<script src="<?php echo $js_path ; ?>diff_history_domain.js"></script>
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
                    <div class="fxTitle"><p class="bdColor">域名：<span id="domain"><?php echo $domain_name; ?></span><span>历史Whois信息对比</span></p></div>
                    <div style="display:none" id="regstatus">2</div>
                    <table cellspacing="0" cellpadding="0" width="100%" class="wsResult">
                        <tbody>
                        <tr>
                            <td class="wsTitle"><span class="contrastime"></span>时间</td>
                            <td class="time2"><?php echo $newer['query_time']; ?></td>
                            <td class="time1"><?php echo $older['query_time'] ?></td>
                        </tr>
                        <tr>
                            <td class='wsTitle' <?php if($newer['owner_email'] != $older['owner_email']){echo "style='color:red'";} ?> ><span class='mailbox'></span>邮箱</td>
                            <td class="time2"><?php echo $newer['owner_email'] ?></td>
                            <td class="time1"><?php echo $older['owner_email'] ?></td>
                        </tr>
                        <tr>
                            <td class='wsTitle' <?php if($newer['owner_name'] != $older['owner_name']){echo "style='color:red'";} ?> ><span class='contact'></span>联系人</td>
                            <td class="time2"><?php echo $newer['owner_name'] ?></td>
                            <td class="time1"><?php echo $older['owner_name'] ?></td>
                        </tr>
                        <tr>
                            <td class='wsTitle' <?php if($newer['owner_organization'] != $older['owner_organization']){echo "style='color:red'";} ?> ><span class='regiter'></span>注册者</td>
                            <td class="time2"><?php echo $newer['owner_organization'] ?></td>
                            <td class="time2"><?php echo $older['owner_organization'] ?></td>
                        </tr>
                        <tr>

                            <td class='wsTitle' <?php if($newer['registrar'] != $older['registrar']){echo "style='color:red'";} ?> ><span class='company'></span>注册商</td>
                            <td class="time2"><?php echo $newer['registrar'] ?></td>
                            <td class="time1"><?php echo $older['registrar'] ?></td>
                        </tr>
                        <tr>
                            <td class='wsTitle' <?php if($newer['creation_date'] != $older['creation_date']){echo "style='color:red'";} ?> ><span class='regtime'></span>注册时间</td>
                            <td class="time2"><?php echo $newer['creation_date'] ?></td>
                            <td class="time1"><?php echo $older['creation_date'] ?></td>
                        </tr>
                        <tr>
                            <td class='wsTitle' <?php if($newer['expiration_date'] != $older['expiration_date']){echo "style='color:red'";} ?> <span class='pastime'></span>过期时间</td>
                            <td class="time2"><?php echo $newer['expiration_date'] ?></td>
                            <td class="time1"><?php echo $older['expiration_date'] ?></td>
                        </tr>
                        <tr>
                            <td class='wsTitle' <?php if($newer['updated_date'] != $older['updated_date']){echo "style='color:red'";} ?> <span class='update'></span>更新时间</td>
                            <td class="time2"><?php echo $newer['updated_date'] ?></td>
                            <td class="time1"><?php echo $older['updated_date'] ?></td>
                        </tr>
                        <tr>
                            <td class='wsTitle' <?php if($newer['status'] != $older['status']){echo "style='color:red'";} ?> ><span class='styls'></span>状态</td>
                            <td class="time2">
                                <?php echo $newer['status'] ?>							</td>
                            <td class="time1">
                                <?php echo $older['status'] ?>							</td>
                        </tr>
                        <tr>
                            <td class='wsTitle' <?php if($newer['nameserver'] != $older['nameserver']){echo "style='color:red'";} ?> ><span class='dns'></span>DNS</td>
                            <td class="time2">
                                <?php echo $newer['nameserver'] ?>								</td>
                            <td class="time1">
                                <?php echo $older['nameserver'] ?>								</td>
                        </tr>
                        </tbody>
                    </table>
                </div>
            </div>
            <dl class="wsHistory">
                <dt>域名whois历史记录</dt>
                <?php echo $history_info; ?>
            </dl>
        </div>
        <div class="bdline"></div>
    </div>
</div>
<!--主体结束-->