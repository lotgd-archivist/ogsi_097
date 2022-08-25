<?php
/*
autore:        Xtramus (chumkiu)
data:          13/2/06
descrizione:   La Caverna oscura è il regno del Beholder. Un mostro potente
               e magico. E' una grande testa con un occhio al centro e una
               bocca! Al posto dei capelli ha 10 tentacoli, ognuno dei quali
               termina con un occhio!
               Sconfiggere il Beholder senza determinati
               oggetti magici è impossibile!
               Quando si entra in caverna è possibile inciampare in
               uno di questi oggetti:
               - un teschio:  annienta la resistenza agli incantesimi
                              del Beholder (è possibile utilizzare
                              poteri speciali come voodoo ecc. ecc.)

               - una torcia:  Acceca gli occhi del Beholder (diminuisce
                              l'attacco perché il mostro non può fare incanti).

               - una spada:   è magica! Acceca e stordisce il Beholder. Diventa
                              relativamebnte facile sconfiggerlo.
                              
               - una pietra:  Non accade nulla

               - una borsa:   Contiene gemme e l'evento finisce.

               La spada e il teschio è possibile portarli via dalla caverna!
               A seconda del caso la spada puoi tenerla per combattimenti
               futuri, e il teschio ti incanta regalandoti poteri in
               arti oscura!
               Una volta sconfitto il Beholder con la spada, essa può essere
               non utilizzabile lasciando il pg senz'armi. Ergo il Beholder
               accecato e stordito deve regalare molte monete.

*/

// dichiaro un pò di funzioni
function continua_ES() {
global $session;
$session['user']['specialinc']="cavernaoscura.php";
}

function prendiSpada_ES() {
// valori della spada
global $session;
    $session['user']['weapon'] = "Spada Lucente della Caverna Oscura";
    $session['user']['attack']-=$session['user']['weapondmg'];
    $session['user']['weapondmg'] = 12;
    $session['user']['attack']+=$session['user']['weapondmg'];
    $session['user']['weaponvalue'] = 10000;
    $session['user']['usura_arma']= intval($session['user']['weapondmg'] * max((15 + $session['user']['dragonkills']/2 - 2*$session['user']['reincarna']),10)) + 100;
    addnews("`%".$session['user']['name'] . " `2ha trovato una `#Spada Lucente`2 nella `xCaverna Oscura!!`0");
}

function gotpower_ES() {
// ha la spada?
global $session;
     if($session['user']['weapon'] == "Spada Lucente della Caverna Oscura") {
          return true;
     }
     else {
          return false;
     }
}

