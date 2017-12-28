<?php

/**
* Main model class
* will be extended by specific model class
*/
class AppModel
{
	/*-------------------------------------------------------------------------------------------------------*/
	/**
	* the Registry that will be used by model
	* @access protected
	*/
	protected $Registry;
	/*-------------------------------------------------------------------------------------------------------*/
	/**
	* model Data to be fetched from Database
	* @access protected
	*/
	protected $Data;
	/*-------------------------------------------------------------------------------------------------------*/
	/**
	* the Validity status of the model
	* @access protected
	*/
	protected $Valid;
	/*-------------------------------------------------------------------------------------------------------*/
	/**
	* Result existance status against a query
	* @access protected
	*/
	protected $Result;
	/*-------------------------------------------------------------------------------------------------------*/
	/**
	* number of returned Result against a query
	*/
	protected $ResultCount;
	
	
	/*=======================================================================================================*/
	
	
	/**
	* class constructor
	* create an instance of Registry and set it to model
	* @access protected
	* @return void
	*/
	protected function __construct(){
		$registry = Registry::GetInstance();
        $this->Registry = $registry;
	}
	/*-------------------------------------------------------------------------------------------------------*/
	/**
	* after selecting a single record from Database, check Validation and fetch record Data
	* @access protected
	* @return void
	*/
	protected function CheckRecord(){
		if($this->Registry->GetObject("Database")->GetRowCount() === 1){
            $this->Valid = TRUE;
			$this->Data = $this->Registry->GetObject("Database")->FetchResultData();
		}
        else{
            $this->Valid = FALSE;
        }
	}
	/*-------------------------------------------------------------------------------------------------------*/
	/**
	* gets the Validity status of the model
	* @access public
	* @return bool the Validity status
	*/
	public function IsValid(){
		return $this->Valid;
	}
	/*-------------------------------------------------------------------------------------------------------*/
	/**
	* get model Data
	* @param String $key the index of associative array Data fetched from Database
	* @access public
	* @return String the model Data
	*/
	public function GetData($key){
		return $this->Data[$key];
	}
	/*-------------------------------------------------------------------------------------------------------*/
	/**
	* after selecting a record set from Database, check Validation and fetch record Data
	* @access protected
	* @return void
	*/
	protected function CheckRecordset(){
		if($this->Registry->GetObject("Database")->GetRowCount() > 0){
            $this->Result = TRUE;
			$this->ResultCount = $this->Registry->GetObject("Database")->GetRowCount();
		}
        else{
            $this->Result = FALSE;
			$this->ResultCount = 0;
        }
	}
	/*-------------------------------------------------------------------------------------------------------*/
	/**
	* get Result status
	* @access public
	* @return the Result status
	*/
	public function HasResult(){
		return $this->Result;
	}
	/*-------------------------------------------------------------------------------------------------------*/
	/**
	* get no. of Result against a query
	*/
	public function GetResultCount(){
		return $this->ResultCount;
	}
}

?>