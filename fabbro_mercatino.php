<?php
/*
carriera FABBRO        carriera SGRIOS        carriera KARNAK               carriera DRAGO
===============        ===============        ===============               ==============
5=>"Garzone",          1=>"Seguace",          10=>"Invasato",               50=>"Stalliere",
6=>"Apprendista",      2=>"Accolito",         11=>"Fanatico",               51=>"Scudiero",
7=>"Fabbro",           3=>"Chierico",         12=>"Posseduto",              52=>"Cavaliere",
8=>"Mastro Fabbro",    4=>"Sacerdote",        13=>"Maestro delle Tenebre",  53=>"Mastro di Draghi",
                       9=>"Gran Sacerdote",   15=>"Falciatore di Anime",    54=>"Dominatore di Draghi",
                       17=>"Sommo Chierico",  16=>"Portatore di Morte",     55=>"Cancelliere dei Draghi",
*/

require_once("common.php");
require_once("common2.php");

//checkday();

//inizio mercatino
page_header("Il Mercatino di Oberon ");
output("`c`b`#Il Mercatino di Oberon`!`b`c`n");
if ($_GET['op'] == "") {
   addnav("Mercatino");
   addnav("E?Esamina gli Oggetti", "fabbro_mercatino.php?op=esamina");
   addnav("V?Valuta i Tuoi Oggetti", "fabbro_mercatino.php?op=valuta");
   addnav("S?Scambia Oggetti", "fabbro_mercatino.php?op=scambia");
   addnav("C?Chiedi Resoconto Affari", "fabbro_mercatino.php?op=affari");
   addnav("I?Incassa", "fabbro_mercatino.php?op=incassa");
   addnav("R?Ritira un tuo oggetto in vendita", "fabbro_mercatino.php?op=ritira");
   addnav("T?Torna da Oberon", "fabbro.php");
   output ("`n`!Il grande fiuto degli affari di `#Oberon`! ha fatto ancora centro, con la consulenza di `@Brax`! ha aperto al pubblico una parte della sua bottega dove tutti posso esporre e comprare oggetti.`n");
   output ("`#Oberon`! chiede solamente un contributo di `^500 Monete`! per ogni pezzo esposto, garantendo al venditore una transazione sicura.");
}

