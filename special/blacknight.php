<?php

/********************
Black Knight
Written by Robert for Maddnets LoGD
Tradotto da Excalibur per www.ogsi.it
Modificato da Hugues per www.ogsi.it
Il Cavaliere Nero 
Affrontare il Cavaliere Nero costa 1 gemma 
Si possono guadagnare esperienza gemme oro e 1 punto in attacco 
I reincarnati possono anche perdere 1 punto in attacco
Il possesso della Pietra di Poker penalizza
Avere l'armatura inferiore al proprio livello penalizza
*********************/
require_once "common2.php";
page_header("Il Cavaliere Nero");
output("`n`c`b`xIl Cavaliere Nero`0`b`c`n",true);

if (!isset($session)) exit();
if ($_GET['op']==""){
    output("`n`3Sbuchi in un’ampia radura erbosa, al centro della quale vedi un imponente cavaliere rivestito da una nera corazza, in sella ad un enorme destriero dal serico manto corvino.");
    output("`3Un gigantesco `2Troll delle Montagne`3 sosta al suo fianco, è il suo scudiero, regge una lunga lancia e lo scudo con le insegne della sua casata.`n");
    output("`3Riconosci quindi in quell'immensa scura figura il leggendario `xCavaliere Nero`3 le cui mirabolanti gesta sono raccontate dai bardi e dai cantastorie accanto al fuoco degli accampamenti notturni."); 
    output("`3Si narra infatti che egli sia un abilissimo Maestro di Spada, dotato di riflessi fulminei e di una forza quasi sovrumana, ma soprattutto imbattibile con qualsiasi arma "); 
    output("`3e considerato da tutti il miglior guerriero mai esistito in tutti i mondi conosciuti.`n"); 
    output("`3Mentre sosti sul bordo della radura guardando con timore reverenziale il nobile personaggio, il `2Troll`3 ti si avvicina e grugnisce che il `xCavaliere Nero`3 sta cercando un nuovo allievo, qualcuno da istruire nella nobile arte della guerra.`n"); 
    output("`3Sei stato in tal modo formalmente invitato ad allenarti con il famoso `xCavaliere Nero`3, farlo però ti verrà a costare una `&gemma`3 in quanto il Maestro non dispensa così facilmente la propria arte.");
    output("`3Ti viene anche fatto capire, che l'allenamento potrebbe anche comportare qualche rischio per la tua integrità fisica, in quanto l'eccezionale guerriero fatica a frenare il suo braccio ed è capitato che qualche suo allievo ");
    output("`3riportasse qualche danno durante le lezioni. `n`n");
    output("`3Cosa scegli di fare ?");
    addnav("`3Accetti l'offerta","forest.php?op=give");
    addnav("`\$Rifiuti, non fà per te","forest.php?op=dont");
    $session['user']['specialinc']="blacknight.php";
}else if ($_GET['op']=="fuggi"){
	$session['user']['specialinc']="";
    page_header("L'attacco del Cavaliere");
    $ident_armatura=array();
	$ident_armatura = identifica_armatura();
	$articoloarmatura = $ident_armatura['articolo'];
	if ($articoloarmatura == "il tuo") { 
			$testo = "viene trapassato" ;
	}else if ($articoloarmatura == "la tua") { 
			$testo = "viene trapassata" ;
	}else if ($articoloarmatura == "i tuoi") { 
			$testo = "vengono trapassati" ;
	}else if ($articoloarmatura == "le tue") { 
			$testo = "vengono trapassate" ;
	}		
    $session['user']['turns']-=1;
    $session['user']['gems']-=1;
    $session['user']['clean'] += 1;
    switch(e_rand(1,4)){
        case 1:
            output("`n`3Incominci a correre disperat".($session['user']['sex']?"a":"o")." come una lepre braccata dai cani, mentre il `xCavaliere Nero`3 che inizialmente aveva provato a raggiungerti,"); 
            output("`3rinuncia al tentativo di acciuffarti e incomincia a sbellicarsi dalle risate accompagnato dagli sghignazzi ancor più fragorosi del suo fido scudiero.`n");  
            output("`3Terrorizzat".($session['user']['sex']?"a":"o")." continui a correre a perdifiato, chiedendoti perchè mai hai voluto diventare un".($session['user']['sex']?"a":"")." guerrier".($session['user']['sex']?"a":"o")." quando la vita tranquilla di un".($session['user']['sex']?"a":"o")." scrivan".($session['user']['sex']?"a":"o")." riserva meno pericoli....`n");    
            debuglog("paga 1 gemma ma fugge al BlackNight ");
        	break;
        case 2:
            output("`n`3Incominci a correre disperat".($session['user']['sex']?"a":"o")." come una lepre braccata dai cani, mentre il `xCavaliere Nero`3, prontamente risalito a cavallo si getta al tuo inseguimento in una improvvisata caccia alla volpe. ..... ehm alla lepre...`n"); 
            output("`3Per tua fortuna sei molto più veloce di lui e riesci a raggiungere il margine della radura nascondendoti tremante tra i fitti cespugli.`n");  
            output("`3Alla fine il grande Maestro di Spada decide che non è il caso di perdere ulteriore tempo per cercarti, e si allontana seguito dal suo fedelissimo scudiero.`n");    
            output("`3Manterrai comunque vivo per un pò di tempo il ricordo del pericolo scampato tremando per la paura!`n`n");
            $session['bufflist'][281] = array("name"=>"`5Terrore del `xCavaliere Nero`3",
                                        "rounds"=>20,
                                        "wearoff"=>"hai superato la paura del `xCavaliere Nero`0 e la forza del tuo attacco torna alla normalità",
                                        "atkmod"=>1.2,
                                        "roundmsg"=>"il terrore di aver incontrato il `xCavaliere Nero`0 ti paralizza e riduce la tua forza in attacco",
                                        "activate"=>"offense");
            debuglog("paga 1 gemma ma fugge al BlackNight ");
            break;
		case 3: 
       		output("`n`3Stai per incominciare a metterti a correre, ma il `2Troll`3 velocissimo ti sferra un potente pugno che ti fa cadere al suolo tramortit".($session['user']['sex']?"a":"o").". `n`n");
    		output("`3Ti risvegli con la faccia dolorante e scopri di aver dormito per `&2`3 turni!`n"); 
    		$session['user']['turns']-=2;
     		debuglog("paga 1 gemma ma fugge al BlackNight e perde 2 combattimenti");
     		break;
		case 4:
            output("`n`3Incominci a correre disperat".($session['user']['sex']?"a":"o")." come una lepre braccata dai cani, mentre il `xCavaliere Nero`3, prontamente risalito a cavallo si getta al tuo inseguimento."); 
            output("`3Ma prima che tu riesca a raggiungere il margine della radura, per cercare di nasconderti tra i cespugli, egli ti raggiunge e ti colpisce con un potente fendente : ");
            output("`3$articoloarmatura`# ".$session['user']['armor']."`3 $testo con estrema facilità dalla lama affilatissima che penetra in profondità uccidendoti all'istante.`n");
            output("`n`4Sei Mort".($session['user']['sex']?"a":"o")."`3!");
            output("Perdi il `&10%`3 della tua esperienza e tutto il tuo oro. Potrai continuare a combattere domani.");
            $session['user']['alive']=false;
            $session['user']['hitpoints']=0;
            $session['user']['experience']*=0.9;
            $session['user']['gold'] = 0;
            addnav("`^Notizie Giornaliere","news.php");
            debuglog("paga 1 gemma al BlackNight fugge e muore. Perde 10% exp e oro");
            addnews($session['user']['name']." `3è stat".($session['user']['sex']?"a":"o")." uccis".($session['user']['sex']?"a":"o")." mentre fuggiva dal `xCavaliere Nero`3.");
            break;
	}    
	if ($session['user']['turns'] < 0) $session['user']['turns'] = 0;
	if ($session['user']['alive']) {
		$session['user']['specialinc']="";
	   	addnav("`2Ritorna alla Foresta","forest.php"); 
    }
}else if ($_GET['op']=="give"){
	page_header("Il Cavaliere Nero");
  	if ($session['user']['gems']>0){
        output("`n`3Mentre il `xCavaliere Nero`3 avanza lentamente verso di te, tutta la tua baldanza viene meno e la tua sicurezza di abile spadaccin".($session['user']['sex']?"a":"o")." si scioglie come neve al sole.");
        output("`3Ti ritrovi a guardare, come ipnotizzat".($session['user']['sex']?"a":"o").", il gesto aggraziato e terribile con cui estrae la sua spada dal fodero.");  
        output("`3Fai un profondo respiro, cerchi di arrestare il tremito che ti assale mentre non sei più così sicur".($session['user']['sex']?"a":"o")." di aver fatto la scelta giusta.`n`n");
        output("`3Che fare ?  Tentare la fuga o fronteggiare impavido l'assalto imminente ?");
        addnav("Fronteggi l'assalto","forest.php?op=combatti");
        addnav("s?`#Cerchi di scappare","forest.php?op=fuggi");
        $session['user']['specialinc']="blacknight.php";
    }else{
        output("`n`3Frughi in tutte le tue tasche ma non riesci a scovare nemmeno una `&gemma`3.`n");
        output("`3Davanti al tuo naso il `2Troll`3 allunga impaziente la mano per ricevere il pagamento pattuito.`n");
        output("`3Indietreggi lentamente sperando di riuscire a fuggire senza dover confessare il tuo errore ma il `xCavaliere Nero`3 ti sbarra la strada : `n`n");
        output("`6Allora ".$session['user']['name']."`6? Ti stai forse prendendo gioco di me?");
        output("`6Speravi di potermi trarre in inganno confessando dopo la lezione che non mi avresti pagato?`n`n");
        output("`3Cadi in ginocchio balbettando miseramente qualche scusa ma il `2Troll`3 ti sferra un poderoso manrovescio che ti fa perdere i sensi. `n`n");
        output("`3Ti risvegli tutt".($session['user']['sex']?"a":"o")." dolorante e scopri di aver perso `&2`3 turni!`n");
        output("`3Ti alzi sconsolat".($session['user']['sex']?"a":"o")." pensando a quale meravigliosa occasione hai sprecato per colpa di una stupida `&gemma`3!");
        debuglog("perde 2 combattimenti dal Blacknight");
        $session['user']['clean']+=1;
        $session['user']['turns']-=2;
        if ($session['user']['turns'] < 0) $session['user']['turns'] = 0;
        $session['user']['specialinc']="";
	    addnav("`2Ritorna alla Foresta","forest.php");
    }    
}else if ($_GET['op']=="combatti"){
		page_header("L'attacco del Cavaliere Nero"); 
		$ident_arma=array();
		$ident_arma = identifica_arma();
		$articoloarma = $ident_arma['articolo'];
		$pugni = $ident_arma['pugni'];
		$ident_armatura=array();
		$ident_armatura = identifica_armatura();
		$articoloarmatura = $ident_armatura['articolo'];
		if ($articoloarmatura == "il tuo") { 
			$testo = "viene trapassato" ;
			$testo1 = "sarebbe stato trapassato" ;
		}else if ($articoloarmatura == "la tua") { 
				$testo = "viene trapassata" ;
				$testo1 = "sarebbe stata trapassata" ;
		}else if ($articoloarmatura == "i tuoi") { 
				$testo = "vengono trapassati" ;
				$testo1 = "sarebbero stati trapassati" ;
		}else if ($articoloarmatura == "le tue") { 
				$testo = "vengono trapassate" ;
				$testo1 = "sarebbero state trapassate" ;
		}
		
		if ($pugni) {	
			output("`3Il `xCavaliere Nero`3, notando che non disponi di armi tranne `3$articoloarma`# ".$session['user']['weapon']."`3, ordina al suo scudiero di fornirti di una delle sue lunghe spade confidando sulla tua capacità di brandeggiare adeguatamente armi pesanti come quelle che lui usa abitualmente senza sforzo.");                 	
			output("`3Barcollando sulle gambe e con le braccia tremolanti dallo sforzo di reggere la pesantissima spada cerchi di metterti in guardia per affrontare degnamente il tuo avversario.`n");
        }
        output("`n`3Con una mossa fulminea il `xCavaliere Nero`3 si avventa su di te con un gran fendente ..... ");
        $session['user']['gems']-=1;
        $session['user']['turns']-=1;
        $session['user']['clean']+=1;
        
        $pietradipoker = verificapietrapoker();
 		$caso = e_rand(5,10);   
 		if ($pietradipoker) {
           	$caso = $caso + 5 ; //la Pietra di Poker influisce negativamente, vengono quindi selezionati casi ad hoc meno favorevoli 
        }else{
        	if ($session['user']['armordef'] < $session['user']['level']) {
				$caso = $caso - 5 ; //avere un'armatura inferiore al livello di gioco influisce negativamente, vengono quindi selezionati casi ad hoc meno favorevoli
			}	 
 		}		 
 		
        switch($caso){
        	
        	case 0:
                output("`3e prima che tu possa alzare la guardia il micidiale attacco del Maestro di Spada giunge a bersaglio : "); 
                output("`3$articoloarmatura`# ".$session['user']['armor']."`3 $testo1 offre pochissima resistenza alla lama affilatissima del `xCavaliere Nero`3 che ti ferisce gravemente.`n");
                output("`3Sanguinante crolli al suolo mentre il `2Troll`3 ti si avvicina e ti soccorre, applicando strani intrugli sulla ferita e fasciandoti il meglio possibile.`n");    
                output("`3Trascorrono `&3`3 turni `3prima che tu sia di nuovo in grado di reggerti in piedi, ma hai perso la metà dei tuoi punti vita.`n"); 
                $session['user']['turns'] -= 3;
                $session['user']['hitpoints']*=0.5;
                debuglog("paga 1 gemma al BlackNight e perde 3 turni e la metà degli hp");
            break;
            case 1:
                output("`3e prima che tu possa alzare la guardia e difenderti, ti procura una `4profonda ferita `3ad un braccio.`n"); 
                output("`3Il Grande Maestro di Spada ti scocca un’occhiata piena di compassione e si allontana borbottando che non ha tempo da perdere con gli incapaci.`n");  
                output("`3Il `2Troll`3 suo scudiero ti si avvicina e ti soccorre, applicando strani intrugli sulla ferita per stagnarti il sangue e poi ti fascia l'arto ferito con una benda.`n");    
                output("`3Trascorrono `&2`3 turni `3prima che tu sia di nuovo in grado di combattere.`n");  
                $session['user']['turns'] -= 2;
                debuglog("paga 1 gemma al BlackNight e perde 2 combattimenti");
            break;
            case 2:
                output("`3ma i tuoi riflessi non ti tradiscono e riesci a parare agilmente la stoccata."); 
                output("`3Il temibile avversario prova più volte ad affondare i colpi ma la tua abilità ti consente di resistere ad ogni suo assalto.`n"); 
                output("`3Accertate le tue capacità di Maestro di spada il `xCavaliere Nero`3 ripone la sua arma e si complimenta con te :`n`n"); 
                output("`3".$session['user']['name']."`6, le tue abilità difensive sono veramente superbe."); 
                output("`6Ritengo di non avere nulla da insegnarti e, dal momento che mi sono molto divertito a duellare con un mio pari, ti faccio dono di queste `&2 gemme.`6 `n`n");
                output("`3Detto questo ti sorride e si allontana con il suo fido scudiero.`n");
                $session['user']['gems']+=2;
                debuglog("paga 1 gemma al BlackNight e ne guadagna 2");
                break;
            case 3:
                output("`3ma i tuoi riflessi non ti tradiscono e riesci a parare agilmente la stoccata rispondendo poi con rapido un affondo che colpisce il tuo avversario ad una coscia.`n"); 
                output("Il `xCavaliere Nero`3pone fine al duello dichiarando : `n`n");
                output("".$session['user']['name']."`6, sono ammirato dalla tua maestria. Non credo di avere nulla da insegnare ad un".($session['user']['sex']?"a":"")." guerrier".($session['user']['sex']?"a":"o")." del tuo calibro!");  
                output("`6Prendi queste `&3 gemme `6come segno tangibile del mio rispetto.`n`n");
                output("`3Detto questo si inchina e si allontana con il suo fido scudiero lasciandoti traboccante di orgoglio.`n");
                $session['user']['gems']+=3;
                debuglog("paga 1 gemma al BlackNight e ne guadagna 3");
            break;
            case 4:
                output("`3ma prima che tu possa alzare la guardia la sua botta poderosa giunge a segno : ");
                output("`3$articoloarmatura`# ".$session['user']['armor']."`3 $testo con estrema facilità dalla lama affilatissima che penetra in profondità uccidendoti all'istante.`n");
                output("`n`4Sei Mort".($session['user']['sex']?"a":"o")."`3!");
                output("Perdi il `&10%`3 della tua esperienza e tutto il tuo `^oro`3. Potrai continuare a combattere domani.");
                $session['user']['alive']=false;
                $session['user']['hitpoints']=0;
                $session['user']['experience']*=0.9;
                $session['user']['gold'] = 0;
                addnav("`^Notizie Giornaliere","news.php");
                debuglog("paga 1 gemma al BlackNight e muore. Perde 10% exp e oro");
                addnews($session['user']['name']." `3è stat".($session['user']['sex']?"a":"o")." uccis".($session['user']['sex']?"a":"o")." dal `xCavaliere Nero`3.");
            break;
            case 5:
                output("`3ma i tuoi riflessi non ti tradiscono e riesci a parare agilmente la stoccata rispondendo successivamente con una serie di affondi che colpiscono il tuo avversario prima ad una coscia e poi alla spalla sinistra.`n"); 
                output("Il `xCavaliere Nero`3pone fine al duello dichiarando : ");
                output("`i".$session['user']['name']."`6, era tanto che non affrontavo un avversario del tuo calibro. Un giorno la tua fama di guerriero sovrasterà la mia. Accetta questo dono in ricordo del nostro incontro.`i`n");  
                output("`3Detto questo fa cenno al suo fido scudiero che ti consegna un’ampolla contenente una pozione.");
                output("`3La bevi e … meraviglia! I tuoi HitPoints sono permanentemente aumentati di `&5`3 ! ");
                $session['user']['maxhitpoints']+=5;
                $session['user']['hitpoints']+=5;
                debuglog("paga 1 gemma al BlackNight e guadagna 5 HP");
            break;
            case 6:
                output("`3ma i tuoi riflessi non ti tradiscono e riesci a parare la stoccata. Incomincia così un susseguirsi di affondi, parate, finte, schivate, stoccate e giravolte. "); 
                output("`3Come due ballerini girate l'uno intorno all'altro in complicate evoluzioni alla ricerca del giusto pertugio nella guardia dell'avversario. ");
                output("`3Senza che ve ne rendiate conto il tempo vola, trascorri allenandoti un paio d'ore con il `xCavaliere Nero`3 apprendendo numerose tecniche nuove fino ad ora a te sconosciute.`n");
                output("`n`3Guadagni il `&20%`3 di esperienza e guadagni `&1`3 Punto in Attacco permanente!`n");
                $session['user']['experience']*=1.2;
                $session['user']['clean']+=1;
                debuglog("paga 1 gemma al BlackNight e guadagna 20% exp e 1 punto attacco permanente");
                $session['user']['attack']++ ;
                $session['user']['bonusattack']++ ;
            break;
            case 7:
                $gold = e_rand($session['user']['level']*200,$session['user']['level']*400);
                output("`3ma i tuoi riflessi non ti tradiscono e riesci a parare, sia pure con una certa difficoltà, la stoccata."); 
                output("Il tuo eccezionale avversario prova più volte ad affondare i suoi terribili colpi ma riesci sempre a respingere tutti i suoi assalti.`n");
                output("`3Accertate le tue capacità di spadaccin".($session['user']['sex']?"a":"o")." il `xCavaliere Nero`3 ripone la sua spada e sorridendoti esclama : ");
				output("`6`i".$session['user']['name']."`6, devo constatare che possiedi una notevole abilità difensiva."); 
                output("`6Posso fare ben poco per migliorarla, mi sono comunque divertito molto a duellare con te e per ringraziarti ti faccio dono di questi `^$gold `6pezzi d'oro!`i`n`n");  
                output("`3Dopo averti dato una pacca amichevole sulla spalla si allontana seguito dal suo fido scudiero.`n");
                $session['user']['gold']+=$gold;
                debuglog("paga 1 gemma al BlackNight e guadagna $gold oro");
            break;
            case 8:
                output("`3e prima che tu possa alzare la guardia una botta poderosa giunge a colpirti : "); 
                output("`3$articoloarmatura`# ".$session['user']['armor']."`3 $testo1 con estrema facilità dalla lama affilatissima se questa non fosse stata fortunosamente fermata dalla fibbia metallica del tuo tascapane.`n");
                output("`3Tremante crolli al suolo dallo spavento per lo scampato pericolo suscitando l’ilarità del `xCavaliere Nero`3 che ti schernisce dicendo :"); 
                output("`6`iCosa ci fa in giro per la foresta un coniglio travestito da guerriero?"); 
                output("`6Per fortuna che la mamma ti aveva dato la merenda stamattina, altrimenti a quest’ora saresti morto! Non ho tempo da perdere con le nullità !`i`n");  
                output("`3Con sdegno risale quindi sul suo destriero e si allontana seguito dal suo scudiero che se la ride allegramente al tuo indirizzo.`n`n");
                output("`3Questo episodio vergognoso ha fatto crollare la tua autostima. Perdi il `&10%`3 di esperienza! ");
                $session['user']['experience']*=0.9;
                if ($session['user']['attack'] >= 9 and $session['user']['reincarna'] >=1 ){
                	output("`3Perdi inoltre `&1`3 Punto in Attacco permanente`n");
                	debuglog("paga 1 gemma al BlackNight e perde 10% exp e 1 punto attacco");
                	$session['user']['attack']--;
                	if ($session['user']['bonusattack']>0) $session['user']['bonusattack']-- ;
                }
            break;
            case 9:
                output("`3ma i tuoi riflessi non ti tradiscono e riesci a parare la stoccata. Incomincia così un susseguirsi di affondi, parate, finte, schivate, stoccate e giravolte. "); 
                output("`3Come due ballerini girate l'uno intorno all'altro in complicate evoluzioni alla ricerca del giusto pertugio nella guardia dell'avversario. ");
                output("`3Senza che ve ne rendiate conto il tempo vola, trascorri allenandoti un paio d'ore con il `xCavaliere Nero`3 apprendendo alcune tecniche nuove fino ad ora a te sconosciute.`n`n");
                output("`3Guadagni il `&10%`3 di esperienza!`n");
                $session['user']['experience']*=1.1;
                debuglog("paga 1 gemma al BlackNight e guadagna 10% exp");
            break;
            case 10:
                output("`3ma i tuoi riflessi non ti tradiscono e riesci a parare la stoccata. Incomincia così un susseguirsi di affondi, parate, finte, schivate, stoccate e giravolte. "); 
                output("`3Senza che ve ne rendiate conto il tempo vola, trascorri allenandoti parecchie ore con il `xCavaliere Nero`3 apprendendo numerose tecniche nuove fino ad ora a te sconosciute.`n");
                output("`3Manterrai comunque vivo per un pò di tempo il ricordo degli insegnamenti del grande Maestro di Spada con il quale hai appena avuto l'onore di allenarti!`n`n");
                output("`3Guadagni il `&10%`3 di esperienza!`n");
                $session['user']['experience']*=1.1;
                $session['user']['clean']+=1;
                $session['bufflist'][280] = array("name"=>"`5Colpo speciale del `xCavaliere Nero`3",
                                            "rounds"=>20,
                                            "wearoff"=>"hai dimenticato gli insegnamenti del `xCavaliere Nero`0 e la forza del tuo attacco torna alla normalità",
                                            "atkmod"=>1.2,
                                            "roundmsg"=>"usi il colpo speciale che ti ha insegnato il `xCavaliere Nero`0 e la tua forza in attacco aumenta",
                                            "activate"=>"offense");
                debuglog("paga 1 gemma al BlackNight e guadagna 10% exp");
            break;
            case 11:
            	$gems = e_rand(5,10); 
                output("`3ma i tuoi riflessi non ti tradiscono e riesci a parare il micidiale colpo del tuo avversario passando successivamente all'offensiva. Incomincia così un susseguirsi di affondi, parate, finte, schivate, stoccate e il duello si sta per concludere a tuo favore quando, al momento di sferrare il colpo finale scivoli sull'erba umida e la tua arma ti cade dalle mani."); 
                output("Purtroppo la `\$Pietra di Poker`3 in tuo possesso ha mostrato tutta la sua influenza negativa e la sfortuna in essa racchiusa si è accanita su di te, ma il `xCavaliere Nero`3 impressionato dalle tue capacità combattive ti da una gran pacca sulla schiena facendoti le sue congratulazioni. Poi, prima di andarsene per la sua strada, ordina al suo scudiero di donarti una borsa contentente `&$gems gemme!`n`n");
                $session['user']['gems']+=$gems;
                debuglog("paga 1 gemma al BlackNight e guadagna $gems gemme");
            break;    
            case 12:
            	$gold = e_rand($session['user']['level']*200,$session['user']['level']*400); 
                output("`3ma i tuoi riflessi non ti tradiscono e riesci a parare il micidiale colpo del tuo avversario anche se al momento di passare al contrattacco scivoli e sei costrett".($session['user']['sex']?"a":"o")." ad appoggiare un ginocchio al suolo lasciandoti alla mercé del tuo avversario."); 
                output("Purtroppo la `\$Pietra di Poker`3 in tuo possesso ha mostrato tutta la sua influenza negativa e la sfortuna in essa racchiusa si è accanita su di te, ma il `xCavaliere Nero`3 impressionato dalle tue capacità difensive ti porge il braccio per rialzarti congratulandosi. Poi, prima di risalire a cavallo e ripartire verso la sua destinazione, ordina al suo scudiero di consegnarti come premio una borsa contentente `^$gold `6pezzi d'oro!`n`n");
                $session['user']['gold']+=$gold;
                debuglog("paga 1 gemma al BlackNight e guadagna $gold oro");
            break;    
            case 13:            	
				if ($pugni) {
					output("`3e mentre cerchi di metterti in guardia per fronteggiare la micidiale stoccata del `xCavaliere Nero`3, la `\$Pietra di Poker`3 in tuo possesso mostra tutta la sua influenza negativa e la sfortuna in essa racchiusa si è accanisce su di te : la cintura che regge le tue brache si rompe e queste iniziano a scendere. `n"); 
	                output("`3L'invincibile guerriero ferma il suo colpo e ti guarda disgustato mentre cerchi disperatamente di coprire le tue nudità : quindi rinfodera la sua arma, risale a cavallo e si allontana borbottando contro l'incapacità dei giovani guerrieri seguito dal suo fedele scudiero che sghignazza allegramente al tuo indirizzo.`n`n");
				}else{
					output("`3e mentre cerchi di metterti in guardia per fronteggiare la micidiale stoccata del `xCavaliere Nero`3, la `\$Pietra di Poker`3 in tuo possesso mostra tutta la sua influenza negativa e la sfortuna in essa racchiusa si è accanisce su di te : la fibbia della tua cintura si rompe e le tue armi cadono al suolo. `n"); 
	                output("`3L'invincibile guerriero ferma il suo colpo e ti guarda disgustato mentre tu cerchi disperatamente di raccogliere $articoloarma`# ".$session['user']['weapon']."`3 guardandolo terrorizzat".($session['user']['sex']?"a":"o")." come ".($session['user']['sex']?"una lepre impaurita":"un coniglio impaurito").". Sdegnosamente rinfodera la sua arma, risale a cavallo e si allontana borbottando contro la tua incapacità seguito dal suo fedele scudiero che scrolla il capo al tuo indirizzo.`n`n");
	            }    
                output("`3Umiliat".($session['user']['sex']?"a":"o")." dalla brutta figura perdi parte della tua autostima e il `&5%`3 di esperienza!");  
                $session['user']['experience']*=0.95;
                debuglog("paga 1 gemma al BlackNight e perde 5% exp");
            break;    
            case 14:
                output("`3e mentre ti appresti a parare la micidiale stoccata del `xCavaliere Nero`3, la `\$Pietra di Poker`3 in tuo possesso mostra tutta la sua influenza negativa e la sfortuna in essa racchiusa si è accanisce su di te : inciampi in una radice che spunta dal terreno e finisci con il sedere per terra. `n"); 
                output("`3L'invincibile guerriero ti guarda sdegnosamente, rinfodera la sua arma, risale a cavallo e si allontana borbottando contro la tua incapacità seguito dal suo fedele scudiero che scrolla il capo al tuo indirizzo.`n");
                output("`3Umiliat".($session['user']['sex']?"a":"o")." dalla brutta figura perdi parte della tua autostima e il `&5%`3 di esperienza!");  
                $session['user']['experience']*=0.95;
                debuglog("paga 1 gemma al BlackNight e perde 5% exp");
            break;    
        	case 15:
                output("`3e mentre ti appresti a parare il micidiale colpo del `xCavaliere Nero`3, la `\$Pietra di Poker`3 in tuo possesso mostra tutta la sua influenza negativa e la sfortuna in essa racchiusa si è accanisce su di te : inciampi in una radice che spunta dal terreno e finisci lungo e disteso per terra con la faccia dentro un cumulo di escrementi di cavallo. `n"); 
                output("`3L'invincibile guerriero scoppia in una grassa risata, rinfodera la sua arma, risale a cavallo e si allontana seguito dal `2Troll`3 suo scudiero che anche lui se la ride allegramente al tuo indirizzo.`n");
                output("`3Zozz".($session['user']['sex']?"a":"o")." e puzzolente ti rialzi vergognandoti per la figuraccia appena fatta: oltre ad aver bisogno di una bella doccia, la lordura che si è depositata sui tuoi abiti ti ha reso meno affascinante! ");  
                $session['user']['charm'] -= 5;
                $session['user']['clean'] += 5 ;
                debuglog("paga 1 gemma al BlackNight e perde 5 punti fascino");
            break;
        }
        if ($session['user']['turns'] < 0) $session['user']['turns'] = 0;
        if ($session['user']['alive']) {
    	 	$session['user']['specialinc']="";
	    	addnav("`2Ritorna alla Foresta","forest.php"); 
	    }

}else{
    $session['user']['specialinc']="";
    output("`n`3Rendi i tuoi omaggi al `xCavaliere Nero`3 con un ossequioso inchino, ma declini rispettosamente la sua offerta.`n");
    output("`3Ti è stata data l'opportunità di allenarti con uno dei più famosi guerrieri mai esistiti sui mondi conosciuti, ma hai rifiutato un così grande onore per non separarti da una misera `&gemma`3.`n");
    output("`3Chissà se hai fatto la scelta giusta.... ");
    output("`3Con questi pensieri per la testa ritorni nella foresta pront".($session['user']['sex']?"a":"o")." ad affrontare nuove avventure.`n");
    addnav("Ritorni nella Foresta","forest.php"); 

}
page_footer();

?>