<?php
require_once "common.php";
page_header("L'ufficio delle assicurazioni");

output("`^`c`bL'ufficio delle assicurazioni`b`c`6");
$costo=$session['user']['level']*50;
if($session['user']['assicurazione']==0) {
	if ($_GET['op']==""){
		checkday();
		output("`5Una grassa donna ti saluta apaticamente da dietro la sua scrivania.`n`n");
		output("\"`3Buongiorno, ".$session['user']['name']."`3, è venuto qui per assicurarsi? Le spiego subito in cosa consiste la nostra assicurazione.`n");
		output("Praticamente lei paga `^$costo `3pezzi d'oro, ed in caso di morte in miniera o nel bosco dei taglialegna tutto l'oro che aveva con se al momento del decesso,");
		output("invece di andare perso le verrà accreditato sul suo conto in banca. Se lei dovesse malauguratamente morire in altri luoghi o in altre circostanze, l'assicurazione non coprirà la sua perdita.`n");
		output("La nostra assicurazione la coprirà solo fino al suo prossimo nuovo giorno`\"`5`n`n");
		output("Intendi assicurarti in caso di morte in uno dei luoghi coperti?");
		if ($session['user']['gold']>=$costo) addnav("Assicurati","assicurazioni.php?op=assicurati");
	}else if($_GET['op']=="assicurati"){
	    output("`5Paghi i $costo pezzi d'oro richiesti, mentre la donna compila svogliatamente un modulo che poi ti fa firmare.`n`n");
		output("\"`3Molto bene, ".$session['user']['name']."`3, adesso la sua morte è coperta dalla nostra assicurazione. Le auguro una buona giornata`\"`5`n`n");
	    output("Non avendo più nulla da fare qua dentro, ti dirigi verso la porta per uscire dall'ufficio.");
		$session['user']['assicurazione']=1;
		$session['user']['gold']-=$costo;
		debuglog("si assicura contro morte in miniera o nel bosco dei taglialegna pagando $costo pezzi d'oro");
	}
}else {
	output("`5Ma tu sei già assicurato per oggi!!! A cosa ti serve assicurarti di nuovo?`n`n");
    output("Non avendo più nulla da fare qua dentro, ti dirigi verso la porta per uscire dall'ufficio.");
}
addnav("Uscita");
addnav("Torna all'Accademia","accademia.php");
addnav("Torna al Villaggio","village.php");

page_footer();

?>