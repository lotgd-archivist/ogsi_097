<?php
require_once("common.php");
addcommentary();
page_header("La Caserma di Rafflingate");
$session['user']['locazione'] = 113;
$player_caserma = getsetting("player_caserma", 0);
$sql = "SELECT * FROM caserma WHERE acctid = '".$session['user']['acctid']."'";
$result = db_query($sql) or die(db_error(LINK));
$row = db_fetch_assoc($result);
if($_POST['testo']){
    $text=$_POST[testo];
    savesetting("comandante_messaggio","$text");
    savesetting("comandante_data","".time()."");
}
output("<big>`b`c`6ANNUNCIO DEL COMANDANTE `#".getsetting("comandante_nome","")." `@DELLA CASERMA`0`c`b</big>`n",true);
output("`8".date("d/m/Y",getsetting("comandante_data",time()))." `b`^".getsetting("comandante_messaggio","")."`b`n`n");
if ($_GET['op']=="") {
    $session['user']['dove_sei'] = 7;
    addnav("Azioni");
    addnav("Fanteria", "caserma.php?op=fanteria");
    addnav("Cavalleria", "caserma.php?op=cavalleria");
    addnav("Genio", "caserma.php?op=genio");
    addnav("Effettivi", "caserma.php?op=effettivi");
    addnav("Sala comando", "caserma.php?op=comando");
    addnav("Torna al Villaggio", "village.php");
    output("`3Arrivi alla caserma, varchi l'ingresso e ti ritrovi in un grande piazzale dove l'attività è frenetica. Ovunque soldati impegnati in varie occupazioni o in sessioni di addestramento, quà e là capannelli di militari osservano alcuni loro compagni impegnati");
    output("`3in esercitazioni, o simulazioni di duelli e battaglie con le armi più svariate e stravaganti, mentre un massiccio `2troll`3 infuriato grida a squarciagola nelle orecchie di un gruppo di nuovi arrivati ordinando loro di mettersi in fila e di fare silenzio!!!`n");
    if(!$row[scuola]){
        output("`3Poi si rivolge a te, finalmente è il tuo turno:\"`@Vedo che sei nuovo, abbiamo bisogno di tutti, ognuno deve fare la sua parte nella guerra contro `6Eythgim!`3\" si ferma un attimo poi prosegue\"`@In questa Caserma ci sono ben tre scuole militari e tutta di alta tradizione, entra pure se vuoi arruolarti! `3\"`n");
    }
    //script promozione
    $sql = "SELECT id_corso FROM caserma_corsi_fatti WHERE acctid = '".$session['user']['acctid']."'";
    $result = db_query($sql) or die(db_error(LINK));
    $corso=0;
    $countrow = db_num_rows($result);
    for ($i=0; $i<$countrow; $i++){
    //for($i=0;$i< db_num_rows($result);$i++){
        $rowc = db_fetch_assoc($result);
        if($rowc[id_corso]==27)$corso+=1;
        if($rowc[id_corso]==30)$corso+=1;
        if($rowc[id_corso]==35)$corso+=1;
        if($rowc[id_corso]==36)$corso+=1;
        if($rowc[id_corso]==39)$corso+=1;
        if($rowc[id_corso]==42)$corso+=1;
    }
    if($corso==6 AND $row[grado]!='Generale'){
        $sqlupdate = "UPDATE caserma SET grado = 'Generale' WHERE acctid='".$session['user']['acctid']."'";
        db_query($sqlupdate) or die(db_error(LINK));
        output("`6Complimenti! Sei stato promosso al grado di generale.`3`n");
    }elseif($corso==5 AND $row[grado]!='Colonnello'){
        $sqlupdate = "UPDATE caserma SET grado = 'Colonnello' WHERE acctid='".$session['user']['acctid']."'";
        db_query($sqlupdate) or die(db_error(LINK));
        output("`6Complimenti! Sei stato promosso al grado di colonnello.`3`n");
    }elseif($corso==4 AND $row[grado]!='Maggiore'){
        $sqlupdate = "UPDATE caserma SET grado = 'Maggiore' WHERE acctid='".$session['user']['acctid']."'";
        db_query($sqlupdate) or die(db_error(LINK));
        output("`6Complimenti! Sei stato promosso al grado di maggiore.`3`n");
    }elseif($corso==3 AND $row[grado]!='Capitano'){
        $sqlupdate = "UPDATE caserma SET grado = 'Capitano' WHERE acctid='".$session['user']['acctid']."'";
        db_query($sqlupdate) or die(db_error(LINK));
        output("`6Complimenti! Sei stato promosso al grado di capitano.`3`n");
    }elseif($corso==2 AND $row[grado]!='Tenente'){
        $sqlupdate = "UPDATE caserma SET grado = 'Tenente' WHERE acctid='".$session['user']['acctid']."'";
        db_query($sqlupdate) or die(db_error(LINK));
        output("`6Complimenti! Sei stato promosso al grado di tenente.`3`n");
    }elseif($corso==1 AND $row[grado]!='Sergente'){
        $sqlupdate = "UPDATE caserma SET grado = 'Sergente' WHERE acctid='".$session['user']['acctid']."'";
        db_query($sqlupdate) or die(db_error(LINK));
        output("`6Complimenti! Sei stato promosso al grado di sergente.`3`n");
    }
}
//Hugues: Mail massive
if ($_GET['op'] == "mail") {
	output("Testo da inviare.`n");
    output("<form action='caserma.php?op=mailto&az=".$_GET['az']."' method='POST'>",true);
    output("<textarea class='input' name='body' cols='37' rows='5'>".HTMLEntities2(stripslashes($_POST['body']))."</textarea>`n",true);
    output("<input type='submit' class='button' value='Invia'></form>",true);
    addnav("","caserma.php?op=mailto&az=".$_GET['az']);
    addnav("Torna all'entrata","caserma.php");
}
if ($_GET['op'] == "mailto") {
    $body = "`^Messaggio del Comandante della Caserma.`n";
    $body .="`#".$_POST['body'];
    if ($_GET['az'] == 1) {
        $corpo = "scuola='Fanteria'";
    }elseif ($_GET['az'] == 2) {
        $corpo = "scuola='Cavalleria'";
    }elseif ($_GET['az'] == 3) {
        $corpo = "scuola='Genio'";
    }
    $sqlmail = "SELECT acctid FROM caserma WHERE ".$corpo;
    $resultmail = db_query($sqlmail);
    $countrow = db_num_rows($resultmail);
    for ($imail=0; $imail<$countrow; $imail++){
    	$rowmail = db_fetch_assoc($resultmail);
        systemmail($rowmail['acctid'],"`^Messaggio del Comandante della Caserma!`0",$body,$session['user']['acctid']);
    }
    addnav("Torna alla Sala Comando", "caserma.php?op=comando");
}
//Hugues: fine Mail massive
    
