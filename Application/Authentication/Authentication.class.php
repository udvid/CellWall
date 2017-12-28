<?php
/**
* Authentication class
* Checks if a user is authenticated, either logged in or signed up
*/
class Authentication
{
	/*-------------------------------------------------------------------------------------------------------------*/
	/**
	* the registry
	*/
    public $Registry ;
	/*-------------------------------------------------------------------------------------------------------------*/
	/**
	* The authenticated user
	*/
	public $AuthUser;
	/*-------------------------------------------------------------------------------------------------------------*/
	/**
	* the authenticated id of the user, either username or email
	*/
    public $AuthId;
	/*-------------------------------------------------------------------------------------------------------------*/
	/**
	* the new login id for a logged in user
	*/
    public $LoginId;
	/*-------------------------------------------------------------------------------------------------------------*/
	/**
	* csrf token for only a session
	*/
	public $CsrfToken;
	/*-------------------------------------------------------------------------------------------------------------*/
	/**
	* login status
	*/
    public $Authenticated = FALSE;
	/*-------------------------------------------------------------------------------------------------------------*/
	/**
	* Authentication error
	*/
	public $Error = FALSE;
	/*-------------------------------------------------------------------------------------------------------------*/
	/**
	* Authentication error message
	*/
	public $ErrorMsg = "";
	/*-------------------------------------------------------------------------------------------------------------*/
	/**
	* block status
	*/
    public $Blocked = FALSE;
	/*-------------------------------------------------------------------------------------------------------------*/
	/**
	* active status
	*/
    public $Active = TRUE;
	/*-------------------------------------------------------------------------------------------------------------*/
	/**
	* just logged in status
	*/
	public $JustLoggedin = FALSE;
	/*-------------------------------------------------------------------------------------------------------------*/
	
	
	/**
	* class constructor
	* @return void
	*/
    public function __construct(){
        $registry = Registry::GetInstance();
        $this->Registry = $registry;
    }
	
	/*----------------------------------------------------------------------------------------------------------------------------------*/
	/**
	* Checks if user is authenticated
	* Checks Session | Login | Registration
	* @return void
	*/
	public function CheckAuthentication(){
        if(isset($_SESSION["AuthId"])){
			$this->AuthenticateSession();
			if($this->HasError()){
				$this->Authenticated = FALSE;
			}
			else{
				$this->Authenticated = TRUE;
				$this->AuthId = $_SESSION["AuthId"];
				$this->LoginId = $_SESSION["LoginId"];
				$this->CsrfToken = $_SESSION["CSRFToken"];
				$this->AuthUser = new UserModel($this->AuthId);
			}
		}
    }
	/*----------------------------------------------------------------------------------------------------------------------------------*/
	/**
	* Session authentication checkup
	* if valid session then user is logged in, other way destroys session
	* @param String $userId the username or email
	* @param String $loginId the login token
	* @access private
	* @return void
	*/
    private function AuthenticateSession(){
		$authId = isset($_SESSION["AuthId"]) ? $_SESSION["AuthId"] : "";
		$loginId = isset($_SESSION["LoginId"]) ? $_SESSION["LoginId"] : "";
		$loginIP = isset($_SESSION["LoginIP"]) ? $_SESSION["LoginIP"] : "";
		$loginUA = isset($_SESSION["LoginUA"]) ? $_SESSION["LoginUA"] : "";
		$csrfToken = isset($_SESSION["CSRFToken"]) ? $_SESSION["CSRFToken"] : "";
		
		if(strlen($csrfToken) === 64){
			$loginRecord = new LoginRecordModel();
			$loginRecord->Select($authId, $loginId, $loginIP, $loginUA, $csrfToken);
			if($loginRecord->IsValid()){
				$user = new UserModel($authId);
				if($user->IsValid()){
					if($user->getData("Blocked") === "TRUE"){
						$this->Error = TRUE;
						$this->Blocked = TRUE;
					}
					else{
						$this->Error = FALSE;
					}
				}
			}
			else{
				$this->Error = TRUE;
				unset($authId);
				unset($loginId);
				unset($loginIP);
				unset($loginUA);
				unset($csrfToken);
				session_unset();
				session_destroy();
				header("Location: /login");
				exit();
			}
		}
		else{
			$this->Error = TRUE;
			unset($authId);
			unset($loginId);
			unset($loginIP);
			unset($loginUA);
			unset($csrfToken);
			session_unset();
			session_destroy();
			header("Location: /login");
			exit();
		}
    }
	
