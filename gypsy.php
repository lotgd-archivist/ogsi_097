<?php
require_once "common.php";
require_once "common2.php";
addcommentary();
$session['user']['locazione'] = 136;
output("`c`b`%La vecchia Gitana`0`b`c`n");
if ($session['zingara']== "") {$session['zingara']=1;}
//echo "session zingara: ".$session['zingara'];
$titolo = ($session['user']['sex']?"Monsieur":"Madame");
$costolivello = $session['user']['level']*50;
$costodraghi = $session['user']['dragonkills']*5;
$costoreinc = $session['user']['reincarna']*10;
//$fascia = calcolafascino();
//$costogems = 2000 + intval(2000 / ($fascia + 1 )) + $cost;
$costogems = 2200 + $costolivello + $costodraghi + $costoreinc ;
if ($_GET['op']==""){
	output ("`%Entri in uno dei carrozzoni che ti hanno consigliato di visitare visto il tuo interesse per le `&gemme`% preziose e ti ritrovi faccia a faccia con una vecchia gitana, che seduta a un tavolino osserva delle carte raffiguranti i tarocchi con aria assorta e concentrata. 
         		La osservi per un minuto mentre l'anziana donna, con le dita tutte inanellate, gira lentamente una carta dopo l'altra ammiccando silenziosamente come se avesse trovato conferma a quanto pensava."); 
    output ("`5Accomodati pure, non essere timoros".($session['user']['sex']?"a":"o")." .... come posso esserti utile ? ");
    addnav("M?Parla con i Morti","gypsy.php?op=pay");
    addnav("C?Leggere le Carte","gypsy.php?op=futuro");
    addnav("G?Compra Gemme","gypsy.php?op=compra");
    addnav("L?Lascia Perdere","village.php");
}else if ($_GET['op']=="pay"){
//    if ($session['user']['gold']>=$cost){
//        $session['user']['gold']-=$cost;
    if ($session['user']['gold']>=$costolivello){
        $session['user']['gold']-=$costolivello;
        if ($_GET['was']=="flirt"){
             debuglog("paga ".$costolivello."per parlare con i morti");
             redirect("gypsy.php?op=flirt2");
        } else {
            debuglog("paga ".$costolivello."per flirtare con l'amante al cimitero");
            redirect("gypsy.php?op=talk");
        }
    }else{
        page_header("Tenda degli zingari");
        addnav("Torna all'Accampamento ","gypsycamp.php");
        output("`5Offri all'anziano zingaro i tuoi `^".$session['user']['gold']."`5 pezzi d'oro per una ge-nu-ina veg-genza,
        ma lui ti informa che i morti possono anche essere morti, ma non sono economici e ti invita ad uscire dal suo tendone.");
        }
}else if ($_GET['op']=="flirt2"){
    page_header("In trance profonda, parli con le ombre");
    output("`5Ipnotizzato dalla sfera di cristallo della gitana senti la tua mente sprofondare nel nulla e cadi in un profondo stato di trance. Ad un tratto ti ritrovi nel regno di `\$Ramius`5 : la terra delle Ombre. Ovunque intorno a te le anime vaganti e sofferenti dei defunti camminano dirigendosi verso la bocca di Ade e il sonno eterno. Ti rendi conto che sei in grado di comunicare con queste povere anime dannate e inizi a guardarti intorno fino a quando incontri lo spirito vagante di tu".($session['user']['sex']?"o marito":"a moglie")." e, abbracciando quell'essenza incorporea, flirti con ".($session['user']['sex']?"lui":"lei").", per alleviare almeno in parte il tormento che ".($session['user']['sex']?"lo":"la")." affligge. ");
    output("`n`^Guadagni un punto di fascino.");
    $session['bufflist']['lover']= array("name"=>"`!Protezione dell'amore","rounds"=>70,"wearoff"=>"`!Ti manca il tuo grande amore!`0","defmod"=>1.2,"roundmsg"=>"Il tuo grande amore ti fa pensare alla tua sicurezza!","activate"=>"defense");

    $session['user']['charm']++;
    $session['user']['seenlover']=1;
    addnav("Esci dalla Trance","gypsycamp.php");
}elseif ($_GET['op']=="talk"){
    page_header("In trance profonda, parli con le ombre");
    output("`5Ipnotizzato dalla sfera di cristallo della gitana senti la tua mente sprofondare nel nulla e cadi in un profondo stato di trance. Ad un tratto ti ritrovi nel regno di `\$Ramius`5 : la terra delle Ombre. Ovunque intorno a te le anime vaganti e sofferenti dei defunti camminano dirigendosi verso la bocca di Ade e il sonno eterno. Ti rendi conto che sei in grado di comunicare con queste povere anime dannate e cerchi di alleviare in questo modo il loro tormento.`n");
    viewcommentary("shade","Parla coi Morti",25,10,"consola i defunti");
    addnav("Esci dalla Trance","gypsycamp.php");
}elseif ($_GET['op']=="compra"){
    page_header("La Venditrice di Gemme");
    output("`c`b`%La Venditrice di Gemme`0`b`c`n");
    if ($session['user']['level']<15){
          if ($_POST['money']==""){
          	     	
                output("\"`%`iHai intenzione di acquistare delle `&gemme`%, non è vero?`i`%\" ti chiede la gitana. \"`%`iBeh, ti darò `&1 gemma`% in cambio di soli `^$costogems pezzi d'oro`%!`%\"");
                output("`n`n`%Quanti pezzi d'oro sei in grado di pagare?`i");
                output("`n`5(Sappi che puoi inserire anche una cifra non perfetta, la vecchia gitana è onesta e dopo aver effettuato lei il calcolo ti darà il resto)");
                output("`n(Digita 0 se vuoi acquistare tutte le `&gemme`5 possibili con il denaro che hai in tasca)");
                output("<form action='gypsy.php?op=compra' method='POST'><input name='money' value='0'><input type='submit' class='button' value='Dai'>`n",true);
                addnav("","gypsy.php?op=compra");
            }else{
              $money = abs((int)$_POST['money']);
                if ($money>$session['user']['gold']){
                  output("`5La vecchia gitana ti guarda arcigna con un'occhiataccia.  \"`%`iNon hai tutto quest'`^oro`%, torna da me quando te ne sarai procurato dell'altro!`i`5\" sibila arrabbiata.");
                }else{

                    if ($money % $costogems == 0 AND $money == 0){
                        $money = $session['user']['gold'];
                    }else{
                    }
                    output("`5Metti `^$money pezzi d'oro`5 sul tavolino.`n");
                    $gemsbuy = intval($money / $costogems);
                    $spesa = $costogems * $gemsbuy;
                    $resto = $money - $spesa;
                    $money-=$resto;
                    if ($money > 0){
                        output("`5La vecchia gitana, conoscendo i tuoi problemi di base con la matematica effettua il conto ti restituisce `^$resto pezzi d'oro`5.`n");
                    }
                    else {
                        output("`n`5La vecchia zingara guarda i `^$resto pezzi d'oro`5 sul tavolino e te li restituisce ridendo a crepapelle e dicendoti che sei più zingaro di lei.");
                    }
                    if ($money>0) {
                        if ($gemsbuy>1) $singplur=1;
                        output("`5La Gitana prende i `^$money pezzi d'oro`5 e ti da in cambio `&$gemsbuy gemm".($singplur?"e":"a")."`5.`n");
                        output("`n`5Felice dell'acquisto fatto, ti allontani dalla tenda della anziana gitana.");
                        $session['user']['gold']-=$money;
                        $session['user']['gems']+=$gemsbuy;
                        debuglog("ha dato $money pezzi d'oro alla gitana in cambio di $gemsbuy gemme");
                    }
                }
            }
    }else{
    output("`n`4`bNon credi sarebbe ora di affrontare il drago ?`b");
    }
	addnav("Campo degli Zingari","gypsycamp.php");
}elseif ($_GET['op']=="futuro"){
    if ($session['user']['gems']>=1){ // Luke
        $session['user']['gems']-=1;
        redirect("gypsy.php?op=carte");
    }else{
        page_header("Tenda degli Zingari");
        addnav("Torna all?accampamento","gypsycamp.php");
        output("`5Non hai gemme da offrire alla vecchia zingara, che effettuando al tuo indirizzo strani gesti e pronunciando parole a te incomprensibili, incomincia a ridere.");
        }
}elseif ($_GET['op']=="gemme"){
    page_header("Hai comprato una gemma");
    output("`5Guardando l'`^oro`5 che la vecchia si intasca e rimirando la tua `&gemma`5, non sei più così convinto di aver fatto un affare`n");
    output("`5Scuoti la testa come per scacciare questo pensiero e ti allontani felice con la `&gemma`5 in mano.`n");

    debuglog("acquista 1 gemma dagli zingari");
    $session['user']['gold'] += 1;
    addnav("Torna all?accampamento","gypsycamp.php");

}elseif ($_GET['op']=="carte"){
	output("`c`b`%La Cartomante`0`b`c`n");
    page_header("La Cartomante");
    output("`%Ti infili all'interno del tendone viola, e la giovane gitana entra subito dopo di te, accomodandosi dietro un tavolino con un mazzo di carte e una candela accesa che illumina fiocamente la stanza. Avverti nell'aria un gradevole profumo di incenso, probabilmente proveniente da un bruciatore posto in un angolo e ti  
    		prendi posto a tua volta di fronte alla cartomante che, nel frattempo, ha iniziato a mescolare le sue carte con molta concentrazione. `nAlla fine, estrae una carta dal mazzo e te la mostra, sorridendo.  ");
    output("Osservi con curiosità la carta pescata ");
	switch(e_rand(1,22)){
    	case 1:
    		$carta = "`@Il Bagatto" ;
    		$cartamsg = "`0`iIl destino premia la tua intraprendenza accrescendo di molto la tua esperienza!`i";
   			$session['user']['experience']+=intval($session['user']['experience']*0.05);    
        break;
      	case 2:
      		$carta = "`@Il Bagatto Rovesciato" ;
    		$cartamsg = "`0`iL'indolenza quest'oggi ti attanaglia, perdi esperienza da recuperare in battaglia!`i";
   			$session['user']['experience']-=intval($session['user']['experience']*0.05); 
        break;
      	case 3:
        	$carta = "`@L'Imperatrice" ;
    		$cartamsg = "`0`iProtezione da Costei viene fornita, sarai al riparo da ogni ferita!`i";
   			$session['user']['defence']++;
        break;
      	case 4:
        	$carta = "`@L'Imperatrice Rovesciata" ;
    		$cartamsg = "`0`iLa Sovrana si presenta a testa in giù, riducendo le tue difese sempre più!`i";
   			if ($session['user']['defence']>=10){
   				$session['user']['defence']--; 
   			}else{
   				$session['bufflist']['debolezza'] = array(
	            "name"=>"`4Debolezza",
	            "rounds"=>100,
	            "wearoff"=>"Il senso di debolezza ti è passato e torni a difenderti come prima.",
	            "atkmod"=>1.0,
	            "defmod"=>.75,
	            "roundmsg"=>"Una grande debolezza ti assale e fai fatica a difenderti dai colpi del tuo avversario.",
	            "activate"=>"roundstart");
	 		}        
   		break;
        case 5:
        	$carta = "`@L'Imperatore" ;
    		$cartamsg = "`0`iIl Sire è uomo che elargisce favori, oggi riempie le tue tasche di ori!`i";
   			$session['user']['gold']+=1000;
        break;
      	case 6:
        	$carta = "`@L'Imperatore Rovesciato" ;
    		$cartamsg = "`0`iIl popolo l'Imperatore ha rovesciato, in tasca non hai più un soldo bucato!`i";
   			$session['user']['gold']-=1000; 
        break;
        case 7:
        	$carta = "`@Gli Innamorati" ;
    		$cartamsg = "`0`iRadioso oggi ti sorride Amore, conquisti fama di gran seduttore!`i";
   			$session['user']['charm']+=10;
        break;
      	case 8:
        	$carta = "`@Gli Innamorati Rovesciati" ;
    		$cartamsg = "`0`iCapriccioso Amore il suo favor ti nega facendoti assomigliare al rospo di una strega!`i";
   			$session['user']['charm']-=10; 
        break;
        case 9:
        	$carta = "`@La Morte" ;
    		$cartamsg = "`0`iSi dice che da lei non v'è ritorno, ma a te oggi dona l'alba di un nuovo giorno!`i";
   			$session['user']['lasthit']="0000-00-00 01:00";
   			$session['user']['restorepage']="village.php";
        break;
      	case 10:
        	$carta = "`@La Morte Rovesciata" ;
    		$cartamsg = "`0`iNotte eterna cala per te su questo giorno, raggiungerai Ramius senza speranza di ritorno!`i";
   			$session['user']['alive']=false;
        break;
        case 11:
        	$carta = "`@Il Carro" ;
    		$cartamsg = "`0`iTrionfi nelle imprese grazie al tuo coraggio, come eroe ti acclama l'intero villaggio!`i";
   			$session[user][fama3mesi]+=100;
        break;
      	case 12:
        	$carta = "`@Il Carro Rovesciato" ;
    		$cartamsg = "`0`iIl Carro hai rovesciato per un azzardo, la tua fama è derisa da ogni bardo!`i";
   			$session[user][fama3mesi]-=100; 
        break;
        case 13:
        	$carta = "`@La Giustizia" ;
    		$cartamsg = "`0`iDinanzi a lei non serve un indovino, rettitudine darà al tuo cammino!`i";
   			$session[user][evil]-=10; 
        break;
      	case 14:
        	$carta = "`@La Giustizia Rovesciata" ;
    		$cartamsg = "`0`iStrano il Destino, davvero buffo, ti renderà cattiv".($session['user']['sex']?"a":"o")." agli occhi dello sceriffo!`i";
   			$session[user][evil]+=10; 
        break;
        case 15:
        	$carta = "`@La Fortuna" ;
    		$cartamsg = "`0`iProdiga è per te la Dea Bendata, sorride arricchendo questa giornata!`i";
   			$session[user][gems]+=2; 
        break;
      	case 16:
        	$carta = "`@La Fortuna Rovesciata" ;
    		$cartamsg = "`0`iAhimè quest'oggi la Sorte è avversa, qualcuna delle tue gemme è andata persa!`i";
   			$session[user][gems]-=2; 
        break;
        case 17:
        	$carta = "`@La Forza" ;
    		$cartamsg = "`0`iQuesto Arcano infonde nuovo vigore, abbatterai i nemici con grande fragore!`i";
   			$session[user][attack]+=1; 
        break;
      	case 18:
        	$carta = "`@La Forza Rovesciata" ;
    		$cartamsg = "`0`iSe capovolta, la Forza diventa debolezza, ogni colpo al nemico sarà lieve carezza!`i";
   			if ($session['user']['attack']>=10){
   				$session['user']['attack']--; 
   			}else{
   				$session['bufflist']['spossatezza'] = array(
	            "name"=>"`4Spossatezza",
	            "rounds"=>100,
	            "wearoff"=>"Il senso di spossatezza ti è passato e torni ad attaccare come prima.",
	            "atkmod"=>.075,
	            "defmod"=>1.00,
	            "roundmsg"=>"Una grande spossatezza ti assale e fai fatica ad attaccare il tuo avversario.",
	            "activate"=>"roundstart");
	 		}
        break;
        case 19:
        	$carta = "`@La Papessa" ;
    		$cartamsg = "`0`iDonna fedele e dedita alla conoscenza, nella tua abilità dona più esperienza!`i";
   			$skillnames = array(1 => "`\$Arti Oscure", "`%Poteri Mistici", "`^Furto", "`3Militare","`\$Seduzione","`^Tattica","`@Pelle di Roccia","`#Retorica","`%Muscoli","`3Natura","`&Clima","`^Elementalista","`6Rabbia Barbara","`5Canzoni del Bardo");
            $skills = array(1=>"darkarts","magic","thievery","militare","mystic","tactic","rockskin","rhetoric","muscle","nature","weather","elementale","barbaro","bardo");
            $skillpoints = array(1=>"darkartuses","magicuses","thieveryuses","militareuses","mysticuses","tacticuses","rockskinuses","rhetoricuses","muscleuses","natureuses","weatheruses","elementaleuses","barbarouses","bardouses");
            $session[user][$skills[$session['user']['specialty']]]++;         
        break;
      	case 20:
        	$carta = "`@La Papessa Rovesciata" ;
    		$cartamsg = "`0`iQualche nozione hai dimenticato, questo succede se il sapere vien trascurato!`i";
   			$skillnames = array(1 => "`\$Arti Oscure", "`%Poteri Mistici", "`^Furto", "`3Militare","`\$Seduzione","`^Tattica","`@Pelle di Roccia","`#Retorica","`%Muscoli","`3Natura","`&Clima","`^Elementalista","`6Rabbia Barbara","`5Canzoni del Bardo");
            $skills = array(1=>"darkarts","magic","thievery","militare","mystic","tactic","rockskin","rhetoric","muscle","nature","weather","elementale","barbaro","bardo");
            $skillpoints = array(1=>"darkartuses","magicuses","thieveryuses","militareuses","mysticuses","tacticuses","rockskinuses","rhetoricuses","muscleuses","natureuses","weatheruses","elementaleuses","barbarouses","bardouses");
            $session[user][$skills[$session['user']['specialty']]]--; 
        break;
        case 21:
        	$carta = "`@Il Papa" ;
    		$cartamsg = "`0`iIl Papa dispensa sapienza vera, avanza col suo favor la tua carriera!`i";
   			$session['user']['punti_carriera']+=100; 
            $session['user']['punti_generati']+=100;
        break;
      	case 22:
        	$carta = "`@Il Papa Rovesciato" ;
    		$cartamsg = "`0`iDai suoi seguaci il Pontefice vien rinnegato, nella tua carriera sarai degradato!`i";
   			$session['user']['punti_carriera']-=100; 
            $session['user']['punti_generati']-=100;
        break;
	}			
	output("mentre ascolti con molta attenzione le parole che la gitana pronuncia. ");				
    output("`n`n $carta : $cartamsg `n");
    
    if (!$session['user']['alive']) {
    	output("`n`n`%E mentre nella tua mente risuonano le tenebrose parole della zingara, sprofondi nell'incoscienza e ti ritrovi, anima dannata, nel regno di `\$Ramius il Signore della Morte `% senza avere la possibilità di risorgere!");
        debuglog("è ucciso dalla lettura delle carte della gitana al campo degli zingari");
        $session['user']['hitpoints']=0;
        $session['user']['soulpoints']=0;
        $session['user']['deathpower']=0;
        $session['user']['gravefights']=0;
        addnav("`^Notizie Giornaliere","news.php");
    }else{
	    output("`n`%Mentre perpless".($session['user']['sex']?"a":"o").", mediti sulle parole appena udite, cercando ti trovare la più corretta interpretazione ");
	    output("che meglio si adatta alla tua realtà, esci dal tendone per continuare la tua passeggiata nel campo degli Zingari, convint".($session['user']['sex']?"a":"o")." di conoscere perfettamente il futuro che ti aspetta. ");
	    addnav("T?Torna all'accampamento","gypsycamp.php");
	}
}else{
    page_header("Tenda degli Zingari");
    output("`5Ti infili nella tenda del vecchietto zoppicante che promette di lasciarti parlare con i defunti. Questi, mentre ti accompagna dalla moglie seduta ad un tavolino sul quale è posata una sfera di cristallo, ti 
    informa che è possibile parlare coi morti solo dietro un piccolo compenso per lo sforzo che la sua compagna dovrà compiere per contattare le anime defunte. Il prezzo è `^$costolivello`5 pezzi d'oro.");    
    addnav("M?Parla con i Morti","gypsy.php?op=pay");
    if ($session['user']['charisma']==4294967295 && $session['user']['seenlover']<1) {
        $sql = "SELECT name,alive FROM accounts WHERE ".$session['user']['marriedto']." = acctid ORDER BY charm DESC";
        $result = db_query($sql) or die(db_error(LINK));
        $row = db_fetch_assoc($result);
        if ($row['alive']==0) addnav("P?Paga per flirtare con ".$row['name'],"gypsy.php?op=pay&was=flirt");
    }
    addnav("L?Lascia Perdere","gypsycamp.php");
}
page_footer();
?>