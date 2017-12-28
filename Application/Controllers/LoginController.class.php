<?php

class LoginController extends AppController {
	
	public function __construct(){
		parent::__construct();
	}
	
	public function Index(){
		if($this->Registry->GetObject("Auth")->IsLoggedin() && $this->Registry->GetObject("Auth")->IsJustLoggedin() == FALSE){
			$this->RedirectView("Location: /");
		}
		else{
			if (Request::IsGET()){
				View::SetVar("Referer", Request::Referer());
				View::SetVar("LoginErrorMsg", "");
				$this->SetView();
			}
			else{
				$login = new Login($this->POST("AuthData"), $this->POST("Password"), $this->POST("Referer"));
				if($login->HasError()){
					View::SetVar("LoginErrorMsg", $login->GetErrorMsg());
					$this->SetView();
				}
				else{
					if (empty($this->POST("Referer"))){
						$this->RedirectView("/");
					}
					else{
						$this->RedirectView($this->POST("Referer"));
					}
				}
			}
		}
	}
}
?>