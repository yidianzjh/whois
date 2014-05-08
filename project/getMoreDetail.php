<?php
$server="whois.verisign-grs.com";//TLD.comwhoisserver
$data="";
$domain="=".$_GET["domainName"];//serchdomain
$fp=fsockopen($server,43);
if($fp){
    fputs($fp,$domain."\r\n");
    while(!feof($fp)){
        $data.=fgets($fp,1000);
    }
}
fclose($fp);
//echo $data."<br /><br /><br />";
preg_match_all('/Domain Name:(.*\s)*/',$data,$matches);
$sub_data=$matches[0][0];
//echo "sub_data   ".$sub_data."<br /><br /><br />";
preg_match_all('/(?P<name>Whois Server): (?P<value>.+)/',$sub_data,$matches);
$server=$matches["value"][0];
//echo "server   ".$server."<br /><br /><br />";
$data="";
$domain=$_GET["domainName"];
$fp=fsockopen($server,43);
if($fp){
    fputs($fp,$domain."\r\n");
    while(!feof($fp)){
        $data.=fgets($fp,1000);
    }
}
fclose($fp);
//echo $data."<br /><br /><br />";
if(preg_match_all('/(?P<name>owner-organization): (?P<value>.+)/',$data,$matches))
{
    $owner_organization=$matches["value"][0];
}
if(preg_match_all('/(?P<name>Registrant.*\s*.*\s*)(?P<value>.+?)[\r\n]/',$data,$matches))
{
    $owner_organization=$matches["value"][0];
}
//echo $owner_organization."<br />";
if(preg_match_all('/(?P<name>owner-name): (?P<value>.+)/',$data,$matches))
{
    $owner_name=$matches["value"][0];
}
if(preg_match_all('/(?P<name>Registrant.*\s*)(?P<value>.+?)[\r\n]/',$data,$matches))
{
    $owner_name=$matches["value"][0];
}
//echo $owner_name;
if(preg_match_all('/(?P<name>owner-email): (?P<value>.+)/',$data,$matches))
{
    $owner_email=$matches["value"][0];
}
if(preg_match_all('/(?P<name>Registrant(.*\s*)*?)(?P<value>\w+([-+.]\w+)*@\w+([-.]\w+)*\.\w+([-.]\w+)*)/',$data,$matches))
{
    $owner_email=$matches["value"][0];
}
//echo $owner_email;
$data=preg_replace('/\r|\n/','<br \/>',$data);
$return_data='{"Code":0,"Detail":"'.$data.'","Email":"'.$owner_email.'","Message":"\u6210\u529f\u3002","RegOrg":"'.$owner_organization.'","Regname":"'.$owner_name.'"}';
echo $return_data;
//\w+([-+.]\w+)*@\w+([-.]\w+)*\.\w+([-.]\w+)*