<?php
require_once("common.php");
page_header("Il Limbo");
$session['user']['locazione'] = 204;
$stanza = "limbo-".$session['user']['acctid'];
if ($session['user']['consono'] == 0){
    output("<big><b>Attesa Approvazione Nick.</b></big>`n`n",true);
    output("`2Devi attendere che gli `\$Admin`2 verifichino il nick che hai scelto per giocare a LoGD.`n");
    output("L'attesa non sarà lunga e verrai avvisato con una mail sia che venga o che non venga accettato.`n`n");
//    output("Per il momento l'unica cosa che puoi fare è scollegarti e attendere ...");
    output("Per il momento l'unica cosa che puoi fare è attendere ... `n`n");
    output("`(Ma puoi provare a contattare lo staff di LoGD, e segnalare eventuali problemi con la tua registrazione (alternativamente puoi utilizzare le petizioni):`n`n`n");
    addnav("E?`\$`bEsci`b`0 da LoGD","login.php?op=logout",true);
    $stanzachiusa=0;
}elseif ($session['user']['consono'] == 2){
    output("`@Il tuo nick è stato approvato dagli `\$Admin`@, e puoi finalmente lasciare questo posto. Ti auguriamo di divertirti con LoGD.`n");
    addnav("V?`@Vai al Villaggio","village.php");
    $stanzachiusa=1;
}
addcommentary();
viewcommentary($stanza,"Invoca le divinità:",30,10,"dice","");
page_footer();
?>