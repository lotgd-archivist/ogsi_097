<?php
require_once "common.php";

addnav("Pozioni Amorose");
addnav("Pozione del Sorriso","eros.php?op=smile");
addnav("Pozione della Bontà","eros.php?op=kind");
addnav("Pozione dell'Amore","eros.php?op=love");
addnav("Pozione della Virilità","eros.php?op=viril");
addnav("Altro");
addnav("Torna al Castello","castelexcal.php");

page_header("Eros Esotico");
$session['user']['locazione'] = 120;
output("`3`c`bEros Esotico`b`c `n`n `&Entri da Eros Esotico, `3una donna molto vecchia ti accoglie e ti dice, `n");
output(" Ho alcune buone offerte di `4Pozioni d'Amore`3 oggi.`n");
output(" Le preparo io stessa, con la conoscenza appresa da Cupido in persona. `n");
output(" Oh, ... quelli si che erano giorni ... quando ero giovane, come te. `n");
output("`3Ne ho alcune tra cui puoi scegliere, il prezzo è onesto e aiuta a pagare le mie necessità. `n`n");
output(" `3La Pozione del Sorriso costa `^1 gemma`3. Ti darà 4 punti fascino. `n");
output(" `3La Pozione della Bontà costa `^2 gemme`3. Ti darà 8 punti fascino.`n");
output(" `3La Pozione dell'Amore costa `^3 gemme`3. Ti darà 12 punti fascino.`n");
output(" `3La Pozione della Virilità costa `^4 gemme`3. Ti darà 16 punti fascino.`n`n");
output("`3Ora dimmi presto, quale pozione desideri, potrei non essere viva domani sai ...`n");

if ($_GET[op]=="smile"){

if ($session[user][gems] > 0){
$session[user][charm]+=4;
$session[user][gems]--;
debuglog("guadagna 4 punti fascino comprando la Pozione del Sorriso (1 gemma). `@FASCINO: ".$session[user][charm]);
output(" La vecchietta ti porge una fiala e prende 1 delle tue gemme.`n");
output(" Sei impaziente, così apri la fiala e ne bevi il contenuto - WOW! `n");
output("Un sorriso appare sul tuo viso.");
}
else {

output(" `n`n `5Non puoi permettertelo mio caro, torna quando potrai");
}

}if ($_GET[op]=="kind"){

if ($session[user][gems] > 1){
$session[user][charm]+=8;
$session[user][gems]-=2;
debuglog("guadagna 8 punti fascino comprando la Pozione della Bontà (2 gemme). `@FASCINO: ".$session[user][charm]);
output(" La vecchietta ti porge una fiala e prende 2 delle tue gemme.`n");
output(" Sei impaziente, così apri la fiala e ne bevi il contenuto - WOW! `n");
output("Senti sbocciare la Bontà nel tuo Cuore.");
}
else {

output(" `n`n `5Non puoi permettertelo mio caro, torna quando potrai");
}

}if ($_GET[op]=="love"){

if ($session[user][gems] > 2){
$session[user][charm]+=12;
$session[user][gems]-=3;
debuglog("guadagna 12 punti fascino comprando la Pozione dell'Amore (3 gemme). `@FASCINO: ".$session[user][charm]);
output(" La vecchietta ti porge una fiala e prende 3 delle tue gemme.`n");
output(" Sei impaziente, così apri la fiala e ne bevi il contenuto - WOW! `n");
output("Senti un impulso d'Amore percorrere il tuo corpo.");
}
else {

output(" `n`n `5Non puoi permettertelo mio caro, torna quando potrai");
}
}if ($_GET[op]=="viril"){

if ($session[user][gems] > 3){
$session[user][charm]+=16;
$session[user][gems]-=4;
debuglog("guadagna 16 punti fascino comprando la Pozione della Virilità (4 gemme). `@FASCINO: ".$session[user][charm]);
output(" La vecchietta ti porge una fiala e prende 4 delle tue gemme.`n");
output(" Sei impaziente, così apri la fiala e ne bevi il contenuto - WOW! `n");
output(" Ti senti impaziente di correre dal tuo amore, e ringrazi la vecchietta.");
}
else {

output(" `n`n `5Non puoi permettertelo mio caro, torna quando potrai");

}
}
page_footer();
?>
