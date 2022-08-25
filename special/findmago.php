<?php
require_once("common.php");
require_once("common2.php");
$caso = e_rand(0,99);
if ($caso > 60) {
   $oggetto = "un `7Sacchetto con una dose di Silice`2";
   $header = "un Sacchetto con una dose di Silice";
   $id = 20;
}elseif ($caso <=60 AND $caso>25) {
   $oggetto = "un `^Sacchetto con una dose di Zolfo`2";
   $header = "un Sacchetto con una dose di Zolfo";
   $id = 21;
}else {
   $oggetto = "un `9Sacchetto con una foglia di Mandragola`2";
   $header = "un Sacchetto con una foglia di Mandragola";
   $id = 22;
}
page_header($header);
output("`2Stai girovagando nella foresta alla ricerca di nuove avventure, quando ad un tratto senti un fruscio alle tue spalle, ");
output("di scatto ti giri temendo l'avvicinarsi di qualche pericolosa creatura, ma invece nulla !!`nEra solo un piccolo scoiattolo..... `n");
output("Sospirando di sollievo guardi la bestiola arrampicarsi su un albero ma, sgomento, ti rendi conto che nel voltarti per ");
output("affrontare il possibile nemico, il borsellino ti si è sganciato dalla cintura ed è caduto nel fosso che costeggia il ");
output("sentiero che stavi percorrendo. Preoccupato di perdere tutte le monete in esso contenute, ti butti a capofitto in una frenetica ");
output("ricerca tra le erbacce e le piante acquatiche che infestano il fosso semi allagato.`n`n");
output("Dopo qualche minuto di panico, ritrovi finalmente il tuo portamonete e vedi un oggetto seminascosto dal fogliame. ");
output("Scostando le erbacce e scacciando un ranocchio che ti guarda curioso, scopri con meraviglia che si tratta di $oggetto!!!`n`n");
if (!zainoPieno($session['user']['acctid'])){
   output("`#Devi proprio ammettere che è stata una vera giornata fortunata! `n`n");
   $sqli="INSERT INTO zaino (idoggetto, idplayer) VALUES ('".$id."', '".$session['user']['acctid']."')";
   db_query($sqli) or die(db_error(LINK));
   debuglog("trova ".$oggetto." nella foresta");
} else {
   output("`%È un vero peccato che tu abbia lo zaino pieno e non possa raccogliere il sacchetto !!`n");
   output("Forse faresti meglio a vendere qualcuno dei materiali che esso contiene per alleggerirlo ");
   output("e far posto ad eventuali altri oggetti di maggior pregio che potresti trovare nella foresta.`n");
}
// Se arrivo dal giardino incantato devo aggiungere navigazione per rientro nella foresta
if ($session['user']['locazione'] == '119') {
	addnav("`3Prosegui nella Foresta","forest.php");
}	
?>