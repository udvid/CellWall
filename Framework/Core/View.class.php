<?php

/**
*------------------------------------------------------------------------------------
* View Engine
*------------------------------------------------------------------------------------
*/

class View
{
	/*-----------------------------------------------(-PROPERTIES-)------------------------------------------*/
	
	/**
	* The model to call
	*/
	private static $Model;
	/*-------------------------------------------------------------------------------------------------------*/
	/**
	* Sometimes multiple models needed
	*/
	private static $Models = array();
	/*-------------------------------------------------------------------------------------------------------*/
	/**
	* Data returned from a resultSet will be stored
	* To be used inside looping html content
	*/
	public static $DataSet = array();
	/*-------------------------------------------------------------------------------------------------------*/
	/**
	* The langugae to be used in the webpage
	*/
	private static $Lang = "";
	/*-------------------------------------------------------------------------------------------------------*/
	/**
	* The charSet to be used in the webpage
	*/
	private static $CharSet = "utf-8";
	/*-------------------------------------------------------------------------------------------------------*/
	/**
	* The dynamic keywords to be used in the webpage
	*/
	private static $Keywords = "";
	/*-------------------------------------------------------------------------------------------------------*/
	/**
	* The dynamic description to be used in the webpage
	*/
	private static $Description = "";
	/*-------------------------------------------------------------------------------------------------------*/
	/**
	* The robot direction
	*/
	private static $RobotDirection = "";
	/*-------------------------------------------------------------------------------------------------------*/
	/**
	* The dynamic title
	*/
	private static $Title = "";
	/*-------------------------------------------------------------------------------------------------------*/
	/**
	* The external CSS files
	*/
	private static $Styles = "";
	/*-------------------------------------------------------------------------------------------------------*/
	/**
	* The external JS files
	*/
	private static $Scripts = "";
	/*-------------------------------------------------------------------------------------------------------*/
	/**
	* The website header file
	*/
	private static $HeaderFile = "";
	/*-------------------------------------------------------------------------------------------------------*/
	/**
	* The data variables to Set in the webpage
	*/
	private static $Vars = array();
	/*-------------------------------------------------------------------------------------------------------*/
	/**
	* Array type data returned from model will be stored
	* To be used inside looping html content
	*/
	private static $Items = array();
	/*-------------------------------------------------------------------------------------------------------*/
	/**
	* The Partial files
	*/
	private static $Partials = array();
	/*-------------------------------------------------------------------------------------------------------*/
	/**
	* The website footer file
	*/
	private static $FooterFile = "";
	/*-------------------------------------------------------------------------------------------------------*/
	/**
	* The HTML snippets
	*/
	private static $Html = array();
	/*-------------------------------------------------------------------------------------------------------*/
	/**
	*
	*/
	private static $IsStatus = array();
	/*-------------------------------------------------------------------------------------------------------*/
	/**
	*
	*/
	private static $HasStatus = array();
	/*-------------------------------------------------------------------------------------------------------*/
	/**
	*
	*/
	private static $CanStatus = array();
	/*-------------------------------------------------------------------------------------------------------*/
	/**
	* A security flag
	*/
	private static $Secure = FALSE;
	
	
	/*
	|>>>>----------------------------------------------------------------------------------------------------->
	|
	|-------------------------------------(--METHODS--)-------------------------------------------------------|
	|
	|>>>>----------------------------------------------------------------------------------------------------->
	*/
	
	/*-----------------------------------------------------------------------------------------------------------*/
	private function __construct(){}
	private function __clone(){}
	/*-----------------------------------------------------------------------------------------------------------*/
	
	/**
	* Registry
	*/
	private static function Registry(){
		return Registry::GetInstance();
	}
	/*---------------------------------------------------------------------------------------------------------*/
	
