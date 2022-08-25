<?php
/**
* Version:    0.2
* Date:        November  2003
* Author:    anpera
* Email:        logd@anpera.de
*
* Purpose:    Additional functions for hard working players
* Program Flow:    The witchhouse appears if a player has 1 or less forest fights left.
*         In it he can buy additional forest fights or use his last forest fight to get a 'special event'.
*        He also can reset some variables to get more tries for example with flirting or finding the dragon...
*
* Nav added in function forest in common.php:
* if ($session[user][turns]<=1 ) addnav("Hexenhaus","hexe.php");
*/
require_once("common.php");
require_once("common2.php");

$max_morti=1; //numero massimo di morti per insolvenza prima che vengano azzerati i favori da Ramius

page_header("Casa della Strega");
$session['user']['locazione'] = 139;
$wkcost=$session['user']['level']*300;
$spcost=$session['user']['level']*100;
if ($_GET['op'] == "wkkauf"){
    if ($session['user']['gold']<$wkcost){
        output("`!\"`%Non hai abbastanza oro! `bVATTENE VIA!`b`!\" Con queste parole magiche, senti una forza sbatterti fuori dalla casa.`nHai perso un po' di esperienza.`n`n");
        $session['user']['experience']=round($session['user']['experience']*0.9);
    } else {
        $session['user']['gold']-=$wkcost;
        $session['user']['turns']++;
        debuglog("riceve un combattimento dalla strega");
        output("`!Dai alla strega`^ $wkcost `!Oro. Velocemente prende le monete e le mette in una cassa di legno. ");
        output(" La strega prende la tazzina di punch di un coloraccio marrone, ti prende un braccio e vuole fartela bere. Nonostante la tua grande forza non sei in grado di liberarti della sua presa. ");
        output("Il liquido acido scende nella tua gola, senti l'energia tornare nel tuo corpo, potrai tornare a combattere nel bosco.`nTe ne vai e la senti ridere, ");
        output("lei torna a sorseggiare il suo punch. Ora torni nel bosco.`n`n");
    }
    forest(true);
} else if ($_GET['op'] == "besonders"){
    if ($session['user']['gold']<$spcost){
        $numero_morti=get_morte_strega();
	//Sook, modifica per togliere tutti i favori a chi si suicida volontariamente dalla strega ed era già risorto una volta.
        if($numero_morti<$max_morti){
            //output("`!\"`%Non hai abbastanza oro! `bSPARISCI DALLA MIA VISTA!`b`!\" Con queste parole magiche, senti una forza enorme schiacciarti al suolo soffocandoti.`n`n");
		output("`!La strega ti guarda in cagnesco per qualche secondo, poi sbotta: `%\"Non hai abbastanza oro! Credi forse che abbia tempo da perdere? `bSPARISCI DALLA MIA VISTA!`b\"`n
                        `!Non fai in tempo a notare i rapidi movimenti delle sue mani che già le forze ti stanno abbandonando. Ti senti schiacciare da un potere sconosciuto, e solo quando i tuoi polmoni cominciano 
                         ad annaspare in cerca d'aria capisci che è ormai troppo tardi.`n`n");
        	output("`n`%SEI MORT".($session['user']['sex']?"A":"O")."!`n`n");
        	$session['user']['experience']=round($session['user']['experience']*0.9);
	        output("`3Perdi tutto l'`^oro`3 che avevi in tasca e il `&10%`3 di esperienza.`n");
	    	$session['user']['alive']=false;
	        $session['user']['hitpoints']=0;
	        $session['user']['gold']=0;
                $numero_morti++;
                set_morte_strega($numero_morti);
	        addnav("Notizie quotidiane","news.php");
	        addnews("`%".$session['user']['name']."`3 è stat".($session['user']['sex']?"a":"o")." uccis".($session['user']['sex']?"a":"o")." per aver fatto infuriare la strega della foresta.`0.");
	 		debuglog("è stato ucciso dalla strega perdendo 10% esperienza e tutto l'oro");
	 		page_footer();     
        }else{
		output("`!La strega diventa paonazza dalla rabbia: `%\"E così a quanto pare l'idea di sprecare il mio prezioso tempo ti diverte molto! Mi credi forse una fattucchiera da quattro soldi? 
                        `bPOI NON DIRE CHE NON TE LA SEI CERCATA: MI ASSICURERÒ CHE TU NON POSSA TORNARE AD INFASTIDIRMI!`b\"`n`!Mentre un ghigno malefico le squarcia il viso, ti senti improvvisamente 
                        afferrare dal basso. Volgi istintivamente il tuo sguardo al terreno, e scopri con orrore che numerose creature dall'aspetto nauseabondo sono aggrappate saldamente alle tue gambe.`n 
                        Lanci un urlo strozzato, ma è troppo tardi: stai sprofondando verso la `9Terra delle Ombre`!! Mentre perdi conoscenza, potresti giurare di aver visto `\$Ramius `!discutere con la strega, 
                        indicandoti e ridendo divertito.`n`n`n`b`\$SEI MORT".($session['user']['sex']?"A":"O")."!`b`n`n`3Perdi tutto l'`^oro `3 che avevi con te e il `&10% `3della tua esperienza!`n`\$Perdi anche 
                        tutti i tuoi favori con Ramius, mentre le orribili creature ti impediscono di tormentare nuovi nemici!");
        	$session['user']['experience']=round($session['user']['experience']*0.9);
	    	$session['user']['alive']=false;
	        $session['user']['hitpoints']=0;
	        $session['user']['gold']=0;
                $numero_morti++;
                set_morte_strega($numero_morti);
	        $session['user']['deathpower']=0;
	        $session['user']['gravefights']=0;
	        addnav("Notizie quotidiane","news.php");
	        addnews("`%".$session['user']['name']."`3 è stat".($session['user']['sex']?"a":"o")." uccis".($session['user']['sex']?"a":"o")." per aver fatto infuriare la strega della foresta.`0.");
	        debuglog("è stato ucciso dalla strega perdendo 10% esperienza e tutto l'oro. Essendo già morto in precedenza, perde anche tutti i favori ed i combattimenti nel cimitero!");
	 		page_footer();     
        }
    }else{
        $session['user']['gold']-=$spcost;
        $session['user']['turns']-=1;
            if (e_rand(0,5)==0) {
            output("`5Purtroppo la magia della strega non ha funzionato completamente e, oltre al tuo oro hai perso un turno di combattimento !!`n");
            }
        output("`!Paghi la strega, e lei ti lancia una magia, una nube ti circonda. La casa sparisce, ed ecco come promesso...`n`n");
        output("`^`c`bAltri servizi!`c`b`0");
        if ($handle = opendir("special")){
            $events = array();
            while (false !== ($file = readdir($handle))){
                if (strpos($file,".php")>0){
                    // Skip the darkhorse if the horse knows the way
                    if ($session['user']['hashorse'] > 0 && $playermount['tavern'] > 0 && strpos($file, "darkhorse") !== false) continue;
                    // Saltiamo Merk se il personaggio non ha almeno 4 reincarnazioni
                    if ($session['user']['reincarna'] < 4 && strpos($file, "merk") !== false) continue;
                    array_push($events,$file);
                }
            }
            $tot=0; //calcolo somma dei totali degli eventi per poi effettuare il sorteggio
            for($i=0;$i<(count($events));$i++) {
               $sqlch="SELECT strega FROM peso_eventi WHERE nomefile='".$events[$i]."' ";
               $resultch = db_query($sqlch) or die(db_error(LINK));
               $rowch = db_fetch_assoc($resultch);
               $tot += $rowch['strega'];
            }

            /*
            $x = 17;
            $x = e_rand(0,count($events)-1);
            Sook, diversificazione delle probabilità dei vari eventi
            $sqltotal="SELECT sum(strega) as tot FROM peso_eventi";
            $resulttotal = db_query($sqltotal) or die(db_error(LINK));
            $rowtotal = db_fetch_assoc($resulttotal);
            */

            if (count($events)==0){
                output("`b`@Ahi, il tuo Amministratore ha deciso che non ti è permesso avere eventi speciali. Prenditela con lui, non con me.");
            }else{
                $x=e_rand(1,$tot); //numero estratto per la selezione evento
                $sqlevents="SELECT nomefile, strega FROM peso_eventi ORDER BY rand()";
                $resultevents = db_query($sqlevents) or die(db_error(LINK));
                $j=0; //indice pesato per la ricerca dell'evento estratto
                while ($j<$x) {
                    $rowevents = db_fetch_assoc($resultevents);
                    $i = $rowevents['nomefile'];
                    $k=0; //controllo che l'evento possa essere scelto
                    for ($l=0;$l<(count($events));$l++) {
                        if ($i==$events[$l]) $k=1; //ok, l'evento è stato trovato ed il giocatore può averlo
                    }
                    if ($k==1) $j += $rowevents['strega']; //incremento indice peso
                }
                $y = $_GET['op'];
                $_GET['op']="";
                //$_GET['op']="";
                //include("special/".$events[$x]);
//              @(include("special/".$events[$x])) or redirect("forest.php");
                @(include("special/".$i)) or redirect("forest.php"); //si usa un altro indice
                $_GET['op']=$y;
                //$_GET['op']=$y;
            }
            if ($x == 500) {
                $session['user']['gold']+=$spcost;
                addnav("Altro Incantesimo","hexe.php?op=besonders");
                output("`b`@La strega impreca e lancia nuovamente la magia che miseramente non ha funzionato.");
            }
        }else{
            output("`c`b`\$ERRORE!!!`b`c`&Non riesco ad aprire gli eventi speciali!  Per favore avverti gli Admin!!");
        }
        if ($nav==""){
            //addnav("Torna alla Foresta","forest.php");
            //$session[user][turns]=0;
             forest(true);
       }
    }
} else if ($_GET['op'] == "verwirren"){
    output("`!La strega prende la tua gemma e poi estrae una bambola da una cesta, assomiglia incredibilmente al maestro. La strega punge la bambola con un ago arruginito ");
    output("sulla testa e dice: \"`%Vai tranquillo dal tuo maestro. Oggi hai una seconda occasione, per combatterlo. Preparati bene!`!\"`nNon sai, ");
    output("quanto sia onorevole, ma una occasione è una occasione.`n`n");
    $session['user']['gems']--;
    $session['user']['seenmaster']=0;
    debuglog("ha dato una gemma alla strega per affrontare nuovamente il maestro");
    forest(true);
} else if ($_GET['op'] == "drachen"){
    output("`!Prendi tre gemme guadagnate con fatica e le porgi alla strega. La strega le prende la tua mano e la stringe talmente forte che inizia a formicolare.");
    output("\"`%Ora hai una nuova possibilità di combattere il drago. Ma questa volta fallo bene!`!\" La strega lascia la tua mano e le gemme spariscono.`n");
    output("I(l Drago ti aspetta...`n`n");
    debuglog("ha dato 3 gemma alla strega per affrontare nuovamente il drago");
    $session['user']['gems']-=3;
    $session['user']['seendragon']=0;
    forest(true);
} else if ($_GET['op'] == "liebe"){
    output("`!La strega prende la tua gemma e prende una bambola da un angolo, che è uguale a ".($session[user][sex]?"Seth":"Violet").". La strega gira la bambola con un paio di volte la schiaccia e dice: ");
    output("\"`%Cosa vuoi da me ? Un bacio ? Vai dal tuo amore.`!\"`n`n");
    debuglog("ha dato una gemma alla strega per incontrare nuovamente Seth/Violet");
    $session['user']['gems']--;
    $session['user']['seenlover']=0;
    forest(true);
} else if ($_GET['op'] == "blase"){
    output("`!La strega prende la tua gemma e ti offre una birra. Poi un'altra e ancora una. E così via... Dopo un po sei completamente ubriaco e pensi, dannata strega ");
    output("mi hai raggirato, non hai fatto nessuna magia... *hic* ...`n`n");
    $session['user']['drunkenness']+=30;
    $session['user']['gems']--;
    $session['user']['usedouthouse']=0;
    debuglog("ha dato una gemma alla strega per usare nuovamente il bagno");
    forest(true);
} else if ($_GET['op'] == "barde"){
    output("`!\"`%Così il bardo non canta più per te? . Se anzichè a me la gemma la avresta data a lui avrebbe di sicura cantato per te. Sai cosa? Prende una bambola uguale al bardo ");
    output(" gli torce i piedi e dice ora è convinto di essersi perso, e che tu lo hai salvato, e se lo conosco, vedrai che te ne sarà riconoscente. ");
    output("`%Bene puoi andare.`!\" ti dice la strega");
    output("Diretto verso il bosco, pensi alla gemma che è sparita... `n`n");
    debuglog("ha dato una gemma alla strega per parlare nuovamente con il bardo");
    $session['user']['gems']--;
    $session['user']['seenbard']=0;
    forest(true);
}else{
    output("`#Incontri la vecchia casa della strega. Dal camino esce un fumo denso e grigio che impregna l'aria. Una tipica strega, alta e magra, gobba con una veste grigia ed un cappello a punta nero ti viene incontro. ");
    output("`n\"`%Oh, ti sei perso? Oppure posso fare qualche cosa per te? ");
    output("Se mi darai`^  $wkcost `%del tuo oro, ti faccio assaggiare il mio punch, e dopo potrai sconfiggere qualche altro mostro. ");
    addnav("Compra combattimenti","hexe.php?op=wkkauf");
    if ($session['user']['turns']>0) {
       addnav("Altri servizi","hexe.php?op=besonders");
        output("Oppure mi dai la tua energia e`^ $spcost `% monete, e ti garantisco grosse sorprese se ... ehm quando lascerai la mia casa.");
    }
    addnav("Torna nel bosco", "forest.php");
    addnav("Altre stregonerie");
    if ($session['user']['seenmaster'] && $session['user']['gems'] && $session['user']['turns']>0) addnav("Sfidare il Maestro (1 Gemma)","hexe.php?op=verwirren");
    if ($session['user']['seendragon'] && $session['user']['gems']>2 && $session['user']['turns']>0 && $session['user']['level']>=15) addnav("Nuova Cerca del Drago (3 Gemme)","hexe.php?op=drachen");
    if ($session['user']['seenlover'] && $session['user']['gems']) addnav("Filtro di Seduzione (1 Gemma)","hexe.php?op=liebe");
    if ($session['user']['usedouthouse'] && $session['user']['gems']) addnav("Il Mondo Girato (1 Gemma)","hexe.php?op=blase");
    if ($session['user']['seenbard'] && $session['user']['gems']) addnav("Infiammare il Bardo (1 Gemma)","hexe.php?op=barde");
    output("`!\"");
}
page_footer();
?>