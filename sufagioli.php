<?php
require_once "common.php";
isnewday(4);
page_header("Gestione Concorso Fagioli");
if ($_GET['op'] == "") {
   output("`3Opzioni per Admin`n");
   output("`3Stato concorso: ".getsetting("statofagioli","chiuso"));
   addnav("Sblocca Concorso","sufagioli.php?op=sblocca");
   addnav("Torna alla Grotta","superuser.php");
} elseif ($_GET['op'] == "sblocca") {
   output("`@Sei sicuro di voler aprire il Concorso dei Fagioli ?`n");
   addnav("Si, procedi","sufagioli.php?op=sbloccasi");
   addnav("No, torna al menù","sufagioli.php");
} elseif ($_GET['op'] == "sbloccasi") {
   savesetting("statofagioli", "aperto");
   savesetting("numerofagioli", "0");
   savesetting("playerfagioli", "0");
   $sql = "DELETE FROM `fagioli`";
   $result = db_query ($sql) or die(db_error(LINK));
   output("`@Sbloccato Concerso Fagioli!`n");
   addnav("Torna al menù","sufagioli.php");
}page_footer();
?>