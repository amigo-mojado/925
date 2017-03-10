<?php
$magentoDir = 'Magento-CE-2.1.5-2017-02-20-05-39-14'.DIRECTORY_SEPARATOR.'vendor'.DIRECTORY_SEPARATOR.'magento';

$optional = array(
"moduleadminnotification",
"moduleadvancedpricingimportexport",
"moduleauthorizenet",
"modulebraintree",
"modulebundleimportexport",
"modulecacheinvalidate",
"modulecaptcha",
"modulecatalogruleconfigurable",
"modulecatalogwidget",
"modulecheckoutagreements",
"moduleconfigurableimportexport",
"modulecookie",
"modulecurrencysymbol",
"modulecustomerimportexport",
"moduledeploy",
"moduledhl",
"moduledownloadableimportexport",
"moduleencryptionkey",
"modulefedex",
"modulegoogleadwords",
"modulegoogleanalytics",
"modulegoogleoptimizer",
"modulegroupedimportexport",
"modulelayerednavigation",
"modulemarketplace",
"modulemultishipping",
"modulenewrelicreporting",
"moduleofflinepayments",
"moduleofflineshipping",
"modulepaypal",
"modulepersistent",
"moduleproductvideo",
"modulesalesinventory",
"modulesampledata",
"modulesendfriend",
"modulesitemap",
"moduleswagger",
"moduleswatches",
"moduleswatcheslayerednavigation",
"moduletaximportexport",
"moduleups",
"moduleusps",
"moduleversion",
"modulewebapisecurity",
"moduleweee");

$readme = array();
$files = scandir($magentoDir); //Ordner "files" auslesen

foreach ($files as $dir) { // Ausgabeschleife
	if(is_dir($magentoDir.DIRECTORY_SEPARATOR.$dir)){
		if (strpos($dir, 'module') !== false) {
			if(file_exists($magentoDir.DIRECTORY_SEPARATOR.$dir.DIRECTORY_SEPARATOR."README.md")){
				$file = file_get_contents($magentoDir.DIRECTORY_SEPARATOR.$dir.DIRECTORY_SEPARATOR."README.md");
				$readme[$dir] = $file;
			}
		}
	}
}

$readmeTxt = "";
foreach($readme as $key=>$content){
	$readmeTxt .= "=== ".$key." ===\n\n";
	$readmeTxt .= "<code>".$content."</code>\n\n";
}
$txt = "====== Modules: ======\n\n";
$txt .= "Magento Vendor Modules:\n\n";
$txt .= $readmeTxt;
$txt .= "[[|Top]]\n\n";

$file = 'DokuWikiStick'.DIRECTORY_SEPARATOR.'dokuwiki'.DIRECTORY_SEPARATOR.'data'.DIRECTORY_SEPARATOR.'pages'.DIRECTORY_SEPARATOR.'magento'.DIRECTORY_SEPARATOR.'vendor-magento-modules.txt';
file_put_contents($file,$txt);