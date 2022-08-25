<?php
require_once("common.php");
require_once("common2.php");

//luke ogsi stats
$secondi = date("s",time());
$minuti = substr(date("i",time()),1,1);
if( ($secondi > "50" OR $secondi < "10") AND ($minuti ="0" OR $minuti ="5") ){
    $nomedb = getsetting("nomedb","logd");
    $sqlcount = "SELECT acctid FROM accounts";
    $resultcount = db_query($sqlcount) or die(db_error(LINK));
    $totcount = db_num_rows($resultcount);
    $sqlcountonline = "SELECT acctid FROM accounts WHERE loggedin = 1
                       AND laston>'".date("Y-m-d H:i:s",strtotime(date("r")."-".getsetting("LOGINTIMEOUT",900)." seconds"))."'";
    $resultcountonline = db_query($sqlcountonline) or die(db_error(LINK));
    $totcountonline = db_num_rows($resultcountonline);
// commenteato perche non esiste per ora il db xoops
 //   mysql_select_db("xoops") or die ("Il database selezionato non esiste");
    $sqlma = "UPDATE game_users SET online='$totcountonline',registrati='$totcount' WHERE nome='$nomedb'";
//    mysql_query($sqlma) or die("Problema in fase di aggiornamento.<br>SQL : $sqlma");
//    mysql_select_db($nomedb) or die ("Il database selezionato non esiste");
}
// luke ogsi stats
$giorno_chiusura_voto = 20;
if ($session['user']['consono'] == 0) redirect ("attesa.php");
//la riga sottostante DEVE ESSERE == "logd2" nel server ufficiale
if (getsetting("nomedb","logd") == "logd2"){
    if ($session['user']['gdr'] == null) redirect("gdrset.php");
    if ($session['user']['gdr'] == "no" AND $_GET['gdr'] == "") redirect("village1.php?gdr=kkk");
    addnav("1?`%Piazza Normale","village1.php?gdr=kkk");
}
if(moduli('stanzegdr')=='on')addnav("&?Stanze GDR","stanzegdr.php");

addcommentary();
checkday();
$session['user']['turni_drago']=$session['user']['reincarna']+1;
$session['user']['dove_sei'] = 0;
if ($_GET['op']=="simanu") {
    savesetting("manutenzione",1);
    output("`b`^Messo il gioco in Manutenzione`b`0");
}
if ($_GET['op']=="nomanu") {
    savesetting("manutenzione",3);
    output("`b`^Tolta la Manutenzione`b`0");
}
if ($session['user']['jail'] > 0) redirect("constable.php?op=twiddle");

