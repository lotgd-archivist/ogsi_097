<?php
require_once "common.php";
popup_header("Limite pagine raggiunto",true);
//header("Location: limite.php");
if (!$_GET[op]) {
	output("`\$`b<big><big>`cATTENZIONE!!!`c`n`@STAI PER ESSERE SCOLLEGATO PER AVER RAGGIUNTO ",true);
	output("IL LIMITE MASSIMO DI PAGINE VISUALIZZABILE PER SESSIONE.</big></big>`0`b`n`n",true);
	output("`#Devi inserire il codice oppure effettuare il logout e riloggarti se vuoi continuare a giocare`n",true);
	$array_lettere=array('A','T','S','K','Z','X','C','U','L','M');
	$codice=e_rand(0,9).e_rand(0,9).e_rand(0,9).e_rand(0,9).$array_lettere[e_rand(0,9)];
	output("`#CODICE AZZERAMENTO CLICK : $codice`n",true);
	output("<form action='limite.php?op=verifica' method='POST'>",true);
	addnav("","limite.php?op=verifica");
	output("`bInserisci codice : <input name='user_code' size='3'><input type='hidden' name='code' value='$codice'><input type='submit' class='button' value='Invia'>",true);
	output("</form>",true);
}else{
	if($_POST[code]==$_POST[user_code]){
		output("`#Codice corretto click azzerati`n",true);
		$session['user']['click_limit']=0;
	}else{
		output("`\$Codice errato!`n",true);
	}
}
popup_footer();
?>