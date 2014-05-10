$(function(){
    var domainName = $("#domain").html();
    document.title = "域名"+domainName+"历史信息查询,变更记录查询  CXW.COM";
    $('label[for="schwhois"]').trigger('click');


    //火狐下会记住选择
    if($(":checkbox:checked").length==2){
        $(":checkbox:checked:last").siblings("span:eq(0)").css('display',"block");
        $(":checkbox:not(':checked')").attr("disabled",true).css({opacity:0.2});
    }

    //默认显示10条
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
        window.open("/index.php/get_whois_by_history_id/index/" + domainName + "/" +domainId+ "/" +indexKey+ "/" + historyId, '_self', '');
    });



    $("span.contrasBtn").click(function(){
        var historyId1 = $(this).attr("historyId");
        var hisid1 = $(this).siblings('input:eq(0)').attr('id').slice(4);
        var historyId2 = $(this).parent().siblings("dd").children("input.hisInput:checked").siblings("span.contrasBtn").attr("historyId");
        var hisid2 =  $(this).parent('dd').siblings("dd").children("input.hisInput:checked").attr("id").slice(4);
        historyId1 = historyId1+"_"+hisid1;
        historyId2 = historyId2+"_"+hisid2;

        if (hisid1 > hisid2) {
            var olderId = historyId1;
            var newerId = historyId2;
        }else{
            var olderId = historyId2;
            var newerId = historyId1;
        }
        window.open("/index.php/diff_history/index/"+olderId+"/"+newerId+"/"+domainName,'_self','');
    })

    $("td[style]").siblings('td').css('color','red');

    var regstatus = $("#regstatus");
    if(regstatus.length>0 && regstatus.html() == 1){
        $("td").css("color","");
        $("td").removeAttr("style");
    }

    var errorStatus1 = $("td:contains('未注册')");
    var errorStatus2 = $("td:contains('被保留')");
    if(errorStatus1.length>0 || errorStatus2.length>0){
        $("td").css("color","");
        $("td").removeAttr("style");
    }











})