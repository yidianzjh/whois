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

class SignNonceModel extends Model
{
    protected $trueTableName = 'api_sign_nonce';

    protected $_map = array(
        'Nonce'     =>  'nonce',
        'GenerateTime'   =>  'generate_time',
    );

    protected function _initialize()
    {
        $this->trueTableName = C('API_SIGN_NONCE_TABLENAME');
        $connection = C('API_SIGN_NONCE_CONNECTION');
        if ( ! is_null($connection)) $this->connection = $connection;
    }

}