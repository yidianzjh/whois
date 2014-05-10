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
 * 数字字符串
 */
class DigitString
{
    const TYPE = 'simple';   // 简单类型

    public static function parse($paramValue, $typeOptions=null, $paramName='', $return=false)
    {
        // 默认配置
        $typeDefaultOptions = array(
            'MinLength'     => 0,       // 长度最小值，null表示不限制
            'MaxLength'     => null,    // 长度最大值，null表示不限制
        );
        $options = is_array($typeOptions) ? array_merge($typeDefaultOptions, $typeOptions) : $typeDefaultOptions;

        $len = strlen($paramValue);
        if ( ! is_null($options['MinLength']) && $len < $options['MinLength'])
        {
            if ($return) return false;
            APIE('InvalidParam:'.$paramName.':TooShort', 'Parameter <'.$paramName.'> is too short!');
        }
        if ( ! is_null($options['MaxLength']) && $len > $options['MaxLength'])
        {
            if ($return) return false;
            APIE('InvalidParam:'.$paramName.':TooLong', 'Parameter <'.$paramName.'> is too long!');
        }
        if ( ! preg_match('/^\d+$/', $paramValue))
        {
            if ($return) return false;
            APIE('InvalidParam:'.$paramName.':NotDigitString', 'Parameter <'.$paramName.'> is not a digital string!');
        }

        return $paramValue;
    }
}