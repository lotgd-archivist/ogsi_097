<?php
// New Hall of Fame features by anpera
require_once "common.php";
$month = array(
1=>"Gennaio",
2=>"Febbraio",
3=>"Marzo",
4=>"Aprile",
5=>"Maggio",
6=>"Giugno",
7=>"Luglio",
8=>"Agosto",
9=>"Settembre",
10=>"Ottobre",
11=>"Novembre",
12=>"Dicembre");
page_header("Sala della Gloria");
$session['user']['locazione'] = 140;
checkday();
if ($session['return']==""){
    $session['return']=$_GET['ret'];
}
if ($_GET['op'] == "paare") {
    output("In una stanza accanto alla Sala della Gloria trovi un elenco con eroi di tutt'altro tipo. Questi eroi affrontano insieme i pericoli del matrimonio!`n`n");
    addnav("Torna alla Sala della Gloria", "hof.php");
    $sql = "SELECT acctid,name,marriedto FROM accounts WHERE sex=0 AND charisma=4294967295 AND marriedto<>4294967295 ORDER BY acctid DESC";
    output("`c`b`&Coppie eroine di questo mondo`b`c`n");
    output("<table cellspacing=0 cellpadding=2 align='center'>
            <tr>
            <td><img src=\"images/female.gif\">`b Nome`b</td>
            <td></td>
            <td><img src=\"images/male.gif\">`b Nome`b</td>
            <td>`bData Matrimonio`b</td>
            </tr>", true);
    $result = db_query($sql) or die(db_error(LINK));
    if (db_num_rows($result) == 0) {
        output("<tr><td colspan=4 align='center'>`&`iIn questo paese non ci sono coppie`i`0</td></tr>", true);
    }
    $countrow = db_num_rows($result);
    for ($i=0; $i<$countrow; $i++){
    //for ($i = 0;$i < db_num_rows($result);$i++) {
        $row = db_fetch_assoc($result);
        $sql2 = "SELECT name FROM accounts WHERE acctid=" . $row['marriedto'] . "";
        $result2 = db_query($sql2) or die(db_error(LINK));
        $row2 = db_fetch_assoc($result2);
        output("<tr class='" . ($i % 2?"trlight":"trdark") . "'><td>`&".$row2['name']."`0</td><td>`) e `0</td><td>`&", true);
        output($row['name']."`0</td><td>`^",true);
        $sqlmatri = "SELECT data FROM matrimoni WHERE acctid1=".$row['marriedto']." OR acctid2=".$row['marriedto'];
        $resultmatri = db_query($sqlmatri) or die(db_error(LINK));
        if (db_num_rows($resultmatri) == 0){
            output("Data sconosciuta</td></tr>", true);
        }else{
            $rowmatri = db_fetch_assoc($resultmatri);
            setlocale(LC_TIME, 'it_IT');
            output(strftime("%d %B %Y",strtotime($rowmatri['data']))."</td></tr>", true);
        }
    }
    output("</table>", true);

}elseif ($_GET['op']=="punch"){
    $sql = "SELECT name,punch,race,sex FROM accounts WHERE locked = 0 AND superuser = 0 AND punch > 0 ORDER BY punch DESC, dragonkills DESC, level DESC, experience DESC LIMIT 20";
    $title = "I colpi più potenti di tutti i tempi";
    output("<table cellspacing=0 cellpadding=2 align='center'><tr><td>`b`6Pos.`b</td><td>`b`3Nome`b</td><td>`b`4Colpo`b</td><td>`bRazza`b</td><td>`b<img src=\"images/female.gif\">/<img src=\"images/male.gif\">`b</td></tr>", true);
    $result = db_query($sql) or die(db_error(LINK));
    if (db_num_rows($result) == 0) {
        output("<tr><td colspan=4 align='center'>`&`iNessun giocatore trovato`0`i</td></tr>", true);
    }
    $countrow = db_num_rows($result);
    for ($i=0; $i<$countrow; $i++){
    //for ($i = 0;$i < db_num_rows($result);$i++) {
        $row = db_fetch_assoc($result);
        if ($row['name'] == $session['user']['name']) {
            output("<tr bgcolor='#007700'>", true);
        } else {
            output("<tr class='" . ($i % 2?"trlight":"trdark") . "'>", true);
        }
        output("<td>`6" . ($i + 1) . ".</td><td>`7".$row['name']."</td><td align='right'>`^`b".$row['punch']."`b</td><td>", true);
        commutarazza($row['race']);
        output("</td><td align='center'>" . ($row['sex']?"<img src=\"images/female.gif\">":"<img src=\"images/male.gif\">") . "</td></tr>", true);
    }
    output("</table>", true);
    addnav("Torna alla Sala della Gloria", "hof.php");

} else if ($_GET['op'] == "oggetti") {
    $sql = "SELECT * FROM oggetti WHERE dove = 0 OR dove = 1 ORDER BY livello DESC,nome DESC";
    output("`c`b`&Oggetti conosciuti nel regno`b`c");
    output("<table cellspacing=0 cellpadding=2 align='center'><tr><td>&nbsp;</td><td>`bNome`b</td><td>`bLivello`b</td><td>`bProprietario`b</td></tr>",true);
    $result = db_query($sql) or die(db_error(LINK));
    if (db_num_rows($result)==0){
        output("<tr><td colspan=4 align='center'>`&Non ci sono oggetti nel regno`0</td></tr>",true);
    }
    $countrow = db_num_rows($result);
    for ($i=0; $i<$countrow; $i++){
    //for ($i=0;$i<db_num_rows($result);$i++){
        $row = db_fetch_assoc($result);
        if ($row['dove']==1) {
            $prop = "`%Mercante Brax";
            output("<tr class='".($i%2?"trlight":"trdark")."'><td>".($i+1).".</td><td>".$row['nome']."</td><td>".$row['livello']."</td><td>$prop</td></tr>",true);

        }else if ($row['dove']==0) {
            $pro = $row['id_oggetti'];
            $sqlp = "SELECT name FROM accounts WHERE oggetto = '$pro' OR zaino = '$pro'";
            $resultp = db_query($sqlp) or die(db_error(LINK));
            if (db_num_rows($resultp)==0){
                $sqle = "UPDATE oggetti SET dove='1' WHERE id_oggetti='".$row['id']."'";
                db_query($sqle);
            }else{
                $rowp = db_fetch_assoc($resultp);
                $prop = $rowp['name'];
                output("<tr class='".($i%2?"trlight":"trdark")."'><td>".($i+1).".</td><td>".$row['nome']."</td><td>".$row['livello']."</td><td>$prop</td></tr>",true);

            }
        }
    }
    output("</table>",true);
    output("`n`2Gli oggetti ancora da trovare quelli dispersi e quelli posseduti da altri mercanti o viaggiatori non compaiono in questo elenco.",true);
    addnav("Torna alla Sala della Gloria", "hof.php");

} else if ($_GET['op'] == "artefatti") {
    $sql = "SELECT * FROM oggetti WHERE dove = 30 OR dove = 31 ORDER BY livello DESC,nome DESC";
    output("`c`b`&Artefatti conosciuti nel regno`b`c");
    output("<table cellspacing=0 cellpadding=2 align='center'><tr><td>&nbsp;</td><td>`bNome`b</td><td>`bLivello`b</td><td>`bProprietario`b</td></tr>",true);
    $result = db_query($sql) or die(db_error(LINK));
    if (db_num_rows($result)==0){
        output("<tr><td colspan=4 align='center'>`&Non ci sono artefatti nel regno`0</td></tr>",true);
    }
    $countrow = db_num_rows($result);
    for ($i=0; $i<$countrow; $i++){
    //for ($i=0;$i<db_num_rows($result);$i++){
        $row = db_fetch_assoc($result);
        if ($row['dove']==31) {
            $prop = "`%sconosciuto";
            output("<tr class='".($i%2?"trlight":"trdark")."'><td>".($i+1).".</td><td>".$row['nome']."</td><td>".$row['livello']."</td><td>$prop</td></tr>",true);

        }else if ($row['dove']==30) {
            $pro = $row['id_oggetti'];
            $sqlp = "SELECT name FROM accounts WHERE oggetto = '$pro' OR zaino = '$pro'";
            $resultp = db_query($sqlp) or die(db_error(LINK));
            if (db_num_rows($resultp)==0){
                $sqle = "UPDATE oggetti SET dove='1' WHERE id_oggetti='".$row['id']."'";
                db_query($sqle);
            }else{
                $rowp = db_fetch_assoc($resultp);
                $prop = $rowp['name'];
                output("<tr class='".($i%2?"trlight":"trdark")."'><td>".($i+1).".</td><td>".$row['nome']."</td><td>".$row['livello']."</td><td>$prop</td></tr>",true);

            }
        }
    }
    output("</table>",true);
    addnav("Torna alla Sala della Gloria", "hof.php");

} else if ($_GET['op'] == "reichtum") {
    $sql = "SELECT name,goldinbank,gold FROM accounts WHERE locked = 0 AND superuser = 0 ORDER BY goldinbank+gold DESC";
    output("`c`b`^I più ricchi di questo villaggio`b`c`0`n");
    output("<table cellspacing=0 cellpadding=2 align='center'><tr><td>`bPos.`b</td><td>`bNome`b</td><td>`bPatrimonio stimato`b</td></tr>", true);
    $result = db_query($sql) or die(db_error(LINK));
    if (db_num_rows($result) == 0) {
        output("<tr><td colspan=4 align='center'>`&Nessun giocatore trovato`0</td></tr>", true);
    }
    $countrow = db_num_rows($result);
    for ($i=0; $i<$countrow && $i<20; $i++){
    //for ($i = 0;$i < db_num_rows($result) && $i < 20;$i++) {
        $row = db_fetch_assoc($result);
        $amt = $row['goldinbank'] + $row['gold'] + e_rand(0 - round($row['goldinbank'] * 0.05), round($row['goldinbank'] * 0.05));
        if ($row['name'] == $session['user']['name']) {
            output("<tr bgcolor='#007700'>", true);
        } else {
            output("<tr class='" . (($i) % 2?"trlight":"trdark") . "'>", true);
        }
        output("<td>" . ($i + 1) . ".</td><td>".$row['name']."</td><td align='right'>$amt " . ($i % 2?"`6":"`3") . "Pezzi d'Oro`0</td></tr>", true);
    }
    output("</table>`n(Patrimonio +/- 5%)", true);
    $sql = "SELECT name,gems FROM accounts WHERE locked = 0 AND superuser = 0 ORDER BY gems DESC";
    output("`n`n`n`c`b`#I più grandi raccoglitori di pietre preziose`b`c`0`n");
    output("<table cellspacing=0 cellpadding=2 align='center'><tr><td>`bPos.`b</td><td>`bNome`b</td><td>`bGemme Accumulate`b</td></tr>", true);
    $result = db_query($sql) or die(db_error(LINK));
    if (db_num_rows($result) == 0) {
        output("<tr><td colspan=4 align='center'>`&Nessun giocatore trovato`0</td></tr>", true);
    }
    $countrow = db_num_rows($result);
    for ($i=0; $i<$countrow && $i<20; $i++){
    //for ($i = 0;$i < db_num_rows($result) && $i < 20;$i++) {
        $row = db_fetch_assoc($result);
        if ($row['name'] == $session['user']['name']) {
            output("<tr bgcolor='#007700'>", true);
        } else {
            output("<tr class='" . ($i % 2?"trlight":"trdark") . "'>", true);
        }
        output("<td>" . ($i + 1) . ".</td><td>".$row['name']."</td><td align='right'>".$row['gems']." " . ($i % 2?"`3":"`6") . "Gemme`0</td></tr>", true);
    }
    output("</table>", true);
    addnav("Torna alla Sala della Gloria", "hof.php");
} else if ($_GET['op'] == "armut") {
    $sql = "SELECT name,goldinbank,gold FROM accounts WHERE locked = 0 AND (gold > 0 OR goldinbank > 0) AND superuser = 0 ORDER BY goldinbank+gold ASC";
    output("`c`b`^I più poveri di questo villaggio`b`c`0`n");
    output("<table cellspacing=0 cellpadding=2 align='center'><tr><td>`bPos.`b</td><td>`bNome`b</td><td>`bPatrimonio stimato`b</td></tr>", true);
    $result = db_query($sql) or die(db_error(LINK));
    if (db_num_rows($result) == 0) {
        output("<tr><td colspan=4 align='center'>`&Nessun giocatore trovato`0</td></tr>", true);
    }
    $countrow = db_num_rows($result);
    for ($i=0; $i<$countrow && $i<21; $i++){
    //for ($i = 0;$i < db_num_rows($result) && $i < 21;$i++) {
        $tma = $amt;
        $row = db_fetch_assoc($result);
        $amt = $row['goldinbank'] + $row['gold'] + e_rand(0 - round($row['goldinbank'] * 0.05), round($row['goldinbank'] * 0.05));
        if ($amt < 0) $amt = "`\$" . $amt;
        if ($amt == $tma && $tma == "0") $i--;
        if ($row['name'] == $session['user']['name']) {
            output("<tr bgcolor='#007700'>", true);
        } else {
            output("<tr class='" . ($i % 2?"trlight":"trdark") . "'>", true);
        }
        output("<td>" . ($i + 1) . ".</td><td>".$row['name']."</td><td align='right'>$amt Gold`0</td></tr>", true);

    }
    output("</table>`n(Patrimonio +/- 5%)`nNon sono considerati i nullatenenti", true);
    addnav("Torna alla Sala della Gloria", "hof.php");
} else if ($_GET['op'] == "evil") {
    $sql = "SELECT name,sex,race,evil FROM accounts WHERE locked = 0 AND superuser = 0 ORDER BY evil DESC LIMIT 30";
    output("`c`b`%I più cattivi di questo mondo`b`c`0`n");
    output("<table cellspacing=0 cellpadding=2 align='center'><tr><td>`bPos.`b</td><td>`bNome`b</td><td>`b<img src=\"images/female.gif\">/<img src=\"images/male.gif\">`b</td><td>`bCattiveria`b</td><td>`bRazza`b</td></tr>", true);
    $result = db_query($sql) or die(db_error(LINK));
    if (db_num_rows($result) == 0) {
        output("<tr><td colspan=4 align='center'>`&Nessun giocatore trovato`0</td></tr>", true);
    }
    $countrow = db_num_rows($result);
    for ($i=0; $i<$countrow; $i++){
    //for ($i = 0;$i < db_num_rows($result);$i++) {
        $row = db_fetch_assoc($result);
        if ($row['name'] == $session['user']['name']) {
            output("<tr bgcolor='#007700'>", true);
        } else {
            output("<tr class='" . ($i % 2?"trlight":"trdark") . "'>", true);
        }
        output("<td>" . ($i + 1) . ".</td><td>".$row['name']."</td><td align='center'>" . ($row['sex']?"<img src=\"images/female.gif\">":"<img src=\"images/male.gif\">") . "</td><td align='right'>".$row['evil']."</td><td>", true);
        commutarazza($row['race']);
        output("</td></tr>", true);

    }
    output("</table>", true);
    addnav("Torna alla Sala della Gloria", "hof.php");
}else if ($_GET['op'] == "nice") {
    $sql = "SELECT name,sex,race FROM accounts WHERE locked = 0 AND superuser = 0 ORDER BY charm DESC";
    output("`c`b`%I più belli di questo mondo`b`c`0`n");
    output("<table cellspacing=0 cellpadding=2 align='center'><tr><td>`bPos.`b</td><td>`bNome`b</td><td>`b<img src=\"images/female.gif\">/<img src=\"images/male.gif\">`b</td><td>`bRazza`b</td></tr>", true);
    $result = db_query($sql) or die(db_error(LINK));
    if (db_num_rows($result) == 0) {
        output("<tr><td colspan=4 align='center'>`&Nessun giocatore trovato`0</td></tr>", true);
    }
    $countrow = db_num_rows($result);
    for ($i=0; $i<$countrow && $i<20; $i++){
    //for ($i = 0;$i < db_num_rows($result) && $i < 20;$i++) {
        $row = db_fetch_assoc($result);
        if ($row['name'] == $session['user']['name']) {
            output("<tr bgcolor='#007700'>", true);
        } else {
            output("<tr class='" . ('($i' % 2?"trlight":"trdark") . "'>", true);
        }
        output("<td>" . ($i + 1) . ".</td><td>".$row['name']."</td><td align='center'>" . ($row['sex']?"<img src=\"images/female.gif\">":"<img src=\"images/male.gif\">") . "</td><td>", true);
        commutarazza($row['race']);
        output("</td></tr>", true);
    }
    output("</table>", true);
    addnav("Torna alla Sala della Gloria", "hof.php");
} else if ($_GET['op'] == "cavalcare") {
    $sql = "SELECT name,level,race FROM accounts WHERE locked = 0 AND superuser = 0 AND cavalcare_drago > 0 ORDER BY cavalcare_drago DESC";
    output("`c`b`\$<big>I migliori cavalieri di draghi di questo mondo</big>`b`c`0`n",true);
    output("<table cellspacing=0 cellpadding=2 align='center'><tr><td>`bPos.`b</td><td>`bNome`b</td><td>`bRazza`b</td><td>`bLivello`b</td></tr>", true);
    $result = db_query($sql) or die(db_error(LINK));
    if (db_num_rows($result) == 0) {
        output("<tr><td colspan=4 align='center'>`&Nessun giocatore trovato`0</td></tr>", true);
    }
    $countrow = db_num_rows($result);
    for ($i=0; $i<$countrow && $i<20; $i++){
    //for ($i = 0;$i < db_num_rows($result) && $i < 20;$i++) {
        $row = db_fetch_assoc($result);
        if ($row['name'] == $session['user']['name']) {
            output("<tr bgcolor='#007700'>", true);
        } else {
            output("<tr class='" . ($i % 2?"trlight":"trdark") . "'>", true);
        }
        output("<td>" . ($i + 1) . ".</td><td>".$row['name']."</td><td>", true);
        commutarazza($row['race']);
        output("</td><td>".$row['level']."</td></tr>", true);
    }
    output("</table>", true);
    addnav("Torna alla Sala della Gloria", "hof.php");
}else if ($_GET['op'] == "draghi") {
    $sql = "SELECT d.*,a.name,a.level,a.race
            FROM draghi d, accounts a
            WHERE d.user_id = a.acctid AND a.superuser = 0
            ORDER BY d.valore DESC
            LIMIT 50";
    $result = db_query($sql) or die(db_error(LINK));

    output("`c`b`\$<big>I migliori draghi di questo mondo</big>`b`c`0`n",true);
    output("<table cellspacing=0 cellpadding=2 align='center'><tr><td>`bPos.`b</td><td>`bNome drago`b</td><td>`bRazza`b</td><td>`bEtà`b</td><td>`bSoffio`b</td><td>`bProprietario`b</td><td>`bRazza`b</td><td>`bLivello`b</td></tr>", true);

    if (db_num_rows($result) == 0) {
        output("<tr><td colspan=8 align='center'>`&Nessun giocatore trovato`0</td></tr>", true);
    }
    $countrow = db_num_rows($result);
    for ($i=0; $i<$countrow; $i++){
    //for ($i = 0;$i < db_num_rows($result);$i++) {
        $row = db_fetch_assoc($result);
            if ($row['name'] == $session['user']['name']) {
                output("<tr bgcolor='#007700'>", true);
            } else {
                output("<tr class='" . (($i + 1) % 2?"trlight":"trdark") . "'>", true);
            }

            output("<td>" . ($i+1) . ".</td><td>".$row['nome_drago']."", true);
            output("</td><td>".$row['tipo_drago']."", true);
            output("</td><td>".$row['eta_drago']."", true);
            output("</td><td>".$row['tipo_soffio']."", true);
            output("</td><td>".$row['name']."</td><td>", true);
            commutarazza($row['race']);
            output("</td><td>".$row['level']."</td></tr>", true);
    }
    output("</table>", true);
    addnav("Torna alla Sala della Gloria", "hof.php");
}else if ($_GET['op'] == "stark") {
    $sql = "SELECT name,level,race FROM accounts WHERE locked = 0 AND superuser = 0 ORDER BY maxhitpoints DESC";
    output("`c`b`\$I più forti di questo mondo`b`c`0`n");
    output("<table cellspacing=0 cellpadding=2 align='center'><tr><td>`bPos.`b</td><td>`bNome`b</td><td>`bRazza`b</td><td>`bLivello`b</td></tr>", true);
    $result = db_query($sql) or die(db_error(LINK));
    if (db_num_rows($result) == 0) {
        output("<tr><td colspan=4 align='center'>`&Nessun giocatore trovato`0</td></tr>", true);
    }
    $countrow = db_num_rows($result);
    for ($i=0; $i<$countrow && $i<20; $i++){
    //for ($i = 0;$i < db_num_rows($result) && $i < 20;$i++) {
        $row = db_fetch_assoc($result);
        if ($row['name'] == $session['user']['name']) {
            output("<tr bgcolor='#007700'>", true);
        } else {
            output("<tr class='" . ($i % 2?"trlight":"trdark") . "'>", true);
        }
        output("<td>" . ($i + 1) . ".</td><td>".$row['name']."</td><td>", true);
        commutarazza($row['race']);
        output("</td><td>".$row['level']."</td></tr>", true);
    }
    output("</table>", true);
    addnav("Torna alla Sala della Gloria", "hof.php");
} else if ($_GET['op'] == "tot") {
    $sql = "SELECT name,level FROM accounts WHERE locked = 0 AND superuser = 0 ORDER BY resurrections DESC";
    output("`c`b`)I più goffi di questo mondo`b`c`0`n");
    output("<table cellspacing=0 cellpadding=2 align='center'><tr><td>`bPos.`b</td><td>`bNome`b</td><td>`bLivello`b</td></tr>", true);
    $result = db_query($sql) or die(db_error(LINK));
    if (db_num_rows($result) == 0) {
        output("<tr><td colspan=4 align='center'>`&Nessun giocatore trovato`0</td></tr>", true);
    }
    $countrow = db_num_rows($result);
    for ($i=0; $i<$countrow && $i<20; $i++){
    //for ($i = 0;$i < db_num_rows($result) && $i < 20;$i++) {
        $row = db_fetch_assoc($result);
        if ($row['name'] == $session['user']['name']) {
            output("<tr bgcolor='#007700'>", true);
        } else {
            output("<tr class='" . ($i % 2?"trlight":"trdark") . "'>", true);
        }
        output("<td>" . ($i + 1) . ".</td><td>".$row['name']."</td><td>".$row['level']."</td></tr>", true);
    }
    output("</table>", true);
    addnav("Torna alla Sala della Gloria", "hof.php");
}else if ($_GET['op'] == "church") {
    output("`c`b`&La guerra delle Sette`b`c`n");
    $minortry = array();
    $minortry = unserialize(getsetting("minortry",""));
    if ($minortry['name'] != ""){
        output("`6`c<big>La setta campione del concorso `&Indovina la Frase`6, con il minor numero di tentativi di sempre, è `#".$minortry['name']." `6con `#".$minortry['try']." `6tentativi.`n`n`c</big>",true);
    }
    $sql="SELECT dio, COUNT(*) AS player FROM accounts WHERE dio > 0 AND superuser = 0 AND (dragonkills >= ".getsetting("dk_sette",2)." OR reincarna > 0) GROUP BY dio ORDER BY dio ASC";
    $result = db_query($sql);
    for ($i=0;$i<db_num_rows($result);$i++){
        $row = db_fetch_assoc($result);
        if ($row['dio'] == 1) {
            $partecipantibuoni = $row['player'];
        }elseif ($row['dio'] == 2) {
            $partecipanticattivi = $row['player'];
        }elseif ($row['dio'] == 3) {
            $partecipantidrago = $row['player'];
        }
    }
    $puntibuoni = getsetting("puntisgrios",0);
    $punticattivi = getsetting("puntikarnak",0);
    $puntidrago = getsetting("puntidrago",0);
    if ($partecipantibuoni != 0) {
        $buoni = round(($puntibuoni/$partecipantibuoni),2);
    } else {
        $puntibuoni=0;
        $buoni=0;
    }
    if ($partecipanticattivi != 0) {
        $cattivi = round(($punticattivi/$partecipanticattivi),2);
    } else {
        $punticattivi=0;
        $cattivi=0;
    }
    if ($partecipantidrago != 0) {
        $drago = round(($puntidrago/$partecipantidrago),2);
    } else {
        $puntidrago=0;
        $drago=0;
    }
    output("<table align='center'>", true);
    output("<tr><td></td><td bgcolor='#222255'>`b`^<big><big><big>Fedeli di Sgrios</big></big></big>`b</td>
    <td bgcolor='#225555'>`b`\$<big><big><big>Seguaci di Karnak</big></big></big>`b</td>
    <td bgcolor='#662266'>`b`@<big><big>Adepti del Drago</big></big>`b</td></tr>",true);
    output("<tr><td align='center' bgcolor='#33CC33'><big><big><big>`!Punti Totali</big></big></big></td>
    <td align='center' bgcolor='#222288'>`b`6<big><big><big>$puntibuoni</big></big></big>`b</td>
    <td align='center' bgcolor='#228888'>`b`4<big><big><big>$punticattivi</big></big></big>`b</td>
    <td align='center' bgcolor='#882288'>`b`2<big><big><big>$puntidrago</big></big></big>`b</td></tr>",true);
    output("<tr><td align='center' bgcolor='#33CC33'><big><big><big>`!N.Player</big></big></big></td>
    <td align='center' bgcolor='#222288'>`b`6<big><big><big>$partecipantibuoni</big></big></big>`b</td>
    <td align='center' bgcolor='#228888'>`b`4<big><big><big>$partecipanticattivi</big></big></big>`b</td>
    <td align='center' bgcolor='#882288'>`b`2<big><big><big>$partecipantidrago</big></big></big>`b</td></tr>",true);
    output("<tr><td align='center' bgcolor='#33CC33'><big><big><big>`!Punti/Player</big></big></big></td>
    <td align='center' bgcolor='#222288'>`b`6<big><big><big>$buoni</big></big></big>`b</td>
    <td align='center' bgcolor='#228888'>`b`4<big><big><big>$cattivi</big></big></big>`b</td>
    <td align='center' bgcolor='#882288'>`b`2<big><big><big>$drago</big></big></big>`b</td></tr>",true);
    output("</table>`n`n", true);

    $sqlliid = "SELECT LAST_INSERT_ID(commentid) AS ultimo FROM commenti ORDER BY commentid DESC";
    $resultliid = db_query($sqlliid);
    $rowliid = db_fetch_assoc($resultliid);
    $ultimo = $rowliid['ultimo'];
    $sqldel = "delete FROM commenti WHERE commentid < ('$ultimo'-19)";
    $resultdel = db_query($sqldel);

    $sqlcom = "SELECT author,comment FROM commenti WHERE section = 'Scontri Sette' ORDER BY postdate ASC";
    $resultcom = db_query($sqlcom) or die(db_error(LINK));
    output("<table align='center'><tr><td bgcolor='#556611' align='center'>`b`(<big><big>I risultati degli ultimi scontri tra `^Fedeli`(,`\$Seguaci`( e `@Adepti</big></big></td></tr>", true);
    $countrow = db_num_rows($resultcom);
    for ($i=0; $i<$countrow; $i++){
    //for ($i = 0; $i < db_num_rows($resultcom); $i++) {
        output("<tr class='" . (($i+$k) % 2?"trlight":"trdark") . "'>", true);
        $rowcom = db_fetch_assoc($resultcom);
        $sqlau = "SELECT name FROM accounts WHERE acctid = ".$rowcom['author']." LIMIT 1";
        $resultau = db_query($sqlau);
        $rowau = db_fetch_assoc($resultau);
        $nome = $rowau['name'];
        $comment = stripslashes($rowcom['comment']);
        $comment = substr($comment,3);
        $comment = "`^".($i+1)." `@".$nome." ".$comment;
        output("<td>`b$comment`b</td></tr>",true);
    }
    output("</table>",true);
    addnav("Torna alla Sala della Gloria", "hof.php");
    if(getsetting("puntisgriosfinemese",0)!=0 OR getsetting("puntikarnakfinemese",0)!=0 OR getsetting("puntidragofinemese",0)!=0) addnav("Classifica ultima sfida", "hof.php?op=churchold");
}else if ($_GET['op'] == "churchold") {
    output("`c`b`&La guerra delle Sette`b`c`n");
    $minortry = array();
    $minortry = unserialize(getsetting("minortry",""));
    if ($minortry['name'] != ""){
        output("`6`c<big>La setta campione del concorso `&Indovina la Frase`6, con il minor numero di tentativi di sempre, è `#".$minortry['name']." `6con `#".$minortry['try']." `6tentativi.`n`n`c</big>",true);
    }
    $sql="SELECT dio, COUNT(*) AS player FROM accounts WHERE dio > 0 AND superuser = 0 AND (dragonkills >= ".getsetting("dk_sette",2)." OR reincarna > 0) GROUP BY dio ORDER BY dio ASC";
    $result = db_query($sql);
    $countrow = db_num_rows($result);
    for ($i=0; $i<$countrow; $i++){
    //for ($i=0;$i<db_num_rows($result);$i++){
        $row = db_fetch_assoc($result);
        if ($row['dio'] == 1) {
            $partecipantibuoni = $row['player'];
        }elseif ($row['dio'] == 2) {
            $partecipanticattivi = $row['player'];
        }elseif ($row['dio'] == 3) {
            $partecipantidrago = $row['player'];
        }
    }
    $puntibuoni = getsetting("puntisgriosfinemese",0);
    $punticattivi = getsetting("puntikarnakfinemese",0);
    $puntidrago = getsetting("puntidragofinemese",0);
    if ($partecipantibuoni != 0) {
        $buoni = round(($puntibuoni/$partecipantibuoni),2);
    } else {
        $puntibuoni=0;
        $buoni=0;
    }
    if ($partecipanticattivi != 0) {
        $cattivi = round(($punticattivi/$partecipanticattivi),2);
    } else {
        $punticattivi=0;
        $cattivi=0;
    }
    if ($partecipantidrago != 0) {
        $drago = round(($puntidrago/$partecipantidrago),2);
    } else {
        $puntidrago=0;
        $drago=0;
    }
    output("<table align='center'>", true);
    output("<tr><td></td><td bgcolor='#222255'>`b`^<big><big><big>Fedeli di Sgrios</big></big></big>`b</td>
    <td bgcolor='#225555'>`b`\$<big><big><big>Seguaci di Karnak</big></big></big>`b</td>
    <td bgcolor='#662266'>`b`@<big><big>Adepti del Drago</big></big>`b</td></tr>",true);
    output("<tr><td align='center' bgcolor='#33CC33'><big><big><big>`!Punti Totali</big></big></big></td>
    <td align='center' bgcolor='#222288'>`b`6<big><big><big>$puntibuoni</big></big></big>`b</td>
    <td align='center' bgcolor='#228888'>`b`4<big><big><big>$punticattivi</big></big></big>`b</td>
    <td align='center' bgcolor='#882288'>`b`2<big><big><big>$puntidrago</big></big></big>`b</td></tr>",true);
    output("<tr><td align='center' bgcolor='#33CC33'><big><big><big>`!N.Player</big></big></big></td>
    <td align='center' bgcolor='#222288'>`b`6<big><big><big>$partecipantibuoni</big></big></big>`b</td>
    <td align='center' bgcolor='#228888'>`b`4<big><big><big>$partecipanticattivi</big></big></big>`b</td>
    <td align='center' bgcolor='#882288'>`b`2<big><big><big>$partecipantidrago</big></big></big>`b</td></tr>",true);
    output("<tr><td align='center' bgcolor='#33CC33'><big><big><big>`!Punti/Player</big></big></big></td>
    <td align='center' bgcolor='#222288'>`b`6<big><big><big>$buoni</big></big></big>`b</td>
    <td align='center' bgcolor='#228888'>`b`4<big><big><big>$cattivi</big></big></big>`b</td>
    <td align='center' bgcolor='#882288'>`b`2<big><big><big>$drago</big></big></big>`b</td></tr>",true);
    output("</table>`n`n", true);
    addnav("Torna alla Sala della Gloria", "hof.php");
    addnav("Classifica Mese Corrente", "hof.php?op=church");
} elseif ($_GET['op'] == "famosi")  {
    $sql = "SELECT acctid,fama_mod,fama3mesi,name,dragonkills,level,reincarna,dio,sex FROM accounts WHERE (dragonkills > 0 OR reincarna >0) AND superuser = 0 ORDER BY fama3mesi DESC,reincarna DESC,dragonkills DESC,level DESC,experience DESC LIMIT 100";
    output("`c`b`&Gli eroi più famosi di questo mondo`b`c`n");
    output("`c`b`vCLASSIFICA SPERIMENTALE`b`c`n");
    output("<table cellspacing=0 cellpadding=2 align='center'><tr><td>`bPos.`b</td><td>`bNome`b</td><td>`b`@DK`b</td><td>`b`&Livello`b</td><td>&nbsp;</td><td>&nbsp;</td><td>`b`5Reinc.`b</td><td>`8`bS`(e`8s`(s`8o`b</td><td>`#`bFede`b</td><td>`6`bFama`b</td>", true);
    if ($session['user']['superuser'] > 3){
        output("<td>`6`bFama Mod.`b</td></tr>", true);
    }else{
        output("</tr>", true);
    }
    $result = db_query($sql) or die(db_error(LINK));
    if (db_num_rows($result) == 0) {
        output("<tr><td colspan=4 align='center'>`&In questo paese non ci sono eroi`0</td></tr>", true);
    }
    $countrow = db_num_rows($result);
    for ($i=0; $i<$countrow; $i++){
    //for ($i = 0;$i < db_num_rows($result);$i++) {
        $row = db_fetch_assoc($result);
        $ultimo_reset = getsetting("fama3mesi_reset",'13');
        $sql = "SELECT ultimo_reset FROM fama3mesi WHERE acctid = '".$row['acctid']."'";
        $resultr = db_query($sql) or die(db_error(LINK));
        $rowr = db_fetch_assoc($resultr);
        if($ultimo_reset==$rowr['ultimo_reset']){
            if ($row['dio'] == 0) {
                $dio='`0Ateo';
            }
            if ($row['dio'] == 1) {
                $dio='`6Sgrios';
            }
            if ($row['dio'] == 2) {
                $dio='`$Karnak';
            }
            if ($row['dio'] == 3) {
                $dio='`@Drago Verde';
            }
            if ($row['name'] == $session['user']['name']) {
                output("<tr bgcolor='#007700'>", true);
            } else {
                output("<tr class='" . ($pos % 2?"trlight":"trdark") . "'>", true);
            }
            output("<td>" . ($pos + 1) . ".</td><td>".$row['name']."</td><td align=right>".$row['dragonkills']."</td><td align=right>".$row['level']."</td><td>&nbsp;</td><td>&nbsp;</td><td align=center>",true);
            if($row['reincarna']){
                commutadk($row['reincarna']);
                output($row['reincarna']);
            }else output("`\$0");
            output("</td><td align='center'>".($row['sex']?"<img src=\"images/female.gif\">":"<img src=\"images/male.gif\">"),true);
            output("</td><td>$dio", true);
            output("</td><td align='right'>`#".$row['fama3mesi']."</td>", true);
            if ($session['user']['superuser'] > 3){
                output("<td align='right'>`@".$row['fama_mod']."</td></tr>", true);
            }else{
                output("</tr>", true);
            }
            $pos+=1;
        }
    }
    output("</table>", true);
    addnav("Torna alla Sala della Gloria", "hof.php");
}else if ($_GET['op'] == "fattorie") {
    $sql = "SELECT name,sex,race,slaves,land,farms FROM accounts WHERE locked = 0 AND manager > 0 AND superuser = 0 ORDER BY slaves DESC";
    output("`c`b`V<big>Gli agricoltori più ricchi di questo mondo</big>`b`c`0`n",true);
    output("<table cellspacing=0 cellpadding=2 align='center'><tr><td>`b`#Pos.`0`b</td><td>`b`@Nome`0`b</td><td>`b<img src=\"images/female.gif\">/<img src=\"images/male.gif\">`b</td><td>`b`^Razza`0`b</td><td>`b`(Acri di Terra`0`b</td><td>`b`@Fattorie`0`b</td><td>`b`\$Schiavi`0`b</td></tr>", true);
    $result = db_query($sql) or die(db_error(LINK));
    if (db_num_rows($result) == 0) {
        output("<tr><td colspan=4 align='center'>`&`iNessun giocatore trovato`i`0</td></tr>", true);
    }
    $countrow = db_num_rows($result);
    for ($i=0; $i<$countrow && $i<20; $i++){
    //for ($i = 0;$i < db_num_rows($result) && $i < 20;$i++) {
        $row = db_fetch_assoc($result);
        if ($row['name'] == $session['user']['name']) {
            output("<tr bgcolor='#444444'>", true);
        } else {
            output("<tr class='" . ('($i' % 2?"trlight":"trdark") . "'>", true);
        }
        output("<td>" . ($i + 1) . ".</td><td>`%`b".$row['name']."`b</td><td align='center'>" . ($row['sex']?"<img src=\"images/female.gif\">":"<img src=\"images/male.gif\">") . "</td><td>", true);
        commutarazza($row['race']);
        output("</td><td align='center'>`(".$row['land']."</td><td align='center'>`@".$row['farms']."</td><td align='center'>`\$".$row['slaves'],true);
        output("</td></tr>", true);
    }
    output("</table>", true);
    addnav("Torna alla Sala della Gloria", "hof.php");
} elseif ($_GET['op'] == "minatori")  {
    $sql = "SELECT minatore,name,dragonkills,level,reincarna,dio,sex FROM accounts WHERE (dragonkills > 0 OR reincarna >0) AND superuser = 0 AND minatore >2 ORDER BY minatore DESC,reincarna DESC,dragonkills DESC,level DESC,experience DESC limit 0,25";
    output("`c`b`&I minatori più bravi di questo mondo`b`c`n");
    output("<table cellspacing=0 cellpadding=2 align='center'><tr><td>`bPos.`b</td><td>`bNome`b</td><td>`b`@DK`b</td><td>`b`&Livello`b</td><td>&nbsp;</td><td>&nbsp;</td><td>`b`5Reinc.`b</td><td>`8`bS`(e`8s`(s`8o`b</td><td>`#`bFede`b</td>", true);
    output("</tr>", true);
    $result = db_query($sql) or die(db_error(LINK));
    if (db_num_rows($result) == 0) {
        output("<tr><td colspan=4 align='center'>`&In questo paese non ci sono minatori`0</td></tr>", true);
    }
    $countrow = db_num_rows($result);
    for ($i=0; $i<$countrow; $i++){
    //for ($i = 0;$i < db_num_rows($result);$i++) {
        $row = db_fetch_assoc($result);
        if ($row['dio'] == 0) {
            $dio='`0Ateo';
        }
        if ($row['dio'] == 1) {
            $dio='`6Sgrios';
        }
        if ($row['dio'] == 2) {
            $dio='`$Karnak';
        }
        if ($row['dio'] == 3) {
            $dio='`@Drago Verde';
        }
        if ($row['name'] == $session['user']['name']) {
            output("<tr bgcolor='#007700'>", true);
        } else {
            output("<tr class='" . ($i % 2?"trlight":"trdark") . "'>", true);
        }
        output("<td>" . ($i + 1) . ".</td><td>".$row['name']."</td><td align=right>".$row['dragonkills']."</td><td align=right>".$row['level']."</td><td>&nbsp;</td><td>&nbsp;</td><td align=center>",true);
        if($row['reincarna']){
            commutadk($row['reincarna']);
            output($row['reincarna']);
        }else output("`\$0");
        output("</td><td align='center'>".($row['sex']?"<img src=\"images/female.gif\">":"<img src=\"images/male.gif\">"),true);
        output("</td><td>$dio", true);
        output("</td>", true);
        output("</tr>", true);
    }
    output("</table>", true);
    addnav("Torna alla Sala della Gloria", "hof.php");
} elseif ($_GET['op'] == "boscaioli")  {
    $sql = "SELECT boscaiolo,name,dragonkills,level,reincarna,dio,sex FROM accounts WHERE (dragonkills > 0 OR reincarna >0) AND superuser = 0 AND boscaiolo >2 ORDER BY boscaiolo DESC,reincarna DESC,dragonkills DESC,level DESC,experience DESC limit 0,25";
    output("`c`b`&I boscaioli più bravi di questo mondo`b`c`n");
    output("<table cellspacing=0 cellpadding=2 align='center'><tr><td>`bPos.`b</td><td>`bNome`b</td><td>`b`@DK`b</td><td>`b`&Livello`b</td><td>&nbsp;</td><td>&nbsp;</td><td>`b`5Reinc.`b</td><td>`8`bS`(e`8s`(s`8o`b</td><td>`#`bFede`b</td>", true);
    output("</tr>", true);
    $result = db_query($sql) or die(db_error(LINK));
    if (db_num_rows($result) == 0) {
        output("<tr><td colspan=4 align='center'>`&In questo paese non ci sono boscaioli`0</td></tr>", true);
    }
    $countrow = db_num_rows($result);
    for ($i=0; $i<$countrow; $i++){
    //for ($i = 0;$i < db_num_rows($result);$i++) {
        $row = db_fetch_assoc($result);
        if ($row['dio'] == 0) {
            $dio='`0Ateo';
        }
        if ($row['dio'] == 1) {
            $dio='`6Sgrios';
        }
        if ($row['dio'] == 2) {
            $dio='`$Karnak';
        }
        if ($row['dio'] == 3) {
            $dio='`@Drago Verde';
        }
        if ($row['name'] == $session['user']['name']) {
            output("<tr bgcolor='#007700'>", true);
        } else {
            output("<tr class='" . ($i % 2?"trlight":"trdark") . "'>", true);
        }
        output("<td>" . ($i + 1) . ".</td><td>".$row['name']."</td><td align=right>".$row['dragonkills']."</td><td align=right>".$row['level']."</td><td>&nbsp;</td><td>&nbsp;</td><td align=center>",true);
        if($row['reincarna']){
            commutadk($row['reincarna']);
            output($row['reincarna']);
        }else output("`\$0");
        output("</td><td align='center'>".($row['sex']?"<img src=\"images/female.gif\">":"<img src=\"images/male.gif\">"),true);
        output("</td><td>$dio", true);
        output("</td>", true);
        output("</tr>", true);
    }
    output("</table>", true);
    addnav("Torna alla Sala della Gloria", "hof.php");
} elseif ($_GET['op'] == "birthday")  {
    addnav("Gennaio","hof.php?op=birthday&op1=01");
    addnav("Febbraio","hof.php?op=birthday&op1=02");
    addnav("Marzo","hof.php?op=birthday&op1=03");
    addnav("Aprile","hof.php?op=birthday&op1=04");
    addnav("Maggio","hof.php?op=birthday&op1=05");
    addnav("Giugno","hof.php?op=birthday&op1=06");
    addnav("Luglio","hof.php?op=birthday&op1=07");
    addnav("Agosto","hof.php?op=birthday&op1=08");
    addnav("Settembre","hof.php?op=birthday&op1=09");
    addnav("Ottobre","hof.php?op=birthday&op1=10");
    addnav("Novembre","hof.php?op=birthday&op1=11");
    addnav("Dicembre","hof.php?op=birthday&op1=12");
    addnav("Torna alla Sala della Gloria","hof.php");
    if ($_GET['op1'] == "")  {
        output("`(Qui puoi vedere i compleanni degli abitanti di `@Rafflingate`(, ordinandoli per mese.`n");
        output("Scegli il mese che ti interessa e vedrai i compleanni ordinati per data.`n`n");
    }else{
        //$dataodierna = date("m");
        $mese = $_GET['op1'];
        $sql = "SELECT name, acctid, substring(compleanno,1,4) AS year,
                substring(compleanno,6,2) AS month,
                substring(compleanno,9,2) AS day
                FROM accounts WHERE substring(compleanno,6,2) = '$mese' ORDER BY substring(compleanno,9,2) ASC";
        db_query($sql);
        $result = db_query($sql);
        $mese += 0;
        output("`c`b`\$<big>Compleanni di ".$month[$mese]."</big>`b`c`0`n",true);
        if (db_num_rows($result) == 0){
            output("`&`i`cNessun abitante compie gli anni in questo mese`i`c");
        }//else{
        output("<table cellspacing=2 cellpadding=2 align='center'><tr bgcolor='#FF0000'><td align='center'>`b`&Giorno`b</td><td align='center'>`b`&Nome`b</td><td align='center'>`b`&Anni Compiuti`b</td></tr>", true);
        $countrow = db_num_rows($result);
        for ($i=0; $i<$countrow; $i++){
        //for ($i=0;$i<db_num_rows($result);$i++){
            $row = db_fetch_assoc($result);
            $anni = date("Y") - $row['year'];
            if ($row['acctid'] == $session['user']['acctid']) {
                output("<tr bgcolor='#007700'>", true);
            } else {
                output("<tr class='" . ($i % 2?"trlight":"trdark") . "'>", true);
            }
            output("<td align='center'>`b`#".$row['day']."`b</td><td align='center'>`b".$row['name']."`b</td><td align='center'>`b`@$anni`b</td></tr>",true);
        }
        output("</table>", true);
        //}
        //output("</table>", true);
    }
} else if ($_GET['op'] == "tasse") {
    $sql = "SELECT t.*, a.name
            FROM tasse t, accounts a
            WHERE oro >49 AND t.acctid = a.acctid
            ORDER BY oro DESC
            LIMIT 50 ";
    output("`c`b`^I più indebitati con il municipio di questo villaggio`b`c`0`n");
    output("<table cellspacing=0 cellpadding=2 align='center'><tr><td>`bPos.`b</td><td>`bNome`b</td><td>`bTasse`b</td></tr>", true);
    $result = db_query($sql) or die(db_error(LINK));
    if (db_num_rows($result) == 0) {
        output("<tr><td colspan=4 align='center'>`&Nessun giocatore trovato`0</td></tr>", true);
    }
    $countrow = db_num_rows($result);
    for ($i=0; $i<$countrow; $i++){
    //for ($i = 0;$i < db_num_rows($result);$i++) {
        $row = db_fetch_assoc($result);
        if ($row['name'] == $session['user']['name']) {
            output("<tr bgcolor='#007700'>", true);
        } else {
            output("<tr class='" . ($i % 2?"trlight":"trdark") . "'>", true);
        }
        output("<td>" . ($i + 1) . ".</td><td>".$row['name']."</td><td align='right'>".$row['oro']." Oro`0</td></tr>", true);

    }
    output("</table>`n", true);
    addnav("Torna alla Sala della Gloria", "hof.php");


}elseif ($_GET['op']=="labi"){
    $sql = "SELECT name,labsolved,race,sex FROM accounts WHERE locked = 0 AND superuser = 0 AND labsolved > 0 ORDER BY labsolved DESC, dragonkills DESC, level DESC, experience DESC LIMIT 20";
    $title = "I migliori solutori di labirinti di Rafflingate";
    output("<table cellspacing=0 cellpadding=2 align='center'><tr><td>`b`6Pos.`b</td><td>`b`3Nome`b</td><td>`b`4N° Labirinti`b</td><td>`bRazza`b</td><td>`b<img src=\"images/female.gif\">/<img src=\"images/male.gif\">`b</td></tr>", true);
    $result = db_query($sql) or die(db_error(LINK));
    if (db_num_rows($result) == 0) {
        output("<tr><td colspan=4 align='center'>`&`iNessun giocatore trovato`0`i</td></tr>", true);
    }
    $countrow = db_num_rows($result);
    for ($i=0; $i<$countrow; $i++){
    //for ($i = 0;$i < db_num_rows($result);$i++) {
        $row = db_fetch_assoc($result);
        if ($row['name'] == $session['user']['name']) {
            output("<tr bgcolor='#007700'>", true);
        } else {
            output("<tr class='" . ($i % 2?"trlight":"trdark") . "'>", true);
        }
        output("<td>`6" . ($i + 1) . ".</td><td>`7".$row['name']."</td><td align='right'>`^`b".$row['labsolved']." Risolto`b</td><td>", true);
        commutarazza($row['race']);
        output("</td><td align='center'>" . ($row['sex']?"<img src=\"images/female.gif\">":"<img src=\"images/male.gif\">") . "</td></tr>", true);
    }
    output("</table>", true);
    addnav("Torna alla Sala della Gloria", "hof.php");

} elseif ($_GET['op'] == "pvpl")  {
    $razzza=array(1=>"`2Troll",
    2=>"`^Elfi",
    3=>"`&Umani",
    4=>"`#Nani",
    5=>"`3Druidi",
    6=>"`@Goblin",
    7=>"`%Orchi",
    8=>"`\$Vampiri",
    9=>"`5Lich",
    10=>"`&Fanciulla delle Nevi",
    11=>"`4Oni",
    12=>"`3Satiro",
    13=>"`#Gigante delle Tempeste",
    14=>"`\$Barbaro",
    15=>"`%Amazzone",
    16=>"`^Titano",
    17=>"`\$Demone",
    18=>"`(Centauro",
    19=>"`8Licantropo",
    20=>"`)Minotauro",
    21=>"`^Cantastorie",
    22=>"`@Eletto",
    0=>"Sconosciuto",
    50=>"Pecora Volante",
    60=>"Tester",
    80=>"Moderatore",
    100=>"`^Admin",
    255=>"`^Divinità"
    );
    //    $sql = "SELECT name,race,dragonkills,level,reincarna,dio,pvpkills FROM accounts WHERE pvpkills > 0 AND superuser = 0 ORDER BY pvpkills DESC, reincarna ASC,dragonkills ASC,level ASC,experience ASC LIMIT 50";
    $sql = "SELECT name,race,dragonkills,level,reincarna,dio,pvplost FROM accounts WHERE pvplost > 0 AND superuser = 0 ORDER BY pvplost DESC, reincarna ASC,dragonkills ASC,level ASC,experience ASC LIMIT 100";
    output("`c`b`&I guerrieri più `4Mozzarella`& di `#Rafflingate`b`c`n");
    output("<table cellspacing=0 cellpadding=2 align='center'><tr><td>`bPos.`b</td><td>`bNome`b</td><td>`b`@DK`b</td><td>`b`&Livello`b</td><td>&nbsp;</td><td>`b`5Reinc.`b</td><td>`#`bFede`b</td><td>`V`bRazza`b</td><td>`^`bPvp persi`b</td></tr>", true);
    $result = db_query($sql) or die(db_error(LINK));
    if (db_num_rows($result) == 0) {
        output("<tr><td colspan=4 align='center'>`&In questo paese non ci sono eroi`0</td></tr>", true);
    }
    $countrow = db_num_rows($result);
    for ($i=0; $i<$countrow; $i++){
    //for ($i = 0;$i < db_num_rows($result);$i++) {
        $row = db_fetch_assoc($result);
        if ($row['dio'] == 0) {
            $dio='`0Ateo';
        }
        if ($row['dio'] == 1) {
            $dio='`6Sgrios';
        }
        if ($row['dio'] == 2) {
            $dio='`$Karnak';
        }
        if ($row['dio'] == 3) {
            $dio='`@Drago Verde';
        }
        if ($row['name'] == $session['user']['name']) {
            output("<tr bgcolor='#007700'>", true);
        } else {
            output("<tr class='" . ($i % 2?"trlight":"trdark") . "'>", true);
        }
        output("<td>" . ($i + 1) . ".</td><td>".$row['name']."</td><td align=right>".$row['dragonkills']."</td><td align=center>".$row['level']."</td><td>&nbsp;</td><td align=center>",true);
        if($row['reincarna']){
            commutadk($row['reincarna']);
            output($row['reincarna']);
        }else output("`\$0");
        output("</td><td>$dio", true);
        $razza=$row[race];
        output("</td><td>$razzza[$razza]", true);
        output("</td><td align=center>`b`^".$row['pvplost']."`b</td></tr>", true);
    }
    output("</table>", true);
    addnav("Torna alla Sala della Gloria", "hof.php");





} elseif ($_GET['op'] == "pvp")  {
    $razzza=array(1=>"`2Troll",
    2=>"`^Elfi",
    3=>"`&Umani",
    4=>"`#Nani",
    5=>"`3Druidi",
    6=>"`@Goblin",
    7=>"`%Orchi",
    8=>"`\$Vampiri",
    9=>"`5Lich",
    10=>"`&Fanciulla delle Nevi",
    11=>"`4Oni",
    12=>"`3Satiro",
    13=>"`#Gigante delle Tempeste",
    14=>"`\$Barbaro",
    15=>"`%Amazzone",
    16=>"`^Titano",
    17=>"`\$Demone",
    18=>"`(Centauro",
    19=>"`8Licantropo",
    20=>"`)Minotauro",
    21=>"`^Cantastorie",
    22=>"`@Eletto",
    0=>"Sconosciuto",
    50=>"Pecora Volante",
    60=>"Tester",
    80=>"Moderatore",
    100=>"`^Admin",
    255=>"`^Divinità"
    );
    //    $sql = "SELECT name,race,dragonkills,level,reincarna,dio,pvpkills FROM accounts WHERE pvpkills > 0 AND superuser = 0 ORDER BY pvpkills DESC, reincarna ASC,dragonkills ASC,level ASC,experience ASC LIMIT 50";
    $sql = "SELECT name,race,dragonkills,level,reincarna,dio,pvpkills FROM accounts WHERE pvpkills > 0 AND superuser = 0 ORDER BY pvpkills DESC, reincarna ASC,dragonkills ASC,level ASC,experience ASC LIMIT 50";
    output("`c`b`&I killer più spietati`b`c`n");
    output("<table cellspacing=0 cellpadding=2 align='center'><tr><td>`bPos.`b</td><td>`bNome`b</td><td>`b`@DK`b</td><td>`b`&Livello`b</td><td>&nbsp;</td><td>`b`5Reinc.`b</td><td>`#`bFede`b</td><td>`V`bRazza`b</td><td>`^`bPvp vinti`b</td></tr>", true);
    $result = db_query($sql) or die(db_error(LINK));
    if (db_num_rows($result) == 0) {
        output("<tr><td colspan=4 align='center'>`&In questo paese non ci sono eroi`0</td></tr>", true);
    }
    $countrow = db_num_rows($result);
    for ($i=0; $i<$countrow; $i++){
    //for ($i = 0;$i < db_num_rows($result);$i++) {
        $row = db_fetch_assoc($result);
        if ($row['dio'] == 0) {
            $dio='`0Ateo';
        }
        if ($row['dio'] == 1) {
            $dio='`6Sgrios';
        }
        if ($row['dio'] == 2) {
            $dio='`$Karnak';
        }
        if ($row['dio'] == 3) {
            $dio='`@Drago Verde';
        }
        if ($row['name'] == $session['user']['name']) {
            output("<tr bgcolor='#007700'>", true);
        } else {
            output("<tr class='" . ($i % 2?"trlight":"trdark") . "'>", true);
        }
        output("<td>" . ($i + 1) . ".</td><td>".$row['name']."</td><td align=right>".$row['dragonkills']."</td><td align=center>".$row['level']."</td><td>&nbsp;</td><td align=center>",true);
        if($row['reincarna']){
            commutadk($row['reincarna']);
            output($row['reincarna']);
        }else output("`\$0");
        output("</td><td>$dio", true);
        $razza=$row[race];
        output("</td><td>$razzza[$razza]", true);
        output("</td><td align=center>`b`^".$row['pvpkills']."`b</td></tr>", true);
    }
    output("</table>", true);
    addnav("Torna alla Sala della Gloria", "hof.php");
} elseif ($_GET['op'] == "fortunati")  {
    $sql = "SELECT d.*,a.name
            FROM donazioni_lotteria d,accounts a
            WHERE d.acctid = a.acctid
            ORDER BY id DESC LIMIT 20";
    output("`c`b`^I vincitori della lotteria di Rafflingate !!!`b`c`0`n");
    output("<table cellspacing=0 cellpadding=2 align='center'><tr><td>`bData`b</td><td>`bNome`b</td><td>`bOperazione`b</td></tr>", true);
    $result = db_query($sql) or die(db_error(LINK));
    if (db_num_rows($result) == 0) {
        output("<tr><td colspan=4 align='center'>`&Nessun giocatore trovato`0</td></tr>", true);
    }
    $countrow = db_num_rows($result);
    for ($i=0; $i<$countrow; $i++){
    //for ($i = 0;$i < db_num_rows($result);$i++) {
        $row = db_fetch_assoc($result);
        if ($row['name'] == $session['user']['name']) {
            output("<tr bgcolor='#007700'>", true);
        } else {
            output("<tr class='" . ($i % 2?"trlight":"trdark") . "'>", true);
        }
        output("<td>" . $row['data'] . "</td><td>".$row['username']."</td><td align='right'>",true);
        if($row['prelevati']=='No' AND $row['acctid']==$session['user']['acctid']){
            output("<A href=hof.php?op=preleva_premio>`^Ritira premio`0</a>",true);
            addnav("", "hof.php?op=preleva_premio");
        }
        output("</td></tr>", true);
    }
    output("</table>`n", true);
    addnav("Torna alla Sala della Gloria", "hof.php");
} elseif ($_GET['op'] == "preleva_premio")  {
    db_query("UPDATE donazioni_lotteria SET prelevati='Si' WHERE acctid='".$session['user']['acctid']."'");
    $session['user']['donation']+=50;
    output("Complimenti ecco i tuoi 50 punti donazione!`n", true);
    addnav("Torna alla Sala della Gloria", "hof.php");
    debuglog("ritira 50 punti donazione della lotteria");
}else {
    $razzza=array(1=>"`2Troll",
    2=>"`^Elfi",
    3=>"`&Umani",
    4=>"`#Nani",
    5=>"`3Druidi",
    6=>"`@Goblin",
    7=>"`%Orchi",
    8=>"`\$Vampiri",
    9=>"`5Lich",
    10=>"`&Fanciulla delle Nevi",
    11=>"`4Oni",
    12=>"`3Satiro",
    13=>"`#Gigante delle Tempeste",
    14=>"`\$Barbaro",
    15=>"`%Amazzone",
    16=>"`^Titano",
    17=>"`\$Demone",
    18=>"`(Centauro",
    19=>"`8Licantropo",
    20=>"`)Minotauro",
    21=>"`^Cantastorie",
    22=>"`@Eletto",
    0=>"Sconosciuto",
    50=>"Pecora Volante",
    60=>"Tester",
    80=>"Moderatore",
    100=>"`^Admin",
    255=>"`^Divinità"
    );
    $sql = "SELECT name,race,dragonkills,level,age,bestdragonage,reincarna,dio,sex FROM accounts WHERE (dragonkills > 0 OR reincarna >0) AND superuser = 0 ORDER BY reincarna DESC,dragonkills DESC,level DESC,experience DESC LIMIT 50";
    output("`c`b`&Gli eroi di questo mondo`b`c`n");
    output("<table cellspacing=0 cellpadding=2 align='center'><tr><td>`bPos.`b</td><td>`bNome`b</td><td>`b`@DK`b</td><td>`b`&Livello`b</td><td>&nbsp;</td><td>`b`6Giorni`b</td><td>&nbsp;</td><td>`b`^Miglior DK`b</td><td>`b`5Reinc.`b</td><td>`8`bS`(e`8s`(s`8o`b</td><td>`#`bFede`b</td><td>`V`bRazza`b</td></tr>", true);
    $result = db_query($sql) or die(db_error(LINK));
    if (db_num_rows($result) == 0) {
        output("<tr><td colspan=4 align='center'>`&In questo paese non ci sono eroi`0</td></tr>", true);
    }
    $countrow = db_num_rows($result);
    for ($i=0; $i<$countrow; $i++){
    //for ($i = 0;$i < db_num_rows($result);$i++) {
        $row = db_fetch_assoc($result);
        if ($row['dio'] == 0) {
            $dio='`0Ateo';
        }
        if ($row['dio'] == 1) {
            $dio='`6Sgrios';
        }
        if ($row['dio'] == 2) {
            $dio='`$Karnak';
        }
        if ($row['dio'] == 3) {
            $dio='`@Drago Verde';
        }
        if ($row['name'] == $session['user']['name']) {
            output("<tr bgcolor='#007700'>", true);
        } else {
            output("<tr class='" . ($i % 2?"trlight":"trdark") . "'>", true);
        }
        output("<td>" . ($i + 1) . ".</td><td>".$row['name']."</td><td align=right>".$row['dragonkills']."</td><td align=right>".$row['level']."</td><td>&nbsp;</td><td align=right>" . ($row[age]?$row[age]:"Sconosciuto") . "</td><td>&nbsp;</td><td align=right>" . ($row['bestdragonage']?$row['bestdragonage']:"sconosciuto") . "</td><td align=center>",true);
        if($row['reincarna']){
            commutadk($row['reincarna']);
            output($row['reincarna']);
        }else output("`\$0");
        output("</td><td align='center'>".($row['sex']?"<img src=\"images/female.gif\">":"<img src=\"images/male.gif\">"),true);
        output("</td><td>$dio", true);
        $razza=$row[race];
        output("</td><td>$razzza[$razza]</td></tr>", true);

    }
    output("</table>", true);
    addnav("Top 20");
    addnav("R?I più Ricchi", "hof.php?op=reichtum");
    addnav("P?I più Poveri", "hof.php?op=armut");
    addnav("F?I più Forti", "hof.php?op=stark");
    addnav("C?I Colpi più potenti", "hof.php?op=punch");
    addnav("B?I più Belli", "hof.php?op=nice");
    addnav("K?I più Kattivi", "hof.php?op=evil");
    addnav("S?I più Spietati", "hof.php?op=pvp");
    addnav("Z?I più MoZzarella", "hof.php?op=pvpl");
    addnav("I?Imbranati", "hof.php?op=tot");
    addnav("O?I più famOsi (TEST)","hof.php?op=famosi");
    addnav("I più Fortunati", "hof.php?op=fortunati");
    addnav("Mestieri");
    addnav("A?Gli Agricoltori più ricchi","hof.php?op=fattorie");
    addnav("M?I Minatori migliori","hof.php?op=minatori");
    addnav("L?I boscaioLi migliori","hof.php?op=boscaioli");
    addnav("Altro");
    addnav("Q?Le coppie di Questo mondo", "hof.php?op=paare");
    addnav("N?I CompleaNni di LoGD", "hof.php?op=birthday");
    addnav("H?Lo scontro tra le cHiese", "hof.php?op=church");
    addnav("G?Gli oggetti migliori", "hof.php?op=oggetti");
    addnav("A?Gli artefatti", "hof.php?op=artefatti");
    addnav("D?Cavalieri di Draghi", "hof.php?op=cavalcare");
    addnav("T?Draghi più forTi", "hof.php?op=draghi");
    addnav(",?I più tassati", "hof.php?op=tasse");
    addnav(".?I Maghi del Labirinto", "hof.php?op=labi");
}
if ($session['user']['alive']) {
    if ($session['return']==""){
        addnav("V?Torna al Villaggio", "village.php");
    }else{
        $return = preg_replace("'[&?]c=[[:digit:]-]+'","",$session['return']);
        $return = substr($return,strrpos($return,"/")+1);
        addnav("Torna da dove sei venuto",$return);
    }
} else {
    addnav("Terra delle Ombre", "shades.php");
}

function commutarazza($scegli) {
        switch ($scegli) {
            case 0:
                output("`7Sconosciuto`0");
                break;
            case 1:
                output("`2Troll`0");
                break;
            case 2:
                output("`^Elfo`0");
                break;
            case 3:
                output("`0Umana`0");
                break;
            case 4:
                output("`#Nano`0");
                break;
            case 5:
                output("`3Druido`0");
                break;
            case 6:
                output("`6Goblin`0");
                break;
            case 7:
                output("`2Orco`0");
                break;
            case 8:
                output("`4Vampiro`0");
                break;
            case 9:
                output("`5Lich`0");
                break;
            case 10:
                output("`&Fanciulla delle Nevi`0");
                break;
            case 11:
                output("`4Oni`0");
                break;
            case 12:
                output("`3Satiro`0");
                break;
            case 13:
                output("`#Gigante delle Tempeste`0");
                break;
            case 14:
                output("`\$Barbaro`0");
                break;
            case 15:
                output("`%Amazzone`0");
                break;
            case 16:
                output("`^Titano`0");
                break;
            case 17:
                output("`\$Demone`0");
                break;
            case 18:
                output("`(Centauro`0");
                break;
            case 19:
                output("`^Licantropo`0");
                break;
            case 20:
                output("`)Minotauro");
                break;
            case 21:
                output("`^Cantastorie");
                break;
            case 22:
                output("`@Eletto");
                break;
            case 90:
                output("`%M`5o`%d`5e`%r`5a`%t`5o`%r`5e");
                break;
            case 100:
                output("`\$A`(d`^m`@i`#n");
                break;
        }
}
function commutadk($scegli){
        switch ($scegli) {
            case 1:
                output("`3");
                break;
            case 2:
                output("`2");
                break;
            case 3:
                output("`5");
                break;
            case 4:
                output("`4");
                break;
            case 5:
                output("`6");
                break;
            case 6:
                output("`!");
                break;
            case 7:
                output("`%");
                break;
            case 8:
                output("`8");
                break;
            case 9:
                output("`(");
                break;
            case 10:
                output("`r");
                break;
            case 11:
                output("`R");
                break;
            case 12:
                output("`g");
                break;
            case 13:
                output("`G");
                break;
            case 14:
                output("`q");
                break;
            case 15:
                output("`Q");
                break;
            case 16:
                output("`f");
                break;
            case 17:
                output("`F");
                break;
            case 18:
                output("`p");
                break;
            case 19:
                output("`E");
                break;
            case 20:
                output("`e");
                break;
            case 21:
                output("`J");
                break;
            case 22:
                output("`j");
                break;
            case 23:
                output("`X");
                break;
            case 24:
                output("`&");
                break;
        }
        output($row['reincarna']);
}
page_footer();

?>