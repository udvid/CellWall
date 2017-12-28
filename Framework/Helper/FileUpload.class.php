<?php

/**
* File Upload class
*/

class FileUpload
{
	private $FileField ;
	
	private $FileName ;
	
	private $FileType ;
	
	private $FileSize ;
	
	private $FileDimension ;
	
	private $TargetFileName ;
	
	private $UploadPath ;
	
	private $PermittedTypes = array() ;
	
	private $MaxSize ;
	
	public function __construct(string $fileField, string $uploadPath, array $permittedTypes, int $maxSize){
		$this->FileField = $_FILES[$fileField];
		$this->FileName = basename($this->FileField["name"]);
		$this->FileType = pathinfo($this->FileName,PATHINFO_EXTENSION);
		$this->FileSize = $this->FileField["size"];
		$this->FileDimension = getimagesize($this->FileField["tmp_name"]);
		$this->UploadPath = $uploadPath;
		$this->PermittedTypes = $permittedTypes;
		$this->MaxSize = $maxSize;
	}
	
	public function IsValidFile(){
		if(empty($this->FileName)){
			return FALSE;
		}
		elseif($this->FileDimension == FALSE) {
			return FALSE;
		}
		elseif(in_array($this->FileType, $this->PermittedTypes) == FALSE) {
			return FALSE;
		}
		elseif($this->FileSize > $this->MaxSize){
			return FALSE;
		}
		else{
			return TRUE;
		}
	}
	
	public function FileInfo(string $info){
		return $this->$info;
	}
}
?>