<?php
class TestController extends AppController {
	
	public function __construct(){
		parent::__construct();
	}
	
	public function Index(){
		$this->SetView();
	}
	
	public function Validate(){
		$uname = $this->POST("uname");
		$model = new TestModel();
		$model->Validate(array("uname"=>$uname));
	}
}
?>