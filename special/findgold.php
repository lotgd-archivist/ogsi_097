<?php
$gold = e_rand($session['user']['level']*10,$session['user']['level']*50);
output("`3Nelle tue esplorazioni attraverso la foresta, scorgi un omino barbuto alto meno di un metro ");
output("con una giacca a falde color `Gverde smeraldo `3che, indossando un cappello a tricorno, un grembiule ");
output("da lavoro in `3pelle`3, un panciotto di lana, pantaloni alla zuava, calze al ginocchio, scarpe di pelle con ");
output("fibbie d'`&argento`3 e redingote, si fuma beatamente la pipa seduto su un tronco caduto e innalza anelli di ");
output("fumo `#azzurro `3verso le fronde degli alberi sopra di lui.`n`n");
output("Ricordando le antiche leggende che ti venivano raccontate da bambino, sai di aver incontrato una simpatica ");
output("creatura del boschi: il `fLeprechaun`3.`nAvanzi quindi risoluto verso il piccolo folletto il quale, ");
output("semiparalizzato dalla tua presenza apre la capiente borsa che porta a tracolla consegnandotene il suo ");
output("contenuto, mentre ti implora di lasciarlo libero senza fargli alcun male.`nMentre afferri ciò che lo strano ");
output("ometto ti consegna, il tuo sguardo si stacca dai suoi occhi per un istante e, quando lo rialzi, ti ritrovi ");
output("a fissare un cespuglio di bacche.`n");
output("La piccola creatura è svanita nel nulla lasciandoti però il suo piccolo tesoro.`n`n");
output("`^Hai ottenuto `r`b$gold `b`^pezzi d'oro!");
$session['user']['gold']+=$gold;
// Se arrivo dal giardino incantato devo aggiungere navigazione per rientro nella foresta
if ($session['user']['locazione'] == '119') {
	addnav("`3Prosegui nella Foresta","forest.php");
}
?>