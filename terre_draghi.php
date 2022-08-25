<?php
require_once "common.php";
require_once("common2.php");

function liberaterrenondifese() {

	// Viene forzata la liberazione delle terre non difese con l'azzeramento del bonus  

	$sql = "SELECT * FROM terre_draghi WHERE totale_bonus > 0 AND id_player >0 AND attacco <= 10 AND difesa <= 10 AND vita <= 20 ORDER BY rand() LIMIT 1 ";
    $result = db_query($sql) or die(db_error(LINK));
    $countrow = db_num_rows($result);
    if ($countrow > 0 ) { 
    	$row = db_fetch_assoc($result);
	    $id_terra = $row['id'];
	    $id_player = $row['id_player'];
	    
	    $sql = "UPDATE terre_draghi_storia SET tempo_fine='".time()."',data_fine=FROM_UNIXTIME('".time()."') where tempo_fine='0' AND id_player=".$id_player." AND id_terra=".$id_terra." ";
	    db_query($sql) or die(db_error(LINK));
	    $sql = "SELECT * FROM terre_draghi_storia WHERE id_player=".$id_player." and conteggiato='no' AND id_terra=".$id_terra." ";
	    $result = db_query($sql) or die(db_error(LINK));
	    $rowtds = db_fetch_assoc($result);
	    $tempo_permanenza=intval(($rowtds['tempo_fine']-$rowtds['tempo_inizio'])/3600);
	    $guadagno=$rowtds['bonus_terra']*$tempo_permanenza;

	    if($guadagno>$row['totale_bonus'])$guadagno=$row['totale_bonus'];
	    
	    $nemico = 'Zurkan il Signore dei Cavalieri Oscuri';
	    switch (e_rand(1,5)){
        case 1:
            $nemico = 'Zurkan il Signore dei Cavalieri Oscuri';
            break;
        case 2:
            $nemico = 'Rothgard il Cavaliere Oscuro';
            break;
        case 3:
            $nemico = 'Hisendhall il Cavaliere Oscuro';
            break;
        case 4:
            $nemico = 'Burstrom il Cavaliere Oscuro';
            break;
        case 5:
            $nemico = 'Kaleb il Cavaliere Oscuro';
           	break;        	
    	}
	    
	    $mailmessage = "`x$nemico `2ha attaccato con il Soffio di Morte della sua `xViverna Nera`2 il territorio `6".$row['nome']." `2devastandolo completamente. `nIl tempo per cui hai mantenuto il controllo del territorio ti ha fatto guadagnare `6$guadagno`@ punti carriera.";
        systemmail($id_player,"`2Terre dei Draghi : Territorio distrutto.`2",$mailmessage);     
		
		addnews("`x$nemico `2 ha devastato completamente `6".$row['nome']."`2 nelle Terre dei Draghi.");     

		$sql = "UPDATE accounts SET punti_carriera = punti_carriera +$guadagno,fama3mesi=(fama3mesi+(fama_mod*$guadagno)) where acctid=".$id_player." ";	    
		db_query($sql) or die(db_error(LINK));
	    $sql = "UPDATE terre_draghi_storia SET conteggiato='si' where id_player=".$id_player." AND id_terra=".$id_terra." ";
	    db_query($sql) or die(db_error(LINK));
	    $sql = "UPDATE terre_draghi SET id_player=0,totale_bonus=0 where id=".$id_terra." ";
	    db_query($sql) or die(db_error(LINK));
	}
}

