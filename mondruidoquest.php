<?php
require_once "common.php";
page_header("Le Lezioni del Druido");
output(" `3`c`bLe Lezioni del Druido - Quest`b`c`n`n`3");
addnav("D?Torna dal Druido","mondruido.php");
addnav("M?Torna al Monastero","monastero.php");
addnav("");
addnav("SI, dimmi le Quest","monquest.php");
if (getsetting("nomedb","logd") == "logd2"){
   output("`3\"`XAvanti`3\"`nrisponde il venerabile quando sente bussare alla sua porta.`n");
   output("\"`XBenvenut".($session['user']['sex']?"a Milady":"o Messere").", oggi mi domandate di narrarVi più cose ");
   output("riguardo le quest ed il luogo in cui trovarle, bene, allora sedete innanzi a me ed ascoltate.`3\"`ndice ");
   output("indicando la sedia al di la della scrivania.`n`n");
   output("\"`XBel luogo la foresta incantata che si cela dietro l'imponente roccia curiosa ... luogo di meraviglie ");
   output("... luogo misterioso, mirabile e periglioso ... ad alcuni livelli di gioco e ad alcuni titoli nobiliari ");
   output("cui accederete, voi troverete una prova da affrontare, che potrà portare buoni frutti o acerbi assai ... ");
   output("dovete accostarvi con cautela, al giusto livello e ben preparat".($session['user']['sex']?"a":"o")."; per ");
   output("ora sbirciate soltanto, ma nulla ");
   output("vi sarà dato di vedere ... quando infine avrete raggiunto il livello adeguato o ucciso il vostro primo ");
   output("`@Drago Verde`X, e avrete quindi acquisito il titolo di `GEsploratore`X, potrete aggirarvi nella foresta ");
   output("... vi troverete un luogo di cui non voglio troppo svelare ... procedete con cautela ma senza timore!`3\" ");
   output("`nsorride affabile.`n`n");
   output("Poi tentendo una mano verso una pergamena gualcita:`n\"`XVago nei miei lemmi per rispetto a voi, or vi ");
   output("dimando ... siete proprio sicur".($session['user']['sex']?"a":"o")." di voler anzitempo sapere cosa precisamente vi troverete?`3\"`n`n");
}else{
   output(" `3Così vuoi saperne di più sulle Quest di LoGD! `n`n");
   output(" Ascoltami, `& ".$session['user']['name'].". `3 `n");
   output("`&Nel gioco `2La Leggenda del Drago Verde`&, ci sono svariate missioni che puoi compiere.`n");
   output(" Alcune di esse sono disponibili solo per un determinato `^Titolo Nobiliare`&, altre invece sono accessibili solo`n");
   output(" quando raggiungi un determinato livello. Alcune di esse sono estremamente semplici, in altre puoi rischiare`n");
   output(" la morte. Ti consiglio estrema cautela quando affronti una quest, la morte potrebbe essere dietro l'angolo. `n");
   output(" Tu vieni da me per conoscere tutte le Quest, `\$ma sei proprio sicur".($session['user']['sex']?"a":"o")." di voler sapere in anticipo quali sono e rovinarti la sorpresa`& ?");
}
page_footer();
?>