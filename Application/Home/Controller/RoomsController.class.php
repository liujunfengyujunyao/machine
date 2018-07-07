<?php

namespace Home\Controller;
use Think\Controller;
use Common\Plugin\Wxlogin;
header("content-type:text/html;charset=utf-8");
header('Access-Control-Allow-Origin:*');
header("Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept");
class RoomsController extends Controller{

  public function sess(){
    $data = $_SESSION['WA'];
    dump($data);dump(S('ha'));
  }
  public function get_room_types(){
    //查出现在商品存放于机台的种类
    $roomtype = M('Goods')->alias("t1")->where("t3.state!=0")->join("left join type as t2 on t1.type_id = t2.type_id")->join("left join equipment as t3 on t3.goods_id = t1.id")->getField('t1.type_id,t2.type_name');
    //不显示没有机台在线的商品房间
    $data = json_encode($roomtype,JSON_UNESCAPED_UNICODE);
    echo $data;
  }

  public function get_room_list(){

           $params = $GLOBALS['HTTP_RAW_POST_DATA'];         
           $params = json_decode($params,true);
           $signature = array(
            'type' => $params['type'],
            'userid' => $params['userid'],
            'timestamp' => $params['timestamp'],
            'access_token' => $_SESSION['accesstoken'],
            );
           
           $signature = json_encode($signature);
           $signature = sha1($signature);
          
           if (time()-$params['timestamp']>12) {
             $data = array(
              'msgtype' => 'error',
              'params' => array(
                'errid' => 10001,
                'timestamp' => time(),
                ),
              );
           }
           // elseif($params['signature']!=$signature){
           //    $data = array(
           //      'msgtype' => 'error',
           //      'params' => array(
           //        'errid' => 10003,
           //        'errmsg' => 'signature error',
           //        ),
           //      );
           // }
           else{
               $type = $params['type'];
               $limit = $params['limit'];
               $room = $this->available($type,$limit);
               //分页获取
            

               $data = array(
                    'msgtype' => 'room_list',
                    'rooms'   => $room,
                );
           }
         

           $data = json_encode($data,JSON_UNESCAPED_UNICODE);
           // return $data;
           echo $data;
  }

  public function enter_room2(){
        $params = $GLOBALS['HTTP_RAW_POST_DATA'];         
        $params = json_decode($params,true);
        $roomid = $params['roomid'];
        //验证是否超时
        if (time()-$params['timestamp']>12) {
           $data = array(
            'msgtype'=>'error',
            'params' => array(
              'errid'=>10001,
              'timestamp'=>time(),
              ),
            );
         }else{
              //查出现在空闲出来的机台state1为空闲
        $equipment = M('Equipment')->field('id as equipment_id')->where(['goods_id'=>$roomid,'state'=>1])->select();
        // dump($equipment);die;
        if ($equipment) {
          //如果有值,证明有空闲的机台0,0,0 取出第一台机台的信息
          $equipment_id = implode($equipment[0]);
          M('Equipment')->where(['id'=>$equipment_id])->save(['state'=>2]); //将机台状态修改为2(待机)
          //将这个机台的人数存入缓存(有人)
          $number = 1;
          S("$equipment_id",$number);
          $room = M('Equipment')->field('id as machineid,live_channel1 as camera0,live_channel2 as camera1')->where(['id'=>$equipment_id])->find();
        }else{
          //如果没有空闲的机台的话,查询出这个房间下所有的机台
          $machine = M('Equipment')->where(['goods_id'=>$roomid])->select();
          foreach ($machine as $key => $value) {
            //找出对应机台在缓存中存储的房间人数
            $data[$value['id']] = S($value['id']);
          }
          reset($data);
          //获取到人数从小到大排序后第一个元素的键值(ID)
          $equipment_id = key($data);
          $number = S("$equipment_id")+1;
          S("$equipment_id",$number);
          $room = M('Equipment')->where(['id'=>$equipment_id])->find();
        }
          $data = array(
              'gameserver' => NULL,
              'machineid'  => $room['machineid'],
              'camera0'    => $room['camera0'],
              'camera1'    => $room['camera1'],
            );
         }
         $data = json_encode($data,JSON_UNESCAPED_UNICODE);
         echo $data;


  }
  //进入房间获取房间信息
  public function enter_room(){
    //接收到客户端发送过来的roomid,查询出来这个商品下所有的机台,将机台的数组发给游戏服务器
    $params = $GLOBALS['HTTP_RAW_POST_DATA'];         
    $params = json_decode($params,true);
    //var_dump($params);die;
    //添加signature
    $signature = array(
        'userid' => $params['userid'],
        'timestamp' => $params['timestamp'],
        'access_token' => $_SESSION['accesstoken'],
      );
    $signature = json_encode($signature);
    $signature = sha1($signature);
    // $signature = 1;
    if (time()-$params['timestamp']>12) {
      $data = array(
        'msgtype' => 'error',
        'params' => array(
          'errid' => 10001,
          'timestamp' => time(),
          ),
        );
    }
    // elseif($params['signature']!=$signature){
    //   $data = array(
    //     'msgtype' => 'error',
    //     'params' => array(
    //       'errid' => 10003,
    //       'errmsg' => 'signature error',
    //       )
    //     );
    // }
    else{
      
      //获取到这个房间下的所有机台id
    $machines = M('Equipment')->where(['goods_id'=>$params['roomid']])->getField('id',true);
    $machines_ids = implode(',',$machines);
    $url = "http://192.168.1.148:7777/account_server";//游戏服务器地址
    $key = array(
      'msgtype' => 'get_machine_status',
      'machines' => $machines,
      'timestamp' => time(),
      );
    $key = sha1($key);

    //将machines改成int形
    foreach ($machines as $key => $value) {
      $machines2[] = intval($value);
    }
    $data = array(
      'msgtype'  => 'get_machine_status',
      // 'machines' => $machines,
      'machines' => $machines2,
      'timestamp' => time(),
      'signature' => $key,
      );
    
    $return = json_curl($url,$data);//发送给游戏服务器获取机台的machineid机器IDusers用户数和state是否被占用

    $state = json_decode($return,1);//转换为数组

    //获取到服务器返回的stateJSON数组
    //根据房间人数升序重组二维数组,升序人数从少到多
    $state = arraySequence($state,'users',$sort = 'SORT_ASC');
    //闲置机台(非离线,运行)
    $free = M('Equipment')->where("id in ({$machines_ids}) and state = 1")->find();
    if ($free) {
      $data = array(
        // 'gameserver' => $free['gamesever'],
        'gameserver' => "ws://192.168.1.148:7777/game_server",
        'machineid'  => $free['id'],
        'camera0'    => $free['live_channel1'],
        'camera1'    => $free['live_channel2'],
        );
    }else{
      //取出升序排序后第一个(人数最少的机台)
      reset($state);
      $state = $state['machines'][0];
      $state = M('Equipment')->where(['id'=>$state['machineid']])->find();
      $data = array(
        // 'gamesever'  => $state['gamesever'],
        'gameserver' => "ws://192.168.1.148:7777/game_server",
        'machineid'  => $state['id'],
        'camera0'    => $state['live_channel1'],
        'camera1'    => $state['live_channel2'],
        );
    }
  }
  
       $data = json_encode($data,JSON_UNESCAPED_UNICODE);
       echo $data;
  }

