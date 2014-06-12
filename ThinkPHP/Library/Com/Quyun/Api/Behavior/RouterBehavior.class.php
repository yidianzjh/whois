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
namespace Com\Quyun\Api\Behavior;

use Think\Hook;
use Com\Quyun\Api\Boot,
    Com\Quyun\Api\Request,
    Com\Quyun\Api\Model\ApplicationModel,
    Com\Quyun\Api\Model\SignNonceModel,
    Com\Quyun\Api\Exception;

/**
 * API路由行为扩展
 */
class RouterBehavior
{
    private $_request = null;
    private $_isTestMode = false;

    private function _testInit()
    {
        // API测试初始化
        if (C('API_TEST_ENABLED'))
        {
            // 检查是否进入测试模式
            $this->_isTestMode = $this->_request->getParam('api_test', true);
            if ( ! $this->_isTestMode) return true;
            
            // 加载测试模式配置
            $testConfig = C('API_TEST_CONFIG');
            if ($testConfig)
            {
                if (is_file(CONF_PATH.$testConfig.'.php')) C(include CONF_PATH.$testConfig.'.php');
            }

            // 加载测试初始化类
            $testModule = C('API_TEST_MODULE');
            $initClassName = $testModule.'\\Init';
            if (class_exists($initClassName))
            {
                $init = new $initClassName;
                if (method_exists($init, 'run')) $init->run();
            }
        }

        return true;
    }

    private function _checkSign()
    {
        // 接口签名检测
        if (C('API_SIGN_ENABLED'))
        {
            $signKey = C('API_SIGN_KEY');

            if (C('API_SIGN_APPID_ENABLED'))
            {
                // 检查应用是否存在
                $appId = $this->_request->getParam('api_appid', true);

                // 从数据库中读取appid与appkey
                $app = new ApplicationModel();
                $appInfo = $app->where("app_id='%s'", $appId)->find();
                if ( ! $appInfo)
                {
                    APIE('Global:AppNotExist', 'Application does not exist!');
                }
                $signKey = $appInfo['app_key'];
            }

            // 开发模式下，不进行后续验证
            if (C('API_DEV_MODE')) return true;

            // 检查时间戳
            $apiTimestamp = $this->_request->getParam('api_timestamp', true);
            if (is_null($apiTimestamp))
            {
                APIE('Global:MissingTimestamp', 'Missing timestamp!');
            }
            $requestTime = $this->_request->getRequestTime();
            if (filter_var($apiTimestamp, FILTER_VALIDATE_INT, array('min_range' => 0)) === false
                || $apiTimestamp - $requestTime > C('API_SIGN_ERRORBAND'))
            {
                APIE('Global:InvalidTimestamp', 'Timestamp is invalid!');
            }
            else if ($requestTime - $apiTimestamp > C('API_SIGN_EXPIRETIME'))
            {
                APIE('Global:SignatureExpired', 'Request has expired!');
            }

            // 检查应用签名
            $sign = $this->_request->getParam('api_sign', true);
            if (is_null($sign))
            {
                APIE('Global:MissingSignature', 'Missing signature!');
            }
            if ($sign != $this->_request->sign($signKey))
            {
                APIE('Global:SignatureError', 'API signature check failed!');
            }

            // 网络重调用检测
            if (C('API_SIGN_NONCE_ENABLED'))
            {
                $apiSignNonce = $this->_request->getParam('api_sign_nonce', true);
                if (is_null($apiSignNonce))
                {
                    APIE('Global:MissingSignatureNonce', 'Missing signature nonce!');
                }

                $signNonceStorage = C('API_SIGN_NONCE_STORAGE');
                if ($signNonceStorage == 'mysql')
                {
                    if ( ! preg_match('/^\d{16}$/', $apiSignNonce))
                    {
                        APIE('Global:InvalidSignatureNonce', 'Invalid signature nonce!');
                    }

                    // 检查数据库中是否存在nonce
                    $nonce = new SignNonceModel();
                    if ($nonce->where("nonce='%s'", $apiSignNonce)->find())
                    {
                        APIE('Global:SignatureNonceExist', 'Signature nonce already exists!');
                    }

                    // 缓存nonce
                    $nonce->data(array(
                        'nonce' => $apiSignNonce,
                        'generate_time' => array('exp', 'UNIX_TIMESTAMP()'),
                    ))->add();

                    // 清理nonce
                    if (mt_rand(1, C('API_SIGN_NONCE_CLEARRATE')) == 1)
                    {
                        // 启动自动清理
                        $expireTime = C('API_SIGN_NONCE_EXPIRETIME');
                        if ($expireTime < C('API_SIGN_EXPIRETIME')) $expireTime = C('API_SIGN_EXPIRETIME') + 300;
                        $nonce->where('generate_time < UNIX_TIMESTAMP()-'.$expireTime)->delete();
                    }
                }
                elseif ($signNonceStorage == 'memcache')
                {
                    // 连接到memcache检查是否存在nonce
                    $config = array_merge(array(
                        'host' => '127.0.0.1',
                        'port' => '11211',
                        'persistent' => true,
                    ), C('API_SIGN_NONCE_MEMCACHE'));

                    $memcache = new \Memcache;
                    $connectFunc = $config['persistent'] ? 'pconnect' : 'connect';
                    $memcache->$connectFunc($config['host'], $config['port']);

                    // 检查nonce是否存在
                    $nonceKey = C('API_SIGN_NONCE_KEYPREFIX').$apiSignNonce;
                    if ($memcache->get($nonceKey))
                    {
                        APIE('Global:SignatureNonceExist', 'Signature nonce already exists!');
                    }

                    // 缓存nonce
                    $expireTime = C('API_SIGN_NONCE_EXPIRETIME');
                    if ($expireTime < C('API_SIGN_EXPIRETIME')) $expireTime = C('API_SIGN_EXPIRETIME') + 300;
                    $memcache->set($nonceKey, true, 0, $expireTime);

                    if ( ! $config['persistent']) $memcache->close();
                }
            }
        }

        return true;
    }

