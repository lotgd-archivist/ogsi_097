<?php
require_once "common.php";
if ($session['thequest'] != null) {$quest = $session['thequest'];} else {$quest=array();$quest['navigazione']="";}

$dungeonMaxAsseX = 4;
$dungeonMaxAsseY = 4;

// solo per test - mostra le coordinate delle stanze
function test_dungeon($dungeon,$dungeonMaxAsseX,$dungeonMaxAsseY) {
   for ($asseX=0;$asseX<$dungeonMaxAsseX+2;$asseX++) {
        for ($asseY=0;$asseY<$dungeonMaxAsseY+2;$asseY++) {
             output("`nAsse X ".$asseX." Asse Y ".$asseY." Stanza ".$dungeon[$asseX][$asseY]);
        }
   }
}

// inserisce la $stanza in una posizione casuale non occupata
function inserisciStanza($stanza,$dungeon,$dungeonMaxAsseX,$dungeonMaxAsseY) {
   do {
     $asseX = e_rand(1,$dungeonMaxAsseX);
     $asseY = e_rand(1,$dungeonMaxAsseY);
     if ($dungeon[$asseX][$asseY] == "stanzavuota") {
         $dungeon[$asseX][$asseY] = $stanza;
         break;
     }
   } while ($dungeon[$asseX][$asseY]!=$stanza);
   return $dungeon;
}

// inizializza il dungeon e prepara le stanze
function generaDungeon($dungeon,$dungeonMaxAsseX,$dungeonMaxAsseY) {
   // dimensioni 4x4
   for ($asseX=1;$asseX<$dungeonMaxAsseX+1;$asseX++) {
        for ($asseY=1;$asseY<$dungeonMaxAsseY+1;$asseY++) {
             $dungeon[$asseX][$asseY] = "stanzavuota";
        }
   }
   // prima stanza a posizione 1,1
   $dungeon[1][1] = "room1";
   // generazione casuale delle altre stanze
   $dungeon = inserisciStanza("room2",$dungeon,$dungeonMaxAsseX,$dungeonMaxAsseY);
   $dungeon = inserisciStanza("room3",$dungeon,$dungeonMaxAsseX,$dungeonMaxAsseY);
   $dungeon = inserisciStanza("room4",$dungeon,$dungeonMaxAsseX,$dungeonMaxAsseY);
   $dungeon = inserisciStanza("room5",$dungeon,$dungeonMaxAsseX,$dungeonMaxAsseY);
   $dungeon = inserisciStanza("room6",$dungeon,$dungeonMaxAsseX,$dungeonMaxAsseY);
   $dungeon = inserisciStanza("room7",$dungeon,$dungeonMaxAsseX,$dungeonMaxAsseY);
   $dungeon = inserisciStanza("room8",$dungeon,$dungeonMaxAsseX,$dungeonMaxAsseY);
   $dungeon = inserisciStanza("room9",$dungeon,$dungeonMaxAsseX,$dungeonMaxAsseY);
   $dungeon = inserisciStanza("room10",$dungeon,$dungeonMaxAsseX,$dungeonMaxAsseY);
   return $dungeon;
}

// genera casualmente l'uscita all'estremità del dungeon
function generaUscita($dungeon,$dungeonMaxAsseX,$dungeonMaxAsseY) {
   $uscita = e_rand(0,1);
   // inizializza limite
   $uscitaAsseX = 5;
   for ($uscitaAsseY=0;$uscitaAsseY<$dungeonMaxAsseY+2;$uscitaAsseY++) {
       $dungeon[$uscitaAsseX][$uscitaAsseY]="limite";
   }
   $uscitaAsseX = 0;
   for ($uscitaAsseY=0;$uscitaAsseY<$dungeonMaxAsseY+2;$uscitaAsseY++) {
       $dungeon[$uscitaAsseX][$uscitaAsseY]="limite";
   }
   $uscitaAsseY = 5;
   for ($uscitaAsseX=0;$uscitaAsseX<$dungeonMaxAsseX+2;$uscitaAsseX++) {
       $dungeon[$uscitaAsseX][$uscitaAsseY]="limite";
   }
   $uscitaAsseY = 0;
   for ($uscitaAsseX=0;$uscitaAsseX<$dungeonMaxAsseX+2;$uscitaAsseX++) {
       $dungeon[$uscitaAsseX][$uscitaAsseY]="limite";
   }
   if ($uscita==0) {
       // l'uscita è sull'asse X
       $uscitaAsseX = 5;
       $uscitaAsseY = e_rand(1,$dungeonMaxAsseY);
       $dungeon[$uscitaAsseX][$uscitaAsseY]="uscita";
       $dungeon[$uscitaAsseX+1][$uscitaAsseY]="limite";
   } else {
       // l'uscita è sull'asse Y
       $uscitaAsseY = 5;
       $uscitaAsseX = e_rand(1,$dungeonMaxAsseX);
       $dungeon[$uscitaAsseX][$uscitaAsseY]="uscita";
       $dungeon[$uscitaAsseX][$uscitaAsseY+1]="limite";
   }
   return $dungeon;
}

