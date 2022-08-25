<?php
// TUTTO PREDISPOSTO PER TRANSLATE LUKE

if (isset($_POST['template'])){
    setcookie("template",$_POST['template'],strtotime(date("r")."+45 days"));
    $_COOKIE['template']=$_POST['template'];
}

require_once "common.php";

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
        <a href=\"http://logd.ogsi.it/s1/logd/newfaq.php\" target=\"_blank\">
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
if (getsetting("manutenzione", 3) != 3) {
    output("`c`n`n<big><big><big><big><big>`b`\$SERVER IN MANUTENZIONE - ACCESSO SECONDARIO</big></big>",true);
    output("`n`n`^",true);
    output("Accesso consentito unicamente agli admin dello staff.`b</big></big></big>`n`n`n",true);
    output("`2Inserisci il tuo");output(" `@",true);
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
    output("<form action='loginback.php' method='POST'>"
    .templatereplace("login",array("username"=>"Nome","password"=>"<u>P</u>assword","button"=>"Entra"))
    ."</form>`c",true);
    // Without this, I had one user constantly get 'badnav.php' :/  Everyone else worked, but he didn't
    addnav("","loginback.php");
    //output("`n`b`&**BETA**`0 This is a BETA of this website, things are likely to change now and again, as it is under active development (when I have time ;-)) `&**BETA**`0`n");
    output("`n`b`&",true);
    output(getsetting("loginbanner","*BETA* Questo sito è in fase BETA, le cose cambiano spesso, ed è in costante e attivo sviluppo *BETA*"));
    output("`0`b`n",true);
    /////////////////////////////||||||||||||||||||\\\\\\\\\\\\\\\\\\\\\\\\\\\\
    //    Skin-Wechsel, gesehen bei Version 0.9.8 +, coded von Eliwood

    rawoutput("<form action='index_backdoor.php' method='POST'>");
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
    addnav("Pagina Login Principale", "index.php");
    addnav("OGSI","http://www.ogsi.it");
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
    output("`c`n`n<big><big><big><big><big>`b`\$SERVER NON IN MANUTENZIONE</big></big>",true);
    output("`n`n`^",true);
    output("Utilizzare la pagina di accesso principale.`b`c</big></big></big>`n`n`n`n",true);
    addnav("Pagina Login Principale", "index.php");
    addnav("OGSI","http://www.ogsi.it");
    addnav("Forum del Gioco","http://logd.forumfree.it/");
}
page_footer();
?>
