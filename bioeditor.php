<?php
require_once "common.php";
require_once "common2.php";
//isnewday(3);
reset($_POST);
if (is_array($_POST['bio'])) reset($_POST['bio']);

page_header("Biografia Personaggi");
	
if ($_GET['ret']==""){
    addnav("P?Torna alle Preferenze","prefs.php");
    addnav("V?Torna al Villaggio","village.php");
}else{
	$ritorno=$_GET['ret'];
    $return = preg_replace("'[&?]c=[[:digit:]-]+'","",$_GET['ret']);
    $return = substr($return,strrpos($return,"/")+1);
    if ($_GET['op']=="save"){
    	addnav("T?Torna da dove sei venuto ",$return."?id={$bio['bioacctid']}");
    }else{
	    addnav("T?Torna da dove sei venuto ",$return);
	}    	
}

if ($_GET['op']==""){
	
	$sql = "SELECT * FROM bio where bioacctid= ".$session['user']['acctid']." "; 
	$resultbio = db_query($sql) or die(db_error(LINK));
	if (db_num_rows($resultbio) == 0) {
	  addnav("Crea la tua Biografia","bioeditor.php?op=add");
    }else{  
   		
	    $rowbio = db_fetch_assoc($resultbio);
	    
		output("`n`b`0Biografia di : `b ".$session['user']['name']." `n`n`n");
		if ($rowbio['biostatus']) {
			output("`b`4Attenzione!!`b la tua Biografia risulta essere Bloccata dagli Amministratori perchè contiene testi non appropriati. ");
	    	output("`nCorreggila e contatta lo Staff tramite una petizione affinchè dopo verifica dei contenuti possa essere sbloccata.`n`n ");
		}   
	    output("<table cellspacing=0 cellpadding=2 align='left'><tr><td>`^Descrizione </td><td>`^Fisica </td></tr>",true);
    	
	    output("<td> `n`0Età : </td><td>`n`@".($rowbio['bioage'])."</td></tr>",true);
	    output("<td> `0Occhi : </td><td>`@".($rowbio['bioeyes'])."</td></tr>",true);
	    output("<td> `0Capelli : </td><td>`@".($rowbio['biohair'])."</td></tr>",true);
	    output("<td> `0Carnagione : </td><td>`@".($rowbio['bioskin'])." </td></tr>",true);
	    output("<td> `0Corporatura : </td><td>`@".($rowbio['biobuild'])."</td></tr>",true);
	    output("<td> `0Altezza : </td><td>`@".($rowbio['bioheight'])."</td></tr>",true);
	    output("<td> `0Peso : </td><td>`@".($rowbio['bioweight'])."</td></tr>",true);
	    output("<td> `0Abbigliamento : </td><td>`@".($rowbio['biowear'])."</td></tr>",true);
	    output("<td> `0Segni particolari : </td><td>`@".($rowbio['bionotes'])."</td></tr>",true);
	 	
	    output("<td> `n`^Descrizione </td><td>`n`^Mentale </td></tr>",true);
	    output("<td> `0Bio : </td><td>`n`@".($rowbio['bio'])."</td></tr>",true);
	    output("<td> `0Carattere : </td><td>`@".($rowbio['bionature'])."</td></tr>",true);
	    output("</table>",true);
   		
	    output("`n`n`n`n`n`n`n`n`n`n`n`n`n`n`n`n`n`n`n`n`n`n`n`n`n`n`0`n`n`n");
	       
	    addnav("Modifica Biografia","bioeditor.php?op=edit&id={$rowbio['bioacctid']}"); 
	  
   }
 
}

if($_GET['op']=="add"){
   output("Editor Biografia Personaggi:`n");
   creabio(array(),"");
}

if ($_GET['op']=="edit"){
	
    $sqlbio = "SELECT * FROM bio WHERE bioacctid='".$_GET['id']."'";
 
    $resultbio = db_query($sqlbio);
    if (db_num_rows($resultbio)<=0){
        output("`i`b`\$Biografia non trovata! Contatta gli Amministratori per cortesia `i`b`0");
    }else{
        output("Editor Biografia Personaggi:`n");
        $rowbio = db_fetch_assoc($resultbio);
        creabio($rowbio,$ritorno);
	}
   
}

