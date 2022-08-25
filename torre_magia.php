<?php
require_once("common.php");
page_header("Torre della magia");
addnav("Acquista");
addnav("".$gemma_1." oro - 1 gemma","vessa.php?op=buy&level=1");
addnav("".$gemma_2." oro - 2 gemme","vessa.php?op=buy&level=2");
addnav("".$gemma_3." oro - 3 gemme","vessa.php?op=buy&level=3");
addnav("Vendi");
addnav("Vendi 1 gemma per ".$val_gem." ","vessa.php?op=sell");
addnav("Exit");
addnav("Torna al Giardino", "gardens.php");
addnav("Torna al Villaggio","village.php");

if($_GET[op]=="buy"){
       
output("Vessa ti mostra il dito medio dopo che hai cercato di pagarla meno del valore delle gemme.`n`n");
   
}elseif($_GET[op]=="sell"){
    if ($session[user][gems]<1){
        output(
        "Vessa ti fa il gesto dell'ombrello, alzando il polso, sapendo che non hai neanche una gemma.`n`n");
    }else{
        output(
        "Vessa accetta la tua gemma e ti posa in mano ".$val_gem." pezzi d'oro in cambio di essa.`n`n");
        debuglog("vende 1 gemma a Vessa in cambio di $val_gem oro");
                $session[user][gold]+=$val_gem;
        $session[user][gems]-=1;
        savesetting("selledgems",getsetting("selledgems",0)+1);
    }

}else{
    checkday();
    page_header("Il Mercato delle Gemme di Vessa");
    output("Non credi che sarebbe ora di affrontare e uccidere il Drago Verde?");
    addnav("Torna al Giardino", "gardens.php");
    addnav("Torna al Villaggio","village.php");
}
page_footer();
?>