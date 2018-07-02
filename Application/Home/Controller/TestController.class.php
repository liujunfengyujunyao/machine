<?php
namespace Home\Controller;
use Think\Controller;
header('Access-Control-Allow-Origin:*');
header("Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept");
class TestController extends Controller{
	/**
     * 统一入口
     */
    private $api_sign = 'vbhkneomak2naJSD9Ddjks901sj';//接口参数
	public function index($id){
		if (empty($id)) {
			$this->error('请重试');
		}else{
			$member = M('Member')->where(['id'=>$id])->find();
			$list = M('Goods')
    		->alias('t1')
			->field("t1.name as goods_name,t1.price,t2.pics_mid")
			->join("left join goodspics as t2 on t2.goods_id = t1.id")
			->select();
			
			$data = array(
				'member' => $member,
				'list' => $list,
				);
			$return = array('res'=>1,'data'=>$data);
		
			dump(json_encode($return,JSON_UNESCAPED_UNICODE));die;
		}
		return json_encode($return,JSON_UNESCAPED_UNICODE);
	}
	public function test($id){
		//用post方式
		$id = I('post.id');
		$token = I('post.token');
		//接口验证
		if(!$this->checkApi($token)){
            $return = array('msg'=>'Token Error');
            $this->ajaxReturn($data);
        }
        if(empty($id)){
        	$return = array('res'=>2,'msg'=>'id not exist');
        }else{
        //t1为equipment,t2为type,t3为goods,t4为goodspics,machine为t5
		$data1 = M('Equipment')
		      ->alias('t1')
		      ->field('t1.equipment_name,t2.type_name,t1.equipment_price,t1.time_limit,t4.pics_mid,t5.isOnline')
		      ->join('left join type as t2 on t2.type_id = t1.equipment_type')
		      ->join("left join goods as t3 on t3.id = t1.goods_id")
		      ->join("left join goodspics as t4 on t4.goods_id = t3.id")
		      ->join("left join machine as t5 on t5.uuid = t1.uuid")
		      ->select();
		//t1为goodspics表,t2为good,t3为equipment,t4为type,t5为machine
		$data2 = M('Goodspics')
			  ->alias('t1')
			  ->field("t1.pics_mid,t3.equipment_name,t4.type_name,t3.equipment_price,t3.time_limit,t5.isOnline")
			  ->join("left join goods as t2 on t2.id = t1.goods_id")
			  ->join("left join equipment as t3 on t3.goods_id = t2.id")
			  ->join("left join type as t4 on t4.type_id = t3.equipment_type")
			  ->join("left join machine as t5 on t5.uuid = t3.uuid")
			  ->select();
		//t1为goods,t2为goodspics,t3为equipment,t4为type,t5为machine
		$data3 = M('Goods')
			  ->alias('t1')
			  ->field("t1.name as goods_name,t1.id as goods_id,t2.pics_mid as goods_pics,t4.type_id as equipment_type,t3.id as equipment_id")
			  ->where("t3.goods_id = t1.id")
			  ->join("left join goodspics as t2 on t2.goods_id = t1.id")
			  ->join("left join equipment as t3 on t3.goods_id = t1.id")
			  ->join("left join type as t4 on t3.equipment_type = t4.type_id")
			  ->join("left join machine as t5 on t5.uuid = t3.uuid")
			 
			  ->select();
		$data = array(
				'equipment' => $data1,
				'goodspics' => $data2,
				'goods'     => $data3,
		);
		$return = array('res'=>1,'data'=>$data);
	
        }
		
        	return json_encode($data);
	}
	/**
     * 接口验证
     */
    private function checkApi($key){
        $api = $this->api_sign;
        if($key!=$api){
            return false;
        }
        return true;
    }
    public function ceshi(){
    	$list = M('Goods')
    		->alias('t1')
			->field("t1.name as goods_name,t1.price,t2.pics_mid")
			->join("left join goodspics as t2 on t2.goods_id = t1.id")
			->select();
			dump(json_encode($list));die;

    }

   	// public function user_login(){

