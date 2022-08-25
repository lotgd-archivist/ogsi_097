<?php
/* the picnic basket -
   by robert of maddnet logd
   written for LoGD 097
   uses lonnys hunger and thirst mod
*/
page_header("Il Cestino nella radura");
if (!isset($session)) exit();
if ($_GET['op']==""){
    output("`n`n`2Dopo aver affrontato una lunga ed estenuante serie di combattimenti contro le malvage creature della foresta, stanco ed affamato, ");
    output("`nti imbatti in una piccola radura. Al centro di essa, noti una tovaglia apparecchiata e avvicinandoti, sopra di essa vedi una fiasca e un cesto da picnic. ");
    output("`nTi guardi attorno alla ricerca di anima viva, ma non vedi e non senti nessuno. \"`# Che strano \" `2 pensi tra te e te.  ");
    output("`nE' da parecchio che non fai un pasto decente, la fame e la sete si fanno sentire, lo stomaco protesta con un sordo brontolio. `n`&Cosa vuoi fare?");
    addnav("Il Cesto da Picnic");
    addnav("Apri il Cesto","forest.php?op=open");
    addnav("Lascialo stare","forest.php?op=dont");
    $session['user']['specialinc']="picnic.php";
}else if ($_GET['op']=="open"){
  if ($session['user']['hunger']>=2){
      output("`n`2Senti un certo languorino e decidi di aprire il cestino per esaminarne il contenuto, scoprendo che  ");
      $session['user']['evil'] += 1;
      debuglog("guadagna 1 punto cattiveria aprendo il cestino da picnic.");
      $session['user']['turns']--;
        switch(e_rand(1,8)){
          case 1:
               output(" il cesto è vuoto. `n\"`# Maledizione! \" `2Adesso sei più affamato di quanto non lo eri prima.");
               $session['user']['hunger']++;
               break;
          case 2:
               output("`n c'è un bellissimo pollo allo spiedo che mangi rapidamente, `#mmmmm ... veramente buono!`2");
               $session['user']['hunger']-=2;
               break;
          case 3:
               output("`n c'è soltanto dell'insalata fresca. Vabbè sempre meglio di niente, `#mmmmm ... rinfrescante!`2");
               $session['user']['hunger']--;
               break;
          case 4:
               output("`n ci sono un tozzo di pane, del formaggio e della carne secca affumicata, `#mmmmm ... deliziosi!`2`nAl suo interno trovi anche alcuni pezzi d'oro che fai scivolare nella tua tasca!");
               $findgold = e_rand(100,300)*$session['user']['level'];
               $session['user']['gold'] += $findgold;
               debuglog("guadagna $findgold monete d'oro aprendo il cestino da picnic.");
               $session['user']['hunger']--;
               break;
          case 5:
               output("`n c'è del coniglio arrosto ancora caldo che mangi avidamente, `#mmmmm ... veramente buono!`2");
               output("`nAppena hai finito di divorare il tutto come una belva affamata, `nvedi Violet e Seth venir fuori da un cespuglio . . .`\$Che imbarazzo!!`2");
               $session['user']['hunger']--;
               $session['user']['charm']--;
               debuglog("perde 1 fascino aprendo il cestino da picnic.");
               break;
          case 6:
               output("`n è stracolmo di insalata, cosciotti di maiale e anche dei dolci.. divori tutto, `#mmmmm ... tutto eccezionale!`2");
               output("`nNon riesci a trattenere un potente rutto alla fine del pasto luculliano, il forte rumore provoca inoltre una sorpresa imbarazzante. ");
               output("`n Vedi infatti Violet e Seth spuntare da dietro un cespuglio . . . `\$ops!! Che figuraccia!!!! `2");
               $session['user']['hunger']-=2;
               $session['user']['charm']-=2;
               debuglog("perde 2 punti fascino aprendo il cestino da picnic.");
               break;
          case 7:
               output("`n è pieno di cibarie sulle quali ti getti avidamente spazzolando tutto fino all'ultima briciola. ");
               output("`nQualcosa però doveva essere avariato, perchè ad un tratto vieni afflitto da violenti conati di vomito.. ");
               output("`n perdi un turno nella foresta per riprenderti dalla nausea e rimetterti in forze.");
               $session['user']['hunger']--;
               $session['user']['turns']--;
               break;
           case 8:
               output(" è stracolmo di frutta e verdura, `nmangi tutto con ingordigia, riempiendoti la pancia fino quasi a scoppiare. ");
               output("`nPeccato però che tutta quella verdura ti provoca un effetto indesiderato, per cui sei costretto ad andare di corsa a nasconderti dietro un cespuglio ");
               output("`n per espletare attività non molto edificanti, perdendo così un turno di combattimento nella foresta. ");
               $session['user']['hunger']-=2;
               $session['user']['turns']--;
               break;
          }
          $session['user']['specialinc']="";
addnav("`@Continua", "forest.php");
	}else{
      output("`2Non che il cesto da picnic non sia invitante, ma preferisci sdraiarti e riposare per una ");
      output("mezz'oretta sopra la tovaglia per recuperare le forze. `nDopo una veloce dormita ti senti decisamente meglio. ");
      $session['user']['turns']--;
      $session['user']['hitpoints']++;
      $session['user']['specialinc']="";
addnav("`@Continua", "forest.php");
    }
}else{
  output("`n`n`2Non è nella tua natura metterti a curiosare nei cestini altrui per cui, nonostante la fame, torni nella foresta. ");
  $session['user']['specialinc']="";
addnav("`@Continua", "forest.php");
}

?>