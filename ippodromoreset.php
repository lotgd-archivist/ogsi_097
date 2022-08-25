<?php
/*
**************************************************
* Scuderie e Ippodromo                           *
**************************************************
 @version 1.1
 @author Maximus - www.ogsi.it

 Mod per LotGD 0.9.7 composto da tre moduli da copiare nella cartella principale:

 - scuderie.php
 - ippodromo.php
 - ippodromoreset.php (questo file)

 Aggiungere le seguenti righe nel village.php
 - addnav("Scuderie","scuderie.php");
 - addnav("Ippodromo","ippodromo.php");

 Aggiungere le seguenti righe nel superuser.php
 - addnav("Gestione Gara Ippodromo","ippodromoreset.php");

 Per poter funzionare ha bisogno della mountcategory (Tabella MOUNT) pari alla variabile $mountcategory presente in scuderie.php

 Per poter funzionare ha bisogno della creazione delle seguenti tabelle:

CREATE TABLE gara (
  acctid int(11) unsigned NOT NULL default '0',
  data int(11) unsigned NOT NULL default '0',
  datagara varchar(10) NOT NULL default '',
  giro1 int(5) unsigned NOT NULL default '0',
  giro2 int(5) unsigned NOT NULL default '0',
  giro3 int(5) unsigned NOT NULL default '0',
  giro4 int(5) unsigned NOT NULL default '0'
) TYPE=MyISAM;

CREATE TABLE scommessa (
  acctid int(11) unsigned NOT NULL default '0',
  acctid2 int(11) unsigned NOT NULL default '0',
  scommessa int(5) unsigned NOT NULL default '0',
  quota int(5) unsigned NOT NULL default '0',
  DATA int(11) unsigned NOT NULL default '0',
  tipo char(1) NOT NULL default ''
) TYPE=MyISAM;

CREATE TABLE scuderie (
  acctid int(11) unsigned NOT NULL default '0',
  mountid int(11) unsigned NOT NULL default '0',
  nome varchar(20) NOT NULL default '',
  condizione int(5) unsigned NOT NULL default '0',
  sprint int(5) unsigned NOT NULL default '0',
  turni tinyint(4) unsigned NOT NULL default '50',
  iscritto int(5) unsigned NOT NULL default '0'
) TYPE=MyISAM;


*/

require_once "common.php";
page_header("Gestione Gara Ippodromo");

$operazione = $_GET['op'];
global $gara_ultima;$data_oggi;$gara_ippodromo;$scommessa_ippodromo;$allenamento_ippodromo;

$gara_ippodromo = getsetting("gara_ippodromo", 'false');
$scommessa_ippodromo = getsetting("scommessa_ippodromo", 'false');
$allenamento_ippodromo = getsetting("allenamento_ipp", 'false');

$gara_ultima = getsetting("gara_ultima", 0);
$gara_penultima = getsetting("gara_penultima", 0);
$data_oggi = time();

if ($gara_ultima==0) {
   savesetting("gara_ultima", $data_oggi);
   $gara_ultima = $data_oggi;
}

/*****************************************
 *
 * INIZIO FUNZIONI
 *
 *****************************************/

function aggiornaScuderie() {
   $sqlUpdate = "UPDATE scuderie SET
                        iscritto = 0
                       ,turni = 50";
   db_query($sqlUpdate) or die(db_error(LINK));
   if (db_affected_rows(LINK)<=0){
      output("`n`n`\$Errore`^: Si è verificato un errore nella riassegnazione turni allenamento!");
   }
}

function generaGiro($caratteristiche) {
    $fattoreImprevisto = e_rand(-1,1);
    $percorrenza = (intval(e_rand(2,4)) + $fattoreImprevisto) * intval($caratteristiche/2);
    output("`nFattore Imprevisto: ".$fattoreImprevisto." Percorrenza: ".$percorrenza);
    return $percorrenza;
}