   	// }
   	public function api(){
   		$url = 'http://diamond.com/Home/Api/user_login';
   		$data = array(
   			'msgtype'=>'login_auth',
   			'params'=>array(
   				'userid'=>666,
   				'timestamp'=>1522292040,
   				),
   			);
   		$return = curl_request($url,'POST',$data);
   		$return = json_decode($return,1);
   		dump($return);die;
   	}
   	public function diamond(){
   		if (isset($_GET['code'])){
   			$url = "http://192.168.1.164/Home/Api/user_login?code=".$_GET['code'];
   			$data = array(
   				'msgtype'=>'login_auth',
   				'timestamp'=>1522292040,
   				);
   			$return = curl_request($url,'POST',$data);
   			$return = json_decode($return,1);
   			dump($return);die;
   		}
   		$url = "http://192.168.1.164/Home/Api/user_login";
   		$data = array(
   			'msgtype'=>'login_auth',
   			'timestamp'=>1522292040,
   			);
   		$return = curl_request($url,'POST',$data);
   		$return = json_decode($return,1);
   		Header("Location: $return");
   		exit();
   		// dump($return);die;
   	}
   	//模拟获取回调地址页面
   public function a(){
   		$url = "http://192.168.1.164/Home/Api/user_login";
   		$data = array(
   			'msgtype'=>'get_code',
   			// 'timestamp'=>1522292040,
   			);
   		$return = curl_request($url,'POST',$data);

   		$return = json_decode($return,1);dump($return);die;
   		$url = $return['params']['oauth_url'];

   		Header("Location: $url");
   		exit();
   }
   //模拟提交code返回用户信息
   // public function huidiao(){
   // 		$url = "http://192.168.1.164/Home/Api/user_login";
   // 		$data = array(
   // 			'msgtype'=>'login_auth',
   // 			'timestamp'=>1522292040,
   // 			// 'code'=>$_GET['']
   // 			);
   // 		$return = curl_request($url,'POST',$data);
   // 		$return = json_decode($return,1);
   // 		dump($return);die;
   // 		dump($return);die;
   // }
   public function c(){
        $url = "http://www.machine.com/Home/Api/lobby";//你的接口地址
        $data = array(
               'msgtype' => 'get_room_list',
               'params'=>array(
               	'userid'=>666,
               	'accesstoken'=>666,
               	),
            );      
        // $data = json_encode($data,JSON_UNESCAPED_UNICODE);
        // dump($data);die;  
        // dump($data);die;
        $return = json_curl($url,$data);
        // dump($return);die;
		    // $return =curl_request($url,'POST',$data);
        // $return = json_decode($return,1);
        dump($return);die;
       
}
  //测试显示授权页面
  public function wx(){
    $url = 'http://192.168.1.164/Home/Api/user_login';
    $data = array(
      'msgtype'=>'get_code',
      );
    $return = json_curl($url,$data); 
    $return = json_decode($return,1);
   // dump($return);die;
    // dump($return);die;
    $oauth_url = $return['params']['oauth_url'];
    Header("Location:$oauth_url");
    exit();
    // var_dump($return);die;
  }
  //测试发送code返回用户信息
 public function code(){
    $code = $_GET;
    $url = 'http://192.168.1.164/Home/Api/user_login';
    $data = array(
        'msgtype' => 'login_auth',
        'params' => $code,
      );
    
    $return = json_curl($url,$data);
    $return = json_decode($return,1);
    dump($return);die;
 }


 public function sql(){
   $data = M('Goods')->alias('t1')->field('t1.id,t1.name,t2.pics_mid as photo,t1.price,t3.type_name as type')->join('left join goodspics as t2 on t2.goods_id = t1.id')->join("left join type as t3 on t3.type_id = t1.type_id")->select();

    $room = M('Equipment')->where(['goods_id'=>4])->group('id')->limit(1)->select();
    dump($room);die;
 }
 public function rooms(){
  $url = 'http://www.machine.com/Home/Api/rooms';
  $data = array(
    'msgtype' => 'enter_room',
    'params' => array(
      'roomid' => 6,
      ),
    );
    
    $return = json_curl($url,$data);
   
    $return = json_decode($return,1);
    dump($return);die;
 }

 public function enter_room(){
  $url = 'http://192.168.1.164/Home/Api/rooms';
  $data = array(
    'msgtype' => 'entrt_room',
    'params' => array(
      'roomid' => 6,
      ),
    );
 }
 public function uselogin(){
   
    $url = 'http://www.machine.com/Home/Userlogin/get_code';
    $return = json_curl($url);
   
    // $return = curl_request($url);
   
    $return = json_decode($return,1);
    dump($return);die;
 }

 public function get_room_list(){
    $url = "http://www.machine.com/Home/Rooms/get_room_list";
    $data = array(
      'type' => 3,
      );

    $data = json_encode($data);
    dump($data);die;
    $return = json_curl($url,$data);

    // dump($return);die;
    $return = json_decode($return,1);
    dump($return);die;
 }

 public function enter_room2(){
    $url = 'http://www.machine.com/Home/Rooms/enter_room';
    $data = array(
      'roomid'=>4,
      );
    // $data = json_encode($data);
    $return = json_curl($url,$data);
    dump($return);die;
 }
 //Sever/user_auth  (服务器之间的接口)ok
 public function user_auth(){
    $url = "http://www.machine.com/Home/Sever/user_auth";
    $data = array(
      'userid' => 35,
      'timestamp' => time(),
      );
    $return = json_curl($url,$data);
    dump($return);die;

 }

 public function payment_request(){
    $url = "http://www.machine.com/Home/Sever/payment";
    $data = array(
      'msgtype' => 'payment_request',
      'userid'  => 35,
      'machineid' => 1,
      'amount'  => 10,
      'type'  => 'gold',
      'timestamp' => time(),
      );
    // dump(json_encode($data));die;
    $return = json_curl($url,$data);

    $return = json_decode($return,true);
    dump($return);die;
 }

 public function payment_cancel(){
    $url = "http://www.machine.com/Home/Sever/payment";
    $data = array(
      'msgtype' => 'payment_cancel',
      'userid'  => 35,
      'machineid' => 1,
      'amount'  => 10,
      'type'  => 'gold',
      'timestamp' => time(),
      );
    // dump(json_encode($data));die;
    $return = json_curl($url,$data);
    dump($return);die;
 }

 public function game_result(){
    $url = "http://www.machine.com/Home/Sever/payment";
    $data = array(
      'msgtype' => 'game_result',
      'userid'  => 35,
      'roomid'  => 4,
      'machineid' => 35,
      'gamelogid' => 100,
      'result'   => 0,
      );
    $return = json_curl($url,$data);
    dump($return);die;
 }
 public function get_room_types(){
    $url = "http://www.machine.com/Home/Rooms/get_room_types";
   
    $return = json_curl($url,$data);
    dump($return);die;
 }
 public function get_room_list11(){
    $url = "http://www.machine.com/Home/Rooms/get_room_list";
     $data = array(
      'type' => 2,
      );
    $return = json_curl($url,$data);
    $return = json_decode($return,true);
    dump($return);die;
 }
 public function get_room_listall(){
    $url = "http://www.machine.com/Home/Rooms/get_room_list";
    $return = json_curl($url);
    $return = json_decode($return,true);
    dump($return);die;
 }

