<?php

/**
* Handy class
* Contains useful methods
*/

class Handy
{
	/*-----------------------------------------------------------------------------------------------------------*/
	private function __construct(){}
	private function __clone(){}
	/*-----------------------------------------------------------------------------------------------------------*/
	public static function DeleteTempFiles($path){
		$path = ROOT . $path;
		if ($handle = opendir($path)) {
   		 	while (false !== ($file = readdir($handle))) { 
        	$filelastmodified = filemtime($path . $file);
        	//Time is calculated in seconds
        	if((time() - $filelastmodified) > 60){
           		$delete = unlink($path . $file);
        	}

    	}
		if($delete == TRUE){
			closedir($handle);
			return TRUE;
		}
		}
	}
	/*-----------------------------------------------------------------------------------------------------------*/
	public static function DeleteFile($fileDir){
		$delete = unlink(ROOT . $fileDir);
		if($delete == TRUE){
			return TRUE;
		}
		else{
			return FALSE;
		}
	}
	/*-----------------------------------------------------------------------------------------------------------*/
	public static function DeleteFiles($files=array()){
		foreach($files as $file){
			$delete = unlink(ROOT . $file);
		}
		if($delete == TRUE){
			return TRUE;
		}
		else{
			return FALSE;
		}
	}
	/*-----------------------------------------------------------------------------------------------------------*/
	public static function SplitJoin($str, $delem){
		$newStr = "";
		$words = array();
		$cleanStr = trim( preg_replace( "/[^0-9a-z]+/i", " ", $str ) );
		$strParts = preg_split('/\s+/', $cleanStr);
		$words = $strParts;
		foreach($words as $word){
			$newStr .= $word.$delem;
		}
		$newStr = substr($newStr, 0, -1);
		return $newStr;
	}
	/*-----------------------------------------------------------------------------------------------------------*/
	public static function DirectoryList($inputRoot, $includeRoot = TRUE){
		$directoryList = array();
		if (gettype($inputRoot) == "array"){
			foreach ($inputRoot as $root){
				$iterator = new RecursiveIteratorIterator(
					new RecursiveDirectoryIterator(ROOT . $root, RecursiveDirectoryIterator::SKIP_DOTS),
					RecursiveIteratorIterator::SELF_FIRST,
					RecursiveIteratorIterator::CATCH_GET_CHILD
				);
				if ($includeRoot == TRUE){
					$directoryList[] = ROOT . $root;
				}
				foreach ($iterator as $path => $dir){
					if ($dir->isDir()){
						$directoryList[] = str_replace(DIRECTORY_SEPARATOR, "/", $path . "/");
					}
				}
			}	
		}
		else {
			$iterator = new RecursiveIteratorIterator(
				new RecursiveDirectoryIterator(ROOT . $inputRoot, RecursiveDirectoryIterator::SKIP_DOTS),
				RecursiveIteratorIterator::SELF_FIRST,
				RecursiveIteratorIterator::CATCH_GET_CHILD
			);
			if ($includeRoot == TRUE){
				$directoryList[] = ROOT . $inputRoot;
			}
			foreach ($iterator as $path => $dir){
				if ($dir->isDir()){
					$directoryList[] = str_replace(DIRECTORY_SEPARATOR, "/", $path . "/");
				}
			}
		}
		return $directoryList;
	}
	/*-----------------------------------------------------------------------------------------------------------*/
	/**
	* @author CodexWorld
	**/
	public static function CreateImageThumbnail($originalImage, $extention, $thumbnailName, $thumbFolder = '', $thumbWidth = '', $thumbHeight = ''){

		//folder path setup
		$thumbPath = $thumbFolder;
		$thumbnail = $thumbPath . $thumbnailName;
		list($width,$height) = getimagesize($originalImage);
		$thumbCreate = imagecreatetruecolor($thumbWidth,$thumbHeight);
		switch($extention){
			case 'jpg':
				$source = imagecreatefromjpeg($originalImage);
				break;
			case 'jpeg':
				$source = imagecreatefromjpeg($originalImage);
				break;

			case 'png':
				$source = imagecreatefrompng($originalImage);
				break;
			case 'gif':
				$source = imagecreatefromgif($originalImage);
				break;
			default:
				$source = imagecreatefromjpeg($originalImage);
		}

		imagecopyresized($thumbCreate,$source,0,0,0,0,$thumbWidth,$thumbHeight,$width,$height);
		switch($extention){
			case 'jpg' || 'jpeg':
				imagejpeg($thumbCreate,$thumbnail,100);
				break;
			case 'png':
				imagepng($thumbCreate,$thumbnail,100);
				break;

			case 'gif':
				imagegif($thumbCreate,$thumbnail,100);
				break;
			default:
				imagejpeg($thumbCreate,$thumbnail,100);
		}
		
	}
}
?>