	/*
	|
	====================================<--SetTERS-->========================================================|||
	|
	*/
	
	
	/*---------------------------------------------------------------------------------------------------------*/
	/**
	* Set a single model
	*/
	public static function SetModel($model){
		self::$Model = $model;
	}
	/*---------------------------------------------------------------------------------------------------------*/
	/**
	* Set multiple models into models array
	*/
	public static function SetModels($models = array()){
		foreach($models as $key=>$value){
			self::$Models[$key] = $value;
		}
	}
	/*---------------------------------------------------------------------------------------------------------*/
	/**
	* Set the keywords of the webpage
	* @return void;
	*/
	public static function SetKeywords($keywords){
		self::$Keywords = $keywords;
	}
	/*---------------------------------------------------------------------------------------------------------*/
	/**
	* Set the description of the webpage
	* @return void
	*/
	public static function SetDescription($description){
		self::$Description = $description;
	}
	/*---------------------------------------------------------------------------------------------------------*/
	/**
	* Set the title of the webpage
	* @return void
	*/
	public static function SetTitle($title){
		self::$Title = $title;
	}
	/*---------------------------------------------------------------------------------------------------------*/
	/**
	* Set robot direction
	@return string the robot direction
	*/
	public static function SetRobotDirection(){
		if (self::$isSecure() === TRUE){
			return "noindex, nofollow";
		}
		else{
			return "";
		}
	}
	/*---------------------------------------------------------------------------------------------------------*/
	/**
	* Set the security flag
	* @return void
	*/
	public static function SetSecure(){
		self::$Secure = TRUE;
	}
	/*---------------------------------------------------------------------------------------------------------*/
	/**
	* Check if this page is totally secure that it can not be viewed without logging in
	* @return boolean the security status
	*/
	public static function IsSecure(){
		return self::$Secure;
	}
	/*---------------------------------------------------------------------------------------------------------*/
	/**
	* Set a boolean flag to avaiblity of a content
	*/
	public static function SetHas($key, $value){
		self::$HasStatus[$key] = $value;
	}
	/*---------------------------------------------------------------------------------------------------------*/
	/**
	* Set a boolean flag if something is that
	*/
	public static function SetIs($key, $value){
		self::$IsStatus[$key] = $value;
	}
	/*---------------------------------------------------------------------------------------------------------*/
	/**
	* Set a boolean flag if something is that
	*/
	public static function SetCan($key, $value){
		self::$CanStatus[$key] = $value;
	}
	/*---------------------------------------------------------------------------------------------------------*/
	/**
	* Set the CSS files
	* return void
	*/
	public static function SetStyles(...$files){
		foreach($files as $ile){
			self::$Styles .= "<link rel=\"stylesheet\" href=\"" . Config::CSS_PATH . $file . ".css\">" . "\n";
		}
	}
	/*---------------------------------------------------------------------------------------------------------*/
	/**
	* Set the JS files
	* @return void
	*/
	public static function SetScripts(...$files){
		foreach($files as $file){
			self::$Scripts .= "<script type=\"text/javascript\" src=\"" . Config::JS_PATH . $file . ".js\"></script>" . "\n";
		}
	}
	/*---------------------------------------------------------------------------------------------------------*/
	/**
	* Set the header file for the webpage
	* @return void
	*/
	public static function SetHeader($file = NULL){
		if(empty($file)){
			self::$HeaderFile = ROOT . Config::PARTIAL_VIEW_PATH . "Header.php";
		}
		else{
			self::$HeaderFile = ROOT . Config::PARTIAL_VIEW_PATH . $file . ".php";
		}
	}
	/*---------------------------------------------------------------------------------------------------------*/
	/**
	* Add a new variable to the variables array
	* @var key the variable key
	* @var value the value of the variable
	* @var escape the boolean flag to specify whether the characters in the variable must be escaped
	* @return void;
	*/
	public static function SetVar($key, $value, $escape = TRUE){
		if ($escape === FALSE){
			self::$Vars[$key] = $value;
		}
		else{
			self::$Vars[$key] = Firewall::EscapeChars($value);
		}
	}
	/*---------------------------------------------------------------------------------------------------------*/
	/**
	* Add a new html entity encoded variable to the variables array with
	* @var key the variable key
	* @var value the value of the variable
	* @var escape the boolean flag to specify whether the characters in the variable must be escaped
	* @return void;
	*/
	public static function SetEntVar($key, $value){
		self::$Vars[$key] = htmlentities($value, ENT_QUOTES);
	}
	/*---------------------------------------------------------------------------------------------------------*/
	/**
	* Add bulk new variables to the variables array
	* @var vars the array of new variables
	* @var escape the boolean flag to specify whether the characters in the variables must be escaped
	* @return void
	*/
	public static function SetVars($Vars = array(), $escape = TRUE){
		foreach ($Vars as $key=>$value){
			if ($escape === FALSE){
				self::$Vars[$key] = $value;
			}
			else{
				self::$Vars[$key] = Firewall::EscapeChars($value);
			}
		}
	}
	/*---------------------------------------------------------------------------------------------------------*/
	/**
	* Add a new array type item
	*/
	public static function SetItems($key, $value){
		self::$Items[$key] = $value;
	}
	/*---------------------------------------------------------------------------------------------------------*/
	/**
	* Set a file to be Partiald for a section in the webpage
	* @return void
	*/
	public static function SetPartial($key, $file){
		self::$Partials[$key] = ROOT . Config::PARTIAL_VIEW_PATH . $file;
	}
	/*---------------------------------------------------------------------------------------------------------*/
	/**
	* Add multiple files to the Partials array
	* @return void
	*/
	public static function SetPartials($files = array()){
		foreach ($files as $key=>$value){
			self::$Partials[$key] = ROOT . Config::PARTIAL_VIEW_PATH . $value;
		}
	}
	/*---------------------------------------------------------------------------------------------------------*/
	/**
	* Set the webpage footer file
	* @return void
	*/
	public static function SetFooter($file = NULL){
		if(empty($file)){
			self::$FooterFile = ROOT . Config::PARTIAL_VIEW_PATH . "Footer.php";
		}
		else{
			self::$FooterFile = ROOT . Config::PARTIAL_VIEW_PATH . $file . ".php";
		}
	}
	/*---------------------------------------------------------------------------------------------------------*/
	/**
	* Set HTML snipet
	* @return void
	*/
	public static function SetHTML($key, $Html){
		self::$Html[$key] = $Html;
	}
	/*
	-----------------------------------------------------------------------------------------------------------
	--------------------------<-RETURN ME WHAT I WANT, PLEASE!->-----------------------------------------------
	-----------------------------------------------------------------------------------------------------------
	*/
	/**
	* Get single model
	*/
	public static function Model(){
		return self::$Model;
	}
	/*---------------------------------------------------------------------------------------------------------*/
	/**
	* Get a model from models array
	*/
	public static function Models($key){
		return self::$Models[$key];
	}
	/*---------------------------------------------------------------------------------------------------------*/
	/**
	* Get the language of the webpage
	* @return string the language
	*/
	public static function Lang(){
		return self::$Lang;
	}
	/*---------------------------------------------------------------------------------------------------------*/
	/**
	* Get the charSet of the webpage
	* @return string the charSet
	*/
	public static function CharSet(){
		return self::$CharSet;
	}
	/*---------------------------------------------------------------------------------------------------------*/
	/**
	* Get the keywords of the webpage
	* @return string the keywords
	*/
	public static function Keywords(){
		return self::$Keywords;
	}
	/*---------------------------------------------------------------------------------------------------------*/
	/**
	* Get the description of the webpage
	* @return string the description
	*/
	public static function Description(){
		return self::$Description;
	}
	/*---------------------------------------------------------------------------------------------------------*/
	/**
	* Get the robot direction
	* @return string the direction
	*/
	public static function Robot(){
		return self::$RobotDirection;
	}
	/*---------------------------------------------------------------------------------------------------------*/
	/**
	* Get the title of the webpage
	* return the title
	*/
	public static function Title(){
		return self::$Title;
	}
	/*---------------------------------------------------------------------------------------------------------*/
	/**
	* Get the external CSS files
	* Return html string the CSS files
	*/
	public static function Styles(...$files){
		if (empty($files)){
			return self::$Styles;
		}
		else{
			foreach($files as $file){
				self::$Styles .= "<link rel=\"stylesheet\" href=\"" . Config::CSS_PATH . $file . ".css\">" . "\n";
			}
			return self::$Styles;
		}
	}
	/*---------------------------------------------------------------------------------------------------------*/
	/**
	* Get the external JS files
	* Return html string the JS files
	*/
	public static function Scripts(...$files){
		if (empty($files)){
			return self::$Scripts;
		}
		else{
			foreach($files as $file){
				self::$Scripts .= "<script type=\"text/javascript\" src=\"" . Config::JS_PATH . $file . ".js\"></script>" . "\n";
			}
			return self::$Scripts;
		}
	}
	/*---------------------------------------------------------------------------------------------------------*/
	/**
	* Get the webpage header file
	* @return string the file url
	*/
	public static function HeaderFile($file = NULL){
		if (empty($file) && empty(self::$HeaderFile)){
			return ROOT . Config::PARTIAL_VIEW_PATH . "Header.php";
		}
		elseif (empty($file) && !empty(self::$HeaderFile)){
			return self::$HeaderFile;
		}
		elseif (!empty($file) && empty(self::$HeaderFile)){
			return ROOT . Config::PARTIAL_VIEW_PATH . $file . ".php";
		}
		elseif (!empty($file) && !empty(self::$HeaderFile)){
			return ROOT . Config::PARTIAL_VIEW_PATH . $file . ".php";
		}
	}
	/*---------------------------------------------------------------------------------------------------------*/
	/**
	* Get a variable
	* @var key the key of the variable
	* @return string the value of the variable
	*/
	public static function Vars($key){
		if(array_key_exists($key, self::$Vars)){
			return self::$Vars[$key];
		}
		else{
			return "";
		}
	}
	/*---------------------------------------------------------------------------------------------------------*/
	/**
	* Return an escaped data for output
	*/
	public static function Escape($data){
		return Firewall::EscapeChars($data);
	}
	/*---------------------------------------------------------------------------------------------------------*/
	/**
	* Get an item from array items
	*/
	public static function Items($key){
		return self::$Items[$key];
	}
	/*---------------------------------------------------------------------------------------------------------*/
	/**
	* Get a data from dataSet
	* Mainly to use inside a looping html content
	* @var key the key of the dataSet
	* @return mixed the value of data
	*/
	public static function Data($key, $escape = TRUE){
		if(array_key_exists($key, self::$DataSet)){
			if($escape === FALSE){
				return self::$DataSet[$key];
			}
			else{
				return Firewall::EscapeChars(self::$DataSet[$key]);
			}
		}
		else{
			return "";
		}
	}
	/*---------------------------------------------------------------------------------------------------------*/
	/**
	* Get a file to Partial
	* @var key the key of the file
	* @return string the url of the file
	*/
	public static function Partial($file){
		return ROOT . Config::PARTIAL_VIEW_PATH . $file . ".inc.php";
	}
	/*---------------------------------------------------------------------------------------------------------*/
	/**
	* Get the webpage footer file
	* @return string the file url
	*/
	public static function FooterFile($file = NULL){
		if (empty($file) && empty(self::$FooterFile)){
			return ROOT . Config::PARTIAL_VIEW_PATH . "Footer.php";
		}
		elseif (empty($file) && !empty(self::$FooterFile)){
			return self::$FooterFile;
		}
		elseif (!empty($file) && empty(self::$FooterFile)){
			return ROOT . Config::PARTIAL_VIEW_PATH . $file . ".php";
		}
		elseif (!empty($file) && !empty(self::$FooterFile)){
			return ROOT . Config::PARTIAL_VIEW_PATH . $file . ".php";
		}
	}
	/*---------------------------------------------------------------------------------------------------------*/
	/**
	* Get an HTML snippet
	* @return string the html snippet
	*/
	public static function Html($key){
		return self::$Html[$key];
	}
	
