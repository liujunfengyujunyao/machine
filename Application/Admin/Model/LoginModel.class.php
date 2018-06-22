<?php
namespace Admin\Model;
use Think\Model;
class LoginModel extends Model{
	//自动验证
	protected $_validate = array(
		array('email','require','邮箱不能为空'),
		array('email','email','邮箱格式不正确'),
		array('email','','邮箱已被注册',0,'unique'),
		array('password','require','密码不能为空11111'),
		array('repassword','password','两次密码不一致',0,'confirm')
		);

	//自动完成
	protected $_auto = array(

		array('create_time','time',1,'function'),
		array('password','encrypt_password',3,'function'),
		);
}