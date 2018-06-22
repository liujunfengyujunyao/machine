<?php
namespace Home=Controller;
use Think\Controller;
header('Access-Control-Allow-Origin:*');
header("Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept");
class Diliang2Controller extends Controller{
	//认证用户
	public function userlogin(){
		$params = $GLOBALS['HTTP_RAW_POST_DATA'];
		$params = json_decode($params,true);
		$url = "http://43.524.90.98/index.php/Home/Iwawa/iwawa";
		$data = array(
			'msgtype' => 'client_auth',
			'params' => $params,
			);
		$return = json_curl($url,$data);
		$return = json_decode($return,true);
		if ($return['useruuid']) {
			$accesstoken = md5(time());
			//成功
			$useruuid = M('all_user')->where(['uuid'=>$return['useruuid']])->find();
			$id = $useruuid['id'];
			if ($useruuid) {
				//已经存在
				$data = array(
					'userid' => $useruuid['id'],
					'accesstoken' => $accesstoken,
					);
			}else{
				//不存在,添加
				//添加到用户表中(添加owner所属客户端的信息)
				$user = M('all_user')->add(array('uuid'=>$return['useruuid'],'addtime'=>time(),'owner'=>11));
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

		if (time()-$parmas['timestamp']>10) {
			
			$data = array(
				'errid' => 10001,
				'timestamp' => time(),
				);
		}elseif (!$params['userid']) {
			
			//参数缺失
			$data = array(
				'msgtype' => 'error',
				'params' => array(
					'errid' => 10003,
					'errmsg' => 'accesstoken error',
					),
				);
		}elseif ($params['accesstoken']!=$_SESSION['access_token']) {
			
			//accesstoken不对
			$data = array(
				'msgtype' => 'error',
				'params' => array(
					'errid' => 10003,
					'errmsg' => 'accesstoken error',
					),
				);
		}else{
			$url = "http://43.524.90.98/index.php/Home/Iwawa/iwawa";
			$data = array(
				'msgtype' => 'get_current_user_info',
				'params' => $params,
				);
			$return = json_curl($url,$data);
			$data = json_decode($return,true);
		}

		$data = json_encode($data,JSON_UNESCAPED_UNICODE);
		echo $data;
	}

	public function get_room_list(){
		$params = $GLOBALS['HTTP_RAW_POST_DATA'];
		$params = json_decode($params,true);
		$type = $params['type'];
		$room = $this->available($type);
		$data = array(
			'msgtype' => 'room_list',
			'room' => $room,
			);

		$data = json_encode($data,JSON_UNESCAPED_UNICODE);
		echo $data;
	}

	public function available($type){
		//pid为9是Diliang2  4是ce
		if ($type == 1) {
			$rooms = M('Goods')
			->alias('t1')
			->distinct(true)
			->where("t1.type_id = 1 and t4.status!=0 and t4.pid = 11")
			->field("t1.id as roomid,t1.name,t2.pics_origin as photo,t1.price,t3.type_name as type")
      		->join("left join goodspics as t2 on t2.goods_id = t1.id")
      		->join("left join type as t3 on t3.type_id = t1.type_id")
      		->join("left join equipment as t4 on t4.goods_id = t1.id")
      		->select();
		}elseif($type == 2){
	      $rooms = M('Goods')
	   
	      ->alias('t1')
	      ->distinct(true)
	      ->where("t1.type_id = 2 and t4.status!=0 and t4.pid = 11")//查询彩票机的
	      ->field("t1.id as roomid,t1.name,t2.pics_origin as photo,t1.price,t3.type_name as type")
	      ->join("left join goodspics as t2 on t2.goods_id = t1.id")
	      ->join("left join type as t3 on t3.type_id = t1.type_id")
	      ->join("left join equipment as t4 on t4.goods_id = t1.id")
	      ->select();
	    }elseif($type == 3){
	        $rooms = M('Goods')
	      ->alias('t1')
	      ->distinct(true)
	      ->where("t1.type_id = 3 and t4.status!=0 and t4.pid = 11")
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
	      ->where("t5.isOnline = 1 and t4.pid = 11")//在线 属于他
	      ->field("t1.id as roomid,t1.name,t2.pics_origin as photo,t1.price,t3.type_name as type")
	      ->join("left join goodspics as t2 on t2.goods_id = t1.id")
	      ->join("left join type as t3 on t3.type_id = t1.type_id")
	      ->join("left join equipment as t4 on t4.goods_id = t1.id")
	      ->join("left join machine as t5 on t5.uuid = t4.uuid")
	      ->select();
	    }
	    
	    foreach ($rooms as $key => &$value) {
	      $available = M('Goods')->alias('t1')->where(['t2.status'=>1,'t2.goods_id'=>$value['roomid']])->join("left join equipment as t2 on t2.goods_id = t1.id")->find();
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
  		if (time()-$params['timestamp']>10) {
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
  				'gamelosg' => $game_logs,
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
  		if (time()-$params['timestamp']>10) {
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
  		if (time()-$params['timestamp']>10) {
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

  	//获取订单记录
  	public function get_order_logs(){
  		$params = $GLOBALS['HTTP_RAW_POST_DATA'];
  		$params = json_decode($params,true);
  		$user = M('all_user')->where(['id'=>$params['userid']])->find();
  		$uuid = $user['uuid'];
  		$order = M('tbl_order')->where(['uuid'=>$uuid])->select();
  		//获取tbl_order表中属于这个用户的订单id
  		if (time()-$params['timestamp']>10) {
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



  	//用来接收游戏服务器传输的数据
  public function payment(){

    $params = $GLOBALS['HTTP_RAW_POST_DATA'];
    file_put_contents("Diliang2.txt",$params);
    $params = json_decode($params,true);

    $type = $params['msgtype'] ? $params['msgtype'] : "";
    // $params = $params['params'];
    
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
     
	    $url = "http://www.machine.com/Home/Iwawa/iwawa";
	    $data = array(
	    	'msgtype' => 'payment_request',
	    	'params' => $params,
	    	);

	    $return = json_curl($url,$data);
      // var_dump($return);die;
	    // $return = json_decode($return,true);
	     return $return;
	}
  //撤销扣款
  public function payment_cancel($params){
     
      $url = "http://www.machine.com/Home/Iwawa/iwawa";
      $data = array(
        'msgtype' => 'payment_cancel',
        'params' => $params
        );
      $return = json_curl($url,$data);
      // var_dump($return);die;
      return $return;
      // $return = json_decode($return,true);

  }


  //游戏结果
  public function game_result($params){
      //接收从游戏服务器发送过来的游戏结果
      
     
      
      //发送给iwawa服务器
      $goods = M('Goods')->where(['id'=>$params['roomid']])->find();
     
      $res['equipment_id'] = $params['machineid'];
      $res['got_gift'] = $params['result'];
      $res['userid'] = $params['userid'];//区别 iwawa使用的是uuid
      $res['uuid'] = M('all_user')->where(['id'=>$params['userid']])->getField("uuid");
      $res['goods_id'] = $goods['id'];
      $res['tag'] = time()+100;//后期改
      $res['end_time'] = time();
      $result = M('tbl_game_log')->add($res);
     
      //将游戏记录存入数据库
      if ($result) {
         $url = "http://www.machine.com/Home/Iwawa/iwawa";
         $data = array(
          'msgtype' => 'game_result',
          'params' =>array(
        'useruuid' => $res['uuid'],
        'roomid' => $params['roomid'],
        'machineid' => $params['machineid'],
        'gamelogid' => $result,
        'result' => $params['result'],
        'timestamp' => time(),
        'signature' => '测试',
            ),
          );

         //调用IwawaController下的game_result方法 将数据发送到接入方服务器
        $data = json_curl($url,$data);
       
        // var_dump($data);die;
        // var_dump($data);die;
        // $data = json_decode($return,true);
        
      }

        $data = json_encode($data,JSON_UNESCAPED_UNICODE);
        echo $data;
        
  }
}