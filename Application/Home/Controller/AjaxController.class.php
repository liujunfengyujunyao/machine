<?php
namespace Home\Controller;
use Think\Controller;
header('Access-Control-Allow-Origin:*');
header("Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept");
class AjaxController extends Controller{
	public function index(){
		$this->display();
	}
	public function index2(){
		$this->display();
	}

}