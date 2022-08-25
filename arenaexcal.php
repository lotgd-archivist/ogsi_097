<?php
require_once "common.php";
checkday();
page_header("Arena dei Combattimenti di Excalibur");
if ($session['user']['alive']){ }else{
    redirect("shades.php");
}
if ($session['user']['turns']>0 ){
output(" `c`b`3Arena dei Combattimenti - Excalibur`b`c`n`n ");
if ($_GET['op']==""){
    output("`3Hai scelto di combattere con `6Excalibur il Barbaro`3.  Il biglietto costa `^100 pezzi d'oro`3. `n");
    output("Ogni combattimento costa 1 Turno Foresta oltre al prezzo del biglietto.");
    addnav("Paga i 100 pezzi d'oro","arenaexcal.php?op=paga");
    addnav("Torna all'Arena","arena.php?op=non paga");

}else if ($_GET['op']=="paga"){
  if ($session['user']['gold']>99){

      output(" `3Paghi il biglietto d'ingresso `^100 pezzi d'oro`3`3. `n");
      output(" Combatti con `6Excalibur il Barbaro`3.  Scopri che ...`n`n");
      addnav("Torna all'Arena","arena.php");
        $session['user']['gold']-=100;
        $session['user']['turns']--;
        debuglog("paga 100 oro per l'arena con Excalibur.");
        switch(e_rand(1,10)){
           case 1: case 2: case 3: case 4: case 5:
                output(" `&Combatti con `3Excalibur il Barbaro`&! Excalibur usa la sua Spada del Colosso!`n");
                output(" Cerchi di respingere i suoi attacchi con la tua ".$session['user']['armor']." ma vieni ferito leggermente!`n");
                output(" Hai perso il combattimento!`n`n");
                $session['user']['turns']--;
                $session['user']['hitpoints']-=10;
                output("`\$Perdi un combattimento nella foresta e 10 punti ferita!!!");
                break;
            case 6: case 7: case 8: case 9:
                $exp=$session['user']['experience']*0.01;
                output(" `&Combatti con `3Excalibur il Barbaro`&! Excalibur usa la sua Spada del Colosso!`n");
                output(" Respingi ogni suo attacco con la tua ".$session['user']['armor']." e rimani illeso!`n");
                output(" Il combattimento è pari! Ricevi `^50 Pezzi d'Oro`&, la metà del costo del biglietto.`n");
                output(" Per l'impegno profuso vieni premiato con l'1% di esperienza.`n");
                $session['user']['gold']+=50;
                $session['user']['experience']+=$exp;
                break;
            case 10:
                $exp=$session['user']['experience']*0.05;
                output(" Combatti con `3Excalibur il Barbaro`&! Excalibur usa la sua Spada del Colosso!`n");
                output(" Attacchi eroicamente con la tua ".$session['user']['weapon']." e ferisci `3Excalibur`&!`n");
                output(" Hai vinto questo combattimento! Ricevi `^500 Pezzi d'oro `&e 5% di esperienza");
                $session['user']['gold']+=500;
                $session['user']['experience']+=$exp;
                debuglog("riceve 500 pezzi d'oro nell'arena");
                break;
        }
    }else{
      output(" `n`n`&Devi avere 100 `^pezzi d'oro`& per poter combattere. ");
      addnav("Torna al Villaggio","village.php");
    }
}else{
      output(" `3Non ti senti abbastanza audace per partecipare a questi eventi sportivi, e quindi abbandoni l'Arena.");
      output("Torna al Villaggio","village.php");
}
}
else {output(" `n`n`&Devi avere almeno un Turno Foresta per poter combattere. ");
      addnav("Torna al Villaggio","village.php");
      page_footer();
      }
page_footer();
?>