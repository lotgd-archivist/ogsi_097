<?php
/*-------------------------------------------------------------------*/
/* OGSI Logd Project                                                 */
/* (c) 2004-2006 Gianluca Brozzi                                     */
/* Use of this program is governed by Creative Commons Deeds         */
/* If you did not receive a copy of the license:                     */
/* http://creativecommons.org/licenses/by-nc-sa/2.0/it/              */
/* Original Author : Gianluca Brozzi                                 */
/* Queste righe non possono essere modificate                        */
/*-------------------------------------------------------------------*/
/* Prime Maintainer : Luke and Excalibur                             */
/*-------------------------------------------------------------------*/
/* Modifiche al codice vanno aggiunte quì di seguito                 */
/* con anche una piccola descrizione                                 */
/* Data:       Nome:           Modifica:                             */
/*-------------------------------------------------------------------*/


error_reporting(E_ALL);
ini_set('memory_limit', '30M');
set_time_limit(180); // 3 minutes

if(!empty($_SERVER['SERVER_SOFTWARE'])) {
    echo 'Lo schedulatore può essere richiamato solamente da CLI!';
    exit;
}

/* Connessione e selezione del database */
$connessione = mysql_connect("$DB_HOST", "$DB_USER", "$DB_PASS")
or die("Connessione non riuscita: " . mysql_error());
//print "Connesso con successo";
mysql_select_db("$DB_NAME") or die("Selezione del database non riuscita");
//Sook, impostazione orario europeo sul db
mysql_query("set time_zone = '+2:00'");
//Luke: accounts dynamic
//funzione assegnazione premi player
function assign_to_pg($acctid,$type,$amount,$text=null){
    $sql = "INSERT INTO accounts_dynamic (acctid,type,amount,text) VALUES ('$acctid','$type','$amount','$text')";
    db_query($sql) or die(db_error($link));
}
//funzione ritiro premi player
function pickup_to_pg(){
    global $session;
    $sql = "SELECT * FROM accounts_dynamic WHERE acctid = '".$session['user']['acctid']."'";
    $result = db_query($sql) or die(db_error(LINK));
    $countrow = db_num_rows($result);
    for ($i=0; $i<$countrow; $i++){
    //for ($i = 0;$i < db_num_rows($result);$i++) {
        $row = db_fetch_assoc($result);
        if($row['text']!=null)$text=$row['text'];
        if($row['type']=='gold'){
            $session['user']['gold']+=$row['amount'];
            output("`nRicevi $row[amount] Oro.`n$text`n");
        }elseif($row['type']=='gems'){
            $session['user']['gems']+=$row['amount'];
            output("`nRicevi $row[amount] Gemme.`n$text`n");
        }elseif($row['type']=='pc'){
            $session['user']['punti_carriera']+=$row['amount'];
            output("`nRicevi $row[amount] Punti Carriera.`n$text`n");
        }elseif($row['type']=='fama'){
            $session['user']['fama3mesi']+=$row['amount'];
            output("`nRicevi $row[amount] Punti Fama.`n$text`n");
        }else{
            $mailmessage = "`^PG: ".$session['user']['acctid']."`n`^Type: ".$row['type']."`n`^Amount: ".$row['amount']."`n";
            systemmail(1,"`2PROBLEMA PICKUP.`2",$mailmessage);
        }
        $mailmessage = "`^".$row['type'].'  : '.$row['amount']."`n$text`n";
        systemmail($session['user']['acctid'],"`2Ricevi $row[type].`2",$mailmessage);
    }
    $sqlogg = "DELETE FROM accounts_dynamic WHERE acctid = '".$session['user']['acctid']."'";
    db_query($sqlogg) or die(db_error(LINK));
}
function savesetting($settingname,$value){
    global $settings;
    loadsettings();
    if ($value>""){
        if (!isset($settings[$settingname])){
            $sql = "INSERT INTO settings (setting,value) VALUES (\"".addslashes($settingname)."\",\"".addslashes($value)."\")";
        }else{
            $sql = "UPDATE settings SET value=\"".addslashes($value)."\" WHERE setting=\"".addslashes($settingname)."\"";
        }
        db_query($sql) or die(db_error(LINK));
        $settings[$settingname]=$value;
        if (db_affected_rows()>0) return true; else return false;
    }
    return false;
}
function loadsettings(){
    global $settings;
    //as this seems to be a common complaint, examine the execution path of this function,
    //it will only load the settings once per page hit, in subsequent calls to this function,
    //$settings will be an array, thus this function will do nothing.
    if (!is_array($settings)){
        $settings=array();
        $sql = "SELECT * FROM settings";
        $result = db_query($sql) or die(db_error(LINK));
        $countrow = db_num_rows($result);
        for ($i=0; $i<$countrow; $i++){
        //for ($i=0;$i<db_num_rows($result);$i++){
            $row = db_fetch_assoc($result);
            $settings[$row[setting]] = $row[value];
        }
        db_free_result($result);
        $ch=0;
        if ($ch=1 && strpos($_SERVER['SCRIPT_NAME'],"login.php")){
            //@file("http://www.mightye.org/logdserver?".$_SERVER['SERVER_NAME'].$_SERVER['REQUEST_URI']);
        }
    }
}
function getsetting($settingname,$default){
    global $settings;
    loadsettings();
    if (!isset($settings[$settingname])){
        savesetting($settingname,$default);
        return $default;
    }else{
        if (trim($settings[$settingname])=="") $settings[$settingname]=$default;
        return $settings[$settingname];
    }
}

