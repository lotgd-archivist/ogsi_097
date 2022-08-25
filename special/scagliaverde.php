<?php
/**
* Taverna nella foresta
*
* @version 0.1
* @author Teg lotgd@mentat.it
*/

//***************************************//
//                TOKI                   //
//***************************************//
//require_once("common.php");

page_header("La Taverna della Scaglia Verde Fritta");
$valori = array(31,32,41,42,43,51,52,53,54,61,62,63,64,65,11,22,33,44,55,66,21); // Valori Toki
$occorrenze = array(2,2,2,2,2,2,2,2,2,2,2,2,2,2,1,1,1,1,1,1,2); // Occorrenze dei Valori Toki su 36
// Calcola le probabilita' su 36 di battere un tiro
function getTokiWinChance($val){
    global $valori, $occorrenze;
    $pos = getTokiValuePosition($val);
    $res = 0;
    for($i = $pos; $i < count($valori); $i++){
        $res += $occorrenze[$i];
    }
    return $res;
}

// Effettua un lancio di dadi per Toki
function tokiRoll(){
    $dado1 = e_rand(1,6);
    $dado2 = e_rand(1,6);
    $risultato = max($dado1,$dado2)*10 + min($dado1,$dado2);

    return $risultato;
}

// Restituisce la posizione di un risultato nell'array di valori
function getTokiValuePosition($val){
    global $valori;
    $res = -1;
    for($i = 0; $i < count($valori); $i++){
        $tmp = $valori[$i];
        if(strcmp($val."",$tmp."") == 0){
            $res = $i;
        }
    }
    return $res;
}

// Verifica se il valore passato e' Toki
function isToki($val){
    global $valori;
    if(getTokiValuePosition($val) == (count($valori)-1)){
        $res = true;
    } else {
        $res = false;
    }
    return $res;
}

// Trasforma in stringa un tiro
function tokiToString($val){
    if(isToki($val)){
        $res = "`b`@Toki`b";
    } else {
        $res = "".$val;
    }
    return $res;
}

// Calcola un numero casuale maggiore uguale a quello passato
function fakeTokiRoll($val){
    global $valori;
    //output("`n[DEBUG] Fake Roll Begin for value ".$val);
    $scala = count($valori);
    //output("`n[DEBUG] Scala ".$scala);
    $pos = getTokiValuePosition($val);
    //output("`n[DEBUG] Pos ".$pos);
    $basePos = e_rand(0,$scala-1);
    //output("`n[DEBUG] BasePos ".$basePos);
    if($basePos >= $pos){
        $newPos = $basePos;
    } else {
        $newPos = ((($scala-$pos) * $basePos) / $scala) + $pos;
    }
    //output("`n[DEBUG] NewPos ".$newPos);
    //output("`n[DEBUG] Fake Roll End ".$valori[$newPos]);
    return $valori[$newPos];
}

// Scrive la tabella dei risultati possibili
function writeTokiTable(){
    output("<table border='0' cellpadding='0' align='center'>",true);
    output("<tr class='trlight' align='center'><td colspan='6'><strong><span class='colLtGreen'>21 (Toki)</span></strong></td></tr>",true);
    output("<tr class='trdark' align='center'>",true);
    output("    <td><span class='colLtCyan'>66</span></td>",true);
    output("    <td><span class='colLtCyan'>55</span></td>",true);
    output("    <td><span class='colLtCyan'>44</span></td>",true);
    output("    <td><span class='colLtCyan'>33</span></td>",true);
    output("    <td><span class='colLtCyan'>22</span></td>",true);
    output("    <td><span class='colLtCyan'>11</span></td>",true);
    output("</tr>",true);
    output("<tr class='trlight' align='center'>",true);
    output("    <td>&nbsp;</td>",true);
    output("    <td><span class='colDkCyan'>65</span></td>",true);
    output("    <td><span class='colDkCyan'>64</span></td>",true);
    output("    <td><span class='colDkCyan'>63</span></td>",true);
    output("    <td><span class='colDkCyan'>62</span></td>",true);
    output("    <td><span class='colDkCyan'>61</span></td>",true);
    output("</tr>",true);
    output("<tr class='trdark' align='center'>",true);
    output("    <td>&nbsp;</td>",true);
    output("    <td>&nbsp;</td>",true);
    output("    <td><span class='colLtMagenta'>54</span></td>",true);
    output("    <td><span class='colLtMagenta'>53</span></td>",true);
    output("    <td><span class='colLtMagenta'>52</span></td>",true);
    output("    <td><span class='colLtMagenta'>51</span></td>",true);
    output("</tr>",true);
    output("<tr class='trlight' align='center'>",true);
    output("    <td>&nbsp;</td>",true);
    output("    <td>&nbsp;</td>",true);
    output("    <td>&nbsp;</td>",true);
    output("    <td><span class='colLtYellow'>43</span></td>",true);
    output("    <td><span class='colLtYellow'>42</span></td>",true);
    output("    <td><span class='colLtYellow'>41</span></td>",true);
    output("</tr>",true);
    output("<tr class='trdark' align='center'>",true);
    output("    <td>&nbsp;</td>",true);
    output("    <td>&nbsp;</td>",true);
    output("    <td>&nbsp;</td>",true);
    output("    <td>&nbsp;</td>",true);
    output("    <td><span class='colLtWhite'>32</span></td>",true);
    output("    <td><span class='colLtWhite'>31</span></td>",true);
    output("</tr>",true);
    output("</table>",true);
}
//***************************************//
//               END TOKI                //
//***************************************//

//***************************************//
//                TALI                   //
//      by Maximus (www.ogsi.it)         //
//***************************************//
// Simula il lancio di dadi a 4 facce sostituendoli con i risultati di Tali
function taliRoll() {
  $result = intval(e_rand(1,4));
  if ($result==4) $result=6;
  if ($result==3) $result=4;
  if ($result==2) $result=3;

  return $result;
}

// Ordina l'array dei risultati
function sortTaliResult($dice) {
  for ($x=0;$x<count($dice);$x++){
    for ($y=$x+1;$y<count($dice);$y++){
      if ($dice[$x]<$dice[$y]) {
        $appo = $dice[$x];
        $dice[$x] = $dice[$y];
        $dice[$y] = $appo;
      }
    }
  }
  return $dice;
}

// Scrive le sensazioni di Maximus riferite al lancio di dado
function checkTaliResult($gamer,$dice) {
  // $gamer == 1 --> Player
  // $gamer == 2 --> Maximus
  if ($gamer == 1) {
     output("`nMaximus guarda il tuo lancio e dice:`n");
  } else {
     output("`nMaximus guarda il suo lancio e dice:`n");
  }

  $out = "";

  $out = (" \"`#Gli antichi non attribuivano nessun nome a questo risultato...`7\"");

  if ($dice[0] == 6) {
      $out = (" \"`#Gli antichi attribuivano a questo risultato il nome di `@Senio`#...`7\"");
  }

  if ($dice[0] == $dice[1] && $dice[1] == $dice[2] && $dice[2] == $dice[3]) {
      if ($dice[0] == 1) {
         $out = (" \"`#Thò! Il risultato del `@Cane`#!`7\"");
         if ($gamer == 2) {
            $out = $out.("`nIl suo volto improvvisamente si rabbuia.`n");
         } else {
            $out = $out.("`nUn ampio sorriso gli si forma in volto.`n");
         }
      } else {
         $out = (" \"`#Gli antichi attribuivano a questo risultato il nome di `@Vultures`#...`7\"`n");
      }

  }

  if ($dice[0] == 6 && $dice[1] == 4 && $dice[2] == 3 && $dice[3] == 1) {
         $out = (" \"`#Se ognuno con diverse facce sortirai, che splendido regalo, esclamerai...`7\"`n");
         $out = $out.(" \"`#Fortuna sfacciata! Gli antichi attribuivano a questo risultato il nome di `@Venus`#, impossibile da battere...`7\"");
         if ($gamer == 1) {
            $out = $out.("`nIl suo volto improvvisamente si rabbuia.`n");
         } else {
            $out = $out.("`nUn ampio sorriso gli si forma in volto.`n");
         }
  }

  output($out);

}

// Funzione che restituisce il valore del lancio
function getTaliValue($dicePlayer) {
  $result = 0;
  if ($dicePlayer[0]==1 && $dicePlayer[1]==1 && $dicePlayer[2]==1 && $dicePlayer[3]==1) {
      $result = 1;
  }
  if ($dicePlayer[0]==3 && $dicePlayer[1]==3 && $dicePlayer[2]==3 && $dicePlayer[3]==3) {
      $result = 2;
  }
  if ($dicePlayer[0]==4 && $dicePlayer[1]==4 && $dicePlayer[2]==4 && $dicePlayer[3]==4) {
      $result = 3;
  }
  if ($dicePlayer[0]==6 && $dicePlayer[1]==6 && $dicePlayer[2]==6 && $dicePlayer[3]==6) {
      $result = 4;
  }
  if ($dicePlayer[0]==3 && $dicePlayer[1]==1 && $dicePlayer[2]==1 && $dicePlayer[3]==1) {
      $result = 5;
  }
  if ($dicePlayer[0]==4 && $dicePlayer[1]==1 && $dicePlayer[2]==1 && $dicePlayer[3]==1) {
      $result = 6;
  }
  if ($dicePlayer[0]==3 && $dicePlayer[1]==3 && $dicePlayer[2]==1 && $dicePlayer[3]==1) {
      $result = 7;
  }
  if ($dicePlayer[0]==4 && $dicePlayer[1]==3 && $dicePlayer[2]==1 && $dicePlayer[3]==1) {
      $result = 8;
  }
  if ($dicePlayer[0]==3 && $dicePlayer[1]==3 && $dicePlayer[2]==3 && $dicePlayer[3]==1) {
      $result = 9;
  }
  if ($dicePlayer[0]==4 && $dicePlayer[1]==4 && $dicePlayer[2]==1 && $dicePlayer[3]==1) {
      $result = 10;
  }
  if ($dicePlayer[0]==4 && $dicePlayer[1]==3 && $dicePlayer[2]==3 && $dicePlayer[3]==1) {
      $result = 11;
  }
  if ($dicePlayer[0]==4 && $dicePlayer[1]==4 && $dicePlayer[2]==3 && $dicePlayer[3]==1) {
      $result = 12;
  }
  if ($dicePlayer[0]==4 && $dicePlayer[1]==4 && $dicePlayer[2]==4 && $dicePlayer[3]==1) {
      $result = 13;
  }
  if ($dicePlayer[0]==4 && $dicePlayer[1]==4 && $dicePlayer[2]==3 && $dicePlayer[3]==3) {
      $result = 14;
  }
  if ($dicePlayer[0]==6 && $dicePlayer[1]==6 && $dicePlayer[2]==1 && $dicePlayer[3]==1) {
      $result = 15;
  }
  if ($dicePlayer[0]==4 && $dicePlayer[1]==4 && $dicePlayer[2]==4 && $dicePlayer[3]==3) {
      $result = 16;
  }
  if ($dicePlayer[0]==6 && $dicePlayer[1]==6 && $dicePlayer[2]==3 && $dicePlayer[3]==1) {
      $result = 17;
  }
  if ($dicePlayer[0]==6 && $dicePlayer[1]==6 && $dicePlayer[2]==4 && $dicePlayer[3]==1) {
      $result = 18;
  }
  if ($dicePlayer[0]==6 && $dicePlayer[1]==6 && $dicePlayer[2]==3 && $dicePlayer[3]==3) {
      $result = 19;
  }
  if ($dicePlayer[0]==6 && $dicePlayer[1]==6 && $dicePlayer[2]==4 && $dicePlayer[3]==3) {
      $result = 20;
  }
  if ($dicePlayer[0]==6 && $dicePlayer[1]==6 && $dicePlayer[2]==6 && $dicePlayer[3]==1) {
      $result = 21;
  }
  if ($dicePlayer[0]==6 && $dicePlayer[1]==6 && $dicePlayer[2]==4 && $dicePlayer[3]==4) {
      $result = 22;
  }
  if ($dicePlayer[0]==6 && $dicePlayer[1]==6 && $dicePlayer[2]==6 && $dicePlayer[3]==3) {
      $result = 23;
  }
  if ($dicePlayer[0]==6 && $dicePlayer[1]==6 && $dicePlayer[2]==6 && $dicePlayer[3]==4) {
      $result = 24;
  }
  if ($dicePlayer[0]==6 && $dicePlayer[1]==4 && $dicePlayer[2]==3 && $dicePlayer[3]==1) {
      $result = 25;
  }
  return $result;
}

