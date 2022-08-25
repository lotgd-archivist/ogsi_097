<?php

/********************
EVENTO SPECIALE
Allenatore di Draghi nella Foresta
Written by Maximus for www.ogsi.it
*********************/

if (!isset($session)) exit();

page_header("Allenatore di Draghi Eremita");
output("`n`c`b`2L'Allenatore di `@Draghi`2 Eremita`0`b`c`n",true);

switch($_GET['op']){
    case "":
         $session['user']['specialinc'] = "allenatore_drago_special.php";
         output("`2Dopo aver scoperto e imboccato un nuovo sentiero da te mai esplorato, spunti in una radura erbosa e ai tuoi occhi appare una casupola di legno piuttosto malridotta.`n");
         output("`2Dato che dal comignolo sale verso il cielo un filo di fumo nero, deduci che questa catapecchia sia abitata.");
         addnav("`2Bussa alla Porta","forest.php?op=bussa");
         addnav("`\$Torna alla Foresta","forest.php?op=scappa");
         break;
    case "scappa":
         output("`2Non sei mai stato curios".($session['user']['sex']?"a":"o").", quindi ripercorri il sentiero a ritroso e ti ritrovi nei luoghi a te familiari e che conosci come le tue tasche`n");
         break;
    case "bussa":
         output("`2Bussi alla porta e dopo aver aspettato qualche secondo questa si apre a alla tua vista appare un vecchio `^Elfo`2 grinzoso e malandato, al quale manca un braccio e con una benda sporca sull'occhio destro.`n");
         // il player non è reincarnato
         if ($session['user']['reincarna'] == 0 AND $session['user']['dragonkills'] < 19) {
            output("`2Questi, Dopo averti squadrat".($session['user']['sex']?"a":"o")." per bene, ti dice che non può aiutarti e sgarbato richiude la porta.`n");
            output("`2Non volendo reagire ad un povero vecchietto, riprendi il sentiero e torni in foresta.");
            //addnav("`\$Torna alla Foresta","forest.php?op=scappa");
            break;
         }
         // il player possiede un Drago
         if ($session['user']['id_drago'] > 0) {
            output("`2Questi, dopo averti squadrat".($session['user']['sex']?"a":"o")." per bene e soprattutto dopo aver osservato attentamente il tuo `@drago`2 illuminandosi in volto ti rivolge la parola:`n`n");
            output("`2\"`^`iVedo che possiedi un `@Drago`^, anche io una volta ne possedevo uno, anzi a dir la verità ero il migliore allenatore di `@Draghi`2 in circolazione nei mondi conosciuti, ma poi la malasorte mi ha ridotto in questo misero stato e sono caduto in disgrazia...`i`@\"`n`n");
            output("`2Il suo volto si fa cupo ma continua ad osservare il tuo `@drago`2... Quindi facendoti coraggio gli domandi se per caso è disposto a 'tramandarti' qualcuno dei suoi segreti di allenatore. Egli inizialmente scrolla il capo, ma poi ci ripensa e accetta di sottoporti ai suoi insegnamenti, ma solo dopo aver ricevuto un piccolo compenso.");
            $session['user']['specialinc'] = "allenatore_drago_special.php";
            addnav("G?`2Paga `&una Gemma","forest.php?op=gemma");
            addnav("1?`2Paga `61000`2 monete d'`6Oro","forest.php?op=1000");
            addnav("5?`2Paga `65000`2 monete d'`6Oro","forest.php?op=5000");
            addnav("`\$Torna alla Foresta","forest.php?op=scappa");
            break;
         }

         // il player è reincarnato ma non possiede un Drago
         output("`2Dopo averti squadrat".($session['user']['sex']?"a":"o")." per bene ti rivolge parola:`n`n");
         output("`2\"`^`iVedo delle potenzialità in te, un giorno potresti diventare un".($session['user']['sex']?"a":"")."grande ".($session['user']['sex']?"allenatrice":"allenatore")." di `@Draghi`2 come lo ero io prima della mia rovina... Penso che per un piccolo compenso potrei svelarti alcuni 'trucchi' del mestiere...`i`@\"`n");
         $session['user']['specialinc'] = "allenatore_drago_special.php";
         addnav("G?`2Paga `&una Gemma","forest.php?op=gemma");
         addnav("1?`2Paga `61000`2 monete d'`6Oro","forest.php?op=1000");
         addnav("5?`2Paga `65000`2 monete d'`6Oro","forest.php?op=5000");
         addnav("`\$Torna alla Foresta","forest.php?op=scappa");
         break;
    case "gemma":
         if ($session['user']['gems']>=1) {
             output("`2Dai una delle tue preziose `&gemme`2 all'`^Elfo`2 e...`n`n");
             $gain = e_rand(1,2);
             $session['user']['cavalcare_drago']+=$gain;
             $session['user']['gems']--;
             output("`2Questi inizia a raccontarti alcune tecniche di addestramento che tu apprendi e che ti consentono di guadagnare `#$gain`2 punti cavalcare!");
             debuglog("paga 1 gemma all'allenatore di draghi e guadagna $gain punti cavalcare");
             if ($session['user']['superuser']>1) {
                output("`@`n`nDebug: Ora hai ".$session['user']['cavalcare_drago']." punti cavalcare");
             }
         } else {
             output("`2Stai per pagare una `&gemma`2 all'`^Elfo`2 ma ti accorgi di non possederne nemmeno una...Ti scusi con lui per la tua sbadataggine e ti congedi tornando nella foresta.");
         }
         break;
    case "1000":
         if ($session['user']['gold']>=1000) {
             output("`2Dai `61000`2 monete d'`6Oro`2 all'`^Elfo`2 e...`n`n");
             $gain = 1;
             $session['user']['cavalcare_drago']+=$gain;
             $session['user']['gold']-=1000;
             output("`2Questi inizia a raccontarti alcune tecniche di addestramento che tu apprendi e che ti consentono di guadagnare `#$gain`2 punti cavalcare!");
             debuglog("paga 1000 oro all'allenatore di draghi e guadagna $gain punti cavalcare");
             if ($session['user']['superuser']>1) {
                output("`@`n`nDebug: Ora hai ".$session['user']['cavalcare_drago']." punti cavalcare");
             }
         } else {
             output("`2Stai per pagare l'`^Elfo`2 ma ti accorgi di non possedere `61000`2 monete d'`6Oro`2...Ti scusi con lui per la tua sbadataggine e ti congedi tornando nella foresta.");
         }
         break;
    case "5000":
         if ($session['user']['gold']>=5000) {
            output("`2Dai `65000`2 monete d'`6Oro`2 all'`^Elfo`2 e...`n`n");
            $gain = e_rand(2,4);
            $session['user']['cavalcare_drago']+=$gain;
            $session['user']['gold']-=5000;
            output("`2Questi inizia a raccontarti alcune tecniche di addestramento che tu apprendi e che ti consentono di guadagnare `#$gain`2 punti cavalcare!");
            debuglog("paga 5000 oro all'allenatore di draghi e guadagna $gain punti cavalcare");
             if ($session['user']['superuser']>1) {
                output("`@`n`nDebug: Ora hai ".$session['user']['cavalcare_drago']." punti cavalcare");
             }
         } else {
             output("`2Stai per pagare l'`^Elfo`2 ma ti accorgi di non possedere `65000`2 monete d'`6Oro`2...Ti scusi con lui per la tua sbadataggine e ti congedi tornando nella foresta.");
         }
         break;
}


?>