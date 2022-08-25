<?php
/*
carriera FABBRO        carriera SGRIOS        carriera KARNAK               carriera DRAGO
===============        ===============        ===============               ==============
5=>"Garzone",          1=>"Seguace",          10=>"Invasato",               50=>"Stalliere",
6=>"Apprendista",      2=>"Accolito",         11=>"Fanatico",               51=>"Scudiero",
7=>"Fabbro",           3=>"Chierico",         12=>"Posseduto",              52=>"Cavaliere",
8=>"Mastro Fabbro",    4=>"Sacerdote",        13=>"Maestro delle Tenebre",  53=>"Mastro di Draghi",
                       9=>"Gran Sacerdote",   15=>"Falciatore di Anime",    54=>"Dominatore di Draghi",
                       17=>"Sommo Chierico",  16=>"Portatore di Morte",     55=>"Cancelliere dei Draghi",
*/

require_once("common.php");
require_once("common2.php");
manutenzione(getsetting("manutenzione",3));

checkday();

// Funzioni per le riparazioni
function getCosto($value_arma, $percentuale_arma, $riparazione, $manodopera) {
    return intval(($value_arma * $percentuale_arma * $riparazione * (1 + $manodopera)) / 100);
}
function getPercentuale($usura_arma, $durata_max_usura_arma) {
    $percentuale = intval(100 - (($usura_arma * 100) / $durata_max_usura_arma));
    if ($percentuale > 100) $percentuale=100;
    return $percentuale;
}
function getPuntiRiparazione($percentuale_arma,$livello_arma) {
    return intval( ($percentuale_arma / 10)  * $livello_arma);
}

//determinazione numero dei fabbri (Sook)
$sqlfab = "SELECT acctid FROM accounts WHERE carriera > 4 AND carriera < 9 AND superuser=0";
$resultfab = db_query($sqlfab) or die(db_error(LINK));
$player_fabbri = db_num_rows($resultfab);
//fine numero fabbri

// Generazione delle ricette e dei materiali di Heimdall
function carica_Heimdall() {
    global $player_fabbri;
    //ricerca ricette esistenti nel database
    $ricette=array();
    $sqlricette = "SELECT id FROM materiali WHERE tipo='R'";
    $resultricette = db_query($sqlricette) or die(db_error(LINK));
    $countrow = db_num_rows($resultricette);
    for ($i=0; $i<$countrow; $i++){
    //for($i=0;$i<db_num_rows($resultricette);$i++){
        $ricetta = db_fetch_assoc($resultricette);
        array_push($ricette, $ricetta[id]);
    }
    //generazione ricette
    $id_Heimdall=3;
    $sqlH = "SELECT COUNT(idplayer) FROM zaino WHERE idplayer=".$id_Heimdall;
    $resultH = db_query($sqlH) or die(db_error(LINK));
    $numero_ricette = db_num_rows($resultH);
    $numero_ricette = intval($numero_ricette/2);
    if($numero_ricette>500 OR $numero_ricette>($player_fabbri)) {
        $sqldel="DELETE FROM zaino WHERE idplayer=".$id_Heimdall." LIMIT ".$numero_ricette;
        db_query($sqldel);
    }

    for($i=0;$i<(getsetting("Ricette_Heimdall",20));$i++) {
        $quale=e_rand(1,count($ricette))-1;
        $sqladd="INSERT INTO zaino (idoggetto,idplayer) VALUES ('".$ricette[$quale]."','".$id_Heimdall."')";
        db_query($sqladd) or die(db_error(LINK));
    }
    $ricette_create= e_rand(10,20) + round($player_fabbri*0.75);
    savesetting("Ricette_Heimdall",$ricette_create);
}

//Sook, settaggio automatico a mastro fabbro per gli admin
if ($session['user']['superuser'] > 2) {
    $session['user']['carriera'] = 8;
}

// Variabili
$skills = array(1 => "`\$Arti Oscure", "`%Poteri Mistici", "`^Furto", "`3Militare","`\$Seduzione","`^Tattica","`@Pelle di Roccia","`#Retorica","`%Muscoli","`3Natura","`&Clima","`^Elementalista","`6Rabbia Barbara","`5Canzoni del Bardo");

$puntiriparazionemin= 20 * $player_fabbri;
$puntiriparazionesblocco = 50 * $player_fabbri;
$riparazioni = getsetting("riparazioni",'false');
$maxpuntiriparazioneindividuali = 3000;

$manodopera = 5/100;
$riparazione = 33/100;
$dk = $session['user']['dragonkills'];
$carriera = $session['user']['carriera'];
$idplayer = $session['user']['acctid'];
$arraycarriera = array(
5=>"Garzone",
6=>"Apprendista",
7=>"Fabbro",
8=>"Mastro Fabbro",
);
$statowpn = array(
0=>"Perfetta",
1=>"Come Nuova",
2=>"In Buone Condizioni",
3=>"Ammaccata",
4=>"Incrinata",
5=>"`\$Arrugginita",
);
$statoarm = array(
0=>"Perfetta",
1=>"Ben Tenuta",
2=>"Ammaccata",
3=>"Danneggiata",
4=>"Gravemente Danneggiata",
5=>"`\$Quasi a Pezzi",
);
$statoogg = array(
0=>"Perfetto",
1=>"Molto ben tenuto",
2=>"In buono stato",
3=>"Malconcio",
4=>"Danneggiato",
5=>"`\$Semidistrutto",
);

$scaglie_ferro = getsetting("scagliemetallo",0);
$scaglie_rame = getsetting("scaglierame",0);
$carbone = getsetting("carbone",0);
$argento = getsetting("argento",0);
$oro = getsetting("oro",0);
$caso = mt_rand(1,4);
$caso2 = mt_rand(1,4);
$caso3 = mt_rand(1,4);
$caso_gold = ($session['user']['level']*8* $caso3);
$caso_gold2 = ($session['user']['level']*15* $caso3);
$caso_gold3 = ($session['user']['level']*30* $caso3);
$cento = mt_rand(1,100);
// Arrivo di Heimdall
$heimdall = 0;
$ultima_volta = getsetting("ultima_volta", 0);
$prossima_visita = getsetting("prossima_volta", 0);
$data_oggi = time();
if (($data_oggi - $ultima_volta) > ($prossima_visita - 86400)) {
    $heimdall = 1;
    if (($data_oggi - $ultima_volta) > $prossima_visita) {
        $data_casuale = mt_rand(259200,777600);
        savesetting("prossima_volta", $data_casuale);
        savesetting("ultima_volta", $data_oggi);
        $heimdall = 0;
        //creazione ricette per Heimdall
        carica_Heimdall();
        //svuotamento oggetti inutili all'emporio
        if (e_rand(1,5)==1) {
            $sqldelogg="DELETE FROM oggetti WHERE dove=1 AND potenziamenti=0 AND attack_help=0 AND defence_help=0 AND special_help=0 AND special_use_help=0 AND gold_help=0
                AND turns_help=0 AND gems_help=0 AND quest_help=0 AND hp_help=0 AND pvp_help=0 AND exp_help=0 AND heal_help=0 AND nome!='Piccone da minatore' AND nome!='Ascia da boscaiolo' AND nome!='Sega da falegname'";
            db_query($sqldelogg);
        }
    }
    if ($heimdall==1) output("`$ Heimdall è arrivato.`n`n`n`7");
}
// fine codice verifica arrivo di Heimdall

// Randomizzazione mercato
$ultimo_mercato = getsetting("ultimo_mercato_f", 0);
$prossimo_mercato = getsetting("prossimo_mercato_f", 0);
if (($data_oggi - $ultimo_mercato) > ($prossimo_mercato)) {
        $scaglie_ferro += intval($scaglie_ferro*e_rand(-10, 10)/100) + e_rand(-5, 5);
        $scaglie_rame += intval($scaglie_rame*e_rand(-10, 10)/100) + e_rand(-5, 5);
        $carbone += intval($carbone*e_rand(-10, 10)/100) + e_rand(-5, 5);
        if (e_rand(1,5) == 1) $argento += intval($argento*e_rand(-10, 10)/100) + e_rand(-5, 5);
        if (e_rand(1,5) == 10)$oro += intval($oro*e_rand(-10, 10)/100) + e_rand(-5, 5);
        savesetting("scagliemetallo",$scaglie_ferro);
        savesetting("scaglierame",$scaglie_rame);
        savesetting("carbone",$carbone);
        savesetting("argento",$argento);
        savesetting("oro",$oro);
        addnews("Una carovana di mercanti stranieri è stata vista nei pressi della bottega di Oberon");
    $data_mercato = mt_rand(43200,259200);
    savesetting("prossimo_mercato_f", $data_mercato);
    savesetting("ultimo_mercato_f", $data_oggi);
}
// fine randimizzazione mercato
// nomina mastro fabbro
// Maximus, nuova versione della nomina, come per i sacerdoti
    if ($carriera == 7 OR $carriera == 8) {
        if ($session['user']['superuser'] == 0) {
            $carrierainiz=$carriera;
            savesetting("mastrofabbro","0");
            $sqlma = "SELECT acctid FROM accounts WHERE
        carriera = 7 OR carriera = 8 and superuser = 0
        ORDER BY punti_carriera DESC LIMIT 1";
            $resultma = db_query($sqlma) or die(db_error(LINK));
            $rowma = db_fetch_assoc($resultma);
            $mf = $rowma['acctid'];
            savesetting("mastrofabbro", $mf);
        }
        if ((getsetting("mastrofabbro",0)!=$session['user']['acctid'] AND $session['user']['superuser']==0) OR $session['user']['punti_carriera']<20000){
            $session['user']['carriera'] = 7;
            if ($carrierainiz == 8) {
                output("<big>`\$`b`cRetrocessione`c`n`b`0</big>",true);
                output("`#Non sei più il miglior Fabbro di Rafflingate, pertanto non ti puoi più fregiare del titolo di `^Mastro Fabbro`#.`n");
            }
        }else{
            if ($carrierainiz == 7) {
                output("<big>`^`b`cPromozione`c`b`0`n</big>",true);
                output("Sei diventato il Mastro Fabbro!`n`n");
                $sqlma = "SELECT acctid FROM accounts WHERE
                carriera = 7 OR carriera = 8
                ORDER BY punti_carriera DESC LIMIT 2";
                $resultma = db_query($sqlma) or die(db_error(LINK));
                $countrow = db_num_rows($resultma);
                for ($i=0; $i<$countrow; $i++){
                //for ($i = 0;$i < db_num_rows($resultma);$i++) {
                    $rowma = db_fetch_assoc($resultma);
                }
                if ($session['user']['superuser']==0) {
                    $sqlaggiornamf = "UPDATE accounts SET carriera = 7 WHERE acctid = ".$rowma['acctid']."";
                    $resultaggiornamf = db_query($sqlaggiornamf) or die(db_error(LINK));
                }
            }
            $session['user']['carriera'] = 8;
        }
    }
// fine Maximus
//cancellazione mastro precedente
/*
if ($session['user']['superuser'] == 0) {
    if ($carriera == 7) {
        $sqlm = "SELECT acctid FROM accounts WHERE carriera=8";
        $resultm = db_query($sqlm) or die(db_error(LINK));
        $rowm = db_fetch_assoc($resultm);
        if (db_num_rows($resultm) != 0) {
            if ($rowm['acctid'] == $idplayer) {
                $session['user']['carriera'] = 7;
            } else {
                $sqlu = "UPDATE accounts SET carriera='7' WHERE acctid='{$rowm['acctid']}' ";
                //output("SQL : $sqlu `n");
                db_query($sqlu) or die(db_error(LINK));
            }
        }
        //aggiornamento nuovo mastro
        $sqlma = "SELECT acctid FROM accounts WHERE punti_carriera >= 20000 AND carriera=7 AND superuser=0 ORDER BY punti_carriera DESC LIMIT 1";
        $resultma = db_query($sqlma) or die(db_error(LINK));
        $rowma = db_fetch_assoc($resultma);
        if (db_num_rows($resultma) != 0) {
            if ($rowma['acctid'] == $idplayer) {
                $session['user']['carriera'] = 8;
            }else{
                $sqlua = "UPDATE accounts SET carriera='8' WHERE acctid='{$rowma['acctid']}' ";
                //output("SQL : $sqlua `n");
                db_query($sqlua) or die(db_error(LINK));
            }
        }
    }
}
*/
//  prezzo acquisto variabile
$val_scaglie_metallo = 1000-(10*$scaglie_ferro);
$val_scaglie_rame = 2200-(10*$scaglie_rame);
$val_carbone = 600-(10*$carbone);
$val_argento = 5000-(10*$argento);
$val_oro = 8400-(10*$oro);
if ($val_scaglie_metallo < 10) $val_scaglie_metallo=10;
if ($val_carbone < 10) $val_carbone=10;
if ($val_scaglie_rame < 10) $val_scaglie_rame=10;
if ($val_argento < 100) $val_argento=100;
if ($val_oro < 500) $val_oro=500;

page_header("Oberon il fabbro");
$session['user']['locazione'] = 123;

// Arrivo di Heimdall
// Selezione Punti Riparazione Collettivi
$sqlripcoll = "SELECT SUM(punti_riparazione) AS punti FROM riparazioni a, accounts b WHERE a.acctid = b.acctid";
// Commentare per prova la riga sotto
//$sqlripcoll = $sqlripcoll . " AND b.superuser = 0";
$resultripcoll = db_query($sqlripcoll) or die(db_error(LINK));
$refripcoll = db_fetch_assoc($resultripcoll);

// Controlli sul minimo Punti Riparazione
if ($refripcoll[punti]< $puntiriparazionemin) {
    $riparazioni = 'false';
}
if ($refripcoll[punti]>$puntiriparazionesblocco AND $riparazioni == 'false') {
    $riparazioni = 'true';
}
savesetting("riparazioni", $riparazioni);

//arrabbiato
/*
addnav("Sei arrabbiato con i fabbri ?");
addnav("Rompi qualche cosa","fabbro.php?op=rompi");
addnav("Picchia un apprendista","fabbro.php?op=picchia");
addnav("Picchia Oberon","fabbro.php?op=picchia_ob");
if ($_GET['op']=="rompi") {
output("`\$Ti sfoghi su dei tavoli e delle sedie spaccando un po' di cose!`n");
savesetting("arrabbiato",getsetting("arrabbiato",0)+1);

}
if ($_GET['op']=="picchia") {
output("`\$Prendi a calci i garzoni di Oberon che scappano a gambe levate!`n");
savesetting("arrabbiato",getsetting("arrabbiato",0)+1);

}
if ($_GET['op']=="picchia_ob") {
output("`\$Dai un pugno a Oberon .... ops ..... forse era meglio non farlo ....!`n");
savesetting("arrabbiato",getsetting("arrabbiato",0)+1);

}
output("`\$Oberon borbotta :\"abbiamo avuto ".getsetting("arrabbiato",0)." lamentele, quello scansafatiche del mastro dove si è cacciato!!!!`n`n`3");
*/


// Inizio Menù di Navigazione
if ($_GET['op']!="corso" AND $_GET['op']!="mercatino") {
addnav("Azioni");
}
if ($carriera == 0 AND $_GET['op']!="garzone" AND $_GET['op']!="diventagarzone" AND $_GET['op']!="corso" AND $_GET['op']!="mercatino") {
    addnav("Impara l'arte del fabbro","fabbro.php?op=garzone");
}
if ($carriera > 4 AND $carriera < 9 AND $_GET['op']!="corso" AND $_GET['op']!="mercatino" AND $riparazioni == 'true') {
    addnav("Corso di Specializzazione", "fabbro.php?op=corso");
}
// esercezio per livello
if (($carriera == 5 OR $carriera == 6 OR $carriera == 7 OR $carriera == 8) AND $_GET['op']!="corso" AND $_GET['op']!="mercatino" AND $riparazioni == 'true') {
    addnav("M?Esercitati al Mantice","fabbro.php?op=mantice");
}
if (($carriera == 6 OR $carriera == 7 OR $carriera == 8) AND $_GET['op']!="corso" AND $_GET['op']!="mercatino" AND $riparazioni == 'true') {
    addnav("I?Esercitati all'Incudine","fabbro.php?op=incudine");
}
if (($carriera == 7 OR $carriera == 8) AND $_GET['op']!="corso" AND $_GET['op']!="mercatino" AND $riparazioni == 'true') {
    addnav("A?Esercitati nell'Affilatura","fabbro.php?op=affila");
}
if ($carriera == 8 AND $_GET['op']!="corso" AND $_GET['op']!="mercatino" AND $riparazioni == 'true') {
    addnav("T?Esercitati nella Tempratura","fabbro.php?op=tempra");
}
//Inizio carriera Fabbro come Garzone, conferma
if($_GET['op']=="garzone"){
    addnav("Diventa garzone", "fabbro.php?op=diventagarzone");
}
// Lavoro con Ricette
if ($_GET['op']!="corso" AND $_GET['op']!="mercatino" AND $riparazioni == 'true') {
    addnav("Lavora");
}
if (($carriera == 6 OR $carriera == 7 OR $carriera == 8) AND $_GET['op']!="corso" AND $_GET['op']!="mercatino" AND $riparazioni == 'true') {
    addnav("o?Lavora oggetto Piccolo","fabbro.php?op=forgia&og=p");
}
if (($carriera == 7 OR $carriera == 8) AND $_GET['op']!="corso" AND $_GET['op']!="mercatino" AND $riparazioni == 'true') {
    addnav("L?Lavora oggetto Medio","fabbro.php?op=forgia&og=m");
}
if ($carriera == 8 AND $_GET['op']!="corso" AND $_GET['op']!="mercatino" AND $riparazioni == 'true') {
    addnav("G?Lavora oggetto Grande","fabbro.php?op=forgia&og=g");
}
// Riparazioni
if ($_GET['op']!="garzone" AND $_GET['op']!="corso" AND $_GET['op']!="mercatino") {
    addnav("Riparazioni");
    addnav("R?Servizio Riparazioni", "fabbro.php?op=riparazioni");
    if ($carriera > 4 AND $carriera < 9 ) {
        addnav("F?Fai delle Riparazioni", "fabbro.php?op=makeriparazioni&og=intro");
        addnav("P?Riscuoti Paga", "fabbro.php?op=riscuoti");
    }
}
// Manù Info Varie
if ($_GET['op']!="corso" AND $_GET['op']!="mercatino") {
    addnav("Info");
}
if (($carriera == 5 OR $carriera == 6 OR $carriera == 7 OR $carriera == 8) AND $_GET['op']!="corso" AND $_GET['op']!="mercatino") {
    addnav("d?Chiedi come stai andando","fabbro.php?op=chiedi");
}
if (($carriera == 5 OR $carriera == 6 OR $carriera == 7 OR $carriera == 8) AND $_GET['op']!="corso" AND $_GET['op']!="mercatino") {
    addnav("I Migliori Fabbri","fabbro.php?op=migliori");
    addnav("La Sala Riunioni","fabbro.php?op=sala");
}
// Promozione ad Apprendista
if($session['user']['punti_carriera']>=5000 AND $carriera == 5 AND ($dk > 10 OR $session['user']['reincarna'] > 0)){
    output("<big>`^`b`cPromozione`c`b`0`n</big>",true);
    output("Oberon ti guarda pensieroso e a grandi passi si avvicina verso di te.`n");
    output("Ti batte su una spalla e dice :`n `&\"Finalmente è il tuo grande giorno, da oggi sei un apprendista fabbro!`n");
    output("Tutto l'esercizio che hai fatto ti permetterà di lavorare dei piccoli oggetti. Per lavorare gli oggetti devi trovare le ricette e tutti gli ingredienti necessari.`n");
    output("Ricorda però che la creazione di oggetti ti stancherà molto, e ogni oggetto assorbirà una parte della tua abilità.`n");
    output("Da ora puoi esercitarti nell'uso del martello e dell'incudine per apprendere più velocemente l'arte del fabbro.  \"`7.`n`n");
    output("Oberon ti porge una pergamena, con piacere noti che è una ricetta, la tua prima ricetta.");
    if (zainoPieno ($idplayer)) {
        output(" Peccato che tu non abbia lo spazio necessario nello zaino per poterla prendere...");
        debuglog("doveva ricevere una ricetta per accessori da Oberon ma non aveva spazio per promozione ad apprendista");
    } else {
        $sqldr="INSERT INTO zaino (idoggetto,idplayer) VALUES ('6','{$idplayer}')";
        db_query($sqldr);
    }
    output("`n`nInoltre con il duro lavoro svolto la tua costituzione è migliorata e guadagni 5 punti vita permanenti.`n`n");
    $session['user']['maxhitpoints']+=5;
    debuglog("riceve 5 HP permanenti da Oberon per promozione ad apprendista");
    $session['user']['carriera'] = 6;
}
// Promozione a Fabbro
if($session['user']['punti_carriera']>=20000 AND $carriera == 6 AND ($dk > 15 OR $session['user']['reincarna'] > 0)){
    output("<big>`^`b`cPromozione`c`b`0`n</big>",true);
    output("Oberon ti guarda pensieroso e a grandi passi si avvicina verso di te.`n");
    output("Ti batte su una spalla e dice : `&\"Finalmente è il tuo grande giorno, da oggi sei un fabbro.`n");
    output("Tutto l'esercizio che hai fatto ti permetterà di lavorare oggetti più grandi.`n");
    output("Ricorda però che la creazione di oggetti ti stancherà molto, e in ogni oggetto trasferirai una parte della tua abilità.`n");
    output("Da ora puoi esercitarti nella affilatura per apprendere più velocemente l'arte del fabbro.  \"`7.`n`n");
    output("Inoltre con il duro lavoro svolto la tua costituzione è migliorata e guadagni 10 punti vita permanenti.`n`n");
    $session['user']['maxhitpoints']+=10;
    debuglog("riceve 10 HP permanenti da Oberon per promozione a fabbro");
    $session['user']['carriera'] = 7;
}
// Blocco di Compra/Vendita Materiali
if ($session['user']['level'] > 4) addnav("","fabbro.php?op=vendica");
if ($session['user']['level'] < 15) addnav("","fabbro.php?op=compraca");
if ($session['user']['level'] > 4) addnav("","fabbro.php?op=vendisr");
if ($session['user']['level'] < 15) addnav("","fabbro.php?op=comprasr");
if ($session['user']['level'] > 4) addnav("","fabbro.php?op=vendism");
if ($session['user']['level'] < 15) addnav("","fabbro.php?op=comprasm");
// Nuovi Materiali
if ($session['user']['level'] > 4) addnav("","fabbro.php?op=vendiar");
if ($session['user']['level'] < 15) addnav("","fabbro.php?op=compraar");
if ($session['user']['level'] > 4) addnav("","fabbro.php?op=vendior");
if ($session['user']['level'] < 15) addnav("","fabbro.php?op=compraor");

