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
namespace Com\Quyun\Api\Model;

use Think\Model;

class LogDetailModel extends Model
{
    protected $trueTableName = 'api_log_detail';

    protected $_map = array(
        'RequestId'     =>  'request_id',
        'RequestData'   =>  'request_data',
        'ResponseData'  =>  'response_data',
    );

    protected function _initialize()
    {
        // 关闭读取时的字段自动映射
        C('READ_DATA_MAP', false);

        $this->trueTableName = C('API_LOG_DETAIL_TABLENAME');
        $connection = C('API_LOG_CONNECTION');
        if ( ! is_null($connection)) $this->connection = $connection;
    }

}