<?php
require_once("common.php");
$gemme_vessa = getsetting("selledgems",0);
if ($session['user']['level']<15){
    checkday();
if ($session['user']['dragonkills']<=15){
$val_gem = (4000 - ((50 * $session['user']['dragonkills'])+($gemme_vessa * 5)));
if ($val_gem < 900) {
    $val_gem = 900;
    }
}else{
$val_gem= 999 - ($gemme_vessa * 5);
if ($val_gem < 500) {
    $val_gem = 500;
    }}
    // Modifica per prezzo acquisto variabile
    $gemma_1 = (2000-(25*$gemme_vessa))+(25*$session['user']['dragonkills']);
    $gemma_2 = (4000-(51*$gemme_vessa))+(48*$session['user']['dragonkills']);
    $gemma_3 = (6000-(78*$gemme_vessa))+(75*$session['user']['dragonkills']);
if ($gemma_1 < 1000) {
    $gemma_1 =1000;
    }
if ($gemma_2 < 2000) {
    $gemma_2 =2000;
    }
if ($gemma_3 < 3000) {
    $gemma_3 =3000;
    }

if ($val_gem > $gemma_1) $val_gem=$gemma_1-100;
page_header("Le Pietre Preziose di Virna");
$session['user']['locazione'] = 189;
addnav("Acquista");
addnav("`@".$gemma_1." oro - 1 gemma","virna.php?op=buy&level=1");
addnav("`@".$gemma_2." oro - 2 gemme","virna.php?op=buy&level=2");
addnav("`@".$gemma_3." oro - 3 gemme","virna.php?op=buy&level=3");
addnav("Vendi");
addnav("`\$Vendi 1 gemma per ".$val_gem." pezzi d'oro","virna.php?op=sell");
addnav("Exit");
addnav("`#Torna al Borgo","villaggio.php");

$gems=array(1=>1,2,3);
$costs=array(1=>$gemma_1,$gemma_2,$gemma_3);
if ($_GET[op]==""){
    output("
Virna ha delle gemme in vendita. Ma ne acquista anche.`nAttualmente Virna ha ".$gemme_vessa." gemme");
}elseif($_GET[op]=="buy"){
        if ($session[user][gold]>=$costs[$_GET[level]]){
           if (getsetting("selledgems",0) >= $_GET[level]) {

output(
"Virna afferra velocemente i tuoi ".($costs[$_GET[level]])." pezzi d'oro
e ti da ".($gems[$_GET[level]])." ".($gems[$_GET[level]]==1?"gemma":"gemme")." in cambio.`n`n");
            debuglog("compra {$gems[$_GET[level]]} gemma da Virna in cambio di {$costs[$_GET[level]]} oro");
            $session[user][gold]-=$costs[$_GET[level]];
              $session[user][gems]+=$gems[$_GET[level]];
              if (getsetting("selledgems",0) - $_GET[level] < 1) {
                savesetting("selledgems","0");
              } else {
                savesetting("selledgems",getsetting("selledgems",0)-$_GET[level]);
              }
           } else {
output("Purtroppo Virna ha già venduto tutte le gemme che aveva, sei arrivato troppo tardi.`n");
output("Altri esploratori avidi prima di te hanno esaurito le poche gemme che Virna aveva nel suo negozio.`n`n");
           }
        }else{
output("Virna ti fa il gesto dell'ombrello dopo che hai cercato di pagarla meno del valore delle gemme.`n`n");
        }
}elseif($_GET[op]=="sell"){
    if ($session[user][gems]<1){
        output(
        "Virna ti fa il gesto dell'ombrello, alzando il polso, sapendo che non hai neanche una gemma.`n`n");
    }else{
        output(
        "Virna accetta la tua gemma e ti posa in mano ".$val_gem." pezzi d'oro in cambio di essa.`n`n");
        debuglog("vende 1 gemma a Virna in cambio di $val_gem oro");
        $session[user][gold]+=$val_gem;
        $session[user][gems]-=1;
        savesetting("selledgems",getsetting("selledgems",0)+1);
    }
}
}else{
    checkday();
    page_header("Le Pietre Preziose di Virna");
    output("Non credi che sarebbe ora di affrontare e uccidere il Drago Verde?");
addnav("`#Torna al Borgo","villaggio.php");
}
page_footer();
?>