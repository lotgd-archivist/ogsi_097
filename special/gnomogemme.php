<?php
/*******************************************
/ gnomogemme.php - The Gem's Gnome V1.0
/ Originally by Excalibur (www.ogsi.it)
/ 14 October 2004

----install-instructions--------------------
DIFFICULTY SCALE: extremely easy

Forest Event for LotGD 0.9.7
Drop into your "Specials" folder
--------------------------------------------

Version History:
Ver. 1.0 created by Excalibur (www.ogsi.it)
Original Version posted to DragonPrime
********************************************
Originally by: Excalibur
English clean-up:
xx October 2004
*/
require_once "common.php";
//checkday();
if (!isset($session)) exit();
if (getsetting("gnomogemme",0) < 50) savesetting("gnomogemme",50);
page_header("Lo Gnomo delle Gemme");
$basedice = 6;
output("`c<font size='+2'>`%Lo Gnomo delle Gemme</font>`c`n`n",true);
//srand ((double) microtime() * 10000000);
if ($_GET['op']=="") {
   $session['gemwin'] = 0;
   $session['gemtopay'] = 0;
   output("`2Vagando nella foresta incroci uno gnomo che, con fare furbesco ti guarda dall'alto di una roccia.`n");
   output("Allacciata alla cintura tiene una borsa stracolma di preziose gemme.`n");
   output("Alla vista del luccichio i tuoi occhi si illuminano e un velo di bava scorre ai lati della bocca.`n");
   output("Lo gnomo notando la tua cupidigia fa un balzo scendendo dalla roccia e magicamente fa comparire un tavolo ");
   output("che piazza di fronte a te, ed una serie di dadi che però differiscono dai normali dadi a 6 facce e ");
   output("rivolgendoti la parola dice: `n");
   output("\"`#Aye, salve giovine esplorat".($session['user']['sex']?"rice":"ore").", ho notato lo sguardo che avesti ");
   output("alla visione delle mie preziose gemme, e voglio ordunque offrire te possibilità di vincere gemme tantissime.`2\"`n`n");
   addnav("`@Prosegui","forest.php?op=goon");
   addnav("`\$Lascia perdere","forest.php?op=leave");
   $session['user']['specialinc']="gnomogemme.php";
}else if ($_GET['op']=="leave") {
   output("`5Non avendo tempo da perdere, dici allo gnomo che hai smesso di credere alle fate da molto tempo. ");
   output("Ti congedi e ti avvii nuovamente nella foresta, sapendo che il tempo che hai sprecato con lo ");
   output("gnomo ti è costato un combattimento.`n");
   $session['user']['turns'] -= 1;
   $session['user']['specialinc']="";
   addnav("Torna alla Foresta","forest.php");
}else if ($_GET['op']=="goon") {
      output("`2Lo gnometto prosegue: `n\"`#Ach bene, tu essere guerrier".($session['user']['sex']?"a":"o"));
      output(" coraggios".($session['user']['sex']?"a":"o").". Io ammiro te. Il gioco essere molto semplice, io avere una serie ");
      output("di dadi speciali moltissimo. Essi sono con facce da $basedice a ".($basedice+5).". Cominceresti con dado a $basedice facce, poi proseguiresti con ");
      output("dado a ".($basedice+1)." facce nella tornata susseguente e poi ancora con una faccia superiore per arrivare a dado con ".($basedice+5)." facce.`n");
      output("Prima di tirare miei dadi dovrai darmi una bella gemma, scegli numero e se dopo lancio se tuo numero uguale a ");
      output("numero dado tu moltiplichi per tre la posta se invece tuo numero vicino numero esce su dado tu vinci doppio, altrimenti ");
      output("tu perde ed io vince hihihihi.`2\"`n`n");
      output("Lo gnomo fa una pausa e poi riprende a parlare prima che tu possa dire o fare qualcosa: `n\"`#");
      output("Dimenticare io stavo, i primi 2 tiri di dadi miei sono obbligati, tu non puoi ritirare. Dopo tiri succedenti tu può ");
      output("decidere se continua o se vuole fermare. Io no do te gemme subito, io mette gemme a parte e tu in basso a destra ");
      output("può vedere gemme che ho in mia borsa e gemme che tu vince se no continua mio gioco dopo due turnazioni.`n");
      output("Cosa molto importantissima io no posso dare te gemme più di quello che ho in mia borsa anche se tu vince molte più. `n");
      output("Tu se poi perde può ripo ... rirpo ... riprovare e mettere prova tua fortuna altre volte, ma per ogni prova ");
      output("susseguente mio costo aumenta di una gemma. `2\"`n`n");
      output("`^Cosa vuoi fare ?");
      addnav("`@Voglio giocare","forest.php?op=anothergem");
      addnav("`\$Nah, non mi interessa","forest.php?op=leave");
      $session['user']['specialinc']="gnomogemme.php";
}else if ($_GET['op']=="anothergem") {
   $session['gemtopay'] +=1;
   if ($session['user']['gems'] < $session['gemtopay']){
      output("`2\"`#Tu altre gemme non hai, tu no imbroglia me! Io piccolo gnomo ma cervello fino!`2\"`n");
      output("Detto ciò lancia un incantesimo e scompare come un fulmine nella foresta!!`n");
      $loseturn = e_rand(2,4);
      if ($loseturn > $session['user']['turns']) $loseturns = $session['user']['turns'];
      $session['user']['turns'] -= $loseturn;
      output("Ti senti improvvisamente debole e ti rendi conto di aver perso $loseturn turni di combattimento a causa dell'incantesimo.");
       $session['user']['specialinc']="";
      addnav("Torna alla Foresta","forest.php");
   }else{
      $session['user']['gems'] -= $session['gemtopay'];
      debuglog("paga {$session['gemtopay']} gemme per giocare con lo Gnometto");
      $newgem = getsetting("gnomogemme",0) + $session['gemtopay'];
      savesetting("gnomogemme",$newgem);
      output("`2Lo gnomo inizia a fregarsi le mani alla vista delle gemme e dice: `n\"`#Bene ora inizio ha gioco. Ecco ");
      output("dado $basedice facce, tu prima però sceglie numero fortunato`2\".`n");
      $session['user']['specialinc']="gnomogemme.php";
      addnav("Scegli un numero tra 1 e $basedice","forest.php?op=number6");
   }
// First try with 6 faces dice
}else if ($_GET['op']=="number6") {
   output("`2Scrivi nella finestra il numero che pensi uscirà sul dado a $basedice facce`n");
   output("<form action='forest.php?op=number6bis' method='POST'><input name='number' value='0'><input type='submit' class='button' value='Scegli Numero'>`n",true);
   $session['user']['specialinc']="gnomogemme.php";
   addnav("","forest.php?op=number6bis");
}else if ($_GET['op']=="number6bis") {
   $number = intval($_POST['number']);
   if ($number < 1 OR $number > $basedice){
      output("`2Lo gnomo ti guarda storto e dice \"`#Numero tra 1 e $basedice tu scegliere deve. Tu detto `b`%$number`b`# che numero giusto non è!`2\"`n");
       $session['user']['specialinc']="gnomogemme.php";
      addnav("Scegli un numero tra 1 e $basedice","forest.php?op=number6");
   }else{
      output("`2\"`#Scelto tu hai `b`%$number`b`#, ora io lancio dado e noi vediamo se tu vinto hai`2\"`n`n");
      $match = rand(1,$basedice);
      dot($match);
      output("`6Il dado alla fine si ferma e mostra il numero `\$$match `^sulla faccia superiore.`n`n");
      if ($match == $number) {
         $session['gemwin'] += 3;
         output("`\$Arghhh !! `#Vinto tu hai. Io do te {$session['gemwin']} gemme che tu vinto ha, ma tu deve ancora fare ");
         output("una scelta obbligatoriamente, solo dopo ritirare potrai`n");
         $session['user']['specialinc']="gnomogemme.php";
         addnav("Scegli un numero tra 1 e ".($basedice+1),"forest.php?op=number7");
      }else if (($number-1) == $match OR ($number+1) == $match) {
         $session['gemwin'] += 2;
         output("`\$Arghhh !! `#Tuo numero vicino numero di faccia dado. Io do te {$session['gemwin']} gemme che tu vinto ha, ");
         output("ma tu deve ancora fare una scelta obbligatoriamente, solo dopo ritirare potrai`n");
         $session['user']['specialinc']="gnomogemme.php";
         addnav("Scegli un numero tra 1 e ".($basedice+1),"forest.php?op=number7");
      }else {
         output("`#Eh eh eh, perso tu ha! Io da te altra possibilità se tu vuole di giocare ancora, o tu può tornare ");
         output("a foresta se no gioco piace! Tu deve dare me ".($session['gemtopay']+1)." gemme per ritentare fortuna.`n`n");
         output("`2Cosa vuoi fare?`n");
         $session['gemwin'] = 0;
         $session['user']['specialinc']="gnomogemme.php";
         addnav("Fammi riprovare","forest.php?op=anothergem");
         addnav("Sei un imbroglione, me ne vado!","forest.php?op=exit");
      }
   }
//Second try with 7 faces dice
}else if ($_GET['op']=="number7") {
   output("`2Scrivi nella finestra il numero che pensi uscirà sul dado a ".($basedice+1)." facce`n");
   output("<form action='forest.php?op=number7bis' method='POST'><input name='number' value='0'><input type='submit' class='button' value='Scegli Numero'>`n",true);
   $session['user']['specialinc']="gnomogemme.php";
   addnav("","forest.php?op=number7bis");
}else if ($_GET['op']=="number7bis") {
   $number = intval($_POST['number']);
   if ($number < 1 OR $number > ($basedice+1)){
      output("`2Lo gnomo ti guarda storto e dice \"`#Numero tra 1 e ".($basedice+1)." tu scegliere deve. Tu detto `b`%$number`b`# che numero giusto non è!`2\"`n");
       $session['user']['specialinc']="gnomogemme.php";
      addnav("Scegli un numero tra 1 e ".($basedice+1),"forest.php?op=number7");
   }else{
      output("`2\"`#Scelto tu hai `b`%$number`b`#, ora io lancio dado e noi vediamo se tu vinto hai`2\"`n");
      $match = rand(1,($basedice+1));
      dot($match);
      output("`6Il dado alla fine si ferma e mostra il numero `\$$match `^sulla faccia superiore.`n`n");
      if ($match == $number) {
         $session['gemwin'] *= 3;
         output("`\$Arghhh !! `#Vinto tu hai. Io do te {$session['gemwin']} gemme che tu vinto ha, se tu vuole ora può continuare ");
         output("a giocare o se preferisca può ritirare vincita.`n");
         $session['user']['specialinc']="gnomogemme.php";
         addnav("Mi ritiro, ho vinto abbastanza","forest.php?op=exit");
         addnav("Mi sento fortunato, proseguo","forest.php?op=number8");
      }else if (($number-1) == $match OR ($number+1) == $match) {
         $session['gemwin'] *= 2;
         output("`\$Arghhh !! `#Tuo numero vicino numero di faccia dado. Io do te {$session['gemwin']} gemme che tu vinto ha, ");
         output("se tu vuole ora può continuare a giocare o se preferisca può ritirare vincita.`n");
         $session['user']['specialinc']="gnomogemme.php";
         addnav("Mi ritiro, ho vinto abbastanza","forest.php?op=exit");
         addnav("Mi sento fortunato, proseguo","forest.php?op=number8");
      }else {
         output("`#Eh eh eh, perso tu ha! Io da te altra possibilità se tu vuole di giocare ancora, o tu può tornare ");
         output("a foresta se no gioco piace! Tu deve dare me ".($session['gemtopay']+1)." gemme per ritentare fortuna.`n`n");
         output("`2Cosa vuoi fare?`n");
         $session['gemwin'] = 0;
         $session['user']['specialinc']="gnomogemme.php";
         addnav("Fammi riprovare","forest.php?op=anothergem");
         addnav("Sei un imbroglione, me ne vado!","forest.php?op=exit");
      }
   }
//Third try with 8 faces dice
}else if ($_GET['op']=="number8") {
   output("`2Scrivi nella finestra il numero che pensi uscirà sul dado a ".($basedice+2)." facce`n");
   output("<form action='forest.php?op=number8bis' method='POST'><input name='number' value='0'><input type='submit' class='button' value='Scegli Numero'>`n",true);
   $session['user']['specialinc']="gnomogemme.php";
   addnav("","forest.php?op=number8bis");
}else if ($_GET['op']=="number8bis") {
   $number = intval($_POST['number']);
   if ($number < 1 OR $number > ($basedice+2)){
      output("`2Lo gnomo ti guarda storto e dice \"`#Numero tra 1 e ".($basedice+2)." tu scegliere deve. Tu detto `b`%$number`b`# che numero giusto non è!`2\"`n");
       $session['user']['specialinc']="gnomogemme.php";
      addnav("Scegli un numero tra 1 e ".($basedice+2),"forest.php?op=number8");
   }else{
      output("`2\"`#Scelto tu hai `b`%$number`b`#, ora io lancio dado e noi vediamo se tu vinto hai`2\"`n");
      $match = rand(1,($basedice+2));
      dot($match);
      output("`6Il dado alla fine si ferma e mostra il numero `\$$match `^sulla faccia superiore.`n`n");
      if ($match == $number) {
         $session['gemwin'] *= 3;
         output("`\$Arghhh !! `#Vinto tu hai. Io do te {$session['gemwin']} gemme che tu vinto ha, se tu vuole ora può continuare ");
         output("a giocare o se preferisca può ritirare vincita.`n");
         $session['user']['specialinc']="gnomogemme.php";
         addnav("Mi ritiro, ho vinto abbastanza","forest.php?op=exit");
         addnav("Mi sento fortunato, proseguo","forest.php?op=number9");
      }else if (($number-1) == $match OR ($number+1) == $match) {
         $session['gemwin'] *= 2;
         output("`\$Arghhh !! `#Tuo numero vicino numero di faccia dado. Io do te {$session['gemwin']} gemme che tu vinto ha, ");
         output("se tu vuole ora può continuare a giocare o se preferisca può ritirare vincita.`n");
         $session['user']['specialinc']="gnomogemme.php";
         addnav("Mi ritiro, ho vinto abbastanza","forest.php?op=exit");
         addnav("Mi sento fortunato, proseguo","forest.php?op=number9");
      }else {
         output("`#Eh eh eh, perso tu ha! Io da te altra possibilità se tu vuole di giocare ancora, o tu può tornare ");
         output("a foresta se no gioco piace! Tu deve dare me ".($session['gemtopay']+1)." gemme per ritentare fortuna.`n`n");
         output("`2Cosa vuoi fare?`n");
         $session['gemwin'] = 0;
         $session['user']['specialinc']="gnomogemme.php";
         addnav("Fammi riprovare","forest.php?op=anothergem");
         addnav("Sei un imbroglione, me ne vado!","forest.php?op=exit");
      }
   }
//Forth try with 9 faces dice
}else if ($_GET['op']=="number9") {
   output("`2Scrivi nella finestra il numero che pensi uscirà sul dado a ".($basedice+3)." facce`n");
   output("<form action='forest.php?op=number9bis' method='POST'><input name='number' value='0'><input type='submit' class='button' value='Scegli Numero'>`n",true);
   $session['user']['specialinc']="gnomogemme.php";
   addnav("","forest.php?op=number9bis");
}else if ($_GET['op']=="number9bis") {
   $number = intval($_POST['number']);
   if ($number < 1 OR $number > ($basedice+3)){
      output("`2Lo gnomo ti guarda storto e dice \"`#Numero tra 1 e ".($basedice+3)." tu scegliere deve. Tu detto `b`%$number`b`# che numero giusto non è!`2\"`n");
       $session['user']['specialinc']="gnomogemme.php";
      addnav("Scegli un numero tra 1 e ".($basedice+3),"forest.php?op=number9");
   }else{
      output("`2\"`#Scelto tu hai `b`%$number`b`#, ora io lancio dado e noi vediamo se tu vinto hai`2\"`n");
      $match = rand(1,($basedice+3));
      dot($match);
      output("`6Il dado alla fine si ferma e mostra il numero `\$$match `^sulla faccia superiore.`n`n");
      if ($match == $number) {
         $session['gemwin'] *= 3;
         output("`\$Arghhh !! `#Vinto tu hai. Io do te {$session['gemwin']} gemme che tu vinto ha, se tu vuole ora può continuare ");
         output("a giocare o se preferisca può ritirare vincita.`n");
         $session['user']['specialinc']="gnomogemme.php";
         addnav("Mi ritiro, ho vinto abbastanza","forest.php?op=exit");
         addnav("Mi sento fortunato, proseguo","forest.php?op=number10");
      }else if (($number-1) == $match OR ($number+1) == $match) {
         $session['gemwin'] *= 2;
         output("`\$Arghhh !! `#Tuo numero vicino numero di faccia dado. Io do te {$session['gemwin']} gemme che tu vinto ha, ");
         output("se tu vuole ora può continuare a giocare o se preferisca può ritirare vincita.`n");
         $session['user']['specialinc']="gnomogemme.php";
         addnav("Mi ritiro, ho vinto abbastanza","forest.php?op=exit");
         addnav("Mi sento fortunato, proseguo","forest.php?op=number10");
      }else {
         output("`#Eh eh eh, perso tu ha! Io da te altra possibilità se tu vuole di giocare ancora, o tu può tornare ");
         output("a foresta se no gioco piace! Tu deve dare me ".($session['gemtopay']+1)." gemme per ritentare fortuna.`n`n");
         output("`2Cosa vuoi fare?`n");
         $session['gemwin'] = 0;
         $session['user']['specialinc']="gnomogemme.php";
         addnav("Fammi riprovare","forest.php?op=anothergem");
         addnav("Sei un imbroglione, me ne vado!","forest.php?op=exit");
      }
   }
//Fifth try with 10 faces dice
}else if ($_GET['op']=="number10") {
   output("`2Scrivi nella finestra il numero che pensi uscirà sul dado a ".($basedice+4)." facce`n");
   output("<form action='forest.php?op=number10bis' method='POST'><input name='number' value='0'><input type='submit' class='button' value='Scegli Numero'>`n",true);
   $session['user']['specialinc']="gnomogemme.php";
   addnav("","forest.php?op=number10bis");
}else if ($_GET['op']=="number10bis") {
   $number = intval($_POST['number']);
   if ($number < 1 OR $number > ($basedice+4)){
      output("`2Lo gnomo ti guarda storto e dice \"`#Numero tra 1 e ".($basedice+4)." tu scegliere deve. Tu detto `b`%$number`b`# che numero giusto non è!`2\"`n");
       $session['user']['specialinc']="gnomogemme.php";
      addnav("Scegli un numero tra 1 e ".($basedice+4),"forest.php?op=number10");
   }else{
      output("`2\"`#Scelto tu hai `b`%$number`b`#, ora io lancio dado e noi vediamo se tu vinto hai`2\"`n");
      $match = rand(1,($basedice+4));
      dot($match);
      output("`6Il dado alla fine si ferma e mostra il numero `\$$match `^sulla faccia superiore.`n`n");
      if ($match == $number) {
         $session['gemwin'] *= 3;
         output("`\$Arghhh !! `#Vinto tu hai. Io do te {$session['gemwin']} gemme che tu vinto ha, se tu vuole ora può continuare ");
         output("a giocare o se preferisca può ritirare vincita.`n");
         $session['user']['specialinc']="gnomogemme.php";
         addnav("Mi ritiro, ho vinto abbastanza","forest.php?op=exit");
         addnav("Mi sento fortunato, proseguo","forest.php?op=number11");
      }else if (($number-1) == $match OR ($number+1) == $match) {
         $session['gemwin'] *= 2;
         output("`\$Arghhh !! `#Tuo numero vicino numero di faccia dado. Io do te {$session['gemwin']} gemme che tu vinto ha, ");
         output("se tu vuole ora può continuare a giocare o se preferisca può ritirare vincita.`n");
         $session['user']['specialinc']="gnomogemme.php";
         addnav("Mi ritiro, ho vinto abbastanza","forest.php?op=exit");
         addnav("Mi sento fortunato, proseguo","forest.php?op=number11");
      }else {
         output("`#Eh eh eh, perso tu ha! Io da te altra possibilità se tu vuole di giocare ancora, o tu può tornare ");
         output("a foresta se no gioco piace! Tu deve dare me ".($session['gemtopay']+1)." gemme per ritentare fortuna.`n`n");
         output("`2Cosa vuoi fare?`n");
         $session['gemwin'] = 0;
         $session['user']['specialinc']="gnomogemme.php";
         addnav("Fammi riprovare","forest.php?op=anothergem");
         addnav("Sei un imbroglione, me ne vado!","forest.php?op=exit");
      }
   }
// Sixth try with 11 faces dice
}else if ($_GET['op']=="number11") {
   output("`2Scrivi nella finestra il numero che pensi uscirà sul dado a ".($basedice+5)." facce`n");
   output("<form action='forest.php?op=number11bis' method='POST'><input name='number' value='0'><input type='submit' class='button' value='Scegli Numero'>`n",true);
   $session['user']['specialinc']="gnomogemme.php";
   addnav("","forest.php?op=number11bis");
}else if ($_GET['op']=="number11bis") {
   $number = intval($_POST['number']);
   if ($number < 1 OR $number > ($basedice+5)){
      output("`2Lo gnomo ti guarda storto e dice \"`#Numero tra 1 e ".($basedice+5)." tu scegliere deve. Tu detto `b`%$number`b`# che numero giusto non è!`2\"`n");
       $session['user']['specialinc']="gnomogemme.php";
      addnav("Scegli un numero tra 1 e ".($basedice+5),"forest.php?op=number11");
   }else{
      output("`2\"`#Scelto tu hai `b`%$number`b`#, ora io lancio dado e noi vediamo se tu vinto hai`2\"`n");
      $match = rand(1,($basedice+5));
      dot($match);
      output("`6Il dado alla fine si ferma e mostra il numero `\$$match `^sulla faccia superiore.`n`n");
      if ($match == $number) {
         $session['gemwin'] *= 3;
         output("`\$Arghhh !! `#Vinto tu hai. Io do te {$session['gemwin']} gemme che tu vinto ha, tu me sbancato io penso finito ");
         output("gemme da te dare. Io triste molto io penso che forse non riesce dare te tutta vincita.`n");
         $session['user']['specialinc']="gnomogemme.php";
         addnav("Ritira Vincita","forest.php?op=exit");
      }else if (($number-1) == $match OR ($number+1) == $match) {
         $session['gemwin'] *= 2;
         output("`\$Arghhh !! `#Tuo numero vicino numero di faccia dado. Io do te {$session['gemwin']} gemme che tu vinto ha, ");
         output("tu me sbancato io penso finito gemme. Io triste molto che forse non riesce dare te tutta vincita`n");
         $session['user']['specialinc']="gnomogemme.php";
         addnav("Ritira Vincita","forest.php?op=exit");
      }else {
         output("`#Eh eh eh, perso tu ha! Io da te altra possibilità se tu vuole di giocare ancora, o tu può tornare ");
         output("a foresta se no gioco piace! Tu deve dare me ".($session['gemtopay']+1)." gemme per ritentare fortuna.`n`n");
         output("`2Cosa vuoi fare?`n");
         $session['gemwin'] = 0;
         $session['user']['specialinc']="gnomogemme.php";
         addnav("Fammi riprovare","forest.php?op=anothergem");
         addnav("Sei un imbroglione, me ne vado!","forest.php?op=exit");
      }
   }
}else if ($_GET['op']=="exit") {
   if ($session['gemwin'] == 0) {
      output("`%Scornato per la sconfitta subita, ti giri in direzione della foresta e ti allontani dallo gnomo, che ");
      output("se la ride di gusto rimirando le gemme che ti ha sottratto!`n");
   } else {
      output("`@Felice per la vincita rimiri il mucchietto di gemme che hai sottratto allo gnometto, e mentri ti allontani ");
      output("nella foresta, percorri molta strada prima di non udire più i lamenti di dolore dello gnomo.`n`n");
      if ($session['gemwin'] > getsetting("gnomogemme",0)) {
         $session['gemwin'] = getsetting("gnomogemme",0);
      }
      $session['user']['gems'] += $session['gemwin'];
      debuglog("vince {$session['gemwin']} dallo Gnometto");
      $newgem = getsetting("gnomogemme",0) - $session['gemwin'];
      savesetting("gnomogemme",$newgem);
   }
   $session['user']['specialinc']="";
   addnav("F?Torna alla Foresta","forest.php");
}

if ($_GET['op']!="") {
   output("<big><div align=right>`n`n`b`^Gemme dello Gnomo`b:`#".getsetting("gnomogemme",0)."`n</big></div>",true);
   output("<big><div align=right>`b`^Montepremi`b:`#{$session['gemwin']}`n</big></div>",true);
}else {
   output("<big><div align=right>`n`n`b`^Gemme dello Gnomo`b:`#".getsetting("gnomogemme",0)."`n</big></div>",true);
}
function dot($match)
   {
    for ($i = 0; $i < $match;$i++) {
        output("`\$...........`%...........`!...........`#...........`@...........`^...........`n`n");
   }
   }
//I cannot make you keep this line here but would appreciate it left in.
rawoutput("<br><div style=\"text-align: right ;\"><a href=\"http://www.ogsi.it\" target=\"_blank\"><font color=\"#33FF33\">Gem's Gnome by Excalibur @ http://www.ogsi.it</font></a><br>");
page_footer();
?>