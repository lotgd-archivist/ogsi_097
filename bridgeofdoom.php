<?php
/*bridge of doom by Nick Puleo
http://www.happyapplefarm.net 
Translated by Excalibur */
require_once "common.php";
checkday();

page_header("Il Ponte del Destino");
output("`c`b`&Il Ponte del Destino !!`0`b`c");
	
if ($_GET[op]==""){
	  addnav("T?`@Torna al Villaggio","village.php");
	addnav("S?`\$Salta giù dal Ponte","bridgeofdoom.php?op=jump");
	output("`!Ad un'estremità del villaggio c'è un ponticello che attraversa un enorme strapiombo.  Guardando verso il basso vedi un fiume scorrere MOLTO veloce.`n`n");
	}
	
//die	
else if ($_GET[op]=="jump" AND $session[user][turns]>0){
	$chanceofsurvival=e_rand(0,50);
	if ($chanceofsurvival>1){
	output("`!Salti......`n`nCadi........`n`nE`$ MUORI!`n`n");
	$session[user][alive]=false;
	$session[user][hitpoints]=0;
	output("`!Poiché hai azzardato il salto e, poiché sei incredibilmente stupido, perdi un po' di ESPERIENZA!`n`n");
	$xp=$session[user][level]*e_rand(50,100);
	output("`@Perdi `^$xp `@punti esperienza.");
	$session[user][ experience]-=$xp;
	output("`n`n Ovviamente, ora sei morto....");
	addnav("N?`^Notizie Giornaliere","news.php");
	addnews("`%".$session[user][name]."`3 ha deciso che non valeva la pena vivere, ed è saltato dal ponte.");
	debuglog("salta dal ponte, muore e perde $xp esperienza");
	}
else{
	output("`!Salti......`n`nCadi........`n`nE`@ SOPRAVVIVI!`n`n");
	$session[user][hitpoints]+=50;
	output("`!Poiché hai azzardato il salto e, poiché pur essendo incredibilmente stupido sei SOPRAVVISSUTO, guadagni un po' di ESPERIENZA!`n`n");
	$xp=$session[user][level]*e_rand(50,100);
	output("`@Guadagni `^$xp `@punti esperienza.");
	$session[user][ experience]+=$xp;
	$gems=e_rand(1,3);
	$session[user][gems]+=$gems;
	output("`@Mentre ti arrampichi sul lato opposto dello strapiombo, sul tuo cammino trovi `\$$gems GEMME!!! `@tra le rocce !!!`n`n");
	addnav("N?`^Notizie Giornaliere","news.php");
	addnav("T?`@Torna al Villaggio","village.php");
	addnews("`%".$session[user][name]."`3 è saltato dal ponte, ed è sopravvissuto per raccontarlo!");
	debuglog("salta dal ponte, sopravvive, guadagna $xp exp e $gems gemme");
}
}else{
output("`5Sei talmente stremato, che non hai le forze necessarie per compiere il balzo. `nRiprova quando ti sarai ristorato.`n");
addnav("T?`@Torna al Villaggio","village.php");
}
page_footer();
?>