  public function get_banner_pictures(){
    $banner = M('Banner')->select();
    
    $data = json_encode($banner,JSON_UNESCAPED_UNICODE);
    $data = stripslashes($data);//将转义符去掉
    // echo $_GET['callback']."(".json_encode($data).")";
    echo $data;
  }


  public function test1(){
    $x = M('Equipment')->alias('t1')->join("left join goods as t2 on t2.id = t1.goods_id")->select();
    $x = arraySequence($x,'id',$sort = 'SORT_DESC');dump($x);
    reset($x);dump($x[0]);die;
    //查询出娃娃机中空闲下来的机台
  $rooms = M('Goods')->alias('t1')->where("t1.type_id = 1 && t4.state = 1")->dietinct(true)->field("t1.id as roomid,t1.name,t2.pics_origin as pjoto,t1.price,t3.type_name as type,t4.id as equipment_id")->join("left join goodspics as t2 on t2.goods_id = t1.id")->join("left join type as t3 on t3.type_id = t1.type_id")->join("left join equipment as t4 on t4.goods_id = t1.id")->select();
  dump($rooms);die;
  
  $room = count($rooms);
  return $room;//空闲下来的总数
  }


  public function ceee(){
    $type = 0;
    $return = $this->available($type);
    dump($return);
  }
  //封装判断机器是否空闲以及种类的方法
  public function available($type,$limit){

    if ($type == 1) {
      $rooms = M('Goods')
      ->alias('t1')
      ->distinct(true)
      ->where("t1.type_id = 1 and t4.state!=0 and t4.pid = 4 and t4.state!=-1")//查询娃娃机的
      ->field("t1.id as roomid,t1.name,t2.pics_origin as photo,t1.price,t3.type_name as type")
      ->join("left join goodspics as t2 on t2.goods_id = t1.id")
      ->join("left join type as t3 on t3.type_id = t1.type_id")
      ->join("left join equipment as t4 on t4.goods_id = t1.id")
      // ->limit($limit,1)
      ->limit($limit*10,10)
      ->select();
    }elseif($type == 2){
      $rooms = M('Goods')
   
      ->alias('t1')
      ->distinct(true)
      ->where("t1.type_id = 2 and t4.state!=0 and t4.pid = 4 and t4.state!=-1")//查询彩票机的
      ->field("t1.id as roomid,t1.name,t2.pics_origin as photo,t1.price,t3.type_name as type")
      ->join("left join goodspics as t2 on t2.goods_id = t1.id")
      ->join("left join type as t3 on t3.type_id = t1.type_id")
      ->join("left join equipment as t4 on t4.goods_id = t1.id")
      // ->limit($limit,1)
     ->limit($limit*10,10)
      ->select();
    }elseif($type == 3){
        $rooms = M('Goods')
      ->alias('t1')
      ->distinct(true)
      ->where("t1.type_id = 3 and t4.state!=0 and t4.pid =4 and t4.state!=-1")
      ->field("t1.id as roomid,t1.name,t2.pics_origin as photo,t1.price,t3.type_name as type")
      ->join("left join goodspics as t2 on t2.goods_id = t1.id")
      ->join("left join type as t3 on t3.type_id = t1.type_id")
      ->join("left join equipment as t4 on t4.goods_id = t1.id")
      // ->limit($limit,1)
      ->limit($limit*10,10)
      ->select();
    }else{
        $rooms = M('Goods')
      ->alias('t1')
      ->distinct(true)
      ->where("t4.state!=0 and t4.pid = 4 and t4.state!=-1")
      ->field("t1.id as roomid,t1.name,t2.pics_origin as photo,t1.price,t3.type_name as type")
      ->join("left join goodspics as t2 on t2.goods_id = t1.id")
      ->join("left join type as t3 on t3.type_id = t1.type_id")
      ->join("left join equipment as t4 on t4.goods_id = t1.id")
      // ->limit($limit,1)
      ->limit($limit*10,10)
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
    // var_dump($rooms);die;
    return $rooms;
  }
  //封装查询出空闲的机台/人数最少机台的方法
  // public function number($roomid){
  //   $roomid = 4;
  //   //查出现在空闲出来的机台state1为空闲
  //   $equipment = M('Equipment')->field('id as equipment_id')->where(['goods_id'=>$roomid,'state'=>1])->select();
  //   // dump($equipment);die;
  //   if ($equipment) {
  //     //如果有值,证明有空闲的机台0,0,0 取出第一台机台的信息
  //     $equipment_id = implode($equipment[0]);
  //     M('Equipment')->where(['id'=>$equipment_id])->save(['state'=>2]); //将机台状态修改为2(待机)
  //     //将这个机台的人数存入缓存(有人)
  //     $number = 1;
  //     S("$equipment_id",$number);
  //     $room = M('Equipment')->field('id as machineid,live_channel1 as camera0,live_channel2 as camera1')->where(['id'=>$equipment_id])->find();
  //   }else{
  //   	//如果没有空闲的机台的话,查询出这个房间下所有的机台
  //     $machine = M('Equipment')->where(['goods_id'=>$roomid])->select();
  //     foreach ($machine as $key => $value) {
  //       //找出对应机台在缓存中存储的房间人数
  //       $data[$value['id']] = S($value['id']);
  //     }
  //     reset($data);
  //     //获取到人数从小到大排序后第一个元素的键值(ID)
  //     $equipment_id = key($data);
  //     $number = S('$equipment_id')+1;
  //     S('$equipment_id',$number);
  //     $room = M('Equipment')->where(['id'=>$equipment_id])->find();
  //   }

  // }

  public function number1(){
    dump(S('1'));die;
    $room_id = 5;$number=100;$equipment_id = 2;
    $number2 = 50;$equipment_id2 = 3;
    S("$room_id",["$equipment_id2"=>$number2]);
    S("$room_id",["$equipment_id"=>$number]);
    dump(S("5"));die;
    dump(S("$equipment_id"));die;
    S('doogie', ['sex' => '男', 'age' => 26], 3600);  
  	S('doogie1', ['sex' => '女', 'age' => 26], 3600);  
  // phpinfo();
    dump(S('doogie'));  
    dump(S('doogie1'));  die;
   // $mem = new Memcache();
   // $rs = $mem->connect('127.0.0.1',11211);
   // var_dump($rs);
   // echo "<hr>";
   // $rs1 = $mem->set('roomid','666',0,0);
   // var_dump($rs1);
   // $data = $mem->get('roomid');
   // echo "<hr>";
   // var_dump($data);
  }
  public function a(){
    $roomid = 4;
    $id1 = 10;$id2 = 20;$num1 = 3;$num4 = 4;

    S("$id1",$num1);
    S("$id2",$num4);
    
    $machine = M('Equipment')->where(['goods_id'=>$roomid])->select();
 
    foreach ($machine as $key => $value) {
      $data[$value['id']] = S($value['id']);
    }
    reset($data);
    $key = key($data);
    dump($key);die;
    //根据值从小到大排序
    asort($data);
    // dump($data);die;
    foreach ($data as $key => $value) {
      $k = $key;
    }
    dump($k);die;
    $e = array_keys($a);
    dump($e);die;
    dump($data);die;//1号机台3个人 2号机台4个人
    
}

 
  public function b(){
    S('2',2);
    dump(S('2'));
    S('1',1);
     dump(S('1'));die;
  }
}