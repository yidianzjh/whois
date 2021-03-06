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
 * 电子邮箱
 */
class Email
{
    const TYPE = 'simple';   // 简单类型

    public static function parse($paramValue, $typeOptions=null, $paramName='', $return=false)
    {
        // 默认配置
        $typeDefaultOptions = array(
            'MinLength'  => 0,       // 长度最小值，null表示不限制
            'MaxLength'     => null,    // 长度最大值，null表示不限制
        );
        $options = is_array($typeOptions) ? array_merge($typeDefaultOptions, $typeOptions) : $typeDefaultOptions;

        $regex = "/^([\w\!\#$\%\&\'\*\+\-\/\=\?\^\`{\|\}\~]+\.)*[\w\!\#$\%\&\'\*\+\-\/\=\?\^\`{\|\}\~]+@((((([a-z0-9]{1}[a-z0-9\-]{0,62}[a-z0-9]{1})|[a-z0-9])\.)+[a-z]{2,6})|(\d{1,3}\.){3}\d{1,3}(\:\d{1,5})?)$/i";
        if ( ! preg_match($regex, $paramValue))
        {
            APIE('InvalidParam:'.$paramName.':NotEmail', 'Parameter <'.$paramName.'> is not a email!');
        }

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

        return $paramValue;
    }
}