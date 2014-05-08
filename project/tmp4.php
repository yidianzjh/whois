INSERT INTO `domain_info` ( `domain_name` , `owner_email` , `owner_name` , `owner_organization` , `registrar` , `whois_server` , `creation_date` , `expiration_date` , `updated_date` , `status` , `nameservers` , `md5` , `query_time` )
VALUES (
"baidu.com", "baidu.com", "baidu.com", "baidu.com", "baidu.com", "baidu.com", 1, 11, 111, "baidu.com", "baidu.com", 111111111, 1111111
)

SELECT id, query_time
FROM `domain_info`
WHERE domain_name = "baidu.com"
ORDER BY query_time
LIMIT 0 , 30
<?php
require_once 'conn.php';
$db = new data_class();
$result = $db->query("SELECT `id`,`query_time`,`md5` FROM `domain_info` WHERE `domain_name`=\"".$_GET["domainName"]."\"");
$i=0;
$str="";
foreach($result as $tmp)
{
    $str.="<dd><input type='checkbox' name='history[]' id='his_".$i."' class='hisInput' \/><label for='his_".$i."'>".$result[$i]->query_time."<\/label><span class='contrasBtn' style='display:none' historyId='".$result[$i]->id."'><\/span><span class='see_whois' MD5='".$result[$i]->md5."'  style='display:none' historyId='".$result[$i]->md5."'><\/span><\/dd>";
    $i+=1;
}
$str.="<dd id='mcheck' style='float:right;display:inline;margin-right:10px;text-align:right;color:#0399D7;cursor:pointer'>\u66f4\u591a<\/dd>";
//echo $str;
//echo $result[0]->id;
//var_dump($result);

/*
 *
 "<dd><input type='checkbox' name='history[]' id='his_0' class='hisInput' \/>
<label for='his_0'>2013-06-19<\/label><span class='contrasBtn' style='display:none' historyId='93279751'><\/span>
<span class='see_whois' domainId='22934386'  style='display:none' historyId='93279751'><\/span>
<\/dd>
<dd><input type='checkbox' name='history[]' id='his_1' class='hisInput' \/>
<label for='his_1'>2012-08-19<\/label><span class='contrasBtn' style='display:none' historyId='28914798'><\/span>
<span class='see_whois' domainId='22934386'  style='display:none' historyId='28914798'><\/span>
<\/dd>
<dd><input type='checkbox' name='history[]' id='his_2' class='hisInput' \/>
<label for='his_2'>2011-12-10<\/label><span class='contrasBtn' style='display:none' historyId='29110273'><\/span>
<span class='see_whois' domainId='22934386'  style='display:none' historyId='29110273'><\/span>
<\/dd>
<dd id='mcheck' style='float:right;display:inline;margin-right:10px;text-align:right;color:#0399D7;cursor:pointer'>\u66f4\u591a<\/dd>"


*/