<?php
/**
* Configurations to be used by the framework and application
*/

class ConfigBag
{
	/*-----------------------------------------------------------------------------------------------------------*/
	private function __construct(){}
	private function __clone(){}
	/*-----------------------------------------------------------------------------------------------------------*/
	public static function GetEnvironment(){
		if (strpos(strtolower($_SERVER['HTTP_HOST']), "localhost") !== FALSE || ($_SERVER['REMOTE_ADDR'] == '127.0.0.1' || '::1')){
			return "Testing";
		}
		else{
			return "Production";
		}
	}
	/*-----------------------------------------------------------------------------------------------------------*/
	public static function IsHTTPS(){
		if(isset($_SERVER["HTTPS"]) && ($_SERVER["HTTPS"] == "on")){
			return TRUE;
		}
		else{
			return FALSE;
		}
	}
	/*-----------------------------------------------------------------------------------------------------------*/
	public static function GetRegisteredRouteController($key){
		return RouteConfig::REGISTERED_ROUTES[$key];
	}
	/*-----------------------------------------------------------------------------------------------------------*/
	public static function HasRouteWithActionMethodAlias($route){
		if (array_key_exists($route, RouteConfig::ACTION_METHOD_ALIAS)){
			return TRUE;
		}
		else{
			return FALSE;
		}
	}
	/*-----------------------------------------------------------------------------------------------------------*/
	public static function GetRoutesWithActionMethodAlias($routeOnly = FALSE){
		if ($routeOnly == TRUE){
			return array_keys(RouteConfig::ACTION_METHOD_ALIAS);
		}
		else{
			return RouteConfig::ACTION_METHOD_ALIAS;
		}
	}
	/*-----------------------------------------------------------------------------------------------------------*/
	public static function HasActionMethodAlias($route, $action){
		if (array_key_exists($action, RouteConfig::ACTION_METHOD_ALIAS[$route])){
			return TRUE;
		}
		else{
			return FALSE;
		}
	}
	/*-----------------------------------------------------------------------------------------------------------*/
	public static function GetActionMethodAlias($route, $action){
		return RouteConfig::ACTION_METHOD_ALIAS[$route][$action];
	}
	/*-----------------------------------------------------------------------------------------------------------*/
	public static function GetRegisteredRoutes(){
		return RouteConfig::REGISTERED_ROUTES;
	}
	/*-----------------------------------------------------------------------------------------------------------*/
	public static function GetRegisteredRouteBases(){
		return array_keys(RouteConfig::REGISTERED_ROUTES);
	}
	/*-----------------------------------------------------------------------------------------------------------*/
	public static function GetIgnoredRoutes(){
		return RouteConfig::IGNORED_ROUTES;
	}
	/*-----------------------------------------------------------------------------------------------------------*/
	public static function GetActiveRoutes(){
		return array_diff(self::GetRegisteredRouteBases(), self::GetIgnoredRoutes());
	}
}
?>