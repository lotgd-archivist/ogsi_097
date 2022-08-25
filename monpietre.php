<?php
/* This file is part of "Magic Stones"
* made by Excalibur, refer to pietre.php
* for instructions and copyright notice */
require_once "common.php";
page_header("Le Pietre della Fontana di Aris");
addnav("D?Torna dal Druido","mondruido.php");
addnav("M?Torna al Monastero","monastero.php");
output("`!`b`c<font size='+1'>Le Pietre della Fontana di Aris</font>`c`b`n`n",true);
output("`@Vuoi sapere chi sono i possessori delle `&Pietre della Fontana di Aris`@ e se ce ne sono ancora disponibili ?`n");
output("Eccoti accontentato, mio giovane guerriero.`n");
output("Ogni Pietra ha un potere specifico, unico. Il solo modo per conoscerlo è entrare in possesso della Pietra.`n`n");
output("<table cellspacing=2 cellpadding=2 align='center'>",true);
output("<tr bgcolor='#FF0000'><td align='center'>`&`bPietra N°`b</td><td align='center'>`&`bNome Pietra`b</td><td align='center'>`b`&Guerriero`b</td></tr>",true);
for ($i = 1; $i < 21; $i++){
	$pietra1=$pietre[$i];
    $sql = "SELECT owner FROM pietre WHERE pietra=$i";
    $result = db_query($sql);
    $row = db_fetch_assoc($result);
    if (db_num_rows($result) == 0) {
        $rown[name]="`\$Non assegnata";
    }else {
          $sqln="SELECT name FROM accounts WHERE acctid = {$row['owner']}";
          $resultn = db_query($sqln);
          $rown = db_fetch_assoc($resultn);
    }
    if ($rown[name] == $session[user][name]) {
        output("<tr bgcolor='#007700'>", true);
    } else {
        output("<tr class='" . ($i % 2?"trlight":"trdark") . "'>", true);
        }
    output("<td align='center'>`&".$i."</td><td align='center'>`&`b$pietra1`b</td><td align='center'>`&`b{$rown[name]}`b</td></tr>",true);
}
output("</table>", true);
page_footer();
?>