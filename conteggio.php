<?php
require_once "common.php";
page_header("Conteggio Punti Carriera");
$sql="SELECT dio, SUM(punti_carriera) AS puntitotali, COUNT(*) AS player FROM accounts WHERE dio > 0  AND superuser = 0 GROUP BY dio ORDER BY dio ASC";
$result = db_query($sql);

$countrow = db_num_rows($result);
for ($i=0; $i<$countrow; $i++){
//for ($i=0 ; $i < db_num_rows($result) ; $i++){
    $row = db_fetch_assoc($result);
    if ($row['dio']==1) {
       $totalebuoni = $row['puntitotali'];
       $tb = $row['puntitotali'];
       $puntibuoni = getsetting("puntibuoni", 0);
       $totalebuoni -= $puntibuoni;
       $partecipantibuoni = $row['player'];
    } elseif ($row['dio']==2) {
       $totalecattivi = $row['puntitotali'];
       $tc = $row['puntitotali'];
       $punticattivi = getsetting("punticattivi", 0);
       $totalecattivi -= $punticattivi;
       $partecipanticattivi = $row['player'];
    }  else {
       $totaledrago = $row['puntitotali'];
       $td = $row['puntitotali'];
       $puntidrago = getsetting("puntidrago", 0);
       $totaledrago -= $punticattivi;
       $partecipantidrago = $row['player'];
    }

}
$sql="SELECT dio, SUM(punti_carriera) AS puntitotali, COUNT(*) AS player FROM accounts WHERE dio > 0  AND superuser = 0 AND (carriera < 5 OR carriera > 8) GROUP BY dio ORDER BY dio ASC";
$result = db_query($sql);

$countrow = db_num_rows($result);
for ($i=0; $i<$countrow; $i++){
//for ($i=0 ; $i < db_num_rows($result) ; $i++){
    $row = db_fetch_assoc($result);
    if ($row['dio']==1) {
       $totb = $row['puntitotali'];
       $pb = $row['player'];
    } elseif ($row['dio']==2) {
       $totc = $row['puntitotali'];
       $pc = $row['player'];
    } else {
       $totd = $row['puntitotali'];
       $pd = $row['player'];
    }

}

