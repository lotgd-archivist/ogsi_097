<?php
require_once("common.php");
require_once("common2.php");
$config = unserialize($session['user']['donationconfig']);
$owner1=$session['user']['acctid'];
$sql="SELECT * FROM pietre WHERE owner=$owner1";
$result = db_query($sql);
$pot = db_fetch_assoc($result);
if (count($result) != 0) {
    $flagstone=$pot['pietra'];
    if ($flagstone == 1 ) {
		$pokerstone = 1; 
	}	   
}    
if ($config['healer'] || $session['user']['acctid']==getsetting("hasegg",0)) $golinda = 1;
if ($config['healer'] || $session['user']['acctid']==getsetting("hasblackegg",0)) $blackgolinda = 1;

if ($golinda OR $blackgolinda) {
    page_header("Capanna di Golinda");
    $session['user']['locazione'] = 137;
    output("`6`b`cCapanna di Golinda`c`b`n");
} else {	
	page_header("Capanna del Guaritore");
	$session['user']['locazione'] = 138;
	output("`#`b`cCapanna del Guaritore`c`b`n"); 	
}
//Luke sconto generale per donazione
$tempo_donazione=time()-getsetting("ultima_donazione",0);
if ($tempo_donazione<=432000){
    $donazione_bonus=0.8;
    output("`b`c`VGli dei sono buoni per una donazione ricevuta le cure costano il 20% in meno!`0`c`b`n`n");
}else{
    $donazione_bonus=1;
}
//fine luke
//luke sconto per golinda card con punti donazione
if (donazioni('sconto_cura')==true){
    $sql = "SELECT usi FROM donazioni WHERE nome = 'sconto_cura' AND idplayer='".$session['user']['acctid']."'";
    $result = db_query($sql) or die(db_error(LINK));
    $row = db_fetch_assoc($result);
    if($row['usi']>0){
        $golinda = 1;
        $golindacard = 1;
    }
}
//fine luke
$loglev = log($session['user']['level']);
$cost = (($loglev * ($session['user']['maxhitpoints']-$session['user']['hitpoints'])) + ($loglev*10))*$donazione_bonus;
$potioncost = (($loglev * ($session['user']['maxhitpoints'])) + ($loglev*10))*$donazione_bonus;
if ($potioncost == 0) $potioncost=$session['user']['dragonkills']*5;
if ($golinda) $cost *= .5;
if ($blackgolinda) $cost *= 1.2;
$cost = round($cost,0);

