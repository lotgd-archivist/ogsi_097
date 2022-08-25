<?php
require_once "common.php"; 

page_header("Il Negozio di Sgarro");
$session['user']['locazione'] = 172;
addnav("Acquisti"); 
addnav("2000gp - Compra 1 Combattimento","sgarro.php?op=buy&level=1"); 
addnav("3500gp - Compra 2 Combattimenti","sgarro.php?op=buy&level=2"); 
addnav("5000gp - Compra 3 Combattimenti","sgarro.php?op=buy&level=3"); 
addnav("Altro"); 
addnav("La Farmacia di Adriana","adriana.php"); 
addnav("Torna al Castello","castelexcal.php");
addnav("Torna al Villaggio","village.php"); 
$turns=array(1=>1,2,3); 
$costs=array(1=>2000,3500,5000); 
if ($_GET[op]==""){ 
    output("`c`@`bIl Negozio di Sgarro`b`0`c`n`n"); 
    output(" `&Sgarro Il Troll tiene un piccolo negozietto all'interno di quello di Adriana`n`n"); 
    output(" `%`bSgarro dice che puo aumentare la tua resistenza, `iper un piccolo prezzo ... he he he.`i`n"); 
}elseif($_GET[op]=="buy"){ 
        if ($session[user][gold]>=$costs[$_GET[level]]){ 
output("`c`@`bSgarro's Power Shop`b`0`c`n`n"); 
output("`@Sgarro prende i tuoi ".($costs[$_GET[level]])." pezzi d'oro e ti da più resistenza.`n"); 
output(" Ottieni ".($turns[$_GET[level]])." Combattiment".($turns[$_GET[level]]>=2?"i":"o")." in cambio.`n`n"); 
            $session[user][gold]-=$costs[$_GET[level]]; 
            $session[user][turns]+=$turns[$_GET[level]]; 
        }else{ 
output("`c`@`bSgarro's Power Shop`b`0`c`n`n"); 
output("`b`^Sgarro ti ride dietro per il tuo tentativo di pagarlo meno di quel che valgono i suoi servigi.`n`n");             
        } 

} 
page_footer(); 
?> 
