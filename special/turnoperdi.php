<?php
if (!isset($session)) exit();
   output("`^Mentre ti incammini sul sentiero che conduce alla foresta, ti imbatti in un campo di fiori.`n
   Ti fermi per raccoglierne uno, e ne inali il profumo.`n`n`\$Yawn!`^ Il profumo del fiore è stordente.
   Ti sdrai sul prato per fare un riposino.`n`n`%Perdi un`^ turno!`0");
   $session[user][turns]--;
   addnav("Torna alla Foresta","forest.php");
?>