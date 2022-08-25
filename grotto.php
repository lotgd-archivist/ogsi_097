<?php
/**
* Gnomes Grotto
*
* A rewrite of the outhouse script for Legend of the Green Dragon
* The player can choose to eat the Feast or soup. It costs Gold
* to eat the Feast. The soup is free. After eating one of the Gnomes meals,
* the player can tip their host or leave. If they choose to tip their host, there is a
* chance that they can get their gold back. If they don't choose to tip their host, there
* is a chance that they will lose some gold. If they lose gold there is an entry added
* to the daily news.
* Tradotto da Excalibur per www.ogsi.it
* excalthesword@fastwebnet.it
*/
require_once("common.php");

// Quanto costa fare un banchetto
$cost = $session['user']['reincarna']*5 + 5;
// Quanto oro il player deve avere prima di perderne
$goldinhand = 2;
// Quanto oro deve essere restituito se il player si complimenta con lo gnomo
$giveback = 3;
// Quanto oro prendere se il player è punito per non essersi complimentato con lo gnomo
$takeback = 1;
// Numero casuale minimo per le buone abitudini
$goodminimum = 1;
// Numero casuale massimo per le buone abitudini
$goodmaximum = 10;
// Chance di riavere i soldi
$goodmusthit = 6;
// Numero casuale minimo per le cattive abitudini
$badminimum = 1;
// Numero casuale massimo per le cattive abitudini
$badmaximum = 4;
// Chance di perdere soldi
$badmusthit = 2;
// Metti 1 se per dare chance di usare il bagno gratuitamente
$usebath = 1;
// Percentuale di usare bagno gratis
$usebathpercent = 10;
// Metti 1 per dare la chance di trovare una gemma se il player fa il banchetto e da la mancia allo gnomo
$giveagem = 1;
// Percentuale di trovare gemma
$givegempercent = 10;
$gemminimum = 1;
$gemmaximum = 100;
// Vuoi dare al player un turno se fa il banchetto e da la mancia allo gnomo
// 1 da un turno
// 0 non concede un turno extra
$giveaturn = 0;
// Dove vuoi far tornare il giocatore quando lascia questo luogo?
$returnto = "castelexcal.php";
// Il giocatore ha abbastanza oro per fare il banchetto ?


// Non dovresti editare nulla sotto questa linea !
if ($session[user][gold] >= $cost) $canpay = True;

