<?php
namespace Home\Controller;
use Think\Controller;
header('Access-Control-Allow-Origin:*');
header("Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept");
class IndexController extends Controller {
    public function index(){
        //查询图片轮播部分商品信息
        // $goods3 = D('Goods')->where('id in (1,2,3)')->select();
        // $this->assign('goods3',$goods3);
        // //查询经典产品部分商品信息
        // $goods = D('Goods')->select();
        // $this->assign('goods',$goods);
//        $data = M()->db(2,'DB_CONFIG2')->table("all_user")->where(['id'=>1])->find();
        $res = M('all_user')->where(['id'=>1])->find();
        var_dump($res);die;
//        $this->display();
    }
    public function ajax(){
        $data = array(
            'msgtype' => 'test',
        );
        $data = json_encode($data,1);
        echo $data;
    }
    public function weixinpay_js1(){
        //接收穿过来的订单编号
//        $params = $GLOBALS['HTTP_RAW_POST_DATA'];
//        $params = json_decode($params,true);
        $params['userid'] = I('get.userid');
        $user = M('all_user')->where(['id'=>$params['userid']])->find();
        if (!$params['userid']){
            $data =  array(
                'errid'=>10003,
                'msgtype' => 'userid error',
            );
            $data = json_encode($data);
            echo $data;die;
        }
        $params['id'] = 1;//对应order表的订单编号
        $out_trade_no = time()+round(1,999);//生成订单编号
        //将订单存入数据库,status为0(未支付)
        $data = array(
            'out_trade_no' => $out_trade_no,
            'create_time' => time(),
            'order_id' => $params['id'],
            'userid' => $params['userid'],
//            'brand' => $brand,
        );
        M('Order_log')->add($data);
        $url = U('Home/Weixinpay/pay',array('out_trade_no'=>$out_trade_no));
        $this->ajaxReturn($url);die;
//
//        Vendor('Weixinpay.Weixinpay');
//
//        $wxpay=new \Weixinpay();
//
//        // 获取jssdk需要用到的数据
//        $data=$wxpay->getParameters();
//        var_dump($data);die;
//          $url = "www.12202.com.cn.diamond/index.php/Home/Weixinpay/pay/out_trade_no/$out_trade_no.html";
//          $array = array(
//              'out_trade_no'=>$out_trade_no,
//        );
//          var_dump($url);die;
//        var_dump($url);die;
//        $url = "http://www.12202.com.cn/vue/index.html#/recharge?out_trade_no=1528688753";
//        $url = "http://www.12202.com.cn/diamond/index.php/Home/ajax/index2?out_trade_no=$out_trade_no";
//        var_dump($url);die;
//        var_dump(json_encode($array));die;
        redirect($url);


    }
    public function weixinpay_js5(){
        //接收穿过来的订单编号
//        $params = $GLOBALS['HTTP_RAW_POST_DATA'];
//        $params = json_decode($params,true);
        $params['userid'] = I('get.userid');
        if (!$params['userid']){
            $data =  array(
                'errid'=>10003,
                'msgtype' => 'userid error',
            );
            $data = json_encode($data);
            echo $data;die;
        }
        $params['id'] = 2;

//        $params['id'] = 1;
        $out_trade_no = time()+rand(0,99999);//生成订单编号
        //将订单存入数据库,status为0(未支付)
        $data = array(
            'out_trade_no' => $out_trade_no,
            'create_time' => time(),
            'order_id' => $params['id'],
            'userid' => $params['userid'],
        );
        M('Order_log')->add($data);
        $url = U('Home/Weixinpay/pay',array('out_trade_no'=>$out_trade_no));
        $this->ajaxReturn($url);die;
        redirect($url);

    }
    public function express(){
        $params['userid'] = I('get.userid');
        $user = M('all_user')->where(['id'=>$params['userid']])->find();
        if (!$params['userid']){
            $data =  array(
                'errid'=>10003,
                'msgtype' => 'userid error',
            );
            $data = json_encode($data);
            echo $data;die;
        }
        $params['id'] = 10;//对应order表的订单编号
        $out_trade_no = rand(10,999999);//生成订单编号
        //将订单存入数据库,status为0(未支付)
        $data = array(
            'out_trade_no' => $out_trade_no,
            'create_time' => time(),
            'order_id' => $params['id'],
            'userid' => $params['userid'],
//            'brand' => $brand,
        );
        M('Express_pay')->add($data);
        $url = U('Home/Weixinpay/pay2',array('out_trade_no'=>$out_trade_no));
//        $this->ajaxReturn($url);die;
        redirect($url);
    }

