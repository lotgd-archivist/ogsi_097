<?php
require_once "common.php";

page_header("Lo Sceriffo");
$session['user']['locazione'] = 169;
output("`c`b`&Lo Sceriffo`0`b`c`n`n");
if ($_GET['op'] == ""){
   output("`3Dopo aver fatto un baccano infernale riesci finalmente ad attirare l'attenzione dello Sceriffo ");
   output("che lentamente si alza dalla sua scrivania e ti si avvicina con aria scocciata. Tenendo la mano sull'elsa ");
   output("della Spada Corta al suo fianco ti squadra attentamente e con fare svogliato dice: `n\"`#Cosa posso fare ");
   output("per un delinquente da strapazzo come te rinchiuso senza alcuna possibilità di fuga in quella topaia che è la tua cella ?`3\"`n Dopodichè un sorriso ");
   output("sardonico si dipinge sulle sue labbra.`n");
   addnav("Corrompi lo Sceriffo","scerifchance.php?op=corrompi");
   addnav("Sorprendi lo Sceriffo","scerifchance.php?op=sorprendi");
   addnav("Afferra la Spada Corta","scerifchance.php?op=spada");
   addnav("Lascia Perdere","constable.php?op=twiddle");
}else if ($_GET['op'] == "corrompi"){
   page_header("Corruzione");
   if ($session['user']['gems'] != 0){
      if ($_POST['gemme']==""){
         output("`2Decidi di tentare di giocarti la carta della corruzione, ma non avendo neanche un pezzo d'oro decidi di offrire ");
         output("qualcuna delle tue gemme. Quante vuoi provare ad offrirne ?`n");
         output("<form action='scerifchance.php?op=corrompi' method='POST'><input name='gemme' value='0'><input type='submit' class='button' value='Dai'>`n",true);
         addnav("","scerifchance.php?op=corrompi");
      }else{
         $gemme = abs((int)$_POST['gemme']);
         if ($gemme > $session['user']['gems']) {
            output("`%Non fare il furbo, non hai tutte quelle gemme !!!`n");
            addnav("Gira i Pollici","constable.php?op=twiddle");
         }else{
            if ($gemme == 0) {
               output("`3Lo Sceriffo prende le tue gemme immaginarie, con uno sguardo cattivo e un sorrisetto sadico apre la porta della tua cella, entra e con un bastone inizia a ");
               output("picchiarti selvaggiamente. Non si ferma fino a quando non ti vede per terra quasi moribondo, e richiudendo la cella ti ");
               output("dice con voce suadente`n\"`#forse la prossima volta ci penserai 2 volte prima di tentare di prenderti gioco di me... Buona dormita ora !`3\"`n`n");
               output("`\$Sei ridotto in fin di vita!!`n`^Hai perso il 10% della tua esperienza!!`n");
               debuglog("perde 10% exp nel tentativo di corrompere lo sceriffo. Evil = ".$session['user']['evil']);
               $session['user']['hitpoints'] = 1;
               $session['user']['experience'] = intval($session['user']['experience'] * 0.9);
               addnav("Gira i Pollici","constable.php?op=twiddle");
            }else if ($gemme >= ($session['user']['gems']/2) OR $gemme > 3){
               output("`6Gli occhi dello Sceriffo brillano avidamente alla vista del luccichio nella tua mano, riesci ");
               output("a leggervi chiaramente la bramosia che lo sta animando, e dopo qualche istante vedi comparire quasi magicamente la chiave della ");
               output("tua cella nella sua mano mentre le tue gemme cambiano rapidamente proprietario. `n\"`#Io non ho visto nulla, ma sappi ");
               output("che se ti dovessi reincontrare in qualcuno dei miei giri di perlustrazione sarò implacabile e non ti farò alcuno sconto di pena.`6\"`n");
               output("Detto ciò apre la cella e si gira dalla parte opposta, lasciandoti libero di fuggire.`n Non ti ");
               output("perdi in convenevoli e prendi più veloce che puoi la strada verso la libertà.`n");
               debuglog("Paga $gemme gemme per corrompere lo sceriffo e riesce a fuggire. Evil = ".$session['user']['evil']);
               addnews("`%".$session['user']['name']." `@è fuggito di prigione corrompendo lo sceriffo !!");
               $session['user']['gems'] -= $gemme;
               $session['user']['jail'] = 0;
               $session['return'] = "";
               addnav("Fuggi !!","village.php");
            }else{
               output("`3Lo Sceriffo afferra le tue gemme e soppesandole dice:`n\"`#Non penserai che ti lasci fuggire per ");
               output("questa miseria, vero? Non riuscirei a pagarci neanche una notte con Violet!`3\"`n E detto ciò ");
               output("torna alla sua scrivania pur intascandosi le tue preziose gemme. Col tuo misero tentativo hai ottenuto solo il risultato di perdere inutilmente $gemme gemme!!!`n");
               debuglog("Perde $gemme gemme nel tentativo di corrompere lo sceriffo. Evil = ".$session['user']['evil']);
               $session['user']['gems'] -= $gemme;
               addnav("Gira i Pollici","constable.php?op=twiddle");
            }
         }
      }
   }else {
      output("`3Sfortunatamente non hai in tasca niente di valore o di interessante da offrire allo sceriffo, e rassegnato ti sdrai sulla brandina ");
      output("della cella a meditare sul fatto che il crimine non paga.`n");
      addnav("Gira i Pollici","constable.php?op=twiddle");
   }
}else if ($_GET['op'] == "sorprendi"){
   page_header("Sorprendi lo Sceriffo");
   output("`5Mentre lo Sceriffo ti sbeffeggia senti la rabbia salire dentro di te, ed un piano di fuga improvviso ti si presenta ");
   output("davanti agli occhi:`n immobilizzare il malvagio carceriere, impossessarti del suo mazzo di chiavi, aprire la tua cella e fuggire verso l'agognata ");
   output("libertà !!`nDecidi immediatamente di tentare la sorte e ti avvicini alle sbarre con l'aria più innocente che ");
   output("sei in grado di fare, parlando della tua presunta innocenza per distrarre l'odioso personaggio. `nQuando si avvicina alle ");
   output("sbarre quel tanto che basta, con un guizzo fulmineo allunghi entrambe le braccia ");
   switch (e_rand(1,5)){
      case 1:
         output("`5e lo afferri alla gola in una morsa d'acciaio. Colto alla sprovvista lo Sceriffo ");
         output("non è in grado di reagire e ben presto cade a terra incosciente. Frughi tra i suoi vestiti e afferri le chiavi della cella, apri le sbarre ");
         output("e ti avvii verso la libertà!`n");
         switch (e_rand(0,1)){
            case 0:
               output("`n`%Sai che alle autorità non piacerà la tua azione, e che sicuramente verrà aumentata la taglia sulla tua ");
               output("testa, come sei anche perfettamente cosciente che il tuo livello di cattiveria ha subito un ulteriore incremento a causa della ");
               output("tua azione malvagia!`n");
               $taglia = 1000 * e_rand(1,3);
               $session['user']['bounty'] += $taglia;
               $session['user']['evil'] += 50;
               $session['user']['jail'] = 0;
               $session['return'] = "";
               addnav("Fuggi !!","village.php");
               debuglog("Immobilizza lo sceriffo e fugge dalla prigione. Aggiunta $taglia di taglia. Evil = ".$session['user']['evil']);
               addnews("`#".$session['user']['name']." `2è riuscito a fuggire dalla prigione dopo aver reso incosciente lo sceriffo !!");
            break;
            case 1:
               output("`n`5Nell'istante in cui stai per allontanarti dalla prigione, senti qualcosa di freddo e rigido sulla ");
               output("schiena, e mentre ti giri per scoprire cosa sia vedi il ghigno del Vice ");
               output("Sceriffo che ti sta premendo la punta di un pugnale all'altezza delle reni.`n\"`^Pensavi forse di farla franca, delinquente? Sono ");
               output("certo che qualche giorno supplementare di prigione ti farà riflettere sulle tue azioni!`5\"`n`n");
               output("Detto ciò ti risbatte a pedate in cella e si ferma a soccorrere il suo superiroe.`n");
               $session['user']['jail'] = 2;
               $session['user']['evil'] += 41;
               addnav("Gira i Pollici","constable.php?op=twiddle");
               debuglog("Immobilizza lo sceriffo e prova a fuggire dalla prigione, ma il vicesceriffo glielo impedisce. Viene ulteriormente punito. Evil = ".$session['user']['evil']);
            break;
         }
      break;
      case 2:
         output("`5solo per andare a sbattere il muso contro le sbarre! Lo Sceriffo si aspettava la tua mossa ");
         output("e con un tempismo perfetto si è sottratto al tuo tentativo di immobilizzarlo. `nIl sorriso sardonico ");
         output("si è trasformato in un ghigno e quella che senti risuonare nella tua testa è la sua risata ... `n");
         output("`6AH AH  <big>AH AH  `^AH AH  <big>AH AH  <big>AH AH  <big>`\$AH AH  <big>AH AH AH AH</big></big></big></big>",true);
         addnav("Gira i Pollici","constable.php?op=twiddle");
         debuglog("Prova ad immobilizzare lo sceriffo ma fallisce. Evil = ".$session['user']['evil']);
         $session['user']['jail'] = 2;
      break;
      case 3: case 5:
         output("`5solo per afferrare l'aria!! Lo Sceriffo, al di fuori della tua portata, se la ride di gusto sbeffeggiandoti ");
         output("per il tuo ridicolo tentativo. Per oggi non avrai sicuramente altre opportunità di sorprenderlo.`nTi sdrai ");
         output("sulla brandina della tua cella rimuginando su ciò che hai fatto nel tentativo di capire dove hai sbagliato.`n");
         addnav("Gira i Pollici","constable.php?op=twiddle");
         debuglog("Prova ad immobilizzare lo sceriffo ma fallisce. Evil = ".$session['user']['evil']);
         $session['user']['jail'] = 2;
      break;
      case 4:
         output("`5e riesci ad afferrare il braccio destro dello Sceriffo.`nLo tiri verso le sbarre cercando di immobilizzarlo ");
         output("ma per tua sfortuna l'odioso guardiano è mancino, ed estraendo fulmineo la sua spada corta ti colpisce con l'elsa della stessa ");
         output("alla tempia. Perdi conoscenza e rinvieni quando una secchiata di acqua gelata ti colpisce. Lo Sceriffo con ");
         output("un secchio in mano ti osserva indispettito e ti dice`n\"`#Sono certo che un giorno di galera supplementare ");
         output("ti farà bene`5\"`nDopodichè si volta e torna ai suoi compiti di routine seduto dietro la scrivania.`n`n");
         output("Scopri inoltre che in seguito al colpo ricevuto hai perso il 5% della tua esperienza!!`n");
         addnav("Gira i Pollici","constable.php?op=twiddle");
         debuglog("perde il 5% exp + 21 PC tentando di fuggire dalla prigione. Evil = ".$session['user']['evil']);
         $session['user']['jail'] = 2;
         $session['user']['experience'] = intval($session['user']['experience'] * 0.9);
         $session['user']['evil'] += 21;
         debuglog("Prova ad immobilizzare lo sceriffo ma fallisce, e lo sceriffo lo punisce ulteriormente. Evil = ".$session['user']['evil']);
      break;
   }
}else if ($_GET['op'] == "spada"){
   page_header("Afferra la Spada Corta");
   output("`5Mentre lo Sceriffo ti sbeffeggia senti la rabbia salire dentro di te, ed un piano di fuga improvviso ti ");
   output("si presenta davanti agli occhi: impossessarti della Spada Corta dell'odiato tormentatore, minacciarlo per farti aprire ");
   output("la cella e fuggire verso la libertà !!`nDecidi di tentare la sorte e ti avvicini alle sbarre con l'aria ");
   output("più innocente che sei in grado di fare, parlando della tua presunta innocenza per distrarre il tuo carceriere. Quando si ");
   output("avvicina alle sbarre quel tanto che basta con un guizzo fulmineo allunghi la mano ");
   switch (e_rand(1,2)){
      case 1:
         output("`5e afferri l'elsa della corta spada. La estrai dal fodero e la punti alla gola dello Sceriffo che ti guarda basito. ");
         output("Gli ordini di aprire la cella, ");
         switch (e_rand(1,2)){
            case 1:
               output("`5cosa che il tuo ostaggio si appresta a fare immediatamente, terrorizzato dall'arma che gli stai ");
               output("puntando addosso. Aperta la porta stordisci lo Sceriffo colpendolo alla testa. ");
               output("Veloce ti allontani dalla prigione e ti dirigi alla piazza mescolandoti con gli altri ");
               output("cittadini.`nHai riguadagnato la libertà ma sicuramente il tuo livello di cattiveria ha subito un notevole incremento ");
               output("e sei sicuro che le autorità avranno anche aumentato la taglia sulla tua testa.`n");
               if (e_rand(0,1) == 0){
                  $sql = "SELECT name,acctid,goldinbank,sex FROM accounts WHERE loggedin = 0 AND alive = 1 AND superuser = 0 ORDER BY RAND() LIMIT 1";
                  $result = db_query($sql) or die(db_error(LINK));
                  $row = db_fetch_assoc($result);
                  $nome = $row['name'];
                  $acctid = $row['acctid'];
                  $orobanca = $row['goldinbank'];
                  $sesso = $row['sex'];
                  if ($orobanca > 0){
                     $ororubato = intval($orobanca / 2);
                     if ($ororubato > 1000) {$ororubato = 1000; }
                     output("`6Inoltre mentre esci incroci `#$nome`6 e l".($sex?"a":"o")." derubi di `&$ororubato pezzi d'oro`6!!`n");
                     $mailmsg = "`^Mentre passavi nei pressi della prigione sei stato derubat".($sex?"a":"o")." di $ororubato pezzi d'oro da ";
                     $mailmsg .= "`#".$session['user']['name']." `^mentre evadeva dalla prigione. Purtroppo per te ";
                     $mailmsg .= "eri nel posto sbagliato al momento sbagliato.";
                     $session['user']['gold'] += $ororubato;
                     $session['user']['evil'] += 30;
                     debuglog("deruba a $nome $ororubato pezzi d'oro mentre evade dalla prigione. Evil = ".$session['user']['evil']);
                     systemmail($acctid,"`!`bSei stato rapinato !!!`b",$mailmsg);
                     $sql = "UPDATE accounts SET goldinbank=goldinbank-$ororubato WHERE acctid = '$acctid'";
                     $result = db_query($sql) or die(db_error(LINK));
                     addnews("`#".$session['user']['name']." `2è riuscito a fuggire dalla prigione dopo aver reso incosciente lo Sceriffo !!`n
                     `3Inoltre ha derubato `%$nome`3 che passeggiava tranquillamente da quelle parti !!");
                  }else{
                     addnews("`#".$session['user']['name']." `2è riuscito a fuggire dalla prigione dopo aver reso incosciente lo Sceriffo !!");
                  }
               }
               $taglia = 1000 * e_rand(1,3);
               $session['user']['bounty'] += $taglia;
               $session['user']['evil'] += 50;
               $session['user']['jail'] = 0;
               $session['return'] = "";
               addnav("Fuggi !!","village.php");
               debuglog("Immobilizza lo sceriffo e fugge dalla prigione. Aggiunti $taglia oro di taglia. Evil = ".$session['user']['evil']);
            break;
            case 2:
               output("`5ma quella mammoletta, terrorizzato dall'arma che gli punti contro ... sviene!!!`n Decidi ");
               output("quindi di forzare la serratura utilizzando la punta della spada. ");
               switch (e_rand(1,2)){
                  case 1:
                     output("`5Purtroppo la tua abilità di scassinatore lascia alquanto a desiderare e dopo fatto una serie di inutili tentativi, la ");
                     output("serratura è ancora chiusa.`nRassegnato abbandoni la spada oramai inutile visto che la lama si è spezzata e torni a ");
                     output("sdraiarti sulla brandina rimuginando e imprecando contro la sfortuna che si accanisce contro di te.`n");
                     addnav("Gira i Pollici","constable.php?op=twiddle");
                     $session['user']['jail'] = 2;
                     debuglog("Prova ad evadere dalla prigione ma fallisce. Evil = ".$session['user']['evil']);
                  break;
                  case 2:
                     output("`5Click, la serratura scatta e si apre, puoi uscire verso la tanto agognata libertà. ");
                     output("Ma quando stai per avviarti alla porta d'ingresso della prigione, questa si spalanca di colpo e il vice sceriffo ti ");
                     output("blocca la strada puntandoti contro una nera balestra armata. `nNon ti rimane altro che ritornare mestamente nella tua cella ");
                     output("rassegnandoti a scontare la tua pena ... o forse no .... diciamo fino al ");
                     output("prossimo tentativo di fuga ......`n");
                     addnav("Gira i Pollici","constable.php?op=twiddle");
                     $session['user']['jail'] = 2;
                     debuglog("Prova ad evadere dalla prigione ma fallisce. Evil = ".$session['user']['evil']);
                  break;
               }
            break;
         }
      break;
      case 2:
         output("`5e afferri l'elsa della spada. Purtroppo non riesci a stringerla a sufficienza e mentre ritrai ");
         output("il braccio ti scivola di mano ");
         switch (e_rand(1,2)){
            case 1:
               output("`5infilzandoti il piede.ARRRGHHHHHH!! Lo Sceriffo recupera velocemente l'arma e manda a chiamare il cerusico ");
               output("per curarti.`n Il Druido guaritore Wylelm arriva dopo pochi minuti e si dedica alla tua ferita dimostrando notevole abilità curatrici, ");
               output("ma al termine del suo intervento ");
               if (($session['user']['maxhitpoints']/10) > $session['user']['level']){
                  output("ti comunica, con estremo rammarico, che hai perso `^`b1 HP `iPermanente`i`b``5!!`n");
                  output("`#La ferita ti ha causato anche la perdita del `b5%`b di esperienza!!`n");
                  debuglog("perde 1 HP permanente per una ferita al piede tentando di evadere. Evil = ".$session['user']['evil']);
                  $session['user']['maxhitpoints']--;
                  $session['user']['hitpoints']--;
               }else{
                  output("sei completamente guarito, anche se la ferita ti ha causato la perdita del `b5%`b di esperienza!!`n");
               }
               addnav("Gira i Pollici","constable.php?op=twiddle");
               $session['user']['jail'] = 2;
               $session['user']['experience'] = intval($session['user']['experience'] * 0.95);
            break;
            case 2:
               $sql = "SELECT name,acctid,sex FROM accounts WHERE loggedin = 0 AND superuser = 0 ORDER BY RAND() LIMIT 1";
               $result = db_query($sql) or die(db_error(LINK));
               $row = db_fetch_assoc($result);
               $nome = $row['name'];
               $acctid = $row['acctid'];
               $sesso = $row['sex'];
               /*$nome = "`\$Admin Excalibur";
               $acctid = 3;
               $sesso = 0; */
               output("`5infilzando il piede dello Sceriffo il quale inizia a ballonzonare per la stanza saltando su un piede solo in maniera scomposta. ARRGGGGHHH!! `n");
               output("`5Così facendo va a urtare una balestra appesa al muro tenuta carica per scoraggiare i tentativi di evasione, azionandola. Il mortale dardo colpisce, fortunatamente non in modo grave, l'innocente `@$nome `5che nel frattempo era entrato nella prigione per visitare un prigioniero.`n");
               if ($session['user']['gems'] > 0){
                  output("`%Lo Sceriffo ti confisca `^1 gemma `%per darla come indennizzo al".($sex?"la povera malcapitata":" povero malcapitato")." `@$nome`%.");
                  output("`nIl fallito tentativo di fuga appena compiuto aumenta inoltre la tua malvagità, quindi dovrai scontare una giornata supplementare ");
                  output("in gabbia, in compagnia dei topi e delle cimici che infestano la tua cella!!`n");
                  $session['user']['gems'] -= 1;
                  $sql = "UPDATE accounts SET gems=gems+1 WHERE acctid = '$acctid'";
                  $result = db_query($sql) or die(db_error(LINK));
                  $mailmsg = "`%Mentre passavi nei pressi della prigione sei stato ferito di striscio da un dardo vagante partito accidentalmente dalla balestra ";
                  $mailmsg .= "dello Sceriffo, mentre `#".$session['user']['name']." `%tentava di fuggire. Come indennizzo le Autorità ti hanno dato ";
                  $mailmsg .= "una gemma del condannato.`n Speriamo tu possa accettarla e dimenticare rapidamente l'accaduto.";
               }else{
                  output("`5Lo Sceriffo ti confischerebbe 1 gemma se tu l'avessi per darla come indennizzo al".($sex?"`\$ povero":"`6la povera")." $nome.");
                  output("`nGli farà recapitare per l'inconveniente 2.000 pezzi d'oro e si augura che non il malcapitato non ti porti rancore per l'accaduto.");
                  output("`nIl tuo tentativo di fuga appena fallito aumenta inoltre la tua malvagità, quindi dovrai scontare una giornata supplementare ");
                  output("tra le sbarre assieme alle zecche e alle pulci che infestano la tua branda!!`n");
                  $sql = "UPDATE accounts SET goldinbank=goldinbank+2000 WHERE acctid = '$acctid'";
                  $result = db_query($sql) or die(db_error(LINK));
                  $mailmsg = "`%Mentre passavi nei pressi della prigione sei stato colpito di striscio da un dardo vagante partito accidentalmente dalla balestra
                  dello Sceriffo, mentre `#".$session['user']['name']." `%tentava di fuggire. Come indennizzo le Autorità hanno depositato
                  sul tuo conto 2.000 pezzi d'oro.`n Speriamo tu possa accettarla e dimenticare rapidamente l'accaduto.";
               }
               $session['user']['jail'] = 2;
               addnav("Gira i Pollici","constable.php?op=twiddle");
               $session['user']['evil'] += 21;
               systemmail($acctid,"`!`bSei stato ferito !!!`b",$mailmsg);
               debuglog("nel tentativo di evadere fa partire un dardo dalla balestra che colpisce ".$row['name'].". Evil = ".$session['user']['evil']);
            break;
         }
      break;
   }
}

page_footer();
?>

