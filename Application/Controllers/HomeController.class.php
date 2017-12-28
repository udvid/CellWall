<?php
class HomeController extends AppController {
	
	public function __construct(){
		parent::__construct();
	}
	
	public function Index(){
		$this->SetView();
	}
}
?>