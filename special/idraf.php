<?php

/*
Idra della palude di Lerna 
Created by Hugues
Script destinato agli amanti del powerplay 
Il giocatore incontra l'Idra di Lerna le cui nove teste si rigenerano una volta sconfitte in sequenza.
Il giocatore può vincere solo se utilizza la Fiamma della Gloria almeno una volta altrimenti può solo darsi alla fuga
L'utilizzo della Fiamma della Gloria viene controllato a inizio script e memorizzato in tabella sql Idra e alla vittoria sulla nona testa
se il numero di colpi di questo spell è calato si decreta la sconfitta del mostro, se è rimasto uguale le teste si rigenerano e si ricomincia
il combattimento.
*/

require_once "common.php";
page_header("L'Idra di Lerna");
$sql1 = "SELECT * FROM items WHERE owner = ".$session['user']['acctid']." AND class='Spell' and name = '`4Fiamma della Gloria' " ;
$result1 = db_query($sql1) or die(db_error(LINK));
$countrow = db_num_rows($result1);
for ($i=0; $i < $countrow; $i++){
	$row1 = db_fetch_assoc($result1);
	$colpi += $row1['value1'];
}
$sql = "SELECT * FROM idra WHERE acctid = ".$session['user']['acctid']." " ;
$result = db_query($sql) or die(db_error(LINK));
$countrow = db_num_rows($result);
if ( $countrow == 0 ) {
	$sql = "INSERT INTO idra (acctid,colpi) VALUES( '".$session['user']['acctid']."','".$colpi."' ) " ;
	$result = db_query($sql) or die(db_error(LINK));
} else {
	$sql = "UPDATE idra SET colpi = '".$colpi."' WHERE acctid = '".$session['user']['acctid']."' " ;
	$result = db_query($sql) or die(db_error(LINK));
}
output("`GMentre stai camminando lungo un sentiero della foresta, ti rendi conto che questa è avvolta in un silenzio surreale, quasi come se tutti gli animali del bosco fossero improvvisamente scomparsi. `n");
output("Ad un tratto il sentiero è bruscamente interrotto da una siepe, ma sembra che questa sia stata messa apposta per nascondere qualcosa. `n");
output("Non riuscendo a trattenere la tua innata curiosità, ti fai largo tra le fronde e.... `n");
addnav("Continua","idra.php");
page_footer();
?>