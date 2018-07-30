<?php
namespace Home\Controller;
use Think\Controller;
header('Access-Control-Allow-Origin:*');
header("Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept");
class DiliangController extends Controller{
	//认证用户
	public function userlogin(){
		  $params = $GLOBALS['HTTP_RAW_POST_DATA'];
      echo $params;die;
	    $params = json_decode($params,true);
	    // $url = "http://192.168.1.3/Home/Iwawa/iwawa";
      // $url = "http://192.168.1.164/Home/Iwawa/iwawa"; 
      // $url = "http://192.168.1.3/index.php/Home/Iwawa/iwawa";
      $url = "https://www.goldenbrother.cn/index.php/Home/Iwawa/iwawa";
	    $data = array(
	    	'msgtype' => "client_auth",
	    	'params' => $params,
	    	);
	    
	    $return = json_curl($url,$data);

	    $return = json_decode($return,true);
	   
	    if ($return['useruuid']) {
	    	$accesstoken = md5(time());
	    	//成功
	    	// $useruuid = M('iwawa_user')->where(['uuid'=>$return['useruuid']])->find();
        $useruuid = M('all_user')->where(['uuid'=>$return['useruuid']])->find();
	    	$id = $useruuid['id'];
	    	if ($useruuid) {
	    		//已存在  更新
          $url = "http://wwj.94zl.com/iwawa/get_current_user_info";
          $info = array(
            'useruuid' => $return['useruuid'],
            'timestamp' => time(),
            'signature' => "测试",
            );
          $info = json_curl($url,$data);
          $info = json_decode($info,true);
          //更新昵称和头像
          $user = M('all_user')->where(['id'=>$id])->save(['nick'=>$info['username'],'head'=>$info['avatar']]);
	    		$data = array(
	    			'userid' => $useruuid['id'],
	    			'accesstoken' => $accesstoken,
	    			);
	    	}else{
	    		//不存在,添加
	    		// $user = M('iwawa_user')->add(array('uuid'=>$return['useruuid'],'create_time'=>time()));
          //添加到用户表中
          //添加获取用户详细信息
          $url = "http://wwj.94zl.com/iwawa/get_current_user_info";
          $info = array(
            'useruuid' => $return['useruuid'],
            'timestamp' => time(),
            'signature' => "测试",
            );
          $info = json_curl($url,$data);
          $info = json_decode($info,true);
          $user = M('all_user')->add(array('uuid'=>$return['useruuid'],'addtime'=>time(),'nick'=>$info['username'],'head'=>$info['avatar']));
	    		$id = $user;
	    		$data = array(
	    			'userid' => $user,
	    			'accesstoken' => $accesstoken,
	    			);
	    	}
	    	$_SESSION['accesstoken'] = $accesstoken;
	    	$_SESSION['userid'] = $id;
	    }else{
	    	
	    	$data = array(
	    		'errid' => 10003,
	    		);
	    }
	    $data = json_encode($data,JSON_UNESCAPED_UNICODE);
	    echo $data;
		}
	//获取用户信息
	public function get_current_user_info(){
		$params = $GLOBALS['HTTP_RAW_POST_DATA'];
	    $params = json_decode($params,true);

	    if (time()-$params['timestamp']>30) {
	   
	    	$data = array(
	    		'errid' => 10001,
	    		'timestamp' => time(),
	    		);
	    }elseif(!$params['userid']){
	    	
	    	//参数缺失
	    	$data = array(
	    		'msgtype' => 'error',
	    		'params' => array(
	    			'errid' => 403,
	    			'errmsg' => 'params error',
	    			),
	    		);
	    }elseif($params['accesstoken']!=$_SESSION['access_token']){
	    
	    	//accesstoken不对
	    	$data = array(
	    		'msgtype' => 'error',
	    		'params'  => array(
	    			'errid' => 10003,
	    			'errmsg' => 'accesstoken error',
	    			),
	    		);
	    }else{
	    	
	    	 // $url = "http://192.168.1.164/Home/Iwawa/iwawa";
         // $url = "http://192.168.1.3/index.php/Home/Iwawa/iwawa"; 
         $url = "https://www.goldenbrother.cn/index.php/Home/Iwawa/iwawa"; 
	    	 // $uuid = M('iwawa_user')->where("id"=>)
			 $data = array(
			    'msgtype' => "get_current_user_info",
			    'params' => $params,//客户端发给我的数据(useruuid,timestamp,signature)
			    	);

			 $return = json_curl($url,$data);
			 
			 $data = json_decode($return,true);
	    }
	   
	    $data = json_encode($data,JSON_UNESCAPED_UNICODE);
	    echo $data;

	}

