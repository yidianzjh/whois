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
//echo $data;
$sub_data=$data;
preg_match_all('/Domain Name:(.*\s)*/',$data,$matches);
$sub_data=$matches[0][0];
//print_r($sub_data);
preg_match_all('/(?P<name>Domain Name): (?P<value>.+)/',$sub_data,$matches);
$domain_name=$matches["value"][0];
echo $domain_name."<br />";
preg_match_all('/(?P<name>Registrar): (?P<value>.+)/',$sub_data,$matches);
$registrar=$matches["value"][0];
echo $registrar."<br />";
preg_match_all('/(?P<name>Whois Server): (?P<value>.+)/',$sub_data,$matches);
$whois_server=$matches["value"][0];
echo $whois_server."<br />";
preg_match_all('/(?P<name>Referral URL): (?P<value>.+)/',$sub_data,$matches);
$referral_URL=$matches["value"][0];
echo $referral_URL."<br />";
preg_match_all('/(?P<name>Name Server): (?P<value>.+)/',$sub_data,$matches);
foreach($matches["value"] as $tmp )
{
    $name_server.=$tmp."<br />";
}
echo $name_server."<br />";
preg_match_all('/(?P<name>Status): (?P<value>.+)/',$sub_data,$matches);
foreach($matches["value"] as $tmp )
{
    $status.=$tmp."<br />";
}
echo $status."<br />";
preg_match_all('/(?P<name>Updated Date): (?P<value>.+)/',$sub_data,$matches);
$updated_date=$matches["value"][0];
echo $updated_date."<br />";
preg_match_all('/(?P<name>Creation Date): (?P<value>.+)/',$sub_data,$matches);
$creation_date=$matches["value"][0];
echo $creation_date."<br />";
preg_match_all('/(?P<name>Expiration Date): (?P<value>.+)/',$sub_data,$matches);
$expiration_date=$matches["value"][0];
echo $expiration_date."<br />";

?>

