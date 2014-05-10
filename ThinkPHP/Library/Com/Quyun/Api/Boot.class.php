<?php
// +----------------------------------------------------------------------
// | Quyun API Suite
// +----------------------------------------------------------------------
// | Copyright (c) 2014 http://www.quyun.com/ All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: 江林锦 <jianglj@quyun.com>
// +----------------------------------------------------------------------
namespace Com\Quyun\Api;

/**
 * API类库启动初始化
 */
class Boot
{
    private static $_inited = false; // 是否已初始化

    public static function init()
    {
        if (self::$_inited) return;
        self::$_inited = true;

        define('API_LIB_PATH', realpath(__DIR__).'/');

        // 加载公共函数
        include_once API_LIB_PATH.'Common/functions.php';

        // 加载API框架惯例配置
        C(include API_LIB_PATH.'Conf/convention.php');
        // 加载应用配置
        if (is_file(CONF_PATH.'api.php')) C(include CONF_PATH.'api.php');
        $loadExtConfig = C('API_LOAD_EXT_CONFIG');
        if (strlen($loadExtConfig) > 0)
        {
            $configs = explode(',', $loadExtConfig);
            foreach ($configs as $config)
            {
                if (is_file(CONF_PATH.$config.'.php')) C(include CONF_PATH.$config.'.php');
            }
        }
    }
}