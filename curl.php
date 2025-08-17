<?php

$ch = curl_init(); 
curl_setopt($ch, CURLOPT_URL, "http://localhost:8018/wbms/helloworld.php");
curl_setopt($ch, CURLOPT_POST, true);
Curl_setopt($ch, CURLOPT_RETURNTRANSFER,true);
curl_setopt($ch, CURLOPT_POSTFIELDS, [
    "user" => "tpi",
    "password" => "12345"
]);

$result = curl_exec($ch);

print_r($result);