function creaBeholder_ES($power=FALSE) {
/* creatura magica: testa tentacolare dai molti occhi!
Se non si ha la spada o il teschio o la torcia DEVE risultare invincibile!
Il Beholder senza penalità non permette di eseguire incantesimi: è immune a tutti i poteri speciali.
Il Teschio permette di usare poteri speciali
La Torcia acceca il Beholder, non gli permette di eseguire incantesimi (meno attacco)
La Spada lucente lo stordisce e lo acceca!
*/
global $session;
$badguy= Array();
     if(gotpower_ES() || $power=="spada") {
          // stordito e accecato dal potere della spada! Valori che fanno schifo, ma piena vita!
          // in caso di vittoria dà molto oro perché si può perdere la spada lucente e si rimane coi pugni
          $badguy['creatureexp']= e_rand(100,150)*$session['user']['level'];
          $badguy['playerstarthp']=($session['user']['maxhitpoints']>=500)? $session['user']['maxhitpoints']*2 : 1000;
          $badguy['creaturename']="Il Beholder accecato e stordito";
          $badguy['creatureweapon']="Urla strazianti";
          $badguy['creaturegold']=e_rand(8000,12000);
          $badguy['creaturegems']=e_rand(3,5);
          $badguy['creaturelevel']=8;
          $badguy['creatureattack']=35;
          $badguy['creaturedefense']=5;
          $badguy['creaturehealth']=$badguy['playerstarthp'];
          $badguy['diddamage']=0;
          $badguy['incantesimi']=false;
          $badguy['resisteincanti']=false;
          $badguy['fama']=500;
     }
     else if($power=="teschio") {
          // volubile agli incantesimi (sono attivi i poteri speciali)
          $badguy['creatureexp']= e_rand(100,150)*$session['user']['level'];
          $badguy['playerstarthp']=($session['user']['maxhitpoints']>=500)? $session['user']['maxhitpoints']*2 : 1000;
          $badguy['creaturename']="Il Beholder esposto ad Incantesimi";
          $badguy['creatureweapon']="Incantesimo di annientamento della volontà";
          $badguy['creaturegold']=e_rand(3000,5000);
          $badguy['creaturegems']=e_rand(3,7);
          $badguy['creaturelevel']=16;
          $badguy['creatureattack']=150;
          $badguy['creaturedefense']=10;
          $badguy['creaturehealth']=$badguy['playerstarthp'];
          $badguy['diddamage']=0;
          $badguy['incantesimi']=true;
          $badguy['resisteincanti']=false;
          $badguy['fama']=250;
     }
     else if($power=="torcia") {
          // accecato! Meno valori in attacco. meno vita
          $badguy['creatureexp']= e_rand(100,150)*$session['user']['level'];
          $badguy['playerstarthp']=($session['user']['maxhitpoints']>=300)? $session['user']['maxhitpoints']*2 : 600;
          $badguy['creaturename']="Il Beholder accecato";
          $badguy['creatureweapon']="Denti aguzzi";
          $badguy['creaturegold']=e_rand(3000,5000);
          $badguy['creaturegems']=e_rand(3,7);
          $badguy['creaturelevel']=10;
          $badguy['creatureattack']=60;
          $badguy['creaturedefense']=10;
          $badguy['creaturehealth']=$badguy['playerstarthp'];
          $badguy['diddamage']=0;
          $badguy['incantesimi']=false;
          $badguy['resisteincanti']=true;
          $badguy['fama']=150;
     }
     else {
          // Pieni poteri suoi, senza poteri noi (imbattibile...)
          // sarebbe meglio anche saltare il combattimento
          //e morire direttamente... che dite?
          $badguy['creatureexp']= e_rand(0,1)*$session['user']['level'];
          $badguy['playerstarthp']=($session['user']['maxhitpoints']>=2500)? $session['user']['maxhitpoints']*2 : 5000;
          $badguy['creaturename']="Il terrificante Beholder";
          $badguy['creatureweapon']="Incantesimo di pietrificazione";
          $badguy['creaturegold']=e_rand(10000,20000);
          $badguy['creaturegems']=10;
          $badguy['creaturelevel']=30;
          $badguy['creatureattack']=50+$session['user']['attack'];
          $badguy['creaturedefense']=50+$session['user']['defence'];
          $badguy['creaturehealth']=$badguy['playerstarthp'];
          $badguy['diddamage']=0;
          $badguy['incantesimi']=true;
          $badguy['resisteincanti']=true;
          $badguy['fama']=1500;
     }
     $session['user']['badguy']=createstring($badguy);
}

page_header("La Caverna Oscura!");
output("`n`c`b`xL'antro della Caverna Oscura`0`b`c`n",true);

/* *********** INIZIO ************** */

if($_GET['op']=="") {
     if(!gotpower_ES()) {
     output("`2Nella fitta foresta riesci a scorgere, per la prima volta, una fessura in una roccia nascosta da folti cespugli!!`n");
     output("Sradichi alcune erbacce e scopri che la spaccatura si allarga in una ampia `xcaverna!`2`nLa `xbuia`2 galleria sembra essere molto `xprofonda`2, ma nonostante questo risveglia la tua innata curiosità e la voglia di avventurose esplorazioni ");
     output("alla ricerca di pericolose creature da massacrare o di tesori nascosti. `nEntrare ed iniziare le ricerche ti costerà un turno di combattimento, e forse non solo quello...");
     }
     else {
     output("`2Nella fitta foresta riesci a ritrovare il luogo dove nel passato hai trovato la tua `#Spada Lucente della Caverna Oscura`2 !`nVuoi provare a rientrare in quella `xbuia caverna`2 e affrontare una nuova avventura?");
     }
addnav("Entra nella `7`bCaverna Oscura`b", "forest.php?op=entra");
addnav("Allontanati velocemente", "forest.php?op=scappa");
continua_ES();
}