output("<table cellspacing=4 cellpadding=2 align='center'>", true);
output("<tr><td colspan=4 align='center' bgcolor='#FF0000'>`b<big><big>`&Vecchio Sistema</big></big>`b</td></tr>",true);
output("<tr><td></td><td bgcolor='#222255'>`b`^<big><big>Fedeli di Sgrios</big></big>`b</td><td bgcolor='#225555'>`b`\$<big><big>Seguaci di Karnak</big></big>`b</td><td bgcolor='#552255'>`b`@<big><big>Seguaci del Drago</big></big>`b</td></tr>", true);
output("<tr><td align='center' bgcolor='#777777'><big><big>`b`&Conteggio (Tutti)`b</big></big></td><td align='center' bgcolor='#555555'>`b`^<big><big>$tb</big></big>`b</td><td align='center' bgcolor='#555555'>`b`\$<big><big>$tc</big></big>`b</td><td align='center' bgcolor='#555555'>`b`@<big><big>$td</big></big>`b</td></tr>",true);
output("<tr><td align='center' bgcolor='#777777'><big><big>`b`&Conteggio (Seguaci)`b</big></big></td><td align='center' bgcolor='#555555'>`b`^<big><big>$totb</big></big>`b</td><td align='center' bgcolor='#555555'>`b`\$<big><big>$totc</big></big>`b</td><td align='center' bgcolor='#555555'>`b`@<big><big>$totd</big></big>`b</td></tr>",true);
output("<tr><td align='center' bgcolor='#33CC33'><big><big>`!Punti Totali</big></big></td><td align='center' bgcolor='#222288'>`b`6<big><big>$totalebuoni</big></big>`b</td><td align='center' bgcolor='#228888'>`b`4<big><big>$totalecattivi</big></big>`b</td><td align='center' bgcolor='#662266'>`b`2<big><big>$totaledrago</big></big>`b</td></tr>",true);
output("<tr><td align='center' bgcolor='#33CC33'><big><big>`!N.Player</big></big></td><td align='center' bgcolor='#222288'>`b`6<big><big>$partecipantibuoni</big></big>`b</td><td align='center' bgcolor='#228888'>`b`4<big><big>$partecipanticattivi</big></big>`b</td><td align='center' bgcolor='#662266'>`b`2<big><big>$partecipantidrago</big></big>`b</td></tr>",true);
$buoni = round(($totalebuoni/$partecipantibuoni),2);
$cattivi = round(($totalecattivi/$partecipanticattivi),2);
$drago = round(($totaledrago/$partecipantidrago),2);
output("<tr><td align='center' bgcolor='#33CC33'><big><big>`!Punti/Player</big></big></td><td align='center' bgcolor='#222288'>`b`6<big><big>$buoni</big></big>`b</td><td align='center' bgcolor='#228888'>`b`4<big><big>$cattivi</big></big>`b</td><td align='center' bgcolor='#662266'>`b`2<big><big>$drago</big></big>`b</td>",true);
output("</tr></table>`n`n`n", true);

    $sql="SELECT dio, COUNT(*) AS player FROM accounts WHERE dio > 0 AND superuser = 0 GROUP BY dio ORDER BY dio ASC";
    $result = db_query($sql);
    $countrow = db_num_rows($result);
    for ($i=0; $i<$countrow; $i++){
    //for ($i=0;$i<db_num_rows($result);$i++){
        $row = db_fetch_assoc($result);
        if ($row['dio'] == 1) {
           $partecipantibuoni = $row['player'];
        }elseif ($row['dio'] == 2) {
           $partecipanticattivi = $row['player'];
        }else {
           $partecipantidrago = $row['player'];
        }

    }
    $puntibuoni = getsetting("puntisgrios",0);
    $punticattivi = getsetting("puntikarnak",0);
    $puntidrago = getsetting("puntidrago",0);
    $buoni = round(($puntibuoni/$partecipantibuoni),2);
    $cattivi = round(($punticattivi/$partecipanticattivi),2);
    $drago = round(($puntidrago/$partecipantidrago),2);
    output("<table cellspacing=4 cellpadding=2 align='center'>", true);
    output("<tr><td colspan=4 align='center' bgcolor='#FF0000'>`b<big><big>`@Nuovo Sistema</big></big>`b</td></tr>",true);
    output("<tr><td></td><td bgcolor='#222255'>`b`^<big><big>Fedeli di Sgrios</big></big>`b</td><td bgcolor='#225555'>`b`\$<big><big>Seguaci di Karnak</big></big>`b</td><td bgcolor='#552255'>`b`@<big><big>Seguaci del Drago</big></big>`b</td></tr>",true);
    output("<tr><td align='center' bgcolor='#33CC33'><big><big>`!Punti Totali</big></big></td><td align='center' bgcolor='#222288'>`b`6<big><big>$puntibuoni</big></big>`b</td><td align='center' bgcolor='#228888'>`b`4<big><big>$punticattivi</big></big>`b</td><td align='center' bgcolor='#662266'>`b`2<big><big>$puntidrago</big></big>`b</td></tr>",true);
    output("<tr><td align='center' bgcolor='#33CC33'><big><big>`!N.Player</big></big></td><td align='center' bgcolor='#222288'>`b`6<big><big>$partecipantibuoni</big></big>`b</td><td align='center' bgcolor='#228888'>`b`4<big><big>$partecipanticattivi</big></big>`b</td><td align='center' bgcolor='#662266'>`b`2<big><big>$partecipantidrago</big></big>`b</td></tr>",true);
    output("<tr><td align='center' bgcolor='#33CC33'><big><big>`!Punti/Player</big></big></td><td align='center' bgcolor='#222288'>`b`6<big><big>$buoni</big></big>`b</td><td align='center' bgcolor='#228888'>`b`4<big><big>$cattivi</big></big>`b</td><td align='center' bgcolor='#662266'>`b`4<big><big>$drago</big></big>`b</td>",true);

    output("</tr></table>`n`n", true);

