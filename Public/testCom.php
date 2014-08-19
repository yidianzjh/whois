<?php

function curl($publicParams)
{
    if (isset($publicParams['api_signature']))
        unset($publicParams['api_signature']);
    asort($publicParams);
    $request_str = http_build_query($publicParams);

    $publicParams['api_signature'] = urlencode(base64_encode(hash_hmac('sha1', $request_str, '654321', TRUE)));
    $request_str = http_build_query($publicParams);
    $url = "http://192.168.122.150/?".$request_str;
    $headers = array("Host: "."whois.local");
    $ch = curl_init();
    $timeout = 100;
    curl_setopt ($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
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

    $publicParams['DomainName'] = 'aa.Com';
    $publicParams['api_action'] = 'Whois.Search.Search';
    curl($publicParams);
    exit;
}