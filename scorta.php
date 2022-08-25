<?php
require_once "common.php";

$operazione = $_GET[op];
switch ($operazione) {
        case "":
             $caso = e_rand(0,10);
             switch ($caso) {
                     case 0:
                          page_header("L'Ira Divina!");
                          output("`5Ti stai dirigendo verso la prigione dove è tenuto il `\$Negromante `5con il ghigno
                                   sulle labbra pregustando i maltrattamenti da infliggergli. Quando arrivi alla soglia,
                                   prendi le chiavi e apri la cella ma improvvisamente senti una forza misteriosa
                                   impossessarti del tuo corpo. E' la divinità protettrice del `\$Negromante !!");
                          output("`%`nPurtroppo il tuo corpo stà avvizzendo..Le tue forze se ne vanno e di te rimangono
                                   solo i vestiti per terra.");
                          output("`3`n`nPerdi tutto il tuo oro e le tue gemme.");
                          debuglog("muore colpito da avvizzimento e perde {$session[user][gold]} oro e {$session[user][gems]} gemme vicino alla progione");
                          addnav("Terra delle Ombre","village.php");
                          $session[user][alive]=false;
                          $session[user][hitpoints]=0;
                          $session[user][gems]=0;
                          $session[user][gold]=0;
                          break;
                     case 1:
                     case 2:
                     case 3:
                          page_header("Il Negromante");
                          output("`5Arrivi alla cella del `\$Negromante `5che ti scruta con i suoi occhi infossati mentre apri la porta.
                                   Hai la sensazione che ti voglia parlare ma allo stesso tempo hai paura dei suoi oscuri poteri.");
                          output("`5`n`nCosa fai.");
                          addnav("Parla al Negromante","scorta.php?op=parla");
                          addnav("Attacca il Negromante","negromante.php");
                          addnav("Scorta il Negromante","scorta.php?op=scorta");
                          addnav("Scappa","scorta.php?op=scappa");
                          break;
                     case 4:
                     case 5:
                     case 6:
                     case 7:
                          page_header("La Cella");
                          output("`5Arrivi alla cella del `\$Negromante `5 e gli leghi le mani ben strette con una corda.
                                   Lui cerca di divincolarsi mentre impreca e ti maledice ma alla fine riesci ad ammanettarlo.");
                          output("`nState per avviarvi quando senti una voce nella tua testa che ti parla. E' il `\$Negromante !!");
                          output("`5`n`nCosa fai.");
                          addnav("Lo Ascolti","scorta.php?op=parla");
                          addnav("Lo Attacchi","negromante.php");
                          addnav("Lo Ignori","scorta.php?op=scorta");
                          addnav("Scappi","scorta.php?op=scappa");
                          break;
                     case 8:
                          page_header("La Cella Vuota");
                          $expe=intval($session[user][experience]*0.1);
                          output("`5Arrivi alla cella del `\$Negromante `5 ma trovi la porta aperta e nessun segno del prigioniero.
                                   Preoccupato della sicura punizione che lo sceriffo ti infliggerà, cominci a cercare il
                                   fuggitivo dappertutto ma alla fine della giornata ancora non l'hai trovato.");
                          output("`5`n`nLa tua credibilità nel villaggio è calata !!`n`3Perdi $expe punti esperienza !!");
                          $session[user][experience] -=$expe;
                          debuglog("perde il 10% di esperienza per non aver trovato il Negromante fuggito");
                          $session['user']['quest'] += 1;
                          addnav("Torna al Villaggio","village.php");
                          break;
                     case 9:
                          page_header("L'imboscata");
                          $exp=intval($session[user][experience]*0.15);
                          output("`5Ti incammini verso la cella che ti ha indicato lo sceriffo ma non fai in tempo ad accorgerti che
                                   il `\$Negromante `5è evaso ed ha evocato una moltitudine di non-morti.`n");
                          output("`5I suoi oscuri servitori ti circondano subito e non fai in tempo a difenderti.");
                          output("`5`n`nSei morto.`n");
                          output("Almeno hai imparato qualche cosa riguardo ai non morti e ai suoi evocatori
                                  guadagnando $exp punti esperienza ma perdi tutto l'oro che avevi con te.`n`n");
                          output("`3Potrai continuare a giocare domani");
                          debuglog("è morto cadendo in un imboscata del Negromante e ha perso {$session[user][gold]} oro");
                          $session[user][experience] +=$exp;
                          $session[user][alive]=false;
                          $session[user][hitpoints]=0;
                          $session[user][gold]=0;
                          addnav("Terra delle Ombre","village.php");
                          break;
                     case 10:
                          page_header("L'imprevisto");
                          output("`5Sei quasi arrivato alla prigione quando vedi `6".($session['user']['sex']?"Seth":"Violet")."
                                   `5che ti chiama. Non sapendo resistergli ti dimentichi del tuo impegno e alla fine rimedi
                                   solo un due di picche e una bella sgridata dallo sceriffo!!");
                          addnav("Torna al Villaggio","village.php");
                          break;
             }
             break;
             // end case ""
       case "scappa":
             page_header("Scappi come una Bimba Impaurita.");
             output("Ti volti e inizi a correre il più lontano possibile.`n");
             output("Hai solamente sprecato del tempo.");
             addnav("Torna al Villaggio","village.php");
             $session['user']['quest'] += 1;
             break;
             // end case "scappa"
       case "parla":
             page_header("Il discorso");
             if ($session[user][darkarts]>=1) {
                 output("`4Sento che i tuoi poteri sono simili ai miei, perchè non mi lasci libero? Ti ricompenserò bene !");
                 output("`#`n`nCosa fai.");
                 addnav("Lo liberi","scorta.php?op=libera");
                 addnav("Lo ignori","scorta.php?op=scorta");
             }else{
                 output("`4Povero ".($session['user']['name'])." lasciami libero se tieni alla vita`n`n");
                 $casotouch = e_rand(0,5);
                 switch ($casotouch) {
                         case 0:
                              output("`5Cerca di toccarti e ti riesce a prendere !!`n");
                              output("`5La tua pelle comincia a cadere e nella tua mente affiorano i tormenti più tremendi.");
                              output("`3`n`nSei Morto. Potrai continuare a giocare domani");
                              addnav("Terra delle Ombre","village.php");
                              debuglog("è morto toccato dal Negromante e ha perso {$session[user][gold]} oro");
                              $session[user][alive]=false;
                              $session[user][hitpoints]=0;
                              $session[user][gold]=0;
                              break;
                         case 1:
                         case 2:
                         case 3:
                         case 4:
                              output("`5Cerca di toccarti ma non riesce colpirti. Hai davvero rischiato grosso !!");
                              output("`5`n`nCosa fai.");
                              addnav("Lo Attacchi","negromante.php");
                              addnav("Lo Scorti","scorta.php?op=scorta");
                              addnav("Scappi","scorta.php?op=scappa");
                              break;
                         case 5:
                              $hplose = intval($session['user']['maxhitpoints']*0.02);
                              if ($hplose==0) $hplose=1;
                              output("`5Cerca di toccarti e ti riesce a sfiorare !!");
                              output("`5La pelle colpita si comincia a putrificare.`n");
                              output("`n`n`3Perdi $hplose Punti Ferita Permanentemente !!");
                              output("`5`n`nCosa fai.");
                              addnav("Lo Attacchi","negromante.php");
                              addnav("Lo Scorti","scorta.php?op=scorta");
                              addnav("Scappi","scorta.php?op=scappa");
                              $session['user']['maxhitpoints'] -= $hplose;
                              $session['user']['hitpoints'] -= $hplose;
                              if ($session['user']['hitpoints'] < 1)  {
                                  $session['user']['hitpoints'] = 1;
                              }
                              debuglog("perde $hplose HP colpito dal Negromante");
                              break;
                 }
             }
             break;
             // end case "parla"
       case "libera":
             page_header("Evasione!");
             $session[user][darkarts]+=2;
             $session['user']['quest'] += 2;
             output("`5Ammaliato dalle conoscenze che il `\$Negromante `5può trasmetterti, lo liberi. Lui in cambio ti
                      insegna alcune cose sulla magia oscura.");
             // Aggiungere script per i ricercati
             $pcatt=e_rand(100,200);
             output("`n`n`3Ora sei ricercato per crimini contro la comunità !!`n`n`5Ma in fondo non sei mai stato un bravo ragazzo.");
             output("Lo sceriffo ha messo una taglia sulla tua testa di 5.000 pezzi d'oro e i tuoi Punti Cattiveria sono cresciuti ");
             output("di molto. Guardati le spalle d'ora in avanti, Dag Durnick ti ha inserito nel suo libretto !!!`n`n");
             $session[user][evil]+=$pcatt;
             $session[user][bounty]+=5000;
             $chance=e_rand(1,10);
             if ($chance==5){
                output("Mentre ti stai avviando verso il villaggio, senti un rumore alle tue spalle. Stai per girarti ma ");
                output("senti un dolore lancinante al braccio ! Qualcuno te lo sta torcendo e prima che tu possa renderti ");
                output("conto di chi sia ti ritrovi ammanettato. Probabilmente lo sceriffo ti stava tenendo d'occhio, e ");
                output("adesso ti ritrovi in prigione a scontare il fio delle tue azioni malvagie. `n`n");
                $session[user][jail]=1; 
                $name=$session[user][name]; 
                addnews("`!$name `!è stato catturato dallo Sceriffo per aver liberato il `\$Negromante`!!"); 
                addnav("Continua","constable.php?op=twiddle"); 
                }else {
                    addnav("Torna al Villaggio","village.php");
                }
             break;
             // end case "libera"
       case "scorta":
             page_header("Scorta");
             $casoscorta = e_rand(0,5);
             output("`5Finalmente riesci a imboccare il sentiero nella foresta diretto al bivio dove ti aspetta lo sceriffo");
             switch ($casoscorta) {
                     case 0:
                          output(".`n");
                          output("`5Cammini con passi sicuri dietro il `\$Negromante `5che ogni tanto strattoni perchè rallenta
                                   l'andatura.`n");
                          output("`5L'aria man mano che camminate si fa sempre più pesante e il sole sembra quasi si stia oscurando.
                                   Capisci che c'è qualcosa che non va e ne sei preoccupato.`n");
                          output("`5Cerchi una appiglio su cui legare la corda del prigioniero per avere le mani libere
                                   ma non fai in tempo a girare la testa che un ombra cupa cala sopra la tua testa.`n");
                          output("`5Sembra che oggi la fortuna non sia dalla tua parte.`n");
                          output("`5La mano gelata della morte ti sfiora e la tua anima si stacca dal corpo.`n");
                          output("`3`n`nSei Morto. Puoi continuare a giocare domani");
                          addnav("Terra delle Ombre","village.php");
                          debuglog("è morto sfiorato dalla Morte, perdendo {$session[user][gold]} oro");
                          $session[user][alive]=false;
                          $session[user][hitpoints]=0;
                          $session[user][gold]=0;
                          break;
                     case 1:
                     case 2:
                          output(" quando i tuoi sensi acuti ti avvertono di un pericolo imminente.`n");
                          output("`5Preoccupato sfoderi `6".($session['user']['weapon'])." `5e ti prepari alla battaglia ma le tue
                                   angoscie si dissolvono quando vedi che il vicesceriffo sbuca da dietro un albero e con un ");
                          output("`5cenno ti saluta.`n`n");
                          output("`4Salve ".($session['user']['name'])." ti stavo aspettando !!`n`n");
                          output("`5Con le tue abilità noti però che c'è qualcosa che non và.`n");
                          output("`5Improvvisamente infatti il vicesceriffo si avvicina verso di te con un ghigno sospetto
                                   e ad ogni passo sembra che si stia trasformando in qualcosa, o meglio, in qualcuno !!`n`n");
                          output("`5Inorridito dalla visione, ora hai davanti te stesso !!`n");
                          addnav("Attacca!","doplanger.php");
                          addnav("Scappa!","scorta.php?op=scappa");
                          break;
                     case 3:
                     case 4:
                          output(".`n");
                          output("`5Dopo una decina di minuti arrivi ad una radura e noti poco più avanti un piccolo bagliore
                                   vicino a degli alberi.`n");
                          output("`5La curiosità è sempre stata una tua prerogativa e decidi di andare a vedere cos'è quel luccichio,
                                   leghi il prigioniero ben stretto ad un albero e ti avvicini.`n`n`n");
                          $casogold = e_rand(0,5);
                          switch ($casogold) {
                                  case 0:
                                  case 1:
                                       output("`6La fortuna ti sorride !! Trovi una pentola piena d'oro !!`n");
                                       output("`6Forse uno Gnomo l'ha dimenticata quì.`n");
                                       output("`6L'ammontare del patrimonio è di 1000 pezzi oro !!`n");
                                       $session[user][gold] += 1000;
                                       $casogem = e_rand(0,4);
                                       if ($casogem > 2) {
                                           output("`6Trovi anche 1 Gemma !!`n");
                                           $session[user][gems] += 1;
                                           debuglog("trova 1000 oro e 1 gemma in una pentola");
                                       } else {
                                           debuglog("trova 1000 oro in una pentola");
                                       }
                                       break;
                                  case 2:
                                  case 3:
                                       output("`6La fortuna ti sorride !! Trovi una pentola stracolma di ricchezze !!`n");
                                       output("`6Forse uno Gnomo l'ha dimenticata quì.`n");
                                       output("`6L'ammontare del patrimonio è di 10000 pezzi oro !!`n");
                                       $session[user][gold] += 10000;
                                       $casogem = e_rand(0,4);
                                       if ($casogem > 2) {
                                           output("`6Trovi anche 3 Gemme !!`n");
                                           $session[user][gems] += 3;
                                           debuglog("trova 10000 oro e 3 gemme in una pentola");
                                       } else {
                                           debuglog("trova 10000 oro in una pentola");
                                       }
                                       break;
                                  case 4:
                                  case 5:
                                       output("`2Una volta sul posto vedi che si tratta solo di un riflesso.`n");
                                       break;
                          }
                          // end switch $casogold
                          addnav("Prosegui","scorta.php?op=prologo");
                          break;
                     case 5:
                          output("`.`n");
                          output("`5Arrivi incolume all'appuntamento con l'altra scorta e consegni il `\$Negromante.`n");
                          output("`5Il vicesceriffo con un cenno ti ringrazia e ti consegna un borsellino.`n");
                          output("`5Quando lo apri trovi 1000 monete e 2 gemme !!`n");
                          output("`5Felice di aver aiutato la legge te ne ritorni al Villaggio.");
                          debuglog("riceve 1000 oro e 2 gemme dal vicesceriffo per la scorta");
                          $session['user']['quest'] += 2;
                          $session['user']['gems'] += 2;
                          $session['user']['gold'] += 1000;
                          addnav("Torna al Villaggio","village.php");
                          break;
             }
             break;
             // end case "scorta"
       case "prologo":
             page_header("Prologo");
             output("`5`nTorni dal prigioniero e trovi solo la fune. E' riuscito a scappare !!`n");
             $casofuga = e_rand(0,4);
             switch ($casofuga) {
                     case 0:
                          output("`5Spieghi allo sceriffo che non è stata colpa tua inventandoti una scusa ma lui non ti crede.`n");
                          output("`5Per oggi hai finito di combinar danni.`n");
                          addnav("Torna al Villaggio","village.php");
                          $session['user']['quest'] += 2;
                          $session['user']['turns'] = 0;
                          break;
                     case 1:
                     case 2:
                          $exp=intval($session[user][experience]*0.15);
                          output("`3La tracce sono fresche e riesci a seguirle. Lo raggiungi facilmente e lo catturi.`n");
                          output("`3Poco più tardi lo consegni allo sceriffo che ti ringrazia.`n");
                          output("`3Guadagni $exp Esperienza.`n");
                          addnav("Torna al Villaggio","village.php");
                          $session['user']['quest'] += 2;
                          $session[user][experience] +=$exp;
                          debuglog("riceve $exp esperienza per aver consegnato il Negromante allo sceriffo");
                          break;
                     case 3:
                     case 4:
                          output("`3La tracce sono fresche e riesci a seguirle. Lo raggiungi facilmente e lo catturi.`n");
                          output("`3Poco più tardi lo consegni allo sceriffo che ti ringrazia regalandoti 500 oro.`n");
                          addnav("`@Torna al Villaggio","village.php");
                          debuglog("riceve 500 oro dallo sceriffo per la scorta");
                          $session['user']['quest'] += 2;
                          $session['user']['gold'] += 500;
                          break;
             }
             break;
             // end case "prologo"
}

page_footer();
?>