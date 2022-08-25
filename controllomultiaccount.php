<?php
require_once "common.php";
isnewday(3);

page_header("Ricerca Multiaccount");
output("<form action='controllomultiaccount.php?op=debuglog' method='POST'>
    Ricerca acctid personaggi: <input name='z' id='z'> (se non è vuoto i campi seguenti saranno ignorati)`n`n
    Acctid personaggio: <input name='p' id='p' value='".$_POST['p']."'>`n
    <input type='radio' name='ipaux' value='1'>AND <input type='radio' name='ipaux' value='2'>OR `n
    IP: <input name='q' id='q' value='".$_POST['q']."'>`n
    <input type='radio' name='idaux' value='1'>AND <input type='radio' name='idaux' value='2'>OR `n
    ID: <input name='r' id='r' value='".$_POST['r']."'>`n
    <input type='checkbox' name='data1' value='1'>DOPO IL (compilare tutti i campi di data e ora correttamente)`n
    (anno)<input name='anno1' id='anno1' value='".$_POST['anno1']."'>
    (mese)<input name='mese1' id='mese1' value='".$_POST['mese1']."'>
    (giorno)<input name='giorno1' id='giorno1' value='".$_POST['giorno1']."'>
    (ora)<input name='ora1' id='ora1' value='".$_POST['ora1']."'>
    (minuti)<input name='minuti1' id='minuti1' value='".$_POST['minuti1']."'>
    (secondi)<input name='secondi1' id='secondi1' value='".$_POST['secondi1']."'>`n
    <input type='checkbox' name='data2' value='1'>PRIMA DEL (compilare tutti i campi di data e ora correttamente)`n
    (anno)<input name='anno2' id='anno2' value='".$_POST['anno2']."'>
    (mese)<input name='mese2' id='mese2' value='".$_POST['mese2']."'>
    (giorno)<input name='giorno2' id='giorno2' value='".$_POST['giorno2']."'>
    (ora)<input name='ora2' id='ora2' value='".$_POST['ora2']."'>
    (minuti)<input name='minuti2' id='minuti2' value='".$_POST['minuti2']."'>
    (secondi)<input name='secondi2' id='secondi2' value='".$_POST['secondi2']."'>`n
    <input type='submit' class='button'></form>",true);
output("<script language='JavaScript'>document.getElementById('q').focus();</script>",true);
addnav("","controllomultiaccount.php?op=debuglog");
addnav("G?Torna alla Grotta","superuser.php");
addnav("M?Torna alla Mondanità","village.php");
//addnav("User Editor Home","user.php");

if ($_GET[op]=="debuglog"){
    if ($_POST['z']!="") {
        $sql = "SELECT acctid FROM accounts WHERE ";
        $where="
        login LIKE '%{$_POST['z']}%' OR
        acctid LIKE '%{$_POST['z']}%' OR
        name LIKE '%{$_POST['z']}%' OR
        emailaddress LIKE '%{$_POST['z']}%' OR
        lastip LIKE '%{$_POST['z']}%' OR
        uniqueid LIKE '%{$_POST['z']}%' OR
        gentimecount LIKE '%{$_POST['z']}%' OR
        level LIKE '%{$_POST['z']}%'";
        $result = db_query($sql.$where);
        if (db_num_rows($result)<=0){
            output("`\$No results found`0");
            $where="";
        }elseif (db_num_rows($result)>100){
            output("`\$Troppi risultati, restringi il campo di ricerca per favore.`0");
            $where="";
        }else{
            $sql = "SELECT acctid,login,name,level,lastip,uniqueid,emailaddress FROM accounts ".($where>""?"WHERE $where ":"");
            $result = db_query($sql) or die(db_error(LINK));
            output("<table>",true);
            output("<tr>
            <td>Acctid</td>
            <td>Login</td>
            <td>Nome</td>
            <td>Livello</td>
            <td>UltimoIP</td>
            <td>UltimoID</td>
            <td>Email</td>
            </tr>",true);
            $countrow = db_num_rows($result);
            for ($i=0; $i<$countrow; $i++){
            //for ($i=0;$i<db_num_rows($result);$i++){
                $row=db_fetch_assoc($result);
                output("<tr class='".($i%2?"trlight":"trdark")."'>",true);

                output("<td>",true);
                output($row[acctid]);
                output("</td><td>",true);
                output($row[login]);
                output("</td><td>",true);
                output($row[name]);
                output("</td><td>",true);
                output($row[level]);
                output("</td><td>",true);
                output($row[lastip]);
                output("</td><td>",true);
                output($row[uniqueid]);
                output("</td><td>",true);
                output($row[emailaddress]);
                output("</td>",true);
                output("</tr>",true);
            }
            output("</table>",true);
        }
    } elseif ($_POST['p']!="") {
        $sql = "SELECT accessi.*,a1.name as nome FROM accessi LEFT JOIN accounts AS a1 ON a1.acctid=accessi.acctid WHERE (accessi.acctid='{$_POST['p']}'";
        if ($_POST['ipaux']==1 AND $_POST['q']!="") {
            $sql.=" AND accessi.ip LIKE'%{$_POST['q']}%'";
        }
        elseif ($_POST['ipaux']==2 AND $_POST['q']!="") {
            $sql.=" OR accessi.ip LIKE'%{$_POST['q']}%'";
        }
        if ($_POST['idaux']==1 AND $_POST['r']!="") {
            $sql.=" AND accessi.id LIKE '%{$_POST['r']}%'";
        }
        elseif ($_POST['idaux']==2 AND $_POST['r']!="") {
            $sql.=" OR accessi.id LIKE '%{$_POST['r']}%'";
        }
        if ($_POST['data1']==1) {
            $data=$_POST['anno1']."-".$_POST['mese1']."-".$_POST['giorno1']." ".$_POST['ora1'].":".$_POST['minuti1'].":".$_POST['secondi1'];
            $sql.=" AND data > '".$data."'";
        }
        if ($_POST['data2']==1) {
            $data=$_POST['anno2']."-".$_POST['mese2']."-".$_POST['giorno2']." ".$_POST['ora2'].":".$_POST['minuti2'].":".$_POST['secondi2'];
            $sql.=" AND data < '".$data."'";
        }
        $sql.=") ORDER by accessi.data DESC LIMIT 500";
    } elseif ($_POST['q']!="") {
        $sql = "SELECT accessi.*,a1.name as nome FROM accessi LEFT JOIN accounts AS a1 ON a1.acctid=accessi.acctid WHERE (accessi.ip LIKE'%{$_POST['q']}%'";
        if ($_POST['idaux']==1 AND $_POST['r']!="") {
            $sql.=" AND accessi.id LIKE '%{$_POST['r']}%'";
        }
        elseif ($_POST['idaux']==2 AND $_POST['r']!="") {
            $sql.=" OR accessi.id LIKE '%{$_POST['r']}%'";
        }
        if ($_POST['data1']==1) {
            $data=$_POST['anno1']."-".$_POST['mese1']."-".$_POST['giorno1']." ".$_POST['ora1'].":".$_POST['minuti1'].":".$_POST['secondi1'];
            $sql.=" AND data > '".$data."'";
        }
        if ($_POST['data2']==1) {
            $data=$_POST['anno2']."-".$_POST['mese2']."-".$_POST['giorno2']." ".$_POST['ora2'].":".$_POST['minuti2'].":".$_POST['secondi2'];
            $sql.=" AND data < '".$data."'";
        }
        $sql.=") ORDER by accessi.data DESC LIMIT 500";
    } elseif ($_POST['r']!="") {
        $sql = "SELECT accessi.*,a1.name as nome FROM accessi LEFT JOIN accounts AS a1 ON a1.acctid=accessi.acctid WHERE (accessi.id LIKE '%{$_POST['r']}%'";
        if ($_POST['data1']==1) {
            $data=$_POST['anno1']."-".$_POST['mese1']."-".$_POST['giorno1']." ".$_POST['ora1'].":".$_POST['minuti1'].":".$_POST['secondi1'];
            $sql.=" AND data > '".$data."'";
        }
        if ($_POST['data2']==1) {
            $data=$_POST['anno2']."-".$_POST['mese2']."-".$_POST['giorno2']." ".$_POST['ora2'].":".$_POST['minuti2'].":".$_POST['secondi2'];
            $sql.=" AND data < '".$data."'";
        }
        $sql.=") ORDER by accessi.data DESC LIMIT 500";
    } elseif ($_POST['data1']=="1" OR $_POST['data2']=="1") {
        $sql = "SELECT accessi.*,a1.name as nome FROM accessi LEFT JOIN accounts AS a1 ON a1.acctid=accessi.acctid WHERE (";
        if ($_POST['data1']==1) {
            $data=$_POST['anno1']."-".$_POST['mese1']."-".$_POST['giorno1']." ".$_POST['ora1'].":".$_POST['minuti1'].":".$_POST['secondi1'];
            $sql.="data > '".$data."'";
        }
        if ($_POST['data2']==1) {
            $data=$_POST['anno2']."-".$_POST['mese2']."-".$_POST['giorno2']." ".$_POST['ora2'].":".$_POST['minuti2'].":".$_POST['secondi2'];
            if ($_POST['data1']==1) $sql.=" AND ";
            $sql.="data < '".$data."'";
        }
        $sql.=") ORDER by accessi.data DESC LIMIT 500";
    }

    output("Query eseguita:`n`&".$sql);
    output("`n`n`^Dati di connessione trovati:`n");
    $result = db_query($sql);
    output("<table cellspacing=0 cellpadding=2 align='center'>", true);
    output("<tr class='trhead'><td>`b`#Nome`b</td><td>`b`@Data e ora`b</td><td>`b`&IP`b</td><td>`b`^ID`b</td></tr>", true);
    if (db_num_rows($result) == 0) {
        output("<tr><td colspan=4 align='center'>`&Nessuna corrispondenza trovata`0</td></tr>", true);
    }else{
        $countrow = db_num_rows($result);
        for ($i=0; $i<$countrow; $i++){
        //for ($i=0; $i<db_num_rows($result); $i++) {
            $row = db_fetch_assoc($result);
            output("<tr class='" . ($i % 2?"trlight":"trdark") . "'><td>`#".$row['nome'].".</td><td>`@{$row['data']}</td><td>`&{$row['ip']}</td><td>`^{$row['id']}</td></tr>",true);
        }
    }
    output("</table>", true);
}
page_footer();
?>