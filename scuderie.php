<?php
/*
**************************************************
* Scuderie e Ippodromo                           *
**************************************************
 @version 1.1
 @author Maximus - www.ogsi.it

 Mod per LotGD 0.9.7 composto da tre moduli da copiare nella cartella principale:

 - scuderie.php (questo file)
 - ippodromo.php
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

$acctid = $session[user][acctid];
$sql = "SELECT s.acctid, s.mountid, s.nome, s.condizione, s.sprint, s.turni, s.iscritto, m.mountname, m.mountcostgold, m.mountcostgems  FROM scuderie s, mounts m WHERE s.acctid='$acctid' and s.mountid = m.mountid";
$result = db_query($sql) or die(db_error(LINK));
$ref = db_fetch_assoc($result);
$gara_ippodromo = getsetting("gara_ippodromo", 'false');
$allenamento_ippodromo = getsetting("allenamento_ipp", 'false');
$mountcategory = "Cavalli";

/*****************************************
 *
 * INIZIO FUNZIONI
 *
 *****************************************/
function valori($ref) {
         if ($ref['condizione']==100) $descrizione = "`3Eccellente";
         if ($ref['condizione']<100)  $descrizione = "`3Ottima";
         if ($ref['condizione']<80)   $descrizione = "`3Buona";
         if ($ref['condizione']<70)   $descrizione = "`3In Crescita";
         if ($ref['condizione']<50)   $descrizione = "`3Normale";
         if ($ref['condizione']<20)   $descrizione = "`3Scarsa";
         if ($ref['condizione']<10)   $descrizione = "`3Pessima";
         output("`n`n Condizione: $ref[condizione] $descrizione");

         if ($ref['sprint']==100) $descrizione = "`3Eccellente";
         if ($ref['sprint']<100)  $descrizione = "`3Ottimo";
         if ($ref['sprint']<80)   $descrizione = "`3Buono";
         if ($ref['sprint']<70)   $descrizione = "`3In Crescita";
         if ($ref['sprint']<50)   $descrizione = "`3Normale";
         if ($ref['sprint']<20)   $descrizione = "`3Scarso";
         if ($ref['sprint']<10)   $descrizione = "`3Pessimo";
         output("`n`n`0 Sprint : $ref[sprint] $descrizione");
}

function updatepet($ref,$acctid,$operazionePet,$turni) {
         $sql = " UPDATE scuderie set ";
         if ($operazionePet=="condizione" || $operazionePet=="condizionesprint") {
             $sql = $sql." condizione = '$ref[condizione]' ";
         }
         if ($operazionePet=="sprint" || $operazionePet=="condizionesprint") {
             if ($operazionePet=="condizionesprint") {
                 $sql = $sql.",";
             }
             $sql = $sql." sprint     = '$ref[sprint]' ";
         }
         $appo = $ref[turni]-$turni;
         $sql = $sql.", turni = '$appo' ";
         $sql = $sql." WHERE acctid='$acctid'";
         db_query($sql) or die(db_error(LINK));
}
/*****************************************
 *
 * FINE FUNZIONI
 *
 *****************************************/

