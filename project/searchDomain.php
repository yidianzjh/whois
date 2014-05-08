<?php
include "head.php";
?>

<?php
if(isset($_GET["domainName"]) && $_GET["domainName"]!="")
{
    $_GET["domainName"]=strtolower($_GET["domainName"]);
    $str_empty=strripos($_GET["domainName"]," ");
    $substr_start=strripos($_GET["domainName"],".");
    if($substr_start=="" || $substr_start==0 || $str_empty!="")
    {
        $error_str="域名格式错误";
        include "searchError.php";
    }
    else
    {
        /*
        $substr=substr($_GET["domainName"],$substr_start+1);
        if(strcmp($substr,"com")!=0)
        {
            $error_str="暂不支持查询的后缀。";
            include "searchError.php";
        }
        else
        */
        {
            include "searchRight.php";
        }
    }

}
else
{
    $error_str="必须输入域名名称";
    include "searchError.php";
}
?>
<?php
include "end.php";
?>
