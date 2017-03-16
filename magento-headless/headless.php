<?php
class headless{

    protected $consumerKey = 'w58erx98ot5muyhtadh1qyevrdvscoup';
    protected $consumerSecret = 'b1lnrmcudaqeqn3gu0j11fhwakt2dxku';
    protected $accessToken = '1ltkk0pmiks5rymb9defkilomvb0fw4r';
    protected $accessTokenSecret = 'cnacadbiux8w4u0x1gbvs44rats3rnr7';

    protected function sign($method, $url, $data, $consumerSecret, $tokenSecret)
    {
        $url = $this->urlEncodeAsZend($url);

        $data = $this->urlEncodeAsZend(http_build_query($data, '', '&'));
        $data = implode('&', [$method, $url, $data]);

        $secret = implode('&', [$consumerSecret, $tokenSecret]);

        return base64_encode(hash_hmac('sha1', $data, $secret, true));
    }

    protected function urlEncodeAsZend($value)
    {
        $encoded = rawurlencode($value);
        $encoded = str_replace('%7E', '~', $encoded);
        return $encoded;
    }

    public function getData($method,$url)
    {
        $data = [
            'oauth_consumer_key' => $this->consumerKey,
            'oauth_nonce' => md5(uniqid(rand(), true)),
            'oauth_signature_method' => 'HMAC-SHA1',
            'oauth_timestamp' => time(),
            'oauth_token' => $this->accessToken,
            'oauth_version' => '1.0',
        ];

        $data['oauth_signature'] = $this->sign($method, $url, $data, $this->consumerSecret, $this->accessTokenSecret);

        $curl = curl_init();

        curl_setopt_array($curl, [
            CURLOPT_RETURNTRANSFER => 1,
            CURLOPT_URL => $url,
            CURLOPT_HTTPHEADER => [
                'Authorization: OAuth ' . http_build_query($data, '', ',')
            ],
            CURLOPT_NOPROXY => '192.168.56.222,spielwiese.local'
        ]);

        $result = curl_exec($curl);
        curl_close($curl);

        return $result;
    }
}

$headless = new headless();

$data = array();
$data['store'] = array();

$data['store']['storeViews']['method'] = 'GET';
$data['store']['storeViews']['url'] = '/V1/store/storeViews';

$data['store']['storeGroups']['method'] = 'GET';
$data['store']['storeGroups']['url'] = '/V1/store/storeGroups';

$data['store']['websites']['method'] = 'GET';
$data['store']['websites']['url'] = '/V1/store/websites';

$data['store']['storeConfigs']['method'] = 'GET';
$data['store']['storeConfigs']['url'] = '/V1/store/storeConfigs';

foreach($data as $key=>$var){
    foreach($var as $k=>$v){
        echo $k ."<br>\n";
        echo $v['method'] ."<br>\n";
        echo $v['url'] ."<br>\n";
        $$k = $headless->getData($v['method'],'http://192.168.56.222/index.php/rest/'.$v['url']);
    }
}
$a = hallo;
$$a = 'test';
echo $hallo;
print_r(json_decode($storeConfigs));