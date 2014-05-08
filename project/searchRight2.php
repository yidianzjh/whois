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
//echo $domain_name."<br />";
preg_match_all('/(?P<name>Registrar): (?P<value>.+)/',$sub_data,$matches);
$registrar=$matches["value"][0];
//echo $registrar."<br />";
preg_match_all('/(?P<name>Whois Server): (?P<value>.+)/',$sub_data,$matches);
$whois_server=$matches["value"][0];
//echo $whois_server."<br />";
preg_match_all('/(?P<name>Referral URL): (?P<value>.+)/',$sub_data,$matches);
$referral_URL=$matches["value"][0];
//echo $referral_URL."<br />";
preg_match_all('/(?P<name>Name Server): (?P<value>.+)/',$sub_data,$matches);
foreach($matches["value"] as $tmp )
{
    $name_server.=$tmp."<br />";
}
//echo $name_server."<br />";
preg_match_all('/(?P<name>Status): (?P<value>.+)/',$sub_data,$matches);
foreach($matches["value"] as $tmp )
{
    $status.=$tmp."<br />";
}
//echo $status."<br />";
preg_match_all('/(?P<name>Updated Date): (?P<value>.+)/',$sub_data,$matches);
$updated_date=$matches["value"][0];
//echo $updated_date."<br />";
preg_match_all('/(?P<name>Creation Date): (?P<value>.+)/',$sub_data,$matches);
$creation_date=$matches["value"][0];
//echo $creation_date."<br />";
preg_match_all('/(?P<name>Expiration Date): (?P<value>.+)/',$sub_data,$matches);
$expiration_date=$matches["value"][0];
//echo $expiration_date."<br />";

?>



<script src="/js/searchDomain.js"></script>
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
        <div class="wsBox">
            <div class="whoisBox">
                <div class="wsMsg">
                    <!-- 域名注册 -->
                    <div id="sectionID" style="display:none">7186625</div>
                    <div id="isMoreInfo" style="display:none">1</div>
                    <div id="regstatus" style="display:none">2</div>
                    <div id="whoisServer" style="display:none">whois.markmonitor.com</div>

                    <div class="fxTitle">
                        <p class="bdColor">

                            <span id="domain"><?php echo $domain_name ?></span>
                            <span>的最新Whois信息</span>
                            <!-- 域名未注册 域名被保留  -->
                        </p>
                    </div>
                    <table cellspacing="0" cellpadding="0" width="100%" class="wsResult">
                        <col width="120"/>
                        <tr>
                            <td class="wsTitle"><span class="nameico"></span>域名</td>
                            <td>
                                <span><?php echo $domain_name ?></span>
                                <a href="javascript:void(0)" name="counter" class="fc_link">[whois反查]</a>
                            </td>
                        </tr>
                        <!-- more detail start -->
                        <tr>
                            <td class="wsTitle"><span class="mailbox"></span>邮箱</td>
                            <td>
                                <span id="email"></span>
                                <a href="javascript:void(0)" name="counter" class="fc_link" style="display:none">[whois反查]</a>
                            </td>
                        </tr>
                        <tr>
                            <td class="wsTitle"><span class="contact"></span>联系人</td>
                            <td><span id="regname"></span>
                                <a href="javascript:void(0)" name="counter" class="fc_link" style="display:none">[whois反查]</a>
                            </td>
                        </tr>
                        <tr>
                            <td class="wsTitle"><span class="regiter"></span>注册者</td>
                            <td><span id="regorg"></span>
                                <a href="javascript:void(0)" name="counter" class="fc_link" style="display:none">[whois反查]</a>
                            </td>
                        </tr>
                        <!-- more detail end -->


                        <tr>
                            <td class="wsTitle" ><span class="company"></span>注册商</td>
                            <td colspan="2"><?php echo $registrar ?></td>
                        </tr>

                        <tr>
                            <td class="wsTitle"><span class="regtime"></span>注册时间</td>
                            <td colspan="2"><?php echo $creation_date ?></td>
                        </tr>

                        <tr>
                            <td class="wsTitle"><span class="pastime"></span>过期时间</td>
                            <td colspan="2"><?php echo $expiration_date ?></td>
                        </tr>
                        <tr>
                            <td class="wsTitle"><span class="update"></span>更新时间</td>
                            <td colspan="2"><?php echo $updated_date ?></td>
                        </tr>
                        <div id="status" style="display:none">clientDeleteProhibited;clientTransferProhibited;clientUpdateProhibited;serverDeleteProhibited;serverTransferProhibited;serverUpdateProhibited;</div>
                        <tr>
                            <td class="wsTitle"><span class="styls"></span>状态</td>
                            <td colspan="2">
                                <?php echo $status ?></td>
                        </tr>

                        <div id="nameserver" style="display:none">DNS.BAIDU.COM;NS2.BAIDU.COM;NS3.BAIDU.COM;NS4.BAIDU.COM;</div>
                        <tr>
                            <td class="wsTitle"><span class="dns"></span>DNS</td>
                            <td colspan="2">
                                <?php echo $name_server ?></td>
                        </tr>
                        <tr>
                            <td colspan="2" style="font-weight:bold;colo:black;" ><a href="javascript:void(0)" id="more" class='fc_link' style="display:none;">点击展开注册信息</a></td>
                        </tr>
                        <tr>
                            <td colspan="2" id="moreInfo"><div style="display:none"></div></td>
                        </tr>
                    </table>

                    <!-- 某一时期的whois信息 -->
                </div>
                <table cellspacing="0" cellpadding="0" width="100%" class="seoMeg">
                    <tr>
                        <td class="fontbd">搜索引擎</td>
                        <td>百度</td>
                        <td>Google</td>
                        <td>搜狗</td>
                        <td>360搜索</td>
                    </tr>
                    <tr>
                        <td class="fontbd">收录信息</td>
                        <td id="bd"></td>
                        <td id="gg"></td>
                        <td id="sg"></td>
                        <td id="so"></td>
                    </tr>
                </table>
            </div>
            <dl class="wsHistory">
                <dt id="historyInfo">历史记录<span style="color:#cccccc">[勾选可对比]</span></dt>
            </dl>
        </div>
        <div class="bdline"></div>
    </div>
</div>
<!--主体结束-->