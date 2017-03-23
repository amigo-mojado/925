<?php

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

$host = '192.168.56.222';
$consumerKey = 'w58erx98ot5muyhtadh1qyevrdvscoup';
$consumerSecret = 'b1lnrmcudaqeqn3gu0j11fhwakt2dxku';
$accessToken = '1ltkk0pmiks5rymb9defkilomvb0fw4r';
$accessTokenSecret = 'cnacadbiux8w4u0x1gbvs44rats3rnr7';
$url = $host.'/rest/V1/carts/mine';

$data = [
    'oauth_consumer_key' => $consumerKey,
    'oauth_nonce' => md5(uniqid(rand(), true)),
    'oauth_signature_method' => 'HMAC-SHA1',
    'oauth_timestamp' => time(),
    'oauth_token' => $accessToken,
    'oauth_version' => '1.0',
];

$data['oauth_signature'] = sign('POST', $url, $data, $consumerSecret, $accessTokenSecret);

$curl = curl_init();
print_r($data);
curl_setopt_array($curl, [
    CURLOPT_POST => 1,
    CURLOPT_RETURNTRANSFER => 1,
    CURLOPT_URL => $host.'/rest/V1/carts/mine',
    CURLOPT_HTTPHEADER => [
        'Authorization: OAuth ' . http_build_query($data, '', ',')
    ],
    CURLOPT_NOPROXY => '192.168.56.222,spielwiese.local',
]);

$result = curl_exec($curl);
curl_close($curl);

echo $result;
exit;



//Create Empty Cart:
$ch = curl_init();
curl_setopt( $ch, CURLOPT_POST, '1');
curl_setopt( $ch, CURLOPT_URL,$host.'/rest/V1/carts/mine');
curl_setopt( $ch, CURLOPT_RETURNTRANSFER, true );
curl_setopt( $ch, CURLOPT_NOPROXY,'192.168.56.222,spielwiese.local');
# Send request.
$result = curl_exec($ch);
curl_close($ch);
# Print response.
echo "<pre>$result</pre>";