if ($_GET['op'] == "heal"){
    output("Il guaritore afferra velocemente il tuo oro e ti getta una fiala contenente uno strano liquido.`n");
    output("Lo posizioni delicatamente nella cintura.");
    $session['user']['gold']-=round(100*$potioncost/100,0);
    $session['user']['potion']+=1;
    addnav("Continua","healer.php");
}else{
    if ($_GET['op']==""){
        checkday();
        if ($golinda) {
            if ($golindacard){
                output("`3Una piccola e bella brunetta ti guarda mentre entri \"`#`iAhh.. Tu devi essere ".$session['user']['name'].".`#
                        Io sono `6Golinda`3 la guaritrice, mi è stato detto di aspettarti, tu hai la mia Golinda Card. Entra.. entra!`i`3\" esclama.`n`nTi fai strada verso l'interno della capanna.`n`n");
            }else{
                output("`3Una piccola e bella brunetta ti guarda mentre entri \"`#`iAhh.. Tu devi essere ".$session['user']['name'].".`#
                Io sono `6Golinda`3 la guaritrice, mi è stato detto di aspettarti. Entra.. entra!`i`3\" esclama.`n`nTi fai strada verso l'interno della capanna.`n`n");
            }
        } else {
            output("`3Entri in una piccola capanna di frasche piena di fumo. L'aroma acre e pungente ti prende la gola facendoti fa tossire, a attirando l'attenzione
        		di un vecchio macilento che immobile come una pietra ti era sembrato una parte dell'arredamento di quella misera capanna. Strano che ti fosse sfuggito 
        		visto che sei sempre particolarmente attent".($session['user']['sex']?"a":"o")." e all'erta visto che il pericolo si nasconde ovunque.....`n`n");
        }
        if ($session['user']['hitpoints'] < $session['user']['maxhitpoints']){
            if ($golinda) {
                output("`3\"`#`iOra... Vediamo un po'. Hmmm. Hmmm. Sembra che ti abbiano un po' ammaccat".($session[user][sex]?"a":"o").".`i`3\"`n`n\"`5`iUh, si.
            Credo. Quanto mi costerà?`i`3\" domandi con un'aria da ebete. \"`5`iSolitamente non mi faccio così male, sai.
            `i`3\"`n`n\"`#`iLo so. Lo so. Nessuno di voi lo fa `^mai`#. Comunque, posso rimetterti in sella per `^`b$cost`b`#
            pezzi d'oro. `nPosso anche darti delle dosi ridotte per un prezzo minore se non ti puoi permettere una pozione
            intera,`i`3\" dice `6Golinda`3, sorridendo.");
            } else {
                output("\"`#`iVedo, io ti.  Prima che tu vedi me, io penso, hmm? `i`3\" dice il vecchio sciamano.  \" `#`iConosco, io ti,
            mie cure tu cerchi. Curare voglio io, ma solo se vuoi tu e pagare puoi tu.`i`3\"`n`n\"`5`iUh, um.  Quanto?`i`3\" chiedi, pronto a
            liberarti di quel vecchio coso puzzolente. `n`nL'anziano essere ti picchia nelle costole con un bastone tarlato.
            \"`#`iPer te... `^`b$cost`b`# pezzi d'oro per una guarigione completa!!`i`3\" dice mentre si piega ed estrae una
            fialetta di argilla da dietro una pila di teschi che si trova in un angolo. La vista della cosa che si piega
            in avanti per prendere la fiala ti fa quasi abbastanza danni mentali da farti avere bisogno di una pozione più
            potente.  \"`#`iHo anche alcune, ehm... pozioni 'in saldo' disponibili,`i`3\" dice facendo cenni in direzione di
            una pila di fiale crepate e polverose.  \"`#`iCureranno una certa percentuale delle tue ferite`i.`3\"");
            }
            addnav("Pozioni");
            addnav("`^Guarigione Completa`0","healer.php?op=buy&pct=100");
            for ($i=90;$i>0;$i-=10){
                addnav("$i% - ".round($cost*$i/100,0)."gp","healer.php?op=buy&pct=$i");
            }
            addnav("`bIndietro`b");
            addnav("Torna alla Foresta", "forest.php");
            addnav("Torna al Villaggio","village.php");
        }else if($session['user']['hitpoints'] == $session['user']['maxhitpoints']){
            if ($golinda) {
                output("`3`6Golinda`3 ti guarda attentamente.  \"`#`iBeh, hai un calletto lì, ma a parte quello sembri in ottima salute! `n
            `^Io`# penso che tu sia venut".($session[user][sex]?"a":"o")." qui solo perché ti senti sol".($session[user][sex]?"a":"o").",`i`3\" ridacchia.`n`nComprendendo che ha ragione, e che
            la stai trattenendo dal curare altri pazienti, torni a vagabondare nella foresta.");
            } else {
                output("`#`iBisogno di una pozione, tu non hai.  Perché tu disturbi me, domando io.`i`3\" dice lo strano essere. `n`n
            L'aroma disgustoso del suo alito ti fa desiderare di non essere mai entrat".($session['user']['sex']?"a":"o")." in questa capanna. `nPensi che forse è il caso di cambiare aria e tornare il più velocemente possibile nella foresta.");
            }
            forest(true);
        }else{
            if ($golinda) {
                output("`3`6Golinda`3 ti guarda attentamente. \"`#`iCielo, cielo! Non hai neppure un calletto da farti curare! Sei un
            perfetto esemplare di " . ($session['user']['sex'] == 1 ? "femminilità" : "mascolinità") . "!  Torna quando sarai
            stat".($session[user][sex]?"a":"o")." ferit".($session[user][sex]?"a":"o").", per favore,`i`3\" dice, voltandosi per riprendere a miscelare pozioni.`n`n\"`#`iCerto, certo`i`3\"borbotti,
            incredibilmente imbarazzat".($session[user][sex]?"a":"o").", mentre torni nella foresta.");
            } else {
                output("`3La vecchia creatura grugnisce guardando verso di te. \"`#`iBisogno di una pozione, tu non hai.  Perché tu
            disturbi me, domando io.`i`3\" dice lo strano essere. `n`nL'aroma disgustoso del suo alito ti fa desiderare di non essere mai entrat".($session['user']['sex']?"a":"o")."
            in questa capanna. `nPensi che forse è un'ottima idea uscire il più velocemente possibile all'aria aperta.");
            }
            forest(true);
        }
        if ($session['user']['gold'] >= round(100*$potioncost/100,0) and $session['user']['potion'] < 5 AND $session['user']['level'] > 1){
            output("`n`n`2Pozioni Speciali!`n");
            output("`3Le Pozioni Guaritrici Portatili costano `^".round(100*$potioncost/100,0)."`3 pezzi d'oro. Puoi portare al massimo `45`3 pozioni per guarirti.`n`n");
            addnav("Speciale");
            addnav("Pozione Guaritrice","healer.php?op=heal");
        }
    }else{
        $newcost=round($_GET['pct']*$cost/100,0);
        if ($session['user']['gold']>=$newcost){
            $session['user']['gold']-=$newcost;
            debuglog("speso $newcost oro per guarirsi");
            $diff = round(($session['user']['maxhitpoints']-$session['user']['hitpoints'])*$_GET['pct']/100,0);
            if (($pokerstone) AND (!$golindacard))  { 
	            $sfortuna = e_rand(0,1); 
	        }   
            if ($golinda) {
	            if ($sfortuna)  {
		            output("`3Inizi a bere la pozione. Mentre ti scende nella gola, però, senti un sapore orribile e ti viene il voltastomaco.`n
		            	Purtroppo la sfortuna della {$pietre[$flagstone]}`3 della `&Fonte di Aris`3 si è accanita contro di te, la pozione di guarigione doveva essere avariata e non ha svolto efficacemente il suo compito. 
            			Barcollando esci dalla capanna e torni nella foresta, sei comunque stat".($session[user][sex]?"a":"o")." guarit".($session[user][sex]?"a":"o").", ma solo parzialmente e non come speravi. ");
            		$diff=round($diff*0.9);
		        }else{   
                	output("`3Aspettandoti un'orrenda mistura, inizi a bere la pozione. Mentre ti scende nella gola, però, senti
            			un gusto di cinnamomo, miele e frutta. Senti il calore spandersi nel tuo corpo mentre i tuoi muscoli si rinsaldano.
            			Sentendoti molto meglio, dai a `6Golinda`3 le monete che le devi e torni nella foresta.");
            	}
            } else {
	             if ($sfortuna)  {
		            output("`3Con una smorfia, inizi a bere la pozione. Mentre ti scende nella gola, però, senti un sapore orribile e ti viene il voltastomaco.
		            	Purtroppo la sfortuna {$pietre[$flagstone]}`3 della `&Fonte di Aris`3 si è accanita contro di te, la pozione di guarigione doveva essere avariata e non ha svolto efficacemente il suo compito. 
            			Barcollando esci dalla capanna e torni nella foresta, sei comunque stat".($session[user][sex]?"a":"o")." guarit".($session[user][sex]?"a":"o").", ma solo parzialmente e non come speravi. ");
            		$diff=round($diff*0.9);
		        }else{ 
                	output("`3Con una smorfia, ti scoli la pozione che la creatura ti consegna, e nonostante l'orribile sapore senti un
            			calore che ti si diffonde nelle vene mentre i tuoi muscoli si ristorano. `nBarcollando incert".($session[user][sex]?"a":"o")." sulle tue gambe tremolanti, consegni quanto pattuito come pagamento
           				e sei pront".($session[user][sex]?"a":"o")." ad andartene.");
           		}	
            }
            if ($diff < 1 )  {
	            $diff = 1 ;
	        }   
            $session['user']['hitpoints'] += $diff;
            if ($diff == 1 )  {
	        	output("`n`n`3Sei stat".($session[user][sex]?"a":"o")." curat".($session[user][sex]?"a":"o")." per `^$diff`3 punto!");
	        } else { 	   
            	output("`n`n`3Sei stat".($session[user][sex]?"a":"o")." curat".($session[user][sex]?"a":"o")." per `^$diff`3 punti!");
            }
            forest(true);
        }else{
            if ($golinda) {
                output("`3\"`#Tsk, tsk!`3\" mormora `6Golinda`3.  \"`#`iForse dovresti fare un salto in banca e tornare quando
            avrai `b`\$$newcost`#`b pezzi d'oro?`i`3\" ti dice sorridendo.`n`nStai lì sentendoti stupid".($session[user][sex]?"a":"o")." per averle fatto perdere
            tempo.`n`n\"`#`iO forse sarebbe il caso di prendere una pozione meno costosa!`i`3\" suggerisce lei gentilmente.");
            } else {
                output("`3La vecchia creatura ti fulmina con uno sguardo crudele. I tuoi riflessi fulminei ti permettono di
            schivare il colpo del suo bastone tarlato. Forse dovresti procurarti un po' più denaro prima di tentare di fare
            affari con qualcuno.`n`nTi ricordi che la creatura aveva chiesto `b`\$$newcost`3`b pezzi d'oro.");
            }
            addnav("Pozioni");
            addnav("`^Guarigione Completa`0","healer.php?op=buy&pct=100");
            for ($i=90;$i>0;$i-=10){
                addnav("$i% - ".round($cost*$i/100,0)."gp","healer.php?op=buy&pct=$i");
            }
            addnav("`bTorna`b");
            addnav("Torna alla Foresta","forest.php");
            addnav("Torna al Villaggio","village.php");
        }
    }
}
page_footer();
?>