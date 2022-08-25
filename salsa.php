<?php
require_once "common.php";

page_header("La Salsamenteria di Lulù");
$session['user']['locazione'] = 168;
addnav("Pozioni Forti");
addnav("`4Pozione della Forza","salsa.php?op=forza");
addnav("R?`4Pozione della Robustezza","salsa.php?op=robu");
addnav("V?`2Pozione della Vita","salsa.php?op=vita");
addnav("g?`3Pozione del Viaggiatore","salsa.php?op=viaggio");
addnav("Pozioni Potenti");
addnav("z?`\$Pozione della Potenza","salsa.php?op=potenza");
addnav("l?`\$Pozione della Pellaccia","salsa.php?op=pelle");
addnav("Altro");
//addnav("Negozio di Sgarro","sgarro.php");
addnav("T?`@Torna al Borgo","villaggio.php");
output("`3`c`bLa Salsamenteria di Lulù`b`c `n`n `&Entri nella Salsamenteria di Lulù, una affascinante donna che ti accoglie e ti dice: Cosa posso fare per te?`n");
output(" Ho alcune eccezionali offerte di `4Pozioni della Potenza`3 oggi... Guarda qui:`n");
output(" `4Queste pozioni non sono permanenti, il loro effetto avrà termine quando avrai ucciso il drago. `n");
output(" Con l'eccezione di quelle che danno HP (Punti Vita), quelli rimarranno. `n");
output("`3Ne ho alcune tra cui puoi scegliere, il prezzo è adeguato e mi aiuta a pagare il mutuo del negozio. `n`n");
output(" `3La Pozione della Forza costa `61 gemme`3 oggi. Ti darà 1 punto attacco. `n");
output(" `3La Pozione della Robustezza costa `61 gemme`3 oggi. Ti darà 1 punto difesa.`n");
output(" `3La Pozione della Vita costa `61 gemme`3 oggi. Ti darà 1 HP massimo supplementare.`n");
output(" `3La Pozione della Viaggiatore costa `61000 pezzi d'oro`3 oggi. Ti darà 2 Combattimenti supplementari.`n`n");
output(" `4Queste pozioni al contrario sono permanenti, ed il loro costo è superiore ma adeguato al loro valore. `n");
output(" `3La Pozione della `\$Potenza`3 costa `615 gemme`3 oggi. Ti darà 1 punto attacco `i`^Permanente`3`i. `n");
output(" `3La Pozione della `\$Pellaccia`3 costa `615 gemme`3 oggi. Ti darà 1 punto difesa `i`^Permanente`3`i. `n");
output("`3Queste sono tutte le pozioni che ho disponibili oggi. Forse domani ne avrò di altre ...`n`n");

if ($_GET[op]=="forza"){

if ($session['user']['gems'] > 0){
$session['user']['attack']++;
$session['user']['gems']-=1;
output(" `3Lulù ti porge un flaconcino e prende 1 delle tue gemme.`n");
output(" Sei impaziente, così apri la boccetta e ne bevi il contenuto - WOW! `n");
output("Ti senti più prestante! I tuoi punti attacco sono aumentati di 1");
}
else {

output(" `n`n `5Non puoi permettertelo mio caro `&". $session['user']['name'] ."`5, torna quando potrai.");
}

}if ($_GET[op]=="robu"){

if ($session['user']['gems'] > 0){
$session['user']['defence']++;
$session['user']['gems']-=1;
output(" `3Lulù ti porge un flaconcino e prende 1 delle tue gemme.`n");
output(" Sei impaziente, così apri la boccetta e ne bevi il contenuto - WOW! `n");
output("Ti senti più prestante! I tuoi punti difesa sono aumentati di 1");
}
else {

output(" `n`n `5Non puoi permettertelo mio caro `&". $session['user']['name'] ."`5, torna quando potrai.");
}

}if ($_GET[op]=="vita"){

if ($session['user']['gems'] > 0){
$session['user']['maxhitpoints']++;
$session['user']['hitpoints']++;
$session['user']['gems']-=1;
debuglog("spende 1 gemma per 1 HP da Lulù");
output(" `3Lulù ti porge un flaconcino e prende 1 delle tue gemme.`n");
output(" Sei impaziente, così apri la boccetta e ne bevi il contenuto - WOW! `n");
output("Senti la vita scorrere nelle tue vene! Hai guadagnato 1 HP supplementare!");
}
else {

output(" `n`n `5Non puoi permettertelo mio caro `&". $session['user']['name'] ."`5, torna quando potrai.");
}
}if ($_GET[op]=="viaggio"){

if ($session['user']['gold'] > 999){
$session['user']['turns']+=2;
$session['user']['gold']-=1000;
output(" `3Lulù ti porge un flaconcino e prende 1.000 dei tuoi pezzi d'oro.`n");
output(" Sei impaziente, così apri la boccetta e ne bevi il contenuto - WOW! `n");
output(" Ti senti impaziente di correre nella foresta per uccidere altre creature.");
}
else {
output(" `n`n `5Non puoi permettertelo mio caro `&". $session['user']['name'] ."`5, torna quando potrai.");
}
}if ($_GET[op]=="potenza"){

if ($session['user']['gems'] > 14){
$session['user']['attack']++;
$session['user']['bonusattack']++;
$session['user']['gems']-=15;
debuglog("spende 15 gemme per 1 Punto Attacco permanente");
output(" `3Lulù ti porge una fiala e prende 15 delle tue gemme.`n");
output(" Sei impaziente, così apri la boccetta e ne bevi il contenuto - WOW! `n");
output("Ti senti più prestante! I tuoi punti attacco sono `i`6PERMANENTEMENTE`3`i aumentati di 1");
}
else {

output(" `n`n `5Non puoi permettertelo mio caro `&". $session['user']['name'] ."`5, torna quando potrai.");
}

}if ($_GET[op]=="pelle"){

if ($session['user']['gems'] > 14){
$session['user']['defence']++;
$session['user']['bonusdefence']++;
$session['user']['gems']-=15;
debuglog("spende 15 gemme per 1 Punto Difesa permanente");
output(" `3Lulù ti porge un flaconcino e prende 15 delle tue gemme.`n");
output(" Sei impaziente, così apri la boccetta e ne bevi il contenuto - WOW! `n");
output("Ti senti più prestante! I tuoi punti difesa sono `i`6PERMANENTEMENTE`3`i aumentati di 1");
}
else {

output(" `n`n `5Non puoi permettertelo mio caro `&". $session['user']['name'] ."`5, torna quando potrai.");
}

}
page_footer();
?>