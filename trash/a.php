<?php

$path = realpath('vendor');
$readme = array();
$composer_require = array();
$composer_homepage = array();

$objects = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($path), RecursiveIteratorIterator::SELF_FIRST);
foreach($objects as $name => $object){
	if(!is_dir($name)){
		$path_parts = pathinfo($name);
		/**
		//README:
		if(array_key_exists('extension',$path_parts)){
			$isReadme = str_replace(".".$path_parts['extension'],'',strtolower($path_parts['basename']));
			if($isReadme == 'readme'){
				//echo strtolower($path_parts['basename']) ."\n";
				$readme[] = $name;				
			}
		}
		**/
		//Composer
		if($path_parts['basename'] == 'composer.json'){
			$file = file_get_contents($name);
			$json_decode = json_decode($file);
			if(property_exists($json_decode,'require')){
				$require = (array) $json_decode->require;
			
				foreach(array_keys($require) as $r){
					$composer_require[] = $r;
				}				
			}
			if(property_exists($json_decode,'homepage')){
				$homepage = $json_decode->homepage;
				$composer_homepage[] = $homepage;
			}
			if(property_exists($json_decode,'authors')){
				$authors = $json_decode->authors;
				foreach($authors as $author){
					if(property_exists($author,'homepage')){
						$homepage = $author->homepage;
						$composer_homepage[] = $homepage;
					}
				}

			}
		}
		
	}
}

//toHTML
$html = "";
/**
$html .= "<h2>README:</h2>\n";
foreach($readme as $file){
	$html .= "<strong>".$file."</strong><br>\n";
	$fp = @fopen($file, 'r');

	// Add each line to an array

	if ($fp) {
		$fileContentAsArray = explode("\n", fread($fp, filesize($file)));
		$i = 1;
		$html .= "<div style=\"margin: 4px; background-color: #DDDDDD;\">\n";
		foreach($fileContentAsArray as $line){
			$html .= $i.": ".$line."<br>\n";
			$i++;
		}
		$html .= "</div>\n";
	}	
}


$html .= "<h2>Composer - require:</h2>\n";
$composer_require = array_unique($composer_require);
asort($composer_require);
foreach($composer_require as $r){
	$html .= $r."<br>\n";
}
**/

$html .= "<h2>Composer - homepage:</h2>\n";
$composer_homepage = array_unique($composer_homepage);
asort($composer_homepage);
print_r($composer_homepage);
foreach($composer_homepage as $h){
	$html .= "<a href=\"".$h."\">".$h."</a><br>\n";
}

$file = 'a.html';
file_put_contents($file,"<html>\n<body>\n".$html."</body>\n</html>\n");
