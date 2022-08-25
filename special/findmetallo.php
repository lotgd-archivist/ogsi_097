<?php
require_once("common.php");
require_once("common2.php");
$caso = e_rand(0,99);
if ($caso > 60) {
   $oggetto = "un `9Pezzo di Carbone`2";
   $header = "un Pezzo di Carbone";
   $id = 3;
}elseif ($caso <=60 AND $caso>30) {
   $oggetto = "una `#Scaglia di Ferro`2";
   $header = "una Scaglia di Ferro";
   $id = 1;
}elseif ($caso <=30 AND $caso>9) {
   $oggetto = "una `(Scaglia di Rame`2";
   $header = "una Scaglia di Rame";
   $id = 2;
}elseif ($caso <=9 AND $caso>2) {
   $oggetto = "una `&Scaglia di Argento`2";
   $header = "una Scaglia di Argento";
   $id = 5;
}else {
   $oggetto = "una `^Scaglia d'Oro`2";
   $header = "una Scaglia d'Oro";
   $id = 7;
}
page_header($header);
output("`2Mentre girovaghi senza scopo nella foresta, un oggetto tra il fogliame attira la tua attenzione.`n");
output("Ti avvicini alle foglie che nascondono l'oggetto, e scostandole scopri con meraviglia ");
output("che sotto il fogliame si nasconde $oggetto!!!`n`n");
if (!zainoPieno($session['user']['acctid'])){
   output("`#È stata proprio una giornata fortunata!`n`n");
   $sqli="INSERT INTO zaino (idoggetto, idplayer) VALUES ('".$id."', '".$session['user']['acctid']."')";
   db_query($sqli) or die(db_error(LINK));
   debuglog("trova ".$oggetto." nella foresta");
} else {
   output("`%È un vero peccato che tu abbia lo zaino pieno e non possa raccoglierla !!`n");
   output("Forse faresti meglio a vendere qualcuno dei materiali che ti porti appresso per alleggerire ");
   output("lo zaino e far posto ad eventuali materiali che potresti trovare nella foresta.`n");
}
// Se arrivo dal giardino incantato devo aggiungere navigazione per rientro nella foresta
if ($session['user']['locazione'] == '119') {
	addnav("`3Prosegui nella Foresta","forest.php");
}
?>