<?php
//Products:
if($_GET['categoryId']){
    $result = getData($config['host'].'/V1/categories/'.$_GET['categoryId'].'/products',$_SESSION['admin_token']);

    $html = "<table>\n";
    $html .= "\t<tr>\n";
    $html .= "\t\t<td>sku</td>\n";
    $html .= "\t\t<td>position</td>\n";
    $html .= "\t\t<td>category_id</td>\n";
    $html .= "\t\t<td>&nbsp;</td>\n";
    $html .= "\t</tr>\n";
    foreach($result as $product){
        $html .= "\t<tr>\n";
        $html .= "\t\t<td>".$product->sku."</td>\n";
        $html .= "\t\t<td>".$product->position."</td>\n";
        $html .= "\t\t<td>".$product->category_id."</td>\n";
        $html .= "\t\t<td><a href=\"?productSku=".$product->sku."\">details</a></td>\n";
        $html .= "\t</tr>\n";
    }
    $html .= "</table>\n";
    $view['content']['category_products'] = $html;
}

//Product:
if(!isset($_GET['addToCart']) && $_GET['productSku']){
    $products = array();
    $product = getData($config['host'].'/V1/products/'.$_GET['productSku'],$_SESSION['admin_token']);

    if($product->type_id == 'configurable'){
        $products[] = $product;
        $children = getData($config['host'].'/V1/configurable-products/'.$_GET['productSku'].'/children',$_SESSION['admin_token']);
        foreach($children as $product){
            $products[] = $product;
        }
    }
    else{
        $products[] = $product;
    }

    $html = "<table>\n";
    $html .= "\t<tr>\n";
    $html .= "\t\t<td>sku</td>\n";
    $html .= "\t\t<td>name</td>\n";
    $html .= "\t\t<td>type_id</td>\n";
    $html .= "\t\t<td>&nbsp;</td>\n";
    $html .= "\t</tr>\n";
    foreach($products as $product){
        $html .= "\t<tr>\n";
        $html .= "\t\t<td>".$product->sku."</td>\n";
        $html .= "\t\t<td>".$product->name."</td>\n";
        $html .= "\t\t<td>".$product->type_id."</td>\n";
        if($product->type_id == 'simple'){
            $html .= "\t\t<td><a href=\"?addToCart=true&productSku=".$product->sku."\">add to cart</a></td>\n";
        }
        else{
            $html .= "\t\t<td>&nbsp;</td>\n";
        }
        $html .= "\t</tr>\n";
    }
    $html .= "</table>\n";
    $view['content']['product_details'] = $html;
}