// ricava dal nome della stanza la descrizione
function generaStanza($dungeon,$asseX,$asseY,$quest) {

   $combattimento = false;

   if ($dungeon[$asseX][$asseY] == "stanzavuota") {
       page_header("Stanza");
       output("`5La stanza è piena di ragnatele e polvere. Non c'è nulla che attrae la tua attenzione.");
   }
   // solo per verifica
   if ($dungeon[$asseX][$asseY] == "limite") {
       page_header("Il Limite");
       output("`5Hai raggiunto il limite del Dungeon.");
   }
   if ($dungeon[$asseX][$asseY] == "uscita") {
       page_header("L'Uscita");
       output("`5Dopo qualche minuto di camminata vedi una luce filtrare nella roccia.
                Non ti eri accorto che questo corridoio piano piano saliva in superficie.`n");
       output("`5Dopo molto tempo passato nei sotterranei, finalmente respiri aria pura.`n");
       output("`5Ti ritrovi in una specie di giardino pieno di fiori colorati.`n");
       output("`5Una bocca, come quella all'inizio di questa costruzione, appare davanti a te e comincia a parlare.`n`n");
       output("`3Benvenuto nobile avventuriero. Hai trovato la strada per uscire da questo test. Ma solo che possiede la chiave
                giusta può uscire da quì. Tu la possiedi?");
       if ($quest['goldkey']==1) {
           addnav("M?Mostra la chiave","iltest.php?op=fine");
       }
   }
   if ($dungeon[$asseX][$asseY] == "room1") {
       page_header("La Stanza Animata");
       output("`5Entri in una stanza buia il cui unico contenuto è ragnatele e polvere.");
       if ($quest['navigazione']=='tunnel1' || $quest['navigazione']=='pozzo') {
          if ($quest['navigazione']=='tunnel1') {
             output("`5`n`nLa porta di legno alle tue spalle svanisce come per magia, al suo posto rimane solida roccia,
                      una");
          } else {
            output("`n`nUna");
          }
          output("`5 bocca appare davanti a te, comincia a parlare.`n`n");
          output("`3Benvenuto. Stai per affrontare il Test di `6Excalibur il Grande`3.
                   Sei qui per mettere alla prova te stesso e vedere se sei degno del tuo rango.
                   Il test è inequivocabile, devi lasciare questo complesso senza aiuto alcuno.
                   `nBuona Fortuna.`n`n");
          output("`5Dopo aver proferito queste parole, la bocca svanisce nel nulla.`n");
       }
   }

   if ($dungeon[$asseX][$asseY] == "room2") {
       page_header("La tana della Manticora");
       output("Ti ritrovi in una stanza il cui pavimento è ricoperto d'ossa di piccoli animali.`n`n");
       if ($quest['manticora']==1) {
           output("`5Una volta entrato senti una presenza minacciosa e le tue preoccupazioni sono presto realizzate.`n");
           output("`5Nell'oscurità non avevi notato che la stanza è a tutti gli effetti la tana di una `4Manticora `5!!`n");
           addnav("C?Combatti","manticora.php");
           $combattimento = true;
       } else {
           output("`5Ti senti tranquillo nel vedere in terra il cadavere della `4Manticora `5uccisa poco prima, potevi
                    esserci tu al suo posto, ma è andata diversamente.`n");
       }
   }

   if ($dungeon[$asseX][$asseY] == "room3") {
       page_header("La sala dei Mind Flayer");
       output("`5Ti ritrovi in una stanza, il pavimento è ricoperto di cadaveri umani decomposti.`n`n");
       if ($quest['mindflayer']==1) {
           output("`5Una volta entrato hai difronte a te due figure alte circa due metri, con la pelle color malva.`n");
           output("`5La loro testa sembra gelatina e quattro tentacoli che escono dalle pupille.`n");
           addnav("C?Combatti","mindflayer.php");
           $combattimento = true;
       } else {
           output("`5Vedi anche i cadaveri dei due `4Mind Flayer `5uccisi poco prima.`n");
       }
   }

   if ($dungeon[$asseX][$asseY] == "room4") {
        page_header("Camera");
        output("`5Entri in una stanza le cui mura sono decorate da arazzi raffiguranti
                 scene di battaglie tra eroici cavalieri e il famigerato `2Drago Verde`5.");
        if ($quest['chest']==1) {
            output("`5`nIn terra c'è uno scrigno chiuso con un lucchetto.");
            addnav("A?Apri lo scrigno","iltest.php?op=chestroom");
        } else {
            output("`5`nIn terra c'è lo scrigno che hai aperto prima.");
        }
   }

   if ($dungeon[$asseX][$asseY] == "room5") {
        output("`5Entri in una stanza decorata da arazzi raffiguranti
                 scene di un mago che lancia un incantesimo contro un armata di `4Orchi`5.`n");
        if ($quest['potion']==1) {
            output("`5`nIn terra c'è un ampolla piena di uno strano liquido.");
            addnav("B?Bevi","iltest.php?op=ampollaroom");
        }
   }

   if ($dungeon[$asseX][$asseY] == "room6") {
       page_header("Il Tempio");
       output("`5Entri in una stanza decorata da arazzi raffiguranti una creatura
                divina che si adopera per il bene dell'umanità. Ci sono anche quattro urne d'argento sul pavimento
                che stanno alla base di quello che sembra un altare. Dall'altra parte dell'altare c'è una statua
                che sembrerebbe rappresentare la creatura divina degli arazzi.");
       if ($session['user']['dio']==1) {
          output("`5`nRiconosci che la divinità è `6Sgrios il Grande`5.");
          addnav("P?Prega","iltest.php?op=prayroom");
       }
   }

   if ($dungeon[$asseX][$asseY] == "room7") {
       page_header("La Sala Degli Specchi");
       output("`5`nTi ritrovi in una stanza le cui mura sono fatte di un materiale riflettente. `n`n");
       addnav("H?SpeccHiati","iltest.php?op=specchio");
   }

   if ($dungeon[$asseX][$asseY] == "room8") {
       page_header("La Stanza del Mago Ogre");
       if ($quest['mageogre']==1) {
          output("Davanti a te c'è una porta di legno, quando la apri rimani shockato da una orribile visione, una creatura
                   alta più di due metri con la pelle color blu stà al centro della stanza intento a sgranocchiare delle ossa
                   umanoidi. Al contrario delle tue preghiere, la sua attenzione ora è verso di te.");
           addnav("C?Combatti","mageogre.php");
           $combattimento = true;
       } else {
           output("`5Quando entri nella stanza vedi alcuni cadaveri umanoidi vicino al corpo del `4Mago Ogre `5che hai ucciso
                    poco prima, tiri un sospiro di sollievo pensando che a quest'ora potevi essere cibo per Ogre.`n");
       }
   }

   if ($dungeon[$asseX][$asseY] == "room9") {
       page_header("La Sala del Trono");
       output("`5Entri in una stanza che sembra essere una sala del trono. Sulla parete est si erge a circa tre metri di altezza
                un piccolo trono di color porpora. Il muro è coperto di arazzi raffiguranti normali attività di corte. Il
                soffitto è decorato di angeli che volano attorno ad un grande castello e un sole che sorge dietro una
                grande montagna. Al contrario di tutte le altre stanze che hai visitato, in questa la polvere è
                totalmente assente, anzi, nell'aria si sente un forte profumo di rose.`n");
       if ($quest['trono'] == 1) {
           addnav("T?Avvicinati al Trono","iltest.php?op=trono");
       }
   }

   if ($dungeon[$asseX][$asseY] == "room10") {
       page_header("La Biblioteca");
       output("`5Entri in una stanza piena di libri ordinatamente raccolti in pile. A prima vista saranno più di 500 volumi.`n");
       output("`5L'aria ha un forte odore di pelle e su tutti gli angoli della stanza ci sono ragnatele.`n");
       if ($quest['book'] == 1) {
           addnav("L?Leggi un libro","iltest.php?op=book");
       }
   }

   if (!$combattimento) {
        generaNavigazione($dungeon,$asseX,$asseY);
        output("`#`n`nCosa fai ?");
   }

   // solo per i test
   //output("`4`n`nasseX ".$asseX."`nasseY ".$asseY);

}

// genera il menù di navigazione omettendo le coordinate con il limite
function generaNavigazione($dungeon,$asseX,$asseY) {
    // Maximus inizio modifica per evitare la navigazione all'infinito se si perde la sessione
    if ($dungeon == null) {
       output("`n`n`\$`c`bATTENZIONE, LA TUA SESSIONE E' SCADUTA ED HAI PERSO I RIFERIMENTI AL TEST DI EXCALIBUR, SEI COSTRETTO A TORNARE AL VILLAGGIO`b`c'`n`n");
       addnav("V?Torna al Villaggio","village.php");
       return;
    }
    // Maximus Fine
    $nord = true;
    $sud = true;
    $est = true;
    $ovest = true;
    if ($dungeon[$asseX+1][$asseY]=='limite') {
        $nord = false;
    }
    if ($dungeon[$asseX-1][$asseY]=='limite') {
        $sud = false;
    }
    if ($dungeon[$asseX][$asseY+1]=='limite') {
        $est = false;
    }
    if ($dungeon[$asseX][$asseY-1]=='limite') {
        $ovest = false;
    }
    if ($nord){
        addnav("N?Vai a Nord","iltest.php?op=nord");
    }
    if ($sud){
        addnav("S?Vai a Sud","iltest.php?op=sud");
    }
    if ($est){
        addnav("E?Vai a Est","iltest.php?op=est");
    }
    if ($ovest){
        addnav("O?Vai a Ovest","iltest.php?op=ovest");
    }
}

$operazione = $_GET['op'];
switch ($operazione) {
        case "":
             $dungeon = array();
             page_header("Il Pozzo D'Oro");
             $dungeon = generaDungeon($dungeon,$dungeonMaxAsseX,$dungeonMaxAsseY);
             $dungeon = generaUscita($dungeon,$dungeonMaxAsseX,$dungeonMaxAsseY);
             //test_dungeon($dungeon,$dungeonMaxAsseX,$dungeonMaxAsseY);
             //output("`5`n`n");
             output("`5Ti fai strada tra gli alberi avvicinandoti al bagliore.
                      Vedi che i raggi del sole riflettono sulla superficie di un vecchio pozzo in disuso
                      e più ti avvicini più diventano luminosi e quasi ti accecano.`n
                      Senti una grande fonte di magia sprigionare dal pozzo.`n");
             addnav("T?Tuffati nel Pozzo","iltest.php?op=room1");
             if ($session['user']['race'] == 4 || $session['user']['thievery'] >= 1){
                 output("`5`nGrazie alle tue abilità di ");
                 if ($session['user']['race'] == 4) {
                     output("Nano ");
                 } else {
                     output("Ladro ");
                 }
                 output("riesci a scoprire una specie di passaggio segreto vicino il pozzo");
                 addnav("P?Prendi il passaggio","iltest.php?op=tunnel1");
             }
             addnav("S?Scappa","iltest.php?op=scappa");
             $quest['dungeon']=$dungeon;
             $quest['navigazione']='pozzo';
             $quest['manticora']=1;
             $quest['mindflayer']=1;
             $quest['mageogre']=1;
             $quest['trap1']=1;
             $quest['trap2']=1;
             $quest['trap3']=1;
             $quest['trap4']=1;
             $quest['trono']=1;
             $quest['book']=1;
             $quest['goldkey']=0;
             $quest['chest']=1;
             $quest['pray']=1;
             $quest['potion']=1;
             $session['thequest'] = $quest;
             $session['user']['quest'] += 1;
             break;
             // end case ""
       case "scappa":
             page_header("Il Pozzo D'Oro");
             output("`5Credi sia meglio non sfidare la fortuna oggi, ti volti indietro e vai verso il villaggio");
             addnav("V?Torna al Villaggio","village.php");
             $session['thequest'] = null;
             break;
       case "tunnel1":
             page_header("Tunnel");
             output("`5Il tunnel si dirige verso Est per circa 2 kilometri e man mano che vai avanti ti sembra che abbia una leggera
                     pendenza verso il basso.`n");
             output("`5Alla fine del corridoio ti trovi davanti una porta di legno senza serratura.");
             addnav("A?Apri la porta ed Entra","iltest.php?op=room1");
             addnav("T?Torna Indietro","iltest.php");
             addnav("S?Scappa","iltest.php?op=scappa");
             $quest['navigazione']='tunnel1';
             $session['thequest'] = $quest;
             break;
       case "room1":
             $playerAsseX = 1;
             $playerAsseY = 1;
             $dungeon = $quest['dungeon'];
             generaStanza($dungeon,$playerAsseX,$playerAsseY,$quest);
             $quest['navigazione']=$dungeon[$playerAsseX][$playerAsseY];
             $quest['playerAsseX']=$playerAsseX;
             $quest['playerAsseY']=$playerAsseY;
             $session['thequest'] = $quest;
             break;
       case "nord":
             page_header("Corridoio");
             output("`5Esci dalla stanza e ti incammini per un corridoio verso Nord ");
             $casualTrap = e_rand(0,5);
             $trap = 1;
             if ($quest['trap1']==1 && $casualTrap==0) {
                output("`5finchè ti accorgi di aver inavvertitamente premuto una specie di bottone per terra.`n
                         Hai appena attivato una `3trappola `5!!");
                output("`5`n`nUna grossa pietra cade sopra di te..`n");
                $trap = e_rand(0,9);
                $quest['trap1'] = 0;
                switch ($trap) {
                       case 0:
                            output("`5Purtroppo non riesci ad evitarla, la roccia è troppo pesante per te e
                                     ben presto rimani senza respiro..");
                            output("`3`n`nPerdi tutto il tuo oro.");
                            output("`3`nPuoi continuare a giocare domani");
                            debuglog("muore schiacciato da una pietra perdendo {$session[user][gold]} oro nel test di Excalibur");
                            addnav("T?Terra delle Ombre","village.php");
                            $session['user']['alive']=false;
                            $session['user']['hitpoints']=0;
                            $session['user']['gold']=0;
                            $quest['navigazione']="";
                            break;
                       case 1: case 2: case 3: case 4: case 5: case 6:
                            output("`5Purtroppo non riesci ad evitarla, perdi alcuni punti ferita ma comunque riesci a
                                     divincolarti da sotto la pietra..");
                            $hplose = intval($session['user']['maxhitpoints']*0.1);
                            $session['user']['hitpoints'] -= $hplose;
                            if ($session['user']['hitpoints'] < 1)  {
                               $session['user']['hitpoints'] = 1;
                            }
                            break;
                       case 7: case 8: case 9:
                            output("`5Per tua fortuna ultimamente ti sei allenato spesso e riesci ad evitarla.");
                            break;
                }
             }
             if ($trap==0) break;
             output("`n`n");
             $playerAsseX = $quest['playerAsseX'] + 1;
             $playerAsseY = $quest['playerAsseY'];
             $dungeon = $quest['dungeon'];
             generaStanza($dungeon,$playerAsseX,$playerAsseY,$quest);
             $quest['navigazione']=$dungeon[$playerAsseX][$playerAsseY];
             $quest['playerAsseX']=$playerAsseX;
             $quest['playerAsseY']=$playerAsseY;
             $session['thequest'] = $quest;
             break;
       case "sud":
             page_header("Corridoio");
             output("`5Ti incammini per un corridoio verso Sud ");
             $casualTrap = e_rand(0,5);
             $trap = 1;
             if ($quest['trap3']==1 && $casualTrap==0) {
                output("`5finchè ti accorgi di aver inavvertitamente premuto una specie di bottone per terra.`n
                         Hai appena attivato una `3trappola `5!!");
                output("`5`n`nUna fiammata erutta da una fessura nel muro che non avevi notato..`n");
                $trap = e_rand(0,9);
                $quest['trap3'] = 0;
                switch ($trap) {
                       case 0:
                            output("`5Purtroppo non riesci ad evitarla, il tuo corpo viene carbonizzato all'istante..");
                            output("`3`n`nPerdi tutto il tuo oro.");
                            output("`3`nPuoi continuare a giocare domani");
                            debuglog("muore carbonizzato perdendo {$session[user][gold]} oro nel test di Excalibur");
                            addnav("T?Terra delle Ombre","village.php");
                            $session['user']['alive']=false;
                            $session['user']['hitpoints']=0;
                            $session['user']['gold']=0;
                            $quest['navigazione']="";
                            break;
                       case 1: case 2: case 3: case 4: case 5: case 6:
                            output("`5Purtroppo non riesci ad evitarla del tutto, perdi alcuni punti ferita ma comunque riesci a
                                     rimanere vivo..");
                            $hplose = intval($session['user']['maxhitpoints']*0.1);
                            $session['user']['hitpoints'] -= $hplose;
                            if ($session['user']['hitpoints'] < 1)  {
                               $session['user']['hitpoints'] = 1;
                            }
                            break;
                       case 7: case 8: case 9:
                            output("`5Per tua fortuna ultimamente ti sei allenato spesso e riesci a schivarla.");
                            break;
                 }
             }
             if ($trap==0) break;
             output("`n`n");
             $playerAsseX = $quest['playerAsseX'] - 1;
             $playerAsseY = $quest['playerAsseY'];
             $dungeon = $quest['dungeon'];
             generaStanza($dungeon,$playerAsseX,$playerAsseY,$quest);
             $quest['navigazione']=$dungeon[$playerAsseX][$playerAsseY];
             $quest['playerAsseX']=$playerAsseX;
             $quest['playerAsseY']=$playerAsseY;
             $session['thequest'] = $quest;
             break;
       case "est":
             page_header("Il Corridoio");
             output("`5Esci dalla stanza e ti incammini per un corridoio verso Est ");
             $casualTrap = e_rand(0,5);
             $trap = 1;
             if ($quest['trap4']==1 && $casualTrap==0) {
                output("`5finchè ti accorgi di aver inavvertitamente premuto una specie di bottone per terra.`n
                         Hai appena attivato una `3trappola `5!!");
                output("`5`n`nUna serie di spuntoni metallici escono dal pavimento..`n");
                $trap = e_rand(0,9);
                $quest['trap4'] = 0;
                switch ($trap) {
                       case 0:
                            output("`5Purtroppo non riesci ad evitarli, il tuo corpo è infilzato e muori all'istante..");
                            output("`3`n`nPerdi tutto il tuo oro.");
                            output("`3`nPuoi continuare a giocare domani");
                            debuglog("muore infilzato dagli spuntoni perdendo {$session[user][gold]} oro nel test di Excalibur");
                            addnav("T?Terra delle Ombre","village.php");
                            $session['user']['alive']=false;
                            $session['user']['hitpoints']=0;
                            $session['user']['gold']=0;
                            $quest['navigazione']="";
                            break;
                       case 1: case 2: case 3: case 4: case 5: case 6:
                            output("`5Purtroppo non riesci ad evitarli del tutto, perdi alcuni punti ferita ma comunque riesci a
                                     rimanere vivo..");
                            $hplose = intval($session['user']['maxhitpoints']*0.1);
                            $session['user']['hitpoints'] -= $hplose;
                            if ($session['user']['hitpoints'] < 1)  {
                               $session['user']['hitpoints'] = 1;
                            }
                            break;
                       case 7: case 8: case 9:
                            output("`5Per tua fortuna ultimamente ti sei allenato spesso e riesci a schivarli.");
                            break;
                }
             }
             if ($trap==0) break;
             output("`n`n");
             $playerAsseX = $quest['playerAsseX'];
             $playerAsseY = $quest['playerAsseY'] + 1;
             $dungeon = $quest['dungeon'];
             generaStanza($dungeon,$playerAsseX,$playerAsseY,$quest);
             $quest['navigazione']=$dungeon[$playerAsseX][$playerAsseY];
             $quest['playerAsseX']=$playerAsseX;
             $quest['playerAsseY']=$playerAsseY;
             $session['thequest'] = $quest;
             break;
        case "ovest":
             page_header("Il Corridoio");
             output("`5Esci dalla stanza e ti incammini per un corridoio verso Ovest ");
             output("`n`n");
             $playerAsseX = $quest['playerAsseX'];
             $playerAsseY = $quest['playerAsseY'] - 1;
             $dungeon = $quest['dungeon'];
             generaStanza($dungeon,$playerAsseX,$playerAsseY,$quest);
             $quest['navigazione']=$dungeon[$playerAsseX][$playerAsseY];
             $quest['playerAsseX']=$playerAsseX;
             $quest['playerAsseY']=$playerAsseY;
             $session['thequest'] = $quest;
             break;
        case "manticorawin":
             page_header("La tana della Manticora");
             output("`5Guardi il cadavere della `4Manticora `5, poi osservi meglio la stanza ma non trovi niente di interessante.`n`n");
             $quest['manticora']=0;
             $playerAsseX = $quest['playerAsseX'];
             $playerAsseY = $quest['playerAsseY'];
             $dungeon = $quest['dungeon'];
             $quest['navigazione']=$dungeon[$playerAsseX][$playerAsseY];
             generaNavigazione($dungeon,$playerAsseX,$playerAsseY);
             $quest['playerAsseX']=$playerAsseX;
             $quest['playerAsseY']=$playerAsseY;
             $session['thequest'] = $quest;
             break;
        case "mindflayerwin":
             page_header("La sala dei Mind Flayer");
             output("`5Guardi i cadaveri dei `4Mind Flayer `5e li ");
             output("`5perquisisci meglio, trovi una piccola `6chiave d'oro`5..`n");
             output("`5`nPensando che ti sarà utile in futuro la metti in tasca..`n");
             $quest['goldkey']=1;
             $quest['mindflayer']=0;
             $playerAsseX = $quest['playerAsseX'];
             $playerAsseY = $quest['playerAsseY'];
             $dungeon = $quest['dungeon'];
             $quest['navigazione']=$dungeon[$playerAsseX][$playerAsseY];
             generaNavigazione($dungeon,$playerAsseX,$playerAsseY);
             $quest['playerAsseX']=$playerAsseX;
             $quest['playerAsseY']=$playerAsseY;
             $session['thequest'] = $quest;
             break;
         case "chestroom":
             page_header("Lo Scrigno");
             output("`5Forzi la serratura dello scrigno e...`n`n");
             $chest = e_rand(0,5);
             switch ($chest) {
                     case 0:
                          output("`5Hai appena attivato una `3trappola `5!!");
                          output("`5`nUna nuvola di veleno ti avvolge il viso e ti rende cieco !!");
                          output("`5`nIl veleno penetra nei polmoni e piano piano ti uccide..");
                          output("`3`n`nPuoi continuare a giocare domani");
                          debuglog("muore avvelenato cercando di aprire uno scrigno, perdendo {$session[user][gold]} oro nel test di Excalibur");
                          addnav("T?Terra delle Ombre","village.php");
                          $session['user']['alive']=false;
                          $session['user']['hitpoints']=0;
                          $session['user']['gold']=0;
                          $quest['navigazione']="";
                          break;
                     case 1:
                     case 2:
                          output("`5Hai appena attivato una `3trappola `5!!");
                          output("`5`nUna nuvola di veleno ti avvolge il viso e ti rende cieco !!");
                          output("`5`nPer tua fortuna il veleno non è molto potente e ben presto
                                   riacquisti la vista.");
                          output("`5`n`nNello scrigno trovi 500 pezzi d'oro !!");
                          $session['user']['gold']+=500;
                          break;
                     case 3:
                     case 4:
                     case 5:
                          output("`5Sei fortunato !! `nNello scrigno trovi una gemma e 500 pezzi d'oro !!");
                          $session['user']['gold']+=500;
                          $session['user']['gems']+=1;
                          break;
             }
             if ($chest==0) break;
             $quest['chest']=0;
             $playerAsseX = $quest['playerAsseX'];
             $playerAsseY = $quest['playerAsseY'];
             $dungeon = $quest['dungeon'];
             $quest['navigazione']=$dungeon[$playerAsseX][$playerAsseY];
             generaNavigazione($dungeon,$playerAsseX,$playerAsseY);
             $quest['playerAsseX']=$playerAsseX;
             $quest['playerAsseY']=$playerAsseY;
             $session['thequest'] = $quest;
             break;
         case "ampollaroom":
             page_header("Camera");
             output("`5Raccogli da terra l'ampolla e ne bevi una bella sorsata...`n`n");
             $ampollaroom = e_rand(0,10);
             switch ($ampollaroom) {
                     case 0: case 1: case 2:
                          if ($session['user']['hitpoints'] >= $session['user']['maxhitpoints']) {
                              output("`5Non senti nessun effetto nocivo o benefico`n");
                          } else {
                              output("`5Le tue ferite iniziano a curarsi da sole !!`n");
                              $hpcure = $session['user']['maxhitpoints'] - $session['user']['hitpoints'];
                              $hpcure = $hpcure / 2;
                              $session['user']['hitpoints']+=intval($hpcure);
                          }
                          break;
                     case 3: case 10:
                          output("`5La tua vista diventa più acuta, in terra trovi una gemma !!`n");
                          $session['user']['gems']+=1;
                          break;
                     case 4: case 5: case 6: case 7:
                          output("`5La tua vista diventa più acuta, in terra trovi 500 oro !!`n");
                          $session['user']['gold']+=500;
                          break;
                     case 8:
                          output("`5Dopo alcuni secondi ti senti male e cominci a vomitare. Sembra che la stanza stia girando.
                                   Perdi i sensi...`n");
                          output("`5Sei rimasto svenuto per alcune ore, quando ti svegli ti senti debole.
                                   `nHai perso 5 HP permanenti...`n");
                          if ($session['user']['maxhitpoints'] <= 10) {
                               output("`5`nGli dei oggi sono clementi con te. Hanno visto che la tua salute era già precaria e
                                        ti risparmiano questa punizione.");
                          } else {
                               $session['user']['maxhitpoints']-=5;
                               $session['user']['hitpoints']-=5;
                               if ($session['user']['hitpoints'] <= 10) $session['user']['hitpoints'] = 1;
                               debuglog("perde 5 HP permanenti nel test di Excalibur");
                          }
                          break;
                     case 9:
                          output("`5Dopo alcuni secondi ti senti male e cominci a vomitare. Sembra che la stanza stia girando.
                                   Perdi i sensi e non ti risvegli più...`n");
                          output("`5Sei rimasto vittima di un potentissimo veleno`n");
                          output("`3`n`nPerdi tutto il tuo oro.");
                          output("`3`nPuoi continuare a giocare domani");
                          debuglog("muore avvelenato perdendo ".$session['user']['gold']." oro nel test di Excalibur");
                          addnav("T?Terra delle Ombre","village.php");
                          $session['user']['alive']=false;
                          $session['user']['hitpoints']=0;
                          $session['user']['gold']=0;
                          $quest['navigazione']="";
                          break;
             }
             if ($ampollaroom==9) break;
             $quest['potion']=0;
             $playerAsseX = $quest['playerAsseX'];
             $playerAsseY = $quest['playerAsseY'];
             $dungeon = $quest['dungeon'];
             $quest['navigazione']=$dungeon[$playerAsseX][$playerAsseY];
             generaNavigazione($dungeon,$playerAsseX,$playerAsseY);
             $quest['playerAsseX']=$playerAsseX;
             $quest['playerAsseY']=$playerAsseY;
             $session['thequest'] = $quest;
             break;
         case "prayroom":
             page_header("Il Tempio");
             if ($session['user']['turns'] < 1 || $quest['pray']==0) {
                 output("`5Sei troppo esausto per metterti a pregare in onore di `6Sgrios`5.");
             } else{
                 output("`5Ti avvicini all'altare, ti inginocchi e cominci a pregare...`n");
                 output("`5Senti la tua fede aumentare e ti raccogli maggiormente, invocando interiormente `6Sgrios`5`n");
                 $pray = e_rand(1, 3) * $session['user']['carriera'];
                 $session['user']['punti_carriera'] += intval(2 + $pray);
                 $session['user']['punti_generati'] += intval(2 + $pray);
                 $session['user']['turns'] -= 1;
                 $session['user']['experience']+=70;
                 $prayroom = e_rand(0,5);
                 $quest['pray']=0;
                 switch ($prayroom) {
                     case 0:
                     case 1:
                          output("`6Sgrios`5 ha ascoltato le tue suppliche!!`n");
                          output("`5Le tue ferite iniziano a curarsi da sole, ti senti completamente ristorato!!`n");
                          $session['user']['hitpoints']=$session['user']['maxhitpoints'];
                          break;
                     case 2:
                     case 3:
                          output("`6Sgrios`5 ha ascoltato le tue suppliche!!`n");
                          $exp = intval(e_rand($session['user']['experience'] * 0.05,$session['user']['experience'] * 0.1));
                          output("Ottieni `6$exp`5 Punti Esperienza...");
                          debuglog("guadagna $exp exp nel Tempio del test di Excalibur");
                          $session['user']['experience'] += $exp;
                          break;
                     case 4:
                          output("`6Sgrios`5 ha ascoltato le tue suppliche!!`n");
                          $hpgain = intval(floor($session['user']['maxhitpoints'] *0.075));
                          if ($hpgain == 0) $hpgain=1;
                          if ($hpgain > 5) $hpgain=5;
                          $session['user']['maxhitpoints'] += $hpgain;
                          output("Ottieni `6$hpgain `^Hitpoints Extra...");
                          debuglog("guadagna $hpgain HP permanenti nel Tempio del test di Excalibur");
                          break;
                     case 5:
                          output("`6Sgrios`5 si è accorto della tua malafede, ed è infuriato con te!!`n");
                          output("`5Ti ritrovi solo con la metà del tuo oro..`n");
                          $goldloss = intval(floor($session['user']['gold'] / 2));
                          $session['user']['gold']=$goldloss;
                          debuglog("perde $goldloss oro nel Tempio del test di Excalibur");
                          break;
                 }
             }
             $playerAsseX = $quest['playerAsseX'];
             $playerAsseY = $quest['playerAsseY'];
             $dungeon = $quest['dungeon'];
             $quest['navigazione']=$dungeon[$playerAsseX][$playerAsseY];
             generaNavigazione($dungeon,$playerAsseX,$playerAsseY);
             $quest['playerAsseX']=$playerAsseX;
             $quest['playerAsseY']=$playerAsseY;
             $session['thequest'] = $quest;
             break;
        case "mageogrewin":
             page_header("La Stanza del Mago Ogre");
             output("`5Guardi il cadavere del `4Mago Ogre`5, poi osservi meglio la stanza ma non trovi niente di interessante.`n`n");
             $quest['mageogre']=0;
             $playerAsseX = $quest['playerAsseX'];
             $playerAsseY = $quest['playerAsseY'];
             $dungeon = $quest['dungeon'];
             $quest['navigazione']=$dungeon[$playerAsseX][$playerAsseY];
             generaNavigazione($dungeon,$playerAsseX,$playerAsseY);
             $quest['playerAsseX']=$playerAsseX;
             $quest['playerAsseY']=$playerAsseY;
             $session['thequest'] = $quest;
             break;
         case "trono":
             page_header("La Sala del Trono");
             output("`5Ti avvicini alla scalinata che porta al trono e la sali...`n`n");
             $trono = e_rand(0,5);
             $quest['trono'] = 0;
             switch ($trono) {
                     case 0:
                            output("`5Purtroppo l'odore che sentivi nella stanza non era di rose ma di un potentissimo veleno
                                     che viene emanato magicamente dal trono...");
                            output("`5`nQuando te ne rendi conto è già troppo tardi, ti sei avvicinato talmente tanto che l'aria
                                     nei tuoi polmoni è stata sostituita completamente dal gas letale...");
                            output("`3`n`nPerdi tutto il tuo oro.");
                            output("`3`nPuoi continuare a giocare domani");
                            debuglog("muore avvelenato nella sala del trono del test di Excalibur perdendo {$session[user][gold]} oro");
                            addnav("T?Terra delle Ombre","village.php");
                            $session['user']['alive']=false;
                            $session['user']['hitpoints']=0;
                            $session['user']['gold']=0;
                            $quest['navigazione']="";
                            break;
                     case 1:
                     case 2:
                            output("`5Noti che il cuscino del trono non è fissato e lo sposti...`n");
                            output("`5La fortuna ti sorride !! Trovi ben 1000 oro !!");
                            $session['user']['gold'] += 1000;
                            break;
                     case 3:
                     case 4:
                            output("`5Noti che il cuscino del trono non è fissato e lo sposti...`n");
                            output("`5La fortuna ti sorride !! Trovi 1 gemma !!");
                            $session['user']['gems'] += 1;
                            break;
                     case 5:
                            output("`5Noti che il cuscino del trono non è fissato e lo sposti...`n");
                            output("`5La fortuna ti sorride !! Trovi ben 1000 oro e 1 gemma !!");
                            $session['user']['gold'] += 1000;
                            $session['user']['gems'] += 1;
                            break;
             }
             if ($trono==0) break;
             $playerAsseX = $quest['playerAsseX'];
             $playerAsseY = $quest['playerAsseY'];
             $dungeon = $quest['dungeon'];
             $quest['navigazione']=$dungeon[$playerAsseX][$playerAsseY];
             generaNavigazione($dungeon,$playerAsseX,$playerAsseY);
             $quest['playerAsseX']=$playerAsseX;
             $quest['playerAsseY']=$playerAsseY;
             $session['thequest'] = $quest;
             break;
         case "specchio":
             page_header("Lo Specchio");
             output("`5Ti specchi e...`n`n");
             $glass = e_rand(0,3);
             switch ($glass) {
                    case 0:
                         output("Vedi una scena in cui vieni ucciso da una non meglio definita creatura,
                                  dallo spavento un pò dell'oro che avevi in tasca ti cade e scivola in una fessura nel pavimento...");
                         if ($session['user']['gold']>100) {$session['user']['gold']-=100;}
                         break;
                    case 1:
                         output("Vedi una scena in cui vieni premiato da un Re, poi guardi per terra e trovi 100 oro...");
                         $session['user']['gold']+=100;
                         break;
                    case 2:
                         output("Vedi una scena in cui vieni catturato e torturato da un grosso `4Orco`5,
                                  il dolore sembra così reale che perdi un punto fascino...");
                          if ($session['user']['charm']>0) $session['user']['charm']--;
                         break;
                    case 3:
                         output("Vedi una scena in cui passi alcuni momenti felici
                                  con un".($session['user']['sex']?" bel giovane":"a bella giovane").", guadagni un punto fascino...");
                         $session['user']['charm']++;
                         break;
             }
             $playerAsseX = $quest['playerAsseX'];
             $playerAsseY = $quest['playerAsseY'];
             $dungeon = $quest['dungeon'];
             $quest['navigazione']=$dungeon[$playerAsseX][$playerAsseY];
             generaNavigazione($dungeon,$playerAsseX,$playerAsseY);
             $quest['playerAsseX']=$playerAsseX;
             $quest['playerAsseY']=$playerAsseY;
             $session['thequest'] = $quest;
             break;
        case "book":
             page_header("La Biblioteca");
             if ($session['user']['turns'] < 1) {
                 output("`5Sei troppo esausto per metterti a leggere.");
             } else{
                 output("`5Vista la quantità di libri, ne prendi uno e lo cominci a leggere...`n`n");
                 $book = e_rand(1,11);
                 $quest['book'] = 0;
                 if ($session['user']['specialty'] == $book) {
                    output("`5Sei fortunato !! Trovi un libro che descrive la tua specialità !!`n");
                    increment_specialty();
                 } else {
                    output("`5Passi qualche ora a leggere ma non trovi nulla di interessante...`n");
                 }
                 output("`5`nRiponi il libro sulla pila.");
             }
             $playerAsseX = $quest['playerAsseX'];
             $playerAsseY = $quest['playerAsseY'];
             $dungeon = $quest['dungeon'];
             $quest['navigazione']=$dungeon[$playerAsseX][$playerAsseY];
             generaNavigazione($dungeon,$playerAsseX,$playerAsseY);
             $quest['playerAsseX']=$playerAsseX;
             $quest['playerAsseY']=$playerAsseY;
             $session['thequest'] = $quest;
             break;
        case "fine":
             page_header("Epilogo");
             $exp=intval($session['user']['experience']*0.15);
             output("`5La bocca ricomincia a profferire parola.`n`n");
             output("`3Nobile avventuriero. Sei veramente degno del nome che porti.
                      `nLe tue gesta verranno ricordate negli annali di questo regno e la tua gloria sarà grande.
                      `nQuesta è la tua ricompensa. Buona Fortuna nelle tue prossime avventure.`n`n");
             output("`5Dopo aver finito queste parole, la bocca svanisce nel nulla.`n");
             output("`5Per terra trovi uno scrigno contenente una fortuna. 3.000 pezzi d'oro e 3 gemme !!`n");
             output("`5Inoltre hai guadagnato $exp Esperienza.`n");
             addnews("`%".$session['user']['name']."`5 ha completato il test di `6Excalibur !!");
             $session['user']['experience'] += $exp;
             $session['user']['gold'] += 3000;
             $session['user']['gems'] += 3;
             $session['thequest'] = null;
             $session['user']['quest'] ++;
             addnav("V?Torna al Villaggio","village.php");
             break;
}
page_footer();
?>