<?php

namespace Home\Controller;
use Think\Controller;
use Common\Plugin\Wxlogin;
header("content-type:text/html;charset=utf-8");
header('Access-Control-Allow-Origin:*');
header("Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept");
class UseraccountController extends Controller{
	public function recharge(){
		 $params = $GLOBALS['HTTP_RAW_POST_DATA'];         
         $params = json_decode($params,true);
         $user = M('all_user')->where(['id'=>$params['userid']])->find();
         if (time()-$params['timestamp']>10) {
         	$data = array(
         		'errid' => 10001,
         		'timestamp' => time(),
         		);
         }elseif (!$user) {
         	$data = array(
         		'errid' => 10003,
         		'errmsg' => 'auth error',
         		);
         }else{
         	//
         	
         	
         }
        $data = json_encode($data,JSON_UNESCAPED_UNICODE);
        // return $data;
        echo $data;
       

	}


	//游戏记录
	public function get_game_logs(){
        
         $params = $GLOBALS['HTTP_RAW_POST_DATA'];         
         $params = json_decode($params,true);
         //添加$signature
         $signature = array(
            'userid' => $params['userid'],
            'timestamp' => $params['timestamp'],
            'access_token' => $_SESSION['accesstoken'],
            );
         $signature = json_encode($signature);
         $signature = sha1($signature);
         $user = M('all_user')->where(['id'=>$params['userid']])->find();
         if (time()-$params['timestamp']>12) {
         	$data = array(
         		'errid' => 10001,
         		'timestamp' => time(),
         		);
         }elseif(!$user){
         	$data = array(
         		'errid' => 10003,
         		'errmsg' => 'auth error',
         		);
         }elseif($params['signature']!=$signature){
            $data = array(
                'msgtype' => 'error',
                'params' => array(
                    'errid' => 10003,
                    'msgtype' => 'signature error',
                    ),
                );
         }else{
         	$log = M('tbl_game_log')->alias("t1")->field("t1.*,t2.pics_origin,t3.name as goods_name")->where(['t1.userid'=>$params['userid']])->join("left join goodspics as t2 on t2.goods_id = t1.goods_id")->join("left join goods as t3 on t3.id = t1.goods_id")->join("left join tbl_order as t4 on t4.log_id = t1.id")->select();
                $success_count = count(M('tbl_game_log')->where(['userid'=>$params['userid'],'got_gift'=>1])->select());
                $count = count(M('tbl_game_log')->where(['userid'=>$params['userid']])->select());
                $stock_count = count(M('tbl_game_log')->where(['userid'=>$params['userid'],'got_gift'=>1,'status'=>0])->select());
                //遍历修改数据
                foreach ($log as $key => $value) {
                       $game_logs[$key]['gamelogid'] = $value['id'];
                       $game_logs[$key]['roomid'] = $value['goods_id'];
                       $game_logs[$key]['photo'] = $value['pics_origin'];
                       $game_logs[$key]['machined'] = $value['equipment_id'];
                       $game_logs[$key]['goods_name'] = $value['goods_name'];
                       // $game_logs['paymentid'] = $value['paymentid'];
                       $game_logs[$key]['start'] = $value['start_time'];
                       $game_logs[$key]['end'] = $value['end_time'];
                       $game_logs[$key]['result'] = $value['got_gift'];
                       // $game_logs[$key]['status'] = $value['status'];
                       $game_logs[$key]['status'] = $value['status'];
                }
                // var_dump($game_logs);die;
         	$data = array(
         		'gamelogs' => $game_logs,
         		'userid'   => $params['userid'],
                        'success_count' => $success_count,//游戏成功次数
                        'count'    => $count,//游戏总次数
                        'stock_count' => $stock_count,//抓中娃娃,还没有申请发货的数量
         		);
         }
        $data = json_encode($data,JSON_UNESCAPED_UNICODE);
        // return $data;
        echo $data;
	}



