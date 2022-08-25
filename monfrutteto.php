<?php

require_once "common.php";
checkday();

page_header("Il Frutteto del Monastero");
if ($_GET['op']==""){
addnav("Il Frutteto");
addnav("Mela","monfrutteto.php?op=mela");
addnav("Pera","monfrutteto.php?op=pera");
addnav("Ciliege","monfrutteto.php?op=ciliege");
addnav("Altro");
addnav("Torna al Monastero","monastero.php");
output(" `^`c`bIl Frutteto del Monastero`b`c`n`n");
output(" `2Ti avvicini ad un'area dove un cartello indica chiaramente `^*Vietato l'Accesso*`2. `n");
output(" Aldilà della misera staccionata di legno, puoi vedere molti alberi da frutto. `n");
output(" Sono chiaramente ben curati e sui loro rami osservi i più bei frutti che tu abbia mai visto. `n");
output(" I monaci sono stati così amichevoli con te, ti azzarderai a derubarli?. `n");
output(" Forse non dovresti sfidare il destino. `n`n");
}

if ($_GET['op']=="mela"){
   output(" `&Il Frutteto del Monastero è cibo per i Monaci che vivono qui. `n");
   output(" `&Ma hai deciso che la tua fame è maggiore di ogni stupida superstizione. `n");
   output(" `2E ti avvicini all'Albero delle Mele.`n");
   output(" Prendi e mangi molte delle deliziose mele mettendo a tacere la tua fame. `n");
   output(" Mentre ti sdrai sotto il Melo, `b`&F U L M I N I _ _ A _ _ C I E L _ _ S E R E N O !`b `n");
   output(" `2Un serpente si avvicina e ti morde!. `n");
   output(" Non ti senti molto bene! `^Per aver ignorato uno `bstupido avvertimento`b, perdi alcuni dei tuoi HP. `n");
   addnav("Altro");
   addnav("Torna al Monastero","monastero.php");
   if ((($session['user']['maxhitpoints']-3)/$session['user']['level']) > 10){
      debuglog(" Perde 3 maxHP nel frutteto");
      $session['user']['maxhitpoints']-=3;
      $session['user']['hitpoints']-=3;
   }else{
      $session['user']['hitpoints']=1;
   }

}

if ($_GET['op']=="pera"){
   output(" `&Il Frutteto del Monastero è cibo per i Monaci che vivono qui. `n");
   output(" `&Ma hai deciso che la tua fame è maggiore di ogni stupida superstizione. `n");
   output(" `2E ti avvicini all'Albero delle Pere.`n");
   output(" Prendi e mangi molte delle deliziose pere mettendo a tacere la tua fame. `n");
   output(" Mentre ti sdrai sotto il Pero, `b`&F U L M I N I _ _ A _ _ C I E L _ _ S E R E N O !`b `n");
   output(" `2Un serpente si avvicina e ti morde!. `n");
   output(" Non ti senti molto bene! `^Per aver ignorato uno `bstupido avvertimento`b, perdi alcuni dei tuoi HP. `n");
   addnav("Altro");
   addnav("Torna al Monastero","monastero.php");
   if ((($session['user']['maxhitpoints']-3)/$session['user']['level']) > 10){
      $session['user']['maxhitpoints']-=3;
      $session['user']['hitpoints']-=3;
      debuglog(" Perde 3 maxHP nel frutteto");
   }else{
      $session['user']['hitpoints']=1;
   }

}
if ($_GET['op']=="ciliege"){
   output(" `&Il Frutteto del Monastero è cibo per i Monaci che vivono qui. `n");
   output(" `&Ma hai deciso che la tua fame è maggiore di ogni stupida superstizione. `n");
   output(" `2E ti avvicini all'Albero delle Ciliege.`n");
   output(" Prendi e mangi molte delle deliziose ciliege placando la tua fame. `n");
   output(" Mentre ti sdrai sotto il Ciliegio per riposarti, `b`&F U L M I N I _ _ A _ _ C I E L _ _ S E R E N O !`b `n");
   output(" `2Un serpente si avvicina e ti morde!. `n");
   output(" Non ti senti molto bene! `^Per aver ignorato uno `bstupido avvertimento`b, perdi alcuni dei tuoi HP. `n");
   addnav("Altro");
   addnav("Torna al Monastero","monastero.php");
   if ((($session['user']['maxhitpoints']-3)/$session['user']['level']) > 10){
      debuglog(" Perde 3 maxHP nel frutteto");
      $session['user']['maxhitpoints']-=3;
      $session['user']['hitpoints']-=3;
   }else{
      $session['user']['hitpoints']=1;
   }
}


page_footer();
?>