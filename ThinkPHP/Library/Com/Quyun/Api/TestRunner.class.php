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

class TestRunner
{
	// 获取测试用例生成规则列表
    public function getCaseRules()
    {
    	return array();
    }

    // 获取预置用例列表
    public function getCases()
    {
    	return array();
    }

    // 该接口测试前的初始化操作
    public function init()
    {
    	return true;
    }

}