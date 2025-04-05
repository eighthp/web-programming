<?php
$str = 'a1b2c3';
$result = preg_replace_callback('/\d+/', function($matches) {
    $number = (int)$matches[0];
    $cube = $number ** 3;
    return (string)$cube;
}, $str);

echo $result;
?>