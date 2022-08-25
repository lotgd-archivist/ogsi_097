<?php
require_once("common.php");
require_once("common2.php");
addcommentary();
checkday();
$session['user']['locazione'] = 193;
if ($session[user][slainby]!=""){
    page_header("Sei stato ucciso!");
        output("`\$Sei stato ucciso nella ".$session[user][killedin]."`\$ da `%".$session[user][slainby]."`\$.  Ti è costato il 5% della tua esperienza, e tutto l'oro che avevi con te. Non pensi sia il caso di vendicarsi?");
    addnav("Continua",$REQUEST_URI);
    $session[user][slainby]="";
    page_footer();
}

$buff = array("name"=>"`!Protezione dell'Amore","rounds"=>60,"wearoff"=>"`!Senti la mancanza del tuo amore!.`0","defmod"=>1.2,"roundmsg"=>"Il tuo amante ti ispira a mantenerti al sicuro!","activate"=>"defense");

page_header("Locanda Testa di Cinghiale");
output("<span style='color: #9900FF'>",true);
output("`c`bLocanda \"alla Testa del Cinghiale\"`b`c");

//Festa della birra, Sook, 1° parte (impostazione data)
$festa=0;
$data = date("m-d");
$datafesta = "03-13";
if ($data==getsetting("festa","no") OR $data==$datafesta) $festa=1;

if ($data==$datafesta) output("<big>`n`n`c`b`^FESTA DELLA BIRRA`b`c`n`n`0</big>",true);
//Fine festa della birra

