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

// Sook, nuova procedura per assegnazione messa slegata dal newday
$ultima_messa = getsetting("tempogilda", 0);
/*
$sql = "SELECT value1 FROM items WHERE class = 'messa' AND value2=3 AND owner='".$session['user']['acctid']."'";
$result = db_query($sql) or die(db_error(LINK));
$row = db_fetch_assoc($result);
if (db_num_rows($result)==0){
    $ultima_messa_player = 0;
} else {
    $ultima_messa_player = $row['value1'];
}
if ($ultima_messa!=$ultima_messa_player) {
    //il giocatore non ha partecipato all'ultima messa, allora attiviamo la possibilità di prenderla
    $session['user']['messa'] = 0;
    $sql = "SELECT * FROM items WHERE owner = '".$session['user']['acctid']."' AND value2=3 AND class = 'messa'";
    $result = db_query($sql) or die(db_error(LINK));
    if (db_affected_rows()==0){
        $sql = "INSERT INTO items (id,name,class,owner,value1,value2,gold,gems,description,hvalue,buff,tempo)
                       VALUES('','','messa','".$session['user']['acctid']."','$ultima_messa','3','','','','','','')";
        $result = db_query($sql) or die(db_error(LINK));
    }else{
        $sql = "UPDATE items SET value1='$ultima_messa' WHERE owner = '".$session['user']['acctid']."' AND value2=3 AND class = 'messa'";
        $result = db_query($sql) or die(db_error(LINK));
    }
}

*/
// Sook, fine procedura assegnazione messa


