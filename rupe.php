<?php
require_once("common.php");
// Hugues la rupe scoscesa 
if ($session['user']['reincarna'] > 10 OR $session['user']['superuser']>1){
	addcommentary();
}
page_header("La Rupe Scoscesa");
checkday();
if ($session['user']['reincarna'] > 10 OR $session['user']['superuser']>1) {

	output("`b`c`2La Rupe Scoscesa`0`c`b");
	
	output("`n`n`4La leggenda narra che da questa rupe è possibile intraprendere un lungo ed avventuroso viaggio cavalcando il proprio fedele drago e raggiungere in questo modo l'Isola Maledetta.");
	output("`nBardi e Aedi hanno poi raccontato di quest'isola popolata di mostri invincibili ma nessun eroe dei nostri giorni può vantare alcuna esperienza a riguardo.");
	output("`nDi sicuro per poter partire alla volta di questo luogo leggendario è necessario possederne una mappa in modo da essere in grado di orientarsi al meglio tra gli indicibili pericoli che ");
	output("sicuramente l'isola nasconde. Ma dove procurarsi questa mappa? Forse è necessario esplorare meglio gli angoli più oscuri della foresta di Rafflingate.");
	output("`n`n");
	viewcommentary("rupe","Saluta il villaggio",30,10,"saluta");
}else{
	
	output("`n`n`4La leggenda narra che da questa rupe è possibile intraprendere un lungo ed avventuroso viaggio cavalcando il proprio fedele drago e raggiungere in questo modo l'Isola Maledetta.");
	output("`nBardi e Aedi hanno poi raccontato di quest'isola popolata di mostri invincibili ma nessun eroe dei nostri giorni può vantare alcuna esperienza a riguardo.");
	output("`nDi sicuro per poter partire alla volta di questo luogo leggendario è necessario avere più vite sulle proprie spalle di quante tu ne hai ora e possederne una mappa in modo da essere in grado di orientarsi al meglio tra gli indicibili pericoli che ");
	output("sicuramente l'isola nasconde. Ma dove procurarsi questa mappa? Forse è necessario esplorare meglio gli angoli più oscuri della foresta di Rafflingate.");
	output("`n`n");
	output("Rendendoti conto della tua poca esperienza, decidi di tornare al villaggio.");
}
//addnav("Il Bosco a Sud","bosco.php");

addnav("Torna al Villaggio","village.php");

page_footer();
?>
