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
        <nav></nav>
    </header>
    <div id="content">&nbsp;
        <?php foreach($errors as $error){echo "<div class=\"error\">".$error."</div>\n";} ?>
        <?php foreach($view['content'] as $html){echo $html;} ?>
    </div>
    <div id="rightcol"><?php foreach($view['sidebar'] as $html){echo $html;} ?></div>
    <footer><pre><?php print_r($_SESSION); ?></pre></footer>
</div>
<script src="js/scripts.js"></script>
</body>
</html>