<?php
/**
* Site configurations to be used by the framework and application
*/

class SiteConfig
{
	const DOMAIN = "";
	const IP = "";
	
	/**
	* Service continuation configuration
	* Set only one constant to true and others false. Your site can not be unavailable for multiple causes.
	*/
	const UNDER_CONSTRUCTION = FALSE;
	const SERVICE_PAUSE = FALSE;
	
	/*-----------------------------------------------------------------------------------------------------------*/
	private function __construct(){}
	private function __clone(){}
	/*-----------------------------------------------------------------------------------------------------------*/
}
?>