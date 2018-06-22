<?php

namespace Home\Controller;
use Think\Controller;
use Common\Plugin\WxLogin;
header("Content-type:text/html;charset=utf-8");
class ApiController extends Controller{
	/*
	 * 登录接口统一入口
	 * by Ljf 2018-4-16
	 */
	public function user_login(){
            $a = $GLOBALS['HTTP_RAW_POST_DATA'];         
            $a = json_decode($a,true);
            // var_dump($a);die;
            $type = $a['msgtype'] ? $a['msgtype'] : "";
            $timestamp = $a['timestamp'] ? $a['timestamp'] : '';
            $params = $a['params'] ? $a['params'] : '';
            $signature = $a['signature'] ? $a['signature'] : '';

            switch($type){
                case 'get_code':
                    echo $this-> get_code();
                    break;
                case 'login_auth':
                   echo $this -> login_auth($params);
                    break;
                case 'get_current_user_info':
                   echo $this -> get_current_user_info($params);
                    break;
                default:
                    $data = array(
                        'msgtype'=>'error',
                        'params' => array(
                            'errid' => 403,
                            'errmsg'=>'msgtype error',
                        ),
                    );
                    $data = json_encode($data,JSON_UNESCAPED_UNICODE);
                    return $data;
                    // echo $data;
                    // $this->ajaxReturn($data);
            }
    }
    /*
     * 获取code
     * by Ljf 2018-4-19
     */
    public function get_code(){ 
         $config = C('wx_test');
         $obj = new Wxlogin($config);
         //这个地址是前端的
         // $redirect_url = "http://www.machine.com/Home/Test/code";
         // $redirect_url = "http://192.168.1.164/Home/Test/code";
         $redirect_url = "http://192.168.1.171/#roomList/list1";
         $url = $obj->getOauthurl($redirect_url);
         $data = array(
                'msgtype'=>'wechat_url',
                'params' => array(
                    'oauth_url' => $url,
                ),
            );   
        
        $data = json_encode($data,JSON_UNESCAPED_UNICODE);
        // echo $data;
        return $data;
    }
    /*
	 * 登录请求验证
	 * by Ljf 2018-4-16
	 */
    public function login_auth($params){
        // $p =$GLOBALS['HTTP_RAW_POST_DATA'];
        // $p = json_decode($p,true);
        // $params = $p['params'];
        //微信浏览器，公众平台
        $config = C('wx_test');
        $obj = new WxLogin($config);
        if(!$params['code']){
            $data = array(
                'msgtype'=>'error',
                'params'=>array(
                    'errid' => 403, 
                    'errmsg' => 'code error',
                    ),
                );
        }else{
            $wx_user = $obj->wx_log($params['code']);
            //$wx_user你需要的用户信息
            if(!$wx_user){
                $data = array(
                    'msgtype'=>'error',
                    'params' => array(
                        'errid' => 403,
                        'errmsg'=>'access overtime',
                    ),
                );
            }else{
                //查询微信用户表里是否已经存在该用户
                $user = M('wx_user')->where(['openid'=>$wx_user->openid])->find();
                if (!$user) {
                    //不存在,插入
                    $data['openid'] = $wx_user->openid;
                    $data['nick'] = $wx_user->nickname;
                    $data['head'] = $wx_user->headimgurl;
                    $data['gender'] = $wx_user->sex;
                    $data['type'] = 'oauth';
                    $data['addtime'] = time();
                    $id = M('wx_user')->add($data);
                }else{
                    //存在,更新会变更的数据
                    $data['nick'] = $wx_user->nickname;
                    $data['head'] = $wx_user->headimgurl;
                    $data['gender'] = $wx_user->sex;
                    M('Wx_user')->where(['openid'=>$wx_user->openid])->save($data);
                    $id = $user['id'];
                }
                $_SESSION['wx_accesstoken'] = $wx_user->access_token;
                //新增的非文档信息
                $info = M('wx_user')->field('nick,head,gender,gold,silver')->where(['id'=>$id])->find();
                $roomtype = M('Type')->alias("t1")->join('left join equipment as t2 on t2.equipment_type = t1.type_id')->join("left join goods as t3 on t3.id = t2.goods_id")->group('type_name')->getField('type_name',true);
                //新增的非文档信息
                $data = array(
                    'msgtype' => 'auth_ok',
                    'params'  => array(
                        'userid'      => $id,
                        'accesstoken' => $wx_user->access_token,
                        'msgid'       => 200,
                        'chatserver'  => null,//聊天服务器地址
                    // 'info'    => $info,
                    // 'roomtype'=> $roomtype,
                    ),
                );
            }
        }
        $data = json_encode($data,JSON_UNESCAPED_UNICODE);
        return $data;
        // $this->ajaxReturn($data);
    }
    /*
	 * 用户具体信息
	 * by Ljf 2018-4-16
	 */
	public function get_current_user_info($params){
		//接口验证
        if(!$params['accesstoken']||!$params['userid']){
        	//参数为空
        	$data = array(
                'msgtype'=>'error',
                'params' => array(
                    'errid' => 403,
                    'errmsg'=>'params error',
                ),
            );
        }elseif($params['accesstoken']!=$_SESSION['wx_accesstoken']){
        	//accesstoken不对
        	$data = array(
        		'msgtype'=>'error',
                'params' => array(
                    'errid' => 403,
                    'errmsg'=>'accesstoken error',
                ),
        	);
        }else{
        	$user = M('wx_user')->where(['id'=>$params['userid']])->find();
        	if(!$user){
        		//用户id不对
        		$data = array(
        			'msgtype' => 'error',
        			'params'  => array(
        				'errid' => 403,
        				'errmsg'=> 'userid not exist',
        			),
        		);
        	}else{
                $roomtype = M('Goods')->alias("t1")->join("left join type as t2 on t1.type_id = t2.type_id")->getField('t2.type_id,t2.type_name',true);
	        	$data = array(
	        		'msgtype' => 'current_user_info',
	        		'params'  => array(
	        			'userid'  => $user['userid'],
	        			'username'=> $user['nickname'],
	        			'avatar'  => $user['head'],
	        			'silver'  => $user['silver'],
	        			'gold'    => $user['gold'],
	        			'rank'    => '玩家等级',
	        			'award'   => '登录奖励',
                        'roomtype' => $roomtype,
	        		),
	        	);
	        }
		}

        $data = json_encode($data,JSON_UNESCAPED_UNICODE);
        return $data;
		// $this->ajaxreturn($data);

	}

