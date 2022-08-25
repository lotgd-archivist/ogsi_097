<?php
/*
**************************************************
* Scuderie e Ippodromo                           *
**************************************************
 @version 1.1
 @author Maximus - www.ogsi.it

 Mod per LotGD 0.9.7 composto da tre moduli da copiare nella cartella principale:

 - scuderie.php
 - ippodromo.php (questo file)
 - ippodromoreset.php

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
$operazione = $_GET[op];
$acctid = $session[user][acctid];
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

// inizio cancellazione dati ibsoleti
if ($gara_penultima!=0) {
    $sqlDelete = "DELETE FROM scommessa where data < '$gara_penultima'";
    db_query($sqlDelete) or die(db_error(LINK));
    $sqlDelete = "DELETE FROM gara where data < '$gara_penultima'";
    db_query($sqlDelete) or die(db_error(LINK));
}
// fine cancellazione dati ibsoleti


// Verifica se il player ha un cavallo
$sql = "SELECT s.acctid, s.mountid, s.nome, s.condizione, s.sprint, s.iscritto, m.mountname  FROM scuderie s, mounts m WHERE s.acctid='$acctid' and s.mountid = m.mountid";
$result = db_query($sql) or die(db_error(LINK));
$ref = db_fetch_assoc($result);
// Fine Verifica

/*****************************************
 *
 * INIZIO FUNZIONI
 *
 *****************************************/

function generaQuotazione($caratteristiche,$partecipanti,$caratteristicaMax) {
    if ($partecipanti==1) return 1;
    $quotaBase = 10;
    if ($caratteristicaMax>=190) {
        if ($caratteristiche>=20) $quotaBase = 9;
        if ($caratteristiche>=40) $quotaBase = 8;
        if ($caratteristiche>=60) $quotaBase = 7;
        if ($caratteristiche>=80) $quotaBase = 6;
        if ($caratteristiche>=100) $quotaBase = 5;
        if ($caratteristiche>=130) $quotaBase = 4;
        if ($caratteristiche>=160) $quotaBase = 3;
        if ($caratteristiche>=190)$quotaBase = 2;
        return $quotaBase;
    }
    if ($caratteristicaMax>=160) {
        if ($caratteristiche>=20) $quotaBase = 9;
        if ($caratteristiche>=40) $quotaBase = 8;
        if ($caratteristiche>=60) $quotaBase = 7;
        if ($caratteristiche>=80) $quotaBase = 6;
        if ($caratteristiche>=100) $quotaBase = 5;
        if ($caratteristiche>=120) $quotaBase = 4;
        if ($caratteristiche>=140) $quotaBase = 3;
        if ($caratteristiche>=160) $quotaBase = 2;
        return $quotaBase;
    }
    if ($caratteristicaMax>=140) {
        $quotaBase = 9;
        if ($caratteristiche>=20) $quotaBase = 8;
        if ($caratteristiche>=40) $quotaBase = 7;
        if ($caratteristiche>=60) $quotaBase = 6;
        if ($caratteristiche>=80) $quotaBase = 5;
        if ($caratteristiche>=100) $quotaBase = 4;
        if ($caratteristiche>=120) $quotaBase = 3;
        if ($caratteristiche>=140) $quotaBase = 2;
        return $quotaBase;
    }
    if ($caratteristicaMax>=120) {
        $quotaBase = 8;
        if ($caratteristiche>=20) $quotaBase = 7;
        if ($caratteristiche>=40) $quotaBase = 6;
        if ($caratteristiche>=60) $quotaBase = 5;
        if ($caratteristiche>=80) $quotaBase = 4;
        if ($caratteristiche>=100) $quotaBase = 3;
        if ($caratteristiche>=120) $quotaBase = 2;
        return $quotaBase;
    }
    if ($caratteristicaMax>=100) {
        $quotaBase = 7;
        if ($caratteristiche>=20) $quotaBase = 6;
        if ($caratteristiche>=40) $quotaBase = 5;
        if ($caratteristiche>=60) $quotaBase = 4;
        if ($caratteristiche>=80) $quotaBase = 3;
        if ($caratteristiche>=100) $quotaBase = 2;
        return $quotaBase;
    }

    $quotaBase = 2;
    return $quotaBase;
}

function generaGiro($caratteristiche) {
    $fattoreImprevisto = e_rand(-2,2);
    $percorrenza = (intval(e_rand(2,5)) - $fattoreImprevisto) * intval($caratteristiche/2);
    return $percorrenza;
}

