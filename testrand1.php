<?php
require_once("common.php");
page_header("Check Vecchio e_rand");
if (!is_array($result)) $result = array();
$result = unserialize($session['user']['specialmisc']);
switch($_GET['game']){
    case "":
    output("`2Quante iterazioni vuoi fare ?");
    output("<form action='testrand1.php?game=numeri' method='POST'><input name='iter' id='iter'><input type='submit' class='button' value='Iterazioni'></form>",true);
    output("<script language='JavaScript'>document.getElementById('iter').focus();</script>",true);
    addnav("","testrand1.php?game=numeri");
    break;
    case "numeri":
    $result['iter']= intval($_POST['iter']);
    output("`3Numero minimo del test - e_rand(`b`^X`b`3,x)`n");
    output("<form action='testrand1.php?game=numeromax' method='POST'><input name='nummin' id='nummin'><input type='submit' class='button' value='Numero Minimo'></form>",true);
    output("<script language='JavaScript'>document.getElementById('nummin').focus();</script>",true);
    addnav("","testrand1.php?game=numeromax");
    break;
    case "numeromax":
    $result['nummin']= intval($_POST['nummin']);
    output("`3Numero massimo del test - e_rand(x,`b`^X`b`3)`n");
    output("<form action='testrand1.php?game=test' method='POST'><input name='nummax' id='nummax'><input type='submit' class='button' value='Numero Massimo'></form>",true);
    output("<script language='JavaScript'>document.getElementById('nummax').focus();</script>",true);
    addnav("","testrand1.php?game=test");
    break;
    case "test";
    $result['nummax']= intval($_POST['nummax']);
    if ($result['iter'] > 1000000 OR $result['nummax'] > 50 OR $result['nummin'] < -50){
       output("`^Il numero massimo di iterazioni consentite è di `b`\$1.000.000`b`^, il valore minimo dell'e_rand è `b`\$-50`b`^ ed il valore massimo dell'e_rand è `b`\$50`b`^`n");
       output("Reinserisci i valori entro questi limiti");
       $_GET['game'] = "";
       addnav("Riprova","testrand1.php");
    }else{
        for ($x=$result['nummin'];$x<$result['iter'];$x++){
            $test = e_rand1($result['nummin'],$result['nummax']);
            $result[$test]++;
        }
    output("<table cellspacing=2 cellpadding=2 align='center'>",true);
    output("<tr bgcolor='#FF0000'><td align='center'>`b`&Caso`b</td><td align='center'>`b`&Uscite`b</td><td align='center'>`b`&Percentuale</td></tr>",true);
    for ($x=$result['nummin'];$x<($result['nummax']+1);$x++){
        $percent = intval(($result[$x]/$result['iter'])*10000);
        $percent /= 100;
        output("<tr class='" . ($x % 2?"trlight":"trdark") . "'>", true);
        output("<td>`b`@".$x.".`b<td align='center'>`&`b$result[$x]`b</td><td align='center'>`b`^$percent%`b</td></tr>",true);
        //output("`@Caso $x: `#".$result[$x]." ($percent%)`n");
    }
    output("</table>",true);
    }
    $result=array();
    addnav("Villaggio","village.php");
    addnav("Riprova","testrand1.php");
    addnav("Passa al Nuovo","testrand.php");
    break;
}
$session['user']['specialmisc'] = serialize($result);
page_footer();
?>