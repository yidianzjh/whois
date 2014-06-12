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
 * 整数
 */
class Int
{
    const TYPE = 'simple';   // 简单类型

    public static function parse($paramValue, $typeOptions=null, $paramName='', $return=false)
    {
        // 默认配置
        $typeDefaultOptions = array(
            'Min'   => 0,       // 最小值，null表示不限制
            'Max'   => null,    // 最大值，null表示不限制
        );
        $options = is_array($typeOptions) ? array_merge($typeDefaultOptions, $typeOptions) : $typeDefaultOptions;

        if (filter_var($paramValue, FILTER_VALIDATE_INT) === false)
        {
            if ($return) return false;
            APIE('InvalidParam:'.$paramName.':NotInt', 'Parameter <'.$paramName.'> is not an integer!');
        }
        if ( ! is_null($options['Min']) && $paramValue < $options['Min'])
        {
            if ($return) return false;
            APIE('InvalidParam:'.$paramName.':TooSmall', 'Parameter <'.$paramName.'> is too small!');
        }
        if ( ! is_null($options['Max']) && $paramValue > $options['Max'])
        {
            if ($return) return false;
            APIE('InvalidParam:'.$paramName.':TooLarge', 'Parameter <'.$paramName.'> is too large!');
        }

        return $paramValue;
    }

    // 计算两个值之间的差距
    public static function diff($valueA, $valueB)
    {
        return $valueB - $valueA;
    }
}