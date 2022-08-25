<?php
require_once "common.php";
require_once "common2.php";
checkday();

page_header("Armature da Pegasus");
output("`c`b`%Armature da Pegasus`0`b`c`n");
$session['user']['locazione'] = 106;
$tradeinvalue = round(($session['user']['armorvalue']*.75),0);
// Maximus Inizio: Usura Arma e Armatura
if ($session['user']['armordef'] > 0){
    $usura_max = intval($session['user']['armordef'] * max((15 + $session['user']['dragonkills']/2 - 2*$session['user']['reincarna']),10)) + 100;
    $percentuale = intval(100 - (($session['user']['usura_armatura'] * 100) / ($usura_max)));
    if ($percentuale > 100) $percentuale=100;
    $differenza = round($tradeinvalue  * $percentuale / 100);
    $tradeinvalue -= $differenza;
}

// Maximus Fine: Usura Arma e Armatura

if ($_GET['op']==""){
	if (!$session['user']['sex']) {
	    output("`5La dolce e bellissima `#Pegasus`5 ti saluta con un caldo sorriso mentre sali sul suo carrozzone da zingara ");
	    output("dai vividi colori, che, stranamente, si trova proprio accanto all'armeria di `!MightyE`5. Gli abiti indossati dalla bella zingara sono ");
	    output("di colori brillanti e sfarzosi quasi quanto il suo carrozzone, ed è quasi, ma non del tutto, sufficiente a farti distogliere lo sguardo dai suoi grandi ");
	    output("occhi grigi e dai tratti di pelle che si intravvedono tra i suoi capi gitani non proprio sufficienti a coprire tutto il suo corpo flessuoso.");
	    output("`n`n");
	}else{
   		output("`5Mentre sali sul carrozzone da zingara di `#Pegasus`5 ti si fa incontro `#Raphael`5, un giovane prestante e decisamente affascinante, che ti saluta con un caldo sorriso e ti informa che la sua padrona è indaffarata con `!MightyE`5 a provare alcune armi giunte da poco da mercati lontani.");
	    output("Incominci a fare gli occhi dolci al possente venditore di armature e quasi ti dimentichi il motivo per cui sei entrata in questo negozio mobile dal momento che sei distratta dall' avvenente giovane e dalla sua notevole muscolatura. ");
	    output("Alla fine ti ricordi che devi acquistare una nuova armatura e, a malincuore, distogli lo sguardo da `#Raphael`5 per incominciare a esaminare le merci esposte nel negozio ambulante.");
	    output("`n`n");
	}    
    addnav("Guarda le merci di `#Pegasus`0","armor.php?op=browse");
    addnav("Armeria di `!MightyE`7","weapons.php");
    addnav("Torna al villaggio","village.php");
}else if ($_GET['op']=="browse"){

	$identarmatura=array();
	$ident_armatura = identifica_armatura();
	$articoloarmatura = $ident_armatura['articolo'];
    $maledetta = $ident_armatura['maledetta'];
    $tshirt = $ident_armatura['tshirt'];
   
    $sql = "SELECT max(level) AS level FROM armor WHERE level<=".$session['user']['dragonkills'];
    $result = db_query($sql) or die(db_error(LINK));
    $row = db_fetch_assoc($result);

  	$sql = "SELECT * FROM armor WHERE level=$row[level] ORDER BY value";
    $result = db_query($sql) or die(db_error(LINK));
    if (!$session['user']['sex']) {
	    output("`5Guardi i vari articoli di equipaggiamento esposti, e ti domandi se `#Pegasus`5 sarebbe tanto carina da provarne qualcuno ");
	    output("per te, quando ti rendi conto che è impegnata a fissare con sguardo sognante `!MightyE`5 attraverso la vetrina del suo negozio ");
	    output("mentre questi, a torso nudo, offre una dimostrazione dell'uso di una delle sue armi in vendita ad un cliente. Notando per un attimo che, finalmente ");
	    output("stai guardando la sua merce, con occhio esperto `#Pegasus`5 valuta con rapidamente $articoloarmatura `#{$session['user']['armor']}`5 e ti dice che ");
    }else{
	    output("`5Guardi i vari articoli di equipaggiamento in esposizione, chiedendoti se `#Raphael`5 sarebbe tanto carino da provarne qualcuno ");
	    output("per te, quando ti rendi conto che il bel venditore è impegnato a fissare con sguardo sognante `!MightyE`5 attraverso la vetrina del suo negozio ");
	    output("mentre questi, a torso nudo, offre una dimostrazione dell'uso di una delle sue armi in vendita alla zingara `#Pegasus`5. Notando per un attimo che, ");
	    output("finalmente stai guardando la sua merce, `#Raphael`5 con occhio esperto valuta con rapidamente $articoloarmatura `#{$session['user']['armor']}`5 e ti dice che ");
	}    
	if ($maledetta) {    
	 	 output("trattandosi di una armatura posseduta da un oscuro potere malvagio non può venderti alcuna delle sue merci in quanto innanzitutto non riusciresti a levartela di dosso e che in ogni caso non l'acquisterebbe perchè poi non riuscirebbe più a rivenderla dato che nessuno sarebbe disposto a comprare un oggetto maledetto.");
	}else{ 
		if ($tshirt) {
			output("non può offrirti nulla in cambio.");
		}else{ 
	    	output("può essere in grado di offrirti immediatamente `^$tradeinvalue`5 monete d'`^oro`5.");
	    }
    }
    output("`n`n<table border='0' cellpadding='0'>",true);
    output("<tr class='trhead'><td>`bNome`b</td><td align='center'>`bDifesa`b</td><td align='right'>`bCosto`b</td></tr>",true);
    $countrow = db_num_rows($result);
    for ($i=0; $i<$countrow; $i++){
      $row = db_fetch_assoc($result);
        $bgcolor=($i%2==1?"trlight":"trdark");
        if ($maledetta) { 
        	output("<tr class='$bgcolor'><td>`7$row[armorname]</td><td align='center'>$row[defense]</td><td align='right'>$row[value]</td></tr>",true);
	        addnav("","armor.php?op=buy&id=$row[armorid]");
        }else{
        	if ($row['value']<=($session['user']['gold']+$tradeinvalue)){
	            output("<tr class='$bgcolor'><td><a href='armor.php?op=buy&id=$row[armorid]'>`&$row[armorname]</a></td><td align='center'>$row[defense]</td><td align='right'>$row[value]</td></tr>",true);
	            addnav("","armor.php?op=buy&id=$row[armorid]");
	        }else{
	            output("<tr class='$bgcolor'><td>`7$row[armorname]</td><td align='center'>$row[defense]</td><td align='right'>$row[value]</td></tr>",true);
	            addnav("","armor.php?op=buy&id=$row[armorid]");
	        }
        }
    }
    output("</table>",true);
    addnav("Armeria di `!MightyE`7","weapons.php");
    addnav("Torna al Villaggio","village.php");
}else if ($_GET['op']=="buy"){
	$ident_armatura=array();
	$ident_armatura = identifica_armatura();
	$articoloarmatura = $ident_armatura['articolo'];
    $maledetta = $ident_armatura['maledetta'];
    $tshirt = $ident_armatura['tshirt'];
    
  	$sqln = "SELECT * FROM armor WHERE armorid='$_GET[id]'";
    $resultn = db_query($sqln) or die(db_error(LINK));
    if (db_num_rows($resultn)==0){
      	output("`#Pegasus`5 ti guarda per un secondo, confusa, poi capisce che evidentemente devi aver presto troppe botte in testa, annuisce e ti sorride.");
        addnav("Ritenta","armor.php");
        addnav("Torna al Villaggio","village.php");
    }else{
      $rown = db_fetch_assoc($resultn);
        if ($rown['value']>($session['user']['gold']+$tradeinvalue)){
        	output("`5Aspettando che `#Pegasus`5 sia distratta, raggiungi attentamente il `%$row[armorname]`5, che rimuovi attentamente dalla pila di tessuti sui cui ");
            output("si trova. Certo del tuo furto, inizi a voltarti solo per scoprire che il tuo movimento è ostacolato da un pugno chiuso strettamente intorno alla tua ");
            output("gola. Guardando in basso, segui il pugno fino al braccio a cui è attaccato, che è a sua volta attaccato ad un muscolosissimo `!MightyE`5. Tenti di ");
            output("spiegare cosa è successo, ma la tua gola non sembra in grado di aprirsi per far passare la voce, per non parlare dell'essenziale ossigeno.  ");
            output("`n`nMentre la vista ti si oscura, mandi uno sguardo pietoso ma inutile a `%Pegasus`5 che sta osservando con aria sognante `!MightyE`5, con le ");
            output("mani giunte accanto al viso adornato da un grande sorriso di ammirazione.`n");
            $session['user']['alive']=false;
            debuglog("ha perso " . $session['user']['gold'] . " pezzi d'oro per aver tentato di derubare Pegasus");
            $session['user']['gold']=0;
            $session['user']['hitpoints']=0;
            $session['user']['experience']=round($session['user']['experience']*.9,0);
            output("`b`&Sei stato ucciso da `!MightyE`&!!!`n");
            output("`6Hai perso tutto l'oro che avevi con te!`n");
            output("`4Hai perso il 10% della tua esperienza!`n");
            output("`!Potrai ricominciare a combattere domani.`b");
            addnav("Notizie giornaliere","news.php");
            addnews("`%".$session['user']['name']."`5 è stato ucciso da `!MightyE`5 per aver tentato di rubare dal carrozzone di `#Pegasus`5.");
        }else{
        	if ($tshirt) {
            	output("" . ($session['user']['sex']?"`#Raphael`5":"`#Pegasus`5") . " intasca le monete, e ti sorride complimentandosi per la tua scelta mentre ti porge una splendida armatura nuova di zecca : $rown[armoraggpos] `#$rown[armorname]`5.");
            }else{
				output("" . ($session['user']['sex']?"`#Raphael`5":"`#Pegasus`5") . " intasca le monete, prende anche $articoloarmatura `#{$session['user']['armor']}`5 e, con tua grande sorpresa, rapidamente vi attacca un cartellino con un prezzo maggiorato rispetto a quanto ti ha appena pagato mette il tutto in bella mostra su un'altra pila di capi. ");
            	output("In cambio ti porge una splendida armatura nuova di zecca : $rown[armoraggpos] `#$rown[armorname]`5.");
            }
			output("`n`nVorresti protestare ma " . ($session['user']['sex']?"ammaliata dal sorriso dell'affascinante venditore":"ammaliato dal sorriso dell'affascinate venditrice") . " riesci solo a biascicare: `0Indossare solamente `#$rown[armorname]`0 non sarà una cosa stupida?`0 ");
            output ("`5`nCi pensi per un attimo e poi ti rendi conto che tutti quanti in città stanno facendo lo stesso e quindi con una alzata di spalle incominci ad osservarti da ogni lato, pavoneggiandoti, per l'acquisto appena effettuato. ");
            debuglog("ha speso " . ($rown['value']-$tradeinvalue) . " pezzi d'oro per " . $rown['armorname'] . " armatura");
            $session['user']['gold']-=$rown['value'];
            $session['user']['armor'] = $rown['armorname'];
            $session['user']['gold']+=$tradeinvalue;
            $session['user']['defence']-=$session['user']['armordef'];
            $session['user']['armordef'] = $rown['defense'];
            $session['user']['defence']+=$session['user']['armordef'];
            $session['user']['armorvalue'] = $rown['value'];
            // Maximus Inizio: Usura Arma e Armatura
            $usura_max = intval($rown['defense'] * max((15 + $session['user']['dragonkills']/2 - 2*$session['user']['reincarna']),10)) + 100;
            $session['user']['usura_armatura'] = $usura_max;
            // Maximus Fine: Usura Arma e Armatura
            addnav("Armeria di `!MightyE`7","weapons.php");
            addnav("Torna al Villaggio","village.php");
        }
    }
}
page_footer();
?>