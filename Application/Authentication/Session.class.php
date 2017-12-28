<?php
/**
* session class. perform session tasks
*/
class Session
{
	/*-----------------------------------------------------------------------------------------------------------------*/
	private $Name = "smp";
	/*-----------------------------------------------------------------------------------------------------------------*/
	private $Cookie = array();
	/*-----------------------------------------------------------------------------------------------------------------*/
	
	
	
	/**
	* class constructor
	* @access public
	*/
    public function __construct(){
		$this->Cookie = array("lifetime"=>0, "path"=>"/", "domain"=>ini_get('udvid.com'), "httponly"=>TRUE);
		if (ConfigBag::GetEnvironment() == "Testing"){
			$this->Cookie["secure"] = FALSE;
		}
		else{
			$this->Cookie["secure"] = TRUE;
		}
		$this->Setup();
    }
	/*-----------------------------------------------------------------------------------------------------------------*/
	public function Start(){
		session_start();
		if (!isset($_SESSION["idBirthTime"])) {
    		session_regenerate_id(TRUE);
			$_SESSION["idBirthTime"] = time();
		}
		// Regenerate session id every 5 minutes
		if ($_SESSION["idBirthTime"] < time() - 300) {
    		session_regenerate_id(TRUE);
    		$_SESSION["idBirthTime"] = time();
		}
	}
	/*-----------------------------------------------------------------------------------------------------------------*/
	private function Setup(){
		ini_set('session.use_cookies', 1);
		ini_set('session.use_only_cookies', 1);
		ini_set("session.use_trans_sid", FALSE);
		ini_set('session.entropy_length', 32);
		ini_set('session.entropy_file', '/dev/urandom');
		ini_set('session.hash_function', 'sha256');
		ini_set('session.hash_bits_per_character', 5);
		session_name($this->Name);
		session_set_cookie_params($this->Cookie["lifetime"], $this->Cookie["path"], $this->Cookie["domain"], $this->Cookie["secure"], $this->Cookie["httponly"]);
	}
	/*-----------------------------------------------------------------------------------------------------------------*/
	public function Destroy($sessonData = array()){
		$_SESSION = array();
		setcookie($this->Name, "", time() - 42000, $this->Cookie["path"], $this->Cookie["domain"], $this->Cookie["secure"], $this->Cookie["httponly"]);
		foreach ($sessonData as $sessData){
			unset($sessData);
		}
		session_unset();
		session_destroy();
	}
}
?>