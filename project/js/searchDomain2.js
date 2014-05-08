//获取最新的whois字段信息
function getNewDomainStr(){
    var str = '';
    var regorg = $("#regorg").length> 0 ? ($("#regorg").html()!="暂无数据"  ? $("#regorg").html():"") : $(".regiter").parent().next('td').children('span').text();
    var email = $("#email").length>0 ? ($("#email").html()!="暂无数据" ? $("#email").html():"") : $(".mailbox").parent('td').next('td').children('span').html();
    var regname = $("#regname").length>0 ? ($("#regname").html()!="暂无数据" ? $("#regname").html():"") : $(".contact").parent('td').next('td').children('span').html();
    var regtime = $(".regtime").parent('td').next('td').text();
    var pastime = $(".pastime").parent().next('td').text();
    var update = $(".update").parent('td').next('td').text();
    var styls = $("#status").html();
    var dns = $("#nameserver").html();
    var company = $(".company").parent('td').next('td').html();
    var regstatus = $("#regstatus").html();
    var whoisserver = $("#whoisServer").html();
    str += regstatus;
    str += dns;
    str += styls;
    str += whoisserver;
    str += company;
    str += email;
    str += regname;
    str += regorg;
    str += regtime;
    str += pastime;
    str += update;
    return str;
}

//判断最新的whois信息和历史记录的最新一条是否一致
function isequal(domainName){
    $.ajax({
        type:"GET",
        url:"/domain/getNewHistory",
        data:{domainName:domainName},
        dataType:"json",
        success:function(data){
            var domainstr = getNewDomainStr();
            var regStatus = $("#regstatus").length ? $("#regstatus").html():"";
            var newestTime = $("label[for=\"his_0\"]").length>0 ? $("label[for=\"his_0\"]").html():"";
            if(regStatus.length>0 && newestTime!="" && domainstr.indexOf(data)==0 && data.indexOf(domainstr)==0 ){
                var pobj  = $("p[class=\"bdColor\"]").eq(0);
                $("<span>[最新whois信息与"+newestTime+"的记录相同]</span>").insertAfter(pobj);
            }
        }
    });
}


