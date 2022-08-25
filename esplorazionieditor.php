<?php
require_once "common.php";
require_once("common2.php");
isnewday(3);
reset($_POST);
reset($_POST['mount']);

page_header("Editor Luoghi Speciali della Foresta");
addnav("G?Torna nella Grotta","superuser.php");
addnav("M?Torna alla Mondanità","village.php");
addnav("Aggiungi un luogo","esplorazionieditor.php?op=add");
addnav("Dai mappa a player","esplorazionieditor.php?op=addmappla");
if ($_GET['op']==""){
    $sql = "SELECT * FROM mappe_foresta_luoghi";
    output("<table cellspacing=0 cellpadding=2 align='center'>", true);
    output("<tr><td align='center'>Terra numero</td><td align='center'>Nome</td><td align='center'>Giocatori che posseggono la mappa</td><td align='center'>Opzioni</td></tr>",true);
    $result = db_query($sql);

    $countrow = db_num_rows($result);
    for ($i=0; $i<$countrow; $i++){
    //for ($i=0;$i<db_num_rows($result);$i++){
        $row = db_fetch_assoc($result);
        output("<tr class='" . ($i % 2?"trlight":"trdark") . "'>",true);
        output("<td align='center'>{$row['id']}</td>",true);
        output("<td align='center'>{$row['nome']}</td>",true);
        $sql2="SELECT COUNT(acctid) AS quanti FROM mappe_foresta_player WHERE luogo=".$row['id'];
        $result2 = db_query($sql2) or die(db_error(LINK));
        $row2 = db_fetch_assoc($result2);
        output("<td align='center'>{$row2['quanti']} <a href='esplorazionieditor.php?op=chi&id={$row['id']}'>(chi sono?)</a></td>",true);
        addnav("","esplorazionieditor.php?op=chi&id={$row['id']}");
        output("<td align='center'>[<a href='esplorazionieditor.php?op=edit&id={$row['id']}'>Rinomina</a>]</td>",true);
        addnav("","esplorazionieditor.php?op=edit&id={$row['id']}");
        output("</tr>",true);
    }
    output("</table>",true);
}elseif ($_GET['op']=="add"){
    addnav("Home Editor Mappe","esplorazionieditor.php");
    creamappe(array());
}elseif ($_GET['op']=="addmappla"){
    addnav("Home Editor Mappe","esplorazionieditor.php");
    output("Il tuo id: ".$session[user][acctid]."`n`n");
    daimappe(array());
}elseif ($_GET['op']=="edit"){
    addnav("Torna Indietro","esplorazionieditor.php");
    $sql = "SELECT * FROM mappe_foresta_luoghi WHERE id='{$_GET['id']}'";
    $result = db_query($sql);
    if (db_num_rows($result)<=0){
        output("`iMappa non trovata.`i");
    }else{
        output("Editor Mappe:`n");
        $row = db_fetch_assoc($result);
        creamappe($row);
    }
}elseif ($_GET['op']=="save"){
    $keys='';
    $vals='';
    $sql='';
    $i=0;
    while (list($key,$val)=each($_POST['mount'])){
        if (is_array($val)) $val = addslashes(serialize($val));
        if ($_GET['id']>""){
            $sql.=($i>0?",":"")."$key='$val'";
        }else{
            $keys.=($i>0?",":"")."$key";
            $vals.=($i>0?",":"")."'$val'";
        }
        $i++;
    }
    if ($_GET['id']>""){
        $sql="UPDATE mappe_foresta_luoghi SET $sql WHERE id='{$_GET['id']}'";
    }else{
        $sql="INSERT INTO mappe_foresta_luoghi ($keys) VALUES ($vals)";
    }
    db_query($sql);
    if (db_affected_rows()>0){
        output("Mappa salvata!");
    }else{
        output("Mappa non salvata: $sql");
    }
    addnav("Home Editor Mappe","esplorazionieditor.php");
}elseif ($_GET['op']=="salva"){
    $keys='';
    $vals='';
    $sql='';
    $i=0;
    while (list($key,$val)=each($_POST['mount'])){
        if (is_array($val)) $val = addslashes(serialize($val));
        if ($_GET['id']>""){
            $sql.=($i>0?",":"")."$key='$val'";
        }else{
            $keys.=($i>0?",":"")."$key";
            $vals.=($i>0?",":"")."'$val'";
        }
        $i++;
    }
    if ($_GET['id']>""){
        $sql="UPDATE mappe_foresta_player SET $sql WHERE id='{$_GET['id']}'";
    }else{
        $sql="INSERT INTO mappe_foresta_player ($keys) VALUES ($vals)";
    }
    db_query($sql);
    if (db_affected_rows()>0){
        output("Mappa consegnata!");
    }else{
        output("Mappa non consegnata: $sql");
    }
    addnav("Home Editor Mappe","esplorazionieditor.php");
}elseif ($_GET['op']=="chi"){
    $sql = "SELECT * FROM mappe_foresta_player WHERE luogo=".$_GET[id]." ORDER BY acctid ASC";
    $result = db_query($sql) or die(db_error(LINK));
    output("<table cellspacing=0 cellpadding=2 align='center'>", true);
    output("<tr><td align='center'>ID</td><td align='center'>Personaggio</td><td align='center'>Visitato</td><td align='center'>Premio</td></tr>",true);

    $countrow = db_num_rows($result);
    for ($i=0; $i<$countrow; $i++){
    //for ($i=0;$i<db_num_rows($result);$i++){
        $row = db_fetch_assoc($result);
        output("<tr class='" . ($i % 2?"trlight":"trdark") . "'>",true);
        output("<td align='center'>`3{$row['id']}</td>",true);
        $sql2 = "SELECT name FROM accounts WHERE acctid=".$row[acctid];
        $result2 = db_query($sql2) or die(db_error(LINK));
        $row2 = db_fetch_assoc($result2);
        output("<td align='center'>{$row2['name']}`3</td>",true);
        if ($row[visitato]==0) {
            output("<td align='center'>No</td>",true);
        } else {
            output("<td align='center'>Sì</td>",true);
        }
        output("<td align='center'>`3{$row['premio']}</td>",true);
        output("</tr>",true);
    }
    output("</table>",true);
    addnav("Home Editor Mappe","esplorazionieditor.php");
}
function creamappe($mount){
    global $output;
    output("<form action='esplorazionieditor.php?op=save&id={$mount['id']}' method='POST'>",true);
    addnav("","esplorazionieditor.php?op=save&id={$mount['id']}");
    $output.="<table>";
    $output.="<tr><td>Nome:</td><td><input name='mount[nome]' value=\"".HTMLEntities2($mount['nome'])."\"></td></tr>";
    $output.="</table>";
    $output.="<input type='submit' class='button' value='Salva'></form>";
}
function daimappe($mount){
    global $output;
    output("<form action='esplorazionieditor.php?op=salva&id={$mount['id']}' method='POST'>",true);
    addnav("","esplorazionieditor.php?op=salva&id={$mount['id']}");
    $output.="<table>";
    $output.="<tr><td>Id mappa:</td><td><input name='mount[luogo]' value=\"".HTMLEntities2($mount['idoggetto'])."\"></td></tr>";
    $output.="<tr><td>Id player:</td><td><input name='mount[acctid]' value=\"".HTMLEntities2($mount['idplayer'])."\"></td></tr>";
    $output.="</table>";
    $output.="<input type='submit' class='button' value='Salva'></form>";
}
page_footer();
?>