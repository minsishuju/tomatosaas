<?php
namespace think;
if (PHP_VERSION_ID < 80100) {
    die("Your php version is " . phpversion() . '. Please upgrade php version to 8.1+');
}
require __DIR__ . '/../vendor/autoload.php';

// 执行HTTP应用并响应
$http = (new App())->http;

$response = $http->name('admin')->run();

$response->send();

$http->end($response);
