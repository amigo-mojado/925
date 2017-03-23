<?php
if($_GET['addToCart']){
    $result = getData($config['host'].'/V1/carts/'.$_SESSION['customer_cart'],$_SESSION['admin_token']);

    $item['cartItem'] = array();
    $item['cartItem']['quote_id'] = $_SESSION['customer_cart'];
    $item['cartItem']['sku'] = $_GET['productSku'];
    $item['cartItem']['qty'] = 1;

    $data_string = json_encode($item);

    $ch = curl_init();
    curl_setopt( $ch, CURLOPT_POST, '1');
    curl_setopt( $ch, CURLOPT_POSTFIELDS, $data_string);
    curl_setopt( $ch, CURLOPT_URL,$config['host'].'/V1/guest-carts/'.$_SESSION['customer_cart'].'/items');
    curl_setopt( $ch, CURLOPT_RETURNTRANSFER, true );
    curl_setopt($ch, CURLOPT_HTTPHEADER, array("Content-Type: application/json", "Authorization: Bearer " . $_SESSION['admin_token']));
    curl_setopt( $ch, CURLOPT_NOPROXY,'192.168.56.222,spielwiese.local');
    curl_setopt($ch, CURLOPT_HTTPHEADER, array(
            'Content-Type: application/json',
            'Content-Length: ' . strlen($data_string))
    );

    # Send request.
    $result = curl_exec($ch);

    if (!curl_errno($ch)) {
        switch ($http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE)) {
            case 200:  # OK
                break;
            default:
                $errors[] = $result;
        }
    }

    curl_close($ch);

    $item['cartItem'] = array();
//    $item['cartItem']['quoteId'] = 66;
//    $item['cartItem']['cartId'] = "66";
    $item['cartItem']['sku'] = $_GET['productSku'];
    $item['cartItem']['qty'] = 1;
    //$item['cartItem']['name'] = 'test';
    //$item['cart_item']['price'] = 0;

    //$item['cartItem']['product_type'] = 'simple';
    $item['cartItem']['quote_id'] = '66';

    $data_string = json_encode($item);

    $ch = curl_init();
    curl_setopt( $ch, CURLOPT_POST, '1');
    curl_setopt( $ch, CURLOPT_URL,$config['host'].'/V1/carts/mine/items');
    curl_setopt( $ch, CURLOPT_POSTFIELDS, $data_string);
    curl_setopt( $ch, CURLOPT_RETURNTRANSFER, true );
    curl_setopt($ch, CURLOPT_HTTPHEADER, array("Content-Type: application/json", "Authorization: Bearer " . $_SESSION['customer_token']));
    curl_setopt( $ch, CURLOPT_NOPROXY,'192.168.56.222,spielwiese.local');
    //curl_setopt($ch, CURLOPT_HTTPHEADER, array("Content-Type: application/json", "Content-Lenght: " . strlen($data_string)));

    # Send request.
    $result = curl_exec($ch);
    $quoteId = str_replace('"','',$result);
echo $result;
    curl_close($ch);

//-------------------------------------------------
    //if()
    $item['cartItem'] = array();
    $item['cartItem']['quoteId'] = $_SESSION['customer_cart'];
    $item['cartItem']['sku'] = $_GET['productSku'];
    $item['cartItem']['qty'] = 1;

    $data_string = json_encode($item);

    $ch = curl_init();
    curl_setopt( $ch, CURLOPT_POST, '1');
    curl_setopt( $ch, CURLOPT_POSTFIELDS, $data_string);
    curl_setopt( $ch, CURLOPT_URL,$config['host'].'/V1/guest-carts/'.$_SESSION['customer_cart'].'/items');
    curl_setopt( $ch, CURLOPT_RETURNTRANSFER, true );
    curl_setopt($ch, CURLOPT_HTTPHEADER, array("Content-Type: application/json", "Authorization: Bearer " . $_SESSION['admin_token']));
    curl_setopt( $ch, CURLOPT_NOPROXY,'192.168.56.222,spielwiese.local');
    curl_setopt($ch, CURLOPT_HTTPHEADER, array(
           'Content-Type: application/json',
    ));

    # Send request.
    $result = curl_exec($ch);
    echo $result;

}