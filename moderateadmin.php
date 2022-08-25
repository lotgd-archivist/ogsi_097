<?php
require_once "common.php";
isnewday(2);

page_header("Moderazione Commenti");
addnav("G?Torna alla Grotta","superuser.php");
addnav("M?Torna alla Moderazione","moderate.php");
if (isset($_POST['mod'])){
    $moderatore = $_POST['mod'];
}
if (isset($_GET['mod'])){
    $moderatore = $_GET['mod'];
}
output("<div align=\"center\"><form method=\"POST\" name=\"section\" action=\"moderateadmin.php\">",true);
output("<select name=\"mod\" class=\"input\" OnChange=\"document.section.submit();\">",true);
output("<option value=\"\" selected>Scegli Mod/Admin</option><br>",true);

$sql = "SELECT DISTINCT commentdeleted.cancellatore,
                        accounts.name,
                        accounts.acctid
                   FROM commentdeleted
             INNER JOIN accounts
                     ON accounts.acctid = commentdeleted.cancellatore
               ORDER BY cancellatore ASC";
$result = db_query($sql) or die(sql_error($sql));
$countrow = db_num_rows($result);
for ($i=0; $i<$countrow; $i++){
//for($i=0;$i<db_num_rows($result);$i++){
    $row = db_fetch_assoc($result);
    output(" <option value=\"".$row['cancellatore']."\">".$row['name']."</option>",true);
}
output("</select>",true);
output("</form></div><br>",true);

addnav("","moderateadmin.php");

if($_GET['op'] == "ripristina"){
    $sqlrip = "SELECT * FROM commentdeleted WHERE commentid = ".$_GET['commentid'];
    $resultrip = db_query($sqlrip) or die(db_error(LINK));
    $rowrip = db_fetch_assoc($resultrip);
    $sqlins = "INSERT INTO commentary VALUES
           ('".$rowrip['commentid']."',
           '".$rowrip['section']."',
           '".$rowrip['author']."',
           '".addslashes($rowrip['comment'])."',
           '".$rowrip['postdate']."')";
    $resultins = db_query($sqlins) or die(db_error(LINK));
    $sqldel = "DELETE FROM commentdeleted WHERE commentid = ".$_GET['commentid'];
    $resultdel = db_query($sqldel) or die(db_error(LINK));
    $moderatore = $_GET['mod'];
}

commenti($moderatore,100);

function commenti($mod,$limit=10) {
    global $_POST,$session,$REQUEST_URI,$_GET;
    $com=(int)$_GET[comscroll];
    $sql = "SELECT commentdeleted.*,
                   accounts.login,
                   accounts.acctid
              FROM commentdeleted
             INNER JOIN accounts
                ON accounts.acctid = commentdeleted.cancellatore
             WHERE cancellatore = '".$mod."'
             ORDER BY postdate ASC
             LIMIT ".($com*$limit).",$limit";
    $result = db_query($sql) or die(db_error(LINK));
    output("<table cellspacing=2 cellpadding=2 align='center'>",true);
    output("<tr bgcolor='#AA0000'>
            <td align='center'>`%Ops`0</td>
            <td align='center'>`^`bMod/Admin`b`0</td>
            <td align='center'>`&Poster`0</td>
            <td align='center'>`&Commento Cancellato`0</td>
            <td align='center'>`&Data`0</td>
            <td align='center'>`&Sezione`0</td></tr>",true);
    $countrow = db_num_rows($result);
    for ($i=0; $i<$countrow; $i++){
    //for ($i=0;$i < db_num_rows($result);$i++){
        $row = db_fetch_assoc($result);
        $sqlname = "SELECT name FROM accounts WHERE acctid = ".$row['author'];
        $resultname = db_query($sqlname) or die(db_error(LINK));
        $rowname = db_fetch_assoc($resultname);
        output("<tr class='" . ($i % 2?"trlight":"trdark") . "'>",true);
        output("<td>[<a href='moderateadmin.php?op=ripristina&commentid=".$row['commentid']."&mod=$mod'>Cure</a>]</td>
                <td align='center'>`\$".$row['login']."</td>
                <td align='center'>`#".$rowname['name']."</td>
                <td align='center'>`&".$row['comment']."</td>
                <td align='center'>`@".$row['postdate']."</td>
                <td align='center'>`X".$row['section']."</td></tr>",true);
        addnav("","moderateadmin.php?op=ripristina&commentid=".$row['commentid']."&mod=$mod");
    }
    output("</table>",true);
    if (db_num_rows($result)>=$limit){
        $req = preg_replace("'[&]?c(omscroll)?=([[:digit:]-])*'","",$REQUEST_URI)."&comscroll=".($com+1);
        $req = str_replace("?&","?",$req);
        if (!strpos($req,"?")) $req = str_replace("&","?",$req);
        if (!strpos($req,$mod)) $req.="&mod=".$mod;
        output("<a href=\"$req\">&lt;&lt; Precedente</a>",true);
        addnav("",$req);
    }
    $req = preg_replace("'[&]?c(omscroll)?=([[:digit:]]|-)*'","",$REQUEST_URI)."&comscroll=0";
    $req = str_replace("?&","?",$req);
    if (!strpos($req,"?")) $req = str_replace("&","?",$req);
    if (!strpos($req,$mod)) $req.="&mod=".$mod;
    output("&nbsp;<a href=\"$req\">Aggiorna</a>&nbsp;",true);
    addnav("",$req);
    if ($com>0){
        $req = preg_replace("'[&]?c(omscroll)?=([[:digit:]]|-)*'","",$REQUEST_URI)."&comscroll=".($com-1);
        $req = str_replace("?&","?",$req);
        if (!strpos($req,"?")) $req = str_replace("&","?",$req);
        if (!strpos($req,$mod)) $req.="&mod=".$mod;
        output(" <a href=\"$req\">Prossima &gt;&gt;</a>",true);
        addnav("",$req);
    }
    db_free_result($result);
}
page_footer();
?>