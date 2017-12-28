<?php
/**
* Data Validation Class
* Will validate incoming data from user
*/

class Validation
{
	/**
	* Validation error report collection
	*/
	private static $ValidationReport = array();
	/*-----------------------------------------------------------------------------------------------------------------*/
	/**
	* Pass data as: array("Name"=>name, "Value"=>value, "ErrorMessage"=>errorMessage)
	* Name is the "Name" attribute of the form field
	*/
	public static function Required(array $data){
		if (empty($data["Value"])){
			self::$ValidationReport[$data["Name"]] = $data["ErrorMessage"];
		}
	}
	/*-----------------------------------------------------------------------------------------------------------------*/
	/**
	* Pass data as: array("Name"=>name, "Value"=>value, "Pattern"=>regexPattern, "ErrorMessage"=>errorMessage)
	* Name is the "Name" attribute of the form field
	*/
	public static function Regex(array $data){
		if (!preg_match($data["Pattern"], $data["Value"])){
			self::$ValidationReport[$data["Name"]] = $data["ErrorMessage"];
		}
	}
	/*-----------------------------------------------------------------------------------------------------------------*/
	/**
	* Pass data as: array("Name"=>name, "Value"=>value, "Type"=>type, "ErrorMessage"=>errorMessage)
	* Name is the "Name" attribute of the form field
	*/
	public static function Type(array $data){
		if (gettype($data["Value"]) !== $data["Type"]){
			self::$ValidationReport[$data["Name"]] = $data["ErrorMessage"];
		}
	}
	/*-----------------------------------------------------------------------------------------------------------------*/
	public static function Maximum($data, $maxVal){
		
	}
	/*-----------------------------------------------------------------------------------------------------------------*/
	public static function Minimum($data, $minVal){
	}
	/*-----------------------------------------------------------------------------------------------------------------*/
	/**
	* Pass data as: array("Name"=>name, "Password"=>password, "Hash"=>hashedPassword, "ErrorMessage"=>errorMessage)
	* Name is the "Name" attribute of the form field
	*/
	public static function Password(array $data){
		if (!password_verify($data["Password"], $data["Hash"])) {
			self::$ValidationReport[$data["Name"]] = $data["ErrorMessage"];
		}
	}
	/*-----------------------------------------------------------------------------------------------------------------*/
	public static function ValidationReport(){
		return self::$ValidationReport;
	}
	/*------------------------------------------------------------------------------------------------------------------*/
}
?>