<?php
require_once "common.php";
$session['user']['locazione'] = 102;

page_header("La Farmacia di Adriana");
addnav("Pozioni Forti");
addnav("Pozione della Forza","adriana.php?op=forza");
addnav("Pozione della Robustezza","adriana.php?op=robu");
addnav("Pozione della Vita","adriana.php?op=vita");
addnav("Pozione del Viaggiatore","adriana.php?op=viaggio");
addnav("Pozioni Potenti");
addnav("Pozione della Potenza","adriana.php?op=potenza");
addnav("Pozione della Pellaccia","adriana.php?op=pelle");
addnav("Altro");
addnav("Negozio di Sgarro","sgarro.php");
addnav("Torna al Castello","castelexcal.php");
output("`3`c`bLa Farmacia di Adriana`b`c `n`n `&Entri nella Farmacia di Adriana, una giovane donna che ti accoglie e ti dice: Cosa posso fare per te?`n");
output(" Ho alcune eccezionali offerte di `4Pozioni della Potenza`3 oggi... Guarda qui:`n");
output(" `4Queste pozioni non sono permanenti, il loro effetto avrà termine quando avrai ucciso il drago. `n");
output(" Con l'eccezione di quelle che danno HP (Punti Vita), quelli rimarranno. `n");
output("`3Ne ho alcune tra cui puoi scegliere, il prezzo è adeguato e mi aiuta a pagare il mutuo del negozio. `n`n");
output(" `3La Pozione della Forza costa `^2 gemme`3 oggi. Ti darà 1 punto attacco. `n");
output(" `3La Pozione della Robustezza costa `^2 gemme`3 oggi. Ti darà 1 punto difesa.`n");
output(" `3La Pozione della Vita costa `^2 gemme`3 oggi. Ti darà 1 HP massimo supplementare.`n");
output(" `3La Pozione del Viaggiatore costa `^4000 pezzi d'oro`3 oggi. Ti darà 2 Combattimenti supplementari.`n`n");
output(" `4Queste pozioni al contrario sono permanenti, ed il loro costo è superiore ma adeguato al loro valore. `n");
output(" `3La Pozione della `4Potenza`3 costa `^30 gemme`3 oggi. Ti darà 1 punto attacco `i`6Permanente`3`i. `n");
output(" `3La Pozione della `4Pellaccia`3 costa `^30 gemme`3 oggi. Ti darà 1 punto difesa `i`6Permanente`3`i. `n");
output("`3Queste sono tutte le pozioni che ho disponibili oggi. Forse domani ne avrò di altre ...`n`n");

if ($_GET[op]=="forza"){

if ($session['user']['gems'] > 1){
$session['user']['attack']++;
$session['user']['gems']-=2;
output(" `3Adriana ti porge un flaconcino e prende 2 delle tue gemme.`n");
output(" Sei impaziente, così apri la boccetta e ne bevi il contenuto - WOW! `n");
output("Ti senti più prestante! I tuoi punti attacco sono aumentati di 1");
debuglog("compra 1 punto attacco temporaneo da Adriana");
}
else {

output(" `n`n `5Non puoi permettertelo mio caro `&". $session['user']['name'] ."`5, torna quando potrai.");
}

}if ($_GET[op]=="robu"){

if ($session['user']['gems'] > 1){
$session['user']['defence']++;
$session['user']['gems']-=2;
output(" `3Adriana ti porge un flaconcino e prende 2 delle tue gemme.`n");
output(" Sei impaziente, così apri la boccetta e ne bevi il contenuto - WOW! `n");
output("Ti senti più prestante! I tuoi punti difesa sono aumentati di 1");
debuglog("compra 1 punto difesa temporaneo da Adriana");
}
else {

output(" `n`n `5Non puoi permettertelo mio caro `&". $session['user']['name'] ."`5, torna quando potrai.");
}

}if ($_GET[op]=="vita"){

if ($session['user']['gems'] > 1){
$session['user']['maxhitpoints']++;
$session['user']['hitpoints']++;
$session['user']['gems']-=2;
output(" `3Adriana ti porge un flaconcino e prende 2 delle tue gemme.`n");
output(" Sei impaziente, così apri la boccetta e ne bevi il contenuto - WOW! `n");
output("Senti la vita scorrere nelle tue vene! Hai guadagnato un HP supplementare!");
debuglog("compra 1 HP permanente da Adriana");
}
else {

output(" `n`n `5Non puoi permettertelo mio caro `&". $session['user']['name'] ."`5, torna quando potrai.");
}
}if ($_GET[op]=="viaggio"){

if ($session['user']['gold'] > 3999){
$session['user']['turns']+=2;
$session['user']['gold']-=4000;
output(" `3Adriana ti porge un flaconcino e prende 4000 delle tue monete.`n");
output(" Sei impaziente, così apri la boccetta e ne bevi il contenuto - WOW! `n");
output(" Ti senti impaziente di correre nella foresta per uccidere altre creature.");
debuglog("compra 2 FF da Adriana");
}
else {
output(" `n`n `5Non puoi permettertelo mio caro `&". $session['user']['name'] ."`5, torna quando potrai.");
}
}if ($_GET[op]=="potenza"){

if ($session['user']['gems'] > 29){
$session['user']['attack']++;
$session['user']['bonusattack']++;
$session['user']['gems']-=30;
output(" `3Adriana ti porge una fiala e prende 30 delle tue gemme.`n");
output(" Sei impaziente, così apri la boccetta e ne bevi il contenuto - WOW! `n");
output("Ti senti più prestante! I tuoi punti attacco sono `i`6PERMANENTEMENTE`3`i aumentati di 1");
debuglog("compra 1 punto attacco permanente da Adriana");
}
else {

output(" `n`n `5Non puoi permettertelo mio caro `&". $session['user']['name'] ."`5, torna quando potrai.");
}

}if ($_GET[op]=="pelle"){

if ($session['user']['gems'] > 29){
$session['user']['defence']++;
$session['user']['bonusdefence']++;
$session['user']['gems']-=30;
output(" `3Adriana ti porge un flaconcino e prende 30 delle tue gemme.`n");
output(" Sei impaziente, così apri la boccetta e ne bevi il contenuto - WOW! `n");
output("Ti senti più prestante! I tuoi punti difesa sono `i`6PERMANENTEMENTE`3`i aumentati di 1");
debuglog("compra 1 punto difesa permanente da Adriana");
}
else {

output(" `n`n `5Non puoi permettertelo mio caro `&". $session['user']['name'] ."`5, torna quando potrai.");
}

}
page_footer();
?>