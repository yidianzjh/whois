<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en">
<head>
    <meta http-equiv="Content-Type" content="text/html;charset=UTF-8" />
    <meta http-equiv="Content-Type" content="text/html;charset=UTF-8" />
    <title>——whois查询和whois反查的专业网站</title>
    <meta name="Keywords" content="whois反查,whois,whois查询,whois信息查询,whois查询工具,域名whois查询,域名查询工具,域名注册信息查询">
    <meta name="Description" content="我们提供最专业的whois查询、whois反查、历史whois对比、域名注册信息查询、域名whois查询工具等服务。">
    <link rel="icon" href="/images/favicon.ico" type="image/x-icon"/>
    <link rel="shortcut icon" href="/images/favicon.ico" type="image/x-icon"/>
    <link rel="stylesheet" href="/css/css.css" />
    <script src="/js/jquery-1.8.3.min.js"></script>
    <script src="/js/cxw.js"></script>
    <!--[if IE 6]>
    <script type="text/javascript" src="js/DD_belatedPNG.js" ></script>
    <script type="text/javascript">
        DD_belatedPNG.fix('*');
    </script>
    <![endif]-->

</head>
<body>
<!--头部开始-->
<div class="header">
    <div class="main">
        <a href="http://huodong.ename.net" target="_blank">活动</a><a href="http://www.ename.net" class="current">域名管理平台</a><a href="http://www.ename.com" target="_blank">域名交易平台</a><a href="http://www.ename.cn" target="_blank">域名门户</a><a href="http://www.dnbbs.com" target="_blank">域名论坛</a><a href="http://www.iidns.com" target="_blank">IIDNS</a><a href="http://www.cxw.com"  class="blodFt">查询网</a>
    </div>
</div>
<!--头部结束-->
<?php
require_once 'conn.php';
include 'getDomainInfo.php';


$DI=new domainInfo();
$domain_name=$DI->getDomainName();
echo "domainname=".$domain_name."<br />";
var_dump($DI->getAllData());
$registrar=$DI->getRegistrar();
echo "registrar=".$registrar."<br />";

$whois_server=$DI->getWhoisServer();
//echo $whois_server."<br />";

$name_server=$DI->getNameservers();

$status=$DI->getStatus();
echo "status=".$status."<br />";

$updated_date=$DI->getUpdatedDate();
//echo $updated_date."<br />";

$creation_date=$DI->getCreationDate();
//echo $creation_date."<br />";

$expiration_date=$DI->getExpirationDate();
//echo $expiration_date."<br />";

$owner_name= $DI->getOwnerName();

$owner_organization=$DI->getOwnerOrganization();

$owner_email=$DI->getOwnerEmail();

$rawdata=$DI->getRawdata();
$md51=md5($rawdata);
$sql="INSERT INTO `domain_info`\n"
    ."(`domain_name`, `owner_email`, `owner_name`, `owner_organization`, `registrar`, `whois_server`, `creation_date`, `expiration_date`, `updated_date`, `status`, `nameservers`, `md5`, `query_time`) \n"
    ."VALUES (\"".$domain_name."\",\"".$owner_email."\",\"".$owner_name."\",\"".$owner_organization."\",\"".$registrar."\",\"".$whois_server."\",".$creation_date.",".$expiration_date.",".$updated_date.",\"".$status."\",\"".$nameservers."\",\"".$md51."\",".time().")";
$mysqli->query($sql);

echo "</br>";
echo "</br>";
echo $mysqli->insert_id;
echo "</br>";
echo "</br>";
echo "</br>";
echo "</br>";
var_dump($result);
echo "</br>";
echo "</br>";
echo "</br>";
echo "</br>";

?>
<!--底部开始-->
<div class="footer">
    <p><a href="http://www.miitbeian.gov.cn" target="_blank">闽ICP备09007125号-2</a> 增值电信业务经营许可证[闽B2-20120071]  文网文[2011]0279-010<br/>
        Copyright © 2005-2013[易名中国] 厦门易名科技有限公司 eName Technology Co.,Ltd.</p>
</div>
<div class="count" style="display:none">
    <script type="text/javascript">
        var _bdhmProtocol = (("https:" == document.location.protocol) ? " https://" : " http://");
        document.write(unescape("%3Cscript src='" + _bdhmProtocol + "hm.baidu.com/h.js%3Faecc6bcab1c574d3cc1c6f8f19b6c283' type='text/javascript'%3E%3C/script%3E"));
    </script>
</div>
<!--底部结束-->
</body>
</html>