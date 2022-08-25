<?php
require_once "common.php";
isnewday(3);
addnav("G?Torna alla Grotta","superuser.php");
addnav("R?Ricarica Pagina","controllotenute.php");
page_header("Controllo Tenute per Interazioni");
$sql = "SELECT value1
        FROM items
        WHERE class = 'key' AND name = 'House key'
        ORDER BY value1 DESC LIMIT 1";
$result = db_query($sql);
$row = db_fetch_assoc($result);
$tenuta = $row['value1'];
for ($i=0; $i<=$tenuta; $i++){
    $sql = "SELECT COUNT( a.lastip ) AS ipuguali, a.name, a.acctid, a.lastip, i.owner, i.value1 AS tenuta
            FROM accounts a
            INNER JOIN items i ON i.owner = a.acctid
            WHERE i.value1 = ".$i."
            GROUP BY a.lastip";
    $result = db_query($sql);
    $row = db_fetch_assoc($result);
    if ($row['ipuguali'] > 1){
        $sql1 = "SELECT acctid, name, lastip FROM accounts
                 WHERE lastip = '".$row['lastip']."'";
        $result1 = db_query($sql1);
        if (db_num_rows($result1) > 1){
           output("<table cellspacing=2 cellpadding=2 align='center' border='4'  frame='hsides' rules='rows'>",true);
           output("<tr bgcolor='#444444'><td colspan='4'><big>`b`c`@TENUTA N° `%$tenuta`n`@Interazione proprietario/ospite`c`b</big></td></tr>",true);
           output("<tr>
                   <td>`c`&AccountID`c</td>
                   <td>`c`@Nome PG`c</td>
                   <td>`c`^IP`c</td>
                   <td>`c`#Propr./Ospite`c</td>
                   </tr>
                   ",true);
           $countrow = db_num_rows($result1);
           for ($j=0; $j<$countrow; $j++){
           //for ($j=0;$j<db_num_rows($result1);$j++){
               $row1=db_fetch_assoc($result1);
               $sql2 = "SELECT owner FROM houses WHERE $tenuta = houseid";
               $result2 = db_query($sql2);
               $row2=db_fetch_assoc($result2);
               output("<tr class='" . ($j % 2?"trlight":"trdark") . "'>
                       <td>`&".$row1['acctid']."</td>
                       <td>`@".$row1['name']."</td>
                       <td>`^".$row1['lastip']."</td>",true);
                       if ($row2['owner'] == $row1['acctid']){
                           output("<td>`(Proprietario<td>",true);
                       }else{
                           output("<td>`(Ospite<td>",true);
                       }
                       output("</tr>",true);
           }
           output("</table>",true);
        }
    }
}
page_footer();
?>