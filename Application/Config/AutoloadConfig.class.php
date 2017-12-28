<?php
/**
* Autoloader Class
*/

class AutoloadConfig
{
	
	const CLASS_PATHS = array(
		"/Application/Config/",
		"/Application/Models/",
		"/Application/Controllers/",
		"/Application/Controllers/Interactions/",
		"/Application/Authentication/",
		"/Application/Utilities/",
		"/Framework/Interfaces/",
		"/Framework/Core/",
		"/Framework/Helper/",
		"/Framework/Library/"
	);
	
	/*-----------------------------------------------------------------------------------------------------------*/
	private function __construct(){}
	private function __clone(){}
	/*-----------------------------------------------------------------------------------------------------------*/
}
?>