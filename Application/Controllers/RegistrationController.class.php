<?php

class RegistrationController extends AppController {
	
	public function __construct(){
		parent::__construct();
	}
	
	public function Index(){
		if($this->Registry->GetObject("Auth")->IsLoggedin() && $this->Registry->GetObject("Auth")->IsJustLoggedin() == FALSE){
			header("Location: /");
		}
		else{
			$this->SetView();
		}
	}
}
?>