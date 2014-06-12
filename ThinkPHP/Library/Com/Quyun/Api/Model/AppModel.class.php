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

class AppModel extends Model
{
    protected $trueTableName = 'api_app';

    protected $_apiListOptions = array(
        'DefaultOrder'      => null,
        'DefaultOrderBy'    => null,
        'AllowOrderBys'     => null,
        'RowsKey'           => 'Apps',
        'ExcludeFields'     => 'id',
    );
    protected $_apiDetailOptions = array(
        'RowKey'            => 'App',
        'ExcludeFields'     => 'id',
    );
    protected $_apiCreateOptions = array(
        'AutoGenIdLen'      => 8,
        'IdKey'             => 'AppId',
    );
    protected $_apiUpdateOptions = array(
        'RowCountKey'       => 'RowCount',
        'ErrorOnZero'       => true,
    );
    protected $_apiDeleteOptions = array(
        'RowCountKey'       => 'RowCount',
        'ErrorOnZero'       => true,
    );

    protected $_apiIdField = 'AppId';

    protected function _initialize()
    {
        // 关闭读取时的字段自动映射
        C('READ_DATA_MAP', false);

        $this->trueTableName = C('API_SIGN_TABLENAME');
        $connection = C('API_SIGN_CONNECTION');
        if ( ! is_null($connection)) $this->connection = $connection;
    }

}