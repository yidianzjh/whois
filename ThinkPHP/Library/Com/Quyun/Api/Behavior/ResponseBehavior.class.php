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

use Org\Net\IpLocation;
use Think\Hook;
use Com\Quyun\Api\Request,
    Com\Quyun\Api\Response,
    Com\Quyun\Api\Model\LogModel,
    Com\Quyun\Api\Model\LogDetailModel;

/**
 * API输出结果行为扩展
 */
class ResponseBehavior
{
    public function run(&$params)
    {
        // 判断是否API请求
        $response = Response::getInstance();
        if (is_null($response->code)) return;

        $response->output();

        if (C('API_LOG_ENABLED'))
        {
            $request = Request::getInstance();

            // 记录日志
            $log = new LogModel();

            $logData = array(
                'request_id'    =>  $request->getRequestId(),
                'request_method'=>  $_SERVER['REQUEST_METHOD'],
                'code'          =>  $response->code,
            );

            // 请求数据
            $logData['action'] = $request->getParam('api_action', true);
            $logData['raw_action'] = $request->getMappedAction($logData['action']);
            $logData['is_debug'] = $request->getParam('api_debug', true) ? 'Y' : 'N';
            $logData['app_id'] = $request->getAppId();

            // IP地理位置
            $ipAddress = get_client_ip();
            //$ipAddress = '218.85.157.99';
            $logData['ip_address'] = $ipAddress;
            if (C('API_LOG_GEO_ENABLED'))
            {
                $ip = new IpLocation(C('API_LOG_GEO_LIBPATH'));
                $geo = $ip->getlocation($ipAddress);
                $logData['location'] = iconv('gbk','utf-8', $geo['country']);
                $logData['location_ex'] = iconv('gbk','utf-8', $geo['area']);
            }

            // 时间统计
            $logData['request_time'] = $_SERVER['REQUEST_TIME'];
            $logData['elapse_time'] = G('api_router_start', 'api_response_end', 6) * 1000000;

            // [TAG]: api_log_before_add
            Hook::listen(C('API_LOG_BEFORE_ADD_TAG'), $logData);

            $log->add($logData);

            // 记录详细日志
            if (C('API_LOG_DETAIL_ENABLED'))
            {
                $detail = new LogDetailModel();
                $detailData = array(
                    'request_id' => $request->getRequestId(),
                    'request_data' => json_encode($request->getParams()),
                    'response_data' => json_encode($response->getReturnData()),
                );
                $detail->add($detailData);
            }
        }
    }
}
