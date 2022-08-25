<?php
if (!isset($session)) exit();
output("`^Un vecchietto ti colpisce con una bella bacchetta, ridacchia e corre via!`n`n`%Guadagni un punto di`^ fascino!");
$session[user][charm]++;
addnav("Torna alla Foresta","forest.php");
?>