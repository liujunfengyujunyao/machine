<?php
namespace app\mobile\controller;
class diaoyong extends Controller{
	public function index(){
		//获取测肤报告详情
		$url = 'http://191.168.1.123/EZM_Work/web_api/GetRecordDetail.php';//你的接口地址
        $data = array(
                'key'=>'vbhkneomak2naJSD9Ddjks901sj';//接口参数
                'id' => 1,
            );
        
		$return =curl_request($url,'POST',$data);
        $return = json_decode($return,1);
        //$return就是接收到的数据自己处理后$this->assign()到前台
        }
        	$this->display();
	}
}