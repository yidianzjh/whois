$(function(){
    var domain = $("#domain").html();
    var iswhoissch = $("#iswhoissch").html();
    var tvalue = iswhoissch.length>0 ? "域名" + domain + "Whois反查信息  CXW.COM":"域名" + domain + "Whois查询,域名" + domain+ "注册信息查询  CXW.COM";
    document.title = tvalue;

    if(iswhoissch==1){
        $("#whoissch").attr("checked",true);
        $("#schwhois").attr("checked",false);
        $("label[for=\"whoissch\"]").addClass('active').trigger('click');
    }

    $("#goback").click(function(){
        if($("#returnUrl").html()==1){
            window.location.href="/";
        }else{
            history.go(-1);
            return false;
        }
    });
})