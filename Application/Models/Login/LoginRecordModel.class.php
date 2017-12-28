<?php
/**
* Database table : LoginRecords
* Fields : Id(PK), IP, AttemptCount, LastAttemptTime
*/
class LoginRecordModel extends AppModel
{
	private $Table = "LoginRecords";
	private $AuthId;
	private $LoginId;
	private $LoginIP;
	private $LoginUA;
	private $CSRFToken;
	
	public function __construct(){
		parent::__construct();
	}
	
	public function Select($authId, $loginId, $loginIP, $loginUA, $csrfToken){
		$this->Registry->GetObject("Database")->Select($this->Table, "*", array("AuthId", "LoginId", "LoginIP", "LoginUA", "CSRFToken"), array($authId, $loginId, $loginIP, $loginUA, $csrfToken));
		if($this->Registry->GetObject("Database")->GetRowCount() === 1){
            $this->Valid = TRUE;
			$this->Data = $this->Registry->GetObject("Database")->FetchResultData();
		}
        else{
            $this->Valid = FALSE;
        }
	}
	
	public function RecordExists($username, $email){
		$this->Registry->GetObject("Database")->SelectRecord($this->Table, "*", "AuthId=? OR AuthId=?", array($username, $email));
		if($this->Registry->GetObject("Database")->GetRowCount() > 0){
			return TRUE;
		}
		else{
			return FALSE;
		}
	}
	
	public function Insert($authId, $loginId, $loginIP, $loginUA, $csrfToken){
		return $this->Registry->GetObject("Database")->InsertRecord($this->Table, array("AuthId", "LoginId", "LoginIP", "LoginUA", "CSRFToken"), array($authId, $loginId, $loginIP, $loginUA, $csrfToken));
	}
	
	public function Delete($username, $email){
		if ($this->RecordExists($username, $email)){
			return $this->Registry->GetObject("Database")->DeleteRecord($this->Table, "AuthId=? OR AuthId=?", array($username, $email));
		}
	}
	
}
?>