/* *********** ENTRA ************** */

else if($_GET['op']=="entra") {
$session['user']['turns']-=1;
page_header("La Caverna Oscura!");

if (gotpower_ES()) {
	$caso_ES = 5 ;
	output("`3Entri nella caverna che viene illuminata dalla tua arma magica, mentre procedi con cautela inciampi in qualcosa, abbassi lo sguardo per scoprire che è ");	
}else{
	$caso_ES = e_rand(1,5);
	output("`xEntri nella caverna! E' tutto buio ed inizi a pensare se non sia il caso di tornare indietro!`n");
	output("Mentre procedi a tentoni inciampi su qualcosa, ma non sai bene cosa! La prendi in mano e scopri che è ");
}		
     if($caso_ES==1) {
	     $ndg_ES= e_rand(1,3);
	     output("una borsa di cuoio! La apri e trovi all'interno `&".$ndg_ES." gemm".(($ndg_ES==1)?"a":"e")."`x!`n");
	     output("Pensi che l'avventura ti abbia pagato abbastanza per il momento! Decidi quindi di ritornare sui ");
	     output("tuoi passi, ma l'oscurità ti disorienta e per trovare l'uscita perdi un altro turno di combattimenti!!`n");
	     $session['user']['turns']-=1;
	     $session['user']['gems']+=$ndg_ES;
	     debuglog("trova $ndg_ES gemme nella Caverna Oscura");
     }
     if($caso_ES==2) {
	     output("`0un teschio`x! Senti le cavità degli occhi e tutti i denti ... È proprio un teschio umano!`n");
	     output("Qualcosa o qualcuno deve aver ucciso e decapitato questo poveretto ... dato che non c'è traccia ");
	     output("nelle vicinanze del resto del corpo! Eppure ha qualcosa di strano, al tatto la consistenza del cranio sembra non essere quella di un teschio normale...`n");
	     output("`nCosa decidi di fare?");
	     addnav("Vai avanti e conserva il teschio nella bisaccia", "forest.php?op=prendiecontinua&cosa=teschio");
	     addnav("Vai avanti ma lasci il teschio", "forest.php?op=continua");
	     addnav("Esci dalla caverna con il teschio", "forest.php?op=prendiescappa&cosa=teschio");
	     addnav("Scappa via", "forest.php?op=scappa");
	     continua_ES();
     }
     if($caso_ES==3) {
	      output("l'elsa di una `#spada`x! `nLa estrai dal terreno in cui è conficcata e `3scopri che brilla di ");
	      output("luce propria! Una luce molto forte ma che non abbaglia... una luce magica!`n`n");
	      output("Cosa vuoi fare adesso?");
	      addnav("Prendi la spada e continua!", "forest.php?op=prendiecontinua&cosa=spada");
	      addnav("Prendi la spada e scappa!", "forest.php?op=prendiescappa&cosa=spada");
	      addnav("Molla tutto e scappa!", "forest.php?op=scappa");
	      continua_ES();
     }
     if($caso_ES==4) {
           output("una `(torcia!`x`n`nOvviamente è spenta ma non appena la impugni si `3illumina magicamente, illuminando l'antro e il suo contenuto. `n");
           output("Osservi quindi tutto ciò che ti circonda, anche se forse avresti preferito rimanere nella più fitta oscurità : le pareti sono letteralmente composte da ossa e teschi! Scheletri interi o parti di essi animali, `&umani`3, `^elfi`3, `#nani`3, `2troll`3... e appartenenti a creature di ogni ");
           output("razza conosciuta sono accatastate formando macabri muri e anche lo stesso pavimento sul quale appoggi i piedi non è altro che un fondo formato da resti e ossa di svariati esseri viventi! Inorridit".($session[user][sex]?"a":"o")." pensi che qualcuno o qualcosa dimora in questo ossario : ");
           output("un essere abbietto che probabilmente ha il gusto del macabro oltre ad essere decisamente pericoloso.... ora resta solo da decidere cosa fare....");
           addnav("Avanzi con gran coraggio!", "forest.php?op=prendiecontinua&cosa=torcia");
           addnav("Scappa il più velocemente possibile", "forest.php?op=scappa");
           continua_ES();
     }
     if($caso_ES==5) {
     	if (gotpower_ES()) {
	    	output("solo una comunissima `0pietra`3! `nChe sia un segnale di avvertimento del destino? ...cosa fare?");
	    }else{
	    	output("solo una comunissima `0pietra`x! `nChe sia un segnale di avvertimento del destino? ...cosa fare?");
	    }	
	     addnav("Continua (in fondo ami l'avventura)", "forest.php?op=continua");
	     addnav("Scappa via", "forest.php?op=scappa");
	     continua_ES();
     }
}

