<?php
/**
* This is the default file. All the requests will have to go through this file.
*
* ********************************************************
* ********************************************************
* TRY NOT TO EDIT THIS FILE
* ********************************************************
* ********************************************************
*/




/*
* Define Document Root
*/
define("ROOT", $_SERVER["DOCUMENT_ROOT"]);
/*------------------------------------------------------------------------------------------------------------------------*/



/* Include Main Configuration, Configuration Bag and Autoloader File */
require_once ROOT . "/Application/Config/" . "Config.class.php" ;
require_once ROOT . "/Framework/Core/" . "ConfigBag.class.php" ;
require_once ROOT . "/Framework/Core/" . "Autoloader.class.php" ;
/*------------------------------------------------------------------------------------------------------------------------*/


/*
* Controll Error Reporting
* Error is displayed only in Testing Environment
*/

if (ConfigBag::GetEnvironment() == "Testing"){
	ini_set('display_errors', 1);
	ini_set('display_startup_errors', 1);
	error_reporting(E_ALL);
}
else{
	ini_set('display_errors', 0);
	ini_set('display_startup_errors', 0);
	error_reporting(0);
}
/*------------------------------------------------------------------------------------------------------------------------*/




/*
* Call Class-Autoloading method
*/
spl_autoload_register("Autoloader::AutoloadClass");
/*------------------------------------------------------------------------------------------------------------------------*/




/*
* Instantiate Registry and Store Objects
*/
$Registry = Registry::GetInstance();
$Registry->StoreObject("Session", "Session");
$Registry->StoreObject("Application", "App");
/*------------------------------------------------------------------------------------------------------------------------*/

/*
* Start the Session
*/
$Registry->GetObject("Session")->Start();
/*------------------------------------------------------------------------------------------------------------------------*/

/*
* Connect the Database
*/
if (DBConfig::USE_DB == TRUE) {
	$Registry->StoreObject(DBConfig::DB_INTERFACE . "Database", "Database");
	$Registry->GetObject("Database")->Connect(DBConfig::DB_HOST, DBConfig::DB_USERNAME, DBConfig::DB_PASSWORD, DBConfig::DB_NAME);
}
/*------------------------------------------------------------------------------------------------------------------------*/

/*
* Check Authentication
*/
if (AuthConfig::USE_AUTH == TRUE) {
	$Registry->StoreObject("Authentication", "Auth");
	$Registry->GetObject("Auth")->CheckAuthentication();
}
/*------------------------------------------------------------------------------------------------------------------------*/

/*
* Parse Route and Analyze request
*/
RouteManager::ParseRoute();
Request::Analyze();
/*------------------------------------------------------------------------------------------------------------------------*/


/*
* Finally, Everything is ready and Get Response.
*/
include_once Request::GetResponse();
?>