    // 调用Action
    private function callAction($apiAction, $apiParams)
    {
        // Copy from App.class.php -- start
        // 应用开始标签
        Hook::listen('app_begin');
        // Session初始化
        if(!IS_CLI){
            session(C('SESSION_OPTIONS'));
        }
        // 记录应用初始化时间
        G('initTime');
        // Copy from App.class.php -- end

        // 模块被指定到扩展命名空间
        list($mName, $cName, $aName) = explode('.', $apiAction);
        $controller = A($mName.'/'.$cName, $cLayer);
        if ($controller === false)
        {
            $apiAction = 'Com\Quyun://Api.Error.Error';
            $apiParams = array('Global:ActionNotExist', 'API action does not exist!');
            list($mName, $cName, $aName) = explode('.', $apiAction);
            $controller = A($mName.'/'.$cName, $cLayer);
        }

        $apiErrorAction = 'Com\Quyun://Api.Error.Error';
        if ($apiAction != $apiErrorAction)
        {
            try
            {
                // [TAG]: api_controller_before
                Hook::listen(C('API_CONTROLLER_BEFORE_TAG'), $this->_request);
            }
            catch (Exception $e)
            {
                $apiAction = $apiErrorAction;
                $apiParams = array($e->getApiCode(), $e->getApiMessage());
                list($mName, $cName, $aName) = explode('.', $apiAction);
                $controller = A($mName.'/'.$cName, $cLayer);
            }
        }

        define('MODULE_NAME', $mName);
        define('CONTROLLER_NAME', $cName);
        define('ACTION_NAME', $aName);

        // 分析模块路径
        define('MODULE_PATH', LIB_PATH.str_replace(array('://', '\\'), '/', $mName));

        if ( ! is_null($aSuffix) && ! defined('API_ACTION_SUFFIX'))
        {
            define('API_ACTION_SUFFIX', $aSuffix);
        }

        // Copy from App.class.php -- start
        // 引导到__call方法处理
        $method = new \ReflectionMethod($controller,'__call');
        if ( ! isset($apiParams)) $apiParams = array();
        $method->invokeArgs($controller,array($aName, $apiParams));
        // Copy from App.class.php -- end

        // 应用结束标签
        Hook::listen('app_end');
        exit;
    }

