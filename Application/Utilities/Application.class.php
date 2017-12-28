<?php
/**
* Application class
* perform application related tasks
* ----------------------------------------------------
*/

class Application
{
	private $Registry;
	
	public function __construct(){
		$registry = Registry::GetInstance();
		$this->Registry = $registry;
	}

}
?>