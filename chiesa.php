<?php
/*
carriera FABBRO        carriera SGRIOS        carriera KARNAK               carriera DRAGO
===============        ===============        ===============               ==============
5=>"Garzone",          1=>"Seguace",          10=>"Invasato",               50=>"Stalliere",
6=>"Apprendista",      2=>"Accolito",         11=>"Fanatico",               51=>"Scudiero",
7=>"Fabbro",           3=>"Chierico",         12=>"Posseduto",              52=>"Cavaliere",
8=>"Mastro Fabbro",    4=>"Sacerdote",        13=>"Maestro delle Tenebre",  53=>"Mastro di Draghi",
                       9=>"Gran Sacerdote",   15=>"Falciatore di Anime",    54=>"Dominatore di Draghi",
                       17=>"Sommo Chierico",  16=>"Portatore di Morte",     55=>"Cancelliere dei Draghi",
*/

require_once("common.php");
require_once("common2.php");
manutenzione(getsetting("manutenzione",3));

checkday(); //Sook, per evitare che si possa continuare a donare oltre la mezzanotte del giorno di premiazione

if ($session['user']['superuser'] > 2) {
    $session['user']['dio'] = 1;
    $session['user']['carriera'] = 1;
}
$sqlpun = "SELECT * FROM punizioni_chiese WHERE acctid = '{$session['user']['acctid']}' AND giorni > 0 AND fede = '1'";
$resultpun = db_query($sqlpun) or die(db_error(LINK));
$refpun = db_fetch_assoc($resultpun);
if(db_num_rows($resultpun)==0) {
    $carriera = $session['user']['carriera'];
    $dio = $session['user']['dio'];
    $dk = $session['user']['dragonkills'] + ($session['user']['reincarna'] * 19);
    $bonus = 0;
    $caso = mt_rand(1, 3);
    $cento = mt_rand(1, 99);
    $venti = mt_rand(1, 20);
    $dieci = mt_rand(1, 10);
    $ultima_messa = getsetting("tempomessa", 0);
    $player_inchiesa = getsetting("player_inchiesa", 0);
    $data_messa = time();
    //Modifica per inserire news
    if ($session['user']['dio'] == 1){
        $sql="SELECT * FROM custom WHERE area1='gransacerdote'";
        $result=db_query($sql);
        $dep = db_fetch_assoc($result);
        if (db_num_rows($result) == 0) {
            $sqli = "INSERT INTO custom (dTime,area1) VALUES (NOW(),'gransacerdote')";
            $resulti=db_query($sqli);
        }
        if ($carriera==9 AND !$_GET['az']){
            output("`0<form action=\"chiesa.php\" method='POST'>",true);
            output("[Gran Sacerdote] Inserisci Notizia? <input name='meldung' size='80'> ",true);
            output("<input type='submit' class='button' value='Insert'>`n`n",true);
            addnav("","chiesa.php");
            if ($_POST['meldung']){
                $sql = "UPDATE custom SET dTime = now(),dDate = now() WHERE area1 = 'gransacerdote'";
                $result=db_query($sql);
                $sql = "UPDATE custom SET amount = ".$session['user']['acctid']." WHERE area1 = 'gransacerdote'";
                $result=db_query($sql);
                $sql = "UPDATE custom SET area ='".addslashes($_POST['meldung'])."' WHERE area1 = 'gransacerdote'";
                $result=db_query($sql);
                $_POST[meldung]="";
            }
            addnav("","news.php");
        }
        if ($carriera==17 AND !$_GET['az']){
            output("`0<form action=\"chiesa.php\" method='POST'>",true);
            output("[Sommo Chierico] Inserisci Notizia? <input name='meldung' size='80'> ",true);
            output("<input type='submit' class='button' value='Insert'>`n`n",true);
            addnav("","chiesa.php");
            if ($_POST['meldung']){
                $sqlogg = "DELETE FROM custom WHERE amount = '".$session['user']['acctid']."' AND area1='sommo'";
                db_query($sqlogg) or die(db_error(LINK));
                $sqli = "INSERT INTO custom (amount,dTime,dDate,area,area1)
                VALUES ('".$session['user']['acctid']."',NOW(),NOW(),'".addslashes($_POST['meldung'])."','sommo')";
                db_query($sqli);
                $_POST[meldung]="";
            }
            addnav("","news.php");
        }
        $sql="SELECT * FROM custom WHERE area1='gransacerdote'";
        $result=db_query($sql);
        $dep = db_fetch_assoc($result);
        $lasttime=$dep['dTime'];
        $lastdate=$dep['dDate'];
        $msgchiesa = stripslashes($dep['area']);
        $idgs = $dep['amount'];
        if ($msgchiesa !="") {
            $sqlnome="SELECT name,carriera,login FROM accounts WHERE acctid=$idgs";
            $resultnome=db_query($sqlnome);
            $dep1=db_fetch_assoc($resultnome);
            $nomegs=$dep1['name'];
            if($dep1[carriera]==9){
                output("<big>`b`c`@ANNUNCIO DEL GRAN SACERDOTE `#$nomegs `@DELLA CHIESA DI `6SGRIOS`0`c`b</big>`n",true);
                output("`8".date("d/m/Y",strtotime($lastdate))." `6".date("H:i:s",strtotime($lasttime))."   `b`^".$msgchiesa."`b`n`n");
            }
        }
        output("`b`c`@ANNUNCI DEI SOMMI CHIERICI DELLA CHIESA DI `6SGRIOS`0`c`b`n",true);
        $sql="SELECT * FROM custom WHERE area1='sommo' ORDER BY dDate ASC, dTime ASC";
        $result=db_query($sql);
        $countrow = db_num_rows($result);
        for ($i=0; $i<$countrow; $i++){
        //for($i=0;$i<db_num_rows($result);$i++){
            $dep = db_fetch_assoc($result);
            $lasttime=$dep['dTime'];
            $lastdate=$dep['dDate'];
            $msgchiesa = stripslashes($dep['area']);
            $idgs = $dep['amount'];
            if ($msgchiesa !="") {
                $sqlnome="SELECT name,carriera,login FROM accounts WHERE acctid=$idgs";
                $resultnome=db_query($sqlnome);
                $dep1=db_fetch_assoc($resultnome);
                $nomegs=$dep1['name'];
                if ($dep1[carriera]==17){
                    output("`8".date("d/m/Y",strtotime($lastdate))." `6".date("H:i:s",strtotime($lasttime))." `@".$dep1['login']." `^: `b".$msgchiesa."`b`n");
                }
            }
        }
        output("`n");
        //Excalibur: Visualizzazione annuncio prossima messa
        output("<font size='+2'>`b`c`@PROSSIMA MESSA IN ONORE DI `6SGRIOS`0`c`b</font>`n",true);
        $sql="SELECT * FROM custom WHERE area1='messasgrios'";
        $result=db_query($sql);
        $dep = db_fetch_assoc($result);
        $quando = $dep['dDate']." ".$dep['dTime'];
        if (date ("Y-m-d H:m:s",strtotime ("now")) <= $quando){
           $msgproxmessa = stripslashes($dep['area']);
           output("<font size='+1'>`c`^".$msgproxmessa."`0`c</font>`n",true);
        }else{
           output("<font size='+1'>`c`^DATA MESSA DA DEFINIRE`0`c</font>`n",true);
           output("`c`6Ultima messa celebrata il ".$quando."`0`c`n",true);
        }
        //Excalibur: Fine Visualizzazione annuncio prossima messa
    }
    //Fine Modifica News
    page_header("La chiesa di Sgrios");
    $session['user']['locazione'] = 195;
    $potenziamenti = array(
    0=>"Zero",
    1=>"Uno",
    2=>"Due",
    3=>"Tre",
    );
    if ((259200 - ($data_messa - $ultima_messa)) < 0) {
        $messa = 1;
    }
    if ((280 - ($data_messa - $ultima_messa)) > 0) {
        $potere_messa = 1;
    }
    $giorni_messa = intval((259200 - ($data_messa - $ultima_messa)) / 86400);
    $ore_messa = intval(((259200 - ($data_messa - $ultima_messa)) - ($giorni_messa * 86400)) / 3600);
    $minuti_messa = intval((((259200 - ($data_messa - $ultima_messa)) - ($giorni_messa * 86400)) - ($ore_messa * 3600)) / 60);
    $session['user']['dove_sei'] = 0;
    if ($giorni_messa < 0) $giorni_messa = 0;
    if ($ore_messa < 0) $ore_messa = 0;
    if ($minuti_messa < 0) $minuti_messa = 0;
    //ultima messa celebrata da player
    if ($carriera == 17 OR $carriera == 9) {
        $sqlno = "SELECT * FROM  messa where acctid='".$session['user']['acctid']."'";
        $resultno = db_query($sqlno) or die(db_error(LINK));
        if (db_num_rows($resultno) == 0) {
            $sqldr="INSERT INTO messa (acctid,data) VALUES ('".$session['user']['acctid']."','".$data_messa."')";
            db_query($sqldr);
        }else{
            $rowno = db_fetch_assoc($resultno);
            if ((604800 - ($data_messa - $rowno[data])) < 0) {
                $messap = 1;
            }
            $giorni_messa_player = intval((604800 - ($data_messa - $rowno[data])) / 86400);
            $ore_messa_player = intval(((604800 - ($data_messa - $rowno[data])) - ($giorni_messa_player * 86400)) / 3600);
            $minuti_messa_player = intval((((604800 - ($data_messa - $rowno[data])) - ($giorni_messa_player * 86400)) - ($ore_messa_player * 3600)) / 60);
            if ($giorni_messa_player < 0) $giorni_messa_player = 0;
            if ($ore_messa_player < 0) $ore_messa_player = 0;
            if ($minuti_messa_player < 0) $minuti_messa_player = 0;
        }
    }
    // Inserisce commento se ucciso seguace di Karnak o Drago Verde e aggiunge punti carriera
    if ($session['user']['history'] AND $dio == 1){
        $paragone=strpos($session['user']['history'],"`^ ha sconfitto");
        $session['user']['history'] = addslashes($session['user']['history']);
        $sql = "INSERT INTO commenti (section,author,comment,postdate) VALUES ('Scontri Sette','".$session['user']['acctid']."','".$session['user']['history']."',NOW())";
        db_query($sql) or die(db_error($link));
        if ($paragone === false){
        }else{
            $session['user']['punti_carriera'] += $dieci;
            $session['user']['punti_generati'] += $dieci;
            $fama = (100*$dieci*$session[user][fama_mod]);
            $session['user']['fama3mesi'] += $fama;
            debuglog("Guadagna $dieci punti carriera e $fama punti fama da Sgrios per aver ucciso un infedele. Ora ha ".$session['user']['punti_carriera']." punti carriera e ".$session['user']['fama3mesi']." punti fama");
        }
        $session['user']['history']="";
        if ($session['user']['superuser'] == 0){
            savesetting("puntisgrios", getsetting("puntisgrios",0)+$dieci);
        }else{
            print("Se tu non fossi stato un admin avrei aggiunto i punti alla setta");
        }
    }
    //fine inserimento commento

    //Excalibur: declassamento Sommo Chierico se punti fede < 100.000
    if ($session['user']['carriera']==17  AND $session['user']['punti_carriera']<100000 AND $session['user']['dio']==1){
        output("<big>`\$`b`cRetrocessione`c`n`b`0</big>",true);
        output("`#Non rispondi più ai requisiti per essere Sommo Chierico, pertanto vieni retrocesso al rango di Sacerdote.`n");
        $session['user']['carriera']=4;
        $sqlogg = "DELETE FROM custom WHERE amount='".$session['user']['acctid']."' AND area1='sommo'";
        db_query($sqlogg) or die(db_error(LINK));
    }
    //Excalibur: fine

    //Luke nuovo sistema per gestione Sommo Sacerdote
    if ($carriera == 4 OR $carriera == 9 OR $carriera == 17) {
        if ($session['user']['superuser'] == 0) {
            savesetting("gransacerdote","0");
            $sqlma = "SELECT acctid FROM accounts WHERE
            (carriera = 4 OR carriera = 9 OR carriera = 17)
            AND punti_carriera > 99999
            AND reincarna > 0
            ORDER BY punti_carriera DESC LIMIT 1";
            $resultma = db_query($sqlma) or die(db_error(LINK));
            $rowma = db_fetch_assoc($resultma);
            $gs = $rowma['acctid'];
            savesetting("gransacerdote", $gs);
        }
        if (getsetting("gransacerdote",0)!=$session['user']['acctid']){
            if ($session['user']['punti_carriera']> 100000 AND $session['user']['reincarna']>0){
                $session['user']['carriera'] = 17;
            }else{
                $session['user']['carriera'] = 4;
            }
        }else{
           output("<big>`^`b`cPromozione`c`b`0`n</big>",true);
            output("Sei diventato il Gran Sacerdote!`n`n");
            $session['user']['carriera'] = 9;
            $sqlogg = "DELETE FROM custom WHERE amount='".$session['user']['acctid']."' AND area1='sommo'";
        db_query($sqlogg) or die(db_error(LINK));
        }
    }
    /*
    //  rimozione attuale gransacerdote
    if ($session['user']['superuser'] == 0) {
    if ($carriera == 4 OR $carriera == 9) {
    $sqlm = "UPDATE accounts SET carriera = 4 WHERE carriera = 9";
    $resultm = db_query($sqlm) or die(db_error(LINK));
    $session['user']['carriera'] = 4;
    /*if (db_num_rows($resultm) != 0) {
    if ($rowm['acctid'] == $session['user']['acctid']) {
    $session['user']['carriera'] = 4;
    } else {
    $sqlu = "UPDATE accounts SET carriera='4' WHERE acctid='{$rowm[acctid]}' ";
    //output("SQL : $sqlu `n");
    db_query($sqlu) or die(db_error(LINK));
    }
    }
    // aggiornamento nuovo gran sacerdote
    $sqlma = "SELECT acctid FROM accounts WHERE
    punti_carriera >= 20000 AND
    carriera=4 AND
    superuser=0 AND
    (dragonkills > 15 OR reincarna > 0)
    ORDER BY punti_carriera DESC LIMIT 1";
    $resultma = db_query($sqlma) or die(db_error(LINK));
    $rowma = db_fetch_assoc($resultma);
    if (db_num_rows($resultma) != 0) {
    if ($rowma['acctid'] == $session['user']['acctid']) {
    $session['user']['carriera'] = 9;
    } else {
    $sqlua = "UPDATE accounts SET carriera='9' WHERE acctid='{$rowma[acctid]}' ";
    //output("SQL : $sqlua `n");
    db_query($sqlua) or die(db_error(LINK));
    }
    }
    }
    }
    */
    if (($carriera > 0 AND $carriera < 5) OR $carriera == 9 OR $carriera == 17) {
        $navi = "Rango ".$prof[$carriera];
        addnav($navi);
    } elseif ($dio == 1 AND (($carriera > 4 AND $carriera < 9) OR ($carriera > 40 AND $carriera < 45))) {
        addnav("Rango Fedele");
    }
    if ($dio == 1 AND $carriera == 0) {
        addnav("Diventa un Seguace", "chiesa.php?az=seguace");
        addnav("(Adepto)","");
    }
    if ($dio == 0 AND !$_GET['az']) {
        addnav("Azioni");
        if ($carriera==0) {
            addnav("Diventa un seguace", "chiesa.php?az=seguace");
            addnav("(Adepto)","");
        }
        addnav("Diventa un fedele", "chiesa.php?az=fedele");
        addnav("(Simpatizzante)","");
        addnav("Altro");
        //addnav("Ambasciata","ambasciata.php?setta=chiesa");
        addnav("Torna al Villaggio", "village.php");
        output("Entri nella grande chiesa di Sgrios`n");
    } elseif ($_GET['az'] == "fedele") {
        if ($dio != 1 AND $dio != 0) {
            output("Non puoi dedicarti a Sgrios e ad un'altra divinità, non fare il furbo con Sgrios.`n");
            addnav("Torna al Villaggio", "village.php");
        } else {
            output("Sei sicuro di voler diventare un fedele di Sgrios ?`n");
            output("Questa scelta è molto importante quindi pensaci bene, una volta presa non potrai tornare indietro.`n");
            addnav("Sono sicuro", "chiesa.php?az=fedele_sicuro");
            addnav("Ci devo pensare", "village.php");
        }
    } elseif ($_GET['az'] == "fedele_sicuro") {
        output("`2Sei diventato un fedele di Sgrios, benvenuto.`n Le tue responsabilità da ora in poi saranno grandi.`n");
        output("Dovrai contribuire attivamente alla diffusione del `#Verbo di Sgrios`2, e convertire gli eretici.`n");
        output("Potrai partecipare alle messe quando un Gran Sacerdote ne indirà una.`n");
        $session['user']['dio'] = 1;
        if (getsetting("gransacerdote","0") != "0") {
            systemmail(getsetting("gransacerdote","0"),"`&Nuovo fedele","`&".$session['user']['name']." `&è diventato fedele di Sgrios!");
        }
        addnav("Avvicinati all'altare", "chiesa.php");
    } elseif ($_GET['az'] == "seguace") {
        if ($carriera != 0) {
            output("Non puoi dedicarti a Sgrios e ad una professione minore, non fare il furbo con Sgrios.`n");
            addnav("Torna al Villaggio", "village.php");
        } else {
            output("Sei sicuro di voler diventare un seguace di Sgrios ?`n");
            output("Questa scelta è molto importante quindi pensaci bene, una volta presa non potrai tornare indietro.`n");
            addnav("Sono sicuro", "chiesa.php?az=seguace_sicuro");
            addnav("Ci devo pensare", "village.php");
        }
    } elseif ($_GET['az'] == "seguace_sicuro") {
        output("Sei diventato un seguace di Sgrios, benvenuto.`n");
        output("Avvicinati all'altare.`n");
        $session['user']['dio'] = 1;
        $session['user']['carriera']= 1;
        if (getsetting("gransacerdote","0") != "0") {
            systemmail(getsetting("gransacerdote","0"),"`&Nuovo seguace","`&".$session['user']['name']." `&è diventato seguace di Sgrios!");
        }
        addnav("Avvicinati all'altare", "chiesa.php");
    } elseif ($_GET['az'] == "accolito") {
        output("<big>`^`b`cPromozione`c`b`0`n</big>",true);
        output("Sei diventato un accolito di Sgrios.`n");
        output("Avvicinati all'altare.`n");
        $session['user']['carriera']= 2;
        addnav("Avvicinati all'altare", "chiesa.php");
    } elseif ($_GET['az'] == "chierico") {
        output("<big>`^`b`cPromozione`c`b`0`n</big>",true);
        output("Sei diventato un chierico di Sgrios.`n");
        output("Avvicinati all'altare.`n");
        $session['user']['carriera']= 3;
        addnav("Avvicinati all'altare", "chiesa.php");
    } elseif ($_GET['az'] == "sacerdote") {
        output("<big>`^`b`cPromozione`c`b`0`n</big>",true);
        output("Sei diventato un sacerdote di Sgrios.`n");
        output("Avvicinati all'altare.`n");
        $session['user']['carriera']= 4;
        addnav("Avvicinati all'altare", "chiesa.php");
    } elseif ($_GET['az'] == "sommo") {
        output("<big>`^`b`cPromozione`c`b`0`n</big>",true);
        output("`%Sei diventato un Sommo Chierico di Sgrios.`n");
        output("Avvicinati all'altare.`n");
        $session['user']['carriera'] = 17;
        addnav("Avvicinati all'altare", "chiesa.php");
    } elseif ($dio == 1 AND !$_GET['az']) {
        addnav("Azioni");
        if (($carriera > 0 AND $carriera < 5) OR $carriera == 9 OR $carriera == 17) {
            addnav("Fai un dono", "chiesa.php?az=dono");
            addnav("Prega", "chiesa.php?az=prega");
        }
        if (($carriera > 1 AND $carriera < 5) OR $carriera == 9 OR $carriera == 17) {
            addnav("Celebra cerimonia", "chiesa.php?az=cerimonia");
        }
        if (($carriera > 2 AND $carriera < 5) OR $carriera == 9 OR $carriera == 17) {
            addnav("Processione", "chiesa.php?az=processione");
        }
        if (($carriera > 3 AND $carriera < 5) OR $carriera == 9 OR $carriera == 17) {
            addnav("Ritiro Spirituale", "chiesa.php?az=ritiro");
        }
        if ($carriera == 9 OR $session['user']['superuser'] > 3) {
            addnav("Punisci", "chiesa.php?az=punisci");
            //Excalibur: Mail di massa
            addnav("Mail globali");
            addnav("Invia Mail a TUTTI (Fedeli e Seguaci)","chiesa.php?az=mail&op=1");
            addnav("Invia Mail a FEDELI","chiesa.php?az=mail&op=2");
            addnav("Invia Mail a Sommi Chierici","chiesa.php?az=mail&op=3");
            //Excalibur: fine Mail di massa
        }
        //Excalibur: Inserimento annuncio prossima messa
        if ($carriera == 9 OR $carriera == 17 OR $session['user']['superuser'] > 3) {
            addnav("Annuncio Messa","msgmesse.php");
        }
        //Excalibur: fine Inserimento annuncio prossima messa
        addnav("Richieste");
        if ($session['user']['punti_carriera'] > 4000 and $carriera == 1 AND $dk > 5) {
            addnav("Diventa Accolito", "chiesa.php?az=accolito");
        }
        if ($session['user']['punti_carriera'] > 10000 and $carriera == 2 AND $dk > 10) {
            addnav("Diventa Chierico", "chiesa.php?az=chierico");
        }
        if ($session['user']['punti_carriera'] > 40000 and $carriera == 3 AND $dk > 15) {
            addnav("Diventa Sacerdote", "chiesa.php?az=sacerdote");
        }
        if ($session['user']['punti_carriera'] > 100000 and $carriera == 4 AND $session['user']['reincarna']> 0) {
            addnav("Diventa Sommo Chierico", "chiesa.php?az=sommo");
        }
        if (($carriera > 0 AND $carriera < 5) OR $carriera == 9 OR $carriera == 17) {
            addnav("Supplica", "chiesa.php?az=supplica");
        }
        if (($carriera > 1 AND $carriera < 5) OR $carriera == 9 OR $carriera == 17) {
            addnav("Supplica Superiore", "chiesa.php?az=supplicas");
        }
        if (($carriera > 2 AND $carriera < 5) OR $carriera == 9 OR $carriera == 17) {
            addnav("Benedizione Minore", "chiesa.php?az=benedici");
        }
        if (($carriera > 3 AND $carriera < 5) OR $carriera == 9 OR $carriera == 17) {
            addnav("Benedizione Maggiore", "chiesa.php?az=benedici_maggiore");
        }
        addnav("Info");
        if (($carriera > 0 AND $carriera < 5) OR $carriera == 9 OR $carriera == 17) {
            addnav("I più Devoti","chiesa.php?az=devoti");
            addnav("Medita sulla tua fede","chiesa.php?az=chiedi");
        }
        addnav("Puniti","chiesa.php?az=puniti");
        addnav("Altro");
        //addnav("Ambasciata","ambasciata.php?setta=chiesa");
        addnav("Sala del Gran Sacerdote", "chiesa.php?az=gs");
        addnav("Direttive", "direttive.php");
        addnav("Torna al Villaggio", "village.php");
        output("`2Entri nella grande chiesa di `6Sgrios`2, un odore acre di incenso si diffonde nell'ambiente, alcuni fedeli
    pregano vicino all'altare. Un religioso silenzio viene tranquillamente rispettato da tutti gli avventori.`n");
    } elseif ($_GET['az'] == "prega") {
        if ($session['user']['turns'] < 1) {

            output("`5Sei troppo esausto per metterti a pregare in onore di Sgrios.`n");
            addnav("Torna all'altare", "chiesa.php");
        } else {
            output("`3Ti inginocchi e inizi a pregare. Senti la tua fede aumentare e ti raccogli maggiormente, invocando interiormente `6Sgrios`n");
            $session['user']['punti_carriera'] += (2 + $caso);
            $session['user']['punti_generati'] += (2 + $caso);
            $fama = (2 + $caso)*$session[user][fama_mod];
            $session['user']['fama3mesi'] += $fama;
            debuglog("Guadagna ".(2+$caso)." punti carriera e $fama punti fama da Sgrios con la preghiera. Ora ha ".$session['user']['punti_carriera']." punti carriera e ".$session['user']['fama3mesi']." punti fama");
            if ($session['user']['superuser'] == 0){
                savesetting("puntisgrios", getsetting("puntisgrios",0)+(2 + $caso));
            }
            $session['user']['turns'] -= 1;
            $session['user']['experience']+=($session['user']['level']*4);
            addnav("Prega", "chiesa.php?az=prega");
            addnav("`@Torna all'Altare", "chiesa.php");
        }
    } elseif ($_GET['az'] == "dono") {
        output("`3Ti avvicini alla statua di `6Sgrios`3. Alla sua base noti un contenitore in cui giacciono molti oggetti.`n");
        output("Sono le offerte fatte dai fedeli in onore di `6Sgrios`3, pellegrini che si sono recati qui per manifestare ");
        output("la propria devozione.");
        output("Cosa vuoi offrire ?.`n");
        output("Pezzi d'oro, oppure 1 gemma.`n");
        addnav("5?`^Getta 500 Oro", "chiesa.php?az=oro5");
        addnav("1?`^Getta 1000 Oro", "chiesa.php?az=oro1");
        addnav("0?`^Getta 5000 Oro", "chiesa.php?az=oro50");
        addnav("O?`^Getta 10000 Oro", "chiesa.php?az=oro10");
        addnav("`&Getta 1 Gemma", "chiesa.php?az=gemme");
        // addnav("Getta oggetto magico","chiesa.php?az=magico");
        addnav("`@Torna all'Altare", "chiesa.php");
    } elseif ($_GET['az'] == "oro5") {
        if ($session['user']['gold'] < 500) {
            output("Non hai abbastanza oro.`n");
            addnav("`@Torna all'Altare", "chiesa.php");
        } else {
            output("Getti felice i 500 Pezzi d'Oro in onore di Sgrios nel contenitore ... senti immediatamente una folata ");
            output("di energia percorrere la tua anima. La tua fede è aumentata !`n");
            addnav("5?`^Getta 500 Oro", "chiesa.php?az=oro5");
            addnav("`@Torna all'Altare", "chiesa.php");
            $session['user']['punti_carriera'] += (20 + $caso);
            $session['user']['punti_generati'] += (20 + $caso);
            $fama = (20 + $caso)*$session[user][fama_mod];
            $session['user']['fama3mesi'] += $fama;
            debuglog("Guadagna ".(20+$caso)." punti carriera e $fama punti fama da Sgrios donando 500 oro. Ora ha ".$session['user']['punti_carriera']." punti carriera e ".$session['user']['fama3mesi']." punti fama");
            if ($session['user']['superuser'] == 0){
                savesetting("puntisgrios", getsetting("puntisgrios",0)+(20 + $caso));
            }
            $session['user']['gold'] -= 500;
        }
    } elseif ($_GET['az'] == "oro1") {
        if ($session['user']['gold'] < 1000) {
            output("Non hai abbastanza oro.`n");
            addnav("`@Torna all'Altare", "chiesa.php");
        } else {
            output("Getti felice i 1.000 Pezzi d'Oro in onore di Sgrios nel contenitore ... senti immediatamente una folata ");
            output("di energia percorrere la tua anima. La tua fede è aumentata !`n");
            addnav("1?`^Getta 1000 Oro", "chiesa.php?az=oro1");
            addnav("`@Torna all'Altare", "chiesa.php");
            $session['user']['punti_carriera'] += (42 + $caso);
            $session['user']['punti_generati'] += (42 + $caso);
            $fama = (42 + $caso)*$session[user][fama_mod];
            $session['user']['fama3mesi'] += $fama;
            debuglog("Guadagna ".(42+$caso)." punti carriera e $fama punti fama da Sgrios donando 1000 oro. Ora ha ".$session['user']['punti_carriera']." punti carriera e ".$session['user']['fama3mesi']." punti fama");
            if ($session['user']['superuser'] == 0){
                savesetting("puntisgrios", getsetting("puntisgrios",0)+(42 + $caso));
            }
            $session['user']['gold'] -= 1000;
        }
    } elseif ($_GET['az'] == "oro50") {
        if ($session['user']['gold'] < 5000) {
            output("Non hai abbastanza oro.`n");
            addnav("`@Torna all'Altare", "chiesa.php");
        } else {
            output("Getti felice i 5.000 Pezzi d'Oro in onore di Sgrios nel contenitore ... senti immediatamente una folata ");
            output("di energia percorrere la tua anima. La tua fede è aumentata !`n");
            addnav("0?`^Getta 5000 Oro", "chiesa.php?az=oro50");
            addnav("`@Torna all'Altare", "chiesa.php");
            $session['user']['punti_carriera'] += (215 + $caso);
            $session['user']['punti_generati'] += (215 + $caso);
            $fama = (215 + $caso)*$session[user][fama_mod];
            $session['user']['fama3mesi'] += $fama;
            debuglog("Guadagna ".(215+$caso)." punti carriera e $fama punti fama da Sgrios donando 5000 oro. Ora ha ".$session['user']['punti_carriera']." punti carriera e ".$session['user']['fama3mesi']." punti fama");
            if ($session['user']['superuser'] == 0){
                savesetting("puntisgrios", getsetting("puntisgrios",0)+(215 + $caso));
            }
            $session['user']['gold'] -= 5000;
        }
    } elseif ($_GET['az'] == "oro10") {
        if ($session['user']['gold'] < 10000) {
            output("Non hai abbastanza oro.`n");
            addnav("`@Torna all'Altare", "chiesa.php");
        } else {
            output("Getti felice i 10.000 Pezzi d'Oro in onore di Sgrios nel contenitore ... senti immediatamente una folata ");
            output("di energia percorrere la tua anima. La tua fede è aumentata !`n");
            addnav("O?`^Getta 10000 Oro", "chiesa.php?az=oro10");
            addnav("`@Torna all'Altare", "chiesa.php");
            $session['user']['punti_carriera'] += (440 + $caso);
            $session['user']['punti_generati'] += (440 + $caso);
            $fama = (440 + $caso)*$session[user][fama_mod];
            $session['user']['fama3mesi'] += $fama;
            debuglog("Guadagna ".(440+$caso)." punti carriera e $fama punti fama da Sgrios donando 10000 oro. Ora ha ".$session['user']['punti_carriera']." punti carriera e ".$session['user']['fama3mesi']." punti fama");
            if ($session['user']['superuser'] == 0){
                savesetting("puntisgrios", getsetting("puntisgrios",0)+(440 + $caso));
            }
            $session['user']['gold'] -= 10000;
        }
    } elseif ($_GET['az'] == "gemme") {
        if ($session['user']['gems'] < 1) {
            output("`4Non possiedi nessuna gemma.`n");
            addnav("`@Torna all'Altare", "chiesa.php");
        } else {
            output("`#Getti felice 1 gemma in onore di `6Sgrios `#nel contenitore ... senti immediatamente una folata ");
            output("di energia percorrere la tua anima. La tua fede è aumentata !`n");
            addnav("`&Getta 1 Gemma", "chiesa.php?az=gemme");
            addnav("Torna all'Altare", "chiesa.php");
            $session['user']['punti_carriera'] += (100 + $caso);
            $session['user']['punti_generati'] += (100 + $caso);
            $fama = (100 + $caso)*$session[user][fama_mod];
            $session['user']['fama3mesi'] += $fama;
            debuglog("Guadagna ".(100+$caso)." punti carriera e $fama punti fama da Sgrios donando una gemma. Ora ha ".$session['user']['punti_carriera']." punti carriera e ".$session['user']['fama3mesi']." punti fama");
            if ($session['user']['superuser'] == 0){
                savesetting("puntisgrios", getsetting("puntisgrios",0)+(100 + $caso));
            }
            $session['user']['gems'] -= 1;
            if ($cento > 89) {
                $buff = array("name" => "`\$Tocco di Sgrios", "rounds" => 15, "wearoff" => "`!La tua Forza Divina scompare e torni normale", "defmod" => 1.6, "roundmsg" => "Senti Sgrios al tuo fianco!", "activate" => "defense");
                $session['bufflist']['magicweak'] = $buff;
                debuglog("Guadagna anche il Tocco di Sgrios donando la gemma");
                output("`%Un leggera aura luminosa ti circonda. Senti il grande potere di `6Sgrios `%entrare in te!`n");
            }
        }
    } elseif ($_GET['az'] == "supplica") {
        if ($session['user']['punti_carriera'] < 50 OR $session['user']['turns'] < 1) {
            output("`#In ginocchio con le mani protese davanti a te inizi a supplicare il grande Sgrios .`n");
            output("Vieni attraversato da un brivido freddo e percepisci un tremore percorrere la tua anima.`n");
            output("La tua fede non soddisfa Sgrios.`n");
            addnav("Torna all'altare", "chiesa.php");
        } else {
            output("`@In ginocchio, con le mani protese davanti a te, inizi a supplicare il grande `6Sgrios`@.`n");
            output("Immediatamente un calore si diffonde nel tuo corpo ed entri in comunione con `6Sgrios`@ !!");
            addnav("Supplica", "chiesa.php?az=supplica");
            addnav("Torna all'altare", "chiesa.php");
            $session['user']['punti_carriera'] -= (20 + $venti);
            $session['user']['turns'] -= 1;
            $session['user']['suppliche'] += 1;
            $return=20-($session['user']['dragonkills']+(4*$session['user']['reincarna']));
            if($return < 3)$return=3;
            $gemme=1;
            if($session['user']['suppliche'] <= $return){
                output("Guadagni 1 turno.`n");
                $session['user']['turns'] += 1;
                $caso5 = 100+mt_rand(100, 400);
                $caso10 = 200+mt_rand(200, 800);
                $gemme+=1;
            }elseif($session['user']['suppliche'] <= ($return*2) AND $session['user']['suppliche']  > $return){
                $caso5 = 100+mt_rand(100, 400);
                $caso10 = 200+mt_rand(200, 800);
            }elseif($session['user']['suppliche'] <= ($return*3) AND $session['user']['suppliche']  > ($return*2)){
                $caso5 = 100+mt_rand(100, 200);
                $caso10 = 200+mt_rand(200, 400);
            }elseif($session['user']['suppliche'] <= ($return*4) AND $session['user']['suppliche']  > ($return*3)){
                $caso5 = mt_rand(100, 200);
                $caso10 = mt_rand(200, 400);
            }elseif($session['user']['suppliche']  > ($return*4)){
                $caso5 = mt_rand(0, 100);
                $caso10 = mt_rand(0, 200);
            }
            if ($cento > 89) {
                $buff = array("name" => "`\$Tocco di Sgrios Maggiore", "rounds" => 40, "wearoff" => "`!La tua Forza Divina scompare e torni normale", "defmod" => 1.6, "roundmsg" => "Senti Sgrios al tuo fianco!", "activate" => "defense");
                $session['bufflist']['magicweak'] = $buff;
                output("`\$Un leggera aura luminosa ti circonda. Senti l'immenso potere di `6Sgrios `\$entrare in te!`n");
                debuglog("guadagna Tocco di Sgrios Maggiore alla chiesa con la supplica");
            } elseif ($cento <= 89 and $cento > 79) {
                output("Il tempo oggi scorrerà più lento potrai combattere altre $gemme creature.`n");
                $session['user']['turns'] += $gemme;
                debuglog("guadagna 5 FF alla chiesa con la supplica");
            } elseif ($cento <= 79 and $cento > 69) {
                output("$gemme gemme compare davanti a te.`n");
                debuglog("guadagna $gemme gemme alla chiesa con la supplica");
                $session['user']['gems'] += $gemme;
            } elseif ($cento <= 69 and $cento > 49) {
                output("Un mucchietto di $caso10 pezzi d'oro compare davanti a te.`n");
                debuglog("guadagna $caso10 oro alla chiesa con la supplica");
                $session['user']['gold'] += $caso10;
            } elseif ($cento <= 49 and $cento > 0) {
                output("Un mucchietto di $caso5 pezzi d'oro compare davanti a te.`n");
                debuglog("guadagna $caso5 oro alla chiesa con la supplica");
                $session['user']['gold'] += $caso5;
                $buff = array("name" => "`\$Tocco di Sgrios", "rounds" => 25, "wearoff" => "`!La tua Forza Divina scompare e torni normale", "defmod" => 1.2, "roundmsg" => "Senti Sgrios al tuo fianco!", "activate" => "defense");
                $session['bufflist']['magicweak'] = $buff;
                output("`%Un leggera aura luminosa ti circonda. Senti il grande potere di `6Sgrios`% entrare in te!`n");
                debuglog("guadagna Tocco di Sgrios alla chiesa con la supplica");
            }
        }
    } elseif ($_GET['az'] == "supplicas") {
        if ($session['user']['punti_carriera'] < 150 OR $session['user']['turns'] < 2) {
            output("`&In ginocchio con le mani protese davanti a te inizi a supplicare il grande `6Sgrios`&.`n");
            output("Vieni attraversato da un brivido freddo e senti la tua anima tremare.`n");
            output("La tua fede non soddisfa `6Sgrios`&.`n");
            addnav("`@Torna all'Altare", "chiesa.php");
        } else {
            output("`&In ginocchio con le mani protese davanti a te inizi a supplicare il grande `6Sgrios`&.`n");
            addnav("Supplica Superiore", "chiesa.php?az=supplicas");
            addnav("Torna all'altare", "chiesa.php");
            $session['user']['punti_carriera'] -= (50 + $cento);
            $session['user']['turns'] -= 2;
            $session['user']['suppliche'] += 2;
            $return=20-($session['user']['dragonkills']+(4*$session['user']['reincarna']));
            if($return < 3)$return=3;
            $gemme=1;
            if($session['user']['suppliche'] <= ($return*1)){
                output("Recuperi i 2 turni spesi per la supplica.`n");
                $session['user']['turns'] += 2;
                $caso8 = 300+mt_rand(300, 1700);
                $caso9 = 400+mt_rand(400, 2600);
                $caso10 = 500+mt_rand(500, 3500);
                $gemme+=2;
            }elseif($session['user']['suppliche'] <= ($return*2) AND $session['user']['suppliche']  > ($return*1)){
                output("Recuperi 1 turno dei 2 spesi per la supplica.`n");
                $session['user']['turns'] += 1;
                $caso8 = 300+mt_rand(300, 1700);
                $caso9 = 400+mt_rand(400, 2600);
                $caso10 = 500+mt_rand(500, 3500);
                $gemme+=1;
            }elseif($session['user']['suppliche'] <= ($return*3) AND $session['user']['suppliche']  > ($return*2)){
                $caso8 = 300+mt_rand(300, 1000);
                $caso9 = 400+mt_rand(400, 1700);
                $caso10 = 500+mt_rand(500, 2600);
            }elseif($session['user']['suppliche'] <= ($return*4) AND $session['user']['suppliche']  > ($return*3)){
                $caso8 = 300+mt_rand(300, 500);
                $caso9 = 400+mt_rand(400, 850);
                $caso10 = 500+mt_rand(500, 1300);
            }elseif($session['user']['suppliche']  > ($return*4)){
                $caso8 = mt_rand(300, 500);
                $caso9 = mt_rand(400, 850);
                $caso10 = mt_rand(500, 1300);
            }
            if ($cento > 97) {
                //crea oggetto 102
                $pot = mt_rand(10,20);
                $potn = mt_rand(0,1);
                $att = mt_rand(0,1);
                $dif = mt_rand(1,8);
                $turn = mt_rand(0,1);
                $vit = mt_rand(0,36);
                $valore = ($pot*$potn)+($att*10)+($dif*10)+($turn*6)+($vit)+10;
                $livello = intval($valore/30);
                $usuraextra = 0;
                $durata = 50 + 5*$valore + 100*$att + 100*$dif + 10*$turn + 2*$vit;
                $duratamagica = 0;
                $usuramagicaextra=0;
                if ($turn > 0) $duratamagica = 25 + 10*$turn;
                $sqlno = "SELECT * FROM  oggetti_nomi where serbatoio=102 ORDER BY RAND() LIMIT 1";
                $resultno = db_query($sqlno) or die(db_error(LINK));
                $rowno = db_fetch_assoc($resultno);
                $nome=$rowno['nome']." di Sgrios";
                $desc=$rowno['nome']." forgiata da Sgrios per ".$session[user][login];
                $resultno = db_query($sqlno) or die(db_error(LINK));
                $rowno = db_fetch_assoc($resultno);
                $sql="INSERT INTO oggetti (nome, descrizione, dove, dove_origine, livello, valore, potenziamenti,attack_help,defence_help,turns_help,hp_help,
                        usura, usuramax, usuraextra, usuramagica, usuramagicamax, usuramagicaextra)
                        VALUES ('{$nome}','{$desc}','102','1','$livello','$valore','$potn','$att','$dif','$turn','$vit',
                        '$durata', '$durata', '$usuraextra', '$duratamagica', '$duratamagica', '$usuraextramagica')";
                db_query($sql);
                //estrai oggetto 102
                $sql = "SELECT * FROM oggetti WHERE dove = 102 ORDER BY RAND() LIMIT 1";
                $result = db_query($sql) or die(db_error(LINK));
                $row = db_fetch_assoc($result);
                output ("`^Davanti a te compare ...`n`n");
                output ("Oggetto :" . $row['nome'] . "`n`n");
                output ("Descrizione:" . $row['descrizione'] . "`n`n");
                if ($session['user']['zaino'] == "0") {
                    output ("`#Raccogli l'oggetto e lo metti nello zaino.`n`n");
                    debuglog("riceve l'oggetto {$row['nome']} con la supplica superiore");
                    $session['user']['zaino'] = $row['id_oggetti'];
                    $oggetto_id = $row['id_oggetti'];
                    $sqlu = "UPDATE oggetti SET dove=0 WHERE id_oggetti='$oggetto_id'";
                    db_query($sqlu) or die(db_error(LINK));
                } else {
                    output ("`4Sfortunatamente non hai posto nello zaino e lo devi lasciare qui.`n`n");
                    $buff = array("name" => "`\$Tocco di Sgrios maggiore", "rounds" => 40, "wearoff" => "`!La tua Forza Divina scompare e torni normale", "defmod" => 1.4, "roundmsg" => "Senti Sgrios al tuo fianco!", "activate" => "defense");
                    $session['bufflist']['magicweak'] = $buff;
                    output("`!La bontà di `^Sgrios`! è grande, una potente aura luminosa ti circonda. `%Senti il potere di `^Sgrios `%entrare in te!`n");
                    debuglog("Non riceve oggetto per zaino pieno e guadagna Tocco di Sgrios Maggiore alla chiesa con la supplica superiore");
                }
            } elseif ($cento <= 97 and $cento > 89) {
                $buff = array("name" => "`\$Tocco di Sgrios Supremo", "rounds" => 85, "wearoff" => "`!La tua Forza Divina scompare e torni normale", "defmod" => 1.6, "roundmsg" => "Senti Sgrios al tuo fianco!", "activate" => "defense");
                $session['bufflist']['magicweak'] = $buff;
                output("Un potente aura luminosa ti circonda. Senti l'incommensurabile potere di Sgrios entrare in te.`n");
                debuglog("guadagna Tocco di Sgrios Supremo alla chiesa con la supplica superiore");
            } elseif ($cento <= 89 and $cento > 79) {
                output("Un mucchietto di $caso10 pezzi d'oro compare davanti a te.`n");
                debuglog("riceve $caso10 oro alla chiesa con la supplica superiore");
                $session['user']['gold'] += $caso10;
            } elseif ($cento <= 79 and $cento > 69) {
                output("Un mucchietto di $gemme gemme compare davanti a te.`n");
                debuglog("riceve $gemme gemme alla chiesa con la supplica superiore");
                $session['user']['gems'] += $gemme;
            } elseif ($cento <= 69 and $cento > 49) {
                output("Un mucchietto di $caso9 pezzi d'oro compare davanti a te.`n");
                debuglog("riceve $caso9 oro alla chiesa con la supplica superiore");
                $session['user']['gold'] += $caso9;
            } elseif ($cento <= 49 and $cento > 0) {
                output("Un mucchietto di $caso8 pezzi d'oro compare davanti a te.`n");
                debuglog("riceve $caso8 oro alla chiesa con la supplica superiore");
                $session['user']['gold'] += $caso8;
                $buff = array("name" => "`\$Tocco di Sgrios", "rounds" => 40, "wearoff" => "`!La tua Forza Divina scompare e torni normale", "defmod" => 1.2, "roundmsg" => "Senti Sgrios al tuo fianco!", "activate" => "defense");
                $session['bufflist']['magicweak'] = $buff;
                output("`%Un leggera aura luminosa ti circonda. Senti il grande potere di `6Sgrios`% entrare in te!`n");
            }
        }
    } elseif ($_GET['az'] == "cerimonia") {
        if ($session['user']['turns'] < 5) {
            output("`7Sei troppo esausto per tenere una cerimonia in onore di Sgrios.`n");
            addnav("`@Torna all'Altare", "chiesa.php");
        } else {
            output("Celebri una cerimonia in onore di Sgrios.`n");
            $session['user']['punti_carriera'] += (20 + $venti);
            $session['user']['punti_generati'] += (20 + $venti);
            $fama = (20 + $venti)*$session[user][fama_mod];
            $session['user']['fama3mesi'] += $fama;
            debuglog("Guadagna ".(20+$venti)." punti carriera e $fama punti fama da Sgrios con una cerimonia. Ora ha ".$session['user']['punti_carriera']." punti carriera e ".$session['user']['fama3mesi']." punti fama");
            if ($session['user']['superuser'] == 0){
                savesetting("puntisgrios", getsetting("puntisgrios",0)+(20 + $caso));
            }
            $session['user']['turns'] -= 5;
            $session['user']['experience']+=($session['user']['level']*20);
            addnav("Cerimonia", "chiesa.php?az=cerimonia");
            addnav("`@Torna all'Altare", "chiesa.php");
        }
    } elseif ($_GET['az'] == "processione") {
        if ($session['user']['turns'] < 5 OR $session['user']['playerfights'] < 1) {
            output("`\$Sei troppo esausto per celebrare una processione in onore di `6Sgrios`\$.`n");
            addnav("`@Torna all'Altare", "chiesa.php");
        } else {
            output("`@Celebri una processione in onore di `6Sgrios`@. Vi partecipano molti pellegrini e i vostri canti e `n");
            output("le vostre preghiere salgono al `6Sommo`@. Senti la tua fede potenziata !!");
            $session['user']['punti_carriera'] += (40 + $venti);
            $session['user']['punti_generati'] += (40 + $venti);
            $fama = (40 + $venti)*$session[user][fama_mod];
            $session['user']['fama3mesi'] += $fama;
            debuglog("Guadagna ".(40+$venti)." punti carriera e $fama punti fama da Sgrios con una processione. Ora ha ".$session['user']['punti_carriera']." punti carriera e ".$session['user']['fama3mesi']." punti fama");
            if ($session['user']['superuser'] == 0){
                savesetting("puntisgrios", getsetting("puntisgrios",0)+(40 + $caso));
            }
            $session['user']['turns'] -= 5;
            $session['user']['playerfights'] -= 1;
            $session['user']['experience']+=($session['user']['level']*30);
            addnav("Processione", "chiesa.php?az=processione");
            addnav("Torna all'altare", "chiesa.php");
        }
    } elseif ($_GET['az'] == "ritiro") {
        if ($session['user']['turns'] < 7 or $session['user']['playerfights'] < 1) {
            output("Sei troppo esausto per ritirarti e meditare in onore di Sgrios.`n");
            addnav("Torna all'altare", "chiesa.php");
        } else {
            output("`5Celebri un ritiro spirituale in onore di `6Sgrios`5. Il priore del monastero di fornisce una `n");
            output("cella dove ti puoi raccogliere in preghiera solitaria. Al termine senti che la tua fede è aumentata !!");
            $session['user']['punti_carriera'] += (80 + $venti);
            $session['user']['punti_generati'] += (80 + $venti);
            $fama = (80 + $venti)*$session[user][fama_mod];
            $session['user']['fama3mesi'] += $fama;
            debuglog("Guadagna ".(80+$venti)." punti carriera e $fama punti fama da Sgrios con un ritiro. Ora ha ".$session['user']['punti_carriera']." punti carriera e ".$session['user']['fama3mesi']." punti fama");
            if ($session['user']['superuser'] == 0){
                savesetting("puntisgrios", getsetting("puntisgrios",0)+(80 + $caso));
            }
            $session['user']['turns'] -= 7;
            $session['user']['playerfights'] -= 1;
            $session['user']['experience']+=($session['user']['level']*40);
            addnav("Ritiro Spirituale", "chiesa.php?az=ritiro");
            addnav("`@Torna all'Altare", "chiesa.php");
        }
    } elseif ($_GET['az'] == "gs") {
        $session['user']['dove_sei'] = 1;
        $sqlpic = "SELECT acctid FROM accounts WHERE dove_sei=1";
        $resultpic = db_query($sqlpic);
        $player_inchiesa = db_num_rows($resultpic);
        output("`&Entri nella Sala delle Cerimonie del `3Gran Sacerdote`&.`n`n");
        $GS = getsetting("gransacerdote",0);
        $sql = "SELECT `name` FROM `accounts` WHERE `acctid` = $GS";
        $result = db_query($sql) or die(db_error(LINK));
        $row = db_fetch_assoc($result);
        if ($GS == 0) {
            output("`\$Nessuno raggiunge i requisiti richiesti da `6Sgrios `\$per occupare la carica di `3Gran Sacerdote`\$.`n`n");
            output("`6La prossima messa potrà essere celebrata fra : `^`b$giorni_messa`b`6 giorni, `^`b$ore_messa`b`6 ore e `^`b$minuti_messa`b`6 minuti.`n`n");
            output("`3Sono presenti nella sala del Gran Sacerdote : `#`b$player_inchiesa`b `3fedeli.`n`n");
            //} elseif (db_num_rows($result) > 1) {
            //    output("`$`b`iErrore segnalalo ad un admin, ci sono troppi Gran Sacerdote !!!`b`i`n");
        } else {
            output("`&L'attuale Gran Sacerdote è : `b`@".$row['name']."`b`n`n");
            output("`6La prossima messa potrà essere celebrata fra : `^`b$giorni_messa`b`6 giorni, `^`b$ore_messa`b`6 ore e `^`b$minuti_messa`b`6 minuti.`n`n");
            output("`3Sono presenti nella sala del Gran Sacerdote : `#`b$player_inchiesa`b `3fedeli.`n`n");
            if ($carriera == 17 OR $carriera == 9) {
                output("`3Tu potrai celebrare la prossima `#Messa`3 fra : `^`b$giorni_messa_player`b`3 giorni, `^`b$ore_messa_player`b`3 ore e `^`b$minuti_messa_player`b`3 minuti.`n`n");

            }
        }
        addnav("Azioni");
        if (($carriera == 17 OR $carriera == 9) AND $messa == 1 AND $messap==1 AND $session['user']['punti_carriera']>=5000) {
            addnav("`^Celebra `&Messa", "chiesa.php?az=messaconferma");
        }
        if ($potere_messa == 1 AND $session['user']['messa'] == 0) {
            addnav("`&Partecipa alla Messa", "chiesa.php?az=partecipa");
        }
        addnav("Exit");
        addnav("Sala delle Riunioni","salariunioni.php");
        addnav("`@Torna all'Altare", "chiesa.php");
        addcommentary();
//        checkday();
        viewcommentary("Chiesa di Sgrios", "prega",30,25);
        //Excalibur: concorso indovina la frase
        if (getsetting("indovinello", "") == "sbloccato" AND ($session['user']['dragonkills'] > 0 OR $session['user']['reincarna'] > 0)){
            addnav("Grande Concorso delle Sette (Indovina la Frase)","chiesa.php?az=tryguess");
        }
        if (getsetting("indovinello", "") == "chiuso" AND ($session['user']['dragonkills'] > 0 OR $session['user']['reincarna'] > 0)){
            addnav("Grande Concorso delle Sette (Ritira Premio)","chiesa.php?az=lookguess");
        }
        if (getsetting("indovinello", "") == "bloccato" AND ($session['user']['dragonkills'] > 0 OR $session['user']['reincarna'] > 0)){
            addnav("Grande Concorso delle Sette (Standby)","chiesa.php?az=lockguess");
        }
    } elseif ($_GET['az'] == "tryguess") {
        $session['user']['premioindovinello'] = 0;
        $sql = "SELECT area FROM custom WHERE area1 = 'frasesgriosnascosta'";
        $result = db_query ($sql) or die(db_error(LINK));
        $row = db_fetch_assoc($result);
        $frase1 = stripslashes($row['area']);
        output("`n`c`@Attualmente la situazione della frase da indovinare è la seguente:`n");
        output("`b`#<tt><font size='+1'>`n".$frase1."</tt></font>`b`c`n",true);
        if ($session['user']['superuser'] > 3) {
           $sql = "SELECT area FROM custom WHERE area1 = 'frasesgrios'";
           $result = db_query ($sql) or die(db_error(LINK));
           $row = db_fetch_assoc($result);
           $frase = stripslashes($row['area']);
           output("`c`b`#<tt><font size='+1'>".$frase."</tt></font>`b`c`n",true);
        }
        $dataodierna = date("m-d");
        $dataguess = substr($session['user']['guess'], 5, 5);
        if ($dataguess != $dataodierna) {
           output("`@Non hai ancora effettuato il tentativo di indovinare la frase per oggi, vuoi provarci ?`n");
           addnav("Si, fammi provare","chiesa.php?az=tryguess1");
           addnav("No, ci devo pensare","chiesa.php?az=gs");
        }else{
           output("`@Hai già effettuato il tentativo odierno per indovinare la frase.`nTorna domani e potrai ");
           output("riprovarci.`n`n");
           addnav("`&Sala del Gran Sacerdote", "chiesa.php?az=gs");
        }
    } elseif ($_GET['az'] == "tryguess1") {
        $sql = "SELECT area FROM custom WHERE area1 = 'frasesgriosnascosta'";
        $result = db_query ($sql) or die(db_error(LINK));
        $row = db_fetch_assoc($result);
        $frase1 = stripslashes($row['area']);
        output("`n`c`@Attualmente la situazione della frase da indovinare è la seguente:`n");
        output("`b`#`n<tt><font size='+1'>".$frase1."</tt></font>`b`c`n",true);
        if ($session['user']['superuser'] > 3) {
           $sql = "SELECT area FROM custom WHERE area1 = 'frasesgrios'";
           $result = db_query ($sql) or die(db_error(LINK));
           $row = db_fetch_assoc($result);
           $frase = stripslashes($row['area']);
           output("`c`b`#`n<tt><font size='+1'>".$frase."</tt></font>`b`c`n",true);
        }
        output("`@Scrivi la frase che pensi sia corretta.`n");
        output("<form action='chiesa.php?az=tryguess2' method='POST'><input name='try' value=''><input type='submit' class='button' cols='80' value='Frase'>`n",true);
        addnav("","chiesa.php?az=tryguess2");
        addnav("Ci ho ripensato","chiesa.php?az=gs");
    } elseif ($_GET['az'] == "tryguess2") {
        $tryfrase = stripslashes($_POST['try']);
        $sql = "SELECT area FROM custom WHERE area1 = 'frasesgrios'";
        $result = db_query ($sql) or die(db_error(LINK));
        $row = db_fetch_assoc($result);
        $frase = stripslashes($row['area']);
        $sql = "SELECT area FROM custom WHERE area1 = 'frasesgriosnascosta'";
        $result = db_query ($sql) or die(db_error(LINK));
        $row = db_fetch_assoc($result);
        $frase1 = stripslashes($row['area']);
        $session['user']['guess'] = date("Y-m-d");
        output("Hai inserito questa frase:`n");
        output("`c`b`#`n<tt><font size='+1'>".$tryfrase."</tt></font>`b`c`n",true);
        output("Situazione attuale:`n");
        output("`c`b`#`n<tt><font size='+1'>".$frase1."</tt></font>`b`c`n",true);
        if ($session['user']['superuser'] > 3) {
           output("Frase originale:`n");
           output("`c`b`#<tt><font size='+1'>".$frase."</tt></font>`b`c`n",true);
        }
        if ($frase != $tryfrase) {
           debuglog("ha tentato, senza fortuna, di indovinare la frase misteriosa dell'indovinello");
           output("`(Spiacente, il tuo tentativo non ha dato i frutti sperati, non hai indovinato la frase.`n");
           output("Potrai riprovare domani. Buona fortuna !!!`n");
           addnav("`&Sala del Gran Sacerdote", "chiesa.php?az=gs");
           savesetting("tentasgrios",(getsetting("tentasgrios",0)+1));
        } else {
           savesetting("tentasgrios",(getsetting("tentasgrios",0)+1));
           $premio = getsetting("premioconcorso",5);
           output("`c`b<font size='+1'>`!C`@O`#N`5G`3R`^A`4T`1U`2L`2A`4Z`5I`6O`5N`7I`3!</font>`c`b`n`n",true);
           output("`@Hai indovinato la frase misteriosa !!!! `n");
           output("Hai fatto vincere alla tua setta il mitico premio del `&Grande Concorso `iIndovina la Frase`i`@ !!`n`n");
           output("Tu, in qualità di risolutore della frase misteriosa, guadagni un premio doppio !!`n");
           output("Il concorso viene messo in pausa fino al prossimo round, e verranno distribuiti i premi.`n");
           output("Ti facciamo i nostri complimenti per essere riuscito ad indovinare la frase, ti sei guadagnato ");
           output("`&".($premio*2)." GEMME`@ !!!`n`n");
           $minortry = array();
           $minortry = unserialize(getsetting("minortry",""));
           if ($minortry['try'] > getsetting("tentasgrios",0)){
               $minortry['try'] = getsetting("tentasgrios",0);
               $minortry['name'] = "Sgrios";
               $minortry = serialize($minortry);
               savesetting("minortry",$minortry);
           }
           savesetting("indovinello","chiuso");
           savesetting("settaindovinello","sgrios");
           savesetting("solutoreindovinello",$session['user']['name']);
           debuglog("ha indovinato la frase misteriosa dell'indovinello del Grande Concorso");
           debuglog("riceve $premio gemme come premio per l'indovinello del Grande Concorso");
           $session['user']['gems'] += ($premio * 2);
           $session['user']['premioindovinello'] = 1;
           addnav("`&Sala del Gran Sacerdote", "chiesa.php?az=gs");
        }
    } elseif ($_GET['az'] == "lookguess") {
        $settaindovinello = getsetting("settaindovinello","nessuna");
        if ($settaindovinello != "sgrios" AND $settaindovinello != "tutti") {
            output("`\$Purtroppo il `&Grande Concorso `iIndovina la Frase`i`\$ è stato vinto dalla setta `&$settaindovinello`n");
            output("Dovrete impegnarmi maggiormente nel prossimo concorso per aggiudicarvelo.`n");
            output("La frase misteriosa era:`n`n");
            $sql = "SELECT area FROM custom WHERE area1 = 'frasesgrios'";
            $result = db_query ($sql) or die(db_error(LINK));
            $row = db_fetch_assoc($result);
            $frase = stripslashes($row['area']);
            output("`n`c`b`#<tt><font size='+1'>".$frase."</tt></font>`b`n",true);
            $sql = "SELECT area FROM custom WHERE area1 = 'frasesgriosnascosta'";
            $result = db_query ($sql) or die(db_error(LINK));
            $row = db_fetch_assoc($result);
            $frase1 = stripslashes($row['area']);
            output("`b`#<tt><font size='+1'>".$frase1."</tt></font>`b`c`n",true);
            addnav("`&Sala del Gran Sacerdote", "chiesa.php?az=gs");
        } else {
            output("`c`b<font size='+1'>`!C`@O`#N`5G`3R`^A`4T`1U`2L`2A`4Z`5I`6O`5N`7I`3!</font>`c`b`n`n",true);
            output("`^La tua setta si è aggiudicata il `&Grande Concorso `iIndovina la Frase`i`^.`n");
            output("La frase è stata indovinata da `(".getsetting("solutoreindovinello","sconosciuto")."`^, e la frase era:`n");
            $sql = "SELECT area FROM custom WHERE area1 = 'frasesgrios'";
            $result = db_query ($sql) or die(db_error(LINK));
            $row = db_fetch_assoc($result);
            $frase = stripslashes($row['area']);
            output("`n`c`b`#<tt><font size='+1'>".$frase."</tt></font>`b`c`n",true);
            if ($session['user']['premioindovinello'] == 1) {
               output("`^Hai già ritirato il premio che ti spettava, ora puoi solo aspettare che venga indetto un ");
               output("nuovo concorso, non puoi fare nient'altro qui.`n");
               addnav("`&Sala del Gran Sacerdote", "chiesa.php?az=gs");
            } else {
               $premio = getsetting("premioconcorso",5);
               $session['user']['premioindovinello'] = 1;
               output("`^Ora ti verrà consegnato il premio, cioè `@$premio Gemme`^, fanne buon uso !!`n");
               $session['user']['gems'] += $premio;
               debuglog("riceve $premio gemme come premio per l'indovinello del Grande Concorso");
               addnav("`&Sala del Gran Sacerdote", "chiesa.php?az=gs");
            }
        }
    } elseif ($_GET['az'] == "lockguess") {
        output("`@Il `&Grande Concorso `iIndovina la Frase`i`@ è attualmente bloccato.`nProbabilmente gli Admin ");
        output("stanno pensando a qualche frase particolarmente complicata da farti indovinare.`n");
        output("Controlla più tardi se viene abilitata l'opzione per indovinare la frase.`n`n");
        addnav("`&Sala del Gran Sacerdote", "chiesa.php?az=gs");
        //Excalibur: fine concorso indovina la frase
    } elseif ($_GET['az'] == "messaconferma") {
        output("`n`7Attenzione, stai per celebrare la messa in onore di `&Sgrios`7. Sei sicuro di volerlo fare?`n`n");
        output("<table border=0 cellpadding=2 cellspacing=1 align=center>",true);
                output("<tr class='trlight'><td><a href=chiesa.php?az=messa>`bCelebra la messa`b</a></td></tr>", true);
        output("</table>",true);
                addnav("","chiesa.php?az=messa");
                addnav("`&Sala del Gran Sacerdote", "chiesa.php?az=gs");
    } elseif ($_GET['az'] == "messa") {
        if ($messa == 1) {
            output("`#Dopo aver raccolto una massa di fedeli, inizi a cantilenare le tue preghiere. Celebri la messa in ");
            output("onore di `6Sgrios`#.`n La funzione consuma `bmolti`b dei tuoi punti carriera.");
            $session['user']['punti_carriera']-=5000;
            addnav("Sala del Gran Sacerdote", "chiesa.php?az=gs");
            savesetting("tempomessa", $data_messa);
            $sql = "UPDATE accounts SET messa = 0 WHERE dio = 1";
            db_query($sql);
            $session['user']['messa'] = 0;
            $sql = "UPDATE messa SET data = '".$data_messa."'WHERE acctid = '".$session['user']['acctid']."'";
            db_query($sql);
            $sql = "SELECT acctid FROM accounts WHERE dove_sei=1";
            $result = db_query($sql);
            $player_inchiesa = db_num_rows($result);
            if ($player_inchiesa <=2) $session['user']['punti_carriera']=-100;
            savesetting("player_inchiesa", $player_inchiesa);
            // Maximus modifica messa, salvo i presenti per dare la possibilità
            // a tutti i partecipanti di ricevere la stessa percentuale dei premi
            savesetting("partecipanti_messa", $player_inchiesa);
            debuglog("celebra la messa in onore di Sgrios con $player_inchiesa partecipanti");
            // Fine
            //Sook, calcolo base del premio (nuovo sistema)
            //calcolo valori dei presenti
            $sqlbase = "SELECT COUNT(acctid) AS presenti, SUM(punti_carriera) AS punti_presenti, SUM(dragonkills) AS dk_presenti, SUM(reincarna) AS reinc_presenti FROM accounts WHERE dove_sei='1' AND (dragonkills>'2' OR reincarna>'0') AND superuser=0 GROUP BY dio";
            $resultbase = db_query($sqlbase);
            $rowbase = db_fetch_assoc($resultbase);
            $player_inchiesa = $rowbase['presenti'];
            $punti_carriera_presenti = $rowbase['punti_presenti'];
            $dk_presenti = $rowbase['dk_presenti'] + 30 * $rowbase['reinc_presenti'];
            $base1 = $punti_carriera_presenti/$dk_presenti/$player_inchiesa;
            //calcolo valori totali dei fedeli
            $sqlbp = "SELECT COUNT(acctid) AS fedeli, SUM(punti_carriera) AS punti_totali, SUM(dragonkills) AS dk_totali, SUM(reincarna) AS reinc_totali FROM accounts WHERE dio='1' AND (dragonkills>'2' OR reincarna>'0') AND superuser=0 GROUP BY dio";
            $resultbp = db_query($sqlbp);
            $rowbp = db_fetch_assoc($resultbp);
            $player_infede = $rowbp['fedeli'];
            $punti_carriera_totali = $rowbp['punti_totali'];
            //$dk_totali = $rowbase['dk_totali'] + 30 * $rowbase['reinc_totali'];
            $dk_totali = $rowbp['dk_totali'] + 30 * $rowbp['reinc_totali'];
            $base2 = $punti_carriera_totali/$dk_totali/$player_infede;

            $rapporto_fedeli = $player_inchiesa / $player_infede;
            $rapporto_dk = $dk_presenti / $dk_totali;
            $rapporto_carriera = $punti_carriera_presenti / $punti_carriera_totali;

            $base=min($base1,$base2);
            $presenza=$rapporto_fedeli+$rapporto_dk+$rapporto_carriera;
            $base+=$presenza;
            $base+=(0.02*$player_inchiesa);
            savesetting("baseSgrios",$base);
            //fine calcolo base
        } else {
            output("`^ERRORE: la messa è stata già celebrata da un altro personaggio, dovrai aspettare prima di celebrarne un'altra");
            addnav("`&Sala del Gran Sacerdote", "chiesa.php?az=gs");
        }
    } elseif ($_GET[az] == "partecipa") {
        // Maximus display dei partecipanti, tanto per far vedere quanti sono...
        $partecipanti_messa = getsetting("partecipanti_messa", 0);
        //output("`#Ti unisci al canto in onore di `6Sgrios`#, tutti i presenti sono concentrati, l'aria inizia scintillare,
        output("`#Insieme ad altri `6{$partecipanti_messa} `#fedeli, ti unisci al canto in onore di `6Sgrios`#, tutti i presenti sono concentrati, l'aria inizia scintillare,
    l'immenso potere di Sgrios scende sui fedeli presenti.`n`n");
        $session['user']['messa'] = 1;
        if ($session['user']['punti_carriera'] != -100){
            output("Nella mente ti si focalizzano questi elementi, Sgrios attende che tu decida.`n");
            output("`^Oro.`n");
            output("`&Vita.`n");
            output("`#Abilità.`n");
            addnav("`^Oro", "chiesa.php?az=partecipa_oro");
            addnav("`&Vita", "chiesa.php?az=partecipa_vita");
            if ($carriera != 0) {
                addnav("`#Abilità", "chiesa.php?az=partecipa_abilita");
            }
        }else{
            output("`\$La tua avidità non piace a `6Sgrios`\$. `nVuoi tenere solo per te i doni che `6Sgrios`\$ così ");
            output("generosamente mette a disposizione di `b`@TUTTI`b`\$ i suoi fedeli ? `nAdesso ne pagherai le conseguenze ");
            output("stolto mortale !!!`n`n");
            $session['user']['punti_carriera']=200;
            $carriera=1;
            $gemloss=round($session['user']['gems']/2);
            output("`2Un `b`i`&F `#U `&L `#M `&I `#N `&E`b`i`2 cade dal cielo e ti colpisce in pieno petto !!!`n`\$Sei morto !!!`n");
            output("`#Perdi tutto l'oro che avevi con te, e `6Sgrios`# punisce la tua avidità togliendoti il 20% della tua esperienza, ");
            output("la metà delle gemme che possedevi, `bTUTTI`b i tuoi punti carriera, e ti retrocede a semplice `iSeguace`i.");
            $session['user']['alive']=false;
            debuglog("ha voluto fare il furbo celebrando una messa solo per sè e perde oro, gemme, 20% exp e resta con 200 PuntiCarriera !!!");
            addnews("`\$".$session['user']['name']." `@ha voluto approfittare della generosità di `6Sgrios`@, celebrando la messa per se stesso,
        e non volendo condividere i doni di `6Sgrios`@ con gli altri fedeli. È stato punito duramente per la sua avidità !!");
            $session['user']['hitpoints']=0;
            $session['user']['messa'] = 1;
            $session['user']['experience']*=0.8;
            $session['user']['gold']=0;
            $session['user']['gems']-=$gemloss;
            addnav("Terra delle Ombre","shades.php");
        }
    } elseif ($_GET['az'] == "partecipa_oro") {
/*        // Maximus modifica messa
        $partecipanti_messa = getsetting("partecipanti_messa", 0);
        $session['user']['suppliche']=0;
        $oro_messa = (($partecipanti_messa / 10) * $partecipanti_messa * 50);
        if ($oro_messa < 5000)$oro_messa=5000;
        if ($oro_messa > 50000)$oro_messa=50000;
        if ($carriera==9) $oro_messa *= 1.5;
        output("`^Davanti a te si materializzano `b$oro_messa`b Pezzi d'Oro. La messa celebrata in onore di `6Sgrios`^ ");
        output("ha raggiunto il `6Sommo`^, che dall'alto della sua benevolenza ha accolto le tue implorazioni. `n");
        addnav("`&Sala del Gran Sacerdote", "chiesa.php?az=gs");
        $session['user']['gold'] += $oro_messa;
        debuglog("riceve $oro_messa oro alla chiesa per aver partecipato alla messa");
        // Fine*/

        // Vecchio sistema
        /*
        $session['user']['suppliche']=0;
        $oro_messa = (($player_inchiesa * $caso) * $player_inchiesa * $player_inchiesa * $player_inchiesa);
        if ($oro_messa > 20000)$oro_messa=20000;
        if ($carriera==9 OR $carriera==17) $oro_messa *= 1.5;
        output("`^Davanti a te si materializzano `b$oro_messa`b Pezzi d'Oro. La messa celebrata in onore di `6Sgrios`^ ");
        output("ha raggiunto il `6Sommo`^, che dall'alto della sua benevolenza ha accolto le tue implorazioni. `n");
        addnav("`&Sala del Gran Sacerdote", "chiesa.php?az=gs");
        $session['user']['gold'] += $oro_messa;
        debuglog("riceve $oro_messa oro alla chiesa per aver partecipato alla messa");
        */

        //Nuovo sistema (Sook)
        //calcolo coefficiente bonus carriera
        switch($session['uesr']['carriera']) {
            case 2:
            case 7:
            case 43:
                $grado = 1.2;
            break;
            case 3:
                $grado = 1.4;
            break;
            case 4:
                $grado = 1.6;
            break;
            case 17:
                $grado = 1.8;
            break;
            case 9:
                $grado = 2;
            break;
            case 6:
            case 42:
                $grado = 1.1;
            break;
            case 8:
            case 44:
                $grado = 1.3;
            break;
            default:
                $grado = 1;
            break;
        }
        $partecipanti_messa = getsetting("partecipanti_messa", 0);
        $base=getsetting("baseSgrios",0)+($session['user']['punti_generati']/1000);
        $session['user']['punti_generati']=0;
        $session['user']['suppliche']=0;
        $dkpl=$session['user']['dragonkills'];
        if ($dkpl>40) $dkpl=40;
        $oro_messa = $base * 200 * (1 + ($session['user']['level']/10) + (($dkpl+$session['user']['reincarna']*50)/10)) * $grado;
        $b1 = e_rand(-1, 1);
        $b2 = e_rand(-1, 1);
        $bonus = (4+$b1+$b2)/4;
        $oro_messa = round($oro_messa*$bonus);
        if ($oro_messa < 4000)$oro_messa=4000;
        if ($oro_messa > 50000)$oro_messa=50000;
        output("`^Davanti a te si materializzano `b$oro_messa`b Pezzi d'Oro. La messa celebrata in onore di `6Sgrios`^ ");
        output("ha raggiunto il `6Sommo`^, che dall'alto della sua benevolenza ha accolto le tue implorazioni. `n");
        addnav("`&Sala del Gran Sacerdote", "chiesa.php?az=gs");
        $session['user']['gold'] += $oro_messa;
        debuglog("riceve $oro_messa oro alla chiesa per aver partecipato alla messa");
        // Fine

    } elseif ($_GET['az'] == "partecipa_vita") {
/*        // Maximus modifica messa
        $partecipanti_messa = getsetting("partecipanti_messa", 0);
        $session['user']['suppliche']=0;
        $vita_messa = round(($partecipanti_messa / 10)*2.5);
        if ($vita_messa < 2) $vita_messa = 2;
        if ($vita_messa > 25) $vita_messa = 25;
        if ($carriera==9) $vita_messa *= 1.2;
        output("`&Senti la tua Vitalità crescere. La messa celebrata in onore di `6Sgrios`& ");
        output("ha raggiunto il `6Sommo`&, che dall'alto della sua benevolenza ha accolto le tue implorazioni. `n");
        addnav("`&Sala del Gran Sacerdote", "chiesa.php?az=gs");
        $session['user']['maxhitpoints'] += intval($vita_messa);
        debuglog("riceve $vita_messa HP permanenti alla chiesa per aver partecipato alla messa");
        // Fine*/

        // Vecchio sistema
        /*
        $session['user']['suppliche']=0;
        $vita_messa = round($player_inchiesa * $caso / 2);
        if ($vita_messa < 10) $vita_messa = 10;
        if ($carriera==9 OR $carriera==17) $vita_messa *= 1.2;
        output("ha raggiunto il `6Sommo`&, che dall'alto della sua benevolenza ha accolto le tue implorazioni. `n");
        addnav("`&Sala del Gran Sacerdote", "chiesa.php?az=gs");
        $session['user']['maxhitpoints'] += $vita_messa;
        debuglog("riceve $vita_messa HP permanenti alla chiesa per aver partecipato alla messa");
        */
        //Nuovo sistema (Sook)
        $partecipanti_messa = getsetting("partecipanti_messa", 0);
        $base=getsetting("baseSgrios",0)+$session['user']['punti_generati']/1000;
        $session['user']['punti_generati']=0;
        $session['user']['suppliche']=0;
        $vita_messa = ($base/3) + intval($session['user']['dragonkills']/5) + $session['user']['reincarna']*6;
        $b1 = e_rand(-1, 1);
        $b2 = e_rand(-1, 1);
        $bonus = (4+$b1+$b2)/4;
        $vita_messa = round($vita_messa*$bonus);
        if ($vita_messa < 5) $vita_messa = 5;
        if ($vita_messa > 35) $vita_messa = 35;
        output("`&Senti la tua Vitalità crescere. La messa celebrata in onore di `6Sgrios`& ");
        output("ha raggiunto il `6Sommo`&, che dall'alto della sua benevolenza ha accolto le tue implorazioni. `n");
        addnav("`&Sala del Gran Sacerdote", "chiesa.php?az=gs");
        $session['user']['maxhitpoints'] += intval($vita_messa);
        debuglog("riceve $vita_messa HP permanenti alla chiesa per aver partecipato alla messa");
        // Fine

    } elseif ($_GET['az'] == "partecipa_abilita") {
/*        // Maximus modifica messa
        $partecipanti_messa = getsetting("partecipanti_messa", 0);
        $session['user']['suppliche']=0;
        $abilita_messa = intval($partecipanti_messa * $partecipanti_messa * 0.5);
        if ($abilita_messa < 500) $abilita_messa = 500;
        if ($abilita_messa > 5000) $abilita_messa = 5000;
        if ($carriera==9) $abilita_messa *= 1.5;
        if (($carriera > 0 AND $carriera < 5) OR $carriera == 9 OR $carriera == 17) {
            output("`#Senti la tua fede crescere. La messa celebrata in onore di `6Sgrios`# ");
            output("ha raggiunto il `6Sommo`#, che dall'alto della sua benevolenza ha accolto le tue implorazioni. `n");
        }
        if ($carriera > 4 AND $carriera < 9) {
            output("`#Senti la tua abilità di fabbro crescere. La messa celebrata in onore di `6Sgrios`# ");
            output("ha raggiunto il `6Sommo`#, che dall'alto della sua benevolenza ha accolto le tue implorazioni. `n");
        }
        if ($carriera > 40 AND $carriera < 45) {
            output("`#Senti la tua abilità di mago crescere. La messa celebrata in onore di `6Sgrios`# ");
            output("ha raggiunto il `6Sommo`#, che dall'alto della sua benevolenza ha accolto le tue implorazioni. `n");
        }
        addnav("Sala del Gran Sacerdote", "chiesa.php?az=gs");
        $session['user']['punti_carriera'] += $abilita_messa;
        $fama = $abilita_messa*$session[user][fama_mod];
        $session['user']['fama3mesi'] += $fama;
        debuglog("Guadagna $abilita_messa punti carriera e $fama punti fama alla chiesa per aver partecipato alla messa. Ora ha ".$session['user']['punti_carriera']." punti carriera e ".$session['user']['fama3mesi']." punti fama");
        if ($session['user']['superuser'] == 0){
            savesetting("puntisgrios", getsetting("puntisgrios",0)+$abilita_messa);
        }
        // Fine*/

        // Vecchio sistema
        /*
        $session['user']['suppliche']=0;
        $abilita_messa = (($player_inchiesa * $caso) * $player_inchiesa * 1.5);
        if ($abilita_messa < 100) $abilita_messa = 100;
        if ($carriera==9 OR $carriera==17) $abilita_messa *= 1.5;
        if (($carriera > 0 AND $carriera < 5) OR $carriera == 9) {
            output("`#Senti la tua fede crescere. La messa celebrata in onore di `6Sgrios`# ");
            output("ha raggiunto il `6Sommo`#, che dall'alto della sua benevolenza ha accolto le tue implorazioni. `n");
        }
        if ($carriera > 4 AND $carriera < 9) {
            output("`#Senti la tua abilità di fabbro crescere. La messa celebrata in onore di `6Sgrios`# ");
            output("ha raggiunto il `6Sommo`#, che dall'alto della sua benevolenza ha accolto le tue implorazioni. `n");
        }
        addnav("Sala del Gran Sacerdote", "chiesa.php?az=gs");
        $session['user']['punti_carriera'] += $abilita_messa;
        $fama = $abilita_messa*$session[user][fama_mod];
        $session['user']['fama3mesi'] += $fama;
        debuglog("Guadagna $fama punti fama da Sgrios. Ora ha ".$session['user']['fama3mesi']." punti");
        if ($session['user']['superuser'] == 0){
            savesetting("puntisgrios", getsetting("puntisgrios",0)+$abilita_messa);
        }
        debuglog("riceve $abilita_messa punti carriera alla chiesa per aver partecipato alla messa");
        */
        //Nuovo sistema (Sook)
        //calcolo coefficiente bonus carriera
        switch($session['uesr']['carriera']) {
            case 2:
            case 7:
            case 43:
                $grado = 1.2;
            break;
            case 3:
                $grado = 1.4;
            break;
            case 4:
                $grado = 1.6;
            break;
            case 17:
                $grado = 1.8;
            break;
            case 9:
                $grado = 2;
            break;
            case 6:
            case 42:
                $grado = 1.1;
            break;
            case 8:
            case 44:
                $grado = 1.3;
            break;
            default:
                $grado = 1;
            break;
        }

        $partecipanti_messa = getsetting("partecipanti_messa", 0);
        $base=getsetting("baseSgrios",0)+$session['user']['punti_generati']/1000;
        $session['user']['punti_generati']=0;
        $session['user']['suppliche']=0;
        $dkpl=$session['user']['dragonkills'];
        if ($dkpl>40) $dkpl=40;
        $abilita_messa = $base * 20 * (1 + (($dkpl+$session['user']['reincarna']*50)/10)) * $grado;
        $b1 = e_rand(-1, 1);
        $b2 = e_rand(-1, 1);
        $bonus = (4+$b1+$b2)/4;
        $abilita_messa = round($abilita_messa*$bonus);
        if ($abilita_messa < 200) $abilita_messa = 200;
        if ($abilita_messa > 5000) $abilita_messa = 5000;
        if (($carriera > 0 AND $carriera < 5) OR $carriera == 9 OR $carriera == 17) {
            output("`#Senti la tua fede crescere. La messa celebrata in onore di `6Sgrios`# ");
            output("ha raggiunto il `6Sommo`#, che dall'alto della sua benevolenza ha accolto le tue implorazioni. `n");
        }
        if ($carriera > 4 AND $carriera < 9) {
            output("`#Senti la tua abilità di fabbro crescere. La messa celebrata in onore di `6Sgrios`# ");
            output("ha raggiunto il `6Sommo`#, che dall'alto della sua benevolenza ha accolto le tue implorazioni. `n");
        }
        if ($carriera > 40 AND $carriera < 45) {
            output("`#Senti la tua abilità di mago crescere. La messa celebrata in onore di `6Sgrios`# ");
            output("ha raggiunto il `6Sommo`#, che dall'alto della sua benevolenza ha accolto le tue implorazioni. `n");
        }
        addnav("Sala del Gran Sacerdote", "chiesa.php?az=gs");
        $session['user']['punti_carriera'] += $abilita_messa;
        $fama = $abilita_messa*$session[user][fama_mod];
        $session['user']['fama3mesi'] += $fama;
        debuglog("Guadagna $abilita_messa punti carriera e $fama punti fama alla chiesa per aver partecipato alla messa. Ora ha ".$session['user']['punti_carriera']." punti carriera e ".$session['user']['fama3mesi']." punti fama");
        if ($session['user']['superuser'] == 0){
            savesetting("puntisgrios", getsetting("puntisgrios",0)+$abilita_messa);
        }
        // Fine

    } elseif ($_GET['az'] == "devoti") {
        output("Magicamente, in uno specchio incastonato d'oro Sgrios mostra i nomi dei suoi più devoti figli.`n`n");
        $sqlo = "SELECT * FROM accounts WHERE superuser = 0 AND punti_carriera >= 1 AND ((carriera >=1 AND carriera <=4) OR carriera=9 OR carriera=17) ORDER BY punti_carriera DESC";
        $resulto = db_query($sqlo) or die(db_error(LINK));
        output("<table cellspacing=0 cellpadding=2 align='center'><tr><td>&nbsp;</td><td>`bNome`b</td><td>`bLivello`b</td></tr>", true);
        if (db_num_rows($resulto) == 0) {
            output("<tr><td colspan=4 align='center'>`&Non ci sono chierici in paese`0</td></tr>", true);
        }
        $countrow = db_num_rows($resulto);
        for ($i=0; $i<$countrow; $i++){
        //for ($i = 0;$i < db_num_rows($resulto);$i++) {
            $rowo = db_fetch_assoc($resulto);
            if ($row['name'] == $session['user']['name']) {
                output("<tr bgcolor='#007700'>", true);
            } else {
                output("<tr class='" . (($i + $k) % 2?"trlight":"trdark") . "'>", true);
            }
            if ($rowo[carriera] == 1) {
                $livello = 'Seguace';
            } elseif ($rowo['carriera'] == 2) {
                $livello = 'Accolito';
            } elseif ($rowo['carriera'] == 3) {
                $livello = 'Chierico';
            } elseif ($rowo['carriera'] == 4) {
                $livello = 'Sacerdote';
            } elseif ($rowo['carriera'] == 9) {
                $livello = 'Gran Sacerdote';
            }
            $carr = $rowo['carriera'];
            output("<td>" . ($i + 1) . ".</td><td>".$rowo['name']."</td><td>".$prof[$carr]."</td></tr>", true);
        }
        output("</table>", true);
        addnav("`@Torna all'Altare", "chiesa.php");
    } elseif ($_GET['az'] == "benedici") {
        $oggetto = $session['user']['oggetto'];
        if ($session['user']['oggetto'] == "0") {
            $ogg = "Nulla";
        } else {
            $sqlo = "SELECT * FROM oggetti WHERE id_oggetti = $oggetto";
            $resultoo = db_query($sqlo) or die(db_error(LINK));
            $rowo = db_fetch_assoc($resultoo);
            $ogg = $rowo['nome'];
        }
        output ("<table>", true);
        output ("<tr><td>`#Puoi benedire  : </td><td>`b`^" . $ogg . "`b</td></tr>", true);
        if ($rowo['pregiato']==true AND getsetting("blocco_valore",0)=="1") output ("<tr><td>`^`bL'oggetto è pregiato`b </td><td></td></tr>", true);
        output ("<tr><td>`@Potenziamenti: </td><td>`b`\$" . $potenziamenti[$rowo['potenziamenti']] . "`b</td></tr>", true);
        output ("</table>", true);
        if ($rowo['potenziamenti'] > 0 AND $session['user']['punti_carriera'] > 2999) {
            addnav("`&Benedici oggetto", "chiesa.php?az=benedici_conferma");
        }else if ($rowo['potenziamenti'] == 0) {
            output("`5`nL'oggetto non ha potenziamenti residui con cui benedirlo.`n");
        }else if ($session['user']['punti_carriera'] < 3000) {
            output("`%`nNon hai Punti Carriera a sufficienza per eseguire una Benedizione Minore sull'oggetto.`n");
        }
        addnav("Vai all'altare", "chiesa.php");
    } elseif ($_GET['az'] == "benedici_conferma") {
        output("`%Cosa vuoi migliorare?`n`n");
        $sqlo = "SELECT pregiato FROM oggetti WHERE id_oggetti = ".$session['user']['oggetto'];
        $resultoo = db_query($sqlo) or die(db_error(LINK));
        $rowo = db_fetch_assoc($resultoo);
//        output("`\$Attacco`n");
        output("`@Vita`n");
        output("`&Difesa`n");
        if ($rowo['pregiato']==false OR getsetting("blocco_valore",0)=="0") output("`^Valore`n");
//        addnav("`\$Attacco", "chiesa.php?az=benedici_attacco");
        addnav("`@Vita", "chiesa.php?az=benedici_vita");
        addnav("`&Difesa", "chiesa.php?az=benedici_difesa");
        if ($rowo['pregiato']==false OR getsetting("blocco_valore",0)=="0") addnav("`^Valore", "chiesa.php?az=benedici_valore");
        addnav("");
        addnav("Torna all'altare", "chiesa.php");
    } elseif ($_GET['az'] == "benedici_attacco") {
        $oggetto = $session['user']['oggetto'];
        $bonus = $caso * mt_rand(8,12);
        $sqlu = "UPDATE oggetti SET attack_help=attack_help+$caso, potenziamenti=potenziamenti-1, valore=valore+$bonus WHERE id_oggetti='$oggetto'";
        db_query($sqlu) or die(db_error(LINK));
        //modifica per aggiornamento dell'usura
        $usuraextra =  $caso * 100 + $bonus * 5;
        $sqlusura = "SELECT usuramax FROM oggetti WHERE id_oggetti='$oggetto'";
        $resultus = db_query($sqlusura) or die(db_error(LINK));
        $rowus = db_fetch_assoc($resultus);
        if ($rowus[usuramax]>0) {
            $sqlu = "UPDATE oggetti SET usuramax=usuramax+$usuraextra WHERE id_oggetti='$oggetto'";
            db_query($sqlu) or die(db_error(LINK));
        }
        //fine modifica usura
        output("`7Una luce `\$`brossa`b`7 circonda il tuo oggetto.`n");
        output ("La forza d'attacco del tuo oggetto è stata migliorata di `b$caso`b punti.");
        output("`nEd il suo valore è aumentato di $bonus gemme`n");
        //modifica di Excalibur
        $session['user']['attack']+=$caso;
        $session['user']['bonusattack']+=$caso;
        //fine modifica
        debuglog("ha benedetto l'oggetto ($oggetto) migliorando di $caso la forza d'attacco e aggiungendo $bonus gemme al valore");
        addnav("`@Vai all'Altare", "chiesa.php");
        $session['user']['punti_carriera'] -= (1000 * $caso);
    } elseif ($_GET['az'] == "benedici_vita") {
        $oggetto = $session['user']['oggetto'];
        $vita = $caso * 5;
        $bonus = $caso * mt_rand(8,12);
        $sqlu = "UPDATE oggetti SET hp_help=hp_help+$vita, potenziamenti=potenziamenti-1, valore=valore+$bonus WHERE id_oggetti='$oggetto'";
        db_query($sqlu) or die(db_error(LINK));
        //modifica per aggiornamento dell'usura
        $usuraextra =  $vita * 2 + $bonus * 5;
        $sqlusura = "SELECT usuramax FROM oggetti WHERE id_oggetti='$oggetto'";
        $resultus = db_query($sqlusura) or die(db_error(LINK));
        $rowus = db_fetch_assoc($resultus);
        if ($rowus[usuramax]>0) {
            $sqlu = "UPDATE oggetti SET usuramax=usuramax+$usuraextra WHERE id_oggetti='$oggetto'";
            db_query($sqlu) or die(db_error(LINK));
        }
        //fine modifica usura
        output("`7Una luce `@`bverde`b`7 circonda il tuo oggetto.`n");
        output ("La forza vitale del tuo oggetto è stata migliorata di `b$vita`b punti.");
        output("`nEd il suo valore è aumentato di $bonus gemme`n");
        //modifica di Excalibur
        $session['user']['hitpoints']+=$vita;
        $session['user']['maxhitpoints']+=$vita;
        //fine modifica
        debuglog("ha benedetto l'oggetto ($oggetto) migliorando di $vita la forza vitale e aggiungendo $bonus gemme al valore");
        addnav("`@Vai all'Altare", "chiesa.php");
        $session['user']['punti_carriera'] -= (1000 * $caso);
    }elseif ($_GET['az'] == "benedici_difesa") {
        $oggetto = $session['user']['oggetto'];
        $difesa = $caso + e_rand (0,1);
        $bonus = $difesa * mt_rand(8,12);
        $sqlu = "UPDATE oggetti SET defence_help=defence_help+$difesa, potenziamenti=potenziamenti-1, valore=valore+$bonus WHERE id_oggetti='$oggetto'";
        db_query($sqlu) or die(db_error(LINK));
        output("`7Una luce `b`&bianca`7`b circonda il tuo oggetto.`n");
        output ("La forza protettrice che offre il tuo oggetto è stata migliorata di $caso punti.");
        output("`nEd il suo valore è aumentato di $bonus gemme`n");
        //modifica per aggiornamento dell'usura
        $usuraextra =  $difesa * 100 + $bonus * 5 + $caso * 50;
        $sqlusura = "SELECT usuramax FROM oggetti WHERE id_oggetti='$oggetto'";
        $resultus = db_query($sqlusura) or die(db_error(LINK));
        $rowus = db_fetch_assoc($resultus);
        if ($rowus[usuramax]>0) {
            $sqlu = "UPDATE oggetti SET usuramax=usuramax+$usuraextra, usuraextra=usuraextra+$caso*50 WHERE id_oggetti='$oggetto'";
            db_query($sqlu) or die(db_error(LINK));
        }
        //fine modifica usura
        //modifica di Excalibur
        $session['user']['defence']+=$difesa;
        $session['user']['bonusdefence']+=$difesa;
        //fine modifica
        debuglog("ha benedetto l'oggetto ($oggetto) migliorando di $difesa la difesa e aggiungendo $bonus gemme al valore");
        addnav("`@Vai all'Altare", "chiesa.php");
        $session['user']['punti_carriera'] -= (1000 * $caso);
    }elseif ($_GET['az'] == "benedici_valore") {
        $oggetto = $session['user']['oggetto'];
        $bonus = ($caso+2) * (mt_rand(8,12));
        $sqlu = "UPDATE oggetti SET pregiato=1, potenziamenti=potenziamenti-1, valore=valore+$bonus WHERE id_oggetti='$oggetto'";
        db_query($sqlu) or die(db_error(LINK));
        //modifica per aggiornamento dell'usura
        $usuraextra = $bonus * 5;
        $sqlusura = "SELECT usuramax FROM oggetti WHERE id_oggetti='$oggetto'";
        $resultus = db_query($sqlusura) or die(db_error(LINK));
        $rowus = db_fetch_assoc($resultus);
        if ($rowus[usuramax]>0) {
            $sqlu = "UPDATE oggetti SET usuramax=usuramax+$usuraextra WHERE id_oggetti='$oggetto'";
            db_query($sqlu) or die(db_error(LINK));
        }
        //fine modifica usura
        output("`7Una luce `b`^giallastra`7`b circonda il tuo oggetto.`n");
        output ("Il valore del tuo oggetto è stato aumentato di $bonus gemme.");
        debuglog("ha benedetto l'oggetto ($oggetto) migliorando di $bonus il suo valore");
        addnav("`@Vai all'Altare", "chiesa.php");
        $session['user']['punti_carriera'] -= (1000 * $caso);
        // benedizione maggiore
    }elseif ($_GET['az'] == "benedici_maggiore") {
        $oggetto = $session['user']['oggetto'];
        if ($session['user']['oggetto'] == "0") {
            $ogg = "Nulla";
        } else {
            $sqlo = "SELECT * FROM oggetti WHERE id_oggetti = $oggetto";
            $resultoo = db_query($sqlo) or die(db_error(LINK));
            $rowo = db_fetch_assoc($resultoo);
            $ogg = $rowo['nome'];
        }
        output ("<table>", true);
        output ("<tr><td>`#Puoi benedire  : </td><td>`b`^" . $ogg . "`b</td></tr>", true);
        if ($rowo['pregiato']==true AND getsetting("blocco_valore",0)=="1") output ("<tr><td>`^`bL'oggetto è pregiato`b </td><td></td></tr>", true);
        output ("<tr><td>`@Potenziamenti: </td><td>`b`\$" . $potenziamenti[$rowo['potenziamenti']] . "`b</td></tr>", true);
        output ("</table>", true);
        if ($rowo['potenziamenti'] > 0 AND $session['user']['punti_carriera'] > 8999) {
            addnav("`&Benedici oggetto", "chiesa.php?az=benedici_maggiore_conferma");
        }else if ($rowo['potenziamenti'] == 0) {
            output("`5`nL'oggetto non ha potenziamenti residui con cui benedirlo.`n");
        }else if ($session['user']['punti_carriera'] < 9000) {
            output("`%`nNon hai Punti Carriera a sufficienza per eseguire una Benedizione Maggiore sull'oggetto.`n");
        }
        addnav("`@Vai all'Altare", "chiesa.php");
    } elseif ($_GET['az'] == "benedici_maggiore_conferma") {
        output("`%Cosa vuoi migliorare?`n`n");
        $sqlo = "SELECT pregiato FROM oggetti WHERE id_oggetti = ".$session['user']['oggetto'];
        $resultoo = db_query($sqlo) or die(db_error(LINK));
        $rowo = db_fetch_assoc($resultoo);
//        output("`\$Attacco`n");
        output("`@Vita`n");
        output("`&Difesa`n");
        if ($rowo['pregiato']==false OR getsetting("blocco_valore",0)=="0") output("`^Valore`n");
//        addnav("`\$Attacco", "chiesa.php?az=benedici_maggiore_attacco");
        addnav("`@Vita", "chiesa.php?az=benedici_maggiore_vita");
        addnav("`&Difesa", "chiesa.php?az=benedici_maggiore_difesa");
        if ($rowo['pregiato']==false OR getsetting("blocco_valore",0)=="0") addnav("`^Valore", "chiesa.php?az=benedici_maggiore_valore");
        addnav("");
        addnav("Torna all'altare", "chiesa.php");
    } elseif ($_GET['az'] == "benedici_maggiore_attacco") {
        $oggetto = $session['user']['oggetto'];
        $attacco = $caso + e_rand (2,4);
        $bonus = $attacco * (mt_rand(8,12) + 5);
        $sqlu = "UPDATE oggetti SET attack_help=attack_help+$attacco, potenziamenti=potenziamenti-1, valore=valore+$bonus WHERE id_oggetti='$oggetto'";
        db_query($sqlu) or die(db_error(LINK));
        //modifica per aggiornamento dell'usura
        $usuraextra =  $attacco * 100 + $bonus * 5 + $caso * 100;
        $sqlusura = "SELECT usuramax FROM oggetti WHERE id_oggetti='$oggetto'";
        $resultus = db_query($sqlusura) or die(db_error(LINK));
        $rowus = db_fetch_assoc($resultus);
        if ($rowus[usuramax]>0) {
            $sqlu = "UPDATE oggetti SET usuramax=usuramax+$usuraextra, usuraextra=usuraextra+$caso*100 WHERE id_oggetti='$oggetto'";
            db_query($sqlu) or die(db_error(LINK));
        }
        //fine modifica usura
        output("`7Una luce `\$`brossa`b`7 circonda il tuo oggetto.`n");
        output ("La forza d'attacco del tuo oggetto è stata migliorata di `b".($caso + 1)."`b punti.");
        output("`nEd il suo valore è aumentato di $bonus gemme`n");
        //modifica di Excalibur
        $session['user']['attack'] += $attacco;
        $session['user']['bonusattack'] += $attacco;
        //fine modifica
        debuglog("ha benedetto l'oggetto ($oggetto) migliorando di $attacco l'attacco e $bonus gemme il suo valore");
        addnav("`@Vai all'Altare", "chiesa.php");
        $session['user']['punti_carriera'] -= (3000 * $caso);
    } elseif ($_GET['az'] == "benedici_maggiore_vita") {
        $oggetto = $session['user']['oggetto'];
        $vita = $caso * 8;
        $bonus = ($caso + 2) * mt_rand(8,12);
        $sqlu = "UPDATE oggetti SET hp_help=hp_help+$vita, potenziamenti=potenziamenti-1, valore=valore+$bonus WHERE id_oggetti='$oggetto'";
        db_query($sqlu) or die(db_error(LINK));
        //modifica per aggiornamento dell'usura
        $usuraextra =  $vita * 2 + $bonus * 5;
        $sqlusura = "SELECT usuramax FROM oggetti WHERE id_oggetti='$oggetto'";
        $resultus = db_query($sqlusura) or die(db_error(LINK));
        $rowus = db_fetch_assoc($resultus);
        if ($rowus[usuramax]>0) {
            $sqlu = "UPDATE oggetti SET usuramax=usuramax+$usuraextra WHERE id_oggetti='$oggetto'";
            db_query($sqlu) or die(db_error(LINK));
        }
        //fine modifica usura
        output("`7Una luce `@`bverde`b`7 circonda il tuo oggetto.`n");
        output ("La forza vitale del tuo oggetto è stata migliorata di `b$vita`b punti.");
        output("`nEd il suo valore è aumentato di $bonus gemme`n");
        //modifica di Excalibur
        $session['user']['hitpoints']+=$vita;
        $session['user']['maxhitpoints']+=$vita;
        //fine modifica
        debuglog("ha benedetto l'oggetto ($oggetto) migliorando di $vita la forza vitale e aggiungendo $bonus gemme al valore");
        addnav("`@Vai all'Altare", "chiesa.php");
        $session['user']['punti_carriera'] -= (3000 * $caso);
    } elseif ($_GET[az] == "benedici_maggiore_difesa") {
        $oggetto = $session['user']['oggetto'];
        $difesa = $caso + e_rand (2,4);
        $bonus = $difesa * (mt_rand(8,12) + 5);
        $sqlu = "UPDATE oggetti SET defence_help=defence_help+$difesa, potenziamenti=potenziamenti-1, valore=valore+$bonus WHERE id_oggetti='$oggetto'";
        db_query($sqlu) or die(db_error(LINK));
        output("`7Una luce `b`&bianca`7`b circonda il tuo oggetto.`n");
        output ("La forza protettrice che offre il tuo oggetto è stata migliorata di ".($caso + 1)." punti.");
        output("`nEd il suo valore è aumentato di $bonus gemme`n");
        //modifica per aggiornamento dell'usura
        $usuraextra =  $attacco * 100 + $bonus * 5 + $caso * 100;
        $sqlusura = "SELECT usuramax FROM oggetti WHERE id_oggetti='$oggetto'";
        $resultus = db_query($sqlusura) or die(db_error(LINK));
        $rowus = db_fetch_assoc($resultus);
        if ($rowus[usuramax]>0) {
            $sqlu = "UPDATE oggetti SET usuramax=usuramax+$usuraextra, usuraextra=usuraextra+$caso*100 WHERE id_oggetti='$oggetto'";
            db_query($sqlu) or die(db_error(LINK));
        }
        //fine modifica usura
        //modifica di Excalibur
        $session['user']['defence'] += $difesa;
        $session['user']['bonusdefence'] += $difesa;
        //fine modifica
        debuglog("ha benedetto l'oggetto migliorando di $difesa la difesa e $bonus gemme il suo valore");
        addnav("`@Vai all'Altare", "chiesa.php");

        $session['user']['punti_carriera'] -= (3000 * $caso);
    }elseif ($_GET['az'] == "benedici_maggiore_valore") {
        $oggetto = $session['user']['oggetto'];
        $bonus = ($caso + 2) * (mt_rand(8,12)+10);
        $sqlu = "UPDATE oggetti SET pregiato=1, potenziamenti=potenziamenti-1, valore=valore+$bonus WHERE id_oggetti='$oggetto'";
        db_query($sqlu) or die(db_error(LINK));
        //modifica per aggiornamento dell'usura
        $usuraextra = $bonus * 5;
        $sqlusura = "SELECT usuramax FROM oggetti WHERE id_oggetti='$oggetto'";
        $resultus = db_query($sqlusura) or die(db_error(LINK));
        $rowus = db_fetch_assoc($resultus);
        if ($rowus[usuramax]>0) {
            $sqlu = "UPDATE oggetti SET usuramax=usuramax+$usuraextra WHERE id_oggetti='$oggetto'";
            db_query($sqlu) or die(db_error(LINK));
        }
        //fine modifica usura
        output("`7Una luce `b`^giallastra`7`b circonda il tuo oggetto.`n");
        output ("Il valore del tuo oggetto è stato aumentato di $bonus punti.");
        debuglog("ha benedetto l'oggetto migliorando di $bonus gemme il suo valore");
        addnav("`@Vai all'Altare", "chiesa.php");
        $session['user']['punti_carriera'] -= (3000 * $caso);
    }elseif ($dio > 1) {
        addnav("Exit");
        addnav("`@Torna al Villaggio", "village.php");
        output("`\$Entri nella grande chiesa di `6Sgrios`n");
        output("`\$Una forza molto potente ti spinge ad uscire dalla chiesa, e non puoi opporti al suo volere.`n");
    }elseif($_GET['az']=="chiedi"){
        addnav("`&Vai all'entrata", "chiesa.php");
        if ($carriera == 1 ) {
            output("`3Mediti molto profondamente cercando di capire che considerazione ha Sgrios nei tuoi confronti`n e preghi:`#\"Sgrios sono un servo fedele dimmi quanto manca alla mia promozione ?\"`7.`n");
            $voto = intval(($session['user']['punti_carriera']/2000)*10);
            output("`#Una voce tonante esplode nella tua mente :`& \"In questo momento sei un `$ Seguace `&e la mia considerazione per te è pari a `$ $voto `&su 10 \"`7.`n `#Stai tremando come una foglia, pensi che non è una buona idea disturbare Sgrios per queste frivolezze.");
            $session['user']['punti_carriera']-=1;
        }
        if ($carriera == 2 ) {
            output("`3Mediti molto profondamente cercando di capire che considerazione ha Sgrios nei tuoi confronti`n e preghi:`#\"Sgrios sono un servo fedele dimmi quanto manca alla mia promozione ?\"`7.`n");
            $voto = intval(($session['user']['punti_carriera']/5000)*10);
            output("`#Una voce tonante esplode nella tua mente :`& \"In questo momento sei un `$ Accolito `&e la mia considerazione per te è pari a `$ $voto `&su 10 \"`7.`n `#Stai tremando come una foglia, pensi che non è una buona idea disturbare Sgrios per queste frivolezze.");
            $session['user']['punti_carriera']-=1;
        }
        if ($carriera == 3 ) {
            output("`3Mediti molto profondamente cercando di capire che considerazione ha Sgrios nei tuoi confronti`n e preghi:`#\"Sgrios sono un servo fedele dimmi quanto manca alla mia promozione ?\"`7.`n");
            $voto = intval(($session['user']['punti_carriera']/20000)*10);
            output("`#Una voce tonante esplode nella tua mente :`& \"In questo momento sei un `$ Chierico `&e la mia considerazione per te è pari a `$ $voto `&su 10 \"`7.`n `#Stai tremando come una foglia, pensi che non è una buona idea disturbare Sgrios per queste frivolezze.");
            $session['user']['punti_carriera']-=1;
        }
                if ($carriera == 4 ) {
            output("`3Mediti molto profondamente cercando di capire che considerazione ha Sgrios nei tuoi confronti`n e preghi:`#\"Sgrios sono un servo fedele dimmi quanto manca alla mia promozione ?\"`7.`n");
            $voto = intval(($session['user']['punti_carriera']/100000)*10);
            output("`#Una voce tonante esplode nella tua mente :`& \"In questo momento sei un `$ Sacerdote `&e la mia considerazione per te è pari a `$ $voto `&su 10 \"`7.`n `#Stai tremando come una foglia, pensi che non è una buona idea disturbare Sgrios per queste frivolezze.");
            $session['user']['punti_carriera']-=1;
        }
        if ($carriera == 17 OR $carriera == 9) {
            if ($carriera == 9) {
                output("`3Mediti molto profondamente cercando di capire che considerazione ha Sgrios nei tuoi confronti`n e preghi:`#\"Sgrios sono un servo fedele dimmi quanto manca alla mia promozione ?\"`7.`n");
                output("`#Una voce tonante esplode nella tua mente :`& \"Ma tu sei il mio Gran Sacerdote !!\"`7.`n `#Un fulmine ti colpisce, e pensi di essere uno stupido.");
                $session['user']['punti_carriera']-=10;
                if ($session['user']['hitpoints'] >= ($session['user']['maxhitpoints']/2)){
                   $session['user']['hitpoints']=intval($session['user']['hitpoints']/4);
                }
            }else{
                output("`3Mediti molto profondamente cercando di capire che considerazione ha Sgrios nei tuoi confronti`n e preghi:`#\"Sgrios sono un servo fedele dimmi quanto manca alla mia promozione ?\"`7.`n");
                output("`#Una voce tonante esplode nella tua mente :`& \"In questo momento sei un `$ Sommo Chierico `&e puoi solo diventare il mio Gran Sacerdote, se sarai più devoto \"`7.`n `3Pensi che non è una buona idea disturbare Sgrios per queste frivolezze.");
                $session['user']['punti_carriera']-=1;
            }
        }
    }
    //Excalibur: Mail di massa
    if ($_GET['az'] == "mail") {
         output("Testo da inviare.`n");
         output("<form action='chiesa.php?az=mailto&op=".$_GET['op']."' method='POST'>",true);
         output("<textarea class='input' name='body' cols='37' rows='5'>".HTMLEntities2(stripslashes($_POST['body']))."</textarea>`n",true);
         output("<input type='submit' class='button' value='Invia'></form>",true);
         addnav("","chiesa.php?az=mailto&op=".$_GET['op']);
         addnav("Torna all'entrata","chiesa.php");
    }
    if ($_GET['az'] == "mailto") {
         $body = "`^Messaggio del Gran Sacerdote.`n";
         $body .="`#".$_POST['body'];
         if ($_GET['op'] == 1) {
             $clausula = "dio=1 AND superuser=0";
         }elseif ($_GET['op'] == 2) {
             $clausula = "dio=1 AND (carriera<5 OR carriera=17) AND superuser=0";
         }elseif ($_GET['op'] == 3) {
             $clausula = "dio=1 AND carriera=17 AND superuser=0";
         }
         $sqlmail = "SELECT acctid FROM accounts WHERE ".$clausula;
         $resultmail = db_query($sqlmail);
         //output("Query SQL: ".$sqlmail."`nNumero righe: ".db_num_rows($resultmail));
         $countrow = db_num_rows($resultmail);
         for ($imail=0; $imail<$countrow; $imail++){
         //for ($imail=0;$imail<db_num_rows($resultmail);$imail++){
             $rowmail = db_fetch_assoc($resultmail);
             systemmail($rowmail['acctid'],"`^Comunicazione del Gran Sacerdote!`0",$body,$session['user']['acctid']);
             //output("Account ID N°".($imail+1).": ".$rowmail['acctid']."`n");
         }
         addnav("`&Vai all'entrata", "chiesa.php");
    }
    //Excalibur: fine Mail di massa
    if ($_GET['az'] == "punisci") {
        addnav("`&Vai all'entrata", "chiesa.php");
        output("`3Da quì puoi infliggere le punizioni in nome di `\6Sgrios`3 e bandire i fedeli per alcuni giorni (di gioco) massimo 9.`7.`n");
        output("<form action='chiesa.php?az=addpun' method='POST'>",true);
        addnav("","chiesa.php?az=addpun");
        output("`bPersonaggio: <input name='name'>`nGiorni di punizione: <input name='amt' size='3'>`n<input type='submit' class='button' value='Punisci'>",true);
        output("</form>",true);
    }
    if ($_GET['az'] == "addpun") {
        addnav("`&Vai all'entrata", "chiesa.php");
        $search="%";
        for ($i=0;$i<strlen($_POST['name']);$i++){
            $search.=substr($_POST['name'],$i,1)."%";
        }
        $sql = "SELECT name,acctid,superuser FROM accounts WHERE login LIKE '$search' AND dio = 1";
        $result = db_query($sql);
        if($_POST['amt']>10){
            $punizione=10;
        }else{
            $punizione=$_POST['amt'];
        }
        output("Conferma l'aggiunta di {$punizione} giorni di espulsione dalla chiesa a:`n`n");
        $countrow = db_num_rows($result);
        for ($i=0; $i<$countrow; $i++){
        //for ($i=0;$i<db_num_rows($result);$i++){
            $row = db_fetch_assoc($result);
            if($row[superuser]==0){
                output("<a href='chiesa.php?op=add2&id={$row['acctid']}&amt={$punizione}'>",true);
                output($row['name']." ({$row['punizione']})");
                output("</a>`n",true);
                addnav("","chiesa.php?op=add2&id={$row['acctid']}&amt={$punizione}");
            }
        }
    }
    if ($_GET['op']=="add2"){
        addnav("`&Vai all'entrata", "chiesa.php");
        $punizione=$_GET['amt'];
        $sqlpun = "SELECT * FROM punizioni_chiese WHERE acctid='{$_GET['id']}' AND fede='1'";
        $respun = db_query($sqlpun) or die(db_error(LINK));
        if (db_num_rows($respun) == 0) {
            $sqli = "INSERT INTO punizioni_chiese (acctid,giorni,fede) VALUES ('{$_GET['id']}','{$punizione}','1')";
            $resulti=db_query($sqli);
            $mailmessage = "`\$Il gran sacerdote `7ti ha inflitto una punizione!`nSei stato bandito dalla chiesa di Sgrios per `\$".$_GET['amt']." `7 giorni.`n";
            systemmail($_GET['id'],"`2Punizione.`2",$mailmessage);
            output("`3Hai inflitto la punizione in nome di `6Sgrios`3!`n");
        } else {
            $sqli = "UPDATE punizioni_chiese SET giorni='{$punizione}' WHERE acctid='{$_GET['id']}' AND fede='1'";
            $resulti=db_query($sqli);
            $mailmessage = "`\$Il gran sacerdote `7ha modificato la tua punizione!`nSei ora bandito dalla chiesa di Sgrios per `\$".$_GET['amt']." `7 giorni.`n";
            systemmail($_GET['id'],"`2Punizione.`2",$mailmessage);
            output("`3Hai modificato la punizione in nome di `6Sgrios`3!`n");
        }
        $_GET['op']="";

    }
    if ($_GET['az']=="puniti"){
        addnav("`&Vai all'entrata", "chiesa.php");
        $sql = "SELECT a.acctid,a.name,b.giorni FROM accounts a, punizioni_chiese b WHERE a.acctid=b.acctid AND b.giorni>0 AND a.dio=1 AND b.fede=1 ORDER BY b.giorni DESC";
        $result = db_query($sql);
        output("<table border='0' cellpadding='5' cellspacing='0'>",true);
        output("<tr><td>Nome</td><td>Giorni rimanenti</td></tr>",true);
        $countrow = db_num_rows($result);
        for ($i=0; $i<$countrow; $i++){
        //for ($i=0;$i<db_num_rows($result);$i++){
            $row = db_fetch_assoc($result);
            output("<tr class='".($i%2?"trlight":"trdark")."'>",true);
            output("<td>",true);
            output("`V{$row['name']}`0",true);
            output("<td>`%".$row['giorni']."`0</td>",true);
            output("</tr>",true);
        }
        output("</table>",true);
    }
}else{
    page_header("Ostracizzato dalla chiesa!");
    $sql = "SELECT * FROM punizioni_chiese WHERE acctid='{$session['user']['acctid']}' AND fede=1";
    $result = db_query($sql);
    if (db_num_rows($result) != 0) {
        $ref = db_fetch_assoc($result);
    }
    output("`3Nonostante la punizione che ti ha inflitto il Gran Sacerdote di Sgrios provi ad entrare in chiesa.`n");
    output("`3Ma tutti sanno delle tue malefatte, ti malmenano e ti ributtano fuori dalla chiesa!");
    output("`3 Ti dicono di tornare tra `^".$ref[giorni]." giorni`3, quando scadrà la tua punizione!`n`n");
    addnav("Torna al Villaggio", "village.php");
}
page_footer();

?>