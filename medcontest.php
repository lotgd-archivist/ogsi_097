<?php
require_once("common.php");
require_once("common2.php");
checkday();
page_header("Torneo delle Medaglie");
$session['user']['locazione'] = 149;
if ($_GET['op'] == ""){
addnav("Chiudi e resetta il Torneo","medcontest.php?op=reset");
addnav("Premia il Torneo","medcontest.php?op=premia");
addnav("Annulla il Torneo (senza premiare)","medcontest.php?op=annulla");
}
if ($_GET['op'] == "reset"){
    output("`@<font size='+1'>`c`n`nQuesta è l'ultima possibilità che hai. `nOltre questo punto non potrai tornare indietro.`n",true);
    output("`\$Vuoi VERAMENTE terminare l'attuale Gara delle Medaglie ?`c</font>",true);
    addnav("C?`\$Chiudi e resetta il Torneo","medcontest.php?op=resetsicuro");
}
if ($_GET['op'] == "resetsicuro"){
    $sql = "SELECT * FROM medagliepremi ORDER BY medpoints DESC LIMIT 5";
    $result = db_query($sql) or die(db_error(LINK));
    if (db_num_rows($result) == 0) {
        $sql = "INSERT INTO medagliepremi (acctid,medpoints) SELECT acctid,medpoints FROM accounts WHERE medhunt>0 AND superuser = 0";
        $result = db_query($sql) or die(db_error(LINK));
        $sql3 = "UPDATE accounts SET medallion='0', medhunt='0', medpoints='0', medfind='0'";
        db_query($sql3);
    } else {
        output("`n`\$L'ultimo torneo non è ancora stato premiato! Procedere prima con quella premiazione o con l'annullamento di quella gara!!");
    }
}
if ($_GET['op'] == "premia"){
    output("`@<font size='+1'>`c`n`nQuesta è l'ultima possibilità che hai. `nOltre questo punto non potrai tornare indietro.`n",true);
    output("`\$Vuoi VERAMENTE azzerare la Gara delle Medaglie ?`c</font>",true);
    addnav("P?`\$Premia e azzera la gara","medcontest.php?op=sicuro");
    $sql = "SELECT a.name,b.medpoints FROM accounts a, medagliepremi b WHERE b.acctid=a.acctid AND a.superuser = 0 ORDER BY b.medpoints DESC, a.reincarna ASC, a.dragonkills ASC";
    $result = db_query($sql);
    $totalpot=db_num_rows($result)*2;
    $secondplace=round($totalpot*.23);
    $thirdplace=round($totalpot*.17);
    $forthplace=round($totalpot*.12);
    $fifthplace=round($totalpot*.07);
    $firstplace=$totalpot-$secondplace;
    $firstplace=$firstplace-$thirdplace;
    $firstplace=$firstplace-$forthplace;
    $firstplace=$firstplace-$fifthplace;
    output("`n`n`n`3Montepremi Attuale: $totalpot gemme.`n`n");
    output("`^Primo Posto: $firstplace gemme.`n");
    output("`#Secondo Posto: $secondplace gemme.`n");
    output("`@Terzo Posto: $thirdplace gemme.`n");
    output("`3Quarto Posto: $forthplace gemme.`n");
    output("`2Quinto Posto: $fifthplace gemme.`n`n");
    output("`c`bClassifica Attuale:`c`b`n");
    output("<table cellspacing=0 cellpadding=2 align='center'><tr><td>Pos.</td><td align='center'>`bNome`b</td><td align='center'>`bPunteggio`b</td></tr>",true);
    if (db_num_rows($result) == 0) {
        output("<tr><td colspan=4 align='center'>`&Nessun Giocatore ha partecipato all'ultima Gara delle Medaglie.`n`7Forse sono già stati dati i premi dell'ultima gara?`0</td></tr>", true);
    }
    $countrow = db_num_rows($result);
    for ($i=0; $i<$countrow; $i++){
    //for ($i = 0;$i < db_num_rows($result);$i++) {
        $row = db_fetch_assoc($result);
        output("<tr class='".(($i)% 2?"trlight":"trdark")."'><td>".($i+1)."</td><td>`b{$row['name']}`b</td><td align='right'>".$row['medpoints']."</td></tr>", true);
    }
    output("</table>", true);
}
if ($_GET['op'] == "annulla"){
    output("`@<font size='+1'>`c`n`nQuesta è l'ultima possibilità che hai. `nOltre questo punto non potrai tornare indietro.`n",true);
    output("`\$Vuoi VERAMENTE annullare l'ultima Gara delle Medaglie ?`c</font>",true);
    addnav("A?`\$Annulla la gara","medcontest.php?op=annullasicuro");
    $sql = "SELECT a.name,b.medpoints FROM accounts a, medagliepremi b WHERE b.acctid=a.acctid AND a.superuser = 0 ORDER BY b.medpoints DESC, a.reincarna ASC, a.dragonkills ASC";
    $result = db_query($sql);
    $totalpot=db_num_rows($result)*2;
    $secondplace=round($totalpot*.23);
    $thirdplace=round($totalpot*.17);
    $forthplace=round($totalpot*.12);
    $fifthplace=round($totalpot*.07);
    $firstplace=$totalpot-$secondplace;
    $firstplace=$firstplace-$thirdplace;
    $firstplace=$firstplace-$forthplace;
    $firstplace=$firstplace-$fifthplace;
    output("`n`n`n`3Montepremi Attuale: $totalpot gemme.`n`n");
    output("`^Primo Posto: $firstplace gemme.`n");
    output("`#Secondo Posto: $secondplace gemme.`n");
    output("`@Terzo Posto: $thirdplace gemme.`n");
    output("`3Quarto Posto: $forthplace gemme.`n");
    output("`2Quinto Posto: $fifthplace gemme.`n`n");
    output("`c`bClassifica Attuale:`c`b`n");
    output("<table cellspacing=0 cellpadding=2 align='center'><tr><td>Pos.</td><td align='center'>`bNome`b</td><td align='center'>`bPunteggio`b</td></tr>",true);
    if (db_num_rows($result) == 0) {
        output("<tr><td colspan=4 align='center'>`&Nessun Giocatore ha partecipato all'ultima Gara delle Medaglie.`n`7Forse sono già stati dati i premi dell'ultima gara?`0</td></tr>", true);
    }
    $countrow = db_num_rows($result);
    for ($i=0; $i<$countrow; $i++){
    //for ($i = 0;$i < db_num_rows($result);$i++) {
        $row = db_fetch_assoc($result);
        output("<tr class='".(($i)% 2?"trlight":"trdark")."'><td>".($i+1)."</td><td>`b{$row['name']}`b</td><td align='right'>".$row['medpoints']."</td></tr>", true);
    }
    output("</table>", true);
}
if ($_GET['op'] == "sicuro"){
//    $sql = "SELECT medpoints FROM accounts where medhunt>0 AND superuser = 0";
    $sql = "SELECT medpoints FROM medagliepremi";
    $result = db_query($sql) or die(db_error(LINK));
    $totalpot=db_num_rows($result)*2;
    $secondplace=round($totalpot*.23);
    $thirdplace=round($totalpot*.17);
    $forthplace=round($totalpot*.12);
    $fifthplace=round($totalpot*.07);
    $firstplace=$totalpot-$secondplace;
    $firstplace=$firstplace-$thirdplace;
    $firstplace=$firstplace-$forthplace;
    $firstplace=$firstplace-$fifthplace;
    $sql = "SELECT b.acctid,a.name,a.sex,a.gems,a.fama_mod,a.fama3mesi,b.medpoints FROM accounts a, medagliepremi b WHERE b.acctid = a.acctid AND a.superuser = 0 ORDER BY b.medpoints DESC, a.reincarna ASC, a.dragonkills ASC LIMIT 5";
//    $sql = "SELECT acctid,name,sex,gems,medpoints,fama_mod,fama3mesi FROM accounts where medhunt>0 AND superuser = 0 ORDER BY medpoints DESC, reincarna ASC, dragonkills ASC LIMIT 5";
    $result = db_query($sql) or die(db_error(LINK));
    $countrow = db_num_rows($result);
    for ($i=0; $i<$countrow; $i++){
    //for ($i = 0;$i < db_num_rows($result);$i++) {
        $row = db_fetch_assoc($result);
        $account=$row['acctid'];
        if ($i==0){
           //Luke: modifica contatore fama
           $fama = 2000*$row['fama_mod'];
           $famanew = $fama + $row['fama3mesi'];
           //Luke: fine mod fama
           //$gemme=$row['gems']+$firstplace;
           $gemme=$firstplace;
           $message="guadagna $fama fama e $gemme gemme al torneo medaglie (1° Classificato). Adesso ha $famanew punti fama";
           $sqlfama = "INSERT INTO debuglog VALUES(0,now(),$account,0,'".addslashes($message)."')";
           db_query($sqlfama);
           $mailmessage = "`@Hai vinto il Primo Premio del torneo delle Medaglie !!!`nTi sei aggiudicat".($row['sex']?"a":"o")." $firstplace Gemme!!!";
           $mailmessage .= "`nGuadagni anche $fama Punti Fama!!!";
           systemmail($row['acctid'],"`#Complimenti!Hai vinto il Torneo delle Medaglie!",$mailmessage);
           addnews("`#".$row['name']." `%si è classificat".($row['sex']?"a":"o")." al `^1° posto`# nel `@Torneo delle Medaglie`#.`n
           `#{$row['name']} `#ha vinto $firstplace gemme trovando ".$row['medpoints']." medaglie !!");
        }else if ($i==1){
           //Luke: modifica contatore fama
           $fama = 1500*$row['fama_mod'];
           $famanew = $fama + $row['fama3mesi'];
           //Luke: fine mod fama
           //$gemme=$row['gems']+$secondplace;
           $gemme=$secondplace;
           $message="guadagna $fama e $gemme gemme fama al torneo medaglie (2° Classificato). Adesso ha $famanew punti fama";
           $sqlfama = "INSERT INTO debuglog VALUES(0,now(),$account,0,'".addslashes($message)."')";
           db_query($sqlfama);
           $mailmessage = "`@Hai vinto il Secondo Premio del torneo delle Medaglie !!!`nTi sei aggiudicat".($row['sex']?"a":"o")." $secondplace Gemme!!!";
           $mailmessage .= "`nGuadagni anche $fama Punti Fama!!!";
           systemmail($row['acctid'],"`#Complimenti!!Hai vinto al Torneo delle Medaglie!",$mailmessage);
           addnews("`#".$row['name']." `%si è classificat".($row['sex']?"a":"o")." al `^2° posto`# nel `@Torneo delle Medaglie`#.`n
           `#{$row['name']} `#ha vinto $secondplace gemme trovando ".$row['medpoints']." medaglie !!");
        }else if ($i==2){
           //Luke: modifica contatore fama
           $fama = 1000*$row['fama_mod'];
           $famanew = $fama + $row['fama3mesi'];
           //Luke: fine mod fama
           //$gemme=$row['gems']+$thirdplace;
           $gemme=$thirdplace;
           $message="guadagna $fama e $gemme gemme fama al torneo medaglie (3° Classificato). Adesso ha $famanew punti fama";
           $sqlfama = "INSERT INTO debuglog VALUES(0,now(),$account,0,'".addslashes($message)."')";
           db_query($sqlfama);
           $mailmessage = "`@Hai vinto il Terzo Premio del torneo delle Medaglie !!!`nTi sei aggiudicat".($row['sex']?"a":"o")." $thirdplace Gemme!!!";
           $mailmessage .= "`nGuadagni anche $fama Punti Fama!!!";
           systemmail($row['acctid'],"`#Complimenti!!Hai vinto al Torneo delle Medaglie!",$mailmessage);
           addnews("`#".$row['name']." `%si è classificat".($row['sex']?"a":"o")." al `^3° posto`# nel `@Torneo delle Medaglie`#.`n
           `#{$row['name']} `#ha vinto $thirdplace gemme trovando ".$row['medpoints']." medaglie !!");
        }else if ($i==3){
           //Luke: modifica contatore fama
           $fama = 750*$row['fama_mod'];
           $famanew = $fama + $row['fama3mesi'];
           //Luke: fine mod fama
           //$gemme=$row['gems']+$forthplace;
           $gemme=$forthplace;
           $message="guadagna $fama e $gemme gemme fama al torneo medaglie (4° Classificato). Adesso ha $famanew punti fama";
           $sqlfama = "INSERT INTO debuglog VALUES(0,now(),$account,0,'".addslashes($message)."')";
           db_query($sqlfama);
           $mailmessage = "`@Hai vinto il Quarto Premio del torneo delle Medaglie !!!`nTi sei aggiudicat".($row['sex']?"a":"o")." $forthplace Gemme!!!";
           $mailmessage .= "`nGuadagni anche $fama Punti Fama!!!";
           systemmail($row['acctid'],"`#Complimenti!!Hai vinto al Torneo delle Medaglie!",$mailmessage);
           addnews("`#".$row['name']." `%si è classificat".($row['sex']?"a":"o")." al `^4° posto`# nel `@Torneo delle Medaglie`#.`n
           `#{$row['name']} `#ha vinto $forthplace gemme trovando ".$row['medpoints']." medaglie !!");
        }else if ($i==4){
           //Luke: modifica contatore fama
           $fama = 500*$row['fama_mod'];
           $famanew = $fama + $row['fama3mesi'];
           //Luke: fine mod fama
           //$gemme=$row['gems']+$fifthplace;
           $gemme=$fifthplace;
           $message="guadagna $fama e $gemme gemme fama al torneo medaglie (5° Classificato). Adesso ha $famanew punti fama";
           $sqlfama = "INSERT INTO debuglog VALUES(0,now(),$account,0,'".addslashes($message)."')";
           db_query($sqlfama);
           $mailmessage = "`@Hai vinto il Quinto Premio del torneo delle Medaglie !!!`nTi sei aggiudicat".($row['sex']?"a":"o")." $fifthplace Gemme!!!";
           $mailmessage .= "`nGuadagni anche $fama Punti Fama!!!";
           systemmail($row['acctid'],"`#Complimenti!!Hai vinto al Torneo delle Medaglie!",$mailmessage);
           addnews("`#{$row['name']} `%si è classificat".($row['sex']?"a":"o")." al `^5° posto`# nel `@Torneo delle Medaglie`#.`n
           `#".$row['name']." `#ha vinto $fifthplace gemme trovando ".$row['medpoints']." medaglie !!");
        }

        //Sook, vecchia premiazione
        /*
        $sql2 = "UPDATE `accounts` SET `gems` = '$gemme', fama3mesi = '$famanew' WHERE `acctid` = '$account'";
        $result1=db_query($sql2);
        */
        //Sook, nuova premiazione
        assign_to_pg($row['acctid'],'fama',$fama);
        assign_to_pg($row['acctid'],'gems',$gemme);
    }
    //now clear everything
//    $session['user']['medallion']=0;
//    $session['user']['medhunt']=0;
//    $session['user']['medpoints']=0;
//    $session['user']['medfind']=0;
//    $sql3 = "UPDATE accounts SET medallion=0, medhunt=0, medpoints=0, medfind=0";
    $sql3 = "DELETE FROM medagliepremi WHERE 1";
    db_query($sql3);
    if (db_affected_rows()>0){
//        output("`^Righe Modificate: ". db_affected_rows()." !");
        output("`^Righe Cancellate: ". db_affected_rows()." !");
    }else{
//        output("`#Tabella non aggiornata: $sql3");
        output("`#Tabella non resettata: $sql3");
    }
}
if ($_GET['op'] == "annullasicuro"){
    $sql3 = "DELETE FROM medagliepremi WHERE 1";
    db_query($sql3);
    if (db_affected_rows()>0){
        output("`^Righe Cancellate: ". db_affected_rows()." !");
    }else{
        output("`#Tabella non resettata: $sql3");
    }
}
addnav("Torna alla Grotta","superuser.php");
addnav("Torna alla Mondanità","village.php");
page_footer();
?>