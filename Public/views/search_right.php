


<script src="<?php echo $js_path ?>searchDomain2.js"></script>
<script src="<?php echo $js_path ?>md5.js"></script>
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
                            <?php if(!$registered){echo "(<font color=\"red\"><a href='https://www.paomi.com/domain/reg?word=".$domain_name."' target='_blank'>未注册，点击立即注册</a></font>)";} ?>
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
                                <span id="email"><?php echo $owner_email ?></span>
                                <a href="javascript:void(0)" name="counter" class="fc_link"  style="display:none">[whois反查]</a>
                            </td>
                        </tr>
                        <tr>
                            <td class="wsTitle"><span class="contact"></span>联系人</td>
                            <td><span id="regname"><?php echo $owner_name ?></span>
                                <a href="javascript:void(0)" name="counter" class="fc_link" style="display:none">[whois反查]</a>
                            </td>
                        </tr>
                        <tr>
                            <td class="wsTitle"><span class="regiter"></span>注册者</td>
                            <td><span id="regorg"><?php echo $owner_organization ?></span>
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
                                <?php echo $nameserver ?></td>
                        </tr>
                        <tr>
                            <td colspan="2"><a href="javascript:void(0)" id="more" class='fc_link'>点击展开注册信息</a></td>
                        </tr>
                        <tr>
                            <td colspan="2" id="moreInfo"><div style="display:none"><?php echo $rawdata ?></div></td>
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