if ($session['user']['superuser'] > 2) {
    $session['user']['dio'] = 3;
    $session['user']['carriera'] = 50;
}
$sqlpun = "SELECT * FROM punizioni_chiese WHERE acctid = '{$session['user']['acctid']}' AND giorni > 0 AND fede = '3'";
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
    $player_inchiesa = getsetting("player_ingilda", 0);
    $data_messa = time();
    //Modifica per inserire news
    if ($dio == 3){
        $sql="SELECT * FROM custom WHERE area1='grancerimoniere'";
        $result=db_query($sql);
        $dep = db_fetch_assoc($result);
        if (db_num_rows($result) == 0) {
            $sqli = "INSERT INTO custom (dTime,area1) VALUES (NOW(),'grancerimoniere')";
            $resulti=db_query($sqli);
        }
        if ($carriera==54 AND !$_GET['az']){
            output("`0<form action=\"gildadrago.php\" method='POST'>",true);
            output("[Dominatore di Draghi] Inserisci Notizia? <input name='meldung' size='80'> ",true);
            output("<input type='submit' class='button' value='Insert'>`n`n",true);
            addnav("","gildadrago.php");
            if ($_POST['meldung']){
                $sql = "UPDATE custom SET dTime = now(),dDate = now() WHERE area1 = 'grancerimoniere'";
                $result=db_query($sql);
                $sql = "UPDATE custom SET amount = ".$session['user']['acctid']." WHERE area1 = 'grancerimoniere'";
                $result=db_query($sql);
                $sql = "UPDATE custom SET area ='".addslashes($_POST['meldung'])."' WHERE area1 = 'grancerimoniere'";
                $result=db_query($sql);
                $_POST[meldung]="";
            }
            addnav("","news.php");
        }
        if ($carriera==55 AND !$_GET['az']){
            output("`0<form action=\"gildadrago.php\" method='POST'>",true);
            output("[Cancelliere di Draghi] Inserisci Notizia? <input name='meldung' size='80'> ",true);
            output("<input type='submit' class='button' value='Insert'>`n`n",true);
            addnav("","gildadrago.php");
            if ($_POST['meldung']){
                $sqlogg = "DELETE FROM custom WHERE amount = '".$session['user']['acctid']."' AND area1='cerimoniere'";
                db_query($sqlogg) or die(db_error(LINK));
                $sqli = "INSERT INTO custom (amount,dTime,dDate,area,area1)
                VALUES ('".$session['user']['acctid']."',NOW(),NOW(),'".addslashes($_POST['meldung'])."','cerimoniere')";
                db_query($sqli);
                $_POST[meldung]="";
            }
            addnav("","news.php");
        }
        $sql="SELECT * FROM custom WHERE area1='grancerimoniere'";
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
            if($dep1[carriera]==54){
                    output("<big>`b`c`@ANNUNCIO DEL DOMINATORE DI DRAGHI `#$nomegs `@DELLA GILDA del `6DRAGO`0`c`b</big>`n",true);
                    output("`8".date("d/m/Y",strtotime($lastdate))." `6".date("h:i:s",strtotime($lasttime))."   `b`^".$msgchiesa."`b`n`n");
            }
        }
        output("`b`c`@ANNUNCI DEI CANCELLIERI DEI DRAGHI DELLA GILDA del `6DRAGO`0`c`b`n",true);
        $sql="SELECT * FROM custom WHERE area1='cerimoniere' ORDER BY dDate ASC, dTime ASC";
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
                if ($dep1[carriera]==55){
                    output("`8".date("d/m/Y",strtotime($lastdate))." `6".date("H:i:s",strtotime($lasttime))." `@".$dep1['login']." `^: `b".$msgchiesa."`b`n");
                }
            }
        }
        output("`n");
        //Excalibur: Visualizzazione annuncio prossima messa
        output("<font size='+2'>`b`c`2PROSSIMA MESSA IN ONORE DEL `@DRAGO VERDE`0`c`b</font>`n",true);
        $sql="SELECT * FROM custom WHERE area1='messadrago'";
        $result=db_query($sql);
        $dep = db_fetch_assoc($result);
        $quando = $dep['dDate']." ".$dep['dTime'];
        if (date ("Y-m-d H:m:s",strtotime ("now")) <= $quando){
           $msgproxmessa = stripslashes($dep['area']);
           output("<font size='+1'>`c`@".$msgproxmessa."`0`c</font>`n",true);
        }else{
           output("<font size='+1'>`c`^DATA MESSA DA DEFINIRE`0`c</font>`n",true);
           output("`c`2Ultima messa celebrata il ".$quando."`0`c`n",true);
        }
        //Excalibur: Fine Visualizzazione annuncio prossima messa
    }
    //Fine Modifica
    page_header("La Gilda del Drago");
    $session['user']['locazione'] = 133;
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
    if ($carriera == 54 OR $carriera == 55) {
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
    // Inserisce commento se ucciso seguace di Karnak o Sgrios e aggiunge punti carriera
    if ($session['user']['history'] AND $dio == 3){
        $paragone=strstr($session['user']['history'],"`@ ha reso inerme");
        $session['user']['history'] = addslashes($session['user']['history']);
        $sql = "INSERT INTO commenti (section,author,comment,postdate) VALUES ('Scontri Sette','".$session['user']['acctid']."','".$session['user']['history']."',NOW())";
        db_query($sql) or die(db_error($link));
        if ($paragone === false){
        }else{
            $session['user']['punti_carriera'] += $dieci;
            $session['user']['punti_generati'] += $dieci;
            $fama = (100*$dieci*$session[user][fama_mod]);
            $session['user']['fama3mesi'] += $fama;
            debuglog("Guadagna $dieci punti carriera e $fama punti fama dal Drago per aver ucciso un infedele. Ora ha ".$session['user']['punti_carriera']." punti carriera e ".$session['user']['fama3mesi']." punti fama");
        }
        $session['user']['history']="";
        if ($session['user']['superuser'] == 0){
            savesetting("puntidrago", getsetting("puntidrago",0)+$dieci);
        }else{
            print("Se tu non fossi stato un admin avrei aggiunto i punti alla setta");
        }
    }
    //fine inserimento commento

    //Excalibur: declassamento Dominatore di Draghi se punti fede < 100.000
    if ($session['user']['carriera']==54  AND $session['user']['punti_carriera']<100000 AND $dio==3){
        output("<big>`\$`b`cRetrocessione`c`n`b`0</big>",true);
        output("`#Non rispondi più ai requisiti per essere Dominatore di Draghi, pertanto vieni retrocesso al rango di Mastro di Draghi.`n");
        $session['user']['carriera']=53;
        $sqlogg = "DELETE FROM custom WHERE amount='".$session['user']['acctid']."' AND area1='cerimoniere'";
        db_query($sqlogg) or die(db_error(LINK));
    }
    //Excalibur: fine

    //Luke nuovo sistema per gestione Dominatore di Draghi
    if ($carriera == 53 OR $carriera == 54 OR $carriera == 55) {
        if ($session['user']['superuser'] == 0) {
            savesetting("grancerimoniere","0");
            $sqlma = "SELECT acctid FROM accounts WHERE
            (carriera = 53 OR carriera = 54 OR carriera = 55)
            AND punti_carriera > 99999
            AND reincarna > 0
            ORDER BY punti_carriera DESC LIMIT 1";
            $resultma = db_query($sqlma) or die(db_error(LINK));
            $rowma = db_fetch_assoc($resultma);
            $gs = $rowma['acctid'];
            savesetting("grancerimoniere", $gs);
        }
        if (getsetting("grancerimoniere",0)!=$session['user']['acctid']){
            if ($session['user']['punti_carriera']> 100000 AND $session['user']['reincarna']>0){
                $session['user']['carriera'] = 55;
            }else{
                $session['user']['carriera'] = 53;
            }
        }else{
            output("<big>`^`b`cPromozione`c`b`0`n</big>",true);
            output("Sei diventato il Dominatore di Draghi!`n");
            $session['user']['carriera'] = 54;
            $sqlogg = "DELETE FROM custom WHERE amount='".$session['user']['acctid']."' AND area1='cerimoniere'";
        db_query($sqlogg) or die(db_error(LINK));
        }
    }

    if ($carriera > 49 AND $carriera < 56) {
        $navi = "`b`^- Rango ".$prof[$carriera]."`b `^-`0";
        addnav($navi,"");
    } elseif ($dio == 3 AND (($carriera > 4 AND $carriera < 9) OR ($carriera > 40 AND $carriera < 45))) {
        addnav("Rango Fedele");
    }
    if ($dio == 3 AND $carriera == 0) {
        addnav("Diventa un Seguace", "gildadrago.php?az=seguace");
        addnav("(Adepto)","");
    }
    if ($dio == 0 AND !$_GET['az']) {
        addnav("Azioni");
        if ($carriera==0) {
            addnav("Diventa un seguace", "gildadrago.php?az=seguace");
            addnav("(Adepto)","");
        }
        addnav("Diventa un fedele", "gildadrago.php?az=fedele");
        addnav("(Simpatizzante)","");
        addnav("Altro");
        addnav("Torna al Villaggio", "village.php");
        output("Entri nella grande gilda dei Draghi`n");
    } elseif ($_GET['az'] == "fedele") {
        if ($dio != 3 AND $dio != 0) {
            output("Non puoi dedicarti ai Draghi e ad un'altra divinità, non fare il furbo.`n");
            addnav("Torna al Villaggio", "village.php");
        } else {
            output("Sei sicuro di voler diventare un fedele della gilda dei Draghi ?`n");
            output("Questa scelta è molto importante quindi pensaci bene, una volta presa non potrai tornare indietro.`n");
            addnav("Sono sicuro", "gildadrago.php?az=fedele_sicuro");
            addnav("Ci devo pensare", "village.php");
        }
    } elseif ($_GET['az'] == "fedele_sicuro") {
        output("`2Sei diventato un fedele della Gilda dei Draghi, benvenuto.`n Le tue responsabilità da ora in poi saranno grandi.`n");
        output("Dovrai contribuire attivamente alla diffusione del `#Verbo dei Draghi`2, e convertire gli eretici.`n");
        output("Potrai partecipare alle messe quando un Dominatore dei Draghi ne indirà una.`n");
        $session['user']['dio'] = 3;
        if (getsetting("grancerimoniere","0") != "0") {
            systemmail(getsetting("grancerimoniere","0"),"`@Nuovo fedele","`@".$session['user']['name']." `@è diventato fedele del Drago Verde!");
        }
        addnav("Avvicinati all'altare", "gildadrago.php");
    } elseif ($_GET['az'] == "seguace") {
        if ($carriera != 0) {
            output("Non puoi dedicarti ai Draghi e ad una professione minore, non fare il furbo.`n");
            addnav("Torna al Villaggio", "village.php");
        } else {
            output("Sei sicuro di voler diventare un seguace della Gilda dei Draghi?`n");
            output("Questa scelta è molto importante quindi pensaci bene, una volta presa non potrai tornare indietro.`n");
            addnav("Sono sicuro", "gildadrago.php?az=seguace_sicuro");
            addnav("Ci devo pensare", "village.php");
        }
    } elseif ($_GET['az'] == "seguace_sicuro") {
        output("Sei diventato uno stalliere di Draghi, benvenuto.`n");
        output("Avvicinati all'altare.`n");
        $session['user']['dio'] = 3;
        $session['user']['carriera']= 50;
        if (getsetting("grancerimoniere","0") != "0") {
            systemmail(getsetting("grancerimoniere","0"),"`@Nuovo stalliere","`@".$session['user']['name']." `@è diventato stalliere dei Draghi per il Drago Verde!");
        }
        addnav("Avvicinati all'altare", "gildadrago.php");
    } elseif ($_GET['az'] == "accolito") {
        output("<big>`^`b`cPromozione`c`b`0`n</big>",true);
        output("Sei diventato uno scudiero di Draghi.`n");
        output("Avvicinati all'altare.`n");
        $session['user']['carriera']= 51;
        addnav("Avvicinati all'altare", "gildadrago.php");
    } elseif ($_GET['az'] == "chierico") {
        output("<big>`^`b`cPromozione`c`b`0`n</big>",true);
        output("Sei diventato un cavaliere di Draghi.`n");
        output("Avvicinati all'altare.`n");
        $session['user']['carriera']= 52;
        addnav("Avvicinati all'altare", "gildadrago.php");
    } elseif ($_GET['az'] == "sacerdote") {
        output("<big>`^`b`cPromozione`c`b`0`n</big>",true);
        output("Sei diventato un Mastro di Draghi.`n");
        output("Avvicinati all'altare.`n");
        $session['user']['carriera']= 53;
        addnav("Avvicinati all'altare", "gildadrago.php");
    } elseif ($_GET['az'] == "sommo") {
        output("<big>`^`b`cPromozione`c`b`0`n</big>",true);
        output("`%Sei diventato un Cancelliere di Draghi.`n");
        output("Avvicinati all'altare.`n");
        $session['user']['carriera'] = 55;
        addnav("Avvicinati all'altare", "gildadrago.php");
    } elseif ($dio == 3 AND !$_GET['az']) {
        addnav("Azioni");
        if ($carriera > 49 AND $carriera < 56) {
            addnav("Fai un dono", "gildadrago.php?az=dono");
            addnav("Prega", "gildadrago.php?az=prega");
        }
        if ($carriera > 50 AND $carriera < 56) {
            addnav("Celebra cerimonia", "gildadrago.php?az=cerimonia");
        }
        if ($carriera > 51 AND $carriera < 56) {
            addnav("Processione", "gildadrago.php?az=processione");
        }
        if ($carriera > 52 AND $carriera < 56) {
            addnav("Ritiro Spirituale", "gildadrago.php?az=ritiro");
        }
        if ($carriera == 54 OR $session['user']['superuser'] > 3) {
            addnav("Punisci", "gildadrago.php?az=punisci");
            //Excalibur: Mail di massa
            addnav("Mail globali");
            addnav("Invia Mail a TUTTI (Fedeli e Seguaci)","gildadrago.php?az=mail&op=1");
            addnav("Invia Mail a Fedeli del Drago","gildadrago.php?az=mail&op=2");
            addnav("Invia Mail a Cancellieri","gildadrago.php?az=mail&op=3");
            //Excalibur: fine Mail di massa
        }
        //Excalibur: Inserimento annuncio prossima messa
        if ($carriera == 54 OR $carriera == 55 OR $session['user']['superuser'] > 3) {
            addnav("Annuncio Messa","msgmesse.php");
        }
        //Excalibur: fine Inserimento annuncio prossima messa
        addnav("Richieste");
        if ($session['user']['punti_carriera'] > 4000 and $carriera == 50 AND $dk > 5) {
            addnav("Diventa Scudiero", "gildadrago.php?az=accolito");
        }
        if ($session['user']['punti_carriera'] > 10000 and $carriera == 51 AND $dk > 10) {
            addnav("Diventa Cavaliere", "gildadrago.php?az=chierico");
        }
        if ($session['user']['punti_carriera'] > 40000 and $carriera == 52 AND $dk > 15) {
            addnav("Diventa Mastro", "gildadrago.php?az=sacerdote");
        }
        if ($session['user']['punti_carriera'] > 100000 and $carriera == 53 AND $session['user']['reincarna']> 0) {
            addnav("Diventa Cancelliere", "gildadrago.php?az=sommo");
        }
        if ($carriera > 49 AND $carriera < 56) {
            addnav("Supplica", "gildadrago.php?az=supplica");
        }
        if ($carriera > 50 AND $carriera < 56) {
            addnav("Supplica Superiore", "gildadrago.php?az=supplicas");
        }
        if ($carriera > 51 AND $carriera < 56) {
            addnav("Benedizione Minore", "gildadrago.php?az=benedici");
        }
        if ($carriera > 52 AND $carriera < 56) {
            addnav("Benedizione Maggiore", "gildadrago.php?az=benedici_maggiore");
        }
        addnav("Info");
        if ($carriera > 49 AND $carriera < 56) {
            addnav("I più Devoti","gildadrago.php?az=devoti");
            addnav("Medita sulla tua fede","gildadrago.php?az=chiedi");
        }
        addnav("Puniti","gildadrago.php?az=puniti");
        addnav("Altro");
        addnav("Sala del Dominatore", "gildadrago.php?az=gs");
        addnav("Direttive", "direttive.php");
        addnav("Torna al Villaggio", "village.php");
        output("`2Entri nella grande gilda dei `6Draghi`2, un odore acre di zolfo si diffonde nell'ambiente, alcuni fedeli
    pregano vicino all'altare. Un religioso silenzio viene tranquillamente rispettato da tutti gli avventori.`n");
    } elseif ($_GET['az'] == "prega") {
        if ($session['user']['turns'] < 1) {

            output("`5Sei troppo esausto per metterti a pregare in onore dei Draghi.`n");
            addnav("Torna all'altare", "gildadrago.php");
        } else {
            output("`3Ti inginocchi e inizi a pregare. Senti la tua fede aumentare e ti raccogli maggiormente, invocando il `@Drago Verde`n");
            $session['user']['punti_carriera'] += (2 + $caso);
            $session['user']['punti_generati'] += (2 + $caso);
            $fama = (2 + $caso)*$session[user][fama_mod];
            $session['user']['fama3mesi'] += $fama;
            debuglog("Guadagna ".(2+$caso)." punti carriera e $fama punti fama dal Drago con la preghiera. Ora ha ".$session['user']['punti_carriera']." punti carriera e ".$session['user']['fama3mesi']." punti fama");
            if ($session['user']['superuser'] == 0){
                savesetting("puntidrago", getsetting("puntidrago",0)+(2 + $caso));
            }
            $session['user']['turns'] -= 1;
            $session['user']['experience']+=($session['user']['level']*4);
            addnav("Prega", "gildadrago.php?az=prega");
            addnav("`@Torna all'Altare", "gildadrago.php");
        }
    } elseif ($_GET['az'] == "dono") {
        output("`3Ti avvicini alla statua del `@Drago Verde`3. Alla sua base noti un contenitore in cui giacciono molti oggetti.`n");
        output("Sono le offerte fatte dai fedeli in onore del `@Drago Verde`3, pellegrini che si sono recati qui per manifestare ");
        output("la propria devozione.");
        output("Cosa vuoi offrire ?.`n");
        output("Pezzi d'oro, oppure 1 gemma.`n");
        addnav("5?`^Getta 500 Oro", "gildadrago.php?az=oro5");
        addnav("1?`^Getta 1000 Oro", "gildadrago.php?az=oro1");
        addnav("0?`^Getta 5000 Oro", "gildadrago.php?az=oro50");
        addnav("O?`^Getta 10000 Oro", "gildadrago.php?az=oro10");
        addnav("`&Getta 1 Gemma", "gildadrago.php?az=gemme");
        addnav("`@Torna all'Altare", "gildadrago.php");
    } elseif ($_GET['az'] == "oro5") {
        if ($session['user']['gold'] < 500) {
            output("Non hai abbastanza oro.`n");
            addnav("`@Torna all'Altare", "gildadrago.php");
        } else {
            output("Getti felice i 500 Pezzi d'Oro in onore del Drago Verde nel contenitore ... senti immediatamente una folata ");
            output("di energia percorrere la tua anima. La tua fede è aumentata !`n");
            addnav("5?`^Getta 500 Oro", "gildadrago.php?az=oro5");
            addnav("`@Torna all'Altare", "gildadrago.php");
            $session['user']['punti_carriera'] += (20 + $caso);
            $session['user']['punti_generati'] += (20 + $caso);
            $fama = (20 + $caso)*$session[user][fama_mod];
            $session['user']['fama3mesi'] += $fama;
            debuglog("Guadagna ".(20+$caso)." punti carriera e $fama punti fama dal Drago donando 500 oro. Ora ha ".$session['user']['punti_carriera']." punti carriera e ".$session['user']['fama3mesi']." punti fama");
            if ($session['user']['superuser'] == 0){
                savesetting("puntidrago", getsetting("puntidrago",0)+(20 + $caso));
            }
            $session['user']['gold'] -= 500;
        }
    } elseif ($_GET['az'] == "oro1") {
        if ($session['user']['gold'] < 1000) {
            output("Non hai abbastanza oro.`n");
            addnav("`@Torna all'Altare", "gildadrago.php");
        } else {
            output("Getti felice i 1.000 Pezzi d'Oro in onore del Drago Verde nel contenitore ... senti immediatamente una folata ");
            output("di energia percorrere la tua anima. La tua fede è aumentata !`n");
            addnav("1?`^Getta 1000 Oro", "gildadrago.php?az=oro1");
            addnav("`@Torna all'Altare", "gildadrago.php");
            $session['user']['punti_carriera'] += (42 + $caso);
            $session['user']['punti_generati'] += (42 + $caso);
            $fama = (42 + $caso)*$session[user][fama_mod];
            $session['user']['fama3mesi'] += $fama;
            debuglog("Guadagna ".(42+$caso)." punti carriera e $fama punti fama dal Drago donando 1000 oro. Ora ha ".$session['user']['punti_carriera']." punti carriera e ".$session['user']['fama3mesi']." punti fama");
            if ($session['user']['superuser'] == 0){
                savesetting("puntidrago", getsetting("puntidrago",0)+(42 + $caso));
            }
            $session['user']['gold'] -= 1000;
        }
    } elseif ($_GET['az'] == "oro50") {
        if ($session['user']['gold'] < 5000) {
            output("Non hai abbastanza oro.`n");
            addnav("`@Torna all'Altare", "gildadrago.php");
        } else {
            output("Getti felice i 5.000 Pezzi d'Oro in onore del Drago Verde nel contenitore ... senti immediatamente una folata ");
            output("di energia percorrere la tua anima. La tua fede è aumentata !`n");
            addnav("0?`^Getta 5000 Oro", "gildadrago.php?az=oro50");
            addnav("`@Torna all'Altare", "gildadrago.php");
            $session['user']['punti_carriera'] += (215 + $caso);
            $session['user']['punti_generati'] += (215 + $caso);
            $fama = (215 + $caso)*$session[user][fama_mod];
            $session['user']['fama3mesi'] += $fama;
            debuglog("Guadagna ".(215+$caso)." punti carriera e $fama punti fama dal Drago donando 5000 oro. Ora ha ".$session['user']['punti_carriera']." punti carriera e ".$session['user']['fama3mesi']." punti fama");
            if ($session['user']['superuser'] == 0){
                savesetting("puntidrago", getsetting("puntidrago",0)+(215 + $caso));
            }
            $session['user']['gold'] -= 5000;
        }
    } elseif ($_GET['az'] == "oro10") {
        if ($session['user']['gold'] < 10000) {
            output("Non hai abbastanza oro.`n");
            addnav("`@Torna all'Altare", "gildadrago.php");
        } else {
            output("Getti felice i 10.000 Pezzi d'Oro in onore del Drago Verde nel contenitore ... senti immediatamente una folata ");
            output("di energia percorrere la tua anima. La tua fede è aumentata !`n");
            addnav("O?`^Getta 10000 Oro", "gildadrago.php?az=oro10");
            addnav("`@Torna all'Altare", "gildadrago.php");
            $session['user']['punti_carriera'] += (440 + $caso);
            $session['user']['punti_generati'] += (440 + $caso);
            $fama = (440 + $caso)*$session[user][fama_mod];
            $session['user']['fama3mesi'] += $fama;
            debuglog("Guadagna ".(440+$caso)." punti carriera e $fama punti fama dal Drago donando 10000 oro. Ora ha ".$session['user']['punti_carriera']." punti carriera e ".$session['user']['fama3mesi']." punti fama");
            if ($session['user']['superuser'] == 0){
                savesetting("puntidrago", getsetting("puntidrago",0)+(440 + $caso));
            }
            $session['user']['gold'] -= 10000;
        }
    } elseif ($_GET['az'] == "gemme") {
        if ($session['user']['gems'] < 1) {
            output("`4Non possiedi nessuna gemma.`n");
            addnav("`@Torna all'Altare", "gildadrago.php");
        } else {
            output("`#Getti felice 1 gemma in onore del `@Drago Verde `#nel contenitore ... senti immediatamente una folata ");
            output("di energia percorrere la tua anima. La tua fede è aumentata !`n");
            addnav("`&Getta 1 Gemma", "gildadrago.php?az=gemme");
            addnav("Torna all'Altare", "gildadrago.php");
            $session['user']['punti_carriera'] += (100 + $caso);
            $session['user']['punti_generati'] += (100 + $caso);
            $fama = (100 + $caso)*$session[user][fama_mod];
            $session['user']['fama3mesi'] += $fama;
            debuglog("Guadagna ".(100+$caso)." punti carriera e $fama punti fama dal Drago donando una gemma. Ora ha ".$session['user']['punti_carriera']." punti carriera e ".$session['user']['fama3mesi']." punti fama");
            if ($session['user']['superuser'] == 0){
                savesetting("puntidrago", getsetting("puntidrago",0)+(100 + $caso));
            }
            $session['user']['gems'] -= 1;
            if ($cento > 89) {
                $buff = array("name" => "`\$Tocco del Drago", "rounds" => 15, "wearoff" => "`!La tua Forza Divina scompare e torni normale", "defmod" => 1.6, "roundmsg" => "Senti il Drago Verde al tuo fianco!", "activate" => "defense");
                $session['bufflist']['magicweak'] = $buff;
                debuglog("Guadagna anche il Tocco del Drago donando la gemma");
                output("`%Un leggera aura luminosa ti circonda. Senti il grande potere del `@Drago Verde`%entrare in te!`n");
            }
        }
    } elseif ($_GET['az'] == "supplica") {
        if ($session['user']['punti_carriera'] < 50 OR $session['user']['turns'] < 1) {
            output("`#In ginocchio con le mani protese davanti a te inizi a supplicare il grande Drago .`n");
            output("Vieni attraversato da un brivido freddo e percepisci un tremore percorrere la tua anima.`n");
            output("La tua fede non soddisfa il Drago.`n");
            addnav("Torna all'altare", "gildadrago.php");
        } else {
            output("`2In ginocchio, con le mani protese davanti a te, inizi a supplicare il grande `@Drago Verde`2.`n");
            output("Immediatamente un calore si diffonde nel tuo corpo ed entri in comunione con `@Drago Verde`2 !!");
            addnav("Supplica", "gildadrago.php?az=supplica");
            addnav("Torna all'altare", "gildadrago.php");
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
                $buff = array("name" => "`\$Tocco del Drago Maggiore", "rounds" => 40, "wearoff" => "`!La tua Forza Divina scompare e torni normale", "defmod" => 1.6, "roundmsg" => "Senti il Drago al tuo fianco!", "activate" => "defense");
                $session['bufflist']['magicweak'] = $buff;
                output("`\$Un leggera aura luminosa ti circonda. Senti l'immenso potere del `@Drago Verde `\$entrare in te!`n");
                debuglog("guadagna Tocco del Drago Maggiore alla gilda con la supplica");
            } elseif ($cento <= 89 and $cento > 79) {
                output("Il tempo oggi scorrerà più lento potrai combattere altre $gemme creature.`n");
                $session['user']['turns'] += $gemme;
                debuglog("guadagna $gemme FF alla gilda con la supplica");
            } elseif ($cento <= 79 and $cento > 69) {
                output("$gemme gemme compare davanti a te.`n");
                debuglog("guadagna $gemme gemma alla gilda con la supplica");
                $session['user']['gems'] += $gemme;
            } elseif ($cento <= 69 and $cento > 49) {
                output("Un mucchietto di $caso10 pezzi d'oro compare davanti a te.`n");
                debuglog("guadagna $caso10 oro alla gilda con la supplica");
                $session['user']['gold'] += $caso10;
            } elseif ($cento <= 49 and $cento > 0) {
                output("Un mucchietto di $caso5 pezzi d'oro compare davanti a te.`n");
                debuglog("guadagna $caso5 oro alla gilda con la supplica");
                $session['user']['gold'] += $caso5;
                $buff = array("name" => "`\$Tocco del Drago", "rounds" => 25, "wearoff" => "`!La tua Forza Divina scompare e torni normale", "defmod" => 1.2, "roundmsg" => "Senti il Drago al tuo fianco!", "activate" => "defense");
                $session['bufflist']['magicweak'] = $buff;
                output("`%Un leggera aura luminosa ti circonda. Senti il grande potere del `@Drago Verde`% entrare in te!`n");
                debuglog("guadagna Tocco del Drago alla chiesa con la supplica");
            }
        }
    } elseif ($_GET['az'] == "supplicas") {
        if ($session['user']['punti_carriera'] < 150 OR $session['user']['turns'] < 2) {
            output("`&In ginocchio con le mani protese davanti a te inizi a supplicare il grande `@Drago Verde`&.`n");
            output("Vieni attraversato da un brivido freddo e senti la tua anima tremare.`n");
            output("La tua fede non soddisfa il `@Drago Verde`&.`n");
            addnav("`@Torna all'Altare", "gildadrago.php");
        } else {
            output("`&In ginocchio con le mani protese davanti a te inizi a supplicare il grande `@Drago Verde`&.`n");
            addnav("Supplica Superiore", "gildadrago.php?az=supplicas");
            addnav("Torna all'altare", "gildadrago.php");
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
                //crea oggetto 103
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
                $nome=$rowno['nome']." del Drago Verde";
                $desc=$rowno['nome']." forgiata dal fuoco del Drago Verde per ".$session[user][login];
                $resultno = db_query($sqlno) or die(db_error(LINK));
                $rowno = db_fetch_assoc($resultno);
                $sql="INSERT INTO oggetti (nome, descrizione, dove, dove_origine, livello, valore, potenziamenti,attack_help,defence_help,turns_help,hp_help,
                        usura, usuramax, usuraextra, usuramagica, usuramagicamax, usuramagicaextra)
                        VALUES ('{$nome}','{$desc}','103','1','$livello','$valore','$potn','$att','$dif','$turn','$vit',
                        '$durata', '$durata', '$usuraextra', '$duratamagica', '$duratamagica', '$usuraextramagica')";
                db_query($sql);
                //estrai oggetto 103
                $sql = "SELECT * FROM oggetti WHERE dove = 103 ORDER BY RAND() LIMIT 1";
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
                    $buff = array("name" => "`\$Tocco del Drago maggiore", "rounds" => 40, "wearoff" => "`!La tua Forza Divina scompare e torni normale", "defmod" => 1.4, "roundmsg" => "Senti il Drago al tuo fianco!", "activate" => "defense");
                    $session['bufflist']['magicweak'] = $buff;
                    output("`!La bontà del `@Drago Verde`! è grande, una potente aura luminosa ti circonda. `%Senti il potere del `@Drago Verde `%entrare in te!`n");
                    debuglog("Non riceve oggetto per zaino pieno e guadagna Tocco del Drago Maggiore alla gilda con la supplica superiore");
                }
            } elseif ($cento <= 97 and $cento > 89) {
                $buff = array("name" => "`\$Tocco del Drago Supremo", "rounds" => 85, "wearoff" => "`!La tua Forza Divina scompare e torni normale", "defmod" => 1.6, "roundmsg" => "Senti il Drago al tuo fianco!", "activate" => "defense");
                $session['bufflist']['magicweak'] = $buff;
                output("Un potente aura luminosa ti circonda. Senti l'incommensurabile potere del Drago entrare in te.`n");
                debuglog("guadagna Tocco del Drago Supremo alla gilda con la supplica superiore");
            } elseif ($cento <= 89 and $cento > 79) {
                output("Un mucchietto di $caso10 pezzi d'oro compare davanti a te.`n");
                debuglog("riceve $caso10 oro alla gilda con la supplica superiore");
                $session['user']['gold'] += $caso10;
            } elseif ($cento <= 79 and $cento > 69) {
                output("Un mucchietto di $gemme gemme compare davanti a te.`n");
                debuglog("riceve $gemme gemme alla gilda con la supplica superiore");
                $session['user']['gems'] += $gemme;
            } elseif ($cento <= 69 and $cento > 49) {
                output("Un mucchietto di $caso9 pezzi d'oro compare davanti a te.`n");
                debuglog("riceve $caso9 oro alla gilda con la supplica superiore");
                $session['user']['gold'] += $caso9;
            } elseif ($cento <= 49 and $cento > 0) {
                output("Un mucchietto di $caso8 pezzi d'oro compare davanti a te.`n");
                debuglog("riceve $caso8 oro alla gilda con la supplica superiore");
                $session['user']['gold'] += $caso8;
                $buff = array("name" => "`\$Tocco del Drago", "rounds" => 40, "wearoff" => "`!La tua Forza Divina scompare e torni normale", "defmod" => 1.2, "roundmsg" => "Senti il Drago al tuo fianco!", "activate" => "defense");
                $session['bufflist']['magicweak'] = $buff;
                output("`%Un leggera aura luminosa ti circonda. Senti il grande potere del `@Drago`% entrare in te!`n");
            }
        }
    } elseif ($_GET['az'] == "cerimonia") {
        if ($session['user']['turns'] < 5) {
            output("`7Sei troppo esausto per tenere una cerimonia in onore del `@Drago Verde`7.`n");
            addnav("`@Torna all'Altare", "gildadrago.php");
        } else {
            output("`3Celebri una cerimonia in onore del `@Drago Verde`3.`n");
            $session['user']['punti_carriera'] += (20 + $venti);
            $session['user']['punti_generati'] += (20 + $venti);
            $fama = (20 + $venti)*$session[user][fama_mod];
            $session['user']['fama3mesi'] += $fama;
            debuglog("Guadagna ".(20+$venti)." punti carriera e $fama punti fama dal Drago con una cerimonia. Ora ha ".$session['user']['punti_carriera']." punti carriera e ".$session['user']['fama3mesi']." punti fama");
            if ($session['user']['superuser'] == 0){
                savesetting("puntidrago", getsetting("puntidrago",0)+(20 + $caso));
            }
            $session['user']['turns'] -= 5;
            $session['user']['experience']+=($session['user']['level']*20);
            addnav("Cerimonia", "gildadrago.php?az=cerimonia");
            addnav("`@Torna all'Altare", "gildadrago.php");
        }
    } elseif ($_GET['az'] == "processione") {
        if ($session['user']['turns'] < 5 OR $session['user']['playerfights'] < 1) {
            output("`\$Sei troppo esausto per celebrare una processione in onore del `@Drago Verde`\$.`n");
            addnav("`@Torna all'Altare", "gildadrago.php");
        } else {
            output("`3Celebri una processione in onore del `@Drago Verde`3. Vi partecipano molti pellegrini e i vostri canti e `n");
            output("le vostre preghiere raggiungono il `@Grande Drago Verde`3. Senti la tua fede potenziata !!");
            $session['user']['punti_carriera'] += (40 + $venti);
            $session['user']['punti_generati'] += (40 + $venti);
            $fama = (40 + $venti)*$session['user']['fama_mod'];
            $session['user']['fama3mesi'] += $fama;
            debuglog("Guadagna ".(40+$venti)." punti carriera e $fama punti fama dal Drago con una processione. Ora ha ".$session['user']['punti_carriera']." punti carriera e ".$session['user']['fama3mesi']." punti fama");
            if ($session['user']['superuser'] == 0){
                savesetting("puntidrago", getsetting("puntidrago",0)+(40 + $caso));
            }
            $session['user']['turns'] -= 5;
            $session['user']['playerfights'] -= 1;
            $session['user']['experience']+=($session['user']['level']*30);
            addnav("Processione", "gildadrago.php?az=processione");
            addnav("Torna all'altare", "gildadrago.php");
        }
    } elseif ($_GET['az'] == "ritiro") {
        if ($session['user']['turns'] < 7 or $session['user']['playerfights'] < 1) {
            output("`3Sei troppo esausto per ritirarti e meditare in onore del `@Drago Verde`3.`n");
            addnav("Torna all'altare", "gildadrago.php");
        } else {
            output("`5Esegui un ritiro spirituale in onore del `@Drago Verde`5. Il priore del monastero ti fornisce una `n");
            output("cella dove ti puoi raccogliere in preghiera solitaria. Al termine senti che la tua fede è aumentata !!");
            $session['user']['punti_carriera'] += (80 + $venti);
            $session['user']['punti_generati'] += (80 + $venti);
            $fama = (80 + $venti)*$session['user']['fama_mod'];
            $session['user']['fama3mesi'] += $fama;
            debuglog("Guadagna ".(80+$venti)." punti carriera e $fama punti fama dal Drago con un ritiro. Ora ha ".$session['user']['punti_carriera']." punti carriera e ".$session['user']['fama3mesi']." punti fama");
            if ($session['user']['superuser'] == 0){
                savesetting("puntidrago", getsetting("puntidrago",0)+(80 + $caso));
            }
            $session['user']['turns'] -= 7;
            $session['user']['playerfights'] -= 1;
            $session['user']['experience']+=($session['user']['level']*40);
            addnav("Ritiro Spirituale", "gildadrago.php?az=ritiro");
            addnav("`@Torna all'Altare", "gildadrago.php");
        }
    } elseif ($_GET['az'] == "gs") {
        $session['user']['dove_sei'] = 3;
        $sqlpic = "SELECT acctid FROM accounts WHERE dove_sei=3";
        $resultpic = db_query($sqlpic);
        $player_inchiesa = db_num_rows($resultpic);
        output("`&Entri nella Sala delle Cerimonie del `3Dominatore di Draghi`&.`n`n");
        $GS = getsetting("grancerimoniere",0);
        $sql = "SELECT `name` FROM `accounts` WHERE `acctid` = $GS";
        $result = db_query($sql) or die(db_error(LINK));
        $row = db_fetch_assoc($result);
        if ($GS == 0) {
            output("`\$Nessuno raggiunge i requisiti richiesti dal `@Drago Verde `\$per occupare la carica di `3Dominatore di Draghi`\$.`n`n");
            output("`6La prossima messa potrà essere celebrata fra : `^`b$giorni_messa`b`6 giorni, `^`b$ore_messa`b`6 ore e `^`b$minuti_messa`b`6 minuti.`n`n");
            output("`3Sono presenti nella sala del Dominatore di Draghi : `#`b$player_inchiesa`b `3fedeli.`n`n");
            //} elseif (db_num_rows($result) > 1) {
            //    output("`$`b`iErrore segnalalo ad un admin, ci sono troppi Gran Sacerdote !!!`b`i`n");
        } else {
            output("`&L'attuale Dominatore di Draghi è : `b`@".$row['name']."`b`n`n");
            output("`6La prossima messa potrà essere celebrata fra : `^`b$giorni_messa`b`6 giorni, `^`b$ore_messa`b`6 ore e `^`b$minuti_messa`b`6 minuti.`n`n");
            output("`3Sono presenti nella sala del Dominatore di Draghi : `#`b$player_inchiesa`b `3fedeli.`n`n");
            if ($carriera == 54 OR $carriera == 55) {
                output("`3Tu potrai celebrare la prossima `#Messa`3 fra : `^`b$giorni_messa_player`b`3 giorni, `^`b$ore_messa_player`b`3 ore e `^`b$minuti_messa_player`b`3 minuti.`n`n");

            }
        }
        addnav("Azioni");
        if (($carriera == 54 OR $carriera == 55) AND $messa == 1 AND $messap==1 AND $session['user']['punti_carriera']>=5000) {
            addnav("`^Celebra `(Messa`&", "gildadrago.php?az=messaconferma");
        }
        if ($potere_messa == 1 AND $session['user']['messa'] == 0) {
            addnav("`&Partecipa alla Messa", "gildadrago.php?az=partecipa");
        }
        addnav("Exit");
        addnav("Sala delle Riunioni","salariunioni.php");
        addnav("`@Torna all'Altare", "gildadrago.php");
        addcommentary();
//        checkday();
        viewcommentary("Gilda del Drago", "invoca",30,25);
        //Excalibur: concorso indovina la frase
        if (getsetting("indovinello", "") == "sbloccato" AND ($session['user']['dragonkills'] > 0 OR $session['user']['reincarna'] > 0)){
            addnav("Grande Concorso delle Sette (Indovina la Frase)","gildadrago.php?az=tryguess");
        }
        if (getsetting("indovinello", "") == "chiuso" AND ($session['user']['dragonkills'] > 0 OR $session['user']['reincarna'] > 0)){
            addnav("Grande Concorso delle Sette (Ritira Premio)","gildadrago.php?az=lookguess");
        }
        if (getsetting("indovinello", "") == "bloccato" AND ($session['user']['dragonkills'] > 0 OR $session['user']['reincarna'] > 0)){
            addnav("Grande Concorso delle Sette (Standby)","gildadrago.php?az=lockguess");
        }
    } elseif ($_GET['az'] == "tryguess") {
        $session['user']['premioindovinello'] = 0;
        $sql = "SELECT area FROM custom WHERE area1 = 'frasedragonascosta'";
        $result = db_query ($sql) or die(db_error(LINK));
        $row = db_fetch_assoc($result);
        $frase1 = stripslashes($row['area']);
        output("`n`c`@Attualmente la situazione della frase da indovinare è la seguente:`n");
        output("`b`#<tt><font size='+1'>`n".$frase1."</tt></font>`b`c`n",true);
        if ($session['user']['superuser'] > 3) {
           $sql = "SELECT area FROM custom WHERE area1 = 'frasedrago'";
           $result = db_query ($sql) or die(db_error(LINK));
           $row = db_fetch_assoc($result);
           $frase = stripslashes($row['area']);
           output("`c`b`#<tt><font size='+1'>".$frase."</tt></font>`b`c`n",true);
        }
        $dataodierna = date("m-d");
        $dataguess = substr($session['user']['guess'], 5, 5);
        if ($dataguess != $dataodierna) {
           output("`@Non hai ancora effettuato il tentativo di indovinare la frase per oggi, vuoi provarci ?`n");
           addnav("Si, fammi provare","gildadrago.php?az=tryguess1");
           addnav("No, ci devo pensare","gildadrago.php?az=gs");
        }else{
           output("`@Hai già effettuato il tentativo odierno per indovinare la frase.`nTorna domani e potrai ");
           output("riprovarci.`n`n");
           addnav("`&Sala del Dominatore di Draghi", "gildadrago.php?az=gs");
        }
    } elseif ($_GET['az'] == "tryguess1") {
        $sql = "SELECT area FROM custom WHERE area1 = 'frasedragonascosta'";
        $result = db_query ($sql) or die(db_error(LINK));
        $row = db_fetch_assoc($result);
        $frase1 = stripslashes($row['area']);
        output("`n`c`@Attualmente la situazione della frase da indovinare è la seguente:`n");
        output("`b`#`n<tt><font size='+1'>".$frase1."</tt></font>`b`c`n",true);
        if ($session['user']['superuser'] > 3) {
           $sql = "SELECT area FROM custom WHERE area1 = 'frasedrago'";
           $result = db_query ($sql) or die(db_error(LINK));
           $row = db_fetch_assoc($result);
           $frase = stripslashes($row['area']);
           output("`c`b`#`n<tt><font size='+1'>".$frase."</tt></font>`b`c`n",true);
        }
        output("`@Scrivi la frase che pensi sia corretta.`n");
        output("<form action='gildadrago.php?az=tryguess2' method='POST'><input name='try' value=''><input type='submit' class='button' cols='80' value='Frase'>`n",true);
        addnav("","gildadrago.php?az=tryguess2");
        addnav("Ci ho ripensato","gildadrago.php?az=gs");
    } elseif ($_GET['az'] == "tryguess2") {
        $tryfrase = stripslashes($_POST['try']);
        $sql = "SELECT area FROM custom WHERE area1 = 'frasedrago'";
        $result = db_query ($sql) or die(db_error(LINK));
        $row = db_fetch_assoc($result);
        $frase = stripslashes($row['area']);
        $sql = "SELECT area FROM custom WHERE area1 = 'frasedragonascosta'";
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
           addnav("`&Sala del Dominatore di Draghi", "gildadrago.php?az=gs");
           savesetting("tentadrago",(getsetting("tentadrago",0)+1));
        } else {
           savesetting("tentadrago",(getsetting("tentadrago",0)+1));
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
           if ($minortry['try'] > getsetting("tentadrago",0)){
               $minortry['try'] = getsetting("tentadrago",0);
               $minortry['name'] = "Drago Verde";
               $minortry = serialize($minortry);
               savesetting("minortry",$minortry);
           }
           savesetting("indovinello","chiuso");
           savesetting("settaindovinello","drago"); //(premio anche alla setta)
//         savesetting("settaindovinello","tutti"); //(premio a tutti tranne gli agnostici)
           savesetting("solutoreindovinello",$session['user']['name']);
           debuglog("ha indovinato la frase misteriosa dell'indovinello del Grande Concorso");
           debuglog("riceve $premio gemme come premio per l'indovinello del Grande Concorso");
           $session['user']['gems'] += ($premio * 2);
           $session['user']['premioindovinello'] = 1;
           addnav("`&Sala del Dominatore di Draghi", "gildadrago.php?az=gs");
        }
    } elseif ($_GET['az'] == "lookguess") {
        $settaindovinello = getsetting("settaindovinello","nessuna");
        if ($settaindovinello != "drago" AND $settaindovinello != "tutti") {
            output("`\$Purtroppo il `&Grande Concorso `iIndovina la Frase`i`\$ è stato vinto dalla setta `&$settaindovinello`n");
            output("Dovrete impegnarmi maggiormente nel prossimo concorso per aggiudicarvelo.`n");
            output("La frase misteriosa era:`n`n");
            $sql = "SELECT area FROM custom WHERE area1 = 'frasedrago'";
            $result = db_query ($sql) or die(db_error(LINK));
            $row = db_fetch_assoc($result);
            $frase = stripslashes($row['area']);
            output("`n`c`b`#<tt><font size='+1'>".$frase."</tt></font>`b`n",true);
            $sql = "SELECT area FROM custom WHERE area1 = 'frasedragonascosta'";
            $result = db_query ($sql) or die(db_error(LINK));
            $row = db_fetch_assoc($result);
            $frase1 = stripslashes($row['area']);
            output("`b`#<tt><font size='+1'>".$frase1."</tt></font>`b`c`n",true);
            addnav("`&Sala del Dominatore di Draghi", "gildadrago.php?az=gs");
        } else {
            output("`c`b<font size='+1'>`!C`@O`#N`5G`3R`^A`4T`1U`2L`2A`4Z`5I`6O`5N`7I`3!</font>`c`b`n`n",true);
            output("`^La tua setta si è aggiudicata il `&Grande Concorso `iIndovina la Frase`i`^.`n");
            output("La frase è stata indovinata da `(".getsetting("solutoreindovinello","sconosciuto")."`^, e la frase era:`n");
            $sql = "SELECT area FROM custom WHERE area1 = 'frasedrago'";
            $result = db_query ($sql) or die(db_error(LINK));
            $row = db_fetch_assoc($result);
            $frase = stripslashes($row['area']);
            output("`n`c`b`#<tt><font size='+1'>".$frase."</tt></font>`b`c`n",true);
            if ($session['user']['premioindovinello'] == 1) {
               output("`^Hai già ritirato il premio che ti spettava, ora puoi solo aspettare che venga indetto un ");
               output("nuovo concorso, non puoi fare nient'altro qui.`n");
               addnav("`&Sala del Dominatore di Draghi", "gildadrago.php?az=gs");
            } else {
               $premio = getsetting("premioconcorso",5);
               $session['user']['premioindovinello'] = 1;
               output("`^Ora ti verrà consegnato il premio, cioè `@$premio Gemme`^, fanne buon uso !!`n");
               $session['user']['gems'] += $premio;
               debuglog("riceve $premio gemme come premio per l'indovinello del Grande Concorso");
               addnav("`&Sala del Dominatore di Draghi", "gildadrago.php?az=gs");
            }
        }
    } elseif ($_GET['az'] == "lockguess") {
        output("`@Il `&Grande Concorso `iIndovina la Frase`i`@ è attualmente bloccato.`nProbabilmente gli Admin ");
        output("stanno pensando a qualche frase particolarmente complicata da farti indovinare.`n");
        output("Controlla più tardi se viene abilitata l'opzione per indovinare la frase.`n`n");
        addnav("`&Sala del Dominatore di Draghi", "gildadrago.php?az=gs");
        //Excalibur: fine concorso indovina la frase
    } elseif ($_GET['az'] == "messaconferma") {
        output("`n`7Attenzione, stai per celebrare la messa in onore del `@Drago Verde`7. Sei sicuro di volerlo fare?`n`n");
        output("<table border=0 cellpadding=2 cellspacing=1 align=center>",true);
                output("<tr class='trlight'><td><a href=gildadrago.php?az=messa>`bCelebra la messa`b</a></td></tr>", true);
        output("</table>",true);
                addnav("","gildadrago.php?az=messa");
                addnav("`&Sala del Dominatore di Draghi", "gildadrago.php?az=gs");
    } elseif ($_GET['az'] == "messa") {
        if ($messa == 1) {
            output("`3Dopo aver raccolto una massa di fedeli, inizi a cantilenare le tue preghiere. Celebri la messa in ");
            output("onore del `@Drago Verde`3.`nLa funzione consuma `bmolti`b dei tuoi punti carriera.");
            $session['user']['punti_carriera']-=5000;
            addnav("Sala del Dominatore di Draghi", "gildadrago.php?az=gs");
            savesetting("tempogilda", $data_messa);
            $sql = "UPDATE accounts SET messa = 0 WHERE dio = 3";
            db_query($sql);
            $session['user']['messa'] = 0;
            $sql = "UPDATE messa SET data = '".$data_messa."'WHERE acctid = '".$session['user']['acctid']."'";
            db_query($sql);
            $sql = "SELECT acctid FROM accounts WHERE dove_sei=3";
            $result = db_query($sql);
            $player_inchiesa = db_num_rows($result);
            if ($player_inchiesa <=2) $session['user']['punti_carriera']=-100;
            savesetting("player_ingilda", $player_inchiesa);
            // Maximus modifica messa, salvo i presenti per dare la possibilità
            // a tutti i partecipanti di ricevere la stessa percentuale dei premi
            savesetting("partecipanti_gilda", $player_inchiesa);
            debuglog("celebra la messa in onore del Drago Verde con $player_inchiesa partecipanti");
            // Fine
            //Sook, calcolo base del premio (nuovo sistema)
            //calcolo valori dei presenti
            $sqlbase = "SELECT COUNT(acctid) AS presenti, SUM(punti_carriera) AS punti_presenti, SUM(dragonkills) AS dk_presenti, SUM(reincarna) AS reinc_presenti FROM accounts WHERE dove_sei=3 AND (dragonkills>2 OR reincarna>0) AND superuser=0 GROUP BY dio";
            $resultbase = db_query($sqlbase);
            $rowbase = db_fetch_assoc($resultbase);
            $player_inchiesa = $rowbase['presenti'];
            $punti_carriera_presenti = $rowbase['punti_presenti'];
            $dk_presenti = $rowbase['dk_presenti'] + 30 * $rowbase['reinc_presenti'];
            $base1 = $punti_carriera_presenti/$dk_presenti/$player_inchiesa;
            //calcolo valori totali dei fedeli
            $sqlbp = "SELECT COUNT(acctid) AS fedeli, SUM(punti_carriera) AS punti_totali, SUM(dragonkills) AS dk_totali, SUM(reincarna) AS reinc_totali FROM accounts WHERE dio=3 AND (dragonkills>2 OR reincarna>0) AND superuser=0 GROUP BY dio";
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
            savesetting("baseDrago",$base);
            //fine calcolo base
        } else {
            output("`^ERRORE: la messa è stata già celebrata da un altro personaggio, dovrai aspettare prima di celebrarne un'altra");
            addnav("`&Sala del Dominatore di Draghi", "gildadrago.php?az=gs");
        }
    } elseif ($_GET[az] == "partecipa") {
        // Maximus display dei partecipanti, tanto per far vedere quanti sono...
        $partecipanti_messa = getsetting("partecipanti_gilda", 0);
        //output("`#Ti unisci al canto in onore del `@Drago Verde`3`#, tutti i presenti sono concentrati, l'aria inizia scintillare,
        output("`3Insieme ad altri `6{$partecipanti_messa} `3fedeli, ti unisci al canto in onore del `@Drago Verde`3, tutti i presenti sono concentrati, l'aria inizia scintillare,
    l'immenso potere del `@Drago Verde`3 scende sui fedeli presenti.`n`n");
        $session['user']['messa'] = 1;
        if ($session['user']['punti_carriera'] != -100){
            output("`3Nella mente ti si focalizzano questi elementi, il `@Drago Verde`3 attende che tu decida.`n");
            output("`^Oro.`n");
            output("`&Vita.`n");
            output("`#Abilità.`n");
            addnav("`^Oro", "gildadrago.php?az=partecipa_oro");
            addnav("`&Vita", "gildadrago.php?az=partecipa_vita");
            if ($carriera != 0) {
                addnav("`#Abilità", "gildadrago.php?az=partecipa_abilita");
            }
        }else{
            output("`\$La tua avidità non piace al `@Drago Verde`\$. `nVuoi tenere solo per te i doni che il `@Drago Verde`\$ così ");
            output("generosamente mette a disposizione di `b`@TUTTI`b`\$ i suoi fedeli ? `nAdesso ne pagherai le conseguenze ");
            output("stolto mortale !!!`n`n");
            $session['user']['punti_carriera']=200;
            $carriera=50;
            $gemloss=round($session['user']['gems']/2);
            output("`2Una palla di  `b`i`&F `\$U `&O `\$C `&I`b`i`2 cade dal cielo e ti colpisce in pieno petto !!!`n`\$Sei morto !!!`n");
            output("`#Perdi tutto l'oro che avevi con te, e il `@Drago Verde`# punisce la tua avidità togliendoti il 20% della tua esperienza, ");
            output("la metà delle gemme che possedevi, `bTUTTI`b i tuoi punti carriera, e ti retrocede a semplice `iStalliere`i.");
            $session['user']['alive']=false;
            debuglog("ha voluto fare il furbo e perde oro, gemme, 20% exp e resta con 200 PuntiCarriera !!!");
            addnews("`\$".$session['user']['name']." `&ha voluto approfittare della generosità del `@Drago Verde`&, celebrando la messa per se stesso,
        e non volendo condividere i doni del magnanimo `@Drago Verde`& con gli altri fedeli. È stato punito duramente per la sua avidità !!");
            $session['user']['hitpoints']=0;
            $session['user']['messa'] = 1;
            $session['user']['experience']*=0.8;
            $session['user']['gold']=0;
            $session['user']['gems']-=$gemloss;
            addnav("Terra delle Ombre","shades.php");
        }
    } elseif ($_GET['az'] == "partecipa_oro") {
/*        // Maximus modifica messa
        $partecipanti_messa = getsetting("partecipanti_gilda", 0);
        $session['user']['suppliche']=0;
        $oro_messa = (($partecipanti_messa / 10) * $partecipanti_messa * 50);
        if ($oro_messa < 5000)$oro_messa=5000;
        if ($oro_messa > 50000)$oro_messa=50000;
        if ($carriera==54) $oro_messa *= 1.5;
        output("`^Davanti a te si materializzano `b$oro_messa`b Pezzi d'Oro. La messa celebrata in onore del `@Drago Verde`^ ");
        output("ha raggiunto il `@Possente`^, che dall'alto della sua benevolenza ha accolto le tue implorazioni. `n");
        addnav("`&Sala del Dominatore di Draghi", "gildadrago.php?az=gs");
        $session['user']['gold'] += $oro_messa;
        debuglog("riceve $oro_messa oro alla gilda per aver partecipato alla messa");
        // Fine*/

        // Vecchio sistema
        /*
        $session['user']['suppliche']=0;
        $oro_messa = (($player_inchiesa * $caso) * $player_inchiesa * $player_inchiesa * $player_inchiesa);
        if ($oro_messa > 20000)$oro_messa=20000;
        if ($carriera==54 OR $carriera==55) $oro_messa *= 1.5;
        output("`^Davanti a te si materializzano `b$oro_messa`b Pezzi d'Oro. La messa celebrata in onoredel `@Drago Verde`3`^ ");
        output("ha raggiunto il `6Sommo`^, che dall'alto della sua benevolenza ha accolto le tue implorazioni. `n");
        addnav("`&Sala del Gran Sacerdote", "gildadrago.php?az=gs");
        $session['user']['gold'] += $oro_messa;
        debuglog("riceve $oro_messa oro alla chiesa per aver partecipato alla messa");
        */

        //Nuovo sistema (Sook)
        //calcolo coefficiente bonus carriera
        switch($session['uesr']['carriera']) {
            case 51:
            case 7:
            case 43:
                $grado = 1.2;
            break;
            case 52:
                $grado = 1.4;
            break;
            case 53:
                $grado = 1.6;
            break;
            case 54:
                $grado = 1.8;
            break;
            case 55:
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
        $partecipanti_messa = getsetting("partecipanti_gilda", 0);
        $base=getsetting("baseDrago",0)+($session['user']['punti_generati']/1000);
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
        output("`^Davanti a te si materializzano `b$oro_messa`b Pezzi d'Oro. La messa celebrata in onore del `@Drago Verde`^ ");
        output("ha raggiunto il `@Possente`^, che dall'alto della sua benevolenza ha accolto le tue implorazioni. `n");
        addnav("`&Sala del Dominatore di Draghi", "gildadrago.php?az=gs");
        $session['user']['gold'] += $oro_messa;
        debuglog("riceve $oro_messa oro alla gilda per aver partecipato alla messa");
        // Fine
    } elseif ($_GET['az'] == "partecipa_vita") {
/*        // Maximus modifica messa
        $partecipanti_messa = getsetting("partecipanti_gilda", 0);
        $session['user']['suppliche']=0;
        $vita_messa = round(($partecipanti_messa / 10)*2.5);
        if ($vita_messa < 2) $vita_messa = 2;
        if ($vita_messa > 25) $vita_messa = 25;
        if ($carriera==54) $vita_messa *= 1.2;
        output("`&Senti la tua vitalità crescere. La messa celebrata in onore del `Drago Verde`& ");
        output("ha raggiunto il `@Possente`&, che dall'alto della sua benevolenza ha accolto le tue implorazioni. `n");
        addnav("`&Sala del Dominatore di Draghi", "gildadrago.php?az=gs");
        $session['user']['maxhitpoints'] += intval($vita_messa);
        debuglog("riceve $vita_messa HP permanenti alla gilda per aver partecipato alla messa");
        // Fine*/

        // Vecchio sistema
        /*
        $session['user']['suppliche']=0;
        $vita_messa = round($player_inchiesa * $caso / 2);
        if ($vita_messa < 10) $vita_messa = 10;
        if ($carriera==54 OR $carriera==55) $vita_messa *= 1.2;
        output("ha raggiunto il `6Sommo`&, che dall'alto della sua benevolenza ha accolto le tue implorazioni. `n");
        addnav("`&Sala del Gran Sacerdote", "gildadrago.php?az=gs");
        $session['user']['maxhitpoints'] += $vita_messa;
        debuglog("riceve $vita_messa HP permanenti alla chiesa per aver partecipato alla messa");
        */
        //Nuovo sistema (Sook)
        $partecipanti_messa = getsetting("partecipanti_gilda", 0);
        $base=getsetting("baseDrago",0)+$session['user']['punti_generati']/1000;
        $session['user']['punti_generati']=0;
        $session['user']['suppliche']=0;
        $vita_messa = ($base/3) + intval($session['user']['dragonkills']/5) + $session['user']['reincarna']*6;
        $b1 = e_rand(-1, 1);
        $b2 = e_rand(-1, 1);
        $bonus = (4+$b1+$b2)/4;
        $vita_messa = round($vita_messa*$bonus);
        if ($vita_messa < 5) $vita_messa = 5;
        if ($vita_messa > 35) $vita_messa = 35;
        output("`&Senti la tua vitalità crescere. La messa celebrata in onore del `@Drago Verde`& ");
        output("ha raggiunto il `@Possente`&, che dall'alto della sua benevolenza ha accolto le tue implorazioni. `n");
        addnav("`&Sala del Dominatore di Draghi", "gildadrago.php?az=gs");
        $session['user']['maxhitpoints'] += intval($vita_messa);
        debuglog("riceve $vita_messa HP permanenti alla gilda per aver partecipato alla messa");
        // Fine

    } elseif ($_GET['az'] == "partecipa_abilita") {
/*        // Maximus modifica messa
        $partecipanti_messa = getsetting("partecipanti_gilda", 0);
        $session['user']['suppliche']=0;
        $abilita_messa = intval($partecipanti_messa * $partecipanti_messa * 0.5);
        if ($abilita_messa < 500) $abilita_messa = 500;
        if ($abilita_messa > 5000) $abilita_messa = 5000;
        if ($carriera==54) $abilita_messa *= 1.5;
        if ($carriera > 49 AND $carriera < 56)  {
            output("`3Senti la tua fede crescere. La messa celebrata in onore del `@Drago Verde`3 ");
            output("ha raggiunto il `@Possente`3, che dall'alto della sua benevolenza ha accolto le tue implorazioni. `n");
        }
        if ($carriera > 4 AND $carriera < 9) {
            output("`3Senti la tua abilità di fabbro crescere. La messa celebrata in onore del `@Drago Verde`3 ");
            output("ha raggiunto il `@Possente`3, che dall'alto della sua benevolenza ha accolto le tue implorazioni. `n");
        }
        if ($carriera > 40 AND $carriera < 45) {
            output("`3Senti la tua abilità di mago crescere. La messa celebrata in onore del `@Drago Verde`3 ");
            output("ha raggiunto il `@Possente`3, che dall'alto della sua benevolenza ha accolto le tue implorazioni. `n");
        }
        addnav("Sala del Dominatore di Draghi", "gildadrago.php?az=gs");
        $session['user']['punti_carriera'] += $abilita_messa;
        $fama = $abilita_messa*$session[user][fama_mod];
        $session['user']['fama3mesi'] += $fama;
        debuglog("Guadagna $abilita_messa punti carriera e $fama punti fama alla gilda per aver partecipato alla messa dei Draghi. Ora ha ".$session['user']['punti_carriera']." punti carriera e ".$session['user']['fama3mesi']." punti fama");
        if ($session['user']['superuser'] == 0){
            savesetting("puntidrago", getsetting("puntidrago",0)+$abilita_messa);
        }
        // Fine*/

        // Vecchio sistema
        /*
        $session['user']['suppliche']=0;
        $abilita_messa = (($player_inchiesa * $caso) * $player_inchiesa * 1.5);
        if ($abilita_messa < 100) $abilita_messa = 100;
        if ($carriera==54 OR $carriera==55) $abilita_messa *= 1.5;
        if (($carriera > 0 AND $carriera < 5) OR $carriera == 9) {
            output("`#Senti la tua fede crescere. La messa celebrata in onore del `@Drago Verde`3`# ");
            output("ha raggiunto il `6Sommo`#, che dall'alto della sua benevolenza ha accolto le tue implorazioni. `n");
        }
        if ($carriera > 4 AND $carriera < 9) {
            output("`#Senti la tua abilità di fabbro crescere. La messa celebrata in onore del `@Drago Verde`3`# ");
            output("ha raggiunto il `6Sommo`#, che dall'alto della sua benevolenza ha accolto le tue implorazioni. `n");
        }
        addnav("Sala del Gran Sacerdote", "gildadrago.php?az=gs");
        $session['user']['punti_carriera'] += $abilita_messa;
        $fama = $abilita_messa*$session[user][fama_mod];
        $session['user']['fama3mesi'] += $fama;
        debuglog("Guadagna $fama punti fama dal Drago Verde. Ora ha ".$session['user']['fama3mesi']." punti");
        if ($session['user']['superuser'] == 0){
            savesetting("puntidrago", getsetting("puntidrago",0)+$abilita_messa);
        }
        debuglog("riceve $abilita_messa punti carriera alla chiesa per aver partecipato alla messa");
        */
        //Nuovo sistema (Sook)
        //calcolo coefficiente bonus carriera
        switch($session['uesr']['carriera']) {
            case 51:
            case 7:
            case 43:
                $grado = 1.2;
            break;
            case 52:
                $grado = 1.4;
            break;
            case 53:
                $grado = 1.6;
            break;
            case 54:
                $grado = 1.8;
            break;
            case 55:
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

        $partecipanti_messa = getsetting("partecipanti_gilda", 0);
        $base=getsetting("baseDrago",0)+$session['user']['punti_generati']/1000;
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
        if ($carriera > 49 AND $carriera < 56)  {
            output("`3Senti la tua fede crescere. La messa celebrata in onore del `@Drago Verde`3 ");
            output("ha raggiunto il `@Possente`3, che dall'alto della sua benevolenza ha accolto le tue implorazioni. `n");
        }
        if ($carriera > 4 AND $carriera < 9) {
            output("`3Senti la tua abilità di fabbro crescere. La messa celebrata in onore del `@Drago Verde`3 ");
            output("ha raggiunto il `@Possente`3, che dall'alto della sua benevolenza ha accolto le tue implorazioni. `n");
        }
        if ($carriera > 40 AND $carriera < 45) {
            output("`3Senti la tua abilità di mago crescere. La messa celebrata in onore del `@Drago Verde`3 ");
            output("ha raggiunto il `@Possente`3, che dall'alto della sua benevolenza ha accolto le tue implorazioni. `n");
        }
        addnav("Sala del Dominatore di Draghi", "gildadrago.php?az=gs");
        $session['user']['punti_carriera'] += $abilita_messa;
        $fama = $abilita_messa*$session[user][fama_mod];
        $session['user']['fama3mesi'] += $fama;
        debuglog("Guadagna $abilita_messa punti carriera e $fama punti fama alla gilda per aver partecipato alla messa dei Draghi. Ora ha ".$session['user']['punti_carriera']." punti carriera e ".$session['user']['fama3mesi']." punti fama");
        if ($session['user']['superuser'] == 0){
            savesetting("puntidrago", getsetting("puntidrago",0)+$abilita_messa);
        }
        // Fine

    } elseif ($_GET['az'] == "devoti") {
        output("`3Magicamente, in uno specchio incastonato d'oro il `@Drago Verde `3mostra i nomi dei suoi più devoti figli.`n`n");
        $sqlo = "SELECT * FROM accounts WHERE superuser = 0 AND punti_carriera >= 1 AND (carriera >=50 AND carriera <=55) ORDER BY punti_carriera DESC";
        $resulto = db_query($sqlo) or die(db_error(LINK));
        output("<table cellspacing=0 cellpadding=2 align='center'><tr><td>&nbsp;</td><td>`bNome`b</td><td>`bLivello`b</td></tr>", true);
        if (db_num_rows($resulto) == 0) {
            output("<tr><td colspan=4 align='center'>`&Non ci sono fedeli nel villaggio`0</td></tr>", true);
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
            if ($rowo[carriera] == 50) {
                $livello = 'Stalliere';
            } elseif ($rowo['carriera'] == 51) {
                $livello = 'Scudiero';
            } elseif ($rowo['carriera'] == 52) {
                $livello = 'Cavaliere';
            } elseif ($rowo['carriera'] == 53) {
                $livello = 'Mastro di Draghi';
            } elseif ($rowo['carriera'] == 54) {
                $livello = 'Dominatore di Draghi';
            }
            $carr = $rowo['carriera'];
            output("<td>" . ($i + 1) . ".</td><td>".$rowo['name']."</td><td>".$prof[$carr]."</td></tr>", true);
        }
        output("</table>", true);
        addnav("`@Torna all'Altare", "gildadrago.php");
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
            addnav("`&Benedici oggetto", "gildadrago.php?az=benedici_conferma");
        }else if ($rowo['potenziamenti'] == 0) {
            output("`5`nL'oggetto non ha potenziamenti residui con cui benedirlo.`n");
        }else if ($session['user']['punti_carriera'] < 3000) {
            output("`%`nNon hai Punti Carriera a sufficienza per eseguire una Benedizione Minore sull'oggetto.`n");
        }
        addnav("Vai all'altare", "gildadrago.php");
    } elseif ($_GET['az'] == "benedici_conferma") {
        output("`%Cosa vuoi migliorare?`n`n");
        $sqlo = "SELECT pregiato FROM oggetti WHERE id_oggetti = ".$session['user']['oggetto'];
        $resultoo = db_query($sqlo) or die(db_error(LINK));
        $rowo = db_fetch_assoc($resultoo);
        output("`\$Attacco`n");
        output("`@Vita`n");
        //output("`&Difesa`n");
        if ($rowo['pregiato']==false OR getsetting("blocco_valore",0)=="0") output("`^Valore`n");
        addnav("`\$Attacco", "gildadrago.php?az=benedici_attacco");
        addnav("`@Vita", "gildadrago.php?az=benedici_vita");
        //addnav("`&Difesa", "gildadrago.php?az=benedici_difesa");
        if ($rowo['pregiato']==false OR getsetting("blocco_valore",0)=="0") addnav("`^Valore", "gildadrago.php?az=benedici_valore");
        addnav("");
        addnav("Torna all'altare", "gildadrago.php");
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
        debuglog("ha benedetto l'oggetto (oggetto) migliorando di $caso l'attacco e $bonus gemme il suo valore");
        addnav("`@Vai all'Altare", "gildadrago.php");
        $session['user']['punti_carriera'] -= (1000 * $caso);
    } elseif ($_GET['az'] == "benedici_vita") {
        $oggetto = $session['user']['oggetto'];
        $vita = $caso * e_rand (5,10);
        $bonus = ($caso + e_rand(0, 1)) * mt_rand(8,12);
        $sqlu = "UPDATE oggetti SET hp_help=hp_help+$vita, potenziamenti=potenziamenti-1, valore=valore+$bonus WHERE id_oggetti='$oggetto'";
        db_query($sqlu) or die(db_error(LINK));
        //modifica per aggiornamento dell'usura
        $usuraextra =  $vita * 3 + $bonus * 5;
        $sqlusura = "SELECT usuramax FROM oggetti WHERE id_oggetti='$oggetto'";
        $resultus = db_query($sqlusura) or die(db_error(LINK));
        $rowus = db_fetch_assoc($resultus);
        if ($rowus[usuramax]>0) {
            $sqlu = "UPDATE oggetti SET usuramax=usuramax+$usuraextra, usuraextra=usuraextra+$vita WHERE id_oggetti='$oggetto'";
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
        debuglog("ha benedetto l'oggetto (oggetto) migliorando di $vita la forza vitale e $bonus gemme il suo valore");
        addnav("`@Vai all'Altare", "gildadrago.php");
        $session['user']['punti_carriera'] -= (1000 * $caso);
    }elseif ($_GET['az'] == "benedici_difesa") {
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
        debuglog("ha benedetto l'oggetto ($oggetto) migliorando di $caso la difesa e $bonus gemme il suo valore");
        addnav("`@Vai all'Altare", "gildadrago.php");
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
        debuglog("ha benedetto l'oggetto migliorando di $bonus il suo valore");
        addnav("`@Vai all'Altare", "gildadrago.php");
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
            addnav("`&Benedici oggetto", "gildadrago.php?az=benedici_maggiore_conferma");
        }else if ($rowo['potenziamenti'] == 0) {
            output("`5`nL'oggetto non ha potenziamenti residui con cui benedirlo.`n");
        }else if ($session['user']['punti_carriera'] < 9000) {
            output("`%`nNon hai Punti Carriera a sufficienza per eseguire una Benedizione Maggiore sull'oggetto.`n");
        }
        addnav("`@Vai all'Altare", "gildadrago.php");
    } elseif ($_GET['az'] == "benedici_maggiore_conferma") {
        output("`%Cosa vuoi migliorare?`n`n");
        $sqlo = "SELECT pregiato FROM oggetti WHERE id_oggetti = ".$session['user']['oggetto'];
        $resultoo = db_query($sqlo) or die(db_error(LINK));
        $rowo = db_fetch_assoc($resultoo);
        output("`\$Attacco`n");
        output("`@Vita`n");
        //output("`&Difesa`n");
        if ($rowo['pregiato']==false OR getsetting("blocco_valore",0)=="0")output("`^Valore`n");
        addnav("`\$Attacco", "gildadrago.php?az=benedici_maggiore_attacco");
        addnav("`@Vita", "gildadrago.php?az=benedici_maggiore_vita");
        //addnav("`&Difesa", "gildadrago.php?az=benedici_maggiore_difesa");
        if ($rowo['pregiato']==false OR getsetting("blocco_valore",0)=="0")addnav("`^Valore", "gildadrago.php?az=benedici_maggiore_valore");
        addnav("");
        addnav("Torna all'altare", "gildadrago.php");
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
        output ("La forza d'attacco del tuo oggetto è stata migliorata di `b$attacco`b punti.");
        output("`nEd il suo valore è aumentato di $bonus gemme`n");
        //modifica di Excalibur
        $session['user']['attack'] += $attacco;
        $session['user']['bonusattack'] += $attacco;
        //fine modifica
        debuglog("ha benedetto l'oggetto ($oggetto) migliorando di $attacco l'attacco e $bonus gemme il suo valore");
        addnav("`@Vai all'Altare", "gildadrago.php");
        $session['user']['punti_carriera'] -= (3000 * $caso);
    } elseif ($_GET['az'] == "benedici_maggiore_vita") {
        $oggetto = $session['user']['oggetto'];
        $vita = ($caso + 2) * 5;
        $bonus = ($caso + 2) * mt_rand(8,12);
        $sqlu = "UPDATE oggetti SET hp_help=hp_help+$vita, potenziamenti=potenziamenti-1, valore=valore+$bonus WHERE id_oggetti='$oggetto'";
        db_query($sqlu) or die(db_error(LINK));
        //modifica per aggiornamento dell'usura
        $usuraextra =  $vita * 3 + $bonus * 5;
        $sqlusura = "SELECT usuramax FROM oggetti WHERE id_oggetti='$oggetto'";
        $resultus = db_query($sqlusura) or die(db_error(LINK));
        $rowus = db_fetch_assoc($resultus);
        if ($rowus[usuramax]>0) {
            $sqlu = "UPDATE oggetti SET usuramax=usuramax+$usuraextra, usuraextra=usuraextra+$vita WHERE id_oggetti='$oggetto'";
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
        debuglog("ha benedetto l'oggetto ($oggetto) migliorando di $vita la forza vitale e $bonus gemme il suo valore");
        addnav("`@Vai all'Altare", "gildadrago.php");
        $session['user']['punti_carriera'] -= (3000 * $caso);
    } elseif ($_GET[az] == "benedici_maggiore_difesa") {
        $oggetto = $session['user']['oggetto'];
        $difesa = $caso + e_rand (2,4);
        $bonus = $difesa * (mt_rand(8,12) + 5);
        $sqlu = "UPDATE oggetti SET defence_help=defence_help+$difesa, potenziamenti=potenziamenti-1, valore=valore+$bonus WHERE id_oggetti='$oggetto'";
        db_query($sqlu) or die(db_error(LINK));
        output("`7Una luce `b`&bianca`7`b circonda il tuo oggetto.`n");
        output ("La forza protettrice che offre il tuo oggetto è stata migliorata di $difesa punti.");
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
        $session['user']['defence'] += $difesa;
        $session['user']['bonusdefence'] += $difesa;
        //fine modifica
        debuglog("ha benedetto l'oggetto ($oggetto) migliorando di $difesa la difesa e $bonus gemme il suo valore");
        addnav("`@Vai all'Altare", "gildadrago.php");

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
        output ("Il valore del tuo oggetto è stato aumentato di $bonus gemme.");
        debuglog("ha benedetto l'oggetto migliorando di $bonus gemme il suo valore");
        addnav("`@Vai all'Altare", "gildadrago.php");
        $session['user']['punti_carriera'] -= (3000 * $caso);
    }elseif ($dio != 3 AND $dio != 0) {
        addnav("Exit");
        addnav("`@Torna al Villaggio", "village.php");
        output("`\$Entri nella grande gilda del `@Drago Verde`n");
        output("`\$Due energumeni ti squadrano e ti chiedono di mostrare il segno di riconoscimento. Non sapendo quale sia ne inventi uno al momento, ma gli omaccioni ti sbattono fuori senza fare complimenti.`n");
    }elseif($_GET['az']=="chiedi"){
        addnav("`&Vai all'entrata", "gildadrago.php");
        if ($carriera == 50 ) {
            output("`3Mediti molto profondamente cercando di capire che considerazione ha il Drago Verde nei tuoi confronti`n e preghi:`#\"Grande Drago Verde sono un servo fedele dimmi quanto manca alla mia promozione ?\"`7.`n");
            $voto = intval(($session['user']['punti_carriera']/2000)*10);
            output("`#Una voce tonante esplode nella tua mente :`& \"In questo momento sei uno `$ Stalliere `&e la mia considerazione per te è pari a `$ $voto `&su 10 \"`7.`n `#Stai tremando come una foglia, pensi che non è una buona idea disturbare il Drago Verde per queste frivolezze.");
            $session['user']['punti_carriera']-=1;
        }
        if ($carriera == 51 ) {
            output("`3Mediti molto profondamente cercando di capire che considerazione ha il Drago Verde nei tuoi confronti`n e preghi:`#\"Grande Drago Verde sono un servo fedele dimmi quanto manca alla mia promozione ?\"`7.`n");
            $voto = intval(($session['user']['punti_carriera']/5000)*10);
            output("`#Una voce tonante esplode nella tua mente :`& \"In questo momento sei un `$ Scudiero `&e la mia considerazione per te è pari a `$ $voto `&su 10 \"`7.`n `#Stai tremando come una foglia, pensi che non è una buona idea disturbare il Drago Verde per queste frivolezze.");
            $session['user']['punti_carriera']-=1;
        }
        if ($carriera == 52 ) {
            output("`3Mediti molto profondamente cercando di capire che considerazione ha il Drago Verde nei tuoi confronti`n e preghi:`#\"Grande Drago Verde sono un servo fedele dimmi quanto manca alla mia promozione ?\"`7.`n");
            $voto = intval(($session['user']['punti_carriera']/20000)*10);
            output("`#Una voce tonante esplode nella tua mente :`& \"In questo momento sei un `$ Cavaliere `&e la mia considerazione per te è pari a `$ $voto `&su 10 \"`7.`n `#Stai tremando come una foglia, pensi che non è una buona idea disturbare il Drago Verde per queste frivolezze.");
            $session['user']['punti_carriera']-=1;
        }
        if ($carriera == 53 ) {
            output("`3Mediti molto profondamente cercando di capire che considerazione ha il Drago Verde nei tuoi confronti`n e preghi:`#\"Grande Drago Verde sono un servo fedele dimmi quanto manca alla mia promozione ?\"`7.`n");
            $voto = intval(($session['user']['punti_carriera']/100000)*10);
            output("`#Una voce tonante esplode nella tua mente :`& \"In questo momento sei un `$ Mastro `&e la mia considerazione per te è pari a `$ $voto `&su 10 \"`7.`n `#Stai tremando come una foglia, pensi che non è una buona idea disturbare il Drago Verde per queste frivolezze.");
            $session['user']['punti_carriera']-=1;
        }
        if ($carriera == 54 OR $carriera == 55) {
            if ($carriera == 54) {
                output("`3Mediti molto profondamente cercando di capire che considerazione ha il Drago Verde nei tuoi confronti`n e preghi:`#\"Grande Drago Verde sono un servo fedele dimmi quanto manca alla mia promozione ?\"`7.`n");
                output("`#Una voce tonante esplode nella tua mente :`& \"Ma tu sei il mio Dominatore di Draghi !!\"`7.`n `#Un fulmine ti colpisce, e pensi di essere uno stupido.");
                $session['user']['punti_carriera']-=10;
                if ($session['user']['hitpoints'] >= ($session['user']['maxhitpoints']/2)){
                   $session['user']['hitpoints']=intval($session['user']['hitpoints']/4);
                }
            }else{
                output("`3Mediti molto profondamente cercando di capire che considerazione ha il Grande Drago Verde nei tuoi confronti`n e preghi:`#\"Grande Drago Verde sono un servo fedele dimmi quanto manca alla mia promozione ?\"`7.`n");
                output("`#Una voce tonante esplode nella tua mente :`& \"In questo momento sei un `$ Cancelliere di Draghi `&e puoi solo diventare il mio Dominatore di Draghi, se sarai più devoto \"`7.`n `3Pensi che non è una buona idea disturbare il drago Verde per queste frivolezze.");
                $session['user']['punti_carriera']-=1;
            }
        }
    }
    //Excalibur: Mail di massa
    if ($_GET['az'] == "mail") {
         output("Testo da inviare.`n");
         output("<form action='gildadrago.php?az=mailto&op=".$_GET['op']."' method='POST'>",true);
         output("<textarea class='input' name='body' cols='37' rows='5'>".HTMLEntities2(stripslashes($_POST['body']))."</textarea>`n",true);
         output("<input type='submit' class='button' value='Invia'></form>",true);
         addnav("","gildadrago.php?az=mailto&op=".$_GET['op']);
         addnav("Torna all'entrata","gildadrago.php");
    }
    if ($_GET['az'] == "mailto") {
         $body = "`^Messaggio del Dominatore di Draghi.`n";
         $body .="`#".$_POST['body'];
         if ($_GET['op'] == 1) {
             $clausula = "dio=3 AND superuser=0";
         }elseif ($_GET['op'] == 2) {
             $clausula = "dio=3 AND (carriera>49 OR carriera<56) AND superuser=0";
         }elseif ($_GET['op'] == 3) {
             $clausula = "dio=3 AND carriera=55 AND superuser=0";
         }
         $sqlmail = "SELECT acctid FROM accounts WHERE ".$clausula;
         $resultmail = db_query($sqlmail);
         //output("Query SQL: ".$sqlmail."`nNumero righe: ".db_num_rows($resultmail));
         $countrow1 = db_num_rows($resultmail);
         for ($imail=0; $imail<$countrow1; $imail++){
         //for ($imail=0;$imail<db_num_rows($resultmail);$imail++){
             $rowmail = db_fetch_assoc($resultmail);
             systemmail($rowmail['acctid'],"`^Comunicazione del Dominatore di Draghi!`0",$body,$session['user']['acctid']);
             //output("Account ID N°".($imail+1).": ".$rowmail['acctid']."`n");
         }
         addnav("`&Vai all'entrata", "gildadrago.php");
    }
    //Excalibur: fine Mail di massa
    if ($_GET['az'] == "punisci") {
        addnav("`&Vai all'entrata", "gildadrago.php");
        output("`3Da quì puoi infliggere le punizioni in nome del `@Drago Verde`3 e bandire i fedeli per alcuni giorni (di gioco) massimo 9.`7.`n");
        output("<form action='gildadrago.php?az=addpun' method='POST'>",true);
        addnav("","gildadrago.php?az=addpun");
        output("`bPersonaggio: <input name='name'>`nGiorni di punizione: <input name='amt' size='3'>`n<input type='submit' class='button' value='Punisci'>",true);
        output("</form>",true);
    }
    if ($_GET['az'] == "addpun") {
        addnav("`&Vai all'entrata", "gildadrago.php");
        $search="%";
        for ($i=0;$i<strlen($_POST['name']);$i++){
            $search.=substr($_POST['name'],$i,1)."%";
        }
        $sql = "SELECT name,acctid,superuser FROM accounts WHERE login LIKE '$search' AND dio = 3";
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
            if($row['superuser']==0){
                output("<a href='gildadrago.php?op=add2&id={$row['acctid']}&amt={$punizione}'>",true);
                output($row['name']." ({$row['punizione']})");
                output("</a>`n",true);
                addnav("","gildadrago.php?op=add2&id={$row['acctid']}&amt={$punizione}");
            }
        }
    }
    if ($_GET['op']=="add2"){
        addnav("`&Vai all'entrata", "gildadrago.php");
        $punizione=$_GET['amt'];
        $sqlpun = "SELECT * FROM punizioni_chiese WHERE acctid='{$_GET['id']}' AND fede='3'";
        $respun = db_query($sqlpun) or die(db_error(LINK));
        if (db_num_rows($respun) == 0) {
            $sqli = "INSERT INTO punizioni_chiese (acctid,giorni,fede) VALUES ('{$_GET['id']}','{$punizione}','3')";
            $resulti=db_query($sqli);
            $mailmessage = "`\$Il Dominatore di Draghi `7ti ha inflitto una punizione!`nSei stato bandito dalla Gilda del Drago Verde per `\$".$_GET['amt']." `7 giorni.`n";
            systemmail($_GET['id'],"`2Punizione.`2",$mailmessage);
            output("`3Hai inflitto la punizione in nome del `@Drago Verde`3!`n");
        } else {
            $sqli = "UPDATE punizioni_chiese SET giorni='{$punizione}' WHERE acctid='{$_GET['id']}' AND fede='3'";
            $resulti=db_query($sqli);
            $mailmessage = "`\$Il Dominatore di Draghi `7ha modificato la tua punizione!`nSei ora bandito dalla Gilda del Drago Verde per `\$".$_GET['amt']." `7 giorni.`n";
            systemmail($_GET['id'],"`2Punizione.`2",$mailmessage);
            output("`3Hai modificato la punizione in nome del `@Drago Verde`3!`n");
        }
        $_GET['op']="";

    }
    if ($_GET['az']=="puniti"){
        addnav("`&Vai all'entrata", "gildadrago.php");
        $sql = "SELECT a.acctid,a.name,b.giorni FROM accounts a, punizioni_chiese b WHERE a.acctid=b.acctid AND b.giorni>0 AND a.dio=3 AND b.fede=3 ORDER BY b.giorni DESC";
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
    $sql = "SELECT * FROM punizioni_chiese WHERE acctid='{$session['user']['acctid']}' AND fede=3";
    $result = db_query($sql);
    if (db_num_rows($result) != 0) {
        $ref = db_fetch_assoc($result);
    }
    output("`3Nonostante la punizione che ti ha inflitto il Dominatore dei Draghi provi ad entrare nella Gilda.`n");
    output("`3Ma tutti sanno delle tue malefatte, ti malmenano e ti ributtano fuori dal Salone!");
    output("`3 Ti dicono di tornare tra `^".$ref[giorni]." giorni`3, quando scadrà la tua punizione!`n`n");
    addnav("Torna al Villaggio", "village.php");
}
page_footer();

?>