if ($_GET[op]=="strolldown"){
    output("Scendi le scale della locanda, pronto per nuove avventure!  ");
}
if ($_GET[op]==""){
    output("`nEntri nella taverna fumosa e puzzolente che ti è tanto familiare. L'aroma pungente di tabacco di pipa riempie l'aria.");
}
if ($_GET[op]=="" || $_GET[op]=="strolldown"){
    output(" `nSaluti diversi clienti che conosci, e strizzi l'occhio a ".
                ($session[user][sex]?
                "`%Seth`0 che sta accordando il suo liuto accanto al caminetto.":
                "`5Violet`0 che sta servendo birra agli avventori. ").
                "`n`7Cedrik`0 l'oste sta dietro il suo bancone e chiacchiera con un forestiero. `nNon riesci a"
                ." sentire quello che dice, ma ti sembra di capire che stia parlando di ");

    switch (e_rand(1,10)){
        case 1:
            output("`@draghi`0");
            break;
        case 2:
            output("`%Seth`0");
            break;
        case 3:
            output("`5Violet`0");
            break;
        case 4:
            output("`!MightyE`0");
            break;
        case 5:
            output("buona birra");
           	break;
         case 6:
            output("`#Pegasus`0");
           	break; 
         case 7:  	 	
           	output("`!Dag Durnick`0");
           	break;
         case 8:  	 	
           	output("`#Faber il Fabbro della Foresta`0");
           	break;  
         case 9:  	 	
           	output(" ".($session[user][sex]?"`(Monsieur Deguise":"`(Madame Deguise")."`0");
           	break;
		 case 10:  	 	
           	output("`#Oberon`0");
           	break;          	
	
    }
    if (getsetting("pvp",1)) {
        output(".`n ". ($session[user][sex]?
                "`5Violet`0 sta servendo birra agli avventori,":
                "`%Seth`0 sta accordando il suo liuto accanto al caminetto,")." mentre `!Dag Durnick`0 sta seduto in un angolo con una pipa serrata tra le labbra. ");
    }
    output("`n`nL'orologio sulla parete segna le `6".getgametime()."`0.");
    addnav("Cose da Fare");
    if ($session[user][sex]==0) addnav("V?Corteggia Violet","inn.php?op=violet");
    if ($session[user][sex]==1) addnav("V?Chiacchiera con Violet","inn.php?op=violet");
    addnav("S?Parla con il bardo Seth","inn.php?op=seth");
    addnav("Parla con altri clienti","inn.php?op=converse");
    addnav("C?Parla con l'oste Cedrik","inn.php?op=bartender");
    if (@file_exists("fireplace.php"))  addnav("Il Caminetto","fireplace.php");
    if (getsetting("pvp",1)) {
        addnav("D?Parla con Dag Durnick","dag.php");
    }
    addnav("Altro");
    addnav("A?Affitta una camera (ESCI)","inn.php?op=room");
    addnav("Torna al Villaggio","village.php");
}else{
  switch($_GET[op]){
      case "violet":
            /*
            Wink
            Kiss her hand
            Peck her on the lips
            Sit her on your lap
            Grab her backside
            Carry her upstairs
            Marry her
            */
            if ($session[user][sex]==1){
                if ($_GET[act]==""){
                    addnav("Spettegola","inn.php?op=violet&act=gossip");
                    $identarmatura=array();
					$ident_armatura = identifica_armatura();
					$articoloarmatura = $ident_armatura['articolo'];
                    addnav("Chiedi se sembri ingrassata indossando $articoloarmatura ".$session[user][armor]." ","inn.php?op=violet&act=fat");
                    output("`nRaggiungi `5Violet`0 e la aiuti con le birre che sta portando. Una volta finito, ");
                    output("lei si asciuga il sudore dalla fronte con un panno e ti ringrazia moltissimo. Ovviamente a te ");
                    output("non è pesato, visto che lei è una delle tue più care amiche!");
                }else if($_GET[act]=="gossip"){
                    output("`nTu e `5Violet`0 spettegolate tranquillamente per qualche minuto senza un argomento in particolare, poi lei ti offre un sottaceto.  ");
                    output("`nTu accetti sapendo che farlo è nella sua natura di ex ragazza dei sottaceti. `nDopo qualche minuto, ");
                    output("`7Cedrik`0 inizia a mandare delle occhiatacce nella tua direzione, e decidi che sarebbe meglio lasciar tornare `5Violet`0 al lavoro.");
                }else if($_GET[act]=="fat"){
                    $charm = $session[user][charm]+e_rand(-1,1);
                    output("`n`5Violet`0 ti scruta attentamente dall'alto in basso con aria molto seria. `nSolo un'amica può essere veramente onesta, ed è per questo che ");
                    output("lo hai chiesto a lei. `n`nInfine raggiunge una conclusione ed afferma, \"`%`i");
					
					$fascia = calcolafascino();
		    		$rnd = e_rand(1,3);
		    		
    				switch ($fascia) {
                        case 0:
                        	if ($rnd == 1) {
    							output(" I tuoi abiti non lasciano molto all'immaginazione, ma ci sono cose a cui è meglio non pensare affatto. Prenditi qualcosa di meno rivelatore, vedilo come un servizio al pubblico! ");
    						} else {
    							if ($rnd == 2) {
    								output(" Sei messa davvero male,mia cara! Un rospo avrebbe serie difficoltà a baciarti! ");
    							} else {
    								output(" Argh! mai vista una così! Mia cara, temo che resterai zitella perchè anche l'uomo più brutto del villaggio avrà mai il coraggio di baciarti! ");
    							}
    						}
                        break; 
                        case 1:
                            if ($rnd == 1) {
    							output(" Ho visto delle donne adorabili nella mia vita, ma temo che tu non sia una di queste. ");
    						} else {
    							if ($rnd == 2) {
    								output(" Beh....il tuo aspetto è decisamente scoraggiante ma credo che tu possa sviluppare la tua personalità! ");
    							} else {
    								output(" Ehmmm...amica mia....ti consiglio di puntare sulla simpatia! Non hai speranze! ");
    							}
    						}
                        break;
                        case 2:
                            if ($rnd == 1) {
    							output(" Ho visto di peggio amica mia, ma solo attaccato ad un cavallo. ");
    						} else {
    							if ($rnd == 2) {
    								output(" Non sei proprio da buttare e credo che in mezzo ad un campo, con un cappello in testa, saresti un perfetto spaventapasseri. `nProva a presentarti alle Fattorie, penso che verresti subito assunta! ahahahah ");
    							} else {
    								output(" Beh...fossi in te eviterei di andare a giro così... non ti sei accorta che spaventi tutti i bambini? ");
    							}
    						}
                        break;
                        case 3:
                            if ($rnd == 1) {
    							output(" Hai un aspetto alquanto normale, amica mia. ");
    						} else {
    							if ($rnd == 2) {
    								output(" Non sei bella.. non sei brutta.... sei solo tristemente normale! ");
    							} else {
    								output(" Uffa che barba, uffa che noia! Hai un banale aspetto normale mia cara. ");
    							}
    						}
                        break;
                        case 4:
                            if ($rnd == 1) {
    							output(" Vale davvero la pena guardarti, ma non montarti troppo la testa adesso, eh? ");
    						} else {
    							if ($rnd == 2) {
    								output(" Uhmmmm..guardandoti bene hai un bel corpicino ma forse dovresti curare meglio la tua immagine! puoi fare di meglio! ");
    							} else {
    								output(" Uhmmmm...il fisico non va proprio male, L'aspetto è migliorato ma ancora sei piuttosto scarsina! ");
    							}
    						}
                        break;
                        case 5:
                            if ($rnd == 1) {
    							output(" Devo ammettere che sei un bel po' sopra la media! ");
    						} else {
    							if ($rnd == 2) {
    								output(" Ooooohhhh sei davvero carina! Sei come un piccolo bocciolo ancora chiuso ma che sembra promettere un bel fiore! ");
    							} else {
    								output(" Ecco, un bell'aspetto è quel che ci vuole! Sono sicura che gli uomini stanno iniziando a guardarti con interesse. ");
    							}
    						}
                        break;
                        case 6:
                            if ($rnd == 1) {
    							output(" Poche donne potrebbero reggere il confronto con te! ");
    						} else {
    							if ($rnd == 2) {
    								output(" Ehilà! La tua bellezza è davvero notevole! Stai cominciando a farmi preoccupare! Dovrò fare un salto all'Eros Erotico a migliorare il mio aspetto altrimenti rischio di restare senza clienti! ");
    							} else {
    								output(" Che fisico e che sorriso affascinante! Credo di aver di fronte a me una rivale davvero pericolosa! ");
    							}
    						}
                        break;
                        case 7:
                            if ($rnd == 1) {
    							output(" Temo che solo poche uomini sarebbero in grado di resistere al tuo fascino! ");
    						} else {
    							if ($rnd == 2) {
    								output(" Wow! devo ammettere che sei davvero bella! Adesso capisco quel concerto che ho udito poco fa! Erano i guerrieri che fischiavano al tuo passaggio! ");
    							} else {
    								output(" La bellezza statuaria del tuo corpo farebbe invidia ad una statua greca! ");
    							}
    						}
                        break;
                        case 8:
                            if ($rnd == 1) {
    							output(" Ti odio, sei la donna più bella che abbia mai visto! ");
    						} else {
    							if ($rnd == 2) {
    								output(" L'armonia e la bellezza della tua persona farebbero morire di rabbia anche la stessa Venere! ");
    							} else {
    								output(" Complimenti amica! Adone in persona cadrebbe ai tuoi piedi completamente avvinto dal tuo fascino! ");
    							}
    						}
                        break;
                    }
                    output("`0\"`i");

                }
            }
            if ($session[user][sex]==0){
                  //$session[user][seenlover]=0;
              if ($session[user][seenlover]==0){
                  if ($session['user']['marriedto']==4294967295){
                    if (e_rand(1, 4)==1){
                      output("Ti avvicini a Violet e le baci il viso ed il collo, ma lei grugnisce qualcosa a proposito");
                      switch(e_rand(1,4)){
                      case 1:
                        output("di essere troppo impegnata a servire i clienti che oggi si comportano tutti da veri porci,");
                        break;
                      case 2:
                        output("di \"quel periodo del mese,\"");
                        break;
                      case 3:
                        output("di \"un po' di raffreddore...  *coff coff* vedi?\"");
                        break;
                      case 4:
                        output("del fatto che gli uomini sono tutti maiali,");
                        break;
                      }
                      output("e dopo un commento del genere ti allontani rapidamente da lei!");
                      $session['user']['charm']--;
                      output("`n`n`^PERDI un punto di fascino!");
                    } else {
                        output("Tu e `5Violet`0 vi prendete qualche momento per voi, e lasci la locanda raggiante!");
                        $session['bufflist']['lover']=$buff;
                        $session['user']['charm']++;
                        output("`n`n`^Guadagni un punto di fascino!");
                    }
                    $session['user']['seenlover']=1;
                  } elseif ($_GET[flirt]==""){
                        output("Guardi trasognato verso `5Violet`0, dall'altra parte della stanza, che si piega su un tavolo ");
                        output("per servire da bere ad un cliente. Nel farlo, mostra forse un po' più di pelle ");
                        output("del necessario, ma non ti sembra il caso di obiettare.");
                        addnav("Flirt");
                        addnav("O?Strizza l'Occhio","inn.php?op=violet&flirt=1");
                        addnav("B?Baciale la Mano","inn.php?op=violet&flirt=2");
                        addnav("L?Sfiorale le Labbra","inn.php?op=violet&flirt=3");
                        addnav("G?Prendila in Grembo","inn.php?op=violet&flirt=4");
                        addnav("T?Toccale il Didietro","inn.php?op=violet&flirt=5");
                        addnav("C?Portala in Camera","inn.php?op=violet&flirt=6");
                        if ($session[user][charisma]!=4294967295) addnav("S?Sposala","inn.php?op=violet&flirt=7");
                    }else{
                      $c = $session[user][charm];
                        $session[user][seenlover]=1;
                      switch($_GET[flirt]){
                          case 1:
                              if (e_rand($c,2)>=2){
                                  output("Strizzi l'occhio a `5Violet`0, e lei ti fa un caldo sorriso in cambio.");
                                    if ($c<4) $c++;
                                }else{
                                  output("Strizzi l'occhio a `5Violet`0, ma lei fa finta di non accorgersene.");
                                }
                              break;
                          case 2:
                              if (e_rand($c,4)>=4){
                                  output("Attraversi baldanzoso la stanza andando verso `5Violet`0.  Le prendi la ");
                                    output("mano e gliela baci gentilmente, le tue labbra si soffermano solo per pochi secondi. `5Violet`0 ");
                                    output("arrossisce e si mette una ciocca di capelli dietro l'orecchio mentre ti allontani, poi preme ");
                                    output("il dorso della mano contro la sua guancia mentre ti guarda allontanarti.");
                                    if ($c<7) $c++;
                                }else{
                                  output("Attraversi baldanzoso la stanza andando verso `5Violet`0 e le prendi la mano.  ");
                                    output("`n`nMa `5Violet`0 se la riprende e ti domanda se per caso vorresti una birra.");
                                }
                              break;
                          case 3:
                              if (e_rand($c,7)>=7){
                                  output("Dando le spalle ad una colonna di legno, attendi che `5Violet`0 capiti ");
                                    output("da quelle parti e la chiami per nome. Lei si avvicina, con un principio di sorriso sul volto.  ");
                                    output("Le prendi il mento, glielo sollevi appena, e le dai un bacio risoluto ma rapido sulle sue ");
                                    output("morbide labbra.");
                                    if ($session[user][charisma]==4294967295) {
                              output(" Tua moglie sarà delusa quando verrà a saperlo!");
                              $c--;
                           } else {
                              if ($c<11) $c++;
                           }
                                }else{
                                  output("Dando le spalle ad una colonna di legno, attendi che `5Violet`0 capiti ");
                                    output("da quelle parti e la chiami per nome. Lei sorride e si scusa ripetendo che è troppo occupata ");
                                    output("per poter interrompere quello che sta facendo.");
                                }
                              break;
                          case 4:
                              if (e_rand($c,11)>=11){
                                  output("Seduto ad un tavolo, aspetti che `5Violet`0 passi da te. Quando lo fa, ");
                                    output("ti allunghi e la afferri saldamente per la vita, facendotela sedere in grembo. Lei ride ");
                                    output("e ti mette le braccia attorno al collo in un caldo abbraccio prima di colpirti sul petto, ");
                                    output("alzarsi e dirti che deve per forza tornare al lavoro.");
                                    if ($session[user][charisma]==4294967295) {
                              output(" Tua moglie sarà molto delusa quando verrà a saperlo!");
                              $c--;
                           } else {
                              if ($c<14) $c++;
                           }
                                }else{
                                  output("Seduto ad un tavolo, aspetti che `5Violet`0 passi da te. Quando lo fa, ");
                                    output("ti allunghi per afferrarla alla vita, ma lei ti schiva con destrezza, attenta a non versare la ");
                                    output("birra che sta portando.");
                                    if ($c>0 && $c<10) $c--;
                                }
                              break;
                          case 5:
                              if (e_rand($c,14)>=14){
                                output("Aspetti che `5Violet`0 ti passi vicino e le tasti il didietro. Lei si volta e ");
                                output("ti fa un caldo sorriso complice.");
                                if ($session[user][charisma]==4294967295) {
                                    $c--;
                                } else {
                                if ($c<18) $c++;
                           }

                                }else{
                                  output("Aspetti che `5Violet`0 ti passi vicino e le tasti il didietro. Lei si volta e ");
                                    output("ti da un ceffone in faccia. Forte. Forse dovresti andarci un po' più piano.");
                                    //$session[user][hitpoints]=1;
                                    if ($c>0 && $c<13) $c--;
                                }
                                if ($session[user][charisma]==4294967295) output(" Tua moglie sarà molto delusa quando verrà a saperlo!");

                              break;
                          case 6:
                              if (e_rand($c,18)>=18){
                              output("Come un tornado, attraversi la locanda, afferrando `5Violet`0, che ti mette le braccia ");
                                    output("al collo, e la porti nella sua stanza al piano di sopra. Non più di 10 minuti dopo ");
                                    output("ridiscendi le scale, con una pipa in bocca ed un sorriso da un orecchio all'altro.  ");
                                    if ($session['user']['turns']>0){
                                      output("Ti senti esausto!  ");
                                        $session['user']['turns']-=2;
                                        if ($session['user']['turns']<0) $session['user']['turns']=0;
                                    }
                                    addnews("`@".$session[user][name]."`@ e `5Violet`@ sono stati visti salire le scale della locanda insieme.");
                                    if ($session[user][charisma]==4294967295 && e_rand(1,3)==2) {
                                        $sql = "SELECT acctid,name FROM accounts WHERE locked=0 AND acctid=".$session[user][marriedto]."";
                                        $result = db_query($sql) or die(db_error(LINK));
                                        $row = db_fetch_assoc($result);
                                        $partner=$row[name];
                                        addnews("`\$$partner ha lasciato ".$session[user][name]."`\$ per una scappatella con `5Violet`\$!");
                                        output("`nQuesto era troppo per $partner! Chiede il divorzio. La metà dell'oro che è in banca viene aggiudicato a lei. D'ora in poi sei di nuovo single!");
                                        $session[user][charisma]=0;
                                        $session[user][marriedto]=0;
                                        if ($session[user][goldinbank]>0) $getgold=round($session[user][goldinbank]/2);
                                        $session[user][goldinbank]-=$getgold;
                                        $sql = "UPDATE accounts SET charisma=0,marriedto=0,goldinbank=goldinbank+($getgold+0) WHERE acctid='$row[acctid]'";
                                        db_query($sql);
                                        systemmail($row['acctid'],"`\$Scappatella!`0","`&{$session['user']['name']}`6 ti tradisce con Violet!`nE' motivo sufficiente per te per chiedere il divorzio. D'ora in poi sei di nuovo single.`nRiceverai `^$getgold`6 del suo patrimonio sul tuo conto in banca.");
                                    }else if ($session[user][charisma]==4294967295) {
                                        output(" Tua moglie sarà molto delusa quando verrà a saperlo!");
                                        $c--;
                                    }else{
                              if ($c<25) $c++;
                           }

                                }else{
                                  output("Come un tornado, attraversi la locanda e tenti di afferrare `5Violet`0. Lei si volta e ");
                                    output("ti da un ceffone!  \"`%Che genere di ragazza pensi che sia?`0\" ti domanda! ");
                                    if ($c>0) $c--;
                                }
                              break;
                            case 7:
                                output("`5Violet`0 sta lavorando febbrilmente per servire i clienti della locanda. La raggiungi e ");
                                output("le togli i boccali dalle mani, mettendoli su un tavolo vicino. Tra le sue proteste ");
                                output("ti pieghi su un ginocchio, prendendole la mano. Lei si calma mentre alzi lo sguardo verso di lei ");
                                output("e fai la domanda che non avresti mai pensato di fare. Lei ti fissa e tu ");
                                output("capisci subito la risposta dall'espressione del suo volto. ");
                              if ($c>=22){
                                  output("`n`nÈ un'espressione di gioia immensa.  \"`%Sì!`0\" dice, \"`%Sì, sì sì!!!`0\"");
                                    output("  Le sue ultime parole sono seppellite in una cascata di baci sulla tua faccia ed il tuo collo. ");
                                    output("`n`nI giorni successivi fuggono; tu e `5Violet`0 vi sposate nell'abbazia in fondo alla strada, ");
                                    output("in una splendica cerimonia con un mucchio di frivole cosette femminili.");
                                    addnews("`&".$session[user][name]." e `%Violet`& si sono gioiosamente uniti oggi in matrimonio!!!");
                                    debuglog("si è sposato con Violet");
                                    $session['user']['marriedto']=4294967295; // int max. I very much doubt that anyone is going to be
                                    $session['bufflist']['lover']=$buff;
                                }else{
                                  output("`n`nÈ un'espressione di tristezza.  \"`%No`0,\" dice, \"`%non sono ancora pronta a mettere su famiglia`0.\"");
                                    output("`n`nCon il cuore spezzato, non hai più voglia di avventurarti nella foresta per oggi.");
                                    $session[user][turns]=0;
                                    debuglog("perde tutti i turni rifiutato da Violet");
                                }
                        }
                        if ($c > $session[user][charm]) output("`n`n`^Guadagni un punto di fascino!");
                        if ($c < $session[user][charm]) output("`n`n`\$PERDI un punti di fascino!");
                        $session[user][charm]=$c;
                    }
                }else{
                  output("Pensi che sia meglio non tentare troppo la fortuna con `5Violet`0 per oggi.");
                }
            }else{
              //sorry, no lezbo action here.
            }
            break;
        case "seth":
            /*
            Wink
            Flutter Eyelashes
            Drop Hankey
            Ask the bard to buy you a drink
            Kiss the bard soundly
            Completely seduce the bard
            Marry him
            */
      if ($_GET[subop]=="" && $_GET[flirt]==""){
        output("`n`%Seth`0 ti guarda incuriosito come se stesse cercando di indovinare che cosa tu possa volere da lui.`n`n`i`^Desiderate ".($session[user][sex]?"mia signora":"mio signore")."  ? In cosa posso servirvi ?`i`0");
        addnav("Chiedi a Seth di intrattenere","inn.php?op=seth&subop=hear");
        addnav("Gioca con Seth","rockpaper.php");
        if ($session[user][sex]==1){
            if ($session['user']['marriedto']==4294967295) {
                addnav("Flirta con Seth", "inn.php?op=seth&flirt=1");
            } else {
                addnav("Corteggia");
                addnav("O?Strizza l'Occhio","inn.php?op=seth&flirt=1");
                addnav("C?Sbatti le Ciglia","inn.php?op=seth&flirt=2");
                addnav("F?Fai cadere il Fazzoletto","inn.php?op=seth&flirt=3");
                addnav("h?Chiedigli di offrirti da bere","inn.php?op=seth&flirt=4");
                addnav("b?Bacialo","inn.php?op=seth&flirt=5");
                addnav("T?Seducilo Totalmente","inn.php?op=seth&flirt=6");
                if ($session[user][charisma]!=4294967295) addnav("S?Sposalo","inn.php?op=seth&flirt=7");
            }
        } else {
            
        	$identarmatura=array();
			$ident_armatura = identifica_armatura();
			$articoloarmatura = $ident_armatura['articolo'];
            addnav("Chiedi a Seth come trova $articoloarmatura ".$session[user][armor] ,"inn.php?op=seth&act=armor");
        }
      }
            if ($_GET[act]=="armor"){
                $charm = $session[user][charm]+e_rand(-1,1);
		    	$fascia = calcolafascino();
                output("`n`n`%Seth`0 ti scruta dall'alto in basso con aria seria perchè ha capito il vero significato della tua domanda. Solo un amico vero può essere sincero e onesto nella sua risposta, ed è per questo che ");
                output("hai scelto di rivolgerti a lui. `nDopo aver ragionato per qualche istante, che a te pare interminabile, il bardo giunge ad una conclusione e ti dice, \"`^`i");
		    	$rnd = e_rand(1,3);
		    	switch ($fascia) {
    				
    				case 0:
    					if ($rnd == 1) {
    						output(" Mi rendi felice di non essere gay! ");
    					} else {
    						if ($rnd == 2) {
    							output(" Argh! mai visto uno così! Amico mio, temo che resterai un orribile rospo perchè nessuno avrà mai il coraggio di baciarti! ");
    						} else {
    							output(" Sei messo davvero male, amico mio! Anche la donna più racchia del villaggio avrebbe serie difficoltà a baciarti! ");
    						}
    					}	
                    break;
                    case 1:
                    	if ($rnd == 1) {
    						output(" Ho visto dei begli uomini ai miei tempi, ma temo che tu non sia fra questi. ");
    					} else {
    						if ($rnd == 2) {
    							output(" Ehmmm...amico....ti consiglio di puntare sulla simpatia! Non hai speranze! ");
    						} else {
    							output(" Beh....il tuo aspetto è decisamente scoraggiante ma credo che tu possa sviluppare la tua personalità! ");
    						}
    					}
                    break;
                    case 2:
                        if ($rnd == 1) {
    						output(" Ho visto di peggio amico mia, ma solo attaccato ad un cavallo. ");
    					} else {
    						if ($rnd == 2) {
    							output(" Beh...fossi in te eviterei di andare a giro così...spaventi i bambini! ");
    						} else {
    							output(" Non sei proprio da buttare e credo che in mezzo ad un campo, con un cappello in testa, saresti uno spaventapasseri perfetto! `nProva a presentarti alle Fattorie, penso che ti assumerebbero subito! ahahahah ");
    						}
    					}
                    break;
                    case 3:
                        if ($rnd == 1) {
    						output(" Hai un aspetto alquanto normale amico mio. ");
    					} else {
    						if ($rnd == 2) {
    							output(" Non sei proprio brutto ma.....noiosamente normale!! ");
    						} else {
    							output(" Che noia! hai un banale aspetto normale. ");
    						}
    					}
                    break;
                    case 4:
                        if ($rnd == 1) {
    						output(" Sei di certo qualcuno che vale la pena guardare, ma adesso non ti montare la testa, eh? ");
    					} else {
    						if ($rnd == 2) {
    							output(" Uhmmmm...il fisico non va proprio male, L'aspetto è migliorato ma ancora sei piuttosto scarso! ");
    						} else {
    							output(" Uhmmmm..guardandoti bene hai un buon aspetto ma forse dovresti curare meglio la tua immagine! puoi fare di meglio! ");
    						}
    					}
                    break;
                    case 5:
                        if ($rnd == 1) {
    						output(" Sei decisamente sopra la media! ");
    					} else {
    						if ($rnd == 2) {
    							output(" Ecco, un bell'aspetto è quel che ci vuole! Sono sicuro che le donne iniziano a guardarti con interesse. ");
    						} else {
    							output(" Ooooohhhh sei davvero carino! Un bel faccino che sembra promettere molto! ");
    						}
    					}	
                    break;
                    case 6:
                        if ($rnd == 1) {
    						output(" Ti trovo veramente affascinante, un uomo degno di nota! ");    					
    					} else {
    						if ($rnd == 2) {
    							output(" Che fisico e che affascinante sorriso! Credo di avere di fronte a me un meritevole rivale in amore! ");
    						} else {
    							output(" Ehilà! La tua bellezza è notevole! Stai cominciando a farmi preoccupare! Dovrò fare un salto all'Eros Erotico a migliorarmi un pò altrimenti rischio di perdere tutta la mia clientela! ");
    						}
    					}
                    break;
                    case 7:
                        if ($rnd == 1) {
    						output(" Temo che solo poche donne potrebbero resisterti! ");    					
    					} else {
    						if ($rnd == 2) {
    							output(" La bellezza statuaria del tuo corpo farebbe invidia ad una statua greca! ");
    						} else {
    							output(" Wow! devo ammettere che sei davvero bello! Adesso capisco quel concerto di gridolini che ho udito poco fa! Erano le Dame che ti manifestavanola loro approvazione! ");
    						}
    					}
                    break;
                    case 8:
                        if ($rnd == 1) {
    						output(" Ti odio, sei l'uomo più bello che si sia mai visto a Rafflingate! ");    					
    					} else {
    						if ($rnd == 2) {
    							output(" Complimenti amico! Venere in persona cadrebbe ai tuoi piedi completamernte vinta dal tuo fascino! ");
    						} else {
    							output(" L'armonia e la bellezza della tua persona farebbero morire di rabbia anche lo stesso Adone! ");
    						}
    					}
                    break;
                    }
    				
                output("`0\"`i");

            }
      if ($_GET[subop]=="hear"){
      	$ident_armatura=array();
		$ident_armatura = identifica_armatura();
		$articoloarmatura = $ident_armatura['articolo'];
        //$session[user][seenbard]=0;
        if($session[user][seenbard]){
          output("`n`%Seth`0 si schiarisce la gola e beve un po' d'acqua. `^`iMi spiace, ho la gola troppo secca.`i`n");
         // addnav("Return to the inn","inn.php");
        }else{
          $rnd = e_rand(0,20);
          $session[user][seenbard]=1;
          switch ($rnd){
            case 0:
          	  	output("`n`%Seth`0 si schiarisce la gola inizia a strimpellare qualche nota col suo liuto e comincia a cantare allegramente:`n`n`^");
              	output("`iIl `@Drago Verde`^ è verde. `nIl `@Drago Verde`^ è il re della foresta. `nSogno un `@Drago Verde`^ a cui tagliar la testa. `i");
             	output("`n`n`0Il tuo spirito di avventura si infiamma tanto che guadagni `@due`0 turni di combattimenti nella foresta!");
              	$session[user][turns]+=2;
              break;
            case 1:
            	output("`n`%Seth`0 si schiarisce la gola inizia a strimpellare qualche nota col suo liuto e comincia a cantare:`n`n`^");
              	output("`iMireraband io ti derido e ti solletico i ditoni.`nPerchè mandano più puzza di una mandria di caproni! `i");
              	output("`n`n`0Ti senti allegr".($session[user][sex]?"a":"o")." e guadagni `@un`0 combattimento nella foresta.");
              	$session[user][turns]++;
              break;
            case 2:
            	output("`n`%Seth`0 si schiarisce la gola inizia a strimpellare qualche nota col suo liuto e comincia a cantare:`n`n`^");
              	output("`iUomo Membrana, Uomo Membrana. `nUomo Membrana odia ".$session[user][name]."`^. `nCombattono, vince Uomo Membrana. `nUomo Membrana.`i ");
              	output("`n`n`0Terminata la ballata `%Seth`0 stramazza a terra esausto e non sembra proprio in perfetta forma.");
              	output("`nOnestamente non sai proprio cosa pensare... `nPerpless".($session[user][sex]?"a":"o")." ti limiti ad uscire dalla locanda, ripromettendoti di tornare a chiedere spiegazioni a `%Seth`0 quando si sarà ripreso e starà meglio.`n");
              	output("Essendoti nel frattempo riposat".($session[user][sex]?"a":"o")." un poco, trovi lo spirito per poter affrontare un altro cattivone.");
              	$session[user][turns]++;
              break;
            case 3:
            	output("`n`%Seth`0 si schiarisce la gola inizia a strimpellare qualche nota col suo liuto e quasi sussurrando come se avesse timore di farsi sentire:`n`n`^");
              	output("`iRiunitevi e vi racconterò una storia terribile ed oscura `nsu `7Cedrik`^ e la sua sporca birra scura. `nPosso anche dirvi e senza azzardo, che il barista odia questo bardo! `i");
              	output("`n`n`0Ti rendi conto che `%Seth`0 ha ragione, la birra di `7Cedrik`0 è davvero cattiva. `nTi chini sotto il rozzo tavolo per un conato di vomito che però riesci a trattenere e così facendo vedi alcune monete sul pavimento che raccogli e ti metti in tasca rapidamente!");
              	$gain = e_rand(10,50);
              	$session[user][gold]+=$gain;
              	debuglog("trova $gain pezzi d'oro vicino a Seth");
              break;              
            case 4: 
            	output("`n`%Seth`0 si schiarisce la gola inizia a strimpellare qualche nota col suo liuto e quasi sussurrando come se avesse timore di farsi sentire:`n`n`^");
              	output("`iRiunitevi e vi racconterò una storia terribile ed oscura `nsu `7Cedrik`^ e la sua birra sporca `ne su quanto odia questo bardo! `i");
              	output("`n`n`0Ti rendi conto che `%Seth`0 ha ragione, oggi la birra di `7Cedrik`0 è davvero cattiva. Ti chini sotto il rozzo tavolo per un conato di vomito e vieni assalit".($session[user][sex]?"a":"o")." da una forte nausea.");
              	output("`n`nForse è meglio uscire dalla locanda per respirare un poco di aria fresca.");
              	$session['bufflist']['nausea'] = array(
	            "name"=>"`4Nausea",
	            "rounds"=>10,
	            "wearoff"=>"La nausea ti passa e torni in piena salute.",
	            "atkmod"=>.90,
	            "defmod"=>.80,
	            "roundmsg"=>"Una seria di conati di vomito ti impediscono di attaccare e riesci a difenderti a stento dal tuo avversario.",
	            "activate"=>"roundstart");
              break; 
            case 5:
            	output("`n`%Seth`0 si schiarisce la gola inizia a strimpellare qualche nota col suo liuto e racconta una allegra storiella:`n`n`^");
              	output("`iUn pirata entra in un bar con una ruota del timone nei pantaloni. `nIl barista dice, \"Sai che hai una ruota di timone nei pantaloni?\" `nIl pirata risponde, \"Yaaarr, me le sta facendo girare!\"`i ");
              	output("`n`n`0Reprimendo un risolino, esci nel mondo, pront".($session[user][sex]?"a":"o")." ad affrontare qualunque cosa!");
              	$session[user][hitpoints]=round($session[user][maxhitpoints]*1.2,0);
              break;
            case 6:
            	output("`n`%Seth`0 si schiarisce la gola inizia a strimpellare qualche nota col suo liuto e inizia a recitare con aria greve:`n`n`^");
              output("`iAvvicinati ".$session[user][name]." `^ed ascolta attentamente: ogni secondo che passa ci avvicina alla morte.  *wink*`i");
              output("`n`n`0Depress".($session[user][sex]?"a":"o").", ti dirigi verso casa... e perdi `@un`0 combattimento nella foresta!");
              $session[user][turns]--;
                            if ($session[user][turns]<0) $session[user][turns]=0;
              break;
            case 7:
            	output("`n`%Seth`0 si schiarisce la gola inizia a strimpellare qualche nota col suo liuto e inizia a recitare allegramente:`n`n`^");
              	output("`iAmo MightyE, le armi di MightyE, amo MightyE, le armi di MightyE, amo MightyE, le armi MightyE, `nniente uccide bene quanto... LE ARMI DI MightyE!`i");
              	output("`n`n`0Pensi che `%Seth`0 abbia ragione... `ne vuoi subito uscire ed andare ad massacrare qualcosa, anche se per qualche strana ragione ti viene di pensare ad api e pesci.");
              	$session[user][turns]++;
              break;
            case 8:
              	output("`n`0`%Seth`0 si siede, accorda il suo liuto e sembra prepararsi per qualcosa di veramente notevole. `nPoi all'improvviso ti rutta rumorosamente in faccia.  `n`n`^`iCom'era come intrattenimento?`0`i");
              	output("`n`n`0L'odore è devastante, tanto che non ti senti troppo bene e perdi alcuni punti ferita.");
              	$session[user][hitpoints]-= round($session[user][maxhitpoints] * 0.1,0);
              	if ($session[user][hitpoints]<=0) $session[user][hitpoints]=1;
              break;
            case 9:
                if ($session['user']['gold'] >= 5) {
                  output("`n`0`^`iQual è il suono di una mano che applaude?`i`0 chiede `%Seth`0. `nMentre ponderi su questo enigma, `%Seth`0 \"libera\" una piccola tassa di intrattenimento dal tuo borsellino.");
                  output("`n`nPerdi `^5`0 monete d'`^oro!`0");
                  $session[user][gold]-=5;
                  debuglog("perde 5 oro, rubati da Seth");
                } else {
                  output("`n`0`^`iQual è il suono di una mano che applaude?`0`i chiede `%Seth`0. Mentre ponderi su questo enigma, `%Seth`0 \"libera\" una piccola tassa di intrattenimento dal tuo borsellino, ma non ne trova abbastanza per infastidirti.");
                }
              break;
            case 10:
            	output("`n`%Seth`0 si schiarisce la gola inizia a strimpellare qualche nota col suo liuto e racconta una storiella:`n`n`^");
              	output("`iCosa sono i vulcani?`n`nDelle vulbestie che fanno vulbau vulbau.`i`0");
              	output("`n`nFai un gemito mentre `%Seth`0 si rotola per terra dal ridere. `nScuoti la testa in segno di disapprovazione, ma così fecondo noti una `&gemma`0 tra la polvere.");
              	$session[user][gems]++;
              	debuglog("trova 1 gemma da Seth");
              break;
            case 11:
              	output("`n`%Seth`0 suona una melodia dolce e ammaliante.");
              	output("`n`nTi senti rilassat".($session[user][sex]?"a":"o")." e le tue ferite sembrano dissolversi.");
              	if ($session['user']['hitpoints']<$session['user']['maxhitpoints']) $session[user][hitpoints]=$session[user][maxhitpoints];
              break;
            case 12:
              	output("`n`%Seth`0 suona per te una malinconica litania funebre.");
              	output("`n`n`^ `iEran stati imprigionati 
						`n nell'inferno più profondo
						`n ma il dimon li ha liberati 
						`n e ora vagan per il mondo.");
				output("`n`n Sono i Cavalieri dell'Apocalisse 
						`n crudeli e privi d'ogni remissione
						`n nessun eroe mai li sconfisse  
						`n portan sempre morte e distruzione.");
				output("`n`n Pestilenza, Carestia, Guerra e Morte, 
						`n se li vedi è meglio scappare
						`n ma se ti ritieni forte
						`n la tua fine puoi aspettare!`i");
              	output("`n`n`0Ti senti depress".($session[user][sex]?"a":"o")." e non ti sembra di essere in perfetta forma per affrontare molti nemici oggi.");
              	$session[user][turns]--;
              	if ($session[user][turns]<0) $session[user][turns]=0;
              break;
            case 13:
            	output("`n`%Seth`0 si schiarisce la gola inizia a strimpellare qualche nota col suo liuto e comincia a cantare allegramente:`n`n`^");
              	output("`iLe formiche marciano una ad una, urrà, urrà.`nLe formiche marciano una ad una, urrà, urrà!`nLe formiche marciano una ad una e la più piccola si ferma per succhiarsi il pollice,`ned esse marciano tutte, verso il terreno, per allontanarsi dalla pioggia...`nbum bum bum`nLe formiche marciano a due a due, urrà, urrà!....`i");
              	output("`n`n`%Seth`0 continua a cantare, ma non volendo scoprire fino a che numero è in grado di contare, te ne vai silenziosamente.`n`nEssendoti riposat".($session[user][sex]?"a":"o")." un po', ti senti più fresc".($session[user][sex]?"a":"o").".");
                            $session[user][hitpoints]=$session[user][maxhitpoints];
              break;
            case 14:
            	output("`n`%Seth`0 si schiarisce la gola inizia a strimpellare qualche nota col suo liuto e comincia a cantare allegramente:`n`n`^");
              	output("`n`n`^`iUn tempo `@Merlino`^ era in questo Villaggio, era Arcimago, era forte, era saggio. 
						`n Un fato triste l’ha perseguitato, e ora se ne è andato, smarrito, esiliato.
						`n Senza il suo drago, senza il fratello, ora è impazzito il poverello.
						`n Ma la premessa fin troppo è durata, ora inizio la mia sonata.");
              	output("`n`nNella Torre l’Arcimago lavorava come un drago
						`n con il mana da gestire ed i maghi da punire,
						`n ma era molto insoddisfatto del vestito poco adatto.");
				output("`n`nNon si vede proprio bello con la tunica e il mantello,
						`n i suoi muscoli più tosti non li vuole mai nascosti
						`ne scuotendo la sua chioma disse : `@Basta solo un perizoma!`^");
				output("`n`nIniziò ad esplorare per cercare l’animale
						`n con la pelle maculata ch’egli aveva tanto sognata
						`n e alla fin scovò il felino : un leopardo blù turchino.");
				output("`n`nIl colore con i fiocchi s’intonava coi suoi occhi : 
						`n con un pugno l’ammazzò e la pelle gli levò,
						`n la legò con nastri bianchi se la cinse ai tosti fianchi.");
				output("`n`nCome Eracle abbigliato or regnava incontrastato
						`n ma nella sua nera lista mise un mago ambientalista
						`n ed appena lo punì questo ingrato lo tradì.");
				output("`n`nLa sua accusa è presto detta : caccia a specie ogn’or protetta
						`n per cui lui fu condannato a cercarne un duplicato.
						`n Così disse il Gran Concilio : `2Se fallisci c’è l’esilio!`^");
				output("`n`nMari e monti perlustrò ma la belva non trovò :
						`n quello splendido animale era l’ultimo esemplare
						`n e di questo ormai restava solo il lembo che indossava.");
				output("`n`nTriste e mesto lui si volse ma la pelle non si tolse.
						`n Nel suo esilio senza mappe e con la coda fra le chiappe
						`n del rarissimo felino, se ne andò `@Mago Merlino.`0`i");              	
              	output("`n`n`%Seth`0 si inchina fra gli applausi mentre tu sentendoti ispirat".($session[user][sex]?"a":"o").", guadagni un combattimento nella foresta.");
              	$session[user][turns]++;
              break;
            case 15:
              output("`n`%Seth`0 intona un canto di battaglia che risveglia il tuo spirito guerriero.");
              output("`n`n`0Guadagni un combattimento nella foresta!");
              $session[user][turns]++;
              break;
            case 16:
              output("`n`%Seth`0 invece di cantare sembra interessato ai tuoi... occhi.");
              if ($session[user][sex]==1){
                output("`n`n`0Guadagni un punto di fascino!");
                $session[user][charm]++;
              }else{
                output("`n`n`0Furioso, vuoi uscire subito dalla locanda! Nella tua furia guadagni un combattimento nella foresta.");
                $session[user][turns]++;
              }
              break;
            case 17:
              output("`n`%Seth`0 inizia a suonare, ma una corda del liuto si spezza, colpendoti dritto nell'occhio.`n`n`0\"`^`iOoops, attent".($session[user][sex]?"a":"o").", ci rimetterai un occhio così!`i`0\"");
              output("`n`nImmediatamente il tuo occhio si gonfia e vai fatica a vedere attraverso le palpebre semichiuse dal vasto ematoma!");
              $session[user][hitpoints]-=round($session[user][maxhitpoints]*.1,0);
                            if ($session[user][hitpoints]<1) $session[user][hitpoints]=1; 
	    	  $session['bufflist']['occhio gonfio'] = array(
	            "name"=>"`4Occhio tumefatto",
	            "rounds"=>10,
	            "wearoff"=>"Il tuo occhio si sgonfia a torni a vedere normalmente.",
	            "atkmod"=>.95,
	            "defmod"=>.90,
	            "roundmsg"=>"Hai difficoltà nel vedere i colpi dell'avversario dal lato dell'occhio tumefatto!",
	            "activate"=>"roundstart");
              break;
            case 18:
              output("`n`%Seth`0 inizia a suonare, ma mentre stai ascoltando la sua ballata, un rozzo cliente ti passa davanti e ti urta versandoti la sua birra addosso senza nemmeno chiederti scusa. `nTi perdi la performance del bardo per cercare di ripulire dalla bevanda il tuo ".$session[user][armor].".");
              break;
            case 19:
              output("`n`%Seth`0 ti guarda pensieroso, ovviamente intento a comporre rapidamente un poema epico...`n`n`^B-R-U-T-T-".($session[user][sex]?"A":"O").", Non hai nessun alibi --sei brutt".($session[user][sex]?"a":"o").", yeah yeah, sei brutt".($session[user][sex]?"a":"o")."!");
              $session[user][charm]--;
              if ($session[user][charm]<0){
                output("`n`n`0Se avessi avuto un minimo di fascino ti saresti offes".($session[user][sex]?"a":"o").", invece una corda del liuto di `%Seth`0 si rompe.");
              }else{
                output("`n`n`0Depress".($session[user][sex]?"a":"o").", perdi un punto di fascino.");
              }
              break;
            case 20:
            	output("`n`%Seth`0 si schiarisce la gola inizia a strimpellare qualche nota col suo liuto e comincia a cantare allegramente:`n`n`^");
            	output("`n`n`^ `iLa storia odierna è la prima ballata, 
						`n la morte del drago vien qui celebrata
						`n e se qualcuno non vuole ascoltare, 
						`n presto da `7Cedrik`^ si può ritirare.");
				output("`n`n Il prode Merlino un dì baldo e fiero,
						`n in ricerca del `@drago`^ andò sul sentiero.
						`n Con il pegaso e la lancia in resta  
						`n si addentrò piano nella foresta.");
				output("`n`n Dopo sei ore su e giù a camminare, 
						`n sentì la fame e si mise a mangiare.
						`n Nella sua sacca avea pane e formaggio,
						`n una banana, due pere e un ortaggio.");
				output("`n`n Cercò un sedil su una rupe rotta,
						`n cadde e arrivò fin dentro a una grotta. 
						`n Giunto sul fondo trovò a muso duro
						`n un `@drago`^ immenso color `2verde scuro`^.");
				output("`n`n Estrasse la spada e menò grandi fendenti,
						`n ma era lento nei suoi movimenti, 
						`n incantesimi a iosa ha poi pronunciato,
						`n senza veder mai alcun un risultato.");
				output("`n`n Provò anche a dargli la bacchetta in testa,
						`n ma la gran bestia la schivò lesta.
						`n Un gran ruggito e un fetido fiato,
						`n lasciò il nostro eroe paralizzato."); 
				output("`n`n`@ Ma chi sei tu piccolo mago,
						`n che dai fastidio ad un grande `@drago`^?
						`n`^ Così Merlino si armò di coraggio,
						`n e offrì alla belva le pere e il formaggio.");
				output("`n`n`@ Ma chi sei tu vile creatura
						`n che vuoi placarmi con doni in natura?
						`n Uhm buono, ottimo dammene ancora... 
						`n`^ E la grande fiera mangiò per un’ora."); 
				output("`n`n Ma poi ad un tratto fè un forte rutto
						`n e cadde di schianto il `@verde brutto`^,
						`n con gli arti alla pancia per il gran male
						`n andò a morire il possente animale.");
				output("`n`n Ed esalando il fatal respiro,
						`n sussurrò piano con un sospiro 
						`n`@ Vattene a casa e schiocca un gran bacio
						`n alla tua Freya che comprò il cacio...");
				output("`n`n`^ La lieta morale della novella, 
						`n ormai s’è capito, è proprio quella 
						`n Al `@verde drago`^ non far sapere
						`n che può morire di cacio e pere.`i");
				output("`n`n`0Entusiasta incominci ad applaudire `%Seth`0 e ti senti ".($session[user][sex]?"pronta":"pronto")." ad uscire dirigendoti verso la foresta e ad affrontare qualunque creatura mostruosa!");
              	$session[user][hitpoints]=round($session[user][maxhitpoints]*1.2,0);
              	$session['bufflist']['Spirito combattivo'] = array(
	            "name"=>"`4Spirito combattivo",
	            "rounds"=>10,
	            "wearoff"=>"Il ricordo dell'allegra ballata di Seth scompare e torni a combattere normalmente.",
	            "atkmod"=>1.05,
	            "defmod"=>1.10,
	            "roundmsg"=>"Il ricordo dell'allegra ballata di Seth migliora le tue capacità combattive!",
	            "activate"=>"roundstart");

              break;  
          }
        }
      }
            if ($session[user][sex]==1 && $_GET[flirt]<>""){
              //$session[user][seenlover]=0;
              if ($session[user][seenlover]==0){
                  if ($session['user']['marriedto']==4294967295){
                    if (e_rand(1,4)==1){
                      output("Ti avvicini a Seth per coccolarlo un po' e gli baci la faccia ed il collo, ma lui borbotta qualcosa riguardo ");
                      switch(e_rand(1,4)){
                      case 1:
                        output("l'essere troppo occupato ad accordare il suo liuto,");
                        break;
                      case 2:
                        output("di \"quel periodo del mese,\"");
                        break;
                      case 3:
                        output("di \"un po' di raffreddore...  *coff coff* vedi?\"");
                        break;
                      case 4:
                        output("del fatto che vorrebbe che gli portassi una birra,");
                        break;
                      }
                      output("e dopo un commento del genere, te ne vai!");
                      $session['user']['charm']--;
                      output("`n`n`^PERDI un punto di fascino!");
                    }else{
                      output("Tu e Seth vi prendete qualche momento per voi, e lasci la locanda raggiante!");
                      $session['bufflist']['lover']=$buff;
                      $session['user']['charm']++;
                      output("`n`n`^Guadagni un punto di fascino!");
                    }
                    $session['user']['seenlover']=1;
                  } elseif ($_GET[flirt]==""){
                    }else{
                      $c = $session[user][charm];
                        $session[user][seenlover]=1;
                      switch($_GET[flirt]){
                          case 1:
                              if (e_rand($c,2)>=2){
                                  output("Seth fa un ampio sorriso a 32 denti. Cielo, non è carina la fossetta nel suo mento??");
                                    if ($c<4) $c++;
                                }else{
                                  output("Seth inarca un sopracciglio e ti domanda se per caso hai qualcosa nell'occhio.");
                                }
                              break;
                          case 2:
                              if (e_rand($c,4)>=4){
                                  output("Seth inarca un sopracciglio e ti domanda se per caso hai qualcosa nell'occhio`0\"");
                                    if ($c<7) $c++;
                                }else{
                                    output("Seth sorride e saluta con la mano... la persona dietro di te.");
                                }
                              break;
                          case 3:
                              if (e_rand($c,7)>=7){
                                  output("Seth si piega e recupera il tuo fazzoletto, mentre tu ammiri il suo sodo posteriore.");
                                    if ($session[user][charisma]==4294967295) {
                              output(" Tuo marito sarà molto deluso quando verrà a saperlo!");
                              $c--;
                           } else {
                              if ($c<11) $c++;
                           }
                                }else{
                                    output("Seth si piega e recupera il tuo fazzoletto, si soffia il naso e te lo restituisce.");
                                }
                              break;
                          case 4:
                              if (e_rand($c,11)>=11){
                                  output("Seth ti mette un braccio intorno alla vita e ti scorta fino al bancone dove ti offre una delle migliori brodaglie della locanda.");
                                    if ($session[user][charisma]==4294967295) {
                              output(" Tuo marito sarà molto deluso quando verrà a saperlo!");
                              $c--;
                           } else {
                              if ($c<14) $c++;
                           }
                                }else{
                                  output("Seth si scusa, \"`^Mi spiace milady, non ho soldi da spendere,`0\" mentre rovescia il suo borsellino mangiucchiato dalle tarme.");
                                    if ($c>0 && $c<10) $c--;
                                }
                              break;
                          case 5:
                              if (e_rand($c,14)>=14){
                                  output("Ti avvicini a Seth, lo afferri dalla maglia, lo fai alzare in piedi e gli dai un deciso, lungo bacio sulle labbra. Lui collassa subito dopo, con i capelli un po' disordinati e il respiro mozzato.");
                                    if ($session[user][charisma]==4294967295) {
                              $c--;
                           } else {
                              if ($c<18) $c++;
                           }
                                }else{
                                  output("Ti abbassi per baciare Seth sulle labbra, ma appena lo fai lui si piega per allacciarsi una scarpa.");
                                    // $session[user][hitpoints]=1; //why the heck was this here???
                                    if ($c>0 && $c<13) $c--;
                                }
                                if ($session[user][charisma]==4294967295) output(" Tuo marito sarà molto deluso quando verrà a saperlo!");

                              break;
                          case 6:
                              if (e_rand($c,18)>=18){
                              output("Stando alla base delle scale, fai cenno a Seth di avvicinarsi. Ti segue come un cagnolino.");
                                    if ($session['user']['turns']>0){
                                      output("Ti senti esausta e non puoi reggere un altro combattimento nella foresta per oggi!  ");
                                      $session['user']['turns']-=2;
                                      if ($session['user']['turns']<0) $session['user']['turns']=0;
                                    }
                                    addnews("`@".$session[user][name]."`@ e `^Seth`@ sono stati visti salire insieme le scale della locanda.");
                                    if ($session[user][charisma]==4294967295 && e_rand(1,3)==2) {
                              $sql = "SELECT acctid,name FROM accounts WHERE locked=0 AND acctid=".$session[user][marriedto]."";
                                $result = db_query($sql) or die(db_error(LINK));
                              $row = db_fetch_assoc($result);
                              $partner=$row[name];
                              addnews("`\$$partner ha lasciato ".$session[user][name]."`\$ per una scappatella con `^Seth`\$!");
                              output("`nQuesto era troppo per $partner! Chiede il divorzio. La metà dell'oro che è in banca viene aggiudicato a lui. D'ora in poi sei di nuovo single!");
                              $session[user][charisma]=0;
                              $session[user][marriedto]=0;
                              if ($session[user][goldinbank]>0) $getgold=round($session[user][goldinbank]/2);
                              $session[user][goldinbank]-=$getgold;
                              $sql = "UPDATE accounts SET charisma=0,marriedto=0,goldinbank=goldinbank+($getgold+0) WHERE acctid='$row[acctid]'";
                              db_query($sql);
                              systemmail($row['acctid'],"`\$Scappatella!`0","`&{$session['user']['name']}`6 ti tradisce con Seth!`nE' motivo sufficiente per te per chiedere il divorzio. D'ora in poi sei di nuovo single.`nRivecerai `^$getgold`6 del suo patrimonio sul tuo conto in banca.");
                           }else if ($session[user][charisma]==4294967295) {
                              output(" Tuo marito sarà molto deluso quando verrà a saperlo!");
                              $c--;
                           }else{
                              if ($c<25) $c++;
                           }
                                }else{
                                  output("\"`^Mi spiace milady ma ho un'esibizione tra 5 minuti`0\"");
                                    if ($c>0) $c--;
                                }
                              break;
                            case 7:
                                output("Avvicinandoti a Seth, gli chiedi semplicemente di sposarti.`n`nLui ti guarda per qualche secondo.`n`n");
                              if ($c>=22){
                                    output("\"`^Certo amore!`0\" dice. Le settimane successive fuggono mentre preparate il meraviglioso matrimonio, pagato interamente da Seth, e vi dirigete verso il fitto della foresta per la luna di miele.");
                                    addnews("`&".$session[user][name]." e `^Seth`& oggi si sono gioiosamente uniti in matrimonio!!!");
                                    debuglog("si è sposata con Seth");
                                    $session['user']['marriedto']=4294967295; //int max.
                                    $session['bufflist']['lover']=$buff;
                                }else{
                                    output("Seth dice, \"`^Mi spiace, apparentemente ti ho dato un'impressione sbagliata, penso che dovremmo essere solo buoni amici.");
                                    $session[user][turns]=0;
                                    debuglog("perde tutti i turni rifiutato da Seth");
                                }
                        }
                        if ($c > $session[user][charm]) output("`n`n`^Guadagni un punto di fascino!");
                        if ($c < $session[user][charm]) output("`n`n`\$PERDI un punto di fascino!");
                        $session[user][charm]=$c;
                    }
                }else{
                    output("Pensi che sia meglio non sfidare oltre la fortuna con `^Seth`0 oggi.");
                }
            }else{
              //sorry, no lezbo action here.
            }
            break;
        case "converse":
          output("Ti avvicini ad un tavolo, metti i piedi sulla panca ed ascolti le conversazioni:`n");
            viewcommentary("inn","Aggiungi alla conversazione",30,25);
            break;
        case "bartender":
          $alecost = $session[user][level]*10;
          if ($festa==1) $alecost=0; //modifica festa della birra (settaggio birra gratuita)
          if ($_GET[act]==""){
                output("`n`0Mentre ti avvicini al bancone, l'oste `7Cedrik`0 ti guarda più o meno di sghimbescio e con aria sospettosa. L'avventore della Locanda non è mai stato il tipo che si fida ".($session[user][sex]?"di una donna ":"di un uomo")." più di quanto ".($session[user][sex]?"la":"lo")." possa lanciare, ");
                output("cosa che avvantaggia alquanto i nani, tranne che nelle province in cui ");
                output("il lancio del nano è stato dichiarato illegale. `n`7Cedrik`0 lucida un bicchiere, lo mette in controluce verso la porta, ");
                output("mentre un altro cliente la apre per uscire dal locale e barcollare lungo la strada, e dopo una smorfia, sputa sul bicchiere e riprende nella sua meticolosa operazione di pulizia.  \"`%`iCos'è che vuoi?`i`0\" ti domanda con una voce gutturale che assomiglia quasi al ringhiare di un lupo.");
                //Festa della Birra (messaggio di Cedrik)
                if ($festa==1) {
                    output("`n`npoi prosegue \"`%`iA proposito, sapevi che oggi è giorno di festa in questa cittadina, per cui per tutta la giornata la birra è gratis?`i`0\"");
                }
                //fine messaggio Festa della Birra
                addnav("C?Corrompi","inn.php?op=bartender&act=bribe");
                addnav("G?Gemme","inn.php?op=bartender&act=gems");
                //if (getsetting("statofagioli", "chiuso") != "chiuso" AND $session['user']['reincarna'] < 2){
                if (getsetting("statofagioli", "chiuso") != "chiuso"){
                   addnav("F?Concorso dei Fagioli","fagioli.php");
                }else{
                	if ($festa==1) {
                   		output("`n\"`%`iAh, stavo per dimenticarmi di avvisarti che il concorso dei fagioli è chiuso`i");
                	} else {
                		output("`n`n\"`%`iAh, stavo per dimenticarmi di avvisarti che il concorso dei fagioli è chiuso`i");
                	
                	}
                   if (getsetting("tentanomefagioli", "nessuno") != "nessuno"){
                      output("`i e l'ultimo vincitore del concorso è stato `6`b".getsetting("vincitorefagioli","")."`b`i`5\"");
                   } else output(".`n");
                }
                addnav("Birra (`^$alecost`0 pezzi d'oro)","inn.php?op=bartender&act=ale");
                
              if ($session[user][sex]==1){
              	$drunkenness = array(-1=>"terribilmente sobria",
                                                         0=>"`&piuttosto sobria",
                                                         1=>"`7leggermente brilla",
                                                         2=>"`6alticcia",
                                                         3=>"`^quasi ubriaca",
                                                         4=>"`3leggermente ubriaca",
                                                         5=>"`#ubriaca",
                                                         6=>"`@molto ubriaca",
                                                         7=>"`5sbronza",
                                                         8=>"`%completamente sbronza",
                                                         9=>"`4quasi priva di sensi",
                                                         10=>"`\$devastata dall'alcool"
                                    );
                }else{
                	$drunkenness = array(-1=>"terribilmente sobrio",
                                                         0=>"`&piuttosto sobrio",
                                                         1=>"`7leggermente brillo",
                                                         2=>"`6alticcio",
                                                         3=>"`^quasi ubriaco",
                                                         4=>"`3leggermente ubriaco",
                                                         5=>"`#ubriaco",
                                                         6=>"`@molto ubriaco",
                                                         7=>"`5sbronzo",
                                                         8=>"`%completamente sbronzo",
                                                         9=>"`4quasi privo di sensi",
                                                         10=>"`\$devastato dall'alcool"
                                    );

                } 
                $drunk = round($session[user][drunkenness]/10-.5,0);
                if ($drunk > 10) $drunk=10;
                output("`n`n`0Ti senti ".$drunkenness[$drunk]."`n`n");
            }else if ($_GET[act]=="gems"){
              if ($_POST[gemcount]==""){
                    output("\"`%`iHai delle gemme, vero?`i`0\" chiede `7Cedrik`0. \"`%`iBeh, ti preparerò un elisir magico per `^due gemme`%!`i`0\"");
                    output("`n`nQuante gemme gli dai?");
                    output("<form action='inn.php?op=bartender&act=gems' method='POST'><input name='gemcount' value='0'><input type='submit' class='button' value='Dai'>`n",true);
                    output("E cosa desideri?`n<input type='radio' name='wish' value='1' checked> Fascino`n<input type='radio' name='wish' value='2'> Vitalità`n",true);
                    addnav("","inn.php?op=bartender&act=gems");
                    output("<input type='radio' name='wish' value='3'> Salute`n",true);
                    output("<input type='radio' name='wish' value='4'> Dimenticanza`n",true);
                    output("<input type='radio' name='wish' value='5'> Transmutazione</form>",true);
                }else{
                  $gemcount = abs((int)$_POST[gemcount]);
                    if ($gemcount>$session[user][gems]){
                      output("`7Cedrik`0 ti guarda con occhi vacui.  \"`%`iNon hai tutte queste gemme, `bvai a procurartene delle altre!`b`i`0\" dice.");
                    }else{
                      output("`#Metti $gemcount gemme sul bancone.");
                        if ($gemcount % 2 == 0){

                        }else{
                            output("  `7Cedrik`0, sapendo dei tuoi problemi di base con la matematica ");
                            output("te ne restituisce una.");
                            $gemcount-=1;
                        }
                        if ($gemcount>0) {
                            output("  Bevi la pozione che `7Cedrik`0 ti da in cambio delle tue gemme, e.....`n`n");
                            $session[user][gems]-=$gemcount;
                            debuglog("ha usato $gemcount gemme da `7Cedrik`0 per la pozione {$_POST[wish]}");
                            switch($_POST[wish]){
                                case 1:
                                    $session[user][charm]+=($gemcount/2);
                                    output("`&Ti senti affascinante! `^(Guadagni punti di fascino)");
                                    break;
                                case 2:
                                    $session[user][maxhitpoints]+=($gemcount/2);
                                    $session[user][hitpoints]+=($gemcount/2);
                                    output("`&Ti senti vigoroso! `^(Il tuo massimo di punti ferita aumenta)");
                                    break;
                                case 3:
                                    if ($session[user][hitpoints]<$session[user][maxhitpoints]) $session[user][hitpoints]=$session[user][maxhitpoints];
                                    $session[user][hitpoints]+=($gemcount*10);
                                    output("`&Ti senti bene! `^(Guadagni punti ferita temporanei)");
                                    break;
                                case 4:
                                    $session[user][specialty]=0;
                                    output("`&Ti senti completamente privo di direzione nella vita. Dovcresti riposare e prendere delle importanti decisioni per il tuo futuro! `^(La tua specialità è stata resettata)");
                                    break;
                                case 5:
                                    if ($session['user']['race']==1) $session['user']['attack']--;
                                    if ($session['user']['race']==2) $session['user']['defence']--;
                                    $session['user']['race']=0;
                                    output("`@Ti pieghi in due tremando per l'effetto della pozione di trasformazione, mentre le tue ossa diventano gelatina!`n`^(La tua razza è stata cancellata, ne potrai scegliere una nuova domani.)");
                                    if (isset($session['bufflist']['transmute'])) {
                                        $session['bufflist']['transmute']['rounds'] += 10;
                                    } else {
                                        $session['bufflist']['transmute']=array(
                                            "name"=>"`6Nausea da Trasmutazione",
                                            "rounds"=>10,
                                            "wearoff"=>"Smetti di tirar su le tue budella.  Letteralmente.",
                                            "atkmod"=>0.75,
                                            "defmod"=>0.75,
                                            "roundmsg"=>"Parte del tuo corpo si rimodella come cera.",
                                            "survivenewday"=>1,
                                            "newdaymessage"=>"`6A causa dell'effetto della Pozione della  Trasmutazione, sei ancora `2malato`6.",
                                            "activate"=>"offense,defense"
                                        );
                                    }
                                    break;
                            }
                        }else{
                          output("`n`nSenti che le tue gemme potrebbero essere usate meglio in altro modo, non per delle pozioni puzzolenti.");
                        }
                    }
                }
            }else if ($_GET[act]=="bribe"){
                $g1 = $session[user][level]*10;
                $g2 = $session[user][level]*50;
                $g3 = $session[user][level]*100;
                if ($_GET[type]==""){
                    output("Quanto vuoi offrirgli?");
                    addnav("1 gemma","inn.php?op=bartender&act=bribe&type=gem&amt=1");
                    addnav("2 gemme","inn.php?op=bartender&act=bribe&type=gem&amt=2");
                    addnav("3 gemme","inn.php?op=bartender&act=bribe&type=gem&amt=3");
                    addnav("$g1 pezzi d'oro","inn.php?op=bartender&act=bribe&type=gold&amt=$g1");
                    addnav("$g2 pezzi d'oro","inn.php?op=bartender&act=bribe&type=gold&amt=$g2");
                    addnav("$g3 pezzi d'oro","inn.php?op=bartender&act=bribe&type=gold&amt=$g3");
                }else{
                  if ($_GET[type]=="gem"){
                        if ($session['user']['gems']<$_GET['amt']){
                            $try=false;
                            output("Non hai {$_GET['amt']} gemme!");
                        }else{
                          $chance = $_GET[amt]/4;
                            $session[user][gems]-=$_GET[amt];
                            debuglog("ha speso {$_GET['amt']} gemme per corrompere `7Cedrik`0");
                            $try=true;
                        }
                    }else{
                        if ($session['user']['gold']<$_GET['amt']){
                            output("Non hai {$_GET['amt']} pezzi d'oro!");
                            $try=false;
                        }else{
                            $try=true;
                            $chance = $_GET[amt]/($session[user][level]*40);
                            $session[user][gold]-=$_GET[amt];
                            debuglog("ha speso {$_GET['amt']} oro per corrompere `7Cedrik`0");
                        }
                    }
                    $chance*=100;
                    if ($try){
                        if (e_rand(0,100)<$chance){
                            output("`7Cedrik`0 si piega sopra il bancone verso di te.  \"`%`iChe posso fare per te?`i`0\" domanda.");
                                if (getsetting("pvp",1)) {
                                addnav("Chi c'è di sopra?","inn.php?op=bartender&act=listupstairs");
                            }
                            addnav("Dimmi dei colori","inn.php?op=bartender&act=colors");
                            addnav("Cambia specialità","inn.php?op=bartender&act=specialty");
                        }else{
                            output("`7Cedrik`0 inizia a pulire la parte di sopra del bancone, una cosa che doveva essere fatta da molto tempo.  ");
                            output("Quando finisce, ");
                            if ($_GET[type]=="gem") {
                               if ($_GET[amt]>1){
                                  output("le tue gemme sono sparite. ");
                               }else{
                                  output("la tua gemma è sparita. ");
                               }
                            }else{
                                  output("i tuoi pezzi d'oro sono spariti. ");
                            }
                            output("Gli chiedi che fine ha".($_GET[amt]>1?"nno":"")." fatto e lui ti guarda con espressione stranita.");
                        }
                    }else{
                        output("`n`nC`7Cedrik`0 ti guarda con espressione stranita.");
                    }
                }
            }else if ($_GET[act]=="ale"){
              output("`nSbattendo il pugno sul bancone, chiedi una birra");
                if ($session[user][drunkenness]>66){
                  //************************************************************************************************************************************
                    output(", ma `7Cedrik`0 continua a pulire i bicchieri su cui stava lavorando.  \"`%`iNe hai avuta abbastanza ".($session[user][sex]?"ragazza":"ragazzo").",`i`0\" ti dice.");
                }else{
                  if ($session[user][gold]>=$alecost){
                      $session[user][drunkenness]+=33;
                        $session[user][bladder]+=3;
                        $session[user][gold]-=$alecost;
                        debuglog("ha speso $alecost oro per una birra");
                        output(".  `7Cedrik`0 tira fuori un bicchiere e versa una birra spumosa da un barile dietro di lui.  ");
                        output("o lancia lungo il bancone e tu lo afferri con i tuoi riflessi da guerrier".($session[user][sex]?"a":"o").".  ");
                        output("`n`nVoltandoti, bevi un grosso sorso della bevanda, e rivolgi a ".($session[user][sex]?"Seth":"Violet"));
                        output(" un sorriso con i baffi di schiuma.`n`n");
                        switch(e_rand(1,3)){
                          case 1:
                            case 2:
                              output("`&Ti senti in salute!");
                                $session[user][hitpoints]+=round($session[user][maxhitpoints]*.1,0);
                                break;
                            case 3:
                              output("`&Ti senti vigoros".($session[user][sex]?"a":"o")."!");
                                $session[user][turns]++;
                        }
                        $session[bufflist][101] = array("name"=>"`#Ronzio","rounds"=>10,"wearoff"=>"Il tuo ronzio svanisce.","atkmod"=>1.25,"roundmsg"=>"Emetti proprio un bel ronzio.","activate"=>"offense");
                    }else{
                      output("Non hai abbastanza soldi. Come pensi di comprare della birra senza soldi!?!");
                    }
                }
            }else if ($_GET[act]=="listupstairs"){
                addnav("Ricarica la lista","inn.php?op=bartender&act=listupstairs");
                output("`n`0L'oste `7Cedrik`0 sgancia dalla cintura un pesante mazzo di chiavi e lo appoggia sul bancone spiegandoti poi per ogni chiave a quale numero di stanza questa corrisponde. A questo punto hai la possibilità di intrufolarti in tutte le stanze ed attaccare chiunque abbia un livello più o meno simile al tuo.`n");
                $pvptime = getsetting("pvptimeout",600);
                $pvptimeout = date("Y-m-d H:i:s",strtotime(date("r")."-$pvptime seconds"));
                pvpwarning();
                $days = getsetting("pvpimmunity", 5);
                $exp = getsetting("pvpminexp", 1500);
                $sql = "SELECT name,alive,location,sex,level,laston,loggedin,login,pvpflag FROM accounts WHERE
                (locked=0) AND
                (level >= ".($session[user][level]-1)." AND level <= ".($session[user][level]+2).") AND
                (alive=1 AND location=1) AND
                (lastip <> '".$session['user']['lastip']."') AND (emailaddress <> '".$session['user']['emailaddress']."') AND
                (age > $days OR dragonkills > 0 OR reincarna > 0 OR pk > 0 OR experience > $exp) AND
                (laston < '".date("Y-m-d H:i:s",strtotime(date("r")."-".getsetting("LOGINTIMEOUT",900)." sec"))."' OR loggedin=0) AND
                (acctid <> ".$session[user][acctid].")
                ORDER BY level DESC";
                $result = db_query($sql) or die(db_error(LINK));
                output("<table border='0' cellpadding='3' cellspacing='0'><tr><td>Name</td><td>Level</td><td>Ops</td></tr>",true);
                $countrow = db_num_rows($result);
                for ($i=0; $i<$countrow; $i++){
                //for ($i=0;$i<db_num_rows($result);$i++){
                    $row = db_fetch_assoc($result);
                    $biolink = "bio.php?char=".rawurlencode($row[login])."&ret=".urlencode($_SERVER['REQUEST_URI']);
                    addnav("", $biolink);
                    if($row[pvpflag]>$pvptimeout){
                        output("<tr class='".($i%2?"trlight":"trdark")."'><td>$row[name]</td><td>$row[level]</td><td>[ <a href='$biolink'>Bio</a> | `i(Attaccato di recente)`i ]</td></tr>",true);
                    }else{
                        output("<tr class='".($i%2?"trlight":"trdark")."'><td>$row[name]</td><td>$row[level]</td><td>[ <a href='$biolink'>Bio</a> | <a href='pvp.php?op5=locanda&act=attack&bg=1&name=".rawurlencode($row[login])."'>Attacca</a> ]</td></tr>",true);
                        addnav("","pvp.php?op5=locanda&act=attack&bg=1&name=".rawurlencode($row[login]));
                    }
                }
                output("</table>",true);
            }else if($_GET[act]=="colors"){
              output("`7Cedrik`0 si piega in avanti sul bancone.  \"`%`iCosì vuoi sapere dei colori, vero?`i`0\" domanda.");
                output("  Stai per rispondergli quando ti rendi conto che era una domanda retorica.  ");
                output("`7Cedrik`0 continua, \"`%`iPer fare i colori, ecco cosa devi fare.  Prima cosa, usa un segno &#0096; ",true);
                output("(ALT + 096) seguito da 1, 2, 3, 4, 5, 6, 7, !, @, #, $, %, ^, &.  Ognuno corrisponde ad  ");
                output("un colore come questo: `n`1&#0096;1 `2&#0096;2 `3&#0096;3 `4&#0096;4 `5&#0096;5 `6&#0096;6 `7&#0096;7 ",true);
                output("`n`!&#0096;! `@&#0096;@ `#&#0096;# `\$&#0096;\$ `%&#0096;% `^&#0096;^ `&&#0096;& `n",true);
                output("`% capito?`0\" Puoi fare pratica qui:`i");
                output("<form action=\"$REQUEST_URI\" method='POST'>",true);
                output("Hai scritto ".str_replace("`","&#0096;",HTMLEntities($_POST[testtext]))."`n",true);
                output("Ecco cosa appare ".$_POST[testtext]." `n");
                output("<input name='testtext' id='input'><input type='submit' class='button' value='Prova'></form>",true);
                output("<script language='javascript'>document.getElementById('input').focus();</script>",true);

                output("`0`n`n`iQuesti colori possono essere usati per il tuo nome e per qualunque conversazione.`i");
                addnav("",$REQUEST_URI);
            }else if($_GET[act]=="specialty"){
                if ($_GET[specialty]==""){
                    output("\"`2Voglio cambiare specialità,`0\" annunci a `7Cedrik`0.`n`n");
                    output("Senza una parola, `7Cedrik`0 ti afferra dalla maglietta, ti tira oltre il bancone e dietro ");
                    output("i barili dietro di esso. Qui, gira il rubinetto di una botticella etichettata \"Buona Brodaglia XXX\"");
                    output("`n`nTi guardi intorno cercando la porta segreta che sai dovrebbe aprirsi nei dintorni quando `7Cedrik`0 ");
                    output("gira di nuovo il rubinetto e solleva una tazza spumosa piena di quella che apparentemente è la sua buona brodaglia, colore blu-verde ");
                    output("e tutto.");
                    output("`n`n\"`3`iChe? Ti aspettavi una stanza segreta?`i`0\" domanda.  \"`3`iOra, dovresti essere più ");
                    output("cauto quando dici cose del tipo che vuoi cambiare specialità, non a tutti ");
                    output("piace questo genere di cose.`n`n`0\"`3Che nuova specialità avevi in mente?`i`0\"");
                    addnav("Arti Oscure",preg_replace("/[&?]c=[[:digit:]-]*/","",$REQUEST_URI)."&specialty=1");
                    addnav("Poteri Mistici",preg_replace("/[&?]c=[[:digit:]-]*/","",$REQUEST_URI)."&specialty=2");
                    addnav("Furto",preg_replace("/[&?]c=[[:digit:]-]*/","",$REQUEST_URI)."&specialty=3");
                    addnav("Militare",preg_replace("/[&?]c=[[:digit:]-]*/","",$REQUEST_URI)."&specialty=4");
                    addnav("Seduzione",preg_replace("/[&?]c=[[:digit:]-]*/","",$REQUEST_URI)."&specialty=5");
                    addnav("Tattica",preg_replace("/[&?]c=[[:digit:]-]*/","",$REQUEST_URI)."&specialty=6");
                    addnav("Pelle di Roccia",preg_replace("/[&?]c=[[:digit:]-]*/","",$REQUEST_URI)."&specialty=7");
                    addnav("Retorica",preg_replace("/[&?]c=[[:digit:]-]*/","",$REQUEST_URI)."&specialty=8");
                    addnav("Muscoli",preg_replace("/[&?]c=[[:digit:]-]*/","",$REQUEST_URI)."&specialty=9");
                    addnav("Natura",preg_replace("/[&?]c=[[:digit:]-]*/","",$REQUEST_URI)."&specialty=10");
                    addnav("Clima",preg_replace("/[&?]c=[[:digit:]-]*/","",$REQUEST_URI)."&specialty=11");

                }else{
                    output("\"`3OK allora,`0\" dice `7Cedrik`0, a posto.`n`n\"`2`iTutto qui?`i`0\" gli chiedi.");
                    output("`n`n\"`3`iYep. Che ti aspettavi, Qualche strano rituale???`i`0\" `7Cedrik`0 ");
                    output("inizia a ridere fragorosamente.  \"`3`iSei a posto, ragazz".($session[user][sex]?"a":"o")."... solo non giocare mai a poker, eh?`0");
                    output("`n`n\"`3Oh, un'ultima cosa. I tuoi vecchi punti di utilizzo e livello di abilità valgono ancora per quel talento, ");
                    output("dovrai guadagnartene qualcuno in questo per essere brav".($session[user][sex]?"a":"o").".`i`0\"");
                    //addnav("Return to the inn","inn.php");
                    $session[user][specialty]=$_GET[specialty];
                }
            }
            break;
        case "room":
            $config = unserialize($session['user']['donationconfig']);
            $expense = round(($session[user][level]*(10+log($session[user][level]))),0);
            if ($_GET[pay]){
                if ($_GET['coupon']==1){
                    $config['innstays']--;
                    $session['user']['donationconfig']=serialize($config);
                    $session['user']['loggedin']=0;
                    $session['user']['sconnesso']=0;
                    $session['user']['location']=1;
                    $session['user']['locazione']=0;
                    $session['user']['boughtroomtoday']=1;
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
                    saveuser();
                    $session=array();
                    redirect("index.php");
                }else{
                    if ($_GET[pay] == 2 || $session[user][gold]>=$expense || $session[user][boughtroomtoday]){
                        if ($session[user][loggedin]){
                            if ($session[user][boughtroomtoday]) {
                            }else{
                                if ($_GET[pay] == 2) {
                                    $fee = getsetting("innfee", "5%");
                                    if (strpos($fee, "%"))
                                        $expense += round($expense * $fee / 100,0);
                                    else
                                        $expense += $fee;
                                    $goldline = ",goldinbank=goldinbank-$expense";
                                } else {
                                    $goldline = ",gold=gold-$expense";
                                }
                                $goldline .= ",boughtroomtoday=1";
                            }
                            debuglog("ha speso $expense oro per una stanza alla locanda");
                            debuglog("va a dormire alla locanda");
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
                            $sql = "UPDATE accounts SET loggedin=0, sconnesso=0, location=1, locazione=0 $goldline WHERE acctid = ".$session[user][acctid];
                            db_query($sql) or die(sql_error($sql));
                        }
                        $session=array();
                        redirect("index.php");
                    }else{
                        output("\"`iAah, è così,`i\" dice `7Cedrik`0 mentre rimette la chiave che aveva preso sul gancio ");
                        output("dietro il bancone. `iForse dovresti procurarti dei fondi sufficienti prima di cercare di affittare una stanza nella mia prestigiosa locanda.`i");
                    }
                }
            }else{
                if ($session[user][boughtroomtoday]){
                    output("Hai già pagato la tua stanza per oggi.");
                    addnav("Vai alla stanza","inn.php?op=room&pay=1");
                }else{
                    if ($config['innstays']>0){
                        addnav("Mostragli il tuo buono per ".$config['innstays']." giornate nella locanda","inn.php?op=room&pay=1&coupon=1");
                    }
                    output("`nTi avvicini a `7Cedrik`0 e gli domandi se ha una stanza disponibile. Lui ti squadra attentamente, poi ti dice che la stanza migliore del suo locale è libera e che ti costerà la miseria di `^".$expense." pezzi d'oro`0 per una notte.");
                    $fee = getsetting("innfee", "5%");
                    if (strpos($fee, "%"))
                        $bankexpense = $expense + round($expense * $fee / 100,0);
                    else
                        $bankexpense = $expense + $fee;
                    if ($session['user']['goldinbank'] >= $bankexpense && $bankexpense != $expense) {
                        output("`nAggiunge inoltre che, poichè ti ritiene una persona per bene, può anche farti un prezzo speciale di `^".$bankexpense."`0 pezzi d'oro se pagherai direttamente tramite la banca, prezzo che include solo un piccolo contributo spese del `#" . (strpos($fee, "%") ? $fee : "$fee gold"));
                    }

                    output("`n`n`0Vorresti trattare sul prezzo, non volendo separarti dal tuo oro visto che i campi offrono un posto gratuito per dormire, ma poi ti rendi conto che la locanda è da considerarsi un rifugio decisamento più sicuro, essendo meno probabile che occasionali vagabondi possano entrare nella tua stanza mentre dormi per tentare di derubarti.");
                    addnav("Dagli $expense pezzi d'oro","inn.php?op=room&pay=1");
                    if ($session['user']['goldinbank'] >= $bankexpense) {
                        addnav("Paga $bankexpense pezzi d'oro dalla banca","inn.php?op=room&pay=2");
                    }
                }
            }
            break;
    }
  addnav("Torna alla Locanda","inn.php");
}

output("</span>",true);

page_footer();
?>