$(function() {
    var domain = $("#domain").html();
    var isMobile = $("#isMobile");
    document.title = isMobile.length>0 ? domain+'的反查结果-查询网': domain.replace(/(^\s*)|(\s*$)/g, "")+" Whois反查  CXW.COM";
    $("#whoissch").attr("checked",true);
    $("#schwhois").attr("checked",false);
    $("label[for=\"whoissch\"]").addClass('active').trigger('click');
})