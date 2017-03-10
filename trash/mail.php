<?php
$empfaenger = "empf@domain.de";
$betreff = "Die Mail-Funktion";
$from = "From: Nils Reimers <absender@domain.de>\n";
$from .= "Reply-To: antwort@domain.de\n";
$from .= "Content-Type: text/html\n";
$text = "Hier lernt Ihr, wie man mit <b>PHP</b> Mails
verschickt";

//mail($empfaenger, $betreff, $text, $from);

//hide next line:
include("debug.php");