	/*-------------------------------------------------------------------------------------------------------------*/
	/**
	* Check if authentication has any error
	* @return void
	*/
	public function HasError(){
	    return $this->Error;
	}
	/*-------------------------------------------------------------------------------------------------------------*/
	/**
	* gets authentication error message
	* @return void
	*/
	public function GetErrorMsg(){
	    return $this->ErrorMsg;
	}
	/*-------------------------------------------------------------------------------------------------------------*/
	/**
	* checks if a user logged in just now
	* @return bool
	*/
	public function IsJustLoggedin(){
		return $this->JustLoggedin;
	}
	/*-------------------------------------------------------------------------------------------------------------*/
	/**
	* @return bool login status
	*/
    public function IsLoggedin(){
        return $this->Authenticated;
    }
	/*-------------------------------------------------------------------------------------------------------------*/
	/**
	* Gets authenticated users record
	* @param String $key associative array index
	* @return array the users data
	*/
	public function GetAuthUserData($key){
		return $this->AuthUser->GetData($key);
	}
	/*-------------------------------------------------------------------------------------------------------------*/
	/**
	* @return String the username
	*/
	public function GetUsername(){
		return $this->GetAuthUserData("Username");
	}
	/*-------------------------------------------------------------------------------------------------------------*/
	/**
	* @return String the user account id
	*/
	public function GetAccountId(){
		return $this->GetAuthUserData("AccountId");
	}
	/*-------------------------------------------------------------------------------------------------------------*/
	/**
	* @return String the CSRF token
	*/
	public function GetCSRFToken(){
		return $this->CsrfToken;
	}
	/*-------------------------------------------------------------------------------------------------------------*/
	/**
	* @return String the login id
	*/
	public function GetLoginId(){
		return $this->LoginId;
	}
	/*-------------------------------------------------------------------------------------------------------------*/
	/**
	* @return bool role
	*/
	public function IsAdmin(){
		if($this->GetUserType() === "admin"){
			return TRUE;
		}
		else{
			return FALSE;
		}
	}
	/*-------------------------------------------------------------------------------------------------------------*/
	public function IsMember(){
		if($this->GetUserType() === "member"){
			return TRUE;
		}
		else{
			return FALSE;
		}
	}
	/*-------------------------------------------------------------------------------------------------------------*/
	public function IsEditor(){
		if($this->GetUserType() === "member" && $this->GetAuthUserData("Designation") === "Editor"){
			return TRUE;
		}
		else{
			return FALSE;
		}
	}
	/*-------------------------------------------------------------------------------------------------------------*/
	public function GetUserType(){
		return strtolower($this->GetAuthUserData("Role"));
	}
	/*-------------------------------------------------------------------------------------------------------------*/
	/**
	* @return bool block status
	*/
	public function IsBlocked(){
		return $this->Blocked;
	}
	/*-------------------------------------------------------------------------------------------------------------*/
	/**
	* @return String authentication id
	*/
    public function GetAuthId(){
        return $this->AuthId;
    }
	/*-------------------------------------------------------------------------------------------------------------*/
	public function PasswordVerified($userId, $password){
		$user = new UserModel($userId);
		if($user->IsValid()){
			$dbHashedPwd = $user->GetData("Password");
			if(password_verify($password, $dbHashedPwd)){
				return TRUE;
			}
			else{
				return FALSE;
			}
		}
	}
	/*-------------------------------------------------------------------------------------------------------------*/
	/**
	* checks if the user, who is logged in or trying to log in, is blocked by any other user
	* @return bool
	*/
    public function IsBlockedBy($accountId){
        $authAccountId = $this->getAccountId();
	    $this->Registry->GetObject("Database")->SelectRecord("BlockList", "*", "BlockedUser=? AND Username=?", array($authAccountId, $accountId));
        if($this->Registry->GetObject("Database")->GetRowCount() > 0){
            return TRUE;
        }
		else{
			return FALSE;
		}
	}
	
}
?>