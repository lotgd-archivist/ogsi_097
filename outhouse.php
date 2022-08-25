<?php
/**
* Version:  0.6
* Date:     July 31, 2003
* Author:   John J. Collins
* Email:    collinsj@yahoo.com
*
* Purpose:  Provide a fun module to Legend of the Green Dragon
* Program Flow: The player can choose to use the Private or Public Toilet. It costs Gold
* to use the Private Toilet. The Public Toilet is free. After using one of the toilet's,
* the play can was their hands or return. If they choose to wash their hands, there is a
* chance that they can get their gold back. If they don't choose to wash their hands, there
* is a chance that they will lose some gold. If they loose gold there is an entry added
* to the daily news.
*/
require_once("common.php");
require_once("common2.php");

output("`@`c`bI`2 `#Gabinetti Pubblici`2 di `@RafflinGate`b`c");

// How much does it cost to use the Private Toilet?
$cost = $session['user']['reincarna']*5 + 5;
// How much gold must user have in hand before they can lose money
$goldinhand = 1;
// How much gold to give back if the player is rewarded for washing their hands
$goldfound = 1500 - $session['user']['reincarna']*100 + 100;
// Gold found if you are luchy
$giveback = $session['user']['reincarna']*3 + 1;
// How much gold to take if the user is punished for not washing their hands
$takeback = 1;
// Minium random number for good habits
$goodminimum = 1;
// Maximum randdom number for good habits
$goodmaximum = 10;
// Odds of getting your money back
$goodmusthit = 5;
// Minimum random number for bad habits
$badminimum = 1;
// Maximum random number for bad habits
$badminimum = 4;
// Odds of losing money
$badmusthit = 2;
// Turn on to give the player a chance of finding a Gem if they visit the Private Toilet and Wash their hands.
// Turn on = 1
// Turn off = 0
$giveagem = 1;
// Give a gem if you visit the pay toilet and wash your hands. 1 in 4 chance of getting the gem.
// How often do you want to give out a Gem?
// Default is 1 out of 4 odds.
$givegempercent = 50;
$gemminimum = 1;
$gemmaximum = 100;
// Do you want to give the player a turn if they use the Pay Toilet and wash their hands.
// 1 give a turn
// 0 does not give an extra turn
$giveaturn = 0;
// Where do you want the player to go after leaving here?
// Usually this is the forest, you don't want no stinking toilet in the village do you, but can be anywhere.
$returnto = "forest.php";
// Does the player have enough gold to use the Private Toilet?


//You should really not have to edit anything below this line!
if ($session[user][gold] >= $cost) $canpay = True;

