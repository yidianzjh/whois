<?php
include "head.php";
?>
<?php
if(isset($_GET["olderId"]) && isset($_GET["newerId"]))
{
    require_once 'tableOperate.php';
    $dt=new domain_info();
    $id=array();
    $id[0]=$_GET["newerId"];
    $id[1]=$_GET["olderId"];
    for($i=0;$i<2;$i++)
    {
        $dt->queryById($id[$i]);
        $domain_name[$i]=$dt->getDomainName();
//echo $domain_name."<br />";

        $registrar[$i]=$dt->getRregistrar();
//echo $registrar."<br />";

        $whois_server[$i]=$dt->getWhoisServer();
//echo $whois_server."<br />";

        $name_server[$i]=$dt->getNameservers();

        $status[$i]=$dt->getStatus();
//echo $status."<br />";

        $updated_date[$i]=$dt->getUpdatedDate();
//echo $updated_date."<br />";

        $creation_date[$i]=$dt->getCreationDate();
//echo $creation_date."<br />";

        $expiration_date[$i]=$dt->getExpirationDate();
//echo $expiration_date."<br />";

        $owner_name[$i]= $dt->getOwnerName();

        $owner_organization[$i]=$dt->getOwnerOrganization();

        $owner_email[$i]=$dt->getOwnerEmail();

        $query_time[$i]=$dt->getQueryTime();
    }

    if($domain_name[0] != $_GET["domainName"] || $domain_name[1] != $_GET["domainName"])
    {
?>

<script src="/static/js/error.js"></script>

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
                    <input id="schBtn" type="button" class="con_schBtn" />
                </div>
            </div>
        </div>
    </div>
    <div class="conMain">
        <div class="errorPage">
            <div style="display:none" id="domain"></div>
            <div style="display:none" id="iswhoissch"></div>
            <div style="display:none" id="returnUrl"></div>
            <h1 class="errorTitle"><p class="errorColor"><a href="">查询网</a><span>></span><span>提示信息</span></p></h1>
            <p class="errorMsg">
                [
                <a id="goback" href="#" style="color:#0399d7;padding:30px 0;text-align:center;text-decoration:none">
                    没有该域名的历史信息						点此返回</a>

                ]<br/>
            </p>
        </div>
        <div class="bdline"></div>
    </div>
</div>
<!--主体结束-->

<?php

    }

?>
<script src="/js/diffHistoryDomain.js"></script>
<!--主体开始-->
<div class="content">
    <div class="contontTop">
        <div class="logosch">
            <a href="/" class="contlogo">查询网</a>
            <div class="con_searchBox">
                <p class="chooseR"><input type="radio" name="schwhois" id="schwhois" checked/><label for="schwhois" class="active">查询whois</label></p>
                <p class="chooseR"><input type="radio" name="schwhois" id="whoissch" /><label for="whoissch">whois反查</label></p>
                <div class="con_searchInput">
                    <input id="key" type="text" class="con_inputBox" value="请输入要查询的域名"   />
                    <input id="schBtn" type="button" class="con_schBtn"/>
                </div>
            </div>
        </div>
    </div>
    <div class="conMain">
        <div class="wsBox">
            <div class="whoisBox">
                <div class="wsMsg">
                    <div class="fxTitle"><p class="bdColor">域名：<span id="domain"><?php echo $_GET["domainName"] ?></span><span>历史Whois信息对比</span></p></div>
                    <div style="display:none" id="regstatus">2</div>
                    <table cellspacing="0" cellpadding="0" width="100%" class="wsResult">
                        <tbody>
                        <tr>
                            <td class="wsTitle"><span class="contrastime"></span>时间</td>
                            <td class="time2"><?php echo $query_time[0] ?></td>
                            <td class="time1"><?php echo $query_time[1] ?></td>
                        </tr>
                        <tr>
                            <td class='wsTitle' <?php if($owner_email[0] != $owner_email[1]){echo "style='color:red'";} ?> ><span class='mailbox'></span>邮箱</td>
                            <td class="time2"><?php echo $owner_email[0] ?></td>
                            <td class="time1"><?php echo $owner_email[1] ?></td>
                        </tr>
                        <tr>
                            <td class='wsTitle' <?php if($owner_name[0] != $owner_name[1]){echo "style='color:red'";} ?> ><span class='contact'></span>联系人</td>
                            <td class="time2"><?php echo $owner_name[0] ?></td>
                            <td class="time1"><?php echo $owner_name[1] ?></td>
                        </tr>
                        <tr>
                            <td class='wsTitle' <?php if($$owner_organization[0] != $$owner_organization[1]){echo "style='color:red'";} ?> ><span class='regiter'></span>注册者</td>
                            <td class="time2"><?php echo $owner_organization[0] ?></td>
                            <td class="time2"><?php echo $owner_organization[1] ?></td>
                        </tr>
                        <tr>

                            <td class='wsTitle' <?php if($registrar[0] != $registrar[1]){echo "style='color:red'";} ?> ><span class='company'></span>注册商</td>
                            <td class="time2"><?php echo $registrar[0] ?></td>
                            <td class="time1"><?php echo $registrar[1] ?></td>
                        </tr>
                        <tr>
                            <td class='wsTitle' <?php if($creation_date[0] != $creation_date[1]){echo "style='color:red'";} ?> ><span class='regtime'></span>注册时间</td>
                            <td class="time2"><?php echo $creation_date[0] ?></td>
                            <td class="time1"><?php echo $creation_date[1] ?></td>
                        </tr>
                        <tr>
                            <td class='wsTitle' <?php if($expiration_date[0] != $expiration_date[1]){echo "style='color:red'";} ?> style='color:red'><span class='pastime'></span>过期时间</td>
                            <td class="time2"><?php echo $expiration_date[0] ?></td>
                            <td class="time1"><?php echo $expiration_date[1] ?></td>
                        </tr>
                        <tr>
                            <td class='wsTitle' <?php if($updated_date[0] != $updated_date[1]){echo "style='color:red'";} ?> style='color:red'><span class='update'></span>更新时间</td>
                            <td class="time2"><?php echo $updated_date[0] ?></td>
                            <td class="time1"><?php echo $updated_date[1] ?></td>
                        </tr>
                        <tr>
                            <td class='wsTitle' <?php if($status[0] != $status[1]){echo "style='color:red'";} ?> ><span class='styls'></span>状态</td>
                            <td class="time2">
                                <?php echo $status[0] ?>							</td>
                            <td class="time1">
                                <?php echo $status[1] ?>							</td>
                        </tr>
                        <tr>
                            <td class='wsTitle' <?php if($name_server[0] != $name_server[1]){echo "style='color:red'";} ?> ><span class='dns'></span>DNS</td>
                            <td class="time2">
                                <?php echo $name_server[0] ?>								</td>
                            <td class="time1">
                                <?php echo $name_server[0] ?>								</td>
                        </tr>
                        </tbody>
                    </table>
                </div>
            </div>
            <dl class="wsHistory">
                <dt>域名whois历史记录</dt>
                <?php include "getHistoryInfoByAjax.php"; ?>
            </dl>
        </div>
        <div class="bdline"></div>
    </div>
</div>
<!--主体结束-->
<?php
}
else
{
?>
    <script src="/static/js/error.js"></script>

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
                        <input id="schBtn" type="button" class="con_schBtn" />
                    </div>
                </div>
            </div>
        </div>
        <div class="conMain">
            <div class="errorPage">
                <div style="display:none" id="domain"></div>
                <div style="display:none" id="iswhoissch"></div>
                <div style="display:none" id="returnUrl"></div>
                <h1 class="errorTitle"><p class="errorColor"><a href="">查询网</a><span>></span><span>提示信息</span></p></h1>
                <p class="errorMsg">
                    [
                    <a id="goback" href="#" style="color:#0399d7;padding:30px 0;text-align:center;text-decoration:none">
                        必须输入对比记录ID						点此返回</a>

                    ]<br/>
                </p>
            </div>
            <div class="bdline"></div>
        </div>
    </div>
    <!--主体结束-->
<?php
}

include "end.php";
?>
