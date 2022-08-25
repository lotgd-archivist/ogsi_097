<?php
require_once "common.php";
require_once("common2.php");
isnewday(2);

page_header("Il Limbo");
addnav("G?Torna alla Grotta","superuser.php");
addnav("Limbo");
if ($_GET['id2']!="1"){
    addnav("Mostra Utenti Approvati","limbo.php?id2=1");
}

$session['user']['locazione'] = 204;

$sql = "SELECT DISTINCT section FROM commentary WHERE section LIKE 'limbo-%'";
$result = db_query($sql);
if ($_GET['id']==""){
    $countrow = db_num_rows($result);
    for ($i=0; $i<$countrow; $i++){
    //for ($i=0;$i<db_num_rows($result);$i++){
        $row = db_fetch_assoc($result);
        $idplayer=substr($row['section'],6);
        if ($_GET['id2']!="1"){
            $sql2 = "SELECT name FROM accounts WHERE acctid = '".$idplayer."' AND consono = '0'";
        } else {
            $sql2 = "SELECT name FROM accounts WHERE acctid = '".$idplayer."'";
        }
        $result2 = db_query($sql2);
        $countrow2 = db_num_rows($result2);
        if ($countrow2==1) {
            $row2 = db_fetch_assoc($result2);
            addnav($row2['name'],"limbo.php?id=".$idplayer);
        }
    }
    output("Da qui puoi accedere al limbo in cui si trovano i personaggi erranti in attesa di approvazione, per discutere con loro ed affrontare le eventuali problematiche che impediscono il completamento delle pratiche di registrazione.`n");
    addnav("");
    addnav("A?Aggiorna Limbo","limbo.php");
}else{
    $stanza = "limbo-".$_GET['id'];
    $sql2 = "SELECT name FROM accounts WHERE acctid = '".$_GET['id']."'";
    $result2 = db_query($sql2);
    $row2 = db_fetch_assoc($result2);
    output("`&<big><b>Limbo di ".$row2['name'].".`^</b></big>`n`n",true);
    output($row2['name']." ha invocato lo staff. Vuoi manifestare la tua essenza e conversare con lui?`n`n");
    if ($row2['consono'] != 0) output("\$ATTENZIONE: Questo personaggio non è più in attesa di approvazione, pertanto non potrà più rispondere in questa stanza.`n`n");
    addnav("");
    addnav ("Torna indietro","limbo.php");
    addcommentary();
    viewcommentary($stanza,"Manifestati:",30,"","dice","");
}
page_footer();
?>