        //快递申请
	public function create_order(){
		$params = $GLOBALS['HTTP_RAW_POST_DATA'];  file_put_contents('order.txt',$params); 
		$params = json_decode($params,true);
        $commodity = $params['gamelogid'];
        $gamelogid = explode(',',$params['gamelogid']);
        if(count($gamelogid)>1){//判断穿过来的gamelogid的长度
            //包邮
            $signature = array(
                'userid' => $params['userid'],
                'gamelogid' => $params['gamelogid'],
                'roomid' => $params['roomid'],
                'name' => $params['name'],
                'tel' => $params['tel'],
                'addresss' => $params['addresss'],
                'timestamp' => $params['timestamp'],
                'access_token' => $_SESSION['accesstoken'],
                );
            $signature = json_encode($signature,JSON_UNESCAPED_UNICODE);
            file_put_contents('order2.txt',$signature);
            $signature = sha1($signature);
            $log = M('tbl_game_log')
            ->where("id in ($commodity)")
            ->where(['status'=>0,'userid'=>$params['userid']])
            ->find();
             if (time()-$params['timestamp']>12) {
            $data = array(
                'errid' => 10001,
                'timestamp' => time(),
                );
            }elseif (!$log) {
            $data = array(
                'errid' => 40002,
                );
            }elseif($params['signature']!=$signature){
            $data = array(
                'msgtype' => 'error',
                'params' => array(
                    'errid' => 10003,
                    'errmsg' => 'signature error',
                    ),
                );
        }else{
            //将传来的数据全部添加到tbl_order表中
                  M('tbl_game_log')->where("id in ($commodity)")->save(['status'=>1]); 
            $array = array(
                    'create_time' => time(),
                    'address'=>$params['addresss'],
                    'phone' => $params['tel'],
                    'userid' => $params['userid'],
                    'name' => $params['name'],
                    'roomid' => $params['roomid'],
                    'log_id' => $params['gamelogid'],
                );            
                M('tbl_order')->add($array);
            $data = array(
                'orderlogid' => $order_id,
                );
        }
        }else{
            //不包邮
             //添加$signature
        $signature = array(
            'userid' => $params['userid'],
            'gamelogid' => $params['gamelogid'],
            'roomid' => $params['roomid'],
            'name' => $params['name'],
            'tel' => $params['tel'],
            'addresss' => $params['addresss'],
            'timestamp' => $params['timestamp'],
            'access_token' => $_SESSION['accesstoken'],
            );
        $signature = json_encode($signature,JSON_UNESCAPED_UNICODE);
        $signature = sha1($signature);
                //查询发送过来的订单号是否满足邮寄标准(tbl_game_logs中的)status=0
                $log = M('tbl_game_log')->where(['id'=>$params['gamelogid'],'status'=>0,'userid'=>$params['userid'],'got_gift'=>1])->find();
                $user = M('all_user')->where(['id'=>$params['userid']])->find();
        if (time()-$params['timestamp']>12) {
            $data = array(
                'errid' => 10001,
                'timestamp' => time(),
                );
        }elseif (!$log) {
            $data = array(
                'errid' => 40002,
                );
        }elseif($params['signature']!=$signature){
            $data = array(
                'msgtype' => 'error',
                'params' => array(
                    'errid' => 10003,
                    'errmsg' => 'signature error',
                    ),
                );
        }else{

                        //拉起支付页面
                        // $params['id'] = 10;
                        $out_trade_no = rand(10,999999);//生成订单编号
                        //将订单存入数据库,status为0(未支付)
                        //  $repeat = $params['gamelogid'];
                        // $gamelogid = M('tbl_game_log')->where("id in ($repeat)")->distinct(true)->getField('id',true);
                        // $gamelogid = implode(',',$gamelogid);
                        $data = array(
                            'out_trade_no'=>$out_trade_no,
                            'create_time'=>time(),
                            'order_id' => 10,
                            'userid' => $params['userid'],
                            'log_id' => $params['gamelogid'],
                            'name' => $params['name'],
                            'address' => $params['addresss'],
                            'phone' => $params['tel'],
                            ); 
                        M('express_pay')->add($data);
                        //添加信息到邮费支付表中
                        $url = U('Home/Weixinpay/pay2',array('out_trade_no'=>$out_trade_no));
                        $this->ajaxReturn($url);die;

                        //将这条游戏记录的status改为1(已申请)
                        // M('tbl_game_log')->where(['id'=>$params['gamelogid']])->save(['status'=>1]);
                        // //将数据存入数据库中
                        // $res['log_id'] = $params['gamelogid'];
                        // $res['name'] = $params['name'];
                        // $res['create_time'] = time();
                        // $res['address'] = $params['addresss'];
                        // $res['phone'] = $params['tel'];
                        // $res['userid'] = $params['userid'];
                        // $order_id = M('tbl_order')->add($res);
                        // $data = array(
                        //         'orderlogid' => $order_id,
                        //         );
        }
        }
       
                        $data = json_encode($data,JSON_UNESCAPED_UNICODE);
                        echo $data;
	}