$sql="SELECT dio, SUM(punti_carriera) AS puntitotali, COUNT(*) AS player FROM accounts WHERE superuser = 0 AND carriera > 4 AND carriera < 9 GROUP BY dio ORDER BY dio ASC";
    $result = db_query($sql);
    $countrow = db_num_rows($result);
    for ($i=0; $i<$countrow; $i++){
    //for ($i=0;$i<db_num_rows($result);$i++){
        $row = db_fetch_assoc($result);
        if ($row['dio'] == 0) {
           $fabbriat = $row['player'];
           $fabbria = $row['puntitotali'];
           $puntia = round(($fabbria/$fabbriat),2);
        }else if ($row['dio'] == 1) {
           $fabbrist = $row['player'];
           $fabbris = $row['puntitotali'];
           $puntis = round(($fabbris/$fabbrist),2);
        }else if ($row['dio'] == 2) {
           $fabbrikt = $row['player'];
           $fabbrik = $row['puntitotali'];
           $puntik = round(($fabbrik/$fabbrikt),2);
        }else {
           $fabbridt = $row['player'];
           $fabbrid = $row['puntitotali'];
           $puntid = round(($fabbrid/$fabbridt),2);
        }

    }
    $fabbritot = $fabbriat + $fabbrist + $fabbrikt + $fabbridt;
    $fabbriptot = $fabbria + $fabbris + $fabbrik + $fabbrid;
    $puntitot = round(($fabbriptot/$fabbritot),2);
    output("<table cellspacing=4 cellpadding=2 align='center'>", true);
    output("<tr><td colspan=4 align='center' bgcolor='#0000FF'>`b<big><big>`#Punti Carriera Fabbri</big></big>`b</td></tr>",true);
    output("<tr><td bgcolor='#222255'>`b`^<big><big>Fede</big></big>`b</td><td bgcolor='#225555'>`b`\$<big><big>Punti Carriera</big></big>`b</td><td bgcolor='#225555'>`b`\$<big><big>N° Player</big></big>`b</td><td bgcolor='#225555'>`b`\$<big><big>Punti/Player</big></big>`b</td></tr>",true);
    output("<tr><td align='center' bgcolor='#33CC33'><big><big>`&`bAgnostici`b</big></big></td><td align='center' bgcolor='#222288'>`b<big><big>`&$fabbria</big></big>`b</td><td align='center' bgcolor='#222288'>`b<big><big>`&$fabbriat</big></big>`b</td><td align='center' bgcolor='#222288'>`b<big><big>`&$puntia</big></big>`b</td></tr>",true);
    output("<tr><td align='center' bgcolor='#222255'><big><big>`^`bSgrios`b</big></big></td><td align='center' bgcolor='#222288'>`b<big><big>`^$fabbris</big></big>`b</td><td align='center' bgcolor='#222288'>`b<big><big>`^$fabbrist</big></big>`b</td><td align='center' bgcolor='#222288'>`b<big><big>`^$puntis</big></big>`b</td></tr>",true);
    output("<tr><td align='center' bgcolor='#225555'><big><big>`\$`bKarnak`b</big></big></td><td align='center' bgcolor='#227070'>`b<big><big>`4$fabbrik</big></big>`b</td><td align='center' bgcolor='#227070'>`b<big><big>`4$fabbrikt</big></big>`b</td><td align='center' bgcolor='#227070'>`b<big><big>`4$puntik</big></big>`b</td></tr>",true);
    output("<tr><td align='center' bgcolor='#552255'><big><big>`@`bDrago`b</big></big></td><td align='center' bgcolor='#662266'>`b<big><big>`4$fabbrid</big></big>`b</td><td align='center' bgcolor='#662266'>`b<big><big>`4$fabbridt</big></big>`b</td><td align='center' bgcolor='#662266'>`b<big><big>`4$puntid</big></big>`b</td></tr>",true);
    output("<tr><td align='center' bgcolor='#3333CC'><big><big>`(`bTotali`b</big></big></td><td align='center' bgcolor='#222288'>`b<big><big>`($fabbriptot</big></big>`b</td><td align='center' bgcolor='#222288'>`b<big><big>`($fabbritot</big></big>`b</td><td align='center' bgcolor='#222288'>`b<big><big>`($puntitot</big></big>`b</td></tr>",true);

    output("</table>", true);
/// maghi