if ($_GET['op']=="save"){  //Crea o Aggiorna Biografia
    $sql='';
    $i=0; 
    $stato = controllabio(false);
    $_POST['bio']['biotime'] = date("Y-m-d H:i:s");
      
    //if ($_POST['bioacctid']==0 ) {
	//	$_POST['bioacctid'] = $session['user']['acctid'];
	//}   
    while (list($key,$val)=each($_POST['bio'])){
        if (is_array($val)) $val = addslashes(serialize($val));
        if ($_GET['id']>""){
            $sql.=($i>0?",":"")."$key='$val'";
            
        }else{
            $keys.=($i>0?",":"")."$key";
            $vals.=($i>0?",":"")."'$val'";
        }
        $i++;
    }
   
    if ($_GET['id']> 0){
	    $sql="UPDATE bio SET $sql WHERE bioacctid={$_GET['id']}";
    }else{
	    
	    $keys.=',bioacctid';
	    $vals.=($i>0?",":"").$session['user']['acctid'];
        $sql="INSERT INTO bio ($keys) VALUES ($vals)";
    }
    db_query($sql);
    if (db_affected_rows()>0){
	    if ($stato) {
			output("`n`@Biografia salvata nonostante gli errori segnalati, probabilmente alcuni campi verranno troncati! `0");
		}else{    
        	output("`n`@Biografia salvata Correttamente! `0");
        }
        if ($session['user']['acctid']==$_GET['id']) {
			report(3,"`GModifica Bio di `&".$session['user']['login']."`0","`&".$session['user']['login']."`2 ha modificato/inserito la propria bio`n","biografia");
    	}
	}else{
        output("`n`\$Biografia non salvata ! Contatta gli Amministratori per cortesia. $sql");
    }
	addnav("Modifica Biografia","bioeditor.php?op=edit&id={$_GET['id']}"); 	    
   
}

function controllabio($stato){
	
	global $_POST ;
	
    if (strlen($_POST['bio']['bioage'])>20){
                output("`n`4Il campo Età supera di ".(strlen($_POST['bio' ]['bioage'])-20)." caratteri la lunghezza massima consentita!`0`n",true);
                $stato = true;
    }  
    if (strlen($_POST['bio']['bioeyes'])>20){
                output("`n`4Il campo Occhi supera di ".(strlen($_POST['bio' ]['bioeyes'])-20)." caratteri la lunghezza massima consentita!`0`n",true);
                $stato = true;
    } 
    if (strlen($_POST['bio']['biohair'])>20){
                output("`n`4Il campo Capelli supera di ".(strlen($_POST['bio' ]['biohair'])-20)." caratteri la lunghezza massima consentita!`0`n",true);
                $stato = true;
    }  
    if (strlen($_POST['bio']['bioskin'])>20){
                output("`n`4Il campo Carnagione supera di ".(strlen($_POST['bio' ]['bioskin'])-20)." caratteri la lunghezza massima consentita!`0`n",true);
                $stato = true;
    }   
    if (strlen($_POST['bio']['biobuild'])>20){
                output("`n`4Il campo Corporatura supera di ".(strlen($_POST['bio' ]['biobuild'])-20)." caratteri la lunghezza massima consentita!`0`n",true);
                $stato = true;
    }  
    if (strlen($_POST['bio']['bioheigth'])>20){
                output("`n`4Il campo Altezza supera di ".(strlen($_POST['bio' ]['bioheight'])-20)." caratteri la lunghezza massima consentita!`0`n",true);
                $stato = true;
    } 
    if (strlen($_POST['bio']['bioweight'])>20){
                output("`n`4Il campo Peso supera di ".(strlen($_POST['bio' ]['bioweight'])-20)." caratteri la lunghezza massima consentita!`0`n",true);
                $stato = true;
    }  
    if (strlen($_POST['bio']['biowear'])>200){
                output("`n`4Il campo Abbigliamento supera di ".(strlen($_POST['bio' ]['biowear'])-200)." caratteri la lunghezza massima consentita!`0`n",true);
                $stato = true;
    }
    if (strlen($_POST['bio']['bionotes'])>200){
                output("`n`4Il campo Segni Particolari supera di ".(strlen($_POST['bio' ]['bionotes'])-200)." caratteri la lunghezza massima consentita!`0`n",true);
                $stato = true;
    }
    if (strlen($_POST['bio']['bio'])>500){
                output("`n`4Il campo Biografia supera di ".(strlen($_POST['bio' ]['bio'])-500)." caratteri la lunghezza massima consentita!`0`n",true);
                $stato = true;
    }
    if (strlen($_POST['bio']['bionature'])>200){
                output("`n`4Il campo Carattere supera di ".(strlen($_POST['bio' ]['bionature'])-200)." caratteri la lunghezza massima consentita!`0`n",true);
                $stato = true;
    }   
    return $stato;            
    
}

