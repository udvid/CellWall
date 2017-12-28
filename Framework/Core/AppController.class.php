<?php

/**
* Main controller class
* will be extended by a specific controller class
*/
abstract class AppController
{
	protected $Registry ;
	
	private $View = "";
	
	/*--------------------------------------------------------------------------------------------------------------*/
	/**
	* class constructor
	* create an instance of the registry and set it to the controller
	* @access protected
	* @return void
	*/
	protected function __construct(){
		$registry = Registry::GetInstance();
        $this->Registry = $registry;
	}
	
	/*--------------------------------------------------------------------------------------------------------------*/
	/**
	* Get value by $_POST method
	* @return mixed the value
	*/
	final public function IsViewResponse(){
		if (empty($this->View)) {
			return FALSE;
		}
		else{
			return TRUE;
		}
	}
	
	/*--------------------------------------------------------------------------------------------------------------*/
	/**
	* Get value by $_POST method
	* @return mixed the value
	*/
	final public function SetView($fileName = NULL){
		if (empty($fileName)) {
			$this->View = $this->ViewPath();
		}
		else{
			$this->View = $this->ViewPath($fileName);
		}
	}
	
	/*--------------------------------------------------------------------------------------------------------------*/
	/**
	* Get value by $_POST method
	* @return mixed the value
	*/
	final public function GetView(){
		if (file_exists($this->View)){
			return $this->View ;
		}
		else{
			return $this->ErrorView();
		}
	}
	
	/*--------------------------------------------------------------------------------------------------------------*/
	/**
	* Error 404 view
	* @return mixed the value
	*/
	final public function ErrorView(){
		if (Request::IsAjaxRequest()){
			$this->View = ROOT . Config::ERROR_VIEW_PATH . "Ajax404.php";
		}
		else{
			$this->View = ROOT . Config::ERROR_VIEW_PATH . "404.php";
		}
		return $this->View ;
	}
	
	/*--------------------------------------------------------------------------------------------------------------*/
	/**
	* Redirect to another page
	* @return mixed the value
	*/
	final public function RedirectView($url){
		header("Location: " . $url);
	}
	
	/*--------------------------------------------------------------------------------------------------------------*/
	/**
	* Get value by $_POST method
	* @return mixed the value
	*/
	private function ViewPath($fileName = NULL){
		$directory = Request::GetController();
		if (empty($fileName)){
			$file = Request::GetController() . Request::GetRouteAction() ;
		}
		else{
			$file = $fileName ;
		}
		return ROOT . Config::MAIN_VIEW_PATH . $directory . "/" . $file . ".php";
	}
	
	
	/*--------------------------------------------------------------------------------------------------------------*/
	/**
	* Get value from $_POST
	* @return mixed the value
	*/
	final public function POST($field, $sanitized = FALSE){
		if (($_SERVER["REQUEST_METHOD"] == "POST")) {
			if (isset($_POST[$field])){
				if($sanitized == TRUE){
					return Firewall::SanitizeInput($_POST[$field]);
				}
				else{
					return $_POST[$field];
				}
			}
			else{
				return "";
			}
		}
	}
	
	/*--------------------------------------------------------------------------------------------------------------*/
	/**
	* Get value from $_GET
	* @return mixed the value
	*/
	final public function GET($field, $sanitized = FALSE){
		if (($_SERVER["REQUEST_METHOD"] == "GET")) {
			if (isset($_GET[$field])){
				if($sanitized == TRUE){
					return Firewall::SanitizeInput($_GET[$field]);
				}
				else{
					return $_GET[$field];
				}
			}
			else{
				return "";
			}
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
	final public function RequestAuthenticated($requester, $token){
		if(!empty($requester) && !empty($token)){
			if($this->Registry->GetObject("Auth")->IsLoggedin() && $requester === $this->Registry->GetObject("Auth")->GetAuthAccountId() && ($token === $this->Registry->GetObject("Auth")->GetCSRFToken())){
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
	final public function AdminRequestAuthenticated($requester, $token){
		if(!empty($requester) && !empty($token)){
			if($this->Registry->GetObject("Auth")->IsLoggedin() && $this->Registry->GetObject("Auth")->IsAdmin() && ($requester === $this->Registry->GetObject("Auth")->GetAuthAccountId()) && ($token === $this->Registry->GetObject("Auth")->GetCSRFToken())){
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
	final public function MemberRequestAuthenticated($requester, $token){
		if(!empty($requester) && !empty($token)){
			if($this->Registry->GetObject("Auth")->IsLoggedin() && $this->Registry->GetObject("Auth")->IsMember() && ($requester === $this->Registry->GetObject("Auth")->GetAuthAccountId()) && ($token === $this->Registry->GetObject("Auth")->GetCSRFToken())){
				return TRUE;
			}
			else{
				return FALSE;
			}
		}
	}
	/*--------------------------------------------------------------------------------------------------------------*/
	/**
	* create a response HTML after completing a request
	* @param String the response type - success or error
	* @param String the response text
	* @access protected
	* @return void
	*/
	protected function Response($type,$text){
		if($type === "error"){
			echo "<span style='color:#F00'>{$text}</span>";
		}
		elseif($type === "success"){
			echo "<span style='color:#090'>{$text}</span>";
		}
	}
}

?>