if ($_GET['op'] == "pay"){
    page_header("Gabinetto Privilegiato");
    $session['user']['locazione'] = 159;
    $session['user']['usedouthouse'] = 1;
    $session['user']['bladder']=0;
    output("`n`2Non appena entri nel `&Gabinetto Privilegiato`2 incontri lo `@Gnomo del Bagno`2 che ti mostra tutte le strutture a tua disposizione `n");
    output("`i`^'Questo è il bagno più pulito della regione!' `i`2esclama con una vocina stridula`n");
    output("`2Pur non essendo troppo convint".($session['user']['sex']?"a":"o")." delle parole dello gnometto accetti comunque di pagare `^$cost`2 pezzi d'`^Oro`2 per usufruire di tutti i servizi offerti dal `&Gabinetto Privilegiato`2 a pagamento.`n");
    output("E mentre ti appresti ad espletare i tuoi bisogni, vieni interrott".($session['user']['sex']?"a":"o")." dallo `@Gnomo della Carta Igienica`2 che ti invita a chiedere a lui qualsiasi cosa di cui tu abbia bisogno. `n");
    output("Detto ciò, molto educatamente ti volta le spalle lasciandoti nella tua intimità e prosegue nel suo lavoro di pulizia dei bagni.`n`n");
    $session['user']['gold'] -= $cost;
    debuglog("ha speso $cost oro per l'uso del bagno");
    switch(e_rand(1,20)){
            case 1: case 2: case 3: case 4: case 5: case 6: case 7: case 8: case 9: case 10:
            	break;
            case 11: 
        		output("`2Hai quasi concluso la delicata operazione fisiologica quando ti accorgi che vicino ai tuoi piedi c'è una piccola borsa di pelle rigonfia probabilmente persa da 
     					qualcuno che ha utilizzato i servizi igienici prima di te. `n ");
     			output("Con circospezione la apri scoprendo che contiene `^$goldfound`2 pezzi d'oro. `n");
     			output("Ti guardi intorno e vedi che lo `@Gnomo`2 è indaffarato nei suoi lavori e non si è accorto di quanto è accaduto.`n`n ");
     			output("Cosa decidi di fare ? `n");
     			output("Mettertela in tasca e fare finta di nulla o consegnarla allo `@Gnomo`2 affinchè la restituisca al legittimo proprietario che sicuramente egli sarà in grado di rintracciare?`n ");
    			$bagfound = True ;
                break;
            case 12: case 13: case 14: case 15: case 16: case 17: case 18: case 19: case 20:
             	break; 
	 }   
     if ($bagfound){
			addnav("Fai finta di nulla", "outhouse.php?op=bag");
	        addnav("Consegna la borsa", "outhouse.php?op=getbag");	    		   
     }else {
	    	addnav("Lavati le Mani", "outhouse.php?op=washpay");	
    		addnav("Esci", "outhouse.php?op=nowash"); 		
     }
}elseif ($_GET['op'] == "free"){
    page_header("Gabinetto Popolare");
    $session['user']['locazione'] = 160;
    $session['user']['usedouthouse'] = 1;
    $session['user']['bladder']=0;
    output("`n`2Hai deciso di utilizzare il più economico `(Gabinetto Popolare`2 e pertanto ti avvicini alla grande fossa biologica usata a tal scopo: l'odore è così forte che ti lacrimano gli occhi e ti senti soffocare!`n");
    output("Dopo averci soffiato il naso, lo `@Gnomo della Carta Igienica`2 ti offre un minuscolo quadratino di carta igienica da usare.`n");
    output("Hai quasi il voltastomaco dopo aver dato un'occhiata alle sue mani e soprattutto dopo aver visto la collezione di sporcizia ");
    output("che vi risiede sopra, tanto da pensare che forse sia meglio non utilizzare quel coriandolo di carta e rifiuti l'offerta.`n`n");
    switch(e_rand(1,20)){
            case 1: case 2: case 3: case 4: case 5: case 6: case 7: case 8: case 9: case 10: case 11: case 12:
        		output("Mentre stai " . ($session['user']['sex']?"accosciata sopra il":"in piedi davanti al") . " grosso buco ricolmo di liquami,
     					con lo `@Gnomo`2 che ti osserva attentamente, hai un mancamento e per poco non ci cadi dentro.`n");
    			output("Con uno sforzo ti riprendi e prosegui nella tua opera cercando di fare quello che devi fare nel minor tempo possibile, dato che non puoi trattenere il respiro in eterno.`n");
                break;
            case 13: case 14:
            	output("Mentre stai " . ($session['user']['sex']?"accosciata sopra il":"in piedi davanti al") . " grosso buco ricolmo di liquami,
     					con lo `@Gnomo`2 che ti osserva attentamente, hai un mancamento e per poco non ci cadi dentro ma ti schizzi gli stivali inzozzandoli.`n");
    			output("Con uno sforzo ti riprendi e prosegui nella tua opera cercando di fare quello che devi fare nel minor tempo possibile, dato che non puoi trattenere il respiro in eterno.`n");
                output("`5Hai perso `^2 Punti Fascino`5 dato l'incremento del tuo cattivo odore !!!`n`n");
                $session['user']['charm']-=2;
                $session['user']['clean']+=2;                
                debuglog("perde 5 fascino e guadagna 2 punti di sporcizia sporcandosi nei `#Gabinetti Pubblici`2");
    			break;
            case 15:
            	output("Mentre stai " . ($session['user']['sex']?"accosciata sopra il":"in piedi davanti al") . " grosso buco ricolmo di liquami,
     					con lo `@Gnomo`2 che ti osserva attentamente, hai un mancamento e svieni cadendo nella fossa inzozzandoti tutti i vestiti.`n");
    			output("Faticosamente riesci ad uscire da quella lordura ma Bleahhh quanto puzzi !! Forse è necessaria per te una bella doccia....`n");
    			output("`5Perdi `^5 Punti Fascino`5 e acquisisci il titolo di `%PigPen !!!`n`n");
       			$session['user']['charm']-=5;
       			$session['user']['clean'] = 20;
       			debuglog("perde 5 fascino e diventa PigPen cadendo nella fossa dei `#Gabinetti Pubblici`2");
       			addnews("".$session['user']['name']." `@guadagna il titolo di `%PigPen `@dopo aver sguazzato a lungo nella fossa biologica dei `#Gabinetti Pubblici`@ di Rafflingate.");
       			assegnapigpen($session['user']['name']);
       			$pigpen = True ;
                break;    
            case 16: 
            	output("Mentre stai " . ($session['user']['sex']?"accosciata sopra il":"in piedi davanti al") . " grosso buco ricolmo di liquami,
     					con lo `@Gnomo`2 che ti osserva attentamente, noti qualcosa luccicare in un angolo della fossa biologica.`n");
    			output("Potrebbe essere una `&gemma`2 persa da qualcuno che ha usufruito dei servizi igienici prima di te.`n Cosa decidi di fare ? Continuare coi tuoi bisogni o cercare di recuperarla?`n");
                $gemma = True ;
                break;
            case 17: case 18: case 19: case 20:
                output("Mentre stai " . ($session['user']['sex']?"accosciata sopra il":"in piedi davanti al") . " grosso buco ricolmo di liquami,
     					con lo `@Gnomo`2 che ti osserva attentamente, ti senti fortemente in imbarazzo e sei tentat" . ($session['user']['sex']?"a":"o") . " di andartene via trattenendo i tuoi bisogni corporali. `n");
    			output("Con uno sforzo di volontà prosegui nella tua opera cercando di fare quello che devi fare nel minor tempo possibile, dato che non puoi trattenere il respiro tanto a lungo, 
                        ma riprometti " . ($session['user']['sex']?"a te stessa":"a te stesso") . " che la prossima volta sarai meno " . ($session['user']['sex']?"avara":"avaro") . " e spenderai `^$cost `2 pezzi d'oro.");
                break;
            }
    if ($gemma){
			addnav("Recupera la gemma", "outhouse.php?op=gem");
	        addnav("Continua i tuoi bisogni", "outhouse.php?op=continue");	    		   
    }else {
    		if ($pigpen){
	    		addnav("Esci", "outhouse.php?op=leave");
    		}else{
	    		addnav("Lavati le Mani", "outhouse.php?op=washfree");	
    			addnav("Esci", "outhouse.php?op=nowash");
    		}
    }
}elseif ($_GET['op'] == "bag"){ 
		page_header("La borsa di monete d'oro"); 
		$session['user']['evil']+=5;
		debuglog("guadagna 5 punti cattiveria cercando di intascarsi la borsa di monete trovata ai `#Gabinetti Pubblici`2");
		output("`n`2Guardandoti attorno e cercando di far finta di nulla cerchi di far scivolare la borsa nelle tue tasche ....`n ");
	  	switch(e_rand(1,10)){
            case 1: case 2: case 3: case 4: 
        		output("`2Lo `@Gnomo della Carta Igienica`2 in realtà si era accorto delle tue losche manovre e aveva chiamato in loco lo Sceriffo e le sue Guardie.`n");
        		output("Una stretta ferrea quasi ti stritola il polso e quando ti volti vedi il volto ghignante dello Sceriffo !`n");
        		output("`3`iVero che avevi intenzione di consegnarmela per restituirla al legittimo proprietario ?`i`n`2 ti dice con un sorrisetto sardonico `n");
        		output("`3`iQuindi ho pensato di risparmiarti tutta la strada fino al mio ufficio `i`2 aggiunge sghignazzando. `n");
        		output("Detto ciò ti strappa di mano la borsa e se la mette in tasca mentre dà ordine ai suoi scagnozzi di scortarti fuori dai `#Gabinetti Pubblici`2");	
                break;
            case 5: case 6: case 7:
                output("`2Lo `@Gnomo della Carta Igienica`2 in realtà si è accorto delle tue losche manovre ed è riuscito a rintracciare il legittimo proprietario.");
                output("Una stretta ferrea quasi ti stritola il polso e quando ti volti vedi il volto sogghignante di un energumeno!`n");
        		output("`3`iVero che avevi intenzione di restituirla al legittimo proprietario ?`i`2 ti dice con un ghigno animalesco stampato sul volto. `n");
        		output("Detto ciò ti strappa di mano la borsa e se la mette in tasca mentre incomincia a pestarti con un nodoso randello aiutato dal piccolo ");
        		output("`@Gnomo`2 che approfitta dell'occasione per vendicarsi di alcune tue cattiverie nei suoi confronti.");
        		if ($session['user']['hitpoints']>($session['user']['maxhitpoints']*.1)) {
                    $session['user']['hitpoints']-=round($session['user']['maxhitpoints']*.1,0);
                }		
                break;
            case 8:
                output("`2L'operazione è perfettamente riuscita, nessuno si è accorto di nulla e tu ti ritrovi con `^$goldfound`2 pezzi d'oro in più in saccoccia ");
                output("anche se dovresti aver un minimo di rimorso per aver compiuto una azione disonesta e malvagia!");
                $session['user']['gold'] += $goldfound;
				debuglog("guadagna `^$goldfound`2 pezzi d'oro nella borsa di monete trovata ai `#Gabinetti Pubblici`2");	
                break;
            case 9: case 10:
                output("`2Purtroppo la borsa ti scivola e cade nella fossa biologica diventando così irrecuperabile. `nPeccato! Era una buona occasione per ");
                output("guadagnare un pò di soldini facilmente anche se in maniera disonesta ma come dice il proverbio: `#La farina del Diavolo finisce tutta in crusca!");
                break;    
        }
	  	addnav("Lavati le Mani", "outhouse.php?op=washpay");	
    	addnav("Esci", "outhouse.php?op=nowash");     
}elseif ($_GET['op'] == "getbag"){ 
		page_header("La borsa di monete d'oro");
		$goldpremium = $goldfound * 0.2 ;
		$session['user']['gold'] += $goldpremium;
		$session['user']['evil'] -= 5;
	    output("`n`2Hai deciso di chiamare lo `@Gnomo`2 e gli consegni la borsa che hai appena trovato. Egli ti sorride e ti ringrazia per l'onestà che hai appena dimostrato,");
	    output("ti consegna quindi `^$goldpremium`2 monete come ricompensa, il venti per cento della somma in oro contenuta nella borsa, e ti augura buona giornata anche a nome del legittimo proprietario.`n");
	    output("Felice per la buona azione appena compiuta sei contento con te stesso e ti senti meno malvagio.");
        debuglog("guadagna $goldpremium di ricompensa perde 5 punti cattiveria per aver riconsegnato la borsa ai `#Gabinetti Pubblici`2");
	    addnav("Lavati le Mani", "outhouse.php?op=washpay");	
    	addnav("Esci", "outhouse.php?op=nowash");    		
}elseif ($_GET['op'] == "continue"){ 
		page_header("Un luccichio nella fossa");
		output("`n`2Il puzzo è talmente terribile che preferisci continuare coi tuoi bisogni cercando di concludere il prima possibile con questa tortura.`n ");
	  	output("`2Senza poi considerare che non avresti mai il coraggio di mettere le mani in quella maleodorante fanghiglia che riempie quel putrido buco.");
	  	addnav("Lavati le Mani", "outhouse.php?op=washfree");	
    	addnav("Esci", "outhouse.php?op=nowash");
}elseif ($_GET['op'] == "gem"){
	    page_header("Un luccichio nella fossa");
		output("`n`2Ti chini per vedere meglio e hai la conferma di ciò che hai visto in precedenza. Sembra essere una splendida `&gemma`2 ! `n");
		output("Cerchi quindi di prenderla ma non ci arrivi, ti sporgi pericolosamente oltre il bordo della fossa e ti allunghi sempre più nel tentativo di afferrarla.`n");
    			
		switch(e_rand(1,10)){
            case 1: case 2: case 3: case 4: 
        		output("Con uno sforzo ulteriore riesci a toccarla, ma questa affonda nella melma e ti ritrovi a dover rimestolare nel liquame sino a quando riesci nel tuo intento.`n");
                output("E' proprio una `&gemma`2, ti sei sporcato le braccia fino ai gomiti e puzzi come un caprone: ma lo sforzo ne è valso la pena!`n"); 
    			$session['user']['clean']+=2;
    			$session['user']['gems']+=1;
    			debuglog("trova 1 gemma e guadagna 2 punti di sporcizia per prenderla nei `#Gabinetti Pubblici`2");
    			break;
            case 5:
            	output("Con uno sforzo ulteriore riesci a toccarla, ma questa affonda nella melma e ti ritrovi a dover rimestolare nel liquame sino a quando riesci nel tuo intento.`n");
                output("Purtroppo è solo un pezzo di vetro, ti sei sporcato inutilmente le braccia fino ai gomiti e puzzi come un caprone: a volte l'avidità non paga!`n"); 
    			$session['user']['clean']+=2;              
                debuglog("guadagna 2 punti di sporcizia prendendo un pezzo di vetro nei `#Gabinetti Pubblici`2");
    			break;
            case 6:
            	output("Con uno sforzo ulteriore riesci a toccarla, ma questa affonda nella melma e ti ritrovi a dover rimestolare nel liquame sino a quando riesci nel tuo intento.`n");
                output("Ma così facendo perdi l'equilibrio e Splash  precipiti a capofitto nella melma inzozzandoti completamente.`n");
               	output("Faticosamente esci da quella lordura ma Bleahhh quanto puzzi !! Forse è necessaria per te una bella doccia....`n");
    			output("`5Hai sì guadagnato una `&gemma`2 ma perdi `^5 Punti Fascino`5 e acquisisci il titolo di `%PigPen !!!`n`n");
       			$session['user']['charm']-=5;
       			$session['user']['clean'] = 20;
       			debuglog("perde 5 fascino e diventa PigPen prendendo la gemma ma cadendo nella fossa dei `#Gabinetti Pubblici`2");
       			addnews("".$session['user']['name']." `@guadagna il titolo di `%PigPen `@dopo aver raccolto una `&gemma`2 dalla melma nella fossa biologica dei `#Gabinetti Pubblici`@ di Rafflingate.");
       			assegnapigpen($session['user']['name']);
       			$pigpen = True ;
                break;    
            case 7: case 8: case 9: case 10:
            	output("Con uno sforzo ulteriore riesci a toccarla, ma questa affonda nella melma e ti ritrovi a dover rimestolare nel liquame cercando di raggiungere il tuo obiettivo.`n");
                output("Ma mentre stai per afferrarla lo `@Gnomo`2 che ti osservava attentamente, ti afferra e ti allontana dicendo che qualsiasi cosa si trova della fossa appartiene di diritto ai ");
    			output("`#Gabinetti Pubblici`2 di Rafflingate e che quindi non hai alcun permesso di impadronirtene.`n");
                output("Decidi quindi di andartene anche se ti sporcato inutilmente le braccia fino ai gomiti e puzzi come un caprone, a volte l'avidità non paga!`n"); 
    			$session['user']['clean']+=2;              
                debuglog("guadagna 2 punti di sporcizia cacciato dallo Gnomo nei `#Gabinetti Pubblici`2");
                break;
            }
		if ($pigpen){
	    		addnav("Esci", "outhouse.php?op=leave");
    		}else{
	    		addnav("Lavati le Mani", "outhouse.php?op=washfree");	
    			addnav("Esci", "outhouse.php?op=nowash");
    	}
}elseif ($_GET['op'] == "leave"){
		page_header("PigPen");
		output("`n`2Ricoperto da capo a piedi di zozzure e puzzolente come un porcello esci dai `(Gabinetti Popolari`2 memore di questa poco lieta avventura.`0`n");
		forest(true);	     	
}elseif ($_GET['op'] == "washpay"|| $_GET['op'] == "washfree"){
    page_header("Lavatoio");
    $session['user']['clean']-=1;

	$identarmatura=array();
	$ident_armatura = identifica_armatura();
	$articoloarmatura = $ident_armatura['articolo'];

    output("`n`2Lavarsi le mani è sempre un bene ed è soprattutto un buon indice di igiene e pulizia.`n Dopodichè ti sistemi, aggiusti $articoloarmatura `#{$session['user']['armor']}`2 guardando il riflesso della tua immagine nell'acqua e ti incammini.`n");
    $goodhabits = e_rand($goodminimum, $goodmaximum);
    if ($goodhabits > $goodmusthit && $_GET['op']=="washpay"){
        output("`2La `^Fatina del Lavatoio`2 ti benedice e decide di premiarti restituendoti `^$giveback `2pezzi d'oro per essere stat".($session[user][sex]?"a":"o")." igienista e pulit".($session[user][sex]?"a":"o")."!`n");
        $session['user']['gold'] += $giveback;
        debuglog("riceve dalla Fatina del Lavatoio $giveback oro per aver lavato le mani");
        if ($session['user']['drunkenness']>0){
                $session['user']['drunkenness'] *= .9;
                output("`2Lasciando i bagni, ti senti un po' più sobrio. ");
        }
        if ($giveaturn == 1){
                $session['user']['turns']++;
                output("`2Hai guadagnato un turno!`0`n");
        }
        if ($giveagem == 1){
            $givegemtemp = e_rand($gemminimum, $gemmaximum);
            if ($givegemtemp <= $givegempercent){
                $session['user']['gems']++;
                debuglog("trova 1 gemma uscendo dai `#Gabinetti Pubblici`2");
                output("`2E sei ulteriormente fortunat".($session[user][sex]?"a":"o").", mentre stai per andartene, trovi per terra `&una gemma`2 probabilmente persa da qualcuno.`n");
            }   
        }
    }elseif ($goodhabits > $goodmusthit && $_GET['op'] == "washfree"){
        if (e_rand(1, 3)==1) {
            output("`2La `^Fatina del Lavatoio`2 ti benedice e decide di premiarti regalandoti `^$giveback `2pezzi d'oro per essere stato igienista e pulito!`n");
        	$session['user']['gold'] += $giveback;
        	debuglog("riceve dalla Fatina del Lavatoio $giveback oro per aver lavato le mani");
        }
    }
    forest(true);
}elseif (($_GET['op'] == "nowash")){
	
   	    page_header("Mani Puzzolenti");
   	    $session['user']['clean']+=1;
   	    $session['user']['charm']-=2;
	    output("`n`2Hai deciso di andartene dai `#Gabinetti Pubblici`2 senza lavare le tue mani sporche e puzzolenti!`n");
	    output("Non mi sembra sia questo quello che ti hanno insegnato i tuoi genitori! Non ti vergogni?`n");
	    output("`2La `^Fatina del Lavatoio`2 decide di impartirti una piccola lezione, invoca cosi' un potente incantesimo che ti trasforma temporaneamente in uno strillante `Vmaialino`2 e ti toglie `^2 Punti Fascino`2 !`n");
	    $session['bufflist'][278] = array("name"=>"`VStrilli come un suino",
        	                                    "rounds"=>10,
            	                                "wearoff"=>"Hai finalmente ripreso il tuo aspetto naturale recuperando tutta la tua energia in attacco.",
                	                            "atkmod"=>0.8,
                    	                        "roundmsg"=>"Il tuo ridicolo aspetto da maialino riduce la potenza dei tuoi attacchi!",
                        	                    "activate"=>"offense");
        debuglog("perde 2 punti fascino e viene trasformato in un maialino per non essersi lavato le mani ai `#Gabinetti Pubblici`2");
	    addnews("`2Sempre fine, " . ($session['user']['name']) . " `2gira per Rafflingate grufolando con una lunga striscia di carta igienica attaccata al suo piede.");
    	forest(true);
}else{
    page_header("I Bagni Pubblici");
    output("`n`2Oltrepassata la cerchia dei bastioni di `@`bRafflinGate`b`2, nei pressi del limitare della foresta, sono ubicati i `#Gabinetti Pubblici`2, servizi igienici a disposizione di tutti gli abitanti del regno e caratterizzati da un aroma ");
    output("non propriamente idilliaco che però ha il grande vantaggio di mantenere ad una notevole distanza dalle mura cittadine tutte le creature ostili.`n");
    if ($session['user']['usedouthouse'] == 0){
        output("In tipico stile di casta, i `#Gabinetti Pubblici`2 di `@`bRafflinGate`b`2 sono di due tipologie: quello `&Privilegiato`2 a pagamento, dotato di una copertura ");
        output("per le intemperie e attrezzato con i migliori servizi e le comodità ed uno `(Popolare`2 gratuito, completamente all'aperto, molto più spartano e funzionale. `n");
        output("La scelta su quale dei due utilizzare è tua, hai infatti la facoltà di decidere in completa autonomia dove espletare i tuoi bisogni fisiologici in base alla tua disponibilità economica.`0`n`n");
        addnav("Gabinetti");
        if ($canpay){
            addnav("`&Gabinetto Privilegiato`3 : (costo `^ $cost`3 pezzi d'oro)", "outhouse.php?op=pay");
        }else{
            output("`2Usare il `&Gabinetto Privilegiato`2 costa `^$cost `2pezzi d'oro. Dal momento che non sembri essere in grado di pagare, sarai costrett".($session['user']['sex']?"a":"o")." ");
            output("a trattenere i tuoi bisogni o espletarli all'aperto usufruendo del `(Gabinetto Popolare`2!");
        }
        addnav("`(Gabinetto  Popolare `3: (gratuito)", "outhouse.php?op=free");
        addnav("Trattieni", "forest.php");
    }else{
        switch(e_rand(1,5)){
            case 1:
        		output("`2Un cartello ti informa che i `#Gabinetti Pubblici`2 sono chiusi per alcuni lavori di riparazione. Purtroppo dovrai trattenere i tuoi bisogni fino a domani!");
                break;
            case 2:
                output("`2Avvicinandoti ai `#Gabinetti Pubblici`2, vieni pres".($session['user']['sex']?"a":"o")." da un senso di nausea, decidi quindi di soprassedere dato che oggi non riusciresti a sopportarne l'odore un'altra volta.");
                break;
            case 3:
                output("`2Ma cosa ci fai nei pressi dei `#Gabinetti Pubblici`2? Ora non hai davvero alcuno stimolo!");
                break;
            case 4:
                output("`2I `#Gabinetti Pubblici`2 non sono accessibili al momento perchè lo `@Gnomo del Bagno`2 li sta disinfettando. Purtroppo dovrai trattenere i tuoi bisogni.");
                break;
            case 5:
                output("`2Un massiccio attacco di dissenteria che ha colpito la cittadinanza ha reso i `#Gabinetti Pubblici`2 poco salubri e il Sindaco con un'ordinanza è stato costretto a farli chiudere temporaneamente per una adeguata disinfezione. `nDovrai trattenerti fino alla loro riapertura.");
                break;        
            }
        output("`n`n`7Torni alla foresta.`0");
        forest(true);
    }
}
page_footer();

?>