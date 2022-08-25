<?php
require_once("common.php");
require_once("common2.php");
checkday();
page_header("Ukhtrak il Mercante di Draghi");
$session['user']['locazione'] = 151;
$session['coeffvendita'] = 2; // il valore dell drago viene ridotto di 1/2 alla vendita
//generazione automatica draghi se ce ne sono meno di 5 
//cancellazione automatica draghi ( 1 drago ad ogni ingresso ) se ce ne sono più di 500

$sql = "SELECT count(*) AS numero_draghi FROM draghi WHERE dove = 1";
$result = db_query($sql) or die(db_error(LINK));
$row = db_fetch_assoc($result);
if ($row['numero_draghi'] > 500 ) {
	$sql = "SELECT id FROM draghi WHERE dove = 1 AND user_id='0' ORDER BY RAND() LIMIT 1";
    $result = db_query($sql) or die(db_error(LINK));
    $row = db_fetch_assoc($result);
    $sqle = "DELETE FROM draghi WHERE id='{$row['id']}'";
    db_query($sqle);
    //fine cancellazione automatica
}else {
	if ($row['numero_draghi'] < 5) {
   		 crea_drago(0,1,0,3,1);
   		 //fine generazione automatica
	}
}

addnav("Azioni");
addnav("E?Esamina i draghi", "mercante_di_draghi.php?op=esamina");
addnav("D?Valuta il tuo Drago", "mercante_di_draghi.php?az=valuta");