function generaCronaca($gara_ultima) {
   $sql = "SELECT a.name, g.giro1, g.datagara, s.nome FROM accounts a, gara g, scuderie s WHERE s.acctid=g.acctid AND a.acctid = s.acctid AND data = '$gara_ultima' order by giro1 DESC LIMIT 10";
   $result = db_query($sql) or die(db_error(LINK));
   $risultatoGara = array();

   $countrow = db_num_rows($result);
   for ($i=0; $i<$countrow; $i++){
   //for ($i=0;$i<db_num_rows($result);$i++){
       $row = db_fetch_assoc($result);
       if ($i==0) {
           output("`n`n`\$Cronaca riferita alla gara svolta il $row[datagara].");
           output("`n`n`0La tensione è alle stelle, vediamo i cavalli schierati pronti a scattare al via");
           output("`nPochi secondi ed ecco..attenzione..`1TRE  `2DUE  `3UNO  `4PARTITI!`0`n");
       }
       if ($row[nome]==nul || $row[nome]=='' || $row[nome]=="") {
          if ($i==0) {output("`nIl cavallo di `3$row[name] `0è subito in testa");}
          if ($i==1) {output(" seguito da quello di `3$row[name]`0");}
          if ($i==2) {output(" subito dietro vediamo il cavallo di `3$row[name]`0");}
          if ($i==3) {output(" e di `3$row[name]`0");}
          if ($i==4) {output(" segue `3$row[name]`0,");}
          if ($i==5) {output(" in sesta posizione l'animale di `3$row[name]`0,");}
          if ($i==6) {output(" mentre dietro vediamo lo stallone di `3$row[name]`0,");}
          if ($i==7) {output(" distaccato di una lunghezza ecco `3$row[name]`0");}
          if ($i==8) {output(" seguito da vicino dal puledro di `3$row[name]`0");}
          if ($i==9) {output(" mentre in decima posizione troviamo il cavallo di `3$row[name]`0");}
       } else {
          if ($i==0) {output("`n$row[nome] `0è subito in testa");}
          if ($i==1) {output(" seguito da $row[nome]`0");}
          if ($i==2) {output(" subito dietro vediamo $row[nome]`0");}
          if ($i==3) {output(" e $row[nome]`0");}
          if ($i==4) {output(" segue $row[nome]`0,");}
          if ($i==5) {output(" in sesta posizione c'è $row[nome]`0,");}
          if ($i==6) {output(" mentre dietro vediamo $row[nome]`0,");}
          if ($i==7) {output(" distaccato di una lunghezza ecco $row[nome]`0");}
          if ($i==8) {output(" seguito da vicino da $row[nome]`0");}
          if ($i==9) {output(" mentre in decima posizione troviamo $row[nome]`0");}
       }
       $risultatoGara[1][$i][name]=$row[name];
       $risultatoGara[1][$i][mountname]=$row[mountname];
       $risultatoGara[1][$i][giro]=$row[giro1];
   }
   if (db_num_rows($result) > 0) {
       output("`n`n`5Inizia ora il secondo giro`0`n`n");
   }
   $sql = "SELECT a.name, s.nome, (g.giro1+g.giro2) as giro FROM accounts a, gara g, scuderie s WHERE s.acctid=g.acctid AND a.acctid = s.acctid AND data = '$gara_ultima' order by giro DESC LIMIT 10";
   $result = db_query($sql) or die(db_error(LINK));
   $countrow = db_num_rows($result);
   for ($i=0; $i<$countrow; $i++){
   //for ($i=0;$i<db_num_rows($result);$i++){
       $row = db_fetch_assoc($result);
       $risultatoGara[2][$i][name]=$row[name];
       $risultatoGara[2][$i][mountname]=$row[mountname];
       $risultatoGara[2][$i][giro]=$row[giro];
       if ($row[nome]==nul || $row[nome]=='' || $row[nome]=="") {
              if ($i==0) {
                 if ($risultatoGara[1][$i][name]==$risultatoGara[2][$i][name]) {
                     output("Il cavallo di `3$row[name] `0mantiene la prima posizione,");
                 } else {
                     output("Attenzione, il cavallo di `3$row[name] `0non si arrende e passa in testa,");
                 }
              }
              if ($i==1) {
                 if ($risultatoGara[1][$i][name]==$risultatoGara[2][$i][name]) {
                     output("la seconda posizione è saldamente nelle mani del destriero di `3$row[name]`0");
                 } else {
                     output("la seconda posizione è ora occupata dal destriero di `3$row[name]`0");
                 }
              }
              if ($i==2) {output(" subito dietro vediamo il cavallo di `3$row[name]`0");}
              if ($i==3) {output(" e di `3$row[name]`0");}
              if ($i==4) {output(" segue di un'incollatura `3$row[name]`0");}
              if ($i==5) {output(" pressato da vicino da `3$row[name]`0,");}
              if ($i==6) {output(" leggermente distaccato ecco il cavallo di `3$row[name]`0,");}
              if ($i==7) {output(" ad una testa di distanza vediamo `3$row[name]`0");}
              if ($i==8) {output(" subito seguito dal puledro di `3$row[name]`0");}
              if ($i==9) {output(" mentre in decima posizione troviamo il cavallo di `3$row[name]`0");}
       } else {
              if ($i==0) {
                 if ($risultatoGara[1][$i][name]==$risultatoGara[2][$i][name]) {
                     output("$row[nome] `0mantiene la prima posizione,");
                 } else {
                     output("Attenzione, $row[nome] `0non si arrende e passa in testa,");
                 }
              }
              if ($i==1) {
                 if ($risultatoGara[1][$i][name]==$risultatoGara[2][$i][name]) {
                     output("la seconda posizione è saldamente nelle mani di $row[nome]`0");
                 } else {
                     output("la seconda posizione è ora occupata di $row[nome]`0");
                 }
              }
              if ($i==2) {output(" subito dietro vediamo $row[nome]`0");}
              if ($i==3) {output(" e $row[nome]`0");}
              if ($i==4) {output(" segue di un'incollatura $row[nome]`0");}
              if ($i==5) {output(" pressato da vicino da $row[nome]`0,");}
              if ($i==6) {output(" leggermente distaccato ecco $row[nome]`0,");}
              if ($i==7) {output(" ad una testa di distanza vediamo $row[nome]`0");}
              if ($i==8) {output(" subito seguito da $row[nome]`0");}
              if ($i==9) {output(" mentre in decima posizione troviamo $row[nome]`0");}
       }
   }
   if (db_num_rows($result) > 0) {
       output("`n`n`5Eccoci giunti al terzo giro e penultimo giro !!`0`n`n");
   }
   $sql = "SELECT a.name, s.nome, (g.giro1+g.giro2+g.giro3) as giro FROM accounts a, gara g, scuderie s WHERE s.acctid=g.acctid AND a.acctid = s.acctid AND data = '$gara_ultima' order by giro DESC LIMIT 10";
   $result = db_query($sql) or die(db_error(LINK));
   $countrow = db_num_rows($result);
   for ($i=0; $i<$countrow; $i++){
   //for ($i=0;$i<db_num_rows($result);$i++){
       $row = db_fetch_assoc($result);
       $risultatoGara[3][$i][name]=$row[name];
       $risultatoGara[3][$i][mountname]=$row[mountname];
       $risultatoGara[3][$i][giro]=$row[giro];
       if ($row[nome]==nul || $row[nome]=='' || $row[nome]=="") {
              if ($i==0) {
                 if ($risultatoGara[2][$i][name]==$risultatoGara[3][$i][name]) {
                     output("Il cavallo di `3$row[name] `0mantiene la prima posizione");
                 } else {
                     output("Cambio di leader, ora a guidere la gara è il cavallo di `3$row[name]`0");
                 }
              }
              if ($i==1) {
                 if ($risultatoGara[2][$i][name]==$risultatoGara[3][$i][name]) {
                     output(" mentre il destriero di `3$row[name] `0difende tenacemente la seconda posizione");
                 } else {
                     output(" mentre in seconda posizione ora c'è il destriero di `3$row[name]`0");
                 }
              }
              if ($i==2) {output(" subito dietro vediamo il cavallo di `3$row[name]`0");}
              if ($i==3) {output(" e di `3$row[name]`0");}
              if ($i==4) {output(" in quinta posizione segue `3$row[name]`0,");}
              if ($i==5) {output(" subito dietro l'animale di `3$row[name]`0,");}
              if ($i==6) {output(" possiamo seguire lo stallone di `3$row[name]`0 in settima posizione,");}
              if ($i==7) {output(" con il cavallo di `3$row[name]`0 vicinissimo");}
              if ($i==8) {output(" che quasi si tocca con il puledro di `3$row[name]`0");}
              if ($i==9) {output(" ed in decima posizione troviamo il cavallo di `3$row[name]`0");}
       } else {
              if ($i==0) {
                 if ($risultatoGara[2][$i][name]==$risultatoGara[3][$i][name]) {
                     output("$row[nome] `0mantiene la prima posizione");
                 } else {
                     output("Cambio di leader, ora a guidere la gara è $row[nome]`0");
                 }
              }
              if ($i==1) {
                 if ($risultatoGara[2][$i][name]==$risultatoGara[3][$i][name]) {
                     output(" mentre $row[nome] `0difende tenacemente la seconda posizione");
                 } else {
                     output(" mentre in seconda posizione ora c'è $row[nome]`0");
                 }
              }
              if ($i==2) {output(" subito dietro vediamo $row[nome]`0");}
              if ($i==3) {output(" e $row[nome]`0");}
              if ($i==4) {output(" in quinta posizione segue $row[nome]`0,");}
              if ($i==5) {output(" subito dietro $row[nome]`0,");}
              if ($i==6) {output(" possiamo seguire $row[nome]`0 in settima posizione,");}
              if ($i==7) {output(" con $row[nome]`0 vicinissimo");}
              if ($i==8) {output(" che quasi si tocca con $row[nome]`0");}
              if ($i==9) {output(" ed in decima posizione troviamo $row[nome]`0");}
       }
   }
   if (db_num_rows($result) > 0) {
       output("`n`n`5Eccoci giunti all'`^Ultimo Giro`5, la corsa è emozionante, vediamo chi riuscirà ad aggiudicarsela !!`0`n`n");
   }
   $sql = "SELECT a.name, s.nome, (g.giro1+g.giro2+g.giro3+g.giro4) as giro FROM accounts a, gara g, scuderie s WHERE s.acctid=g.acctid AND a.acctid = s.acctid AND data = '$gara_ultima' order by giro DESC LIMIT 10";
   $result = db_query($sql) or die(db_error(LINK));
   $countrow = db_num_rows($result);
   for ($i=0; $i<$countrow; $i++){
   //for ($i=0;$i<db_num_rows($result);$i++){
       $row = db_fetch_assoc($result);
       if ($row[nome]==nul || $row[nome]=='' || $row[nome]=="") {
              if ($i==0) {
                 output("Il vincitore è il cavallo di `^$row[name]`0!! Complimenti all'allenatore!!");
              }
              if ($i==1) {
                 output("`nSecondo è arrivato il purosangue di `3$row[name]`0");
              }
              if ($i==2) {output("`nIl gradino più basso del podio invece è occupato dal cavallo di `3$row[name]`0");}
              if ($i==3) {output("`nApplausi anche per il quarto arrivato il ronzino di `3$row[name]`0,");}
              if ($i==4) {output(" seguono quinto `3$row[name]`0,");}
              if ($i==5) {output(" sesto `3$row[name]`0,");}
              if ($i==6) {output(" settimo `3$row[name]`0,");}
              if ($i==7) {output(" ottavo `3$row[name]`0,");}
              if ($i==8) {output(" nono `3$row[name]`0");}
              if ($i==9) {output(" e decimo `3$row[name]`0");}
       } else {
              if ($i==0) {
                 output("Il vincitore è $row[nome]`0!! Complimenti a $row[name]!!");
              }
              if ($i==1) {
                 output("`nSecondo è arrivato $row[nome]`0, proprietà di $row[name]`0");
              }
              if ($i==2) {output("`nIl gradino più basso del podio invece è occupato da $row[nome]`0, il cavallo di $row[name]`0");}
              if ($i==3) {output("`nApplausi anche per il quarto arrivato $row[nome]`0,");}
              if ($i==4) {output(" seguono quinto $row[nome]`0,");}
              if ($i==5) {output(" sesto $row[nome]`0,");}
              if ($i==6) {output(" settimo $row[nome]`0,");}
              if ($i==7) {output(" ottavo $row[nome]`0,");}
              if ($i==8) {output(" nono $row[nome]`0");}
              if ($i==9) {output(" e decimo $row[nome]`0");}
       }
   }
}