// Funzione che restituisce il vincitore
function winnerTali($diceOne,$diceTwo) {
         // $result == 0 --> Pareggio
         // $result == 1 --> vincitore $diceOne
         // $result == 2 --> vincitore $diceTwo
  $result = 0;

  $punteggioOne = getTaliValue($diceOne);
  $punteggioTwo = getTaliValue($diceTwo);

  if ($punteggioOne > $punteggioTwo) {
      $result = 1;
  } else {
      $result = 2;
  }
  if ($punteggioOne == $punteggioTwo) {
      $result = 0;
  }
  return $result;
}

// Funzione che restituisce il risultato migliore su tre lanci
function getMaxTaliRoll($dice1,$dice2,$dice3) {

  $punteggioOne = getTaliValue($dice1);
  $punteggioTwo = getTaliValue($dice2);
  $punteggioThree = getTaliValue($dice3);

  if ($punteggioOne >= $punteggioTwo) {
      if ($punteggioOne >= $punteggioThree) {
          $result = $dice1;
      } else {
          $result = $dice3;
      }

  }

  if ($punteggioTwo >= $punteggioThree) {
      $result = $dice2;
  } else {
      $result = $dice3;
  }

  return $result;
}

// Scrive la tabella dei risultati possibili di tali
function writeTaliTable(){
    output("<table border='0' cellpadding='0' align='center'>",true);
    output("<tr class='trlight' align='center'>",true);
    output("    <td colspan=5><span class='colDkCyan'>IL TALI</span></td>",true);
    output("</tr>",true);
    output("<tr class='trdark' align='center'>",true);
    output("    <td><span class='colLtCyan'>&nbsp;6&nbsp;</span></td>",true);
    output("    <td><span class='colLtCyan'>&nbsp;4&nbsp;</span></td>",true);
    output("    <td><span class='colLtCyan'>&nbsp;3&nbsp;</span></td>",true);
    output("    <td><span class='colLtCyan'>&nbsp;1&nbsp;</span></td>",true);
    output("    <td><span class='colLtCyan'>&nbsp;Venus&nbsp;</span></td>",true);
    output("</tr>",true);
    output("<tr class='trlight' align='center'>",true);
    output("    <td><span class='colDkCyan'>&nbsp;6&nbsp;</span></td>",true);
    output("    <td><span class='colDkCyan'>&nbsp;6&nbsp;</span></td>",true);
    output("    <td><span class='colDkCyan'>&nbsp;6&nbsp;</span></td>",true);
    output("    <td><span class='colDkCyan'>&nbsp;4&nbsp;</span></td>",true);
    output("    <td><span class='colDkCyan'>&nbsp;Senio&nbsp;</span></td>",true);
    output("</tr>",true);
    output("<tr class='trdark' align='center'>",true);
    output("    <td><span class='colLtCyan'>&nbsp;6&nbsp;</span></td>",true);
    output("    <td><span class='colLtCyan'>&nbsp;6&nbsp;</span></td>",true);
    output("    <td><span class='colLtCyan'>&nbsp;6&nbsp;</span></td>",true);
    output("    <td><span class='colLtCyan'>&nbsp;3&nbsp;</span></td>",true);
    output("    <td><span class='colLtCyan'>&nbsp;Senio&nbsp;</span></td>",true);
    output("</tr>",true);
    output("<tr class='trlight' align='center'>",true);
    output("    <td><span class='colDkCyan'>&nbsp;6&nbsp;</span></td>",true);
    output("    <td><span class='colDkCyan'>&nbsp;6&nbsp;</span></td>",true);
    output("    <td><span class='colDkCyan'>&nbsp;4&nbsp;</span></td>",true);
    output("    <td><span class='colDkCyan'>&nbsp;4&nbsp;</span></td>",true);
    output("    <td><span class='colDkCyan'>&nbsp;Senio&nbsp;</span></td>",true);
    output("</tr>",true);
    output("<tr class='trdark' align='center'>",true);
    output("    <td><span class='colLtCyan'>&nbsp;6&nbsp;</span></td>",true);
    output("    <td><span class='colLtCyan'>&nbsp;6&nbsp;</span></td>",true);
    output("    <td><span class='colLtCyan'>&nbsp;6&nbsp;</span></td>",true);
    output("    <td><span class='colLtCyan'>&nbsp;1&nbsp;</span></td>",true);
    output("    <td><span class='colLtCyan'>&nbsp;Senio&nbsp;</span></td>",true);
    output("</tr>",true);
    output("<tr class='trlight' align='center'>",true);
    output("    <td><span class='colDkCyan'>&nbsp;6&nbsp;</span></td>",true);
    output("    <td><span class='colDkCyan'>&nbsp;6&nbsp;</span></td>",true);
    output("    <td><span class='colDkCyan'>&nbsp;4&nbsp;</span></td>",true);
    output("    <td><span class='colDkCyan'>&nbsp;3&nbsp;</span></td>",true);
    output("    <td><span class='colDkCyan'>&nbsp;Senio&nbsp;</span></td>",true);
    output("</tr>",true);
    output("<tr class='trdark' align='center'>",true);
    output("    <td><span class='colLtCyan'>&nbsp;6&nbsp;</span></td>",true);
    output("    <td><span class='colLtCyan'>&nbsp;6&nbsp;</span></td>",true);
    output("    <td><span class='colLtCyan'>&nbsp;3&nbsp;</span></td>",true);
    output("    <td><span class='colLtCyan'>&nbsp;3&nbsp;</span></td>",true);
    output("    <td><span class='colLtCyan'>&nbsp;Senio&nbsp;</span></td>",true);
    output("</tr>",true);
    output("<tr class='trlight' align='center'>",true);
    output("    <td><span class='colDkCyan'>&nbsp;6&nbsp;</span></td>",true);
    output("    <td><span class='colDkCyan'>&nbsp;6&nbsp;</span></td>",true);
    output("    <td><span class='colDkCyan'>&nbsp;4&nbsp;</span></td>",true);
    output("    <td><span class='colDkCyan'>&nbsp;1&nbsp;</span></td>",true);
    output("    <td><span class='colDkCyan'>&nbsp;Senio&nbsp;</span></td>",true);
    output("</tr>",true);
    output("<tr class='trdark' align='center'>",true);
    output("    <td><span class='colLtCyan'>&nbsp;6&nbsp;</span></td>",true);
    output("    <td><span class='colLtCyan'>&nbsp;6&nbsp;</span></td>",true);
    output("    <td><span class='colLtCyan'>&nbsp;3&nbsp;</span></td>",true);
    output("    <td><span class='colLtCyan'>&nbsp;1&nbsp;</span></td>",true);
    output("    <td><span class='colLtCyan'>&nbsp;Senio&nbsp;</span></td>",true);
    output("</tr>",true);
    output("<tr class='trlight' align='center'>",true);
    output("    <td><span class='colDkCyan'>&nbsp;4&nbsp;</span></td>",true);
    output("    <td><span class='colDkCyan'>&nbsp;4&nbsp;</span></td>",true);
    output("    <td><span class='colDkCyan'>&nbsp;4&nbsp;</span></td>",true);
    output("    <td><span class='colDkCyan'>&nbsp;3&nbsp;</span></td>",true);
    output("    <td><span class='colDkCyan'>&nbsp;&nbsp;</span></td>",true);
    output("</tr>",true);
    output("<tr class='trdark' align='center'>",true);
    output("    <td><span class='colLtCyan'>&nbsp;6&nbsp;</span></td>",true);
    output("    <td><span class='colLtCyan'>&nbsp;6&nbsp;</span></td>",true);
    output("    <td><span class='colLtCyan'>&nbsp;1&nbsp;</span></td>",true);
    output("    <td><span class='colLtCyan'>&nbsp;1&nbsp;</span></td>",true);
    output("    <td><span class='colLtCyan'>&nbsp;Senio&nbsp;</span></td>",true);
    output("</tr>",true);
    output("<tr class='trlight' align='center'>",true);
    output("    <td><span class='colDkCyan'>&nbsp;4&nbsp;</span></td>",true);
    output("    <td><span class='colDkCyan'>&nbsp;4&nbsp;</span></td>",true);
    output("    <td><span class='colDkCyan'>&nbsp;3&nbsp;</span></td>",true);
    output("    <td><span class='colDkCyan'>&nbsp;3&nbsp;</span></td>",true);
    output("    <td><span class='colDkCyan'>&nbsp;&nbsp;</span></td>",true);
    output("</tr>",true);
    output("<tr class='trdark' align='center'>",true);
    output("    <td><span class='colLtCyan'>&nbsp;4&nbsp;</span></td>",true);
    output("    <td><span class='colLtCyan'>&nbsp;4&nbsp;</span></td>",true);
    output("    <td><span class='colLtCyan'>&nbsp;4&nbsp;</span></td>",true);
    output("    <td><span class='colLtCyan'>&nbsp;1&nbsp;</span></td>",true);
    output("    <td><span class='colLtCyan'>&nbsp;&nbsp;</span></td>",true);
    output("</tr>",true);
    output("<tr class='trlight' align='center'>",true);
    output("    <td><span class='colDkCyan'>&nbsp;4&nbsp;</span></td>",true);
    output("    <td><span class='colDkCyan'>&nbsp;4&nbsp;</span></td>",true);
    output("    <td><span class='colDkCyan'>&nbsp;3&nbsp;</span></td>",true);
    output("    <td><span class='colDkCyan'>&nbsp;1&nbsp;</span></td>",true);
    output("    <td><span class='colDkCyan'>&nbsp;&nbsp;</span></td>",true);
    output("</tr>",true);
    output("<tr class='trdark' align='center'>",true);
    output("    <td><span class='colLtCyan'>&nbsp;4&nbsp;</span></td>",true);
    output("    <td><span class='colLtCyan'>&nbsp;3&nbsp;</span></td>",true);
    output("    <td><span class='colLtCyan'>&nbsp;3&nbsp;</span></td>",true);
    output("    <td><span class='colLtCyan'>&nbsp;1&nbsp;</span></td>",true);
    output("    <td><span class='colLtCyan'>&nbsp;&nbsp;</span></td>",true);
    output("</tr>",true);
    output("<tr class='trlight' align='center'>",true);
    output("    <td><span class='colDkCyan'>&nbsp;4&nbsp;</span></td>",true);
    output("    <td><span class='colDkCyan'>&nbsp;4&nbsp;</span></td>",true);
    output("    <td><span class='colDkCyan'>&nbsp;1&nbsp;</span></td>",true);
    output("    <td><span class='colDkCyan'>&nbsp;1&nbsp;</span></td>",true);
    output("    <td><span class='colDkCyan'>&nbsp;&nbsp;</span></td>",true);
    output("</tr>",true);
    output("<tr class='trdark' align='center'>",true);
    output("    <td><span class='colLtCyan'>&nbsp;3&nbsp;</span></td>",true);
    output("    <td><span class='colLtCyan'>&nbsp;3&nbsp;</span></td>",true);
    output("    <td><span class='colLtCyan'>&nbsp;3&nbsp;</span></td>",true);
    output("    <td><span class='colLtCyan'>&nbsp;1&nbsp;</span></td>",true);
    output("    <td><span class='colLtCyan'>&nbsp;&nbsp;</span></td>",true);
    output("</tr>",true);
    output("<tr class='trlight' align='center'>",true);
    output("    <td><span class='colDkCyan'>&nbsp;4&nbsp;</span></td>",true);
    output("    <td><span class='colDkCyan'>&nbsp;3&nbsp;</span></td>",true);
    output("    <td><span class='colDkCyan'>&nbsp;1&nbsp;</span></td>",true);
    output("    <td><span class='colDkCyan'>&nbsp;1&nbsp;</span></td>",true);
    output("    <td><span class='colDkCyan'>&nbsp;&nbsp;</span></td>",true);
    output("</tr>",true);
    output("<tr class='trdark' align='center'>",true);
    output("    <td><span class='colLtCyan'>&nbsp;3&nbsp;</span></td>",true);
    output("    <td><span class='colLtCyan'>&nbsp;3&nbsp;</span></td>",true);
    output("    <td><span class='colLtCyan'>&nbsp;1&nbsp;</span></td>",true);
    output("    <td><span class='colLtCyan'>&nbsp;1&nbsp;</span></td>",true);
    output("    <td><span class='colLtCyan'>&nbsp;&nbsp;</span></td>",true);
    output("</tr>",true);
    output("<tr class='trlight' align='center'>",true);
    output("    <td><span class='colDkCyan'>&nbsp;4&nbsp;</span></td>",true);
    output("    <td><span class='colDkCyan'>&nbsp;1&nbsp;</span></td>",true);
    output("    <td><span class='colDkCyan'>&nbsp;1&nbsp;</span></td>",true);
    output("    <td><span class='colDkCyan'>&nbsp;1&nbsp;</span></td>",true);
    output("    <td><span class='colDkCyan'>&nbsp;&nbsp;</span></td>",true);
    output("</tr>",true);
    output("<tr class='trdark' align='center'>",true);
    output("    <td><span class='colLtCyan'>&nbsp;3&nbsp;</span></td>",true);
    output("    <td><span class='colLtCyan'>&nbsp;1&nbsp;</span></td>",true);
    output("    <td><span class='colLtCyan'>&nbsp;1&nbsp;</span></td>",true);
    output("    <td><span class='colLtCyan'>&nbsp;1&nbsp;</span></td>",true);
    output("    <td><span class='colLtCyan'>&nbsp;&nbsp;</span></td>",true);
    output("</tr>",true);
    output("<tr class='trlight' align='center'>",true);
    output("    <td><span class='colDkCyan'>&nbsp;6&nbsp;</span></td>",true);
    output("    <td><span class='colDkCyan'>&nbsp;6&nbsp;</span></td>",true);
    output("    <td><span class='colDkCyan'>&nbsp;6&nbsp;</span></td>",true);
    output("    <td><span class='colDkCyan'>&nbsp;6&nbsp;</span></td>",true);
    output("    <td><span class='colDkCyan'>&nbsp;Vultures&nbsp;</span></td>",true);
    output("</tr>",true);
    output("<tr class='trdark' align='center'>",true);
    output("    <td><span class='colLtCyan'>&nbsp;4&nbsp;</span></td>",true);
    output("    <td><span class='colLtCyan'>&nbsp;4&nbsp;</span></td>",true);
    output("    <td><span class='colLtCyan'>&nbsp;4&nbsp;</span></td>",true);
    output("    <td><span class='colLtCyan'>&nbsp;4&nbsp;</span></td>",true);
    output("    <td><span class='colLtCyan'>&nbsp;Vultures&nbsp;</span></td>",true);
    output("</tr>",true);
    output("<tr class='trlight' align='center'>",true);
    output("    <td><span class='colDkCyan'>&nbsp;3&nbsp;</span></td>",true);
    output("    <td><span class='colDkCyan'>&nbsp;3&nbsp;</span></td>",true);
    output("    <td><span class='colDkCyan'>&nbsp;3&nbsp;</span></td>",true);
    output("    <td><span class='colDkCyan'>&nbsp;3&nbsp;</span></td>",true);
    output("    <td><span class='colDkCyan'>&nbsp;Vultures&nbsp;</span></td>",true);
    output("</tr>",true);
    output("<tr class='trdark' align='center'>",true);
    output("    <td><span class='colLtCyan'>&nbsp;1&nbsp;</span></td>",true);
    output("    <td><span class='colLtCyan'>&nbsp;1&nbsp;</span></td>",true);
    output("    <td><span class='colLtCyan'>&nbsp;1&nbsp;</span></td>",true);
    output("    <td><span class='colLtCyan'>&nbsp;1&nbsp;</span></td>",true);
    output("    <td><span class='colLtCyan'>&nbsp;Cane&nbsp;</span></td>",true);
    output("</tr>",true);
    output("</table>",true);
}
//***************************************//
//               END TALI                //
//***************************************//

