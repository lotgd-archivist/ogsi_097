<?php
require_once "common.php";
page_header("La Vecchia Banca");
$session['user']['locazione'] = 108;
if($session[user][euro]<1)output('<center><script type="text/javascript"><!--
google_ad_client = "pub-8533296456863947";
google_ad_width = 234;
google_ad_height = 60;
google_ad_format = "234x60_as";
google_ad_type = "text_image";
google_ad_channel ="8745036574";
google_color_border = "6F3C1B";
google_color_bg = "78B749";
google_color_link = "6F3C1B";
google_color_text = "063E3F";
google_color_url = "000000";
//--></script>
<script type="text/javascript"
  src="http://pagead2.googlesyndication.com/pagead/show_ads.js">
</script></center>',true);

output("`^`c`bLa Vecchia Banca`b`c`6");
if ($_GET['op']==""){
  checkday();
  output("Un uomo basso con un abito immacolato ti saluta da dietro i suoi occhiali da lettura.`n`n");
  output("\"`5Salute buon uomo,`6\" rispondi, \"`5Potrei chiedere a quanto ammonta il mio saldo in questo splendido giorno?`6\"`n`n");
  output("Il banchiere borbotta, \"`3Hmm, ".$session['user']['name']."`3, vediamo...`6\" mentre scruta una pagina ");
  output("nel suo registro.  ");
    if ($session['user']['goldinbank']>=0){
        output("\"`3Aah, sì, ecco qui.  Hai `^".$session['user']['goldinbank']." pezzi d'oro`3 nella nostra ");
        output("prestigiosa banca. C´è altro che posso fare per te?`6\"");
    }else{
        output("\"`3Aah, sì, ecco.  Hai un `&debito`3 di `^".abs($session['user']['goldinbank'])." pezzi d'oro`3 nella nostra ");
        output("prestigiosa banca. C´è altro che posso fare per te?`6\"");
    }
}else if($_GET['op']=="transfer"){
    output("`6`bTrasferisci Denaro`b:`n");
    if ($session['user']['goldinbank']>=0){
        output("Puoi trasferire solo `^".getsetting("transferperlevel",25)."`6 pezzi d'oro per livello del destinatario.`n");
        $maxout = $session['user']['level']*getsetting("maxtransferout",25);
        output("Non puoi traferire più di `^$maxout`6 pezzi d'oro in totale. ");
        if ($session['user']['amountouttoday'] > 0) {
            output("(Hai già trasferito `^".$session['user']['amountouttoday']."`6 pezzi d'oro oggi)`n`n");
        } else output("`n`n");
        output("<form action='bank.php?op=transfer2' method='POST'>Quanto vuoi <u>t</u>rasferire: <input name='amount' id='amount' accesskey='h' width='5'>`n",true);
        output("T<u>o</u>: <input name='to' accesskey='o'> (vanno bene nomi incompleti, ti verrà chiesta conferma prima di completare la transazione).`n",true);
        output("<input type='submit' class='button' value='Anteprima Trasferimento'></form>",true);
        output("<script language='javascript'>document.getElementById('amount').focus();</script>",true);
        addnav("","bank.php?op=transfer2");
    }else{
        output("`6Il piccolo banchiere ti dice che si rifiuta di trasferire denaro per qualcuno che ha un debito.");
    }
}else if($_GET['op']=="transfer2"){
    output("`6`bConferma Trasferimento`b:`n");
    $string="%";
    for ($x=0;$x<strlen($_POST['to']);$x++){
        $string .= substr($_POST['to'],$x,1)."%";
    }
    $sql = "SELECT name,login FROM accounts WHERE name LIKE '".addslashes($string)."'";
    $result = db_query($sql);
    $amt = abs((int)$_POST['amount']);
    if (db_num_rows($result)==1){
        $row = db_fetch_assoc($result);
        output("<form action='bank.php?op=transfer3' method='POST'>",true);
        output("`6Trasferisci `^$amt`6 a `&$row[name]`6.");
        output("<input type='hidden' name='to' value='".HTMLEntities($row['login'])."'><input type='hidden' name='amount' value='$amt'><input type='submit' class='button' value='Completa Trasferimento'></form>",true);
        addnav("","bank.php?op=transfer3");
    }elseif(db_num_rows($result)>100){
        output("Il banchiere ti guarda disgustato e ti suggerisce di restringere il campo di ricerca almeno un po!`n`n");
        output("<form action='bank.php?op=transfer2' method='POST'>Transfer <u>h</u>ow much: <input name='amount' id='amount' accesskey='h' width='5' value='$amt'>`n",true);
        output("T<u>o</u>: <input name='to' accesskey='o' value='". $_POST['to'] . "'> (vanno bene nomi incompleti, ti verrà chiesta conferma prima di completare la transazione).`n",true);
        output("<input type='submit' class='button' value='Anteprima Trasferimento'></form>",true);
        output("<script language='javascript'>document.getElementById('amount').focus();</script>",true);
        addnav("","bank.php?op=transfer2");
    }elseif(db_num_rows($result)>1){
        output("<form action='bank.php?op=transfer3' method='POST'>",true);
        output("`6Trasferisci `^$amt`6 a <select name='to' class='input'>",true);
        $countrow = db_num_rows($result);
        for ($i=0; $i<$countrow; $i++){
            $row = db_fetch_assoc($result);
            //output($row[name]." ".$row[login]."`n");
            output("<option value=\"".HTMLEntities($row['login'])."\">".preg_replace("'[`].'","",$row['name'])."</option>",true);
        }
        output("</select><input type='hidden' name='amount' value='$amt'><input type='submit' class='button' value='Completa Trasferimento'></form>",true);
        addnav("","bank.php?op=transfer3");
    }else{
        output("`6Non è stato trovato nessuno con quel nome! Per favore ritenta.");
    }
}else if($_GET['op']=="transfer3"){
    $amt = abs((int)$_POST['amount']);
    output("`6`bCompletamento del Trasferimento`b`n");
    if ($session['user']['gold']+$session['user']['goldinbank']<$amt){
        output("`6Come puoi trasferire `^$amt`6 pezzi d'oro se ne possiedi solo ".($session['user']['gold']+$session['user']['goldinbank'])."`6?");
    }else{
        $sql = "SELECT name,acctid,level,transferredtoday FROM accounts WHERE login='{$_POST['to']}'";
        $result = db_query($sql);
        if (db_num_rows($result)==1){
            $row = db_fetch_assoc($result);
            $maxout = $session['user']['level']*getsetting("maxtransferout",25);
            $maxtfer = $row['level']*getsetting("transferperlevel",25);
            if ($session['user']['amountouttoday']+$amt > $maxout) {
                output("`6Il trasferimento non è stato completato: Non ti è concesso di trasferire più di `^$maxout`6 pezzi d'oro per giorno.");
            }else if ($maxtfer<$amt){
                output("`6Il trasferimento non è stato completato: `&".$row['name']."`6 può ricevere al massimo `^$maxtfer`6 pezzi d'oro.");
            }else if($row['transferredtoday']>=getsetting("transferreceive",3)){
                output("`&".$row['name']."`6 ha ricevuto troppi trasferimenti di denaro oggi, dovrai aspettare domani.");
            }else if($amt<(int)$session['user']['level']){
                output("`6Vorrai fare un trasferimento decente, almeno pari al tuo livello.");
            }else if($row['acctid']==$session['user']['acctid']){
                output("`6Non puoi mandare dei soldi a te stesso! Non ha senso!");
            }else{
                debuglog("ha trasferito $amt pezzi d'oro a ", $row['acctid']);
                $session['user']['gold']-=$amt;
                if ($session['user']['gold']<0){ //withdraw in case they don't have enough on hand.
                    $session['user']['goldinbank']+=$session['user']['gold'];
                    $session['user']['gold']=0;
                }
                $session['user']['amountouttoday']+= $amt;
                $sql = "UPDATE accounts SET goldinbank=goldinbank+$amt,transferredtoday=transferredtoday+1 WHERE acctid='{$row['acctid']}'";
                db_query($sql);
                output("`6Trasferimento Completato!");
                systemmail($row['acctid'],"`^Hai ricevuto del denaro!`0","`&".$session['user']['name']."`6 ha trasferito `^$amt`6 pezzi d'oro sul tuo conto in banca!");
            }
        }else{
            output("`6Non è stato possibile completare il trasferimento, sei pregato di ritentare!");
        }
    }
}else if($_GET['op']=="deposit"){
  output("<form action='bank.php?op=depositfinish' method='POST'>Hai ".($session['user']['goldinbank']>=0?"un saldo di":"un debito di")." ".abs($session['user']['goldinbank'])." pezzi d'oro.`n",true);
    output("`^<u>Q</u>uanto vuoi ".($session['user']['goldinbank']>=0?"depositare":"restituire")."? <input id='input' name='amount' width=5 accesskey='q'> <input type='submit' class='button' value='Deposita'> `n`iDigita 0 o niente per depositare tutto`i</form>",true);
    output("<script language='javascript'>document.getElementById('input').focus();</script>",true);
  addnav("","bank.php?op=depositfinish");
}else if($_GET['op']=="depositfinish"){
    $_POST['amount']=abs((int)$_POST['amount']);
    if ($_POST['amount']==0){
        $_POST['amount']=$session['user']['gold'];
    }
    if ($_POST['amount']>$session['user']['gold']){
        output("`\$ERRORE: Non hai così tanti soldi da depositare.`^`n`n");
        output("Lasci i tuoi `&".$session['user']['gold']."`^ pezzi d'oro sul bancone e dichiari di voler depositare in tutto `&$_POST[amount]`^ pezzi d'oro.");
        output("`n`nL´ometto ti fissa con sguardo vacuo per qualche secondo fino a quando te ne rendi conto e conti di nuovo i tuoi soldi, accorgendoti dell´errore.");
    }else{
        output("`^`bHai depositato `&".$_POST['amount']."`^ pezzi d'oro sul tuo conto in banca, ");
        debuglog("ha depositato " . $_POST['amount'] . " pezzi d'oro in banca");
        $session['user']['goldinbank']+=$_POST['amount'];
        $session['user']['gold']-=$_POST['amount'];
        output("restando con ".($session['user']['goldinbank']>=0?"un saldo di":"un debito di")." `&".abs($session['user']['goldinbank'])."`^ pezzi d'oro sul tuo conto e `&".$session['user']['gold']."`^ pezzi d'oro in tasca.`b");
    }
}else if($_GET['op']=="borrow"){
    $maxborrow = $session['user']['level']*getsetting("borrowperlevel",20);
  output("<form action='bank.php?op=withdrawfinish' method='POST'>Hai ".($session['user']['goldinbank']>=0?"un saldo di":"un debito di")." ".abs($session['user']['goldinbank'])." pezzi d'oro.`n",true);
  output("`^<u>Q</u>uanto vuoi chiedere in prestito (massimo $maxborrow  pezzi d'oro in tutto al tuo livello)? <input id='input' name='amount' width=5 accesskey='h'> <input type='hidden' name='borrow' value='x'><input type='submit' class='button' value='Chiedi Prestito'>`n(I soldi verranno prelevati dal tuo conto finché ne hai, il resto verrà preso in prestito)</form>",true);
    output("<script language='javascript'>document.getElementById('input').focus();</script>",true);
  addnav("","bank.php?op=withdrawfinish");
}else if($_GET['op']=="withdraw"){
  output("<form action='bank.php?op=withdrawfinish' method='POST'>Hai ".$session['user']['goldinbank']." pezzi d'oro in banca.`n",true);
  output("`^<u>Q</u>uanto vuoi prelevare? <input id='input' name='amount' width=5 accesskey='h'> <input type='submit' class='button' value='Preleva'>`n`iDigita 0 o niente per prelevare tutto`i</form>",true);
    output("<script language='javascript'>document.getElementById('input').focus();</script>",true);
  addnav("","bank.php?op=withdrawfinish");
}else if($_GET['op']=="withdrawfinish"){
    $_POST['amount']=abs((int)$_POST['amount']);
    if ($_POST['amount']==0){
        $_POST['amount']=abs($session['user']['goldinbank']);
    }
    if ($_POST['amount']>$session['user']['goldinbank'] && $_POST['borrow']=="") {
        output("`\$ERRORE: Non hai così tanti soldi sul conto.`^`n`n");
        output("Essendo stato informato che hai `&".$session['user']['goldinbank']."`^ pezzi d'oro sul tuo conto, dichiari di volerne prelevare `&$_POST[amount]`^.");
        output("`n`nL´ometto ti fissa per un secondo con uno sguardo vacuo, poi ti consiglia di riprendere a studiare l´aritmetica. Ti rendi conto della tua stupidaggine e pensi che dovresti riprovare.");
    }else if($_POST['amount']>$session['user']['goldinbank']){
        $lefttoborrow = $_POST['amount'];
        $maxborrow = $session['user']['level']*getsetting("borrowperlevel",20);
        if ($lefttoborrow<=$session['user']['goldinbank']+$maxborrow){
            if ($session['user']['goldinbank']>0){
                output("`6Prelevi i restanti `^".$session['user']['goldinbank']."`6 pezzi d'oro, e ");
                $lefttoborrow-=$session['user']['goldinbank'];
                $session['user']['gold']+=$session['user']['goldinbank'];
                $session['user']['goldinbank']=0;
                debuglog("ha prelevato " . $_POST['amount'] . " pezzi d'oro dalla banca");
            }else{
                output("`6");
            }
            if ($lefttoborrow-$session['user']['goldinbank'] > $maxborrow){
                output("Chiedi in prestito `^$lefttoborrow`6 pezzi d'oro. L´ometto guarda il tuo conto e ti informa che puoi chiederne al massimo `^$maxborrow`6.");
            }else{
                output("Ricevi in prestito `^$lefttoborrow`6 pezzi d'oro.");
                $session['user']['goldinbank']-=$lefttoborrow;
                $session['user']['gold']+=$lefttoborrow;
                debuglog("ha preso in prestito $lefttoborrow pezzi d'oro dalla banca");
            }
        }else{
            output("`6Tenendo conto dei `^".$session['user']['goldinbank']."`6 pezzi d'oro sul tuo conto, ne chiedi in prestito `^".($lefttoborrow-$session[user][goldinbank])."`6, ma
            l´ometto guarda il tuo conto e ti informa che puoi averne al massimo `^$maxborrow`6 al tuo livello.");
        }
    }else{
        output("`^`bPrelevi `&".$_POST['amount']."`^ pezzi d'oro dal tuo conto in banca, ");
        $session['user']['goldinbank']-=$_POST['amount'];
        $session['user']['gold']+=$_POST['amount'];
        debuglog("preleva " . $_POST['amount'] . " pezzi d'oro dalla banca");
        output("restando con `&".$session['user']['goldinbank']."`^ pezzi d'oro nel conto e `&".$session['user']['gold']."`^ in tasca.`b");
    }
}
addnav("Torna al Villaggio","village.php");
if ($session['user']['robbank'] < 1 AND $session['user']['dragonkills'] > 0 AND ($session['user']['turns'] >= (int)($session['user']['turnimax']/2))) {
    addnav("B?`4Deruba la Banca","robbank.php");
}
if ($session['user']['goldinbank']>=0){
    addnav("Preleva","bank.php?op=withdraw");
    addnav("Deposita","bank.php?op=deposit");
    if (getsetting("borrowperlevel",20)) addnav("Prendi un Prestito","bank.php?op=borrow");
}else{
    addnav("Paga il Debito","bank.php?op=deposit");
    if (getsetting("borrowperlevel",20)) addnav("Chiedi un altro Prestito","bank.php?op=borrow");
}
if (getsetting("allowgoldtransfer",1)){
    if ($session['user']['level']>=getsetting("mintransferlev",3) || $session['user']['dragonkills']>0){
        addnav("Traferisci Denaro","bank.php?op=transfer");
    }
}

page_footer();

?>