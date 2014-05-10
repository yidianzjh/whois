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
 * CIDR网段（仅支持IPv4）
 */
class CIDR
{
    const TYPE = 'simple';   // 简单类型

    public static function parse($paramValue, $typeOptions=null, $paramName='', $return=false)
    {
        // 默认配置
        $typeDefaultOptions = array(
            'AllowIpAddress' => true,   // 是否允许是IPv4地址
        );
        $options = is_array($typeOptions) ? array_merge($typeDefaultOptions, $typeOptions) : $typeDefaultOptions;

        // 检查是否为IPv4地址
        if (strpos($paramValue, '/') === false)
        {
            if ( ! $options['AllowIpAddress'] || $options['AllowIpAddress'] && ! ip2long($paramValue))
            {
                if ($return) return false;
                APIE('InvalidParam:'.$paramName.':NotCIDR', 'Parameter <'.$paramName.'> is not a cidr notation!');
            }
        }
        else
        {
            // 检查是否为合法的CIDR
            $parts = explode('/', $paramValue);
            $subnet = ip2long($parts[0]);
            $cidr = $parts[1];
            if (count($parts) != 2 || ! $subnet || filter_var($cidr, FILTER_VALIDATE_INT, array('min_range' => 0, 'max_range' => 32)) === false)
            {
                if ($return) return false;
                APIE('InvalidParam:'.$paramName.':NotCIDR', 'Parameter <'.$paramName.'> is not a cidr notation!');
            }

            // 192.168.1.1/24 不合法
            // 192.168.1.0/24 合法
            $netmask = long2ip(-1 << (32 - (int)$cidr));
            if (($subnet & ip2long($netmask)) != $subnet)
            {
                if ($return) return false;
                APIE('InvalidParam:'.$paramName.':NotCIDR', 'Parameter <'.$paramName.'> is not a cidr notation!');
            }
        }

        return $paramValue;
    }
}