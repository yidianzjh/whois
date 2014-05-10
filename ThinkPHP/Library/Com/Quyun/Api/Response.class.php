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

use Com\Quyun\Api\Request;

/**
* API响应类（单例模式）
*/
class Response
{
    // 保存类实例的静态成员变量
    private static $_instance;

    public $code = null;        // 响应代码
    public $data = null;        // 响应数据
    public $message = null;     // 错误消息
    public $force = false;      // 是否强制输出$message属性指定的错误消息

    private $_paramReplace = null;  // 参数替换配置
    private $_returnData = null;    // 输出缓冲数据
     
    // private标记的构造方法
    private function __construct()
    {
    }
     
    // 创建__clone方法防止对象被复制克隆
    public function __clone()
    {
        trigger_error('Clone is not allow!', E_USER_ERROR);
    }

    // 单例方法, 用于访问实例的公共的静态方法
    public static function getInstance()
    {
        if (!(self::$_instance instanceof self))
        {
        	self::$_instance = new self;
        }
        return self::$_instance;
    }

    private function _getReplacedParam($paramName)
    {
        if (is_null($this->_paramReplace))
        {
            $this->_paramReplace = C('API_RESULT_PARAM_REPLACE');
            if (!is_array($this->_paramReplace)) $this->_paramReplace = array();
        }

        return isset($this->_paramReplace[$paramName]) ? $this->_paramReplace[$paramName] : $paramName;
    }

    // 输出响应数据
    public function output()
    {
        $request = Request::getInstance();
        $apiFormat = $request->getFormat();
        $apiDebug = $request->getParam('api_debug', true);

        $jsonSetting = JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE;
        if ($apiDebug || C('API_DEV_MODE'))
        {
            $jsonSetting = JSON_PRETTY_PRINT | $jsonSetting;
        }

        $resultJson = json_encode($this->getReturnData(), $jsonSetting);

        if ($apiFormat == 'json')
        {
            header("Content-Type: application/json; charset=utf-8");
            echo $resultJson;
        }
        elseif ($apiFormat == 'jsonp')
        {
            $apiCallback = $request->getParam('api_callback', true);
            if (empty($apiCallback))
            {
                $apiCallback = C('API_DEFAULT_CALLBACK');
            }

            header("Content-Type: application/x-javascript; charset=utf-8");
            echo $apiCallback.'('.$resultJson.');';
        }
    }

