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
 * API输入参数处理类
 */
class Param
{
    // 根据指定规则列表创建ThinkPHP的Model查询条件
    // 单条规则格式：
    // array($dbFieldName => $paramName)
    // array($dbFieldName => array($paramName, $paramType, $default))
    // array($dbFieldName => array(
    //          'Param' => $paramName
    //          [, 'Operator' => $operator]
    // ))
    // array($dbFieldName => array(
    //          'Param' => $paramName
    //          [, 'Operator' => array($operator, $operatorOption)]
    // ))
    // array($dbFieldName => array(
    //          'Param' => array($paramName, $paramType, $default)
    //          [, 'Operator' => $operator]
    // ))
    // array($dbFieldName => array(
    //          'Param' => array($paramName, $paramType, $default)
    //          [, 'Operator' => array($operator, $operatorOption)]
    // ))
    public static function buildWhere($buildRules)
    {
        $where = array();
        foreach ($buildRules as $dbFieldName=>$rule)
        {
            if (is_string($rule) || ! isset($rule['Param']))
            {
                $rule = array('Param'=>$rule, 'Operator'=>'=');
            }
            elseif ( ! isset($rule['Operator']))
            {
                $rule['Operator'] = '=';
            }
            $exp = self::buildWhereExp($rule['Operator'], $rule['Param']);
            if ( ! is_null($exp)) $where[$dbFieldName] = $exp;
        }
        return $where;
    }

    // 根据指定规则创建ThinkPHP的Model查询条件表达式
    // $param可以为字符串形式的参数名，也可以为数组配置，格式：
    // array($paramName, $paramType, $default)
    // 数组中的三个参数分别对应于get接口的三个参数
    public static function buildWhereExp($operator, $param)
    {
        if (is_string($param))
        {
            $param = array($param, 'String', null);
        }
        $param = array_pad($param, 3, null);
        list($paramName, $paramType, $default) = $param;
        $paramValue = self::get($paramName, $paramType, $default);

        // 没有传入参数值，则不生成条件表达式
        if (is_null($paramValue)) return null;

        if (is_array($operator))
        {
            list($operatorName, $operatorOption) = $operator;
        }
        else
        {
            $operatorName = $operator;
            $operatorOption = null;
        }

        // 操作：between, in
        if (in_array($operatorName, array('Between', 'In')))
        {
            // between和in的参数必须以,为分隔符，或者是数组
            // 如果分隔符不是,，则需要特殊处理
            if (is_array($paramType))
            {
                $paramType = array_pad($paramType, 3, false);
                list($typeName, $typeOptions, $required) = $paramType;
                if ($typeName == 'Range' && is_array($typeOptions))
                {
                    if (isset($typeOptions['Delimiter']) && $typeOptions['Delimiter'] != ',')
                    {
                        $paramValue = explode($typeOptions['Delimiter'], $paramValue);
                    }
                }
            }
            if (is_string($paramValue))
            {
                $paramValue = explode(',', $paramValue);
            }

            if ($operatorName == 'Between')
            {
                // 边界条件处理，默认为'>=,<='
                $availableOptions = array('>=,<=', '>,<=', '>=,<', '>,<');
                if (is_null($operatorOption) || $operatorOption == '>=,<=' || ! in_array($operatorOption, $availableOptions))
                {
                    return array('Between', $paramValue);
                }

                $expFlags = explode(',', $operatorOption);
                $expMap = array(
                    '>=' => 'egt',
                    '<=' => 'elt',
                    '>' => 'gt',
                    '<' => 'lt',
                );
                return array(
                    array($expMap[$expFlags[0]], $paramValue[0]),
                    array($expMap[$expFlags[1]], $paramValue[1]),
                );
            }
            else // in
            {
                $paramValue = array_unique($paramValue);

                // 只有一个元素，则自动转为等于查询
                if (count($paramValue) == 1)
                {
                    return array('eq', $paramValue[0]);
                }

                return array('In', $paramValue);
            }
        }
        // 操作：like
        elseif ($operatorName == 'Like')
        {
            // 左右模糊匹配条件处理
            //$availableOptions = array('left', 'right', 'both');
            $paramValue = str_replace(array('\\', '%', '_'), array('\\\\', '\%', '\_'), $paramValue);
            if ($operatorOption == 'left') return array('like', '%'.$paramValue);
            if ($operatorOption == 'right') return array('like', $paramValue.'%');
            return array('like', '%'.$paramValue.'%');
        }

        // 其余操作
        $operatorMap = array(
            '='     => 'eq',
            '>'     => 'gt',
            '>='    => 'egt',
            '<'     => 'lt',
            '<='    => 'elt',
            'Exp'   => 'exp',
        );
        assert('isset($operatorMap["'.$operatorName.'"])', 'Unrecognized operator name!');

        return array($operatorMap[$operatorName], $paramValue);
    }

    // 根据指定规则列表创建ThinkPHP的Model数据数组
    // 单条规则格式：
    // array($dbFieldName => $paramName)
    // array($dbFieldName => array($paramName, $paramType, $default))
    public static function buildData($buildRules)
    {
        $data = array();
        foreach ($buildRules as $dbFieldName=>$param)
        {
            if (is_string($param))
            {
                $param = array($param, 'String', null);
            }
            list($paramName, $paramType, $default) = $param;
            $paramValue = self::get($paramName, $paramType, $default);   
            if ( ! is_null($paramValue)) $data[$dbFieldName] = $paramValue;
        }
        return $data;
    }

    // 根据参数配置获取输入参数的值
    // $options格式：array('参数类型', '参数类型参数[可选]', '是否必须[可选]')
    // 如：array('Int', array('Min'=>0,'Max'=>10), true)
    public static function get($paramName, $paramType='String', $default=null, $return=false)
    {
        $paramValue = I($paramName, $default);

        if (is_string($paramType))
        {
            $paramType = array($paramType);
        }

        $paramType = array_pad($paramType, 2, null);
        $paramType = array_pad($paramType, 3, false);
        list($typeName, $typeOptions, $required) = $paramType;

        // 无法识别的类型
        $typeClass = __NAMESPACE__.'\\ParamType\\'.$typeName;
        assert('class_exists("'.$typeClass.'")', 'Unrecognized parameter type: "'.$typeClass.'"!');

        // 参数非空验证
        if ($required && is_null($paramValue))
        {
            if ($return) return false;
            APIE('MissingParam:'.$paramName, 'Missing parameter '.$paramName.'!');
        }

        if ( ! is_null($paramValue))
        {
            // 参数类型分析
            return $typeClass::parse($paramValue, $typeOptions, $paramName, $return);
        }

        return $paramValue;
    }

    // 分析并检测指定类型的参数值
    public static function parse($typeName, $paramValue, $typeOptions=null, $paramName='', $return=false)
    {
        $typeClass = __NAMESPACE__.'\\ParamType\\'.$typeName;
        assert('class_exists("'.$typeClass.'")', 'Unrecognized parameter type: "'.$typeClass.'"!');

        return $typeClass::parse($paramValue, $typeOptions, $paramName, $return);
    }

    // 检测参数值是否为指定类型
    // 成功返回参数值，失败返回false
    public static function validate($typeName, $paramValue, $typeOptions=null)
    {
        return self::parse($typeName, $paramValue, $typeOptions, '', true);
    }
}