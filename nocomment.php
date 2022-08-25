<?php
require_once("common.php");
require_once("common2.php");
isnewday(2);
if ($_GET['op']=="search"){
    $sql = "SELECT acctid FROM accounts WHERE ";
    $where="login LIKE '%{$_POST['q']}%'";
    $result = db_query($sql.$where);
    if (db_num_rows($result)<=0){
        output("`\$Nessun player trovato`0");
        $_GET['op']="";
        $where="";
    }elseif (db_num_rows($result)>100){
        output("`\$Troppi risultati, restringi il campo di ricerca per favore.`0");
        $_GET['op']="";
        $where="";
    }elseif (db_num_rows($result)==1){
        //$row = db_fetch_assoc($result);
        //redirect("nocomment.php?op=edit&userid=$row[acctid]");
        $_GET['op']="";
        $_GET['page']=0;
    }else{
        $_GET['op']="";
        $_GET['page']=0;
    }
}
page_header("Mute Player & FixNavs");
output("<form action='nocomment.php?op=search' method='POST'>Cerca un player: <input name='q' id='q'><input type='submit' class='button'></form>",true);
output("<script language='JavaScript'>document.getElementById('q').focus();</script>",true);
addnav("","nocomment.php?op=search");
addnav("G?Torna alla Grotta","superuser.php");
addnav("M?Torna alla Mondanità","village.php");
addnav("S?Elenca Personaggi Silenziati","nocomment.php?op=silenced");
addnav("V?Visualizza Mute Assegnati","nocomment.php?op=assigned");
if ($session['user']['superuser']>2) addnav("U?Editor Utenti","user.php");
//output("`@Scegli un'opzione`n");

$sql = "SELECT count(acctid) AS count FROM accounts";
$result = db_query($sql);
$row = db_fetch_assoc($result);
$page=0;
while ($row['count']>0){
    $page++;
    addnav("$page Pagina di $page","nocomment.php?page=".($page-1)."&sort=$_GET[sort]");
    $row['count']-=100;
}