// Menù Acquisti
if ($_GET['op']!="corso" AND $_GET['op']!="mercatino") {
   addnav("Acquisti");
   addnav("A?Acquista Ricette","fabbro.php?op=ricette");
}
// Menù Heimdall
if (($heimdall==1 OR $session['user']['superuser']>=2) AND $_GET['op']!="corso" AND $_GET['op']!="mercatino") {
    addnav("H?Parla con Heimdall","fabbro.php?op=heimdall");
}

if ($_GET['op']!="corso" AND $_GET['op']!="mercatino") {
   // Maximus blocco definitivo del mercatino
   if ($session['user']['level'] > 5) {
      addnav("Mercatino");
      addnav("M?Mercatino","fabbro_mercatino.php");
   }
   // Maximus fine
}


// Menù di Uscita
addnav("Exit");
/*if ($_GET['op']=="mercatino") {
   addnav("M?Torna al Mercatino","fabbro_mercatino.php?op=mercatino");
}
*/
if ($_GET['op']!="garzone" OR $_GET['op']) {
    addnav("E?Torna all'Entrata", "fabbro.php");
}
addnav("V?Torna al Villaggio","village.php");

if ($_GET['op']==""){
    output("Al momento Oberon ha questi materiali. Ricorda che il valore indicato è quello a cui Oberon vende i materiali, li compra a molto meno.`n`n");
    // Inizio tabella Materiali
    if ($session['user']['superuser'] > 2) {
        addnav("Modifica Quantità Materiali","fabbro.php?op=modmater");
        output("<form action='fabbro.php?op=materiali' method='POST'>",true);
        addnav("","fabbro.php?op=materiali");
    }
    output("<table cellspacing=0 cellpadding=2 align='center'>", true);
    output("<tr class='trhead' align='center'><td>`bMateriale`b</td><td>`bQuantità`b</td><td>`bValore oro`b</td><td>Ops</td>",true);
    if ($session['user']['superuser'] > 2) output("<td>`iModifica quantità`i</td>",true);
    output("</tr>", true);
    // Ferro
    output("<tr class='trlight'><td>Scaglie di ferro</td><td>$scaglie_ferro</td><td>$val_scaglie_metallo</td>",true);
    if ($session['user']['level'] < 15) {
        output("<td><A href=fabbro.php?op=comprasm>Compra </a>",true);
    } else {
        output("<td>",true);
    }
    if ($session['user']['level'] > 4) output("-<A href=fabbro.php?op=vendism> Vendi</a>",true);
    if ($session['user']['superuser'] > 2) output("</td><td><input name='ferro' value=\"".HTMLEntities2($scaglie_ferro)."\" size='5'>",true);
    output("</td></tr>", true);
    // Rame
    output("<tr class='trdark'><td>Scaglie di rame</td><td>$scaglie_rame</td><td>$val_scaglie_rame</td>",true);
    if ($session['user']['level'] < 15) {
        output("<td><A href=fabbro.php?op=comprasr>Compra </a>",true);
    } else {
        output("<td>",true);
    }
    if ($session['user']['level'] > 4) output("-<A href=fabbro.php?op=vendisr> Vendi</a>",true);
    if ($session['user']['superuser'] > 2) output("</td><td><input name='rame' value=\"".HTMLEntities2($scaglie_rame)."\" size='5'>",true);
    output("</td></tr>", true);
    // Carbone
    output("<tr class='trlight'><td>Carbone</td><td>$carbone</td><td>$val_carbone</td>",true);
    if ($session['user']['level'] < 15) {
        output("<td><A href=fabbro.php?op=compraca>Compra </a>",true);
    } else {
        output("<td>",true);
    }
    if ($session['user']['level'] > 4) output("-<A href=fabbro.php?op=vendica> Vendi</a>",true);
    if ($session['user']['superuser'] > 2) output("</td><td><input name='carbone' value=\"".HTMLEntities2($carbone)."\" size='5'>",true);
    output("</td></tr>", true);
    // Argento
    output("<tr class='trdark'><td>Argento</td><td>$argento</td><td>$val_argento</td>",true);
    output("<td>",true);
    if ($session['user']['level'] < 15) {
        output("<A href=fabbro.php?op=compraar>Compra </a>",true);
    }
    if ($session['user']['level'] > 4) output("-<A href=fabbro.php?op=vendiar> Vendi</a>",true);
    if ($session['user']['superuser'] > 2) output("</td><td><input name='argento' value=\"".HTMLEntities2($argento)."\" size='5'>",true);
    output("</td></tr>", true);
    // Oro
    output("<tr class='trlight'><td>Oro</td><td>$oro</td><td>$val_oro</td>",true);
    output("<td>",true);
    if ($session['user']['level'] < 15) {
        output("<A href=fabbro.php?op=compraor>Compra </a>",true);
    }
    if ($session['user']['level'] > 4) output("-<A href=fabbro.php?op=vendior> Vendi</a>",true);
    if ($session['user']['superuser'] > 2) output("</td><td><input name='oro' value=\"".HTMLEntities2($oro)."\" size='5'>",true);
    output("</td></tr>", true);
    output("</table>", true);
    if ($session['user']['superuser'] > 2) output("`n`n<input type='submit' class='button' value='Salva'></form>",true);
    // Fine tabella Materiali
    output ("`n`n");

    if (($carriera == 5 OR $carriera == 6 OR $carriera == 7 OR $carriera == 8) AND $riparazioni == 'false') {
        output("Oberon è molto nervoso e indaffarato, ha occupato tutti gli attrezzi di lavoro con armi ed armature di ogni genere, ");
        output("sembra che tutti gli avventurieri del villaggio vadano da lui per usufruire del servizio di riparazioni. ");
        output(" Forse dandogli una mano potrai ricominciare ad esercitarti e chissà, magari guadagnare qualcosina...`n");
    }

    output ("`n`n");
    // Inizio tabella Zaino
    output("<table cellspacing=0 cellpadding=2 align='center'>", true);
    output("<tr class='trhead'><td colspan=2 align='center'>`bIl tuo Zaino`b</td></tr>", true);

    // Selezione Materiali Player
    $sqlmateriali = "SELECT materiali.id AS idmateriali, materiali.nome AS nome, materiali.valoremo AS valoremo, materiali.valorege AS valorege,
                     materiali.descrizione AS descrizione FROM zaino, materiali WHERE zaino.idplayer = $idplayer AND zaino.idoggetto = materiali.id";
    $resultmateriali = db_query($sqlmateriali ) or die(db_error(LINK));

    if (db_num_rows($resultmateriali) == 0) {
        output("<tr class='trhead'><td colspan=2 align='center'>`&Non hai oggetti nello zaino`0</td></tr>", true);
    }
    $countrow = db_num_rows($resultmateriali);
    for ($i=0; $i<$countrow; $i++){
    //for ($i = 0;$i < db_num_rows($resultmateriali);$i++) {
        $row = db_fetch_assoc($resultmateriali);
        output("<tr class='" . ($i % 2?"trlight":"trdark") . "'><td>" . ($i + 1) . ".</td><td>$row[nome]</td></tr>", true);

    }
    output("</table>", true);
    // Fine tabella Zaino
}
// editor quantità di materiali (Sook & Excalibur)
if ($_GET['op']=="modmater"){
   if ($_POST['valore']==""){
      output("Valore da inserire`n");
      output("<form action='fabbro.php?op=modmater' method='POST'><input name='valore' value='0'><input type='submit' class='button' value='Scrivi'>`n",true);
      output("Quale Materiale?`n<input type='radio' name='wish' value='1' checked> Carbone`n",true);
      output("<input type='radio' name='wish' value='2'> Ferro`n",true);
      output("<input type='radio' name='wish' value='3'> Rame`n",true);
      output("<input type='radio' name='wish' value='4'> Argento`n",true);
      output("<input type='radio' name='wish' value='5'> Oro</form>",true);
      addnav("","fabbro.php?op=modmater");
   }else{
      $valore = abs((int)$_POST['valore']);
      $mat = $_POST['wish'];
      $tipo = array(
      1=>"Carbone",
      2=>"Metallo",
      3=>"Rame",
      4=>"Argento",
      5=>"Oro");
      output("Variabile settata correttamente. Valore: ".$valore.", Materiale: ".$tipo[$mat]." ");
      addnav("Modifica Quantità Materiali","fabbro.php?op=modmater");
      switch ($mat){
         case 1:
              savesetting("carbone", $valore);
         break;
         case 2:
              savesetting("scagliemetallo", $valore);
         break;
         case 3:
              savesetting("scaglierame", $valore);
         break;
         case 4:
              savesetting("argento", $valore);
         break;
         case 5:
              savesetting("oro", $valore);
         break;
      }
   }
}
//Fine editor quantità di materiali (Sook & Excalibur)
if ($_GET['op']=="materiali"){
    savesetting("scagliemetallo",$_POST['ferro']);
    savesetting("scaglierame",$_POST['rame']);
    savesetting("carbone",$_POST['carbone']);
    savesetting("argento",$_POST['argento']);
    savesetting("oro",$_POST['oro']);
    output("Le quantità dei materiali in vendita da Oberon sono state modificate");
}
//fine editor quantità materiali

// Diventa Garzone
if($_GET['op']=="garzone"){
    output("Ti avvicini a Oberon e gli chiedi :`#\"Cosa devo fare per diventare vostro garzone ?\"`7.`n");
    output("Oberon ti guarda e dice :`&\"Devi portarmi 2 gemme, sai le spese per i materiali ...\"`7.`n`n");
    output("Poi interessato chiedi :`#\"E cosa ci guadagno a diventare garzone ?\"`7.`n");
    output("Oberon risponde :`&\"Una volta che sarai mio allievo, ti insegnerò l'arte del fabbro, e a seconda della tua bravura e del tuo impegno potrai diventare `3garzone, `!apprendista, `2fabbro`& e poi solamente il migliore acquisirà il titolo di`$ Mastro Fabbro");
    output("`&Inoltre io sarò l'unico che giudicherà quando sei pronto a salire di grado, e quando sarai `!apprendista `&potrai iniziare a costruire i primi piccoli oggetti.\" `7.");

}
if($_GET['op']=="diventagarzone"){
    if ($session['user']['gems']>=2) {
        output("Oberon prende le due gemme e dice :`& \"Bene amico da oggi sei un mio allievo, inizia ad esercitarti con il mantice. Quando sarai pronto ad essere apprendista ti avvertirò io,  `\$AL LAVORO PELANDRONE\"`7.`n");
        $session['user']['gems'] -= 2;
        debuglog("paga 2 gemme per intraprendere la carriera di fabbro");
        $session['user']['carriera'] = 5;
        if (getsetting("mastrofabbro","0") != "0") {
            systemmail(getsetting("mastrofabbro","0"),"`^Nuovo garzone","`^".$session['user']['name']." `^è diventato garzone di Oberon!");
        }

    }else{
        output("Oberon aspetta le 2 gemme e dice :`& \"Amico non farmi perdere tempo quì stiamo lavorando. VATTENE E TORNA QUANDO AVRAI LE 2 GEMME\"`7.`n");
    }
}
// Inizio Corsi Specializzazione
if ($_GET['op'] == "corso" AND ($carriera > 4 AND $carriera < 9)) {
    output("Puoi seguire dei corsi di specializzazione per migliorare la tua tecnica di forgia. Ce ne sono per tutti i gusti e tutte le tasche.`n");
    output("Dal corso base a quello avanzato. Ognuno di essi ti farà guadagnare Punti Carriera. I corsi inferiori, ovviamente, forniranno minori ");
    output("conoscenze (e quindi punti carriera) rispetto ai corsi superiori.`n`");
    output("Che corso vuoi seguire?`n");
    addnav("Corsi di Specializzazione");
    addnav("5?`^Base - 500 Oro", "fabbro.php?op=oro5");
    addnav("1?`^Intermedio - 1000 Oro", "fabbro.php?op=oro1");
    addnav("0?`^Avanzato - 5000 Oro", "fabbro.php?op=oro50");
    addnav("O?`^Superiore - 10000 Oro", "fabbro.php?op=oro10");
    addnav("`&Speciale - 1 Gemma", "fabbro.php?op=gemma");
}

if ($_GET['op'] == "corso" AND ($carriera < 5 OR $carriera > 8)) {
    output("Devi essere un Fabbro per poter accedere ai Corsi di Specializzazione !!!`n`n");
}

if ($_GET['op'] == "oro5") {
    if ($session['user']['gold'] < 500) {
        output("Non hai abbastanza oro.`n");
    } else {
        output("Paghi felice i 500 Pezzi d'Oro per il Corso Base ... le tue conoscenze nell'Arte della Forgia migliorano ");
        output("e ti senti più abile!`n");
        $session['user']['punti_carriera'] += (20 + $caso);
        $session['user']['punti_generati'] += (20 + $caso);
        $fama = (20 + $caso)*$session[user][fama_mod];
        $session['user']['fama3mesi'] += $fama;
        debuglog("Guadagna ".(20+$caso)." punti carriera e $fama punti fama da Oberon spendendo 500 oro. Ora ha ".$session['user']['punti_carriera']." punti carriera e ".$session['user']['fama3mesi']." punti fama");
        $session['user']['gold'] -= 500;
    }
}

if ($_GET['op'] == "oro1") {
    if ($session['user']['gold'] < 1000) {
        output("Non hai abbastanza oro.`n");
    } else {
        output("Paghi felice i 1.000 Pezzi d'Oro per il Corso Intermedio ... le tue conoscenze nell'Arte della Forgia migliorano ");
        output("e ti senti più abile!`n");
        $session['user']['punti_carriera'] += (42 + $caso);
        $session['user']['punti_generati'] += (42 + $caso);
        $fama = (42 + $caso)*$session[user][fama_mod];
        $session['user']['fama3mesi'] += $fama;
        debuglog("Guadagna ".(42+$caso)." punti carriera e $fama punti fama da Oberon spendendo 1000 oro. Ora ha ".$session['user']['punti_carriera']." punti carriera e ".$session['user']['fama3mesi']." punti fama");
        $session['user']['gold'] -= 1000;
    }
}

if ($_GET['op'] == "oro50") {
    if ($session['user']['gold'] < 5000) {
        output("Non hai abbastanza oro.`n");
    } else {
        output("Paghi felice i 5.000 Pezzi d'Oro per il Corso Avanzato ... le tue conoscenze nell'Arte della Forgia migliorano ");
        output("e ti senti decisamente più abile!`n");
        $session['user']['punti_carriera'] += (215 + $caso);
        $session['user']['punti_generati'] += (215 + $caso);
        $fama = (215 + $caso)*$session[user][fama_mod];
        $session['user']['fama3mesi'] += $fama;
        debuglog("Guadagna ".(215+$caso)." punti carriera e $fama punti fama da Oberon spendendo 5000 oro. Ora ha ".$session['user']['punti_carriera']." punti carriera e ".$session['user']['fama3mesi']." punti fama");
        $session['user']['gold'] -= 5000;
    }
}

if ($_GET['op'] == "oro10") {
    if ($session['user']['gold'] < 10000) {
        output("Non hai abbastanza oro.`n");
    } else {
        output("Paghi felice i 10.000 Pezzi d'Oro per il Corso Superiore ... le tue conoscenze nell'Arte della Forgia migliorano ");
        output("e ti senti nettamente più abile!`n");
        $session['user']['punti_carriera'] += (440 + $caso);
        $session['user']['punti_generati'] += (440 + $caso);
        $fama = (440 + $caso)*$session[user][fama_mod];
        $session['user']['fama3mesi'] += $fama;
        debuglog("Guadagna ".(440+$caso)." punti carriera e $fama punti fama da Oberon spendendo 10000 oro. Ora ha ".$session['user']['punti_carriera']." punti carriera e ".$session['user']['fama3mesi']." punti fama");
        $session['user']['gold'] -= 10000;
    }
}

if ($_GET['op'] == "gemma") {
    if ($session['user']['gems'] < 1) {
        output("`4Non possiedi nessuna gemma.`n");
    } else {
        output("`#Paghi felice la gemma per il Corso Speciale ... le tue conoscenze nell'Arte della Forgia migliorano ");
        output("e ti senti più abile!`n");
        $session['user']['punti_carriera'] += (100 + $caso);
        $session['user']['punti_generati'] += (100 + $caso);
        $fama = (100 + $caso)*$session[user][fama_mod];
        $session['user']['fama3mesi'] += $fama;
        debuglog("Guadagna ".(100+$caso)." punti carriera e $fama punti fama da Oberon spendendo una gemma. Ora ha ".$session['user']['punti_carriera']." punti carriera e ".$session['user']['fama3mesi']." punti fama");
        $session['user']['gems'] -= 1;
        if ($cento > 89) {
            $buff = array("name" => "`\$Abilità del Fabbro", "rounds" => 15, "wearoff" => "`!La tua abilità scompare e torni normale", "defmod" => 1.6, "roundmsg" => "Senti Oberon al tuo fianco!", "activate" => "defense");
            $session['bufflist']['fabbro'] = $buff;
            debuglog("Guadagna anche l' Abilità del Fabbro donando la gemma");
            output("`%Una leggera aura luminosa ti circonda. Percepisci la presenza di `6Oberon`% al tuo fianco!`n");
        }
    }
}
// Inizio Esercizi
if($_GET['op']=="mantice"){
    if ($session['user']['turns']>0) {
        if ($caso == 4) {
            output("Oberon sta forgiando un'arma. Molti garzoni e apprendisti gli stanno intorno e tu sei il responsabile del mantice.`n Dopo quasi un ora di duro lavoro Oberon ti fa i complimenti e dice:`&\"Hai fatto un ottimo lavoro, BRAVO\"`7.`n");
            $session['user']['punti_carriera']+=(2+$caso);
            $session['user']['punti_generati']+=(2+$caso);
            $fama = (2 + $caso)*$session[user][fama_mod];
            debuglog("Guadagna ".(2+$caso)." punti carriera e $fama punti fama da Oberon esercitandosi al mantice. Ora ha ".$session['user']['punti_carriera']." punti carriera e ".$session['user']['fama3mesi']." punti fama");
            $session['user']['experience']+=($session['user']['level']*4);
            $caso_gold +=50;
            if ($caso2 == 4 && (!zainoPieno ($idplayer))) {
                //$session[qtazaino]++;
                output("Di nascosto dagli altri Oberon ti da un pezzo di carbone e dice :`& \"Te lo sei meritato.\" `7`n");
                $sqldr="INSERT INTO zaino (idoggetto,idplayer) VALUES ('3','{$idplayer}')";
                db_query($sqldr);
                debuglog("riceve 1 pezzo di carbone da Oberon");
            } else {
                output("Di nascosto dagli altri Oberon ti allunga $caso_gold pezzi d'oro, e ti strizza l'occhio`7.`n");
                $session['user']['gold']+= $caso_gold;
                debuglog("riceve $caso_gold oro da Oberon");
            }
        }else {
            if ($caso == 3){
                output("Oberon sta forgiando un'arma molti garzoni e apprendisti gli stanno intorno tu sei il responsabile del mantice.`n Dopo quasi un ora di duro lavoro Oberon ti da una pacca sulla spalla.`n");
                $session['user']['punti_carriera']+=(1+$caso);
                $session['user']['punti_generati']+=(1+$caso);
                $fama = (1 + $caso)*$session[user][fama_mod];
                $session['user']['fama3mesi'] += $fama;
                debuglog("Guadagna ".(1+$caso)." punti carriera e $fama punti fama da Oberon esercitandosi al mantice. Ora ha ".$session['user']['punti_carriera']." punti carriera e ".$session['user']['fama3mesi']." punti fama");
                $session['user']['experience']+=($session['user']['level']*4);
                if ($caso2 == 3) {
                    output("Di nascosto dagli altri Oberon ti allunga $caso_gold pezzi d'oro`7.`n");
                    $session['user']['gold']+= $caso_gold;
                    debuglog("riceve $caso_gold oro da Oberon");
                }
            }else{
                output("Oberon sta forgiando un'arma. Molti garzoni e apprendisti gli stanno intorno e tu sei il responsabile del mantice. Oberon ti guarda di sbieco e poi ti ignora, non hai fatto una gran bella figura.`n");
                $session['user']['punti_carriera']+=($caso);
                $session['user']['punti_generati']+=($caso);
                $fama = ($caso)*$session[user][fama_mod];
                $session['user']['fama3mesi'] += $fama;
                debuglog("Guadagna ".($caso)." punti carriera e $fama punti fama da Oberon esercitandosi al mantice. Ora ha ".$session['user']['punti_carriera']." punti carriera e ".$session['user']['fama3mesi']." punti fama");
                $session['user']['experience']+=($session['user']['level']*4);
            }
        }
        $session['user'][turns]-=1;
    }else{
        output("Sei troppo esausto per lavorare.`n");
    }
}

