<?php
require_once("common.php");
require_once("common2.php");
//Excalibur: Cancellazione sbeffeggi nelle tenute più vecchi di 2 giorni
$sbeffdate = date("Y-m-d H:i:s",strtotime(date("r")."-2 days"));
$sbefftext = "::vi sbeffeggia dicendo%";
$sqlsbeff = "DELETE FROM commentary
             WHERE comment LIKE '".$sbefftext."'
             AND postdate < '".$sbeffdate."'";
db_query($sqlsbeff);
//Excalibur: Fine cancellazione sbeffeggi
if ($session['user']['consono'] == 0) redirect ("attesa.php");
if ($session['user']['lasthit'] != "0000-00-00 00:00:00"){
/*$sql="UPDATE accounts
      SET loggedin = 0
      WHERE laston<'".date("Y-m-d H:i:s",strtotime(date("r")."-".getsetting("LOGINTIMEOUT",900)." seconds"))."'
      AND acctid <> ".$session['user']['acctid'];
db_query($sql);
*/
/**
* SETTINGS **
*/
$turnsperday = getsetting("turns", 10);
$casinoturns = getsetting("casinoturns",5);
$maxinterest = ((float)getsetting("maxinterest", 10) / 100) + 1; //1.1;
$mininterest = ((float)getsetting("mininterest", 1) / 100) + 1; //1.1;


// $mininterest = 1.01;
$dailypvpfights = getsetting("pvpday", 3);

if ($_GET['resurrection'] == "true") {
    debuglog("resuscita grazie a Ramius");
    $resline = "&resurrection=true";
} elseif ($_GET['resurrection'] == "tunnel") {
    debuglog("resuscita percorrendo il tunnel");
    $_GET['resurrection'] = "true";
    $resline = "&resurrection=true";
} else if ($_GET['resurrection'] == "egg") {
    debuglog("resuscita utilizzando l'Uovo d'Oro");
    $session['user']['risorto'] = 0;
    $resline = "&resurrection=egg";
} else {
    $resline = "";
}
/**
* End Settings **
*/
//Festa, Sook, 1° parte (impostazione data)
$festa=0;
$data = date("m-d");
//$datafesta = "03-13";
if ($data==getsetting("festa","no") /*OR $data==$datafesta*/) $festa=1;
$dataieri = date("m-d", mktime(0,0,0,date("m"),date("d")-1));
if ($dataieri==getsetting("festa","no")) savesetting("festa","no");
//if ($data==$datafesta) output("<big>`n`n`c`b`^FESTA DELLA CARNE`b`c`n`n`0</big>",true);
//Fine festa
if (count($session['user']['dragonpoints']) < $session['user']['dragonkills'] && $_GET['dk'] != "") {
    array_push($session['user']['dragonpoints'], $_GET[dk]);
    $session['user']['defence'] = ($session['user']['bonusdefence'] + 1);
    $session['user']['attack'] = ($session['user']['bonusattack'] + 1);
    $session['user']['turns'] = $session['user']['bonusfight'];
    while (list($key, $val) = each($session['user']['dragonpoints'])) {
        if ($val == "at") {
            $session['user']['attack']++;
        }
        if ($val == "ff") {
            $session['user']['turns']++;
        }
        if ($val == "de") {
            $session['user']['defence']++;
        }
    }
}
// remmata di prova per eliminare il problema al drago
switch ($_GET['dk']) {
    case "hp":
        $session['user']['maxhitpoints'] += 5;
        break;
    case "at":
        //        $session['user']['attack']++;
        break;
    case "de":
        //        $session['user']['defence']++;
        break;
}
if (count($session['user']['dragonpoints']) < $session['user']['dragonkills'] && $_GET['dk'] != "ignore") {
    reset($session['user']['dragonpoints']);
    while (list($key, $val) = each($session['user']['dragonpoints'])) {
        if ($val == "ff") {
            $conteggioff ++;
        }
    }
    for ($x = 0; $x < ($session['user']['reincarna']+1);$x++) {
        if ($x < 6){
           $conteggioff1 += $x * 2;
        } elseif ($x == 6) {
           $conteggioff1 += 5;
        } else {
           $conteggioff1 += 10;
        }
    }
    //if ($conteggioff1 > 30) $conteggioff1 = 30;
    $conteggioff += $conteggioff1;
    $conteggioff += $session['user']['bonusfight'];
    //conto dei turni dell'oggetto in mano (da sottrarre a quelli totali)
    $sqlo = "SELECT * FROM oggetti WHERE id_oggetti = '{$session['user']['oggetto']}'";
    $resulto = db_query($sqlo) or die(db_error(LINK));
    $rowo = db_fetch_assoc($resulto);
    if ($rowo['usuramagica']!=0 AND $rowo['usuramagicamax']!=0) $conteggioff -= $rowo['turns_help'];
    if ($session['user']['superuser'] > 0){
        print("conteggio turni foresti supplementari: ".$conteggioff);
    }
    page_header("Dragon Points");
    addnav("Hitpoints Max + 5", "newday.php?dk=hp$resline");
    if ($conteggioff < (($session['user']['reincarna']+1)*15)){
        addnav("Turni Foresta + 1", "newday.php?dk=ff$resline");
    }else{
        addnav("Max Turni Foresta Raggiunti", "");
    }
    addnav("Attacco + 1", "newday.php?dk=at$resline");
    addnav("Difesa + 1", "newday.php?dk=de$resline");
    // addnav("Ignore (Dragon Points are bugged atm)","newday.php?dk=ignore$resline");
    output("`@Hai `^" . ($session['user']['dragonkills'] - count($session['user']['dragonpoints'])) . "`@ Punti Drago (DK) inutilizzati. Come desideri spenderli?`n`n");
    output("Guadagni un DK ogni volta che uccidi il drago. Gli avanzamenti ottenuti spendendo DK sono permanenti!");
} else if ((int)$session['user']['race'] == 0) {
    page_header("Un po' della tua storia");
    if ($_GET['setrace'] != "") {
        $session['user']['race'] = (int)($_GET['setrace']);
        switch ($_GET['setrace']) {
            case "1":
                $session['user']['attack']++;
                output("`2Essendo un troll, ed essendoti sempre difeso da solo, i metodi di combattimento non ti sono ignoti.`n`^Guadagni un punto di attacco!");
                break;
            case "2":
                $session['user']['defence']++;
                output("`^Essendo un elfo, sei consapevole di tutto quello che ti circonda in ogni momento, poche cose riescono a prenderti di sorpresa.`nGuadagni un punto di difesa!");
                break;
            case "3":
                output("`&Essendo umano, la tua taglia e la tua forza ti danno la capacità di impugnare armi senza sforzo, stancandoti molto più lentamente rispetto alle altre razze.`n`^Guadagni un combattimento supplementare nella foresta ogni giorno!");
                break;
            case "4":
                output("`#Essendo un nano, sei più abile nell'identificare il valore di alcuni oggetti.`n`^Guadagni denaro extra dai combattimenti nella foresta!");
                break;
            case "5";
                output("`3Essendo un druido, il mondo mistico non ha segreti per te.`n `3Guadagni un punto di utilizzo nei Poteri Mistici!");
                $session['user']['magicuses']+=1;
                break;
            case "6":
                $session['user']['defence']++;
                $session['user']['attack']++;
                output("`6Essendo un goblin, il mondo è un luogo pericoloso per quelli della tua razza. `n Sei fiero e possente, `^guadagni un punto attacco e un punto difesa ma hai meno combattimenti foresta!");
                break;
            case "7":
                $session['user']['defence']++;
                $session['user']['attack']+=2;
                output(" `4Essendo un orco, conduci una vita solitaria e da nomade, e sei dotato delle abilità di un guerriero. Guadagni punti attacco e difesa ma hai meno combattimenti foresta! `n");
                break;
            case "8":
                $session['user']['defence']--;
                $session['user']['darkartuses']+=2;
                output(" `4Essendo un vampiro, cammini tra i vivi, e sei particolarmente dotato nelle Arti Oscure, tramandate dai tuoi avi! Ma perdi un punto di difesa per la fragilità del tuo corpo.`n");
                break;
            case "9":
                $session['user']['defence']--;
                $session['user']['attack']--;
                $session['user']['darkartuses']+=2;
                output("`5Poichè sei un `%Lich`5, sei particolarmente debole e perdi un punto di attacco e un punto di difesa, ma guadagni 2 punti nella Arti Oscure oltre a 50 favori con `\$Ramius`5, tuo amico da sempre`n");
                break;
            case "10":
                $session['user']['attack']--;
                $session['user']['defence']++;
                $session['user']['weatheruses']+=2;
                output("`3Essendo una `&Fanciulla delle Nevi`3 sei di costituzione debole a causa della tua natura semi-eterea, ma possedendo il dominio degli elementi compensi l'attacco con la difesa.`nGuadagni 2 punti di utilizzo in `&Clima`3, un punto di difesa, ma perdi un punto di attacco.`n");
                break;
            case "11":
                $session['user']['attack']++;
                $session['user']['defence']--;
                $session['user']['tacticuses']+=2;
                output("`2Essendo un `%Oni`2 sei poco resistente agli attacchi dei tuoi nemici, ma la tua natura demoniaca ti ha dotato di una notevole forza. Inoltre, prima di condividere il tuo corpo con un demone, ricordi di essere stato un valente condottiero. Perdi un punto di difesa, ma guadagni un punto di attacco e 2 punti di utilizzo in Tattica!`n");
                break;
            case "12":
                output("`2Essendo un satiro non hai particolare doti, ma la natura ti è amica e ti consente di fuggire dai tuoi avversari più facilmente.`nInoltre guadagni 1 punto di utilizzo in `@Natura`2!");
                $session['user']['natureuses']+=1;
                break;
            case "13":
                output("`3I `#Giganti della Tempesta `3sono conosciuti per la loro forza eccezionale, e la loro maestria nell'uso delle masse di roccia.`n");
                output("`3Guadagni un punto di attacco ed un potenziamento per tutti i tuoi combattimenti!`n");
                output("Inoltre, guadagni un punto di utilizzo in `#Abilità della Roccia`3!!`n");
                $session['user']['attack']+=1;
                $session['user']['rockskinuses']+=1;
                break;
            case "14":
                output("`4I `\$Barbari `4sono conosciuti per la loro ferocia, e la loro maestria nell'uso delle armi.`n");
                output("`4Guadagni due punti attacco, un punto difesa ed un potenziamento per tutti i tuoi combattimenti!`n");
                $session['user']['attack']+=2;
                $session['user']['defence']+=1;
                break;
            case "15":
                output("`5Le `%Amazzoni `5sono famose per la loro agilità e la loro maestria nell'uso dell'arco.`n");
                output("`5Guadagni un punto attacco ed un punto difesa ed un potenziamento per tutti i tuoi combattimenti!`n");
                output("Inoltre, essendo la tua razza molto rispettosa della natura, guadagni un punto di utilizzo in `@Natura`5!");
                $session['user']['natureuses']+=1;
                $session['user']['attack']+=1;
                $session['user']['defence']+=1;
                break;
            case "16":
                output("`3I `#Titani`3, difensori della fede di `6Sgrios`3, sono conosciuti per la loro maestosa imponenza fisica, che dà loro un naturale vantaggio nel combattimento.`n");
                output("`3Guadagni `^tre`3 punti attacco e `^due`3 punti difesa ed un potenziamento per tutti i tuoi combattimenti!`n");
                output("Inoltre, grazie al tuo imponente fisico, guadagni due punti di utilizzo in `^Muscoli`3!");
                $session['user']['muscleuses']+=2;
                $session['user']['attack']+=3;
                $session['user']['defence']+=2;
                break;
            case "17":
                output("`8I `\$Demoni`8, malefici collaboratori di `4Karnak`8, sono stati relegati da tempo nelle profondità delle viscere della terra, dove hanno sviluppato doti insospettate per  il loro fisico.`n");
                output("`8Guadagni `(due`8 punti attacco e `^due`3 punti difesa ed un potenziamento per tutti i tuoi combattimenti!`n");
                output("`8Inoltre, grazie alla lunga permanenza nel sottosuolo vicino agli esseri malefici che vi abitano, apprendi due punti di utilizzo in `\$Arti Oscure`8 e `\$Ramius`8 ti concede `(50`8 favori!`n");
                $session['user']['darkartuses']+=2;
                $session['user']['attack']+=2;
                $session['user']['defence']+=2;
                $session['user']['deathpower']+=50;
                break;
            case "18":
                output("`8I `%Centauri `8sono un popolo fiero e famoso per la loro indomita indole, che ha permesso loro di non essere mai stati soggiogati.`n");
                output("`8Guadagni `(un`8 punto difesa ed un potenziamento per tutti i tuoi combattimenti!`n");
                output("Inoltre, essendo la tua razza dedita alle arti fisiche, guadagni un punto di utilizzo in `^Muscoli`8!`n");
                $session['user']['muscleuses']+=1;
                $session['user']['defence']+=1;
                break;
            case "19":
                output("`6I `^Lincantropi `6erano una razza sulla via dell'estinzione, fino a quando un fiero appartenente alla loro razza, Luthien, li ha riportati ai fasti di un tempo.`n");
                output("`6Guadagni `^due`6 punti attacco e `^due`6 punti difesa ed un potenziamento per tutti i tuoi combattimenti!`n");
                output("Inoltre, essendo la tua razza parte del regno animale, guadagni un punto di utilizzo in `@Natura`6!`n");
                $session['user']['natureuses']+=1;
                $session['user']['attack']+=2;
                $session['user']['defence']+=2;
                break;
            case "20":
                output("`8I `(Minotauri`8, mitica razza discendente della stirpe di Tarmatack, figlio degenere nato dall'incrocio di un toro ed una donna, ha da sempre prediletto le arti fisiche.`n");
                output("`6Guadagni `^3`6 punti attacco e `^un`6 punto difesa ed un potenziamento per tutti i tuoi combattimenti!`n");
                output("Inoltre, essendo la tua razza particolarmente robusta, guadagni un punto di utilizzo in `^Muscoli`6!`n");
                $session['user']['muscleuses']+=1;
                $session['user']['attack']+=3;
                $session['user']['defence']+=1;
                break;
            case "21":
                output("`3I `#Cantastorie`3, una razza in via di estinzione, di cui tu sei uno dei rari esemplari, ha da sempre suonato una chitarra.`n");
                output("`6Guadagni `^1`6 punto attacco e `^1`6 punti difesa ed un potenziamento per tutti i tuoi combattimenti!`n");
                output("Inoltre, essendo la tua razza particolarmente sensibile al fascino delle arti, guadagni un punto di utilizzo in `@Canzoni del Bardo`6!`n");
                $session['user']['bardouses']+=1;
                $session['user']['attack']+=1;
                $session['user']['defence']+=1;
                break;
            case "22":
                output("`2Gli `@Eletti`2, i mitici abitanti di Eld, hanno da sempre avuto un rapporto privilegiato con i Draghi. La vicinanza alle Terre dei Draghi ha favorito lo studio e la conoscenza delle mitiche creature.`n");
                output("`2Guadagni `@`b2`b`2 punti attacco e `@`b3`b`2 punti difesa ed un potenziamento per tutti i tuoi combattimenti!`n");
                output("Inoltre, essendo la tua razza amica della razza dei draghi, guadagni un punto di utilizzo in `@Natura`2!`n");
                $session['user']['natureuses']+=2;
                $session['user']['attack']+=2;
                $session['user']['defence']+=3;
                break;
        }
        addnav("Continua", "newday.php?continue=1$resline");
    } else {
        output("Dove ricordi di essere cresciuto?`n`n");
        output("<a href='newday.php?setrace=1$resline'>Nelle paludi di Glukmoore</a> come `2troll`0, combattendo fin dal primo istante in cui sei uscito dal tuo uovo, ucidendo i tuoi fratelli ancora non schiusi, e pasteggiando con le loro ossa.`n`n", true);
        output("<a href='newday.php?setrace=2$resline'>Sugli alti alberi della</a> foresta di Glorfindal, in elaborate strutture `^Elfiche`0 di fragile aspetto che sembravano poter collassare sotto il minimo peso, eppure esistono da secoli.`n`n", true);
        output("<a href='newday.php?setrace=3$resline'>Nelle pianure nella città di Romar</a>, la città degli `&uomini`0; seguendo sempre tuo padre e guardando ogni sua mossa fino a quando non è andato a caccia del `@Drago Verde`0, e non ha mai fatto ritorno.`n`n", true);
        output("<a href='newday.php?setrace=4$resline'>Nelle profonde fortezze sotterranee di Qexelcrag</a>, casa del nobile e fiero popolo dei `#Nani`0 il cui desiderio di privacy e tesori non si sposa affatto con la loro scarsa altezza.`n`n", true);
        output("<a href='newday.php?setrace=5$resline'>Nascosto nelle segrete di Castel Excalibur dove vivono le Fate</a>, i `6Druidi `0che praticano e conoscono i Poteri Mistici meglio di qualunque altra razza del Mondo di Mezzo.`n`n",true);
        if ($session['user']['dragonkills']>1)  output("<a href='newday.php?setrace=12$resline'>Tra gli alberi della foresta</a>, nutrendoti di bacche, ghiande e funghi. e anche della carne in putrefazione delle creature che la abitano, attaccando quelle più deboli e affaticate, e fuggendo da quelle più forti.`n`n",true);
        if ($session['user']['dragonkills']>4)  output("<a href='newday.php?setrace=6$resline'>Nei Regni Oscuri</a>, tra gli ambiziosi amici `5Goblin`0. La tua gente è conosciuta come gli Elfi Oscuri, signori della guerra e della notte, finchè il fato o l'ambizione ti ha portato qui.`n`n",true);
        if ($session['user']['dragonkills']>6)  output("<a href='newday.php?setrace=7$resline'>Nelle foreste del Nord</a>, fra gli alberi e gli `#Orchi `0scuri. La tua genia sono gli Orchi, possenti nomadi delle montagne settentrionali. Il tuo girovagare ti ha condotto qui.`n`n",true);
        if ($session['user']['dragonkills']>8)  output("<a href='newday.php?setrace=8$resline'>All'interno delle Caverne Oscure</a>, tra i pipistrelli e l'umidità vivi come `2Vampiro`0. La tua genia sono i Vampiri, potenti non-morti delle caverne. La tua conoscenza delle Arti Oscure è molto sviluppata.`n`n",true);
        if ($session['user']['dragonkills']>14) output("<a href='newday.php?setrace=9$resline'>Nell'occidentale cittadina di Zantyk</a>. Mai dimenticherai il giorno in cui sei stato seppellito vivo e la `\$Morte`0 in persona ti ha offerto la possibilità di rifarti una `inon-vita`i come `%Lich`0.`n`n",true);
        if ($session['user']['dragonkills']>17 AND $session['user']['sex']==1) output("<a href='newday.php?setrace=10$resline'>Tra le valli innevate di WinterHeart</a>. In comunione con l'ambiente circostante uccideresti per difendere ciò che fin da piccola ogni `&Fanciulla delle Nevi`0 impara ad amare.`n`n",true);
        if ($session['user']['dragonkills']>17 AND $session['user']['sex']==0) output("<a href='newday.php?setrace=11$resline'>Nelle terre orientali di Keltaras</a>. Come tutti i maschi `4Oni`0 imparasti l'arte militare sin da piccolo. Il destino decise che un antico demone, forse un tuo avo, tormentasse il tuo giovane animo. Non potendo combatterlo decidesti di renderlo parte di te. Ora sei costretto a divere il tuo corpo con questo demone.`n`n",true);
        $dkdonazioni = $session['user']['dragonkills'] + min($session['user']['reincarna'],5);
        if (donazioni('gigante_tempesta')==true) {
            if (donazioni_usi('gigante_tempesta')!=0){
                output("<a href='newday.php?setrace=13$resline'>`3Oltre le montagne di Mardork</a>,`3 la città dei nani, la tua razza di `#Giganti della Tempesta `3vive nelle caverne.`n`n",true);
            }
        }
        if (donazioni('barbaro')==true AND $session['user']['sex']==0 AND ($session['user']['dragonkills']>4 OR $session['user']['reincarna'] > 0)) {
            if (donazioni_usi('barbaro')!=0){
                output("<a href='newday.php?setrace=14$resline'>`4Nelle lande desolate di Hodertoth</a>,`4 le piane desertiche oltre Maggoth, la tua razza di `\$Barbari `4si batte continuamente per la supremazia della regione.`n`n",true);
            }
        }
        if (donazioni('amazzone')==true AND $session['user']['sex']==1 AND ($session['user']['dragonkills']>4 OR $session['user']['reincarna'] > 0)) {
            if (donazioni_usi('amazzone')!=0){
                output("<a href='newday.php?setrace=15$resline'>`5Nei rigogliosi boschi di Landoriel</a>,`5 dove la razza delle `%Amazzoni `5 vive in simbiosi con la natura, cacciando con l'arco le creature che vivono nella zona.`n`n",true);
            }
        }
        if (donazioni('titano')==true AND $session['user']['dio']==1 AND ($session['user']['dragonkills']>14 OR $session['user']['reincarna'] > 0)) {
            if (donazioni_usi('titano')!=0){
                output("<a href='newday.php?setrace=16$resline'>`3Oltre il margine esterno del reame conosciuto</a>,`3 in prossimità del regno di `^Sgrios`3, la tua razza di `#Titani `3conduce la sua esistenza professando la parola di `6Sgrios`3 e scolpendo il proprio fisico nella palestra degli dei.`n`n",true);
            }
        }
        if (donazioni('demone')==true AND $session['user']['dio']==2 AND($session['user']['dragonkills']>14 OR $session['user']['reincarna'] > 0)) {
            if (donazioni_usi('demone')!=0){
                output("<a href='newday.php?setrace=17$resline'>`4Nelle profondità della terra</a>,`4 in prossimità del regno di `\$Karnak`4, la tua razza di `\$Demoni `4conduce la sua esistenza straziando le povere anime mortali che giungono sotto le loro grinfie. `\$Ramius`4 è un vostro alleato.`n`n",true);
            }
        }
        if (donazioni('centauro')==true) {
            if (donazioni_usi('centauro')!=0){
                output("<a href='newday.php?setrace=18$resline'>`8Nelle pianure erbose di Lonthariel</a>,`8 non lontano dai boschi delle Amazzoni, la tua razza di `(Centauri `8vive pascolando a contatto della natura e correndo libera da ogni giogo.`n`n",true);
            }
        }
        if (donazioni('licantropo')==true AND ($session['user']['dragonkills']>9 OR $session['user']['reincarna'] > 0)) {
            if (donazioni_usi('licantropo')!=0){
                output("<a href='newday.php?setrace=19$resline'>`6Sulle colline nei pressi del fiume Moonleador</a>,`6 la tua razza di `Licantropi `8vive aggredendo e terrorizzando i poveri allevatori dediti alla pastorizia, ma non disdegnando di cibarsi di qualche tenero agnellino.`n`n",true);
            }
        }
        if (donazioni('minotauro')==true AND ($session['user']['dragonkills']>9 OR $session['user']['reincarna'] > 0)) {
            if (donazioni_usi('minotauro')!=0){
                output("<a href='newday.php?setrace=20$resline'>`8Nei campi rigogliosi di Bullensach</a>,`8 dove il grano ha doti nutritive eccezionali, la tua razza di `(Minotauri `8vive non disdegnando banchetti umani a base di giovani fanciulle vergini e temprando il fisico in combattimento.`n`n",true);
            }
        }
        if (donazioni('chansonnier')==true) {
            if (donazioni_usi('chansonnier')!=0){
                output("<a href='newday.php?setrace=21$resline'>`6Nella mitica Eldorado</a>,`5 la città delle arti, la tua razza di `^Cantastorie `5ha da sempre raccontato le epiche gesta degli Eroi.`n`n",true);
            }
        }
        if (donazioni('eletto')==true AND $session['user']['dio']==3 AND ($session['user']['dragonkills']>14 OR $session['user']['reincarna'] > 0)) {
            if (donazioni_usi('eletto')!=0){
                output("<a href='newday.php?setrace=22$resline'>`2Nelle mitica `@Eld`2</a>,`2 non lontano dalle terre dei draghi, la tua razza di `@Eletti `2si è sempre distinta per le proprie capacità di controllare i draghi. La `@Natura`2 è vostra alleata.`n`n",true);
            }
        }
        addnav("Scegli la tua Razza");
        addnav("`2Troll`0", "newday.php?setrace=1$resline");
        addnav("`^Elfo`0", "newday.php?setrace=2$resline");
        addnav("`&Umano`0", "newday.php?setrace=3$resline");
        addnav("`#Nano`0", "newday.php?setrace=4$resline");
        addnav("`3Druido`0","newday.php?setrace=5$resline");
        if ($session['user']['dragonkills']>1)  addnav("`2Satiro`0","newday.php?setrace=12$resline");
        if ($session['user']['dragonkills']>4)  addnav("`6Goblin`0","newday.php?setrace=6$resline");
        if ($session['user']['dragonkills']>6)  addnav("`2Orco`0","newday.php?setrace=7$resline");
        if ($session['user']['dragonkills']>8)  addnav("`4Vampiro`0","newday.php?setrace=8$resline");
        if ($session['user']['dragonkills']>14)  addnav("`5Lich`0","newday.php?setrace=9$resline");
        if ($session['user']['dragonkills']>17 AND $session['user']['sex']==1)  addnav("`&Fanciulla delle Nevi`0","newday.php?setrace=10$resline");
        if ($session['user']['dragonkills']>17 AND $session['user']['sex']==0)  addnav("`4Oni`0","newday.php?setrace=11$resline");
        if (donazioni('gigante_tempesta')==true){
            if (donazioni_usi('gigante_tempesta')!=0){
                addnav("`3Gigante della Tempesta`0","newday.php?setrace=13$resline");
            }
        }
        if (donazioni('barbaro')==true AND $session['user']['sex']==0 AND ($session['user']['dragonkills']>4 OR $session['user']['reincarna'] > 0)) {
            if (donazioni_usi('barbaro')!=0){
                addnav("`4Barbaro`0","newday.php?setrace=14$resline");
            }
        }
        if (donazioni('amazzone')==true AND $session['user']['sex']==1 AND ($session['user']['dragonkills']>4 OR $session['user']['reincarna'] > 0)) {
            if (donazioni_usi('amazzone')!=0){
                addnav("`5Amazzone`0","newday.php?setrace=15$resline");
            }
        }
        if (donazioni('titano')==true AND $session['user']['dio']==1 AND ($session['user']['dragonkills']>14 OR $session['user']['reincarna'] > 0)){
            if (donazioni_usi('titano')!=0){
                addnav("`#Titano`0","newday.php?setrace=16$resline");
            }
        }
        if (donazioni('demone')==true AND $session['user']['dio']==2 AND ($session['user']['dragonkills']>14 OR $session['user']['reincarna'] > 0)){
            if (donazioni_usi('demone')!=0){
                addnav("`\$Demone`0","newday.php?setrace=17$resline");
            }
        }
        if (donazioni('centauro')==true){
            if (donazioni_usi('centauro')!=0){
                addnav("`(Centauro`0","newday.php?setrace=18$resline");
            }
        }
        if (donazioni('licantropo')==true AND ($session['user']['dragonkills']>9 OR $session['user']['reincarna'] > 0)){
            if (donazioni_usi('licantropo')!=0){
                addnav("`^Licantropo`0","newday.php?setrace=19$resline");
            }
        }
        if (donazioni('minotauro')==true AND ($session['user']['dragonkills']>9 OR $session['user']['reincarna'] > 0)){
            if (donazioni_usi('minotauro')!=0){
                addnav("`(Minotauro`0","newday.php?setrace=20$resline");
            }
        }
        if (donazioni('chansonnier')==true){
            if (donazioni_usi('chansonnier')!=0){
                addnav("`6Cantastorie`0","newday.php?setrace=21$resline");
            }
        }
        if (donazioni('eletto')==true AND $session['user']['dio']==3 AND ($session['user']['dragonkills']>14 OR $session['user']['reincarna'] > 0)){
            if (donazioni_usi('eletto')!=0){
                addnav("`@Eletto`0","newday.php?setrace=22$resline");
            }
        }
        addnav("", "newday.php?setrace=1$resline");
        addnav("", "newday.php?setrace=2$resline");
        addnav("", "newday.php?setrace=3$resline");
        addnav("", "newday.php?setrace=4$resline");
        addnav("", "newday.php?setrace=5$resline");
        if ($session['user']['dragonkills']>1)  addnav("", "newday.php?setrace=12$resline");
        if ($session['user']['dragonkills']>4)  addnav("", "newday.php?setrace=6$resline");
        if ($session['user']['dragonkills']>6)  addnav("", "newday.php?setrace=7$resline");
        if ($session['user']['dragonkills']>8)  addnav("", "newday.php?setrace=8$resline");
        if ($session['user']['dragonkills']>14)  addnav("", "newday.php?setrace=9$resline");
        if ($session['user']['dragonkills']>17 AND $session['user']['sex']==1)  addnav("", "newday.php?setrace=10$resline");
        if ($session['user']['dragonkills']>17 AND $session['user']['sex']==0)  addnav("", "newday.php?setrace=11$resline");
        if (donazioni('gigante_tempesta')==true) {
            if (donazioni_usi('gigante_tempesta')!=0){
                addnav("","newday.php?setrace=13$resline");
            }
        }
        if (donazioni('barbaro')==true AND $session['user']['sex']==0 AND ($session['user']['dragonkills']>4 OR $session['user']['reincarna'] > 0)) {
            if (donazioni_usi('barbaro')!=0){
                addnav("","newday.php?setrace=14$resline");
            }
        }
        if (donazioni('amazzone')==true AND $session['user']['sex']==1 AND ($session['user']['dragonkills']>4 OR $session['user']['reincarna'] > 0)) {
            if (donazioni_usi('amazzone')!=0){
                addnav("","newday.php?setrace=15$resline");
            }
        }
        if (donazioni('titano')==true AND $session['user']['dio']==1 AND ($session['user']['dragonkills']>14 OR $session['user']['reincarna'] > 0)) {
            if (donazioni_usi('titano')!=0){
                addnav("","newday.php?setrace=16$resline");
            }
        }
        if (donazioni('demone')==true AND $session['user']['dio']==2 AND ($session['user']['dragonkills']>14 OR $session['user']['reincarna'] > 0)) {
            if (donazioni_usi('demone')!=0){
                addnav("","newday.php?setrace=17$resline");
            }
        }
        if (donazioni('centauro')==true) {
            if (donazioni_usi('centauro')!=0){
                addnav("","newday.php?setrace=18$resline");
            }
        }
        if (donazioni('licantropo')==true AND ($session['user']['dragonkills']>9 OR $session['user']['reincarna'] > 0)) {
            if (donazioni_usi('licantropo')!=0){
                addnav("","newday.php?setrace=19$resline");
            }
        }
        if (donazioni('minotauro')==true AND ($session['user']['dragonkills']>9 OR $session['user']['reincarna'] > 0)) {
            if (donazioni_usi('minotauro')!=0){
                addnav("","newday.php?setrace=20$resline");
            }
        }
        if (donazioni('chansonnier')==true) {
            if (donazioni_usi('chansonnier')!=0){
                addnav("","newday.php?setrace=21$resline");
            }
        }
        if (donazioni('eletto')==true AND $session['user']['dio']==3 AND ($session['user']['dragonkills']>14 OR $session['user']['reincarna'] > 0)) {
            if (donazioni_usi('eletto')!=0){
                addnav("","newday.php?setrace=22$resline");
            }
        }
    }
} else if ((int)$session['user']['specialty'] == 0) {
    if ($_GET['setspecialty'] === null) {
        addnav("", "newday.php?setspecialty=1$resline");
        addnav("", "newday.php?setspecialty=2$resline");
        addnav("", "newday.php?setspecialty=3$resline");
        addnav("", "newday.php?setspecialty=4$resline");
        addnav("", "newday.php?setspecialty=5$resline");
        addnav("", "newday.php?setspecialty=6$resline");
        addnav("", "newday.php?setspecialty=7$resline");
        addnav("", "newday.php?setspecialty=8$resline");
        addnav("", "newday.php?setspecialty=9$resline");
        addnav("", "newday.php?setspecialty=10$resline");
        addnav("", "newday.php?setspecialty=11$resline");
        if (donazioni('elementale')==true AND ($session['user']['dragonkills']>3 OR $session['user']['reincarna'] > 0)) {
            if (donazioni_usi('elementale')!=0){
                addnav("", "newday.php?setspecialty=12$resline");
            }
        }
        if (donazioni('barbarian')==true AND ($session['user']['dragonkills']>4 OR $session['user']['reincarna'] > 0)) {
            if (donazioni_usi('barbarian')!=0){
                addnav("", "newday.php?setspecialty=13$resline");
            }
        }
        if (donazioni('bardo')==true AND ($session['user']['dragonkills']>7 OR $session['user']['reincarna'] > 0)) {
            if (donazioni_usi('bardo')!=0){
                addnav("", "newday.php?setspecialty=14$resline");
            }
        }
        page_header("Un po' della tua storia");
        output("Ricordi che da bambino, crescendo:`n`n");
        output("<a href='newday.php?setspecialty=1$resline'>Uccidevi molte creature della foresta (`\$Arti Oscure`0)</a>`n", true);
        output("<a href='newday.php?setspecialty=2$resline'>Sguazzavi nelle forze mistiche (`%Poteri Mistici`0)</a>`n", true);
        output("<a href='newday.php?setspecialty=3$resline'>Rubavi ai ricchi e davi a te stesso (`^Furto`0)</a>`n", true);
        output("<a href='newday.php?setspecialty=4$resline'>Hai sempre maneggiato le armi e le sai usare sempre al meglio (`#Militare`0)</a>`n", true);
        output("<a href='newday.php?setspecialty=5$resline'>Hai sempre affascinato le persone di sesso opposto (`\$Seduzione`0)</a>`n",true);
        if ($session['user']['dragonkills']>1){
            output("<a href='newday.php?setspecialty=6$resline'>Scalavi gli alberi e ti muovevi come un animale(`^Tattica`0)</a>`n",true);
        }
        if ($session['user']['dragonkills']>3){
            output("<a href='newday.php?setspecialty=7$resline'>Scalavi le montagne, e le pietre non avevano segreti per te(`@Abilità della Roccia`0)</a>`n",true);
        }
        if ($session['user']['dragonkills']>5){
            output("<a href='newday.php?setspecialty=8$resline'>Ti immergevi nella lettura di libri e ti perdevi per ore(`#Retorica`0)</a>`n",true);
        }
        if ($session['user']['dragonkills']>7){
            output("<a href='newday.php?setspecialty=9$resline'>Ti allenavi per ore correndo e sollevando pesi(`%Muscoli`0)</a>`n",true);
        }
        if ($session['user']['dragonkills']>9){
            output("<a href='newday.php?setspecialty=10$resline'>Davi da mangiare agli uccellini e camminavi a fianco dei cervi nella foresta(`!Natura`0)</a>`n",true);
        }
        if ($session['user']['dragonkills']>11){
            output("<a href='newday.php?setspecialty=11$resline'>Osservavi con interessi i fenomeni atmosferici(`&Clima`0)</a>`n",true);
        }
        if (donazioni('elementale')==true AND ($session['user']['dragonkills']>3 OR $session['user']['reincarna'] > 0)) {
            if (donazioni_usi('elementale')!=0){
                output("<a href='newday.php?setspecialty=12$resline'>Giocavi con i differenti elementi sulla terra(`^Elementalista`0)</a>`n",true);
            }
        }
        if (donazioni('barbarian')==true AND ($session['user']['dragonkills']>4 OR $session['user']['reincarna'] > 0)) {
            if (donazioni_usi('barbarian')!=0){
                output("<a href='newday.php?setspecialty=13$resline'>Avevi frequenti scatti di collera ed un temperamento violento(`6Barbaro`0)</a>`n",true);
            }
        }
        if (donazioni('bardo')==true AND ($session['user']['dragonkills']>7 OR $session['user']['reincarna'] > 0)) {
            if (donazioni_usi('bardo')!=0){
                output("<a href='newday.php?setspecialty=14$resline'>Raccontavi al mondo intero quale grande artista tu fossi(`5Bardo`0)</a>`n",true);
            }
        }
        addnav("`\$Arti Oscure", "newday.php?setspecialty=1$resline");
        addnav("`%Poteri Mistici", "newday.php?setspecialty=2$resline");
        addnav("`^Furto", "newday.php?setspecialty=3$resline");
        addnav("`#Militare", "newday.php?setspecialty=4$resline");
        addnav("`\$Seduzione","newday.php?setspecialty=5$resline");
        if ($session['user']['dragonkills']>1){
            addnav("`^Tattica","newday.php?setspecialty=6$resline");
        }
        if ($session['user']['dragonkills']>3){
            addnav("`@Abilità della Roccia","newday.php?setspecialty=7$resline");
        }
        if ($session['user']['dragonkills']>5){
            addnav("`#Retorica","newday.php?setspecialty=8$resline");
        }
        if ($session['user']['dragonkills']>7){
            addnav("`%Muscoli","newday.php?setspecialty=9$resline");
        }
        if ($session['user']['dragonkills']>9){
            addnav("`!Natura","newday.php?setspecialty=10$resline");
        }
        if ($session['user']['dragonkills']>11){
            addnav("`&Clima","newday.php?setspecialty=11$resline");
        }
        if (donazioni('elementale')==true AND ($session['user']['dragonkills']>3 OR $session['user']['reincarna'] > 0)){
            if (donazioni_usi('elementale')!=0){
                addnav("`^Elementalista`0","newday.php?setspecialty=12$resline");
            }
        }
        if (donazioni('barbarian')==true AND ($session['user']['dragonkills']>4 OR $session['user']['reincarna'] > 0)){
            if (donazioni_usi('barbarian')!=0){
                addnav("`6Barbaro`0","newday.php?setspecialty=13$resline");
            }
        }
        if (donazioni('bardo')==true AND ($session['user']['dragonkills']>7 OR $session['user']['reincarna'] > 0)){
            if (donazioni_usi('bardo')!=0){
                addnav("`5Bardo`0","newday.php?setspecialty=14$resline");
            }
        }
    } else {
        addnav("Continua", "newday.php?continue=1$resline");
        switch ($_GET['setspecialty']) {
            case 1:
                page_header("Arti Oscure");
                output("`5Ricordi che nell'infanzia uccidevi numerose piccole creature della foresta, ripetendo che ");
                output("stavano tramando contro di te. I tuoi genitori, preoccupati di vederti uccidere tali creature ");
                output("a mani nude, ti comprarono il tuo primo bastone appuntito. Fu solo negli anni dell'adolescenza che ");
                output("iniziasti ad eseguire rituali oscuri con le creature, scomparendo nella foresta per giorni, ");
                output("senza che nessuno sapesse da dove provenivano quei suoni.");
                break;
            case 2:
                page_header("Poteri Mistici");
                output("`3Ricordi che da bambino sapevi che c'era dell'altro oltre il mondo fisico, e ciò che ");
                output("potevi toccare con le mani. Comprendesti che la tua stessa mente, con l'allenamento, poteva diventare ");
                output("un'arma. Col tempo, iniziasti a controllare i pensieri di piccole creature, ordinando loro ");
                output("di piegarsi ai tuoi voleri, ed iniziasti anche ad attingere alla forza mistica nota come mana, ");
                output("che poteva essere modellata in numerose forme elementali, fuoco, acqua, ghiaccio, vento, ed anche ");
                output("usata come arma contro i tuoi nemici.");
                break;
            case 3:
                page_header("Furto");
                output("`6Ricordi di aver scoperto crescendo che un urto casuale in una stanza affollata poteva farti guadagnare ");
                output("il borsellino di qualcuno solitamente più fortunato di te. Scopristi anche che ");
                output("la parte posteriore dei tuoi nemici era meglio predisposta per una lama sottile di ");
                output("quanto non lo fosse quella anteriore anche per un'arma più potente.");
                break;
            case 4:
                page_header("Militare");
                output("`2Hai scoperto la tua innata capacità nell'usare ogni tipo di arma, ");
                output("nelle risse hai sempre battuto quelli grossi e cattivi, e alla fine sei diventato tu ");
                output("il più forte. Abbatterai i nemici con colpi potenti e precisi che gli altri nemmeno possono immaginarsi.");
                break;
            case 5:
                page_header("Seduction");
                output("`\$Crescendo, ricordi di aver scoperto l'amore e l'arte della seduzione. ");
                output("Il tuo fascino è cresciuto con te e puoi facilmente far innamorare chiunque di te. ");
                output("Tutti ti adorano e ti amano.");
                break;
            case 6:
                page_header("Tattica");
                output("`7Crescendo, ti ricordi di tutti i segreti e luoghi nascosti della foresta.");
                output("  Hai imparato a bilanciare il peso del tuo corpo e a compiere attacchi acrobatici.");
                output("  Il tuo corpo è la tua arma migliore.");
                break;
            case 7:
                page_header("Pelle di Roccia");
                output("`#Crescendo, ricordi tutti i tagli ed i lividi che ti sei procurato scalando le rocce.");
                output(" Tutte quelle scalate ti hanno indurito la pelle e l'hanno resa callosa.");
                output("  La tua pelle è ora dura come una roccia.");
                break;
            case 8:
                page_header("Retorica");
                output("`#Crescendo, ti rimembri di tutta la conoscenza appresa leggendo. ");
                output(" Mentre gli altri erano fuori a giocare, tu stavi rinchiuso a leggere i testi di Platone e Socrate.  ");
                output("Il tuo cervello è la tua arma migliore.");
                break;
            case 9:
                page_header("Muscoli");
                output("`%Crescendo, ti ricordi di quando sollevavi i cavalli e i carri con una mano sola. ");
                output(" Potresti correre sino al villaggio più lontano in un solo giorno. ");
                output("Il tuo corpo è in condizioni splendide.");
                break;
            case 10:
                page_header("Natura");
                output("`@Crescendo, ricordi che passavi tutto il tuo tempo libero nella foresta.  ");
                output("Tutti gli animali ti considerano loro amico, e sei sempre disponibile ad aiutarli. ");
                output("Gli animali sono la tua arma migliore.");
                break;
            case 11:
                page_header("Clima");
                output("`&Crescendo, ti ricordi che passavi la maggior parte del tuo tempo ad osservare il cielo. ");
                output("Osservavi le spirali disegnate dai tornado, il cadere della pioggia e i movimenti del vento. ");
                output("Hai il potere di invocare tempeste.");
                break;
            case 12:
                page_header("Elementalista");
                output("`3Crescendo, ti ricordi che scavavi buche nel terreno, nuotavi, appiccavi fuochi e cercavi di volare come un uccello.");
                output("Apprendesti i segreti più reconditi degli elementi della terra.  Queste cose erano veramente affascinanti. ");
                output("Comprendesti che se solo avessi potuto imbrigliare questi elementi avresti potuto usarli come arma.  Ti allenasti duramente. ");
                output("Col tempo, iniziasti a controllare il terreno, potevi modellare le acque, creare potenti raffiche di vento e generare fuochi dove altri non riuscivano.");
                output("Per tuo sommo piacere, potevano essere usati anche come arma contro i tuoi nemici.");
                break;
            case 13:
                page_header("Rabbia Barbara");
                output("`6Quando eri ancora un bambinetto, tutto ciò che desideravi era un pony per il tuo compleanno. ");
                output("Sembrava abbastanza ragionevole, il saper cavalcare è un'abilità invidiabile, ed i cavalli sono ottimi ");
                output("compagni.  I tuoi genitori non erano ricchi, ma si privarono del cibo per un mese, ");
                output("e ti comprarono il tuo ingrato pony per il tuo compleanno.  Appena ti vide, il pony scappò ");
                output("via terrorizzato--gli animali percepiscono il pericolo.  Non appena il pony arrivò al margine della foresta, ");
                output("il Drago si diresse su di lui e mangiò il tuo prezioso pony per colazione.  Non avesti mai il tempo di chiamare ");
                output("il tuo pony per nome, Mr. Furia-Cavallo-Del-West.`n`n");
                output("`^La cocente delusione causata da questo trauma infantile alimenta ancora oggi la tua Rabbia Barbara.`n`n");
                break;
            case 14:
                page_header("Canzoni del Bardo");
                output("`5Da bambino, sapevi che eri speciale, e migliore di chiunque altro.  Tu eri ");
                output("un artista.  I tuoi genitori dissero che eri un vagabondo disoccupato.  Ma tu percepisti che l'edonismo ");
                output("l'avrebbe vinta alla lunga, e gli artisti sono così sottovalutati.  Ma arrivò il Drago ed iniziò ad ");
                output("uccidere chiunque gli capitasse a tiro.  Ciò uccise il tuo estro e disgregò la tua visione artistica. ");
                output("La vera ragione che ti spinse a diventare un artista--a parte l'evitare un vero lavoro--era per corteggiare ");
                output("i membri del sesso opposto.  E se ognuno di noi deve morire un giorno, perchè non farlo con un po' di vino, ");
                output("favole e canzoni?  Ma per comporre una vera ballata eroica, ci deve essere un eroe per cui valga la pena ");
                output("cantare le gesta.  Sembra che dovrai fare tutto da solo...`n`n");
                break;
        }
        $session['user']['specialty'] = $_GET['setspecialty'];
    }
    if ($session['user']['level']==1 and $session['user']['dragonkills']==0){
        $session['user']['chow']=12367;
        $session['user']['potion']=1;
    }

} else {
    if ($session['user']['slainby'] != "") {
        page_header("Sei stato ucciso");
        output("`\$Sei stato ucciso nella " . $session['user']['killedin'] . " da `%" . $session['user']['slainby'] . "`\$.  Hai perso il 5% della tua esperienza, e tutto l'oro che avevi con te. Non pensi sia il momento di vendicarsi un po'?");
        addnav("Continua", "newday.php?continue=1$resline");
        $session['user']['slainby'] = "";
    } else {
        page_header("Inizia un Nuovo Giorno!");
        if ($session['user']['agedrago'] > 60 AND $session['user']['superuser'] == 0 AND $session['user']['npg']==0) {
            $session['user']['hitpoints']= $session['user']['maxhitpoints'];
            $session['user']['alive'] = true;
            $session['user']['agedrago'] = 58;
            output("`b`(Non credi sarebbe ora di affrontare il `@Drago Verde`(?`n");
            output("Sono più di 60 giorni che stai bighellonando per il villaggio senza scopo, ora potrai ");
            output("finalmente mettere alla prova la tua abilità di guerriero e confrontarti con il temibile ");
            output("`@Drago Verde`(!`n`nAuguri ... ne hai proprio bisogno ^__^`n`n");
            addnav("Affronta il `@Drago Verde`0","dragon.php?op1=smart");
        }else{
            $interestrate = e_rand($mininterest * 100, $maxinterest * 100) / (float)100;
            output("`c<font size='+1'>`b`#Inizia un nuovo giorno!`0`b</font>`c", true);
            $session['user']['specialinc']="";

            if ($session['user']['alive'] != true) {
                $session['user']['resurrections']++;
                output("`@Sei risort".($session['user']['sex']?"a":"o")."! Questa è la tua " . ordinal($session['user']['resurrections']) . " resurrezione.`0`n");
                $session['user']['alive'] = true;
            }
            $session['user']['age']++;
            $session['user']['agedrago']++;
            $session['user']['seenmaster'] = 0;
            output("Apri gli occhi per scoprire che un nuovo giorno è iniziato, è il tuo `^" . ordinal($session['user']['age']) . "`0 giorno.  ");
            output("Ti senti abbastanza riposat".($session[user][sex]?"a":"o")." da affrontare il mondo!`n");
            output("`2I turni per oggi sono `^ ".$turnsperday." `n");
            //Per evitare l'accumulo dei soldi per comprare pozioni-gemme e quant'altro by Excalibur
            //Excalibur: modifica per premio compleanno
            if ($session['user']['dragonkills'] > 0 OR $session['user']['reincarna'] > 0) {
                $dataodierna = date("m-d");
                $datacompleanno = substr($session['user']['compleanno'], 5, 5);
                $laston = substr($session['user']['lasthit'], 5, 5);
                if ($dataodierna == $datacompleanno AND $laston != $dataodierna){
                    $quanti = date("Y") - substr($session['user']['compleanno'], 0, 4);
                    output("<big>`c`n`(`bMa oggi è il tuo compleanno !! Dobbiamo festeggiare !!`n`b</big>",true);
                    output("<big>`(Gli Admin di LoGD hanno deciso di farti un piccolo regalo: `&10 Gemme`( e `^10.000 pezzi d'oro`(`n</big>",true);
                    output("<big>`(Fanne buon uso e tanti auguri per il tuo $quanti° compleanno !!!`n`n`n`c</big>",true);
                    debuglog("riceve 10 gemme e 10.000 pezzi d'oro per il suo compleanno");
                    $session['user']['gems'] += 10;
                    $session['user']['gold'] += 10000;
                }
            }
            //Excalibur:Fine premi compleanni
            if ($session['user']['goldinbank']<100000){
                if ($session['user']['cittadino']=="Si") {
                    $sql = "SELECT * FROM tasse WHERE acctid='".$session['user']['acctid']."'";
                    $result = db_query($sql) or die(db_error(LINK));
                    $row = db_fetch_assoc($result);
                    if ($row[oro]>getsetting("esattore",0) AND getsetting("esattore",0)>0 AND getsetting("blocco_int_evas",0)>0) $evasore=1;
                }
                if ($session['user']['turns'] > getsetting("fightsforinterest", 4) && $session['user']['goldinbank'] >= 0) {
                    $interestrate = 1;
                    output("`2Tasso di interesse odierno: `^0% (I banchieri in questo villaggio danno interessi solo a chi lavora sodo per guadagnarseli)`n");
                } elseif ($evasore) {
                    $interestrate = 1;
                    output("`2Tasso di interesse odierno: `^0% (Gli interessi guadagnati ti sono stati pignorati dall'esattore, paga le tasse e ti verranno accreditati)`n");
                } elseif ($_GET['resurrection'] != "egg" AND $_GET['resurrection'] != "true") {
                    output("`2Tasso di interesse odierno: `^" . (($interestrate-1) * 100) . "% `n");
                    if ($session['user']['goldinbank'] >= 0) {
                        output("`2Pezzi d'oro guadagnati con gli interessi: `^" . (int)($session['user']['goldinbank'] * ($interestrate-1)) . "`n");
                    } else {
                        output("`2Interesse accumulato sul Debito: `^" . - (int)($session['user']['goldinbank'] * ($interestrate-1)) . "`2 pezzi d'oro.`n");
                    }
                } else {
                    output("`2Poichè sei risort".($session[user][sex]?"a":"o")." non hai diritto a ricevere interessi dalla banca. Solo al `@Nuovo Giorno`2 si ha diritto a ricevere gli interessi`n");
                }
            }else{
                output("`n`\$`bNON FARE ".($session[user][sex]?"LA FURBA":"IL FURBO")." !!! Non è questo il modo di giocare !!! `nDovresti uccidere il `2Drago Verde`\$ e smetterla ");
                output("di accumulare soldi per acquistare gemme !!!`n Il limite per i quale si ottengono interessi è di `&100.000`\$ pezzi d'oro.`b`n");
            }//fine modifica by Excalibur
            if ($session['user']['goldinbank']>95000 AND $session['user']['goldinbank']<100000){
                $session['user']['furbo']++;
                //$spippo = $session['user']['goldinbank'] * ($interestrate - 1);
                //$mailmessage = "`@L'utente {$session['user']['name']} `@ha + di 90.000 pezzi d'oro in banca. Oggi ha ottenuto $spippo pezzi d'oro in interessi";
                //systemmail(696,"`2L'utente {$session['user']['name']} `2sta facendo il furbo",$mailmessage);
            }
            if ($session['user']['goldinbank'] < 10000 AND $session['user']['gold'] < 10000 AND $session['user']['furbo']>2){
                $mailmessage = "`@L'utente {$session['user']['name']} `@ha fatto il furbo";
                report(3,"`#Furbata Interessi","`2".$session['user']['name']." `2ha fatto il furbo con gli interessi in banca, ora ha speso tutti i suoi risparmi","interessi");
                $session['user']['furbo']=0;
            }


            $textdebug="al NEWDAY ha ".$session['user']['gold']." oro in mano,".
                       $session['user']['goldinbank']." oro in banca, ".
                       $session['user']['gems']." gemme, ".
                       $session['user']['evil']." evil, ".
                       $session['user']['charm']." charme, ".
                       $session['user']['attack']." atk, ".
                       $session['user']['defence']." def, ".
                       $session['user']['bonusattack']." bonusatk, ".
                       $session['user']['bonusdefence']." bonusdef.";
            output("`2I tuoi HitPoints sono stati riportati a `^" . $session['user']['maxhitpoints'] . "`n");
            $skills = array(1 => "`\$Arti Oscure", "`%Poteri Mistici", "`^Furto", "`3Militare","`\$Seduzione","`^Tattica","`@Pelle di Roccia","`#Retorica","`%Muscoli","`3Natura","`&Clima","`^Elementalista","`6Rabbia Barbara","`5Canzoni del Bardo");
            $sb = getsetting("specialtybonus", 1);
            output("`2Grazie al tuo interesse in `&" . $skills[$session['user']['specialty']] . "`2, ricevi $sb uso extra di `&" . $skills[$session['user']['specialty']] . "`2 per oggi.`n");
            $session['user']['darkartuses'] = (int)($session['user']['darkarts'] / 3) + ($session['user']['specialty'] == 1?$sb:0);
            $session['user']['magicuses'] = (int)($session['user']['magic'] / 3) + ($session['user']['specialty'] == 2?$sb:0);
            $session['user']['thieveryuses'] = (int)($session['user']['thievery'] / 3) + ($session['user']['specialty'] == 3?$sb:0);
            $session['user']['militareuses'] = (int)($session['user']['militare'] / 3) + ($session['user']['specialty'] == 4?$sb:0);
            $session['user']['mysticuses'] = (int)($session['user']['mystic']/3) + ($session['user']['specialty']==5?$sb:0);
            $session['user']['tacticuses'] = (int)($session['user']['tactic']/3) + ($session['user']['specialty']==6?$sb:0);
            $session['user']['rockskinuses'] = (int)($session['user']['rockskin']/3) + ($session['user']['specialty']==7?$sb:0);
            $session['user']['rhetoricuses'] = (int)($session['user']['rhetoric']/3) + ($session['user']['specialty']==8?$sb:0);
            $session['user']['muscleuses'] = (int)($session['user']['muscle']/3) + ($session['user']['specialty']==9?$sb:0);
            $session['user']['natureuses'] = (int)($session['user']['nature']/3) + ($session['user']['specialty']==10?$sb:0);
            $session['user']['weatheruses'] = (int)($session['user']['weather']/3) + ($session['user']['specialty']==11?$sb:0);
            $session['user']['elementaleuses'] = (int)($session['user']['elementale']/3) + ($session['user']['specialty']==12?$sb:0);
            $session['user']['barbarouses'] = (int)($session['user']['barbaro']/3) + ($session['user']['specialty']==13?$sb:0);
            $session['user']['bardouses'] = (int)($session['user']['bardo']/3) + ($session['user']['specialty']==14?$sb:0);
            if($session['user']['specialty'] == 14) {
                if($session['user']['charm'] > 0) {
                    $amt = (int)($session['user']['bardouses'] / 3);
                    $charmbonus = (int)($session['user']['charm'] / 50);
                    if($charmbonus > 0) {
                        output("`n`2Per essere un tale ammaliatore, ricevi `^1`2 uso extra di `&".$skills[$session['user']['specialty']]."`2 per oggi.`n");
                        $session['user']['bardouses'] ++;
                    }
                }else{
                    output("`n`2È veramente dura sedurre la gente senza fascino, perciò oggi non ricevi usi extra di `&".$skills[$session['user']['specialty']]."`2.`n");
                }
            }
            // $session['user']['bufflist']=array(); // with this here, buffs are always wiped, so the preserve stuff fails!

            if ($session['user']['marriedto'] == 4294967295 || $session['user']['charisma'] == 4294967295) {
                output("`n`%Sei sposat".($session[user][sex]?"a":"o").", perciò non c'è nessun motivo di mantenere un'immagine perfetta, ed oggi ti lasci andare un po'.`n");
                $session['user']['charm']--;
                if ($session['user']['charm'] <= 0) {
                    output("`bQuando ti svegli, trovi accanto a te un biglietto che dice`n`5Car".($session['user']['sex']?"a":"o")." ");
                    output($session['user']['name']);
                    output("`5,`nNonostante i bellissimi baci, scopro di non provare più attrazione per te nel modo in cui la provavo prima.`n`n");
                    output("Chiamami volubile, ma ho bisogno di andarmene. Ci sono altr".($session['user']['sex']?"e":"i")." guerrier".($session[user][sex]?"e":"i")." nel villaggio e penso che ");
                    output("alcun".($session['user']['sex']?"e":"i")." siano parecchio attraenti. Perciò non sei tu, sono io, eccetera eccetera....");
                    $sql = "SELECT name,acctid FROM accounts WHERE locked=0 AND acctid=" . $session['user']['marriedto'] . "";
                    $result = db_query($sql) or die(db_error(LINK));
                    $row = db_fetch_assoc($result);
                    $partner = $row[name];
                    if ($partner == "") {
                       $partner = $session['user']['sex']?"Seth":"Violet";
                    }else{
                        //Excalibur: cancellazione matrimonio
                        $sqlmatri = "DELETE FROM matrimoni
                        WHERE acctid1 = ".$session['user']['acctid']."
                        OR acctid2 = ".$session['user']['acctid'];
                        $resultmatri = db_query($sqlmatri) or die(db_error(LINK));
                        //Excalibur: fine matrimonio
                    }
                    output("`n`nSenza rancore, Baci, $partner`b`n");
                    debuglog("è stato lasciato da $partner");
                    addnews("`\$$partner ha lasciato ".$session['user']['name']."`\$ per seguire \"altri interessi\"!");
                    if ($session['user']['marriedto'] == 4294967295) $session['user']['marriedto'] = 0;
                    if ($session['user']['charisma'] == 4294967295) {
                        $sql = "UPDATE accounts SET charisma=0,marriedto=0 WHERE acctid='$row[acctid]'";
                        db_query($sql);
                        systemmail($row['acctid'], "`\$Di nuovo single!`0", "`6hai lasciato `&".$session['user']['name']."`6. Il matrimonio ultimamente era diventato una gabbia.");
                        $session['user']['charisma'] = 0;
                        $session['user']['marriedto'] = 0;
                    }
                }
            }
            // clear all standard buffs
            $tempbuf = unserialize($session['user']['bufflist']);
            $session['user']['bufflist'] = "";
            $session['bufflist'] = array();
            while (list($key, $val) = @each($tempbuff)) {
                if ($val['survivenewday'] == 1) {
                    $session['bufflist'][$key] = $val;
                    output("{$val['newdaymessage']}`n");
                }
            }

            reset($session['user']['dragonpoints']);
            $dkff = 0;
            while (list($key, $val) = each($session['user']['dragonpoints'])) {
                if ($val == "ff") {
                    $dkff++;
                }
            }
            if ($session[user][hashorse]) {
                $session['bufflist']['mount'] = unserialize($playermount['mountbuff']);
            }
            if ($dkff > 0) output("`n`2Guadagni `^$dkff`2 combattimenti grazie ai punti drago spesi!");
            //Sook, qui vanno scalati i turni degli oggetti
            $sqlo = "SELECT * FROM oggetti WHERE id_oggetti = '{$session['user']['oggetto']}'";
            $resulto = db_query($sqlo) or die(db_error(LINK));
            $rowo = db_fetch_assoc($resulto);
            $bonusturni = $session['user']['bonusfight'] - $rowo['turns_help'];
            if ($session['user']['reincarna'] > 0 AND $bonusturni > 0){
                output("`n`2Ottieni `^".$bonusturni."`2 combattimenti ottenuti nelle vite precedenti!");
            }
            $r1 = e_rand1(-1, 1);
            $r2 = e_rand1(-1, 1);
            $spirits = $r1 + $r2;
            if ($_GET['resurrection'] == "true") {
                addnews("`&{$session['user']['name']}`& è risort".($session[user][sex]?"a":"o")." grazie all'opera di `\$Ramius`&.");
                //debuglog("è risorto grazie a Ramius");
                $spirits = -6;
                $session['user']['deathpower'] -= 100;
                $session['user']['restorepage'] = "village.php?c=1";
            }

            if ($_GET['resurrection'] == "egg") {
                addnews("`&{$session['user']['name']}`& ha usato`^ l'uovo d'oro`&  e lascia il regno delle ombre.");
                //debuglog("è risorto usando l'uovo d'oro");
                $spirits = -6;
                $session['user']['restorepage'] = "village.php?c=1";
                savesetting("hasegg", stripslashes(0));
            }

            $sp = array((-6) => "Risorto", (-2) => "Molto Basso", (-1) => "Basso", "0" => "Normale", 1 => "Alto", 2 => "Molto Alto");
            output("`n`2Il tuo spirito oggi è `^" . $sp[$spirits] . "`2!`n");
            if (abs($spirits) > 0 AND $spirits != -6) {
                output("`2Perciò `^");
                if ($spirits > 0) {
                    output("guadagni ");
                } else {
                    output("perdi ");
                }
                output(abs($spirits) . " combattimenti nella foresta`2 per oggi!`n");
            }
            if ($session['user']['restorepage'] == "termininewplayer.php") $session['user']['restorepage']="village.php";
            $rp = $session['user']['restorepage'];
            $x = max(strrpos("&", $rp), strrpos("?", $rp));
            // prigione by luke
            if (e_rand(1,100) < 66 && $session['user']['evil']>0) {
                $session['user']['evil']-=1;
                output("Ti senti più buono e gentile verso gli altri oggi. Perciò ti viene scalato un `4Punto Cattiveria`2!!`n ");
            }
            if ($session['user']['jail']>0){
                if ($session['user']['jail']==2){$session['user']['jail'] = 1;}
                $session['user']['evil']-=20;
                // Maximus, remmata la redirect per evitare di fare il newday 'n' volte in prigione
                //redirect("constable.php?op=newday");
                // fine rem
            }
            if ($session['user']['evil']>99){
                if ($session['user']['bounty']<$session['user']['level']*500) $session['user']['bounty']=($session['user']['level']*500);
            }
            //fine prigione by luke
            //By Excalibur
            if ($session['user']['evil'] < 0) $session['user']['evil'] = 0;
            if ($x > 0) $rp = substr($rp, 0, $x);
            if (substr($rp, 0, 10) == "badnav.php") {
                //addnav("Continua", "news.php");
                addnav("Piazza del Villaggio","village.php");
            } else {
                // Maximus, modificato addnav per mandare il player in prigione quando scatta il newday
                //addnav("Continua", preg_replace("'[?&][c][=].+'", "", $rp));
                if ($session['user']['jail']>0){
                    addnav("Continua", "constable.php?op=newday");
                } else {
                    addnav("Continua", preg_replace("'[?&][c][=].+'", "", $rp));
                }
                // fine Maximus
            }
            //Per evitare l'accumulo dei soldi per comprare pozioni-gemme e quant'altro by Excalibur
            if ($session['user']['goldinbank']<100000){
                // Modifica per colpire i giocatori che fanno i furbi
                if ($session['user']['furbo'] > 2){
                    output("`n`\$`bOps, siamo spiacenti ma il direttore della banca ci ha comunicato che lei non ha diritto ");
                    output("a ricevere nessun interesse per condotta impropria. Cambi atteggiamento di gioco e forse potrà ");
                    output("tornare a percepire nuovamente gli interessi come tutti gli altri giocatori. `b`n");
                    output("`#Per qualunque comunicazione contattate `\$gli Amministratori`#, buona giornata. `n");
                    output("`%P.S. Per tornare ad ottenere gli interessi dovrai spendere `b`@TUTTI`b`% i soldi che hai ;-)`n`n");
                }elseif ($session['user']['evil'] > 99){
                    output("`n`\$`bEssendo ricercat".($session[user][sex]?"a":"o")." per crimini contro la giustizia, non hai diritto ad ottenere interessi sul
    tuo conto bancario (che probabilmente è frutto di rapine). Sconta i tuoi crimini con la giustizia e, come
    tutti cittadini onesti, tornerai a percepire gli interessi.`n`c`#La Direzione`b`c`n");
                }else //fine Modifica
                { //
                    $session['user']['laston'] = date("Y-m-d H:i:s");
                    if ($_GET['resurrection'] != "egg" AND $_GET['resurrection'] != "true") {
                        $bgold = $session['user']['goldinbank'];
                        $session['user']['goldinbank'] *= $interestrate;
                        $nbgold = $session['user']['goldinbank'] - $bgold;
                        if ($nbgold != 0) {
                            debuglog(($nbgold >= 0 ? "guadagna " : "paga ") . abs($nbgold) . " pezzi d'oro in interessi");
                        }
                    }
                }
            } //fine modifica by Excalibur

            //Excalibur: gestione Fattorie
            if ($_GET['resurrection'] != "egg" AND $_GET['resurrection'] != "true") {
                if ($session['user']['slaves']>=1){
                    if ($session['user']['age']>15) $perdita=(15-$session['user']['age'])*4;
                    $rand = e_rand(1,10);
                    switch ($rand){
                        case 1: case 5: case 8:
                            $slavemoney = $session['user']['slaves']*(20+$perdita);
                            if ($slavemoney < 0) $slavemoney = 0;
                            $slavemoney = intval(($slavemoney/15)*$session['user']['level']);
                            $session['user']['goldinbank']+=$slavemoney;
                            output("`n`(I tuoi Schiavi hanno lavorato in maniera discreta sfruttando il `^75% `(delle capacità delle tue Fattorie.`n");
                            break;
                        case 2: case 6: case 9:
                            $slavemoney = $session['user']['slaves']*(22+$perdita);
                            if ($slavemoney < 0) $slavemoney = 0;
                            $slavemoney = intval(($slavemoney/15)*$session['user']['level']);
                            $session['user']['goldinbank']+=$slavemoney;
                            output("`n`(I tuoi Schiavi hanno lavorato in maniera ottima sfruttando l'`^80% `(delle capacità delle tue Fattorie.`n");
                            break;
                        case 3: case 7: case 10:
                            $slavemoney = $session['user']['slaves']*(24+$perdita);
                            if ($slavemoney < 0) $slavemoney = 0;
                            $slavemoney = intval(($slavemoney/15)*$session['user']['level']);
                            $session['user']['goldinbank']+=$slavemoney;
                            output("`n`(I tuoi Schiavi hanno lavorato in maniera eccellente sfruttando il `^95% `(delle capacità delle tue Fattorie.`n");
                            break;
                        case 4:
                            $slavemoney = $session['user']['slaves']*(25+$perdita);
                            if ($slavemoney < 0) $slavemoney = 0;
                            $slavemoney = intval(($slavemoney/15)*$session['user']['level']);
                            $session['user']['goldinbank']+=$slavemoney;
                            if ($session['user']['slaves'] >= 10){
                                $schiavi = 10;
                                $schiavigemme = 3;
                                $session['user']['slaves']-=10;
                                $session['user']['gems']+=3;
                                debuglog("riceve 3 gemme per 10 schiavi alla fattoria");
                            }else{
                                $schiavi = $session['user']['slaves'];
                                $schiavigemme = (int)(($session['user']['slaves']/10)*3);
                                $session['user']['slaves'] = 0;
                                $session['user']['gems'] += $schiavigemme;
                                debuglog("riceve $schiavigemme gemme per $schiavi schiavi alla fattoria");
                            }
                            output("`n`\$$schiavi dei tuoi schiavi hanno acquistato la loro libertà pagando un riscatto di `&$schiavigemme gemme`\$!!!`n");
                            output("`(I tuoi Schiavi hanno lavorato in maniera eccezionale sfruttando il `b`^100% `b`(delle capacità delle tue Fattorie.`n");
                            break;
                    }
                    if ($session['user']['age']>15) output("`@Purtroppo sei lent".($session[user][sex]?"a":"o")." nel passare il DK, e sei stat".($session[user][sex]?"a":"o")." penalizzat".($session[user][sex]?"a":"o")." per questo`n");
                    output("`(Hai guadagnato `^`b$slavemoney pezzi d'oro`b`( (I guadagni sono ora proporzionati al livello).`n`n");
                    debuglog("guadagna $slavemoney oro con la fattoria");
                }
            }
            //Excalibur: fine gestione Fattorie

            //$session['user']['turns'] = $turnsperday + $spirits + $dkff;
            $session['user']['turns'] = ($session['user']['bonusfight'] + $turnsperday + $spirits + $dkff);
            if ($spirits == -6) $session['user']['turns'] += 6;
            $session['user']['turnimax'] = $session['user']['turns'];
            $session['user']['casinoturns'] = $casinoturns;
            $session['user']['hitpoints'] = $session['user']['maxhitpoints'];
            $session['user']['spirits'] = $spirits;
            if ($spirits != -6) $session['user']['playerfights'] = $dailypvpfights;
            $session['user']['transferredtoday'] = 0;
            $session['user']['amountouttoday'] = 0;
            $session['user']['seendragon'] = 0;
            $session['user']['seenmaster'] = 0;
            $session['user']['seenlover'] = 0;
            $session['user']['rockpaper'] = 0;
            $session['user']['perfect'] = 0;
            $session['user']['usedouthouse'] = 0;
            if ($spirits != -6) $session['user']['hadmerc'] = 0;
            $session['user']['turni_mestiere']=0;
            //set_pref_pond("relaxes",0);
            //set_pref_pond("swims",0);
            if ($spirits  != -6) {
               $oid = $session['user']['oggetto'];
               $sqlo = "SELECT * FROM oggetti WHERE id_oggetti = $oid";
               $resulto = db_query($sqlo) or die(db_error(LINK));
               $row = db_fetch_assoc($resulto);
               $potere=$row['potere'];
               if ($row['potere']>0 AND $row['usuramagica']!=0 AND $row['usuramagicamax']!=0) {
                  output("`2Il tuo `^".$row['nome']. "`2 si è ricaricato.`n");
                  $sqlu = "UPDATE oggetti SET potere_uso=$potere WHERE id_oggetti=$oid";
                  db_query($sqlu) or die(db_error(LINK));
               }
               if ($row['usuramagica']!=0 AND $row['usuramagicamax']!=0) $session['user']['gold'] += $row['gold_help'];
               if ($row['usuramagica']!=0 AND $row['usuramagicamax']!=0) $session['user']['gems'] += $row['gems_help'];
               if ($row['usuramagica']!=0 AND $row['usuramagicamax']!=0) $session['user']['playerfights'] += $row['pvp_help'];
               if ($row['gold_help']!=0 AND $row['usuramagica']!=0 AND $row['usuramagicamax']!=0) {
                  output("`2Grazie a `^".$row['nome']. "`2 hai guadagnato `^" . $row['gold_help'] . "`2 pezzi d'oro.`n");
               } elseif ($row['gold_help']!=0){
                  output("`$Il tuo `^".$row['nome']. "`$ ha esaurito la sua magia, non guadagnerai nessun pezzo d'oro finchè non sarà rigenerato.`n");
               }
               if ($row['gems_help']!=0 AND $row['usuramagica']!=0 AND $row['usuramagicamax']!=0) {
                  output("`2Grazie a `^".$row['nome']. "`2 hai guadagnato `^" . $row['gems_help'] . "`2 gemme.`n");
               } elseif ($row['gems_help']!=0){
                  output("`$Il tuo `^".$row['nome']. "`$ ha esaurito la sua magia, non guadagnerai nessuna gemma finchè non sarà rigenerato.`n");
               }
               if ($row['pvp_help']!=0 AND $row['usuramagica']!=0 AND $row['usuramagicamax']!=0) {
                  output("`2Grazie a `^".$row['nome']. "`2 hai guadagnato `^" . $row['pvp_help'] . "`2 turno PvP.`n");
               } elseif ($row['pvp_help']!=0){
                  output("`$Il tuo `^".$row['nome']. "`$ ha esaurito la sua magia, non guadagnerai nessun turno PvP finchè non sarà rigenerato.`n");
               }
            }
            //Excalibur: Modifica per travestimento; riduzione potenziatore di Guido
            if ($session['user']['camuffa'] AND $session['user']['bankrobbed'] > 0) {
                $dataodierna = date("m-d");
                $laston = substr($session['user']['lasthit'], 5, 5);
                $camuffa = $session['user']['camuffa'];
                $giornosett = date("z");
                if ($camuffa == 1 AND ($giornosett/5) == (int)($giornosett/5) AND $laston != $dataodierna) {
                    $session['user']['bankrobbed']-=15;
                    output("`(Le proprietà magiche del tuo costume riducono la forza di Guido !!`n");
                } elseif ($camuffa == 2 AND ($giornosett/4) == (int)($giornosett/4) AND $laston != $dataodierna) {
                    $session['user']['bankrobbed']-=15;
                    output("`(Le proprietà magiche del tuo costume riducono la forza di Guido !!`n");
                } elseif ($camuffa == 3 AND ($giornosett/3) == (int)($giornosett/3) AND $laston != $dataodierna) {
                    $session['user']['bankrobbed']-=15;
                    output("`(Le proprietà magiche del tuo costume riducono la forza di Guido !!`n");
                } elseif ($camuffa == 4 AND ($giornosett/2) == (int)($giornosett/2) AND $laston != $dataodierna) {
                    $session['user']['bankrobbed']-=15;
                    output("`(Le proprietà magiche del tuo costume riducono la forza di Guido !!`n");
                }
                if ($session['user']['bankrobbed'] < 0) $session['user']['bankrobbed'] = 0;
            }
            //Excalibur: fine modifica travestimento
            if ($_GET['resurrection'] != "true" && $_GET['resurrection'] != "egg") {
                $session['user']['soulpoints'] = 50 + 5 * $session['user']['level'];
//                $session['user']['gravefights'] = getsetting("gravefightsperday", 10);
                $session['user']['gravefights'] = getsetting("gravefightsperday", 10) + $playermount['mounttorments'];
            }
            $session['user']['specialinc'] = ""; //per correggere errore con evento speciale
            if (($spirits != -6) && ($session['user']['age'] > 1)) $session['user']['messa'] = 0;
            $session['user']['seenbard'] = 0;
            $session['user']['maze'] = 0;
            $session['user']['boughtroomtoday'] = 0;
            $session['user']['recentcomments'] = $session['user']['lasthit'];
            $session['user']['lasthit'] = date("Y-m-d H:i:s");
            if ($session['user']['drunkenness'] > 66) {
                output("`&Per i postumi della sbornia, perdi 1 combattimento nella foresta oggi.");
                $session['user']['turns']--;
                $session['user']['turnimax'] = $session['user']['turns'];
            }
            //Set global newdaysemaphore

            $lastnewdaysemaphore = convertgametime(strtotime(getsetting("newdaysemaphore","0000-00-00 00:00:00")));
            $gametoday = gametime();

            if (date("Ymd",$gametoday)!=date("Ymd",$lastnewdaysemaphore)){
                $sql = "LOCK TABLES settings WRITE";
                db_query($sql);

                $lastnewdaysemaphore = convertgametime(strtotime(getsetting("newdaysemaphore","0000-00-00 00:00:00")));

                $gametoday = gametime();
                if (date("Ymd",$gametoday)!=date("Ymd",$lastnewdaysemaphore)){
                    //we need to run the hook, update the setting, and unlock.
                    savesetting("newdaysemaphore",date("Y-m-d H:i:s"));
                    $sql = "UNLOCK TABLES";
                    db_query($sql);

                    require_once "setweather.php";

                }else{
                    //someone else beat us to it, unlock.
                    $sql = "UNLOCK TABLES";
                    db_query($sql);
                    output("È arrivato qualcuno prima di noi");
                }
            }

            output("`nDal dolore della battaglia nelle tue stanche ossa, sai che oggi il tempo sarà `6".$settings['weather']."`@.`n");
            if ($_GET['resurrection']==""){
                if ($session['user']['specialty']==1 and $settings['weather']=="piovoso"){
                    output("`^`nLa pioggia abbatte il tuo spirito ma acuisce le tua Arti Oscure, guadagni un combattimento foresta.`n");
                    $session[user][turns]++;
                }
                if ($session['user']['specialty']==2 and $settings['weather']=="rovesci temporaleschi"){
                    output("`^`nI fulmini ricaricano i tuoi Poteri Mistici, guadagni un combattimento foresta.`n");
                    $session[user][turns]++;
                }
                if ($session['user']['specialty']==3 and $settings['weather']=="nebbioso"){
                    output("`^`nLa nebbia dà ai ladri un vantaggio nascondendo alla vista le loro imprese, guadagni un combattimento foresta.`n");
                    $session[user][turns]++;
                }
                if ($session['user']['specialty']==4 and $settings['weather']=="sereno ma freddo"){
                    output("`^`nIl cielo limpido ed il clima freddo stimolano i sensi di un espert".($session[user][sex]?"a":"o")." nell'arte militare quale tu sei, guadagni un combattimento foresta.`n");
                    $session[user][turns]++;
                }
            }
            //End global newdaysemaphore code.

            if ($session['user']['bounty'] > 10000) {
                $session['user']['bounty']=10000;
            }
            if ($session['user']['hashorse']) {
                output(str_replace("{weapon}", $session['user']['weapon'], "`n`&{$playermount['newday']}`n`0"));
                if ($playermount['mountforestfights'] > 0) {
                    output("`n`&Poiché hai un {$playermount['mountname2']}, guadagni `^" . ((int)$playermount['mountforestfights']) . "`& combattimenti nella foresta per oggi!`n`0");
                    $session['user']['turns'] += (int)$playermount['mountforestfights'];
                    $session['user']['turnimax'] = $session['user']['turns'];
                }
                //Sook: aggiunta nuovi effetti animali
                if ($playermount['resbonus'] == 0) {
                    $playermount['pvpchance'] = 0;
                    $playermount['gemchance'] = 0;
                    $playermount['goldchance'] = 0;
                    $playermount['favorichance'] = 0;
                    $playermount['charmchance'] = 0;
                    $playermount['evilchance'] = 0;
                }
                if ($playermount['pvpchance'] > 0) {
                    $random = e_rand(1, 10000);
                    $chance = $playermount['pvpchance'] * 100;
                    if ($random <= $chance) {
                        $session['user']['playerfights'] ++;
                        output("`n`3Poiché hai un {$playermount['mountname2']}, guadagni `^ 1`3 Combattimento PVP per oggi!`n`0");
                    }
                }
                 if ($playermount['pvpchance'] < 0) {
                    $random = e_rand(1, 10000);
                    $chance = $playermount['pvpchance'] * -100;
                    if ($random <= $chance) {
                        $session['user']['playerfights'] --;
                        output("`n`3Poiché hai un {$playermount['mountname2']}, `\$perdi `^ 1`3 Combattimento PVP per oggi!`n`0");
                    }
                }
                if ($playermount['gemchance'] > 0) {
                    $random = e_rand(1, 10000);
                    $chance = $playermount['gemchance'] * 100;
                    if ($random <= $chance) {
                        $session['user']['gems'] ++;
                        output("`n`#Poiché hai un {$playermount['mountname2']}, guadagni `^ 1`# gemma!`n`0");
                        if ($playermount['gem_oro'] == 1) $playermount['goldchance']=0;
                    }
                }
                if ($playermount['gemchance'] < 0) {
                    $random = e_rand(1, 10000);
                    $chance = $playermount['gemchance'] * -100;
                    if ($random <= $chance) {
                        $session['user']['gems'] --;
                        output("`n`#Poiché hai un {$playermount['mountname2']}, `\$perdi `^ 1`# gemma!`n`0");
                    }
                }
                if ($playermount['goldchance'] > 0) {
                    $random = e_rand(1, 10000);
                    $chance = $playermount['goldchance'] * 100;
                    $randomb = e_rand($playermount['goldmin'], $playermount['goldmax']);
                    if ($random <= $chance && $randomb!=0) {
                        if ($randomb > 0) {
                            $session['user']['gold'] += $randomb;
                            output("`n`6Poiché hai un {$playermount['mountname2']}, guadagni `^" . ($randomb) . "`6 pezzi d'Oro!`n`0");
                        } else {
                            $randomb*=-1;
                            if ($session['user']['gold'] >= $randomb) {
                                $session['user']['gold'] -= $randomb;
                            } else {
                                $differenza = $session['user']['gold'] - $randomb;
                                $session['user']['gold'] = 0;
                                if ($differenza >= $session['user']['goldinbank']) {
                                    $session['user']['goldinbank'] = 0;
                                } else {
                                    $session['user']['goldinbank'] -= $differenza;
                                }
                            }
                            output("`n`6Poiché hai un {$playermount['mountname2']}, `\$perdi " . ($randomb) . "`6 pezzi d'Oro!`n`0");
                        }
                    }
                }
                if ($playermount['favorichance'] > 0) {
                    $random = e_rand(1, 10000);
                    $chance = $playermount['favorichance'] * 100;
                    if ($random <= $chance && $playermount['favori']!=0) {
                        $session['user']['deathpower'] += $playermount['favori'];
                        if ($playermount['favori'] > 0) {
                            output("`n`9Poiché hai un {$playermount['mountname2']}, guadagni `^" . ((int)$playermount['favori']) . "`9 favori con Ramius!`n`0");
                        } else {
                            $playermount['favori']*=-1;
                            output("`n`9Poiché hai un {$playermount['mountname2']}, `\$perdi " . ((int)$playermount['favori']) . "`9 favori con Ramius!`n`0");
                        }
                    }
                }
                if ($playermount['charmchance'] > 0) {
                    $random = e_rand(1, 10000);
                    $chance = $playermount['charmchance'] * 100;
                    if ($random <= $chance && $playermount['charm']!=0) {
                        $session['user']['charm'] += $playermount['charm'];
                        if ($playermount['charm'] > 0) {
                            output("`n`%Poiché hai un {$playermount['mountname2']}, guadagni `^" . ((int)$playermount['charm']) . "`% punti fascino!`n`0");
                        } else {
                            $playermount['charm']*=-1;
                            output("`n`%Poiché hai un {$playermount['mountname2']}, `\$perdi " . ((int)$playermount['charm']) . "`% punti fascino!`n`0");
                        }
                    }
                }
                if ($playermount['evilchance'] > 0) {
                    $random = e_rand(1, 10000);
                    $chance = $playermount['evilchance'] * 100;
                    if ($random <= $chance && $playermount['evil']!=0) {
                        $session['user']['evil'] -= $playermount['evil'];
                        if ($playermount['evil'] > 0) {
                            output("`n`@Poiché hai un {$playermount['mountname2']}, hai perso `^" . ((int)$playermount['evil']) . "`@ punti cattiveria!`n`0");
                        } else {
                            $playermount['evil']*=-1;
                            output("`n`@Poiché hai un {$playermount['mountname2']}, `\$hai guadagnato " . ((int)$playermount['evil']) . "`@ punti cattiveria!`n`0");
                        }
                    }
                }
                //Sook: fine aggiunta nuovi effetti animali
                if ($session['user']['hashorse']==14) {
                    $goose=(e_rand(1000,2000));
                    output(" `n`n`2Al risveglio scopri che l'`^Oca d'Oro `2ti ha fatto trovare un regalino. ");
                    switch(e_rand(1,5)){
                        case 1: case 2: case 3: case 4:
                            output(" `n`2Trovi `^$goose PEZZI D'ORO `2sotto l'Oca, \"Come può essere?\" ...ti chiedi.");
                            $session['user']['gold']+=$goose;
                            break;
                        case 5:
                            output(" `n`2trovi una `^GEMMA `2sotto l'Oca, \"Come può essere?\" ...ti chiedi.");
                            $session['user']['gems']+=1;
                            break;
                    }
                }
            } else {
                output("`n`&Leghi il tuo `%" . $session['user']['weapon'] . "`& alla schiena e vai in cerca di avventure.`0");
            }

            //Sook, visualizzazione turni extra dell'oggetto.
            if ($session['user']['oggetto']!=0) {
                $sqlo = "SELECT * FROM oggetti WHERE id_oggetti = '{$session['user']['oggetto']}'";
                $resulto = db_query($sqlo) or die(db_error(LINK));
                $rowo = db_fetch_assoc($resulto);
                if (($rowo['usura']>1 AND $rowo['usuramax']>0 OR $rowo['usuramax']==-1) AND ($rowo['usuramagica']>1 AND $rowo['usuramagicamax']>0  OR $rowo['usuramagicamax']==-1) AND $rowo['turns_help']>0) {
                    output("`n`#Il tuo {$rowo['nome']}`#, ti fa guadagnare `^".$rowo['turns_help']."`# combattimenti nella foresta aggiuntivi!`n`0");
                }
            }

            if ($session['user']['race'] == 3) {
                $session['user']['turns']++;
                $session['user']['turnimax'] = $session['user']['turns'];
                output("`n`&Poichè sei un Umano, guadagni `^1`& combattimento nella foresta per oggi!`n`0");
            }
            if  ($session['user']['race'] == 6 ){
                $session['user']['turns']-=1;
                $session['user']['turnimax'] = $session['user']['turns'];
                output("`n`%Poichè sei un Goblin, perdi `^1`% combattimenti nella foresta per oggi!`n`0");
            }
            if  ($session['user']['race'] == 7 ){
                $session['user']['turns']-=2;
                $session['user']['turnimax'] = $session['user']['turns'];
                output("`n`\$Poichè sei un Orco, perdi `^2`\$ combattimenti nella foresta per oggi!`n`0");
            }
            if  ($session['user']['race'] == 5 ){
                $session['user']['magicuses']+=1;
                output("`n`@Poichè sei un Druido, guadagni `^1`@ punto di utilizzo nei Poteri Mistici!`n`0");
            }
            if  ($session['user']['race'] == 8 ){
                $session['user']['darkartuses']+=2;
                output("`n`4Poichè sei un Vampiro, guadagni `^2`4 punto di utilizzo nelle Arti Oscure!`n`0");
            }
            if  ($session['user']['race'] == 9 ){
                $session['user']['darkartuses']+=2;
                output("`n`4Poichè sei un `%Lich`5, guadagni `^2`4 punto di utilizzo nelle Arti Oscure!");
                if ($session['user']['spirits'] > -6 AND $session['user']['deathpower'] < 400) {
                    output(" E 50 favori con Ramius!`n`0");
                    $session['user']['deathpower']+=50;
                }
            }
            if  ($session['user']['race'] == 10 ){
                $session['user']['weatheruses']+=2;
                output("`n`3Poichè sei una `&Fanciulla delle Nevi`3, guadagni `^2`4 punti di utilizzo in Clima!`n`0");
            }
            if  ($session['user']['race'] == 11 ){
                $session['user']['tacticuses']+=2;
                output("`n`4Poichè sei un `%Oni`4, guadagni `^2`4 punti di utilizzo in Tattica!`n`0");
            }
            if  ($session['user']['race'] == 12 ){
                $session['user']['natureuses']+=1;
                output("`n`2Poichè sei un `@Satiro`4, guadagni `^1`2 punto di utilizzo in Natura e riesci a sfuggire più facilmente dai tuoi avversari!`n`0");
            }
            if  ($session['user']['race'] == 13 ){
                $session['user']['rockskinuses']+=1;
                output("`n`3Poichè sei un `#Gigante della Tempesta`3, guadagni `^1`3 punto di utilizzo in Abilità delle Rocce oltre al bonus di razza!`n`0");
            }
            if  ($session['user']['race'] == 14 ){
                output("`n`4Poichè sei un `\$Barbaro`4, hai il bonus di razza che ti consente di infliggere più danni agli avversari!`n`0");
            }
            if  ($session['user']['race'] == 15 ){
                output("`n`5Poichè sei un'`%Amazzone`5, guadagni `^1`5 punto di utilizzo in `@Natura`5 oltre al bonus di razza!`n`0");
                $session['user']['natureuses']+=1;
            }
            if  ($session['user']['race'] == 16 ){
                output("`3Essendo un `#Titano`3, guadagni due punti di utilizzo in `^Muscoli`3!");
                $session['user']['muscleuses']+=2;
            }
            if  ($session['user']['race'] == 17 ){
                output("`8Essendo un `\$Demone`8, guadagni due punti di utilizzo in `\$Arti Oscure`8!");
                $session['user']['darkartuses']+=2;
                if ($session['user']['spirits'] > -6 AND $session['user']['deathpower'] < 500) {
                    output(" E 50 favori con `\$Ramius!`8`n");
                    $session['user']['deathpower']+=50;
                }
            }
            if  ($session['user']['race'] == 18 ){
                output("`8Essendo un `(Centauro`8, guadagni un punto di utilizzo in `^Muscoli`8!");
                $session['user']['muscleuses']+=1;
            }
            if  ($session['user']['race'] == 19 ){
                output("`6Essendo un `^Licantropo`6, guadagni un punto di utilizzo in `@Natura`6!");
                $session['user']['natureuses']+=1;
            }
            if  ($session['user']['race'] == 20 ){
                output("`8Essendo un `(Minotauro`8, guadagni un punto di utilizzo in `^Muscoli`8!");
                $session['user']['muscleuses']+=1;
            }
            if  ($session['user']['race'] == 21 ){
                output("`6Essendo un `^Cantastorie`6, guadagni un punto di utilizzo in `^Canzoni del Bardo`8!");
                $session['user']['bardouses']+=1;
            }
            if  ($session['user']['race'] == 22 ){
                output("`2Essendo un `@Eletto`2, guadagni due punti di utilizzo in `@Natura`2!");
                $session['user']['natureuses']+=2;
            }
            if  ($session['user']['evil'] > 100 ){
                $session['user']['turns']--;
                $session['user']['turnimax'] = $session['user']['turns'];
                output("`n`\$Poichè la tua malvagità ha superato il limite critico, sei ricercat".($session[user][sex]?"a":"o")." dallo sceriffo e devi muoverti con una certa ");
                output("discrezione.`nCiò ti fa perdere `^1`\$ combattimento in foresta!`n`0");
            }
            //Modifica per favorire i player di livello basso
            if ($session['user']['dragonkills']<5 AND $session['user']['reincarna']==0){
                $turnisuppl=10 - (2*$session['user']['dragonkills']);
                $session['user']['turns']+=$turnisuppl;
                $session['user']['turnimax'] = $session['user']['turns'];
                output("`n`#Poichè sei ".($session[user][sex]?"una guerriera":"un guerriero")." di primo pelo, gli dei ti concedono `^`b$turnisuppl`b`# combattimenti supplementari. Fanne buon uso.");
            }
            //fine modifica aggiunta turni

            // Excalibur: Mercante Ophelius
            if (get_pref_pond("mercante")==1) {
                output("`n`n`8Mentre ti stai preparando per affrontare nuove imprese, un postino ti consegna un ");
                if ($session['user']['marriedto'] == 4294967295 || $session['user']['charisma'] == 4294967295) {
                   output("biglietto di ".($session['user']['sex']?"`^tuo marito":"`%tua moglie")."`5.`n");
                }else{
                   output("biglietto del".($session['user']['sex']?" tuo fidanzato":"la tua fidanzata")."`5.`n");
                }
                if (get_pref_pond("liked")==1){
                    output("\"`(Che meravigliosa sorpresa! Ho apprezzato moltissimo il tuo regalo! Lo mostrerò ");
                    output("a tutt".($session['user']['sex']?"i i miei amici":"e le mie amiche")."!\"`n`n");
                    output("`^`bGuadagni del fascino e una protezione supplementare per i combattimenti!`b`n");
                    $session['user']['charm']+=2;
                    $turniamore = e_rand(50,80);
                    $session['bufflist']['mercante'] = array(
                             "name"=>"`(L'amore ti protegge",
                             "rounds"=>$turniamore,
                             "wearoff"=>"`%Il ricordo del tuo amore si dissolve e non sei più protetto.",
                             "defmod"=>1.4,
                             "roundmsg"=>"L'immagine del tuo amore crea uno scudo protettivo.",
                             "activate"=>"defense");
                } else{
                    output("\"`%Non posso credere che tu sperassi di ottenere la mia approvazione con un oggetto ");
                    output("così pacchiano!\"`n`n");
                    output("`\$Perdi`^ del fascino.`n");
                    $turniamore = e_rand(20,50);
                    $session['bufflist']['mercante'] = array(
                             "name"=>"`xDelusione d'amore",
                             "rounds"=>$turniamore,
                             "wearoff"=>"`%Il ricordo della delusione si dissolve e torni efficiente.",
                             "atkmod"=>0.9,
                             "roundmsg"=>"La delusione d'amore riduce l'efficacia dei tuoi attacchi.",
                             "activate"=>"offense,defense");
                    if ($session['user']['charm']>2){
                        $session['user']['charm']-=2;
                    } else {
                        $session['user']['charm'] = 0;
                    }
                }
            }
            // Excalibur: end Mercante Ophelius
            db_query("DELETE FROM items WHERE class = 'pond' AND owner = ".$session['user']['acctid']) or die(db_error(LINK));

            $config = unserialize($session['user']['donationconfig']);
            if (!is_array($config['forestfights'])) $config['forestfights'] = array();
            reset($config['forestfights']);
            while (list($key, $val) = each($config['forestfights'])) {
                $config['forestfights'][$key]['left']--;
                output("`@Guadagni un turno extra per i punti spesi a `^{$val['bought']}`@.");
                $session['user']['turns']++;
                $session['user']['turnimax'] = $session['user']['turns'];
                if ($val['left'] > 1) {
                    output("  Hai ancora `^" . ($val['left']-1) . "`@ giorni restanti di questo acquisto.`n");
                } else {
                    unset($config['forestfights'][$key]);
                    output("  Questo acquisto è scaduto.`n");
                }
            }
            if ($config['healer'] > 0) {
                $config['healer']--;
                if ($config['healer'] > 0) {
                    output("`n`@Golinda sarà felice di vederti per altri {$config['healer']} giorn" . ($config['healer'] > 1 ? "i." : "o."));
                } else {
                    output("`n`@Golinda non si occuperà più di te.");
                    unset($config['healer']);
                }
            }
            $session['user']['donationconfig'] = serialize($config);
            if ($session['user']['hauntedby'] > "") {
                output("`n`n`)Sei stat".($session[user][sex]?"a":"o")." tormentat".($session[user][sex]?"a":"o")." da ".$session['user']['hauntedby']."`), e per questo perdi un combattimento nella foresta!");
                $session['user']['turns']--;
                $session['user']['turnimax'] = $session['user']['turns'];
                $session['user']['hauntedby'] = "";
            }
            $session['user']['drunkenness'] = 0;
            $session['user']['lotto']= 0;
            $session['user']['lottochoice']=0;
            $session['user']['matches']=0;
            $session['user']['bounties'] = 0;
            $session['user']['vecchio'] = 0;
            $session['user']['sabota'] = 0;

            //Excalibur: modifica per concorso fagioli
            if ($spirits != -6){
               db_query("DELETE FROM items WHERE class = 'morte_strega' AND owner = ".$session['user']['acctid']) or die(db_error(LINK));
               if ($session['user']['robbank']==0 AND $session['user']['bankrobbed']>0) {
                   $session['user']['bankrobbed']--;
               }
               $session['user']['robbank']=0;
               db_query("DELETE FROM items WHERE owner='".$session['user']['acctid']."' AND class='robbank'") or die(db_error(LINK));
               $session['user']['heimdall'] = 0;
               if ($session['user']['nocomment'] > 0) {
                  $session['user']['nocomment']--;
               }
               $session['user']['fagiolitry'] = 0;
               if (getsetting("statofagioli", "chiuso") == "chiuso"){
                   $session['user']['fagioli'] = 0;
               }
            }
            //Excalibur: fine modifica concorso fagioli

            //Excalibur: modifica per riportare stat drago
            if ($session['user']['id_drago'] != 0 AND $spirits != -6) {
                $sqldrago = "SELECT * FROM draghi WHERE id = ".$session['user']['id_drago'];
                $resultdrago = db_query($sqldrago) or die(db_error(LINK));
                if (db_num_rows($resultdrago) == 0){
                    $session['user']['id_drago'] = 0;
                    output("`n`^`b<big>Il tuo fidato drago pare sia morto, dovrai procurartene un altro per raggiungere le Terre dei Draghi.</big>`b`n",true);
                }else{
                    $sqldrago = "UPDATE draghi SET vita_restante = vita WHERE id = ".$session['user']['id_drago'];
                    $resultdrago=db_query($sqldrago) or die(db_error(LINK));
                    $session['user']['turni_drago_rimasti'] = $session['user']['turni_drago'];
                    output("`n`^`bI tuoi turni drago sono stati riportati al massimo, come anche i punti vita del tuo fedele drago.`b`n");
                }
            }
            //Excalibur: fine modifica stat drago

            // Modifica spells Handle buffs from items
            $sql="SELECT * FROM items WHERE (class='Spell' OR class='Move') AND owner=".$session[user][acctid]." ORDER BY id";
            $result=db_query($sql);
            $countrow = db_num_rows($result);
            for ($i=0; $i<$countrow; $i++){
            //for ($i=0;$i< db_num_rows($result);$i++){
                $row = db_fetch_assoc($result);
                if ($row[hvalue]>0){
                    $row[hvalue]--;
                    if ($row[hvalue]<=0 && $row['class']=="Spell"){
                        db_query("DELETE FROM items WHERE id=$row[id]");
                        output("`n`2$row[name]`2 lost its power.`n");
                    }else{
                        $what="hvalue=$row[hvalue]";
                        if ($row['class']=='Spell') $what.=", value1=$row[value2]";
                        db_query("UPDATE items SET $what WHERE id=$row[id]");
                    }
                }
            }
            // Fine modifica spells

            //begin bladder code
            if ($session['user']['bladder']>20 AND $session['user']['spirits']!=-6){
                output("`3Scopri che non sei riuscit".($session[user][sex]?"a":"o")." a trattenerti e ti sei bagnat".($session[user][sex]?"a":"o")." mentre dormivi!");
                $session['user']['charm']-=2;
                addnews("`^".$session['user']['name']."`! ha bagnato il letto mentre dormiva!");
                $session['user']['bladder']=-2;
            }
            $session['user']['bladder']+=2;
            //end bladder code

            //begin cleanliness code
            //output("->".$session['user']['title']."<-");
            if ($session['user']['title']=="PigPen"){
        		$session['user']['charm']-=1;
    		}else {
	            if ($session ['user']['clean'] > 6 AND $session['user']['spirits']!=-6) $session['user']['charm']-=($session['user']['clean']-6);
	            $session['user']['clean']+=1;
	            if ($session['user']['clean']>9 AND $session['user']['clean']<15 AND $session['user']['spirits']!=-6) addnews("".$session['user']['name']."`2 emette un odore poco gradevole!");
	            if ($session['user']['clean']>14 AND $session['user']['clean']<20 AND $session['user']['spirits']!=-6){
	                output("`n`%Riesci a sopportare a malapena il terribile puzzo che emetti!");
	                addnews("".$session['user']['name']."`2 puzza come un caprone di montagna!");
	            }
	            if ($session['user']['clean']>19 AND $session['user']['spirits']!=-6){
	                output("`n`\$Hai guadagnato il titolo di PigPen per essere così sporc".($session[user][sex]?"a":"o")."!`n");
	                addnews("".$session['user']['name']." `6ha guadagnato il titolo di PigPen per la sua sporcizia estrema!");
	                assegnapigpen($session['user']['name']);
	            }
			}   
            //end cleanliness code
        }
    }

    if ($session['user']['superuser'] > 1 OR $session['user']['npg']==1) {
        $session['user']['bladder'] = 0;
        $session['user']['hunger'] = 0;
        $session['user']['clean'] = 0;
    }

    //Modifica Uovo Nero
    if ($session['user']['acctid']==getsetting("hasblackegg",0)) {
        output("`n`%Poichè possiedi l'`!`bUovo Nero`b `%perdi `\$2 Combattimenti`% !!!`n");
        $session['user']['turns'] -= 2;
        $session['user']['turnimax'] = $session['user']['turns'];
    }
    //Perdita automatica Uovo d'oro in caso di non uso se morto
    if ($session['user']['acctid']==getsetting("hasegg",0) AND $session['user']['risorto']==1) {
        output("`n`#Eri in possesso dell'`^Uovo d'Oro`#, e nonostante tu fossi mort".($session[user][sex]?"a":"o")." non l'hai utilizzato.`n");
        output("Non sei degn".($session[user][sex]?"a":"o")." di possederlo, gli Dei te lo sottraggono e lo restituiscono a mamma Grifone `n");
        output("perchè qualche altro giocatore possa trovarlo e sfruttarlo, cosa che tu non hai fatto !!`n");
        addnews("`%Gli dei hanno sottratto l'`^Uovo d'Oro `%a `@".$session['user']['name']." `%per restituirlo a mamma Grifone !!");
        savesetting("hasegg",stripslashes(0));
        $session['user']['risorto'] = 0;
    }
    $session['user']['risorto'] = 0;
    //Perdita automatica Uovo Nero in caso di non login per più di 3 giorni reali
    $egggold = getsetting("hasblackegg",0);
    if ($egggold != 0) {
        $olddays = 2;
        $oldhour = 12;
        if ($egggold != $session['user']['acctid']) {
            $sqlegg = "SELECT acctid,name,emailaddress,laston FROM accounts WHERE acctid=$egggold";
            db_query($sqlegg) or die(db_error(LINK));
            $resultegg = db_query($sqlegg);
            $rowegg = db_fetch_assoc($resultegg);
            if (date("YmdHis",strtotime($rowegg[laston])) < (date("YmdHis",strtotime(date("r")."-$olddays days -$oldhour hours")))) {
                savesetting("hasblackegg",stripslashes(0));
                output("`n`n`@Gli dei hanno tolto l'`^Uovo d'Oro`@ a `#".$rowegg['name']."`@ perchè non si è
                collegat".($session[user][sex]?"a":"o")." per 2 giorni e 1/2 (reali).`nPrecisamente dal ".$rowegg[laston]);
                mail($rowegg['emailaddress'],"Uovo Nero",
                "Poichè non ti sei collegat".($session[user][sex]?"a":"o")." per 3 giorni (reali), gli dei ti hanno tolto l'Uovo Nero, per darlo a qualche altro giocatore altrettanto sfortunato, e farlo soffrire per il solo possederlo.",
                "From: LoGD OGSI"
                );
            }
        }
    }
    //Fine Perdita automatica Uovo Nero in caso di non login per più di 3 giorni reali

    //Perdita automatica Uovo d'oro in caso di non login per più di 3 giorni reali
    $egggold = getsetting("hasegg",0);
    if ($egggold != 0) {
        $olddays = 2;
        $oldhour = 12;
        if ($egggold != $session['user']['acctid']) {
            $sqlegg = "SELECT acctid,name,emailaddress,laston FROM accounts WHERE acctid=$egggold";
            db_query($sqlegg) or die(db_error(LINK));
            $resultegg = db_query($sqlegg);
            $rowegg = db_fetch_assoc($resultegg);
            if (date("YmdHis",strtotime($rowegg[laston])) < (date("YmdHis",strtotime(date("r")."-$olddays days -$oldhour hours")))) {
                savesetting("hasegg",stripslashes(0));
                output("`n`n`@Gli dei hanno tolto l'`^Uovo d'Oro`@ a `#".$rowegg['name']."`@ perchè non si è
                collegato per 2 giorni e 1/2 (reali).`nPrecisamente dal ".$rowegg[laston]);
                mail($rowegg['emailaddress'],"Uovo d'Oro",
                "Poichè non ti sei collegat".($session[user][sex]?"a":"o")." per 3 giorni (reali), gli dei ti hanno tolto l'Uovo d'Oro, per consentire agli altri giocatori di usufruire delle sue proprietà taumaturgiche.",
                "From: LoGD OGSI"
                );
            }
        }
    }
    //Fine Perdita automatica Uovo d'oro in caso di non login per più di 3 giorni reali

    //Perdita automatica Uovo d'oro in caso di possesso prolungato dello stesso
    if ($egggold == $session['user']['acctid']) {
       //echo "Variabile UovoOro: ".getsetting("giorniuovo",0);
       savesetting("giorniuovo",(getsetting("giorniuovo",0)+5));
       if (getsetting("giorniuovo",0) > e_rand(20,80)){
           output("`n`GGli `SDei`G di `@Rafflingate`G hanno deciso che è da troppo tempo che sei in possesso dell'uovo ");
           output("d'`^Oro`G e pertanto te lo sottraggono e lo restituiscono a mamma Grifone, ");
           output("perchè qualche altro giocatore possa trovarlo e sfruttarlo meglio di quanto hai fatto tu!!`n");
           addnews("`%Gli dei hanno sottratto l'`^Uovo d'Oro `%a `@".$session['user']['name']." `%per restituirlo a mamma Grifone !!");
           debuglog("`^perde l'uovo d'oro perchè non l'ha utilizzato per ".(getsetting("giorniuovo",0)/5)." newday`0");
           savesetting("hasegg",stripslashes(0));
           savesetting("giorniuovo",stripslashes(0));
       }
    }
    //Fine perdita automatica Uovo d'oro in caso di possesso prolungato dello stesso

    //militari
    $sql = "SELECT * FROM caserma WHERE acctid = '".$session['user']['acctid']."'";
    $result = db_query($sql) or die(db_error(LINK));
    $row = db_fetch_assoc($result);
    if($row['corso']!=0){
        if($row['durata_corso']==1){
            $sql = "SELECT * FROM caserma_corsi WHERE id = '".$row['corso']."'";
            $resultc = db_query($sql) or die(db_error(LINK));
            $rowc = db_fetch_assoc($resultc);
            $difesa=$row['difesa']+$rowc['difesa'];
            $attacco=$row['attacco']+$rowc['attacco'];
            $strategia=$row['strategia']+$rowc['strategia'];
            $sqlupdate = "UPDATE caserma SET lezione_privata='No',difesa='$difesa',strategia='$strategia',attacco='$attacco',corso = '0',durata_corso = '0' WHERE acctid='".$session['user']['acctid']."'";
            db_query($sqlupdate) or die(db_error(LINK));
            $mailmessage = "`^Hai completato il corso militare, forse è il caso di iniziarne uno nuovo!`n";
            systemmail($session['user']['acctid'],"`2Corso militare completato.`2",$mailmessage);
            $sql = "INSERT INTO caserma_corsi_fatti (acctid,id_corso) VALUES ('".$session['user']['acctid']."','".$row['corso']."')";
            $result=db_query($sql);
        }else{
            $durata = $row['durata_corso']-1;
            $sqlupdate = "UPDATE caserma SET lezione_privata='No',durata_corso = '$durata' WHERE acctid='".$session['user']['acctid']."'";
            db_query($sqlupdate) or die(db_error(LINK));
        }
    }

    //begin voodoo code
    //controllo se c'è un incantesimo che colpisce il giocatore
    $sql= "SELECT * FROM voodoo WHERE target = ".$session['user']['acctid'];
    $result = db_query($sql) or die(db_error(LINK));
    $row = db_fetch_assoc($result);
    $curse = $row['spell'];
    if ($curse != ""){
        switch($curse){
            case "poverty":
                output("`n`4Hai ricevuto una Maledizione di Povertà!  Perdi 500 pezzi d'oro!`0`n`n");
                $session['user']['goldinbank']-=500;
                debuglog("ha subito una Maledizione della Povertà e perde 500 pezzi d'oro in banca");
                if($session['user']['goldinbank'] < 0) $session['user']['goldinbank']=0;
                break;
            case "ugliness":
                output("`n`4Hai ricevuto una Maledizione di Bruttezza!  Perdi 2 Punti Fascino!`0`n`n");
                $session['user']['charm']-=2;
                debuglog("ha subito una Maledizione della Bruttezza e perde 2 Punti Fascino");
                break;
            case "sleep":
                output("`n`4Hai ricevuto una Maledizione di Lentezza!  Perdi 3 Combattimenti nella Foresta!`0`n`n");
                $session['user']['turns']-=3;
                $session['user']['turnimax'] = $session['user']['turns'];
                debuglog("ha subito una Maledizione della Lentezza e perde 3 turni");
                break;
            case "weak":
                if ($session['user']['maxhitpoints'] > (10 * $session['user']['level'])) {
                    output("`n`4Hai ricevuto una Maledizione di Debolezza!  Perdi 1 Hitpoint Permanente!`0`n`n");
                    $session['user']['maxhitpoints']-=1;
                    debuglog("ha subito una Maledizione della Debolezza e perde 1 Hitpoint Permanente");
                } else {
                    output("`n`4Hai ricevuto una Maledizione di Debolezza! Ma sei già abbastanza debole, perdi solo 1 Hitpoint Temporaneo!`0`n`n");
                    debuglog("ha subito una Maledizione della Debolezza ma avendo pochi hp perde 1 hp temporaneo");
                }
                $session['user']['hitpoints']-=1;
                break;
            case "death":
                output("`n`$Hai ricevuto una Maledizione di Morte!  Sei Mort".($session[user][sex]?"a":"o")."!`0`n`n");
                $session['user']['hitpoints']=0;
                debuglog("ha subito una Maledizione della Povertà e muore al newday");
                break;
            case "drunk":
                output("`n`4Hai ricevuto una Maledizione di Ebbrezza!  Sei completamente ubriac".($session[user][sex]?"a":"o")."!`0`n`n");
                $session['user']['drunkenness']=80;
                debuglog("ha subito una Maledizione dell'Ebbrezza e si sveglia ubriaco");
                break;
            case "wealth":
                output("`n`@Hai ricevuto un Incantesimo di Ricchezza!  Guadagni 500 pezzi d'oro!`0`n`n");
                $session['user']['gold']+=500;
                debuglog("ha subito un Incantesimo della Ricchezza 500 pezzi d'oro in banca");
                break;
            case "beauty":
                output("`n`@Hai ricevuto un Incantesimo di Bellezza!  Guadagni 3 Punti Fascino!`0`n`n");
                $session['user']['charm']+=3;
                debuglog("ha subito una Incantesimo della Bellezza e guadagna 3 Punti Fascino");
                break;
            case "vitality":
                output("`n`@Hai ricevuto un Incantesimo di Resistenza!  Guadagni 3 Combatimenti nella Foresta!`0`n`n");
                $session['user']['turns']+=3;
                $session['user']['turnimax'] = $session['user']['turns'];
                debuglog("ha subito un Incantesimo della Resistenza e guadagna 3 turni");
                break;
            case "strength":
                output("`n`@Hai ricevuto un Incantesimo di Vigore! Guadagni 1 Hitpoint Permanente!`0`n`n");
                $session['user']['maxhitpoints']+=1;
                $session['user']['hitpoints']+=1;
                debuglog("ha subito un Incantesimo del Vigore e guadagna 1 Hitpoint Permanente");
                break;
                //Sook, Riduzione dell'Attacco e della Difesa
            case "att":
                output("`n`4Hai ricevuto un Incantesimo di Riduzione dell'Attacco! Perdi 1 Punto Attacco Temporaneo!`0`n`n");
                //controllo punti precedenti la reincarnazione, arma e livello
                $rif = $session['user']['bonusattack'] + $session['user']['weapondmg'] + $session['user']['level'];
                if ($session['user']['oggetto'] != 0) {
                    $oggetto = $session['user']['oggetto'];
                    $sqlogg = "SELECT * FROM oggetti WHERE id_oggetti = $oggetto";
                    //controllo bonus dall'oggetto in mano
                    $resultogg = db_query($sqlogg) or die(db_error(LINK));
                    $rowogg = db_fetch_assoc($resultogg);
                    $rif += $rowogg[attack_help];
                }
                //controllo Dragon Points
                while (list($key, $val) = each($session['user']['dragonpoints'])) {
                    if ($val == "at") {
                        $rif++;
                    }
                }
                //controllo razza
                if ($session['user']['race'] == 1 || $session['user']['race'] == 6 || $session['user']['race'] == 11 || $session['user']['race'] == 13 || $session['user']['race'] == 15 || $session['user']['race'] == 21) $rif++;
                if ($session['user']['race'] == 7 || $session['user']['race'] == 14 || $session['user']['race'] == 17 || $session['user']['race'] == 19) $rif+=2;
                if ($session['user']['race'] == 16 || $session['user']['race'] == 20 || $session['user']['race'] == 22) $rif+=3;
                if ($session['user']['race'] == 9 || $session['user']['race'] == 10) $rif--;
                //non si può scendere sotto il 5% dei punti permanenti posseduti
                $rif -= intval($rif/20);
                if ($session['user']['attack'] > $rif) {
                    $session['user']['attack']-=1;
                    debuglog("ha subito un Incantesimo di Riduzione dell'Attacco e perde 1 Punto Attacco Temporaneo");
                } else {
                    output("`@Fortunatamente per te, il tuo attacco è già abbastanza basso, e gli dei impediscono che venga abbassato ulteriormente.`0`n`n");
                    debuglog("ha subito un Incantesimo di Riduzione dell'Attacco ma non ha perso nulla.");
                }
                break;
            case "dif":
                output("`n`4Hai ricevuto un Incantesimo di Riduzione della Difesa! Perdi 1 Punto Difesa Temporaneo!`0`n`n");
                $rif = $session['user']['bonusdefence'] + $session['user']['armordef'] + $session['user']['level'];
                if ($session['user']['oggetto'] != 0) {
                    $oggetto = $session['user']['oggetto'];
                    $sqlogg = "SELECT * FROM oggetti WHERE id_oggetti = $oggetto";
                    //controllo bonus dall'oggetto in mano
                    $resultogg = db_query($sqlogg) or die(db_error(LINK));
                    $rowogg = db_fetch_assoc($resultogg);
                    $rif += $rowogg[defence_help];
                }
                while (list($key, $val) = each($session['user']['dragonpoints'])) {
                    if ($val == "de") {
                        $rif++;
                    }
                }
                if ($session['user']['race'] == 2 || $session['user']['race'] == 6 || $session['user']['race'] == 7 || $session['user']['race'] == 10 || $session['user']['race'] == 14 || $session['user']['race'] == 15 || $session['user']['race'] == 18 || $session['user']['race'] == 20) $rif++;
                if ($session['user']['race'] == 16 || $session['user']['race'] == 17 || $session['user']['race'] == 19) $rif+=2;
                if ($session['user']['race'] == 8 || $session['user']['race'] == 9 || $session['user']['race'] == 11) $rif--;
                $rif -= intval($rif/20);
                if ($session['user']['defence'] > $rif) {
                    $session['user']['defence']-=1;
                    debuglog("ha subito un Incantesimo di Riduzione della Difesa e perde 1 Punto Difesa Temporaneo");
                } else {
                    output("`@Fortunatamente per te, la tua difesa è già abbastanza bassa, e gli dei impediscono che venga abbassata ulteriormente.`0`n`n");
                    debuglog("ha subito un Incantesimo di Riduzione della Difesa ma non ha perso nulla.");
                }
                break;
            case "attmax":
                output("`n`4Hai ricevuto un Incantesimo di Risucchio dell'Attacco! Perdi `\$`bTUTTI`b `4 i tuoi Punti Attacco Temporanei!`0`n`n");
                $rif = $session['user']['bonusattack'] + $session['user']['weapondmg'] + $session['user']['level'];
                if ($session['user']['oggetto'] != 0) {
                    $oggetto = $session['user']['oggetto'];
                    $sqlogg = "SELECT * FROM oggetti WHERE id_oggetti = $oggetto";
                    //controllo bonus dall'oggetto in mano
                    $resultogg = db_query($sqlogg) or die(db_error(LINK));
                    $rowogg = db_fetch_assoc($resultogg);
                    $rif += $rowogg[attack_help];
                }
                while (list($key, $val) = each($session['user']['dragonpoints'])) {
                    if ($val == "at") {
                        $rif++;
                    }
                }
                if ($session['user']['race'] == 1 || $session['user']['race'] == 6 || $session['user']['race'] == 11 || $session['user']['race'] == 13 || $session['user']['race'] == 15) $rif++;
                if ($session['user']['race'] == 7 || $session['user']['race'] == 14 || $session['user']['race'] == 17 || $session['user']['race'] == 19) $rif+=2;
                if ($session['user']['race'] == 16 || $session['user']['race'] == 20) $rif+=3;
                if ($session['user']['race'] == 9 || $session['user']['race'] == 10) $rif--;
                if ($session['user']['attack'] > $rif) {
                    $session['user']['attack'] = $rif;
                    debuglog("ha subito un Incantesimo di Risucchio dell'Attacco e perde TUTTI i punti attacco temporanei! Ora ha attacco ".$rif);
                } else {
                    if ($rif>80 AND e_rand(1,(2+$session['user']['reincarna'])) == 1) {
                        output("`n`4Hai ricevuto un Incantesimo di Risucchio dell'Attacco! Perdi 1 Punto Attacco `\$`bPermanente`b`4!`0`n`n");
                        $session['user']['attack'] --;
                        $session['user']['bonusattack'] --;
                        debuglog("ha subito un Incantesimo di Risucchio dell'Attacco e perde 1 punto attacco permanente! Ora ha attacco ".($rif-1));
                    } else {
                        output("`@Fortunatamente per te, non hai punti attacco temporanei, e gli dei preservano i tuoi punti permanenti, quindi non hai perso nulla.`0`n`n");
                        debuglog("ha subito un Incantesimo di Risucchio dell'Attacco ma non ha perso nulla.");
                    }
                }
                break;
            case "difmax":
                output("`n`4Hai ricevuto un Incantesimo di Risucchio della Difesa! Perdi `\$`bTUTTI`b `4 i tuoi Punti Difesa Temporanei!`0`n`n");
                $rif = $session['user']['bonusdefence'] + $session['user']['armordef'] + $session['user']['level'];
                if ($session['user']['oggetto'] != 0) {
                    $oggetto = $session['user']['oggetto'];
                    $sqlogg = "SELECT * FROM oggetti WHERE id_oggetti = $oggetto";
                    //controllo bonus dall'oggetto in mano
                    $resultogg = db_query($sqlogg) or die(db_error(LINK));
                    $rowogg = db_fetch_assoc($resultogg);
                    $rif += $rowogg[defence_help];
                }
                while (list($key, $val) = each($session['user']['dragonpoints'])) {
                    if ($val == "de") {
                        $rif++;
                    }
                }
                if ($session['user']['race'] == 2 || $session['user']['race'] == 6 || $session['user']['race'] == 7 || $session['user']['race'] == 10 || $session['user']['race'] == 14 || $session['user']['race'] == 15 || $session['user']['race'] == 18 || $session['user']['race'] == 20) $rif++;
                if ($session['user']['race'] == 16 || $session['user']['race'] == 17 || $session['user']['race'] == 19) $rif+=2;
                if ($session['user']['race'] == 8 || $session['user']['race'] == 9 || $session['user']['race'] == 11) $rif--;
                if ($session['user']['defence'] > $rif) {
                    $session['user']['defence'] = $rif;
                    debuglog("ha subito un Incantesimo di Risucchio della Difesa e perde TUTTI i punti difesa temporanei! Ora ha difesa ".$rif);
                } else {
                    if ($rif>80 AND e_rand(1,(2+$session['user']['reincarna'])) == 1) {
                        output("`n`4Hai ricevuto un Incantesimo di Risucchio della Difesa! Perdi 1 Punto Difesa `\$`bPermanente`b`4!`0`n`n");
                        $session['user']['defence'] --;
                        $session['user']['bonusdefence'] --;
                        debuglog("ha subito un Incantesimo di Risucchio della Difesa e perde 1 punto difesa permanente! Ora ha difesa ".($rif-1));
                    } else {
                        output("`@Fortunatamente per te, non hai punti attacco temporanei, e gli dei preservano i tuoi punti permanenti, quindi non hai perso nulla.`0`n`n");
                        debuglog("ha subito un Incantesimo di Risucchio dell'Attacco ma non ha perso nulla.");
                    }
                }
                break;
            default:
                output("`n`7Oggi ti senti un po' stran".($session[user][sex]?"a":"o").", ma non sai come mai...`0`n`n");
                break;
        }
    }
    $sqle = "DELETE FROM voodoo WHERE target = '".$session['user']['acctid']."'";
    db_query($sqle);
    //end voodoo code

    //Modifica per Medaglioni
    if ($session['user']['spirits'] != -6){

        $session['user']['medfind']=e_rand(8,12);
        if ($session['user']['medhunt']>0){
            $session['user']['medhunt']=1;
        }
        //Fine Modifica per Medaglioni

        //Modifica pietre della Fonte di Aris
        $owner1=$session['user']['acctid'];
        $sql="SELECT * FROM pietre WHERE owner=$owner1";
        $result = db_query($sql);
        $pot = db_fetch_assoc($result);
        if (db_num_rows($result) != 0) {
            $flagstone=$pot['pietra'];
            switch ($flagstone) {
                case 1:
                    output("`n`n`%Poichè possiedi la {$pietre[$flagstone]}`% della `&Fonte di Aris`% perdi un combattimento supplementare !`n");
                    $session['user']['turns']-=1;
                    $session['user']['turnimax'] = $session['user']['turns'];
                    break;

                case 2:
                    output("`n`n`%Poichè possiedi la {$pietre[$flagstone]}`% della `&Fonte di Aris`% guadagni un punto di fascino !`n");
                    $session['user']['charm']+=1;
                    break;

                case 3:
                    output("`n`n`%Poichè possiedi la {$pietre[$flagstone]}`% della `&Fonte di Aris`% guadagni un combattimento supplementare !`n");
                    $session['user']['turns']+=1;
                    $session['user']['turnimax'] = $session['user']['turns'];
                    break;

                case 4:
                    output("`n`n`%Poichè possiedi la {$pietre[$flagstone]}`% della `&Fonte di Aris`% guadagni 300 pezzi d'oro!`n");
                    $session['user']['gold']+=300;
                    break;

                case 5:
                    output("`n`n`%Poichè possiedi la {$pietre[$flagstone]}`% della `&Fonte di Aris`% guadagni potenza in attacco!`n");
                    $session[bufflist][120] = array("name"=>"{$pietre[$flagstone]}","rounds"=>200,"wearoff"=>"`4La luminescenza scompare dalla tua {$pietre[$flagstone]}.","atkmod"=>1.3,"roundmsg"=>"`4La {$pietre[$flagstone]} potenzia il tuo attacco!","activate"=>"offense");
                    break;

                case 6:
                    output("`n`n`%Poichè possiedi la {$pietre[$flagstone]}`% della `&Fonte di Aris`% la tua difesa viene potenziata!`n");
                    $session[bufflist][120] = array("name"=>"{$pietre[$flagstone]}","rounds"=>200,"wearoff"=>"`4La luminescenza scompare dalla tua {$pietre[$flagstone]}.","defmod"=>1.3,"roundmsg"=>"`4La {$pietre[$flagstone]} potenzia la tua difesa!","activate"=>"offense");
                    break;

                case 7:
                    output("`n`n`%Poichè possiedi la {$pietre[$flagstone]}`% della `&Fonte di Aris`% attacchi e difendi meglio!`n");
                    $session[bufflist][120] = array("name"=>"{$pietre[$flagstone]}","rounds"=>200,"wearoff"=>"`4La luminescenza scompare dalla tua {$pietre[$flagstone]}.","atkmod"=>1.3,"defmod"=>1.3,"roundmsg"=>"`4La {$pietre[$flagstone]} acuisce le tue capacità!","activate"=>"offense");
                    break;

                case 8:
                    output("`n`n`%Poichè possiedi la {$pietre[$flagstone]}`% della `&Fonte di Aris`% acquisisci capacità supplementari in alcune arti!`n");
                    $session['user']['darkartuses']+=3;
                    $session['user']['magicuses']+=3;
                    $session['user']['thieveryuses']+=3;
                    $session['user']['militareuses']+=3;
                    break;

                case 9:
                    output("`n`n`%Poichè possiedi la {$pietre[$flagstone]}`% della `&Fonte di Aris`% guadagni un combattimento supplementare!`n");
                    $session['user']['turns']+=1;
                    $session['user']['turnimax'] = $session['user']['turns'];
                    break;

                case 10:
                    output("`n`n`%Poichè possiedi la {$pietre[$flagstone]}`% della `&Fonte di Aris`% ti senti meno colpevole (perdi alcuni punti cattiveria)!`n");
                    $session['user']['evil']-=5;
                    break;

                case 11:
                    output("`n`n`%Poichè possiedi la {$pietre[$flagstone]}`% della `&Fonte di Aris`% guadagni 200 pezzi d'oro!`n");
                    $session['user']['gold']+=200;
                    break;

                case 12:
                    output("`n`n`%Poichè possiedi la {$pietre[$flagstone]}`% della `&Fonte di Aris`% guadagni 500 pezzi d'oro!`n");
                    $session['user']['gold']+=500;
                    break;

                case 13:
                    output("`n`n`%Poichè possiedi la {$pietre[$flagstone]}`% della `&Fonte di Aris`% guadagni 800 pezzi d'oro!`n");
                    $session['user']['gold']+=800;
                    break;

                case 14:
                    output("`n`n`%Poichè possiedi la {$pietre[$flagstone]}`% della `&Fonte di Aris`% attacchi e difendi meglio!`n");
                    $session[bufflist][120] = array("name"=>"{$pietre[$flagstone]}","rounds"=>500,"wearoff"=>"`4La luminescenza scompare dalla tua {$pietre[$flagstone]}.","atkmod"=>1.5,"defmod"=>1.5,"roundmsg"=>"`4La {$pietre[$flagstone]} acuisce le tue capacità!.","activate"=>"offense");
                    break;

                case 15:
                    if ($session['user']['spirits'] > -6) {
                        output("`n`n`%Poichè possiedi la {$pietre[$flagstone]}`% della `&Fonte di Aris`% guadagni favori con Ramius!`n");
                        $session['user']['deathpower']+=100;
                    }
                    break;

                case 16:
                    output("`n`n`%Poichè possiedi la {$pietre[$flagstone]}`% della `&Fonte di Aris`% sei ubriac".($session[user][sex]?"a":"o")." !`n");
                    $session['user']['drunkenness']+=66;
                    break;

                case 17:
                    output("`n`n`%Poichè possiedi la {$pietre[$flagstone]}`% della `&Fonte di Aris`% guadagni due combattimenti supplementari!`n");
                    $session['user']['turns']+=2;
                    $session['user']['turnimax'] = $session['user']['turns'];
                    break;

                case 18:
                    output("`n`n`%Poichè possiedi la {$pietre[$flagstone]}`% della `&Fonte di Aris`% ti senti più pur".($session[user][sex]?"a":"o")." (perdi alcuni punti cattiveria)!`n");
                    $session['user']['evil']-=3;
                    break;

                case 19:
                    output("`n`n`%Poichè possiedi la {$pietre[$flagstone]}`% della `&Fonte di Aris`% guadagni un combattimento supplementare!`n");
                    $session['user']['turns']+=1;
                    $session['user']['turnimax'] = $session['user']['turns'];
                    break;

                case 20:
                    output("`n`n`%Poichè possiedi la {$pietre[$flagstone]}`% della `&Fonte di Aris`% guadagni una gemma!`n");
                    $session['user']['gems']+=1;
                    break;
            }
        }
        //fine modifica pietre


    }
    //Visualizzazione oggetti persi al DK
    $sqlogg = "SELECT comment,author FROM commentary WHERE section = 'Perdita Oggetti' ORDER BY postdate DESC";
    $resultogg = db_query($sqlogg) or die(db_error(LINK));
    $limit = db_num_rows($resultogg);
    if ($limit > 3) $limit = 3;
    for ($i=0;$i < $limit;$i++) {
        $rowogg = db_fetch_assoc($resultogg);
        $idogg = $rowogg['author'];
        $sqlveri = "SELECT dove,dove_origine FROM oggetti WHERE id_oggetti = '$idogg'";
        $resultveri = db_query($sqlveri) or die(db_error(LINK));
        $rowveri = db_fetch_assoc($resultveri);
        if ($rowveri['dove'] != 0 AND $rowveri['dove_origine'] < 10) {
            $value = stripslashes($rowogg['comment']);
            output("`n<font size='+1'>".$value."</font>`n",true);
            output("`@Quale migliore occasione per fare un salto da Brax e vedere se l'articolo può interessarti ?");
        }
    }
    //Fine visualizzazione

    if (
    strtotime(
    getsetting("lastdboptimize",
    date("Y-m-d H:i:s",
    strtotime(date("r")."-1 day")
    )
    )
    ) < strtotime(date("r")."-1 day")
    ) {
        savesetting("lastdboptimize", date("Y-m-d H:i:s"));
        $result = db_query("SHOW TABLES");
        $countrow = db_num_rows($result);
        for ($i=0; $i<$countrow; $i++){
        //for ($i = 0;$i < db_num_rows($result);$i++) {
            list($key, $val) = each(db_fetch_assoc($result));
            db_query("OPTIMIZE TABLE $val");
        }
    }
//elezione striscione
if(getsetting("striscione_asta","no")==no){
    if(date('G')==0){
        savesetting("striscione_offerta",'0');
        savesetting("striscione_acctid",'0');
        savesetting("striscione_testo",'');
        savesetting("striscione_asta","si");
    }
}else{
    if(date('G')>=1){
        savesetting("striscione_asta","no");
    }
}
//strisione sindaco reset
if(getsetting("stri_sin_data",(strtotime(date("r"))))<(strtotime("now"))){
    savesetting("striscione_sindaco","no");
}
// elezione sindaco
if(getsetting("elezione_sindaco","no")==no){
    if(date('d')==5){
        $sql = "SELECT acctid FROM elezione_candidati order by voti desc";
        $result = db_query($sql);
        $row = db_fetch_assoc($result);
        savesetting("sindaco",$row[acctid]);
        db_query("DELETE FROM elezione_candidati");
        db_query("DELETE FROM elezione_votanti");
        db_query("DELETE FROM municipio_voce");
        db_query("DELETE FROM municipio_approvazione");
        savesetting("elezione_sindaco","si");
    }
}else{
    if(date('d')==1){
        savesetting("elezione_sindaco","no");
    }
}
//fine elezione sindaco
//esattore
//tasse per municipio
if($session['user']['cittadino']=="Si" AND $session['user']['superuser']==0){
    $tasse = getsetting("tasse",'2');
    $sqlupdate = "UPDATE tasse SET oro = oro+$tasse WHERE acctid='".$session['user']['acctid']."'";
    db_query($sqlupdate) or die(db_error(LINK));
    if (db_affected_rows()==0){
        $sqlupdate = "INSERT INTO tasse (acctid,oro) VALUES('".$session['user']['acctid']."','".$tasse."')";
        db_query($sqlupdate) or die(db_error(LINK));
        report(3,"Aggiunto record tasse","Aggiunto il record tasse al personaggio ".$session['user']['name'],"tasse");
    }
    // fine tasse
    $esattore = getsetting("esattore",'0');
    if ($esattore!=0){
        $sql = "SELECT * FROM tasse WHERE acctid='".$session['user']['acctid']."'";
        $result = db_query($sql) or die(db_error(LINK));
        $row = db_fetch_assoc($result);
        if ($row[oro]>$esattore){
            $buff = array("name"=>"`\$Esattore infuriato!`0",
            "atkmod"=>0.95,
            "defmod"=>0.95,
            "survivenewday"=>1,
            "rounds"=>100,
            "roundmsg"=>"`\$Esattore asfissiante vuole i soldi per le tasse!`0",
            "activate"=>"offense"
            );
            $session['bufflist']['esattore']=$buff;
        }
    }
}
//fine esattore
//bonus sindaco
if(getsetting("sindaco",'')==$session['user']['acctid']){
    $buff = array("name"=>"`\$Potere occulto del sindaco`0",
    "atkmod"=>1.15,
    "survivenewday"=>1,
    "rounds"=>-1,
    "roundmsg"=>"`\$Potere occulto del sindaco!`0",
    "activate"=>"offense"
    );
    $session['bufflist']['bonus_sindaco']=$buff;
    //evento
    $caso = e_rand(1,100);
    $evento = getsetting("evento_sindaco",'0');
    $day_evento = getsetting("scadenza_evento_s",'0');
    $day=$day_evento-1;
    savesetting("scadenza_evento_s","$day");
    output("giorni : $day_evento,$day");
    if ($evento==0){
        if ($caso>80){
            if (getsetting("relazione_eythgim",50)<30){
                $sql = "SELECT * FROM eventi WHERE guerra='Si' ORDER BY RAND() LIMIT 1";
                $caso1 = e_rand(1,50);
                $caso2 = e_rand(1,3);
                if($caso2==1){
                    if($caso1>getsetting("relazione_eythgim",50)){
                        //savesetting("guerra_eythgim","".e_rand(1,50)."");
                        //script guerra
                    }
                }
            }else{
                $sql = "SELECT * FROM eventi WHERE guerra='No' ORDER BY RAND() LIMIT 1";
            }
            $result = db_query($sql) or die(db_error(LINK));
            $row = db_fetch_assoc($result);
            savesetting("evento_sindaco","$row[id]");
            savesetting("scadenza_evento_s","$row[scadenza]");
            $mailmessage = "`^Sindaco un evento richiede la tua attenzione vai nel tuo ufficio!`n";
            systemmail($session['user']['acctid'],"`2Evento sindaco.`2",$mailmessage);
        }
    }else{
        if(getsetting("scadenza_evento_s",'0')==0){
            $mailmessage = "`^Sindaco è scaduto il tempo per un evento, ne pagherai le conseguenze!`n";
            systemmail($session['user']['acctid'],"`2Fallito evento sindaco.`2",$mailmessage);
            savesetting("evento_sindaco","0");
        }
    }
    //titolo sindaco

    $name=$session['user']['name'];
    $y = substr($session['user']['name'], 0, 1);
    if ($y == "`") {
        $newtitle = substr($session['user']['name'], 0, 2)."Sindaco";
    }else{
        $newtitle="`@Sindaco`0";
    }
    $n = $session['user']['name'];
    $x = strpos($n,$session['user']['title']);
    if ($x!==false){
        $regname=substr($n,$x+strlen($session['user']['title']));
        $session['user']['name'] = substr($n,0,$x).$newtitle.$regname;
        $session['user']['title'] = $newtitle;
    }else{
        $regname = $session['user']['name'];
        $session['user']['name'] = $newtitle." ".$session['user']['name'];
        $session['user']['title'] = $newtitle;
    }

}
    //luke turni bonus
    if (donazioni('turni')==true AND donazioni_usi('turni')>0){
        $session['user']['turns']+=5;
        $session['user']['turnimax'] = $session['user']['turns'];
        output("`n`#Anche oggi hai 5 turni in più acquistati con i punti donazione!`n`n");
        $usi=donazioni_usi('turni')-1;
        donazioni_usi_up('turni',$usi);
        if(donazioni_usi('turni')==0){
            output("`\$Questo era il 30 giorno che hai sfruttato i 5 turni addizionali, purtroppo dovrai riaquistarli al capanno di caccia!!`n`n");
            $sqle = "DELETE FROM donazioni WHERE nome='turni' AND idplayer='".$session['user']['acctid']."'";
            db_query($sqle);
            $mailmessage = "`\$Questo era il 30 giorno che hai sfruttato i 5 turni addizionali, purtroppo dovrai riaquistarli al capanno di caccia!!";
            systemmail($session['user']['acctid'],"`2Esaurito turni extra.`2",$mailmessage);
        }
    }
    if (donazioni('polvere_fata')==true){
        $buff = array("name"=>"`\$Protezione della Fata",
        "rounds" => 250,
        "wearoff"=>"`!Il potere della fata si esaurisce!",
        "defmod"=> 1.2,
        "roundmsg"=>"Ti senti protetto!",
        "activate"=>"defense"
        );
        $session['bufflist']['fata'] = $buff;
        output("`n`%Ti ricordi di cospargerti con un pò di polvere magica della fata, ed una leggera aura azzurra ti circonda!`n`n");
        $usi=donazioni_usi('polvere_fata')-1;
        donazioni_usi_up('polvere_fata',$usi);
        if(donazioni_usi('polvere_fata')==0){
            output("`\$Hai esaurito la polvere di fata, purtroppo dovrai riaquistarla al capanno!!`n`n");
            $sqle = "DELETE FROM donazioni WHERE nome = 'polvere_fata' AND idplayer='".$session['user']['acctid']."'";
            db_query($sqle);
            $mailmessage = "`\$Nooooo!! La polvere di fata è finita, dovrai ricomprarla al capanno del cacciatore!";
            systemmail($session['user']['acctid'],"`2Noooooo!.`2",$mailmessage);
        }
    }
    if (donazioni('sconto_cura')==true){
        if(donazioni_usi('sconto_cura')>0){
            $usi=donazioni_usi('sconto_cura')-1;
            donazioni_usi_up('sconto_cura',$usi);
            if (donazioni_usi('sconto_cura')==0){
                $mailmessage = "`\$Questo è l'ultimo giorno della tua Golinda card, purtroppo dovrai rinnovarla per solo 100 punti donazione al capanno di caccia!!";
                systemmail($session['user']['acctid'],"`2Scaduta Golinda card.`2",$mailmessage);
            }
        }
    }
    //fine luke

}

//Festa, Sook, 2° parte (applicazione buff)
if($festa==1 AND $session['user']['cittadino']=="Si" AND $session['user']['evil']<=75){
    output("`n`VOggi è un giorno di festa per Rafflingate! Sei così felice che l'adrenalina nel tuo corpo ti consente di attaccare con più efficacia i tuoi nemici!`0`n");
    $buff = array("name"=>"`\$Festa di Rafflingate`0",
    "atkmod"=>1.05,
    "defmod"=>1.05,
    "rounds"=>-1,
    "regen"=>1,
    "badguydmgmod"=>0.9,
    "roundmsg"=>"`(La voglia di tornare `bVIVO`b a Rafflingate per festeggiare ti fa combattere meglio!`0",
    "activate"=>"roundstart"
    );
    $session['bufflist']['festa']=$buff;
}
//Fine festa


//sword in the stone code
$sql="SELECT * FROM custom WHERE area='swordGuy'";
$result=db_query($sql) or die(db_error(LINK));
$dep = db_fetch_assoc($result);
$lastchose=strtotime($dep['dTime']);
$now=strtotime(date("H:i:s"));
$diff=$now-$lastchose;
$swordguy=0;
if ($diff>21600 OR $diff<0){
    addnews("`%La Spada nella Roccia ha scelto un nuovo Maestro");
    $sql="SELECT acctid FROM accounts WHERE superuser=0 ORDER BY RAND() LIMIT 1";
    $result = db_query($sql) or die(db_error(LINK));
    $dep = db_fetch_assoc($result);
    if (db_num_rows($result)>0) $swordguy=$dep['acctid'];
    //update time sword was goten
    $sql="UPDATE custom SET dTime = now(), dDate = now(), amount = $swordguy WHERE area = 'swordGuy'";
    $result = db_query($sql) or die(db_error(LINK));
}
$sql="SELECT * FROM custom WHERE area='swordGuy'";
$result=db_query($sql) or die(db_error(LINK));
$dep = db_fetch_assoc($result);
if ($session[user][acctid]==$dep['amount'])output("`n`n`@Oggi hai una strana sensazione ... come se fossi stato
scelt".($session[user][sex]?"a":"o")." per fare qualcosa di importante. Forse è il caso di indagare al villaggio ...");
//sword in the stone code
//Luke codice capanno di caccio gestione donazioni
if (donazioni('gigante_tempesta')==true AND $session['user']['race']==13){
    $buff = array("name"=>"`3Macigno del Gigante`0",
    "minioncount"=>1,
    "minbadguydamage"=>0,
    "maxbadguydamage"=>3+ceil($session['user']['level']/2),
    "effectmsg"=>"`#Sollevi il Macigno e lo cali pesantemente su {badguy}`# causandogli `^{damage}`# punti danno.",
    "effectnodmgmsg"=>"`#Il macigno viene lanciato contro {badguy}, ma lo `\$MANCA`)!",
    "defmod"=>1+((2+floor($session['user']['level']/3))/$session['user']['defence']),
    "survivenewday"=>1,
    "rounds"=>-1,
    "activate"=>"roundstart"
    );
    $session['bufflist']['gigante']=$buff;
}
if (donazioni('barbaro')==true AND $session['user']['race']==14){
    $buff = array("name"=>"`\$Rabbia Barbara`0",
    "atkmod"=>1.5,
    "survivenewday"=>1,
    "rounds"=>-1,
    "roundmsg"=>"`\$Essendo un barbaro, il tuo attacco è potenziato!`0",
    "activate"=>"offense"
    );
    $session['bufflist']['barbaro']=$buff;
}
if (donazioni('amazzone')==true AND $session['user']['race']==15){
    $buff = array("name"=>"`%Frecce dell'Amazzone`0",
    "startmsg"=>"`n`5Imbracci il tuo arco ed inizi a lanciare frecce contro {badguy}.`n`n",
    "minioncount"=>round($session['user']['level']/5)+1,
    "maxbadguydamage"=>$session['user']['level'],
    "survivenewday"=>1,
    "rounds"=>-1,
    "effectmsg"=>"`)Una freccia colpisce {badguy} causando `^{damage}`) punti danno.",
    "effectnodmgmsg"=>"`)Una freccia sfiora {badguy} ma `\$LO MANCA`)!",
    "activate"=>"offense"
    );
    $session['bufflist']['amazzone']=$buff;
}
if (donazioni('titano')==true AND $session['user']['race']==16 AND $session['user']['dio']==1){
    $buff = array("name"=>"`#Furia del Titano`0",
    "minioncount"=>2,
    "minbadguydamage"=>0,
    "maxbadguydamage"=>3+ceil($session['user']['level']/3),
    "effectmsg"=>"`#Sollevando le braccia al cielo raccogli una saetta che lanci contro {badguy}`#, infliggendogli `^{damage}`# punti danno.",
    "effectnodmgmsg"=>"`3Sollevando le braccia al cielo raccogli una saetta che lanci contro {badguy}`3, ma lo `\$MANCHI`)!",
    "defmod"=>1+((3+floor($session['user']['level']/3))/$session['user']['defence']),
    "survivenewday"=>1,
    "rounds"=>-1,
    "activate"=>"offense"
    );
    $session['bufflist']['centauro']=$buff;
}
if (donazioni('demone')==true AND $session['user']['race']==17 AND $session['user']['dio']==2){
    $buff = array("name"=>"`\$Fuoco Demoniaco`0",
    "minioncount"=>1,
    "minbadguydamage"=>0,
    "maxbadguydamage"=>3+ceil($session['user']['level']/1.5),
    "effectmsg"=>"`4Evochi un fuoco infernale che circonda {badguy}`#, provocandogli `^{damage}`# punti danno.",
    "effectnodmgmsg"=>"`4Cerchi di evocare un fuoco infernale contro {badguy}`3, ma riesci ad accendere una debole fiammella e `\$FALLISCI`)!",
    "defmod"=>1+((3+floor($session['user']['level']/3))/$session['user']['defence']),
    "survivenewday"=>1,
    "rounds"=>-1,
    "activate"=>"offense"
    );
    $session['bufflist']['centauro']=$buff;
}
if (donazioni('centauro')==true AND $session['user']['race']==18){
    $buff = array("name"=>"`(Zoccoli del Centauro`0",
    "minioncount"=>2,
    "minbadguydamage"=>0,
    "maxbadguydamage"=>2+ceil($session['user']['level']/4),
    "effectmsg"=>"`(Come un cavallo imbizzarrito ti sollevi sulle zampe posteriori e colpisci {badguy}`( con gli zoccoli per `#{damage}`( punti danno.",
    "effectnodmgmsg"=>"`#Cerchi di colpire {badguy} con i tuoi zoccoli, ma lo `\$MANCHI`)!",
    "defmod"=>1+((2+floor($session['user']['level']/3))/$session['user']['defence']),
    "survivenewday"=>1,
    "rounds"=>-1,
    "activate"=>"roundstart"
    );
    $session['bufflist']['centauro']=$buff;
}
if (donazioni('licantropo')==true AND $session['user']['race']==19){

    // Excalibur: modifica per influenzare l'attacco speciale dei Licantropi in base alla fase lunare
    $dateAsTimeStamp = '';
    $mp = new moonPhase($dateAsTimeStamp);
    $faselunare = $mp->getPositionInCycle();
    if (0.53 >= $faselunare AND $faselunare >= 0.474){
        output("`n`n`b<big><big><big>`%È luna nuova, il tuo attacco speciale non è efficace come al solito!!</big></big></big>`n`n`b",true);
        $buffluna = 0.05;
    }elseif (0.974 <= $faselunare OR $faselunare <= 0.026){
        output("`n`n`b<big><big><big>`%È luna piena, il tuo attacco speciale è più efficace del solito!!</big></big></big>`b`n`n",true);
        $buffluna = -0.05;
    }
    // Excalibur: fine modifica per influenzare l'attacco speciale dei Licantropi in base alal fase lunare

    $buff = array("name"=>"`^Zanne Lunari`0",
    "lifetap"=>((0.3+$buffluna)-(0.013*$session['user']['level'])), //ratio of damage healed to damage dealt
    "effectmsg"=>"`6Azzannando {badguy}`6 ne bevi il suo sangue e vieni guarito per `#{damage}`6 HitPoint.",
    "effectnodmgmsg"=>"`6Senti un formicolio mentre il sangue di {badguy}`6 ripristina la salute del tuo corpo.",
    "effectfailmsg"=>"`4Tenti di bere il sangue del tuo avversario, ma tutto quello che riesci a bere è un sorso d'aria!",
    "defmod"=>1+((2+floor($session['user']['level']/3))/$session['user']['defence']),
    "survivenewday"=>1,
    "rounds"=>-1,
    "activate"=>"roundstart"
    );
    $session['bufflist']['licantropo']=$buff;
}
if (donazioni('minotauro')==true AND $session['user']['race']==20){
    $buff = array("name"=>"`(Zoccoli del Minotauro`0",
    "minioncount"=>2,
    "minbadguydamage"=>0,
    "maxbadguydamage"=>2+ceil($session['user']['level']/3),
    "effectmsg"=>"`(Attacchi {badguy}`( e lo colpisci con i tuoi zoccoli causando `#{damage}`( punti danno.",
    "effectnodmgmsg"=>"`#Cerchi di colpire {badguy} con i tuoi zoccoli, ma lo `\$MANCHI`)!",
    "defmod"=>1+((2+floor($session['user']['level']/3))/$session['user']['defence']),
    "survivenewday"=>1,
    "rounds"=>-1,
    "activate"=>"roundstart"
    );
    $session['bufflist']['minotauro']=$buff;
}
if (donazioni('chansonnier')==true AND $session['user']['race']==21){
    $buff = array("name"=>"`3Chitarra del Cantastorie`0",
    "minioncount"=>1,
    "minbadguydamage"=>0,
    "maxbadguydamage"=>3+ceil($session['user']['level']/3),
    "effectmsg"=>"`#Pizzichi delicatamente le corde della chitarra, e le note raggiungono {badguy}`# causandogli `^{damage}`# punti danno.",
    "effectnodmgmsg"=>"`#Strimpelli malamante la chitarra per ammaliare {badguy}`), ma la melodia non ha nessun effetto su di lui!",
    "defmod"=>1+((2+floor($session['user']['level']/3))/$session['user']['defence']),
    "survivenewday"=>1,
    "rounds"=>-1,
    "activate"=>"roundstart"
    );
    $session['bufflist']['chansonnier']=$buff;
}
if (donazioni('eletto')==true AND $session['user']['race']==22 AND $session['user']['dio']==3){
    $buff = array("name"=>"`@Respiro di Fuoco`0",
    "minioncount"=>1,
    "minbadguydamage"=>0,
    "maxbadguydamage"=>3+ceil($session['user']['level']/1.5),
    "effectmsg"=>"`2Esali un fiato di fuoco che colpisce {badguy}`#, provocandogli `^{damage}`# punti danno.",
    "effectnodmgmsg"=>"`4lanci il tuo respiro infuocato contro {badguy}`3, ma riesci solo a sbruciacchiargli qualche pelo e `\$FALLISCI`)!",
    "defmod"=>1+((3+floor($session['user']['level']/3))/$session['user']['defence']),
    "survivenewday"=>1,
    "rounds"=>-1,
    "activate"=>"offense"
    );
    $session['bufflist']['eletto']=$buff;
}
//Luke reset pass ambasciata draghi
$settimana_db=getsetting("pass_draghi",0);
$week = strftime("%U",time());
if($week!=$settimana_db){
    savesetting("pass_draghi", $week);
    $sql = "UPDATE donazioni SET usi=3 where nome = 'pass_draghi'";
    db_query($sql) or die(db_error(LINK));
}
//fine luke
//luke per fama mod
$fama=intval($session['user']['donation']/1000);
$euro=$session['user']['euro']/100;
$fama_mod=($fama*0.01)+1+$euro;
if($fama_mod>=1.5)$fama_mod=1.5;
$session['user']['fama_mod']=$fama_mod;
//output("$euro / $fama / $fama_mod");
//fine luke

$mese = strftime("%m",time());
$mese_db = getsetting("fama3mesi",'13');

//Sook, blocco punteggio delle chiese allo scadere del mese, congelamento dei premi e inizio nuovo torneo
if($mese!=$mese_db AND getsetting("puntisgrios",0)!=0 AND getsetting("puntikarnak",0)!=0 AND getsetting("puntidrago",0)!=0) {
    savesetting("puntisgriosfinemese", getsetting("puntisgrios",0));
    savesetting("puntikarnakfinemese", getsetting("puntikarnak",0));
    savesetting("puntidragofinemese", getsetting("puntidrago",0));
    savesetting("puntisgrios", "0");
    savesetting("puntikarnak", "0");
    savesetting("puntidrago", "0");
}
//Sook, blocco torneo delle medaglie allo scadere del mese, congelamento dei premi e inizio nuovo torneo
if($mese!=$mese_db) {
    $sql = "INSERT INTO medagliepremi (acctid,medpoints) SELECT acctid,medpoints FROM accounts WHERE medhunt>0 AND superuser = 0";
    $result = db_query($sql) or die(db_error(LINK));
/*  $sql = "SELECT acctid,medpoints FROM accounts WHERE medhunt>0 AND superuser = 0";
    $result = db_query($sql) or die(db_error(LINK));
    for ($i = 0;$i < db_num_rows($result);$i++) {
        $row = db_fetch_assoc($result);
        $sql2 = "INSERT INTO medagliepremi VALUES('".$row['acctid']."','".$row['medpoints']."')";
        $result2 = db_query($sql2) or die(db_error(LINK));
    }
*/  $sql3 = "UPDATE accounts SET medallion='0', medhunt='0', medpoints='0', medfind='0'";
    db_query($sql3);
}
//Sook, blocco torneo di LoGD allo scadere del mese, congelamento dei premi e inizio nuovo torneo
if($mese!=$mese_db) {
    $sql = "INSERT INTO torneopremi (acctid,torneo,torneopoints) SELECT acctid,torneo,torneopoints FROM accounts WHERE torneo>0 AND superuser = 0";
    $result = db_query($sql) or die(db_error(LINK));
    $sql3 = "UPDATE accounts SET torneo='0', torneopoints=''";
    db_query($sql3);
}

//Luke per reset classifica calcolo vincitori e assegnazione premi
if($session['user']['superuser']>0)output("Mese: $mese Mese_DB: $mese_db");
if(($mese!=$mese_db AND $mese==01) OR ($mese!=$mese_db AND $mese==04) OR ($mese!=$mese_db AND $mese==07) OR ($mese!=$mese_db AND $mese==10)){
    $sql = "SELECT acctid,fama3mesi FROM accounts WHERE superuser = 0 ORDER BY fama3mesi DESC";
    $result = db_query($sql) or die(db_error(LINK));
    $pos=1;
    $countrow = db_num_rows($result);
    for ($i=0; $i<$countrow; $i++){
    //for ($i = 0;$i < db_num_rows($result);$i++) {
        $row = db_fetch_assoc($result);
        $ultimo_reset = getsetting("fama3mesi_reset",'13');
        $sql = "SELECT ultimo_reset FROM fama3mesi WHERE acctid = '".$row['acctid']."'";
        $resultr = db_query($sql) or die(db_error(LINK));
        $rowr = db_fetch_assoc($resultr);
        if($ultimo_reset==$rowr[ultimo_reset]){
            $acctid[]=$row[acctid];
            $fama3mesi[]=$row[fama3mesi];
            if($pos==3)break;
            $pos+=1;
        }
    }
    $sql = "INSERT INTO vincitori_fama
              (acctid1,acctid2,acctid3,data,punti1,punti2,punti3)
              VALUES('".$acctid[0]."',
                '".$acctid[1]."',
             '".$acctid[2]."',
              FROM_UNIXTIME(UNIX_TIMESTAMP()),
              '".$fama3mesi[0]."',
                '".$fama3mesi[1]."',
             '".$fama3mesi[2]."')";
    db_query($sql) or die(db_error(LINK));

    //assegnazione premi
    /* commentato per primo test reset
    $sql = "UPDATE accounts SET donation=donation+300 WHERE acctid='".$acctid[0]."'";
    db_query($sql);
    $sql = "UPDATE accounts SET donation=donation+150 WHERE acctid='".$acctid[1]."'";
    db_query($sql);
    $sql = "UPDATE accounts SET donation=donation+75 WHERE acctid='".$acctid[2]."'";
    db_query($sql);
    */
    //fine ass pre
    $sqle = "DELETE FROM fama3mesi";
    db_query($sqle);
    savesetting("fama3mesi_reset",$mese);
}
$sql = "SELECT acctid FROM fama3mesi WHERE acctid = '".$session['user']['acctid']."'";
$result = db_query($sql) or die(db_error(LINK));
if(db_num_rows($result)==0){
    $session['user']['fama_anno']+=$session['user']['fama3mesi'];
    $session['user']['fama3mesi']=0;
    $sql = "INSERT INTO fama3mesi
              (acctid,ultimo_reset)
              VALUES('".$session['user']['acctid']."','$mese')";
    db_query($sql) or die(db_error(LINK));
}
if($mese != $mese_db){
    savesetting("fama3mesi",$mese);
}
if($session['user']['superuser']>0)output("Mese: $mese Mese_DB: $mese_db");
//fine luke
//Sook, punizione chiese
$sql = "SELECT * FROM punizioni_chiese WHERE acctid='".$session['user']['acctid']."'";
$result = db_query($sql) or die(db_error(LINK));
$countrow = db_num_rows($result);
for ($i=0; $i<$countrow; $i++){
//for ($i = 0;$i < db_num_rows($result);$i++) {
    $row = db_fetch_assoc($result);
    $row[giorni]--;
    if ($row[giorni]>0) {
        $sqli = "UPDATE punizioni_chiese SET giorni = '{$row[giorni]}' WHERE acctid = '".$session['user']['acctid']."'";
        db_query($sqli) or die(db_error(LINK));
    } else {
        $sqli = "DELETE FROM punizioni_chiese WHERE acctid='".$session['user']['acctid']."'";
        db_query($sqli) or die(db_error(LINK));
    }
}
//fine punizione
//Sook punizione maghi
$sql = "SELECT * FROM punizioni_maghi WHERE acctid='".$session['user']['acctid']."'";
$result = db_query($sql) or die(db_error(LINK));
if(db_num_rows($result)>0){
    $row = db_fetch_assoc($result);
    $row[giorni]--;
    if ($row[giorni]>0) {
        $sqli = "UPDATE punizioni_maghi SET giorni = '{$row[giorni]}' WHERE acctid = '".$session['user']['acctid']."'";
        db_query($sqli) or die(db_error(LINK));
    } else {
        $sqli = "DELETE FROM punizioni_maghi WHERE acctid='".$session['user']['acctid']."'";
        db_query($sqli) or die(db_error(LINK));
    }
}
//fine punizione maghi
//Maximus inizio limitazione paga riparazioni fabbri
$sqlupdate = "UPDATE riparazioni SET riscosso = 0 WHERE acctid='".$session['user']['acctid']."'";
db_query($sqlupdate) or die(db_error(LINK));
//Maximus fine limitazione paga riparazioni fabbri
//Sook inizio limitazione paga rigenerazione oggetti maghi
$sqlupdate = "UPDATE rigenerazioni SET riscosso = 0 WHERE acctid='".$session['user']['acctid']."'";
db_query($sqlupdate) or die(db_error(LINK));
//Sook fine limitazione paga rigenerazione oggetti maghi

//Annulla bonus draghi
$sql = "SELECT * FROM draghi WHERE user_id = '".$session['user']['acctid']."'";
$result = db_query($sql) or die(db_error(LINK));
$rowd = db_fetch_assoc($result);
if($rowd[bonus_erold]!='no'){
    if(e_rand(1,4)!=1){
        if($rowd[bonus_erold]==da){
            $valore=$rowd[danno_soffio]-$rowd[bonus_erold_valore];
            $sql = "UPDATE draghi SET danno_soffio='$valore',bonus_erold='no',bonus_erold_valore='0' WHERE user_id='".$rowd['user_id']."'";
            db_query($sql) or die(db_error(LINK));
        }
        if($rowd[bonus_erold]==at){
            $valore=$rowd[attacco]-$rowd[bonus_erold_valore];
            $sql = "UPDATE draghi SET attacco='$valore',bonus_erold='no',bonus_erold_valore='0' WHERE user_id='".$rowd['user_id']."'";
            db_query($sql) or die(db_error(LINK));
        }
        if($rowd[bonus_erold]==di){
            $valore=$rowd[difesa]-$rowd[bonus_erold_valore];
            $sql = "UPDATE draghi SET difesa='$valore',bonus_erold='no',bonus_erold_valore='0' WHERE user_id='".$rowd['user_id']."'";
            db_query($sql) or die(db_error(LINK));
        }
        if($rowd[bonus_erold]==vi){
            $sql = "UPDATE draghi SET bonus_erold='no',bonus_erold_valore='0' WHERE user_id='".$rowd['user_id']."'";
            db_query($sql) or die(db_error(LINK));
        }
    }
}
//Fine draghi

//Sook, incremento usura oggetti
if ($session[user][oggetto]!=0) {
    $sqlo = "SELECT * FROM oggetti WHERE id_oggetti = '{$session['user']['oggetto']}'";
    $resulto = db_query($sqlo) or die(db_error(LINK));
    $rowo = db_fetch_assoc($resulto);
    if ($rowo[usuramax] > 0) {
        if ($rowo[usura] == 1) {
            output("`n`n`bIl tuo ".$rowo[nome]." si è spaccato in più pezzi, adesso non hai più un oggetto in mano...`b");
            $session['user']['attack'] -= $rowo['attack_help'];
            $session['user']['defence'] -= $rowo['defence_help'];
            $session['user']['bonusattack'] -= $rowo['attack_help'];
            $session['user']['bonusdefence'] -= $rowo['defence_help'];
            $session['user']['maxhitpoints'] -= $rowo['hp_help'];
            $session['user']['hitpoints'] -= $rowo['hp_help'];
            if ($rowo[usuramagica]!=0 AND $row['usuramagicamax']!=0) {
                $session['user']['turns'] -= $rowo['turns_help'];
                $session['user']['bonusfight'] -= $rowo['turns_help'];
                $session['user']['turnimax'] = $session['user']['turns'];
            }
            if ($rowo['usuramagica']!=0 AND $row['usuramagicamax']!=0) $session['user']['bonusfight'] -= $rowo['turns_help'];
            debuglog("ha rotto ".$rowo[nome]." per eccessiva usura");
            $sql = "DELETE FROM oggetti WHERE id_oggetti='{rowo[id_oggetti]}'";
            db_query($sql) or die(db_error(LINK));
            $session[user][oggetto]=0;
        } else {
            $sqlu = "UPDATE oggetti SET usura = '".($rowo[usura]-1)."' WHERE id_oggetti = '{$session['user']['oggetto']}'";
            db_query($sqlu) or die(db_error($link));
            if ($rowo[usuramagica]>0 AND $rowo[usuramagicamax]>0) {
                $sqlu = "UPDATE oggetti SET usuramagica = '".($rowo[usuramagica]-1)."' WHERE id_oggetti = '{$session['user']['oggetto']}'";
                db_query($sqlu) or die(db_error($link));
                if ($rowo[usuramagica]==0) {
                    output("L'oggetto che tieni in mano ha perso le sue proprietà magiche. Alcuni bonus dell'oggetto andranno perduti finchè l'oggetto non sarà rigenerato dai maghi");
                    $session['user']['maxhitpoints'] -= $rowo['hp_help'];
                    $session['user']['hitpoints'] -= $rowo['hp_help'];
                    $session['user']['turns'] -= $rowo[turns_help];
                    $session['user']['playerfights'] -= $row['pvp_help'];
                    $session['user']['bonusfight'] -= $rowo['turns_help'];
                    $session['user']['turnimax'] = $session['user']['turns'];
                }
            }
        }

    }
}
//Excalibur: riduzione turni per resuscitati
if ($session['user']['deathpower'] > 500) $session['user']['deathpower'] = 500;
if ($spirits == -6) {
    $session['user']['turns'] = intval($session['user']['turns']/2);
    output("`n`b`\$Poichè sei resuscitat".($session[user][sex]?"a":"o")." hai diritto a metà dei turni di combattimento, cioè `@".$session['user']['turns']." `\$turni.`b`n`n`0");
}
//Excalibur: fine riduzione turni per resuscitati

//Sook, reset assicurazioni
$session['user']['assicurazione']=0;

if ($session[user][zaino]!=0) {
    $sqlo = "SELECT * FROM oggetti WHERE id_oggetti = '{$session['user']['zaino']}'";
    $resulto = db_query($sqlo) or die(db_error(LINK));
    $rowo = db_fetch_assoc($resulto);
    if ($rowo[usuramax] > 0) {
        if ($rowo[usura] == 1) {
            output("`n`n`bIl tuo ".$rowo[nome]." si è spaccato in più pezzi, adesso non hai più un oggetto nello zaino...`b");
            debuglog("ha rotto ".$rowo[nome]." per eccessiva usura");
            $sql = "DELETE FROM oggetti WHERE id_oggetti='{rowo[id_oggetti]}'";
            db_query($sql) or die(db_error(LINK));
            $session[user][zaino]=0;
        } else {
            $sqlu = "UPDATE oggetti SET usura = '".($rowo[usura]-1)."' WHERE id_oggetti = '{$session['user']['zaino']}'";
            db_query($sqlu) or die(db_error($link));
            if ($rowo[usuramagica]>0) {
                $sqlu = "UPDATE oggetti SET usuramagica = '".($rowo[usuramagica]-1)."' WHERE id_oggetti = '{$session['user']['zaino']}'";
                db_query($sqlu) or die(db_error($link));
                if ($rowo[usuramagica]==0) {
                    output("L'oggetto che tieni nello zaino ha perso le sue proprietà magiche. Alcuni bonus dell'oggetto andranno perduti finchè l'oggetto non sarà rigenerato dai maghi");
                }
            }
        }
    }
}
//fine usura oggetti
//sorteggio settimanale 50 punti donazione
$settimana_db=getsetting("lotteria_pd",0);
$utime=time()+3600;
$week = strftime("%U",$utime);
if($week!=$settimana_db){
    savesetting("lotteria_pd", $week);
    $sqlo = "SELECT acctid,login FROM accounts WHERE superuser='0' ORDER BY RAND() LIMIT 1";
    $resulto = db_query($sqlo) or die(db_error(LINK));
    $rowo = db_fetch_assoc($resulto);
    $sql = "INSERT INTO donazioni_lotteria (acctid,username,data) VALUES ('".$rowo['acctid']."','".$rowo['login']."', FROM_UNIXTIME($utime))";
    $result=db_query($sql);
    $mailmessage = "`2Hai vinto 50 punti donazione, per ritirarli vai nella sala degli eroi nella classifica dei più fortunati!`n";
    systemmail($rowo['acctid'],"`#Lotteria dei fortunati.`2",$mailmessage);
}
//Luke:prelievo automatico
pickup_to_pg();
//Luke:fine
//Sook:selezione premio territori di Merk e reset territori visitati
function premio_esplorazione() {
	global $session;
    //Questa funzione sorteggia cosa viene trovato nel territorio
    if ($session['user']['reincarna'] > 8 OR $session['user']['superuser']>1) {
    	$premio=array(
        1=> "",
        2=> "oro",
        3=> "gemme",
        4=> "tesoro",
        5=> "hpmax",
        6=> "hp",
        7=> "attacco", //evento unico
        8=> "difesa", //evento unico
        9=> "exp",
        10=> "oblio",
        11=> "pvp", //evento unico
        12=> "turnicosto",
        13=> "turni",
        14=> "turniperdi",
        15=> "illusione",
        16=> "pozione_nulla",
        17=> "drink",
        18=> "bladder",
        19=> "quasimorte",
        20=> "morte", //evento unico
        21=> "trappolamortale", //evento unico
        22=> "trappola",
        23=> "favori",
        24=> "good",
        25=> "evil",
        26=> "bonus",
        27=> "malus",
        28=> "pergamena",
        29=> "ricetta",
        30=> "simbolo",
        31=> "evento",
        32=> "cavalcare",
        33=> "grizzly",
        34=> "alligatore",
        35=> "wyrm", //evento unico
        36=> "basilisco" //evento unico
    	);
	}else{
		$premio=array(
		1=> "",
        2=> "oro",
        3=> "gemme",
        4=> "tesoro",
        5=> "hpmax",
        6=> "hp",
        7=> "attacco", //evento unico
        8=> "difesa", //evento unico
        9=> "exp",
        10=> "oblio",
        11=> "pvp", //evento unico
        12=> "turnicosto",
        13=> "turni",
        14=> "turniperdi",
        15=> "illusione",
        16=> "pozione_nulla",
        17=> "drink",
        18=> "bladder",
        19=> "quasimorte",
        20=> "morte", //evento unico
        21=> "trappolamortale", //evento unico
        22=> "trappola",
        23=> "favori",
        24=> "good",
        25=> "evil",
        26=> "bonus",
        27=> "malus",
        28=> "pergamena",
        29=> "ricetta",
        30=> "simbolo",
        31=> "evento",
        32=> "cavalcare",
        33=> "grizzly",
        34=> "alligatore",
        35=> "wyrm" //evento unico
    	);
    }
    $x=e_rand(1,count($premio));
    return $premio[$x];
}
$sql = "SELECT * FROM mappe_foresta_player WHERE acctid=".$session['user']['acctid']." ORDER BY RAND()";
$result = db_query($sql) or die(db_error(LINK));
//conteggio premi unici
$attacco=0;
$difesa=0;
$pvp=0;
$morte=0;
$trappolamortale=0;
$wyrm=0;
$basilisco=0;
$countrow = db_num_rows($result);
for ($i=0; $i<$countrow; $i++){
//for($i=0;$i< db_num_rows($result);$i++){
    $row = db_fetch_assoc($result);

    do {
        $premio=premio_esplorazione();
    } while(($attacco==1 AND $premio=="attacco") OR ($difesa==1 AND $premio=="difesa") OR ($pvp==1 AND $premio=="pvp") OR ($morte==1 AND $premio=="morte") OR ($trappolamortale==1 AND $premio=="trappolamortale") OR ($wyrm==1 AND $premio=="wyrm") OR ($basilisco==1 AND $premio=="basilisco"));

    if ($premio=="attacco") $attacco=1;
    if ($premio=="difesa") $difesa=1;
    if ($premio=="pvp") $pvp=1;
    if ($premio=="morte") $morte=1;
    if ($premio=="trappolamortale") $trappolemortale=1;
    if ($premio=="wyrm") $wyrm=1;
    if ($premio=="basilisco") $basilisco=1;

    //aggiornamento record
    $sql="UPDATE mappe_foresta_player SET visitato='0', premio='".$premio."' WHERE luogo='".$row[luogo]."' AND acctid='".$session['user']['acctid']."'";
    db_query($sql) or die(db_error(LINK));
}
//Fine settaggio territori di Merk

//Sook, limite ai punti permanenti in base al numero di reincarnazioni (50+50*reinc.)
if (getsetting("puntipermbase",-1)>-1) {
    $attb=0; //punti attacco legati ai Dragon Points
    $defb=0; //punti difesa legati ai Dragon Points
    while (list($key, $val) = each($session['user']['olddp'])) {
        if ($val == "at") {
            $attb ++;
        }
        if ($val == "de") {
            $defb ++;
        }
    }
    while (list($key, $val) = each($session['user']['dragonpoints'])) {
        if ($val == "at") {
            $attb ++;
        }
        if ($val == "de") {
            $defb ++;
        }
    }
    $limiteattacco = getsetting("puntipermbase",0)+getsetting("puntipermreinc",0)*$session['user']['reincarna'] + $attb;
    $limitedifesa = getsetting("puntipermbase",0)+getsetting("puntipermreinc",0)*$session['user']['reincarna'] + $defb;
    if ($session['user']['bonusattack'] > $limiteattacco) {
        output("`n`b`\$ATTENZIONE: `bhai superato il limite massimo di punti attacco permanenti che il tuo numero attuale di reincarnazioni ti consente; i punti in eccesso sono stati convertiti in punti attacco temporanei.");
        $session['user']['bonusattack']=$limiteattacco;
    }
    if ($session['user']['bonusdefence'] > $limitedifesa) {
        output("`n`b`\$ATTENZIONE: `bhai superato il limite massimo di punti difesa permanenti che il tuo numero attuale di reincarnazioni ti consente; i punti in eccesso sono stati convertiti in punti difesa temporanei.");
        $session['user']['bonusdefence']=$limitedifesa;
    }
}
//fine limite punti permanenti
if ($session['user']['npg'] == 1) {
   $session['user']['turns'] = 0;
   $session['user']['playerfights'] = 0;
   $session['user']['experience'] = 0;
   $session['user']['level'] = 0;
}
$textdebug.= " e ha ".$session['user']['turns']." turni e ".$session['user']['playerfights']." PvP";
$textcompara=" e ha ".$session['user']['turns']." turni e ".$session['user']['playerfights']." PvP";
if ($textdebug != $textcompara) {
    debuglog($textdebug);
}
page_footer();
}else{
    redirect("regolamentonewplayer.php");
}
?>