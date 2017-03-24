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
            <a href="/headless-magento/">home</a> | <a href="/headless-magento/?cart=true">cart</a> | <a href="/headless-magento/?cms=true">cms</a>
            | CMS:
            <?php
                foreach($view['nav']['cms'] as $page){
                    echo $page;
                }
            ?>

        </nav>
    </header>
    <div id="content">&nbsp;
        <?php foreach($messages['errors'] as $message){echo "<div class=\"error\">".$message."</div>\n";} ?>
        <?php foreach($messages['success'] as $message){echo "<div class=\"success\">".$message."</div>\n";} ?>
        <?php foreach($view['content'] as $html){echo $html;} ?>
    </div>
    <div id="rightcol"><?php foreach($view['sidebar'] as $html){echo $html;} ?></div>
    <footer><pre><?php print_r($_SESSION); ?></pre></footer>
</div>
<script src="js/scripts.js"></script>
</body>
</html>