<?php
require_once("common.php");
require_once("common2.php");
page_header("L'Ufficio del Catasto");
$datadioggi = ottienidatadelgiorno($datadioggi);
function getUserKey($user, $house) {
    $chiaveSql = "SELECT * FROM items WHERE owner=$user AND class='Key' AND value1=$house ORDER BY value2 DESC";
    $chiaveResult = db_query($chiaveSql) or die(db_error(LINK));
    $chiave = db_fetch_assoc($chiaveResult);
    return $chiave;
}

if ($_GET['op']==""){
	if ($session['user']['cittadino']=="No" ) {
		output("`#Cerchi di entrare nella sala ma due robuste Guardie ti impediscono di entrare dicendoti : `n ");
		output("`6Ingresso vietato ");
		output($session['user']['name'],true);	
		output(" `6, solo ai cittadini di Rafflingate è consentito usufruire dei servizi dell'Ufficio Catasto !`0`n`n");
		addnav("Torna all'Ingresso del Municipio","municipio.php");
	}else{
		output("`#`nDopo aver oltrepassato le guardie all'ingresso che, avendoti riconosciuto come cittadino di Rafflingate, ti hanno consentito di proseguire, ");	
		output("`#entri in un grande salone dove in fondo, c'è una serie di tavoli ciascuno con un funzionario indaffarato a scrivere o a consultare enormi libroni. ");
		output("`#Mentre cerchi di capire a quale ufficio corrisponda ogni scrivania per indirizzarti verso il funzionario giusto, il tuo sguardo vaga per l'enorme stanza, alla tua sinistra, una gigantesca bacheca ");
		output("`#riempie l'intera parete e la scritta `b`8Elenco Tenute di Rafflingate`b`# attira la tua attenzione. Dall'altro lato invece, ");
		output("`#la parete opposta è completamente ricoperta da un gigantesco arazzo che raffigura la mappa della città di Rafflingate. `n`n");
		addnav("Esamina la Bacheca","municipio_ufficio_catasto.php?op=elenco");
		addnav("Esamina l'arazzo","municipio_ufficio_catasto.php?op=arazzo");
		
		$sql = "(SELECT houseid, housename FROM items,houses WHERE items.owner ='".$session['user']['acctid']."' AND class = 'key' AND houses.houseid = items.value1 AND houses.status=3 )";
		$result = db_query($sql) or die(db_error(LINK));
    	
    	if (db_num_rows($result) !== 0){
    		addnav("Sportello Tenute Abbandonate","municipio_ufficio_catasto.php?op=abbandonate");
    	}
    		   
	    addnav("Torna all'Ingresso del Municipio","municipio.php");
	}
	
}elseif($_GET['op']=="abbandonate"){
		output("`#Dopo averti fatto aspettare qualche minuto, il funzionario alza gli occhi dal suo massiccio librone e ti elenca le tenute abbandonate delle quali possiedi una chiave, e ti informa che risulta possibile ");	
		output("`#recuperare le tue `&gemme`# in esse depositate, consegnando la chiave e pagando una piccola tassa di `^250 monete`# per gli adempimenti burocratici necessari alla pratica e di `^10 monete`# per ogni `&gemma`# che ti verrà restituita. `n");
		output("`@`nPossiedi le chiavi delle seguenti tenute abbandonate: `n`n");  	
    	$sql = "(SELECT houseid, housename, value1 FROM items,houses WHERE items.owner ='".$session['user']['acctid']."' AND class = 'key' AND houses.houseid = items.value1 AND houses.status=3 )";
		$result = db_query($sql) or die(db_error(LINK));
    	output("<table cellpadding = 2 align = 'center'><tr><td>`bCasa#. `b</td><td>`bNome`b</td></tr>",true);
    	$countrow = db_num_rows($result);
        for ($i=0; $i<$countrow; $i++){
        	$row = db_fetch_assoc($result);
        	$bgcolor= ($i%2==1?"trlight":"trdark");
        	if ($office !=$row[value1] && $row[value1] !=$session[user][house]){
        		output("<tr class='$bgcolor'><td align = 'center'>$row[houseid]</td><td><a href='municipio_ufficio_catasto.php?op=inside&id=$row[houseid]'>$row[housename]</a></td></tr>",true);
            	addnav("","municipio_ufficio_catasto.php?op=inside&id=$row[houseid]");
            }
            $office=$row[value1];             
      	}
    	output("</table>",true);
    	addnav("Torna al centro del Salone", "municipio_ufficio_catasto.php");
    	addnav("Torna all'Ingresso del Municipio","municipio.php");
}elseif($_GET['op']=="inside"){
		$tenuta = $_GET['id'];
		$sqlh = "(SELECT housename FROM houses WHERE houseid = $tenuta )";
		$resulth = db_query($sqlh) or die(db_error(LINK));
		$rowh = db_fetch_assoc($resulth);
		$descrizione = $rowh[housename];
		output("`#Consegni la tua chiave e, dopo aver controllato più volte sui suoi libroni, l'anziano funzionario prende una pergamena, intinge la penna d'oca nel calamaio, ");
    	output("redige in bella calligrafia l'atto della tua restituzione, scalda della ceralacca facendone colare alcune gocce sul ");
    	output("documento, vi imprime il sigillo municipale e inizia a leggere:  `&`n `n Anno Domini `^$datadioggi`& il quì presente "); 
    	output($session['user']['name'],true);
    	output("`& ha restituito in codesto loco la chiave della tenuta abbandonata `^ $tenuta `&denominata `^$descrizione. ");
    	output("`n`& Per i poteri conferitimi da Sua Eccellenza il Sindaco di Rafflingate, la richiesta viene accolta senza riserve.");
    	$sql = "SELECT * FROM houses WHERE houseid = $tenuta ORDER BY houseid DESC";
    	$result = db_query($sql) or die(db_error(LINK));
   	 	$row = db_fetch_assoc($result);
    	$chiaveDaTogliere = getUserKey($session['user']['acctid'], $tenuta);
	    $sqlprop = "SELECT owner FROM items WHERE value1=$tenuta AND class='key' AND owner=$row[owner] ORDER BY id ASC LIMIT 1";
        $resultprop = db_query($sql) or die(db_error(LINK));
        $rowprop = db_fetch_assoc($resultprop);
        $gemsgive = $chiaveDaTogliere['gems'];
        $session['user']['gems'] += $gemsgive;
        debuglog("si riprende $gemsgive gemme restituendo la chiave della tenuta al Catasto");
        $datarestituzione = strtotime(date("r")."+48 hours");
        $sql = "UPDATE items SET owner = $row[owner], gems = 0, buff = ".$session['user']['acctid'].", tempo = $datarestituzione WHERE id = ".$chiaveDaTogliere['id'];
        db_query($sql);
        $tasse  = ( 250 + ($gemsgive*10));     
        $sqlupdate = "UPDATE tasse SET oro = oro + $tasse WHERE acctid=".$session['user']['acctid'];
        db_query($sqlupdate) or die(db_error(LINK));
        output("`n Pertanto vengono restituite al quì presente ");
        output($session['user']['name'],true);
        output("`& numero `^$gemsgive gemme `& di sua proprietà depositate nella cassaforte della tenuta e vengono addebitate `^$tasse monete d'oro `& per le spese burocratiche sostenute da codesto ufficio. ");
	    addnav("Torna all'Ingresso del Municipio","municipio.php");

}elseif ($_GET['op'] == "arazzo") {
		output("`#Ti avvicini lentamente all'arazzo per guardarlo più da vicino, la mappa della città è molto ben dettagliata e ne riconosci alcuni edifici. `n");  
    	output("Però non vedi null'altro di particolarmente interessante e degno di nota. ");
    	addnav("Torna al centro del Salone", "municipio_ufficio_catasto.php");
    	
}elseif ($_GET['op'] == "elenco") {
	    output("Sulla parete della stanza su una bacheca di legno è affissa una pergamena dove sono indicate le attuali case e i loro rispettivi proprietari. `n`n");
	    addnav("Torna al centro del Salone", "municipio_ufficio_catasto.php");
	    $sql = "SELECT * FROM houses WHERE status<100 ORDER BY houseid ASC";
    	output("<table cellpadding = 2 cellspacing = 1 bgcolor='#999999' align = 'center'><tr class = 'trhead'><td>`bCasa#. `b</td><td>`bNome`b</td><td>`bProprietario`b</td><td>`bStato`b</td><td>`bFede`b</td></tr>",true);
    	$result = db_query($sql) or die(db_error(LINK));
    	if (db_num_rows($result) ==0){
        	output("<tr><td colspan = 4 align = 'center'>`&`iAncora nessuna Casa`i`0</td></tr>",true);
	    }else{
	        $countrow = db_num_rows($result);
	        for ($i=0; $i<$countrow; $i++){
		        $row = db_fetch_assoc($result);
		        $bgcolor= ($i%2==1?"trlight":"trdark");
		        output("<tr class='$bgcolor'><td align = 'right'>$row[houseid]</td><td>$row[housename]</td><td>",true);
		        $sql = "SELECT name FROM accounts WHERE acctid = $row[owner] ORDER BY acctid DESC";
		        $result2 = db_query($sql) or die(db_error(LINK));
		        $row2 = db_fetch_assoc($result2);
		        output("$row2[name]</td><td>",true);
		        if ($row['status'] ==0) output("`6In Costruzione`0");
		        elseif ($row['status'] ==1) output("`!Abitata`0");
		        elseif ($row['status'] ==2) output("`^In Vendita`0");
		        elseif ($row['status'] ==3) output("`4Abbandonata`0");
		        elseif ($row['status'] ==4) output("`\$In Rovina`0");
		        output("</td><td>",true);
		        output($fedecasa[$row['fede']]);
		        output("</td></tr>",true);
	        }
	    }
	    		    	  	
}     
page_footer();

?>
