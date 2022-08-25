<?php
require_once("common.php");
require_once("common2.php");
$caso = e_rand(0,99);
if ($caso > 60) {
   $oggetto = "un `7Sacchetto con della Silice`2";
   $header = "un Sacchetto con della Silice";
   $id = 20;
}elseif ($caso <=60 AND $caso>25) {
   $oggetto = "un `^Sacchetto con dello Zolfo`2";
   $header = "un Sacchetto con dello Zolfo";
   $id = 21;
}else {
   $oggetto = "una `9Foglia di Mandragola`2";
   $header = "una Foglia di Mandragola";
   $id = 22;
}
page_header($header);
output("`2Mentre girovaghi senza scopo nella foresta, un oggetto tra il fogliame attira la tua attenzione.`n");
output("Ti avvicini alle foglie che nascondono l'oggetto, e scostandole scopri con meraviglia ");
output("che sotte il fogliame si nasconde $oggetto!!!`n`n");
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
addnav("Torna alla foresta","forest.php");
page_footer();
?>