function buffAle() {
   $buff = array("name"=>"`#Ronzio",
       "rounds"=>10,
       "wearoff"=>"Il tuo ronzio svanisce.",
       "atkmod"=>1.25,
       "roundmsg"=>"Emetti proprio un bel ronzio.",
       "activate"=>"offense");
   return $buff;
}

function buffDefence($rounds, $mod) {
   $buff = null;
   output("Dopo aver mangiato questo piatto succulento ti senti strano... Hai mangiato una parte del drago ");
   output("che reagisce con il tuo organismo creando delle `@Scaglie di Drago`7 sulla tua pelle e la tua difesa ");
   output("migliora!`n");
   $buff = array("name"=>"`@Scaglie di Drago"
       ,"rounds"=>$rounds
       ,"wearoff"=>"`!Le scaglie scompaiono e il tuo corpo torna normale"
       ,"defmod"=>$mod
       ,"roundmsg"=>"Ti senti invincibile ricoperto dalle scaglie di drago!"
       ,"activate"=>"defense");
   //print_r($buff);
   return $buff;
}

function buffAttack($rounds, $mod) {
   $buff = null;

   output("Dopo aver mangiato questo piatto succulento ti senti strano... Hai finalmente scoperto un antico ");
   output("segreto dei draghi, il `@Soffio del Drago!`7 Il tuo attacco migliora perché anche tu ora sputi fuoco!`n");
   $buff = array("name"=>"`@Soffio del Drago"
       ,"rounds"=>$rounds
       ,"wearoff"=>"`!Apri la bocca ma ora esce solo del fumo nero"
       ,"atkmod"=>$mod
       ,"roundmsg"=>"Incenerisci il tuo nemico con il fuoco!"
       ,"activate"=>"offense");
   //print_r($buff);
   return $buff;
}

function buffRegeneration($rounds, $mod, $livello) {
   $buff = null;

   output("Dopo aver mangiato questo piatto succulento ti senti strano... Hai scoperto come il drago riesce ");
   output("a resistere per molti attacchi, la pietanza che hai mangiato accelera la guarigione e ora riesci a ");
   output("`@Rigenerare le ferite`7!`n");
   $moltiplica = $livello * $mod;
   $buff = array(
       "startmsg"=>"Il potere del drago ti rigenera!",
       "name"=>"`@Rigenerazione del Drago",
       "rounds"=>$rounds,
       "wearoff"=>"`!Hai smesso di rigenerarti",
       "regen"=>$moltiplica,
       "effectmsg"=>"Ti rigeneri per {damage} HP.",
       "effectnodmgmsg"=>"Non hai ferite da rigenerare.",
       "activate"=>"roundstart");
   //print("mod = ".$mod.", livello = ".$livello." moltiplica = ".$moltiplica."<br>");
   //print_r($buff);
   return $buff;
}

// genera un buff random
function generateBuff($piatto,$livello){

   $buff = null;
   switch($piatto) {
     case "cosciotto":
           $generaBuff = e_rand(1,3);
           switch($generaBuff) {
             case 1:
                 $buff = buffDefence(25, 1.4);
                 break;
             case 2:
                 $buff = buffAttack(25, 1.4);
                 break;
             case 3:
                 $buff = buffRegeneration(25, 1,$livello);
                 break;
           }
           break;
     case "filetto":
           $generaBuff = e_rand(1,3);
           switch($generaBuff) {
             case 1:
                 $buff = buffDefence(25, 1.7);
                 break;
             case 2:
                 $buff = buffAttack(25, 1.7);
                 break;
             case 3:
                 $buff = buffRegeneration(25, 1.5,$livello);
                 break;
           }
           break;
     case "piattospeciale":
           $generaBuff = e_rand(1,6);
           switch($generaBuff) {
             case 1:
                 $buff = buffDefence(15, 1.2);
                 break;
             case 2:
                 $buff = buffAttack(15, 1.2);
                 break;
             case 3:
                 $buff = buffRegeneration(15, 0.5,$livello);
                 break;
             case 4:
                 output("Dopo aver mangiato questo piatto succulento ti senti strano... Forse non era tanto ");
                 output("succulento... Anzi, quando guardi il fondo del piatto vedi dei resti di vermiciattoli! ");
                 output("Vai in bagno e cominci a vomitare, sei rimasto `^Intossicato`7!`n");
                 $buff = array("name"=>"`%Intossicazione"
                     ,"startmsg"=>"`n`^Ti senti svenire!`n`n"
                     ,"rounds"=>15
                     ,"wearoff"=>"Il tuo corpo è tornato alla normalità."
                     ,"defmod"=>0.7
                     ,"roundmsg"=>"Vomiti e non riesci a difenderti"
                     ,"activate"=>"defense");
                 break;
             case 5:
                 output("Dopo aver mangiato questo piatto succulento ti senti strano... Forse è la fatica di ");
                 output("mille battaglie o la digestione in corso... La `^Debolezza`7 si sta impossessando di te!`n");
                 $buff = array("name"=>"`%Debolezza"
                     ,"startmsg"=>"`n`^Ti senti sfiancato !`n`n"
                     ,"rounds"=>15
                     ,"wearoff"=>"Le forze ti sono tornate."
                     ,"atkmod"=>0.7
                     ,"roundmsg"=>"Non riesci nemmeno a sollevare l'arma!"
                     ,"activate"=>"offense");
                 break;
             case 6:
                 output("Dopo aver mangiato questo piatto succulento ti senti strano... Forse non era tanto ");
                 output("succulento... Anzi, comincia ad avere dei `^Crampi`7 dolorosissimi!`n");
                 $buff = array("name"=>"`%Crampi"
                     ,"startmsg"=>"`n`^Sei dolorante dai crampi!`n`n"
                     ,"rounds"=>15
                     ,"wearoff"=>"I crampi sono terminati."
                     ,"minioncount"=>1
                     ,"mingoodguydamage"=>0
                     ,"maxgoodguydamage"=>$livello+$dkb
                     ,"effectmsg"=>"I crampi ti causano {damage} punti danno."
                     ,"effectnodmgmsg"=>"Riesci a sopportare il dolore dei crampi."
                     ,"activate"=>"roundstart");
                 break;
           }
           break;
     }
     return $buff;
}

if ($session[scagliaverdeeat] == null) {$session[scagliaverdeeat]=1;}
if ($session[scagliaverdedrink] == null) {$session[scagliaverdedrink]=1;}

if (!isset($session)) exit();
$session['user']['specialinc'] = "scagliaverde.php";
$nomeTaverna = "`@Alla Scaglia Verde Fritta";
output("`c`b`@Taverna $nomeTaverna`b`c",true);

//$prezzobase=($session['user']['dragonkills'] * 2) + ($session['user']['reincarna'] * 10);
//$prezzostinco=$prezzobase+40;
//$prezzoarrosto=$prezzobase+50;
$prezzostinco = ($session['user']['level'] * 10);
$prezzoarrosto = ($session['user']['level'] * 12);
$alecost = $session[user][level]*10;

