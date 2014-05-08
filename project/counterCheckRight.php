<?php
require_once 'tableOperate.php';
$dt=new domain_info();
$key=$_GET["key"];
$count=$dt->countByKey($key);
$page_num=$dt->pageByKey($key);
$page_size=$dt->getPageSize();
if(isset($_GET["page"]))
{
    $tmp_page=$_GET["page"];
    if(intval($tmp_page))
    {
        $dt->queryByKey($key,$tmp_page);
    }
    else
        $dt->queryByKey($key);
}
else
{
    $dt->queryByKey($key);
}

?>
<!--主体开始-->
<div class="content">
    <div class="contontTop">
        <div class="logosch">
            <a href="/" class="contlogo">查询网</a>
            <div class="con_searchBox">
                <p class="chooseR"><input type="radio" name="schwhois" id="schwhois" checked/><label for="schwhois" class="active">查询whois</label></p>
                <p class="chooseR"><input type="radio" name="schwhois" id="whoissch" /><label for="whoissch">whois反查</label></p>
                <div class="con_searchInput">
                    <input id="key" type="text" class="con_inputBox" value="请输入要查询的域名"  />
                    <input id="schBtn" type="button" class="con_schBtn"/>
                </div>
            </div>
        </div>
    </div>
    <div class="conMain">
        <div class="fxBox">
            <div class="fxTitle"><p class="bdColor">关键词：<span id="domain">
				<?php echo $key ?></span><span>反查域名</span>结果数量（<font color="#000"><?php echo $count ?></font>）</p></div>
            <table cellspacing="0" cellpadding="0" width="100%" class="fxResult nobreak">
                <tr class="headtb"><th>域名</th><th>注册商</th><th>注册者</th><th>注册时间</th><th>过期时间</th></tr>
<?php
$n=0;
while($dt->getNextRecord())
{
    echo "<tr><th><a href='/searchDomain.php?domainName=".$dt->getDomainName()."' target='_blank'>".$dt->getDomainName()."</a></th><th>".$dt->getRregistrar()."</th><th>".$dt->getOwnerOrganization()."</th><th>".$dt->getCreationDate()."</th><th>".$dt->getExpirationDate()."</th></tr>";
    $n++;
}
$page=0;
if(isset($_GET["page"]))
{
    if($_GET["page"]>$page_num)
        $page=$page_num;
    else
        $page= $_GET["page"];
}
?>
            </table>
            <div class="page_bx">共<?php echo $count ?>条记录,当前从<?php echo ($page*$page_size+1)."-".($page*$page_size+$n) ?>,本页显示<?php echo $n ?>条<b><?php echo ($page+1)."/".($page_num+1) ?></b>&nbsp;
<?php
if($page>0)
{
    echo "<a  href=\"/counterCheckDomain.php?key=".$key."&page=0\">首页</a>";
    echo "<a  href=\"/counterCheckDomain.php?key=".$key."&page=".($page-1)."\">上一页</a>";
}
for($i=0;$i<=$page_num;$i++)
{
    if($i==$page)
    {
        echo ($i+1);
    }
    else
    {
        echo "<a  href=\"/counterCheckDomain.php?key=".$key."&page=2\">".($i+1)."</a>";
    }
}

if($page<$page_num)
{
    echo "<a  href=\"/counterCheckDomain.php?key=".$key."&page=".($page+1)."\">下一页</a>";
    echo "<a  href=\"/counterCheckDomain.php?key=".$key."&page=".$page_num."\">末页</a>";

}
?>
        </div>
        <div class="bdline"></div>
    </div>
</div>
<!--主体结束-->
