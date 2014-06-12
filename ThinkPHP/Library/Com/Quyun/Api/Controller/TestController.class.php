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
namespace Com\Quyun\Api\Controller;

use Com\Quyun\Api\Controller,
    Com\Quyun\Api\Param;

class TestController extends Controller
{
    // 判断是否测试基类的子类
    private function _isTest($class)
    {
        $pClass = $class;
        while ($pClass = $pClass->getParentClass())
        {
            if ($pClass->getName() == 'Com\\Quyun\\Api\\TestRunner')
            {
                return true;
            }
        }

        return false;
    }

    // 获取可测试的接口列表
    public function listActionAction()
    {
        $actions = array();

        // 查找测试模块下的模块目录
        $testModule = C('API_TEST_MODULE');
        $testPath = APP_PATH.$testModule;
        if ( ! is_dir($testPath)) return $actions;
        $testDir = dir($testPath);
        while (($mName = $testDir->read()) !== false)
        {
            if (in_array($mName, array('.', '..'))) continue;
            $mPath = $testPath.'/'.$mName;
            if ( ! is_dir($mPath)) continue;

            // 查找控制器目录
            $mDir = dir($mPath);
            while (($cName = $mDir->read()) !== false)
            {
                if (in_array($cName, array('.', '..'))) continue;
                $cPath = $mPath.'/'.$cName;
                if ( ! is_dir($cPath)) continue;

                // 查找接口测试类
                $cDir = dir($cPath);
                $aSuffix = C('API_TEST_SUFFIX');
                $aFileNameSuffix = $aSuffix.'.class.php';
                while (($aFileName = $cDir->read()) !== false)
                {
                    if (in_array($aFileName, array('.', '..'))) continue;
                    if (substr($aFileName, -strlen($aFileNameSuffix)) !== $aFileNameSuffix) continue;
                    $aName = substr($aFileName, 0, -strlen($aFileNameSuffix));

                    // 反射接口测试类
                    try
                    {
                        $class = new \ReflectionClass($testModule.'\\'.$mName.'\\'.$cName.'\\'.$aName.$aSuffix);
                    }
                    catch (\ReflectionException $e)
                    {
                        continue;
                    }

                    if ( ! $this->_isTest($class)) continue;

                    $actionName = $mName.'.'.$cName.'.'.$aName;
                    $actions[] = $this->request->reverseAction($actionName);
                }
            }
        }

        return $actions;
    }

    // 生成指定接口的测试用例
    // 返回用例格式：
    // array(
    //     array(
    //         'type' => 'rule'|'define', // 由规则产生或自定义的
    //         'params' => array(  // 该用例下的参数值
    //             '{ParamName1}' => '{ParamValue1}',
    //             ...
    //         ),
    //         'expect' => true|false, // 该用例预期接口调用结果
    //     )
    // )
    public function genCasesAction()
    {
        $action = Param::get('Action', array('String', null, true));
        $destAction = $this->request->mapAction($action);
        $className = C('API_TEST_MODULE').'\\'.str_replace('.', '\\', $destAction).C('API_TEST_SUFFIX');

        if ( ! class_exists($className))
        {
            APIE('ObjectNotExist:Action', 'Action does not exist!');
        }

        // 一、根据用例规则生成用例
        $a = new $className;
        $testCases = array();
        $paramRules = $a->getCaseRules();

        // 检查配置合法性
        $this->checkRules($paramRules);

        // 逐个参数进行测试
        // 每个参数取一个有效值、一个无效值、一个空值进行测试，测试时保持其它参数值为合法
        foreach ($paramRules as $paramName=>$paramRule)
        {
            foreach (array('valid', 'invalid') as $caseType)
            {
                // 随机取参数值
                $paramValue = $paramRule[$caseType][array_rand($paramRule[$caseType])];
                $expect = ($caseType == 'valid') ? true : false;

                // 随机生成用例
                $testCases[] = array(
                    'type'   => 'rule',
                    'params' => $this->genParams($paramRules, $expect, array($paramName, $paramValue)),
                    'expect' => $expect,
                );
            }
        }

        // 二、获取自定义用例
        $cases = $a->getCases();

        // 检查用例合法性
        $this->checkCases($cases);
        foreach ($cases as $case)
        {
            $testCases[] = array(
                'type' => 'define',
                'params' => $case['params'],
                'expect' => $case['expect'],
            );
        }

        return $testCases;
    }

