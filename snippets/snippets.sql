BEGIN TRANSACTION;
CREATE TABLE "snippets" (
	`id`	INTEGER NOT NULL PRIMARY KEY AUTOINCREMENT UNIQUE,
	`category`	TEXT,
	`description`	TEXT,
	`code`	TEXT
);
INSERT INTO `snippets` VALUES (1,'Test','','Test');
INSERT INTO `snippets` VALUES (2,'MySQL','show tables','<?php
try {
	$dbh = new PDO(''mysql:host=localhost;dbname=dbname'', ''root'', ''password'');
} catch (PDOException $e) {
	print "Error!: " . $e->getMessage() . "<br/>";
	die();
}

$tableList = array();
$result = $dbh->query("SHOW TABLES");
while ($row = $result->fetch(PDO::FETCH_NUM)) {
	$tableList[] = $row[0];
}

foreach($tableList as $table_name){
	echo $table_name."<br>\n";
}');
COMMIT;
