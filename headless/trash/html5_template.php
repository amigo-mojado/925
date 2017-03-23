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
    <div id="content">
        <?php

            if($_GET['categoryId']){
                echo "<h2>Products:</h2><br>\n";

                echo showProducts($categoriesCategoryIdProducts);
            }

            if($_GET['productSku']){
                echo "<h2>Details:</h2><br>\n";

                echo showDetails($productsSku);
                echo "<br><a href=\"?addToCart=true&sku=".$_GET['productSku']."\">cart</a>\n";
            }

            if($_GET['addToCart'] && $_GET['sku']){
                addToCart($_GET['sku']);
            }
        ?>
    </div>
    <div id="rightcol">
        <?php echo makeMenu($categories); ?>
    </div>
    <footer>  </footer>
</div>
<script src="js/scripts.js"></script>
</body>
</html>