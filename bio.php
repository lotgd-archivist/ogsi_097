<?php
require_once "common.php";
require_once "common2.php";
checkday();


/* Hugues asteriscata e spostata in bio giocatori Grotta superutente
if ($session['user']['superuser'] > 2){
   output("`@Visualizza Bio (si/no)?`n");
   output("<form action='bio.php?op1=bio' method='POST'><input name='bio' value='si'><input type='submit' class='button' value='Bio - Si/No'>`n",true);
   addnav("","bio.php?op1=bio");
}
if ($_GET['op1'] == "bio"){
   $bio = $_POST['bio'];
   savesetting("vedibio", $bio);
}
*/
if (getsetting("vedibio","si") == "si" ) {
	if ($_GET['char'] == "") $row['char'] = "excalibur";
	$result = db_query("SELECT acctid,login,name,dragonkills,reincarna,race,hashorse,mountname,id_drago,marriedto,sex,charisma,title,level,resurrections,pvpkills,pvplost,carriera,specialty,dio,housekey FROM accounts WHERE login='".$_GET['char']."'");
	$row = db_fetch_assoc($result);
    $row['login'] = rawurlencode($row['login']);
    $resultbio = db_query("SELECT * FROM bio WHERE bioacctid=".$row['acctid']." ");
    $rowbio = db_fetch_assoc($resultbio);
    
    page_header("Biografia del Personaggio: ".preg_replace("'[`].'","",$row['name']));
    
    if ($rowbio['biostatus']) {
	    output("`^La visualizzazione di questa Biografia è stata bloccata dagli `4Amministratori`^ in quanto il suo contenuto è inappropriato!`n`n");
	}else {    
	    $specialty=array(0=>"Non specificata","Arti Oscure","Poteri Mistici","Furto","Militare","Seduzione","Tattica","Pelle di Roccia","Retorica","Muscoli","Natura","Clima","Elementalista","Rabbia Barbara","Canzoni del Bardo");
	    
		output("`c`b`0Biografia di : `b ".$row['name']." ");
	   	if ($session['user']['loggedin']) output("<a href=\"mail.php?op=write&to=".$row['login']."\" target=\"_blank\" onClick=\"".popup("mail.php?op=write&to=".$row['login']).";return false;\"><img src='images/newscroll.GIF' width='16' height='16' alt='Write Mail' border='0'></a>",true);
	  	output("`c`n`n");
	    if ($session['user']['name'] != $row['name']) {
	       $dk=$session['user']['dragonkills']+($session['user']['reincarna']*19);
	       $dk1=$row['dragonkills']+($row['reincarna']*19);
	       if (($dk - $dk1) >= 0){
	           $deltadk = " `# Diff.DK = ".($dk - $dk1)." " ;
	       }else { 
		       $deltadk = "`4 Diff.DK = ".($dk - $dk1)." " ;
	       }
	    }else { 
		    $deltadk = "" ;
	    }  
	    $razza = $row[race] ;
	    $sqlm = "SELECT mountname FROM mounts WHERE mountid='".$row['hashorse']."'";
	    $resultm = db_query($sqlm);
	    $mount = db_fetch_assoc($resultm);
	    if ($mount['mountname']=="") {
	         $mount['mountname'] = "Nessuna";
	         $row['mountname'] = "";
	    }   
	    $sqld = "SELECT tipo_drago,nome_drago FROM draghi WHERE id = ".$row['id_drago']." ";
	    $resultd = db_query($sqld) or die(db_error(LINK));
	    $rowd = db_fetch_assoc($resultd);
	    $coloredrago = coloradrago($rowd['tipo_drago']);
	    if ($rowd['tipo_drago']=="") {
	   	  	 $rowd['tipo_drago'] = "Nessuno";
	         $rowd['nome_drago'] = "";
	    }
	    
	    if ($row['marriedto']){ 
		    if ($row['marriedto']==4294967295){
		        $partner = ($row['sex']?"`#Seth":"`%Violet") ;
		    }elseif ($row['charisma']==4294967295){
		        $sqlp = "SELECT login FROM accounts WHERE acctid='".$row['marriedto']."'";
		        $resultp = db_query($sqlp);
		        $rowp = db_fetch_assoc($resultp);
		        $partner = $rowp['login'] ;
		    }
	    }else {
		    $partner = "Nessuno" ;
	   	}
	   	
	   	$sqlh = "SELECT housename FROM houses WHERE houseid = ".$row['housekey']." ";
	    $resulth = db_query($sqlh) or die(db_error(LINK));
	    $rowh = db_fetch_assoc($resulth);
	    
	    if ($rowh['housename']=="") {
			$rowh['housename'] = "Nessuna";
	   	  	$casa = "";
	    }else {
		    $casa = "(  ".$row['housekey']."  )" ;
		}    
	   		
		$sqlc = "SELECT grado FROM caserma WHERE acctid = ".$row['acctid']." ";
	    $resultc = db_query($sqlc) or die(db_error(LINK));
	    $rowc = db_fetch_assoc($resultc);
	        
	    output("<table tdwidth=25% cellspacing=0 cellpadding=2 align='center'><tr><td>`b `b</td><td>`b `b</td><td>`b `b</td><td>`b `b</td></tr>",true);
	    
	    output("<td> `b`0Titolo: `0`b</td><td>".$row['title']."</td><td align=right>`b`0Livello:`b`0</td><td align=right>`@".$row['level']."</td><td align=right>`b`0Reincarnazioni:`b`0</td><td align=right>`@".$row['reincarna']."</td><td align=right>`b`0Resurrezioni:`b`0</td><td align=right>`@".$row['resurrections']."</td></tr>",true);
	    output("<td> `b`0Draghi Uccisi: `0`b</td><td>`@".$row['dragonkills']."$deltadk</td><td align=right>`b`0Nemici Uccisi:`b`0</td><td align=right>`@".$row['pvpkills']."</td><td align=right>`b`0PVP Persi:`b`0</td><td align=right>`@".$row['pvplost']."</td><td align=right>`b`0 `b`0</td><td align=right>`@ </td></tr>",true);
	    output("<td> `b`0Carriera: `0`b</td><td>`@".$prof[$row['carriera']]."</td><td align=right>`b`0Specialità:`b`0</td><td align=right>`@".$specialty[$row['specialty']]."</td><td align=right>`b`0Religione:`b`0</td><td align=right>`@".$fedecasa[$row['dio']]."</td><td align=right>`b`0Grado Militare:`b`0</td><td align=right>`@ ".$rowc['grado']."</td></tr>",true);
	    output("<td> `b`0Razza: `0`b</td><td>`@".$races[$razza]."</td><td align=right>`b`0Sesso:`b`0</td><td align=right>`@".($row['sex']?"Femmina":"Maschio")."</td><td align=right>`b`0".($row['sex']?"Sposata con:":"Sposato con:")."`b`0</td><td align=left>`@".$partner."</td><td align=right>`b`0 `b`0</td><td align=right>`@ </td></tr>",true);
	    output("<td> `b`0Creatura: `0`b</td><td>`@".$mount['mountname']."</td><td align=right>`b`0Nome Creatura:`b`0</td><td align=right>`@".$row['mountname']."</td><td align=right>`b`0 `b`0</td><td align=right>`@ </td><td align=right>`b`0 `b`0</td><td align=right>`@ </td></tr>",true);
	    output("<td> `b`0Drago:`b`0</td><td align=left>`@".$coloredrago."".$rowd['tipo_drago']."</td><td align=right>`b`0Nome Drago:`b`0</td><td align=right>`@".$rowd['nome_drago']."</td></tr>",true);
	    output("<td> `b`0Casa :`b`0</td><td align=left>`@".$rowh['housename']." ".$casa."</td><td align=right>`b`0 `b`0</td><td align=right>`0`@ </td></tr>",true);
	        
	    output("</table>",true);
	    
	    output("`n`n`^Descrizione Fisica `n`n ");
	   
	    output("<table tdwidth=50% cellspacing=0 cellpadding=2 align='left'><tr><td> </td><td> </td></tr>",true);
	    
	    output("<td> `0Età : </td><td>`@".($rowbio['bioage'])."</td></tr>",true);
	    output("<td> `0Occhi : </td><td>`@".($rowbio['bioeyes'])."</td></tr>",true);
	    output("<td> `0Capelli : </td><td>`@".($rowbio['biohair'])."</td></tr>",true);
	    output("<td> `0Carnagione : </td><td>`@".($rowbio['bioskin'])." </td></tr>",true);
	    output("<td> `0Corporatura : </td><td>`@".($rowbio['biobuild'])."</td></tr>",true);
	    output("<td> `0Altezza : </td><td>`@".($rowbio['bioheight'])."</td></tr>",true);
	    output("<td> `0Peso : </td><td>`@".($rowbio['bioweight'])."</td></tr>",true);
	    output("<td> `0Abbigliamento : </td><td>`@".($rowbio['biowear'])."</td></tr>",true);
	    output("<td> `0Segni particolari : </td><td>`@".($rowbio['bionotes'])."`0</td></tr>",true);
	 	
	    output("</table>",true);
	    
	    output("`n`n`n`n`n`n`n`n`n`n`n`n`n`n`n`^Descrizione Mentale `n`n ");
	    
	    output("<table tdwidth=50% cellspacing=0 cellpadding=2 align='left'><tr><td> </td><td> </td></tr>",true);
	    output("<td> `0Bio : </td><td>`@".($rowbio['bio'])."</td></tr>",true);
	    output("<td> `0Carattere : </td><td>`@".($rowbio['bionature'])."`0</td></tr>",true);
	    output("</table>",true);
	   	       
	    output("`n`n`n`n`n`n`n`n`n`n`n`n`n`n`n`^Imprese (e sconfitte) recenti `n ");   
		
		$resultn = db_query("SELECT * FROM news WHERE accountid=".$row['acctid']." ORDER BY newsdate DESC,newsid ASC LIMIT 100");
		$odate="";
		$countrown = db_num_rows($resultn);
		
		   
    	$mesedioggi = intval(date("m"));	
		$meselatino = array(1=>'Ianuarius',
                           'Februarius',
                           'Martius',
	                       'Aprilis',
	                       'Maius',
	                       'Iunius',
	                       'Iulius',
	                       'Augustus',
                           'September',
                           'October',
	                       'November',
	                       'December');	
		
		
		for ($i=0; $i<$countrown; $i++){
			$rown = db_fetch_assoc($resultn);
			
			if ($odate!=$rown['newsdate']){
				$timestamp = strtotime($rown['newsdate']) ;
				//output("`n`b`@".date("D, M d",strtotime($rown['newsdate']))."`b`n");
				$mesedioggi = intval(date("m",$timestamp));
				$datadioggi = (date("Y,",$timestamp)-773).", addì ".date("d ",$timestamp).$meselatino[$mesedioggi];
				output("`n`b`@ Anno Domini $datadioggi`b`n");
				$odate=$rown['newsdate'];
			}
			output("".$rown['newstext']."`n");
		}
	}		
}else{
   page_header("Biografia Personaggi");
   output("`@La visualizzazione delle Biografie dei Personaggi è stata temporaneamente bloccata dagli `\$Admin`@. `nRiprova in un altro momento.`n`n");
}

if ($_GET['ret']==""){
    addnav("Torna all'elenco guerrieri","list.php");
}else{
    $return = preg_replace("'[&?]c=[[:digit:]-]+'","",$_GET['ret']);
    $return = substr($return,strrpos($return,"/")+1);
    addnav("Torna da dove sei venuto",$return);
}
page_footer();

?>