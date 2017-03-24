<?php
session_start();

include('config.php');
include('auth.php');
include('categories.php');
include('products.php');
if($_GET['cart'] || $_GET['addToCart']){
    include('cart.php');
}
include('checkout.php');
//include('cms.php');
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

function postData($host,$token,$data_string,$messages,$json = false){

    $ch = curl_init();
    curl_setopt( $ch, CURLOPT_POST, '1');
    curl_setopt( $ch, CURLOPT_POSTFIELDS, $data_string);
    curl_setopt( $ch, CURLOPT_URL,$host);
    curl_setopt( $ch, CURLOPT_RETURNTRANSFER, true );
    curl_setopt($ch, CURLOPT_HTTPHEADER, array("Content-Type: application/json", "Authorization: Bearer " . $token));
    curl_setopt( $ch, CURLOPT_NOPROXY,'192.168.56.222,spielwiese.local');

    # Send request.
    $result = curl_exec($ch);

    $messages = messages($ch,$result,$messages);

    curl_close($ch);

    return $messages;
}

function messages($ch,$result,$messages){

    if (!curl_errno($ch)) {
        switch ($http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE)) {
            case 200:  # OK
                $messages['success'][] = $http_code." - ".$result;
                break;
            case 400:  # OK
                $messages['errors'][] = $result;
                break;
            default:
                $messages['errors'][] = $result;
        }
    }
    return $messages;
}

function _d($var){
    echo "<pre>\n";
    print_r($var);
    echo "</pre>\n";
}
function _dt($var){
    echo "<textarea>\n";
    print_r($var);
    echo "</textarea>\n";
}

