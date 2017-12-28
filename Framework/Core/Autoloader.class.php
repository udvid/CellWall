<?php

require_once ROOT . "/Framework/Helper/Handy.class.php";
require_once ROOT . "/Application/Config/AutoloadConfig.class.php";

class Autoloader
{
	public static function AutoloadClass($class) {	
		$directoryList = Handy::DirectoryList(AutoloadConfig::CLASS_PATHS);
		foreach ($directoryList as $path){
			if(file_exists($path . $class . ".class.php")) {
				require_once $path . $class . ".class.php";
			}
		}
	}
	/*-----------------------------------------------------------------------------------------------------------*/
	
}
?>