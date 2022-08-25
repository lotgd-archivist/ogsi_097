<?php
require_once("common.php");
checkday();
page_header("La casetta di Javella");
$session['user']['locazione'] = 142;
$oldname = $session['user']['name'];

if ($_GET['op'] == "reinc") {
    $caso = e_rand(100, 3000);
    $chance = $session['user']['dragonkills'] * 100 ;
    $oggettoid = $session['user']['oggetto'];
        $sql = "SELECT * FROM oggetti WHERE id_oggetti = $oggettoid";
        $result = db_query($sql) or die(db_error(LINK));
        $row = db_fetch_assoc($result);
        if ($caso > $chance){
        output("`3Senti l'energia del tuo corpo abbandonarti, ti senti come svuotat".($session['user']['sex']?"a":"o").".`n");
        output("`3Javella si avvicina, posa la sua mano destra all'altezza del tuo cuore e la mano sinistra sulla tua testa.`n");
        output("`3Poi, in una nuvola di fumo Javella svanisce, e senti la sua voce che dice che non sei pront".($session['user']['sex']?"a":"o").".`n");
        $text = "`^" . $session['user']['name'] . "`^ ha dato l'uovo a Javella, fallendo nella reincarnazione";
        addnews($text . ".");
        debuglog("ha fallito la reincarnazione da Javella");
        savesetting("hasegg", stripslashes(0));
        // addnav("Torna al Villaggio","village.php");
    }

        if ($caso <= $chance) {
        $attiva_reinc = 1;
        $session['user']['reincarna'] += 1;
        $new_hp = intval($session['user']['maxhitpoints'] / 10) + ($session['user']['dragonkills'] * 8);
        //$session['user']['title'] = 'Contadino';
        $reincarnazioni = $session['user']['reincarna'];
        if ($reincarnazioni > 24) $reincarnazioni = 24;
        switch ($reincarnazioni) {
        case 1:
            if ($session['user']['sex'] == 0){
               $session['user']['name'] = '`3Contadino ' . $session['user']['login'];
               $session['user']['title'] = '`3Contadino';
            } else {
               $session['user']['name'] = '`3Contadina ' . $session['user']['login'];
               $session['user']['title'] = '`3Contadina';
            }
        break;
        case 2:
            if ($session['user']['sex'] == 0){
               $session['user']['name'] = '`2Contadino ' . $session['user']['login'];
               $session['user']['title'] = '`2Contadino';
            } else {
               $session['user']['name'] = '`2Contadina ' . $session['user']['login'];
               $session['user']['title'] = '`2Contadina';
            }
        break;
        case 3:
            if ($session['user']['sex'] == 0){
               $session['user']['name'] = '`5Contadino ' . $session['user']['login'];
               $session['user']['title'] = '`5Contadino';
            } else {
               $session['user']['name'] = '`5Contadina ' . $session['user']['login'];
               $session['user']['title'] = '`5Contadina';
            }
        break;
        case 4:
            if ($session['user']['sex'] == 0){
               $session['user']['name'] = '`4Contadino ' . $session['user']['login'];
               $session['user']['title'] = '`4Contadino';
            } else {
               $session['user']['name'] = '`4Contadina ' . $session['user']['login'];
               $session['user']['title'] = '`4Contadina';
            }
        break;
        case 5:
            if ($session['user']['sex'] == 0){
               $session['user']['name'] = '`6Contadino ' . $session['user']['login'];
               $session['user']['title'] = '`6Contadino';
            } else {
               $session['user']['name'] = '`6Contadina ' . $session['user']['login'];
               $session['user']['title'] = '`6Contadina';
            }
        break;
        case 6:
            if ($session['user']['sex'] == 0){
               $session['user']['name'] = '`!Contadino ' . $session['user']['login'];
               $session['user']['title'] = '`!Contadino';
            } else {
               $session['user']['name'] = '`!Contadina ' . $session['user']['login'];
               $session['user']['title'] = '`!Contadina';
            }
        break;
        case 7:
            if ($session['user']['sex'] == 0){
               $session['user']['name'] = '`%Contadino ' . $session['user']['login'];
               $session['user']['title'] = '`%Contadino';
            } else {
               $session['user']['name'] = '`%Contadina ' . $session['user']['login'];
               $session['user']['title'] = '`%Contadina';
            }
        break;
        case 8:
            if ($session['user']['sex'] == 0){
               $session['user']['name'] = '`8Contadino ' . $session['user']['login'];
               $session['user']['title'] = '`8Contadino';
            } else {
               $session['user']['name'] = '`8Contadina ' . $session['user']['login'];
               $session['user']['title'] = '`8Contadina';
            }
        break;
        case 9:
            if ($session['user']['sex'] == 0){
               $session['user']['name'] = '`(Contadino ' . $session['user']['login'];
               $session['user']['title'] = '`(Contadino';
            } else {
               $session['user']['name'] = '`(Contadina ' . $session['user']['login'];
               $session['user']['title'] = '`(Contadina';
            }
        break;
        case 10:
            if ($session['user']['sex'] == 0){
               $session['user']['name'] = '`rContadino ' . $session['user']['login'];
               $session['user']['title'] = '`rContadino';
            } else {
               $session['user']['name'] = '`rContadina ' . $session['user']['login'];
               $session['user']['title'] = '`rContadina';
            }
        break;
        case 11:
            if ($session['user']['sex'] == 0){
               $session['user']['name'] = '`RContadino ' . $session['user']['login'];
               $session['user']['title'] = '`RContadino';
            } else {
               $session['user']['name'] = '`RContadina ' . $session['user']['login'];
               $session['user']['title'] = '`RContadina';
            }
        break;
        case 12:
            if ($session['user']['sex'] == 0){
               $session['user']['name'] = '`gContadino ' . $session['user']['login'];
               $session['user']['title'] = '`gContadino';
            } else {
               $session['user']['name'] = '`gContadina ' . $session['user']['login'];
               $session['user']['title'] = '`gContadina';
            }
        break;
        case 13:
            if ($session['user']['sex'] == 0){
               $session['user']['name'] = '`GContadino ' . $session['user']['login'];
               $session['user']['title'] = '`GContadino';
            } else {
               $session['user']['name'] = '`GContadina ' . $session['user']['login'];
               $session['user']['title'] = '`GContadina';
            }
        break;
        case 14:
            if ($session['user']['sex'] == 0){
               $session['user']['name'] = '`qContadino ' . $session['user']['login'];
               $session['user']['title'] = '`qContadino';
            } else {
               $session['user']['name'] = '`qContadina ' . $session['user']['login'];
               $session['user']['title'] = '`qContadina';
            }
        break;
        case 15:
            if ($session['user']['sex'] == 0){
               $session['user']['name'] = '`QContadino ' . $session['user']['login'];
               $session['user']['title'] = '`QContadino';
            } else {
               $session['user']['name'] = '`QContadina ' . $session['user']['login'];
               $session['user']['title'] = '`QContadina';
            }
        break;
		case 16:
            if ($session['user']['sex'] == 0){
               $session['user']['name'] = '`fContadino ' . $session['user']['login'];
               $session['user']['title'] = '`fContadino';
            } else {
               $session['user']['name'] = '`fContadina ' . $session['user']['login'];
               $session['user']['title'] = '`fContadina';
            }
        break;
		case 17:
            if ($session['user']['sex'] == 0){
               $session['user']['name'] = '`FContadino ' . $session['user']['login'];
               $session['user']['title'] = '`FContadino';
            } else {
               $session['user']['name'] = '`FContadina ' . $session['user']['login'];
               $session['user']['title'] = '`FContadina';
            }
        break;
		case 18:
            if ($session['user']['sex'] == 0){
               $session['user']['name'] = '`pContadino ' . $session['user']['login'];
               $session['user']['title'] = '`pContadino';
            } else {
               $session['user']['name'] = '`pContadina ' . $session['user']['login'];
               $session['user']['title'] = '`pContadina';
            }
        break;
		case 19:
            if ($session['user']['sex'] == 0){
               $session['user']['name'] = '`EContadino ' . $session['user']['login'];
               $session['user']['title'] = '`EContadino';
            } else {
               $session['user']['name'] = '`EContadina ' . $session['user']['login'];
               $session['user']['title'] = '`EContadina';
            }
        break;
		case 20:
            if ($session['user']['sex'] == 0){
               $session['user']['name'] = '`eContadino ' . $session['user']['login'];
               $session['user']['title'] = '`eContadino';
            } else {
               $session['user']['name'] = '`eContadina ' . $session['user']['login'];
               $session['user']['title'] = '`eContadina';
            }
        break;
		case 21:
            if ($session['user']['sex'] == 0){
               $session['user']['name'] = '`JContadino ' . $session['user']['login'];
               $session['user']['title'] = '`JContadino';
            } else {
               $session['user']['name'] = '`JContadina ' . $session['user']['login'];
               $session['user']['title'] = '`JContadina';
            }
        break;
		case 22:
            if ($session['user']['sex'] == 0){
               $session['user']['name'] = '`jContadino ' . $session['user']['login'];
               $session['user']['title'] = '`jContadino';
            } else {
               $session['user']['name'] = '`jContadina ' . $session['user']['login'];
               $session['user']['title'] = '`jContadina';
            }
        break;
		case 23:
            if ($session['user']['sex'] == 0){
               $session['user']['name'] = '`XContadino ' . $session['user']['login'];
               $session['user']['title'] = '`XContadino';
            } else {
               $session['user']['name'] = '`XContadina ' . $session['user']['login'];
               $session['user']['title'] = '`XContadina';
            }
        break;
		case 24:
            if ($session['user']['sex'] == 0){
               $session['user']['name'] = '`&Contadino ' . $session['user']['login'];
               $session['user']['title'] = '`&Contadino';
            } else {
               $session['user']['name'] = '`&Contadina ' . $session['user']['login'];
               $session['user']['title'] = '`6Contadina';
            }
        break;
                }
        debuglog("si reincarna al suo ".$session['user']['dragonkills']."° DK");
        $session['user']['dragonkills'] = 0;
        $session['user']['ctitle'] = "";
        $session['user']['gold'] = 100;
        $session['user']['gems'] = 0;
        $session['user']['charm'] = 5;
        $session['user']['quest'] = 0;
        $session['user']['deathpower'] = 0;
        $session['bufflist'] = array();
        $dkff=0;
        $kappa="aveva ".$session['user']['maxhitpoints']." HitPoints PRIMA, ";
        $session['user']['maxhitpoints'] = $new_hp;
        $kappa .= "e ha ".$session['user']['maxhitpoints']." HitPoints DOPO";
        debuglog($kappa);
        $session['user']['hitpoints'] = $session['user']['maxhitpoints'];
        while(list($key,$val)=each($session['user']['dragonpoints'])){
            if ($val=="ff"){
                $dkff++;
            }
        }
        $perdita = e_rand(1,2);
        debuglog("ha ".($dkff+$session['user']['bonusfight'])." turni foresta prima della reincarnazione, e ne perde $perdita alla reincarnazione");
        $dkff -= $perdita;
        $session['user']['bonusfight'] += $dkff;
        if ($session['user']['bonusfight'] < 0) $session['user']['bonusfight'] = 0;
        if ($reincarnazioni == 6) {
           output("`b`#`cC O M P L I M E N T I ! ! ! !`c`b`n`n");
           output("`6Questa è la tua sesta reincarnazione, e ti vengono assegnati 5 turni foresta supplementari !!`n`n");
           $session['user']['bonusfight'] += 5;
           debuglog("riceve 5 Forest Fight per la sua sesta reincarnazione");
        }
        //$session['user']['attack'] -= ($session['user']['weapondmg']+$row['attack_help']+$session['user']['level']);
        //$session['user']['defence'] -= ($session['user']['armordef']+$row['defence_help']+$session['user']['level']);
        $session['user']['bonusattack'] -= $row['attack_help'];
        $session['user']['bonusdefence'] -= $row['defence_help'];
        $session['user']['turns'] -= $row['turns_help'];
        debuglog("Bonus Def PRIMA: ".$session['user']['bonusdefence'].", Bonus Att PRIMA: ".$session['user']['bonusattack']);
        $newbonusdef = e_rand(($session['user']['bonusdefence'])/2,$session['user']['bonusdefence']);
        $newbonusatk = e_rand(($session['user']['bonusattack'])/2,$session['user']['bonusattack']);
        //Sook, messa in conto dei Punti Drago
        reset($session['user']['dragonpoints']);
/*        if (is_array($session['user']['olddp'])) {
            $olddp = createarray($session['user']['olddp']);
        }else {
            $olddp=array();
        }*/
            while (list($key, $val) = each($session['user']['dragonpoints'])) {
            if ($val == "at") {
//                array_push($olddp, "at");
                array_push($session['user']['olddp'], "at");
            }
            if ($val == "de") {
//                array_push($olddp, "de");
                array_push($session['user']['olddp'], "de");
            }
            if ($val == "hp") {
//                array_push($olddp, "hp");
                array_push($session['user']['olddp'], "hp");
            }
        }
        $attb=0;
        $defb=0;
        $hpb=0;
/*        reset($olddp);
        while (list($key, $val) = each($olddp)) {
            if ($val == "at") {
                $attb ++;
            }
            if ($val == "de") {
                $defb ++;
            }
            if ($val == "hp") {
                $hpb += 5;
            }
        }*/
        //$session['user']['olddp']=createstring($olddp);
        //fine Punti Drago
        $session['user']['bonusdefence'] = $newbonusdef + $row['defence_help'] + $attb;
        $session['user']['bonusattack'] = $newbonusatk + $row['attack_help'] + $defb;
        debuglog("Bonus Def DOPO: ".$session['user']['bonusdefence'].", Bonus Att DOPO: ".$session['user']['bonusattack']);
        $session['user']['defence'] = $session['user']['bonusdefence'] + 1;
        $session['user']['attack'] = $session['user']['bonusattack'] + 1;
/*      $kappa="aveva ".$session['user']['maxhitpoints']." HitPoints PRIMA, ";
        $session['user']['maxhitpoints'] = $new_hp + $hpb;
        $session['user']['hitpoints'] = $session['user']['maxhitpoints'];
        $kappa .= "e ha ".$session['user']['maxhitpoints']." HitPoints DOPO";
        debuglog($kappa);*/
        $session['user']['charm'] = 0;
        $session['user']['level'] = 1;
        $session['user']['experience'] = 1;
        $session['user']['goldinbank'] = 0;

        //Excalibur: per azzerare punti abilità, Incantesimi di Hatetepe e Attacchi Speciali
        $skills = array(1=>"darkarts","magic","thievery","militare","mystic","tactic","rockskin","rhetoric","muscle","nature","weather","elementale","barbaro","bardo");
        $skillpoints = array(1=>"darkartuses","magicuses","thieveryuses","militareuses","mysticuses","tacticuses","rockskinuses","rhetoricuses","muscleuses","natureuses","weatheruses","elementaleuses","barbarouses","bardouses");
        for ($i = 1; $i < (count($skills)+1); $i++){
            $session['user'][$skills[$i]] = 0;
            $session['user'][$skillpoints[$i]] = 0;
        }
        //$sql="DELETE FROM items WHERE class='Spells' AND owner = '".$session['user']['acctid']."'";
        //$result = db_query($sql) or die(db_error(LINK));
        //$sql="DELETE FROM incantesimi WHERE acctid = '".$session['user']['acctid']."'";
        //$result = db_query($sql) or die(db_error(LINK));
        //$session['bufflist'] = array();
        //Excalibur: fine azzeramento punti abilità e incantesimi e attacchi speciali

        /*Sook, svuotamento soldi e gemme non riscossi e nascosti in giro per il gioco
        //rigenerazioni maghi
        $sql="DELETE FROM rigenerazioni WHERE acctid = '".$session['user']['acctid']."'";
        $result = db_query($sql) or die(db_error(LINK));
        //riparazioni fabbri
        $sql="UPDATE riparazioni SET gold='0',riscosso='0' WHERE acctid = '".$session['user']['acctid']."'";
        $result = db_query($sql) or die(db_error(LINK));
        //cassaforte ladri
        $sql="UPDATE ladri SET cassaforte='0' WHERE acctid = '".$session['user']['acctid']."'";
        $result = db_query($sql) or die(db_error(LINK));
        //mercatino di Oberon, oggetti venduti
        $sql="DELETE FROM mercatino WHERE acctid = '".$session['user']['acctid']."'";
        $result = db_query($sql) or die(db_error(LINK));
        //mercatino di Oberon, oggetti in vendita
        $sql="UPDATE oggetti SET dove='1',acctid='0',mercatino_gemme='0',mercatino_oro='0' WHERE dove=7 AND acctid='".$session['user']['acctid']."'";
        $result = db_query($sql) or die(db_error(LINK));
        //gemme nelle casseforti delle case
        $sql="UPDATE items SET gems='0' WHERE class='Key' AND owner='".$session['user']['acctid']."'";
        $result = db_query($sql) or die(db_error(LINK));
        //soldi in sospeso premiazione
        $sql="DELETE FROM accounts_dynamic WHERE acctid = '".$session['user']['acctid']."' AND (type='gold' OR type='gems' OR type='hp')";
        $result = db_query($sql) or die(db_error(LINK));
        //Sook fine azzeramento averi */

        $session['user']['dragonpoints'] = 'a:0:{}';
        $session['user']['weapondmg'] = 0;
        $session['user']['armordef'] = 0;
        $session['user']['weaponvalue'] = 0;
        $session['user']['armorvalue'] = 0;
        $session['user']['age'] = 0;
        $session['user']['agedrago'] = 0;
        $session['user']['drunkenness'] = 0;
        $session['user']['race'] = 0;
        $session['user']['specialty'] = 0;
        $sql = "UPDATE donazioni SET usi = 0 WHERE idplayer = '".$session['user']['acctid']."' AND tipo = 'R*'";
        $result = db_query($sql) or die(db_error(LINK));
        $session['user']['turni_drago'] ++;
        $session['bufflist'] = array();
        //Luke: modifica contatore fama
        $fama = 10000*$session['user']['fama_mod'];
        $session['user']['fama3mesi'] += $fama;
        debuglog("guadagna $fama fama alla reincarnazione. Adesso ha ".$session['user']['fama3mesi']." punti fama");
        //Luke: fine mod fama
        output("`3Javella ti dice di seguirla, e si avvia verso una porta che da sul retro della casa. Entri nella porta per ");
        output("scoprire che una rampa di scale scende verso il basso. Percepisci un intenso profumo di incenso che si ");
        output("diffonde dalla cantina, e alla fine ti ritrovi in un antro scavato nella roccia. Le pareti trasudano umidità, ");
        output("e la temperatura della stanza è decisamente alta. Mentre ti stai chiedendo cosa ci fai li, Javella si ");
        output("rivolge a te dicendoti: \"`6Rimuovi la tua `5" . $session['user']['armor'] . "`6 e posa la tua `5" . $session['user']['weapon']);
        output("`6 mi".($session['user']['sex']?"a":"o")." car".($session['user']['sex']?"a":"o")." `&" . $session['user']['name'] . "`6, per affrontare la ");
        output("reincarnazione dovrai essere spogli".($session['user']['sex']?"a":"o")." di ogni tua proprietà, il tuo spirito non dovrà avere nulla che possa");
        output("tenerlo legato a questa tua esistenza.\"`3Imbarazzat".($session['user']['sex']?"a":"o")." per la tua nudità cerchi di coprirti, ma Javella prosegue: ");
        output("\"`6Non sentirti imbarazzat".($session['user']['sex']?"a":"o").", e adesso sdraiati su questo tavolo, il processo di reincarnazione sta per avere inizio.\" `3");
        output("Ti sdrai e Javella ti porge una boccetta di un liquido color cremisi, invitandoti a bere. Inizia a compiere degli ");
        output("strani rituali e pronuncia delle parole che suonano vuote nelle tua mente: `n\"`6Bhí fear in a chomhnuidhe ar an bhaile ");
        output("s'againne a dtugadh siad Micheál Ruadh air. Bhí teach beag cheann-tuigheadh aige ar fhód an bhealaigh mhóir agus bhí ");
        output("an donas air le séideadh anuas agus le deora anuas!\"");
        output("`3Mentre Javella prosegue nel rituale, senti la tua mente ottenebrarsi e mentre stai perdendo conoscenza comprendi ");
        output("che forse non eri pront".($session['user']['sex']?"a":"o")." per affrontare un'esperienza del genere. Le ultime parole che odi sono: \"`6Ó, go díreach go leath-mheasardha\"`3,");
        output("dopodichè cadi nell'oblio.`n`n");
        output("Dopo un tempo che potrebbe essere un'eternità, ti risvegli e scopri di essere nella foresta, con solo la tua `4T-Shirt`3 indosso ");
        output("e i tuoi `4Pugni`3 come arma. Javella devi averti trasportat".($session['user']['sex']?"a":"o")." qui dopo che sei cadut".($session['user']['sex']?"a":"o")." in stato d'incoscienza, e noti ");
        output("un bigliettino di fianco a te. Lo raccogli ed inizi a leggerlo `&\"Grazie per aver firmato l'assegno in bianco. ");
        output("Ti ho lasciato 100 pezzi d'oro in tasca. Recati alle Tenute Reali, adesso hai la possibilità di acquistare una casa. Ti suggerisco ");
        output("di non far parola con nessuno di quello che è successo, ne andrebbe della tua reputazione, e comunque non riotterresti nulla di quello che hai perso.`n");

        //Mandos, testi differenziati in base al numero di reincarnazioni
        switch ($reincarnazioni) {
        case 1: //(vecchio testo)
            output("Considera che hai guadagnato qualcosa che solo gli `6ADMIN`& di questo sito hanno: ");
            output("Il tuo nome appare colorato, e questo ti distinguerà da tutti gli altri giocatori. Forse non lo considererai per quello che ");
            output("vale, ma consolati, non sarai l'ultim".($session['user']['sex']?"a":"o")." ad essere stat".($session['user']['sex']?"a":"o")." imbrogliat".($session['user']['sex']?"a":"o").".\" `n");
            output("Oh, a proposito... ti consiglio di recarti alle Tenute Reali, giacchè ora hai la possibilità  di costruirti una casa.\"");
    		output("Per nulla turbat".($session['user']['sex']?"a":"o")." da quanto appena letto, decidi di seguire il consiglio e raggiungere le Tenute Reali. In fondo, una solida abitazione offre ");
    	    output("pur sempre un certo grado di protezione dagli altri guerrieri. Protezione di cui avrai sicuramente bisogno, visto che parte della tua ");
    		output("energia pare essersi volatilizzata.");
		break;
		case 2:
		    output("Oh, quasi dimenticavo... hai mai sentito parlare di `^Hatetepe`&? E' un tipo un po' strano, ma simpatico. Siccome anche tu mi sei simpatic".($session['user']['sex']?"a":"o"));
		    output("ho deciso che gli parlerò bene di te. Va' a conoscerlo, potresti concludere dei buoni affari con lui.");
		    output("`n`3Per nulla turbat".($session['user']['sex']?"a":"o")." da quanto appena letto, decidi di seguire il consiglio e andare in cerca di questo Hatetepe. I buoni affari non ");
		    output("hanno mai fatto male a nessuno, men che meno a te!");
		break;
		case 3:
		    output("In ogni caso, se ti dovesse capitare di incontrare `^Thor`& in foresta digli che ti mando io. Sono sicura che non te ne pentirai!");
		    output("`n`3Per nulla turbat".($session['user']['sex']?"a":"o")." da quanto appena letto, decidi di seguire il consiglio e andare in cerca di `^Thor`3. Tanto, considerando ");
		    output("come ti senti in questo momento, è lecito pensare che peggio di così non possa andare.");
		break;
		case 4:
		    output("`nA proposito, ultimamente mi è capitato di incontrare spesso nella foresta un mio vecchio amico. Si chiama `^Merk`&, e tra la miriade ");
		    output("di tomi e pergamene che tenterà di venderti potresti anche trovare qualcosa di gran valore. Tienilo a mente se dovessi incrociarlo!");
		    output("`n`3Per nulla turbat".($session['user']['sex']?"a":"o")." da quanto appena letto, decidi di dirigerti subito in foresta alla ricerca di questo misterioso individuo.");
		break;
		default:
		    output("`n`3Per nulla turbat".($session['user']['sex']?"a":"o")." da quanto appena letto, decidi di recarti in città per cominciare una nuova vita.");
		}
		output("`n`n`^`bD'ora in poi sarai noto a tutti come $newname `^!`0");
		
        // addnav("Torna al Villaggio","village.php");
        //$text = "`^" . $session['user']['name'] . "`^ ha dato l'uovo a Javella e si è reincarnat".($session['user']['sex']?"a":"o").".";
        //addnews($text . ".");
		//addnews($text);
        addnews("`^$oldname `^ha consegnato l'uovo d'oro a Javella e si è reincarnat".($session['user']['sex']?"a":"o")."! Da ora sarà not".($session['user']['sex']?"a":"o")." a tutti come ".$session['user']['name']."`^!");
        savesetting("hasegg", stripslashes(0));
        $session['user']['weapon'] = "Pugni";
        $session['user']['armor'] = "T-Shirt";

    } //fine routine reincarnazione
}
$sql = "SELECT acctid,name,sex FROM accounts WHERE acctid = '" . getsetting("hasegg", 0) . "'";
$result = db_query($sql) or die(db_error(LINK));
$row = db_fetch_assoc($result);
$owner = $session['user']['acctid'];
if ($owner == $row[acctid]) {
    if ($session['user']['dragonkills'] < 18) {
        output("`3L'uovo d'oro che porti con te inizia a brillare.");
        output(" Javella ti sorride e dice :\"Non sei pronto, torna quando un imperatore si inchinerà dinanzi a te.\"");
        addnav("Torna al Giardino", "gardens.php");
        addnav("Torna al Villaggio", "village.php");
    } else {
        output("`3Javella ti sta aspettando!");
        output(" Ti stavo aspettando mortale, so cosa vuoi da me, io posso darti una nuova vita, farti rinascere con alcuni dei tuoi poteri e la tua astuzia.");
        output("`3In cambio della tua reincarnazione voglio il tuo uovo!");
        output("`\$Ma ricorda non sempre la reincarnazione funziona, e l'uovo lo perderai lo stesso!");
        addnav("Reincarnati", "javella.php?op=reinc");
        addnav("Torna al Giardino", "gardens.php");
        addnav("Torna al Villaggio", "village.php");
    }
} else if ($attiva_reinc == 1) {
        $session['user']['restorepage']="village.php";
   addnav("E' un nuovo giorno", "newday.php?");

} else {
    output("`3Javella sta sistemando la sua deliziosa casetta!");
    addnav("Torna al Giardino", "gardens.php");
    addnav("Torna al Villaggio", "village.php");
}
page_footer();

?>