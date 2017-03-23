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

//categories:
$result = getData($config['host'].'/V1/categories',$_SESSION['admin_token']);
$view['sidebar']['menu_categories'] = makeMenu($result);