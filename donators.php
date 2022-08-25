<?php
require_once "common.php";
isnewday(3);

page_header("Pagina Donatori");
addnav("P?Paylog","paylog97.php");
addnav("G?Torna alla Grotta","superuser.php");
addnav("M?Torna alla Mondanità","village.php");

output("<form action='donators.php?op=add1' method='POST'>",true);
addnav("","donators.php?op=add1");
output("`bAggiungi Punti Donazione:`b`nPersonaggio: <input name='name'> `nPunti: <input name='amt' size='3'>`nEuro: <input name='euro' size='3'>`n<input type='submit' class='button' value='Aggiungi Donazione'>",true);
output("</form>",true);

if ($_GET['op']=="add2"){
    if ($_GET['id']==$session['user']['acctid']){
        $session['user']['donation']+=$_GET['amt'];
        $session['user']['euro']+=$_GET['euro'];
    }
    //ok to execute when this is the current user, they'll overwrite the value at the end of their page
    //hit, and this will allow the display table to update in real time.
    $sql = "UPDATE accounts SET euro=euro+'".$_GET['euro']."',donation=donation+'".$_GET['amt']."' WHERE acctid='".$_GET['id']."'";
    db_query($sql);

    //Excalibur: modifica per inserimento nel DB paylog

    $datap=date("H:i:s M d, Y T");
    if($_GET['euro']==0 OR !$_GET['euro']){
        $don=" PD";
        $val = $_GET[amt];
    }else{
        $don=" EUR";
        $val=$_GET[euro];
    }
    $lenght = strlen($val);
    $testo = "a:29:{s:8:\"txn_type\";s:9:\"Manuale  \";s:12:\"payment_date\";s:25:\"$datap\";s:9:\"last_name\";s:0:\"\";s:0:\"\";s:0:\"\";s:9:\"item_name\";s:0:\"\";s:13:\"payment_gross\";s:0:\"\";s:11:\"mc_currency\";s:3:\"$don\";s:8:\"business\";s:12:\"luke@dnet.it\";s:12:\"payment_type\";s:7:\"instant\";s:0:\"\";s:0:\"\";s:0:\"\";s:0:\"\";s:3:\"tax\";s:4:\"0.00\";s:11:\"payer_email\";s:0:\"\";s:6:\"txn_id\";s:0:\"\";s:8:\"quantity\";s:1:\"1\";s:14:\"receiver_email\";s:12:\"luke@dnet.it\";s:10:\"first_name\";s:0:\"\";s:8:\"payer_id\";s:0:\"\";s:11:\"receiver_id\";s:13:\"TNB4825J4GU9N\";s:4:\"memo\";s:0:\"\";s:11:\"item_number\";s:0:\"\";s:14:\"payment_status\";s:9:\"Completed\";s:11:\"payment_fee\";s:0:\"\";s:6:\"mc_fee\";s:4:\"0.00\";s:8:\"shipping\";s:4:\"0.00\";s:8:\"mc_gross\";s:".$lenght.":\"".$val."\";s:6:\"custom\";s:0:\"\";s:7:\"charset\";s:12:\"windows-1252\";s:14:\"notify_version\";s:3:\"1.9\";}";
    $sql = "
        INSERT INTO paylog(
            info,
            response,
            txnid,
            amount,
            name,
            acctid,
            processed,
            filed,
            txfee,
            processdate
        )VALUES(

            '".$testo."',
            '',
            '',
            '".$_GET['euro']."',
            '".$_GET['name']."',
            ".(int)$_GET['id'].",
            '',
            '',
            '',
            '".date("Y-m-d H:i:s")."'
        )";
    db_query($sql) or die(db_error(LINK));
    //Excalibur: fine modifica

    $_GET['op']="";
    //luke per settaggio avvenuta donazione
    $sql = "SELECT superuser FROM accounts WHERE acctid='".$_GET['id']."'";
    $result = db_query($sql);
    $row = db_fetch_assoc($result);

    //luke fine
    if($_GET['amt']==null OR $_GET['amt']==0){
    }else{
        if($_GET['euro']!=null OR $_GET['euro']!=0){
            if ($row['superuser']==0){
                savesetting("ultima_donazione", time());
                $mailmessage = "`\$Grazie `7per la donazione.`nTi sono stati assegnati : `\$".$_GET['amt']." `7 punti donazione.`nPuoi spenderli nel capanno di caccia. `nBuon divertimento!";
                systemmail($_GET['id'],"`2Conferma donazione.`2",$mailmessage);
            }
        }else{
                $mailmessage = "`\$Ti sono stati assegnati : `\$".$_GET['amt']." `7 punti donazione.`nPuoi spenderli nel capanno di caccia. `nBuon divertimento!";
                systemmail($_GET['id'],"`2Punti donazione.`2",$mailmessage);
        }
    }
}
if ($_GET['op']==""){
    $sql = "SELECT acctid,name,donation,donationspent,euro FROM accounts WHERE donation>25 ORDER BY euro DESC,donation DESC";
    $result = db_query($sql);
    output("<table border='0' cellpadding='5' cellspacing='0'>",true);
    output("<tr><td>ID</td><td>Nome</td><td>Punti</td><td>Spesi</td><td>Euro</td></tr>",true);
    $countrow = db_num_rows($result);
    for ($i=0; $i<$countrow; $i++){
    //for ($i=0;$i<db_num_rows($result);$i++){
        $row = db_fetch_assoc($result);
        output("<tr class='".($i%2?"trlight":"trdark")."'>",true);
        output("<td>",true);
        output("`V".$row['acctid']."`0",true);
        output("</td><td><A href=donators.php?op=acquistati&id=$row[acctid]>`^{$row['name']}`0</a>",true);
        addnav("", "donators.php?op=acquistati&id=$row[acctid]");
        output("</td><td>`@".number_format($row['donation'])."`0</td>",true);
        output("<td>`%".number_format($row['donationspent'])."`0</td>",true);
        output("<td>`%".$row['euro']."`0</td>",true);
        output("</tr>",true);
    }
    output("</table>",true);
}else if ($_GET['op']=="add1"){
    $search="%";
    for ($i=0;$i<strlen($_POST['name']);$i++){
        $search.=substr($_POST['name'],$i,1)."%";
    }
    $sql = "SELECT name,acctid,donation,donationspent,euro FROM accounts WHERE login LIKE '$search'";
    $result = db_query($sql);
    output("Conferma l'aggiunta di ".$_POST['amt']." punti e ".$_POST['euro']." euro a:`n`n");
    $countrow = db_num_rows($result);
    for ($i=0; $i<$countrow; $i++){
    //for ($i=0;$i<db_num_rows($result);$i++){
        $row = db_fetch_assoc($result);
        output("<a href='donators.php?op=add2&id={$row['acctid']}&amt={$_POST['amt']}&euro={$_POST['euro']}'>",true);
        output($row['name']." (".$row['donation']."/".$row['donationspent'].")");
        output("</a>`n",true);
        addnav("","donators.php?op=add2&id={$row['acctid']}&amt={$_POST['amt']}&euro={$_POST['euro']}");
    }
}elseif ($_GET['op']=="acquistati"){
    $sql = "SELECT * FROM donazioni WHERE idplayer= '".$_GET['id']."'";
    $result = db_query($sql);
    output("<table border='0' cellpadding='5' cellspacing='0'>",true);
    output("<tr><td>Nome</td><td>Usi</td><td>Tipo</td></tr>",true);
    $countrow = db_num_rows($result);
    for ($i=0; $i<$countrow; $i++){
    //for ($i=0;$i<db_num_rows($result);$i++){
        $row = db_fetch_assoc($result);
        $sql = "SELECT acctid,name,donation,donationspent FROM accounts WHERE acctid='".$row['idplayer']."'";
        $resulta = db_query($sql);
        $rowa = db_fetch_assoc($resulta);

        output("<tr class='".($i%2?"trlight":"trdark")."'>",true);
        //output("`V{$rowa['acctid']}`0",true);
        //output("</td><td>`^{$rowa['name']}`0",true);
        output("<td>`@".$row['nome']."`0</td>",true);
        output("</td><td>`@".$row['usi']."`0</td>",true);
        output("<td>`%".$row['tipo']."`0</td>",true);
        output("</tr>",true);

    }
    output("</table>",true);
    output("`n`# Legenda:`n");
    output("`%T`7 = Temporaneo, dura un periodo di tempo limitato in genere giorni di gioco e contano solo quelli giocati!`n");
    output("`%P`7 = Permanente, dura per sempre!`n");
    output("`%R`7 = Reincarnazione, dura fino a quando non vi reincarnate!`n");
    output("`\$*`7 = Rinnovabile, non dovete ricomprarlo ma rinnovarlo in genere al 50% del valore se non specificato diversamente!`n");
    addnav("Pagina donatori","donators.php");
}
page_footer();
?>