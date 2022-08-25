<?php
require_once("common.php");
require_once("common2.php");
checkday();
if ($session['user']['dragonkills'] < 10) {
   $livello = 1 + (3 * $session['user']['reincarna']);
} elseif ($session['user']['dragonkills'] >= 10 AND $session['user']['dragonkills'] < 19) {
  $livello = 2 + (3 * $session['user']['reincarna']);
} elseif ($session['user']['dragonkills'] >= 19) {
  $livello = 3 + (3 * $session['user']['reincarna']);
}
$sql = "SELECT livello FROM oggetti WHERE dove = 1 ORDER BY livello DESC LIMIT 1";
$result = db_query($sql);
$row = db_fetch_assoc($result);
$maxlevel = min($livello,$row['livello']);
//echo $livello." ".$row['livello'];
$skills = array(1 => "`\$Arti Oscure", "`%Poteri Mistici", "`^Furto", "`3Militare","`\$Seduzione","`^Tattica","`@Pelle di Roccia","`#Retorica","`%Muscoli","`3Natura","`&Clima","`^Elementalista","`6Rabbia Barbara","`5Canzoni del Bardo");

//Sook, conteggio oggetti presenti
$sqlquanti="SELECT nome FROM oggetti WHERE dove=1";
$resultquanti = db_query($sqlquanti) or die(db_error(LINK));
//Sook, cancellazione oggetti in eccesso
if (db_num_rows($resultquanti)>getsetting("Oggetti_Brax",500)) {
    //1°scaglione: oggetti senza nessun potere nè potenziamenti, esclusi gli attrezzi da lavoro
    $sqldelogg="DELETE FROM oggetti WHERE dove=1 AND potenziamenti=0 AND attack_help=0 AND defence_help=0 AND special_help=0 AND special_use_help=0 AND gold_help=0
        AND turns_help=0 AND gems_help=0 AND quest_help=0 AND hp_help=0 AND pvp_help=0 AND exp_help=0 AND heal_help=0 AND nome!='Piccone da minatore' AND nome!='Ascia da boscaiolo' AND nome!='Sega da falegname'";
    db_query($sqldelogg);
    $sqlquanti="SELECT nome FROM oggetti WHERE dove=1";
    $resultquanti = db_query($sqlquanti) or die(db_error(LINK));
    if (db_num_rows($resultquanti)>getsetting("Oggetti_Brax",500)) {
        //2°scaglione: oggetti senza potenziamenti che alzano solo attacco e difesa di poco, esclusi gli attrezzi da lavoro usati
        $sqldelogg="DELETE FROM oggetti WHERE dove=1 AND potenziamenti=0 AND (attack_help<6 OR defence_help<6) AND special_help=0 AND special_use_help=0 AND gold_help=0
            AND turns_help=0 AND gems_help=0 AND quest_help=0 AND hp_help=0 AND pvp_help=0 AND exp_help=0 AND heal_help=0 AND nome!='Piccone da minatore' AND nome!='Ascia da boscaiolo'";
        db_query($sqldelogg);
        $sqlquanti="SELECT nome FROM oggetti WHERE dove=1";
        $resultquanti = db_query($sqlquanti) or die(db_error(LINK));
        if (db_num_rows($resultquanti)>getsetting("Oggetti_Brax",500)) {
            //3°scaglione: 50% degli oggetti per ogni livello, esclusi oggetti che danno oro gemme o pvp, inclusi attrezzi da lavoro
            for ($i=1;$i<10;$i++) {
                $sql="SELECT COUNT(livello) AS quanti FROM oggetti WHERE dove=1 AND gold_help=0 AND gems_help=0 AND quest_help=0 AND hp_help=0 AND pvp_help=0
                     AND exp_help=0 AND heal_help=0 AND livello=".$i;
                $result = db_query($sql) or die(db_error(LINK));
                $row = db_fetch_assoc($result);
                $limite=intval($row['quanti']/2);
                for ($j=0;$j<$limite;$j++) {
                    $sql="SELECT id_oggetti FROM oggetti WHERE dove=1 AND gold_help=0 AND gems_help=0 AND quest_help=0 AND hp_help=0 AND pvp_help=0
                         AND exp_help=0 AND heal_help=0 AND livello=".$i;
                    $result = db_query($sql) or die(db_error(LINK));
                    $row2 = db_fetch_assoc($result);
                    $sqldelogg="DELETE FROM oggetti WHERE id_oggetti=".$row2['id_oggetti'];
                    db_query($sqldelogg);
                }
            }
        }//fine 3° scaglione
    }
}

