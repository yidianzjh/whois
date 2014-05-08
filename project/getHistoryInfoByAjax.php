<?php
error_reporting(0);
require_once 'conn.php';
$db = new db();
$result = $db->query("SELECT `id`,`query_time`,`md5` FROM `domain_info` WHERE `domain_name`=\"".$_GET["domainName"]."\" ORDER BY query_time DESC");

$i=0;
$str="\"";
if(!is_bool($result))
{
    foreach($result as $tmp)
    {
        $time=date("Y-m-d",$result[$i]->query_time);
        $str.="<dd><input type='checkbox' name='history[]' id='his_".$i."' class='hisInput' /><label for='his_".$i."'>".$time."</label><span class='contrasBtn' style='display:none' historyId='".$result[$i]->id."'></span><span class='see_whois' MD5='".$result[$i]->md5."'  style='display:none' historyId='".$result[$i]->id."' for='his_".$i."'></span></dd>";
        $i+=1;
    }
}
$str.="<dd id='mcheck' style='float:right;display:inline;margin-right:10px;text-align:right;color:#0399D7;cursor:pointer'>\u66f4\u591a<\/dd>\"";
echo $str;
