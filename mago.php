<?php
require_once("common.php");
require_once("common2.php");
manutenzione(getsetting("manutenzione",3));

checkday();
/*
Descrizione tabelle dei database

*/

// Funzioni per le rigenerazioni oggetti magici
function getCosto($valore, $percentuale_oggetto, $riparazione, $manodopera) {
        return intval(($valore * $percentuale_oggetto * $riparazione * (1 + $manodopera)) / 100);
}
function getPercentuale($usura, $usura_max) {
        $percentuale = intval(100 - (($usura * 100) / $usura_max));
        if ($percentuale > 100) $percentuale=100;
        return $percentuale;
}
function getPuntiMana($percentuale_oggetto,$livello) {
        return intval( ($percentuale_oggetto/2)  * $livello);
}

// Funzione per generare il costo in punti mana di un incantesimo
function mana_incantesimo($grado,$livello_incantesimo,$incant) {
        switch($grado) {
                case 41:
                return $incant;
                break;
                case 42:
                if ($livello_incantesimo == 1) {
                        return intval($incant*3/4);
                } else {
                        return $incant;
                }
                break;
                case 43:
                if ($livello_incantesimo == 1) {
                        return intval($incant*2/3);
                } elseif ($livello_incantesimo == 2) {
                        return intval($incant*3/4);
                } else {
                        return $incant;
                }
                break;
                case 44:
                if ($livello_incantesimo == 1) {
                        return intval($incant*3/5);
                } elseif ($livello_incantesimo == 2) {
                        return intval($incant*2/3);
                } else {
                        return intval($incant*3/4);
                }
                break;
        }
}
//determinazione numero dei maghi (Sook)
$sqlmag = "SELECT acctid FROM accounts WHERE carriera > 40 AND carriera < 45 AND superuser=0";
$resultmag = db_query($sqlmag) or die(db_error(LINK));
$player_maghi = db_num_rows($resultmag);
//fine numero maghi

//Sook, settaggio automatico ad arcimago per gli admin
if ($session['user']['superuser'] > 2) {
    $session['user']['carriera'] = 44;
}

// Variabili

$manodopera = 15/100;
$riparazione = 50/100;
$dk = $session['user']['dragonkills'];
$carriera = $session['user']['carriera'];
$idplayer = $session['user']['acctid'];

$mana = getsetting("mana",0);

//Controllo presenza del record del giorno odierno nella tabella "mana" del database e creazione se inesistente
$data_unix = time();
$data = date("y-m-d");
$sqlmp = "SELECT * FROM mana WHERE acctid='{$idplayer}' AND data='{$data}'";;
$resmp = db_query($sqlmp) or die(db_error(LINK));
$managiocatore = db_fetch_assoc($resmp);
$manaplayer = $managiocatore['mana_player'];
if (db_num_rows($resmp) == 0 AND $session['user']['carriera']>40 AND $session['user']['carriera']<45) {
        //creo il campo
        $sqloggi="INSERT INTO mana (acctid, data, mana_player) VALUES ('".$idplayer."', '".$data."', '0')";
        db_query($sqloggi) or die(db_error(LINK));
        $manaplayer = 0;
}
//cancellazione dei record vecchi dalla tabella mana
$giorni_durata = 5;
$dataold = 60*60*24*$giorni_durata; //da sistemare
$sqlold = "DELETE FROM mana WHERE $data_unix-UNIX_TIMESTAMP(data) > '".$dataold."'";
db_query($sqlold);

$arraycarriera = array(
41=>"Iniziato",
42=>"Stregone",
43=>"Mago",
44=>"Arcimago",
);
$statoogg = array(
0=>"Ottimale",
1=>"Forte",
2=>"Accettabile",
3=>"Debole",
4=>"Minimo",
5=>"`(Corrotto",
);

$zolfo = getsetting("zolfo",0);
$mandragola = getsetting("mandragola",0);
$silice = getsetting("silice",0);
$argento = getsetting("argentomaghi",0);
$oro = getsetting("oromaghi",0);
$caso = mt_rand(1,4);
$caso2 = mt_rand(1,4);
$caso3 = mt_rand(1,4);
$cento = mt_rand(1,100);

// Randomizzazione mercato
$ultimo_mercato = getsetting("ultimo_mercato_m", 0);
$prossimo_mercato = getsetting("prossimo_mercato_m", 0);
$data_oggi = time();
if (($data_oggi - $ultimo_mercato) > ($prossimo_mercato)) {
//        $zolfo += intval($zolfo*e_rand(-10, 10)/100) + e_rand(-5, 5);
//        $mandragola += intval($mandragola*e_rand(-10, 10)/100) + e_rand(-5, 5);
//        $silice += intval($silice*e_rand(-10, 10)/100) + e_rand(-5, 5);
        $zolfo += e_rand(10, 70);
        $mandragola += e_rand(10, 50);
        $silice += e_rand(10, 50);
        if (e_rand(1,5) == 1) $argento += intval($argento*e_rand(-10, 10)/100) + e_rand(-5, 5);
        if (e_rand(1,5) == 10)$oro += intval($oro*e_rand(-10, 10)/100) + e_rand(-5, 5);
        savesetting("zolfo",$zolfo);
        savesetting("mandragola",$mandragola);
        savesetting("silice",$silice);
        savesetting("argentomaghi",$argento);
        savesetting("oromaghi",$oro);
        addnews("Una carovana di mercanti stranieri è stata vista nei pressi della torre dei maghi");
    $data_mercato = mt_rand(43200,259200);
    savesetting("prossimo_mercato_m", $data_mercato);
    savesetting("ultimo_mercato_m", $data_oggi);
}
// fine randimizzazione mercato
// nomina arcimago
if ($carriera == 43 OR $carriera == 44) {
        if ($session['user']['superuser'] == 0) {
            $carrierainiz=$carriera;
            savesetting("arcimago","0");
            $sqlma = "SELECT acctid FROM accounts WHERE
        carriera = 43 OR carriera = 44 AND superuser = 0
        ORDER BY punti_carriera DESC LIMIT 1";
            $resultma = db_query($sqlma) or die(db_error(LINK));
            $rowma = db_fetch_assoc($resultma);
            $am = $rowma['acctid'];
            savesetting("arcimago", $am);
        }
        if ((getsetting("arcimago",0)!=$session['user']['acctid'] AND $session['user']['superuser']==0) OR $session['user']['punti_carriera']<20000){
            $session['user']['carriera'] = 43;
            if ($carrierainiz == 44) {
                output("<big>`\$`b`cRetrocessione`c`n`b`0</big>",true);
                output("`#Non sei più il miglior Mago di Rafflingate, pertanto non ti puoi più fregiare del titolo di `VArcimago`#.`n");
            }
        }else{
            if ($carrierainiz == 43) {
                output("<big>`^`b`cPromozione`c`b`0`n</big>",true);
                output("Sei diventato l'Arcimago!`n`n");
                $sqlma = "SELECT acctid FROM accounts WHERE
                carriera = 43 OR carriera = 44
                ORDER BY punti_carriera DESC LIMIT 2";
                $resultma = db_query($sqlma) or die(db_error(LINK));
                $countrowma = db_num_rows($resultma);
                for ($i=0; $i<$countrowma; $i++){
                //for ($i = 0;$i < db_num_rows($resultma);$i++) {
                    $rowma = db_fetch_assoc($resultma);
                }
                if ($session['user']['superuser']==0) {
                    $sqlaggiornaam = "UPDATE accounts SET carriera = 43 WHERE acctid = ".$rowma['acctid']."";
                    $resultaggiornaam = db_query($sqlaggiornaam) or die(db_error(LINK));
                }
            }
            $session['user']['carriera'] = 44;
        }
}

// prezzo acquisto variabile
$val_zolfo = 200-(10*$zolfo);
$val_mandragola = 500-(10*$mandragola);
$val_silice = 50-(10*$silice);
$val_argento = 5000-(10*$argento);
$val_oro = 8400-(10*$oro);
if ($val_zolfo < 10) $val_zolfo=10;
if ($val_mandragola < 10) $val_mandragola=10;
if ($val_silice < 10) $val_silice=10;
if ($val_argento < 100) $val_argento=100;
if ($val_oro < 500) $val_oro=500;

page_header("La Torre Dei Maghi");
$session['user']['locazione'] = 146;

// Inizio Menù di Navigazione
if ($_GET['op']!="corso" AND $_GET['op']!="laboratorio" AND $_GET['op']!="prepara" AND $_GET['op']!="ingredienti" AND $_GET['op']!="makemana" AND $_GET['op']!="alchimia") {
        addnav("Azioni");
}
if ($carriera == 0 AND $_GET['op']!="rigenerazione" AND $_GET['op']!="iniziato" AND $_GET['op']!="diventainiziato" AND $_GET['op']!="corso" AND $_GET['op']!="laboratorio" AND $_GET['op']!="prepara" AND $_GET['op']!="ingredienti" AND $_GET[op]!="sfera" AND $_GET['op']!="quantomana" AND $_GET['op']!="alchimia") {
        addnav("Impara l'arte della magia","mago.php?op=iniziato");
}
if ($carriera > 40 AND $carriera < 45 AND $_GET['op']!="corso" AND $_GET['op']!="laboratorio" AND $_GET['op']!="prepara" AND $_GET['op']!="ingredienti" AND $_GET['op']!="makemana" AND $_GET[op]!="sfera" AND $_GET['op']!="quantomana" AND $_GET['op']!="alchimia") {
        addnav("Corso di Magia", "mago.php?op=corso");
}
// esercizio per livello
if ($carriera > 40 AND $carriera < 45 AND $_GET['op']!="corso" AND $_GET['op']!="laboratorio" AND $_GET['op']!="prepara" AND $_GET['op']!="ingredienti" AND $_GET['op']!="makemana" AND $_GET[op]!="sfera" AND $_GET['op']!="quantomana" AND $_GET['op']!="alchimia") {
        addnav("M?Esercitati nel Controllo del Mana","mago.php?op=controllo");
}
if ($carriera > 41 AND $carriera < 45 AND $_GET['op']!="corso" AND $_GET['op']!="laboratorio" AND $_GET['op']!="prepara" AND $_GET['op']!="ingredienti" AND $_GET['op']!="makemana" AND $_GET[op]!="sfera" AND $_GET['op']!="quantomana" AND $_GET['op']!="alchimia") {
        addnav("S?Esercitati nella Divinazione","mago.php?op=divinazione");
}
if ($carriera > 42 AND $carriera < 45 AND $_GET['op']!="corso" AND $_GET['op']!="laboratorio" AND $_GET['op']!="prepara" AND $_GET['op']!="ingredienti" AND $_GET['op']!="makemana" AND $_GET[op]!="sfera" AND $_GET['op']!="quantomana" AND $_GET['op']!="alchimia") {
        addnav("E?Esercitati nell'Evocazione","mago.php?op=evocazione");
}
if ($carriera == 44 AND $_GET['op']!="corso" AND $_GET['op']!="laboratorio" AND $_GET['op']!="prepara" AND $_GET['op']!="ingredienti" AND $_GET['op']!="makemana" AND $_GET[op]!="sfera" AND $_GET['op']!="quantomana" AND $_GET['op']!="alchimia") {
        addnav("A?Esercitati nell'Alchimia","mago.php?op=alchimiaeserc");
}
if ($carriera > 40 AND $carriera < 45 AND $_GET['op']!="corso" AND $_GET['op']!="laboratorio" AND $_GET['op']!="prepara" AND $_GET['op']!="ingredienti" AND $_GET['op']!="makemana" AND $_GET['op']!="alchimia") {
        addnav("G?Genera del Mana", "mago.php?op=makemana&og=intro");
}
if ($carriera == 44 AND $_GET['op']!="corso" AND $_GET['op']!="laboratorio" AND $_GET['op']!="prepara" AND $_GET['op']!="ingredienti" AND $_GET['op']!="makemana" AND $_GET['op']!="alchimia") {
        addnav("P?Prova a creare dei materiali", "mago.php?op=alchimia&og=intro");
        addnav("Punisci un mago","mago.php?op=punisci");
}
// Riparazioni
if ($_GET['op']!="iniziato" AND $_GET['op']!="rigenerazione" AND $_GET['op']!="corso" AND $_GET['op']!="laboratorio" AND $_GET['op']!="prepara" AND $_GET['op']!="ingredienti" AND $_GET['op']!="makemana" AND $_GET[op]!="sfera" AND $_GET['op']!="quantomana" AND $_GET['op']!="alchimia") {
        addnav("Rigenerazione");
        addnav("R?Servizio Rigenerazione Oggetti Magici", "mago.php?op=rigenerazione");
        if ($carriera > 41 AND $carriera < 45) addnav("Q?Prendi la tua quota", "mago.php?op=riscuoti");
}
//Inizio carriera del mago come iniziato, conferma
if($_GET['op']=="iniziato"){
        addnav("Diventa iniziato", "mago.php?op=diventainiziato");
}
// Manù Info Varie
if ($_GET['op']!="corso" AND $_GET['op']!="laboratorio" AND $_GET['op']!="prepara" AND $_GET['op']!="ingredienti" AND $_GET['op']!="makemana" AND $_GET[op]!="sfera" AND $_GET['op']!="quantomana" AND $_GET['op']!="alchimia") {
        addnav("Info");
        if ($session['user']['superuser'] >=3) addnav("Analizza ulteriormente il mana", "mago.php?op=sfera");
}
if ($carriera > 40 AND $carriera < 45 AND $_GET['op']!="corso" AND $_GET['op']!="laboratorio" AND $_GET['op']!="prepara" AND $_GET['op']!="ingredienti" AND $_GET['op']!="makemana" AND $_GET[op]!="sfera" AND $_GET['op']!="quantomana" AND $_GET['op']!="alchimia") {
        addnav("d?Chiedi come stai andando","mago.php?op=chiedi");
        addnav("I Migliori Maghi","mago.php?op=migliori");
        addnav("La Sala Riunioni","mago.php?op=sala");
        addnav("Percepisci Mana","mago.php?op=quantomana");
        addnav("Puniti","mago.php?op=puniti");
}
// Promozione a Stregone
if($session['user']['punti_carriera']>=5000 AND $carriera == 41 AND ($dk > 10 OR $session['user']['reincarna'] > 0)){
        output("<big>`^`b`cPromozione`c`b`0`n</big>",true);
        output("Amon ti si avvicina, con il suo solito sguardo impenetrabile che lo caratterizza.`n");
        output("Guardandoti fisso negli occhi, ti dice:`n `&\"Ho notato che il tuo potere magico è aumentato molto ultimamente.`n");
        output("Sei diventato in grado di percepire il mana con maggiore precisione.`n");
        output("Anche la tua capacità di lanciare incantesimi è migliorata, puoi prepararli consumando minor quantità di mana.`n`n");
        output("Pertanto sono lieto di annunciarti la promozione al grado di Stregone!`n`n");
        output("Da ora puoi esercitarti nelle tecniche di divinazione, per migliorare più velocemente la tua conoscenza delle arti magiche.\"`7.`n");
        output("Inoltre, ti verrà dato accesso agli incantesimi di secondo livello, che potrai preparare nel nostro laboratorio.`n`n`n");
        debuglog("è stato promosso da Amon al grado di Stregone");
        $session['user']['carriera'] = 42;
}
// Promozione a Mago
if($session['user']['punti_carriera']>=20000 AND $carriera == 42 AND ($dk > 15 OR $session['user']['reincarna'] > 0)){
        output("<big>`^`b`cPromozione`c`b`0`n</big>",true);
        output("Amon ti si avvicina, con il suo solito sguardo impenetrabile che lo caratterizza.`n");
        output("Guardandoti fisso negli occhi, ti dice:`n `&\"Ho notato che il tuo potere magico è ulteriormente aumentato in questi giorni.`n");
        output("Il tuo controllo sul mana è diventato ormai praticamente perfetto.`n");
        output("Riesci a percepirne ogni minima vibrazione e a manipolarlo facilmente, preparando incantesimi con il minimo sforzo e ancora meno mana rispetto a prima.`n`n");
        output("Pertanto sono lieto di annunciarti la promozione al grado di Mago!`n`n");
        output("Da ora puoi esercitarti nelle tecniche di evocazione, che sai non essere esenti da pericoli, per migliorare più velocemente la tua conoscenza delle arti magiche.\"`7.`n`n");
        output("Inoltre, ti verrà dato accesso agli incantesimi di terzo livello, che potrai preparare nel nostro laboratorio.`n`n`n");
        debuglog("è stato promosso da Amon al grado di Mago");
        $session['user']['carriera'] = 43;
}
// Blocco di Compra/Vendita Materiali
if ($session['user']['level'] > 4) addnav("","mago.php?op=vendizolfo");
if ($session['user']['level'] < 15) addnav("","mago.php?op=comprazolfo");
if ($session['user']['level'] > 4) addnav("","mago.php?op=vendimandragola");
if ($session['user']['level'] < 15) addnav("","mago.php?op=compramandragola");
if ($session['user']['level'] > 4) addnav("","mago.php?op=vendisilice");
if ($session['user']['level'] < 15) addnav("","mago.php?op=comprasilice");
// Nuovi Materiali
if ($session['user']['level'] > 4) addnav("","mago.php?op=vendiargento");
if ($session['user']['level'] < 15) addnav("","mago.php?op=compraargento");
if ($session['user']['level'] > 4) addnav("","mago.php?op=vendioro");
if ($session['user']['level'] < 15) addnav("","mago.php?op=compraoro");

