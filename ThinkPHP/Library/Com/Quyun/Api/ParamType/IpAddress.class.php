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
namespace Com\Quyun\Api\ParamType;

/*
 * IP地址
 */
class IpAddress
{
    const TYPE = 'simple';   // 简单类型

    public static function parse($paramValue, $typeOptions=null, $paramName='', $return=false)
    {
        // 默认配置
        $typeDefaultOptions = array(
            'IPv4'  => true,    // 是否分析IPv4
            'IPv6'  => false,   // 是否分析IPv6
        );
        $options = is_array($typeOptions) ? array_merge($typeDefaultOptions, $typeOptions) : $typeDefaultOptions;

        if ($options['IPv4'])
        {
            if (ip2long($paramValue) !== false) return $paramValue;
        }

        if ($options['IPv6'])
        {
            if (preg_match('/\A
                (?:
                (?:
                (?:[a-f0-9]{1,4}:){6}
                |
                ::(?:[a-f0-9]{1,4}:){5}
                |
                (?:[a-f0-9]{1,4})?::(?:[a-f0-9]{1,4}:){4}
                |
                (?:(?:[a-f0-9]{1,4}:){0,1}[a-f0-9]{1,4})?::(?:[a-f0-9]{1,4}:){3}
                |
                (?:(?:[a-f0-9]{1,4}:){0,2}[a-f0-9]{1,4})?::(?:[a-f0-9]{1,4}:){2}
                |
                (?:(?:[a-f0-9]{1,4}:){0,3}[a-f0-9]{1,4})?::[a-f0-9]{1,4}:
                |
                (?:(?:[a-f0-9]{1,4}:){0,4}[a-f0-9]{1,4})?::
                )
                (?:
                [a-f0-9]{1,4}:[a-f0-9]{1,4}
                |
                (?:(?:[0-9]|[1-9][0-9]|1[0-9][0-9]|2[0-4][0-9]|25[0-5])\.){3}
                (?:[0-9]|[1-9][0-9]|1[0-9][0-9]|2[0-4][0-9]|25[0-5])
                )
                |
                (?:
                (?:(?:[a-f0-9]{1,4}:){0,5}[a-f0-9]{1,4})?::[a-f0-9]{1,4}
                |
                (?:(?:[a-f0-9]{1,4}:){0,6}[a-f0-9]{1,4})?::
                )
                )\Z/ix', 
                $paramValue
            )) return $paramValue;
        }

        if ($return) return false;
        APIE('InvalidParam:'.$paramName.':NotIpAddress', 'Parameter <'.$paramName.'> is not an ip address!');
    }
}