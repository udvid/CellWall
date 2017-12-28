<?php
Class TestModel extends AppModel
{
	public function __construct(){
		parent::__construct();
	}
	
	public function Validate(array $data){
		Validation::Required(array("Name"=>$data["name"], "Value"=>));
	}
}
?>