	public function get_room_types(){
		$roomtype = M('Goods')->alias("t1")->where("t3.state!=0 && t3.pid = 9")->join("left join type as t2 on t1.type_id = t2.type_id")->join("left join equipment as t3 on t3.goods_id = t1.id")->getField('t2.type_name');

		$roomtype = M('equipment')->alias("t1")->where("t1.pid =9 && t1.state != 0")->join("left join type as t2 on t2.type_id = t1.type")->getField("t2.type_name");
		
		$data = json_encode($roomtype,JSON_UNESCAPED_UNICODE);
		echo $data;
	}
  //获取房间列表
	public function get_room_list(){

        $params = $GLOBALS['HTTP_RAW_POST_DATA'];         
        $params = json_decode($params,true);

        $type = $params['type'];
        $room = $this->available($type);
        
        $data = array(
                'msgtype' => 'room_list',
                'rooms'   => $room,
            );

        $data = json_encode($data,JSON_UNESCAPED_UNICODE);
           // return $data;
        echo $data;
	}
	public function available($type){
    //pid为9是Diliang  4是ce
    if ($type == 1) {
      $rooms = M('Goods')
      ->alias('t1')
      ->distinct(true)
      ->where("t1.type_id = 1 and t4.state!=0")//查询娃娃机的
      ->field("t1.id as roomid,t1.name,t2.pics_origin as photo,t1.price,t3.type_name as type")
      ->join("left join goodspics as t2 on t2.goods_id = t1.id")
      ->join("left join type as t3 on t3.type_id = t1.type_id")
      ->join("left join equipment as t4 on t4.goods_id = t1.id")
      ->select();
    }elseif($type == 2){
      $rooms = M('Goods')
   
      ->alias('t1')
      ->distinct(true)
      ->where("t1.type_id = 2 and t4.state!=0")//查询彩票机的
      ->field("t1.id as roomid,t1.name,t2.pics_origin as photo,t1.price,t3.type_name as type")
      ->join("left join goodspics as t2 on t2.goods_id = t1.id")
      ->join("left join type as t3 on t3.type_id = t1.type_id")
      ->join("left join equipment as t4 on t4.goods_id = t1.id")
      ->select();
    }elseif($type == 3){
        $rooms = M('Goods')
      ->alias('t1')
      ->distinct(true)
      ->where("t1.type_id = 3 and t4.state!=0")
      ->field("t1.id as roomid,t1.name,t2.pics_origin as photo,t1.price,t3.type_name as type")
      ->join("left join goodspics as t2 on t2.goods_id = t1.id")
      ->join("left join type as t3 on t3.type_id = t1.type_id")
      ->join("left join equipment as t4 on t4.goods_id = t1.id")
      ->select();
    }else{
        $rooms = M('Goods')
      ->alias('t1')
      ->distinct(true)
      // ->where("t4.status!=0")
      ->where("t5.isOnline = 1")
      ->field("t1.id as roomid,t1.name,t2.pics_origin as photo,t1.price,t3.type_name as type")
      ->join("left join goodspics as t2 on t2.goods_id = t1.id")
      ->join("left join type as t3 on t3.type_id = t1.type_id")
      ->join("left join equipment as t4 on t4.goods_id = t1.id")
      ->join("left join machine as t5 on t5.uuid = t4.uuid")
      ->select();
    }
    
    foreach ($rooms as $key => &$value) {
      $available = M('Goods')->alias('t1')->where(['t2.state'=>1,'t2.goods_id'=>$value['roomid']])->join("left join equipment as t2 on t2.goods_id = t1.id")->find();
      if ($available) {
        $value['available'] = 1;
      }else{
        $value['available'] = 0;
      }
    }
    return $rooms;
  }


