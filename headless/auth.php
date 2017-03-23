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

    if (!curl_errno($ch)) {
        switch ($http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE)) {
            case 200:  # OK
                $_SESSION['admin_token'] = str_replace('"','',$token);
                break;
            default:
                $errors[] = $token;
        }
    }
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

    if (!curl_errno($ch)) {
        switch ($http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE)) {
            case 200:  # OK
                $_SESSION['customer_token'] = str_replace('"','',$token);
                break;
            default:
                $errors[] = $token;
        }
    }
}
$customer =  getData($config['host'].'/V1/carts/mine/items',$_SESSION['customer_token']);
//print_r($customer);
if(!empty($_SESSION['customer_cart'])){
    $ch = curl_init($config['host']."/V1/guest-carts/");
    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
    curl_setopt( $ch, CURLOPT_POSTFIELDS, json_encode(array('customerId' => 1 )));
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, array("Content-Type: application/json", "Authorization: Bearer " . $_SESSION['admin_token']));
    curl_setopt($ch, CURLOPT_NOPROXY,'192.168.56.222,spielwiese.local');
    $token = curl_exec($ch);

    if (!curl_errno($ch)) {
        switch ($http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE)) {
            case 200:  # OK
                $_SESSION['customer_cart'] = str_replace('"','',$token);
                break;
            default:
                $errors[] = $token;
        }
    }
}



