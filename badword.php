<?php
require_once "common.php";

isnewday(2);

page_header("Editor Parolacce");
addnav("G?Tornal alla Grotta","superuser.php");
addnav("M?Torna alla Mondanità","village.php");
addnav("Aggiorna la Lista","badword.php");
output("Qui puoi editare la parole che verranno filtrate. Se usi * all'inizio o alla fine di una parola sarà come un ");
output("jolly che corrisponderà a qualsiasi carattere.  Queste parole sono filtrate solamente se il filtro è abilitato ");
output("nella pagina dei settaggi del gioco.");
//output("<form action='badword.php?op=add' method='POST'>Add a word: <input name='word'><input type='submit' value='Add'></form>",true);
//output("<form action='badword.php?op=remove' method='POST'>Remove a word: <input name='word'><input type='submit' value='Remove'></form>",true);
//output("<form action='badword.php?op=test' method='POST'>Test a word: <input name='word'><input type='submit' value='Test'></form>",true);
output("<form action='badword.php?op=add' method='POST'>Aggiungi una parola:<input name='word'><input type='submit' class='button' value='Aggiungi'></form>",true);
output("<form action='badword.php?op=remove' method='POST'>Togli una parola: <input name='word'><input type='submit' class='button' value='Togli'></form>",true);
output("<form action='badword.php?op=test' method='POST'>Prova una parola: <input name='word'><input type='submit' class='button' value='Prova'></form>",true);

addnav("","badword.php?op=add");
addnav("","badword.php?op=remove");
addnav("","badword.php?op=test");
$sql = "SELECT * FROM nastywords";
$result = db_query($sql);
$row = db_fetch_assoc($result);
$words = split(" ",$row['words']);
reset($words);

if ($_GET['op']=="add"){
    array_push($words,stripslashes($_POST['word']));
}
if ($_GET['op']=="remove"){
    unset($words[array_search(stripslashes($_POST['word']),$words)]);
}
if ($_GET['op']=="test"){
    output("`7Il risultato della tua prova è `^".soap($_POST['word'])."`7.  (Se non hai abilitato il filtro, questo test non funzionerà).`n`n");
}
sort($words);
$lastletter="";
while (list($key,$val)=each($words)){
    if (trim($val)==""){
        unset($words[$key]);
    }else{
        if (substr($val,0,1)!=$lastletter){
            $lastletter = substr($val,0,1);
            output("`n`n`^`b" . strtoupper($lastletter) . "`b`@`n");
        }
        output($val." ");
    }
}
if ($_GET['op']=="add" || $_GET['op']=="remove"){
    $sql = "DELETE FROM nastywords";
    db_query($sql);
    $sql = "INSERT INTO nastywords VALUES ('" . addslashes(join(" ",$words)) . "')";
    db_query($sql);
}
page_footer();
?>
