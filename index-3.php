<?php
// TUTTO PREDISPOSTO PER TRANSLATE LUKE

function casapgmorto($pg, $nome, $casa, $oro, $nomecasa) {
    $sqlf = "SELECT owner,gems FROM items WHERE value1=$casa
    AND class='key'
    AND ( (owner=$pg AND value2=10) OR (owner<>$pg) )
    ORDER BY id ASC";
    $resultf = db_query($sqlf) or die(db_error(LINK));
    $office = db_num_rows($resultf);
    $goldgive = round($oro / $office);
    for ($i=0; $i<$office; $i++){
        $itemf = db_fetch_assoc($resultf);
        if ($pg != $itemf['owner']) {
            $sqlf2 = "UPDATE accounts SET goldinbank=goldinbank+$goldgive,gems=gems+".$itemf['gems']." WHERE acctid = ".$itemf['owner'];
            db_query($sqlf2);
            systemmail($itemf['owner'],"`@Sbattuto Fuori!`0","`&$nome`2 ha venduto la
            Tenuta!`nOra `b$nomecasa`b`2 è in vendita e abbandonata.`nPoichè hai vissuto come
            affittuario che ha contribuito al mantenimento della Tenuta, ricevi `^$goldgive`2 pezzi d'oro
            e le tue `#".$itemf['gems']."`2 gemme dalla cassaforte!");
            $sqllog = "INSERT INTO debuglog VALUES(0,now(),{$itemf['owner']},0,'".addslashes("viene sbattuto
                fuori dalla tenuta $nomecasa messa in vendita da $nome (cancellato) e ottiene $goldgive
                oro e ".$itemf['gems']." gemme per il disturbo")."')";
            db_query($sqllog);
        }
    }
    $sqlf3 = "UPDATE items SET owner = 0, gems = 0, tempo = 0 WHERE class='key' AND value1=$casa";
    db_query($sqlf3);
    $sqlf4 = "UPDATE houses SET owner=0,status=3,gold=60000,gems=90 WHERE houseid=$casa";
    db_query($sqlf4);
}

if (isset($_POST['template'])){
    setcookie("template",$_POST['template'],strtotime(date("r")."+45 days"));
    $_COOKIE['template']=$_POST['template'];
}

require_once "common.php";

// controllo cancellazione account inattivi
$deltatime = 3600; //tempo in secondi per il controllo cancellazione pg (3600 = una volta ogni ora)
$check = $deltatime + getsetting("timepgexpirecheck",0);

if(strtotime("now")>$check) {
    $old = getsetting("expireoldacct",45);
    $new = getsetting("expirenewacct",10);
    $trash = getsetting("expiretrashacct",1);
    
    //primo passaggio, account ordinari

    //Sook, eliminazione delle variabili globali del giocatore inattivo
    //$sql = "SELECT acctid,oggetto,zaino FROM accounts WHERE laston < \"".date("Y-m-d H:i:s",strtotime(date("r")."-$old days"))."\" AND superuser=0";
    //$result = db_query($sql);
    //Sook, eliminazione delle variabili globali del giocatore inattivo
    $sql = "SELECT acctid,name,oggetto,zaino FROM accounts WHERE 1=0 "
    .($old>0?"OR (laston < \"".date("Y-m-d H:i:s",strtotime(date("r")."-$old days"))."\" AND superuser=0) \n":"");
    $result = db_query($sql);
    $countrow = db_num_rows($result);
    for ($i=0; $i<$countrow; $i++){
        $row = db_fetch_assoc($result);
        //Modifica PvP Online
        $sql = "DELETE FROM pvp WHERE acctid2=$row[acctid] OR acctid1=$row[acctid]";
        db_query($sql) or die(db_error(LINK));
        //Annullamento matrimoni
        $sql = "UPDATE accounts SET charisma=0,marriedto=0 WHERE marriedto=$row[acctid]";
        db_query($sql);
        $sql = "UPDATE accounts SET sentnotice=1,marriedto=0 WHERE acctid='$row[acctid]'";
        db_query($sql);
        //Messa in vendita della casa (se proprietario)
        $sql = "UPDATE houses SET owner=0,status=3,gold=60000,gems=90 WHERE owner=$row[acctid] AND status=1";
        db_query($sql);
        $sql = "UPDATE houses SET owner=0,status=4 WHERE owner=$row[acctid] AND status=0";
        db_query($sql);
        //Rimozione chiavi dal gioco
        $sql = "UPDATE items SET owner = 0 WHERE owner=$row[acctid] AND class='Key'";
        db_query($sql);
        //Eliminazione incantesimi di Hatetepe
        //$sql = 'DELETE FROM items WHERE owner='.$row['acctid'].' AND class<>Spell';
         $sql = "DELETE FROM items WHERE owner=$row[acctid] AND class<>'Key'";
        db_query($sql);
        //Eliminazione materiali zaino
        $sql = "DELETE FROM zaino WHERE idplayer = ".$row['acctid'];
        db_query($sql);
        // Drago rimesso in vendita
        $sql = "UPDATE draghi SET user_id = 0 WHERE user_id = '".$row['acctid']."'";
        db_query($sql);
        // Libera Terre dei Draghi occupate
        $sql = "UPDATE terre_draghi SET id_player = 0 WHERE id_player = '".$row['acctid']."'";
        db_query($sql);
        $sql = "DELETE FROM terre_draghi_dove WHERE id_player = '".$row['acctid']."'";
        db_query($sql);
        $sql = "DELETE FROM terre_draghi_exp WHERE id_player = '".$row['acctid']."'";
        db_query($sql);
        $sql = "UPDATE terre_draghi_storia SET conteggiato = 'si' WHERE id_player = '".$row['acctid']."'";
        db_query($sql);
        // Materiali trovati in miniera o foresta
        $sql = "DELETE FROM foresta WHERE acctid = '".$row['acctid']."'";
        db_query($sql);
        $sql = "DELETE FROM miniera WHERE acctid = '".$row['acctid']."'";
        db_query($sql);
        // Eliminazione Riparazioni, Rigenerazioni, Incantesimi, Mercatino
        $sql = "DELETE FROM riparazioni WHERE acctid = '".$row['acctid']."'";
        db_query($sql);
        $sql = "DELETE FROM rigenerazioni WHERE acctid = '".$row['acctid']."'";
        db_query($sql);
        $sql = "DELETE FROM incantesimi WHERE acctid = '".$row['acctid']."'";
        db_query($sql);
        $sql = "DELETE FROM mercatino WHERE acctid = '".$row['acctid']."'";
        db_query($sql);
        // Eliminazione dati Municipio
        $sql = "DELETE FROM municipio_approvazione WHERE acctid = '".$row['acctid']."'";
        db_query($sql);
        // Azzera proprietario pietra
        $sql = "UPDATE pietre SET owner = 0 WHERE owner = '".$row['acctid']."'";
        db_query($sql);
        // Rimessa in vendita oggetti posseduti
        if ($row['oggetto'] != 0){
            $sql = "UPDATE oggetti SET dove = dove_origine WHERE id_oggetti = '".$row['oggetto']."'";
            db_query($sql);
        }
        if ($row['zaino'] != 0){
            $sql = "UPDATE oggetti SET dove = dove_origine WHERE id_oggetti = '".$row['zaino']."'";
            db_query($sql);
        }
        // Eliminazione punizioni e tasse
        $sql = "DELETE FROM punizioni_chiese WHERE acctid = '".$row['acctid']."'";
        db_query($sql);
        $sql = "DELETE FROM punizioni_maghi WHERE acctid = '".$row['acctid']."'";
        db_query($sql);
        $sql = "DELETE FROM tasse WHERE acctid = '".$row['acctid']."'";
        db_query($sql);
        // Eliminazione voodoo
        $sql = "DELETE FROM voodoo WHERE target = '".$row['acctid']."'";
        db_query($sql);
        // Eliminazione caserma
        $sql = "DELETE FROM caserma WHERE acctid = '".$row['acctid']."'";
        db_query($sql);
    
    
        if ($row['acctid']==getsetting("hasegg",0)) savesetting("hasegg","0");
        if ($row['acctid']==getsetting("hasblackegg",0)) savesetting("hasblackegg","0");
    }
    
    //secondo passaggio, account recenti poco sviluppati
    
    $sql = "SELECT acctid,name,oggetto,zaino FROM accounts WHERE 1=0 "
    .($new>0?"OR (laston < \"".date("Y-m-d H:i:s",strtotime(date("r")."-$new days"))."\" AND level=1 AND dragonkills=0 AND reincarna=0 AND superuser=0)\n":"");
    $result = db_query($sql);
    $countrow = db_num_rows($result);
    for ($i=0; $i<$countrow; $i++){
    //for ($i=0;$i<db_num_rows($result);$i++){
        $row = db_fetch_assoc($result);
        //Modifica PvP Online
        $sql = "DELETE FROM pvp WHERE acctid2=$row[acctid] OR acctid1=$row[acctid]";
        db_query($sql) or die(db_error(LINK));
        //Annullamento matrimoni
        $sql = "UPDATE accounts SET charisma=0,marriedto=0 WHERE marriedto=$row[acctid]";
        db_query($sql);
        $sql = "UPDATE accounts SET sentnotice=1,marriedto=0 WHERE acctid='$row[acctid]'";
        db_query($sql);
        //Messa in vendita della casa (se proprietario)
        $sql = "UPDATE houses SET owner=0,status=3 WHERE owner=$row[acctid] AND status=1";
        db_query($sql);
        $sql = "UPDATE houses SET owner=0,status=4 WHERE owner=$row[acctid] AND status=0";
        db_query($sql);
        //Rimozione chiavi dal gioco
        $sql = "UPDATE items SET owner = 0 WHERE owner=$row[acctid] AND class='Key'";
        db_query($sql);
        //Eliminazione incantesimi di Hatetepe
        $sql = "DELETE FROM items WHERE owner=$row[acctid] AND class<>'Key'";
        db_query($sql);
        //Eliminazione materiali zaino
        $sql = "DELETE FROM zaino WHERE idplayer = '".$row['acctid']."'";
        db_query($sql);
        // Drago rimesso in vendita
        $sql = "UPDATE draghi SET user_id = 0 WHERE user_id = '".$row['acctid']."'";
        db_query($sql);
        // Libera Terre dei Draghi occupate
        $sql = "UPDATE terre_draghi SET id_player = 0 WHERE id_player = '".$row['acctid']."'";
        db_query($sql);
        $sql = "DELETE FROM terre_draghi_dove WHERE id_player = '".$row['acctid']."'";
        db_query($sql);
        $sql = "DELETE FROM terre_draghi_exp WHERE id_player = '".$row['acctid']."'";
        db_query($sql);
        $sql = "UPDATE terre_draghi_storia SET conteggiato = 'si' WHERE id_player = '".$row['acctid']."'";
        db_query($sql);
        // Materiali trovati in miniera o foresta
        $sql = "DELETE FROM foresta WHERE acctid = '".$row['acctid']."'";
        db_query($sql);
        $sql = "DELETE FROM miniera WHERE acctid = '".$row['acctid']."'";
        db_query($sql);
        // Eliminazione Riparazioni, Rigenerazioni, Incantesimi, Mercatino
        $sql = "DELETE FROM riparazioni WHERE acctid = '".$row['acctid']."'";
        db_query($sql);
        $sql = "DELETE FROM rigenerazioni WHERE acctid = '".$row['acctid']."'";
        db_query($sql);
        $sql = "DELETE FROM incantesimi WHERE acctid = '".$row['acctid']."'";
        db_query($sql);
        $sql = "DELETE FROM mercatino WHERE acctid = '".$row['acctid']."'";
        db_query($sql);
        // Eliminazione dati Municipio
        $sql = "DELETE FROM municipio_approvazione WHERE acctid = '".$row['acctid']."'";
        db_query($sql);
        // Azzera proprietario pietra
        $sql = "UPDATE pietre SET owner = 0 WHERE owner = '".$row['acctid']."'";
        db_query($sql);
        // Rimessa in vendita oggetti posseduti
        if ($row['oggetto'] != 0){
            $sql = "UPDATE oggetti SET dove = dove_origine WHERE id_oggetti = '".$row['oggetto']."'";
            db_query($sql);
        }
        if ($row['zaino'] != 0){
            $sql = "UPDATE oggetti SET dove = dove_origine WHERE id_oggetti = '".$row['zaino']."'";
            db_query($sql);
        }
        // Eliminazione punizioni e tasse
        $sql = "DELETE FROM punizioni_chiese WHERE acctid = '".$row['acctid']."'";
        db_query($sql);
        $sql = "DELETE FROM punizioni_maghi WHERE acctid = '".$row['acctid']."'";
        db_query($sql);
        $sql = "DELETE FROM tasse WHERE acctid = '".$row['acctid']."'";
        db_query($sql);
        // Eliminazione voodoo
        $sql = "DELETE FROM voodoo WHERE target = '".$row['acctid']."'";
        db_query($sql);
    
        if ($row['acctid']==getsetting("hasegg",0)) savesetting("hasegg","0");
        if ($row['acctid']==getsetting("hasblackegg",0)) savesetting("hasblackegg","0");
    }
    //fine modifica eliminazione record
    
    //echo(strpos($_SERVER['SERVER_NAME'],"logd.mightye.org"));
    $sql = "DELETE FROM accounts WHERE superuser<=1 AND npg=0 AND (1=0\n"
    .($old>0?"OR (laston < \"".date("Y-m-d H:i:s",strtotime(date("r")."-$old days"))."\")\n":"")
    .($new>0?"OR (laston < \"".date("Y-m-d H:i:s",strtotime(date("r")."-$new days"))."\" AND level=1 AND dragonkills=0 AND reincarna=0)\n":"")
    .($trash>0?"OR (laston < \"".date("Y-m-d H:i:s",strtotime(date("r")."-".($trash+1)." days"))."\" AND level=1 AND experience < 10 AND dragonkills=0 AND reincarna=0)\n":"")
    .")"; // Hugues aggiunta where su reincarna per evitare cancellazione pg dopo solo 3 gg di inattività appena reincarnato
    //echo "<pre>".HTMLEntities($sql)."</pre>";
    db_query($sql) or die(db_error(LINK));
    
    $old-=5;
    $sql = "SELECT acctid,login,emailaddress FROM accounts WHERE 1=0 "
    .($old>0?"OR (laston < \"".date("Y-m-d H:i:s",strtotime(date("r")."-$old days"))."\")\n":"")
    ." AND emailaddress!='' AND sentnotice=0";
    $result = db_query($sql);
    $countrow = db_num_rows($result);
    for ($i=0; $i<$countrow; $i++){
    //for ($i=0;$i<db_num_rows($result);$i++){
        $row = db_fetch_assoc($result);
        mail($row[emailaddress],"Scadenza Personaggi Inattivi a LoGD",
        "
        Uno o più dei tuoi personaggi (".$row['login'].") in Legend of the Green Dragon a
        ".$_SERVER['SERVER_NAME'].$_SERVER['SCRIPT_NAME']."
        sta per scadere. Se vuoi mantenere questo personaggio, dovresti
        collegarti con esso al più presto!",
        "From: ".getsetting("gameadminemail","postmaster@localhost.com")
        );
        $sql2 = "UPDATE accounts SET sentnotice='1' WHERE acctid='$row[acctid]'";
        db_query($sql2) or die(db_error(LINK));
    }
    savesetting("timepgexpirecheck",strtotime("now"));
}
// fine controllo cancellazione account inattivi


//echo "<pre>".HTMLEntities($sql)."</pre>";

if ($session[loggedin]){
    redirect("badnav.php");
}
page_header();
$nomedb = getsetting("nomedb","logd");


output("`c`(",true);
output("Benvenuti su Legend of the Green Dragon, basato sul gioco di Seth Able: The legend of the Red Dragon");
output("`n");
if($nomedb=="logd2"){
    output("<big><big><big><big><big>
            <a href=\"http://www.ogsi.it/modules/newbb/viewtopic.php?topic_id=679&forum=17\" target=\"_blank\">
            <b><font color='#FF0000'>LEGGETE IL REGOLAMENTO</font></b></a></big></big></big></big></big>
            <br><font color='#FF0000'>(cliccate sulla scritta)</font><br>",true);
    output("<big><a href=\"http://www.ogsi.it/modules/newbb/viewtopic.php?topic_id=688&forum=17\" target=\"_blank\">
            <b><font color='#ffb400'>PUNIZIONI E BAN</font></b></a></big>`n",true);
}
output("<big><big><big><big><big>
        <a href=\"http://www.ogsi.it/logd/newfaq.php\" target=\"_blank\">
        <b><font color='#33FF33'>LEGGETE LE FAQ !!!</font></b></a></big></big></big></big></big><br>",true);
output("`@A ",true);
output("Rafflingate sono le ore");
output(" `%".getgametime()."`@.`0`n",true);

/* Vecchia routine di generazione prossimo newday
//Next New Day in ... is by JT from logd.dragoncat.net
$time = gametime();
$tomorrow = strtotime(date("Y-m-d H:i:s",$time)." + 1 day");
$tomorrow = strtotime(date("Y-m-d 00:00:00",$tomorrow));
$secstotomorrow = $tomorrow-$time;
$realsecstotomorrow = $secstotomorrow / getsetting("daysperday",4);
output("`#Il prossimo giorno di gioco avrà inizio tra: `$".date("G\\h, i\\m, s\\s \\(\\r\\e\\a\\l\\ \\t\\i\\m\\e\\)",strtotime("1970-01-01 00:00:00 + $realsecstotomorrow seconds"))."`0`n");
*/

$time = gametime();
$tomorrow = mktime(0,0,0,date('m',$time),date('d',$time)+1,date('Y',$time));
$secstotomorrow = $tomorrow-$time;
$realsecstotomorrow = round($secstotomorrow / (int)getsetting("daysperday",4));
//$nextdattime = date("G \\O\\r\\e, i \\M\\i\\n\\u\\t\\i, s \\S\\e\\c\\o\\n\\d\\i\\ \\(\\T\\e\\m\\p\\o \\R\\e\\a\\l\\e\\)",strtotime("1980-01-01 00:00:00 + $realsecstotomorrow seconds"));
output('<div id="index_time">'.$nextdattime.'</div>
<script language="javascript">
var index_time_div = document.getElementById("index_time");
var index_time_day = Math.ceil(24/'.(int)getsetting("daysperday",4).');
var index_dest_time = 0;
function index_act_time()
{
var jetzt = new Date();
var tm = jetzt.getTime();
if( tm > index_dest_time ){
index_dest_time += index_time_day*3600000+ (tm-index_dest_time);
}
var diff = index_dest_time - tm;
var edit = "`# Il prossimo giorno di gioco avrà inizio tra: `(";
var s = Math.floor(diff / 3600000);
diff %= 3600000;
var m = Math.floor(diff / 60000);
diff %= 60000;
var sek = Math.floor(diff / 1000);
index_time_div.innerHTML = edit+"<big><b>"+s+"</b> `# Or"+(s!=1 ? "e":"a")+",`(<b> "+(m<10 ? "0"+m : (m==71 || m==72 ? "<font color=\"#FFFFFF\">"+m+"</font>" : m))+"</b> `# Minut"+(m!=1 ? "i" : "o")+",`( <b>"+(sek<10 ? "0"+sek : sek)+"</b> `# Second"+(sek!=1 ? "i" : "o")+" `#</big>(Tempo Reale)";
window.setTimeout("index_act_time()", 1000);
}
function index_set_time(s,m,sek)
{
if( !index_dest_time ){
var jetzt = new Date();
index_dest_time = jetzt.getTime() + 1000*sek + 60000*m + 3600000*s;
}
window.setTimeout("index_act_time()", 1);
}
if( index_time_div ){
index_set_time('.date('G, i, s',strtotime('1980-01-01 00:00:00 + '.$realsecstotomorrow.' seconds')).');
}
</script>
',true);
//output($str_out,true);


output("`@",true);
output("Il tempo nel regno è");
output("`6 ",true);
output($settings['weather']);
output("`@.`n`c");
//$newplayer=stripslashes(getsetting("newplayer",""));
if ($settings['newplayer'] != ""){
    output("`#`c",true);
    output("Un caloroso benvenuto al nostro player più giovane:");
    output("`^".stripslashes($settings['newplayer'])."`#!`0`n`c",true);
}
//$newdk=stripslashes(getsetting("newdk",""));
if ($settings['newdk'] != ""){
    output("`@`c",true);
    output("L'ultimo eroe del villaggio ad aver ucciso il");
    output(" `b",true);output("Drago Verde");output(" `b ",true);output("è");
    output(" `^".stripslashes($settings['newdk'])."`@!`0`n`c",true);
}
//$torneo=stripslashes(getsetting("torneo",""));
if ($settings['torneo'] != ""){
    output("`#`c",true);
    output("L'attuale campione del");
    output(" `#`b",true);
    output("Torneo");
    output("`b`@ è `^".stripslashes($settings['torneo'])."`#!`0`n`c",true);
}
if ($settings['pigpen'] != ""){
    output("`@`c",true);
    output("L'ultimo abitante del villaggio a fregiarsi del titolo di");
    output(" `\$PigPen `@ ",true);
    output("è");
    output(" `^".stripslashes($settings['pigpen'])."`@!`0`n`c",true);
}
$sql = "SELECT name,sex FROM accounts WHERE evil > 99 AND superuser = 0 ORDER BY evil DESC LIMIT 5";
db_query($sql);
$result = db_query($sql);
if (db_num_rows($result) > 0){
    output("`%`b`c",true);
    output("I 5 peggiori criminali del Villaggio sono:");
    output("`b`n`c",true);
    $countrow = db_num_rows($result);
    for ($i=0; $i<$countrow; $i++){

        $row = db_fetch_assoc($result);
        output("`^`c".stripslashes($row[name])." `\$",true);
        output("è ricercat".($row[sex]?"a":"o")." dallo Sceriffo!");
        output("`0`n`c",true);
    }
}
//Excalibur: modifica per visualizzare compleanni player
$dataodierna = date("m-d");
//$sql = "SELECT name, substring(compleanno,1,4) AS year, substring(compleanno,6,2) AS month, substring(compleanno,9,2) AS day FROM accounts WHERE substring(compleanno,6) = '$dataodierna' ORDER BY name";
$sql = "SELECT name, substring(compleanno,1,4) AS year FROM accounts WHERE substring(compleanno,6) = '$dataodierna' ORDER BY compleanno ASC";
db_query($sql);
$result = db_query($sql);
if (db_num_rows($result) > 0){
    output("`n");
    $countrow = db_num_rows($result);
    for ($i=0; $i<$countrow; $i++){
    //for ($i=0;$i<db_num_rows($result);$i++){
        $row = db_fetch_assoc($result);
        $anni = date("Y") - $row['year'];
        output("<big>`^`c".stripslashes($row['name'])."`# ",true);
        output("compie oggi");
        output(" `^`b$anni`b`# ",true);
        output("anni!");output("`0`n`c</big>",true);
    }
}
//Excalibur: fine compleanni
if (getsetting("manutenzione", 3) == 3) {
    output("`c`n`2",true);
    output("Inserisci il tuo");output(" `@",true);
    output("NOME UTENTE");output(" `2",true);output("e la tua");
    output(" `@",true);output("PASSWORD");output("`2 ",true);
    output("per entrare nel reame.");output("`n",true);
    if ($_GET['op']=="timeout"){
        $session['message'].=" La tua sessione è scaduta, devi rifare il login.`n";
        if (!isset($_COOKIE['PHPSESSID'])){
            $session['message'].=" Inoltre, sembra che tu stia bloccando i cookies di questo sito. Devono essere abilitati almeno i cookies di sessione per usare questo sito.`n";
        }
    }
    if ($_GET['op']=="kicked"){
        $session['message'].=" Sei stato buttato fuori dallo staff.`n";
        if (!isset($_COOKIE['PHPSESSID'])){
            $session['message'].=" Inoltre, sembra che tu stia bloccando i cookies di questo sito. Devono essere abilitati almeno i cookies di sessione per usare questo sito.`n";
        }
    }
    if ($session[message]>"") output("`b`\$$session[message]`b`n");
    output("<form action='login.php' method='POST'>"
    .templatereplace("login",array("username"=>"Nome","password"=>"<u>P</u>assword","button"=>"Entra"))
    ."</form>`c",true);
    // Without this, I had one user constantly get 'badnav.php' :/  Everyone else worked, but he didn't
    addnav("","login.php");
    //output("`n`b`&**BETA**`0 This is a BETA of this website, things are likely to change now and again, as it is under active development (when I have time ;-)) `&**BETA**`0`n");
    output("`n`b`&",true);
    output(getsetting("loginbanner","*BETA* Questo sito è in fase BETA, le cose cambiano spesso, ed è in costante e attivo sviluppo *BETA*"));
    output("`0`b`n",true);
    /////////////////////////////||||||||||||||||||\\\\\\\\\\\\\\\\\\\\\\\\\\\\
    //    Skin-Wechsel, gesehen bei Version 0.9.8 +, coded von Eliwood

    rawoutput("<form action='index.php' method='POST'>");
    rawoutput("<table align='center'><tr><td>");
    $form = array("template"=>"`^Scegli la skin:");
    output("$form[template] <select name='template' size=\"1\">",true);
    if ($handle = @opendir("templates")){
        $skins = array();
        while (false !== ($file = @readdir($handle))){
            if (strpos($file,".htm")>0){
                array_push($skins,$file);
            }
        }
        sort($skins);
        if (count($skins)==0){
            output("`b`@",true);
            output("Argh, gli Admin hanno deciso che non puoi scegliere la skin. Prenditela con loro, non con me.");output("`n",true);
        }else{
            output("<b>Skin:</b><br>",true);
            while (list($key,$val)=each($skins)){
                //if($_COOKIE['template']==$val) $select = "selected='selected'";
                output("<option name='template' $select value='$val'".($_COOKIE['template']==""&&$val=="yarbro.htm" || $_COOKIE['template']==$val?"  selected":"").">".substr($val,0,strpos($val,".htm"))."<br>",true);
            }
        }
    }else{
        output("`c`b`\$",true);output("ERRORE!!!");output("`b`c`&",true);output("Il browser non ha trovato nessuna Skin. Avvisa gli Admin!!");
    }
    rawoutput("</select>");
    rawoutput("</td><td><input type='submit' class='button' value='Scegli'></td>");
    rawoutput("</tr></table></form>");
    //
    ///////////////////////Ende des Skinwechsler\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\
    $session['message']="";
    output("`c`2",true);output("Questo server usa la versione:");output(" `@{$logd_version}`0`c",true);
    clearnav();
    addnav("Nuovo di qui?");
    addnav("Crea un personaggio","create.php");
    addnav("Altro");
    addnav("Informazioni su LoGD","about.php");
    addnav("`b`^Mini FAQ`b","hints.php");
    addnav("`b`(Nuove FAQ`b","newfaq.php");
    addnav("`b`(FAQ Player`b","faqplayer.php");
    if($nomedb!='logd2') addnav("`b`\$REGOLAMENTO`b","regolamento.php");
    addnav("Elenco dei guerrieri","list.php");
    addnav("Notizie Giornaliere","news.php");
    addnav("Info sul Setup del Gioco","about.php?op=setup");
    addnav("LoGD Net","logdnet.php?op=list");
    addnav("Hai dimenticato la password?","create.php?op=forgot");
    if($nomedb!='logd')addnav("Logd server 1","http://logd.ogsi.it/s1/logd/");
    if($nomedb!='logd2')addnav("Logd server 2","http://logd.ogsi.it/s2/logd/");
    addnav("Logd Versione 1.0","http://lotgd.ogsi.it/home.php?");
    addnav("OGSI","http://www.ogsi.it");
    addnav("Termini di utilizzo","termini.php");
    addnav("Forum del Gioco","http://logd.forumfree.it/",false,false,false,true);
    output('`n<center>',true);
    output('<script type="text/javascript" id="cookiebanner"
    src="http://cookiebanner.eu/js/cookiebanner.min.js" ></script>',true);
/*
    output('<script type="text/javascript"><!--
google_ad_client = "pub-8533296456863947";
google_ad_width = 468;
google_ad_height = 60;
google_ad_format = "468x60_as";
google_ad_type = "text_image";
//2007-03-21: Logd_468x60_bottom
google_ad_channel = "0924802386";
google_color_border = "6F3C1B";
google_color_bg = "78B749";
google_color_link = "6F3C1B";
google_color_text = "063E3F";
google_color_url = "000000";
//-->
</script>
<script type="text/javascript"
  src="http://pagead2.googlesyndication.com/pagead/show_ads.js">
</script>`n`n',true);
*/
    output('`n</center>',true);
}else{
    output("`c`n`n<big><big><big><big><big>`b`\$SERVER IN MANUTENZIONE</big></big>",true);
    output("`n`^",true);
    output("Riprovate a collegarvi più tardi.");output("`n",true);
    output("Gli Admin di LoGD stanno lavorando per voi.");
    output("^_^`b`c</big></big></big>",true);
    addnav("OGSI","http://www.ogsi.it");
    addnav("Forum del Gioco","http://logd.forumfree.it/");
}
page_footer();
?>
