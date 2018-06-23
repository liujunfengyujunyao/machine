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
        $this->display();
    }
  
    public function weixinpay_js(){
        $out_trade_no=time();
        $url = U('Home/Weixinpay/pay',array('out_trade_no'=>$out_trade_no));
        redirect($url);
    }

    public function key(){
        $key = md5("goldbrother");//ede449b5c872ada3365d8f91563dd8b6
        $count = count($key);
        dump($count);die;
        dump($key);die;
    }
    public function test(){
        $data = array(
            'id'=>1,
            );
        $url = "http://12202.com.cn/diamond/index.php/Home/index/weixinpay_js";
        $return = json_curl($url,$data);
       
    }

    public function ajax(){
        echo 3333;
    }
   
    
}