/* *********** CONTINUA ************** */

else if($_GET['op']=="prendiecontinua" || $_GET['op']=="continua") {
		$colore = "`x" ;
        if($_GET['cosa']=="spada" || gotpower_ES()) {
        	$colore = "`3" ;
        	if($_GET['cosa']=="spada") {
        		prendiSpada_ES();
            	output("$colore È una bella `#Spada Lucente $colore! La provi senza sforzo sul femore di uno scheletro, che viene reciso nettamente, riuscendo in questo modo ad apprezzarne la grande leggerezza, la perfetta equilibratura  e la lama affilatissima. ");
            	output("Probabilmente quest'arma è il frutto del connubio tra l'opera di una valente artigiano e la magia. Il possessore di questo oggetto sarà sicuramente invidiato da tutti gli abitanti del villaggio!`n");
            }
            output("$colore Impugni quindi ben stretta la tua `#Spada Lucente $colore e ti guardi intorno! Non noti nulla di rilevante a parte mucchi di ossa sparsi ovunque sul terreno...`nContinuando ad esplorare giungi in fondo alla caverna, ma anche qui non ");
            output("vedi nulla di particolarmente interessante. Ti volti per riguadagnare l'uscita e ...`n`n");
         }else if($_GET['cosa']=="torcia") {
         			$colore = "`3" ;
               		output("$colore Impugnando saldamente la `(torcia $colore prosegui lungo il macabro cunicolo... oltre alle centinaia di ossa non noti nulla di particolare!");
               		output(" Giungi alla fine della caverna ma nulla di interessante attira la tua attenzione... quindi decidi di ritornare nella foresta. Ti volti verso l'uscita e ...`n`n");
          		}else{
          			output("$colore Esplori terrorizzato la caverna! Senti un ghigno... ti volti e ...`n`n");
          		}
	     output("$colore ... hai davanti a te una orrenda creatura! Una enorme corpo quasi sferico levita nell'aria come una grande testa con un solo occhio al ");
	     output("centro, una grande bocca con centinaia di denti aguzzi e al posto dei capelli decine di tentacoli che terminano ciascuno con un occhio! Ti ritrovi di fronte ad un `(Beholder $colore. `n");
	     output("Ricordi vagamente di aver sentito parlare di queste creature! Sembra che si tratti di aberrazioni, creature magiche che usano i loro occhi per lanciare incantesimi irresistibili e che non è possibile combatterli ");
	     output("con poteri speciali! L'unica maniera per ucciderli sembra sia essere quella di possedere qualche oggetto magico in grado di annebbiare la loro vista, riducendo in questo modo la loro resistenza agli incantesimi!`n ");
	     output("Purtroppo per te la strada verso l'uscita della caverna è sbarrata dal `(Beholder $colore e l'unico modo per guadagnare la salvezza è quello di attaccare e sconfiggere questo mostro!`n`n");
	     creaBeholder_ES($_GET['cosa']);
	     continua_ES();
	     addnav("Combatti", "forest.php?op=combatti");
}

/* *********** SCAPPA ************** */

