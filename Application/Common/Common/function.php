<?php 
// //手机号加密
//  function encrypt_phone($phone){
// 	return substr($phone,0,3) . '****' .substr($phone,7,4);
// }

//密码加密
function encrypt_password($password){
    //加盐
    $salt = 'djsdgfdkfdskafhds';
    return md5( $salt . md5($password) );
}
#递归方法实现无限极分类
function getTree($list,$pid=0,$level=0) {
    static $tree = array();
    foreach($list as $row) {
        if($row['pid']==$pid) {
            $row['level'] = $level;
            $tree[] = $row;
            getTree($list, $row['id'], $level + 1);
        }
    }
    return $tree;
}
//手机号加密
 function encrypt_phone($phone){
    return substr($phone,0,3) . '****' .substr($phone,7,4);
}

//接口 
//封装发送curl请求的函数
function curl_request($url,$post = false,$data = array(),$https = fasle){
    //初始化curl请求,设置请求地址
    $ch = curl_init($url);
    //设置请求参数 针对post请求
    if ($post) {
        //发送post请求
        curl_setopt($ch,CURLOPT_POST,true);//设置请求方式为post
        curl_setopt($ch,CURLOPT_POSTFIELDS,$data);//设置请求参数
    }

    //绕过https协议的证书校验
    if ($https) {
        //当前发送的是https协议的请求
        //禁用证书校验
        curl_setopt($ch,CURLOPT_SSL_VERIFYHOST, fasle);
        curl_setopt($ch,CURLOPT_SSL_VERIFYPEER, fasle);
    }
    //发送请求
    //直接返回结果
    curl_setopt($ch,CURLOPT_RETURNTRANSFER,true);
    $result = curl_exec($ch);//成功就是返回数据 失败返回fasle
    //关闭请求 释放请求资源
    curl_close($ch);
    //返回数据
    return $result;
    
}
function sendmail($email, $subject, $body){
    require './Application/Tools/PHPMailer/PHPMailerAutoload.php';
    $mail = new PHPMailer;

    //$mail->SMTPDebug = 3;                               // Enable verbose debug output

    $mail->isSMTP();                                      // 设置使用SMTP服务
    $mail->Host = 'smtp.qq.com';                          // 设置SMTP服务器主机地址
    $mail->SMTPAuth = true;                               // 开启SMTP认证
    $mail->Username = '1004184169@qq.com';                  // SMTP username
    $mail->Password = 'sopdbvmrizxabcih';                 // SMTP password
    $mail->SMTPSecure = 'tls';                            // 使用tls加密方式
    $mail->Port = 25;                                     // 邮件发送端口 25 默认是587
    $mail->CharSet = 'UTF-8'; //设置邮件字符编码  //
    $mail->setFrom('1004184169@qq.com');                      //设置发件人邮箱
    $mail->addAddress($email);                              //添加一个收件人邮箱
    // $mail->addAddress('ellen@example.com');               // 可以添加多个收件人
    // $mail->addReplyTo('info@example.com', 'Information');
    // $mail->addCC('cc@example.com');
    // $mail->addBCC('bcc@example.com');

    // $mail->addAttachment('/var/tmp/file.tar.gz');         // 添加附件
    // $mail->addAttachment('/tmp/image.jpg', 'new.jpg');    // Optional name
    $mail->isHTML(true);                                  // 设置邮件内容格式为html

    $mail->Subject = $subject; //邮件主题
    $mail->Body    = $body; //邮件内容
    // $mail->AltBody = 'This is the body in plain text for non-HTML mail clients';

    if(!$mail->send()) {
        //发送失败 直接返回错误信息
        return $mail->ErrorInfo;
    } else {
        //发送成功，返回true
        return true;
    }
}
//Json传输数据
function json_curl($url, $para ){

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
// function curl_request($url, $post = false, $data=array(), $https = false){
//       //使用curl_init初始化一个curl请求
//       $ch = curl_init($url);
//       //默认为get请求不需要设置请求方式和请求参数
//       //如果是post请求
//       if($post){
//       //设置请求方式
//       curl_setopt($ch, CURLOPT_POST, true);
//       //设置请求参数
//       curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
//       }
//       //默认发送http请求，如果是https，需要做特殊设置
//       if($https){
//       curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);//验证证书 设置为false表示不验证
//       curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);//验证证书和主机是否匹配
//       }
//       //默认情况下，curl_exec返回true|false,如果要得到返回数据，需要设置CURLOPT_RETURNTRANSFER
//       curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
//       //发送请求
//       $res = curl_exec($ch);
//       if(!$res){
//       //请求失败，通过curl_error获取错误信息
//       $error = curl_error($ch);
//       //重新组装返回结果。如果返回的是数组代表请求失败
//       $res = array(
//       'error' => $error
//       );
//       }
//       //关闭curl请求
//       curl_close($ch);
//       //返回结果给调用方
//       return $res;
//       }

/**
 * 二维数组根据字段进行排序
 * @params array $array 需要排序的数组
 * @params string $field 排序的字段
 * @params string $sort 排序顺序标志 SORT_DESC 降序；SORT_ASC 升序
 */
function arraySequence($array, $field, $sort = 'SORT_DESC')
{
    $arrSort = array();
    foreach ($array as $uniqid => $row) {
        foreach ($row as $key => $value) {
            $arrSort[$key][$uniqid] = $value;
        }
    }
    array_multisort($arrSort[$field], constant($sort), $array);
    return $array;
}
function curl_get_contents($url){
    $ch=curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);                //设置访问的url地址
    // curl_setopt($ch,CURLOPT_HEADER,1);               //是否显示头部信息
    curl_setopt($ch, CURLOPT_TIMEOUT, 5);               //设置超时
    curl_setopt($ch, CURLOPT_USERAGENT, $_SERVER['HTTP_USER_AGENT']);   //用户访问代理 User-Agent
    curl_setopt($ch, CURLOPT_REFERER,$_SERVER['HTTP_HOST']);        //设置 referer
    curl_setopt($ch,CURLOPT_FOLLOWLOCATION,1);          //跟踪301
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);        //返回结果
    $r=curl_exec($ch);
    curl_close($ch);
    return $r;
}

