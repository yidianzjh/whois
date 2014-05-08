<?php
require_once 'conn.php';
include 'getDomainInfo.php';

$DI=new domainInfo();
$rawdata_array=$DI->getRawdataArray();
$db = new db();
$sql = "SELECT `id`, `query_time`,`md5` \n"
    . "FROM `domain_info`\n"
    . "WHERE domain_name = \"".$_GET["domainName"]."\"\n"
    . "ORDER BY query_time DESC\n"
    . "LIMIT 1";
$result_array = $db->query($sql);

if(!is_bool($result_array))
{
    $rawdata_array[0]=preg_replace('/\"/','\\"',$rawdata_array[0]);
    $rawdata_array[1]=preg_replace('/\"/','\\"',$rawdata_array[1]);
    $data=preg_replace('/>>>(.*\s*)*/','r',$rawdata_array[0]);
    $data.=preg_replace('/>>>(.*\s*)*/','r',$rawdata_array[1]);
    echo "<br/>data=".$data;
    $md51=md5($data);
    //echo $md51."<br/>";
    $md52=$result_array[0]->md5;
    //echo $md52."<br/>";
    if($md51==$md52)
    {
        $str="{\"VValue\":\"true\",\"query_time\":\"".date("Y-m-d",$result_array[0]->query_time)."\"}";
    }
    else
    {
        $domain_name=$DI->getDomainName();
//echo $domain_name."<br />";

        $registrar=$DI->getRregistrar();
//echo $registrar."<br />";

        $whois_server=$DI->getWhoisServer();
//echo $whois_server."<br />";

        $name_server=$DI->getNameservers();

        $status=$DI->getStatus();
//echo $status."<br />";

        $updated_date=$DI->getUpdatedDate();
//echo $updated_date."<br />";

        $creation_date=$DI->getCreationDate();
//echo $creation_date."<br />";

        $expiration_date=$DI->getExpirationDate();
//echo $expiration_date."<br />";

        $owner_name= $DI->getOwnerName();

        $owner_organization=$DI->getOwnerOrganization();

        $owner_email=$DI->getOwnerEmail();
        $sql="INSERT INTO `domain_info`\n"
        ."(`domain_name`, `owner_email`, `owner_name`, `owner_organization`, `registrar`, `whois_server`, `creation_date`, `expiration_date`, `updated_date`, `status`, `nameservers`, `md5`, `query_time`) \n"
        ."VALUES (\"".$domain_name."\",\"".$owner_email."\",\"".$owner_name."\",\"".$owner_organization."\",\"".$registrar."\",\"".$whois_server."\",".strtotime($creation_date).",".strtotime($expiration_date).",".strtotime($updated_date).",\"".$status."\",\"".$nameservers."\",\"".$md51."\",".time().")";
        $id=$db->insert_id($sql);
        //echo "id=".$id;
        if($id>0)
        {

            $sql="INSERT INTO `registrar_info`(`id`, `content`) VALUES (".$id.",\"".$rawdata_array[1]."\")";  //注册商
            $db->query($sql);
            echo "<br/>".$sql;
            $sql="INSERT INTO `registry_info`(`id`, `content`) VALUES (".$id.",\"".$rawdata_array[0]."\")";  //注册局
            $db->query($sql);
            echo "<br/>".$sql;
        }
        $str="{\"VValue\":\"false\",\"query_time\":-2}";
    }

}
else
{
    $str="{\"VValue\":\"false\",\"query_time\":-1}";

}

echo $str;

