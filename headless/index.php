<?php
//print_r($_SERVER['HTTP_HOST']);exit;
function sign($method, $url, $data, $consumerSecret, $tokenSecret)
{
	$url = urlEncodeAsZend($url);
 
	$data = urlEncodeAsZend(http_build_query($data, '', '&'));
	$data = implode('&', [$method, $url, $data]);
 
	$secret = implode('&', [$consumerSecret, $tokenSecret]);
 
	return base64_encode(hash_hmac('sha1', $data, $secret, true));
}
 
function urlEncodeAsZend($value)
{
	$encoded = rawurlencode($value);
	$encoded = str_replace('%7E', '~', $encoded);
	return $encoded;
}
 
// REPLACE WITH YOUR ACTUAL DATA OBTAINED WHILE CREATING NEW INTEGRATION
$consumerKey = 'w58erx98ot5muyhtadh1qyevrdvscoup';
$consumerSecret = 'b1lnrmcudaqeqn3gu0j11fhwakt2dxku';
$accessToken = '1ltkk0pmiks5rymb9defkilomvb0fw4r';
$accessTokenSecret = 'cnacadbiux8w4u0x1gbvs44rats3rnr7';
 
$method = 'GET';
$url = 'http://192.168.56.222/index.php/rest/V1/customers/2';
 
//
$data = [
	'oauth_consumer_key' => $consumerKey,
	'oauth_nonce' => md5(uniqid(rand(), true)),
	'oauth_signature_method' => 'HMAC-SHA1',
	'oauth_timestamp' => time(),
	'oauth_token' => $accessToken,
	'oauth_version' => '1.0',
];
 
$data['oauth_signature'] = sign($method, $url, $data, $consumerSecret, $accessTokenSecret);
 
$curl = curl_init();
 
curl_setopt_array($curl, [
    CURLOPT_RETURNTRANSFER => 1,
    CURLOPT_URL => $url,
	CURLOPT_HTTPHEADER => [
		'Authorization: OAuth ' . http_build_query($data, '', ',')
	]
]);
 
curl_setopt($curl, CURLOPT_PROXY, 'http-proxy');
curl_setopt($curl, CURLOPT_PROXYPORT, 3128);


$result = curl_exec($curl);
curl_close($curl);
var_dump($result);