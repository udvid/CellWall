<?php

/**
* Login class
* Processes user's login request
*/
/*--------------------------------------------------------------------------------------------------------------------------------------*/

class Login
{
	/*----------------------------------------------------------------------------------------------------------------------------------*/
	/**
	* the registry
	*/
    private $Registry;
	/*----------------------------------------------------------------------------------------------------------------------------------*/
	/**
	* the authentication data - username or email
	*/
	private $AuthData;
	/*----------------------------------------------------------------------------------------------------------------------------------*/
	/**
	* the password
	*/
	private $Password;
	/*----------------------------------------------------------------------------------------------------------------------------------*/
	/**
	* the webpage which will be returned after login
	*/
	private $ReturnPage;
	/*----------------------------------------------------------------------------------------------------------------------------------*/
	/**
	* login error message to be thrown to the user
	*/
    private $ErrorMsg = "";
	/*----------------------------------------------------------------------------------------------------------------------------------*/
	/**
	* Error status
	*/
    private $Error = FALSE;
	/*----------------------------------------------------------------------------------------------------------------------------------*/
	/**
	* Login Attempt
	*/
	private $LoginAttempt;
	/*----------------------------------------------------------------------------------------------------------------------------------*/
	/**
	* Login data
	*/
	private $LoginRecord;
	
	
	/**
	* class constructor
	* @return void
	*/
    public function __construct($authData, $password, $returnPage){
        $registry = Registry::GetInstance();
        $this->Registry = $registry;
		$this->LoginAttempt = new LoginAttemptModel();
		$this->LoginRecord = new LoginRecordModel();
		
		$this->AuthData = $authData;
		$this->Password = $password;
		$this->ReturnPage = $returnPage;
		
		$this->ProcessLoginRequest();
    }
	/*----------------------------------------------------------------------------------------------------------------------------------*/
	/**
	* process login form request
	* if entered correct credentials by user, a new session is started
	* if not, login error message is thrown
	* @param String $authData either username or email
	* @param Str or int $password the password
	* @param String $returnPage the page which the user will be redirected to after log in
	* @access private
	* @return void
	*/
	private function ProcessLoginRequest(){		
	    if(!empty($this->AuthData) && !empty($this->Password)){
			if($this->LoginAttempt->IsValid()){
				$attemptCount = $this->LoginAttempt->GetAttemptCount();
				$lastAttemptTime = $this->LoginAttempt->GetLastAttemptTime();
				$period = 1800;
				$timeString = intval($period/60) . " minutes";
				if($attemptCount == 5){
					//if period time is not over
					if($this->LoginAttempt->GetLastAttemptTime() > time() - $period){
						$this->ErrorMsg = "You have exeeded max. numbers of attempts. Try again after {$timeString}.";
						$this->Error = TRUE;
					}
					else{
						$this->LoginAttempt->Reset();
						$this->VerifyLoginFormData();
					}
				}
				else{
					$this->VerifyLoginFormData();
				}
			}
			else{
				$this->VerifyLoginFormData();
			}
	   }
	   else{
		   $this->ErrorMsg = "Enter Username and Password.";
		   $this->Error = TRUE;
	   }
	}
	/*----------------------------------------------------------------------------------------------------------------------------------*/
	/**
	* Verify login data
	*/
	private function VerifyLoginFormData(){
		$requestedUser = new UserModel($this->AuthData);
		if($requestedUser->IsValid()){
			$username = $requestedUser->GetData("Username");
			$email = $requestedUser->GetData("Email");
			$hashedPassword = $requestedUser->GetData("Password");
			$blocked = $requestedUser->GetData("Blocked");
			
			 if (!password_verify($this->Password, $hashedPassword)) {
				 $this->LoginAttempt->Update();
				 $this->ErrorMsg = "Username/Email or Password is invalid";
				 $this->Error = TRUE;
			 }
			 elseif($blocked === "TRUE"){
				 $this->ErrorMsg = "You are BLOCKED to access this website.";
				 $this->Error = TRUE;
			 }
			 else{
				 $this->LoginAttempt->Delete();
				 $authId = $this->AuthData;
				 $loginId = $this->AuthData . Firewall::generateUniqueID(32) . time();
				 $loginIP = $_SERVER["REMOTE_ADDR"];
				 $loginUA = $_SERVER["HTTP_USER_AGENT"];
				 $csrfToken = Firewall::generateUniqueID(64);
				 if ($this->LoginRecord->Delete($username, $email)){
					 if ($this->LoginRecord->Insert($authId, $loginId, $loginIP, $loginUA, $csrfToken)){
						 $this->Error = FALSE;
						 $_SESSION["AuthId"] = $authId;
						 $_SESSION["LoginId"] = $loginId;
						 $_SESSION["LoginIP"] = $loginIP;
						 $_SESSION["LoginUA"] = $loginUA;
						 $_SESSION["CSRFToken"] = $csrfToken;
					 }
				 }
			}
		}
		else{
			$this->LoginAttempt->Update();
			$this->ErrorMsg = "Username/Email or Password is invalid";
			$this->Error = TRUE;
		}
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