<?php
require_once("common.php");
page_header("Incantesimi Voodoo");
output("`@`b`cGli Incantesimi Voodoo`c`b`n`n");
if ($_GET['acctid'] != "") {
    $sql = "DELETE FROM voodoo WHERE target='".$_GET['acctid']."'";
    db_query($sql);
    output("`cIncantesimo Annullato!`c`n`n`n");
}
output("<table cellspacing=2 cellpadding=2 align='center'><tr><td>`bCommittente`b</td><td>`bIncantesimo`b</td><td>`bDestinatario`b</td></tr>",true);
if ($session['user']['superuser'] > 2) output("<td></td></tr>",true);
$ppp=25;
if (!$_GET['limit']){
   $page=0;
}else{
   $page=(int)$_GET['limit'];
   addnav("Pagina Precedente","suvoodoo.php?limit=".($page-1)."");
}
$limit="".($page*$ppp).",".($ppp+1);
$sql = "SELECT * FROM voodoo ORDER BY id ASC LIMIT $limit";
$result = db_query($sql) or die(db_error(LINK));
if (db_num_rows($result)>$ppp) addnav("Pagina Successiva","suvoodoo.php?limit=".($page+1)."");
if (db_num_rows($result) == 0) {
   output("<tr><td colspan=4 align='center'>`&`iNon sono stati fatti incantesimi `\$Voodoo`&.`i`0</td></tr>",true);
}else{
   $countrow = db_num_rows($result);
   for ($i=0; $i<$countrow; $i++){
   //for ($i=0;$i<db_num_rows($result);$i++){
       $row = db_fetch_assoc($result);
       $sql2 = "SELECT name FROM accounts WHERE acctid = '".$row['caster']."'";
       $result2 = db_query($sql2) or die(db_error(LINK));
       $row2 = db_fetch_assoc($result2);
       $sql3 = "SELECT name FROM accounts WHERE acctid = '".$row['target']."'";
       $result3 = db_query($sql3) or die(db_error(LINK));
       $row3 = db_fetch_assoc($result3);
       output("<tr bgcolor='#003366'><td align='center'>`b`@".$row2['name']."`b`0</td><td align='center'>`b`\$".$row['spell']."`0`b</td><td align='center'>`b`(".$row3['name']."`b`0</td>",true);
       if ($session['user']['superuser']>2) {
           output("<td><A href=suvoodoo.php?acctid=".$row['target'].">Elimina</A></td>",true);
           addnav("","suvoodoo.php?acctid=".$row['target']);
       }
       output("</tr>",true);
   }
}
output("</table>",true);
addnav("Torna alla Grotta","superuser.php");
addnav("Torna alla Mondanità","village.php");
output("`n<div align='right'>`)2005 by Excalibur</div>",true);
page_footer();
?>