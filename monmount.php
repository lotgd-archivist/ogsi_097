<?php
require_once "common.php";
page_header("Gli animali di LoGD");
addnav("D?Torna dal Druido","mondruido.php");
addnav("M?Torna al Monastero","monastero.php");
output("`!`b`c<font size='+1'>Lista degli animali disponibili da Merick</font>`c`b`n`n",true);
output("`@Alle stalle di `\$Merick`@ è disponibile una vasta scelta di compagni fidati per le vostre avventure ");
output("nella foresta. Si va dal semplice `^Cane Lupo`@ salendo fino al mito di `^Pegaso`@.`n ");
output("Ognuno di essi ha dei bonus specifici. Chi contribuisce nella fase di attacco, chi in difesa, chi dando ");
output("dei combattimenti supplementari, altri con un mix di tutte queste caratteristiche. `nEcco nella tabella ");
output("tutte le caratteristiche di ogni animale:`n`n");

output("<table cellspacing=2 cellpadding=2 align='center'",true);
output("<tr><td align='center'>`b`@Animale`b</td><td align='center'>`b`&Costo Gemme`b</td><td align='center'>`b`^Costo Oro`b</td>
<td align='center'>`b`#Turni Foresta`b</td><td align='center'>`b`%Accesso Taverna`b</td><td align='center'>`b`#Durata`b</td>
<td align='center'>`b`\$Potenz. Attacco`b</td><td align='center'>`b`&Potenz. Difesa`b</td><td align='center'>`b`^Rigen`b</td>
<td align='center'>`b`\$Riduz. Attacco Nemici`b</td><td align='center'>`b`&Riduz. Difesa Nemici`b</td></tr>",true);
$sql = "SELECT * FROM mounts WHERE mountactive = 1 ORDER BY mountcategory ASC,mountcostgems ASC, mountcostgold ASC";
$result = db_query($sql);
for ($i = 0;$i < mysql_num_rows($result) ;$i++){
    $row = db_fetch_assoc($result);
    $row['mountbuff']=unserialize($row['mountbuff']);
    $taverna="Si";
    $cross="";
    $cross1="";
    $cross2="";
    $cross3="";
    $cross4="";
    if ($row[tavern]==0) $taverna="No";
    if ($row[mountbuff][atkmod]>0) $cross="x";
    if ($row[mountbuff][defmod]>0) $cross1="x";
    if ($row[mountbuff][regen]>0) $cross2="x";
    if ($row[mountbuff][badguyatkmod]>0) $cross3="x";
    if ($row[mountbuff][badguydefmod]>0) $cross4="x";
    output("<tr class='" . ($i % 2?"trlight":"trdark") . "'>", true);
    output("<td>`@{$row[mountname]}</td><td align='right'>`&{$row[mountcostgems]}</td><td align='right'>`^{$row[mountcostgold]}</td>
    <td align='right'>`#{$row[mountforestfights]}</td><td align='center'>`%$taverna</td><td align='right'>`#{$row[mountbuff][rounds]}</td>
    <td align='center'>`\$$cross</td><td align='center'>`&$cross1</td><td align='center'>`^$cross2</td><td align='center'>`\$$cross3</td><td align='center'>`&$cross4</td></tr>",true);
}
output("</table>",true);
output("`n`@Le `#`bx`b`@ nelle colonne di `\$Attacco`@, `&Difesa`@ e `^Rigenerazione`@ indicano la presenza di un moltiplicatore di tali valori. `n");
output("Alcuni animali inoltre posseggono altre abilità, ma preferisco non svelare completamente le caratteristiche di ciascun animale e lascio a te, o nobile guerriero, ");
output("il compito di determinare tali valori ed abilità mancanti.`n");

page_footer();
?>