 public function get_recharge_logs(){
    $url = "http://www.machine.com/Home/Useraccount/get_recharge_logs";
    $data = array(

      );
    $return = json_curl($url,$data);
 }


 //测试获取游戏记录  ok
 public function get_game_logs(){
  $url = "http://www.machine.com/Home/Useraccount/get_game_logs";
  $data = array(
    'userid' => 35,
    'timestamp' => time(),
    );
  $return = json_curl($url,$data);

  $return = json_decode($return,true);
  dump($return);die;
 }

 //将tbl_game_log的status改为1(已申请),tbl_order添加此条信息,status默认为0(未发货)
 public function create_order(){
    $url = "http://www.machine.com/Home/Useraccount/create_order";
    $data = array(
      'userid' => 35,
      'gamelogid' => 20,
      'roomid' => 5,
      'photo'  =>'tupian',
      'name'  => 'ceshi',
      'tel'   => 13683141819,
      'addresss' => '天津',
      'timestamp' => time(),
      );
    $return = json_curl($url,$data);
    dump($return);die;
 }
 //测试获取用户信息添加的参数  count  soket_count success_count
 public function get_current_user_info(){
    $url = "http://www.machine.com/Home/Userlogin/get_current_user_info";
    $data = array(
      'userid' => 35,
      'timestamp' => time(),
      );
    $return = json_curl($url,$data);
    dump($return);die;
 }

 //获取到所有订单列表
 public function get_order_logs(){
  $url = "http://www.machine.com/Home/Useraccount/get_order_logs";
  $data = array(
    'userid' => 35,
    'timestamp' => time(),
    );
    $return = json_curl($url,$data);
    $return = json_decode($return);
    dump($return);die;
 }

 public function updata_order(){
   $url = "http://www.machine.com/Home/Useraccount/updata_order";
   $data = array(
    'userid' => 35,
    'orderlogid' => 9,
    'roomid' => 5,
    'name' => 'xiugai',
    'tel' => 18222874465,
    'addresss' => '上海',
    'timestamp' => time(),
    );
   $return = json_curl($url,$data);
   dump($return);die;
 }

