<?php
require_once("common.php");
page_header("Utenti sconnessi per limite click");
if ($_GET['op'] == "") $_GET['op'] = "click";
if ($_GET['op'] == "click") {
   output("`c`b`#Elenco Utenti Sconnessi`b`c`n
   `\$Vengono evidenziati in rosso quelli che hanno raggiunto il limite prima di 15 minuti.`n
   `%Vengono evidenziati in viola quelli che sono rimasti connessi per più di 2 ore.`n`n`n`0");
}elseif ($_GET['op'] == "time"){
   output("`c`b`#Elenco Utenti Connessi per più di 2 ore`b`c`n
   `\$Vengono evidenziati in rosso quelli che Sono rimasti connessi per più di 4 ore.`n
   `%Vengono evidenziati in viola quelli che sono rimasti connessi tra 2 ore e 4 ore.`n`n`n`0");
}
addnav("Disconnessioni","suclick.php?op=click");
addnav("Tempi","suclick.php?op=time");
addnav("SlowQuery (time)","suclick.php?op=slow&op1=time");
addnav("SlowQuery (query)","suclick.php?op=slow&op1=query");
addnav("SlowQuery (N°player)","suclick.php?op=slow&op1=numplayer");
addnav("G?Grotta Superutente","superuser.php");
if ($_GET['op'] != "slow"){
   output("<table align='center' border='1' bordercolor='black'>
   <tr>
   <td bgcolor='navy' align='center'><b><font color='White'>NOME</font></b></td>
   <td bgcolor='navy' align='center'><b><font color='White'>PERMANENZA</font></b></td>
   <td bgcolor='navy' align='center'><b><font color='White'>LOGIN TIME</font></b></td>
   <td bgcolor='navy' align='center'><b><font color='White'>LOGOUT TIME</font></b></td>
   </tr>",true);
   $sql = "SELECT f.*,a.name FROM furbetti f
           LEFT JOIN accounts a ON (a.acctid=f.acctid)
           WHERE type = '".$_GET['op']."' ORDER BY acctid ASC, logintime ASC";
   $result = db_query($sql) or die(db_error(LINK));
   $countrow = db_num_rows($result);
   for ($i=0; $i<$countrow; $i++){
   //for ($i=0; $i < db_num_rows($result); $i++) {
       $row = db_fetch_assoc($result);
       $nome = $row['name'];
       if ($row['logintime'] == "0000-00-00 00:00:00"){
           output("<tr><td align='center'>`@$nome</td>",true);
           output("<td align='center'>`(Login non registrato`0</td><td></td><td></td></tr>",true);
       }else{
           $login = strtotime($row['logintime']);
           $logout = strtotime($row['logouttime']);
           $tempo = $logout - $login;
           $minuti = intval($tempo/60);
           $secondi = $tempo - ($minuti*60);
           if ($minuti > 60){
              $ore = intval($minuti/60);
              $minuti = $minuti - ($ore*60);
           }
           $ore = intval($tempo/3600);
           if ($_GET['op'] == "click"){
               if (($ore == 0 AND $minuti > 15) OR ($ore > 0 AND $ore < 2)){
                   output("<tr><td align='center'>`@$nome</td>",true);
                   output("<td align='center'>`@$ore h $minuti min $secondi sec</td>",true);
                   output("<td align='center'>`@".$row['logintime']."</td>",true);
                   output("<td align='center'>`@".$row['logouttime']."</td></tr>",true);
               }elseif ($ore > 1){
                   output("<tr><td align='center'>`%$nome</td>",true);
                   output("<td align='center'>`%$ore h $minuti min $secondi sec</td>",true);
                   output("<td align='center'>`%".$row['logintime']."</td>",true);
                   output("<td align='center'>`%".$row['logouttime']."</td></tr>",true);
               }else{
                   output("<tr><td align='center'>`\$$nome</td>",true);
                   output("<td align='center'>`\$$ore h $minuti min $secondi sec</td>",true);
                   output("<td align='center'>`\$".$row['logintime']."</td>",true);
                   output("<td align='center'>`\$".$row['logouttime']."</td></tr>",true);
               }
           }else{
               if ($ore < 4){
                   output("<tr><td align='center'>`%$nome</td>",true);
                   output("<td align='center'>`%$ore h $minuti min $secondi sec</td>",true);
                   output("<td align='center'>`%".$row['logintime']."</td>",true);
                   output("<td align='center'>`%".$row['logouttime']."</td></tr>",true);
               }else{
                   output("<tr><td align='center'>`\$$nome</td>",true);
                   output("<td align='center'>`\$$ore h $minuti min $secondi sec</td>",true);
                   output("<td align='center'>`\$".$row['logintime']."</td>",true);
                   output("<td align='center'>`\$".$row['logouttime']."</td></tr>",true);
               }        }
       }
   }
   output("</table>",true);
}else{
   output("<table align='center' border='1' bordercolor='black'>
   <tr>
   <td bgcolor='navy' align='center'><b><font color='White'>Tempo</font></b></td>
   <td bgcolor='navy' align='center'><b><font color='White'>Query</font></b></td>
   <td bgcolor='navy' align='center'><b><font color='White'>Link</font></b></td>
   <td bgcolor='navy' align='center'><b><font color='White'>Player</font></b></td>
   <td bgcolor='navy' align='center'><b><font color='White'>Ora</font></b></td>
   <td bgcolor='navy' align='center'><b><font color='White'>N° Player</font></b></td>
   </tr>",true);
   $sql = "SELECT s.*, a.login FROM slowquery s  LEFT JOIN accounts a USING(acctid) ORDER BY ".$_GET['op1']." DESC";
   $result = db_query($sql) or die(db_error(LINK));
   $countrow = db_num_rows($result);
   for ($i=0; $i<$countrow; $i++){
   //for ($i=0; $i < db_num_rows($result); $i++) {
       $row = db_fetch_assoc($result);
       $sqlnome = "SELECT name FROM accounts WHERE acctid = ".$row['acctid'];
       output("<tr><td align='center'>`@".$row['time']."</td>
       <td align='center'>`^".$row['query']."</td>
       <td align='center'>`X".$row['link']."</td>
       <td align='center'>`R".$row['login']."</td>
       <td align='center'>`G".$row['orario']."</td>
       <td align='center'>`f".$row['numplayer']."</td>
       </tr>",true);
   }
   output("</table>",true);
}
page_footer();
?>