<?php
/* This file is part of "The Tournament"
* made by Excalibur, refer to torneo.php
* for instructions and copyright notice */
$livello['1'] = "uno";
$livello['2'] = "due";
$livello['3'] = "tre";
$livello['4'] = "quattro";
$livello['5'] = "cinque";
$livello['6'] = "sei";
$livello['7'] = "sette";
$livello['8'] = "otto";
$livello['9'] = "nove";
$livello['10'] = "dieci";
$livello['11'] = "undici";
$livello['12'] = "dodici";
$livello['13'] = "tredici";
$livello['14'] = "quattordici";
$livello['15'] = "quindici";

require_once "common.php";
page_header("Il TORNEO");
output("<font size='+1>'`b`c`#IL TORNEO`b`c`n`n</font>",true);
$level=$session['user']['level'];
$flag="";

while (list($key, $val) = each($session[user][torneopoints])) {
	if ($val == $livello[$level]) $flag="pippo";
}
if ($flag=="pippo"){
output("`\$Hai già completato la prova per questo livello, non puoi farla più di una volta !!`");
addnav("T?`@Torna al Castello","castelexcal.php");
}else{
	$points=e_rand(1,100);
	$session['user']['torneo']+=$points;
	$points1=intval($points / 10);
	$resto=100-$points;
	if ($points1==0) $points1=1;
	array_push($session['user']['torneopoints'],$points, $livello[$level]);
    for ($i1=0; $i1<5; $i1++){
	output("`c");
	for ($i=0; $i<$points1; $i++){
		output(". .");
		}
		output("`c`n");}
switch ($level) {
	
case 1:
	output("`#Eccoti giunto alla prova prevista per il livello $level. Non ti far trarre in inganno dal fatto che è ");
	output("per il livello 1, è impegnativa quanto le altre !! Ma non perdiamoci in convenevoli, diamo inizio ");
	output("alle danze. `nLa prova consiste nel `%Lancio del Nano`#, più lontano riesci a lanciarlo, più punti ");
	output("accumulerai.`n`n");
	switch ($points1) {
		case 1:
		output("`n`n`\$Uno dei peggiori lanci che io abbia mai visto !!! `n`#Hai totalizzato <font size='+1'>`6un misero $points</font> `#!!",true);
		addnav("T?`@Torna al Castello","castelexcal.php");
		break;
		case 2: case 3: case 4: case 5:
		output("`n`n`%Niente di eccezionale, ma ho visto di peggio. `n`#Hai totalizzato <font size='+1'>`6$points punti</font> `#!!",true);
		addnav("T?`@Torna al Castello","castelexcal.php");
		break;
		case 6: case 7: case 8: case 9:
		output("`n`n`#Bel lancio, complimenti. `n`#Hai totalizzato <font size='+1'>`6$points punti</font> `#!!",true);
		addnav("T?`@Torna al Castello","castelexcal.php");
		break;
		case 10:
		output("`n`n`^Wow, sei più bravo di `!Mighthy`^, il miglior lancio del nano che abbia mai visto !!!`n");
		output("`#Hai totalizzato <font size='+1'>`6$points punti</font> `#!!",true);
		output("`n`^Ti aggiudichi `b1 gemma`b bonus !!!");
		$session['user']['gems']+=1;
		addnav("T?`@Torna al Castello","castelexcal.php");
		break;
	}break;
	
case 2:
	output("`@Ed ecco la prova per il livello $level. Anche questa sebbene sia per un livello basso non è per nulla ");
	output("una prova semplice da affrontare !! Qui dovrai mettere alla prova le tue doti fisiche di resistenza. ");
	output("Più kilometri riuscirai a percorrere, più punti ti aggiudicherai. Forza, inizia con la ventilazione, ");
	output("l'allenamento è importante in una prova come questa !!!`n`n");
	switch ($points1) {
		case 1:
		output("`n`n`\$Una delle peggiori performarce che io abbia mai visto !!! `n`#Hai percorso solamente <font size='+1'>`6$points kilometri</font> `#!!",true);
		output("`n`#Hai totalizzato <font size='+1'>`6$points punti</font> `#!!",true);
		addnav("T?`@Torna al Castello","castelexcal.php");
		break;
		case 2: case 3: case 4: case 5:
		output("`n`n`%Niente di eccezionale, ma potresti fare di meglio. `b`#Hai percorso <font size='+1'>`6$points kilometri</font> `#!!",true);
		output("`n`#Hai guadagnato <font size='+1'>`6$points punti</font> `#!!",true);
		addnav("T?`@Torna al Castello","castelexcal.php");
		break;
		case 6: case 7: case 8: case 9:
		output("`n`n`#Mhhh, niente male, complimenti. `n`#Hai percorso la bellezza di <font size='+1'>`6$points kilometri</font> `#!!",true);
		output("`n`#Hai totalizzato <font size='+1'>`6$points punti</font> `#!!",true);
		addnav("T?`@Torna al Castello","castelexcal.php");
		break;
		case 10:
		output("`n`n`^Wow, sei un mezzofondista nato, sei l'emulo di Forrest Gump !!!`n");
		output("`#Sei riuscito a raggiungere il traguardo percorrendo tutti i <font size='+1'>`6$points kilometri</font> `#del percorso !!",true);
		output("`n`#Hai totalizzato <font size='+1'>`6$points punti</font> `#!!",true);
		output("`n`^Ti aggiudichi `b1 gemma`b bonus !!!");
		$session['user']['gems']+=1;
		addnav("T?`@Torna al Castello","castelexcal.php");
		break;
	}break;
	
case 3:
	output("`%Ed ecco la prova per il livello $level. Siamo ancora nella zona bassa, e le prove non sono ancora ");
	output("impossibili da affrontare. Questa consiste in una gara di precisione. Dovrai lanciare 5 dardi con una ");
	output("balestra contro un bersaglio, il centro vale 20 punti, quindi il miglior punteggio realizzabile è di ");
	output("100 punti. Saprai eguagliare il record stabilito dal cecchino del nostro villaggio, `b`@Saruman`b`% ??`n`n");
	switch ($points1) {
		case 1:
		output("`n`n`\$Una delle peggiori performance che io abbia mai visto !!! `n`#Hai totalizzato solamente <font size='+1'>`6$points punti</font> `#!!",true);
		addnav("T?`@Torna al Castello","castelexcal.php");
		break;
		case 2: case 3: case 4: case 5:
		output("`n`n`%Niente di eccezionale, ma potresti fare di meglio. `n`#Hai totalizzato <font size='+1'>`6$points punti</font> `#!!",true);
		addnav("T?`@Torna al Castello","castelexcal.php");
		break;
		case 6: case 7: case 8: case 9:
		output("`n`n`#Mhhh, buona mira, complimenti. `#Hai fatto anche un centro, per un totale di <font size='+1'>`6$points punti</font> `#!!",true);
		addnav("T?`@Torna al Castello","castelexcal.php");
		break;
		case 10:
		output("`n`n`^Wow, 5 centri su 5 tiri, ma sei `@Saruman`^ travestito ??`n");
		output("`#Ti sei portato a casa il bottino completo, totalizzando <font size='+1'>`6$points punti</font> `#!!!",true);
		output("`n`^Ti aggiudichi `b1 gemma`b bonus !!!");
		$session['user']['gems']+=1;
		addnav("T?`@Torna al Castello","castelexcal.php");
		break;
	}break;

case 4:
	output("`#Ed eccoci alla prova per il $level° livello . Ci stiamo avvicinando ai livelli intermedi, e le prove ");
	output("si stanno facendo via via più impegnative. Anche questa consiste in una gara di precisione. Qui al contrario ");
	output("della precedente dove veniva utilizzata una balestra, dovrai cimentarti con arco e frecce. Anche in questa ");
	output("prova avrai a disposizione 5 lanci, ed il centro vale sempre 20 punti. Il nostro campione di tiro con l'arco ");
	output("è il poliedrico `b`^Aris`b`#, saprai eguagliarlo in bravura e precisione ???`n`n");
	switch ($points1) {
		case 1:
		output("`n`n`\$Una delle peggiori performance che io abbia mai visto !!! `n`#Hai totalizzato solamente <font size='+1'>`6$points punti</font> `#!!",true);
		addnav("T?`@Torna al Castello","castelexcal.php");
		break;
		case 2: case 3: case 4: case 5:
		output("`n`n`%Niente di eccezionale, ma potresti fare di meglio. `n`#Hai totalizzato <font size='+1'>`6$points punti</font> `#!!",true);
		addnav("T?`@Torna al Castello","castelexcal.php");
		break;
		case 6: case 7: case 8: case 9:
		output("`n`n`#Mhhh, buona mira, complimenti. `#Hai fatto anche due centro, per un totale di <font size='+1'>`6$points punti</font> `#!!",true);
		addnav("T?`@Torna al Castello","castelexcal.php");
		break;
		case 10:
		output("`n`n`^Wow, 5 centri su 5 tiri. Non sarai mica il maestro di `#Aris`^, vero ??`n");
		output("`#Ti sei portato a casa il bottino completo, totalizzando <font size='+1'>`6$points punti</font> `#!!!",true);
		output("`n`^Ti aggiudichi `b1 gemma`b bonus !!!");
		$session['user']['gems']+=1;
		addnav("T?`@Torna al Castello","castelexcal.php");
		break;
	}break;
	
case 5:
	output("`^Siamo arrivati alla prova per il $level° livello . Siamo a buon punto, le tue capacità sono state messe ");
	output("alla prova, vediamo come te la caverai con questa. Il `bRE`b indiscusso di questo `itest`i è senza ombra ");
	output("di dubbio `#Cedrik`^ (e come poteva essere altrimenti ?). Dovrai dimostrare le doti del tuo stomaco nel ");
	output("trangugiare quanti più boccali di birra. Il record di `b`#Cedrik`b`^ è di 100 boccali, saprai eguagliarlo ??? `n`n");
	switch ($points1) {
		case 1:
		output("`n`n`\$Una performance degna del peggior trangugiatore di birra che io abbia mai visto !!! `#Hai tracannato solamente <font size='+1'>`6$points boccali</font> `#!!",true);
		addnav("T?`@Torna al Castello","castelexcal.php");
		break;
		case 2: case 3: case 4: case 5:
		if ($session['user']['sex']==0){
		output("`n`n`%Prova degna per una donzella, ma non mi pare tu porti la gonna.`n Sono sicuro che tua moglie ");
		output("saprebbe fare di meglio. `n`#Hai tracannato <font size='+1'>`6$points boccali</font> `#!!",true);
		}else{
		output("`%Niente male per appartenere al gentil sesso !! Probabilmente hai fatto meglio di tuo marito !!`n");
		output("`#Hai trangugiato <font size='+1'>`6$points boccali</font> `#!!",true);
		}
		addnav("T?`@Torna al Castello","castelexcal.php");
		break;
		case 6: case 7: case 8: case 9:
		output("`n`n`#Mhhh, potresti fare concorrenza a `\$SuperCiuk`#, complimenti. `n`#Ti sei sbevazzat".($session[user][sex]?"a":"o")." <font size='+1'>`6$points boccali</font> `#!!`n",true);
		output("Ma dove stai correndo ??? Guarda che il bagno è dalla parte opposta .....");
		addnav("T?`@Torna al Castello","castelexcal.php");
		break;
		case 10:
		output("`n`n`^Wow, sei mica ".($session[user][sex]?"la sorella":"il fratello")." di `#Cedrik`^ ? `n");
		output("`#Meglio nascondere le botti di birra con te in giro ... hai ingollato tutti e 100 i boccali a disposizione, totalizzando <font size='+1'>`6$points punti</font> `#!!!",true);
		output("`n`^Ti aggiudichi `b1 gemma`b bonus !!!");
		$session['user']['gems']+=1;
		addnav("T?`@Torna al Castello","castelexcal.php");
		break;
	}break;
	
case 6:
	output("`&Ed eccoci giunti alla prova per il livello $level. Siamo praticamente a metà delle prove ");
	output("ed è qui che si vede la stoffa del campione. Cosa di meglio dopo la buona bevuta della prova precedente ");
	output("di una buona mangiata ? Cedrik dopo la birra ha messo a disposizione uno stock di `b`7Aringhe Affumicate`&`b. ");
	output("Chi riuscirà a far entrare nel proprio stomaco, già messo a dura prova dalla birra, il maggior quantitativo di ");
	output("aringhe sarà eletto `b`\$Affumicato dell'Anno`b`&. Il campione del villaggio è `b`#Luke`b`&, che con 99 aringhe ");
	output("è il campione indiscusso del villaggio.`n`n");
	switch ($points1) {
		case 1:
		output("`n`n`\$Una delle peggiori performance che io abbia mai visto !!! `n`#Hai mangiato solo <font size='+1'>`6$points aringhe</font> `#!!",true);
		addnav("T?`@Torna al Castello","castelexcal.php");
		break;
		case 2: case 3: case 4: case 5:
		output("`n`n`%Ho visto di meglio ultimamente. `n`#Hai mangiato <font size='+1'>`6$points aringhe</font> `#!!",true);
		addnav("T?`@Torna al Castello","castelexcal.php");
		break;
		case 6: case 7: case 8: case 9:
		output("`n`n`#Mhhh, potresti competere con Luke, complimenti. `n`#Sono rimaste sul bancone solo $resto aringhe, e quindi hai guadagnato <font size='+1'>`6$points punti</font> `#!!",true);
		addnav("T?`@Torna al Castello","castelexcal.php");
		break;
		case 10:
		output("`n`n`^Wow, abbiamo un nuovo campione al villaggio !! Hai polverizzato il record di `#Luke`^ !!`n");
		output("`#Hai spazzolato tutte e 100 le aringhe a disposizione, facendo il colpo grosso. `nHai guadagnato <font size='+1'>`6$points punti</font> `#!!!",true);
		output("`n`^Ti aggiudichi anche `b1 gemma`b bonus !!!");
		$session['user']['gems']+=1;
		addnav("T?`@Torna al Castello","castelexcal.php");
		break;
	}break;

case 7:
	output("`^Ed eccoci giunti alla prova per il livello $level. Siamo praticamente a metà delle prove ");
	output("ed è qui che si vede la stoffa del campione. Tra le aringhe e la birra delle prove precedenti il tuo ");
	output("stomaco è in fermento. Senti già la pressione del gas che si sta facendo strada verso la tua bocca ? ");
	output("Trattienilo, perchè ti servirà per questa prova. Chi riuscirà a fare il rutto più lungo si aggiudicherà ");
	output("il punteggio più alto, quindi meglio non indugiare ulteriormente, non vorrei che qualche stomaco esplodesse ");
	output("anzitempo, non sarebbe un bello spettacolo da vedere. Il campione indiscusso di questa prova è il nostro ");
	output("beneamato concittadino `!`bHutgard`b`^ con 99 secondi. Ma ecco, sento un sordo brontolio, diamo il via alle danze.`n`n");
	switch ($points1) {
		case 1:
		output("`n`n`\$Una delle peggiori performance che io abbia mai visto !!! `n`#Il tuo rutto è durato solamente <font size='+1'>`6$points secondi</font> `#!!",true);
		$resto=100-$points;
		addnav("T?`@Torna al Castello","castelexcal.php");
		break;
		case 2: case 3: case 4: case 5:
		output("`n`n`%Ho sentito di meglio ultimamente. `n`#Hai ruttato per <font size='+1'>`6$points secondi</font> `#!!",true);
		addnav("T?`@Torna al Castello","castelexcal.php");
		break;
		case 6: case 7: case 8: case 9:
		output("`n`n`#Mhhh, niente male, sono svenute una trentina di persone, complimenti. `n`#Il tuo rutto è durato <font size='+1'>`6$points secondi</font> `#!!",true);
		addnav("T?`@Torna al Castello","castelexcal.php");
		break;
		case 10:
		output("`n`n`^Wow, abbiamo un nuovo campione al villaggio !! Hai polverizzato il record di `#Hutgard`^ !!`n");
		output("`#Il tuo rutto ha rotto molte delle finestre del nostro villaggio, e si è sentito in alcuni dei villaggi ");
		output("confinanti. `nHai guadagnato <font size='+1'>`6$points punti</font> `#!!!",true);
		output("`n`^Ti aggiudichi anche `b1 gemma`b bonus !!!");
		$session['user']['gems']+=1;
		addnav("T?`@Torna al Castello","castelexcal.php");
		break;
	}break;
	
case 8:
	output("`@Hai resistito fino a qui ? Bene ecco la prova per il livello $level. Abbiamo passato dei bei momenti ");
	output("insieme fino adesso, non mollare proprio ora !! Tra aringhe, birra e rutti delle prove precedenti che ");
	output("ne diresti di una bella dormita ? Il sorriso di compiacimento sul tuo viso mi fa capire che sei d'accordo. ");
	output("Bene, mi auguro che il tuo sonno arretrato sia sufficiente per farti aggiudicare questa prova. Chi dormirà ");
	output("più a lungo guadagnerà più punti dei suoi avversari !! Come nelle prove precedenti abbiamo un campione ");
	output("nel villaggio, il beneamato concittadino `!`bDankor`b`^ con 99 ore filate di sonno !! `n`n");
	switch ($points1) {
		case 1:
		output("`n`n`\$Una delle peggiori performance che io abbia mai visto !!! `n`#Hai dormito solamente <font size='+1'>`6$points ore</font> `#!!`n",true);
		output("Certo per te il tempo che usi per dormire è tempo perso vero ? Peccato perchè in questa prova hai guadagnato solo <font size='+1'>`6$points punti</font> `#!!`n",true);
		$resto=100-$points;
		addnav("T?`@Torna al Castello","castelexcal.php");
		break;
		case 2: case 3: case 4: case 5:
		output("`n`n`%Che dire, non sei un gran dormiglione. `n`#Hai dormito per <font size='+1'>`6$points ore</font> `#!!",true);
		addnav("T?`@Torna al Castello","castelexcal.php");
		break;
		case 6: case 7: case 8: case 9:
		output("`n`n`#Mhhh, niente male, veramente. `n`#Il tuo sonno è durato <font size='+1'>`6$points ore</font> `#!!",true);
		addnav("T?`@Torna al Castello","castelexcal.php");
		break;
		case 10:
		output("`n`n`^Wow, abbiamo un nuovo campione al villaggio !! Hai polverizzato il record di `#Dankor`^ con `b100 ore`b filate !!`n");
		output("`#Il tuo sonno potrebbe competere con quello della `#Bella Addormentata nel Bosco`^ !!! ");
		output("`nHai guadagnato <font size='+1'>`6$points punti</font> `#!!!",true);
		output("`n`^Ti aggiudichi anche `b1 gemma`b bonus !!!");
		$session['user']['gems']+=1;
		addnav("T?`@Torna al Castello","castelexcal.php");
		break;
	}break;

case 9:
	output("`2Bene, eccoci alla prova per il livello $level. Questa è collegata direttamente alla precedente, ");
	output("in effetti l'hai già svolta contemporaneamente a quella del sonno. In cosa consiste ti starai chiedendo, ");
	output("visto che non ti sei accorto di nulla ? Semplice, abbiamo registrato il livello, in decibel, del tuo russare. ");
	output("Anche per questa prova abbiamo un campione in carica, che risponde al nome di `b`@CMT`b`2, che con 99 decibel ");
	output("è il Re incostrastato di questa prova. Vediamo se hai saputo fare di meglio ... `n`n");
	switch ($points1) {
		case 1:
		output("`n`n`\$Noooooo, una delle peggiori performance che io abbia mai visto !!! `n`#Hai raggiunto solo <font size='+1'>`6$points decibel</font> `#!!`n",true);
		output("Ma lo sai che soffiarsi il naso prima di andare a letto non aiuta nello svolgimento di questa prova ?`n");
		$resto=100-$points;
		addnav("T?`@Torna al Castello","castelexcal.php");
		break;
		case 2: case 3: case 4: case 5:
		output("`n`n`%Che dire, riesci a svegliare solo ".($session[user][sex]?"tuo marito":"tua moglie")." che ti dorme accanto. `n`#Hai raggiunto <font size='+1'>`6$points decibel</font> `#!!",true);
		addnav("T?`@Torna al Castello","castelexcal.php");
		break;
		case 6: case 7: case 8: case 9:
		output("`n`n`#Mhhh, niente male, veramente. `#Riesci a tenere sveglio tutto il vicinato con il tuo russare ! `nCon $points decibel guadagni <font size='+1'>`6$points punti</font> `#!!",true);
		addnav("T?`@Torna al Castello","castelexcal.php");
		break;
		case 10:
		output("`n`n`^Wow, abbiamo un nuovo campione al villaggio !! Hai polverizzato il record di `#CMT`^ con `b100 decibel`b !!`n");
		output("`#Potremmo usare il tuo russare al posto delle mine per demolire i palazzi pericolanti !!!! ");
		output("`nHai guadagnato <font size='+1'>`6$points punti</font> `#!!!",true);
		output("`n`^Ti aggiudichi anche `b1 gemma`b bonus !!!");
		$session['user']['gems']+=1;
		addnav("T?`@Torna al Castello","castelexcal.php");
		break;
	}break;

case 10:
	output("`6In questa prova, per il livello $level, ti forniamo 100 elastici. A cosa serviranno mai 100 elastici ? ");
	output("Come ben sai lo Gnomo del Bagno della foresta non è un campione di pulizia, ed il ristagnare dell'acqua ");
	output("favorisce il proliferare delle zanzare. Bene, con gli elastici che ti abbiamo fornito dovrai cercare di ");
	output("sterminare il maggior numero di quelle maledette creature. Se non mancherai neanche un colpo, sarai ");
	output("incoronato `#Campione di Tiro con l'Elastico alla Zanzara`6, attualmente appartenente a `b`#OberonGloin`b`6, ");
	output("ed avrai la riconoscenza di tutti gli abitanti del villaggio. `n`n");
	switch ($points1) {
		case 1:
		output("`n`n`\$Noooooo, una delle peggiori performance che io abbia mai visto !!! `@Le zanzare ti ringraziano ");
		output("per averle risparmiate. Hai totalizzato solo <font size='+1'>`^$points punti</font> `@!!`n",true);
		output("Visto che parteggi per le zanzare, non è che per caso sei un parente alla lontana di `\$Dracula`@ ???`n");
		$resto=100-$points;
		addnav("T?`@Torna al Castello","castelexcal.php");
		break;
		case 2: case 3: case 4:
		output("`n`n`%Che dire, quando ti fai cucinare una bistecca da ".($session[user][sex]?"tuo marito":"tua moglie")." gliela fai fare al sangue.");
		output("Le zanzare ti ispirano un senso di tenerezza, e ti dispiace ucciderle. `n`#Hai guadagnato solo <font size='+1'>`6$points punti</font> `#!!",true);
		addnav("T?`@Torna al Castello","castelexcal.php");
		break;
		case 5: case 6: case 7: case 8: case 9:
		output("`n`n`#Mhhh, niente male, sei un cecchino. `#Le zanzare quando percepiscono la tua presenza ti stanno ");
		output("alla larga. `nCon $points centri guadagni <font size='+1'>`6$points punti</font> `#!!",true);
		addnav("T?`@Torna al Castello","castelexcal.php");
		break;
		case 10:
		output("`n`n`^Wow, abbiamo un nuovo `#Campione di Tiro con l'Elastico alla Zanzara `^al villaggio !! Hai polverizzato ");
		output("il record di `#OberonGloin`^ con `b100 centri`b !!`n");
		output("`#I villaggi vicini ti stanno già cercando per far fuori tutte le zanzare della contea !!!! ");
		output("`nHai guadagnato <font size='+1'>`6$points punti</font> `#!!!",true);
		output("`n`^Ti aggiudichi anche `b1 gemma`b bonus !!!");
		$session['user']['gems']+=1;
		addnav("T?`@Torna al Castello","castelexcal.php");
		break;
	}break;

case 11:
	output("`3Bene, ci stiamo avvicinando al gotha di LoGD, sei giunto alla prova relativa al livello $level. ");
	output("Questa prova consiste nella doma di un centauro selvaggio, fornitoci gentilmente da `!Merick`3. ");
	output("Sappiamo bene che solo `!Merick`3 è in grado di domare un centauro, ma vogliamo vedere quanti secondi ");
	output("riuscirai a stare in groppa a questa fiera bestia. Come al solito abbiamo un campione in carica al villaggio, ");
	output("ed è `#`bPoker`b`3, con 99 secondi in groppa al centauro. Saprai fare di meglio ?? `n`n");
	switch ($points1) {
		case 1:
		output("`n`n`\$Noooooo, Il centauro ti ha disarcionato dopo soli $points secondi !!! ");
		output(" Hai totalizzato solo <font size='+1'>`^$points punti</font> `@!!`n",true);
		output("Non vai molto d'accordo con i quadrupedi, vero ???`n");
		$resto=100-$points;
		addnav("T?`@Torna al Castello","castelexcal.php");
		break;
		case 2: case 3: case 4:
		output("`n`n`%Non potresti mai fare il mandriano, il tuo tempo è di $points secondi. Però non sei neanche una scamorza a cavalcare, non ");
		output("dimentichiamoci che quello era un `&`icentauro infuriato`i`%. `n`%Comunque hai guadagnato <font size='+1'>`6$points punti</font> `#!!",true);
		addnav("T?`@Torna al Castello","castelexcal.php");
		break;
		case 5: case 6: case 7: case 8: case 9:
		output("`n`n`#Mhhh, niente, niente male. Gli animali a quattro zampe non hanno segreti per te. Sei stato molto bravo ");
		output("hai resistito per ben $points secondi. `nTi sei guadagnato <font size='+1'>`6$points punti</font> `#!!",true);
		addnav("T?`@Torna al Castello","castelexcal.php");
		break;
		case 10:
		output("`n`n`^Wow, `!Merick`^ non è nessuno al tuo confronto !!! Sei il nuovo campione del villaggio !! Hai polverizzato ");
		output("il record di `#Poker`^ con `b100 secondi`b !!`n");
		output("`#I villaggi vicini ti stanno già cercando per domare i centauri selvaggi !!!! ");
		output("`nHai guadagnato <font size='+1'>`6$points punti</font> `#!!!",true);
		output("`n`^Ti aggiudichi anche `b1 gemma`b bonus !!!");
		$session['user']['gems']+=1;
		addnav("T?`@Torna al Castello","castelexcal.php");
		break;
	}break;

case 12:
	output("`@Ecco, un altra prova che ci avvicina al livello più alto di LoGD, preparati ad affrontare la prova N° $level. ");
	output("Oggi ti dovrai cimentare nella raccolta di Pixie, queste splendide e sfuggenti creaturine. Verrai rinchiuso ");
	output("in una stanza con 100 di queste fragili esserini, e dovrai cercare di catturarne la maggior quantità possibile. ");
	output("Riuscirai a battere il record detenuto dal nostro campione `%`bKhendra`b`@, che ne ha catturate ben 99 nel tempo ");
	output("assegnato ? Preparati, la prova sta per cominciare.`n`n");
	switch ($points1) {
		case 1:
		output("`n`n`\$hai bisogno di un paio di occhiali ? Non le vedevi ? Sono troppo piccole per le tue mani da muratore ? ");
		output(" `#Ne hai catturate solo $points !! `nHai totalizzato solo <font size='+1'>`^$points punti</font> `#!!`n",true);
		$resto=100-$points;
		addnav("T?`@Torna al Castello","castelexcal.php");
		break;
		case 2: case 3: case 4:
		output("`n`n`%Beh con $points Pixie catturate ti non sei poi così scamuffo. Certo ci sono altri giocatori migliori di ");
		output("te, ma ce ne sono anche tanti altri peggiori di te. `nTi sei meritato <font size='+1'>`6$points punti</font> `#!!",true);
		addnav("T?`@Torna al Castello","castelexcal.php");
		break;
		case 5: case 6: case 7: case 8: case 9:
		output("`n`n`#Mhhh, proprio un bel risultato. Un piccolo sforzo e forse avresti potuto battere il record. ");
		output("Hai catturato ben $points Pixie. `nTi sei guadagnato <font size='+1'>`6$points punti</font> `#!!",true);
		addnav("T?`@Torna al Castello","castelexcal.php");
		break;
		case 10:
		output("`n`n`^Wow, hai polverizzato il record di `#Khendra`^ !!! Sei il nuovo campione del villaggio !! ");
		output("Con `b$points Pixie`b catturate sei diventato una leggenda !!`n");
		output("`#Il villaggio dei nani vicino ti sta già cercando, le Pixie sono animali ricercati !!!! ");
		output("`nHai guadagnato <font size='+1'>`6$points punti</font> `#!!!",true);
		output("`n`^Ti aggiudichi anche `b1 gemma`b bonus !!!");
		$session['user']['gems']+=1;
		addnav("T?`@Torna al Castello","castelexcal.php");
		break;
	}break;

case 13:
	output("`@Ancora un passo per avvicinarci al livello più alto di LoGD, preparati ad affrontare la prova N° $level. ");
	output("La nostra fantasia sta iniziando a dare segni di stanchezza, ma anche per oggi siamo riusciti ad inventarci ");
	output("un'altra prova. Saprai destreggiarti anche in questa tra i fornelli ?? Ti vengono fornite due uova e una padella, ");
	output("e dovrai cimentarti nella `%Giravolta della Frittata`@. Riuscirai a battere il record detenuto dal nostro campione ");
	output("cuoco `%`bNulla`b`@, che ne ha fatte ben 99 ?? Bando alle ciance, preparati, la prova sta per cominciare.`n`n");
	switch ($points1) {
		case 1:
		output("`n`n`\$Scommetto che non hai mai preparato qualcosa da mangiare in vita tua. `#".($session[user][sex]?"Tuo marito":"Tua moglie")." ");
		output(" ti ha fatto sempre trovare il pranzo nel piatto !! `nHai fatto solo $points giravolte!! `nHai totalizzato <font size='+1'>`^$points punti</font> `#!!`n",true);
		$resto=100-$points;
		addnav("T?`@Torna al Castello","castelexcal.php");
		break;
		case 2: case 3: case 4:
		output("`n`n`%non sei certo un grande chef, ma la tua pasta in bianco e la tua bistecchina al burro sei capace di "); 
		output("preparartele de sol".($session[user][sex]?"a":"o").". `nHai ottenuto <font size='+1'>`6$points punti</font> `#!!",true);
		addnav("T?`@Torna al Castello","castelexcal.php");
		break;
		case 5: case 6: case 7: case 8: case 9:
		output("`n`n`#Mhhh, faresti un figurone anche in un ristorante a **** stelle. Un piccolo sforzo e forse avresti potuto battere il record. ");
		output("Hai effettuato $points giravolte con quella frittata. `nTi sei guadagnato <font size='+1'>`6$points punti</font> `#!!",true);
		addnav("T?`@Torna al Castello","castelexcal.php");
		break;
		case 10:
		output("`n`n`^Wow, hai polverizzato il record di `@Nulla`^ !!! Sei il nuovo campione del villaggio !! ");
		output("Con `b$points giravolte`b della frittata sei diventato una leggenda !!`n");
		output("La `@Grotta dello Gnomo`^ ti sta già cercando per sostituire il loro chef !!!! ");
		output("`nHai guadagnato <font size='+1'>`6$points punti</font> `#!!!",true);
		output("`n`^Ti aggiudichi anche `b1 gemma`b bonus !!!");
		$session['user']['gems']+=1;
		addnav("T?`@Torna al Castello","castelexcal.php");
		break;
	}break;

case 14:
	output("`%Solo un gradino ti separe dal livello più alto di LoGD, eccoci arrivati alla prova N° $level. ");
	output("Cosa ci inventeremo ancora per mettere alla prova le tue capacità ? Non lo sappiamo nemmeno noi ! ");
	output("Ma il nostro prode `!`bMightyE`b`% ci è venuto in soccorso. Come nella prova precedente dovrai far ");
	output("fare la giravolta a qualcosa, in questo caso ad una spada fornita gentilmente da `!`bMightyE`b`%. Ma ");
	output("fai attenzione, una spada è decisamente più affilata di una frittata.`n`n");
	switch ($points1) {
		case 1:
		output("`n`n`\$Sbaglio o quelle sul tuo braccio sono ferite ? Ahi ahi ahi ... hai ottenuto un punteggio così ");
		output("scarso per un guerriero che quasi mi vergogno a dirlo: solo $points giravolte!! `nHai totalizzato <font size='+1'>`^$points punti</font> `#!!`n",true);
		$resto=100-$points;
		addnav("T?`@Torna al Castello","castelexcal.php");
		break;
		case 2: case 3: case 4:
		output("`n`n`%non sei di certo Conan, ma la tua parte nella foresta la fai onestamente, facendo assaggiare "); 
		output("il filo della tua lama alle creature che ci vivono. `nHai ottenuto <font size='+1'>`6$points punti</font> `#!!",true);
		addnav("T?`@Torna al Castello","castelexcal.php");
		break;
		case 5: case 6: case 7: case 8: case 9:
		output("`n`n`#Però, non me lo aspettavo un risultato di questo livello ! Un piccolo sforzo e forse avresti potuto battere il record. ");
		output("Hai effettuato $points giravolte con la spada. `nTi sei guadagnato <font size='+1'>`6$points punti</font> `#!!",true);
		addnav("T?`@Torna al Castello","castelexcal.php");
		break;
		case 10:
		output("`n`n`^Wow, hai polverizzato il record di `!`bMightyE`b`^ !!! Sei il nuovo campione del villaggio !! ");
		output("Con `b$points giravolte`b della spada sei diventato un mito vivente !!`n");
		output("`!`bMightyE`b`^ ti propone di entrare in società con lui !!!! ");
		output("`nHai guadagnato <font size='+1'>`6$points punti</font> `#!!!",true);
		output("`n`^Ti aggiudichi anche `b1 gemma`b bonus !!!");
		$session['user']['gems']+=1;
		addnav("T?`@Torna al Castello","castelexcal.php");
		break;
	}break;

case 15:
	output("`#Eccoci finalmente arrivati alla prova del livello più alto, il livello $level. ");
	output("Siamo riusciti a catturare un esemplare di `\$Drago Rosso`#, e adesso `bTU`b dovrai affrontarlo e ");
	output("resistere il più a lungo possibile alle terribili fiamme che si sprigionano dalle sue fauci. `n");
	output("Si lo so che stai per obiettare, ma non ti preoccupare abbiamo il miglior sciamano della contea con ");
	output("i suoi unguenti in caso di pericolo. Vai adesso, non riusciamo più a tenerlo .... `n`n");
	switch ($points1) {
		case 1:
		output("`n`n`\$Uh uh, ehm ehm ... Ti avevamo avvertito, se lo avessimo saputo che eri così delicato di pelle ");
		output("non ti avremmo permesso di disputare questa prova. Comunque, nonostante tutto hai resistito alle ");
		output("fiamme del drago per $points secondi. `nHai totalizzato <font size='+1'>`^$points punti</font> `#!!`n",true);
		$resto=100-$points;
		addnav("T?`@Torna al Castello","castelexcal.php");
		break;
		case 2: case 3: case 4:
		output("`n`n`%Beh dai, le ustioni che hai riportato sono solo del 3° grado, e solo sul 40% del tuo corpo. "); 
		output("Noi te lo avevamo detto di mettere la tuta ignifuga, ma tu non hai voluto sentire ragioni. `nHai ottenuto <font size='+1'>`6$points punti</font> `#!!",true);
		addnav("T?`@Torna al Castello","castelexcal.php");
		break;
		case 5: case 6: case 7: case 8: case 9:
		output("`n`n`#Ti sei allenato nel deserto del Sahara per caso ? Sembravi perfettamente a tuo agio tra le fiamme ");
		output("che si sprigionavano dalle fauci del `\$Drago Rosso`#. Se non fosse stato per i capelli che hanno preso ");
		output("fuoco avresti potuto battere il record di `bExcalibur`b. `nTi sei aggiudicato <font size='+1'>`6$points punti</font> `#!!",true);
		addnav("T?`@Torna al Castello","castelexcal.php");
		break;
		case 10:
		output("`n`n`^Wow, hai polverizzato il record di `@`bExcalibur`b`^ !!! Sei il nuovo campione del villaggio !! ");
		output("Con `b$points secondi`b sotto le fauci fiammeggianti del `\$Drago Rosso`^ sei diventato un mito vivente !!`n");
		output("I pompieri del villaggio ti hanno eletto `i`&Pompiere dell'Anno`i`^ !!!! `n");
		output("Hai guadagnato <font size='+1'>`6$points punti</font> `#!!!",true);
		output("`n`^Ti aggiudichi anche `b1 gemma`b bonus !!!");
		$session['user']['gems']+=1;
		addnav("T?`@Torna al Castello","castelexcal.php");
		break;
	}break;

	} // chiusura switch $livello
} //chiusura else riga 14

page_footer();
?>

