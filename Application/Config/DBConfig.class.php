<?php
/**
* Database Configuration
*/

class DBConfig
{
	/**
	* @const String DB_INTERFACE The database interface. Use "PDO" for PDO, "MySQLi" for MySQLi.
	* @const String DB_HOST The database server.
	* @const String DB_USERNAME The database username.
	* @const String DB_PASSWORD The password to connect the database.
	* @const String DB_NAME The name of the database.
	*/
	const USE_DB = FALSE;
	/*-----------------------------------------------------------------------------------------------------------------*/
	const DB_INTERFACE = "P";
	/*-----------------------------------------------------------------------------------------------------------------*/
	const DB_HOST = "localhost";
	/*-----------------------------------------------------------------------------------------------------------------*/
	const DB_USERNAME = "";
	/*-----------------------------------------------------------------------------------------------------------------*/
	const DB_PASSWORD = "";
	/*-----------------------------------------------------------------------------------------------------------------*/
	const DB_NAME = "";
	
	
	/*-----------------------------------------------------------------------------------------------------------*/
	private function __construct(){}
	private function __clone(){}
}
?>
