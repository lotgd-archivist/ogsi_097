<?php

/* This file is part of "The Tournament"
* made by Excalibur, refer to torneo.php
* for instructions and copyright notice */

$livello['1'] = "uno";
$livello['2'] = "due";
$livello['3'] = "tre";
$livello['4'] = "quattro";
$livello['5'] = "cinque";
$livello['6'] = "sei";
$livello['7'] = "sette";
$livello['8'] = "otto";
$livello['9'] = "nove";
$livello['10'] = "dieci";
$livello['11'] = "undici";
$livello['12'] = "dodici";
$livello['13'] = "tredici";
$livello['14'] = "quattordici";
$livello['15'] = "quindici";

require_once "common.php";
page_header("Classifiche Torneo");
addnav("T?`@Torna al Castello","castelexcal.php");
addnav("G?`#Classifica Generale","torneoclas.php?op=generale");
addnav("L?`%Classifica per Livello","torneoclas.php?op=livello");
addnav("L?`^Livelli Completati","torneoclas.php?op=fatti");
$sql = "SELECT * FROM torneopremi";
$result = db_query($sql) or die(db_error(LINK));
$totalpot=db_num_rows($result);
if ($totalpot>0) addnav("`@Classifiche Torneo Precedente","torneoclasold.php");


if ($_GET['op']=="") {
    output("<font size='+1'>`c`b`!Le Classifiche del Torneo di LoGD`b`c`n`n</font>",true);
    output("`6Benvenuto alle `@Classifiche del Grande Torneo di LoGD`6. Qui hai la possibilità di visualizzare diversi ");
    output("tipi di tabelle. Una di queste è la `@Classifica Generale`6, in cui vengono visualizzati i giocatori attualmente ");
    output("in lizza per la conquista delle prime posizioni con a fianco il loro punteggio totale ottenuto fino a quel momento, ");
    output("o puoi scegliere di visualizzare la classifica per ogni singola prova disputata, in cui potrai vedere chi si è ");
    output("distinto nella singola prova. `n`n");
    output("Quale classifica vuoi vedere ?`n");
}
if ($_GET[op]=="generale" OR $_GET['op']=="") {
    page_header("Classifica Generale");
    output("<font size='+1'>`c`b`!La Classifica Generale del Torneo di LoGD`b`c`n`n</font>",true);
    $sql = "SELECT name,torneo,torneopoints,superuser FROM accounts WHERE torneo > 0 AND superuser = 0 ORDER BY torneo DESC";
    output("`c`b`&La Classifica del Torneo di LoGD`b`c`n");
    output("<table cellspacing=0 cellpadding=2 align='center'><tr><td></td><td align='center'>`bNome`b</td><td align='center'>`bPunteggio`b</td><td align='center'>`bProve Disputate`b</td></tr>", true);
    $result = db_query($sql) or die(db_error(LINK));
    if (db_num_rows($result) == 0) {
        output("<tr><td colspan=4 align='center'>`&Nessun Giocatore si è iscritto al Torneo`0</td></tr>", true);
    }
    $countrow = db_num_rows($result);
    for ($i=0; $i<$countrow; $i++){
    //for ($i = 0;$i < db_num_rows($result);$i++) {
        $row = db_fetch_assoc($result);
        if ($i==0) savesetting("torneo",addslashes("$row[name]"));
        $row[torneopoints]=unserialize($row[torneopoints]);
        if ($row[superuser] == 0) {
        if ($row[name] == $session[user][name]) {
            output("<tr bgcolor='#007700'>", true);
        } else {
            output("<tr class='" . (($i+$k) % 2?"trlight":"trdark") . "'>", true);
        }
         $prove=count($row[torneopoints])/2;
          output("<td>" . ($i + $k + 1) . ".</td><td>$row[name]</td><td align='right'>".($row[torneo]-1)."</td><td align='center'>$prove</td></tr>", true);
        } else {
        $k = $k - 1;
        }
    }
    output("</table>", true);
}
if ($_GET[op]=="livello" && $_GET[op1]==""){
    page_header("Classifica per Livello");
    output("<font size='+1'>`c`b`!La Classifica per Livello del Torneo di LoGD`b`c`n`n</font>",true);
    for ($i=1; $i<16; $i++){
    addnav("`\$Livello $i","torneoclas.php?op=livello&op1=".$i);
    }
}
if ($_GET[op]=="livello" && $_GET[op1]!=""){
    page_header("Classifica per Livello");
    $arr=array();
    $sql = "SELECT name,torneo,torneopoints,superuser FROM accounts WHERE torneo > 0 AND superuser = 0";
    $result = db_query($sql) or die(db_error(LINK));
    $k=1;
    $z=db_num_rows($result);
    for ($i = 0;$i < $z ;$i++){
        $row = db_fetch_assoc($result);
        $row[torneopoints]=unserialize($row[torneopoints]);
        $convers = array();
        //print_r($row[torneopoints]);
        for ($ii = 0; $ii < count ($row[torneopoints]); $ii += 2) {
            if (isset ($row[torneopoints][$ii]) && isset ($row[torneopoints][$ii + 1])) {
                $convers[] = array ("punteggio" => $row[torneopoints][$ii],"livello" => $row[torneopoints][$ii + 1]);
            }
        }
        /*while (list($key, $val) = each($row[torneopoints])){
            if ( ($key/2) == intval($key/2)){
                $convers[($key/2)]['punteggio']=$val;
            }else {
                $convers[(($key-1)/2)]['livello']=$val;
            }
        }*/
        //print_r($convers);
        reset($convers);
        $nome=$row[name];
        foreach ($convers as $key => $row){
            if ($convers[$key]['livello'] == $livello[$_GET[op1]]){
                $arr[$k]['punteggio'] = $convers[$key]['punteggio'];
                $arr[$k]['nome'] = $nome;
                $k++;
            }
        }
    }
    arsort($arr);
    reset($arr);
    output("<font size='+1'>`c`b`!La Classifica del Livello $_GET[op1] del Torneo di LoGD`b`c`n`n</font>",true);
    output("<table cellspacing=2 cellpadding=2 align='center'><tr><td></td><td align='center'>`bNome`b</td><td align='center'>`bPunteggio`b</td></tr>", true);

    $kkk=0;
    foreach ($arr as $key => $row) {
    if ($arr[$key]['nome'] == $session[user][name]) {
        output("<tr bgcolor='#007700'>", true);
    } else {
        output("<tr class='" . (($key+1) % 2?"trlight":"trdark") . "'>", true);
        }
    output("<td>" . ($kkk+1) . ".</td><td align='center'>{$arr[$key]['nome']}</td><td align='right'>{$arr[$key]['punteggio']}</td></tr>", true);
    $kkk++;
    $flag=1;
    }
    if (!$flag) {
        output("<tr><td colspan=4 align='center'>`&Nessun Giocatore ha disputato la prova per questo livello`0</td></tr>", true);
        }
    output("</table>", true);
}
if ($_GET[op]=="fatti") {
    page_header("Livelli Svolti");
    output("<font size='+1'>`c`b`!I Livelli svolti da {$session['user']['name']}`b`c`n`n</font>",true);
    $sql = "SELECT torneopoints FROM accounts WHERE acctid = {$session['user']['acctid']}";
    $result = db_query($sql) or die(db_error(LINK));
    $row = db_fetch_assoc($result);
    $row[torneopoints]=unserialize($row[torneopoints]);
    output("<table cellspacing=2 cellpadding=2 align='center'><tr><td align='center'>`bLivello`b</td><td align='center'>`bPunteggio`b</td></tr>", true);
    for ($k = 1; $k < 16; $k++){
        for ($i = 0; $i < count ($row[torneopoints]); $i += 2){
            if ($livello[$k]==$row[torneopoints][$i + 1]){
                output("<tr class='" . (($pippo) % 2?"trlight":"trdark") . "'>", true);
                output("<td align='center'>`@{$livello[$k]}</td><td align='right'>`^{$row[torneopoints][$i]}</td></tr>", true);
                $pippo++;
                $totale=$totale+$row[torneopoints][$i];
                }
            }
        }
    output("<tr bgcolor='#005599'>", true);
    output("<td align='center'>`\$`bTotale`b</td><td align='right'>`^`b$totale`b</td></tr>", true);
    output("</table>", true);
}

page_footer();
?>