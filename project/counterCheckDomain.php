<?php
include "head.php";

?>

<script src="/js/counterCheckDomain.js"></script>
<?php
if(isset($_GET["key"]) && $_GET["key"]!="" && $_GET["key"]!=null)
{
    include 'counterCheckRight.php';
}
else
    include 'counterCheckError.php';
?>

<?php
include "end.php";
?>
