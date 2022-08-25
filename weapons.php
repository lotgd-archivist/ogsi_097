<?php
require_once "common.php";
require_once "common2.php";
checkday();

page_header("Armeria di MightyE");
$session['user']['locazione'] = 191;
output("`c`b`&Armeria di MightyE`0`b`c");
$tradeinvalue = round(($session['user']['weaponvalue']*.75),0);
// Maximus Inizio: Usura Arma e Armatura
if ($session['user']['weapondmg'] > 0){
    $usura_max = intval($session['user']['weapondmg'] * max((15 + $session['user']['dragonkills']/2 - 2*$session['user']['reincarna']),10)) + 100;
    $percentuale = intval(100 - (($session['user']['usura_arma'] * 100) / ($usura_max)));
    if ($percentuale > 100) $percentuale=100;
    $differenza = round($tradeinvalue  * $percentuale / 100);
    $tradeinvalue -= $differenza;
}
// Maximus Fine: Usura Arma e Armatura
if ($_GET['op']==""){
    
  	output("`n`7Entri nell'armeria e il famoso `!MightyE `7 eroe di molte leggende è ritto a braccia incrociate in piedi vicino il suo banco. Il possente armaiolo non ti degna nemmeno di uno sguardo quando varchi la soglia del suo negozio, ");
    output("ma hai come la sensazione che quell'uomo in realtà stia osservando attentamente ogni tuo movimento. `!MightyE `7 indossa stivali di morbida pelle nera, braghe nere, una giubba di cuoio nera ");
    output("stretta in vita da un cinturone anch'esso nero mentre al di sopra della sua spalla sinistra in diagonale si erge l'elsa ricoperta di pelle e rivestita con una fettuccia di seta di una strana larga spada ricurva. ");
    output("L'aspetto decisamente inquietante del mercante di armi è reso ancor più temibile dalle braccia muscolose completamente tatuate e dalla testa rasata dell'uomo che sicuramente non solo conosce alla perfezione le caratteristiche ");
   	output("di tutte le sue armi ma conosce a menadito anche l'arte di uccidere. Non per niente le ballate dei cantastorie narrano che in un passato non troppo lontano, egli sia stato in grado di uccidere ".($session['user']['sex']?"donne più valorose":"uomini più valorosi")." di te ");
    output("nel tempo di un solo battito di ciglia e senza che il suo avversario si rendesse conto di essere già passato a miglior vita. `n");
    output("Mentre stai quasi per rinunciare all'acquisto e girare i tacchi per uscire dal negozio, `!MightyE`7 ti fa un cenno con il capo, si accarezza il pizzetto, e ti indica la sua collezione di armi facendoti capire che oggi è il tuo giorno ");
    output("fortunato e potrai avere l'opportunità di provare e di acquistare uno di quegli splendidi strumenti di morte.");
    addnav("Esamina le Armi di `!MightyE`7","weapons.php?op=peruse");
    addnav("U?ArmatUre da `#Pegasus`0","armor.php");
    addnav("Torna al Villaggio","village.php");
}else if ($_GET['op']=="peruse"){

	$identarma=array();
	$ident_arma = identifica_arma();
	$articoloarma = $ident_arma['articolo'];
    $maledetta = $ident_arma['maledetta'];
    $pugni = $ident_arma['pugni'];
    
    $sql = "SELECT max(level) AS level FROM weapons WHERE level<=".(int)$session['user']['dragonkills'];
    $result = db_query($sql) or die(db_error(LINK));
    $row = db_fetch_assoc($result);

  $sql = "SELECT * FROM weapons WHERE level = ".(int)$row['level']." ORDER BY damage ASC";
    $result = db_query($sql) or die(db_error(LINK));
    output("`n`7Cammini avanti e indietro esaminando con molta attenzione tutte le armi in esposizione, facendo finta di essere " . ($session['user']['sex']?"una vera esperta":"un vero esperto") . " nel loro uso. ");
    output("`n`!MightyE`7, che sta saggiando col dito il filo di una spada, senza nemmeno alzare lo sguardo ti dice che ");
    if ($pugni) {
        output("non avendo alcuna arma tranne $articoloarma `#{$session['user']['weapon']}`7, è cosa buona per " . ($session['user']['sex']?"una guerriera":"un guerriero") . " del tuo calibro procursene una, l'importante è che la tua scelta venga fatta rapidamente in quanto egli ha numerose cose di cui occuparsi e non ha tempo di dedicarsi ai perdigiorno. ");
	}else{
    	output("può farti `^$tradeinvalue monete d'oro`7 di sconto per $articoloarma `#{$session['user']['weapon']}`7, l'importante è che la tua scelta venga fatta rapidamente in quanto ha numerose cose di cui occuparsi e non ha tempo di dedicarsi ai perdigiorno. ");
    }
    output("`n`nDetto questo riprende il suo lavoro lasciandoti alle prese con la tua importante decisione.`n`n");
    output("<table border='0' cellpadding='0'>",true);
    output("<tr class='trhead'><td>`bNome`b</td><td align='center'>`bDanno`b</td><td align='right'>`bCosto`b</td></tr>",true);
    $countrow = db_num_rows($result);
    for ($i=0; $i < $countrow; $i++){
    	$row = db_fetch_assoc($result);
        $bgcolor=($i%2==1?"trlight":"trdark");
        if ($row[value]<=($session['user']['gold']+$tradeinvalue)){
            output("<tr class='$bgcolor'><td><a href='weapons.php?op=buy&id=$row[weaponid]'>`&$row[weaponname]</a></td><td align='center'>$row[damage]</td><td align='right'>$row[value]</td></tr>",true);
            addnav("","weapons.php?op=buy&id=$row[weaponid]");
        }else{
            output("<tr class='$bgcolor'><td>`7$row[weaponname]</td><td align='center'>$row[damage]</td><td align='right'>$row[value]</td></tr>",true);
            addnav("","weapons.php?op=buy&id=$row[weaponid]");
        }
    }
    output("</table>",true);
    addnav("U?ArmatUre da `#Pegasus`0","armor.php");
    addnav("Torna al Villaggio","village.php");
}else if ($_GET['op']=="buy"){
	$ident_arma=array();
	$ident_arma = identifica_arma();
	$articoloarma = $ident_arma['articolo'];
    $maledetta = $ident_arma['maledetta'];
    $pugni = $ident_arma['pugni'];
    
  	$sqln = "SELECT * FROM weapons WHERE weaponid='$_GET[id]'";
    $resultn = db_query($sqln) or die(db_error(LINK));
    if (db_num_rows($resultn)==0){
      	output("`!MightyE`7 ti guarda confuso per un secondo, poi capisce che evidentemente devi aver preso troppe botte in testa, così annuisce e ti sorride.");
        addnav("Ritenti?","weapons.php");
        addnav("U?ArmatUre da `#Pegasus`0","armor.php");
        addnav("Torna al Villaggio","village.php");
    }else{
      $rown = db_fetch_assoc($resultn);
        if ($rown[value]>($session['user']['gold']+$tradeinvalue)){
          	output("Aspetti che `!MightyE`7 si distragga e raggiungi attentamente il `5".$rown['weaponname']."`7, che rimuovi con cura dalla rastrelliera su cui ");
            output("si trova. Sicuro della riuscita del tuo furto, ti volti e vai verso la porta velocemente, silenziosamente, come un ninja, solo per scoprire dopo averla raggiunta ");
            output("che il massiccio `!MightyE`7 le sta proprio davanti, bloccandoti l'uscita. Esegui un calcio volante. A mezz'aria senti lo \"SHING\" di una spada ");
            output("che viene estratta dal suo fodero... il tuo piede è andato. Atterri sul moncherino e `!MightyE`7 sta davanti all'uscita, con lo spadone nuovamente infilato nel fodero dietro la schiena, senza ");
            output("alcun indizio che riveli che è stato usato, con le braccia incrociate sul petto muscoloso. \"`#Forse ti andrebbe di pagare?`7\" è tutto quello che ");
            output("ti dice mentre crolli al suolo, con il sangue che irrora le tavole del pavimento sotto il piede che ti è rimasto.");
            $session['user']['alive']=false;
            debuglog("ha perso " . $session['user']['gold'] . " oro per tentato furto a MightyE");
            $session['user']['gold']=0;
            $session['user']['hitpoints']=0;
            $session['user']['experience']=round($session['user']['experience']*.9,0);
            output("`b`&Sei stato ucciso da `!MightyE`&!!!`n");
            output("`4Hai perso tutto l'oro che avevi con te!`n");
            output("`4Hai perso il 10% della tua esperienza!`n");
            output("Potrai ricominciare a combattere domani.");
            addnav("Notizie Giornaliere","news.php");
            addnews("`%".$session['user']['name']."`5 è stato ucciso mentre tentava di rubare dall'armeria di `!MightyE`5.");
        }else{
        	if ($pugni) {
        		output("`nComplimentandosi con te per l'ottima scelta `!MightyE`7 toglie dalla rastrelliera e ti consegna una splendida arma nuova di zecca : $rown[weaponaggpos] `#$rown[weaponname]`7."); 
			}else{
    			output("`n`!MightyE`7 prende $articoloarma `#".$session['user']['weapon']."`7 e rapidamente gli aggancia un cartellino con un prezzo maggiorato rispetto a quanto ti ha appena pagato e lo mette in bella mostra assieme alla sua collezione di armi. ");
    			output("`nPoi toglie dalla rastrelliera e ti una splendida arma nuova di zecca : $rown[weaponaggpos] `#$rown[weaponname]`7.");
    		}

            debuglog("ha speso " . ($rown['value']-$tradeinvalue) . " oro per " . $rown['weaponname'] . " arma");
            $session['user']['gold']-=$rown['value'];
            $session['user']['weapon'] = $rown['weaponname'];
            $session['user']['gold']+=$tradeinvalue;
            $session['user']['attack']-=$session['user']['weapondmg'];
            $session['user']['weapondmg'] = $rown['damage'];
            $session['user']['attack']+=$session['user']['weapondmg'];
            $session['user']['weaponvalue'] = $rown['value'];
            // Maximus Inizio: Usura Arma e Armatura
            $usura_max = intval($rown['damage'] * max((15 + $session['user']['dragonkills']/2 - 2*$session['user']['reincarna']),10)) + 100;
            $session['user']['usura_arma'] = $usura_max;
            // Maximus Fine: Usura Arma e Armatura
            output("`n`nEccitat" . ($session['user']['sex']?"a":"o") . " dall'acquisto appena effettuato inizi a provare  $rown[weaponaggpos] `#$rown[weaponname]`7 nella stanza, quasi staccando la testa di `!MightyE`7, che con riflessi fulminei si abbassa prontamente: ormai ci è abituato, non sei " . ($session['user']['sex']?"la prima ":"il primo ") . " che prova una nuova arma con un po' troppa esuberanza.");
            addnav("Esamina le Armi di `!MightyE`7","weapons.php?op=peruse");
            addnav("U?ArmatUre da `#Pegasus`0","armor.php");
            addnav("Torna al Villaggio","village.php");
        }
    }
}

page_footer();
?>