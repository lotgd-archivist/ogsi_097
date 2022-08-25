<?php
/*****************************************/
/* Waterfall                             */
/* ---------                             */
/* Written by Kevin Kilgore              */
/* (with some creative help by Jake Taft)*/
/* tradotto in italiano da Excalibur     */
/*****************************************/

$session['user']['specialinc']="waterfall.php";
switch($_GET['op']){
case "search":
case "":
output("`n`3Vedi un piccolo sentiero che si allontana dalla strada maestra. Il sentiero è invaso di piante e non lo ");
output("avevi quasi notato.`n`n");
output("Chinandoti per studiare le tracce, noti delle impronte che vanno verso il sentiero ma, stranamente, neanche una che ne esce.");
output("Mentre osservi le impronte senti un suono come di acqua corrente.`n");
addnav("S?`&Segui il Sentiero","forest.php?op=trail");
addnav("F?`@Prosegui nella Foresta","forest.php?op=leave");
$session['user']['specialinc']="waterfall.php";
break;

case "trail":
output("`2Imbocchi il sentiero ed inizi ad esplorare...`n`n");
$session['user']['clean'] += 1;
$rand = e_rand(1,12);
switch ($rand)
{ case 1:case 2: case 3: case 4: case 5:
output("`n`2Dopo qualche ora di esplorazione ti rendi conto che ti sei perso.`n`n");
output("`7Perdi un turno di combattimento per ritrovare la strada di casa.`n`n");
if ($session['user']['turns']>0) $session['user']['turns']--;
debuglog("perde 1 combattimento alla cascata");
$session['user']['specialinc']="";
break;
case 6: case 7: case 8:
output("`^Dopo qualche minuto di esplorazione trovi una piccola cascata!`n`n");
output("`2Noti anche una piccola sporgenza tra le rocce della cascata.`n");
output("Vuoi salire sulla sporgenza?");
addnav("S?`&Vai alla Sporgenza","forest.php?op=ledge");
addnav("F?`@Ritorna alla Foresta","forest.php?op=leaveleave");
break;
case 9: case 10: case 11: case 12:
output("`^Dopo qualche minuto di esplorazione trovi una piccola cascata!`n");
output("`2Assetato per la camminata devi decidere se bere o no.`n");
addnav("B?`&Bevi","forest.php?op=drink");
addnav("F?`@Ritorna alla Foresta","forest.php?op=leaveleave");
break;
}
break;

case "ledge":
$fall = e_rand(1,10);
$session['user']['specialinc']="";
switch ($fall)
{ case 1: case 2: case 3: case 4:
output("Sali cautamente sulla sporgenza dietro la cascata e trovi... ");
$gems = e_rand(1,2);
if ($gems == 1) output("`^$gems gemma!`n");
else output("`^$gems gemme!`n");
$session['user']['gems'] += $gems;
debuglog("trova $gems gemma(e) alla cascata.");
break;
case 5: case 6: case 7: case 8:
$lhps = round($session['user']['hitpoints']*.25);
$session['user']['hitpoints'] = round($session['user']['hitpoints']*.75);
output("Sali cautamente sulla sporgenza dietro la cascata ma
non abbastanza attentamente!`n");
output("Scivoli e cadi ferendoti.`n`n");
output("`\$Hai perso $lhps HP nella caduta!");
if ($session['user']['gold']>0)
{$gold = round($session['user']['gold']*.15);
$session['user']['gold'] -= $gold;
output("`5Noti anche che hai perso`^ $gold pezzi d'oro`5 nello scivolone.`n`n");
debuglog("perde $gold oro quando è caduto nell'acqua alla cascata.");
}
break;
case 9:
output("`7Mentre cammini sulla sporgenza scivoli e cadi,`n");
output("colpendo le rocce che stavano dietro l'acqua della cascata!`n`n");
output("`4`nSei morto! Potrai continuare a combattere domani.`n");
$session['user']['turns'] = 0;
$session['user']['hitpoints'] = 0;
$session['user']['gold'] = 0;
$session['user'][alive] = false;
debuglog("muore e perde {$session['user']['gold']} oro quando è caduto dall'alto della cascata.");
addnews("`%Il corpo straziato di ".$session['user']['name']." si trova parzialmente sommerso vicino alle rocce dietro la cascata.");
addnav("`^Notizie giornaliere","news.php");
break;
case 10:
output("Sali cautamente sulla sporgenza dietro la cascata e trovi ... ");
output("`^1 ricetta!`n`n");
if (!zainoPieno($session['user']['acctid'])){
	$sqldr="INSERT INTO zaino (idoggetto,idplayer) VALUES ('6','{$session['user']['acctid']}')";
	db_query($sqldr);
	debuglog("trova la ricetta id 6 ");
} else {
   output("`%È un vero peccato che tu abbia lo zaino pieno e non possa raccoglierla !!`n");
   output("Forse faresti meglio a vendere qualcuno dei materiali che ti porti appresso per alleggerire ");
   output("lo zaino e far posto ad eventuali materiali che potresti trovare nella foresta.`n`n");
}

output("`^Perdi un turno per ritrovare il sentiero che conduce alla foresta.`n`n");
if ($session['user']['turns']>0) $session['user']['turns']--;
$session['user']['specialinc']="";
break;


}
break;

case "drink":
$session['user']['specialinc']="";
$cnt = e_rand(1,6);
switch ($cnt)
{ case 1: case 2: case 3:
output("`2Bevi dalla cascata e ti senti ristorato!`n`n");
output("`^I tuoi punti vita sono riportati al massimo!");
if ($session['user']['hitpoints'] < $session['user']['maxhitpoints'])
$session['user']['hitpoints']=$session['user']['maxhitpoints'];
break;
case 4:
output("`2Cammini alla base della cascata e bevi una profonda sorsata di acqua pura.`n");
output("Mentre bevi, senti uno strano formicolio percorrere tutto il tuo corpo...`n");
output("Ti senti rinfrescato e più in forma che mai!`n`n");
output("`^I tuoi punti vita sono stati ristabiliti e vengono aumentati di 1");
$session['user']['maxhitpoints']++;
$session['user']['hitpoints'] = $session['user']['maxhitpoints'];
debuglog("guadagna 1 HP permanente alla cascata");
break;
case 5: case 6:
output("`2Bevi dalla cascata ed inizi a sentirti strano. Ti siedi e percepisci la febbre che sale.`n");
output("`4Perdi un turno foresta mentre recuperi le tue forze!");
if ($session['user']['turns']>0) {
    $session['user']['turns']--;
    debuglog("perde 1 combattimento alla cascata");
}
break;
}
break;

case "leave":
output("Osservi il sentiero per qualche istante cercando di trovare il coraggio di esplorarlo. ");
output("Un gelo pungente corre per la tua spina dorsale, ed inizi a tremare. A questo punto ");
output("hai deciso di rimanere sulla strada principale, e ti allontani velocemente dal sentiero misterioso.");
$session['user']['specialinc']="";
break;

case "leaveleave":
output("Hai deciso che la discrezione è parte fondamentale del valore, o almeno della sopravvivenza, e ritorni alla foresta.");
$session['user']['specialinc']="";
break;
}
?>