$user_id=$session['user']['acctid'];
rivaluta_drago($user_id);
page_header("Antiche Terre dei Draghi");
$session['user']['locazione'] = 181;
if($_GET['pass']=="on"){
    $usi=donazioni_usi('pass_draghi')-1;
    donazioni_usi_up('pass_draghi',$usi);
    debuglog("va nelle terre dei draghi utilizzando il pass dell'ambasciata, restano $usi accessi.");
    $_GET['pass'] = "";
}
if ($session['user']['alive'] != 0) {
    $sql = "SELECT * FROM terre_draghi_exp where id_player='".$session['user']['acctid']."' AND acquisiti='no'";
    $result = db_query($sql) or die(db_error(LINK));
    $countrow = db_num_rows($result);
    for ($i=0; $i<$countrow; $i++){
    //for ($i = 0;$i < db_num_rows($result);$i++) {
        $row = db_fetch_assoc($result);
        if($row['punti_carriera']> 0){
            $session['user']['punti_carriera']+=$row['punti_carriera'];
            $fama = $row['punti_carriera']*$session[user][fama_mod];
            $session['user']['fama3mesi'] += $fama;
            debuglog("guadagna $fama punti fama nelle Terre dei Draghi. Ora ha ".$session['user']['fama3mesi']." punti");
            debuglog("guadagna ".$row['punti_carriera']." nelle Terre dei Draghi. Ora ha ".$session['user']['punti_carriera']." punti");
            $mailmessage = "`^".$session['user']['name']."`2 hai raccolto $row[punti_carriera] punti carriera guadagnati in precedenza nelle terre dei draghi!`n";
            $mailmessage = str_replace("%o",($session['user']['sex']?"lei":"lui"),$mailmessage);
            systemmail($session['user']['acctid'],"`2Raccolto punti carriera.`2",$mailmessage);
        }
        $sql = "UPDATE terre_draghi_exp SET acquisiti='si',data=FROM_UNIXTIME('".time()."') where id=$row[id]";
        db_query($sql) or die(db_error(LINK));
    }
    if ($session['user']['superuser'] > 2){
        addnav("`SIngresso Admin","");
        addnav("Viaggia","terre_draghi.php?op=tdd");
    }
    if ($session['user']['id_drago'] != 0 OR $_GET['op']=="mercante") {
        $sql = "SELECT * FROM draghi WHERE user_id = '".$session['user']['acctid']."'";
        $result = db_query($sql) or die(db_error(LINK));
        $rowd = db_fetch_assoc($result);
        if ($rowd['vita_restante'] > 0) {
        	if ($rowd['carattere'] > $session['user']['cavalcare_drago']) {
        			output("`2`c`bVerso le Terre dei Draghi`b`c`2`n`n");
	        		output("`2Il viaggio sul dorso del tuo fedele `@Drago`2 richiede molta perizia ed abilità, purtroppo i tuoi punti cavalcare non sono sufficienti per controllare in tutta sicurezza la tua cavalcatura. ");
        			output("Torna al villaggio ed acquisisci la necessaria abilità per poter intraprendere il viaggio verso le leggendarie `@Terre dei Draghi`2.`n");
        			addnav("Torna al Villaggio","village.php");
            }else{
	            if ($_GET['op']==""){
	            	$liberacento = mt_rand(1, 99);
		            if($liberacento < 6){
		            	liberaterrenondifese() ;
		            }
	                if (donazioni('sentiero_segreto')==false){
	                    if ($session['user']['turns'] < 10) {
	                        output("`5Il viaggio richiede una notevole forza fisica e tu sei troppo esausto ");
	                        output("per affrontarlo.`n Devi disporre di almeno 10 turni foresta per poterlo fare e tu ");
	                        output("ne hai a disposizione solamente `^".$session['user']['turns']."!!`n");
	                        addnav("Torna al Villaggio","village.php");
	                    } else {
	                        $session['user']['turns'] -= 10;
	                        addnav("Viaggia","terre_draghi.php?op=tdd");
	                        debuglog("va nelle terre dei draghi al costo di 10 turni");
	                        if ( $rowd['nome_drago'] == "" ) {
	                        	output("`3In sella del tuo fedele `2drago`3, compagno di tante avventure, affronti pieno di speranze il lungo viaggio, sorvolando terre desolate, campi coltivati, ");
	                        }else{
		                       output("`3in sella a ".$rowd['nome_drago'].", il tuo fedele `2drago`3 compagno di tante avventure, affronti pieno di speranze il lungo viaggio, sorvolando terre desolate, campi coltivati, ");
	                         }
	                        output("montagne innevate e territori di cui non avevi mai neanche lontanamente sospettato ");
	                        output("l'esistenza. Ti chiedi preoccupato dove ti porterà questo volo nei cieli a te sconosciuti, ma la sicurezza con cui il ");
	                        output("tuo `2drago`3 segue la rotta ti ridona fiducia e, già pregustando le ricchezze che aspettano di essere scoperte da te ");
	                        output("ti concedi qualche minuto di riposo addormentandoti sulla schiena della tua nobile creatura.`n`n");
	                    }
	                }else{
	                    if ($session['user']['turns'] < 6) {
	                        output("`5Il viaggio richiede una notevole forza fisica e tu sei troppo esausto ");
	                        output("per affrontarlo.`n Devi disporre di almeno `&6`5 turni foresta per poterlo fare e tu ");
	                        output("ne hai a disposizione solamente `^".$session['user']['turns']."!!`n");
	                        addnav("Torna al Villaggio","village.php");
	                    } else {
	                        $session['user']['turns'] -= 6;
	                        addnav("Viaggia","terre_draghi.php?op=tdd");
	                        debuglog("va nelle terre dei draghi al costo di 6 turni");
	                        output("`3Affronti quello che pensavi fosse un lungo viaggio, ma, ");
	                        output("sfruttando la mappa segreta che ti sei procurato arrivi velocemente nelle terre dei draghi!`n`n");
	                    }
	                }
	            }elseif ($_GET['op']=="tdd"){
	                addnav("Vai al Vulcano","terre_draghi.php?op=vulcano");
	                output("`3Il lungo volo prosciuga una notevole quantità dei tuoi turni foresta ma, quando arrivi ");
	                output("finalmente in quella che è conosciuta come la `n`@Terra dei Draghi`3, lo straordinario ");
	                output("spettacolo che ti si mostra dinanzi agli occhi ti lascia senza fiato!! `nUna inospitale ");
	                output("ma quantomeno magnifica landa vulcanica, con sicuramente tanti tesori da scoprire e moltissime");
	                output("sfide da affrontare.`n Ora, accompagnato solamente dal tuo fedele `2drago`3, dovrai fronteggiare i tuoi ");
	                output("pari per il dominio di questa terra ... sarai in grado di farlo?`n`n");
	                output("A qualche lega di distanza noti un vulcano dormiente che sembra però in procinto di risvegliarsi`n`n");
	                output("`n`n");
	            }elseif ($_GET['op']=="vulcano"){
	                $sql = "DELETE FROM terre_draghi_dove  WHERE id_player='".$session['user']['acctid']."'";
	                db_query($sql) or die(db_error($link));
	                output("`^`c`bVicino al vulcano`b`c`6`n`n");
	                checkday();
	                output("`4Ti avvicini cautamente alle pendici del vulcano che pare essere il punto centrale di questa landa ");
	                output("inospitale. `nDecidi di effettuare una ricognizione della zona e sorvoli alcuni sentieri che si ");
	                output("diramano in ogni direzione.`n`n `\$Cosa decidi di fare?");
	                output("`n`n");
	                if ($session['user']['superuser']>0) {
	                    addnav("Città dei Draghi","terre_draghi.php?op=citta");
	
	                }
	                addnav("Esplora (3 turni)","terre_draghi.php?op=esplora");
	                addnav("Erold","terre_draghi.php?op=erold");
	                addnav("Luoghi Importanti","terre_draghi.php?op=importanti");
	                addnav("--");
	                addnav("Torna al Villaggio","village.php");
	            }else if($_GET['op']=="esplora"){
	                if ($session['user']['turns'] < 3) {
	                    output("`5Sei troppo esausto per metterti a esplorare .`n");
	                    addnav("Torna al Vulcano","terre_draghi.php?op=vulcano");
	                } else {
	                    //Luke: Crea terra nuova se ci sono abbastanza draghi appartenti a player rapporto terre draghi 1 a 5
	                    $sql = "SELECT * FROM terre_draghi";
	                    $resultte = db_query($sql) or die(db_error(LINK));
	                    $sql = "SELECT * FROM draghi WHERE user_id>'0'";
	                    $resultdr = db_query($sql) or die(db_error(LINK));
	                    $cont_terre=db_num_rows($resultte)*5;
	                    $cont_draghi=db_num_rows($resultdr);
	                    if ($session['user']['superuser']>0) {
	                        output("`5Terre=$cont_terre ---- Draghi: $cont_draghi.`n");
	                    }
	                    if ( $cont_draghi > $cont_terre) {
	                        $sesso= mt_rand(1,2);
	                        if($sesso==1){
	                            $pre_nome=array('Il sacro ','Il mitico ','Il leggendario ','Il misterioso ','Il temibile ','Il pericoloso ','Il favoloso ','Il grande ');
	                            $post_nome=array('fiume ','albero ','tempio ','monte ','colle ','antro ','fosso ');
	                            $aggettivo=array('','blu','antico','rosso','funesto','nero','largo','bruciato','del dolore','zolforoso','del terrore','devastato');
	                        }else{
	                            $pre_nome=array('La sacra ','La mitica ','La leggendaria ','La misteriosa ','La temibile ','La pericolosa ','La favolosa ');
	                            $post_nome=array('rovina ','grotta ','pianura ','collina ','caverna ','cascata ','palude ','cascata ','fossa ','fontana ','fonte ');
	                            $aggettivo=array('','bruciata','rigogliosa','azzurra','funesta','luminosa','nera','ghiacciata','del terrore','sprofondata','distrutta');
	                        }
	                        $pre_caso= mt_rand(0,(count($pre_nome)-1));
	                        $post_caso= mt_rand(0,(count($post_nome)-1));
	                        $aggettivo_caso= mt_rand(0,(count($aggettivo)-1));
	                        $nome=$pre_nome[$pre_caso].$post_nome[$post_caso].$aggettivo[$aggettivo_caso];
	                        $bonus=mt_rand(5,50)*10;
	                        // Vecchia formula $totale_bonus=mt_rand(1,55)*1000;
	                        $totale_bonus=mt_rand(4000,40000);
	                        $sql = "INSERT INTO terre_draghi (nome,bonus,totale_bonus,descrizione)
	                     VALUES ('$nome','$bonus','$totale_bonus','$nome')";
	                        db_query($sql) or die(db_error($link));
	                    }
	                    //Luke: fine scelta terra
	                    $session['user']['turns'] -= 3;
	                    $cento = mt_rand(1, 99);
	                    if($cento < 30){
	                        output("`%`b`cEsplorazione Sfortunata`b`c`n`n");
	                        if($cento >=0 AND $cento < 10) {
			                	output("`2Dopo svariate ore di esplorazione noti un gruppo di rocce ... sei sicuro di averle ");
	                        	output("già viste. \"`@`nMa non sono le stesse di qualche ora fa?`2\" ti chiedi pensieroso.`n");
	                        	output("Dopo una decina di minuti i tuoi sospetti vengono confermati, e ti ritrovi nuovamente ");
	                        	output("nei pressi del vulcano!! `n`nDevi aver girato in tondo perdendo `&3`2 preziosi turni!!`n`n");    
	                        }elseif($cento >=10 AND $cento < 20) {
	                       		output("`2Dopo svariate ore di esplorazione noti all'orizzonte un drago volare pacifico. `n");
	                        	output("Il pensiero di poterti scontrare contro quel possente animale per poter valutare meglio la forza della tua cavalcatura prende il sopravvento `n");
	                        	output("inciti quindi il tuo fedele compagno gettandoti in un forsennato inseguimento. Invano! Il drago avversario è leggermente più veloce del tuo e, ");
	                        	output("nonostante tutte le tue innumerevoli manovre di avvicinamento, non riesci a raggiungerlo. `nAlla fine desisti dal tuo intento, hai perso `&3`2 preziosi turni!!`n`n");
	                        }elseif($cento >=20 AND $cento < 30) {
	                        	output("`2Dopo svariate ore di esplorazione si presenta di fronte a te uno stupendo panorama mozzafiato. `n");
	                        	output("Il cielo azzurro costellato di nuvole rossastre sembra dominare il terreno soffocandolo in una nebbia sanguinolenta, una montagna `n");
	                        	output("innevata si erge imperiosa all'orizzonte, decidi quindi di dirigerti verso di essa, ma forti venti contrari ti impediscono di avvicinarti. `n");
	                        	output("Il tuo drago, ormai stremato scende al suolo e atterra sulla landa desolata. `nSei costretto a fermarti per farlo riposare perdendo così `&3`2 preziosi turni!!`n`n");
	                        }
	                        addnav("Esplora","terre_draghi.php?op=esplora");
	                        addnav("Torna al Vulcano","terre_draghi.php?op=vulcano");
	                    }elseif($cento >= 30 AND $cento < 60){
	                        $oro= mt_rand(100, 400);
	                        output("`6`b`cHai trovato un po' d'oro`b`c`n`n");
	                        if($cento >=30 AND $cento < 40) {
	                        		output("`3Mentre esplori questa landa desolata un debole bagliore cattura la tua attenzione. Ti avvicini ");
	                        		output("a quello che sembra il letto di un fiume prosciugato e il luccichio da te notato è ");
	                        		output("proprio ciò che pensavi che fosse: `^`boro`b.`n`n ");
	                        }elseif($cento >=40 AND $cento < 50) {
	                        		output("`3Mentre esplori questa landa desolata un riflesso sulla cima di una vetta innevata attira la tua attenzione. `n ");
	                        		output("Ti avvicini con cautela e scopri con piacere che il luccichio da te notato è proprio ciò che pensavi che fosse: `^oro.`n`n ");
	                        }elseif($cento >=50 AND $cento < 60) {
	                        		output("`3Mentre esplori questa landa desolata un bagliore sulla cima di una desolata collina attira la tua attenzione. `n ");
	                        		output("Ti avvicini con cautela e scopri con piacere che il luccichio da te notato è proprio ciò che pensavi che fosse: `^oro.`n`n ");
	                        }
	                        output("`3Hai trovato `^$oro pezzi d'oro!!`n`n");	
	                        addnav("Esplora","terre_draghi.php?op=esplora");
	                        addnav("Torna al Vulcano","terre_draghi.php?op=vulcano");
	                        $session['user']['gold'] += $oro;
	                        debuglog("trova $oro oro nelle terre dei draghi");
	                    }elseif($cento >= 60 AND $cento < 63){
	                        $oro= mt_rand(500, 2000);
	                        output("`6`b`cHai trovato molto oro`b`c`n`n");
	                        output("`2Esplorando la zona circostante il vulcano un ciottolo rilucente cattura la tua ");
	                        output("attenzione. `nTi avvicini e prendendolo in mano togli lo strato di cenere che lo ");
	                        output("ricopre: è una stupenda pepita d'`^oro!`2 `nFelice per l'eccezionale ritrovamento, osservi tutt'intorno e ...... eccone altre ! ti chini subito a raccoglierle e contandole scopri che....`n`n`3Hai trovato `^$oro pezzi d'oro!!`n`n");
	                        addnav("Esplora","terre_draghi.php?op=esplora");
	                        addnav("Torna al Vulcano","terre_draghi.php?op=vulcano");
	                        $session['user']['gold'] += $oro;
	                        debuglog("trova $oro oro nelle terre dei draghi");
	                    }elseif($cento == 63){
	                        $testoperso = mt_rand(0, 1);
		                    if ($testoperso == 1) {  
	                        	output("`#`b`cUn grifone ti ha tagliato la strada,`$ stavi per cadere dal drago!`b`c`n`n");
	                        	output("`3Mentre sei alla ricerca di nuove terre, noti una landa che ti sembra promettente, cerchi quindi di dirigerti in quella direzione,`n ");
	                        	output("in quella direzione, ma ad un tratto un grifone ti sfreccia davanti improvvisamente. Preso dal panico, fai compiere al tuo drago `n ");
	                        }else {
		                        output("`#`b`cManovra azzardata,`$ stavi per cadere dal drago!`b`c`n`n");
	                        	output("`3Mentre sei alla ricerca di nuove terre, noti una landa che ti sembra promettente, cerchi quindi di dirigerti in quella direzione,`n ");
	                        	output("ma una fortissima corrente ascensionale ti porta altrove, tenti quindi di far compiere al tuo drago `n ");
	                        } 
	                        output("una rapida virata ma, ne perdi il controllo rischiando una pericolosa caduta. `nRiesci a malapena ad aggrapparti al collo del tuo fedele animale, ");
	                        output("salvandoti a stento da un mortale capitombolo, ma così facendo perdi fiducia nelle tue capacità di cavaliere di draghi. `n ");   
	                        output("`n`&Hai perso `\$1`& punto cavalcare draghi!`n`n");
	                        addnav("Esplora","terre_draghi.php?op=esplora");
	                        addnav("Torna al Vulcano","terre_draghi.php?op=vulcano");
	                        $session['user']['cavalcare_drago'] -= 1;
	                    }elseif($cento == 64){
	                        $testoguadagnato = mt_rand(0, 1);
	                        if ($testoguadagnato == 1) {
	                        	output("`#`b`cHai incontrato un vecchio cavaliere di draghi`b`c`n`n");
	                        	output("`)Dopo aver imboccato uno dei sentieri che dipartono dalla base del vulcano, senti un ");
	                        	output("rumore che giunge da dietro un enorme masso. Ti avvicini cautamente e scopri seduto su una roccia un ");
	                        	output("cavaliere dell'`&Ordine dei Draghi`)che ti saluta e ti invita al suo desco.`n");
	                        	output("Accetti con entusiasmo l'invito e trascorri il tempo con lui ascoltando il racconto delle sue imprese ");
	                        	output("leggendarie, carpendo da esse alcuni suoi segreti.`n`n `6Grazie ai suoi insegnamenti ");
	                        	output("hai appreso nuove tecniche per combattere con i draghi: `n`n`&Hai guadagnato `\$1`& punto cavalcare draghi!`n`n");
	                        }else {
		                        output("`#`b`cManovra riuscita, `4 migliori la tua abilità col drago!`b`c`n`n");
	                        	output("`3Mentre sei alla ricerca di nuove terre, noti una landa che ti sembra promettente, cerchi quindi di dirigerti in quella direzione, ma una`n ");
	                        	output("fortissima corrente ascensionale ti porta altrove, tenti quindi di far compiere al tuo drago ");
	                        	output("un'ardita evoluzione per rimetterti in quota. `nLa manovra ti riesce perfettamente e così facendo ");
	                        	output("hai acquisito maggior fiducia in te stesso nel cavalcare i draghi: `n`n`&Hai guadagnato `\$1`& punto cavalcare draghi!`n`n");
	                        }
	                        addnav("Esplora","terre_draghi.php?op=esplora");
	                        addnav("Torna al Vulcano","terre_draghi.php?op=vulcano");
	                        $session['user']['cavalcare_drago'] += 1;
	                    }elseif($cento >= 65 AND $cento < 79){
	                        $gemme= mt_rand(1, 2);
	                        if ($gemme == 1) $gemm="gemma";
	                        if ($gemme != 1) {
	                            $gemm="gemme";
	                            $gemme = 2;
	                        }
	                        output("`&`b`cHai trovato qualche gemma`b`c`n`n");
	                        output("`2Affaticato dal clima infuocato che caratterizza queste terre, smonti dal tuo drago ");
	                        output("per concederti qualche istante di pausa. Innervosito dall'esplorazione infruttuosa ");
	                        output("dai un calcio ad un sasso, per scoprire che sotto di esso si cela qualcosa di ");
	                        output("rilucente ai raggi del sole: `n`nHai trovato `&$gemme $gemm!!!`n`n");
	                        addnav("Esplora","terre_draghi.php?op=esplora");
	                        addnav("Torna al Vulcano","terre_draghi.php?op=vulcano");
	                        $session['user']['gems'] += $gemme;
	                        debuglog("trova $gemme gemme esplorando le terre dei draghi");
	                    }elseif($cento == 79){
	                        $gemme= mt_rand(2, 4);
	                        output("`&`b`cHai trovato molte gemme`b`c`n`n");
	                        output("`2Affaticato dal clima infuocato che caratterizza queste terre, smonti dal tuo drago ");
	                        output("per concederti qualche istante di pausa. Innervosito dall'esplorazione infruttuosa ");
	                        output("dai un calcio ad un sasso, per scoprire che sotto di esso si cela qualcosa di ");
	                        output("rilucente ai raggi del sole: `n`nHai trovato `&$gemme gemme!!!`n`n");
	                        addnav("Esplora","terre_draghi.php?op=esplora");
	                        addnav("Torna al Vulcano","terre_draghi.php?op=vulcano");
	                        $session['user']['gems'] += $gemme;
	                        debuglog("trova $gemme gemme esplorando le terre dei draghi");
	                    }elseif($cento >= 80 AND $cento < 85){
	                        output("`#`b`cHai incontrato una Fairy!!`b`c`n`n");
	                        output("`8Nel piene delle tue esplorazioni vieni attratto da uno strano suono, ti dirigi verso un altopiano sassoso, e scopri che impigliata`n");
	                        output("in una tela di ragno c'è una leggiadra e graziosa `^Fairy`8 che chiede il tuo aiuto.`nLa liberi ");
	                        output("prontamente da quella che era morte certa e lei per ringraziamento, cosparge un sasso con una polverina color arcobaleno, `n");
	                        output("trasformandolo in una rilucente `&gemma`8. La gentil creaturina raccoglie la pietra preziosa e te la porge, osservi la `&gemma`8 e mentre ti volti per ringraziare la dolce e ");
	                        output("diafana fatina, scopri che è già scomparsa`n`n");
	                        addnav("Esplora","terre_draghi.php?op=esplora");
	                        addnav("Torna al Vulcano","terre_draghi.php?op=vulcano");
	                        $session['user']['gems'] += 1;
	                        debuglog("guadagna 1 gemma dalla fata della terra dei draghi");
	                    }elseif($cento == 85){
	                        output("`3Mentre girovaghi alla ricerca di qualcosa ti imbatti in un sentiero che sembra essere stato utilizzato ");
	                        output("di recente da molti viaggiatori. `nTi incammini con la speranza di trovare qualcosa di proficuo.`n ");
	                        output("Dopo aver seguito il sentiero per ore, sei convinto di aver perso il tuo tempo inutilmente ");
	                        output("e stai per tornare sui tuoi passi quando il tuo sesto senso ti dice di proseguire e di svoltare ");
	                        output("dietro la collinetta davanti a te. `nIndeciso su cosa fare, decidi infine di seguire le voce interiore, ");
	                        output("e quando scollini ti appare una maestosa città:`n`n");
	                        output("`^`b`cHai trovato la Città dei Draghi!!`c`b`n`n");
	                        output("`3Cosa vuoi fare ?");
	                        addnav("Città dei Draghi","terre_draghi.php?op=citta");
	                        addnav("Torna al Vulcano","terre_draghi.php?op=vulcano");
	                    }elseif($cento >= 86 AND $cento < 97){
	                        //generazione automatica draghi se ce ne sono meno di 10
	                        $sql = "SELECT * FROM draghi WHERE dove = 3 ORDER by rand()";
	                        $result = db_query($sql) or die(db_error(LINK));
	                        if (db_num_rows($result) < 10) {
	                            crea_drago(0,2,0,3,3);
	                            $sql = "SELECT * FROM draghi WHERE dove = 3 ORDER by rand()";
	                            $result = db_query($sql) or die(db_error(LINK));
	                        }
	                        //fine generazione automatica
	                        $row = db_fetch_assoc($result);
	                        output("`@`b`cUn temibile incontro!`b`c`n`n");
	                        $coloredrago = coloradrago($row['tipo_drago']);
	                        output("`5`bHai incontrato un drago solitario ".$row['eta_drago']." ".$coloredrago."".$row['tipo_drago']."`5`b:`n");
	                        $azione= mt_rand(1,3);
	                        if($azione!=1){
	                            output("`2Il drago sembra pacifico, e si sta probabilmente esercitando in quello che è il ");
	                            output("passatempo preferito dei draghi: oziare.`nCosa vuoi fare, attaccarlo sapendo quanto ");
	                            output("possa essere pericoloso disturbare un drago che dorme o tornare al vulcano e lasciar riposare ");
	                            output("il possente animale nel mezzo del suo sonno tranquillo? A te la scelta.`n`n");
	                            addnav("Attacca","terre_draghi.php?op=attacca&id_drago=$row[id]");
	                            addnav("Torna al Vulcano","terre_draghi.php?op=vulcano");
	                        }else{
	                            output("`4Il tuo arrivo ha interrotto il pisolino del drago. È risaputo che interrompere ");
	                            output("il sonno di un drago può essere estremamente pericoloso, ed in effetti le fiamme che ");
	                            output("fuoriescono dalle fauci del drago non promettono niente di buono.`nCosa decidi di fare, ");
	                            output("tentare una difesa contro l'attacco del drago o rifugiarti in una poco gloriosa fuga?`n`n");
	                            addnav("Difenditi","terre_draghi.php?op=attacca&id_drago=$row[id]");
	                            addnav("Scappa","terre_draghi.php?op=scappa&id_drago=$row[id]");
	                        }
	                    }elseif($cento >= 97 AND $cento < 99){
	                        $sql = "SELECT * FROM terre_draghi_mostri ORDER by rand() LIMIT 1 ";
	                        $result = db_query($sql) or die(db_error(LINK));
	                        $row = db_fetch_assoc($result);  
	                        $testo_incontro = $row[testo_incontro];                                    
	                        output("`@`b`cCadi in un agguato!`b`c`n`n");
	                        output("`2$testo_incontro");                          
	                        output("`n`nDue sono le opzioni possibili: affrontare questo temibile nemico in uno scontro all'ultimo sangue o tentare la fuga!`n`n");                       
							addnav("Difenditi","terre_draghi.php?op=attaccamostri&id_mostro=$row[id]");
	                        addnav("Scappa","terre_draghi.php?op=scappamostri&id_mostro=$row[id]");    
	                    }elseif($cento == 99){
	                        $sql = "SELECT * FROM terre_draghi WHERE stato = 'da scoprire' ORDER by rand()";
	                        $result = db_query($sql) or die(db_error(LINK));
	                        if (db_num_rows($result) == 0) {
	                            //generazione automatica draghi se ce ne sono meno di 10
	                            $sql = "SELECT * FROM draghi WHERE dove = 3 ORDER by rand()";
	                            $result = db_query($sql) or die(db_error(LINK));
	                            if (db_num_rows($result) < 10) {
	                                //crea_drago(1,3,1,5,3);
	                                crea_drago(1,3,6,9,3);
	                                $sql = "SELECT * FROM draghi WHERE dove = 3 ORDER by rand()";
	                                $result = db_query($sql) or die(db_error(LINK));
	                            }
	                            //fine generazione automatica
	                            $row = db_fetch_assoc($result);
	                            $coloredrago = coloradrago($row['tipo_drago']);
	                            output("`@`b`cUn temibile incontro!`b`c`n`n");
	                            output("`6Hai incontrato un $row[eta_drago] drago ".$coloredrago."".$row[tipo_drago]."`6 solitario:`n");
	                            $azione= mt_rand(1,4);
	                            if($azione!=1){
	                                output("`2Il drago sembra pacifico, e si sta probabilmente esercitando in quello che è il ");
	                                output("passatempo preferito dei draghi: oziare.`nCosa vuoi fare, attaccarlo sapendo quanto ");
	                                output("possa essere pericoloso disturbare un drago o tornare al vulcano e lasciar riposare ");
	                                output("il drago che dorme? A te la scelta.`n`n");
	                                addnav("Attacca","terre_draghi.php?op=attacca&id_drago=$row[id]");
	                                addnav("Torna al Vulcano","terre_draghi.php?op=vulcano");
	                            }else{
	                                output("`4Il tuo arrivo ha interrotto il pisolino del drago. È risaputo che interrompere ");
	                                output("il sonno di un drago può essere estremamente pericoloso, ed in effetti le fiamme che ");
	                                output("fuoriescono dalle fauci del drago non promettono niente di buono.`nCosa decidi di fare, ");
	                                output("tentare una difesa contro l'attacco del drago o rifugiarti in una poco gloriosa fuga?`n`n");
	                                addnav("Attacca","terre_draghi.php?op=attacca&id_drago=$row[id]");
	                                addnav("Scappa","terre_draghi.php?op=scappa&id_drago=$row[id]");
	                            }
	                        }else{
	                            $row = db_fetch_assoc($result);
	                            output("`^`b`c<font size='+1'>Hai trovato un Territorio Leggendario!!: ".$row['nome']."`b`c`n</font>",true);
	                            output("`^`b`c<font size='+1'>".$row['descrizione']."`b`c`n`n</font>",true);
	                            output("Hai scoperto uno dei mitici Territori Leggendari! Il possesso di tale territorio ");
	                            output("porta benefici notevoli, ma fai attenzione, altri esploratori agognano al possesso di tali ");
	                            output("territori, e dovrai difenderlo, anche a costo della tua stessa vita!!`n`n");
	                            debuglog("`ha scoperto `^".$row['nome']." `#esplorando nelle Terre dei Draghi");
	                            addnews("`#".$session['user']['name']." `3 ha scoperto un `^Territorio Leggendario`3 esplorando le Terre dei Draghi.");
	                            addnav("Prendi possesso","terre_draghi.php?op=possesso&id_terra=$row[id]");
	                            addnav("Torna al Vulcano","terre_draghi.php?op=vulcano");
	                            $sql = "UPDATE terre_draghi SET stato='scoperta' where id=$row[id]";
	                            db_query($sql) or die(db_error(LINK));
	                            switch ($session['user']['dio']) {
					            	case 1:
					                	savesetting("puntisgrios", getsetting("puntisgrios",0)+2500);
					           		break;
					                case 2:
					                	savesetting("puntikarnak", getsetting("puntikarnak",0)+2500);
					                break;
					                case 3:
					                	savesetting("puntidrago", getsetting("puntidrago",0)+2500);
					                break;
					            }
	                        }
	                    }
	                }
	            }else if($_GET['op']=="difesa_terra"){
	
	                output("`5`c`bDifesa Automatica del tuo Territorio Leggendario: $row[nome]`b`c`n`n`n");
	                $sql = "SELECT * FROM terre_draghi WHERE id='$_GET[id_terra]'";
	                $result = db_query($sql) or die(db_error(LINK));
	                $row = db_fetch_assoc($result);
	                $tot_dif=($row[attacco]+$row[difesa]+$row[vita]+1);
	                $costo_minima=500;
	                if ($_GET['az']=="attiva"){
	                    if ($session['user']['gems'] > 0 AND $session['user']['turns'] > 1) {
	                        output("`4`c`bHai attivato le difese!`b`c`n`n`n");
	                        $attacco = 10;
	                        $difesa = 10;
	                        $vita = 20;
	                        $sql = "UPDATE terre_draghi SET attacco='$attacco',difesa='$difesa',vita='$vita' where id=$row[id]";
	                        db_query($sql) or die(db_error(LINK));
	                        $session['user']['gems'] -= 1;
	                        debuglog("spende 1 gemma difesa terra dei draghi");
	                        $session['user']['turns'] -= 2;
	                    }else{
	                        output("`!`c`bNon hai la gemma necessaria oppure i turni necessari!`b`c`n`n`n");
	                    }
	                }
	                if ($_GET['az']=="minima"){
	                    if ($session['user']['gold'] >= $costo_minima AND $session['user']['turns'] > 0) {
	                        if($row['attacco']<50){
	                            $attacco=mt_rand(20,40);
	                        }
	                        if($row['attacco']>=50 AND $row['attacco']<100){
	                            $attacco=mt_rand(15,25);
	                        }
	                        if($row['attacco']>=100 AND $row['attacco']<150){
	                            $attacco=mt_rand(5,15);
	                        }
	                        if($row['attacco']>=150){
	                            $attacco=mt_rand(1,5);
	                        }
	                        if($row['difesa']<100){
	                            $difesa=mt_rand(20,40);
	                        }
	                        if($row['difesa']>=100 AND $row['difesa']<100){
	                            $difesa=mt_rand(15,25);
	                        }
	                        if($row['difesa']>=100 AND $row['difesa']<150){
	                            $difesa=mt_rand(5,15);
	                        }
	                        if($row['difesa']>=150){
	                            $difesa=mt_rand(1,5);
	                        }
	                        if($row['vita']<100){
	                            $vita=mt_rand(30,60);
	                        }
	                        if($row['vita']>=100 AND $row['vita']<350){
	                            $vita=mt_rand(25,50);
	                        }
	                        if($row['vita']>=350 AND $row['vita']<1000){
	                            $vita=mt_rand(15,30);
	                        }
	                        if($row['vita']>=1000){
	                            $vita=mt_rand(5,15);
	                        }
	                        output("`6Migliorato attacco: $attacco difesa: $difesa vita: $vita`n`n`n");
	                        $attacco+=$row['attacco'];
	                        $difesa+=$row['difesa'];
	                        $vita+=$row['vita'];
	                        $sql = "UPDATE terre_draghi SET attacco='$attacco',difesa='$difesa',vita='$vita' where id=$row[id]";
	                        db_query($sql) or die(db_error(LINK));
	                        $session['user']['gold'] -= $costo_minima;
	                        $session['user']['turns'] -= 1;
	                        debuglog("spende $costo_minima oro per difesa terra dei draghi");
	                    }else{
	                        output("`%Nessuna difesa automatica acquisita: non hai abbastanza oro o turni!!`n`n`n");
	                    }
	                }
	                $sql = "SELECT * FROM terre_draghi WHERE id='$_GET[id_terra]'";
	                $result = db_query($sql) or die(db_error(LINK));
	                $row = db_fetch_assoc($result);
	                output("<table cellspacing=0 cellpadding=2 align='center'><tr>
	                <td>&nbsp;</td><td>`b`#Attacco`b</td>
	                <td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td>
	                <td>`b`@Difesa`b</td><td>&nbsp;</td>
	                <td>`b`^Vita`b</td>",true);
	                output("</tr>", true);
	                output("<tr>
	                <td>&nbsp;</td><td>`b".$row['attacco']."`b</td>
	                <td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td>
	                <td>`b".$row['difesa']."`b</td><td>&nbsp;</td>
	                <td>`b".$row['vita']."`b</td>",true);
	                output("</tr>", true);
	                output("</table>", true);
	                if($row['totale_bonus']>0){
	                    if($row['vita']==0 AND $row['difesa']==0 AND $row['attacco']==0){
	                        addnav("Attiva difesa (1 Gemma 2 Turni)","terre_draghi.php?op=difesa_terra&az=attiva&id_terra=$_GET[id_terra]");
	                    }else{
	                        addnav("Potenzia difesa ($costo_minima Oro 1 Turno)","terre_draghi.php?op=difesa_terra&az=minima&id_terra=$_GET[id_terra]");
	                    }
	                }
	                addnav("Sorveglia la tua terra (Logout)","terre_draghi.php?op=sorveglia");
	                addnav("Torna al Vulcano","terre_draghi.php?op=vulcano");
	            }else if($_GET['op']=="possesso"){
	                if ($session['user']['turni_drago_rimasti'] > 0) {
	                    $sql = "SELECT * FROM terre_draghi WHERE id='$_GET[id_terra]'";
	                    $result = db_query($sql) or die(db_error(LINK));
	                    $row = db_fetch_assoc($result);
	                    if(ismultiaction("terre",$_GET['id_terra'])) {
	                         output("Stai interagendo con i tuoi draghi");
	                         addnav("Torna indietro","terre_draghi.php?op=importanti");
	                    }elseif ($row['totale_bonus'] > 0) {
	                        saveaction("terre",$_GET['id_terra'],3600);
	                        output("`&`b`cHai preso possesso del Territorio Leggendario chiamato: `S".$row['nome']."`b`c`n`n");
	                        output("`GTi sei impossessato del Territorio `S".$row['nome']."`G. Detenere questo luogo ti porterà ");
	                        output("notevoli benefici, ma attirerà anche l'invidia degli altri guerrieri, che tenteranno ");
	                        output("di strapparti il possesso di tale terra.`nCosa vuoi fare?`n");
	                        debuglog("ha preso possesso del Territorio Leggendario `^".$row['nome']);
	                        if ($session['user']['superuser'] == 0) {
	                            addnews("`3".$session['user']['name']." `3 ha preso possesso del `^Territorio Leggendario `3conosciuto come `^".$row['nome']."`3!!");
	                        }
	                        addnav("Q?Sorveglia la tua terra (`%Logout`0)","terre_draghi.php?op=sorveglia");
	                        addnav("D?Metti Difesa","terre_draghi.php?op=difesa_terra&id_terra=$_GET[id_terra]");
	                        addnav("V?Torna al Vulcano","terre_draghi.php?op=vulcano");
	                        addnav("A?Abbandona Terra","terre_draghi.php?op=abbandona&id_terra=$row[id]");
	                        //verifica se la terra appartiene ad un altro player e dagli la exp guadagnata
	                        if($row[id_player]!=0){
	                            $sql = "DELETE FROM terre_draghi_dove where id_terra='".$_GET[id_terra]."'";
	                            db_query($sql) or die(db_error(LINK));
	                            $sql = "UPDATE terre_draghi_storia SET tempo_fine='".time()."',data_fine=FROM_UNIXTIME('".time()."') where tempo_fine='0' AND id_terra='".$_GET[id_terra]."' AND conteggiato='no'";
	                            db_query($sql) or die(db_error(LINK));
	                            $sql = "SELECT * FROM terre_draghi_storia WHERE id_terra='".$_GET[id_terra]."' AND conteggiato='no'";
	                            $result = db_query($sql) or die(db_error(LINK));
	                            $rowtds = db_fetch_assoc($result);
	                            $tempo_permanenza=intval(($rowtds['tempo_fine']-$rowtds['tempo_inizio'])/3600);
	                            $guadagno=$rowtds['bonus_terra']*$tempo_permanenza;
	                            if($guadagno>$row['totale_bonus'])$guadagno=$row['totale_bonus'];
	                            if($guadagno!=0){
	                                $sql = "INSERT INTO terre_draghi_exp (id_player,punti_carriera,acquisiti)
	                                VALUES ('".$rowtds['id_player']."','$guadagno','no')";
	                                db_query($sql) or die(db_error($link));
	                                $totale_bonus=$row['totale_bonus']-$guadagno;
	                                $sql = "UPDATE terre_draghi SET id_player='0',totale_bonus='$totale_bonus' where id_player='".$row['id_player']."' AND id='".$_GET[id_terra]."'";
	                                db_query($sql) or die(db_error(LINK));
	                            }
	                            debuglog("`0ha preso il Territorio Leggendario `^".$row['nome']."`0 a",$row['id_player']);
	                            $mailmessage = "`^".$session['user']['name']."`2 ti ha rubato un territorio leggendario."
	                            ." `n`nControllando il territorio hai guadagnato $guadagno punti carriera.  `n`nNon pensi sia il momento di vendicarsi?";
	                            $mailmessage = str_replace("%o",($session['user']['sex']?"lei":"lui"),$mailmessage);
	                            systemmail($row['id_player'],"`2Hai perso un terreno.`2",$mailmessage);
	                            $sql = "UPDATE terre_draghi_storia SET conteggiato='si' where id_terra='".$_GET[id_terra]."' AND conteggiato='no'";
	                            db_query($sql) or die(db_error(LINK));
	                            $totale_bonus=$row['totale_bonus']-$guadagno;
	                            $sql = "UPDATE terre_draghi SET id_player='".$session['user']['acctid']."',totale_bonus=$totale_bonus where id='".$_GET[id_terra]."'";
	                            db_query($sql) or die(db_error(LINK));
	                        }else{
	                            $sql = "UPDATE terre_draghi SET id_player='".$session['user']['acctid']."' where id='".$_GET[id_terra]."'";
	                            db_query($sql) or die(db_error(LINK));
	                        }
	                        //setta posizione player
	                        $sql = "DELETE FROM terre_draghi_dove  WHERE id_player='".$session['user']['acctid']."'";
	                        db_query($sql) or die(db_error($link));
	                        $sql = "INSERT INTO terre_draghi_dove (id_terra,id_player)
	                    VALUES ('$row[id]','".$session['user']['acctid']."')";
	                        db_query($sql) or die(db_error($link));
	                        // fine settaggio posizione
	                        $sql = "INSERT INTO terre_draghi_storia (id_terra,id_player,tempo_inizio,data_inizio,bonus_terra,conteggiato)
	                    VALUES ('$row[id]','".$session['user']['acctid']."','".time()."',FROM_UNIXTIME('".time()."'),'$row[bonus]','no')";
	                        db_query($sql) or die(db_error($link));
	                        $sql = "UPDATE terre_draghi SET vita='0',attacco='0',difesa='0' WHERE id='".$_GET[id_terra]."'";
	                        db_query($sql) or die(db_error(LINK));
	                        $session['user']['turni_drago_rimasti'] -=1;
	                    }else{
	
	                        if($row[id_player]!=0){
	                            $sql = "DELETE FROM terre_draghi_dove where id_terra='".$_GET[id_terra]."'";
	                            db_query($sql) or die(db_error(LINK));
	                            $sql = "UPDATE terre_draghi_storia SET tempo_fine='".time()."',data_fine=FROM_UNIXTIME('".time()."') where tempo_fine='0' AND id_terra='".$_GET[id_terra]."' AND conteggiato='no'";
	                            db_query($sql) or die(db_error(LINK));
	                            $sql = "SELECT * FROM terre_draghi_storia WHERE id_terra='".$_GET[id_terra]."' AND conteggiato='no'";
	                            $result = db_query($sql) or die(db_error(LINK));
	                            $rowtds = db_fetch_assoc($result);
	                            $tempo_permanenza=intval(($rowtds['tempo_fine']-$rowtds['tempo_inizio'])/3600);
	                            $guadagno=$rowtds['bonus_terra']*$tempo_permanenza;
	                            if($guadagno>$row['totale_bonus'])$guadagno=$row['totale_bonus'];
	                            if($guadagno!=0){
	                                $sql = "INSERT INTO terre_draghi_exp (id_player,punti_carriera,acquisiti)
	                            VALUES ('".$rowtds['id_player']."','$guadagno','no')";
	                                db_query($sql) or die(db_error($link));
	                                $totale_bonus=$row['totale_bonus']-$guadagno;
	                                $sql = "UPDATE terre_draghi SET id_player='0',totale_bonus='$totale_bonus' where id_player='".$row['id_player']."' AND id='".$_GET[id_terra]."'";
	                                db_query($sql) or die(db_error(LINK));
	                            }
	                            $mailmessage = "`^".$session['user']['name']."`2 ti ha rubato un territorio leggendario."
	                            ." `n`nControllando il territorio hai guadagnato $guadagno punti carriera.  `n`nNon pensi sia il momento di vendicarsi?";
	                            $mailmessage = str_replace("%o",($session['user']['sex']?"lei":"lui"),$mailmessage);
	                            systemmail($row['id_player'],"`2Hai perso un terreno.`2",$mailmessage);
	                            $sql = "UPDATE terre_draghi_storia SET conteggiato='si' where id_terra='".$_GET[id_terra]."' AND conteggiato='no'";
	                            db_query($sql) or die(db_error(LINK));
	                            $totale_bonus=$row['totale_bonus']-$guadagno;
	                            $sql = "UPDATE terre_draghi SET id_player='".$session['user']['acctid']."',totale_bonus=$totale_bonus where id='".$_GET[id_terra]."'";
	                            db_query($sql) or die(db_error(LINK));
	                        }
	                        $time_out=time();
	                        addnav("Torna al Vulcano","terre_draghi.php?op=vulcano");
	                        output("Purtroppo il territorio che hai conquistato ha esaurito le risorse!");
	                        $sql = "UPDATE terre_draghi SET id_player='0',time_out='$time_out' WHERE id='".$_GET[id_terra]."'";
	                        db_query($sql) or die(db_error(LINK));
	                    }
	                }else{
	                    addnav("Torna al Vulcano","terre_draghi.php?op=vulcano");
	                    output("Il tuo drago ha esaurito le sue energie.");
	                }
	            }else if($_GET['op']=="sorveglia"){
	                output("`6Decidi di prenderti qualche istante per riposare. Chiudi gli occhi ma i tuoi sensi`n");
	                output("sono allertati, e sai che ogni rumore sospetto ti riporterebbe dallo stato di veglia`n");
	                output("in cui stai per piombare, alla piena efficenza. I tuoi avversari dovranno essere cauti`n`n");
	                debuglog("va a dormire nelle terre dei draghi");
	                /*
	                //Excalibur: registrazione dei tempi di connessione
	                // per chi è rimasto connesso per più di X ore (X = 2)
	                $check = getsetting("onlinetime",7200); //secondi oltre i quali viene registrato il tempo di connessione  7200 = 2 ore
	                if ( (strtotime($session['user']['laston']) - strtotime($session['user']['lastlogin']) ) > $check) {
	                   $sql1 = "INSERT INTO furbetti
	                            (type,acctid,logintime,logouttime)
	                            VALUES ('time','".$session['user']['acctid']."','".$session['user']['lastlogin']."','".$session['user']['laston']."')";
	                   $result1 = db_query($sql1) or die(db_error(LINK));
	                }
	                //Excalibur: fine
	                */
	                $session['user']['loggedin']=0;
	                $session['user']['sconnesso']=0;
	                $session['user']['location']=11;
	                $session['user']['locazione']=0;
	                $sql = "UPDATE accounts SET loggedin=0,sconnesso=0,location=11 WHERE acctid = ".$session[user][acctid];
	                db_query($sql) or die(sql_error($sql));
	                saveuser();
	                $session=array();
	                redirect("index.php");
	            }else if($_GET['op']=="abbandona"){
	                $sql = "SELECT * FROM terre_draghi WHERE id='".$_GET['id_terra']."'";
	                $result = db_query($sql) or die(db_error(LINK));
	                $row = db_fetch_assoc($result);
	                output("`6`bAbbandona terra`n");
	                $sql = "UPDATE terre_draghi_storia SET tempo_fine='".time()."',data_fine=FROM_UNIXTIME('".time()."') where tempo_fine='0' AND id_player='".$session['user']['acctid']."'AND id_terra='".$_GET[id_terra]."'";
	                db_query($sql) or die(db_error(LINK));
	                $sql = "SELECT * FROM terre_draghi_storia WHERE id_player='".$session['user']['acctid']."' and conteggiato='no' AND id_terra='".$_GET[id_terra]."'";
	                $result = db_query($sql) or die(db_error(LINK));
	                $rowtds = db_fetch_assoc($result);
	                $tempo_permanenza=intval(($rowtds['tempo_fine']-$rowtds['tempo_inizio'])/3600);
	                $guadagno=$rowtds['bonus_terra']*$tempo_permanenza;
	                // EXCALIBUR: Modifica per dare massimo bonus e non tempo permanenza * bonus/h
	                if ($session['user']['superuser'] > 3){
	                    print"Tempo Permanenza = ".$tempo_permanenza."<br>";
	                    print"Guadagno (per permanenza) = ".$guadagno."<br>";
	                    print"Guadagno (bonus terra) = ".$row['totale_bonus']."<br>";
	                }
	                if($guadagno>$row['totale_bonus'])$guadagno=$row['totale_bonus'];
	                // EXCALIBUR: Fine Modifica
	                $session['user']['punti_carriera'] += $guadagno;
	                $fama = $guadagno*$session['user']['fama_mod'];
	                $session['user']['fama3mesi'] += $fama;
	                $sqlgetname = "SELECT nome FROM terre_draghi WHERE id='".$_GET['id_terra']."'";
	                $resultgetname = db_query($sqlgetname) or die(db_error(LINK));
	                $rowgetname = db_fetch_assoc($resultgetname);
	                debuglog("abbandona `^".$rowgetname['nome']."`3 dopo $tempo_permanenza ore guadagnando $guadagno punti carriera");
	                debuglog("guadagna $fama punti fama. Ora ha ".$session['user']['fama3mesi']." punti");
	                $sql = "UPDATE terre_draghi_storia SET conteggiato='si' where id_player='".$session['user']['acctid']."' AND id_terra='".$_GET[id_terra]."'";
	                db_query($sql) or die(db_error(LINK));
	                //gestione posizione player
	                $sql = "DELETE FROM terre_draghi_dove  WHERE id_player='".$session['user']['acctid']."'";
	                db_query($sql) or die(db_error($link));
	                $sql = "INSERT INTO terre_draghi_dove (id_terra,id_player)
	                VALUES ('$row[id_terra]','".$session['user']['acctid']."')";
	                db_query($sql) or die(db_error($link));
	                output("`@Abbandoni la terra che hai controllato per `&$tempo_permanenza `@ore.`n`GGuadagni `g$guadagno `Gpunti carriera.`n");
	                $sql = "SELECT * FROM terre_draghi WHERE id='".$_GET['id_terra']."'";
	                $result = db_query($sql) or die(db_error(LINK));
	                $rowtd = db_fetch_assoc($result);
	                $totale_bonus=$rowtd['totale_bonus']-$guadagno;
	                $sql = "UPDATE terre_draghi SET id_player='0',totale_bonus='$totale_bonus' where id='".$_GET[id_terra]."'";
	                db_query($sql) or die(db_error(LINK));
	                addnav("Torna al Vulcano","terre_draghi.php?op=vulcano");
	            }else if($_GET['op']=="importanti"){
	                output("`^`b`cLuoghi Leggendari fino ad ora conosciuti`b`c`n`n");
	                output("<table cellspacing=0 cellpadding=2 align='center'><tr>
	                <td>&nbsp;</td><td>`b`@Nome`b</td>
	                <td>`b`#Bonus`b</td>
	                <td>`b`VOnore`b</td>
	                <td>&nbsp;</td>
	                <td>&nbsp;</td>
	                <td>`b`@Stato`b</td><td>&nbsp;</td>
	                <td>`b`^Avvicinati`b</td>",true);
	                if ($session['user']['superuser'] > 2) {
	                    output("<td>`b`#ID`b</td>",true);
	                }
	                output("</tr>", true);
	                $sql = "SELECT * FROM terre_draghi WHERE stato = 'scoperta' ORDER BY bonus desc";
	                $result = db_query($sql) or die(db_error(LINK));
	                if (db_num_rows($result) == 0) {
	                    output("<tr><td colspan=4 align='center'>`&Non sono stati ancora scoperti Territori Leggendari.`0</td></tr>", true);
	                }
	                $countrow = db_num_rows($result);
	                for ($i=0; $i<$countrow; $i++){
	                //for ($i = 0;$i < db_num_rows($result);$i++) {
	                    $row = db_fetch_assoc($result);
	                    if($row[id_player]==$session['user']['acctid']){
	                        if($row['vita']>0){
	                            $stato="`3Tua terra con difesa";
	                        }else{
	                            $stato="`3Tua terra senza difese";
	                        }
	                    }elseif ($row['id_player']!=0){
	                        $sql = "SELECT * FROM terre_draghi_dove WHERE id_terra='".$row['id']."'";
	                        $resultpla = db_query($sql) or die(db_error(LINK));
	                        $rowpla = db_fetch_assoc($resultpla);
	                        if ($rowpla['id_terra']!=0){
	                            $stato="`\$Occupato presente";
	                        }else{
	                            if($row[vita]>0){
	                                $stato="`7Occupato non presente con difesa";
	                            }else{
	                                $stato="`&Occupato non presente senza difese";
	                            }
	                        }
	                    }else{
	                        $stato="Libero";
	                    }
	                    $sql = "SELECT dio FROM accounts WHERE acctid='".$row['id_player']."'";
	                    $resultac = db_query($sql) or die(db_error(LINK));
	                    $rowac = db_fetch_assoc($resultac);
	                    if ($rowac[dio]==0){
	                        $dio="`vSe stesso";
	                    }elseif($rowac[dio]==1){
	                        $dio="`6Sgrios";
	                    }elseif($rowac[dio]==2){
	                        $dio="`$"."Karnak";
	                    }elseif($rowac[dio]==3){
	                        $dio="`@"."Drago";
	                    }
	                    if ($row[test]!=on){
	                        if($row['totale_bonus']==0 AND $row['id_player']==0){
	                            if($row[time_out]==0){
	                                $time_out=time();
	                                $sql = "UPDATE terre_draghi SET time_out='$time_out' WHERE id='".$row[id]."'";
	                                db_query($sql) or die(db_error(LINK));
	                            }else{
	                                $time_out=time();
	                                $time_random = mt_rand(1, 14600);  // per randomizzare da 1 a 4 ore 
	                                if (($time_out-$row[time_out])> ( 100000 - $time_random )){
	                                    $sql = "DELETE FROM terre_draghi  WHERE id='".$row[id]."'";
	                                    db_query($sql) or die(db_error($link));
	                                }
	                            }
	                            output("<tr class='" . ($i % 2?"trlight":"trdark") . "'><td align=right>" . ($i + 1) . ".</td>
	                        <td>`b`#".$row['nome']."`b</td>
	                        <td>`b`&".$row['bonus']."`b /ora</td>
	                        <td>$dio</td>
	                        <td>&nbsp;</td>
	                        <td>&nbsp;</td>
	                        <td align='center'>`b`@".$stato."`b</td><td>&nbsp;</td>
	                        <td>Esaurito</td>",true);
	                            if ($session['user']['superuser'] > 2) {
	                                output("<td>`b`#".$row['id']."`b</td>",true);
	                            }
	                            output("</tr>", true);
	                        }else{
	                            output("<tr class='" . ($i % 2?"trlight":"trdark") . "'><td align=right>" . ($i + 1) . ".</td>
	                        <td>`b`#".$row['nome']."`b</td>
	                        <td>`b`&".$row['bonus']."`b /ora</td>
	                        <td>$dio</td>
	                        <td>&nbsp;</td>
	                        <td>&nbsp;</td>
	                        <td align='center'>`b`@".$stato."`b</td><td>&nbsp;</td>
	                        <td><A href=terre_draghi.php?op=avvicinati&id_terra=$row[id]>`@Avvicinati</a></td>",true);
	                            if ($session['user']['superuser'] > 2) {
	                                output("<td>`b`#".$row['id']."`b</td>",true);
	                            }
	                            output("</tr>", true);
	                            addnav("", "terre_draghi.php?op=avvicinati&id_terra=$row[id]");
	                        }
	                    }else{
	                        if ($session['user']['superuser'] > 2) {
	                            output("<tr class='" . ($i % 2?"trlight":"trdark") . "'><td align=right>" . ($i + 1) . ".</td>
	                        <td>`b`#".$row['nome']."`b</td>
	                        <td>`b`&".$row['bonus']."`b /ora</td>
	                        <td>$dio</td>
	                        <td>&nbsp;</td>
	                        <td>&nbsp;</td>
	                        <td align='center'>`b`@".$stato."`b</td><td>&nbsp;</td>
	                        <td><A href=terre_draghi.php?op=avvicinati&id_terra=$row[id]>`@Avvicinati</a></td>",true);
	
	                            output("<td>`b`#".$row['id']."`b</td>",true);
	
	                            output("</tr>", true);
	                            addnav("", "terre_draghi.php?op=avvicinati&id_terra=$row[id]");
	                        }
	                    }
	                }
	                output("</table>", true);
	                addnav("Torna al Vulcano","terre_draghi.php?op=vulcano");
	            }else if($_GET['op']=="avvicinati"){
	                $sql = "SELECT * FROM terre_draghi WHERE id=$_GET[id_terra]";
	                $result = db_query($sql) or die(db_error(LINK));
	                $row = db_fetch_assoc($result);
	
	                output("`)`bTi avvicini al Territorio Leggendario conosciuto come `&".$row['nome']."`b`n`n");
	                if($row['id_player']==0){
	                    addnav("Prendi possesso","terre_draghi.php?op=possesso&id_terra=$row[id]");
	                    output("`3Entri nel Territorio Leggendario conosciuto come `#".$row['nome']."`3.");
	                    output("`2Questa terra, ".$row['descrizione'].", ha proprietà taumaturgiche ed è in grado di ");
	                    output("far guadagnare al suo possessore `(Punti Carriera`2, proporzionalmente al tempo ivi ");
	                    output("trascorso.`nMa fai attenzione, altri guerrieri sapranno della tua scoperta e tenteranno ");
	                    output("di strappartela.`n`n");
	                }elseif($row['id_player']==$session['user']['acctid']){
	                    //output("`6`bTi avvicini a $row[nome]`n");
	                    $sql = "SELECT tempo_inizio FROM terre_draghi_storia WHERE id_terra='".$row['id']."' AND id_player='$row[id_player]' AND conteggiato='no'";
	                    $result = db_query($sql) or die(db_error(LINK));
	                    $rows = db_fetch_assoc($result);
	                    $ore=intval((time()-$rows[tempo_inizio])/3600);
	                    $minuti=intval((time()-$rows[tempo_inizio]-($ore*3600))/60);
	                    output("`^`bQuesta terra è tua!`b`n`nIl suo possesso ti da diritto ad un bonus, che dipende dal ");
	                    output("tempo in cui ne sei in possesso.`n`n");
	                    output("`@ Stai occupando questa terra da ".$ore." ore e ".$minuti." minuti`n");
	                    output("`^`n`nCosa decidi di fare?`n`n");
	                    if($row['totale_bonus']>0){
	                        addnav("Q?Sorveglia la tua terra (`%Logout`0)","terre_draghi.php?op=sorveglia");
	                        addnav("Difendi Territorio","terre_draghi.php?op=difesa_terra&id_terra=$_GET[id_terra]");
	                        addnav("Abbandona Territorio","terre_draghi.php?op=abbandona&id_terra=$row[id]");
	                    }else{
	                        output("`\$`bQuesta terra purtroppo si è esaurita non ti resta altro da fare che abbandonarla!");
	                        addnav("Abbandona Territorio","terre_draghi.php?op=abbandona&id_terra=$row[id]");
	                    }
	                    //setta posizione player
	                    $sql = "DELETE FROM terre_draghi_dove  WHERE id_player='".$session['user']['acctid']."'";
	                    db_query($sql) or die(db_error($link));
	                    $sql = "INSERT INTO terre_draghi_dove (id_terra,id_player)
	                    VALUES ('$row[id]','".$session['user']['acctid']."')";
	                    db_query($sql) or die(db_error($link));
	                    // fine settaggio posizione
	                }else{
	                    $sql = "SELECT * FROM terre_draghi_dove WHERE id_terra='".$row['id']."'";
	                    $resultpla = db_query($sql) or die(db_error(LINK));
	                    if (db_num_rows($resultpla)!=0){
	                        $rowpla = db_fetch_assoc($resultpla);
	                        $sql = "SELECT * FROM draghi WHERE user_id='$row[id_player]'";
	                        $result = db_query($sql) or die(db_error(LINK));
	                        $rowd = db_fetch_assoc($result);
	                        $sql = "SELECT name,dio FROM accounts WHERE acctid='$row[id_player]'";
	                        $result = db_query($sql) or die(db_error(LINK));
	                        $rowa = db_fetch_assoc($result);
	                        output("`)Terra occupata da `&".$rowa['name']."`) con un ".$rowd['eta_drago']." di drago ".$rowd['tipo_drago']."`n");
	                        $nome_dio=array('Se stesso','Sgrios','Karnak','Drago');
	                        output("`# In nome di ".$nome_dio[$rowa[dio]]."`n");
	                        $sql = "SELECT tempo_inizio FROM terre_draghi_storia WHERE id_terra='".$row['id']."' AND id_player='$row[id_player]' AND conteggiato='no'";
	                        $result = db_query($sql) or die(db_error(LINK));
	                        if (db_num_rows($result) != 0){
	                            $rows = db_fetch_assoc($result);
	                            $ore=intval((time()-$rows[tempo_inizio])/3600);
	                            output("`@ Occupata da ".$ore." ore`n");
	                        }
	                        addnav("Attacca ( 1 td )","terre_draghi.php?op=attacca_terra&id_player=$row[id_player]&id_terra=$_GET[id_terra]");
	                        addnav("Usa soffio","terre_draghi.php?op=soffio&id_player=$row[id_player]&id_terra=$_GET[id_terra]");
	                        addnav("( 1 td + 1 pc )","");
	                    }else{
	                        if($row['vita']>0){
	                            $sql = "SELECT name,dio FROM accounts WHERE acctid='$row[id_player]'";
	                            $result = db_query($sql) or die(db_error(LINK));
	                            $rowa = db_fetch_assoc($result);
	                            output("`4Occupata da ".$rowa['name']." ma non presente.`n".$row['descrizione']."`n");
	                            $nome_dio=array('Se stesso','Sgrios','Karnak','Drago');
	                            output("`# In nome di ".$nome_dio[$rowa[dio]]."`n`n");
	                            output("`\$Questa terra ha delle difese posizionate pronte a combattere`n");
	                            addnav("Sorvola ( 1 turno )","terre_draghi.php?op=sorvola&id_player=$row[id_player]&id_terra=$_GET[id_terra]");
	                            addnav("Attacca ( 1 td )","terre_draghi.php?op=attacca_terra_auto&id_player=$row[id_player]&id_terra=$_GET[id_terra]");
	                            //if ($session['user']['superuser'] > 0)
	                            addnav("Attacca con soffio ( 1 td e 1 pc)","terre_draghi.php?op=attacca_terra_soffio&id_player=$row[id_player]&id_terra=$_GET[id_terra]");
	                            $sql = "SELECT tempo_inizio FROM terre_draghi_storia WHERE id_terra='".$row['id']."' AND id_player='$row[id_player]' AND conteggiato='no'";
	                            $result = db_query($sql) or die(db_error(LINK));
	                            $rows = db_fetch_assoc($result);
	                            $ore=intval((time()-$rows[tempo_inizio])/3600);
	                            output("`@ Occupata da ".$ore." ore`n");
	                        }else{
	                            addnav("Prendi possesso","terre_draghi.php?op=possesso&id_terra=$row[id]");
	                            $sql = "SELECT name,dio FROM accounts WHERE acctid='$row[id_player]'";
	                            $result = db_query($sql) or die(db_error(LINK));
	                            $rowa = db_fetch_assoc($result);
	                            $nome_dio=array('Se stesso','Sgrios','Karnak','Drago');
	                            output("`6Occupata da ".$rowa['name'].", ma non presente`n".$row['descrizione']."`n");
	                            output("`# In nome di ".$nome_dio[$rowa[dio]]."`n`n");
	                            output("`$ Questa terra non ha delle difese.`n");
	                            $sql = "SELECT tempo_inizio FROM terre_draghi_storia WHERE id_terra='".$row['id']."' AND id_player='$row[id_player]' AND conteggiato='no'";
	                            $result = db_query($sql) or die(db_error(LINK));
	                            $rows = db_fetch_assoc($result);
	                            $ore=intval((time()-$rows[tempo_inizio])/3600);
	                            output("`@ Occupata da ".$ore." ore`n");
	                        }
	                    }
	                }
	                addnav("Luoghi Importanti","terre_draghi.php?op=importanti");
	                addnav("Torna al Vulcano","terre_draghi.php?op=vulcano");
	            }else if($_GET['op']=="attacca_terra_soffio"){
	                if ($session['user']['turni_drago_rimasti'] > 0 AND $session['user']['cavalcare_drago'] >1){
	                    $sql = "SELECT * FROM draghi WHERE user_id = '".$session['user']['acctid']."'";
	                    $result = db_query($sql) or die(db_error(LINK));
	                    $rowd = db_fetch_assoc($result);
	                    $sql = "SELECT * FROM terre_draghi WHERE id = '".$_GET[id_terra]."'";
	                    $result = db_query($sql) or die(db_error(LINK));
	                    $row = db_fetch_assoc($result);
	                    $session['user']['turni_drago_rimasti']-=1;
	                    $session['user']['cavalcare_drago'] -=1;
	                    $danno_attacco=e_rand(intval($rowd[danno_soffio]/2),intval($rowd[danno_soffio]*2))+e_rand(1,20);
	                    $danno_difesa=e_rand(intval($rowd[danno_soffio]/2),intval($rowd[danno_soffio]*2))+e_rand(1,20);
	                    $danno_vita=e_rand(intval($rowd[danno_soffio]/4),intval($rowd[danno_soffio]));
	                    $attacco=$row[attacco]-$danno_attacco;
	                    $difesa=$row[difesa]-$danno_difesa;
	                    $vita=$row[vita]-$danno_vita;
	                    if($vita<0)$vita=0;
	                    if($attacco<0)$attacco=0;
	                    if($difesa<0)$difesa=0;
	                    $mailmessage = "`%".$session['user']['name']." `@ti ha attaccato con il soffio del suo drago nel territorio `6$row[nome] `@e hai perso `%att:`\$$danno_attacco `%dif:`\$$danno_difesa `%vita:`\$$danno_vita `@e ti restano `%att:`\$$attacco `%dif:`\$$difesa `%vita:`\$$vita";
	                    systemmail($_GET['id_player'],"`2Territorio sotto attacco.`2",$mailmessage);
	                    $sql = "UPDATE terre_draghi SET vita='$vita',attacco='$attacco',difesa='$difesa' WHERE id='".$_GET[id_terra]."'";
	                    db_query($sql) or die(db_error(LINK));
	                    output("`#Hai pesantemente danneggiato il nemico  !`n`nAtt:$danno_attacco `nDif:$danno_difesa `nVita:$danno_vita");
	                    debuglog("`0ha attaccato con soffio il Territorio Leggendario `^".$row['nome']."`0 di",$row['id_player']);
	                }else{
	                    output("`4Il tuo drago è troppo stanco, dovrai aspettare che si sia riposato prima di poter ");
	                    output("attaccare questo territorio.`n`n");
	                }
	                addnav("Luoghi Importanti","terre_draghi.php?op=importanti");
	            }else if($_GET['op']=="sorvola"){
	                $sql = "SELECT * FROM terre_draghi WHERE id = '".$_GET[id_terra]."'";
	                $result = db_query($sql) or die(db_error(LINK));
	                if ($session['user']['turns'] > 0){
	                    $session['user']['turns'] -=1;
	                    $result = db_query($sql) or die(db_error(LINK));
	                    $row = db_fetch_assoc($result);
	                    $attacco=$row[attacco];
	                    $difesa=$row[difesa];
	                    $vita=$row[vita];
	                    $attacco=mt_rand(intval($attacco*0.8), intval($attacco*1.2));
	                    $difesa=mt_rand(intval($difesa*0.8), intval($difesa*1.2));
	                    $vita=mt_rand(intval($vita*0.8), intval($vita*1.2));
	                    output("`# Forze attacco stimate : `$$attacco`n`n");
	                    output("`@ Forze difesa stimate : `$$difesa`n`n");
	                    output("`! Dimensione esercito stimata : `$$vita`n`n");
	                }else{
	                    output("`4Il tuo drago è troppo stanco, dovrai aspettare che si sia riposato prima di poter ");
	                    output("sorvolare questo territorio.`n`n");
	                }
	                addnav("Luoghi Importanti","terre_draghi.php?op=importanti");
	                addnav("Torna al Vulcano","terre_draghi.php?op=vulcano");
	            }else if($_GET['op']=="attacca_terra_auto"){
	                    if(ismultiaction("terre",$_GET['id_terra'])) {
	                    output("Stai forse interagendo con i draghi dei tuoi pg??");
	                    addnav("Torna indietro","terre_draghi.php?op=importanti");
	                    }
	                    elseif ($session['user']['turni_drago_rimasti'] > 0){
	                    $sql = "SELECT * FROM draghi WHERE user_id = '".$session['user']['acctid']."'";
	                    saveaction("terre",$_GET['id_terra'],3600);
	                         $result = db_query($sql) or die(db_error(LINK));
	                    $rowd = db_fetch_assoc($result);
	                    $sql = "SELECT * FROM terre_draghi WHERE id = '".$_GET[id_terra]."'";
	                    $result = db_query($sql) or die(db_error(LINK));
	                    $row = db_fetch_assoc($result);
	                    $session['user']['turni_drago_rimasti'] -=1;
	                    //rowd dati drago player
	                    $caso_player_att = mt_rand(1, 20);
	                    $caso_player_dif = mt_rand(1, 20);
	                    $caso_danno_att=mt_rand(intval($rowd['danno_soffio']/2), $rowd['danno_soffio']);
	                    $attacco_drago_player= intval($session['user']['cavalcare_drago']/2)+intval($rowd['carattere']/2)+$rowd['attacco']+$caso_player_att;
	                    $difesa_drago_player=intval($session['user']['cavalcare_drago']/2)+intval($rowd['carattere']/2)+$rowd['difesa']+$caso_player_dif;
	                    if($rowd['combatte']=="terra"){
	                        $attacco_drago_player=$attacco_drago_player+$rowd['bonus'];
	                        $difesa_drago_player=$difesa_drago_player+$rowd['bonus'];
	                    }
	                    $caso_cpu_att = mt_rand(1, 20);
	                    $caso_cpu_dif = mt_rand(1, 20);
	                    $attacco_drago_cpu=$row['attacco']+$caso_cpu_att;
	                    $difesa_drago_cpu=$row['difesa']+$caso_cpu_dif;
	                    $danni_player=$attacco_drago_player-$difesa_drago_cpu;
	                    $danni_cpu=$attacco_drago_cpu-$difesa_drago_player;
	                    if ($danni_player<0){
	                        $danni_player=0;
	                    }
	                    if ($danni_cpu<0){
	                        $danni_cpu=0;
	                    }
	                    $danni_player+=$caso_danno_att;
	                    if($danni_player>$danni_cpu){
	                        $exp_drago=0;
	                        output("`^Hai vinto lo scontro causando `b`\$$danni_player danni`^`b e subendo `b`@$danni_cpu danni`b`^.`n");
	                        output("Inoltre hai guadagnato `b`%$exp_drago punti`^`b Abilità Cavalcare Draghi!`n");
	                        output("`6Player: `^$attacco_drago_player/$difesa_drago_player `2cpu: `@$attacco_drago_cpu/$difesa_drago_cpu`n");
	                        $vita_persa=(2*$danni_player);
	                        $vita_restante=$row['vita']-$vita_persa;
	                        $attacco_perso=$danni_player+e_rand(10,20);
	                        $attacco=$row['attacco']-$attacco_perso;
	                        $difesa_persa=$danni_player+e_rand(10,20);
	                        $difesa=$row['difesa']-$difesa_persa;
	                        if($vita_restante<0)$vita_restante=0;
	                        if($attacco<0)$attacco=0;
	                        if($difesa<0)$difesa=0;
	                        $mailmessage = "`%".$session['user']['name']." `@ti ha attaccato con il suo drago nel territorio `6$row[nome] `@e sconfitto hai perso `%att:$attacco_perso `%dif:`\$$difesa_persa `%vita:`\$$vita_persa `@e ti restano `%att:`\$$attacco `%dif:`\$$difesa `%vita:`\$$vita_restante";
	                        systemmail($_GET['id_player'],"`2Territorio sotto attacco.`2",$mailmessage);
	                        $sql = "UPDATE terre_draghi SET vita='$vita_restante',attacco='$attacco',difesa='$difesa' WHERE id='".$_GET[id_terra]."'";
	                        db_query($sql) or die(db_error(LINK));
	                        $vita_restante=$rowd[vita_restante]-$danni_cpu;
	                        $sql = "UPDATE draghi SET vita_restante='$vita_restante' WHERE user_id='".$session['user']['acctid']."'";
	                        db_query($sql) or die(db_error(LINK));
	                        debuglog("`0ha attaccato vincendo il Territorio Leggendario `^".$row['nome']."`0 di",$row['id_player']);
	                    }elseif($danni_player<=$danni_cpu){
	                        output("`5Hai perso lo scontro causando `b`\$$danni_player danni`^`b e subendo `b`@$danni_cpu danni`b`^.`n");
	                        output("`6Player: `^$attacco_drago_player/$difesa_drago_player `2cpu: `@$attacco_drago_cpu/$difesa_drago_cpu`n");
	                        $vita_restante=$rowd['vita_restante']-$danni_cpu;
	                        $sql = "UPDATE draghi SET vita_restante='$vita_restante' WHERE user_id='".$session['user']['acctid']."'";
	                        db_query($sql) or die(db_error(LINK));
	                        //if($vita_restante<=0)$danni_player=$danni_player*2;//da rimuovere dopo equilibrio
	                        $vita_restante=$row['vita']-$danni_player;
	                        $attacco_perso=$danni_player+e_rand(1,10);
	                        $attacco=$row['attacco']-$attacco_perso;
	                        $difesa_persa=$danni_player+e_rand(1,10);
	                        $difesa=$row['difesa']-$attacco_perso;
	                        if($vita_restante<0)$vita_restante=0;
	                        if($attacco<0)$attacco=0;
	                        if($difesa<0)$difesa=0;
	                        $sql = "UPDATE terre_draghi SET vita='$vita_restante',attacco='$attacco',difesa='$difesa' WHERE id='".$_GET[id_terra]."'";
	                        db_query($sql) or die(db_error(LINK));
	                        $mailmessage = "`%".$session['user']['name']." `@ti ha attaccato con il suo drago nel territorio `6$row[nome] `@e hai vinto, le tue forze hanno subito questi danni `%att:`\$$attacco_perso `%dif:`\$$difesa_persa `%vita:`\$$danni_player `@e ti restano `%att:`\$$attacco `%dif:`\$$difesa `%vita:`\$$vita_restante";
	                        systemmail($_GET['id_player'],"`2Territorio sotto attacco.`2",$mailmessage);
	                        debuglog("`0ha attaccato perdendo il Territorio Leggendario `^".$row['nome']."`0 di",$row['id_player']);
	                    }
	                    $sql = "SELECT * FROM terre_draghi WHERE id = '".$_GET[id_terra]."'";
	                    $result = db_query($sql) or die(db_error(LINK));
	                    $row = db_fetch_assoc($result);
	                    if($row['vita']<=0){
	                        output("`%Dopo una strenua lotta in cui le difesa avversarie sembravano prevalere, sei infine riuscit".($session[user][sex]?"a":"o")." ");
	                        output("ad annientare le forze posizionate a difesa del territorio nemico!`n Puoi ora acquisirne il possesso!`n");
	                        $sql = "UPDATE terre_draghi SET vita='0',attacco='0',difesa='0' WHERE id='".$_GET[id_terra]."'";
	                        db_query($sql) or die(db_error(LINK));
	                        addnav("Prendi possesso","terre_draghi.php?op=possesso&id_terra=$_GET[id_terra]");
	                        debuglog("`0ha annientato le difese del Territorio Leggendario `^".$row['nome']."`0 di",$row['id_player']);
	                    }
	                    $sql = "SELECT * FROM draghi WHERE user_id = '".$session['user']['acctid']."'";
	                    $result = db_query($sql) or die(db_error(LINK));
	                    $rowd = db_fetch_assoc($result);
	                    if($rowd['vita_restante'] <=0){
	                        if(e_rand(1,20)==1){
	                            if ($rowd['aspetto']=='Ottimo'){
	                                if(e_rand(1,5)==1){
	                                    $sql = "UPDATE draghi SET aspetto='Buono' WHERE user_id='".$session['user']['acctid']."'";
	                                    db_query($sql) or die(db_error(LINK));
	                                    $testo ="diventando di aspetto buono";
	                                }else{
	                                    $testo="fortunatamente le ferite ricevute non hanno provocato un peggioramento delle sue caratteristiche";
	                                }
	                            }elseif ($rowd['aspetto']=='Buono'){
	                                if(e_rand(1,5)==1){
	                                    $sql = "UPDATE draghi SET aspetto='Normale' WHERE user_id='".$session['user']['acctid']."'";
	                                    db_query($sql) or die(db_error(LINK));
	                                    $testo ="diventando di aspetto normale";
	                                }else{
	                                    $testo="fortunatamente le ferite ricevute non hanno provocato un peggioramento delle sue caratteristiche";
	                                }
	                            }elseif ($rowd['aspetto']=='Normale'){
	                                if(e_rand(1,5)==1){
	                                    $sql = "UPDATE draghi SET aspetto='Brutto' WHERE user_id='".$session['user']['acctid']."'";
	                                    db_query($sql) or die(db_error(LINK));
	                                    $testo ="diventando di aspetto brutto";
	                                }else{
	                                    $testo="fortunatamente le ferite ricevute non hanno provocato un peggioramento delle sue caratteristiche";
	                                }
	                            }elseif ($rowd['aspetto']=='Brutto'){
	                                if(e_rand(1,5)==1){
	                                    $sql = "UPDATE draghi SET aspetto='Pessimo' WHERE user_id='".$session['user']['acctid']."'";
	                                    db_query($sql) or die(db_error(LINK));
	                                    $testo ="diventando di aspetto pessimo";
	                                }else{
	                                    $testo="fortunatamente le ferite ricevute non hanno provocato un peggioramento delle sue caratteristiche";
	                                }
	                            }elseif ($rowd['aspetto']=='Pessimo'){
	                                $testo="fortunatamente le ferite ricevute non hanno provocato un peggioramento delle sue caratteristiche";
	                            }
	                        }else{
	                            $testo="fortunatamente le ferite ricevute non hanno provocato un peggioramento delle sue caratteristiche";
	                        }
	                        output("`)Il tuo drago è stato `\$$testo1`) ucciso durante l'ultimo attacco $testo!`n");
	                        $mailmessage = "`@Hai ucciso il drago di `%".$session['user']['name']." mentre lui ti stava attaccando!";
	                        $mailmessage = str_replace("%o",($session['user']['sex']?"lei":"lui"),$mailmessage);
	                        systemmail($_GET['id_player'],"`2Hai ucciso un drago.`2",$mailmessage);
	                        addnav("Torna al Villaggio","village.php");
	                    }else{
	                        //addnav("Prendi Territorio","terre_draghi.php?op=avvicinati&id_terra=$_GET[id_terra]");
	                        addnav("Torna al Vulcano","terre_draghi.php?op=vulcano");
	                    }
	                }else{
	                    output("`4Il tuo drago è troppo stanco, dovrai aspettare che si sia riposato prima di poter ");
	                    output("attaccare questo territorio.`n`n");
	                    addnav("Torna al Vulcano","terre_draghi.php?op=vulcano");
	                }
	            }else if($_GET['op']=="attacca"){
	                //Attacco contro draghi incontarti casualmente in esplorazione
	                $sql = "SELECT * FROM draghi WHERE id = '".$_GET[id_drago]."'";
	                $result = db_query($sql) or die(db_error(LINK));
	                $row = db_fetch_assoc($result);
	                //rowd dati drago player
	                $caso_player_att = mt_rand(1, 20);
	                $caso_player_dif = mt_rand(1, 20);
	                $caso_danno_pla=mt_rand(intval($rowd['danno_soffio']/2), $rowd['danno_soffio']);
	                $attacco_drago_player= intval($session['user']['cavalcare_drago']/2)+intval($rowd['carattere']/2)+$rowd['attacco']+$caso_player_att;
	                $difesa_drago_player=intval($session['user']['cavalcare_drago']/2)+intval($rowd['carattere']/2)+$rowd['difesa']+$caso_player_dif;
	                if($rowd['combatte']=="volo"){
	                    $attacco_drago_player=$attacco_drago_player+$rowd['bonus'];
	                    $difesa_drago_player=$difesa_drago_player+$rowd['bonus'];
	                }
	                $caso_cpu_att = mt_rand(1, 20);
	                $caso_cpu_dif = mt_rand(1, 20);
	                $caso_danno_cpu=mt_rand(intval($row['danno_soffio']/2), $row['danno_soffio']);
	                $attacco_drago_cpu=$row['carattere']+$row['attacco']+$caso_cpu_att;
	                $difesa_drago_cpu=$row['carattere']+$row['difesa']+$caso_cpu_dif;
	                if($row['combatte']=="volo"){
	                    $attacco_drago_cpu=$attacco_drago_cpu+$row['bonus'];
	                    $difesa_drago_cpu=$difesa_drago_cpu+$row['bonus'];
	                }
	                $danni_player=$attacco_drago_player-$difesa_drago_cpu+$caso_danno_pla;
	                $danni_cpu=$attacco_drago_cpu-$difesa_drago_player+$caso_danno_cpu;
	                if ($danni_player<0){
	                    $danni_player=0;
	                }
	                if ($danni_cpu<0){
	                    $danni_cpu=0;
	                }
	                if($danni_player>$danni_cpu){
	                    output("`^Hai vinto lo scontro causando `b`\$$danni_player danni`^`b e subendo `b`@$danni_cpu danni`b`^.`n");
	                    $caso_premio=mt_rand(1, 80);
	                    if ($caso_premio > 21 ){
	                        output("`6`b`c`nPurtroppo il drago non aveva nulla!`b`c`n`n");
	                        addnews("`\$".$session['user']['name']." `2sconfigge un drago in combattimento.");
	                    }elseif($caso_premio >= 2 AND $caso_premio < 15){
	                        $oro= mt_rand(100, 500);
	                        output("`6`b`c`nHai trovato un po' d'oro`b`c`n`n");
	                        output("`#Il drago aveva `^$oro pezzi d'oro!!`n`n");
	                        $session['user']['gold'] += $oro;
	                        debuglog("trova $oro oro nelle terre dei draghi");
	                        addnews("`\$".$session['user']['name']." `2 trova `^$oro pezzi d'oro `2Sconfiggendo un drago in combattimento.");
	                    }elseif($caso_premio >= 15 AND $caso_premio < 19){
	                        $oro= mt_rand(1000, 5000);
	                        output("`6`b`c`nHai trovato tanto oro`b`c`n`n");
	                        output("`#Il drago aveva `^$oro pezzi d'oro!!`n`n");
	                        $session['user']['gold'] += $oro;
	                        debuglog("trova $oro oro nelle terre dei draghi");
	                        addnews("`\$".$session['user']['name']." `2 trova `^$oro pezzi d'oro `2Sconfiggendo un drago in combattimento.");
	                    }elseif($caso_premio == 19 AND !zainoPieno($idplayer)){
	                        $sqli="INSERT INTO zaino (idoggetto, idplayer) VALUES (10, '{$session['user']['acctid']}')";
	                        db_query($sqli) or die(db_error(LINK));
	                        output("`6`b`c`nHai staccato una scaglia al drago !! Vale moltissimo oro !!`b`c`n`n");
	                        debuglog("trova una scaglia di drago oro nelle terre dei draghi");
	                        addnews("`\$".$session['user']['name']." `2 trova `^una scaglia di drago! `2Sconfiggendo un drago in combattimento.");
	                    }elseif($caso_premio == 19 AND zainoPieno($idplayer)){
	                        output("`6`b`c`nHai staccato una scaglia al drago !! Vale moltissimo oro !!`b`c`n`n");
	                        output("`^Peccato tu non abbia posto nello zaino. Ti conviene vendere qualcosa e liberare dello spazio!!!`n`n");
	                    }elseif($caso_premio == 20 OR $caso_premio == 21){
	                        output("`6`b`c`nHai guadagnato 1 punto abilità cavalcare draghi !!`b`c`n`n");
	                        debuglog("migliora di 1 cavalcare draghi");
	                        $session['user']['cavalcare_drago']+=1;
	                        addnews("`\$".$session['user']['name']." `2Sconfigge un drago in combattimento.");
	                    }elseif($caso_premio == 1){
	                        output("`6`b`c`nHai guadagnato 2 punti abilità cavalcare draghi !!`b`c`n`n");
	                        debuglog("migliora di 2 cavalcare draghi");
	                        $session['user']['cavalcare_drago']+=2;
	                        addnews("`\$".$session['user']['name']." `2Sconfigge un drago in combattimento.");
	                    }
	                    output("`n`n`6Player: `^$attacco_drago_player/$difesa_drago_player `2cpu: `@$attacco_drago_cpu/$difesa_drago_cpu`n");
	                    $vita_restante=$rowd['vita_restante']-$danni_cpu;
	                    $sql = "UPDATE draghi SET vita_restante='$vita_restante' WHERE user_id='".$session['user']['acctid']."'";
	                    db_query($sql) or die(db_error(LINK));
	                    if  ($_GET[id_drago] = $session['user']['id_drago']) {
	                    	// non si fa nulla perchè si tratta di un mostro che non esiste nella tabella draghi
						}else{		
	                    	$sql = "DELETE from draghi WHERE id= '".$_GET[id_drago]."'";
	                        db_query($sql) or die(db_error(LINK));    
	                    }

	
	                }elseif($danni_player<=$danni_cpu){
	                    output("`5Hai perso lo scontro causando `b`\$$danni_player danni`^`b e subendo `b`@$danni_cpu danni`b`^.`n");
	                    output("`6Player: `^$attacco_drago_player/$difesa_drago_player `2cpu: `@$attacco_drago_cpu/$difesa_drago_cpu`n");
	                    $vita_restante=$rowd['vita_restante']-$danni_cpu;
	                    $sql = "UPDATE draghi SET vita_restante='$vita_restante' WHERE user_id='".$session['user']['acctid']."'";
	                    db_query($sql) or die(db_error(LINK));
	                }
	                $sql = "SELECT * FROM draghi WHERE user_id = '".$session['user']['acctid']."'";
	                $result = db_query($sql) or die(db_error(LINK));
	                $rowd = db_fetch_assoc($result);
	                if($rowd['vita_restante'] <=0){
	                    if ($rowd['aspetto']=='Ottimo'){
	                        $sql = "UPDATE draghi SET aspetto='Buono' WHERE user_id='".$session['user']['acctid']."'";
	                        db_query($sql) or die(db_error(LINK));
	                        $testo ="diventando di aspetto buono";
	                    }elseif ($rowd['aspetto']=='Buono'){
	                        $sql = "UPDATE draghi SET aspetto='Normale' WHERE user_id='".$session['user']['acctid']."'";
	                        db_query($sql) or die(db_error(LINK));
	                        $testo ="diventando di aspetto normale";
	                    }elseif ($rowd['aspetto']=='Normale'){
	                        $sql = "UPDATE draghi SET aspetto='Brutto' WHERE user_id='".$session['user']['acctid']."'";
	                        db_query($sql) or die(db_error(LINK));
	                        $testo ="diventando di aspetto brutto";
	                    }elseif ($rowd['aspetto']=='Brutto'){
	                        $sql = "UPDATE draghi SET aspetto='Pessimo' WHERE user_id='".$session['user']['acctid']."'";
	                        db_query($sql) or die(db_error(LINK));
	                        $testo ="diventando di aspetto pessimo";
	                    }elseif ($rowd['aspetto']=='Pessimo'){
	                        $caso_morte = mt_rand(1, 20);
	                        if ($caso_morte ==1){
	                            $sql = "DELETE from draghi WHERE user_id='".$session['user']['acctid']."'";
	                            db_query($sql) or die(db_error(LINK));
	                            $session['user']['id_drago']=0;
	                            $testo1 ="definitivamente";
	                        }else{
	                            $testo1 ="miseramente";
	                        }
	
	                    }
	                    output("`)Il tuo drago è stato `\$$testo1`) ucciso durante l'ultimo attacco $testo!`n");
	                    addnav("Torna al Villaggio","village.php");
	                }else{
	                    addnav("Torna al Vulcano","terre_draghi.php?op=vulcano");
	                }
	            }else if($_GET['op']=="scappa"){
	                $caso=mt_rand(1, 3);
	                if ($caso==3){
	                    addnav("Torna al Vulcano","terre_draghi.php?op=vulcano");
	                    output("`6Sei riuscit".($session[user][sex]?"a":"o")." a scappare`n");
	                }else{
	                    addnav("Attacca","terre_draghi.php?op=attacca&id_drago=$_GET[id_drago]");
	                    output("`6Non sei riuscit".($session[user][sex]?"a":"o")." a scappare`n");
	                }
	            }else if($_GET['op']=="attaccamostri"){
	                //Attacco contro mostri incontrati casualmente in esplorazione
	                $id_mostro = $_GET[id_mostro] ;                       
	                output("`@`b`cCadi in un agguato! `b`c`n`n");
	                $sql = "SELECT * FROM terre_draghi_mostri WHERE id = $id_mostro";
	                $result = db_query($sql) or die(db_error(LINK));
	                $row = db_fetch_assoc($result);
	
	                //rowd dati drago player
	                // viene generato il mostro prendendo le stesse caratteristiche del drago del giocatore
	                $row['danno_soffio'] = $rowd['danno_soffio'];
	                $row['difesa'] = $rowd['difesa'];
	                $row['attacco'] = $rowd['attacco'];
	                $row['carattere'] = $rowd['carattere'];
	                $row['bonus'] = $rowd['bonus'] ;
	                
	                $caso_player_att = mt_rand(1, 20);
	                $caso_player_dif = mt_rand(1, 20);
	                $caso_danno_pla=mt_rand(intval($rowd['danno_soffio']/2), $rowd['danno_soffio']);
	                $attacco_drago_player= intval($session['user']['cavalcare_drago']/2)+intval($rowd['carattere']/2)+$rowd['attacco']+$caso_player_att;
	                $difesa_drago_player=intval($session['user']['cavalcare_drago']/2)+intval($rowd['carattere']/2)+$rowd['difesa']+$caso_player_dif;
	                if($rowd['combatte']=="volo"){
	                    $attacco_drago_player=$attacco_drago_player+$rowd['bonus'];
	                    $difesa_drago_player=$difesa_drago_player+$rowd['bonus'];
	                }
	                $caso_cpu_att = mt_rand(1, 20);
	                $caso_cpu_dif = mt_rand(1, 20);
	                $caso_danno_cpu=mt_rand(intval($row['danno_soffio']/2), $row['danno_soffio']);
	                $attacco_drago_cpu=$row['carattere']+$row['attacco']+$caso_cpu_att;
	                $difesa_drago_cpu=$row['carattere']+$row['difesa']+$caso_cpu_dif;
	                if($row['combatte']=="volo"){
	                    $attacco_drago_cpu=$attacco_drago_cpu+$row['bonus'];
	                    $difesa_drago_cpu=$difesa_drago_cpu+$row['bonus'];
	                }
	                $danni_player=$attacco_drago_player-$difesa_drago_cpu+$caso_danno_pla;
	                $danni_cpu=$attacco_drago_cpu-$difesa_drago_player+$caso_danno_cpu;
	                if ($danni_player<0){
	                    $danni_player=0;
	                }
	                if ($danni_cpu<0){
	                    $danni_cpu=0;
	                }
	
	                if($danni_player >= $danni_cpu){
	                	$testo_vittoria = $row['testo_vittoria'];
	                	$tipo_soffio = $rowd['tipo_soffio'];
	                	$tipo_soffio = "`#".$tipo_soffio."`2";
	                	$testo_vittoria = str_replace("%tipo_soffio",$tipo_soffio,$testo_vittoria);	
	                	$mostro = $row['nome_mostro'];
	                	output(" $testo_vittoria ");
	                	output("`n`nHai vinto lo scontro con $mostro, il tuo `@Drago`2 ha procurato `b`@$danni_player danni`2`b al nemico e ha subito la perdita di `b`\$$danni_cpu `2punti vita`b.`n");
	                	$caso_premio=mt_rand(1, 100);
	                    if ($caso_premio > 60 ){
	                        output("`2Il tuo nemico non aveva nulla di valore che valesse la pena di raccogliere!");
	                        addnews(" ".$session['user']['name']." `2 ha sconfitto $mostro in combattimento.");
	                    }elseif($caso_premio > 40 AND $caso_premio < 61){
	                        $oro= mt_rand(100, 500);
	                        output("`2Hai trovato un pò di `^oro`2 : il tuo nemico custodiva un piccolo tesoro di `^$oro`2 pezzi d'`^oro`2!");
	                        $session['user']['gold'] += $oro;
	                        debuglog("trova $oro oro nelle terre dei draghi");
	                        addnews(" ".$session['user']['name']." `2 trova `^$oro`2 pezzi d'`^oro`2 Sconfiggendo $mostro in combattimento.");
	                    }elseif($caso_premio > 30 AND $caso_premio < 41){
	                        $oro= mt_rand(1000, 5000);
	                        output("`2Hai trovato molto `^oro`2: il tesoro che il tuo nemico custodiva ammonta a ben `^$oro `2 pezzi d'`^oro`2!`n`n");
	                        $session['user']['gold'] += $oro;
	                        debuglog("trova $oro oro nelle terre dei draghi");
	                        addnews(" ".$session['user']['name']." `2 trova `^$oro`2 pezzi d'`^oro`2 `2Sconfiggendo $mostro in combattimento.");
	                    }elseif($caso_premio > 20 AND $caso_premio < 31 AND !zainoPieno($idplayer)){
	                        $sqli="INSERT INTO zaino (idoggetto, idplayer) VALUES (10, '{$session['user']['acctid']}')";
	                        db_query($sqli) or die(db_error(LINK));
	                        output("`2Il tuo nemico custodiva una scaglia di `@Drago`2 che raccogli velocemente e riponi nel tuo zaino.");
	                        debuglog("trova una scaglia di drago oro nelle terre dei draghi");
	                        addnews(" ".$session['user']['name']." `2 trova una scaglia di `@Drago`2 sconfiggendo $mostro in combattimento.");
	                    }elseif($caso_premio > 9 AND $caso_premio < 21 AND zainoPieno($idplayer)){
	                        output("`2Il tuo nemico custodiva una scaglia di `@Drago`2! Peccato tu non abbia posto nello zaino e la debba buttare. Ti conviene vendere qualcosa e liberare dello spazio per poter raccogliere e conservare gli oggetti di maggior pregio.");
	                    }elseif($caso_premio > 3 AND $caso_premio < 10){
	                        output("`2Hai migliorato la tua abilità con i `@Draghi`2 : il duro scontro contro il tuo nemico ha accresciuto le tue capacità di `@Cavaliere dei Draghi`2 di `#1 punto`2 !");
	                        debuglog("migliora di 1 cavalcare draghi");
	                        $session['user']['cavalcare_drago']+=1;
	                        addnews(" ".$session['user']['name']." `2Ha sconfitto $mostro in combattimento.");
	                    }elseif($caso_premio > 0 AND $caso_premio < 4){
	                        output("`2Hai migliorato la tua abilità con i `@Draghi`2 : il duro scontro contro il tuo nemico ha accresciuto le tue capacità di `@Cavaliere dei Draghi`2 di `#2 punti`2 !");
	                        debuglog("migliora di 2 cavalcare draghi");
	                        $session['user']['cavalcare_drago']+=2;
	                        addnews(" ".$session['user']['name']." `2Ha sconfitto $mostro in combattimento.");
	                    }
	                    if ($session['user']['superuser'] > 1){ 
	                    	output("`n`n`6Player: `^$attacco_drago_player/$difesa_drago_player `2cpu: `@$attacco_drago_cpu/$difesa_drago_cpu`n");
	                    }
	                    $vita_restante=$rowd['vita_restante']-$danni_cpu;
	                    $sql = "UPDATE draghi SET vita_restante='$vita_restante' WHERE user_id='".$session['user']['acctid']."'";
	                    db_query($sql) or die(db_error(LINK));
	                    if  ($_GET[id_drago] = $session['user']['id_drago']) {
	                    	// non si fa nulla perchè si tratta di un mostro che non esiste nella tabella draghi
						}else{		
	                    	$sql = "DELETE from draghi WHERE id= '".$_GET[id_drago]."'";
	                        db_query($sql) or die(db_error(LINK));    
	                    }
	                }elseif($danni_player < $danni_cpu){              	
	                	$testo_sconfitta = $row['testo_sconfitta'];	
	                	$tipo_soffio = $rowd['tipo_soffio'];
	                	$tipo_soffio = "`#".$tipo_soffio."`2";
	                	$testo_sconfitta = str_replace("%tipo_soffio",$tipo_soffio,$testo_sconfitta);	
	                	$mostro = $row['nome_mostro'];
	                	output(" $testo_sconfitta ");
	                    output("`n`nHai perso lo scontro con $mostro, il tuo `@Drago`2 ha procurato `b`@$danni_player danni`2`b al nemico e ha subito la perdita di `b`\$$danni_cpu `2punti vita`b.`n`n");                    
	                    $vita_restante=$rowd['vita_restante']-$danni_cpu;
	                    $sql = "UPDATE draghi SET vita_restante='$vita_restante' WHERE user_id='".$session['user']['acctid']."'";
	                    db_query($sql) or die(db_error(LINK));
	                }
	                $sql = "SELECT * FROM draghi WHERE user_id = '".$session['user']['acctid']."'";
	                $result = db_query($sql) or die(db_error(LINK));
	                $rowd = db_fetch_assoc($result);
	                if($rowd['vita_restante'] <=0){
	                    if ($rowd['aspetto']=='Ottimo'){
	                        $sql = "UPDATE draghi SET aspetto='Buono' WHERE user_id='".$session['user']['acctid']."'";
	                        db_query($sql) or die(db_error(LINK));
	                        $testo ="diventando di aspetto buono";
	                    }elseif ($rowd['aspetto']=='Buono'){
	                        $sql = "UPDATE draghi SET aspetto='Normale' WHERE user_id='".$session['user']['acctid']."'";
	                        db_query($sql) or die(db_error(LINK));
	                        $testo ="diventando di aspetto normale";
	                    }elseif ($rowd['aspetto']=='Normale'){
	                        $sql = "UPDATE draghi SET aspetto='Brutto' WHERE user_id='".$session['user']['acctid']."'";
	                        db_query($sql) or die(db_error(LINK));
	                        $testo ="diventando di aspetto brutto";
	                    }elseif ($rowd['aspetto']=='Brutto'){
	                        $sql = "UPDATE draghi SET aspetto='Pessimo' WHERE user_id='".$session['user']['acctid']."'";
	                        db_query($sql) or die(db_error(LINK));
	                        $testo ="diventando di aspetto pessimo";
	                    }elseif ($rowd['aspetto']=='Pessimo'){
	                        $caso_morte = mt_rand(1, 1);
	                        if ($caso_morte == 1){
	                            $sql = "DELETE from draghi WHERE user_id='".$session['user']['acctid']."'";
	                            db_query($sql) or die(db_error(LINK));
	                            $session['user']['id_drago']=0;
	                            $morte = true ;
	                        }else{
	                            $morte = false ;
	                            $testo ="rimanendo di aspetto pessimo";
	                        }
	
	                    }
	                    if ($morte) {
	                    	output("`2Purtroppo il tuo `@Drago`2 è stato ucciso nella cruenta battaglia. Disperat".($session[user][sex]?"a":"o")." per aver perso il tuo compagno rientri mestamente al villaggio struggendoti di dolore e senza aver più la forza di fare altro per il resto della giornata.`n
	                    	Dovrai inoltre trovare un nuovo compagno per tornare ad essere un vero `@Cavaliere dei Draghi`2 e esplorare queste Terre alla ricerca di nuove avventure! ") ;
	                    	$session['user']['turns'] = 0;
	                    }else{
	                       	output("`2Purtroppo il tuo `@Drago`2 è stato gravemente ferito nella cruenta battaglia perdendo tutta la sua linfa vitale e $testo. 
	                    		Sei quindi costrett".($session[user][sex]?"a":"o")." a rientrare al villaggio per poter curare il tuo fedele compagno e dovrai aspettare almeno fino al prossimo nuovo giorno per poter tornare in queste terre ed affrontare con lui nuove avventure!`n");
	                    }
	                    addnav("Torna al Villaggio","village.php");
	                }else{
	                    addnav("Torna al Vulcano","terre_draghi.php?op=vulcano");
	                }
	            }else if($_GET['op']=="scappamostri"){
	                $caso=mt_rand(1, 3);
	                if ($caso==3){
	                    addnav("Torna al Vulcano","terre_draghi.php?op=vulcano");
	                    output("`6Sei riuscit".($session[user][sex]?"a":"o")." a scappare`n");
	                }else{
	                    addnav("Attacca","terre_draghi.php?op=attaccamostri&id_mostro=$_GET[id_mostro]");
	                    output("`6Non sei riuscit".($session[user][sex]?"a":"o")." a scappare`n");
	                }    
	            }else if($_GET['op']=="attacca_terra"){
	                          if(ismultiaction("draghi",$_GET['id_player']) || ismultiaction("terre",$_GET['id_player'])) {
	                              output("`\$Hai già attaccato il drago con un tuo pg!");
	                              debuglog("Tenta di interagire coi suoi pg contro il drago del giocatore " . $_GET['id_player']);
	                              addnav("Torna indietro","terre_draghi.php?op=importanti");
	                          }
	                          elseif ($session['user']['turni_drago_rimasti'] > 0) {
	                         saveaction("draghi",$_GET['id_player'],3600);
	                         saveaction("terre",$_GET['id_terra'],3600);
	                    $sql = "SELECT cavalcare_drago,carriera,name FROM accounts WHERE acctid='".$_GET['id_player']."'";
	                    $result = db_query($sql) or die(db_error(LINK));
	                    $rowpd = db_fetch_assoc($result);
	                    $session['user']['turni_drago_rimasti'] -=1;
	                    $sql = "SELECT * FROM draghi WHERE user_id = '".$session['user']['acctid']."'";
	                    $result = db_query($sql) or die(db_error(LINK));
	                    $rowa = db_fetch_assoc($result);
	                    $sql = "SELECT * FROM draghi WHERE user_id = '".$_GET['id_player']."'";
	                    $result = db_query($sql) or die(db_error(LINK));
	                    $rowd = db_fetch_assoc($result);
	                    $caso_att_att = mt_rand(1, 20);
	                    $caso_dif_att = mt_rand(1, 20);
	                    $caso_att_dif = mt_rand(1, 20);
	                    $caso_dif_dif = mt_rand(1, 20);
	                    $caso_danno_att=mt_rand(intval($rowa['danno_soffio']/2), $rowa['danno_soffio']);
	                    $caso_danno_dif=mt_rand(intval($rowd['danno_soffio']/2), $rowd['danno_soffio']);
	                    if($rowa['combatte']=="volo"){
	                        $att_att_bonus=$rowa['bonus'];
	                        $dif_att_bonus=$rowa['bonus'];
	                    }
	                    if($rowd['combatte']=="volo"){
	                        $att_dif_bonus=$rowd['bonus'];
	                        $dif_dif_bonus=$rowd['bonus'];
	                    }
	                    $att_attaccante = $att_att_bonus+$rowa['attacco']+intval($rowa['carattere']/2)+intval($session['user']['cavalcare_drago']/2)+$caso_att_att;
	                    $dif_attaccante = $dif_att_bonus+$rowa['difesa']+intval($rowa['carattere']/2)+intval($session['user']['cavalcare_drago']/2)+$caso_dif_att;
	                    $att_difensore = $att_dif_bonus+$rowd['attacco']+intval($rowd['carattere']/2)+intval($rowpd['cavalcare_drago']/2)+$caso_att_dif;
	                    $dif_difensore = $dif_dif_bonus+$rowd['difesa']+intval($rowd['carattere']/2)+intval($rowpd['cavalcare_drago']/2)+$caso_dif_dif;
	                    $danno_inflitto_dif=$att_attaccante-$dif_difensore+$caso_danno_att;
	                    $danno_inflitto_att=$att_difensore-$dif_attaccante+$caso_danno_dif;
	                    if ($danno_inflitto_dif<0){
	                        $danno_inflitto_dif=0;
	                    }
	                    if ($danno_inflitto_att<0){
	                        $danno_inflitto_att=0;
	                    }
	                    $vita_restante_dif=$rowd['vita_restante']-$danno_inflitto_dif;
	                    $vita_restante_att=$rowa['vita_restante']-$danno_inflitto_att;
	                    $sql = "UPDATE draghi SET vita_restante='$vita_restante_dif' WHERE user_id='".$_GET['id_player']."'";
	                    db_query($sql) or die(db_error(LINK));
	                    $sql = "UPDATE draghi SET vita_restante='$vita_restante_att' WHERE user_id='".$session['user']['acctid']."'";
	                    db_query($sql) or die(db_error(LINK));
	                    if($danno_inflitto_att >=$danno_inflitto_dif){
	                        output("`5Hai perso lo scontro causando `b`\$$danno_inflitto_dif danni`^`b e subendo `b`@$danno_inflitto_att danni`b`^.`n");
	                        output("`6Player_att: `^$att_attaccante/$dif_attaccante `2player_dif:$att_difensore/$dif_difensore`n");
	                        $mailmessage = "`^".$session['user']['name']."`2 ti ha attaccato `2 con il suo `^ drago ".$rowa['tipo_drago']." ".$rowa['eta_drago']."`2, e lo hai sconfitto !"
	                        ." `n`nHa ferito il tuo drago causandogli $danno_inflitto_dif danni. All'inizio il tuo drago aveva $rowd[vita_restante] ora gli restano $vita_restante_dif punti vita!!`n`nAl suo drago hai inflitto $danno_inflitto_att danni. Ora gli restano $vita_restante_att punti vita!";
	                        $mailmessage = str_replace("%o",($session['user']['sex']?"lei":"lui"),$mailmessage);
	                        systemmail($_GET['id_player'],"`2Vittoria con il drago.`2",$mailmessage);
	                        addnav("Prendi quota", "terre_draghi.php?op=avvicinati&id_terra=$_GET[id_terra]");
	                        debuglog("`0ha attaccato, perdendo, il drago di",$_GET['id_player']);
	                    }elseif ($danno_inflitto_att < $danno_inflitto_dif){
	                        output("`^Hai vinto lo scontro causando `b`\$$danno_inflitto_dif danni`^`b e subendo `b`@$danno_inflitto_att danni`b`^!`n");
	                        output("`6Player_att:$att_attaccante/$dif_attaccante player_dif:$att_difensore/$dif_difensore`n");
	                        $mailmessage = "`^".$session['user']['name']."`2 ti ha attaccato`2 con il suo `^ drago ".$rowa['tipo_drago']." ".$rowa['eta_drago']."`2, e ti ha sconfitto !"
	                        ." `n`nHa ferito il tuo drago causandogli $danno_inflitto_dif danni. All'inizio il tuo drago aveva $rowd[vita_restante] ora gli restano $vita_restante_dif punti vita!!`n
	                        `nAl suo drago hai inflitto $danno_inflitto_att danni. Ora gli restano $vita_restante_att punti vita!";
	                        $mailmessage = str_replace("%o",($session['user']['sex']?"lei":"lui"),$mailmessage);
	                        systemmail($_GET['id_player'],"`2Sconfitta con il drago.`2",$mailmessage);
	                        addnav("Prendi quota", "terre_draghi.php?op=avvicinati&id_terra=$_GET[id_terra]");
	                        debuglog("`0ha attaccato, vincendo, il drago di",$_GET['id_player']);
	                    }
	                    //controlli drago difensore
	                    $sql = "SELECT * FROM draghi WHERE user_id = '".$_GET['id_player']."'";
	                    $result = db_query($sql) or die(db_error(LINK));
	                    $rowd = db_fetch_assoc($result);
	                    if($rowd['vita_restante'] <=0){
	                        if ($rowd['aspetto']=='Ottimo'){
	                            $sql = "UPDATE draghi SET aspetto='Buono' WHERE user_id='".$_GET['id_player']."'";
	                            db_query($sql) or die(db_error(LINK));
	                            $testo ="diventando di aspetto buono";
	                        }elseif ($rowd['aspetto']=='Buono'){
	                            $sql = "UPDATE draghi SET aspetto='Normale' WHERE user_id='".$_GET['id_player']."'";
	                            db_query($sql) or die(db_error(LINK));
	                            $testo ="diventando di aspetto normale";
	                        }elseif ($rowd['aspetto']=='Normale'){
	                            $sql = "UPDATE draghi SET aspetto='Brutto' WHERE user_id='".$_GET['id_player']."'";
	                            db_query($sql) or die(db_error(LINK));
	                            $testo ="diventando di aspetto brutto";
	                        }elseif ($rowd['aspetto']=='Brutto'){
	                            $sql = "UPDATE draghi SET aspetto='Pessimo' WHERE user_id='".$_GET['id_player']."'";
	                            db_query($sql) or die(db_error(LINK));
	                            $testo ="diventando di aspetto pessimo";
	                        }elseif ($rowd['aspetto']=='Pessimo'){
	                        }
	                        output("`&Hai ucciso il drago nemico e puoi prendere il territorio che controllava! `n");
	                        $sql = "DELETE FROM terre_draghi_dove where id_terra='".$_GET[id_terra]."'";
	                        db_query($sql) or die(db_error(LINK));
	                        $mailmessage = "`2Durante l'ultimo attacco `^".$session['user']['name']."`2 ha ucciso il tuo drago!!"
	                        ."`n`nNon pensi sia il momento di vendicarsi?";
	                        $mailmessage = str_replace("%o",($session['user']['sex']?"lei":"lui"),$mailmessage);
	                        systemmail($_GET['id_player'],"`2Il tuo drago è stato ucciso.`2",$mailmessage);
	                        debuglog("`0ha ucciso il drago di",$_GET['id_player']);
	                    }
	                    //controlli drago attaccante
	                    $sql = "SELECT * FROM draghi WHERE user_id = '".$session['user']['acctid']."'";
	                    $result = db_query($sql) or die(db_error(LINK));
	                    $rowd = db_fetch_assoc($result);
	                    if($rowd[vita_restante] <=0){
	                        if(e_rand(1,20)==1){
	                            if ($rowd['aspetto']=='Ottimo'){
	                                $sql = "UPDATE draghi SET aspetto='Buono' WHERE user_id='".$session['user']['acctid']."'";
	                                db_query($sql) or die(db_error(LINK));
	                                $testo ="diventando di aspetto buono";
	                            }elseif ($rowd['aspetto']=='Buono'){
	                                $sql = "UPDATE draghi SET aspetto='Normale' WHERE user_id='".$session['user']['acctid']."'";
	                                db_query($sql) or die(db_error(LINK));
	                                $testo ="diventando di aspetto normale";
	                            }elseif ($rowd['aspetto']=='Normale'){
	                                $sql = "UPDATE draghi SET aspetto='Brutto' WHERE user_id='".$session['user']['acctid']."'";
	                                db_query($sql) or die(db_error(LINK));
	                                $testo ="diventando di aspetto brutto";
	                            }elseif ($rowd['aspetto']=='Brutto'){
	                                $sql = "UPDATE draghi SET aspetto='Pessimo' WHERE user_id='".$session['user']['acctid']."'";
	                                db_query($sql) or die(db_error(LINK));
	                                $testo ="diventando di aspetto pessimo";
	                            }elseif ($rowd['aspetto']=='Pessimo'){
	                                $caso_morte = mt_rand(1, 20);
	                                if ($caso_morte ==1){
	                                    $sql = "DELETE from draghi WHERE user_id='".$session['user']['acctid']."'";
	                                    db_query($sql) or die(db_error(LINK));
	                                    $session['user']['id_drago']=0;
	                                    $testo1="definitivamente";
	                                }else{
	                                    $testo1="miseramente";
	                                }
	                            }
	                        }
	                        output("`6Il tuo drago è stato $testo1 ucciso durante l'ultimo attacco $testo! `n");
	                        $mailmessage = "`2Durante l'ultimo attacco hai ucciso il drago di `^".$session['user']['name']." !";
	                        $mailmessage = str_replace("%o",($session['user']['sex']?"lei":"lui"),$mailmessage);
	                        systemmail($_GET['id_player'],"`2Hai ucciso un drago.`2",$mailmessage);
	                        debuglog("`0ha perso, morendo, contro il drago di",$_GET['id_player']);
	                    }
	                    addnav("Torna al Villaggio","village.php");
	                }else{
	                    output("`4Il tuo drago è troppo stanco per combattere ! `n");
	                    addnav("Torna al Vulcano","terre_draghi.php?op=vulcano");
	                }
	            }else if($_GET['op']=="soffio"){
	                if(ismultiaction("draghi",$_GET['id_player']) || ismultiaction("terre",$_GET['id_terra'])) {
	                    output("`\$Hai già attaccato il drago con un tuo pg!");
	                    debuglog("Tenta di interagire coi suoi pg contro il drago del giocatore " . $_GET['id_player']);
	                    addnav("Torna indietro","terre_draghi.php?op=importanti");
	                }
	                elseif($session['user']['turni_drago_rimasti'] > 0 AND $session['user']['cavalcare_drago'] > 1) {
	                    saveaction("draghi",$_GET['id_player'],3600);
	                    saveaction("terre",$_GET['id_terra'],3600);
	                    $sql = "SELECT cavalcare_drago,carriera,name FROM accounts WHERE acctid='".$_GET['id_player']."'";
	                    $result = db_query($sql) or die(db_error(LINK));
	                    $rowpd = db_fetch_assoc($result);
	                    $session['user']['turni_drago_rimasti'] -=1;
	                    $session['user']['cavalcare_drago'] -=1;
	                    $sql = "SELECT * FROM draghi WHERE user_id = '".$session['user']['acctid']."'";
	                    $result = db_query($sql) or die(db_error(LINK));
	                    $rowa = db_fetch_assoc($result);
	                    $sql = "SELECT * FROM draghi WHERE user_id = '".$_GET['id_player']."'";
	                    $result = db_query($sql) or die(db_error(LINK));
	                    $rowd = db_fetch_assoc($result);
	                    $caso_att = mt_rand(1, 20);
	                    $caso_dif = mt_rand(20, 20);
	                    $attacco= ($rowa['attacco']*2)+intval($rowa['carattere']/2)+intval($session['user']['cavalcare_drago']/2)+$caso_att;
	                    $difesa=$rowd['difesa']+intval($rowd[carattere]/2)+intval($rowpd['cavalcare_drago']/2)+$caso_dif;
	                    $caso_danno=mt_rand(intval($rowa['danno_soffio']/2), $rowa['danno_soffio']);
	                    $danno=$attacco-$difesa+$caso_danno;
	                    if ($danno<0){
	                        $danno=0;
	                    }
	                    output("`)Il tuo drago utilizza il suo soffio di `^".$rowa['tipo_soffio']."`) causandogli `b`\$$danno`)`b danni.`n");
	                    $vita_restante=$rowd['vita_restante']-$danno;
	                    $sql = "UPDATE draghi SET vita_restante='$vita_restante' WHERE user_id='".$_GET['id_player']."'";
	                    db_query($sql) or die(db_error(LINK));
	                    if($vita_restante <=0){
	                        if(e_rand(1,20)==1){
	                            $testo="";
	                            if ($rowd['aspetto']=='Ottimo'){
	                                $caso = mt_rand(1, 5);
	                                if($caso==1){
	                                    $sql = "UPDATE draghi SET aspetto='Buono' WHERE user_id='".$_GET['id_player']."'";
	                                    db_query($sql) or die(db_error(LINK));
	                                    $testo ="Purtroppo l'aspetto del tuo drago è peggiorato, ora è buono!";
	                                }
	                            }elseif ($rowd['aspetto']=='Buono'){
	                                $caso = mt_rand(1, 5);
	                                if($caso==1){
	                                    $sql = "UPDATE draghi SET aspetto='Normale' WHERE user_id='".$_GET['id_player']."'";
	                                    db_query($sql) or die(db_error(LINK));
	                                    $testo ="Purtroppo l'aspetto del tuo drago è peggiorato, ora è normale!";
	                                }
	                            }elseif ($rowd['aspetto']=='Normale'){
	                                $caso = mt_rand(1, 5);
	                                if($caso==1){
	                                    $sql = "UPDATE draghi SET aspetto='Brutto' WHERE user_id='".$_GET['id_player']."'";
	                                    db_query($sql) or die(db_error(LINK));
	                                    $testo ="Purtroppo l'aspetto del tuo drago è peggiorato, ora è brutto!";
	                                }
	                            }elseif ($rowd['aspetto']=='Brutto'){
	                                $caso = mt_rand(1, 5);
	                                if($caso==1){
	                                    $sql = "UPDATE draghi SET aspetto='Pessimo' WHERE user_id='".$_GET['id_player']."'";
	                                    db_query($sql) or die(db_error(LINK));
	                                    $testo ="Purtroppo l'aspetto del tuo drago è peggiorato, ora è pessimo!";
	                                }
	                            }elseif ($rowd['aspetto']=='Pessimo'){
	                            }
	                        }
	                        if($caso==1)$testo_aspetto="`n`n`2$testo";
	                        output("`6Hai ucciso il drago nemico e puoi prendere il territorio che controllava! `n");
	                        $sql = "DELETE FROM terre_draghi_dove where id_terra='".$_GET[id_terra]."'";
	                        db_query($sql) or die(db_error(LINK));
	                        $mailmessage = "`^".$session['user']['name']."`2 ti ha attaccato`2 con il soffio di ".$rowa['tipo_soffio']." del suo `^".$row['eta_drago']." di drago ".$rowa['tipo_drago']." `2, e ti ha sconfitto !"
	                        ." `n`nHa solamente ferito il tuo drago causandogli $danno danni. Il tuo drago aveva $rowd[vita_restante] punti vita, ora ne ha $vita_restante !  $testo_aspetto`n`nNon pensi sia il momento di vendicarsi?";
	                        $mailmessage = str_replace("%o",($session['user']['sex']?"lei":"lui"),$mailmessage);
	                        systemmail($_GET['id_player'],"`2Hanno ucciso il tuo drago.`2",$mailmessage);
	                        debuglog("`0ha ucciso con soffio il drago di",$_GET['id_player']);
	                    }else{
	                        if ($danno==0){
	                        }else{
	                            $mailmessage = "`^".$session['user']['name']."`2 ti ha attaccato`2 con il soffio di ".$rowa['tipo_soffio']." del suo `^".$row['eta_drago']." di drago ".$rowa['tipo_drago']." `2, e ti ha sconfitto !"
	                            ." `n`nHa solamente ferito il tuo drago causandogli $danno danni. Il tuo drago aveva $rowd[vita_restante] punti vita, ora ne ha $vita_restante !  `n`nNon pensi sia il momento di vendicarsi?";
	                            $mailmessage = str_replace("%o",($session['user']['sex']?"lei":"lui"),$mailmessage);
	                            systemmail($_GET['id_player'],"`2Hanno ferito il tuo drago.`2",$mailmessage);
	                            debuglog("`0ha ferito con soffio il drago di",$_GET['id_player']);
	                        }
	                    }
	                    addnav("Prendi quota", "terre_draghi.php?op=avvicinati&id_terra=$_GET[id_terra]");
	                    addnav("Torna al Vulcano","terre_draghi.php?op=vulcano");
	                }else{
	                    output("`4Il tuo drago ha esaurito l'energia per usare il soffio!`nDovrai aspettare che si ricarichi!`n`n");
	                    addnav("Torna al Vulcano","terre_draghi.php?op=vulcano");
	                }
	            }else if($_GET['op']=="erold"){
	                output("`@Erold  dei draghi`n`n");
	                output("`2Noti una capanna di legno che reca una strana insegna \"`!Erold dei draghi `2\"`n");
	                output("`2Davanti alla capanna un vecchio è intento a vendere i propri servizi ad una moltitudine di cavalieri.`n");
	                output("`2Sbirciando quà e la vedi un listino con dei prezzi.`n");
	                output("`\$Attacco : `61000 oro e un punto cavalcare drago`n");
	                output("`#Difesa : `61000 oro e un punto cavalcare drago`n");
	                output("`!Danno : `62000 oro e un punto cavalcare drago`n");
	                output("`%Vita : `66000 oro e un punto cavalcare drago`n");
	                output("`vCittà dei draghi : `33 gemme e 1 turno drago`n");
	                $costo_cura=10*($rowd[vita]-$rowd[vita_restante]);
	                output("`@Cura : `6$costo_cura oro`n");
	                if($rowd[bonus_erold]==no)addnav("Attacco","terre_draghi.php?op=e_attacco");
	                if($rowd[bonus_erold]==no)addnav("Difesa","terre_draghi.php?op=e_difesa");
	                if($rowd[bonus_erold]==no)addnav("Danno","terre_draghi.php?op=e_danno");
	                if($rowd[bonus_erold]==no)addnav("Vita","terre_draghi.php?op=e_vita");
	                addnav("Cura","terre_draghi.php?op=ec");
	                addnav("Città dei draghi (3 gemme + 1 td)","terre_draghi.php?op=cd");
	                addnav("Torna al Vulcano","terre_draghi.php?op=vulcano");
	            }else if($_GET['op']=="e_vita"){
	                if($session['user']['gold'] >= 6000){
	                    if( $session['user']['cavalcare_drago'] > 0){
	                        $session['user']['cavalcare_drago'] -= 1;
	                        $session['user']['gold'] -= 6000;
	                        $bonus=e_rand(intval($rowd[vita]/2),$rowd[vita])+5;
	                        $vita=$bonus+$rowd[vita];
	                        output("`2Erold impone le mani sul tuo drago qualche minuto e poi dice:\"`@Ora il tuo drago una maggiore vitalità!`2\"`n`n");
	                        $sql = "UPDATE draghi SET vita_restante='$vita',bonus_erold='vi',bonus_erold_valore='$bonus' WHERE user_id='".$rowd['user_id']."'";
	                        db_query($sql) or die(db_error(LINK));
	                        if( $session['user']['superuser'] > 0)output("bonus vita : $bonus");
	                    }else{
	                        output("`2Erold dice:\"`@Devi imparare a cavalcare il drago!`2\"`n`n");
	                    }
	                }else{
	                    output("`2Erold dice:\"`@Non hai abbastanza oro!`2\"`n`n");
	                }
	                addnav("Torna al Vulcano","terre_draghi.php?op=vulcano");
	            }else if($_GET['op']=="e_danno"){
	                if($session['user']['gold'] >= 2000){
	                    if( $session['user']['cavalcare_drago'] > 0){
	                        $session['user']['cavalcare_drago'] -= 1;
	                        $session['user']['gold'] -= 2000;
	                        $bonus=e_rand(intval($rowd[danno_soffio]/2),$rowd[danno_soffio])+5;
	                        $danno_soffio=$bonus+$rowd[danno_soffio];
	                        output("`2Erold impone le mani sul tuo drago qualche minuto e poi dice:\"`@Ora il tuo drago farà dei danni maggiori ai tuoi nemici!`2\"`n`n");
	                        $sql = "UPDATE draghi SET danno_soffio='$danno_soffio',bonus_erold='da',bonus_erold_valore='$bonus' WHERE user_id='".$rowd['user_id']."'";
	                        db_query($sql) or die(db_error(LINK));
	                        if( $session['user']['superuser'] > 0)output("bonus attacco : $bonus");
	                    }else{
	                        output("`2Erold dice:\"`@Devi imparare a cavalcare il drago!`2\"`n`n");
	                    }
	                }else{
	                    output("`2Erold dice:\"`@Non hai abbastanza oro!`2\"`n`n");
	                }
	                addnav("Torna al Vulcano","terre_draghi.php?op=vulcano");
	
	            }else if($_GET['op']=="e_difesa"){
	                if($session['user']['gold'] >= 1000){
	                    if( $session['user']['cavalcare_drago'] > 0){
	                        $session['user']['cavalcare_drago'] -= 1;
	                        $session['user']['gold'] -= 1000;
	                        $bonus=e_rand(intval($rowd[difesa]/2),$rowd[difesa])+10;
	                        $difesa=$bonus+$rowd[difesa];
	                        output("`2Erold impone le mani sul tuo drago qualche minuto e poi dice:\"`@Ora il tuo drago è più resistente!`2\"`n`n");
	                        $sql = "UPDATE draghi SET difesa='$difesa',bonus_erold='di',bonus_erold_valore='$bonus' WHERE user_id='".$rowd['user_id']."'";
	                        db_query($sql) or die(db_error(LINK));
	                        if( $session['user']['superuser'] > 0)output("bonus attacco : $bonus");
	                    }else{
	                        output("`2Erold dice:\"`@Devi imparare a cavalcare il drago!`2\"`n`n");
	                    }
	                }else{
	                    output("`2Erold dice:\"`@Non hai abbastanza oro!`2\"`n`n");
	                }
	                addnav("Torna al Vulcano","terre_draghi.php?op=vulcano");
	
	            }else if($_GET['op']=="e_attacco"){
	                if($session['user']['gold'] >= 1000){
	                    if( $session['user']['cavalcare_drago'] > 0){
	                        $session['user']['cavalcare_drago'] -= 1;
	                        $session['user']['gold'] -= 1000;
	                        $bonus=e_rand(intval($rowd[attacco]/2),$rowd[attacco])+10;
	                        $attacco=$bonus+$rowd[attacco];
	                        output("`2Erold impone le mani sul tuo drago qualche minuto e poi dice:\"`@Ora il tuo drago ha un attacco potenziato!`2\"`n`n");
	                        $sql = "UPDATE draghi SET attacco='$attacco',bonus_erold='at',bonus_erold_valore='$bonus' WHERE user_id='".$rowd['user_id']."'";
	                        db_query($sql) or die(db_error(LINK));
	                        if( $session['user']['superuser'] > 0)output("bonus attacco : $bonus");
	                    }else{
	                        output("`2Erold dice:\"`@Devi imparare a cavalcare il drago!`2\"`n`n");
	                    }
	                }else{
	                    output("`2Erold dice:\"`@Non hai abbastanza oro!`2\"`n`n");
	                }
	                addnav("Torna al Vulcano","terre_draghi.php?op=vulcano");
	            }else if($_GET['op']=="cd"){
	                if($session['user']['gems'] >= 3){
	                    if($session['user']['turni_drago_rimasti'] > 0){
	                        $session['user']['gems'] -= 3;
	                        $session['user']['turni_drago_rimasti'] -= 1;
	                        output("`2Erold impone le mani sul tuo drago qualche minuto e poi dice:\"`@Ora il tuo drago per un po di tempo conosce la strada per la mitica città dei draghi!`2\"`n`n");
	                        addnav("Città dei Draghi","terre_draghi.php?op=citta");
	                    }else{
	                        output("`2Erold dice:\"`@Il tuo drago è troppo stanco!`2\"`n`n");
	                        addnav("Torna al Vulcano","terre_draghi.php?op=vulcano");
	                    }
	                }else{
	                    output("`2Erold dice:\"`@Non hai abbastanza gemme!`2\"`n`n");
	                    addnav("Torna al Vulcano","terre_draghi.php?op=vulcano");
	                }
	            }else if($_GET['op']=="ec"){
	                output("`@Erold  dei draghi`n`n");
	                $costo_cura=10*($rowd[vita]-$rowd[vita_restante]);
	                if($costo_cura==0){
	                    output("`2Erold dice:\"`@Il tuo drago sta bene non ha bisogno di cure!\"`n`n");
	                }else{
	                    if($costo_cura<=$session['user']['gold']){
	                        $session['user']['gold'] -= $costo_cura;
	                        output("`2Erold impone le mani sul tuo drago qualche minuto e poi dice:\"`@Ora il tuo drago sta bene, addio!\"`n`n");
	                        $sql = "UPDATE draghi SET vita_restante=vita WHERE user_id=".$session['user']['acctid'];
	                        db_query($sql) or die(db_error(LINK));
	                    }else{
	                        output("`2Erold dice:\"`@Non hai abbastanza soldi!\"`n`n");
	                    }
	                }
	                if($_GET['op']!="cd")addnav("Torna al Vulcano","terre_draghi.php?op=vulcano");
	            }else if($_GET['op']=="citta"){
	                addnav("Torna al Vulcano","terre_draghi.php?op=vulcano");
	                addnav("Mercante","mercante_citta_draghi.php");
	                addnav("Thantalas","terre_draghi.php?op=allenatore");
	                addnav("Fabbro","fabbro_citta_draghi.php");
	                output("`2Ti incammini nelle vie della città, ed una moltitudine di negozi attirano la tua attenzione con ");
	                output("suoni, colori ed aromi di ogni tipo.`nMa la tua vista viene catturata da un negozio che si ");
	                output("distingue dagli altri per le dimensione mastodontiche. Sicuramente al suo interno troverai ciò ");
	                output("per cui questa città leggendaria è famosa: `b`%DRAGHI`b`2!!!`n`n");
	                addcommentary();
	                viewcommentary("citta_draghi","Aggiungi",25,10);
	            }else if($_GET['op']=="allenatore"){
	                output("`3Seguendo le indicazioni arrivi in quella che pare la costruzione gemella di quella di Ukhtrak, il ");
	                output("mercante di draghi. Sulla soglia noti un maestoso elfo, che incute soggezione al solo guardarlo. `n");
	                output("Thantalas, il domatore di draghi, ti lancia uno sguardo distratto, e mentre avvicinandoti stai per ");
	                output("parlare, dice,`n`n\"`@Hey, ".$races[$session['user']['race']]."`@, mi hanno riferito che possiedi un drago ");
	                output("e che vorresti che io lo allenassi, nevvero?`3\"`n`n");
	                output("Ti chiedi come sia possibile che sappia delle tue intenzioni, ma le voci circolano velocemente in una città ");
	                output("come questa, e dopo un primo momento di imbarazzo, gli dici,`n`n\"`%Ti hanno riferito bene Thantalas, sono qui ");
	                output("per questo motivo, il mio drago ultimamente non ha la condizione di una volta e sono qui per riportarlo ai ");
	                output("fasti di un tempo.`3\"`n`n");
	                output("Thantalas abbassa lo sguardo sul borsello attaccato alla tua cintura, come per valutare le tue finanze, poi ");
	                output("prosegue dicendo,`n`n\"`@Bene, bene, bene. Come ti avranno detto sono il migliore della zona, nessun altro può ");
	                output("competere con la mia abilità e le mie conoscienze sui draghi, e queste qualità hanno un prezzo.`3\"`n`n");
	                output("Dopodichè ti indica con un gesto della mano un cartello appeso a fianco dell'ingresso, dove fanno bella mostra ");
	                output("i prezzi dei suoi servigi.`n`n");
	                addnav("Azioni");
	                addnav("A?Allena Attacco", "terre_draghi.php?op=all_att");
	                addnav($rowd['attacco']." gemme", "");
	                addnav("D?Allena Difesa", "terre_draghi.php?op=all_dif");
	                addnav($rowd['difesa']." gemme", "");
	                addnav("S?Allena Soffio", "terre_draghi.php?op=all_sof");
	                addnav($rowd['danno_soffio']." gemme", "");
	                //addnav("T?Allena Tutto", "terre_draghi.php?op=all_tut");
	                //addnav("40 gemme", "");
	                addnav("M?Migliora Aspetto", "terre_draghi.php?op=aspetto");
	                addnav("50 gemme", "");
	                addnav("Uscita");
	                addnav("Città dei Draghi","terre_draghi.php?op=citta");
	            }else if($_GET['op']=="aspetto"){
	                if ($session['user']['gems'] > 49){
	                    $sql = "SELECT * FROM draghi WHERE user_id = ".$session['user']['acctid'];
	                    $result = db_query($sql) or die(db_error(LINK));
	                    $rowd = db_fetch_assoc($result);
	                    if ($rowd[aspetto]=="Ottimo"){
	                        output("`3Thantalas ti squadra con espressione perplessa e dice,`n`n\"`@Il tuo drago ha già un aspetto ottimo, ");
	                        output("cosa pensi di migliorare?`3\"`n`n");
	                    }elseif($rowd['aspetto']=="Buono"){
	                        output("`3Thantalas afferra il guinzaglio del tuo drago e lo osserva con occhio professionale, poi ti dice,`n`n\"`@");
	                        output("Torna fra un paio d'ore, vedrò cosa posso fare per lui, anche se mi pare in buone condizioni.`3\"`n`n");
	                        output("Ne approfitti per girare nei vicoli della città, e quando torni trovi il tuo drago all'esterno della ");
	                        output("struttura, l'aspetto decisamente migliore di quando l'hai consegnato a Thantalas.`n`n");
	                        output("`^L'aspetto del drago adesso è `b`\$Ottimo`b`^.`n`n");
	                        $sql = "UPDATE draghi SET aspetto='Ottimo' WHERE user_id=".$session['user']['acctid'];
	                        db_query($sql) or die(db_error(LINK));
	                        $session['user']['gems'] -= 50;
	                    }elseif($rowd['aspetto']=="Normale"){
	                        output("`3Thantalas afferra il guinzaglio del tuo drago e lo osserva con occhio professionale, poi ti dice,`n`n\"`@");
	                        output("Torna fra un paio d'ore, vedrò cosa posso fare per lui, anche se mi pare in discrete condizioni.`3\"`n`n");
	                        output("Ne approfitti per girare nei vicoli della città, e quando torni trovi il tuo drago all'esterno della ");
	                        output("struttura, l'aspetto decisamente migliore di quando l'hai consegnato a Thantalas.`n`n");
	                        output("`^L'aspetto del drago adesso è `b`\$Buono`b`^.`n`n");
	                        $sql = "UPDATE draghi SET aspetto='Buono' WHERE user_id=".$session['user']['acctid'];
	                        db_query($sql) or die(db_error(LINK));
	                        $session['user']['gems'] -= 50;
	                    }elseif($rowd['aspetto']=="Brutto"){
	                        output("`3Thantalas afferra il guinzaglio del tuo drago e lo osserva con occhio professionale, poi ti dice,`n`n\"`@");
	                        output("Torna fra un paio d'ore, vedrò cosa posso fare per lui, ti consiglio comunque di curarlo meglio nel futuro.`3\"`n`n");
	                        output("Ne approfitti per girare nei vicoli della città, e quando torni trovi il tuo drago all'esterno della ");
	                        output("struttura, l'aspetto decisamente migliore di quando l'hai consegnato a Thantalas.`n`n");
	                        output("`^L'aspetto del drago adesso è `b`\$Normale`b`^.`n`n");
	                        $sql = "UPDATE draghi SET aspetto='Normale' WHERE user_id=".$session['user']['acctid'];
	                        db_query($sql) or die(db_error(LINK));
	                        $session['user']['gems'] -= 50;
	                    }elseif($rowd['aspetto']=="Pessimo"){
	                        output("`3Thantalas afferra il guinzaglio del tuo drago e lo osserva con occhio professionale, poi ti dice,`n`n\"`@");
	                        output("Torna fra un paio d'ore, vedrò cosa posso fare per lui, anche se non faccio miracoli.`3\"`n`n");
	                        output("Ne approfitti per girare nei vicoli della città, e quando torni trovi il tuo drago all'esterno della ");
	                        output("struttura, l'aspetto decisamente migliore di quando l'hai consegnato a Thantalas.`n`n");
	                        output("`^L'aspetto del drago adesso è `b`\$Brutto`b`^.`n`n");
	                        $sql = "UPDATE draghi SET aspetto='Brutto' WHERE user_id=".$session['user']['acctid'];
	                        db_query($sql) or die(db_error(LINK));
	                        $session['user']['gems'] -= 50;
	                    }
	                }else{
	                    output("`3Thantalas gonfia il petto possente, e sembra che le sue orecchie si allunghino più di ");
	                    output("quanto non lo siano attualmente, e prorompe con voce baritonale,`n`n\"`\$Non hai `b`&50`b`\$ gemme!! ");
	                    output(" Non tentare di imbrogliarmi!!`3\"`n`n");
	                }
	                addnav("Torna da Thantalas","terre_draghi.php?op=allenatore");
	            }else if($_GET['op']=="all_att"){
	                if ($session['user']['gems']>=$rowd['attacco']){
	                    $caso= mt_rand(1,2);
	                    $migliora=$rowd['attacco']+$caso;
	                    $sql = "UPDATE draghi SET attacco='$migliora' WHERE id='$rowd[id]'";
	                    db_query($sql) or die(db_error(LINK));
	                    $session['user']['gems'] -= $rowd['attacco'];
	                    output("`3Thantalas afferra il guinzaglio del tuo drago e lo osserva con occhio professionale, poi ti dice,`n`n\"`@");
	                    output("Torna fra un paio d'ore, vedrò cosa posso fare per lui, tenendo conto delle sue condizioni.`3\"`n`n");
	                    output("Ne approfitti per visitare i luighi caratteristici della città, e quando torni trovi il tuo drago all'esterno della ");
	                    output("struttura, l'aspetto  migliore di quando l'hai consegnato a Thantalas.`n`n");
	                    output("\"`@Sono riuscito a migliorare l'attacco del tuo drago di `b`^$caso`b`@. Sono a tua disposizione se ");
	                    output("volessi usufruire dei miei servigi`3\"`n`n");
	                    addnav("Torna da Thantalas","terre_draghi.php?op=allenatore");
	                }else{
	                    output("`3Thantalas gonfia il petto possente, e sembra che le sue orecchie si allunghino più di ");
	                    output("quanto non lo siano attualmente, e prorompe con voce baritonale,`n`n\"`\$Non hai `b`&".$rowd['attacco']."`b`\$ gemme!! ");
	                    output(" Non tentare di imbrogliarmi!!`3\"`n`n");
	                    addnav("Torna da Thantalas","terre_draghi.php?op=allenatore");
	                }
	            }else if($_GET['op']=="all_dif"){
	                if ($session['user']['gems']>=$rowd['difesa']){
	                    $caso= mt_rand(1,2);
	                    $migliora=$rowd['difesa']+$caso;
	                    $sql = "UPDATE draghi SET difesa='$migliora' WHERE id='$rowd[id]'";
	                    db_query($sql) or die(db_error(LINK));
	                    $session['user']['gems'] -= $rowd['difesa'];
	                    output("`3Thantalas afferra il guinzaglio del tuo drago e lo osserva con occhio professionale, poi ti dice,`n`n\"`@");
	                    output("Torna fra un paio d'ore, vedrò cosa posso fare per lui, tenendo conto delle sue condizioni.`3\"`n`n");
	                    output("Ne approfitti per visitare i luighi caratteristici della città, e quando torni trovi il tuo drago all'esterno della ");
	                    output("struttura, l'aspetto  migliore di quando l'hai consegnato a Thantalas.`n`n");
	                    output("\"`@Sono riuscito a migliorare la difesa del tuo drago di `b`^$caso`b`@. Sono a tua disposizione se ");
	                    output("volessi usufruire dei miei servigi`3\"`n`n");
	                    addnav("Torna da Thantalas","terre_draghi.php?op=allenatore");
	                }else{
	                    output("`3Thantalas gonfia il petto possente, e sembra che le sue orecchie si allunghino più di ");
	                    output("quanto non lo siano attualmente, e prorompe con voce baritonale,`n`n\"`\$Non hai `b`&".$rowd['difesa']."`b`\$ gemme!! ");
	                    output(" Non tentare di imbrogliarmi!!`3\"`n`n");
	                    addnav("Torna da Thantalas","terre_draghi.php?op=allenatore");
	                }
	            }else if($_GET['op']=="all_sof"){
	                if ($session['user']['gems']>=$rowd[danno_soffio]){
	                    $caso= mt_rand(1,2);
	                    $migliora=$rowd['danno_soffio']+$caso;
	                    $sql = "UPDATE draghi SET danno_soffio='$migliora' WHERE id='$rowd[id]'";
	                    db_query($sql) or die(db_error(LINK));
	                    $session['user']['gems'] -= $rowd['danno_soffio'];
	                    output("`3Thantalas afferra il guinzaglio del tuo drago e lo osserva con occhio professionale, poi ti dice,`n`n\"`@");
	                    output("Torna fra un paio d'ore, vedrò cosa posso fare per lui, tenendo conto delle sue condizioni.`3\"`n`n");
	                    output("Ne approfitti per visitare i luighi caratteristici della città, e quando torni trovi il tuo drago all'esterno della ");
	                    output("struttura, l'aspetto  migliore di quando l'hai consegnato a Thantalas.`n`n");
	                    output("\"`@Sono riuscito a migliorare il danno da soffio del tuo drago di `b`^$caso`b`@. Sono a tua disposizione se ");
	                    output("volessi usufruire dei miei servigi`3\"`n`n");
	                    addnav("Torna da Thantalas","terre_draghi.php?op=allenatore");
	                }else{
	                    output("`3Thantalas gonfia il petto possente, e sembra che le sue orecchie si allunghino più di ");
	                    output("quanto non lo siano attualmente, e prorompe con voce baritonale,`n`n\"`\$Non hai `b`&".$rowd['danno_soffio']."`b`\$ gemme!! ");
	                    output(" Non tentare di imbrogliarmi!!`3\"`n`n");
	                    addnav("Torna da Thantalas","terre_draghi.php?op=allenatore");
	                }
	            }else if($_GET['op']=="all_tut"){
	                if ($session['user']['gems']>=40){
	                    $caso1= mt_rand(0,1);
	                    $caso2= mt_rand(0,1);
	                    $caso3= mt_rand(0,1);
	                    $caso4= mt_rand(2,4);
	                    $migliora4=$rowd['carattere']+$caso4;
	                    $migliora3=$rowd['danno_soffio']+$caso3;
	                    $migliora2=$rowd['difesa']+$caso2;
	                    $migliora1=$rowd['attacco']+$caso1;
	                    $sql = "UPDATE draghi SET carattere='$migliora4',danno_soffio='$migliora3',difesa='$migliora2',attacco='$migliora1' WHERE id='$rowd[id]'";
	                    db_query($sql) or die(db_error(LINK));
	                    $session['user']['gems'] -= 40;
	                    output("`3Thantalas afferra il guinzaglio del tuo drago e lo osserva con occhio professionale, poi ti dice,`n`n\"`@");
	                    output("Torna fra un paio d'ore, vedrò cosa posso fare per lui, tenendo conto delle sue condizioni.`3\"`n`n");
	                    output("Ne approfitti per visitare i luighi caratteristici della città, e quando torni trovi il tuo drago all'esterno della ");
	                    output("struttura, l'aspetto  migliore di quando l'hai consegnato a Thantalas.`n`n");
	                    output("\"`@Ho lavorato sodo con il tuo drago, ho fatto del mio meglio ed ecco i risultati:`n`n");
	                    output("`&L'attacco è migliorato di `b`\$$caso1`b`@.`n");
	                    output("`&La difesa è migliorata di `b`\$$caso2`b`@.`n");
	                    output("`&Il danno da soffio è migliorato di `b`\$$caso3`b`@.`n");
	                    output("`&Il carattere è migliorato di `b`\$$caso4`b`@.`n`n");
	                    output("`3Poi conclude dicendoti, \"`@È un piacere fare affari con te, resto a tua completa disposizione se ");
	                    output("volessi usufruire dei miei servigi in futuro`3\"`n`n");
	                    addnav("Torna da Thantalas","terre_draghi.php?op=allenatore");
	                }else{
	                    output("`3Thantalas gonfia il petto possente, e sembra che le sue orecchie si allunghino più di ");
	                    output("quanto non lo siano attualmente, e prorompe con voce baritonale,`n`n\"`\$Non hai `b`&40`b`\$ gemme!! ");
	                    output(" Non tentare di imbrogliarmi!!`3\"`n`n");
	                    addnav("Torna da Thantalas","terre_draghi.php?op=allenatore");
	                }
	            }else if ($_GET['op']=="risveglio"){
	                $sql = "SELECT * FROM draghi WHERE user_id = '".$session['user']['acctid']."'";
	                $result = db_query($sql) or die(db_error(LINK));
	                $rowd = db_fetch_assoc($result);
	                if ($rowd[vita_restante] > 0) {
	                    $sql = "SELECT * FROM terre_draghi_dove WHERE id_player='".$session['user']['acctid']."'";
	                    $result = db_query($sql) or die(db_error(LINK));
	                    $rowtdd = db_fetch_assoc($result);
	                    $sql = "SELECT * FROM terre_draghi WHERE id='".$rowtdd[id_terra]."'";
	                    $result = db_query($sql) or die(db_error(LINK));
	                    $rowtd = db_fetch_assoc($result);
	                    output("`3Ti risvegli dopo una lunga guardia. Le tue stanche ossa protestano per lo sforzo a cui sono state ");
	                    output("costrette dalla snervante sorveglianza.`n`@");
	                    if (is_new_day()){
	                       output("Per non essere avvantaggiato nei confronti degli altri abitanti del villaggio, ");
	                       output("l'unica cosa che puoi fare è tornare al villaggio.`n`n");
	                       addnav("Torna al Villaggio","village.php");
	                    }else{
	                       output("Cosa vuoi fare?`n`n");
	                       if($rowtd[totale_bonus] > 0) {
	                           addnav("Torna al Vulcano","terre_draghi.php?op=vulcano");
	                       }
	                       addnav("Abbandona Territorio","terre_draghi.php?op=abbandona&id_terra=$rowtdd[id_terra]");
	                       addnav("Q?Sorveglia Territorio (`%Logout`0)","terre_draghi.php?op=sorveglia");
	                    }
	                }else{
	                    output("`4Ti risvegli per scoprire che il tuo drago è stato ucciso. Controlla la posta per scoprire quale ");
	                    ouput("guerriero ha approfittato del tuo riposo per sorprenderti nel sonno e derubarti di ciò che ti eri ");
	                    output("guadagnato con sacrificio.`nPurtroppo non puoi far altro che tornare al villaggio.`n`n");
	                    addnav("Torna al Villaggio","village.php");
	                }
	            }else if($_GET['op']=="esplora_b"){
	                addnav("Torna al Vulcano","terre_draghi.php?op=vulcano");
	                output("`6Da fare`n");
	
	            }else if($_GET['op']=="esplora_t"){
	                addnav("Torna al Vulcano","terre_draghi.php?op=vulcano");
	                output("`6Da fare`n");
	            }
            
           	} 
        } else {
            output("`2`c`bVerso le Terre dei Draghi`b`c`2`n`n");
            output("`2Purtroppo il tuo `@Drago`2 è gravemente ferito, pertanto non ti è possibile intraprendere il viaggio verso le `@Terre dei Draghi`2 con un compagno in queste condizioni. ");
            output("Scornato torni al villaggio.`n");
            addnav("Torna al villaggio","village.php");
        }
    }else{
        output("`2`c`bVerso le Terre dei Draghi`b`c`2`n`n");
        output("`2Il viaggio verso le `@Terre dei Draghi`2 richiede l'attraversamento di molti territori inospitali, e l'unico modo per poter effettuarlo è quello di possedere un `@Drago`2, cosa che tu non hai. Purtroppo non puoi far altro che restare ");
        output("al villaggio e cercare di procurartene uno per poter raggiungere quei luoghi mitici e leggendari tanto declamati dai bardi e dai cantastorie.");
        addnav("Torna al Villaggio","village.php");
    }
} else {
    output("`2`c`bVerso le Terre dei Draghi`b`c`2`n`n");
    output("`8Sei uno spirito, e come tale non hai un corpo. L'unico viaggio che puoi intraprendere è quello che porta alla `4Terra delle Ombre`8, al cospetto del suo signore inconstrastato `&Ramius`8 !!");
    addnav("Terra delle Ombre","shades.php");
}

page_footer();

?>