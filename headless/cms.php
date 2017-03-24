<?php
$pages = array(1,2,3,4,5,6);
foreach($pages as $pageId){
    $cms = getData($config['host'] . '/V1/cmsPage/'.$pageId, $_SESSION['admin_token']);
    $view['nav']['cms'][] = "<a href=\"?cms=true&pageId=".$cms->id."\">".$cms->identifier."</a> | \n";
}

//$configs = getData($config['host'] . '/V1/store/storeConfigs', $_SESSION['admin_token']);
//_d($configs);
if($_GET['cms'] && $_GET['pageId']){
    $cms = getData($config['host'] . '/V1/cmsPage/'.$_GET['pageId'], $_SESSION['admin_token']);

    $zeichenkette = $cms->content;
    $suchmuster = "/{{(.+)}}/U";
    preg_match_all($suchmuster, $zeichenkette, $treffer, PREG_OFFSET_CAPTURE, 3);
    $view['content'][] = "<br>\n";
    foreach($treffer[0] as $variable){
        $view['content'][] = "variable: ". $variable[0] . "<br>\n";
    }
    $view['content'][] = "<br>\n";

    $view['content'][] = $cms->content_heading;
    $view['content'][] = $cms->content;
}
