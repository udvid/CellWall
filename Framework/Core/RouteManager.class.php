<?php
class RouteManager
{
	/*-----------------------------------------------------------------------------------------------------------*/
    private static $RouteSections = array();
	
	/*-----------------------------------------------------------------------------------------------------------*/
	private function __construct(){}
	private function __clone(){}
	/*-----------------------------------------------------------------------------------------------------------*/
	public static function ParseRoute(){
        $route = Firewall::sanitizeURL($_SERVER['REQUEST_URI']);
		$path = parse_url($route, PHP_URL_PATH);
		/*$path = isset($_GET['url'])? $_GET['url'] : "";*/
		if($path == "/"){
			self::$RouteSections[] = "home";
		}
		else{
			$routeData = explode( '/', $path );
            while(!empty($routeData) && strlen( reset( $routeData ) ) === 0){
                array_shift( $routeData );
            }
            while ( !empty( $routeData ) && strlen( end( $routeData ) ) === 0){
                array_pop( $routeData );
            }
            self::$RouteSections = $routeData;
		}
	}
	/*-----------------------------------------------------------------------------------------------------------*/
	public static function GetAjaxRoot(){
		if(isset($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest'){
			return self::GetRouteSection(2);
		}
	}
	/*-----------------------------------------------------------------------------------------------------------*/
    public static function GetRouteSections(){
        return self::$RouteSections;
    }
	/*-----------------------------------------------------------------------------------------------------------*/
    public static function GetRouteSection($index){
		if ($index > 0){
			if (self::CountRouteSections() >= $index){
				return strtolower(self::$RouteSections[$index - 1]);
			}
			else{
				return "";
			}
		}
		else{
			return "";
		}
    }
	/*-----------------------------------------------------------------------------------------------------------*/
	public static function CountRouteSections(){
		return count(self::$RouteSections);
	}
	
}
?>