<?php
if (!isset($session)) exit();
if ($_GET['op']==""){
    $session['user']['specialinc']="fairy1.php";
    output("`n`3Lungo il tuo cammino ti appare una gracile figura femminile, piccola di statura, dalla pelle chiarissima, ");
    output("quasi perlacea e dai lunghi capelli biondi che le arrivano fino alla vita. Indossa un abito sfarzoso e in ");
    output("testa porta un buffo copricapo conico.`n`n");

    output("`3Hai incontrato una fata della foresta. `n`n\"`GDammi una parte delle tue ricchezze!`3\" ti dice.`n`n Cosa fai?`n`n");
    output("<table cellspacing='0' cellpadding='0'>",true);
    output("<tr><td><a href='forest.php?op=1'>`^Le dai 1000 pezzi d'Oro`0</a></td></tr>", true);
    output("<tr><td><a href='forest.php?op=2'>`&Le dai 1 gemma`0</a></td></tr>", true);
    output("<tr><td><a href='forest.php?op=no'>`\$Non le dai nulla`0</a></td></tr>", true);
    output("</table>",true);
    addnav("O?`^Le dai 1000 pezzi di Oro","forest.php?op=1");
    addnav("G?`&Le dai 1 Gemma","forest.php?op=2");
    addnav("N?`\$Non le dai nulla","forest.php?op=no");
    addnav("","forest.php?op=1");
    addnav("","forest.php?op=2");
    addnav("","forest.php?op=no");

}elseif($_GET['op']=="no"){
        output("`3Non vuoi rischiare oro e gemme e ritieni la piccola creatura una fastidiosa, inutile perdita di tempo.");
        output("Con un gesto inconsulto spiaccichi al suolo la povera creaturina e prosegui indifferente il tuo cammino nella foresta, ");
        output("inconsapevole della punizione che riceverai per aver compiuto un gesto così vile.`n`n");
        $session['user']['evil'] += 20;
        debuglog("uccide la fairy e guadagna 20 punti cattiveria");
}else{
  switch($_GET['op']){
  case 1:
       if ($session['user']['gold']>=1000){
           $session['user']['gold']-= 1000;
           $log = "paga 1000 oro alla fairy, ";
           output("`3Dai alla fata `^1.000`3 dei tuoi `^pezzi d'Oro `3faticosamente guadagnati.`nLei li osserva attentamente, ");
           if ($session['user']['gems']>1){
               output("ma così facendo hai urtato la sua suscettibilità.`nPur avendo gemme a disposizione, non ti sei fidat".($session['user']['sex']?"a":"o")." ");
               output("della fatina scegliendo di darle solo `^1.000 miseri pezzi d'Oro`3.`nOffesissima la creatura, volteggia ");
               output("sopra la tua testa e sparge una `\$polvere fatata rossa`3, la vista ti si oscura, e quando ti risvegli non sai più dove sei.`n");
               output("Impieghi parecchio tempo a ritrovare il sentiero del villaggio e oltre al tuo oro perdi anche un combattimento nella foresta.");
               $session['user']['turns']--;
           }else{
               output("ride deliziata e ti promette un dono in cambio. Volteggia sopra la tua testa e sparge una `Xpolvere fatata dorata`3 ");
               output("su di te prima di scomparire dalla tua visuale. Scopri che ...`n`n`^");
               switch(e_rand(1,7)){
                   case 1:
                           output("Hai ricevuto l'energia per due combattimenti extra nella foresta!");
                           $session['user']['turns']+=2;
                           $log .= "e guadagna 2 turni";
                           debuglog($log);
                   break;
                   case 2:
                   case 3:
                           output("Ti senti particolarmente ricettiv".($session[user][sex]?"a":"o")." e trovi `%UNA`^ gemma nei paraggi!");
                           $session['user']['gems']++;
                           $log .= "e guadagna 1 gemma";
                           debuglog($log);
                   break;
                   case 4:
                   case 5:
                           output("I tuoi punti ferita massimi vengono `bpermanentemente`b aumentati di 1!");
                           $session['user']['maxhitpoints']++;
                           $log .= "e guadagna 1 HP";
                           debuglog($log);
                   break;
                   case 6:
                   case 7:
                           increment_specialty();
                           $log .= "e guadagna specialità";
                           debuglog($log);
                   break;
               }
           }

       }else{
           output("`3Prometti di dare `^1000 pezzi d'Oro `3alla fata, ma quando apri il tuo borsellino scopri di ");
           output("non averne. La fatina svolazza sopra di te, battendo il piede in aria, mentre tenti di ");
           output("spiegarle perché le hai mentito.`n`n");
           output("Avendone abbastanza dei tuoi borbottii, la creatura ti spruzza della `\$polvere fatata rossa `3addosso.`n");
           output("La vista ti si oscura, e quando ti risvegli non sai dove sei. Passi ");
           output("tanto tempo a cercare la strada per tornare al villaggio che perdi `b`rdue`b`3 combattimenti nella foresta.");
           debuglog("vuole dare 1000 oro alla fata ma non li ha e perde 2 turni");
           $session['user']['turns']-=2;
       }
  break;
  case 2:
       if ($session['user']['gems']>0){
           $log = "Paga 1 gemma alla fairy ";
           output("`3Dai alla fata una delle tue gemme faticosamente guadagnate. Lei la guarda, ride deliziata, ");
           output("e ti promette un dono in cambio. Volteggia sopra la tua testa e sparge una `Xpolvere fatata dorata `3");
           output("su di te prima di svolazzare via. Scopri che ...`n`n`^");
           $session['user']['gems']--;

           switch(e_rand(1,7)){

               case 1:
                       output("`FHai ricevuto l'energia per due combattimenti extra nella foresta!`0");
                       $session['user']['turns']+=2;
                       $log .="e guadagna 2 turni";
                       debuglog($log);
               break;
               case 2:
               case 3:
                       output("`RTi senti particolarmente ricettiv".($session[user][sex]?"a":"o")." e trovi `&DUE`R gemme nei paraggi!`0");
                       $session['user']['gems']+=2;
                       $log .="e trova 2 gemme";
                       debuglog($log);
               break;
               case 4:
               case 5:
                       output("`^I tuoi punti ferita massimi vengono `bpermanentemente`b aumentati di 1!`0");
                       $session['user']['maxhitpoints']++;
                       $log .="e guadagna 1 HP";
                       debuglog($log);
               break;
               case 6:
               case 7:
                       increment_specialty();
                       output("`0");
                       $log .="e guadagna specialità";
                       debuglog($log);
               break;
           }

       }else{
           output("`3Prometti di dare una gemma alla fata, ma quando apri il tuo borsellino scopri di ");
           output("non averne. La fatina svolazza sopra di te, battendo il piede in aria, mentre tenti di ");
           output("spiegarle perché le hai mentito.");
           output("`n`nAvendone abbastanza dei tuoi borbottii, ti spruzza della `\$polvere fatata rossa`3 addosso.  ");
           output("La vista ti si oscura, e quando ti risvegli non sai dove sei. Passi ");
           output("tanto tempo a cercare la strada per tornare al villaggio che perdi due combattimenti nella foresta.");
           $session['user']['turns']-=2;

       }
  break;
  }
}
?>