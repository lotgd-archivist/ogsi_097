<?php
require_once "common.php";
isnewday(2);
page_header("Concorso GDR");
if ($_GET['op'] == "") {
   addnav("G?Torna alla Grotta","superuser.php");
   addnav("Chiudi concorso","gdr.php?op=gdr_chiudi");
   output("`c`bBrani inviati in questo concorso`b`c`n");
   $sql = "SELECT g.*, AVG(v1.voto) AS voto_finale
           FROM gdr_contest g
           LEFT JOIN accounts a ON a.acctid=g.id
           LEFT JOIN gdr_voti v1 ON g.id=v1.id_brano
           WHERE g.voto='0'
           GROUP BY g.id
           ORDER BY data DESC";
   $result = db_query($sql) or die(sql_error($sql));
   output("`3I brani che hai già votato saranno di `@`bquesto colore`b`3, quelli da votare di `^`bquesto colore`b`3`n`n");
   output("<table border=0 cellpadding=2 cellspacing=1 bgcolor='#999999'>",true);
   output("<tr class='trhead'><td><b>Player</b></td><td><b>Titolo</b></td><td><b>Voto medio provvisorio</b></td><td><b>Data Arrivo</b></td><td><b>Leggi</b></td></tr>",true);
   $countrow = db_num_rows($result);
   for ($i=0; $i<$countrow; $i++){
   //for($i=0;$i<db_num_rows($result);$i++){
       $row = db_fetch_assoc($result);
       $sql = "SELECT votante FROM gdr_voti
               WHERE id_brano='".$row['id']."'
               AND ".$session['user']['acctid']." = votante";
       $resultvoto = db_query($sql) or die(sql_error($sql));

       if (db_num_rows($resultvoto) > 0){
           $color = "`@";
       }else{
           $color = "`^";
       }
       //$sql = "SELECT AVG(voto) AS voto FROM gdr_voti WHERE id_brano='".$row['id']."'";
       //$resultv = db_query($sql) or die(sql_error($sql));
       //$rowv = db_fetch_assoc($resultv);
       output("<tr class='".($i%2?"trdark":"trlight")."'><td>",true);
       output($color.$row['nome']."`0");
       output("</td><td align=center>",true);
       output($color.$row['titolo']."`0");
       output("</td><td align=center>",true);
       output($color.number_format($row['voto_finale'],2)."`0");
       output("</td><td>",true);
       output($color.$row['data']."`0");
       output("</td><td><A href=gdr.php?op=gdr_leggi&id=".$row['id'].">".$color."Leggi`0</a>",true);
       output("</td></tr>",true);
       addnav("","gdr.php?op=gdr_leggi&id=".$row['id']);
   }
   output("</table>",true);
}elseif ($_GET['op'] == "gdr_leggi") {
    addnav("B?Elenco Brani", "gdr.php");
    addnav("G?Torna alla Grotta","superuser.php");
    $sql = "SELECT * FROM gdr_contest WHERE id='".$_GET['id']."'";
    $result = db_query($sql) or die(sql_error($sql));
    $row = db_fetch_assoc($result);
    $id=$row['id'];
    $titolo="`S`bTitolo:`b`@ ".$row['titolo'];
    $brano=nl2br($row['testo']);
    output("$titolo`n`n`s`bTesto`b:`n`&$brano`0",true);
    $sql = "SELECT g.*,a.name FROM gdr_voti g
            LEFT JOIN accounts a ON a.acctid=g.votante
            WHERE g.id_brano='".$_GET['id']."'";
    $result = db_query($sql) or die(sql_error($sql));
    output("<a href='#' onClick='visualizza(\"voti\")'>`n`n`b`SVisualizza/Nascondi Voti`b`0`n`n</a>",true);
    //addnav("","#");
    output("<div id='voti' style='display: none'>",true);
    output("<table border=0 cellpadding=2 cellspacing=1 bgcolor='#999999'>",true);
    output("<tr class='trhead'>
            <td><b>Admin</b></td>
            <td><b>Originalità</b></td>
            <td><b>Stile</b></td>
            <td><b>Trama</b></td>
            <td><b>Voto medio</b></td>
            </tr>"
            ,true);
    $countrow = db_num_rows($result);
    for ($i=0; $i<$countrow; $i++){
    //for($i=0;$i<db_num_rows($result);$i++){
        $row = db_fetch_assoc($result);
        output("<tr class='".($i%2?"trdark":"trlight")."'><td>",true);
        output("`^".$row['name']."`0");
        output("</td><td align=center>",true);
        output("`^".$row['originalita']."`0");
        output("</td><td>",true);
        output("`^".$row['stile']."`0");
        output("</td><td>",true);
        output("`^".$row['trama']."`0");
        output("</td><td>",true);
        output("`^".$row['voto']."`0");
        output("</td></tr>",true);
        if($session['user']['acctid']==$row['votante'])$votato=true;
    }
    output("</table></div>",true);
    if(!$votato){
        output("<form action='gdr.php?op=add_vote&idbrano=$id' method='POST'>",true);
        addnav("","gdr.php?op=add_vote&idbrano=$id");
        output("`n`b`#Dai voti da 1 a 10 (i voti negativi saranno calcolati zero, quelli superiori a 10 saranno
                 calcolati 10)`b`n`nOriginalità: <input name='ori' size='3'>
                 Stile: <input name='sti' size='3'>
                 Trama: <input name='tra' size='3'>
                 <input type='submit' class='button' value='Aggiungi Voto'>",true);
        output("</form>",true);
    }
}elseif ($_GET['op'] == "add_vote") {
    addnav("B?Elenco Brani", "gdr.php");
    addnav("G?Torna alla Grotta","superuser.php");
    $voto_medio=number_format(( max(0,min(10,$_POST['ori'])) + max(0,min(10,$_POST['tra'])) + max(0,min(10,$_POST['sti'])) )/3,2);
    $sqldr="INSERT INTO gdr_voti (id_brano,votante,originalita,stile,trama,voto)
    VALUES ('".$_GET['idbrano']."','".$session['user']['acctid']."','".max(0,min(10,$_POST['ori']))."','".max(0,min(10,$_POST['sti']))."','".max(0,min(10,$_POST['tra']))."','".$voto_medio."')";
    db_query($sqldr) or die(db_error(LINK));
    output("`c`bIl tuo voto è stato registrato correttamente!`b`c`n");
}elseif ($_GET['op'] == "gdr_chiudi") {
    output("`n`c`b`SPROSEGUI SOLO SE SEI SICURO DI QUELLO CHE STAI FACENDO!!`b`c`n");
    output("`c`b`@E DISTRIBUISCI I PREMI SENZA UTENTI ONLINE!!`b`c`n");
    addnav("G?Torna alla Grotta","superuser.php");
    addnav("B?Elenco Brani", "gdr.php");
    $sql = "SELECT g.*, AVG(v.voto) AS voto_finale, a.name FROM gdr_contest g
            LEFT JOIN accounts a ON a.acctid=g.id
            LEFT JOIN gdr_voti v ON g.id=v.id_brano
            WHERE g.voto='0'
            GROUP BY g.id
            ORDER BY data DESC";
    $result = db_query($sql) or die(sql_error($sql));
    if (db_num_rows($result) != 0){
        $oggi=date("d/m/Y");
        output("<form action='gdr.php?op=gdr_chiudi_sicuro' method='POST'>",true);
        addnav("","gdr.php?op=gdr_chiudi_sicuro");
        output("`bInserisci data chiusura concorso: <input name='data' size='12' value='$oggi'><input type='submit' class='button' value='Conferma chiusura'>",true);
        output("</form>",true);
    }else{
        output("`GNon ci sono brani da premiare !!!!`n");
    }
    //output("<A href='http://www.valuemail.it/online_reg/affirmation.jsp?action=register&errorPage=/index.jsp&gid=40014214&pid=2014&iso_lang=it&iso_country=It&uemail=".$session[user][emailaddress]."&invite_user=vm&agb=1&mime_type_id=0&vm_active=1' target=_blank>`^Valuemail`0</a>",true);
}elseif ($_GET['op'] == "gdr_chiudi_sicuro") {
    addnav("G?Torna alla Grotta","superuser.php");
    addnav("B?Elenco Brani", "gdr.php");

    list($anno, $mese, $giorno) = explode("/", $_POST['data']);
    $oggi =  $giorno."-".$mese."-".$anno;
    output("`c`b$oggi`b`c`n");
    $sql = "SELECT * FROM gdr_contest WHERE voto='0' AND data <= '$oggi' ORDER BY data DESC";
    $result = db_query($sql) or die(sql_error($sql));
    $countrow = db_num_rows($result);
    for ($i=0; $i<$countrow; $i++){
    //for($i=0;$i<db_num_rows($result);$i++){
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
    //for($i=0;$i<db_num_rows($result);$i++){
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
}
page_footer();
?>