  //获取游戏记录
  	public function get_game_logs(){
  		$params = $GLOBALS['HTTP_RAW_POST_DATA'];
  		$params = json_decode($params,true);
  		$user = M('all_user')->where(['id'=>$params['userid']])->find();
  		if (time()-$params['timestamp']>30) {
  			$data = array(
  				'errid' => 10001,
  				'timestamp' => time(),
  				);
  		}elseif(!$user){
  			$data = array(
  				'errid' => 10003,
  				'errmsg' => 'auth error',
  				);
  		}else{
  			//tbl_game_log没有iwawa的userid  where('uuid')
  			$log = M('tbl_game_log')->alias("t1")->field("t1.*,t2.pics_origin,t3.name as goods_name")->where(['userid'=>$params['userid']])->join("left join goodspics as t2 on t2.goods_id = t1.goods_id")->join("left join goods as t3 on t3.id = t1.goods_id")->select();
  			$success_count = count(M('tbl_game_log')->where(['userid'=>$params['userid']])->select());
  			$stock_count = M('tbl_game_log')->where(['userid'=>$params['userid'],'got_gift'=>1,'status'=>0])->select();
  			//遍历修改数据
  			foreach ($log as $key => $value) {
  				$game_logs[$key]['gamelogid'] = $value['id'];
  				$game_logs[$key]['roomid'] = $value['goods_id'];
  				$game_logs[$key]['photo'] = $value['pics_origin'];
  				$game_logs[$key]['machined'] = $value['equipment_id'];
  				$game_logs[$key]['goods_name'] = $value['goods_name'];
  				$game_logs[$key]['start'] = $value['start_time'];
  				$game_logs[$key]['end'] = $value['end_time'];
  				$game_logs[$key]['result'] = $value['got_gift'];
  			}

  			$data = array(
  				'gamelog' => $game_logs,
  				'userid' => $params['userid'],
  				'success_count' => $success_count,//游戏成功次数,
  				'count'  =>  $count,//游戏总次数
  				'stock_count' => $stock_count,//库存个数
  				);
  		}
  		$data = json_encode($data,JSON_UNESCAPED_UNICODE);
  		echo $data;
  	}
  	//快递申请
  	public function create_order(){
  		$params = $GLOBALS['HTTP_RAW_POST_DATA'];
  		$params = json_decode($params,true);
  		$uuid = M('all_user')->where(['id'=>$params['userid']])->getField('uuid');
  		//查询发送过来的订单号是否满足邮寄标准(tbl_game_logs中的)status=0
  		$log = M('tbl_game_log')->where(['id'=>$params['gamelogid'],'status'=>0,'useruuid'=>$uuid,'got_gift'=>1])->find();
  		if (time()-$params['timestamp']>30) {
  			$data = array(
  				'errid' => 10001,
  				'timestamp' => time(),
  				);
  		}elseif (!$log) {
  			$data = array(
  				'errid' => 40002,
  				);
  		}else{
  			//将这条游戏记录的status改为1(已申请)
  			M('tbl_game_log')->where(['id'=>$params['gamelogid']])->save(['status'=>1]);
  			//将数据存入数据库中
  			$res['log_id'] = $params['gamelogid'];
  			$res['name'] = $params['name'];
  			$res['create_time'] = time();
  			$res['address'] = $params['addresss'];
  			$res['phone'] = $params['tel'];
  			$res['userid'] = $params['userid'];
  			$order_id = M('tbl_order')->add($res);
  			$data = array(
  				'orderlogid'  =>  $order_id,
  				);
  		}
  			$data = json_encode($data,JSON_UNESCAPED_UNICODE);
  			echo $data;
  	}

    //订单修改
  	public function update_order(){
  		$params = $GLOBALS['HTTP_RAW_POST_DATA'];
  		$params = json_decode($params,true);
  		$order = M("tbl_order")->where(['id'=>$params['orderlogid']])->find();
  		if (time()-$params['timestamp']>30) {
  				$data = array(
  					'errid' => 10001,
  					'timestamp' => time(),
  					);
  		}elseif(!$order){
  			$data = array(
  				'errid' => 40004,//订单号错误
  				);
  		}elseif($order['status'] == 1){
  			$data = array(
  				'errid' => 40003,//订单已经锁定
  				);
  		}else{
  			//可以修改
  			$res['name'] = $params['name'];
  			$res['phone'] = $params['tel'];
  			$res['address'] = $params['addresss'];
  			$result = M('tbl_order')->where(['id'=>$params['orderlogid']])->save($res);
  			$data = array(
  				'orderlogid' => $parmas['orderlogid'],
  				'msgtype' => 'success',
  				);
  		}

  		$data = json_encode($data,JSON_UNESCAPED_UNICODE);
  		echo $data;
  	}


