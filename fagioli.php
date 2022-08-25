<?php
require_once "common.php";
page_header("I Fagioli di Cedrik");
$session['user']['locazione'] = 124;
/*
Indovina i Fagioli
By Excalibur (www.ogsi.it)
version 0.1 february 18, 2006
*/

/*
Excalibur: concorso indovina il numero di fagioli contenuti in una boccia
creare tabella 'fagioli' campi 'id'(autoincrement),acctid(INT 11), guess (INT 6)
aggiungere campi "fagioli" (TINYINT 1), "fagiolitry" (TINYINT 1) alla tabella accounts
azzerare "fagiolitry" al newday
NON azzerare "fagioli" e "fagiolitry" al DK

USO VARIABILI
=============
$session['user']['fagioli'] --> 0 Non iscritto - 1 Iscritto
$session['user']['fagiolitry'] --> 0 Tentativo non fatto - 1 Tentativo fatto
$setting['statofagioli'] --> 'chiuso' concorso chiuso - 'aperto'  concorso aperto
$setting['numerofagioli'] --> numero di fagioli nella boccia
$setting['playerfagioli'] --> numero di player che partecipano al concorso
$setting['vincitorefagioli'] --> ultimo vincitore concorso
$setting['tentanumerofagioli'] --> numero di tentativi miglior risultato
$setting['tentanomefagioli'] --> nome player che ha effettuato il miglior risultato
*/
output("`c`b`(Il Concorso dei Fagioli di `%Cedrik`c`b`n");
if (getsetting("tentanomefagioli", "nessuno") != "nessuno"){
   output("`c`(Il miglior concorrente del concorso è `^`b".getsetting("tentanomefagioli", "nessuno"));
   output("`b`( con `^`b".getsetting("tentanumerofagioli", "0")."`b`( tentativi`n");
   output("L'ultimo vincitore del concorso è stato `6`b".getsetting("vincitorefagioli","")."`b`(`c`n`n");
}
$playerfagioli = getsetting("playerfagioli", 0);
$numerofagioli = getsetting("numerofagioli", 0);
if ($session['user']['fagioli'] == 0 AND getsetting("statofagioli","chiuso") != "chiuso"){
   if ($_GET['act'] == ""){
      output("`5Cedrik ti squadra per bene da capo a piedi, e notando la tue espressione attonita dice:`n`n");
      output("\"`%Così vuoi sapere di cosa stiamo parlando, eh ? Beh, niente di più semplice. La vedi quell'enorme ");
      output("boccia di vetro sul bancone ? Bene, quelli che vedi al suo interno sono fagioli.`5\"`n`nRiprende fiato ");
      output("per un attimo, quindi ingurgita un boccale della sua fetida birra, emette un rutto che fa tremare la ");
      output("boccia in questione, e riprende a parlare:`n`n\"`%Se indovinerai il numero `b`&ESATTO`b`% dei fagioli ");
      output("che contiene, vincerai il favoloso montepremi accumulato.`5\"`n`nAltra pausa, altra birra, altro ");
      output("rutto. Il boccione trema pericolosamente, dando l'impressione di vivere di vita propria, per poi ");
      output("smettere di tremare.`nIl tuo sguardo inquisitore fa capire a Cedrik che vuoi saperne di più, ");
      output("soprattutto sul montepremi, e quindi l'oste riprende a parlare non prima di aver dato fondo ad un ");
      output("terzo boccale ... senza emissioni di gas nocivi, almeno per il momento.`n`n\"`%Ah, vedo dal luccichio ");
      output("del tuo sguardo che sono riuscito a carpire la tua attenzione. Bene. Per partecipare devi pagare una ");
      output("tassa d'ingresso, tassa che andrà ad incrementare il montepremi finale, e che è la miseria di `^100 ");
      output("pezzi d'oro`%, che devi avere con te per poterti iscrivere.`5\"`n`nA questo punto estrae un taccuino, ");
      output("lo scorre velocemente e prosegue:`n`n\"`%Vedo che non ti sei ancora iscritto, se vuoi puoi farlo ");
      output("adesso, se hai l'oro necessario con te.`5\"`n`nDetto ciò rimane in attesa di una tua decisione.`n");
      if ($session['user']['gold'] > 99) addnav("`%Si, mi iscrivo","fagioli.php?act=iscrivi");
      addnav("`\$No, torna alla Locanda","inn.php");
   }elseif ($_GET['act'] == "iscrivi"){
      output("`5\"`%Ottimo !!`5\" esclama Cedrik afferando il tuo oro \"`%Ora puoi partecipare al concorso e ");
      output("tentare di indovinare quanti fagioli ci sono nella boccia.`5\"`n`nDopodichè una sorriso beffardo ");
      output("si dipinge sul suo volto. La tua espressione interrogativa lo spinge a riprendere a parlare:`n`n\"");
      output("`%Ah, mi ero dimenticato di dirti che il vincitore del concorso, oltre al premio in pezzi d'oro, ");
      output("vincerà anche tutti i fagioli, e dovrà mangiarseli tutti ... `6ah `(ah `\$ah AH AH AH ....`n");
      savesetting("playerfagioli",(getsetting("playerfagioli", 0)+1));
      $session['user']['fagioli'] = 1;
      $session['user']['gold'] -=100;
      addnav("Voglio indovinare il numero!","fagioli.php");
   }
}elseif ($session['user']['fagioli'] == 1 AND getsetting("statofagioli","chiuso") != "chiuso"){
    if ($_GET['op'] == ""){
       if ($numerofagioli == 0){
          savesetting("numerofagioli",e_rand(1,1000000));
          $sql = "INSERT INTO fagioli VALUES (0,0,0)" ;
       }
       if ($session['user']['fagiolitry'] == 1){
          output("`5\"`%Ehi, hai già tentato di indovinare il numero dei fagioli per oggi, torna domani per effettuare ");
          output("un altro tentativo!`5\"`n`nDetto ciò torna a pulire il bancone con uno straccio che più che pulire ");
          output("sposta solo lo sporco da un punto ad un altro del bancone.`n");
          addnav("Guarda tentativi fatti","fagioli.php?op=consulta");
          addnav("Torna alla Locanda","inn.php");
       } else {
          output("`5\"`%Aha, ecco un altro prode guerriero che vuole sfidare la sorte e tentare di indovinare il numero ");
          output("di fagioli contenuti nella boccia!`5\" e si gira a guardarla ");
          output("come per ripassare mentalmente il numeri di fagioli in essa contenuti.`n");
          addnav("Guarda tentativi fatti","fagioli.php?op=consulta");
          addnav("Indovina","fagioli.php?op=try");
          addnav("Provo più tardi","inn.php");
       }
    }elseif ($_GET['op'] == "try"){
    output("`5\"`%Allora, quanti sono secondo te i fagioli?`5\"`n`n");
    output("<form action='fagioli.php?op=try1' method='POST'><input name='try' value=''><input type='submit' class='button' cols='20' value='Numero Fagioli'>`n",true);
    addnav("","fagioli.php?op=try1");
    }elseif ($_GET['op'] == "try1"){
        $nfagioli = intval($_POST['try']);
        if ($nfagioli < 1 OR $nfagioli > 1000000){
           output("`5Cedrik ti guarda storto e poi dice:`n`5\"`%Il numero di fagioli è compreso tra `b`^1`%`b e ");
           output("`b`^1.000.000`%`b, tu hai detto `b`($nfagioli`%`b. Riprova con una cifra compresa tra questi due ");
           output("estremi`5\"`n`nPoi rimane in attesa che tu scelga un numero corretto.`n");
           addnav("Indovina","fagioli.php?op=try");
        }else{
           $session['user']['fagiolitry'] = 1;
           output("`5\"`%Bene, hai fatto la tua scelta, ora fammi controllare se hai indovinato o no`5\"`n`nDetto ");
           output("ciò Cedrik si allontana e consulta il suo libretto, inizia a scribacchiarci qualcosa sopra per poi ");
           output("tornare da te e ");
           $sql = "INSERT INTO fagioli VALUES (0,".$session['user']['acctid'].",$nfagioli)";
           $result = db_query ($sql) or die(db_error(LINK));
           if ($nfagioli != $numerofagioli){
              output("`5dirti con aria contrita:`n`n\"`%Mi spiace ".$session['user']['name']."`%, il tuo tentativo ");
              output("non è stato fortunato, purtroppo non hai indovinato. Posso però dirti che ");
              if (abs($nfagioli - $numerofagioli)< 1000){
                 switch (e_rand(1,3)){
                    case 1:
                       output("ci sei andato abbastanza vicino.`5\"");
                       break;
                    case 2:
                       output("non sei molto lontano.`5\"");
                       break;
                    case 3:
                       output("sei sulla buona strada.`5\"");
                       break;
                 }
              }else{
                 switch (e_rand(1,3)){
                    case 1:
                       output("sei decisamente fuori strada, amico.`5\"");
                       break;
                    case 2:
                       output("hai un brutto rapporto con i numeri.`5\"");
                       break;
                    case 3:
                       output("il numero scelto non si avvicina a quello giusto.`5\"");
                       break;
                 }
              }
              output("`n`n`5Detto ciò si allontana e torna a servire gli avventori che nel frattempo sono entrati ");
              output("nella locanda.`n");
              addnav("Torna alla Locanda","inn.php");
           }else{
              output(" ....`n`n`b`c`^<big>Non ci posso credere !!!`n`n<big> HAI INDOVINATO !!!!</big></big>`c`b`n`n",true);
              output("`%Sei riuscito a primeggiare tra i $playerfagioli guerrieri che partecipavano al concorso, e ");
              output("ti sei aggiudicato `&".($playerfagioli*100)." Pezzi d'Oro `%per la tua abilità !!`5\"`n`n");
              output("Detto ciò ti consegna la vincita con aria contrita, come se gli dispiacesse separarsi dal ");
              output("premio, e prima di tornare alla sua attività si rivolge ancora a te dicendo:`n`n\"`%Ah, stavo ");
              output("per dimenticare ... adesso dovrai mangiarteli tutti, lascia che li cucini per te e poi potrai ");
              output("ingozzarti a piacere.`5\"`n`nChiama il suo aiutante, raccoglie il boccione di fagioli e lo ");
              output("porta nelle cucine per uscirne poco dopo con la stessa boccia che però non contiene più i ");
              output("fagioli, ma una `8poltiglia `5color marroncino che posa sul bancone davanti a te porgendoti ");
              output("un grosso cucchiaio e guardandoti con aria inquisitoria.`nTu raccogli il cucchiaio ed inizi ");
              output("a far fronte al difficile compito che ti aspetta con efficienza, e dopo qualche ora la boccia ");
              output("è vuota ed il tuo stomaco pieno come un otre!`n");
              $sql="SELECT acctid, COUNT(*) AS tentativi FROM fagioli WHERE acctid = '".$session['user']['acctid']."' GROUP BY acctid";
              $result = db_query ($sql) or die(db_error(LINK));
              $row = db_fetch_assoc($result);
              $tentativi = $row['tentativi'];
              savesetting("vincitorefagioli", addslashes($session['user']['name']));
              if ($tentativi < getsetting("tentanumerofagioli", 1000000)){
                 savesetting("tentanumerofagioli", $tentativi);
                 savesetting("tentanomefagioli", addslashes($session['user']['name']));
              }
              $vincita = $playerfagioli*100;
              debuglog("`0indovina il numero di fagioli e vince $vincita pezzi d'oro");
              addnews($session['user']['name']." `2ha indovinato il numero di fagioli nella boccia con $tentativi tentativi e ha vinto `^$vincita pezzi d'oro!!");
              addnav("Torna alla Locanda","inn.php");
              savesetting("statofagioli", "chiuso");
              $session['user']['gold'] += $vincita;
              $round = ($numerofagioli >= 200000)? intval($numerofagioli/10000) : 20;
              $session['bufflist']['fagioli'] = array(
                    "startmsg"=>"`n`8Emetti un peto micidiale che asfissia `^{badguy}`8 i cui colpi perdono d'efficacia!`n`n",
                    "name"=>"`%Peto Micidiale",
                    "rounds"=>$round,
                    "wearoff"=>"L'aria mefitica del tuo peto si dissolve e i colpi di {badguy} tornano efficaci.",
                    "badguydmgmod"=>0.8,
                    "roundmsg"=>"{badguy} non riesce a colpirti a piena potenza.",
                    "activate"=>"defense"
                    );
           }

        }
    }elseif ($_GET['op'] == "consulta"){
       //legge DB per tentativi
       //solo il tentativo per altri player
       //con commento per i propri
       output("`5Cedrik ti dice che puoi vedere i tentativi fatti dagli altri guerrieri, decidendo se ordinarli per il ");
       output("numero tentato o per guerriero, a te la scelta.`n");
       addnav("Per Numero","fagioli.php?op=num");
       addnav("Per Guerriero","fagioli.php?op=war");
       addnav("Torna al Menù","fagioli.php");
    }elseif ($_GET['op'] == "war"){
       if ($_GET['op'] == "num"){
          $sql = "SELECT * FROM fagioli ORDER BY guess ASC";
       } else {
          $sql = "SELECT * FROM fagioli ORDER BY acctid ASC, guess ASC";
       }
       $result = db_query ($sql) or die(db_error(LINK));
       output("<table cellspacing=4 cellpadding=2 align='center'>", true);
       output("<tr bgcolor='#BB3333'><td align='center'><big>`b`&Numero`b`0</big></td><td align='center'><big>`b`&Player`b`0</big></td><td align='center'><big>`b`@Info`b`0</big></td>",true);
       $countrow = db_num_rows($result);
       for ($i=0; $i<$countrow; $i++){
       //for ($i=0;$i<db_num_rows($result);$i++){
          $row = db_fetch_assoc($result);
          $sqln = "SELECT name FROM accounts WHERE acctid = ".$row['acctid'];
          $resultn = db_query ($sqln) or die(db_error(LINK));
          $rown = db_fetch_assoc($resultn);
          if ($row['acctid'] == $session['user']['acctid']) {
             output("<tr bgcolor='#449944'>", true);
          } else {
             output("<tr class='" . ($i % 2?"trlight":"trdark") . "'>", true);
          }
          output("<td align='right'>`b`#".$row['guess']."`b`0</td><td>`b`@".$rown['name']."`b`0</td>",true);
          if ($row['acctid'] == $session['user']['acctid']) {
              if (abs($row['guess'] - $numerofagioli)< 1000){
                  output("<td>`b`@Vicino`b</td>",true);
              }else{
                  output("<td>`b`\$Lontano`b</td>",true);
              }
          }else{
              output("<td></td>",true);
          }
          output("</tr>",true);
       }
       output("</table>",true);
       addnav("Torna al Menù","fagioli.php");
    }elseif ($_GET['op'] == "num"){
       output("`5Tra quali valori vuoi vedere i tentativi fatti dai player ?");
       output("<form action='fagioli.php?op=num1' method='POST'>Valore (min. 0)<input name='min' value='0'><br>Valore (max. 1.000.000)<input name='max' value='1000000'><br><input type='submit' class='button' value='Valori'>`n",true);
       addnav("","fagioli.php?op=num1");
    }elseif ($_GET['op'] == "num1"){
       $min = intval($_POST['min']);
       $max = intval($_POST['max']);
       if ($min < 0 OR $max > 1000000 OR $min >= $max){
          output("`\$Uno dei valori è fuori range, devi inserire un valore superiore a `&0 `\$ per il primo valore ed ");
          output("inferiore a `&1.000.000 `\$ per il secondo.`nInserisci nuovamente i valori.`n");
          addnav("Inserisci Range","fagioli.php?op=num");
       } else {
          $sql = "SELECT * FROM fagioli WHERE guess >= $min AND guess <= $max ORDER BY guess ASC";
          $result = db_query ($sql) or die(db_error(LINK));
          output("<table cellspacing=4 cellpadding=2 align='center'>", true);
          output("<tr bgcolor='#BB3333'><td align='center'><big>`b`&Numero`b`0</big></td><td align='center'><big>`b`&Player`b`0</big></td><td align='center'><big>`b`@Info`b`0</big></td>",true);
          $countrow = db_num_rows($result);
          for ($i=0; $i<$countrow; $i++){
          //for ($i=0;$i<db_num_rows($result);$i++){
             $row = db_fetch_assoc($result);
             $sqln = "SELECT name FROM accounts WHERE acctid = ".$row['acctid'];
             $resultn = db_query ($sqln) or die(db_error(LINK));
             $rown = db_fetch_assoc($resultn);
             if ($row['acctid'] == $session['user']['acctid']) {
                output("<tr bgcolor='#449944'>", true);
             } else {
                output("<tr class='" . ($i % 2?"trlight":"trdark") . "'>", true);
             }
             output("<td align='right'>`b`#".$row['guess']."`b`0</td><td>`b`@".$rown['name']."`b`0</td>",true);
             if ($row['acctid'] == $session['user']['acctid']) {
                if (abs($row['guess'] - $numerofagioli)< 1000){
                  output("<td>`b`@Vicino`b</td>",true);
                }else{
                  output("<td>`b`\$Lontano`b</td>",true);
                }
             }else{
                output("<td></td>",true);
             }
             output("</tr>",true);
          }
          output("</table>",true);
          addnav("Torna al Menù","fagioli.php");
       }
    }
}else{
    output("`7E come ci sei capitato qui ? Avvisa gli admin delle azioni che hai compiuto per trovarti qui.`n");
    addnav("Torna al Villaggio","village.php");
}
page_footer();
?>