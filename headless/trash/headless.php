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

    public function postData($url){
        $data = [
            'oauth_consumer_key' => $this->consumerKey,
            'oauth_nonce' => md5(uniqid(rand(), true)),
            'oauth_signature_method' => 'HMAC-SHA1',
            'oauth_timestamp' => time(),
            'oauth_token' => $this->accessToken,
            'oauth_version' => '1.0',
        ];

        $data['oauth_signature'] = $this->sign('POST', $url, $data, $this->consumerSecret, $this->accessTokenSecret);

        $curl = curl_init();

        curl_setopt_array($curl, [
            CURLOPT_POST => 1,
            CURLOPT_RETURNTRANSFER => 1,
            CURLOPT_URL => $url,
            CURLOPT_HTTPHEADER => [
                'Authorization: OAuth ' . http_build_query($data, '', ',')
            ],
            CURLOPT_NOPROXY => '192.168.56.222,spielwiese.local',
        ]);


        $data_string = array('cartItem' =>array('sku' => '24-MB01', 'qty' => 1));
        $data_string = json_encode($data_string);

        curl_setopt( $curl, CURLOPT_POSTFIELDS, $data_string);
        curl_setopt( $curl, CURLOPT_HTTPHEADER, array('Content-Type:application/json'));

        $result = curl_exec($curl);
        curl_close($curl);

        return $result;
    }

    public function createCart(){
        $ch = curl_init();
        # Setup request to send json via POST.
        //$payload = json_encode( array( "customer"=> $data ) );
        curl_setopt( $ch, CURLOPT_POST, '1');
        //curl_setopt( $ch, CURLOPT_POSTFIELDS, '');
        curl_setopt( $ch, CURLOPT_URL,'http://192.168.56.222/rest/default/V1/guest-carts');
        curl_setopt( $ch, CURLOPT_HTTPHEADER, array('Content-Type:application/json'));
        # Return response instead of printing.
        curl_setopt( $ch, CURLOPT_RETURNTRANSFER, true );

        curl_setopt( $ch, CURLOPT_NOPROXY,'192.168.56.222,spielwiese.local');
        # Send request.
        $result = curl_exec($ch);
        curl_close($ch);
        # Print response.
        echo "<pre>$result</pre>";
    }

    public function getCart(){
        $ch = curl_init();
        # Setup request to send json via POST.
        //$payload = json_encode( array( "customer"=> $data ) );
        //curl_setopt( $ch, CURLOPT_POST, '1');
        //curl_setopt( $ch, CURLOPT_POSTFIELDS, '');
        curl_setopt( $ch, CURLOPT_URL,'http://192.168.56.222/rest/default/V1/carts/mine');
        //curl_setopt( $ch, CURLOPT_HTTPHEADER, array('Content-Type:application/json'));
        # Return response instead of printing.
        curl_setopt( $ch, CURLOPT_RETURNTRANSFER, true );

        curl_setopt( $ch, CURLOPT_NOPROXY,'192.168.56.222,spielwiese.local');
        # Send request.
        $result = curl_exec($ch);
        curl_close($ch);
        # Print response.
        echo "<pre>$result</pre>";
    }
}


$headless = new headless();

$data = array();
$data['store'] = array();

//$data['store']['storeViews']['method'] = 'GET';
//$data['store']['storeViews']['url'] = '/V1/store/storeViews';

//$data['store']['storeGroups']['method'] = 'GET';
//$data['store']['storeGroups']['url'] = '/V1/store/storeGroups';

//$data['store']['websites']['method'] = 'GET';
//$data['store']['websites']['url'] = '/V1/store/websites';

//$data['store']['storeConfigs']['method'] = 'GET';
//$data['store']['storeConfigs']['url'] = '/V1/store/storeConfigs';

$data['catalog']['categories']['method'] = 'GET';
$data['catalog']['categories']['url'] = '/V1/categories';

if($_GET['categoryId']){
    $data['catalog']['categoriesCategoryIdProducts']['method'] = 'GET';
    $data['catalog']['categoriesCategoryIdProducts']['url'] = '/V1/categories/'.$_GET['categoryId'].'/products';
}

if($_GET['productSku']){
    $data['catalog']['productsSku']['method'] = 'GET';
    $data['catalog']['productsSku']['url'] = '/V1/products/'.$_GET['productSku'];
}

foreach($data as $key=>$var){
    foreach($var as $k=>$v){
        $string = $headless->getData($v['method'],'http://192.168.56.222/index.php/rest/'.$v['url']);
        if(!empty($string)){
            $$k = json_decode($string);
        }
        else $$k = $string;
    }
}

//print_r(json_decode($storeConfigs));