<?php
require_once("common.php");
require_once("common2.php");
page_header("Zukron, il mercante della Città dei Draghi");
$session['user']['locazione'] = 150;
$session['coeffvendita'] = 3; // il valore dell drago viene ridotto di 2/3 alla vendita
//generazione automatica draghi se ce ne sono meno di 5
$sql = "SELECT * FROM draghi WHERE dove = 4";
$result = db_query($sql) or die(db_error(LINK));
if (db_num_rows($result) < 5) {
    $casuale=e_rand(1,100);
    if ($casuale==1) {
        crea_drago(1,3,9,9,4);
    }
    elseif ($casuale<=10) {
        crea_drago(1,3,6,9,4);
    }
    else {
        crea_drago(1,3,0,6,4);
    }
    $sql = "SELECT * FROM draghi WHERE dove = 4";
    $result = db_query($sql) or die(db_error(LINK));
}
//fine generazione automatica

addnav("Azioni");
addnav("E?Esamina i draghi", "mercante_citta_draghi.php?opp=esamina");
addnav("V?Valuta il tuo drago", "mercante_citta_draghi.php?az=valuta");
//addnav("Exit");
//addnav("Citta dei Draghi","terre_draghi.php?op=citta");
if ($_GET['opp'] == "" and $_GET['az'] == "") {
	output("`3Non appena entri nell'enorme capannone, il tuo naso viene investito dall'acre odore di `6zolfo`3, ");
	output("caratteristica che indica la presenza di draghi. `nL'interno è un caos indescrivibile, con i draghi ");
	output("che sollevano un polverone con lo sbattere delle loro ali, i loro sibili risuonano amplificati ");
	output("dalla `nforma a cupola del soffitto ed i loro sbuffi contribuiscono a rendere ancora più caotico ");
	output("l'ambiente circostante.`n");	
   	output("`2Zukron`3, un troll puzzolente che sembra essere il fratello gemello di `2Ukhtrak `3 il mercante di draghi di Rafflingate, ha anche lui un campionario `ndi draghi di ogni tipo e di tutte le età, ");
   	output("da lui catturati o acquistati da altri avventurieri nei mondi conosciuti. `n");
   	output("Ovviamente il prezzo di questi portentosi animali è proporzionato alle loro caratteristiche e l'eventuale acquirente deve essere`n");
    output("innanzitutto in grado di poterli controllare e utilizzare, rispondendo a determinati requisiti che risultano essere fondamentali `n");
    output("per poter procedere e formalizzare l'acquisto di quello che diventerà nel futuro un compagno di avventure.`n");
} elseif ($_GET['opp'] == "esamina") {
   $sql = "SELECT * FROM draghi WHERE dove = 4 ORDER BY valore DESC";
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
      output("<td>`b`#ID`b</td>",true);
   }
   output("</tr>", true);
   $result = db_query($sql) or die(db_error(LINK));
   if (db_num_rows($result) == 0) {
      output("<tr><td colspan=4 align='center'>`&`iAttualmente non ci sono draghi in vendita.`i`0</td></tr>", true);
   }
   $countrow = db_num_rows($result);
   for ($i=0; $i<$countrow; $i++){
   //for ($i = 0;$i < db_num_rows($result);$i++) {
       $row = db_fetch_assoc($result);
       $coloredrago = coloradrago($row['tipo_drago']);
       output("<tr class='" . ($i % 2?"trlight":"trdark") . "'><td align=right>" . ($i + 1) . ".</td>
       <td><A href=mercante_citta_draghi.php?opp=$row[id]>".$coloredrago."".$row['tipo_drago']."</a></td>
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
          output("<td>`b`#".$row['id']."`b</td>",true);
       }
       output("</tr>", true);
       addnav("", "mercante_citta_draghi.php?opp=".$row['id']."");
   }
       output("</table>", true);
} elseif ($_GET['az'] == "") {
	
 	$drago = $_GET['opp'];
	
	if ($session['user']['superuser'] > 2) {
    	output ("ID Drago : $drago `n`n");
    }	
    
    visualizzadrago($drago);
    
    output("<form action='mercante_citta_draghi.php?az=compra&dragoid=$_GET[opp]' method='POST'>", true);
    addnav("", "mercante_citta_draghi.php?az=compra&dragoid=$_GET[opp]");
    output("<input type='submit' class='button' value='Compra'>", true);
    output("</form>", true);
    
}
if ($_GET['az'] == "compra") {
   if ($session['user']['reincarna'] > 0 OR $session['user']['dragonkills'] >= 18) {
      $sqlo = "SELECT * FROM draghi WHERE id = ".$_GET['dragoid'];
      $resulto = db_query($sqlo) or die(db_error(LINK));
      $row = db_fetch_assoc($resulto);
      if ($row['valore']>$session['user']['gems']) {
         output ("`3Zukron ti guarda torvo e dice `n\"`5Non hai abbastanza gemme!`3\"`n`n");
      }else{
         $sqlogg = "SELECT * FROM accounts WHERE id_drago = $_GET[dragoid]";
         $resultogg = db_query($sqlogg) or die(db_error(LINK));
         if ($session['user']['cavalcare_drago'] < $row['carattere']) {
            output ("`3Zukron ti osserva, come per valutare il tuo fisico e dice `n");
            output("\"`5Questo drago è troppo potente per te!`n`n");
         } else {
            if (db_num_rows($resultogg) == 0) {
               if ($session['user']['id_drago'] == 0) {
                  output ("<BIG>`2Hai comprato questo drago :`b`@" . $row['eta'] . $row['tipo_drago']. "`b`2 e lo tieni al guinzaglio.</BIG>`n`n",true);
                  debuglog("ha comprato ".$row['id']." da Zukron per ".$row['valore']." gemme");
                  $session['user']['id_drago'] = $row[id];
                  $user_id=$session['user']['acctid'];
                  $sql = "UPDATE draghi SET user_id='$user_id',dove=0 WHERE id=$_GET[dragoid]";
                  db_query($sql) or die(db_error(LINK));
                  $session['user']['gems'] -= $row['valore'];
               }else {
                  output ("`2Hai già un drago.`2\"`0`n`n");
               }
            } else {
               output ("`4Questo drago non è disponibile, qualche altro cliente lo ha già acquistato.`0`n`n");
            }
         }
      }
   }else {
      output("`n`0\"`\$Mi spiace, ma devi essere reincarnato per comprare i miei draghi`0\" ti dice Zukron`n");
   }
}else if ($_GET[az] == "valuta") {
	
	$drago = $session['user']['id_drago'];
        
    if ($session['user']['id_drago'] == 0) {
        $drago = "Nulla";
        output("`2Zukron `3sgrana gli occhi e ti guarda meravigliato per la tua bizzarra richiesta e sogghignando esclama :`n`n");   
        output ("`^Molto bene ".$session['user']['name']." `^ Cavaliere di Draghi il tuo è un bellissimo Drago Invisibile. `n");
        output("`3Dopodichè, scoppiando in una fragorosa risata aggiunge: `n`^Direi che la sua valutazione attuale è di circa `#1.000`^ gemme invisibili!!`0`n`n");
        
    } else {
	   	output("`2Zukron `3esamina minuziosamente in ogni aspetto il tuo drago, ne prende accuratamente le misure, palpeggiandone attentamente la muscolatura. `n ");
	   	output("Nulla viene lasciato al caso, infatti il mercante osserva scrupolosamente lo stato e le condizioni della dentatura del tuo animale e ne valuta anche `n");
	   	output("la robustezza delle scaglie che ne rivestono il corpo. `n");   
    	output("Al termine della sua valutazione, con un sospiro esclama : `n`n");
	    output ("`^Molto bene ".$session['user']['name']." `^ Cavaliere di Draghi queste sono le caratteristiche del tuo drago : `0`n`n");
	    $modulo= "mercante_di_draghi.php";
	    valutadrago($drago,$session['coeffvendita']);
	    addnav("V?Vendi il tuo drago", "mercante_citta_draghi.php?az=vendid&did=$drago");
    	       
    }
	
} else if ($_GET['az'] == "vendid") {
	
	$sqlo = "SELECT * FROM draghi WHERE id = $_GET[did]";
        $resulto = db_query($sqlo) or die(db_error(LINK));
        $rowo = db_fetch_assoc($resulto);
        $valvendo = intval(($rowo['valore']-$rowo['bonus_erold_valore']) / $session['coeffvendita']);
        output("`3Con un pizzico di commozione consegni il tuo fedele drago ".$rowo['nome_drago']." compagno di tante battaglie a `2Zukron `3. `n ");
        output("`3Sei comune certo che il mercante ne avrà cura e che presto riuscirà a trovare per lui un buon cavaliere di draghi che lo tratterà bene. `n ");
        output ("`3Hai venduto il tuo drago per `#`b$valvendo`3`b gemme, somma che `2Zukron `3 ti consegna prontamente. `n`n");
        debuglog("ha venduto il drago $_GET[did] {$rowo['nome_drago']} a `2Zukron `3 per `#`b$valvendo`3`b gemme.");
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
        
        $sql = "UPDATE draghi SET user_id='0',dove=4,nome_drago='' WHERE id=$_GET[did]";
          
        db_query($sql) or die(db_error(LINK));
        $session['user']['id_drago'] = 0;
        $session['user']['gems'] += $valvendo;
	
	
}

addnav("Exit");
addnav("Citta dei Draghi","terre_draghi.php?op=citta");

page_footer();
?>