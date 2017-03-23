<?php
session_start();
$host = 'http://192.168.56.222/index.php/rest';
$html = array();
$userData = ["username" => "roni_cost@example.com", "password" => "roni_cost3@example.com"];
$ch = curl_init($host."/V1/integration/customer/token");
curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($userData));
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_HTTPHEADER, array("Content-Type: application/json", "Content-Lenght: " . strlen(json_encode($userData))));
curl_setopt($ch, CURLOPT_NOPROXY,'192.168.56.222,spielwiese.local');
$token = curl_exec($ch);

//Get customer info
$ch = curl_init($host."/V1/customers/me");
curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "GET"); // Get method
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_HTTPHEADER, array("Content-Type: application/json", "Authorization: Bearer " . json_decode($token)));
curl_setopt($ch, CURLOPT_NOPROXY,'192.168.56.222,spielwiese.local');
$result = curl_exec($ch);

$result = json_decode($result, 1);
echo '<pre>';print_r($result);

//Get customer info
$ch = curl_init($host."/V1/carts/mine");
curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "GET"); // Get method
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_HTTPHEADER, array("Content-Type: application/json", "Authorization: Bearer " . json_decode($token)));
curl_setopt($ch, CURLOPT_NOPROXY,'192.168.56.222,spielwiese.local');
$result = curl_exec($ch);

$result = json_decode($result, 1);
echo '<pre>';print_r($result);