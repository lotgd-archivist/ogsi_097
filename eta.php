<?php
require_once("common.php");
page_header("Età Player di LoGD");
if (getsetting("nomedb","logd") == "logd2"){
    $limite = 8;
}else{
    $limite = 2;
}
output("`(<big><big>`b`cLe Età dei Player di `@LoGD`b`c</big></big>`n`n",true);
addnav("V?Torna al Villaggio","village.php");
$sql = "SELECT substring(compleanno ,1,4) AS year,
        COUNT(*) AS player
        FROM accounts
        GROUP BY year ASC";
$result = db_query($sql) or die(db_error(LINK));
$eta = array();
$i = 0;
output("<table align='center'>",true);
output("<tr><td>`b`&Anno Nascita`0`b</td>
        <td  align='center' colspan='2'>`b`&Numero Player`0`b</td></tr>",true);
while ($row = db_fetch_assoc($result)) {
      $len=0;
      $len2=0;
      $perc=0;
      for ($i=0;$i<200;$i+=1){
          if ($row['player']>$i){
              $len+=$limite;
          }
      }
      if ($row['year'] < 1000){
          output("<tr><td>`b`\$PG non validato`b</td>",true);
          output("<td><img src=\"./images/bmeter.gif\" title=\"\" alt=\"\" style=\"width:" . $len . "px; height: 10px;\"></td>",true);
          output("<td>`\$ ".$row['player']." player</td></tr>",true);
      }elseif (1000 < $row['year'] AND $row['year'] < 1970){
          output("<tr><td>`b`\$".$row['year']."`b</td>",true);
          output("<td><img src=\"./images/bmeter.gif\" title=\"\" alt=\"\" style=\"width:" . $len . "px; height: 10px;\"></td>",true);
          output("<td>`\$ ".$row['player']." player</td></tr>",true);
      }elseif (1969 < $row['year'] AND $row['year'] < 1987){
          output("<tr><td>`b`^".$row['year']."`b</td>",true);
          output("<td><img src=\"./images/ometer.gif\" title=\"\" alt=\"\" style=\"width:" . $len . "px; height: 10px;\"></td>",true);
          output("<td>`^ ".$row['player']." player</td></tr>",true);
      }elseif ($row['year'] < 2050){
          output("<tr><td>`b`@".$row['year']."`b</td>",true);
          output("<td><img src=\"./images/hmeter.gif\" title=\"\" alt=\"\" style=\"width:". $len . "px; height: 10px;\"></td>",true);
          output("<td>`@ ".$row['player']." player</td></tr>",true);
      }else{
          output("<tr><td>`b`%Non Inserito`b</td>",true);
          output("<td><img src=\"./images/bmeter.gif\" title=\"\" alt=\"\" style=\"width:". $len . "px; height: 10px;\"></td>",true);
          output("<td>`% ".$row['player']." player</td></tr>",true);
      }
}
output("</table>",true);

page_footer();
?>