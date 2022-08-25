<?php
require_once "common.php";
isnewday(2);
addcommentary();
$accountid = $session['user']['acctid'];
//Excalibur: Per mandare gli admin ad approvare i nuovi PG
if ($session['user']['superuser'] > 2){
    $sql = "SELECT consono FROM accounts WHERE consono = 0 AND emailvalidation = '' LIMIT 1";
    $result = db_query($sql) or die(db_error(LINK));
    if (db_num_rows($result) > 0){
        output("<big>`c`b`\$CI SONO NUOVI UTENTI IN ATTESA DI APPROVAZIONE !!!`b`c`0</big>`n`n",true);
        addnav("Valuta Nick","approva.php");
    }
}
//Excalibur: Fine approvazione PG
if ($_GET['opmanu']=="si"){
    savesetting("manutenzione",3);
    output("<big><big>`b`^Tolta la Manutenzione`b`0</big></big>`n`n",true);
}
//cancellazione vecchie news
$sql = "DELETE from news WHERE newsdate <'".date("Y-m-d H:i:s",strtotime(date("r")."-5 days"))."'";
db_query($sql);
addnav("M?Torna alla Mondanità","village.php");
if ($_GET['op']=="newsdelete"){
    $sql = "DELETE FROM news WHERE newsid='".$_GET['newsid']."'";
    db_query($sql);
    $return = $_GET['return'];
    $return = preg_replace("'[?&]c=[[:digit:]-]*'","",$return);
    $return = substr($return,strrpos($return,"/")+1);
    redirect($return);
}
if ($_GET['op']=="commentdelete"){
    $sql = "SELECT *
              FROM commentary
             WHERE commentid='".$_GET['commentid']."'";
    $result = db_query($sql) or die(db_error(LINK));
    $row = db_fetch_assoc($result);
    $row['comment']=preg_replace("'[`][^123456789v!@#$%^&()V]'","",$row['comment']);
    $sql = "INSERT INTO commentdeleted VALUES
           ('".$row['commentid']."',
           '".$row['section']."',
           '".$row['author']."',
           '".addslashes($row['comment'])."',
           '".$accountid."',
           '".$row['postdate']."')";
    db_query($sql);
    $sql = "DELETE FROM commentary WHERE commentid='".$_GET['commentid']."'";
    db_query($sql);
    $return = $_GET['return'];
    $return = preg_replace("'[?&]c=[[:digit:]-]*'","",$return);
    $return = substr($return,strrpos($return,"/")+1);
    if (strpos($return,"?")===false && strpos($return,"&")!==false){
        $x = strpos($return,"&");
        $return = substr($return,0,$x-1)."?".substr($return,$x+1);
    }
    redirect($return);
}

