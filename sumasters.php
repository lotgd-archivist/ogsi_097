<?php
/*
* sumasters.php
* Version:   15.08.2004
* Author:   bibir
* Email:   logd_bibir@email.de
*
* -----------------------
* Traduzione in Italiano
* Excalibur (www.ogsi.it)
* -----------------------
*
* Purpose: show and edit masters
*
#
# Tabellenstruktur für Tabelle `masters`
#

DROP TABLE IF EXISTS `masters`;
CREATE TABLE `masters` (
  `creaturename` varchar(50) default NULL,
  `creaturelevel` int(11) default NULL,
  `creatureweapon` varchar(50) default NULL,
  `creaturelose` varchar(180) default NULL,
  `creaturewin` varchar(180) default NULL,
  `creaturehealth` int(11) default NULL,
  `creatureattack` int(11) default NULL,
  `creaturedefense` int(11) default NULL,
  PRIMARY KEY  (`creaturelevel`)
) TYPE=MyISAM AUTO_INCREMENT=15 ;
*/

require_once "common.php";
isnewday(2);

page_header("Editor Maestri");

addnav("M?Torna alla Mondanità","village.php");
addnav("G?Torna alla Grotta","superuser.php");

output('`b`cEditor Maestri`c`b`n`n');

if($_GET['op']=="edit"){
    addnav("Lista Maestri","sumasters.php");
     if($_GET['subop']=="save"){
        // fehler abfangen
        if($_POST['creaturename']=="" || $_POST['creatureweapon']==""|| $_POST['creaturelose']==""|| $_POST['creaturewin']==""){
            output("`n`4I dati forniti non sono accettabili (uno o più campi sono vuoti).");
        } else if($_POST['creaturehealth']<=0 || $_POST['creatureattack']<=0 || $_POST['creaturedefense']<=0){
            output("`n`4Valori forniti per HitPoints, Attacco e/o Difesa errati (minori o uguali a zero).");
        } else {
            output("`^Valori Maestro aggiornati.`0`n`n");
            $sql = "UPDATE masters SET creaturename='".$_POST['creaturename']."',
                                       creatureweapon='".$_POST['creatureweapon']."',
                                       creaturehealth='".$_POST['creaturehealth']."',
                                       creatureattack='".$_POST['creatureattack']."',
                                       creaturedefense='".$_POST['creaturedefense']."',
                                       creaturelose='".$_POST['creaturelose']."',
                                       creaturewin='".$_POST['creaturewin']."'
                    WHERE creaturelevel=".$_GET['level'];
            db_query($sql);
        }
    }

   output("Edita questo Maestro:");
   output('`nLe lettere Maiuscole (%X,%W) indicano i valori del Vincitore (Nome, arma), quelle minuscole (%x, %w) quelli del Perdente.');
   $sql = "SELECT * FROM masters WHERE creaturelevel=".$_GET['level'];
   $result = db_query($sql) or die(db_error(LINK));
   $row = db_fetch_assoc($result);
   output("`0<form action=\"sumasters.php?op=edit&subop=save&level=".$_GET['level']."\" method='POST'>",true);
   output("<table><tr><td>Level</td><td>".$row['creaturelevel']."</td></tr>",true);
   output("<tr><td>Nome</td><td><input type='text' name='creaturename' maxlength='50' value='".HTMLEntities2($row['creaturename'],ENT_QUOTES)."'></td></tr>",true);
   output("<tr><td>Arma</td><td><input type='text' name='creatureweapon' maxlength='50' value='".HTMLEntities2($row['creatureweapon'],ENT_QUOTES)."'></td></tr>",true);
   output("<tr><td>HitPoints</td><td><input type='text' name='creaturehealth'  value='".$row['creaturehealth']."'></td></tr>",true);
   output("<tr><td>Attacco</td><td><input type='text' name='creatureattack'  value='".$row['creatureattack']."'></td></tr>",true);
   output("<tr><td>Difesa</td><td><input type='text' name='creaturedefense'  value='".$row['creaturedefense']."'></td></tr>",true);
   output("<tr><td>Messaggio Sconfitta</td><td><input type='text' name='creaturelose' size='80' maxlength='180' value='".HTMLEntities2($row['creaturelose'],ENT_QUOTES)."'></td></tr>",true);
   output("<tr><td>Messaggio Vittoria</td><td><input type='text' name='creaturewin' size='80' maxlength='180' value='".HTMLEntities2($row['creaturewin'],ENT_QUOTES)."'></td></tr>",true);
   output('</table>',true);
   output("<input type='submit' class='button' value='Salva'></form>",true);
   addnav("","sumasters.php?op=edit&subop=save&level=".$_GET['level']);
   output("Sono supportati i seguenti codici (presta attenzione a maiusc/minusc):`n");
   output("%w = Nome del Perdente`n");
   output("%x = Arma del Perdente`n");
   output("%s = Soggettivo del Perdente (lui lei)`n");
   output("%p = Possessivo Perdente (suo sua)`n");
   output("%o = Oggettivo del Perdente (egli ella)`n");
   output("%W = Nome del Vincitore`n");
   output("%X = Arma del Vincitore`n");
} else {
   addnav("Aggiorna","sumasters.php");
   $sql = "SELECT * FROM masters ORDER BY creaturelevel ASC";
   $result = db_query($sql);
   output("<table border=0 cellpadding=2 cellspacing=1 bgcolor='#999999' align=center>",true);
   output("<tr class='trlight'><td>`3`bEDITA`b</td><td>`bLIVELLO`b</td><td>`&`bNOME MAESTRO`b</td><td>`#`bARMA`b</td><td>`\$`bHITPOINTS`b</td><td>`%`bATTACCO`b</td><td>`^`bDIFESA`b</td></tr>",true);
   output("<tr class='trdark'><td></td><td colspan=\"3\">`(`bMESSAGGIO SCONFITTA MAESTRO`b</td><td colspan=\"3\">`@`bMESSAGGIO VITTORIA MAESTRO`b</td></tr>",true);
   while($row = db_fetch_assoc($result)){
      output("<tr></tr>",true);
      output("<tr class='trlight'><td>[<a href='sumasters.php?op=edit&level=".$row['creaturelevel']."'>Edit</a>]</td>",true);
      addnav("","sumasters.php?op=edit&level=".$row['creaturelevel']);
      output("<td>`b`&".$row['creaturelevel']."`b</td>",true);
      output("<td>".$row['creaturename']."</td>",true);
      output("<td>`#".$row['creatureweapon']."</td>",true);
      output("<td>`\$".$row['creaturehealth']."</td>",true);
      output("<td>`%".$row['creatureattack']."</td>",true);
      output("<td>`^".$row['creaturedefense']."</td>",true);
      output("</tr><tr class='trdark'><td></td>",true);
      output("<td colspan=\"3\">`(".$row['creaturelose']."</td>",true);
      output("<td colspan=\"3\">`@".$row['creaturewin']."</td>",true);
      output("</tr>",true);
   }
   output("</table>",true);

}

page_footer();
?>