if ($session['user']['alive']){ }else{
    redirect("shades.php");
}
//Excalibur: modifica per compleanno
if ($session['user']['compleanno'] == "0000-00-00") redirect ("compleanno.php");
//Modifica PvP Online
$sql="SELECT acctid1,acctid2,turn FROM pvp WHERE acctid1=".$session['user']['acctid']." OR acctid2=".$session['user']['acctid']."";
$result = db_query($sql) or die(db_error(LINK));
$row = db_fetch_assoc($result);
if(($row['acctid1']==$session['user']['acctid'] && $row['turn']==1) || ($row['acctid2']==$session['user']['acctid'] && $row['turn']==2)){
    redirect("pvparena.php");
}
//Fine modifica PvP Online
if (getsetting("automaster",1) && $session['user']['seenmaster']!=1 && $session['user']['npg']!= 1){
    //masters hunt down truant students
    $exparray=array(1=>100,400,1002,1912,3140,4707,6641,8985,11795,15143,19121,23840,29437,36071,43930);
    while (list($key,$val)=each($exparray)){
        $exparray[$key]= round(
        $val + ($session['user']['dragonkills']/4) * $session['user']['level'] * 100
        ,0);
    }
    $expreqd=$exparray[$session['user']['level']+1];
    if ($session['user']['experience']>$expreqd && $session['user']['level']<15){
        redirect("train.php?op=autochallenge");
    }
}
$session['return']="";
//$ora = strftime("%H",time());
if (getsetting("manutenzione",3) == 3 OR $session['user']['superuser'] > 2) {
  if($session['user']['prefs']['villaggio_nav']=="0") {
    addnav("F.A.Q.");
    addnav("??`(Sezione HELP","help.php");
    addnav("La Foresta");
    addnav("F?Foresta","forest.php");
    addnav("I Campi (logout)");
    addnav("Q?`S`bEsci`b`0 nei campi","login.php?op=logout",true);
    addnav("`@RafflinGate`0");

    if($_GET['sub']=="lame" OR !$_GET['sub'] OR $session['user']['prefs']['villaggio_nav']=="1") {
        addnav("`2Viale delle Lame`0");
        addnav("A?Campo d'Allenamento","train.php");
        if (getsetting("pvp",1)){
            addnav("U?Uccidi un Giocatore","pvp.php");
            addnav("N?L'AreNa","pvparena.php");
        }
        addnav("L?La Spada nella Roccia","spadaroccia.php");
        addnav("E?Sala degli Eroi","hof.php");
        addnav("R?Roccia Curiosa","rock.php");
        if(moduli('caserma')=='on')addnav("S?CaSerma","caserma.php");
        if ($session['user']['prefs']['villaggio_nav']=="0") addnav("");
    } else {
        addnav("L?`#Viale delle Lame`0","village.php?sub=lame");
    }

    if($_GET['sub']=="mercato" OR $session['user']['prefs']['villaggio_nav']=="1") {
        addnav("`2Piazza del Mercato`0");
        addnav("A?Armeria di MightyE","weapons.php");
        addnav("U?ArmatUre da Pegasus","armor.php");
        addnav("B?La Vecchia Banca","bank.php");
        addnav("S?Sceriffo","constable.php");
        addnav("F?Oberon il Fabbro ","fabbro.php");
        addnav("W?Torre di Magia","mago.php");
        addnav("E?L'AccadEmia","accademia.php");
        if ($session['user']['dragonkills'] >= 18 OR $session['user']['reincarna'] > 0 OR $session['user']['superuser'] > 0){
            addnav("R?Mercante di DRaghi","mercante_di_draghi.php");
            addnav("N?AlleNatore di Draghi","allenatore_drago.php");
        }
        if ($session['user']['reincarna'] > 1 OR $session['user']['superuser']>2) addnav("Z?Il negoZio di Hatetepe","spellshop.php");
        if ($session['user']['prefs']['villaggio_nav']=="0") addnav("");
    } else {
        addnav("M?`#Piazza del Mercato`0","village.php?sub=mercato");
    }
    if (@file_exists("pavilion.php")) addnav("E?Eye-catching Pavilion","pavilion.php");

    if($_GET['sub']=="centro" OR $session['user']['prefs']['villaggio_nav']=="1") {
        addnav("`2Centro del paese`0");
        addnav("C?Chiesa di Sgrios ","chiesa.php");
        addnav("R?Area Residenziale","houses.php");
        addnav("N?MuNicipio","municipio.php");
        addnav("S?AmbaSciata","ambasciata.php");
        addnav("A?I GiArdini", "gardens.php");
        if ($session['user']['prefs']['villaggio_nav']=="0") addnav("");
    } else {
        addnav("C?`#Centro del paese`0","village.php?sub=centro");
    }

    if($_GET['sub']=="taverna" OR $session['user']['prefs']['villaggio_nav']=="1") {
        addnav("`2Strada della Taverna`0");
        addnav("A?La LocAnda","inn.php");
        addnav("T?Taverna del Drago","dracodiner.php");
        addnav("E?Le DoccE Pubbliche","bathhouse.php");
        addnav("K?Stalla di MericK","stables.php");
        addnav("N?Il TorNeo delle Medaglie","contestcorner.php");
        if (@file_exists("lodge.php"))  addnav("9?Capanno di Caccia","lodge.php");
        if ($session['user']['prefs']['villaggio_nav']=="0") addnav("");
    } else {
        addnav("T?`#Strada della Taverna`0","village.php?sub=taverna");
    }

    if($_GET['sub']=="periferia" OR $session['user']['prefs']['villaggio_nav']=="1") {
        addnav("`2La Periferia`0");
        addnav("V?Voodoo","voodoo.php");
        if($session['user']['lupin']['carriera']<2 || !isset($session['user']['lupin']['carriera'])) {
              addnav("N?`^Il MaNicomio`0","manicomio.php");
        }
        else {
              addnav("N?`\$Il covo dei ladri`0","covoladri.php");
        }
        addnav("O?Osservatorio","osservatorio.php");
        addnav("Z?Campo degli Zingari","gypsycamp.php");
        addnav("E?Gilda dEl Drago","gildadrago.php");
        addnav("K?Grotta Di Karnak","karnak.php");
        addnav("R?MinieRa","miniera.php");
        if (
        ($session['user']['reincarna'] == 0
        AND $session['user']['playerfights'] > 0
        AND $session['user']['turns'] > 0
        AND $session['user']['dragonkills'] > 7
        AND $session['user']['questcastle'] < 3) OR
        ($session['user']['reincarna'] > 0
        AND $session['user']['playerfights'] > 1
        AND $session['user']['turns'] > 0
        AND $session['user']['dragonkills'] > 7
        AND $session['user']['questcastle'] < 3)
         OR $session['user']['superuser'] > 3){
            addnav("S?CaStello Abbandonato","abandoncastle.php");
        if ($session['user']['prefs']['villaggio_nav']=="0") addnav("");
        }
    } else {
        addnav("P?`#La Periferia`0","village.php?sub=periferia");
    }

    if ($session['user']['dragonkills'] > 9 OR $session['user']['reincarna'] > 0) {
        if($_GET['sub']=="ippodromo" OR $session['user']['prefs']['villaggio_nav']=="1") {
            addnav("`2L'Ippodromo`0");
            addnav("S?Le Scuderie","scuderie.php");
            addnav("I?L'Ippodromo","ippodromo.php");
        } else {
            addnav("I?`#L'Ippodromo`0","village.php?sub=ippodromo");
        }
    }

    if ($session['user']['dragonkills'] >= 18 OR $session['user']['reincarna'] > 0 OR $session['user']['superuser'] > 0){
        addnav("Draghi");
        addnav("D?Terre dei Draghi","terre_draghi.php");
        if (donazioni('sentiero_segreto')==false){
            addnav("10 turni","");
        }else{
            addnav("6 turni","");
        }
    }
    addnav("La Rupe Scoscesa","rupe.php");
    addnav("");

    if ($session['user']['superuser'] > 0){
        addnav("Area Test");
        addnav("Test Nuovo e_rand","testrand.php");
        addnav("Test Vecchio e_rand","testrand1.php");
        if ($session['user']['superuser'] > 2){
            addnav("Test","test.php");
            addnav("Conto Fede Sette","conteggio.php");
        }
        //addnav("Terre dei Draghi","terre_draghi.php");
    }

    if ($session['user']['superuser']>3){
        addnav("Excal&Luke only","");

    }

    addnav("`bAltro`b");
    addnav(">?Gestione Oggetto","gestione_mag.php");
    addnav("<?Zaino Materiali","zaino.php");
    if ($session['user']['superuser']>2) {
        addnav("9?Mercante Admin (99)","emporio_mag_adm.php");
        addnav("8?Materiali ","materiali.php");
        addnav("£?Metti Manutenzione","village.php?op=simanu");
        addnav("Togli Manutenzione","village.php?op=nomanu");
    }
    if ($session['user']['superuser']>=2 OR $session['user']['npg']==1){
        addnav("Suicidati","suicidio.php");
    }
    addnav("/?Notizie Giornaliere","news.php");
    addnav("!?Preferenze","prefs.php");
    addnav(".?Elenco Guerrieri","list.php");
    addnav(",?Età Player","eta.php");
    if ($session['user']['superuser']>=2){
        addnav("+?Trump Tower","trumptower.php");
        addnav("Y?Fissa Link","sblocca.php");
        addnav("X?`bLa Grotta del SuperUtente`b","superuser.php");
    }
  } else {
    addnav("F.A.Q.");
    addnav("??`(Sezione HELP","help.php");
    addnav("Viale delle Lame");
    addnav("F?Foresta","forest.php");
    addnav("C?Campo d'Allenamento","train.php");
    if (getsetting("pvp",1)){
        addnav("U?Uccidi un Giocatore","pvp.php");
        addnav("A?L'Arena","pvparena.php");
    }
    addnav("S?La Spada nella Roccia","spadaroccia.php");
    addnav("E?Sala degli Eroi","hof.php");

    addnav("Piazza del Mercato");
    addnav("M?Armeria di MightyE","weapons.php");
    addnav("P?Armature da Pegasus","armor.php");
    addnav("B?La Vecchia Banca","bank.php");
    addnav("I?ScerIffo","constable.php");
    if (@file_exists("pavilion.php")) addnav("E?Eye-catching Pavilion","pavilion.php");
    addnav("Centro del paese");
    addnav("H?CHiesa di Sgrios ","chiesa.php");
    addnav("O?Oberon il fabbro ","fabbro.php");
    addnav("R?Area Residenziale","houses.php");
    addnav("N?MuNicipio","municipio.php");
    addnav("3?Il Torneo delle Medaglie","contestcorner.php");
    addnav("7?Ambasciata","ambasciata.php");
    addnav("Strada della Taverna");
    addnav("L?La Locanda","inn.php");
    addnav("T?Taverna del Drago","dracodiner.php");
    addnav("D?Le Docce Pubbliche","bathhouse.php");
    addnav("K?Stalla di MericK","stables.php");
    if (@file_exists("lodge.php"))  addnav("9?Capanno di Caccia","lodge.php");
    if ($session['user']['superuser']>3){
        addnav("Excal&Luke only","");

    }
    addnav("G?I Giardini", "gardens.php");
    addnav("J?Roccia CurJosa","rock.php");
    addnav("La Periferia");
    addnav("Voodoo","voodoo.php");
    addnav("W?Torre di Magia","mago.php");
    addnav("L'Accademia","accademia.php");
    if($session['user']['lupin']['carriera']<2 || !isset($session['user']['lupin']['carriera'])) {
          addnav("Il Manicomio`0","manicomio.php");
    }
    else {
          addnav("`\$Il covo dei ladri","covoladri.php");
    }
    addnav("Osservatorio","osservatorio.php");
    addnav("Z?Campo degli Zingari","gypsycamp.php");
    addnav("1?Gilda del Drago","gildadrago.php");
    addnav("Y?Grotta Di Karnak","karnak.php");
    if ($session['user']['reincarna'] > 1 OR $session['user']['superuser']>2) addnav("-?Il negozio di Hatetepe","spellshop.php");
    addnav("M?Miniera","miniera.php");
    if (
    ($session['user']['reincarna'] == 0
    AND $session['user']['playerfights'] > 0
    AND $session['user']['turns'] > 0
    AND $session['user']['dragonkills'] > 7
    AND $session['user']['questcastle'] < 3) OR
    ($session['user']['reincarna'] > 0
    AND $session['user']['playerfights'] > 1
    AND $session['user']['turns'] > 0
    AND $session['user']['dragonkills'] > 7
    AND $session['user']['questcastle'] < 3)
    OR $session['user']['superuser'] > 3){
        addnav("Castello Abbandonato","abandoncastle.php");
    }
    if(moduli('caserma')=='on')addnav("6?Caserma","caserma.php");
    if ($session['user']['dragonkills'] >= 18 OR $session['user']['reincarna'] > 0 OR $session['user']['superuser'] > 0){
        addnav("Draghi");
        addnav("Mercante di draghi","mercante_di_draghi.php");
        addnav("Allenatore di Draghi","allenatore_drago.php");
        addnav("Terre dei Draghi","terre_draghi.php");
        if (donazioni('sentiero_segreto')==false){
            addnav("10 turni","");
        }else{
            addnav("6 turni","");
        }
    }
    addnav("La Rupe Scoscesa","rupe.php");
    if ($session['user']['dragonkills'] > 9 OR $session['user']['reincarna'] > 0) {
        addnav("Ippodromo");
        addnav("Le Scuderie","scuderie.php");
        addnav("L'Ippodromo","ippodromo.php");
    }
    if ($session['user']['superuser'] > 0){
        addnav("Area Test");
        if ($session['user']['superuser'] > 2){
            addnav("Test","test.php");
            addnav("Test Nuovo e_rand","testrand.php");
            addnav("Test Vecchio e_rand","testrand1.php");
            addnav("Conto Fede Sette","conteggio.php");
        }
        //addnav("Terre dei Draghi","terre_draghi.php");
    }
    addnav("`bAltro`b");
    addnav(">?Gestione Oggetto","gestione_mag.php");
    addnav("<?Zaino Materiali","zaino.php");
    if ($session['user']['superuser']>2) {
        addnav("t?Mercante Admin (99)","emporio_mag_adm.php");
        addnav("h?Materiali ","materiali.php");
        addnav("£?Metti Manutenzione","village.php?op=simanu");
        addnav("Togli Manutenzione","village.php?op=nomanu");
    }
    addnav("/?Notizie Giornaliere","news.php");
    addnav("!?Preferenze","prefs.php");
    addnav(".?Elenco Guerrieri","list.php");
    addnav("Età Player","eta.php");
    addnav("Q?`S`bEsci`b`0 nei campi","login.php?op=logout",true);
    if ($session['user']['superuser']>=2){
        addnav("+?Trump Tower","trumptower.php");
        addnav("Fissa Link","sblocca.php");
        addnav("X?`bLa Grotta del SuperUtente`b","superuser.php");
    }
    if ($session['user']['superuser']>=2 OR $session['user']['npg']==1){
        addnav("Suicidati","suicidio.php");
    }
  }
    //let users try to cheat, we protect against this and will know if they try.
    addnav("","superuser.php");
    addnav("","user.php");
    addnav("","taunt.php");
    addnav("","creatures.php");
    addnav("","configuration.php");
    addnav("","badword.php");
    addnav("","armoreditor.php");
    addnav("","bios.php");
    addnav("","badword.php");
    addnav("","donators.php");
    addnav("","referers.php");
    addnav("","retitle.php");
    addnav("","stats.php");
    addnav("","viewpetition.php");
    addnav("","weaponeditor.php");
    if ($session['user']['superuser'] == 1){
        addnav("2?Modera Commenti","moderatealt.php");
    }

    if ($session['user']['superuser'] > 1){
        addnav("*?Nuovo Giorno","newday.php");
    }
} else {
    addnav("Q?`S`bEsci`b`0 nei campi","login.php?op=logout",true);
    output("`c`n`n<big><big><big><big><big>`b`\$SERVER IN MANUTENZIONE</big></big></big></big></big>",true);
    output("`nSi consiglia di uscire dal game ed attendere il termine della manutenzione`nGrazie per la collaborazione`n");
    output("Gli admin di LoGD`n`n`c`b");
}
if ($session['user']['bladder'] > 15){
    $dance=e_rand(1,500);
    if ($dance < 26){
        //$sql ="INSERT INTO commentary (postdate,section,author,comment) VALUES (now(),'village1','".$session['user']['acctid']."','::fa la danza della pipì.')";
        //db_query($sql);
        output("Una delle guardie del villaggio nota la tua incontinenza, e ti estorce ".($session['user']['level']*5)." pezzi d'oro quale tassa per le pulizie`n`n");
        $session['user']['goldinbank'] -=$session['user']['level']*5;
        if ($session['user']['goldinbank'] < 0) $session['user']['goldinbank']=0;
    }
}
if (getsetting("nomedb","logd") == "logd2"){
    page_header("RafflinGate (stanza GDR)");
    $session['user']['locazione'] = 196;
    output("`@`c`bRafflinGate`b`cIl villaggio è in trambusto e nessuno si accorge veramente della tua presenza ");
    output("mentre ti aggiri per i vari negozi e le attività che si affacciano lungo la Via delle Lame. Appena ");
    output("ti affacci sulle piazze del Villaggio il tuo sguardo viene catturato dalla imponente statua di un Dio.`n");
    output("Essa, in bella mostra sull'asse principale della via, al centro preciso del rettangolo disegnato dalle ");
    output("due piazze, rivolta nelle sembianze ai quattro punti cardinali, campeggia possente; scolpita da mani ");
    output("esperte nel candido marmo a guisa e monito per ogni viandante sicchè s'avveda che la magnificenza di ");
    output("questo villaggio altro non è se non merito dell'Admin Excalibur e dei suoi collaboratori, raffigurati ");
    output("in forma di simboli nel monile che porta sul petto, scolpita con garbo e maestria, tanto più mirabile ");
    output("quanto più raffigura un'immagine sacra.`nCuriosamente osservi poi una strana roccia che, sebbene sembri ");
    output("deposta lì da un lato con noncuranza, richiama attorno a sè sempre numerosi viandanti.`nDa ultimo ");
    output("allunghi l'occhio verso i confini della via cercandone il limitare, ma essa si distende a perdita ");
    output("d'occhio all'interno della foresta che, fitta ed oscura, circonda tutto il Villaggio.`n`n");

}else{
    page_header("RafflinGate");
    $session['user']['locazione'] = 187;
    output("`@`c`bRafflinGate`b`cIl villaggio è in trambusto. Nessuno si accorge veramente della tua presenza.");
    output("  Vedi vari negozi ed attività lungo la strada principale. C'è una strana roccia da un lato.  ");
    output("Da ogni lato il villaggio è circondato da una fitta ed oscura foresta.`n`n");
}
//output("<img src='images/trans.gif' width='1' height='700' alt='' align='right'>",true);

