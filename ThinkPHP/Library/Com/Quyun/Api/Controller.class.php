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

use Think\Hook;
use Com\Quyun\Api\Request,
	Com\Quyun\Api\Response,
    Com\Quyun\Api\Param,
	Com\Quyun\Api\Exception;

/**
 * API控制器基类
 */
class Controller
{
    protected $request;     // 请求对象
    protected $response;    // 响应对象

    public function __construct()
    {
        // [TAG]: api_controller_init
        Hook::listen(C('API_CONTROLLER_INIT_TAG'), $this);

        $this->request = Request::getInstance();
        $this->response = Response::getInstance();
    }

    public function __call($method, $args)
    {
        $actionName = ACTION_NAME.(defined('API_ACTION_SUFFIX') ? API_ACTION_SUFFIX : C('API_ACTION_SUFFIX'));
        $args = is_array($args) ? $args : array();  // ThinkPHP对$args做了特殊处理

        // action方法必须存在且必须为public
        $actionExists = false;
        if (method_exists($this, $actionName))
        {
            $method = new \ReflectionMethod($this, $actionName);
            if ($method->isPublic())
            {
                $actionExists = true;
            }
        }

        if ($actionExists)
        {
            try
            {
                $responseData = call_user_func_array(array($this, $actionName), $args);
                $this->_apiSuccess($responseData);
            }
            catch (Exception $e)
            {
                $this->_apiError($e->getApiCode(), $e->getApiMessage(), $e->getApiForce());
            }
        }
        else
        {
            $this->_apiError('Global:ActionNotExist', 'API action does not exist!');
        }
    }

    private function _apiSuccess($data)
    {
        $this->response->code = C('API_SUCCESS_CODE');
        $this->response->data = $data;
    }
    
    private function _apiError($code, $message=null, $force=false)
    {
        $this->response->code = $code;
        $this->response->message = $message;
        $this->response->force = $force;
    }
}
