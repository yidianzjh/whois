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

return array(
    'Global:AppNotExist'            => '应用不存在！',
    'Global:MissingSignature'       => '缺少签名！',
    'Global:SignatureError'         => '签名校验失败！',
    'Global:MissingTimestamp'       => '缺少时间戳！',
    'Global:InvalidTimestamp'       => '时间戳不合法!',
    'Global:SignatureExpired'       => '请求已超时！',
    'Global:MissingSignatureNonce'  => '缺少签名Nonce！',
    'Global:InvalidSignatureNonce'  => '无效的签名Nonce！',
    'Global:SignatureNonceExist'    => '签名Nonce已存在！',
    'Global:ActionNotExist'         => '接口不存在！',

    'ObjectNotExist'        => '对象不存在！',
    'ObjectDuplicated'      => '对象已存在！',
    'NoObjectDeleted'       => '没有对象被删除！',
    'NoObjectUpdated'       => '没有对象被更新！',
    'QuotaExceed'           => '超出配额限制！',
    'PermissionDenied'      => '没有权限！',
    'OperationFailed'       => '操作失败！',

    'InternalError:GenIdFailed'     => '内部错误：随机ID生成失败！',
    'InternalError:InsertFailed'    => '内部错误：数据插入失败！',
    'InternalError:UpdateFailed'    => '内部错误：数据更新失败！',
);