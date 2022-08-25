<?php
require_once("common.php");
    checkday();
page_header("La dimora di Jabba");
if($_GET[op]=="buy"){
       $session[user][casa]=1;
       $session[user][maxhitpoints]=intval($session[user][maxhitpoints]*0.65);
	   $session[user][hitpoints]=$session[user][maxhitpoints];
	   checkday();
       output("Adesso possiedi una bella casetta di pietra. È situata nella via principale del villaggio, proprio di
	   fianco alla via del mercato. Ti è costata cara, ma adesso sei decisamente più al sicuro dai malintenzionati. 
	   Fanne buon uso.");
}

if ($session[user][casa]==0 and $session[user][reincarna]> 0 ) {
addnav("Acquista");
addnav("35% dei tuoi HP - Casa di pietra","jabba.php?op=buy");
addnav("Esci");
addnav("Torna al Villaggio","village.php");
    output("`3Benvenuto al Mercato Immobiliare di `6Jabba`3. Qui ti viene data la possibilità di acquistare un tetto 
	da mettere sopra la tua testa per proteggerti dai guerrieri senza scrupoli.`n");
	output("Vi starete chiedendo quanto potrà costare questa stupenda opportunità che vi viene offerta ...`n");
	output("Conoscendo le origini di Jabba, nato in Transilvania, egli non si accontenterà di niente di meno che 
	della vostra forza vitale, i vostri HitPoints.`n");
    output("Ovviamente non sarai al sicuro al 100% ma di questi tempi chi lo è ?`n");
	//output("Ecco i livelli di protezione offerti dai vari tipi di abitazione con i relativi costi`n`n");
	output("`4Casa di pietra    - 35% `&minor chance di essere attaccati - `435% dei tuoi HP`n");
	}
	
	else {
		   output("`3La casa è vuota e polverosa, dei brividi ti corrono lungo la schiena.`n");
		output("`3Ti senti osservato, e pensi che è meglio andarsene di quì, forse non sei ancora pronto.`n");
		addnav("Torna al Villaggio","village.php");
		 } 
		 
page_footer();
?>