        //订单(快递)记录
        public function get_order_logs(){
                $params = $GLOBALS['HTTP_RAW_POST_DATA'];
                $params = json_decode($params,true);
                //添加$signature
                $signature = array(
                    'userid' => $params['userid'],
                    'timestamp' => $params['timestamp'],
                    'access_token' => $_SESSION['accesstoken'],
                    );
                $signature = json_encode($signature);
                $signature = sha1($signature);
                $user = M('all_user')->where(['id'=>$params['userid']])->find();
                $order = M('tbl_order')->where(['userid'=>$params['userid']])->select();

                //获取tbl_order表中属于这个用户的订单id
                // $log_id = M('tbl_game_log')->where(['userid'=>$params['userid'],'status'=>1])->getField('id',true);
                if (time()-$params['timestamp']>12) {
                        $data = array(
                                'errid' => 10001,
                                'timestamp' => time(),
                                );
                }elseif (!$user) {
                        $data = array(
                                'errid' => 10003,
                                );
                }elseif(!$order){
                        $data = array(
                                'userid' => $params['userid'],
                                'orderlogs' => NULL,
                                );
                }elseif($params['signature']!=$signature){
                        $data = array(
                            'msgtype' => 'error',
                            'params' => array(
                                'errid' => 10003,
                                'errmsg' => 'signature error',
                                ),
                            );
                }else{
                        //通过验证
                        
                       // var_dump($order);die;
                        foreach ($order as $key => &$value) {
                                $res[$key]['orderlogid'] = $value['id'];
                                $res[$key]['createdate'] = $value['create_time'];
                                $res[$key]['gamelogid']  = $value['log_id'];
                                $res[$key]['roomid']     = M('tbl_game_log')->where(['id'=>$value['log_id']])->getField('goods_id');
                                $res[$key]['photo']      = M('tbl_game_log')->alias('t1')->where(['t1.id'=>$value['log_id']])->join("left join goods as t2 on t2.id = t1.goods_id")->join("left join goodspics as t3 on t3.goods_id = t2.id")->getField('pics_origin');
                                $res[$key]['status']     = $value['status'];
                                $res[$key]['name']       = $value['name'];
                                $res[$key]['tel']        = $value['phone'];
                                $res[$key]['addresss']   = $value['address'];
                                $res[$key]['trackingid'] = $value['trace_number'];
                                $res[$key]['carrier']    = M('tbl_order')->alias('t1')->where(['t1.log_id'=>$value['log_id']])->join("left join express as t2 on t2.express_id = t1.express_id")->getField('express_name');
                                $res[$key]['delieverdate']= NULL;

                        }//$value['status']0为待发货 1为已发货 2为到货
                        // var_dump($res);die;
                        $data = array(
                                'orderlogs' => $res,
                                'userid'    => $params['userid'],
                                );
                }
                $data = json_encode($data,JSON_UNESCAPED_UNICODE);
                echo $data;

        }