 public function iwawa(){
   $params = $GLOBALS['HTTP_RAW_POST_DATA'];
    $url = "http://wwj.94zl.com/iwawa/client_auth";
    // $url = "http://www.machine.com/Home/Test/ccc";
    $return = json_curl($url,$params);  
    $return = json_decode($return,true);

    if ($return) {
      //成功
      $useruuid = M('iwawa_user')->where(['uuid'=>$return])->find();
      if ($useruuid) {
        $data = array(
          'userid' => $useruuid['id'],
          'accesstoken' => md5(time()),
          );
      }else{
        $user = M('iwawa_user')->add(array('uuid'=>$return));
        $data = array(
          'userid' => $user,
          'accesstoken' => md5(time()),
          );
      }
      
    }else{
      $data = array(
        'errid' => 10003,
        );
      
    }
    $data = json_encode($data,JSON_UNESCAPED_UNICODE);
    echo $data;

 }
 function json_curl($url, $para ){

    $data_string=json_encode($para,JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP | JSON_UNESCAPED_UNICODE);
    $curl = curl_init($url);
    curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "POST");
    curl_setopt($curl, CURLOPT_POSTFIELDS, $data_string);//$data JSON类型字符串
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, true);//SSL证书认证
    curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 2);//严格认证
    curl_setopt($curl, CURLOPT_HTTPHEADER, array('Content-Type: application/json', 'Content-Length: ' . strlen($data_string)));
    $result = curl_exec($curl);
    curl_close($curl);
    return $result;
}
public function userlogin(){
    $params = $GLOBALS['HTTP_RAW_POST_DATA'];
     var_dump($params);die;
    // print $params;die;
    $params = json_decode($params,true);
    $data = array(
      'params' => $params,
      'timestamp' => 111,
      'signature' => "测试",
      );
    $url = "http://wwj.94zl.com/iwawa/client_auth";
    // $url = "http://www.machine.com/Home/Test/ccc";
    $return = json_curl($url,$data); 
    // dump($return);die;
    $return = json_decode($return,true); 
    if ($return['useruuid']) {

      //成功
      $useruuid = M('iwawa_user')->where(['uuid'=>$return['useruuid']])->find();
      if ($useruuid) {
        $data = array(
          'userid' => $useruuid['id'],
          'accesstoken' => md5(time()),
          );
      }else{
        $user = M('iwawa_user')->add(array('uuid'=>$return['useruuid']));
        $data = array(
          'userid' => $user,
          'accesstoken' => md5(time()),
          );
      }
      
    }else{
      $data = array(
        'errid' => 10003,
        );
      
    }
    // header("Access-Control-Allow-Origin: *");
    $data = json_encode($data,JSON_UNESCAPED_UNICODE);
    echo $data;



  }
  public function cm(){
    $params = $GLOBALS['HTTP_RAW_POST_DATA'];

    // $url = "http://wwj.94zl.com/iwawa/client_auth";
    $url = "http://www.machine.com/Home/Test/ccc";
    $return = json_curl($url,$params);  
   dump($return);die;
    $return = json_decode($return,true);
   // dump($return);die;
    if ($return) {
      //成功
      $useruuid = M('iwawa_user')->where(['uuid'=>$return])->find();
      if ($useruuid) {
        $data = array(
          'userid' => $useruuid['id'],
          'accesstoken' => md5(time()),
          );
      }else{
        $user = M('iwawa_user')->add(array('uuid'=>$return));
        $data = array(
          'userid' => $user,
          'accesstoken' => md5(time()),
          );
      }
      
    }else{
      $data = array(
        'errid' => 10003,
        );
      
    }
    $data = json_encode($data,JSON_UNESCAPED_UNICODE);
    echo $data;
  }

  public function ccc(){
    // $params = $params = $GLOBALS['HTTP_RAW_POST_DATA'];
    $uuid = array('useruuid'=>11111);
    $uuid = json_encode($uuid,JSON_UNESCAPED_UNICODE);
    // dump($uuid);die;
    echo $uuid;
    
  }

  public function sh(){
    $url = "http://wwj.94zl.com/iwawa/client_auth";
    $params = array("id"=>10);
    $data = array(
      'params' => $params,
      'timestamp' => 111,
      "signature" => "测试",
      );

    // dump(json_encode($params,JSON_UNESCAPED_UNICODE));die;
    $return = json_curl($url,$data);

    dump($return);die;
   echo $return;
  }

  public function i(){
    $url = "http://www.machine.com/Home/Test/userlogin";
    $data = array('id'=>1);
    $return = json_curl($url,$data);
    dump($return);die;
  }
  public function hh(){
    $url = "http://43.254.90.98:8080/index.php/Home/test/userlogin";
    $data = "aaaa";
    $return = json_curl($url,$data);
    dump($return);die;
  }

  public function kk(){
    $url = "http://43.254.90.98:8080/index.php/Home/test/userlogin";
    $params = array(
      'id' => 1,
      );
    $return = json_curl($url,$params);
    dump($return);die;
  }
   public function r(){
    $url = "http://192.168.1.164/Home/Diliang/userlogin";
    $params = array('id'=>1);
    $data = array(
      'params' => $params,
      );
    $return = json_curl($url,$data);
    dump($return);die;
  }

  public function lu(){
    $data = array(
      'msgtype' => 'payment_request',
      'userid' => 1,
      // 'gamelogid' =>88, 
      'paymentid'=>111,
      'timestamp' => time(),
      'roomid' => 4,
      'machineid'=>1,
      'price' => 1,
      );
    // $url = "http://www.machine.com/Home/Diliang/create_order";

    $url = "http://www.machine.com/Home/Diliang/payment";
    
    $return = json_curl($url,$data);
    // $return = json_decode($return,true);
    dump($return);die;
  }

  public function cc(){
    $data = array(
      'id' => 1,
      );
    $url = "http://192.168.1.3/Home/Diliang/userlogin";
    $return = json_curl($url,$data);
    dump($return);die;
   
  }
  public function m(){

       $data = array(
      'msgtype' => 'game_result',
      'userid' => 123,
      'machineid' => 8,
      'result' => 0
      );
    $url = "http://192.168.1.3/index.php/Home/Diliang/payment";
    $return = json_curl($url,$data);
    dump($return);die;
  }

  public function l(){
    $data = array(
      'id' => 22,
      );
    // $url = "http://43.524.90.98/index.php/Home/Diliang/userlogin";
    $url = "http://192.168.1.3/index.php/Home/Diliang/userlogin";
    // $url = "http://www.machine.com/home/diliang/userlogin";
    $return = json_curl($url,$data);
    dump($return);die;
  }
  //客户端登陆验证,获取用户信息
  public function tree(){
    $data = array(
      'userid' => 3,
      'timestamp' => time(),
      'signature' => '测试',
      );
    // $url = "http://192.168.1.3/index.php/Home/Diliang/get_room_types";
    $url = "http://www.machine.com/Home/Diliang/get_room_list";
    $return = json_curl($url);
    dump($return);die;
  }
  //测试内网获取游戏记录
  public function tree2(){
    $data = array(
      'userid' => 1,
      'timestamp' => time(),
      'signature' => '测试',
      ); 
    // $url = "http://www.machine.com/Home/Diliang/get_game_logs";
    $url = "http://192.168.1.3/index.php/Home/Diliang/get_game_logs";
    $return = json_curl($url,$data);
    dump($return);die;
  }

  public function tree3(){
     $url = "http://192.168.1.3/index.php/home/diliang/payment";
     // $url = "http://www.machine.com/Home/Diliang/payment";
         $data = array(
         'msgtype' => 'game_result',
        'userid' => 2,
        'roomid' => 4,
        'machineid' => 8,
        // 'gamelogid' => 134,
        'result' => 0,
        // 'timestamp' => time(),
        // 'signature' => '测试',
        );
      dump(json_encode($data,JSON_UNESCAPED_UNICODE));die;
      $return = json_curl($url,$data);
      dump($return);die;
  }
  public function o(){
    $data = array(
      'msgtype' => "cuicuicui",
      );
    // $url = "http://www.machine.com/Home/Diliang/payment";
    // $url = "http://liujunfeng.imwork.net:41413/Home/Diliang/payment";
    // $url = "http://310975f0.nat123.cc/Home/Diliang/payment";
    $url = "http://www.12202.com.cn/machine/redirect.php";
    
    $return = json_curl($url,$data);
    dump($return);die;
  }


  //测试扣款
 public function test5(){
    $data = array(
      'msgtype' => 'payment_request',
      'userid' => 1,
      'machineid' => 2,
      'amount' => 1,
      'type' => 'gold',
      'timestamp' => time(),
      'signature' => '测试',
      );
    $url = "http://192.168.1.164/Home/Sever/payment";
    $return = json_curl($url,$data);
    dump($return);die;
 }

 //测试上海扣款
 public function test6(){
  $data = array(
      'msgtype' => 'payment_request',
      'userid' => 2,
      'machineid' => 2,
      'amount' => 5,
      'type' => 'gold',
      'timestamp' => time(),
      'signature' => '测试',
    );
  $url = "http://192.168.1.164/home/Diliang/payment";
  $return = json_curl($url,$data);

  dump($return);die;
 }
 //测试游戏结果
 public function test7(){
    $data = array(
      'msgtype' => 'game_result',
      'userid' => '2',
      'roomid' => 4,
      'machineid' => 2,
      'result' => 1,
      );
    $url = "http://192.168.1.164/index.php/home/Diliang/payment";
    $return = json_curl($url,$data);
    dump($return);die;
 }
 public function test8(){
  $data = array(
    'msgtype' => 'game_result',
    'userid' => '2',
    'machineid' => 2,
    'roomid' => 4,
    'result' => 0,
    );

  $url = "http://192.168.1.164/home/Diliang/payment";

  $return = json_curl($url,$data);

 }

 public function test9(){
  $data = array(
    'msgtype' => 'payment_request',
    'amount' => 2,
    'type' => 'gold',
    'roomid' => 4,
    'machineid' => 2,
    'timestamp' => time(),
    'signature' => '测试',
    'userid' => 1,
    );
  $url = "http://192.168.1.164/home/sever/payment";

  $return = json_curl($url,$data);
  $return = json_decode($return,true);
  
  dump($return);die;
 }

 public function diliang(){
    $data = array(
      'msgtype' => 'payment_request',
      'userid' => 111,
      'machineid' => 2,
      'amount' => 1,
      'type' => 'gold',
      'timestamp' => time(),
      'signature' => '测试',
      );
    // $url = "http://192.168.1.164/home/Diliang/payment";
    $url = "http://192.168.1.3/index.php/home/diliang/payment";
    $return = json_curl($url,$data);
    dump($return);die;
 }

 public function sever(){
  $data = array( 
    'userid' => 111,
    'timestamp' => time(),
    'signature' => '测试',
    );
  //测试获取游戏记录
  $url = "http://192.168.1.164/home/Useraccount/get_game_logs";
  $return = json_curl($url,$data);
  $return = json_decode($return,true);
  dump($return);die;

 }

 public function diliang_payment(){
    $data = array(
      'msgtype' => 'payment_request',
      
      );
    $url = "http://192.168.1.164/home/diliang/payment";
    $return = json_curl($url,$data);
 }

 public function sql2(){
  $sql = "select * from tbl_game_log where (`userid` = 1 && `got_gift` = 1 && `status` = 0)";
  $Model = M('tbl_game_log');
  $result = $Model->query($sql);
  $number1 = 7926*2.93;
  $number2 = 8000*320;
  dump($number1);dump($number2);die;
  dump($result);die;
 }
 public function vue(){
  $data = array(
    'msgtype' => 'test',
    'userid' => 1,
    );
  $url = "http://192.168.1.145/Home/Test/api";
  $return = json_curl($url,$data);
  dump($return);die;
 }


 public function tp(){
  $data = array(
    'msgtype' => 'game_result',
    'userid' => 2,
    'roomid' => 4,
    'machineid' => 2,
    'result' => 1,
    );
  $url = "http://192.168.1.3/index.php/Home/Diliang/payment";
  // $url = "http://192.168.1.164/home/diliang/payment";
  $return = json_curl($url,$data);
  var_dump($return);die;
 }

 public function yao(){
  $y = M('all_user')->where(['id'=>1])->find();
  $x = $y['openid'];
  // $x = M('all_user')->getFieldByNick('金鼎苹','openid');
  // $x = M('all_user')->getFieldByAccess_token("f43c3e15160bc6e820caf0e60695fb5e","nick");
  $x = M('all_user')->alias("t1")->where("t1.id < 5")->join("left join tbl_game_log as t2 on t2.userid = t1.id")->getField('t2.id,t2.equipment_id,t2.tag',':');

  dump($x);die;
 }

 public function cui(){
  
  // $url = "www.12202.com.cn/diamond/index.php/Home/Test/get_current_user_info";
  $url = "www.12202.com.cn/diamond/index.php/Home/Index/weixinpay_js1/userid/1";
  // $url = "www.12202.com.cn/diamond/index.php/home/index/ajax";
  // $url = "http://d7882e4c.ngrok.io/Home/Test/pipiyao";
  $return = json_curl($url);
  var_dump($return);die;
 }

 public function pipiyao(){
  $data = array(
    'msgtype' => 'test',
    );
  $data = json_encode($data,1);
  echo $data;
 }

 public function offer(){
  $data = array(
    'userid' => 1,
    'timestamp' => 1528098209,
    'access_token' => 'f0731324282cc3b66e39e09d265f03c97b1cbfe5',
    );
  $url = "http://192.168.1.164/home/userlogin/get_current_user_info";
  // {"userid":1,"timestamp":1528098209,"access_token":"f0731324282cc3b66e39e09d265f03c97b1cbfe5"}
  $data = json_curl($url,$data);

  // $data = sha1($data['userid'].$data['timestamp'].$data['access_token']);
  dump($data);die;
 }

 public function signature(){
  $url = "http://192.168.1.164/home/userlogin/get_current_user_info";
  $data = array(
    'userid' => 1,
    'timestamp' => 1528100902,
    'signature' => "7b98bae0025db8111433c84f5b22496bca49f1fe" ,
      );
 
  $signature = json_curl($url,$data);
  
  dump($signature);die;
 }

 public function b(){
  $t = time();
  $last_start_time = mktime(0,0,0,date("m",$t),date("d",$t),date("Y",$t));
  $last_end_time = mktime(23,59,59,date("m",$t),date("d",$t),date("Y",$t));
  $x = $this->todayData();

  dump($x);die;
 }


 //测试签到
 public function sign(){
    $data = array(
      'userid' => 2,

      );
    // $data = array(
    //   'userid' => 1,
    //   );
    $url = "http://192.168.1.164/Home/Userlogin/sign";
    $return = json_curl($url,$data);
    dump($return);die;
 }

 public function sign2(){
  $data = array(
    'userid' => 1,
    );
    $url = "http://192.168.1.164/home/Userlogin/sign";
    $return = json_curl($url,$data);
    dump($return);die;

 }

 public function weixinpay(){
  $url = "www.12202.com.cn/diamond/index.php/Home/index/weixinpay_js1/userid/1";
  $return = httpGet($url);
  dump($return);die;
 }

 public function tixian(){
  $url = "http://192.168.1.164/Home/Tixian/actionAct_tixian";
  $return = json_curl($url);
  dump($return);die;
 }

 public function memcache(){
  S('key','hello memcache!');
  S('key1','hello sssss!');
  $out = S('key');
  echo $out;
 }


    // public function equipment(){
    //     $y = date("Y");
    //     $m = date("m");
    //     $d = date("d");
    //     $morningTime= mktime(0,0,0,$m,$d,$y);
    //     $end = $morningTime-1;//前一天0点的时间戳
    //     $star = $morningTime-60*60*24;//前一天23:59:59的时间戳
    //     //总
    //     $data_all = M('tbl_game_log')
    //     ->alias("t1")
    //     ->field("FROM_UNIXTIME(t1.end_time,'%Y%m%d') days,count(t1.id) count ,t1.equipment_id")
    //     ->where("t1.end_time between $star and $end")
    //     ->Group('t1.equipment_id')
    //     ->join("left join equipment as t2 on t2.id = t1.equipment_id")
    //     ->select();
    //     // $data = M('tbl_game_log')
    //     // ->alias("t1")
    //     // ->field("FROM_UNIXTIME(t1.end_time,'%Y%m%d') days,count(t1.id) count")
    //     // ->where("t1.end_time between $star and $end && t2.pid = $id")
    //     // ->Group('days')
    //     // ->join("left join equipment as t2 on t2.id = t1.equipment_id")
    //     // ->select();
    //     dump($data_all);die;
    //     //get
    // }
    // 