page_header("Grotta del SuperUtente");
$session['user']['locazione'] = 197;
if ($_GET['op']=="checkcommentary"){
    addnav("G?Torna alla Grotta","superuser.php");
    //viewcommentary("' or '1'='1","X",100);
    viewcommentary("village' || section NOT LIKE 'salariunioni%","X",100);
}else if ($_GET['op'] == "bounties") {
    addnav("G?Torna alla Grotta","superuser.php");
    output("`c`bElenco Ricercati`b`c`n");
    $sql = "SELECT name,alive,sex,level,laston,loggedin,lastip,uniqueid,bounty FROM accounts WHERE bounty>0 ORDER BY bounty DESC";
    $result = db_query($sql) or die(sql_error($sql));
    output("<table border=0 cellpadding=2 cellspacing=1 bgcolor='#999999'>",true);
    output("<tr class='trhead'><td><b>Taglia</b></td><td><b>Livello</b></td><td><b>Nome</b></td><td><b>Locazione</b></td><td><b>Sesso</b></td><td><b>Vivo</b></td><td><b>Last on</b></tr>",true);
    $countrow = db_num_rows($result);
    for ($i=0; $i<$countrow; $i++){
    //for($i=0;$i< db_num_rows($result);$i++){
        $row = db_fetch_assoc($result);
        output("<tr class='".($i%2?"trdark":"trlight")."'><td>",true);
        output("`^".$row['bounty']."`0");
        output("</td><td>",true);
        output("`^".$row['level']."`0");
        output("</td><td>",true);
        output("`&".$row['name']."`0");
        if ($session['user']['loggedin']) output("</a>",true);
        output("</td><td>",true);
        $loggedin=(date("U") - strtotime($row['laston']) < getsetting("LOGINTIMEOUT",900) && $row['loggedin']);
        output($row['location'] ?"`3La Locanda`0" :($loggedin ?"`#Connesso`0" :"`3I Campi`0"));
        output("</td><td>",true);
        output($row['sex']?"`!Femmina`0":"`!Maschio`0");
        output("</td><td>",true);
        output($row['alive']?"`1Si`0":"`4No`0");
        output("</td><td>",true);
        $laston=round((strtotime(date("r"))-strtotime($row['laston'])) / 86400,0)." giorni";
        if (substr($laston,0,2)=="1 ") $laston="1 day";
        if (date("Y-m-d",strtotime($row['laston'])) == date("Y-m-d")) $laston="Oggi";
        if (date("Y-m-d",strtotime($row['laston'])) == date("Y-m-d",strtotime(date("r")."-1 day"))) $laston="Ieri";
        if ($loggedin) $laston="Ora";
        output($laston);
        output("</td></tr>",true);
    }
    output("</table>",true);
}else if ($_GET['op'] == "miniera") {
    addnav("P?Dati per player", "superuser.php?op=minierap");
    addnav("G?Torna alla Grotta","superuser.php");
    output("`c`bMateriali estratti in miniera per giorno`b`c`n");
    $sql = "SELECT COUNT(acctid) AS totale,materiale,data FROM miniera GROUP BY data,materiale ORDER BY data DESC";
    $result = db_query($sql) or die(sql_error($sql));
    output("<table border=0 cellpadding=2 cellspacing=1 bgcolor='#999999'>",true);
    output("<tr class='trhead'><td><b>Materiale</b></td><td><b>Data</b></td><td><b>Quantità</b></td></tr>",true);
    $countrow = db_num_rows($result);
    for ($i=0; $i<$countrow; $i++){
    //for($i=0;$i<db_num_rows($result);$i++){
        $row = db_fetch_assoc($result);
        output("<tr class='".($i%2?"trdark":"trlight")."'><td>",true);
        output("`^".$row['materiale']."`0");
        output("</td><td>",true);
        output("`^".$row['data']."`0");
        output("</td><td>",true);
        output("`&".$row['totale']."`0");
        output("</td></tr>",true);
    }
    output("</table>",true);
}else if ($_GET['op'] == "riparazioni") {
    $arraycarriera = array(
    5=>"Garzone",
    6=>"Apprendista",
    7=>"Fabbro",
    8=>"Mastro Fabbro",
    );
    //addnav("P?Dati per player", "superuser.php?op=minierap");
    addnav("G?Torna alla Grotta","superuser.php");
    output("`c`bPunti riparazione`b`c`n");
    $sql = "SELECT * FROM riparazioni ORDER BY punti_riparazione DESC";
    $result = db_query($sql) or die(sql_error($sql));
    output("<table border=0 cellpadding=2 cellspacing=1 bgcolor='#999999'>",true);
    output("<tr class='trhead'><td><b>Player</b></td><td><b>Rango</b></td><td><b>Punti riparazioni</b></td><td><b>Oro</b></td></tr>",true);
    $sqldel = "DELETE FROM riparazioni WHERE acctid IN (";
    $booleandelete = "false";
    $trovato = "false";
    $totale=0;
    $countrow = db_num_rows($result);
    for ($i=0; $i<$countrow; $i++){
    //for($i=0;$i< db_num_rows($result);$i++){
        $row = db_fetch_assoc($result);
        $sql = "SELECT name,carriera FROM accounts WHERE acctid='".$row['acctid']."'";
        $resulta = db_query($sql) or die(sql_error($sql));
        $rowa = db_fetch_assoc($resulta);
        $carriera=$rowa['carriera'];
        if (($rowa['name']==null OR  $rowa['name']=="")
        OR  ($row['punti_riparazione']==0 AND $row['gold']==0)
        OR  ($arraycarriera[$carriera]=="" OR $arraycarriera[$carriera]==null))
        {
            $sqldel = $sqldel."'".$row['acctid']."',";
            $booleandelete = "true";
        } else {
            output("<tr class='".($i%2?"trdark":"trlight")."'><td>",true);
            output("`^".$rowa['name']."`0");
            output("</td><td>",true);
            output("`^$arraycarriera[$carriera]`0");
            output("</td><td>",true);
            output("`^".$row['punti_riparazione']."`0");
            output("</td><td>",true);
            output("`^".$row['gold']."`0");
            output("</td></tr>",true);
            $totale = $totale + $row['punti_riparazione'];
            $trovato = true;
        }
    }
    if (!$trovato OR $i==0) {
        output("<tr>",true);
        output("<td colspan=4>`iNessun Player Trovato`i</td>",true);
        output("</tr>",true);
    }
    output("<tr class='trhead'><td colspan=2><b>Totale punti riparazione</b></td><td colspan=2><b>$totale</b></td></tr>",true);
    output("</table>",true);
    if ($booleandelete=="true") {
        $sqldel = substr($sqldel,0,strlen($sqldel)-1);
        $sqldel = $sqldel.")";
        db_query($sqldel) or die(db_error(LINK));
    }
}else if ($_GET['op'] == "minierap") {
    addnav("P?Dati generici", "superuser.php?op=miniera");
    addnav("G?Torna alla Grotta","superuser.php");
    output("`c`bMateriali estratti in miniera per giorno`b`c`n");
    $sql = "SELECT COUNT(id) AS totale,materiale,data,acctid FROM miniera GROUP BY acctid,data,materiale ORDER BY data DESC,totale DESC,materiale DESC";
    $result = db_query($sql) or die(sql_error($sql));
    output("<table border=0 cellpadding=2 cellspacing=1 bgcolor='#999999'>",true);
    output("<tr class='trhead'><td><b>Player</b></td><td><b>Materiale</b></td><td><b>Data</b></td><td><b>Quantità</b></td></tr>",true);
    $countrow = db_num_rows($result);
    for ($i=0; $i<$countrow; $i++){
    //for($i=0;$i< db_num_rows($result);$i++){
        $row = db_fetch_assoc($result);
        $sql = "SELECT name FROM accounts WHERE acctid='".$row['acctid']."'";
        $resulta = db_query($sql) or die(sql_error($sql));
        $rowa = db_fetch_assoc($resulta);
        output("<tr class='".($i%2?"trdark":"trlight")."'><td>",true);
        output("`^".$rowa['name']."`0");
        output("</td><td>",true);
        output("`^".$row['materiale']."`0");
        output("</td><td>",true);
        output("`^".$row['data']."`0");
        output("</td><td>",true);
        output("`&".$row['totale']."`0");
        output("</td></tr>",true);
    }
    output("</table>",true);
    //maximus inizio - legno della foresta
}else if ($_GET['op'] == "foresta") {
    addnav("P?Dati per player", "superuser.php?op=forestap");
    addnav("G?Torna alla Grotta","superuser.php");
    output("`c`bLegno estratto in foresta per giorno`b`c`n");
    $sql = "SELECT COUNT(acctid) AS totale,materiale,data FROM foresta GROUP BY data,materiale ORDER BY data DESC";
    $result = db_query($sql) or die(sql_error($sql));
    output("<table border=0 cellpadding=2 cellspacing=1 bgcolor='#999999'>",true);
    output("<tr class='trhead'><td><b>Materiale</b></td><td><b>Data</b></td><td><b>Quantità</b></td></tr>",true);
    $countrow = db_num_rows($result);
    for ($i=0; $i<$countrow; $i++){
    //for($i=0;$i< db_num_rows($result);$i++){
        $row = db_fetch_assoc($result);
        output("<tr class='".($i%2?"trdark":"trlight")."'><td>",true);
        output("`^".$row['materiale']."`0");
        output("</td><td>",true);
        output("`^".$row['data']."`0");
        output("</td><td>",true);
        output("`&".$row['totale']."`0");
        output("</td></tr>",true);
    }
    output("</table>",true);
}else if ($_GET['op'] == "forestap") {
    addnav("P?Dati generici", "superuser.php?op=foresta");
    addnav("G?Torna alla Grotta","superuser.php");
    output("`c`bLegno estratto in foresta per giorno`b`c`n");
    $sql = "SELECT COUNT(id) AS totale,materiale,data,acctid FROM foresta GROUP BY acctid,data,materiale ORDER BY data DESC,totale DESC,materiale DESC";
    $result = db_query($sql) or die(sql_error($sql));
    output("<table border=0 cellpadding=2 cellspacing=1 bgcolor='#999999'>",true);
    output("<tr class='trhead'><td><b>Player</b></td><td><b>Materiale</b></td><td><b>Data</b></td><td><b>Quantità</b></td></tr>",true);
    $countrow = db_num_rows($result);
    for ($i=0; $i<$countrow; $i++){
    //for($i=0;$i< db_num_rows($result);$i++){
        $row = db_fetch_assoc($result);
        $sql = "SELECT name FROM accounts WHERE acctid='".$row['acctid']."'";
        $resulta = db_query($sql) or die(sql_error($sql));
        $rowa = db_fetch_assoc($resulta);
        output("<tr class='".($i%2?"trdark":"trlight")."'><td>",true);
        if ($rowa['name']=="" or $rowa['name']==null) {
            output("`^`iSconosciuto`i`0");
        } else {
            output("`^".$rowa['name']."`0");
        }
        output("</td><td>",true);
        output("`^">$row['materiale']."`0");
        output("</td><td>",true);
        output("`^".$row['data']."`0");
        output("</td><td>",true);
        output("`&".$row['totale']."`0");
        output("</td></tr>",true);
    }
    output("</table>",true);
    // Maximus fine - legno della foresta
}else if ($_GET['op'] == "gdr") {
    addnav("G?Torna alla Grotta","superuser.php");
    addnav("Chiudi concorso","superuser.php?op=gdr_chiudi");
    output("`c`bBrani inviati in questo concorso`b`c`n");
    $sql = "SELECT * FROM gdr_contest WHERE voto='0' ORDER BY data DESC";
    $result = db_query($sql) or die(sql_error($sql));
    output("<table border=0 cellpadding=2 cellspacing=1 bgcolor='#999999'>",true);
    output("<tr class='trhead'><td><b>Player</b></td><td><b>Titolo</b></td><td><b>Voto medio provvisorio</b></td><td><b>Data Arrivo</b></td><td><b>Leggi</b></td></tr>",true);
    $countrow = db_num_rows($result);
    for ($i=0; $i<$countrow; $i++){
    //for($i=0;$i< db_num_rows($result);$i++){
        $row = db_fetch_assoc($result);
        $sql = "SELECT AVG(voto) AS voto FROM gdr_voti WHERE id_brano='".$row['id']."'";
        $resultv = db_query($sql) or die(sql_error($sql));
        $rowv = db_fetch_assoc($resultv);
        output("<tr class='".($i%2?"trdark":"trlight")."'><td>",true);
        output("`^".$row['nome']."`0");
        output("</td><td align=center>",true);
        output("`^".$row['titolo']."`0");
        output("</td><td align=center>",true);
        output("`^".number_format($rowv['voto'],2)."`0");
        output("</td><td>",true);
        output("`^".$row['data']."`0");
        output("</td><td><A href=superuser.php?op=gdr_leggi&id=".$row['id'].">`^Leggi`0</a>",true);
        output("</td></tr>",true);
        addnav("","superuser.php?op=gdr_leggi&id=".$row['id']);
    }
    output("</table>",true);
}else if ($_GET['op'] == "gdr_leggi") {
    addnav("B?Elenco Brani", "superuser.php?op=gdr");
    addnav("G?Torna alla Grotta","superuser.php");
    $sql = "SELECT * FROM gdr_contest WHERE id='".$_GET['id']."'";
    $result = db_query($sql) or die(sql_error($sql));
    $row = db_fetch_assoc($result);
    $id=$row['id'];
    $titolo=$row['titolo'];
    $brano=nl2br($row['testo']);
    output("`@$titolo`n`n`&$brano`0",true);
    $sql = "SELECT * FROM gdr_voti WHERE id_brano='".$_GET['id']."'";
    $result = db_query($sql) or die(sql_error($sql));
    output("<table border=0 cellpadding=2 cellspacing=1 bgcolor='#999999'>",true);
    output("<tr class='trhead'><td><b>Admin</b></td><td><b>Originalità</b></td><td><b>Stile</b></td><td><b>Trama</b></td><td><b>Voto medio</b></td></tr>",true);
    $countrow = db_num_rows($result);
    for ($i=0; $i<$countrow; $i++){
    //for($i=0;$i< db_num_rows($result);$i++){
        $row = db_fetch_assoc($result);
        output("<tr class='".($i%2?"trdark":"trlight")."'><td>",true);
        output("`^".$row['nome_admin']."`0");
        output("</td><td align=center>",true);
        output("`^".$row['originalita']."`0");
        output("</td><td>",true);
        output("`^".$row['stile']."`0");
        output("</td><td>",true);
        output("`^".$row['trama']."`0");
        output("</td><td>",true);
        output("`^".$row['voto']."`0");
        output("</td></tr>",true);
        if($session['user']['name']==$row['nome_admin'])$votato=si;
    }
    output("</table>",true);
    if($votato!=si){
        output("<form action='superuser.php?op=add_vote&idbrano=$id' method='POST'>",true);
        addnav("","superuser.php?op=add_vote&idbrano=$id");
        output("`bDai voti da 1 a 10:`b`nOriginalità: <input name='ori' size='3'> Stile: <input name='sti' size='3'> Trama: <input name='tra' size='3'> <input type='submit' class='button' value='Aggiungi Voto'>",true);
        output("</form>",true);
    }
}else if ($_GET['op'] == "add_vote") {
    addnav("B?Elenco Brani", "superuser.php?op=gdr");
    addnav("G?Torna alla Grotta","superuser.php");
    $voto_medio=number_format(($_POST['ori']+$_POST['tra']+$_POST['sti'])/3,2);
    $sqldr="INSERT INTO gdr_voti (id_brano,nome_admin,originalita,stile,trama,voto)
    VALUES ('".$_GET['idbrano']."','".$session['user']['name']."','".$_POST['ori']."','".$_POST['sti']."','".$_POST['tra']."','".$voto_medio."')";
    db_query($sqldr) or die(db_error(LINK));
    output("`c`bIl tuo voto è stato registrato correttamente!`b`c`n");
}else if ($_GET['op'] == "gdr_chiudi") {
    output("`c`bPROSEGUI SOLO SE SEI SICURO DI QUELLO CHE STAI FACENDO!!`b`c`n");
    output("`c`b`@E DISTRIBUISCI I PREMI SENZA UTENTI ONLINE!!`b`c`n");
    addnav("G?Torna alla Grotta","superuser.php");
    addnav("B?Elenco Brani", "superuser.php?op=gdr");
    $oggi=date("d/m/Y");
    output("<form action='superuser.php?op=gdr_chiudi_sicuro' method='POST'>",true);
    addnav("","superuser.php?op=gdr_chiudi_sicuro");
    output("`bInserisci data chiusura concorso: <input name='data' size='12' value='$oggi'><input type='submit' class='button' value='Conferma chiusura'>",true);
    output("</form>",true);
    //output("<A href='http://www.valuemail.it/online_reg/affirmation.jsp?action=register&errorPage=/index.jsp&gid=40014214&pid=2014&iso_lang=it&iso_country=It&uemail=".$session[user][emailaddress]."&invite_user=vm&agb=1&mime_type_id=0&vm_active=1' target=_blank>`^Valuemail`0</a>",true);
}else if ($_GET['op'] == "gdr_chiudi_sicuro") {
    addnav("G?Torna alla Grotta","superuser.php");
    addnav("B?Elenco Brani", "superuser.php?op=gdr");

    list($anno, $mese, $giorno) = explode("/", $_POST['data']);
    $oggi =  $giorno."-".$mese."-".$anno;
    output("`c`b$oggi`b`c`n");
    $sql = "SELECT * FROM gdr_contest WHERE voto='0' AND data <= '$oggi' ORDER BY data DESC";
    $result = db_query($sql) or die(sql_error($sql));
    $countrow = db_num_rows($result);
    for ($i=0; $i<$countrow; $i++){
    //for($i=0;$i< db_num_rows($result);$i++){
        $row = db_fetch_assoc($result);
        $sql = "SELECT AVG(voto) AS voto FROM gdr_voti WHERE id_brano='".$row['id']."'";
        $resultv = db_query($sql) or die(sql_error($sql));
        $rowv = db_fetch_assoc($resultv);

        $sqlua = "UPDATE gdr_contest SET data_concorso='$oggi',voto='".$rowv['voto']."' WHERE id='".$row['id']."'";
        $resultm = db_query($sqlua) or die(db_error(LINK));
    }
    output("`c`bConcorso chiuso`b`c`n");

    //Distribuzione premi concorso GDR by Excalibur
    $sql = "SELECT * FROM gdr_contest WHERE data_concorso='$oggi' AND voto > 0 ORDER BY voto DESC LIMIT 3";
    $result = db_query($sql) or die(sql_error($sql));
    $countrow = db_num_rows($result);
    for ($i=0; $i<$countrow; $i++){
    //for($i=0;$i< db_num_rows($result);$i++){
        $row = db_fetch_assoc($result);
        $sqllp = "SELECT name FROM accounts WHERE acctid=".$row['acctid'];
        $resultlp = db_query($sqllp) or die(db_error($sqllp));
        $rowlp = db_fetch_assoc($resultlp);
        $nomeplayer=$rowlp['name'];
        //output("Nome Player da premiare: ".$nomeplayer." (I vale: $i)`n");
        if ($i == 0) {
            $sqlupa = "UPDATE accounts SET fama3mesi=fama3mesi+50000, donation=donation+50 WHERE acctid=".$row['acctid'];
            $resultupa = db_query($sqlupa) or die(db_error($sqlupa));
            $sqllrs = "SELECT * FROM donazioni WHERE nome = 'chansonnier' AND idplayer = ".$row['acctid'];
            $resultlrs = db_query($sqllrs) or die(sql_error($sqllrs));
            if (db_num_rows($resultlrs) == 0){
                $sqlirs = "INSERT INTO donazioni VALUES ('chansonnier',".$row['acctid'].",1,'P')";
                $resultirs = db_query($sqlirs) or die(sql_error($sqlirs));
            } else {
                output("`(Il primo classificato aveva già la razza `@Cantastorie`(`nInvio mail comunicazione.`n");
            }
            systemmail($row['acctid'],"`(Premio Concorso GDR","`6Ti sei classificato al 1° posto nel concorso GDR!!!`n
           Ti sei aggiudicato la razza speciale `@Cantastorie`6, 50.000 Punti Fama e 50 Punti Donazione!!!");
            output("`@Assegnata razza `&Cantastorie`@, 50.000 punti fama e 50 punti donazione a $nomeplayer`n`n");
        }elseif ($i == 1) {
            $sqlupa = "UPDATE accounts SET fama3mesi=fama3mesi+25000 WHERE acctid=".$row['acctid'];
            $resultupa = db_query($sqlupa) or die(db_error($sqlupa));
            $sqllrs = "SELECT * FROM donazioni WHERE nome = 'sconto_cura' AND idplayer = ".$row['acctid'];
            $resultlrs = db_query($sqllrs) or die(sql_error($sqllrs));
            if (db_num_rows($resultlrs) == 0){
                $sqlirs = "INSERT INTO donazioni VALUES ('sconto_cura',".$row['acctid'].",30,'T*')";
                $resultirs = db_query($sqlirs) or die(sql_error($sqlirs));
            } else {
                $sqlirs = "UPDATE donazioni SET usi = usi+30 WHERE idplayer = ".$row['acctid']." AND nome = 'sconto_cura'";
                $resultirs = db_query($sqlirs) or die(sql_error($sqlirs));
            }
            systemmail($row['acctid'],"`(Premio Concorso GDR","`6Ti sei classificato al 2° posto nel concorso GDR!!!`n
           Ti sei aggiudicato 25.000 Punti Fama ed il Golinda Pass, che ti darà accesso al 50% di sconto sulle guarigioni!!!");
            output("`@Assegnati 25.000 punti fama e Golinda Pass a $nomeplayer`n`n");
        }else {
            $sqlupa = "UPDATE accounts SET fama3mesi=fama3mesi+10000 WHERE acctid=".$row['acctid'];
            $resultupa = db_query($sqlupa) or die(db_error($sqlupa));
            $sqllrs = "SELECT * FROM donazioni WHERE nome='polvere_fata' AND idplayer=".$row['acctid'];
            $resultlrs = db_query($sqllrs) or die(sql_error($sqllrs));
            if (db_num_rows($resultlrs) == 0){
                $sqlirs = "INSERT INTO donazioni VALUES ('polvere_fata',".$row['acctid'].",45,'T')";
                $resultirs = db_query($sqlirs) or die(sql_error($sqlirs));
            } else {
                $sqlirs = "UPDATE donazioni SET usi=usi+45 WHERE idplayer=".$row['acctid']." AND nome='polvere_fata'";
                $resultirs = db_query($sqlirs) or die(sql_error($sqlirs));
            }
            systemmail($row['acctid'],"`(Premio Concorso GDR","`6Ti sei classificato al 3° posto nel concorso GDR!!!`n
           Ti sei aggiudicato 10.000 Punti Fama ed una bottiglia di Polvere di Fata!!!");
            output("`@Assegnati 10.000 punti fama e una bottiglia di Polvere di Fata a $nomeplayer`n`n");
        }
    }
    //Fine distribuzione premi concorso GDR

}elseif ($_GET['op'] == 'export_cvs_gioco'){
 /** CVS EXPORT
 *  per poter funzionare il comando sudo per apache
 *  va aggiunto in /etc/sudoers
 *
 *  apache  ALL=(ALL) NOPASSWD: ALL
 *
 * Questo è pericoloso perchè da diritti da root allo user apche
 * passando attraverso sudo senza bisogno di password.
 * ATTENZIONE !!
 */
    addnav("G?Torna alla Grotta","superuser.php");
    addnav("","superuser.php?op=export_cvs_gioco_si");
    output("<center>ATTENZIONE !!<br><br>Sei sicuro di voler procedere ?<br>",true);
    output("<form method=\"post\" action=\"superuser.php?op=export_cvs_gioco_si\">",true);
    output("<br><input type=\"submit\" value=\"Procedi\"></center></form>",true);
}elseif ($_GET['op'] == 'export_cvs_gioco_si'){
    addnav("G?Torna alla Grotta","superuser.php?opmanu=si");
    addnav("A?Aggiorna CVS GIOCO","superuser.php?op=export_cvs_gioco_sicuro");
    savesetting("manutenzione",1);
    output("<big><big>`b`^Messo il gioco in Manutenzione`b</big></big>`n`n`#Attendi qualche secondo prima di procedere.",true);
    output("`@Conferma l'aggiornamento del `\$CVS AREA GIOCO`@, o torna alla Grotta ");
    output("`n(viene tolta automaticamente la manutenzione)`n");
    viewcommentary("village","Aggiungi",30,20);
}elseif ($_GET['op'] == 'export_cvs_gioco_sicuro'){
    addnav("G?Torna alla Grotta","superuser.php");
    $str=shell_exec('sudo /bin/bash /root/export_cvs.sh');
    debuglog("`\$aggiorna CVS area gioco da server gioco`0");
    output("<br>Esecuzione terminata!<br>",true);
    output("`\$NOTA: se sono stati aggiunti degli eventi speciali, è necessario passare nell'editor di probabilità per inserirli effettivamente in gioco!");
    savesetting("manutenzione",3);
    output("<big><big>`b`^Tolta la Manutenzione`b`0</big></big>`n`n",true);
}elseif ($_GET['op'] == 'export_cvs_test'){
 /** CVS EXPORT
 *  per poter funzionare il comando sudo per apache
 *  va aggiunto in /etc/sudoers
 *
 *  apache  ALL=(ALL) NOPASSWD: ALL
 *
 * Questo è pericoloso perchè da diritti da root allo user apche
 * passando attraverso sudo senza bisogno di password.
 * ATTENZIONE !!
 */
    addnav("G?Torna alla Grotta","superuser.php");
    addnav("","superuser.php?op=export_cvs_test_si");
    output("<center>ATTENZIONE !!<br><br>Sei sicuro di voler procedere ?<br>",true);
    output("<form method=\"post\" action=\"superuser.php?op=export_cvs_test_si\">",true);
    output("<br><input type=\"submit\" value=\"Procedi\"></center><br></form>",true);
}elseif ($_GET['op'] == "export_cvs_test_si"){
    viewcommentary("village","Aggiungi",30,20);
    addnav("G?Torna alla Grotta","superuser.php");
    addnav("A?Aggiorna CVS GIOCO","superuser.php?op=export_cvs_test_sicuro");
    output("`@Conferma l'aggiornamento del `#CVS AREA TEST`@, o torna alla Grotta`n`n");
}elseif ($_GET['op'] == "export_cvs_test_sicuro"){
    addnav("G?Torna alla Grotta","superuser.php");
    $str=shell_exec('sudo /bin/bash /root/export_cvs_test.sh');
    debuglog("`#aggiorna CVS area test da server gioco`0");
    output('<br>Esecuzione terminata!<br>',true);
    output("`\$NOTA: se sono stati aggiunti degli eventi speciali, è necessario passare nell'editor di probabilità per inserirli effettivamente in gioco!");
}elseif ($_GET['op'] == "moduli"){
    if($_GET['az']=="registra_moduli"){
        $sqlirs = "UPDATE moduli SET stato = 'off'";
        $resultirs = db_query($sqlirs) or die(sql_error($sqlirs));
        output("`n`n`\$MODIFICA REGISTRATA!");
        $sql = "SELECT * FROM moduli";
        $result = db_query($sql) or die(sql_error($sql));
        while($row = db_fetch_assoc($result)) {
            if($_POST[$row['nome']]=='on'){
                $sqlirs = "UPDATE moduli SET stato='on' WHERE nome='".$row['nome']."'";
                $resultirs = db_query($sqlirs) or die(sql_error($sqlirs));
            }else{
                $sqlirs = "UPDATE moduli SET stato='off' WHERE nome='".$row['nome']."'";
                $resultirs = db_query($sqlirs) or die(sql_error($sqlirs));
            }
        }
    }
    addnav("","superuser.php?op=moduli&az=registra_moduli");
    output("<form method=\"post\" action=\"superuser.php?op=moduli&az=registra_moduli\">",true);
    addnav("G?Torna alla Grotta","superuser.php");
    output("`^Gestione moduli attivi in gioco, agisci solo se sai cosa stai facendo!");
    $sql = "SELECT * FROM moduli";
    $result = db_query($sql) or die(sql_error($sql));
    output("<table border=1 cellpadding=2 cellspacing=1 bgcolor='#999999'>",true);
    output("<tr class='trhead'><td><b>Nome</b></td><td><b>Stato</b></td></tr>",true);
    while($row = db_fetch_assoc($result)) {
        output("<tr class='trhead'><td>".$row['nome']."</td>",true);
        if($row[stato]=='off'){
            output("<td><input type=checkbox name=".$row['nome']."></td></tr>",true);
        }else{
            output("<td><input type=checkbox name=".$row['nome']." checked></td></tr>",true);
        }
    }
    output("</table>",true);
    output("<br><input type=\"submit\" value=\"Registra\"><br></form><br><br>",true);

    output("`n`n`6Mettere i moduli a mano nella tabella moduli in futuro fare un tool per la creazione!");
}else{
    if ($session['user']['sex']){
        output("`^Ti infili in una caverna segreta conosciuta da pochi intimi. All'interno ");
        output("sei accolta dalla vista di numerosi uomini muscolosi a petto nudo che muovono ");
        output("delle palme e ti offrono dell'uva mentre di distendi su un divano Greco-Romano ");
        output("foderato di seta.`n`n");
    }else{
        output("`^Ti infili in una caverna segreta conosciuta da pochi intimi. All'interno ");
        output("sei accolto dalla vista di numerose donne prosperose scarsamente vestite che muovono ");
        output("delle palme e ti offrono dell'uva mentre di distendi su un divano Greco-Romano ");
        output("foderato di seta.`n`n");
    }
    viewcommentary("superuser","Comunica con gli altri dei:",25,"","sussurra",2);

    addnav("`@AZIONI`0");
    if ($session['user']['superuser']>=2){
        addnav("`%Opzioni Moderatori`0","");
        //addnav("C?Commenti Recenti","superuser.php?op=checkcommentary");
        addnav("Guarda Petizioni","viewpetition.php");
        addnav("1?Modera Commenti 1","moderate.php");
        addnav("2?Modera Commenti 2","moderatealt.php");
        addnav("B?Bio Giocatori","bios.php");
        addnav("y?Mute & FixNavs Player","nocomment.php");
        addnav("0?Il Limbo","limbo.php");
        addnav("Punizioni","punitions.php");
        addnav("F?Editor Frasi","taunt.php");
        addnav("A?Editor Armi","weaponeditor.php");
        addnav("r?Editor Armature","armoreditor.php");
        addnav("d?Editor Parolacce","badword.php");
        addnav("G?GDR", "gdr.php");
        if ($session['user']['superuser']>=3){
           addnav("`\$Opzioni Admin`0","");
           addnav("T?Mostra Taglie", "superuser.php?op=bounties");
           addnav("M?Mostra Miniera", "superuser.php?op=miniera");
           addnav("Mostra Foresta", "superuser.php?op=foresta");
           addnav("R?Riparazioni", "superuser.php?op=riparazioni");
        }
    }
    if ($session['user']['superuser']>3){
        addnav("`^Opzioni SuperAdmin`0","");
        addnav("D?Pagina Donatori","donators.php");
        addnav("D?Acquisti Donatori","acquisti.php");
        addnav("Retitler","retitle.php");
        addnav("Log & Mail","logs.php");
    }
    addnav("`@EDITOR`0");
    addnav("`%Editor Moderatori`0","");
    addnav("&?Editor Creature","creatures.php");
    addnav("Editor di Labirinti","mazeedit.php");
    if ($session['user']['superuser']>=3){
       addnav("`\$Editor Admin`0","");
        addnav("U?Editor Utenti","user.php");
        addnav("A?Editor Animali","mounts.php");
        addnav("Editor Maestri","sumasters.php");
        addnav("O?Editor di Oggetti","magiceditor.php");
        addnav("Spells Editor","itemeditor.php");
        addnav("Editor Incantesimi Maghi","incanteditor.php");
        addnav("Editor di Materiali","materialieditor.php");
        addnav("Editor Mappe Foresta","esplorazionieditor.php");
        addnav("Editor di Draghi","draghieditor.php");
        addnav("Editor Caserma","casermaeditor.php");
        if ($session['user']['superuser']>3) {
           addnav("`^EDITOR SuperAdmin`0","");
            addnav("Editor Terre Draghi","terradraghieditor.php");
        }
    }
    addnav("`@VARIE`0");
    addnav("`%Varie Moderatori`0","");
    if (getsetting("nomedb","logd") == "logd"){
        addnav("ToDo List","todolist.php");
    }
    addnav("Guida per lo Staff (WIP)","guidastaff.php");
    if ($session['user']['superuser']>=3) {
        addnav("`\$VARIE Admin`0","");
        addnav("Autorizzazione Multiaccounts","multiaccounts.php");
        addnav("Ricerca Multiaccount","controllomultiaccount.php");
        //addnav("Dai chiavi a proprietari","chiaviprop.php");
        addnav("V?Mostra Voodoo", "suvoodoo.php");
        addnav("Admin Tenute","suhouses.php");
        addnav("Premia Setta","premiochiesa.php");
        addnav("Azzera Torneo & Premi","torneoresetpremi.php");
        addnav("Editor Torneo Medaglie","medcontest.php");
        addnav("Gestione Gara Ippodromo","ippodromoreset.php");
        addnav("Visore Interazioni","controllointerazioni.php");
        addnav("Interazioni Tenute","controllotenute.php");
        addnav("Controllo Ricette Heimdall","controlloheimdall.php");
        addnav("Controllo Disconnessioni e SlowQuery","suclick.php");
        addnav("Controllo Tenute Abbandonate","sutenuteabb.php");
    }
    if ($session['user']['superuser']>=4) {
        addnav("`^VARIE Superadmin`0","");
        addnav("Traduttore","translate.php");
        addnav("Editor Concorso Frasi","suguess.php");
        addnav("Editor Concorso Fagioli","sufagioli.php");
    }
    addnav("`@Meccaniche di Gioco`0");
    addnav("`%Meccaniche Moderatori`0","");
    addnav("Referring URLs","referers.php");
    addnav("Statistiche","stats.php");
    if ($session['user']['superuser']>3){
        addnav("`\$Meccaniche SuperAdmin`0","");
        addnav("Settaggi Gioco","configuration.php");
        addnav("Moduli attivi","superuser.php?op=moduli");
        addnav("Frequenza Eventi Speciali","incontroeventi.php");
        if ($accountid == 1 or $accountid = 3 or $accountid = 3748 or $accountid = 4561 or $accountid = 8374 or $accountid = 25249){
             addnav("`\$Aggiorna CVS GIOCO`0","superuser.php?op=export_cvs_gioco");
             addnav("`#Aggiorna CVS TEST`0","superuser.php?op=export_cvs_test");
        }
    }
    addnav("Q?`\$`bEsci`b`0 nei campi","login.php?op=logout",true);
}
page_footer();
?>