if ($_GET['op']=="comando") {
    $sqlpic = "SELECT acctid FROM accounts WHERE dove_sei=7";
    $resultpic = db_query($sqlpic);
    $player_caserma = db_num_rows($resultpic);
    savesetting("player_caserma", $player_caserma);
    $tempo_prossimo_attacco=time()-getsetting("attacco_eytgim","");
    if(getsetting("comandante_caserma","0")==$session['user']['acctid'] or $session['user']['superuser']>2){
        output("`0<form action=caserma.php?op=comando method='POST'>",true);
        output("[Comandante] Inserisci Notizia? <input name='testo'> ",true);
        output("<input type='submit' class='button' value='Insert'>`n`n",true);
        output("</form>",true);
        
        //Hugues: Mail massive
        addnav("Mail globali");
        addnav("Invia Mail a tutti i soldati della Fanteria","caserma.php?op=mail&az=1");
        addnav("Invia Mail a tutti i soldati della Cavalleria","caserma.php?op=mail&az=2");
        addnav("Invia Mail a tutti i soldati del Genio","caserma.php?op=mail&az=3");
        //Hugues: Mail massive  
   
        addnav("", "caserma.php?op=comando");
    }
    output("`0In caserma sono presenti: ".getsetting("player_caserma", 0)." soldati.`n`n");
    output("`0Tempo necessario riorganizzazione fino: ".date('d/m/y H:i',getsetting("attacco_eytgim","")+432000)."`n");
    $relazione=getsetting("relazione_eythgim",50);
    if ($relazione<30){
        $att='on';
//        if(($session['user']['superuser']>3 OR getsetting("comandante_caserma","0")==$session['user']['acctid']) AND $tempo_prossimo_attacco>432000){
//            addnav("Ordina attacco", "caserma.php?op=attacco");
//        }
        $rel="`\$GUERRA";
    }elseif ($relazione>=30 AND $relazione<40){
        $rel="`6TENSIONE";
    }elseif ($relazione>=40 AND $relazione<60){
        $rel="`vCOLLABORAZIONE";
    }elseif ($relazione>=60){
        $rel="`#PACE";
    }
    if(getsetting("campagna_militare","off")=="on" AND ($session['user']['superuser']>3 OR getsetting("comandante_caserma","0")==$session['user']['acctid']) AND $tempo_prossimo_attacco>432000){
        addnav("Ordina attacco", "caserma.php?op=attacco");
    }
    if(time()-getsetting("attacco_eytgim","")<150 AND $row[attacco_eythgim]=='No')
    {
        addnav("Partecipa attacco","caserma.php?op=partecipa_attacco");
    }else{
        $sqlupdate = "UPDATE caserma SET attacco_eythgim = 'No' WHERE acctid='".$session['user']['acctid']."'";
        db_query($sqlupdate) or die(db_error(LINK));
        addnav("Risultato battaglia", "caserma.php?op=risultato");
    }
    output("`n`n`@Relazioni città di Eythgim: $rel`n");
    output("`n`n`%`@Alcuni militari parlano nelle vicinanze:`n");
    viewcommentary("caserma","Aggiungi",25,20);
    addnav("Entrata caserma", "caserma.php");
}
if ($_GET['op']=='risultato') {
    addnav("Sala comando", "caserma.php?op=comando");
    output("Sconfitte :".getsetting("attacchi_persi","")."`n");
    output("Vittorie :".getsetting("attacchi_vinti",""));

}
if ($_GET['op']=="attacco") {
    output("`n`n`@Sei sicuro di voler attaccare la città di Eythgim, questo degraderà ulteriormente le relazioni!`n");
    output("`n`n`@Puoi lanciare un attacco ogni 5 giorni!`n");
    addnav("Conferma attacco", "caserma.php?op=attacco_si");
    addnav("Sala comando", "caserma.php?op=comando");
}
if ($_GET['op']=="attacco_si") {
     savesetting("relazione_eythgim",getsetting("relazione_eythgim",50)-25);
    savesetting("attacchi_vinti","0");
    savesetting("attacchi_persi","0");
    output("`n`n`@Hai dato il via all'attacco, nei prossimi 2 minuti puoi radunare il tuo esercito !`n");
    savesetting("attacco_eytgim","".time()."");
    savesetting("campagna_militare","off");
    addnav("Partecipa attacco","caserma.php?op=partecipa_attacco");
    addnav("Sala comando", "caserma.php?op=comando");
    //AGGIUNGERE CALCOLI SU EQUIPAGGIAMENTO
    $sql = "SELECT SUM(attacco) AS attacco,SUM(difesa) AS difesa,SUM(strategia) AS strategia FROM caserma";
    $result = db_query($sql) or die(db_error(LINK));
    $row = db_fetch_assoc($result);
    $sql = "SELECT * FROM caserma_equipaggiamento";
    $resultc = db_query($sql) or die(db_error(LINK));
    $countrow = db_num_rows($resultc);
    for ($i=0; $i<$countrow; $i++){
    //for ($i = 0;$i < db_num_rows($resultc);$i++) {
        $rowc = db_fetch_assoc($resultc);
        $att+=$rowc[qta]*$rowc[bonus_att];
        $dif+=$rowc[qta]*$rowc[bonus_dif];
        $str=$rowc[qta]*$rowc[bonus_str];
    }
    $attacco=$row[attacco]+$att;
    $difesa=$row[difesa]+$dif;
    $strategia=$row[strategia]+$str;
    savesetting("eythgim_att","".e_rand(intval($attacco*0.8), intval($attacco*1.2))."");
    savesetting("eythgim_dif","".e_rand(intval($difesa*0.8), intval($difesa*1.2))."");
    savesetting("eythgim_str","".e_rand(intval($strategia*0.8), intval($strategia*1.2))."");
    savesetting("eythgim_de","".e_rand(intval($player_caserma*0.8), intval($player_caserma*1.2))."");
    savesetting("rafflingate_att",$attacco);
    savesetting("rafflingate_dif",$difesa);
    savesetting("rafflingate_str",$strategia);
    savesetting("rafflingate_de",$player_caserma);
}
if ($_GET['op']=="partecipa_attacco") {
    $sqlupdate = "UPDATE caserma SET attacco_eythgim = 'Si' WHERE acctid='".$session['user']['acctid']."'";
    db_query($sqlupdate) or die(db_error(LINK));

    output("`n`n<table cellspacing=0 cellpadding=2 align='center'>", true);
    output("<tr class='trhead' align='center'><td>`bEsercito`b</td><td>`bAttacco`b</td><td>`bDifesa`b</td><td>`bStrategia`b</td><td>`bDimensione esercito`b</td><td>`bForza esercito`b</td></tr>", true);
    output("<tr class='trdark'><td>Rafflingate</td>",true);
    output("<td align='center'>".getsetting("rafflingate_att","")."</td>",true);
    output("<td align='center'>".getsetting("rafflingate_dif","")."</td>",true);
    output("<td align='center'>".getsetting("rafflingate_str","")."</td>",true);
    output("<td align='center'>".getsetting("rafflingate_de","")."</td>",true);
    $forza1=intval(getsetting("rafflingate_att","")*getsetting("rafflingate_str","")*getsetting("rafflingate_de",""));
    output("<td align='center'>".$forza1."</td></tr>",true);
    output("<tr class='trlight'><td>Eytghim</td>",true);
    output("<td align='center'>".getsetting("eythgim_att","")."</td>",true);
    output("<td align='center'>".getsetting("eythgim_dif","")."</td>",true);
    output("<td align='center'>".getsetting("eythgim_str","")."</td>",true);
    output("<td align='center'>".getsetting("eythgim_de","")."</td>",true);
    $forza2=intval(getsetting("eythgim_att","")*getsetting("eythgim_str","")*getsetting("eythgim_de",""));
    output("<td align='center'>".$forza2."",true);
    output("</td></tr>", true);
    output("</table>", true);
    $forza1=intval($forza1-(getsetting("eythgim_dif","")*getsetting("eythgim_str","")*getsetting("eythgim_de","")));
    $forza2=intval($forza2-(getsetting("rafflingate_dif","")*getsetting("rafflingate_str","")*getsetting("rafflingate_de","")));
    //iniziativa
    if ($forza1>$forza2){
        $az='vinto';
        $buff = array("name"=>"`\$Furia di guerra`0",
        "atkmod"=>1.4,
        "survivenewday"=>1,
        "rounds"=>-1,
        "roundmsg"=>"`\$Lo scontro ti fa combattere ferocemente!`0",
        "activate"=>"offense"
        );
        $session['bufflist']['barbaro']=$buff;
    }else{
        $az='perso';
    }
    addnav("Lanciati attacco","caserma.php?op=lanciati_attacco&az=$az");
}
if ($_GET['op']=='bottino') {
    $go=e_rand(5000,10000);
    output("`3Hai trovato $go monete d'oro`n");
    $session['user']['gold']+=$go;
    debuglog("ha raccolto un bottino di ".$go." pezzi d'oro attaccando il paese di Eythgim.");
    addnav("Torna caserma", "caserma.php");
}
if ($_GET['op']=="fanteria") {
    if(!$row[scuola]){
        output("`3Un `#nano`3 pieno di cicatrici sta raccontando ad alcuni allievi cosa significa essere un `\$fante.`n");
        output("`3Inizi ad ascoltarlo: `vEssere un `\$fante`v è il massimo, la `\$Fanteria`v è la miglior scuola militare, un corpo dal quale nascono gli eroi e le leggende!`n");
        output("La `\$Fanteria`v è la spina dorsale dell'esercito, siamo noi che aspettiamo a piè fermo i nemici e con il nostro coraggio li sbaragliamo, facendo loro pentire di essere nati, non quegli schizzinosi con le orecchie a punta che si limitano ad urlare a vanvera stando ben comodi sui loro ronzini.`n");

        addnav("Diventa un fante", "caserma.php?op=diventa_fante");
    }elseif($row[scuola]=='Fanteria'){
        output("`3Il `#nano Gog Lancia di Ferro del Clan delle Lunghe Picche della Stirpe dei Secondi`3, uno degli eroi più valorosi di `@Rafflingate`3, ti saluta mentre sta raccontando alcune leggende della `\$Fanteria `3a dei novellini.`n");
        addnav("Equipaggiamento", "caserma.php?op=equipaggiamento");
        addnav("Corsi", "caserma.php?op=corsi");
        addnav("Addestramento", "caserma.php?op=addestramento");
        addnav("Abbandona fanteria", "caserma.php?op=abbandona_fanteria");
    }else{
    	if(getsetting("comandante_caserma","0")==$session['user']['acctid']){
    		output("`3Il `#nano Gog Lancia di Ferro del Clan delle Lunghe Picche della Stirpe dei Secondi`3, uno degli eroi più valorosi di `@Rafflingate`3, saluta il suo `6Comandante`3, rendendoti onore con la sua smisurata arma e poi prosegue a raccontare alcune delle leggende della `\$Fanteria `3 ad un manipolo di novellini.`n");
    		addnav("Equipaggiamento", "caserma.php?op=equipaggiamento");
    	}else{	
        	output("`3Un `#nano`3 pieno di cicatrici ti dice : `v`Essere un `\$fante`v è il massimo, la `\$Fanteria`v è il miglior corpo militare!`n");
        }
    }
    addnav("Entrata caserma", "caserma.php");
}
if ($_GET['op']=="diventa_fante") {
    output("`3Squadrandoti ben bene il `#nano`3 ti domanda: `vSei sicuro di voler far parte della nostra gloriosa `\$Fanteria?`n");
    addnav("Si", "caserma.php?op=diventa_fante_si");
    addnav("No", "caserma.php");
}
if ($_GET['op']=="diventa_fante_si") {
     output("`3Con una gran pacca sulla schiena che ti toglie il fiato il `#nano`3 si congratula con te: `v`Ben fatto soldato! Ora puoi andare ovunque a vantarti, dicendo che sei fiero di far parte della `\$Fanteria!`n");
    $sql = "INSERT INTO caserma (acctid,scuola,grado) VALUES ('".$session['user']['acctid']."','Fanteria','Soldato')";
    $result=db_query($sql);
    addnav("Entrata caserma", "caserma.php");
}
if ($_GET['op']=="cavalleria") {
    if(!$row[scuola]){
        output("`3Un `^elfo`3 dall'aria altezzosa, ti squadra quasi con disgusto.`n");
        output("`3Inizi ad ascoltarlo: `vLa `^Cavalleria`v è il miglior corpo militare, è l'arma più nobile che permette di vincere le battaglie!`n");
        output("Destrezza e velocità sono le armi che ci consentono di sbaragliare i nemici e rendono la `^Cavalleria`v il corpo più temuto in tutto il mondo conosciuto!`n");
        addnav("Diventa un cavaliere", "caserma.php?op=diventa_cavaliere");
    }elseif($row[scuola]=='Cavalleria'){
        output("`3L'`^elfo Arendur`3 leggendario cavaliere degli `^Alti elfi`3 ti saluta mentre è occupato a strigliare il suo cavallo!`n");
        addnav("Equipaggiamento", "caserma.php?op=equipaggiamento");
        addnav("Corsi", "caserma.php?op=corsi");
        addnav("Addestramento", "caserma.php?op=addestramento");
        addnav("Abbandona cavalleria", "caserma.php?op=abbandona_cavalleria");
    }else{
    	if(getsetting("comandante_caserma","0")==$session['user']['acctid']){
    		output("`3L'`^elfo Arendur`3 leggendario cavaliere degli `^Alti elfi`3 ti saluta in qualità di `6Comandante della Caserma`3 e poi prosegue a strigliare il suo cavallo.`n");
    		addnav("Equipaggiamento", "caserma.php?op=equipaggiamento");
    	}else{	
        	output("`3Un elfo altezzoso ti dice:\"`vVattene non possono stare quì gli inferiori appartenenti agli altri corpi!`3\"`n");
        }
    }
    addnav("Entrata caserma", "caserma.php");
}
if ($_GET['op']=="diventa_cavaliere") {
    output("`3L'`^elfo`3: `vSei sicuro di voler far parte del nobile corpo della `^Cavalleria? `n");
    addnav("Si", "caserma.php?op=diventa_cavaliere_si");
    addnav("No", "caserma.php");
}
if ($_GET['op']=="diventa_cavaliere_si") {
    output("`3L'`^elfo ti lancia un sorriso`3: `vBen fatto, ora sei un cavaliere, comportati secondo il codice della `^Cavalleria!`n");
    $sql = "INSERT INTO caserma (acctid,scuola,grado) VALUES ('".$session['user']['acctid']."','Cavalleria','Soldato')";
    $result=db_query($sql);
    addnav("Entrata caserma", "caserma.php");
}
if ($_GET['op']=="genio") {
    if(!$row[scuola]){
        output("`3Un `&umano`3 del Corpo del `8Genio`3 sta lucidando una grossa catapulta.`n");
        output("`3Inizi ad ascoltarlo: `vNoi del `8Genio`v siamo fuori dalle scaramucce tra `\$fanti`v e `^cavalieri`v, noi usiamo l'ingegno per vincere le battaglie!`n");
        addnav("Diventa un geniere", "caserma.php?op=diventa_geniere");
    }elseif($row[scuola]=='Genio'){
        output("`3Un `&umano`3 del corpo dei `8Genieri`3ti saluta mentre sta riparando una ballista.`n");
        addnav("Equipaggiamento", "caserma.php?op=equipaggiamento");
        addnav("Corsi", "caserma.php?op=corsi");
        addnav("Addestramento", "caserma.php?op=addestramento");
        addnav("Abbandona il genio", "caserma.php?op=abbandona_genio");
    }else{
    	if(getsetting("comandante_caserma","0")==$session['user']['acctid']){
    		output("`3Un `&umano`3 scatta sull'attenti all'avvicinarsi del `6Comandante della Caserma`3 e poi prosegue nel suo lavoro di riparazione di una ballista.`n");
    		addnav("Equipaggiamento", "caserma.php?op=equipaggiamento");
    	}else{	
        	output("`3Un umano ti dice:\"`vVuoi diventare un geniere hai capito finalmente ....!`3\"`n");
        }
    }
    addnav("Entrata caserma", "caserma.php");
}
if ($_GET['op']=="diventa_geniere") {
    output("`3L'`&umano`3: `vSei proprio sicuro di voler diventare un `8geniere? `n");
    addnav("Si", "caserma.php?op=diventa_geniere_si");
    addnav("No", "caserma.php");
}
if ($_GET['op']=="diventa_geniere_si") {
    output("`3Congratulandosi con te l'`&umano`3 ti lancia un martello da falegname: `vBen fatto pivellino, ora sei un `8geniere!`n");
    $sql = "INSERT INTO caserma (acctid,scuola,grado) VALUES ('".$session['user']['acctid']."','Genio','Soldato')";
    $result=db_query($sql);
    addnav("Entrata caserma", "caserma.php");
}