function getHistoryInfo(domainName){
    var hisTitle = $("#historyInfo");
    //延时ajax请求，保证历史信息里是最新的记录
    var dt =setTimeout(function(){
        $.ajax({
            type:"GET",
            url:"getHistoryInfoByAjax.php",
            data:{domainName:domainName},
            dataType:"json",
            success:function(data){
                $(data).insertAfter(hisTitle);
                isequal(domainName);
                if($(":checkbox:checked").length==2){
                    $(":checkbox:checked:last").siblings("span:eq(0)").css('display',"block");
                    $(":checkbox:not(':checked')").attr("disabled",true).css({opacity:0.2});
                }
                var his = $("[name=\"history[]\"]");
                if(his.length>10){
                    $("[name=\"history[]\"]:gt(9)").parent('dd').css('display','none');
                    $("#mcheck").css('display','');
                }else{
                    $("#mcheck").css('display','none');
                }

                $("#mcheck").click(function(){
                    var vcheckbox = $(":checkbox:visible");
                    var ll = vcheckbox.length+10;
                    $("[name=\"history[]\"]:lt("+ll+")").parent('dd').css('display','');
                    if(ll<his.length){
                        $("#mcheck").html("更多");
                    }else{
                        $("#mcheck").hide();
                    }
                });

                //每一个checkbox操作
                his.each(function(){
                    $(this).click(function(){
                        $(this).attr("checked",this.checked);
                        $(this).siblings("span").css("display","none");
                        var checklong = $(":checkbox:checked").length;
                        if(checklong==2){
                            $(this).siblings("span.contrasBtn").css("display","block");
                        }else{
                            $("[name=\"history[]\"] ~ span").css('display','none');
                        }
                        if(checklong>=2){
                            $(":checkbox:not(':checked')").attr("disabled",true).css({opacity:0.2});
                        }else{
                            $(":checkbox:not(':checked')").attr("disabled",false).css({opacity:1});
                        }
                    });
                });

                //显示查看按钮
                $('dl.wsHistory dd').hover(function(){
                        if($(":checkbox:checked").length!=2){
                            $(this).find('.see_whois').show();
                        }
                    },function(){
                        $(this).find('.see_whois').hide();
                    }
                );

                //查看某一时期的whois信息-----从历史记录来
                $("span.see_whois").click(function(){
                    var regstatus = $("#regstatus").html() ? $("#regstatus").html() : $("#newStatus").html();
                    var historyId = $(this).attr("historyId");
                    var indexKey = $(this).siblings('input:eq(0)').attr('id').slice(4);
                    var domainId = $(this).attr('domainId')+regstatus;
                    window.open("/domain/getwhoisbyhistoryid?domainName=" + domainName +"&domainId="+domainId+"&indexKey="+indexKey+ "&whoisHistoryId=" + historyId,'_self', '');
                });

                //显示查看按钮
                $("span.contrasBtn").click(
                    function() {
                        var historyId1 = $(this).attr("historyId");
                        var hisid1 = $(this).siblings('input:eq(0)').attr('id').slice(4);
                        var historyId2 = $(this).parent().siblings("dd").children("input.hisInput:checked").siblings("span.contrasBtn").attr("historyId");
                        var hisid2 = $(this).parent().siblings("dd").children("input.hisInput:checked").attr("id").slice(4);
                        historyId1 = historyId1 + "_" + hisid1;
                        historyId2 = historyId2 + "_" + hisid2;

                        if (hisid1 > hisid2) {
                            var olderId = historyId1;
                            var newerId = historyId2;
                        } else {
                            var olderId = historyId2;
                            var newerId = historyId1;
                        }
                        window.open("/domain/diffhistory?olderId=" + olderId+ "&newerId=" + newerId + "&domainName=" + domainName,'_self', '');
                    }
                )
            }
        });
    },800);
}

