<?php

namespace Whois\Model;

use Com\Quyun\Api\Model;

class DomainInfoBizModel extends Model
{
    protected $trueTableName = 'domain_info_biz';

    protected $_pk = 'id';

    public $patchValidate = true;

    protected $fields = array(
        'id',
        'domain_info_id',
        'name',
        'create_time',
        'update_time',
        'is_registered',
    );

    protected $_apiListOptions = array(
        'DefaultOrder'      => null,
        'DefaultOrderBy'    => 'DomainInfoId',
        'AllowOrderBys'     => null,
        'RowsKey'           => 'DomainInfoList',
        'ExcludeFields'     => 'id',
    );
    protected $_apiDetailOptions = array(
        'RowKey'            => 'DomainInfo',
        'ExcludeFields'     => 'id',
    );
    protected $_apiCreateOptions = array(
        'IdKey'             => 'DomainInfoId',
    );
    protected $_apiUpdateOptions = array(
        'RowCountKey'       => 'RowCount',
        'ErrorOnZero'       => true,
    );
    protected $_apiDeleteOptions = array(
        'RowCountKey'       => 'RowCount',
        'ErrorOnZero'       => true,
    );

    protected $_apiIdField = 'DomainInfoId';
}