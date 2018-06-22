<?php

namespace Home\Controller;
use Think\Controller;
// use Common\Plugin\WxLogin;
header("Content-type:text/html;charset=utf-8");
class RedirectController extends Controller{

public function redirect(){
	$params = $GLOBALS['HTTP_RAW_POST_DATA'];         
    $params = json_decode($params,true);
    $url = "http://liujunfeng.imwork.net:41413/Home/Weixinpay/getParameters";
    $return = $this->json_curl($url,$data);
}


public function json_curl($url, $para ){

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

public function test(){
    dump("test");die;
}

}