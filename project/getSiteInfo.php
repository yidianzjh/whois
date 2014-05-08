<?php
function _url($Date){
    $ch = curl_init();
    $timeout = 5;
    curl_setopt ($ch, CURLOPT_URL, "$Date");
    curl_setopt ($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt ($ch, CURLOPT_USERAGENT, "Mozilla/4.0 (compatible; MSIE 6.0; Windows NT 5.1; SV1)");
    curl_setopt ($ch, CURLOPT_CONNECTTIMEOUT, $timeout);
    $contents = curl_exec($ch);
    curl_close($ch);
    return $contents;
}
$pageURL=array();
$contents=array();
$matches=array();
$pageURL[0]="http://www.baidu.com/s?wd=site:".rawurlencode($_GET["domainName"]);
$pageURL[1]="http://www.so.com/s?q=site:".rawurlencode($_GET["domainName"]);
$pageURL[2]="http://www.sogou.com/web?query=site:".rawurlencode($_GET["domainName"]);
$pageURL[3]="http://www.google.de/search?hl=en&safe=off&btnG=Search&q=site:".rawurlencode($_GET["domainName"]);
$contents[0]=_url($pageURL[0]);
$contents[1]=_url($pageURL[1]);
$contents[2]=_url($pageURL[2]);
$contents[3]=_url($pageURL[3]);
//var_dump($contents[0]);
preg_match('/找到相关结果数(?P<num>[,\d]*)/',$contents[0],$matches[0]);
preg_match('/找到相关结果约(?P<num>[,\d]*)/',$contents[1],$matches[1]);
preg_match('/找到约 <span id=\"scd_num\">(?P<num>[,\d]*)/',$contents[2],$matches[2]);
preg_match('/<div id=\"resultStats\">About (?P<num>[,\d]*)/',$contents[3],$matches[3]);
//var_dump($matches);

//echo $contents;

echo "{\"bd_sl\":\"".$matches[0]["num"]."\",\"bd\":\"http:\/\/www.baidu.com\/s?wd=site:".$_GET["domainName"]."\",";
echo "\"so_sl\":\"".$matches[1]["num"]."\",\"so\":\"http:\/\/www.so.com\/s?q=site:".$_GET["domainName"]."\",";
echo "\"sg_sl\":\"".$matches[2]["num"]."\",\"sg\":\"http:\/\/www.sogou.com\/web?query=site:".$_GET["domainName"]."\",";
echo "\"gg_sl\":\"".$matches[3]["num"]."\",\"gg\":\"http:\/\/www.google.de\/search?hl=en&safe=off&btnG=Search&q=site:".$_GET["domainName"]."\"}";