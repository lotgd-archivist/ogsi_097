<?php
require_once "common.php";
checkday();
page_header("Arena dei Combattimenti di Excalibur");
if ($session['user']['alive']){ }else{
    redirect("shades.php");
}
if ($session['user']['turns']>0 ) {
output(" `c`b`3Arena dei Combattimenti - Luke`b`c`n`n ");
if ($_GET['op']==""){
    output("`3Hai scelto di combattere con `6Luke il Normanno`3.  Il biglietto costa `^80 pezzi d'oro`3. `n");
    output("Ogni combattimento costa 1 Turno Foresta oltre al prezzo del biglietto.");
    addnav("Paga gli 80 pezzi d'oro","arenaluke.php?op=paga");
    addnav("Torna all'Arena","arena.php?op=nonpaga");

}else if ($_GET['op']=="paga"){
  if ($session['user']['gold']>79){

      output(" `3Paghi il biglietto d'ingresso `^80 pezzi d'oro`3. `n");
      output(" Combatti con `6Luke il Normanno`3.  Scopri che ...`n`n");
      addnav("Torna all'Arena","arena.php");
        $session['user']['gold']-=80;
        $session['user']['turns']--;
        debuglog("paga 80 oro per l'arena con Luke.");
        switch(e_rand(1,10)){
          case 1: case 2: case 3: case 4:
                output(" `&Combatti con `3Luke il Normanno`&! Luke usa la sua Scimitarra.`n");
                output(" Cerchi di respingere i suoi attacchi con la tua ".$session['user']['armor']." ma vieni ferito leggermente!`n");
                output(" Hai perso il combattimento!`n`n");
                $session['user']['hitpoints']-=8;
                output("`$Perdi 8 punti ferita");
                break;
            case 5: case 6: case 7: case 8:
                $exp=$session['user']['experience']*0.01;
                output(" `&Combatti con Luke il Normanno! Luke usa la sua Scimitarra.`n");
                output(" Respingi ogni suo attacco con la tua ".$session['user']['armor']." e rimani illeso!`n");
                output(" Il combattimento è pari! Ricevi `^40 Pezzi d'Oro`&, la metà del costo del biglietto.");
                output(" Per l'impegno profuso vieni premiato con l'1% di esperienza.`n");
                $session['user']['experience']+=$exp;
                $session['user']['gold']+=40;
                break;
            case 9: case 10:
                $exp=$session['user']['experience']*0.02;
                output(" `&Combatti con `3Luke il Normanno`&! Luke usa la sua Scimitarra.`n");
                output(" Attacchi eroicamente con la tua ".$session['user']['weapon']." e ferisci Luke!`n");
                output(" Hai vinto questo combattimento! Ricevi `^150 Pezzi d'oro `&e 2% di esperienza");
                $session['user']['gold']+=150;
                $session['user']['experience']+=$exp;
                debuglog("riceve 150 pezzi d'oro nell'arena");
                break;
        }
    }else{
      output(" `n`n`&Devi avere 80 `^pezzi d'oro`& per poter combattere. ");
      addnav("Torna al villaggio","village.php");
    }
}else{
      output(" `3Non ti senti abbastanza audace per partecipare a questi eventi sportivi, e quindi abbandoni l'Arena.");
      output("Torna al Villaggio","village.php");
}
}
else {output(" `n`n`&Devi avere almeno un Turno Foresta per poter combattere. ");
      addnav("Torna al Villaggio","village.php");
          }

page_footer();
?>