<?php
require_once "common.php";
isnewday(3);

page_header("Settaggio Ora");
addnav("G?Torna alla Grotta","superuser.php");
addnav("S?Torna al Pannello Settaggi Gioco","configuration.php");
addnav("M?Torna alla Mondanità","village.php");

if ($_GET[op]==""){
    output("Da qui puoi modificare l'ora del newday impostando una variazione del valore di offset del gioco.`n`n");
	output("<form action='oraset.php?op=save' method='POST'>",true);
    addnav("","oraset.php?op=save");
	output("Sposta l'offset del newday di <input name='offset' size='5'> secondi`n",true);
    output("<input type='radio' name='modo' value=1>In anticipo",true);
    output("<input type='radio' name='modo' value=2>In ritardo",true);
    output("`n`n<input type='submit' class='button' value='Sposta'></form>",true);
    output("</form>",true);
}

if ($_GET[op]=="save"){
    if ($_POST['modo']==1){
    	$nuovo_offset=getsetting("gameoffsetseconds",0) - $_POST['offset'];
    	while ($nuovo_offset<0) {
    		$nuovo_offset+=86400;
    	}
    	savesetting("gameoffsetseconds",$nuovo_offset);
		if ($nuovo_offset==0) savesetting("gameoffsetseconds","0");
    }
    if ($_POST['modo']==2){
    	$nuovo_offset=getsetting("gameoffsetseconds",0) + $_POST['offset'];
    	while ($nuovo_offset>=86400) {
    		$nuovo_offset-=86400;
    	}
    	savesetting("gameoffsetseconds",$nuovo_offset);
		if ($nuovo_offset==0) savesetting("gameoffsetseconds","0");
    }
    if (!$_POST['modo']){
    	output("Errore nell'inserimento dell'offset (non selezionato se in anticipo o in ritardo)");
    }
    output("`n`nL'orario del nuovo giorno è stato spostato.");
    addnav("Aggiusta ancora","oraset.php");
}

output("`n`n`nAttuale offset per newday: ".getsetting("gameoffsetseconds",0));

page_footer();
?>