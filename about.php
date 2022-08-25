<?php
require_once("common.php");
require_once("common2.php");
page_header("About Legend of the Green Dragon");
$time = (strtotime(date("1981-m-d H:i:s",strtotime(date("r")."-".getsetting("gameoffsetseconds",0)." seconds"))))*getsetting("daysperday",4) % strtotime("1981-01-01 00:00:00");
$time = gametime();
//Excalibur: nuovo codice
$tomorrow = mktime(0,0,0,date('m',$time),date('d',$time)+1,date('Y',$time));
$today = mktime(0,0,0,date('m',$time),date('d',$time),date('Y',$time));
$dayduration = ($tomorrow-$today) / getsetting("daysperday",4);
$secstotomorrow = $tomorrow-$time;
$secssofartoday = $time - $today;
$realsecstotomorrow = round($secstotomorrow / getsetting("daysperday",4),0);
$realsecssofartoday = round($secssofartoday / getsetting("daysperday",4),0);
//Excalibur: fine
/*
$tomorrow = strtotime(date("Y-m-d H:i:s",$time)." + 1 day");
$tomorrow = strtotime(date("Y-m-d 00:00:00",$tomorrow));
$today = strtotime(date("Y-m-d 00:00:00",$time));
$dayduration = ($tomorrow-$today) / getsetting("daysperday",4);
$secstotomorrow = $tomorrow-$time;
$secssofartoday = $time - $today;
$realsecstotomorrow = $secstotomorrow / getsetting("daysperday",4);
$realsecssofartoday = $secssofartoday / getsetting("daysperday",4);
*/
checkday();
if ($_GET[op]==""){
    $order=array("1","2");
    while (list($key,$val)=each($order)){
        switch($val){
        case "2":
            /* NOTICE
             * NOTICE Server admins may put their own information here, please leave the main about body untouched.
             * NOTICE
             */
            output("<hr>",true);
            output("`@Traduzione italiana curata da: Skie, Luke, CMT, Excalibur. Si ringraziano: Robert, LonnyL,
            Sixf00t4, Eth, Spider e tutti gli altri creatori di MOD. Un grazie sentito va ad Aris e Poker per le
            loro idee strampalate che ho tradotto in script. Un grazie va anche a Maximus per la sua preziosa
            collaborazione e per la creazione di alcuni eventi. Ma il grazie più grande va a Luke che ha creduto
            in questo progetto e che si è sbattuto per portarlo avanti e che mi ha insegnato tutto quello che so
            di PHP. `n`nExcalibur");

            break;
        case "1":
            /* NOTICE
             * NOTICE This section may not be modified, please modify the Server Specific section above.
             * NOTICE
             */
            output("`@La Leggenda del Drago Verde`nBy Eric Stevens`n`n");
            output("`cVersione LoGD {$logd_version}`c");
            //This section may not be modified, please modify the Server Specific section above.
            output("MightyE tells you, \"`2Legend of the Green Dragon is my remake of the classic");
            output("BBS Door game, Legend of the Red Dragon (aka LoRD) by Seth Able Robinson.  ");
            output("`n`n`@\"`2LoRD is now owned by Gameport (<a href='http://www.gameport.com/bbs/lord.html'>http://www.gameport.com/bbs/lord.html</a>), and ",true);
            output("they retain exclusive rights to the LoRD name and game.  That's why all content in ");
            //This section may not be modified, please modify the Server Specific section above.
            output("Legend of the Green Dragon is new, with only a very few nods to the original game, such ");
            output("as the buxom barmaid, Violet, and the handsome bard, Seth.`n`n");
            output("`@\"`2Although serious effort was made to preserve the original feel of the game, ");
            output("numerous departures were taken from the original game to enhance playability, and ");
            //This section may not be modified, please modify the Server Specific section above.
            output("to adapt it to the web.`n`n");
            output("`@\"`2LoGD is released under the GNU General Public License (GPL), which essentially means ");
            output("that the source code to the game, and all derivatives of the game must remain open and");
            output("available upon request.`n`n");
            //This section may not be modified, please modify the Server Specific section above.
            output("`@\"`2You can download the latest version of LoGD at <a href='http://sourceforge.net/projects/lotgd' target='_blank'>http://sourceforge.net/projects/lotgd</a>",true);
            output(" and you can play the latest version at <a href='http://lotgd.net/'>http://lotgd.net</a>.`n`n",true);
            output("`@\"`2LoGD is programmed in PHP with a MySQL backend.  It is known to run on Windows and Linux with appropriate
                setups.  Most code has been written by Eric Stevens, with some pieces by other authors (denoted in source at these locations),
                and the code has been released under the
                <a href=\"http://www.gnu.org/copyleft/gpl.html\">GNU General Public License</a>.  Users of the source
                are bound to the terms therein.`@\"`n`n",true);
            //This section may not be modified, please modify the Server Specific section above.
            output("`@\"`2Users of the source are free to view and modify the source, but original copyright information, and
                original text from the about page must be preserved, though they may be added to.`@\"`n`n");
            output("`@\"`2I hope you enjoy the game!`@\"");
            //This section may not be modified, please modify the Server Specific section above.
            break;
        }
    }

    addnav("Game Setup Info","about.php?op=setup");
}elseif($_GET[op]=="setup"){
    addnav("About LoGD","about.php");
    $setup = array(
        "Setup del Gioco,title",
        "pvp"=>"Abilita il PvP,viewonly",
        "pvpday"=>"Combattimenti al giorno,viewonly",
        "pvpimmunity"=>"Giorni in cui i nuovi giocatori sono salvi dal PvP,viewonly",
        "pvpminexp"=>"Quantità di esperienza quando i player diventano attaccabili nel PvP,viewonly",
        "soap"=>"Pulisci i post (filtra il cattivo linguaggio),viewonly",
        "newplayerstartgold"=>"Quantità d'oro con cui inizia un giocatore,viewonly",
        "New Days,title",
        "fightsforinterest"=>"Player deve avere meno di X combattimenti per guadagnare gli interessi,viewonly",
        "maxinterest"=>"Tasso d'Interesse Max (%),viewonly",
        "mininterest"=>"Tasso d'Interesse Min(%),viewonly",
        "daysperday"=>"Giorni di gioco per ogni giorno effettivo,viewonly",
        "specialtybonus"=>"Extra daily uses in specialty area,viewonly",

        "Banca,title",
        "borrowperlevel"=>"Massima quantità che un player può prendere in prestito per livello,viewonly",
        "transferperlevel"=>"Massima quantità che un player può trasferire per lvl del ricevente,viewonly",
        "mintransferlev"=>"Lvl minimo per poter trasferire oro,viewonly",
        "transferreceive"=>"Oro totale che un player può ricevere in un giorno di gioco,viewonly",
        "maxtransferout"=>"Massima quantità che un player può trasferire per livello,viewonly",

        "Taglie,title",
        "bountymin"=>"Quantità minima per livello della taglia,viewonly",
        "bountymax"=>"Quantità massima per livello della taglia,viewonly",
        "bountylevel"=>"Livello minimo per essere obbiettivo della taglia,viewonly",
        "bountyfee"=>"Percentuale trattenuta da Dag Durnick per mettere la taglia,viewonly",
        "maxbounties"=>"Quante taglie può mettere un player al giorno,viewonly",

        "Foresta,title",
        "turns"=>"Combattimenti Foresta al giorno,viewonly",
        "dropmingold"=>"Le Creature della foresta droppano almeno 1/4 dell'oro possibile,viewonly",
        "lowslumlevel"=>"Livello minimo per visitare i Bassifondi,viewonly",

        "Settaggi Mail,title",
        "mailsizelimit"=>"Limite di dimensione per Messaggio,viewonly",
        "inboxlimit"=>"Limita il # di messaggi nella inbox,viewonly",
        "oldmail"=>"Cancella automaticamente i msg vecchi dopo (giorni),viewonly",

        "Termine dei Contenuti,title",
        "expirecontent"=>"Giorni da mantenere i commenti e le news?  (0 for infinite),viewonly",
        "expiretrashacct"=>"Giorni da mantenere gli account che non hanno mai effettualo il login? (0 for infinite),viewonly",
        "expirenewacct"=>"Giorni da mantenere gli account lvl 1 con 0 Dragon Kill? (0 for infinite),viewonly",
        "expireoldacct"=>"Giorni da mantenere tutti gli altri account? (0 for infinite),viewonly",
        "LOGINTIMEOUT"=>"Secondi di inattività prima dell'auto-logoff,viewonly",

        "Informazioni Utili,title",
        "Durata del Giorno: ".round(($dayduration/60/60),0)." ore,viewonly",
        "Ora Attuale del Server: ".date("Y-m-d h:i:s a").",viewonly",
        "Ultimo Nuovo Giorno: ".date("h:i:s a",strtotime(date("r")."-$realsecssofartoday seconds")).",viewonly",
        "Ora attuale di gioco: ".getgametime().",viewonly",
        "Prossimo Nuovo Giorno: ".date("h:i:s a",strtotime(date("r")."+$realsecstotomorrow seconds"))." (".date("H\\h i\\m s\\s",strtotime("1970-01-01 00:00:00 + $realsecstotomorrow seconds"))."),viewonly"
        );

    output("`@<h3>SetUp di questo gioco</h3>`n`n",true);
    //output("<table border=1>",true);
    showform($setup,$settings,true);
    //output("</table>",true);
}elseif($_GET['op'] == "staff"){
page_header("Elenco dello Staff");

//$biolink=get_module_setting("biolink");
$sql = "SELECT name, superuser, sex, laston, loggedin, login FROM accounts WHERE superuser > 0 AND stealth = 0 ORDER BY superuser DESC, acctid ASC";
$result = db_query($sql);
$count = db_num_rows($result);

output("`c`b`@Elenco Staff`0`b`c`n`n");

$hname = "Nome";
$hsex = "Sesso";
$hdesc = "Descrizione";
$hon = "Online";
output("<center>",true);
output("<table border='0' cellpadding='2' cellspacing='1' bgcolor='#999999'>",true);
output("<tr class='trhead'><td>$hname</td><td>$hsex</td>",true);
output("<td>$hon</td>",true);
output("</tr>",true);
for($i=0;$i<$count;$i++){
    $row = db_fetch_assoc($result);
    output("<tr class='".($i%2?"trdark":"trlight")."'><td>",true);
    if ($session['user']['loggedin']) {
        output("<a href='bio.php?char=".rawurlencode($row['login'])."&ret=".urlencode($_SERVER['REQUEST_URI'])."'>",true);
        addnav("","bio.php?char=".rawurlencode($row['login'])."&ret=".urlencode($_SERVER['REQUEST_URI']));
    }
    output("`&".$row['name']."`0");
    if ($session['user']['loggedin']) output("</a>",true);
    output("</td><td>",true);
    output($row['sex']?"`%Femmina`0":"`!Maschio`0");
    output("</td><td align='center'>",true);
    $loggedin=(date("U") - strtotime($row['laston']) < getsetting("LOGINTIMEOUT",900) && $row['loggedin']);
    output($loggedin?"`@Si`0":"`\$No`0");
    output("</td></tr>",true);
}
output("</table>",true);
output("</center>",true);

}else{

}
if ($session['user']['loggedin'] && $_GET['ret1']) {
    addnav("Torna al Villaggio","village.php");
}elseif($session['user']['loggedin']){
    addnav("Torna alle News","news.php");
}else{
    addnav("Elenco Staff","about.php?op=staff");
    addnav("Pagina di Login","index.php");
}
page_footer();
?>