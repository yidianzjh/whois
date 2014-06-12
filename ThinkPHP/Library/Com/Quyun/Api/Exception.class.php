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

use Think\Exception as Think_Exception;

class Exception extends Think_Exception
{
    private $_apiCode;
    private $_apiMessage;
    private $_apiForce;

    private $_errorTypes = array(
        'Global'            => array('exFieldCount' => 1),
        'ObjectNotExist'    => array('exFieldCount' => array(0, 1)),
        'ObjectDuplicated'  => array('exFieldCount' => array(0, 1)),
        'NoObjectUpdated'   => array('exFieldCount' => array(0, 1)),
        'NoObjectDeleted'   => array('exFieldCount' => array(0, 1)),
        'MissingParam'      => array('exFieldCount' => 1, 'allowOr' => array(true, false)),
        'InvalidParam'      => array('exFieldCount' => array(1, 2)),
        'QuotaExceed'       => array('exFieldCount' => array(0, 1)),
        'PermissionDenied'  => array('exFieldCount' => array(0, 1)),
        'OperationFailed'   => array('exFieldCount' => 1),
        'InternalError'     => array('exFieldCount' => 1),
    );

    public function __construct($code, $message=null, $force=false)
    {
        // 仅在开发模式下进行检测
        if (C('API_DEV_MODE') && C('API_ERROR_CODE_CHECK'))
        {
            // 检查错误类型格式
            $fields = explode(':', $code);
            $errorType = array_shift($fields);

            assert('isset($this->_errorTypes["'.$errorType.'"])', 'Invalid error type: "'.$errorType.'"!');
            $typeDefine = $this->_errorTypes[$errorType];
            $exFieldCount = $typeDefine['exFieldCount'];
            if (is_int($exFieldCount)) $exFieldCount = array($exFieldCount);
            assert('in_array(count($fields), array('.implode(',', $exFieldCount).'))', 'Error code field count not allow!');
            foreach ($fields as $i=>$field)
            {
                if (isset($typeDefine['allowOr']) && $typeDefine['allowOr'][$i])
                    $regEx = '/^[A-Z][A-Za-z0-9]*(?:\|[A-Z][A-Za-z0-9]*)*$/';
                else
                    $regEx = '/^[A-Z][A-Za-z0-9]*$/';
                assert('preg_match($regEx, $field)', 'Invalid error code field format: "'.$field.'"!');
            }
        }

        parent::__construct($code);
        $this->_apiCode    = $code;
        $this->_apiMessage = $message;
        $this->_apiForce   = $force;
    }

    public function getApiCode()
    {
        return $this->_apiCode;
    }

    public function getApiMessage()
    {
        return $this->_apiMessage;
    }

    public function getApiForce()
    {
        return $this->_apiForce;
    }
}