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

use Com\Quyun\Api\ViewModel;

class LogViewModel extends ViewModel
{
    public $viewFields = array(
        'Log' => array(
            'request_id',
            'request_method',
            'app_id',
            'action',
            'raw_action',
            'code',
            'language',
            'is_debug',
            'ip_address',
            'location',
            'location_ex',
            'request_time',
            'elapse_time',
            '_type' => 'LEFT',
        ),
        'LogDetail' => array(
            'request_data',
            'response_data',
            '_on' => 'LogDetail.request_id=Log.request_id'
        ),
    );

    protected $_apiIntFields = array('RequestTime', 'ElapseTime');

    protected $_apiListOptions = array(
        'DefaultOrder'      => 'desc',
        'DefaultOrderBy'    => 'RequestTime',
        'AllowOrderBys'     => array('RequestTime', 'ElapseTime'),
        'RowsKey'           => 'Logs',
        'ExcludeFields'     => 'id',
    );
    protected $_apiDetailOptions = array(
        'RowKey'            => 'Log',
        'ExcludeFields'     => 'id',
    );
    protected $_apiDeleteOptions = array(
        'RowCountKey'       => 'RowCount',
        'ErrorOnZero'       => false,
    );

    protected function _initialize()
    {
        // 关闭读取时的字段自动映射
        C('READ_DATA_MAP', false);

        $this->viewFields['Log']['_table'] = C('API_LOG_TABLENAME');
        $this->viewFields['LogDetail']['_table'] = C('API_LOG_DETAIL_TABLENAME');

        $connection = C('API_LOG_CONNECTION');
        if ( ! is_null($connection)) $this->connection = $connection;

        // 合并日志扩展字段的配置
        $logExMap = C('API_LOGEX_MAP');
        if ($logExMap) $this->_map = array_merge($this->_map, array_flip($logExMap));
    }

}