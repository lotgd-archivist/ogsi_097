<?php
/*****************************************/
/* Il Laghetto                           */
/* -----------                           */
/* Written by Excalibur                  */
/* (da un'idea di Magikko)               */
/* Revised by Hugues & Thalya            */
/*****************************************/

function emettisentenza($guadagniperdi,$testopositivo,$testonegativo){
    global $session;
	if ($guadagniperdi == 0) {
	    
	} else {			
    	if ($guadagniperdi < 0) {
	    	output("`n`n$testonegativo");
	    } else {		
    		output("`n`n$testopositivo");
    	}
    }	
}
page_header("Il laghetto");
$session['user']['specialinc']="laghetto.php";
switch($_GET[op]){
case "":
    output("`n `3Ti ritrovi in una zona lacustre, dove una densa bruma grigiastra avvolge tutto come in una cappa di piombo impedendoti addirittura di scorgere il terreno su cui stai camminando.`n");
    output(" `3Avanzi con molta cautela per timore di mettere il piede in qualche trappola, quando la nebbia di colpo scompare facendoti scoprire che il terreno lentamente digrada in un delizioso specchio d'acqua azzurrino.`n");
    output("`3Alla tua sinistra un canneto con altissime canne di bambù che si ergono dall'acqua, mentre al centro del laghetto delle splendide ");
    output("`3 ninfee sono attorniate da sciami di lucciole che ti consentono di vedere chiaramente nonostante la giornata si stia già avviando verso l'imbrunire.`n`n");
    output("`3 Appare dal nulla una picola `^pixie`3 che, ridacchiando mentre ti svolazza intorno, si rivolge a te dicendoti: `n\"`6Valoros".($session['user']['sex']?"a":"o")." guerrier".($session['user']['sex']?"a":"o").", questo laghetto ");
    output(" è incantato, chi si specchia sulla sua superficie ha la possibilità di conoscere esattamente il valore del proprio fascino, ma ti ");
    output(" avverto, spesso la `&Dama del Lago`6 appare e lancia un incantesimo sull'incauto viaggiatore che si sofferma a specchiarsi e i risultati non sempre sono piacevoli.`3\"`n`n");
    output("`3Cosa vuoi fare ?");
    addnav("`@Specchiati","forest.php?op=specchia");
    addnav("`\$Prosegui nella foresta","forest.php?op=lascia");
    $session['user']['specialinc']="laghetto.php";
    
	
break;

case "specchia":
	page_header("La Dama del Lago");
    output("`n`3La tua vanità ti spinge ad avvicinarti moltissimo alla riva per osservare meglio il tuo riflesso nello specchio d'acqua e ... la superficie del lago prima calma incomincia ad incresparsi 
    	sempre più sino ad assumere la forma di un vortice dal quale poi emerge l'eterea figura di una donna dalla meravigliosa bellezza: la `&Dama del Lago`3.`n`n");
    
    $sql = "SELECT count(*) as numgiocatori, sum(charm) as totfascino, max(charm) as maxfascino FROM accounts WHERE dragonkills>0 and superuser=0 ";
    $result = db_query($sql) or die(db_error(LINK));   
	$row = db_fetch_assoc($result);
	$numgiocatori = $row['numgiocatori'];
	$totfascino = $row['totfascino'];
	$maxfascino = $row['totfascino'];
	
	$mediafascino = intval($totfascino / $numgiocatori);
	$primolimite = intval($mediafascino / 2);
	$secondolimite = $mediafascino;
	$fasciasup = intval(($maxfascino - $mediafascino) / 5 );
	$terzolimite = $mediafascino + $fasciasup;
	$quartolimite = $terzolimite + $fasciasup;
	$quintolimite = $quartolimite + $fasciasup;
	$sestolimite = $quintolimite + $fasciasup;
	$settimolimite = $maxfascino;

	$fascino = $session['user']['charm'];
	$guadagniperdi = e_rand(-3,3); 
	$testoguadagniperdi = abs($guadagniperdi);
	
	if ($fascino == 0) {
		$fascia = 0 ;
		$guadagniperdi = 2;
	} else {		
	    if ($fascino > 0 AND $fascino < $primolimite ) { 
		    $fascia = 1 ;
	    } else {
			if ($fascino > $primolimite AND $fascino < $secondolimite ) { 
		    	$fascia = 2 ;  		
	    	} else {
				if ($fascino > $secondolimite AND $fascino < $terzolimite ) { 
		    		$fascia = 3 ;
			    } else {
					if ($fascino > $terzolimite AND $fascino < $quartolimite ) { 
			    		$fascia = 4 ;		
		    		} else {
						if ($fascino > $quartolimite AND $fascino < $quintolimite ) { 
		    				$fascia = 5 ;		    				
		    			} else {
							if ($fascino > $quintolimite AND $fascino < $sestolimite ) { 
		    					$fascia = 6 ;		    					
		    				} else {
			    				if ($fascino > $sestolimite AND $fascino < $settimolimite ) {
	    							$fascia = 7 ;
		    					} else {
			    					if ($fascino == $settimolimite OR $fascino > $settimolimite) {	
				    					$fascia = 8 ;	
				    				}				    				}	
		    				}
		    			}
		    		}
		    	}
		    }
		}
	}
		    	
    switch ($fascia) {

	    case 0:
	       	output("`3La stupenda creatura sgrana gli occhi per un istante e si ritrae inorridita dal tuo aspetto. Con voce tremante sussurra :`n`# ' Oh povera me, non pensavo assolutamente che potessero esistere 
	    		in questo mondo creature tanto ripugnanti. Guardarti è una vera sofferenza per i miei poveri occhi, ".$session['user']['name'].". `#Sappi che il tuo fascino è pari a `&".$session['user']['charm']." `#!");
	    	$testonegativo = "`3La `&Dama del Lago`3 si allontana velocemente per non dover sopportare oltre la visione della tua repellente figura ma, improvvisamente, torna sui suoi passi. Ti guarda per un solo istante con occhi
	    		 colmi di pietà e lancia su di te un incantesimo che migliora, seppur di poco, il tuo aspetto di `&$guadagniperdi`3 punti fascino !";
	    	$testopositivo = "`3La `&Dama del Lago`3 si allontana velocemente per non dover sopportare oltre la visione della tua repellente figura ma, improvvisamente, torna sui suoi passi. Ti guarda per un solo istante con occhi
	    		 colmi di pietà e lancia su di te un incantesimo che migliora, seppur di poco, il tuo aspetto di `&$guadagniperdi`3 punti fascino !"; 
	    		   	 
	    break;
	    
	    case 1:
	    	output("`3La bellissima donna socchiude gli occhi e ti squadra dalla testa ai piedi:`n`#'Era da tempo che non vedevo una creatura dall'aspetto così misero. Pover".($session['user']['sex']?"a":"o")." ".$session['user']['name'].". `#, 
	    		hai solo `&".$session['user']['charm']." `# punti fascino !'");
	    	$testonegativo = "`3La `&Dama del Lago`3 sta per andarsene ma la pietà improvvisamente svanisce lasciando posto al disprezzo. Ti si avvicina e posa il suo sguardo critico su di te mentre mormora un incantesimo che ti toglie `&$testoguadagniperdi`3 punti fascino!"; 
	    	$testopositivo = "`3La `&Dama del Lago`3 sta per andarsene ma la pietà ha il sopravvento. Ti si avvicina e posa il suo sguardo su di te mentre mormora un incantesimo che ti regala `&$testoguadagniperdi`3 punti fascino! " ;	
	    			
	    break;
	    
	    case 2:
	    	output("`3Il suo bellissimo volto si incupisce mentre ti guarda accigliata.`n`# 'Devi proprio fare qualcosa per curare il tuo aspetto ".$session['user']['name']."`#, sei a malapena accettabile avendo un fascino di soli `&".$session['user']['charm']." `# punti!'");
	    	$testonegativo = "`3La `&Dama del Lago`3 sta per svanire nuovamente nel lago quando ha un ripensamento. `#'Ma come è possibile lasciarsi andare così?'`3 ti domanda acidamente, mentre pronuncia un incantesimo che ti toglie `&$testoguadagniperdi`3 punti fascino!"; 
	    	$testopositivo = "`3La `&Dama del Lago`3 sta per svanire nuovamente nel lago quando ha un ripensamento. `#'Meglio cominciare subito a migliorare, non trovi?'`3 ti domanda, mentre pronuncia un incantesimo che ti aggiunge `&$testoguadagniperdi`3 punti fascino! " ;
	    	
	    break; 
	    
	    case 3: 
	     	output("`3Ella si sofferma per un attimo a guardarti e poi esclama : `n`#'Non c'è male, ".$session['user']['name']."`#, il tuo aspetto è curato e le vesti sono in ordine. 
	     		Sappi inoltre che il tuo fascino è di `&".$session['user']['charm']." `# punti!'");
	     	$testonegativo = "`3La `&Dama del Lago`3 si appresta ad allontanarsi ma poi ha un piccolo ripensamento. Ti si avvicina e mormora : `#'Certo che con un pò più di impegno nel curare il tuo aspetto avresti potuto essere anche meglio!'`3 e lancia su di te un incantesimo che ti toglie `&$testoguadagniperdi`3 punti fascino!"; 
	    	$testopositivo = "`3La `&Dama del Lago`3 si appresta ad allontanarsi ma poi ha un piccolo ripensamento. Ti si avvicina e mormora : `#'Meriti un piccolo segno del mio apprezzamento'`3 e lancia su di te un incantesimo che ti aggiunge `&$testoguadagniperdi`3 punti fascino! " ;
	    	
	    break; 
	    
	    case 4:
	        output("`3Le sue labbra sottili si increspano in un lieve sorriso e sussurra : `n`#'Sei proprio ".($session['user']['sex']?"graziosa":"carino").", ".$session['user']['name']."`#, e quella tua armatura mette decisamente in risalto ".($session['user']['sex']?"le tue forme":"i tuoi muscoli").".
	        	 Il tuo fascino ammonta alla bellezza di `&".$session['user']['charm']." `# punti!'");
	        $testonegativo = "`3La `&Dama del Lago`3 ti osserva ancora per qualche secondo ed aggiunge con quella che suona alle tue orecchie come una punta di invidia : `#'Certo la maggior parte del merito va a Pegasus che ti ha consigliato così bene nell'acquisto dell'armatura che indossi!'`3 e ti lancia un piccolo incantesimo che ti  toglie `&$testoguadagniperdi`3 punti fascino!	"; 
	     	$testopositivo = "`3La `&Dama del Lago`3 ti osserva ancora per qualche secondo ed aggiunge con grazia : `#'Forse si potrebbe fare ancora di meglio però … Ecco fatto!'`3 ti sfiora con la mano e il suo tocco fatato ti regala `&$testoguadagniperdi`3 punti fascino! ";
	    
	    break;
	    
	    case 5: 
	    	output("`3Il suo volto perfetto si illumina di contentezza nel contemplarti.`n`#'Ti faccio i miei complimenti per come curi il tuo aspetto, car".($session['user']['sex']?"a":"o")." ".$session['user']['name']."`#. 
	    		Sei in forma smagliante! Il tuo fascino è davvero notevole e ammonta a `&".$session['user']['charm']." `#punti!'");
	    	$testonegativo = "`3La `&Dama del Lago`3 rimane assorta nei suoi pensieri per qualche istante e poi aggiunge : `#'Conservare un aspetto tanto smagliante richiede certamente notevoli sacrifici, ma la vanità eccessiva non è una buona qualità!'`3 e lancia su di te un incantesimo che ti toglie `&$testoguadagniperdi`3 punti fascino!	"; 
	     	$testopositivo = "`3La `&Dama del Lago`3 rimane assorta nei suoi pensieri per qualche istante e poi aggiunge : `#'Conservare un aspetto tanto smagliante richiede certamente notevoli sacrifici, meriti un premio!'`3 e lancia su di te un incantesimo che ti regala `&$testoguadagniperdi`3 punti fascino! ";
	    
	    break;
	    
	    case 6:
	        output("`3Sbatte più volte le palpebre come abbagliata e sussurra con voce suadente : `n`#'Fermati quì un pò con me, ".$session['user']['name']."`# e lascia che io goda per qualche istante
	        	 nel contemplare tanto splendore. E' veramente raro incontrare creature come te dotate di `&".$session['user']['charm']." `#punti fascino!'");
	        $testonegativo = "`3La `&Dama del Lago`3 perfettamente immobile, è come assorta in contemplazione come se non riuscisse a distogliere lo sguardo dal tuo viso : `#'La tua avvenenza mi sta regalando una gioia immensa, ma ricordati che tutto passa e la bellezza è, tra tutti, il bene più effimero!'`3 e lancia su di te un incantesimo che ti toglie `&$testoguadagniperdi`3 punti fascino!	"; 
	     	$testopositivo = "`3La `&Dama del Lago`3 perfettamente immobile, è come assorta in contemplazione come se non riuscisse a distogliere lo sguardo dal tuo viso : `#'La tua avvenenza mi sta regalando una gioia immensa, accetta dunque questo piccolo dono come segno della mia riconoscenza !'`3 e lancia su di te un incantesimo che ti aggiunge `&$testoguadagniperdi`3 punti fascino! ";
	        
	    break;
	
	    case 7: 
	    	output("`3Il suo volto impallidisce e resta a guardarti a bocca aperta per parecchi minuti. Lentamente si riprende dallo stupore e balbetta : `n`#'Sei una creatura terrena o ti hanno generato gli dei? Come può esistere tanta bellezza in questo mondo imperfetto? 
	    		Io mi inchino di fronte a tanta perfezione, ".$session['user']['name']."`# e la mia voce addirittura è tremante mentre ti sono ad annunciare che possiedi ben `&".$session['user']['charm']." `#punti fascino!'`3" );
	    	$testonegativo = "`3La `&Dama del Lago`3 totalmente rapita nel contemplarti, dimentica addirittura di respirare. Improvvisamente si riscuote, ti si avvicina e scrutandoti più da vicino esclama: `#'Ecco, da qui posso scorgere qualche impercettibile segno di imperfezione … Che sollievo, solo io sono realmente perfetta!'`3 e, lasciandosi sfuggire un accenno di sogghigno, lancia su di te un incantesimo che ti toglie `&$testoguadagniperdi`3 punti fascino!	"; 
	     	$testopositivo = "`3La `&Dama del Lago`3 totalmente rapita nel contemplarti, dimentica addirittura di respirare. Improvvisamente si riscuote, ti si avvicina e scrutandoti più da vicino esclama: `#'Ecco, da qui posso scorgere qualche impercettibile segno di imperfezione … Che si può facilmente sistemare!'`3 e lancia su di te un incantesimo che ti aggiunge `&$testoguadagniperdi`3 punti fascino! ";
	        	
	    break;
	    
	    case 8: 
	    	output("`3Il suo volto impallidisce e resta pietrificata a guardarti meravigliata per parecchi minuti. Lentamente si riprende dallo stupore e balbetta : `n`#'Sei sicuramente una creatura generata gli dei, visto che sei l'essere vivente più affascinante di tutti i mondi conosciuti. 
	    		Io mi inchino di fronte a sì cotanta bellezza, ".$session['user']['name']."`# e sono lieta di dirti che hai `&".$session['user']['charm']." `#punti fascino!'`3" );
	    	$testonegativo = "`3La `&Dama del Lago`3 si riscuote, ti si avvicina e urlandoti nelle orecchie: `#'Nessuno può pretendere di essere più affascinante di me e tantomeno solo pensare di avvicinarsi alla mia bellezza!'`3 e, con una risata quasi satanica, lancia su di te un incantesimo che ti toglie `&$testoguadagniperdi`3 punti fascino!	"; 
	     	$testopositivo = "`3La `&Dama del Lago`3 si riscuote, ti si avvicina e con voce suadente esclama: `#'Ecco, finalmente incontro un essere vivente degno di essere affascinante quanto me!'`3 e lancia su di te un incantesimo che ti aggiunge `&$testoguadagniperdi`3 punti fascino! ";
	        	
	    break;
    
    }
    emettisentenza($guadagniperdi,$testopositivo,$testonegativo);
    $session['user']['charm'] = $session['user']['charm'] + $guadagniperdi;
    debuglog("guadagna $guadagniperdi punti fascino al laghetto");
	output("`n`n`3Dopodichè scompare nuovamente nelle acque del laghetto che ritornano calme e chete come prima del tuo arrivo.`n`n");   
    $session['user']['specialinc']="";
    addnav("Torna nella Foresta","forest.php");
           
break;

case "lascia":
    output("`n`3Osservi la superficie del lago, e dopo qualche istante decidi che non hai tempo da perdere in queste cose da donnicciole.`n");
    output("`3 Sei un".($session['user']['sex']?"a donna":"")." guerriero e la bellezza è l'ultima delle qualità che permettono ad un".($session['user']['sex']?"a":"o")." combattente del tuo livello di sopravvivere.`n ");
    if ($session['user']['turns']>0) {
        output("Abbandoni amareggiat".($session['user']['sex']?"a":"o")." il laghetto, sapendo di aver perso `&1`3 turno di combattimento.");
        $session['user']['turns']-=1;
    }
    
    $session['user']['specialinc']="";
    page_header("Il laghetto"); 
    addnav("Torna nella Foresta","forest.php"); 
    
break;

}

page_footer();
?>