function generaGara($gara_ultima) {
    $sql = "SELECT a.acctid, s.mountid, s.nome, s.condizione, s.sprint FROM scuderie s, mounts m, accounts a WHERE s.acctid = a.acctid and s.mountid = m.mountid and s.iscritto = 1";
    $result = db_query($sql) or die(db_error(LINK));
    $countrow = db_num_rows($result);
    for ($i=0; $i<$countrow; $i++){
    //for ($i=0;$i<db_num_rows($result);$i++){
         $percorrenzaTot = 0;
         $row = db_fetch_assoc($result);
         $condizione = $row['condizione'];
         $sprint = $row['sprint'];
         $caratteristiche = $condizione + $sprint;

         if ($row['mountid'] == 1) {
            $handicap=1.15;
            $handicap1=2;
         }
         if ($row['mountid'] == 2) {
            $handicap=1.10;
            $handicap1=1;
         }
         if ($row['mountid'] == 3) {
            $handicap=1.00;
            $handicap1=0;
         }
         // Giro 1
         $percorrenza1 = generaGiro($caratteristiche);
         $condizione -= intval(e_rand((7-$handicap1),(12+$handicap1))*$handicap);
         if ($condizione<=1) {$condizione=1;}
         $sprint -= intval(e_rand((7-$handicap1),(12+$handicap1))*$handicap);
         if ($sprint<=1) {$sprint=1;}
         $caratteristiche = $condizione + $sprint;
         // Giro 2
         $percorrenza2 = generaGiro($caratteristiche);
         $condizione -= intval(e_rand((7-$handicap1),(12+$handicap1))*$handicap);
         if ($condizione<=1) {$condizione=1;}
         $sprint -= intval(e_rand((7-$handicap1),(12+$handicap1))*$handicap);
         if ($sprint<=1) {$sprint=1;}
         $caratteristiche = $condizione + $sprint;
         // Giro 3
         $percorrenza3 = generaGiro($caratteristiche);
         $condizione -= intval(e_rand((7-$handicap1),(12+$handicap1))*$handicap);
         if ($condizione<=1) {$condizione=1;}
         $sprint -= intval(e_rand((7-$handicap1),(12+$handicap1))*$handicap);
         if ($sprint<=1) {$sprint=1;}
         $caratteristiche = $condizione + $sprint;
         // Giro 4
         $percorrenza4 = generaGiro($caratteristiche);
         $condizione -= intval(e_rand((7-$handicap1),(12+$handicap1))*$handicap);
         if ($condizione<=1) {$condizione=1;}
         $sprint -= intval(e_rand((7-$handicap1),(12+$handicap1))*$handicap);
         if ($sprint<=1) {$sprint=1;}
         //print("ID:".$row['acctid']."Condizione:".$condizione."Sprint:".$sprint."<br>");
         $data_gara = date("d-m-Y");
         $sqlInsert = "INSERT INTO gara
                                    (acctid
                                    ,data
                                    ,datagara
                                    ,giro1
                                    ,giro2
                                    ,giro3
                                    ,giro4
                            ) VALUES (
                                    '".$row['acctid']."'
                                   ,'$gara_ultima'
                                   ,'$data_gara'
                                   ,'$percorrenza1'
                                   ,'$percorrenza2'
                                   ,'$percorrenza3'
                                   ,'$percorrenza4'
                            )";
         db_query($sqlInsert) or die(db_error(LINK));
         if (db_affected_rows(LINK)<=0){
            output("`n`n`\$Errore`^: Si è verificato un errore nella gara dell'Ippodromo (step1)");
         } else {
            $sqlUpdate = "UPDATE scuderie SET
                           condizione = '$condizione'
                          ,sprint = '$sprint'
                    WHERE acctid='".$row['acctid']."'";
            db_query($sqlUpdate) or die(db_error(LINK));
            if (db_affected_rows(LINK)<=0){
                output("`n`n`\$Errore`^: Si è verificato un errore nella gara dell'Ippodromo (step2)");
            }
         }
    }

}
/*****************************************
 *
 * FINE FUNZIONI
 *
 *****************************************/


