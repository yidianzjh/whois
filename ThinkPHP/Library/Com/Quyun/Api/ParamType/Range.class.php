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
 * 区间
 * 由两个简单类型元素组成，支持的实现了diff方法的简单类型
 */
class Range
{
    const TYPE = 'complex';   // 复杂类型

    public static function parse($paramValue, $typeOptions=null, $paramName='', $return=false)
    {
        // 默认配置
        $typeDefaultOptions = array(
            'Delimiter'     => ',',                 // 元素间的分隔符
            'Required'      => array(false, false), // 范围两端的值是否必须
            'Default'       => array(null, null),   // 范围两端的值的默认值
            'MinDistance'   => null,                // 最小允许范围的跨度值，根据元素类型不同，单位不同。Int：1，Timestamp：1秒，Date：1天，DateTime：1秒
            'MaxDistance'   => null,                // 最大允许范围的跨度值
            'ItemType'      => 'Int',               // 集合中的元素类型，支持字符串或数组定义，如：array('Int', array('min'=>0,'max'=>100))
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
        // 仅支持实现了diff方法的简单类型
        assert('method_exists("'.$typeClass.'", "diff")', 'Not allow diff method on type: "'.$typeClass.'"!');

        // 检查个数
        $items = explode($options['Delimiter'], $paramValue);
        $itemCount = count($items);
        if ($itemCount > 2)
        {
            APIE('InvalidParam:'.$paramName.':NotPair', 'Parameter <'.$paramName.'> is not a pair!');
        }

        // 检查两端是否存在
        for ($i=0; $i<2; $i++)
        {
            $item = isset($items[$i]) ? $items[$i] : $items[0];
            if ($item === '')
            {
                if ($options['Required'][$i])
                {
                    APIE('InvalidParam:'.$paramName.':MissingItem', 'Missing '.($i=0?'left':'right').' item for parameter '.$paramName.'!');
                }
                else
                {
                    // 默认值
                    $items[$i] = $options['Default'][$i];
                }
            }
        }

        // 检查元素值
        foreach ($items as $item)
        {
            if ($item === '' || is_null($item)) continue;
            if ( ! $typeClass::parse($item, $tOptions, $paramName, true))
            {
                if ($return) return false;
                APIE('InvalidParam:'.$paramName.':RangeInvalid', 'Some item is invalid in parameter '.$paramName.'!');
            }
        }

        // 只有一个元素，则左右两端值相等
        if ($itemCount == 1) $items = array($paramValue, $paramValue);

        // 比较范围两端的值的大小及距离
        $distance = $typeClass::diff($items[0], $items[1]);
        if ($distance < 0)
        {
            if ($return) return false;
            APIE('InvalidParam:'.$paramName.':RangeReversed', 'Left value should be little than right value in parameter '.$paramName.'!');
        }
        if ( ! is_null($options['MinDistance']) && $distance < $options['MinDistance'])
        {
            if ($return) return false;
            APIE('InvalidParam:'.$paramName.':RangeExceeded', 'Range specified by <'.$paramName.'> exceeds the min allowed range!');
        }
        if ( ! is_null($options['MaxDistance']) && $distance > $options['MaxDistance'])
        {
            if ($return) return false;
            APIE('InvalidParam:'.$paramName.':RangeExceeded', 'Range specified by <'.$paramName.'> exceeds the max allowed range!');
        }

        return implode($options['Delimiter'], $items);
    }
}