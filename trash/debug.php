<?php
include("system.php");
$system = new system();

$pattern = '/\$([a-zA-Z_\x7f-\xff][a-zA-Z0-9_\x7f-\xff]*)/';
$subject = file_get_contents(basename($_SERVER["PHP_SELF"]));
preg_match_all($pattern, $subject, $matches);
$vars = array_keys(array_flip($matches[count($matches)-1]));

$system->setVars($vars);

$system->setFilename(basename($_SERVER["PHP_SELF"]))->reader();

echo $system->dumpVarsInThisFile(get_defined_vars());