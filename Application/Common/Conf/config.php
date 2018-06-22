<?php
return array(

    'DB_TYPE' => 'mysql', // 数据库类型
    'DB_HOST' => '192.168.1.3', // 服务器地址
    'DB_NAME' => 'PiMachineServerDB', // 数据库名
    'DB_USER' => 'ljf', // 用户名
    'DB_PWD' => 'bjyxkf@2308', // 密码
    'DB_PORT' => 3306, // 端口
    'DB_PREFIX' => '', // 数据库表前缀
    'DB_CHARSET'=> 'utf8', // 字符集
    'DB_DEBUG' => TRUE, // 数据库调试模式 开启后可以记录SQL日志 

    'WEIXINPAY_CONFIG'       => array(
    'APPID'              => 'wx7d93e0114cc3453a', // 微信支付APPID
    'MCHID'              => '1457705302', // 微信支付MCHID 商户收款账号
    'KEY'                => 'ede449b5c872ada3365d8f91563dd8b6', // 微信支付KEY
    'APPSECRET'          => 'e64bda5d1006894a4f3cfb1b908dca19', // 公众帐号secert (公众号支付专用)
    // 'NOTIFY_URL'         => 'http://www.machine.com/Home/Weixinpay/notify', // 接收支付状态的连接
    'NOTIFY_URL'         => 'http://310975f0.nat123.cc/Home/Weixinpay/notify', // 接收支付状态的连接
    ),
);