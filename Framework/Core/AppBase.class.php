<?php

class AppBase
{
	protected $registry;
	/*--------------------------------------------------------------------------------------------------------------*/
	protected function __construct(){
		$registry = Registry::GetInstance();
        $this->registry = $registry;
	}
	/*--------------------------------------------------------------------------------------------------------------*/
	/**
	* Get logged in username
	* @access public
	* return String the username
	*/
	public function getAuthUsername(){
		return $this->Registry->GetObject("auth")->getAuthUserData("Username");
	}
	/*--------------------------------------------------------------------------------------------------------------*/
	/**
	* Get logged in account id
	* @access public
	* return String the account id
	*/
	public function getAuthAccountId(){
		return $this->Registry->GetObject("auth")->getAccountId();
	}
	/*--------------------------------------------------------------------------------------------------------------*/
	/**
	* Get logged in CSRF token
	* @access public
	* return String the token
	*/
	public function getCSRFToken(){
		return $this->Registry->GetObject("auth")->getCSRFToken();
	}
	/*--------------------------------------------------------------------------------------------------------------*/
	/**
	* Check if user is logged in
	* @access public
	* return Boolean the login status
	*/
	public function isLoggedin(){
		return $this->Registry->GetObject("auth")->IsLoggedin();
	}
	/*--------------------------------------------------------------------------------------------------------------*/
	/**
	* check for a valid model and set instance of the model
	* @param String the model
	* @param String the unique id of the model
	* @param String the account id of the user
	* @access public
	* @return Boolean
	*/
	public function validModel($model, $uniqueKey, $requester){
		$modelInstance = new $model($uniqueKey, $requester);
		if($modelInstance->isValid()){
			return TRUE;
		}
		else{
			return FALSE;
		}
	}
	/*--------------------------------------------------------------------------------------------------------------*/
	/**
	* check if a form/ajax request comes from a general user
	* @param String the account id of the logged in user
	* @param String the CSRF token
	* @access public
	* @return Boolean
	*/
	public function requestAuthenticated($requester, $token){
		if(!empty($requester) && !empty($token)){
			if($this->isLoggedin() && $requester === $this->getAuthAccountId() && ($token === $this->Registry->GetObject("auth")->getCSRFToken())){
				return TRUE;
			}
			else{
				return FALSE;
			}
		}
	}
	/*--------------------------------------------------------------------------------------------------------------*/
	/**
	* check if a form/ajax request comes from admin
	* @param String the account id of the logged in user
	* @param String the CSRF token
	* @access public
	* @return Boolean
	*/
	public function adminRequestAuthenticated($requester, $token){
		if(!empty($requester) && !empty($token)){
			if($this->isLoggedin() && $this->Registry->GetObject("auth")->isAdmin() && ($requester === $this->getAuthAccountId()) && ($token === $this->Registry->GetObject("auth")->getCSRFToken())){
				return TRUE;
			}
			else{
				return FALSE;
			}
		}
	}
	/*--------------------------------------------------------------------------------------------------------------*/
	/**
	* check if a form/ajax request comes from a member
	* @param String the account id of the logged in user
	* @param String the CSRF token
	* @access public
	* @return Boolean
	*/
	public function memberRequestAuthenticated($requester, $token){
		if(!empty($requester) && !empty($token)){
			if($this->isLoggedin() && $this->Registry->GetObject("auth")->isMember() && ($requester === $this->getAuthAccountId()) && ($token === $this->Registry->GetObject("auth")->getCSRFToken())){
				return TRUE;
			}
			else{
				return FALSE;
			}
		}
	}
}

?>