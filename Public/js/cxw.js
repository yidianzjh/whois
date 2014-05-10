$(function(){
    $("#key").focus(function(){
        if($("#schwhois").is(":checked")){
            if($(this).val() == "请输入要查询的域名")	{
                $(this).val("");
            }
        }else{
            if($(this).val() == "请输入域名，邮箱或注册人"){
                $(this).val("");
            }
        }
    }).blur(function(){
            if($("#schwhois").is(":checked")){
                if($(this).val() == ""){
                    $(this).val("请输入要查询的域名");
                }
            }else{
                if($(this).val() == ""){
                    $(this).val("请输入域名，邮箱或注册人");
                }
            }
        }).keypress(function(e){
            if(e.keyCode==13)
                $("#schBtn").trigger('click');
        });

    $("#schBtn").click(function(){
        var re = /\?+/;
        if($("#schwhois").is(":checked")){
            if($("#key").val().replace(/(^\s*)|(\s*$)/g,"") == "请输入要查询的域名" || $("#key").val().replace(/(^\s*)|(\s*$)/g,"") == ""||$("#key").val().match(re)){
                $("#key").val("请输入要查询的域名");
                window.location.href="/";
            }else{
                window.open("/index.php/search_domain/index/"+$("#key").val(),'_self','');
            }
        }else{
            if($("#key").val().replace(/(^\s*)|(\s*$)/g,"") == "请输入域名，邮箱或注册人" || $("#key").val().replace(/(^\s*)|(\s*$)/g,"") == ""){
                $("#key").val("请输入域名，邮箱或注册人");
                window.location.href="/";
            }else{
                window.open("/index.php/counter_check_domain/index/"+$("#key").val(),'_self','');
            }
        }
    })

    $("span.chooseR").click(function(){
        if($(this).find("input").is("#schwhois")){
            if($("#key").val() == "请输入域名，邮箱或注册人"||$("#key").val() == ""){
                $("#key").val("请输入要查询的域名");
            }
        }else{
            if($("#key").val() == "请输入要查询的域名"||$("#key").val() == ""){
                $("#key").val("请输入域名，邮箱或注册人");
            }
        }
    })

    $(".chooseR").click(function(){
        if($(this).find("input").is(":checked")){
            $(this).find("label").addClass("text-info").parent().siblings().find("label").removeClass("text-info");
        }
    })

    $(".fxResult").find("tr:gt(0):odd").css("background","#f5f5f5");
    $(".wsResult").find("tr:even").css("background","#f5f5f5");
    $(".wsHistory").find("dd:last").css("border-bottom","none");

    var $backToTopTxt = "返回顶部";
    var $backToTopEle = $('<div class="backToTop"></div>')
        .appendTo($("body"))
        .text($backToTopTxt).attr("title", $backToTopTxt)
        .click(function() {
            $("html, body").animate({ scrollTop: 0 }, 120);
        });
    var $backToTopFun = function() {
        var st = $(document).scrollTop(), winh = $(window).height();
        (st > 0)? $backToTopEle.show(): $backToTopEle.hide();
        //IE6下的定位
        if (!window.XMLHttpRequest) {
            $backToTopEle.css("top", st + winh - 166);
        }
    };
    $(window).bind("scroll", $backToTopFun);
    $(function(){$backToTopFun();});
})