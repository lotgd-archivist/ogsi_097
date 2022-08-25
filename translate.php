<?php
require_once "common.php";
isnewday(4);

page_header("Editor Lingue");
addnav("G?Torna alla Grotta","superuser.php");
addnav("M?Torna alla Mondanità","village.php");
addnav("R?Traduttore","translate.php");
addnav("Lingue");
addnav("T?Tedesco","translate.php?op=de");
addnav("I?Inglese","translate.php?op=en");

if(!$_GET['op']){
	if($_GET['az']=='aggiorna_impostazioni'){
		savesetting("defaultlanguage", $_POST['defaultlanguage']);
		savesetting("raccogli_testo", $_POST['raccogli_testo']);
	}
	addnav("","translate.php?az=aggiorna_impostazioni");
	output("<form method=\"post\" action=\"translate.php?az=aggiorna_impostazioni\">",true);
	output("Lingua default :");
	output("<select name='defaultlanguage'>",true);
	$lingue_=array('it','en','de');
	foreach($lingue_ AS $lingua_){
		if($lingua_==getsetting("defaultlanguage","it")){
			output("<option value=\"".$lingua_."\" selected>",true);
			output($lingua_,true);
		}else{
			output("<option value=\"".$lingua_."\">",true);
			output($lingua_,true);
		}
	}
	output("</select>`n",true);
	output("Raccogli testo :");
	output("<select name='raccogli_testo'>",true);
	$racc_=array('on','off');
	foreach($racc_ AS $rac_){
		if($rac_==getsetting("raccogli_testo","off")){
			output("<option value=\"".$rac_."\" selected>",true);
			output($rac_,true);
		}else{
			output("<option value=\"".$rac_."\">",true);
			output($rac_,true);
		}
	}
	output("</select>`n",true);
	output("<input type='submit' name='s1' value='Modifica'>`n</form>",true);
}else{
	$op_language=$_GET['op'];
	if(!$_GET['pg']){
		if($_GET['az']=='save'){
			$no_translate=serialize($_POST);
			savesetting("no_translate", $no_translate);
			$no_translate=$_POST;
		}
		if($session['user']['superuser']>3){
			output("<form name=\"no_translate\" action='translate.php?op=$op_language&az=save' method='POST'>",true);
			addnav("","translate.php?op=$op_language&az=save");
		}
		output("<table cellspacing=0 cellpadding=2 align='center'>", true);
		output("<tr><td>`0`b",true);
		output("Traduci");
		output("`b</td><td>`b",true);
		output("Pagina");
		output("`b</td><td>`b",true);
		output("Frasi tot.");
		output("`b</td><td>`b",true);
		output("No translate");
		if($session['user']['superuser']>3){
			output("`b</td><td>`b",true);
			output("Frasi tradotte");
		}
		output("`b</td></tr>`b",true);
		$sql = "SELECT COUNT(it) AS parole,COUNT($op_language) AS tradotte, page FROM text GROUP BY page ORDER BY page";
		$result = db_query($sql) or die(db_error(LINK));
		$countrow = db_num_rows($result);
		for ($i=0; $i<$countrow; $i++){
			$row = db_fetch_assoc($result);
			addnav("","translate.php?op=$op_language&pg=$row[page]");
			output("<tr class='" . ($i % 2?"trlight":"trdark") . "'><td>",true);
			if(in_array($row['page'],$no_translate)==false){
				output("<a href=\"translate.php?op=$op_language&pg=$row[page]\">",true);
				output("Vai");
				output("</a>",true);
			}
			output("</td><td>",true);
			output($row['page'],true);
			output("</td><td>",true);
			output($row['parole'],true);
			output("</td><td>",true);
			output($row['tradotte'],true);
			if($session['user']['superuser']>3){
				output("</td><td>",true);
				if(in_array($row['page'],$no_translate)==false){
					output("<input type=checkbox name=".$row['page']." value=".$row['page'].">",true);
				}else{
					output("<input type=checkbox name=".$row['page']." value=".$row['page']." checked>",true);
				}
			}
			output("</td></tr>",true);

		}
		output("</table>`n`n`n",true);
		if($session['user']['superuser']>3){
			output("`c<input type=\"submit\" value=\"Registra\">`c",true);
			output("</form>",true);
		}
	}else{
		if($_GET['az']=='save'){
			$sql = "UPDATE text SET $op_language='".addslashes($_POST['text'])."' WHERE id='".$_GET['id']."'";
			db_query($sql) or die(db_error(LINK));
			output("`c`\$FRASE SALVATA`c`n");
		}
		output("<table cellspacing=0 cellpadding=2 align='center'>", true);
		output("<tr><td>`0`b",true);
		output("Frase it");
		output("`b</td><td>`b",true);
		output("Frase $op_language");
		output("`b</td><td>`b",true);
		output("Registra");
		output("`b</td></tr>`b",true);

		$sql = "SELECT id,$op_language,it FROM text WHERE page='".$_GET['pg']."' ORDER BY ".$_GET['op'];
		$result = db_query($sql) or die(db_error(LINK));
		$countrow = db_num_rows($result);
		for ($i=0; $i<$countrow; $i++){
			$row = db_fetch_assoc($result);
			output("<form name=\"$i\" action='translate.php?op=$op_language&pg=$_GET[pg]&az=save&id=$row[id]' method='POST'>",true);
			addnav("","translate.php?op=$op_language&pg=$_GET[pg]&az=save&id=$row[id]");
			output("<tr class='" . ($i % 2?"trlight":"trdark") . "'><td>",true);
			rawoutput("$row[it]");
			output("</td><td>",true);
			$tex=stripslashes($row[$_GET['op']]);
			output("<input name='text' size='35' value='$tex'>",true);
			output("</td><td>",true);
			output("<input type='submit' class='button' value='Save'>",true);
			output("</td></tr>",true);
			output("</form>",true);
		}
	}
}

page_footer();
?>