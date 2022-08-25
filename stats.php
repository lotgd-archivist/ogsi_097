<?php
require_once "common.php";
isnewday(2);

page_header("Stats");
addnav("G?Torna alla Grotta","superuser.php");
addnav("M?Torna alla Mondanità","village.php");
addnav("Aggiorna le Statistiche","stats.php");

$sql = "SELECT sum(gentimecount) AS c, sum(gentime) AS t, sum(gensize) AS s, count(*) AS a FROM accounts";
$result = db_query($sql);
$row = db_fetch_assoc($result);
output("`b`%Per gli account esistenti:`b`n");
output("`@Account Totali: `^".number_format($row['a'])."`n");
output("`@Total Hits: `^".number_format($row['c'])."`n");
output("`@Total Page Gen Time: `^".dhms($row['t'])."`n");
output("`@Total Page Gen Size: `^".number_format($row['s'])."b`n");
output("`@Average Page Gen Time: `^".dhms($row['t']/$row['c'],true)."`n");
output("`@Average Page Gen Size: `^".number_format($row['s']/$row['c'])."`n");
output("`n`%`bTop Referers:`b`0`n");
output("<table border='0' cellpadding='2' cellspacing='1' bgcolor='#999999'>",true);
output("<tr class='trhead'><td><b>Nome</b></td><td><b>Referrals</b></td></tr>",true);
$sql = "SELECT count(*) AS c, acct.acctid,acct.name AS referer,acct.lastip FROM accounts INNER JOIN accounts AS acct ON acct.acctid = accounts.referer WHERE accounts.referer>0 GROUP BY accounts.referer DESC ORDER BY c DESC";
$result = db_query($sql);
$countrow = db_num_rows($result);
for ($i=0; $i<$countrow; $i++){
//for ($i=0;$i<db_num_rows($result);$i++){
    $row = db_fetch_assoc($result);
    output("<tr class='".($i%2?"trdark":"trlight")."'><td>",true);
    output("`@{$row['referer']}`0 (`6".$row['lastip']."`0)</td><td>`^{$row['c']}:`0  ", true);
    $sql = "SELECT name,refererawarded,lastip,level from accounts WHERE referer = ${row['acctid']} ORDER BY acctid ASC";
    $res2 = db_query($sql);
    $countrow2 = db_num_rows($res2);
    for ($j=0; $j<$countrow2; $j++){
    //for ($j = 0; $j < db_num_rows($res2); $j++) {
        $r = db_fetch_assoc($res2);
        output(($r['refererawarded']?"`&":"`$") . $r['name'] . " `0(`@".$r['level']."`0) (`6".$r['lastip']."`0)");
        if ($j != $countrow2-1) output(",");
    }
    output("</td></tr>",true);
}
output("</table>",true);
$sql = "SELECT count(*) AS c, substring(laston,1,10) AS d FROM accounts GROUP BY d DESC ORDER BY d DESC";
$result = db_query($sql);
output("`n`%`bDate accounts last logged on:`b");
$output.="<table border='0' cellpadding='0' cellspacing='5'>";
$class="trlight";
$odate=date("Y-m-d");
$j=0;
$countrow = db_num_rows($result);
for ($i=0; $i<$countrow; $i++){
//for ($i=0;$i<db_num_rows($result);$i++){
    $row = db_fetch_assoc($result);
    $diff = (strtotime($odate)-strtotime($row['d']))/86400;
    for ($x=1;$x<$diff;$x++){
        //if ($j%7==0) $class=($class=="trlight"?"trdark":"trlight");
        //$j++;
        $class=(date("W",strtotime("$odate -$x days"))%2?"trlight":"trdark");
        $output.="<tr class='$class'><td>".date("Y-m-d",strtotime("$odate -$x days"))."</td><td>0</td><td>$cumul</td></tr>";
    }
//  if ($j%7==0) $class=($class=="trlight"?"trdark":"trlight");
//  $j++;
    $class=(date("W",strtotime($row['d']))%2?"trlight":"trdark");
    $cumul+=$row['c'];
    $output.="<tr class='$class'><td>{$row['d']}</td><td><img src='images/trans.gif' width='{$row['c']}' border='1' height='5'>{$row['c']}</td><td>$cumul</td></tr>";
    $odate = $row['d'];
}
$output.="</table>";
page_footer();
?>