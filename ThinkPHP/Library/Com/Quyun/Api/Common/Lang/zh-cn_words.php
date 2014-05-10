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
    // String
    'TooShort'      => '太短',
    'TooLong'       => '太长',
    'WrongFormat'   => '格式错误',

    // DigitString
    //'TooShort'      => '太短',
    //'TooLong'       => '太长',
    'NotDigitString'=> '不是数字串',

    // Int
    'NotInt'        => '不是整数',

    // Timestamp
    'TooSmall'      => '太小',
    'TooLarge'      => '太大',

    // Date
    'NotDate'       => '日期格式错误',
    'TooEarly'      => '太早',
    'TooLate'       => '太晚',

    // DateTime
    'NotDateTime'   => '时间格式错误',
    // 'TooEarly'      => '太早',
    // 'TooLate'       => '太晚',

    // IpAddress
    'NotIpAddress'  => '不是IP地址',

    // CIDR
    'NotCIDR'       => '不是CIDR地址段',

    // Email
    'NotEmail'      => '邮件格式错误',
    // 'TooShort'      => '太短',
    // 'TooLong'       => '太长',

    // Range
    'NotPair'       => '范围不成对',
    'MissingItem'   => '缺少一端',
    'RangeInvalid'  => '格式错误',
    'RangeReversed' => '两端值反了',
    'RangeExceeded' => '范围超出限制',

    // Set
    'TooFew'        => '太少',
    'TooMany'       => '太多',
    'ItemInvalid'   => '集合中存在不合法的元素',
);