else if($_GET['op']=="scappa") {
     output("`xScappi via terrorizzato!`n");
     if(e_rand(1,10)==1) {
          output("`2Lo spavento ricevuto ti ha talmente caricato di adrenalina che senti il bisogno di sfogare tutta la tua rabbia guerriera su qualcuno! `nGuadagni `&1`2 turno di esplorazioni nella foresta e hai acquisito maggior forza durante i tuoi prossimi combattimenti");
          debuglog("fugge dalla Caverna Oscura guadagnando 1 turno e un buff");
          $session['user']['turns']+=1;
          $session['bufflist'][278] = array("name"=>"`5Adrenalina in corpo",
                                            "rounds"=>10,
                                            "wearoff"=>"Ti sei finalmente sfogato",
                                            "atkmod"=>1.5,
                                            "roundmsg"=>"Dai sfogo allo spavento subìto!",
                                            "activate"=>"offense");
     }else{
          output("`2Lo spavento ricevuto ti ha prosciugato molte delle tue energie: perdi  `&2`2 turni di esplorazione nella foresta e parte delle tue forze per i prossimi combattimenti.");
          debuglog("fugge dalla Caverna Oscura perdendo 2 turn e con un buff negativo");
          $session['user']['turns']-=2;
          $session['bufflist'][278] = array("name"=>"`5Spavento",
                                            "rounds"=>10,
                                            "wearoff"=>"ti sei finalmente ripreso dallo spavento",
                                            "atkmod"=>0.8,
                                            "roundmsg"=>"La paura ti impedisce di usare tutta la tua forza!",
                                            "activate"=>"offense");
     }
}

/* *********** ESCI CON LA SPADA O IL TESCHIO ************** */

else if($_GET['op']=="prendiescappa") {
     if($_GET['cosa']=="teschio") {
     output("`2Scappare non è sinonimo di vigliaccheria, ma di saggezza! Inoltre hai preso questo bel `0teschio`2. ");
     output("Questo bello e inutile `0teschio`2... starà proprio bene nella tua camera, pensi`n");
          if(e_rand(1,2)==1) {
               output("Mentre lo osservi attentamente rigirandolo da ogni parte, vedi illuminarsi di una luce `4rossa`2 i suoi occhi e ricevi un potente incantesimo che ti consente di guadagnare `&1`2 punto di utilizzo nelle `4Arti Oscure`2!");
               debuglog("fugge dalla Caverna Oscura e guadagna un punto in Arti Oscure");
               $session['user']['darkarts']+=3;
               $session['user']['darkartuses']+=1;
          }
     }
     if($_GET['cosa']=="spada") {
     output("`2Scappi via terrorizzato, ma hai comunque trovato una bella arma nella `xCaverna Oscura`2`n");
          if(e_rand(1,3)==2) {
          output("Agiti la `#Spada Lucente`2 felice, la provi sulle fronde di un cespuglio e scopri che oltre ad eseere leggerissima è molto affilata. Sei quasi cert".($session[user][sex]?"a":"o")." che gli abitanti del Villaggio ti invidieranno per quanto hai appena trovato!`n");
          debuglog("scappa dalla Caverna Oscura con la Spada Lucente");
          prendiSpada_ES();
        }
        else {
        	$identarma=array();
			$ident_arma = identifica_arma();
			$articoloarma = $ident_arma['articolo'];
    		$pugni = $ident_arma['pugni'];
    		if ($pugni) {
    			output("Purtroppo per te, la spada perde tutta la sua lucentezza e si rivela per quello che è realmente : una semplice ed inutile spada di legno! Ti ritrovi quindi completamente disarmato a dover affrontare i tuoi nemici con solo $articoloarma `#{$session['user']['weapon']}`2!`n");
			}else{
        		output("Purtroppo per te, la spada perde tutta la sua lucentezza e si rivela per quello che è realmente : una semplice ed inutile spada di legno! Ma, per tua fortuna, non avevi ancora buttato $articoloarma `#{$session['user']['weapon']}`2!`n");
        	}
        }
     }
}

/* *********** COMBATTI! ************** */

