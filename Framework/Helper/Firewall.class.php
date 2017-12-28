<?php
/**
* security management class
*/
class Firewall
{
	/*-----------------------------------------------------------------------------------------------------------*/
	private function __construct(){}
	private function __clone(){}
	/*-----------------------------------------------------------------------------------------------------------*/
	public static function GenerateUniqueID($maxLength = NULL){
		$entropy = '';

    // try ssl first
    if (function_exists('openssl_random_pseudo_bytes')) {
        $entropy = openssl_random_pseudo_bytes(64, $strong);
        // skip ssl since it wasn't using the strong algo
        if($strong !== true) {
            $entropy = '';
        }
    }

    // add some basic mt_rand/uniqid combo
    $entropy .= uniqid(mt_rand(), true);

    // try to read from the windows RNG
    if (class_exists('COM')) {
        try {
            $com = new COM('CAPICOM.Utilities.1');
            $entropy .= base64_decode($com->GetRandom(64, 0));
        } catch (Exception $ex) {
        }
    }

    // try to read from the unix RNG
    if (is_readable('/dev/urandom')) {
        $h = fopen('/dev/urandom', 'rb');
        $entropy .= fread($h, 64);
        fclose($h);
    }

    $hash = hash('whirlpool', $entropy);
    if ($maxLength) {
        return substr($hash, 0, $maxLength);
    }
    return $hash;
	}
	/*-----------------------------------------------------------------------------------------------------------*/
	public static function SafeOutput($string){
		echo htmlspecialchars($string, ENT_QUOTES,'UTF-8');
	}
	/*-----------------------------------------------------------------------------------------------------------*/
	public static function EscapeChars($string){
		return htmlspecialchars($string, ENT_QUOTES,'UTF-8');
	}
	/*-----------------------------------------------------------------------------------------------------------*/
	public static function SanitizeInput($input){
		$type = gettype($input);
		switch ($type){
			case "string" :
			return filter_var($input, FILTER_SANITIZE_STRING);
			break;
			
			case "integer" :
			return filter_var($input, FILTER_SANITIZE_NUMBER_INT);
			break;
			
			case "double" :
			return filter_var($input, FILTER_SANITIZE_NUMBER_FLOAT);
			break;
			
			default :
			return 0;
		}
	}
	/*-----------------------------------------------------------------------------------------------------------*/
	public static function SanitizeURL($url){
		return filter_var($url, FILTER_SANITIZE_URL);
	}
	public static function validUsername($username){
		if (preg_match('/\s/', $username)) {
			return FALSE;
		}
		elseif (preg_match('/^\d+$/', $username)) {
			return FALSE;
		}
		elseif (preg_match('/[^a-zA-Z0-9_-]/', $username)) {
			return FALSE;
		}
		elseif (preg_match('/^[0-9]+[a-zA-Z0-9_-]+$/', $username)) {
			return FALSE;
		}
		else{
			return TRUE;
		}
	}
	/*-----------------------------------------------------------------------------------------------------------*/
	public static function ValidName($name){
		if (preg_match('/\s/', $name)) {
			return FALSE;
		}
		elseif (preg_match('/^\d+$/', $name)) {
			return FALSE;
		}
		elseif (preg_match('/[^a-zA-Z]/', $name)) {
			return FALSE;
		}
		elseif(strlen($name) < 2 || strlen($name) > 30){
			return FALSE;
		}
		else{
			return TRUE;
		}
	}
	/*-----------------------------------------------------------------------------------------------------------*/
	public static function ValidFullname($name){
		if (preg_match('/^\d+$/', $name)) {
			return FALSE;
		}
		elseif (preg_match('/[^a-zA-Z\s]/', $name)) {
			return FALSE;
		}
		elseif(strlen($name) < 2 || strlen($name) > 100){
			return FALSE;
		}
		else{
			return TRUE;
		}
	}
	/*-----------------------------------------------------------------------------------------------------------*/
	public static function ValidEmail($email){
		$email = filter_var($email, FILTER_SANITIZE_EMAIL);
		if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
      		return FALSE; 
    	}
		else{
			return TRUE;
		}
	}
	/*-----------------------------------------------------------------------------------------------------------*/
	public static function ValidInputURL($url){
		$url = filter_var($url, FILTER_SANITIZE_URL);
		if (!filter_var($url, FILTER_VALIDATE_URL)) {
      		return FALSE;
    	}
		else{
			return TRUE;
		}
	}
}
?>