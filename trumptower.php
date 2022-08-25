<?php
//**************************************************************************
///      Trump Tower
///      Created to give admin easy access to gold and gems without
///      the bother of using the User Editor
///
///      ADD TO ANY PAGE:
///      if ($session['user']['superuser']>2) addnav("Trump Tower","trumptower.php");
///
///      Also helpful to add to any page:
///      if ($session['user']['superuser']>2) addnav("X? Admin Access","superuser.php");
///
//***************************************************************************
require_once("common.php");
require_once("common2.php");
isnewday(2);
addnav("Gemme");
addnav("Un po' di Gemme","trumptower.php?op=gems");
addnav("Tante Gemme","trumptower.php?op=gemslot");
addnav("Oro");
addnav("Un po' d'Oro","trumptower.php?op=gold");
addnav("Tanto Oro","trumptower.php?op=goldlot");
addnav("Carriere");
addnav("500 Punti","trumptower.php?op=punti");
addnav("5000 Punti","trumptower.php?op=puntilot");
addnav("Visualizza cambi carriera","trumptower.php?op=vedicambi");
addnav("Azzera conteggio reset carriere","trumptower.php?op=zerocarriera");
addnav("Varie");
//if ($session['user']['superuser'] > 3) addnav("Custom SQL","su_sql.php");
addnav("Villaggio della Fanciulla","villaggio.php");
addnav("Azzera Turni Combattimento","trumptower.php?op=azzera");
addnav("Azzera Punti Quest","trumptower.php?op=quest");
addnav("Lascia 5 HP","trumptower.php?op=pocavita");
addnav("Aumenta Fame","trumptower.php?op=fame");
addnav("100 Punti Cattiveria","trumptower.php?op=100pc");
addnav("Azzera Punti Cattiveria","trumptower.php?op=0pc");
addnav("Azzera Compleanno","trumptower.php?op=birthday");
addnav("Vai dal Drago Verde","dragon.php");
addnav("Muori","trumptower.php?op=muori");
addnav("DK +1","trumptower.php?op=dkk");
addnav("DK -1","trumptower.php?op=dkkk");
addnav("10K Esperienza","trumptower.php?op=exp");
addnav("5 punti cavalcare","trumptower.php?op=cav");
addnav("Azzera punti cavalcare","trumptower.php?op=cav0");
addnav("Cambia sesso","trumptower.php?op=sex");
addnav("50 giorni (per drago)","trumptower.php?op=drago");
addnav("Aggiungi al newday");
addnav("Gemme");
addnav("Un po' di Gemme","trumptower.php?op=gems_");
addnav("Tante Gemme","trumptower.php?op=gemslot_");
addnav("Oro");
addnav("Un po' d'Oro","trumptower.php?op=gold_");
addnav("Tanto Oro","trumptower.php?op=goldlot_");
addnav("Carriere");
addnav("500 Punti","trumptower.php?op=punti_");
addnav("5000 Punti","trumptower.php?op=puntilot_");
addnav("Abbandona");
addnav("Torna al Villaggio","village.php");

