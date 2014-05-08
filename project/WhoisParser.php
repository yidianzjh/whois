<?php
require_once 'DomainParser/Parser.php';
require_once 'WhoisParser/Parser.php';

$Parser = new Novutec\WhoisParser\Parser();
$Parser->setFormat('object');
$result = $Parser->lookup('baidu.com');
//echo $result;
//echo $result->created;
var_dump($result);
?>
