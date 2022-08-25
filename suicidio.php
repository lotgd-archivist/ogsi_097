<?php
require_once("common.php");
require_once("common2.php");

page_header("Suicidio?");

if ($_GET[op]=="sicuro") {
	output("`#La tua volontà prevale sul tuo istinto di sopravvivenza.`nCompi l'estremo atto, consapevole che presto ti troverai al cospetto di `\$Ramius");
	debuglog("si è suicidato");
    addnav("Notizie Giornaliere","news.php");
    $session['user']['alive']=false;
    $session['user']['hitpoints']=0;
}else{
	output("`^`c`bATTENZIONE!`b`c`n`nHai davvero intenzione di suicidarti?");
    addnav("No, voglio vivere!","village.php");
    addnav("Sì, mi voglio suicidare!","suicidio.php?op=sicuro");
}
page_footer();

?>