if($_GET['op']=="incudine"){
    if ($session['user']['turns']>1) {
        if ($caso == 4) {
            output("Oberon segue mentre picchi con il martello l'anima di una spada.`n Dopo quasi un ora di duro lavoro Oberon ti fa i complimenti e dice:`&\"Hai trasmesso al metallo il tuo vero spirito\"`7.`n");
            $session['user']['punti_carriera']+=(4+(4*$caso));
            $session['user']['punti_generati']+=(4+(4*$caso));
            $fama = (4+(4*$caso))*$session[user][fama_mod];
            $session['user']['fama3mesi'] += $fama;
            debuglog("Guadagna ".(4+(4*$caso))." punti carriera e $fama punti fama da Oberon esercitandosi all'incudine. Ora ha ".$session['user']['punti_carriera']." punti carriera e ".$session['user']['fama3mesi']." punti fama");
            $session['user']['experience']+=($session['user']['level']*10);
            if ($caso2 == 4 && (!zainoPieno ($idplayer))) {
                //$session[qtazaino]++;
                output("Di nascosto dagli altri Oberon ti da un pezzo di ferro e dice :`& \"Te lo sei meritato.\" `7`n");
                $sqldr="INSERT INTO zaino (idoggetto,idplayer) VALUES ('1','{$idplayer}')";
                db_query($sqldr);
                debuglog("riceve 1 pezzo di ferro da Oberon");
            } else {
                $caso_gold2 += 70;
                output("Di nascosto dagli altri Oberon ti allunga $caso_gold2 pezzi d'oro, e ti strizza l'occhio`7.`n");
                $session['user']['gold']+= $caso_gold2;
                debuglog("riceve $caso_gold2 oro da Oberon");
            }
        }else {
            if ($caso == 3){
                output("Oberon segue mentre picchi con il martello l'anima di una spada.`n Dopo quasi un ora di duro lavoro Oberon ti da una pacca sulla spalla.`n");
                $session['user']['punti_carriera']+=(2+(3*$caso3));
                $session['user']['punti_generati']+=(2+(3*$caso3));
                $fama = (2+(3*$caso))*$session[user][fama_mod];
                $session['user']['fama3mesi'] += $fama;
                debuglog("Guadagna ".(2+(3*$caso))." punti carriera e $fama punti fama da Oberon esercitandosi all'incudine. Ora ha ".$session['user']['punti_carriera']." punti carriera e ".$session['user']['fama3mesi']." punti fama");
                $session['user']['experience']+=($session['user']['level']*8);
                if ($caso2 == 3) {
                    $session['user']['gold']+= $caso_gold2;
                    output("Di nascosto dagli altri Oberon ti allunga $caso_gold2 pezzi d'oro`7.`n");
                    debuglog("riceve $caso_gold2 oro da Oberon");
                }
            }else{
                output("Qualche colpo è andato a vuoto ma ti sei impegnato. Oberon ti guarda di sbieco e poi ti ignora, non hai fatto una gran bella figura.`n");
                $session['user']['punti_carriera']+=(1+(2*$caso3));
                $session['user']['punti_generati']+=(1+(2*$caso3));
                $fama = (1+(2*$caso))*$session[user][fama_mod];
                $session['user']['fama3mesi'] += $fama;
                debuglog("Guadagna ".(1+(2*$caso))." punti carriera e $fama punti fama da Oberon esercitandosi all'incudine. Ora ha ".$session['user']['punti_carriera']." punti carriera e ".$session['user']['fama3mesi']." punti fama");
                $session['user']['experience']+=($session['user']['level']*6);
            }
        }
        $session['user'][turns]-=2;
    }else{
        output("Sei troppo esausto per lavorare.`n");
    }


}

if($_GET['op']=="affila"){
    if ($session['user'][turns]>=3) {
        if ($caso == 4) {
            output("Oberon segue mentre con la mola affili la lama di una spada.`n Dopo quasi un ora di duro lavoro Oberon ti fa i complimenti e dice:`&\"Un ottimo lavoro continua così\"`7.`n");
            $session['user']['punti_carriera']+=(4+(8*$caso));
            $session['user']['punti_generati']+=(4+(8*$caso));
            $fama = (4+(8*$caso))*$session[user][fama_mod];
            $session['user']['fama3mesi'] += $fama;
            debuglog("Guadagna ".(4+(8*$caso))." punti carriera e $fama punti fama da Oberon esercitandosi nell'affilatura. Ora ha ".$session['user']['punti_carriera']." punti carriera e ".$session['user']['fama3mesi']." punti fama");
            $session['user']['experience']+=($session['user']['level']*15);
            if ($caso2 == 4 && (!zainoPieno ($idplayer))) {
                //$session[qtazaino]++;
                output("Di nascosto dagli altri Oberon ti da un pezzo di rame e dice :`& \"Te lo sei meritato.\" `7`n");
                $sqldr="INSERT INTO zaino (idoggetto,idplayer) VALUES ('2','{$idplayer}')";
                db_query($sqldr);
                debuglog("riceve 1 pezzo di rame da Oberon");
            }else {
                $caso_gold3 += 100;
                output("Di nascosto dagli altri Oberon ti allunga $caso_gold3 pezzi d'oro, e ti strizza l'occhio`7.`n");
                $session['user']['gold']+= $caso_gold3;
            }
        }else {
            if ($caso == 3){
                output("Oberon ti incoraggia sorridente con qualche botta sulla tua povera schiena dolente..`n");
                $session['user']['punti_carriera']+=(2+(6*$caso));
                $session['user']['punti_generati']+=(2+(6*$caso));
                $fama = (2+(6*$caso))*$session[user][fama_mod];
                $session['user']['fama3mesi'] += $fama;
                debuglog("Guadagna ".(2+(6*$caso))." punti carriera e $fama punti fama da Oberon esercitandosi nell'affilatura. Ora ha ".$session['user']['punti_carriera']." punti carriera e ".$session['user']['fama3mesi']." punti fama");
                $session['user']['experience']+=($session['user']['level']*13);
                if ($caso2 == 3) {
                    output("Di nascosto dagli altri Oberon ti allunga $caso_gold3 pezzi d'oro`7.`n");
                    $session['user']['gold']+= $caso_gold3;
                    debuglog("riceve $caso_gold3 oro da Oberon");
                }
            }else{
                output("Un normale lavoro niente di più. Oberon ti guarda in tralice, sa che non ti sei impegnato.`n");
                $session['user']['punti_carriera']+=(1+(4*$caso));
                $session['user']['punti_generati']+=(1+(4*$caso));
                $fama = (1+(4*$caso))*$session[user][fama_mod];
                $session['user']['fama3mesi'] += $fama;
                debuglog("Guadagna ".(1+(4*$caso))." punti carriera e $fama punti fama da Oberon esercitandosi nell'affilatura. Ora ha ".$session['user']['punti_carriera']." punti carriera e ".$session['user']['fama3mesi']." punti fama");
                $session['user']['experience']+=($session['user']['level']*12);
            }
        }
        $session['user']['turns']-=3;
    }else{
        output("Sei troppo esausto per lavorare.`n");
    }
}

if($_GET['op']=="tempra"){
    if ($session['user']['turns']>4 AND $session['user']['playerfights']>0) {
        if ($caso == 3) {
            output("Oberon ti segue mentre tempri del metallo.`n Dopo quasi un ora di duro lavoro Oberon ti fa i complimenti e dice:`&\"Un ottimo lavoro degno del Mastro!!!\"`7.`n");
            $caso_gold3 += 450;
            $session['user']['punti_carriera']+=(5+(10*$caso));
            $session['user']['punti_generati']+=(5+(10*$caso));
            $fama = (5+(10*$caso))*$session[user][fama_mod];
            $session['user']['fama3mesi'] += $fama;
            debuglog("Guadagna ".(5+(10*$caso))." punti carriera e $fama punti fama da Oberon esercitandosi nella tempra. Ora ha ".$session['user']['punti_carriera']." punti carriera e ".$session['user']['fama3mesi']." punti fama");
            $session['user']['experience']+=($session['user']['level']*25);
            if ($caso2 == 2) {
                output("Oberon ti da 1 gemma per l'ottimo lavoro svolto`7.`n");
                $session['user']['gems']+= 1;
                debuglog("riceve 1 gemma da Oberon");
            }
            output("Di nascosto dagli altri Oberon ti allunga $caso_gold3 pezzi d'oro`7.`n");
            $session['user']['gold']+= $caso_gold3;
            debuglog("riceve $caso_gold3 oro da Oberon");
        }else{
            output("Un normale lavoro niente di più. Oberon ti guarda in tralice, sa che non ti sei impegnato.`n");
            $session['user']['punti_carriera']+=(5+(5*$caso));
            $session['user']['punti_generati']+=(5+(5*$caso));
            $fama = (5+(5*$caso))*$session[user][fama_mod];
            $session['user']['fama3mesi'] += $fama;
            debuglog("Guadagna ".(5+(5*$caso))." punti carriera e $fama punti fama da Oberon esercitandosi nella tempra. Ora ha ".$session['user']['punti_carriera']." punti carriera e ".$session['user']['fama3mesi']." punti fama");
            $session['user']['experience']+=($session['user']['level']*20);
            $caso_gold3 += 250;
            $session['user']['gold']+= $caso_gold3;
            debuglog("riceve $caso_gold3 oro da Oberon");
            output("Di nascosto dagli altri Oberon ti allunga $caso_gold3 pezzi d'oro`7.`n");
        }
        $session['user'][turns]-=5;
        $session['user'][playerfights]-=1;
    }else{
        output("Sei troppo esausto per lavorare.`n");
    }


}
// Inizio Info Carriera
if($_GET['op']=="chiedi"){
    output("Ti avvicini a Oberon tutto sudato e provato dal duro lavoro e dici:`n`#\"Maestro come sto andando ? Manca ancora molto alla mia nuova mansione ?\"`7.`n");
    if ($carriera == 5 ) {
        $voto = intval($session['user']['punti_carriera']/50);
        output("Oberon dice :`& \"Non mi piace che il lavoro venga interrotto per queste cose, però visto che lo vuoi sapere in questo momento sei un `$ Garzone `&e la mia considerazione per te è pari a `$ $voto `&su 100 \"`7.`n Sbuffando Oberon torna al lavoro.");
        $session['user']['punti_carriera']-=1;
    }
    if ($carriera == 6 ) {
        $voto = intval($session['user']['punti_carriera']/200);
        output("Oberon dice :`& \"Non mi piace che il lavoro venga interrotto per queste cose, però visto che lo vuoi sapere in questo momento sei un `$ Apprendista `&e la mia considerazione per te è pari a `$ $voto `&su 100 \"`7.`n Sbuffando Oberon torna al lavoro.");
        $session['user']['punti_carriera']-=1;
    }
    if ($carriera == 7 OR $carriera == 8) {
        if ($carriera == 8) {
            output("Oberon gentilmente ti dice:`& \"Sei il `$ Mastro Fabbro `&di questo villaggio, nulla ti è sconosciuto nell'arte della forgia.\"`7.`n Sospirando e scuotendo la testa Oberon torna al lavoro.");
            $session['user']['punti_carriera']-=10;
        }else{
            $voto = intval($session['user']['punti_carriera']/200);
            if ($voto >= 100) {
                $voto = 100;
            }
            output("Oberon dice :`& \"Non mi piace che il lavoro venga interrotto per queste cose, però visto che lo vuoi sapere in questo momento sei un `$ Fabbro `&e la mia considerazione per tè è pari a `$ $voto `&su 100 \"`7.`n Sbuffando Oberon torna al lavoro.");
            $session['user']['punti_carriera']-=1;
        }
    }

}
// Inizio lavorazione Oggetti con Ricetta
if($_GET['op']=="forgia"){
    if ($session['user']['zaino']!=0) {
        output("Non puoi lavorare con un oggetto nello zaino.`n`n");
    }else{
        output("Ti avvicini alla forgia che Oberon fa utilizzare ai suoi apprendisti.`n`n");
        output("Queste sono le ricette che hai con te.`n`n");

        $sql = "SELECT materiali.id AS idmateriali, materiali.nome AS nome, materiali.valoremo AS valoremo, materiali.valorege AS valorege,
            materiali.descrizione AS descrizione FROM zaino, materiali WHERE zaino.idplayer = $idplayer AND zaino.idoggetto = materiali.id AND materiali.tipo = 'R'";

        if ($_GET[og]=="p") {
            $sql = $sql ." AND valorege<5";
            $dimensione=p;
        }
        if ($_GET[og]=="m") {
            $sql = $sql ." AND valorege>=5 AND valorege<50";
            $dimensione=m;
        }
        if ($_GET[og]=="g") {
            $sql = $sql ." AND valorege>=50";
            $dimensione=g;
        }

        output("<table cellspacing=0 cellpadding=2 align='center'>", true);
        output("<tr class='trhead'><td>&nbsp;</td><td>`bNome`b</td><td>`bValore oro`b</td><td>`bValore gemme`b</td></tr>", true);
        $result = db_query($sql) or die(db_error(LINK));
        if (db_num_rows($result) == 0) {
            output("<tr><td colspan=4 align='center'>`&Non hai ricette di questo tipo !`0</td></tr>", true);
        }
        $countrow = db_num_rows($result);
        for ($i=0; $i<$countrow; $i++){
        //for ($i = 0;$i < db_num_rows($result);$i++) {
            $row = db_fetch_assoc($result);
            output("<tr class='" . ($i % 2?"trlight":"trdark") . "'><td>" . ($i + 1) . ".</td><td><A href=fabbro.php?id=$row[idmateriali]&op=ingredienti&og=$dimensione>$row[nome]</a></td><td>$row[valoremo]</td><td>$row[valorege]</td></tr>", true);
            addnav("", "fabbro.php?id={$row['idmateriali']}&op=ingredienti&og=$dimensione");
        }
        output("</table>", true);
    }
}

