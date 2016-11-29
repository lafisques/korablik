<?php

require_once 'Replacer.php';
require_once 'InverseReplacer.php';
require_once 'LoggedReplacer.php';

$url = "http://vm-da497948.netangels.ru/digits";
$replacer = LoggedReplacer::build($url);

$replacer->fetchURL();
$opData = $replacer->replaceByArray([
    ['search' => '50', 'replace' => '333'],
    ['search' => '1', 'replace' => '5'],
    ['search' => 'leo', 'replace' => 'лео'],
    ['search' => 'ultrices', 'replace' => 'слово'],
    ['search' => 'Mauris', 'replace' => 'Маурис'],
]);
$replacer->printResult();
//$replacer->printStackTrace();
//$replacer->clearStackTrace();

$replacer = Replacer::build($url, true);
$replacer->setData($opData);
$opData = $replacer->replaceByArray([
    ['search' => '50', 'replace' => '333'],
    ['search' => '1', 'replace' => '5'],
    ['search' => 'leo', 'replace' => 'лео'],
    ['search' => 'ultrices', 'replace' => 'слово'],
    ['search' => 'Mauris', 'replace' => 'Маурис'],
]);
$replacer->printResult();