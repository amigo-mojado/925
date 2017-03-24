<?php
if($_GET['checkout']){
    $view['content'][] = "<br>\n";
    $view['content'][] = "<a href=\"?checkout=true&addShipping=true\">add Shipping</a><br>\n";
    $view['content'][] = "<a href=\"?checkout=true&addPayment=true\">add Payment</a><br>\n";
}

if($_GET['checkout'] && $_GET['addShipping']){
    //---------
    $item['addressInformation'] = array();
    $item['addressInformation']['shippingAddress'] = array();
    $item['addressInformation']['shippingAddress']['region'] = 'MH';
    $item['addressInformation']['shippingAddress']['region_id'] = 0;
    $item['addressInformation']['shippingAddress']['country_id'] = "IN";
    $item['addressInformation']['shippingAddress']['street'] = array('Chakala,Kalyan (e)');
    $item['addressInformation']['shippingAddress']['company'] = "abc";
    $item['addressInformation']['shippingAddress']['telephone'] = "11111111";
    $item['addressInformation']['shippingAddress']['postcode'] = "12345";
    $item['addressInformation']['shippingAddress']['city'] = "Mumbai";
    $item['addressInformation']['shippingAddress']['firstname'] = "Homer";
    $item['addressInformation']['shippingAddress']['lastname'] = "Simpson";
    $item['addressInformation']['shippingAddress']['email'] = "me@you.com";
    $item['addressInformation']['shippingAddress']['prefix'] = "address_";
    $item['addressInformation']['shippingAddress']['region_code'] = "MH";
    $item['addressInformation']['shippingAddress']['sameAsBilling'] = 1;

    $item['addressInformation']['billingAddress'] = array();
    $item['addressInformation']['billingAddress']['region'] = 'MH';
    $item['addressInformation']['billingAddress']['region_id'] = 0;
    $item['addressInformation']['billingAddress']['country_id'] = "IN";
    $item['addressInformation']['billingAddress']['street'] = array('Chakala,Kalyan (e)');
    $item['addressInformation']['billingAddress']['company'] = "abc";
    $item['addressInformation']['billingAddress']['telephone'] = "11111111";
    $item['addressInformation']['billingAddress']['postcode'] = "12345";
    $item['addressInformation']['billingAddress']['city'] = "Mumbai";
    $item['addressInformation']['billingAddress']['firstname'] = "Homer";
    $item['addressInformation']['billingAddress']['lastname'] = "Simpson";
    $item['addressInformation']['billingAddress']['email'] = "me@you.com";
    $item['addressInformation']['billingAddress']['prefix'] = "address_";
    $item['addressInformation']['billingAddress']['region_code'] = "MH";

    $item['addressInformation']['shipping_method_code'] = "flatrate";
    $item['addressInformation']['shipping_carrier_code'] = "flatrate";

    $data_string = json_encode($item);

    //GUEST CART
    $ch = curl_init();
    curl_setopt( $ch, CURLOPT_POST, '1');
    curl_setopt( $ch, CURLOPT_POSTFIELDS, $data_string);
    curl_setopt( $ch, CURLOPT_URL,$config['host'].'/V1/guest-carts/'.$_SESSION["customer_cart"].'/shipping-information');
    curl_setopt( $ch, CURLOPT_RETURNTRANSFER, true );
    curl_setopt($ch, CURLOPT_HTTPHEADER, array("Content-Type: application/json", "Authorization: Bearer " . $_SESSION['admin_token']));
    curl_setopt( $ch, CURLOPT_NOPROXY,'192.168.56.222,spielwiese.local');
    curl_setopt($ch, CURLOPT_HTTPHEADER, array(
            'Content-Type: application/json',
            'Content-Length: ' . strlen($data_string))
    );

    # Send request.
    $result = curl_exec($ch);

    $messages = messages($ch,$result,$messages);

    curl_close($ch);

    //MINE
    $ch = curl_init();
    curl_setopt( $ch, CURLOPT_POST, '1');
    curl_setopt( $ch, CURLOPT_POSTFIELDS, $data_string);
    curl_setopt( $ch, CURLOPT_URL,$config['host'].'/V1/carts/mine/shipping-information');
    curl_setopt( $ch, CURLOPT_RETURNTRANSFER, true );
    curl_setopt($ch, CURLOPT_HTTPHEADER, array("Content-Type: application/json", "Authorization: Bearer " . $_SESSION['customer_token']));
    curl_setopt( $ch, CURLOPT_NOPROXY,'192.168.56.222,spielwiese.local');

    # Send request.
    $result = curl_exec($ch);

    $messages = messages($ch,$result,$messages);

    curl_close($ch);
}

if($_GET['checkout'] && $_GET['addPayment']){

    $item['paymentMethod'] = array();
    $item['paymentMethod']['method'] = 'checkmo';
    $item['email'] = 'checkmo@checkmo.com';

    $data_string = json_encode($item);

    //Guest Cart
    $ch = curl_init();
    curl_setopt( $ch, CURLOPT_POST, '1');
    curl_setopt( $ch, CURLOPT_POSTFIELDS, $data_string);
    curl_setopt( $ch, CURLOPT_URL,$config['host'].'/V1/guest-carts/'.$_SESSION["customer_cart"].'/payment-information');
    curl_setopt( $ch, CURLOPT_RETURNTRANSFER, true );
    curl_setopt($ch, CURLOPT_HTTPHEADER, array("Content-Type: application/json", "Authorization: Bearer " . $_SESSION['admin_token']));
    curl_setopt( $ch, CURLOPT_NOPROXY,'192.168.56.222,spielwiese.local');

    # Send request.
    $result = curl_exec($ch);

    $messages = messages($ch,$result,$messages);

    curl_close($ch);
    $_SESSION['customer_cart'] = '';


    //Cart Mine
    $ch = curl_init();
    curl_setopt( $ch, CURLOPT_POST, '1');
    curl_setopt( $ch, CURLOPT_POSTFIELDS, $data_string);
    curl_setopt( $ch, CURLOPT_URL,$config['host'].'/V1/carts/mine/payment-information');
    curl_setopt( $ch, CURLOPT_RETURNTRANSFER, true );
    curl_setopt($ch, CURLOPT_HTTPHEADER, array("Content-Type: application/json", "Authorization: Bearer " . $_SESSION['customer_token']));
    curl_setopt( $ch, CURLOPT_NOPROXY,'192.168.56.222,spielwiese.local');

    # Send request.
    $result = curl_exec($ch);

    $messages = messages($ch,$result,$messages);

    curl_close($ch);
    $_SESSION['mine_cart'] = '';

}



