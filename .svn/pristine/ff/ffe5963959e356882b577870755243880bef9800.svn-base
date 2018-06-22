<?php
namespace Admin\Controller;
use Think\Controller;
class IndexController extends CommonController{
	public function __construct(){
		parent::__construct();
		if (!session('?manager_info')) {
			$this->redirect('Admin/Login/login');
		}
	}
	public function index(){
		$this->display();
	}
}