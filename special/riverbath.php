<?php
/*
river for bathing
Version 0.1
Type: Forest Event
Web: http://www.pqcomp.com/logd
E-mail: logd@pqcomp.com
Author: Lonny Luberts
*/
require_once "common.php";
//checkday();
$dea="Myrionymos";
$session['user']['specialinc']="riverbath.php";
if ($_GET['op'] == ""){
output("`2Girovagando per la foresta ti imbatti in un piccolo torrente, le cui acque paiono fresche e pulite. ");
output("Le frasche degli alberi secolari ti riparano dal sole cocente, e questo angolo di paradiso sembra ");
output("proprio il posto giusto per fare un bagno!`nCosa decidi?");
addnav("`^Fai un Bagno","forest.php?op=bathe");
addnav("`@Torna alla Foresta","forest.php?op=continue");
}
if ($_GET['op'] == "bathe"){
    $session['user']['clean']=0;
    $session['user']['turns']-=1;
    output("Ti spogli completamente e ti getti nel torrente.  Il bagno ti lascia ristorato e pulito, ti senti decisamente meglio!");
    $session['user']['specialinc']="";
    addnav("`@Torna alla Foresta","forest.php");
}
if ($_GET['op'] == "continue"){
    addnav("`@Torna alla Foresta","forest.php");
    $session['user']['specialinc']="";
    output("`2Decidi di non sprecare il tuo tempo per un'attività futile come il lavarsi, e cerchi così di raggiungere ");
    output("la sponda opposta del torrente camminando sulle rocce che affiorano dall'acqua. Ma proprio mentre posi il ");
    output("piede sull'argine del torrente, una figura eterea si materializza accanto a te.`nUna splendida fanciulla, ");
    output("con la chioma ornata da ghirlande di rose a bordo di un carro trainato da delicate colombe e spendidi cigni.`n");
    output("Resti affascinato dalla sua bellezza e ascolti incantato la sua voce melodiosa.`n`n");
    output("`3\"`#Sono la dea $dea, scesa dal Monte Olimpo su queste lande per premiare l'igiene dei mortali.`n");
    $clean = $session['user']['clean'] * 5;
    if ($clean <= 29){
       output("Vedo di fronte a me un valoroso guerriero, un eroe sempre impavido di fronte al pericolo e che, oltre ");
       output("a dimostrare il proprio valore in battaglia, si preoccupa anche di aver cura del proprio aspetto e ");
       output("della propria igiene.`nPer questo motivo ho deciso di premiarti perchè il ricordo del nostro incontro ");
       output("resti vivido nella tua memoria.`3\"`n`n");
       switch(e_rand(1,3)){
           case 1:
           $gain = e_rand(2,3);
           output("`@Guadagni `&$gain `@Punti Fascino!!`n");
           $session['user']['charm']+=$gain;
           debuglog("viene premiato con $gain fascino dalla dea $dea al torrente");
           break;
           case 2:
           $gain = e_rand(5,15)*100;
           output("`@Guadagni `&$gain `@monete d'oro!!`n");
           $session['user']['gold']+=$gain;
           debuglog("viene premiato con $gain oro dalla dea $dea al torrente");
           break;
           case 3:
           output("`@Guadagni `&1 `@Gemma!!`n");
           $session['user']['gems']++;
           debuglog("viene premiato con 1 gemma dalla dea $dea al torrente");
           break;
       }
    }elseif ($clean <= 99){
       output("Vedo di fronte a me un leale guerriero reduce da molte battaglie, un eroe che dovrebbe però avere ");
       output("maggior cura di se stesso.`nPer questo motivo ti consiglio di far visita alle docce cittadine più ");
       output("di frequente e a mantenere in efficienza il tuo abbigliamento. Tieni, queste monete ti saranno utili ");
       output("per accedere ai bagni pubblici di Rafflingate.`3\"`n`n");
       output("`@Guadagni `&5 `@monete ... sempre meglio di niente.`n");
       $session['user']['gold']+=5;
       debuglog("viene premiato con 5 monete dalla dea $dea al torrente");
    }elseif ($clean > 19){
       output("Vedo di fronte a me un brutale guerriero, un barbaro che pensa solo alla battaglia trascurando il  ");
       output("proprio aspetto, un essere animalesco che fa della lordura il proprio stile di vita.`nPer questo ");
       output("motivo ti riporto alla condizione di suino, acciocchè tu possa sguazzare nella fanghiglia che ");
       output("dimostri di amare così tanto.`3\"`n`n");
       output("`5Perdi `^5 Punti Fascino`5 e acquisisci il titolo di `%PigPen !!!`n`n");
       $session['user']['charm']-=5;
       debuglog("perde 5 fascino e diventa PigPen dalla dea $dea al torrente");
       addnews("`@La dea $dea assegna il titolo di `%PigPen `@a `#".$session['user']['name']."`@ per la
       sua ritrosia nell'entrare in contatto con l'acqua.");
       assegnapigpen($session['user']['name']);

    }
//    redirect("forest.php");
}
?>