	/**
	--------------------------------------------------------------------------------------------
	* Some special and frequently used methods to easily use in the views and view-controllers
	--------------------------------------------------------------------------------------------
	*/
	
	/**
	* Get a path
	*/
	public static function Path($need){
		switch ($need){
			case "Image":
			return Config::IMAGE_PATH;
			break;
			
			case "Css":
			return Config::CSS_PATH;
			break;
			
			case "Js":
			return Config::JS_PATH;
			break;
			
			case "Partial":
			return ROOT . Config::PARTIAL_VIEW_PATH;
			break;
			
			case "Page":
			return ROOT . Config::MAIN_VIEW_PATH;
			break;
			
			case "Extra":
			return ROOT . Config::EXTRA_PATH;
			break;
			
			case "Error":
			return ROOT . Config::ERROR_VIEW_PATH;
			break;
			
			default:
			return ROOT . Config::MAIN_VIEW_PATH;
		}
	}
	/*---------------------------------------------------------------------------------------------------------*/
	/**
	* Check if the model has a resource
	*/
	public static function Has($key){
		return self::$HasStatus[$key];
	}
	/*---------------------------------------------------------------------------------------------------------*/
	/**
	* Check if a model property is in a specific state
	*/
	public static function Is($key){
		return self::$IsStatus[$key];
	}
	/*---------------------------------------------------------------------------------------------------------*/
	/**
	* Check if a model property is in a specific state
	*/
	public static function Can($key){
		return self::$CanStatus[$key];
	}
	/*---------------------------------------------------------------------------------------------------------*/
	public static function Fetch(){
		return self::Registry()->GetObject("Database")->FetchResultData();
	}
	