$sql="SELECT dio, SUM(punti_carriera) AS puntitotali, COUNT(*) AS player FROM accounts WHERE superuser = 0 AND carriera > 40 AND carriera < 45 GROUP BY dio ORDER BY dio ASC";
    $result = db_query($sql);
    $countrow = db_num_rows($result);
    for ($i=0; $i<$countrow; $i++){
    //for ($i=0;$i<db_num_rows($result);$i++){
        $row = db_fetch_assoc($result);
        if ($row['dio'] == 0) {
           $fabbriat = $row['player'];
           $fabbria = $row['puntitotali'];
           $puntia = round(($fabbria/$fabbriat),2);
        }else if ($row['dio'] == 1) {
           $fabbrist = $row['player'];
           $fabbris = $row['puntitotali'];
           $puntis = round(($fabbris/$fabbrist),2);
        }else if ($row['dio'] == 2) {
           $fabbrikt = $row['player'];
           $fabbrik = $row['puntitotali'];
           $puntik = round(($fabbrik/$fabbrikt),2);
        }else {
           $fabbridt = $row['player'];
           $fabbrid = $row['puntitotali'];
           $puntid = round(($fabbrid/$fabbridt),2);
        }

    }
    $fabbritot = $fabbriat + $fabbrist + $fabbrikt + $fabbridt;
    $fabbriptot = $fabbria + $fabbris + $fabbrik + $fabbrid;
    $puntitot = round(($fabbriptot/$fabbritot),2);
    output("<table cellspacing=4 cellpadding=2 align='center'>", true);
    output("<tr><td colspan=4 align='center' bgcolor='#0000FF'>`b<big><big>`#Punti Carriera Maghi</big></big>`b</td></tr>",true);
    output("<tr><td bgcolor='#222255'>`b`^<big><big>Fede</big></big>`b</td><td bgcolor='#225555'>`b`\$<big><big>Punti Carriera</big></big>`b</td><td bgcolor='#225555'>`b`\$<big><big>N° Player</big></big>`b</td><td bgcolor='#225555'>`b`\$<big><big>Punti/Player</big></big>`b</td></tr>",true);
    output("<tr><td align='center' bgcolor='#33CC33'><big><big>`&`bAgnostici`b</big></big></td><td align='center' bgcolor='#222288'>`b<big><big>`&$fabbria</big></big>`b</td><td align='center' bgcolor='#222288'>`b<big><big>`&$fabbriat</big></big>`b</td><td align='center' bgcolor='#222288'>`b<big><big>`&$puntia</big></big>`b</td></tr>",true);
    output("<tr><td align='center' bgcolor='#222255'><big><big>`^`bSgrios`b</big></big></td><td align='center' bgcolor='#222288'>`b<big><big>`^$fabbris</big></big>`b</td><td align='center' bgcolor='#222288'>`b<big><big>`^$fabbrist</big></big>`b</td><td align='center' bgcolor='#222288'>`b<big><big>`^$puntis</big></big>`b</td></tr>",true);
    output("<tr><td align='center' bgcolor='#225555'><big><big>`\$`bKarnak`b</big></big></td><td align='center' bgcolor='#227070'>`b<big><big>`4$fabbrik</big></big>`b</td><td align='center' bgcolor='#227070'>`b<big><big>`4$fabbrikt</big></big>`b</td><td align='center' bgcolor='#227070'>`b<big><big>`4$puntik</big></big>`b</td></tr>",true);
    output("<tr><td align='center' bgcolor='#552255'><big><big>`@`bDrago`b</big></big></td><td align='center' bgcolor='#662266'>`b<big><big>`4$fabbrid</big></big>`b</td><td align='center' bgcolor='#662266'>`b<big><big>`4$fabbridt</big></big>`b</td><td align='center' bgcolor='#662266'>`b<big><big>`4$puntid</big></big>`b</td></tr>",true);
    output("<tr><td align='center' bgcolor='#3333CC'><big><big>`(`bTotali`b</big></big></td><td align='center' bgcolor='#222288'>`b<big><big>`($fabbriptot</big></big>`b</td><td align='center' bgcolor='#222288'>`b<big><big>`($fabbritot</big></big>`b</td><td align='center' bgcolor='#222288'>`b<big><big>`($puntitot</big></big>`b</td></tr>",true);

    output("</table>", true);


/// fine maghi
addnav("Villaggio","village.php");
addnav("Conteggio","conteggio.php");
page_footer();

?>