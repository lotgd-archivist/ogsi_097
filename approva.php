<?php
require_once("common.php");
require_once("common2.php");
page_header("Approvazione Nuovi Nick");
addnav("R?Ricarica","approva.php");
addnav("G?Torna alla Grotta","superuser.php");
if ($_GET['op'] == ""){
    output("`c`@Elenco player in attesa di approvazione.`n`n");
    $sql = "SELECT acctid,login,lastip,uniqueid,emailaddress
            FROM accounts
            WHERE consono = 0 AND emailvalidation = ''
            ORDER BY login ASC";
    $result = db_query($sql) or die(db_error(LINK));
    output("`&Legenda colonna `bPG Multi`b: `@Nick (OK) `&- `(Nick (Mute) `&- `\$Nick (BAN) `&- `%Nick (DELETED)`0`c`n");
    output("`b`H`^NOTA BENE`H`b: `3Se si vuole assegnare un `#BAN`3, non verrà approvato/cancellato nessun PG !!!`n`n");
    output("<table cellspacing=2 cellpadding=2 border='1' align='center'>",true);
    output("<tr>
            <td>`b`\$BAN`b`0</td>
            <td>`b`@Approva`b`0</td><td>`b`&Nick`b`0</td>
            <td align='center'>`b`\$Cancella`b`0</td>
            <td>`Snul`0</td>
            <td>`b`\$PG multi`b`0</td>
            <td>`b`%LastIP`b`0</td>
            <td>`b`%UniqueID`b`0</td>
            <td>`b`(Mail Address`b`0</td>
            </tr>",true);
    output("<form method='POST' action='approva.php?op=valuta'>",true);
    $countrow = db_num_rows($result);
    for ($i=0; $i<$countrow; $i++){
        $row = db_fetch_assoc($result);
        //$_POST[del] contiene il nick del player
        //$_POST[az] può valere:
        //                      'del' (cancellazione)
        //                      'oki' (approvazione)
        //                      'nul' (lascia in sospeso)
        output("<tr class='".($i%2?"trlight":"trdark")."'>
                <td><input type='radio' name='ban' value='".$row['acctid']."'></td>
                <td><input type='checkbox' name='az[".$i."]' value='oki'>
                <td><input type='text' readonly='readonly' name='del[]' value='".$row['login']."'></td>
                <td><input type='checkbox' name='az[".$i."]' value='del'></td></td>
                <td><input type='checkbox' checked='checked' name='az[".$i."]' value='nul'></td>",true);
        $sql2 = "SELECT DISTINCT acctid, login, nocomment
                FROM accounts
                WHERE login <> '".$row['login']."' AND
                (lastip = '".$row['lastip']."'
                OR uniqueid = '".$row['uniqueid']."'
                OR emailaddress = '".$row['emailaddress']."')
                ORDER BY login ASC";
        $result2 = db_query($sql2) or die(db_error(LINK));
        $max2 = db_num_rows($result2);
        $sql3 = "SELECT DISTINCT login
                FROM accounts_deleted
                WHERE (
                lastip = '".$row['lastip']."'
                OR uniqueid = '".$row['uniqueid']."'
                OR emailaddress = '".$row['emailaddress']."'
                )
                ORDER BY login ASC";
        $result3 = db_query($sql3) or die(db_error(LINK));
        $max3 = db_num_rows($result3);
        $max = max($max2,$max3);
        //print"Max2: ".$max2." - Max3: ".$max3." - Max: ".$max."<br>";
        output("<td>",true);
        for ($j=0; $j < $max; $j++){
            if ($max2 > $j){
                $row2=db_fetch_assoc($result2);
                if ($row2['nocomment'] == 0){
                    output("`@");
                    output("`b".$row2['login']."`b`& - ");
                }else{
                    output("`(");
                    output("`b".$row2['login']."`b`& - ");
                }
            }
            if ($max3 > $j){
                $row3=db_fetch_assoc($result3);
            }
            $sql4 = "SELECT *
                     FROM bans
                     WHERE uniqueid = '".$row['uniqueid']."'
                     OR ipfilter = '".$row['lastip']."'";
            $result4 = db_query($sql4) or die(db_error(LINK));
            if (db_num_rows($result4) > 0){
                output("`\$");
                output("`b".$row2['login']."`b`& - ");
                $row2=array();
            }
            if ($row3['login'] != ""){
                 output("`%`b".$row3['login']."`b`& - ");
                $row3=array();
            }
        }
        output("`0</td>",true);
        output("<td>`%".$row['lastip']."`0</td><td>`%".$row['uniqueid']."`0</td>
                <td>`(".$row['emailaddress']."`0</td>",true);
        output("</tr><tr><td  colspan='8'>",true);
        output("<textarea name='motivo[]' rows='1' cols='40'>Scrivi motivo cancellazione nick</textarea>",true);
    }
    output("</table>",true);
    addnav("","approva.php?op=valuta");
    output("`n`n<input type='submit' class='button' value='Valuta Nick'></form>`n`n",true);
}elseif ($_GET['op'] == "valuta"){
    //print_r($_POST);
    if ($_POST['ban'] == ""){
       if(getsetting("nomedb","logd")=='logd2'){
           $indirizzo = "http://www.ogsi.it/modules/newbb/viewtopic.php?topic_id=679&forum=17";
       }else{
           $indirizzo = "http://logd.ogsi.it/s1/logd/regolamento.php";
       }
$titolodel = "Nick non approvato";
$testodel = "Gli Admin di LoGD non hanno approvato il nick che hai scelto.
Prima di creare un nuovo personaggio ti consigliano di (ri)leggere attentamente
il Regolamento che puoi consultare a questo URL:
".$indirizzo."
Ci auguriamo di averti come ospite sul nostro server.

Lo Staff di LoGD

L'admin responsabile della cancellazione dice:

";
$titolooki = "Nick approvato";
$testooki = "Gli Admin di LoGD hanno approvato il nick che hai scelto.
Ti consigliano, per una miglior fruizione, di attenerti al Regolamento
che puoi consultare a questo URL:
".$indirizzo."
Ti augurano buon divertimento ed una lunga permanenza sul server di LoGD.

Lo Staff di LoGD";
       output("<big>`c`b`4In `\$ROSSO`4 gli UTENTI CANCELLATI`c`b`n</big>",true);
       output("<big>`c`b`2In `@VERDE`2 gli UTENTI APPROVATI`c`b`n`n</big>",true);
       //echo "Dimensione array: ".sizeof($_POST['del'])."<br>";
       for ($i=0; $i < sizeof($_POST['del']); $i++){
       //echo "azione: ".$_POST['az'][$i]."<br>";
              if ($_POST['az'][$i]=="del"){
                  $sqlmail = "SELECT * FROM accounts WHERE login = '".$_POST['del'][$i]."'";
                  $resultmail = db_query($sqlmail) or die(db_error(LINK));
                  $rowmail = db_fetch_assoc($resultmail);
                  // INVIO MAIL A PLAYER CANCELLATO
                  //echo "Motivo: ".stripslashes($_POST['motivo'][$i])."<br>";
                  $ok=mail(
                  $rowmail['emailaddress'],
                  $titolodel." (".$_POST['del'][$i].")",
                  $testodel.stripslashes($_POST['motivo'][$i]),
                  "From: ".$session['user']['emailaddress']
                  );
                  if ($ok=='' || $ok==1) {
                      output("`\$".($i + 1).". ".$_POST['del'][$i]."`n");
                      $sqlcanc = "INSERT INTO accounts_deleted
                                  SELECT accounts.*
                                  FROM accounts WHERE accounts.login = '".$_POST['del'][$i]."'";
                      db_query($sqlcanc);
                      $sqlcanc = "DELETE FROM accounts WHERE login = '".$_POST['del'][$i]."'";
                      db_query($sqlcanc);
                      debuglog("`\$non ha approvato il nick `&".$_POST['del'][$i]." `\$cancellandolo dal DB`0");
                      $mailmessage = "`2L'admin ".$session['user']['name']."`2 ha cancellato il nick `#".$_POST['del'][$i];
                      $mailmessage .="`n`GMotivo Cancellazione: `g".stripslashes($_POST['motivo'][$i]);
                      report(3,"`F`kNick `SNO`F: ".$_POST['del'][$i]."`k",$mailmessage,"delete_nick");
                  } else {
                      output("`\$Il comando non ha funzionato e la mail non è stata inviata. Prego ripetere.`n");
                  }
              }elseif ($_POST['az'][$i]=="oki"){
                  //output("`n`n");
                  //output("<big>`c`b`2UTENTI APPROVATI`c`b`n`n</big>",true);
                  //for ($i=0; $i < sizeof($_POST['oki']); $i++){
                      $sqlmail = "SELECT name, emailaddress FROM accounts WHERE login = '".$_POST['del'][$i]."'";
                      $resultmail = db_query($sqlmail) or die(db_error(LINK));
                      $rowmail = db_fetch_assoc($resultmail);
                      // INVIA MAIL A PLAYER APPROVATO
                      $ok=mail(
                      $rowmail['emailaddress'],
                      $titolooki." (".$_POST['del'][$i].")",
                      $testooki,
                      "From: ".$session['user']['emailaddress']
                      );
                      if ($ok=='' || $ok==1) {
                          output("`@".($i + 1).". ".$_POST['del'][$i]."`n");
                          $sqlcanc = "UPDATE accounts SET consono = 2, allowednavs='', output=\"\"
                                      WHERE login = '".$_POST['del'][$i]."'";
                          db_query($sqlcanc);
                          debuglog("`%ha approvato il nick `^".$_POST['del'][$i]."`0");
                          $mailmessage = "`3L'admin ".$session['user']['name']."`3 ha approvato il nick `^".$_POST['del'][$i];
                          report(3,"`GNick `aSI`G: ".$_POST['del'][$i],$mailmessage,"approva_nick");
                          if(getsetting("requirevalidemail",0)==0) savesetting("newplayer",addslashes($rowmail['name']));
                      } else {
                          output("`\$Il comando non ha funzionato e la mail non è stata inviata. Prego ripetere.`n");
                      }
                  }else{
              }
       }
       output("`n`n");
    }else{
       redirect("user.php?op=setupban&userid=".$_POST['ban']);
    }

}
page_footer();
?>