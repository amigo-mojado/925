<?php
session_start();
$host = 'http://192.168.56.222/index.php/rest';
$html = array();

if(key_exists('token',$_SESSION) && !empty($_SESSION["token"])){
    $token = str_replace('"','',$_SESSION["token"]);
}
else{
    $userData = array("username" => "rest", "password" => "magento-123");
    $ch = curl_init($host."/V1/integration/admin/token");
    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($userData));
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, array("Content-Type: application/json", "Content-Lenght: " . strlen(json_encode($userData))));
    curl_setopt($ch, CURLOPT_NOPROXY,'192.168.56.222,spielwiese.local');
    $token = curl_exec($ch);
    $_SESSION["token"] =  str_replace('"','',$token);
}

$html['token'] =  str_replace('"','',$token);

$ch = curl_init($host."/V1/customers/1");
curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "GET");
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_HTTPHEADER, array("Content-Type: application/json", "Authorization: Bearer " . json_decode($token)));
curl_setopt($ch, CURLOPT_NOPROXY,'192.168.56.222,spielwiese.local');

$result = curl_exec($ch);

$html['/V1/customers/1'] = json_decode($result);


//categories:
$ch = curl_init($host."/V1/categories");
curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "GET");
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_HTTPHEADER, array("Content-Type: application/json", "Authorization: Bearer " . json_decode($token)));
curl_setopt($ch, CURLOPT_NOPROXY,'192.168.56.222,spielwiese.local');

$result = curl_exec($ch);
$menu = makeMenu(json_decode($result));

$html['/V1/categories'] = json_decode($result);

//products in category
if(isset($_GET['categoryId'])){
    $ch = curl_init($host."/V1/categories/".$_GET['categoryId']."/products");
    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "GET");
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, array("Content-Type: application/json", "Authorization: Bearer " . json_decode($token)));
    curl_setopt($ch, CURLOPT_NOPROXY,'192.168.56.222,spielwiese.local');

    $result = curl_exec($ch);
    $category_products_data = $result;

    //output buffering
    ob_start();
    var_export(json_decode($result));
    $category_products = ob_get_clean();

    $html['/V1/categories/:categoryId/products'] = json_decode($result);

    //---------
    $products = array();
    foreach(json_decode($result) as $product){
        $ch = curl_init($host."/V1/products/".$product->sku);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "GET");
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array("Content-Type: application/json", "Authorization: Bearer " . json_decode($token)));
        curl_setopt($ch, CURLOPT_NOPROXY,'192.168.56.222,spielwiese.local');

        $result = curl_exec($ch);
        $data = json_decode($result);
        $products[] = array('sku' => $data->sku, 'name' => $data->name);
    }
}

//products details
if(isset($_GET['productSku'])){
    $ch = curl_init($host."/V1/products/".$_GET['productSku']);
    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "GET");
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, array("Content-Type: application/json", "Authorization: Bearer " . json_decode($token)));
    curl_setopt($ch, CURLOPT_NOPROXY,'192.168.56.222,spielwiese.local');

    $result = curl_exec($ch);

    $html['/V1/products/:sku'] = json_decode($result);
    $product = json_decode($result);

    if($product->type_id == 'configurable'){
        $children = array();
        $ch = curl_init($host."/V1/configurable-products/".$_GET['productSku']."/children");
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "GET");
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array("Content-Type: application/json", "Authorization: Bearer " . json_decode($token)));
        curl_setopt($ch, CURLOPT_NOPROXY,'192.168.56.222,spielwiese.local');

        $result = curl_exec($ch);

        $html['/V1/configurable-products/:sku/children'] = json_decode($result);
        $children = json_decode($result);
    }
}

