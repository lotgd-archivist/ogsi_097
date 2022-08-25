<?php
/*
Medallion Hunt
Author Lonny Luberts
version 1.0
July 2004

Mysql inclusions
ALTER TABLE accounts ADD `medhunt` int(11) NOT NULL default '0'
ALTER TABLE accounts ADD `medallion` int(11) NOT NULL default '0'
ALTER TABLE accounts ADD `medpoints` int(11) NOT NULL default '0'
ALTER TABLE accounts ADD `medfind` int(11) NOT NULL default '0'

in common.php before $u['hitpoints']=round($u['hitpoints'],0);
add
        //begin medallion meter
        for ($i=0;$i<6;$i+=1){
            if ($session['user']['medallion']>$i){
                $medallion.="<img src=\"./images/medallion.gif\" title=\"\" alt=\"\" style=\"width: 14px; height: 16px;\">";
            }else{
                $medallion.="<img src=\"./images/medallionclear.gif\" title=\"\" alt=\"\" style=\"width: 14px; height: 16px;\">";
            }
        }
        //end medallion meter

now you are going to have to break up your stats....
can be after
    .templatereplace("statrow",array("title"=>"Gems","value"=>$u['gems']))
but you can place it elsewhere

add
    ,true);
        if ($session[user][medhunt]==1){
            $charstat.=appoencode(
            templatereplace("statrow",array("title"=>"Medallions","value"=>$medallion))
            ,true);
        }
        $charstat.=appoencode(

now take out the , before templatereplace on the next line

after function checkday($CheckIfDead=FALSE) {

add
if (e_rand(1,100)>(100-$session[user][medfind]) and $session[user][alive]==1){
        if ($session[user][medhunt]==1 and $session[user][medfind]>0){
            if ($session[user][medallion]<5){
                output("`c`b`4<big><big><big><big>You Found a Medallion!</big></big></big></big>`b`c",true);
                $session[user][medallion]+=1;
                $session[user][medfind]-=1;
            }else{
                output("`c`b`4<big><big>You Found a Medallion!</big></big>`b`c",true);
                output("`c`b`4<big><big>Too bad you are already carrying your limit!</big></big>`b`c",true);
            }
        }
    }


in newday.php
$session['user']['medfind']=e_rand(8,12);
*/

