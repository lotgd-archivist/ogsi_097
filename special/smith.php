<?php
require_once "common2.php";
$session['user']['specialinc']="smith.php";
$scelta=array();
$scelta['weapon']="arma";
$scelta['armor']="armatura";
$opzione="none";
output("`n`c`b`#Faber il Fabbro!`b`c`n",true);

if ($_GET['op']=="" || $_GET['op']=="search"){
    output("`7Cammini con circospezione nella foresta quando ti imbatti improvvisamente, alle pendici di una collinetta, in una grotta dalla quale appare, venendoti incontro, un massiccio individuo che tiene in una mano un enorme martello.  ");
    output("Fiducios".($session[user][sex]?"a":"o")." che quell'uomo non rappresenti per te una minaccia, ti avvicini a lui e inizi una conversazione : \"`&`iSalve, buonuomo! Come vi chiamate`i?`7\"`n`n");
    output("\"`6`iMi chiamo `#Faber`6`i`7\" risponde l'altro.");
    output("`n`n\"`i`&Come? Cosa fate qui? Di cosa vi occupate? `i`7\" chiedi, mostrando una certa inquietudine.");
    output("`n`n\"`i`#Faber`6, questo è il mio nome.  Sono un fabbro.  Qualcuno mi chiama `#Faber il Fabbro`6.  E offro a tutti i viandanti che passano da queste parti i miei servigi di fabbro per un piccolo obolo.");
    output("`nPer solo `&1 gemma`6, posso provare a migliorare la tua arma o la tua armatura. Le mie capacità sono enormi, ma ti avviso, sebbene sia il miglior fabbro di questa regione raramente faccio anche io qualche errore, e non sempre mi riesce di ottenere il meglio dalle qualità degli oggetti su cui intervengo.`i`7\"");
    output("`n`n`7Incuriosit".($session[user][sex]?"a":"o")." dalle parole del fabbro, ti fermi un attimo a riflettere sull'opportunità che ti si è presentata. Certo esiste qualche margine di rischio, ma confidando nell'abilità di `#Faber`7 forse la spesa di una misera `&gemma`7 può rivelarsi un affare vantaggioso.");  
    addnav("Chiedi di migliorare la tua Arma ","forest.php?op=weapon");
    addnav("Chiedi di migliorare la tua Armatura ","forest.php?op=armor");
    addnav("Non migliorare nulla","forest.php?op=none");
}elseif ($_GET['op']=="none"){
    output("`7Ritieni che le tue armi e la tua armatura non necessitino dell'intervento di `#Faber`7, quindi prosegui per la tua strada mentre lui ti augura una buona giornata e prosegui e rientra nella sua grotta.");
    $session['user']['specialinc']="";
}elseif ($session['user']['gems']>0){

	$session['user']['specialinc']="";
    $previously_upgraded   = strpos($session['user'][$_GET['op']]," +1")!==false ? true : false;
    $previously_downgraded = strpos($session['user'][$_GET['op']]," -1")!==false ? true : false;
	$opzione = $_GET['op'] ;
	    
	if ($_GET['op']=="armor") {
		$ident_armatura=array();
		$ident_armatura = identifica_armatura();
		$articoloarmatura = $ident_armatura['articolo'];
    	$maledetta = $ident_armatura['maledetta'];	
    	$tshirt = $ident_armatura['tshirt'];
		output("`7Porgi a `#Faber`7 $articoloarmatura `#");
	}else{
		$ident_arma=array();
		$ident_arma = identifica_arma();
		$articoloarma = $ident_arma['articolo'];
    	$maledetta = $ident_arma['maledetta'];
    	$pugni = $ident_arma['pugni'];
		output("`7Porgi a `#Faber`7 $articoloarma ");			
	}	
    output("`# ".$session['user'][$_GET['op']]." ");	
    if( $maledetta){
    	output("`7, che egli osserva attentamente.  `n`n`6\"`iAah, vedo che questa ".$scelta[$opzione]." è maledetta. No, mi spiace ".$session['user']['name']."`6 ho paura che non ci sia nulla che io possa fare per rimuovere la maledizione che la impregna. Buona fortuna a te amic".($session[user][sex]?"a":"o")." mi".($session[user][sex]?"a":"o")." !`i`7\" E detto questo rientra nella sua grotta.");
    }else{ 
    	if ($tshirt OR $pugni) {
    		if ($tshirt) {
    			output("`7, che egli osserva attentamente.  `n`n`6\"`iAahaha, vedo che sei veramente spiritos".($session[user][sex]?"a":"o")." ".$session['user']['name']." `6ma io sono un fabbro non un sarto, e quindi non posso migliorare $articoloarmatura `#".$session['user'][$_GET['op']]."`6 !`i`7\"  E detto questo rientra nella sua grotta.");
    		}else{
	    		output("`7, che egli osserva attentamente.  `n`n`6\"`iAahaha, vedo che sei molto spiritos".($session[user][sex]?"a":"o")." ".$session['user']['name']." `6ma io sono un fabbro e quindi non posso migliorare $articoloarma `#".$session['user'][$_GET['op']]."`6! Non è a me che ti devi rivolgere ma all'allenatore di una palestra ! Ahahahaha `7`i\"  E detto questo rientra nella sua grotta.");			}
		}else{   
			if ((strchr($session['user']['weapon'],"allenamento")) OR (strchr($session['user']['weapon'],"Spada Lucente della Caverna Oscura"))) {
				if (strchr($session['user']['weapon'],"Spada Lucente della Caverna Oscura")) {
					output("`7, che egli osserva attentamente.  `n`n`6\"`iAah, vedo che questa ".$scelta[$opzione]." è impregnata di un grande potere magico, quindi come può un, seppur abile, fabbro migliorare la magia?`7");
		    		output("`n\"`6No, mi spiace ".$session['user']['name']."`6 ho paura che non ci sia nulla che io possa fare per migliorarla ulteriormente. Lunga vita a te amic".($session[user][sex]?"a":"o")." mi".($session[user][sex]?"a":"o")."!`7`i\"  E detto questo rientra nella sua grotta.");
				}else{
					output("`7, che egli osserva attentamente.  `n`n`6\"`iAah, vedo che questa ".$scelta[$opzione]." ti è stata data dai maestri, e io non spreco la mia abilità alla forgia per oggetti di allenamento. Lunga vita a te amic".($session[user][sex]?"a":"o")." mi".($session[user][sex]?"a":"o")."!`7`i\"  E detto questo rientra nella sua grotta.");
				}
			}else{
		    	if ($previously_upgraded){
		    	    output("`7, che egli osserva attentamente.  `n`n`6\"`iAah, vedo che questa ".$scelta[$opzione]." è già stata potenziata rispetto alle sue condizioni iniziali. Quindi mi chiedo, come posso migliorare la perfezione?`7");
		    	    output("`n\"`6No, mi spiace ".$session['user']['name']."`6 ho paura che non ci sia nulla che io possa fare per migliorarla ulteriormente. Lunga vita a te amic".($session[user][sex]?"a":"o")." mi".($session[user][sex]?"a":"o")."!`7`i\"  E detto questo rientra nella sua grotta.");
		    	}elseif ($previously_downgraded){
		    	    output("`7, che egli osserva attentamente.  `6\"`iAah, vedo che un dilettante ha lavorato su questa ".$scelta[$opzione]." e ha compiuto veramente uno scempio! Ma non ti preoccupare,");
		    	    output("posso riparare facilmente il danno!`i`7\" detto questo ritorna rapidamente nella grotta che utilizza come fucina e scompare. Dall'esterno puoi sentire un gran baccagliare del martello sull'incudine, il soffiare del mantice e lo sfrigolio del metallo incandescente messo a raffreddare nell'acqua. Vorresti entrare per osservare il fabbro al lavoro, ma intimorito non osi farti avanti. 
		    	    	Poi, dopo un lungo silenzio, `#Faber`7 esce dalla grotta e ti restituisce la tua ".$scelta[$opzione].", migliorata e senza più la penalizzazione che aveva prima.");
		    	    $session['user']['gems']--;
		    	    $session['user'][$_GET['op']."value"]*=1.33333;
		    	    $session['user'][$_GET['op']] = str_replace(" -1","",$session['user'][$_GET['op']]);
		    	    $session['user'][($_GET['op']=="weapon"?"attack":"defence")]++;
		    	    $session['user'][$_GET['op'].($_GET['op']=="weapon"?"dmg":"def")]+=1;
		    	}else{
		    	    $session['user']['gems']--;
		    	    $r = e_rand(1,100);
		    	    if ($r<30){
		    	        output("`7, che egli osserva attentamente.  \"`i`6Sono spiacente, ma non c'è molto che io posso fare qui amico.`7`i\" dice restituendoti la tua ".$scelta[$opzione].".");
		    	    }elseif ($r<90){
		    	        output("`7, che egli osserva per un momento, quindi si inoltra nella grotta che utilizza come fucina e scompare. Dall'esterno puoi sentire un gran baccagliare del martello sull'incudine, il soffiare del mantice e lo sfrigolio del metallo incandescente messo a raffreddare nell'acqua. Vorresti entrare per osservare il fabbro al lavoro, ma intimorito non osi farti avanti. Poi, dopo un lungo silenzio, `#Faber`7 esce dalla grotta e ti restituisce la tua ".$scelta[$opzione].", migliorata rispetto a prima!`n");
		    	        $session['user'][$_GET['op']] = $session['user'][$_GET['op']]." +1";
		    	        $session['user'][$_GET['op'].($_GET['op']=="weapon"?"dmg":"def")]+=1;
		    	        $session['user'][($_GET['op']=="weapon"?"attack":"defence")]++;
		    	        $session['user'][$_GET['op']."value"]*=1.33333;
		    	    }else{
		    	        output("`7, che egli osserva per un momento, quindi si inoltra nella grotta che utilizza come fucina e scompare. Dall'esterno puoi sentire un gran baccagliare del martello sull'incudine ma intimorito non osi farti avanti per dare una sbirciata. Poi, dopo un lungo silenzio, `#Faber`7 esce dalla grotta, senza dirti nulla ti restituisce la tua ".$scelta[$opzione].", peggiorata rispetto a prima, e prima che tu possa esprimere le tue rimostranze per il cattivo lavoro compiuto rientra nella sua grotta lasciandoti con un palmo di naso a rimuginare sulla `&gemma`7 letteralmente regalata a un imbroglione!`n");
		    	        $session['user'][$_GET['op']] = $session['user'][$_GET['op']]." -1";
		    	        $session['user'][$_GET['op'].($_GET['op']=="weapon"?"dmg":"def")]-=1;
		    	        $session['user'][($_GET['op']=="weapon"?"attack":"defence")]--;
		    	        $session['user'][$_GET['op']."value"]*=0.75;
		    	    }
		    	}
		    }	
	    }	
    }
}else{
    output("`7Purtroppo il tuo borsellino è vuoto e non hai la `&gemma`7 necessaria a pagare `#Faber`7, il fabbro che potrebbe migliorare il tuo equipaggiamento. A questo punto non ti resta che tornare verso la foresta, rimpiangendo le `&gemme`7 sperperate in acquisti inutili.");
    $session['user']['specialinc']="";
}
?>