        //订单修改
        public function update_order(){
                $params = $GLOBALS['HTTP_RAW_POST_DATA'];
                $params = json_decode($params,true);
                $signature = array(
                    'userid' => $params['userid'],
                    'orderlogid' => $params['orderlogid'],
                    'roomid' => $params['roomid'],
                    'photo' => $params['photo'],
                    'name' => $params['name'],
                    'tel' => $params['tel'],
                    'addresss' => $params['addresss'],
                    'timestamp' => $params['timestamp'],
                    'access_token' => $_SESSION['accesstoken'],
                    );
                $signature = str_replace("\\/", "/", json_encode($signature,JSON_UNESCAPED_UNICODE));   
                $signature = sha1($signature);
                // $order = M('tbl_game_log')->where(['id'=>$params['orderlogid']])->find();
                // 查询出order表中要修改的这条数据
                $order = M('tbl_order')->where(['id'=>$params['orderlogid']])->find();
                if (time()-$params['timestamp']>12) {
                        $data = array(
                                'errid' => 10001,
                                'timestamp' => time(),
                                );
                }elseif (!$order) {
                        $data = array(
                                'errid' => 40004,
                                );
                }elseif ($order['status'] == 1) {
                        $data = array(
                                'errid' => 40004,
                                );
                }elseif($params['signature']!=$signature){
                        $data = array(
                            'msgtype' => 'error',
                            'params' => array(
                                'errid' => 10003,
                                'errmsg' => 'signature error',
                                ),
                            );
                }else{
                        //可以修改
                        $res['name'] = $params['name'];
                        $res['phone'] = $params['tel'];
                        $res['address'] = $params['addresss'];
                        $result = M('tbl_order')->where(['id'=>$params['orderlogid']])->save($res);
                        $data = array(
                                'orderlogid' => $params['orderlogid'],
                                'msgtype' => 'success',
                                );
                }

                $data = json_encode($data,JSON_UNESCAPED_UNICODE);
                echo $data;

        }


        //消费记录
        public function get_payment_logs(){
            $params = $GLOBALS['HTTP_RAW_POST_DATA'];
            $params = json_decode($params,true);
            $signature = array(
                'userid' => $params['userid'],
                'timestamp' => $params['timestamp'],
                'access_token' => $_SESSION['accesstoken'],
                );
            $signature = json_encode($signature);
            $signature = sha1($signature);
            $user = M('all_user')->where(['id'=>$params['userid']])->find();
            if (time()-$params['timestamp'] > 12) {
                $data = array(
                    'errid' => 10001,
                    'timestamp' => time(),
                    );
            }elseif (!$user) {
                $data = array(
                    'errid' => 10003,
                    );
            }elseif($params['signature']!=$signature){
                $data = array(
                    'msgtype' => 'error',
                    'params' => array(
                        'errid' => 10003,
                        'errmsg' => 'signature error',
                        ),
                    );
            }else{
                 $record = M('record')->where(['userid'=>$params['userid']])->select();
                 foreach ($record as $key => $value) {
                     $logs[$key]['paymentid'] = $value['paymentid'];
                     $logs[$key]['roomid'] = $value['roomid'];
                     $logs[$key]['goodsname'] = $value['goodsname'];
                     $logs[$key]['photo'] = $value['photo'];
                     $logs[$key]['machineid'] = $value['equipment_id'];
                     $logs[$key]['amount'] = $value['amount'];
                     $logs[$key]['cancel'] = $value['cancel'];
                 }
                 $data = array(
                    'userid' => $params['userid'],
                    'paymentlogs' => $logs,
                    );
            }
           
            $data = json_encode($data,JSON_UNESCAPED_UNICODE);
            echo $data;
        }


        //留言
        public function create_comment(){
            $params = $GLOBALS['HTTP_RAW_POST_DATA'];
            $params = json_decode($params,true);
            $signature = array(
                'userid' => $params['userid'],
                'message' => $params['message'],
                'timestamp' => $params['timestamp'],
                'access_token' => $_SESSION['accesstoken'],
                );
            $signature = json_encode($signature,JSON_UNESCAPED_UNICODE);
            $signature = sha1($signature);
            $user = M('all_user')->where(['id'=>$params['userid']])->find();
            if (time() - $params['timestamp'] > 12) {
                $data = array(
                    'errid' => 10001,
                    'timestamp' => time(),
                    );
            }elseif (!$user) {
                $data = array(
                    'errid' => 10003,
                    );
            }elseif ($params['signature']!=$signature) {
                $data = array(
                    'msgtype' => 'error',
                    'params' => array(
                        'errid' => 10003,
                        'errmsg' => 'signature error',
                        ),
                    );
            }else{
                $comment = array(
                    'userid' => $params['userid'],
                    'message' => $params['message'],
                    'create_time' => time(),
                    );
                M('Comment')->add($comment);
                $data = array(
                    'msgtype' => 'add auth',
                    );
            }
            $data = json_encode($data,JSON_UNESCAPED_UNICODE);
            echo $data;
        }


