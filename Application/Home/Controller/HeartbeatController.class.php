<?php

namespace Home\Controller;
use Think\Controller;
header("content-type:text/html;charset=utf-8");
class HeartbeatController extends Controller{
	public function index(){
		

		$data = array(
			'userid' => $_SESSION['userid'],
			);
		$this->ajaxReturn($data);
	}
}