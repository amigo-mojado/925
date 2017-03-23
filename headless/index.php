<?php
session_start();
include('config.php');
include('auth.php');
include('categories.php');
include('products.php');
include('cart.php');
include('template.php');

function getData($host,$token,$encode = true){
    $ch = curl_init($host);
    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "GET");
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, array("Content-Type: application/json", "Authorization: Bearer " . $token));
    curl_setopt($ch, CURLOPT_NOPROXY,'192.168.56.222,spielwiese.local');

    $result = curl_exec($ch);

    if($encode == true){
        $result = json_decode($result);
    }

    return $result;
}