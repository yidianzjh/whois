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
* API请求类（单例模式）
*/
class Request
{
    // 保存类实例的静态成员变量
    private static $_instance;

    // 保存请求数据
    private $_params = array();

    // 合法的接口名映射规则
    private $_actionMap = null;

    // 映射后的接口名
    private $_mappedAction = null;

    // 请求ID
    private $_requestId = null;

    // 返回数据格式
    private $_format = null;

    // 错误消息语言
    private $_lang = null;

    // 请求时间
    private $_requestTime = null;
     
    // private标记的构造方法
    private function __construct()
    {
        $this->parse();
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

    // 分析请求数据
    private function parse()
    {
        $methods = explode(',', C('API_HTTP_METHOD'));
        $methods = array_map('strtolower', array_map('trim', $methods));
        $methods = array_intersect($methods, array('get', 'post'));

        foreach ($methods as $method)
        {
            $this->_params = array_merge($this->_params, I($method.'.'));
        }
    }

    // 获取请求数据中的参数值
    // 如果$rawParam为false，则获取被替换后的参数名对应的参数值
    public function getParam($paramName, $rawParam=true)
    {
        if ($rawParam)
            return isset($this->_params[$paramName]) ? $this->_params[$paramName] : null;
        else
            return $this->getParam($this->getReplacedParamName($paramName));
    }

    // 获取请求数据中的所有参数值
    public function getParams($rawParam=true)
    {
        if ($rawParam) return $this->_params;

        $params = array();
        foreach ($this->_params as $paramName=>$value)
        {
            $params[$paramName] = $this->getParam($paramName, false);
        }
        return $params;
    }

    // 获取被替换后的参数名
    public function getReplacedParamName($paramName)
    {
        $paramReplace = C('API_REQUEST_PARAM_REPLACE');

        if (is_array($paramReplace) && isset($paramReplace[$paramName]))
        {
            $actionParamName = $paramReplace[$paramName];
        }
        else
        {
            $actionParamName = $paramName;
        }

        return $actionParamName;
    }

    // 分析接口名中的字段列表
    public function parseAction($actionName)
    {
        if (is_null($actionName)) return false;

        // action合法性检测
        $separator = C('API_ACTION_SEPARATOR');
        if ($separator != '')   // 分隔符为非空
        {
            // 字段数必须为1~3个
            $fieldCount = substr_count($actionName, $separator) + 1;
            if ($fieldCount > 3) return false;

            // 字段名由字母或下划线开头，后续由大小写字母、数字和下划线组成
            if ( ! preg_match('/^([_A-Za-z][_A-Za-z0-9]*)(?:'.preg_quote($separator, '/').'([_A-Za-z][_A-Za-z0-9]*))?(?:'.preg_quote($separator, '/').'([_A-Za-z][_A-Za-z0-9]*))?$/', $actionName, $fields))
            {
                return false;
            }
        }
        else // 分隔符为空
        {
            // 字段名由一个或多个大写字母开头，后续由小写字母、数字和下划线组成
            if ( ! preg_match('/^([A-Z]+[_a-z0-9]*)(?:'.preg_quote($separator, '/').'([A-Z]+[_a-z0-9]*))?(?:'.preg_quote($separator, '/').'([A-Z]+[_a-z0-9]*))?$/', $actionName, $fields))
            {
                return false;
            }
        }

        array_shift($fields);

        return $fields;
    }

    // 获取有效的接口名映射规则列表。
    // 分析接口名映射规则，将不合法的规则过滤掉
    public function getActionMapRules()
    {
        if ( ! is_null($this->_actionMap)) return $this->_actionMap;

        $separator = C('API_ACTION_SEPARATOR');
        $actionMap = C('API_ACTION_MAP');

        if ( ! is_array($actionMap))
        {
            $this->_actionMap = array();
            return $this->_actionMap;
        }

        foreach ($actionMap as $srcRule=>$destRule)
        {
            // 过滤非法规则
            if ($separator != '')
            {
                // 规则中的源接口名只能由1~3个字段构成
                $srcRuleFields = explode($separator, $srcRule);
                $srcRuleFieldCount = count($srcRuleFields);
                if ($srcRuleFieldCount > 3) continue;

                // 源接口名中的字段只能由字母或下划线开头，后续由大小写字母、数字和下划线组成，或为*
                $srcRuleValided = true;
                foreach ($srcRuleFields as $field)
                {
                    if ($field != '*' && ! preg_match('/^([_A-Za-z][_A-Za-z0-9]*)$/', $field))
                    {
                        $srcRuleValided = false;
                        break;
                    }
                }
                if ( ! $srcRuleValided) continue;
            }
            else
            {
                // 过滤非法规则
                // 源接口名只能由1~3个字段构成
                // 源接口名中的字段只能由一个或多个大写字母开头，后续由小写字母、数字和下划线组成，或为*
                if ( ! preg_match('/^([A-Z]+[_a-z0-9]*|\*)([A-Z]+[_a-z0-9]*|\*)?([A-Z]+[_a-z0-9]*|\*)?$/', $srcRule, $fields)) continue;
                array_shift($fields);
                $srcRuleFields = $fields;
            }

            // 如果目标接口名为缩写形式，则源接口名的通配符数量与目标接口名的字段数量总和必须为3
            $cLayer = $aSuffix = '';
            if (strpos($destRule, ','))
            {
                list($destRule, $extConfig) = explode(',', $destRule, 2);
                if (strpos($extConfig, ','))
                {
                    list($cLayer, $aSuffix) = explode(',', $extConfig, 2);
                }
                else
                {
                    $cLayer = $extConfig;
                }
            }
            $destRuleFields = explode('.', $destRule);
            $destRuleFieldCount = count($destRuleFields);
            if ($destRuleFieldCount != 3)
            {
                $wildcardFieldCount = array_count_values($srcRuleFields)['*'];
                if ($wildcardFieldCount + $destRuleFieldCount != 3) continue;

                // 补齐目标action
                for ($i=1; $i<=$wildcardFieldCount; $i++)
                {
                    $destRuleFields[] = '\\'.$i;
                }
            }

            // 每个\N只能使用一次
            $destN = array();
            $destNDumplicated = false;
            foreach ($destRuleFields as $i=>$field)
            {
                if (preg_match('/^\\\\([1-3])$/', $field, $matches))
                {
                    $n = $matches[1];
                    if (isset($destN[$n]))
                    {
                        $destNDumplicated = true;
                        break;
                    }
                    $destN[$n] = true;
                }
            }
            if ($destNDumplicated) continue;

            $this->_actionMap[] = array(
                'src' => $srcRuleFields,
                'dest' => $destRuleFields,
                'ext' => array($cLayer, $aSuffix),
            );
        }

        return $this->_actionMap;
    }

    // 映射接口名
    public function mapAction($actionName)
    {
        $actionFields = $this->parseAction($actionName);
        if ($actionFields === false) return false;

        // 接口名映射转换
        $actionMap = $this->getActionMapRules();
        $actionFieldCount = count($actionFields);
        $separator = C('API_ACTION_SEPARATOR');
        if (count($actionMap) == 0)
        {
            // 若未定义映射规则，则请求的字段数必须是3个
            if ($actionFieldCount != 3) return false;
        }
        else
        {
            // 查找符合条件的映射关系
            foreach ($actionMap as $map)
            {
                // 逐个字段与请求字段匹配
                $srcRuleFields = $map['src'];
                if (count($srcRuleFields) != $actionFieldCount) continue;

                $srcRuleMatched = true;
                $matchedN = array();
                $n = 1;
                foreach ($srcRuleFields as $i=>$field)
                {
                    if ($field != '*')  // 普通字段名，完全匹配
                    {
                        if ($field != $actionFields[$i])
                        {
                            $srcRuleMatched = false;
                            break;
                        }
                    }
                    else    // 通配符
                    {
                        if ($separator != '')
                        {
                            if ( ! preg_match('/^[_A-Za-z][_A-Za-z0-9]*$/', $actionFields[$i]))
                            {
                                $srcRuleMatched = false;
                                break;
                            }
                        }
                        else
                        {
                            if ( ! preg_match('/^[A-Z]+[_a-z0-9]*$/', $actionFields[$i]))
                            {
                                $srcRuleMatched = false;
                                break;
                            }
                        }
                        $matchedN[$n++] = $actionFields[$i];
                    }
                }

                if ($srcRuleMatched)
                {
                    // 替换\N为匹配值
                    $destRuleFields = $map['dest'];
                    foreach ($destRuleFields as $i=>$field)
                    {
                        if (preg_match('/^\\\\([1-3])$/', $field, $matches))
                        {
                            $n = (int)$matches[1];
                            $destRuleFields[$i] = $matchedN[$n];
                        }
                    }
                    return implode('.', $destRuleFields);
                }
            }
        }

        // 未匹配到映射规则，则按默认接口映射返回
        if ($actionFieldCount != 3) return false;

        // 映射后只能使用映射后的接口名，而原有接口名不再能使用。
        // 此处判断是否该操作名是否被其它源接口名匹配
        $destActionName = implode('.', $actionFields);
        if ($this->reverseAction($destActionName) !== implode($separator, $actionFields)) return false;

        return $destActionName;
    }

    // 反向映射接口名
    public function reverseAction($destActionName)
    {
        $actionMap = $this->getActionMapRules();
        $actionFields = explode('.', $destActionName);
        
        if (count($actionMap) > 0)
        {
            foreach ($actionMap as $map)
            {
                // 逐个字段比对目标规则与请求字段是否匹配
                $destRuleFields = $map['dest'];
                $destRuleMatched = true;
                $matchedN = array();
                foreach ($destRuleFields as $di=>$field)
                {
                    if (preg_match('/^\\\\([1-3])$/', $field, $matches))
                    {
                        $n = (int)$matches[1];
                        $matchedN[$n] = $actionFields[$di];
                    }
                    else
                    {
                        if ($field !== $actionFields[$di])
                        {
                            $destRuleMatched = false;
                            break;
                        }
                    }
                }

                // 有匹配的目标规则，则还原为源规则
                if ($destRuleMatched)
                {
                    $srcRuleFields = $map['src'];
                    // 逐个替换 *
                    $n = 1;
                    foreach ($srcRuleFields as $si=>$field)
                    {
                        if ($field == '*')
                        {
                            $srcRuleFields[$si] = $matchedN[$n++];
                        }
                    }
                    return implode(C('API_ACTION_SEPARATOR'), $srcRuleFields);
                }
            }
        }

        return implode(C('API_ACTION_SEPARATOR'), $actionFields);
    }

    // 获取映射后的接口名
    // 若映射出错，则返回false
    public function getMappedAction()
    {
        if ( ! is_null($this->_mappedAction)) return $this->_mappedAction;

        // 获取接口名字段列表
        $actionName = $this->getParam('api_action', false);
        return $this->_mappedAction = $this->mapAction($actionName);
    }

    // 直接设置映射后的接口名
    public function setMappedAction($destActionName)
    {
        $this->_mappedAction = $destActionName;
    }

    // 获取应用ID
    public function getAppId()
    {
        if ( ! C('API_SIGN_APPID_ENABLED')) return '';
        return $this->getParam('api_appid', false);
    }

    // 对请求数据进行签名
    public function sign($signKey)
    {
        $params = $this->_params;
        unset($params[$this->getReplacedParamName('api_sign')]);

        ksort($params);
        return hash_hmac('md5', http_build_query($params), $signKey);
    }

    // 获取请求ID
    public function getRequestId()
    {
        if ( ! is_null($this->_requestId)) return $this->_requestId;

        $this->_requestId = $this->getParam('api_requestid', false);
        if ( ! empty($this->_requestId)) return $this->_requestId;

        // 生成UUID格式的请求ID
        $this->_requestId = sprintf('%04x%04x-%04x-%04x-%04x-%04x%04x%04x',
            // 32 bits for "time_low"
            mt_rand( 0, 0xffff ), mt_rand( 0, 0xffff ),
            // 16 bits for "time_mid"
            mt_rand( 0, 0xffff ),
            // 16 bits for "time_hi_and_version",
            // four most significant bits holds version number 4
            mt_rand( 0, 0x0fff ) | 0x4000,
            // 16 bits, 8 bits for "clk_seq_hi_res",
            // 8 bits for "clk_seq_low",
            // two most significant bits holds zero and one for variant DCE1.1
            mt_rand( 0, 0x3fff ) | 0x8000,
            // 48 bits for "node"
            mt_rand( 0, 0xffff ), mt_rand( 0, 0xffff ), mt_rand( 0, 0xffff )
        );
        return $this->_requestId;
    }

    // 获取API输出格式
    public function getFormat()
    {
        if ( ! is_null($this->_format)) return $this->_format;

        $apiFormat = $this->getParam('api_format', false);
        if (empty($apiFormat) || !in_array($apiFormat, array('json', 'jsonp')))
        {
            $apiFormat = C('API_DEFAULT_FORMAT');
        }

        return $this->_format = $apiFormat;
    }

    // 获取API语言
    public function getLang()
    {
        if ( ! is_null($this->_lang)) return $this->_lang;

        $apiLang = $this->getParam('api_lang', false);
        if (empty($apiLang) || !in_array($apiLang, array_map('trim', explode(',', C('API_LANG_LIST')))))
        {
            $apiLang = C('API_DEFAULT_LANG');
        }

        return $this->_lang = $apiLang;
    }

    // 获取请求时间
    public function getRequestTime()
    {
        if ( ! is_null($this->_requestTime)) return $this->_requestTime;

        $this->_requestTime = isset($_SERVER['REQUEST_TIME']) ? $_SERVER['REQUEST_TIME'] : time();
        return $this->_requestTime;
    }
}
