<?
require_once "common.php";
page_header("Le rovine nella nebbia");

$o = ($session['user']['sex'] == 0) ? "o" : "a";
$session['user']['specialinc']="nebbia.php";

switch ($_GET['op']) {
	
	case "":
	output("`@Mentre girovaghi in cerca di avventure nel fitto della foresta ti ritrovi improvvisamente circondat$o da una fittissima nebbia. Non essendo in ");
	output("grado di orientarti, la scelta migliore sarebbe quella di seguire a ritroso le tue orme finché ne hai la possibilità.");
	$id = $session['user']['acctid'];
	$sql = "SELECT name, value1, value2, gold, gems, description, hvalue FROM items WHERE class='nebbia' AND owner=$id";
	$result = db_query($sql);
	if ((db_num_rows($result) > 0) OR ($session['user']['attack'] < 100)) {
		output("`nNel voltarti, scorgi poco lontano un luccichìo: avvicinandoti scopri una `^gemma`@ semisepolta nel terreno, e prontamente te la metti in tasca.");
		$session['user']['gems'] += 1;
		$session['user']['specialinc']="";
		addnav("Torna alla foresta","forest.php");
		debuglog("trova una gemma nella nebbia");
		}
	else {
	output(" Ti rendi conto che proseguire è un'idea assolutamente folle, tuttavia ti fermi un istante per riflettere sul da farsi.`n`n");
	output("`^Cosa preferisci fare? Vuoi proseguire o tornare indietro?");
	addnav("Prosegui","forest.php?op=prosegui");
	addnav("s?Torna sui tuoi passi","forest.php?op=torna");
	}	
	break;
	
	case "torna":
	output("`@Decidi che la tua sete d'avventura non è abbastanza grande da giustificare una simile imprudenza. Con molta pazienza cerchi le tue impronte e ");
	output("le segui finché la visibilità non torna accettabile. In cuor tuo senti di aver fatto la scelta più giusta, perché `^ci sono esperienze che è meglio ");
	output("non vivere`@.`n`n`^La tua breve avventura ti ha comunque portato via `^cinque turni`@.");
	$session['user']['specialinc']="";
	$session['user']['turns'] -= 5;
	addnav("Torna nella foresta","forest.php");
	break;
	
	case "prosegui":
	output("`@In un impeto di pazzia, `^perché solo di questo puó trattarsi`@, decidi di proseguire alla cieca. Un'idea quantomeno bislacca, si potrebbe dire. ");
	output("Dopo aver segnato la corteccia di un albero per identificare il punto di partenza (ma non potevi usare tutto questo ingegno per scegliere di tornare ");
	output("verso casa?) ti metti in cammino.`nLa scarsissima visibilità e la pesante umidità cominciano ben presto a renderti ad ogni passo più nervos$o e ");
	output("insicur$o riguardo alla scelta presa. Sicché, dopo un tempo che pare infinito, decidi di riposarti ai piedi di un albero. Mentre ti avvicini ");
	output("all'imponente vegetale noti però con sgomento che è lo stesso che avevi segnato precedentemente. Hai girato in tondo per ore. Ottima idea, davvero!`n");
	output("Infuriat$o con te stess$o prendi l'albero a pugni, scorticandoti le nocche. Sei cert$o di poter ritrovare le tue vecchie impronte: hai ancora la ");
	output("possibilità di tornare in direzione del villaggio. Lo farai, vero? Sarebbe saggio. `^Davvero`@. Non c'è alcun motivo per continuare a girare ");
	output("a vuoto rischiando di perdersi. Non è difficile da capire. Tornare indietro: buono. Continuare a girovagare: cattivo. Molto, molto semplice.`n`n");
	output("`^Cosa decidi di fare?");
	
	addnav("Continua a girovagare","forest.php?op=continua");
	addnav("s?Torna sui tuoi passi","forest.php?op=torna");
	break;


	case "continua":
	output("`@È ormai evidente che il tuo continuo fare a botte in foresta ti abbia procurato dei pesanti danni cerebrali. È l'unica spiegazione per questa ");
	output("altrimenti incomprensibile forma di autolesionismo. Riprendi dunque a vagare per la foresta muovendoti come uno zombie, le mani protese in avanti perché è ormai ");
	output("difficile anche solo distinguere gli alberi che ti si parano davanti. Per ingannare il tempo cominci a contare i tuoi passi, ma perdi il conto ");
	output("dopo essere arrivat$o a quota `^12.984.567`@. Quando sei ormai sull'orlo del suicidio, dal nulla sbuca un vecchio decrepito, vestito con una lunga tunica `$rossa `@ il cui  ");
	output("cappuccio copre parzialmente il suo viso. L'aspetto che più ti colpisce è sicuramente la sua lunghissima barba, sistemata a mo' di sciarpa attorno al suo collo. ");
	output("Appena ti vede, l'improbabile individuo comincia a sbraitare: `#\"DOVE STAI ANDANDO??? Sei forse impazzit$o? Non sai dove ti trovi? Non sai che fine stai per fare? ");
	output("Non sai che `^dovresti decisamente tornare indietro, finché puoi`#? Posso darti una mano, se lo desideri! Conosco un incantesimo che ti riporterà ");
	output("al tuo villaggio istantaneamente, al misero costo di `^dieci turni`# e `^duemila monete`#! Provare per credere!\"`n`@Bisogna ammetterlo, la tentazione è davvero forte. ");
	output("Perché mai dovresti continuare? `^Chi ti assicura che potresti trarne giovamento?`n`#\"Ci sono esperienze che è meglio non vivere!\" `@Ti ammonisce ");
	output("il vecchio strambo. E si sa, la saggezza degli anziani è un tesoro prezioso. Probabilmente... no, `^sicuramente `@dovresti ascoltarlo.`n");
	output("`n`^Cosa scegli di fare?");
	
	addnav("Insisti!","forest.php?op=insisti");
	addnav("Accetta la proposta","forest.php?op=accetta");
	break;
	
	case "accetta":
	if ($session['user']['gold']<2000) {
		output("`@Tremendamente sconfortat$o e finalmente pront$o ad arrenderti porti automaticamente una mano alla borsa delle monete per sincerarti di poter pagare ");
		output("il servizio, e la trovi con disappunto fin troppo leggera.`n`3\"Anche volendo, non potrei pagarti la cifra che chiedi. Saresti disposto a farmi ");
		output("uno sconto?\" `@chiedi col tono più cortese che tu abbia mai utilizzato in vita tua.`nL'uomo a stento ti permette di finire la frase, poi si lancia");
		output(" a terra ridendo come un matto: `#\"Poveracci$o oltre che stupid$o! Sconto? Ma quale sconto? Stai fresc$o! Se hai deciso di arrivare fin qui puoi ");
		output("benissimo proseguire: vai, vai e divertiti! Incredibile, incredibile!\"`n`@Piuttosto contrariat$o per l'atteggiamento del vecchio, pare che comunque ");
		output("tu sia ormai costrett$o a insistere nella tua ricerca di... cos'è che cercavi? Sicur$o che fosse una buona idea? Beh, a questo punto insisti, dai... ");
		output("magari trovi davvero qualche tesoro (ma magari `^NO`@.)");
		
		addnav("Insisti!","forest.php?op=insisti");
		}

	else {
		output("`@Ce n'è voluto di tempo, ma alla fine hai capito l'antifona. Senza fartelo ripetere due volte lanci una borsa di monete tra le mani del vecchio e");
		output("chiudi gli occhi. `3\"Fa' presto, ti prego. Giro a vuoto da non so quanto, sono esaust$o!\"`n`@Non fai in tempo a terminare la frase che già il ");
		output(" brusìo della piazza giunge come la più dolce delle melodie alle tue orecchie. Rincuorat$o, assapori questo meraviglioso istante prima di aprire ");
		output("finalmente gli occhi. Mai soldi furono meglio spesi, `^questo è certo`@.");
		$session['user']['specialinc']="";
		addnav("Apri gli occhi","village.php");
		$session['user']['gold'] -= 2000;
		$session['user']['turns'] -= 10;
		}
	break;
	
	case "insisti":
		$class = "nebbia";
		$id = $session['user']['acctid'];
		$hpmax = $session['user']['maxhitpoints'];
		$hp = $session['user']['hitpoints'];
		$atk = $session['user']['attack'];
		$def = $session['user']['defence'];
		$gold = $session['user']['gold'];
		$gems = $session['user']['gems'];
		$turns = $session['user']['turns'];	
		$sql = "INSERT INTO items (name, class, owner, value1, value2, gold, gems, description, hvalue) VALUES ($turns, '". $class. "', $id, $hpmax, $hp, $gold, $gems, $atk, $def)";
		db_query($sql);
		//E ci siamo salvati tutti i dati "sensibili" da parte, così possiamo continuare.
	
	output("`@La tua testa è apparentemente dura come le scaglie di drago, e decidi quindi di ignorare il vecchio. Lo saluti con un cenno e senza ulteriore ");
	output("indugio prosegui lungo la tua strada.`nGirovagando senza meta tra la nebbia `(perdi la metà dei tuoi turni `@prima di riuscire a trovare riparo ");
	output("in un antico edificio evidentemente abbandonato.`n`n");
	output("Esaust$o per il continuo vagare e incapace di ritrovare la strada per il villaggio, ti fai forza e decidi di esplorare il luogo.`n");
	output("Addentrandoti sempre più verso l'interno della costruzione cominci a convincerti della presenza di alcune voci che bisbigliano intorno a te: ");
	output("ti trovi in un lungo corridoio quando all'improvviso avverti un tonfo sordo alle tue spalle e ti volti di scatto, senza però riuscire a scorgere altro che oscurità.`n");
	output("Decis$o a scoprire se qualcuno ti stia davvero seguendo, prosegui fino a raggiungere due porte poste l'una di fronte all'altra e ne approfitti ");
	output("per nasconderti dietro una delle due per spiare l'esterno e prendere in contropiede l'eventuale misteriosa presenza. ");
	output("La porta alla tua sinistra è deturpata dalla presenza di numerosi`^ graffi`@, probabilmente opera di qualche animale randagio; ");
	output("quella alla tua destra è invece assolutamente intonsa.`n`n`^Quale delle due scegli di varcare?`0");
	
	addnav("s?Entra a sinistra","forest.php?op=sinistra");
	addnav("d?Entra a destra","forest.php?op=destra");
	$session['user']['turns'] = intval($session['user']['turns'] / 2);
	break;
	
	case "sinistra": case "destra":		//La cosa è voluta. Non c'è via di scampo, l'evento nasce come un esercizio e diventa scherzo bastardo.
	$lato = $_GET['op'];
	output("`@Scegli di dirigerti alla tua`^ $lato`@. Muovendoti lentamente ti appoggi alla porta e spingi piano verso l'interno. Il cigolìo dei cardini arrigginiti ");
	output("ti gela il sangue per un istante mentre metti piede in quella che sembra... una`^ sala delle torture`@! Sala che, perdipiù, sembra essere decisamente meglio ");
	output("tenuta del resto dell'edificio: a giudicare dall'assenza di polvere è più che probabile che qualcuno la utilizzi abbastanza frequentemente.`n");
	output("Hai ormai raggiunto il centro della stanza quando noti un cadavere impiccato in un angolo: spalanchi gli occhi per la sorpresa e decidi ");
	output("di fare dietrofront per raggiungere la stanza di fronte, ma voltandoti inciampi in un sacco e cadi rovinosamente battendo la testa su una cassa di metallo.`n`n");
	output("Ti rialzi stordit$o dopo chissà quanto tempo. Le tempie pulsano forsennatamente mentre un fiotto di sangue caldo ti riga il viso.`n`^Per il tempo perso ");
	output("`(hai consumato un'ulteriore metà dei turni rimanenti`^!`nLe tue forze ti abbandonano sempre più: `(hai perso la metà dei tuoi HP permanenti`@!!!`n`n");
	output("Ti guardi intorno cercando di non pensare al dolore che ti trafigge il cranio: sai che se non riuscirai a curarti perderai nuovamente conoscenza, ");
	output("e stavolta probabilmente per sempre. Ti muovi arrancando verso un grande armadio alla ricerca di qualcosa che possa aiutarti a fermare l'emorragia.`n");
	output("Apri ogni anta finché non riesci a trovare un piccolo contenitore pieno di`$ pozioni `fsconosciute`@. Disperat$o e annebbiat$o dal dolore, decidi di ");
	output("provarne una nella speranza che abbia capacità curative.`n`n`^Quale pozione scegli? Quella`$ rossa`^ oppure quella `fblu`^?");
	
	addnav("r?Scegli quella`$ rossa`0","forest.php?op=rossa");
	addnav("b?Scegli quella`f blu`0","forest.php?op=blu");
	$hp = $session['user']['hitpoints'];
	$session['user']['turns'] = intval($session['user']['turns']/2);
	$session['user']['maxhitpoints'] = intval($session['user']['maxhitpoints']/2);
	$session['user']['hitpoints'] = ($hp < $session['user']['maxhitpoints']) ? $hp : $session['user']['maxhitpoints'];
	break;
	
	case "rossa": case "blu":
	$colore = ($_GET['op'] == "rossa") ? "`$ rossa" : "`f blu" ;
	output("`@Di una cosa sei sicur$o: le pozioni curative che il guaritore ti propina normalmente hanno un odore nauseabondo. Dato che la pozione$colore ");
	output("`@ha un aroma quasi altrettanto disgustoso decidi di tentare la sorte e senza ulteriore esitazione la trangugi tutta d'un fiato.`n");
	output("Ti ci vogliono solo pochi secondi per capire di aver commesso un `^errore madornale`@! Senti le tue ultime energie abbandonarti mentre cadi ");
	output("di peso sul pavimento freddo e umido, le gambe ormai incapaci di sorreggerti oltre. Un torpore innaturale avvolge i tuoi arti, mentre si fa ");
	output("strada dentro di te la netta sensazione di essere consumat$o dall'interno. Guardi con orrore le tue braccia contorcersi mentre la massa ");
	output("muscolare si riduce ogni secondo di più, fino a lasciarti quasi completamente rachitic$o. Passa ancora qualche secondo, poi il dolore ");
	output("esplode nuovamente in tutto il corpo lasciandoti senza fiato. Incapace di sopportarlo oltre, perdi definitivamente conoscenza.`n`n");
	output("Ti risvegli svariate ore più tardi, dannatamente priv$o di forze. Disperat$o, ti rimetti in piedi decis$o a fuggire quanto prima da ");
	output("questo luogo maledetto. Fai una fatica immensa nel raccogliere la tua arma, rendendoti conto con sgomento che parte della tua forza è andata definitivamente ");
	output("perduta. Con la mente svuotata e i sensi ovattati ti dirigi verso il corridoio da cui provieni. In un modo o in un altro riuscirai a sopravvivere.`n`n");
	output("`^La pozione ti ha portato via `(metà dei tuoi punti attacco`^!!!");
	
	addnav("Torna nel corridoio","forest.php?op=corridoio");
	$session['user']['attack'] = intval($session['user']['attack']/2);
	break;
	
	case "corridoio":
	output("`@Un po' per paura e un po' per prudenza, attendi svariati secondi prima di varcare nuovamente la porta. Quando torni nel corridoio lo ritrovi ");
	output("così come lo ricordavi: immerso nell'oscurità. Aguzzi le orecchie tentando di percepire qualcosa, ma i sussurri che prima ti circondavano ");
	output("sembrano aver lasciato spazio a un profondo e altrettanto inquietante silenzio. Ritorni sui tuoi passi, sperando di ritrovare presto l'uscita.`n");
	output("Dopo aver camminato a lungo sbuchi in un androne che non riconosci. Ti senti comunque confortat$o dalla `^luce `@che filtra attraverso numerose ");
	output("finestre. All'altro capo della sala una porta socchiusa sembra dare sull'esterno. Muovi un primo, speranzoso passo verso la salvezza quand'ecco ");
	output("che dalle tue spalle giunge un clangore metallico, come di catene trascinate lungo il pavimento di pietra. Migliaia di voci cominciano improvvisamente ");
	output("a bisbigliare intorno a te, mentre ombre oscure appaiono in prossimità delle finestre, immobili e minacciose.`nTerrorizzat$o dalle apparizioni, ");
	output("affretti il passo in direzione dell'uscita. Il lamento degli spettri si fa sempre più intenso, fino a sfociare in una serie di urla agghiaccianti ");
	output("quando sei ormai a pochi metri dalla salvezza. La tua schiena viene percorsa da un brivido quando la figura di un boia, eterea ma terribilmente ");
	output("reale, compare accanto all'uscio. Immobile, il boia non ti guarda: si limita a indicare con la sua ascia proprio la porta cui stavi puntando.`n");
	output("Ti blocchi a breve distanza, incert$o sul da farsi. La tua mente fa ormai fatica ad analizzare ciò che sta succedendo. Quando sei sull'orlo di ");
	output("una crisi di nervi scorgi un barlume, una piccola fessura tra i mattoni della parete alla tua destra da cui filtra della luce, luce solare!. ");
	output("`^È un passaggio segreto! `@Sposti lo sguardo dalla porta del boia al passaggio, con la mente in preda a mille dubbi: cosa fare? Quale strada ");
	output("imboccare? Finora il tuo sesto senso ti ha tradito più di una volta: forse sarebbe meglio fare l'opposto di quello che ti suggerisce.`n`n");
	output("`^Quale via scegli?");
	
	addnav("b?La porta del boia","forest.php?op=boia");
	addnav("p?Il passaggio segreto","forest.php?op=passaggio");
	break;
	
	case "boia" : case "passaggio" :
	$text1 = "`@Decidi che ne hai abbastanza di segreti e misteri, e inoltre il boia in fondo non sembra così pericoloso. Ti dirigi a passo spedito verso la 
			porta, spalancandola con un calcio. Abbagliat$o dalla luce del sole e felice di essere finalmente salv$o, muovi i primi passi senza neppure 
			guardare dove metti i piedi.`n";
	$text2 = "`@Decidi che ne hai abbastanza di spettri e brutte sorprese, quindi scegli di farne tu stess$o una alle entità che ti stanno osservando. 
			Ti dirigi a passo spedito verso il passaggio, cert$o che gli spettri non si aspettino che tu l'abbia notato. Ti lanci contro il muro con tutte 
			le tue forze, spalancando il passaggio con una spallata. Abbagliat$o dalla luce del sole e felice di essere finalmente salv$o, ti ritrovi 
			improvvisamente all'aperto.`n";
	$text = ($_GET['op'] == "boia") ? $text1 : $text2 ;
	output($text);
	output("La tua gioia dura però molto poco, poiché metti un piede in fallo nel momento stesso in cui ti accorgi di essere sull'orlo di un dirupo. Perdendo ");
	output("l'equilibrio, ti ritrovi a precipitare per almeno venti metri atterrando su un cumulo di rocce.`nIl dolore è insopportabile, senti tutte le ");
	output("tue ossa a pezzi, dalla prima all'ultima. Non riesci a muoverti e capisci che la tua fine è arrivata, ironia della sorte, proprio ");
	output("quando eri a un passo dalla salvezza. Ormai rassegnat$o, ti abbandoni completamente all'oblio.`n`n");
	output("`^Le gravissime ferite ti hanno fatto perdere `(metà dei tuoi punti difesa`^!!!`n");
	output("Durante la caduta hai inoltre perso `(tutto il tuo oro e le tue gemme`^!!!");
	
	addnav("O?Vai al Regno delle Ombre","forest.php?op=ombre");
	$session['user']['defence'] = intval($session['user']['defence']/2);
	$session['user']['gold'] = 0;
	$session['user']['gems'] = 0;
	break;
	
	case "ombre":
	output("`@Ti risvegli improvvisamente, convint$o di essere ormai nel regno dei morti. Guardandoti intorno capisci però di essere in errore: ti trovi in una ");
	output("stanza enorme, di forma ovale, riccamente arredata. Sei stes$o su un letto a baldacchino finemente decorato, con lenzuola di raso`$ rosso`@. Una figura ");
	output("incappucciata da un manto anch'esso`$ rosso `@è affacciata ad una finestra, dandoti le spalle.`nAppena gli posi gli occhi addosso, il misterioso ");
	output("individuo comincia a parlare: `#\"Bentornat$o nel mondo dei vivi. Se non ti avessi rinvenut$o durante una delle mie escursioni saresti già ");
	output("nell'oltretomba. Eri in fin di vita, ma fortunatamente i miei poteri trascendono anche la morte.\"`n`@Resti in silenzio, cercando di capire con chi ");
	output("hai a che fare stavolta: che sia il vecchio incontrato nella foresta? Deglutisci lentamente, mentre una goccia di sudore ti riga la fronte.`n`#\"Sai\" `@continua il tuo misterioso interlocutore ");
	output("`#\"trovo sinceramente affascinante la curiosità di voi mortali. Questa totale incapacità di leggere i segni, di capire quando è il momento di fermarsi. ");
	output("È stato interessante studiare le tue mosse all'interno della prigione in rovina. Ti ringrazio per avermene dato l'opportunità, e ti offro in cambio ");
	output("un accordo.\"`n`@Fai per aprir bocca, ma resti senza fiato. I battiti del tuo cuore accelerano mentre la rabbia monta, cieca: `3\"Non hai ");
	output("forse appena detto di avermi `^trovat$o durante una tua escursione`3? Come puoi dire di avermi osservat$o? Eri lì o no mentre vivevo le pene ");
	output("dell'inferno? Rispondi! Sei tu, vecchio balordo? Che tu sia dann...\"`n`@Le parole ti muoiono in bocca quando la figura alza una mano. Non riesci più a parlare, come se un nodo alla gola bloccasse");
	output("ogni sillaba. `#\"L'ho detto? Non ricordo di averlo detto. Ma dov'ero rimasto? Ah, sì... ti propongo uno accordo. Ti potrebbe interessare?\" ");
	output("`@L'essere si volta a guardarti, e strabuzzi gli occhi riconoscendo in lui il volto di`$ Mandos`@. Da una delle tasche della tunica fuoriesce quella che sembra ");
	output("una barba finta, evidentemente malriposta dopo la sua prima visita nella foresta. Non avendo facoltà di rispondere, resti in silenzio con ");
	output("un'espressione imbronciata. `#\"Bene, chi tace acconsente!\" `@commenta lui`@.`n`#\"Mi sembra di capire che tu non sia in ottime condizioni di salute. ");
	output("Me ne rincresce, anche se ovviamente io non c'entro assolutamente nulla. Ma veniamo al dunque. Se vuoi, posso ripristinare tutte le tue facoltà fisiche in ");
	output("un batter d'occhio. Tuttavia dovrai accettare `^due `#condizioni. La prima, `^FONDAMENTALE`#, è che non dovrai parlare a nessuno di ciò che ti è capitato. ");
	output("Se io o qualcuno dei miei fratelli e sorelle dovessimo venire a sapere che hai parlato della tua esperienza ad anima viva o morta la mia collera si abbatterà su di te. ");
	output("La seconda condizione è che dovrai portare il marchio di questa avventura finché lo desidererò. L'alternativa è ovviamente quella di tornare in città nello stato in cui ");
	output("ti ritrovi. Puoi pensarci con calma, io non ho fretta.\"`n`@La proposta ti sembra allettante, ma potrebbe rivelarsi un ultimo disgraziato tranello: ");
	output("decidi di prenderti del tempo per ragionarci, ma dopo solo pochi secondi`$ Mandos `@ti apostrofa: `#\"E allora, ci muoviamo? Devo portare il cibo al ");
	output("beholder, non è che posso star qui tutto il giorno!\"`n`n`^Cosa scegli?");
	
	addnav("D'accordo, accetto","forest.php?op=daccordo");
	addnav("Col cavolo, non mi freghi!", "forest.php?op=cavolo");
	break;
	
	case "daccordo": case "cavolo":
		$class = "nebbia";
		$id = $session['user']['acctid'];
	
	if ($_GET['op'] == "daccordo") {
		$titolo = ($session['user']['sex'] == 0) ? " `(il Pollastro" : " `(la Pollastra";
		output("`@Anche se vorresti solo insultarlo, ti mordi la lingua e scegli di accettare l'accordo. Resti sorpres$o della tua voce quando senza pensarci parli ");
		output("senza alcun problema: `3\"Va bene, mi hai convinto\" `@ti limiti a sussurrare.`$ Mandos `@ti sorride e si avvicina al letto, portando una mano verso ");
		output("il tuo viso. Chiudi gli occhi quando ti sfiora, e il suo tocco è nonostante tutto caldo e piacevole: cominci subito a rilassarti. Quando avverti ");
		output("la mano allontanarsi da te, apri gli occhi e ti ritrovi davanti un ghigno divertito: non fai in tempo a capire cosa sta per succedere e dunque non ");
		output("riesci a proteggerti.`n`$ Mandos`@ urla a squarciagola `#\"TECNICA ANCESTRALE DELLO SCHIAFFO CURATIVO!\" `@e ti colpisce in pieno volto con una ");
		output("potenza inaudita, tanto che per tua fortuna perdi conoscenza già un attimo prima di ricevere la botta.`n`n");
		output("Ti risvegli al limitare della piazza del villaggio, stes$o sul terreno. Ti senti stranamente in perfetta forma. Pare che, dopotutto, fidarsi di");
		output("`$ Mandos `@sia stata una scelta vincente. Rialzandoti noti accanto a te le tue borse contenenti tutto l'oro e le gemme che avevi prima di inoltrarti ");
		output("nella nebbia. Ti senti anche parecchio riposat$o, come se la disavventura fosse stata solo un sogno. Forse, ti dici, lo è stato per davvero. Alzi le spalle, ");
		output("decis$o comunque a `^mantenere il patto e non parlare a NESSUNO di ciò che ti è accaduto`@. Non si sa mai.`n`n");

		$session['user']['ctitle'] = " " . $titolo;
		$session['user']['name'] = $session['user']['title'] . " " . $session['user']['login'] . " " . $titolo ;
		$name = $session['user']['name'];
		output("`^Tutti i tuoi HP e i punti attacco e difesa sono stati ripristinati!`nHai inoltre recuperato tutti i tuoi averi!`n`n");		
		output("`^Purtroppo, in cambio di questa concessione sarai da oggi conosciuto come $name`@! E senza poterne spiegare il perché ai tuoi concittadini...");
		debuglog("viene pres$o a ceffoni da `\$Mandos`0");
		}	
	else {
		output("`@Non ti piegheresti mai ad un simile ricatto, dunque scuoti violentemente il capo e raccogli le tue forze cercando di alzarti. In qualche modo ");
		output("dovrai pur fuggire!`$ Mandos `@ti osserva divertito mentre lentamente ti lasci cadere dal materasso e strisci disperat$o verso la porta della stanza. ");
		output("La sua risata divertita ti rende ancora più furios$o e al contempo determinat$o a riuscire nel tuo intento. Con le tue ultime energie ti ");
		output("alzi in piedi e ti trascini verso la porta, già socchiusa. L'apri di scatto senza accorgerti dell'ultimo ignobile trucchetto, una grossa roccia ");
		output("tenuta in equilibrio precario proprio sopra lo stipite: la caduta è rovinosa e il macigno ti colpisce in piena testa. Ormai non riesci a portare il conto ");
		output("delle volte in cui hai perso i sensi oggi, quel che è certo è che sembri averci fatto l'abitudine: la tua coscienza scivola via veloce insieme ");
		output("al dolore...`n`nApri gli occhi. Sei nella foresta. La testa ti duole, ma meno di prima. Gambe e braccia si muovono normalmente, anche se un po' ");
		output("indolenzite. Ti alzi lentamente, massaggiandoti la tempia destra. Devi essere scivolat$o e aver battuto la testa cadendo. Non c'è traccia di");
		output("`$ Mandos`@, degli spettri, della nebbia. Solo tu, qualche albero e la grossa pietra sulla quale la tua testa era poggiata fino a qualche secondo ");
		output("fa. Controlli subito se i tuoi averi sono a posto: sì, ci sono. Gemme e oro, c'è tutto. Anche le tue capacità sembrano effettivamente inalterate, ");
		output("e non ti senti più stanc$o di quanto non fossi all'inizio della tua disavventura. Tirando più di un sospiro di sollievo, decidi di dirigerti ");
		output("immediatamente al villaggio.`n`nFatti pochi passi, noti una pergamena affissa su un albero. Credendolo un avviso di taglia, decidi di controllare. ");
		output("Avvicinandoti capisci tuttavia che non è ciò che pensavi: la pergamena riporta solo poche lettere.`n`c`^NON. UNA. PAROLA.`n`cTurbat$o, decidi ");
		output("che in ogni caso sarà meglio non parlare mai a nessuno dell'esperienza appena vissuta.");
		debuglog("se l'è vista brutta, ma almeno ha evitato di essere pres$o a ceffoni");
		}
		
	$session['user']['specialinc']="";
	addnav("Torna al villaggio","village.php");
	
	
		$sql = "SELECT name, value1, value2, gold, gems, description, hvalue FROM items WHERE class='nebbia' AND owner=$id";
		$result = db_query($sql) or die(db_error(LINK));
        $row = db_fetch_assoc($result);
		
		$session['user']['maxhitpoints'] = $row['value1'];
		$session['user']['hitpoints'] = $row['value2'];
		$session['user']['attack'] = $row['description'];
		$session['user']['defence'] = $row['hvalue'];
		$session['user']['gold'] = $row['gold'];
		$session['user']['gems'] = $row['gems'];
		$session['user']['turns'] = $row['name'];
		
		
		
		//Cancellazione item per ritentare l'evento a scopo di debug:
		//$sql2 = "DELETE FROM items WHERE class='nebbia' AND owner=$id";
		//db_query($sql2);
		
	break;		
	}
	
page_footer();
?>