if ($_GET['op']!="corso" AND $_GET['op']!="rigenerazione" AND $_GET['op']!="iniziato" AND $_GET['op']!="laboratorio" AND $_GET['op']!="prepara" AND $_GET['op']!="ingredienti" AND $_GET['op']!="makemana" AND $_GET[op] !== "sfera" AND $_GET['op']!="prepara" AND $_GET['op']!="ingredienti" AND $_GET['op']!="quantomana" AND $_GET['op']!="alchimia") {
        addnav("Laboratorio");
        addnav("L?Laboratorio","mago.php?op=laboratorio");
}
//Alchimia, per creare materiali da mago
else if ($_GET[op] == "alchimia") {
        $prob=e_rand(-10,150);
        if ($prob<0) $prob=0;
        if ($_GET[og] == "intro") {
            output("`&Con la tua conoscenza della magia puoi provare ad utilizzare il mana per creare degli ingredienti per gli incantesimi.`nI materiali così creati verranno messi in vendita per te o per gli altri maghi.`n`n");
            output("Non sei però sicuro di riuscirci, e potresti ritrovarti a sprecare del mana per nulla...`nVuoi davvero tentare?`n`n");
            addnav("Alchimia");
            addnav("Crea della silice","mago.php?op=alchimia&og=silice");
            addnav("Crea dello zolfo","mago.php?op=alchimia&og=zolfo");
            addnav("Crea della mandragola","mago.php?op=alchimia&og=mandragola");
            addnav("Crea dell'argento","mago.php?op=alchimia&og=argento");
            addnav("Crea dell'oro","mago.php?op=alchimia&og=oro");
            debuglog("ha analizzato la generazione del mana da parte dei singoli maghi");
        }elseif ($_GET[og] == "silice") {
            if ($session['user']['turns']<3) {
                output("`\$Sei troppo stanco per tentare un incantesimo così faticoso.");
            }elseif ($mana<50000) {
                output("`\$Non c'è mana a sufficienza per questa magia.");
            }elseif ($session['user']['punti_carriera']<2000) {
                output("`\$Non hai abbastanza punti carriera per questa magia.");
            }else{
                output("`^Ti concentri e cominci a pronunciare delle formule arcane. L'incantesimo è lungo e consuma gran parte delle tue energie.`n`n");
                $mana -= 50000;
                $manaplayer -= 50000;
                savesetting("mana",$mana);
                $sqlmana = "UPDATE mana SET mana_player = '".$manaplayer."' WHERE acctid='{$idplayer}' AND data='{$data}'";
                db_query($sqlmana) or die(db_error(LINK));
                $prob=intval($prob/2);
                if ($prob>50) $prob=50;
                $session['user']['turns'] -= 3;
                $session['user']['punti_carriera']-=2000;
                switch ($prob){
                    case 0:
                        output("`#Qualcosa è andato storto, non sei riuscito a generare nemmeno una dose di silice!");
                    break;
                    case 1:
                        output("`#Hai generato una dose di silice.");
                    break;
                    default:
                        output("`#Hai generato $prob dosi di silice.");
                    break;
                }
                savesetting("silice",getsetting("silice",0)+$prob);
                debuglog("ha utilizzato l'alchimia per generare $prob dosi di silice, spendendo 3 turni, 5000 punti carriera e 50000 punti mana");
            }
        }elseif ($_GET[og] == "zolfo") {
            if ($session['user']['turns']<3) {
                output("`\$Sei troppo stanco per tentare un incantesimo così faticoso.");
            }elseif ($mana<50000) {
                output("`\$Non c'è mana a sufficienza per questa magia.");
            }elseif ($session['user']['punti_carriera']<2000) {
                output("`\$Non hai abbastanza punti carriera per questa magia.");
            }else{
                output("`^Ti concentri e cominci a pronunciare delle formule arcane. L'incantesimo è lungo e consuma gran parte delle tue energie.`n`n");
                $mana -= 50000;
                $manaplayer -= 50000;
                savesetting("mana",$mana);
                $sqlmana = "UPDATE mana SET mana_player = '".$manaplayer."' WHERE acctid='{$idplayer}' AND data='{$data}'";
                db_query($sqlmana) or die(db_error(LINK));
                $prob=intval($prob/2.5);
                if ($prob>40) $prob=40;
                $session['user']['turns'] -= 3;
                $session['user']['punti_carriera']-=2000;
                switch ($prob){
                    case 0:
                        output("`#Qualcosa è andato storto, non sei riuscito a generare nemmeno una dose di zolfo!");
                    break;
                    case 1:
                        output("`#Hai generato una dose di zolfo.");
                    break;
                    default:
                        output("`#Hai generato $prob dosi di zolfo.");
                    break;
                }
                savesetting("zolfo",getsetting("zolfo",0)+$prob);
                debuglog("ha utilizzato l'alchimia per generare $prob dosi di zolfo, spendendo 3 turni, 5000 punti carriera e 50000 punti mana");
            }
        }elseif ($_GET[og] == "mandragola") {
            if ($session['user']['turns']<3) {
                output("`\$Sei troppo stanco per tentare un incantesimo così faticoso.");
            }elseif ($mana<50000) {
                output("`\$Non c'è mana a sufficienza per questa magia.");
            }elseif ($session['user']['punti_carriera']<2000) {
                output("`\$Non hai abbastanza punti carriera per questa magia.");
            }else{
                output("`^Ti concentri e cominci a pronunciare delle formule arcane. L'incantesimo è lungo e consuma gran parte delle tue energie.`n`n");
                $mana -= 50000;
                $manaplayer -= 50000;
                savesetting("mana",$mana);
                $sqlmana = "UPDATE mana SET mana_player = '".$manaplayer."' WHERE acctid='{$idplayer}' AND data='{$data}'";
                db_query($sqlmana) or die(db_error(LINK));
                $prob=intval($prob/4);
                if ($prob>25) $prob=25;
                $session['user']['turns'] -= 3;
                $session['user']['punti_carriera']-=2000;
                switch ($prob){
                    case 0:
                        output("`#Qualcosa è andato storto, non sei riuscito a generare nemmeno una foglia di mandragola!");
                    break;
                    case 1:
                        output("`#Hai generato una foglia di mandragola.");
                    break;
                    default:
                        output("`#Hai generato $prob foglie di mandragola.");
                    break;
                }
                savesetting("mandragola",getsetting("mandragola",0)+$prob);
                debuglog("ha utilizzato l'alchimia per generare $prob foglie di mandragola, spendendo 3 turni, 5000 punti carriera e 50000 punti mana");
            }
        }elseif ($_GET[og] == "argento") {
            if ($session['user']['turns']<3) {
                output("`\$Sei troppo stanco per tentare un incantesimo così faticoso.");
            }elseif ($mana<50000) {
                output("`\$Non c'è mana a sufficienza per questa magia.");
            }elseif ($session['user']['punti_carriera']<2000) {
                output("`\$Non hai abbastanza punti carriera per questa magia.");
            }else{
                output("`^Ti concentri e cominci a pronunciare delle formule arcane. L'incantesimo è lungo e consuma gran parte delle tue energie.`n`n");
                $mana -= 50000;
                $manaplayer -= 50000;
                savesetting("mana",$mana);
                $sqlmana = "UPDATE mana SET mana_player = '".$manaplayer."' WHERE acctid='{$idplayer}' AND data='{$data}'";
                db_query($sqlmana) or die(db_error(LINK));
                $prob=intval($prob/10);
                if ($prob>10) $prob=10;
                $session['user']['turns'] -= 3;
                $session['user']['punti_carriera']-=2000;
                switch ($prob){
                    case 0:
                        output("`#Qualcosa è andato storto, non sei riuscito a generare nemmeno una scaglia di argento!");
                    break;
                    case 1:
                        output("`#Hai generato una scaglia di argento.");
                    break;
                    default:
                        output("`#Hai generato $prob scaglie di argento.");
                    break;
                }
                savesetting("argentomaghi",getsetting("argentomaghi",0)+$prob);
                debuglog("ha utilizzato l'alchimia per generare $prob scaglie di argento, spendendo 3 turni, 5000 punti carriera e 50000 punti mana");
            }
        }elseif ($_GET[og] == "oro") {
            if ($session['user']['turns']<3) {
                output("`\$Sei troppo stanco per tentare un incantesimo così faticoso.");
            }elseif ($mana<50000) {
                output("`\$Non c'è mana a sufficienza per questa magia.");
            }elseif ($session['user']['punti_carriera']<2000) {
                output("`\$Non hai abbastanza punti carriera per questa magia.");
            }else{
                output("`^Ti concentri e cominci a pronunciare delle formule arcane. L'incantesimo è lungo e consuma gran parte delle tue energie.`n`n");
                $mana -= 50000;
                $manaplayer -= 50000;
                savesetting("mana",$mana);
                $sqlmana = "UPDATE mana SET mana_player = '".$manaplayer."' WHERE acctid='{$idplayer}' AND data='{$data}'";
                db_query($sqlmana) or die(db_error(LINK));
                $prob=intval($prob/20);
                if ($prob>5) $prob=5;
                $session['user']['turns'] -= 3;
                $session['user']['punti_carriera']-=2000;
                switch ($prob){
                    case 0:
                        output("`#Qualcosa è andato storto, non sei riuscito a generare nemmeno una scaglia di oro!");
                    break;
                    case 1:
                        output("`#Hai generato una scaglia di oro.");
                    break;
                    default:
                        output("`#Hai generato $prob scaglie di oro.");
                    break;
                }
                savesetting("oromaghi",getsetting("oromaghi",0)+$prob);
                debuglog("ha utilizzato l'alchimia per generare $prob scaglie di oro, spendendo 3 turni, 5000 punti carriera e 50000 punti mana");
            }
        }
}

// Menù di Uscita
addnav("Exit");
if (($_GET['op']=="laboratorio" AND $_GET['op2'] != "") OR $_GET['op']=="prepara" OR $_GET['op']=="ingredienti") {
        addnav("L?Torna al Laboratorio","mago.php?op=laboratorio");
}
if ($_GET['op']!="iniziato" AND $_GET['op']!="") {
        addnav("E?Torna all'Entrata", "mago.php");
}
addnav("V?Torna al Villaggio","village.php");

