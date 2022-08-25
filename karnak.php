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
    $session['user']['dio'] = 2;
    $session['user']['carriera'] = 10;
}
$sqlpun = "SELECT * FROM punizioni_chiese WHERE acctid = '{$session['user']['acctid']}' AND giorni > 0 AND fede = '2'";
$resultpun = db_query($sqlpun) or die(db_error(LINK));
$refpun = db_fetch_assoc($resultpun);
if(db_num_rows($resultpun)==0) {
    $carriera = $session['user']['carriera'];
    $dio = $session['user']['dio'];
    $dk = $session['user']['dragonkills'] + ($session['user']['reincarna'] * 19);
    $bonus = 0;
    $caso = mt_rand(1, 3);
    $cento = mt_rand(1, 99);
    $dieci = mt_rand(1, 10);
    $venti = mt_rand(1, 20);
    $ultima_messa = getsetting("tempomessasat", 0);
    $player_ingrotta = getsetting("player_ingrotta", 0);
    $data_messa = time();
    //Modifica per inserire news
    if ($session['user']['dio'] == 2) {
        $sql="SELECT * FROM custom WHERE area1='falciatore'";
        $result=db_query($sql);
        $dep = db_fetch_assoc($result);
        if (db_num_rows($result) == 0) {
            $sqli = "INSERT INTO custom (dTime,area1) VALUES (NOW(),'falciatore')";
            $resulti=db_query($sqli);
        }
        if ($carriera==15 AND !$_GET['az']){
            output("`0<form action=\"karnak.php\" method='POST'>",true);
            output("[Falciatore di Anime] Inserisci Notizia? <input name='meldung' size='80'> ",true);
            output("<input type='submit' class='button' value='Insert'>`n`n",true);
            addnav("","karnak.php");
            if ($_POST['meldung']){
                $sql = "UPDATE custom SET dTime = now(),dDate = now() WHERE area1 = 'falciatore'";
                $result=db_query($sql);
                $sql = "UPDATE custom SET amount = ".$session['user']['acctid']." WHERE area1 = 'falciatore'";
                $result=db_query($sql);
                $sql = "UPDATE custom SET area ='".addslashes($_POST['meldung'])."' WHERE area1 = 'falciatore'";
                $result=db_query($sql);
                $_POST[meldung]="";
            }
            addnav("","news.php");
        }
        if ($carriera==16 AND !$_GET['az']){
            output("`0<form action=\"karnak.php\" method='POST'>",true);
            output("[Portatore di Morte] Inserisci Notizia : <input name='meldung' size='80'> ",true);
            output("<input type='submit' class='button' value='Insert'>`n`n",true);
            addnav("","karnak.php");
            if ($_POST['meldung']){
                $sqlogg = "DELETE FROM custom WHERE amount = '".$session['user']['acctid']."' AND area1='portatore'";
                db_query($sqlogg) or die(db_error(LINK));
                $sqli = "INSERT INTO custom (amount,dTime,dDate,area,area1)
                VALUES ('".$session['user']['acctid']."',NOW(),NOW(),'".addslashes($_POST['meldung'])."','portatore')";
                db_query($sqli);
                $_POST[meldung]="";
            }
            addnav("","news.php");

        }
        $sql="SELECT * FROM custom WHERE area1='falciatore'";
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
            if($dep1[carriera]==15){
                    output("<big>`b`c`%ANNUNCIO DEL FALCIATORE DI ANIME `4$nomefda `%DELLA GROTTA DI `\$KARNAK`0`c`b</big>`n",true);
                    output("`8".date("d/m/Y",strtotime($lastdate))." `6".date("h:i:s",strtotime($lasttime))."   `b`^".$msgchiesa."`b`n`n");
           }
        }
        output("`b`c`%ANNUNCI DEI PORTATORI DI MORTE DELLA GROTTA DI `\$KARNAK`0`c`b`n",true);
        $sql="SELECT * FROM custom WHERE area1='portatore' ORDER BY dDate ASC, dTime ASC";
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
                if ($dep1[carriera]==16){
                    output("`8".date("d/m/Y",strtotime($lastdate))." `6".date("H:i:s",strtotime($lasttime))." `@".$dep1['login']." `^: `b".$msgchiesa."`b`n");
                }
            }
        }
        output("`n");
        //Excalibur: Visualizzazione annuncio prossima messa
        output("<font size='+2'>`b`c`\$PROSSIMA MESSA IN ONORE DI `4KARNAK`0`c`b</font>`n",true);
        $sql="SELECT * FROM custom WHERE area1='messakarnak'";
        $result=db_query($sql);
        $dep = db_fetch_assoc($result);
        $quando = $dep['dDate']." ".$dep['dTime'];
        if (date ("Y-m-d H:m:s",strtotime ("now")) <= $quando){
           $msgproxmessa = stripslashes($dep['area']);
           output("<font size='+1'>`c`\$".$msgproxmessa."`0`c</font>`n",true);
        }else{
           output("<font size='+1'>`c`4DATA MESSA DA DEFINIRE`0`c</font>`n",true);
           output("`c`4Ultima messa celebrata il ".$quando."`0`c`n",true);
        }
        //Excalibur: Fine Visualizzazione annuncio prossima messa
    }
    //Fine Modifica News
    page_header("La Grotta di Karnak");
    $session['user']['locazione'] = 143;
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
    if ($carriera == 16 OR $carriera == 15) {
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
    //fine ultima messa celebrata da player
    // Inserisce commento se ucciso seguace di Sgrios o Drago Verde e aggiunge punti carriera
    if ($session['user']['history'] AND $dio == 2){
        $paragone=strstr($session['user']['history'],"`\$ ha massacrato");
        $session['user']['history'] = addslashes($session['user']['history']);
        $sql = "INSERT INTO commenti (section,author,comment,postdate) VALUES ('Scontri Sette','".$session['user']['acctid']."','".$session['user']['history']."',NOW())";
        db_query($sql) or die(db_error($link));
        if ($paragone === false){
        }else {
            $session['user']['punti_carriera'] += $dieci;
            $session['user']['punti_generati'] += $dieci;
            $fama = (100*$dieci*$session[user][fama_mod]);
            $session['user']['fama3mesi'] += $fama;
            debuglog("Guadagna $dieci punti carriera e $fama punti fama da Karnak per aver ucciso un infedele. Ora ha ".$session['user']['punti_carriera']." punti carriera e ".$session['user']['fama3mesi']." punti fama");
            if ($session['user']['superuser'] == 0){
                savesetting("puntikarnak", getsetting("puntikarnak",0)+$dieci);
            }else{
                print("Se tu non fossi stato un admin avrei aggiunto i punti alla setta");
            }
        }
        $session['user']['history']="";
    }
    //fine inserimento commento

    //Excalibur: declassamento Portatore di Morte se punti fede < 50.000
    if ($session['user']['carriera']==16 AND $session['user']['punti_carriera']<50000 AND $session['user']['dio']==2){
        output("<big>`\$`b`cRetrocessione`c`n`b`0</big>",true);
        output("`\$Non rispondi più ai requisiti per essere Portatore di Morte, pertanto vieni retrocesso al rango di Maestri delle Tenebre.`n");
        $session['user']['carriera']=13;
        $sqlogg = "DELETE FROM custom WHERE amount = '".$session['user']['acctid']."' AND area1='portatore'";
        db_query($sqlogg) or die(db_error(LINK));
    }
    //Excalibur: fine

    //Luke nuovo sistema per gestione Falciatore di anime
    if ($carriera == 16 OR $carriera == 15 OR $carriera == 13) {
        if ($session['user']['superuser'] == 0) {
            savesetting("falciatore","0");
            $sqlma = "SELECT acctid FROM accounts WHERE
            (carriera = 16 OR carriera = 15 OR carriera = 13)
            AND punti_carriera > 99999
            AND reincarna > 0
            ORDER BY punti_carriera DESC LIMIT 1";
            $resultma = db_query($sqlma) or die(db_error(LINK));
            $rowma = db_fetch_assoc($resultma);
            $fda = $rowma['acctid'];
            savesetting("falciatore", $fda);
        }
        if (getsetting("falciatore",0)!=$session['user']['acctid']){
            if ($session['user']['punti_carriera'] > 100000 AND $session['user']['reincarna'] > 0){
                $session['user']['carriera'] = 16;
            }else{
                $session['user']['carriera'] = 13;
            }
        }else{
            output("<big>`^`b`cPromozione`c`b`0`n</big>",true);
            output("Sei diventato Falciatore di Anime!`n");
            $session['user']['carriera'] = 15;
            $sqlogg = "DELETE FROM custom WHERE amount='".$session['user']['acctid']."' AND area1='portatore'";
            db_query($sqlogg) or die(db_error(LINK));
        }
    }
    /*
    //like fine nuovo sistema
    //  rimozione attuale Falciatore di Anime
    if ($session['user']['superuser'] == 0) {
    if ($carriera == 13 OR $carriera == 15) {
    $sqlua = "UPDATE accounts SET carriera = 13 WHERE carriera = 15";
    $resultm = db_query($sqlua) or die(db_error(LINK));
    $session['user']['carriera'] = 13;
    /*if (db_num_rows($resultm) != 0) {
    if ($rowm[acctid] == $session['user'][acctid]) {
    $session['user']['carriera'] = 13;
    } else {
    $sqlu = "UPDATE accounts SET carriera='13' WHERE acctid='{$rowm[acctid]}' ";
    //output("SQL : $sqlu `n");
    db_query($sqlu) or die(db_error(LINK));
    }
    }
    // aggiornamento nuovo Falciatore di Anime
    $sqlma = "SELECT acctid FROM accounts WHERE
    punti_carriera >= 20000 AND
    carriera=13 AND
    superuser=0 AND
    (dragonkills > 15 OR reincarna > 0)
    ORDER BY punti_carriera DESC LIMIT 1";
    $resultma = db_query($sqlma) or die(db_error(LINK));
    $rowma = db_fetch_assoc($resultma);
    if (db_num_rows($resultma) != 0) {
    if ($rowma['acctid'] == $session['user']['acctid']) {
    $session['user']['carriera'] = 15;
    } else {
    $sqlua = "UPDATE accounts SET carriera='15' WHERE acctid='{$rowma[acctid]}' ";
    //output("SQL : $sqlua `n");
    db_query($sqlua) or die(db_error(LINK));
    }
    }
    }
    }
    */
    if (($carriera > 9 AND $carriera < 14) OR $carriera == 15 OR $carriera == 16) {
        addnav("Rango ".$prof[$carriera]);
    } elseif ($dio == 2 AND (($carriera > 4 AND $carriera < 9) OR ($carriera > 40 AND $carriera < 45))) {
        addnav("Rango Adoratore");
    }
    if ($dio == 2 AND $carriera == 0) {
        addnav("Diventa un Invasato", "karnak.php?az=invasato");
        addnav("(Adepto)","");
    }
    if ($dio == 0 AND !$_GET['az'] AND $carriera == 0) {
        addnav("Azioni per Karnak");
        addnav("Diventa un Invasato", "karnak.php?az=invasato");
        addnav("(Adepto)","");
        addnav("Diventa un Adoratore", "karnak.php?az=adoratore");
        addnav("(Simpatizzante)","");
        addnav("Altro");
        //addnav("Ambasciata","ambasciata.php?setta=karnak");
        addnav("Torna al Villaggio", "village.php");
        output("`3Entri nella Grande Grotta di `\$Karnak`3`n");
    }
    if ($dio == 0 AND !$_GET['az'] AND $carriera != 0) {
        addnav("Azioni");
        addnav("Diventa un Adoratore", "karnak.php?az=adoratore");
        addnav("(Simpatizzante)","");
        addnav("Altro");
        //addnav("Ambasciata","ambasciata.php?setta=karnak");
        addnav("Torna al Villaggio", "village.php");
        output("`3Entri nella Grande Grotta di `\$Karnak`3`n");
    } elseif ($_GET['az'] == "adoratore") {
        if ($dio != 2 AND $dio != 0) {
            output("`3Non puoi adorare `\$Karnak`3 ed un'altra divinità, l'ira di `\$Karnak`3 potrebbe essere terribile.`n");
            addnav("Torna al Villaggio", "village.php");
        } else {
            output("`%Sei sicuro di voler diventare un Adoratore di `\$Karnak`% ?`n");
            output("Questa scelta è fondamentale, condizionerà le tue azioni future, quindi pensaci bene. Una volta presa non potrai tornare indietro.`n");
            addnav("Sono sicuro", "karnak.php?az=adoratore_sicuro");
            addnav("Ci devo pensare", "village.php");
        }
    } elseif ($_GET['az'] == "adoratore_sicuro") {
        output("`3Sei diventato un adoratore di `\$Karnak`3, che tu sia maledetto.`n Le tue responsabilità da ora in poi saranno grandi.`n");
        output("Dovrai contribuire attivamente alla diffusione della `#Maledizione di Karnak`3, e convertire gli eretici.`n");
        output("Potrai partecipare alle messe quando un `%Falciatore di Anime`3 ne indirà una.`n");
        $session['user']['dio'] = 2;
        if (getsetting("falciatore","0") != "0") {
            systemmail(getsetting("falciatore","0"),"`\$Nuovo adoratore","`\$".$session['user']['name']." `\$è diventato adoratore di Karnak!");
        }
        addnav("Ara dei Sacrifici", "karnak.php");
    } elseif ($_GET['az'] == "invasato") {
        if ($carriera != 0) {
            output("`3Non puoi dedicarti a `\$Karnak `3e ad una professione minore, non fare il furbo con `\$Karnak`3.`n");
            addnav("Torna al Villaggio", "village.php");
        } else {
            output("`3Sei sicuro di voler diventare un Invasato di `\$Karnak`3 ?`n");
            output("Questa scelta è fondamentale, condizionerà le tue azioni future, quindi pensaci bene. Una volta presa non potrai tornare indietro.`n");
            addnav("Sono sicuro", "karnak.php?az=invasato_sicuro");
            addnav("Ci devo pensare", "village.php");
        }
    } elseif ($_GET['az'] == "invasato_sicuro") {
        output("`%Sei diventato un Invasato di `\$Karnak`%, che tu sia maledetto.`n");
        output("Avvicinati all'Ara dei Sacrifici.`n");
        $session['user']['dio'] = 2;
        $session['user']['carriera'] = 10;
        if (getsetting("falciatore","0") != "0") {
            systemmail(getsetting("falciatore","0"),"`\$Nuovo invasato","`\$".$session['user']['name']." `\$è diventato invasato di Karnak!");
        }
        addnav("Ara dei Sacrifici", "karnak.php");
    } elseif ($_GET['az'] == "fanatico") {
        output("<big>`^`b`cPromozione`c`b`0`n</big>",true);
        output("`%Sei diventato un Fanatico di `\$Karnak`%.`n");
        output("Avvicinati all'Ara dei Sacrifici.`n");
        $session['user']['carriera'] = 11;
        addnav("Ara dei Sacrifici", "karnak.php");
    } elseif ($_GET['az'] == "posseduto") {
        output("<big>`^`b`cPromozione`c`b`0`n</big>",true);
        output("`%Sei diventato un Posseduto di `\$Karnak`%.`n");
        output("Avvicinati all'Ara dei Sacrifici.`n");
        $session['user']['carriera'] = 12;
        addnav("Ara dei Sacrifici", "karnak.php");
    } elseif ($_GET['az'] == "sacerdote") {
        output("<big>`^`b`cPromozione`c`b`0`n</big>",true);
        output("`%Sei diventato un Maestro delle Tenebre di `\$Karnak`%.`n");
        output("Avvicinati all'Ara dei Sacrifici.`n");
        $session['user']['carriera'] = 13;
        addnav("Ara dei Sacrifici", "karnak.php");
    } elseif ($_GET['az'] == "portatore") {
        output("<big>`^`b`cPromozione`c`b`0`n</big>",true);
        output("`%Sei diventato un Portatore di Morte di `\$Karnak`%.`n");
        output("Avvicinati all'Ara dei Sacrifici.`n");
        $session['user']['carriera'] = 16;
        addnav("Ara dei Sacrifici", "karnak.php");
    } elseif ($dio == 2 AND !$_GET['az']) {
        addnav("Azioni");
        if (($carriera > 9 AND $carriera < 14) OR $carriera == 15 OR $carriera == 16) {
            addnav("Fai un dono", "karnak.php?az=dono");
            addnav("Invoca", "karnak.php?az=invoca");
        }
        if (($carriera > 10 AND $carriera < 14) OR $carriera == 15 OR $carriera == 16) {
            addnav("Celebra Sacrificio", "karnak.php?az=sacrificio");
        }
        if (($carriera > 11 AND $carriera < 14) OR $carriera == 15 OR $carriera == 16) {
            addnav("Rito del Sangue", "karnak.php?az=processione");
        }
        if (($carriera > 12 AND $carriera < 14) OR $carriera == 15 OR $carriera == 16) {
            addnav("Massacro", "karnak.php?az=massacro");
        }
        if ($carriera == 15 OR $session['user']['superuser'] > 3 OR $session['user']['login']=='Lilli') {
            addnav("Punisci", "karnak.php?az=punisci");
            //Excalibur: Mail di massa
            addnav("Mail globali");
            addnav("Invia Mail a TUTTI (Invasati e Adoratori)","karnak.php?az=mail&op=1");
            addnav("Invia Mail a Invasati","karnak.php?az=mail&op=2");
            addnav("Invia Mail a Portatori di Morte","karnak.php?az=mail&op=3");
            //Excalibur: fine Mail di massa
        }
        //Excalibur: Inserimento annuncio prossima messa
        if ($carriera == 15 OR $carriera == 16 OR $session['user']['superuser'] > 3) {
            addnav("Annuncio Messa","msgmesse.php");
        }
        //Excalibur: fine Inserimento annuncio prossima messa
        addnav("Richieste");
        if ($session['user']['punti_carriera'] > 4000 and $carriera == 10 AND $dk > 5) {
            addnav("Diventa Fanatico", "karnak.php?az=fanatico");
        }
        if ($session['user']['punti_carriera'] > 10000 and $carriera == 11 AND $dk > 10) {
            addnav("Diventa Posseduto", "karnak.php?az=posseduto");
        }
        if ($session['user']['punti_carriera'] > 40000 and $carriera == 12 AND $dk > 15) {
            addnav("Diventa Maestro delle Tenebre", "karnak.php?az=sacerdote");
        }
        if ($session['user']['punti_carriera'] > 100000 and $carriera == 13 AND $session['user']['reincarna']> 0) {
            addnav("Diventa Portatore di Morte", "karnak.php?az=portatore");
        }
        if (($carriera > 9 AND $carriera < 14) OR $carriera == 15 OR $carriera == 16) {
            addnav("Supplica", "karnak.php?az=supplica");
        }
        if (($carriera > 10 AND $carriera < 14) OR $carriera == 15 OR $carriera == 16) {
            addnav("Supplica Superiore", "karnak.php?az=supplicas");
        }
        if (($carriera > 11 AND $carriera < 14) OR $carriera == 15 OR $carriera == 16) {
            addnav("Maledizione Minore", "karnak.php?az=maledici");
        }
        if (($carriera > 12 AND $carriera < 14) OR $carriera == 15 OR $carriera == 16) {
            addnav("Maledizione Maggiore", "karnak.php?az=maledici_maggiore");
        }
        addnav("Info");
        if (($carriera > 9 AND $carriera < 14) OR $carriera == 15 OR $carriera == 16) {
            addnav("I più Malvagi","karnak.php?az=devoti");
            addnav("Medita sulla tua cattiveria","karnak.php?az=chiedi");
        }
        addnav("Puniti","karnak.php?az=puniti");
        addnav("Altro");
        //addnav("Ambasciata","ambasciata.php?setta=karnak");
        addnav("Sala del Falciatore di Anime", "karnak.php?az=gs");
        addnav("Direttive", "direttive.php");
        addnav("Torna al Villaggio", "village.php");
        output("`5Entri nella grande chiesa di `\$Karnak`5, un odore acre di `6zolfo`5 si diffonde nell'ambiente, alcuni invasati ");
        output("compiono oscuri riti vicino all'Ara dei Sacrifici. Un baccano infernale viene prodotto da tutti i maledetti presenti.`n");
    } elseif ($_GET['az'] == "invoca") {
        if ($session['user']['turns'] < 1) {
                        output("`5Sei troppo esausto per metterti a invocare `\$Karnak`5.`n");
            addnav("Torna all'Ara dei Sacrifici", "karnak.php");
        } else {
            output("`3Ti inginocchi e inizi a invocare. Senti la tua cattiveria aumentare e invochi con più vigore la maledizione di `\$Karnak`n");
            $session['user']['punti_carriera'] += (2 + $caso);
            $session['user']['punti_generati'] += (2 + $caso);
            $fama = (2+$caso)*$session[user][fama_mod];
            $session['user']['fama3mesi'] += $fama;
            debuglog("Guadagna ".(2+$caso)." punti carriera e $fama punti fama da Karnak con un'invocazione. Ora ha ".$session['user']['punti_carriera']." punti carriera e ".$session['user']['fama3mesi']." punti fama");
            if ($session['user']['superuser'] == 0){
                savesetting("puntikarnak", getsetting("puntikarnak",0)+(2 + $caso));
            }
            $session['user']['turns'] -= 1;
            $session['user']['experience']+=($session['user']['level']*4);
            addnav("Invoca", "karnak.php?az=invoca");
            addnav("`@Torna all'Ara dei Sacrifici", "karnak.php");
        }
    } elseif ($_GET['az'] == "dono") {
        output("`5Ti avvicini alla statua di `\$Karnak`5. Alla sua base noti un contenitore in cui giacciono molti oggetti.`n");
        output("Sono le offerte fatte dai seguaci in onore di `\$Karnak`5, esseri malvagi che si sono recati qui per manifestare ");
        output("la propria perversione.");
        output("Cosa vuoi offrire ?.`n");
        output("Pezzi d'oro, oppure 1 gemma.`n");
        addnav("5?`^Getta 500 Oro", "karnak.php?az=oro5");
        addnav("1?`^Getta 1000 Oro", "karnak.php?az=oro1");
        addnav("0?`^Getta 5000 Oro", "karnak.php?az=oro50");
        addnav("O?`^Getta 10000 Oro", "karnak.php?az=oro10");
        addnav("`&Getta 1 Gemma", "karnak.php?az=gemme");
        // addnav("Getta oggetto magico","karnak.php?az=magico");
        addnav("`@Torna all'Ara dei Sacrifici", "karnak.php");
    } elseif ($_GET['az'] == "oro5") {
        if ($session['user']['gold'] < 500) {
            output("Non hai abbastanza oro.`n");
            addnav("`@Torna all'Ara dei Sacrifici", "karnak.php");
        } else {
            output("Getti felice i 500 Pezzi d'Oro in onore di `\$Karnak`# nel contenitore ... senti immediatamente una folata ");
            output("di energia percorrere la tua anima corrotta. La tua cattiveria è aumentata !`n");
            addnav("`@Torna all'Ara dei Sacrifici", "karnak.php");
            addnav("5?`^Getta 500 Oro", "karnak.php?az=oro5");
            $session['user']['punti_carriera'] += (20 + $caso);
            $session['user']['punti_generati'] += (20 + $caso);
            $fama = (20+$caso)*$session[user][fama_mod];
            $session['user']['fama3mesi'] += $fama;
            debuglog("Guadagna ".(20+$caso)." punti carriera e $fama punti fama da Karnak donando 500 oro. Ora ha ".$session['user']['punti_carriera']." punti carriera e ".$session['user']['fama3mesi']." punti fama");
            if ($session['user']['superuser'] == 0){
                savesetting("puntikarnak", getsetting("puntikarnak",0)+(20 + $caso));
            }
            $session['user']['gold'] -= 500;
        }
    } elseif ($_GET['az'] == "oro1") {
        if ($session['user']['gold'] < 1000) {
            output("Non hai abbastanza oro.`n");
            addnav("`@Torna all'Ara dei Sacrifici", "karnak.php");
        } else {
            output("`#Getti estasiato i 1.000 Pezzi d'Oro in onore di `\$Karnak`# nel contenitore ... senti immediatamente una folata ");
            output("di energia percorrere la tua anima corrotta. La tua cattiveria è aumentata !`n");
            addnav("`@Torna all'Ara dei Sacrifici", "karnak.php");
            addnav("1?`^Getta 1000 Oro", "karnak.php?az=oro1");
            $session['user']['punti_carriera'] += (42 + $caso);
            $session['user']['punti_generati'] += (42 + $caso);
            $fama = (42+$caso)*$session[user][fama_mod];
            $session['user']['fama3mesi'] += $fama;
            debuglog("Guadagna ".(42+$caso)." punti carriera e $fama punti fama da Karnak donando 1000 oro. Ora ha ".$session['user']['punti_carriera']." punti carriera e ".$session['user']['fama3mesi']." punti fama");
            if ($session['user']['superuser'] == 0){
                savesetting("puntikarnak", getsetting("puntikarnak",0)+(42 + $caso));
            }
            $session['user']['gold'] -= 1000;
        }
    } elseif ($_GET['az'] == "oro50") {
        if ($session['user']['gold'] < 5000) {
            output("Non hai abbastanza oro.`n");
            addnav("`@Torna all'Ara dei Sacrifici", "karnak.php");
        } else {
            output("`#Getti estasiato i 5.000 Pezzi d'Oro in onore di `\$Karnak`# nel contenitore ... senti immediatamente una folata ");
            output("di energia percorrere la tua anima corrotta. La tua cattiveria è aumentata !`n");
            addnav("`@Torna all'Ara dei Sacrifici", "karnak.php");
            addnav("0?`^Getta 5000 Oro", "karnak.php?az=oro50");
            $session['user']['punti_carriera'] += (215 + $caso);
            $session['user']['punti_generati'] += (215 + $caso);
            $fama = (215+$caso)*$session[user][fama_mod];
            $session['user']['fama3mesi'] += $fama;
            debuglog("Guadagna ".(215+$caso)." punti carriera e $fama punti fama da Karnak donando 5000 oro. Ora ha ".$session['user']['punti_carriera']." punti carriera e ".$session['user']['fama3mesi']." punti fama");
            if ($session['user']['superuser'] == 0){
                savesetting("puntikarnak", getsetting("puntikarnak",0)+(215 + $caso));
            }
            $session['user']['gold'] -= 5000;
        }
    } elseif ($_GET['az'] == "oro10") {
        if ($session['user']['gold'] < 10000) {
            output("Non hai abbastanza oro.`n");
            addnav("`@Torna all'Ara dei Sacrifici", "karnak.php");
        } else {
            output("`#Getti estasiato i 10.000 Pezzi d'Oro in onore di `\$Karnak`# nel contenitore ... senti immediatamente una folata ");
            output("di energia percorrere la tua anima corrotta. La tua cattiveria è aumentata !`n");
            addnav("`@Torna all'Ara dei Sacrifici", "karnak.php");
            addnav("O?`^Getta 10000 Oro", "karnak.php?az=oro10");
            $session['user']['punti_carriera'] += (440 + $caso);
            $session['user']['punti_generati'] += (440 + $caso);
            $fama = (440+$caso)*$session[user][fama_mod];
            $session['user']['fama3mesi'] += $fama;
            debuglog("Guadagna ".(440+$caso)." punti carriera e $fama punti fama da Karnak donando 10000 oro. Ora ha ".$session['user']['punti_carriera']." punti carriera e ".$session['user']['fama3mesi']." punti fama");
            if ($session['user']['superuser'] == 0){
                savesetting("puntikarnak", getsetting("puntikarnak",0)+(440 + $caso));
            }
            $session['user']['gold'] -= 10000;
        }
    } elseif ($_GET['az'] == "gemme") {
        if ($session['user']['gems'] < 1) {
            output("`4Non possiedi nessuna gemma.`n");
            addnav("`@Torna all'Ara dei Sacrifici", "karnak.php");
        } else {
            output("`#Getti estasiato 1 gemma in onore di `\$Karnak `#nel contenitore ... senti immediatamente una folata ");
            output("di energia percorrere la tua anima. La tua cattiveria è aumentata !`n");
            addnav("Torna all'Ara dei Sacrifici", "karnak.php");
            addnav("`&Getta 1 Gemma", "karnak.php?az=gemme");
            $session['user']['punti_carriera'] += (100 + $caso);
            $session['user']['punti_generati'] += (100 + $caso);
            $fama = (100+$caso)*$session[user][fama_mod];
            $session['user']['fama3mesi'] += $fama;
            debuglog("Guadagna ".(100+$caso)." punti carriera e $fama punti fama da Karnak donando una gemma. Ora ha ".$session['user']['punti_carriera']." punti carriera e ".$session['user']['fama3mesi']." punti fama");
            if ($session['user']['superuser'] == 0){
                savesetting("puntikarnak", getsetting("puntikarnak",0)+(100 + $caso));
            }
            $session['user']['gems'] -= 1;
            if ($cento > 89) {
                $buff = array("name" => "`\$Tocco infernale di Karnak", "rounds" => 15, "wearoff" => "`!La tua Forza Infernale scompare e torni normale", "defmod" => 1.5, "roundmsg" => "Senti Karnak al tuo fianco!", "activate" => "defense");
                debuglog("Guadagna anche il Tocco di Karnak donando la gemma");
                $session['bufflist']['magicweak'] = $buff;
                output("`%Un leggera aura malefica ti circonda. Senti il grande potere di `\$Karnak `%entrare in te!`n");
            }
        }
    } elseif ($_GET['az'] == "supplica") {
        if ($session['user']['punti_carriera'] < 50 OR $session['user']['turns'] < 1) {
            output("`#Prostrato sul pavimento inizi a supplicare il grande `\$Karnak`#.`n");
            output("Vieni attraversato da un brivido freddo e percepisci un tremore percorrere la tua anima.`n");
            output("`5La tua malvagità non soddisfa `\$Karnak`5.`n");
            addnav("Torna all'Ara dei Sacrifici", "karnak.php");
        } else {
            output("`%In ginocchio, con le mani protese davanti a te, inizi a supplicare il grande `\$Karnak`%.`n");
            output("Immediatamente un calore si diffonde nel tuo corpo ed entri in contatto con `\$Karnak`% !!");
            addnav("Supplica", "karnak.php?az=supplica");
            addnav("Torna all'Ara dei Sacrifici", "karnak.php");
            $session['user']['punti_carriera'] -= (20 + $venti);
            $session['user']['turns'] -= 1;
            $session['user']['suppliche'] += 1;
            $return=20-($session['user']['dragonkills']+(4*$session['user']['reincarna']));
            if($return < 3)$return=3;
            $gemme=1;
            if($session['user']['suppliche'] <= ($return*1)){
                output("Guadagni 1 turno.`n");
                $session['user']['turns'] += 1;
                $caso5 = 100+mt_rand(100, 400);
                $caso10 = 200+mt_rand(200, 800);
                $gemme+=1;
            }elseif($session['user']['suppliche'] <= ($return*2) AND $session['user']['suppliche']  > ($return*1)){
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
                $buff = array("name" => "`\$Tocco di Karnak Maggiore", "rounds" => 40, "wearoff" => "`!La tua Forza Malefica scompare e torni normale", "defmod" => 1.6, "roundmsg" => "Senti Karnak al tuo fianco!", "activate" => "defense");
                $session['bufflist']['magicweak'] = $buff;
                output("`5Un potente aura malefica ti circonda. Senti l'immenso potere di `\$Karnak `5entrare in te!`n");
                debuglog("guadagna Tocco di Karnak Maggiore alla grotta con la supplica");
            } elseif ($cento <= 89 and $cento > 79) {
                output("`%Il tempo oggi scorrerà più lento potrai combattere altre $gemme creature.`n");
                $session['user']['turns'] += $gemme;
                debuglog("guadagna $gemme turni alla grotta con la supplica");
            } elseif ($cento <= 79 and $cento > 69) {
                output("`%$gemme gemme compare davanti a te.`n");
                debuglog("guadagna $gemme gemme alla grotta con la supplica");
                $session['user']['gems'] += $gemme;
            } elseif ($cento <= 69 and $cento > 49) {
                output("`%Un mucchietto di `^`b$caso10`b`% pezzi d'oro compare davanti a te.`n");
                debuglog("guadagna $caso10 oro alla grotta con la supplica");
                $session['user']['gold'] += $caso10;
            } elseif ($cento <= 49 and $cento > 0) {
                output("`%Un mucchietto di `^`b$caso5`b`% pezzi d'oro compare davanti a te.`n");
                debuglog("guadagna $caso5 oro alla grotta con la supplica");
                $session['user']['gold'] += $caso5;
                $buff = array("name" => "`\$Tocco di Karnak", "rounds" => 25, "wearoff" => "`!La tua Forza Malefica scompare e torni normale", "defmod" => 1.2, "roundmsg" => "Senti Karnak al tuo fianco!", "activate" => "defense");
                $session['bufflist']['magicweak'] = $buff;
                output("`%Un leggera aura malefica ti circonda. Senti il potere di `\$Karnak`% entrare in te!`n");
                debuglog("guadagna Tocco di Karnak alla grotta con la supplica");
            }
        }
    } elseif ($_GET['az'] == "supplicas") {
        if ($session['user']['punti_carriera'] < 150 OR $session['user']['turns'] < 2) {
            output("`&Prostrato sul pavimento inizi a supplicare il grande `\$Karnak`&.`n");
            output("Vieni attraversato da un brivido freddo e senti la tua anima tremare.`n");
            output("La tua malvagità non soddisfa `\$Karnak`&.`n");
            addnav("`@Torna all'Ara dei Sacrifici", "karnak.php");
        } else {
            output("`&Prostrato sul pavimento inizi a supplicare il grande `\$Karnak`&.`n");
            addnav("Supplica Superiore", "karnak.php?az=supplicas");
            addnav("Torna all'Ara dei Sacrifici", "karnak.php");
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
                //crea oggetto 101
                $pot = mt_rand(10,20);
                $potn = mt_rand(0,1);
                $att = mt_rand(1,8);
                $dif = mt_rand(0,2);
                $turn = mt_rand(0,2);
                $vit = mt_rand(0,20);
                $valore = ($pot*$potn)+($att*10)+($dif*10)+($turn*6)+($vit)+10;
                $livello = intval($valore/30);
                $usuraextra = 0;
                $durata = 50 + 5*$valore + 100*$att + 100*$dif + 10*$turn + 2*$vit;
                $duratamagica = 0;
                $usuramagicaextra=0;
                if ($turn > 0) $duratamagica = 25 + 10*$turn;
                $sqlno = "SELECT * FROM  oggetti_nomi where serbatoio=101 ORDER BY RAND() LIMIT 1";
                $resultno = db_query($sqlno) or die(db_error(LINK));
                $rowno = db_fetch_assoc($resultno);
                $nome=$rowno['nome']." di Karnak";
                $desc=$rowno['nome']." forgiata da Karnak per ".$session[user][login];
                $resultno = db_query($sqlno) or die(db_error(LINK));
                $rowno = db_fetch_assoc($resultno);
                $sql="INSERT INTO oggetti (nome, descrizione, dove, dove_origine, livello, valore, potenziamenti,attack_help,defence_help,turns_help,hp_help,
                        usura, usuramax, usuraextra, usuramagica, usuramagicamax, usuramagicaextra)
                        VALUES ('{$nome}','{$desc}','101','1','$livello','$valore','$potn','$att','$dif','$turn','$vit',
                        '$durata', '$durata', '$usuraextra', '$duratamagica', '$duratamagica', '$usuraextramagica')";
                db_query($sql);
                //estrai oggetto 101
                $sql = "SELECT * FROM oggetti WHERE dove = 101 ORDER BY RAND() LIMIT 1";
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
                    $buff = array("name" => "`\$Tocco di Karnak maggiore", "rounds" => 40, "wearoff" => "`!La tua Forza Malefica scompare e torni normale", "defmod" => 1.4, "roundmsg" => "Senti Karnak al tuo fianco!", "activate" => "defense");
                    $session['bufflist']['magicweak'] = $buff;
                    output("`!La malvagità di \$Karnak`! è grande, una potente aura malefica ti circonda. `%Senti il potere di `\$Karnak `%entrare in te!`n");
                    debuglog("guadagna Tocco di Karnak Maggiore alla grotta con la supplica superiore");
                }
            } elseif ($cento <= 97 and $cento > 89) {
                $buff = array("name" => "`\$Tocco di Karnak Supremo", "rounds" => 60, "wearoff" => "`!La tua Forza Malefica scompare e torni normale", "defmod" => 1.5, "roundmsg" => "Senti Karnak al tuo fianco!", "activate" => "defense");
                $session['bufflist']['magicweak'] = $buff;
                output("`3Un possente aura malefica ti circonda. Senti l'incommensurabile potere di `\$Karnak`3 entrare in te.`n");
                debuglog("guadagna Tocco di Karnak Supremo alla grotta con la supplica superiore");
            } elseif ($cento <= 89 and $cento > 79) {
                output("`%Un mucchietto di `b`^$caso10`b`% pezzi d'oro compare davanti a te.`n");
                debuglog("riceve $caso10 oro alla grotta con la supplica superiore");
                $session['user']['gold'] += $caso10;
            } elseif ($cento <= 79 and $cento > 69) {
                output("`%Un mucchietto con `&`b$gemme`b`% gemme compare davanti a te.`n");
                debuglog("riceve $gemme gemme alla grotta con la supplica superiore");
                $session['user']['gems'] += $gemme;
            } elseif ($cento <= 69 and $cento > 49) {
                output("`%Un mucchietto di `^`b$caso9`b`% pezzi d'oro compare davanti a te.`n");
                debuglog("riceve $caso9 oro alla grotta con la supplica superiore");
                $session['user']['gold'] += $caso9;
            } elseif ($cento <= 49 and $cento > 0) {
                output("`%Un mucchietto di `b`^$caso8`b`% pezzi d'oro compare davanti a te.`n");
                debuglog("riceve $caso8 oro alla grotta con la supplica superiore");
                $session['user']['gold'] += $caso8;
                $buff = array("name" => "`\$Tocco di Karnak", "rounds" => 40, "wearoff" => "`!La tua Forza Malefica scompare e torni normale", "defmod" => 1.2, "roundmsg" => "Senti Karnak al tuo fianco!", "activate" => "defense");
                $session['bufflist']['magicweak'] = $buff;
                output("`%Un leggera aura malefica ti circonda. Senti il grande potere di `\$Karnak`% entrare in te!`n");
            }
        }
    }elseif ($_GET['az'] == "sacrificio") {
        if ($session['user']['turns'] < 5) {
            output("`7Sei troppo esausto per eseguire un sacrificio in onore di `\$Karnak`7.`n");
            addnav("`@Torna all'Ara dei Sacrifici", "karnak.php");
        } else {
            output("`3Celebri un sacrificio in onore di `\$Karnak`3, sgozzando alcuni poveri animali che sono capitati a tiro delle tue grinfie.`n");
            $session['user']['punti_carriera'] += (20 + $venti);
            $session['user']['punti_generati'] += (20 + $venti);
            $fama = (20+$venti)*$session[user][fama_mod];
            $session['user']['fama3mesi'] += $fama;
            debuglog("Guadagna ".(20+$venti)." punti carriera e $fama punti fama da Karnak con un sacrificio. Ora ha ".$session['user']['punti_carriera']." punti carriera e ".$session['user']['fama3mesi']." punti fama");
            if ($session['user']['superuser'] == 0){
                savesetting("puntikarnak", getsetting("puntikarnak",0)+(20 + $caso));
            }
            $session['user']['turns'] -= 5;
            $session['user']['experience']+=($session['user']['level']*20);
            addnav("Celebra Sacrificio", "karnak.php?az=sacrificio");
            addnav("`@Torna all'Ara dei Sacrifici", "karnak.php");
        }
    } elseif ($_GET['az'] == "processione") {
        if ($session['user']['turns'] < 5 OR $session['user']['playerfights'] < 1) {
            output("`5Sei troppo esausto per celebrare un `4Rito del Sangue`5 in onore di `\$Karnak`5.`n");
            addnav("`@Torna all'Ara dei Sacrifici", "karnak.php");
        } else {
            output("`%Celebri un `4Rito del Sangue`% in onore di `\$Karnak`%. Vi partecipano molti disperati e le vostre urla e `n");
            output("le vostre imprecazioni scendono fino al `\$Dannato`%. Senti la tua malvagità potenziata !!");
            $session['user']['punti_carriera'] += (40 + $venti);
            $session['user']['punti_generati'] += (40 + $venti);
            $fama = (40+$venti)*$session[user][fama_mod];
            $session['user']['fama3mesi'] += $fama;
            debuglog("Guadagna ".(40+$venti)." punti carriera e $fama punti fama da Karnak con un rito del sangue. Ora ha ".$session['user']['punti_carriera']." punti carriera e ".$session['user']['fama3mesi']." punti fama");
            if ($session['user']['superuser'] == 0){
                savesetting("puntikarnak", getsetting("puntikarnak",0)+(40 + $caso));
            }
            $session['user']['turns'] -= 5;
            $session['user']['playerfights'] -= 1;
            $session['user']['experience']+=($session['user']['level']*30);
            addnav("Rito del Sangue", "karnak.php?az=processione");
            addnav("Torna all'Ara dei Sacrifici", "karnak.php");
        }
    } elseif ($_GET['az'] == "massacro") {
        if ($session['user']['turns'] < 7 or $session['user']['playerfights'] < 1) {
            output("`5Sei troppo esausto per compiere un massacro in onore di `\$Karnak`5.`n");
            addnav("Torna all'Ara dei Sacrifici", "karnak.php");
        } else {
            output("`5Compi un massacro di innocenti in onore di `\$Karnak`5. Smembri molti poveretti che non avevano `n");
            output("fatto nulla di male e infierisci sui loro corpi. Al termine, coperto di sangue, senti che la tua cattiveria è aumentata !!");
            $session['user']['punti_carriera'] += (80 + $venti);
            $session['user']['punti_generati'] += (80 + $venti);
            $fama = (80+$venti)*$session[user][fama_mod];
            $session['user']['fama3mesi'] += $fama;
            debuglog("Guadagna ".(80+$venti)." punti carriera e $fama punti fama da Karnak con un massacro. Ora ha ".$session['user']['punti_carriera']." punti carriera e ".$session['user']['fama3mesi']." punti fama");
            if ($session['user']['superuser'] == 0){
                savesetting("puntikarnak", getsetting("puntikarnak",0)+(80 + $caso));
            }
            $session['user']['turns'] -= 7;
            $session['user']['playerfights'] -= 1;
            $session['user']['experience']+=($session['user']['level']*40);
            addnav("Massacro", "karnak.php?az=massacro");
            addnav("`@Torna all'Ara dei Sacrifici", "karnak.php");
        }
    } elseif ($_GET['az'] == "gs") {
        $session['user']['dove_sei'] = 2;
        $sqlpic = "SELECT acctid FROM accounts WHERE dove_sei=2";
        $resultpic = db_query($sqlpic);
        $player_ingrotta = db_num_rows($resultpic);
        output("`&Entri nella Sala delle Cerimonie del `#Falciatore di Anime`&.`n`n");
        $FdiA = getsetting("falciatore",0);
        $sqlfda = " SELECT `name` FROM `accounts` WHERE `acctid` = '$FdiA'";
        $resultfda = db_query($sqlfda) or die(db_error(LINK));
        $rowfda = db_fetch_assoc($resultfda);
        if ($FdiA == 0) {
            output("`6Nessuno raggiunge i requisiti richiesti da `\$Karnak `6per occupare la carica di `#Falciatore di Anime`6.`n`n");
            output("`6La prossima `!`bMessa Nera`b`6 potrà essere celebrata fra : `^`b$giorni_messa`b`6 giorni, `^`b$ore_messa`b`6 ore e `^`b$minuti_messa`b`6 minuti.`n`n");
            output("`6Sono presenti nella sala del `#Falciatore di Anime : `\$`b$player_ingrotta`b `6fedeli.`n`n");
            //} elseif (db_num_rows($result) > 1) {
            //    output("`\$`b`iErrore segnalalo ad un admin, ci sono troppi `#Falciatore di Anime`\$ !!!`b`i`n");
        } else {
            output("`&L'attuale Falciatore di Anime è : `@".$rowfda['name']."`n`n");
            output("`6La prossima `!Messa Nera`6 potrà essere celebrata fra : `^`b$giorni_messa`b`6 giorni, `^`b$ore_messa`b`6 ore e `^`b$minuti_messa`b`6 minuti.`n`n");
            output("`3Sono presenti nella sala del `#Falciatore di Anime`3 : `#`b$player_ingrotta`b `3fedeli.`n`n");
            if ($carriera == 16 OR $carriera == 15) {
                output("`VTu potrai celebrare la prossima `!Messa Nera`6 fra : `^`b$giorni_messa_player`b`6 giorni, `^`b$ore_messa_player`b`6 ore e `^`b$minuti_messa_player`b`6 minuti.`n`n");

            }
        }
        addnav("Azioni");
        if (($carriera == 16 OR $carriera == 15) AND $messa == 1 AND $messap==1 AND $session['user']['punti_carriera']>=10000) {
            addnav("`^Celebra `!Messa Nera`&", "karnak.php?az=messaconferma");
        }
        if ($potere_messa == 1 AND $session['user']['messa'] != 2) {
            addnav("`&Partecipa alla `!Messa Nera`&", "karnak.php?az=partecipa");
        }
        addnav("Altro");
        addnav("Sala delle Riunioni","salariunioni.php");
        addnav("`@Torna all'Ara dei Sacrifici", "karnak.php");
        addcommentary();
//        checkday();
        viewcommentary("Chiesa di Karnak", "Maledici",30,25);
        //Excalibur: concorso indovina la frase
        if (getsetting("indovinello", "") == "sbloccato" AND ($session['user']['dragonkills'] > 0 OR $session['user']['reincarna'] > 0)){
            addnav("Grande Concorso delle Sette (Indovina la Frase)","karnak.php?az=tryguess");
        }
        if (getsetting("indovinello", "") == "chiuso" AND ($session['user']['dragonkills'] > 0 OR $session['user']['reincarna'] > 0)){
            addnav("Grande Concorso delle Sette (Ritira Premio)","karnak.php?az=lookguess");
        }
        if (getsetting("indovinello", "") == "bloccato" AND ($session['user']['dragonkills'] > 0 OR $session['user']['reincarna'] > 0)){
            addnav("Grande Concorso delle Sette (Standby)","karnak.php?az=lockguess");
        }
    } elseif ($_GET['az'] == "tryguess") {
        $session['user']['premioindovinello'] = 0;
        $sql = "SELECT area FROM custom WHERE area1 = 'frasekarnaknascosta'";
        $result = db_query ($sql) or die(db_error(LINK));
        $row = db_fetch_assoc($result);
        $frase1 = stripslashes($row['area']);
        output("`n`c`@Attualmente la situazione della frase da indovinare è la seguente:`n");
        output("`b`#<tt><font size='+1'>`n".$frase1."</tt></font>`b`c`n",true);
        if ($session['user']['superuser'] > 3) {
           $sql = "SELECT area FROM custom WHERE area1 = 'frasekarnak'";
           $result = db_query ($sql) or die(db_error(LINK));
           $row = db_fetch_assoc($result);
           $frase = stripslashes($row['area']);
           output("`c`b`#<tt><font size='+1'>".$frase."</tt></font>`b`c`n",true);
        }
        $dataodierna = date("m-d");
        $dataguess = substr($session['user']['guess'], 5, 5);
        if ($dataguess != $dataodierna) {
           output("`@Non hai ancora effettuato il tentativo di indovinare la frase per oggi, vuoi provarci ?`n");
           addnav("Si, fammi provare","karnak.php?az=tryguess1");
           addnav("No, ci devo pensare","karnak.php?az=gs");
        }else{
           output("`@Hai già effettuato il tentativo odierno per indovinare la frase.`nTorna domani e potrai ");
           output("riprovarci.`n`n");
           addnav("`&Sala del Falciatore di Anime", "karnak.php?az=gs");
        }
    } elseif ($_GET['az'] == "tryguess1") {
        $sql = "SELECT area FROM custom WHERE area1 = 'frasekarnaknascosta'";
        $result = db_query ($sql) or die(db_error(LINK));
        $row = db_fetch_assoc($result);
        $frase1 = stripslashes($row['area']);
        output("`n`c`@Attualmente la situazione della frase da indovinare è la seguente:`n");
        output("`b`#`n<tt><font size='+1'>".$frase1."</tt></font>`b`c`n",true);
        if ($session['user']['superuser'] > 3) {
           $sql = "SELECT area FROM custom WHERE area1 = 'frasekarnak'";
           $result = db_query ($sql) or die(db_error(LINK));
           $row = db_fetch_assoc($result);
           $frase = stripslashes($row['area']);
           output("`c`b`#`n<tt><font size='+1'>".$frase."</tt></font>`b`c`n",true);
        }
        output("`@Scrivi la frase che pensi sia corretta.`n");
        output("<form action='karnak.php?az=tryguess2' method='POST'><input name='try' value=''><input type='submit' class='button' cols='80' value='Frase'>`n",true);
        addnav("","karnak.php?az=tryguess2");
        addnav("Ci ho ripensato","karnak.php?az=gs");
    } elseif ($_GET['az'] == "tryguess2") {
        $tryfrase = stripslashes($_POST['try']);
        $sql = "SELECT area FROM custom WHERE area1 = 'frasekarnak'";
        $result = db_query ($sql) or die(db_error(LINK));
        $row = db_fetch_assoc($result);
        $frase = stripslashes($row['area']);
        $sql = "SELECT area FROM custom WHERE area1 = 'frasekarnaknascosta'";
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
           addnav("`&Sala del Falciatore di Anime", "karnak.php?az=gs");
           savesetting("tentakarnak",(getsetting("tentakarnak",0)+1));
        } else {
           savesetting("tentakarnak",(getsetting("tentakarnak",0)+1));
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
           if ($minortry['try'] > getsetting("tentakarnak",0)){
               $minortry['try'] = getsetting("tentakarnak",0);
               $minortry['name'] = "Karnak";
               $minortry = serialize($minortry);
               savesetting("minortry",$minortry);
           }
           savesetting("indovinello","chiuso");
           savesetting("settaindovinello","karnak"); //(premio anche alla setta)
//         savesetting("settaindovinello","tutti");  //(premio a tutti tranne gli agnostici)
           savesetting("solutoreindovinello",$session['user']['name']);
           debuglog("ha indovinato la frase misteriosa dell'indovinello del Grande Concorso");
           debuglog("riceve $premio gemme come premio per l'indovinello del Grande Concorso");
           $session['user']['gems'] += ($premio * 2);
           $session['user']['premioindovinello'] = 1;
           addnav("`&Sala del Falciatore di Anime", "karnak.php?az=gs");
        }
    } elseif ($_GET['az'] == "lookguess") {
        $settaindovinello = getsetting("settaindovinello","nessuna");
        if ($settaindovinello != "karnak" AND $settaindovinello != "tutti") {
            output("`\$Purtroppo il `&Grande Concorso `iIndovina la Frase`i`\$ è stato vinto dalla setta `&$settaindovinello`n");
            output("Dovrete impegnarmi maggiormente nel prossimo concorso per aggiudicarvelo.`n");
            output("La frase misteriosa era:`n`n");
            $sql = "SELECT area FROM custom WHERE area1 = 'frasekarnak'";
            $result = db_query ($sql) or die(db_error(LINK));
            $row = db_fetch_assoc($result);
            $frase = stripslashes($row['area']);
            output("`n`c`b`#<tt><font size='+1'>".$frase."</tt></font>`b`n",true);
            $sql = "SELECT area FROM custom WHERE area1 = 'frasekarnaknascosta'";
            $result = db_query ($sql) or die(db_error(LINK));
            $row = db_fetch_assoc($result);
            $frase1 = stripslashes($row['area']);
            output("`b`#<tt><font size='+1'>".$frase1."</tt></font>`b`c`n",true);
            addnav("`&Sala del Falciatore di Anime", "karnak.php?az=gs");
        } else {
            output("`c`b<font size='+1'>`!C`@O`#N`5G`3R`^A`4T`1U`2L`2A`4Z`5I`6O`5N`7I`3!</font>`c`b`n`n",true);
            output("`^La tua setta si è aggiudicata il `&Grande Concorso `iIndovina la Frase`i`^.`n");
            output("La frase è stata indovinata da `(".getsetting("solutoreindovinello","sconosciuto")."`^, e la frase era:`n");
            $sql = "SELECT area FROM custom WHERE area1 = 'frasekarnak'";
            $result = db_query ($sql) or die(db_error(LINK));
            $row = db_fetch_assoc($result);
            $frase = stripslashes($row['area']);
            output("`n`c`b`#<tt><font size='+1'>".$frase."</tt></font>`b`c`n",true);
            if ($session['user']['premioindovinello'] == 1) {
               output("`^Hai già ritirato il premio che ti spettava, ora puoi solo aspettare che venga indetto un ");
               output("nuovo concorso, non puoi fare nient'altro qui.`n");
               addnav("`&Sala del Falciatore di Anime", "karnak.php?az=gs");
            } else {
               $premio = getsetting("premioconcorso",5);
               $session['user']['premioindovinello'] = 1;
               output("`^Ora ti verrà consegnato il premio, cioè `@$premio Gemme`^, fanne buon uso !!`n");
               $session['user']['gems'] += $premio;
               debuglog("riceve $premio gemme come premio per l'indovinello del Grande Concorso");
               addnav("`&Sala del Falciatore di Anime", "karnak.php?az=gs");
            }
        }
    } elseif ($_GET['az'] == "lockguess") {
        output("`@Il `&Grande Concorso `iIndovina la Frase`i`@ è attualmente bloccato.`nProbabilmente gli Admin ");
        output("stanno pensando a qualche frase particolarmente complicata da farti indovinare.`n");
        output("Controlla più tardi se viene abilitata l'opzione per indovinare la frase.`n`n");
        addnav("`&Sala del Falciatore di Anime", "karnak.php?az=gs");
        //Excalibur: fine concorso indovina la frase
    } elseif ($_GET['az'] == "messaconferma") {
        output("`n`7Attenzione, stai per celebrare la messa nera in onore di `\$Karnak`7. Sei sicuro di volerlo fare?`n`n");
        output("<table border=0 cellpadding=2 cellspacing=1 align=center>",true);
                output("<tr class='trlight'><td><a href=karnak.php?az=messa>`bCelebra la messa nera`b</a></td></tr>", true);
        output("</table>",true);
                addnav("","karnak.php?az=messa");
                addnav("`&Sala del Falciatore di Anime", "karnak.php?az=gs");
    } elseif ($_GET['az'] == "messa") {
        if ($messa==1) {
            output("`2Dopo aver raccolto una massa di seguaci, inizi a cantilenare le tue preghiere. Celebri la `!Messa Nera`2 in ");
            output("onore di `\$Karnak`2.`n La funzione consuma `bmolti`b dei tuoi punti carriera.");
            $session['user']['punti_carriera']-=5000;
            addnav("Sala del Falciatore di Anime", "karnak.php?az=gs");
            savesetting("tempomessasat", $data_messa);
            $sql = "UPDATE accounts SET messa = 0 WHERE dio = 2";
            db_query($sql);
            $session['user']['messa'] = 0;
            $sql = "UPDATE messa SET data = '".$data_messa."'WHERE acctid = '".$session['user']['acctid']."'";
            db_query($sql);
            $sql = "SELECT acctid FROM accounts WHERE dove_sei=2";
            $result = db_query($sql);
            $player_ingrotta = db_num_rows($result);
            if ($player_ingrotta <=2) $session['user']['punti_carriera']=-100;
            savesetting("player_ingrotta", $player_ingrotta);
            // Maximus modifica messa, salvo i presenti per dare la possibilità
            // a tutti i partecipanti di ricevere la stessa percentuale dei premi
            savesetting("part_messanera", $player_ingrotta);
            debuglog("celebra la messa in onore di Karnak con $player_ingrotta partecipanti");
            // Fine
            //Sook, calcolo base del premio (nuovo sistema)
            //calcolo valori dei presenti
            $sqlbase = "SELECT COUNT(acctid) AS presenti, SUM(punti_carriera) AS punti_presenti, SUM(dragonkills) AS dk_presenti, SUM(reincarna) AS reinc_presenti FROM accounts WHERE dove_sei=2 AND (dragonkills>2 OR reincarna>0) AND superuser=0 GROUP BY dio";
            $resultbase = db_query($sqlbase);
            $rowbase = db_fetch_assoc($resultbase);
            $player_inchiesa = $rowbase['presenti'];
            $punti_carriera_presenti = $rowbase['punti_presenti'];
            $dk_presenti = $rowbase['dk_presenti'] + 30 * $rowbase['reinc_presenti'];
            $base1 = $punti_carriera_presenti/$dk_presenti/$player_inchiesa;
            //calcolo valori totali dei fedeli
            $sqlbp = "SELECT COUNT(acctid) AS fedeli, SUM(punti_carriera) AS punti_totali, SUM(dragonkills) AS dk_totali, SUM(reincarna) AS reinc_totali FROM accounts WHERE dio=2 AND (dragonkills>2 OR reincarna>0) AND superuser=0 GROUP BY dio";
            $resultbp = db_query($sqlbp);
            $rowbp = db_fetch_assoc($resultbp);
            $player_infede = $rowbp['fedeli'];
            $punti_carriera_totali = $rowbp['punti_totali'];
            $dk_totali = $rowbp['dk_totali'] + 30 * $rowbp['reinc_totali'];
            $base2 = $punti_carriera_totali/$dk_totali/$player_infede;

            $rapporto_fedeli = $player_inchiesa / $player_infede;
            $rapporto_dk = $dk_presenti / $dk_totali;
            $rapporto_carriera = $punti_carriera_presenti / $punti_carriera_totali;

            $base=min($base1,$base2);
            $presenza=$rapporto_fedeli+$rapporto_dk+$rapporto_carriera;
            $base+=$presenza;
            $base+=(0.02*$player_inchiesa);
            savesetting("baseKarnak",$base);
            //fine calcolo base
        } else {
            output("`^ERRORE: la messa è stata già celebrata da un altro personaggio, dovrai aspettare prima di celebrarne un'altra");
            addnav("`&Sala del Falciatore di Anime", "karnak.php?az=gs");
        }
    } elseif ($_GET['az'] == "partecipa") {
        // Maximus display dei partecipanti, tanto per far vedere quanti sono...
        $part_messanera = getsetting("part_messanera", 0);
        //output("`^Ti unisci al canto in onore di `\$Karnak`^, tutti i presenti sono concentrati, l'aria inizia scintillare,
        output("`^Insieme ad altri `\${$part_messanera} `^seguaci, ti unisci al canto in onore di `\$Karnak`^, tutti i presenti sono concentrati, l'aria inizia scintillare,
    l'immenso potere di `\$Karnak`^ scende sugli scalmanati presenti.`n`n");
        $session['user']['messa'] = 2;
        if ($session['user']['punti_carriera'] != -100){
            output("`&Nella mente ti si focalizzano questi elementi, `\$Karnak`& attende che tu decida.`n");
            output("`^Oro.`n");
            output("`&Vita.`n");
            addnav("`^Oro", "karnak.php?az=partecipa_oro");
            addnav("`&Vita", "karnak.php?az=partecipa_vita");
            if ($carriera != 0) {
                output("`#Abilità.`n");
                addnav("`#Abilità", "karnak.php?az=partecipa_abilita");
            }
        }else{
            output("`4La tua avidità non piace a `\$Karnak`4. `nVuoi tenere solo per te i doni che `\$Karnak`4 così ");
            output("generosamente mette a disposizione di `b`^TUTTI`b`4 i suoi adoratori ? `nAdesso ne pagherai le conseguenze ");
            output("stolto mortale !!!`n`n");
            $session['user']['punti_carriera']=200;
            $carriera=10;
            $gemloss=round($session['user']['gems']/2);
            output("`2Un `b`i`&F `#U `&L `#M `&I `#N `&E`b`i`2 cade dal cielo e ti colpisce in pieno petto !!!`n`\$Sei morto !!!`n");
            output("`^Perdi tutto l'oro che avevi con te, e `\$Karnak`^ punisce la tua avidità togliendoti il 20% della tua esperienza, ");
            output("la metà delle gemme che possedevi, `bTUTTI`b i tuoi punti carriera, e ti retrocede a semplice `iInvasato`i.");
            $session['user']['alive']=false;
            debuglog("ha voluto fare il furbo celebrando una messa solo per sè e perde oro, gemme, 20% exp e resta con 200 Punti Carriera !!!");
            addnews("`\$".$session['user']['name']." `@ha voluto approfittare della generosità di `\$Karnak`@, celebrando la `!Messa Nera`@ per se stesso,
        e non volendo condividere i doni di `\$Karnak`@ con gli altri fedeli. È stato punito duramente per la sua avidità !!");
            $session['user']['hitpoints']=0;
            $session['user']['messa'] = 2;
            $session['user']['experience']*=0.8;
            $session['user']['gold']=0;
            $session['user']['gems']-=$gemloss;
            addnav("Terra delle Ombre","shades.php");
        }
    } elseif ($_GET['az'] == "partecipa_oro") {
/*        // Maximus modifica messa
        $part_messanera = getsetting("part_messanera", 0);
        $session['user']['suppliche']=0;
        $oro_messa = ($part_messanera  * ($part_messanera/10) * 50);
        if ($oro_messa < 5000)$oro_messa=5000;
        if ($oro_messa > 50000)$oro_messa=50000;
        if ($carriera==15) $oro_messa *= 1.5;
        output("`^Davanti a te si materializzano `b$oro_messa`b Pezzi d'Oro. La `!Messa Nera`^ celebrata in onore di `\$Karnak`^ ");
        output("ha raggiunto il `4Malvagio Supremo`^, che dal masso della sua malevolenza ha accolto le tue implorazioni. `n");
        addnav("`&Sala del Falciatore di Anime", "karnak.php?az=gs");
        $session['user']['gold'] += $oro_messa;
        debuglog("riceve $oro_messa oro alla grotta per aver partecipato alla Messa Nera");
        // Fine*/

        // Vecchio sistema
        /*
        $session['user']['suppliche']=0;
        $oro_messa = (($player_ingrotta * $caso) * $player_ingrotta * $player_ingrotta * $player_ingrotta);
        if ($oro_messa > 20000)$oro_messa=20000;
        if ($carriera==15) $oro_messa *= 1.5;
        output("`^Davanti a te si materializzano `b$oro_messa`b Pezzi d'Oro. La `!Messa Nera`^ celebrata in onore di `\$Karnak`^ ");
        output("ha raggiunto il `4Malvagio Supremo`^, che dal masso della sua malevolenza ha accolto le tue implorazioni. `n");
        addnav("`&Sala del Falciatore di Anime", "karnak.php?az=gs");
        $session['user']['gold'] += $oro_messa;
        debuglog("riceve $oro_messa oro alla chiesa per aver partecipato alla Messa Nera");
        */
        //Nuovo sistema (Sook)
        //calcolo coefficiente bonus carriera
        switch($session['uesr']['carriera']) {
            case 11:
            case 7:
            case 43:
                $grado = 1.2;
            break;
            case 12:
                $grado = 1.4;
            break;
            case 13:
                $grado = 1.6;
            break;
            case 16:
                $grado = 1.8;
            break;
            case 15:
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
        $part_messanera = getsetting("part_messanera", 0);
        $base=getsetting("baseKarnak",0)+($session['user']['punti_generati']/1000);
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
        output("`^Davanti a te si materializzano `b$oro_messa`b Pezzi d'Oro. La `!Messa Nera`^ celebrata in onore di `\$Karnak`^ ");
        output("ha raggiunto il `4Malvagio Supremo`^, che dal masso della sua malevolenza ha accolto le tue implorazioni. `n");
        addnav("`&Sala del Falciatore di Anime", "karnak.php?az=gs");
        $session['user']['gold'] += $oro_messa;
        debuglog("riceve $oro_messa oro alla grotta per aver partecipato alla Messa Nera");
        // Fine

    } elseif ($_GET['az'] == "partecipa_vita") {
/*        // Maximus modifica messa
        $part_messanera = getsetting("part_messanera", 0);
        $session['user']['suppliche']=0;
        $vita_messa = round(($part_messanera / 10)*2.5);
        if ($vita_messa < 2) $vita_messa = 2;
        if ($vita_messa > 25) $vita_messa = 25;
        if ($carriera==15) $vita_messa *= 1.2;
        output("`&Senti la tua vitalità crescere. La `!Messa Nera`& celebrata in onore di `\$Karnak`& ");
        output("ha raggiunto il `4Malvagio Supremo`&, che dal basso della sua malevolenza ha accolto le tue implorazioni. `n");
        addnav("`&Sala del Falciatore di Anime", "karnak.php?az=gs");
        $session['user']['maxhitpoints'] += intval($vita_messa);
        debuglog("riceve $vita_messa HP permanenti alla grotta per aver partecipato alla Messa Nera");
        // Fine*/

        // Vecchio sistema
        /*
        $session['user']['suppliche']=0;
        $vita_messa = round($player_ingrotta * $caso / 2);
        if ($vita_messa < 10) $vita_messa = 10;
        if ($carriera==15) $vita_messa *= 1.2;
        output("ha raggiunto il `4Malvagio Supremo`&, che dal basso della sua malevolenza ha accolto le tue implorazioni. `n");
        addnav("`&Sala del Falciatore di Anime", "karnak.php?az=gs");
        $session['user']['maxhitpoints'] += $vita_messa;
        debuglog("riceve $vita_messa HP permanenti alla chiesa per aver partecipato alla Messa Nera");
        */
        //Nuovo sistema (Sook)
        $part_messanera = getsetting("part_messanera", 0);
        $base=getsetting("baseKarnak",0)+$session['user']['punti_generati']/1000;
        $session['user']['punti_generati']=0;
        $session['user']['suppliche']=0;
        $vita_messa = ($base/3) + intval($session['user']['dragonkills']/5) + $session['user']['reincarna']*6;
        $b1 = e_rand(-1, 1);
        $b2 = e_rand(-1, 1);
        $bonus = (4+$b1+$b2)/4;
        $vita_messa = round($vita_messa*$bonus);
        if ($vita_messa < 5) $vita_messa = 5;
        if ($vita_messa > 35) $vita_messa = 35;
        output("`&Senti la tua vitalità crescere. La `!Messa Nera`& celebrata in onore di `\$Karnak`& ");
        output("ha raggiunto il `4Malvagio Supremo`&, che dal basso della sua malevolenza ha accolto le tue implorazioni. `n");
        addnav("`&Sala del Falciatore di Anime", "karnak.php?az=gs");
        $session['user']['maxhitpoints'] += intval($vita_messa);
        debuglog("riceve $vita_messa HP permanenti alla grotta per aver partecipato alla Messa Nera");
        // Fine

    } elseif ($_GET['az'] == "partecipa_abilita") {
/*        // Maximus modifica messa
        $part_messanera = getsetting("part_messanera", 0);
        $session['user']['suppliche']=0;
        $abilita_messa = intval($part_messanera * $part_messanera * 0.5);
        if ($abilita_messa < 500) $abilita_messa = 500;
        if ($abilita_messa > 5000) $abilita_messa = 5000;
        if ($carriera==15) $abilita_messa *= 1.5;
        if (($carriera > 9 AND $carriera < 14) OR $carriera == 15 or $carriera == 16) {
            output("`#Senti la tua malvagità crescere. La `!Messa Nera`# celebrata in onore di `\$Karnak`# ");
            output("ha raggiunto il `6Malvagio Supremo`#, che dal basso della sua malevolenza ha accolto le tue implorazioni. `n");
        }
        if ($carriera > 4 AND $carriera < 9) {
            output("`#Senti la tua abilità di fabbro crescere. La `!Messa Nera`# celebrata in onore di `\$Karnak`# ");
            output("ha raggiunto il `6Malvagio Supremo`#, che dal basso della sua malevolenza ha accolto le tue implorazioni. `n");
        }
        addnav("Sala del Falciatore di Anime", "karnak.php?az=gs");
        $session['user']['punti_carriera'] += $abilita_messa;
        $fama = ($abilita_messa)*$session[user][fama_mod];
        $session['user']['fama3mesi'] += $fama;
        debuglog("Guadagna $abilita_messa punti carriera e $fama punti fama alla grotta per aver partecipato alla messa nera. Ora ha ".$session['user']['punti_carriera']." punti carriera e ".$session['user']['fama3mesi']." punti fama");
        if ($session['user']['superuser'] == 0){
            savesetting("puntikarnak", getsetting("puntikarnak",0)+$abilita_messa);
        }
        // Fine*/

        // Vecchio sistema
        /*
        $session['user']['suppliche']=0;
        $abilita_messa = (($player_ingrotta * $caso) * $player_ingrotta * 1.5);
        if ($abilita_messa < 100) $abilita_messa = 100;
        if ($carriera==15) $abilita_messa *= 1.5;
        if (($carriera > 9 AND $carriera < 14) OR $carriera == 15) {
            output("`#Senti la tua malvagità crescere. La `!Messa Nera`# celebrata in onore di `\$Karnak`# ");
            output("ha raggiunto il `6Malvagio Supremo`#, che dal basso della sua malevolenza ha accolto le tue implorazioni. `n");
        }
        if ($carriera > 4 AND $carriera < 9) {
            output("`#Senti la tua abilità di fabbro crescere. La `!Messa Nera`# celebrata in onore di `\$Karnak`# ");
            output("ha raggiunto il `6Malvagio Supremo`#, che dal basso della sua malevolenza ha accolto le tue implorazioni. `n");
        }
        if ($carriera > 40 AND $carriera < 45) {
            output("`#Senti la tua abilità di mago crescere. La `!Messa Nera`# celebrata in onore di `\$Karnak`# ");
            output("ha raggiunto il `6Malvagio Supremo`#, che dal basso della sua malevolenza ha accolto le tue implorazioni. `n");
        }
        addnav("Sala del Falciatore di Anime", "karnak.php?az=gs");
        $session['user']['punti_carriera'] += $abilita_messa;
        $fama = ($abilita_messa)*$session[user][fama_mod];
        $session['user']['fama3mesi'] += $fama;
        debuglog("Guadagna $fama punti fama da Karnak. Ora ha ".$session['user']['fama3mesi']." punti");
        if ($session['user']['superuser'] == 0){
            savesetting("puntikarnak", getsetting("puntikarnak",0)+$abilita_messa);
        }
        debuglog("riceve $abilita_messa punti carriera alla chiesa per aver partecipato alla Messa Nera");
        */
        //Nuovo sistema (Sook)
        //calcolo coefficiente bonus carriera
        switch($session['uesr']['carriera']) {
            case 11:
            case 7:
            case 43:
                $grado = 1.2;
            break;
            case 12:
                $grado = 1.4;
            break;
            case 13:
                $grado = 1.6;
            break;
            case 16:
                $grado = 1.8;
            break;
            case 15:
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

        $part_messanera = getsetting("part_messanera", 0);
        $base=getsetting("baseKarnak",0)+$session['user']['punti_generati']/1000;
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
        if (($carriera > 9 AND $carriera < 14) OR $carriera == 15 or $carriera == 16) {
            output("`#Senti la tua malvagità crescere. La `!Messa Nera`# celebrata in onore di `\$Karnak`# ");
            output("ha raggiunto il `6Malvagio Supremo`#, che dal basso della sua malevolenza ha accolto le tue implorazioni. `n");
        }
        if ($carriera > 4 AND $carriera < 9) {
            output("`#Senti la tua abilità di fabbro crescere. La `!Messa Nera`# celebrata in onore di `\$Karnak`# ");
            output("ha raggiunto il `6Malvagio Supremo`#, che dal basso della sua malevolenza ha accolto le tue implorazioni. `n");
        }
        if ($carriera > 40 AND $carriera < 45) {
            output("`#Senti la tua abilità di mago crescere. La messa celebrata in onore di `\$Karnak`# ");
            output("ha raggiunto il `6Malvagio Supremo`#, che dal basso della sua malevolenza ha accolto le tue implorazioni. `n");
        }
        addnav("Sala del Falciatore di Anime", "karnak.php?az=gs");
        $session['user']['punti_carriera'] += $abilita_messa;
        $fama = $abilita_messa*$session[user][fama_mod];
        $session['user']['fama3mesi'] += $fama;
        debuglog("Guadagna $abilita_messa punti carriera e $fama punti fama alla grotta per aver partecipato alla messa nera. Ora ha ".$session['user']['punti_carriera']." punti carriera e ".$session['user']['fama3mesi']." punti fama");
        if ($session['user']['superuser'] == 0){
            savesetting("puntikarnak", getsetting("puntikarnak",0)+$abilita_messa);
        }
        // Fine

    } elseif ($_GET['az'] == "devoti") {
        output("`7Magicamente, in uno specchio incastonato di rubini `\$Karnak`7 mostra i nomi dei suoi figli più malvagi.`n`n");
        $sqlo = "SELECT * FROM accounts WHERE superuser = 0 AND punti_carriera >= 1 AND ((carriera >=10 AND carriera <=15) OR carriera=16) ORDER BY punti_carriera DESC";
        $resulto = db_query($sqlo) or die(db_error(LINK));
        output("<table cellspacing=0 cellpadding=2 align='center'><tr><td>&nbsp;</td><td>`bNome`b</td><td>`bLivello`b</td></tr>", true);
        if (db_num_rows($resulto) == 0) {
            output("<tr><td colspan=4 align='center'>`&Non ci sono adoratori di `\$Karnak`& nel villaggio`0</td></tr>", true);
        }
        $countrow1 = db_num_rows($resulto);
        for ($i=0; $i<$countrow1; $i++){
        //for ($i = 0;$i < db_num_rows($resulto);$i++) {
            $rowo = db_fetch_assoc($resulto);
            if ($row['name'] == $session['user']['name']) {
                output("<tr bgcolor='#007700'>", true);
            } else {
                output("<tr class='" . ($i % 2?"trlight":"trdark") . "'>", true);
            }
            /*if ($rowo['carriera'] == 10) {
            $livello = 'Invasato';
            } elseif ($rowo['carriera'] == 11) {
            $livello = 'Fanatico';
            } elseif ($rowo['carriera'] == 12) {
            $livello = 'Posseduto';
            } elseif ($rowo['carriera'] == 13) {
            $livello = 'Maestro delle Tenebre';
            } elseif ($rowo['carriera'] == 15) {
            $livello = 'Falciatore di Anime';
            } elseif ($rowo['carriera'] == 16) {
            $livello = 'Portatore di Morte';
            }*/
            $carr = $rowo['carriera'];
            output("<td>" . ($i + 1) . ".</td><td>".$rowo['name']."</td><td>".$prof[$carr]."</td></tr>", true);
        }
        output("</table>", true);
        addnav("`@Torna all'Ara dei Sacrifici", "karnak.php");
    } elseif ($_GET['az'] == "maledici") {
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
        output ("<tr><td>`#Puoi maledire  : </td><td>`b`^" . $ogg . "`b</td></tr>", true);
        if ($rowo['pregiato']==true AND getsetting("blocco_valore",0)=="1") output ("<tr><td>`^`bL'oggetto è pregiato`b </td><td></td></tr>", true);
        output ("<tr><td>`@Potenziamenti: </td><td>`b`\$" . $potenziamenti[$rowo['potenziamenti']] . "`b</td></tr>", true);
        output ("</table>", true);
        if ($rowo['potenziamenti'] > 0 AND $session['user']['punti_carriera'] > 2999) {
            addnav("`&Maledici oggetto", "karnak.php?az=maledici_conferma");
        }else if ($rowo['potenziamenti'] == 0) {
            output("`5`nL'oggetto non ha potenziamenti residui con cui maledirlo.`n");
        }else if ($session['user']['punti_carriera'] < 3000) {
            output("`%`nNon hai Punti Carriera a sufficienza per eseguire una Maledizione Semplice sull'oggetto.`n");
        }
        addnav("Vai all'Ara dei Sacrifici", "karnak.php");
    } elseif ($_GET['az'] == "maledici_conferma") {
        output("`%Cosa vuoi potenziare?`n`n");
        $sqlo = "SELECT pregiato FROM oggetti WHERE id_oggetti = ".$session['user']['oggetto'];
        $resultoo = db_query($sqlo) or die(db_error(LINK));
        $rowo = db_fetch_assoc($resultoo);
        output("`\$Attacco`n");
        output("`&Difesa`n");
        if ($rowo['pregiato']==false OR getsetting("blocco_valore",0)=="0") output("`^Valore`n");
        //output("`@Vita`n");
        addnav("`\$Attacco", "karnak.php?az=maledici_attacco");
        addnav("`&Difesa", "karnak.php?az=maledici_difesa");
        if ($rowo['pregiato']==false OR getsetting("blocco_valore",0)=="0") addnav("`^Valore", "karnak.php?az=maledici_valore");
        //addnav("`@Vita", "karnak.php?az=maledici_vita");
        addnav("");
        addnav("Torna all'Ara dei Sacrifici", "karnak.php");
    } elseif ($_GET['az'] == "maledici_attacco") {
        $oggetto = $session['user']['oggetto'];
        $attacco = $caso + e_rand (0,1);
        $bonus = $attacco * mt_rand(8,12);
        $sqlu = "UPDATE oggetti SET attack_help=attack_help+$attacco, potenziamenti=potenziamenti-1, valore=valore+$bonus WHERE id_oggetti='$oggetto'";
        db_query($sqlu) or die(db_error(LINK));
        //modifica per aggiornamento dell'usura
        $usuraextra =  $attacco * 100 + $bonus * 5 + $caso * 50;
        $sqlusura = "SELECT usuramax FROM oggetti WHERE id_oggetti='$oggetto'";
        $resultus = db_query($sqlusura) or die(db_error(LINK));
        $rowus = db_fetch_assoc($resultus);
        if ($rowus[usuramax]>0) {
            $sqlu = "UPDATE oggetti SET usuramax=usuramax+$usuraextra, usuraextra=usuraextra+$caso*50 WHERE id_oggetti='$oggetto'";
            db_query($sqlu) or die(db_error(LINK));
        }
        //fine modifica usura
        output("`7Una luce `\$`brossa`b`7 circonda il tuo oggetto.`n");
        output ("La forza d'attacco del tuo oggetto è stata migliorata di `b$caso`b punti.");
        output("`nEd il suo valore è aumentato di $bonus gemme`n");
        //modifica di Excalibur
        $session['user']['attack']+=$attacco;
        $session['user']['bonusattack']+=$attacco;
        //fine modifica
        debuglog("ha maledetto l'oggetto ($oggetto) migliorando di $attacco la forza d'attacco e $bonus gemme il suo valore");
        addnav("`@Vai all'Ara dei Sacrifici", "karnak.php");
        $session['user']['punti_carriera'] -= (1000 * $caso);
    } elseif ($_GET['az'] == "maledici_vita") {
        $oggetto = $session['user']['oggetto'];
        $vita = $caso * 5;
        $bonus = $caso * mt_rand(8,12);
        $sqlu = "UPDATE oggetti SET hp_help=hp_help+$vita, potenziamenti=potenziamenti-1, valore=valore+$bonus WHERE id_oggetti='$oggetto'";
        db_query($sqlu) or die(db_error(LINK));
        //modifica per aggiornamento dell'usura
        $usuraextra = $vita * 2 + $bonus * 5;
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
        debuglog("ha maledetto l'oggetto ($oggetto) migliorando di $vita la forza vitale e $bonus gemme il suo valore");
        addnav("`@Vai all'Ara dei Sacrifici", "karnak.php");
        $session['user']['punti_carriera'] -= (1000 * $caso);
    }elseif ($_GET['az'] == "maledici_difesa") {
        $oggetto = $session['user']['oggetto'];
        $bonus = $caso * mt_rand(8,12);
        $sqlu = "UPDATE oggetti SET defence_help=defence_help+$caso, potenziamenti=potenziamenti-1, valore=valore+$bonus WHERE id_oggetti='$oggetto'";
        db_query($sqlu) or die(db_error(LINK));
        output("`7Una luce `b`&bianca`7`b circonda il tuo oggetto.`n");
        output ("La forza protettrice che offre il tuo oggetto è stata migliorata di $caso punti.");
        output("`nEd il suo valore è aumentato di $bonus gemme`n");
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
        //modifica di Excalibur
        $session['user']['defence']+=$caso;
        $session['user']['bonusdefence']+=$caso;
        //fine modifica
        debuglog("ha maledetto l'oggetto ($oggetto) migliorando di $difesa la difesa e $bonus gemme il suo valore");
        addnav("`@Vai all'Ara dei Sacrifici", "karnak.php");
        $session['user']['punti_carriera'] -= (1000 * $caso);
    }elseif ($_GET['az'] == "maledici_valore") {
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
        debuglog("ha maledetto l'oggetto ($oggetto) migliorando di $bonus il suo valore");
        addnav("`@Vai all'Ara dei Sacrifici", "karnak.php");
        $session['user']['punti_carriera'] -= (1000 * $caso);
        // maledizione maggiore
    }elseif ($_GET['az'] == "maledici_maggiore") {
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
        output ("<tr><td>`#Puoi maledire  : </td><td>`b`^" . $ogg . "`b</td></tr>", true);
        if ($rowo['pregiato']==true AND getsetting("blocco_valore",0)=="1") output ("<tr><td>`^`bL'oggetto è pregiato`b </td><td></td></tr>", true);
        output ("<tr><td>`@Potenziamenti: </td><td>`b`\$" . $potenziamenti[$rowo['potenziamenti']] . "`b</td></tr>", true);
        output ("</table>", true);
        if ($rowo['potenziamenti'] > 0 AND $session['user']['punti_carriera'] > 8999) {
            addnav("`&Maledici oggetto", "karnak.php?az=maledici_maggiore_conferma");
        }else if ($rowo['potenziamenti'] == 0) {
            output("`5`nL'oggetto non ha potenziamenti residui con cui maledirlo.`n");
        }else if ($session['user']['punti_carriera'] < 9000) {
            output("`%`nNon hai Punti Carriera a sufficienza per eseguire una Maledizione Maggiore sull'oggetto.`n");
        }
        addnav("`@Vai all'Ara dei Sacrifici", "karnak.php");
    } elseif ($_GET['az'] == "maledici_maggiore_conferma") {
        output("`%Cosa vuoi potenziare?`n`n");
        $sqlo = "SELECT pregiato FROM oggetti WHERE id_oggetti = ".$session['user']['oggetto'];
        $resultoo = db_query($sqlo) or die(db_error(LINK));
        $rowo = db_fetch_assoc($resultoo);
        output("`\$Attacco`n");
        //output("`@Vita`n");
        output("`&Difesa`n");
        if ($rowo['pregiato']==false OR getsetting("blocco_valore",0)=="0") output("`^Valore`n");
        addnav("`\$Attacco", "karnak.php?az=maledici_maggiore_attacco");
        //addnav("`@Vita", "karnak.php?az=maledici_maggiore_vita");
        addnav("`&Difesa", "karnak.php?az=maledici_maggiore_difesa");
        if ($rowo['pregiato']==false OR getsetting("blocco_valore",0)=="0") addnav("`^Valore", "karnak.php?az=maledici_maggiore_valore");
        addnav("");
        addnav("Torna all'Ara dei Sacrifici", "karnak.php");
    } elseif ($_GET['az'] == "maledici_maggiore_attacco") {
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
        output ("La forza d'attacco del tuo oggetto è stata migliorata di `b$attacco`b punti.");
        output("`nEd il suo valore è aumentato di $bonus gemme`n");
        //modifica di Excalibur
        $session['user']['attack'] += $attacco;
        $session['user']['bonusattack'] += $attacco;
        //fine modifica
        debuglog("ha maledetto l'oggetto ($oggetto) migliorando di $attacco l'attacco e $bonus gemme il suo valore");
        addnav("`@Vai all'Ara dei Sacrifici", "karnak.php");
        $session['user']['punti_carriera'] -= (3000 * $caso);
    } elseif ($_GET['az'] == "maledici_maggiore_vita") {
        $oggetto = $session['user']['oggetto'];
        $vita = ($caso + 2) * 5;
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
        $session['user']['hitpoints']+=$caso;
        $session['user']['maxhitpoints']+=$caso;
        //fine modifica
        debuglog("ha maledetto l'oggetto ($oggetto) migliorando di $vita la forza vitale e $bonus gemme il suo valore");
        addnav("`@Vai all'Ara dei Sacrifici", "karnak.php");
        $session['user']['punti_carriera'] -= (3000 * $caso);
    } elseif ($_GET['az'] == "maledici_maggiore_difesa") {
        $oggetto = $session['user']['oggetto'];
        $difesa = $caso + e_rand (2,4);
        $bonus = $difesa * (mt_rand(8,12) + 5);
        $sqlu = "UPDATE oggetti SET defence_help=defence_help+$difesa, potenziamenti=potenziamenti-1, valore=valore+$bonus WHERE id_oggetti='$oggetto'";
        db_query($sqlu) or die(db_error(LINK));
        output("`7Una luce `b`&bianca`7`b circonda il tuo oggetto.`n");
        output ("La forza protettrice che offre il tuo oggetto è stata migliorata di $difesa punti.");
        output("`nEd il suo valore è aumentato di $bonus gemme`n");
        //modifica per aggiornamento dell'usura
        $usuraextra =  $attacco * 100 + $bonus * 5 + $caso * 100;
        $sqlusura = "SELECT usuramax FROM oggetti WHERE id_oggetti='$oggetto'";
        $resultus = db_query($sqlusura) or die(db_error(LINK));
        $rowus = db_fetch_assoc($resultus);
        if ($rowus[usuramax]>0) {
            $sqlu = "UPDATE oggetti SET usuramax=usuramax+$usuraextra WHERE id_oggetti='$oggetto'";
            db_query($sqlu) or die(db_error(LINK));
        }
        //fine modifica usura
        //modifica di Excalibur
        $session['user']['defence'] += $difesa;
        $session['user']['bonusdefence'] += $difesa;
        //fine modifica
        debuglog("ha maledetto l'oggetto ($oggetto) migliorando di $difesa la difesa e $bonus gemme il suo valore");
        addnav("`@Vai all'Ara dei Sacrifici", "karnak.php");

        $session['user']['punti_carriera'] -= (3000 * $caso);
    }elseif ($_GET['az'] == "maledici_maggiore_valore") {
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
        output("`7Una luce `b`6giallastra`7`b circonda il tuo oggetto.`n");
        output ("Il valore del tuo oggetto è stato aumentato di $bonus gemme.");
        debuglog("ha maledetto l'oggetto migliorando di $bonus gemme il suo valore");
        addnav("`@Vai all'Ara dei Sacrifici", "karnak.php");
        $session['user']['punti_carriera'] -= (3000 * $caso);
    }elseif ($dio != 2 AND $dio !=0) {

        page_header("La Grotta delle Voci");
        output(" `c`2<big>La Grotta delle Voci</big>`c`n`n",true);
        output(" `3`cTi addentri in una umida grotta, malamente illuminata da torce consunte che emanano`n");
        output(" bagliori sinistri. Sparse ovunque noti delle ossa che hanno una forma che ti è familiare. `n");
        output(" Dei brividi ti percorrono la schiena, hai riconosciuto una tibia umana, e poco più in la scorgi`n");
        output(" un teschio che è sicuramente appartenuto ad un elfo. Dopo pochi passi ti fermi, le voci che senti`n");
        output(" provenire dal fondo della grotta, dove scorgi un bagliore più intenso, non sono per niente rassicuranti,`n");
        output(" anzi più che voci sono dei suoni gutturali ed animaleschi che ti fanno rabbrividire. Decidi che non è`n");
        output(" per niente salutare proseguire oltre, quindi inverti la direzione e torni verso la rassicurante luce del`n");
        output(" giorno che proviene dall'entrata della grotta. Sai di aver fatto la cosa giusta, e nessuno verrà mai a `n");
        output(" sapere di questo tuo comportamento che non fa di certo onore alla figura figura del guerriero che vuoi `n");
        output(" dimostrare di essere. Mentre riguadagni l'uscita odi in lontananza delle voci ... meglio affrettarsi !!`c`n`n");
        addnav("Exit");
        addnav("Torna al Villaggio", "village.php");
    }elseif($_GET['az']=="chiedi"){
        addnav("`&Vai all'entrata", "karnak.php");
        if ($carriera == 10 ) {
            output("`3Ti interroghi profondamente cercando di capire che considerazione ha `\$Karnak`3 nei tuoi confronti`n e preghi:`#\"Karnak sono un tuo adoratore dimmi quanto manca alla mia promozione ?\"`7.`n");
            $voto = intval($session['user']['punti_carriera']/200);
            output("`#Una voce tonante esplode nella tua mente :`& \"In questo momento sei un `$ Invasato `&e la mia considerazione per te è pari a `$ $voto `&su 10 \"`7.`n `#Stai tremando come una foglia, pensi che non è una buona idea disturbare Karnak per queste frivolezze.");
            $session['user']['punti_carriera']-=1;
        }
        if ($carriera == 11 ) {
            output("`3Ti interroghi profondamente cercando di capire che considerazione ha `\$Karnak`3 nei tuoi confronti`n e preghi:`#\"Karnak sono un tuo adoratore dimmi quanto manca alla mia promozione ?\"`7.`n");
            $voto = intval($session['user']['punti_carriera']/500);
            output("`#Una voce tonante esplode nella tua mente :`& \"In questo momento sei un `$ Fanatico `&e la mia considerazione per te è pari a `$ $voto `&su 10 \"`7.`n `#Stai tremando come una foglia, pensi che non è una buona idea disturbare Karnak per queste frivolezze.");
            $session['user']['punti_carriera']-=1;
        }
        if ($carriera == 12 ) {
            output("`3Ti interroghi profondamente cercando di capire che considerazione ha `\$Karnak`3 nei tuoi confronti`n e preghi:`#\"Karnak sono un tuo adoratore dimmi quanto manca alla mia promozione ?\"`7.`n");
            $voto = intval($session['user']['punti_carriera']/2000);
            output("`#Una voce tonante esplode nella tua mente :`& \"In questo momento sei un `$ Posseduto `&e la mia considerazione per te è pari a `$ $voto `&su 10 \"`7.`n `#Stai tremando come una foglia, pensi che non è una buona idea disturbare Karnak per queste frivolezze.");
            $session['user']['punti_carriera']-=1;
        }
        if ($carriera == 13 ) {
            output("`3Ti interroghi profondamente cercando di capire che considerazione ha `\$Karnak`3 nei tuoi confronti`n e preghi:`#\"Karnak sono un tuo adoratore dimmi quanto manca alla mia promozione ?\"`7.`n");
            $voto = intval($session['user']['punti_carriera']/10000);
            output("`#Una voce tonante esplode nella tua mente :`& \"In questo momento sei un `$ Maestro delle enebre `&e la mia considerazione per te è pari a `$ $voto `&su 10 \"`7.`n `#Stai tremando come una foglia, pensi che non è una buona idea disturbare Karnak per queste frivolezze.");
            $session['user']['punti_carriera']-=1;
        }
        if ($carriera == 16 OR $carriera == 15) {
            if ($carriera == 15) {
                output("`3Ti interroghi profondamente cercando di capire che considerazione ha `\$Karnak`3 nei tuoi confronti`n e preghi:`#\"Karnak sono un tuo adoratore dimmi quanto manca alla mia promozione ?\"`7.`n");
                output("`#Una voce tonante esplode nella tua mente :`& \"Ma tu sei il mio Falciatore di Anime !!\"`7.`n `#Un fulmine ti colpisce, e pensi di essere uno stupido.");
                $session['user']['punti_carriera']-=10;
                if ($session['user']['hitpoints'] >= ($session['user']['maxhitpoints']/2)){
                   $session['user']['hitpoints']=intval($session['user']['hitpoints']/4);
                }
            }else{
                output("`3Ti interroghi profondamente cercando di capire che considerazione ha `\$Karnak`3 nei tuoi confronti`n e preghi:`#\"Karnak sono un tuo adoratore dimmi quanto manca alla mia promozione ?\"`7.`n");
                output("`#Una voce tonante esplode nella tua mente :`& \"In questo momento sei un `% Portatore di Morte `&e puoi solo diventare il mio `\$Falciatore di Anime`&, se sarai più devoto \"`7.`n `3Pensi che non è una buona idea disturbare Karnak per queste frivolezze.");
                $session['user']['punti_carriera']-=1;
            }
        }
    }
    //Excalibur: Mail di massa
    if ($_GET['az'] == "mail") {
         output("Testo da inviare.`n");
         output("<form action='karnak.php?az=mailto&op=".$_GET['op']."' method='POST'>",true);
         output("<textarea class='input' name='body' cols='37' rows='5'>".HTMLEntities2(stripslashes($_POST['body']))."</textarea>`n",true);
         output("<input type='submit' class='button' value='Invia'></form>",true);
         addnav("","karnak.php?az=mailto&op=".$_GET['op']);
         addnav("Torna all'entrata","karnak.php");
    }
    if ($_GET['az'] == "mailto") {
         $body = "`^Messaggio del Falciatore di Anime.`n";
         $body .="`#".$_POST['body'];
         if ($_GET['op'] == 1) {
             $clausula = "dio=2 AND superuser=0";
         }elseif ($_GET['op'] == 2) {
             $clausula = "dio=2 AND (carriera>9 OR carriera<17) AND superuser=0";
         }elseif ($_GET['op'] == 3) {
             $clausula = "dio=2 AND carriera=16 AND superuser=0";
         }
         $sqlmail = "SELECT acctid FROM accounts WHERE ".$clausula;
         $resultmail = db_query($sqlmail);
         //output("Query SQL: ".$sqlmail."`nNumero righe: ".db_num_rows($resultmail));
         $countrowmail = db_num_rows($resultmail);
         for ($imail=0; $imail<$countrowmail; $imail++){
         //for ($imail=0;$imail<db_num_rows($resultmail);$imail++){
             $rowmail = db_fetch_assoc($resultmail);
             systemmail($rowmail['acctid'],"`^Comunicazione del Falciatore di Anime!`0",$body,$session['user']['acctid']);
             //output("Account ID N°".($imail+1).": ".$rowmail['acctid']."`n");
         }
         addnav("`&Vai all'entrata", "karnak.php");
    }
    //Excalibur: fine Mail di massa
    if ($_GET['az'] == "punisci") {
        //addnav("`&Vai all'entrata", "karnak.php");
        output("`3Da quì puoi infliggere le punizioni in nome di `\$Karnak`3 e bandire i fedeli per alcuni giorni (di gioco) massimo 9.`7.`n");
        output("<form action='karnak.php?az=addpun' method='POST'>",true);
        addnav("","karnak.php?az=addpun");
        output("`bPersonaggio: <input name='name'>`nGiorni di punizione: <input name='amt' size='3'>`n<input type='submit' class='button' value='Punisci'>",true);
        output("</form>",true);
    }
    if ($_GET['az'] == "addpun") {
        addnav("`&Vai all'entrata", "karnak.php");
        $search="%";
        for ($i=0;$i<strlen($_POST['name']);$i++){
            $search.=substr($_POST['name'],$i,1)."%";
        }
        $sql = "SELECT name,acctid,superuser FROM accounts WHERE login LIKE '$search' AND dio = 2";
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
                output("<a href='karnak.php?op=add2&id={$row['acctid']}&amt={$punizione}'>",true);
                output($row['name']." ({$row['punizione']})");
                output("</a>`n",true);
                addnav("","karnak.php?op=add2&id={$row['acctid']}&amt={$punizione}");
            }
        }
    }
    if ($_GET['op']=="add2"){
        addnav("`&Vai all'entrata", "karnak.php");
        $punizione=$_GET['amt'];
        $sqlpun = "SELECT * FROM punizioni_chiese WHERE acctid='{$_GET['id']}' AND fede='2'";
        $respun = db_query($sqlpun) or die(db_error(LINK));
        if (db_num_rows($respun) == 0) {
            $sqli = "INSERT INTO punizioni_chiese (acctid,giorni,fede) VALUES ('{$_GET['id']}','{$punizione}','2')";
            $resulti=db_query($sqli);
            $mailmessage = "`\$Il falciatore di anime `7ti ha inflitto una punizione!`nSei stato bandito dalla grotta di Karnak per `\$".$_GET['amt']." `7 giorni.`n";
            systemmail($_GET['id'],"`2Punizione.`2",$mailmessage);
            output("`3Hai inflitto la punizione in nome di `\$Karnak`3!`n");
        } else {
            $sqli = "UPDATE punizioni_chiese SET giorni='{$punizione}' WHERE acctid='{$_GET['id']}' AND fede='2'";
            $resulti=db_query($sqli);
            $mailmessage = "`\$Il falciatore di anime `7ha modificato la tua punizione!`nSei ora bandito dalla grotta di Karnak per `\$".$_GET['amt']." `7 giorni.`n";
            systemmail($_GET['id'],"`2Punizione.`2",$mailmessage);
            output("`3Hai modificato la punizione in nome di `\$Karnak`3!`n");
        }
        $_GET['op']="";

    }
    if ($_GET['az']=="puniti"){
        addnav("`&Vai all'entrata", "karnak.php");
        $sql = "SELECT a.acctid,a.name,b.giorni FROM accounts a, punizioni_chiese b WHERE a.acctid=b.acctid AND b.giorni>0 AND a.dio=2 AND b.fede=2 ORDER BY b.giorni DESC";
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
    page_header("Ostracizzato dalla grotta!");
    $sql = "SELECT * FROM punizioni_chiese WHERE acctid='{$session['user']['acctid']}' AND fede=2";
    $result = db_query($sql);
    if (db_num_rows($result) != 0) {
        $ref = db_fetch_assoc($result);
    }
    output("`5Nonostante la punizione che ti ha inflitto il Falciatore di Anime rappresentante di `\$Karnak`5 provi ad entrare nella grotta.`n");
    output("`5Ma tutti sanno delle tue malefatte ti malmenano e ti ributtano fuori dalla grotta!");
    output("`5Ti dicono di tornare tra `\$".$ref[giorni]." giorni`5, quando scadrà la tua punizione!`n`n");
    addnav("Torna al Villaggio", "village.php");
}
page_footer();

?>