    private function apie($message)
    {
        APIE('InternalError:TestRuleError', $message, true);
    }

    // 根据用例规则，随机生成用例参数
    private function genParams($paramRules, $expect, $condition=null)
    {
        if ( ! is_null($condition))
        {
            list($condParamName, $condParamValue) = $condition;
            $caseParams = array($condParamName => $condParamValue);
        }
        else
        {
            $condParamName = $condParamValue = null;
            $caseParams = array();
        }

        foreach ($paramRules as $paramName=>$paramRule)
        {
            if ($paramName !== $condParamName)
            {
                $paramValue = $paramRule['valid'][array_rand($paramRule['valid'])];
                $caseParams[$paramName] = $paramValue;
            }

            if (isset($paramRule['switch']))
            {
                $switchCases = $paramRule['switch'];

                if ($expect)
                {
                    // 如果当前用例测试的参数值为正确的，则按该参数值生成其它参数的正确值
                    foreach ($switchCases as $i=>$switchCase)
                    {
                        if ( ! in_array($condParamValue, $switchCase['case'])) continue;

                        // 找到case分支
                        $caseIndex = $i;
                    }
                }
                else
                {
                    // 如果当前用例测试的参数值为错误的，则随机产生其它参数的正确值
                    $caseIndex = array_rand($switchCases);
                }

                $caseParamRules = $switchCases[$caseIndex]['params'];
                $caseParams = array_merge($caseParams, $this->genParams($caseParamRules, true));
            }
        }
        return $caseParams;
    }