require_once "common.php";
checkday();
page_header("Il Concorso delle Medaglie");
if ($session['user']['superuser'] > 2){
   output("`@Scrivi data scadenza torneo`n");
   output("<form action='contestcorner.php?op1=data' method='POST'><input name='data' value='0'><input type='submit' class='button' value='Data Scadenza Torneo'>`n",true);
   addnav("","contestcorner.php?op1=data");
}
if ($_GET['op1'] == "data"){
   $data = $_POST['data'];
   savesetting("datamedaglie", $data);
}
output("`c`b`&La Classifica`0`b`c`n`n");
output("`2La Caccia alle Medaglie!  Il giocatore che trova il maggior numero di medaglie nel periodo di tempo prestabilito vince!`n");
output("Mentre giochi puoi trasportare fino ad un massimo di 5 medaglie su di te.  Qui puoi convertire le medaglie in punti.`n`n");
//lets make notice of when started and when ending
output("<big>`n`@Il Torneo delle Medaglie si chiuderà il giorno `%".getsetting("datamedaglie","")." `@!!!</big>`n`n",true);
//lets award the top person the amount of gems paid in - superuser module
if ($_GET['op'] == ""){
    output("Miglior risultato di sempre: ".$settings['medconthigh']." di ".$settings['medcontplay']."`n`n");
    $sql = "SELECT name,medpoints FROM accounts where medhunt > 0 AND superuser = 0 ORDER BY medpoints DESC";
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
    output("`3Montepremi Attuale: $totalpot gemme.`n`n");
    output("`^Primo Posto: $firstplace gemme.`n");
    output("`#Secondo Posto: $secondplace gemme.`n");
    output("`@Terzo Posto: $thirdplace gemme.`n");
    output("`3Quarto Posto: $forthplace gemme.`n");
    output("`2Quinto Posto: $fifthplace gemme.`n");
    if ($session['user']['medhunt']>0 OR $session['user']['superuser']>1){
        output("Hai totalizzato ".$session['user']['medpoints']." punti!`n`n");
        output("`c`bClassifica Attuale:`c`b`n");
        output("<table cellspacing=0 cellpadding=2 align='center'><tr><td>Pos.</td><td align='center'>`bNome`b</td><td align='center'>`bPunteggio`b</td></tr>",true);
        if (db_num_rows($result) == 0) {
            output("<tr><td colspan=4 align='center'>`&Nessun Giocatore si è iscritto alla Gara delle Medaglie`0</td></tr>", true);
        }
        $countrow = db_num_rows($result);
        for ($i=0; $i<$countrow; $i++){
        //for ($i = 0;$i < db_num_rows($result);$i++) {
            $row = db_fetch_assoc($result);
            if ($row['name'] == $session['user']['name']) {
                output("<tr bgcolor='#BB0000'>", true);
            } else {
                output("<tr class='" . (($i) % 2?"trlight":"trdark") . "'>", true);
            }
              output("<td>".($i+1)."</td><td>`b{$row['name']}`b</td><td align='right'>".$row['medpoints']."</td></tr>", true);
        }
        output("</table>", true);

    /*    output("============`n");
        for ($i=0;$i<db_num_rows($result);$i++){
        $row = db_fetch_assoc($result);
            output("`&".($i+1).". `@".$row['name']."`7 - `^".$row['medpoints']."`n");
            }
        output("`n"); */
        if ($session['user']['medallion'] > 0) {
           addnav("`^Converti Medaglie","contestcorner.php?op=turnin");
        }
    }else{
        output("`nIl costo per partecipare alla gara è di 2 gemme.  I vincitori si accaparreranno l'intero montepremi in gemme versate");
        output("dai partecipanti.  Quindi se ci sono 50 partecipanti il premio totale sarà di 100 gemme.`n`n");
        output("<big><big>`b`^Si rammenta che chi sfrutta tutti i turni di gioco troverà più medaglie !!!`b</big></big>`n",true);
        if ($session['user']['gems']>1 and $_GET['op'] == ""){
            addnav("`#Partecipa alla Gara","contestcorner.php?op=enter");
        }else{
            output("Sembra che tu non abbia abbastanza gemme per partecipare.`n");
        }
    }
    $sql = "SELECT medpoints FROM medagliepremi";
    $result = db_query($sql) or die(db_error(LINK));
    $totalpot=db_num_rows($result)*2;
    if ($totalpot>0) addnav("`@Classifica Torneo Precedente","contestcorner.php?op=classificaold");
}
if ($_GET['op'] == "classificaold"){
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
    output("`n`n`n`3Montepremi: $totalpot gemme.`n`n");
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
        if ($row['name'] == $session['user']['name']) {
            output("<tr bgcolor='#BB0000'>", true);
        } else {
            output("<tr class='" . (($i) % 2?"trlight":"trdark") . "'>", true);
        }
        output("<tr class='".(($i)% 2?"trlight":"trdark")."'><td>".($i+1)."</td><td>`b{$row['name']}`b</td><td align='right'>".$row['medpoints']."</td></tr>", true);
    }
    output("</table>", true);
    addnav("`@Torna Indietro","contestcorner.php");
}
if ($_GET['op'] == "turnin"){
    output("Le tue medaglie sono state convertite in punti!");
    $session['user']['medpoints']+=$session['user']['medallion'];
    $session['user']['medallion']=0;
    if ($settings['medconthigh'] < $session['user']['medpoints']){
        savesetting("medconthigh",$session['user']['medpoints']);
        savesetting("medcontplay",$session['user']['name']);
    }
    addnav("`%Continua","contestcorner.php");
}
if ($_GET['op'] == "enter"){
    $session['user']['gems']-=2;
    $session['user']['medhunt']=1;
    $session['user']['medfind']=e_rand(8,12);
    output("`n`5Luke ti offre una pozione ... che tu trangugi senza esitazione per la grande fiducia che riponi in lui.");
    output("`n`5Luke ti dice che questa pozione magica ti darà la percezione di cui necessiti per vedere le medaglie che sono nascoste ovunque.");
    output("`n`\$Ti sei iscritto alla gara!  `5Cosa aspetti ? Corri ed inizia a raccogliere le medaglie!");
    addnav("`@Continua","contestcorner.php");
}
addnav("`@Torna al Villaggio","village.php");
//I cannot make you keep this line here but would appreciate it left in.
//rawoutput("<div style=\"text-align: left;\"><a href=\"http://www.pqcomp.com\" target=\"_blank\">Medallion Contest by Lonny @ http://www.pqcomp.com</a><br>");
page_footer();
?>