function creabio($bio,$ritorno){
    global $output;
      
    if ( $ritorno > "" ) {
		
    	output("<form action='bioeditor.php?op=save&id={$bio['bioacctid']}&ret=$ritorno' method='POST'>",true);
    	addnav("","bioeditor.php?op=save&id={$bio['bioacctid']}&ret=$ritorno");
    	
    }else{	
	    output("<form action='bioeditor.php?op=save&id={$bio['bioacctid']}' method='POST'>",true);
    	addnav("","bioeditor.php?op=save&id={$bio['bioacctid']}");
    	
	}   
	
    $output.="<table>";
    $output.="<tr><td>Età (max 20 car) :</td><td><input size=20 name='bio[bioage]' value=\"".HTMLEntities2($bio['bioage'])."\"></td></tr>";                             
    $output.="<tr><td>Occhi (max 20 car) :</td><td><input size=20 name='bio[bioeyes]' value=\"".HTMLEntities2($bio['bioeyes'])."\"></td></tr>";
    $output.="<tr><td>Capelli (max 20 car) :</td><td><input size=20 name='bio[biohair]' value=\"".HTMLEntities2($bio['biohair'])."\"></td></tr>";
    $output.="<tr><td>Carnagione (max 20 car) :</td><td><input size=20 name='bio[bioskin]' value=\"".HTMLEntities2($bio['bioskin'])."\"></td></tr>";
    $output.="<tr><td>Corporatura (max 20 car) :</td><td><input size=20 name='bio[biobuild]' value=\"".HTMLEntities2($bio['biobuild'])."\"></td></tr>";
    $output.="<tr><td>Altezza (max 20 car) :</td><td><input size=20 name='bio[bioheight]' value=\"".HTMLEntities2($bio['bioheight'])."\"></td></tr>";
    $output.="<tr><td>Peso (max 20 car) :</td><td><input size=20 name='bio[bioweight]' value=\"".HTMLEntities2($bio['bioweight'])."\"></td></tr>";
    $output.="<tr><td>Abbigliamento (max 200 car) :</td><td><textarea class='input' name='bio[biowear]' cols='40' rows='5' >".HTMLEntities2($bio['biowear'])."</textarea></td></tr>";
    $output.="<tr><td>Segni Particolari (max 200 car) :</td><td><textarea class='input' name='bio[bionotes]' cols='40' rows='5'  >".HTMLEntities2($bio['bionotes'])."</textarea></td></tr>";
    $output.="<tr><td>Breve Biografia del Personaggio (max 500 car) :</td><td><textarea class='input' name='bio[bio]' cols='40' rows='5' >".HTMLEntities2($bio['bio'])."</textarea></td></tr>";
    $output.="<tr><td>Carattere (max 200 car) :</td><td><textarea class='input' name='bio[bionature]' cols='40' rows='5' >".HTMLEntities2($bio['bionature'])."</textarea></td></tr>";
    
    $output.="</td></tr>";
    $output.="</table>";
    $output.="<input type='submit' class='button' value='Salva'></form>";
}

page_footer();
?>