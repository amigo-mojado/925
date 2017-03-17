<?php
$ch = curl_init();
$headers = array("User-Agent: Mozilla/5.0 (Windows; U; Windows NT 5.1; en-US; rv:1.8.0.8) Gecko/20061025 Firefox/1.5.0.8");

//curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
curl_setopt($ch, CURLOPT_URL, '192.168.56.222');
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_BINARYTRANSFER, true);
curl_setopt($ch, CURLOPT_PROXY, 'http-proxy');
curl_setopt($ch, CURLOPT_PROXYPORT, 3128);

$fp = fopen(dirname(__FILE__).'/errorlog.txt', 'a+'); 

curl_setopt($ch, CURLOPT_VERBOSE, 1);
curl_setopt($ch, CURLOPT_STDERR, $fp); 

//execute post
$result = curl_exec($ch);
//close connection
curl_close($ch);
var_dump($result);
?> 