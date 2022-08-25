<?php
require_once "common.php";
$i=0;
$spazio="|";
page_header("Le Quest di LoGD");
output(" `3`c`bLe Quest di LoGD`b`c`n`n`3");
addnav("D?Torna dal Druido","mondruido.php");
addnav("M?Torna al Monastero","monastero.php");
output("`!`b`c<font size='+1'>Lista delle Quest accessibili per Rango e per Livello.</font>`c`b`n`n",true);
output("`@Le quest per Titolo Nobiliare hanno la precedenza su quelle per livello, quindi se siete ");
output("`%Esploratore`@ di livello `%5`@, avreste accesso sia alla quest del `#Cimitero`@ sia a quella del `^Labirinto`@, ");
output("ma potrete accedere solamente a quella del `#Cimitero`@.`n`n");
output("Alcune quest hanno finali multipli, alcuni dei quali consumano tutti i `i`b`\$Punti Quest`b`i`@ disponibili, mentre ");
output("altri ne consumano solo una parte. Dopo aver speso i `i`b`\$Punti Quest`b`i`@ a propria disposizione, non si potrà ");
output("affrontare altre quest, ma si dovrà passare al Titolo  Nobiliare successivo per riazzerare il conto dei `i`b`\$Punti Quest`b`i`@ ");
output("e poter avere accesso nuovamente alle quest relative al nuovo Titolo Nobiliare ed ai livelli ad esso associati.`n");

$livello_DK['0'] = "Contadino";
$livello_DK['1'] = "Esploratore";
$livello_DK['2'] = "Scudiero";
$livello_DK['3'] = "Gladiatore";
$livello_DK['4'] = "Legionario";
$livello_DK['5'] = "Centurione";
$livello_DK['6'] = "Sir";
$livello_DK['7'] = "Castaldo";
$livello_DK['8'] = "Camerlengo";
$livello_DK['9'] = "Maggiore";
$livello_DK['10'] = "Barone";
$livello_DK['11'] = "Conte";
$livello_DK['12'] = "Visconte";
$livello_DK['13'] = "Marchese";
$livello_DK['14'] = "Cancelliere";
$livello_DK['15'] = "Principe";
$livello_DK['16'] = "Re";
$livello_DK['17'] = "Imperatore";
$livello_DK['18'] = "Angelo";
$livello_DK['19'] = "Arcangelo";
$livello_DK['20'] = "Principato";
$livello_DK['21'] = "Potere";
$livello_DK['22'] = "Virtù";
$livello_DK['23'] = "Dominio";
$livello_DK['24'] = "Throne";
$livello_DK['25'] = "Cherubino";
$livello_DK['26'] = "Serafino";
$livello_DK['27'] = "Semidio";
$livello_DK['28'] = "Titano";
$livello_DK['29'] = "ArchTitano";
$livello_DK['30'] = "Dio Minore";
$livello_DK['31'] = "Dio";

$quest_DK['1'] = "`@Cimitero`0";
$quest_DK['2'] = "`\$Goblin`0";
$quest_DK['3'] = "`#Selva Oscura`0";
$quest_DK['4'] = "`%Test di Excalibur`0";
$quest_DK['5'] = "`@Cimitero`0";
$quest_DK['6'] = "`\$Goblin`0";
$quest_DK['7'] = "`#Selva Oscura`0";
$quest_DK['8'] = "`%Test di Excalibur`0";
$quest_DK['9'] = "`^Re degli Elfi`0";
$quest_DK['10'] = "`@Cimitero`0";
$quest_DK['11'] = "`(Municipio`0";
$quest_DK['12'] = "`\$Goblin`0";
//$quest_DK['13'] = "`(Municipio`0";
$quest_DK['14'] = "`#Selva Oscura`0";
$quest_DK['15'] = "`(Municipio`0";
$quest_DK['16'] = "`%Test di Excalibur`0";
$quest_DK['17'] = "`(Municipio`0";
$quest_DK['18'] = "`^Re degli Elfi`0";

$quest_LVL['1'] = "`b`#RUOTA DELLA FORTUNA`b`0";
$quest_LVL['3'] = "`b`#LA SCORTA`b`0";
//$quest_LVL['4'] = "`b`#TEST DI EXCALIBUR`b`0";
$quest_LVL['5'] = "`b`#LABIRINTO`b`0";
$quest_LVL['8'] = "`b`#FANCIULLA`b`0";
$quest_LVL['11'] = "`b`#TORRE NERA`b`0";
$quest_LVL['12'] = "`b`#TORRE NERA`b`0";
//$quest_LVL['13'] = "`b`#SELVA OSCURA`b`0";

$quest_ACC['1'] = "Campo Zingari";
$quest_ACC['3'] = "Bosco a Sud";
$quest_ACC['4'] = "Bosco a Sud";
$quest_ACC['5'] = "Bosco a Sud";
$quest_ACC['8'] = "Foresta";
$quest_ACC['11'] = "Bosco a Sud";
$quest_ACC['12'] = "Bosco a Sud";
//$quest_ACC['13'] = "Bosco a Sud";

output("<table cellspacing=0 cellpadding=2 align='center' name='Quadro sinottico Quest LoGD'><tr><td valign='top'>",true);
    output("<table name='Quest per Titolo Nobiliare'><tr><td>`bDK`b</td><td>`bRango`b</td><td>`bQuest`b</td></tr>",true);
    for ($i=0; $i<=18; $i++){
        output("<tr class='".($i%2?"trlight":"trdark")."'><td align='right'>".$i."</td><td>`b$livello_DK[$i]`b</td><td align='center'>`b$quest_DK[$i]`b</td></tr>",true);
    }
    output("</table></td><td width='30'></td><td valign='top'><table name='Quest per Livello'><tr><td>`bLvl`b</td><td>`bQuest`b</td><td>`bAccesso`b</td></tr>",true);
//output("</td><td>",true);
    //output("<table name='Quest per Livello'><tr><td>Livello</td><td>Quest</td><td>Accesso</td><tr>",true);
    for ($i=1; $i<=12; $i++){
        output("<tr class='".($i%2?"trlight":"trdark")."'><td align='right'>".$i."</td><td>$quest_LVL[$i]</td><td align='center'>`b`6$quest_ACC[$i]`0`b</td></tr>",true);
    }
    output("</table>",true);
output("</td></tr></table>",true);

/*
output("<table cellspacing=0 cellpadding=2 align='center'><tr><td>`bDK`b</td><td>`bRango`b</td><td>`bQuest`b</td><td>$spazio</td><td align='center'>`bQuest->Livello`b</td></tr>",true);
for( $i = 0; $i <= 15; $i++ ) {
    output("<tr class='".($i%2?"trlight":"trdark")."'><td>".  $i  ."</td><td>`b$livello_DK[$i]`b</td><td>`b$quest_DK[$i]`b</td><td>$spazio</td><td align='center'>$quest_LVL[$i]</td></tr>",true);
    }
*/

output("`n`n`n");

page_footer();
?>