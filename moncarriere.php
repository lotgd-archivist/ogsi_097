<?php
require_once "common.php";
page_header("Le Carriere di LoGD");
$rango=array(1=>"`3Seguace`0",
             2=>"`3Accolito`0",
             3=>"`3Chierico`0",
             4=>"`3Sacerdote`0",
             9=>"`#Gran Sacerdote`0",
             5=>"`2Garzone`0",
             6=>"`2Apprendista`0",
             7=>"`2Fabbro`0",
             8=>"`@Mastro Fabbro`0",
             10=>"`4Invasato`0",
             11=>"`4Fanatico`0",
             12=>"`4Posseduto`0",
             13=>"`%Maestro delle Tenebre`0",
             15=>"`\$Falciatore di Anime`0",
             16=>"`(Portatore di Morte`0",
             17=>"`@Sommo Chierico`0",
             41=>"`6Iniziato`0",
             42=>"`6Stregone`0",
             43=>"`6Mago`0",
             44=>"`VArcimago`0",
             50=>"`8Stalliere dei Draghi`0",
             51=>"`8Scudiero dei Draghi`0",
             52=>"`8Cavaliere dei Draghi`0",
             53=>"`(Mastro dei Draghi`0",
             54=>"`(Dominatore di Draghi`0",
             55=>"`^Cancelliere dei Draghi`0"
             );
