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
 * 集合
 * 由简单类型元素组成的集合
 */
class Set
{
    const TYPE = 'complex';   // 复杂类型

    public static function parse($paramValue, $typeOptions=null, $paramName='', $return=false)
    {
        // 默认配置
        $typeDefaultOptions = array(
            'MinCount'  => 0,          // 集合中最少允许的元素数量，null表示不限制
            'MaxCount'  => null,       // 集合中最多允许的元素数量，null表示不限制
            'Delimiter' => ',',        // 元素间的分隔符
            'ItemType'  => 'String',   // 集合中的元素类型，支持字符串或数组定义，如：array('String', array('MinLength'=>6))
        );
        $options = is_array($typeOptions) ? array_merge($typeDefaultOptions, $typeOptions) : $typeDefaultOptions;

        // 元素类型检查
        $typeDefine = $options['ItemType'];
        if (is_string($typeDefine))
        {
            $typeDefine = array($typeDefine, null);
        }
        list($tName, $tOptions) = $typeDefine;

        // 无法识别的类型
        $typeClass = __NAMESPACE__.'\\'.$tName;
        assert('class_exists("'.$typeClass.'")', 'Unrecognized parameter type: "'.$typeClass.'"!');
        // 成员必须为简单类型
        assert('$typeClass::TYPE === "simple"', $typeClass.' is not a simple type!');

        // 检查个数限制
        $items = explode($options['Delimiter'], $paramValue);
        $itemCount = count($items);
        if ( ! is_null($options['MinCount']) && $itemCount < $options['MinCount'])
        {
            APIE('InvalidParam:'.$paramName.':TooFew', 'Items in parameter <'.$paramName.'> set is too few!');
        }
        if ( ! is_null($options['MaxCount']) && $itemCount > $options['MaxCount'])
        {
            APIE('InvalidParam:'.$paramName.':TooMany', 'Items in parameter <'.$paramName.'> set is too many!');
        }

        // 检查元素值
        foreach ($items as $item)
        {
            if ( ! $typeClass::parse($item, $tOptions, $paramName, true))
            {
                if ($return) return false;
                APIE('InvalidParam:'.$paramName.':ItemInvalid', 'Some item is invalid in parameter '.$paramName.'!');
            }
        }

        return $paramValue;
    }
}