<?php
require_once("common.php");
page_header("Furbetti che dormono nelle tenute abbandonate");
output("`@`b`cFurbetti che dormono nelle tenute abbandonate`c`b`n`n");

output("<table cellspacing=2 cellpadding=2 align='center'><tr><td>`bNome`b</td><td>`bTenuta`b</td><td>`bhouseid`b</td></tr>",true);
if ($session['user']['superuser'] > 2) output("<td></td></tr>",true);
$ppp=25;
if (!$_GET['limit']){
   $page=0;
}else{
   $page=(int)$_GET['limit'];
   addnav("Pagina Precedente","sutenuteabb.php?limit=".($page-1)."");
}
$limit="".($page*$ppp).",".($ppp+1);
$sql = "SELECT actor, login, housename, houseid, status,(substr( message, 27, 3 )) FROM debuglog, houses, accounts WHERE message LIKE '%va a dormire nella tenuta%' AND actor = acctid AND status = 3 AND (substr( message, 27, 3) ) = houseid ORDER BY login ASC LIMIT $limit";
$result = db_query($sql) or die(db_error(LINK));
if (db_num_rows($result)>$ppp) addnav("Pagina Successiva","suvoodoo.php?limit=".($page+1)."");
if (db_num_rows($result) == 0) {
   output("<tr><td colspan=4 align='center'>`&`iNon è stato trovato alcun furbetto`&.`i`0</td></tr>",true);
}else{
   $countrow = db_num_rows($result);
   for ($i=0; $i<$countrow; $i++){
   //for ($i=0;$i< db_num_rows($result);$i++){
       $row = db_fetch_assoc($result);
       output("<tr bgcolor='#003366'><td align='center'>`b`@".$row['login']."`b`0</td><td align='center'>`b`@".$row['housename']."`0`b</td><td align='center'>`b`(".$row['houseid']."`b`0</td>",true);
       output("</tr>",true);
   }
}
output("</table>",true);
addnav("Torna alla Grotta","superuser.php");
addnav("Torna alla Mondanità","village.php");
output("`n<div align='right'>`)2012 by Hugues</div>",true);
page_footer();
?>