    public function weixinpay_js10(){
        //接收穿过来的订单编号
//        $params = $GLOBALS['HTTP_RAW_POST_DATA'];
//        $params = json_decode($params,true);
        $params['userid'] = I('get.userid');
        if (!$params['userid']){
            $data =  array(
                'errid'=>10003,
                'msgtype' => 'userid error',
            );
            $data = json_encode($data);
            echo $data;die;
        }
        $params['id'] = 3;

//        $params['id'] = 1;
        $out_trade_no = time()+rand(0,999);//生成订单编号
        //将订单存入数据库,status为0(未支付)
        $data = array(
            'out_trade_no' => $out_trade_no,
            'create_time' => time(),
            'order_id' => $params['id'],
            'userid' => $params['userid'],
        );
        M('Order_log')->add($data);
        $url = U('Home/Weixinpay/pay',array('out_trade_no'=>$out_trade_no));
        $this->ajaxReturn($url);
        redirect($url);
    }
    public function weixinpay_js20(){
        //接收穿过来的订单编号
//        $params = $GLOBALS['HTTP_RAW_POST_DATA'];
//        $params = json_decode($params,true);
        $params['userid'] = I('get.userid');
        if (!$params['userid']){
            $data =  array(
                'errid'=>10003,
                'msgtype' => 'userid error',
            );
            $data = json_encode($data);
            echo $data;die;
        }
        $params['id'] = 4;

//        $params['id'] = 1;
        $out_trade_no = time()+rand(0,999);//生成订单编号
        //将订单存入数据库,status为0(未支付)
        $data = array(
            'out_trade_no' => $out_trade_no,
            'create_time' => time(),
            'order_id' => $params['id'],
            'userid' => $params['userid'],
        );
        M('Order_log')->add($data);
        $url = U('Home/Weixinpay/pay',array('out_trade_no'=>$out_trade_no));
        $this->ajaxReturn($url);die;
        redirect($url);
    }
    public function weixinpay_js50(){
        //接收穿过来的订单编号
//        $params = $GLOBALS['HTTP_RAW_POST_DATA'];
//        $params = json_decode($params,true);
        $params['userid'] = I('get.userid');
        if (!$params['userid']){
            $data =  array(
                'errid'=>10003,
                'msgtype' => 'userid error',
            );
            $data = json_encode($data);
            echo $data;die;
        }
        $params['id'] = 5;

//        $params['id'] = 1;
        $out_trade_no = time()+rand(0,999);//生成订单编号
        //将订单存入数据库,status为0(未支付)
        $data = array(
            'out_trade_no' => $out_trade_no,
            'create_time' => time(),
            'order_id' => $params['id'],
            'userid' => $params['userid'],
        );
        M('Order_log')->add($data);
        $url = U('Home/Weixinpay/pay',array('out_trade_no'=>$out_trade_no));
        $this->ajaxReturn($url);
        redirect($url);
    }
    public function weixinpay_js100(){
        //接收穿过来的订单编号
//        $params = $GLOBALS['HTTP_RAW_POST_DATA'];
//        $params = json_decode($params,true);
        $params['userid'] = I('get.userid');
        if (!$params['userid']){
            $data =  array(
                'errid'=>10003,
                'msgtype' => 'userid error',
            );
            $data = json_encode($data);
            echo $data;die;
        }
        $params['id'] = 6;

//        $params['id'] = 1;
        $out_trade_no = time()+rand(0,999);//生成订单编号
        //将订单存入数据库,status为0(未支付)
        $data = array(
            'out_trade_no' => $out_trade_no,
            'create_time' => time(),
            'order_id' => $params['id'],
            'userid' => $params['userid'],
        );
        M('Order_log')->add($data);
        $url = U('Home/Weixinpay/pay',array('out_trade_no'=>$out_trade_no));
        $this->ajaxReturn($url);
        redirect($url);
    }
    public function weixinpay_js200(){
        //接收穿过来的订单编号
//        $params = $GLOBALS['HTTP_RAW_POST_DATA'];
//        $params = json_decode($params,true);
        $params['userid'] = I('get.userid');
        if (!$params['userid']){
            $data =  array(
                'errid'=>10003,
                'msgtype' => 'userid error',
            );
            $data = json_encode($data);
            echo $data;die;
        }
        $params['id'] = 7;

//        $params['id'] = 1;
        $out_trade_no = time()+rand(0,999);//生成订单编号
        //将订单存入数据库,status为0(未支付)
        $data = array(
            'out_trade_no' => $out_trade_no,
            'create_time' => time(),
            'order_id' => $params['id'],
            'userid' => $params['userid'],
        );
        M('Order_log')->add($data);
        $url = U('Home/Weixinpay/pay',array('out_trade_no'=>$out_trade_no));
        $this->ajaxReturn($url);
        redirect($url);
    }
    public function weixinpay_js500(){
        //接收穿过来的订单编号
//        $params = $GLOBALS['HTTP_RAW_POST_DATA'];
//        $params = json_decode($params,true);
        $params['userid'] = I('get.userid');
        if (!$params['userid']){
            $data =  array(
                'errid'=>10003,
                'msgtype' => 'userid error',
            );
            $data = json_encode($data);
            echo $data;die;
        }
        $params['id'] = 8;

//        $params['id'] = 1;
        $out_trade_no = time()+rand(0,999);//生成订单编号
        //将订单存入数据库,status为0(未支付)
        $data = array(
            'out_trade_no' => $out_trade_no,
            'create_time' => time(),
            'order_id' => $params['id'],
            'userid' => $params['userid'],
        );
        M('Order_log')->add($data);
        $url = U('Home/Weixinpay/pay',array('out_trade_no'=>$out_trade_no));
        $this->ajaxReturn($url);
        redirect($url);
    }
    public function weixinpay_js1000(){
        //接收穿过来的订单编号
//        $params = $GLOBALS['HTTP_RAW_POST_DATA'];
//        $params = json_decode($params,true);
        $params['userid'] = I('get.userid');
        if (!$params['userid']){
            $data =  array(
                'errid'=>10003,
                'msgtype' => 'userid error',
            );
            $data = json_encode($data);
            echo $data;die;
        }
        $params['id'] = 9;

//        $params['id'] = 1;
        $out_trade_no = time()+rand(0,999);//生成订单编号
        //将订单存入数据库,status为0(未支付)
        $data = array(
            'out_trade_no' => $out_trade_no,
            'create_time' => time(),
            'order_id' => $params['id'],
            'userid' => $params['userid'],
        );
        M('Order_log')->add($data);
        $url = U('Home/Weixinpay/pay',array('out_trade_no'=>$out_trade_no));
        $this->ajaxReturn($url);
        redirect($url);
    }
    public function test(){
        $data = array(
            'id'=>1,
        );
        $url = "http://www.12202.com.cn/diamond/index.php/Home/index/weixinpay_js";
        $return = json_curl($url,$data);

    }

    public function ce(){
        dump(2);die;
    }
}