      public function huancun(){
        $id = 1;
        $data = S(1);
        dump($data);
      }
    //获取订单记录
  	public function get_order_logs(){
  		$params = $GLOBALS['HTTP_RAW_POST_DATA'];
  		$params = json_decode($params,true);
  		$user = M('all_user')->where(['id'=>$params['userid']])->find();
  		$uuid = $user['uuid'];
  		$order = M('tbl_order')->where(['uuid'=>$uuid])->select();
  		//获取tbl_order表中属于这个用户的订单id
  		if (time()-$params['timestamp']>30) {
  			$data = array(
  				'errid' => 10001,
  				'timestamp' => time(),
  				);
  		}elseif (!$user) {
  			$data = array(
  				'errid' => 10003,
  				);
  		}elseif (!$order) {
  			$data = array(
  				'userid' => $params['userid'],
  				'orderlogs' => NULL,
  				);
  		}else{
  			//通过验证
  			foreach ($order as $key => $value) {
  				$res[$key]['orderlogid'] = $value['id'];
  				$res[$key]['createdate'] = $value['create_time'];
  				$res[$key]['gamelogid'] = $value['log_id'];
  				$res[$key]['roomid'] = M('tbl_game_log')->where(['id'=>$value['log_id']])->getField('goods_id');
  				$res[$key]['photo'] = M('tbl_game_log')->alias("t1")->where(['t1.id'=>$value['log_id']])->join("left join goods as t2 on t2.id = t1.goods_id")->join("left join goodspics as t3 on t3.goods_id = t2.id")->getField('pics_origin');
  				$res[$key]['status'] = $value['status'];
  				$res[$key]['name'] = $value['name'];
  				$res[$key]['tel'] = $value['phone'];
  				$res[$key]['addresss'] = $value['address'];
  				$res[$key]['trackingid'] = $value['trace_number'];//物流单号
  				$res[$key]['carrier'] = M('tbl_order')->alias('t1')->where(['t1.log_id'=>$value['log_id']])->join("left join express as t2 on t2.express_id = t1.express_id")->getField('express_name');
  				$res[$key]['delieverdate'] = NULL;
  			}
  			$data = array(
  				'orderlogs' => $res,
  				'userid'  =>  $params['userid'],
  				);
  		}
  		$data = json_encode($data,JSON_UNESCAPED_UNICODE);
  		echo $data;
  	}


    //游戏服务器认证用户
      public function user_auth(){
      //获取到从服务器接收到的数据,转换成数组
        $params = $GLOBALS['HTTP_RAW_POST_DATA'];   file_put_contents("11111111111111.txt",$params);
        $params = json_decode($params,true);  
        //var_dump($params);die;
        $user = M('all_user')->where(['id'=>$params['userid']])->find();
        $test = json_encode($user,JSON_UNESCAPED_UNICODE);
        
        $access_token = S($params['userid']);
        file_put_contents("hehehhhhhhhhhhhhh.txt",$params);
        // $access_token = M('all_user')->where(['id'=>$params['userid']])->getField('token'); 
        $signature = array(
        'msgtype' => 'login_request',
        // 'userid' => $params['userid'],
        'userid' => $params['userid'],
        'machineid' => $params['machineid'],
        'timestamp' => $params['timestamp'],
        // 'timestamp' => 1530784819,
        // 'access_token' => $_SESSION['accesstoken'],
        'access_token' => $access_token,
      );

        $signature = json_encode($signature);file_put_contents("333333333333333.txt",$signature);
        $signature = sha1($signature);
        $time = time();
        $post_time = $params['timestamp'];
        file_put_contents('timetimetime.txt',$post_time);
        file_put_contents("9999999999999999.txt",$time);
        if(time()-$params['timestamp']>30 || $params['timestamp']-time()>30){
          $data = array(
            'errid' => 10001,
            'timestamp' => time(),
            );
        }elseif (!$user) {
          $data = array(
            'errid' => 10003,
            'errmsg' => 'auth error',
            );
        }
        // elseif($params['signature'] != $signature){
        //   $data = array(
        //     'errid' => 10005,
        //     'errmsg' => 'signature error',
        //     );
        // }
        else{
          //验证成功,返回用户数组ID,用户昵称,头像地址
          $data = array(
            'userid' => intval($user['id']),
            'username' => $user['nick'],
            'avatar'  => $user['head'],
            ); 
        }
        $data = json_encode($data,JSON_UNESCAPED_UNICODE);
          echo $data;
    }


