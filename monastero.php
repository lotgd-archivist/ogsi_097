<?php
/************************************
Monastery
Written by Robert for Maddnet LoGD
Tradotto da Excalibur per www.ogsi.it
*************************************/
require_once("common.php");
require_once("common2.php");
manutenzione(getsetting("manutenzione",3));
addcommentary();
checkday();
page_header("Il Monastero");
$session['user']['locazione'] = 154;

if ($session['user']['alive']){ }else{
    redirect("shades.php");
}

addnav("Monastero");
addnav("");
//addnav("A?Altare dei Sacrifici","monsacrificealtar1.php");
addnav("A?Altare dei Sacrifici","moncambiocarriera.php");
addnav("P?Priore","monpriore.php");
addnav("D?Druido","mondruido.php");
addnav("F?Frutteto","monfrutteto.php");
addnav("V?La Vigna","monvigneto.php");
addnav("S?Il Santuario","monsantuario.php");
addnav("c?Il Sacrario","monsacrario.php");
addnav("B?La Biblioteca","library.php");
addnav("Sentieri");
addnav("F?Vai alla Foresta","forest.php");
addnav("T?Torna al Villaggio","village.php");
addnav("");
addnav("`bVarie`b");
addnav("N?Notizie Giornaliere","news.php");

output("`3`c`bIl Monastero`b`c `n `n");
output(" `2Sei arrivato al `3Monastero`2. `n");
output(" Una comunità di monaci, legata dai voti ad una vita religiosa che trascorre la propria esistenza in parziale o totale ritiro.`n`n");
output(" Nel profondo della Foresta, in quasi totale isolamento hai trovato il Monastero.`n");
output(" L'area all'esterno è ben curata. Noti anche un piccolo frutteto. `n");
output("`2Ogni lato del `3Monastero `2è circondato da una densa e buia foresta.`n");
output(" I monaci che vivono qui si mostrano abbastanza amichevoli da invitarti all'interno. `n");
output(" `3Sembra che gli umili monaci abbiano veramente poco da offrirti oggi, ma chissà, un altro giorno ...`n");


output("`n`n`2Nelle vicinanze puoi sentire altri esploratori parlare tra di loro:`n");
viewcommentary("monastero","parla con gli altri",15,5);

page_footer();
?>