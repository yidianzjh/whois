<?php
require_once 'DomainParser/Parser.php';
require_once 'WhoisParser/Parser.php';

$Parser = new Novutec\WhoisParser\Parser();

$result = $Parser->lookup('baidu.com');

var_dump($result);