//Festa, Sook (impostazione data)
$festa=0;
$data = date("m-d");
if ($data==getsetting("festa","no")) $festa=1;

if ($festa==1) output("<big>`n`n`c`b`^FESTA COMUNALE DI RAFFLINGATE`b`c`n`n`0</big>",true);
//Fine festa

$sql = "SELECT * FROM news WHERE 1 ORDER BY newsid DESC LIMIT 1";
$result = db_query($sql) or die(db_error(LINK));
$news = db_fetch_assoc($result);
output("`^L'ultima News del villaggio:`n`n`0`c`i".$news['newstext']."`i`c`n`0");
output("`cIl tempo oggi è `6 ".$settings['weather']."`@ e l'orologio della locanda segna le `^".getgametime()."`@.`c");
if ($session['user']['superuser'] > 2) {
   output("`c`@Ora del Server (`\$per Admin`@): ".date("d-m-Y H:i:s",time())."`c");
}
$torneo=stripslashes(getsetting("torneo",""));
if ($torneo!="") output("`c`@L'attuale campione del `#`bTorneo`b`@ è `^$torneo`@!`0`c");
$sql = "SELECT name FROM accounts WHERE medhunt > 0 AND superuser = 0 ORDER BY medpoints DESC LIMIT 1";
$result = db_query($sql);
$row = db_fetch_assoc($result);
$plaque = $row['name'];
if ($plaque <> ""){
    output("`@`cL'attuale Leader della `#`bGara delle Medaglie`b`@ è: $plaque`@.`c");
}
$plaque="";
if(getsetting("striscione_acctid",0)>0 AND date('d')>=1 AND date('d')<=4){
    $sqlnome="SELECT name FROM accounts WHERE acctid='".getsetting("striscione_acctid",0)."'";
    $resultnome=db_query($sqlnome);
    $dep=db_fetch_assoc($resultnome);
    $nome=$dep['name'];
    output("`n`b`c`@Uno striscione all'entrata del municipio riporta:`n\"`6 Annuncio del candidato sindaco $nome `@\"`0`c`b",true);
    output("`b`c<big>`0".stripslashes(getsetting("striscione_testo",0))."</big>`c`b",true);
}
if (getsetting("nomedb","logd") == "logd"
    AND date("d") > ($giorno_chiusura_voto - 5)
    AND date("d") < ($giorno_chiusura_voto + 1)){
    output("`n`c`XSono aperte le votazioni per eleggere il gestore dell'`SAgenzia Matrimoniale`X !!!`c");
}
output("`n`n`%`@Alcuni abitanti del villaggio parlano nelle vicinanze:`n");
viewcommentary("village","Aggiungi",30,20);

if($session['user']['euro']<1)addnav('<center><script type="text/javascript"><!--
google_ad_client = "pub-8533296456863947";
google_ad_width = 125;
google_ad_height = 125;
google_ad_format = "125x125_as";
google_ad_type = "text_image";
google_ad_channel ="";
google_color_border = "804000";
google_color_bg = "78B749";
google_color_link = "804000";
google_color_text = "000000";
google_color_url = "6F3C1B";
//--></script>
<script type="text/javascript"
  src="http://pagead2.googlesyndication.com/pagead/show_ads.js">
</script></center><br>','',true);
page_footer();
?>
