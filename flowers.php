<?php
require_once "common.php";
//global $gift;
page_header("Il Negozio dei Regali di Cassandra");
$session['user']['locazione'] = 128;
output("<span style='color: #FF66FF'>",true);
output("`c`bIl Negozio dei Regali di Cassandra`b`c");

if ($_GET['op']==""){
   if ($_POST['giftwish']==""){
      output("`n`nNon appena entri nel negozio di Cassandra, noti immediatamente la sua orda di folletti che lavora alacremente per tutto il negozio.`n");
      output("Vedi finalmente Cassandra dietro la cassa, in avida attesa dei suoi clienti.`n");
      checkday();
      output("<form action='flowers.php' method='POST'><input type='submit' class='button' value='Scegli'>`n`n",true);
      output("`@Cosa vuoi inviare?  Il costo per il regalo e la consegna è di 50 pezzi d'oro.`0`n`n");
      output("<input type='radio' name='giftwish' value='1'> Un Bouquet di Rose`n",true);
      output("<input type='radio' name='giftwish' value='2'> Un Vassoio di Pasticcini`n",true);
      output("<input type='radio' name='giftwish' value='3'> Un Biglietto d'Auguri di Natale`n",true);
      output("<input type='radio' name='giftwish' value='4'> Un Biglietto di Ringraziamento`n",true);
      output("<input type='radio' name='giftwish' value='5'> Un Biglietto di Auguri di Compleanno`n",true);
      output("<input type='radio' name='giftwish' value='6'> Una Scatola di Cioccolatini`n",true);
      output("<input type='radio' name='giftwish' value='7'> Un Biglietto di Congratulazioni`n",true);
      output("<input type='radio' name='giftwish' value='8'> Una Spazzola (quale miglior modo per dire Ti Amo?)`n",true);
      output("<input type='radio' name='giftwish' value='9'> Un Orsacchiotto di Pelouche`n",true);
      output("<input type='radio' name='giftwish' value='10'> Un Biglietto di Scuse`n",true);
      output("<input type='radio' name='giftwish' value='11'> Un Quadrifoglio</form>",true);
      addnav("","flowers.php");
      addnav("G?Torna al Giardino", "gardens.php");
      addnav("T?Torna al Villaggio","village.php");
   }else {
       if ($session['user']['gold'] < 50){
          output("`n`n`4Non possiedi abbastanza `6`iORO`i`4 per pagare il regalo. Preleva `6".(50-$session['user']['gold'])." pezzi d'oro`4 prima di tornare qui.`n");
          addnav("G?Torna al Giardino", "gardens.php");
          addnav("T?Torna al Villaggio","village.php");
       }
       else if ($_POST['giftwish'] == 1)  $gift= "Un Bouquet di Rose";
       else if ($_POST['giftwish'] == 2)  $gift= "Un Vassoio di Pasticcini";
       else if ($_POST['giftwish'] == 3)  $gift= "Un Biglietto d'Auguri di Natale";
       else if ($_POST['giftwish'] == 4)  $gift= "Un Biglietto di Ringraziamento";
       else if ($_POST['giftwish'] == 5)  $gift= "Un Biglietto di Auguri di Compleanno";
       else if ($_POST['giftwish'] == 6)  $gift= "Una Scatola di Cioccolatini";
       else if ($_POST['giftwish'] == 7)  $gift= "Un Biglietto di Congratulazioni";
       else if ($_POST['giftwish'] == 8)  $gift= "Una Spazzola";
       else if ($_POST['giftwish'] == 9)  $gift= "Un Orsacchiotto di Pelouche";
       else if ($_POST['giftwish'] == 10) $gift= "Un Biglietto di Scuse";
       else if ($_POST['giftwish'] == 11) $gift= "Un Quadrifoglio";
       if ($session['user']['gold'] > 49){
          output("`n`n`@Ma che gesto gentile da parte tua mandare ".$gift);
          addnav("Continua","flowers.php?op=Sendto&act=".$_POST['giftwish']);
          $session['user']['gold']-=50;
       }
   }
}elseif ($_GET['op']=="Sendto"){
        output("`n`n`@Per favore scrivi il nome della persona a cui vuoi mandare il regalo.`n`n");
        output("<form action='flowers.php?op=findname&act=".$_GET['act']."' method='POST'><u>A</u>: <input name='to' accesskey='o'> (nomi parziali sono ok, ti verrà chiesto di confermare il regalo prima di eseguirla).`n",true);
        output("<input type='submit' class='button' value='Invia Regalo'></form>",true);
        output("<script language='javascript'>document.getElementById('to').focus();</script>",true);
        addnav("","flowers.php?op=findname&act=".$_GET['act']);
        addnav("G?Torna al Giardino", "gardens.php");
        addnav("T?Torna al Villaggio","village.php");
        addnav("","flowers.php?op=findname&act=".$_GET['act']);
        output("`n`n");
}else if ($_GET['op']=="findname"){
      $string="%";
      for ($x=0;$x<strlen($_POST['to']);$x++){
          $string .= substr($_POST['to'],$x,1)."%";
      }
      $sql = "SELECT name,login,acctid FROM accounts WHERE name LIKE '".addslashes($string)."'";
      $result = db_query($sql);
      if (db_num_rows($result)==1){
         $row = db_fetch_assoc($result);
         output("<form action='flowers.php?op=pickname&act=".$_GET['act']."' method='POST'>",true);
         output("`6Manda un regalo a  `&".$row['name']."`6?");
         output("<input type='hidden' name='to' value='".HTMLEntities($row['login'])."'><input type='submit' class='button' value='Invia Regalo'></form>",true);
         addnav("","flowers.php?op=pickname&act=".$_GET['act']);
         addnav("G?Torna al Giardino", "gardens.php");
         addnav("T?Torna al Villaggio","village.php");
      }elseif(db_num_rows($result)>100){
          output("Gli Amministratori ti guardano disgustati e suggeriscono di restringere il campo di ricerca del nome a cui vorresti mandare il regalo!!`n`n");
          //  output("T<u>o</u>: <input name='to' accesskey='o' value='". $_POST['to'] . "'> (nomi parziali sono ok, ti verrà chiesto di confermare la transazione prima di eseguirla).`n",true);
          output("<form action='flowers.php?op=findname&act=".$_GET['act']."' method='POST'>T<u>o</u>: <input name='to' accesskey='o'> (nomi parziali sono ok, ti verrà chiesto di confermare il regalo prima di eseguirla).`n",true);
          output("<input type='submit' class='button' value='send gift'></form>",true);
          output("<script language='javascript'>document.getElementById('to').focus();</script>",true);
          addnav("","flowers.php?op=findname&act=".$_GET['act']);
          addnav("G?Torna al Giardino", "gardens.php");
          addnav("T?Torna al Villaggio","village.php");
      }elseif(db_num_rows($result)>1){
          output("<form action='flowers.php?op=pickname&act=".$_GET['act']."' method='POST'>",true);
          output("`6Manda il Regalo a <select name='to' class='input'>",true);
          $countrow = db_num_rows($result);
          for ($i=0; $i<$countrow; $i++){
          //for ($i=0;$i<db_num_rows($result);$i++){
              $row = db_fetch_assoc($result);
              //output($row[name]." ".$row[login]."`n");
              output("<option value=\"".HTMLEntities($row['login'])."\">".preg_replace("'[`].'","",$row['name'])."</option>",true);
          }
          output("</select><input type='hidden' name='name' value='$row[acctid]'><input type='submit' class='button' value='Invia Regalo'></form>",true);
          addnav("","flowers.php?op=pickname&act=$_GET[act]");
          addnav("G?Torna al Giardino", "gardens.php");
          addnav("T?Torna al Villaggio","village.php");
      }else{
          output("`6Non esiste nessun nome corrispondente a questo!  Per favore riprova.");
          addnav("G?Torna al Giardino", "gardens.php");
          addnav("T?Torna al Negozio","flowers.php");
      }
}else if($_GET['op']=="pickname") {
      if ($_POST['body']==""){
         $session['to'] = $_POST['to'];
         if ($_POST['to'] == "") $session['to'] = $_GET['to'];
         output("Puoi personalizzare il messaggio aggiungendo un tuo pensiero.`n");
         output("<form action='flowers.php?op=pickname&act=".$_GET['act']."&to=".$session['to']."' method='POST'>",true);
         output("<textarea class='input' name='body' cols='37' rows='5'>".HTMLEntities(stripslashes($_POST['body']))."</textarea>`n",true);
         output("<input type='submit' class='button' value='Invia'></form>",true);
         addnav("","flowers.php?op=pickname&act=".$_GET['act']."&to=".$session['to']);
      }else{
          $session['to'] = $_POST['to'];
          if ($_POST['to'] == "") $session['to'] = $_GET['to'];
          if ($_GET['act'] == 1) $gift= "Un Bouquet di Rose";
          elseif ($_GET['act'] == 2) $gift= "Un Vassoio di Pasticcini";
          elseif ($_GET['act'] == 3) $gift= "Un Biglietto d'Auguri di Natale";
          elseif ($_GET['act'] == 4) $gift= "Un Biglietto di Ringraziamento";
          elseif ($_GET['act'] == 5) $gift= "Un Biglietto di Compleanno";
          elseif ($_GET['act'] == 6) $gift= "Una Scatola di Cioccolatini";
          elseif ($_GET['act'] == 7) $gift= "Un Biglietto di Congratulazioni";
          elseif ($_GET['act'] == 8) $gift= "Una Spazzola (quale miglior modo per dire: Ti Amo, che regalare una bella Spazzola?)";
          elseif ($_GET['act'] == 9) $gift= "Un Orsacchiotto di Pelouche";
          elseif ($_GET['act'] == 10) $gift= "Un Biglietto di Scuse";
          elseif ($_GET['act'] == 11) $gift= "Un Quadrifoglio (Portafortuna!)";
          $sql = "SELECT name,acctid FROM accounts WHERE login='".$session['to']."'";
          $result = db_query($sql);
          //if (db_num_rows($result)==1){
             $row = db_fetch_assoc($result);
             $body = "`^Qualcuno ti ha mandato `@$gift`^.  Quando apri il messaggio, scopri che te lo manda ".$session['user']['name']."`n";
             $body .="`#".$_POST['body'];
             //systemmail($acctid,"`!`bSei stato ferito !!!`b",$mailmsg);
             systemmail($row['acctid'],"`^Hai ricevuto qualcosa di Speciale!`0",$body,$session['user']['acctid']);
             output ("`n`n`^<font size='+1'>Il tuo Regalo è stato mandato a ".$row['name']." !!</font>",true);
             addnav("G?Torna al Giardino", "gardens.php");
             addnav("T?Torna al Villaggio","village.php");
          //}
      }
}
page_footer();
?>