$(function() {
    var domainName = $("#domain").html();
    document.title = "域名"+domainName +"Whois查询,域名"+domainName+"注册信息查询,域名"+domainName+"网站信息查询  CXW.COM";
    $('label[for="schwhois"]').trigger('click');
    var regstatus = $("#regtatus").html();
    if(regstatus ==1){
        $("span[name=\"counter\"]").css('display','none');
    }

    //获取详细信息里的邮箱注册人注册组织字段值
    var sectionID = $("#sectionID").length ? $("#sectionID").html() : "";
    var email = $("#email");
    var regorg = $("#regorg");
    var regname = $("#regname");
    var more = $("#more");
    var moreInfo = $("#moreInfo").children('div');
    if(more.length>0){
        more.parent('td').parent('tr').next('tr').hide();
        more.click(function(){
            if(moreInfo.length>0){
                clickText = moreInfo.is(":hidden") ? '点击收起注册信息' :'点击展开注册信息';
                moreInfo.toggle();
                more.text(clickText);
                more.parent('td').parent('tr').next('tr').toggle();
            }else{
                moreInfo.html();
            }
        });
    }else{
        more.parent('td').parent('tr').next('tr').hide();
    }

    var isMoreInfo = $("#isMoreInfo").html();
    isMoreInfo=1;
    /*
    if(isMoreInfo == 1)
    {

        email.show().html("<img id='loading' src='/images/ajax_load.gif' border='0' />");
        regorg.show().html("<img id='loading' src='/images/ajax_load.gif' border='0' />");
        regname.show().html("<img id='loading' src='/images/ajax_load.gif' border='0' />");
        $.ajax({
            type : "GET",
            url : "/getMoreDetail.php",
            data : {domainName : domainName,sectionID:sectionID},
            dataType : "json",
            timeout:100000,
            success : function(data) {
                var returnEmail = data.Email?data.Email:"暂无数据";
                var returnRegorg= data.RegOrg?data.RegOrg:"暂无数据";
                var returnRegname = data.Regname?data.Regname:"暂无数据";
                var returnMore = data.Detail?data.Detail:"暂无数据";
                email.show().html(returnEmail);
                if(returnEmail!="" && returnEmail!="暂无数据")
                    email.siblings('a').css('display','');
                regorg.show().html(returnRegorg);
                if(returnRegorg!="" && returnRegorg!="暂无数据")
                    regorg.siblings('a').css('display','');
                regname.show().html(returnRegname);
                if(returnRegname!="" && returnRegname!="暂无数据")
                    regname.siblings('a').css('display','');
                if(returnMore!='' && returnMore!='暂无数据'){
                    more.show();
                    moreInfo.html(returnMore).hide();
                }
                //get the historyInfo
                getHistoryInfo(domainName);
            },
            error: function(XMLHttpRequest, textStatus, errorThrown) {
                if(email.text().length == 0)
                    email.html('暂无数据');
                if(regorg.text().length == 0)
                    regorg.html('暂无数据');
                if(regname.text().length ==0)
                    regname.html('暂无数据');
                //get the historyInfo
                getHistoryInfo(domainName);

            }
        });
    }else{
        getHistoryInfo(domainName);
    }
    */

    //获取收录情况
    var bd = $("#bd");
    var gg = $("#gg");
    var sg = $("#sg");
    var so = $("#so");
    bd.html("<img id='loading' src='/images/ajax_load.gif' border='0' />");
    gg.html("<img id='loading' src='/images/ajax_load.gif' border='0' />");
    sg.html("<img id='loading' src='/images/ajax_load.gif' border='0' />");
    so.html("<img id='loading' src='/images/ajax_load.gif' border='0' />");
    $.ajax({
        type : "GET",
        url : "/domain/getSiteInfo",
        data : {domainName:domainName},
        timeout : 100000,
        dataType : "json",
        success : function(data){
            bd.html("<a href="+data.bd+" target='blank_'>"+data.bd_sl+"</a>");
            gg.html("<a href="+data.gg+" target='blank_'>"+data.gg_sl+"</a>");
            sg.html("<a href="+data.sg+" target='blank_'>"+data.sg_sl+"</a>");
            so.html("<a href="+data.so+" target='blank_'>"+data.so_sl+"</a>");
        },
        error : function(XMLHttpRequest, textStatus, errorThrown){
            bd.html("0");
            gg.html("0");
            sg.html("0");
            so.html("0");
        }
    });
    //反查
    $("a[name=\"counter\"]").click(function(){
        var cvalue = $(this).siblings("span").html();
        $(this).attr("href","/domain/countercheckdomain?key="+cvalue);
    });

    //反查去掉空值
    var whoisCounter = $("a[name=\"counter\"]").siblings('span');
    if(whoisCounter.html()==""||whoisCounter=="暂无数据"){
        $("a[name=\"counter\"]").css('display','none');
    }

    $("#whoisMore").click(function(){
        $("#whoisDetail").children('div').toggle();
        $("#whoisDetail").children('div').is(":hidden") ? $(this).text('点击展开注册信息') :$(this).text('点击收起注册信息') ;
    });

    //判断域名的注册状态
    var msgStatus = $("#msgStatus");
    if(msgStatus.length>0){
        var regObj = $("<span></span>");
        if(msgStatus.html() == 1)
            regObj.html("(<a href=https://www.ename.net/domain/index/"+domainName+ " target='_blank'>未注册，点击立即注册</a>)").css('color','red');
        else if(msgStatus.html() == 2)
            regObj.html("(未注册)").css('color','red');
        else if(msgStatus.html() == 3)
            regObj.html("(被保留)").css('color','red');
        regObj.insertAfter($("#domain").siblings("span"));
    }
})