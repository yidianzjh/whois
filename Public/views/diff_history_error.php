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
                    <?php echo $error_message; ?></a>

                ]<br/>
            </p>
        </div>
        <div class="bdline"></div>
    </div>
</div>
<!--主体结束-->