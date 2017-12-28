<?php
/**
* URL Configurations to be used by application
*/

class RouteConfig
{
	
	/*
	* Register routes
	* In order to make a route accessible, you must have to register it first
	* Pattern: routebase => controller 
	*/
	const REGISTERED_ROUTES = array(
		"test" => "Test",
		"home" => "Home",
	);
	
	/**
	* Register an alias for an action method different than the name that is present in URL
	* Pattern: "controller" => array(NameInURL => Alias, ...)
	* Example: "article" => array("editarticle" => "edit", "createarticle" => "create")
	*/
	const ACTION_METHOD_ALIAS = array(
		
	);
	
	/**
	* Enter routes which will be ignored and shown 404 error if tried to access
	* Example: "tutorial";
	*/
	const IGNORED_ROUTES = array(
		""
	);
	
	/*-----------------------------------------------------------------------------------------------------------*/
	private function __construct(){}
	private function __clone(){}
	/*-----------------------------------------------------------------------------------------------------------*/
	
}
?>