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
            $order_id = M("order")->where(['money'=>$params['value']])->getField('id');
            $order = M("order")->where(['id'=>$order_id])->select();
            //var_dump($order);die;
            foreach ($order as $key => $value) {
                //var_dump($value);die;
                if($params['value']==$value['money']){
                    if($params['value'] == 1||$params['value'] == 5){
                        $user['gold'] = $user['gold'] + $params['value'];

                    }
                    elseif($params['value'] == 10 ){
                        $user['gold'] = $user['gold'] + $params['value'];
                        $user['silver'] = $user['silver'] +1;
                        $type = 1;
                        $value = "银币";
                    }
                    elseif($params['value'] ==20 ){
                        $user['gold'] = $user['gold'] + $params['value'];
                        $user['silver'] = $user['silver'] +2;
                        $type = 2;
                        $value = "银币";
                    }elseif ($params['value'] == 50){
                        $user['gold'] = $user['gold'] + $params['value'];
                        $user['silver'] = $user['silver'] + 3;
                        $type = 3;
                        $value = "银币";
                    }elseif ($params['value'] ==100) {
                        $user['gold'] = $user['gold'] + $params['value'];
                        $user['silver'] = $user['silver'] +5;
                        $type = 5;
                        $value = "银币";
                    }elseif ($params['value'] ==500) {
                        $user['gold'] = $user['gold'] + $params['value'];
                        $user['silver'] = $user['silver'] +15;
                        $type = 15;
                        $value = "银币";
                    }elseif ($params['value'] ==1000) {
                        $user['gold'] = $user['gold'] + $params['value']+100;
                        //$user['silver'] = $user['silver'] + $params['amount']+5;
                        $type = 100;
                        $value = "金币";
                    }
                }
            }
             M('all_user')->where(['id'=>$params['userid']])->save($user);
                if($params['value'] == 10 ){
                        $user['silver'] = $user['silver'] -1;
                    }elseif($params['value'] ==20 ){
                        $user['silver'] = $user['silver'] -2;
                    }elseif ($params['value'] == 50){
                        $user['silver'] = $user['silver'] - 3;
                    }elseif ($params['value'] ==100) {
                        $user['silver'] = $user['silver'] -5;
                    }elseif ($params['value'] ==500) {
                        $user['silver'] = $user['silver'] -15;
                    }elseif ($params['value'] ==1000) {
                        $user['gold'] = $user['gold']-100;
                    }
            $purchase = array(
                ['type'=>'金币','value'=>$user['gold']],
                ['type'=>'银币','value'=>$user['silver']],
                        // 'type'=>'您充值了'.','.M("order")->where(['money'=>$params['value']])->getField('money').'元',
                        // 'value'=>'您现在的资产'.','.'金币'.$user['gold'].'银币'.$user['silver'],
                    );
            $award = array(
                ['type'=>'金币','value'=>M("all_user")->where(['id'=>$params['userid']])->getField('gold')],
                ['type'=>'银币','value'=>M("all_user")->where(['id'=>$params['userid']])->getField('silver')],
                // 'type'=>'恭喜您,您充值了'.','.M("order")->where(['money'=>$params['value']])->getField('money').'元'.','.'奖励您'.$value.','.$type.'元',
                // 'value'=>'您现在的资产'.','.'金币'.M("all_user")->where(['id'=>$params['userid']])->getField('gold').','.'银币'.M("all_user")->where(['id'=>$params['userid']])->getField('silver'),
                );
           $data = array(
                'msgtype'=>'recharge_success',
                'purchase'=>$purchase,
                 'award'=>$award,
            );

           //添加充值记录
           $order_log = array(
                'order_id'=>$order_id['id'],
                'userid'=>$params['userid'],
                'award'=>$type,
                'out_trade_no'=>strtotime( date("Y-m-d H:i:s",strtotime("+1 seconds"))),
                'create_time'=>time(),
                'status'=>0,
            );
           //var_dump($order_log);die;
           M("order_log")->add($order_log);

         }        
        $data = json_encode($data,JSON_UNESCAPED_UNICODE);
         //return $data;
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
         }
         // elseif($params['signature']!=$signature){
         //    $data = array(
         //        'msgtype' => 'error',
         //        'params' => array(
         //            'errid' => 10003,
         //            'msgtype' => 'signature error',
         //            ),
         //        );
         // }
         else{
         	$log = M('tbl_game_log')->alias("t1")->field("t1.*,t2.pics_origin,t3.name as goods_name")->where(['t1.userid'=>$params['userid']])->join("left join goodspics as t2 on t2.goods_id = t1.goods_id")->join("left join goods as t3 on t3.id = t1.goods_id")->join("left join tbl_order as t4 on t4.log_id = t1.id")->select();
                $success_count = count(M('tbl_game_log')->where(['userid'=>$params['userid'],'got_gift'=>1])->select());
                $count = count(M('tbl_game_log')->where(['userid'=>$params['userid']])->select());
                $stock_count = count(M('tbl_game_log')->where(['userid'=>$params['userid'],'got_gift'=>1,'status'=>0])->select());
                $gold = M('equipment')->alias('t1')->where(['t2.userid'=>$params['userid']])->join('left join tbl_game_log as t2 on t2.equipment_id = t1.id')->getField('t1.price');
                $silver = M('equipment')->alias('t1')->where(['t2.userid'=>$params['userid']])->join('left join tbl_game_log as t2 on t2.equipment_id = t1.id')->getField('t1.money');
               //var_dump($dd);die;
                //遍历修改数据
                foreach ($log as $key => $value) {
                       $game_logs[$key]['gamelogid'] = $value['id'];
                       $game_logs[$key]['roomid'] = $value['goods_id'];
                       
                       if($value['type']=='gold'){
                        $game_logs[$key]['paymenttype'] = $value['type']='金币';
                        $game_logs[$key]['value'] = intval($gold);

                       }elseif($value['type']=='silver'){
                       $game_logs[$key]['paymenttype'] = $value['type']='银币';
                       $game_logs[$key]['value'] = intval($silver);           
                       }
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
                 //var_dump($game_logs);die;
         	$data = array(
         		'gamelogs' => $game_logs,
         		'userid'   => $params['userid'],
                        'success_count' => $success_count,//游戏成功次数
                        'count'    => $count,//游戏总次数
                        'stock_count' => $stock_count,//抓中娃娃,还没有申请发货的数量
         		);
         }
        $data = json_encode($data,JSON_UNESCAPED_UNICODE);
         //var_dump($data);die;
        // return $data;
        echo $data;
	}



           //快递申请
    public function create_order(){
        $params = $GLOBALS['HTTP_RAW_POST_DATA'];  //file_put_contents('order.txt',$params); 
        $params = json_decode($params,true);
        //var_dump($params);die;
        if(count($params['gamelogid']>1)){//判断穿过来的gamelogid的长度
            $gamelogid = implode(',',$params['gamelogid']);
            $signature = array(
                'userid' => $params['userid'],
                'gamelogid' => $gamelogid,
                'roomid' => $params['roomid'],
                'name' => $params['name'],
                'tel' => $params['tel'],
                'address' => $params['addresss'],
                'timestamp' => $params['timestamp'],
                'access_token' => $_SESSION['accesstoken'],
                );
            $signature = json_encode($signature,JSON_UNESCAPED_UNICODE);
            $signature = sha1($signature);
            //var_dump($signature);die;
            $log = M('tbl_game_log')
            ->where("id in ($gamelogid)")
            ->where(['status'=>0,'userid'=>$params['userid']])
            ->select();
             if (time()-$params['timestamp']>12) {
            $data = array(
                'errid' => 10001,
                'timestamp' => time(),
                );
            }elseif (!$log) {
            $data = array(
                'errid' => 40002,
                );
            }
            elseif($params['signature']!=$signature){
            $data = array(
                'msgtype' => 'error',
                'params' => array(
                    'errid' => 10003,
                    'errmsg' => 'signature error',
                    ),
                );
        }
        else{
            //将传来的数据全部添加到tbl_order表中
            //var_dump($data);die;
                M('tbl_game_log')->where("id in ($gamelogid)")->save(['status'=>1]); 
            $array = array(
                    'create_time' => time(),
                    'address'=>$params['addresss'],
                    'phone' => $params['tel'],
                    'userid' => $params['userid'],
                    'name' => $params['name'],
                    'roomid' => $params['roomid'],
                    'log_id' => $gamelogid,
                );        
            $order_id = M('tbl_order')->add($array);
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
            'address' => $params['addresss'],
            'timestamp' => $params['timestamp'],
            'access_token' => $_SESSION['accesstoken'],
            );
        $signature = json_encode($signature,JSON_UNESCAPED_UNICODE);
        $signature = sha1($signature);
                //查询发送过来的订单号是否满足邮寄标准(tbl_game_logs中的)status=0
                $log = M('tbl_game_log')->where(['id'=>$params['gamelogid'],'status'=>0,'userid'=>$params['userid'],'got_gift'=>1])->find();
                //var_dump($log);die;
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
        }
        elseif($params['signature']!=$signature){
            $data = array(
                'msgtype' => 'error',
                'params' => array(
                    'errid' => 10003,
                    'errmsg' => 'signature error',
                    ),
                );
        }
        else{

                        //拉起支付页面
                        // $params['id'] = 10;
                        $out_trade_no = rand(10,999999);//生成订单编号
                        //var_dump($out_trade_no);die;
                        //将订单存入数据库,status为0(未支付)
                        $repeat = $params['gamelogid'];
                        $gamelogid = M('tbl_game_log')->where("id in ($repeat)")->distinct(true)->getField('id',true);
                        $gamelogid = implode(',',$gamelogid);
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
                        //var_dump($data);die;
                       $dd = M('express_pay')->add($data);
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
                if (time()-$params['timestamp']>30) {
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
                }
                // elseif($params['signature']!=$signature){
                //         $data = array(
                //             'msgtype' => 'error',
                //             'params' => array(
                //                 'errid' => 10003,
                //                 'errmsg' => 'signature error',
                //                 ),
                //             );
                // }
                else{
                         //通过验证
                    //$res = array();
                        foreach ($order as $key => &$value) {
                                $a = $value['log_id'];
                                $res[$key]['items'] = array(
                                    'gamelogid'  => explode(',',$value['log_id']),
                                    'roomid'=> M('tbl_game_log')->where(['id'=> ['in',$a]])->getField('goods_id',true),

                                    'goodsname'=> M('tbl_game_log')->alias('t1')->where(['t1.id'=>['in',$a]])
                                    ->join("left join goods as t2 on t2.id = t1.goods_id")
                                    ->getField('name',true),

                                    'photo'=> M('tbl_game_log')->alias('t1')->where(['t1.id'=>['in',$a]])->join("left join goods as t2 on t2.id = t1.goods_id")
                                    ->join("left join goodspics as t3 on t3.goods_id = t2.id")->getField('pics_origin',true),                                  
                                );
                               
                                $res[$key]['orderlogid'] = $value['id'];
                                $res[$key]['createdate'] = $value['create_time'];
                               // $res[$key]['gamelogid']  = explode(',',$value['log_id']);
                                $res[$key]['status']     = $value['status'];
                                $res[$key]['name']       = $value['name'];
                                $res[$key]['tel']        = $value['phone'];
                                $res[$key]['addresss']   = $value['address'];
                                $res[$key]['trackingid'] = $value['trace_number'];
                                $res[$key]['carrier']    = M('tbl_order')->alias('t1')->where(['t1.log_id'=>$value['log_id']])
                                ->join("left join express as t2 on t2.express_id = t1.express_id")->getField('express_name');
                                $res[$key]['delieverdate']= NULL;     
                            
                        }
                       foreach ($res as $k => &$v) {
                                $tep = [];
                                foreach ($v['items']as $k1 => $v1) {
                                    //echo "aa";
                                    for ($i=0; $i<count($v['items']['gamelogid']); $i++) {
                                        $tep[$i][$k1] = $v1[$i];
                                    }
                                }
                                $v['items'] = $tep;
                            }//转换items里面的数组顺序。
                        //$value['status']0为待发货 1为已发货 2为到货
                        // $arrStr = serialize($res);
                        // file_put_contents('arr.txt', $arrStr);
                      // $arr = $json;
                      // $arr_new = array();
                      //   foreach($res[$key]['items'] as &$item){
                      //       foreach($item as $key=> &$val){
                      //           $arr_new[$key][] = $val;
                      //       }
                      //   }
                      //    $key = ['gamelogid','roomid','goodsname','photo'];
                      //   $new_array = array();
                      //   foreach($arr_new as $k=> &$v) {
                      //       $new_array[$k] = array_combine($key,$v);

                      //   }
                      //   // $new_array = json_encode($new_array,JSON_UNESCAPED_UNICODE);
                      //   var_dump($new_array);die;
                     //    $array= array(
                     //            'res'=>$res,
                     //            'json'=>$new_array,
                     //        );
                     // var_dump($arr);die;

                        $data = array(
                                'orderlogs' => $res,
                                'userid'    => $params['userid'],
                                );
                }

                //var_dump($data);die;
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
                }
                elseif($params['signature']!=$signature){
                        $data = array(
                            'msgtype' => 'error',
                            'params' => array(
                                'errid' => 10003,
                                'errmsg' => 'signature error',
                                ),
                            );
                }
                else{
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
            if (time()-$params['timestamp'] > 30) {
                $data = array(
                    'errid' => 10001,
                    'timestamp' => time(),
                    );
            }elseif (!$user) {
                $data = array(
                    'errid' => 10003,
                    );
            }
            // elseif($params['signature']!=$signature){
            //     $data = array(
            //         'msgtype' => 'error',
            //         'params' => array(
            //             'errid' => 10003,
            //             'errmsg' => 'signature error',
            //             ),
            //         );
            // }
            else{
                //游戏//
                 $tbl_game_log = M('tbl_game_log')->alias('t1')->field('t1.id,t1.paymentid,t1.type,t2.price,t2.money,t3.name,t4.pics_origin')->join('left join equipment as t2 on t2.id = t1.equipment_id')->join('left join goods as t3 on t3.id = t1.goods_id')->join('left join goodspics as t4 on t4.goods_id = t3.id')->where(['t1.userid'=>$params['userid']])->select();
                   //var_dump($tbl_game_log);die;
                    foreach ($tbl_game_log as $key => $v) {
                        if($v['type']=='gold'){
                                $goldsilver = '金币';
                                $oscar = $v['price'];
                        }else{
                               $goldsilver = '银币';
                               $oscar = $v['money'];
                        }  
                        $los[$key] = ['activityid'=>intval($v['id']),'activitytype'=>'游戏','paymenttype'=>$goldsilver,'value'=>intval($oscar)];
                        $los[$key]['paymentid'] = intval($v['paymentid']);
                        $los[$key]['goodsname'] = $v['name'];
                        $los[$key]['photo'] = $v['pics_origin'];
                        $los[$key]['cancel'] = intval($v['cancel']);
                  }
                  //订单//
            $express_pay = M('express_pay')->alias('t1')->field("t1.id,t1.userid,t1.log_id,t1.order_id,t2.paymentid,t3.name,t4.pics_origin")->join("left join tbl_game_log as t2 on t2.id = t1.log_id")->join('left join goods as t3 on t3.id = t2.goods_id')->join("left join goodspics as t4 on t4.goods_id = t3.id")->where(['t1.userid'=>$params['userid']])->select();
                //var_dump($express_pay);die;
                foreach ($express_pay as $key => $value) {
                        $log[$key] = ['activityid'=>intval($value['id']),'activitytype'=>'邮费','paymenttype'=>'人民币','value'=>intval(M('order')->where(['id'=>$value['order_id']])->getField('money'))];
                        $log[$key]['paymentid'] = intval($value['paymentid']);
                        $log[$key]['goodsname'] = $value['name'];
                        $log[$key]['photo'] = $value['pics_origin'];
                        $log[$key]['cancel'] = intval($value['cancel']);
                  }
                  //购买//
                $record = M('record')->where(['userid'=>$params['userid']])->select();
                        foreach ($record as $key => $value) {
                            if($value['type']=='gold'){
                                    $goldsilver = '金币';
                            }else{
                                   $goldsilver = '银币';
                            }  
                            $logs[$key] = ['activityid'=>intval($value['id']),'activitytype'=>'购买','paymenttype'=>$goldsilver,'value'=>intval($value['amount'])];
                            $logs[$key]['goodsname'] = $value['goodsname'];
                            $logs[$key]['photo'] = $value['photo'];
                            $logs[$key]['paymentid'] = intval($value['paymentid']);
                            $logs[$key]['cancel'] = intval($value['cancel']);
                      }
                  $data = array(
                  'userid' => $params['userid'],
                  'paymentlogs' => array(
                        $los,
                        $log,
                        $logs,
                    ),
                  );
                  //var_dump($data);die;
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
            }
            elseif ($params['signature']!=$signature) {
                $data = array(
                    'msgtype' => 'error',
                    'params' => array(
                        'errid' => 10003,
                        'errmsg' => 'signature error',
                        ),
                    );
            }
            else{
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
            }
            elseif($params['signature']!=$signature){
                $data = array(
                    'msgtype' => 'error',
                    'params' => array(
                        'errid' => 10003,
                        'errmsg' => 'signature error', 
                        ),
                    );
            }
            else{
              
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
            if (time()-$params['timestamp']>30) {
                $data = array(
                    'errid' => 10001,
                    'timestamp' => time(),
                    );
            }
            // elseif($params['signature']!=$signature){
            //     $data = array(
            //         'msgtype' => 'error',
            //         'params' => array(
            //             'errid' => 10003,
            //             'errmsg' => 'signature error',
            //             ),
            //         );
            // }
            else{
                // $rechargelogs = M('order_log')->where("userid = $userid && status = 1")->select();
                $rechargelogs = M()->db(2,"DB_CONFIG2")->table("order_log")->where("userid = $userid")->select();
                if($rechargelogs['status'] == 1){
                      //链接远程数据库 查询支付信息(nugh)
                foreach ($rechargelogs as $key => $value) {
                    $data[$key]['rechargelogsid'] = $value['id'];
                    $data[$key]['value'] = intval(M()->db(2,"DB_CONFIG2")->table("order")->where(['id'=>$value['order_id']])->getField('money'));
                    // $data[$key]['amount'] = M('order')->where(['id'=>$value['order_id']])->getField('money');
                    //$data[$key]['amount'] = M()->db(2,"DB_CONFIG2")->table("order")->where(['id'=>$value['order_id']])->getField('money');
                    // $data[$key]['awardamount'] = M('order')->where(['id'=>$value['order_id']])->getField('amount');
                    // $data[$key]['purchase']=array(
                    //     ['type'=>'充值记录'.M()->db(2,"DB_CONFIG2")->table("order")->where(['id'=>$value['order_id']])->getField('money'),'value'=>'个人资产'.','.'金币'.M('all_user')->where(['id'=>$userid])->getField('gold').','.'银币'.M('all_user')->where(['id'=>$userid])->getField('silver')],
                    //     );
                    $data[$key]['purchase']=array(
                        ['type'=>'金币','value'=>intval(M()->db(2,"DB_CONFIG2")->table("order")->where(['id'=>$value['order_id']])->getField('money'))],
                        );
                    if($value['award']<100){
                        $goldsilver = "金币";
                    }else{
                        $goldsilver = "银币";
                    }
                    $data[$key]['award']=array(
                        ['type'=>$goldsilver,'value'=>intval($value['award'])],
                        );
                   // $data[$key]['awardamount'] = M()->db(2,"DB_CONFIG2")->table("order")->where(['id'=>$value['order_id']])->getField('amount');
                    //$data[$key]['awardtype'] = 'gold';
                    $data[$key]['date'] = $value['create_time'];
                }
            }
            elseif($rechargelogs['status'] == 0){
                //链接远程数据库 查询支付信息(nugh)
                foreach ($rechargelogs as $key => $value) {
                    $data[$key]['rechargelogsid'] = $value['id'];
                    $data[$key]['value'] = intval(M()->db(2,"DB_CONFIG2")->table("order")->where(['id'=>$value['order_id']])->getField('money'));
                    // $data[$key]['amount'] = M('order')->where(['id'=>$value['order_id']])->getField('money');
                    //$data[$key]['amount'] = M()->db(2,"DB_CONFIG2")->table("order")->where(['id'=>$value['order_id']])->getField('money');
                    // $data[$key]['awardamount'] = M('order')->where(['id'=>$value['order_id']])->getField('amount');
                 $data[$key]['purchase']=array(
                        ['type'=>'金币','value'=>intval(M()->db(2,"DB_CONFIG2")->table("order")->where(['id'=>$value['order_id']])->getField('money'))],
                        );
                    if($value['award']<100){
                        $goldsilver = "银币";
                    }if($value['award']>=100){
                        $goldsilver = "金币";
                    }
                    $data[$key]['award']=array(
                        ['type'=>$goldsilver,'value'=>intval($value['award'])],
                        );
                   // $data[$key]['awardamount'] = M()->db(2,"DB_CONFIG2")->table("order")->where(['id'=>$value['order_id']])->getField('amount');
                    //$data[$key]['awardtype'] = 'gold';
                    $data[$key]['date'] = $value['create_time'];
                }
            }
              $data = array(
                        'msgtype'=>'recharge_success',
                        'rechargelogs'=>$data,
                        'userid'=>$userid,
                    );
            }
            //var_dump($data);die;
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