if ($_GET['op']==""){
    if (isset($_GET['page'])){
        $order = "acctid";
        if ($_GET['sort']!="") $order = "$_GET[sort]";
        $offset=(int)$_GET['page']*100;
        $sql = "SELECT acctid,login,name,level,laston,loggedin FROM accounts ".($where>""?"WHERE $where ":"")."ORDER BY \"$order\" LIMIT $offset,100";
        $result = db_query($sql) or die(db_error(LINK));
        output("<table>",true);
        output("<tr>
        <td>Ops</td>
        <td>Login</td>
        <td>Nome</td>
        <td>Laston</td>
        <td>Liv</td>
        </tr>",true);
        $rn=0;
        $countrow = db_num_rows($result);
        for ($i=0; $i<$countrow; $i++){
        //for ($i=0;$i<db_num_rows($result);$i++){
            $row=db_fetch_assoc($result);
            $laston=round((strtotime(date("r"))-strtotime($row[laston])) / 86400,0)." giorni";
            if (substr($laston,0,2)=="1 ") $laston="1 day";
            if (date("Y-m-d",strtotime($row[laston])) == date("Y-m-d")) $laston="Oggi";
            if (date("Y-m-d",strtotime($row[laston])) == date("Y-m-d",strtotime(date("r")."-1 day"))) $laston="Ieri";
            if ($row['loggedin']) $laston="Ora";
            $row[laston]=$laston;
            output("<tr class='".($rn%2?"trlight":"trdark")."'>",true);
            output("<td>",true);
            output("[<a href='nocomment.php?op=pm&userid=".$row['acctid']."'>PM</a>|<a href='nocomment.php?op=mute&userid=".$row['acctid']."'>Mute</a>|<a href='nocomment.php?op=fixnav&userid=".$row['acctid']."'>Fissa Link</a>]",true);
            addnav("","nocomment.php?op=pm&userid=".$row['acctid']);
            addnav("","nocomment.php?op=mute&userid=".$row['acctid']);
            addnav("","nocomment.php?op=fixnav&userid=".$row['acctid']);
            output("</td><td>",true);
            output($row['login']);
            output("</td><td>",true);
            output($row['name']);
            output("</td><td>",true);
            output($row['laston']);
            output("</td><td>",true);
            output($row['level']);
            output("</td>",true);
            output("</tr>",true);
        }
        output("</table>",true);
    }
}elseif ($_GET['op']=="mute"){
    $sqlnome="SELECT name FROM accounts WHERE acctid=".$_GET['userid'];
    $resultnome = db_query($sqlnome) or die(db_error(LINK));
    $countrow = db_num_rows($resultnome);
    for ($i=0; $i<$countrow; $i++){
    //for ($i = 0;$i < db_num_rows($result);$i++) {
        $nome=db_fetch_assoc($resultnome);
    }
    $session['punitions']=$nome['name'];
    output("<form action='nocomment.php?op=muteok&userid=".$_GET['userid']."' method='POST'>Giorni di Mute per ".$nome['name']."`0: <input name='giorni' value='4' id='giorni'>`n",true);
    output("`nCausa del mute:`n",true);
    output("<textarea class='input' name='cause' cols='40' rows='10'>".HTMLEntities(stripslashes($_POST['cause']))."</textarea>`n",true);
    output("`nInserisci il testo del PM da che vuoi inviare a ".$nome['name']."`0:`n",true);
    output("<textarea class='input' name='mailmessage' cols='40' rows='10'>".HTMLEntities(stripslashes($_POST['mailmessage']))."</textarea>`n",true);
    output("<input type='submit' class='button'></form>",true);
    output("<script language='JavaScript'>document.getElementById('giorni').focus();</script>",true);
    addnav("","nocomment.php?op=muteok&userid=".$_GET['userid']);
}elseif ($_GET['op']=="muteok"){
    $sql = "SELECT acctid, name, uniqueid, emailaddress FROM accounts WHERE acctid = ".$_GET['userid'];
    $result = db_query($sql) or die(db_error(LINK));
    $row = db_fetch_assoc($result);
    $nomemute = $row['name'];
    $sql = "SELECT acctid, name FROM accounts
            WHERE uniqueid = '".$row['uniqueid']."'
            OR emailaddress = '".$row['emailaddress']."'";
    $result = db_query($sql) or die(db_error(LINK));
    $countrow = db_num_rows($result);
    if ($countrow == 1){
        $sqlmute = "UPDATE accounts SET nocomment = ".$_POST['giorni']." WHERE acctid = ".$_GET['userid'];
        db_query($sqlmute) or die(db_error(LINK));
        $sqlmute = "INSERT INTO mute VALUES ('','".$_POST['giorni']."','".$_GET['userid']."','".$session['user']['acctid']."',NOW())";
        db_query($sqlmute) or die(db_error(LINK));
        output("`@Mute di ".$_POST['giorni']." giorni assegnato`n");
        debuglog("`0assegna un mute di ".$_POST['giorni']." giorni a ",$_GET['userid']);
        report(2,"`&Messo mute","`\$".$session['user']['name']."`2 ha messo un mute di `@".$_POST['giorni']." NewDay `2al player `(".$session['punitions'],"provvedimenti");
        $sqlmute_punitions = "INSERT INTO punitions (acctid_guilty,guilty,whomade,date,cause,type,numday) VALUES ('".$_GET['userid']."','".$session['punitions']."','".$session['user']['acctid']."',now(),'".$_POST['cause']."','Mute','".$_POST['giorni']."')";
        db_query($sqlmute_punitions) or die(db_error(LINK));
        systemmail($_GET['userid'],"`#Mute!!",$_POST['mailmessage'],$session['user']['acctid']);
    }else{
        for ($x=0; $x < $countrow; $x++){
            $row=db_fetch_assoc($result);
            $sqlmute = "UPDATE accounts SET nocomment = ".$_POST['giorni']." WHERE acctid = ".$row['acctid'];
            db_query($sqlmute) or die(db_error(LINK));
            $sqlmute = "INSERT INTO mute VALUES ('','".$_POST['giorni']."','".$row['acctid']."','".$session['user']['acctid']."',NOW())";
            //print "QuerySQL mute: ".$sqlmute."<br>";
            db_query($sqlmute) or die(db_error(LINK));
            output("`@Mute di ".$_POST['giorni']." giorni assegnato a ".$row['name']."`n");
            debuglog("`0assegna un mute di ".$_POST['giorni']." giorni a ",$row['acctid']);
            report(2,"`&Messo mute","`\$".$session['user']['name']."`2 ha messo un mute di `@".$_POST['giorni']." NewDay `2al player `(".$row['name']."`n`r(causato da ".$nomemute."`r)","provvedimenti");
            $sqlmute_punitions = "INSERT INTO punitions (acctid_guilty,guilty,whomade,date,cause,type,numday) VALUES ('".$row['acctid']."','".addslashes($row['name'])."','".$session['user']['acctid']."',now(),'".$_POST['cause']."','Mute','".$_POST['giorni']."')";
            db_query($sqlmute_punitions) or die(db_error(LINK));
            $_POST['mailmessage'] .= "Questo mute è stato causato da ".$nomemute;
            systemmail($row['acctid'],"`#Mute!!",$_POST['mailmessage'],$session['user']['acctid']);
        }
    }
}elseif($_GET['op']=="silenced"){
    output("<table><tr><td>`cOps`c</td><td>`cAcctid`c</td><td>`cNome`c</td><td>`cDurata`c</td></tr>",true);
    $sql = "SELECT acctid, name, nocomment FROM accounts WHERE nocomment>0 ORDER BY nocomment DESC, acctid ASC";
    $result = db_query($sql) or die(db_error(LINK));
    $countrow = db_num_rows($result);
    for ($i=0; $i<$countrow; $i++){
    //for ($i=0;$i<db_num_rows($result);$i++){
        $row = db_fetch_assoc($result);
        output("<tr class='".($i%2?"trlight":"trdark")."'><td>
                <a href=\"nocomment.php?op=unmute&id=".$row['acctid']."\">Unmute</a>",true);
        addnav("","nocomment.php?op=unmute&id=".$row['acctid']);
        output("</td><td>",true);
        output($row['acctid']);
        output("</td><td>",true);
        output($row['name']);
        output("</td><td>",true);
        output($row['nocomment']);
        output("</td></tr>",true);
    }
    output("</table>",true);
}elseif($_GET['op']=="unmute"){
    $sql = "UPDATE accounts SET nocomment=0 WHERE acctid=".$_GET['id'];
    db_query($sql) or die(db_error(LINK));
    output("`n`n`@Blocco commenti rimosso.");
    debuglog("`0rimuove un mute a ",$_GET['id']);
    report(2,"`&Tolto mute","`\$".$session['user']['name']."`6 ha tolto un mute al player `G".$_GET['id'],"provvedimenti");
}elseif($_GET['op']=="fixnav"){
    $sql = "UPDATE accounts SET allowednavs='',output=\"\" WHERE acctid=".$_GET['userid'];
    db_query($sql) or die(db_error(LINK));
    debuglog("ha fissato il link del player ",$_GET['userid']);
    output("`n`n`@È stata fissata la navigazione del player ".$row['login'].".");
}elseif($_GET['op']=="assigned"){
    $limit = 100;
    $com=(int)$_GET['comscroll'];
    $sql = "SELECT mute.*,
                   accounts.name,
                   accounts.acctid
              FROM mute
             INNER JOIN accounts
                ON accounts.acctid = mute.acctid
             ORDER BY id ASC
             LIMIT ".($com*$limit).",$limit";
    //$sql = "SELECT * FROM mute WHERE 1";
    db_query($sql) or die(db_error(LINK));
    $result = db_query($sql) or die(db_error(LINK));
    output("<table cellspacing=2 cellpadding=2 align='center'>",true);
    output("<tr bgcolor='#AA0000'>
            <td align='center'>`^`bAdmin/Mod</td>
            <td align='center'>`&Player</td>
            <td align='center'>`&Durata</td>
            <td align='center'>`&Data`b</td></tr>",true);
    $countrow = db_num_rows($result);
    for ($i=0; $i<$countrow; $i++){
//    for ($i=0;$i < db_num_rows($result);$i++){
        $row = db_fetch_assoc($result);
        $sqlname = "SELECT name FROM accounts WHERE acctid = ".$row['committente'];
        $resultname = db_query($sqlname) or die(db_error(LINK));
        $rowname = db_fetch_assoc($resultname);
        output("<tr class='" . ($i % 2?"trlight":"trdark") . "'>",true);
        output("<td align='center'>`\$".$rowname['name']."</td>
                <td align='center'>`#".$row['name']."</td>
                <td align='center'>`&".$row['giorni']."</td>
                <td align='center'>`@".$row['mutedate']."</td></tr>",true);
    }
    output("</table>",true);
    //print "Result = ".db_num_rows($result)." Limit = ".$limit;
    if (db_num_rows($result)>=$limit){
        $req = preg_replace("'[&]?c(omscroll)?=([[:digit:]-])*'","",$REQUEST_URI)."&comscroll=".($com+1);
        $req = str_replace("?&","?",$req);
        if (!strpos($req,"?")) $req = str_replace("&","?",$req);
        //if (!strpos($req,$mod)) $req.="&mod=".$mod;
        output("<a href=\"$req\">&lt;&lt; Precedente</a>",true);
        addnav("",$req);
    }
    $req = preg_replace("'[&]?c(omscroll)?=([[:digit:]]|-)*'","",$REQUEST_URI)."&comscroll=0";
    $req = str_replace("?&","?",$req);
    if (!strpos($req,"?")) $req = str_replace("&","?",$req);
    //if (!strpos($req,$mod)) $req.="&mod=".$mod;
    output("&nbsp;<a href=\"$req\">Aggiorna</a>&nbsp;",true);
    addnav("",$req);
    if ($com>0){
        $req = preg_replace("'[&]?c(omscroll)?=([[:digit:]]|-)*'","",$REQUEST_URI)."&comscroll=".($com-1);
        $req = str_replace("?&","?",$req);
        if (!strpos($req,"?")) $req = str_replace("&","?",$req);
        //if (!strpos($req,$mod)) $req.="&mod=".$mod;
        output(" <a href=\"$req\">Prossima &gt;&gt;</a>",true);
        addnav("",$req);
    }
}elseif($_GET['op']=="pm"){
        $sql = "SELECT name FROM accounts WHERE acctid=".$_GET['userid'];
    $resultnome = db_query($sql) or die(db_error(LINK));
    $countrow = db_num_rows($result);
    for ($i=0; $i<$countrow; $i++){
    //for ($i = 0;$i < db_num_rows($result);$i++) {
        $nome=db_fetch_assoc($resultnome);
    }
    $provanome = $nome['name'];
    $session['punitions']=$nome['name'];
    output("<form action='nocomment.php?op=pmok&userid=".$_GET['userid']."' method='POST'>`n",true);
        output("`n`nInserire il motivo per l'avvertimento al player ".$nome['name']."`0:`n",true);
        //output("<input type='hidden' name='name' value='".$nome['name']."'/>",true);
    output("<textarea class='input' name='cause' cols='40' rows='10'>".HTMLEntities(stripslashes($_POST['cause']))."</textarea>`n",true);
    output("`nInserisci il testo del PM da che vuoi inviare a ".$nome['name']."`0:`n",true);
    output("<textarea class='input' name='mailmessage' cols='40' rows='10'>".HTMLEntities(stripslashes($_POST['mailmessage']))."</textarea>`n",true);
    output("<input type='submit' class='button'></form>",true);
    addnav("","nocomment.php?op=pmok&userid=".$_GET['userid']);
}elseif ($_GET['op']=="pmok"){
    $sqlpm_punitions = "INSERT INTO punitions (acctid_guilty,guilty,whomade,date,cause,type) VALUES ('".$_GET['userid']."','".$session['punitions']."','".$session['user']['acctid']."',now(),'".$_POST['cause']."','PM')";
    db_query($sqlpm_punitions) or die(db_error(LINK));
    output("`@Segnalazione PM avvenuta.");
    report(2,"`&Avvertito player","`\$".$session['user']['name']."`3 ha mandato un avvertimento a `^".$session['punitions'],"provvedimenti");
    systemmail($_GET['userid'],"`#Avvertimento!!",$_POST['mailmessage'],$session['user']['acctid']);


    db_free_result($result);
}

page_footer();
?>