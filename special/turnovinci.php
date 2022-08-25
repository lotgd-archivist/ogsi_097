<?php
/****************************
Rendiamo onore ai guerrieri *
più meritevoli del Reame    *
*****************************/

if (!isset($session)) exit();
$rand = e_rand(1,10);
switch ($rand) {
    case 1: case 2:
    $warrior="`3EXCALIBUR";
    break;
    case 3: case 4:
    $warrior="`2LUKE";
    break;
    case 5:
    $warrior="`4SARUMAN";
    break;
    case 6:
    $warrior="`5ARIS";
    break;
    case 7:
    $warrior="`1LABAT";
    break;
    case 8:
    $warrior="`7DANKOR";
    break;
    case 9:
    $warrior="`&HUTGARD";
    break;
    case 10;
    $warrior="`%OBERON GLOIN";
    break;
}
output("`^Camminando nella foresta trovi un otre ripieno appoggiato alle radici di un albero.`n
  Poichè riconosci i sigilli inscritti sull'otre come quelli di `b`i$warrior`^`b`i, un potente guerriero del villaggio,`n
  decidi che potrebbe essere salutare berne una sorsata.`n`n`nMhhh, è potente questo elisir!  Ti senti `b`!POTENTE`^`b.`n`n`%Ricevi 1`^ combattimento extra!`0");

$session[user][turns]++;
addnav("Torna alla Foresta","forest.php");
?>