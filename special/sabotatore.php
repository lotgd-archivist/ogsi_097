<?php
//echo "Backup :".$session['user']['buffbackup']."<br>Originale: ";
//print_r ($session['bufflist']);
//echo"<br>";
/* *******************************************************
IL SABOTATORE (sabotatore.php)
versione:      0.1

autore:        Excalibur

data:          10/08/2006

note:          Evento per consentire ai seguagi delle tre sette di ottenere punti carriere
               per la propria setta.

descrizione:    I seguaci di Sgrios-Karnak-Drago Verde devono prima battere il sabotatore,
                che possono affrontare solo dopo aver pagato X gemme (e/o XXX oro). Non potranno
                utilizzare attacchi speciali nel combattimento, e pagando una gemma supplementare
                posso ristorare i propri HP.
                Se riescono a batterlo, egli saboterà UNA delle sette avversarie (scelta random)
                facendogli perdere X punti ai fini dello scontro delle chiese. Esiste una minima
                possibilità che egli si rivolti contro chi lo ha sconfitto (1% ???) e saboti al
                contrario la sua setta.
                In caso di sconfitta invece sarà proprio il player sconfitto a perdere una percentuale
                dei propri punti carriera, oltre all'oro che ha in mano
********************************************************* */
if ($session['user']['sabota'] == 0) {
   //Il player non ha incontrato il sabotatore nella giornata
   $dio = $session['user']['dio'];
   $costo = 2;
   $costohp = 1;
   $costohporo = 500;
   $oro = $session['user']['gold'];
   $hp = $session['user']['hitpoints'];
   $maxhp = $session['user']['maxhitpoints'];
   $perdita = 200 + e_rand(100,300);
   //Setup del Sabotatore
   page_header("Il Sabotatore");
   output("`n`n`c`b`@Il Sabotatore`0`b`c`n`n");
   if ($dio != 0){
      //Caso player appartente ad una setta
      if ($_GET['op']== ""){
         $badguy = array(
           "creaturename"=>"`4Il Sabotatore",
           "creaturelevel"=>$session['user']['level']+e_rand(1,2),
           "creatureweapon"=>"`(Coltello da Rambo`0",
           "creatureattack"=>($session['user']['attack']*0.98),
           "creaturedefense"=>($session['user']['defence']*1.02),
           "creaturehealth"=>ceil($session['user']['maxhitpoints']*1.04),
           "diddamage"=>0,
           "pvp"=>1
         );
         $session['user']['badguy']=createstring($badguy);
         $session['user']['specialinc']="sabotatore.php";
         output("`3Nella tua ricerca di avventure nella foresta, ti imbatti in uno strano tipo che ti si para davanti.`n");
         output("È abbigliato in maniera decisamente inusuale, con una attillata tuta nera e armi e coltelli appese alla cintura!`n");
         output("Lo scruti con attenzione per capire se può essere pericoloso o meno, quando alzando una mano in segno di ");
         output("pace, si rivolge a te dicendoti:`n`n");
         output("\"`@Salve guerriero, non temere non ho intenzioni pericolose.`n");
         output("Al contrario posso offrirti i miei servigi per favorire la setta di ".$fedecasa[$dio]." `@alla quale appartieni.`n");
         output("Se riuscirai a battermi, e dopo aver pagato per provarci, io effettuerò un sabotaggio nei confronti di ");
         output("una delle sette avversarie, togliendo loro una certa quantità di punti carriera, cosa che avvantaggerà la tua ");
         output("setta di appartenenza.`nSe al contrario non riuscirai a battermi ... beh scoprirai cosa ti attende solo provandoci!!`3\"`n`n");
         output("`3Il Sabotatore attende una tua risposta, devi scegliere se vuoi affrontarlo pagando $costo gemme o rinunciare ");
         output(" a questa opportunità di favorire la tua setta.`n`nCosa fai?`n");
         //controllo se ha gemme il player
         if ($session['user']['gems'] >= $costo){
            addnav("Affrontalo","forest.php?op=prosegui");
         }else{
            addnav("Non hai gemme","forest.php?op=abbandona");
         }
         addnav("Ritirati","forest.php?op=abbandona");
      }elseif($_GET['op'] == "prosegui"){
         $session['user']['specialinc']="sabotatore.php";

         //il player accetta lo scontro
         $session['user']['gems'] -= $costo;
         debuglog(" paga $costo gemme per affrontare il Sabotatore");
         output("`3\"`@Bene, vedo che il fegato non ti manca!`nPrima mi sono dimenticato di avvisarti che in questo scontro ");
         output("non è consentito usare attacchi speciali.`n");

         //Tolgo gli eventuali buffs attivi ^__^
         $session['user']['buffbackup']=serialize($session['bufflist']);
         $session['bufflist']=array();

         //controllo su HP per eventuale ripristino al massimo
         if ($hp < $maxhp){
            if ($session['user']['gems'] > $costohp){
               output("Se ti serve posso fornirti una pozione per riportare al massimo i tuoi HP al modico costo ");
               output("di $costohp gemm".(($costohp==0)? "e" : "a").". Ne vuoi approfittare?`3\"`n");
               addnav("Ripristina HP massimi","forest.php?op=hpmax");
               addnav("No, sto bene così","forest.php?op=fight");
            }else{
              output("Peccato tu non sia al 100% della tua forma, ma forse questo renderà il nostro scontro più interessante!`3\"");
              $battle=true;
              addnav("Combatti!","forest.php?op=fight");
            }
         }else{
            output("Bene, tutto è pronto! Seguimi nella radura qui a fianco, dove potremo confrontarci senza problemi.`3\"`n`n");
            output("Detto ciò si avvia e tu lo segui.`nArrivati nello spazio senza alberi vi fronteggiate, e dopo esservi rivolti ");
            output("i rispettosi inchini di rito, date inizio allo scontro.`n");
            $battle=true;
            addnav("Combatti!","forest.php?op=fight");
         }
      }elseif($_GET['op'] == "hpmax"){
         $session['user']['specialinc']="sabotatore.php";
         //Il player paga per riportare al massimo gli HP
         $session['user']['gems'] -= $costohp;
         $session['user']['hitpoints'] = $session['user']['maxhitpoints'];
         output("`3Paghi $costohp gemm".(($costohp==0)? "e" : "a")." al Sabotatore, che ti allunga una pozione che ");
         debuglog("paga $costohp gemme al Sabotatore per riportare HP max");
         output("trangugi velocemente ... ha un gusto delicato di limonata e ti senti nuovamente nel pieno della tua ");
         output("forma fisica.`nOra sei pronto ad affrontare il duello!!`n`n");
         $battle=true;
         addnav("Combatti!","forest.php?op=fight");
      }elseif($_GET['op'] == "fight"){
         $battle=true;
         if ($battle){
            include_once("battle.php");
            $_GET['op']="fight";
            $session['user']['specialinc']="sabotatore.php";
            if($victory) {
               $flawless = 0;
               if ($badguy['diddamage'] != 1){
                  $flawless = 1;
                  output("`b`c`&~~ Combattimento Perfetto ~~`0`c`b`n`n");
               }
               output("`n`n`3Stai per affondare l'ultimo colpo sul sabotatore quando ti chiede di risparmiarlo e ti ");
               output("ricorda il compito che ha da svolgere. Fermi la tua furia ed egli estrae una pozione da una ");
               output("tasca e, visibilmente scosso per la sconfitta, si rimette in piedi e dice:`n`n");
               output("\"`@Bene, mi hai sconfitto ed onorerò l'impegno assunto. Sappi che c'è una piccola percentuale ");
               output("che il mio sabotaggio fallisca o peggio ancora si ritorca contro la tua stessa setta, ma ");
               output("lasciamo che a decidere quale setta saboterò sia la sorte.`3\"`n`nEstrae una moneta sulle cui ");
               output("facce compaiono ");
               if ($dio == 1){
                  $sabota = e_rand(2,3);
                  output("la sagoma di un `@Drago `3e l'immagine di `\$Karnak`3, ");
               }elseif ($dio == 2){
                  $sabota = e_rand(1,2);
                  if ($sabota == 2) $sabota = 3;
                     output("la sagoma di un `@Drago `3e l'immagine di `^Sgrios`3, ");
               }else{
                  $sabota = e_rand(1,2);
                  output("l'immagine di `^Sgrios`3 `3e l'immagine di `\$Karnak`3, ");
               }
               output("la lancia in aria ....");
               output("`n`c.....");
               output("`n....");
               output("`n...");
               output("`n..");
               output("`n.`c`n");
               output("La moneta cade a terra e mostra la setta scelta per il sabotaggio: ".$fedecasa[$sabota]."`n");
               $session['user']['sabota'] = 1;
               $caso = e_rand(1,100);
               //$caso = 100;
               output("`3Il sabotatore osserva la moneta e si allontana raccogliendo la sua attrezzatura.`nDopo qualche ");
               output("minuto torna e dice:`n`n\"`@Ho fatto quello che ti avevo promesso, ora possiamo solo aspettare e ");
               output("vedere se il sabotaggio è riuscito o no.`3\"`n`n");
               if ($caso < 97){
                  //sabotaggio riuscito
                  if ($sabota == 1){
                      savesetting("puntisgrios", getsetting("puntisgrios",0)-$perdita);
                  }elseif ($sabota == 2){
                      savesetting("puntikarnak", getsetting("puntikarnak",0)-$perdita);
                  }else {
                      savesetting("puntidrago", getsetting("puntidrago",0)-$perdita);
                  }
                  $session['user']['history'] = "/me`& ha sconfitto`( il Sabotatore`&, facendo perdere`\$ $perdita`& punti alla setta di ".$fedecasa[$sabota];
                  output("Dopo qualche istante osserva un piccolo marchingegno ed un largo sorriso si dipinge sul suo ");
                  output("volto.`n`n\"`@Il sabotaggio è riuscito perfettamente, la setta di ".$fedecasa[$sabota]." ha ");
                  debuglog("fa perdere $perdita PuntiCarriera a ".$fedecasa[$sabota]." con il Sabotatore");
                  addnews($session['user']['name'] . " `#ha sconfitto il `^Sabotatore`#,
                  che ha fatto perdere $perdita Punti Carriera alla setta di ".$fedecasa[$sabota]."!");
                  output("perso `b`&$perdita`@`b punti carriera!!!`3\"`n`nDetto ciò si allontana visibilmente soddisfatto del proprio ");
                  output("lavoro, e proprio quando sta per scomparire nel fitto della foresta si volta per un attimo e ti ");
                  output("rivolge un ultimo saluto");
                  if ($flawless == 1){
                     output(", ricordandoti di raccogliere l".(($costo==0)? "e" : "a")." gemm".(($costo==0)? "e" : "a"));
                     output("che avevi pagato per i suoi servigi, e che avendo fatto un combattimento perfetto meriti di non ");
                     output("pagare. Ora va alla tua setta per rendere partecipi i tuoi confratelli del tuo successo.`n");
                     $session['user']['gems'] += $costo;
                  }else{
                     output(". Ora va alla tua setta per rendere partecipi i tuoi confratelli del tuo successo.`n");
                  }
               }elseif ($caso < 100){
                  //sabotaggio fallito
                  $session['user']['sabota'] = 1;
                  $session['user']['history'] = "/me`& ha sconfitto`( il Sabotatore`&, ma purtroppo il sabotaggio è fallito.";
                  output("Dopo qualche istante osserva un piccolo marchingegno ed una smorfia si dipinge sul suo ");
                  output("volto.`n`n\"`@Come è potuto accadere?? Avevo pianificato tutto con cura, ma forse il fato ");
                  output("ha preferito che il mio operato non stravolgesse la competizione, lasciando che il risultato ");
                  output("finale sia deciso dal solo contributo dei fedeli.`nMi spiace ".$session['user']['name']."`@, ");
                  output("ma il mio sabotaggio non è andato a buon fine.`3\"`n`nDetto ciò si allontana con aria affranta, ");
                  output("e dopo qualche istante ti ritrovi solo nella foresta, con qualche gemma in meno, e decidi di ");
                  output("tornartene al villaggio ... forse una birra da Cedrik riuscirà a farti dimenticare la perdita");
                  debuglog("il Sabotatore ha fallito");
                  addnews($session['user']['name'] . " `2ha sconfitto il `^Sabotatore`2, ma il sabotaggio non è andato a buon fine!");
                  if ($flawless == 1){
                     output(", non prima di raccogliere l".(($costo==0)? "e" : "a")." gemm".(($costo==0)? "e" : "a"));
                     output("che avevi pagato per i suoi servigi, e che avendo fatto un combattimento perfetto meriti di non ");
                     output("pagare.`n");
                     $session['user']['gems'] += $costo;
                  }else{
                     output(".`n");
                  }
               }else{
                  //il sabotaggio si è ritorto contro la setta del player
                  if ($dio == 1){
                      savesetting("puntisgrios", getsetting("puntisgrios",0)-$perdita);
                  }elseif ($dio == 2){
                      savesetting("puntikarnak", getsetting("puntikarnak",0)-$perdita);
                  }else {
                      savesetting("puntidrago", getsetting("puntidrago",0)-$perdita);
                  }
                  $session['user']['sabota'] = 1;
                  $session['user']['history'] = "/me`& ha sconfitto`( il Sabotatore`&, ma purtroppo il sabotaggio si è
                  ritorto contro la vostra setta, causando una perdita di`\$ $perdita`& punti carriera !!!";
                  $session['user']['history'] = addslashes($session['user']['history']);
                  $sql = "INSERT INTO commenti (section,author,comment,postdate) VALUES ('Scontri Sette','".$session['user']['acctid']."','".$session['user']['history']."',NOW())";
                  db_query($sql) or die(db_error($link));
                  $session['user']['history'] = "";
                  output("Dopo qualche istante osserva un piccolo marchingegno ed una strana espressione si dipinge sul ");
                  output("suo viso. Intuisci che qualcosa è andato storto, ma quello che ti dice ti lascia di stucco.`n`n");
                  output("\"`@Sono costernato ".$session['user']['name']."`@, se avessi saputo che sarebbe stato questo ");
                  output("il risultato del mio sabotaggio non ti avrei offerti i miei servigi ... ti prego di credermi!!");
                  output("`3\"`nNotando lo sguardo interrogativo sul tuo volto prosegue:`n\"`@Purtroppo il fato, o ");
                  output("lo stesso ".$fedecasa[$dio]."`@, hanno voluto punire la tua arroganza, e hanno ritorto contro la ");
                  output("tua stessa setta il mio atto di sabotaggio.`3\"`n`nCapisci che qualcosa è andato storto, e le ");
                  output("parole del sabotatore suonano stonate nella tua mente, quando realizzi che i punti carriera ");
                  output("sono stati sottratti alla `b`&TUA`b`3 setta!!!`nI tuoi confratelli non saranno contenti del tuo ");
                  output("operato, e sarà meglio che trovi una buona scusa prima di tornare da loro.`n`n");
                  debuglog("fa perdere $perdita PuntiCarriera alla sua stessa setta (".$fedecasa[$dio].")");
                  addnews($session['user']['name'] . " `%ha sconfitto il `^Sabotatore`%, ma il sabotaggio si è ritorto contro
                  la sua stessa setta, ".$fedecasa[$dio].", `%che ha perso `#$perdita `%Punti Carriera !!!");
                  if ($flawless == 1){
                     output(" Ti consoli raccogliendo l".(($costo==0)? "e" : "a")." gemm".(($costo==0)? "e" : "a"));
                     output("che avevi pagato per i suoi servigi, e che avendo fatto un combattimento perfetto meriti di non ");
                     output("pagare.`n");
                     $session['user']['gems'] += $costo;
                  }else{
                     output(".`n");
                  }
               }
               $session['user']['specialinc']="";
               addnav("Torna al Villaggio","village.php");
         }elseif($defeat) {
            output("`n`n`3Il Sabotatore sta per affondare il fendente che porrà fine al vostro scontro, ma anzichè ");
            output("porre fine alla tua vita ti offre una pozione rigenerante e ti tende la mano per aiutarti a ");
            output("rimetterti in piedi.`n`n\"`@Sei stato un avversario valoroso, e non ritengo necessario proseguire ");
            output("lo scontro per arrivare alla tua morte. Ovviamente non effettuerò il sabotaggio alle sette ");
            output("avversarie visto che non sei riuscito a battermi, ma sappi che se in futuro dovessimo nuovamente ");
            output("incontrarci, ti concederò nuovamente la possibilità di affrontarmi.`3\"`n`nDetto ciò si congeda ");
            output("da te, e si dirige nel fitto della foresta, dove scompare dopo qualche istante.`nHai perso una ");
            output("chance di avvantaggiare la setta di ".$fedecasa[$dio]."`3 alla quale appartieni, ma a parte questo ");
            output("non hai subito altre perdite.`n");
            addnews($session['user']['name'] . " `5è stat".($session[user][sex]?"a":"o")." sconfitt".($session[user][sex]?"a":"o")." dal `^Sabotatore!");
            debuglog("è stato sconfitto dal sabotatore.");
            $session['user']['specialinc']="";
            $session['user']['hitpoints'] = $session['user']['maxhitpoints'];
            $session['user']['badguy']="";
            $session['user']['turns'] -= 1;
            addnav("Torna al Villaggio","village.php");
         }else {
            $session['user']['specialinc']="sabotatore.php";
            fightnav(false,false);
         }
         }
      }elseif($_GET['op'] == "abbandona"){
         $session['user']['specialinc']="";
         if ($_GET['op1'] == 1){
            output("`3Purtroppo non hai di che pagare il `^Sabotatore`3 e non puoi quindi usufruire dei sui servigi.");
         }else{
            output("`3Non ha abbastanza fegato per affrontare il `^Sabotatore`3!!!");
         }
         output("`3Ti allontani quindi da lui e torni alla foresta ...`n");
         addnav("Torna alla Foresta","forest.php");
      }
   }else {
       //Caso player agnostico
       if ($_GET['op']== ""){
         $badguy = array(
           "creaturename"=>"`4Il Sabotatore",
           "creaturelevel"=>$session['user']['level'],
           "creatureweapon"=>"`(Coltello da Rambo`0",
           "creatureattack"=>($session['user']['attack']*0.98),
           "creaturedefense"=>($session['user']['defence']*1.02),
           "creaturehealth"=>ceil($session['user']['maxhitpoints']*1.03),
           "diddamage"=>0
         );
         $session['user']['badguy']=createstring($badguy);
         $session['user']['specialinc']="sabotatore.php";
         output("`3Nella tua ricerca di avventure nella foresta, ti imbatti in uno strano tipo che ti si para davanti.`n");
         output("È abbigliato in maniera decisamente inusuale, con una attillata tuta nera e armi e coltelli appese alla cintura!`n");
         output("Lo scruti con attenzione per capire se può essere pericoloso o meno, quando alzando una mano in segno di ");
         output("pace, si rivolge a te dicendoti:`n`n");
         output("\"`@Salve guerriero, non temere non ho intenzioni pericolose.`n");
         output("Dalla mancanza di tatuaggi sulle tue braccia e sulle tue vesti noto che non sei schierato con nessuna setta. ");
         output("È un vero peccato, visto che generalmente offro i miei servigi agli adoratori di tali sette per sabotare le ");
         output("sette rivali, ma il nostro incontro potrà comunque esserti utile. È da qualche giorno che sono inattivo ed ");
         output("ho proprio voglia di battermi con un guerriero per tenermi in allenamento, e se riuscirai a battermi saprò ");
         output("ricompensarti adeguatamente.`3\"`n`n");
         output("`3Sapendo di non aver nulla da perdere, decidi di accettare la sfida, chissà che la giornata non ti ");
         output("riservi una bella sorpresa?`n");
         addnav("Affrontalo","forest.php?op=prosegui");
      }elseif($_GET['op'] == "prosegui"){
         $session['user']['specialinc']="sabotatore.php";
         //il player accetta lo scontro
         output("`3\"`@Bene, vedo che il fegato non ti manca!`nPrima mi sono dimenticato di avvisarti che in questo scontro ");
         output("non è consentito usare attacchi speciali.`n");
         //controllo su HP per eventuale ripristino al massimo
         if ($hp < $maxhp){
            if ($oro > $costohporo){
               output("Se ti serve posso fornirti una pozione per riportare al massimo i tuoi HP al modico costo ");
               output("di $costohporo pezzi d'oro. Ne vuoi approfittare?`3\"`n");
               addnav("Ripristina HP massimi","forest.php?op=hpmax");
               addnav("No, sto bene così","forest.php?op=fight");
            }else{
              output("Peccato tu non sia al 100% della tua forma, ma forse questo renderà il nostro scontro più interessante!`3\"");
              $battle=true;
              addnav("Combatti!","forest.php?op=fight");
            }
         }else{
            output("Bene, tutto è pronto! Seguimi nella radura qui a fianco, dove potremo confrontarci senza problemi.`3\"`n`n");
            output("Detto ciò si avvia e tu lo segui.`nArrivati nello spazio senza alberi vi fronteggiate, e dopo esservi rivolti ");
            output("i rispettosi inchini di rito, date inizio allo scontro.`n");
            $battle=true;
            addnav("Combatti!","forest.php?op=fight");
         }
      }elseif($_GET['op'] == "hpmax"){
         $session['user']['specialinc']="sabotatore.php";
         //Il player paga per riportare al massimo gli HP
         $session['user']['gold'] -= $costohporo;
         $session['user']['hitpoints'] = $session['user']['maxhitpoints'];
         output("`3Paghi $costohporo pezzi d'oro al Sabotatore, che ti allunga una pozione che trangugi ");
         debuglog("paga $costohporo oro al Sabotatore per riportare HP max");
         output("velocemente ... ha un gusto delicato di limonata e ti senti nuovamente nel pieno della tua ");
         output("forma fisica.`nOra sei pronto ad affrontare il duello!!`n`n");
         $battle=true;
         addnav("Combatti!","forest.php?op=fight");
      }elseif($_GET['op'] == "fight"){
         $battle=true;
         if ($battle){
            include_once("battle.php");
            $_GET['op']="fight";
            $session['user']['specialinc']="sabotatore.php";
            if($victory) {
               $flawless = 0;
               if ($badguy['diddamage'] != 1){
                  $flawless = 1;
                  output("`b`c`&~~ Combattimento Perfetto ~~`0`c`b`n`n");
               }
               $session['user']['sabota'] = 1;
               output("`n`n`3Stai per affondare l'ultimo colpo sul Sabotatore quando ti chiede di risparmiarlo e ti ");
               output("ricorda La ricompensa che ti attende. Fermi la tua furia ed egli estrae una pozione da una ");
               output("tasca e, visibilmente scosso per la sconfitta, si rimette in piedi e dice:`n`n");
               output("\"`@Bene, mi hai sconfitto ed onorerò la promessa.`n");
               output("Prendi questa pozione, preparata dal druido del Monastero, ti aiuterà nei tuoi combattimenti,");
               output("consentendoti di dimezzare i danni subiti.`3\"`n`nPoi ti allunga un borsellino tintinnante e ");
               $rewardgold = $session['user']['level']*500;
               $rewardgem = e_rand(4,10);
               $session['user']['gold'] += $rewardgold;
               $session['user']['gems'] += $rewardgem;
               debuglog("guadagna $rewardgold oro e $rewardgems gemme per aver sconfitto il Sabotatore");
               addnews($session['user']['name'] . " `@ha sconfitto il `^Sabotatore`@, e ha guadagnato un bel gruzzoletto!");
               output("quando lo apri scopri che contiene `^$rewardgold pezzi d'oro`3 e `&$rewardgem gemme`3 !!!");
               $turnisabo = e_rand(40,60);
               $session['bufflist']['sabotatore'] =
                         array("name"=>"`(Sabotaggio",
                               "rounds"=>$turnisabo,
                               "wearoff"=>"La pozione esaurisce il suo effetto, e non riesci a sabotare le mosse del tuo avversario",
                               "badguydmgmod"=>0.5,
                               "roundmsg"=>"Riesci a sabotare gli attacchi di {badguy}, che ti provoca solo metà danno.",
                               "activate"=>"defense");
               $session['user']['specialinc']="";
               addnav("Torna al Villaggio","village.php");
            }elseif($defeat) {
               output("`n`n`3Il Sabotatore sta per affondare il fendente che porrà fine al vostro scontro, ma anzichè ");
               output("porre termine alla tua misera vita ti offre una pozione rigenerante e ti tende la mano per aiutarti a ");
               output("rimetterti in piedi.`n`n\"`@Sei stato un avversario valoroso, e non ritengo necessario proseguire ");
               output("lo scontro per arrivare alla tua morte.");
               addnews($session['user']['name'] . " `5è stato sconfitto dal `^Sabotatore!");
               debuglog("è stato sconfitto dal Sabotatore.");
               $session['user']['specialinc']="";
               $session['user']['hitpoints'] = $session['user']['maxhitpoints'];
               $session['user']['badguy']="";
               $session['user']['turns'] -= 1;
               addnav("Torna al Villaggio","village.php");
            }else {
               $session['user']['specialinc']="sabotatore.php";
               fightnav(false,false);
            }
         }
      }
   }
}else{
   //Il player ha già incontrato il sabotatore, lo mandiamo da qualche altra parte ^__^
   $session['user']['specialinc']="";
   output("`#Hai già incontrato il `%Sabotatore`@ oggi, e quindi per il momento non c'è niente da fare qui.`n");
   addnav("Torna al Villaggio","village.php");
}
?>