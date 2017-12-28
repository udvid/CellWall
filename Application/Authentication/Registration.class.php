<?php
/**
* Registration class
* checks if a user is authenticated, either logged in or signed up
*/
class Registration
{
	/*----------------------------------------------------------------------------------------------------------------------------*/
	/**
	* Minimum length of password
	*/
	const MIN_PASSWORD_LENGTH = 6;
	/*----------------------------------------------------------------------------------------------------------------------------*/
	/**
	* Minimum length of username
	*/
	const MIN_USERNAME_LENGTH = 4;
	/*----------------------------------------------------------------------------------------------------------------------------*/
	/**
	* Maximum length of username
	*/
	const MAX_USERNAME_LENGTH = 30;
	/*----------------------------------------------------------------------------------------------------------------------------*/
	/**
	* the registry
	*/
    private $Registry;
	/*----------------------------------------------------------------------------------------------------------------------------*/
	/**
	* the registration fields
	*/
    private $Fields = array("username"=>"_username",
							"email"=>"_email",
							"password"=>"_password",
							"confirmedPassword"=>"_confirmedPassword"
							);
	/*----------------------------------------------------------------------------------------------------------------------------*/
	/**
	* signup error checker
	*/
	private $Error = FALSE;
	/*----------------------------------------------------------------------------------------------------------------------------*/
	/**
	* signup error message to be thrown to the user
	*/
	private $ErrorMsg = "";
	/*----------------------------------------------------------------------------------------------------------------------------*/
	
	/**
	* class constructor
	* @return void
	*/
    public function __construct(){
        $registry = GlobalRegistry::getInstance();
        $this->Registry = $registry;
		$this->ProcessRegistrationRequest();
    }
	/*----------------------------------------------------------------------------------------------------------------------------*/
	/**
	* Process the registration request
	*/
	private function ProcessRegistrationRequest(){
		
		/*------------------------Check if all fields are empty------------------------------*/
		
		foreach ($this->Fields as $key=>$name){
			if(!isset($_POST[$name]) || empty($_POST[$name])){
				$this->Error = TRUE;
				$this->ErrorMsg = "All fields are empty!";
			}
		}
		
		$username = Firewall::sanitizeInput($_POST[$this->Fields["username"]]);
		$email = Firewall::sanitizeInput($_POST[$this->Fields["email"]]);
		$password = $_POST[$this->Fields["password"]];
		$confirmedPassword = $_POST[$this->Fields["confirmedPassword"]];
		
		/*-------------------------Is username available?-----------------------------*/
		
		$this->Registry->GetObject("Database")->selectRecord("Users", "*", "Username=?", array($username));
		if($this->Registry->GetObject("Database")->GetRowCount() > 0){
			$this->Error = TRUE;
			$this->ErrorMsg = "Username is not available.";
		}
		
		/*-----------------------Min. and Max. length of username-------------------------------*/
		
		if (strlen($username) < Registration::MIN_USERNAME_LENGTH || strlen($username) > Registration::MAX_USERNAME_LENGTH) {
			$this->Error = TRUE;
			$this->ErrorMsg = "Username should be between 4 and 30 characters.";
		}
		
		/*------------------------Is an acceptable username?------------------------------*/
		
		if(Firewall::validUsername($username) == FALSE){
			$this->Error = TRUE;
			$this->ErrorMsg = "We do not support this username!";
		}
		
		/*------------------------Is email id already in use?------------------------------*/
		
		$this->Registry->GetObject("Database")->selectRecord("Users", "*", "Email=?", array($email));
		if($this->Registry->GetObject("Database")->GetRowCount() > 0){
			$this->Error = TRUE;
			$this->ErrorMsg = "Email is already in use.";
		}
		
		/*-------------------------Is a valid email id?-----------------------------*/
		
		if(Firewall::validEmail($email) == FALSE){
			$this->Error = TRUE;
			$this->ErrorMsg = "Invalid email id!";
		}
		
		/*-------------------------Min. length of password-----------------------------*/
		
		if (strlen($password) < Registration::MIN_PASSWORD_LENGTH) {
			$this->Error = TRUE;
			$this->ErrorMsg = "Password must be minimum 6 characters long.";
		}
		
		/*------------------------Does password match confirmed password?------------------------------*/
		
		if ($password !== $confirmedPassword) {
			$this->Error = TRUE;
			$this->ErrorMsg = "Passwords do not match.";
		}
		
		if($this->Error == FALSE){
			$hashedPassword = password_hash($password, PASSWORD_BCRYPT);
			if(password_verify($password, $hashedPassword)){
				$dbFields = array("AccountId", "Username", "Password", "Email");
				$accountId = "UDV" . substr(str_shuffle("0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ-_"), 0, 5) . time();
				$params = array($accountId, $username, $hashedPassword, $email);
				$checkOK1 = $this->Registry->GetObject("Database")->insertRecord("Users", $dbFields, $params);
				if($checkOK1 == TRUE){
					$_SESSION["UserId"] = $username;
					$_SESSION["LoginId"] = $username . Firewall::generateUniqueID(32) . time();
					$_SESSION["LoginIP"] = $_SERVER["REMOTE_ADDR"];
					$_SESSION["LoginUA"] = $_SERVER["HTTP_USER_AGENT"];
					$_SESSION["CSRFToken"] = Firewall::generateUniqueID(64);
					$_SESSION["NewUser"] = $username;
					$checkOK2 = $this->Registry->GetObject("Database")->insertRecord("Login", array("AuthId","LoginId","LoginIP","LoginUA","CSRFToken"), array($_SESSION["UserId"], $_SESSION["LoginId"],$_SESSION["LoginIP"], $_SESSION["LoginUA"], $_SESSION["CSRFToken"]));
					if($checkOK2 == TRUE){
						$this->Error = FALSE;
					}
				}
			}
		}
	}
	/*----------------------------------------------------------------------------------------------------------------------------------*/
	/**
	* Rediect from registration page after successful registration
	*/
	public function Redirect(){
		header("Location: /settings");
	}
	/*----------------------------------------------------------------------------------------------------------------------------------*/
	/**
	* Error message after an unsuccessful request
	*/
	public function GetErrorMsg(){
		return $this->ErrorMsg;
	}
	/*----------------------------------------------------------------------------------------------------------------------------------*/
	/**
	* Check if an error exists
	*/
	public function HasError(){
		return $this->Error;
	}
	
	
}
?>