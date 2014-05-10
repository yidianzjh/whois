<script src="<?php echo $js_path ?>searchDomain2.js"></script>
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
                    <div id="sectionID" style="display:none"></div>
                    <div id="isMoreInfo" style="display:none">0</div>
                    <div id="regstatus" style="display:none"></div>
                    <div id="whoisServer" style="display:none"></div>

                    <!-- 某一时期的whois信息 -->
                    <div style="display:none" id="msgStatus"></div>
                    <div style="display:none" id="newStatus">2</div>
                    <div class="fxTitle">
                        <p class="bdColor">
                            <span id="domain"><?php echo $domain_name ?></span>
                            <span>在<?php echo $query_time ?>的Whois信息</span>
                        </p>
                    </div>
                    <table cellspacing="0" cellpadding="0" width="100%" class="wsResult">
                        <col width="120"/>
                        <tr>
                            <td class="wsTitle"><span class="nameico"></span>域名</td>
                            <td>
                                <span><?php echo $domain_name ?></span>
                                <a href="javascript:void(0)" name="counter" class="fc_link" style="display:">[whois反查]</a>
                            </td>
                        </tr>
                        <tr>
                            <td class="wsTitle"><span class="mailbox"></span>邮箱</td>
                            <td>
                                <span><?php echo $owner_email ?></span>
                                <a href="javascript:void(0)" name="counter" class="fc_link" style="display:">[whois反查]</a>
                            </td>
                        </tr>
                        <tr>
                            <td class="wsTitle"><span class="contact"></span>联系人</td>
                            <td>
                                <span id="test"><?php echo $owner_name ?></span>
                                <a href="javascript:void(0)" name="counter" class="fc_link" style="display:">[whois反查]</a>
                            </td>
                        </tr>
                        <tr>
                            <td class="wsTitle"><span class="regiter"></span>注册者</td>
                            <td>
                                <span><?php echo $owner_organization ?></span>
                                <a href="javascript:void(0)" name="counter" class="fc_link" style="display:">[whois反查]</a>
                            </td>
                        </tr>
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
                        <tr>
                            <td class="wsTitle"><span class="styls"></span>状态</td>
                            <td colspan="2">
                                <?php echo $status ?></td>
                        </tr>
                        <tr>
                            <td class="wsTitle"><span class="dns"></span>DNS</td>
                            <td colspan="2">
                                <?php echo $nameserver ?></td>
                        </tr>
                        <tr>
                            <td colspan="2" style="font-weight:bold;color:black;display:;" ><a href="javascript:void(0)" id="whoisMore" class='fc_link'>点击展开注册信息</a></td>
                        </tr>
                        <tr>
                            <td colspan="2" id="whoisDetail">
                                <div style="display:none">
                                <?php
                                    preg_match_all('/(?P<rawdata>.+?)[\r\n]/',$rawdata,$matches);
                                    foreach ($matches['rawdata'] as $tmp)
                                    {
                                        echo $tmp . '<br/>';
                                    }

                                ?>
                                 </div>
                            </td>
                        </tr>
                    </table>
                </div>
            </div>
            <dl class="wsHistory">
                <dt id="historyInfo">历史记录<span style="color:#cccccc">[勾选可对比]</span></dt>
            </dl>
        </div>
        <div class="bdline"></div>
    </div>
</div>
<!--主体结束-->
