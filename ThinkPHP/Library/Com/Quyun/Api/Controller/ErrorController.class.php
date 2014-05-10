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

class ErrorController extends Controller
{
    public function errorAction($code='InternalError', $message='Unknown')
    {
        APIE($code, $message);
    }
}