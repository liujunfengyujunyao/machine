<?php
namespace Admin\Model;
use Think\Model;
class ManagerModel extends Model{
	protected $_validate = array(
		// array('username','require','邮箱不能为空'),
		array('email','require','邮箱不能为空'),
		array('email','email','邮箱格式不正确'),
		array('email','','账号名称已经存在',0,'unique',1),//新增的时候验证email字段名是否已经存在
		// array('phone','require','手机号码不能为空'),
		// array('phone','/\d{11}/','手机号格式不正确'),
		array('password','require','密码不能为空'),
		// array('repassword','require','确认密码不能为空'),
		array('repassword','password','两次密码不一致',0,'confirm'),
		
		);
	protected $_auto = array(
		array('create_time','time',1,'function'),
		array('password','encrypt_password',3,'function'),
		);
}