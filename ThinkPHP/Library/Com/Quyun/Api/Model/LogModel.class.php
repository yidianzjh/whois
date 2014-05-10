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

use Com\Quyun\Api\Model;

class LogModel extends Model
{
    protected $trueTableName = 'api_log';

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

        $this->trueTableName = C('API_LOG_TABLENAME');
        $connection = C('API_LOG_CONNECTION');
        if ( ! is_null($connection)) $this->connection = $connection;

        // 合并日志扩展字段的配置
        $logExMap = C('API_LOGEX_MAP');
        if ($logExMap) $this->_map = array_merge($this->_map, array_flip($logExMap));
    }

}