<?php
require_once("common.php");
$gemme_vessa = getsetting("selledgems",0);
if ($session['user']['level']<15){
    checkday();
    if ($session['user']['dragonkills']<=15){
        $val_gem = (800 - ((50 * $session['user']['dragonkills'])+($gemme_vessa * 5)));
        if ($val_gem < 500) {
            $val_gem = 500;
        }
    }else{
        $val_gem= 500-($gemme_vessa * 5);
        if ($val_gem < 300) {
            $val_gem = 300;
        }
    }
    // Modifica per prezzo acquisto variabile
    $gemma_1 = (3000-(50*$gemme_vessa))+(50*$session['user']['dragonkills']);
    $gemma_2 = (6000-(102*$gemme_vessa))+(98*$session['user']['dragonkills']);
    $gemma_3 = (9000-(156*$gemme_vessa))+(145*$session['user']['dragonkills']);
    if ($gemma_1 < 2000) {
        $gemma_1 =2000;
    }
    if ($gemma_2 < 3900) {
        $gemma_2 =3900;
    }
    if ($gemma_3 < 5800) {
        $gemma_3 =5800;
    }
    page_header("Il Mercato delle Gemme di Vessa");
    $session['user']['locazione'] = 186;
    addnav("`@Acquista`0");
    addnav("".$gemma_1." oro - 1 gemma","vessa.php?op=buy&level=1");
    addnav("".$gemma_2." oro - 2 gemme","vessa.php?op=buy&level=2");
    addnav("".$gemma_3." oro - 3 gemme","vessa.php?op=buy&level=3");
    addnav("`^Vendi`0");
    addnav("Vendi 1 gemma per ".$val_gem." ","vessa.php?op=sell");
    addnav("`\$Exit`0");
    addnav("Torna al Giardino", "gardens.php");
    addnav("Torna al Villaggio","village.php");
    $gems=array(1=>1,2,3);
    $costs=array(1=>$gemma_1,$gemma_2,$gemma_3);
    if ($_GET['op']==""){
        output("`3Vessa ha delle gemme in vendita. Ma ne acquista anche.`nAttualmente Vessa ha ".$gemme_vessa." gemme");
    }elseif($_GET['op']=="buy"){
        if ($session['user']['gold']>=$costs[$_GET['level']]){
            if (getsetting("selledgems",0) >= $_GET['level']) {
                output("`#Vessa afferra velocemente i tuoi ".($costs[$_GET[level]])." pezzi d'oro e ti da
                ".($gems[$_GET[level]])." ".($gems[$_GET[level]]==1?"gemma":"gemme")." in cambio.`n`n");
                debuglog("compra ".$gems[$_GET[level]]." gemma da Vessa in cambio di ".$costs[$_GET[level]]." oro");
                $session['user']['gold']-=$costs[$_GET[level]];
                $session['user']['gems']+=$gems[$_GET[level]];
                if ((getsetting("selledgems",0) - $_GET['level']) < 1) {
                    savesetting("selledgems","0");
                } else {
                    savesetting("selledgems",(getsetting("selledgems",0)-$_GET['level']));
                }
                redirect("vessa.php");
            } else {
                output("`8Purtroppo Vessa ha già venduto tutte le gemme che aveva, sei arrivato troppo tardi.`n");
                output("Altri esploratori avidi prima di te hanno esaurito le poche gemme che Vessa aveva nel ");
                output("suo negozio.`n`n");
            }
        }else{
            output("`(Vessa ti mostra il dito medio dopo che hai cercato di pagarla meno del valore delle gemme.`n`n");
        }
    }elseif($_GET['op']=="sell"){
        if ($session['user']['gems']<1){
            output("`(Vessa ti fa il gesto dell'ombrello, alzando il polso, sapendo che non hai neanche una gemma.`n`n");
        }else{
            output("`^Vessa accetta la tua gemma e ti posa in mano ".$val_gem." pezzi d'oro in cambio di essa.`n`n");
            debuglog("vende 1 gemma a Vessa in cambio di $val_gem oro");
            $session['user']['gold']+=$val_gem;
            $session['user']['gems']-=1;
            savesetting("selledgems",getsetting("selledgems",0)+1);
            redirect("vessa.php");
        }
    }
}else{
    checkday();
    page_header("Il Mercato delle Gemme di Vessa");
    output("`%Non credi che sarebbe ora di affrontare e uccidere il Drago Verde?");
    addnav("Torna al Giardino", "gardens.php");
    addnav("Torna al Villaggio","village.php");
}
page_footer();
?>