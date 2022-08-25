<?php

/********************
Allenatore di Draghi del Villaggio
Written by Maximus for www.ogsi.it
*********************/

require_once("common.php");
checkday();
page_header("L'Istruttore dei Cavalieri di Draghi");
$session['user']['locazione'] = 104;

switch($_GET[op]){
    case "":
		output("`3Accanto al serraglio di `2Ukhtrak`3, si trova la tenda di suo fratello `2Ukhtrok`3, rinomato istruttore di Cavalieri di Draghi. `nIl tuo naso ti informa subito che l'avversione per l'acqua deve essere per questi fratelli ");
        output("una vera e propria tradizione familiare, ma la fama di `2Ukhtrok`3 `nè tale che, dopo aver fatto alcuni respiri profondi, decidi comunque di entrare. ");
		output("`nIl grosso troll ti viene incontro sorridente e rapidamente ti spiega che, in cambio di qualche gemma, sarebbe disponibile ad addestrare nuovi allievi. ");

         break;
    case "allena":
         if ($session['user']['dragonkills'] >= 18 OR $session['user']['reincarna'] > 0) {
            if ($session['user']['gems']>=1) {
               if ($session['user']['cavalcare_drago']<=25) {
                   output("`3Paghi una gemma a `2Ukhtrok`3 e questi inizia subito ad insegnarti come si addomestica e si addestra un drago. La lezione è talmente avvincente che per un pò dimentichi addirittura di respirare, con somma gioia del tuo povero naso. ");
                   output("Sei immensamente felice per le nuove tecniche che hai appreso e ti congedi da `2Ukhtrok`3 ringraziandolo calorosamente per la sua disponibilità. ");
                   output("Hai guadagnato un Punto Cavalcare! ");
                   $session['user']['gems']--;
                   $session['user']['cavalcare_drago']++;
                   debuglog("paga `#1`3 gemma a `2Ukhtrok`3 e guadagna 1 punto cavalcare");
                   if ($session['user']['superuser']>1) {
                      output("`@`n`nDebug: Ora hai ".$session['user']['cavalcare_drago']." punti cavalcare");
                   }
               } else {
                   output("`3Porgi una gemma ad `2Ukhtrok`3 ma questi la rifiuta con decisione : `n'`^Mi dispiace ma conosci già tutto quello che potrei insegnarti. Per completare la tua preparazione potrai chiedere consigli ai cavalieri esperti `nche incontrerai ");
                   output("nelle Terre dei Draghi, oppure potrai cercare di incontrare il mio vecchio maestro, che sta trascorrendo i suoi ultimi anni vivendo `nda eremita ritirato nel profondo della foresta. `nSi favoleggia anche di Trattati sui Draghi, custoditi in un luogo misterioso, ma io non ne ho mai mai trovati.");
				   output("`3'`nDetto questo `2Ukhtrok`3 ti augura buona fortuna e si congeda da te per dedicarsi alle sue occupazioni. ");

               }
            } else {
                output("`3Frughi disperatamente in tutte le tue tasche ma non riesci a trovare nemmeno una gemma. `2Ukhtrok`3 ti guarda con un sorriso indulgente e ti dice che, quando avrai di che pagarlo, ");
                output("`nsarà sempre lieto di farti da maestro. Rassegnato abbandoni l'idea di seguire una lezione da un vero istruttore per Cavalieri di Draghi e ti allontani mesto. L'unico a gongolare è il tuo naso! ");
            }
         } else {
	         output("`3Porgi una gemma ad `2Ukhtrok`3 ma questi , dopo averti guardato più attentamente, la rifiuta con decisione : '`^Mi dispiace ma sei troppo giovane per poter diventare uno dei miei allievi. ");
	         output("`nDevi essere reincarnato oppure aver ucciso almeno 19 volte il Drago `@Verde`3 per poter diventare un Cavaliere di Draghi.");
			 output("Detto questo `2Ukhtrok`3 ti augura buona fortuna e si congeda da te dedicandosi alle sue attività ");
         }
         break;
}

addnav("G?Paga una Gemma", "allenatore_drago.php?op=allena");
addnav("V?Torna al Villaggio", "village.php");
page_footer();
?>