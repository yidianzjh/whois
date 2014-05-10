$(function() {

    $('.keyHelp').children('a').each(function() {
        $(this).hover(function(){
            $('.helpTXT_li').hide();
            $('#' + $(this).attr('class')).show();
        },function(){
            $('.helpTXT_li').hide();
        });
    });
})
