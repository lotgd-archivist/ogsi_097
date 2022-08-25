<?php
require_once "common.php";

isnewday(2);
page_header("Biografie Giocatori");
addnav("G?Torna alla Grotta","superuser.php");
addnav("M?Torna alla Mondanità","village.php");
addnav("R?Ricerca Bio per player","bios.php?op=ricerca");
addnav("Blocca / Sblocca Tutte le Bio","bios.php?op=allbio");
addnav("Torna a Biografie","bios.php");

if ($_GET['op']=="ricerca"){
		output("`6Inserisci il nome completo o una parte di esso del personaggio per cui vuoi cercare la biografia`n`n");
		output("<form action='bios.php?op=ricerca_player' method='POST'>",true);
	    addnav("","bios.php?op=ricerca_player");
	    output("`bNome del giocatore:`b <input name='name'> <input type='submit' class='button' value='Ricerca'>",true);
	    output("</form>",true);
	    //addnav("Torna a Biografie","bios.php");
}elseif($_GET['op']=="ricerca_player"){
	    $search="%";
	    for ($i=0;$i<strlen($_POST['name']);$i++){
	        $search.=substr($_POST['name'],$i,1)."%";
	    }
	    $sql = "SELECT name,acctid FROM accounts WHERE login LIKE '$search' ";
	    $result = db_query($sql);
	    $countrow = db_num_rows($result);
	    if ($countrow > 0) {
		    output("`6Seleziona dall'elenco il nome corretto del personaggio per cui vuoi cercare la biografia`n`n");
		}else{
			output("`6Sei proprio sicuro ? Non trovo nessun personaggio col nome che mi hai indicato! `n`n"); 
		}	   
	    $countrow = db_num_rows($result);
	    for ($i=0; $i<$countrow; $i++){
	    $row = db_fetch_assoc($result);
	        output("<a href='bios.php?op=conferma_player&id={$row['acctid']}'>",true);
	        output($row['name']);
	        output("</a>`n",true);
	        addnav("","bios.php?op=conferma_player&id={$row['acctid']}");
	    }
	    //addnav("Torna a Biografie","bios.php");
}elseif($_GET['op']=="conferma_player"){
		global $player ;
		$player=$_GET['id'];
		$sql = "SELECT name,acctid,login,b.bio,b.biotime,b.biostatus FROM accounts a,bio b WHERE acctid=bioacctid AND acctid=$player ";
		$result = db_query($sql);
				
		$row = db_fetch_assoc($result);
		
            $biolink="bio.php?char=".rawurlencode($row['login'])."&ret=".urlencode($_SERVER['REQUEST_URI']);
            addnav("", $biolink);
            $biolinkeditor="bioeditor.php?char=".rawurlencode($row['login'])."&ret=".urlencode($_SERVER['REQUEST_URI'])."&op=edit"."&id={$row['acctid']}";
            addnav("", $biolinkeditor);
		
        if ($row['biotime']>$session['user']['recentcomments']) {
		        output("<img src='images/new.gif' alt='&gt;' width='3' height='5' align='absmiddle'> ",true);
		}        
		if ($row['biostatus']) {
			output("`![<a href='bios.php?op=unblock&userid={$row['acctid']}'>Sblocca</a>]",true);
		    addnav("","bios.php?op=unblock&userid={$row['acctid']}");
		    //output("`&{$row['name']}: `^".soap($row['bio'])."`n");
		}else {    
		    output("`![<a href='bios.php?op=block&userid={$row['acctid']}'>Blocca</a>]",true);
		    addnav("","bios.php?op=block&userid={$row['acctid']}");
		    //output("`&{$row['name']}: `^".soap($row['bio'])."`n");
		}
		output("`&{$row['name']}: `^".soap($row['bio'])."`n");
		output("[ <a href='$biolink'>Vedi Bio completa</a> ]`n`n",true);
		output("[ <a href='$biolinkeditor'>Mofidica Bio </a> ]`n`n",true);
		//output("`&{$row['name']}: `^".soap($row['bio'])."`n");
		//addnav("Torna a Biografie","bios.php");
}elseif ($_GET['op']=="block"){
	$dataoraupdate = date("Y-m-d H:i:s") ;
    $sql = "UPDATE bio SET biostatus=1,biotime='$dataoraupdate' WHERE bioacctid='{$_GET['userid']}'";
    systemmail($_GET['userid'],"La tua bio è stata bloccata","L'admin ha deciso che il testo della tua biografia è inappropriato,
     per cui è stato bloccato.`n`nSe vuoi appellarti a questa decisione, puoi farlo usando il link \"Petizioni\".");
    db_query($sql);
    //addnav("Torna a Biografie","bios.php");
}elseif ($_GET['op']=="unblock"){
	$dataoraupdate = date("Y-m-d H:i:s") ;
    $sql = "UPDATE bio SET biostatus=0,biotime='$dataoraupdate' WHERE bioacctid='{$_GET['userid']}'";
    systemmail($_GET['userid'],"La tua bio è stata sbloccata","L'admin ha deciso di sbloccare la tua biografia. Puoi inserire nuovamente un testo.");
    db_query($sql);
    //addnav("Torna a Biografie","bios.php");
}elseif ($_GET['op']=="allbio"){
	if ($_POST['bio'] == "") {
		output("`@Per Bloccare la visualizzazione delle Biografie dei personaggi selezionare `0No`@ per Sbloccare selezionare `0Si`@ e poi premere il pulsante.`n`n");
	   	output("<form action='bios.php?op=allbio' method='POST'><input type='submit' class='button' value='vedi Bio - si/no'>`n`n`n",true);
	   	output("<input type='radio' name='bio' value='si'> `@Si",true);
	   	output("<input type='radio' name='bio' value='no'> `@No",true);
	   	addnav("","bios.php?op=allbio");
	}else{   	
		$bio = $_POST['bio'];
		savesetting("vedibio", $bio);
   		if ($bio == "si") {
	   		output("`n`@Ora TUTTE le Biografie `4sono`@ visualizzabili!`n`n");
		}else{
			output("`n`@Ora TUTTE le Biografie `4NON`@ sono visualizzabili!`n`n");
		}
	 }
	 //addnav("Torna a Biografie","bios.php");			    
}elseif ($_GET['op']==""){
	
	if ($session['user']['superuser'] > 2){
		$vedibio = getsetting("vedibio","") ;
		if ($vedibio == "si") {
			output("`@Le Biografie dei personaggi `4sono`@ attualmente visualizzabili.`n`n");
		}else{
			output("`@Le Biografie dei personaggi `4Non`@ sono attualmente visualizzabili.`n`n");
		}
	}
	
	$sql = "SELECT name,acctid,b.bio,b.biotime FROM accounts a,bio b WHERE acctid=bioacctid AND b.biostatus = 0 ORDER BY b.biotime DESC LIMIT 100";
	$result = db_query($sql);
	output("`6Per visualizzare le Biografie complete ed eventualmente editarle occorre prima effettuare la ricerca per player`n`n");
	output("`n`b`&Ultime Biografie Modificate :`0`b`n`n");
	$countrow = db_num_rows($result);
	for ($i=0; $i<$countrow; $i++){
	    $row = db_fetch_assoc($result);
	    if ($row['biotime']>$session['user']['recentcomments'])
	        output("<img src='images/new.gif' alt='&gt;' width='3' height='5' align='absmiddle'> ",true);
	    output("`![<a href='bios.php?op=block&userid={$row['acctid']}'>Blocca</a>]",true);
	    addnav("","bios.php?op=block&userid={$row['acctid']}");
	    output("`&{$row['name']}: `^".soap($row['bio'])."`n");
	}
	db_free_result($result);
	
	$sql = "SELECT name,acctid,b.bio,b.biotime FROM accounts a,bio b WHERE acctid=bioacctid AND b.biostatus = 1 ORDER BY b.biotime DESC LIMIT 100";
	$result = db_query($sql);
	output("`n`n`b`&Biografie Personaggi Bloccate :`0`b`n`n");
	$countrow = db_num_rows($result);
	for ($i=0; $i<$countrow; $i++){
	    $row = db_fetch_assoc($result);
	    output("`![<a href='bios.php?op=unblock&userid={$row['acctid']}'>Sblocca</a>]",true);
	    addnav("","bios.php?op=unblock&userid={$row['acctid']}");
	    output("`&{$row['name']}: `^".soap($row['bio'])."`n");
	}
	db_free_result($result);
}

page_footer();


?>