if ($_GET['op'] == "" and $_GET['az'] == "") {
	output("`n`3Un pò timoroso entri nel serraglio di `2Ukhtrak`3 il mercante e quasi svieni per l'incredibile puzza che aleggia nell'aria e che addirittura`n ");
	output("sovrasta il tipico odore di zolfo dovuto al soffio dei draghi presenti. Inizialmente ti domandi da dove giunga questo terribile odore, visto `n ");
	output("che il pavimento è ben pulito e notoriamente i draghi sono animali estremamente puliti, ma trovi immediatamente la risposta quando un `n ");
	output("enorme `2Troll`3, che probabilmente deve aver litigato anni or sono con la vecchina gestrice delle docce pubbliche, ti viene incontro con aria amichevole.`n");
    output("`2Ukhtrak`3 ti conduce verso gli stalli dei draghi e per ognuno di essi ti racconta le incredibili peripezie che ne hanno accompagnato la `n");
    output("cattura nelle terre più remote e lontane. Il tuo naso però fatica a sopportare il nauseabondo afrore di bue muschiato sprigionato dal `n");
    output("mercante per cui blocchi bruscamente quel fiume di parole esortandolo a passare subito agli affari.");
    output("Il `2Troll`3 sogghignando ti informa che il prezzo dei suoi animali è proporzionato alle loro caratteristiche e ti ricorda che l'acquirente,");
    output("per poter cavalcare, deve essere sufficientemente abile da domare il loro carattere. `n");
    output("Conclude poi chiedendoti se preferisci esaminare con calma i portentosi animali attualmente in vendita guardando attentamente le loro `n");
    output("caratteristiche o se invece preferisci far valutare il tuo attuale drago per una eventuale vendita.`n");
} elseif ($_GET['op'] == "esamina") {
    $sql = "SELECT * FROM draghi WHERE dove = 1 ORDER BY valore DESC";
    output("`c`b`&Draghi attualmente in vendita.`n`n`b`c");
    output("<table cellspacing=0 cellpadding=2 align='center'><tr>
            <td>&nbsp;</td><td>`b`@Tipo`b</td>
            <td>`b`#Età`b</td>
            <td>`b`&Costo`b</td>
            <td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td>
            <td>`b`@Carattere`b</td><td>&nbsp;</td>
            <td>`b`\$Att.`b</td><td>&nbsp;</td>
            <td>`b`^Dif.`b</td><td>&nbsp;</td>
            <td>`b`%Vita.`b</td><td>&nbsp;</td>
            <td>`b`^Soffio.`b</td><td>&nbsp;</td>
            <td>`b`&Danno Soffio.`b</td><td>&nbsp;</td>
            <td>`b`3Aspetto.`b</td>",true);
    if ($session['user']['superuser'] > 2) {
        output("<td>&nbsp;</td><td>`b`#ID`b</td>",true);
    }
    output("</tr>", true);
    $result = db_query($sql) or die(db_error(LINK));
    if (db_num_rows($result) == 0) {
        output("<tr><td colspan=4 align='center'>`&Attualmente non ci sono draghi in vendita.`0</td></tr>", true);
    }
    $countrow = db_num_rows($result);
    for ($i=0; $i<$countrow; $i++){
        $row = db_fetch_assoc($result);
        $coloredrago = coloradrago($row['tipo_drago']);
        output("<tr class='" . ($i % 2?"trlight":"trdark") . "'><td align=right>" . ($i + 1) . ".</td>
                <td><A href=mercante_di_draghi.php?op=".$row['id'].">".$coloredrago."".$row['tipo_drago']."</a></td>
                <td>`b`#".$row['eta_drago']."`b</td>
                <td>`b`&".$row['valore']."`b</td>
                <td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td>
                <td align='center'>`b`@".$row['carattere']."`b</td><td>&nbsp;</td>
                <td align='center'>`b`\$".$row['attacco']."`b</td><td>&nbsp;</td>
                <td align='center'>`b`^".$row['difesa']."`b</td><td>&nbsp;</td>
                <td align='center'>`b`%".$row['vita']."`b</td><td>&nbsp;</td>
                <td align='center'>`b`^".$row['tipo_soffio']."`b</td><td>&nbsp;</td>
                <td align='center'>`b`&".$row['danno_soffio']."`b</td></td><td>&nbsp;</td>
                <td align='center'>`b`3".$row['aspetto']."`b</td></td>",true);
        if ($session['user']['superuser'] > 2) {
            output("<td>&nbsp;</td><td>`b`#".$row['id']."`b</td>",true);
        }
        output("</tr>", true);
        addnav("", "mercante_di_draghi.php?op=".$row['id']."");
    }
    output("</table>", true);

} elseif ($_GET['az'] == "") {
	
	$drago = $_GET['op'];
	
	if ($session['user']['superuser'] > 2) {
    	output ("ID Drago : $drago `n`n");
    }	
    
    visualizzadrago($drago);
    
    output("<form action='mercante_di_draghi.php?az=compra&dragoid=$_GET[op]' method='POST'>", true);
    addnav("", "mercante_di_draghi.php?az=compra&dragoid=$_GET[op]");
    output("<input type='submit' class='button' value='Compra'>", true);
    output("</form>", true);
}
if ($_GET[az] == "compra") {
    if ($session['user']['reincarna'] > 0 OR $session['user']['dragonkills'] >= 18) {
        $sqlo = "SELECT id,eta_drago,tipo_drago,valore,carattere FROM draghi WHERE id = $_GET[dragoid]";
        $resulto = db_query($sqlo) or die(db_error(LINK));
        $row = db_fetch_assoc($resulto);
        if ($row['valore']>$session['user']['gems']) {
	        output("`n`2Ukhtrak `3sgrana gli occhi e ti guarda meravigliato per il tuo patetico tentivo di imbroglio e sogghignando esclama :`n");   
        	output ("`^Molto bene ".$session['user']['name']." `^ Cavaliere di Draghi ci hai provato ma ti è andata male! `n");
        	output("`3Dopodichè, scoppiando in una fragorosa risata aggiunge: `n`^Direi che per quelle poche gemme che possiedi potrei venderti solamente il mio cavallo!`0`n`n");
        }else{
            $sqlogg = "SELECT acctid FROM accounts WHERE id_drago = $_GET[dragoid]";
            $resultogg = db_query($sqlogg) or die(db_error(LINK));
            if ($session['user']['cavalcare_drago'] < $row['carattere']) {
                output("`2Ukhtrak `3ti osserva attentamente come per valutare le tue capacità e sogghignando esclama :`n");   
        		output ("`^ ".$session['user']['name']." `^ Cavaliere di Draghi ti venderei volentieri l'animale che hai scelto, ma è troppo potente per i tuoi attuali punti cavalcare.`0`n");
        		output("`3Dopodichè, scoppiando in una fragorosa risata aggiunge: `n`^Direi che ti conviene sceglierne un altro o tornare quando sarai diventato più abile!`0`n`n");
            } else {
                if (db_num_rows($resultogg) == 0) {
                    if ($session['user']['id_drago'] == 0) {
	                    output("`n`2Ukhtrak `3incamera rapidamente le gemme che gli consegni e sogghignando esclama :`n");   
        				output ("`^Molto bene ".$session['user']['name']." `^ Cavaliere di Draghi ecco il tuo nuovo compagno di avventure!`0`n");
        				output("`3Dopodichè, scoppiando in una fragorosa risata ti consegna l'animale.`0`n`n");
            			$coloredrago = coloradrago($row['tipo_drago']);
                        output ("`0l tuo bellissimo drago $coloredrago ".$row['tipo_drago']." ".$row['eta']." `0sembra felice di avere un nuovo proprietario e lo dimostra sbattendo con forza le sue ali.");
                        debuglog("ha comprato il drago $_GET[dragoid] da `2Ukhtrak `3 per `#`b{$row['valore']}`3`b gemme.");
                        $session['user']['id_drago'] = $row['id'];
                        $user_id=$session['user']['acctid'];
                        $sql = "UPDATE draghi SET user_id='$user_id',dove=0 WHERE id=$_GET[dragoid]";
                        db_query($sql) or die(db_error(LINK));
                        $session['user']['gems'] -= $row['valore'];
                    }else {
	                    output("`n`2Ukhtrak `3sgrana gli occhi e ti guarda stupito per quanto hai intenzione di fare e sogghignando esclama :`n");   
        				output ("`^Molto bene ".$session['user']['name']." `^ Cavaliere di Draghi vedo che sei interessato a uno dei miei bellissimi animali. `n");
        				output("`3Dopodichè, scoppiando in una fragorosa risata aggiunge: `n`^Direi che però ti conviene vendere il drago che già possiedi prima di comprarne un altro!`0`n`n");
                                            }
                } else {
	                output("`n`2Ukhtrak `3sgrana gli occhi attonito e sospirando esclama :`n`n");   
        			output ("`^Mi spiace molto ".$session['user']['name']." `^ Cavaliere di Draghi vedo che sei interessato a uno dei miei bellissimi animali, `n");
        			output("`3ma purtroppo non posso vendertelo, perchè non è più disponibile, qualche altro cliente lo ha già acquistato!`0`n`n");
                }
            }
        }
    }else {
	    output("`n`2Ukhtrak `3ti osserva attentamente come per valutare le tue capacità e il tuo valore di guerriero, poi sogghignando esclama :`n");   
        output ("`^ ".$session['user']['name']." `^ ti venderei volentieri l'animale che hai scelto, ma devi essere reincarnato o almeno aver ucciso 18 draghi verdi per acquistare nel mio negozio.`0`n");
        output("`3Dopodichè, scoppiando in una fragorosa risata aggiunge: `n`^Direi che ti conviene tornare quando sarai diventato un guerriero più valente!`0`n`n");
        
    }
}else if ($_GET[az] == "valuta") {
    $drago = $session['user']['id_drago'];
        
    if ($session['user']['id_drago'] == 0) {
        $drago = "Nulla";
        output("`n`2Ukhtrak `3spalanca gli occhi e ti guarda meravigliato per la tua bizzarra richiesta e sogghignando esclama :`n");   
        output ("`^Molto bene ".$session['user']['name']." `^ Cavaliere di Draghi il tuo è un bellissimo Drago Invisibile. `n");
        output("`3Dopodichè, scoppiando in una fragorosa risata aggiunge: `n`^Direi che la sua valutazione attuale è di circa `#1.000`^ gemme invisibili!!`0`n`n");
        
    } else {
	   	output("`n`2Ukhtrak `3esamina minuziosamente in ogni aspetto il tuo drago, ne prende accuratamente le misure, palpeggiandone attentamente la muscolatura. ");
	   	output("Nulla viene lasciato al caso, infatti il mercante osserva scrupolosamente lo stato e le condizioni della dentatura del tuo animale e ne valuta anche ");
	   	output("la robustezza delle scaglie che ne rivestono il corpo. `n");   
    	output("Al termine della sua valutazione, con un sospiro esclama : `n`n");
	    output ("`^Ecco fatto ".$session['user']['name']." `^ Cavaliere di Draghi queste sono le caratteristiche del tuo drago : `0`n`n");
	    $modulo= "mercante_di_draghi.php";
	    valutadrago($drago,$session['coeffvendita']);
	    addnav("V?Vendi il tuo drago", "mercante_di_draghi.php?az=vendid&did=$drago");
    	       
    }
        
} else if ($_GET['az'] == "vendid") {
    
        $sqlo = "SELECT * FROM draghi WHERE id = $_GET[did]";
        $resulto = db_query($sqlo) or die(db_error(LINK));
        $rowo = db_fetch_assoc($resulto);
        $valvendo = intval(($rowo['valore']-$rowo['bonus_erold_valore']) / $session['coeffvendita']);
        output("`n`3Con un pizzico di commozione consegni il tuo fedele drago ".$rowo['nome_drago']." compagno di tante battaglie a `2Ukhtrak `3. `n ");
        output("`3Sei comune certo che il mercante ne avrà cura e che presto riuscirà a trovare per lui un buon cavaliere di draghi che lo tratterà bene. `n ");
        output ("`3Hai venduto il tuo drago per `#`b$valvendo`3`b gemme, somma che `2Ukhtrak `3 ti consegna prontamente. `n`n");
        debuglog("ha venduto il drago $_GET[did] {$rowo['nome_drago']} a `2Ukhtrak `3 per `#`b$valvendo`3`b gemme.");
        $user_id=$session['user']['acctid'];
        
        if($rowo[bonus_erold]==da){
            $valore=$rowo[danno_soffio]-$rowo[bonus_erold_valore];
            $sql = "UPDATE draghi SET danno_soffio = '$valore',bonus_erold = 'no',bonus_erold_valore = '0' WHERE user_id='$user_id'";
            db_query($sql) or die(db_error(LINK));
        }
        if($rowo[bonus_erold]==at){
            $valore=$rowo[attacco]-$rowo[bonus_erold_valore];
            $sql = "UPDATE draghi SET attacco = '$valore',bonus_erold = 'no',bonus_erold_valore = '0' WHERE user_id='$user_id'";
            db_query($sql) or die(db_error(LINK));
        }
        if($rowo[bonus_erold]==di){
            $valore=$rowo[difesa]-$rowo[bonus_erold_valore];
            $sql = "UPDATE draghi SET difesa = '$valore',bonus_erold = 'no',bonus_erold_valore = '0' WHERE user_id='$user_id'";
            db_query($sql) or die(db_error(LINK));
        }
        if($rowo[bonus_erold]==vi){
            $sql = "UPDATE draghi SET vita_restante = vita,bonus_erold = 'no',bonus_erold_valore = '0' WHERE user_id='$user_id'";
            db_query($sql) or die(db_error(LINK));
        }
        
        $sql = "UPDATE draghi SET user_id='0',dove=1,nome_drago='' WHERE id=$_GET[did]";
          
        db_query($sql) or die(db_error(LINK));
        $session['user']['id_drago'] = 0;
        $session['user']['gems'] += $valvendo;
  
}

addnav("Exit");
addnav("T?Torna in paese", "village.php");


page_footer();

?>