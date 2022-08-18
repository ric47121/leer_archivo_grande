<?php
header("Access-Control-Allow-Origin: *");
// header("Access-Control-Allow-Origin: https://taxi-a-ezeiza.com.ar");
// header("Access-Control-Allow-Origin: taxi-a-ezeiza.com.ar");
header('Access-Control-Allow-Methods: POST, GET, DELETE, PUT, PATCH, OPTIONS');
header('Access-Control-Allow-Headers: token, Content-Type');
header('Access-Control-Max-Age: 1728000');



$arr = [];
$arr['text'] = 'hola';

echo json_encode($arr);