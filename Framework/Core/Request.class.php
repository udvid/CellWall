<?php
class Request
{
	/* The base of the request url 
	* For example, in URL: /tutorial/php/php-intro, 'tutorial' is base
	*/
	private static $Base;
	/*-----------------------------------------------------------------------------------------------------------*/
	/* The requested controller */
	private static $Controller;
	/*-----------------------------------------------------------------------------------------------------------*/
	/* The requested action in the controller */
	private static $Action;
	/*-----------------------------------------------------------------------------------------------------------*/
	/* The requested action in the url */
	private static $RouteAction;
	/*-----------------------------------------------------------------------------------------------------------*/
	/* The parameters */
	private static $Params = array();
	/*-----------------------------------------------------------------------------------------------------------*/
	private function __construct(){}
	private function __clone(){}
	/*-----------------------------------------------------------------------------------------------------------*/
	public static function Analyze(){
		self::$Base = RouteManager::GetRouteSection(1);
		self::$Controller = self::CheckController();
		if (RouteManager::CountRouteSections() == 1){
			self::$Action = "Index";
			self::$RouteAction = "Index";
		}
		elseif (RouteManager::CountRouteSections() > 1){
			if (RouteManager::CountRouteSections() == 2){
				if (ConfigBag::HasRouteWithActionMethodAlias(self::$Base) && ConfigBag::HasActionMethodAlias(self::$Base, RouteManager::GetRouteSection(2)) && method_exists(self::$Controller . "Controller", ConfigBag::GetActionMethodAlias(self::$Base, RouteManager::GetRouteSection(2)))){
					self::$Action = ConfigBag::GetActionMethodAlias(self::$Base, RouteManager::GetRouteSection(2));
					self::$RouteAction = RouteManager::GetRouteSection(2);
				}
				elseif (method_exists(self::$Controller . "Controller", RouteManager::GetRouteSection(2))){
					self::$Action = RouteManager::GetRouteSection(2);
					self::$RouteAction = RouteManager::GetRouteSection(2);
				}
				else{
					self::$Action = "Index";
					self::$RouteAction = "Index";
					self::$Params[] = RouteManager::GetRouteSection(2);
				}
			}
			elseif (RouteManager::CountRouteSections() >= 3){
				if (ConfigBag::HasRouteWithActionMethodAlias(self::$Base) && ConfigBag::HasActionMethodAlias(self::$Base, RouteManager::GetRouteSection(2)) && method_exists(self::$Controller . "Controller", ConfigBag::GetActionMethodAlias(self::$Base, RouteManager::GetRouteSection(2)))){
					self::$Action = ConfigBag::GetActionMethodAlias(self::$Base, RouteManager::GetRouteSection(2));
					self::$RouteAction = RouteManager::GetRouteSection(2);
					self::$Params = array_slice(RouteManager::GetRouteSections(), 2);
				}
				elseif (method_exists(self::$Controller . "Controller", RouteManager::GetRouteSection(2))){
					self::$Action = RouteManager::GetRouteSection(2);
					self::$RouteAction = RouteManager::GetRouteSection(2);
					self::$Params = array_slice(RouteManager::GetRouteSections(), 2);
				}
				else{
					self::$Action = "Index";
					self::$RouteAction = "Index";
					self::$Params = array_slice(RouteManager::GetRouteSections(), 1);
				}
			}
		}
	}
	/*-----------------------------------------------------------------------------------------------------------*/
	public static function GetResponse(){
		if (SiteConfig::UNDER_CONSTRUCTION == TRUE){
			return ROOT . Config::ERROR_VIEW_PATH . "UnderConstruction.php";
		}
		elseif (SiteConfig::SERVICE_PAUSE == TRUE){
			return ROOT . Config::ERROR_VIEW_PATH . "ServicePause.php";
		}
		else{
			if (in_array(self::GetBase(), ConfigBag::GetActiveRoutes())){
				$controllerName = self::GetController() . "Controller";
				$action = self::GetAction();
				$controller = new $controllerName();
				$controller->$action();
				if ($controller->IsViewResponse()){
					return $controller->GetView();
				}
			}
			else {
				if (self::IsAjaxRequest()){
					return ROOT . Config::ERROR_VIEW_PATH . Config::AJAX_ERROR_FILE;
				}
				else{
					return ROOT . Config::ERROR_VIEW_PATH . Config::ERROR_FILE;
				}
			}
		}
	}
	/*-----------------------------------------------------------------------------------------------------------*/
	private static function CheckController(){
		if (array_key_exists(self::$Base, ConfigBag::GetRegisteredRoutes())){
			return ConfigBag::GetRegisteredRouteController(self::$Base);
		}
		else {
			return "Home";
		}
	}
	/*-----------------------------------------------------------------------------------------------------------*/
	public static function GetBase(){
		return self::$Base;
	}
	/*-----------------------------------------------------------------------------------------------------------*/
	public static function GetController(){
		return self::$Controller;
	}
	/*-----------------------------------------------------------------------------------------------------------*/
	public static function GetAction(){
		return ucfirst(self::$Action);
	}
	/*-----------------------------------------------------------------------------------------------------------*/
	public static function GetRouteAction(){
		return ucfirst(self::$RouteAction);
	}
	/*-----------------------------------------------------------------------------------------------------------*/
	/* Parameter index starts from 1 */
	public static function Parameter($index){
		if ($index > 0){
			if (count(self::$Params) >= $index){
				return self::$Params[$index - 1];
			}
			else{
				return 0;
			}
		}
		else{
			return 0;
		}
	}
	/*-----------------------------------------------------------------------------------------------------------*/
	public static function QueryString($key){
		if (self::IsGET()){
			if (isset($_GET[$key])){
				return Firewall::EscapeChars(Firewall::SanitizeInput($_GET[$key])) ;
			}
			else {
				return "";
			}
		}
	}
	/*-----------------------------------------------------------------------------------------------------------*/
	public static function IsAjaxRequest(){
		if(isset($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest'){
			return TRUE;
		}
		else{
			return FALSE;
		}
	}
	/*-----------------------------------------------------------------------------------------------------------*/
	public static function IsViewRequest(){
		if (self::IsAjaxRequest() == FALSE && $_SERVER["REQUEST_METHOD"] == "GET"){
			return TRUE;
		}
		else{
			return FALSE;
		}
	}
	/*-----------------------------------------------------------------------------------------------------------*/
	public static function IsGET(){
		if (strtolower($_SERVER["REQUEST_METHOD"]) == "get"){
			return TRUE;
		}
		else{
			return FALSE;
		}
	}
	/*-----------------------------------------------------------------------------------------------------------*/
	public static function IsPOST(){
		if (strtolower($_SERVER["REQUEST_METHOD"]) == "post"){
			return TRUE;
		}
		else{
			return FALSE;
		}
	}
	/*-----------------------------------------------------------------------------------------------------------*/
	public static function Referer(){
		if(isset($_SERVER['HTTP_REFERER']) && !empty($_SERVER['HTTP_REFERER'])){
			return $_SERVER['HTTP_REFERER'];
		}
	}
	
}
?>