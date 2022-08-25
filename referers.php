<?php
require_once "common.php";
isnewday(2);

$sql = "DELETE FROM referers WHERE last<'".date("Y-m-d H:i:s",strtotime(date("r")."-".getsetting("expirecontent",180)." days"))."' OR site LIKE '%ogsi.it%'";
db_query($sql);
if ($_GET['op']=="rebuild"){
    $sql = "SELECT * FROM referers";
    $result = db_query($sql);
    $countrow = db_num_rows($result);
    for ($i=0; $i<$countrow; $i++){
    //for ($i=0;$i<db_num_rows($result);$i++){
        $row = db_fetch_assoc($result);
        $site = str_replace("http://","",$row['uri']);
        if (strpos($site,"/")) $site = substr($site,0,strpos($site,"/"));
        $sql = "UPDATE referers SET site='".addslashes($site)."' WHERE refererid='{$row['refererid']}'";
        db_query($sql);
    }
}
addnav("G?Torna alla Grotta","superuser.php");
addnav("M?Torna alla Mondanità","village.php");
addnav("Opzioni Referer");
addnav("",$_SERVER['REQUEST_URI']);
addnav("Aggiorna","referers.php?sort=".URLEncode($_GET['sort'])."");
addnav("C?Ordina per Conteggio","referers.php?sort=count".($_GET['sort']=="count DESC"?"":"+DESC"));
addnav("U?ordina per URL","referers.php?sort=uri".($_GET['sort']=="uri"?"+DESC":""));
addnav("T?Ordina per Tempo","referers.php?sort=last".($_GET['sort']=="last DESC"?"":"+DESC"));

addnav("Ricostruisci Siti","referers.php?op=rebuild");

page_header("Referenti");
$order = "count DESC";
if ($_GET['sort']!="") $order=$_GET['sort'];
$sql = "SELECT SUM(count) AS count, MAX(last) AS last,site FROM referers GROUP BY site ORDER BY $order";
output("<table><tr><td>Conteggi</td><td>Ultimo</td><td>URL</td></tr>",true);
$result = db_query($sql);
$countrow = db_num_rows($result);
for ($i=0; $i<$countrow; $i++){
//for ($i=0;$i<db_num_rows($result);$i++){
    $row = db_fetch_assoc($result);
    output("<tr class='trdark'><td valign='top'>`b",true);
    output($row['count']);
    output("`b</td><td valign='top'>`b",true);
    $diffsecs = strtotime(date("r"))-strtotime($row['last']);
    output((int)($diffsecs/86400)."g ".(int)($diffsecs/3600%3600)."h ".(int)($diffsecs/60%60)."m ".(int)($diffsecs%60)."s");
    output("`b</td><td>`b".HTMLEntities($row['site']==""?"`iNessuno`i":$row['site'])."`b</td></tr>",true);
    $sql = "SELECT count,last,uri FROM referers WHERE site='".addslashes($row['site'])."' ORDER BY {$order}";
    $result1 = db_query($sql);
    $skippedcount=0;
    $skippedtotal=0;
    $countrow1 = db_num_rows($result1);
    for ($k=0; $k<$countrow1; $k++){
    //for ($k=0;$k<db_num_rows($result1);$k++){
        $row1=db_fetch_assoc($result1);
        $diffsecs = strtotime(date("r"))-strtotime($row1['last']);
        if ($diffsecs<=604800){
            output("<tr class='trlight'><td>",true);
            output($row1['count']);
            output("</td><td valign='top'>",true);
            //output((int)($diffsecs/86400)."d".(int)($diffsecs/3600%3600)."h".(int)($diffsecs/60%60)."m".(int)($diffsecs%60)."s");
            output(dhms($diffsecs));
            output("</td><td valign='top'>",true);
            if ($row1['uri']>"")
                output("<a href='".HTMLEntities($row1['uri'])."' target='_blank'>".HTMLEntities(substr($row1['uri'],0,150))."</a>`n",true);
            else
                output("`i`bNone`b`i`n");
            output("</td></tr>",true);
        }else{
            $skippedcount++;
            $skippedtotal+=$row1['count'];
        }
    }
    if ($skippedcount>0){
        output("<tr class='trlight'><td>$skippedtotal</td><td valign='top' colspan='2'>`i$skippedcount record saltati (+ vecchio di una settimana)`i</td></tr>",true);
    }
    //output("</td></tr>",true);
}
output("</table>",true);
page_footer();
?>