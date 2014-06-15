<?php
date_default_timezone_set('UTC');

ignore_user_abort(); // 后台运行
set_time_limit(0); // 取消脚本运行时间的超时上限

function curl($publicParams)
{
    if (isset($publicParams['api_signature']))
        unset($publicParams['api_signature']);
    asort($publicParams);
    $request_str = http_build_query($publicParams);

    $publicParams['api_signature'] = urlencode(base64_encode(hash_hmac('sha1', $request_str, '654321', TRUE)));
    $request_str = http_build_query($publicParams);
    $url = "http://whois.local/?".$request_str;

    $ch = curl_init();
    $timeout = 100;
    curl_setopt ($ch, CURLOPT_URL, $url);
    curl_setopt ($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt ($ch, CURLOPT_CONNECTTIMEOUT, $timeout);
    $file_contents = curl_exec($ch);
    curl_close($ch);
    var_dump($file_contents);
}

$publicParams = array();

$publicParams['api_format'] 	= 'json';
$publicParams['api_timestamp'] = time();
$publicParams['api_sign_nonce'] = rand();


while (1)
{

    /******daemon******/
    $letterArr = array('a','b','c','d','e','f','g','h','i','j','k',
        'l','m','n','o','p','q','r','s','t','u','v','w','x','y','z');
    $domainSuffixArr = array('.be','.biz','.cc','.cn','.com','.co','.cz','.ee',
        '.gg','.gl','info','.lc','.ms','.net','.nl','.org','.vg',);
    $charArr = $letterArr;
    shuffle($charArr);
    shuffle($domainSuffixArr);
    searchDomain('', 1, 2, $charArr, $domainSuffixArr);
    exit;
}

function searchDomain($domainName, $i, $n, $charArr, $domainSuffixArr)
{
    foreach ($charArr as $char)
    {
        searchDomainSub($domainName.$char, $domainSuffixArr);
        if ($i < $n)
        {
            searchDomain($domainName.$char, $i+1, $n, $charArr, $domainSuffixArr);
        }
    }
}

function searchDomainSub($domainName, $domainSuffixArr)
{
    foreach ($domainSuffixArr as $domainSuffix)
    {
        $publicParams['DomainName'] = $domainName.$domainSuffix;
        $publicParams['api_action'] = 'Whois.Search.Search';
        curl($publicParams);
    }
}