function generaGara($gara_ultima) {
    $sql = "SELECT a.acctid, s.mountid, s.condizione, s.sprint FROM scuderie s, mounts m, accounts a WHERE s.acctid = a.acctid and s.mountid = m.mountid and s.iscritto = 1";
    $result = db_query($sql) or die(db_error(LINK));
    $countrow1 = db_num_rows($result);
    for ($j=0; $j<$countrow1; $j++){
    //for ($i=0;$i<db_num_rows($result);$i++){
         $percorrenzaTot = 0;
         $row = db_fetch_assoc($result);
         $condizione = $row[condizione];
         $sprint = $row[sprint];
         $caratteristiche = $condizione + $sprint;
         if ($row[mountid] == 1) {$handicap=1.25;}
         if ($row[mountid] == 2) {$handicap=1.13;}
         if ($row[mountid] == 3) {$handicap=1.00;}
         // Giro 1
         $percorrenza1 = generaGiro($caratteristiche);
         $condizione -= intval(e_rand(8,15)*$handicap);
         if ($condizione<=1) {$condizione=1;}
         $sprint -= intval(e_rand(8,15)*$handicap);
         if ($sprint<=1) {$sprint=1;}
         print("ID:".$row['acctid']."Condizione:".$condizione."Sprint:".$sprint."<br>");
         $caratteristiche = $condizione + $sprint;
         // Giro 2
         $percorrenza2 = generaGiro($caratteristiche);
         $condizione -= intval(e_rand(8,15)*$handicap);
         if ($condizione<=1) {$condizione=1;}
         $sprint -= intval(e_rand(8,15)*$handicap);
         if ($sprint<=1) {$sprint=1;}
         print("ID:".$row['acctid']."Condizione:".$condizione."Sprint:".$sprint."<br>");
         $caratteristiche = $condizione + $sprint;
         // Giro 3
         $percorrenza3 = generaGiro($caratteristiche);
         $condizione -= intval(e_rand(8,15)*$handicap);
         if ($condizione<=1) {$condizione=1;}
         $sprint -= intval(e_rand(8,15)*$handicap);
         if ($sprint<=1) {$sprint=1;}
         print("ID:".$row['acctid']."Condizione:".$condizione."Sprint:".$sprint."<br>");
         $caratteristiche = $condizione + $sprint;
         // Giro 4
         $percorrenza4 = generaGiro($caratteristiche);
         $condizione -= intval(e_rand(8,15)*$handicap);
         if ($condizione<=1) {$condizione=1;}
         $sprint -= intval(e_rand(8,15)*$handicap);
         if ($sprint<=1) {$sprint=1;}
         print("ID:".$row['acctid']."Condizione:".$condizione."Sprint:".$sprint."<br>");
         $sqlInsert = "INSERT INTO gara
                                    (acctid
                                    ,data
                                    ,giro1
                                    ,giro2
                                    ,giro3
                                    ,giro4
                            ) VALUES (
                                    '$row[acctid]'
                                   ,'$gara_ultima'
                                   ,'$percorrenza1'
                                   ,'$percorrenza2'
                                   ,'$percorrenza3'
                                   ,'$percorrenza4'
                            )";
         db_query($sqlInsert) or die(db_error(LINK));
         if (db_affected_rows(LINK)<=0){
            output("`n`n`\$Errore`^: Si è verificato un errore nella gara dell'Ippodromo (step1), segnalalo agli Admin per favore");
         } else {
            $sqlUpdate = "UPDATE scuderie SET
                           iscritto = 0
                          ,condizione = '$condizione'
                          ,sprint = '$sprint'
                    WHERE acctid='$row[acctid]'";
            db_query($sqlUpdate) or die(db_error(LINK));
            if (db_affected_rows(LINK)<=0){
                output("`n`n`\$Errore`^: Si è verificato un errore nella gara dell'Ippodromo (step2), segnalalo agli Admin per favore");
            }
         }
    }

}
/*****************************************
 *
 * FINE FUNZIONI
 *
 *****************************************/


