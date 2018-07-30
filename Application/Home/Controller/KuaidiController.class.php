<?php
namespace Home\Controller;
use Think\Controller;
header("content-type:text/html;charset=utf-8");
class KuaidiController extends Controller{
	public function logistics(){
			$params = $GLOBALS['HTTP_RAW_POST_DATA'];   
            $params = json_decode($params,true);
			$tbl_order = M('tbl_order')->alias('t1')->field('t1.name,t1.address,t1.phone,t1.trace_number,t2.express_name')->where(['t1.trace_number'=>$params['trackingid']])->join('left join express as t2 on t1.express_id = t2.express_id')->find();
			if($tbl_order['express_name']=='圆通'){
				$ShipperCode = 'YTO';
			}elseif($tbl_order['express_name']=='中通') {
				$ShipperCode = 'ZTO';
			}elseif($tbl_order['express_name']=='韵达') {
				$ShipperCode = 'YD';
			}elseif($tbl_order['express_name']=='申通') {
				$ShipperCode = 'STO';
			}elseif($tbl_order['express_name']=='EMS') {
				$ShipperCode = 'EMS';
			}elseif($tbl_order['express_name']=='天天') {
				$ShipperCode = 'HHTT';
			}elseif($tbl_order['express_name']=='顺丰') {
				$ShipperCode = 'SF';
			}
			$res = $this->getMessage($ShipperCode,$tbl_order['trace_number']);
			$data[] = json_decode($res,JSON_UNESCAPED_UNICODE);
			foreach ($data as $key => $value) {
				$LogisticCode = $value['LogisticCode'];
				$Traces[$key] = $value['Traces'];
				if($value['State']==0){
				$State = '无轨迹!';
				}elseif($value['State']==1) {
					$State = '已揽收!';
				}elseif($value['State']==2) {
					$State = '在途中!';
				}elseif($value['State']==3) {
					$State = '签收!';
				}elseif($value['State']==4) {
					$State = '问题件!';
				}
			}
			$array = array(
				'LogisticCode'=>$LogisticCode,
				'ShipperCode'=>$tbl_order['express_name'],
				'Traces'=>$Traces,
				'State'=>$State,
				);
			var_dump($array);die;
	}
/**
 * 使用快递鸟api进行查询
 * User: Administrator
 * Date: 2017/4/22 0022
 * Time: 09:09
 */
	//测试ID,1285564。。。key,264ff9e0-2f4c-48d5-877f-1e0670400d18
	//正式ID.1326909...key,17e40394-2314-4ec6-b63c-a23ef05d1f80
    const EBusinessID = 1285564;
    const AppKey = '264ff9e0-2f4c-48d5-877f-1e0670400d18';
    const ReqURL = "http://api.kdniao.cc/Ebusiness/EbusinessOrderHandle.aspx";

    /**
     * @param $ShipperCode 快递公司编号
     * @param $order_sn 运单号
     */
    public function getMessage($ShipperCode,$order_sn){
        $requestData= "{'OrderCode':'','ShipperCode':'".$ShipperCode."','LogisticCode':'".$order_sn."'}";
        $datas = array(
            'EBusinessID' => self::EBusinessID,
            'RequestType' => '1002',//接口指令1002，固定
            'RequestData' => urlencode($requestData) ,
            'DataType' => '2', //数据返回格式 2 json
        );
        //把$requestData进行加密处理
        $datas['DataSign'] = $this -> encrypt($requestData, self::AppKey);
        $result = $this -> sendPost( self::ReqURL, $datas);
        return $result;
    }

    /**
     *  post提交数据
     * @param  string $url 请求Url
     * @param  array $datas 提交的数据
     * @return url响应返回的html
     */
    function sendPost($url, $datas) {
        $temps = array();
        foreach ($datas as $key => $value) {
            $temps[] = sprintf('%s=%s', $key, $value);
        }
        $post_data = implode('&', $temps);
        $url_info = parse_url($url);
        if(empty($url_info['port']))
        {
            $url_info['port']=80;
        }
        $httpheader = "POST " . $url_info['path'] . " HTTP/1.0\r\n";
        $httpheader.= "Host:" . $url_info['host'] . "\r\n";
        $httpheader.= "Content-Type:application/x-www-form-urlencoded\r\n";
        $httpheader.= "Content-Length:" . strlen($post_data) . "\r\n";
        $httpheader.= "Connection:close\r\n\r\n";
        $httpheader.= $post_data;
        $fd = fsockopen($url_info['host'], $url_info['port']);
        fwrite($fd, $httpheader);
        $gets = "";
        $headerFlag = true;
        while (!feof($fd)) {
            if (($header = @fgets($fd)) && ($header == "\r\n" || $header == "\n")) {
                break;
            }
        }
        while (!feof($fd)) {
            $gets.= fread($fd, 128);
        }
        fclose($fd);

        return $gets;
    }


    /*
     * 进行加密
     */
    function encrypt($data, $appkey) {
        return urlencode(base64_encode(md5($data.$appkey)));
    }
}

