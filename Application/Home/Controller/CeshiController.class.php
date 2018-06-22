<?php
namespace Home\Controller;
use Think\Controller;
class TixianController extends Controller{
     private $config=array(
        'APPID'              => 'wx7d93e0114cc3453a', // 微信支付APPID
        'MCHID'              => '1457705302', // 微信支付MCHID 商户收款账号
        'KEY'                => 'ede449b5c872ada3365d8f91563dd8b6', // 微信支付KEY
        'APPSECRET'          => 'e64bda5d1006894a4f3cfb1b908dca19',  //公众帐号secert
        'NOTIFY_URL'         => 'http://310975f0.nat123.cc/Home/Weixinpay/notify', // 接收支付状态的连接  改成自己的域名
        );
    // 构造函数
    public function __construct(){
        // 如果是在thinkphp中 那么需要补全/Application/Common/Conf/config.php中的配置
        // 如果不是在thinkphp框架中使用；那么注释掉下面一行代码；直接补全 private $config 即可
        $this->config=C('WEIXINPAY_CONFIG');
    }
$mch_appid=$appid;//公众账号appid
$mchid='10000005';//商户号
$nonce_str='qyzf'.rand(100000, 999999);//随机数
$partner_trade_no='xx'.time().rand(10000, 99999);//商户订单号
$openid=$openids;//用户唯一标识,上一步授权中获取
$check_name='NO_CHECK';//校验用户姓名选项，NO_CHECK：不校验真实姓名， FORCE_CHECK：强校验真实姓名（未实名认证的用户会校验失败，无法转账），OPTION_CHECK：针对已实名认证的用户才校验真实姓名（未实名认证用户不校验，可以转账成功）
$re_user_name='测试';//用户姓名
$amount=100;//企业金额，这里是以分为单位（必须大于100分）
$desc='测试数据呀！！！';//描述
$spbill_create_ip='192.168.0.1';//请求ip



$dataArr=array();

$dataArr['amount']=$amount;

$dataArr['check_name']=$check_name;

$dataArr['desc']=$desc;

$dataArr['mch_appid']=$mch_appid;

$dataArr['mchid']=$mchid;

$dataArr['nonce_str']=$nonce_str;

$dataArr['openid']=$openid;

$dataArr['partner_trade_no']=$partner_trade_no;

$dataArr['re_user_name']=$re_user_name;

$dataArr['spbill_create_ip']=$spbill_create_ip; 

//生成签名

$sign=getSign($dataArr);//getSign($dataArr);见结尾

echo "-----<br/>签名：".$sign."<br/>*****";//die;

//拼写正确的xml参数

$data="<xml>

<mch_appid>".$mch_appid."</mch_appid>

<mchid>".$mchid."</mchid>

<nonce_str>".$nonce_str."</nonce_str>

<partner_trade_no>".$partner_trade_no."</partner_trade_no>

<openid>".$openid."</openid>

<check_name>".$check_name."</check_name>

<re_user_name>".$re_user_name."</re_user_name>

<amount>".$amount."</amount>

<desc>".$desc."</desc>

<spbill_create_ip>".$spbill_create_ip."</spbill_create_ip>

<sign>".$sign."</sign>

</xml>";  

4、发出企业付款请求

$ch = curl_init ();
$MENU_URL="https://api.mch.weixin.qq.com/mmpaymkttransfers/promotion/transfers";
curl_setopt ( $ch, CURLOPT_URL, $MENU_URL );
curl_setopt ( $ch, CURLOPT_CUSTOMREQUEST, "POST" );
curl_setopt ( $ch, CURLOPT_SSL_VERIFYPEER, FALSE );
curl_setopt ( $ch, CURLOPT_SSL_VERIFYHOST, FALSE );

//两个证书（必填，请求需要双向证书。）
$zs1="/apiclient_cert.pem";
$zs2="/apiclient_key.pem";
curl_setopt($ch,CURLOPT_SSLCERT,$zs1);
curl_setopt($ch,CURLOPT_SSLKEY,$zs2);
curl_setopt ( $ch, CURLOPT_FOLLOWLOCATION, 1 );
curl_setopt ( $ch, CURLOPT_AUTOREFERER, 1 );
curl_setopt ( $ch, CURLOPT_POSTFIELDS, $data );
curl_setopt ( $ch, CURLOPT_RETURNTRANSFER, true );

$info = curl_exec ( $ch );
if (curl_errno ( $ch )) {
echo 'Errno' . curl_error ( $ch );
}
curl_close ( $ch );
echo "-----<br/>请求返回值：";
var_dump($info);
echo "<br/>*****";die;




/**
 * 作用：生成签名
 */
function getSign($Obj)
{
var_dump($Obj);//die;
foreach ($Obj as $k => $v)
{
$Parameters[$k] = $v;
}
//签名步骤一：按字典序排序参数
ksort($Parameters);
$String = formatBizQueryParaMap($Parameters, false);//方法如下
//echo '【string1】'.$String.'</br>';
//签名步骤二：在string后加入KEY
$String = $String."&key=6cd1c9cab639cb399cb371cbd893e15e";
//echo "【string2】".$String."</br>";
//签名步骤三：MD5加密
$String = md5($String);
//echo "【string3】 ".$String."</br>";
//签名步骤四：所有字符转为大写
$result_ = strtoupper($String);
//echo "【result】 ".$result_."</br>";
return $result_;
}


/**
 * 作用：格式化参数，签名过程需要使用
 */
function formatBizQueryParaMap($paraMap, $urlencode)
{
var_dump($paraMap);//die;
$buff = "";
ksort($paraMap);
foreach ($paraMap as $k => $v)
{
if($urlencode)
{
$v = urlencode($v);
}
//$buff .= strtolower($k) . "=" . $v . "&";
$buff .= $k . "=" . $v . "&";
}
$reqPar;
if (strlen($buff) > 0)
{
$reqPar = substr($buff, 0, strlen($buff)-1);
}
var_dump($reqPar);//die;
return $reqPar;
}

}