//add to cart
if(isset($_GET['addToCart'])){
    //create Cart if not exists:
    //$_SESSION["cartId"] = '';
    if(key_exists('cartId',$_SESSION) && !empty($_SESSION["cartId"])){
        $cart = str_replace('"','',$_SESSION["cartId"]);
    }
    else{
        //Create Empty Cart:
        $ch = curl_init();
        curl_setopt( $ch, CURLOPT_POST, '1');
        curl_setopt( $ch, CURLOPT_URL,$host.'/V1/guest-carts/');
        curl_setopt( $ch, CURLOPT_RETURNTRANSFER, true );
        //curl_setopt($ch, CURLOPT_HTTPHEADER, array("Content-Type: application/json", "Authorization: Bearer " . json_decode($token)));
        curl_setopt( $ch, CURLOPT_NOPROXY,'192.168.56.222,spielwiese.local');

        # Send request.
        $result = curl_exec($ch);
        curl_close($ch);
        # Print response.
        $_SESSION["cartId"] = str_replace('"','',$result);
        $cart = str_replace('"','',$result);
        //echo "<pre>$result</pre>";
    }

    $cart = str_replace('"','',$cart);

    $html['/V1/guest-carts/'] = $cart;

    //---------
    $item['cartItem'] = array();
    $item['cartItem']['quote_id'] = $cart;
    $item['cartItem']['sku'] = $_GET['sku'];
    $item['cartItem']['qty'] = 1;

    $data_string = json_encode($item);

    $ch = curl_init();
    curl_setopt( $ch, CURLOPT_POST, '1');
    curl_setopt( $ch, CURLOPT_POSTFIELDS, $data_string);
    curl_setopt( $ch, CURLOPT_URL,$host.'/V1/guest-carts/'.$cart.'/items');
    curl_setopt( $ch, CURLOPT_RETURNTRANSFER, true );
    //curl_setopt($ch, CURLOPT_HTTPHEADER, array("Content-Type: application/json", "Authorization: Bearer " . json_decode($token)));
    curl_setopt( $ch, CURLOPT_NOPROXY,'192.168.56.222,spielwiese.local');
    curl_setopt($ch, CURLOPT_HTTPHEADER, array(
            'Content-Type: application/json',
            'Content-Length: ' . strlen($data_string))
    );

    # Send request.
    $result = curl_exec($ch);
    curl_close($ch);

    $html['/V1/guest-carts/:quote_id/items\''] = $result;

}

//cart

if(isset($_GET['addPayment'])){

    $item['paymentMethod'] = array();
    $item['paymentMethod']['method'] = 'checkmo';
    $item['email'] = 'checkmo@checkmo.com';
    //$item['shippingMethod'] = array();
    //$item['shippingMethod']['method_code'] = 'flatrate';
    //$item['shippingMethod']['carrier_code'] = 'flatrate';
    //$item['shippingMethod']['additionalPropertiers'] = array();

    $data_string = json_encode($item);

    $ch = curl_init();
    curl_setopt( $ch, CURLOPT_POST, '1');
    curl_setopt( $ch, CURLOPT_POSTFIELDS, $data_string);
    curl_setopt( $ch, CURLOPT_URL,$host.'/V1/guest-carts/'.$_SESSION["cartId"].'/payment-information');
    curl_setopt( $ch, CURLOPT_RETURNTRANSFER, true );
    //curl_setopt($ch, CURLOPT_HTTPHEADER, array("Content-Type: application/json", "Authorization: Bearer " . json_decode($token)));
    curl_setopt( $ch, CURLOPT_NOPROXY,'192.168.56.222,spielwiese.local');
    curl_setopt($ch, CURLOPT_HTTPHEADER, array(
            'Content-Type: application/json',
            'Content-Length: ' . strlen($data_string))
    );

    # Send request.
    $result = curl_exec($ch);
    curl_close($ch);

    $html['/V1/guest-carts/:quote_id/payment-information'] = $result;
    $_SESSION["cartId"] = '';

}
if(isset($_GET['addShipping'])){
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

    $ch = curl_init();
    curl_setopt( $ch, CURLOPT_POST, '1');
    curl_setopt( $ch, CURLOPT_POSTFIELDS, $data_string);
    curl_setopt( $ch, CURLOPT_URL,$host.'/V1/guest-carts/'.$_SESSION["cartId"].'/shipping-information');
    curl_setopt( $ch, CURLOPT_RETURNTRANSFER, true );
    //curl_setopt($ch, CURLOPT_HTTPHEADER, array("Content-Type: application/json", "Authorization: Bearer " . json_decode($token)));
    curl_setopt( $ch, CURLOPT_NOPROXY,'192.168.56.222,spielwiese.local');
    curl_setopt($ch, CURLOPT_HTTPHEADER, array(
            'Content-Type: application/json',
            'Content-Length: ' . strlen($data_string))
    );

    # Send request.
    $result = curl_exec($ch);
    curl_close($ch);

    $html['/V1/guest-carts/:quote_id/shipping-information'] = $result;
}

if(isset($_GET['cart'])){
    //create Cart if not exists:
    //$_SESSION["cartId"] = '';
    if(key_exists('cartId',$_SESSION) && !empty($_SESSION["cartId"])){
        $cart = str_replace('"','',$_SESSION["cartId"]);
    }
    else{
        //Create Empty Cart:
        $ch = curl_init();
        curl_setopt( $ch, CURLOPT_POST, '1');
        curl_setopt( $ch, CURLOPT_URL,$host.'/V1/guest-carts/');
        curl_setopt( $ch, CURLOPT_RETURNTRANSFER, true );
        //curl_setopt($ch, CURLOPT_HTTPHEADER, array("Content-Type: application/json", "Authorization: Bearer " . json_decode($token)));
        curl_setopt( $ch, CURLOPT_NOPROXY,'192.168.56.222,spielwiese.local');

        # Send request.
        $result = curl_exec($ch);
        curl_close($ch);
        # Print response.
        $_SESSION["cartId"] = str_replace('"','',$result);
        $cart = str_replace('"','',$result);
        //echo "<pre>$result</pre>";
    }

    $cart = str_replace('"','',$cart);

    $html['/V1/guest-carts/'] = $cart;


    $ch = curl_init($host."/V1/guest-carts/".$_SESSION["cartId"]);
    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "GET");
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, array("Content-Type: application/json", "Authorization: Bearer " . json_decode($token)));
    curl_setopt($ch, CURLOPT_NOPROXY,'192.168.56.222,spielwiese.local');

    $result = curl_exec($ch);

    $html['/V1/guest-carts/:cartId'] = json_decode($result);
    $cart_data = json_decode($result);
    //$cart_data = json_decode(json_encode($result), true);
}