$operazione = $_GET[op];
switch ($operazione) {
case "":
     page_header("Le Scuderie");
     $session['user']['locazione'] = 170;
     addcommentary();
     output("Alla periferia del villaggio, vicino la tenda della Zingara, è stata recentemente aperta una nuova area.");
     output("`nUna scritta all'ingresso dell'immenso complesso ti dà il benvenuto alle `3Scuderie`0.");
     output("`n`nIl podere è diviso in due grosse parti, una dove si possono allevare e far allenare i cavalli mentre");
     output(" l'altra è un vero e proprio `3Ippodromo`0 dove scommettere sulle corse e far partecipare il proprio destriero.");
     output("`n`nIn questo momento ti trovi al Maneggio, quì è pieno di cavalli di tutte le razze e dimensioni.");
     if (db_num_rows($result)>0){
        output("`nVedi anche il tuo {$ref['mountname']} {$ref['nome']} `0accudito dallo stalliere di fiducia,");
        output(" ti avvicini al tuo destriero e lo accarezzi sul muso, prendi una carruba dalla tasca");
        output(" e la metti vicino al suo muso. Lui la prende e ricambia il gesto di affetto con un nitrito.");
        valori($ref);
        $repaygold = round($ref['mountcostgold']*2/3,0);
        $repaygems = round($ref['mountcostgems']*2/3,0);
        if ($allenamento_ippodromo=='false') {
           output("`0`n`nLo stalliere ti ricorda che la sua vendita ti frutterebbe ben `^{$repaygold}`0 oro e `%{$repaygems}`0 gemme`n");
           if ($ref[turni]==0) {
              output("`0`nLo stalliere ti ricorda anche che per questa gara non puoi più allenare o passeggiare con il tuo destriero, hai finito i turni a disposizione.`n");
           }
           addnav("Azioni");
           if ($ref[turni]>0) {
              addnav("Allena il Cavallo","scuderie.php?op=allena");
              addnav("Passeggia a Cavallo","scuderie.php?op=passeggia");
           }
           addnav("Battezza il Cavallo","scuderie.php?op=battezza&action=intro");
           addnav("Vendi il Cavallo","scuderie.php?op=vendi&id={$ref['mountid']}");
        } else {
           output("`n`n`4`cLo stalliere ti dice che per volere della dea Burina, la dea dalla testa equina, i cavalli devono riposare.`c`0");
        }
     } else {
        output("`nMolti stallieri sono indaffarati ad accudire i propri destrieri.");
        addnav("Esamina un Cavallo","scuderie.php?op=esamina");
     }
     addnav("Luoghi");
     addnav("Vai all'Ippodromo","ippodromo.php");
     addnav("Torna al Villaggio","village.php");
     output("`n`n");
     viewcommentary("scuderie","Parla:",20,10,"dice");
     break;
     // end case ""
case "esamina":
     page_header("Il Maneggio");
     output("Ti avvicini ad uno stalliere che ti mostra i suoi cavalli in vendita.");
     $sql = "SELECT mountname,mountid,mountcategory FROM mounts WHERE mountactive=1 AND mountcategory='$mountcategory' ORDER BY mountcategory,mountcostgems,mountcostgold";
     $result = db_query($sql);
     addnav("Torna alle Scuderie","scuderie.php");
     addnav("","");
     $countrow = db_num_rows($result);
     for ($i=0; $i<$countrow; $i++){
     //for ($i=0;$i<db_num_rows($result);$i++){
         $row = db_fetch_assoc($result);
         addnav("Esamina {$row['mountname']}`0","scuderie.php?op=esaminacav&id={$row['mountid']}");
     }
     break;
     // end case "esamina"
case "esaminacav":
     page_header("Il Maneggio");
     addnav("Torna alle Scuderie","scuderie.php");
     $sql = "SELECT * FROM mounts WHERE mountid='{$_GET['id']}'";
     $result = db_query($sql);
     if (db_num_rows($result)<=0){
        output("`7\"`&Accidenti, non ci sono animali così da queste parti!`7\" urla lo stalliere!");
     }else{
        output("`7\"`&E' si, è proprio una bella bestia!`7\" commenta lo stalliere.`n`n");
        $mount = db_fetch_assoc($result);
        output("`7Animale: `&{$mount['mountname']}`n");
        output("`7Descrizione: `&{$mount['mountdesc']}`n");
        output("`7Costo: `^{$mount['mountcostgold']}`& Pezzi D'Oro, `%{$mount['mountcostgems']}`& Gemme`n");
        output("`n");
        addnav("Compra questo cavallo","scuderie.php?op=compra&id={$mount['mountid']}");
     }
     $sql = "SELECT mountname,mountid,mountcategory FROM mounts WHERE mountactive=1 AND mountcategory='Cavalli' ORDER BY mountcategory,mountcostgems,mountcostgold";
     $result = db_query($sql);
     addnav("","");
     $countrow = db_num_rows($result);
     for ($i=0; $i<$countrow; $i++){
     //for ($i=0;$i<db_num_rows($result);$i++){
         $row = db_fetch_assoc($result);
         addnav("Esamina {$row['mountname']}`0","scuderie.php?op=esaminacav&id={$row['mountid']}");
     }
     break;
     // end case "esaminacav"
case "compra":
      page_header("L'Acquisto");
      $sql = "SELECT * FROM mounts WHERE mountid='{$_GET['id']}'";
      $result = db_query($sql);
      if (db_num_rows($result)<=0){
          output("`7\"`&Accidenti, non ci sono animali così da queste parti!`7\" urla lo stalliere!");
      }else{
          $mount = db_fetch_assoc($result);
          if (
              ($session['user']['gold']) < $mount['mountcostgold']
              ||
              ($session['user']['gems']) < $mount['mountcostgems']
          ){
              output("`7Lo stalliere ti guarda storto.  \"`&'Emm, cosa credi di fare?");
              output(" Per avere {$mount['mountname']} devi darmi `^{$mount['mountcostgold']}`& oro");
              output(" e `%{$mount['mountcostgems']}`& gemme, e tu non ne hai`7\"");
          }else{
              $caratteristiche = 8 + intval($mount[mountid]*2);
              $sql = "INSERT INTO scuderie
                         (acctid
                         ,mountid
                         ,condizione
                         ,sprint
                         ,iscritto
                 ) VALUES (
                         '$acctid'
                        ,'$mount[mountid]'
                        ,'$caratteristiche'
                        ,'$caratteristiche'
                        ,'0'
                 )";
              db_query($sql) or die(db_error(LINK));
              if (db_affected_rows(LINK)<=0){
                  output("`\$Errore`^: Si è verificato un errore nell'acquisto del cavallo, riprova più tardi");
              } else {
                  output("`7Dai la somma richiesta allo stalliere e lui in cambio ti affida le redini di un
                           bel `&{$mount['mountname']}`7!`n`n");
                  $session['user']['gold']-=$mount['mountcostgold'];;
                  $session['user']['gems']-=$mount['mountcostgems'];
              }
              debuglog("ha acquistato un {$mount['mountname']} alle scuderie");
          }
      }
      addnav("Torna alle Scuderie","scuderie.php");
      break;
      // end case "compra"
case "vendi":
      page_header("La Vendita");
      $sql = "SELECT * FROM mounts WHERE mountid='{$_GET['id']}'";
      $result = db_query($sql);
      if (db_num_rows($result)<=0){
         output("`7\"`&Accidenti, non ci sono animali così da queste parti!`7\" urla lo stalliere!");
      }else{
         if ($ref['iscritto']==1) {
             output("`7Lo stalliere si avvicina e ti parla \"`&'Emm, il tuo cavallo è iscritto ad una gara, non sarebbe");
             output(" il caso di aspettare il risultato? Magari è una bestia vincente e ci ripensi.`7\"");
         } else {
              $mount = db_fetch_assoc($result);
              $sql = "DELETE FROM scuderie
                       WHERE acctid = '$acctid'";
              db_query($sql) or die(db_error(LINK));
              if (db_affected_rows(LINK)<=0){
                   output("`\$Errore`^: Si è verificato un errore nella vendita del cavallo, riprova più tardi");
              } else {
                   output("`7Lo stalliere ti dà un borsellino contenete il dovuto e si riprende l'animale che ti guarda con");
                   output(" occhi pieni di malinconia");
                   $repaygold = round($mount['mountcostgold']*2/3,0);
                   $repaygems = round($mount['mountcostgems']*2/3,0);
                   $session['user']['gold']+=$repaygold;
                   $session['user']['gems']+=$repaygems;
              }
              debuglog("ha venduto un {$mount['mountname']} alle scuderie");
         }
      }
      addnav("Torna alle Scuderie","scuderie.php");
      break;
      // end case "compra"
case "battezza":
      page_header("Il Maneggio");
      switch($_GET[action]){
      case "intro":
           if ($ref['nome'] == null || $ref['nome'] == '' || $ref['nome'] == "") {
              output("Dunque il fatidico giorno è giunto, come chiamerai il tuo {$ref['mountname']}?");
           } else {
              output("Il tuo {$ref['mountname']} ha già un nome: {$ref['nome']}`0. Sei sicuro di volerlo cambiare?");
           }
           output("<form action='scuderie.php?op=battezza&action=nomina' method='POST'>",true);
           output("<input id='input' name='nome' maxlength=20 width=5>&nbsp;<input type='submit' class='button' value='Battezza!'>`n`)(Max 20 caratteri)`0`n</form>",true);
           output("<script language='javascript'>document.getElementById('input').focus();</script>",true);
           addnav("","scuderie.php?op=battezza&action=nomina");
           break;
      case "nomina":
           $nome = $_POST[nome];
           $sqlUpd = " UPDATE scuderie set nome = '$nome' WHERE acctid='$acctid'";
           db_query($sqlUpd) or die(db_error(LINK));
           if (db_affected_rows(LINK)<=0){
              output("`n`n`\$Errore`^: Si è verificato un errore nel battesimo del cavallo, riprova più tardi");
           } else {
              output("Da oggi in poi il tuo {$ref['mountname']} si chiamerà {$nome}!");
           }
           break;
      }
      addnav("Torna alle Scuderie","scuderie.php");
      break;
      // end case "battezza"
case "allena":
      page_header("Il Maneggio");
      output("Ti avvicini al tuo {$ref['mountname']} {$ref['nome']}`n");
      $data_oggi = time();
      $futtercost =intval((50+$session[user][dragonkills]+($session[user][level]/2))*0.8);
      output("`n`0Sai che allenando il cavallo perderai dei turni.");
      output("`n`nIl cibo oggi costa `^$futtercost `0oro");
      $futtercost += intval($futtercost*0.2);
      output("`nLavare il tuo animale ti costa `^$futtercost `0oro e DUE turni");
      valori($ref);
      if ($ref[turni]<=1) {
         output("`0`n`nLo stalliere ti ricorda che per questa gara non puoi più lavare il tuo destriero, hai finito i turni a disposizione.`n");
      }
      output("`#`n`nCosa fai.");
      addnav("Tipi di Allenamento");
      addnav("Dagli da Mangiare","scuderie.php?op=mangiare");
      addnav("Fallo Correre","scuderie.php?op=corsa");
      if ($ref[turni]>1) {
         addnav("Lavalo","scuderie.php?op=lavare");
      }
      addnav("Luoghi");
      addnav("Torna alle Scuderie","scuderie.php");
      break;
      // end case "allena"
case "mangiare":
      page_header("Il Maneggio");
      $futtercost =intval((50+$session[user][dragonkills]+($session[user][level]/2))*0.8);
      if ($ref['condizione']==100) {
          output("Il tuo cavallo è in condizione perfetta e non ha bisogno di mangiare");
      } else {
           if ($session['user']['gold'] < $futtercost || $session[user][turns]<=0){
               output("Non hai abbastanza ");
               if ($session['user']['gold'] < $futtercost)
                   output("soldi ");
               else
                   output("turni ");
               output("per sfamare il tuo animale");
           } else {
               output("Dai da mangiare al tuo cavallo e...");
               $casomangiare = e_rand(0,8);
               $bonus = e_rand(1,6);
               $condizione = intval($ref['condizione']*$bonus/100);
               if ($condizione>=0 && $condizione<=1) $condizione=1;
               if ($condizione<=0 && $condizione>=-1) $condizione=-1;
               switch ($casomangiare) {
               case 0:
                    output("`n`n`^Il fieno è più nutriente del solito !! ");
                    $condizione += intval($ref['condizione']*0.2);
                    output("`n`n`2Guadagni $condizione Punti Condizione.");
                    break;
               case 1:
                    if ($ref[nome]==nul || $ref[nome]=='' || $ref[nome]=="") {
                       $nome = $ref[mountname];
                    } else {
                       $nome = $ref[nome];
                    }
                    output("`n`n`^L'inserviente delle scuderie, pagato a tua insaputa dai tuoi avversari,");
                    output(" ti ha venduto una balla di fieno al cui interno aveva celato una partita di erba");
                    output(" cavallina, che non ha fatto di certo bene al tuo $nome... ");
                    $condizione = intval($condizione*-1);
                    $condizioneOut = $condizione*-1;
                    output("`n`n`2Perdi $condizioneOut Punti Condizione.");
                    break;
               case 2:
               case 3:
               case 4:
               case 5:
               case 6:
               case 7:
               case 8:
                    output("`n`n`^Il fieno sazia il tuo cavallo");
                    output("`n`n`2Guadagni $condizione Punti Condizione.");
                    break;
               } // end case $casomangiare
               if ($condizione>=0 && $condizione<=1) $condizione=1;
               if ($condizione<=0 && $condizione>=-1) $condizione=-1;
               $ref['condizione'] += intval($condizione);
               if ($ref['condizione']>=100) $ref['condizione'] = 100;
               if ($ref['condizione']<=0) $ref['condizione'] = 1;
               updatepet($ref,$acctid,"condizione",1);
               if (db_affected_rows(LINK)<=0){
                   output("`n`n`\$Errore`^: Si è verificato un errore nell'allenamento del cavallo, riprova più tardi");
               } else {
                   output("`n`n`0Ora il tuo cavallo ha Condizione $ref[condizione]");
                   $session[user][turns]--;
                   $session['user']['gold']-=$futtercost;
               }
           }
      }
      //addnav("Allena il Cavallo","scuderie.php?op=allena");
      addnav("Luoghi");
      addnav("Torna alle Scuderie","scuderie.php");
      break;
      // end case "mangiare"
case "corsa":
      page_header("Il Maneggio");
      if ($ref['sprint']==100) {
          output("Lo sprint del tuo cavallo è perfetto, non ha bisogno di allenarsi");
      } else {
           if ($session[user][turns]<=0){
               output("Non hai abbastanza turni per far correre il tuo cavallo");
           } else {
               output("Fai correre il tuo cavallo e...");
               $casosprint = e_rand(0,8);
               $bonus = e_rand(1,6);
               $sprint = intval($ref['sprint']*$bonus/100);
               if ($sprint>=0 && $sprint<=1) $sprint=1;
               if ($sprint<=0 && $sprint>=-1) $sprint=-1;
               switch ($casosprint) {
               case 0:
                    output("`n`n`^Oggi è molto reattivo e risponde perfettamente ai tuoi ordini !! ");
                    $sprint += intval($ref['sprint']*0.2);
                    output("`n`n`2Guadagni $sprint Punti Sprint.");
                    break;
               case 1:
                    output("`n`n`^Si inbizzarrisce e non riesci a salire !! ");
                    $sprint = intval($sprint*-1);
                    $sprintOut = $sprint*-1;
                    output("`n`n`2Perdi $sprintOut Punti Sprint.");
                    break;
               case 2:
               case 3:
               case 4:
               case 5:
               case 6:
               case 7:
               case 8:
                    output("`n`n`^Riesci a fare un buon allenamento");
                    output("`n`n`2Guadagni $sprint Punti Sprint.");
                    break;
               } // end case $casosprint
               if ($sprint>=0 && $sprint<=1) $sprint=1;
               if ($sprint<=0 && $sprint>=-1) $sprint=-1;
               $ref['sprint'] += intval($sprint);
               if ($ref['sprint']>=100) $ref['sprint'] = 100;
               if ($ref['sprint']<=0) $ref['sprint'] = 1;
               updatepet($ref,$acctid,"sprint",1);
               if (db_affected_rows(LINK)<=0){
                   output("`n`n`\$Errore`^: Si è verificato un errore nell'allenamento del cavallo, riprova più tardi");
               } else {
                   output("`n`n`0Ora il tuo cavallo ha Sprint $ref[sprint]");
                   $session[user][turns]--;
               }
           }
      }
      //addnav("Allena il Cavallo","scuderie.php?op=allena");
      addnav("Luoghi");
      addnav("Torna alle Scuderie","scuderie.php");
      break;
      // end case "corsa"
case "lavare":
      page_header("Il Maneggio");
      $futtercost = intval((50+$session[user][dragonkills]+($session[user][level]/2))*0.8);
      $futtercost += intval($futtercost*0.2);
      if ($ref['condizione']==100 && $ref['sprint']==100) {
          output("Ti accorgi che il tuo cavallo è più pulito di te");
      } else {
           if ($session['user']['gold'] < $futtercost || $session[user][turns]<=1){
               output("Non hai abbastanza ");
               if ($session['user']['gold'] < $futtercost)
                   output("soldi ");
               else
                   output("turni ");
               output("per lavare il tuo animale");
           } else {
               output("Lavi il tuo cavallo e...");
               $casolavare = e_rand(0,8);
               $bonus = e_rand(1,3);
               $condizione = intval($ref['condizione']*$bonus/100);
               $sprint = intval($ref['sprint']*$bonus/100);
               if ($condizione>=0 && $condizione<=1) $condizione=1;
               if ($condizione<=0 && $condizione>=-1) $condizione=-1;
               if ($sprint>=0 && $sprint<=1) $sprint=1;
               if ($sprint<=0 && $sprint>=-1) $sprint=-1;
               $operazionePet = "";
               switch ($casolavare) {
               case 0:
                    output("`n`n`^Utilizzi un prodotto speciale che ti ha dato Merick e riesci a ripulirlo a fondo !! ");
                    if ($ref['condizione'] < 100) {
                        $condizione += intval($ref['condizione']*0.2);
                        $operazionePet = $operazionePet."condizione";
                        output("`n`n`2Guadagni $condizione Punti Condizione.");
                    }
                    if ($ref['sprint'] < 100) {
                        $sprint += intval($ref['sprint']*0.2);
                        $operazionePet = $operazionePet."sprint";
                        output("`n`n`2Guadagni $sprint Punti Sprint.");
                    }
                    break;
               case 1:
                    output("`n`n`^Dopo averlo lavato corre dentro una pozzanghera e ora è più sporco di prima !! ");
                    if ($ref['condizione'] > 1) {
                        $condizione = intval($condizione * -1);
                        $condizioneOut = $condizione*-1;
                        output("`n`n`2Perdi $condizioneOut Punti Condizione.");
                        $operazionePet = $operazionePet."condizione";
                    }
                    if ($ref['sprint'] > 1) {
                        $sprint = intval($sprint * -1);
                        $sprintOut = $sprint*-1;
                        output("`n`n`2Perdi $sprintOut Punti Sprint.");
                        $operazionePet = $operazionePet."sprint";
                    }
                    break;
               case 2:
               case 3:
               case 4:
               case 5:
               case 6:
               case 7:
               case 8:
                    output("`n`n`^Riesci a dargli una bella pulita");
                    if ($ref['condizione'] < 100) {
                        output("`n`n`2Guadagni $condizione Punti Condizione.");
                        $operazionePet = $operazionePet."condizione";
                    }
                    if ($ref['sprint'] < 100) {
                        output("`n`n`2Guadagni $sprint Punti Sprint.");
                        $operazionePet = $operazionePet."sprint";
                    }
                    break;
               } // end case $casolavare
               if ($condizione>=0 && $condizione<=1) $condizione=1;
               if ($condizione<=0 && $condizione>=-1) $condizione=-1;
               if ($sprint>=0 && $sprint<=1) $sprint=1;
               if ($sprint<=0 && $sprint>=-1) $sprint=-1;
               $ref['condizione'] += intval($condizione);
               $ref['sprint'] += intval($sprint);
               if ($ref['condizione']>=100) $ref['condizione'] = 100;
               if ($ref['sprint']>=100) $ref['sprint'] = 100;
               if ($ref['condizione']<=0) $ref['condizione'] = 1;
               if ($ref['sprint']<=0) $ref['sprint'] = 1;
               updatepet($ref,$acctid,$operazionePet,2);
               if (db_affected_rows(LINK)<=0){
                   output("`n`n`\$Errore`^: Si è verificato un errore nell'allenamento del cavallo, riprova più tardi");
               } else {
                   output("`n`n`0Ora il tuo cavallo ha Condizione $ref[condizione]");
                   output("`n`0Ora il tuo cavallo ha Sprint $ref[sprint]");
                   $session[user][turns]-=2;
                   $session['user']['gold']-=$futtercost;
               }
           }
      }
      //addnav("Allena il Cavallo","scuderie.php?op=allena");
      addnav("Luoghi");
      addnav("Torna alle Scuderie","scuderie.php");
      break;
      // end case "lavare"
case "passeggia":
      page_header("La Passeggiata");
      if ($session[user][turns]>0) {
          output("Prendi il tuo cavallo e ti fai una bella passeggiata`n");
          $passeggiata = e_rand(0,9);
          switch ($passeggiata) {
          case 0:
          case 5:
               output("`^`nTi rilassi e le tue ferite sembrano dissolversi");
               $session[user][hitpoints]=$session[user][maxhitpoints];
               $session[user][turns]--;
               break;
          case 1:
          case 6:
               if ($ref['sprint']==100) {
                   output("`^`nTi rilassi e le tue ferite sembrano dissolversi");
                   $session[user][hitpoints]=$session[user][maxhitpoints];
                   $session[user][turns]--;
               } else {
                   output("`^`nLo fai correre per un bel pò e il suo sprint migliora");
                   $bonus = e_rand(1,6);
                   $sprint = intval($ref['sprint']*$bonus/100);
                   if ($sprint>=0 && $sprint<=1) $sprint=1;
                   output("`n`n`2Guadagni $sprint Punti Sprint.");
                   $ref['sprint'] += intval($sprint);
                   if ($ref['sprint']>=100) $ref['sprint'] = 100;
                   updatepet($ref,$acctid,"sprint",1);
                   if (db_affected_rows(LINK)<=0){
                       output("`n`n`\$Errore`^: Si è verificato un errore nella passeggiata del cavallo, riprova più tardi");
                   } else {
                       output("`n`n`0Ora il tuo cavallo ha Condizione $ref[condizione]");
                       output("`n`0Ora il tuo cavallo ha Sprint $ref[sprint]");
                       $session[user][turns]--;
                   }
               }
               break;
          case 2:
          case 7:
               if ($ref['condizione']==100) {
                   output("`^`nTi rilassi e le tue ferite sembrano dissolversi");
                   $session[user][hitpoints]=$session[user][maxhitpoints];
                   $session[user][turns]--;
               } else {
                   output("`^`nLo fai correre per un bel pò e la sua condizione migliora");
                   $bonus = e_rand(1,6);
                   $condizione = intval($ref['condizione']*$bonus/100);
                   if ($condizione>=0 && $condizione<=1) $condizione=1;
                   output("`n`n`2Guadagni $condizione Punti Condizione.");
                   $ref['condizione'] += intval($condizione);
                   if ($ref['condizione']>=100) $ref['condizione'] = 100;
                   updatepet($ref,$acctid,"condizione",1);
                   if (db_affected_rows(LINK)<=0){
                       output("`n`n`\$Errore`^: Si è verificato un errore nella passeggiata del cavallo, riprova più tardi");
                   } else {
                       output("`n`n`0Ora il tuo cavallo ha Condizione $ref[condizione]");
                       output("`n`0Ora il tuo cavallo ha Sprint $ref[sprint]");
                       $session[user][turns]--;
                   }
               }
               break;
          case 3:
          case 8:
               if ($ref['sprint']==0) {
                   output("`^`nTi rilassi e le tue ferite sembrano dissolversi");
                   $session[user][hitpoints]=$session[user][maxhitpoints];
                   $session[user][turns]--;
               } else {
                   output("`^`nPurtroppo nella corsa il cavallo si fa male e perde un pò di sprint");
                   $bonus = e_rand(1,6);
                   $sprint = intval($ref['sprint']*$bonus/100);
                   if ($sprint>=0 && $sprint<=1) $sprint=1;
                   output("`n`n`2Perdi $sprint Punti Sprint.");
                   $ref['sprint'] -= intval($sprint);
                   if ($ref['sprint']<=0) $ref['sprint'] = 1;
                   updatepet($ref,$acctid,"sprint",1);
                   if (db_affected_rows(LINK)<=0){
                       output("`n`n`\$Errore`^: Si è verificato un errore nella passeggiata del cavallo, riprova più tardi");
                   } else {
                       output("`n`n`0Ora il tuo cavallo ha Condizione $ref[condizione]");
                       output("`n`0Ora il tuo cavallo ha Sprint $ref[sprint]");
                       $session[user][turns]--;
                   }
               }
               break;
          case 4:
          case 9:
               if ($ref['condizione']==0) {
                   output("`^`nTi rilassi e le tue ferite sembrano dissolversi");
                   $session[user][hitpoints]=$session[user][maxhitpoints];
                   $session[user][turns]--;
               } else {
                   output("`^`nPurtroppo nella corsa il cavallo si fa male e perde un pò di condizione");
                   $bonus = e_rand(1,6);
                   $condizione = intval($ref['condizione']*$bonus/100);
                   if ($condizione>=0 && $condizione<=1) $condizione=1;
                   output("`n`n`2Perdi $condizione Punti Condizione.");
                   $ref['condizione'] -= intval($condizione);
                   if ($ref['condizione']<=0) $ref['condizione'] = 1;
                   updatepet($ref,$acctid,"condizione",1);
                   if (db_affected_rows(LINK)<=0){
                       output("`n`n`\$Errore`^: Si è verificato un errore nella passeggiata del cavallo, riprova più tardi");
                   } else {
                       output("`n`n`0Ora il tuo cavallo ha Condizione $ref[condizione]");
                       output("`n`0Ora il tuo cavallo ha Sprint $ref[sprint]");
                       $session[user][turns]--;
                   }
               }
               break;
          }
          // end switch($passeggiata)
      } else {
          output("Non hai abbastanza tempo per fare una passeggiata a cavallo");
      }
      addnav("Torna alle Scuderie","scuderie.php");
      break;
      // end case "passeggia"

//------------------------------------------------
}
rawoutput("<br><br><div style=\"text-align: right;\"><font color=\"#00FFFF\">La Scuderie e L'Ippodromo </font><font color=\"#FFFF33\">by Maximus </font> <font color=\"#00FFFF\"> - </font><font color=\"#FFFF33\"> www.ogsi.it</font><br>");
page_footer();
?>