if($_GET['op']=="ingredienti"){
    $idmateriale = $_GET[id];
    $tutto_ok = 0;
    // Selezione Ricetta
    $sql = "SELECT * FROM  materiali WHERE id = $idmateriale";
    $result = db_query($sql) or die(db_error(LINK));
    $row = db_fetch_assoc($result);
    if ($_GET[og]=="p") {
        $numero_ingredienti = 5;
        $num_ing = 4;
    }
    if ($_GET[og]=="m") {
        $numero_ingredienti = 7;
        $num_ing = 6;
    }
    if ($_GET[og]=="g") {
        $numero_ingredienti = 11;
        $num_ing = 10;
    }

    output ("`bDescrizione Ricetta :`b `n`n");
    output ("$row[descrizione]`n`n`n");
    if ($row['tipo'] == R) {
        output ("`bPer realizzare questa ricetta serve :`b`n`n");
        for ($i = 1;$i < $numero_ingredienti ;$i++) {
            $ingrediente = "ingrediente".$i;
            // Selezione Ingrediente
            $sqlm = "SELECT nome FROM  materiali WHERE id='{$row[$ingrediente]}'";
            $resultm = db_query($sqlm) or die(db_error(LINK));
            $rowm = db_fetch_assoc($resultm);
            // Controllo se Disponibile
            $sqlz = "SELECT id FROM  zaino WHERE idplayer='{$idplayer}' AND idoggetto = '{$row[$ingrediente]}' {$ingr[($i-1)]}";
            $resultz = db_query($sqlz) or die(db_error(LINK));
            $rowz = db_fetch_assoc($resultz);
            //output ("SQL : $sqlz`n`n");
            if (db_num_rows($resultz) > 0) {
                $stato[$i] = "`6in tuo Possesso";
                $ingr[$i] = $ingr[($i-1)]." AND id != '{$rowz['id']}'";
                $ingr_delete[$i] = $ingr_delete[($i-1)]." OR id ='{$rowz['id']}'";
            }else{
                $tutto_ok =1;
                $stato[$i] = "`4NON `6in tuo Possesso";
                $ingr[$i] = $ingr[($i-1)];
                $ingr_delete[$i] = $ingr_delete[($i-1)];
            }
            output ("`0Ingrediente $i : `3{$rowm['nome']} `0-- $stato[$i]`0`n`n");
        }
        //requisito in punti riparazione per usare una ricetta
        switch ($row[dove_oggetto_creato]) {
            case 110:
            $puntiripnec = 38;
            break;
            case 111:
            $puntiripnec = 82;
            break;
            case 112:
            $puntiripnec = 82;
            break;
            case 113:
            $puntiripnec = 111;
            break;
            case 114:
            $puntiripnec = 972;
            break;
            case 115:
            $puntiripnec = 13;
            break;
            case 116;
            $puntiripnec = 21;
            break;
            case 117;
            $puntiripnec = 17;
            break;
            case 118;
            $puntiripnec = 1274;
            break;
        }
        //calcolo punti riparazione a disposizione del fabbro e controllo
        $sqlcreaz = "SELECT punti_riparazione FROM riparazioni WHERE acctid = '{$idplayer}'";
        $resultcreaz = db_query($sqlcreaz) or die(db_error(LINK));
        $refcreaz = db_fetch_assoc($resultcreaz);
        if (db_num_rows($resultcreaz) == 0) {
            $refcreaz[punti_riparazione] = 0;
        }
        if ($refcreaz[punti_riparazione] < $puntiripnec) $tutto_ok = 2;
        if ($row['costo_exp'] > $session['user']['punti_carriera']) $tutto_ok = 3;

        //tutto a posto
        if ($tutto_ok == 0) {
            //inserimento automatico oggetti
            //101 Usato oggetto karnak
            //102 Usato oggetti sgrios
            //accessori
            if ($row[dove_oggetto_creato]==110) {
                $valorernd = mt_rand(10,20);
                $usuraextra = e_rand(0,5);
                $durata = 50 + 5 * $valorernd + $usuraextra;
                $valore=$valorernd;
                $sqlno = "SELECT * FROM  oggetti_nomi where serbatoio=110 ORDER BY rand() LIMIT 1";
                $resultno = db_query($sqlno) or die(db_error(LINK));
                $rowno = db_fetch_assoc($resultno);
                $sql="INSERT INTO oggetti (nome, descrizione, dove, dove_origine, livello, valore, potenziamenti, usura, usuramax, usuraextra)
                        VALUES ('{$rowno['nome']}','{$rowno['descrizione']}','110','1','1','$valorernd','1', '$durata', '$durata', '$usuraextra')";
                db_query($sql);
            }
            //arma piccola
            if ($row[dove_oggetto_creato]==111) {
                $valorernd = mt_rand(25,35);
                $valore=$valorernd;
                $usuraextra = e_rand(0,10);
                $durata = 50 + 5 * $valorernd + $usuraextra;
                $sqlno = "SELECT * FROM  oggetti_nomi where serbatoio=111 ORDER BY rand() LIMIT 1";
                $resultno = db_query($sqlno) or die(db_error(LINK));
                $rowno = db_fetch_assoc($resultno);
                $sql="INSERT INTO oggetti (nome, descrizione, dove, dove_origine, livello, valore, potenziamenti, usura, usuramax, usuraextra)
                        VALUES ('{$rowno['nome']}','{$rowno['descrizione']}','111','1','2','$valorernd','2', '$durata', '$durata', '$usuraextra')";
                db_query($sql);
            }
            //protezione
            if ($row[dove_oggetto_creato]==112) {
                $valorernd = mt_rand(25,35);
                $valore=$valorernd;
                $usuraextra = e_rand(0,10);
                $durata = 50 + 5 * $valorernd + $usuraextra;
                $sqlno = "SELECT * FROM  oggetti_nomi where serbatoio=112 ORDER BY rand() LIMIT 1";
                $resultno = db_query($sqlno) or die(db_error(LINK));
                $rowno = db_fetch_assoc($resultno);
                $sql="INSERT INTO oggetti (nome, descrizione, dove, dove_origine, livello, valore, potenziamenti, usura, usuramax, usuraextra)
                        VALUES ('{$rowno['nome']}','{$rowno['descrizione']}','112','1','2','$valorernd','2', '$durata', '$durata', '$usuraextra')";
                db_query($sql);
            }
            //arma media
            if ($row[dove_oggetto_creato]==113) {
                $valorernd = mt_rand(40,50);
                $valore=$valorernd;
                $usuraextra = e_rand(0,15);
                $durata = 50 + 5 * $valorernd + $usuraextra;
                $sqlno = "SELECT * FROM  oggetti_nomi where serbatoio=113 ORDER BY rand() LIMIT 1";
                $resultno = db_query($sqlno) or die(db_error(LINK));
                $rowno = db_fetch_assoc($resultno);
                $sql="INSERT INTO oggetti (nome, descrizione, dove, dove_origine, livello, valore, potenziamenti, usura, usuramax, usuraextra)
                        VALUES ('{$rowno['nome']}','{$rowno['descrizione']}','113','1','3','$valorernd','3', '$durata', '$durata', '$usuraextra')";
                db_query($sql);
            }
            //spada media
            if ($row[dove_oggetto_creato]==114) {
//                $sqle = "DELETE FROM oggetti WHERE dove='114'";
//                db_query($sqle);
                $pot = mt_rand(10,20);
                $potn = mt_rand(0,3);
                $att = mt_rand(1,10);
                $dif = mt_rand(1,3);
                $turn = mt_rand(1,3);
                $valore = ($pot*$potn)+($att*10)+($dif*10)+($turn*6)+10;
                $usuraextra = e_rand(0,20);
                $durata = 50 + 5*$valore + 100*$att + 100*$dif + 10*$turn + $usuraextra;
                $duratamagica = 0;
                $usuramagicaextra=0;
                if ($turn > 0) $usuramagicaextra = e_rand(0,5);
                if ($turn > 0) $duratamagica = 25 + 10*$turn + $usuramagicaextra;
                $livello = ceil($valore/30);
                $sqlno = "SELECT * FROM  oggetti_nomi where serbatoio=114 ORDER BY rand() LIMIT 1";
                $resultno = db_query($sqlno) or die(db_error(LINK));
                $rowno = db_fetch_assoc($resultno);
                $nome=$rowno['nome']." di mastro ".$session[user][login];
                $desc="Spada forgiata da mastro ".$session[user][login];
                $sql="INSERT INTO oggetti (nome, descrizione, dove, dove_origine, livello, valore, potenziamenti,attack_help,defence_help,turns_help,
                         usura, usuramax, usuraextra, usuramagica, usuramagicamax, usuramagicaextra)
                         VALUES ('{$nome}','{$desc}','114','1','$livello','$valore','$potn','$att','$dif','$turn',
                         '$durata', '$durata', '$usuraextra', '$duratamagica', '$duratamagica', '$usuramagicaextra')";
                db_query($sql);
            }
            //Piccone
            if ($row[dove_oggetto_creato]==115) {
                $pot = mt_rand(10,20);
                $potn = 0;
                $att = 0;
                $dif = 0;
                $turn = 0;
                $valorernd = mt_rand(2,6);
                $valore = ($pot*$potn)+($att*10)+($dif*10)+($turn*6)+$valorernd;
                $usuraextra = e_rand(0,20);
                $durata = 50 + 20*$valore + 100*$att + 100*$dif + 10*$turn + $usuraextra;
                $livello = ceil($valore/30);
                $nome="Piccone da minatore";
                $desc="Piccone per lavorare in miniera";
                $sql="INSERT INTO oggetti (nome, descrizione, dove, dove_origine, livello, valore, potenziamenti,attack_help,defence_help,turns_help, usura, usuramax, usuraextra)
                        VALUES ('{$nome}','{$desc}','115','1','$livello','$valore','$potn','$att','$dif','$turn', '$durata', '$durata', '$usuraextra')";
                db_query($sql);
            }
            //Ascia da Boscaiolo
            if ($row[dove_oggetto_creato]==116) {
                $pot = mt_rand(10,20);
                $potn = 0;
                $att = 0;
                $dif = 0;
                $turn = 0;
                $valorernd = mt_rand(4,10);
                $valore = ($pot*$potn)+($att*10)+($dif*10)+($turn*6)+$valorernd;
                $usuraextra = 50 + e_rand(0,20);
                $durata = 50 + 20*$valore + 100*$att + 100*$dif + 10*$turn + $usuraextra;
                $livello = ceil($valore/30);
                $nome="Ascia da boscaiolo";
                $desc="Ascia per abbattere gli alberi";
                $sql="INSERT INTO oggetti (nome, descrizione, dove, dove_origine, livello, valore, potenziamenti,attack_help,defence_help,turns_help, usura, usuramax, usuraextra)
                        VALUES ('{$nome}','{$desc}','116','1','$livello','$valore','$potn','$att','$dif','$turn', '$durata', '$durata', '$usuraextra')";
                db_query($sql);
            }
            //Sega da Falegname
            if ($row[dove_oggetto_creato]==117) {
                $pot = mt_rand(10,20);
                $potn = 0;
                $att = 0;
                $dif = 0;
                $turn = 0;
                $valorernd = mt_rand(3,8);
                $valore = ($pot*$potn)+($att*10)+($dif*10)+($turn*6)+$valorernd;
                $usuraextra = 50 + e_rand(0,20);
                $durata = 50 + 20*$valore + 100*$att + 100*$dif + 10*$turn + $usuraextra;
                $livello = ceil($valore/30);
                $nome="Sega da falegname";
                $desc="Sega per lavorare la legna e creare oggetti";
                $sql="INSERT INTO oggetti (nome, descrizione, dove, dove_origine, livello, valore, potenziamenti,attack_help,defence_help,turns_help, usura, usuramax, usuraextra)
                        VALUES ('{$nome}','{$desc}','117','1','$livello','$valore','$potn','$att','$dif','$turn', '$durata', '$durata', '$usuraextra')";
                db_query($sql);
            }
             //spadone a 2 mani
            if ($row[dove_oggetto_creato]==118) {
//                $sqle = "DELETE FROM oggetti WHERE dove='118'";
//                db_query($sqle);
                $pot = mt_rand(10,20);
                $potn = mt_rand(0,3);
                $att = mt_rand(1,15);
                $dif = mt_rand(0,3);
                $turn = mt_rand(0,3);
                $valore = ($pot*$potn)+($att*10)+($dif*10)+($turn*6)+10;
                $usuraextra = e_rand(0,20);
                $durata = 50 + 5*$valore + 100*$att + 100*$dif + 10*$turn + $usuraextra;
                $duratamagica = 0;
                $usuramagicaextra=0;
                if ($turn > 0) $usuramagicaextra = e_rand(0,5);
                if ($turn > 0) $duratamagica = 25 + 10*$turn + $usuramagicaextra;
                $livello = ceil($valore/30);
                $sqlno = "SELECT * FROM  oggetti_nomi where serbatoio=114 ORDER BY rand() LIMIT 1";
                $resultno = db_query($sqlno) or die(db_error(LINK));
                $rowno = db_fetch_assoc($resultno);
                $nome="Spadone a 2 mani";
                $desc="Una spada veramente enorme";
                $sql="INSERT INTO oggetti (nome, descrizione, dove, dove_origine, livello, valore, potenziamenti,attack_help,defence_help,turns_help,
                         usura, usuramax, usuraextra, usuramagica, usuramagicamax, usuramagicaextra)
                         VALUES ('{$nome}','{$desc}','118','1','$livello','$valore','$potn','$att','$dif','$turn',
                         '$durata', '$durata', '$usuraextra', '$duratamagica', '$duratamagica', '$usuramagicaextra')";
                db_query($sql);
            }
            // fine inserimento automatico oggetti
            //aggiornamento punti riparazione
            $puntiriparazioneoggettocreato = intval(exp(log($valore)*0.85)*(intval($valore/30)+3));
            $mt_random_creazione = $refcreaz[punti_riparazione] - $puntiriparazioneoggettocreato;
            $sqlupdate = "UPDATE riparazioni SET punti_riparazione = $mt_random_creazione WHERE acctid='{$idplayer}' ";
            db_query($sqlupdate) or die(db_error(LINK));
//Sook, modifica cancellazione una sola ricetta per tipo
            $sqldr = "SELECT * FROM zaino WHERE idplayer='{$idplayer}' AND idoggetto='$idmateriale'";
            $resultdr = db_query($sqldr) or die(db_error(LINK));
            $rowdr = db_fetch_assoc($resultdr);
//fine modifica
            output ("Bene hai tutti gli ingredienti, inizi a lavorare e dopo qualche ora di duro lavoro ottieni il tuo oggetto.`n`n");
            $sqle = "DELETE FROM zaino WHERE idplayer='{$idplayer}' AND (id='".$rowdr['id']."' $ingr_delete[$num_ing])";
            db_query($sqle);
            // inizio assegnazione oggetto
            $sqlri = "SELECT * FROM oggetti WHERE dove = '{$row['dove_oggetto_creato']}' ORDER BY id_oggetti DESC LIMIT 1";
            $resultri = db_query($sqlri) or die(db_error(LINK));
            if (db_num_rows($resultri) == 0) {
                //Caso in cui non ci sono oggetti nel contenitore richiesto errore di gestione
                output("`3L'oggetto collegato alla ricetta manca, segnala il problema all'admin indicando il nome della ricetta usata.`n");
            }else{
                //output ("SQL : $sqlri`n`n");
                $rowri = db_fetch_assoc($resultri);
                output ("`3Hai forgiato questo oggetto : `6" . $rowri['nome'] . "`3`n");
                output ("\"`4Che magnifico oggetto!\"`3, pensi mentre lo analizzi e percepisci che può ricevere `b`5".$rowri['potenziamenti']." `3potenziamenti.`n`n");
                if ($session['user']['zaino'] == "0") {
                    output ("Raccogli l'oggetto e lo metti nello zaino.`n`n");
                    debuglog("costruisce l'oggetto {$rowri['nome']} da Oberon, spendendo $puntiriparazioneoggettocreato punti riparazione e {$row['costo_exp']} punti carriera");
                    $session['user']['zaino'] = $rowri['id_oggetti'];
                    $oggetto_id = $rowri['id_oggetti'];
                    $sqlu = "UPDATE oggetti SET dove=0 WHERE id_oggetti='{$oggetto_id}'";
                    db_query($sqlu) or die(db_error(LINK));
                    $session['user']['punti_carriera'] -= $row['costo_exp'];
                }else{
                    debuglog("Avrebbe dovuto ricevere {$rowri['nome']} id:{$rowri['id_oggetti']} ma non aveva spazio nello zaino");
                    output ("Segnala il problema all'admin indicando quale ricetta hai usato.`n`n");
                }
            }
        } elseif ($tutto_ok == 1) {
            output ("Ti manca qualche ingrediente, torna quando li avrai tutti.`n`n");
        } elseif ($tutto_ok == 2) {
            output("Non disponi di un numero sufficiente di punti riparazione per creare questo oggetto.`n`n");
            $percentualepuntirip = intval($refcreaz[punti_riparazione] / $puntiripnec * 100);
            output("`\$Al momento, hai a disposizione solo il `^$percentualepuntirip`$% dei punti riparazione necessari alla fabbricazione di questo oggetto`n`n");
            output("`)Prima di creare l'oggetto, aumenta i tuoi punti riparazione.");
        } else {
            output("Non hai abbastanza punti carriera a disposizione e la tua abilità non è sufficiente per forgiare questo oggetto.`n`n");
            output("Devi esercitarti ulteriormente prima di essere in grado di utilizzare questa ricetta.");
        }
    }

}
// Inizio Compra/Vendita dei Materiali
// Carbone
if($_GET['op']=="compraca"){
    if ($session['user']['gold']>=$val_carbone){
        if ($carbone >= 1) {
            if (zainoPieno ($idplayer)) {
                output("Purtroppo non hai più spazio nello zaino.`n");
            } else {
                output("Oberon afferra i tuoi ".$val_carbone." pezzi d'oro e ti da 1 pezzo di carbone in cambio.`n`n");
                $session['user']['gold']-=$val_carbone;
                $sqli="INSERT INTO zaino (idoggetto, idplayer) VALUES (3, '{$idplayer}')";
                db_query($sqli) or die(db_error(LINK));
                debuglog("compra 1 pezzo di carbone da Oberon per {$val_carbone} oro");
                if (getsetting("carbone",0) - 1 < 1) {
                    savesetting("carbone","0");
                } else {
                    savesetting("carbone",getsetting("carbone",0)-1);
                }
            }
        } else {
            output("Purtroppo Oberon ha già venduto tutto il carbone che aveva, sei arrivato troppo tardi.`n");
            output("Il carbone è abbastanza comune, non dovrebbe tardare ad arrivare qualche avventuriero con del carbone.`n`n");
        }
    }else{
        output("Oberon accarezza la sua pesante mazza ferrata, ti guarda truce, e agitando l'indice sul tuo muso dice :\"Non fare il furbo con me !\".`n`n");
    }
}

if($_GET['op']=="vendica"){
    $sql = "SELECT * FROM zaino WHERE idoggetto = 3 AND idplayer='{$idplayer}'";
    $result = db_query($sql) or die(db_error(LINK));
    if (db_num_rows($result)<1){
        output("Oberon accarezza la sua pesante mazza ferrata, ti guarda truce, e agitando l'indice sul tuo muso dice :\"Non fare il furbo con me !\".`n`n");
    }else{
        output("Oberon accetta il tuo carbone e ti posa in mano ".($val_carbone/2)." pezzi d'oro in cambio.`n`n");
        $session['user']['gold']+=($val_carbone/2);
        $row = db_fetch_assoc($result);
        $sqld = "DELETE FROM zaino WHERE id = '{$row['id']}'";
        db_query($sqld);
        debuglog("vende 1 pezzo di carbone ad Oberon per ".($val_carbone/2)." oro");
        savesetting("carbone",getsetting("carbone",0)+1);
    }
}
// Rame
if($_GET['op']=="comprasr"){
    if ($session['user']['gold']>=$val_scaglie_rame){
        if ($scaglie_rame >= 1) {
            if (zainoPieno ($idplayer)) {
                output("Purtroppo non hai più spazio nello zaino.`n");
            } else {
                output("Oberon afferra i tuoi ".$val_scaglie_rame." pezzi d'oro e ti da 1 scaglia di rame in cambio.`n`n");
                $session['user']['gold']-=$val_scaglie_rame;
                $sqli="INSERT INTO zaino (idoggetto, idplayer) VALUES (2, '{$idplayer}')";
                db_query($sqli) or die(db_error(LINK));
                debuglog("compra 1 scaglia di rame da Oberon per {$val_scaglie_rame} oro");
                if (getsetting("scaglierame",0) - 1 < 1) {
                    savesetting("scaglierame","0");
                } else {
                    savesetting("scaglierame",getsetting("scaglierame",0)-1);
                }
            }
        } else {
            output("Purtroppo Oberon ha già venduto tutto il rame che aveva, sei arrivato troppo tardi.`n");
            output("Il rame è abbastanza comune, non dovrebbe tardare ad arrivare qualche avventuriero con delle scaglie di rame.`n`n");
        }
    }else{
        output("Oberon accarezza la sua pesante mazza ferrata, ti guarda truce, e agitando l'indice sul tuo muso dice :\"Non fare il furbo con me !\".`n`n");
    }
}

if($_GET['op']=="vendisr"){
    $sql = "SELECT * FROM zaino WHERE idoggetto = 2 AND idplayer='{$idplayer}'";
    $result = db_query($sql) or die(db_error(LINK));
    if (db_num_rows($result)<1){
        output("Oberon accarezza la sua pesante mazza ferrata, ti guarda truce, e agitando l'indice sul tuo muso dice :\"Non fare il furbo con me !\".`n`n");
    }else{
        output("Oberon accetta la tua scaglia di rame e ti posa in mano ".($val_scaglie_rame/2)." pezzi d'oro in cambio.`n`n");
        $session['user']['gold']+=($val_scaglie_rame/2);
        $row = db_fetch_assoc($result);
        debuglog("vende 1 scaglia di rame ad Oberon per ".($val_scaglie_rame/2)." oro ");
        $sqld = "DELETE FROM zaino WHERE id = '{$row['id']}'";
        db_query($sqld);
        savesetting("scaglierame",getsetting("scaglierame",0)+1);
    }
}
//Ferro
if($_GET['op']=="comprasm"){
    if ($session['user']['gold']>=$val_scaglie_metallo){
        if ($scaglie_ferro >= 1) {
            if (zainoPieno ($idplayer)) {
                output("Purtroppo non hai più spazio nello zaino.`n");
            } else {
                output("Oberon afferra i tuoi ".$val_scaglie_metallo." pezzi d'oro e ti da 1 scaglia di ferro in cambio.`n`n");
                $session['user']['gold']-=$val_scaglie_metallo;
                $sqli="INSERT INTO zaino (idoggetto, idplayer) VALUES (1, '{$idplayer}')";
                db_query($sqli) or die(db_error(LINK));
                debuglog("compra 1 scaglia di ferro da Oberon per {$val_scaglie_metallo} oro");
                if (getsetting("scagliemetallo",0) - 1 < 1) {
                    savesetting("scagliemetallo","0");
                } else {
                    savesetting("scagliemetallo",getsetting("scagliemetallo",0)-1);
                }
            }
        } else {
            output("Purtroppo Oberon ha già venduto tutte le scaglie di ferro che aveva, sei arrivato troppo tardi.`n");
            output("Il ferro è abbastanza comune, non dovrebbe tardare ad arrivare qualche avventuriero con delle scaglie di ferro.`n`n");
        }
    }else{
        output("Oberon accarezza la sua pesante mazza ferrata, ti guarda truce, e agitando l'indice sul tuo muso dice :\"Non fare il furbo con me !\".`n`n");
    }
}

if($_GET['op']=="vendism"){
    $sql = "SELECT * FROM zaino WHERE idoggetto = 1 AND idplayer='{$idplayer}'";
    $result = db_query($sql) or die(db_error(LINK));
    if (db_num_rows($result)<1){
        output("Oberon accarezza la sua pesante mazza ferrata, ti guarda truce, e agitando l'indice sul tuo muso dice :\"Non fare il furbo con me !\".`n`n");
    }else{
        output("Oberon accetta le tue scaglie di ferro e ti posa in mano ".($val_scaglie_metallo/2)." pezzi d'oro in cambio.`n`n");
        $session['user']['gold']+=($val_scaglie_metallo/2);
        $row = db_fetch_assoc($result);
        $sqld = "DELETE FROM zaino WHERE id = '{$row['id']}'";
        db_query($sqld);
        debuglog("vende 1 scaglia di ferro ad Oberon per ".($val_scaglie_metallo/2)." oro");
        savesetting("scagliemetallo",getsetting("scagliemetallo",0)+1);
    }
}
// Argento
if($_GET['op']=="compraar"){
    if ($session['user']['gold']>=$val_argento){
        if ($argento >= 1) {
            if (zainoPieno ($idplayer)) {
                output("Purtroppo non hai più spazio nello zaino.`n");
            } else {
                output("Oberon afferra i tuoi ".$val_argento." pezzi d'oro e ti da 1 scaglia di argento in cambio.`n`n");
                $session['user']['gold']-=$val_argento;
                $sqli="INSERT INTO zaino (idoggetto, idplayer) VALUES (5, '{$session['user']['acctid']}')";
                db_query($sqli) or die(db_error(LINK));
                debuglog("compra 1 scaglia d'argento da Oberon per {$val_argento} oro");
                if (getsetting("argento",0) - 1 < 1) {
                    savesetting("argento","0");
                } else {
                    savesetting("argento",getsetting("argento",0)-1);
                }
            }
        } else {
            output("Purtroppo Oberon ha già venduto tutte le scaglie d'argento che aveva, sei arrivato troppo tardi.`n");
            output("L'argento è abbastanza raro non arriverà prima di qualche giorno!.`n`n");
        }
    }else{
        output("Oberon accarezza la sua pesante mazza ferrata, ti guarda truce, e agitando l'indice sul tuo muso dice :\"Non fare il furbo con me !\".`n`n");
    }
}

if($_GET['op']=="vendiar"){
    $sql = "SELECT * FROM zaino WHERE idoggetto = 5 AND idplayer='{$session['user']['acctid']}'";
    $result = db_query($sql) or die(db_error(LINK));
    if (db_num_rows($result)<1){
        output("Oberon accarezza la sua pesante mazza ferrata, ti guarda truce, e agitando l'indice sul tuo muso dice :\"Non fare il furbo con me !\".`n`n");
    }else{
        output("Oberon accetta le tue scaglie d'argento e ti posa in mano ".($val_argento/2)." pezzi d'oro in cambio.`n`n");
        $session['user']['gold']+=($val_argento/2);
        $row = db_fetch_assoc($result);
        $sqld = "DELETE FROM zaino WHERE id = '{$row['id']}'";
        db_query($sqld);
        debuglog("vende 1 scaglia d'argento ad Oberon per ".($val_argento/2)." oro");
        savesetting("argento",getsetting("argento",0)+1);
    }
}
// Oro
if($_GET['op']=="compraor"){
    if ($session['user']['gold']>=$val_oro){
        if ($oro >= 1) {
            if (zainoPieno ($idplayer)) {
                output("Purtroppo non hai più spazio nello zaino.`n");
            } else {
                output("Oberon afferra i tuoi ".$val_oro." pezzi d'oro e ti da 1 scaglia di oro in cambio.`n`n");
                $session['user']['gold']-=$val_oro;
                $sqli="INSERT INTO zaino (idoggetto, idplayer) VALUES (7, '{$session['user']['acctid']}')";
                db_query($sqli) or die(db_error(LINK));
                debuglog("compra 1 scaglia d'oro da Oberon per {$val_oro} oro");
                if (getsetting("oro",0) - 1 < 1) {
                    savesetting("oro","0");
                } else {
                    savesetting("oro",getsetting("oro",0)-1);
                }
            }
        } else {
            output("Purtroppo Oberon ha già venduto tutte le scaglie d'oro che aveva, sei arrivato troppo tardi.`n");
            output("L'oro è molto raro non arriverà prima di qualche settimana!.`n`n");
        }
    }else{
        output("Oberon accarezza la sua pesante mazza ferrata, ti guarda truce, e agitando l'indice sul tuo muso dice :\"Non fare il furbo con me !\".`n`n");
    }
}

