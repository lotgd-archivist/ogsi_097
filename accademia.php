<?php
require_once "common.php";
page_header("L'accademia di arti e mestieri");
$session['user']['locazione'] = 101;
output("`^`c`bL'accademia di arti e mestieri`b`c`6");

if ($_GET['op']==""){
	checkday();
	output("`3Questa costruzione è la sede delle gilde dei lavoratori di Rafflingate.`n`n");
	output("Qui puoi rivolgerti a loro per migliorare la tua bravura nel loro mestiere, oppure comprare un'assicurazione che ti copra nel caso di morte sul luogo di lavoro.");
	addnav("M?Gilda dei Minatori","accademia.php?op=minatori");
	addnav("T?Gilda dei Taglialegna","accademia.php?op=taglialegna");
	addnav("F?Gilda dei Falegnami","accademia.php?op=falegnami");
	addnav("A?Ufficio delle Assicurazioni","assicurazioni.php");
	addnav("S?Maestro delle Specialità","myrrdin.php");
}else if($_GET['op']=="minatori"){
	output("`3Entri nella gilda dei minatori. `n`nAl suo interno c'è parecchio movimento, noti alcune persone intente a discutere di un tunnel bloccato da un macigno,");
	output("dall'altro lato della stanza un giovane nano si sta addestrando nell'utilizzo del piccone");
	output("Un vecchio seduto su uno sgabello ti osserva entrare, poi si rivolge a te: \"`!Vorresti esercitarti anche tu nell'utilizzo del piccone?");
	output("Ti costerà `^500 pezzi d'oro `!e `\$2 combattimenti nella foresta`!, ma qui non correrai nessun rischio e migliorerai comunque le tue abilità di minatore.`n");
	output("Oppure per `^10 pezzi d'oro `!ti dirò quanto sei bravo nel nostro mestiere.`3\"`n");
	addnav("Esercitati","accademia.php?op=espiccone");
	addnav("Chiedi quanto sei bravo","accademia.php?op=bravopiccone");
	addnav("Torna all'Accademia","accademia.php");
}else if($_GET['op']=="espiccone"){
    if ($session['user']['gold'] < 500) {
        output("Non hai abbastanza oro.`n");
    } elseif ($session['user']['turns'] < 2) {
        output("Sei troppo stanco per esercitarti.`n");
    } else {
        output("`3Il vecchio minatore ti consegna un piccone, e ti indica una parete della stanza: \"`!Esercitati su");
        output("quella parete, è magica ed è fatta apposta per essere presa a picconate! Non ti preoccupare di colpire piano, verrà riparata al termine della tua esercitazione.`3\"`n`n");
        output("Colpisci incessantemente la parete per quasi un'ora, continuando a riempirla di buchi, fino a quando le braccia dolenti non riescono più a sollevare il piccone.`n`n");
        output("`^Le tua capacità di minatore sono migliorate!");
        $aumento=(e_rand(3,5)/100);
        $session['user']['gold']-=500;
        $session['user']['turns']-=2;
        $session['user']['minatore']+=$aumento;
    }
	addnav("M?Gilda dei Minatori","accademia.php?op=minatori");
}else if($_GET['op']=="bravopiccone"){
    if ($session['user']['gold'] < 10) {
        output("Non hai abbastanza oro.`n");
    } else {
        output("`3Il vecchio minatore dice \"`!I tuoi muscoli mi dicono che la tua abilità è : ".intval($session['user']['minatore']) ." `3\"`n");
        $session['user']['gold']-=10;
    }
	addnav("M?Gilda dei Minatori","accademia.php?op=minatori");
}else if($_GET['op']=="taglialegna"){
	output("`3Entri nella gilda dei taglialegna. `n`nIl suo interno è incredibilmente pieno di piante, non hai idea di quale magia le faccia crescere;");
	output(" diverse persone, di età e razza diversa, si esercitano nel taglio e nell'abbattimento di questi alberi.");
	output("Un vecchio uomo seduto su un tronco d'albero ti vede entrare e ti dice: \"`!Vorresti esercitarti anche tu nell'utilizzo dell'ascia da legna?");
	output("Ti costerà `^500 pezzi d'oro `!e `\$2 combattimenti nella foresta`!, ma qui non correrai nessun rischio e migliorerai comunque le tue abilità di taglialegna.`n");
	output("Oppure per `^10 pezzi d'oro `!ti dirò quanto sei bravo nel nostro mestiere.`3\"`n");
	addnav("Esercitati","accademia.php?op=esascia");
	addnav("Chiedi quanto sei bravo","accademia.php?op=bravoascia");
	addnav("Torna all'Accademia","accademia.php");
}else if($_GET['op']=="esascia"){
    if ($session['user']['gold'] < 500) {
        output("Non hai abbastanza oro.`n");
    } elseif ($session['user']['turns'] < 2) {
        output("Sei troppo stanco per esercitarti.`n");
    } else {
        output("`3Il vecchio taglialegna ti consegna un'ascia, e ti dice: \"`!Esercitati anche tu su queste piante! Sono magiche, puoi abbatterne anche tutte,");
        output("tanto possiamo farle ricrescere a volontà.`3\"`n`n");
        output("Cominci ad abbattere alberi ed a tagliarne i rami con gli altri taglialegna, fino a quando le braccia dolenti non riescono più a tenere alzata l'ascia.`n`n");
        output("`^Le tua capacità di taglialegna sono migliorate!");
        $aumento=(e_rand(3,5)/100);
        $session['user']['gold']-=500;
        $session['user']['turns']-=2;
        $session['user']['minatore']+=$aumento;
    }
	addnav("T?Gilda dei Taglialegna","accademia.php?op=taglialegna");
}else if($_GET['op']=="bravoascia"){
    if ($session['user']['gold'] < 10) {
        output("Non hai abbastanza oro.`n");
    } else {
        output("`3Il vecchio taglialegna dice \"`6I tuoi muscoli mi dicono che la tua abilità è : ".intval($session['user']['boscaiolo']) ." `3\"`n");
        $session['user']['gold']-=10;
    }
	addnav("T?Gilda dei Taglialegna","accademia.php?op=taglialegna");
}else if($_GET['op']=="falegnami"){
	output("`3Questa gilda non è ancora aperta");
	addnav("Torna all'Accademia","accademia.php");
}
addnav("Uscita");
addnav("V?Torna al Villaggio","village.php");

page_footer();

?>