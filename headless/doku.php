<?php
session_start();
////http://192.168.56.222/index.php/rest/default/schema
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

//--------- --------- ---------

$ch = curl_init('http://192.168.56.222/rest/default/schema?services=all');
curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "GET");
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_HTTPHEADER, array("Content-Type: application/json", "Authorization: Bearer " . $token));
curl_setopt($ch, CURLOPT_NOPROXY,'192.168.56.222,spielwiese.local');

# Send request.
$result = curl_exec($ch);

curl_close($ch);

$services = json_decode($result);

//--------- --------- ---------
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

    <script src="js/jquery-2.1.3.min.js"></script>
    <script src="js/jquery.json-viewer.js"></script>
    <link href="css/jquery.json-viewer.css" type="text/css" rel="stylesheet" />

    <script>
        $(function() {
            $('#btn-json-viewer').click(function() {
                try {
                    var input = eval('(' + $('#json-input').val() + ')');
                }
                catch (error) {
                    return alert("Cannot eval JSON: " + error);
                }
                var options = {
                    collapsed: $('#collapsed').is(':checked'),
                    withQuotes: $('#with-quotes').is(':checked')
                };
                $('#json-renderer').jsonViewer(input, options);
            });

            // Display JSON sample on load
            $('#btn-json-viewer').click();
        });
    </script>
</head>

<body>
<div id="wrapper">
    <header>
        <nav></nav>
    </header>
    <div id="content">
        <?php
        $ch = curl_init('http://192.168.56.222/rest/default/schema?services='.$_GET['service']);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "GET");
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array("Content-Type: application/json", "Authorization: Bearer " . $token));
        curl_setopt($ch, CURLOPT_NOPROXY,'192.168.56.222,spielwiese.local');

        # Send request.
        $result = curl_exec($ch);

        curl_close($ch);
        $info = json_decode($result);
        $tags = $info->tags;
        ?>
        <h1><?php echo $tags[0]->name; ?></h1>
        <?php echo $tags[0]->description; ?>
        <br>
        <br>
        <p>
            Options:
            <label><input type="checkbox" id="collapsed" />Collapse nodes</label>
            <label><input type="checkbox" id="with-quotes" />Keys with quotes</label>
        </p>
        <br>
        <button id="btn-json-viewer" title="run jsonViewer()">Show</button>
        <br>
        <br>
        <pre id="json-renderer"></pre>
        json:
        <textarea id="json-input" autocomplete="off"><?php echo $result; ?></textarea>
    </div>
    <div id="rightcol">
        <?php
        $tags = (array) $services;
        echo "<ul>\n";
        foreach($tags['tags'] as $service){
            echo "<li><a href=\"doku.php?service=".$service->name."\">" . $service->name ."</a></li>\n";
        }
        echo "</ul>\n";
        ?>
    </div>
    <footer>footer</footer>
</div>
<script src="js/scripts.js"></script>
</body>
</html>