	/**
	----------------------------------------------------------------------------------------------------------
	* In the views, we may need authentication informations
	----------------------------------------------------------------------------------------------------------
	*/
	
	/**
	* Get the base request (The first section of the URL path)
	*/
	public static function BaseRequest(){
		return Request::GetBase();
	}
	/*---------------------------------------------------------------------------------------------------------*/
	/**
	* Number of sections in the URL path
	*/
	public static function RequestSectionCount(){
		return RouteManager::CountRouteSections();
	}
	/*---------------------------------------------------------------------------------------------------------*/
	/**
	* Check if the user is logged in
	* @return bool the login status
	*/
	public static function Loggedin(){
		return self::Registry()->GetObject("Auth")->IsLoggedin();
	}
	/*---------------------------------------------------------------------------------------------------------*/
	/**
	* Get acount unique id
	* @return string the account id
	*/
	public static function AccountId(){
		return self::Registry()->GetObject("Auth")->GetAccountId();
	}
	/*---------------------------------------------------------------------------------------------------------*/
	/**
	* Get csrf token
	* @return string the token
	*/
	public static function CsrfToken(){
		return self::Registry()->GetObject("Auth")->GetCSRFToken();
	}
	/*---------------------------------------------------------------------------------------------------------*/
	/**
	* Get username of loggedin user
	* @return string the username
	*/
	public static function Username(){
		return self::AuthData("Username");
	}
	/*---------------------------------------------------------------------------------------------------------*/
	/**
	* Get image of loggedin user
	* @return string the image url
	*/
	public static function UserImage(){
		return self::AuthData("ProfileImage");
	}
	/*---------------------------------------------------------------------------------------------------------*/
	/**
	* Get image thumbnail of loggedin user
	* @return string the image url
	*/
	public static function UserThumb(){
		return self::AuthData("ProfileImageThumbnail");
	}
	/*---------------------------------------------------------------------------------------------------------*/
	/**
	* Get type of loggedin user
	* @return string the user type
	*/
	public static function UserType(){
		return self::Registry()->GetObject("Auth")->GetUserType();
	}
	/*---------------------------------------------------------------------------------------------------------*/
	/**
	* Get loggedin user data
	* @return string the data
	*/
	public static function AuthData($key){
		$data = self::Registry()->GetObject("Auth")->GetAuthUserData($key);
		return Firewall::EscapeChars($data);
	}
	/*----------------------------------------------------------------------------------------------------------*/
	public static function FounderId(){
		return "TR7AGJH1470800972";
	}
}

?>