        //查看留言
        public function get_comment_logs(){

            $params = $GLOBALS['HTTP_RAW_POST_DATA'];
            $params = json_decode($params,true);
            //添加$signature
            $signature = array(
                'userid' => $params['userid'],
                'timestamp' => $params['timestamp'],
                'access_token' => $_SESSION['accesstoken'],
                );
            $signature = json_encode($signature,JSON_UNESCAPED_UNICODE);
            $signature = sha1($signature);
            $user = M('all_user')->where(['id'=>$params['userid']])->find();

            if (time() - $params['timestamp'] > 12) {

                $data = array(
                    'errid' => 10001,
                    'timestamp' => time(),
                    );
               
            }elseif (!$user) {
                $data = array(
                    'errid' => 10003,
                    );
            }elseif($params['signature']!=$signature){
                $data = array(
                    'msgtype' => 'error',
                    'params' => array(
                        'errid' => 10003,
                        'errmsg' => 'signature error', 
                        ),
                    );
            }else{
              
                $commentlogs = M('Comment')->where(['userid'=>$params['userid']])->select();
                
                foreach ($commentlogs as $key => $value) {
                    $data[$key]['commentlogid'] = $value['id'];
                    $data[$key]['comment'] = $value['message'];
                    $data[$key]['commentdata'] = $value['create_time'];
                    $data[$key]['reply'] = $value['reply'];
                    $data[$key]['replydate'] = $value['replydate'];
                }
              
               
            }

             $data = json_encode($data,JSON_UNESCAPED_UNICODE);
             echo $data;
        }

        public function t(){
            $data = array(
                'userid' => 2,
                // 'message' => "hello world!",
                'timestamp' => time(),
                );

            $url = "http://www.machine.com/Home/Useraccount/get_comment_logs";
            $return = json_curl($url,$data);
            dump($return);die;
        }

       
        //获取充值记录(修改数据库连接配置)
        public function get_recharge_logs(){
          
            $params = $GLOBALS['HTTP_RAW_POST_DATA'];
            $params = json_decode($params,true);
            $signature = array(
                'userid' => $params['userid'],
                'timestamp' => $params['timestamp'],
                'access_token' => $_SESSION['accesstoken'],
                );
            $signature = json_encode($signature,JSON_UNESCAPED_UNICODE);
            $signature = sha1($signature);

            $userid = $params['userid'];
            // $user = M('all_user')->where(['id'=>$params['userid']])->find();
            // $userid = $user['id'];
            if (time()-$params['timestamp']>12) {
                $data = array(
                    'errid' => 10001,
                    'timestamp' => time(),
                    );
            }elseif($params['signature']!=$signature){
                $data = array(
                    'msgtype' => 'error',
                    'params' => array(
                        'errid' => 10003,
                        'errmsg' => 'signature error',
                        ),
                    );
            }else{

                // $rechargelogs = M('order_log')->where("userid = $userid && status = 1")->select();
                $rechargelogs = M()->db(2,"DB_CONFIG2")->table("order_log")->where("userid = $userid && status = 1")->select();
                //链接远程数据库 查询支付信息(nugh)
                foreach ($rechargelogs as $key => $value) {
                    $data[$key]['rechargelogsid'] = $value['id'];
                    // $data[$key]['amount'] = M('order')->where(['id'=>$value['order_id']])->getField('money');
                    $data[$key]['amount'] = M()->db(2,"DB_CONFIG2")->table("order")->where(['id'=>$value['order_id']])->getField('money');
                    // $data[$key]['awardamount'] = M('order')->where(['id'=>$value['order_id']])->getField('amount');
                    $data[$key]['awardamount'] = M()->db(2,"DB_CONFIG2")->table("order")->where(['id'=>$value['order_id']])->getField('amount');
                    $data[$key]['awardtype'] = 'gold';
                    $data[$key]['date'] = $value['create_time'];
                }
                

            }
            
            $data = json_encode($data,JSON_UNESCAPED_UNICODE);
            echo $data;
        }

// $data2  = M()->db(2,"DB_CONFIG2")->table("machine")->where(['id'=>1])->find();
        //测试获取充值记录  
      public function test(){
            $data = date('Ymd') . str_pad(mt_rand(1, 99999), 5, '0', STR_PAD_LEFT);
            dump($data);die;  
      }

      
}