if ($_GET['op']=="equipaggiamento") {
    output("`n`n<table cellspacing=0 cellpadding=2 align='center'>", true);
    output("<tr class='trhead' align='center'><td>`bNome`b</td><td>`bQuantità`b</td><td>`bOro`b</td>
    <td>`bOntano`b</td><td>`bBetulla`b</td><td>`bZolfo`b</td><td>`bCarbone`b</td><td>`bRame`b</td><td>`bFerro`b</td><td>`bOps`b</td></tr>", true);
    $sql = "SELECT * FROM caserma_equipaggiamento WHERE corpo='$row[scuola]'";
    $result = db_query($sql) or die(db_error(LINK));
    $countrow = db_num_rows($result);
    for ($i=0; $i<$countrow; $i++){
        $rowcf = db_fetch_assoc($result);
        output("<tr class='" . ($i % 2?"trlight":"trdark") . "'><td><A href=caserma.php?op=compra_equipaggiamento&id=$rowcf[id]>$rowcf[nome]</a></td>",true);
        addnav("", "caserma.php?op=compra_equipaggiamento&id=$rowcf[id]");
        output("<td>$rowcf[qta]</td>",true);
        output("<td align='center'>$rowcf[oro]",true);
        output("<td align='center'>$rowcf[ontano]",true);
        output("<td align='center'>$rowcf[betulla]",true);
        output("<td align='center'>$rowcf[zolfo]",true);
        output("<td align='center'>$rowcf[carbone]",true);
        output("<td align='center'>$rowcf[rame]",true);
        output("<td align='center'>$rowcf[ferro]",true);
        output("<td align='center'><A href=caserma.php?op=compra_equipaggiamento&id=$rowcf[id]>Compra</a>",true);
        output("</td></tr>", true);
    }
    output("</table>", true);

    // Inizio tabella Zaino
    output("`n<table cellspacing=0 cellpadding=2 align='center'>", true);
    output("<tr class='trhead'><td colspan=2 align='center'>`bIl tuo Zaino`b</td><td align='center'>`bQuantità`b</td></tr>", true);

    // Selezione Materiali Player
    $sqlmateriali = "SELECT materiali.id AS idmateriali, materiali.nome AS nome, materiali.valoremo AS valoremo, materiali.valorege AS valorege,
                     materiali.descrizione AS descrizione,COUNT(materiali.id) AS qta FROM zaino, materiali WHERE zaino.idplayer = '".$session['user']['acctid']."' AND zaino.idoggetto = materiali.id GROUP BY materiali.id";
    $resultmateriali = db_query($sqlmateriali ) or die(db_error(LINK));

    if (db_num_rows($resultmateriali) == 0) {
        output("<tr class='trhead'><td colspan=2 align='center'>`&Non hai oggetti nello zaino`0</td></tr>", true);
    }
    $countrow = db_num_rows($resultmateriali);
    for ($i=0; $i<$countrow; $i++){
    //for ($i = 0;$i < db_num_rows($resultmateriali);$i++) {
        $row = db_fetch_assoc($resultmateriali);
        output("<tr class='" . ($i % 2?"trlight":"trdark") . "'><td>" . ($i + 1) . ".</td><td>$row[nome]</td><td>$row[qta]</td></tr>", true);

    }
    output("</table>", true);
    // Fine tabella Zaino
    addnav("Entrata caserma", "caserma.php");
}
if ($_GET['op']=="compra_equipaggiamento") {
    $id_materiali=array(24,26,21,3,2,1);
    $nome_materiale=array('ontano','betulla','zolfo','carbone','rame','ferro');
    $sql = "SELECT * FROM caserma_equipaggiamento WHERE id='$_GET[id]'";
    $result = db_query($sql) or die(db_error(LINK));
    $rowcf = db_fetch_assoc($result);
    output("$rowcf[nome]`n");
    //NUOVO SCRIPT
    for($im=0;$im< count($id_materiali);$im++){
        $nom_mat=$nome_materiale[$im];
        $id_mat=$id_materiali[$im];
        $sql = "SELECT COUNT(id) AS tot FROM zaino WHERE idoggetto='$id_mat' AND idplayer='".$session['user']['acctid']."' GROUP BY idoggetto";
        $result = db_query($sql) or die(db_error(LINK));
        $rowz = db_fetch_assoc($result);
        if($rowcf[$nom_mat]>0){
            if($rowz[tot]>=$rowcf[$nom_mat]){
                $ex='';
                for($z=0;$z<$rowcf[$nom_mat];$z++){
                    $sql = "SELECT id FROM zaino WHERE idplayer='".$session['user']['acctid']."' AND idoggetto='$id_mat'".$ex;
                    $resultza = db_query($sql) or die(db_error(LINK));
                    $rowza = db_fetch_assoc($resultza);
                    if(db_num_rows($resultza)>0){
                        $id_zaino[]=$rowza[id];
                        $ex.=" AND id!='".$rowza[id]."'";
                    }else{
                        $ko='ko';
                    }
                }
            }else{
                $ko='ko';
            }
        }
    }
    if($rowcf[oro]>0){
        if($session[user][gold]<$rowcf[oro]){
            $ko='ko';
        }
    }
    if($ko!='ko'){
        output ("Bene hai tutto il necessario.`n`n");
        foreach($id_zaino AS $idz){
            $sqle = "DELETE FROM zaino WHERE id='".$idz."'";
            db_query($sqle);
        }
        $sql = "UPDATE caserma_equipaggiamento SET qta=qta+1 WHERE id = ".$_GET['id']."";
        $result = db_query($sql) or die(db_error(LINK));
        $session[user][gold]-=$rowcf[oro];
    }else{
        output("`3Non hai i materiali necessari!`n");
    }
    addnav("Entrata caserma", "caserma.php");
}

if ($_GET['op']=="effettivi") {

    //Excalibur: cancellazione player che non esistono più nella tabella accounts
    $sqlcanc = "SELECT c.acctid
    FROM caserma c
    LEFT JOIN accounts a
    USING (acctid)
    WHERE a.acctid IS NULL
    ORDER BY c.acctid ASC";
    $resultcanc = db_query($sqlcanc) or die(db_error(LINK));
    if (db_num_rows($resultcanc) != 0){
        $countrow = db_num_rows($resultcanc);
        for ($i=0; $i<$countrow; $i++){
        //for ($i = 0; $i < db_num_rows($resultcanc); $i++){
            $rowcanc = db_fetch_assoc($resultcanc);
            $sqlcanc1 = "DELETE FROM caserma WHERE acctid = ".$rowcanc['acctid'];
            $resultcanc1 = db_query($sqlcanc1) or die(db_error(LINK));
        }
    }
    //Excalibur: fine cancellazione

    //elezione comandante
    $sql = "SELECT * FROM caserma order by grado DESC,strategia+attacco+difesa DESC limit 1";
    $result = db_query($sql) or die(db_error(LINK));
    $rowcf = db_fetch_assoc($result);
    savesetting("comandante_caserma","$rowcf[acctid]");
    $sql = "SELECT login FROM accounts WHERE acctid = '".$rowcf['acctid']."'";
    $resultc = db_query($sql) or die(db_error(LINK));
    $rowc = db_fetch_assoc($resultc);
    savesetting("comandante_nome","$rowc[login]");
    savesetting("comandante_grado","$rowcf[grado]");
    output("`n`n<table cellspacing=0 cellpadding=2 align='center'>", true);
    output("<tr class='trhead' align='center'><td>`bComandante caserma`b</td><td>`bGrado`b</td><td>`bCorpo`b</td></tr>", true);
    output("<tr class='trdark'><td>$rowc[login]</td>",true);
    output("<td>$rowcf[grado]</td>",true);
    output("<td align='center'>$rowcf[scuola]",true);
    output("</td></tr>", true);
    output("</table>", true);
    $sql = "SELECT scuola,grado,count(id) AS totale FROM caserma group by scuola,grado order by scuola,grado DESC,totale";
    $result = db_query($sql) or die(db_error(LINK));
    output("`n`n<table cellspacing=0 cellpadding=2 align='center'>", true);
    output("<tr class='trhead' align='center'><td>`bScuola`b</td><td>`bGrado`b</td><td>`bEffettivi`b</td></tr>", true);
    $countrow = db_num_rows($result);
    for ($i=0; $i<$countrow; $i++){
        $rowcf = db_fetch_assoc($result);
        output("<tr class='" . (1 % 2?"trlight":"trdark") . "'><td>$rowcf[scuola]</td>",true);
        output("<td>$rowcf[grado]</td>",true);
        output("<td align='center'>$rowcf[totale]",true);
        output("</td></tr>", true);
    }
    output("</table>", true);
    $sql = "SELECT * FROM caserma order by strategia+attacco+difesa DESC limit 10";
    $result = db_query($sql) or die(db_error(LINK));
    output("`n`n<table cellspacing=0 cellpadding=2 align='center'>", true);
    output("<tr class='trhead' align='center'><td>`bNome`b</td><td>`bGrado`b</td><td>`bCorpo`b</td></tr>", true);
    $countrow = db_num_rows($result);
    for ($i=0; $i<$countrow; $i++){
        $rowcf = db_fetch_assoc($result);
        $sql = "SELECT login FROM accounts WHERE acctid = '".$rowcf['acctid']."'";
        $resultc = db_query($sql) or die(db_error(LINK));
        $rowc = db_fetch_assoc($resultc);
        output("<tr class='" . (1 % 2?"trlight":"trdark") . "'><td>$rowc[login]</td>",true);
        output("<td>$rowcf[grado]</td>",true);
        output("<td align='center'>$rowcf[scuola]",true);
        output("</td></tr>", true);

    }
    output("</table>", true);
    addnav("Entrata caserma", "caserma.php");
}
if ($_GET['op']=="corsi") {
    if($row[corso]!=0){
        $sql = "SELECT * FROM caserma_corsi WHERE id = '".$row['corso']."'";
        $resultc = db_query($sql) or die(db_error(LINK));
        $rowc = db_fetch_assoc($resultc);
        output("`3Stai frequentando il corso : `v$rowc[nome] ($rowc[livello])`n");
        output("`3Lo devi frequentare ancora per  : `v$row[durata_corso] giorni `3(newday)`n");
    }else{
        //corsi disponibili
        $sql = "SELECT id_corso FROM caserma_corsi_fatti WHERE acctid = '".$session['user']['acctid']."'";
        $result = db_query($sql) or die(db_error(LINK));
        output("`n`n<table cellspacing=0 cellpadding=2 align='center'>", true);
        output("<tr class='trhead' align='center'><td>`bNome corso disponibile`b</td><td>`bDurata corso`b</td><td>`bInfo`b</td><td>`bIscriviti`b</td></tr>", true);
        if(db_num_rows($result)==0){
            $sql = "SELECT id,nome,tempo,livello FROM caserma_corsi WHERE id = '1'";
            $resultc = db_query($sql) or die(db_error(LINK));
            $rowc = db_fetch_assoc($resultc);
            output("<tr class='trlight'><td>$rowc[nome] ($rowc[livello])</td>",true);
            output("<td>$rowc[tempo]</td>",true);
            output("<td align='center'><A href=caserma.php?op=info_corso&id=$rowc[id]>Info </a></td>",true);
            addnav("","caserma.php?op=info_corso&id=$rowc[id]");
            output("<td align='center'><A href=caserma.php?op=corso_studia&id=$rowc[id]>Iscriviti </a>",true);
            addnav("", "caserma.php?op=corso_studia&id=$rowc[id]");
            output("</td></tr>", true);
        }
        $countrow = db_num_rows($result);
        for ($i=0; $i<$countrow; $i++){
            $rowcf = db_fetch_assoc($result);
            $sql = "SELECT id,nome,tempo,livello FROM caserma_corsi WHERE aperto_da = '".$rowcf['id_corso']."'";
            $resultc = db_query($sql) or die(db_error(LINK));
            $countrow1 = db_num_rows($resultc);
            for ($z=0; $z<$countrow1; $z++){
                $rowc = db_fetch_assoc($resultc);
                $sql = "SELECT id FROM caserma_corsi_fatti WHERE id_corso = '".$rowc['id']."' AND acctid = '".$session['user']['acctid']."'";
                $resultf = db_query($sql) or die(db_error(LINK));
                //output("<tr class='trlight'><td>".db_num_rows($resultf)."</td></tr>",true);
                if(db_num_rows($resultf)==0){
                    output("<tr class='trlight'><td>$rowc[nome] ($rowc[livello])</td>",true);
                    output("<td>$rowc[tempo]</td>",true);
                    output("<td align='center'><A href=caserma.php?op=info_corso&id=$rowc[id]>Info </a></td>",true);
                    addnav("","caserma.php?op=info_corso&id=$rowc[id]");
                    output("<td align='center'><A href=caserma.php?op=corso_studia&id=$rowc[id]>Iscriviti </a>",true);
                    addnav("", "caserma.php?op=corso_studia&id=$rowc[id]");
                    output("</td></tr>", true);
                }

            }
        }
        output("</table>", true);
    }
    if($row[durata_corso]>=3)addnav("Lezione privata", "caserma.php?op=lezione_privata");
    addnav("Corsi superati", "caserma.php?op=corsi_fatti");
    addnav("Entrata caserma", "caserma.php");
}
if ($_GET['op']=="corsi_fatti") {
    //corsi fatti
    $sql = "SELECT id_corso FROM caserma_corsi_fatti WHERE acctid = '".$session['user']['acctid']."'";
    $result = db_query($sql) or die(db_error(LINK));
    output("`n`n<table cellspacing=0 cellpadding=2 align='center'>", true);
    output("<tr class='trhead' align='center'><td>`bNome corso completato`b</td><td>`bDurata corso`b</td><td>`bInfo`b</td></tr>", true);
    $countrow = db_num_rows($result);
    for ($i=0; $i<$countrow; $i++){
        $rowcf = db_fetch_assoc($result);
        $sql = "SELECT id,nome,tempo,livello FROM caserma_corsi WHERE id = '".$rowcf['id_corso']."'";
        $resultc = db_query($sql) or die(db_error(LINK));
        $rowc = db_fetch_assoc($resultc);
        output("<tr class='trlight'><td>$rowc[nome] ($rowc[livello])</td>",true);
        output("<td>$rowc[tempo]</td>",true);
        output("<td align='center'><A href=caserma.php?op=info_corso&id=$rowc[id]>Info </a>",true);
        addnav("","caserma.php?op=info_corso&id=$rowc[id]");
        output("</td></tr>", true);
    }
    output("</table>", true);
    addnav("Sala corsi", "caserma.php?op=corsi");
    addnav("Entrata caserma", "caserma.php");
}
if ($_GET['op']=="info_corso") {
    $sql = "SELECT * FROM caserma_corsi WHERE id = '".$_GET['id']."'";
    $resultc = db_query($sql) or die(db_error(LINK));
    $rowc = db_fetch_assoc($resultc);
    output("`2Nome corso : `6$rowc[nome]`3`n");
    output("`2Livello corso : `6$rowc[livello]`3`n");
    output("`2Durata corso : `6$rowc[tempo]`3 (newday)`n");
    output("`2Descrizione : `6$rowc[descrizione]`3`n");
    output("`2Miglioramento caratteristiche`n");
    if($rowc[attacco]>=0.75){
        $testo='Alto';
    }elseif($rowc[attacco]<0.1){
        $testo='Basso';
    }else{
        $testo='Medio';
    }
    output("`2Attacco : `6$testo`3`n");
    if($rowc[difesa]>=0.75){
        $testo='Alto';
    }elseif($rowc[difesa]<0.1){
        $testo='Basso';
    }else{
        $testo='Medio';
    }
    output("`2Difesa : `6$testo`3`n");
    if($rowc[strategia]>=0.75){
        $testo='Alto';
    }elseif($rowc[strategia]<0.1){
        $testo='Basso';
    }else{
        $testo='Medio';
    }
    output("`2Strategia : `6$testo`3`n");
    $sql = "SELECT id,nome,tempo,livello FROM caserma_corsi WHERE aperto_da = '".$rowc['id']."'";
    $resultc = db_query($sql) or die(db_error(LINK));
    output("`n`n<table cellspacing=0 cellpadding=2 align='center'>", true);
    output("<tr class='trhead' align='center'><td>`bNome corso sbloccato`b</td><td>`bDurata corso`b</td><td>`bInfo`b</td></tr>", true);

    $countrow1 = db_num_rows($resultc);
    for ($z=0; $z<$countrow1; $z++){
        $rowc = db_fetch_assoc($resultc);
        output("<tr class='trlight'><td>$rowc[nome] ($rowc[livello])</td>",true);
        output("<td>$rowc[tempo]</td>",true);
        output("<td align='center'><A href=caserma.php?op=info_corso&id=$rowc[id]>Info </a>",true);
        addnav("","caserma.php?op=info_corso&id=$rowc[id]");
        output("</td></tr>", true);
    }
    output("</table>", true);
    addnav("Indietro", "caserma.php?op=corsi");

}
if ($_GET['op']=="corso_studia") {
    $sql = "SELECT * FROM caserma_corsi WHERE id = '".$_GET[id]."'";
    $resultc = db_query($sql) or die(db_error(LINK));
    $rowc = db_fetch_assoc($resultc);
    $sqlupdate = "UPDATE caserma SET corso = '".$_GET[id]."',durata_corso = '".$rowc[tempo]."' WHERE acctid='".$session['user']['acctid']."'";
    db_query($sqlupdate) or die(db_error(LINK));
    output("`2Ti sei iscritto al corso `n");
    addnav("Indietro", "caserma.php?op=corsi");
}
if ($_GET['op']=="lezione_privata") {
    output("`2Una lezione privata ti può far risparmiare alcuni giorni, ma l'esito non è garantito! (costa 500 punti carriera) `n");
    if($session['user']['punti_carriera']>500){
        if($row[lezione_privata]=='No'){
            addnav("Partecipa lezione", "caserma.php?op=paga_lezione");
        }else{
            output("`6Puoi prendere una sola lezione al giorno! `n");
        }
    }else{
        output("`6Non hai abbastanza punti carriera! `n");
    }
    addnav("Sala corsi", "caserma.php?op=corsi");
}
if ($_GET['op']=="paga_lezione") {
    $sql = "SELECT * FROM caserma_corsi_fatti WHERE acctid = '".$session['user']['acctid']."'";
    $result = db_query($sql) or die(db_error(LINK));
    $rowcf = db_fetch_assoc($result);
    $session['user']['punti_carriera']-=500;
    debuglog("paga 500 punti carriera per una lezione privata in Caserma.");
    $caso = e_rand(1, 30);
    if($caso==1){
        $bonus=0;
        $frase='Purtroppo non sei stato attento, non hai risparmiato giorni';
    }elseif($caso>1 AND $caso<26){
        $bonus=1;
        $frase='Il corso è stato interessante, hai risparmiato 1 giorno di corso';
    }elseif($caso>25 AND $caso<30){
        $bonus=2;
        $frase='Il corso è stato molto interessante, hai risparmiato 2 giorni di corso';
    }elseif($caso==30){
        $bonus=3;
        $frase='Il corso è stato interessantissimo, hai risparmiato 3 giorno di corso!';
    }
    output("$frase");
    $durata=$row[durata_corso]-$bonus;
    if($durata<1)$durata=1;
    $sqlupdate = "UPDATE caserma SET lezione_privata='Si',durata_corso = '$durata' WHERE acctid='".$session['user']['acctid']."'";
    db_query($sqlupdate) or die(db_error(LINK));
    addnav("Sala corsi", "caserma.php?op=corsi");
}
if ($_GET['op']=="addestramento") {
    output("Benvenuto nel centro addestramento vuoi investire un turno per migliorare nell'arte della guerra?`n`n");
    if($session['user']['turns']>0){
        addnav("Addestramento", "caserma.php?op=addestra_si");
    }else{
        output("Sei troppo stanco per addestrarti ancora!");
    }
    addnav("Entrata caserma", "caserma.php");
}
if ($_GET['op']=="addestra_si") {
    $session['user']['turns']-=1;
    if($session['user']['turns']>0){
        addnav("Addestramento", "caserma.php?op=addestra_si");
    }
    $caso = e_rand(1, 3);
    $dieci = e_rand(1, 10);
    if ($caso==1){
        $text='nella difesa';
        $bonus=1;
        if ($dieci==10)$bonus+=1;
        $difesa=$row[difesa]+$bonus;
        $sqlupdate = "UPDATE caserma SET difesa='$difesa' WHERE acctid='".$session['user']['acctid']."'";
        db_query($sqlupdate) or die(db_error(LINK));
    debuglog("spende 1 turno in Caserma per allenarsi in Difesa.");
    }elseif ($caso==2){
        $text='nella strategia';
        $bonus=1;
        if ($dieci==10)$bonus+=1;
        $strategia=$row[strategia]+$bonus;
        $sqlupdate = "UPDATE caserma SET strategia='$strategia' WHERE acctid='".$session['user']['acctid']."'";
        db_query($sqlupdate) or die(db_error(LINK));
    debuglog("spende 1 turno in Caserma per allenarsi in Strategia.");
    }elseif ($caso==3){
        $text='nell\'attacco';
        $bonus=1;
        if ($dieci==10)$bonus+=1;
        $attacco=$row[attacco]+$bonus;
        $sqlupdate = "UPDATE caserma SET attacco='$attacco' WHERE acctid='".$session['user']['acctid']."'";
        db_query($sqlupdate) or die(db_error(LINK));
    debuglog("spende 1 turno in Caserma per allenarsi in Attacco.");
    }
    output("Dopo esserti addestrato per quasi un'ora, senti che sei migliorato $text!`n`n");
    addnav("Entrata caserma", "caserma.php");
}
if ($_GET['op']=="abbandona_fanteria") {
    output("Sei sicuro di voler abbandonare la fanteria! Perderai tutto quello che hai imparato.");
    addnav("Si", "caserma.php?op=abbandona_si");
    addnav("Entrata caserma", "caserma.php");
}

if ($_GET['op']=="abbandona_cavalleria") {
    output("Sei sicuro di voler abbandonare la cavalleria! Perderai tutto quello che hai imparato.");
    addnav("Si", "caserma.php?op=abbandona_si");
    addnav("Entrata caserma", "caserma.php");
}
if ($_GET['op']=="abbandona_genio") {
    output("Sei sicuro di voler abbandonare il genio! Perderai tutto quello che hai imparato.");
    addnav("Si", "caserma.php?op=abbandona_si");
    addnav("Entrata caserma", "caserma.php");
}

if ($_GET['op']=="abbandona_si") {
    $sql = "delete from caserma WHERE acctid='".$session['user']['acctid']."'";
    db_query($sql) or die(db_error(LINK));
    $sql = "delete from caserma_corsi_fatti WHERE acctid='".$session['user']['acctid']."'";
    db_query($sql) or die(db_error(LINK));
    output("Bene hai abbandonato il tuo vecchio reparto.");
    debuglog("ha abbandonato il suo reparto in Caserma.");
    addnav("Entrata caserma", "caserma.php");
}


if ($_GET['op']=="lanciati_attacco") {
    if (getsetting("comandante_grado",'Soldato')=='Soldato'){
        $caso=rand(1, 20);
    }elseif (getsetting("comandante_grado",'Soldato')=='Sergente'){
        $caso=rand(1, 30);
    }elseif (getsetting("comandante_grado",'Soldato')=='Tenente'){
        $caso=rand(1, 40);
    }elseif (getsetting("comandante_grado",'Soldato')=='Capitano'){
        $caso=rand(1, 50);
    }elseif (getsetting("comandante_grado",'Soldato')=='Maggiore'){
        $caso=rand(1, 60);
    }elseif (getsetting("comandante_grado",'Soldato')=='Colonello'){
        $caso=rand(1, 80);
    }elseif (getsetting("comandante_grado",'Soldato')=='Generale'){
        $caso=rand(1, 100);
    }else{
        $caso=1;
    }


    if($caso<40){
        output("`# Hai incontrato un Soldato di Eythgim!`3`n");
        $badguy = array("creaturename"=>"`&Soldato di Eythgim`3"
        ,"creaturelevel"=>1
        ,"creatureweapon"=>"`7Martello da guerra`3"
        ,"creatureattack"=>2
        ,"creaturedefense"=>0
        ,"diddamage"=>0);
        $userlevel=$session['user']['level'];
        $userattack=$session['user']['attack'];
        $userhealth=$session['user']['maxhitpoints'];
        $userdefense=$session['user']['defense'];
        $badguy['creaturelevel']+=$userlevel;
        $badguy['creatureattack']+=$userattack;
        $badguy['creaturehealth']=intval($userhealth*2.0);
        $badguy['creaturedefense']+=$userdefense;
        $session['user']['badguy']=createstring($badguy);
        addnav("Combatti","caserma.php?op=fight");
        addnav("Scappa","caserma.php?op=run");
    }elseif($caso>=40 AND $caso<50){
        output("`#Hai incontrato un Sergente di Eythgim!`3`n");
        $dkb = round($session['user']['dragonkills']*.15);
        $badguy = array("creaturename"=>"`(Sergente di Eythgim`3"
        ,"creaturelevel"=>2
        ,"creatureweapon"=>"`\$Spadone a due mani`3"
        ,"creatureattack"=>3
        ,"creaturedefense"=>1
        ,"diddamage"=>0);
        $userlevel=$session['user']['level'];
        $userattack=$session['user']['attack'];
        $userhealth=$session['user']['maxhitpoints'];
        $userdefense=$session['user']['defense'];
        $badguy['creaturelevel']+=$userlevel;
        $badguy['creatureattack']+=$userattack;
        $badguy['creaturehealth']=intval($userhealth*2.2);
        $badguy['creaturedefense']+=$userdefense;
        $session['user']['badguy']=createstring($badguy);
        $session['bufflist']['miniera'] = array(
        "startmsg"=>"`n`%La ferita continua a sanguinare quel dannato spadone a due mani è affilatissimo!`n`n",
        "name"=>"`%Ferita di spadone a due mani`3",
        "rounds"=>10,
        "wearoff"=>"La ferita smette di sanguinare.",
        "minioncount"=>10,
        "mingoodguydamage"=>1,
        "maxgoodguydamage"=>2+$dkb,
        "effectmsg"=>"Il sangue perso causa la perdita di {damage} HitPoint.",
        "effectnodmgmsg"=>"Fortunatamente il taglio non ha preso una vena.",
        "activate"=>"roundstart",
        );
        addnav("Combatti","caserma.php?op=fight");
        addnav("Scappa","caserma.php?op=run");
    }elseif($caso>=50 AND $caso<60){
        output("`#Hai incontrato un Tenente di Eythgim!`3`n");
        $dkb = round($session['user']['dragonkills']*.1);
        $badguy = array("creaturename"=>"`@Tenente di Eythgim`3"
        ,"creaturelevel"=>4
        ,"creatureweapon"=>"`2Spada nera magica`3"
        ,"creatureattack"=>4
        ,"creaturedefense"=>2
        ,"creaturehealth"=>2
        ,"diddamage"=>0);
        $userlevel=$session['user']['level'];
        $userattack=$session['user']['attack'];
        $userhealth=$session['user']['maxhitpoints'];
        $userdefense=$session['user']['defense'];
        $badguy['creaturelevel']+=$userlevel;
        $badguy['creatureattack']+=$userattack;
        $badguy['creaturehealth']=intval($userhealth*2.5);
        $badguy['creaturedefense']+=$userdefense;
        $session['user']['badguy']=createstring($badguy);
        $session['bufflist']['miniera'] = array(
        "startmsg"=>"`n`@Il tenente ti ha ferito con la spada nera!`n`n",
        "name"=>"`\$Ferita di spada nera`3",
        "rounds"=>15,
        "wearoff"=>"la ferita si rimargina.",
        "minioncount"=>$session['user']['level'],
        "mingoodguydamage"=>0,
        "maxgoodguydamage"=>3+$dkb,
        "effectmsg"=>"La ferita sanguina per {damage} punti danno.",
        "effectnodmgmsg"=>"La ferita non ti preoccupa.",
        "activate"=>"roundstart",
        );
        addnav("Combatti","caserma.php?op=fight");
        addnav("Scappa","caserma.php?op=run");
    }elseif($caso>=60 AND $caso<70){
        output("`#Hai incontrato un Capitano di Eythgim a cavallo! !`3`n");
        $badguy = array("creaturename"=>"`&Capitano di Eythgim!`3"
        ,"creaturelevel"=>5
        ,"creatureweapon"=>"`&Lancia`3"
        ,"creatureattack"=>5
        ,"creaturedefense"=>5
        ,"creaturehealth"=>2
        ,"diddamage"=>0);
        $userlevel=$session['user']['level'];
        $userattack=$session['user']['attack'];
        $userhealth=$session['user']['maxhitpoints'];
        $userdefense=$session['user']['defense'];
        $badguy['creaturelevel']+=$userlevel;
        $badguy['creatureattack']+=$userattack;
        $badguy['creaturehealth']=intval($userhealth*2.7);
        $badguy['creaturedefense']+=$userdefense;
        $session['user']['badguy']=createstring($badguy);
        addnav("Combatti","caserma.php?op=fight");
        addnav("Scappa","caserma.php?op=run");
    }elseif($caso>=70 AND $caso<80){
        output("`#Hai incontrato un Maggiore di Eythgim del Genio! !`3`n");
        $badguy = array("creaturename"=>"`&Maggiore di Eythgim!`3"
        ,"creaturelevel"=>5
        ,"creatureweapon"=>"`&Azza`3"
        ,"creatureattack"=>5
        ,"creaturedefense"=>5
        ,"creaturehealth"=>2
        ,"diddamage"=>0);
        $userlevel=$session['user']['level'];
        $userattack=$session['user']['attack'];
        $userhealth=$session['user']['maxhitpoints'];
        $userdefense=$session['user']['defense'];
        $badguy['creaturelevel']+=$userlevel;
        $badguy['creatureattack']+=$userattack;
        $badguy['creaturehealth']=intval($userhealth*2.9);
        $badguy['creaturedefense']+=$userdefense;
        $session['user']['badguy']=createstring($badguy);
        addnav("Combatti","caserma.php?op=fight");
        addnav("Scappa","caserma.php?op=run");
    }elseif($caso>=80 AND $caso<90){
        output("`#Hai incontrato un Colonnello di Eythgim a cavallo! !`3`n");
        $badguy = array("creaturename"=>"`&Colonnello di Eythgim!`3"
        ,"creaturelevel"=>5
        ,"creatureweapon"=>"`&Sciabola`3"
        ,"creatureattack"=>5
        ,"creaturedefense"=>5
        ,"creaturehealth"=>2
        ,"diddamage"=>0);
        $userlevel=$session['user']['level'];
        $userattack=$session['user']['attack'];
        $userhealth=$session['user']['maxhitpoints'];
        $userdefense=$session['user']['defense'];
        $badguy['creaturelevel']+=$userlevel;
        $badguy['creatureattack']+=$userattack;
        $badguy['creaturehealth']=intval($userhealth*3.2);
        $badguy['creaturedefense']+=$userdefense;
        $session['user']['badguy']=createstring($badguy);
        addnav("Combatti","caserma.php?op=fight");
        addnav("Scappa","caserma.php?op=run");
    }elseif($caso>=90){
        output("`#Hai incontrato un Generale di Eythgim!!`3`n");
        $dkb = round($session['user']['dragonkills']*.15);
        $badguy = array("creaturename"=>"`\$Generale di Eythgim`3"
        ,"creaturelevel"=>6
        ,"creatureweapon"=>"`\$Spada avvelenata`3"
        ,"creatureattack"=>4
        ,"creaturedefense"=>5
        ,"diddamage"=>0);
        $userlevel=$session['user']['level'];
        $userattack=$session['user']['attack'];
        $userhealth=$session['user']['maxhitpoints'];
        $userdefense=$session['user']['defense'];
        $badguy['creaturelevel']+=$userlevel;
        $badguy['creatureattack']+=$userattack;
        $badguy['creaturehealth']=intval($userhealth*3.5);
        $badguy['creaturedefense']+=$userdefense;
        $session['user']['badguy']=createstring($badguy);
        $session['bufflist']['miniera'] = array(
        "startmsg"=>"`n`%La spada ti ha avvelenato!`n`n",
        "name"=>"`%Veleno`3",
        "rounds"=>10,
        "wearoff"=>"il veleno a esaurito l'effetto.",
        "minioncount"=>1,
        "mingoodguydamage"=>0,
        "maxgoodguydamage"=>2+$dkb+$session['user']['level'],
        "effectmsg"=>"Il veleno si propaga per il corpo causando la perdita di {damage} HitPoint.",
        "effectnodmgmsg"=>"Le tue difese immunitarie riescono a contrastare il veleno.",
        "activate"=>"roundstart",
        );
        addnav("Combatti","caserma.php?op=fight");
        addnav("Scappa","caserma.php?op=run");
    }
}

if ($_GET['op']=='battle' || $_GET['op']=='run') {
    if ($_GET['op']=='run') {
        output("Non riesci a fuggire al nemico!!");
        $_GET['op']="fight";
    }
}
if ($_GET['op']=='fight') {
    $battle=true;
}

if ($battle) {
    include_once("battle.php");
    if($victory) {
        if ($session['user']['hitpoints'] > 0){
            unset($session['bufflist']['miniera']);
            $badguy = createarray($session['user']['badguy']);
            $exp = array(1=>14,26,37,50,61,73,85,98,111,125,140,155,172,189,208,228,250,275,310,348);
            output("Hai battuto `^".$badguy['creaturename'].".`n");
            $guadagno=round($exp[$badguy['creaturelevel']]/2);
            output("Hai guadagnato $guadagno punti esperienza !!!`n");
            addnews("`%".$session['user']['name']."`@ è stato attaccato da ".$badguy['creaturename']. "`@, mentre combatteva per Rafflingate!! E ha vinto!");
            $session['user']['experience']+=$guadagno;
            $session['user']['badguy']="";
            debuglog("guadagna ".$guadagno." esperienza sconfiggendo un nemico in Battaglia contro Eythgim.");

            ////mettere le varie condizioni in base al mostro per tornare alla zona da cui si proveniva
            if($badguy['creaturename']=="`&Soldato di Eythgim`3" OR
            $badguy['creaturename']=="`(Sergente di Eythgim`3" OR
            $badguy['creaturename']=="`@Tenente di Eythgim`3" OR
            $badguy['creaturename']=="`&Capitano di Eythgim!`3" OR
            $badguy['creaturename']=="`&Maggiore di Eythgim!`3" OR
            $badguy['creaturename']=="`&Colonnello di Eythgim!`3" OR
            $badguy['creaturename']=="`\$Generale di Eythgim`3"){
                if(e_rand(1, 5)==1){
                    output("Un altro nemico ti attacca !!!`n");
                    addnav("Combatti","caserma.php?op=lanciati_attacco&az=vinto");
                }else{
                    addnav("Raccogli bottino", "caserma.php?op=bottino");
                }
                savesetting("attacchi_vinti","".(getsetting("attacchi_vinti","")+1)."");
            }
        }else{
            output("`4Sei morto!!`n`n");
            $session['user']['experience']*=0.95;
            $session['user']['badguy']="";
            $session['bufflist']['miniera'] = array();
            $session['user']['alive']=false;
            addnav("Notizie Giornaliere","news.php");
        }
    }else   {
        if ($defeat) {
            $badguy = createarray($session['user']['badguy']);
            output("`4Mentre cadi a terra `^".$badguy['creaturename']. "`4 ride, per quanto `^".$badguy['creaturename']. "`4 possa ridere!`n");
            output("`\$Hai perso il `b`^5%`b`\$ della tua esperienza !!!`n");
            output("Inoltre perdi tutto l'oro che avevi con te !!!`n`n");
            $sql = "SELECT taunt FROM taunts ORDER BY rand(".rand().") LIMIT 1";
            $result = db_query($sql) or die(db_error(LINK));
            $taunt = db_fetch_assoc($result);
            $taunt = str_replace("%s",($session['user']['sex']?"sua":"suo"),$taunt['taunt']);
            $taunt = str_replace("%o",($session['user']['sex']?"lei":"lui"),$taunt);
            $taunt = str_replace("%p",($session['user']['sex']?"her":"his"),$taunt);
            $taunt = str_replace("%x",($session['user']['weapon']),$taunt);
            $taunt = str_replace("%X",$badguy['creatureweapon'],$taunt);
            $taunt = str_replace("%W",$badguy['creaturename'],$taunt);
            $taunt = str_replace("%w",$session['user']['name'],$taunt);
            addnews("`%".$session['user']['name']."`6 è stato ucciso da ".$badguy['creaturename']. "`6, mentre attaccava il paese di Eythgim!!`n$taunt");
            debuglog("è stato ucciso da un ".$badguy['creaturename']. " mentre attaccava il paese di Eythgim. Perde 5% exp e ".$session['user']['gold']." oro");
            addnav("Notizie Giornaliere","news.php");
            $session['user']['experience']*=0.95;
            $session['user']['alive']=false;
            $session['user']['hitpoints']=0;
            $session['user']['gold']=0;
            $session['user']['dove_sei'] = 0;
            savesetting("attacchi_persi","".(getsetting("attacchi_persi","")+1)."");
        }else{
            fightnav(true,false);
        }
    }
}
page_footer();

?>