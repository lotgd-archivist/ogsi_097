<?php
require_once "common.php";
checkday();
page_header("Arena dei Combattimenti di Excalibur");
if ($session['user']['alive']){ }else{
    redirect("shades.php");
}
if ($session['user']['turns']>0) {
output(" `c`b`3Arena dei Combattimenti - Saruman`b`c`n`n ");
if ($_GET['op']==""){
    output("`3Hai scelto di combattere con `6Saruman il Vikingo`3.  Il boglietto costa `^60 pezzi d'oro`3. `n");
    output("Ogni combattimento costa 1 Turno Foresta oltre al prezzo del biglietto.");
    addnav("Paga i 60 pezzi d'oro","arenasaruman.php?op=paga");
    addnav("Torna all'Arena","arena.php?op=nonpaga");

}else if ($_GET['op']=="paga"){
  if ($session['user']['gold']>59){

      output(" `3Paghi il biglietto d'ingresso `^60 pezzi d'oro`3. `n");
      output(" Combatti con `6Saruman il Vikingo`3.  Scopri che ...`n`n");
      addnav("Torna all'Arena","arena.php");
        $session['user']['gold']-=60;
        $session['user']['turns']--;
        debuglog("paga 60 oro per l'arena con Saruman.");
        switch(e_rand(1,10)){
          case 1: case 2: case 3:
                output(" Combatti con Saruman il Vikingo! Saruman usa la sua Ascia da Battaglia Vikinga.`n");
                output(" Cerchi di respingere i suoi attacchi con la tua ".$session['user']['armor']." ma vieni ferito leggermente!`n");
                output(" Hai perso il combattimento!`n`n");
                $session['user']['hitpoints']-=2;
                output("`\$Perdi 2 punti ferita!!");
                break;
            case 4: case 5: case 6: case 7: case 8:
                $exp=$session['user']['experience']*0.005;
                output(" Combatti con Saruman il Vikingo! Saruman usa la sua Ascia da Battaglia Vikinga.`n");
                output(" Respingi ogni suo attacco con la tua ".$session['user']['armor']." e rimani illeso!`n");
                output(" Il combattimento è pari! Ricevi `^30 Pezzi d'Oro`&, la metà del costo del biglietto.");
                output(" Per l'impegno profuso vieni premiato con lo 0.5% di esperienza.`n");
                                $session['user']['experience']+=$exp;
                                $session['user']['gold']+=30;
                break;
            case 9: case 10:
                $exp=$session['user']['experience']*0.01;
                output(" Combatti con Saruman il Vikingo! Saruman usa la sua Ascia da Battaglia Vikinga.`n");
                output(" Attacchi eroicamente con la tua ".$session['user']['weapon']." e ferisci Saruman!`n");
                output("Hai vinto questo combattimento! Ricevi `^100 Pezzi d'oro `&e l'1% di esperienza");
                $session['user']['gold']+=100;
                $session['user']['experience']+=$exp;
                debuglog("riceve 100 pezzi d'oro nell'arena");
                break;
           }
  }else{
      output(" `n`n`&Devi avere 60 `^pezzi d'oro`& per poter combattere. ");
      addnav("Torna al Villaggio","village.php");
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