    // 获取错误消息
    public function getMessage()
    {
        if ($this->force) return $this->message;

        $request = Request::getInstance();
        $message = null;

        // 从语言包中获取消息
        if (C('API_LANG_PKG_ENABLED'))
        {
            $apiLang = $request->getLang();   

            // Copy from CheckLangBehavior.class.php -- start
            // 读取组件公共语言包
            $file   =  API_LIB_PATH.'Common/Lang/'.$apiLang.'.php';
            if(is_file($file))
                L(include $file);

            // 读取应用公共语言包
            $file   =  LANG_PATH.$apiLang.'.php';
            if(is_file($file))
                L(include $file);

            // 读取模块语言包
            $file   =   MODULE_PATH.'Lang/'.$apiLang.'.php';
            if(is_file($file))
                L(include $file);

            // 读取当前控制器语言包
            $file   =   MODULE_PATH.'Lang/'.$apiLang.'/'.strtolower(CONTROLLER_NAME).'.php';
            if (is_file($file))
                L(include $file);
            // Copy from CheckLangBehavior.class.php -- end

            $message = L($this->code);
            if (strcasecmp($message, $this->code) == 0)
            {
                // 如果只是简单的大写，说明语言包中不存在该项
                $message = null;
            }
        }

        // 自动生成消息
        if (is_null($message) && C('API_LANG_AUTOGEN'))
        {
            $apiLang = $request->getLang();

            $rules = array();

            // 读取类库公共语言规则包
            $file   =  API_LIB_PATH.'Common/Lang/'.$apiLang.'_rules.php';
            if(is_file($file))
                $rules = array_merge($rules, include $file);

            // 读取应用公共语言规则包
            $file   =  LANG_PATH.$apiLang.'_rules.php';
            if(is_file($file))
                $rules = array_merge($rules, include $file);

            // 读取模块语言规则包
            $file   =   MODULE_PATH.'Lang/'.$apiLang.'_rules.php';
            if(is_file($file))
                $rules = array_merge($rules, include $file);

            // 读取当前控制器语言规则包
            $file   =   MODULE_PATH.'Lang/'.$apiLang.'/'.strtolower(CONTROLLER_NAME).'_rules.php';
            if (is_file($file))
                $rules = array_merge($rules, include $file);

            $words = array();

            // 读取类库公共语言词汇包
            $file   =  API_LIB_PATH.'Common/Lang/'.$apiLang.'_words.php';
            if(is_file($file))
                $words = array_merge($words, include $file);

            // 读取应用公共语言词汇包
            $file   =  LANG_PATH.$apiLang.'_words.php';
            if(is_file($file))
                $words = array_merge($words, include $file);

            // 读取模块语言词汇包
            $file   =   MODULE_PATH.'Lang/'.$apiLang.'_words.php';
            if(is_file($file))
                $words = array_merge($words, include $file);

            // 读取当前控制器语言词汇包
            $file   =   MODULE_PATH.'Lang/'.$apiLang.'/'.strtolower(CONTROLLER_NAME).'_words.php';
            if (is_file($file))
                $words = array_merge($words, include $file);

            // 分析错误码
            $codeFields = explode(':', $this->code);
            $codeFieldCount = count($codeFields);

            // 遍历查找符合条件的规则
            $srcMatches = array();
            foreach ($rules as $src=>$dest)
            {
                if (strpos($src, $codeFields[0]) !== 0) continue;

                $srcFields = explode(':', $src);
                if (count($srcFields) != $codeFieldCount) continue;

                // 按字段检测是否符合条件，并找出错误码中匹配通配符字段的值
                $matched = true;
                foreach ($srcFields as $i=>$field)
                {
                    if ($field == $codeFields[$i]) continue;
                    if ($field == '*')
                    {
                        $srcMatches[] = $codeFields[$i];
                        continue;
                    }

                    $matched = false;
                    break;
                }
                if ( ! $matched) continue;

                // 替换目标中的占位符
                $message = $dest;
                // 匹配{1}或{1:或}
                if (preg_match_all('/\{(\d)(?:\:([^\}]+))?\}/', $dest, $matches))
                {
                    list($flagMatches, $indexMatches, $orMatches) = $matches;
                    foreach ($flagMatches as $i=>$match)
                    {
                        $index = $indexMatches[$i];
                        $value = $srcMatches[$index-1];
                        $valueFields = explode('|', $value);
                        foreach ($valueFields as $j=>$field)
                        {
                            if (isset($words[$field])) $valueFields[$j] = $words[$field];
                        }
                        $value = implode($orMatches[$i], $valueFields);
                        $message = str_replace($match, $value, $message);
                    }
                }
                break;
            }

        }

        if (is_null($message)) $message = $this->message;

        return $message;
    }

    // 获取响应输出缓存数据
    public function getReturnData()
    {
        if ( ! is_null($this->_returnData)) return $this->_returnData;

        $request = Request::getInstance();

        $result = array();
        if (C('API_RESULT_HAS_REQUESTID'))
        {
            $result[$this->_getReplacedParam('REQUESTID')] = $request->getRequestId();
        }
        if (C('API_RESULT_HAS_ACTION'))
        {
            $result[$this->_getReplacedParam('ACTION')] = $request->getParam('api_action', true);
        }
        $result[$this->_getReplacedParam('CODE')] = $this->code;
        if ($this->code == C('API_SUCCESS_CODE'))
        {
            $result[$this->_getReplacedParam('DATA')] = $this->data;
        }
        else
        {
            $result[$this->_getReplacedParam('MESSAGE')] = $this->getMessage();
        }

        return $this->_returnData = $result;
    }
}
