<?php
if (!isset($session)) exit();
if ($_GET['op']==""){
  output("`3Un vecchietto ti ferma mentre vaghi tra gli alberi.  \"`!Ti andrebbe un piccolo gioco ");
    output("di indovinelli?`3\" ti chiede. Conoscendo il tipo, sai che insisterà per una piccola posta se acceti. ");
    output("`n`nVuoi giocare con lui?`n`n<a href='forest.php?op=yes'>Sì</a>`n<a href='forest.php?op=no'>No</a>",true);
    addnav("Sì","forest.php?op=yes");
    addnav("No","forest.php?op=no");
    addnav("","forest.php?op=yes");
    addnav("","forest.php?op=no");
    $session['user']['specialinc']="oldmanbet.php";
}else if($_GET['op']=="yes"){
  if ($session['user']['gold']>0){
        $session['user']['specialinc']="oldmanbet.php";
        $bet = abs((int)$_GET['bet'] + (int)$_POST['bet']);
        if ($bet<=0){
            output("`3\"`!Hai 6 possibilità di indovinare il numero a cui sto pensando, da 1 a 100. Ogni volta ti dirò se è più alto o più basso.`3\"`n`n");
            output("`3\"`!Quanto vuoi scommettere ".($session['user']['sex']?"bella ragazza":"giovanotto")."?`3\"");
            output("<form action='forest.php?op=yes' method='POST'><input name='bet' id='bet'><input type='submit' class='button' value='Punta'></form>",true);
            output("<script language='JavaScript'>document.getElementById('bet').focus();</script>",true);
            addnav("","forest.php?op=yes");
            $session['user']['specialmisc']=e_rand(1,100);
        }else if($bet>$session['user']['gold']){
          output("`3L'uomo allunga il bastone e tocca il tuo borsellino.  \"`!Non credo che tu abbia `^$bet`! monete!`3\" dichiara.`n`n");
            output("Tentando disperatamente di dimostrargli la tua buona fede, apri il borsellino e lo svuoti: `^".$session['user']['gold']."`3 monete.");
            output("`n`nImbarazzato, pensi di fare ritorno alla foresta.");
            $session['user']['specialinc']="";
            addnav("Torna alla foresta","forest.php");
        }else{
            if ($_POST['guess']!==NULL){
              $try = (int)$_GET['try'];
              if ($_POST['guess']==$session['user']['specialmisc']){
                  output("`3\"`!AAAH!!!!`3\" urla l´uomo, \"`!Hai indovinato il numero con soli $try tentativi! Era `^".$session['user']['specialmisc']."`!!!  Beh, congratulazioni, ");
                    output("penso che me ne andrò adesso... `3\" dice dirigendosi verso il sottobosco. Un rapido colpo del tuo ".$session[user][weapon]);
                    output(" gli fa perdere i sensi. Afferri il suo borsellino, prendendoti le `^$bet`3 monete che ti deve.");
                    $session['user']['gold']+=$bet;
                    $session['user']['specialinc']="";
                    addnav("Torna alla foresta","forest.php");
                }else{
                  if ($_GET['try']>=6){
                      output("`3L´uomo ridacchia.  \"`!Il numero era `^".$session['user']['specialmisc']."`!,`3\" dice. Dal cittadino onorevole che sei, ");
                        output("dai all´uomo le `^$bet`3 monete che gli devi, pronto ad andartene.");
                        $session['user']['specialinc']="";
                        $session['user']['gold']-=$bet;
                        addnav("Torna alla foresta","forest.php");
                    }else{
                      if ((int)$_POST['guess']>$session['user']['specialmisc']){
                          output("`3\"`!No, no, no, non è `^".(int)$_POST['guess']."`!, è più basso! Era il tentativo numero `^$try`!.`3\"`n`n");
                        }else{
                          output("`3\"`!No, no, no, non è `^".(int)$_POST['guess']."`!, è più alto! Era il tentativo numero `^$try`!.`3\"`n`n");
                        }
                        output("`3Hai scommesso `^$bet`3 monete. Che numero pensi che sia?");
                        output("<form action='forest.php?op=yes&bet=$bet&try=".(++$try)."' method='POST'><input name='guess'><input type='submit' value='Indovina'></form>",true);
                        addnav("","forest.php?op=yes&bet=$bet&try=$try");
                    }
                }
            }else{
                output("`3Hai scommesso `^$bet`3 monete. Che numero pensi che sia?");
                output("<form action='forest.php?op=yes&bet=$bet&try=1' method='POST'><input name='guess'><input type='submit' value='Indovina'></form>",true);
                addnav("","forest.php?op=yes&bet=$bet&try=1");
            }
        }
    }else{
      output("`3L´uomo allunga il bastone e tocca il tuo borsellino.  \"`!Vuoto?!?!  Come puoi scommettere senza soldi??`3\" strilla.");
        output("  Detto questo, si volta con un HARUMPH, e scompare tra i cespugli.");
        addnav("Torna alla foresta","forest.php");
        $session['user']['specialinc']="";
    }
}else if($_GET['op']=="no"){
  output("`3Preoccupato di doverti separare dai tuoi preziosi preziosi soldi, declini l´offerta del vecchio Non era il caso ");
    output("comunque, perché avresti vinto di sicuro. Sicuro, non eri assolutamente spaventato dal vecchio, no proprio.");
    addnav("Torna alla foresta","forest.php");
    $session['user']['specialinc']="";
}
?>