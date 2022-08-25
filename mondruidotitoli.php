<?php
require_once "common.php"; 
page_header("Le Lezioni del Druido"); 
output(" `3`c`bLe Lezioni del Druido - Titoli Nobiliari`b`c`n`n`3"); 
addnav("D?Torna dal Druido","mondruido.php"); 
addnav("M?Torna al Monastero","monastero.php"); 
addnav("");
addnav("SI, dimmi i Titoli","montitoli.php");
output(" `3Così vuoi saperne di più sui Titoli Nobiliari! `n`n"); 
output(" Ascoltami, `& {$session['user']['name']}. `3 `n"); 
output("`&Nel gioco `2La Leggenda del Drago Verde`&,");
output(" esistono 30 Titoli Nobiliari di livello crescente.`n");
output(" Per avanzare da un Titolo al successivo, devi uccidere il `2Drago Verde`&.`n");
output(" Ogni giocatore inizia come patetico Contadino o Contadina con la speranza di diventare Uccisore di Draghi. `n");
output(" Devi cercare e combattere le creature della Foresta per guadagnare Oro, Gemme ed Esperienza. `n");
output(" Tu vieni da me per conoscere tutti i Titoli Nobiliari, `4ma sei proprio sicuro di volerli conoscere in anticipo e rovinarti la sorpresa`& ?");
page_footer(); 
?> 