//login:
if(isset($_GET['login'])){
echo "login";
}
?>
<!doctype html>

<html>
<head>
    <meta charset="utf-8">

    <title>Magento Headless Test</title>
    <meta name="description" content="Magento Headless Test">

    <link href="css/reset.css" rel="stylesheet" type="text/css">
    <link href="css/style.css" rel="stylesheet" type="text/css">

    <!--[if lt IE 9]>
    <script src="html5shiv.js"></script>
    <![endif]-->
</head>

<body>
<div id="wrapper">
    <header>
        <nav>
            <a href="/headless-magento/auth.php=true">home</a>
            <a href="/headless-magento/auth.php?cart=true">login</a>
            <a href="/headless-magento/auth.php?login=true">home</a>
        </nav>
    </header>
    <div id="content">
<?php
if(isset($_GET['categoryId'])){
    echo "<h2>Products:</h2>\n";
    echo "<br>\n<br>\n";
    foreach($products as $product){
        echo "<a href=\"?productSku=".$product['sku']."\">".$product['name']."</a><br>\n";
    }
    echo "<br>\n<br>\n";
}
//---------
if(isset($_GET['productSku'])){
    echo "<h2>Product Details:</h2>\n";
    echo "<br>\n<br>\n";
    echo "id: ".$product->id ."<br>\n";
    echo "sku: ".$product->sku ."<br>\n";
    echo "name: ".$product->name ."<br>\n";
    echo "type: ".$product->type_id ."<br>\n";
    echo "price: ".$product->price ."<br>\n";
    echo "...<br>\n";
    echo "<br>\n<br>\n";

    if($product->type_id == 'configurable'){
        echo "<h2>Children:</h2>\n";
        foreach($children as $product){
            echo "sku: ".$product->sku ."<br>\n";
            echo "name: ".$product->name ."<br>\n";
            echo "type: ".$product->type_id ."<br>\n";
            echo "price: ".$product->price ."<br>\n";
            echo "...<br>\n";
            echo "<br>\n<br>\n";
        }
    }
    else{
        echo "<a href=\"?addToCart=true&sku=".$product->sku."\">add to cart</a>\n";
        echo "<br>\n<br>\n";
    }
}
//Cart:
if(isset($_GET['cart'])){

    if(key_exists('cartId',$_SESSION) && !empty($_SESSION["cartId"])){
        $cart = str_replace('"','',$_SESSION["cartId"]);
    }
    else
    {

    }
    echo ">Add shipping / billing information</a><br>\n";
    echo "<br>\n<br>\n";

    echo "<a href=\"auth.php?cart=true&addPayment\">Add Payment</a><br>\n";
    echo "<br>\n<br>\n";

    //output buffering
    ob_start();
    var_export((array) $cart_data);
    $dump = ob_get_clean();
    echo "<pre>".$dump."</pre>\n";
    echo "<br>\n<br>\n";

}

?>
        <table>
            <thead>
                <td>url:</td>
                <td>data:</td>
            </thead>
            <tbody>
<?php
$html = array_reverse($html);
foreach($html as $key=>$var){
    echo "\t\t\t<tr>\n";
    echo "\t\t\t\t<td>".$key."</td>\n";
    //output buffering
    ob_start();
    var_export($var);
    $dump = ob_get_clean();

    echo "\t\t\t\t<td><div style=\"overflow:scroll; height: 100px; width:550px;\"><pre>".$dump."</pre></div></td>\n";
    echo "\t\t\t</tr>\n";
}
?>
            </tbody>
        </table>
</div>
    <div id="rightcol"><?php echo $menu; ?></div>
    <footer>  </footer>
</div>
<script src="js/scripts.js"></script>
</body>
</html>
<?php
function makeMenu($items,$nav = '') {
    if($items->is_active){
        $nav .= "<p style=\"text-indent: ".($items->level * 10)."px;\" ><a href=\"?categoryId=".$items->id."\" title=\"ID: ".$items->id." Level: ".$items->level."\">".$items->name." (".$items->product_count.")</a></p>\n";
    }
    if(!empty($items->children_data)){
        foreach($items->children_data as $item){
            $nav = makeMenu($item,$nav);
        }
    }
    return $nav;
}
?>