  //用来接收游戏服务器传输的数据
  public function payment(){
    $params = $GLOBALS['HTTP_RAW_POST_DATA'];
    //file_put_contents("test2.txt",$params);
    $params = json_decode($params,true);
    $type = $params['msgtype'] ? $params['msgtype'] : "";
    switch ($type) {
      case 'payment_request':
        echo $this->payment_request($params);
        break;
      case 'payment_cancel':
        echo $this->payment_cancel($params);
        break;
      case 'game_result':
        echo $this->game_result($params);
        break;
      default:
        $data = array(
          'msgtype' => 'error',
          'params' => array(
              'errid' => 403,
              'errmsg' => 'msgtype error',
            ),
          );
        
        $data = json_encode($data,JSON_UNESCAPED_UNICODE);
        echo $data;
    }
  }

  //扣款请求(应为开始游戏前游戏服务器请求)
	public function payment_request($params){
      //判断属于谁的用户
      $user = M('all_user')->where(['id'=>$params['userid']])->find();
      //var_dump($user);die;

      if ($user['openid']) {

        // $url = "http://192.168.1.3/index.php/Home/Sever/payment";
        $url = "https://www.goldenbrother.cn/index.php/Home/Sever/payment";
        // $url = "http://192.168.1.145/Home/Sever/payment";
        $return = json_curl($url,$params);
    
      }
      else{
 
        // $url = "http://192.168.1.3/index.php/Home/Iwawa/iwawa"; 
        $url = "https://www.goldenbrother.cn/index.php/Home/Iwawa/iwawa"; 
        // $url = "http://192.168.1.164/Home/Iwawa/iwawa"; 
        $data = array(
        'msgtype' => 'payment_request',
        'params' => $params,
        );

      $return = json_curl($url,$data);
     
      }
      //var_dump($return);die;
	     return $return;
	}




  //撤销扣款
  public function payment_cancel($params){
       //判断属于谁的用户
      $user = M('all_user')->where(['id'=>$params['userid']])->find();
      if ($user['openid']) {

        // $url = "http://192.168.1.3/index.php/Home/Sever/payment";
        $url = "https://www.goldenbrother.cn/index.php/Home/Sever/payment";
        // $url = "http://192.168.1.164/Home/Sever/payment";
        $return = json_curl($url,$params);
      }
      else{
        // $url = "http://192.168.1.3/index.php/Home/Iwawa/iwawa";
        $url = "https://www.goldenbrother.cn/Home/Iwawa/iwawa";
        // $url = "http://192.168.1.164/Home/Iwawa/iwawa";
        $data = array(
        'msgtype' => 'payment_cancel',
        'params' => $params
        );
      $return = json_curl($url,$data);
      }
     
      // var_dump($return);die;
      return $return;
      // $return = json_decode($return,true);

  }


  //游戏结果
  public function game_result($params){
      //判断属于谁的用户
      //接收从游戏服务器发送过来的游戏结果
      $user = M('all_user')->where(['id'=>$params['userid']])->find();
      if ($user['openid']) {
        // $url = "http://192.168.1.3/index.php/Home/Sever/payment";
        $url = "https://www.goldenbrother.cn/index.php/Home/Sever/payment";
        // $url = "http://192.168.1.164/Home/sever/payment";
        $return = json_curl($url,$params);
        return $return;
      }
     
      
      //发送给iwawa服务器
      // $goods = M('Goods')->where(['id'=>$params['roomid']])->find();
      $goods_id = M('equipment')->where(['id'=>$params['machineid']])->getField('goods_id');
      $res['equipment_id'] = $params['machineid'];
      $res['got_gift'] = $params['result'];
      $res['userid'] = $params['userid'];//区别 iwawa使用的是uuid
      $res['uuid'] = M('all_user')->where(['id'=>$params['userid']])->getField("uuid");
      // $res['goods_id'] = $goods['id'];
      $res['goods_id'] = $goods_id;
      $res['tag'] = time()+100;//后期改
      $res['end_time'] = time();
      $res['type'] = "gold";
      $result = M('tbl_game_log')->add($res);
     
      //将游戏记录存入数据库
      if ($result) {
         // $url = "http://192.168.1.164/Home/Iwawa/iwawa";
         // $url = "http://192.168.1.3/index.php/Home/Iwawa/iwawa";
         $url = "https://www.goldenbrother.cn/index.php/Home/Iwawa/iwawa";
         $data = array(
          'msgtype' => 'game_result',
          'params' =>array(
        'useruuid' => $res['uuid'],
        // 'roomid' => $params['roomid'],
        'roomid' => $goods_id,
        'machineid' => $params['machineid'],
        'gamelogid' => $result,
        'result' => $params['result'],
        'timestamp' => time(),
        'signature' => '测试',
            ),
          );

         //调用IwawaController下的game_result方法 将数据发送到接入方服务器
        $data = json_curl($url,$data);
 
        // $data = json_decode($return,true);
        
      }

        $data = json_encode($data,JSON_UNESCAPED_UNICODE);
        echo $data;
        
  }

