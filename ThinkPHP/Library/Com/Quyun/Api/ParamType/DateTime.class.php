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
 * 时间
 * 格式必须为 yyyy-mm-dd hh:ii:ss
 */
class DateTime
{
    const TYPE = 'simple';   // 简单类型

    public static function parse($paramValue, $typeOptions=null, $paramName='', $return=false)
    {
        // 默认配置
        $typeDefaultOptions = array(
            'Min'   => null,    // 最小值，null表示不限制，支持在strtotime中合法的表达式
            'Max'   => null,    // 最大值，null表示不限制，支持在strtotime中合法的表达式
            'ToTimestamp'  => true,    // 是否转化为时间戳
        );
        $options = is_array($typeOptions) ? array_merge($typeDefaultOptions, $typeOptions) : $typeDefaultOptions;

        if (! preg_match('/^[0-9]{4}-(:?0[1-9]|1[0-2])-(:?0[1-9]|[1-2][0-9]|3[0-1]) (:?[0-1][0-9]|2[0-3]):[0-5][0-9]:[0-5][0-9]$/', $paramValue))
        {
            if ($return) return false;
            APIE('InvalidParam:'.$paramName.':NotDateTime', 'Parameter <'.$paramName.'> is not a valid datetime!');
        }
        if ( ! is_null($options['Min']) && strtotime($paramValue) < strtotime($options['Min']))
        {
            if ($return) return false;
            APIE('InvalidParam:'.$paramName.':TooEarly', 'Parameter <'.$paramName.'> is too early!');
        }
        if ( ! is_null($options['Max']) && strtotime($paramValue) > strtotime($options['Max']))
        {
            if ($return) return false;
            APIE('InvalidParam:'.$paramName.':TooLate', 'Parameter <'.$paramName.'> is too late!');
        }

        if ($options['ToTimestamp']) return strtotime($paramValue);
        return $paramValue;
    }

    // 计算两个值之间的差距
    public static function diff($valueA, $valueB)
    {
        return strtotime($valueB) - strtotime($valueA);
    }
}