output("`\$`n`n`c<font size='+1'>ATTENZIONE !!! Stai per modificare la Tabella della Gara Ippodromo.</font>",true);
output("`nSei sicuro al 100% di volerlo fare ?`c`n`n",true);
if ($operazione==""){
        output("La corretta sequenza è Vieta Allenamento, Vieta Iscrizioni, Vieta Scommesse, Inizio Gara, Consegna Premi, Consegna Scommesse.",true);
        output("`nCon la Consegna delle Scommesse si aggiorneranno sulla tabella SETTING i seguenti valori:",true);
        output("`ngara_ultima    --> Data Odierna",true);
        output("`ngara_penultima --> Data Gara Precedente (Zero se prima)",true);

        output("`n`nVieta Allenamento inibisce la possibiltà di accedere alle Scuderie per allenare i cavalli",true);
        output("`nConsenti Allenamento autorizza la possibiltà di accedere alle Scuderie per allenare i cavalli",true);

        output("`n`nVieta Iscrizioni inibisce la possibiltà di Iscrivere cavalli",true);
        output("`nConsenti Iscrizioni autorizza i player ad Iscrivere cavalli",true);

        output("`n`nVieta Scommesse inibisce la possibiltà di fare Scommesse sia legali che clandestine",true);
        output("`nConsenti Scommesse autorizza i player a fare Scommesse",true);

        output("`n`nConfigurazione Attuale:",true);
        output("`ngara_ultima <-- $gara_ultima",true);
        output("`ngara_penultima <-- $gara_penultima",true);
        output("`nallenamento_ippodromo <-- $allenamento_ippodromo",true);
        output("`ngara_ippodromo <-- $gara_ippodromo",true);
        output("`nscommessa_ippodromo <-- $scommessa_ippodromo",true);

        addnav("G?`#Torna alla Grotta","superuser.php");
        addnav("M?`@Torna alla Mondanità","village.php");
        addnav("r?`5Controlla Proprietari","ippodromoreset.php?op=ctrlproprietari");
        addnav("I?`5Controlla Iscritti","ippodromoreset.php?op=ctrliscritti");
        addnav("C?`5Controlla Scommesse","ippodromoreset.php?op=ctrlscommesse");
        if ($allenamento_ippodromo=='false') {
           addnav("A?`7Vieta Allenamento","ippodromoreset.php?op=variabili&action=allenamento");
        } else {
           addnav("A?`7Consenti Allenamento","ippodromoreset.php?op=variabili&action=allenamento");
        }
        if ($gara_ippodromo=='false') {
           addnav("t?`7Vieta Iscrizioni","ippodromoreset.php?op=variabili&action=gara");
        } else {
           addnav("t?`7Consenti Iscrizioni","ippodromoreset.php?op=variabili&action=gara");
        }
        if ($scommessa_ippodromo=='false') {
           addnav("e?`7Vieta Scommesse","ippodromoreset.php?op=variabili&action=scommessa");
        } else {
           addnav("e?`7Consenti Scommesse","ippodromoreset.php?op=variabili&action=scommessa");
        }
        addnav("F?`\$Fai iniziare la Gara","ippodromoreset.php?op=iniziagara");
        addnav("P?`^Consegna Premi","ippodromoreset.php?op=premi");
        addnav("S?`^Consegna le Scommesse","ippodromoreset.php?op=scommesse");
}

if ($operazione=="iniziagara"){
        output("`n`nInizio Generazione Gara",true);
        generaGara($gara_ultima);
        output("`n`nFine Generazione Gara",true);
        // Maximus Inizio modifica acquisizione turni allenamento
        aggiornaScuderie();
        // Maximus Fine modifica acquisizione turni allenamento
        addnews("La gara all'Ippodromo è iniziata!");

        addnav("G?`#Torna alla Grotta","superuser.php");
        addnav("M?`@Torna alla Mondanità","village.php");
        addnav("P?`^Consegna Premi","ippodromoreset.php?op=premi");
}

