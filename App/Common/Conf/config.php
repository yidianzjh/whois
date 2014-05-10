<?php
return array(
    // development时，不验证签名，输出格式较为易读，发布时请改值或注释掉
    'ENVIRONMENT'    => 'development',

    // 设置默认模块
    'DEFAULT_MODULE'  => 'Whois',

    // url区分大小写
    'URL_CASE_INSENSITIVE' => false,

    'ACTION_SUFFIX'  =>  'Action',  // 操作方法后缀

    // 显示页面Trace信息
    //'SHOW_PAGE_TRACE' => true,

    //api_timestamp过期时间
    'TIMESTAMP_EXPIRATION' => 3600,  //1小时

    // 数据库配置信息
    'DB_TYPE'   => 'mysql', // 数据库类型
    'DB_HOST'   => 'localhost', // 服务器地址
    'DB_NAME'   => 'whois_base', // 数据库名
    'DB_USER'   => 'whois_base', // 用户名
    'DB_PWD'    => 'whois_base', // 密码
    'DB_PORT'   => 3306, // 端口
    'DB_PREFIX' => '', // 数据库表前缀
);