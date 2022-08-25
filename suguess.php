<?php
require_once "common.php";
isnewday(4);
page_header("Gestione Indovina Frase");
if ($_GET['op'] == "") {
   output("`3Opzioni per Admin`n");
   output("`3Stato indovinello: ".getsetting("indovinello","non settato"));
   addnav("Blocca visualizzazione","suguess.php?op=blocca");
   addnav("Sblocca visualizzazione","suguess.php?op=sblocca");
   addnav("Chiudi Concorso","suguess.php?op=chiudi");
   addnav("Inserisci Frase Sgrios","suguess.php?op=issgrios");
   addnav("Inserisci Frase Karnak","suguess.php?op=iskarnak");
   addnav("Inserisci Frase Drago Verde","suguess.php?op=isdrago");
   addnav("Vedi stato Sgrios","suguess.php?op=statussgrios");
   addnav("Vedi stato Karnak","suguess.php?op=statuskarnak");
   addnav("Vedi stato Drago Verde","suguess.php?op=statusdrago");
   addnav("Decidi premio","suguess.php?op=premio");
   addnav("Torna alla Grotta","superuser.php");
} elseif ($_GET['op'] == "blocca") {
   output("`@Sei sicuro di voler bloccare la visualizzazione della frase ?`n");
   output("Quest'opzione serve ad impedire ai player di vedere la frase mentre stai aggiornando la frase.`n");
   addnav("Si, procedi","suguess.php?op=bloccasi");
   addnav("No, torna al menù","suguess.php");
} elseif ($_GET['op'] == "bloccasi") {
   savesetting("indovinello", "bloccato");
   output("`@Bloccata visualizzazione frase!`n");
   addnav("Torna al menù","suguess.php");
} elseif ($_GET['op'] == "sblocca") {
   output("`@Sei sicuro di voler sbloccare la visualizzazione della frase ?`n");
   output("Quest'opzione serve a consentire ai player di vedere la frase dopo aver aggiornato la frase.`n");
   addnav("Si, procedi","suguess.php?op=sbloccasi");
   addnav("No, torna al menù","suguess.php");
} elseif ($_GET['op'] == "sbloccasi") {
   savesetting("indovinello", "sbloccato");
   output("`@Sbloccata visualizzazione frase!`n");
   savesetting("settaindovinello","");
   savesetting("solutoreindovinello","");
   addnav("Torna al menù","suguess.php");
} elseif ($_GET['op'] == "chiudi") {
   output("`@Sei sicuro di voler chiudere il concorso ?`n");
   output("Quest'opzione, oltre a chiudere il concorso (senza assegnare premi), visualizza le frasi complete.`n");
   addnav("Si, procedi","suguess.php?op=chiudisi");
   addnav("No, torna al menù","suguess.php");
} elseif ($_GET['op'] == "chiudisi") {
   savesetting("indovinello", "chiuso");
   savesetting("settaindovinello","nessuna");
   output("`@Concorso chiuso.`n");
   addnav("Torna al menù","suguess.php");
} elseif ($_GET['op'] == "issgrios" AND getsetting("indovinello", "") == "bloccato") {
   output("`@Scrivi frase per chiesa Sgrios.`n");
   output("<form action='suguess.php?op=issgrios1' method='POST'><input name='frase' value='0'><input type='submit' class='button' value='Frase Sgrios'>`n",true);
   addnav("","suguess.php?op=issgrios1");
} elseif ($_GET['op'] == "issgrios" AND getsetting("indovinello", "") != "bloccato" ) {
   output("`@Prima di inserire o modificare la frase, devi bloccare il concorso !!`n");
   addnav("Torna al menù","suguess.php");
} elseif ($_GET['op'] == "issgrios1") {
   savesetting("tentasgrios","0");
   $frase = $_POST['frase'];
   print $frase;
   $sql = "UPDATE custom SET area='".$frase."' WHERE area1 = 'frasesgrios'";
   $result = db_query ($sql) or die(db_error(LINK));
   if (db_affected_rows()==0){
      $sql = "INSERT INTO custom (area,area1,dDate) VALUES('".$frase."','frasesgrios',now())";
      $result = db_query ($sql) or die(db_error(LINK));
   }
   //$lunghezza = strlen(stripslashes($frase));
   $frase1 = str_pad("", strlen(stripslashes($frase)), '-');
   $sql = "UPDATE custom SET area='".$frase1."', dDate=now() WHERE area1 = 'frasesgriosnascosta'";
   $result = db_query ($sql) or die(db_error(LINK));
   if (db_affected_rows()==0){
      $sql = "INSERT INTO custom (area,area1,dDate) VALUES('".$frase1."','frasesgriosnascosta',now())";
      $result = db_query ($sql) or die(db_error(LINK));
   }
   addnav("Torna al menù","suguess.php");
   output("`@Frase segreta per Sgrios inserita.`n");
} elseif ($_GET['op'] == "iskarnak"  AND getsetting("indovinello", "") == "bloccato") {
   output("`@Scrivi frase per chiesa Karnak.`n");
   output("<form action='suguess.php?op=iskarnak1' method='POST'><input name='frase' value='0'><input type='submit' class='button' value='Frase Karnak'>`n",true);
   addnav("","suguess.php?op=iskarnak1");
} elseif ($_GET['op'] == "iskarnak" AND getsetting("indovinello", "") != "bloccato" ) {
   output("`@Prima di inserire o modificare la frase, devi bloccare il concorso !!`n");
   addnav("Torna al menù","suguess.php");
} elseif ($_GET['op'] == "iskarnak1") {
   savesetting("tentakarnak","0");
   $frase = $_POST['frase'];
   print $frase;
   $sql = "UPDATE custom SET area='".$frase."' WHERE area1 = 'frasekarnak'";
   $result = db_query ($sql) or die(db_error(LINK));
   if (db_affected_rows()==0){
      $sql = "INSERT INTO custom (area,area1,dDate) VALUES('".$frase."','frasekarnak',now())";
      $result = db_query ($sql) or die(db_error(LINK));
   }
   $lunghezza = strlen($frase);
   $frase1 = str_pad("", strlen(stripslashes($frase)), '-');
   $sql = "UPDATE custom SET area='".$frase1."', dDate=now() WHERE area1 = 'frasekarnaknascosta'";
   $result = db_query ($sql) or die(db_error(LINK));
   if (db_affected_rows()==0){
      $sql = "INSERT INTO custom (area,area1,dDate) VALUES('".$frase1."','frasekarnaknascosta',now())";
      $result = db_query ($sql) or die(db_error(LINK));
   }
   addnav("Torna al menù","suguess.php");
   output("`@Frase segreta per Karnak inserita.`n");
} elseif ($_GET['op'] == "isdrago"  AND getsetting("indovinello", "") == "bloccato") {
   output("`@Scrivi frase per chiesa Drago Verde.`n");
   output("<form action='suguess.php?op=isdrago1' method='POST'><input name='frase' value='0'><input type='submit' class='button' value='Frase Drago'>`n",true);
   addnav("","suguess.php?op=isdrago1");
} elseif ($_GET['op'] == "isdrago" AND getsetting("indovinello", "") != "bloccato" ) {
   output("`@Prima di inserire o modificare la frase, devi bloccare il concorso !!`n");
   addnav("Torna al menù","suguess.php");
} elseif ($_GET['op'] == "isdrago1") {
   savesetting("tentadrago","0");
   $frase = $_POST['frase'];
   print $frase;
   $sql = "UPDATE custom SET area='".$frase."' WHERE area1 = 'frasedrago'";
   $result = db_query ($sql) or die(db_error(LINK));
   if (db_affected_rows()==0){
      $sql = "INSERT INTO custom (area,area1,dDate) VALUES('".$frase."','frasedrago',now())";
      $result = db_query ($sql) or die(db_error(LINK));
   }
   //$lunghezza = strlen($frase);
   $frase1 = str_pad("", strlen(stripslashes($frase)), '-');
   $sql = "UPDATE custom SET area='".$frase1."', dDate=now() WHERE area1 = 'frasedragonascosta'";
   $result = db_query ($sql) or die(db_error(LINK));
   if (db_affected_rows()==0){
      $sql = "INSERT INTO custom (area,area1,dDate) VALUES('".$frase1."','frasedragonascosta',now())";
      $result = db_query ($sql) or die(db_error(LINK));
   }
   addnav("Torna al menù","suguess.php");
   output("`@Frase segreta per Drago Verde inserita.`n");
} elseif ($_GET['op'] == "statussgrios") {
   addnav("Torna al menù","suguess.php");
   $sql = "SELECT area FROM custom WHERE area1 = 'frasesgrios'";
   $result = db_query ($sql) or die(db_error(LINK));
   $row = db_fetch_assoc($result);
   $frase = stripslashes($row['area']);
   $sql = "SELECT area FROM custom WHERE area1 = 'frasesgriosnascosta'";
   $result = db_query ($sql) or die(db_error(LINK));
   $row = db_fetch_assoc($result);
   $frase1 = stripslashes($row['area']);
   output("`@`cSituazione attuale Frase Sgrios`c`n`n");
   output("<tt><font size='+1'>Frase originale: ".$frase."`n",true);
   output("Frase nascosta : ".$frase1."`n</tt></font>",true);
} elseif ($_GET['op'] == "statuskarnak") {
   addnav("Torna al menù","suguess.php");
   $sql = "SELECT area FROM custom WHERE area1 = 'frasekarnak'";
   $result = db_query ($sql) or die(db_error(LINK));
   $row = db_fetch_assoc($result);
   $frase = stripslashes($row['area']);
   $sql = "SELECT area FROM custom WHERE area1 = 'frasekarnaknascosta'";
   $result = db_query ($sql) or die(db_error(LINK));
   $row = db_fetch_assoc($result);
   $frase1 = stripslashes($row['area']);
   output("`@`cSituazione attuale Frase Karnak`c`n`n");
   output("<tt><font size='+1'>Frase originale: ".$frase."`n",true);
   output("Frase nascosta : ".$frase1."`n</tt></font>",true);
} elseif ($_GET['op'] == "statusdrago") {
   addnav("Torna al menù","suguess.php");
   $sql = "SELECT area FROM custom WHERE area1 = 'frasedrago'";
   $result = db_query ($sql) or die(db_error(LINK));
   $row = db_fetch_assoc($result);
   $frase = stripslashes($row['area']);
   $sql = "SELECT area FROM custom WHERE area1 = 'frasedragonascosta'";
   $result = db_query ($sql) or die(db_error(LINK));
   $row = db_fetch_assoc($result);
   $frase1 = stripslashes($row['area']);
   output("`@`cSituazione attuale Frase Drago Verde`c`n`n");
   output("<tt><font size='+1'>Frase originale: ".$frase."`n",true);
   output("Frase nascosta : ".$frase1."`n</tt></font>",true);
} elseif ($_GET['op'] == "premio") {
   $premio = getsetting("premioconcorso",5);
   output("`@Inserisci numero gemme in premio per vincitori setta (chi indovina * 2).`n");
   output("`#Premio attuale: `&$premio Gemme`#.");
   output("<form action='suguess.php?op=premio1' method='POST'><input name='premio' value='0'><input type='submit' class='button' value='Numero Gemme'>`n",true);
   addnav("","suguess.php?op=premio1");
} elseif ($_GET['op'] == "premio1") {
   $premio = $_POST['premio'];
   savesetting("premioconcorso",$premio);
   output("`#Premio concorso `^Indovina la Frase`# settato a `&$premio Gemme`#.`n");
   addnav("Torna al menù","suguess.php");
}
page_footer();
?>