<?php
require_once "../vendor/autoload.php";
$openApi = \OpenApi\Generator::scan([$_SERVER['DOCUMENT_ROOT'] . '/PizzeriaServer/src/Api']);
header('Content-Type: application/json');
echo $openApi->toJson();