function qrcode($url,$size=4){
    Vendor('Phpqrcode.phpqrcode');
    QRcode::png($url,false,QR_ECLEVEL_L,$size,2,false,0xFFFFFF,0x000000);
}
function batch_update($table_name='',$data=array(),$field=''){

if(!$table_name||!$data||!$field){
return false;
}else{
$sql='UPDATE '.$table_name;
}

$con=array();
$con_sql=array();
$fields=array();

foreach ($data as $key => $value) {

$x=0;
foreach ($value as $k => $v) {

if($k!=$field&&!$con[$x]&&$x==0){$con[$x]=" set {$k} = (CASE {$field} ";}

elseif($k!=$field&&!$con[$x]&&$x>0){$con[$x]=" {$k} = (CASE {$field} ";}

if($k!=$field){
$temp=$value[$field];
$con_sql[$x].= " WHEN '{$temp}' THEN '{$v}' ";
$x++;
}
}
$temp=$value[$field];

if(!in_array($temp,$fields)){$fields[]=$temp;}
}
$num=count($con)-1;

foreach ($con as $key => $value) {

foreach ($con_sql as $k => $v) {

if($k==$key&&$key<$num){$sql.=$value.$v.' end),';}

elseif($k==$key&&$key==$num){$sql.=$value.$v.' end)';}
}
}
$str=implode(',',$fields);
$sql.=" where {$field} in({$str})";
$res = M()->execute($sql);
//$res =$sql;
return $res;
}
 function httpGet($url) {
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_TIMEOUT, 500);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
        curl_setopt($curl, CURLOPT_URL, $url);
        $res = curl_exec($curl);
        curl_close($curl);
        return $res;
    }

function navigate_user()
{    
    $navigate = include APP_PATH.'home/navigate.php';    
    $location = strtolower('Home/'.CONTROLLER_NAME);
    $arr = array(
        '首页'=>'/',
        $navigate[$location]['name']=>U('/Home/'.CONTROLLER_NAME),
        $navigate[$location]['action'][ACTION_NAME]=>'javascript:void();',
    );
    return $arr;
}
    //生成每日机器记录时用到的遍历函数
        function inspirit($all = array(),$other = array(),$pre = 'count'){
        foreach($all as $k=>&$v){
            foreach($other as $k1=>$v1){
                if($v['equipment_id']==$v1['equipment_id']){
                  $v[$pre] = $v1['count'];
                }
              }
            if(!$v[$pre]){
                $v[$pre] = '0';
            }
        }
        return $all;
    }
     //封装的截取函数----msubstr
   function msubstr($str, $start=0, $length, $charset="utf-8", $suffix=true)  
    {  
      if(function_exists("mb_substr")){  
              if($suffix)
                    if(mb_strlen($str,"utf-8") > $length)
                      return mb_substr($str, $start, $length, $charset);  
                    else 
                        return mb_substr($str, $start, $length, $charset);
              else
                   return mb_substr($str, $start, $length, $charset);  
         }  
         elseif(function_exists('iconv_substr')) {  
             if($suffix)  
                  return iconv_substr($str,$start,$length,$charset);  
             else
                  return iconv_substr($str,$start,$length,$charset);  
         }  
         $re['utf-8']   = "/[x01-x7f]|[xc2-xdf][x80-xbf]|[xe0-xef]
                  [x80-xbf]{2}|[xf0-xff][x80-xbf]{3}/";  
         $re['gb2312'] = "/[x01-x7f]|[xb0-xf7][xa0-xfe]/";  
         $re['gbk']    = "/[x01-x7f]|[x81-xfe][x40-xfe]/";  
         $re['big5']   = "/[x01-x7f]|[x81-xfe]([x40-x7e]|xa1-xfe])/";  
         preg_match_all($re[$charset], $str, $match);  
         $slice = join("",array_slice($match[0], $start, $length));  
         if($suffix) return $slice;  
         return $slice;
    }
 ?>