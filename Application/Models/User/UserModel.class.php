<?php
/**
* Database table : Users
* Fields : Id(PK), AccountId, Role, Designation, Username, Firstname, Lastname, Email, Sex, Website, ProfileImage, ProfileImageThumbnail, LastActive, Blocked, JoinTime, EmailVerified
*/
class UserModel extends AppModel
{
	/*-----------------------------------------------------------------------------------------------------------------*/
	private $userId;
	private $authUser;
	private $blocked;
	private $role;
	private $designation;
	/*-----------------------------------------------------------------------------------------------------------------*/
	public function __construct($userId=NULL){
		parent::__construct();
		if(empty($userId)){
			$this->getUserList();
		}
		else{
			$this->userId = $userId;
        	$this->fetchUserData();
		}
	}
	/*-----------------------------------------------------------------------------------------------------------------*/
	private function fetchUserData(){
		// syntax : selectRecord($table,$fields[,$condition[,$params[,$order[,$limit]]]])
		// User will be retreived by either account id or username or email.
		$this->Registry->GetObject("Database")->SelectRecord("Users", "*", "(Username=? OR Email=? OR AccountId=?) AND Active='TRUE'",array($this->userId, $this->userId, $this->userId));
		if($this->Registry->GetObject("Database")->GetRowCount() === 1){
            $this->Valid = TRUE;
			$this->Data = $this->Registry->GetObject("Database")->FetchResultData();
			$this->blocked = $this->Data["Blocked"];
			$this->role = $this->Data["Role"];
			$this->designation = $this->Data["Designation"];
		}
        else{
            $this->Valid = FALSE;
        }
	}
	/*-----------------------------------------------------------------------------------------------------------------*/
	public function getAccountId(){
		return $this->GetData("AccountId");
	}
	/*-----------------------------------------------------------------------------------------------------------------*/
	public function getFullname(){
		if(empty($this->GetData("Firstname")) || empty($this->GetData("Lastname"))){
			return $this->GetData("Username");
		}
		else{
			return $this->GetData("Firstname") . " " . $this->GetData("Lastname");
		}
	}
	/*-----------------------------------------------------------------------------------------------------------------*/
	public function isAuthUser(){
		if($this->Registry->GetObject("Auth")->getAuthUserData("Username") === $this->GetData("Username")){
			return TRUE;
		}
		else{
			return FALSE;
		}
	}
	/*-----------------------------------------------------------------------------------------------------------------*/
	public function isBlocked(){
	    return $this->blocked;
	}
	/*-----------------------------------------------------------------------------------------------------------------*/
    public function isActive(){
        
    }
	/*-----------------------------------------------------------------------------------------------------------------*/
	public function isAdmin(){
		if($this->role === "Admin"){
			return TRUE;
		}
		else{
			return FALSE;
		}
	}
	/*-----------------------------------------------------------------------------------------------------------------*/
	public function isMember(){
		if($this->role === "Member"){
			return TRUE;
		}
		else{
			return FALSE;
		}
	}
	/*-----------------------------------------------------------------------------------------------------------------*/
	public function isEditor(){
		if($this->role === "Member" && $this->designation === "Editor"){
			return TRUE;
		}
		else{
			return FALSE;
		}
	}
	/*-----------------------------------------------------------------------------------------------------------------*/
	private function getUserList(){
		$condition = "Role!='Admin'";
		$this->Registry->GetObject("Database")->SelectRecord("Users", "*", $condition);
		if($this->Registry->GetObject("Database")->GetRowCount() > 0){
            $this->Result = TRUE;
			$this->ResultCount = $this->Registry->GetObject("Database")->GetRowCount();
		}
		else{
			$this->Result = FALSE;
		}
	}
    
}
?>