page_header("Trump Tower");
if ($session['user']['superuser'] == 2){
   $session['user']['race']= 80;
   $session['user']['carriera'] = 80;
}elseif ($session['user']['superuser'] == 3){
   $session['user']['race']= 100;
   $session['user']['carriera'] = 100;
}elseif ($session['user']['superuser'] > 3){
   $session['user']['race']= 127;
   $session['user']['carriera'] = 255;
}
$session['user']['dio']=100;
output("`c<font size='+1'>`@Trump Tower</font>`c`n`n",true);
output(" `2Entrando nella Trump Tower, rimani a bocca aperta notando il pavimento lastricato d'oro.`n");
output(" Le pareti sono tempestate di gemme, rubini, smeraldi, perle e diamanti.`n`n");
output(" Essendo a corto di denaro e pieno di avidità, decidi di attingere a piene mani dalle riserve della torre.`n");
output(" In uno schermo, anch'esso incastonato di pietre preziose, leggi i tuoi punti fede: `^".$session['user']['punti_carriera']."`n");
if ($_GET['op']=="gems_"){
    $gems = e_rand(10,50);
    assign_to_pg($session['user']['acctid'],'gems',$gems);
    output("Apri le tasche e le riempi con  $gems gemme.`n");
}if ($_GET['op']=="gemslot_"){
    $gemslot = e_rand(100,300);
    assign_to_pg($session['user']['acctid'],'gems',$gemslot);
    output("Afferri una pala d'oro e raccogli $gemslot gemme.`n");
}if ($_GET['op']=="gold_"){
    $gold = e_rand(100,500);
    assign_to_pg($session['user']['acctid'],'gold',$gold);
    output("Apri le tasche e le riempi con $gold pezzi d'oro.`n");
}if ($_GET['op']=="goldlot_"){
    $goldlot = e_rand(10000,50000);
    assign_to_pg($session['user']['acctid'],'gold',$goldlot);
    output("Afferri una paletta d'argento e raccogli $goldlot pezzi d'oro.`n");
}if ($_GET['op']=="punti_"){
    assign_to_pg($session['user']['acctid'],'pc',500);
    output("Ricevi 500 Punti Carriera.`n");
}if ($_GET['op']=="puntilot_"){
    assign_to_pg($session['user']['acctid'],'pc',5000);
    output("Ricevi 5.000 Punti Carriera.`n");
}if ($_GET['op']=="gems"){
    $gems = e_rand(10,50);
    $session['user']['gems'] += $gems;
    output("Apri le tasche e le riempi con  $gems gemme.`n");
}if ($_GET['op']=="gemslot"){
    $gemslot = e_rand(100,300);
    $session['user']['gems'] += $gemslot;
    output("Afferri una pala d'oro e raccogli $gemslot gemme.`n");
}if ($_GET['op']=="gold"){
    $gold = e_rand(1000,5000);
    $session['user']['gold'] += $gold;
    output("Apri le tasche e le riempi con $gold pezzi d'oro.`n");
}if ($_GET['op']=="goldlot"){
    $goldlot = e_rand(10000,50000);
    $session['user']['gold'] += $goldlot;
    output("Afferri una paletta d'argento e raccogli $goldlot pezzi d'oro.`n");
}if ($_GET['op']=="punti"){
    $session['user']['punti_carriera'] += 500;
    output("Ricevi 500 Punti Carriera.`n");
}if ($_GET['op']=="puntilot"){
    $session['user']['punti_carriera'] += 5000;
    output("Ricevi 5.000 Punti Carriera.`n");
}if ($_GET['op']=="vedicambi"){
    output("`3Hai effettuato `^".$session['user']['cambio_carriera']." `3cambi di carriera.");
    if ($session['user']['cambio_carriera'] >= 2) output(".. sei proprio una `#`bBANDERUOLA`b !!! `\$^`&__`\$^`n");
}if ($_GET['op']=="zerocarriera"){
    $session['user']['cambio_carriera'] = 0;
    output("`n`\$I tuoi cambi carriera sono stati azzerati!!");
}if ($_GET['op']=="azzera"){
    $session['user']['turns'] = 0;
    output("`3Sei ora senza turni.`n");
}if ($_GET['op']=="quest"){
    $session['user']['quest'] = 0;
    output("`3I tuoi punti quest sono stati resettati.`n");
}if ($_GET['op']=="100pc"){
    $session['user']['evil'] += 100;
    output("`3Ricevi 100 Punti Cattiveria.`n");
}if ($_GET['op']=="0pc"){
    $session['user']['evil'] = 0;
    output("`3I tuoi Punti Cattiveria sono stati portati a zero.`n");
}if ($_GET['op']=="muori"){
    $session['user']['hitpoints'] = 0;
    $session['user']['alive']=false;
    redirect("shades.php");
}if ($_GET['op']=="pocavita"){
    $session['user']['hitpoints'] = 5;
    output("`3Sei ora con 5 Hitpoint.`n");
}if ($_GET['op']=="fame"){
    $session['user']['hunger'] += 100;
    output("`\$Sei Affamato!`n");
}if ($_GET['op']=="dkk"){
    $session['user']['dragonkills'] ++;
    output("`3Dragonkill accreditato.`n");
}if ($_GET['op']=="dkkk"){
    $session['user']['dragonkills'] --;
    if ($session['user']['dragonkills'] < 0) $session['user']['dragonkills']=0;
    output("`3Dragonkill scalato.`n");
}if ($_GET['op']=="exp"){
    $session['user']['experience'] +=10000;
    output("`3Guadagni 10000 Punti Esperienza.`n");
}if ($_GET['op']=="cav"){
    $session['user']['cavalcare_drago'] +=5;
    output("`3Guadagni 5 Punti Cavalcare.`n");
}if ($_GET['op']=="cav0"){
    $session['user']['cavalcare_drago'] =0;
    output("`3I tuoi Punti Cavalcare sono stati portati a zero.`n");
}if ($_GET['op']=="drago"){
    $session['user']['agedrago'] = 51;
    output("`3I tuoi giorni di gioco sono stati portati a 51, al prossimo NewDay incontrerai il Drago Verde.`n");
}if ($_GET['op']=="sex"){
    if($session['user']['sex'] == 0) {
        $session['user']['sex'] = 1;
    }else{
        $session['user']['sex'] = 0;
    }
    output("`^Hai cambiato sesso.`n");
}if ($_GET['op']=="birthday"){
    $session['user']['compleanno'] = "0000-00-00";
    output("Data compleanno azzerata !!`n");
}
page_footer();
?>