if ($_GET['op']==""){
        output("Al momento Ithine ha questi materiali. Ricorda che il valore indicato è quello a cui Ithine vende i materiali, ma che li compra a prezzo minore.`n`n");
        // Inizio tabella Materiali
        if ($session['user']['superuser'] > 2) {
            output("<form action='mago.php?op=materiali' method='POST'>",true);
            addnav("","mago.php?op=materiali");
        }
        output("<table cellspacing=0 cellpadding=2 align='center'>", true);
        output("<tr class='trhead' align='center'><td>`bMateriale`b</td><td>`bQuantità`b</td><td>`bValore oro`b</td><td>Ops</td>", true);
        if ($session['user']['superuser'] > 2) output("<td>`iModifica quantità`i</td>",true);
        output("</tr>",true);
        // Zolfo
        output("<tr class='trlight'><td>Dosi di zolfo</td><td>$zolfo</td><td>$val_zolfo</td>",true);
        if ($session['user']['level'] < 15) {
                output("<td><A href=mago.php?op=comprazolfo>Compra </a>",true);
        } else {
                output("<td>",true);
        }
        if ($session['user']['level'] > 4) output("-<A href=mago.php?op=vendizolfo> Vendi</a>",true);
        if ($session['user']['superuser'] > 2) output("</td><td><input name='zolfo' value=\"".HTMLEntities2($zolfo)."\" size='5'>",true);
        output("</td></tr>", true);
        // Mandragola
        output("<tr class='trdark'><td>Foglie di mandragola</td><td>$mandragola</td><td>$val_mandragola</td>",true);
        if ($session['user']['level'] < 15) {
                output("<td><A href=mago.php?op=compramandragola>Compra </a>",true);
        } else {
                output("<td>",true);
        }
        if ($session['user']['level'] > 4) output("-<A href=mago.php?op=vendimandragola> Vendi</a>",true);
        if ($session['user']['superuser'] > 2) output("</td><td><input name='mandragola' value=\"".HTMLEntities2($mandragola)."\" size='5'>",true);
        output("</td></tr>", true);
        // Silice
        output("<tr class='trlight'><td>Silice</td><td>$silice</td><td>$val_silice</td>",true);
        if ($session['user']['level'] < 15) {
                output("<td><A href=mago.php?op=comprasilice>Compra </a>",true);
        } else {
                output("<td>",true);
        }
        if ($session['user']['level'] > 4) output("-<A href=mago.php?op=vendisilice> Vendi</a>",true);
        if ($session['user']['superuser'] > 2) output("</td><td><input name='silice' value=\"".HTMLEntities2($silice)."\" size='5'>",true);
        output("</td></tr>", true);
        // Argento
        output("<tr class='trdark'><td>Argento</td><td>$argento</td><td>$val_argento</td>",true);
        output("<td>",true);
        if ($session['user']['level'] < 15) {
                output("<A href=mago.php?op=compraargento>Compra </a>",true);
        }
        if ($session['user']['level'] > 4) output("-<A href=mago.php?op=vendiargento> Vendi</a>",true);
        if ($session['user']['superuser'] > 2) output("</td><td><input name='argento' value=\"".HTMLEntities2($argento)."\" size='5'>",true);
        output("</td></tr>", true);
        // Oro
        output("<tr class='trlight'><td>Oro</td><td>$oro</td><td>$val_oro</td>",true);
        output("<td>",true);
        if ($session['user']['level'] < 15) {
                output("<A href=mago.php?op=compraoro>Compra </a>",true);
        }
        if ($session['user']['level'] > 4) output("-<A href=mago.php?op=vendioro> Vendi</a>",true);
        if ($session['user']['superuser'] > 2) output("</td><td><input name='oro' value=\"".HTMLEntities2($oro)."\" size='5'>",true);
        output("</td></tr>", true);
        output("</table>", true);
        if ($session['user']['superuser'] > 2) output("`n`n<input type='submit' class='button' value='Salva'></form>",true);
        // Fine tabella Materiali
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
//editor quantità materiali
if ($_GET['op']=="materiali"){
    savesetting("zolfo",$_POST['zolfo']);
    savesetting("mandragola",$_POST['mandragola']);
    savesetting("silice",$_POST['silice']);
    savesetting("argentomaghi",$_POST['argento']);
    savesetting("oromaghi",$_POST['oro']);
    output("Le quantità dei materiali in vendita nella torre dei maghi sono state modificate");
}
//fine editor quantità materiali
// Diventa Iniziato
if($_GET['op']=="iniziato"){
        output("Ti avvicini ad Amon e gli chiedi :`#\"Vorrei apprendere le arti magiche, cosa devo fare per essere ammesso in questa gilda?\"`7.`n");
        output("Amon ti fissa negli occhi, incutendoti un po' di timore, e risponde :`&\"Le arti magiche sono pericolose e difficili da controllare. L'apprendimento e la ricerca sono estremamente costose,");
        output("e la prima cosa che devi fare per essere ammesso è consegnare 2 gemme, che verranno usate per la manutenzione del laboratorio, a cui anche tu avrai accesso.`n");
        output("Poi verrai considerato un iniziato nelle arti magiche.\"`7.`n`n");
        output("Poi interessato chiedi :`#\"E cosa ci guadagno a diventare iniziato?\"`7.`n");
        output("Amon risponde :`&\"Come iniziato, ti verranno insegnate le nozioni e le tecniche di base relative al mana, ed avrai accesso agli incantesimi più semplici.");
        output("Man mano che migliorerai la tua padronanza delle arti magiche, avrai accesso a nuovi incantesimi e nuove tecniche magiche, e salirai di grado diventando `3iniziato, `!stregone, `2mago`&; poi il migliore mago acquisirà il titolo di`$ Arcimago`&.");
        output("Osserverò continuamente i tuoi progressi, e da quando sarai `!stregone `&potrai anche collaborare alla rigenerazione degli oggetti magici.\" `7.`n`n");
}
if($_GET['op']=="diventainiziato"){
        if ($session['user']['gems']>=2) {
                output("Amon prende le due gemme e dice :`& \"Molto bene, da ora in poi sei a tutti gli effetti un iniziato, potrai dunque iniziare a studiare come si controlla il mana, fonte della nostra magia. Quando sarai pronto, ti sarà comunicata la promozione al grado di stregone.`n`n");
                output("Ma ti dico subito una cosa importante, anzi fondamentale: tutte le volte che si utilizza la magia viene consumato del mana. Il mana è accumulato in questa torre, ed è essenziale che non venga prosciugato, e che TUTTI, indipendentemente dal loro grado, si impegnino a generare il mana che usiamo.`n");
                output("Ricordati di questa regola, perchè l'arcimago sa sempre chi genera del mana e chi lo usa, e può decidere di punire chi non si impegna ad accrescere la nostra riserva di mana e vive sul lavoro degli altri.\"`7.`n");
                $session['user']['gems'] -= 2;
                debuglog("paga 2 gemme per intraprendere la carriera di mago");
                $session['user']['carriera'] = 41;
                if (getsetting("arcimago","0") != "0") {
                    systemmail(getsetting("arcimago","0"),"`VNuovo iniziato","`V".$session['user']['name']." `Vè diventato iniziato presso la Torre di Magia!");
                }

        }else{
                output("Amon, intuendo che non hai le 2 gemme, sospira, e dice:`& \"Se non possiedi le gemme, temo che stiamo entrambi perdendo tempo...\"`7. Non ti rendi conto del perchè, ma ti allontani da lui, che riprende a conversare con Ithine.`n");
        }
}
// Inizio Corsi Magia
if ($_GET['op'] == "corso" AND ($carriera > 40 AND $carriera < 45)) {
        output("Puoi seguire dei corsi per migliorare la tua conoscenza della magia. Ce ne sono per tutti i gusti e tutte le tasche.`n");
        output("Dal corso base a quello avanzato. Ognuno di essi ti farà guadagnare Punti Carriera. I corsi inferiori, ovviamente, forniranno minori ");
        output("conoscenze (e quindi punti carriera) rispetto ai corsi superiori.`n`");
        output("Che corso vuoi seguire?`n");
        addnav("Corsi di Magia");
        addnav("5?`^Base - 500 Oro", "mago.php?op=oro5");
        addnav("1?`^Intermedio - 1000 Oro", "mago.php?op=oro1");
        addnav("0?`^Avanzato - 5000 Oro", "mago.php?op=oro50");
        addnav("O?`^Superiore - 10000 Oro", "mago.php?op=oro10");
        addnav("`&Speciale - 1 Gemma", "mago.php?op=gemma");
}

if ($_GET['op'] == "corso" AND ($carriera < 5 OR $carriera > 8)) {
        output("Devi essere un Mago per poter accedere ai Corsi di Magia!!!`n`n");
}

if ($_GET['op'] == "oro5") {
        if ($session['user']['gold'] < 500) {
                output("Non hai abbastanza oro.`n");
        } else {
                output("Paghi felice i 500 Pezzi d'Oro per il Corso Base ... le tue conoscenze nelle Arti della Magia migliorano ");
                output("e ti senti più abile!`n");
                $session['user']['punti_carriera'] += (20 + $caso);
                $session['user']['punti_generati'] += (20 + $caso);
                $fama = (20 + $caso)*$session[user][fama_mod];
                $session['user']['fama3mesi'] += $fama;
                debuglog("Guadagna $fama punti fama e ".(20+$caso)." con il corso Base di magia. Ora ha ".$session['user']['fama3mesi']." punti fama e ".$session['user']['punti_carriera']." punti carriera");
                $session['user']['gold'] -= 500;
        }
}

if ($_GET['op'] == "oro1") {
        if ($session['user']['gold'] < 1000) {
                output("Non hai abbastanza oro.`n");
        } else {
                output("Paghi felice i 1.000 Pezzi d'Oro per il Corso Intermedio ... le tue conoscenze nelle Arti della Magia migliorano ");
                output("e ti senti più abile!`n");
                $session['user']['punti_carriera'] += (42 + $caso);
                $session['user']['punti_generati'] += (42 + $caso);
                $fama = (42 + $caso)*$session[user][fama_mod];
                $session['user']['fama3mesi'] += $fama;
                debuglog("Guadagna $fama punti fama e ".(42+$caso)." con il corso Intermedio di magia. Ora ha ".$session['user']['fama3mesi']." punti fama e ".$session['user']['punti_carriera']." punti carriera");
                $session['user']['gold'] -= 1000;
        }
}

if ($_GET['op'] == "oro50") {
        if ($session['user']['gold'] < 5000) {
                output("Non hai abbastanza oro.`n");
        } else {
                output("Paghi felice i 5.000 Pezzi d'Oro per il Corso Avanzato ... le tue conoscenze nelle Arti della Magia migliorano ");
                output("e ti senti decisamente più abile!`n");
                $session['user']['punti_carriera'] += (215 + $caso);
                $session['user']['punti_generati'] += (215 + $caso);
                $fama = (215 + $caso)*$session[user][fama_mod];
                $session['user']['fama3mesi'] += $fama;
                debuglog("Guadagna $fama punti fama e ".(215+$caso)." con il corso Avanzato di magia. Ora ha ".$session['user']['fama3mesi']." punti fama e ".$session['user']['punti_carriera']." punti carriera");
                $session['user']['gold'] -= 5000;
        }
}

if ($_GET['op'] == "oro10") {
        if ($session['user']['gold'] < 10000) {
                output("Non hai abbastanza oro.`n");
        } else {
                output("Paghi felice i 10.000 Pezzi d'Oro per il Corso Superiore ... le tue conoscenze nelle Arti della Magia migliorano ");
                output("e ti senti nettamente più abile!`n");
                $session['user']['punti_carriera'] += (440 + $caso);
                $session['user']['punti_generati'] += (440 + $caso);
                $fama = (440 + $caso)*$session[user][fama_mod];
                $session['user']['fama3mesi'] += $fama;
                debuglog("Guadagna $fama punti fama e ".(440+$caso)." con il corso Superiore di magia. Ora ha ".$session['user']['fama3mesi']." punti fama e ".$session['user']['punti_carriera']." punti carriera");
                $session['user']['gold'] -= 10000;
        }
}

if ($_GET['op'] == "gemma") {
        if ($session['user']['gems'] < 1) {
                output("`4Non possiedi nessuna gemma.`n");
        } else {
                output("`#Paghi felice la gemma per il Corso Speciale ... le tue conoscenze nelle Arti della Magia migliorano ");
                output("e ti senti più abile!`n");
                $session['user']['punti_carriera'] += (100 + $caso);
                $session['user']['punti_generati'] += (100 + $caso);
                $fama = (100 + $caso)*$session[user][fama_mod];
                $session['user']['fama3mesi'] += $fama;
                debuglog("Guadagna $fama punti fama e ".(100+$caso)." con il corso Speciale di magia. Ora ha ".$session['user']['fama3mesi']." punti fama e ".$session['user']['punti_carriera']." punti carriera");
                $session['user']['gems'] -= 1;
                if ($cento > 89) {
                        $buff = array("name" => "`\$Aura magica", "rounds" => 15, "wearoff" => "`!La tua aura si è dissolta", "defmod" => 1.6, "roundmsg" => "Un'aura magica ti circonda e ti protegge dal nemico!", "activate" => "defense");
                        $session['bufflist']['fabbro'] = $buff;
                        output("`%Un leggera aura luminosa ti circonda. Percepisci che la tua difesa è migliorata!`n");
                }
        }
}
// Inizio Esercizi
if($_GET['op']=="controllo"){
        if ($session['user']['turns']>0) {
                output("Amon ed Ithine ti spiegano come si genera il mana, come lo si focalizza e come si attinge ad esso per utilizzarlo. Dopodiché");
                $eserc=e_rand(1, 3);
                switch ($eserc) {
                        case 1:
                        output("ti consegnano un'asta di legno e ti chiedono di farla levitare utilizzando il mana.");
                        break;
                        case 2:
                        output("ti chiedono di provare ad utilizzare il mana per creare della luce.");
                        break;
                        case 3:
                        output("ti interrogano sulle tecniche per generare il mana.");
                        break;
                }
                output("L'esercitazione prosegue così per circa un'ora, prima di giungere al termine.");
                if ($caso == 4) {
                        output("`nAmon ti dice: `&\"Bravo, vedo che te la cavi ottimamente!\"`7, mentre sembra accennare un sorriso.");
                        $session['user']['punti_carriera']+=(2+$caso);
                        $session['user']['punti_generati']+=(2+$caso);
                        $fama = (2 + $caso)*$session[user][fama_mod];
                        $session['user']['fama3mesi'] += $fama;
                        debuglog("Guadagna $fama punti fama e ".(2+$caso)." punti carriera esercitandosi nel Controllo del Mana. Ora ha ".$session['user']['fama3mesi']." punti fama e ".$session['user']['punti_carriera']." punti carriera");
                        $session['user']['experience']+=($session['user']['level']*4);
                        $caso_gold +=50;
                        $mana += (2+$caso);
                        $manaplayer += (2+$caso);
                        savesetting("mana",$mana);
                        $sqlmana = "UPDATE mana SET mana_player = '".$manaplayer."' WHERE acctid='{$idplayer}' AND data='{$data}'";
                        db_query($sqlmana) or die(db_error(LINK));
                        if ($caso2 == 4) {
                                output("Ed Ithine aggiunge :`#\"E' stata un'esercitazione molto proficua, abbiamo anche generato molto mana.\" `7`n");
                                $mana +=10;
                                $manaplayer += 10;
                                savesetting("mana",$mana);
                                $sqlmana = "UPDATE mana SET mana_player = '".$manaplayer."' WHERE acctid='{$idplayer}' AND data='{$data}'";
                                db_query($sqlmana) or die(db_error(LINK));
                                debuglog("ha generato ".(12+$caso)." punti mana");
                        } else {
                                debuglog("ha generato ".(2+$caso)." punti mana");
                        }
                }else {
                        if ($caso == 3){
                                output("`nAmon ti osserva negli occhi, e ti dice apaticamente: `&\"Direi che non c'è male.\"`7.`n");
                                $session['user']['punti_carriera']+=(1+$caso);
                                $session['user']['punti_generati']+=(1+$caso);
                                $fama = (1 + $caso)*$session[user][fama_mod];
                                $session['user']['fama3mesi'] += $fama;
                                debuglog("Guadagna $fama punti fama e ".(1+$caso)." punti carriera esercitandosi nel Controllo del Mana. Ora ha ".$session['user']['fama3mesi']." punti fama e ".$session['user']['punti_carriera']." punti carriera");
                                $session['user']['experience']+=($session['user']['level']*4);
                                $mana += (1+$caso);
                                $manaplayer += (1+$caso);
                                savesetting("mana",$mana);
                                $sqlmana = "UPDATE mana SET mana_player = '".$manaplayer."' WHERE acctid='{$idplayer}' AND data='{$data}'";
                                db_query($sqlmana) or die(db_error(LINK));
                                debuglog("ha generato ".(1+$caso)." punti mana");
                        }else{
                                output("`nAmon scuote la testa, e ti dice apaticamente: `&\"Non ci siamo, devi impegnarti di più.\"`7.`n");
                                $session['user']['punti_carriera']+=($caso);
                                $session['user']['punti_generati']+=($caso);
                                $fama = ($caso)*$session[user][fama_mod];
                                $session['user']['fama3mesi'] += $fama;
                                debuglog("Guadagna $fama punti fama e ".($caso)." punti carriera esercitandosi nel Controllo del Mana. Ora ha ".$session['user']['fama3mesi']." punti fama e ".$session['user']['punti_carriera']." punti carriera");
                                $session['user']['experience']+=($session['user']['level']*4);
                                $mana += ($caso);
                                $manaplayer += ($caso);
                                savesetting("mana",$mana);
                                $sqlmana = "UPDATE mana SET mana_player = '".$manaplayer."' WHERE acctid='{$idplayer}' AND data='{$data}'";
                                db_query($sqlmana) or die(db_error(LINK));
                                debuglog("ha generato ".$caso." punti mana");
                        }
                }
                $session['user'][turns]-=1;
        }else{
                output("Sei troppo esausto per esercitarti.`n");
        }
}

if($_GET['op']=="divinazione"){
        if ($session['user']['turns']>1) {
                output("Amon ed Ithine ti spiegano come il mana interagisce sulla mente, e come lo si può utilizzare per superare i limiti della percezione sensoriale. Dopodiché");
                $eserc=e_rand(1, 3);
                switch ($eserc) {
                        case 1:
                        output("ti consegnano una sfera di cristallo e ti chiedono di descrivere cosa sta succedendo nella locanda in questo momento.");
                        break;
                        case 2:
                        output("ti chiedono di leggere nella loro mente e di dirgli le cose a cui stanno pensando.");
                        break;
                        case 3:
                        output("ti interrogano sulle tecniche di divinazione.");
                        break;
                }
                output("L'esercitazione prosegue incessantemente per circa due ore, prima di giungere al termine.");
                if ($caso == 4) {
                        output("`nLo sguardo di Amon sembra per un istante radioso, mentre ti dice: `&\"Interessante, sembra che tu abbia del talento!.\"`7.`n");
                        $session['user']['punti_carriera']+=(4+(4*$caso));
                        $session['user']['punti_generati']+=(4+(4*$caso));
                        $fama = (4+(4*$caso))*$session[user][fama_mod];
                        $session['user']['fama3mesi'] += $fama;
                        debuglog("Guadagna $fama punti fama e ".(4+(4*$caso))." punti carriera esercitandosi nella Divinazione. Ora ha ".$session['user']['fama3mesi']." punti fama e ".$session['user']['punti_carriera']." punti carriera");
                        $session['user']['experience']+=($session['user']['level']*10);
                        $mana += (4+(4*$caso));
                        $manaplayer += (4+(4*$caso));
                        savesetting("mana",$mana);
                        $sqlmana = "UPDATE mana SET mana_player = '".$manaplayer."' WHERE acctid='{$idplayer}' AND data='{$data}'";
                        db_query($sqlmana) or die(db_error(LINK));
                        if ($caso2 == 4) {
                                output("Ed Ithine aggiunge :`#\"E' stata un'esercitazione molto proficua, abbiamo anche generato molto mana.\" `7`n");
                                $mana += 20;
                                $manaplayer += 20;
                                savesetting("mana",$mana);
                                $sqlmana = "UPDATE mana SET mana_player = '".$manaplayer."' WHERE acctid='{$idplayer}' AND data='{$data}'";
                                db_query($sqlmana) or die(db_error(LINK));
                                debuglog("ha generato ".(24+(4*$caso))." punti mana");
                        } else {
                                debuglog("ha generato ".(4+(4*$caso))." punti mana");
                        }
                }else {
                        if ($caso == 3){
                                output("`nAmon ti osserva negli occhi, e ti dice apaticamente: `&\"Mi sembra buono.\"`7.`n");
                                $session['user']['punti_carriera']+=(2+(3*$caso));
                                $session['user']['punti_generati']+=(2+(3*$caso));
                                $fama = (2+(3*$caso))*$session[user][fama_mod];
                                $session['user']['fama3mesi'] += $fama;
                                debuglog("Guadagna $fama punti fama e ".(2+(3*$caso))." punti carriera esercitandosi nella Divinazione. Ora ha ".$session['user']['fama3mesi']." punti fama e ".$session['user']['punti_carriera']." punti carriera");
                                $session['user']['experience']+=($session['user']['level']*8);
                                $mana += (2+(3*$caso));
                                $manaplayer += (2+(3*$caso));
                                savesetting("mana",$mana);
                                $sqlmana = "UPDATE mana SET mana_player = '".$manaplayer."' WHERE acctid='{$idplayer}' AND data='{$data}'";
                                db_query($sqlmana) or die(db_error(LINK));
                                debuglog("ha generato ".(2+(3*$caso))." punti mana");
                        }else{
                                output("`nAmon scuote la testa, e ti dice apaticamente: `&\"Non ci siamo, voglio vederti fare molto di più.\"`7.`n");
                                $session['user']['punti_carriera']+=(1+(2*$caso));
                                $session['user']['punti_generati']+=(1+(2*$caso));
                                $fama = (1+(2*$caso))*$session[user][fama_mod];
                                $session['user']['fama3mesi'] += $fama;
                                debuglog("Guadagna $fama punti fama e ".(1+(2*$caso))." punti carriera esercitandosi nella Divinazione. Ora ha ".$session['user']['fama3mesi']." punti fama e ".$session['user']['punti_carriera']." punti carriera");
                                $session['user']['experience']+=($session['user']['level']*6);
                                $mana += (1+(2*$caso));
                                $manaplayer += (1+(2*$caso));
                                savesetting("mana",$mana);
                                $sqlmana = "UPDATE mana SET mana_player = '".$manaplayer."' WHERE acctid='{$idplayer}' AND data='{$data}'";
                                db_query($sqlmana) or die(db_error(LINK));
                                debuglog("ha generato ".(1+(2*$caso))." punti mana");
                        }
                }
                $session['user'][turns]-=2;
        }else{
                output("Sei troppo esausto per esercitarti.`n");
        }
}

if($_GET['op']=="evocazione"){
        if ($session['user'][turns]>=3) {
                output("Amon ed Ithine ti spiegano come funziona l'evocazione di materia, energia ed esseri senzienti. Ti spiegano anche quali sono i pericoli correlati, e come fronteggiarli. Dopodiché");
                $eserc=e_rand(1, 3);
                switch ($eserc) {
                        case 1:
                        output("spargono della sabbia sul pavimento, e ti chiedono di vetrificarla evocando del fuoco.");
                        break;
                        case 2:
                        output("ti chiedono di evocare un imp, e di mantenerne il controllo. Loro si tengono pronti nel caso dovesse liberarsi.");
                        break;
                        case 3:
                        output("ti interrogano sui pericoli e sulle creature che si possono evocare.");
                        break;
                }
                output("L'esercitazione prosegue per non più di mezz'ora, prima di giungere al termine, ma è stata molto impegnativa.");
                if ($caso == 4) {
                        output("`nAmon annuisce vistosamente, poi ti dice: `&\"Magnifico, degno di un mago come si deve!\"`7.`n");
                        $session['user']['punti_carriera']+=(4+(8*$caso));
                        $session['user']['punti_generati']+=(4+(8*$caso));
                        $fama = (4+(8*$caso))*$session[user][fama_mod];
                        $session['user']['fama3mesi'] += $fama;
                        debuglog("Guadagna $fama punti fama e ".(4+(8*$caso))." punti carriera esercitandosi nell'Evocazione. Ora ha ".$session['user']['fama3mesi']." punti fama e ".$session['user']['punti_carriera']." punti carriera");
                        $session['user']['experience']+=($session['user']['level']*15);
                        $mana += (4+(8*$caso));
                        $manaplayer += (4+(8*$caso));
                        savesetting("mana",$mana);
                        $sqlmana = "UPDATE mana SET mana_player = '".$manaplayer."' WHERE acctid='{$idplayer}' AND data='{$data}'";
                        db_query($sqlmana) or die(db_error(LINK));
                        if ($caso2 == 4) {
                                output("Ed Ithine aggiunge :`#\"E' stata un'esercitazione molto proficua, abbiamo anche generato molto mana.\" `7`n");
                                $mana += 40;
                                $manaplayer += 40;
                                savesetting("mana",$mana);
                                $sqlmana = "UPDATE mana SET mana_player = '".$manaplayer."' WHERE acctid='{$idplayer}' AND data='{$data}'";
                                db_query($sqlmana) or die(db_error(LINK));
                                debuglog("ha generato ".(44+(8*$caso))." punti mana");
                        } else {
                                debuglog("ha generato ".(4+(8*$caso))." punti mana");
                        }
                }else {
                        if ($caso == 3){
                                output("`nAmon ti osserva negli occhi, e ti dice apaticamente: `&\"Un buon lavoro.\"`7.`n");
                                $session['user']['punti_carriera']+=(2+(6*$caso));
                                $session['user']['punti_generati']+=(2+(6*$caso));
                                $fama = (2+(6*$caso))*$session[user][fama_mod];
                                $session['user']['fama3mesi'] += $fama;
                                debuglog("Guadagna $fama punti fama e ".(2+(6*$caso))." punti carriera esercitandosi nell'Evocazione. Ora ha ".$session['user']['fama3mesi']." punti fama e ".$session['user']['punti_carriera']." punti carriera");
                                $session['user']['experience']+=($session['user']['level']*13);
                                $mana += (2+(6*$caso));
                                $manaplayer += (2+(6*$caso));
                                savesetting("mana",$mana);
                                $sqlmana = "UPDATE mana SET mana_player = '".$manaplayer."' WHERE acctid='{$idplayer}' AND data='{$data}'";
                                db_query($sqlmana) or die(db_error(LINK));
                                debuglog("ha generato ".(2+(6*$caso))." punti mana");
                        }else{
                                output("`nAmon scuote la testa, e ti dice apaticamente: `&\"E tu saresti un mago? Anche le esercitazioni vanno prese seriamente.\"`7.`n");
                                $session['user']['punti_carriera']+=(1+(4*$caso));
                                $session['user']['punti_generati']+=(1+(4*$caso));
                                $fama = (1+(4*$caso))*$session[user][fama_mod];
                                $session['user']['fama3mesi'] += $fama;
                                debuglog("Guadagna $fama punti fama e ".(1+(4*$caso))." punti carriera esercitandosi nell'Evocazione. Ora ha ".$session['user']['fama3mesi']." punti fama e ".$session['user']['punti_carriera']." punti carriera");
                                $session['user']['experience']+=($session['user']['level']*12);
                                $mana += (1+(4*$caso));
                                $manaplayer += (1+(4*$caso));
                                savesetting("mana",$mana);
                                $sqlmana = "UPDATE mana SET mana_player = '".$manaplayer."' WHERE acctid='{$idplayer}' AND data='{$data}'";
                                db_query($sqlmana) or die(db_error(LINK));
                                debuglog("ha generato ".(1+(4*$caso))." punti mana");
                        }
                }
                $session['user']['turns']-=3;
        }else{
                output("Sei troppo esausto per esercitarti.`n");
        }
}

if($_GET['op']=="alchimiaeserc"){
        if ($session['user']['turns']>4 AND $session['user']['playerfights']>0) {
                output("Amon ti consegna un libro in pelle molto ben custodito, dicendoti: `&\"Questo antico libro ritrovato nelle Terre dei Draghi racchiude le conoscenze dell'alchimia. Tuttavia, quasi tutto il suo contenuto ci è risultato sconosciuto ed incomprensible. Ma forse tu, l'ARCIMAGO, riuscirai ad ottenere qualche risultato...\"`7.`n");
                if ($caso == 3) {
                        output("Passi molto tempo su quel libro. Amon ha ragione, il libro è praticamente incomprensibile. Ma anche per quel poco che sei riuscito a capire, le tue capacità magiche sono migliorate.`n`n");
                        $session['user']['punti_carriera']+=(5+(10*$caso));
                        $session['user']['punti_generati']+=(5+(10*$caso));
                        $fama = (5+(10*$caso))*$session[user][fama_mod];
                        $session['user']['fama3mesi'] += $fama;
                        debuglog("Guadagna $fama punti fama e ".(5+(10*$caso))." punti carriera esercitandosi nell'Alchimia. Ora ha ".$session['user']['fama3mesi']." punti fama e ".$session['user']['punti_carriera']." punti carriera");
                        $session['user']['experience']+=($session['user']['level']*25);
                        $mana += (5+(10*$caso));
                        $manaplayer += (5+(10*$caso));
                        savesetting("mana",$mana);
                        $sqlmana = "UPDATE mana SET mana_player = '".$manaplayer."' WHERE acctid='{$idplayer}' AND data='{$data}'";
                        db_query($sqlmana) or die(db_error(LINK));
                        debuglog("ha generato ".(5+(10*$caso))." punti mana");
                        if ($caso2 == 2) {
                                output("E sei anche riuscito a creare 1 gemma dal nulla.`n`n");
                                $session['user']['gems']+= 1;
                                debuglog("riesce a creare 1 gemma con l'alchimia");
                        }
                }else{
                        output("Passi molto tempo su quel libro. Amon ha ragione, il libro è incomprensibile, ti accorgi che, nonostante tu sia il miglior mago di Rafflingate, non sai praticamente nulla della magia. Eppure senti che, in qualche modo, hai comunque migliorato le tue capacità magiche.`n`n");
                        $session['user']['punti_carriera']+=(5+(5*$caso));
                        $session['user']['punti_generati']+=(5+(5*$caso));
                        $fama = (5+(5*$caso))*$session[user][fama_mod];
                        $session['user']['fama3mesi'] += $fama;
                        debuglog("Guadagna $fama punti fama e ".(5+(5*$caso))." punti carriera esercitandosi nell'Alchimia. Ora ha ".$session['user']['fama3mesi']." punti fama e ".$session['user']['punti_carriera']." punti carriera");
                        $session['user']['experience']+=($session['user']['level']*20);
                        $mana += (5+(5*$caso));
                        $manaplayer += (5+(5*$caso));
                        savesetting("mana",$mana);
                        $sqlmana = "UPDATE mana SET mana_player = '".$manaplayer."' WHERE acctid='{$idplayer}' AND data='{$data}'";
                        db_query($sqlmana) or die(db_error(LINK));
                        debuglog("ha generato ".(5+(10*$caso))." punti mana");
                }
                output("Restituisci il libro ad Amon.");
                $session['user'][turns]-=5;
                $session['user'][playerfights]-=1;
        }else{
                output("Sei troppo esausto per esercitarti.`n");
        }
}
// Inizio Info Carriera
if($_GET['op']=="chiedi"){
        output("Ti avvicini a Amon, ed anche se un po' intimorito gli dici:`n`#\"Maestro, come sto andando? Occorrerà molto per essere promosso ad un nuovo grado?\"`7.`n");
        if ($carriera == 41 ) {
                $voto = intval($session['user']['punti_carriera']/50);
                output("Amon risponde stizzito:`& \"La dote più importante per un mago è la PAZIENZA, ed in questo momento non ne stai certo dando prova. Comunque sei un `\$Iniziato `&e la tua padronanza della magia è pari a `$ $voto `&su 100 \"`7.`n Ti rendi conto che non è stata una buona idea.");
                $session['user']['punti_carriera']-=1;
        }
        if ($carriera == 42 ) {
                $voto = intval($session['user']['punti_carriera']/200);
                output("Amon risponde stizzito:`& \"La dote più importante per un mago è la PAZIENZA, ed in questo momento non ne stai certo dando prova. Comunque sei uno `\$Stregone `&e la tua padronanza della magia è pari a `$ $voto `&su 100 \"`7.`n Ti rendi conto che non è stata una buona idea.");
                $session['user']['punti_carriera']-=1;
        }
        if ($carriera == 43 OR $carriera == 44) {
                if ($carriera == 44) {
                        output("Amon ti osserva perplesso, poi risponde:`& \"Sei l'`\$Arcimago `&della nostra gilda, hai più potere di me. Non capisco il senso di questa tua domanda...\"`7.`n Poi sospirando ritorna alle sue faccende.");
                        $session['user']['punti_carriera']-=10;
                }else{
                        $voto = intval($session['user']['punti_carriera']/200);
                        if ($voto >= 100) {
                                $voto = 100;
                        }
                        output("Amon risponde stizzito:`& \"La dote più importante per un mago è la PAZIENZA, ed in questo momento non ne stai certo dando prova. Comunque sei un `\$Mago `&e la tua padronanza della magia è pari a `$ $voto `&su 100 \"`7.`n Ti rendi conto che non è stata una buona idea.");
                        $session['user']['punti_carriera']-=1;
                }
        }

}
// Inizio Compra/Vendita dei Materiali
// Silice
if($_GET['op']=="comprasilice"){
        if ($session['user']['gold']>=$val_silice){
                if ($silice >= 1) {
                        if (zainoPieno ($idplayer)) {
                                output("Purtroppo non hai più spazio nello zaino.`n");
                        } else {
                                output("Ithine afferra i tuoi ".$val_silice." pezzi d'oro e ti dà 1 dose di silice in cambio.`n`n");
                                $session['user']['gold']-=$val_silice;
                                $sqli="INSERT INTO zaino (idoggetto, idplayer) VALUES (20, '{$idplayer}')";
                                db_query($sqli) or die(db_error(LINK));
                                debuglog("compra 1 dose di silice da Ithine");
                                if (getsetting("silice",0) - 1 < 1) {
                                        savesetting("silice","0");
                                } else {
                                        savesetting("silice",getsetting("silice",0)-1);
                                }
                        }
                } else {
                        output("Purtroppo Ithine ha già venduto tutta la silice che aveva, sei arrivato troppo tardi.`n");
                        output("La silice è abbastanza comune, non dovrebbe tardare ad arrivare qualche avventuriero con della silice.`n`n");
                }
        }else{
                output("Ithine ti guarda seccata, mentre un fulmine si scaglia dalla sua mano a pochi centimetri dai tuoi piedi, e dice: \"Bada di non fare il furbo!\".`n`n");
        }
}

if($_GET['op']=="vendisilice"){
        $sql = "SELECT * FROM zaino WHERE idoggetto = 20 AND idplayer='{$idplayer}'";
        $result = db_query($sql) or die(db_error(LINK));
        if (db_num_rows($result)<1){
                output("Ithine ti guarda seccata, mentre un fulmine si scaglia dalla sua mano a pochi centimetri dai tuoi piedi, e dice: \"Bada di non fare il furbo!\".`n`n");
        }else{
                output("Ithine prende la tua silice e ti posa in mano ".($val_silice/2)." pezzi d'oro in cambio.`n`n");
                $session['user']['gold']+=($val_silice/2);
                $row = db_fetch_assoc($result);
                $sqld = "DELETE FROM zaino WHERE id = '{$row['id']}'";
                db_query($sqld);
                debuglog("vende 1 dose di silice ad Ithine");
                savesetting("silice",getsetting("silice",0)+1);
        }
}
// Mandragola
if($_GET['op']=="compramandragola"){
        if ($session['user']['gold']>=$val_mandragola){
                if ($mandragola >= 1) {
                        if (zainoPieno ($idplayer)) {
                                output("Purtroppo non hai più spazio nello zaino.`n");
                        } else {
                                output("Ithine afferra i tuoi ".$val_mandragola." pezzi d'oro e ti dà 1 foglia di mandragola in cambio.`n`n");
                                $session['user']['gold']-=$val_mandragola;
                                $sqli="INSERT INTO zaino (idoggetto, idplayer) VALUES (22, '{$idplayer}')";
                                db_query($sqli) or die(db_error(LINK));
                                debuglog("compra 1 foglia di mandragola da Ithine");
                                if (getsetting("mandragola",0) - 1 < 1) {
                                        savesetting("mandragola","0");
                                } else {
                                        savesetting("mandragola",getsetting("mandragola",0)-1);
                                }
                        }
                } else {
                        output("Purtroppo Ithine ha già venduto tutte le foglie di mandragola che aveva, sei arrivato troppo tardi.`n");
                        output("La mandragola è abbastanza comune, non dovrebbe tardare ad arrivare qualche avventuriero con delle foglie di mandragola.`n`n");
                }
        }else{
                output("Ithine ti guarda seccata, mentre un fulmine si scaglia dalla sua mano a pochi centimetri dai tuoi piedi, e dice: \"Bada di non fare il furbo!\".`n`n");
        }
}

if($_GET['op']=="vendimandragola"){
        $sql = "SELECT * FROM zaino WHERE idoggetto = 22 AND idplayer='{$idplayer}'";
        $result = db_query($sql) or die(db_error(LINK));
        if (db_num_rows($result)<1){
                output("Ithine ti guarda seccata, mentre un fulmine si scaglia dalla sua mano a pochi centimetri dai tuoi piedi, e dice: \"Bada di non fare il furbo!\".`n`n");
        }else{
                output("Ithine prende la tua foglia di mandragola e ti posa in mano ".($val_mandragola/2)." pezzi d'oro in cambio.`n`n");
                $session['user']['gold']+=($val_mandragola/2);
                $row = db_fetch_assoc($result);
                debuglog("vende 1 foglia di mandragola ad Ithine");
                $sqld = "DELETE FROM zaino WHERE id = '{$row['id']}'";
                db_query($sqld);
                savesetting("mandragola",getsetting("mandragola",0)+1);
        }
}
//Zolfo
if($_GET['op']=="comprazolfo"){
        if ($session['user']['gold']>=$val_zolfo){
                if ($zolfo >= 1) {
                        if (zainoPieno ($idplayer)) {
                                output("Purtroppo non hai più spazio nello zaino.`n");
                        } else {
                                output("Ithine afferra i tuoi ".$val_zolfo." pezzi d'oro e ti dà 1 dose di zolfo in cambio.`n`n");
                                $session['user']['gold']-=$val_zolfo;
                                $sqli="INSERT INTO zaino (idoggetto, idplayer) VALUES (21, '{$idplayer}')";
                                db_query($sqli) or die(db_error(LINK));
                                debuglog("compra 1 dose di zolfo da Ithine");
                                if (getsetting("zolfo",0) - 1 < 1) {
                                        savesetting("zolfo","0");
                                } else {
                                        savesetting("zolfo",getsetting("zolfo",0)-1);
                                }
                        }
                } else {
                        output("Purtroppo Ithine ha già venduto tutto lo zolfo che aveva, sei arrivato troppo tardi.`n");
                        output("Lo zolfo è abbastanza comune, non dovrebbe tardare ad arrivare qualche avventuriero con qualche sode di zolfo.`n`n");
                }
        }else{
                output("Ithine ti guarda seccata, mentre un fulmine si scaglia dalla sua mano a pochi centimetri dai tuoi piedi, e dice: \"Bada di non fare il furbo!\".`n`n");
        }
}

if($_GET['op']=="vendizolfo"){
        $sql = "SELECT * FROM zaino WHERE idoggetto = 21 AND idplayer='{$idplayer}'";
        $result = db_query($sql) or die(db_error(LINK));
        if (db_num_rows($result)<1){
                output("Ithine ti guarda seccata, mentre un fulmine si scaglia dalla sua mano a pochi centimetri dai tuoi piedi, e dice: \"Bada di non fare il furbo!\".`n`n");
        }else{
                output("Ithine prende la tua dose di zolfo e ti posa in mano ".($val_zolfo/2)." pezzi d'oro in cambio.`n`n");
                $session['user']['gold']+=($val_zolfo/2);
                $row = db_fetch_assoc($result);
                $sqld = "DELETE FROM zaino WHERE id = '{$row['id']}'";
                db_query($sqld);
                debuglog("vende 1 dose di zolfo ad Ithine");
                savesetting("zolfo",getsetting("zolfo",0)+1);
        }
}
// Argento
if($_GET['op']=="compraargento"){
        if ($session['user']['gold']>=$val_argento){
                if ($argento >= 1) {
                        if (zainoPieno ($idplayer)) {
                                output("Purtroppo non hai più spazio nello zaino.`n");
                        } else {
                                output("Ithine afferra i tuoi ".$val_argento." pezzi d'oro e ti dà 1 scaglia di argento in cambio.`n`n");
                                $session['user']['gold']-=$val_argento;
                                $sqli="INSERT INTO zaino (idoggetto, idplayer) VALUES (5, '{$session['user']['acctid']}')";
                                db_query($sqli) or die(db_error(LINK));
                                debuglog("compra 1 scaglia d'argento da Ithine");
                                if (getsetting("argentomaghi",0) - 1 < 1) {
                                        savesetting("argentomaghi","0");
                                } else {
                                        savesetting("argentomaghi",getsetting("argentomaghi",0)-1);
                                }
                        }
                } else {
                        output("Purtroppo Ithine ha già venduto tutte le scaglie d'argento che aveva, sei arrivato troppo tardi.`n");
                        output("L'argento è abbastanza raro non arriverà prima di qualche giorno!.`n`n");
                }
        }else{
                output("Ithine ti guarda seccata, mentre un fulmine si scaglia dalla sua mano a pochi centimetri dai tuoi piedi, e dice: \"Bada di non fare il furbo!\".`n`n");
        }
}

if($_GET['op']=="vendiargento"){
        $sql = "SELECT * FROM zaino WHERE idoggetto = 5 AND idplayer='{$session['user']['acctid']}'";
        $result = db_query($sql) or die(db_error(LINK));
        if (db_num_rows($result)<1){
                output("Ithine ti guarda seccata, mentre un fulmine si scaglia dalla sua mano a pochi centimetri dai tuoi piedi, e dice: \"Bada di non fare il furbo!\".`n`n");
        }else{
                output("Ithine prende le tue scaglie d'argento e ti posa in mano ".($val_argento/2)." pezzi d'oro in cambio.`n`n");
                $session['user']['gold']+=($val_argento/2);
                $row = db_fetch_assoc($result);
                $sqld = "DELETE FROM zaino WHERE id = '{$row['id']}'";
                db_query($sqld);
                debuglog("vende 1 scaglia d'argento ad Ithine");
                savesetting("argentomaghi",getsetting("argentomaghi",0)+1);
        }
}
// Oro
if($_GET['op']=="compraoro"){
        if ($session['user']['gold']>=$val_oro){
                if ($oro >= 1) {
                        if (zainoPieno ($idplayer)) {
                                output("Purtroppo non hai più spazio nello zaino.`n");
                        } else {
                                output("Ithine afferra i tuoi ".$val_oro." pezzi d'oro e ti dà 1 scaglia di oro in cambio.`n`n");
                                $session['user']['gold']-=$val_oro;
                                $sqli="INSERT INTO zaino (idoggetto, idplayer) VALUES (7, '{$session['user']['acctid']}')";
                                db_query($sqli) or die(db_error(LINK));
                                debuglog("compra 1 scaglia d'oro da Ithine");
                                if (getsetting("oromaghi",0) - 1 < 1) {
                                        savesetting("oromaghi","0");
                                } else {
                                        savesetting("oromaghi",getsetting("oromaghi",0)-1);
                                }
                        }
                } else {
                        output("Purtroppo Ithine ha già venduto tutte le scaglie d'oro che aveva, sei arrivato troppo tardi.`n");
                        output("L'oro è molto raro non arriverà prima di qualche settimana!.`n`n");
                }
        }else{
                output("Ithine ti guarda seccata, mentre un fulmine si scaglia dalla sua mano a pochi centimetri dai tuoi piedi, e dice: \"Bada di non fare il furbo!\".`n`n");
        }
}

if($_GET['op']=="vendioro"){
        $sql = "SELECT * FROM zaino WHERE idoggetto = 7 AND idplayer='{$session['user']['acctid']}'";
        $result = db_query($sql) or die(db_error(LINK));
        if (db_num_rows($result)<1){
                output("Ithine ti guarda seccata, mentre un fulmine si scaglia dalla sua mano a pochi centimetri dai tuoi piedi, e dice: \"Bada di non fare il furbo!\".`n`n");
        }else{
                output("Ithine prende le tue scaglie d'oro e ti posa in mano ".($val_oro/2)." pezzi d'oro in cambio.`n`n");
                $session['user']['gold']+=($val_oro/2);
                $row = db_fetch_assoc($result);
                $sqld = "DELETE FROM zaino WHERE id = '{$row['id']}'";
                db_query($sqld);
                debuglog("vende 1 scaglia d'oro ad Ithine");
                savesetting("oromaghi",getsetting("oromaghi",0)+1);
        }
}

// Sala Riunioni
if ($_GET['op'] == "sala") {
        output("Entri nella sala delle riunioni dei maghi.`n`n");
        $am = getsetting("arcimago",0);
        $sqlam = " SELECT `name` FROM `accounts` WHERE `acctid` = '$am'";
        $resultam = db_query($sqlam) or die(db_error(LINK));
        $rowam = db_fetch_assoc($resultam);
        if ($am == 0) {
                output("`6Nessuno raggiunge i requisiti richiesti da `&Amon `6per occupare la carica di `#Arcimago`6.`n`n");
        } else {
                output("L'attuale Arcimago è : {$rowam['name']}`n`n");
        }
    //Sook, modifica per annuncio dell'arcimago
    $sql="SELECT * FROM custom WHERE area1='arcimago'";
    $result=db_query($sql);
    $dep = db_fetch_assoc($result);
    if (db_num_rows($result) == 0) {
        $sqli = "INSERT INTO custom (dTime,dDate,area1) VALUES (NOW(),NOW(),'arcimago')";
        $resulti=db_query($sqli);
    }
    if ($carriera==44){
        output("`0<form action=\"mago.php?op=sala\" method='POST'>",true);
        output("[Arcimago] Inserisci Notizia? <input name='meldung' size='80'> ",true);
        output("<input type='submit' class='button' value='Insert'>`n`n",true);
        addnav("","mago.php?op=sala");
        if ($_POST['meldung']){
            $sql = "UPDATE custom SET dTime = now(),dDate = now() WHERE area1 = 'arcimago'";
            $result=db_query($sql);
            $sql = "UPDATE custom SET amount = ".$session['user']['acctid']." WHERE area1 = 'arcimago'";
            $result=db_query($sql);
            $sql = "UPDATE custom SET area ='".addslashes($_POST['meldung'])."' WHERE area1 = 'arcimago'";
            $result=db_query($sql);
            $_POST[meldung]="";
        }
        addnav("","news.php");
    }
    $sql="SELECT * FROM custom WHERE area1='arcimago' ORDER BY area1, dDate, dTime DESC";
    $result=db_query($sql);
    $countrow = db_num_rows($result);
    for ($i=0; $i<$countrow; $i++){
    //for($i=0;$i<db_num_rows($result);$i++){
        $dep = db_fetch_assoc($result);
        $lasttime=$dep['dTime'];
        $lastdate=$dep['dDate'];
        $msgmago = stripslashes($dep['area']);
        $idgs = $dep['amount'];
        if ($msgmago !="") {
            $sqlnome="SELECT name,carriera,login FROM accounts WHERE acctid=$idgs";
            $resultnome=db_query($sqlnome);
            $dep1=db_fetch_assoc($resultnome);
            $nomeam=$dep1['name'];
            if($dep1[carriera]==44){
                output("<big>`n`b`c`@ANNUNCIO DELL'ARCIMAGO `#$nomeam `@.`0`c`b</big>`n",true);
                output("`8".date("d/m/Y",strtotime($lastdate))." `6".date("h:i:s",strtotime($lasttime))."   `b`^".$msgmago."`b`n`n`n");
            }
        }
    }
// fine modifica annuncio arcimago
        addcommentary();
        viewcommentary("Mago","dice",30,25);
}
// I Migliori Maghi
if ($_GET['op']=="migliori") {
        output("In una parete sono incisi i nomi dei migliori maghi. Solo il primo può fregiarsi del titolo di Arcimago.`n`n");
//        $sqlo = "SELECT * FROM accounts WHERE punti_carriera >= 1 AND carriera > 40 AND carriera < 45 AND superuser = 0 ORDER BY punti_carriera DESC";
    $sqlo = "SELECT * FROM accounts WHERE punti_carriera >= 1 AND carriera > 40 AND carriera < 45 ORDER BY punti_carriera DESC";
        $resulto = db_query($sqlo) or die(db_error(LINK));
        output("<table cellspacing=0 cellpadding=2 align='center'>", true);
        output("<tr class='trhead'><td>&nbsp;</td><td>`bNome`b</td><td>`bLivello`b</td></tr>", true);
        if (db_num_rows($resulto) == 0) {
                output("<tr><td colspan=4 align='center'>`&Non ci sono maghi in paese`0</td></tr>", true);
        }
        $countrowo = db_num_rows($resulto);
        for ($i=0; $i<$countrowo; $i++){
        //for ($i = 0;$i < db_num_rows($resulto);$i++) {
                $rowo = db_fetch_assoc($resulto);
                if ($rowo['name'] == $session['user']['name']) {
                        output("<tr bgcolor='#007700'>", true);
                } else {
                        output("<tr class='" . ($i % 2?"trlight":"trdark") . "'>", true);
                }
                output("<td>".($i + 1).".</td><td>{$rowo['name']}</td><td>".$arraycarriera[$rowo[carriera]]."</td></tr>", true);
        }
        output("</table>", true);
}

// Inizio Rigenerazione Oggettii Magici
if ($_GET['op']=="rigenerazione") {

    output("La torre dei maghi è in grado di trasmettere la magia agli oggetti magici e, per un prezzo equo, si occuperà di rigenerare gli oggetti di chiunque lo richieda.`n");
    output("Questo è lo stato del tuo equipaggiamento.`n`n");
    output("<table border=0 cellpadding=2 cellspacing=1 align=center>",true);
    output("<tr class='trhead'><td>`bOggetto`b</td><td>`bStato`b</td><td>`bCosto Riparazione`b</td><td>`bOps`b</td>",true);
    if ($session['user']['superuser']>0) {
        output("<td>`bPunti Mana`b</td>",true);
        output("<td>`bPercentuale`b</td>",true);
    }
    output("</tr>",true);

    if ($session['user']['oggetto'] != 0) {
        $sqlo = "SELECT * FROM oggetti WHERE id_oggetti = '{$session['user']['oggetto']}'";
        $resulto = db_query($sqlo) or die(db_error(LINK));
        $rowo = db_fetch_assoc($resulto);
        $ogg = $rowo['nome'];
        if ($rowo[usuramagicamax]==-1 OR $rowo[usuramagicamax]==0) {
               $percentuale_oggetto = 0;
        } else {
               $percentuale_oggetto = getPercentuale($rowo[usuramagica], $rowo[usuramagicamax]);
        }
        $value_oggetto = (500 + (10 * $rowo[livello]) * $rowo[valore]) ;
        $costo_oggetto = getCosto($value_oggetto, $percentuale_oggetto, $riparazione, $manodopera);
        $stogg = round($percentuale_oggetto/20);
        $stato_oggetto = $statoogg[$stogg];
        if ($rowo[usuramagica] == 0) $stato_oggetto = "`\$Esaurito";
        if ($rowo[usuramagica] == 0) $costo_oggetto *= 2;
        output("<tr class='trlight'><td>`&".$ogg."</td><td>$stato_oggetto</td><td>`^$costo_oggetto Oro</td><td><A href=mago.php?op=rigenera&og=oggetto>Rigenera </a></td>",true);
        if ($session['user']['superuser']>0) {
            $punti_rig_select = getPuntiMana($percentuale_oggetto,$rowo[livello]);
            output("<td>$punti_rig_select</td>",true);
            output("<td>$percentuale_oggetto</td>",true);
        }
        output("</tr>",true);
    }
    if ($session['user']['zaino'] != 0) {
        $sqlz = "SELECT * FROM oggetti WHERE id_oggetti = '{$session['user']['zaino']}'";
        $resultz = db_query($sqlz) or die(db_error(LINK));
        $rowz = db_fetch_assoc($resultz);
        $ogg = $rowz['nome'];
        if ($rowz[usuramagicamax]==-1 OR $rowz[usuramagicamax]==0) {
               $percentuale_oggetto = 0;
        } else {
               $percentuale_oggetto = getPercentuale($rowz[usuramagica], $rowz[usuramagicamax]);
        }
        $value_oggetto = (500 + (10 * $rowz[livello]) * $rowz[valore]) ;
        $costo_oggetto = getCosto($value_oggetto, $percentuale_oggetto, $riparazione, $manodopera);
        $stogg = round($percentuale_oggetto/20);
        $stato_oggetto = $statoogg[$stogg];
        if ($rowz[usuramagica] == 0) $stato_oggetto = "`\$Esaurito";
        if ($rowz[usuramagica] == 0) $costo_oggetto *= 2;
        output("<tr class='trdark'><td>`&".$ogg."</td><td>$stato_oggetto</td><td>`^$costo_oggetto Oro</td><td><A href=mago.php?op=rigenera&og=zaino>Rigenera </a></td>",true);
        if ($session['user']['superuser']>0) {
            $punti_rig_select = getPuntiMana($percentuale_oggetto,$rowz[livello]);
            output("<td>$punti_rig_select</td>",true);
            output("<td>$percentuale_oggetto</td>",true);
        }
        output("</tr>",true);
    }
    output("</table>",true);
    if ($session['user']['oggetto'] != 0) {
        addnav("","mago.php?op=rigenera&og=oggetto");
    }
    if ($session['user']['zaino'] != 0) {
        addnav("","mago.php?op=rigenera&og=zaino");
    }
}

if ($_GET['op']=="rigenera") {
    if ($_GET['og']=="oggetto") {
        // Oggetto in mano
        $sqlo = "SELECT * FROM oggetti WHERE id_oggetti = '{$session['user']['oggetto']}'";
        $resulto = db_query($sqlo) or die(db_error(LINK));
        $rowo = db_fetch_assoc($resulto);
        $value = (500 + (10 * $rowo[livello]) * $rowo[valore]) ;
        $livello_oggetto = $rowo[livello];
        $usura = $rowo[usuramagica];
        $oggetto = $rowo[nome];
        $usura_max = $rowo[usuramagicamax];
        if ($rowo[usuramagicamax]==-1 OR $rowo[usuramagicamax]==0) {
            $percentuale = 0;
        } else {
            $percentuale = getPercentuale($usura, $usura_max);
        }
        $costo = getCosto($value, $percentuale, $riparazione, $manodopera);
        if ($rowo[usuramagica] == 0) $costo *= 2;
    } elseif ($_GET['og']=="zaino") {
        // Oggetto nello zaino
        $sqlo = "SELECT * FROM oggetti WHERE id_oggetti = '{$session['user']['zaino']}'";
        $resulto = db_query($sqlo) or die(db_error(LINK));
        $rowo = db_fetch_assoc($resulto);
        $value = (500 + (10 * $rowo[livello]) * $rowo[valore]) ;
        $livello_oggetto = $rowo[livello];
        $usura = $rowo[usuramagica];
        $oggetto = $rowo[nome];
        $usura_max = $rowo[usuramagicamax];
        if ($rowo[usuramagicamax]==-1 OR $rowo[usuramagicamax]==0) {
            $percentuale = 0;
        } else {
            $percentuale = getPercentuale($usura, $usura_max);
        }
        $costo = getCosto($value, $percentuale, $riparazione, $manodopera);
        if ($rowo[usuramagica] == 0) $costo_oggetto *= 2;
    }

    if (donazioni('tessera_rigenerazioni')==true) {
        $costo = round($costo/2, 0);
    }

    if ($livello_oggetto == 0 || $percentuale <= 0) {$percentuale = 0; $costo = 0;}

    output("`7Amon prende il tuo ".$oggetto." e lo studia attentamente.`n");
    output("`&\"Questa è proprio un bel ".$oggetto."!");
    $status = round($percentuale/20);
    if ($status <= 0) {
        output(" E a quanto vedo è ben carico di mana, non ha senso rigenerarlo adesso...\"`n`n");
        output("`7Con un pò di imbarazzo riprendi il tuo ".$oggetto."...");
    } else {

        if ($session['user']['gold'] < $costo) {
            output(" Ora, dovresti solo avere i soldi per pagare la rigenerazione...\"`n`n");
            output("`7Con un pò di imbarazzo riprendi il tuo ".$oggetto."...");
        } else {
            $punti_rig_select = getPuntiMana($percentuale,$livello_oggetto);
            if ($mana < $punti_rig_select) {
                output("Purtroppo però non mi è possibile rigenerare questo oggetto, poichè non disponiamo di mana sufficiente alla rigenerazione in questo momento.`n");
                output("Prova a cercare qualche mago che si dia da fare a generare del mana, o a tornare più tardi.\"`n`n");
                output("`7Deluso da questa notizia, riprendi il tuo ".$oggetto."...");
            } else {
                output("Sarà un piacere operarci su!\"`n");
                // Inizio rigerazioni
                $casoRigenera = e_rand(90,100);
                if ($rowo[usuramagica]==0) {
                    $casoRigenera = e_rand(30,60);
                    output("Vedo che è completamente privo di mana... la rigenerazione sarà costosa e non potrà essere perfetta, ma almeno l'oggetto riprenderà a funzionare.\"`n`n");
                }
                if ($rowo[usuramagica]>=90) $casoRigenera = 100;
                if ($rowo[usuramagica]==0 AND $_GET['og']=="oggetto") {
                    output("`^L'oggetto che tieni in mano ha riacquisito le sue proprietà magiche. Ora tutti i suoi bonus saranno attivi.");
                    $session['user']['maxhitpoints'] += $rowo['hp_help'];
                    $session['user']['hitpoints'] += $rowo['hp_help'];
                    $session['user']['playerfights'] += $rowo[pvp_help];
                    $session['user']['turns'] = $session['user']['turns'] + $rowo[turns_help];
                    $session['user']['bonusfight'] += $rowo['turns_help'];
                }
                if ($_GET['og']=="oggetto") output("`^L'oggetto che tieni in mano ha riacquisito le sue proprietà magiche. Ora tutti i suoi bonus saranno attivi.");
                if ($_GET['og']=="zaino") output("`^L'oggetto che tieni nello zaino ha riacquisito le sue proprietà magiche. Ora tutti i suoi bonus saranno attivi quando lo prenderai in mano.");
                if ((100-$casoRigenera)>$percentuale) $casoRigenera = 101 - $percentuale;
                $rigenerazione=intval(($usura_max * $casoRigenera) / 100);
                $sql="UPDATE oggetti SET usuramagica='{$rigenerazione}' WHERE id_oggetti='{$rowo[id_oggetti]}'";
                db_query($sql) or die(db_error(LINK));
                debuglog("rigenera {$oggetto} spendendo $costo oro");
                $session['user']['gold'] -= $costo;
                output("`n`n`7".$oggetto." è stato rigenerato.`n ");
                //paga a stregoni/maghi
                $sqlmag = "SELECT acctid,name,carriera FROM accounts WHERE carriera > 41 AND carriera < 45";
                $resultmag = db_query($sqlmag);
                $num=0;
                $countrowmag = db_num_rows($resultmag);
                for ($i=0; $i<$countrowmag; $i++){
                //for ($i=0;$i<db_num_rows($resultmag);$i++){
                    $rowmag = db_fetch_assoc($resultmag);
                    $num ++ ;
                    if ($rowmag[$i][carriera] > 42) $num ++ ;
                }
                if ($num > 0) {
                    if (donazioni('tessera_rigenerazioni')==true) {
                        $costo *= 2;
                    }
                    $pagastregoni = intval($costo/$num*2/3);
                    $pagamaghi = $pagastregoni * 2;
                    $sqlmag = "SELECT acctid,name,carriera FROM accounts WHERE carriera > 41 AND carriera < 45";
                    $resultmag = db_query($sqlmag);
                    $countrowmag1 = db_num_rows($resultmag);
                    for ($i=0; $i<$countrowmag1; $i++){
                    //for ($i=0;$i<db_num_rows($resultmag);$i++){
                        $rowmag = db_fetch_assoc($resultmag);
                        if ($rowmag[carriera] > 42) {
                            $sqlpaga = "SELECT * FROM rigenerazioni WHERE acctid = '{$rowmag['acctid']}'";
                            $resultpaga = db_query($sqlpaga) or die(db_error(LINK));
                            $refpaga = db_fetch_assoc($resultpaga);
                            if (db_num_rows($resultpaga)>0) {
                            // esiste il record vado ad aggiornarlo
                                $sqlupdate = "UPDATE rigenerazioni SET gold = '".($pagastregoni+$refpaga[gold])."' WHERE acctid='{$refpaga['acctid']}' ";
                                db_query($sqlupdate) or die(db_error(LINK));
                            } else {
                            // non esiste il record lo inserisco
                                $sqlinsert = "INSERT INTO rigenerazioni (acctid,gold) VALUES ('{$rowmag['acctid']}','$pagastregoni')";
                                db_query($sqlinsert) or die(db_error(LINK));
                                }
                        } else {
                            $sqlpaga = "SELECT * FROM rigenerazioni WHERE acctid = '{$rowmag['acctid']}'";
                            $resultpaga = db_query($sqlpaga) or die(db_error(LINK));
                            $refpaga = db_fetch_assoc($resultpaga);
                            if (db_num_rows($resultpaga)>0) {
                            // esiste il record vado ad aggiornarlo
                                $sqlupdate = "UPDATE rigenerazioni SET gold = '".($pagamaghi+$refpaga[gold])."' WHERE acctid='{$refpaga['acctid']}' ";
                                db_query($sqlupdate) or die(db_error(LINK));
                            } else {
                            // non esiste il record lo inserisco
                                $sqlinsert = "INSERT INTO rigenerazioni (acctid,gold) VALUES ('{$rowmag['acctid']}','$pagamaghi')";
                                db_query($sqlinsert) or die(db_error(LINK));
                            }
                        }
                    }
                }//fine paga stregoni
                //aggiornamento punti mana
                $mana -= $punti_rig_select;
                savesetting("mana",$mana);
                $sqlmp = "SELECT * FROM mana WHERE acctid='-1' AND data='{$data}'";;
                $resmp = db_query($sqlmp) or die(db_error(LINK));
                $managiocatore = db_fetch_assoc($resmp);
                $manaplayer = $managiocatore['mana_player'];
                if (db_num_rows($resmp) == 0) {
                //creo il campo
                    $sqloggi="INSERT INTO mana (acctid, data, mana_player) VALUES ('-1', '".$data."', '0')";
                    db_query($sqloggi) or die(db_error(LINK));
                    $manaplayer = 0;
                }
                $manaplayer -= $punti_rig_select;
                $sqlmana = "UPDATE mana SET mana_player = '".$manaplayer."' WHERE acctid='-1' AND data='{$data}'";
                db_query($sqlmana) or die(db_error(LINK));
            }//fine controllo mana
        }//fine controllo oro
    }
}
// Fine Rigenerazione Oggetti Magici
// riscossione della paga per le rigenerazioni
if ($_GET['op']=="riscuoti") {
    output ("Ti avvicini ad Amon e gli chiedi se potresti avere il tuo compenso per le rigenerazioni eseguite nella gilda, ");
    output("lui prende un grande registro e controlla.`n");
    $sqlrig = "SELECT gold, riscosso FROM rigenerazioni WHERE acctid = {$idplayer}";
    $resultrig = db_query($sqlrig) or die(db_error(LINK));
    $refrig = db_fetch_assoc($resultrig);
    $livello = $arraycarriera[$carriera];

    if (db_num_rows($resultrig)>0 && $refrig[gold]>0){
       // LIMITAZIONE PAGA MAGHI
       if ($refrig[riscosso]==1) {
          // già riscosso al newday o generato nello stesso giorno
          output("Quindi ti risponde : `&\"Ho eseguito alcune rigenerazioni, ma non posso proprio pagarti di oggi,");
          output("prova a tornare domani e penso che sarò in grado di consegnarti la tua quota... \"`n");
       } else {
          $maxpaga = (500 * $session['user']['level']);
          if ($maxpaga < $refrig['gold']) {
             output("Quindi ti risponde : `&\"Ho eseguito alcune rigenerazioni, la tua quota ammonta a `^$refrig[gold] Monete D'Oro`&.");
             output("Tuttavia, al momento non posso darti più di `^$maxpaga Monete D'Oro`&. Penso che fino a domani non riuscirò a pagarti altre monete.\"`n`n");
             output("`)Sempre imperturbabile, ti allunga un sacchetto con le monete");
             debuglog("riscuote $maxpaga per le rigenerazioni, ha ancora un credito da riscuotere");
             $session['user']['gold']+=$maxpaga;
             $refrig['gold'] -= $maxpaga;
             $sqlupdate = "UPDATE rigenerazioni SET gold = {$refrig['gold']}, riscosso=1 WHERE acctid='{$idplayer}' ";
             db_query($sqlupdate) or die(db_error(LINK));
          } else {
             output("Quindi ti risponde: `&\"Ho eseguito alcune rigenerazioni, la tua quota ammonta a `^$refrig[gold] Monete D'Oro`&\".");
             output("`)Sempre imperturbabile, ti allunga un sacchetto con le monete");
             debuglog("riceve {$refrig[gold]} per le riparazioni fatte");
             $session['user']['gold']+=$refrig[gold];
             $sqlupdate = "UPDATE rigenerazioni SET gold = 0, riscosso=1 WHERE acctid='{$idplayer}' ";
             db_query($sqlupdate) or die(db_error(LINK));
          }
       }
   } else {
        output("Quindi ti risponde: `&\"Non sono state effettuate rigenerazioni dall'ultima volta che sei passato a riscuotere la tua parte. E dunque la tua quota al momento è nulla.\"`n");
   }
}
//fine riscossione paga
//generazione di punti mana
if ($_GET['op']=="makemana") {
        switch($_GET['og']) {
                case "intro":
                output("`nTi avvicini al cuore della torre dei maghi, una grossa sfera d'oro usata come accumulatore per il mana necessario ad ogni magia.");
                                output("`n`n<table border=0 cellpadding=2 cellspacing=1 align=center>",true);
                if ($session[user][turns]>1) {
                        output("<tr class='trlight'><td><a href=mago.php?op=makemana&og=fai>`bGenera del Mana`b</a></td></tr>", true);
                        addnav("","mago.php?op=makemana&og=fai");
                } else {
                        output("<tr class='trlight'><td>`bNon hai abbastanza Turni!`b</td></tr>", true);
                }
                output("</table>`n`n",true);
                break;
                case "fai":
                output("`7Tocchi la sfera dorata e, concentrandoti su di essa, trasferisci una parte delle tue energie al suo interno.`n`n");
                switch ($carriera) {
                        case 41: //Iniziato
                        $managen = e_rand(50,100);
                        $mana += $managen;
                        output("`&Sai per certo di aver generato del mana, ma non sei in grado di capire quanto.`n`n");
                        break;
                        case 42: //Stregone
                        $managen = e_rand(75,125);
                        $mana += $managen;
                        $managen += e_rand(-20,20); // sporchiamo il valore realmente creato con un errore
                        output("`&Hai generato `^".$managen." punti mana`&.`n`n");
                        break;
                        case 43: //Mago
                        $managen = e_rand(100,150);
                        $mana += $managen;
                        output("`&Hai generato `^".$managen." punti mana`&.`n`n");
                        break;
                        case 44: //Arcimago
                        $managen = e_rand(125,200);
                        $mana += $managen;
                        output("`&Hai generato `^".$managen." punti mana`&.`n`n");
                        break;
                }
                $manaplayer += $managen;
                $sqlmana = "UPDATE mana SET mana_player = '".$manaplayer."' WHERE acctid='{$idplayer}' AND data='{$data}'";
                db_query($sqlmana) or die(db_error(LINK));
                debuglog("ha generato ".$managen." punti mana alla torre dei maghi spendendo 2 turni");
                output("`7L'energia consumata ti è costata `\$2 turni`7.");
                $session[user][turns]-=2;
                savesetting("mana",$mana);
                break;
        }
}
//Analisi di quanto mana è disponibile
if ($_GET['op']=="quantomana") {
        output("`7Ti avvicini alla sfera dorata e ti metti alla ricerca del mana contenuto al suo interno.`n`n");
        switch ($carriera) {
                case 41: //Iniziato
                if ($mana == 0) {
                        output("La sfera è completamente priva di mana");
                } elseif ($mana < 20) {
                        output("Avverti tracce di mana, ma in quantità minime");
                } elseif ($mana < 100) {
                        output("C'è poco mana nella sfera");
                } elseif ($mana < 500) {
                        output("Senti il mana contenuto nella sfera, ce n'è una discreta quantità");
                } elseif ($mana < 1000) {
                        output("Senti chiaramente il mana contenuto nella sfera, ce n'è parecchio");
                } else {
                        output("C'è tantissimo mana a disposizione nella sfera, la forte concentrazione ti stordisce per alcuni secondi");
                }
                break;
                case 42: //Stregone
                if ($mana == 0) {
                        output("La sfera è completamente priva di mana");
                } elseif ($mana < 1000) {
                        $manaavv = round($mana * e_rand (85,115) / 100);
                        if ($manaavv > 1000) $manaavv=1000;
                        output("Con le tue crescenti capacità, puoi dire che contiene all'incirca $manaavv punti mana");
                } else {
                        output("La sfera contiene molto mana; più di quanto tu possa ancora riuscire a stimare.");
                }
                break;
                case 43: //Mago
                if ($mana == 0) {
                        output("La sfera è completamente priva di mana");
                } else {
                        output("La sfera contiene esattamente $mana punti mana");
                }
                break;
                case 44: //Arcimago
                if ($mana == 0) {
                        output("La sfera è completamente priva di mana");
                } else {
                        output("La sfera contiene esattamente $mana punti mana");
                }
                output("`@Concentrandoti più intensamente sulla sfera, sai di poter capire anche quanto ciascun mago ha interagito con il mana qui accumulato.`n`n");
                output("Ma questa operazione è rischiosa, in quanto è possibile che parte del mana vada perduto nel processo, perciò dovresti pensarci attentamente prima di procedere.");
                addnav("Studio del mana accumulato");
                addnav("Analizza ulteriormente il mana", "mago.php?op=sfera");
                break;
        }
}
//inizio laboratorio (per incantesimi)
if ($_GET['op'] == "laboratorio") {
    if($_GET['op2']=="comprapergamena"){
        $pergamena = $_GET['id'];
        $sql = "SELECT * FROM materiali WHERE id = '$pergamena'";
        $result = db_query($sql) or die(db_error(LINK));
        $row = db_fetch_assoc($result);
        if ($session['user']['gold']>=$row[valoremo]){
                if ($session['user']['gems']>=$row[valorege]) {
                        if (zainoPieno ($idplayer)) {
                                output("Purtroppo non hai più spazio nello zaino.`n");
                        } else {
                                output("Ithine afferra i tuoi ".$row[valoremo]." pezzi d'oro");
                                if ($row[valorege]==1) output ("e la tua gemma");
                                if ($row[valorege]>1) output ("e le tue ".$row[valorege]." gemme");
                                output(" e ti da una ".$row[nome]." in cambio.`n`n");
                                $session['user']['gold']-=$row[valoremo];
                                $session['user']['gems']-=$row[valorege];
                                $sqli="INSERT INTO zaino (idoggetto, idplayer) VALUES (".$row[id].", '{$idplayer}')";
                                db_query($sqli) or die(db_error(LINK));
                                debuglog("compra una ".$row[nome]." al laboratorio");
                        }
                } else {
                        output("Non hai abbastanza gemme!`n`n");
                }
        }else{
                output("Non hai abbastanza oro!`n`n");
        }
    }elseif($_GET['op2']=="vendipergamena"){
        $pergamena = $_GET['id'];
        $sql = "SELECT * FROM zaino WHERE idplayer='{$idplayer}' AND id = $pergamena";
        $result = db_query($sql) or die(db_error(LINK));
        $row = db_fetch_assoc($result);
        $idvendi = $row['id'];
        $tipopergamena = $row['idoggetto'];
        $sql1 = "SELECT valoremo AS oro, valorege AS gemme, nome AS nome FROM materiali WHERE id = $tipopergamena";
        $result1 = db_query($sql1) or die(db_error(LINK));
        $row1 = db_fetch_assoc($result1);
        $orovendi = round($row1['oro']/3*2);
        $gemmevendi = round($row1['gemme']/3*2);
        $session['user']['gold'] += $orovendi;
        $session['user']['gems'] += $gemmevendi;
        output("Ithine prende la tua pergamena e ti posa in mano $orovendi pezzi d'oro");
        if ($gemmevendi==1) output ("e una gemma");
        if ($gemmevendi>1) output ("e $gemmevendi gemme");
        output("in cambio.`n`n");
        debuglog("vende una ".$row1[nome]." ad Ithine");
        $sqld = "DELETE FROM zaino WHERE id = $idvendi";
        db_query($sqld);
    }elseif ($_GET['op2'] == "incanta") {
        $oggetto = $session['user']['oggetto'];
        if ($session['user']['oggetto'] == "0") {
            $ogg = "Nulla";
        } else {
            $sqlo = "SELECT * FROM oggetti WHERE id_oggetti = $oggetto";
            $resultoo = db_query($sqlo) or die(db_error(LINK));
            $rowo = db_fetch_assoc($resultoo);
            $ogg = $rowo['nome'];
        }
        output ("<table>", true);
        output ("<tr><td>`#Puoi incantare  : </td><td>`b`^" . $ogg . "`b</td></tr>", true);
        if ($rowo['pregiato']==true AND getsetting("blocco_valore",0)=="1") output ("<tr><td>`^`bL'oggetto è pregiato`b </td><td></td></tr>", true);
        output ("<tr><td>`@Potenziamenti rimanenti: </td><td>`b`\$" . $rowo['potenziamenti'] . "`b</td></tr>", true);
        output ("</table>", true);
        if ($rowo['potenziamenti'] > 0 AND $session['user']['punti_carriera'] > 3999 AND $mana > 9999) {
            addnav("Azioni");
            addnav("`&Incanta oggetto", "mago.php?op=laboratorio&op2=incanta_conferma");
        }elseif ($rowo['potenziamenti'] == 0) {
            output("`5`nL'oggetto non ha potenziamenti residui e non può essere incantato.`n");
        }elseif ($session['user']['punti_carriera'] < 4000) {
            output("`%`nNon hai Punti Carriera a sufficienza per incantare l'oggetto.`n");
        }elseif ($mana < 10000) {
            output("`%`nNon c'è sufficiente mana a disposizione per incantare l'oggetto.`n");
        }
    } elseif ($_GET['op2'] == "incanta_conferma") {
        output("`%Che tipo di incantamento vuoi applicare?`n`n");
        output("`(Abilità`n");
        output("`#Vigore`n");
        $sqlo = "SELECT pregiato,turns_help FROM oggetti WHERE id_oggetti = ".$session['user']['oggetto'];
        $resultoo = db_query($sqlo) or die(db_error(LINK));
        $rowo = db_fetch_assoc($resultoo);
        if ($rowo['pregiato']==false AND getsetting("blocco_valore",0)=="1") output("`^Valore`n");
        addnav("Incantamenti");
        addnav("`(Abilità", "mago.php?op=laboratorio&op2=incanta_abilita");
        if ($rowo['turns_help']<8) addnav("`#Vigore", "mago.php?op=laboratorio&op2=incanta_vigore");
        if ($rowo['pregiato']==false AND getsetting("blocco_valore",0)=="1") addnav("`^Valore", "mago.php?op=laboratorio&op2=incanta_valore");
    } elseif ($_GET['op2'] == "incanta_abilita") {
        $oggetto = $session['user']['oggetto'];
        $sqlo = "SELECT * FROM oggetti WHERE id_oggetti = $oggetto";
        $resultoo = db_query($sqlo) or die(db_error(LINK));
        $rowo = db_fetch_assoc($resultoo);
        $skills = array(1 => "`\$Arti Oscure", "`%Poteri Mistici", "`^Furto", "`3Militare","`\$Seduzione","`^Tattica","`@Pelle di Roccia","`#Retorica","`%Muscoli","`3Natura","`&Clima","`^Elementalista","`6Rabbia Barbara","`5Canzoni del Bardo");
        if ($rowo[special_help]==0) $rowo[special_help]=$session['user']['specialty'];
        $potere= ceil($caso/2);
        $bonus = $potere * mt_rand(8,12);
        $sqlu = "UPDATE oggetti SET potere=1, potere_uso=1, potenziamenti=potenziamenti-1, special_use_help=special_use_help+$potere, special_help=$rowo[special_help], valore=valore+$bonus WHERE id_oggetti='$oggetto'";
        db_query($sqlu) or die(db_error(LINK));
        //modifica per aggiornamento dell'usura
        $usuramagicaextra =  $caso * 20 + $bonus * 2;
        $sqlusura = "SELECT usuramagica, usuramagicamax FROM oggetti WHERE id_oggetti='$oggetto'";
        $resultus = db_query($sqlusura) or die(db_error(LINK));
        $rowus = db_fetch_assoc($resultus);
        if ($rowus[usuramagicamax]>-1) {
            if ($rowus[usuramagica]==0 AND $rowus[usuramagicamax]==0) {
                $sqlu = "UPDATE oggetti SET usuramagica=$usuramagicaextra WHERE id_oggetti='$oggetto'";
                db_query($sqlu) or die(db_error(LINK));
            }
            $sqlu = "UPDATE oggetti SET usuramagicamax=usuramagicamax+$usuramagicaextra WHERE id_oggetti='$oggetto'";
            db_query($sqlu) or die(db_error(LINK));
        }
        //fine modifica usura
        output("`7Una luce `(`barancione`b`7 circonda il tuo oggetto.`n");
        output ("L'oggetto $rowo[nome] garantisce ora $potere utilizz".($potere==1?"o":"i")." in più nell'abilità ".$skills[$rowo[special_help]].".");
        output("`nEd il suo valore è aumentato di $bonus gemme`n");
        debuglog("ha benedetto l'oggetto ($oggetto) migliorando di $caso la forza d'attacco e aggiungendo $bonus gemme al valore");
        $session['user']['punti_carriera'] -= (1000 * $caso);
    } elseif ($_GET['op2'] == "incanta_vigore") {
        $oggetto = $session['user']['oggetto'];
        $vigore = intval($caso/2)+1;
        $bonus = $caso * mt_rand(8,12);
        $sqlu = "UPDATE oggetti SET turns_help=turns_help+$vigore, potenziamenti=potenziamenti-1, valore=valore+$bonus WHERE id_oggetti='$oggetto'";
        db_query($sqlu) or die(db_error(LINK));
        //modifica per aggiornamento dell'usura
        $usuraextra =  $vigore * 2 + $bonus * 5;
        $sqlusura = "SELECT usuramax FROM oggetti WHERE id_oggetti='$oggetto'";
        $resultus = db_query($sqlusura) or die(db_error(LINK));
        $rowus = db_fetch_assoc($resultus);
        if ($rowus[usuramax]>0) {
            $sqlu = "UPDATE oggetti SET usuramax=usuramax+$usuraextra WHERE id_oggetti='$oggetto'";
            db_query($sqlu) or die(db_error(LINK));
        }
        $usuramagicaextra =  $caso * 20 + $bonus * 2;
        $sqlusura = "SELECT usuramagicamax FROM oggetti WHERE id_oggetti='$oggetto'";
        $resultus = db_query($sqlusura) or die(db_error(LINK));
        $rowus = db_fetch_assoc($resultus);
        if ($rowus[usuramagicamax]>-1) {
            if ($rowus[usuramagica]==0 AND $rowus[usuramagicamax]==0) {
                $sqlu = "UPDATE oggetti SET usuramagica=$usuramagicaextra WHERE id_oggetti='$oggetto'";
                db_query($sqlu) or die(db_error(LINK));
            }
            $sqlu = "UPDATE oggetti SET usuramagicamax=usuramagicamax+$usuramagicaextra WHERE id_oggetti='$oggetto'";
            db_query($sqlu) or die(db_error(LINK));
        }
        //fine modifica usura
        output("`7Una luce `#`bazzurra`b`7 circonda il tuo oggetto.`n");
        output ("L'oggetto è ora in grado di concedere `b$vigore`b turni extra in più ogni nuovo giorno.");
        output("`nEd il suo valore è aumentato di $bonus gemme`n");
        debuglog("ha incantato l'oggetto ($oggetto) aggiungendogli $vigore turni extra e aggiungendo $bonus gemme al valore");
        $session['user']['turns'] += $vigore;
        $session['user']['bonusfight'] += $vigore;
        $session['user']['punti_carriera'] -= (1000 * $caso);
    }elseif ($_GET['op2'] == "incanta_valore") {
        $oggetto = $session['user']['oggetto'];
        $bonus = ($caso+1) * (mt_rand(8,12));
        $sqlu = "UPDATE oggetti SET pregiato=1, potenziamenti=potenziamenti-1, valore=valore+$bonus WHERE id_oggetti='$oggetto'";
        db_query($sqlu) or die(db_error(LINK));
        //modifica per aggiornamento dell'usura
        $usuraextra = $bonus * 5;
        $sqlusura = "SELECT usuramax FROM oggetti WHERE id_oggetti='$oggetto'";
        $resultus = db_query($sqlusura) or die(db_error(LINK));
        $rowus = db_fetch_assoc($resultus);
        if ($rowus[usuramax]>0) {
            $sqlu = "UPDATE oggetti SET usuramax=usuramax+$usuraextra WHERE id_oggetti='$oggetto'";
            db_query($sqlu) or die(db_error(LINK));
        }
        //fine modifica usura
        output("`7Una luce `b`^giallastra`7`b circonda il tuo oggetto.`n");
        output ("Il valore del tuo oggetto è stato aumentato di $bonus gemme.");
        debuglog("ha incantato l'oggetto ($oggetto) migliorando di $bonus il suo valore");
        $session['user']['punti_carriera'] -= (1000 * $caso);
    }else{
        $sqlpun = "SELECT * FROM punizioni_maghi WHERE acctid = '{$idplayer}'";
        $resultpun = db_query($sqlpun) or die(db_error(LINK));
        $refpun = db_fetch_assoc($resultpun);
        if(db_num_rows($resultpun)==0) {
                output("`7Ithine ti accompagna nel laboratorio degli incantesimi, ed apre una cassettiera dove sono custodite diverse pergamene.`n`n");
                if ($session['user']['carriera'] < 41 OR $session['user']['carriera'] > 44) {
                    output("Poi si rivolge a te: `&\"Purtroppo per te, non sei in grado di lanciare incantesimi. Posso però acquistare le tue pergamene, per rivenderle a chi sa come usarle\"`7.`n`n`n");
                }
                if ($session['user']['carriera'] > 40 AND $session['user']['carriera'] < 45) {
                    output("Poi si rivolge a te: `&\"Ho qui alcune pergamene con delle formule, per preparare gli incantesimi ti servirà la pergamena assieme agli ingredienti necessari\"`7.`n`n");
                    addnav("Laboratorio");
                    output ("`cPrimo Livello:`c`n");
                    //$sql = "SELECT * FROM materiali WHERE tipo = 'A'";
                    $sql = "SELECT * FROM materiali WHERE tipo='P' AND livello='A'";
                    $result = db_query($sql) or die(db_error(LINK));
                    output("<table cellspacing=0 cellpadding=2 align='center'>", true);
                    output("<tr class='trhead'><td align='center'>`bPergamena`b</td><td align='center'>`bCosto oro`b</td><td align='center'>`bCosto gemme`b</td>", true);
                                        if ($session['user']['level'] < 15) output("<td align='center'>Ops</td>", true);
                                        output("</tr>", true);
                    $countrow = db_num_rows($result);
                    for ($i=0; $i<$countrow; $i++){
                    //for ($i = 0;$i < db_num_rows($result);$i++) {
                        $row = db_fetch_assoc($result);
                        output("<tr class='" .($i % 2?"trlight":"trdark"). "'><td>`&".$row['nome']."</td><td>`^`c".$row['valoremo']."`c</td><td>`V`c".$row['valorege']."`c`0</td>",true);
                                                if ($session['user']['level'] < 15) output("<td><A href=mago.php?op=laboratorio&op2=comprapergamena&id={$row['id']}>Compra</a></td>", true);
                        if ($session['user']['level'] < 15) addnav("", "mago.php?op=laboratorio&op2=comprapergamena&id={$row['id']}");
                    }
                    output("</table>`n`n`n", true);
                }
                if ($session['user']['carriera'] > 41 AND $session['user']['carriera'] < 45) {
                    output ("`cSecondo Livello:`c`n");
                    //$sql = "SELECT * FROM materiali WHERE tipo = 'B'";
                    $sql = "SELECT * FROM materiali WHERE tipo='P' AND livello='B'";
                    $result = db_query($sql) or die(db_error(LINK));
                    output("<table cellspacing=0 cellpadding=2 align='center'>", true);
                    output("<tr class='trhead'><td align='center'>`bPergamena`b</td><td align='center'>`bCosto oro`b</td><td align='center'>`bCosto gemme`b</td><td align='center'>`bCompra`b</td></tr>", true);
                    $countrow = db_num_rows($result);
                    for ($i=0; $i<$countrow; $i++){
                    //for ($i = 0;$i < db_num_rows($result);$i++) {
                        $row = db_fetch_assoc($result);
                        output("<tr class='" .($i % 2?"trlight":"trdark"). "'><td>`&".$row['nome']."</td><td>`^`c".$row['valoremo']."`c</td><td>`V`c".$row['valorege']."`c`0</td>",true);
                                                if ($session['user']['level'] < 15) output("<td><A href=mago.php?op=laboratorio&op2=comprapergamena&id={$row['id']}>Compra</a></td>", true);
                        if ($session['user']['level'] < 15) addnav("", "mago.php?op=laboratorio&op2=comprapergamena&id={$row['id']}");
                    }
                    output("</table>`n`n`n", true);
                }
                if ($session['user']['carriera'] > 42 AND $session['user']['carriera'] < 45) {
                    output ("`cTerzo Livello:`c`n");
                    //$sql = "SELECT * FROM materiali WHERE tipo = 'C'";
                    $sql = "SELECT * FROM materiali WHERE tipo='P' AND livello='C'";
                    $result = db_query($sql) or die(db_error(LINK));
                    output("<table cellspacing=0 cellpadding=2 align='center'>", true);
                    output("<tr class='trhead'><td align='center'>`bPergamena`b</td><td align='center'>`bCosto oro`b</td><td align='center'>`bCosto gemme`b</td><td align='center'>`bCompra`b</td></tr>", true);
                    $countrow = db_num_rows($result);
                    for ($i=0; $i<$countrow; $i++){
                    //for ($i = 0;$i < db_num_rows($result);$i++) {
                        $row = db_fetch_assoc($result);
                        output("<tr class='" .($i % 2?"trlight":"trdark"). "'><td>`&".$row['nome']."</td><td>`^`c".$row['valoremo']."`c</td><td>`V`c".$row['valorege']."`c`0</td>",true);
                                                if ($session['user']['level'] < 15) output("<td><A href=mago.php?op=laboratorio&op2=comprapergamena&id={$row['id']}>Compra</a></td>", true);
                        if ($session['user']['level'] < 15) addnav("", "mago.php?op=laboratorio&op2=comprapergamena&id={$row['id']}");
                    }
                    output("</table>`n`n`n", true);
                }
                output("Nello zaino hai le seguenti pergamene:`n`n");
                $sql = "SELECT materiali.id AS idmateriali, materiali.nome AS nome, materiali.valoremo AS valoremo,
           materiali.valorege AS valorege, materiali.descrizione AS descrizione,zaino.id AS id FROM zaino, materiali
           WHERE zaino.idplayer = $idplayer AND zaino.idoggetto = materiali.id AND materiali.tipo='P' AND (materiali.livello = 'A' OR materiali.livello = 'B' OR materiali.livello = 'C' 
           OR materiali.livello = 'D' OR materiali.livello = 'E' OR materiali.livello = 'F')";
                output ("`n`%Ricette in tuo possesso:`n`n");
                output("<table cellspacing=0 cellpadding=2 align='center'>", true);
                output("<tr class='trhead'><td>&nbsp;</td><td align='center'>`bNome`b</td>", true);
                                if ($session['user']['level'] > 4) output("<td align='center'>Ops</td>", true);
                                output("</tr>", true);
                $result = db_query($sql) or die(db_error(LINK));
                if (db_num_rows($result) == 0) {
                    output("<tr><td colspan=4 align='center'>`&Non hai pergamene nello zaino`0</td></tr>", true);
                }
                $countrow = db_num_rows($result);
                for ($i=0; $i<$countrow; $i++){
                //for ($i = 0;$i < db_num_rows($result);$i++) {
                    $row = db_fetch_assoc($result);
                    output("<tr class='" . ($i % 2?"trlight":"trdark") . "'><td>".($i+1).".</td><td>{$row['nome']}</td>", true);
                                        if ($session['user']['level'] > 4) output("<td><A href=mago.php?op=laboratorio&op2=vendipergamena&id={$row['id']}> Vendi</a></td>", true);
                                        output("</tr>", true);
                    if ($session['user']['level'] > 4) addnav("","mago.php?op=laboratorio&op2=vendipergamena&id={$row['id']}");
                }
                output("</table>`n`n", true);
                if ($carriera>40 AND $carriera<45) addnav("Prepara un incantesimo","mago.php?op=prepara");
                if ($carriera>42 AND $carriera<45 AND getsetting("maghi_incanta_ogg",0)=="1") {
                    output("`&Puoi inoltre incantare un oggetto, ma la cosa consumerà molti dei tuoi punti carriera oltre a 10000 punti mana.`n`n");
                    addnav("Incanta un oggetto","mago.php?op=laboratorio&op2=incanta");
                }
        } else {
                $am = getsetting("arcimago",0);
                output("`7Provi ad entrare nel laboratorio degli incantesimi, quando Ithine ti ferma e ti dice che l'Arcimago ha deciso di punirti,");
                output("perchè non ti impegni a sufficienza nel generare mana e ne consumi vivendo del lavoro degli altri maghi.`n`n");
                output("La punizione scadrà tra ".$refpun['giorni']." giorni.");
        }
    }
}
if($_GET['op']=="prepara"){
        $incantesimi_max= $session['user']['carriera'] - 39; //(iniziato: 2 incantesimi, stregone: 3 incantesimi, mago: 4 incantesimi, arcimago: 5 incantesimi)
        $sqlq = "SELECT * FROM incantesimi WHERE acctid='{$idplayer}'";
        $resultq = db_query($sqlq) or die(db_error(LINK));
        $quanti= db_num_rows($resultq);
        if (($incantesimi_max - $quanti) < 1) {
                output("Non sei in grado di preparare nuovi incantesimi, hai raggiunto il limite di incantesimi memorizzati.`n`n");
        }else{
                output("Hai a disposizione le seguenti pergamene. Quale incantesimo vuoi preparare?`n`n`n");
                //incantesimi di primo livello
                $sql = "SELECT materiali.id AS idmateriali, materiali.nome AS nome, materiali.valoremo AS valoremo, materiali.valorege AS valorege,
            materiali.descrizione AS descrizione FROM zaino, materiali WHERE zaino.idplayer = $idplayer AND zaino.idoggetto = materiali.id AND materiali.tipo='P' AND (materiali.livello = 'A' OR materiali.livello = 'D')";
                output("`c`^Primo livello`c`n`$");
                output("<table cellspacing=0 cellpadding=2 align='center'>", true);
                output("<tr class='trhead'><td>&nbsp;</td><td>`bNome`b</td><td>`bValore oro`b</td><td>`bValore gemme`b</td></tr>", true);
                $result = db_query($sql) or die(db_error(LINK));
                if (db_num_rows($result) == 0) {
                        output("<tr><td colspan=4 align='center'>`&Non hai pergamene di questo livello !`0</td></tr>", true);
                }
                $countrow = db_num_rows($result);
                for ($i=0; $i<$countrow; $i++){
                //for ($i = 0;$i < db_num_rows($result);$i++) {
                        $row = db_fetch_assoc($result);
                        output("<tr class='" . ($i % 2?"trlight":"trdark") . "'><td>" . ($i + 1) . ".</td><td><A href=mago.php?op=ingredienti&id={$row['idmateriali']}&liv=1>$row[nome]</a></td><td>`c`^$row[valoremo]`c</td><td>`c`V$row[valorege]`c`0</td></tr>", true);
                        addnav("", "mago.php?op=ingredienti&id={$row['idmateriali']}&liv=1");
                }
                output("</table>`n`n", true);
                //incantesimi di secondo livello
                if ($session['user']['carriera'] > 41) {
                    $sql = "SELECT materiali.id AS idmateriali, materiali.nome AS nome, materiali.valoremo AS valoremo, materiali.valorege AS valorege,
            materiali.descrizione AS descrizione FROM zaino, materiali WHERE zaino.idplayer = $idplayer AND zaino.idoggetto = materiali.id AND materiali.tipo='P' AND (materiali.livello = 'B' OR materiali.livello = 'E')";
                    output("`c`^Secondo livello`c`n`@");
                    output("<table cellspacing=0 cellpadding=2 align='center'>", true);
                    output("<tr class='trhead'><td>&nbsp;</td><td>`bNome`b</td><td>`bValore oro`b</td><td>`bValore gemme`b</td></tr>", true);
                    $result = db_query($sql) or die(db_error(LINK));
                    if (db_num_rows($result) == 0) {
                        output("<tr><td colspan=4 align='center'>`&Non hai pergamene di questo livello !`0</td></tr>", true);
                    }
                    $countrow = db_num_rows($result);
                    for ($i=0; $i<$countrow; $i++){
                    //for ($i = 0;$i < db_num_rows($result);$i++) {
                        $row = db_fetch_assoc($result);
                        output("<tr class='" . ($i % 2?"trlight":"trdark") . "'><td>" . ($i + 1) . ".</td><td><A href=mago.php?op=ingredienti&id={$row['idmateriali']}&liv=2>$row[nome]</a></td><td>`c`^$row[valoremo]`c</td><td>`c`V$row[valorege]`c`0</td></tr>", true);
                        addnav("", "mago.php?op=ingredienti&id={$row['idmateriali']}&liv=2");
                    }
                    output("</table>`n`n", true);
                }
                //incantesimi di terzo livello
                if ($session['user']['carriera'] > 42) {
                    $sql = "SELECT materiali.id AS idmateriali, materiali.nome AS nome, materiali.valoremo AS valoremo, materiali.valorege AS valorege,
            materiali.descrizione AS descrizione FROM zaino, materiali WHERE zaino.idplayer = $idplayer AND zaino.idoggetto = materiali.id AND materiali.tipo='P' AND (materiali.livello = 'C' OR materiali.livello = 'F')";
                    output("`c`^Terzo livello`c`n`#");
                    output("<table cellspacing=0 cellpadding=2 align='center'>", true);
                    output("<tr class='trhead'><td>&nbsp;</td><td>`bNome`b</td><td>`bValore oro`b</td><td>`bValore gemme`b</td></tr>", true);
                    $result = db_query($sql) or die(db_error(LINK));
                    if (db_num_rows($result) == 0) {
                        output("<tr><td colspan=4 align='center'>`&Non hai pergamene di questo livello !`0</td></tr>", true);
                    }
                    $countrow = db_num_rows($result);
                    for ($i=0; $i<$countrow; $i++){
                    //for ($i = 0;$i < db_num_rows($result);$i++) {
                        $row = db_fetch_assoc($result);
                        output("<tr class='" . ($i % 2?"trlight":"trdark") . "'><td>" . ($i + 1) . ".</td><td><A href=mago.php?op=ingredienti&id={$row['idmateriali']}&liv=3>$row[nome]</a></td><td>`c`^$row[valoremo]`c</td><td>`c`V$row[valorege]`c`0</td></tr>", true);
                        addnav("", "mago.php?op=ingredienti&id={$row['idmateriali']}&liv=3");
                    }
                    output("</table>`n`n", true);
                }
        }
}

if($_GET['op']=="ingredienti"){
        $idmateriale = $_GET[id];
        $tutto_ok = 0;
        // Selezione Pergamena
        $sql = "SELECT * FROM  materiali WHERE id = $idmateriale";
        $result = db_query($sql) or die(db_error(LINK));
        $row = db_fetch_assoc($result);
        $livello_incantesimo = $_GET[liv];
        if ($livello_incantesimo=="1") {
                $numero_ingredienti = 5;
                $num_ing = 4;
        }
        if ($livello_incantesimo=="2") {
                $numero_ingredienti = 7;
                $num_ing = 6;
        }
        if ($livello_incantesimo=="3") {
                $numero_ingredienti = 11;
                $num_ing = 10;
        }

        output ("`bDescrizione Incantesimo :`b `n`n");
        output ("$row[descrizione]`n`n`n");
        if ($row['tipo'] == P AND ($row['livello'] == A OR $row['livello'] == B OR $row['livello'] == C OR $row['livello'] == D OR $row['livello'] == E OR $row['livello'] == F)) {
                output ("`bPer realizzare questo incantesimo ti occorre :`b`n`n");
                for ($i = 1;$i < $numero_ingredienti ;$i++) {
                        $ingrediente = "ingrediente".$i;
                        // Selezione Ingrediente
                        $sqlm = "SELECT nome FROM materiali WHERE id='{$row[$ingrediente]}'";
                        $resultm = db_query($sqlm) or die(db_error(LINK));
                        $rowm = db_fetch_assoc($resultm);
                        // Controllo se Disponibile
                        $sqlz = "SELECT id FROM zaino WHERE idplayer='{$idplayer}' AND idoggetto = '{$row[$ingrediente]}' {$ingr[($i-1)]}";
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
                $grado=$session['user']['carriera'];
                $incant=$row['utilizzatore'];
                $costo_mana_incantesimo = mana_incantesimo($grado,$livello_incantesimo,$incant);
                if ($costo_mana_incantesimo > $mana) $tutto_ok = 2;
                if ($row['costo_exp'] > $session['user']['punti_carriera']) $tutto_ok = 3;
                //tutto a posto
                if ($tutto_ok == 0) {
                        //aggiornamento punti mana

                        $sqldr = "SELECT * FROM zaino WHERE idplayer='{$idplayer}' AND idoggetto='$idmateriale'";
                        $resultdr = db_query($sqldr) or die(db_error(LINK));
                        $rowdr = db_fetch_assoc($resultdr);
                        output ("Bene hai tutti gli ingredienti, inizi a pronunciare le formule della pergamena.`n`n");
                        $sqle = "DELETE FROM zaino WHERE idplayer='{$idplayer}' AND (id='".$rowdr['id']."' $ingr_delete[$num_ing])";
                        db_query($sqle);
                        // inizio assegnazione incantesimo
                        $sqlsp="SELECT * FROM protoinc WHERE pergamena='$idmateriale'";
                        $resultsp=db_query($sqlsp);
                        if (db_num_rows($resultsp) == 0) {
                                //Caso in cui non ci sono oggetti nel contenitore richiesto errore di gestione
                                output("`3Questa pergamena non è collegata a nessun incantesimo, segnala il problema all'admin indicando il nome della pergamena usata.`n");
                        }else{
                                //output ("SQL : $sqlri`n`n");
                                $rowsp = db_fetch_assoc($resultsp);
                                output ("`3Dopo circa un minuto, che a te è parso interminabile, senti nella tua mente l'incantesimo pronto per essere lanciato.");
                                $sql="INSERT INTO incantesimi(acctid,nome,quanti,buff) VALUES ('$idplayer','".$rowsp['nome']."','".$rowsp['quanti']."','".addslashes($rowsp[buff])."')";
                                db_query($sql);
                                debuglog("prepara l'incantesimo {$rowsp['nome']}, utilizzando {$costo_mana_incantesimo} punti mana e {$row['costo_exp']} punti carriera");
                                $session['user']['punti_carriera'] -= $row['costo_exp'];
                                $mana -= $costo_mana_incantesimo;
                                $manaplayer -= $costo_mana_incantesimo;
                                savesetting("mana",$mana);
                                $sqlmana = "UPDATE mana SET mana_player = '".$manaplayer."' WHERE acctid='{$idplayer}' AND data='{$data}'";
                                db_query($sqlmana) or die(db_error(LINK));
                        }
                } elseif ($tutto_ok == 1) {
                        output ("Ti manca qualche ingrediente, torna quando li avrai tutti.`n`n");

                } elseif ($tutto_ok == 2) {
                        output("Non c'è a disposizione abbastanza mana per preparare questo incantesimo.`n`n");
                        output("`)Prima di preparare quest'incantesimo, occorrerà che tu o qualche altro mago ne generiate dell'altro.");
                } else {
                        output("Non hai abbastanza punti carriera a disposizione e la tua conoscenza magica non è sufficiente per preparare questo incantesimo.`n`n");
                        output("Devi esercitarti ulteriormente sulla magia prima di essere pronto per la preparazione.");
                }
        }
}
//fine laboratorio
//Arcimago, analisi punti mana
else if ($_GET[op] == "sfera") {
        output("`&Hai deciso di continuare, chiudi gli occhi e riesci a vedere tutto ciò che è accaduto nella gilda negli ultimi giorni.`n");
//        $sql = "SELECT acctid, mana_player AS mana, data FROM mana /*GROUP BY acctid*/ ORDER BY acctid DESC, data ASC";
        $sql = "SELECT a.name, a.carriera, m.acctid, m.mana_player AS mana, m.data FROM mana m LEFT JOIN accounts a ON a.acctid = m.acctid /*GROUP BY m.acctid*/ ORDER BY m.acctid ASC, data ASC";
        if ($_GET['ord']=="nome") $sql = "SELECT a.name, a.carriera, m.acctid, m.mana_player AS mana, m.data FROM mana m LEFT JOIN accounts a ON a.acctid = m.acctid /*GROUP BY m.acctid*/ ORDER BY a.login ASC, data ASC";
        if ($_GET['ord']=="carriera") $sql = "SELECT a.name, a.carriera, m.acctid, m.mana_player AS mana, m.data FROM mana m LEFT JOIN accounts a ON a.acctid = m.acctid /*GROUP BY m.acctid*/ ORDER BY a.carriera DESC, a.login ASC, data ASC";
        if ($_GET['ord']=="dk") $sql = "SELECT a.name, a.carriera, m.acctid, m.mana_player AS mana, m.data FROM mana m LEFT JOIN accounts a ON a.acctid = m.acctid /*GROUP BY m.acctid*/ ORDER BY a.reincarna DESC, a.dragonkills DESC, a.login ASC, data ASC";
        if ($_GET['ord']=="mana") $sql = "SELECT a.name, a.carriera, m.acctid, SUM(m.mana_player) AS mana, m.data FROM mana m LEFT JOIN accounts a ON a.acctid = m.acctid GROUP BY m.acctid ORDER BY mana ASC, data ASC";
        if ($_GET['ord']=="data") $sql = "SELECT a.name, a.carriera, m.acctid, SUM(m.mana_player) AS mana, m.data FROM mana m LEFT JOIN accounts a ON a.acctid = m.acctid GROUP BY m.data ORDER BY data ASC";
        $result = db_query($sql) or die(sql_error($sql));
        output("<table border=0 cellpadding=2 cellspacing=1 bgcolor='#999999'>",true);
        output("<tr class='trhead'><td><b>Nome Mago</b></td><td><b>Grado</b></td><td><b>Data</b></td><td><b>Quantità mana generata</b></td></tr>",true);
        $countrow = db_num_rows($result);
        for ($i=0; $i<$countrow; $i++){
        //for($i=0;$i<db_num_rows($result);$i++){
                $row = db_fetch_assoc($result);
                $row[data] = substr($row[data],8,2)."/".substr($row[data],5,2)."/".substr($row[data],0,4);
                /*if ($row[acctid]>0) {
                        //$sqla = "SELECT name,carriera FROM accounts WHERE acctid='".$row[acctid]."'";
                        //$resulta = db_query($sqla) or die(sql_error($sql));
                        //$rowa = db_fetch_assoc($resulta);
                } else*/if ($row[acctid]==-1) {
                        //$row[name] = "Rigenerazione Oggetti";
                        $endout .= "<tr class='".($i%2?"trdark":"trlight")."'><td>`#Rigenerazione Oggetti`0</td><td>&nbsp;</td><td>`7$row[data]`0</td><td>`&$row[mana]`0</td></tr>";
                } elseif ($row[acctid]==-2) {
                        //$row[name] = "Comune di Rafflingate";
                        $endout .= "<tr class='".($i%2?"trdark":"trlight")."'><td>`#Comune di Rafflingate`0</td><td>&nbsp;</td><td>`7$row[data]`0</td><td>`&$row[mana]`0</td></tr>";
                } elseif ($row[acctid]==-3) {
                        //$row[name] = "Altre cause";
                        $endout .= "<tr class='".($i%2?"trdark":"trlight")."'><td>`#Altre Cause`0</td><td>&nbsp;</td><td>`7$row[data]`0</td><td>`&$row[mana]`0</td></tr>";
                }
                else{
                        if ($_GET['ord']=="data") $row[name]="";
                /*if ($row[acctid]>0) {*/
                        output("<tr class='".($i%2?"trdark":"trlight")."'><td>",true);
                        output("`#$row[name]`0");
                        output("</td><td>",true);
                        if ($_GET['ord']!="data") output($arraycarriera[$row[carriera]]);
                        output("</td><td>",true);
                /*} else {
                        output("<tr class='".($i%2?"trdark":"trlight")."'><td>",true);
                        output("`#$rowa`0");
                        output("</td><td></td><td>",true);
                }*/
                    if ($_GET['ord']=="mana") $row[data]="";
                    output("`7$row[data]`0");
                    output("</td><td>",true);
                    output("`&$row[mana]`0");
                    output("</td></tr>",true);
                }
        }
        output($endout,true);
        output("</table>",true);
        debuglog("ha analizzato la generazione del mana da parte dei singoli maghi");
        if (e_rand(1, 20)==1 AND $session['user']['superuser']==0 AND !($_GET['ord'])) {
                $percent=e_rand(1,15);
                $manapersa = intval($mana*$percent/100);
                $sqlmp = "SELECT * FROM mana WHERE acctid='-3' AND data='{$data}'";;
                $resmp = db_query($sqlmp) or die(db_error(LINK));
                $manag = db_fetch_assoc($resmp);
                $manapl = $manag['mana_player'];
                if (db_num_rows($resmp) == 0) {
                //creo il campo
                    $sqloggi="INSERT INTO mana (acctid, data, mana_player) VALUES ('-3', '".$data."', '0')";
                    db_query($sqloggi) or die(db_error(LINK));
                    $manapl = 0;
                }
                $manapl -= $manapersa;
                $sqlmana = "UPDATE mana SET mana_player = '".$manapl."' WHERE acctid='-3' AND data='{$data}'";
                db_query($sqlmana) or die(db_error(LINK));
                $mana -= $manapersa;
                savesetting("mana",$mana);
                output("`n`n`^Qualcosa è andato storto, e sono andati persi $manapersa punti mana accumulati nella sfera.`n`n");
                debuglog("ha sprecato $manapersa punti mana nell'effettuare l'analisi.");
        }
        addnav("Ordinamento");
        addnav("Per nome","mago.php?op=sfera&ord=nome");
        addnav("Per carriera","mago.php?op=sfera&ord=carriera");
        addnav("Per draghi uccisi","mago.php?op=sfera&ord=dk");
        addnav("Per mana (complessivo)","mago.php?op=sfera&ord=mana");
        addnav("Per data (complessivo)","mago.php?op=sfera&ord=data");
}
//Punizione dei maghi
if ($_GET['op'] == "punisci") {
        output("`3Da quì puoi infliggere le punizioni ai maghi che, secondo te, non si impegnano abbastanza nel generare mana, ed impedire loro l'accesso al laboratorio per alcuni giorni (di gioco), massimo 9.`7.`n");
        output("<form action='mago.php?op=addpun' method='POST'>",true);
        addnav("","mago.php?op=addpun");
        output("`bPersonaggio: <input name='name'>`nGiorni di punizione: <input name='amt' size='3'>`n<input type='submit' class='button' value='Punisci'>",true);
        output("</form>",true);
}
if ($_GET['op'] == "addpun") {
        $search="%";
        for ($i=0;$i<strlen($_POST['name']);$i++){
                $search.=substr($_POST['name'],$i,1)."%";
        }
        $sql = "SELECT name,acctid,carriera,superuser FROM accounts WHERE login LIKE '$search'";
        $result = db_query($sql) or die(sql_error($sql)); //
        if($_POST['amt']>9){
                $punizione=9;
        }else{
                $punizione=$_POST['amt'];
        }
        if ($punizione=="") {
            output("`\$Errore: `^Non hai inserito il numero di giorni di punizione`0");
        }else{
            output("Conferma l'aggiunta di {$punizione} giorni di espulsione dal laboratorio a:`n`n");
            $b=0;
            $countrow = db_num_rows($result);
            for ($i=0; $i<$countrow; $i++){
            //for ($i=0;$i<db_num_rows($result);$i++){
                $row = db_fetch_assoc($result);
                if($row[carriera]>40 AND $row[carriera]<45 /*AND $row[superuser]==0*/){
                    output("<a href='mago.php?op=add2&id={$row['acctid']}&amt={$punizione}'>",true);
                    $sql2 = "SELECT giorni FROM punizioni_maghi WHERE acctid = '{$row[acctid]}'";
                    $result2 = db_query($sql2) or die(sql_error($sql2)); //
                    $row2 = db_fetch_assoc($result2);
                    if ($row2[giorni]=="") $row2[giorni]=0;
                    output($row['name']." `)({$row2[giorni]})");
                    output("</a>`n",true);
                    addnav("","mago.php?op=add2&id={$row['acctid']}&amt={$punizione}");
                    $b++;
                }
            }
            if ($b==0) output("`\$Errore: `^Non c'è nessun personaggio con questo nome o il personaggio selezionato non è un mago`0");
        }
        addnav("Torna alle punizioni","mago.php?op=punisci");
}
if ($_GET['op']=="add2"){
    $punizione=1+$_GET['amt'];
    $sql2 = "SELECT giorni FROM punizioni_maghi WHERE acctid = '{$_GET['id']}'";
    $result2 = db_query($sql2) or die(sql_error($sql2)); //
    $row2 = db_fetch_assoc($result2);
    if ($row2[giorni]=="") {
        $sqli = "INSERT INTO punizioni_maghi (acctid,giorni) VALUES ('{$_GET['id']}','{$punizione}')";
        $resulti=db_query($sqli);
        output("`3Hai inflitto la punizione!`n");
        $mailmessage = "L'`\$Arcimago`^ ti ha inflitto una punizione!`nSei stato bandito dal laboratorio per `\$".$_GET['amt']." `7 giorni.`n";
    }else{
        $sqli = "UPDATE punizioni_maghi SET giorni = '{$punizione}' WHERE acctid = '{$_GET['id']}'";
        $resulti=db_query($sqli);
        output("`3Hai modificato la punizione!`n");
        $mailmessage = "L'`\$Arcimago`^ ha cambiato la tua punizione!`nSei stato bandito dal laboratorio per `\$".$_GET['amt']." `7 giorni.`n";
    }
    $_GET['op']="";
    systemmail($_GET['id'],"`2Punizione.`2",$mailmessage);
    addnav("Punisci un altro mago","mago.php?op=punisci");
}
if ($_GET['op']=="puniti"){
    $sql = "SELECT a.acctid,a.name,b.giorni FROM accounts a, punizioni_maghi b WHERE a.acctid=b.acctid AND b.giorni>0 ORDER BY b.giorni DESC";
    $result = db_query($sql);
    output("<table border='0' cellpadding='5' cellspacing='0'>",true);
    output("<tr><td>Nome</td><td>Giorni rimanenti</td></tr>",true);
    $countrow = db_num_rows($result);
    for ($i=0; $i<$countrow; $i++){
    //for ($i=0;$i<db_num_rows($result);$i++){
        $row = db_fetch_assoc($result);
        output("<tr class='".($i%2?"trlight":"trdark")."'>",true);
        output("<td>",true);
        output("`V{$row['name']}`0",true);
        output("<td>`%".$row['giorni']."`0</td>",true);
        output("</tr>",true);
    }
    output("</table>",true);
}
page_footer();
?>