page_header("L'emporio degli oggetti magici del nano Brax.");
addnav("Azioni");
addnav("E?Esamina gli oggetti", "emporio_mag.php?op=esamina");
for ($k = 1; $k <= $maxlevel; $k++){
    addnav("E?Esamina Oggetti (Livello ".$k.")", "emporio_mag.php?op=esamina&livello=".$k);
}
addnav("V?Valuta i tuoi oggetti", "emporio_mag.php?az=valuta");
addnav("S?Scambia oggetti", "emporio_mag.php?az=scambia");
addnav("Exit");
addnav("T?Torna al Castello", "castelexcal.php");
if ($_GET['op'] == "" and $_GET['az'] == "") {
    output("`3Brax, un vecchio nano rugoso ha un campionario di oggetti unici, di ottima fattura e spesso con ");
    output("proprietà magiche.`nNaturalmente il prezzo di questi portentosi oggetti è proporzionato alle loro ");
    output("caratteristiche e l'acquirente deve possedere certi requisiti per poterli utilizzare.`n`n`n");
    //Sook, gestione limite oggetti in vendita
    if ($session['user']['superuser']>2) {
        output("<form action='emporio_mag.php?op=modoggetti' method='POST'>",true);
        addnav("","emporio_mag.php?op=modoggetti");
        output("Numero di oggetti massimo in vendita (il gioco cercherà di cancellare gli oggetti in eccesso): <input name='quanti' value=\"".HTMLEntities2(getsetting("Oggetti_Brax",500))."\" size='5'>",true);
        output("`n`n<input type='submit' class='button' value='Salva'></form>",true);
    }
}elseif ($_GET['op']=="modoggetti"){
    savesetting("Oggetti_Brax",$_POST['quanti']);
    output("La quantità di oggetti in vendita da Brax è stata modificata");
} elseif ($_GET['op'] == "esamina"){
   if ($_GET['op1'] == ""){
      $sort="livello";
      $modo="DESC";
   }else{
      $sort=$_GET['op1'];
      if ($session['sort'] == $sort AND $session['modo'] != "ASC"){
         $modo = "ASC";
      }else{
         $modo = "DESC";
      }
      $session['sort'] = $sort;
      $session['modo'] = $modo;
   }
   if ($_GET['livello'] != ""){
       $livello = "=".$_GET['livello'];
   }else{
       $livello = "<=".$livello;
   }
   $sql="SELECT id_oggetti, nome, valore, usuramax, usuramagicamax, livello, hp_help, attack_help,
                defence_help,potenziamenti,gold_help,gems_help,usura,usuramagica
       FROM oggetti
       WHERE dove = 1
       AND livello $livello
       ORDER BY $sort $modo, nome DESC";
   //$sql = "SELECT * FROM oggetti WHERE dove = 1 AND livello $livello ORDER BY $sort $modo, nome DESC";
   //echo $sql;
   output("`c`b`&Oggetti attualmente disponibili`b`n`i(cliccando sul nome della colonna potete ordinarli per quello specifico parametro)`n");
   output("(Cliccando nuovamente sul nome della colonna li ordinerete in senso inverso)`i`c`n");
   output("<table cellspacing=0 cellpadding=2 align='center'><tr>
       <td>&nbsp;</td>
       <td><a href='emporio_mag.php?op=esamina&op1=nome&livello=".$_GET['livello']."'>`b`@Nome`b</a></td>
       <td><a href='emporio_mag.php?op=esamina&op1=livello'>`b`#Livello`b</a></td>
       <td>`b`&Costo`b</td>
       <td>&nbsp;</td>
       <td>&nbsp;</td>
       <td>&nbsp;</td>
       <td><a href='emporio_mag.php?op=esamina&op1=hp_help&livello=".$_GET['livello']."'>`b`@HP`b</a></td>
       <td>&nbsp;</td>
       <td><a href='emporio_mag.php?op=esamina&op1=attack_help&livello=".$_GET['livello']."'>`b`\$Att.`b</a></td>
       <td>&nbsp;</td>
       <td><a href='emporio_mag.php?op=esamina&op1=defence_help&livello=".$_GET['livello']."'>`b`^Dif.`b</a></td>
       <td>&nbsp;</td>
       <td><a href='emporio_mag.php?op=esamina&op1=potenziamenti&livello=".$_GET['livello']."'>`b`%Potenz.`b</a></td>
       <td>&nbsp;</td>
       <td><a href='emporio_mag.php?op=esamina&op1=gold_help&livello=".$_GET['livello']."'>`b`^Oro/g.`b</a></td>
       <td>&nbsp;</td>
       <td><a href='emporio_mag.php?op=esamina&op1=gems_help&livello=".$_GET['livello']."'>`b`&Gem/g.`b</a></td>
       <td>`b`(Usura Fisica/g.`b</td>
       <td>`b`8Usura Magica/g.`b</td>",true);
   addnav("","emporio_mag.php?op=esamina&op1=nome&livello=".$_GET['livello']);
   addnav("","emporio_mag.php?op=esamina&op1=livello");
   addnav("","emporio_mag.php?op=esamina&op1=hp_help&livello=".$_GET['livello']);
   addnav("","emporio_mag.php?op=esamina&op1=attack_help&livello=".$_GET['livello']);
   addnav("","emporio_mag.php?op=esamina&op1=defence_help&livello=".$_GET['livello']);
   addnav("","emporio_mag.php?op=esamina&op1=potenziamenti&livello=".$_GET['livello']);
   addnav("","emporio_mag.php?op=esamina&op1=gold_help&livello=".$_GET['livello']);
   addnav("","emporio_mag.php?op=esamina&op1=gems_help&livello=".$_GET['livello']);
   if ($session['user']['superuser'] > 2) {
           output("<td>`b`#ID`b</td>",true);
   }
   output("</tr>", true);
   $result = db_query($sql) or die(db_error(LINK));
   if (db_num_rows($result) == 0) {
           output("<tr><td colspan=4 align='center'>`&Non ci sono oggetti disponibili mi spiace`0</td></tr>", true);
   }
   $countrow = db_num_rows($result);
   for ($i=0; $i<$countrow; $i++){
   //for ($i = 0;$i < db_num_rows($result);$i++) {
       $row = db_fetch_assoc($result);
       $valb = $row['valore'];
       if ($row['usuramax'] > 0) {
           $valf = $row['valore']*$row['usura']/$row['usuramax'];
       } else {
           $valf = $row['valore'];
       }
       if ($row['usuramagicamax'] > 0) {
           $valm = $row['valore']*$row['usuramagica']/$row['usuramagicamax'];
       } else {
           $valm = $row['valore'];
       }
       $valore = intval((2 * $valb + 2 * $valf + $valm)/5);
       if ($row['usuramagica']==0 AND $row['usuramagicamax'] > 0) $valore=intval($valore/2);
       output("<tr class='" . ($i % 2?"trlight":"trdark") . "'><td align=right>" . ($i + 1) . ".</td>
       <td><A href=emporio_mag.php?op=$row[id_oggetti]>`@".$row['nome']."</a></td>
       <td class=\"colLtCyan\">`b".$row['livello']."`b</td>
       <td class=\"colLtWhite\">`b".$valore."`b`0</td>
       <td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td>
       <td align='center' class=\"colLtGreen\">`b".$row['hp_help']."`b</td><td>&nbsp;</td>
       <td align='center' class=\"colLtRed\">`b".$row['attack_help']."`b</td><td>&nbsp;</td>
       <td align='center' class=\"colLtYellow\">`b".$row['defence_help']."`b</td><td>&nbsp;</td>
       <td align='center' class=\"colLtMagenta\">`b".$row['potenziamenti']."`b</td><td>&nbsp;</td>
       <td align='center' class=\"colLtYellow\">`b".$row['gold_help']."`b</td><td>&nbsp;</td>
       <td align='center' class=\"colLtWhite\">`b".$row['gems_help']."`b</td>",true);
       if ($row['usuramax']>0) output("<td align='center' class=\"colLtOrange\">`b".(intval(100-(100*$row['usura']/$row['usuramax'])))."`b</td>",true);
       else output("<td align='center' class=\"colLtOrange\">`bnon soggetto`b</td>",true);
       if ($row['usuramagicamax']>0) output("<td align='center' class=\"colDKOrange\">`b".(intval(100-(100*$row['usuramagica']/$row['usuramagicamax'])))."`b</td>",true);
       else output("<td align='center' class=\"colDKOrange\">`bnon soggetto`b</td>",true);
       if ($session['user']['superuser'] > 2) {
               output("<td>`b`#".$row['id_oggetti']."`b</td>",true);
       }
       output("</tr>", true);
       addnav("", "emporio_mag.php?op=$row[id_oggetti]");
   }
   output("</table>", true);
} elseif ($_GET['az'] == "") {
    output ("ID Oggetto : " . $_GET['op'] . "`n`n");
    $sqlo = "SELECT * FROM oggetti WHERE id_oggetti = ".$_GET['op']." ORDER BY livello DESC,nome DESC";
    $resulto = db_query($sqlo) or die(db_error(LINK));
    $row = db_fetch_assoc($resulto);
    $valb = $row['valore'];
    if ($row['usuramax'] > 0) {
        $valf = $row['valore']*$row['usura']/$row['usuramax'];
    } else {
        $valf = $row['valore'];
    }
    if ($row['usuramagicamax'] > 0) {
        $valm = $row['valore']*$row['usuramagica']/$row['usuramagicamax'];
    } else {
        $valm = $row['valore'];
    }
    $valore = intval((2 * $valb + 2 * $valf + $valm)/5);
    if ($row['usuramagica']==0 AND $row['usuramagicamax'] > 0) $valore=intval($valore/2);
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
    if ($row['usuramax']>0) output ("<tr><td>`)Usura fisica:</td><td>`(".(intval(100-(100*$row['usura']/$row['usuramax'])))."</td></tr>", true);
    else output ("<tr><td>`)Usura fisica:</td><td>`(Non Soggetto</td></tr>", true);
    if ($row['usuramagicamax']>0) output ("<tr><td>`)Usura magica:</td><td>`8".(intval(100-(100*$row['usuramagica']/$row['usuramagicamax'])))."</td></tr>", true);
    else output ("<tr><td>`)Usura magica:</td><td>`8Non Soggetto</td></tr>", true);
    //output ("<tr><td><A href=gestione_mag.php?az=usa&oid=$rowo[id_oggetti]>Usa </a> `#potere oggetto.</td></tr>", true);
    output ("<tr><td>  </td><td>  </td></tr>", true);
    output ("<tr><td>`7Valore in Gemme: </td><td>`&".$valore."</td></tr>", true);
    output ("<tr><td>  </td><td>  </td></tr>", true);
    output ("</table>", true);
    /*output ("`@Oggetto :".$rowo['nome']." (`^".$rowo['descrizione'].")`0"
    output ("Oggetto :" . $row['nome'] . "`n`n");
    output ("Descrizione:" . $row['descrizione'] . "`n`n");
    output ("Livello:" . $row['livello'] . "`n`n");
    output ("Valore in gemme:" . $row['valore'] . "`n`n");
    output("<form action='emporio_mag.php?az=compra&oggettoid=$_GET[op]' method='POST'>", true);
    addnav("", "emporio_mag.php?az=compra&oggettoid=$_GET[op]");
    */
    output("<form action='emporio_mag.php?az=compra&oggettoid=$_GET[op]' method='POST'>", true);
    addnav("", "emporio_mag.php?az=compra&oggettoid=$_GET[op]");
    output("<input type='submit' class='button' value='Compra'>", true);
    // showform($userinfo,$row);
    output("</form>", true);
}
if ($_GET['az'] == "compra") {
   if ($session['user']['level'] > 4) {
      $sqlo = "SELECT * FROM oggetti WHERE id_oggetti = ".$_GET['oggettoid'];
      $resulto = db_query($sqlo) or die(db_error(LINK));
      $row = db_fetch_assoc($resulto);
      $valb = $row['valore'];
      if ($row['usuramax'] > 0) {
          $valf = $row['valore']*$row['usura']/$row['usuramax'];
      } else {
          $valf = $row['valore'];
      }
      if ($row['usuramagicamax'] > 0) {
          $valm = $row['valore']*$row['usuramagica']/$row['usuramagicamax'];
      } else {
          $valm = $row['valore'];
      }
      $valore = intval((2 * $valb + 2 * $valf + $valm)/5);
      if ($row['usuramagica']==0 AND $row['usuramagicamax'] > 0) $valore=intval($valore/2);
      if ($valore>$session['user']['gems']) {
         output ("Non hai abbastanza gemme!");
      }else{
         $sqlogg = "SELECT * FROM accounts WHERE oggetto = ".$_GET['oggettoid'];
         $resultogg = db_query($sqlogg) or die(db_error(LINK));
         if ($session['user']['dragonkills'] < 10) {
            $maxbuylvl = 1 + (3 * $session['user']['reincarna']);
         } elseif ($session['user']['dragonkills'] >= 10 AND $session['user']['dragonkills'] < 19) {
           $maxbuylvl = 2 + (3 * $session['user']['reincarna']);
         } elseif ($session['user']['dragonkills'] >= 19) {
           $maxbuylvl = 3 + (3 * $session['user']['reincarna']);
         }
         if ($session['user']['superuser'] > 1) {
            $maxbuylvl = 100;
         }
         if ($row['livello'] > $maxbuylvl) {
            output ("Questo oggetto è troppo potente per te.`n`n");
         }else{
           if (db_num_rows($resultogg) == 0) {
              $sqlzai = "SELECT * FROM accounts WHERE zaino = ".$_GET['oggettoid'];
              $resultzai = db_query($sqlzai) or die(db_error(LINK));
              if (db_num_rows($resultzai) == 0) {
                 if ($session['user']['oggetto'] == "0") {
                     output ("<BIG>`2Hai comprato questo oggetto :`b`@" . $row['nome'] . "`b`2 e lo hai indossato.</BIG>`n`n",true);
                     debuglog("ha comprato ".$row['nome']." da Brax per ".$row['valore']." gemme indossandolo");
                     $session['user']['oggetto'] = $row['id_oggetti'];
                     $sql = "UPDATE oggetti SET dove=0 WHERE id_oggetti=".$_GET['oggettoid'];
                     db_query($sql) or die(db_error(LINK));
                     $session['user']['attack'] += $row['attack_help'];
                     $session['user']['defence'] += $row['defence_help'];
                     $session['user']['bonusattack'] += $row['attack_help'];
                     $session['user']['bonusdefence'] += $row['defence_help'];
                     $session['user']['maxhitpoints'] += $row['hp_help'];
                     $session['user']['hitpoints'] += $row['hp_help'];
                     if ($row['usuramagica']!=0 AND $row['usuramagicamax']!=0) $session['user']['turns'] += $row['turns_help'];
                     if ($row['usuramagica']!=0 AND $row['usuramagicamax']!=0) $session['user']['bonusfight'] += $row['turns_help'];
                     if ($row['usuramagica']!=0 AND $row['usuramagicamax']!=0) $session['user']['playerfights'] += $row['pvp_help'];
                     $session['user']['gems'] -= $valore;
                 } elseif ($session['user']['zaino'] == "0") {
                     output ("<BIG>`3Hai comprato questo oggetto :`b`#" . $row['nome'] . "`b`3 e lo hai messo nello zaino.</BIG>`n`n",true);
                     debuglog("ha comprato ".$row['nome']." da Brax per ".$row['valore']." gemme e lo ha messo nello zaino");
                     $session['user']['zaino'] = $row['id_oggetti'];
                     $sql = "UPDATE oggetti SET dove=0 WHERE id_oggetti=".$_GET['oggettoid'];
                     db_query($sql) or die(db_error(LINK));
                     $session['user']['gems'] -= $valore;
                 } else {
                     output ("`2Brax ti guarda torvo e dice: \"`5Dove pensi di mettere quest'oggetto? Stai già utilizzando
                     uno dei miei oggetti e anche lo zaino è occupato!! Prima rivendi uno dei due oggetti che hai e poi
                     potremo discutere nuovamente dell'acquisto che intendevi effettuare.`2\"`0`n`n");
                 }
              } else {
                 output ("`4Questo oggetto non è disponibile, qualche altro cliente lo ha già acquistato.`0`n`n");
              }
           } else {
              output ("`4Questo oggetto non è disponibile, qualche altro cliente lo ha già acquistato.`0`n`n");
           }
         }
      }
   } else {
      output("`n`0\"`\$Mi spiace, ma devi essere di livello 5 o superiore per comprare i miei oggetti`0\" ti dice Brax`n");
   }
}elseif ($_GET['az'] == "valuta") {
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
    $valvendob = $rowo['valore'];
    if ($rowo['usuramax'] > 0) {
        $valvendof = $rowo['valore']*$rowo['usura']/$rowo['usuramax'];
    } else {
        $valvendof = $rowo['valore'];
    }
    if ($rowo['usuramagicamax'] > 0) {
        $valvendom = $rowo['valore']*$rowo['usuramagica']/$rowo['usuramagicamax'];
    } else {
        $valvendom = $rowo['valore'];
    }
    $valvendo = intval((2 * $valvendob + 2 * $valvendof + $valvendom)/10);
    if ($rowo['usuramagica']==0 AND $rowo['usuramagicamax'] > 0) $valvendo=intval($valvendo/2);

    $valvendzb = $rowz['valore'];
    if ($rowz['usuramax'] > 0) {
        $valvendzf = $rowz['valore']*$rowz['usura']/$rowz['usuramax'];
    } else {
        $valvendzf = $rowz['valore'];
    }
    if ($rowz['usuramagicamax'] > 0) {
        $valvendzm = $rowz['valore']*$rowz['usuramagica']/$rowz['usuramagicamax'];
    } else {
        $valvendzm = $rowz['valore'];
    }
    $valvendz = intval((2 * $valvendzb + 2 * $valvendzf + $valvendzm)/10);
    if ($rowz['usuramagica']==0 AND $rowz['usuramagicamax'] > 0) $valvendz=intval($valvendz/2);

    output ("`@Possiedi questi oggetti.`n`n");
    output ("<table>", true);
    output ("<tr><td>`3Sei equipaggiato con : </td><td>`#`b" . $ogg . "`b</td><td>&nbsp;</td>",true);
    if ($rowo[dove]!=30) {
        addnav("", "emporio_mag.php?az=vendio&oid=".$rowo['id_oggetti']);
        output ("<td><A href=emporio_mag.php?az=vendio&oid=".$rowo['id_oggetti'].">`bVendi`b</a>`3 per `#`b$valvendo `b`3gemme.</td></tr>", true);
    } else {
        output ("<td>Gli artefatti non possono essere venduti!</td></tr>", true);
    }
    output ("<tr><td>`6Nello zaino hai : </td><td>`b`^" . $zai . "`b</td><td>&nbsp;</td>",true);
    if ($rowz[dove]!=30) {
        addnav("", "emporio_mag.php?az=vendiz&zid=".$rowz['id_oggetti']);
        output ("<td><A href=emporio_mag.php?az=vendiz&zid=".$rowz['id_oggetti'].">`bVendi`b</a>`6 per `^`b$valvendz `b`6gemme.</td></tr>", true);
    } else {
        output ("<td>Gli artefatti non possono essere venduti!</td></tr>", true);
    }
    output ("</table>", true);
} elseif ($_GET['az'] == "vendio") {
   $rnd = e_rand(1,100);
   if ($rnd>70){
      $sql = "SELECT id_oggetti FROM oggetti WHERE dove=1 ORDER BY RAND() LIMIT 1";
      $result = db_query($sql) or die(db_error(LINK));
      $row = db_fetch_assoc($result);
      $sqle = "DELETE FROM oggetti WHERE id_oggetti='".$row['id_oggetti']."'";
      db_query($sqle);
   }
   if ($session['user']['level'] < 6) {
      output ("`\$Non puoi vendere oggetti magici prima di battere il quinto maestro.`0`n`n");
   } else {
     if (!$_GET['oid']) {
        output ("\"`6Vuoi fare il furbo?`0\" Ti dice torvo Brax.\"`4Non hai nulla da vendere!!`0\"`n`n");
     } else {
       $sqlo = "SELECT * FROM oggetti WHERE id_oggetti = ".$_GET['oid'];
       $resulto = db_query($sqlo) or die(db_error(LINK));
       $rowo = db_fetch_assoc($resulto);
       $valb = $rowo['valore'];
       if ($rowo['usuramax'] > 0) {
          $valf = $rowo['valore']*$rowo['usura']/$rowo['usuramax'];
       } else {
         $valf = $rowo['valore'];
       }
       if ($rowo['usuramagicamax'] > 0) {
          $valm = $rowo['valore']*$rowo['usuramagica']/$rowo['usuramagicamax'];
       } else {
         $valm = $rowo['valore'];
       }
       $valore = intval((2 * $valb + 2 * $valf + $valm)/10);
       if ($rowo['usuramagica']==0 AND $rowo['usuramagicamax'] > 0) $valore=intval($valore/2);
       output ("<BIG>`6Hai venduto l'oggetto equipaggiato a Brax per `^`b$valore`6`b gemme.</BIG>`n`n",true);
       debuglog("ha venduto ".$rowo['nome']." a Brax per $valore gemme");
       $sql = "UPDATE oggetti SET dove='1' WHERE id_oggetti=".$_GET['oid'];
       db_query($sql) or die(db_error(LINK));
       $session['user']['oggetto'] = 0;
       $session['user']['attack'] -= $rowo['attack_help'];
       $session['user']['defence'] -= $rowo['defence_help'];
       $session['user']['bonusattack'] -= $rowo['attack_help'];
       $session['user']['bonusdefence'] -= $rowo['defence_help'];
       $session['user']['maxhitpoints'] -= $rowo['hp_help'];
       $session['user']['hitpoints'] -= $rowo['hp_help'];
       if ($rowo['usuramagica']!=0 AND $rowo['usuramagicamax']!=0) $session['user']['playerfights'] -= $rowo['pvp_help'];
       if ($rowo['usuramagica']!=0 AND $rowo['usuramagicamax']!=0) $session['user']['turns'] -= $rowo['turns_help'];
       if ($rowo['usuramagica']!=0 AND $rowo['usuramagicamax']!=0) $session['user']['bonusfight'] -= $rowo['turns_help'];
       $session['user']['gems'] += $valore;
     }
   }
} elseif ($_GET['az'] == "vendiz") {
  if ($session['user']['level'] < 6 ) {
     output ("Non puoi vendere oggetti magici prima di sconfiggere il quinto maestro.`n`n");
  } else {
    if (!$_GET['zid']) {
       output ("\"`6Vuoi fare il furbo?`0\" Ti dice torvo Brax.\"`4Non hai nulla da vendere!!`0\"`n`n");
    } else {
      $sqlz = "SELECT * FROM oggetti WHERE id_oggetti = ".$_GET['zid'];
      $resultz = db_query($sqlz) or die(db_error(LINK));
      $rowz = db_fetch_assoc($resultz);
      $valb = $rowz['valore'];
      if ($rowz['usuramax'] > 0) {
      $valf = $rowz['valore']*$rowz['usura']/$rowz['usuramax'];
      } else {
      $valf = $rowz['valore'];
      }
      if ($rowz['usuramagicamax'] > 0) {
      $valm = $rowz['valore']*$rowz['usuramagica']/$rowz['usuramagicamax'];
      } else {
      $valm = $rowz['valore'];
      }
      $valore = intval((2 * $valb + 2 * $valf + $valm)/10);
      if ($rowz['usuramagica']==0 AND $rowz['usuramagicamax'] > 0) $valore=intval($valore/2);

      output ("<BIG>`6Hai venduto l'oggetto che avevi nello zaino a Brax per `b`^$valore`b`6 gemme.</BIG>`n`n",true);
      debuglog("ha venduto ".$rowz['nome']." a Brax per $valore gemme");
      //$sql = "UPDATE oggetti SET dove='$rowz[dove_origine]' WHERE id_oggetti=$_GET[zid]";
      $sql = "UPDATE oggetti SET dove='1' WHERE id_oggetti=".$_GET['zid'];
      db_query($sql) or die(db_error(LINK));
      $session['user']['zaino'] = 0;
      $session['user']['gems'] += $valore;
    }
  }
} elseif ($_GET['az'] == "scambia") {
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
     } elseif (!$session['user']['zaino']) {
       output ("Non hai nulla nello zaino.`n`n");
     } else {
       output ("Hai invertito l'oggetto equipaggiato con quello nello zaino.`n`n");
       $session['user']['attack'] -= $rowo['attack_help'];
       $session['user']['defence'] -= $rowo['defence_help'];
       $session['user']['bonusattack'] -= $rowo['attack_help'];
       $session['user']['bonusdefence'] -= $rowo['defence_help'];
       $session['user']['maxhitpoints'] -= $rowo['hp_help'];
       $session['user']['hitpoints'] -= $rowo['hp_help'];
       //if ($rowo[usuramagica]!=0) $session['user']['turns'] = $session['user']['turns'] - $rowo[turns_help];
       if ($rowo['usuramagica']!=0 AND $rowo['usuramagicamax']!=0) $session['user']['bonusfight'] -= $rowo['turns_help'];

       $deposito = $session['user']['oggetto'];
       $session['user']['oggetto'] = $session['user']['zaino'];
       $session['user']['zaino'] = $deposito;

       $session['user']['attack'] += $rowz['attack_help'];
       $session['user']['defence'] += $rowz['defence_help'];
       $session['user']['bonusattack'] += $rowz['attack_help'];
       $session['user']['bonusdefence'] += $rowz['defence_help'];
       $session['user']['maxhitpoints'] += $rowz['hp_help'];
       $session['user']['hitpoints'] += $rowz['hp_help'];
       //if ($rowz[usuramagica]!=0) $session['user']['turns'] = $session['user']['turns'] + $rowz[turns_help];
       if ($rowz['usuramagica']!=0 AND $rowz['usuramagicamax']!=0) $session['user']['bonusfight'] += $rowz['turns_help'];

       if ($session['user']['hitpoints'] <1) $session['user']['hitpoints'] = 1;
     }
  }else {
        output("Purtroppo il livello dell'oggetto nello zaino è troppo alto per te, l'unica cosa che puoi fare è venderlo.");
  }
}
page_footer();
?>