addnav("D?Torna dal Druido","mondruido.php");
addnav("M?Torna al Monastero","monastero.php");
if (getsetting("vedibio","si") == "si"){
   addnav("F?I Migliori Fabbri","moncarriere.php?op=fabbro");
   addnav("A?I Maghi più saggi","moncarriere.php?op=mago");
   if(moduli('caserma')=='on')addnav("I Militari Migliori","moncarriere.php?op=militare");
   addnav("D?I Fedeli più Devoti","moncarriere.php?op=fede");
   addnav("S?I Seguaci più Malvagi","moncarriere.php?op=cattivi");
   addnav("I Draghisti Migliori","moncarriere.php?op=draghisti");
}
addnav("B?I Break Point","moncarriere.php?op=breakpoint");
if ($_GET['op']==""){
    output("`!`b`c<font size='+1'>Le Carriere del Villaggio</font>`c`b`n`n",true);
    output("`)Come sanno tutti gli abitanti del villaggio, è possibile intraprendere una carriera. Attualmente la scelta ");
    output("è tra la carriera di fabbro, di mago, la carriera eclesiastica, quella di seguace del malvagio Karnak o quella di adepto del Drago Verde.`n`n ");
    output("`@Per seguire la carriera di fabbro dovrete recarvi da Oberon, un rude ma efficace fabbro, che per un piccolo ");
    output("obolo vi trasmetterà tutte le sue conoscenze. Mentre progredirete nell'arte del fabbro avrete anche la  ");
    output("possibilità di forgiare degli oggetti, che potranno esservi utili per il mestiere di guerriero. Potrete inoltre riparare armi, armature e oggetti di altri guerrieri guadagnando una parte del costo della riparazione.`n`n");
    output("`VPer intraprendere la carriera di mago dovrete parlare con Amon, un mago molto saggio, alla Torre della Magia; per un piccolo ");
    output("compenso Amon ed Ithine vi accetteranno come iniziati e vi insegneranno a generare il mana e ad utilizzare la magia. Mentre migliorerete come maghi imparerete a ");
    output("preparare e lanciare incantesimi sempre più potenti, che sono sempre utili in combattimento. Inoltre dal grado di stregoni riceverete una quota del costo di ogni oggetto magico rigenerato.`n`n");
    output("`#Per la carriera eclesiastica dovrete invece recarvi alla grande chiesa al centro del villaggio, e dichiarare ");
    output("la vostra fede a `^Sgrios`#. Anche in questa carriera potrete ottenere dei bonus con delle suppliche a `^Sgrios`#.`n`n");
    output("`4Se invece decidete di assecondare i vostri istinti più malvagi, dovete recarvi alla Grotta di `\$Karnak`4, aderendo ");
    output("alla malvagia setta. Anche in questo caso potete ottenere dei bonus con sacrifici in onore di `\$Karnak`4.`n`n");
    output("`(Se al contrario ritenete il `@Drago Verde `(l'unica divinità di Rafflingate, adesso potete assecondare la vostra ");
    output("inclinazione ed associarvi alla `@Gilda del Drago`(,l'unica setta che adora la mitica bestia.`n`n");
    output("`)Già molti guerrieri hanno fatto la loro scelta, vediamo chi sono i più meritevoli.");
}else if ($_GET['op']=="fabbro"){
    output("`!`b`c<font size='+1'>I Migliori Fabbri del Villaggio</font>`c`b`n`n",true);
    output("<table cellspacing=2 cellpadding=2 align='center'>",true);
    output("<tr bgcolor='#FF0000'><td></td><td align='center'>`&`bNome`b</td>
    <td align='center'>`b`&Rango`b</td>
    <td align='center'>`b`&Punti Carriera`b</td>
    <td align='center'>`b`&Fede`b</td></tr>",true);
    $sqlf = "SELECT name,carriera,punti_carriera,dio FROM accounts WHERE carriera>4 AND carriera<=8 AND superuser=0 ORDER BY punti_carriera DESC";
    $resultf = db_query($sqlf);
    if (db_num_rows($resultf) == 0) {
        output("<tr><td colspan=4 align='center'>`&`iNessuno ha intrapreso la carriera di `#Fabbro`i`0</td></tr>", true);
    }
    $countrowf = db_num_rows($resultf);
    for ($i=0; $i<$countrowf; $i++){
    //for ($i = 0 ;$i < db_num_rows($resultf) ; $i++){
        $rowf = db_fetch_assoc($resultf);
        if ($rowf['name'] == $session['user']['name']) {
            output("<tr bgcolor='#666666'>", true);
        } else {
            output("<tr class='" . ($i % 2?"trlight":"trdark") . "'>", true);
            }
        output("<td align='right'>".($i + 1).".</td>
        <td align='center'>`&`b".$rowf['name']."`b</td>
        <td align='center'>`b".$rango[$rowf[carriera]]."`b</td>
        <td align='center'>`&".$rowf['punti_carriera']."</td>",true);
        if ($rowf['dio'] == 0){
           output("<td align='center'>`%`bAgnostico`b</td>",true);
        }elseif ($rowf['dio'] == 1){
           output("<td align='center'>`^`bSgrios`b</td>",true);
        }elseif ($rowf['dio'] == 2){
           output("<td align='center'>`\$`bKarnak`b</td>",true);
        }else{
           output("<td align='center'>`@`bDrago Verde`b</td>",true);
        }
        output("</tr>",true);
    }
    output("</table>", true);
}else if ($_GET['op']=="mago"){
    output("`!`b`c<font size='+1'>I Maghi più Saggi del Villaggio</font>`c`b`n`n",true);
    output("<table cellspacing=2 cellpadding=2 align='center'>",true);
    output("<tr bgcolor='#FF0000'><td></td>
    <td align='center'>`&`bNome`b</td>
    <td align='center'>`b`&Rango`b</td>
    <td align='center'>`b`&Punti Carriera`b</td>
    <td align='center'>`b`&Fede`b</td></tr>",true);
    $sqlf = "SELECT name,carriera,punti_carriera,dio FROM accounts WHERE carriera>40 AND carriera<=45 AND superuser=0 ORDER BY punti_carriera DESC";
    $resultf = db_query($sqlf);
    if (db_num_rows($resultf) == 0) {
        output("<tr><td colspan=4 align='center'>`&`iNessuno ha intrapreso la carriera di `#Mago`i`0</td></tr>", true);
    }
    $countrowf = db_num_rows($resultf);
    for ($i=0; $i<$countrowf; $i++){
    //for ($i = 0 ;$i < db_num_rows($resultf) ; $i++){
        $rowf = db_fetch_assoc($resultf);
        if ($rowf['name'] == $session['user']['name']) {
            output("<tr bgcolor='#666666'>", true);
        } else {
            output("<tr class='" . ($i % 2?"trlight":"trdark") . "'>", true);
            }
        output("<td align='right'>".($i + 1).".</td>
        <td align='center'>`&`b".$rowf['name']."`b</td>
        <td align='center'>`b".$rango[$rowf[carriera]]."`b</td>
        <td align='center'>`&".$rowf['punti_carriera']."</td>",true);
        if ($rowf['dio'] == 0){
           output("<td align='center'>`%`bAgnostico`b</td>",true);
        }elseif ($rowf['dio'] == 1){
           output("<td align='center'>`^`bSgrios`b</td>",true);
        }elseif ($rowf['dio'] == 2){
           output("<td align='center'>`\$`bKarnak`b</td>",true);
        }else{
           output("<td align='center'>`@`bDrago Verde`b</td>",true);
        }
        output("</tr>",true);
    }
    output("</table>", true);
}else if ($_GET['op']=="fede"){
    output("`!`b`c<font size='+1'>I Fedeli più Devoti a `^Sgrios`!</font>`c`b`n`n",true);
    output("<table cellspacing=2 cellpadding=2 align='center'>",true);
    output("<tr bgcolor='#FF0000'><td></td><td align='center'>`&`bNome`b</td><td align='center'>`b`&Rango`b</td>
    <td align='center'>`b`&Punti Carriera`b</td></tr>",true);
    $sqla = "SELECT name,carriera,punti_carriera FROM accounts WHERE ((carriera>0 AND carriera<=4) OR carriera=9 OR carriera=17) AND superuser=0 ORDER BY punti_carriera DESC";
    $resulta = db_query($sqla);
    if (db_num_rows($resulta) == 0) {
        output("<tr><td colspan=4 align='center'>`&`iNon c'è nemmeno un fedele di `^Sgrios`i`0</td></tr>", true);
    }
    $countrowa = db_num_rows($resulta);
    for ($i=0; $i<$countrowa; $i++){
    //for ($i = 0 ;$i < db_num_rows($resulta) ; $i++){
        $rowa = db_fetch_assoc($resulta);
        if ($rowa['name'] == $session['user']['name']) {
            output("<tr bgcolor='#666666'>", true);
        } else {
            output("<tr class='" . ($i % 2?"trlight":"trdark") . "'>", true);
            }
        output("<td align='right'>".($i + 1).".</td><td align='center'>`&`b".$rowa['name']."`b</td><td align='center'>`b{$rango[$rowa[carriera]]}`b</td>
        <td align='center'>`@`b".$rowa['punti_carriera']."`b</td></tr>",true);
    }
    output("</table>", true);
}else if ($_GET['op']=="cattivi"){
    output("`!`b`c<font size='+1'>I seguaci di `\$Karnak`! più Malvagi`!</font>`c`b`n`n",true);
    output("<table cellspacing=2 cellpadding=2 align='center'>",true);
    output("<tr bgcolor='#FF0000'><td></td><td align='center'>`&`bNome`b</td><td align='center'>`b`&Rango`b</td>
    <td align='center'>`b`&Punti Carriera`b</td></tr>",true);
    $sqlm = "SELECT name,carriera,punti_carriera FROM accounts WHERE carriera > 9 AND carriera <= 16 AND superuser = 0 ORDER BY punti_carriera DESC";
    $resultm = db_query($sqlm);
    if (db_num_rows($resultm) == 0) {
        output("<tr><td colspan=4 align='center'>`&`iNon c'è nemmeno un seguace di `\$Karnak`i`0</td></tr>", true);
    }
    $countrowm = db_num_rows($resultm);
    for ($i=0; $i<$countrowm; $i++){
    //for ($i = 0 ;$i < db_num_rows($resultm) ; $i++){
        $rowm = db_fetch_assoc($resultm);
        if ($rowm[name] == $session[user][name]) {
            output("<tr bgcolor='#225522'>", true);
        } else {
            output("<tr class='" . ($i % 2?"trlight":"trdark") . "'>", true);
            }
        output("<td align='right'>".($i + 1).".</td><td align='center'>`&`b".$rowm['name']."`b</td><td align='center'>`b{$rango[$rowm[carriera]]}`b</td>
        <td align='center'>`@`b".$rowm['punti_carriera']."`b</td></tr>",true);
    }
    output("</table>", true);
}else if ($_GET['op']=="draghisti"){
    output("`!`b`c<font size='+1'>I seguaci del `@Drago Verde`! più fedeli`!</font>`c`b`n`n",true);
    output("<table cellspacing=2 cellpadding=2 align='center'>",true);
    output("<tr bgcolor='#FF0000'><td></td><td align='center'>`&`bNome`b</td><td align='center'>`b`&Rango`b</td>
    <td align='center'>`b`&Punti Carriera`b</td></tr>",true);
    $sqlm = "SELECT name,carriera,punti_carriera FROM accounts WHERE carriera > 49 AND carriera <= 56 AND superuser = 0 ORDER BY punti_carriera DESC";
    $resultm = db_query($sqlm);
    if (db_num_rows($resultm) == 0) {
        output("<tr><td colspan=4 align='center'>`&`iNon c'è nemmeno un fedele del `\$Drago Verde`i`0</td></tr>", true);
    }
    $countrowm = db_num_rows($resultm);
    for ($i=0; $i<$countrowm; $i++){
    //for ($i = 0 ;$i < db_num_rows($resultm) ; $i++){
        $rowm = db_fetch_assoc($resultm);
        if ($rowm[name] == $session[user][name]) {
            output("<tr bgcolor='#225522'>", true);
        } else {
            output("<tr class='" . ($i % 2?"trlight":"trdark") . "'>", true);
            }
        output("<td align='right'>".($i + 1).".</td><td align='center'>`&`b".$rowm['name']."`b</td><td align='center'>`b{$rango[$rowm[carriera]]}`b</td>
        <td align='center'>`@`b".$rowm['punti_carriera']."`b</td></tr>",true);
    }
    output("</table>", true);
}else if ($_GET['op']=="breakpoint"){
    output("`b`c<font size='+1'>`&I Break Point per le carriere religiose</font>`c`b`n`n",true);
    output("<table cellspacing=2 cellpadding=2 align='center'>",true);
    output("<tr bgcolor='#005500'><td align='center'><big>`&`bDK`b</td><td align='center'><big>`b`&Punti`b</td><td align='center'><big>`b`^Sgrios`b</td><td align='center'><big>`b`\$Karnak`b</td><td align='center'><big>`b`@Drago Verde`b</td></tr>",true);
    output("<tr bgcolor='#333333'><td align='right'><big>`&`b0`b</td><td align='right'><big>`b`&0`b</td><td align='center'><big>`b`^Seguace`b</td><td align='center'><big>`b`\$Invasato`b</td><td align='center'><big>`b`@Stalliere`b</td></tr>",true);
    output("<tr bgcolor='#444444'><td align='right'><big>`&`b6`b</td><td align='right'><big>`b`&4.000`b</td><td align='center'><big>`b`^Accolito`b</td><td align='center'><big>`b`\$Fanatico`b</td><td align='center'><big>`b`@Scudiero`b</td></tr>",true);
    output("<tr bgcolor='#666666'><td align='right'><big>`&`b11`b</td><td align='right'><big>`b`&10.000`b</td><td align='center'><big>`b`^Chierico`b</td><td align='center'><big>`b`\$Posseduto`b</td><td align='center'><big>`b`@Cavaliere`b</td></tr>",true);
    output("<tr bgcolor='#888888'><td align='right'><big>`&`b16`b</td><td align='right'><big>`b`&40.000`b</td><td align='center'><big>`b`^Sacerdote`b</td><td align='center'><big>`b`\$Maestro delle Tenebre`b</td><td align='center'><big>`b`@Mastro dei Draghi`b</td></tr>",true);
    output("<tr bgcolor='#999999'><td align='right'><big>`&`bReinc`b</td><td align='right'><big>`b`&100.000`b</td><td align='center'><big>`b`^Sommo Chierico`b</td><td align='center'><big>`b`\$Portatore di Morte`b</td><td align='center'><big>`b`@Cancelliere dei Draghi`b</td></tr>",true);
    output("<tr bgcolor='#AAAAAA'><td align='right'><big>`&`bReinc`b</td><td align='center'><big>`b`&Miglior Punteggio`b</td><td align='center'><big>`b`^Gran Sacerdote`b</td><td align='center'><big>`b`\$Falciatore di Anime`b</td><td align='center'><big>`b`@Dominatore dei Draghi`b</td></tr>",true);
    output("</table>", true);

    output("`n`b`c<font size='+1'>`&I Break Point per le altre carriere</font>`c`b`n`n",true);
    output("<table cellspacing=2 cellpadding=2 align='center'>",true);
    output("<tr bgcolor='#005500'><td align='center'><big>`&`bDK`b</td><td align='center'><big>`b`&Punti`b</td><td align='center'><big>`b`#Oberon`b</td><td align='center'><big>`b`VAmon`b</td></tr>",true);
    output("<tr bgcolor='#333333'><td align='right'><big>`&`b0`b</td><td align='right'><big>`b`&0`b</td><td align='center'><big>`b`#Garzone`b</td><td align='center'><big>`b`VIniziato`b</td></tr>",true);
    output("<tr bgcolor='#666666'><td align='right'><big>`&`b11`b</td><td align='right'><big>`b`&5.000`b</td><td align='center'><big>`b`#Apprendista`b</td><td align='center'><big>`b`VStregone`b</td></tr>",true);
    output("<tr bgcolor='#888888'><td align='right'><big>`&`b16`b</td><td align='right'><big>`b`&20.000`b</td><td align='center'><big>`b`#Fabbro`b</td><td align='center'><big>`b`vMago`b</td></tr>",true);
    output("<tr bgcolor='#999999'><td align='right'><big>`&`b16`b</td><td align='right'><big>`b`&Miglior Punteggio`b</td><td align='center'><big>`b`#Mastro Fabbro`b</td><td align='center'><big>`b`vArciMago`b</td></tr>",true);
    output("</table>", true);
}else if ($_GET['op']=="militare"){
    output("`!`b`c<font size='+1'>I Militari migliori di `@Rafflingate`!</font>`c`b`n`n",true);
    output("<table cellspacing=2 cellpadding=2 align='center'>",true);
    output("<tr bgcolor='#FF0000'><td></td><td align='center'>`&`bNome`b</td><td align='center'>`b`&Scuola`b</td>
    <td align='center'>`b`&Grado`b</td><td align='center'>`b`&Valore`b</td></tr>",true);
    $sqla = "SELECT a.name,a.acctid,c.acctid,c.scuola,c.grado,c.strategia,c.attacco,c.difesa
             FROM accounts a,caserma c
             WHERE a.superuser=0 AND a.acctid=c.acctid
             ORDER BY (strategia+attacco+difesa) DESC";
    $resulta = db_query($sqla);
    if (db_num_rows($resulta) == 0) {
        output("<tr><td colspan=4 align='center'>`&`iNon c'è nemmeno un militare a `@Rafflingate`i`0</td></tr>", true);
    }
    $countrowa = db_num_rows($resulta);
    for ($i=0; $i<$countrowa; $i++){
    //for ($i = 0 ;$i < db_num_rows($resulta) ; $i++){
        $rowa = db_fetch_assoc($resulta);
        if ($rowa['name'] == $session['user']['name']) {
            output("<tr bgcolor='#666666'>", true);
        } else {
            output("<tr class='" . ($i % 2?"trlight":"trdark") . "'>", true);
            }
        output("<td align='right'>".($i + 1).".</td><td align='center'>`&`b".$rowa['name']."`b</td>
        <td align='center'>`b".$rowa['scuola']."`b</td>
        <td align='center'>`b".$rowa['grado']."`b</td>
        <td align='center'>`b".($rowa['strategia']+$rowa['attacco']+$rowa['difesa'])."`b</td>",true);
    }
    output("</table>", true);
}

page_footer();
?>