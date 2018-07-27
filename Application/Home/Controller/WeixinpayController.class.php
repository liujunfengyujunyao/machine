<?php
namespace Home\Controller;
// use Common\Controller\HomeBaseController;
// use Common\Plugin\WeixinPay;
use Think\Controller;
header('Access-Control-Allow-Origin:*');
header("Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept");
/**
 * 微信支付
 */
class WeixinpayController extends Controller{

    public function test(){
        echo 222;
    }
    /**
     * notify_url接收页面
     */
    public function notify(){
        //测试用 用完删除下
        $xml=file_get_contents('php://input', 'r');
        //转成php数组 禁止引用外部xml实体
        libxml_disable_entity_loader(true);
        $data= json_encode(simplexml_load_string($xml, 'SimpleXMLElement', LIBXML_NOCDATA));
        file_put_contents('./notify.text', $data);
        //测试用 用完删除上
        // 导入微信支付sdk
        Vendor('Weixinpay.Weixinpay');
        $wxpay=new \Weixinpay();
        $result=$wxpay->notify();
        file_put_contents('./pay3.text', $result);

//        file_put_contents('./pay.text', $result.PHP_EOL, FILE_APPEND);
        if ($result) {
            //修改这条充值预订单的status
            // 验证成功 修改数据库的订单状态等 $result['out_trade_no']为订单号
            $pay = M('order_log')->where(['out_trade_no'=>$result['out_trade_no']])->find();
            if ($pay) {
                M('order_log')->where(['out_trade_no' => $result['out_trade_no']])->save(['status' => 1]);//将订单的状态改为1已经支付
                $order_log = M('order_log')->where(['out_trade_no' => $result['out_trade_no']])->find();//查询出对应的订单
                $order = M('Order')->where(['id' => $order_log['order_id']])->find();//查询出订单对应的充值金额
                $user = M('all_user')->where(['id' => $order_log['userid']])->find();//查询出付款的用户
                $gold = $user['gold'] + $order['gold'];//原有金币+充值获取到的金币总额
                $silver = $user['silver'] + $order['amount'];//原有银币+充值获取到的银币数量
                M('all_user')->where(['id' => $user['id']])->save(['gold' => $gold, 'silver' => $silver]);//修改用户的金币数量
            }else{
                M('express_pay')->where(['out_trade_no'=>$result['out_trade_no']])->save(['status' => 1]);//将快递订单的状态改为1已经支付
                $express = M('express_pay')->where(['out_trade_no'=>$result['out_trade_no']])->find();
                M('tbl_game_log')->where(['id'=>$express['log_id']])->save(['status'=>1]);
                $res['log_id'] = $express['log_id'];
                $res['name'] = $express['name'];
                $res['create_time'] = time();
                $res['address'] = $express['address'];
                $res['phone'] = $express['phone'];
                $res['userid'] = $express['userid'];
                $order_id = M('tbl_order')->add($res);

            }

//            $url = U('http://192.168.1.171/#/recharge',array('out_trade_no'=>$out_trade_no));
        }
    }
    /**
     * 公众号支付 必须以get形式传递 out_trade_no 参数

     * 中的weixinpay_js方法
     */
    public function pay(){

        // 导入微信支付sdk
        Vendor('Weixinpay.Weixinpay');

        $wxpay=new \Weixinpay();

        // 获取jssdk需要用到的数据
        $data=$wxpay->getParameters();
        $this->ajaxReturn($data);die;
        // 将数据分配到前台页面

        $assign=array(
            'data'=>json_encode($data)
            );
        var_dump($assign);die;
//        echo $_GET['callback']."(".json_encode($data).")";
//
        $this->assign($assign);
        $this->display();
    }
    public function pay2(){
        Vendor('Weixinpay.Weixinpay');

        $wxpay=new \Weixinpay();

        // 获取jssdk需要用到的数据
        $data=$wxpay->getParameters2();
        $this->ajaxReturn($data);die;
    }

    public function test2(){
        dump(md5("goldbrother"));die;
    }


}