    /*
     * 房间大厅信息
     * by Ljf 2018-4-17
     */
    public function lobby(){
       $a = $GLOBALS['HTTP_RAW_POST_DATA'];
       // echo $a;die;
       // echo $a;die;
       // $a = '{"msgtype":"get_room_list","params":{"userid":666,"accesstoken":666}}';
       // $a='{"msgtype":"get_room_list"}';
       $a = json_decode($a,true);
       
       // echo $p;die;
       // $p=substr($p,3);
       // $a = json_decode($p);
       // $a =  json_decode($a,true);
      // echo array_keys($a);die;
      // var_dump($a['msgtype']);die;
       // echo $a['msgtype'];die;
        // $x = json_encode($_POST);
       // echo $this->ajaxReturn($x);
        // $data = $this->ajaxReturn($data);
        // $type = $a['msgtype'];
        // echo $type;die;
        $type = $a['msgtype'] ? $a['msgtype'] : "";
        $params = $a['params'] ? $a['params'] : "";
        $roomtype = $a['roomtype'] ? $a['roomtype'] : "";
        
        switch($type){
            case 'get_room_list';
           echo $this-> get_room_list($params,$roomtype);
            break;
            default:
                $data = array(
                    'msgtype'=>'error',
                    'params'=>array(
                        'errid' => 403,
                        'errmsg' => 'msgtype error',
                        ),
                    );
                $data = json_encode($data,JSON_UNESCAPED_UNICODE);
                echo $data;
                // return $data;
                // $this->ajaxReturn($data);
        }


    }
    //
    public function get_room_list($params,$roomtype){
        $roomtype = M('Type')->getField('type_id,type_name');
        // dump($_POST);die;
        //接口认证
        if (!$params['accesstoken']||!$params['userid']) {
            //参数为空
            $data = array(
                'msgtype'=>'error',
                'params'=>array(
                    'errid'=>403,
                    'errmsg'=>'params error',
                    ),
                );
        }
        elseif ($params['accesstoken']!=$_SESSION['wx_accesstoken']) {
            //accesstoken不正确
            $data = array(
                'msgtype' => 'error',
                'params' => array(
                    'errid' => 403,
                    'errmsg' => 'accesstoken error',
                    ),
                );
        }
        elseif (!$roomtype) {
            $rooms = M('Goods')->alias('t1')->field('t1.id,t1.name,t2.pics_origin as photo,t1.price,t3.type_name as type')->join('left join goodspics as t2 on t2.goods_id = t1.id')->join("left join type as t3 on t3.type_id = t1.type_id")->select();
            $data = array(
                'msgtype' => 'room_list',
                'params' => array(
                    'rooms' => $rooms,
                    'roomtype' => $roomtype,
                    ),

                );
        }
        elseif ($roomtype = 1) {
           $rooms = M('Goods')->alias('t1')->where("t1.type_id = 1")->field('t1.id,t1.name,t2.pics_origin as photo,t1.price,t3.type_name as type')->join('left join goodspics as t2 on t2.goods_id = t1.id')->join("left join type as t3 on t3.type_id = t1.type_id")->select();
           $data = array(
                'msgtype' => 'room_list',
                'params' => array(
                    'rooms' => $rooms,
                    'roomtype' => $roomtype,
                    ),
            );
        }
        elseif ($roomtype = 2) {
           $rooms = M('Goods')->alias('t1')->where("t1.type_id = 2")->field('t1.id,t1.name,t2.pics_origin as photo,t1.price,t3.type_name as type')->join('left join goodspics as t2 on t2.goods_id = t1.id')->join("left join type as t3 on t3.type_id = t1.type_id")->select();
           $data = array(
                'msgtype' => 'room_list',
                'params' => array(
                    'rooms' => $rooms,
                    'roomtype' => $roomtype,
                    ),
            );
        }
        elseif ($roomtype = 3){
           $rooms = M('Goods')->alias('t1')->where("t1.type_id = 3")->field('t1.id,t1.name,t2.pics_origin as photo,t1.price,t3.type_name as type')->join('left join goodspics as t2 on t2.goods_id = t1.id')->join("left join type as t3 on t3.type_id = t1.type_id")->select();
           $data = array(
                'msgtype' => 'room_list',
                'params' => array(
                    'rooms' => $rooms,
                    'roomtype' => $roomtype,
                    ),
            );
        }
        $data = json_encode($data,JSON_UNESCAPED_UNICODE);
        return $data;
    }