    public function run(&$params)
    {
        // 计时开始
        G('api_router_start');

        // 关闭参数过滤器
        C('DEFAULT_FILTER', '');

        // 类库启动初始化
        Boot::init();

        // 处理请求数据
        $this->_request = Request::getInstance();

        // API测试模式检测
        $this->_testInit();

        if (C('API_DEV_MODE'))
        {
            // Assert处理
            assert_options(ASSERT_ACTIVE,   true);
            assert_options(ASSERT_BAIL,     true);
            assert_options(ASSERT_WARNING,  false);
            assert_options(ASSERT_CALLBACK, 'API_ASSERT_CALLBACK');
        }

        // URL路径检测
        $path = $_SERVER['REQUEST_URI'];
        if (($position = strpos($path, '?')) !== FALSE)
        {
            $path = substr($path, 0, $position);
        }
        // 如果不是请求绑定的路径，则跳过路由
        if ( ! fnmatch(C('API_BIND_PATH'), $path)) return;

        try
        {
            // [TAG]: api_request_init
            Hook::listen(C('API_REQUEST_INIT_TAG'), $this->_request);

            // 接口名映射处理
            $apiAction = $this->_request->getMappedAction();
            if ($apiAction === false)
            {
                // 接口名不合法，访问默认接口
                $apiAction = C('API_DEFAULT_ACTION');
                if (is_null($apiAction))
                {
                    APIE('Global:ActionNotExist', 'API action does not exist!');
                }
            }
            else
            {
                // API签名检测
                $this->_checkSign();
            }
        }
        catch (Exception $e)
        {
            $apiAction = 'Com\Quyun://Api.Error.Error';
            $apiParams = array($e->getApiCode(), $e->getApiMessage());
        }

        // 动态绑定输出行为
        Hook::add('app_end', __NAMESPACE__.'\\ResponseBehavior');

        // 路由信息分析
        $cLayer = $aSuffix = null;
        if (strpos($apiAction, ','))
        {
            list($apiAction, $extConfig) = explode(',', $apiAction, 2);
            if (strpos($extConfig, ','))
            {
                list($cLayer, $aSuffix) = explode(',', $extConfig, 2);
            }
            else
            {
                $cLayer = $extConfig;
            }
        }

        // 调用到具体的控制器
        if (strpos($apiAction, '://') !== false)
        {
            $this->callAction($apiAction, $apiParams);
        }
        else
        {
            try
            {
                if ($this->_isTestMode)
                {
                    // 加载测试初始化类
                    $testModule = C('API_TEST_MODULE');
                    $actionTestClassName = $testModule.'\\'.str_replace('.', '\\', $apiAction).C('API_TEST_SUFFIX');
                    if (class_exists($actionTestClassName))
                    {
                        $actionTestRunner = new $actionTestClassName;
                        if (method_exists($actionTestRunner, 'init')) $actionTestRunner->init();
                    }
                }

                // [TAG]: api_controller_before
                Hook::listen(C('API_CONTROLLER_BEFORE_TAG'), $this->_request);
            }
            catch (Exception $e)
            {
                $apiAction = 'Com\Quyun://Api.Error.Error';
                $apiParams = array($e->getApiCode(), $e->getApiMessage());
                $this->callAction($apiAction, $apiParams);
            }

            // 此处不使用A来调用，走ThinkPHP的路由解析，保证不影响系统定义的行为
            $_SERVER['PATH_INFO'] = str_replace('.', '/', $apiAction);
        }

        // 修改ThinkPHP的Action后缀，保证无法直接调用到API的Action
        C('ACTION_SUFFIX', 'Suffix_'.md5(uniqid()));
    }
}
