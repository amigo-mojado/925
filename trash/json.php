<?php
$file = file_get_contents('data.json');
$json_decode = json_decode($file);

//hide next line:
include("debug.php");