switch ($operazione) {
case "":
      page_header("L'Ippodromo");
      $session['user']['locazione'] = 141;
      addcommentary();
      output("`0Entri nell'ippodromo e il colpo d'occhio è fantastico.`nLa costruzione è un enorme ellisse circondata da");
      output(" tribune coperte dove è assiepato un folto pubblico.");
      output("`nSulla pista ci sono alcuni fantini che allenano i cavalli con cui correranno nella prossima gara.");
      output("`nNelle tribune noti uno sportello che raccoglie le scommesse ed un altro per iscrivere");
      output(" il proprio destriero alla gara.");
      output("`nAccanto allo sportello che accetta le iscrizioni ci sono due lavagne, una con i nomi dei cavalli iscritti");
      output(" alla prossima gara, l'altra con i nomi dei classificati della gara precedente.");
      addnav("Azioni");
      addnav("Controlla i Risultati","ippodromo.php?op=check");
      if (db_num_rows($result)>0) {
          if ($ref['iscritto']==0) {
              if ($gara_ippodromo=='false') {
                  addnav("Iscrivi il Cavallo","ippodromo.php?op=iscrizione");
              }
          } else {
              output("`n`n`3Il tuo {$ref['mountname']} {$ref['nome']} `3è iscritto alla prossima gara!`0");
          }
      }
      addnav("Controlla Iscritti","ippodromo.php?op=iscritti");
      if ($scommessa_ippodromo=='false') {
          addnav("Fai una Scommessa","ippodromo.php?op=scommetti1");
          output("`n`nSi avvicina verso di te un losco figuro Umano, ti chiede se vuoi provare il brivido delle scommesse clandestine.");
          addnav("Scommessa Clandestina","ippodromo.php?op=clandestina");
      }
      if ($gara_ippodromo=='true') {
          output("`n`n`4`cL'inserviente ti dice che per volere del dio Marciatore, il dio camminatore, non si posso iscrivere cavalli alla prossima gara.`c`0");
      }
      if ($scommessa_ippodromo=='true') {
          output("`n`n`4`cL'inserviente ti dice che per volere della dea Cirice, la dea calcolatrice, non si posso fare scommesse.`c`0");
      }
      output("`n`n");
      viewcommentary("ippodromo","Urla:",20,10,"urla");
      addnav("Luoghi");
      addnav("Torna alle Scuderie","scuderie.php");
      addnav("Torna al Villaggio","village.php");
      break;
      // end case ""
case "iscrizione":
      page_header("L'Ippodromo");
      output("`0Ti avvicini allo sportello che accetta le iscrizioni e gli dici che vuoi iscrivere il tuo");
      output(" {$ref['mountname']} alla prossima gara.`n");
      output("`0L'inserviente ti risponde che l'iscrizione costa `^500 `0Pezzi D'Oro e `%1 `0Gemma.");
      addnav("Paga l'Iscrizione","ippodromo.php?op=iscrizioneok");
      output("`#`n`nCosa fai.");
      addnav("Luoghi");
      addnav("Torna all'Ippodromo","ippodromo.php");
      addnav("Torna alle Scuderie","scuderie.php");
      addnav("Torna al Villaggio","village.php");
      break;
      // end case "no
case "iscrizioneok":
      page_header("L'Ippodromo");
      if (($session['user']['gold']) < 500 || ($session['user']['gems']) < 1) {
          output("`0L'inserviente ti guarda con aria di sufficienza \"`&'Amico, non ho tempo da perdere io.`n");
          output(" Se non hai i soldi non bloccare la fila, torna quando le tue finanze saranno sufficienti.`7\"");
      } else {
          $sql = "UPDATE scuderie SET
                         iscritto = 1
                   WHERE acctid='$acctid'";
          db_query($sql) or die(db_error(LINK));
          if (db_affected_rows(LINK)<=0){
                output("`n`n`\$Errore`^: Si è verificato un errore nell'iscrizione del cavallo, riprova più tardi");
          } else {
                output("`0L'inserviente prende il borsellino e scrive il tuo nome e il nome del tuo cavallo su una pergamena");
                output(" che passa al suo aiutante, quest'ultimo si avvicina alla lavagna e con un gessetto la aggiorna");
                output(" accuratamente.");
                $session['user']['gold']-=500;
                $session['user']['gems']-=1;
          }
      }
      addnav("Torna all'Ippodromo","ippodromo.php");
      addnav("Torna alle Scuderie","scuderie.php");
      addnav("Torna al Villaggio","village.php");
      break;
      // end case "no
case "iscritti":
      page_header("Lavagna degli Iscritti");
      output("`0Ti avvicini alla lavagna per vedere gli iscritti.`n`n");
      $sql = "SELECT a.name, m.mountname, s.nome, s.condizione+s.sprint as caratteristiche FROM scuderie s, mounts m, accounts a WHERE s.acctid = a.acctid and s.mountid = m.mountid and s.iscritto = 1 order by caratteristiche desc";
      output("<table cellspacing=1 cellpadding=2 align='center' bgcolor='#999999'><tr class='trhead' align='center'><td>`bPosizione`b</td><td>`bGiocatore`b</td><td>`bCavallo`b</td></tr>",true);
      $result = db_query($sql) or die(db_error(LINK));
      if (db_num_rows($result)==0){
        output("<tr><td colspan=3 align='center'>`&Non ci sono cavalli iscritti`0</td></tr>",true);
      }
      $countrow = db_num_rows($result);
      for ($i=0; $i<$countrow; $i++){
      //for ($i=0;$i<db_num_rows($result);$i++){
          $row = db_fetch_assoc($result);
          if ($row[nome]==nul || $row[nome]=='' || $row[nome]=="") {
              $nome = $row[mountname];
          } else {
              $nome = $row[nome];
          }
          output("<tr align='center' class='".($i%2?"trlight":"trdark")."'><td>".($i+1).".</td><td>$row[name]</td><td>$nome</td></tr>",true);
      }
      output("</table>",true);
      addnav("Torna all'Ippodromo","ippodromo.php");
      addnav("Torna alle Scuderie","scuderie.php");
      addnav("Torna al Villaggio","village.php");
      break;
case "scommetti1":
      page_header("L'ippodromo");
      output("`0Ti avvicini allo sportello che effettua le scommesse.`n");
      $sql = "SELECT s.acctid, s.acctid2, s.scommessa, s.quota FROM scommessa s WHERE s.acctid = '$acctid' and data = '$gara_ultima' AND tipo = 'L'";
      $result = db_query($sql) or die(db_error(LINK));
      $giaPuntato = false;
      if (db_num_rows($result)<>0) $giaPuntato=true;
      if ($giaPuntato) {
          $row = db_fetch_assoc($result);
          $sql = "SELECT name FROM accounts WHERE acctid = '$row[acctid2]'";
          $resultAcc = db_query($sql) or die(db_error(LINK));
          $rowAcc = db_fetch_assoc($resultAcc);
          $puntata = $row[scommessa];
          $acctid2 = $row[acctid2];
          /*
          output("`0L'inserviente ti dice che hai effettuato una scommessa per questa gara, non puoi farne altre.");
          output(" `nPuoi comunque visionare l'andamento delle quote`n`n`n");
          output("`0Se te lo fossi dimenticato hai scommesso la cifra di `^$row[scommessa] `0Pezzi D'Oro sul cavallo di `@$rowAcc[name]`0.`n");
          output("`0Quando hai puntato il cavallo era dato `3$row[quota]/1`0.`n`n");
          */
          output("`0L'inserviente ti dice che hai effettuato una scommessa per questa gara.");
          output("`n`n`0Se te lo fossi dimenticato hai scommesso la cifra di `^$puntata `0Pezzi D'Oro sul cavallo di `@$rowAcc[name]`0.`n");
          output("`0Quando hai puntato il cavallo era dato `3$row[quota]/1`0.`n`n");
          output("`nHai la possibilità di modificare la tua scommessa puntando la stessa cifra su un altro concorrente.");
          output("`nOvvimanente la quota sarà ricalcoltata in base al nuovo andamento`n`n");
      } else {
          output("`0L'inserviente ti chiede il nome del cavallo sul quale vuoi effettuare la tua puntata.`n`n");
      }
      $sql = "SELECT s.acctid, a.name, m.mountname, s.nome, s.condizione+s.sprint as caratteristiche FROM scuderie s, mounts m, accounts a WHERE s.acctid = a.acctid and s.mountid = m.mountid and s.iscritto = 1 order by caratteristiche desc";
      output("<table cellspacing=1 cellpadding=2 align='center'  bgcolor='#999999'><tr class='trhead' align='center'><td>`bPosizione`b</td><td>`bGiocatore`b</td><td>`bCavallo`b</td><td>`bQuotazione`b</td></tr>",true);
      $result = db_query($sql) or die(db_error(LINK));
      if (db_num_rows($result)==0){
          output("<tr><td colspan=4 align='center'>`&Non ci sono cavalli iscritti`0</td></tr>",true);
      } else {
          $caratteristicaMax = 0;
          $countrow = db_num_rows($result);
          for ($i=0; $i<$countrow; $i++){
          //for ($i=0;$i<db_num_rows($result);$i++){
               $row = db_fetch_assoc($result);
               if ($i==0) $caratteristicaMax=$row[caratteristiche];
               $quotazione = generaQuotazione($row[caratteristiche],db_num_rows($result),$caratteristicaMax);
               if ($row[nome]==nul || $row[nome]=='' || $row[nome]=="") {
                   $nome = $row[mountname];
               } else {
                   $nome = $row[nome];
               }
               if ($giaPuntato) {
                   $link = "ippodromo.php?op=riscommetti&id=$row[acctid]&quota=$quotazione&oldid=$acctid2";
                   addnav("",$link);
                   output("<tr align='center' class='".($i%2?"trlight":"trdark")."'><td><a href=$link>".($i+1)."</a>.</td><td><a href=$link>$row[name]</a></td><td><a href=$link>$nome</a></td><td><a href=$link>$quotazione/1</a></td></tr>",true);
                   // output("<tr align='center' class='".($i%2?"trlight":"trdark")."'><td>".($i+1).".</td><td>$row[name]</td><td>$nome</td><td>$quotazione/1</td></tr>",true);
               } else {
                   $link = "ippodromo.php?op=scommetti2&id=$row[acctid]&quota=$quotazione";
                   addnav("",$link);
                   output("<tr align='center' class='".($i%2?"trlight":"trdark")."'><td><a href=$link>".($i+1)."</a>.</td><td><a href=$link>$row[name]</a></td><td><a href=$link>$nome</a></td><td><a href=$link>$quotazione/1</a></td></tr>",true);
               }
          }
      }
      output("</table>",true);
      addnav("Torna all'Ippodromo","ippodromo.php");
      addnav("Torna alle Scuderie","scuderie.php");
      addnav("Torna al Villaggio","village.php");
      break;
case "scommetti2":
      page_header("L'ippodromo");
      $id = $_GET[id];
      $quota = $_GET[quota];
      output("`0L'inserviente prende nota del cavallo, dopodichè ti chiede quanto vuoi puntare.");
      addnav("Scommesse");
      addnav("Scommetti 500","ippodromo.php?op=scommetti3&gold=500&id=$id&quota=$quota");
      addnav("Scommetti 1000","ippodromo.php?op=scommetti3&gold=1000&id=$id&quota=$quota");
      addnav("Scommetti 5000","ippodromo.php?op=scommetti3&gold=5000&id=$id&quota=$quota");
      addnav("Luoghi");
      addnav("Torna all'Ippodromo","ippodromo.php");
      addnav("Torna alle Scuderie","scuderie.php");
      addnav("Torna al Villaggio","village.php");
      break;
case "scommetti3":
      page_header("L'ippodromo");
      $gold = $_GET[gold];
      $id = $_GET[id];
      $quota = $_GET[quota];
      if ($session[user][gold] < $gold) {
          output("`0L'inserviente ti guarda con aria di sufficienza \"`&'Amico, non ho tempo da perdere io.`n");
          output(" Se non hai i soldi non bloccare la fila, torna quando le tue finanze saranno sufficienti.`7\"");
      } else {
          $sql = "INSERT INTO scommessa
                             (acctid
                             ,acctid2
                             ,scommessa
                             ,quota
                             ,data
                             ,tipo
                     ) VALUES (
                             '$acctid'
                            ,'$id'
                            ,'$gold'
                            ,'$quota'
                            ,'$gara_ultima'
                            ,'L'
                     )";
          db_query($sql) or die(db_error(LINK));
          if (db_affected_rows(LINK)<=0){
              output("`\$Errore`^: Si è verificato un errore nella scommessa, riprova più tardi");
          } else {
              output("`0L'inserviente prende la tua puntata e scrive sulla pergamena, accanto al tuo nome e al cavallo che hai");
              output(" scelto, la somma che hai puntato.");
              $session['user']['gold']-=$gold;
          }
      }
      addnav("Torna all'Ippodromo","ippodromo.php");
      addnav("Torna alle Scuderie","scuderie.php");
      addnav("Torna al Villaggio","village.php");
      break;
case "riscommetti":
      page_header("L'ippodromo");
      $oldid = $_GET[oldid];
      $id = $_GET[id];
      $quota = $_GET[quota];
      if ($id==$oldid) {
          output("`0L'inserviente ti guarda con aria di sufficienza \"`&'Amico, non ho tempo da perdere io.`n");
          output(" Se non sai leggere fatti dare ripetizioni da Javella, tze.`7\"");
          output("`n`n`0Ti scosti dalla fila e ripensando a ciò che ha detto il bookmaker ti accorgi che volevi ripuntare sullo ");
          output("stesso cavallo... Fischiettando ti allontani il più presto possibile sperando che quel tizio non vada a raccontare ");
          output(" in giro di questa tua disavventura.");
      } else {
          $sql = "UPDATE scommessa
                  SET acctid2 = '$id'
                     ,quota = $quota
                  WHERE acctid = '$acctid'
                    AND data = '$gara_ultima'
                    AND tipo = 'L'";
          db_query($sql) or die(db_error(LINK));
          if (db_affected_rows(LINK)<=0){
             output("`\$Errore`^: Si è verificato un errore nel rifare la scommessa, riprova più tardi");
          } else {
             output("`0L'inserviente cancella la tua precedente puntata e aggiorna la pergamena  con il nuovo cavallo che hai");
             output(" scelto e la nuova quotazione.");
          }
      }
      addnav("Torna all'Ippodromo","ippodromo.php");
      addnav("Torna alle Scuderie","scuderie.php");
      addnav("Torna al Villaggio","village.php");
      break;
case "check":
      page_header("L'Ippodromo");
      $dataCheck = getsetting("gara_penultima", 0);
      $sql = "SELECT a.name, m.mountname, s.nome, g.datagara, (g.giro1+g.giro2+g.giro3+g.giro4) as giro FROM mounts m, accounts a, gara g, scuderie s WHERE g.acctid = a.acctid AND g.acctid=s.acctid AND s.mountid=m.mountid AND data = '$dataCheck' order by giro DESC";
      output("`c`b`&I Cavalli Vincenti`b`c`n");
      output("<table cellspacing=1 cellpadding=2 align='center'  bgcolor='#999999'><tr class='trhead'><td>`bPosizione`b</td><td>`bGiocatore`b</td><td>`bCavallo`b</td></tr>",true);
      $result = db_query($sql) or die(db_error(LINK));
      if (db_num_rows($result)==0){
        output("<tr><td colspan=3 align='center'>`&Recentemente non sono state disputate gare`0</td></tr>",true);
      } else {
        addnav("Cronaca","ippodromo.php?op=cronaca");
      }
      $countrow = db_num_rows($result);
      for ($i=0; $i<$countrow; $i++){
      //for ($i=0;$i<db_num_rows($result);$i++){
          $row = db_fetch_assoc($result);
          $datagara = $row[datagara];
          if ($row[nome]==nul || $row[nome]=='' || $row[nome]=="") {
              $nome = $row[mountname];
          } else {
              $nome = $row[nome];
          }
          output("<tr class='".($i%2?"trlight":"trdark")."'><td>".($i+1).".</td><td>$row[name]</td><td>$nome</td></tr>",true);
      }
      output("</table>",true);
      if (db_num_rows($result)!=0){
        output("`n`nDati riferiti alla gara svolta il $datagara",true);
      } else {
        addnav("Cronaca","ippodromo.php?op=cronaca");
      }
      addnav("Torna all'Ippodromo","ippodromo.php");
      addnav("Torna alle Scuderie","scuderie.php");
      addnav("Torna al Villaggio","village.php");
      break;
      // end case "no"
case "cronaca":
      page_header("Cronaca");
      $dataCheck = getsetting("gara_penultima", 0);
      generaCronaca($dataCheck);
      addnav("Torna all'Ippodromo","ippodromo.php");
      addnav("Torna alle Scuderie","scuderie.php");
      addnav("Torna al Villaggio","village.php");
      break;
case "clandestina":
      page_header("L'ippodromo");
      output("`0Fai un cenno di assenso all'Umano e vi appartate in un luogo lontano da occhi indiscreti.`n");
      $sql = "SELECT s.acctid, s.acctid2, s.scommessa, s.quota FROM scommessa s WHERE s.acctid = '$acctid' and s.data = '$gara_ultima' AND tipo = 'C'";
      $result = db_query($sql) or die(db_error(LINK));
      $giaPuntato = false;
      if (db_num_rows($result)<>0) $giaPuntato=true;
      if ($giaPuntato) {
          $row = db_fetch_assoc($result);
          $sql = "SELECT name FROM accounts WHERE acctid = '$row[acctid2]'";
          $resultAcc = db_query($sql) or die(db_error(LINK));
          $rowAcc = db_fetch_assoc($resultAcc);
          output("`0L'Umano ti scruta e ti dice che hai già scommesso con lui. Non accetta altre puntate.");
          output(" `nSe ti và puoi comunque visionare l'andamento delle quote`n`n`n");
          output("`0Se te lo fossi dimenticato hai scommesso la cifra di `^$row[scommessa] `0Pezzi D'Oro sul cavallo di `@$rowAcc[name]`0.`n");
          output("`0Quando hai puntato il cavallo era dato `3$row[quota]/1`0.`n`n");
      } else {
          output("`0Quando siete lontani dalla folla ti mostra una pergamena con i cavalli iscritti. Su quale vuoi scommettere?`n`n");
      }

      $sql = "SELECT s.acctid, a.name, m.mountname, s.nome, s.condizione+s.sprint as caratteristiche FROM scuderie s, mounts m, accounts a WHERE s.acctid = a.acctid and s.mountid = m.mountid and s.iscritto = 1 order by caratteristiche desc";
      output("<table cellspacing=1 cellpadding=2 align='center'  bgcolor='#999999'><tr class='trhead' align='center'><td>`bPosizione`b</td><td>`bGiocatore`b</td><td>`bCavallo`b</td><td>`bQuotazione`b</td></tr>",true);
      $result = db_query($sql) or die(db_error(LINK));
      if (db_num_rows($result)==0){
          output("<tr><td colspan=4 align='center'>`&Non ci sono cavalli iscritti`0</td></tr>",true);
      } else {
          $caratteristicaMax = 0;
          $countrow = db_num_rows($result);
          for ($i=0; $i<$countrow; $i++){
          //for ($i=0;$i<db_num_rows($result);$i++){
               $row = db_fetch_assoc($result);
               if ($i==0) $caratteristicaMax=$row[caratteristiche];
               $quotazione = generaQuotazione($row[caratteristiche],db_num_rows($result),$caratteristicaMax);
               if ($row[nome]==nul || $row[nome]=='' || $row[nome]=="") {
                   $nome = $row[mountname];
               } else {
                   $nome = $row[nome];
               }
               if ($giaPuntato) {
                   output("<tr align='center' class='".($i%2?"trlight":"trdark")."'><td>".($i+1).".</td><td>$row[name]</td><td>$nome</td><td>$quotazione/1</td></tr>",true);
               } else {
                   $link = "ippodromo.php?op=clandestina2&id=$row[acctid]&quota=$quotazione";
                   addnav("",$link);
                   output("<tr align='center' class='".($i%2?"trlight":"trdark")."'><td><a href=$link>".($i+1)."</a>.</td><td><a href=$link>$row[name]</a></td><td><a href=$link>$nome</a></td><td><a href=$link>$quotazione/1</a></td></tr>",true);
               }
          }
      }
      output("</table>",true);
      addnav("Torna all'Ippodromo","ippodromo.php");
      addnav("Torna alle Scuderie","scuderie.php");
      addnav("Torna al Villaggio","village.php");
      break;
case "clandestina2":
      page_header("L'ippodromo");
      $id = $_GET[id];
      $quota = $_GET[quota];
      output("`0L'Umano prende nota del cavallo, dopodichè ti chiede quanto vuoi puntare.");
      addnav("Scommesse");
      addnav("Scommetti 500","ippodromo.php?op=clandestina3&gold=500&id=$id&quota=$quota");
      addnav("Scommetti 1000","ippodromo.php?op=clandestina3&gold=1000&id=$id&quota=$quota");
      addnav("Scommetti 5000","ippodromo.php?op=clandestina3&gold=5000&id=$id&quota=$quota");
      addnav("Luoghi");
      addnav("Torna all'Ippodromo","ippodromo.php");
      addnav("Torna alle Scuderie","scuderie.php");
      addnav("Torna al Villaggio","village.php");
      break;
case "clandestina3":
      page_header("L'ippodromo");
      $gold = $_GET[gold];
      $id = $_GET[id];
      $quota = $_GET[quota];
      if ($session[user][gold] < $gold) {
          output("`0L'Umano ti guarda con aria di sfida \"`&'Ue Ue guaglió ccà nisciuno è fesso...
                   Nun se fa niente pe' ssenza niente... Torna cuanno avrai o' denaro.`7\"");
      } else {
          $scommessa = e_rand(0,5);
          switch ($scommessa) {
             case 0;
                output("`0L'Umano si avvicina con fare furtivo e con un rapido gesto indica qualcosa dietro la tua schiena gridando
                `n\"`@`b<big><big>Drrrago Verde !!`b</big></big>`0\"`n Memore di avventure in cui il Drago riempiva i tuoi incubi
                scatti come una molla lasciando cadere l'oro e impugnando la spada ... per scoprire che l'unico `@`bDrago`b`0 è il
                losco figuro che e' scomparso con i tuoi $gold pezzi d'oro.",true);
                //output("`0L'Umano prende il tuo oro e si dilegua prima che tu capisca cosa sta succedendo ... `6Hai perso i tuoi soldi`0...");
                $session['user']['gold']-=$gold;
                break;
             case 1;
             case 2;
             case 3;
             case 4;
             case 5;
                $sql = "INSERT INTO scommessa
                                   (acctid
                                   ,acctid2
                                   ,scommessa
                                   ,quota
                                   ,data
                                   ,tipo
                           ) VALUES (
                                   '$acctid'
                                  ,'$id'
                                  ,'$gold'
                                  ,'$quota'
                                  ,'$gara_ultima'
                                  ,'C'
                           )";
                db_query($sql) or die(db_error(LINK));
                if (db_affected_rows(LINK)<=0){
                    output("`\$Errore`^: Si è verificato un errore nella scommessa clandestina, riprova più tardi");
                } else {
                    output("`0L'Umano ritira la tua puntata, ti stringe la mano e ti saluta dicendo che si farà vivo");
                    output(" lui se dovessi essere così fortunato da vincere ...");
                    $session['user']['gold']-=$gold;
                }
                break;
          }
      }
      addnav("Torna all'Ippodromo","ippodromo.php");
      addnav("Torna alle Scuderie","scuderie.php");
      addnav("Torna al Villaggio","village.php");
      break;
case "no":
      page_header("L'Ippodromo");
      output("`0Ti incammini verso l'ippodromo ma un inserviente ti ferma e ti dice che non è agibile.");
      addnav("Torna alle Scuderie","scuderie.php");
      addnav("Torna al Villaggio","village.php");
      break;
      // end case "no"
//------------------------------------------------
}
rawoutput("<br><br><div style=\"text-align: right;\"><font color=\"#00FFFF\">La Scuderie e L'Ippodromo </font><font color=\"#FFFF33\">by Maximus </font> <font color=\"#00FFFF\"> - </font><font color=\"#FFFF33\"> www.ogsi.it</font><br>");
page_footer();
?>