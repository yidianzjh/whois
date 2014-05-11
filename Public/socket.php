<?php
$server="whois.35.com";//TLD.comwhoisserver
//whois.markmonitor.com  whois.verisign-grs.com
$data="";
$domain="cm.com";//serchdomain
$fp=fsockopen($server,43);
if($fp){
    fputs($fp,$domain."\r\n");
    while(!feof($fp)){
        $data.=fgets($fp,1000);
    }
}
fclose($fp);
//$sub_data=preg_replace('/\r|\n/','<br />',$data);
echo $data."<br /><br /><br />";
//preg_match_all('/(?P<name>Registrant(.*\s*)*?)(?P<value>\w+([-+.]\w+)*@\w+([-.]\w+)*\.\w+([-.]\w+)*)/',$data,$matches);
//$sub_data=$matches["value"][0];
//echo $sub_data;
//print_r($matches);