public function equipment2222(){
        $y = date("Y");
        $m = date("m");
        $d = date("d");
        $morningTime= mktime(0,0,0,$m,$d,$y);
        $end = $morningTime-1;//前一天23:59:59的时间戳
        $star = $morningTime-60*60*24;//前一天0点的时间戳
        //总
        $data_all = M('tbl_game_log')
        // ->alias("t1")
        ->field("count(id) count ,equipment_id")
        ->where("end_time between $star and $end")
        // ->join("left join equipment as t2 on t2.id = t1.equipment_id")
        ->Group('equipment_id')
        ->select();
        dump($data_all);die;
}

public function equipment(){
  set_time_limit(0);
      $y = date("Y");
        $m = date("m");
        $d = date("d");
        $morningTime= mktime(0,0,0,$m,$d,$y);
        $end = $morningTime-1;//前一天23:59:59的时间戳
        $star = $morningTime-60*60*24;//前一天0点的时间戳


        //总次数
        $data_all = M('tbl_game_log')
        // ->alias("t1")
        ->field("count(id) count ,equipment_id")
        ->where("end_time between $star and $end")
        // ->join("left join equipment as t2 on t2.id = t1.equipment_id")
        ->Group('equipment_id')
        ->select();
       
        //全部成功次数
        $data_get = M('tbl_game_log')
        // ->alias("t1")
        ->field("count(id) count,equipment_id")
        ->where("end_time between $star and $end && got_gift=1")
        // ->join("left join equipment as t2 on t2.id = t1.equipment_id")
        ->Group('equipment_id')
        ->select();
        //全部失败次数
        $data_notget = M('tbl_game_log')
        // ->alias("t1")
        ->field("count(id) count,equipment_id")
        ->where("end_time between $star and $end && got_gift=0")
        // ->join("left join equipment as t2 on t2.id = t1.equipment_id")
        ->Group('equipment_id')
        ->select();
        //收费游戏成功次数
        $data_gold_get = M('tbl_game_log')
        ->field("count(id) count,equipment_id")
        ->where("end_time between $star and $end && got_gift=1 && type=('gold')")
        ->Group('equipment_id')
        ->select();
        //免费游戏成功次数
       $data_silver_get = M('tbl_game_log')
       ->field("count(id) count,equipment_id")
       ->where("end_time between $star and $end && got_gift=1 && type=('silver')")
       ->Group('equipment_id')
       ->select();
       //收费游戏失败次数
       $data_gold_notget = M('tbl_game_log')
       ->field("count(id) count,equipment_id")
       ->where("end_time between $star and $end && got_gift=0")
       ->Group("equipment_id")
       ->select();
       //免费游戏失败次数
       $data_silver_notget = M('tbl_game_log')
       ->field("count(id) count,equipment_id")
       ->where("end_time between $star and $end && got_gift=0 && type=('silver')")
       ->Group('equipment_id')
       ->select();




       // gold收费游戏次数
        $gold_all = M('tbl_game_log')
        // ->alias("t1")
        ->field("count(id) count,equipment_id")
        ->where("end_time between $star and $end && type=('gold')")
        // ->join("left join equipment as t2 on t2.id = t1.equipment_id")
        ->Group('equipment_id')
        ->select();

       //silver免费游戏次数
        $silver_all = M('tbl_game_log')
        // ->alias("t1")
        ->field("count(id) count,equipment_id")
        ->where("end_time between $star and $end && type=('silver')")
        // ->join("left join equipment as t2 on t2.id = t1.equipment_id")
        ->Group('equipment_id')
        ->select();

      

        $equipment_all=M('equipment')
        ->alias('t1')
        ->field('t1.id as equipment_id ,t2.price')
        ->join('left join goods as t2 on t1.goods_id=t2.id')
        ->select();


        $equipment_silver = $equipment_all;
        $equipment_gold = $equipment_all;
        $equipment_get = $equipment_all;
        $equipment_notget = $equipment_all;
        $equipment_gold_get = $equipment_all;
        $equipment_silver_get = $equipment_all;
        $equipment_gold_notget = $equipment_all;
        $equipment_silver_notget = $equipment_all;
        //遍历输出所有机台数据
        // foreach($equipment_all as $k=>&$v){
        //   foreach($data_all as $k1=>$v1){
        //     if($v['equipment_id']==$v1['equipment_id']){
        //       $v['days'] = $v1['days'];
        //       $v['count'] = $v1['count'];
        //     }
        //   }
        //   if(!$v['days']){
        //     $v['days'] = $data_all[0]['days'];
        //     $v['count'] = '0';
        //   }
        // }
        foreach($equipment_all as $k=>&$v){
          foreach($data_all as $k1=>$v1){
            if($v['equipment_id']==$v1['equipment_id']){
              $v['count'] = $v1['count'];
            }
          }
          if(!$v['count']){
            $v['count'] = '0';
          }
        }

        foreach($equipment_gold as $k=>&$v){
          foreach($gold_all as $k1=>$v1){
            if($v['equipment_id']==$v1['equipment_id']){
              $v['count'] = $v1['count'];
            }
          }
          if(!$v['count']){
            $v['count'] = '0';
          }
        }

        foreach($equipment_silver as $k=>&$v){
          foreach($silver_all as $k1=>$v1){
            if($v['equipment_id']==$v1['equipment_id']){
              $v['count'] = $v1['count'];
            }
          }
          if(!$v['count']){
            $v['count'] = '0';
          }
        }


        //抓到的
        if(!$data_get){
          foreach($equipment_get as $k=>&$v){
            $v['count'] = '0';
            }
        }else{
          foreach($equipment_get as $k=>&$v){
            foreach($data_get as $k1=>$v1){
              if($v['equipment_id']==$v1['equipment_id']){
                $v['count'] = $v1['count'];
              }
            }
          if(!$v['count']){
              $v['count'] = '0';
            }
          }
        }

        //没抓到的
        if(!$data_notget){
          foreach($equipment_notget as $k=>&$v){
            $v['count'] = '0';
            }
        }else{
          foreach($equipment_notget as $k=>&$v){
            foreach($data_notget as $k1=>$v1){
              if($v['equipment_id']==$v1['equipment_id']){
                $v['count'] = $v1['count'];
              }
            } 
            if(!$v['count']){
              $v['count'] = '0';
            }
          }
        }
 

        // 收费抓到的
        if(!$data_gold_get){
          foreach ($equipment_gold_get as $k=>&$v) {
            $v['count'] = '0';
          }
        }else{
          foreach ($equipment_gold_get as $k=>&$v) {
            foreach ($data_gold_get as $k1=>$v1) {
              if($v['equipment_id']==$v1['equipment_id']){
                $v['count'] = $v1['count'];
              }
            }
            if (!$v['count']) {
              $v['count'] = '0';
            }
          }
        }
 
        //收费没抓到的
        if (!$data_gold_notget) {
          foreach ($equipment_gold_notget as $k=>&$v) {
            $v['count'] = '0';
          }
        }else{
          foreach ($equipment_gold_notget as $k=>&$v) {
            foreach ($data_gold_notget as $k1=>$v1) {
              if ($v['equipment_id']==$v1['equipment_id']) {
                  $v['count'] = $v1['count'];
              }
            }
            if (!$v['count']) {
              $v['count'] = '0';
            }
          }
        }


        //免费抓到的
        if (!$data_silver_get) {
          foreach ($equipment_silver_get as $k=>&$v) {
            $v['count'] = '0';
          }
        }else{
          foreach ($equipment_silver_get as $k=>&$v) {
            foreach ($data_silver_get as $k1=>$v1) {
              if ($v['equipment_id']==$v1['equipment_id']) {
                  $v['count'] = $v1['count'];
              }
            }
            if (!$v['count']) {
              $v['count'] = '0';
            }
          }
        }

        //免费没抓到的
        if (!$data_silver_notget) {
         foreach ($equipment_silver_notget as $k=>&$v) {
           $v['count'] = '0';
         }
        }else{
          foreach ($equipment_silver_notget as $k=>&$v) {
            foreach ($data_silver_notget as $k1=>$v1) {
              if ($v['equipment_id']==$v1['equipment_id']) {
                  $v['count'] = $v1['count'];
              }
            }
            if (!$v['count']) {
              $v['count'] = '0';
            }
          }
        }

      // dump($equipment_all);die;
        // foreach ($equipment_all as $key => $value) {
        //     foreach ($equipment_get as $key1 => $value1) {
        //       foreach($equipment_notget as $key2 => $value2){
        //         if(($value['equipment_id']==$value1['equipment_id'])&&($value['equipment_id']==$value2['equipment_id'])){
        //           $one['manager_id'] = M('Equipment')->where(['id'=>$value['equipment_id']])->getField('pid');
        //           $one['equipment_id'] = $value['equipment_id'];
        //           $one['statistics_data'] = $star;
        //           $one['success_number'] = $value1['count'];
        //           $one['fail_number'] = $value2['count'];
        //           $one['run_count'] = $value['count'];
        //           $one['income_count'] = $value['count']*$value['price'];
        //           $one['create_time'] = time();
        //           $equipment[]=$one;
        //       }
        //         }
        //     }
        // }
       
        foreach ($equipment_all as $key => $value) {
          foreach ($equipment_gold as $key1 => $value1) {
            foreach ($equipment_silver as $key2 => $value2) {
               foreach ($equipment_get as $key3 => $value3) {
                  foreach ($equipment_notget as $key4 => $value4) {
                    foreach ($equipment_gold_get as $key5 => $value5) {
                      foreach ($equipment_gold_notget as $key6 => $value6) {
                        foreach ($equipment_silver_get as $key7 => $value7) {
                          foreach ($equipment_silver_notget as $key8 => $value8) {
                           if(($value['equipment_id']==$value1['equipment_id'])&&($value['equipment_id']==$value2['equipment_id'])&&($value['equipment_id']==$value3['equipment_id'])&&($value['equipment_id']==$value4['equipment_id'])&&($value['equipment_id']==$value5['equipment_id'])&&($value['equipment_id']==$value6['equipment_id'])&&($value['equipment_id']==$value7['equipment_id'])&&($value['equipment_id']==$value8['equipment_id'])){
                                $one['manager_id'] = M('Equipment')->where(['id'=>$value['equipment_id']])->getField('pid');
                                $one['equipment_id'] = $value['equipment_id'];//机台的id
                                $one['statistics_data'] = $star;//被统计日的时间戳
                                $one['silver_game_times'] = $value2['count'];//免费游戏次数
                                $one['silver_game_win_times'] = $value7['count'];//免费游戏成功次数
                                $one['gold_game_times'] = $value1['count'];//收费游戏运行次数
                                $one['gold_game_win_times'] = $value5['count'];//收费游戏成功次数
                                $one['success_number'] = $value3['count'];//总的成功次数
                                $one['fail_number'] = $value4['count'];//总的失败次数
                                $one['run_count'] = $value['count'];//总的运行次数
                                $one['income_count'] = $value['count']*$value['price'];//收入总数
                                $one['create_time'] = time();//生成统计日志的日期
                                $equipment[]=$one;
              }
                          }
                        }
                      }
                    }
                  }
               }
            }
          }
         
        }




           










        dump($equipment);die;
       }

       public function log(){
        $data = array(
          'msgtype' => 'payment_request',
          'userid' => 1,
          'machineid' => 2,
          'amount' => 1,
          'type' => 'gold',
          'timestamp' => time(),
          );
        $url = "http://192.168.1.164/Home/Sever/payment";
        $return = json_curl($url,$data);
        dump($return);die;
       }
       public function log2(){
        $data = array(
          'msgtype' => 'game_result',
          'paymentid' => 2018061673852,
          'userid' => 1,
          'roomid' => 4,
          'machineid' => 2,
          'result' => 0,
          );
        $url = "http://192.168.1.164/Home/Sever/payment";
        $return = json_curl($url,$data);
        dump($return);die;

       }

      public function jiekou(){
        $data = array(
          'msgtype' => 'game_result',
          'userid' => 2,
          'roomid' => 4,
          'machineid' => 11,
          'result' => 0,
          );
        // $url = "http://192.168.1.164/Home/Diliang/payment";
        $url = "http://192.168.1.3/index.php/Home/Diliang/payment";
        $return = json_curl($url,$data);
        dump($return);die;
        // dump(2);die;
      }

      public function iwawa22(){
        $data = array(
          'msgtype' => 'game_result',
          'userid' => 2,
          'roomid' => 4,
          'machineid' => 11,
          'result' => 1,
          );
        $url = "http://192.168.1.164/Home/Diliang/payment";
        $return = json_curl($url,$data);
        dump($return);die;
      }
      public function ass(){
        $data = array(
          'msgtype' => 'payment_cancel',
          'userid' => 1,
          'machineid' =>1,
          'amount' => 20,
          'type' =>'gold',
          'timestamp'=>time(),
          );
        $url = "http://www.machine.com/Home/Sever/payment";
        $return = json_curl($url,$data);
        dump($return);die;
      }

      public function ysever(){
        $url = "http://192.168.1.3/account_server";
        $data = [2,11,8];
        $return = json_curl($url,$data);
        dump($return);die;
      }
       
      public function room_list(){
        $url = "http://www.source.com/Home/Rooms/get_room_list";
        $data = array(
          'limit'=>3,
          'type' => 1,
          'userid'=>1,
          'timestamp'=>time(),
          );
        dump(json_encode($data));die;
        $return = json_curl($url,$data);
        $return = json_decode($return,true);
        dump($return);
      }
}