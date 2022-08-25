<?php
/*
Stones (slots)
simple little slots game for your casino
Author: Lonnyl of http://www.pqcomp.com/logd
Difficulty: Easy
no sql to add
simply upload, link it to your casino.php or whatever you may be using.
upload images to your images folder.
if not using a casino.php change the return nav at the bottom of this file.
(casino.php is not an avialble file, you need to make one of your own)
*/
require_once "common.php";
checkday();
page_header("Gioco delle Pietre");
$session['user']['locazione'] = 178;
//checkevent();
output("`c`b`&Gioco delle Pietre`0`b`c`n`n");
if ($_GET['op'] == ""){
    output("`2Ti avvicini al tavolo del gioco delle Pietre, dove dietro al banco siede un'anziana donna con la sua sacca delle pietre.`n");
    output("L'anziana donna accetta solo gemme.`n");
    output("Ti osserva senza profferire parola.`n");
    output("Estrarrà 3 pietre dalla sua borsa, le vincite sono le seguenti:`n");
    output("<IMG SRC=\"images/stone1.gif\"><IMG SRC=\"images/stone1.gif\"> `@= <big>1 Gemma.<small>`n",true);
    output("<IMG SRC=\"images/stone2.gif\"><IMG SRC=\"images/stone2.gif\"> = <big>2 Gemme.<small>`n",true);
    output("<IMG SRC=\"images/stone1.gif\"><IMG SRC=\"images/stone1.gif\"><IMG SRC=\"images/stone1.gif\"> = <big>4 Gemme.<small>`n",true);
    output("<IMG SRC=\"images/stone2.gif\"><IMG SRC=\"images/stone2.gif\"><IMG SRC=\"images/stone2.gif\"> = <big>8 Gemme.<small>`n",true);
    addnav("Punta una Gemma","stonesgame.php?op=play");
}
if ($_GET['op'] == "play"){
    if ($session['user']['gems'] > 0){
    output("`2Getti la tua gemma sul tavolo e l'anziana donna si appresta ad estrarre le pietre dalla sua borsa.`n");
    $session['user']['gems']-=1;
    $stoneone=e_rand(1,3000);
    $stonetwo=e_rand(1,4000);
    $stonethr=e_rand(1,5000);
    $stoneone=round($stoneone/1000);
    $stonetwo=round($stonetwo/1000);
    if ($stonetwo == 4) $stonetwo = 3;
    $stonethr=round($stonethr/1000);
    if ($stonethr > 3) $stonethr = 3;
    if ($stoneone == 0) $stoneone = 3;
    if ($stonetwo == 0) $stonetwo = 3;
    if ($stonethr == 0) $stonethr = 3;
    output("<IMG SRC=\"images/stone".$stoneone.".gif\"><IMG SRC=\"images/stone".$stonetwo.".gif\"><IMG SRC=\"images/stone".$stonethr.".gif\">`n",true);
    if ($stoneone == 3) $stoneone = 0;
    if ($stonetwo == 3) $stonetwo = 0;
    if ($stonethr == 3) $stonethr = 0;
    if ($stoneone == 2) $stoneone = 5;
    if ($stonetwo == 2) $stonetwo = 5;
    if ($stonethr == 2) $stonethr = 5;
    $stonetotal=($stoneone+$stonetwo+$stonethr);
    if ($stonetotal == 2 or $stonetotal == 7){
        //push
        $session['user']['gems']+=1;
        output("`2Ancora totalmente silente l'anziana donna ti restituisce la tua gemma, che tu afferri velocemente e riponi in tasca.`n");
    }elseif ($stonetotal == 10 or $stonetotal == 11){
        //double
        $session['user']['gems']+=2;
        output("`2Sempre in silenzio l'anziana donna mette 2 gemme sul tavolo. Le afferri lesto e le riponi in tasca.`n");
    }elseif ($stonetotal == 3 or $stonetotal == 8){
        //triple
        $session['user']['gems']+=4;
        output("`2Sempre in silenzio l'anziana donna mette 4 gemme sul tavolo. Le afferri lesto e le riponi in tasca.`n");
    }elseif ($stonetotal == 15 or $stonetotal == 16){
        //quad
        $session['user']['gems']+=8;
        output("`2Sempre in silenzio l'anziana donna mette 8 gemme sul tavolo. Le afferri lesto e le riponi in tasca.`n");
    }else{
        output("`2L'anziana donna ripone la tua gemma e ti guarda incuriosita, come attendendo un tuo gesto.`n");
    }
    addnav("Riprova","stonesgame.php?op=play");
    }else{
        output("Non hai gemme da puntare!`n");
    }
}
addnav("Torna alla Tenuta","houses.php?op=enter");
//I cannot make you keep this line here but would appreciate it left in.
rawoutput("<br><br><div style=\"text-align: right;\">Stones Game by Lonny<br>");
page_footer();
?>