//print("Flag = ".$session['scagliaverdeeat']);
switch($_GET[op]){
case "":
    checkday();
    output("`n`7Arrivi in una piccola radura all'incrocio tra due sentieri e vedi una taverna costruita a ");
    output("ridosso di una collinetta rocciosa.`n");
    output("Dal comignolo esce un fumo denso e un delizioso profumo di cibi da far venire l'acquolina in bocca ");
    output("ti solletica l'appetito.`n");
    output("Dall'interno provengono voci e risa e sopra alla porta vedi un'insegna: ");
    output("`b$nomeTaverna`b");
    output("`n`n`^Il posto ti sembra molto invitante, provi a entrare e rifocillarti o preferisci proseguire ");
    output("per la tua strada?`^");
    addnav("`2`bEntra nella taverna`b`2","forest.php?op=entrataverna");
    addnav("`\$Torna nella foresta","forest.php?op=esci");
    break;
case "entrataverna":
    checkday();
    output("`n`7All'interno la taverna sembra molto accogliente, ti ritrovi in un ampio salone scavato nella ");
    output("roccia con molte belle armi, arazzi e torce appese alle pareti. Nella parete accanto alla porta ");
    output("della cucina c'è una `^vetrinetta `7con qualcosa di luccicante dentro.");
    output("`nIn fondo alla sala spicca una porta socchiusa con scritto a caratteri cubitali `^Bisca Clandestina `7.");
    output("`nLo spazio nella sala è completamente occupato da una grande tavolata a ferro di cavallo affollata ");
    output("da avventurieri che mangiano, scherzano e cantano facendo un gran baccano in mezzo ai quali Teg si ");
    output("destreggia nel servire cibi e birre.");
    output("`nUna bellissima barista ti strizza l'occhio da dietro il bancone del `^Bar `7dove dei clienti ");
    output("sorseggiano alcune bevande.");
    output("`n`nTi accomodi al primo posto libero e Teg ti lancia un `@Menù`7 rilegato in pelle verde e nel ");
    output("fracasso generale ti dice di chiamarlo appena sei pronto ad ordinare.`n`n");

    viewcommentary("calderoneverde","Urla:",25,10,"urla");

    addnav("Taverna");
    addnav("`2`bMenù`b","forest.php?op=menu");
    addnav("B?Vai al Bar","forest.php?op=bar");
    addnav("C?Bisca Clandestina","forest.php?op=bisca");
    addnav("V?Esamina la Vetrinetta","forest.php?op=vetrina");
    addnav("Altro");
    addnav("Esci dalla Taverna","forest.php?op=esci");
    break;

// MENU PRINCIPALE
case "menu":
    output("`n`7Apri il `@Menù`7 e ti si presenta una lista di abbondanti piatti a base di carne, specialità `@Drago Verde`7 in alcune varianti.");
    output("`nIn fondo al listino prezzi trovi anche un piatto speciale gratuito!");
    addnav("Cibi");
    addnav("Stinco al Forno ($prezzostinco)","forest.php?op=stinco"); // Piu' nutriente del pollo
    addnav("Arrosto di Maiale ($prezzoarrosto)","forest.php?op=arrosto"); // Piu' nutriente dello stinco
    addnav("`2Cosciotto di Drago (1 gemma)","forest.php?op=cosciotto"); // Nutriente come l'arrosto e da un potere da drago minore casuale
    addnav("`2Filetto di Drago (2 gemme)","forest.php?op=filetto"); // Nutriente come lo stinco e da un potere da drago maggiore casuale (Vedi commento sugli effetti speciali da Drago)
    addnav("`2Specialità della Casa (1 gemma)","forest.php?op=scaglie"); // Le Scaglie di Drago Fritte non nutrono ma fanno aumentare la bellezza della pelle (5 punti fascino)
    addnav("`\$Piatto speciale gratuito","forest.php?op=piattospeciale"); // Nutriente come lo stinco e con un effetto casuale negativo o positivo
    addnav("Altro");
    addnav("Entra in Cucina","forest.php?op=cucina");
    addnav("Torna alla Taverna","forest.php?op=entrataverna");
    break;
case "vetrina":
    output("`n`7Esamini la vetrinetta e i tuoi occhi s'illuminano vedendo cosa contiene: `^è piena di ");
    output("gemme bellissime`7! `nTi avvicini ancor di più per ammirarle meglio e prendi contro senza ");
    output("volere alla vetrinetta che si apre... hanno dimenticato di chiuderla! Teg è appena entrato ");
    output("in cucina e nessuno ti osserva, potrebbe essere la tua occasione per afferrarne un po' e fuggire ");
    output("prima che qualcuno se ne accorga.");

    addnav("Vetrina");
    addnav("`&Afferra le gemme!","forest.php?op=afferragemme"); // Cerca di rubare le gemme ed esce dalla taverna
    addnav("Altro");
    addnav("Torna alla Taverna","forest.php?op=entrataverna");
    break;
case "bar":
    output("`n`7Jowena ti accoglie con un enigmatico sorriso maliziosamente sadico e ti mostra i cocktail ");
    output("che ti puo' servire, costano tutti `^$alecost`7 pezzi d'oro");

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
    $drunk = round($session[user][drunkenness]/10-.5,0);
    if ($drunk > 10) $drunk=10;
    output("`n`n`7Ti senti ".$drunkenness[$drunk]."`n`n");

    addnav("Bevande");
    addnav("`6Birra della Casa","forest.php?op=birra"); // Birra normale
    addnav("R?`^Gold River","forest.php?op=birraoro"); // Bevanda per fedeli di Sgrios
    addnav("M?`\$Bloody Mary","forest.php?op=birrasangue"); // Bevanda per seguaci di Karnak
    addnav("G?`@Grasshopper","forest.php?op=birraverde"); // Bevanda per seguaci del Drago Verde
    addnav("Altro");
    addnav("Torna alla Taverna","forest.php?op=entrataverna");
    break;
case "bisca":
    output("`n`7Entri in una saletta poco illuminata con alcuni tavoli da gioco, ad uno di essi siede Maximus ");
    output("che ti fa segno di avvicinarti. `nTi siedi di fronte a lui e ti propone alcuni giochi, vuoi provare ");
    output("a sfidarlo anche se la sua fama di baro è ben nota?`n");

    addnav("Giochi");
    addnav("`^Tali","forest.php?op=tali&action=intro");
    addnav("K?`2ToKi","forest.php?op=toki&action=intro");
    addnav("Altro");
    addnav("Torna alla Taverna","forest.php?op=entrataverna");
    break;
case "cucina":
    output("`n`7Teg è appena uscito dalla cucina e un'idea ti è subito balzata in mente, RUBARE DEL CIBO!");
    output("`nTi avvicini quatto quatto alla porta della cucina ed entri furtivo... non c'è nessuno, devi ");
    output("decidere in fretta se vuoi avere successo!");

    addnav("Cucina");
    addnav("`&Afferra del cibo!","forest.php?op=robefood");
    addnav("Altro");
    addnav("Torna al `2`bMenù`b","forest.php?op=menu");
    break;

// MENU ALIMENTARE
case "stinco":
    output("`n`7Chiami Teg e gli ordini uno Stinco al forno. Dopo pochi minuti ritorna con un piatto dall'aspetto ");
    output("molto invitante.`n`n");
    if ($session['user']['gold'] >= ($prezzostinco)){
        if ($session['user']['hunger']>-10){
            output("Ti getti sullo stinco e lo divori con la velocità di uno stormo di cavallette.`n");
            $session['user']['hunger']-=70;
            $session['user']['gold']-=($prezzostinco);
        }else{
            output("`%Ti rendi conto però che sei troppo pieno per poterne mangiare anche una sola porzione!`n");
            output("Con grande dispiacere dici a Teg che non lo vuoi più e lui per punizione te lo rovescia in testa...`n`n");
            $cleanchance=e_rand(0,4);
            $session['user']['clean'] += 2;
            if ($cleanchance == 0) {
                output("`4Il piatto è rovente! Perdi un Punto di Fascino!`7`n");
                $session['user']['charm']--;
            }
        }
    } else{
        output("`%Stai per pagare Teg quando ti accorgi di non avere contanti disponibili. Cerchi di raccontargli ");
        output("qualche frottola ma lui non ci casca e se ne va con il tuo piatto.`n`n");
        output("Ora hai più fame di quando sei entrato!`n");
        $session['user']['hunger']+=30;
    }

    addnav("Torna al `2`bMenù`b","forest.php?op=menu");
    break;
case "arrosto":
    output("`n`7Chiami Teg e gli ordini un Arrosto di Maiale. Dopo pochi minuti ritorna con un piatto dall'aspetto ");
    output("molto invitante.`n`n");
    if ($session['user']['gold'] >= ($prezzoarrosto)){
        if ($session['user']['hunger']>-10){
            output("Ti getti sull'arrosto e lo divori con la velocità di uno stormo di cavallette.`n");
            $session['user']['hunger']-=80;
            $session['user']['gold']-=($prezzoarrosto);
        }else{
            output("`%Ti rendi conto però che sei troppo pieno per poterne mangiare anche una sola porzione!`n");
            output("Con grande dispiacere dici a Teg che non lo vuoi più e lui per punizione te lo rovescia in ");
            output("testa...`n`n");
            $cleanchance=e_rand(0,4);
            $session['user']['clean'] += 2;
            if ($cleanchance == 0) {
                output("`4Il piatto è rovente! Perdi un Punto di Fascino!`7`n");
                $session['user']['charm']--;
            }
        }
    } else{
        output("`%Stai per pagare Teg quando ti accorgi di non avere contanti disponibili. Cerchi di raccontargli ");
        output("qualche frottola ma lui non ci casca e se ne va con il tuo piatto.`n`n");
        output("Ora hai più fame di quando sei entrato!`n");
        $session['user']['hunger']+=50;
    }

    addnav("Torna al `2`bMenù`b","forest.php?op=menu");
    break;
case "cosciotto":
    output("`n`7Chiami Teg e gli ordini un `@Cosciotto di Drago Verde`7. Dopo pochi minuti ritorna con un piatto ");
    output("dall'aspetto molto invitante.`n`n");
    if ($session['user']['gems'] >= 1){
        if ($session['user']['hunger']>-10){
            output("Ti getti sul Cosciotto e lo divori con la velocità di uno stormo di cavallette.`n`n");
            $session['user']['hunger']-=80;
            $session['user']['gems']--;
            //l'effetto speciale è solo per la prima volta
            if ($session[scagliaverdeeat]) {
               $session[scagliaverdeeat] = false;
               $randomBuff = e_rand(0,5);
               switch($randomBuff) {
                      case 0:
                             output("Dopo aver mangiato questo piatto succulento ti senti soddisfatto");
                             break;
                      case 1:
                      case 2:
                      case 3:
                      case 4:
                      case 5:
                             $session['bufflist']['dragoneat']=generateBuff("cosciotto",$session['user']['level']);
                             break;
               }
            }
        }else{
            output("`%Ti rendi conto però che sei troppo pieno per poterne mangiare anche una sola porzione!`n");
            output("Con grande dispiacere dici a Teg che non lo vuoi più e lui per punizione te lo rovescia in testa...`n`n");
            $cleanchance=e_rand(0,4);
            $session['user']['clean'] += 2;
            if ($cleanchance == 0) {
                output("`4Il piatto è rovente! Perdi un Punto di Fascino!`7`n");
                $session['user']['charm']--;
            }
        }
    } else {
        output("`%Stai per pagare Teg quando ti accorgi di non avere di che pagare il conto. Cerchi di ");
        output("raccontargli qualche frottola ma lui non ci casca e se ne va con il tuo piatto.`n`n");
        output("Ora hai più fame di quando sei entrato!`n");
        $session['user']['hunger']+=30;
    }

    addnav("Torna al `2`bMenù`b","forest.php?op=menu");
    break;
case "filetto":
    output("`n`7Chiami Teg e gli ordini un Filetto di Drago Verde. Dopo pochi minuti ritorna con un piatto ");
    output("dall'aspetto molto invitante.`n`n");
    if ($session['user']['gems'] >= 2){
        if ($session['user']['hunger']>-10){
            output("Ti getti sul filetto e lo divori con la velocità di uno stormo di cavallette.`n`n");
            $session['user']['hunger']-=70;
            $session['user']['gems']-= 2;

            //l'effetto speciale è solo per la prima volta
            if ($session[scagliaverdeeat]) {
                    $session[scagliaverdeeat] = false;
                    $randomBuff = e_rand(0,5);
                    switch($randomBuff) {
                           case 0:
                                  output("Dopo aver mangiato questo piatto succulento ti senti soddisfatto");
                                  break;
                           case 1:
                           case 2:
                           case 3:
                           case 4:
                           case 5:
                                  $session['bufflist']['dragoneat']=generateBuff("filetto",$session['user']['level']);
                                  break;
                    }
               }
        }else{
            output("`%Ti rendi conto però che sei troppo pieno per poterne mangiare anche una sola porzione!`n");
            output("Con grande dispiacere dici a Teg che non lo vuoi più e lui per punizione te lo rovescia in testa...`n`n");
            $cleanchance=e_rand(0,4);
            $session['user']['clean'] += 2;
            if ($cleanchance == 0) {
                output("`4Il piatto è rovente! Perdi un Punto di Fascino!`7`n");
                $session['user']['charm']--;
            }
        }
    } else{
        output("`%Stai per pagare Teg quando ti accorgi di non avere di che pagare il conto. Cerchi di ");
        output("raccontargli qualche frottola ma lui non ci casca e se ne va con il tuo piatto.`n`n");
        output("Ora hai più fame di quando sei entrato!`n");
        $session['user']['hunger']+=50;
    }
    addnav("Torna al `2`bMenù`b","forest.php?op=menu");
    break;
case "scaglie":
    output("`n`7Chiami Teg e gli ordini la `@Specialità della Casa`7");
    if ($session[scagliaverdeeat]) {
        output("`nDopo pochi minuti ritorna con un piatto dall'aspetto molto invitante.`n`n");
        if ($session['user']['gems'] >= 1){
            $session[scagliaverdeeat] = false;
            output("Noti con qualche disappunto che sono delle `@Scaglie di Drago Verde Fritte`7. Vai da Teg ");
            output("per delle spiegazioni e lui ti risponde che dovevi aspettartelo, visto il nome della locanda.`n");
            output("Un pò contrariato per aver speso una tua preziosa `&gemma `7per delle scaglie di drago, decidi ");
            output("di non buttarle, ne assaggi una e... WOW sono a dir poco `1F`2A`3N`4T`5A`6S`7T`1I`2C`3H`4E`7`n");
            output("Tempo pochi secondi e finisci il pasto leccando anche il piatto.`n");
            if ($session['user']['sex']) {
                output("Quando Teg viene per toglierti il piatto, rimane per un attimo incantato dai tuoi occhi. ");
                output("Queste scaglie hanno un effetto speciale sulla tua pelle aumentando il tuo fascino!`n`n");
            } else {
                output("Quando Jowena viene per toglierti il piatto, rimane per un attimo incantata dai tuoi occhi. ");
                output("Queste scaglie hanno un effetto speciale sulla tua pelle aumentando il tuo fascino!`n`n");
            }
            output("Hai guadagnato `^5 `7Punti di Fascino.");
            $session['user']['gems']--;
            $session['user']['charm']+=5;
            debuglog("guadagna 5 punti fascino acquistando le scaglie fritte ".$nomeTaverna);
        } else{
            output("`%Stai per pagare Teg quando ti accorgi di non avere di che pagare il conto. Cerchi di ");
            output("raccontargli qualche frottola ma lui non ci casca e se ne va con il tuo piatto.`n`n");
        }
    }else{
       output(" ma purtroppo ti dice che è terminato.`n`n");
    }
    addnav("Torna al `2`bMenù`b","forest.php?op=menu");
    break;
case "piattospeciale":
    output("`n`7Chiami Teg e gli ordini il Piatto Speciale");
    //l'effetto speciale è solo per la prima volta
    if ($session['scagliaverdeeat'] != 3) {
       $session['scagliaverdeeat'] = 3;
       output("`nDopo pochi minuti ritorna con un piatto dall'aspetto molto invitante.`n`n");
       $randomBuff = e_rand(0,5);
       switch($randomBuff) {
              case 0:
                     output("Mentre mangi questo piatto succulento mordi qualcosa di molto resistente che ti ");
                     output("scheggia un dente!`n");
                     output("Imprecando tiri fuori dalla bocca l'oggetto incriminato e noti con stupore che si ");
                     output("tratta di una `^gemma`7!`n");
                     output("Hai perso qualche punto ferita ma la gemma guadagnata ti fa passare il dolore`n");
                     $session['user']['gems']++;
                     $hplose = intval($session['user']['maxhitpoints']*0.1);
                     $session['user']['hitpoints'] -= $hplose;
                     if ($session['user']['hitpoints'] < 1)  {
                        $session['user']['hitpoints'] = 1;
                     }
                     break;
              case 1:
              case 2:
              case 3:
              case 4:
              case 5:
                     $session['bufflist']['dragoneat']=generateBuff("piattospeciale",$session['user']['level']);;
                     break;
       }

    }else{
       output(" ma purtroppo ti dice che è terminato.`n`n");
    }
    addnav("Torna al `2`bMenù`b","forest.php?op=menu");
    break;

// BEVANDE
case "birra":
    $alecost = $session['user']['level']*10;
    output("`7Sbattendo il pugno sul bancone, chiedi una birra");
    if ($session['user']['drunkenness']>66){
        output(", ma Jowena continua a pulire i bicchieri su cui stava lavorando. \"`%Ne hai avuta abbastanza ");
        output(($session[user][sex]?"ragazza":"ragazzo").". Ripassa quando hai smaltito la sbronza`7\" ti dice.");
    }else{
      if ($session['user']['gold']>=$alecost){
          $session['user']['drunkenness']+=33;
          $session['user']['bladder']+=3;
          $session['user']['gold']-=$alecost;
          output(".`nJowena tira fuori un bicchiere e versa una birra spumosa da un barile dietro di lei. ");
          output("Lo lancia lungo il bancone e tu lo afferri con i tuoi riflessi da guerriero.");
          output("`n`nVoltandoti, bevi un grosso sorso della bevanda, e rivolgi a ");
          output(($session['user']['sex']?"Teg":"Jowena"));
          output(" un sorriso con i baffi di schiuma.`n`n");
          switch(e_rand(1,3)){
             case 1:
             case 2:
                  output("`&Ti senti in salute!");
                  $session['user']['hitpoints']+=round($session['user']['maxhitpoints']*.1,0);
                  break;
             case 3:
                  output("`&Ti senti vigoroso!");
                  $session['user']['turns']++;
            }
            $session['bufflist'][101] = array("name"=>"`#Ronzio","rounds"=>10,"wearoff"=>"Il tuo ronzio svanisce.",
            "atkmod"=>1.25,
            "roundmsg"=>"Emetti proprio un bel ronzio.",
            "activate"=>"offense");
        }else{
        output(", ma Jowena continua a pulire i bicchieri su cui stava lavorando. \"`%Niente soldi, ");
        output("niente birra ".($session['user']['sex']?"ragazza":"ragazzo")."!`7\" ti dice.");
        }
    }
    addnav("B?Torna al Bancone","forest.php?op=bar");
    break;
case "birraoro":
    $alecost = $session['user']['level']*10;
    output("`7Sbattendo il pugno sul bancone, chiedi un `^Gold River`7");
    if ($session['user']['drunkenness']>66){
        output(", ma Jowena continua a pulire i bicchieri su cui stava lavorando. \"`%Ne hai avuta abbastanza ");
        output(($session['user']['sex']?"ragazza":"ragazzo").". Ripassa quando hai smaltito la sbronza`7\" ti dice.");
    }else{
      if ($session['user']['gold']>=$alecost){
          $session['user']['drunkenness']+=33;
          $session['user']['bladder']+=3;
          $session['user']['gold']-=$alecost;
          output(".`nJowena tira fuori un bicchiere e ti prepara un `^Gold River`7. ");
          output("Lo lancia lungo il bancone e tu lo afferri con i tuoi riflessi da guerriero.");
          output("`n`nVoltandoti, bevi un grosso sorso della bevanda, e rivolgi a ");
          output(($session['user']['sex']?"Teg":"Jowena"));
          output(" un sorriso con tanto di occhiolino.`n`n");
          switch(e_rand(1,3)){
             case 1:
             case 2:
                  output("`&Ti senti in salute!");
                  $session['user']['hitpoints']+=round($session['user']['maxhitpoints']*.1,0);
                  break;
             case 3:
                  output("`&Ti senti vigoroso!");
                  $session['user']['turns']++;
            }
            if ($session['user']['dio']==1 && e_rand(1,2)==1 && $session[scagliaverdedrink]) {
               $session[scagliaverdedrink]=false;
               $buff = array("name" => "`\$Tocco di Sgrios",
                   "rounds" => 25,
                   "wearoff" => "`!La tua Forza Divina scompare e torni normale",
                   "defmod" => 1.2,
                   "roundmsg" => "Senti Sgrios al tuo fianco!",
                   "activate" => "defense");
               $session['bufflist']['magicweak'] = $buff;
               output("`%`n`nSembra che il cocktail abbia un grande potere su di te... Una leggera aura ");
               output("luminosa ti circonda. Senti `6Sgrios`% entrare in te!`n");
            } else {
               if ($session['user']['dio']!=1 && e_rand(1,2)==1 && $session[scagliaverdedrink]) {
                   $session[scagliaverdedrink]=false;
                   $buff = array("name" => "`\$Maledizione di Sgrios",
                       "rounds" => 25,
                       "wearoff" => "`!La Maledizione Divina scompare e torni normale",
                       "defmod" => 0.7,
                       "roundmsg" => "Senti che Sgrios aiuta il tuo nemico!",
                       "activate" => "defense");
                   $session['bufflist']['dragondrink'] = $buff;
                   output("`%`n`nSembra che il cocktail abbia uno strano potere su di te... Hai bevuto una ");
                   output("bevanda benedetta da `6Sgrios`% e in quanto ".$fededio[$session['user']['dio']]." `%ti senti indifeso!`n");
               }else{
                   $session[bufflist][101] = buffAle();
               }
            }
        }else{
        output(", ma Jowena continua a pulire i bicchieri su cui stava lavorando. \"`%Niente soldi, niente ");
        output("cocktail ".($session['user']['sex']?"ragazza":"ragazzo")."!`7\" ti dice.");
        }
    }
    addnav("B?Torna al Bancone","forest.php?op=bar");
    break;
case "birrasangue":
    $alecost = $session['user']['level']*10;
    output("`7Sbattendo il pugno sul bancone, chiedi un `4Bloody Mary`7");
    if ($session['user']['drunkenness']>66){
        output(", ma Jowena continua a pulire i bicchieri su cui stava lavorando. \"`%Ne hai avuta ");
        output("abbastanza ".($session['user']['sex']?"ragazza":"ragazzo").". Ripassa quando hai ");
        output("smaltito la sbronza`7\" ti dice.");
    }else{
      if ($session['user']['gold']>=$alecost){
          $session['user']['drunkenness']+=33;
          $session['user']['bladder']+=3;
          $session['user']['gold']-=$alecost;
          output(".`nJowena tira fuori un bicchiere e ti prepara un `4Bloody Mary`7. ");
          output("Lo lancia lungo il bancone e tu lo afferri con i tuoi riflessi da guerriero.");
          output("`n`nVoltandoti, bevi un grosso sorso della bevanda, e rivolgi a ");
          output(($session['user']['sex']?"Teg":"Jowena"));
          output(" un sorriso con tanto di occhilino.`n`n");
          switch(e_rand(1,3)){
             case 1:
             case 2:
                  output("`&Ti senti in salute!");
                  $session['user']['hitpoints']+=round($session['user']['maxhitpoints']*.1,0);
                  break;
             case 3:
                  output("`&Ti senti vigoroso!");
                  $session['user']['turns']++;
            }
            if ($session['user']['dio']==2 && e_rand(1,2)==1 && $session[scagliaverdedrink]) {
               $session[scagliaverdedrink]=false;
               $buff = array("name" => "`\$Tocco infernale di Karnak",
                   "rounds" => 15,
                   "wearoff" => "`!La tua Forza Infernale scompare e torni normale",
                   "defmod" => 1.5,
                   "roundmsg" => "Senti Karnak al tuo fianco!",
                   "activate" => "defense");
               $session['bufflist']['magicweak'] = $buff;
               output("`%`n`nSembra che il cocktail abbia un un grande potere su di te... Un leggera aura ");
               output("malefica ti circonda. Senti `\$Karnak `%entrare in te!`n");
            } else {
               if ($session['user']['dio']!=2 && e_rand(1,2)==1 && $session[scagliaverdedrink]) {
                  $session[scagliaverdedrink]=false;
                  $buff = array("name" => "`\$Maledizione di Karnak",
                      "rounds" => 15,
                      "wearoff" => "`!La Maledizione Divina scompare e torni normale",
                      "defmod" => 0.5,
                      "roundmsg" => "Senti Karnak che ti blocca l'arma!",
                      "activate" => "defense");
                  $session['bufflist']['dragondrink'] = $buff;
                  output("`%`n`nSembra che il cocktail abbia uno strano potere su di te... Hai bevuto una ");
                  output("bevanda maledetta da `\$Karnak`% e in quanto ".$fededio[$session['user']['dio']]." `%ti senti senza forze!`n");
               }else{
                  $session[bufflist][101] = buffAle();
               }
            }
        }else{
        output(", ma Jowena continua a pulire i bicchieri su cui stava lavorando. \"`%Niente soldi, ");
        output("niente cocktail ".($session['user']['sex']?"ragazza":"ragazzo")."!`7\" ti dice.");
        }
    }
    addnav("B?Torna al Bancone","forest.php?op=bar");
    break;
case "birraverde":
    $alecost = $session['user']['level']*10;
    output("`7Sbattendo il pugno sul bancone, chiedi un `@Grasshopper`7");
    if ($session['user']['drunkenness']>66){
        output(", ma Jowena continua a pulire i bicchieri su cui stava lavorando. \"`%Ne hai avuta ");
        output("abbastanza ".($session['user']['sex']?"ragazza":"ragazzo").". Ripassa quando hai ");
        output("smaltito la sbronza`7\" ti dice.");
    }else{
      if ($session['user']['gold']>=$alecost){
          $session['user']['drunkenness']+=33;
          $session['user']['bladder']+=3;
          $session['user']['gold']-=$alecost;
          output(".`nJowena tira fuori un bicchiere e ti prepara un `@Grasshopper`7. ");
          output("Lo lancia lungo il bancone e tu lo afferri con i tuoi riflessi da guerriero.");
          output("`n`nVoltandoti, bevi un grosso sorso della bevanda, e rivolgi a ");
          output(($session['user']['sex']?"Teg":"Jowena"));
          output(" un sorriso con tanto di occhilino.`n`n");
          switch(e_rand(1,3)){
             case 1:
             case 2:
                  output("`&Ti senti in salute!");
                  $session['user']['hitpoints']+=round($session['user']['maxhitpoints']*.1,0);
                  break;
             case 3:
                  output("`&Ti senti vigoroso!");
                  $session['user']['turns']++;
            }
            if ($session['user']['dio']==3 && e_rand(1,2)==1 && $session[scagliaverdedrink]) {
               $session[scagliaverdedrink]=false;
               $buff = array("name" => "`@Soffio di Fuoco del Drago Verde",
                   "rounds" => 15,
                   "wearoff" => "`!i Soffio di Fuoco si esaurisce e torni normale",
                   "defmod" => 1.5,
                   "roundmsg" => "Senti il Drago Verde al tuo fianco!",
                   "activate" => "defense");
               $session['bufflist']['magicweak'] = $buff;
               output("`%`n`nSembra che il cocktail abbia un un grande potere su di te... Un leggera aura ");
               output("verdognola ti circonda. Senti il `@Drago Verde `%entrare in te!`n");
            } else {
               if ($session['user']['dio']!=3 && e_rand(1,2)==1 && $session[scagliaverdedrink]) {
                  $session[scagliaverdedrink]=false;
                  $buff = array("name" => "`@Maledizione del Drago Verde",
                      "rounds" => 15,
                      "wearoff" => "`!La Maledizione Ultraterrena scompare e torni normale",
                      "defmod" => 0.5,
                      "roundmsg" => "Percepisci il Drago Verde bloccarti l'arma!",
                      "activate" => "defense");
                  $session['bufflist']['dragondrink'] = $buff;
                  output("`%`n`nSembra che il cocktail abbia uno strano potere su di te... Hai bevuto una ");
                  output("bevanda maledetta dal `@Drago Verde`% e in quanto ".$fededio[$session['user']['dio']]." `%ti senti senza forze!`n");
               }else{
                  $session[bufflist][101] = buffAle();
               }
            }
        }else{
        output(", ma Jowena continua a pulire i bicchieri su cui stava lavorando. \"`%Niente soldi, ");
        output("niente cocktail ".($session['user']['sex']?"ragazza":"ragazzo")."!`7\" ti dice.");
        }
    }
    addnav("B?Torna al Bancone","forest.php?op=bar");
    break;

// VETRINA
case "afferragemme":
    $cattiveriaGemme = 5;
    $session['user']['evil'] += $cattiveriaGemme;
    $nGemme = e_rand(1,3); // Numero di gemme rubate
    if($nGemme == 1) {
        $sGemme = "la gemma";
        $snGemme = "UNA gemma";
    } else {
        $sGemme = "le gemme";
        $snGemme = "$nGemme gemme";
    }
    output("`n`c`b`&Infili la mano nella vetrinetta e riesci ad afferrare $snGemme!`b`c`n");

    $tegAction = e_rand(0,6); /*
         0: morto, meno 10% e senza l'oro;
        1,2: buttato fuori e senza l'oro, se non ha oro qualche hp in meno (dal 10 al 60% in meno);
        3: buttato fuori e privato delle gemme, se non ha gemme di tutto l'oro se non ha oro qualche hp in meno;
        4-6: scappa con le gemme. */
    if($tegAction == 0) {
        $session['user']['alive'] = false;
        $session['user']['hitpoints'] = 0;
        $session['user']['experience'] = round($session['user']['experience'] * .9,0);
        $session['user']['specialinc'] = "";
        addnews("`^".$session['user']['name']." `\$è stat".($session['user']['sex']?"a":"o")."
        uccis".($session['user']['sex']?"a":"o")." nella `@Taverna $nomeTaverna `\$ per aver tentato
        di rubare delle gemme!!`3");
        output("`n`&`bMA...`b `7Teg esce dalla cucina proprio mentre hai la mano nella vetrinetta!");
        output("`nFai solo in tempo a vedere l'ira sul suo volto e un'ascia balenare nella sua mano.");
        output("`n`n`b`\$Sei morto!`b");
        output("`nPerdi il `b10%`b della tua esperienza!");
        output("`nPerdi `bTUTTO`b il tuo oro!");
        debuglog("muore per aver tentato di rubare le gemme nella taverna, perde il 10% exp e ".$session['user']['gold']." oro");
        $session['user']['gold'] = 0;
        addnav("Terra delle Ombre","village.php");
    } else if($tegAction == 1 || $tegAction == 2 || $tegAction ==3) {
        addnews("`^".$session['user']['name']." `\$è stat".($session['user']['sex']?"a":"o")."
        buttat".($session['user']['sex']?"a":"o")." fuori dalla `@Taverna $nomeTaverna `\$
        per aver tentato di rubare delle gemme!!`3");
        output("`n`&`bMA...`b `7Teg esce dalla cucina proprio mentre hai la mano nella vetrinetta!");
        output("`nFai solo in tempo a vedere l'ira sul suo volto e un mattarello nella sua mano.");
        if($tegAction == 3 && $session['user']['gems'] >= $nGemme) {
            $session['user']['gems'] -= $nGemme;
            output("`n`nQuando ti riprendi ti ritrovi nella foresta ancora intero anche se con le tasche piu' leggere!");
            debuglog("perde $nGemme gemme per aver tentato di rubare le gemme nella taverna");
        } else if($session['user']['gold'] < 1) {
            $vecchiHP = $session['user']['hitpoints'];
            $percentualeHPPersi = (e_rand(4,9) / 10); // 10%-60%
            $session['user']['hitpoints'] = round($session['user']['hitpoints'] * $percentualeHPPersi,0);
            output("`n`nQuando ti riprendi ti ritrovi nella foresta ancora intero anche se un po' dolorante!");
        } else {
            output("`n`nQuando ti riprendi ti ritrovi nella foresta ancora intero anche se con le tasche vuote!");
            debuglog("perde ".$session['user']['gold']." oro per aver tentato di rubare le gemme nella taverna");
            $session['user']['gold'] = 0;
        }
        addnav("Torna alla Foresta","forest.php?op=esci");
    } else {
        $session['user']['gems'] += $nGemme;

        addnews("`^".$session['user']['name']." `@è riuscit".($session['user']['sex']?"a":"o")." ad impossessarsi di `&$snGemme`@!!`3");
        debuglog("ruba $nGemme gemme dalla taverna");
        output("`n`^Riesci ad afferrare $sGemme e a nascondere la mano in tasca giusto in tempo!");
        output("`nAppena vedi la porta della cucina che si apre ti fiondi nella foresta.");
        addnav("Torna alla Foresta","forest.php?op=esci");
    }
    break;

case "robefood":
    $cattiveria = 5;
    $session['user']['evil'] += $cattiveria;
    $nPiatto = e_rand(1,6); // Piatto rubato
    switch($nPiatto){
        case "1":
              $thePiatto = "uno Stinco al Forno";
              break;
        case "2":
              $thePiatto = "un Arrosto di Maiale";
              break;
        case "3":
              $thePiatto = "un `2Cosciotto di Drago";
              break;
        case "4":
              $thePiatto = "un `2Filetto di Drago";
              break;
        case "5":
              $thePiatto = "un piatto di `2Scaglie di Drago Verde Fritte";
              break;
        case "6":
              $thePiatto = "un `\$Piatto speciale";
              break;
    }
    output("`n`c`b`&Ti infili in cucina e prendi la prima cosa che trovi: $thePiatto `b`c`n");
    $tegAction = e_rand(0,6);

    if($tegAction == 0) {
        $session['user']['alive'] = false;
        $session['user']['hitpoints'] = 0;
        $session['user']['experience'] = round($session['user']['experience'] * .9,0);
        $session['user']['specialinc'] = "";

        addnews("`^".$session['user']['name']." `\$è stat".($session['user']['sex']?"a":"o")."
        uccis".($session['user']['sex']?"a":"o")." nella `@Taverna $nomeTaverna `\$ per aver tentato
        di rubare del cibo!!`3");

        output("`n`&`bMA...`b `7Teg stava proprio dietro di te, e quando ti giri te lo ritrovi davanti!");
        output("`nFai solo in tempo a vedere l'ira sul suo volto e un'ascia balenare nella sua mano.");
        output("`n`n`b`\$Sei morto!`b");
        output("`nPerdi il `b10%`b della tua esperienza!");
        output("`nPerdi `bTUTTO`b il tuo oro!");

        debuglog("muore per aver tentato di rubare il cibo nella taverna, perde il 10% exp e ".$session['user']['gold']." oro");
        $session['user']['gold'] = 0;

        addnav("Terra delle Ombre","village.php");
    } else if($tegAction == 1 || $tegAction == 2 || $tegAction ==3) {

        addnews("`^".$session['user']['name']." `\$è stat".($session['user']['sex']?"a":"o")."
        buttat".($session['user']['sex']?"a":"o")." fuori dalla `@Taverna $nomeTaverna `\$ per aver
        tentato di rubare del cibo!!`3");

        output("`n`&`bMA...`b `7Teg stava proprio dietro di te, e quando ti giri te lo ritrovi davanti!");
        output("`nFai solo in tempo a vedere l'ira sul suo volto e un mattarello nella sua mano.");

        if($tegAction == 3 && $session['user']['gems']>1) {
            $session['user']['gems'] -= 1;
            output("`n`nQuando ti riprendi ti ritrovi nella foresta ancora intero con un biglietto che dice ");
            output("\"Grazie per la tua donazione\"`n");
            output("Hai una gemma in meno!`n");
            debuglog("perde una gemma per aver tentato di rubare del cibo nella taverna");
        } else if($session['user']['gold'] < 1) {
            $vecchiHP = $session['user']['hitpoints'];
            $percentualeHPPersi = (e_rand(4,9) / 10); // 10%-60%
            $session['user']['hitpoints'] = round($session['user']['hitpoints'] * $percentualeHPPersi,0);
            output("`n`nQuando ti riprendi ti ritrovi nella foresta ancora intero anche se un po' dolorante!");
        } else {
            output("`n`nQuando ti riprendi ti ritrovi nella foresta ancora intero anche se con le tasche vuote!");
            debuglog("perde ".$session['user']['gold']." oro per aver tentato di rubare del cibo nella taverna");
            $session['user']['gold'] = 0;
        }

        addnav("Torna alla Foresta","forest.php?op=esci");
    } else {

        addnews("`^".$session['user']['name']." `@è riuscit".($session['user']['sex']?"a":"o")." a rubare
        nella cucina della `@Taverna $nomeTaverna`\$!!`3");

        output("`n`7Riesci ad afferrare $thePiatto `7e a nasconderti giusto in tempo!");
        output("`nAppena vedi che Teg stà tornando ti fiondi nella foresta.`n`n");
        debuglog("è riuscito a rubare del cibo ".$nomeTaverna);

        switch($nPiatto){
                case "1":
                      output("Ti getti sullo stinco e lo divori con la velocità di uno stormo di cavallette.`n");
                      $session['user']['hunger']-=70;
                      break;
                case "2":
                      output("Ti getti sull'arrosto e lo divori con la velocità di uno stormo di cavallette.`n");
                      $session['user']['hunger']-=80;
                      break;
                case "3":
                      output("Ti getti sul Cosciotto e lo divori con la velocità di uno stormo di cavallette.`n`n");
                      $session['user']['hunger']-=80;
                      switch($randomBuff) {
                      case 0:
                           output("Dopo aver mangiato questo piatto succulento ti senti soddisfatto");
                           break;
                      case 1:
                      case 2:
                      case 3:
                      case 4:
                      case 5:
                           $session['bufflist']['dragoneat']=generateBuff("cosciotto",$session['user']['level']);
                           break;
                      }
                      break;
                case "4":
                     output("Ti getti sul filetto e lo divori con la velocità di uno stormo di cavallette.`n`n");
                     $session['user']['hunger']-=70;
                     $randomBuff = e_rand(0,5);
                     switch($randomBuff) {
                             case 0:
                                  output("Dopo aver mangiato questo piatto succulento ti senti soddisfatto");
                                  break;
                             case 1:
                             case 2:
                             case 3:
                             case 4:
                             case 5:
                                  $session['bufflist']['dragoneat']=generateBuff("filetto",$session['user']['level']);
                                  break;
                     }
                     break;
                case "5":
                      output("Un po' contrariato per aver rischiato la vita per delle scaglie di drago, decidi di non ");
                      output("buttarle, ne assaggi una e... WOW sono a dir poco `1F`2A`3N`4T`5A`6S`7T`1I`2C`3H`4E`7`n");
                      output("Tempo pochi secondi e finisci il pasto leccando anche il piatto.`n");
                      $session['user']['charm']+=5;
                      debuglog("guadagna 5 punti fascino rubando le scaglie fritte ".$nomeTaverna);
                      break;
                case "6":
                      $randomBuff = e_rand(0,5);
                      switch($randomBuff) {
                          case 0:
                               output("Mentre mangi questo piatto succulento mordi qualcosa di molto resistente che ");
                               output("ti scheggia un dente!`n Imprecando tiri fuori dalla bocca quella cosa e noti ");
                               output("con stupore che si tratta di una gemma!`n");
                               output("Hai perso qualche punto ferita ma la gemma guadagnata ti fa passare il dolore`n");
                               $session['user']['gems']++;
                               $hplose = intval($session['user']['maxhitpoints']*0.1);
                               $session['user']['hitpoints'] -= $hplose;
                               if ($session['user']['hitpoints'] < 1)  {
                                  $session['user']['hitpoints'] = 1;
                               }
                               break;
                          case 1:
                          case 2:
                          case 3:
                          case 4:
                          case 5:
                               $session['bufflist']['dragoneat']=generateBuff("piattospeciale",$session['user']['level']);
                               break;
                      }
                      break;
        }

        addnav("Torna alla Foresta","forest.php?op=esci");
    }
    break;

// BISCA
case "tali":
    $taliPuntataMinima = 50;
    switch($_GET[action]){
    case "intro":

        output("`n`7Maximus tira fuori 4 strani dadi a 4 facce ed inizia a spiegarti le regole del gioco.");
        output("`n`n`#\"Il gioco si chiama Tali e come puoi vedere questi non sono dadi normali, in realtà ");
        output("non sono neanche dadi...");
        output("Sono delle ossa di montone che fanno parte dell'articolazione del piede ma si prestano benissimo ");
        output("per ottenere risultati casuali come una sorta di dado a quattro facce.`7\"");
        output("`n`n`7Fa una piccola pausa e poi ricomincia.`n`n");
        output(" \"`#Ad ognuna delle quattro facce è attribuito un punteggio: `&1`#, `&3`#, `&4`# e `&6`#.`n");
        output("Il risultato più alto di questo gioco è tirare 4 facce `&diverse`#, poi vengono le combinazioni ");
        output("con almeno due numeri uguali dove ");
        output("a parità di somma vince quello che ha il lancio del singolo dado più alto.");

        output("`n`n`7Si schiarisce la gola.`n`n");

        output(" `7\"`#Dopo questi risultati, se si ottengono 4 facce `&uguali`# vince il lancio più alto, ");
        output("quindi ne conviene che il lancio peggiore è fare quattro `&1`#. `# Ti avverto anche che un solo ");
        output("`&6`# perde quasi sempre...`7\"`n`n");

        output(" `7\"`#Bene, direi che possiamo cominciare, te la senti di giocare oppure vuoi che ti riassuma ");
        output("i punteggi?`7\"`n`n");

        addnav("Tali");
        addnav("`2Accetto la sfida!","forest.php?op=tali&action=puntata");
        addnav("`3Vedi Punteggi","forest.php?op=tali&action=punteggio");
        addnav("Altro");
        addnav("Torna alla Taverna","forest.php?op=entrataverna");
        break;
    case "punteggio":
        output("`n`7Maximus ti porge un antica pergamena, la srotoli e vedi che ci sono riassunti i punteggi ");
        output("del Tali, dal più grande al più piccolo.");
        writeTaliTable();
        addnav("`2Accetto la sfida!","forest.php?op=tali&action=puntata");
        addnav("`2Torna al Tavolo","forest.php?op=tali&action=intro");
        addnav("C?Bisca Clandestina","forest.php?op=bisca");
        break;
    case "puntata":
        $session['tali']['puntata'] = 0;
        output("`n`3\"`#La puntata minima è di `^$taliPuntataMinima`# pezzi d'oro, ci stai o ti ritiri?`3\"`7");
        output("`n`nQuanto vuoi puntare?");
        output("<form action='forest.php?op=tali&action=gioca' method='POST'>",true);
        output("<input id='input' name='puntata' width=5>&nbsp;<input type='submit' class='button' value='Punta'></form>",true);
        output("<script language='javascript'>document.getElementById('input').focus();</script>",true);

        addnav("C?Bisca Clandestina","forest.php?op=bisca");
        addnav("","forest.php?op=tali&action=gioca");
        break;
    case "gioca":
        if($session['tali']['puntata'] == 0){
            $nuovaPuntata = $_POST[puntata];
            if($nuovaPuntata < $taliPuntataMinima){
                output("`n`7Maximus appoggia la mano sul tuo oro e lo spinge spazientito verso di te `3\"`#Forse ");
                output("sei duro di orecchi... Ho detto che la puntata minima è di `^$taliPuntataMinima`# pezzi d'oro, questi spiccioli vai a buttarli nel lago!`3\"`7");
                addnav("Torna a Puntare","forest.php?op=tali&action=puntata");
                addnav("C?Bisca Clandestina","forest.php?op=bisca");
                break;
            }
            if($nuovaPuntata > $session['user']['gold']){
                output("`n`7Maximus ti scruta bene, si mette comodo sulla sedia e accavalla le gambe:`n`n");
                output(" \"`#Pensi seriamente di potermi imbrogliare, vero? Sò perfettamente che non puoi ");
                output("permetterti di scommettere così tanto...");
                output("Ti consiglio vivamente di andartene il più velocemente possibile da questa taverna, ");
                output("prima che ti possa succedere, come dire, qualcosa di spiacevole.`7\"`n`n");
                output("Un ghigno malefico compare sul suo viso e timoroso che la sua collera si abbatta su di te, ");
                output("esci dalla taverna.`nOggi ti senti fortunato.");

                addnav("Esci dalla taverna","forest.php?op=esci");
                break;
            }
            $session['tali']['puntata'] = $nuovaPuntata;
        }
        $session['user']['gold'] -= $session['tali']['puntata'];
        output("`n`7Mettete tutti e due `^".$session['tali']['puntata']." `7Pezzi D'Oro sul tavolo e poi lanci i dadi ");
        output("nella speranza di vincere.`n");
        $player = array(taliRoll(),taliRoll(),taliRoll(),taliRoll());
        $player = sortTaliResult($player);

        output("`n`7Il risultato dei tuoi lanci è: `&".$player[0]."`7, `&".$player[1]."`7, `&".$player[2]."`7, ");
        output("`&".$player[3]."`7 `n");
        checkTaliResult("1",$player);

        // Maximus è un baro e prende il risultato migliore su 3 lanci
        output("`n`n`n`7Maximus prende a sua volta i dadi ed effettua il suo lancio.`n");

        $maximus1 = array(taliRoll(),taliRoll(),taliRoll(),taliRoll());
        $maximus1 = sortTaliResult($maximus1);
        //output("`n`7Il Primo risultato dei lanci di `\$Maximus`7 è: `&".$maximus1[0]."`7, `&".$maximus1[1]."`7, `&".$maximus1[2]."`7, `&".$maximus1[3]."`7 `n");
        $maximus2 = array(taliRoll(),taliRoll(),taliRoll(),taliRoll());
        $maximus2 = sortTaliResult($maximus2);
        //output("`n`7Il Secondo risultato dei lanci di `\$Maximus`7 è: `&".$maximus2[0]."`7, `&".$maximus2[1]."`7, `&".$maximus2[2]."`7, `&".$maximus2[3]."`7 `n");
        $maximus3 = array(taliRoll(),taliRoll(),taliRoll(),taliRoll());
        $maximus3 = sortTaliResult($maximus3);
        //output("`n`7Il Terzo risultato dei lanci di `\$Maximus`7 è: `&".$maximus3[0]."`7, `&".$maximus3[1]."`7, `&".$maximus3[2]."`7, `&".$maximus3[3]."`7 `n");


        $maximus = getMaxTaliRoll($maximus1,$maximus2,$maximus3);
        $maximus = sortTaliResult($maximus);

        output("`n`7Il risultato dei lanci di Maximus è: `&".$maximus[0]."`7, `&".$maximus[1]."`7, `&".$maximus[2]."`7, `&".$maximus[3]."`7 `n");
        checkTaliResult("2",$maximus);

        $winner = winnerTali($player,$maximus);
        if ($winner == 1) {
           output("`n`n`n \"`#Per questa volta hai vinto tu, fortunello!`7\"`n");
           output("Avvicina a te la sua puntata...Hai vinto `^".$session['tali']['puntata']." `7Pezzi D'Oro.");
           $session['user']['gold']+=$session['tali']['puntata']*2;
           debuglog("vince ".$session['tali']['puntata']." oro nella taverna giocando a tali");
        } elseif ($winner == 2) {
           output("`n`n`n \"`#Mi dispiace ma ho vinto io!`7\"`n");
           output("E prima che te ne accorga le tue monete sono sparite dal tavolo...Hai perso `^".$session['tali']['puntata']." `7Pezzi D'Oro.");
           debuglog("perde ".$session['tali']['puntata']." oro nella taverna giocando a tali");
        } else {
           output("`n`n`n \"`#Maledetti dadi! Mi dispiace ma non ha vinto nessuno`7\"`n");
           output("Riprendi la tua puntata e Maximus`7 fa altrettanto.");
           $session['user']['gold']+=$session['tali']['puntata'];
        }
        output("`n`n\"`#Vuoi riprovare?`7\"`n");

        writeTaliTable();

        addnav("Torna a Puntare","forest.php?op=tali&action=puntata");
        addnav("Torna alla Taverna","forest.php?op=entrataverna");
        break;

    }
    break;

case "toki":
    $tokiPuntataMinima = 50;

    switch($_GET[action]){
    case "intro":
        output("`n`7Maximus tira fuori 2 dadi a 6 facce ed inizia a spiegarti le regole del gioco.");
        output("`n`n`#\"Il gioco funziona così: io lancerò i dadi coprendoli con la mano e ti dirò il risultato. ");
        output("Per non perdere tu dovrai tirare a tua volta i dadi coperti e dichiarare un risultato uguale o ");
        output("maggiore al mio, così toccherà di nuovo a me tirare i dadi coperti e dichiarare un risultato ");
        output("uguale o maggiore del tuo e così via.");
        output("`nNaturalmente si può imbrogliare dichiarando un risultato diverso da quello reale ed il gioco ");
        output("sta proprio qui! Se dubiti della mia dichiarazione non hai che da dirlo e ti farò vedere il ");
        output("risultato reale. Se ho mentito perdo, se non ho mentito però perdi tu!\"`7");
        output("`n`n`#\"Per calcolare il risultato dei dadi basta mettere il maggiore dei due come decine e il ");
        output("minore come unità. I doppi valgono più degli altri risultati e il `b`@21`b`# (che sarebbe il più ");
        output("basso) in realtà è `b`@Toki`b `#e batte ogni altro risultato. Eccoli in ordine su questa tavola ");
        output("dal maggiore al minore.\"`7`n`n");

        writeTokiTable();

        addnav("Toki");
        addnav("`2Accetto la sfida!","forest.php?op=toki&action=puntata");
        addnav("Altro");
        addnav("Torna alla Taverna","forest.php?op=entrataverna");
        break;
    case "puntata":
        $session[toki] = array('tiro' => 0, 'dichiarato' => 0, 'precedente' => 0, 'puntata' => 0);
        output("`n`3\"`#La puntata minima è di `^$tokiPuntataMinima`# pezzi d'oro, ci stai o ti ritiri?`3\"`7");
        output("`n`nQuanto vuoi puntare?");
        output("<form action='forest.php?op=toki&action=avversario' method='POST'>",true);
        output("<input id='input' name='puntata' width=5>&nbsp;<input type='submit' class='button' value='Punta'></form>",true);
        output("<script language='javascript'>document.getElementById('input').focus();</script>",true);

        addnav("C?Bisca Clandestina","forest.php?op=bisca");
        addnav("","forest.php?op=toki&action=avversario");
        break;
    case "avversario":
        // Controllo puntata
        if($session['toki']['puntata'] == 0){
            $nuovaPuntata = $_POST[puntata];
            //output("`n[DEBUG] Puntata: $nuovaPuntata");
            if($nuovaPuntata < $tokiPuntataMinima){
                output("`n`7Maximus appoggia la mano sul tuo oro e lo spinge di nuovo verso di te `3\"`#La ");
                output("puntata minima è di `^$tokiPuntataMinima`# pezzi d'oro, con questi spiccioli magari ");
                output("puoi giocare con dei bambini!`3\"`7");

                addnav("Torna a Puntare","forest.php?op=toki&action=puntata");
                addnav("C?Bisca Clandestina","forest.php?op=bisca");
                break;
            }
            if($nuovaPuntata > $session['user']['gold']){
                output("`n`7Maximus ti scruta e dice: `3\"`#Fammi vedere la grana se davvero ce l'hai!`3\"`7");
                output("`nCerchi per bene nel fondo delle tasche ma non riesci a tirar fuori abbastanza oro ");
                output("da coprire la puntata.");
                output("`n`n`3\"`#Cercavi di fregarmi quindi! Vedi di non provarci più o ti faccio buttar fuori!`3\"`7");

                addnav("Torna a Puntare","forest.php?op=toki&action=puntata");
                addnav("C?Bisca Clandestina","forest.php?op=bisca");
                break;
            }
            $session['toki']['puntata'] = $nuovaPuntata;
            $session['user']['gold'] -= $session['toki']['puntata'];
            output("`n`7Ammucchi i tuoi `^".$session['toki']['puntata']."`7 pezzi d'oro sul tavolo");
        }

        $dubito = false;
        if($_GET[dichiara] > 0){
            $session['toki']['dichiarato'] = $_GET[dichiara];

            // Decide se dubitare
            $possibilitaVittoria = getTokiWinChance($session['toki']['dichiarato']);
            //output("`n[DEBUG] Possibilita' di vittoria: $possibilitaVittoria");
            $decisioneSu36 = e_rand(1,36);
            //output("`n[DEBUG] Decisione Su 36: $decisioneSu36");
            if($decisioneSu36 > $possibilitaVittoria){
                $dubito = true;
            } else if($possibilitaVittoria < 6){ // Per far dubitare anche su dichiarazioni basse
                if(($session['toki']['dichiarato'] - $session['toki']['precedente']) < (count($valori) * 0.3) && e_rand(1,10) <= 4 ){
                    $dubito = true;
                }
            }
        }

        //output("`n[DEBUG] Tiro: ".$session['toki']['tiro']."; Dichiarato: ".$session['toki']['dichiarato']);
        if($session['toki']['dichiarato'] > 0){
            $tiroPrecedente = tokiToString($session['toki']['dichiarato']);
            output("`n`7Hai dichiarato `3\"`#$tiroPrecedente`3\"`7`n");
        }

        if($dubito){
            output("`n`7Maximus ti guarda sospettoso e sbattendo un pugno sul tavolo esclama: `3\"`b`4DUBITO!!`b`3\"`7`n");

            if(strcmp("".$session['toki']['dichiarato'],"".$session['toki']['tiro']) == 0){
                $session['user']['gold'] += $session['toki']['puntata']*2;
                debuglog("vince ".$session['toki']['puntata']." oro nella taverna giocando a toki");
                //http://www.mambodev.com/content/view/17/50/
                output("`n`7Fai un gran sorrisone a Maximus e sollevando la mano gli mostri i dadi");
                output("`nMaximus guarda i dadi ed effettivamente il risultato è `^".tokiToString($session['toki']['tiro'])."`7 come avevi dichiarato!");
                output("`n`n`3\"`b`^HAI VINTO!`b`# Vuoi darmi la rivincita?`3\"`7");
            } else {
                debuglog("perde ".$session['toki']['puntata']." oro nella taverna giocando a toki");
                output("`n`7Sollevi la mano riluttante mostrandogli il risultato reale che è `^");
                output(tokiToString($session['toki']['tiro'])."`7 e non `^");
                output(tokiToString($session['toki']['dichiarato'])."`7 come invece gli avevi detto!");
                output("`n`n`3\"`b`4HAI PERSO!`b`# Vuoi riprovare o ti ritiri?`3\"`7");
            }
            writeTokiTable();

            addnav("Torna a Puntare","forest.php?op=toki&action=puntata");
            addnav("C?Bisca Clandestina","forest.php?op=bisca");
            addnav("Torna alla Taverna","forest.php?op=entrataverna");
            break;
        }

        if($session['toki']['dichiarato'] > 0){
            output("`n`7Maximus ti guarda un attimo poi esclama: `3\"`b`^ACCETTO!!`b`3\"`7`n");
        }
        $session['toki']['tiro'] = tokiRoll();
        //output("`n[DEBUG] Nuovo Tiro: ".$session[toki][tiro]);
        if(getTokiValuePosition($session['toki']['tiro']) < getTokiValuePosition($session['toki']['dichiarato'])){
            $session['toki']['dichiarato'] = fakeTokiRoll($session['toki']['dichiarato']);
            //output("`n[DEBUG] Nuovo Dichiarato: ".$session[toki][dichiarato]);
        } else {
            $session['toki']['dichiarato'] = $session['toki']['tiro'];
        }

        $espressione = "impassibile"; // TODO Da rendere casuale un po' influenzata dal risultato dei dadi
        $session['toki']['precedente'] = $session['toki']['dichiarato'];
        $sTiro = tokiToString($session['toki']['dichiarato']);

        output("`nLancia i dadi coprendoli con la mano, li osserva $espressione ed esclama: `3\"`#$sTiro`#!`3\"`7");
        output("`n`nCome rispondi?");

        writeTokiTable();

        addnav("Toki");
        addnav("`2Accetto!","forest.php?op=toki&action=accetta");
        addnav("`\$Dubito!","forest.php?op=toki&action=dubita");
        break;
    case "accetta":
        $tiroPrecedente = tokiToString($session['toki']['dichiarato']);
        $session['toki']['tiro'] = tokiRoll();
        $sTiro = tokiToString($session['toki']['tiro']);

        output("`n`7Maximus ha dichiarato `3\"`b`#$tiroPrecedente`b`3\"`7");
        output("`nAccetti di provare a battere il risultato di Maximus e lanci i dadi coprendoli con la mano e...");
        output("`n`nOttieni `b`^$sTiro`b`7!");

        if(getTokiValuePosition($session['toki']['tiro']) < getTokiValuePosition($tiroPrecedente)){
            output("`nPurtroppo non basta per battere Maximus, devi dichiarare un valore maggiore per evitare ");
            output("di perdere");
        } else {
            output("`nBuon tiro! Puoi però provare ad imbrogliare e dichiarare un altro risultato più alto ");
            output("del suo, a te la scelta");
        }

        writeTokiTable();

        addnav("Dichiara");
        for($i = getTokiValuePosition($session['toki']['dichiarato']); $i < count($valori); $i++){
            $label = "Dichiara un ".tokiToString($valori[$i]);
            if(tokiToString($valori[$i]) == $session['toki']['tiro']){
                $label = "`b`^".$label."`b";
            }
            addnav($label,"forest.php?op=toki&action=avversario&dichiara=".$valori[$i]);
        }

        break;
    case "dubita":
        if(strcmp("".$session['toki']['dichiarato'],"".$session['toki']['tiro']) == 0){
            output("`n`7Maximus fa un gran sorrisone e sollevando la mano mostra i dadi e ti dice: `3\"`#Hai perso!`3\"`7");
            output("`nGuardi i dadi ed effettivamente il risultato è `^".tokiToString($session['toki']['tiro'])."`7 come aveva dichiarato!");
            output("`n`n`3\"`b`4HAI PERSO!`b`# Vuoi riprovare o ti ritiri?`3\"`7");
            debuglog("perde ".$session['toki']['puntata']." oro nella taverna giocando a toki");
        } else {
            $session['user']['gold'] += $session['toki']['puntata']*2;

            debuglog("vince ".$session['toki']['puntata']." oro nella taverna giocando a toki");
            output("`n`7Maximus solleva la mano mostrandoti i dadi e vedi che il risultato reale è `^");
            output(tokiToString($session['toki']['tiro'])."`7 e non `^".tokiToString($session['toki']['dichiarato']));
            output("`7 come invece ti aveva detto!");
            output("`n`n`3\"`b`^HAI VINTO!`b`# Vuoi darmi la rivincita?`3\"`7");
        }

        writeTokiTable();

        addnav("Torna a Puntare","forest.php?op=toki&action=puntata");
        addnav("C?Bisca Clandestina","forest.php?op=bisca");
        addnav("Torna alla Taverna","forest.php?op=entrataverna");
        break;
    }
    break;

// USCITA
case "esci":
    $session['user']['specialinc']="";
    $session['scagliaverdeeat'] = 1;
    $session['scagliaverdedrink'] = null;
    output("`@`nDecidi di non rischiare, e ti allontani velocemente dalla Taverna.`n`n");
    addnav("Torna alla foresta","forest.php");
    //redirect("forest.php");
}
rawoutput("<br><br><div style=\"text-align: right;\"><font color=\"#00FFFF\">La Taverna della </font><font color=\"#33FF33\">Scaglia Verde Fritta </font><font color=\"#FFFF33\">by Teg</font><br>");
page_footer();
?>

