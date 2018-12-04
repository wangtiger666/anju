<?php
return array(
    "DB_TYPE" => "mysql", //数据库类型 
    "DB_HOST" => "localhost", //数据库服务器地址

    //"DB_NAME" => "pano", //数据库名称
    //"DB_USER" => "root", //数据库用户名
    //"DB_PWD" => "root", //数据库用户密码
    //"DB_CHARSET" => "utf8",
    //'DB_PORT' => 3306, // 端口
    //'DB_PREFIX' => 'pano_', // 数据库表前缀

    "DB_NAME" => "anju", //数据库名称
    "DB_USER" => "root", //数据库用户名
    "DB_PWD" => "root", //数据库用户密码
    "DB_CHARSET" => "utf8",
    'DB_PORT' => 3306, // 端口
    'DB_PREFIX' => 'anju_', // 数据库表前缀


    'UPLOAD_FILE' => '/uploads', //上传目录
    'UPLOAD_BAG' => '/station', //上传暂存目录
    'HOST_DOMAIN' => 'http://pano.56cy.com', //上传暂存目录

    'SESSION_OPTIONS' => array('path'=>APP_ROOT."/session"),
    'DEFAULT_TIMEZONE'=>'PRC',// 默认时区

    "URL_CASE_INSENSITIVE" => true, //URL是否不区分大小写
    "URL_MODEL" => 2 ,//URL访问模式支持 0 (普通模式); 1 (PATHINFO 模式); 2 (REWRITE 模式);3 (兼容模式)
    'DEFAULT_MODULE'     => 'Welcome', //默认模块

    //华为云短信配置信息
    "HUANWEI_YUN_DUANXIN" => array(
		'APP_KEY' => 'Wq07oo35U09Ac9NZ2S202d5E7o6q',//appkey
        'APP_SECRET' => 'NcF3K0lrgWybTPLQMLaq82hijzgi',//appsecret
        'CHANNEL_NO' => '1069100121280199',//通道编号
        'URL' => 'https://117.78.29.66:10443/sms/batchSendSms/v1',//请求地址
        'TEST_TEMPLE_ID' => '298d92ebc7a44be88d2e4f425114fa3a',//测试模板id
	)
);
?>