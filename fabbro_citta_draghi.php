<?php
require_once("common.php");
require_once("common2.php");
$dk = $session['user']['dragonkills'];
$carriera = $session['user']['carriera'];
$scaglie_ferro = getsetting("scagliemetallo_cd",0);
$scaglie_rame = getsetting("scaglierame_cd",0);
$carbone_cd = getsetting("carbone_cd",0);
$argento = getsetting("argento_cd",0);
$oro = getsetting("oro_cd",0);
$ada = getsetting("ada",0);
$mit = getsetting("mit",0);
$sdra = getsetting("sdra",0);
$caso = e_rand(1,4);
$caso2 = e_rand(1,4);
$caso3 = e_rand(1,4);
$caso_gold = ($session['user']['level']*8* $caso3);
$caso_gold2 = ($session['user']['level']*15* $caso3);
$caso_gold3 = ($session['user']['level']*30* $caso3);
$cento = e_rand(1,100);
$idplayer = $session['user']['acctid'];


//  prezzo acquisto variabile
$val_scaglie_metallo = 1200-(10*$scaglie_ferro);
$val_scaglie_rame = 2600-(10*$scaglie_rame);
$val_carbone_cd = 800-(10*$carbone_cd);
$val_argento = 5200-(10*$argento);
$val_oro = 10400-(10*$oro);
$val_ada = 15600-(10*$ada);
$val_mit = 20800-(10*$mit);
$val_sdra = 41600-(10*$sdra);
if ($val_scaglie_metallo < 10) $val_scaglie_metallo=10;
if ($val_carbone_cd < 10) $val_carbone_cd=10;
if ($val_scaglie_rame < 10) $val_scaglie_rame=10;
if ($val_argento < 100) $val_argento=100;
if ($val_oro < 500) $val_oro=500;
if ($val_ada < 5000) $val_ada=5000;
if ($val_mit < 7500) $val_mit=7500;
if ($val_sdra < 20500) $val_sdra=20500;


page_header("Gordon il fabbro");
addnav("Azioni");

if ($session['user']['level'] > 5)  addnav("","fabbro_citta_draghi.php?op=vendica");
if ($session['user']['level'] < 15) addnav("","fabbro_citta_draghi.php?op=compraca");
if ($session['user']['level'] > 5)  addnav("","fabbro_citta_draghi.php?op=vendisr");
if ($session['user']['level'] < 15) addnav("","fabbro_citta_draghi.php?op=comprasr");
if ($session['user']['level'] > 5)  addnav("","fabbro_citta_draghi.php?op=vendism");
if ($session['user']['level'] < 15)  addnav("","fabbro_citta_draghi.php?op=comprasm");
if ($session['user']['level'] < 15) addnav("","fabbro_citta_draghi.php?op=compraar");
if ($session['user']['level'] > 5)  addnav("","fabbro_citta_draghi.php?op=vendiar");
if ($session['user']['level'] < 15) addnav("","fabbro_citta_draghi.php?op=compraor");
if ($session['user']['level'] > 5) addnav("","fabbro_citta_draghi.php?op=vendior");
if ($session['user']['level'] < 15) addnav("","fabbro_citta_draghi.php?op=compraad");
if ($session['user']['level'] > 5) addnav("","fabbro_citta_draghi.php?op=vendiad");
if ($session['user']['level'] < 15) addnav("","fabbro_citta_draghi.php?op=comprami");
if ($session['user']['level'] > 5) addnav("","fabbro_citta_draghi.php?op=vendimi");
if ($session['user']['level'] < 15) addnav("","fabbro_citta_draghi.php?op=comprasd");
if ($session['user']['level'] > 5) addnav("","fabbro_citta_draghi.php?op=vendisd");
addnav("Exit");
if($_GET['op']!="garzone" OR $_GET['op']){
    addnav("Torna all'entrata", "fabbro_citta_draghi.php");
}

addnav("Città dei Draghi","terre_draghi.php?op=citta");

