<?php
require_once("common.php");
require_once("common2.php");
page_header("Premi Lotta Sette");
if ($_GET['op']==""){
$sql="SELECT dio, COUNT(*) AS player FROM accounts WHERE dio > 0 AND superuser = 0 AND (dragonkills >= ".getsetting("dk_sette",2)." OR reincarna > 0) GROUP BY dio ORDER BY dio ASC";
$result = db_query($sql);
$countrow = db_num_rows($result);
for ($i=0; $i<$countrow; $i++){
//for ($i=0;$i<db_num_rows($result);$i++){
    $row = db_fetch_assoc($result);
    if ($row['dio'] == 1) {
       $partecipantibuoni = $row['player'];
    }elseif ($row['dio'] == 2) {
       $partecipanticattivi = $row['player'];
    }elseif ($row['dio'] == 3) {
       $partecipantidrago = $row['player'];
    }
}
$puntibuoni = getsetting("puntisgriosfinemese",0);
$punticattivi = getsetting("puntikarnakfinemese",0);
$puntidrago = getsetting("puntidragofinemese",0);
$buoni = round(($puntibuoni/$partecipantibuoni),2);
$cattivi = round(($punticattivi/$partecipanticattivi),2);
$drago = round(($puntidrago/$partecipantidrago),2);
$differenzabc = $buoni - $cattivi;
$differenzabd = $buoni - $drago;
$differenzacd = $cattivi - $drago;
    output("`c`b`&La guerra delle Sette`b`c`n");
    output("<table cellspacing=4 cellpadding=2 align='center'>", true);
    output("<tr><td></td><td bgcolor='#222255'>`b`^<big><big>Fedeli di Sgrios</big></big>`b</td>&nbsp;
            <td bgcolor='#225555'>`b`\$<big><big>Seguaci di Karnak</big></big>`b</td>&nbsp;
            <td bgcolor='#662266'>`b`@<big><big>Adepti del Drago</big></big>`b</td></tr>", true);
    output("<tr><td align='center' bgcolor='#33CC33'><big><big><big>`!Punti Totali</big></big></big></td>
            <td align='center' bgcolor='#222288'>`b`6<big><big><big>$puntibuoni</big></big></big>`b</td>&nbsp;
            <td align='center' bgcolor='#228888'>`b`4<big><big><big>$punticattivi</big></big></big>`b</td>&nbsp;
            <td align='center' bgcolor='#882288'>`b`2<big><big><big>$puntidrago</big></big></big>`b</td></tr>", true);
    output("<tr><td align='center' bgcolor='#33CC33'><big><big><big>`!N.Player</big></big></big></td>&nbsp;
            <td align='center' bgcolor='#222288'>`b`6<big><big><big>$partecipantibuoni</big></big></big>`b</td>&nbsp;
            <td align='center' bgcolor='#228888'>`b`4<big><big><big>$partecipanticattivi</big></big></big>`b</td>&nbsp;
            <td align='center' bgcolor='#882288'>`b`2<big><big><big>$partecipantidrago</big></big></big>`b</td></tr>",true);
    output("<tr><td align='center' bgcolor='#33CC33'><big><big><big>`!Punti/Player</big></big></big></td>&nbsp;
            <td align='center' bgcolor='#222288'>`b`6<big><big><big>$buoni</big></big></big>`b</td>&nbsp;
            <td align='center' bgcolor='#228888'>`b`4<big><big><big>$cattivi</big></big></big>`b</td>&nbsp;
            <td align='center' bgcolor='#882288'>`b`2<big><big><big>$drago</big></big></big>`b</td></tr>",true);
    output("<tr><td></td><td align='center' bgcolor='#222288'>`b`6<big><big><big>",true);
    if ($differenzabc > 0 AND $differenzabd > 0) {
       output("Vinto</big></big></big>`b</td>&nbsp;
               <td align='center' bgcolor='#228888'>`b`4<big><big><big>Perso</big></big></big>`b</td>&nbsp;
               <td align='center' bgcolor='#882288'>`b`2<big><big><big>Perso</big></big></big>`b</td>",true);
    }elseif ($differenzabc < 0 AND $differenzacd > 0){
       output("Perso</big></big></big>`b</td>&nbsp;
               <td align='center' bgcolor='#228888'>`b`4<big><big><big>Vinto</big></big></big>`b</td>&nbsp;
               <td align='center' bgcolor='#882288'>`b`2<big><big><big>Perso</big></big></big>`b</td>",true);
    }elseif ($differenzabd < 0 AND $differenzacd < 0){
       output("Perso</big></big></big>`b</td>&nbsp;
               <td align='center' bgcolor='#228888'>`b`4<big><big><big>Perso</big></big></big>`b</td>&nbsp;
               <td align='center' bgcolor='#882288'>`b`2<big><big><big>Vinto</big></big></big>`b</td>",true);
    } else {
       output("Pari</big></big></big>`b</td>&nbsp;
               <td align='center' bgcolor='#228888'>`b`4<big><big><big>Pari</big></big></big>`b</td>&nbsp;
               <td align='center' bgcolor='#882288'>`b`2<big><big><big>Pari</big></big></big>`b</td>",true);
    }
    output("</tr></table>", true);


    output("`\$`n`n`c<font size='+1'>ATTENZIONE !!! Stai per distribuire i premi per la lotta tra le sette.`n",true);
    output("Sei sicuro al 100% di volerlo fare ?</font>`c",true);
    addnav("G?`#Torna alla Grotta","superuser.php");
    addnav("M?`@Torna alla Mondanità","village.php");
    addnav("C?`^Consegna Premi","premiochiesa.php?op=verifica");
    addnav("T?`(Termina la gara attuale","premiochiesa.php?op=termina");
    addnav("A?`\$Annulla l'ultima gara","premiochiesa.php?op=annulla");
}
if ($_GET['op']=="verifica"){
    output("`@<font size='+1'>`c`n`nQuesta è l'ultima possibilità che hai. `nOltre questo punto non potrai tornare indietro.`n",true);
    output("`\$Vuoi VERAMANTE distribuire i premi per la lotta tra le sette ?`c</font>",true);
    addnav("G?`#Torna alla Grotta","superuser.php");
    addnav("M?`@Torna alla Mondanità","village.php");
    addnav("P?`\$Distribuisci i Premi","premiochiesa.php?op=sicuro");
}
if ($_GET['op']=="termina"){
    output("`@<font size='+1'>`c`n`nQuesta è l'ultima possibilità che hai. `nOltre questo punto non potrai tornare indietro.`n",true);
    output("`\$Vuoi VERAMANTE terminare la sfida tra le sette in corso ?`n`@(la garà verrà chiusa e saranno salvati i punteggi attuali per poter successivamente premiare)`c</font>",true);
    addnav("G?`#Torna alla Grotta","superuser.php");
    addnav("M?`@Torna alla Mondanità","village.php");
    addnav("T?`\$Termina la gara","premiochiesa.php?op=terminasicuro");
}
if ($_GET['op']=="annulla"){
    output("`@<font size='+1'>`c`n`nQuesta è l'ultima possibilità che hai. `nOltre questo punto non potrai tornare indietro.`n",true);
    output("`\$Vuoi VERAMANTE annullare l'ultima lotta tra le sette ?`n`@(i punteggi verranno cancellati e non sarà distribuito il premio)`c</font>",true);
    addnav("G?`#Torna alla Grotta","superuser.php");
    addnav("M?`@Torna alla Mondanità","village.php");
    addnav("A?`\$Annulla la gara","premiochiesa.php?op=annullasicuro");
}
if ($_GET['op']=="terminasicuro"){
addnav("G?`#Torna alla Grotta","superuser.php");
addnav("M?`@Torna alla Mondanità","village.php");
    savesetting("puntisgriosfinemese", getsetting("puntisgrios",0));
    savesetting("puntikarnakfinemese", getsetting("puntikarnak",0));
    savesetting("puntidragofinemese", getsetting("puntidrago",0));
    savesetting("puntisgrios", "0");
    savesetting("puntikarnak", "0");
    savesetting("puntidrago", "0");
    output("La Sfida delle Sette è stata chiusa, i punteggi sono stati salvati e riazzerati. `nSi può procedere con la premiazione");
}
if ($_GET['op']=="annullasicuro"){
addnav("G?`#Torna alla Grotta","superuser.php");
addnav("M?`@Torna alla Mondanità","village.php");
    savesetting("puntisgriosfinemese", "0");
    savesetting("puntikarnakfinemese", "0");
    savesetting("puntidragofinemese", "0");
    output("La Sfida delle Sette è stata annullata!`n");
    $sql="SELECT acctid FROM accounts WHERE dio > 0 AND superuser = 0";
    $result = db_query($sql);
    $countrow = db_num_rows($result);
    for ($i=0; $i<$countrow; $i++){
    //for ($i=0;$i<db_num_rows($result);$i++){
        $row = db_fetch_assoc($result);
        $mailmessage = "`3Gli Admin hanno deciso di annullare l'ultima Sfida delle Sette. Nessun premio è stato distribuito!";
        systemmail($row['acctid'],"`#Sfida delle sette annullata!",$mailmessage);
    }
    output("`3Mail inviate ai fedeli di `^Sgrios`3, ai seguaci di `\$Karnak`3 e agli accoliti del `@Drago Verde`3. Nessun premio distribuito !`n`n");
}
if ($_GET['op']=="sicuro"){
addnav("G?`#Torna alla Grotta","superuser.php");
addnav("M?`@Torna alla Mondanità","village.php");
//$puntioldbuoni = getsetting("puntibuoni", 0);
//$puntioldcattivi = getsetting("punticattivi", 0);
$sql="SELECT dio, COUNT(*) AS player FROM accounts WHERE dio > 0 AND superuser = 0 AND (dragonkills >= ".getsetting("dk_sette",2)." OR reincarna > 0) GROUP BY dio ORDER BY dio ASC";
$result = db_query($sql);
$countrow = db_num_rows($result);
for ($i=0; $i<$countrow; $i++){
//for ($i=0;$i<db_num_rows($result);$i++){
    $row = db_fetch_assoc($result);
    if ($row['dio'] == 1) {
       $partecipantibuoni = $row['player'];
    }elseif ($row['dio'] == 2) {
       $partecipanticattivi = $row['player'];
    }elseif ($row['dio'] == 3) {
       $partecipantidrago = $row['player'];
    }
}
$puntibuoni = getsetting("puntisgriosfinemese",0);
$punticattivi = getsetting("puntikarnakfinemese",0);
$puntidrago = getsetting("puntidragofinemese",0);
$buoni = round(($puntibuoni/$partecipantibuoni),2);
$cattivi = round(($punticattivi/$partecipanticattivi),2);
$drago = round(($puntidrago/$partecipantidrago),2);
$zero = 0;
savesetting("puntikarnakfinemese","0");
savesetting("puntisgriosfinemese","0");
savesetting("puntidragofinemese","0");
$differenzabc = $buoni - $cattivi;
$differenzabd = $buoni - $drago;
$differenzacd = $cattivi - $drago;

    output("`c`b`&La guerra delle Sette`b`c`n");
    output("<table cellspacing=4 cellpadding=2 align='center'>", true);
    output("<tr><td></td><td bgcolor='#222255'>`b`^<big><big>Fedeli di Sgrios</big></big>`b</td>&nbsp;
            <td bgcolor='#225555'>`b`\$<big><big>Seguaci di Karnak</big></big>`b</td>&nbsp;
            <td bgcolor='#662266'>`b`@<big><big>Adepti del Drago</big></big>`b</td></tr>", true);
    output("<tr><td align='center' bgcolor='#33CC33'><big><big><big>`!Punti Totali</big></big></big></td>
            <td align='center' bgcolor='#222288'>`b`6<big><big><big>$puntibuoni</big></big></big>`b</td>&nbsp;
            <td align='center' bgcolor='#228888'>`b`4<big><big><big>$punticattivi</big></big></big>`b</td>&nbsp;
            <td align='center' bgcolor='#882288'>`b`2<big><big><big>$puntidrago</big></big></big>`b</td></tr>", true);
    output("<tr><td align='center' bgcolor='#33CC33'><big><big><big>`!N.Player</big></big></big></td>&nbsp;
            <td align='center' bgcolor='#222288'>`b`6<big><big><big>$partecipantibuoni</big></big></big>`b</td>&nbsp;
            <td align='center' bgcolor='#228888'>`b`4<big><big><big>$partecipanticattivi</big></big></big>`b</td>&nbsp;
            <td align='center' bgcolor='#882288'>`b`2<big><big><big>$partecipantidrago</big></big></big>`b</td></tr>",true);
    output("<tr><td align='center' bgcolor='#33CC33'><big><big><big>`!Punti/Player</big></big></big></td>&nbsp;
            <td align='center' bgcolor='#222288'>`b`6<big><big><big>$buoni</big></big></big>`b</td>&nbsp;
            <td align='center' bgcolor='#228888'>`b`4<big><big><big>$cattivi</big></big></big>`b</td>&nbsp;
            <td align='center' bgcolor='#882288'>`b`2<big><big><big>$drago</big></big></big>`b</td></tr>",true);
    output("<tr><td></td><td align='center' bgcolor='#222288'>`b`6<big><big><big>",true);
    savesetting("datachiesavittoria",time()); // setta ora vittoria
    if ($differenzabc > 0 AND $differenzabd > 0) {
       output("Vinto</big></big></big>`b</td>&nbsp;
               <td align='center' bgcolor='#228888'>`b`4<big><big><big>Perso</big></big></big>`b</td>&nbsp;
               <td align='center' bgcolor='#882288'>`b`2<big><big><big>Perso</big></big></big>`b</td>",true);
       savesetting("chiesavincente", "sgrios");
    }elseif ($differenzabc < 0 AND $differenzacd > 0){
       output("Perso</big></big></big>`b</td>&nbsp;
               <td align='center' bgcolor='#228888'>`b`4<big><big><big>Vinto</big></big></big>`b</td>&nbsp;
               <td align='center' bgcolor='#882288'>`b`2<big><big><big>Perso</big></big></big>`b</td>",true);
       savesetting("chiesavincente", "karnak");
    }elseif ($differenzabd < 0 AND $differenzacd < 0){
       output("Perso</big></big></big>`b</td>&nbsp;
               <td align='center' bgcolor='#228888'>`b`4<big><big><big>Perso</big></big></big>`b</td>&nbsp;
               <td align='center' bgcolor='#882288'>`b`2<big><big><big>Vinto</big></big></big>`b</td>",true);
       savesetting("chiesavincente", "drago");
    } else {
       output("Pari</big></big></big>`b</td>&nbsp;
               <td align='center' bgcolor='#228888'>`b`4<big><big><big>Pari</big></big></big>`b</td>&nbsp;
               <td align='center' bgcolor='#882288'>`b`2<big><big><big>Pari</big></big></big>`b</td>",true);
       savesetting("chiesavincente", "nessuno");
    }
    output("</tr></table>", true);

if ($differenzabc < 0) $differenzabc *= -1;
if ($differenzabd < 0) $differenzabd *= -1;
if ($differenzacd < 0) $differenzacd *= -1;
$vincente = getsetting("chiesavincente","");
if ($vincente == "sgrios") {
   $premiogemme = intval($buoni/250);
   if ($premiogemme < 5) $premiogemme = 5;
   if ($premiogemme > 30) $premiogemme = 30;
   $sql="SELECT acctid,gems,fama_mod,fama3mesi FROM accounts WHERE dio = 1 AND superuser = 0";
   $result = db_query($sql);
   $countrow = db_num_rows($result);
   for ($i=0; $i<$countrow; $i++){
   //for ($i=0;$i<db_num_rows($result);$i++){
       $row = db_fetch_assoc($result);
       //Luke: modifica contatore fama
       $account=$row['acctid'];
       $fama = 500*$row['fama_mod'];
       $famanew = $fama + $row['fama3mesi'];
       $message="guadagna $fama fama alla guerra delle sette. Adesso ha $famanew punti fama";
       $sqlfama = "INSERT INTO debuglog VALUES(0,now(),$account,0,'".addslashes($message)."')";
       db_query($sqlfama) or die(db_error(LINK));
       //Luke: fine mod fama
       //Nuova funzione
       /*
       $sqlpu = "UPDATE accounts SET gems = gems + $premiogemme, fama3mesi = $famanew WHERE acctid = '{$row[acctid]}' ";
       $resultpu = db_query($sqlpu) or die(db_error(LINK));
        */
       assign_to_pg($row[acctid],'fama',$fama);
       assign_to_pg($row[acctid],'gems',$premiogemme);
       $mailmessage = "`3I seguaci della chiesa di `^Sgrios`3 hanno totalizzato $differenzabc punti carriera per player in più dei `\$Karnakiani`3 e ";
       $mailmessage .= "$differenzabd punti carriera per player in più dei `@Dragoni`3.`n";
       $mailmessage .= "Ti sei aggiudicato, quale fedele di `^Sgrios`@, `b`^$premiogemme`b`@ gemme di premio !!`n";
       $mailmessage .= "Guadagni anche $fama Punti Fama!!!";
       systemmail($row['acctid'],"`2Complimenti, questo mese `^Sgrios`2 ha prevalso.",$mailmessage);
   }
   output("Mail inviate e premi distribuiti ai fedeli di Sgrios.`n`n");
}elseif ($vincente == "karnak") {
   $premiogemme = intval($cattivi/250);
   if ($premiogemme < 5) $premiogemme = 5;
   if ($premiogemme > 30) $premiogemme = 30;
   $sql="SELECT acctid,gems,fama_mod,fama3mesi FROM accounts WHERE dio = 2 AND superuser = 0";
   $result = db_query($sql);
   $countrow = db_num_rows($result);
   for ($i=0; $i<$countrow; $i++){
   //for ($i=0;$i<db_num_rows($result);$i++){
       $row = db_fetch_assoc($result);
       //Luke: modifica contatore fama
       $account=$row['acctid'];
       $fama = 500*$row['fama_mod'];
       $famanew = $fama + $row['fama3mesi'];
       $message="guadagna $fama fama alla guerra delle sette. Adesso ha $famanew punti fama";
       $sqlfama = "INSERT INTO debuglog VALUES(0,now(),$account,0,'".addslashes($message)."')";
       db_query($sqlfama) or die(db_error(LINK));
       //Luke: fine mod fama
       //Nuova funzione
       /*
       $sqlpu = "UPDATE accounts SET gems = gems + $premiogemme, fama3mesi = $famanew WHERE acctid = '{$row[acctid]}' ";
       $resultpu = db_query($sqlpu) or die(db_error(LINK));
        */
       assign_to_pg($row[acctid],'fama',$fama);
       assign_to_pg($row[acctid],'gems',$premiogemme);
       $mailmessage = "`3I seguaci della setta di `\$Karnak`3 hanno totalizzato $differenzabc punti carriera per player in più degli `^Sgriossini`3 e ";
       $mailmessage .= "$differenzacd punti carriera per player in più dei `@Dragoni`3.`n";
       $mailmessage .= "`@Ti sei aggiudicato, quale seguace di `\$Karnak`@, `b`^$premiogemme`b`@ gemme di premio !!`n";
       $mailmessage .= "Guadagni anche $fama Punti Fama!!!";
       systemmail($row['acctid'],"`2Complimenti, questo mese `\$Karnak`2 ha prevalso.",$mailmessage);
   }
   output("Mail inviate e premi distribuiti ai seguaci di Karnak.`n`n");
}elseif ($vincente == "drago") {
   $premiogemme = intval($drago/250);
   if ($premiogemme < 5) $premiogemme = 5;
   if ($premiogemme > 30) $premiogemme = 30;
   $sql="SELECT acctid,gems,fama_mod,fama3mesi FROM accounts WHERE dio = 3 AND superuser = 0";
   $result = db_query($sql);
   $countrow = db_num_rows($result);
   for ($i=0; $i<$countrow; $i++){
   //for ($i=0;$i<db_num_rows($result);$i++){
       $row = db_fetch_assoc($result);
       //Luke: modifica contatore fama
       $account=$row['acctid'];
       $fama = 500*$row['fama_mod'];
       $famanew = $fama + $row['fama3mesi'];
       $message="guadagna $fama fama alla guerra delle sette. Adesso ha $famanew punti fama";
       $sqlfama = "INSERT INTO debuglog VALUES(0,now(),$account,0,'".addslashes($message)."')";
       db_query($sqlfama) or die(db_error(LINK));
       //Luke: fine mod fama
       //Nuova funzione
       /*
       $sqlpu = "UPDATE accounts SET gems = gems + $premiogemme, fama3mesi = $famanew WHERE acctid = '{$row[acctid]}' ";
       $resultpu = db_query($sqlpu) or die(db_error(LINK));
       */
       assign_to_pg($row[acctid],'fama',$fama);
       assign_to_pg($row[acctid],'gems',$premiogemme);
       $mailmessage = "`2I seguaci della setta del `@Drago Verde`2 hanno totalizzato $differenzabd punti carriera per player in più degli `^Sgriossini`2 e ";
       $mailmessage .= "$differenzacd punti carriera per player in più dei `\$Karnakkiani`2.`n";
       $mailmessage .= "Ti sei aggiudicato, quale seguace del `@Drago Verde`2, `b`^$premiogemme`b`2 gemme di premio !!`n";
       $mailmessage .= "Guadagni anche $fama Punti Fama!!!";
       systemmail($row['acctid'],"`2Complimenti, questo mese il `@Drago Verde`2 ha prevalso.",$mailmessage);
   }
   output("Mail inviate e premi distribuiti agli adepti del Drago.`n`n");
}else{
// Caso di Parità ;-)
   $sql="SELECT acctid FROM accounts WHERE dio > 0 AND superuser = 0";
   $result = db_query($sql);
   $countrow = db_num_rows($result);
   for ($i=0; $i<$countrow; $i++){
   //for ($i=0;$i<db_num_rows($result);$i++){
       $row = db_fetch_assoc($result);
       $mailmessage = "`3I seguaci della setta di `\$Karnak`3, della chiesa di `^Sgrios`3 e della setta del `@Drago Verde`3 hanno totalizzato `&$buoni`@ punti carriera per player!!! ";
       $mailmessage .= "Per cui ne i seguaci di `\$Karnak`3, ne i fedeli di `^Sgrios`3 ne gli accoliti del `@Drago Verde`3 vincono nulla !!";
       systemmail($row['acctid'],"`#Incredibile, le sette hanno pareggiato!!",$mailmessage);
   }
   output("`3Mail inviate ai fedeli di `^Sgrios`3, ai seguaci di `\$Karnak`3 e agli accoliti del `@Drago Verde`3. Nessun premio distribuito !`n`n");
}
}

page_footer();
?>