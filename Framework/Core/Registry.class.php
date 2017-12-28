<?php
class Registry
{
	/*-----------------------------------------------------------------------------------------------------------*/
	private static $Instance;
	private static $Objects = array();
    private static $Settings = array();
	/*-----------------------------------------------------------------------------------------------------------*/
    private function __construct(){}
	private function __clone(){}
	/*-----------------------------------------------------------------------------------------------------------*/
    public function StoreObject($object, $key){
        self::$Objects[ $key ] = new $object( self::$Instance );
    }
	/*-----------------------------------------------------------------------------------------------------------*/
    public function GetObject($key){
        return self::$Objects[$key];
    }
	/*-----------------------------------------------------------------------------------------------------------*/
    public function StoreSettings($data, $key){
        self::$Settings[$key] = $data;
    }
	/*-----------------------------------------------------------------------------------------------------------*/
    public function GetSettings($key){
        return self::$Settings[$key];
    }
	/*-----------------------------------------------------------------------------------------------------------*/
    public static function GetInstance(){
        if( !isset( self::$Instance ) ){
        self::$Instance = new Registry;
        }
       return self::$Instance;
    }
}
?>