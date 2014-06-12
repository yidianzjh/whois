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

/**
 * 抛出异常处理
 * @param string $code 异常代码
 * @param string $message 异常消息
 * @param bool $force 是否强制输出$message指定的错误消息（忽略多语言）
 * @return void
 */
function APIE($code, $message=null, $force=false)
{
    throw new Com\Quyun\Api\Exception($code, $message, $force);
}

/**
 * 在HTTP头部输出调试信息，对应的HTTP头为：X-API-DEBUG
 * 调试信息将以json_encode()后的字符串保存
 * @param mixed $debugInfo 调试信息
 * @return void
 */
function APID($debugInfo)
{
	$jsonSetting = JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE;
    header('X-API-DEBUG: '.json_encode($debugInfo, $jsonSetting));
}

/**
 * assert失败处理
 * @param string $file 脚本文件
 * @param int $line 出错行
 * @param string $code 检测条件
 * @param string $desc 消息描述
 * @return void
 */
function API_ASSERT_CALLBACK($file, $line, $code, $desc=null)
{
    echo "<hr>Assertion Failed:<br />";
    echo "Desc: <b style=\"color:red\">{$desc}</b><br />";
    echo "File: <b>{$file}</b><br />";
    echo "Line: <b>{$line}</b><br />";
    echo "Code: <b>{$code}</b><br />";
    echo "<hr />";
}