if ($operazione=="premi") {
    output("`n`nInizio Generazione Premi",true);
    $sqlGara = "SELECT g.acctid, a.name, a.sex, a.gems, a.goldinbank, a.fama_mod, a.fama3mesi, (g.giro1+g.giro2+g.giro3+g.giro4) as giro FROM gara g, accounts a WHERE g.acctid = a.acctid AND data = '$gara_ultima' order by giro DESC LIMIT 3";
    $resultGara = db_query($sqlGara) or die(db_error(LINK));
/**********************/
    output("`c`b`&I Proprietari Vincenti`b`c`n");
    output("<table cellspacing=1 cellpadding=2 align='center' bgcolor='#999999'><tr class='trhead'><td>`bPosizione`b</td><td>`bGiocatore`b</td></tr>",true);
/**********************/
    $countrow1 = db_num_rows($resultGara);
    for ($i=0; $i<$countrow1; $i++){
    //for ($i=0;$i<db_num_rows($resultGara);$i++){
        $rowGara = db_fetch_assoc($resultGara);
        $accountWin = $rowGara['acctid'];
        $nameWin = $rowGara['name'];
        $sexWin = $rowGara['sex'];
        $posto = $i+1;
        if ($i==0) {
           $handicap = 10;
           $gemme = 6;
           $gold = 6000;
           //Luke: modifica contatore fama
           $fama = 3000*$rowGara['fama_mod'];
           $famanew = $fama + $rowGara['fama3mesi'];
           $message="guadagna $fama fama alla gara dei cavalli (1° Classificato). Adesso ha $famanew punti fama";
           $sqlfama = "INSERT INTO debuglog VALUES(0,now(),$accountWin,0,'".addslashes($message)."')";
           db_query($sqlfama);
           //Luke: fine mod fama
           $mailmessage = "`@Hai il cavallo vincente !!! Ti sei aggiudicato $gemme Gemme e $gold Oro (versato in banca)!!!";
           $mailmessage .= "`nGuadagni anche $fama Punti Fama!!!";
           systemmail($accountWin,"`%Complimenti !!! Il tuo cavallo è sul podio",$mailmessage);
        }
        if ($i==1) {
           $handicap = 8;
           $gemme = 4;
           $gold = 4000;
           //Luke: modifica contatore fama
           $fama = 2000*$rowGara['fama_mod'];
           $famanew = $fama + $rowGara['fama3mesi'];
           $message="guadagna $fama fama alla gara dei cavalli (1° Classificato). Adesso ha $famanew punti fama";
           $sqlfama = "INSERT INTO debuglog VALUES(0,now(),$accountWin,0,'".addslashes($message)."')";
           db_query($sqlfama);
           //Luke: fine mod fama
           $mailmessage = "`@Hai il cavallo piazzato al secondo posto !!! Ti sei aggiudicato $gemme Gemme e $gold Oro (versato in banca)!!!";
           $mailmessage .= "`nGuadagni anche $fama Punti Fama!!!";
           systemmail($accountWin,"`%Complimenti !!! Il tuo cavallo è sul podio",$mailmessage);
        }
        if ($i==2) {
           $handicap = 5;
           $gemme = 2;
           $gold = 2000;
           //Luke: modifica contatore fama
           $fama = 1000*$rowGara['fama_mod'];
           $famanew = $fama + $rowGara['fama3mesi'];
           $message="guadagna $fama fama alla gara dei cavalli (1° Classificato). Adesso ha $famanew punti fama";
           $sqlfama = "INSERT INTO debuglog VALUES(0,now(),$accountWin,0,'".addslashes($message)."')";
           db_query($sqlfama);
           //Luke: fine mod fama
           $mailmessage = "`@Hai il cavallo piazzato al terzo posto !!! Ti sei aggiudicato $gemme Gemme e $gold Oro (versato in banca)!!!";
           $mailmessage .= "`nGuadagni anche $fama Punti Fama!!!";
           systemmail($accountWin,"`%Complimenti !!! Il tuo cavallo è sul podio",$mailmessage);
        }
        addnews("`#$nameWin `#si è classificat".($sexWin?"a":"o")." al `^".$posto."° posto`# nell' `@Ippodromo di LoGD`#.`n
                   `#$nameWin `#si è aggiudicat".($sexWin?"a":"o")." `^$gemme gemme`# e `&$gold Pezzi D'Oro !!");
/**********************/
        output("<tr align='center' class='".($s%2?"trlight":"trdark")."'><td>".($i+1).".</td><td>$nameWin</td></tr>",true);
/**********************/
        if ($session['user']['acctid']==$accountWin) {
           $session['user']['goldinbank']+=$gold;
           $session['user']['gems']+=$gemme;
        } else {
           $gemme += $rowGara['gems'];
           $gold += $rowGara['goldinbank'];
           $sqlUpd = "UPDATE accounts SET gems = '$gemme', goldinbank = '$gold', fama3mesi = '$famanew' WHERE acctid = '$accountWin'";
           $resultUpd=db_query($sqlUpd);
           if (db_affected_rows(LINK)<=0){
              output("`n`n`\$Errore`^: Si è verificato un errore nella distribuzione dei premi (step1) dell'Ippodromo, segnalalo agli Admin per favore");
           }
        }
        //Modifica Excalibur per penalizzare i primi 3 classificati
        $sqlhandicap = "UPDATE scuderie SET condizione = condizione - '$handicap', sprint = sprint - '$handicap' WHERE  acctid = '$accountWin'";
        $resulthandicap=db_query($sqlhandicap);
    }
/**********************/
    output("</table>",true);
/**********************/
    output("`n`nFine Generazione Premi",true);
    addnav("G?`#Torna alla Grotta","superuser.php");
    addnav("M?`@Torna alla Mondanità","village.php");
    addnav("S?`^Consegna le Scommesse","ippodromoreset.php?op=scommesse");
}

if ($operazione=="scommesse") {
    // PREMI PER LE SCOMMESSE
    $sqlPrimo = "SELECT acctid, (giro1+giro2+giro3+giro4) as giro FROM gara WHERE data = '$gara_ultima' order by giro DESC LIMIT 1";
    $resultPrimo = db_query($sqlPrimo) or die(db_error(LINK));
/**********************/
    output("`c`b`&Gli Scommettitori Vincenti`b`c`n");
    output("<table cellspacing=1 cellpadding=2 align='center' bgcolor='#999999'><tr class='trhead' align='center'><td>`bPosizione`b</td><td>`bGiocatore`b</td><td>`bPremi`b</td><td>`bTipo`b</td></tr>",true);
/**********************/
        $rowPrimo = db_fetch_assoc($resultPrimo);
        $sqlScommessa = "SELECT acctid, scommessa, quota, tipo FROM scommessa WHERE acctid2 = '".$rowPrimo['acctid']."' AND data = '$gara_ultima' ";
        $resultScommessa = db_query($sqlScommessa) or die(db_error(LINK));
        if (db_num_rows($resultScommessa) <= 0){
            output("<tr><td colspan=4 align='center'>`&Nessuno ha scommesso sul cavallo vincente`0</td></tr>",true);
        }
        $countrow = db_num_rows($resultScommessa);
        for ($s=0; $s<$countrow; $s++){
        //for ($s=0;$s<db_num_rows($resultScommessa);$s++){
             // dati della scommessa
            $rowScommessa = db_fetch_assoc($resultScommessa);
            $accountWin = $rowScommessa['acctid'];
            $scommessa = $rowScommessa['scommessa'];
            $quota = $rowScommessa['quota'];
            $tipo = $rowScommessa['tipo'];
            // dati dell'account vincitore
            $sqlAcc = "SELECT acctid,name,gems,goldinbank,evil FROM accounts WHERE acctid = '$accountWin'";
            $resultAcc = db_query($sqlAcc) or die(db_error(LINK));
            $rowAcc = db_fetch_assoc($resultAcc);

            $gemme = 0;
            $evil = 0;

            $premioGenerato = $scommessa * $quota;

            if ($tipo=='C') {
                $evil = 20;
                $gemme = 1;
                $mailmessage = "`@Da dietro un angolo compare un losco figuro che con fare minaccioso ti si avvicina e dopo
                averti squadrato ben bene, con aria contrita ti consegna la tua vincita di $premioGenerato Pezzi d'Oro che vai subito
                a versare in banca e $gemme Gemme!!! A volte il crimine paga ...";
                systemmail($accountWin,"`%Il bookmaker clandestino ti cerca ...",$mailmessage);
            } else {
                $mailmessage = "`@Hai scommesso sul cavallo vincente !!! Ti sei aggiudicato $premioGenerato Pezzi d'Oro (versato in banca)!!!";
                systemmail($accountWin,"`%Complimenti !!! Hai vinto la scommessa",$mailmessage);
            }

/**********************/
            output("<tr align='center' class='".($s%2?"trlight":"trdark")."'><td>".($i+1).".</td><td>".$rowAcc['name']."</td><td>$gemme Gemme e $premioGenerato Pezzi d'Oro</td><td>".($tipo=='C'?"Clandestina":"Legale")."</td></tr>",true);
/**********************/
            if ($session['user']['acctid']==$accountWin) {
                $session['user']['gems']+=$gemme;
                $session['user']['goldinbank']+=$premioGenerato;
                $session['user']['evil']+=$evil;
            } else {
                $sqlUpd1 = "";
                $sqlUpd2 = "";
                $sqlUpd3 = "";
                $sqlUpd4 = "";
                $golds = $premioGenerato + $rowAcc['goldinbank'];
                $sqlUpd1 = "UPDATE accounts SET goldinbank = '$golds'";
                if ($gemme!=0) {
                    $gemme += $rowAcc['gems'];
                    $sqlUpd2 = " ,gems = '$gemme' ";
                }
                if ($evil != 0) {
                   $evil += $rowAcc['evil'];
                   $sqlUpd3 = " ,evil = '$evil' ";
                }
                $sqlUpd4 = " WHERE acctid = '$accountWin'";
                $sqlUpd = $sqlUpd1."".$sqlUpd2."".$sqlUpd3."".$sqlUpd4;
                db_query($sqlUpd) or die(db_error(LINK));
                if (db_affected_rows(LINK)<=0){
                   output("`n`n`\$Errore`^: Si è verificato un errore nella distribuzione dei premi (step2) dell'Ippodromo, segnalalo agli Admin per favore");
                }
            }
        }
/**********************/
    output("</table>",true);
/**********************/
    savesetting("gara_ultima", $data_oggi);
    if (db_num_rows($resultPrimo) > 0){
        savesetting("gara_penultima", $gara_ultima);
    }
    $gara_ultima = $data_oggi;
    addnav("G?`#Torna alla Grotta","superuser.php");
    addnav("M?`@Torna alla Mondanità","village.php");
    addnav("I?`^Torna all'Inizio","ippodromoreset.php");
}

if ($operazione=="ctrliscritti"){
    $sql = "SELECT a.name, m.mountname, s.nome, s.condizione, s.sprint, s.condizione+s.sprint as caratteristiche FROM scuderie s, mounts m, accounts a WHERE s.acctid = a.acctid and s.mountid = m.mountid and s.iscritto = 1 order by caratteristiche desc";
    output("<table cellspacing=1 cellpadding=2 align='center' bgcolor='#999999'><tr class='trhead'><td>`bPosizione`b</td><td>`bGiocatore`b</td><td>`bCavallo`b</td><td>`bNome`b</td><td>`bCondizione`b</td><td>`bSprint`b</td><td>`bTot`b</td></tr>",true);
    $result = db_query($sql) or die(db_error(LINK));
    if (db_num_rows($result)==0){
       output("<tr><td colspan=6 align='center'>`&Non ci sono cavalli iscritti`0</td></tr>",true);
    }
    $countrow = db_num_rows($result);
    for ($i=0; $i<$countrow; $i++){
    //for ($i=0;$i<db_num_rows($result);$i++){
        $row = db_fetch_assoc($result);
        output("<tr align='center' class='".($i%2?"trlight":"trdark")."'><td>".($i+1).".</td><td>".$row['name']."</td><td>".$row['mountname']."</td><td>".$row['nome']."</td><td>".$row['condizione']."</td><td>".$row['sprint']."</td><td>".$row['caratteristiche']."</td></tr>",true);
    }
    output("</table>",true);

    addnav("G?`#Torna alla Grotta","superuser.php");
    addnav("M?`@Torna alla Mondanità","village.php");
    addnav("I?`^Torna all'Inizio","ippodromoreset.php");

}

if ($operazione=="ctrlscommesse"){
    $sql = "SELECT acctid, acctid2, scommessa, quota, tipo FROM scommessa WHERE data = '$gara_ultima' order by acctid";
    output("<table cellspacing=1 cellpadding=2 align='center'  bgcolor='#999999'><tr class='trhead' align='center'><td>`bPosizione`b</td><td>`bGiocatore`b</td><td>`bGiocatore Scommesso`b</td><td>`bPuntata`b</td><td>`bQuota`b</td><td>`bTipo`b</td></tr>",true);
    $result = db_query($sql) or die(db_error(LINK));
    if (db_num_rows($result)==0){
       output("<tr><td colspan=6 align='center'>`&Non ci sono scommesse`0</td></tr>",true);
    }
    $countrow = db_num_rows($result);
    for ($i=0; $i<$countrow; $i++){
    //for ($i=0;$i<db_num_rows($result);$i++){
        $row = db_fetch_assoc($result);
        $sqlAcc = "SELECT name FROM accounts WHERE acctid = '".$row['acctid']."'";
        $resultAcc = db_query($sqlAcc) or die(db_error(LINK));
        $rowAcc = db_fetch_assoc($resultAcc);
        $name = $rowAcc[name];
        $sqlAcc = "SELECT name FROM accounts WHERE acctid = '".$row['acctid2']."'";
        $resultAcc = db_query($sqlAcc) or die(db_error(LINK));
        $rowAcc = db_fetch_assoc($resultAcc);
        $name1 = $rowAcc['name'];
        output("<tr class='".($i%2?"trlight":"trdark")."' align='center'><td>".($i+1).".</td><td>$name</td><td>$name1</td><td>".$row['scommessa']."</td><td>".$row['quota']."/1</td><td>".($row['tipo']=='C'?"Clandestina":"Legale")."</td></tr>",true);
    }
    output("</table>",true);

    addnav("G?`#Torna alla Grotta","superuser.php");
    addnav("M?`@Torna alla Mondanità","village.php");
    addnav("I?`^Torna all'Inizio","ippodromoreset.php");

}

if ($operazione=="ctrlproprietari"){
    $sql = "SELECT a.name, m.mountname, s.nome, s.iscritto, s.condizione, s.sprint, s.turni, s.condizione+s.sprint as caratteristiche FROM scuderie s, mounts m, accounts a WHERE s.acctid = a.acctid and s.mountid = m.mountid order by caratteristiche desc";
    output("<table cellspacing=1 cellpadding=2 align='center'  bgcolor='#999999'><tr class='trhead'><td>`bGiocatore`b</td><td>`bCavallo`b</td><td>`bNome`b</td><td>`bCondizione`b</td><td>`bSprint`b</td><td>`bTot`b</td><td>`bTurni Rimanenti`b</td><td>`bIscritto`b</td></tr>",true);
    $result = db_query($sql) or die(db_error(LINK));
    if (db_num_rows($result)==0){
       output("<tr><td colspan=6 align='center'>`&Le Scuderie sono vuote`0</td></tr>",true);
    }
    $countrow = db_num_rows($result);
    for ($i=0; $i<$countrow; $i++){
    //for ($i=0;$i<db_num_rows($result);$i++){
        $row = db_fetch_assoc($result);
        output("<tr align='center' class='".($i%2?"trlight":"trdark")."'><td>".$row['name']."</td><td>".$row['mountname']."</td><td>".$row['nome']."</td><td>".$row['condizione']."</td><td>".$row['sprint']."</td><td>".$row['caratteristiche']."</td><td>".$row['turni']."</td><td>".($row['iscritto']?"Si":"No")."</td></tr>",true);
    }
    output("</table>",true);

    addnav("G?`#Torna alla Grotta","superuser.php");
    addnav("M?`@Torna alla Mondanità","village.php");
    addnav("I?`^Torna all'Inizio","ippodromoreset.php");

}

if ($operazione=="variabili"){
    switch($_GET['action']){
    case "gara":
         if ($gara_ippodromo=='true') {
            savesetting("gara_ippodromo", 'false');
         } else {
            savesetting("gara_ippodromo", 'true');
         }
         break;

    case "scommessa":
         if ($scommessa_ippodromo=='true') {
            savesetting("scommessa_ippodromo", 'false');
         } else {
            savesetting("scommessa_ippodromo", 'true');
         }
         break;

    case "allenamento":
         if ($allenamento_ippodromo=='true') {
            savesetting("allenamento_ipp", 'false');
         } else {
            savesetting("allenamento_ipp", 'true');
         }
         break;
    }

   redirect('ippodromoreset.php',true);
}

rawoutput("<br><br><div style=\"text-align: right;\"><font color=\"#00FFFF\">La Scuderie e L'Ippodromo </font><font color=\"#FFFF33\">by Maximus </font> <font color=\"#00FFFF\"> - </font><font color=\"#FFFF33\"> www.ogsi.it</font><br>");
page_footer();
?>