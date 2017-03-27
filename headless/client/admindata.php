<?php
session_start();

include('../config.php');

if(!array_key_exists('admin_token',$_SESSION)){
    $_SESSION['admin_token'] = '';
}

//auth admin:
if(empty($_SESSION['admin_token'])){
    $userData = array("username" => $config['admin_user'], "password" => $config['admin_password']);
    $ch = curl_init($config['host']."/V1/integration/admin/token");
    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($userData));
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, array("Content-Type: application/json", "Content-Lenght: " . strlen(json_encode($userData))));
    curl_setopt($ch, CURLOPT_NOPROXY,'192.168.56.222,spielwiese.local');
    $token = curl_exec($ch);
    $_SESSION['admin_token'] = str_replace('"','',$token);
    $messages = messages($ch,$token,$messages);

    curl_close($ch);
}

if($_GET['categories']){
    header('Content-Type: application/json');
    $result = getData($config['host'].'/V1/categories',$_SESSION['admin_token'],false);
    echo $result;
}

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