else if($_GET['op']=="combatti") {
     $battle=true;
     $badguy= createarray($session['user']['badguy']);
     //fightnav(!$badguy['resisteincanti'],false);
     fightnav(!$badguy['resisteincanti'],false);
     if($badguy['resisteincanti']) {
          $session['user']['buffbackup']=serialize($session['bufflist']);
        $session[bufflist]=array();
     }
     continua_ES();
     require("battle.php");
}
else if($_GET['op']=="fight") {
     $battle=true;
     $badguy= createarray($session['user']['badguy']);
     continua_ES();
     require("battle.php");
          if($victory) {
               output("`n`n`3Hai sconfitto il `(Beholder`3! Questo è un gran giorno per la tua vita di guerrier".($session['user']['sex']?"a":"o")."! L'orribile creatura giace ai tuoi piedi agonizzante e dalla sua enorme testa squarciata escono ");
               output("`^monete d'oro`3 e alcune `&gemme`3. `nRapidamente ti chini a raccoglierle e inizi a valutare il tesoro trovato che ammonta a `&".$badguy['creaturegems']." gemme `3e `^".$badguy['creaturegold']." monete d'oro`3!`n");
               output("`3Sconfiggere un `(Beholder`3 non è impresa di poco conto, guadagni quindi anche `#1000`3 punti esperienza ed è sicuramente cresciuta la tua fama di guerrier".($session['user']['sex']?"a":"o")." invincibile.");
               $session[user][fama3mesi] += $badguy['fama'];
               $session['user']['gems']+=$badguy['creaturegems'];
               $session['user']['gold']+=$badguy['creaturegold'];
               $session['user']['experience']+=1000;
               if(gotpower_ES() && e_rand(1,5)!=3) {
               		output("`nLe sorprese non sono finite, trovi infatti anche una borsa in pelle contenente `^10000 pezzi d'oro`3 ... ma... la tua `#Spada lucente`x perde tutto il suo potere magico e si trasforma in una innocua spada di legno! Forse era lo stesso `(Beholder`x la fonte della sua aura magica o forse con questo combattimento la magia si è esaurita. ");
               		output("Il risultato pratico è che purtroppo ti ritrovi completamente disarmato, ti converrà, una volta tornato al villaggio, passare a salutare `!MightyE`x e cercare di concludere qualche affare.");
               		$session[user][weapon] = "Pugni";
               		$session[user][attack]-=$session[user][weapondmg];
               		$session[user][weapondmg] = 0;
               		$session['user']['usura_arma']=999;
               		$session[user][weaponvalue] = 0;
               		$session['user']['gold']+=10000;
               }
               $session['user']['specialinc']="";
               addnav("Torna nella Foresta", "forest.php");
               debuglog("batte il Beholder e guadagna ".$badguy['creaturegems']." gemme e ".$badguy['creaturegold']." oro, ma rimane senz'arma");
               addnews("`@".$session['user']['name']."`@ ha sconfitto `(".$badguy['creaturename']."`@ nella `xCaverna Oscura");
          }
          else if ($defeat) {
               output("`n`n`xSei stat".($session['user']['sex']?"a":"o")." uccis".($session['user']['sex']?"a":"o")." dal `(Beholder`x! Ora conosci appieno tutta la sua magica potenza e per questo motivo non perdi esperienza anche se perdi tutto il tuo `^oro".(($session['user']['gems']>=2)?"`x e `&2`x delle tue `&gemme`x":"")."!`n");
               debuglog("è stato ucciso dal Beholder e ha perso ".$session['user']['gold']." oro".(($session['user']['gems']>=2)?" e 2 gemme":""));
               $session['user']['gems']-=($session['user']['gems']>=2)? 2 : 0;
               $session['user']['gold']=0;
               $session['user']['hitpoints']=0;
               $session['user']['alive']=false;
               $session['user']['turns']=0;
               $session['user']['specialinc']="";
               addnav("Notizie Giornaliere","news.php");
               addnews("`@ " . $session['user']['name'] . "`@ è stat".($session['user']['sex']?"a":"o")." annientat".($session['user']['sex']?"a":"o")." da `($badguy[creaturename] `@ nella `xCaverna Oscura");
          }
          else {
          fightnav(!$badguy['resisteincanti'],false);
               if($badguy['resisteincanti']) {
                    //$session['user']['buffbackup']=serialize($session['bufflist']);
                   //$session[bufflist]=array();
               }
          }
}


else {
     //ho diemnticato nulla?? :)
     output("Non sai come... ti ritrovi nella foresta!");
}

?>