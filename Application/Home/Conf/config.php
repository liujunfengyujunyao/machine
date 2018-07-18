<?php
return array(
	//'配置项'=>'配置值'
	'TMPL_L_DELIM'          =>  '{{',            // 模板引擎普通标签开始标记
    'TMPL_R_DELIM'          =>  '}}',            // 模板引擎普通标签结束标记
    'wx_open' => array(
    	//开放平台
    	'appId'     =>  'wx9e8c63f03cbd36aa',
    	'appSecret' =>  'aa30b7860f3247a789fff62b08681b7e'
    	),
    'wx_oauth'=>array(
    	//公众平台
    	'appId'     =>  '',
    	'appSecret' =>  '',
    	),
    'wx_test' =>array(
    	'appId'     =>  'wx7d93e0114cc3453a',
    	'appSecret' =>  'e64bda5d1006894a4f3cfb1b908dca19'
    	),

    // 'WEIXINPAY_CONFIG'       => array(
    // 'APPID'              => 'wx7d93e0114cc3453a', // 微信支付APPID
    // 'MCHID'              => '1457705302', // 微信支付MCHID 商户收款账号
    // 'KEY'                => 'ede449b5c872ada3365d8f91563dd8b6', // 微信支付KEY
    // 'APPSECRET'          => 'e64bda5d1006894a4f3cfb1b908dca19', // 公众帐号secert (公众号支付专用)
    // // 'NOTIFY_URL'         => 'http://www.machine.com/Home/Weixinpay/notify/', // 接收支付状态的连接
    // // 'NOTIFY_URL'         => 'http://liujunfeng.imwork.net:41413/Home/Weixinpay/notify',
    // 'NOTIFY_URL'         => 'http://www.12202.com/machine',
    // ),
    'WEIXINPAY_CONFIG'       => array(
    'APPID'              => 'wx9e8c63f03cbd36aa', // 微信支付APPID
    'MCHID'              => '1457705302', // 微信支付MCHID 商户收款账号
    'KEY'                => 'ede449b5c872ada3365d8f91563dd8b6', // 微信支付KEY
    'APPSECRET'          => 'aa30b7860f3247a789fff62b08681b7e', // 公众帐号secert (公众号支付专用)
    // 'NOTIFY_URL'         => 'http://www.machine.com/Home/Weixinpay/notify/', // 接收支付状态的连接
    // 'NOTIFY_URL'         => 'http://liujunfeng.imwork.net:41413/Home/Weixinpay/notify',
    // 'NOTIFY_URL'         => 'http://192.168.1.3/index.php/Home/Weixinpay/notify',
    'NOTIFY_URL'         => 'https://www.goldenbrother.com/index.php/Home/Weixinpay/notify',
    ),

    'DB_TYPE' => 'mysql', // 数据库类型
    // 'DB_HOST' => '192.168.1.3', // 服务器地址
    'DB_HOST' => '43.254.90.98', // 云服务器地址
    // 'DB_HOST' => 'mysql.sql41.cdncenter.net', // 云服务器地址
    'DB_NAME' => 'PiMachineServerDB', // 数据库名
    // 'DB_NAME' => 'sq_nugh123', // 数据库名
    'DB_USER' => 'ljf', // 用户名
    // 'DB_USER' => 'sq_nugh123', // 用户名
    'DB_PWD' => 'bjyxkf@2308', // 密码
    // 'DB_PWD' => 'k5487561', // 密码
    // 'DB_PORT' => 3306, // 内网端口
    'DB_PORT' => 12321, // 外网端口
    'DB_PREFIX' => '', // 数据库表前缀
    'DB_CHARSET'=> 'utf8', // 字符集
    'DB_DEBUG' => TRUE, // 数据库调试模式 开启后可以记录SQL日志 

    'DATA_CACHE_TYPE' => 'Memcache',
    'MEMCACHED_HOST' => '127.0.0.1',
    'MEMCACHED_PORT' => '11211',
    // 'DATA_CACHE_TIME' => '5000',//设置缓存时间

   'DB_CONFIG2' => array(
    //外网数据库链接地址
      'db_type'  => 'mysql',
      'db_user'  => 'ljf',
      'db_pwd'   => 'bjyxkf@2308',
      'db_host'  => '43.254.90.98',
      'db_port'  => '12321',
      'db_name'  => 'PiMachineServerDB',
   ),//里面存在跨库操作
);