if ($_GET['op'] == "esamina") {
      if ($_GET['id'] == null) {
        //gestione pagine, Sook
        addnav("Elenco");
        $ppp=200; // Linee da mostrare per pagina
        if (!$_GET['limit']){
            $page=0;
        }else{
            $page=(int)$_GET['limit'];
            addnav("Pagina Precedente","fabbro_mercatino.php?op=esamina&limit=".($page-1)."");
        }
        $limit="".($page*$ppp).",".($ppp+1);

         $sql = "SELECT o.*,
                 a.name
                 FROM oggetti o
                 INNER JOIN accounts a ON a.acctid=o.acctid
                 WHERE dove = 7 ORDER BY livello DESC,nome DESC LIMIT $limit";
         output("`c`b`&Oggetti attualmente esposti`b`c`n");
         output("<table cellspacing=0 cellpadding=2 align='center'><tr>
                 <td>&nbsp;</td><td>`b`@Nome`b</td>
                 <td>`b`#Livello`b</td>
                 <td>`b`&Costo`b</td>
                 <td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td>
                 <td>`b`@HP`b</td><td>&nbsp;</td>
                 <td>`b`\$Att.`b</td><td>&nbsp;</td>
                 <td>`b`^Dif.`b</td><td>&nbsp;</td>
                 <td>`b`%Potenz.`b</td><td>&nbsp;</td>
                 <td>`b`^Oro/g.`b</td><td>&nbsp;</td>
                 <td>`b`&Gem/g.`b</td>
                 <td>`b`VProprietario.`b</td>
                 <td>`b`(Usura Fisica.`b</td>
          <td>`b`8Usura Magica.`b</td>",true);
         if ($session['user']['superuser'] > 2) {
             output("<td>`b`#ID`b</td>",true);
         }
         output("</tr>", true);
         $result = db_query($sql) or die(db_error(LINK));
         if (db_num_rows($result)>$ppp) addnav("Pagina Successiva","fabbro_mercatino.php?op=esamina&limit=".($page+1)."");
         if (db_num_rows($result) == 0) {
             output("<tr><td colspan=20 align='center'>`&Non ci sono oggetti in esposizione mi spiace`0</td></tr>", true);
         }

         $countrow = db_num_rows($result);
         for ($i=0; $i<$countrow; $i++){
         //for ($i = 0;$i < db_num_rows($result);$i++) {
             $row = db_fetch_assoc($result);
             output("<tr class='" . ($i % 2?"trlight":"trdark") . "'><td align=right>" . ($i + $page*$ppp + 1) . ".</td>
                     <td><A href=fabbro_mercatino.php?op=esamina&id=$row[id_oggetti]>`@".$row['nome']."</a></td>
                     <td>`b`#".$row['livello']."`b</td>
                     <td>`b`&gem:".$row['mercatino_gemme']." / oro:".$row['mercatino_oro']."`b</td>
                     <td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td>
                     <td align='center'>`b`@".$row['hp_help']."`b</td><td>&nbsp;</td>
                     <td align='center'>`b`\$".$row['attack_help']."`b</td><td>&nbsp;</td>
                     <td align='center'>`b`^".$row['defence_help']."`b</td><td>&nbsp;</td>
                     <td align='center'>`b`%".$row['potenziamenti']."`b</td><td>&nbsp;</td>
                     <td align='center'>`b`^".$row['gold_help']."`b</td><td>&nbsp;</td>
                     <td align='center'>`b`&".$row['gems_help']."`b</td>
             <td align='center'>`b`V".$row['name']."`b</td>",true);
             if ($row['usuramax']>0) output("<td align='center'>`b`(".(intval(100-(100*$row['usura']/$row['usuramax'])))."`b</td>",true);
             else output("<td align='center'>`b`(non soggetto`b</td>",true);
             if ($row['usuramagicamax']>0) output("<td align='center'>`b`8".(intval(100-(100*$row['usuramagica']/$row['usuramagicamax'])))."`b</td>",true);
             else output("<td align='center'>`b`8non soggetto`b</td>",true);
             if ($session['user']['superuser'] > 2) {
                 output("<td>`b`#".$row['id_oggetti']."`b</td>",true);
             }
             output("</tr>", true);
             addnav("", "fabbro_mercatino.php?op=esamina&id=$row[id_oggetti]");
         }
         output("</table>", true);
      } else {
        $sqlo = "SELECT * FROM oggetti WHERE id_oggetti = $_GET[id]";
        $resulto = db_query($sqlo) or die(db_error(LINK));
        $row = db_fetch_assoc($resulto);
        output ("<table>", true);
        output ("<tr><td>`7Oggetto: </td><td>`&".$row['nome']."</td></tr>", true);
        output ("<tr><td>`6Descrizione: </td><td>`^".$row['descrizione']."</td></tr>", true);
        if ($row['pregiato']==true) output ("<tr><td>`^`bL'oggetto è pregiato`b </td><td></td></tr>", true);
        output ("<tr><td>`3Livello: </td><td>`#".$row['livello']."</td></tr>", true);
        output ("<tr><td>`7Aiuto HP: </td><td>`&".$row['hp_help']."</td></tr>", true);
        output ("<tr><td>`4Aiuto Attacco: </td><td>`\$".$row['attack_help'] . "</td></tr>", true);
        output ("<tr><td>`6Aiuto Difesa: </td><td>`^".$row['defence_help'] . "</td></tr>", true);
        output ("<tr><td>`8Turni Extra: </td><td>`^" . $row['turns_help'] . "</td></tr>", true);
        if ($row['gold_help']!=0) output ("<tr><td>`^Oro generato: </td><td>`^" . $row['gold_help'] . "/giorno</td></tr>", true);
        if ($row['gems_help']!=0) output ("<tr><td>`&Gemme generate: </td><td>`&" . $row['gems_help'] . "/giorno</td></tr>", true);
        if ($row['special_help']!=0) output ("<tr><td>`5Abilità: </td><td>`@" . $skills[$row['special_help']] . "(`^".$row['special_use_help']."`@)</td></tr>", true);
        if ($row['heal_help']!=0) output ("<tr><td>`5Cura: </td><td>`@" . $row['heal_help'] . " hp</td></tr>", true);
        if ($row['quest_help']!=0) output ("<tr><td>`5Quest: </td><td>`@" . $row['quest_help'] . "punti quest</td></tr>", true);
        if ($row['exp_help']!=0) output ("<tr><td>`5Esperienza: </td><td>`@+" . $row['exp_help'] . "%</td></tr>", true);
        output ("<tr><td>`2Cariche totali: </td><td>`@".$row['potere']."</td></tr>", true);
        output ("<tr><td>`2Cariche residue:</td><td>`@".$row['potere_uso']."</td></tr>", true);
        output ("<tr><td>`2Potenziamenti residui:</td><td>`@".$row['potenziamenti']."</td></tr>", true);
        output ("<tr><td>`)Usura fisica:</td><td>`(".(intval(100-(100*$row['usura']/$row['usuramax'])))."</td></tr>", true);
        output ("<tr><td>`)Usura magica:</td><td>`8".(intval(100-(100*$row['usuramagica']/$row['usuramagicamax'])))."</td></tr>", true);
        output ("<tr><td>  </td><td>  </td></tr>", true);
        output ("<tr><td>`7Valore in Gemme: </td><td>`&".$row['mercatino_gemme']."</td></tr>", true);
        output ("<tr><td>`7Valore in Oro: </td><td>`&".$row['mercatino_oro']."</td></tr>", true);
        output ("<tr><td>  </td><td>  </td></tr>", true);
        output ("</table>", true);
        output("<form action='fabbro_mercatino.php?op=comprao&id=$_GET[id]' method='POST'>", true);
        addnav("", "fabbro_mercatino.php?op=comprao&id=$_GET[id]");
        output("<input type='submit' class='button' value='Compra'>", true);
        output("</form>", true);
      }
      addnav("T?Torna all'Ingresso", "fabbro_mercatino.php");
} // end "esamina"

if ($_GET[op] == "valuta") {
      $oggetto = $session['user']['oggetto'];
      $zaino = $session['user']['zaino'];
      if ($session['user']['oggetto'] == "0") {
          $ogg = "Nulla";
      } else {
          $sqlo = "SELECT * FROM oggetti WHERE id_oggetti = $oggetto";
          $resultoo = db_query($sqlo) or die(db_error(LINK));
          $rowo = db_fetch_assoc($resultoo);
          $ogg = $rowo['nome'];
      }
      if ($session['user']['zaino'] == "0") {
          $zai = "Nulla";
      } else {
          $sqlo = "SELECT * FROM oggetti WHERE id_oggetti = $zaino";
          $resultoz = db_query($sqlo) or die(db_error(LINK));
          $rowz = db_fetch_assoc($resultoz);
          $zai = $rowz['nome'];
      }
      $valvendb = $rowz['valore'];
      if ($rowz[usuramax] > 0) {
          $valvendf = $rowz['valore']*$rowz['usura']/$rowz['usuramax'];
      } else {
          $valvendf = $rowz['valore'];
      }
      if ($rowz[usuramagicamax] > 0) {
          $valvendm = $rowz['valore']*$rowz['usuramagica']/$rowz['usuramagicamax'];
      } else {
          $valvendm = $rowz['valore'];
      }
      $valvendz = intval((2 * $valvendb + 2 * $valvendf + $valvendm)/10);
      if ($rowz['usuramagica']==0 AND $rowz[usuramagicamax] > 0) $valvendz=intval($valvendz/2);
      output ("`@Puoi mettere in vendita solo l'oggetto che hai nello zaino!`n`n");
      if ($rowz[dove]!=30) output("<form action='fabbro_mercatino.php?op=vendiz&id=$rowz[id_oggetti]&value=$valvendz' method='POST'>",true);
      if ($rowz[dove]!=30) addnav("", "fabbro_mercatino.php?op=vendiz&id=$rowz[id_oggetti]&value=$valvendz");
      output ("<table>", true);
      output ("<tr><td>`6Nello zaino hai : </td><td>`b`^" . $zai . "`b</td><td>Oro: <input name='oro' size='3'></td><td>Gemme: <input name='gemme' size='3'></td>",true);
      if ($rowz[dove]!=30) {
        output ("<td><input type='submit' class='button' value='Proponi'></td>",true);
      } else {
        output ("<td>Gli artefatti non possono essere venduti</td>",true);
      }
      output ("</tr>", true);
      output ("</table>", true);
      if ($rowz[dove]!=30) output("</form>",true);
      output ("`nBrax valuta questo oggetto `&$valvendz gemme`@.`n");
      addnav("T?Torna all'Ingresso", "fabbro_mercatino.php");
} // end "valuta"
if ($_GET[op] == "vendiz") {
      $valore_ufficiale=$_GET['value'];
      $valore_proposto=intval($_POST['oro']/2500)+$_POST['gemme'];
      $valore_max=intval($valore_ufficiale*1.50);
      $valore_min=intval($valore_ufficiale*0.80);
      $gemme=$_POST['gemme'];
      $oro=$_POST['oro'];
      if(!$gemme)$gemme=0;
      if(!$oro)$oro=0;
      // Maximus Inizio blocco su vendita se livello è inferiore al 5° o superiore al 13°
      if ($session['user']['level'] < 6 || $session['user']['level'] > 13) {
         output ("`\$In questo momento non puoi vendere oggetti magici.`0`n`n");
      }  else {
      // Maximus Fine
            if ($valore_proposto > $valore_max OR $valore_proposto < $valore_min) {
               output ("\"`&Vuoi fare il furbo? Il valore che hai proposto è troppo alto (o troppo basso), non lo metterò mai in vendita, ho una reputazione da difendere, io...`0\" Ti dice torvo Oberon`0`n`n");
            }else{
              if (!$_GET['id'] OR $session['user']['gold'] < 500) {
                  output ("\"`&Vuoi fare il furbo? Ti servono 500 monete per poter esporre oggetti nella mia bottega`0\" Ti dice torvo Oberon`0`n`n");
              } else {
                  $sqlz = "SELECT * FROM oggetti WHERE id_oggetti = $_GET[id]";
                  $resultz = db_query($sqlz) or die(db_error(LINK));
                  $rowz = db_fetch_assoc($resultz);
                  output ("<BIG>`6Hai messo in vendita l'oggetto che avevi nello zaino per `b`^".$gemme."`b`6 gemme e `b`\$".$oro."`b`6 oro.</BIG>`n`n",true);
                  debuglog("ha messo in vendita oggetto ID {$_GET[id]} per {$gemme} gemme e {$oro} oro");
                  $sql = "UPDATE oggetti SET dove='7',acctid='".$session['user']['acctid']."',mercatino_gemme='$gemme',mercatino_oro='$oro' WHERE id_oggetti=$_GET[id]";
                  db_query($sql) or die(db_error(LINK));
                  $session['user']['zaino'] = 0;
                  $session['user']['gold'] -= 500;
              }
          }
      } // Maximus Fine
      addnav("T?Torna all'Ingresso", "fabbro_mercatino.php");
} // end "vendiz"
if ($_GET[op] == "incassa") {
       $sql = "SELECT SUM(gold) AS oro,SUM(gems) AS gemme FROM mercatino WHERE acctid='".$session['user']['acctid']."'";
       $result = db_query($sql) or die(db_error(LINK));
       $row = db_fetch_assoc($result);
       if ((db_num_rows($result) == 0) || ($row['gemme']==0 && $row['oro']==0)) {
           output ("\"`#`iNessuno ha ancora acquistato i tuoi oggetti, quindi non ho nulla da saldarti`i`!\" Ti dice sorridendo `#Oberon`!`n`n");
       }else{
           output ("Hai venduto oggetti per un totale di `^$row[oro] Monete d'Oro e `&$row[gemme] Gemme `!che `#Oberon`! ti consegna prontamente.");
           debuglog("ha ritirato da Oberon {$row['oro']} oro e {$row['gemme']} gemme da vendite nel mercatino");
           $session['user']['gems'] += $row['gemme'];
           $session['user']['gold'] += $row['oro'];
           $sqle = "DELETE FROM mercatino WHERE acctid='{$session['user']['acctid']}'";
           db_query($sqle);
       }
       addnav("T?Torna all'Ingresso", "fabbro_mercatino.php");
}  // end "incassa"

if ($_GET[op] == "affari") {
   	  	output ("`!Provi a chiedere ad `#Oberon`! a quanto ammontano gli `^ori`! e le `&gemme`! riposti nella cassa del mercatino e relativi alle vendite concluse dei tuoi oggetti, ma questi ti risponde che non ha tempo da perdere per simili sciocchezze 
   	  		e che potrai conoscere l'esatto ammontare di quanto hai accumulato solo al momento dell'incasso. Un pò delus".($session[user][sex]?"a":"o")." dalle parole burbere del `#Grande Maestro dei Fabbri`! stai per rinunciare a soddisfare la tua curiosità,
   	  		quando il garzone addetto al mercatino ti guarda sottecchi e, vedendoti particolarmente interessato alla cosa, sparisce nel retrobottega da dove esce poco dopo tenendo un piccolo libricino  con scritto il tuo nome sul frontespizio. 
			`nSussurrando per non farsi sentire da orecchie indiscrete mormora con aria complice :");
   	  	$sql = "SELECT SUM(gold) AS oro,SUM(gems) AS gemme FROM mercatino WHERE acctid='".$session['user']['acctid']."'";
      	$result = db_query($sql) or die(db_error(LINK));
      	$row = db_fetch_assoc($result);
      	if ((db_num_rows($result) == 0) || ($row['gemme']==0 && $row['oro']==0)) {
           	output ("\"`&`i".$session['user']['name']." `3Nel Registro delle transazioni non risulta alcuna vendita.`i`0\" `n`n");
           	addnav("T?Torna all'Ingresso", "fabbro_mercatino.php");
       	}else{
           	output ("`i".$session['user']['name']." `3nel Registro delle Transazioni risultano alcune vendite. Se siete veramente interessat".($session[user][sex]?"a":"o")." a voler conoscere a quanto ammontano gli affari conclusi 
           		per gli oggetti da voi depositati in questo Mercatino potrei lasciarvi consultare questo libretto, ma la cosa ha, diciamo, un suo piccolo prezzo, diciamo.... una percentuale pari al `@4`3 per mille dell'ammontare in `&gemme`3 e in `^oro`3....`n");
           	output ("Non vi chiederò nulla invece se il totale di quanto avete accumulato è inferiore ad un certo livello, in quanto non sono esoso e capisco che per chi è ancora agli inizi della carriera.....`i");  
           	addnav("A?Accetta", "fabbro_mercatino.php?op=affariaccetta");
   			addnav("R?Rinuncia", "fabbro_mercatino.php?op=affaririnuncia");  
       	}
} // end "affari"
if ($_GET[op] == "affariaccetta") {
   	  	output ("`!Accetti di pagare la somma pattuita e dopo aver sfogliato tutto l'elenco dei tuoi oggetti venduti al Mercatino giungi finalmente alla pagina che riporta il totale.`n");
   	  	$sql = "SELECT SUM(gold) AS oro,SUM(gems) AS gemme FROM mercatino WHERE acctid='".$session['user']['acctid']."'";
      	$result = db_query($sql) or die(db_error(LINK));
      	$row = db_fetch_assoc($result);
      	$gemmeperc = 0;
      	$oroperc = 0;
      	$gemmeaffari = $row['gemme'];
      	$oroaffari = $row['oro'] ;	
	    if ($gemmeaffari > 200 ) {
	    	$gemmeperc = intval($gemmeaffari/250);
	    }	    
	    if ($oroaffari > 200 ) {
	    	$oroperc = intval($oroaffari/250);
	    } 		
      	$gemmerimaste = $gemmeaffari-$gemmeperc ;
      	$ororimasto = $oroaffari-$oroperc ;
      	if ((db_num_rows($result) == 0) || ($row['gemme']==0 && $row['oro']==0)) {
      	    output ("`!`nNel Registro delle transazioni non risulta alcuna vendita. ");
       	}else{
           	output ("`!`nNel Registro delle transazioni risultavano conclusi affari per un totale di `& $gemmeaffari gemme`! e `^ $oroaffari pezzi d'oro`!.`nDetratta la percentuale corrisposta al garzone rimangono `& $gemmerimaste gemme`! e `^ $ororimasto pezzi d'oro`!, somma che potrai riscuotere in qualsiasi momento.");           	
       	}
       	$sql = "UPDATE mercatino set gold = $ororimasto, gems = $gemmerimaste WHERE acctid='".$session['user']['acctid']."'";
      	$result = db_query($sql) or die(db_error(LINK)); 
      	$sql = "UPDATE mercatino set gold = gold+$oroperc, gems = gems+$gemmeperc WHERE acctid=99999999";
      	$result = db_query($sql) or die(db_error(LINK));
       	addnav("T?Torna all'Ingresso", "fabbro_mercatino.php");
} // end "affariconferma"
if ($_GET[op] == "affaririnuncia") {
   	  	output ("`!Ti piacerebbe sapere a quanto ammontano  gli `^ori`! e le `&gemme`! riposti nella cassa del mercatino e relativi alle vendite concluse dei tuoi oggetti, ma non sei dispost".($session[user][sex]?"a":"o")." a pagare la percentuale richiesta dal garzone, quindi lasci l'improvvisato strozzino alle sue attività e torni a gironzolare per il negozio di `#Oberon`!.`n");
       	addnav("T?Torna all'Ingresso", "fabbro_mercatino.php"); 
} // end "affaririnuncia"

if ($_GET[op] == "scambia") {
      if ($session['user']['dragonkills'] < 10) {
          $livello = 1 + (3 * $session['user']['reincarna']);
      } else if ($session['user']['dragonkills'] >= 10 AND $session['user']['dragonkills'] < 19) {
          $livello = 2 + (3 * $session['user']['reincarna']);
      } else if ($session['user']['dragonkills'] >= 19) {
          $livello = 3 + (3 * $session['user']['reincarna']);
      }
      $ogg = $session['user']['oggetto'];
      $zai = $session['user']['zaino'];
      $sqlo = "SELECT * FROM oggetti WHERE id_oggetti = $ogg";
      $resulto = db_query($sqlo) or die(db_error(LINK));
      $rowo = db_fetch_assoc($resulto);
      $sqlz = "SELECT * FROM oggetti WHERE id_oggetti = $zai";
      $resultz = db_query($sqlz) or die(db_error(LINK));
      $rowz = db_fetch_assoc($resultz);
      if ($livello>=$rowz['livello']){
          if (!$session['user']['zaino'] AND !$session['user']['oggetto']) {
              output ("Non hai nulla da scambiare.`n`n");
          } else if (!$session['user']['zaino']) {
              output ("Non hai nulla nello zaino.`n`n");
          } else {
              output ("Hai invertito l'oggetto equipaggiato con quello nello zaino.`n`n");
              $session['user']['attack'] -= $rowo['attack_help'];
              $session['user']['defence'] -= $rowo['defence_help'];
              $session['user']['bonusattack'] -= $rowo['attack_help'];
              $session['user']['bonusdefence'] -= $rowo['defence_help'];
              $session['user']['maxhitpoints'] -= $rowo['hp_help'];
              $session['user']['hitpoints'] -= $rowo['hp_help'];
              //$session['user']['turns'] = $session['user']['turns'] - $rowo[turns_help];
              if ($rowo[usuramagica]!=0 AND $rowo[usuramagicamax]!=0) $session['user']['bonusfight'] -= $rowo['turns_help'];

              $deposito = $session['user']['oggetto'];
              $session['user']['oggetto'] = $session['user']['zaino'];
              $session['user']['zaino'] = $deposito;

              $session['user']['attack'] += $rowz['attack_help'];
              $session['user']['defence'] += $rowz['defence_help'];
              $session['user']['bonusattack'] += $rowz['attack_help'];
              $session['user']['bonusdefence'] += $rowz['defence_help'];
              $session['user']['maxhitpoints'] += $rowz['hp_help'];
              $session['user']['hitpoints'] += $rowz['hp_help'];
              //$session['user']['turns'] = $session['user']['turns'] + $rowz[turns_help];
              if ($rowz[usuramagica]!=0 AND $rowz[usuramagicamax]!=0) $session['user']['bonusfight'] += $rowz['turns_help'];

              if ($session['user']['hitpoints'] <1) $session['user']['hitpoints'] = 1;
          }
      }else {
          output("Purtroppo il livello dell'oggetto nello zaino è troppo alto per te, l'unica cosa che puoi fare è venderlo.");
      }
      addnav("T?Torna all'Ingresso", "fabbro_mercatino.php");
} // end "scambia"
if ($_GET[op] == "comprao") {
      $sqlo = "SELECT * FROM oggetti WHERE id_oggetti = $_GET[id]";
      $resulto = db_query($sqlo) or die(db_error(LINK));
      $row = db_fetch_assoc($resulto);
      $accountVenditore = $row[acctid];

      // Maximus Inizio blocco su acquisto se livello è inferiore al 5° o superiore al 14°
      if ($session['user']['level'] < 6 || $session['user']['level'] > 13) {
         output ("`\$In questo momento non puoi comprare oggetti magici.`0`n`n");
      } else {
      // Maximus Fine
         $stessoPlayer = false;
         if ($session['user']['acctid']==$accountVenditore) {
            $stessoPlayer = true;
         }else{
            $sqlVenditore = "SELECT lastip, emailaddress FROM accounts WHERE acctid = $accountVenditore";
            $resultVenditore = db_query($sqlVenditore) or die(db_error(LINK));
            $rowVenditore = db_fetch_assoc($resultVenditore);
            if ($session['user']['superuser']<3
            &&  $session['user']['lastip'] == $rowVenditore['lastip']
            || ($session['user'] ['emailaddress'] == $rowVenditore['emailaddress']
            && $rowVenditore[emailaddress])) {
               $stessoPlayer = true;
            }
         }

         if ($stessoPlayer) {
            output ("\"`&Vuoi fare il furbo? Non puoi trasferire oggetti tra i tuoi personaggi!`0\" Ti dice torvo Oberon`0`n`n");
         } else {
           if ($row['mercatino_gemme']>$session['user']['gems'] OR $row['mercatino_oro']>$session['user']['gold']) {
              output ("\"`&Vuoi fare il furbo? Non sei abbastanza ricco per permetterti questo oggetto`0\" Ti dice torvo Oberon`0`n`n");
           }else{
               $sqlogg = "SELECT * FROM accounts WHERE oggetto = $_GET[id]";
               $resultogg = db_query($sqlogg) or die(db_error(LINK));
               if ($session['user']['dragonkills'] < 10) {
                   $maxbuylvl = 1 + (3 * $session['user']['reincarna']);
               } else if ($session['user']['dragonkills'] >= 10 AND $session['user']['dragonkills'] < 19) {
                   $maxbuylvl = 2 + (3 * $session['user']['reincarna']);
               } else if ($session['user']['dragonkills'] >= 19) {
                   $maxbuylvl = 3 + (3 * $session['user']['reincarna']);
               }
               if ($session['user']['superuser'] > 1) {
                   $maxbuylvl = 100;
               }
               if ($row['livello'] > $maxbuylvl) {
                   output ("\"`&Purtroppo per te non puoi comprare questo oggetto, è troppo potente`0\" Ti dice sorridendo Oberon`0`n`n");
               } else {
                   if (db_num_rows($resultogg) == 0) {
                       $sqlzai = "SELECT * FROM accounts WHERE zaino = $_GET[id]";
                       $resultzai = db_query($sqlzai) or die(db_error(LINK));
                       if (db_num_rows($resultzai) == 0) {
                           if ($session['user']['oggetto'] == "0") {
                               output ("<BIG>`2Hai comprato questo oggetto :`b`@" . $row['nome'] . "`b`2 e lo hai indossato.</BIG>`n`n",true);
                               debuglog("ha comprato {$row['nome']} da Oberon per {$row['mercatino_gemme']} gemme e {$row['mercatino_oro']} oro indossandolo");
                               // invio mail al proprietario precedente
                               $mailId = $row[acctid];
                               $mailObj = "`^Hai venduto un Oggetto!";
                               $mailMessage = "{$session['user']['name']} `0ha acquistato {$row['nome']} che avevi messo in vendita! Oberon ti aspetta nella sua bottega per consegnarti il ricavato.";
                               systemmail($mailId,$mailObj,$mailMessage);

                               $session['user']['oggetto'] = $row[id_oggetti];
                               // controllo deve ancora riscuotere
                               $sqlMerc = "SELECT SUM(gold) AS oro,SUM(gems) AS gemme FROM mercatino WHERE acctid='".$row['acctid']."'";
                               $resultMerc = db_query($sqlMerc) or die(db_error(LINK));
                               $rowMerc = db_fetch_assoc($resultMerc);
                               if (db_num_rows($resultMerc) == 0 || ($rowMerc[gemme]==0 && $rowMerc[oro]==0)) {
                                  $sqli = "INSERT INTO mercatino (acctid,gems,gold) VALUES ('".$row['acctid']."','".$row['mercatino_gemme']."','".$row['mercatino_oro']."')";
                               } else {
                                  $gemme = $rowMerc[gemme]+$row['mercatino_gemme'];
                                  $oro = $rowMerc[oro]+$row['mercatino_oro'];
                                  $sqli = "UPDATE mercatino set gems=$gemme, gold=$oro where acctid = '".$row['acctid']."'";
                               }
                               db_query($sqli);
                               $sql = "UPDATE oggetti SET dove=0,acctid=0,mercatino_gemme=0,mercatino_oro=0 WHERE id_oggetti=$_GET[id]";
                               db_query($sql) or die(db_error(LINK));
                               $session['user']['attack'] += $row['attack_help'];
                               $session['user']['defence'] += $row['defence_help'];
                               $session['user']['bonusattack'] += $row['attack_help'];
                               $session['user']['bonusdefence'] += $row['defence_help'];
                               $session['user']['maxhitpoints'] += $row['hp_help'];
                               $session['user']['hitpoints'] += $row['hp_help'];
                               if ($row[usuramagica]!=0 AND $row[usuramagicamax]!=0) $session['user']['playerfights'] -= $rowo['pvp_help'];
                               if ($row[usuramagica]!=0 AND $row[usuramagicamax]!=0) $session['user']['turns'] += $row['turns_help'];
                               if ($row[usuramagica]!=0 AND $row[usuramagicamax]!=0) $session['user']['bonusfight'] += $row['turns_help'];
                               $session['user']['gems'] -= $row['mercatino_gemme'];
                               $session['user']['gold'] -= $row['mercatino_oro'];
                           } elseif ($session['user']['zaino'] == "0") {
                               output ("<BIG>`3Hai comprato questo oggetto :`b`#" . $row['nome'] . "`b`3 e lo hai messo nello zaino.</BIG>`n`n",true);
                               debuglog("ha comprato {$row['nome']} da Oberon per {$row['mercatino_gemme']} gemme e {$row['mercatino_oro']} oro e lo ha messo nello zaino");
                               $session['user']['zaino'] = $row[id_oggetti];
                               // invio mail al proprietario precedente
                               $mailId = $row[acctid];
                               $mailObj = "`^Hai venduto un Oggetto!";
                               $mailMessage = "{$session['user']['name']} `0ha acquistato {$row['nome']} che avevi messo in vendita! Oberon ti aspetta nella sua bottega per consegnarti il ricavato.";
                               systemmail($mailId,$mailObj,$mailMessage);

                               // controllo deve ancora riscuotere
                               $sqlMerc = "SELECT SUM(gold) AS oro,SUM(gems) AS gemme FROM mercatino WHERE acctid='".$row['acctid']."'";
                               $resultMerc = db_query($sqlMerc) or die(db_error(LINK));
                               $rowMerc = db_fetch_assoc($resultMerc);
                               if (db_num_rows($resultMerc) == 0 || ($rowMerc[gemme]==0 && $rowMerc[oro]==0)) {
                                  $sqli = "INSERT INTO mercatino (acctid,gems,gold) VALUES ('".$row['acctid']."','".$row['mercatino_gemme']."','".$row['mercatino_oro']."')";
                               } else {
                                  $gemme = $rowMerc['gemme']+$row['mercatino_gemme'];
                                  $oro = $rowMerc['oro']+$row['mercatino_oro'];
                                  $sqli = "UPDATE mercatino set gems=$gemme, gold=$oro where acctid = '".$row['acctid']."'";
                               }
                               db_query($sqli);
                               $sql = "UPDATE oggetti SET dove=0,acctid=0,mercatino_gemme=0,mercatino_oro=0 WHERE id_oggetti=$_GET[id]";
                               db_query($sql) or die(db_error(LINK));
                               $session['user']['gems'] -= $row['mercatino_gemme'];
                               $session['user']['gold'] -= $row['mercatino_oro'];

                           } else {
                               output ("`7Oberon ti guarda torvo e dice: \"`&Dove pensi di mettere quest'oggetto? Ne stai già utilizzando
                               uno e anche lo zaino è occupato!! Prima fai un po' di spazio e poi
                               potremmo discutere nuovamente dell'acquisto che intendevi effettuare. Sempre che tu possa spendere quella cifra...`7\"`0`n`n");
                           }
                       } else {
                           output ("\"`&Questo oggetto non è più disponibile, qualche altro cliente è stato più veloce di te e lo ha acquistato.`0\" Ti dice sorridendo Oberon`0`n`n");
                       }
                   } else {
                       output ("\"`&Questo oggetto non è più disponibile, qualche altro cliente è stato più veloce di te e lo ha acquistato.`0\" Ti dice sorridendo Oberon`0`n`n");
                   }
               }
            }
         }
     } // Maximus Fine
     addnav("T?Torna all'Ingresso", "fabbro_mercatino.php");
} // end "comprao"
if ($_GET[op] == "ritira") {
       if ($_GET['id'] == null) {
           $sql = "SELECT * FROM oggetti WHERE dove = 7 AND acctid='".$session['user']['acctid']."' ORDER BY livello DESC,nome DESC";
           output("`c`b`&I tuoi oggetti attualmente esposti`b`c`n");
           output("<table cellspacing=0 cellpadding=2 align='center'><tr>
                 <td>&nbsp;</td><td>`b`@Nome`b</td>
                 <td>`b`#Livello`b</td>
                 <td>`b`&Costo`b</td>
                 <td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td>
                 <td>`b`@HP`b</td><td>&nbsp;</td>
                 <td>`b`\$Att.`b</td><td>&nbsp;</td>
                 <td>`b`^Dif.`b</td><td>&nbsp;</td>
                 <td>`b`%Potenz.`b</td><td>&nbsp;</td>
                 <td>`b`^Oro/g.`b</td><td>&nbsp;</td>
                 <td>`b`&Gem/g.`b</td>",true);
           if ($session['user']['superuser'] > 2) {
           output("<td>`b`#ID`b</td>",true);
       }
       output("</tr>", true);
       $result = db_query($sql) or die(db_error(LINK));
       if (db_num_rows($result) == 0) {
             output("<tr><td colspan=20 align='center'>`&Non hai nessun oggetto in esposizione!`0</td></tr>", true);
       }
       $countrow = db_num_rows($result);
       for ($i=0; $i<$countrow; $i++){
       //for ($i = 0;$i < db_num_rows($result);$i++) {
             $row = db_fetch_assoc($result);
             output("<tr class='" . ($i % 2?"trlight":"trdark") . "'><td align=right>" . ($i + 1) . ".</td>
                     <td><A href=fabbro_mercatino.php?op=ritira&id=$row[id_oggetti]>`@".$row['nome']."</a></td>
                     <td>`b`#".$row['livello']."`b</td>
                     <td>`b`&gem:".$row['mercatino_gemme']." / oro:".$row['mercatino_oro']."`b</td>
                     <td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td>
                     <td align='center'>`b`@".$row['hp_help']."`b</td><td>&nbsp;</td>
                     <td align='center'>`b`\$".$row['attack_help']."`b</td><td>&nbsp;</td>
                     <td align='center'>`b`^".$row['defence_help']."`b</td><td>&nbsp;</td>
                     <td align='center'>`b`%".$row['potenziamenti']."`b</td><td>&nbsp;</td>
                     <td align='center'>`b`^".$row['gold_help']."`b</td><td>&nbsp;</td>
                     <td align='center'>`b`&".$row['gems_help']."`b</td>",true);
             if ($session['user']['superuser'] > 2) {
                 output("<td>`b`#".$row['id_oggetti']."`b</td>",true);
             }
             output("</tr>", true);
             addnav("", "fabbro_mercatino.php?op=ritira&id=$row[id_oggetti]");
       }
       output("</table>", true);
   } else {
       $sqlo = "SELECT * FROM oggetti WHERE id_oggetti = $_GET[id]";
       $resulto = db_query($sqlo) or die(db_error(LINK));
       $row = db_fetch_assoc($resulto);
       $sqlogg = "SELECT * FROM accounts WHERE oggetto = $_GET[id]";
       $resultogg = db_query($sqlogg) or die(db_error(LINK));
       if ($session['user']['dragonkills'] < 10) {
                $maxbuylvl = 1 + (3 * $session['user']['reincarna']);
       } else if ($session['user']['dragonkills'] >= 10 AND $session['user']['dragonkills'] < 19) {
                $maxbuylvl = 2 + (3 * $session['user']['reincarna']);
       } else if ($session['user']['dragonkills'] >= 19) {
                $maxbuylvl = 3 + (3 * $session['user']['reincarna']);
       }
       if ($session['user']['superuser'] > 1) {
                $maxbuylvl = 100;
       }
       if ($row['livello'] > $maxbuylvl AND $session['user']['zaino'] != "0") {
                output ("\"`&Purtroppo per te non puoi riprendere questo oggetto, è troppo potente`0\" Ti dice sorridendo Oberon`0`n`n");
       }
       if (db_num_rows($resultogg) == 0) {
           $sqlzai = "SELECT * FROM accounts WHERE zaino = $_GET[id]";
           $resultzai = db_query($sqlzai) or die(db_error(LINK));
           if (db_num_rows($resultzai) == 0) {
               if ($session['user']['oggetto'] == "0" AND $row['livello'] <= $maxbuylvl) {
                   output ("<BIG>`2Hai ripreso questo oggetto :`b`@" . $row['nome'] . "`b`2 e lo hai indossato.</BIG>`n`n",true);
                   debuglog("ha ripreso {$row['nome']} da Oberon indossandolo");
                   $session['user']['oggetto'] = $row[id_oggetti];
                   $sql = "UPDATE oggetti SET dove=0,acctid=0,mercatino_gemme=0,mercatino_oro=0 WHERE id_oggetti=$_GET[id]";
                   db_query($sql) or die(db_error(LINK));
                   $session['user']['attack'] += $row['attack_help'];
                   $session['user']['defence'] += $row['defence_help'];
                   $session['user']['bonusattack'] += $row['attack_help'];
                   $session['user']['bonusdefence'] += $row['defence_help'];
                   $session['user']['maxhitpoints'] += $row['hp_help'];
                   $session['user']['hitpoints'] += $row['hp_help'];
                   if ($row[usuramagica]!=0 AND $row[usuramagicamax]!=0) $session['user']['playerfights'] -= $rowo['pvp_help'];
                   if ($row[usuramagica]!=0 AND $row[usuramagicamax]!=0) $session['user']['turns'] += $row['turns_help'];
                   if ($row[usuramagica]!=0 AND $row[usuramagicamax]!=0) $session['user']['bonusfight'] += $row['turns_help'];
               } elseif ($session['user']['zaino'] == "0") {
                   output ("<BIG>`3Hai ripreso questo oggetto :`b`#" . $row['nome'] . "`b`3 e lo hai messo nello zaino.</BIG>`n`n",true);
                   debuglog("ha ripreso {$row['nome']} da Oberon e lo ha messo nello zaino");
                   $session['user']['zaino'] = $row[id_oggetti];
                   $sql = "UPDATE oggetti SET dove=0,acctid=0,mercatino_gemme=0,mercatino_oro=0 WHERE id_oggetti=$_GET[id]";
                   db_query($sql) or die(db_error(LINK));
               } elseif ($row['livello'] <= $maxbuylvl) {
                   output ("`7Oberon ti guarda torvo e dice: \"`&Dove pensi di mettere quest'oggetto? Ne stai già utilizzando
                   uno e anche lo zaino è occupato!! Prima fai un po' di spazio e poi
                   potremmo discutere nuovamente dell'oggetto che intendevi riprendere...`7\"`0`n`n");
               }
           } else {
               output ("\"`&Questo oggetto non è più disponibile, qualche altro cliente è stato più veloce di te e lo ha acquistato.`0\" Ti dice sorridendo Oberon`0`n`n");
           }
       } else {
           output ("\"`&Questo oggetto non è più disponibile, qualche altro cliente è stato più veloce di te e lo ha acquistato.`0\" Ti dice sorridendo Oberon`0`n`n");
       }
   }//Sook fine ripresa oggetti
   addnav("T?Torna all'Ingresso", "fabbro_mercatino.php");
}


page_footer();
?>