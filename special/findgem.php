<?php
output("`FStai camminando lungo un sentiero che attraversa un bosco di conifere, quando la tua attenzione viene ");
output("attirata da un lieve alito di vento e dal fruscio di un cespuglio. Lentamente, ti avvicini e vedi un ");
output("piccolo essere buffo e grottesco sgattaiolare in quella che sembra una tana di animale.`n`nTi apposti ");
output("immobile e dopo una breve attesa scorgi un folletto del bosco che esce dal suo nascondiglio e velocemente ");
output("si dirige verso il folto degli alberi.`n`nMentre lo segui silenziosamente, noti che qualcosa simile a un ");
output("sassolino lucente cade da una delle sue tasche e rotola al suolo. Istintivamente ti affretti a raccoglierlo, ");
output("ma così facendo distogli lo sguardo dalla piccola creatura che sparisce tra gli alberi senza lasciare traccia.`n`n");
output("Resti con un palmo di naso ritrovandoti da solo nel bosco silenzioso, ma alla fine di questa avventura ti ");
output("resta comunque l'oggetto raccolto e che ti sei messo in saccoccia:`n`6un sasso che si rivela essere una ");
output("preziosa `b`&gemma!`b`n");
debuglog("trova una gemma in foresta (findgem)");
$session['user']['gems']++;
// Se arrivo dal giardino incantato devo aggiungere navigazione per rientro nella foresta
if ($session['user']['locazione'] == '119') {
	addnav("`3Prosegui nella Foresta","forest.php");
}
?>