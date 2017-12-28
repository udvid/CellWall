<?php
/**
* Database table : LoginAttempts
* Fields : Id(PK), IP, AttemptCount, SessionCount, LastAttemptTime
*/
class LoginAttemptModel extends AppModel
{
	/*-----------------------------------------------------------------------------------------------------------------*/
	private $Table = "LoginAttempts";
	private $Ip;
	private $AttemptCount;
	private $LastAttemptTime;
	/*-----------------------------------------------------------------------------------------------------------------*/
	
	public function __construct(){
		parent::__construct();
		$this->Select();
	}
	/*-----------------------------------------------------------------------------------------------------------------*/
	private function Select(){
		//syntax : selectRecord($table,$fields[,$condition[,$params[,$order[,$limit]]]])
		$this->Registry->GetObject("Database")->SelectRecord($this->Table, "*", "IP=?", array($_SERVER["REMOTE_ADDR"]));
		if($this->Registry->GetObject("Database")->GetRowCount() === 1){
            $this->Valid = TRUE;
			$this->Data = $this->Registry->GetObject("Database")->FetchResultData();
			$this->Ip = $this->Data["IP"];
			$this->AttemptCount = $this->Data["AttemptCount"];
			$this->LastAttemptTime = $this->Data["LastAttemptTime"];
		}
        else{
            $this->Valid = FALSE;
        }
	}
	/*-----------------------------------------------------------------------------------------------------------------*/
	public function GetIp(){
		return $this->Ip;
	}
	/*-----------------------------------------------------------------------------------------------------------------*/
	public function GetAttemptCount(){
		return $this->AttemptCount;
	}
	/*-----------------------------------------------------------------------------------------------------------------*/
	public function GetLastAttemptTime(){
		return $this->LastAttemptTime;
	}
	/*----------------------------------------------------------------------------------------------------------------------------------*/
	/**
	* If login credentials are not granted
	* Update login attempt counter
	*/
	public function Update(){
		if($this->IsValid()){
			$attemptCount = $this->GetAttemptCount();
			$attempts = $attemptCount + 1;
			$time = time();
			if($attemptCount == 4){
				$this->Registry->GetObject("Database")->UpdateRecord($this->Table, array("AttemptCount", "LastAttemptTime"), array("IP"), array($attempts, $time, $_SERVER["REMOTE_ADDR"]));
			}
			elseif($attemptCount < 4){
				$this->Registry->GetObject("Database")->UpdateRecord($this->Table, array("AttemptCount"), array("IP"), array($attempts, $_SERVER["REMOTE_ADDR"]));
			}
		}
		else{
			$this->Registry->GetObject("Database")->InsertRecord($this->Table,array("IP","AttemptCount"),array($_SERVER["REMOTE_ADDR"], 1));
		}
	}
	/*----------------------------------------------------------------------------------------------------------------------------------*/
	/**
	* Delete login attempt record
	*/
	public function Delete(){
		if($this->IsValid()){
			$this->Registry->GetObject("Database")->DeleteRecord($this->Table, array("IP"), array($_SERVER["REMOTE_ADDR"]));
		}
	}
    /*-----------------------------------------------------------------------------------------------------------------*/
	public function Reset(){
		if($this->IsValid()){
			$this->Registry->GetObject("Database")->UpdateRecord($this->Table, array("AttemptCount","IP"), array(0, $_SERVER["REMOTE_ADDR"]));
		}
	}
}
?>