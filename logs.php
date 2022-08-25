<?php
/*

View faillogs and user mail
Find multi accounts and cheaters

22052004 by anpera

*/


require_once("common.php");

page_header("Logs & Mail");

if ($_GET[op]=="mail"){
    if ($_GET[in]){
        $ppp=25; // Player Per Page to display
        if (!$_GET[limit]){
            $page=0;
        }else{
            $page=(int)$_GET[limit];
            addnav("Pagina Precedente","logs.php?op=mail&in=$_GET[in]&limit=".($page-1)."");
        }
        $limit="".($page*$ppp).",".($ppp+1);
        output("`(La Vecchia Posta (".($_GET[limit]*$ppp)."-".($_GET[limit]*$ppp+$ppp).") -- Tutte le Mail: Inbox $_GET[in] `0`n`n");
        $sql = "SELECT mail.*,accounts.login AS absender FROM mail LEFT JOIN accounts ON accounts.acctid=mail.msgfrom WHERE msgto=$_GET[in] ORDER BY sent DESC LIMIT $limit";
        $result = db_query($sql) or die(db_error(LINK));
        if (db_num_rows($result)>$ppp) addnav("Pagina Successiva","logs.php?op=mail&in=$_GET[in]&limit=".($page+1)."");
        output("<table align='center'><tr><td>`bData`b</td><td>`bMittente`b</td><td>`bOggetto`b</td></tr>",true);
        $countrow = db_num_rows($result);
        for ($i=0; $i<$countrow; $i++){
        //for ($i=0;$i<db_num_rows($result);$i++){
            $row = db_fetch_assoc($result);
            output("<tr><td>$row[sent]</td><td>$row[absender]</td><td>$row[subject]</td></tr><tr><td colspan='3'>$row[body]</td></tr>",true);
            output("<tr><td colspan='3'><hr></td></tr>",true);
        }
        output("</table>",true);
        addnav("Torna Indietro","logs.php?op=mail");
    }else if ($_GET[out]){
        $ppp=25; // Player Per Page to display
        if (!$_GET[limit]){
            $page=0;
        }else{
            $page=(int)$_GET[limit];
            addnav("Pagina Precedente","logs.php?op=mail&out=$_GET[in]&limit=".($page-1)."");
        }
        $limit="".($page*$ppp).",".($ppp+1);
        output("`(La Vecchia Posta (".($_GET[limit]*$ppp)."-".($_GET[limit]*$ppp+$ppp).") -- Tutte le Mail: Outbox $_GET[out] `0`n`n");
        $sql = "SELECT mail.*,accounts.login AS empfaenger FROM mail LEFT JOIN accounts ON accounts.acctid=mail.msgto WHERE msgfrom=$_GET[out] ORDER BY sent DESC LIMIT $limit";
        $result = db_query($sql) or die(db_error(LINK));
        if (db_num_rows($result)>$ppp) addnav("Pagina Successiva","logs.php?op=mail&out=$_GET[out]&limit=".($page+1)."");
        output("<table align='center'><tr><td>`bData`b</td><td>`bDestinatario`b</td><td>`bOggetto`b</td></tr>",true);
        $countrow = db_num_rows($result);
        for ($i=0; $i<$countrow; $i++){
        //for ($i=0;$i<db_num_rows($result);$i++){
            $row = db_fetch_assoc($result);
            output("<tr><td>$row[sent]</td><td>$row[empfaenger]</td><td>$row[subject]</td></tr><tr><td colspan='3'>$row[body]</td></tr>",true);
            output("<tr><td colspan='3'><hr></td></tr>",true);
        }
        output("</table>",true);
        addnav("Torna Indietro","logs.php?op=mail");
    }else{
        if (!$_GET[subop]) $_GET[subop]="system";
        $ppp=25; // Player Per Page to display
        if (!$_GET[limit]){
            $page=0;
        }else{
            $page=(int)$_GET[limit];
            addnav("Pagina Precedente","logs.php?op=mail&limit=".($page-1)."&subop=$_GET[subop]&order=$sort");
        }
        $limit="".($page*$ppp).",".($ppp+1);
        $sort="sent";
        if ($_GET[order]) $sort=$_GET[order];
        output("`(La Vecchia Posta (".($_GET[limit]*$ppp)."-".($_GET[limit]*$ppp+$ppp).") -- ".($_GET[subop]=="all"?"Mail Private":"Mail di Sistema")."`0`n`n");
        addnav($_GET[subop]=="system"?"Mail Normali":"Mail di Sistema","logs.php?op=mail&subop=".($_GET[subop]=="system"?"all":"system")."&order=$_GET[order]&limit=$_GET[limit]");
        $sql = "SELECT mail.*,accounts.login AS empfaenger FROM mail LEFT JOIN accounts ON accounts.acctid=mail.msgto WHERE ".($_GET[subop]=="system"?"msgfrom=0":"msgfrom<>0")." AND msgto<>0 ORDER BY $sort DESC LIMIT $limit";
        $result = db_query($sql) or die(db_error(LINK));
        if (db_num_rows($result)>$ppp) addnav("Pagina Successiva","logs.php?op=mail&limit=".($page+1)."&order=$sort&subop=$_GET[subop]");
        output("<table align='center'><tr><td>`b<a href='logs.php?op=mail&limit=$page&order=sent&subop=$_GET[subop]'>Data</a>`b</td><td>`b<a href='logs.php?op=mail&limit=$page&order=msgfrom&subop=$_GET[subop]'>Mittente</a>`b</td><td>`b<a href='logs.php?op=mail&limit=$page&order=msgto&subop=$_GET[subop]'>Destinatario</a>`b</td><td>`bOggetto`b</td></tr>",true);
        addnav("","logs.php?op=mail&limit=$page&order=sent&subop=$_GET[subop]");
        addnav("","logs.php?op=mail&limit=$page&order=msgto&subop=$_GET[subop]");
        addnav("","logs.php?op=mail&limit=$page&order=msgfrom&subop=$_GET[subop]");
        $countrow = db_num_rows($result);
        for ($i=0; $i<$countrow; $i++){
        //for ($i=0;$i<db_num_rows($result);$i++){
            $row = db_fetch_assoc($result);
            $row2=db_fetch_assoc(db_query("SELECT acctid,login FROM accounts WHERE acctid=$row[msgfrom]"));
            output("<tr><td>$row[sent]</td><td><a href='logs.php?op=mail&out=$row2[acctid]'>$row2[login]</a></td><td><a href='logs.php?op=mail&in=$row[msgto]'>$row[empfaenger]</a></td><td>$row[subject]</td></tr><tr><td colspan='4'>$row[body]</td></tr>",true);
            output("<tr><td colspan='4'><hr></td></tr>",true);
            addnav("","logs.php?op=mail&in=$row[msgto]");
            addnav("","logs.php?op=mail&out=$row2[acctid]");
        }
        output("</table>",true);
        addnav("Torna Indietro","logs.php");
        output("`n`iPer vedere la casella postale di un player, cliccare sul suo nome sotto \"Destinatario\".`nPer leggere le mail inviate da un player, cliccare su \"Mittente\".`i");
    }
}elseif ($_GET[op]=="mail2"){
    if (!$_POST[pl1] && $_GET[pl1]) $_POST[pl1] = $_GET[pl1]; 
    if (!$_POST[pl2] && $_GET[pl2]) $_POST[pl2] = $_GET[pl2]; 
    output("`(La Vecchia Posta -- Mail Player (2):`0`n`n");
    output("<form action='logs.php?op=mail2&pl1={$_POST['pl1']}&pl2={$_POST['pl2']}' method='POST'>",true);
    addnav("","logs.php?op=mail2&pl1={$_POST['pl1']}&pl2={$_POST['pl2']}");
    output("Acctid primo giocatore:<input name='pl1' value=\"".HTMLEntities2($_POST['pl1'])."\">`n`n",true);
    output("Acctid secondo giocatore:<input name='pl2' value=\"".HTMLEntities2($_POST['pl2'])."\">`n`n",true);
    output("<input type='submit' class='button' value='Aggiorna player'></form>`n`n`n",true);
    
    
    // Inbox $_GET[in] `0`n`n");
    if ($_POST[pl1] == ""){
        if ($_POST[pl2] != "") {
            $_POST[pl1] = $_POST[pl2];
            $_POST[pl2] = "";
        }
    }
    if ($_POST[pl1] != ""){
        $ppp=50; // Player Per Page to display
        if (!$_GET[limit]){
            $page=0;
        }else{
            $page=(int)$_GET[limit];
            if ($_POST[pl2] == "") {
                addnav("Pagina Precedente","logs.php?op=mail2&pl1={$_POST['pl1']}&limit=".($page-1)."");
            } else {
                addnav("Pagina Precedente","logs.php?op=mail2&pl1={$_POST['pl1']}&pl2={$_POST['pl2']}&limit=".($page-1)."");
            }
        }
        $limit="".($page*$ppp).",".($ppp+1);
        if ($_POST[pl2] == "") {
            $sqln = "SELECT name FROM accounts WHERE acctid='".$_POST['pl1']."'";
            $resultn = db_query($sqln) or die(db_error(LINK));
            $countrow = db_num_rows($resultn);
            if ($countrow == 1) {
                $rown = db_fetch_assoc($resultn);
                output("`((".($_GET[limit]*$ppp)."-".($_GET[limit]*$ppp+$ppp).") -- Mail di: ".$rown['name']." `0`n`n");
//                $sql = "SELECT mail.*,accounts.login AS absender FROM mail LEFT JOIN accounts ON accounts.acctid=mail.msgfrom WHERE (msgfrom='".$_POST[pl1]."' OR msgto='".$_POST[pl1]."') ORDER BY sent DESC LIMIT $limit";
                $sql = "SELECT mail.*,accounts.login AS absender FROM mail LEFT JOIN accounts ON accounts.acctid=mail.msgfrom WHERE msgfrom='".$_POST[pl1]."' UNION SELECT mail.*,accounts.login AS absender FROM mail LEFT JOIN accounts ON accounts.acctid=mail.msgto WHERE msgto='".$_POST[pl1]."' ORDER BY sent DESC LIMIT $limit";
                $result = db_query($sql) or die(db_error(LINK));
                if (db_num_rows($result)>$ppp) addnav("Pagina Successiva","logs.php?op=mail2&pl1={$_POST['pl1']}&limit=".($page+1)."");
                output("<table align='left'><tr><td>`bData`b</td><td>`bMittente`b</td><td>`bDestinatario`b</td><td>`bOggetto`b</td></tr>",true);
                $countrow = db_num_rows($result);
                for ($i=0; $i<$countrow; $i++){
                    //for ($i=0;$i<db_num_rows($result);$i++){
                    $row = db_fetch_assoc($result);
                    if ($row[msgfrom] == $_POST[pl1]){
                        $row2=db_fetch_assoc(db_query("SELECT acctid,login FROM accounts WHERE acctid=$row[msgto]"));
                        output("<tr><td>$row[sent]</td><td>$row[absender] (".$_POST['pl1'].")</td><td>$row2[login] ($row2[acctid])</td><td>$row[subject]</td></tr><tr><td colspan='4'>".html_entity_decode(str_replace("\n","`n",$row['body']))."</td></tr>",true);
                    } else {
                        $row2=db_fetch_assoc(db_query("SELECT acctid,login FROM accounts WHERE acctid=$row[msgfrom]"));
                        output("<tr><td>$row[sent]</td><td>$row2[login] ($row2[acctid])</td><td>$row[absender] (".$_POST['pl1'].")</td><td>$row[subject]</td></tr><tr><td colspan='4'>".html_entity_decode(str_replace("\n","`n",$row['body']))."</td></tr>",true);
                    }
                    output("<tr><td colspan='4'><hr></td></tr>",true);
                }
                output("</table>",true);
            } else {
                output("ID player inesistente");
            }
        } else {
            $sqln = "SELECT name, login FROM accounts WHERE acctid='".$_POST['pl1']."'";
            $resultn = db_query($sqln) or die(db_error(LINK));
            $countrow = db_num_rows($resultn);
            if ($countrow == 1) {
                $rown = db_fetch_assoc($resultn);
                $sqln = "SELECT name, login FROM accounts WHERE acctid='".$_POST['pl2']."'";
                $resultn = db_query($sqln) or die(db_error(LINK));
                $countrow = db_num_rows($resultn);
                if ($countrow == 1) {
                    $rowm = db_fetch_assoc($resultn);
                    output("`((".($_GET[limit]*$ppp)."-".($_GET[limit]*$ppp+$ppp).") -- Scambio mail tra ".$rown['name']." `(e ".$rowm['name']."`0`n`n");
                    $sql = "SELECT mail.*,accounts.login AS absender FROM mail LEFT JOIN accounts ON accounts.acctid=mail.msgfrom WHERE ((msgfrom='".$_POST['pl1']."' AND msgto='".$_POST['pl2']."') OR (msgfrom='".$_POST['pl2']."' AND msgto='".$_POST['pl1']."')) ORDER BY sent DESC LIMIT $limit";
                    $result = db_query($sql) or die(db_error(LINK));
                    if (db_num_rows($result)>$ppp) addnav("Pagina Successiva","logs.php?op=mail2&pl1={$_POST['pl1']}&pl2={$_POST['pl2']}&limit=".($page+1)."");
                    output("<table align='left'><tr><td>`bData`b</td><td>`bMittente`b</td><td>`bDestinatario`b</td><td>`bOggetto`b</td></tr>",true);
                    $countrow = db_num_rows($result);
                    for ($i=0; $i<$countrow; $i++){
                        //for ($i=0;$i<db_num_rows($result);$i++){
                        $row = db_fetch_assoc($result);
                        if ($row[msgfrom] == $_POST[pl1]){
                            output("<tr><td>$row[sent]</td><td>$rown[login] (".$_POST['pl1'].")</td><td>$rowm[login] (".$_POST['pl2'].")</td><td>$row[subject]</td></tr><tr><td colspan='4'>".html_entity_decode(str_replace("\n","`n",$row['body']))."</td></tr>",true);
                        } else {
                            output("<tr><td>$row[sent]</td><td>$rowm[login] (".$_POST['pl2'].")</td><td>$rown[login] (".$_POST['pl1'].")</td><td>$row[subject]</td></tr><tr><td colspan='4'>".html_entity_decode(str_replace("\n","`n",$row['body']))."</td></tr>",true);
                        }
                        output("<tr><td colspan='4'><hr></td></tr>",true);
                    }
                    output("</table>",true);
                } else {
                    output("ID player 2 inesistente");
                }
            } else {
            output("ID player 1 inesistente");
            }
        }
        addnav("Torna Indietro","logs.php?op=mail");
    }
} elseif ($_GET[op]=="faillog"){
    if ($_GET[id]){
        $ppp=25; // Player Per Page to display
        if (!$_GET[limit]){
            $page=0;
        }else{
            $page=(int)$_GET[limit];
            addnav("Pagina Precedente","logs.php?op=faillog&id=$_GET[id]&limit=".($page-1)."&order=$sort&pw=$_GET[pw]");
        }
        $limit="".($page*$ppp).",".($ppp+1);
        $sort="date";
        if ($_GET[order]) $sort=$_GET[order];
        $sql = "SELECT * FROM faillog WHERE acctid=$_GET[id] ORDER BY $sort DESC LIMIT $limit";
        $result = db_query($sql) or die(db_error(LINK));
        output("Login Errati (".($_GET[limit]*$ppp)."-".($_GET[limit]*$ppp+$ppp).") per l'ID $_GET[id]`n`n");
        if (db_num_rows($result)>$ppp) addnav("Pagina Successiva","logs.php?op=faillog&id=$_GET[id]&limit=".($page+1)."&order=$sort&pw=$_GET[pw]");
        output("<table align='center'><tr><td>`b<a href='logs.php?op=faillog&id=$_GET[id]&limit=$page&order=date'>Data</a>`b</td><td>`b<a href='logs.php?op=faillog&id=$_GET[id]&limit=$page&order=ip'>IP</a>`b</td>".($_GET[pw]?"<td>`bPW errata`b</td>":"")."</tr>",true);
        addnav("","logs.php?op=faillog&id=$_GET[id]&limit=$page&order=date");
        addnav("","logs.php?op=faillog&id=$_GET[id]&limit=$page&order=ip");
        $countrow = db_num_rows($result);
        for ($i=0; $i<$countrow; $i++){
        //for ($i=0;$i<db_num_rows($result);$i++){
            $row = db_fetch_assoc($result);
            if ($_GET[pw] && $session[user][superuser]>3) $row[post]=unserialize($row[post]);
            output("<tr><td>$row[date]</td><td>$row[ip]</td>".($_GET[pw]?"<td>".$row[post][password]."</td>":"")."</tr>",true);
        }
        addnav("Torna Indietro","logs.php?op=faillog&order=$_GET[order]");
        output("</table>",true);
        if ($session[user][superuser]>3) addnav(($_GET[pw]?"P?Non Mostrare ":"P?Mostrare ")."PW","logs.php?op=faillog&id=$_GET[id]".($_GET[pw]?"":"&pw=true")."&order=$_GET[order]&limit=$_GET[limit]");
        if ($_GET[pw] && $i>=3) output("`n`iIl controllo degli accessi per password errata, serve per verificare che sia errori di battitura e non tentativi di indovinare la password.`i");
    }else if ($_GET[ip]){
        $ppp=25; // Player Per Page to display
        if (!$_GET[limit]){
            $page=0;
        }else{
            $page=(int)$_GET[limit];
            addnav("Pagina Precedente","logs.php?op=faillog&ip=$_GET[ip]&limit=".($page-1)."&pw=$_GET[pw]");
        }
        $limit="".($page*$ppp).",".($ppp+1);
        $sql = "SELECT faillog.*,accounts.name AS absender FROM faillog LEFT JOIN accounts ON accounts.acctid=faillog.acctid WHERE ip='$_GET[ip]' ORDER BY date DESC LIMIT $limit";
        $result = db_query($sql) or die(db_error(LINK));
        output("Login Errati (".($_GET[limit]*$ppp)."-".($_GET[limit]*$ppp+$ppp).") dall'IP $_GET[ip]`n`n");
        if (db_num_rows($result)>$ppp) addnav("Pagina Successiva","logs.php?op=faillog&ip=$_GET[ip]&limit=".($page+1)."&pw=$_GET[pw]");
        output("<table align='center'><tr><td>`bData`b</td><td>`bNome`b</td>".($_GET[pw]?"<td>`bPW errate`b</td>":"")."</tr>",true);
        $countrow = db_num_rows($result);
        for ($i=0; $i<$countrow; $i++){
        //for ($i=0;$i<db_num_rows($result);$i++){
            $row = db_fetch_assoc($result);
            if ($_GET[pw] && $session[user][superuser]>3) $row[post]=unserialize($row[post]);
            output("<tr><td>$row[date]</td><td>$row[absender]</td>".($_GET[pw]?"<td>".$row[post][password]."</td>":"")."</tr>",true);
        }
        addnav("Torna Indietro","logs.php?op=faillog&order=$_GET[order]");
        output("</table>",true);
        if ($session[user][superuser]>3) addnav(($_GET[pw]?"P?Non Mostrare ":"P?Mostrare ")."PW","logs.php?op=faillog&ip=$_GET[ip]".($_GET[pw]?"":"&pw=true")."&order=$_GET[order]&limit=$_GET[limit]");
        if ($_GET[pw] && $i>=3) output("`n`iIl controllo degli accessi per password errata, servono per verificare che sia errori di battitura e non tentativi di indovinare la password.`i");
    }else{
        $ppp=25; // Player Per Page to display
        output("Login Errati (".($_GET[limit]*$ppp)."-".($_GET[limit]*$ppp+$ppp).")`n`iCliccare sul nome o sull'IP, per visualizzare tutti i tentativi errati.`i`n`n");
        if (!$_GET[limit]){
            $page=0;
        }else{
            $page=(int)$_GET[limit];
            addnav("Pagina Precedente","logs.php?op=faillog&limit=".($page-1)."&order=$sort");
        }
        $limit="".($page*$ppp).",".($ppp+1);
        $sort="date";
        if ($_GET[order]) $sort=$_GET[order];
        $sql = "SELECT faillog.*,accounts.name AS absender FROM faillog LEFT JOIN accounts ON accounts.acctid=faillog.acctid WHERE 1 ORDER BY $sort DESC LIMIT $limit";
        $result = db_query($sql) or die(db_error(LINK));
        if (db_num_rows($result)>$ppp) addnav("Pagina Successiva","logs.php?op=faillog&limit=".($page+1)."&order=$sort");
        output("<table align='center'><tr><td>`b<a href='logs.php?op=faillog&limit=$page&order=date'>Data</a>`b</td><td>`b<a href='logs.php?op=faillog&limit=$page&order=acctid'>Acctid</a>`b</td><td>`bNome`b</td><td>`b<a href='logs.php?op=faillog&limit=$page&order=ip'>IP</a>`b</td></tr>",true);
        addnav("","logs.php?op=faillog&limit=$page&order=date");
        addnav("","logs.php?op=faillog&limit=$page&order=acctid");
        addnav("","logs.php?op=faillog&limit=$page&order=ip");
        $countrow = db_num_rows($result);
        for ($i=0; $i<$countrow; $i++){
        //for ($i=0;$i<db_num_rows($result);$i++){
            $row = db_fetch_assoc($result);
            output("<tr><td>$row[date]</td><td>$row[acctid]</td><td><a href='logs.php?op=faillog&id=$row[acctid]&order=$sort'>$row[absender]</a></td><td><a href='logs.php?op=faillog&ip=$row[ip]&order=$sort'>$row[ip]</a></td></tr>",true);
            addnav("","logs.php?op=faillog&id=$row[acctid]&order=$sort");
            addnav("","logs.php?op=faillog&ip=$row[ip]&order=$sort");
        }
        output("</table>",true);
        addnav("Torna Indietro","logs.php");
    }
}else{
    output("Gli ultimi 5 login errati:`n`n");
    $sql = "SELECT faillog.*,accounts.name AS absender FROM faillog LEFT JOIN accounts ON accounts.acctid=faillog.acctid WHERE 1 ORDER BY date DESC LIMIT 5";
    $result = db_query($sql) or die(db_error(LINK));
    output("<table align='center'><tr><td>`bData`b</td><td>`bAcctid`b</td><td>`bNome`b</td><td>`bIP`b</td></tr>",true);
    $countrow = db_num_rows($result);
    for ($i=0; $i<$countrow; $i++){
    //for ($i=0;$i<db_num_rows($result);$i++){
        $row = db_fetch_assoc($result);
        output("<tr><td>$row[date]</td><td>$row[acctid]</td><td>$row[absender]</td><td>$row[ip]</td></tr>",true);
    }
    output("</table>`n`nLe ultime 5 Mail di Sistema:`n`n",true);
    $sql = "SELECT mail.*,accounts.name AS empfaenger FROM mail LEFT JOIN accounts ON accounts.acctid=mail.msgto WHERE msgfrom=0 ORDER BY sent DESC LIMIT 5";
    $result = db_query($sql) or die(db_error(LINK));
    output("<table align='center'><tr><td>`bData`b</td><td>`bDestinatario`b</td><td>`bOggetto`b</td></tr>",true);
    $countrow = db_num_rows($result);
    for ($i=0; $i<$countrow; $i++){
    //for ($i=0;$i<db_num_rows($result);$i++){
        $row = db_fetch_assoc($result);
        output("<tr><td>$row[sent]</td><td>$row[empfaenger]</td><td>$row[subject]</td></tr>",true);
    }
    output("</table>`n",true);
    addnav("Faillog","logs.php?op=faillog");
    addnav("User Mail","logs.php?op=mail");
    addnav("User Mail 2 (conversazioni)","logs.php?op=mail2");
    addnav("Aggiorna","logs.php");
}
addnav("G?Torna alla Grotta","superuser.php");
addnav("M?Torna alla Mondanità","village.php");
output("`n<div align='right'>`)2004 by anpera</div>",true);
page_footer();
?>