if ($_GET['op'] == "feast"){
    page_header("Il Banchetto");
    //$session['user']['usedouthouse'] = 1;
    //$session['user']['bladder'] = 0;
    output("`b`c`2Il `&Banchetto`2 della Grotta dello `@Gnomo`0`c`b`n`n");
    output("`2Paghi `^$cost`2 pezzi d'oro per farti servire un `&Banchetto`2 al minuscolo `@Gnomo Cameriere`2 che si è avvicinato al tavolo che hai scelto e dove ti sei accomodato.`n");
    output("\"`@`iQuesto è il miglior Banchetto della zona!`i\" `2esclama sorridendo il simpatico personaggio.`n");
    output("Dopo averci soffiato il naso, il piccolo cameriere si affretta a porgerti un tovagliolo da usare. Dai una rapida occhiata alla sporcizia che copre le sue mani e 
    	disgustato decidi che è meglio non usarlo riponendolo ben piegato sul tavolo.`n");
    output("Il tuo cameriere, il noto `@Gnomo Ghiottone`2 ti ricorda che in caso di bisogno è completamente a tua disposizione e con la grazia e lo stile
    	di un carrettiere ti mostra il didietro iniziando a pulire un tavolo di fronte al tuo.`n");
    output("Nonostante le apparenze trovi il cibo del `&Banchetto`2 delizioso e rifocillante anche se si tratta di cibo per `@Gnomi`2!`n");
    $session['user']['gold'] -= $cost;
    $session['user']['hunger'] -= 20;
    debuglog("spesi $cost pezzi d'oro alla grotta dello gnomo");
    addnav("Dai la mancia", "grotto.php?op=pay");
    addnav("Te ne vai", "grotto.php?op=nopay");
}elseif ($_GET['op'] == "soup"){
    page_header("Minestra e Gallette!");
    //$session['user']['usedouthouse'] = 1;
    //$session['user']['bladder'] = 0;
    $session['user']['hunger'] -= 10;
    output("`b`c`2La `(Minestra di Gallette`2 della Grotta dello `@Gnomo`0`c`b`n`n");
    output("`2La `(Minestra di Gallette`2 di questo locale è così piccante da farti lacrimare gli occhi!`n");
    output("`2Dopo averci soffiato il naso, lo `@Gnomo Cameriere`2 ti porge un tovagliolo da usare. Dai una rapida occhiata alla sporcizia che copre le sue
    	 mani e disgustato decidi che è meglio non usarlo.`n`n");
    output("Pur essendo seduto a tre tavoli di distanza riesci a sentire un odore ripugnante proveniente dal minuscolo cameriere.`n");
    output("Ingurgiti la `(Minestra di Gallette`2 il più velocemente possibile dato che non sei in grado di trattenere il fiato molto a lungo.`n");
    addnav("Dai la mancia", "grotto.php?op=freepay");
    addnav("Te ne vai", "grotto.php?op=nopay");
}elseif ($_GET['op'] == "pay"|| $_GET['op'] == "freepay"){
    page_header("Dai la mancia al Cameriere");
    output("`b`c`2La Grotta dello `@Gnomo`2`c`b`n`n");
    output("`2Il piccolo `@Gnomo cameriere`2 ti sorride, ringrazia ossequioso per la tua generosità e con un inchino ti augura una buona giornata`n`n");
	output("`2Dare la mancia al cameriere è sempre una buona cosa. Il cibo era buono ed il servizio era, ... beh, ... degno di uno `@Gnomo`2`n`n");
    $percallergia = $session['user']['reincarna'];
    if ( $percallergia > 10 ) {
	    $percallergia = 10;
	}    
	$caso = e_rand(1,10);
	if ( $caso > $percallergia ) {
	    output("`2Togli tutte le briciole del tuo pasto dalla tua `%{$session['user']['armor']}`2, ti alzi e ti dirigi verso l'uscita.`0`n");
	    $goodhabits = e_rand($goodminimum, $goodmaximum);
	    if ($goodhabits > $musthit && $_GET['op']=="pay"){
	        output("`2Il proprietario del locale ti blocca e ti comunica con un gran sorriso che sei il loro 100imo cliente di oggi!`n");
	        output("`2Per questo motivo ti consegna `^$giveback`2 pezzi d'oro per essere la celebrità del giorno!`0`n");
	        $session['user']['gold'] += $giveback;
	        debuglog("riceve $giveback pezzi d'oro nella grotta dello gnomo per aver dato la mancia");
	        if ($usebath == 1){
		        $usebathtemp = e_rand(1,100);
	            if ($usebathtemp <= $usebathpercent){
		            output("`2Prima di uscire ne approfitti per fare una capatina al bagno per effettuare un ricambio idrico: tanto è compreso nel prezzo !`n");
					$session['user']['usedouthouse'] = 1;
    				$session['user']['bladder'] = 0;
		            if ($session['user']['drunkenness']>0){
	                	$session['user']['drunkenness'] *= .9;
	                	output("`&Uscendo dal bagno della Grotta dello `@Gnomo`2 ti senti molto meglio, sei anche più sobrio!`n`2");
	            	}
	            }
	        }    
	        if ($giveagem == 1){
	            $givegemtemp = e_rand(1,100);
	            if ($givegemtemp <= $givegempercent){
	
	                $session['user']['gems']++;
	                debuglog("guadagna 1 gemma nella grotta dello gnomo");
	                output("`2Ma che fortuna!! Hai trovato una `&gemma`2 vicino alla porta d'ingresso!`0`n");
	            }
	            if ($giveaturn == 1){
	                $session['user']['turns']++;
	                output("`&Hai guadagnato un turno!`0`n");
	            }
	            
	        }
	    }elseif ($goodhabits > $musthit && $_GET['op'] == "pay"){
	        if (e_rand(1, 3)==1) {
	            output("`2Abandonato per terra vedi un borsellino, lo raccogli, lo apri e .... che bello! Contiene `^$giveback `2pezzi d'oro.`0");
	            $session['user']['gold'] += $giveback;
	            debuglog("trova $giveback pezzi d'oro nella grotta dello gnomo per aver dato la mancia");
	        }
	    }
	}else{
		output("`2Dopo aver tolto le briciole dalla tua `%{$session['user']['armor']}`2 stai per alzarti e riprendere la tua strada quando ti senti strano... 
         	un profondo senso di nausea incomincia a salirti dalla pancia e ti viene da vomitare. Probabilmente sei allergico al cibo degli `@Gnomi`2! ");
        output("Debolissimo ti alzi e ti riavvii verso la foresta, ma i conati di vomito di accompagnano e ti squassano la stomaco, sei rimasto `^Intossicato`2!`n");
        output("Solo ora ti ricordi che man mano che si procedere con le reincarnazioni, l'allergia al cibo degli `@Gnomi`2 aumenta sempre più. `nForse è meglio pranzare in qualche Taverna.`n");
        
        $session['bufflist'][290] = array("name"=>"`%Allergia"
             ,"startmsg"=>"`n`^Ti viene da vomitare!`7`n`n"
             ,"rounds"=>15
             ,"wearoff"=>"La nausea è passata e le tue capacità difensive tornano alla normalità"
             ,"defmod"=>0.7
             ,"roundmsg"=>"I conati di vomito ti impediscono di difenderti al meglio delle tue possibilità"
             ,"activate"=>"defense");
	}		   
    addnav("Esci", "castelexcal.php");
         
}elseif (($_GET['op'] == "nopay")){
    page_header("Non dai la mancia al Cameriere");
    output("`b`c`2La Grotta dello `@Gnomo`2`c`b`n`n");
    output("`2Gli `@Gnomi`2 camerieri si offendono facilmente e sono veramente delusi dal tuo comportamento!`n");
    output("Del cibo così buono, ed un servizio degno di `@Gnomo`2!! Possibile che tua madre non ti ha insegnato le buone maniere ? `n`n");
    output("Il cameriere `@Gnomo`2 responsabile del tuo tavolo chiama il `bButtafuori`b per prendersi cura di te e impartirti una lezione di galateo gnomesco.`n");
    $takeaway = e_rand($badminimum, $badmaximum);
    if ($takeaway >= $badmusthit){
        if ($session['user']['gold'] >= $goldinhand){
            $session['user']['gold'] -= $takeback;
            debuglog("ha perso $takeback pezzi d'oro e 5 punti fascino, ha guadagnato 5 punti odore nella grotta dello gnomo per non aver dato la mancia");
            output("Il `bButtafuori`b della Grotta, un enorme `#Gigante delle Tempeste`2 ti afferra per la collottola e, senza sforzo alcuno, ti solleva e ti fa uscire dal locale passando dalla porta posteriore.
            	Arrivati in un vicolo semibuio, ti perquisisce estraendo dalle tue tasche `^$takeback`2 pezzi d'oro per la tua avarizia, poi ti lancia in aria facendoti cadere nella ripugnante e viscida melma fangosa di un canale di scolo per liquami.`n");
        }else{
	        $session['user']['charm']-=5;
            debuglog("ha perso 10 punti fascino, ha guadagnato 5 punti odore nella grotta dello gnomo per non aver dato la mancia");
            output("Il `bButtafuori`b della Grotta, un enorme `#Gigante delle Tempeste`2 ti afferra per la collottola e, senza sforzo alcuno, ti solleva e ti fa uscire dal locale passando dalla porta posteriore.
            	Arrivati in un vicolo semibuio, ti perquisisce cercando nelle tue tasche un paio di monete d'oro, ma non trovandone, ti lancia in aria facendoti cadere nella ripugnante e viscida melma fangosa di un canale di scolo per liquami.`n");
        }
	    $session['user']['charm']-=5;
        $session['user']['clean']+=5;
	    output("Inutile dire che ti inzozzi tutti gli abiti e che il tuo odore non va certo a favore del tuo fascino, ma forse riuscirai ad andare alle docce pubbliche prima che qualcuno si accorga della tua puzza.`n");        	
        addnews("`2".$session['user']['name']." è stat".($session['user']['sex']?"a":"o")."`2 vist".($session['user']['sex']?"a":"o")." volare in un vicolo fangoso per un eccesso di avarizia.");          
    }else{
    	output("Il `bButtafuori`b della Grotta, un enorme `#Gigante delle Tempeste`2 ti afferra per la collottola e, senza sforzo alcuno, ti solleva e ti fa uscire dal locale passando dalla porta posteriore.
    		Arrivati in un vicolo semibuio, ti lancia in aria facendoti cadere sopra un cumulo di rifiuti che per tua fortuna attutisce la tua rovinosa caduta.`n");
    	addnews("`2".$session['user']['name']."`2 è stat".($session['user']['sex']?"a":"o")." buttat".($session['user']['sex']?"a":"o")." fuori dalla Grotta dello `@Gnomo`2 per un eccesso di avarizia.");          
    }
    addnav("Torna a Castel Excalibur", "castelexcal.php");
}else{
    page_header("La Grotta dello Gnomo");
    output("`b`c`2La Grotta dello `@Gnomo`0`c`b`n`n");
    output("`2La Grotta dello `@Gnomo`2 offre il miglior cibo per `@Gnomi`2 della contea.`n `n");
    if ($session['user']['usedouthouse'] == 0 AND $session['user']['hunger'] > 50 ){
        output("Puoi placare i morsi della fame approffittando della tipica ospitalità degli `@Gnomi`2. `n");
        addnav("Grotta dello Gnomo");
        if ($canpay){
            addnav("`&Banchetto`3: (`^$cost`3 oro)", "grotto.php?op=feast");
            output("Preferisci fare un `&Banchetto`2 completo o ripiegare sulla famosissima `(Minestra di Gallette`2? La scelta è tua!`0`n`n");
        }else{
            output("`2Il `&Banchetto`2 completo costa `^$cost `2pezzi d'oro. Vista la tua grande disponibilità finanziaria del momento attuale, sembra che oggi dovrai accontentarti della `(Minestra di Gallette`2 o tornare più tardi con qualche spicciolo in più.");
        }
        addnav("`(Minestra`3:  (gratis)", "grotto.php?op=soup");
        addnav("Torna a Castel Excalibur", "castelexcal.php");
    }else{
		switch(e_rand(1,5)){
            case 1:
        		output("`2In questo momento La Grotta dello `@Gnomo`2 è chiusa per la notte. Dovrai aspettare fino a domani!");
                break;
            case 2:
                output("`2Mentre ti avvicini all'ingresso della Grotta dello `@Gnomo`2, ti rendi conto che ti è passata la fame.");
                break;
            case 3:
                output("`2Non ti senti in vena di mangiare cibo da `@Gnomo`2 per oggi!");
                break;
            case 4:
                output("`2Noti un cartello appeso all'ingresso ... `4`i`bChiuso per ispezione sanitaria`b`i`6");
                break;
            case 5:
                output("`2Noti un cartello appeso all'ingresso ... `4`i`bChiuso per mancanza di personale`b`i`6");
                break;    
            }
        output("`n`2Te ne vai per tornare un altro giorno`0");
        addnav("Torna a Castel Excalibur", "castelexcal.php");
    }
}
page_footer();

?>