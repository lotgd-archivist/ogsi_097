<?php
require_once("common.php");
/*
+-----------+---------------------+------+-----+---------+----------------+
| Field     | Type                | Null | Key | Default | Extra          |
+-----------+---------------------+------+-----+---------+----------------+
| payid     | int(11)             |      | PRI | NULL    | auto_increment |
| info      | text                |      |     |         |                |
| response  | text                |      |     |         |                |
| txnid     | varchar(32)         |      | MUL |         |                |
| amount    | float(9,2)          |      |     | 0.00    |                |
| name      | varchar(50)         |      |     |         |                |
| acctid    | int(11) unsigned    |      |     | 0       |                |
| processed | tinyint(4) unsigned |      |     | 0       |                |
| filed     | tinyint(4) unsigned |      |     | 0       |                |
| txfee     | float(9,2)          |      |     | 0.00    |                |
+-----------+---------------------+------+-----+---------+----------------+
*/
page_header("Log Pagamenti");

$op = $_GET['op'];
if ($op==""){
    addnav("G?Torna alla Grotta","superuser.php");
    $sql = "SELECT info,txnid FROM paylog WHERE processdate='0000-00-00'";
    $result = db_query($sql);
    while ($row = db_fetch_assoc($result)){
        $info = unserialize($row['info']);
        $sql = "UPDATE ".db_prefix('paylog')." SET processdate='".date("Y-m-d H:i:s",strtotime($info['payment_date']))."' WHERE txnid='".addslashes($row['txnid'])."'";
        db_query($sql);
    }
    $sql = "SELECT substring(processdate,1,7) AS month, sum(amount)-sum(txfee) AS profit FROM paylog GROUP BY month DESC";
    $result = db_query($sql);
    addnav("Months");
    while ($row = db_fetch_assoc($result)){
        //addnav(array(date("M Y",strtotime($row['month']."-01"))." (\$%s)",$row['profit']),"paylog97.php?month={$row['month']}");
        //Set your local language here
        setlocale(LC_TIME, 'it_IT');
        addnav(strftime("%B %Y",strtotime($row['month']."-01"))." (".$row['profit'].")","paylog97.php?month={$row['month']}");
        $totale += $row['profit'];
    }
    addnav("Totale: € ".$totale);
    $month = $_GET['month'];
    if ($month=="") $month = date("Y-m");
    $startdate = $month."-01 00:00:00";
    $enddate = date("Y-m-d H:i:s",strtotime(date("r")."+1 month",strtotime($startdate)));
    $sql = "SELECT paylog.*,accounts.name, accounts.donation, accounts.donationspent FROM paylog LEFT JOIN accounts ON paylog.acctid = accounts.acctid WHERE processdate>='$startdate' AND processdate < '$enddate' ORDER BY payid DESC";
    $result = db_query($sql);
    rawoutput("<table border='0' cellpadding='2' cellspacing='1' bgcolor='#999999'>");
    $type = "Tipo";
    $gross = "Lordo";
    $fee = "Tasse";
    $net = "Netto";
    $processed = "Processato";
    $who = "Chi";
    rawoutput("<tr class='trhead'><td>Data</td><td>$type</td><td>$gross</td><td>$fee</td><td>$net</td><td>$processed</td><td>$who</td></tr>");
    //rawoutput("<tr class='trhead'><td>Data</td><td>Totale</td><td>Chi</td></tr>");
    $countrow = db_num_rows($result);
    for ($i=0; $i<$countrow; $i++){
    //for ($i=0;$i<db_num_rows($result);$i++){
        $row = db_fetch_assoc($result);

        $info = unserialize($row['info']);
        rawoutput("<tr class='".($i%2?"trlight":"trdark")."'><td nowrap>");
        output(date("d/m H:i",strtotime($info['payment_date'])));
        rawoutput("</td><td>");
        output($info['txn_type']);
        rawoutput("</td><td nowrap>");
        output($info['mc_gross']." ".$info['mc_currency']);
        rawoutput("</td><td>");
        output($info['mc_fee']);
        rawoutput("</td><td>");
        output((float)$info['mc_gross'] - (float)$info['mc_fee']);
        rawoutput("</td><td>");
        output($row['processed']?"`@Si`0":"`\$No`0");
        rawoutput("</td><td nowrap>");
        if ($row['name']>"") {
            rawoutput("<a href='user.php?op=edit&userid={$row['acctid']}'>");
            output("`&".$row['name']."`0 (".$row['donationspent']."/".$row['donation'].")");
            rawoutput("</a>");
            addnav("","user.php?op=edit&userid={$row['acctid']}");
        }else{
            $amt = round((float)$info['mc_gross'] * 100,0);
            $link = "donators.php?op=add1&name=".rawurlencode($info['memo'])."&amt=$amt&txnid={$row['txnid']}";
            rawoutput("-=( <a href='$link' title=\"".HTMLEntities2($info['item_number'])."\" alt=\"".HTMLEntities2($info['item_number'])."\">[".HTMLEntities2($info['memo'])."]</a> )=-");
            addnav("",$link);
        }
        rawoutput("</td></tr>");
    }
    rawoutput("</table>");
    addnav("Ricarica","paylog97.php");
    addnav("D?Pagina Donatori","donators.php");
}
page_footer();
?>