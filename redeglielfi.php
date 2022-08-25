<?php
/*****************************************/
/* Il Re degli Elfi                      */
/* ---------------                       */
/* Written by Hugues                     */
/*(da un'idea di LadyRockiell)           */
/*****************************************/
require_once "common.php";

page_header("Daoine Siddhe");

if ($_GET['op'] == ""  ){
    output("`n`c`b`&Daoine Siddhe`0`b`c`n");
	$session['tentativo'] = 0;
	$session['switch'] = "";
    output("`2Dopo esserti inoltrat".($session['user']['sex']?"a":"o")." nella foresta per parecchie ore sbuchi in una grande radura erbosa al centro della quale si erge una collinetta 
    	la cui cima è dominata da un maestoso albero secolare. Stanco del viaggio decidi di accamparti ai piedi di quella collina ed inizi a costruire un rifugio di fortuna. ");
	output("`nUn vecchio rugoso ti si avvicina e con aria preoccupata mormora quasi balbettando : `n`0Figliol".($session['user']['sex']?"a":"o").", datemi retta, questo non è assolutamente 
		un posto sicuro per dormire. Seguitemi e venite con me in paese, sarò lieto di darvi io stesso ospitalità per la notte. ");
	output("Ma abbandonate questo posto, si narrano strane e terribili storie su quanto succede di notte ai piedi di quell'albero... Dovete sapere che 
		questa è una collina delle fate e che `^Finvarra`0 il Re degli `^Elfi`6 Daoine Siddhe `0vi tiene da innumerevoli anni la sua corte. ");
	output("Ascoltatemi vi prego se avete cara la vita.... dormire ai piedi di questa collina può essere molto, molto imprudente.`2`n`n");
	if ($session['user']['quest']>2 AND $session['user']['supervisor']=0) {
      	output("`2Le parole del vecchio ti hanno impaurito, decidi quindi di ascoltare il suo consiglio e ritornare così tra le sicure mura del villaggio. ");
    	addnav("Ritorna al Villaggio","village.php");
    }else{	
	    output("Cosa fare ? Ignorare le parole tremanti del vecchio o sfidare il pericolo ?`n");
   		addnav("Ti accampi ","redeglielfi.php?op=accampamento");
		addnav("Torna al Villaggio","village.php");
	}
	
}elseif ($_GET['op'] == "accampamento"  ){
	page_header("L'ingresso nell'Albero"); 
	output("`n`c`b`&L'ingresso nell'Albero`0`b`c`n");
	output("`2Guardi il vecchio con un sorriso impertinente : `#Vi ringrazio per la vostra ospitalità, ma io non sono superstizios".($session['user']['sex']?"a":"o")." e non ascolto le fantasticherie dei cantastorie.
		 Il posto mi piace e dormirò qui. Non preoccupatevi per la mia salute, sono capace di dormire con un occhio solo vegliando su me stess".($session['user']['sex']?"a":"o")." e non temo alcun nemico.`n ");
	output("`2Il vecchio scuote la testa: `0Siete testard".($session['user']['sex']?"a":"o").", purtroppo.. Vi auguro buona fortuna!`2 e si allontana velocemente.`n `n`2");
    output("Dopo qualche istante ti accorgi che sulla sommità della collina c'è un debole chiarore azzurrino, quasi indistinto nella luce argentea della luna. Incuriosito aggiri la collina per vedere di cosa si tratta. ");       
    output("Quando però raggiungi il fianco opposto noti un'apertura nel vecchio albero dalla quale si sprigiona un intenso bagliore azzurro. L'apertura penetra profondamente 
    	nella collina e da essa giunge il suono di canti in una lingua ignota, e risa e rumori di un fastoso banchetto... `n`n");

    output(" La corte di `^Finvarra`2! ");
    output("`n`nEntri nell'albero o torni al villaggio ? ");
    
    addnav("Entri nell'albero","redeglielfi.php?op=entri");
	addnav("Torna al Villaggio","village.php");
    
}elseif ($_GET['op'] == "entri"  ){     
    page_header("La Corte di Re Finvarra"); 
	output("`n`c`b`6La Corte di Re `^Finvarra`0`b`c`n");
	$session['user']['quest'] += 1;
    $session['user']['playerfights']--;
    output("`3Attraversi sale suntuose e ricche di arazzi mentre al tuo passaggio gli `^Elfi`3 tacciono e si fanno da parte. Ti guardi attorno meravigliat".($session['user']['sex']?"a":"o").". Una splendida reggia sotto la collina! 
    	Difficilmente qualcuno crederà alla tua avventura. ");
    output("Ad un tratto ti trovi davanti a `^Finvarra`3 in persona, l'anziano Re degli `^Elfi`3,  che ti fissa con un sorriso malizioso, indicandoti una sedia libera posta di fronte ad una splendida 
    	scacchiera ingioiellata.`n");
    $chessboard = array(bvg,bag,bbg,bcg,bdg,beg,bfg,bgg,bhg,b1g,rbn,nbb,bbn,qbb,kbn,bbb,nbn,rbb,b2g,pbb,pbn,pbb,pbn,pbb,pbn,pbb,pbn,b3g,vnn,vbb,vnn,vbb,vnn,vbb,vnn,vbb,b4g,vbb,vnn,vbb,vnn,vbb,vnn,vbb,vnn,b5g,vnn,vbb,vnn,vbb,vnn,vbb,vnn,vbb,b6g,vbb,vnn,vbb,vnn,vbb,vnn,vbb,vnn,b7g,pnn,pnb,pnn,pnb,pnn,pnb,pnn,pnb,b8g,rnb,nnn,bnb,qnn,rnb,bnn,nnb,rnn);
                    	
    $chessboardmap.="chess.jpg";
    $mapkey.="<img src=\"./images/chess/$chessboardmap\" title=\"\" alt=\"\" style=\"width: 40px; height: 40px;\">";
    output("`n");
    $mapkey2="";
    $mapkey="";
    for ($i=0;$i<81;$i++){
        $keymap=ltrim($chessboard[$i]);
        $chessboardmap=$keymap;
        $chessboardmap.="chess.jpg";
        $mapkey.="<img src=\"./images/chess/$chessboardmap\" title=\"\" alt=\"\" style=\"width: 40px; height: 40px;\">";
        if ($i==8 or $i==17 or $i==26 or $i==35 or $i==44 or $i==53 or $i==62 or $i==71 or $i==80){
            $mapkey="`n".$mapkey;
            $mapkey2=$mapkey.$mapkey2;
            $mapkey="";
        }
    }
    output($mapkey2,true);	
    	
    output("`n`n`#Allora, giovane guerrier".($session['user']['sex']?"a":"o").", ti va una partita a scacchi con me? `n`n`3Ti vengono in mente le parole del vecchio incontrato prima e un pò timoros".($session['user']['sex']?"a":"o")." rispondi al Re `^Elfo`3 : `0 Sua Maestà, quale è la posta in gioco? 
    	`n`3L'anziano sovrano ti scruta per qualche istante accarezzandosi il mento, poi scandendo lentamente le parole e osservandone l'effetto che fanno su di te, annuncia a gran voce ai presenti: `n`n");
    output("`6Io `^Finvarra`# Re degli `^Elfi `6Daoine Siddhe e ".$session['user']['name']." `6faremo una partita a scacchi, se ".($session['user']['sex']?"ella":"egli")." perderà, con essa perderà anche la vita. Se invece riuscirà a vincermi potrà portare via con se, prelevandole dal mio tesoro, 
    	tutte le `&gemme`6 che riuscirà a tenere nelle sue mani unite a coppa.`n ");
    output("`0Non credo di avere possibilità di scelta, non posso rifiutarmi vero?`3 Mormori sommessamente. `n`6No, non puoi rifiutare! Mi hai sfidato insolentemente accampandoti ai piedi della mia mia collina... 
    	No, decisamente non hai alcuna scelta. `3Replica l'`^Elfo`n `&Va bene potente sovrano, dato che non ho scelta, allora giochiamo pure! `n");
    
	
    if ($session['switch'] == "" AND $session['user']['superuser'] > 0){
     	output("`nSei un superutente, `\$`bDEVI`b `&scegliere la partita (1-31)`n");
        output("<form action='redeglielfi.php?op=continua' method='POST'><input name='match' value='0'><input type='submit' class='button' value='N° Partita (1-31)'>`n",true);
    	addnav("","redeglielfi.php?op=continua");
    }else{
		addnav("Gioca la partita","redeglielfi.php?op=continua");
	}
}elseif ($_GET['op'] == "continua"  ){    
    page_header("La Partita a Scacchi con il Re Elfo"); 
	output("`n`c`b`6La Partita a Scacchi con il Re `^Elfo`0`b`c`n`n");
    output("`3Dopo diverse e studiate mosse ti ritrovi a dover decidere la mossa finale, quella che determinerà l'esito della combattutissima partita.`nPensaci bene e fai la giusta mossa!! ....e... Buona Fortuna!! ");
    // Legenda
    // bvg,bag,bbg,bcg,bdg,beg,bfg,bgg,bhg,b1g,b2g,b3g,b4g,b5g,b6g,b7g,b8g  Bordi con lettere e numeri della scacchiera
    // K (Re) Q (Donna) R (Torre) B (Alfiere) N (Cavallo) P (Pedone) V (casella vuota)
    // b (bianco) n (nero) colore del pezzo o della casella se casella vuota 
    // b (bianco) n (nero) colore della casella
    // 
    
    if ($session['user']['superuser'] > 0){
    	$session['switch'] = $_POST['match'];
    } else {
    	$session['switch']= e_rand(1,34);
    }
           
	switch($session['switch']){
            case 0:
                //title: base
                $session['chessboard'] = array(bvg,bag,bbg,bcg,bdg,beg,bfg,bgg,bhg,b1g,vnn,vbb,vnn,vbb,vnn,vbb,vnn,vbb,b2g,vbb,vnn,vbb,vnn,vbb,vnn,vbb,vnn,b3g,vnn,vbb,vnn,vbb,vnn,vbb,vnn,vbb,b4g,vbb,vnn,vbb,vnn,vbb,vnn,vbb,vnn,b5g,vnn,vbb,vnn,vbb,vnn,vbb,vnn,vbb,b6g,vbb,vnn,vbb,vnn,vbb,vnn,vbb,vnn,b7g,vnn,vbb,vnn,vbb,vnn,vbb,vnn,vbb,b8g,vbb,vnn,vbb,vnn,vbb,vnn,vbb,vnn);
                break;
            case 1:
                //title: uno
                $session['chessboard'] = array(bvg,bag,bbg,bcg,bdg,beg,bfg,bgg,bhg,b1g,vnn,vbb,vnn,vbb,bbn,vbb,vnn,vbb,b2g,vbb,vnn,vbb,rbn,vbb,vnn,vbb,vnn,b3g,vnn,kbb,vnn,vbb,vnn,vbb,vnn,vbb,b4g,vbb,vnn,vbb,vnn,vbb,vnn,vbb,vnn,b5g,vnn,vbb,vnn,vbb,vnn,vbb,vnn,vbb,b6g,vbb,vnn,knb,pnn,vbb,vnn,vbb,vnn,b7g,qbn,vbb,vnn,vbb,vnn,vbb,vnn,vbb,b8g,vbb,vnn,vbb,vnn,vbb,vnn,vbb,vnn);
                $session['mossa'] = "torre" ;
                break;
            case 2:
                //title: due
                $session['chessboard'] = array(bvg,bag,bbg,bcg,bdg,beg,bfg,bgg,bhg,b1g,vnn,vbb,vnn,vbb,bbn,vbb,vnn,vbb,b2g,vbb,vnn,vbb,vnn,vbb,vnn,vbb,vnn,b3g,vnn,vbb,vnn,vbb,vnn,nnb,vnn,vbb,b4g,rbb,vnn,vbb,vnn,vbb,vnn,vbb,vnn,b5g,vnn,vbb,pnn,pnb,vnn,vbb,knn,pnb,b6g,rbb,vnn,vbb,nbn,vbb,vnn,vbb,vnn,b7g,kbn,vbb,vnn,vbb,vnn,qbb,qnn,pnb,b8g,bnb,vnn,bbb,vnn,vbb,vnn,rnb,vnn);
                $session['mossa'] = "torre" ;
                break;
            case 3:
            	//title: tre
                $session['chessboard'] = array(bvg,bag,bbg,bcg,bdg,beg,bfg,bgg,bhg,b1g,vnn,bbb,vnn,vbb,vnn,vbb,vnn,vbb,b2g,vbb,vnn,vbb,vnn,vbb,rnn,vbb,vnn,b3g,knn,vbb,vnn,bnb,rbn,vbb,vnn,vbb,b4g,vbb,vnn,pnb,vnn,vbb,vnn,vbb,vnn,b5g,vnn,qbb,vnn,vbb,vnn,vbb,vnn,vbb,b6g,vbb,vnn,vbb,vnn,vbb,vnn,vbb,bbn,b7g,vnn,vbb,vnn,vbb,vnn,vbb,vnn,vbb,b8g,vbb,vnn,kbb,vnn,vbb,vnn,vbb,vnn);
                $session['mossa'] = "torre" ;
                break;
            case 4:
                //title: quattro
                $session['chessboard'] = array(bvg,bag,bbg,bcg,bdg,beg,bfg,bgg,bhg,b1g,vnn,vbb,vnn,vbb,vnn,rnb,vnn,vbb,b2g,vbb,vnn,vbb,vnn,bnb,vnn,vbb,nbn,b3g,rbn,vbb,vnn,vbb,vnn,pbb,vnn,vbb,b4g,vbb,vnn,vbb,pnn,pbb,vnn,rbb,vnn,b5g,vnn,vbb,vnn,vbb,vnn,vbb,vnn,vbb,b6g,vbb,vnn,vbb,vnn,vbb,vnn,vbb,knn,b7g,vnn,vbb,vnn,vbb,vnn,bbb,vnn,vbb,b8g,vbb,vnn,vbb,vnn,vbb,vnn,vbb,kbn);
                $session['mossa'] = "pedone" ;
                break;
            case 5:
                //title: cinque
                $session['chessboard'] = array(bvg,bag,bbg,bcg,bdg,beg,bfg,bgg,bhg,b1g,vnn,vbb,vnn,vbb,vnn,vbb,vnn,vbb,b2g,vbb,vnn,vbb,vnn,vbb,vnn,vbb,vnn,b3g,vnn,bbb,vnn,vbb,pbn,vbb,vnn,vbb,b4g,vbb,vnn,vbb,vnn,pnb,pbn,vbb,vnn,b5g,vnn,qbb,vnn,vbb,vnn,vbb,vnn,vbb,b6g,vbb,vnn,vbb,knn,vbb,vnn,vbb,vnn,b7g,rnn,vbb,pnn,vbb,vnn,vbb,vnn,vbb,b8g,bnb,vnn,vbb,kbn,vbb,vnn,vbb,vnn);
                $session['mossa'] = "donna" ;
                break;
            case 6:
                //title: sei
                $session['chessboard'] = array(bvg,bag,bbg,bcg,bdg,beg,bfg,bgg,bhg,b1g,vnn,vbb,vnn,vbb,vnn,vbb,vnn,vbb,b2g,kbb,vnn,vbb,vnn,vbb,vnn,vbb,vnn,b3g,vnn,vbb,vnn,vbb,vnn,vbb,vnn,vbb,b4g,vbb,vnn,vbb,pbn,vbb,vnn,vbb,vnn,b5g,vnn,knb,vnn,vbb,vnn,bbb,qbn,vbb,b6g,vbb,vnn,nbb,vnn,vbb,vnn,vbb,vnn,b7g,bbn,vbb,vnn,vbb,vnn,vbb,vnn,vbb,b8g,vbb,vnn,vbb,vnn,vbb,vnn,vbb,vnn);
                $session['mossa'] = "donna" ;
                break;
            case 7:
                //title: sette
                $session['chessboard'] = array(bvg,bag,bbg,bcg,bdg,beg,bfg,bgg,bhg,b1g,vnn,vbb,vnn,vbb,vnn,vbb,vnn,vbb,b2g,vbb,vnn,vbb,pbn,vbb,vnn,vbb,vnn,b3g,vnn,vbb,vnn,pnb,vnn,vbb,vnn,bbb,b4g,vbb,nbn,vbb,vnn,knb,vnn,vbb,vnn,b5g,vnn,vbb,vnn,rnb,bbn,rbb,vnn,vbb,b6g,vbb,vnn,vbb,vnn,vbb,vnn,vbb,vnn,b7g,vnn,vbb,vnn,vbb,nnn,vbb,vnn,kbb,b8g,vbb,vnn,nnb,qbn,vbb,vnn,vbb,vnn);
                $session['mossa'] = "donna" ;
                break;
            case 8:
                //title: otto
                $session['chessboard'] = array(bvg,bag,bbg,bcg,bdg,beg,bfg,bgg,bhg,b1g,vnn,vbb,vnn,bbb,vnn,vbb,bbn,vbb,b2g,vbb,vnn,vbb,nnn,vbb,vnn,vbb,vnn,b3g,rbn,nnb,vnn,vbb,vnn,vbb,vnn,vbb,b4g,vbb,vnn,vbb,vnn,knb,vnn,pnb,vnn,b5g,vnn,vbb,vnn,vbb,vnn,bnb,rbn,vbb,b6g,vbb,vnn,vbb,qbn,vbb,vnn,vbb,vnn,b7g,vnn,vbb,vnn,vbb,kbn,vbb,vnn,vbb,b8g,vbb,vnn,vbb,vnn,vbb,vnn,vbb,vnn);
                $session['mossa'] = "donna" ;
                break;
            case 9:
                //title: nove
                $session['chessboard'] = array(bvg,bag,bbg,bcg,bdg,beg,bfg,bgg,bhg,b1g,vnn,vbb,vnn,vbb,vnn,vbb,kbn,vbb,b2g,vbb,pbn,vbb,vnn,vbb,vnn,nnb,vnn,b3g,vnn,vbb,nbn,vbb,vnn,vbb,vnn,vbb,b4g,vbb,vnn,vbb,knn,vbb,vnn,vbb,vnn,b5g,vnn,vbb,vnn,vbb,nbn,vbb,vnn,vbb,b6g,vbb,vnn,vbb,vnn,bnb,bbn,vbb,vnn,b7g,vnn,vbb,vnn,vbb,vnn,vbb,vnn,vbb,b8g,vbb,vnn,vbb,vnn,rbb,qbn,vbb,vnn);
                $session['mossa'] = "alfiere" ;
                break;
            case 10:
                //title: dieci
                $session['chessboard'] = array(bvg,bag,bbg,bcg,bdg,beg,bfg,bgg,bhg,b1g,knn,vbb,vnn,vbb,vnn,vbb,vnn,kbb,b2g,vbb,vnn,vbb,vnn,vbb,vnn,vbb,rbn,b3g,vnn,vbb,vnn,vbb,vnn,vbb,vnn,rbb,b4g,nnb,vnn,pnb,pnn,vbb,vnn,vbb,vnn,b5g,vnn,vbb,vnn,vbb,vnn,vbb,vnn,vbb,b6g,vbb,qbn,vbb,bnn,vbb,vnn,vbb,vnn,b7g,vnn,vbb,vnn,vbb,vnn,vbb,vnn,vbb,b8g,vbb,vnn,vbb,vnn,vbb,vnn,vbb,vnn);
                $session['mossa'] = "torre" ;
                break;
            case 11:
                //title: undici
                $session['chessboard'] = array(bvg,bag,bbg,bcg,bdg,beg,bfg,bgg,bhg,b1g,vnn,vbb,vnn,vbb,vnn,vbb,vnn,vbb,b2g,vbb,vnn,vbb,vnn,vbb,vnn,vbb,vnn,b3g,vnn,bbb,vnn,vbb,vnn,qbb,vnn,vbb,b4g,vbb,vnn,rbb,vnn,nbb,pnn,vbb,vnn,b5g,vnn,vbb,vnn,vbb,vnn,knb,pbn,vbb,b6g,vbb,vnn,vbb,pbn,vbb,vnn,vbb,vnn,b7g,vnn,vbb,vnn,vbb,pnn,vbb,vnn,kbb,b8g,vbb,vnn,vbb,vnn,vbb,vnn,vbb,vnn);
                $session['mossa'] = "cavallo" ;
                break;
            case 12:
                //title: dodici
                $session['chessboard'] = array(bvg,bag,bbg,bcg,bdg,beg,bfg,bgg,bhg,b1g,vnn,vbb,vnn,vbb,vnn,vbb,vnn,vbb,b2g,vbb,vnn,vbb,vnn,vbb,vnn,vbb,vnn,b3g,vnn,vbb,vnn,vbb,vnn,vbb,vnn,vbb,b4g,vbb,vnn,vbb,vnn,vbb,vnn,vbb,vnn,b5g,vnn,vbb,vnn,qbb,pnn,vbb,vnn,vbb,b6g,nbb,vnn,vbb,vnn,rnb,vnn,pnb,vnn,b7g,pnn,bbb,vnn,pnb,pnn,vbb,kbn,vbb,b8g,rnb,vnn,vbb,vnn,knb,vnn,vbb,vnn);
                $session['mossa'] = "alfiere" ;
                break;
            case 13:
                //title: tredici
                $session['chessboard'] = array(bvg,bag,bbg,bcg,bdg,beg,bfg,bgg,bhg,b1g,vnn,vbb,vnn,vbb,vnn,vbb,vnn,vbb,b2g,vbb,vnn,rbb,vnn,pbb,nbn,vbb,vnn,b3g,vnn,vbb,vnn,vbb,vnn,vbb,vnn,vbb,b4g,vbb,vnn,vbb,pnn,vbb,pnn,qbb,vnn,b5g,rbn,vbb,vnn,vbb,vnn,vbb,vnn,vbb,b6g,vbb,vnn,vbb,knn,rnb,pnn,vbb,vnn,b7g,vnn,vbb,vnn,vbb,vnn,pbb,vnn,vbb,b8g,vbb,vnn,vbb,vnn,vbb,kbn,vbb,vnn);
                $session['mossa'] = "pedone" ;
                break;
            case 14:
                //title: quattordici
                $session['chessboard'] = array(bvg,bag,bbg,bcg,bdg,beg,bfg,bgg,bhg,b1g,vnn,vbb,vnn,vbb,vnn,vbb,vnn,vbb,b2g,pbb,vnn,nbb,vnn,kbb,vnn,vbb,vnn,b3g,vnn,vbb,nbn,vbb,vnn,rbb,vnn,vbb,b4g,vbb,vnn,knb,vnn,vbb,vnn,vbb,vnn,b5g,vnn,pbb,vnn,vbb,vnn,vbb,vnn,vbb,b6g,vbb,vnn,vbb,vnn,vbb,vnn,vbb,vnn,b7g,vnn,vbb,pnn,vbb,pnn,vbb,qbn,vbb,b8g,vbb,vnn,vbb,vnn,vbb,vnn,vbb,bnn);
                $session['mossa'] = "torre" ;
                break;
            case 15:
                //title: quindici
                $session['chessboard'] = array(bvg,bag,bbg,bcg,bdg,beg,bfg,bgg,bhg,b1g,vnn,vbb,vnn,vbb,vnn,vbb,vnn,vbb,b2g,vbb,vnn,vbb,vnn,vbb,vnn,vbb,vnn,b3g,vnn,vbb,vnn,vbb,vnn,vbb,vnn,vbb,b4g,vbb,qbn,vbb,nbn,vbb,vnn,vbb,vnn,b5g,vnn,vbb,vnn,bbb,vnn,vbb,vnn,vbb,b6g,pnb,vnn,vbb,vnn,vbb,vnn,vbb,vnn,b7g,rbn,pbb,knn,pnb,pbn,vbb,vnn,vbb,b8g,kbb,nnn,rnb,vnn,vbb,vnn,vbb,vnn);
                $session['mossa'] = "cavallo" ;
                break;
            case 16:
                //title: sedici
                $session['chessboard'] = array(bvg,bag,bbg,bcg,bdg,beg,bfg,bgg,bhg,b1g,vnn,vbb,vnn,vbb,vnn,vbb,vnn,vbb,b2g,vbb,vnn,vbb,vnn,vbb,vnn,vbb,vnn,b3g,vnn,vbb,vnn,vbb,vnn,vbb,vnn,vbb,b4g,vbb,vnn,vbb,vnn,vbb,vnn,vbb,vnn,b5g,vnn,vbb,vnn,pnb,vnn,vbb,vnn,kbb,b6g,vbb,vnn,vbb,bbn,vbb,vnn,pbb,vnn,b7g,vnn,vbb,vnn,qbb,vnn,pnb,knn,vbb,b8g,qnb,vnn,vbb,vnn,bbb,vnn,bnb,bnn);
                $session['mossa'] = "pedone" ;
                break;
            case 17:
                //title: diciassette
                $session['chessboard'] = array(bvg,bag,bbg,bcg,bdg,beg,bfg,bgg,bhg,b1g,vnn,vbb,vnn,bnb,vnn,vbb,vnn,qbb,b2g,vbb,kbn,vbb,knn,pnb,vnn,vbb,vnn,b3g,vnn,vbb,vnn,vbb,vnn,vbb,vnn,vbb,b4g,vbb,nbn,vbb,vnn,vbb,vnn,bbb,vnn,b5g,vnn,vbb,vnn,vbb,vnn,nbb,vnn,vbb,b6g,vbb,vnn,vbb,vnn,vbb,vnn,vbb,vnn,b7g,vnn,vbb,vnn,vbb,vnn,vbb,vnn,vbb,b8g,vbb,vnn,vbb,vnn,vbb,vnn,vbb,vnn);
                $session['mossa'] = "donna" ;
                break;
            case 18:
                //title: diciotto
                $session['chessboard'] = array(bvg,bag,bbg,bcg,bdg,beg,bfg,bgg,bhg,b1g,vnn,vbb,vnn,vbb,vnn,vbb,vnn,kbb,b2g,vbb,bnn,vbb,vnn,vbb,vnn,vbb,pbn,b3g,vnn,vbb,pnn,vbb,vnn,vbb,vnn,vbb,b4g,pbb,vnn,nnb,vnn,vbb,vnn,pnb,nbn,b5g,vnn,vbb,vnn,bbb,vnn,pbb,vnn,vbb,b6g,pnb,pnn,vbb,pnn,vbb,rnn,vbb,pnn,b7g,vnn,vbb,rbn,vbb,vnn,vbb,vnn,vbb,b8g,vbb,vnn,vbb,vnn,vbb,vnn,vbb,knn);
                $session['mossa'] = "cavallo" ;
                break;
            case 19:
                //title: diciannove
                $session['chessboard'] = array(bvg,bag,bbg,bcg,bdg,beg,bfg,bgg,bhg,b1g,rbn,vbb,bbn,vbb,vnn,rbb,kbn,vbb,b2g,pbb,pbn,pbb,vnn,vbb,vnn,pbb,pbn,b3g,vnn,vbb,nbn,vbb,vnn,nbb,vnn,vbb,b4g,vbb,vnn,vbb,vnn,vbb,vnn,nnb,vnn,b5g,qnn,vbb,pbn,qbb,vnn,bnb,vnn,vbb,b6g,vbb,vnn,vbb,vnn,vbb,knn,pnb,pnn,b7g,pnn,pnb,vnn,vbb,pnn,vbb,bnn,vbb,b8g,rnb,nnn,vbb,rnn,vbb,vnn,vbb,vnn);
                $session['mossa'] = "cavallo" ;
                break;
            case 20:
                //title: venti 4012
                $session['chessboard'] = array(bvg,bag,bbg,bcg,bdg,beg,bfg,bgg,bhg,b1g,vnn,vbb,kbn,rbb,vnn,vbb,vnn,vbb,b2g,pbb,pbn,pbb,vnn,vbb,vnn,vbb,pbn,b3g,vnn,bbb,vnn,vbb,vnn,vbb,vnn,vbb,b4g,nbb,pnn,vbb,vnn,pbb,vnn,vbb,vnn,b5g,vnn,vbb,vnn,vbb,nnn,vbb,pbn,vbb,b6g,pnb,vnn,vbb,pnn,qbb,vnn,pnb,rbn,b7g,vnn,vbb,qnn,vbb,vnn,vbb,knn,pnb,b8g,vbb,rnn,vbb,vnn,nnb,rnn,vbb,vnn);
                $session['mossa'] = "torre" ;
                break;
            case 21:
                //title: ventuno 4046
                $session['chessboard'] = array(bvg,bag,bbg,bcg,bdg,beg,bfg,bgg,bhg,b1g,rnn,vbb,vnn,vbb,vnn,vbb,vnn,vbb,b2g,vbb,vnn,vbb,vnn,vbb,vnn,vbb,pbn,b3g,vnn,vbb,vnn,vbb,vnn,vbb,vnn,vbb,b4g,rnb,vnn,vbb,vnn,pnb,vnn,pbb,vnn,b5g,vnn,pbb,vnn,qbb,vnn,vbb,vnn,vbb,b6g,pbb,vnn,vbb,vnn,vbb,vnn,knb,vnn,b7g,vnn,vbb,vnn,vbb,kbn,pnb,vnn,vbb,b8g,vbb,vnn,vbb,vnn,vbb,vnn,vbb,vnn);
                $session['mossa'] = "re" ;
                break;
            case 22:
                //title: ventidue 4052
                $session['chessboard'] = array(bvg,bag,bbg,bcg,bdg,beg,bfg,bgg,bhg,b1g,vnn,vbb,vnn,vbb,vnn,qbb,kbn,vbb,b2g,vbb,vnn,vbb,vnn,pnb,vnn,pbb,vnn,b3g,vnn,vbb,vnn,pbb,vnn,vbb,pbn,vbb,b4g,pbb,vnn,vbb,pnn,vbb,vnn,vbb,vnn,b5g,vnn,vbb,vnn,vbb,nnn,vbb,vnn,qnb,b6g,vbb,vnn,rbb,nbn,vbb,vnn,knb,vnn,b7g,vnn,vbb,vnn,vbb,vnn,vbb,vnn,pnb,b8g,vbb,vnn,vbb,rnn,vbb,vnn,vbb,vnn);
                $session['mossa'] = "cavallo" ;
                break;
            case 23:
                //title: ventitre 4061
                $session['chessboard'] = array(bvg,bag,bbg,bcg,bdg,beg,bfg,bgg,bhg,b1g,vnn,vbb,vnn,vbb,vnn,vbb,vnn,vbb,b2g,vbb,vnn,vbb,vnn,vbb,vnn,vbb,vnn,b3g,rnn,vbb,vnn,vbb,vnn,vbb,vnn,vbb,b4g,vbb,pbn,vbb,vnn,vbb,pbn,kbb,vnn,b5g,vnn,vbb,bbn,vbb,vnn,vbb,vnn,vbb,b6g,pnb,vnn,vbb,vnn,vbb,vnn,knb,vnn,b7g,vnn,rbb,vnn,vbb,vnn,vbb,vnn,vbb,b8g,vbb,vnn,vbb,nnn,vbb,vnn,vbb,vnn);
                $session['mossa'] = "pedone" ;
                break;
            case 24:
                //title: ventiquattro 4104
                $session['chessboard'] = array(bvg,bag,bbg,bcg,bdg,beg,bfg,bgg,bhg,b1g,vnn,vbb,vnn,vbb,rbn,rbb,kbn,vbb,b2g,pbb,vnn,vbb,vnn,vbb,pbn,pbb,pbn,b3g,vnn,vbb,pbn,vbb,vnn,vbb,vnn,vbb,b4g,vbb,vnn,vbb,vnn,vbb,vnn,vbb,vnn,b5g,vnn,vbb,vnn,vbb,vnn,qnb,bbn,vbb,b6g,vbb,vnn,nnb,vnn,vbb,vnn,pnb,qbn,b7g,pnn,pnb,vnn,pnb,vnn,pnb,vnn,pnb,b8g,rnb,vnn,bnb,vnn,vbb,rnn,knb,vnn);
                $session['mossa'] = "donna" ;
                break;
            case 25:
                //title: venticinque 4123
                $session['chessboard'] = array(bvg,bag,bbg,bcg,bdg,beg,bfg,bgg,bhg,b1g,rbn,vbb,vnn,vbb,vnn,vbb,kbn,vbb,b2g,vbb,vnn,vbb,vnn,vbb,pbn,pbb,pbn,b3g,pbn,vbb,vnn,vbb,vnn,vbb,rbn,vbb,b4g,nbb,vnn,pbb,pnn,vbb,vnn,vbb,vnn,b5g,bnn,vbb,vnn,vbb,pnn,vbb,vnn,qbb,b6g,vbb,vnn,bnb,pbn,vbb,pnn,pnb,bbn,b7g,pnn,vbb,vnn,nnb,vnn,rnb,vnn,vbb,b8g,rnb,vnn,vbb,vnn,qnb,vnn,vbb,knn);
                $session['mossa'] = "alfiere" ;
                break;
            case 26:
                //title: ventisei 4161
                $session['chessboard'] = array(bvg,bag,bbg,bcg,bdg,beg,bfg,bgg,bhg,b1g,vnn,vbb,vnn,vbb,vnn,vbb,kbn,vbb,b2g,pbb,pbn,vbb,vnn,vbb,pbn,pbb,vnn,b3g,vnn,vbb,vnn,vbb,vnn,vbb,rbn,pbb,b4g,vbb,vnn,vbb,vnn,vbb,vnn,vbb,vnn,b5g,vnn,vbb,vnn,pnb,pbn,vbb,vnn,vbb,b6g,vbb,qnn,vbb,vnn,bnb,qbn,pnb,pnn,b7g,pnn,pnb,vnn,vbb,vnn,nbb,vnn,knb,b8g,vbb,vnn,vbb,vnn,vbb,vnn,rnb,vnn);
                $session['mossa'] = "torre" ;
                break;
            case 27:
                //titolo: ventisette 4171	
                $session['chessboard'] = array(bvg,bag,bbg,bcg,bdg,beg,bfg,bgg,bhg,b1g,vnn,vbb,vnn,rbb,vnn,rbb,kbn,vbb,b2g,pbb,pbn,vbb,vnn,vbb,pbn,pbb,pbn,b3g,vnn,vbb,vnn,vbb,pbn,vbb,vnn,vbb,b4g,vbb,vnn,vbb,vnn,vbb,nbn,vbb,vnn,b5g,vnn,vbb,vnn,vbb,vnn,qbb,nbn,vbb,b6g,vbb,qnn,vbb,bnn,vbb,vnn,vbb,vnn,b7g,pnn,pnb,vnn,rnb,vnn,pnb,pnn,vbb,b8g,rnb,nnn,vbb,vnn,vbb,vnn,knb,vnn);
                $session['mossa'] = "donna" ;
                break;
            case 28:
                //titolo: ventotto 4193
                $session['chessboard'] = array(bvg,bag,bbg,bcg,bdg,beg,bfg,bgg,bhg,b1g,vnn,vbb,kbn,rbb,vnn,bbb,vnn,rbb,b2g,pbb,pbn,vbb,qbn,vbb,vnn,vbb,vnn,b3g,vnn,vbb,nbn,vbb,bbn,pbb,nbn,vbb,b4g,vbb,vnn,pbb,pbn,pbb,vnn,vbb,vnn,b5g,vnn,vbb,vnn,vbb,vnn,vbb,pbn,vbb,b6g,pnb,vnn,pnb,pnn,vbb,vnn,pnb,vnn,b7g,vnn,pnb,vnn,nnb,pnn,vbb,bnn,pnb,b8g,rnb,vnn,bnb,qnn,nnb,vnn,rnb,knn);
                $session['mossa'] = "torre" ;
                break;
            case 29:
                //titolo: ventinove 4262
                $session['chessboard'] = array(bvg,bag,bbg,bcg,bdg,beg,bfg,bgg,bhg,b1g,vnn,vbb,vnn,vbb,vnn,rbb,vnn,kbb,b2g,pbb,pbn,vbb,vnn,vbb,vnn,pbb,pbn,b3g,vnn,nbb,pbn,vbb,vnn,vbb,vnn,vbb,b4g,vbb,vnn,vbb,vnn,vbb,vnn,vbb,vnn,b5g,vnn,vbb,bnn,pnb,pbn,vbb,vnn,qbb,b6g,pnb,vnn,vbb,vnn,pnb,vnn,vbb,bbn,b7g,vnn,pnb,qnn,bnb,vnn,bbb,vnn,pnb,b8g,rnb,vnn,vbb,vnn,vbb,rnn,vbb,knn);
                $session['mossa'] = "alfiere" ;
                break;
            case 30:
                //titolo: trenta 4283
                $session['chessboard'] = array(bvg,bag,bbg,bcg,bdg,beg,bfg,bgg,bhg,b1g,vnn,vbb,vnn,vbb,vnn,vbb,vnn,vbb,b2g,bbb,pbn,vbb,vnn,vbb,pbn,kbb,vnn,b3g,pbn,vbb,nbn,vbb,vnn,pbb,qbn,vbb,b4g,vbb,vnn,vbb,vnn,vbb,vnn,vbb,vnn,b5g,vnn,vbb,vnn,vbb,pnn,vbb,vnn,vbb,b6g,vbb,pnn,vbb,vnn,vbb,vnn,pbb,qnn,b7g,pnn,vbb,vnn,vbb,vnn,pnb,vnn,vbb,b8g,bnb,vnn,vbb,vnn,vbb,rnn,knb,vnn);
                $session['mossa'] = "pedone" ;
                break;
            case 31:
                //titolo: trentuno 4361
                $session['chessboard'] = array(bvg,bag,bbg,bcg,bdg,beg,bfg,bgg,bhg,b1g,vnn,vbb,qbn,vbb,vnn,vbb,vnn,rbb,b2g,vbb,vnn,vbb,vnn,vbb,pbn,kbb,vnn,b3g,vnn,vbb,bbn,vbb,vnn,vbb,pbn,vbb,b4g,vbb,pbn,pbb,vnn,pnb,pbn,vbb,vnn,b5g,pbn,vbb,vnn,pbb,vnn,pnb,vnn,vbb,b6g,pnb,vnn,pnb,pnn,vbb,vnn,bbb,vnn,b7g,vnn,pnb,nnn,nnb,qnn,vbb,bnn,rbb,b8g,vbb,rnn,vbb,vnn,vbb,rnn,knb,vnn);
                $session['mossa'] = "torre" ;
                break;
            case 32:
                //titolo: trentadue 4386
                $session['chessboard'] = array(bvg,bag,bbg,bcg,bdg,beg,bfg,bgg,bhg,b1g,vnn,vbb,vnn,rbb,rbn,vbb,kbn,vbb,b2g,vbb,vnn,pbb,vnn,vbb,pbn,pbb,pbn,b3g,vnn,vbb,vnn,vbb,vnn,vbb,vnn,qbb,b4g,vbb,bnn,qnb,vnn,vbb,vnn,vbb,vnn,b5g,vnn,vbb,vnn,vbb,bbn,nbb,vnn,vbb,b6g,pbb,pnn,vbb,vnn,vbb,vnn,nnb,vnn,b7g,pnn,vbb,vnn,vbb,vnn,pnb,pnn,pnb,b8g,rnb,vnn,vbb,vnn,vbb,rnn,knb,vnn);
                $session['mossa'] = "donna" ;
                break;
            case 33:
                //titolo: trentatre 4429
                $session['chessboard'] = array(bvg,bag,bbg,bcg,bdg,beg,bfg,bgg,bhg,b1g,rbn,vbb,vnn,vbb,vnn,vbb,kbn,vbb,b2g,pbb,vnn,rnb,vnn,qnb,pbn,pbb,vnn,b3g,vnn,pbb,vnn,vbb,vnn,vbb,rbn,pbb,b4g,vbb,vnn,vbb,pbn,vbb,vnn,vbb,vnn,b5g,vnn,vbb,vnn,pnb,vnn,pnb,nbn,vbb,b6g,vbb,vnn,vbb,vnn,vbb,nnn,vbb,qbn,b7g,pnn,pnb,vnn,nnb,vnn,pnb,vnn,pnb,b8g,vbb,vnn,vbb,vnn,vbb,rnn,vbb,knn);
                $session['mossa'] = "donna" ;
                break;
            case 34:
                //titolo: trentaquattro 4461
                $session['chessboard'] = array(bvg,bag,bbg,bcg,bdg,beg,bfg,bgg,bhg,b1g,vnn,vbb,vnn,vbb,vnn,vbb,kbn,vbb,b2g,vbb,pbn,vbb,vnn,vbb,pbn,pbb,vnn,b3g,pbn,vbb,pbn,vbb,vnn,vbb,vnn,vbb,b4g,pnb,vnn,bbb,qbn,vbb,pnn,vbb,vnn,b5g,vnn,vbb,vnn,vbb,vnn,vbb,qnn,vbb,b6g,vbb,vnn,vbb,vnn,vbb,vnn,pnb,vnn,b7g,vnn,vbb,vnn,rbb,nnn,pnb,vnn,vbb,b8g,vbb,rnn,vbb,vnn,vbb,knn,vbb,vnn);
                $session['mossa'] = "donna" ;
                break;
            case 35:
                //titolo: trentacinque
                $session['chessboard'] = array(bvg,bag,bbg,bcg,bdg,beg,bfg,bgg,bhg,b1g,vnn,vbb,vnn,vbb,vnn,vbb,vnn,vbb,b2g,vbb,vnn,vbb,vnn,vbb,vnn,vbb,vnn,b3g,vnn,vbb,vnn,vbb,vnn,vbb,vnn,vbb,b4g,vbb,vnn,vbb,vnn,vbb,vnn,vbb,vnn,b5g,vnn,vbb,vnn,vbb,vnn,vbb,vnn,vbb,b6g,vbb,vnn,vbb,vnn,vbb,vnn,vbb,vnn,b7g,vnn,vbb,vnn,vbb,vnn,vbb,vnn,vbb,b8g,vbb,vnn,vbb,vnn,vbb,vnn,vbb,vnn);
                $session['mossa'] = "alfiere" ;
                break;
            case 36:
                //titolo: il cerchio2
                $session['chessboard'] = array(bvg,bag,bbg,bcg,bdg,beg,bfg,bgg,bhg,b1g,vnn,vbb,vnn,vbb,vnn,vbb,vnn,vbb,b2g,vbb,vnn,vbb,vnn,vbb,vnn,vbb,vnn,b3g,vnn,vbb,vnn,vbb,vnn,vbb,vnn,vbb,b4g,vbb,vnn,vbb,vnn,vbb,vnn,vbb,vnn,b5g,vnn,vbb,vnn,vbb,vnn,vbb,vnn,vbb,b6g,vbb,vnn,vbb,vnn,vbb,vnn,vbb,vnn,b7g,vnn,vbb,vnn,vbb,vnn,vbb,vnn,vbb,b8g,vbb,vnn,vbb,vnn,vbb,vnn,vbb,vnn);
                $session['mossa'] = "alfiere" ;
                break;
            case 37:
                //titolo: Attenzione
                $session['chessboard'] = array(bvg,bag,bbg,bcg,bdg,beg,bfg,bgg,bhg,b1g,vnn,vbb,vnn,vbb,vnn,vbb,vnn,vbb,b2g,vbb,vnn,vbb,vnn,vbb,vnn,vbb,vnn,b3g,vnn,vbb,vnn,vbb,vnn,vbb,vnn,vbb,b4g,vbb,vnn,vbb,vnn,vbb,vnn,vbb,vnn,b5g,vnn,vbb,vnn,vbb,vnn,vbb,vnn,vbb,b6g,vbb,vnn,vbb,vnn,vbb,vnn,vbb,vnn,b7g,vnn,vbb,vnn,vbb,vnn,vbb,vnn,vbb,b8g,vbb,vnn,vbb,vnn,vbb,vnn,vbb,vnn);
                $session['mossa'] = "alfiere" ;
                break;
            case 38:
                //titolo: Spirale Maledetta
                $session['chessboard'] = array(bvg,bag,bbg,bcg,bdg,beg,bfg,bgg,bhg,b1g,vnn,vbb,vnn,vbb,vnn,vbb,vnn,vbb,b2g,vbb,vnn,vbb,vnn,vbb,vnn,vbb,vnn,b3g,vnn,vbb,vnn,vbb,vnn,vbb,vnn,vbb,b4g,vbb,vnn,vbb,vnn,vbb,vnn,vbb,vnn,b5g,vnn,vbb,vnn,vbb,vnn,vbb,vnn,vbb,b6g,vbb,vnn,vbb,vnn,vbb,vnn,vbb,vnn,b7g,vnn,vbb,vnn,vbb,vnn,vbb,vnn,vbb,b8g,vbb,vnn,vbb,vnn,vbb,vnn,vbb,vnn);
                $session['mossa'] = "alfiere" ;
                break;
            case 39:
                //titolo: Sei fortunato ?
                $session['chessboard'] = array(bvg,bag,bbg,bcg,bdg,beg,bfg,bgg,bhg,b1g,vnn,vbb,vnn,vbb,vnn,vbb,vnn,vbb,b2g,vbb,vnn,vbb,vnn,vbb,vnn,vbb,vnn,b3g,vnn,vbb,vnn,vbb,vnn,vbb,vnn,vbb,b4g,vbb,vnn,vbb,vnn,vbb,vnn,vbb,vnn,b5g,vnn,vbb,vnn,vbb,vnn,vbb,vnn,vbb,b6g,vbb,vnn,vbb,vnn,vbb,vnn,vbb,vnn,b7g,vnn,vbb,vnn,vbb,vnn,vbb,vnn,vbb,b8g,vbb,vnn,vbb,vnn,vbb,vnn,vbb,vnn);
                $session['mossa'] = "alfiere" ;
                break;
            case 40:
                //titolo: le tre rose bianche
                $session['chessboard'] = array(bvg,bag,bbg,bcg,bdg,beg,bfg,bgg,bhg,b1g,vnn,vbb,vnn,vbb,vnn,vbb,vnn,vbb,b2g,vbb,vnn,vbb,vnn,vbb,vnn,vbb,vnn,b3g,vnn,vbb,vnn,vbb,vnn,vbb,vnn,vbb,b4g,vbb,vnn,vbb,vnn,vbb,vnn,vbb,vnn,b5g,vnn,vbb,vnn,vbb,vnn,vbb,vnn,vbb,b6g,vbb,vnn,vbb,vnn,vbb,vnn,vbb,vnn,b7g,vnn,vbb,vnn,vbb,vnn,vbb,vnn,vbb,b8g,vbb,vnn,vbb,vnn,vbb,vnn,vbb,vnn);
                $session['mossa'] = "alfiere" ;
                break;
            case 41:
                $session['author']= "Aris";
                //titolo: Le tre rose rosse
                $session['chessboard'] = array(j,d,b,b,b,c,d,d,b,d,k,g,l,g,m,f,b,k,l,i,k,g,g,g,i,k,g,g,g,g,j,h,g,g,g,j,e,g,g,g,f,a,k,g,g,g,g,g,g,g,g,g,g,g,g,g,g,g,g,g,g,g,g,g,g,g,f,h,g,g,g,g,g,m,g,g,g,g,j,h,g,m,g,g,j,h,m,g,m,i,k,g,j,h,g,g,j,d,e,o,b,h,g,m,j,h,g,m,j,h,j,c,k,g,j,c,k,g,j,c,k,g,z,g,g,f,z,g,g,g,z,g,i,d,h,m,i,d,h,m,i,p,h);
                break;
            case 42:
                $session['author']= "Aris";
                //titolo: le tre rose nere
                $session['chessboard'] = array(j,d,b,b,b,c,d,d,b,d,k,g,l,g,m,f,b,k,l,i,k,g,g,g,i,k,g,g,g,g,j,h,g,g,g,j,e,g,g,g,f,a,k,g,g,g,g,g,g,g,g,g,g,g,g,g,g,g,g,g,g,g,g,g,g,g,f,h,g,g,g,g,g,m,g,g,g,g,j,h,g,m,g,g,j,h,m,g,m,i,k,g,j,h,g,g,j,d,e,o,b,h,g,m,j,h,g,m,j,h,j,c,k,g,j,c,k,g,j,c,k,g,z,e,g,p,z,g,g,g,z,g,i,d,h,m,i,d,h,m,i,d,h);
                break;
            case 43:
                $session['author']= "de Zent";
                //title: merryChrismas
                $session['chessboard'] = array(q,l,l,l,j,a,k,l,l,l,q,j,c,c,c,c,a,c,c,c,c,k,m,q,l,l,l,g,l,l,l,q,m,p,j,c,c,c,a,c,c,c,k,p,q,m,q,l,l,g,l,l,q,m,q,q,p,j,c,c,a,c,c,k,p,q,q,q,m,q,l,g,l,q,m,q,q,q,q,r,j,c,a,c,k,r,q,q,q,q,q,m,q,g,q,m,q,q,q,q,q,q,p,q,g,q,p,q,q,q,q,q,q,q,j,a,k,q,q,q,q,q,q,q,q,p,g,p,q,q,q,q,q,q,q,q,q,z,q,q,q,q,q);
                break;
            case 44:
                $session['author']= "Teg";
                //titolo: Incroci
                $session['chessboard']=array(j,d,d,b,b,a,b,d,b,b,k,g,j,k,f,a,a,a,k,g,g,g,m,g,g,i,a,a,a,e,i,h,g,j,h,i,k,f,a,a,a,d,d,h,f,k,l,g,f,a,a,a,d,b,n,g,g,i,c,a,a,a,a,b,a,k,f,a,d,n,f,a,a,a,c,e,g,g,g,o,b,a,a,a,a,k,m,g,m,i,k,f,c,a,a,e,f,d,e,j,b,h,m,j,a,a,e,i,b,h,g,g,o,d,c,a,c,h,j,c,k,g,i,b,k,o,c,d,d,c,n,g,i,n,s,i,d,z,o,d,d,d,h);
                break;
            case 45:
                $session['author']= "Sook";
                //titolo: 4 strade per la morte
                $session['chessboard'] = array(o,d,d,d,d,a,d,d,d,d,k,j,d,b,b,k,g,j,d,d,d,h,f,n,m,m,g,g,f,d,d,d,g,i,k,z,b,e,g,g,p,z,l,g,l,g,p,f,e,g,g,g,o,c,h,f,h,i,h,m,g,i,h,o,d,k,i,d,d,d,d,a,d,d,d,d,h,j,d,d,d,k,g,j,d,d,d,k,g,j,d,k,g,g,g,j,d,k,g,g,g,z,g,g,g,g,g,z,g,g,g,g,p,h,g,g,g,i,p,g,g,g,i,d,d,h,g,i,d,d,h,g,i,d,d,d,d,c,d,d,d,d,h);
                break;
            case 46:
                $session['author']= "Excalibur";
                //titolo: ZigoZago
                $session['chessboard'] = array(j,d,b,d,k,g,j,d,d,b,k,g,z,i,k,g,g,i,k,l,g,g,g,g,o,e,g,g,j,h,g,g,g,g,i,k,g,g,g,i,k,g,g,g,g,l,g,g,g,g,j,h,g,g,g,i,h,g,g,g,g,i,k,g,g,g,j,d,h,g,g,g,j,h,g,g,g,g,j,k,g,g,g,g,j,c,h,g,g,g,g,g,i,a,g,g,j,d,h,g,g,g,i,k,g,g,g,i,d,k,g,g,i,r,i,a,h,i,d,k,m,g,i,d,d,d,c,d,d,n,i,k,i,d,d,d,d,d,d,d,d,d,h);
                break;
            case 47:
                 $session['author']= "Sook";
                 //titolo: Cripta Maledetta
                 $session['chessboard'] = array(q,q,q,q,o,a,n,q,q,q,q,q,a,a,a,b,a,b,a,a,a,q,q,a,s,s,a,s,a,s,s,a,q,q,a,s,a,a,a,a,a,s,a,q,q,a,s,a,r,a,r,a,s,a,q,q,a,s,a,r,a,r,a,s,a,q,q,a,s,a,r,a,r,a,s,a,q,q,a,s,a,a,a,a,a,s,a,q,q,a,a,a,r,a,r,a,a,a,q,q,a,s,a,a,a,a,a,s,a,q,q,a,s,a,r,a,r,a,s,a,q,q,e,o,a,r,a,r,a,n,f,q,q,q,q,q,q,z,q,q,q,q,q);
                 break;
            case 48:
                 $session['author']= "The_Dream";
                 //titolo: l\'impossibile
                 $session['chessboard'] = array(j,d,b,b,b,c,r,b,d,k,z,f,n,g,g,g,j,b,q,b,a,k,g,l,g,g,g,g,f,b,a,a,e,g,g,g,g,g,g,f,e,g,g,g,g,g,g,g,g,g,f,e,g,g,g,g,g,g,g,g,f,h,i,a,e,g,g,g,g,g,g,f,k,z,h,g,g,g,g,g,g,g,i,c,d,k,r,g,f,e,f,a,e,l,l,o,a,n,g,g,r,g,g,g,f,a,n,g,z,g,g,g,g,g,g,m,f,n,f,n,g,g,g,i,c,c,p,a,d,a,n,g,i,c,d,d,d,c,c,d,c,d,h);
            break;
            case 49:
                 $session['author']= "Sook";
                 //titolo: Benvenuti al Manicomio!
                 $session['chessboard'] = array(o,d,d,d,o,a,n,d,d,d,n,i,h,i,c,e,a,f,c,h,i,h,f,a,a,a,e,l,f,a,a,a,e,f,q,a,q,e,l,f,r,a,r,e,f,q,a,q,e,l,f,r,a,r,e,i,c,c,c,h,l,i,c,c,c,h,i,d,d,b,d,d,d,d,d,d,h,i,s,h,d,d,d,d,d,d,d,k,m,m,n,b,d,k,b,k,a,s,k,g,e,k,d,s,n,o,s,c,h,n,j,p,d,p,a,f,j,i,i,o,g,j,g,d,f,f,p,n,k,h,p,l,p,d,m,p,p,z,d,n,m,h,n);
            break;
            case 50:
                 $session['author']= "Caronte";
                 //title: Infernal Trap
                 $session['chessboard'] = array(l,j,d,b,b,a,b,b,d,b,n,i,h,p,h,p,a,p,i,d,c,k,o,d,b,d,d,c,k,j,k,j,h,j,d,c,k,j,d,h,g,g,i,k,g,j,k,m,g,j,d,h,g,j,h,g,g,f,k,g,f,d,k,g,i,k,f,h,g,m,g,g,l,m,g,j,h,g,o,a,n,g,i,c,k,g,i,k,f,k,i,k,f,n,j,h,g,j,h,g,i,k,g,g,j,a,p,g,i,k,i,k,g,g,g,g,g,l,z,j,h,j,h,g,g,p,g,g,g,p,i,k,m,o,h,i,d,h,i,h,i,d,h);
            break;
            case 51:
                 $session['author']= "Randir";
                 //titolo: Randir
                 $session['chessboard'] = array(j,b,b,b,b,a,b,b,b,b,k,g,g,g,g,g,s,g,g,g,g,g,g,f,a,a,a,a,a,g,i,e,g,g,g,a,a,a,h,i,a,a,e,g,g,g,f,e,g,z,b,r,i,e,g,g,g,f,a,e,p,a,e,o,e,g,g,g,g,g,f,i,c,a,b,e,g,g,g,g,g,m,o,b,c,a,e,g,g,f,g,g,j,b,a,k,i,e,g,g,f,c,c,m,i,a,q,d,e,g,g,f,k,l,o,k,f,c,d,e,g,g,m,g,i,d,c,a,d,b,e,g,m,o,c,d,d,d,c,d,c,h,m);
            break;
            case 52:
                 $session['author']= "Randir";
                 //titolo: Randir
                 $session['chessboard'] = array(j,b,b,b,b,a,b,r,b,b,k,g,f,e,f,q,a,a,a,e,g,g,g,f,e,g,i,a,c,s,a,e,g,g,f,e,i,d,a,d,h,g,g,g,g,f,e,o,d,a,d,n,g,g,g,g,f,e,o,d,a,d,p,a,e,g,g,f,e,o,d,a,d,n,g,g,g,g,f,e,o,d,a,d,n,g,g,g,g,f,e,o,d,a,d,n,g,g,g,g,f,e,o,d,a,d,n,g,g,g,g,f,e,o,d,a,d,n,g,g,g,g,i,h,o,d,a,d,n,m,g,g,i,d,d,d,d,c,d,d,d,h,z);
            break;
            case 53:
                 $session['author']= "Randir";
                 //titolo: Randir
                 $session['chessboard'] = array(j,n,j,b,b,c,d,d,d,d,r,f,d,e,f,q,z,p,j,d,n,g,g,j,a,a,h,i,e,i,k,l,g,g,f,e,g,j,k,f,d,h,g,g,g,g,g,g,g,g,f,d,b,e,g,g,g,g,i,e,g,s,k,g,g,g,g,g,g,l,g,g,g,f,e,g,g,g,g,f,h,g,g,g,f,e,g,g,g,g,f,k,g,g,g,f,e,g,g,g,g,g,m,g,g,g,f,a,e,g,g,g,g,l,g,g,i,a,e,f,h,g,i,c,c,h,g,o,c,c,c,k,i,d,d,d,d,c,d,d,d,d,h);
            break;
            case 54:
                 $session['author']= "Randir";
                 //titolo: Randir
                 $session['chessboard'] = array(o,d,k,j,k,i,k,j,d,d,k,l,j,a,h,g,j,h,i,d,k,m,i,h,f,n,i,a,k,j,k,f,k,l,j,a,b,b,e,i,h,i,h,g,i,h,g,c,a,a,n,j,k,j,e,l,j,a,k,f,n,j,h,i,a,e,i,h,f,a,e,o,a,b,d,a,h,l,j,e,g,i,k,m,i,k,i,k,i,e,i,a,a,h,j,k,z,j,h,l,f,k,i,c,d,h,m,j,h,l,i,e,g,l,j,k,j,k,i,k,g,o,e,g,i,h,i,h,i,d,a,e,o,c,c,d,d,d,d,d,d,c,h);
            break;
            case 55:
                 $session['author']= "Randir";
                 //titolo: Randir
                 $session['chessboard'] = array(j,d,d,d,b,a,d,d,d,d,k,g,z,d,b,r,c,b,b,b,k,g,g,j,k,i,c,k,m,m,m,g,g,g,g,g,j,k,i,d,k,l,g,g,g,g,g,g,g,j,k,i,c,e,g,g,g,g,g,g,g,g,j,k,m,g,g,g,g,g,g,g,g,g,g,l,g,g,g,g,g,g,g,g,g,i,h,g,g,g,g,g,g,g,i,h,j,k,g,g,g,g,g,g,g,l,j,a,e,g,g,g,g,g,i,h,g,g,f,h,g,g,g,g,g,j,d,c,c,a,n,g,i,h,i,h,i,d,d,d,c,d,h);
            break;
            case 56:
                 $session['author']= "Randir";
                 //titolo: Randir
                 $session['chessboard'] = array(p,b,b,b,b,a,b,b,b,b,p,g,g,j,n,f,a,e,f,h,i,e,g,g,i,k,f,a,e,i,k,j,e,g,g,j,h,f,a,e,j,h,f,e,g,g,i,k,f,a,e,i,k,f,e,g,g,j,h,f,a,e,j,h,f,e,g,g,g,j,r,a,r,i,n,f,e,g,g,f,a,a,a,a,b,e,f,e,g,g,f,a,a,a,e,f,e,f,e,g,g,f,c,c,a,e,g,g,f,e,g,g,f,k,j,a,e,g,g,f,e,g,g,f,s,a,s,e,m,g,f,e,i,h,m,z,m,z,c,z,q,i,h);
            break;
            case 57:
                 $session['author']= "Caronte";
                 //titolo: Infernal Lair
                 $session['chessboard'] = array( l,j,d,b,b,a,b,b,d,b,n,i,h,p,h,p,f,p,i,d,c,n,o,d,b,d,d,c,k,j,b,b,n,j,d,c,k,j,d,h,g,g,i,k,g,j,k,m,g,j,d,h,g,j,h,g,g,f,k,g,f,d,k,g,i,k,f,h,g,m,g,g,l,m,g,j,h,g,o,a,n,g,i,c,k,g,i,k,f,k,i,k,f,n,j,a,p,j,h,g,i,k,g,g,j,a,p,p,c,k,i,k,g,g,g,g,g,f,z,j,h,j,h,g,g,p,g,g,g,p,i,k,m,o,h,i,d,h,i,h,i,d,h,n);
            break;
            case 58:
                 $session['author']= "EvaLowrien";
                 //titolo: ????
                 $session['chessboard'] = array(j,d,d,d,b,l,x,x,l,l,x,g,x,x,x,m,i,d,d,c,a,k,i,k,o,d,d,d,d,b,d,a,e,x,i,k,x,x,x,r,a,b,a,h,j,d,h,x,x,x,x,g,i,c,k,g,p,x,x,x,j,n,g,x,l,g,f,a,d,d,k,g,x,g,x,g,g,m,g,x,x,g,g,l,g,l,g,g,x,g,x,x,a,a,a,a,a,a,h,x,g,x,x,x,x,g,i,a,a,k,x,g,j,b,d,n,g,x,i,k,g,x,f,a,a,z,o,e,x,x,g,g,x,s,c,c,d,d,h,x,x,i,h);
            break;
            case 59:
                 $session['author']= "EvaLowrien";
                 //titolo: ????
                 $session['chessboard'] = array(j,d,d,d,d,e,o,d,d,d,k,g,j,d,b,d,c,b,k,o,k,g,g,g,o,e,j,d,h,i,k,i,e,g,g,o,e,g,j,k,j,c,d,h,g,f,n,g,g,i,a,a,d,d,k,g,g,o,e,i,k,i,c,d,k,g,g,f,n,g,j,c,k,l,p,h,g,g,g,o,e,i,d,h,i,d,k,g,g,f,d,c,d,d,k,z,d,c,e,m,f,d,d,d,d,c,d,d,n,g,o,a,d,d,d,d,d,d,d,k,g,j,a,b,b,b,b,b,b,n,g,g,m,m,i,c,c,c,c,c,d,s,h);
            break;
            case 60:
                 $session['author']= "Lilith";
                 //titolo: ????
                 $session['chessboard'] = array(j,d,d,d,d,a,d,d,b,d,k,g,j,d,k,j,a,b,d,c,k,g,g,g,j,h,g,f,e,j,d,h,g,g,g,i,k,g,g,g,g,j,k,g,g,g,j,h,g,g,g,g,g,g,g,g,g,i,k,g,g,g,f,a,a,h,g,g,j,h,g,g,g,g,f,a,k,g,g,i,k,g,g,m,g,g,g,g,g,g,q,g,g,i,d,a,h,g,g,g,g,g,g,g,o,d,a,d,h,g,g,g,f,h,i,b,d,p,d,d,h,f,h,z,d,d,a,d,a,d,d,k,i,d,d,d,d,c,d,c,d,d,h);
            break;
            case 61:
                 $session['author']= "Lilith";
                 //titolo: ????
            $session['chessboard'] = array(j,d,d,d,n,i,d,d,d,d,k,f,b,b,b,b,b,b,b,b,b,e,f,e,f,e,f,c,e,i,h,r,g,f,e,g,g,i,k,g,j,d,k,g,f,e,g,g,o,e,g,i,d,e,g,f,a,e,g,j,h,g,j,d,a,e,f,a,e,g,i,k,m,f,n,g,g,f,e,g,g,j,h,j,a,d,a,e,f,e,g,g,g,o,c,e,z,g,g,f,e,g,m,i,d,d,e,i,a,q,f,e,g,o,d,d,d,h,o,c,h,f,e,m,o,d,d,d,d,d,d,k,i,c,d,d,d,d,d,d,d,d,h);
            case 62:
                 $session['author']= "Lilith";
                 //titolo: ????
            $session['chessboard'] = array(p,b,d,d,d,c,b,b,b,b,k,j,a,d,d,d,d,a,h,m,f,e,f,e,j,d,d,k,i,d,k,f,e,f,h,g,o,h,h,j,k,g,g,m,i,n,g,j,d,d,h,i,h,i,k,l,o,e,g,j,b,b,b,k,j,h,f,k,i,h,f,h,s,h,g,i,k,f,a,d,k,g,l,z,j,h,j,h,g,g,j,h,f,c,h,i,k,i,k,g,m,f,d,c,n,j,d,h,j,h,i,d,h,l,j,k,i,d,k,i,k,j,d,n,g,f,a,d,d,c,n,g,i,d,d,c,c,c,d,d,d,d,h);
            break;
            case 63:
                 $session['author']= "Silver";
                 //titolo: yeayh
            $session['chessboard'] = array(j,b,b,b,b,a,b,b,b,b,k,f,a,a,a,a,a,a,a,a,a,e,f,a,a,a,a,a,a,a,a,a,e,f,a,a,a,a,a,a,a,a,a,e,f,a,a,a,a,a,a,a,a,a,e,f,a,a,a,a,a,a,a,a,a,e,f,a,a,a,a,a,a,a,a,a,e,f,a,a,a,a,a,a,a,a,a,e,f,a,a,a,q,s,p,a,a,a,e,f,a,a,a,a,z,q,a,a,a,e,f,a,a,a,a,r,a,a,a,a,e,f,a,a,a,a,a,a,a,a,a,e,i,c,c,c,c,c,c,c,c,c,h);
            break;

        }
    
   		$chessboard = $session['chessboard'] ;
        $chessboardmap.="chess.jpg";
        $mapkey.="<img src=\"./images/chess/$chessboardmap\" title=\"\" alt=\"\" style=\"width: 40px; height: 40px;\">";
        output("`n");
        $mapkey2="";
        $mapkey="";
        for ($i=0;$i<81;$i++){
            $keymap=ltrim($chessboard[$i]);
            $chessboardmap=$keymap;
            $chessboardmap.="chess.jpg";
            $mapkey.="<img src=\"./images/chess/$chessboardmap\" title=\"\" alt=\"\" style=\"width: 40px; height: 40px;\">";
            if ($i==8 or $i==17 or $i==26 or $i==35 or $i==44 or $i==53 or $i==62 or $i==71 or $i==80){
                $mapkey="`n".$mapkey;
                $mapkey2=$mapkey.$mapkey2;
                $mapkey="";
            }
        }
        output($mapkey2,true);
        
        if ( $session['user']['superuser'] > 2 ){
			output("`nProblema n° ".$session['switch']." ");
		}
        addnav("Muovi Pedone","redeglielfi.php?op=pedone");
        addnav("Muovi Torre","redeglielfi.php?op=torre");
        addnav("Muovi Cavallo","redeglielfi.php?op=cavallo");
        addnav("Muovi Alfiere","redeglielfi.php?op=alfiere");
        addnav("Muovi Donna","redeglielfi.php?op=donna");
        addnav("Muovi Re","redeglielfi.php?op=re");
        addnav("Abbandona","redeglielfi.php?op=abbandona");
       
        
}elseif ($_GET['op'] == "pedone" OR $_GET['op'] == "torre" OR $_GET['op'] == "cavallo" OR $_GET['op'] == "alfiere" OR $_GET['op'] == "donna" OR $_GET['op'] == "re" OR $_GET['op'] == "abbandona"){  
			$session['tentativo'] = $session['tentativo'] + 1 ;
			if ( $_GET['op'] == $session['mossa'] ) { 
				page_header("Vittoria !"); 
				output("`n`c`b`6La ricompensa per la Vittoria`0`b`c`n`n");
				if ($session['tentativo'] > 1 ) {
					output("`#Complimenti!!!! `n`3L'aver scelto la mossa giusta ha determinato la tua vittoria! Re `^Finvarra`3 accetta la sconfitta e seppur di malavoglia esclama:`n ");
					output("".$session['user']['name']."`6 Hai vinto, riconosco la tua abilità di ".($session['user']['sex']?"giocatrice":"giocatore")." anche se ho giocato d'impulso e ho commesso molti errori.`n");
					output("Pazienza, la mia parola è sacra. Dal momento però che ti ho dato la possibilità di correggere un tuo errore che ti avrebbe portat".($session['user']['sex']?"a":"o")." a una sicura sconfitta, ritengo che sia onorevole da parte tua concedere qualcosa anche a me prendendo solo la metà del premio che avevamo pattuito.`n`n");
					output("`3Ti conduce quindi in quella che è la stanza del tesoro e apre un cofano pieno di pietre preziose. Accettando di buon grado il compromesso con il sovrano, raccogli una manata di pietre luccicanti, circa una quindicina, mettendole in un sacchetto di pelle di daino che `^Finvarra`3 stesso ti ha dato per riporle al sicuro.`n ");
				}else{	
					output("`#Complimenti!!!! `n`3L'aver scelto la mossa giusta ha determinato la tua vittoria! Re `^Finvarra`3 accetta la sconfitta e seppur di malavoglia esclama:`n ");
					output("".$session['user']['name']."`6 Hai vinto, riconosco la tua abilità di ".($session['user']['sex']?"giocatrice":"giocatore")." anche se ho giocato d'impulso e ho commesso molti errori. Pazienza, la mia parola è sacra. Seguimi e ti darò la ricompensa che ti spetta.`n`n");
					output("`3Ti conduce quindi in quella che è la stanza del tesoro e apre un cofano pieno di pietre preziose. Tenendo le mani unite a coppa, ne raccogli circa una trentina e le metti in un sacchetto di pelle di daino che `^Finvarra`3 stesso ti ha dato per riporle al sicuro.`n ");
				}
				output("`3Ti è permesso quindi lasciare la corte degli `^Elfi`3 e ritornare al tuo accampamento ai piedi della collina.`n`n ");
				addnav("Torna all'accampamento","redeglielfi.php?op=ritorna");
				
			}else{ 
				if ($session['tentativo'] > 1 ) {
					page_header("La Sconfitta !");
					output("`n`c`b`6Hai perso la Partita`0`b`c`n`n");
					output("`3Il sudore ti imperla la fronte, purtroppo la tua mossa non è quella giusta e la tua vita dipendeva da questa scelta. ");
					output("Con il terrore che ti gela il sangue guardi `^Finvarra`3 che ti osserva sogghignando e fumando la sua pipa elfica appoggiato allo schienale della sedia. ");
					output("Paralizzato dalla paura inizi a compiere un errore dietro l'altro e, quella che si stava preannunciando come una facile vittoria, si tramuta in una disastrosa
						sconfitta. `nCon orrore vedi `^Finvarra`3 fare la sua ultima mossa e ...... `6 Scacco matto, messere!! Adesso mi prenderò la tua vita… `n");
					output("`3Guardi quasi incredul".($session['user']['sex']?"a":"o")." i pezzi sulla scacchiera ma ti devi arrendere all'evidenza dei fatti e colpisci il tuo re facendolo cadere. `&Hai Perso! `3`n");
					output("Dal pavimento iniziano a salire delle piante rampicanti che si avviluppano attorno al tuo corpo…sempre più strette fino a soffocarti. Senti la tua vita scivolare via e l'ultima cosa che odi è la risata sarcastica di `^Finvarra`3 ");
	 				output("`n`%Sei mort".($session['user']['sex']?"a":"o")."!`n`n");
	        		output("`3Perdi tutto l'`^oro`3 che avevi in tasca e il `&10%`3 di esperienza.`n");
	    			$session['user']['alive']=false;
	        		$session['user']['hitpoints']=0;
	        		$session['user']['gold']=0;
	        		addnav("Notizie quotidiane","news.php");
	        		addnews("`%".$session['user']['name']."`3 è stat".($session['user']['sex']?"a":"o")." uccis".($session['user']['sex']?"a":"o")." da una Partita a Scacchi.`0.");
	 				debuglog("è stato ucciso perdendo a scacchi con Finvarra perdendo 10% esperienza e tutto l'oro");
	 			}else{ 
		 			page_header("L' Errore !");
					output("`n`c`b`6Hai sbagliato Mossa`0`b`c`n`n");
					output("`3Il sudore ti imperla la fronte, ti rendi conto che la mossa non è quella giusta e che intendevi farne un'altra. Però hai già toccato il pezzo sbagliato.... ");
					output("Con il terrore che ti gela il sangue guardi `^Finvarra`3 che ti osserva sogghignando e fumando la sua pipa elfica appoggiato allo schienale della sedia. ");
					output("Egli ti guarda e con un sorriso indulgente ti esorta a cambiare pure mossa, anche se ti lascia capire che al prossimo errore non sarà così magnanimo nei tuoi confronti. ");
					output("Riprendi a respirare per lo scampato pericolo e ti concentri per essere veramente sicuro stavolta di compiere la giusta scelta, anche se i dubbi ti attanagliano lo stomaco. `n`n");
					addnav("Rifai la Mossa","redeglielfi.php?op=riprova");
				}	
            }
}elseif ($_GET['op'] == "riprova"  ){
	page_header("La Partita a Scacchi con il Re Elfo"); 
	output("`n`c`b`6La Partita a Scacchi con il Re `^Elfo`0`b`c`n`n");
	$chessboard = $session['chessboard'] ;
                
	$chessboardmap.="chess.jpg";
        $mapkey.="<img src=\"./images/chess/$chessboardmap\" title=\"\" alt=\"\" style=\"width: 40px; height: 40px;\">";
        output("`n");
        $mapkey2="";
        $mapkey="";
        for ($i=0;$i<81;$i++){
            $keymap=ltrim($chessboard[$i]);
            $chessboardmap=$keymap;
            $chessboardmap.="chess.jpg";
            $mapkey.="<img src=\"./images/chess/$chessboardmap\" title=\"\" alt=\"\" style=\"width: 40px; height: 40px;\">";
            if ($i==8 or $i==17 or $i==26 or $i==35 or $i==44 or $i==53 or $i==62 or $i==71 or $i==80){
                $mapkey="`n".$mapkey;
                $mapkey2=$mapkey.$mapkey2;
                $mapkey="";
            }
        }
        output($mapkey2,true);
        
        if ( $session['user']['superuser'] > 2 ){
			output("`nProblema n° ".$session['switch']." ");
		}
        addnav("Muovi Pedone","redeglielfi.php?op=pedone");
        addnav("Muovi Torre","redeglielfi.php?op=torre");
        addnav("Muovi Cavallo","redeglielfi.php?op=cavallo");
        addnav("Muovi Alfiere","redeglielfi.php?op=alfiere");
        addnav("Muovi Donna","redeglielfi.php?op=donna");
        addnav("Muovi Re","redeglielfi.php?op=re");
        addnav("Abbandona","redeglielfi.php?op=abbandona");           
}elseif ($_GET['op'] == "ritorna"  ){ 
	page_header("La sorpresa"); 
	output("`n`c`b`6La Sorpresa`0`b`c`n`n");
	if ($session['tentativo'] > 1 ) {
		$gemme = e_rand(2,12);
	}else{
		$gemme = e_rand(10,20);
	} 	
	output("`3Soddisfatt".($session['user']['sex']?"a":"o")." di te stess".($session['user']['sex']?"a":"o")." e della tua abilità di scacchista ritorni al tuo accampamento mentre già sta albeggiando. La gioia di avere tutte quelle pietre preziose ti spinge ad aprire il sacchetto per guardarle ancora.
		Con sorpresa scopri che per la maggior parte sono diventate delle comuni pietre e che solo `&$gemme`3 sono rimaste `&gemme`3! ");
	output("`3Da questa avventura impari che i doni degli `^Elfi`3 in maggior parte sono solo sogno ed illusione... Ti volti verso la collina fatata ed il vento del mattino ti porta l'eco di una risata antica... `n`n");
	$session['user']['gems'] += $gemme;
	debuglog("ha guadagnato $gemme gemme vincendo a scacchi con Finvarra. ");
    addnav("Torna al Villaggio","village.php");       
}
    
rawoutput("<br><div style=\"text-align: right ;\"><a href=\"http://www.ogsi.it\" target=\"_blank\"><font color=\"#33FF33\">Il Re degli Elfi by Hugues & LadyRockiell @ http://www.ogsi.it</font></a><br>");

page_footer();
?>