  //机器硬件错误
  public function index(){
    $params = $GLOBALS['HTTP_RAW_POST_DATA'];
    $params = json_decode($params,true);
    if ($params['errid'] == 30002) {
      $errmsg = "机台已下线";
    }elseif ($params['errid'] == 30005) {
      $errmsg = "IO板错误";
    }else{
      $errmsg = "硬件错误";
    }
    $data = array(
      'errid' => $params['errid'],//错误ID
      'machineid' => $params['machineid'],//错误机台
      'errmsg' => $errmsg,
      'time' => time(),
      );
    M('error')->add($data);
  }

  //游戏结果测试
  public function test_game_result(){
    $data = array(
      'msgtype' => 'game_result',
      'params' => array(
      'useruuid' => '1',
      'roomid' => 4,
      'machineid' => 1,
      'result' => 0,
        ),
      );
    // $url = "http://192.168.1.164/home/Diliang/payment";
    $url = "http://192.168.1.3/index.php/Home/Iwawa/iwawa";
    $return = json_curl($url,$data);
    dump($return);die;
  }
//扣款测试
  public function test_payment_request(){
    $data = array(
      'msgtype' => 'payment_request',
      'params' => array(
        'useruuid' => 1,
        'machineid' => 1,
        'amount' => 2,
        'type' => 'gold',
        'timestamp' => time(),
        'signature' => '测试',
        ),
      );
    // $url = "http://192.168.1.164/home/Diliang/payment";
    $url = "http://192.168.1.3/index.php/Home/Iwawa/iwawa";
    $return = json_curl($url,$data);
    dump($return);die;
  }

  //扣款测试OK
public function test2(){
  $params = $GLOBALS['HTTP_RAW_POST_DATA'];

  $params = json_decode($params,true);
  $user = M('all_user')->where(['uuid'=>$params['useruuid']])->find();

  $number = $user['gold'] - $params['price'];
  if($number>=0){
    M('all_user')->where(['id'=>$user['id']])->save(['gold'=>$number]);
    $data = array(
      'useruuid' => $user['uuid'],
      'gold' => $number,
      );
  }else{
    $data = array(
      'errid' => 40001,
      );
  }
  $data = json_encode($data,JSON_UNESCAPED_UNICODE);
  echo $data;

}
public function test_payment_cancel(){
  $data = array(
      'msgtype' => 'payment_cancel',
      'params' => array(
        'useruuid' => 1,
        'machineid' => 1,
        'amount' => 2,
        'type' => 'gold',
        'timestamp' => time(),
        'signature' => '测试',
        ),
    );
  // $url = "http://192.168.1.164/Home/Diliang/payment";
  $url = "http://192.168.1.3/index.php/Home/Iwawa/iwawa";
  $return = json_curl($url,$data);

}

public function test3(){
  $params = $GLOBALS['HTTP_RAW_POST_DATA'];
  $params = json_decode($params,true);
  $user = M('all_user')->where(['uuid'=>$params['useruuid']])->find();
  $number = $user['gold'] + $params['price'];
  $res = M('all_user')->where(['id'=>$user['id']])->save(['gold'=>$number]);
  if ($res!==false) {
    $data = array(
        'useruuid' => $user['uuid'],
        'gold' => $number,
      );
  }else{
    $data = array(
      'msgtype' => 'error',
      );
  }
  $data = json_encode($data,JSON_UNESCAPED_UNICODE);
  echo $data;
}


public function test(){

    $params = $GLOBALS['HTTP_RAW_POST_DATA'];
    
    $params = json_decode($params,true);

    if ($params) {
      // echo 1;die;
      $params = array(
        'msgid' => 200,
        );
     $params = json_encode($params,JSON_UNESCAPED_UNICODE);
      echo $params;
    }else{

      $params = array(
        'errid' => 404,
        );
       $params = json_encode($params,JSON_UNESCAPED_UNICODE);
      echo $params;
     
    }
  
  }





































}