    /*
     * 进入房间
     * by Ljf 2018-4-21
     */
    public function rooms(){
        $a = $GLOBALS['HTTP_RAW_POST_DATA']; 

        $a = json_decode($a,true);
        
        $type = $a['msgtype'] ? $a['msgtype'] : "";
        $timestap = $a['timestamp'] ? $a['timestamp'] : '';
        $params = $a['params'] ? $a['params'] : '';
        $signature = $a['signature'] ? $a['signature'] : '';

        switch($type){
            case 'enter_room':
                echo $this->enter_room($params);
                break;
            default:
                $data = array(
                    'msgtype'=>'error',
                    'params' => array(
                        'error' => 403,
                        'errmsg' => 'msgtype error',
                        ),
                    );
                $data = json_encode($data,JSON_UNESCAPED_UNICODE);
                return $data;
        }
    }


    public function enter_room($params){
        
        $equipment = M('Equipment')->where(['goods_id'=>$params['roomid']])->find();

        if (!$params['roomid']) {
            //参数为空
            $data = array(
                'msgtype'=>'error',
                'params' =>array(
                    'error' => 403,
                    'msgtype'=>'roomid error',
                    ),
                );
        }else{

            $equipment = M('Equipment')->where(['id'=>$equipment['id']])->find();
          
            //验证通过,返回数据
            // $equipment = M('Equipment')->where([''])
            $data = array(
                'msgtype'=>'room_list',
                'params' => array(
                    'gameserver' => NULL,
                    'machineid' => $equipment['id'],
                    'camera0'   => $equipment['live_channel1'],
                    'camera1'   => $equipment['live_channel2'],
                    'controller' => '正在进行游戏的用户名',//非必传
                    ),
                );
        }
        $data = json_encode($data,JSON_UNESCAPED_UNICODE);
        return $data;
    }   

    public function test(){
        $data = M('Goods')->alias('t1')->where("t1.type_id = 1")->field('t1.id,t1.name,t2.pics_mid as photo,t1.price,t3.type_name as type')->join('left join goodspics as t2 on t2.goods_id = t1.id')->join("left join type as t3 on t3.type_id = t1.type_id")->select();
        $roomtype = M('Goods')->alias("t1")->join("left join type as t2 on t1.type_id = t2.type_id")->getField('t2.type_id,t2.type_name',true);
        $datas = array(
            'msgtype' => 'room_list',
                'params' => array(
                    'rooms' => $data,
                    ),
            );
       // dump($roomtype);die;

        $roomtype = M('Goods')->alias("t1")->join("left join type as t2 on t1.type_id = t2.type_id")->getField('t2.type_id,t2.type_name',true);
                $data = array(
                    'msgtype' => 'room_list',
                    'params'  => array(
                        'userid'  => 1,
                        'username'=> 'ssss',
                        'avatar'  => '',
                        'silver'  => '账户银币数',
                        'gold'    => '账户金币数',
                        'rank'    => '玩家等级',
                        'award'   => '登录奖励',
                        'roomtype' => $roomtype,
                    ),
                );
               $rooms = M('Goods')->alias('t1')->field('t1.id,t1.name,t2.pics_mid as photo,t1.price,t3.type_name as type')->join('left join goodspics as t2 on t2.goods_id = t1.id')->join("left join type as t3 on t3.type_id = t1.type_id")->select();
               
    }
    public function pay(){
        $this->display();
    }
}