if($_GET['op']=="vendior"){
    $sql = "SELECT * FROM zaino WHERE idoggetto = 7 AND idplayer='{$session['user']['acctid']}'";
    $result = db_query($sql) or die(db_error(LINK));
    if (db_num_rows($result)<1){
        output("Oberon accarezza la sua pesante mazza ferrata, ti guarda truce, e agitando l'indice sul tuo muso dice :\"Non fare il furbo con me !\".`n`n");
    }else{
        output("Oberon accetta le tue scaglie d'oro e ti posa in mano ".($val_oro/2)." pezzi d'oro in cambio.`n`n");
        $session['user']['gold']+=($val_oro/2);
        $row = db_fetch_assoc($result);
        $sqld = "DELETE FROM zaino WHERE id = '{$row['id']}'";
        db_query($sqld);
        debuglog("vende 1 scaglia d'oro ad Oberon per ".($val_oro/2)." oro");
        savesetting("oro",getsetting("oro",0)+1);
    }
}
// Sala Riunioni
if ($_GET['op'] == "sala") {
    output("Entri nella sala delle riunioni dei fabbri.`n`n");
    // Maximus modifica mastro fabbro
    /*
    $sql = "SELECT * FROM accounts WHERE carriera = 8";
    $result = db_query($sql) or die(db_error(LINK));
    $row = db_fetch_assoc($result);
    output("L'attuale Mastro Fabbro è : {$row['name']}`n`n");
    */
    $mf = getsetting("mastrofabbro",0);
    if ($mf == 0) {
       output("`6Nessuno raggiunge i requisiti richiesti da `&Oberon `6per occupare la carica di `#Mastro Fabbro`6.`n`n");
    } else {
       $sqlmf = " SELECT `name` FROM `accounts` WHERE `acctid` = '$mf'";
       $resultmf = db_query($sqlmf) or die(db_error(LINK));
       $rowmf = db_fetch_assoc($resultmf);
       output("L'attuale Mastro Fabbro è : {$rowmf['name']}`n`n");
    }
    // Maximus fine
    //Sook, modifica per annuncio del mastro fabbro
    $sql="SELECT * FROM custom WHERE area1='mastrofabbro'";
    $result=db_query($sql);
    $dep = db_fetch_assoc($result);
    if (db_num_rows($result) == 0) {
        $sqli = "INSERT INTO custom (dTime,dDate,area1) VALUES (NOW(),NOW(),'mastrofabbro')";
        $resulti=db_query($sqli);
    }
    if ($carriera==8){
        output("`0<form action=\"fabbro.php?op=sala\" method='POST'>",true);
        output("[Mastro Fabbro] Inserisci Notizia? <input name='meldung' size='80'> ",true);
        output("<input type='submit' class='button' value='Insert'>`n`n",true);
        addnav("","fabbro.php?op=sala");
        if ($_POST['meldung']){
            $sql = "UPDATE custom SET dTime = now(),dDate = now() WHERE area1 = 'mastrofabbro'";
            $result=db_query($sql);
            $sql = "UPDATE custom SET amount = ".$session['user']['acctid']." WHERE area1 = 'mastrofabbro'";
            $result=db_query($sql);
            $sql = "UPDATE custom SET area ='".addslashes($_POST['meldung'])."' WHERE area1 = 'mastrofabbro'";
            $result=db_query($sql);
            $_POST[meldung]="";
        }
        addnav("","news.php");
    }
    $sql="SELECT * FROM custom WHERE area1='mastrofabbro' ORDER BY area1, dDate, dTime DESC";
    $result=db_query($sql);
    $countrow = db_num_rows($result);
    for ($i=0; $i<$countrow; $i++){
    //for($i=0;$i<db_num_rows($result);$i++){
        $dep = db_fetch_assoc($result);
        $lasttime=$dep['dTime'];
        $lastdate=$dep['dDate'];
        $msgfabbro = stripslashes($dep['area']);
        $idgs = $dep['amount'];
        if ($msgfabbro !="") {
            $sqlnome="SELECT name,carriera,login FROM accounts WHERE acctid=$idgs";
            $resultnome=db_query($sqlnome);
            $dep1=db_fetch_assoc($resultnome);
            $nomemf=$dep1['name'];
            if($dep1[carriera]==8){
                output("<big>`n`b`c`@ANNUNCIO DEL MASTRO FABBRO `#$nomemf `@.`0`c`b</big>`n",true);
                output("`8".date("d/m/Y",strtotime($lastdate))." `6".date("h:i:s",strtotime($lasttime))."   `b`^".$msgfabbro."`b`n`n`n");
            }
        }
    }
// fine modifica annuncio mastro fabbro
    addcommentary();
    viewcommentary("Fabbro","dice",30,25);
}
// I Migliori Fabbri
if ($_GET['op']=="migliori") {
    output("In un angolo su una lavagnetta Oberon indica i migliori fabbri. Solo il primo può fregiarsi del titolo di Mastro.`n`n");
    $sqlo = "SELECT * FROM accounts WHERE punti_carriera >= 1 AND carriera > 4 AND carriera < 9 AND superuser = 0 ORDER BY punti_carriera DESC";
    $resulto = db_query($sqlo) or die(db_error(LINK));
    output("<table cellspacing=0 cellpadding=2 align='center'>", true);
    output("<tr class='trhead'><td>&nbsp;</td><td>`bNome`b</td><td>`bLivello`b</td></tr>", true);
    if (db_num_rows($resulto) == 0) {
        output("<tr><td colspan=4 align='center'>`&Non ci sono fabbri in paese`0</td></tr>", true);
    }
    $countrow = db_num_rows($resulto);
    for ($i=0; $i<$countrow; $i++){
    //for ($i = 0;$i < db_num_rows($resulto);$i++) {
        $rowo = db_fetch_assoc($resulto);
        if ($rowo['name'] == $session['user']['name']) {
            output("<tr bgcolor='#007700'>", true);
        } else {
            output("<tr class='" . ($i % 2?"trlight":"trdark") . "'>", true);
        }
        output("<td>" . ($i + 1) . ".</td><td>{$rowo['name']}</td><td>".$arraycarriera[$rowo[carriera]]."</td></tr>", true);
    }
    output("</table>", true);
}
// Inizio Heimdall
if ($_GET['op']=="heimdall") {
    output("`3Heimdall è uno dei più grandi avventurieri del regno. È un grande amico di Oberon e uno dei maggiori cercatori di ricette e materiali del villaggio.`n`n");
    if ($carriera > 4 AND $carriera < 9){
        output("Oggi dai suoi lunghi viaggi ha riportato queste ricette e materiali:`n`n");
        $sql = "SELECT zaino.id AS idzaino, materiali.id AS idmateriali, materiali.nome AS nome, materiali.valoremo AS valoremo, materiali.valorege AS valorege,
              materiali.descrizione AS descrizione FROM zaino, materiali WHERE zaino.idplayer = 3 AND zaino.idoggetto = materiali.id";
        output("<table cellspacing=0 cellpadding=2 align='center'>", true);
        output("<tr class='trhead'><td>&nbsp;</td><td>`bNome`b</td><td>`bOro`b</td><td>`bGemme`b</td></tr>", true);
        $result = db_query($sql) or die(db_error(LINK));
        if (db_num_rows($result) == 0) {
            output("<tr><td colspan=4 align='center'>`&Heimdall non ha nulla oggi.`0</td></tr>", true);
        }
        $countrow = db_num_rows($result);
        for ($i=0; $i<$countrow; $i++){
        //for ($i = 0;$i < db_num_rows($result);$i++) {
            $row = db_fetch_assoc($result);
               output("<tr class='" . ($i % 2?"trlight":"trdark") . "'><td>" . ($i + 1) . ".</td><td><A href=fabbro.php?op=compra_heimdall&az=$row[idzaino]>$row[nome]</a></td><td>$row[valoremo]</td><td>$row[valorege]</td></tr>", true);
               addnav("", "fabbro.php?op=compra_heimdall&az={$row['idzaino']}");
        }
        output("</table>", true);
    } else {
        output("Non essendo tu un Fabbro, Heimdall può acquistare i materiali e le ricette in tuo possesso.`n`n");
    }
    $sql = "SELECT materiali.id AS idmateriali, materiali.nome AS nome, materiali.valoremo AS valoremo,
           materiali.valorege AS valorege, materiali.descrizione AS descrizione,zaino.id AS id FROM zaino, materiali
           WHERE zaino.idplayer = $idplayer AND zaino.idoggetto = materiali.id AND materiali.tipo = 'R'";
    output ("`n`%Ricette in tuo possesso:`n`n");
    output("<table cellspacing=0 cellpadding=2 align='center'>", true);
    output("<tr class='trhead'><td>&nbsp;</td><td>`bNome`b</td><td>Ops</td></tr>", true);
    $result = db_query($sql) or die(db_error(LINK));
    if (db_num_rows($result) == 0) {
        output("<tr><td colspan=4 align='center'>`&Non hai oggetti nello zaino`0</td></tr>", true);
    }else{
    $countrow = db_num_rows($result);
    for ($i=0; $i<$countrow; $i++){
    //for ($i = 0;$i < db_num_rows($result);$i++) {
        $row = db_fetch_assoc($result);
        output("<tr class='" . ($i % 2?"trlight":"trdark") . "'><td>".($i+1).".</td><td>{$row['nome']}</td>", true);
        if ($session['user']['level'] > 4) {
           output("<td><A href=fabbro.php?op=vendiri&prog={$row['id']}> Vendi</a></td></tr>",true);
        } else {
           output("<td></td></tr>",true);
        }
        if ($session['user']['level'] > 4) addnav("","fabbro.php?op=vendiri&prog={$row['id']}");
    }
    }
    output("</table>`n`n", true);

}
// Compra da Heimdall
if ($_GET['op']=="compra_heimdall") {
    if (zainoPieno ($session['user']['acctid'])) {
        output("Purtroppo non hai più spazio nello zaino.`n");
    } else {
          $sql = "SELECT zaino.id AS idzaino, materiali.id AS idmateriali, materiali.nome AS nome, materiali.valoremo AS valoremo, materiali.valorege AS valorege,
                 materiali.descrizione AS descrizione FROM zaino, materiali WHERE zaino.id = '$_GET[az]' AND zaino.idoggetto = materiali.id";
          $result = db_query($sql) or die(db_error(LINK));
          $row = db_fetch_assoc($result);
          output("Heimdall sorridente ti chiede :\"`6Sei sicuro di voler comprare : {$row['nome']}?\"`7`n`n");
          output("<A href=fabbro.php>`\$No, voglio pensarci ancora`n</a>",true);
          output("<A href=fabbro.php?op=si_compra_heimdall&az={$_GET['az']}>`@Si, sono sicuro</a>`n",true);
          addnav("","fabbro.php?op=si_compra_heimdall&az={$_GET['az']}");
          addnav("","fabbro.php");
    }
}

