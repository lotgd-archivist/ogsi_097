<?php
if (!isset($session)) exit();
if ($session[user][charm]>0){
    output("`^Un vecchietto ti colpisce con la sua brutta bacchetta, ridacchia e corre via!`n`n`%Perdi un`^ punto di fascino!");
    $session[user][charm]--;
}else{
  output("`^Un vecchietto ti colpisce con la sua brutta bacchetta e sussulta quando la sua bacchetta `%perde un`^ punto di fascino! Sei perfino più brutto della sua brutta bacchetta!");
}
addnav("Torna alla Foresta","forest.php");
?>