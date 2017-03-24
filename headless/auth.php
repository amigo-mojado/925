<?php
//init vars:
if(!array_key_exists('admin_token',$_SESSION)){
    $_SESSION['admin_token'] = '';
}
if(!array_key_exists('customer_token',$_SESSION)){
    $_SESSION['customer_token'] = '';
}

if(!array_key_exists('customer_cart',$_SESSION)){
    $_SESSION['customer_cart'] = '';
}

if(!array_key_exists('mine_cart',$_SESSION)){
    $_SESSION['mine_cart'] = '';
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

//auth customer:
if(empty($_SESSION['customer_token'])){
    $userData = ["username" => $config['customer_user'], "password" => $config['customer_password']];
    $ch = curl_init($config['host']."/V1/integration/customer/token");
    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($userData));
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, array("Content-Type: application/json", "Content-Lenght: " . strlen(json_encode($userData))));
    curl_setopt($ch, CURLOPT_NOPROXY,'192.168.56.222,spielwiese.local');
    $token = curl_exec($ch);
    $_SESSION['customer_token'] = str_replace('"','',$token);
    $messages = messages($ch,$token,$messages);

    curl_close($ch);
}

if(empty($_SESSION['customer_cart'])){

    $ch = curl_init($config['host']."/V1/guest-carts/");
    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
    curl_setopt( $ch, CURLOPT_POSTFIELDS, json_encode(array('customerId' => 1 )));
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, array("Content-Type: application/json", "Authorization: Bearer " . $_SESSION['admin_token']));
    curl_setopt($ch, CURLOPT_NOPROXY,'192.168.56.222,spielwiese.local');
    $token = curl_exec($ch);
    $_SESSION['customer_cart'] = str_replace('"','',$token);
    $messages = messages($ch,$token,$messages);

    curl_close($ch);
}

if(empty($_SESSION['mine_cart'])){
    $ch = curl_init($config['host']."/V1/carts/mine");
    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
    curl_setopt( $ch, CURLOPT_POSTFIELDS, json_encode(array('customerId' => 1 )));
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, array("Content-Type: application/json", "Authorization: Bearer " . $_SESSION['customer_token']));
    curl_setopt($ch, CURLOPT_NOPROXY,'192.168.56.222,spielwiese.local');
    $token = curl_exec($ch);
    $_SESSION['mine_cart'] = str_replace('"','',$token);
    $messages = messages($ch,$token,$messages);

    curl_close($ch);
}


