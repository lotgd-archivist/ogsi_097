<?php
if (!isset($session)) exit();
$session['user']['specialinc']="skillmaster.php";
switch((int)$session['user']['specialty']){
case 1: case 5: case 9: case 14:
    $c="`$";
    break;
case 2: case 6: case 10: case 13:
    $c="`%";
    break;
case 3: case 7: case 11:
    $c="`^";
    break;
case 4: case 8: case 12:
    $c="`#";
    break;

default:
    output("Non hai uno scopo nella vita, devi fermarti e prendere una decisione importante.");
    $session['user']['specialinc']="";
    addnav("`@Torna alla foresta","forest.php");
}
$skills = array(1 => "`\$Arti Oscure", "`%Poteri Mistici", "`^Furto", "`3Militare","`\$Seduzione","`^Tattica","`@Pelle di Roccia","`#Retorica","`%Muscoli","`3Natura","`&Clima","`^Elementalista","`6Rabbia Barbara","`5Canzoni del Bardo");

if ($_GET['op']=="give"){
    if ($session['user']['gems']>0){
        output("$c Dai a `@Foil`&wench$c una gemma, e lei ti consegna un foglio di pergamena contenente istruzioni su come migliorare nella tua specialità.`n`n");
        output("Lo studi intensamente, lo fai a pezzetti e, onde evitare che le informazioni cadano in mano di infedeli, lo ingoi.`n`n`@Foil`&wench$c sospira... \"`&Non eri");
        output("tenuto a mangiarlo...  Oh beh, adesso vattene da qui!$c\"`#");
        increment_specialty();
        $session['user']['gems']--;
    }else{
        output("$c Consegni la tua gemma immaginaria.  `@Foil`&wench$c ti osserva con sguardo vacuo.  \"`&Torna quando hai una gemma `bvera`b sempliciotto.$c\"`n`n");
        output("\"`#Sempliciotto?$c\" domandi.`n`n");
        output("Con questo, `@Foil`&wench$c ti butta fuori.");
    }
    $session['user']['specialinc']="";
    addnav("`@Torna alla Foresta","forest.php");
}else if($_GET['op']=="dont"){
    output("$c Informi `@Foil`&wench$c che se vuole arricchirsi dovrà faticare per farlo, e te ne vai.");
    $session['user']['specialinc']="";
    addnav("`@Torna alla foresta","forest.php");
}else if($session['user']['specialty']>0){
    output("$c Stai vagando nella foresta quando incontri una strana capanna. Entrando, ti trovi di fronte al volto di un´anziana donna temprata dalle");
    output("battaglie.  \"`&Salute ".$session['user']['name'].", io sono `@Foil`&wench$c, maestra di tutto.$c\"`n`n\"`#Maestra di tutto?$c\" domandi.`n`n");
    output("\"`&Sì, maestra di tutto. Tutti i talenti io controllo e posso insegnare.$c\"`n`n\"`#Puoi insegnare?$c\" chiedi.`n`n");
    output("La donna sospira, \"`&Sì, posso insegnare. Ti insegnerò come migliorare in ".$skills[$session['user']['specialty']]." a due condizioni.$c\"`n`n");
    output("\"`#Due condizioni?$c\" ripeti.`n`n");
    output("\"`&Sì. Primo, mi devi dare una gemma e secondo, devi smetterla di ripetere tutto quello che dico in forma di domanda!$c\"`n`n");
    output("\"`#Una gemma!$c\" affermi decisamente.`n`n");
    output("\"`&Beh... suppongo che quella non fosse una domanda. Allora, per la gemma?$c\"");
    addnav("`@Le dai una gemma","forest.php?op=give");
    addnav("`\$Non le dai una gemma","forest.php?op=dont");
}
?>