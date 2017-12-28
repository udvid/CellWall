<?php
/**
* Database table : LoginRequest
*/
class LoginRequestModel extends AppModel
{
	private $Table = "LoginRecord";
	private $AuthId;
	private $LoginId;
	private $LoginIP;
	private $LoginUA;
	private $CSRFToken;
	
	public function __construct(){
		
	}
	
	
}
?>