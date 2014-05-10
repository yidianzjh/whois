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

use Com\Quyun\Api\Controller;

class ActionController extends Controller
{
    public function listAction()
    {
        return array_unique(array_merge($this->_getAppActions(), $this->_getExtActions()));
    }

    // 判断是否API基类的子类
    private function _isApi($class)
    {
        $pClass = $class;
        while ($pClass = $pClass->getParentClass())
        {
            if ($pClass->getName() == __NAMESPACE__)
            {
                return true;
            }
        }

        return false;
    }

    // 获取应用中定义的接口名
    private function _getAppActions()
    {
        $actions = array();

        // 查找模块
        $appDir = dir(APP_PATH);
        while (($mName = $appDir->read()) !== false)
        {
            if (in_array($mName, array('.', '..', 'Common'))) continue;

            $mPath = APP_PATH.$mName;
            if ( ! is_dir($mPath)) continue;

            // 检测Runtime目录是否是运行时目录
            if ($mName == 'Runtime' && realpath(RUNTIME_PATH) == realpath($mPath)) continue;

            // 查找控制器
            $cLayer = C('DEFAULT_C_LAYER');
            $aSuffix = C('ACTION_SUFFIX');
            $cDirPath = $mPath.'/'.$cLayer;
            if ( ! is_dir($cDirPath)) continue;
            $cDir = dir($cDirPath);
            $cFileNameSuffix = $cLayer.'.class.php';

            while (($cFileName = $cDir->read()) !== false)
            {
                if (in_array($cFileName, array('.', '..'))) continue;
                if (substr($cFileName, -strlen($cFileNameSuffix)) !== $cFileNameSuffix) continue;
                $cName = substr($cFileName, 0, -strlen($cFileNameSuffix));
                $cPath = $mPath.'/'.$cLayer.'/'.$cFileName;

                // 反射控制器类
                try
                {
                    $class = new \ReflectionClass($mName.'\\'.$cLayer.'\\'.$cName.$cLayer);
                }
                catch (\ReflectionException $e)
                {
                    continue;
                }

                if ( ! $this->_isApi($class)) continue;

                // 查找action
                $methods = $class->getMethods(\ReflectionMethod::IS_PUBLIC);
                foreach ($methods as $method)
                {
                    $methodName = $method->getName();
                    if (substr($methodName, -strlen($aSuffix)) !== $aSuffix) continue;
                    $aName = substr($methodName, 0, -strlen($aSuffix));

                    $destAction = $mName.'.'.$cName.'.'.$aName;
                    $actions[] = $this->request->reverseAction($destAction);
                }
            }
        }

        return $actions;
    }

    // 获取扩展中定义的接口名
    // 仅返回在接口名映射规则中有指定的接口名
    private function _getExtActions()
    {
        $actions = array();

        // 查找映射规则目标中涉及到的扩展模块、控制器、操作
        $extPaths = array();
        $mapRules = $this->request->getActionMapRules();
        foreach ($mapRules as $rule)
        {
            list($destModule, $destController, $destAction) = $rule['dest'];
            if ( ! strpos($destModule, '://')) continue;

            if (preg_match('/^\\\\([1-3])$/', $destController)) $destController = '*';
            if (preg_match('/^\\\\([1-3])$/', $destAction)) $destAction = '*';

            list($cLayer, $aSuffix) = $rule['ext'];
            if ( ! $cLayer) $cLayer = C('DEFAULT_C_LAYER');
            if ( ! $aSuffix) $aSuffix = C('ACTION_SUFFIX');

            $extPaths[] = array(
                'm' => $destModule,
                'c' => $destController,
                'a' => $destAction,
                'ext' => array($cLayer, $aSuffix),
            );
        }

        // 在扩展模块中查找
        foreach ($extPaths as $extPath)
        {
            // 查找action
            $mName = $extPath['m'];
            $cName = $extPath['c'];
            $aName = $extPath['a'];
            list($cLayer, $aSuffix) = $extPath['ext'];

            $cNames = array();
            if ($cName == '*')
            {
                // 在LIB_PATH下查找模块
                $mPath = LIB_PATH.str_replace(array('://', '\\'), '/', $mName);
                if ( ! is_dir($mPath)) continue;

                // 查找控制器
                $cDirPath = $mPath.'/'.$cLayer;
                if ( ! is_dir($cDirPath)) continue;
                $cDir = dir($cDirPath);
                $cFileNameSuffix = $cLayer.'.class.php';
                while (($cFileName = $cDir->read()) !== false)
                {
                    if (in_array($cFileName, array('.', '..'))) continue;
                    if (substr($cFileName, -strlen($cFileNameSuffix)) !== $cFileNameSuffix) continue;
                    $cNames[] = substr($cFileName, 0, -strlen($cFileNameSuffix));
                }
            }
            else
            {
                $cNames[] = $cName;
            }

            foreach ($cNames as $cName)
            {
                try
                {
                    $className = str_replace('://', '\\', $mName).'\\'.$cLayer.'\\'.$cName.$cLayer;
                    $class = new \ReflectionClass($className);
                }
                catch (\ReflectionException $e)
                {
                    continue;
                }

                if ( ! $this->_isApi($class)) continue;

                $methods = $class->getMethods(\ReflectionMethod::IS_PUBLIC);
                foreach ($methods as $method)
                {
                    $methodName = $method->getName();
                    if (substr($methodName, -strlen($aSuffix)) !== $aSuffix) continue;
                    $aName = substr($methodName, 0, -strlen($aSuffix));
                    if ($extPath['a'] != '*' && $aName != $extPath['a']) continue;

                    $destAction = $mName.'.'.$cName.'.'.$aName;
                    $actions[] = $this->request->reverseAction($destAction);
                }
            }
        }

        return $actions;
    }
}