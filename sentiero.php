<?php
require_once("common.php");
// This idea is Imusade's from lotgd.net
$pvpopen = $session[user][playerfights];
checkday();
if ($pvpopen == 0) {
		page_header("Non puoi oggi");
		addnav("Torna al Villaggio","village.php");
		output("`5`nNon hai abbastanza tempo oggi per cercare avventure così impegnativi.");
    } else {
$session[user][playerfights]--;
$caso = e_rand(1, 3);

if ($caso == 1){
	page_header("La Torre");
	output("`n`b`2Il sentiero nel bosco sbuca in una radura, al centro della quale una torre di pietra nera si 
	innalza nel cielo per centinaia di metri a perdita d'occhio.`0`b`n`n");
	output("`n Cosa fai ?`n ");
	addnav("Vai alla Torre","torre.php");

}elseif ($caso == 2){
	page_header("Il Villaggio");
	output("`n`b`c`2Il sentiero nel bosco sbuca vicino alla tenda degli zingari.`0`c`b");
	output("`n`n");

}else{
	page_header("Perso nel Bosco");
	output("`n`b`c`2Ti sei perso nei boschi, dopo diverse ore ritrovi finalmente il villaggio.`0`c`b");
	output("`n`n");
}
addnav("Torna al Villaggio","village.php");
}
page_footer();
?>

