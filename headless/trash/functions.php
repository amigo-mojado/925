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


function showProducts($products){
    $headless = new headless();
    $html = "<table>\n";
    $html .= "<tr>\n";
    $html .= "<td>SKU:</td>\n";
    $html .= "<td>Data:</td>\n";
    $html .= "<td>Details:</td>\n";
    $html .= "<td>Cart:</td>\n";
    $html .= "</tr>\n";
    foreach($products as $key=>$product){
        $html .= "<tr>\n";
        $html .= "<td>".$product->sku."</td>\n";
        //print_r($product);
        $pData = $headless->getData('GET','http://192.168.56.222/index.php/rest/V1/products/'.$product->sku);
        $pData = json_decode($pData);
        //print_r(get_object_vars ($pData));exit;
        $html .= "<td>".objectDataToHtml($pData)."</td>\n";
        $html .= "<td><a href=\"?productSku=".$product->sku."\">Details</a></td>\n";
        $html .= "<td>Cart:</td>\n";
        $html .= "</tr>\n";
    }
    $html .= "</table>\n";
    return $html;
}

function objectDataToHtml($object){
    $show = array('id','sku','name','price','type_id','file','value');
    $html = '';
        foreach(get_object_vars ($object) as $key=>$var){

        if(is_object($var)){
            $html .= objectDataToHtml($var);
        }
        else{
            if(is_array($var) && is_object($var[0])){
                $html .= objectDataToHtml($var[0]);
            }
            else{
                if(is_array($var)){
                    foreach($var as $k=>$v){
                        if(!empty($v) && in_array($k,$show)){
                            $html .= "<span style=\"float:left;width:250px;\">".$k.":</span>".$v."<br>\n";
                        }
                    }

                }
                else{
                    if(!empty($var) && in_array($key,$show)){
                        $html .= "<span style=\"float:left;width:250px;\">".$key.":</span>".$var."<br>\n";
                    }
                }
            }
        }
    }

    return $html;
}

function showDetails($product){
    return objectDataToHtml($product);
}

function addToCart($sku){
    //$data_string = array('cartItem' =>array('sku' => $sku, 'qty' => 1));
    $headless = new headless();
    //$cart = $headless->createCart();

    $data_string = array('cartItem' =>array('sku' => $sku, 'qty' => 1));
    $data_string = json_encode($data_string);

   // echo $headless->postData('http://192.168.56.222/rest/V1/guest-carts/c8c3a7ccaf246097b67aa2d6f13f747e/items');
    echo $headless->getData('GET','http://192.168.56.222/rest/default/V1/carts/mine');
    //echo $headless->getData('GET','http://192.168.56.222/rest/V1/guest-carts/c8c3a7ccaf246097b67aa2d6f13f747e/items');
}
