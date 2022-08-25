<?php
require_once "common.php";
require_once("common2.php");

reset($_POST);
if (is_array($_POST['mount'])) reset($_POST['mount']);
$session['user']['locazione'] = 203;

page_header("Le stanze GDR");

addnav("P?Torna alla Piazza","village.php");
if ($session['user']['superuser']>=2) addnav("Crea una stanza","stanzegdr.php?op=add");
addnav("");

$sql = "SELECT nome,nomeid FROM stanze WHERE tipo='gdr'";
$result = db_query($sql);
if ($_GET['op']==""){
    $countrow = db_num_rows($result);
    for ($i=0; $i<$countrow; $i++){
    //for ($i=0;$i<db_num_rows($result);$i++){
        $row = db_fetch_assoc($result);
        $nome=stripslashes($row[nome]);
        addnav($nome,"stanzegdr.php?op=".$row[nomeid]);
    }
    output("Da qui puoi accedere ad alcune aree esclusivamente adibite al gioco di ruolo.`n");
    output("Si chiede ai giocatori non interessati a prenderne parte, di andare a conversare altrove e non interrompere i racconti che qui vengono costruiti.`n");
}elseif ($_GET['op']=="add"){
    addnav("Torna indietro","stanzegdr.php");
    output("Da qui puoi creare una nuova stanza.`n`n");
    creastanza(array());
}elseif ($_GET['op']=="edit"){
    addnav("Torna Indietro","stanzegdr.php");
    addnav("`\$Cancella questa stanza","stanzegdr.php?op=del&nomeid=".$_GET['nomeid']);
    $sql = "SELECT * FROM stanze WHERE nomeid='{$_GET['nomeid']}'";
    $result = db_query($sql);
    if (db_num_rows($result)<=0){
        output("`iStanza non trovata.`i");
    }else{
        $row = db_fetch_assoc($result);
        output("Modifica Stanza ".$row['nome'].":`n");
        creastanza($row);
    }
}elseif ($_GET['op']=="del"){
    addnav("Torna Indietro","stanzegdr.php");
    addnav("`\$Cancella questa stanza","stanzegdr.php?op=delsicuro&nomeid=".$_GET['nomeid']);
    output("`c`\$ATTENZIONE!`nVuoi veramente cancellare questa stanza?`c");
}elseif ($_GET['op']=="delsicuro"){
    addnav("Torna Indietro","stanzegdr.php");
    $sql = "DELETE FROM stanze WHERE nomeid='{$_GET['nomeid']}'";
    $result = db_query($sql);
    if (db_affected_rows()<=0){
        output("`iStanza non trovata.`i");
    }else{
        output("Stanza cancellata!");
    }
}elseif ($_GET['op']=="save"){
    $keys='';
    $vals='';
    $sql='';
    $i=0;
    while (list($key,$val)=each($_POST['mount'])){
        if (is_array($val)) $val = addslashes(serialize($val));
        if ($_GET['temp']!=""){
            $sql.=($i>0?",":"")."$key='$val'";
        }else{
            $keys.=($i>0?",":"")."$key";
            $vals.=($i>0?",":"")."'$val'";
        }
        $i++;
    }
    $sql2 = "SELECT * FROM stanze WHERE nomeid='{$_GET['temp']}' AND tipo='gdr'";
    $result2 = db_query($sql2);
    if (db_num_rows($result2)>0){
        $sql="UPDATE stanze SET $sql WHERE nomeid='{$_GET['temp']}'";
    }else{
        $sql="INSERT INTO stanze ($keys) VALUES ($vals)";
    }
    db_query($sql);
    if (db_affected_rows()>0){
        output("Stanza salvata!");
    }else{
        output("Stanza non salvata: $sql");
    }
    addnav("Torna Indietro","stanzegdr.php");
}else{
    $sql = "SELECT * FROM stanze WHERE nomeid='".$_GET[op]."'";
    $result = db_query($sql) or die(db_error(LINK));
    $countrow = db_num_rows($result);
    for ($i=0; $i<$countrow; $i++){
    //for ($i=0;$i<db_num_rows($result);$i++){
        $row = db_fetch_assoc($result);
        $descrizione=stripslashes($row[descrizione]);
        output($descrizione);
    }
    output("`n`n`n");
    if ($session['user']['superuser']>=2) addnav("Modifica questa stanza","stanzegdr.php?op=edit&nomeid=".$row[nomeid]);
    addnav ("Torna indietro","stanzegdr.php");
    if($row[chiusa]=="1") $stanzachiusa=1;
    addcommentary();
    viewcommentary($_GET['op'],"parla con gli altri",$row['commenti_pagina'],$row['commenti_pg'],$row['azione']);
}
function creastanza($mount){
    global $output;
    output("<form action='stanzegdr.php?op=save&temp={$mount['nomeid']}' method='POST'>",true);
    addnav("","stanzegdr.php?op=save&temp={$mount['nomeid']}");
    $output.="<table>";
    $output.="<tr><td>Nome della stanza:</td><td><input name='mount[nome]' value=\"".HTMLEntities2($mount[nome])."\"></td></tr>";
    $output.="<tr><td>Nome identificativo (senza spazi e caratteri speciali, è necessario per la moderazione. Non toccare in editing):</td><td><input name='mount[nomeid]' value=\"".HTMLEntities2($mount[nomeid])."\"></td></tr>";
    $output.="<tr><td>Descrizione (con caratteri speciali):</td><td><input name='mount[descrizione]' value=\"".HTMLEntities2($mount[descrizione])."\"></td></tr>";
    $output.="<tr><td>Azione per comunicare (default: dice):</td><td><input name='mount[azione]' value=\"".HTMLEntities2($mount[azione])."\"></td></tr>";
    $output.="<tr><td>Locazione della stanza (scrivere gdr, non toccare in editing):</td><td><input name='mount[tipo]' value=\"".HTMLEntities2($mount[tipo])."\"></td></tr>";
    $output.="<tr><td>Stato (0:aperta, 1:chiusa):</td><td><input name='mount[chiusa]' value=\"".HTMLEntities2($mount[chiusa])."\"</td></tr>";
    $output.="<tr><td>Numero di commenti visualizzati per pagina (default: 20):</td><td><input name='mount[commenti_pagina]' value=\"".HTMLEntities2($mount[commenti_pagina])."\"</td></tr>";
    $output.="<tr><td>Numero di commenti che possono essere fatti per personaggio (default: 10. Nel server 2 non c'è limite):</td><td><input name='mount[commenti_pg]' value=\"".HTMLEntities2($mount[commenti_pg])."\"</td></tr>";
    $output.="</table>";
    $output.="<input type='submit' class='button' value='Salva'></form>";
}

page_footer();
?>