if ($_GET['op']==""){
    output("Al momento Gordon ha questi materiali. Ricorda che il valore indicato è quello a cui Gordon vende i materiali, li compra a molto meno.`n`n");
    output("<table cellspacing=0 cellpadding=2 align='center'><tr><td>`bMateriale`b</td><td>`bQuantità`b</td><td>`bValore oro`b</td><td>Ops</td></tr>", true);
    output("<tr class='trlight'><td>Scaglie di ferro</td><td>$scaglie_ferro</td><td>$val_scaglie_metallo</td>",true);
    if ($session['user']['level'] < 15) {
        output("<td><A href=fabbro_citta_draghi.php?op=comprasm>Compra </a>",true);
    } else {
        output("<td>",true);
    }
    if ($session['user']['level'] > 5) output("-<A href=fabbro_citta_draghi.php?op=vendism> Vendi</a>",true);
    output("</td></tr>", true);
    output("<tr class='trdark'><td>Scaglie di rame</td><td>$scaglie_rame</td><td>$val_scaglie_rame</td>",true);
    if ($session['user']['level'] < 15) {
        output("<td><A href=fabbro_citta_draghi.php?op=comprasr>Compra </a>",true);
    } else {
        output("<td>",true);
    }
    if ($session['user']['level'] > 5) output("-<A href=fabbro_citta_draghi.php?op=vendisr> Vendi</a>",true);
    output("</td></tr>", true);
    output("<tr class='trlight'><td>carbone</td><td>$carbone_cd</td><td>$val_carbone_cd</td>",true);
    if ($session['user']['level'] < 15) {
        output("<td><A href=fabbro_citta_draghi.php?op=compraca>Compra </a>",true);
    } else {
        output("<td>",true);
    }
    if ($session['user']['level'] > 5) output("-<A href=fabbro_citta_draghi.php?op=vendica> Vendi</a>",true);
    output("</td></tr>", true);

    output("<tr class='trlight'><td>Argento</td><td>$argento</td><td>$val_argento</td>",true);
    if ($session['user']['level'] < 15) {
        output("<td><A href=fabbro_citta_draghi.php?op=compraar>Compra </a>",true);
    } else {
        output("<td>",true);
    }
    if ($session['user']['level'] > 5) output("-<A href=fabbro_citta_draghi.php?op=vendiar> Vendi</a>",true);
    output("</td></tr>", true);

    output("<tr class='trlight'><td>Oro</td><td>$oro</td><td>$val_oro</td>",true);
    if ($session['user']['level'] < 15) {
        output("<td><A href=fabbro_citta_draghi.php?op=compraor>Compra </a>",true);
    } else {
        output("<td>",true);
    }
    if ($session['user']['level'] > 5) output("-<A href=fabbro_citta_draghi.php?op=vendior> Vendi</a>",true);
    output("</td></tr>", true);

    output("<tr class='trlight'><td>Adamantite</td><td>$ada</td><td>$val_ada</td>",true);
    if ($session['user']['level'] < 15) {
        output("<td><A href=fabbro_citta_draghi.php?op=compraad>Compra </a>",true);
    } else {
        output("<td>",true);
    }
    if ($session['user']['level'] > 5) output("-<A href=fabbro_citta_draghi.php?op=vendiad> Vendi</a>",true);
    output("</td></tr>", true);

    output("<tr class='trlight'><td>Mithril</td><td>$mit</td><td>$val_mit</td>",true);
    if ($session['user']['level'] < 15) {
        output("<td><A href=fabbro_citta_draghi.php?op=comprami>Compra </a>",true);
    } else {
        output("<td>",true);
    }
    if ($session['user']['level'] > 5) output("-<A href=fabbro_citta_draghi.php?op=vendimi> Vendi</a>",true);
    output("</td></tr>", true);

    output("<tr class='trlight'><td>Scaglia drago</td><td>$sdra</td><td>$val_sdra</td>",true);
    if ($session['user']['level'] < 15) {
        output("<td><A href=fabbro_citta_draghi.php?op=comprasd>Compra </a>",true);
    } else {
        output("<td>",true);
    }
    if ($session['user']['level'] > 5) output("-<A href=fabbro_citta_draghi.php?op=vendisd> Vendi</a>",true);
    output("</td></tr>", true);

    output("</table>", true);
    $sql = "SELECT materiali.id AS idmateriali, materiali.nome AS nome, materiali.valoremo AS valoremo, materiali.valorege AS valorege,
                 materiali.descrizione AS descrizione FROM zaino, materiali WHERE zaino.idplayer = $idplayer AND zaino.idoggetto = materiali.id";
    output ("Il tuo zaino`n`n");
    output("<table cellspacing=0 cellpadding=2 align='center'><tr><td>&nbsp;</td><td>`bNome`b</td></tr>", true);
    $result = db_query($sql) or die(db_error(LINK));
    if (db_num_rows($result) == 0) {
        output("<tr><td colspan=4 align='center'>`&Non hai oggetti nello zaino`0</td></tr>", true);
    }
    $countrow = db_num_rows($result);
    for ($i=0; $i<$countrow; $i++){
    //for ($i = 0;$i < db_num_rows($result);$i++) {
        $row = db_fetch_assoc($result);
        output("<tr class='" . ($i % 2?"trlight":"trdark") . "'><td>" . ($i + 1) . ".</td><td>$row[nome]</td></tr>", true);

    }
    output("</table>", true);
}elseif(substr($_GET['op'],0,6)=="compra" && zainoPieno($idplayer)) {
     output("Purtroppo hai lo zaino pieno");
}
elseif($_GET['op']=="compraca"){
    if ($session['user']['gold']>=$val_carbone_cd){
        if ($carbone_cd >= 1) {

            output("Gordon afferra i tuoi ".$val_carbone_cd." pezzi d'oro e ti da 1 pezzo di carbone in cambio.`n`n");
            $session['user']['gold']-=$val_carbone_cd;
            $sqli="INSERT INTO zaino (idoggetto, idplayer) VALUES (3, '{$session['user']['acctid']}')";
            db_query($sqli) or die(db_error(LINK));
            debuglog("compra 1 pezzo di carbone da Gordon");


            if (getsetting("carbone_cd",0) - 1 < 1) {
                savesetting("carbone_cd","0");
            } else {
                savesetting("carbone_cd",getsetting("carbone_cd",0)-1);
            }
        } else {
            output("Purtroppo Gordon ha già venduto tutto il carbone che aveva, sei arrivato troppo tardi.`n");
            output("Il carbone è abbastanza comune, non dovrebbe tardare ad arrivare qualche avventuriero con del carbone.`n`n");
        }
    }else{
        output("Gordon accarezza la sua pesante mazza ferrata, ti guarda truce, e agitando l'indice sul tuo muso dice :\"Non fare il furbo con me !\".`n`n");
    }
}elseif($_GET['op']=="vendica"){
    $sql = "SELECT * FROM zaino WHERE idoggetto = 3 AND idplayer='{$session['user']['acctid']}'";
    $result = db_query($sql) or die(db_error(LINK));

    if (db_num_rows($result)<1){
        output(
        "Gordon accarezza la sua pesante mazza ferrata, ti guarda truce, e agitando l'indice sul tuo muso dice :\"Non fare il furbo con me !\".`n`n");
    }else{
        output(
        "Gordon accetta il tuo carbone e ti posa in mano ".($val_carbone_cd/2)." pezzi d'oro in cambio.`n`n");
        $session['user']['gold']+=($val_carbone_cd/2);
        $row = db_fetch_assoc($result);
        $sqld = "DELETE FROM zaino WHERE id = '{$row['id']}'";
        db_query($sqld);
        debuglog("vende 1 pezzo di carbone a Gordon");
        savesetting("carbone_cd",getsetting("carbone_cd",0)+1);
        addnav("Torna da Gordon","fabbro_citta_draghi.php");
    }
}elseif($_GET['op']=="comprasr"){
    if ($session['user']['gold']>=$val_scaglie_rame){
        if ($scaglie_rame >= 1) {

            output(
            "Gordon afferra i tuoi ".$val_scaglie_rame." pezzi d'oro e ti da 1 scaglia di rame in cambio.`n`n");
            $session['user']['gold']-=$val_scaglie_rame;
            $sqli="INSERT INTO zaino (idoggetto, idplayer) VALUES (2, '{$session['user']['acctid']}')";
            db_query($sqli) or die(db_error(LINK));
            debuglog("compra 1 scaglia di rame da Gordon");
            if (getsetting("scaglierame_cd",0) - 1 < 1) {
                savesetting("scaglierame_cd","0");
            } else {
                savesetting("scaglierame_cd",getsetting("scaglierame_cd",0)-1);
            }
        } else {
            output("Purtroppo Gordon ha già venduto tutto il rame che aveva, sei arrivato troppo tardi.`n");
            output("Il rame è abbastanza comune, non dovrebbe tardare ad arrivare qualche avventuriero con delle scaglie di rame.`n`n");
        }
    }else{
        output("Gordon accarezza la sua pesante mazza ferrata, ti guarda truce, e agitando l'indice sul tuo muso dice :\"Non fare il furbo con me !\".`n`n");
    }

}elseif($_GET['op']=="vendisr"){

    $sql = "SELECT * FROM zaino WHERE idoggetto = 2 AND idplayer='{$session['user']['acctid']}'";
    $result = db_query($sql) or die(db_error(LINK));

    if (db_num_rows($result)<1){
        output(
        "Gordon accarezza la sua pesante mazza ferrata, ti guarda truce, e agitando l'indice sul tuo muso dice :\"Non fare il furbo con mè !\".`n`n");
    }else{
        output(
        "Gordon accetta la tua scaglia di rame e ti posa in mano ".($val_scaglie_rame/2)." pezzi d'oro in cambio.`n`n");
        $session['user']['gold']+=($val_scaglie_rame/2);
        $row = db_fetch_assoc($result);
        debuglog("vende 1 scaglia di rame ad Gordon");
        $sqld = "DELETE FROM zaino WHERE id = '{$row['id']}'";
        db_query($sqld);
        savesetting("scaglierame_cd",getsetting("scaglierame_cd",0)+1);
        addnav("Torna da Gordon","fabbro_citta_draghi.php");
    }
}elseif($_GET['op']=="comprasm"){
    if ($session['user']['gold']>=$val_scaglie_metallo){
        if ($scaglie_ferro >= 1) {

            output(
            "Gordon afferra i tuoi ".$val_scaglie_metallo." pezzi d'oro e ti da 1 scaglia di ferro in cambio.`n`n");
            $session['user']['gold']-=$val_scaglie_metallo;
            $sqli="INSERT INTO zaino (idoggetto, idplayer) VALUES (1, '{$session['user']['acctid']}')";
            db_query($sqli) or die(db_error(LINK));
            debuglog("compra 1 scaglia di ferro da Gordon");
            if (getsetting("scagliemetallo_cd",0) - 1 < 1) {
                savesetting("scagliemetallo_cd","0");
            } else {
                savesetting("scagliemetallo_cd",getsetting("scagliemetallo_cd",0)-1);
            }
        } else {
            output("Purtroppo Gordon ha già venduto tutte le scaglie di ferro che aveva, sei arrivato troppo tardi.`n");
            output("Il ferro è abbastanza comune, non dovrebbe tardare ad arrivare qualche avventuriero con delle scaglie di ferro.`n`n");
        }
    }else{
        output("Gordon accarezza la sua pesante mazza ferrata, ti guarda truce, e agitando l'indice sul tuo muso dice :\"Non fare il furbo con me !\".`n`n");
    }
}elseif($_GET['op']=="vendism"){
    $sql = "SELECT * FROM zaino WHERE idoggetto = 1 AND idplayer='{$session['user']['acctid']}'";
    $result = db_query($sql) or die(db_error(LINK));

    if (db_num_rows($result)<1){
        output("Gordon accarezza la sua pesante mazza ferrata, ti guarda truce, e agitando l'indice sul tuo muso dice :\"Non fare il furbo con me !\".`n`n");
    }else{
        output("Gordon accetta le tue scaglie di ferro e ti posa in mano ".($val_scaglie_metallo/2)." pezzi d'oro in cambio.`n`n");
        $session['user']['gold']+=($val_scaglie_metallo/2);
        $row = db_fetch_assoc($result);
        $sqld = "DELETE FROM zaino WHERE id = '{$row['id']}'";
        db_query($sqld);
        debuglog("vende 1 scaglia di ferro ad Gordon");
        savesetting("scagliemetallo_cd",getsetting("scagliemetallo_cd",0)+1);
        addnav("Torna da Gordon","fabbro_citta_draghi.php");
    }
}elseif($_GET['op']=="compraar"){
    if ($session['user']['gold']>=$val_argento){
        if ($argento >= 1) {

            output(
            "Gordon afferra i tuoi ".$val_argento." pezzi d'oro e ti da 1 scaglia di argento in cambio.`n`n");
            $session['user']['gold']-=$val_argento;
            $sqli="INSERT INTO zaino (idoggetto, idplayer) VALUES (5, '{$session['user']['acctid']}')";
            db_query($sqli) or die(db_error(LINK));
            debuglog("compra 1 scaglia d'argento da Gordon");
            if (getsetting("argento_cd",0) - 1 < 1) {
                savesetting("argento_cd","0");
            } else {
                savesetting("argento_cd",getsetting("argento_cd",0)-1);
            }
        } else {
            output("Purtroppo Gordon ha già venduto tutte le scaglie d'argento che aveva, sei arrivato troppo tardi.`n");
            output("L'argento è abbastanza raro non arriverà prima di qualche giorno!.`n`n");
        }
    }else{
        output("Gordon accarezza la sua pesante mazza ferrata, ti guarda truce, e agitando l'indice sul tuo muso dice :\"Non fare il furbo con me !\".`n`n");
    }
}elseif($_GET['op']=="vendiar"){
    $sql = "SELECT * FROM zaino WHERE idoggetto = 5 AND idplayer='{$session['user']['acctid']}'";
    $result = db_query($sql) or die(db_error(LINK));

    if (db_num_rows($result)<1){
        output("Gordon accarezza la sua pesante mazza ferrata, ti guarda truce, e agitando l'indice sul tuo muso dice :\"Non fare il furbo con me !\".`n`n");
    }else{
        output("Gordon accetta le tue scaglie d'argento e ti posa in mano ".($val_argento/2)." pezzi d'oro in cambio.`n`n");
        $session['user']['gold']+=($val_argento/2);
        $row = db_fetch_assoc($result);
        $sqld = "DELETE FROM zaino WHERE id = '{$row['id']}'";
        db_query($sqld);
        debuglog("vende 1 scaglia di ferro ad Gordon");
        savesetting("argento_cd",getsetting("argento_cd",0)+1);
        addnav("Torna da Gordon","fabbro_citta_draghi.php");
    }
}elseif($_GET['op']=="compraor"){
    if ($session['user']['gold']>=$val_oro){
        if ($oro >= 1) {

            output(
            "Gordon afferra i tuoi ".$val_oro." pezzi d'oro e ti da 1 scaglia di oro in cambio.`n`n");
            $session['user']['gold']-=$val_oro;
            $sqli="INSERT INTO zaino (idoggetto, idplayer) VALUES (7, '{$session['user']['acctid']}')";
            db_query($sqli) or die(db_error(LINK));
            debuglog("compra 1 scaglia d'oro da Gordon");
            if (getsetting("oro_cd",0) - 1 < 1) {
                savesetting("oro_cd","0");
            } else {
                savesetting("oro_cd",getsetting("oro_cd",0)-1);
            }
        } else {
            output("Purtroppo Gordon ha già venduto tutte le scaglie d'oro che aveva, sei arrivato troppo tardi.`n");
            output("L'oro è molto raro non arriverà prima di qualche settimana!.`n`n");
        }
    }else{
        output("Gordon accarezza la sua pesante mazza ferrata, ti guarda truce, e agitando l'indice sul tuo muso dice :\"Non fare il furbo con me !\".`n`n");
    }
}elseif($_GET['op']=="vendior"){
    $sql = "SELECT * FROM zaino WHERE idoggetto = 7 AND idplayer='{$session['user']['acctid']}'";
    $result = db_query($sql) or die(db_error(LINK));

    if (db_num_rows($result)<1){
        output("Gordon accarezza la sua pesante mazza ferrata, ti guarda truce, e agitando l'indice sul tuo muso dice :\"Non fare il furbo con me !\".`n`n");
    }else{
        output("Gordon accetta le tue scaglie d'oro e ti posa in mano ".($val_oro/2)." pezzi d'oro in cambio.`n`n");
        $session['user']['gold']+=($val_oro/2);
        $row = db_fetch_assoc($result);
        $sqld = "DELETE FROM zaino WHERE id = '{$row['id']}'";
        db_query($sqld);
        debuglog("vende 1 scaglia di oro ad Gordon");
        savesetting("oro_cd",getsetting("oro_cd",0)+1);
        addnav("Torna da Gordon","fabbro_citta_draghi.php");
    }
}elseif($_GET['op']=="compraad"){
    if ($session['user']['gold']>=$val_ada){
        if ($ada >= 1) {

            output(
            "Gordon afferra i tuoi ".$val_ada." pezzi d'oro e ti da 1 scaglia di adamantite in cambio.`n`n");
            $session['user']['gold']-=$val_ada;
            $sqli="INSERT INTO zaino (idoggetto, idplayer) VALUES (8, '{$session['user']['acctid']}')";
            db_query($sqli) or die(db_error(LINK));
            debuglog("compra 1 scaglia d'adamantite da Gordon");
            if (getsetting("ada",0) - 1 < 1) {
                savesetting("ada","0");
            } else {
                savesetting("ada",getsetting("ada",0)-1);
            }
        } else {
            output("Purtroppo Gordon ha già venduto tutte le scaglie di adamantite che aveva, sei arrivato troppo tardi.`n");
            output("L'adamatite è molto rara non arriverà prima di qualche settimana!.`n`n");
        }
    }else{
        output("Gordon accarezza la sua pesante mazza ferrata, ti guarda truce, e agitando l'indice sul tuo muso dice :\"Non fare il furbo con me !\".`n`n");
    }
}elseif($_GET['op']=="vendiad"){
    $sql = "SELECT * FROM zaino WHERE idoggetto = 8 AND idplayer='{$session['user']['acctid']}'";
    $result = db_query($sql) or die(db_error(LINK));

    if (db_num_rows($result)<1){
        output("Gordon accarezza la sua pesante mazza ferrata, ti guarda truce, e agitando l'indice sul tuo muso dice :\"Non fare il furbo con me !\".`n`n");
    }else{
        output("Gordon accetta le tue scaglie d'adamantite e ti posa in mano ".($val_ada/2)." pezzi d'oro in cambio.`n`n");
        $session['user']['gold']+=($val_ada/2);
        $row = db_fetch_assoc($result);
        $sqld = "DELETE FROM zaino WHERE id = '{$row['id']}'";
        db_query($sqld);
        debuglog("vende 1 scaglia di adamantite ad Gordon");
        savesetting("ada",getsetting("ada",0)+1);
        addnav("Torna da Gordon","fabbro_citta_draghi.php");
    }
}
elseif($_GET['op']=="comprami"){
    if ($session['user']['gold']>=$val_mit){
        if ($mit >= 1) {
            output(
            "Gordon afferra i tuoi ".$val_mit." pezzi d'oro e ti da 1 scaglia di mithril in cambio.`n`n");
            $session['user']['gold']-=$val_mit;
            $sqli="INSERT INTO zaino (idoggetto, idplayer) VALUES (9, '{$session['user']['acctid']}')";
            db_query($sqli) or die(db_error(LINK));
            debuglog("compra 1 scaglia di mithril da Gordon");
            if (getsetting("mit",0) - 1 < 1) {
                savesetting("mit","0");
            } else {
                savesetting("mit",getsetting("mit",0)-1);
            }
        } else {
            output("Purtroppo Gordon ha già venduto tutte le scaglie di mithril che aveva, sei arrivato troppo tardi.`n");
            output("L'oro è molto raro non arriverà prima di qualche settimana!.`n`n");
        }
    }else{
        output("Gordon accarezza la sua pesante mazza ferrata, ti guarda truce, e agitando l'indice sul tuo muso dice :\"Non fare il furbo con me !\".`n`n");
    }
}elseif($_GET['op']=="vendimi"){
    $sql = "SELECT * FROM zaino WHERE idoggetto = 9 AND idplayer='{$session['user']['acctid']}'";
    $result = db_query($sql) or die(db_error(LINK));

    if (db_num_rows($result)<1){
        output("Gordon accarezza la sua pesante mazza ferrata, ti guarda truce, e agitando l'indice sul tuo muso dice :\"Non fare il furbo con me !\".`n`n");
    }else{
        output("Gordon accetta le tue scaglie di mithril e ti posa in mano ".($val_mit/2)." pezzi d'oro in cambio.`n`n");
        $session['user']['gold']+=($val_mit/2);
        $row = db_fetch_assoc($result);
        $sqld = "DELETE FROM zaino WHERE id = '{$row['id']}'";
        db_query($sqld);
        debuglog("vende 1 scaglia di oro ad Gordon");
        savesetting("mit",getsetting("mit",0)+1);
        addnav("Torna da Gordon","fabbro_citta_draghi.php");
    }
}
elseif($_GET['op']=="comprasd"){
    if ($session['user']['gold']>=$val_sdra){
        if ($sdra >= 1) {

            output(
            "Gordon afferra i tuoi ".$val_sdra." pezzi d'oro e ti da 1 scaglia di drago in cambio.`n`n");
            $session['user']['gold']-=$val_sdra;
            $sqli="INSERT INTO zaino (idoggetto, idplayer) VALUES (10, '{$session['user']['acctid']}')";
            db_query($sqli) or die(db_error(LINK));
            debuglog("compra 1 scaglia di srago da Gordon");
            if (getsetting("sdra",0) - 1 < 1) {
                savesetting("sdra","0");
            } else {
                savesetting("sdra",getsetting("sdra",0)-1);
            }
        } else {
            output("Purtroppo Gordon ha già venduto tutte le scaglie di drago che aveva, sei arrivato troppo tardi.`n");
            output("L'oro è molto raro non arriverà prima di qualche mese!.`n`n");
        }
    }else{
        output("Gordon accarezza la sua pesante mazza ferrata, ti guarda truce, e agitando l'indice sul tuo muso dice :\"Non fare il furbo con me !\".`n`n");
    }
}elseif($_GET['op']=="vendisd"){
    $sql = "SELECT * FROM zaino WHERE idoggetto = 10 AND idplayer='{$session['user']['acctid']}'";
    $result = db_query($sql) or die(db_error(LINK));

    if (db_num_rows($result)<1){
        output("Gordon accarezza la sua pesante mazza ferrata, ti guarda truce, e agitando l'indice sul tuo muso dice :\"Non fare il furbo con me !\".`n`n");
    }else{
        output("Gordon accetta le tue scaglie d'oro e ti posa in mano ".($val_sdra/2)." pezzi d'oro in cambio.`n`n");
        $session['user']['gold']+=($val_sdra/2);
        $row = db_fetch_assoc($result);
        $sqld = "DELETE FROM zaino WHERE id = '{$row['id']}'";
        db_query($sqld);
        debuglog("vende 1 scaglia di drago ad Gordon");
        savesetting("sdra",getsetting("sdra",0)+1);
        addnav("Torna da Gordon","fabbro_citta_draghi.php");
    }
}
page_footer();
?>