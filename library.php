<?php
require_once("common.php");
require_once("common2.php");
//checkday(); (tolto per problema scatto newday durante scelta titolo)
$razzza=array(1=>"dei `2Troll",
2=>"degli `^Elfi",
3=>"degli `&Umani",
4=>"dei `#Nani",
5=>"dei `3Druidi",
6=>"dei `@Goblin",
7=>"degli `%Orchi",
8=>"dei `\$Vampiri",
9=>"dei `%Lich",
10=>"delle `&Fanciulle delle Nevi",
11=>"degli `%Oni",
12=>"dei `3Satiri",
13=>"dei `#Giganti della Tempesta",
14=>"dei `\$Barbari",
15=>"delle `6Amazzoni",
16=>"dei `#Titani",
17=>"dei `4Demoni",
18=>"dei `(Centauri",
19=>"dei `^Licantropi",
20=>"dei `(Minotauri",
21=>"dei `^Cantastorie",
22=>"degli `@Eletti"
);
$donationpoints = ($session['user']['donation']-$session['user']['donationspent']);
$session['return']="";
page_header("La Biblioteca di Plato");
$session['user']['locazione'] = 144;
output("`@`c`bLa Biblioteca di Plato`b`c`n");
if ($_GET['op']=='') {
    addnav("Notizie");
    addnav("N?Notizie Giornaliere","news.php?ret=".urlencode($_SERVER['REQUEST_URI']));
    addnav("E?Elenco Guerrieri","list.php?ret=".urlencode($_SERVER['REQUEST_URI']));
    addnav("G?Sala della Gloria","hof.php?ret=".urlencode($_SERVER['REQUEST_URI']));
    addnav("Vai agli Scaffali");
    
    if (getsetting("library", "") == "0" AND $session['user']['superuser'] == 0) {
		$scaffalivuoti = true ;
	}else{
		$sqlc = "SELECT categoria FROM biblioteca_volumi group by categoria ORDER BY categoria ASC";
		$resultc = db_query($sqlc) or die(sql_error($sqlc));
	    $countrowc = db_num_rows($resultc);
	    if($countrowc == 0){
		    $scaffalivuoti = true ;
		}else{
			for ($i=0; $i<$countrowc; $i++){
		        $rowc = db_fetch_assoc($resultc);
		        $categoria = $rowc['categoria'];
		    	addnav("$categoria","library.php?op=scaffali&subop=$categoria");
		    }
		}
	}		
    
    addnav("Studi");
    addnav("P?Pergamena della Saggezza","library.php?op=skills");
    addnav("S?Pergamena della Storia","library.php?op=hplimit");
    if ($session['user']['reincarna'] >=1 OR $session['user']['euro'] >0) {
        addnav("T?Tomo della Nobiltà","library.php?op=title");
        addnav("C?Tomo delle Creature","library.php?op=mount");
        addnav("T?Tomo dei Draghi","library.php?op=draghi");
    }
    addnav("Opzioni");
   
    addnav("O?Vai all'Offertorio","library.php?op=offertorio");
    addnav("??`^`bMiniFAQ`b","hints.php?ret=".urlencode($_SERVER['REQUEST_URI']));
    addnav("Z?GDR","library.php?op=gdr");
    if (getsetting("nomedb","logd") == "logd2"){
       addnav("Notizie GDR","library.php?op=notizie");
    }
    output("`2Entri titubante nella biblioteca del monastero, ben nota come uno dei più antichi e ricchi poli culturali del mondo conosciuto.`n");
    output("Con la bocca spalancata per la meraviglia, il tuo sguardo cade sulle infinite schiere di altissimi scaffali in legno di mogano ove,
    	 con estrema cura, sono riposti migliaia di manoscritti risalenti alle epoche più disparate. ");
    output("Non un solo granello di polvere offusca lo splendore e la perfezione delle superbe rilegature di quei tomi antichissimi, segno della
    	 infinita devozione con la quale i monaci conservano questo incredibile tesoro. `n`n");
    output("La parete alla tua destra è invece completamente occupata da centinaia di ripiani recanti in bella mostra migliaia di pergamene ben 
    	conservate, apparentemente disposte secondo rigidi criteri di classificazione: una discreta sezione è dedicata agli editti reali, preceduti 
    	da quelli risalenti all'epoca imperiale; più in alto s'intravedono antiquate mappe del mondo e, meraviglia delle meraviglie, il ripiano più 
    	alto ospita addirittura quelli che sembrano, ma forse è solo frutto tua immaginazione, degli antichi papiri egizi! `n");
    output("Più avanti c'è un'immensa area dedicata alle opere di filosofia, filologia e teologia, seguita da quelle più piccole, ma non meno ricche 
    	di alchimia, medicina, matematica, zoologia e galenica. `n`n");
    output("`@La tua sete di sapere ha finalmente trovato ciò di cui aveva bisogno! `n`n");
    output("`2Trattieni a stento un grido di gioia, notando appena in tempo un piccolo cartello inchiodato allo stipite della porta che invita i presenti 
    	al silenzio, in segno di rispetto per gli altri e per il luogo stesso. ");
    output("Tremante per l'emozione, non vedi l'ora di poter sfogliare, maneggiandoli con la massima cura, quei rarissimi volumi.`n`n");
    output("`^Cosa vorresti consultare oggi?");
    if ($scaffalivuoti) {
		output("`2`n`nL'anziano bibliotecario ti comunica desolato che al momento non è possibile consultare le opere ospitate negli scaffali: i monaci, 
			spiega, stanno procedendo con una laboriosa opera di catalogazione e trascrizione manuale di antichi testi in lingue straniere o morte. "); 
		output("`nCon un sorriso aggiunge che apprezza il tuo desiderio di conoscenza, invitandoti a tornare in questo luogo di studi tra qualche tempo 
			e ti indica inoltre una piccola cassettina di legno dove potrai, con una piccola offerta, dare il tuo contributo a sostegno dell'opera 
			interminabile dei suoi confratelli. `n`n");    
    } else {
	    output("`2`n`nL'anziano bibliotecario apprezza il tuo desiderio di conoscenza dicendoti che tutte le opere presenti sono a tua disposizione e con un 
	    	bonario sorriso ti indica inoltre una piccola cassettina di legno dove potrai, con una piccola offerta, dare il tuo contributo a sostegno 
	    	dell'opera interminabile dei suoi confratelli. `n`n");     
	}   
} elseif ($_GET['op']=='offertorio') {  
	
	if ($_GET['subop']=='') {
		
		$oro_offertorio = getsetting("offertorio","0");		
		output("`2Una cassetta di legno contiene attualmente `^$oro_offertorio monete d'oro `2 gentilmente donate dai signorotti locali che vogliono aiutare e sostenere il certosino lavoro dei monaci.");
		output("Gli amanuensi, i pergamenari, i librari e i cartolari hanno bisogno di contributi per continuare la loro importantissima opera di catalogazione, conservazione e restauro di queste favolose e antiche opere.");
		output("Anche una tua piccola offerta sarebbe molto gradita e l'anziano bibliotecario potrà esprimerti tutta la sua riconoscenza.");
		
	} elseif ($_GET['subop']=='offerta') {
		if ($_POST['money']==""){
                output("`2Hai deciso di donare un tuo piccolo contributo alla conoscenza. I monaci del monastero ti saranno riconoscenti.");
                output("<form action='library.php?op=offertorio&subop=offerta' method='POST'><input name='money' value='0'><input type='submit' class='button' value='Offri'>`n",true);
                addnav("","library.php?op=offertorio&subop=offerta");
            }else{
              $money = abs((int)$_POST['money']);
                if ($money>$session['user']['gold']){
                  output("`%`bNon hai tutto quest'oro, cerca di essere serio e non prendere in giro i poveri monaci!`b ");
                }else{

                    output("`2Metti `^$money pezzi d'oro`2 nella cassetta.`n");
                    
                    if ($money > 0) {
                    
                    	$offertime = getsetting("pvptimeout",600);
	        			$offertimeout = date("Y-m-d H:i:s",strtotime(date("r")."-$offertime seconds"));
	                    $sql = "SELECT lastoffer FROM offertorio WHERE acctid = ".$session['user']['acctid']." ";
	                    $result = db_query($sql) or die(db_error(LINK));
	                    $row = db_fetch_assoc($result);
	            		$scorsaofferta = $row[lastoffer];
            		
	            		if ($scorsaofferta > $offertimeout){
	            			output("`n`2`bHai appena fatto una offerta ricevendo una una benedizione per la tua generosità, non ne puoi effettuare una seconda, il Monastero non è un luogo di mercificazione della cattiveria !`b");
	                    }else{
	                        output("`n`2Felice della piccola offerta che hai fatto torni all'ingresso della biblioteca.");
		                    switch(e_rand(1,10)){
	        				case 1: 
	        					if ($money>100) {
	        						output("`2`nIncroci L'anziano bibliotecario che sorridendo ti ringrazia e ti benedice per la tua grande generosità. `nIn cuor tuo ti senti
	        								migliore per la buona azione appena compiuta e sei certo che la Biblioteca ne avrà giovamento. ");
	        						$benedizione=3;
	        					}else{
		        					output("`2`nIncroci L'anziano bibliotecario che sorridendo ti ringrazia e ti benedice per la tua generosità. `nIn cuor tuo ti senti
	        								migliore per la buona azione appena compiuta e sei convinto che l'oro da te donato verrà speso bene. ");
	        						$benedizione=2;
		        				}		
	        					break;
	        				case 3: case 4: case 5: case 6: case 7: case 8: case 9: case 10:
	        					output("`2`nIn cuor tuo ti senti più buono per la tua generosa azione e sai che la Biblioteca ne avrà giovamento. ");
	        					$benedizione=1;
	        					break; 
	        				}
	        				
	        				$dataofferta = date("Y-m-d H:i:s"); 			
							$sql = "SELECT * FROM offertorio WHERE acctid = ".$session['user']['acctid']." " ;
							$result = db_query($sql) or die(db_error(LINK));
							$countrow = db_num_rows($result);
							if ( $countrow == 0 ) {
								$sql = "INSERT INTO offertorio (acctid,lastoffer) VALUES( '".$session['user']['acctid']."','".$dataofferta."' ) " ;
								$result = db_query($sql) or die(db_error(LINK));
							} else {
								$sql = "UPDATE offertorio SET lastoffer = '".$dataofferta."' WHERE acctid = '".$session['user']['acctid']."' " ;
								$result = db_query($sql) or die(db_error(LINK));
							}	
	
	        				$session['user']['evil']=$session['user']['evil']-$benedizione;
		                    $session['user']['gold']-=$money;
		                    $oro_offertorio = getsetting("offertorio","0");
		                    $oro_offertorio = $oro_offertorio + $money;
		                    savesetting("offertorio",$oro_offertorio);
		                    debuglog("ha messo $money pezzi d'oro nella cassetta dell'offertorio e ha scalato $benedizione punti cattiveria");
		                }    
	                }else{
		              	output("`n`2`bCerca di essere serio e non prendere in giro i poveri monaci!`b`2 ");
		            }  		
                }
           }
	} elseif  ($_GET['subop']=='saccheggia'){
		
		if ( $session['user']['evil'] > 200 ) {
			$incremento = 50;
		} else {	
			$incremento = 200;
		}	
		$session['user']['evil'] = $session['user']['evil'] + $incremento;
		debuglog("incrementa la sua cattiveria di $incremento per aver forzato l'offertorio in Biblioteca. EVIL=".$session['user']['evil']);
		switch(e_rand(1,10)){
        case 1: 
        	output("`2Dopo aver forzato la serratura dell'offertorio, incominci a riempirti le tasche di monete quando, improvvisamente senti un rumore di passi avvicinarsi.
        		Istintivamente, rimetti tutto a posto alla bene e meglio e ti allontani dalla cassetta, giusto in tempo prima che lo Sceriffo accompagnato dalle sue Guardie faccia il suo ingresso 
        		in Biblioteca. Con l'aria più innocente possibile saluti Lo Sceriffo e i suoi scagnozzi sperando che non si accorgano di nulla. ");
        	break;
        case 2: 
        	$oro_offertorio = getsetting("offertorio","0");
			$oro_rubato = intval ( $oro_offertorio * 0.05 );
        	output("`2Dopo aver forzato la serratura dell'offertorio, incominci a riempirti le tasche di monete quando, improvvisamente senti un rumore di passi avvicinarsi.
        		Istintivamente, rimetti tutto a posto alla bene e meglio e ti allontani dalla cassetta, giusto in tempo prima che lo Sceriffo accompagnato dalle sue Guardie faccia il suo ingresso 
        		in Biblioteca. Con l'aria più innocente possibile saluti Lo Sceriffo e i suoi scagnozzi sperando che non si accorgano di nulla. ");
        		output("`nSei comunque riuscito a rubare `^$oro_rubato`2 monete e di sicuro la tua cattiveria verrà aumentata visto la malvagia azione che hai appena commesso ");
			$oro_offertorio = $oro_offertorio - $oro_rubato;
			savesetting("offertorio",$oro_offertorio); 
        	break;
        case 3: case 4: case 5: case 6: case 7: case 8: case 9:
        	output("`2Dopo aver forzato la serratura dell'offertorio, stai per cominciare a riempirti le tasche di monete d'oro quando ti senti battere su una spalla. Ti volti e vedi la faccia dello  
				Sceriffo che, spuntato da non si sa dove, sghignazza e facendoti sollevare di peso dalle sue Guardie ti scorta in galera ");
			if ($session['user']['gold'] > 0 ) {
				$oro_offertorio = getsetting("offertorio","0");
				$oro_requisito = intval ( $session['user']['gold'] * .25 );
				$session['user']['gold'] = $session['user']['gold'] - $oro_requisito;
				$oro_offertorio = $oro_offertorio + $oro_requisito;
				savesetting("offertorio",$oro_offertorio);
				output("Una parte dell'oro che hai in tasca $oro_requisito monete viene inoltre messa nella cassetta come risarcimento dei danni che hai provocato ");
				
			}	
			$session['user']['jail'] = 2;
		    $name=$session['user']['name'];
		    addnews("$name`1 è stat".($session['user']['sex']?"a":"o")." sbattut".($session['user']['sex']?"a":"o")." in Prigione dallo Sceriffo per aver depredato la cassetta delle offerte al Monastero!");
		    debuglog("è stato catturato dallo Sceriffo in Biblioteca e sbattuto in Prigione. EVIL=".$session['user']['evil']);		    
		    break;
		case 10: 
			$oro_offertorio = getsetting("offertorio","0");
			$oro_rubato = intval ( $oro_offertorio * 0.2 );
			$session['user']['gold'] = $session['user']['gold'] + $oro_rubato;
			output("`2Dopo aver forzato la serratura dell'offertorio, ti riempi le tasche di monete, ma vieni disturbato da un rumore di passi che si avvicinano.
				Cerchi di rimettere tutto a posto alla bene e meglio e ti allontani dalla cassetta assumendo l'aria più innocente che puoi. "); 
			output("`nSei riuscito a rubare `^$oro_rubato`2 monete e di sicuro la tua cattiveria verrà aumentata visto la malvagia azione che hai appena commesso ");
			$oro_offertorio = $oro_offertorio - $oro_rubato;
			savesetting("offertorio",$oro_offertorio);   
		   break; 
        }
	}

} elseif ($_GET['op']=='scaffali') {
	$categoria = $_GET['subop'];
    output("`c`bVolumi disponibili alla consultazione Categoria $categoria`b`c`n");
    $sql = "SELECT * FROM biblioteca_volumi WHERE categoria='$categoria' AND pubblicato=1 ORDER BY titolo ASC";
    $result = db_query($sql) or die(sql_error($sql));
    output("<table border=0 cellpadding=2 cellspacing=1 bgcolor='#999999'>",true);
    output("<tr class='trhead'><td><b>Titolo</b></td><td><b>Autore</b></td><td><b>Data Pubblicazione</b></td><td><b>Leggi</b></td></tr>",true);
    $countrow = db_num_rows($result);
    for ($i=0; $i<$countrow; $i++){
        $row = db_fetch_assoc($result);
        $timestamp = strtotime(($row['data_pubblicazione'])." days");
        $data_pubblicazione = ottienidatadelgiorno($timestamp);
        output("<tr class='".($i%2?"trdark":"trlight")."'><td>",true);
        output("`^".$row['titolo']."`0");
        output("</td><td align=center>",true);
        output("`^".$row['autore']."`0");
        output("</td><td>",true);
        output("`^$data_pubblicazione`0");
        output("</td><td><A href=library.php?op=volumi_leggi_brano&id=$row[id]>`^Leggi`0</a>",true);
        output("</td></tr>",true);
        addnav("","library.php?op=volumi_leggi_brano&id=$row[id]");
    }
    output("</table>",true); 
    addcommentary();
    viewcommentary("Biblioteca Scaffali", "critica",20,5); 
} elseif ($_GET['op']=='volumi_leggi_brano') {
    $sql = "SELECT * FROM biblioteca_volumi WHERE id='".$_GET['id']."'";
    $result = db_query($sql) or die(sql_error($sql));
    $row = db_fetch_assoc($result);
    $id=$row[id];
    $titolo=$row['titolo'];
    $categoria=$row['categoria'];
    $brano=nl2br($row['testo']);
    output("`b`@$titolo`b`n`n`&$brano`0",true);
} elseif ($_GET['op']=='gdr') {
    output("`c`bConcorsi GDR nel mondo di Legend of the green dragon`b`c`n");
    $sql = "SELECT * FROM gdr_contest WHERE data_concorso > '2001-01-01' GROUP BY data_concorso ORDER BY data_concorso DESC";
    $result = db_query($sql) or die(sql_error($sql));
    output("<table border=0 cellpadding=2 cellspacing=1 bgcolor='#999999'>",true);
    output("<tr class='trhead'><td><b>data concorso</b></td><td><b>vincitore</b></td></tr>",true);
    $countrow = db_num_rows($result);
    for ($i=0; $i<$countrow; $i++){
    //for($i=0;$i<db_num_rows($result);$i++){
    $row = db_fetch_assoc($result);
        output("<tr class='".($i%2?"trdark":"trlight")."'>",true);
        $sql = "SELECT * FROM gdr_contest WHERE data_concorso = '".$row['data_concorso']."' ORDER BY voto DESC";
        $resultv = db_query($sql) or die(sql_error($sql));
        $rowv = db_fetch_assoc($resultv);
        output("<td><A href=library.php?op=gdr_leggi&data=$row[data_concorso]>`^$row[data_concorso]`0</a>",true);
        output("</td><td>$rowv[nome]",true);
        output("</td></tr>",true);
        addnav("","library.php?op=gdr_leggi&data=$row[data_concorso]");
    }
    output("</table>",true);
    addcommentary();
    viewcommentary("Biblioteca GDR", "critica",20,5);
} elseif ($_GET['op']=='gdr_leggi') {
    output("`c`bBrani inviati in questo concorso`b`c`n");
    $sql = "SELECT * FROM gdr_contest WHERE data_concorso='".$_GET['data']."' ORDER BY voto DESC";
    $result = db_query($sql) or die(sql_error($sql));
    output("<table border=0 cellpadding=2 cellspacing=1 bgcolor='#999999'>",true);
    output("<tr class='trhead'><td><b>Classificato</b></td><td><b>Player</b></td><td><b>Titolo</b></td><td><b>Data Arrivo</b></td><td><b>Leggi</b></td></tr>",true);
    $countrow = db_num_rows($result);
    for ($i=0; $i<$countrow; $i++){
    //for($i=0;$i<db_num_rows($result);$i++){
        $row = db_fetch_assoc($result);
        output("<tr class='".($i%2?"trdark":"trlight")."'><td>",true);
        output("`^".($i+1)."`0");
        output("</td><td align=center>",true);
        output("`^".$row['nome']."`0");
        output("</td><td align=center>",true);
        output("`^".$row['titolo']."`0");
        output("</td><td>",true);
        output("`^".$row['data']."`0");
        output("</td><td><A href=library.php?op=gdr_leggi_brano&id=$row[id]>`^Leggi`0</a>",true);
        output("</td></tr>",true);
        addnav("","library.php?op=gdr_leggi_brano&id=$row[id]");
    }
    output("</table>",true);
} elseif ($_GET['op']=='gdr_leggi_brano') {
    $sql = "SELECT * FROM gdr_contest WHERE id='".$_GET['id']."'";
    $result = db_query($sql) or die(sql_error($sql));
    $row = db_fetch_assoc($result);
    $id=$row[id];
    $titolo=$row['titolo'];
    $brano=nl2br($row['testo']);
    output("`@$titolo`n`n`&$brano`0",true);

} elseif ($_GET['op']=='skills') {
    addnav("Dona 1 Gemma","library.php?op=skilllist");
    addnav("Scordatelo","library.php");
    output("`3\"`@Voglio apprendere le differenze delle abilità di ogni singola razza,`3\" dici.`n`n");
    output("`3\"`\$SILENZIO!`3\" dice un anziano uomo da dietro.`n`n`0Ti giri e ti trovi faccia a faccia Plato, il proprietario della biblioteca.`n`n");
    output("`3\"`\$Come posso esserti d'aiuto?`3\" chiede.`n`n");
    output("`3\"`@Vorrei imparare di più sulle abilità specifiche delle varie classi di specialità.`3\"`n`n");
    output("`3\"`\$Bene allora, cerchi la Pergamena della Saggezza.  Queste pergamene sono il frutto di secoli di lavoro
    dei nostri amanuensi.  Sono antiche e molto fragili.  Non mi piace separarmene, ma credo di potertele far leggere per la
    ridicola donazione di 1 gemma alla biblioteca.`3\"`n");
} elseif ($_GET['op']=='skilllist') {
    if ($session['user']['gems'] > 0) {
        $session['user']['gems']--;
        addnav("Classi di Specialità");
        addnav("`\$Arti Oscure","library.php?op=showskill&specialty=1");
        addnav("`%Poteri Mistici","library.php?op=showskill&specialty=2");
        addnav("`^Furto","library.php?op=showskill&specialty=3");
        addnav("`#Militare","library.php?op=showskill&specialty=4");
        addnav("`\$Seduzione","library.php?op=showskill&specialty=5");
        if ($session['user']['dragonkills']>1){
            addnav("`^Tattica","library.php?op=showskill&specialty=6");
        }
        if ($session['user']['dragonkills']>3){
            addnav("`@Abilità della Roccia","library.php?op=showskill&specialty=7");
        }
        if ($session['user']['dragonkills']>5){
            addnav("`#Retorica","library.php?op=showskill&specialty=8");
        }
        if ($session['user']['dragonkills']>7){
            addnav("`%Muscoli","library.php?op=showskill&specialty=9");
        }
        if ($session['user']['dragonkills']>9){
            addnav("`!Natura","library.php?op=showskill&specialty=10");
        }
        if ($session['user']['dragonkills']>11){
            addnav("`&Clima","library.php?op=showskill&specialty=11");
        }
        addnav("Abilità");
        //addnav("Pergamene e Tomi","library.php?op=showskill&specialty=98");
        addnav("Tipi di Abilità","library.php?op=showskill&specialty=99");

        output("`3\"`\$Ahhh, grazie, sei stato molto gentile con la tua offerta.
            Verrà utilizzata per il restauro delle pergamene più importanti della biblioteca!`3\"");
    } else
    output("`3\"`\$Bene bene bene.  Che cosa abbiamo qui?  Non proseguire oltre pensando tu possa avere qualsiasi cosa a gratis.
            Se vuoi studiare queste pergamene ti costerà 1 gemma.  Le troverai qui quando tornerai...forse.`3\"");

} elseif ($_GET['op']=='showskill') {
    addnav("Classi di Specialità");
    addnav("`\$Arti Oscure","library.php?op=showskill&specialty=1");
    addnav("`%Poteri Mistici","library.php?op=showskill&specialty=2");
    addnav("`^Furto","library.php?op=showskill&specialty=3");
    addnav("`#Militare","library.php?op=showskill&specialty=4");
    addnav("`\$Seduzione","library.php?op=showskill&specialty=5");
    if ($session['user']['dragonkills']>1){
        addnav("`^Tattica","library.php?op=showskill&specialty=6");
    }
    if ($session['user']['dragonkills']>3){
        addnav("`@Abilità della Roccia","library.php?op=showskill&specialty=7");
    }
    if ($session['user']['dragonkills']>5){
        addnav("`#Retorica","library.php?op=showskill&specialty=8");
    }
    if ($session['user']['dragonkills']>7){
        addnav("`%Muscoli","library.php?op=showskill&specialty=9");
    }
    if ($session['user']['dragonkills']>9){
        addnav("`!Natura","library.php?op=showskill&specialty=10");
    }
    if ($session['user']['dragonkills']>11){
        addnav("`&Clima","library.php?op=showskill&specialty=11");
    }
    addnav("Abilità");
    //addnav("Pergamene e Tomi","library.php?op=showskill&specialty=98");
    addnav("Tipi di Abilità","library.php?op=showskill&specialty=99");
    switch($_GET['specialty']) {
        case 1:
        output("<big>`b`\$Arti Oscure`b`n`n</big>",true);
        output("`#Ciurma di Scheletri
                    `n`%Tipo: `&Seguace
                    `n`%Turni: `^5
                    `n`%Danno Min: `^0
                    `n`%Danno Max: `^(Tuo Livello / 2) + 1
                    `n`%Descrizione: `^Evoca `@(Tuo Livello / 3) + 1`^ seguaci.
            `n`n");
        output("`#Voodoo
                    `n`%Tipo: `&Seguace
                    `n`%Turni: `^1
                    `n`%Danno Min: `^Tuo Attacco * 1.5
                    `n`%Danno Max: `^Tuo Attacco * 3
                    `n`%Descrizione: `^Infili uno spillone nel nemico.
            `n`n");
        output("`#Maledizione
                    `n`%Tipo: `&Difesa
                    `n`%Turni: `^1
                    `n`%Attacco Nemico: `^50%
                    `n`%Descrizione: `^Il nemico fa metà del danno.
            `n`n");
        output("`#Avvizzisci Anima
                    `n`%Tipo: `&Difesa
                    `n`%Turni: `^3
                    `n`%Attacco Nemico: `^0%
                    `n`%Difesa Nemica: `^0%
                    `n`%Descrizione: `^Il nemico ha 0 attacco e 0 difesa.
            `n`n");
        break;
        case 2:
        output("<big>`b`\$Poteri Mistici`b</big>`n`n",true);
        output("`#Rigenerazione
                    `n`%Tipo: `&Rigenerazione
                    `n`%Turni: `^5
                    `n`%Rigenerazione: `^(tuo livello) HP
                    `n`%Descrizione: `^Ti guarisce per (tuo livello) HP.
            `n`n");
        output("`#Pugno di Terra
                    `n`%Tipo: `&Seguace
                    `n`%Turni: `^5
                    `n`%Danno Min: `^1
                    `n`%Danno Max: `^Tuo livello * 3
                    `n`%Descrizione: `^Evoca un pugno per attaccare il tuo nemico.
            `n`n");
        output("`#Drena Vita
                    `n`%Tipo: `&Scudo
                    `n`%Turni: `^5
                    `n`%Recupera Vita: `^(tuo danno al nemico) HP
                    `n`%Descrizione: `^Recuperi vita per il danno che infliggi al nemico.
            `n`n");
        output("`#Aura di Fulmini
                    `n`%Tipo: `&Scudo
                    `n`%Turni: `^5
                    `n`%Schermo Riflettente: `^2 * (danni subiti dal nemico)
                    `n`%Descrizione: `^Crei uno schermo che colpisce il nemico per il doppio dei danni fatti a te.
            `n`n");
        break;
        case 3:
        output("<big>`b`\$Furto`b</big>`n`n",true);
        output("`#Insulto
                    `n`%Tipo: `&Difesa
                    `n`%Turni: `^5
                    `n`%Attacco Nemico: `^50%
                    `n`%Descrizione: `^Il nemico infligge la metà dei danni.
            `n`n");
        output("`#Lama Avvelenata
                    `n`%Tipo: `&Attacco
                    `n`%Turni: `^5
                    `n`%Attacco: 120%
                    `n`%Descrizione: `^Fai 1.2 volte il tuo danno normale.
            `n`n");
        output("`#Attacco Nascosto
                    `n`%Tipo: `&Difesa
                    `n`%Turni: `^3
                    `n`%Attacco Nemico: 20%
                    `n`%Descrizione: `^Il nemico fa solo il 20% del suo attacco normale.
            `n`n");
        output("`#Pugnalata alle Spalle
                    `n`%Tipo: `&Attacco
                    `n`%Turni: `^3
                    `n`%Danno Min: `^tuo attacco / 2
                    `n`%Descrizione: `^Infliggi 3 volte il tuo attacco normale.
            `n`n");
        break;
        case 4:
        output("<big>`b`\$Militare`b</big>`n`n",true);
        output("`#Colpi Multipli
                    `n`%Tipo: `&Seguace
                    `n`%Turni: `^5
                    `n`%Danno Min: `^0
                    `n`%Danno Max: `^(Tuo Livello / 2) + 1
                    `n`%Descrizione: `^Colpisci il nemico per `@(Tuo Livello / 3) + 1`^ volte.
            `n`n");
        output("`#Colpo Mirato
                    `n`%Tipo: `&Attacco
                    `n`%Turni: `^1
                    `n`%Danno Max: `^Tuo Attacco * 3
                    `n`%Danno Min: `^Tuo Attacco * 1.5
                    `n`%Descrizione: `^Colpisci in una zona critica il nemico.
            `n`n");
        output("`#Ferite Mirate
                    `n`%Tipo: `&Difesa
                    `n`%Turni: `^5
                    `n`%Attacco Nemico: `^50%
                    `n`%Descrizione: `^Adotti una posizione di difesa ed il nemico ti infligge la metà dei danni.
            `n`n");
        output("`#Berserk
                    `n`%Tipo: `&Attacco
                    `n`%Turni: `^7
                    `n`%Danno Min: `^10
                    `n`%Danno Max: `^tuo livello * 3
                    `n`%Descrizione: `^La furia si impossessa di te, moltiplicando i danni inflitti al nemico.
            `n`n");
        break;
        case 5:
        output("<big>`b`\$Seduzione`b</big>`n`n",true);
        output("`#Sirene
                    `n`%Tipo: `&Seguace
                    `n`%Turni: `^4
                    `n`%Danno Max: `^(tuo livello / 2) + 1
                    `n`%Descrizione: `^Evoca `@(Tuo Livello / 3) + 1`^ sirene.
            `n`n");
        output("`#Danza
                    `n`%Tipo: `&Seguace
                    `n`%Turni: `^5
                    `n`%Danno Min: `^1
                    `n`%Danno Max: `^tuo livello * 3
                    `n`%Descrizione: `^Colpisci il nemico aumentando i danni provocati.
            `n`n");
        output("`#Fascino
                    `n`%Tipo: `&Difesa
                    `n`%Turni: `^4
                    `n`%Attacco Nemico: `^0%
                    `n`%Difesa Nemico: `^50%
                    `n`%Descrizione: `^Il nemico ha 0% attacco e 50% difesa per.
            `n`n");
        output("`#Sonno
                    `n`%Tipo: `&Difesa/Seguace
                    `n`%Turni: `^5
                    `n`%Attacco Nemico: `^0%
                    `n`%Difesa Nemico: `^0%
                    `n`%Danno Min: `^1
                    `n`%Danno Max: `^tuo livello * 3
                    `n`%Descrizione: `^Il nemico ha 0% attacco, 0% difesa e provochi danni maggiori.
            `n`n");
        break;
        case 6:
        output("<big>`b`\$Tattica`b</big>`n`n",true);
        output("`#Reclute
                    `n`%Tipo: `&Seguace
                    `n`%Turni: `^5
                    `n`%Danno Max: `^(tuo livello) * 2
                    `n`%Descrizione: `^Evoca `@(Tuo Livello / 3) + 1`^ reclute che combattono con te.
            `n`n");
        output("`#Sorpresa
                    `n`%Tipo: `&Seguace
                    `n`%Turni: `^8
                    `n`%Danno Min: `^1
                    `n`%Danno Max: `^tuo livello * 2
                    `n`%Descrizione: `^Provochi danni maggiori, fino a (tuo livello) * 2.
            `n`n");
        output("`#Attacco Notturno
                    `n`%Tipo: `&Scudo/Seguace
                    `n`%Turni: `^4
                    `n`%Attacco: `^200%
                    `n`%Difesa Nemico: `^0%
                    `n`%Descrizione: `^Provochi il doppio dei danni e la difesa del nemico è azzerata.
            `n`n");
        output("`#Arti Marziali
                    `n`%Tipo: `&Attacco/Scudo
                    `n`%Turni: `^5
                    `n`%Attacco: `^300%
                    `n`%Schermo Riflettente: `^2 * (danni subiti dal nemico)
                    `n`%Descrizione: `^Provochi il triplo dei danni e infliggi 2 volte i danni subiti.
            `n`n");
        break;
        case 7:
        output("<big>`b`\$Abilità della Roccia`b</big>`n`n",true);
        output("`#Rocce Cadenti
                    `n`%Tipo: `&Seguace
                    `n`%Turni: `^5
                    `n`%Danno Max: `^(Tuo Livello / 2) + 5
                    `n`%Descrizione: `^Evoca `@(Tuo Livello / 3) + 1`^ rocce.
            `n`n");
        output("`#Pelle Dura
                    `n`%Tipo: `&Difesa
                    `n`%Turni: `^5
                    `n`%Difesa: `^*3
                    `n`%Descrizione: `^La tua difesa viene moltiplicata per 3 per 5 turni.
            `n`n");
        output("`#Pugno di Roccia
                    `n`%Tipo: `&Seguace
                    `n`%Turni: `^4
                    `n`%Danno Min: `^1
                    `n`%Danno Max: `^(Tuo livello * 4
                    `n`%Descrizione: `^Evoca 1 Pugno di Roccia che infligge fino a (tuo livello) * 4 di danno.
            `n`n");
        output("`#Eco Montano
                    `n`%Tipo: `&Difesa
                    `n`%Turni: `^6
                    `n`%Difesa Nemico: `^0%
                    `n`%Attacco Nemico: `^0%
                    `n`%Descrizione: `^Il nemico è terrorizzato dalle tue minacce, il suo attacco e la difesa sono nulle.
            `n`n");
        break;
        case 8:
        output("<big>`b`\$Retorica`b</big>`n`n",true);
        output("`#Dizionari
                    `n`%Tipo: `&Seguace/Attacco
                    `n`%Turni: `^4
                    `n`%Danno Max: `^tuo livello / 3
                    `n`%Attacco: `^*1.2
                    `n`%Descrizione: `^Evochi 3 dizionari che colpiscono il nemico e il tuo attacco è potenziato.
            `n`n");
        output("`#Paroloni
                    `n`%Tipo: `&Difesa
                    `n`%Turni: `^5
                    `n`%Attacco Nemico: `^0%
                    `n`%Descrizione: `^L'attacco del nemico viene azzerato.
            `n`n");
        output("`#Scioglilingua
                    `n`%Tipo: `&Attacco
                    `n`%Turni: `^4
                    `n`%Attacco: `^(tuo livello / 4) + 1
                    `n`%Descrizione: `^Il tuo attacco viene potenziato.
            `n`n");
        output("`#Discorsi
                    `n`%Tipo: `&Rigenerazione
                    `n`%Turni: `^10
                    `n`%Rigenerazione: `^(tuo livello * 2) HP
                    `n`%Descrizione: `^Rigeneri (tuo livello * 2) HP ad ogni round.
            `n`n");
        break;
        case 9:
        output("<big>`b`\$Muscoli`b</big>`n`n",true);
        output("`#Gragnuola di Colpi
                    `n`%Tipo: `&Attacco
                    `n`%Turni: `^4
                    `n`%Attacco: `^(tuo livello / 10) + 1
                    `n`%Descrizione: `^Il tuo attacco viene potenziato.
            `n`n");
        output("`#Flessibilità
                    `n`%Tipo: `&Rigenerazione
                    `n`%Turni: `^6
                    `n`%Rigenerazione: `^(tuo livello) HP
                    `n`%Descrizione: `^Rigeneri (tuo livello) HP.
            `n`n");
        output("`#Capezzoli Infuriati
                    `n`%Tipo: `&Seguace
                    `n`%Turni: `^4
                    `n`%Danno Min: `^1
                    `n`%Danno Max: `^tuo livello * 3.
            `n`n");
        output("`#Lozione Abbronzante
                    `n`%Tipo: `&Scudo/Seguace
                    `n`%Turni: `^6
                    `n`%Schermo Riflettente: `^1.5 * (danni subiti dal nemico)
                    `n`%Danno Max: `^tuo livello * 1.5
                    `n`%Descrizione: `^Rifletti 1.5 danni subiti e provochi danni maggiori.
            `n`n");
        break;
        case 10:
        output("<big>`b`\$Natura`b</big>`n`n",true);
        output("`#Aiuto Animale
                    `n`%Tipo: `&Seguace
                    `n`%Turni: `^4
                    `n`%Danno Min: `^tuo livello / 3
                    `n`%Danno Max: `^tuo livello
                    `n`%Descrizione: `^Evoca (tuo livello / 4) + 1 animali che attaccano i tuoi nemici.
            `n`n");
        output("`#Infestazione di Scarafaggi
                    `n`%Tipo: `&Attacco
                    `n`%Turni: `^5
                    `n`%Attacco: `^* 2
                    `n`%Descrizione: `^Raddoppi i danni inflitti al nemico con l'aiuto degli scarafaggi.
            `n`n");
        output("`#Artigli delle Aquile
                    `n`%Tipo: `&Attacco/Seguace
                    `n`%Turni: `^5
                    `n`%Danno Max: `^tuo livello * 2
                    `n`%Attacco: `^*(tuo livello / 10) + 1.
                    `n`%Descrizione: `^Moltiplica il tuo attacco per (tuo livello / 10) + 1 e aumenta i danni.
            `n`n");
        output("`#Bigfoot
                    `n`%Tipo: `&Seguace
                    `n`%Turni: `^6
                    `n`%Danno Min: `^tuo livello * 2
                    `n`%Danno Max: `^tuo livello * 5
                    `n`%Descrizione: `^Rifletti 1.5 danni subiti e provochi danni maggiori.
            `n`n");
        break;
        case 11:
        output("<big>`b`\$Clima`b</big>`n`n",true);
        output("`#Folate di Vento
                    `n`%Tipo: `&Rigenerazione
                    `n`%Turni: `^10
                    `n`%Rigenerazione: `^(tuo livello / 2) HP
                    `n`%Descrizione: `^Recuperi (tuo livello / 2) HP ogni turno.
            `n`n");
        output("`#Tornado
                    `n`%Tipo: `&Seguace/Attacco
                    `n`%Turni: `^5
                    `n`%Danno Max: `^tuo livello * 1.1
                    `n`%Attacco: `^(tuo livello / 7.5)
                    `n`%Descrizione: `^Moltiplica il tuo attacco per (tuo livello / 7.5) e aumenta i danni.
            `n`n");
        output("`#Pioggia
                    `n`%Tipo: `&Difesa
                    `n`%Turni: `^6
                    `n`%Difesa Nemico: `^0%
                    `n`%Attacco Nemico: `^50%.
                    `n`%Descrizione: `^Dimezza l'attacco del nemico e azzera la sua difesa.
            `n`n");
        output("`#Gelo Polare
                    `n`%Tipo: `&Seguace/Difesa
                    `n`%Turni: `^6
                    `n`%Difesa Nemico: `^0%
                    `n`%Danno Max: `^tuo livello * 2
                    `n`%Descrizione: `^Azzera la difesa del nemico e aumenta i danni.
            `n`n");
        break;
        case 99:
        output("`#Le Abilità (Attacchi Speciali) sono un modo per farti conoscere quali abilità puoi usare contemporaneamente
            `n`nPuoi usare un attacco speciale di ogni tipo alla volta.
            `n`nLe differenti abilità sono: `&Attacco, Difesa, Rigenerazione, Seguace e Scudo`#.
            `n`nPuoi avere anche altre abilità su di te in forma di incantesimi, pergamene, pozioni, animali, o facendo visita al padrone della taverna Cedrik.");
        break;
    }
} elseif ($_GET['op']=='hplimit') {
    addnav("Razze");
    addnav("Troll","library.php?op=rank&race=1");
    addnav("Elfi","library.php?op=rank&race=2");
    addnav("Umani","library.php?op=rank&race=3");
    addnav("Nani","library.php?op=rank&race=4");
    addnav("Druidi","library.php?op=rank&race=5");
    addnav("Goblin","library.php?op=rank&race=6");
    addnav("Orchi","library.php?op=rank&race=7");
    addnav("Vampiri","library.php?op=rank&race=8");
    addnav("Lich","library.php?op=rank&race=9");
    addnav("Fanciulle delle Nevi","library.php?op=rank&race=10");
    addnav("Oni","library.php?op=rank&race=11");
    addnav("Satiri","library.php?op=rank&race=12");
    if ($donationpoints >= 100) {
        addnav("Giganti delle Tempeste","library.php?op=rank&race=13");
        addnav("Barbari","library.php?op=rank&race=14");
        addnav("Amazzoni","library.php?op=rank&race=15");
        addnav("Titani","library.php?op=rank&race=16");
        addnav("Demoni","library.php?op=rank&race=17");
        addnav("Centauri","library.php?op=rank&race=18");
        addnav("Licantropi","library.php?op=rank&race=19");
        addnav("Minotauri","library.php?op=rank&race=20");
        addnav("Cantastorie","library.php?op=rank&race=21");
        addnav("Eletti","library.php?op=rank&race=22");
    }
    $dkhp = 0;
    $dkat = 0;
    $dkde = 0;
    $dkff = 0;
    $item=$session['user']['dragonpoints'];
    if (is_array($item)) $temp = $item;
    else $temp = unserialize($item);
    while(list($key, $val) = @each($temp)) {
        if ($val=="hp") $dkhp++;
        if ($val=="at") $dkat++;
        if ($val=="de") $dkde++;
        if ($val=="ff") $dkff++;
    }
    output("`@Entri in una zona della biblioteca malamente illuminata.  Sui ripiani ci sono pergamene che raccontano la storia di tutte le razze
    conosciute.  Estrai la prima pergamena, e trovi alcune informazioni molto interessanti che ti riguardano.  Dice:`n`n");
    output("`#Benvenuto ".$session['user']['name']."`#,`n`n");
    if ($session['user']['dragonkills']) {
        output("Hai ucciso `@Il Drago Verde`# ".$session['user']['dragonkills']." volt".($session['user']['dragonkills']>1?"e.":"a.")."`n`n");
        output("Guadagni ...");
        if ($dkhp) output("`&".($dkhp*5)." `@Hit Points`#, ");
        if ($dkat) output("`&".$dkat." `\$Punt".($dkat>1?"i":"o")." Attacco`#, ");
        if ($dkde) output(" `&".$dkde." `(Punt".($dkde>1?"i":"o")." Difesa `#");
        output("grazie a".($session['user']['dragonkills']>1?"i":"l")." punt".($session['user']['dragonkills']>1?"i":"o")." drago spesi.`n`n");
        if ($dkff) output("Guadagni anche `&".$dkff." `#turn".($dkff>1?"i":"o")." foresta ogni giorno!`n`n");
    }
    if ($session['user']['reincarna'] > 0 AND $session['user']['bonusfight'] > 0) {
       output("`#Essendo reincarnato, hai anche `^".$session['user']['bonusfight']." `#turn".($session['user']['bonusfight']>1?"i":"o")." foresta ");
       output("ogni giorno, guadagnati nelle precedenti reincarnazioni!`n`n");
    }
    //output("`n`#Il limite massimo di HP per il tuo Rango è `&$hplimit`# (`&".($dkhp*5)."`# guadagnati da from dragon points).`n`n");
    output("`@Guardandoti attorno, ti rendi conto che puoi apprendere molto delle altre razze studiando le pergamene che parlano di loro.");
} elseif ($_GET['op']=='rank') {
    addnav("Razze");
    addnav("Troll","library.php?op=rank&race=1");
    addnav("Elfi","library.php?op=rank&race=2");
    addnav("Umani","library.php?op=rank&race=3");
    addnav("Nani","library.php?op=rank&race=4");
    addnav("Druidi","library.php?op=rank&race=5");
    addnav("Goblin","library.php?op=rank&race=6");
    addnav("Orchi","library.php?op=rank&race=7");
    addnav("Vampiri","library.php?op=rank&race=8");
    addnav("Lich","library.php?op=rank&race=9");
    addnav("Fanciulle delle Nevi","library.php?op=rank&race=10");
    addnav("Oni","library.php?op=rank&race=11");
    addnav("Satiri","library.php?op=rank&race=12");
    if ($donationpoints >= 100) {
        addnav("Giganti delle Tempeste","library.php?op=rank&race=13");
        addnav("Barbari","library.php?op=rank&race=14");
        addnav("Amazzoni","library.php?op=rank&race=15");
        addnav("Titani","library.php?op=rank&race=16");
        addnav("Demoni","library.php?op=rank&race=17");
        addnav("Centauri","library.php?op=rank&race=18");
        addnav("Licantropi","library.php?op=rank&race=19");
        addnav("Minotauri","library.php?op=rank&race=20");
        addnav("Cantastorie","library.php?op=rank&race=21");
        addnav("Eletti","library.php?op=rank&race=22");
    }
    output("`@Sfili una pergamena che racconta la storia ".$razzza[$_GET['race']]."`@ e la srotoli delicatamente.`nRecita:`n`n");
    switch ($_GET['race']) {
        case 1:
        output("`2Essendo un troll, guadagni un punto di attacco fin dal tuo primo giorno in qualità di guerriero.`n");
        break;
        case 2:
        output("`^Essendo un elfo, guadagni un punto di difesa fin dal tuo primo giorno in qualità di guerriero.`n");
        break;
        case 3:
        output("`&Essendo umano, guadagni un combattimento supplementare nella foresta ogni giorno.`n");
        break;
        case 4:
        output("`#Essendo un nano, guadagni denaro extra dai combattimenti nella foresta!`n");
        break;
        case 5:
        output("`3Essendo un druido, guadagni un punto di utilizzo nei Poteri Mistici.`n");
        break;
        case 6:
        output("`6Essendo un goblin, guadagni un punto attacco e un punto difesa ma hai meno combattimenti foresta.`n");
        break;
        case 7:
        output("`xEssendo un `XOrco`x, guadagni punti attacco e difesa ma hai meno combattimenti foresta.`n");
        break;
        case 8:
        output("`FEssendo un `fVampiro`F, guadagni due punti di utilizzo nelle Arti Oscure, ma perdi un punto di difesa.`n");
        break;
        case 9:
        output("`5Poichè sei un `%Lich`5, sei particolarmente debole e perdi un punto di attacco e un punto di difesa, ma guadagni 2 punti nella Arti Oscure oltre a 50 favori con `\$Ramius`5, tuo amico da sempre`n");
        break;
        case 10:
        output("`3Essendo una `&Fanciulla delle Nevi`3 sei di costituzione debole a causa della tua natura semi-eterea, ma possedendo il dominio degli elementi compensi l'attacco con la difesa.`nGuadagni 2 punti di utilizzo in `&Clima`3, un punto di difesa, ma perdi un punto di attacco.`n");
        break;
        case 11:
        output("`2Essendo un `%Oni`2 sei poco resistente agli attacchi dei tuoi nemici, ma la tua natura demoniaca ti ha dotato di una notevole forza. Inoltre, prima di condividere il tuo corpo con un demone, ricordi di essere stato un valente condottiero. Perdi un punto di difesa, ma guadagni un punto di attacco e 2 punti di utilizzo in Tattica!`n");
        break;
        case 12:
        output("`2Essendo un satiro non hai particolare doti, ma la natura ti è amica e ti consente di fuggire dai tuoi avversari più facilmente.`nInoltre guadagni 1 punto si utilizzo in `@Natura`2!`n");
        break;
        case 13:
        output("`3I `#Giganti della Tempesta `3sono conosciuti per la loro forza eccezionale, e la loro maestria nell'uso delle masse di roccia.`n");
        output("`3Guadagni un punto di attacco ed un punto di utilizzo in `#Abilità della Roccia`3!!`n");
        break;
        case 14:
        output("`4I `\$Barbari `4sono conosciuti per la loro ferocia, e la loro maestria nell'uso delle armi.`n");
        output("`4Guadagni due punti di attacco, un punto difesa ed un bonus di razza!`n");
        break;
        case 15:
        output("`5Le `%Amazzoni `5sono famose per la loro agilità e la loro maestria nell'uso dell'arco.`n");
        output("`5Guadagni un punto di attacco ed un punto difesa ed un bonus di razza!`n");
        output("Inoltre, essendo la tua razza molto rispettosa della natura, guadagni un punto di utilizzo in `@Natura`5!");
        break;
        case 16:
        output("`3I `#Titani`3, difensori della fede di `6Sgrios`3, sono conosciuti per la loro maestosa imponenza fisica, che dà loro un naturale vantaggio nel combattimento.`n");
        output("`3Guadagni `^tre`3 punti attacco e `^due`3 punti difesa ed un potenziamento per tutti i tuoi combattimenti!`n");
        output("Inoltre, grazie al tuo imponente fisico, guadagni due punti di utilizzo in `^Muscoli`3!");
        break;
        case 17:
        output("`8I `\$Demoni`8, malefici collaboratori di `XKarnak`8, sono stati relegati da tempo nelle profondità delle viscere della terra, dove hanno sviluppato doti insospettate per  il loro fisico.`n");
        output("`8Guadagni `(due`8 punti attacco e `(due`8 punti difesa ed un potenziamento per tutti i tuoi combattimenti!`n");
        output("`8Inoltre, grazie alla lunga permanenza nel sottosuolo vicino agli esseri malefici che vi abitano, apprendi due punti di utilizzo in `\$Arti Oscure`8 e `\$Ramius`8 ti concede `(50`8 favori!`n");
        break;
        case 18:
        output("`8I `%Centauri `8sono un popolo fiero e famoso per la loro indomita indole, che ha permesso loro di non essere mai stati soggiogati.`n");
        output("`8Guadagni `(un`8 punto difesa ed un potenziamento per tutti i tuoi combattimenti!`n");
        output("Inoltre, essendo la tua razza dedita alle arti fisiche, guadagni un punto di utilizzo in `^Muscoli`8!");
        break;
        case 19:
        output("`6I `^Lincantropi `6erano una razza sulla via dell'estinzione, fino a quando un fiero appartenente alla loro razza, Luthien, li ha riportati ai fasti di un tempo.`n");
        output("`6Guadagni `^due`6 punti attacco e `^due`6 punti difesa ed un potenziamento per tutti i tuoi combattimenti!`n");
        output("Inoltre, essendo la tua razza parte del regno animale, guadagni un punto di utilizzo in `@Natura`6!");
        break;
        case 20:
        output("`8I `(Minotauri`8, mitica razza discendente della stirpe di Tarmatack, figlio degenere nato dall'incrocio di un toro ed una donna, ha da sempre prediletto le arti fisiche.`n");
        output("`6Guadagni `^3`6 punti attacco e `^un`6 punto difesa ed un potenziamento per tutti i tuoi combattimenti!`n");
        output("Inoltre, essendo la tua razza parte per metà del regno animale, guadagni un punto di utilizzo in `@Natura`6!");
        break;
        case 21:
        output("`gI `FCantastorie`g, più che una razza sono una professione tramandata di padre in figlio, e poco o nulla si sa delle loro abilità.`n");
        output("`gI vantaggi di questa razza sono un segreto, che solo i `FCantastorie`g conoscono, e che custodiscono gelosamente.`n");
        break;
        case 22:
        output("`xGli `XEletti`x, razza discendente dal `@Drago Verde`x stesso, sono la genia dell'umana Takara, che accoppiatasi alla mitica creatura ha dato vita ad una stirpe estremamente combattiva.`n");
        output("`xGuadagni `X`b2`b`x punti attacco e `X`b3`b`x punti difesa ed un potenziamento per tutti i tuoi combattimenti!`n");
        output("`xInoltre, essendo la tua razza amica della razza dei draghi, guadagni un punto di utilizzo in `XNatura`x!`n");
        break;

    }
} elseif ($_GET['op']=='title') {
    output("Raggiungi il ripiano più alto e sfili un grande volume.  All'interno vedi la storia della tua famiglia.  Ecco Sam il Mago, Lara la Ranger e molti molti altri...");
    output("`n`nContinuando nella lettura, ti imbatti in una pagina bianca.  È come se il contenuto di questa pagina non sia ancora stato scritto.
    Un'idea luminosa compare nella tua mente. Puoi iniziare a raccontare di te, delle tue avventure e battaglie.
    Ma hai bisogno di un titolo per te stesso. ".$session['user']['login']." il Magnifico... no.. quale potrebbe essere?");
    output("`n`nChiedi ad un gentiluomo dietro di te se puoi acquistare una penna e dell'inchiostro da lui.  ");
    if ($donationpoints < 50) {
        output("Ti dice che ti costerà 100 gemme scrivere il tuo nome.`n`n`&Vuoi pagarlo ?");
        if ($session['user']['gems']>=100)
        addnav("Paga 100 gemme","library.php?op=titlego&method=gems");
        else output("`n`n`\$È un peccato che tu non possieda 100 gemme ... ");
    } else {
        addnav("Usa 50 Punti","library.php?op=titlego&method=lodge");
        if ($session['user']['gems'] >= 100) addnav("Offri 100 gemme","library.php?op=titlego&method=gems");
        output("`n`nEstrai la tua carta di credito Casa del Drago e la mostri al vecchietto.`nEgli dice che ti costerà 50 Punti Donazione scrivere il tuo nome.`nVuoi pagarlo?");
    }
} elseif ($_GET['op']=='titlego') {
    $session['user']['name'] = preg_replace('/\s+/', ' ',$session['user']['name']);
    $session['user']['ctitle'] = preg_replace('/\s+/', ' ',$session['user']['ctitle']);
    if ($session['user']['ctitle'] == " ") $session['user']['ctitle'] = "";
    if ($session['user']['ctitle'] != "") {
        $session['user']['name'] = substr($session['user']['name'],0,(strlen($session['user']['name'])-strlen($session['user']['ctitle'])));
    }
    if (substr($session['user']['name'], -1) == " ") $session['user']['name'] = substr($session['user']['name'], 0, -1);
    if (substr($session['user']['name'], 0, 1) == " ") $session['user']['name'] = substr($session['user']['name'], 1);
    //Excalibur: aggiunta per preview del testo inserito
    rawoutput("<script language='JavaScript'>
    function previewtext(t){
        var out = \"<span class=\'colLtWhite\'>".addslashes(appoencode($session['user']['name']))." \";
        var end = '</span>';
        var x=0;
        var y='';
        var z='';
        for (; x < t.length; x++){
            y = t.substr(x,1);
            if (y=='<'){
                out += '&lt;';
                continue;
            }else if(y=='>'){
                out += '&gt;';
                continue;
            }else if (y=='`'){
                if (x < t.length-1){
                    z = t.substr(x+1,1);
                    if (z=='0'){
                        out += '</span>';
                    }else if (z=='`'){
                        out += z;
                    }else if (z=='1'){
                        out += '</span><span class=\'colDkBlue\'>';
                    }else if (z=='2'){
                        out += '</span><span class=\'colDkGreen\'>';
                    }else if (z=='3'){
                        out += '</span><span class=\'colDkCyan\'>';
                    }else if (z=='4'){
                        out += '</span><span class=\'colDkRed\'>';
                    }else if (z=='5'){
                        out += '</span><span class=\'colDkMagenta\'>';
                    }else if (z=='6'){
                        out += '</span><span class=\'colDkYellow\'>';
                    }else if (z=='7'){
                        out += '</span><span class=\'colDkWhite\'>';
                    }else if (z=='8'){
                        out += '</span><span class=\'colDkOrange\'>';
                    }else if (z=='9'){
                        out += '</span><span class=\'colDkBlack\'>';
                    }else if (z=='v'){
                        out += '</span><span class=\'colDkViolet\'>';
                    }else if (z=='!'){
                        out += '</span><span class=\'colLtBlue\'>';
                    }else if (z=='@'){
                        out += '</span><span class=\'colLtGreen\'>';
                    }else if (z=='#'){
                        out += '</span><span class=\'colLtCyan\'>';
                    }else if (z=='$'){
                        out += '</span><span class=\'colLtRed\'>';
                    }else if (z=='%'){
                        out += '</span><span class=\'colLtMagenta\'>';
                    }else if (z=='^'){
                        out += '</span><span class=\'colLtYellow\'>';
                    }else if (z=='&'){
                        out += '</span><span class=\'colLtWhite\'>';
                    }else if (z=='('){
                        out += '</span><span class=\'colLtOrange\'>';
                    }else if (z=='V'){
                        out += '</span><span class=\'colLtViolet\'>';
                    }else if (z==')'){
                        out += '</span><span class=\'colLtBlack\'>';
                    }else if (z=='x'){
                        out += '</span><span class=\'colDkBrown\'>';
                    }else if (z=='X'){
                        out += '</span><span class=\'colLtBrown\'>';
                    }else if (z=='f'){
                        out += '</span><span class=\'colBlue\'>';
                    }else if (z=='F'){
                        out += '</span><span class=\'colblueviolet\'>';
                    }else if (z=='g'){
                        out += '</span><span class=\'colLime\'>';
                    }else if (z=='G'){
                        out += '</span><span class=\'colXLtGreen\'>';
                    }else if (z=='r'){
                        out += '</span><span class=\'colRose\'>';
                    }else if (z=='R'){
                        out += '</span><span class=\'coliceviolet\'>';
                    }else if (z=='a'){
                        out += '</span><span class=\'colAttention\'>';
                    }else if (z=='A'){
                        out += '</span><span class=\'colWhiteBlack\'>';
                    }else if (z=='s'){
                        out += '</span><span class=\'colBack\'>';
                    }else if (z=='S'){
                        out += '</span><span class=\'colredBack\'>';
                    }else if (z=='q'){
                        out += '</span><span class=\'colVomito\'>';
                    }else if (z=='Q'){
                        out += '</span><span class=\'colaquamarine\'>';
                    }else if (z=='e'){
                        out += '</span><span class=\'collightsalmon\'>';
                    }else if (z=='E'){
                        out += '</span><span class=\'colsalmon\'>';
                    }else if (z=='j'){
                        out += '</span><span class=\'colSenape\'>';
                    }else if (z=='J'){
                        out += '</span><span class=\'colDkSenape\'>';
                    }else if (z=='p'){
                        out += '</span><span class=\'colPrugna\'>';
                    }
                    x++;
                }
            }else{
                out += y;
            }
        }
        document.getElementById(\"previewtext\").innerHTML=out+end+'<br/>';
    }
    </script>
    ");  //Excalibur: fine


    output("Inizia a pensare ad un titolo per te stesso, qualcosa che venga ricordato ma non troppo potente da spaventare gli altri...`n");
    output("`r`n(Per inserire un apostrofo `` dovete scrivere `a[ALT+096][ALT+096]`r --> `S");
    rawoutput("``",true);
    output("`r .`n");
    output("`r E' accettato come apostrofo anche il simbolo ' , scritto normalmente.`n");
    output("`rPer cambiare colore `a[ALT+096][CARATTERE CAMBIO COLORE]`r --> `S");
    rawoutput("`[CARATTERE CAMBIO COLORE]",true);
    output("`r )`n`n");
    /*output("<form action=\"$REQUEST_URI\" method='POST'>`@$message`n
    <input name='insertcommentary[$section]' id='commentary' onKeyUp='previewtext(document.getElementById(\"commentary\").value);'; size='40' maxlength='".(200-$tll)."'>
    <input type='hidden' name='talkline' value='$talkline'>
    <input type='hidden' name='section' value='$section'>
    <input type='submit' class='button' value='Aggiungi'>".
    */
    output("<form method='POST' action=\"library.php?op=titlesave&method=".$_GET['method']."\">",true);
    //output("`@".$session['user']['login']." ");
    output("<input name='newctitle' id='titnob' onKeyUp='previewtext(document.getElementById(\"titnob\").value);'; size=35 maxlength=36>",true);
    output("<input type='submit' class='button' value='Datti un titolo'>",true);
    //output("</form>",true);
    rawoutput("<div id='previewtext'></div></form><br/>",true); //Excalibur: Aggiunta per anteprima

    output("`n`&Nota: Se scegli un nome sconveniente, verrà rimosso ed il notaio cittadino ti infliggerà una multa equa per la distruzione della sua esistenza.");
    addnav("Ho cambiato idea","library.php?op=cambioidea");
    addnav("","library.php?op=titlesave&method=".$_GET['method']);
} elseif ($_GET['op']=='cambioidea') {
    $session['user']['ctitle'] = preg_replace('/\s+/', ' ',$session['user']['ctitle']);
    if ($session['user']['ctitle'] == " ") $session['user']['ctitle'] = "";
    if ($session['user']['ctitle'] != "") $session['user']['name'] .=" ".$session['user']['ctitle'];
    redirect ("library.php");
} elseif ($_GET['op']=='titlesave') {
    if ($_GET['method'] == "gems"){
       $session['user']['gems']-=100;
    }else{
        $session['user']['donationspent']+=50;
    }
    $tmp = $session['user']['name'];
    $session['user']['ctitle'] = " ";
    $newctitle=preg_replace("/(\/|\\\)++/","",$_POST['newctitle']);
    //$newctitle=preg_replace("(\"|\')","",$newctitle);
    $newctitle=preg_replace("(\")","",$newctitle);
    $newctitle=preg_replace("'[`][^ib123456789!@#$%^&()vVxXfFgGrRaAsSqQjJeEp`]'","",$newctitle);
    $session['user']['ctitle'] .= $newctitle;
    $session['user']['ctitle'] = preg_replace('/\s+/', ' ',$session['user']['ctitle']);
    //$session['user']['name'] = $session['user']['name']." ".$session['user']['ctitle'];
    if ($session['user']['ctitle'] == " ") {
        $session['user']['ctitle'] = "";
    }else{
        $session['user']['name'] .= $session['user']['ctitle'];
    }
    if ($_GET['method'] == "gems"){
        debuglog("Paga 100 gemme per acquisire il titolo ".$session['user']['ctitle']);
    }else{
        debuglog("Usa 50 punti donazione per acquisire il titolo ".$session['user']['ctitle']);
        $sql = "INSERT INTO donazioni (nome,idplayer,usi,tipo)
                     VALUES ('titolo personale','".$session['user']['acctid']."','".$session['user']['reincarna']."','R(".$session['user']['reincarna'].")')";
        db_query($sql) or die(db_error($link));
    }
    $mailmessage = $tmp."`2 ha comprato un titolo nobiliare, ed ora è conosciuto come ".$session['user']['name'];
    report(3,"`3Titolo Nobiliare",$mailmessage,"titolonobiliare");
    output("`n`&Il vecchietto scrive il tuo nome sul libro. Da ora in poi ti chiamerai ".$session['user']['name']."`&.`n");
} elseif ($_GET['op']=='mount') {
    output("Estrai dallo scaffale un vecchio, polveroso volume e lo apri.`n`nDopo qualche minuto ti rendi conto che
    puoi dare un nome alla tua creatura riempiendo il modulo allegato e spedendolo, assieme al pagamento di ");
/*    if ($donationpoints <= 25) {
        if ($session['user']['gems']>=50) {
            output("50 gemme al notaio cittadino.`n`n");
            output("Vuoi dare un nome al tuo animale per 50 gemme?");
            addnav("Paga 50 gemme","library.php?op=mountgo&method=gems");
        } else output("`n`n`&Che peccato tu non abbia 50 gemme...");
    } else {
        output("25 punti donazione ");
        if ($session['user']['gems'] >= 50) output(", oppure di 50 gemme, ");
        output("al notaio cittadino.`n`n");
        output("Vuoi dare un nome al tuo animale per 25 punti donazione?");
        addnav("Usa 25 Punti","library.php?op=mountgo&method=lodge");
        if ($session['user']['gems'] >= 50) addnav("Offri 50 gemme","library.php?op=mountgo&method=gems");
    }
*/  output("100 punti donazione al notaio cittadino.`n`n");
    if ($donationpoints >= 100) {
        output("Vuoi dare un nome al tuo animale per 100 punti donazione?");
        addnav("Usa 100 Punti Donazione","library.php?op=mountgo&method=lodge");
    } else {
        output("`n`n`&Purtoppo non hai i 100 punti richiesti.");
    }
} elseif ($_GET['op']=='mountgo') {
    rawoutput("<script language='JavaScript'>
    function previewtext(t){
        var out = \"<span class=\'colLtWhite\'>\";
        var end = \" il ".$playermount['mountname2'].".</span>\";
        var x=0;
        var y='';
        var z='';
        for (; x < t.length; x++){
            y = t.substr(x,1);
            if (y=='<'){
                out += '&lt;';
                continue;
            }else if(y=='>'){
                out += '&gt;';
                continue;
            }else if (y=='`'){
                if (x < t.length-1){
                    z = t.substr(x+1,1);
                    if (z=='0'){
                        out += '</span>';
                    }else if (z=='`'){
                        out += z;
                    }else if (z=='1'){
                        out += '</span><span class=\'colDkBlue\'>';
                    }else if (z=='2'){
                        out += '</span><span class=\'colDkGreen\'>';
                    }else if (z=='3'){
                        out += '</span><span class=\'colDkCyan\'>';
                    }else if (z=='4'){
                        out += '</span><span class=\'colDkRed\'>';
                    }else if (z=='5'){
                        out += '</span><span class=\'colDkMagenta\'>';
                    }else if (z=='6'){
                        out += '</span><span class=\'colDkYellow\'>';
                    }else if (z=='7'){
                        out += '</span><span class=\'colDkWhite\'>';
                    }else if (z=='8'){
                        out += '</span><span class=\'colDkOrange\'>';
                    }else if (z=='9'){
                        out += '</span><span class=\'colDkBlack\'>';
                    }else if (z=='v'){
                        out += '</span><span class=\'colDkViolet\'>';
                    }else if (z=='!'){
                        out += '</span><span class=\'colLtBlue\'>';
                    }else if (z=='@'){
                        out += '</span><span class=\'colLtGreen\'>';
                    }else if (z=='#'){
                        out += '</span><span class=\'colLtCyan\'>';
                    }else if (z=='$'){
                        out += '</span><span class=\'colLtRed\'>';
                    }else if (z=='%'){
                        out += '</span><span class=\'colLtMagenta\'>';
                    }else if (z=='^'){
                        out += '</span><span class=\'colLtYellow\'>';
                    }else if (z=='&'){
                        out += '</span><span class=\'colLtWhite\'>';
                    }else if (z=='('){
                        out += '</span><span class=\'colLtOrange\'>';
                    }else if (z=='V'){
                        out += '</span><span class=\'colLtViolet\'>';
                    }else if (z==')'){
                        out += '</span><span class=\'colLtBlack\'>';
                    }else if (z=='x'){
                        out += '</span><span class=\'colDkBrown\'>';
                    }else if (z=='X'){
                        out += '</span><span class=\'colLtBrown\'>';
                    }else if (z=='f'){
                        out += '</span><span class=\'colBlue\'>';
                    }else if (z=='F'){
                        out += '</span><span class=\'colblueviolet\'>';
                    }else if (z=='g'){
                        out += '</span><span class=\'colLime\'>';
                    }else if (z=='G'){
                        out += '</span><span class=\'colXLtGreen\'>';
                    }else if (z=='r'){
                        out += '</span><span class=\'colRose\'>';
                    }else if (z=='R'){
                        out += '</span><span class=\'coliceviolet\'>';
                    }else if (z=='a'){
                        out += '</span><span class=\'colAttention\'>';
                    }else if (z=='A'){
                        out += '</span><span class=\'colWhiteBlack\'>';
                    }else if (z=='s'){
                        out += '</span><span class=\'colBack\'>';
                    }else if (z=='S'){
                        out += '</span><span class=\'colredBack\'>';
                    }else if (z=='q'){
                        out += '</span><span class=\'colVomito\'>';
                    }else if (z=='Q'){
                        out += '</span><span class=\'colaquamarine\'>';
                    }else if (z=='e'){
                        out += '</span><span class=\'collightsalmon\'>';
                    }else if (z=='E'){
                        out += '</span><span class=\'colsalmon\'>';
                    }else if (z=='j'){
                        out += '</span><span class=\'colSenape\'>';
                    }else if (z=='J'){
                        out += '</span><span class=\'colDkSenape\'>';
                    }else if (z=='p'){
                        out += '</span><span class=\'colPrugna\'>';
                    }
                    x++;
                }
            }else{
                out += y;
            }
        }
        document.getElementById(\"previewtext\").innerHTML=out+'<span class=\'colLtGreen\'>'+end+'<br/>';
    }
    </script>");

    output("Strappi il modulo dal libro ed inizi a compilarlo...`n`n");
    output("<form method=post action=library.php?op=mountsave&method=".$_GET['method'].">",true);
    output("Nome Animale: <input name='newmountname' id='animname' onKeyUp='previewtext(document.getElementById(\"animname\").value);';size=35  maxlength=36 value=''> il ".$playermount['mountname2'].".`n",true);
//    output("Nome Animale: <input type=text name=newmountname size=35 maxsize=250 value=''> il ".$playermount['mountname'].".`n",true);
    rawoutput("<br><div id='previewtext'> il ".$playermount['mountname2'].".</div>",true);
    output("`n<input type=submit value='Dai nome'>",true);
    //    output("</form>",true);\\\\\\
    rawoutput("</form><br>",true);
    output("`&Nota: Se scegli un nome sconveniente, verrà rimosso ed il notaio cittadino ti infliggerà una multa equa per la distruzione della sua esistenza.");
    addnav("Ho cambiato idea","library.php");
    addnav("","library.php?op=mountsave&method=".$_GET['method']);
} elseif ($_GET['op']=='mountsave') {
    if ($_GET['method']=='gems'){
    $session['user']['gems']-=50;
    debuglog("ha speso 50 gemme per dare un nome al suo animale - NOME: ".$_POST['newmountname']);
    }else{
//    $session['user']['donationspent'] += 25;
    $session['user']['donationspent'] += 100;
    debuglog("ha speso 100 punti donazione per dare un nome al suo animale - NOME: ".$_POST['newmountname']);
    }
    $tmp=$session['user']['mountname'];
    $session['user']['mountname']=$_POST['newmountname'];
    output("`nDa ora in avanti chiamerai la tua creatura, ".$_POST['newmountname']." `@il ".$playermount['mountname2'].".`n");
    $mailmessage = "L'animale di ".$session['user']['login'].", che è un ".$playermount['mountname2']." è ora chiamato ".$session['user']['mountname'];
    if ($tmp!="") $mailmessage.="`n(vecchio nome: $tmp)";
    report(3,"`3Nome Creatura",$mailmessage,"nomecreatura");
} elseif ($_GET['op']=='draghi') {
    output("Estrai dallo scaffale un pesante tomo rilegato con scaglie dorate di drago e lo apri.`n`nDopo qualche minuto ti rendi conto che
    puoi dare un nome al tuo drago riempiendo il modulo allegato e spedendolo, assieme al pagamento di ");
/*    if ($donationpoints <= 25) {
        if ($session['user']['gems']>=50) {
            output("50 gemme al notaio cittadino.`n`n");
            output("Vuoi dare un nome al tuo animale per 50 gemme?");
            addnav("Paga 50 gemme","library.php?op=mountgo&method=gems");
        } else output("`n`n`&Che peccato tu non abbia 50 gemme...");
    } else {
        output("25 punti donazione ");
        if ($session['user']['gems'] >= 50) output(", oppure di 50 gemme, ");
        output("al notaio cittadino.`n`n");
        output("Vuoi dare un nome al tuo animale per 25 punti donazione?");
        addnav("Usa 25 Punti","library.php?op=mountgo&method=lodge");
        if ($session['user']['gems'] >= 50) addnav("Offri 50 gemme","library.php?op=mountgo&method=gems");
    }
*/  output("100 punti donazione al notaio cittadino.`n`n");
    if ($session['user']['id_drago'] == 0) {
        output("Ma tu non possiedi un drago!`n`n");
    }elseif ($donationpoints >= 100) {
        output("Vuoi dare un nome al tuo drago per 100 punti donazione?");
        addnav("Usa 100 Punti Donazione","library.php?op=dragogo&method=lodge");
    } else {
        output("`n`n`&Purtoppo non hai i 100 punti richiesti.");
    }
} elseif ($_GET['op']=='dragogo') {
    $sql = "SELECT * FROM draghi WHERE user_id='".$session['user']['acctid']."'";
    $result = db_query($sql) or die(db_error(LINK));
    $countrow = db_num_rows($result);
    if ($countrow == 1) {
        $row = db_fetch_assoc($result);
        rawoutput("<script language='JavaScript'>
        function previewtext(t){
            var out = \"<span class=\'colLtWhite\'>\";
            var end = \"</span>\";
            var x=0;
            var y='';
            var z='';
            for (; x < t.length; x++){
                y = t.substr(x,1);
                if (y=='<'){
                    out += '&lt;';
                    continue;
                }else if(y=='>'){
                    out += '&gt;';
                    continue;
                }else if (y=='`'){
                    if (x < t.length-1){
                        z = t.substr(x+1,1);
                        if (z=='0'){
                            out += '</span>';
                        }else if (z=='`'){
                            out += z;
                        }else if (z=='1'){
                            out += '</span><span class=\'colDkBlue\'>';
                        }else if (z=='2'){
                            out += '</span><span class=\'colDkGreen\'>';
                        }else if (z=='3'){
                            out += '</span><span class=\'colDkCyan\'>';
                        }else if (z=='4'){
                            out += '</span><span class=\'colDkRed\'>';
                        }else if (z=='5'){
                            out += '</span><span class=\'colDkMagenta\'>';
                        }else if (z=='6'){
                            out += '</span><span class=\'colDkYellow\'>';
                        }else if (z=='7'){
                            out += '</span><span class=\'colDkWhite\'>';
                        }else if (z=='8'){
                            out += '</span><span class=\'colDkOrange\'>';
                        }else if (z=='9'){
                            out += '</span><span class=\'colDkBlack\'>';
                        }else if (z=='v'){
                            out += '</span><span class=\'colDkViolet\'>';
                        }else if (z=='!'){
                            out += '</span><span class=\'colLtBlue\'>';
                        }else if (z=='@'){
                            out += '</span><span class=\'colLtGreen\'>';
                        }else if (z=='#'){
                            out += '</span><span class=\'colLtCyan\'>';
                        }else if (z=='$'){
                            out += '</span><span class=\'colLtRed\'>';
                        }else if (z=='%'){
                            out += '</span><span class=\'colLtMagenta\'>';
                        }else if (z=='^'){
                            out += '</span><span class=\'colLtYellow\'>';
                        }else if (z=='&'){
                            out += '</span><span class=\'colLtWhite\'>';
                        }else if (z=='('){
                            out += '</span><span class=\'colLtOrange\'>';
                        }else if (z=='V'){
                            out += '</span><span class=\'colLtViolet\'>';
                        }else if (z==')'){
                            out += '</span><span class=\'colLtBlack\'>';
                        }else if (z=='x'){
                            out += '</span><span class=\'colDkBrown\'>';
                        }else if (z=='X'){
                            out += '</span><span class=\'colLtBrown\'>';
                        }else if (z=='f'){
                            out += '</span><span class=\'colBlue\'>';
                        }else if (z=='F'){
                            out += '</span><span class=\'colblueviolet\'>';
                        }else if (z=='g'){
                            out += '</span><span class=\'colLime\'>';
                        }else if (z=='G'){
                            out += '</span><span class=\'colXLtGreen\'>';
                        }else if (z=='r'){
                            out += '</span><span class=\'colRose\'>';
                        }else if (z=='R'){
                            out += '</span><span class=\'coliceviolet\'>';
                        }else if (z=='a'){
                            out += '</span><span class=\'colAttention\'>';
                        }else if (z=='A'){
                            out += '</span><span class=\'colWhiteBlack\'>';
                        }else if (z=='s'){
                            out += '</span><span class=\'colBack\'>';
                        }else if (z=='S'){
                            out += '</span><span class=\'colredBack\'>';
                        }else if (z=='q'){
                            out += '</span><span class=\'colVomito\'>';
                        }else if (z=='Q'){
                            out += '</span><span class=\'colaquamarine\'>';
                        }else if (z=='e'){
                            out += '</span><span class=\'collightsalmon\'>';
                        }else if (z=='E'){
                            out += '</span><span class=\'colsalmon\'>';
                        }else if (z=='j'){
                            out += '</span><span class=\'colSenape\'>';
                        }else if (z=='J'){
                            out += '</span><span class=\'colDkSenape\'>';
                        }else if (z=='p'){
                            out += '</span><span class=\'colPrugna\'>';
                        }
                        x++;
                    }
                }else{
                    out += y;
                }
            }
            document.getElementById(\"previewtext\").innerHTML=out+'<span class=\'colLtGreen\'>'+end+'<br/>';
        }
        </script>");
    
        output("Strappi il modulo dal libro ed inizi a compilarlo...`n`n");
        output("<form method=post action=library.php?op=dragosave&method=".$_GET['method'].">",true);
        output("Nome Drago: <input name='newmountname' id='animname' onKeyUp='previewtext(document.getElementById(\"animname\").value);';size=35  maxlength=36 value=''> ",true);
        output("<input type=submit value='Dai nome'>",true);
        rawoutput("<br><div id='previewtext'></div>",true);
        rawoutput("</form><br>",true);
        output("`&Nota: Se scegli un nome sconveniente, verrà rimosso ed il notaio cittadino ti infliggerà una multa equa per la distruzione della sua esistenza.");
        addnav("Ho cambiato idea","library.php");
        addnav("","library.php?op=dragosave&method=".$_GET['method']);
    } else {
        output("C'è un errore con la gestione del tuo drago! Segnala questo problema allo staff!");
        addnav("Torna alla biblioteca","library.php");
    }    
} elseif ($_GET['op']=='dragosave') {
    $sql = "SELECT nome_drago FROM draghi WHERE user_id='".$session['user']['acctid']."'";
    $result = db_query($sql) or die(db_error(LINK));
    $row = db_fetch_assoc($result);
    if ($_GET['method']=='gems'){
        $session['user']['gems']-=50;
        debuglog("ha speso 50 gemme per dare un nome al suo drago - NOME: ".$_POST['newmountname']);
    }else{
//    $session['user']['donationspent'] += 25;
        $session['user']['donationspent'] += 100;
        debuglog("ha speso 100 punti donazione per dare un nome al suo drago - NOME: ".$_POST['newmountname']);
    }
    $sql = "UPDATE draghi SET nome_drago='".$_POST['newmountname']."' WHERE user_id='".$session['user']['acctid']."'";
    $result = db_query($sql) or die(db_error(LINK));
    output("`nDa ora in avanti il tuo drago sarà noto col nome ".$_POST['newmountname']." `@.`n");
    $mailmessage = "Il drago di ".$session['user']['login']." è ora chiamato ".$_POST['newmountname'];
    if ($row['nome_drago']!="") $mailmessage.="`n(vecchio nome: ".$row['nome_drago'].")";
    report(3,"`3Nome Drago",$mailmessage,"nomecreatura");
}
if($_GET['op']=="notizie" AND $_GET['az'] == ""){
    if($session['user']['superuser'] > 2){
        addnav("Aggiungi notizia","library.php?op=notizie&az=aggiungi");
    }
    $sql = "SELECT * FROM notiziegdr ORDER BY data ASC";
    $result = db_query($sql) or die(sql_error($sql));
    if (db_num_rows($result) != 0){
        output("<table border=0 align='center' cellpadding=2 cellspacing=1 bgcolor='#444444'>",true);
        output("<tr class='trhead'><td>Op</td><td><b>Data</b></td><td><b>Titolo</b></td></tr>",true);
        $countrow = db_num_rows($result);
        for ($i=0; $i<$countrow; $i++){
        //for ($i=0; $i < db_num_rows($result); $i++){
             $row = db_fetch_assoc($result);
             output("<tr>",true);
             if($session['user']['superuser'] > 2){
                 output("<td><A href=library.php?op=notizie&az=cancella&id=".$row['id'].">`!`bDel`b`0</a></td>",true);
             }else{
                 output("<td></td>",true);
             }
             output("<td>`#".$row['data']."</td><td>",true);
             output("<A href=library.php?op=notizie&az=".$row['id'].">`^".stripslashes($row['titolo'])."`0</a>",true);
             output("</td></tr>",true);
             addnav("","library.php?op=notizie&az=".$row['id']);
             addnav("","library.php?op=notizie&az=cancella&id=".$row['id']);
        }
        output("</table>`n`c`6Clicca sul titolo per leggere la notizia`c",true);
    }else{
        output("<big>`c`b`%Nessuna notizia disponibile !!!`b`c</big>",true);
    }
}elseif($_GET['op']=="notizie" AND $_GET['az'] == "cancella"){
    $sql = "SELECT * FROM notiziegdr WHERE id = ".$_GET['id'];
    $result = db_query($sql) or die(sql_error($sql));
    $row = db_fetch_assoc($result);
    output("`#Titolo Notizia: ".stripslashes($row['titolo'])."`n");
    output("Testo Notizia: ".stripslashes($row['testo'])."`n");
    output("`nSei sicuro di voler cancellare questa notizia?`n`n");
    output("<A href=library.php?op=notizie&az=cancella1&id=".$_GET['id'].">`bSI`b</a>`@, cancella.`n`n",true);
    output("<A href=library.php?op=notizie><big>`bNO`b</big></a>`\$, mi sono sbagliato.`n`n",true);
    addnav("","library.php?op=notizie&az=cancella1&id=".$_GET['id']);
    addnav("","library.php?op=notizie");
}elseif($_GET['op']=="notizie" AND $_GET['az'] == "cancella1"){
    $sql = "DELETE FROM notiziegdr WHERE id = ".$_GET['id'];
    $result = db_query($sql) or die(sql_error($sql));
    output("`b`\$Notizia Cancellata !!!!`n");
}elseif($_GET['op']=="notizie" AND $_GET['az'] == "aggiungi"){
    output("<form method='POST' action='library.php?op=notizie&az=aggiungi1'>",true);
    output("Titolo`n<input type='text' name='titolo' size='40'>",true);
    output("`n`nTesto`n<textarea class='input' name='testo' rows='6' cols='40'></textarea>",true);
    output("`n<input type='submit' class='button' value='Inserisci'></form>",true);
    addnav("","library.php?op=notizie&az=aggiungi1");

}elseif($_GET['op']=="notizie" AND $_GET['az'] == "aggiungi1"){
    $titolo = addslashes($_POST['titolo']);
    $testo = addslashes($_POST['testo']);
    output("`2TITOLO: `@".$_POST['titolo']."`n`3TESTO: `#".$_POST['testo']."`n`^NOTIZIA INSERITA`n");
    $sql = "INSERT INTO notiziegdr (id,data,titolo,testo) VALUES ('',now(),'$titolo','$testo')";
    $result = db_query($sql) or die(sql_error($sql));
}elseif($_GET['op']=="notizie" AND $_GET['az'] != ""){
    $sql = "SELECT * FROM notiziegdr WHERE id = ".$_GET['az'];
    $result = db_query($sql) or die(sql_error($sql));
    $row = db_fetch_assoc($result);
    output("<table border=0 align='center' cellpadding=2 cellspacing=1 bgcolor='#444444'>",true);
    output("<tr class='trhead'><td align='center'>`b".stripslashes($row['titolo'])."`b</td></tr>",true);
    output("<tr><td align='left'>`@".stripslashes($row['testo'])."</td></tr></table>",true);
    addnav("Torna alle notizie","library.php?op=notizie");
}

if ($_GET['op']!='') {
	if ($_GET['op']=="scaffali") {
		addnav("Esci");
		addnav("Torna alla Biblioteca","library.php");
	}elseif($_GET['op']=="offertorio" ) {	
		if ( $session['user']['jail'] == 2 ) {
				addnav("Continua","constable.php?op=twiddle");
			}else{	
				addnav("Opzioni");
				addnav("Fai un'offerta","library.php?op=offertorio&subop=offerta");
				addnav("Saccheggia Offertorio","library.php?op=offertorio&subop=saccheggia");
				addnav("Ritorna");
				if ($_GET['subop'] !='') {
					addnav("Torna all'Offertorio","library.php?op=offertorio");
				}else{
					addnav("Torna alla Biblioteca","library.php");
				}		
			}	
	}elseif($_GET['op']=="volumi_leggi_brano" ) {
			addnav("Opzioni");
			addnav("Riponi il volume","library.php?op=scaffali&subop=$categoria");
			addnav("Ritorna");			
			addnav("Torna alla Biblioteca","library.php");
	}elseif($_GET['op']!='titlego' AND $_GET['op']!='mountgo'){
		addnav("Esci");
		addnav("Torna alla Biblioteca","library.php");
	}
}
if ( $session['user']['jail'] == 2 OR $_GET['subop'] !='' OR $_GET['op']=='titlego' OR $_GET['op']=='mountgo') {
}else{		
	addnav("Esci");	 				
	addnav("Torna al Monastero","monastero.php");
}
page_footer();
?>