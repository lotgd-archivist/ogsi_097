<?php
require_once("common.php");
// This idea is Imusade's from lotgd.net
if ($session['user']['dragonkills']>0 || $session['user']['superuser']>1){
	addcommentary();
}

checkday();
if ($session['user']['dragonkills']>0 || $session['user']['superuser']>1){
	page_header("Club dei Veterani");
	
	output("`b`c`2Club dei Veterani`0`c`b");
	
	output("`n`n`4Qualcosa ti spinge ad esaminare la roccia curiosa. Una qualche magia oscura, imprigionata in orrori antichi di ere.");
	output("`n`nQuando arrivi alla roccia, una vecchia cicatrice sul tuo braccio inizia a pulsare a ritmo con una misteriosa luce che ");
	output("ora sembra provenire dalla roccia.  Mentre la fissi, la roccia brilla, scrollandosi di dosso un'illusione. Ti rendi conto che questa è ");
	output("più che una semplice roccia. In effetti è una porta, ed oltre l'uscio vedi altre persone che hanno cicatrici identiche alla tua. Ti ");
	output("ricorda in qualche modo uno dei grandi serpenti delle leggende. Hai scoperto il Club dei Veterani.");
	output("`n`n");
	viewcommentary("veterans","Vantati qui",30,10,"si vanta");
}else{
	page_header("Roccia Curiosa");
	output("Ti avvicini alla roccia curiosa. Dopo averla fissata e guardata per un po', continua a sembrare una roccia curiosa e basta.`n`n");
	output("Annoiato, decidi di tornare al villaggio.");
}
addnav("Il Bosco a Sud","bosco.php");

addnav("Torna al Villaggio","village.php");

page_footer();
?>