$sql="SELECT dio, COUNT(*) AS player FROM accounts WHERE dio > 0 AND superuser = 0 AND (dragonkills > 4 or reincarna > 0) GROUP BY dio ORDER BY dio ASC";
$result = db_query($sql);
$countrow = db_num_rows($result);
for ($i=0; $i<$countrow; $i++){
//for ($i=0;$i<db_num_rows($result);$i++){
    $row = db_fetch_assoc($result);
    if ($row['dio'] == 1) {
        $partecipantibuoni = $row['player'];
    }elseif ($row['dio'] == 2) {
        $partecipanticattivi = $row['player'];
    }else {
        $partecipantidrago = $row['player'];
    }
}
$puntibuoni = getsetting("puntisgrios",0);
$punticattivi = getsetting("puntikarnak",0);
$puntidrago = getsetting("puntidrago",0);
$buoni = round(($puntibuoni/$partecipantibuoni),2);
$cattivi = round(($punticattivi/$partecipanticattivi),2);
$drago = round(($puntidrago/$partecipantidrago),2);
$zero = 0;
savesetting("puntikarnak","0");
savesetting("puntisgrios","0");
savesetting("puntidrago","0");
$differenzabc = $buoni - $cattivi;
$differenzabd = $buoni - $drago;
$differenzacd = $cattivi - $drago;
savesetting("datachiesavittoria",time()); // setta ora vittoria
if ($differenzabc > 0 AND $differenzabd > 0) {
    savesetting("chiesavincente", "sgrios");
}elseif ($differenzabc < 0 AND $differenzacd > 0){
    savesetting("chiesavincente", "karnak");
}elseif ($differenzabd < 0 AND $differenzacd < 0){
    savesetting("chiesavincente", "drago");
} else {
    savesetting("chiesavincente", "nessuno");
}

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
        assign_to_pg($row[acctid],'fama',$famanew);
        assign_to_pg($row[acctid],'gems',$premiogemme,'Premio sfida sette');
        $mailmessage = "`3I seguaci della chiesa di `^Sgrios`3 hanno totalizzato $differenzabc punti carriera per player in più dei `\$Karnakiani`3 e
                      $differenzabd punti carriera per player in più dei `@Dragoni`3.`n";
        $mailmessage .= "Ti sei aggiudicato, quale fedele di `^Sgrios`@, `b`^$premiogemme`b`@ gemme di premio !!`n";
        $mailmessage .= "Guadagni anche $fama Punti Fama!!!";
        systemmail($row['acctid'],"`2Complimenti, questo mese `^Sgrios`2 ha prevalso.",$mailmessage);
    }
}else if ($vincente == "karnak") {
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
        //Luke: fine mod fama
        //Nuova funzione
        /*
        $sqlpu = "UPDATE accounts SET gems = gems + $premiogemme, fama3mesi = $famanew WHERE acctid = '{$row[acctid]}' ";
        $resultpu = db_query($sqlpu) or die(db_error(LINK));
        */
        assign_to_pg($row[acctid],'fama',$famanew);
        assign_to_pg($row[acctid],'gems',$premiogemme,'Premio sfida sette');
        $mailmessage = "`3I seguaci della setta di `\$Karnak`3 hanno totalizzato $differenzabc punti carriera per player in più degli `^Sgriossini`3
                       e $differenzacd punti carriera per player in più dei `@Dragoni`3.`n";
        $mailmessage .= "`@Ti sei aggiudicato, quale seguace di `\$Karnak`@, `b`^$premiogemme`b`@ gemme di premio !!`n";
        $mailmessage .= "Guadagni anche $fama Punti Fama!!!";
        systemmail($row['acctid'],"`2Complimenti, questo mese `\$Karnak`2 ha prevalso.",$mailmessage);
    }
}else if ($vincente == "drago") {
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
        //Luke: fine mod fama
        //Nuova funzione
        /*
        $sqlpu = "UPDATE accounts SET gems = gems + $premiogemme, fama3mesi = $famanew WHERE acctid = '{$row[acctid]}' ";
        $resultpu = db_query($sqlpu) or die(db_error(LINK));
        */
        assign_to_pg($row[acctid],'fama',$famanew);
        assign_to_pg($row[acctid],'gems',$premiogemme,'Premio sfida sette');
        $mailmessage = "`2I seguaci della setta del `@Drago Verde`2 hanno totalizzato $differenzabd punti carriera per player in più degli `^Sgrissini`2
                      e $differenzacd punti carriera per player in più dei `\$Karnakkiani`2.`n";
        $mailmessage .= "Ti sei aggiudicato, quale seguace del `@Drago Verde`2, `b`^$premiogemme`b`2 gemme di premio !!`n";
        $mailmessage .= "Guadagni anche $fama Punti Fama!!!";
        systemmail($row['acctid'],"`2Complimenti, questo mese il `@Drago Verde`2 ha prevalso.",$mailmessage);
    }
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
}
?>