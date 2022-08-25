<?php

/*
* gypsycamp.php
*
* Script dell'Accampamento degli zingari alla periferia del paese
*
* @version 0.9.7 jt
* @written by Hugues of www.ogsi.it
* @texts by Shinku of www.ogsi.it
*
*/

require_once("common.php");
page_header("Il Campo degli Zingari");
output("`c`b`%L'Accampamento degli Zingari`0`b`c`n");
$session['user']['locazione'] = 136;
checkday();

if (!isset($session)) exit();
if ($_GET[op] == "") {
    output ("`n`%Girovagando per il villaggio raggiungi la periferia di `@RafflinGate`%, e ti ritrovi ad osservare con meraviglia l'Accampamento degli Zingari: la moltitudine di colori che contraddistinguono il campo gitano ti ricordano le decorazioni di una festa e, incuriosito, 
    		ti addentri fra i carrozzoni e i tendoni `5va`(rio`^pin`Rti`%, lungo il sentiero sterrato. I diversi chioschi e le attrazioni presenti catturano la tua attenzione, accompagnati dalle voci dei loro proprietari disposti ad offrire ai passanti i loro servigi. ");
    output ("Agghindata di braccialetti e vesti colorate, una giovane gitana danza con trasporto attorno al `\$falò`% che è al centro del campo, producendo un piacevole tintinnio ad ogni suo passo. Uno zingaro dall'aria truce, seduto poco distante, l'accompagna con il suo liuto.
			Osservi qua e là i tendoni, ascoltando con interesse le descrizioni che i venditori fanno delle loro merci o delle loro mirabolanti offerte. La prima cosa ad attirare la tua attenzione è un tendone di un cupo color `!blu notte`%, piuttosto ampio e spazioso, sulla cui soglia un uomo, 
			agghindato come uno stregone, si sgola per cercare di attirare potenziali clienti, invitandoli ad entrare. "); 
	output ("`n`i`0Venite, signore e signori a mettervi in contatto con i vostri cari defunti! Per una modica cifra, potrete parlare loro ancora una volta! Venite!`i`% strilla l'uomo, che zoppicando si sorregge appoggiandosi ad un bastone nodoso. "); 
	output("`nPassando oltre, noti un bellissimo carrozzone color dell'`6oro`%, ed una vecchietta dall'aria furba che ti invita ad entrare per osservare i suoi preziosi.`n`i`0Possiedo `&gemme`0 di ogni forma e colore`i`% ti dice, scostando il velo che copre l'entrata. `0`iDal momento 
			che mi sembri una brava persona, sono disposta a vendertele per poche `^monete d'oro`0 l'una, un vero affare!`i`% ");
	output("`nLa terza struttura sul tuo cammino è un tendone di un vivido `5viola ametista`%, al cui esterno una fanciulla piuttosto giovane ti sorride gentile, invitandoti ad entrare. `0`iPosso rivelarti il futuro leggendo i tarocchi`i`% spiega, aggiustandosi la bandana che porta 
			sulla testa. `0`iTi costerà solamente `&una gemma`&,`0 ed in cambio avrai una lettura accurata.`i");
	output("`n`%Poco distante scorgi anche la Giostra del Paese, che a quanto puoi capire altri non è che una Ruota della Fortuna. Ripromettendoti di tornare a fare una prova, ti dirigi verso l'ultimo stabile, di un bel `@verde brillante`%. Ti avvicini per osservare con attenzione le 
			merci che vende, e ti accorgi che si tratta di costumi di scena provenienti dai più improbabili palcoscenici del reame.");
	output("`n".($session['user']['sex']?"Il proprietario":"La proprietaria")." ti avvicina, sorridendo affabilmente.`i`0Ti piacciono i miei abiti? Questi sono una piccola parte, all'interno ce ne sono molti di più`i`% ti dice, con voce suadente. `0`iVedi, ti rivelerò un piccolo segreto: non sono semplici 
			travestimenti, sono dotati di poteri magici. `nVieni dentro il mio carrozzone, ti spiegherò meglio la magia che li pervade e potremo scegliere quello più adatto alle tue esigenze.`i`0 "); 		   
   
    addnav ("Osserva");
    addnav("M?Parla con i Morti","gypsy.php?op=paydead");
    addnav("G?Compra Gemme","gypsy.php?op=compra");
    addnav("C?Leggere le Carte","gypsy.php?op=futuro");
    addnav("P?La Giostra del Paese","ruota.php");
    $titolo = ($session['user']['sex']?"Monsieur":"Madame");
    addnav($titolo." Déguise","deguise.php");
    addnav ("V?Torna al Villaggio","village.php");

}
page_footer();
?>

