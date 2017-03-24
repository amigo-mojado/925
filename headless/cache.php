<?php
include('config.php');
foreach($cache as $url_page){
    $content = file_get_contents('http://192.168.56.222/headless-magento/');
    $file = 'cache/'.rawurlencode($url_page);
    file_put_contents($file,$content);
}
$request = 'http://'.$_SERVER[HTTP_HOST].$_SERVER[REQUEST_URI];
echo $request;
if(file_exists('cache/'.rawurlencode($request))){
    include('cache/'.rawurlencode($request));exit;
}