if ($_GET['op']=="si_compra_heimdall") {
    //Excalibur: check per furbetti che fanno multilogin per acquistare 2 ricette da Heimdall
    $sqlchkid = "SELECT acctid,login FROM accounts
                 WHERE (uniqueid = '".$session['user']['uniqueid']."' OR lastip = '".$session['user']['lastip']."')
                 AND acctid <> ".$session['user']['acctid']."
                 AND (heimdall = 1 OR (laston>='".date("Y-m-d H:i:s",strtotime(date("r")."-".getsetting("LOGINTIMEOUT",900)." seconds"))."' AND loggedin = 1) )";
    /*$resultchkid = db_query($sqlchkid) or die(db_error(LINK));
    $rowchkid = db_fetch_assoc($resultchkid);
    $chkid = db_num_rows($resultchkid);
    if ($session['user']['heimdall'] == 0  AND $chkid > 0){
       report(4,"`@Furbata `(Heimdall","`2".$session['user']['login']." (".$session['user']['acctid'].") ha (probabilmente) acquistato 2 ricette da Heimdall con questo PG e con ".$rowchkid['login']." (".$rowchkid['acctid'].")","heimdall");
    }
    //Excalibur: fine check multilogin

    $sqlchkid = "SELECT acctid FROM accounts
                 WHERE (uniqueid = '".$session['user']['uniqueid']."' OR lastip = '".$session['user']['lastip']."')
                 AND acctid <> ".$session['user']['acctid']." AND heimdall = 1";
    */
    $resultchkid = db_query($sqlchkid) or die(db_error(LINK));
    $chkid = db_num_rows($resultchkid);
    $rowchkid = db_fetch_assoc($resultchkid);
    // Hugues 20/05/2009 Tolto controllo si dovrebbe poter acquistare una ricetta per ogni pg
    if ($session['user']['heimdall'] == 0 ){
    //if ($session['user']['heimdall'] == 0 AND $chkid == 0){
       $sql = "SELECT zaino.id AS idzaino, zaino.idplayer AS owner, materiali.id AS idmateriali, materiali.nome AS nome, materiali.valoremo AS valoremo, materiali.valorege AS valorege,
              materiali.descrizione AS descrizione FROM zaino, materiali WHERE zaino.id = '{$_GET[az]}' AND zaino.idoggetto = materiali.id";
       $result = db_query($sql) or die(db_error(LINK));
       $row = db_fetch_assoc($result);
       if ($session['user']['gold']<$row['valoremo'] OR $session['user']['gems']<$row['valorege'] ) {
           output("`\$Heimdall digrignando i denti dice :\"`6Prima fuori i soldi, bello, pensi di fregarmi!!\"`n`n");
       }elseif ($row['owner'] != 3){
           output("Mentre stai decidendo se concludere l'affare o tirarti indietro, vedi Heimdall vendere ".$row['nome']." ad un'altra persona!`n`n");
           output("Ti è andata male, ma forse Heimdall ha ancora qualcosa dello stesso tipo in vendita");
       }else{
           addnews("`%".$session['user']['name']."`5 ha incontrato `\$Heimdall`5 e hanno fatto affari.");
           output("Heimdall dice :\"`6È stato un piacere fare affari con te.\"`n`n");
           $session['user']['heimdall'] = 1;
           $sqlu = "UPDATE zaino SET idplayer='{$idplayer}' WHERE id='{$_GET['az']}'";
           db_query($sqlu) or die(db_error(LINK));
           $session['user']['gold'] -= $row['valoremo'];
           $session['user']['gems'] -= $row['valorege'];
           debuglog("compra ".$row['nome']." da Heimdall per ".$row['valoremo']." oro e ".$row['valorege']." gemme");
       }
    }else{
       output("`b`&Hai già acquistato (tu o un altro tuo PG) una ricetta da Heimdall!!!`b`n`@Lascia che anche gli altri player ");
       output("usufruiscano dei suoi servigi!!!`0`n`n");
    }

}
// Vende a Heimdall
if($_GET['op']=="vendiri"){
    $prog = $_GET['prog'];
    $sql = "SELECT * FROM zaino WHERE idplayer='{$idplayer}' AND id = $prog";
    $result = db_query($sql) or die(db_error(LINK));
    $row = db_fetch_assoc($result);
    $idvendi = $row['id'];
    $tiporicetta = $row['idoggetto'];
    $sql1 = "SELECT valoremo AS oro, valorege AS gemme, nome AS nome FROM materiali WHERE id = $tiporicetta";
    $result1 = db_query($sql1) or die(db_error(LINK));
    $row1 = db_fetch_assoc($result1);
    $orovendi = round($row1['oro']/3*2);
    $gemmevendi = round($row1['gemme']/3*2);
    $session['user']['gold'] += $orovendi;
    $session['user']['gems'] += $gemmevendi;
    if (e_rand(0,10) == 10) {
        $sqlu = "UPDATE zaino SET idplayer = '3' WHERE id = $idvendi";
        $resultu = db_query($sqlu) or die(db_error(LINK));
    } else {
        $sqlu = "DELETE FROM zaino WHERE id = $idvendi";
        $resultu = db_query($sqlu) or die(db_error(LINK));
    }
        output("`3Heimdall prende la tua `\${$row1['nome']}`3 e ti da in cambio `^$orovendi pezzi d'oro `3e `&$gemmevendi gemme.`n`n");
    debuglog("Vende {$row1['nome']} a Heimdall per $orovendi oro e $gemmevendi gemme");
}
// Inizio Riparazioni
// Opzione dei Fabbri per generare Punti Riparazione
if ($_GET['op']=="makeriparazioni") {
    switch($_GET['og']) {
        case "intro":
        $sqlrip = "SELECT punti_riparazione FROM riparazioni WHERE acctid = '{$idplayer}'";
        $resultrip = db_query($sqlrip) or die(db_error(LINK));
        $refrip = db_fetch_assoc($resultrip);
        if ($refrip[punti_riparazione] <= $maxpuntiriparazioneindividuali) {
            output("Oberon mette a disposizione la sua forgia per permetterti di riparare l'equipaggiamento degli altri cittadini.`n");
            output("In questo modo potrai guadagnare dei soldi ma sai che lavorando perderai `b`^3`b `0turni.`n`n");
            output("<table border=0 cellpadding=2 cellspacing=1 align=center>",true);
            if ($session[user][turns]>=3) {
                output("<tr class='trlight'><td><a href=fabbro.php?op=makeriparazioni&og=ripara>`bEsegui Una Riparazione`b</a></td></tr>", true);
                // Maximus, spostato l'addnav nell'IF per evitare di far fare le riparazioni con zero turni
                addnav("","fabbro.php?op=makeriparazioni&og=ripara");
                // fine
            } else {
                output("<tr class='trlight'><td>`bNon hai abbastanza Turni!`b</td></tr>", true);
            }
            output("</table>",true);
            // Maximus, spostato l'addnav nell'IF per evitare di far fare le riparazioni con zero turni
            //addnav("","fabbro.php?op=makeriparazioni&og=ripara");
            // Fine
        } else {
            output("Dopo tutto il lavoro che hai già fatto, le tue braccia sono troppo stanche per continuare");
        }
        break;
        case "ripara":
        $mt_random_riparazione = 0;
        // Garzone
        if ($carriera>=5) {
            $mt_random_riparazione=mt_rand(100,150);
            if (mt_rand(1, 3) == 1) {
                if (getsetting("carbone",0) - 1 > 1) {
                    savesetting("carbone",getsetting("carbone",0)-1);
                }
            }
        }
        // Apprendista
        if ($carriera>=6) {
        $mt_random_riparazione=mt_rand(150,200);
                if (mt_rand(1, 3) == 1) {
                    if (getsetting("scagliemetallo",0) - 1 > 1) {
                    savesetting("scagliemetallo",getsetting("scagliemetallo",0)-1);
                    }
                }
                }
        // Fabbro/ Mastro Fabbro
        if ($carriera>=7) {
            $mt_random_riparazione=mt_rand(200,300);
            if (mt_rand(1, 3) == 1) {
                if (getsetting("scaglierame",0) - 1 > 1) {
                    savesetting("scaglierame",getsetting("scaglierame",0)-1);
                }
            }
        }
        $out =  array(
                1=>"un'arma",
                2=>"un'armatura",
                3=>"un oggetto",
        );
        $out1 = e_rand(1,3);
        output("Ti avvicini alla forgia e cominci a riparare ".$out[$out1]." di un avventuriero, hai generato `^$mt_random_riparazione `0punti riparazione!`n`n");
        // controllo esistenza racord tabella riparazioni
        $sqlrip = "SELECT punti_riparazione FROM riparazioni WHERE acctid = '{$idplayer}'";
        $resultrip = db_query($sqlrip) or die(db_error(LINK));
        $refrip = db_fetch_assoc($resultrip);
        if (db_num_rows($resultrip)>0) {
            // esiste il record vado ad aggiornarlo
            $mt_random_riparazione += $refrip[punti_riparazione];
            $sqlupdate = "UPDATE riparazioni SET punti_riparazione = $mt_random_riparazione WHERE acctid='{$idplayer}' ";
            db_query($sqlupdate) or die(db_error(LINK));
        } else {
            // non esiste il record lo inserisco
            $sqlinsert = "INSERT INTO riparazioni (acctid,punti_riparazione,gold) VALUES ('{$idplayer}','$mt_random_riparazione',0)";
            db_query($sqlinsert) or die(db_error(LINK));
        }
        $session[user][turns]-=3;
        debuglog("ha generato $mt_random_riparazione spendendo 3 turni");
        break;
    }
}
// Servizio Riparazioni, per tutti
if ($_GET['op']=="riparazioni") {

    output("Oberon mette a disposizione della comunità un nuovo servizio di riparazione delle armi e armature.`n");
    output("Questo è lo stato del tuo equipaggiamento.`n`n");

    // calcolo usura dell'arma
    $livello_arma = $session['user']['weapondmg'];
    $value_arma = $session['user']['weaponvalue'];
    $durata_max_usura_arma = intval($livello_arma * max((15 + $session['user']['dragonkills']/2 - 2*$session['user']['reincarna']),10)) + 100;
    $usura_arma = $session['user']['usura_arma'];

    $percentuale_arma = getPercentuale($usura_arma, $durata_max_usura_arma);
    $costo_arma = getCosto($value_arma, $percentuale_arma, $riparazione, $manodopera);

    if (donazioni('tessera_riparazioni')==true) {
        $costo_arma = round($costo_arma/2, 0);
        output("`@Mostri a Oberon la tua tessera riparazioni, che ti dà diritto al 50% di sconto!`n`n");
    }

    if ($livello_arma == 0 || $percentuale_arma <= 0) {$percentuale_arma = 0; $costo_arma = 0;}

    $stwpn = round($percentuale_arma/20);
    $stato_arma = $statowpn[$stwpn];

    // calcolo usura dell'armatura
    $value_armatura = $session['user']['armorvalue'];
    $livello_armatura = $session['user']['armordef'];
    $durata_max_usura_armatura = intval($livello_armatura * max((15 + $session['user']['dragonkills']/2 - 2*$session['user']['reincarna']),10)) + 100;

    $usura_armatura = $session['user']['usura_armatura'];

    $percentuale_armatura = getPercentuale($usura_armatura, $durata_max_usura_armatura);
    $costo_armatura = getCosto($value_armatura, $percentuale_armatura, $riparazione, $manodopera);

    if (donazioni('tessera_riparazioni')==true) {
        $costo_armatura = round($costo_armatura/2, 0);
    }

    if ($livello_armatura == 0 || $percentuale_armatura <= 0) {$percentuale_armatura = 0; $costo_armatura = 0;}

    $starm = round($percentuale_armatura/20);
    $stato_armatura = $statoarm[$starm];

    output("<table border=0 cellpadding=2 cellspacing=1 align=center>",true);
    output("<tr class='trhead'><td>`bOggetto`b</td><td>`bStato`b</td><td>`bCosto Riparazione`b</td><td>`bOps`b</td>",true);
    if ($session['user']['superuser']>0) {
        output("<td>`bPunti Riparazione`b</td>",true);
        output("<td>`bPercentuale`b</td>",true);
    }
    output("</tr>",true);
    output("<tr class='trlight'><td>`&".$session['user']['weapon']."</td><td>$stato_arma</td><td>`^$costo_arma Oro</td><td><A href=fabbro.php?op=ripara&og=arma>Ripara </a></td>",true);
    if ($session['user']['superuser']>0) {
        $punti_rip_select = getPuntiRiparazione($percentuale_arma,$livello_arma);
        output("<td>$punti_rip_select</td>",true);
        output("<td>$percentuale_arma</td>",true);
    }
    output("</tr>",true);
    output("<tr class='trdark'><td>`&".$session['user']['armor']."</td><td>$stato_armatura</td><td>`^$costo_armatura Oro</td><td><A href=fabbro.php?op=ripara&og=armatura>Ripara </a></td>",true);
    if ($session['user']['superuser']>0) {
        $punti_rip_select = getPuntiRiparazione($percentuale_armatura,$livello_armatura);
        output("<td>$punti_rip_select</td>",true);
        output("<td>$percentuale_armatura</td>",true);
    }
    output("</tr>",true);
    if ($session['user']['oggetto'] != 0) {
        $sqlo = "SELECT * FROM oggetti WHERE id_oggetti = '{$session['user']['oggetto']}'";
        $resulto = db_query($sqlo) or die(db_error(LINK));
        $rowo = db_fetch_assoc($resulto);
        $ogg = $rowo['nome'];
        if ($rowo[usuramax]==-1 OR $rowo[usuramax]==0) {
               $percentuale_oggetto = 0;
        } else {
               $percentuale_oggetto = getPercentuale($rowo[usura], $rowo[usuramax]);
        }
        $value_oggetto = (250 + (10 * $rowo[livello]) * $rowo[valore]) ;
        $costo_oggetto = getCosto($value_oggetto, $percentuale_oggetto, $riparazione, $manodopera);
        if (donazioni('tessera_riparazioni')==true) {
            $costo_oggetto = round($costo_oggetto/2, 0);
        }
        $stogg = round($percentuale_oggetto/20);
        $stato_oggetto = $statoogg[$stogg];
        output("<tr class='trlight'><td>`&".$ogg."</td><td>$stato_oggetto</td><td>`^$costo_oggetto Oro</td><td><A href=fabbro.php?op=ripara&og=oggetto>Ripara </a></td>",true);
        if ($session['user']['superuser']>0) {
            $punti_rip_select = getPuntiRiparazione($percentuale_oggetto,$rowo[livello]);
            output("<td>$punti_rip_select</td>",true);
            output("<td>$percentuale_oggetto</td>",true);
        }
        output("</tr>",true);
    }
    if ($session['user']['zaino'] != 0) {
        $sqlz = "SELECT * FROM oggetti WHERE id_oggetti = '{$session['user']['zaino']}'";
        $resultz = db_query($sqlz) or die(db_error(LINK));
        $rowz = db_fetch_assoc($resultz);
        $ogg = $rowz['nome'];
        if ($rowz[usuramax]==-1 OR $rowz[usuramax]==0) {
               $percentuale_oggetto = 0;
        } else {
               $percentuale_oggetto = getPercentuale($rowz[usura], $rowz[usuramax]);
        }
        $value_oggetto = (250 + (10 * $rowz[livello]) * $rowz[valore]) ;
        $costo_oggetto = getCosto($value_oggetto, $percentuale_oggetto, $riparazione, $manodopera);
        if (donazioni('tessera_riparazioni')==true) {
            $costo_oggetto = round($costo_oggetto/2, 0);
        }
        $stogg = round($percentuale_oggetto/20);
        $stato_oggetto = $statoogg[$stogg];
        output("<tr class='trdark'><td>`&".$ogg."</td><td>$stato_oggetto</td><td>`^$costo_oggetto Oro</td><td><A href=fabbro.php?op=ripara&og=oggettozaino>Ripara </a></td>",true);
        if ($session['user']['superuser']>0) {
            $punti_rip_select = getPuntiRiparazione($percentuale_oggetto,$rowz[livello]);
            output("<td>$punti_rip_select</td>",true);
            output("<td>$percentuale_oggetto</td>",true);
        }
        output("</tr>",true);
    }
    output("</table>",true);

    addnav("","fabbro.php?op=ripara&og=arma");
    addnav("","fabbro.php?op=ripara&og=armatura");
    if ($session['user']['oggetto'] != 0) {
        addnav("","fabbro.php?op=ripara&og=oggetto");
    }
    if ($session['user']['zaino'] != 0) {
        addnav("","fabbro.php?op=ripara&og=oggettozaino");
    }
}
// Riparazione vera e propria, per tutti
if ($_GET['op']=="ripara") {
    if ($_GET['og']=="arma") {
        // Arma
        $value_arma = $session['user']['weaponvalue'];
        $livello_oggetto = $session['user']['weapondmg'];
        $usura_arma = $session['user']['usura_arma'];
        $oggetto = $session['user']['weapon'];
        $durata_max_usura_arma = intval($livello_oggetto * max((15 + $session['user']['dragonkills']/2 - 2*$session['user']['reincarna']),10)) + 100;
        $percentuale_arma = getPercentuale($usura_arma, $durata_max_usura_arma);
        $costo_arma = getCosto($value_arma, $percentuale_arma, $riparazione, $manodopera);
                $riparato="l'arma";
    } elseif ($_GET['og']=="armatura") {
        // Armatura
        $value_arma = $session['user']['armorvalue'];
        $livello_oggetto = $session['user']['armordef'];
        $usura_arma = $session['user']['usura_armatura'];
        $oggetto = $session['user']['armor'];
        $durata_max_usura_arma = intval($livello_oggetto * max((15 + $session['user']['dragonkills']/2 - 2*$session['user']['reincarna']),10)) + 100;
        $percentuale_arma = getPercentuale($usura_arma, $durata_max_usura_arma);
        $costo_arma = getCosto($value_arma, $percentuale_arma, $riparazione, $manodopera);
                $riparato="l'armatura";
    } elseif ($_GET['og']=="oggetto") {
        // Oggetto in mano
        $sqlo = "SELECT * FROM oggetti WHERE id_oggetti = '{$session['user']['oggetto']}'";
        $resulto = db_query($sqlo) or die(db_error(LINK));
        $rowo = db_fetch_assoc($resulto);
        $value_arma = (250 + (10 * $rowo[livello]) * $rowo[valore]) ;
        $livello_oggetto = $rowo[livello];
        $usura_arma = $rowo[usura];
        $oggetto = $rowo[nome];
        $durata_max_usura_arma = $rowo[usuramax];
        if ($rowo[usuramax]==-1 OR $rowo[usuramax]==0) {
            $percentuale_arma = 0;
        } else {
            $percentuale_arma = getPercentuale($usura_arma, $durata_max_usura_arma);
        }
        $costo_arma = getCosto($value_arma, $percentuale_arma, $riparazione, $manodopera);
                $riparato="l'oggetto";
    } elseif ($_GET['og']=="oggettozaino") {
        // Oggetto nello zaino
        $sqlo = "SELECT * FROM oggetti WHERE id_oggetti = '{$session['user']['zaino']}'";
        $resulto = db_query($sqlo) or die(db_error(LINK));
        $rowo = db_fetch_assoc($resulto);
        $value_arma = (250 + (10 * $rowo[livello]) * $rowo[valore]) ;
        $livello_oggetto = $rowo[livello];
        $usura_arma = $rowo[usura];
        $oggetto = $rowo[nome];
        $durata_max_usura_arma = $rowo[usuramax];
        if ($rowo[usuramax]==-1 OR $rowo[usuramax]==0) {
            $percentuale_arma = 0;
        } else {
            $percentuale_arma = getPercentuale($usura_arma, $durata_max_usura_arma);
        }
        $costo_arma = getCosto($value_arma, $percentuale_arma, $riparazione, $manodopera);
                $riparato="l'oggetto";
    }

    if (donazioni('tessera_riparazioni')==true) {
        $costo_arma = round($costo_arma/2, 0);
    }

    if ($livello_oggetto == 0 || $percentuale_arma <= 0) {$percentuale_arma = 0; $costo_arma = 0;}

    output("`7Oberon prende il tuo ".$oggetto." e lo studia attentamente.`n");
    if ($_GET['og']=="arma") {
        output("`&\"Questa è proprio una bella arma!");
    } elseif ($_GET['og']=="armatura") {
        output("`&\"Questa è proprio una bella armatura!");
    } else {
        output("`&\"Questa è proprio un bel ".$oggetto."!");
    }
    $status = round($percentuale_arma/20, 0);
    if ($status <= 0) {
        output(" E a quanto vedo è nuova, prima di venire da me almeno utilizzala un pochino...\"`n`n");
        if ($_GET['og']=="arma") {
            output("`7Con un po' di imbarazzo rinfoderi il tuo ".$oggetto."...");
        } elseif ($_GET['og']=="armatura") {
            output("`7Con un po' di imbarazzo indossi il tuo ".$oggetto."...");
        } else {
            output("`7Con un po' di imbarazzo riprendi il tuo ".$oggetto."...");
        }
    } else {
        if ($session['user']['gold'] < $costo_arma) {
            output(" Peccato che tu non abbia contanti per pagarmi la riparazione...\"`n`n");
            if ($_GET['og']=="arma") {
                output("`7Con un po' di imbarazzo rinfoderi il tuo ".$oggetto."...");
            } elseif ($_GET['og']=="armatura") {
                output("`7Con un po' di imbarazzo indossi il tuo ".$oggetto."...");
            } else {
                output("`7Con un po' di imbarazzo riprendi il tuo ".$oggetto."...");
            }
        } else {
            output("Mi divertirò a lavorarci sopra!\"`n");
            // Selezione del Fabbro che effettua la riparazione
            $punti_rip_select = getPuntiRiparazione($percentuale_arma,$livello_oggetto);
            $sqlrip = "SELECT a.acctid, a.punti_riparazione, a.gold, b.name, b.carriera FROM riparazioni a, accounts b WHERE a.acctid = b.acctid AND a.punti_riparazione >= ".$punti_rip_select." ORDER BY rand() LIMIT 1";
            $resultrip = db_query($sqlrip) or die(db_error(LINK));
            $refrip = db_fetch_assoc($resultrip);
            if ((db_num_rows($resultrip)>0) && (donazioni('tessera_riparazioni')==false)){
                // Riparazione fatta da un Player
                if ($refrip['carriera'] == 8) {
                    $livello = 'il ';
                } else {
                    $livello = 'un ';
                }
                $livello = $livello ."". $arraycarriera[$refrip['carriera']];
                $bonus = 6 - $refrip['carriera']; // da +1 per i Garzoni a -2 per il Mastro Fabbro
                output("`7Detto questo si fa consegnare il denaro per poi chiamare ".$refrip['name'].", che sai essere ".$livello.", al quale affida $oggetto...`n");
                output("`7Dopo qualche ora di lavoro ti riconsegna ".$riparato.", ");
                // Inizio riparazioni
                $session['user']['gold']-=$costo_arma;
                $casoRipara = mt_rand(2,9) + $bonus;
                switch($casoRipara){
                    case  0:
                    case  1:
                    case  2:
                    case  3:
                    output("`7sembra che la riparazione gli sia riuscita perfettamente, è praticamente nuovo!`n`n");
                    if ($_GET['og']=="arma") {
                        $session['user']['usura_arma']=$durata_max_usura_arma;
                    } elseif ($_GET['og']=="armatura") {
                        $session['user']['usura_armatura']=$durata_max_usura_arma;
                    } else {
                        $sql="UPDATE oggetti SET usura='{$rowo[usuramax]}' WHERE id_oggetti='{$rowo[id_oggetti]}'";
                        db_query($sql) or die(db_error(LINK));
                    }
                    $perc_guadagnato = 75;
                    debuglog("ripara perfettamente {$oggetto} spendendo $costo_arma oro");
                    break;
                    case  4:
                    case  5:
                    case  6:
                    case  7:
                    case  8:
                    output("`7sembra che la riparazione gli sia riuscita...`n`n");
                    $percentuale_arma -= mt_rand(25,50);
                    $percentuale_riparazione = $durata_max_usura_arma;
                    if ($percentuale_arma > 0) {
                        $percentuale_riparazione=intval(($durata_max_usura_arma * (100 - $percentuale_arma)) / 100);
                    }
                    if ($_GET['og']=="arma") {
                        $session['user']['usura_arma']=$percentuale_riparazione;
                    } elseif ($_GET['og']=="armatura") {
                        $session['user']['usura_armatura']=$percentuale_riparazione;
                    } else {
                        $sql="UPDATE oggetti SET usura='{$percentuale_riparazione}' WHERE id_oggetti='{$rowo[id_oggetti]}'";
                        db_query($sql) or die(db_error(LINK));
                    }
                    $perc_guadagnato = 50;
                    debuglog("ripara in parte {$oggetto} spendendo $costo_arma oro");
                    break;
                    case  9:
                    case 10:
                    output("`7 a giudicare dalle ammaccature la riparazione non è venuta molto bene, ma non hai il coraggio di contraddire l'omone che hai di fronte...`n`n");
                    $percentuale_arma += mt_rand(5,15);
                    if ($percentuale_arma >= 100) {
                      if (e_rand (1,4) == 1){
                        output("`7`b".$oggetto." è talmente usurato che lo devi buttare via, ");
                        if ($_GET['og']=="arma") {
                            output("ti ritrovi ancora una volta a usare i Pugni...");
                            debuglog("ripara {$oggetto} spendendo $costo_arma oro ma si ritrova con i pugni");
                            $session['user']['usura_arma']=999;
                            $session['user']['weapon']='Pugni';
                            $session['user']['attack']-=$session['user']['weapondmg'];
                            $session['user']['weaponvalue']=0;
                            $session['user']['weapondmg']=0;
                        } elseif ($_GET['og']=="armatura") {
                            output("ti ritrovi ancora una volta a usare la T-Shirt...");
                            debuglog("ripara {$oggetto} spendendo $costo_arma oro ma si ritrova con la t-shirt");
                            $session['user']['usura_armatura']=999;
                            $session['user']['armor']='T-Shirt';
                            $session['user']['defence']-=$session['user']['armordef'];
                            $session['user']['armordef'] = 0;
                            $session['user']['armorvalue'] = 0;
                        } elseif ($_GET['og']=="oggetto") {
                            output("adesso non hai più un oggetto in mano...");
                            debuglog("ripara {$oggetto} spendendo $costo_arma oro ma l'oggetto è distrutto");
                            $session['user']['attack'] -= $rowo['attack_help'];
                            $session['user']['defence'] -= $rowo['defence_help'];
                            $session['user']['bonusattack'] -= $rowo['attack_help'];
                            $session['user']['bonusdefence'] -= $rowo['defence_help'];
                            $session['user']['maxhitpoints'] -= $rowo['hp_help'];
                            $session['user']['hitpoints'] -= $rowo['hp_help'];
                            $session['user']['oggetto'] = 0;
                            if ($rowo[usuramagica]!=0 AND $row[usuramagicamax]!=0) $session['user']['playerfights'] -= $rowo['pvp_help'];
                            if ($rowo[usuramagica]!=0 AND $row[usuramagicamax]!=0) $session['user']['turns'] = $session['user']['turns'] - $rowo[turns_help];
                            if ($rowo[usuramagica]!=0 AND $row[usuramagicamax]!=0) $session['user']['bonusfight'] -= $rowo['turns_help'];
                            $sql = "DELETE FROM oggetti WHERE id_oggetti='{$rowo[id_oggetti]}'";
                            db_query($sql) or die(db_error(LINK));
                        } elseif ($_GET['og']=="oggettozaino") {
                            output("adesso non hai più un oggetto da tenere nello saino...");
                            $session['user']['zaino'] = 0;
                            debuglog("ripara {$oggetto} spendendo $costo_arma oro ma l'oggetto è distrutto");
                            $sql = "DELETE FROM oggetti WHERE id_oggetti='{$rowo[id_oggetti]}'";
                            db_query($sql) or die(db_error(LINK));
                        }
                        output("`b`n`n");
                      } else {
                        output("`7`b".$oggetto." è estremamente danneggiato, tanto da essere ormai inutilizzabile, ma fortunatamente è ancora riparabile. `b`n ");
                        if ($_GET['og']=="arma") {
                            $session['user']['usura_arma']=1;
                        } elseif ($_GET['og']=="armatura") {
                            $session['user']['usura_armatura']=1;
                        } else {
                            $sql="UPDATE oggetti SET usura='1' WHERE id_oggetti='{$rowo[id_oggetti]}'";
                            db_query($sql) or die(db_error(LINK));
                        }
                        debuglog("ripara malamente {$oggetto} spendendo $costo_arma");
                      }
                    } else {
                        if ($_GET['og']=="arma") {
                            $session['user']['usura_arma']=intval(($durata_max_usura_arma * (100 - $percentuale_arma)) / 100);
                        } elseif ($_GET['og']=="armatura") {
                            $session['user']['usura_armatura']=intval(($durata_max_usura_arma * (100 - $percentuale_arma)) / 100);
                        } else {
                        $sql="UPDATE oggetti SET usura='".(intval(($durata_max_usura_arma * (100 - $percentuale_arma)) / 100))."' WHERE id_oggetti='{$rowo[id_oggetti]}'";
                        db_query($sql) or die(db_error(LINK));
                        }
                        debuglog("ripara malamente {$oggetto} spendendo $costo_arma");
                    }
                    $costo_arma = round($costo_arma/2, 0);
                    output("`7Per tua fortuna, Oberon ha notato la cattiva riparazione, e ti restituisce metà della cifra pagata, cioè `^$costo_arma monete d'oro");
                    $session['user']['gold'] += $costo_arma;
                    $perc_guadagnato = 20;
                    break;
                }
                // Aggiornamento della tabella riparazioni
                $account_fabbro = $refrip['acctid'];
                $punti_riparazione = $refrip['punti_riparazione'] - $punti_rip_select;
                $oro_guadagnato = $refrip['gold'] + intval($costo_arma * $perc_guadagnato / 100);
                $sqlRip = " UPDATE riparazioni SET punti_riparazione = $punti_riparazione , gold = '$oro_guadagnato' WHERE acctid='$account_fabbro'";
                if ($session['user']['superuser']>0) output("`n".$sqlRip."`n");
                db_query($sqlRip) or die(db_error(LINK));
                if (db_affected_rows(LINK)<=0){
                    output("`n`n`\$Errore`^: Si è verificato un errore nell'aggiornamento della tabella riparazioni, riprova più tardi");
                }
            } else {
                $session['user']['gold']-=$costo_arma;
                // Riparazione fatta da Oberon
                output("`7Detto questo si fa consegnare il denaro per poi andare alla forgia...`n");
                output("`7Dopo qualche ora di lavoro ti riconsegna ".$riparato."...`n");
                // Inizio riparazioni
                $casoRipara = mt_rand(0,9);
                if (donazioni('tessera_riparazioni')==true) $casoRipara = 0;
                switch ($casoRipara) {
                    case 0:
                    case 1:
                    case 2:
                    if (donazioni('tessera_riparazioni')==true) {
                        output("`n`@Poichè hai una tessera riparazioni, Oberon ha lavorato con la massima cura, e il tuo oggetto ti sembra nuovo di zecca!`n`n");
                        debuglog("ripara perfettamente {$oggetto} spendendo $costo_arma oro con una tessera riparazioni (Oberon)");
                    } else {
                        output("`7Sembra che la riparazione gli sia riuscita perfettamente, è praticamente nuovo!`n`n");
                        debuglog("ripara perfettamente {$oggetto} spendendo $costo_arma oro (Oberon)");
                    }
                    if ($_GET['og']=="arma") {
                        $session['user']['usura_arma']=$durata_max_usura_arma;
                    } elseif ($_GET['og']=="armatura") {
                        $session['user']['usura_armatura']=$durata_max_usura_arma;
                    } else {
                        $sql="UPDATE oggetti SET usura='{$rowo[usuramax]}' WHERE id_oggetti='{$rowo[id_oggetti]}'";
                        db_query($sql) or die(db_error(LINK));
                    }
                    break;
                    case 3:
                    case 4:
                    case 5:
                    case 6:
                    case 7:
                    output("`7Sembra che la riparazione gli sia riuscita...`n`n");
                    $percentuale_arma -= mt_rand(25,50);
                    $percentuale_riparazione = $durata_max_usura_arma;
                    if ($percentuale_arma > 0) {
                        $percentuale_riparazione=intval(($durata_max_usura_arma * (100 - $percentuale_arma)) / 100);
                    }
                    if ($_GET['og']=="arma") {
                        $session['user']['usura_arma']=$percentuale_riparazione;
                    } elseif ($_GET['og']=="armatura") {
                        $session['user']['usura_armatura']=$percentuale_riparazione;
                    } else {
                        $sql="UPDATE oggetti SET usura='{$percentuale_riparazione}' WHERE id_oggetti='{$rowo[id_oggetti]}'";
                        db_query($sql) or die(db_error(LINK));
                    }
                    debuglog("ripara in parte {$oggetto} spendendo $costo_arma oro (Oberon)");
                    break;
                    case 8:
                    case 9:
                    output("`7A giudicare dalle ammaccature la riparazione non è venuta molto bene ma non hai il coraggio di contraddire l'omone che hai di fronte...`n`n");
                    $percentuale_arma += mt_rand(5,15);
                    if ($percentuale_arma >= 100) {
                      if (e_rand (1,4) == 1){
                        output("`7`b".$oggetto." è talmente usurato che lo devi buttare via, ");
                        if ($_GET['og']=="arma") {
                            output("ti ritrovi ancora una volta a usare i Pugni...");
                            debuglog("ripara {$oggetto} spendendo $costo_arma oro ma si ritrova con i pugni (Oberon)");
                            $session['user']['usura_arma']=999;
                            $session['user']['weapon']='Pugni';
                            $session['user']['attack']-=$session['user']['weapondmg'];
                            $session['user']['weaponvalue']=0;
                            $session['user']['weapondmg']=0;
                        } elseif ($_GET['og']=="armatura") {
                            output("ti ritrovi ancora una volta a usare la T-Shirt...");
                            debuglog("ripara {$oggetto} spendendo $costo_arma oro ma si ritrova con la t-shirt (Oberon)");
                            $session['user']['usura_armatura']=999;
                            $session['user']['armor']='T-Shirt';
                            $session['user']['defence']-=$session['user']['armordef'];
                            $session['user']['armordef'] = 0;
                            $session['user']['armorvalue'] = 0;
                        } elseif ($_GET['og']=="oggetto") {
                            output("adesso non hai più un oggetto in mano...");
                            debuglog("ripara {$oggetto} spendendo $costo_arma oro ma l'oggetto è distrutto (Oberon)");
                            $session['user']['attack'] -= $rowo['attack_help'];
                            $session['user']['defence'] -= $rowo['defence_help'];
                            $session['user']['bonusattack'] -= $rowo['attack_help'];
                            $session['user']['bonusdefence'] -= $rowo['defence_help'];
                            $session['user']['maxhitpoints'] -= $rowo['hp_help'];
                            $session['user']['hitpoints'] -= $rowo['hp_help'];
                            if ($rowo[usuramagica]!=0 AND $row[usuramagicamax]!=0) $session['user']['playerfights'] -= $rowo['pvp_help'];
                            if ($rowo[usuramagica]!=0 AND $row[usuramagicamax]!=0) $session['user']['turns'] = $session['user']['turns'] - $rowo[turns_help];
                            if ($rowo[usuramagica]!=0 AND $row[usuramagicamax]!=0) $session['user']['bonusfight'] -= $rowo['turns_help'];
                            $sql = "DELETE FROM oggetti WHERE id_oggetti='{$rowo[id_oggetti]}'";
                            db_query($sql) or die(db_error(LINK));
                        } elseif ($_GET['og']=="oggettozaino") {
                            output("adesso non hai più un oggetto da tenere nello saino...");
                            debuglog("ripara {$oggetto} spendendo $costo_arma oro ma l'oggetto è distrutto (Oberon)");
                            $sql = "DELETE FROM oggetti WHERE id_oggetti='{$rowo[id_oggetti]}'";
                            db_query($sql) or die(db_error(LINK));
                        }
                        output("`b`n`n");
                      } else {
                        output("`7`b".$oggetto." è estremamente danneggiato, tanto da essere ormai inutilizzabile, ma fortunatamente è ancora riparabile. `b`n ");
                        if ($_GET['og']=="arma") {
                            $session['user']['usura_arma']=1;
                        } elseif ($_GET['og']=="armatura") {
                            $session['user']['usura_armatura']=1;
                        } else {
                            $sql="UPDATE oggetti SET usura='1' WHERE id_oggetti='{$rowo[id_oggetti]}'";
                            db_query($sql) or die(db_error(LINK));
                        }
                        debuglog("ripara malamente {$oggetto} spendendo $costo_arma (Oberon)");
                      }
                    } else {
                        if ($_GET['og']=="arma") {
                            $session['user']['usura_arma']=intval(($durata_max_usura_arma * (100 - $percentuale_arma)) / 100);
                        } elseif ($_GET['og']=="armatura") {
                            $session['user']['usura_armatura']=intval(($durata_max_usura_arma * (100 - $percentuale_arma)) / 100);
                        } else {
                        $sql="UPDATE oggetti SET usura='".(intval(($durata_max_usura_arma * (100 - $percentuale_arma)) / 100))."' WHERE id_oggetti='{rowo[id_oggetti]}'";
                        db_query($sql) or die(db_error(LINK));
                        }
                        debuglog("ripara malamente {$oggetto} spendendo $costo_arma (Oberon)");
                    }
                    $costo_arma = round($costo_arma/2, 0);
                    output("`7Comunque, Oberon ti restituisce metà della cifra pagata, cioè `^$costo_arma monete d'oro");
                    $session['user']['gold'] += $costo_arma;
                    break;
                } // end switch

            }
        }
    }
}
// Opzione dei Fabbri, riscossione della paga per le riparazioni
if ($_GET['op']=="riscuoti") {
    output ("Ti avvicini ad Oberon e gli chiedi a quanto ammonta la tua paga, ");
    output("lui ti guarda pensieroso e consulta un grande registro.`n");
    $sqlrip = "SELECT gold, riscosso FROM riparazioni WHERE acctid = {$idplayer}";
    $resultrip = db_query($sqlrip) or die(db_error(LINK));
    $refrip = db_fetch_assoc($resultrip);
    $livello = $arraycarriera[$carriera];

    if (db_num_rows($resultrip)>0 && $refrip[gold]>0){
       // LIMITAZIONE PAGA FABBRI
       if ($refrip[riscosso]==1) {
          // già riscosso al newday
          output("Ti batte su una spalla e dice : `&\"Caro il mio $livello... Gli affari non stanno andando molto bene e per oggi hai già preso la tua paga, torna domani...\"`n");
       } else {
          $maxpaga = (500 * $session['user']['level']);
          if ($maxpaga < $refrip['gold']) {
             output("Ti batte su una spalla e dice : `&\"E bravo il mio $livello...Ti sei guadagnato la paga di `^$refrip[gold] Monete D'Oro `&per le riparazioni svolte ");
             output(" però gli affari non stanno andando molto bene, e ora non posso darti più di `^$maxpaga Monete D'Oro`&. Torna domani e forse potrò finire di pagarti\"`n`n");
             output("`)Detto questo, ti porge un sacchetto con le monete");
             debuglog("riceve $maxpaga per le riparazioni fatte, ma ha ancora un credito da riscuotere");
             $session['user']['gold']+=$maxpaga;
             $refrip['gold'] -= $maxpaga;
             $sqlupdate = "UPDATE riparazioni SET gold = {$refrip['gold']}, riscosso=1 WHERE acctid='{$idplayer}' ";
             db_query($sqlupdate) or die(db_error(LINK));
          } else {
             output("Ti batte su una spalla, ti consegna un sacchetto e dice : `&\"E bravo il mio $livello...Ti sei guadagnato la paga di `^$refrip[gold] Monete D'Oro `&per le riparazioni svolte\"`n");
             debuglog("riceve {$refrip[gold]} per le riparazioni fatte");
             $session['user']['gold']+=$refrip[gold];
             $sqlupdate = "UPDATE riparazioni SET gold = 0, riscosso=1 WHERE acctid='{$idplayer}' ";
             db_query($sqlupdate) or die(db_error(LINK));
          }
       }
   } else {
        output("Ti batte su una spalla e dice : `&\"Caro il mio $livello... Nessun avventuriero ha ancora usufruito dei tuoi servigi, non hai guadagnato nulla.\"`n");
   }
}
//Luke  vendita ricette base.
if ($_GET['op']=="ricette") {
    output ("Oberon ha deciso di vendere le ricette più comuni direttamente nel suo negozio, ");
    output("ecco l'elenco delle ricette disponibili.`n`n");
    output ("`n`n");
    output("<table cellspacing=0 cellpadding=2 align='center'>", true);
    output("<tr class='trhead'><td align='center'>`bRicetta`b</td><td align='center'>`bCosto oro`b</td><td align='center'>`bCosto gemme`b</td><td align='center'>`bCompra`b</td></tr>", true);
    output("<tr align='center' class='" . (1 % 2?"trlight":"trdark") . "'><td>Piccone da minatore</td><td>700</td><td>1</td><td><A href=fabbro.php?op=comprapic>Compra </a></td></tr>", true);
    addnav("", "fabbro.php?op=comprapic");
    output("<tr align='center' class='" . (2 % 2?"trlight":"trdark") . "'><td>Ascia da boscaiolo</td><td>900</td><td>1</td><td><A href=fabbro.php?op=compraasc>Compra </a></td></tr>", true);
    addnav("", "fabbro.php?op=compraasc");
    output("<tr align='center' class='" . (1 % 2?"trlight":"trdark") . "'><td>Sega da falegname</td><td>800</td><td>1</td><td><A href=fabbro.php?op=compraseg>Compra </a></td></tr>", true);
    addnav("", "fabbro.php?op=compraseg");
    output("<tr align='center' class='" . (2 % 2?"trlight":"trdark") . "'><td>Accessori</td><td>1000</td><td>2</td><td><A href=fabbro.php?op=compraacc>Compra </a></td></tr>", true);
    addnav("", "fabbro.php?op=compraacc");
    output("<tr align='center' class='" . (1 % 2?"trlight":"trdark") . "'><td>Arma piccola</td><td>3600</td><td>3</td><td><A href=fabbro.php?op=compraarp>Compra </a></td></tr>", true);
    addnav("", "fabbro.php?op=compraarp");
    output("<tr align='center' class='" . (2 % 2?"trlight":"trdark") . "'><td>Arma media</td><td>7400</td><td>7</td><td><A href=fabbro.php?op=compraarm>Compra </a></td></tr>", true);
    addnav("", "fabbro.php?op=compraarm");
    output("</table>", true);

}
if ($_GET['op']=="compraarm") {
    if ($session['user']['gold']>=7400 AND $session['user']['gems']>=7){
        if (zainoPieno($idplayer)==true){
            output ("Non hai posto per questa questa ricetta, il tuo zaino è pieno !");
        }else{
            output ("Eccoti la ricetta per costruire un arma media!");
            $session['user']['gold'] -= 7400;
            $session['user']['gems'] -= 7;
            $sqldr="INSERT INTO zaino (idoggetto,idplayer) VALUES ('13','".$session['user']['acctid']."')";
            db_query($sqldr);
            debuglog("compra una ricetta per Arma Media da Oberon");
        }
    }else{
        output ("Non puoi permetterti questa ricetta !");
    }

}
if ($_GET['op']=="compraarp") {
    if ($session['user']['gold']>=3600 AND $session['user']['gems']>=3){
        if (zainoPieno($idplayer)==true){
            output ("Non hai posto per questa questa ricetta, il tuo zaino è pieno !");
        }else{
            output ("Eccoti la ricetta per costruire un arma piccola!");
            $session['user']['gold'] -= 3600;
            $session['user']['gems'] -= 3;
            $sqldr="INSERT INTO zaino (idoggetto,idplayer) VALUES ('4','".$session['user']['acctid']."')";
            db_query($sqldr);
            debuglog("compra una ricetta per Arma Piccola da Oberon");
        }
    }else{
        output ("Non puoi permetterti questa ricetta !");
    }

}
if ($_GET['op']=="comprapic") {
    if ($session['user']['gold']>=700 AND $session['user']['gems']>=1){
        if (zainoPieno($idplayer)==true){
            output ("Non hai posto per questa questa ricetta, il tuo zaino è pieno !");
        }else{
            output ("Eccoti la ricetta per costruire un piccone!");
            $session['user']['gold'] -= 700;
            $session['user']['gems'] -= 1;
            $sqldr="INSERT INTO zaino (idoggetto,idplayer) VALUES ('19','".$session['user']['acctid']."')";
            db_query($sqldr);
            debuglog("compra una ricetta per Piccone da Minatore da Oberon");
        }
    }else{
        output ("Non puoi permetterti questa ricetta !");
    }

}
if ($_GET['op']=="compraasc") {
    if ($session['user']['gold']>=900 AND $session['user']['gems']>=1){
        if (zainoPieno($idplayer)==true){
            output ("Non hai posto per questa questa ricetta, il tuo zaino è pieno !");
        }else{
            output ("Eccoti la ricetta per costruire un'ascia!");
            $session['user']['gold'] -= 900;
            $session['user']['gems'] -= 1;
            $sqldr="INSERT INTO zaino (idoggetto,idplayer) VALUES ('25','".$session['user']['acctid']."')";
            db_query($sqldr);
            debuglog("compra una ricetta per Ascia da Boscaiolo da Oberon");
        }
    }else{
        output ("Non puoi permetterti questa ricetta !");
    }

}if ($_GET['op']=="compraseg") {
    if ($session['user']['gold']>=800 AND $session['user']['gems']>=1){
        if (zainoPieno($idplayer)==true){
            output ("Non hai posto per questa questa ricetta, il tuo zaino è pieno !");
        }else{
            output ("Eccoti la ricetta per costruire una sega!");
            $session['user']['gold'] -= 800;
            $session['user']['gems'] -= 1;
            $sqldr="INSERT INTO zaino (idoggetto,idplayer) VALUES ('37','".$session['user']['acctid']."')";
            db_query($sqldr);
            debuglog("compra una ricetta per Sega da Falegname da Oberon");
        }
    }else{
        output ("Non puoi permetterti questa ricetta !");
    }

}