    // 检查用例规则配置合法性
    // 1. 键值对配置格式检测
    // 2. 检查分支结构的条件值是否完整
    // 3. 检查参数是否重复
    // 标准参数规则定义：
    // array(
    //     '{ParamName1}' => array(
    //         'require'   => true|false,
    //         'valid'     => array('{ValidValue1}', '{ValidValue2}', ...),
    //         'invalid'   => array('{InvalidValue1}', '{InalidValue2}', ...),
    //         ['switch'    => array(
    //             array(
    //                 'case'    => array('{ValidValue1}', ...)|'default',
    //                 'params'  => array(
    //                     '{ParamNameX}' => {Rule of ParamNameX},
    //                     ...
    //                 ),
    //             ),
    //             ...
    //         ),]
    //     ),
    //     '{ParamName2}' => {Rule of ParamName2},
    //     ...
    // )
    private function checkRules($paramRules, $excludeParamNames=array())
    {
        if ( ! is_array($paramRules)) $this->apie('getCaseRules() must return an array!');

        foreach ($paramRules as $paramName=>$paramRule)
        {
            // 检测参数是否重复
            if (in_array($paramName, $excludeParamNames)) $this->apie("Parameter '{$paramName}' is duplicated!");
            $excludeParamNames[] = $paramName;

            if ( ! is_array($paramRule)) $this->apie("Rule of parameter '{$paramName}' must be an array!");
            if ( ! isset($paramRule['require'])) $this->apie("Missing 'require' in rule of parameter '{$paramName}'!");
            if ( ! isset($paramRule['valid'])) $this->apie("Missing 'valid' in rule of parameter '{$paramName}'!");
            if ( ! isset($paramRule['invalid'])) $this->apie("Missing 'invalid' in rule of parameter '{$paramName}'!");

            if (count($paramRule['valid']) == 0)  $this->apie("No items in 'valid' in rule of parameter '{$paramName}'!");
            if (count($paramRule['invalid']) == 0)  $this->apie("No items in 'invalid' in rule of parameter '{$paramName}'!");

            foreach ($paramRule as $keyName=>$keyValue)
            {
                if ( ! in_array($keyName, array('require', 'valid', 'invalid', 'switch')))
                    $this->apie("Unrecognized key name '{$keyName}' in rule of parameter '{$paramName}'!");
            }

            // require
            if ( ! is_bool($paramRule['require']))
                $this->apie("Unrecognized value of 'require' in rule of parameter '{$paramName}'!");

            // valid
            if ( ! is_array($paramRule['valid']))
                $this->apie("Value of 'valid' in rule of parameter '{$paramName}' must be an array!");
            foreach ($paramRule['valid'] as $value)
            {
                if ( ! is_string($value))
                    $this->apie("Items in 'valid' in rule of parameter '{$paramName}' must be string!");
            }
            if (count($paramRule['valid']) != count(array_unique($paramRule['valid'])))
                $this->apie("One or more items in 'valid' in rule of parameter '{$paramName}' are duplicated!");

            // invalid
            if ( ! is_array($paramRule['invalid']))
                $this->apie("Value of 'invalid' in rule of parameter '{$paramName}' must be an array!");
            foreach ($paramRule['invalid'] as $value)
            {
                if ( ! is_string($value)) $this->apie("Items in 'invalid' in rule of parameter '{$paramName}' must be string!");
            }
            if (count($paramRule['invalid']) != count(array_unique($paramRule['invalid'])))
                $this->apie("One or more items in 'invalid' in rule of parameter '{$paramName}' are duplicated!");

            // switch
            if (isset($paramRule['switch']))
            {
                $caseValues = array();
                $validValues = $paramRule['valid'];
                $hasDefaultCase = false;

                if ( ! is_array($paramRule['switch']))
                    $this->apie("Value of '{$keyName}' in rule of parameter '{$paramName}' must be an array!");
                foreach ($paramRule['switch'] as $keyValueItem)
                {
                    if ( ! is_array($keyValueItem))
                        $this->apie("Items in '{$keyName}' in rule of parameter '{$paramName}' must be array!");
                    foreach ($keyValueItem as $switchKey=>$switchValue)
                    {
                        switch ($switchKey)
                        {
                            case 'case':
                                if ($switchValue != 'default')
                                {
                                    if ( ! is_array($switchValue))
                                        $this->apie("Value of 'switch'->'case' in rule of parameter '{$paramName}' must be an array or string 'default'!");
                                    foreach ($switchValue as $switchValueItem)
                                    {
                                        if ( ! is_string($switchValueItem))
                                            $this->apie("Items in 'switch'->'case' in rule of parameter '{$paramName}' must be string!");
                                        if ( ! in_array($switchValueItem, $validValues))    // case中的值在valid值中不存在
                                            $this->apie("Item '{$switchValueItem}' in 'switch'->'case' does not appear in 'valid' items of parameter '{$paramName}'!");
                                        if (in_array($switchValueItem, $caseValues))     // case中的值在其它case中已存在
                                            $this->apie("Item '{$switchValueItem}' in 'switch'->'case' is duplicated with other 'case' in rule of parameter '{$paramName}'!");
                                        $caseValues[] = $switchValueItem;
                                    }
                                }
                                else
                                {
                                    $hasDefaultCase = true;
                                }
                                break;
                            case 'params':
                                $this->checkRules($switchValue, $excludeParamNames);
                                break;
                            default:
                                $this->apie("Unrecognized key name '{$switchKey}' in 'switch' rule of parameter '{$paramName}'!");
                        }
                    }
                }

                // 检查分支结构的条件值是否完整
                if ( ! $hasDefaultCase)
                {
                    if (count($validValues) != count($caseValues))
                        $this->apie("Valid values of parameter '{$paramName}' are not covered completely in 'switch'!");
                }
            }
        }
    }

    // 检查用例合法性
    private function checkCases($cases)
    {
        if ( ! is_array($cases)) $this->apie('getCases() must return an array!');

        foreach ($cases as $i=>$case)
        {
            if ( ! isset($case['params'])) $this->apie("Missing 'params' in case {$i} returned by getCases()!");
            if ( ! isset($case['expect'])) $this->apie("Missing 'expect' in case {$i} returned by getCases()!");
            if ( ! is_bool($case['expect']))
                $this->apie("Unrecognized value of 'expect' in case {$i} returned by getCases()!");
        }
    }
}