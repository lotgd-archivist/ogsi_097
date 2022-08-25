<?php
if (!isset($session)) exit();
$session['user']['specialinc']="well.php";
if($_GET['op']=="sort"){
$session['user']['gems']--;
//$session['user']['specialinc']="well.php";
switch(e_rand(1,20)){
case 1:
case 2:
case 3:
case 4:
output("`%Gli Dei hanno deciso di non esaudire il tuo desiderio");
addnav("`2Torna al Pozzo","forest.php?op=pozzo");
break;
case 5:
case 6:
$reward = e_rand($session['user']['experience'] * 0.05,$session['user']['experience'] * 0.1);
$session['user']['experience'] += $reward;
output("Ottieni `6$reward`^ Punti Esperienza...");
addnews($session['user']['name']." ha ottenuto `6$reward `^ Punti Esperienza al pozzo dei desideri.");
debuglog("guadagna $reward exp al Pozzo dei Desideri per una gemma");
addnav("`2Torna al Pozzo","forest.php?op=pozzo");
break;
case 7:
case 8:
$gold = e_rand($session['user']['level']
*10,$session['user']['level']*40);
$session['user']['gold'] += $gold;
output("Ottieni `6$gold `^pezzi d'oro...");
addnav("`2Torna al Pozzo","forest.php?op=pozzo");
debuglog("guadagna $gold oro al Pozzo dei Desideri per una gemma");
break;
case 9:
$reward = floor($session['user']['maxhitpoints'] *0.075);
if ($reward==0) {$reward=1; };
if ($reward>5) {$reward=5; };
$session['user']['maxhitpoints'] += $reward;
output("Ottieni `6$reward `^Hitpoints Extra...");
addnews($session['user']['name']." ha ottenuto `6$reward `^Hitpoints Extra al pozzo dei desideri.");
debuglog("guadagna $reward HP permanenti al Pozzo dei Desideri per una gemma");
addnav("`2Torna al Pozzo","forest.php?op=pozzo");
break;
case 10:
$gems = e_rand(1,2);
$session['user']['gems'] += $gems;
output("Ottieni `6$gems `^Gemme...");
debuglog("guadagna $gems gemma(e) al Pozzo dei Desideri per una gemma");
addnav("`2Torna al Pozzo","forest.php?op=pozzo");
break;
case 11:
case 12:
case 13:
case 14:
$ff = e_rand(1,2);
$session['user']['turns'] += $ff;
output("Ottieni `6$ff `^Combattimenti Supplementari...");
debuglog("guadagna $ff combattimenti al Pozzo dei Desideri per una gemma");
addnav("`2Torna al Pozzo","forest.php?op=pozzo");
break;
case 15:
output("`^Hai fatto infuriare gli Dei, e adesso ti ritrovi con 1 solo hitpoint.`n");
$session['user']['hitpoints']=1;
addnav("`2Torna al Pozzo","forest.php?op=pozzo");
break;
case 16:
case 17:
case 18:
case 19:
output("`%Il tuo desiderio non è stato esaudito dagli Dei");
debuglog("spende 1 gemma al Pozzo dei Desideri per niente");
addnav("`2Torna al Pozzo","forest.php?op=pozzo");
break;
case 20:
output("`^Hai fatto infuriare gli Dei, ed ora ti ritrovi senza un solo pezzo d'oro.`n");
debuglog("perde {$session['user']['gold']} oro al Pozzo dei Desideri per una gemma");
$session['user']['gold']=0;
addnav("`2Torna al Pozzo","forest.php?op=pozzo");
break;
}
}else if($_GET['op']=="pozzo"){
//$session['user']['specialinc']="well.php";
if ($session['user']['gems']<1){
output("`3Purtroppo non hai uno straccio di gemma, ed il Pozzo di Jinzat ha il suo regolamento:`n");
output("`6`b\"No gems, no party!\"`3`b E noti sul fondo un riflesso che somiglia in modo impressionante a `7George Clooney`3 con un sorriso beffardo sulle labbra!");
addnav("`\$Allontanati dal Pozzo","forest.php?op=lontano");
}else{
output("`n`%Ora sei vicino al pozzo.`n`n");
addnav("`^Esprimi un Desiderio","forest.php?op=sort");
addnav("`%Allontanati dal Pozzo","forest.php?op=lontano");
}
}else if($_GET['op']=="lontano"){
$session['user']['specialinc']="";
addnav("`@Torna alla Foresta","forest.php");
output("`n`%Hai trovato un sentiero familiare che ti riporta nella fitta foresta.`n`n");
} else if($_GET['op']==""){
if ($session['user']['gems']<1){
output("`3Purtroppo non hai uno straccio di gemma, ed il Pozzo di Jinzat ha il suo regolamento:`n");
output("`6`b\"No gems, no party!\"`3`b E noti sul fondo un riflesso che somiglia in modo impressionante a `7George Clooney`3 con un sorriso beffardo sulle labbra!");
//$session['user']['specialinc']="well.php";
addnav("`%Allontanati dal Pozzo","forest.php?op=lontano");
}else{
output("`n`%Vuoi gettare una gemma nell'acqua ed esprimere un desiderio.`n`n");
addnav("`7Avvicinati al Pozzo","forest.php?op=pozzo");
//$session['user']['specialinc']="well.php";
addnav("`%Allontanati dal Pozzo","forest.php?op=lontano");
}
}
page_footer();
?>