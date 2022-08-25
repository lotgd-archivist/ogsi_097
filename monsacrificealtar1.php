<?php
/************************************ 
Altar of Sacrifice 
Written by TheDragonReborn 
    Based on Forest.php 
Tradotto da Excalibur per www.ogsi.it
*************************************/ 
require_once "common.php"; 
addcommentary(); 
checkday(); 

if ($session['user']['alive']){ }else{ 
    redirect("shades.php"); 
} 
page_header("Altare dei Sacrifici"); 

     
    output(" `3`c`bAltare dei Sacrifici`b`c`n`n "); 
    output(" `3Mentre ti aggiri per il `2Monastero`3, ti imbatti in un altare di pietra, scavato in un "); 
    output(" blocco di roccia basaltica nei pressi di un grande albero della sorba. Ti avvicini, e noti "); 
    output(" le macchie di sangue disseccato da secoli di sacrifici. Questo è sicuramente un luogo speciale, "); 
    output(" e puoi percepire una presenza divina. `n`n"); 
    output(" `2Un Monaco ti si avvicina e ti dice che questo luogo è proibito ai visitatori."); 

    addnav("Abbandona"); 
    addnav("Torna al Monastero","monastero.php"); 
    

page_footer(); 
?> 