if ($_GET['op']=="compraacc") {
    if ($session['user']['gold']>=1000 AND $session['user']['gems']>=2){
        if (zainoPieno($idplayer)==true){
            output ("Non hai posto per questa questa ricetta, il tuo zaino è pieno !");
        }else{
            output ("Eccoti la ricetta per costruire un piccolo accessorio!");
            $session['user']['gold'] -= 1000;
            $session['user']['gems'] -= 2;
            $sqldr="INSERT INTO zaino (idoggetto,idplayer) VALUES ('6','".$session['user']['acctid']."')";
            db_query($sqldr);
            debuglog("compra una ricetta per Accessori da Oberon");
        }
    }else{
        output ("Non puoi permetterti questa ricetta !");
    }

}
/*
//inizio mercatino
if ($_GET['op'] == "mercatino") {
   addnav("Mercatino");
   addnav("O?Esamina gli Oggetti", "fabbro.php?op=mercatino&og=esamina");
   addnav("T?Valuta i Tuoi Oggetti", "fabbro.php?op=mercatino&og=valuta");
   addnav("S?Scambia Oggetti", "fabbro.php?op=mercatino&og=scambia");
   addnav("I?Incassa", "fabbro.php?op=mercatino&og=incassa");
   addnav("R?Ritira un tuo oggetto in vendita", "fabbro.php?op=mercatino&og=ritira");
   if ($_GET['og']=="") {
      output ("Il grande fiuto degli affari di Oberon ha fatto ancora centro, con la consulenza di Brax ha aperto al pubblico una parte della sua bottega dove tutti posso esporre e comprare oggetti.`n");
      output ("Oberon chiede solamente un contributo di `^`b500 Monete`b`0 per ogni pezzo esposto, garantendo al venditore una transazione sicura.");
   } // end ""
   if ($_GET['og'] == "esamina") {
      if ($_GET['id'] == null) {
        //gestione pagine, Sook
        addnav("Elenco");
        $ppp=200; // Linee da mostrare per pagina
        if (!$_GET['limit']){
            $page=0;
        }else{
            $page=(int)$_GET['limit'];
            addnav("Pagina Precedente","fabbro.php?op=mercatino&og=esamina&limit=".($page-1)."");
        }
        $limit="".($page*$ppp).",".($ppp+1);

         $sql = "SELECT o.*,
                 a.name
                 FROM oggetti o
                 INNER JOIN accounts a ON a.acctid=o.acctid
                 WHERE dove = 7 ORDER BY livello DESC,nome DESC LIMIT $limit";
         output("`c`b`&Oggetti attualmente esposti`b`c`n");
         output("<table cellspacing=0 cellpadding=2 align='center'><tr>
                 <td>&nbsp;</td><td>`b`@Nome`b</td>
                 <td>`b`#Livello`b</td>
                 <td>`b`&Costo`b</td>
                 <td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td>
                 <td>`b`@HP`b</td><td>&nbsp;</td>
                 <td>`b`\$Att.`b</td><td>&nbsp;</td>
                 <td>`b`^Dif.`b</td><td>&nbsp;</td>
                 <td>`b`%Potenz.`b</td><td>&nbsp;</td>
                 <td>`b`^Oro/g.`b</td><td>&nbsp;</td>
                 <td>`b`&Gem/g.`b</td>
                 <td>`b`VProprietario.`b</td>
                 <td>`b`(Usura Fisica.`b</td>
          <td>`b`8Usura Magica.`b</td>",true);
         if ($session['user']['superuser'] > 2) {
             output("<td>`b`#ID`b</td>",true);
         }
         output("</tr>", true);
         $result = db_query($sql) or die(db_error(LINK));
         if (db_num_rows($result)>$ppp) addnav("Pagina Successiva","fabbro.php?op=mercatino&og=esamina&limit=".($page+1)."");
         if (db_num_rows($result) == 0) {
             output("<tr><td colspan=20 align='center'>`&Non ci sono oggetti in esposizione mi spiace`0</td></tr>", true);
         }

         $countrow = db_num_rows($result);
         for ($i=0; $i<$countrow; $i++){
         //for ($i = 0;$i < db_num_rows($result);$i++) {
             $row = db_fetch_assoc($result);
             output("<tr class='" . ($i % 2?"trlight":"trdark") . "'><td align=right>" . ($i + $page*$ppp + 1) . ".</td>
                     <td><A href=fabbro.php?op=mercatino&og=esamina&id=$row[id_oggetti]>`@".$row['nome']."</a></td>
                     <td>`b`#".$row['livello']."`b</td>
                     <td>`b`&gem:".$row['mercatino_gemme']." / oro:".$row['mercatino_oro']."`b</td>
                     <td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td>
                     <td align='center'>`b`@".$row['hp_help']."`b</td><td>&nbsp;</td>
                     <td align='center'>`b`\$".$row['attack_help']."`b</td><td>&nbsp;</td>
                     <td align='center'>`b`^".$row['defence_help']."`b</td><td>&nbsp;</td>
                     <td align='center'>`b`%".$row['potenziamenti']."`b</td><td>&nbsp;</td>
                     <td align='center'>`b`^".$row['gold_help']."`b</td><td>&nbsp;</td>
                     <td align='center'>`b`&".$row['gems_help']."`b</td>
             <td align='center'>`b`V".$row['name']."`b</td>",true);
             if ($row['usuramax']>0) output("<td align='center'>`b`(".(intval(100-(100*$row['usura']/$row['usuramax'])))."`b</td>",true);
             else output("<td align='center'>`b`(non soggetto`b</td>",true);
             if ($row['usuramagicamax']>0) output("<td align='center'>`b`8".(intval(100-(100*$row['usuramagica']/$row['usuramagicamax'])))."`b</td>",true);
             else output("<td align='center'>`b`8non soggetto`b</td>",true);
             if ($session['user']['superuser'] > 2) {
                 output("<td>`b`#".$row['id_oggetti']."`b</td>",true);
             }
             output("</tr>", true);
             addnav("", "fabbro.php?op=mercatino&og=esamina&id=$row[id_oggetti]");
         }
         output("</table>", true);
      } else {
        $sqlo = "SELECT * FROM oggetti WHERE id_oggetti = $_GET[id]";
        $resulto = db_query($sqlo) or die(db_error(LINK));
        $row = db_fetch_assoc($resulto);
        output ("<table>", true);
        output ("<tr><td>`7Oggetto: </td><td>`&".$row['nome']."</td></tr>", true);
        output ("<tr><td>`6Descrizione: </td><td>`^".$row['descrizione']."</td></tr>", true);
        if ($row['pregiato']==true) output ("<tr><td>`^`bL'oggetto è pregiato`b </td><td></td></tr>", true);
        output ("<tr><td>`3Livello: </td><td>`#".$row['livello']."</td></tr>", true);
        output ("<tr><td>`7Aiuto HP: </td><td>`&".$row['hp_help']."</td></tr>", true);
        output ("<tr><td>`4Aiuto Attacco: </td><td>`\$".$row['attack_help'] . "</td></tr>", true);
        output ("<tr><td>`6Aiuto Difesa: </td><td>`^".$row['defence_help'] . "</td></tr>", true);
        output ("<tr><td>`8Turni Extra: </td><td>`^" . $row['turns_help'] . "</td></tr>", true);
        if ($row['gold_help']!=0) output ("<tr><td>`^Oro generato: </td><td>`^" . $row['gold_help'] . "/giorno</td></tr>", true);
        if ($row['gems_help']!=0) output ("<tr><td>`&Gemme generate: </td><td>`&" . $row['gems_help'] . "/giorno</td></tr>", true);
        if ($row['special_help']!=0) output ("<tr><td>`5Abilità: </td><td>`@" . $skills[$row['special_help']] . "(`^".$row['special_use_help']."`@)</td></tr>", true);
        if ($row['heal_help']!=0) output ("<tr><td>`5Cura: </td><td>`@" . $row['heal_help'] . " hp</td></tr>", true);
        if ($row['quest_help']!=0) output ("<tr><td>`5Quest: </td><td>`@" . $row['quest_help'] . "punti quest</td></tr>", true);
        if ($row['exp_help']!=0) output ("<tr><td>`5Esperienza: </td><td>`@+" . $row['exp_help'] . "%</td></tr>", true);
        output ("<tr><td>`2Cariche totali: </td><td>`@".$row['potere']."</td></tr>", true);
        output ("<tr><td>`2Cariche residue:</td><td>`@".$row['potere_uso']."</td></tr>", true);
        output ("<tr><td>`2Potenziamenti residui:</td><td>`@".$row['potenziamenti']."</td></tr>", true);
        output ("<tr><td>`)Usura fisica:</td><td>`(".(intval(100-(100*$row['usura']/$row['usuramax'])))."</td></tr>", true);
        output ("<tr><td>`)Usura magica:</td><td>`8".(intval(100-(100*$row['usuramagica']/$row['usuramagicamax'])))."</td></tr>", true);
        output ("<tr><td>  </td><td>  </td></tr>", true);
        output ("<tr><td>`7Valore in Gemme: </td><td>`&".$row['mercatino_gemme']."</td></tr>", true);
        output ("<tr><td>`7Valore in Oro: </td><td>`&".$row['mercatino_oro']."</td></tr>", true);
        output ("<tr><td>  </td><td>  </td></tr>", true);
        output ("</table>", true);
        output("<form action='fabbro.php?op=mercatino&og=comprao&id=$_GET[id]' method='POST'>", true);
        addnav("", "fabbro.php?op=mercatino&og=comprao&id=$_GET[id]");
        output("<input type='submit' class='button' value='Compra'>", true);
        output("</form>", true);
      }
   } // end "esamina"
   if ($_GET[og] == "valuta") {
      $oggetto = $session['user']['oggetto'];
      $zaino = $session['user']['zaino'];
      if ($session['user']['oggetto'] == "0") {
          $ogg = "Nulla";
      } else {
          $sqlo = "SELECT * FROM oggetti WHERE id_oggetti = $oggetto";
          $resultoo = db_query($sqlo) or die(db_error(LINK));
          $rowo = db_fetch_assoc($resultoo);
          $ogg = $rowo['nome'];
      }
      if ($session['user']['zaino'] == "0") {
          $zai = "Nulla";
      } else {
          $sqlo = "SELECT * FROM oggetti WHERE id_oggetti = $zaino";
          $resultoz = db_query($sqlo) or die(db_error(LINK));
          $rowz = db_fetch_assoc($resultoz);
          $zai = $rowz['nome'];
      }
      $valvendb = $rowz['valore'];
      if ($rowz[usuramax] > 0) {
          $valvendf = $rowz['valore']*$rowz['usura']/$rowz['usuramax'];
      } else {
          $valvendf = $rowz['valore'];
      }
      if ($rowz[usuramagicamax] > 0) {
          $valvendm = $rowz['valore']*$rowz['usuramagica']/$rowz['usuramagicamax'];
      } else {
          $valvendm = $rowz['valore'];
      }
      $valvendz = intval((2 * $valvendb + 2 * $valvendf + $valvendm)/10);
      if ($rowz['usuramagica']==0 AND $rowz[usuramagicamax] > 0) $valvendz=intval($valvendz/2);
      output ("`@Puoi mettere in vendita solo l'oggetto che hai nello zaino!`n`n");
      if ($rowz[dove]!=30) output("<form action='fabbro.php?op=mercatino&og=vendiz&id=$rowz[id_oggetti]&value=$valvendz' method='POST'>",true);
      if ($rowz[dove]!=30) addnav("", "fabbro.php?op=mercatino&og=vendiz&id=$rowz[id_oggetti]&value=$valvendz");
      output ("<table>", true);
      output ("<tr><td>`6Nello zaino hai : </td><td>`b`^" . $zai . "`b</td><td>Oro: <input name='oro' size='3'></td><td>Gemme: <input name='gemme' size='3'></td>",true);
      if ($rowz[dove]!=30) {
        output ("<td><input type='submit' class='button' value='Proponi'></td>",true);
      } else {
        output ("<td>Gli artefatti non possono essere venduti</td>",true);
      }
      output ("</tr>", true);
      output ("</table>", true);
      if ($rowz[dove]!=30) output("</form>",true);
      output ("`nBrax valuta questo oggetto `&$valvendz gemme`@.`n");
   } // end "valuta"
   if ($_GET[og] == "vendiz") {
      $valore_ufficiale=$_GET['value'];
      $valore_proposto=intval($_POST['oro']/2500)+$_POST['gemme'];
      $valore_max=intval($valore_ufficiale*1.50);
      $valore_min=intval($valore_ufficiale*0.80);
      $gemme=$_POST['gemme'];
      $oro=$_POST['oro'];
      if(!$gemme)$gemme=0;
      if(!$oro)$oro=0;
      // Maximus Inizio blocco su vendita se livello è inferiore al 5° o superiore al 13°
      if ($session['user']['level'] < 6 || $session['user']['level'] > 13) {
         output ("`\$In questo momento non puoi vendere oggetti magici.`0`n`n");
      }  else {
      // Maximus Fine
            if ($valore_proposto > $valore_max OR $valore_proposto < $valore_min) {
               output ("\"`&Vuoi fare il furbo? Il valore che hai proposto è troppo alto (o troppo basso), non lo metterò mai in vendita, ho una reputazione da difendere, io...`0\" Ti dice torvo Oberon`0`n`n");
            }else{
              if (!$_GET['id'] OR $session['user']['gold'] < 500) {
                  output ("\"`&Vuoi fare il furbo? Ti servono 500 monete per poter esporre oggetti nella mia bottega`0\" Ti dice torvo Oberon`0`n`n");
              } else {
                  $sqlz = "SELECT * FROM oggetti WHERE id_oggetti = $_GET[id]";
                  $resultz = db_query($sqlz) or die(db_error(LINK));
                  $rowz = db_fetch_assoc($resultz);
                  output ("<BIG>`6Hai messo in vendita l'oggetto che avevi nello zaino per `b`^".$gemme."`b`6 gemme e `b`\$".$oro."`b`6 oro.</BIG>`n`n",true);
                  debuglog("ha messo in vendita oggetto ID {$_GET[id]} per {$gemme} gemme e {$oro} oro");
                  $sql = "UPDATE oggetti SET dove='7',acctid='".$session['user']['acctid']."',mercatino_gemme='$gemme',mercatino_oro='$oro' WHERE id_oggetti=$_GET[id]";
                  db_query($sql) or die(db_error(LINK));
                  $session['user']['zaino'] = 0;
                  $session['user']['gold'] -= 500;
              }
          }
      } // Maximus Fine
   } // end "vendiz"
   if ($_GET[og] == "scambia") {
      if ($session['user']['dragonkills'] < 10) {
          $livello = 1 + (3 * $session['user']['reincarna']);
      } else if ($session['user']['dragonkills'] >= 10 AND $session['user']['dragonkills'] < 19) {
          $livello = 2 + (3 * $session['user']['reincarna']);
      } else if ($session['user']['dragonkills'] >= 19) {
          $livello = 3 + (3 * $session['user']['reincarna']);
      }
      $ogg = $session['user']['oggetto'];
      $zai = $session['user']['zaino'];
      $sqlo = "SELECT * FROM oggetti WHERE id_oggetti = $ogg";
      $resulto = db_query($sqlo) or die(db_error(LINK));
      $rowo = db_fetch_assoc($resulto);
      $sqlz = "SELECT * FROM oggetti WHERE id_oggetti = $zai";
      $resultz = db_query($sqlz) or die(db_error(LINK));
      $rowz = db_fetch_assoc($resultz);
      if ($livello>=$rowz['livello']){
          if (!$session['user']['zaino'] AND !$session['user']['oggetto']) {
              output ("Non hai nulla da scambiare.`n`n");
          } else if (!$session['user']['zaino']) {
              output ("Non hai nulla nello zaino.`n`n");
          } else {
              output ("Hai invertito l'oggetto equipaggiato con quello nello zaino.`n`n");
              $session['user']['attack'] -= $rowo['attack_help'];
              $session['user']['defence'] -= $rowo['defence_help'];
              $session['user']['bonusattack'] -= $rowo['attack_help'];
              $session['user']['bonusdefence'] -= $rowo['defence_help'];
              $session['user']['maxhitpoints'] -= $rowo['hp_help'];
              $session['user']['hitpoints'] -= $rowo['hp_help'];
              //$session['user']['turns'] = $session['user']['turns'] - $rowo[turns_help];
              if ($rowo[usuramagica]!=0 AND $rowo[usuramagicamax]!=0) $session['user']['bonusfight'] -= $rowo['turns_help'];

              $deposito = $session['user']['oggetto'];
              $session['user']['oggetto'] = $session['user']['zaino'];
              $session['user']['zaino'] = $deposito;

              $session['user']['attack'] += $rowz['attack_help'];
              $session['user']['defence'] += $rowz['defence_help'];
              $session['user']['bonusattack'] += $rowz['attack_help'];
              $session['user']['bonusdefence'] += $rowz['defence_help'];
              $session['user']['maxhitpoints'] += $rowz['hp_help'];
              $session['user']['hitpoints'] += $rowz['hp_help'];
              //$session['user']['turns'] = $session['user']['turns'] + $rowz[turns_help];
              if ($rowz[usuramagica]!=0 AND $rowz[usuramagicamax]!=0) $session['user']['bonusfight'] += $rowz['turns_help'];

              if ($session['user']['hitpoints'] <1) $session['user']['hitpoints'] = 1;
          }
      }else {
          output("Purtroppo il livello dell'oggetto nello zaino è troppo alto per te, l'unica cosa che puoi fare è venderlo.");
      }
   } // end "scambia"
   if ($_GET[og] == "comprao") {
      $sqlo = "SELECT * FROM oggetti WHERE id_oggetti = $_GET[id]";
      $resulto = db_query($sqlo) or die(db_error(LINK));
      $row = db_fetch_assoc($resulto);
      $accountVenditore = $row[acctid];

      // Maximus Inizio blocco su acquisto se livello è inferiore al 5° o superiore al 14°
      if ($session['user']['level'] < 6 || $session['user']['level'] > 13) {
         output ("`\$In questo momento non puoi comprare oggetti magici.`0`n`n");
      } else {
      // Maximus Fine
         $stessoPlayer = false;
         if ($session['user']['acctid']==$accountVenditore) {
            $stessoPlayer = true;
         }else{
            $sqlVenditore = "SELECT lastip, emailaddress FROM accounts WHERE acctid = $accountVenditore";
            $resultVenditore = db_query($sqlVenditore) or die(db_error(LINK));
            $rowVenditore = db_fetch_assoc($resultVenditore);
            if ($session['user']['superuser']<3
            &&  $session['user']['lastip'] == $rowVenditore['lastip']
            || ($session['user'] ['emailaddress'] == $rowVenditore['emailaddress']
            && $rowVenditore[emailaddress])) {
               $stessoPlayer = true;
            }
         }

         if ($stessoPlayer) {
            output ("\"`&Vuoi fare il furbo? Non puoi trasferire oggetti tra i tuoi personaggi!`0\" Ti dice torvo Oberon`0`n`n");
         } else {
           if ($row['mercatino_gemme']>$session['user']['gems'] OR $row['mercatino_oro']>$session['user']['gold']) {
              output ("\"`&Vuoi fare il furbo? Non sei abbastanza ricco per permetterti questo oggetto`0\" Ti dice torvo Oberon`0`n`n");
           }else{
               $sqlogg = "SELECT * FROM accounts WHERE oggetto = $_GET[id]";
               $resultogg = db_query($sqlogg) or die(db_error(LINK));
               if ($session['user']['dragonkills'] < 10) {
                   $maxbuylvl = 1 + (3 * $session['user']['reincarna']);
               } else if ($session['user']['dragonkills'] >= 10 AND $session['user']['dragonkills'] < 19) {
                   $maxbuylvl = 2 + (3 * $session['user']['reincarna']);
               } else if ($session['user']['dragonkills'] >= 19) {
                   $maxbuylvl = 3 + (3 * $session['user']['reincarna']);
               }
               if ($session['user']['superuser'] > 1) {
                   $maxbuylvl = 100;
               }
               if ($row['livello'] > $maxbuylvl) {
                   output ("\"`&Purtroppo per te non puoi comprare questo oggetto, è troppo potente`0\" Ti dice sorridendo Oberon`0`n`n");
               } else {
                   if (db_num_rows($resultogg) == 0) {
                       $sqlzai = "SELECT * FROM accounts WHERE zaino = $_GET[id]";
                       $resultzai = db_query($sqlzai) or die(db_error(LINK));
                       if (db_num_rows($resultzai) == 0) {
                           if ($session['user']['oggetto'] == "0") {
                               output ("<BIG>`2Hai comprato questo oggetto :`b`@" . $row['nome'] . "`b`2 e lo hai indossato.</BIG>`n`n",true);
                               debuglog("ha comprato {$row['nome']} da Oberon per {$row['mercatino_gemme']} gemme e {$row['mercatino_oro']} oro indossandolo");
                               // invio mail al proprietario precedente
                               $mailId = $row[acctid];
                               $mailObj = "`^Hai venduto un Oggetto!";
                               $mailMessage = "{$session['user']['name']} `0ha acquistato {$row['nome']} che avevi messo in vendita! Oberon ti aspetta nella sua bottega per consegnarti il ricavato.";
                               systemmail($mailId,$mailObj,$mailMessage);

                               $session['user']['oggetto'] = $row[id_oggetti];
                               // controllo deve ancora riscuotere
                               $sqlMerc = "SELECT SUM(gold) AS oro,SUM(gems) AS gemme FROM mercatino WHERE acctid='".$row['acctid']."'";
                               $resultMerc = db_query($sqlMerc) or die(db_error(LINK));
                               $rowMerc = db_fetch_assoc($resultMerc);
                               if (db_num_rows($resultMerc) == 0 || ($rowMerc[gemme]==0 && $rowMerc[oro]==0)) {
                                  $sqli = "INSERT INTO mercatino (acctid,gems,gold) VALUES ('".$row['acctid']."','".$row['mercatino_gemme']."','".$row['mercatino_oro']."')";
                               } else {
                                  $gemme = $rowMerc[gemme]+$row['mercatino_gemme'];
                                  $oro = $rowMerc[oro]+$row['mercatino_oro'];
                                  $sqli = "UPDATE mercatino set gems=$gemme, gold=$oro where acctid = '".$row['acctid']."'";
                               }
                               db_query($sqli);
                               $sql = "UPDATE oggetti SET dove=0,acctid=0,mercatino_gemme=0,mercatino_oro=0 WHERE id_oggetti=$_GET[id]";
                               db_query($sql) or die(db_error(LINK));
                               $session['user']['attack'] += $row['attack_help'];
                               $session['user']['defence'] += $row['defence_help'];
                               $session['user']['bonusattack'] += $row['attack_help'];
                               $session['user']['bonusdefence'] += $row['defence_help'];
                               $session['user']['maxhitpoints'] += $row['hp_help'];
                               $session['user']['hitpoints'] += $row['hp_help'];
                               if ($row[usuramagica]!=0 AND $row[usuramagicamax]!=0) $session['user']['playerfights'] -= $rowo['pvp_help'];
                               if ($row[usuramagica]!=0 AND $row[usuramagicamax]!=0) $session['user']['turns'] += $row['turns_help'];
                               if ($row[usuramagica]!=0 AND $row[usuramagicamax]!=0) $session['user']['bonusfight'] += $row['turns_help'];
                               $session['user']['gems'] -= $row['mercatino_gemme'];
                               $session['user']['gold'] -= $row['mercatino_oro'];
                           } elseif ($session['user']['zaino'] == "0") {
                               output ("<BIG>`3Hai comprato questo oggetto :`b`#" . $row['nome'] . "`b`3 e lo hai messo nello zaino.</BIG>`n`n",true);
                               debuglog("ha comprato {$row['nome']} da Oberon per {$row['mercatino_gemme']} gemme e {$row['mercatino_oro']} oro e lo ha messo nello zaino");
                               $session['user']['zaino'] = $row[id_oggetti];
                               // invio mail al proprietario precedente
                               $mailId = $row[acctid];
                               $mailObj = "`^Hai venduto un Oggetto!";
                               $mailMessage = "{$session['user']['name']} `0ha acquistato {$row['nome']} che avevi messo in vendita! Oberon ti aspetta nella sua bottega per consegnarti il ricavato.";
                               systemmail($mailId,$mailObj,$mailMessage);

                               // controllo deve ancora riscuotere
                               $sqlMerc = "SELECT SUM(gold) AS oro,SUM(gems) AS gemme FROM mercatino WHERE acctid='".$row['acctid']."'";
                               $resultMerc = db_query($sqlMerc) or die(db_error(LINK));
                               $rowMerc = db_fetch_assoc($resultMerc);
                               if (db_num_rows($resultMerc) == 0 || ($rowMerc[gemme]==0 && $rowMerc[oro]==0)) {
                                  $sqli = "INSERT INTO mercatino (acctid,gems,gold) VALUES ('".$row['acctid']."','".$row['mercatino_gemme']."','".$row['mercatino_oro']."')";
                               } else {
                                  $gemme = $rowMerc['gemme']+$row['mercatino_gemme'];
                                  $oro = $rowMerc['oro']+$row['mercatino_oro'];
                                  $sqli = "UPDATE mercatino set gems=$gemme, gold=$oro where acctid = '".$row['acctid']."'";
                               }
                               db_query($sqli);
                               $sql = "UPDATE oggetti SET dove=0,acctid=0,mercatino_gemme=0,mercatino_oro=0 WHERE id_oggetti=$_GET[id]";
                               db_query($sql) or die(db_error(LINK));
                               $session['user']['gems'] -= $row['mercatino_gemme'];
                               $session['user']['gold'] -= $row['mercatino_oro'];

                           } else {
                               output ("`7Oberon ti guarda torvo e dice: \"`&Dove pensi di mettere quest'oggetto? Ne stai già utilizzando
                               uno e anche lo zaino è occupato!! Prima fai un po' di spazio e poi
                               potremmo discutere nuovamente dell'acquisto che intendevi effettuare. Sempre che tu possa spendere quella cifra...`7\"`0`n`n");
                           }
                       } else {
                           output ("\"`&Questo oggetto non è più disponibile, qualche altro cliente è stato più veloce di te e lo ha acquistato.`0\" Ti dice sorridendo Oberon`0`n`n");
                       }
                   } else {
                       output ("\"`&Questo oggetto non è più disponibile, qualche altro cliente è stato più veloce di te e lo ha acquistato.`0\" Ti dice sorridendo Oberon`0`n`n");
                   }
               }
            }
         }
     } // Maximus Fine
   } // end "comprao"
   if ($_GET[og] == "incassa") {
       $sql = "SELECT SUM(gold) AS oro,SUM(gems) AS gemme FROM mercatino WHERE acctid='".$session['user']['acctid']."'";
       $result = db_query($sql) or die(db_error(LINK));
       $row = db_fetch_assoc($result);
       if ((db_num_rows($result) == 0) || ($row['gemme']==0 && $row['oro']==0)) {
           output ("\"`&Nessuno ha ancora acquistato i tuoi oggetti`0\" Ti dice sorridendo Oberon`0`n`n");
       }else{
           output ("Hai venduto oggetti per un totale di `^$row[oro] Monete d'Oro e `&$row[gemme] Gemme `7che Oberon ti consegna prontamente.");
           debuglog("ha ritirato da Oberon {$row['oro']} oro e {$row['gemme']} gemme da vendite nel mercatino");
           $session['user']['gems'] += $row['gemme'];
           $session['user']['gold'] += $row['oro'];
           $sqle = "DELETE FROM mercatino WHERE acctid='{$session['user']['acctid']}'";
           db_query($sqle);
       }
   }  // end "incassa"
   if ($_GET[og] == "ritira") {
       if ($_GET['id'] == null) {
           $sql = "SELECT * FROM oggetti WHERE dove = 7 AND acctid='".$session['user']['acctid']."' ORDER BY livello DESC,nome DESC";
           output("`c`b`&I tuoi oggetti attualmente esposti`b`c`n");
           output("<table cellspacing=0 cellpadding=2 align='center'><tr>
                 <td>&nbsp;</td><td>`b`@Nome`b</td>
                 <td>`b`#Livello`b</td>
                 <td>`b`&Costo`b</td>
                 <td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td>
                 <td>`b`@HP`b</td><td>&nbsp;</td>
                 <td>`b`\$Att.`b</td><td>&nbsp;</td>
                 <td>`b`^Dif.`b</td><td>&nbsp;</td>
                 <td>`b`%Potenz.`b</td><td>&nbsp;</td>
                 <td>`b`^Oro/g.`b</td><td>&nbsp;</td>
                 <td>`b`&Gem/g.`b</td>",true);
           if ($session['user']['superuser'] > 2) {
           output("<td>`b`#ID`b</td>",true);
       }
       output("</tr>", true);
       $result = db_query($sql) or die(db_error(LINK));
       if (db_num_rows($result) == 0) {
             output("<tr><td colspan=20 align='center'>`&Non hai nessun oggetto in esposizione!`0</td></tr>", true);
       }
       $countrow = db_num_rows($result);
       for ($i=0; $i<$countrow; $i++){
       //for ($i = 0;$i < db_num_rows($result);$i++) {
             $row = db_fetch_assoc($result);
             output("<tr class='" . ($i % 2?"trlight":"trdark") . "'><td align=right>" . ($i + 1) . ".</td>
                     <td><A href=fabbro.php?op=mercatino&og=ritira&id=$row[id_oggetti]>`@".$row['nome']."</a></td>
                     <td>`b`#".$row['livello']."`b</td>
                     <td>`b`&gem:".$row['mercatino_gemme']." / oro:".$row['mercatino_oro']."`b</td>
                     <td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td>
                     <td align='center'>`b`@".$row['hp_help']."`b</td><td>&nbsp;</td>
                     <td align='center'>`b`\$".$row['attack_help']."`b</td><td>&nbsp;</td>
                     <td align='center'>`b`^".$row['defence_help']."`b</td><td>&nbsp;</td>
                     <td align='center'>`b`%".$row['potenziamenti']."`b</td><td>&nbsp;</td>
                     <td align='center'>`b`^".$row['gold_help']."`b</td><td>&nbsp;</td>
                     <td align='center'>`b`&".$row['gems_help']."`b</td>",true);
             if ($session['user']['superuser'] > 2) {
                 output("<td>`b`#".$row['id_oggetti']."`b</td>",true);
             }
             output("</tr>", true);
             addnav("", "fabbro.php?op=mercatino&og=ritira&id=$row[id_oggetti]");
       }
       output("</table>", true);
   } else {
       $sqlo = "SELECT * FROM oggetti WHERE id_oggetti = $_GET[id]";
       $resulto = db_query($sqlo) or die(db_error(LINK));
       $row = db_fetch_assoc($resulto);
       $sqlogg = "SELECT * FROM accounts WHERE oggetto = $_GET[id]";
       $resultogg = db_query($sqlogg) or die(db_error(LINK));
       if ($session['user']['dragonkills'] < 10) {
                $maxbuylvl = 1 + (3 * $session['user']['reincarna']);
       } else if ($session['user']['dragonkills'] >= 10 AND $session['user']['dragonkills'] < 19) {
                $maxbuylvl = 2 + (3 * $session['user']['reincarna']);
       } else if ($session['user']['dragonkills'] >= 19) {
                $maxbuylvl = 3 + (3 * $session['user']['reincarna']);
       }
       if ($session['user']['superuser'] > 1) {
                $maxbuylvl = 100;
       }
       if ($row['livello'] > $maxbuylvl AND $session['user']['zaino'] != "0") {
                output ("\"`&Purtroppo per te non puoi riprendere questo oggetto, è troppo potente`0\" Ti dice sorridendo Oberon`0`n`n");
       }
       if (db_num_rows($resultogg) == 0) {
           $sqlzai = "SELECT * FROM accounts WHERE zaino = $_GET[id]";
           $resultzai = db_query($sqlzai) or die(db_error(LINK));
           if (db_num_rows($resultzai) == 0) {
               if ($session['user']['oggetto'] == "0" AND $row['livello'] <= $maxbuylvl) {
                   output ("<BIG>`2Hai ripreso questo oggetto :`b`@" . $row['nome'] . "`b`2 e lo hai indossato.</BIG>`n`n",true);
                   debuglog("ha ripreso {$row['nome']} da Oberon indossandolo");
                   $session['user']['oggetto'] = $row[id_oggetti];
                   $sql = "UPDATE oggetti SET dove=0,acctid=0,mercatino_gemme=0,mercatino_oro=0 WHERE id_oggetti=$_GET[id]";
                   db_query($sql) or die(db_error(LINK));
                   $session['user']['attack'] += $row['attack_help'];
                   $session['user']['defence'] += $row['defence_help'];
                   $session['user']['bonusattack'] += $row['attack_help'];
                   $session['user']['bonusdefence'] += $row['defence_help'];
                   $session['user']['maxhitpoints'] += $row['hp_help'];
                   $session['user']['hitpoints'] += $row['hp_help'];
                   if ($row[usuramagica]!=0 AND $row[usuramagicamax]!=0) $session['user']['playerfights'] -= $rowo['pvp_help'];
                   if ($row[usuramagica]!=0 AND $row[usuramagicamax]!=0) $session['user']['turns'] += $row['turns_help'];
                   if ($row[usuramagica]!=0 AND $row[usuramagicamax]!=0) $session['user']['bonusfight'] += $row['turns_help'];
               } elseif ($session['user']['zaino'] == "0") {
                   output ("<BIG>`3Hai ripreso questo oggetto :`b`#" . $row['nome'] . "`b`3 e lo hai messo nello zaino.</BIG>`n`n",true);
                   debuglog("ha ripreso {$row['nome']} da Oberon e lo ha messo nello zaino");
                   $session['user']['zaino'] = $row[id_oggetti];
                   $sql = "UPDATE oggetti SET dove=0,acctid=0,mercatino_gemme=0,mercatino_oro=0 WHERE id_oggetti=$_GET[id]";
                   db_query($sql) or die(db_error(LINK));
               } elseif ($row['livello'] <= $maxbuylvl) {
                   output ("`7Oberon ti guarda torvo e dice: \"`&Dove pensi di mettere quest'oggetto? Ne stai già utilizzando
                   uno e anche lo zaino è occupato!! Prima fai un po' di spazio e poi
                   potremmo discutere nuovamente dell'oggetto che intendevi riprendere...`7\"`0`n`n");
               }
           } else {
               output ("\"`&Questo oggetto non è più disponibile, qualche altro cliente è stato più veloce di te e lo ha acquistato.`0\" Ti dice sorridendo Oberon`0`n`n");
           }
       } else {
           output ("\"`&Questo oggetto non è più disponibile, qualche altro cliente è stato più veloce di te e lo ha acquistato.`0\" Ti dice sorridendo Oberon`0`n`n");
       }
   }//Sook fine ripresa oggetti
}
}
*/
//Luke fine
page_footer();
?>