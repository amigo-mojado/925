<?php
if($_GET['addToCart']){
    $item['cartItem'] = array();
    $item['cartItem']['quote_id'] = $_SESSION['customer_cart'];
    $item['cartItem']['sku'] = $_GET['productSku'];
    $item['cartItem']['qty'] = 1;

    $data_string = json_encode($item);
    $messages = postData($config['host'].'/V1/guest-carts/'.$_SESSION['customer_cart'].'/items',$_SESSION['admin_token'],$data_string,$messages);
/**
    $ch = curl_init();
    curl_setopt( $ch, CURLOPT_POST, '1');
    curl_setopt( $ch, CURLOPT_POSTFIELDS, $data_string);
    curl_setopt( $ch, CURLOPT_URL,$config['host'].'/V1/guest-carts/'.$_SESSION['customer_cart'].'/items');
    curl_setopt( $ch, CURLOPT_RETURNTRANSFER, true );
    curl_setopt($ch, CURLOPT_HTTPHEADER, array("Content-Type: application/json", "Authorization: Bearer " . $_SESSION['admin_token']));
    curl_setopt( $ch, CURLOPT_NOPROXY,'192.168.56.222,spielwiese.local');

    # Send request.
    $result = curl_exec($ch);

    $messages = messages($ch,$result,$messages);

    curl_close($ch);
**/

//--------- --------- ----------

    //cart:
    //$cart = getData($config['host'].'/V1/carts/mine',$_SESSION['customer_token']);
    //$view['content'][] = "CART MINE:<br>\n";
    //$view['content'][] = cartToHtml($cart);

    $item['cartItem'] = array();
    $item['cartItem']['sku'] = $_GET['productSku'];
    $item['cartItem']['qty'] = 1;
    $item['cartItem']['quote_id'] = $_SESSION['mine_cart'];

    $data_string = json_encode($item);

    $messages = postData($config['host'].'/V1/carts/mine/items',$_SESSION['customer_token'],$data_string,$messages);
    /**
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

    $messages = messages($ch,$result,$messages);

    curl_close($ch);
    **/
//-------------------------------------------------

    /**
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
    **/
}
if($_GET['addToCart'] || $_GET['cart']) {
    //cart:
    $cart = getData($config['host'] . '/V1/guest-carts/' . $_SESSION['customer_cart'], $_SESSION['admin_token']);
    $view['content'][] = "GUEST CART:<br>\n";
    $view['content'][] = cartToHtml($cart);

    //cart:
    $cart = getData($config['host'] . '/V1/carts/mine', $_SESSION['customer_token']);
    $view['content'][] = "CART MINE:<br>\n";
    $view['content'][] = cartToHtml($cart);
}
function cartToHtml($cart){
    $guest_cart = "\n<table>\n";
    $guest_cart .= "\t<tr>\n";
    $guest_cart .= "\t\t<td>sku</td>\n";
    $guest_cart .= "\t\t<td>name</td>\n";
    $guest_cart .= "\t\t<td>qty</td>\n";
    $guest_cart .= "\t\t<td>price</td>\n";
    $guest_cart .= "\t</tr>\n";
    foreach($cart->items as $product){
        $guest_cart .= "\t<tr>\n";
        $guest_cart .= "\t\t<td>".$product->sku."</td>\n";
        $guest_cart .= "\t\t<td>".$product->name."</td>\n";
        $guest_cart .= "\t\t<td>".$product->qty."</td>\n";
        $guest_cart .= "\t\t<td>".$product->price."</td>\n";
        $guest_cart .= "\t</tr>\n";
    }

    $guest_cart .= "</table><a href=\"?checkout=true\">CHECKOUT</a><br>\n<br>\n";
    return $guest_cart;
}