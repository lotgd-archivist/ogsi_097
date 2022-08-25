<?php
require_once "common.php";
checkday();

/*
Slaves Shop
Version 1.0 (03/05/05)
by Andreas Miedler
*/
/*
////// But this in to your SQL////////////////////////////////////////
  ALTER TABLE `accounts` ADD `slaves` INT UNSIGNED DEFAULT '0' NOT NULL ;
  ALTER TABLE `accounts` ADD `land` INT UNSIGNED DEFAULT '0' NOT NULL ;
  ALTER TABLE `accounts` ADD `farms` INT UNSIGNED DEFAULT '0' NOT NULL ;
  ALTER TABLE `accounts` ADD `manager` INT UNSIGNED DEFAULT '0' NOT NULL ;
//////////////////////////////////////////////////////////////////////

/// In `newday.php` add//////

---Find:---

output("`2Interest Accrued on Debt: `^".-(int)($session['user']['goldinbank']*($interestrate-1))."`2 gold.`n");
            }
        }

---under it put:---

if ($session['user']['slaves']>=1){
   $rand = e_rand(1,4);
   switch ($rand){
        case 1:
        $slavemoney = $session['user']['slaves']*16;
        $session['user'][goldinbank]+=$slavemoney;
        output("`2I tuoi Schiavi hanno lavorato in maniera costante producendo il 75% delle capacità delle tue Fattorie.`n");
        break;
        case 2:
        $slavemoney = $session['user']['slaves']*18;
        $session['user'][goldinbank]+=$slavemoney;
        output("`2I tuoi Schiavi hanno lavorato in maniera costante producendo il 80% delle capacità delle tue Fattorie.`n");
        break;
        case 3:
        $slavemoney = $session['user']['slaves']*19;
        $session['user'][goldinbank]+=$slavemoney;
        output("`2I tuoi Schiavi hanno lavorato in maniera costante producendo il 95% delle capacità delle tue Fattorie.`n");
        break;
        case 4:
        $slavemoney = $session['user']['slaves']*22;
        $session['user'][goldinbank]+=$slavemoney;
        if ($session['user']['slaves'] >= 10){
           $schiavi = 10;
           $schiavigemme = 5;
           $session['user']['slaves']-=10;
           $session['user']['gems']+=5;
        }else{
           $schiavi = $session['user']['slaves'];
           $schiavigemme = (int)($session['user']['slaves']/2);
           $session['user']['slaves'] = 0;
           $session['user']['gems'] += $schiavigemme;
        }
        break;
   }
   output("`\$$schiavi dei tuoi schiavi hanno acquistato la loro libertà per $schiavigemme gemme!!!`n");
   output("`2I tuoi Schiavi hanno lavorato in maniera costante producendo il 100% delle capacità delle tue Fattorie.`n");
   break;
   output("Hai guadagnato `^".$slavemoney."`n");
}
/////////////////////////////////////////////////////////////////////////////////////////
---Note---
    Put a link in to the village for Farm Managment and change the return links. I use a castle on my server so all my return
    links named "castle.php" need to be replaced!
*/

page_header("Gestione Fattorie");
$session['user']['locazione'] = 174;
if ($session['user']['euro'] > 0) {
if ($_GET['op']==""){
   if ($session['user']['manager']<=0){
      output("`2Entri in un ampio territorio che costeggia le Tenute Reali e si estende a vista d'occhio in ");
      output("direzione delle lontane zone montuose.  Qui già numerose Fattorie stanno producendo generi alimentari ");
      output("di ogni tipo, e l'attività è frenetica. Vedi moltissimi operai che si muovono come brulicanti formiche ");
      output("e quello che sembra essere il loro capo che con la frusta in mano li incita a lavorare ancora di più.`n`n");
      output("Stai valutando se intraprendere l'attività di agricoltore, e continui ad osservare il gran movimento che si ");
      output("svolge davanti al tuo sguardo attento.  Sai che nel caso tu volessi diventare proprietario terriero dovrai ");
      output(" assumere un Direttore per le tue necessità di gestione agricole.`n`n`@Cosa vuoi fare?");
      addnav("Assumi Direttore","slaves.php?op=managerbuy");
      addnav("Torna alle Tenute Reali","houses.php");
   }else{
      output("`^ `c Resoconto del Manager Apophis sulla Fattoria. `c `n `n");
      if ($session['user']['land']<=0){
         output("`$ Dovresti comperare della Terra così potrai costruire una fattoria.`n");
      }else{
         output("`3 Al momento possiedi `^ ".$session['user']['land']." `3 acri di terra.`n");
      }
      if ($session['user']['farms']<=0){
         output("`$ Dovresti acquistare una Fattoria così potresti comprare degli Schiavi.`n");
      }else{
         output("`3 Ci sono `^ ".$session['user']['farms']." `3 fattorie sulle tue terre.`n");
      }
      if ($session['user']['slaves']<=0){
         output("`$ Dovresti comprare degli Schiavi da far lavorare nelle fattorie per fare un po' di soldi.`n`n`n`n`n");
      }else{
         output("`3E hai al momento `^".$session['user']['slaves']." `3schiavi che stanno lavorando per te.`n");
      }
      $farmneed1 = $session['user']['land'] / 100;
      $farmneed2 = $farmneed1 - $session['user']['farms'];
      $farmneed2 = abs((int)$farmneed2);
      output("`3 Al momento sulle tue terre puoi costruire `^".$farmneed2."`3 Fattorie.`n");
      $slaveneed1 = $session['user']['farms'] * 100;
      $slaveneed2 = $slaveneed1 - $session['user']['slaves'];
      output("`3 Sembra che tu abbia bisogno di `^".$slaveneed2."`3 Schiavi da far lavorare nelle tue`^ ".$session['user']['farms']."`3 fattorie.`n");
      addnav("T?Acquista Terra","slaves.php?op=buyland");
      addnav("F?Acquista Fattoria","slaves.php?op=buyfarm");
      addnav("S?Acquista Schiavi","slaves.php?op=buyslaves");
      addnav("R?Torna alle Tenute Reali","houses.php");
   }
}elseif ($_GET['op']=="managerbuy"){
   output("`2Un uomo dall'aspetto egizio con dei lucenti abiti argentei si avvicina. Noti una luce misteriosa nei suoi occhi e ");
   output("la sua voce è profonda e melodiosa.`n");
   output("`2\"`&Salve, il mio nome è Apophis e le posso offrire i miei servigi in qualità di Manager per le sue Fattorie.`n");
   output("La aiuterò nella conduzione delle attività della fattoria al massimo delle sue capacità.`n");
   output("Mi faccia sapere se è interessato ai miei servigi.`nSe mi pagherà 100.000 pezzi d'oro come onorario in un'unica soluzione, ");
   output("entrerò immediatamente in servizio!`2\"");
   addnav("Pagalo","slaves.php?op=managerget");
   addnav("Torna alle Fattorie","slaves.php");
}else if ($_GET['op']=="managerget"){
   if ($session['user']['gold'] <= 99999){
      output("`2Apophis ti guarda con sguardo condiscendente poi dice:`n`n\"`&Sono spiacente, ma prima dovrebbe procurarsi ");
      output("i pezzi d'oro necessari al pagamento del mio onorario!`2\"`n");
      addnav("Torna alle Tenute Reali","houses.php");
   }else{
      addnav("Torna alle Tenute Reali","houses.php");
      output("`2Apophis si inchina di fronte a te, e senti un insolito potere dentro di te mentre le parole '`@Jaffa Cree!`2' ");
      output("compaiono nella tua mente. Hmmm? Strano!`n`n");
      $session['user']['manager']+=1;
      $session['user']['gold']-=100000;
      debuglog("spende 100000 pezzi d'oro per acquistare il Manager delle Fattorie.");
   }
}else if ($_GET['op']=="buyland"){
//////////////// buy land///////////////
   $landprice = $session['user']['land']*1.1+100;
   $landalowed = $session['user']['gold']/$landprice;
   $n=min($session['user']['reincarna'], 12);
      $landreinc = $acrireinc[$n]-$session['user']['land'];
      if ($landreinc < 0) $landreinc = 0;
   if (moduli('limiteacri')=='on') {
       $landcommon = getsetting("acri",0);
   } else {
       $landcommon = 1;
   }
   output("`\$`c`bVicini (Confinanti)`c`b`n`n");
   if (getsetting("acri",0) == 0) { 
       output("`\$Rafflingate non dispone di acri di terra da mettere in vendita!!!`^`nDialoga con gli altri giocatori, e cercate insieme una soluzione per procurare nuovi acri al paese che vi ospita!");
   } else {
       if (moduli('limiteacri')=='on') output("`2Attualmente `&".getsetting("acri",0)."`2 acri di terra si trovano sotto il controllo di Rafflingate e possono essere acquistati.`n`n");
       output("`2Parli con i proprietari delle terre confinanti per valutare l'acquisto delle loro terre.");
       output("`n\"`#Ti venderò tutta la terra che ti serve, amico mio, ");
       output("ma ricorda che i miei prezzi aumentano col passare del tempo!`2\"`n");
       output("`2Attualmente il prezzo per 1 acro di terra è di `^".$landprice." `^pezzi d'oro`2.`n");
       output("`2Questo perchè al momento possiedi `^".$session['user']['land']."`2 acri di terra.`n`n");
       output("`2Apophis ti porge un opuscolo, che illustra il numero massimo di acri acquistabile per ogni reincarnazione:`n`n");
       output("<table cellspacing=0 cellpadding=2 align='center'><tr><td>`bReincarnazioni`b</td><td> </td><td>`bMassimo Acri Possedibili`b</td></tr>", true);
       output("<tr class='trlight'><td align='center'>`&0`0</td></td><td><td align='center'>`)0`0</td></tr>", true);
       output("<tr class='trdark'><td align='center'>`&1`0</td></td><td><td align='center'>`)1500`0</td></tr>", true);
       output("<tr class='trlight'><td align='center'>`&2`0</td></td><td><td align='center'>`)2500`0</td></tr>", true);
       output("<tr class='trdark'><td align='center'>`&3`0</td></td><td><td align='center'>`)3500`0</td></tr>", true);
       output("<tr class='trlight'><td align='center'>`&4`0</td></td><td><td align='center'>`)4500`0</td></tr>", true);
       output("<tr class='trdark'><td align='center'>`&5`0</td></td><td><td align='center'>`)5500`0</td></tr>", true);
       output("<tr class='trlight'><td align='center'>`&6`0</td></td><td><td align='center'>`)6500`0</td></tr>", true);
       output("<tr class='trdark'><td align='center'>`&7`0</td></td><td><td align='center'>`)7000`0</td></tr>", true);
       output("<tr class='trlight'><td align='center'>`&8`0</td></td><td><td align='center'>`)7500`0</td></tr>", true);
       output("<tr class='trdark'><td align='center'>`&9`0</td></td><td><td align='center'>`)8000`0</td></tr>", true);
       output("<tr class='trlight'><td align='center'>`&10`0</td></td><td><td align='center'>`)8500`0</td></tr>", true);
       output("<tr class='trdark'><td align='center'>`&11`0</td></td><td><td align='center'>`)9000`0</td></tr>", true);
       output("<tr class='trlight'><td align='center'>`&12`0</td></td><td><td align='center'>`)9200`0</td></tr>", true);
       output("<tr class='trdark'><td align='center'>`&`i+1 reinc.`i`0</td></td><td><td align='center'>`)`i+200`i`0</td></tr></table>`n`n", true);
       if ($landreinc > $landalowed && $landreinc <= $landcommon) {
           output("`2Con l'oro che hai con te, puoi acquistare `^".(int)($landalowed)." `2acri di terra.`n`n");
       } elseif ($landalowed<= $landcommon) {
           output("`2Avendo `^".$session['user']['reincarna']." `2reincarnazioni, puoi acquistare `^".$landreinc." `2acri di terra.`n`n");
       }
       output("<form action='slaves.php?op=land10' method='POST'>",true);
       output("`2Quanta terra vuoi acquistare: <input name='amount' id='amount' width='5'>`n`n",true);
       output("<input type='submit' class='button' value='Finalizza Contratto'></form>",true);
       addnav("","slaves.php?op=land10");
   }
   addnav("Torna alle Fattorie","slaves.php");
   addnav("Acquista con i Punti Donazione (WIP)");
}else if ($_GET['op']=="land10"){
   $amt = abs((int)$_POST['amount']);
   $landprice = abs((int)$session['user']['land']*1.1+100);
   $landalowed = $session['user']['gold']/$landprice;
   $landalowed = abs((int)$landalowed);
   $n=min($session['user']['reincarna'], 12);
   $landreinc = $acrireinc[$n]-$session['user']['land'];
      if ($landreinc < 0) $landreinc = 0;
   if(moduli('limiteacri')=='on') {
       $landcommon = getsetting("acri",0);
   } else {
       $landcommon = -1;
   }
   $endprice = $landprice * $amt;
   if ($landcommon < $amt && $landcommon < $landreinc && $landcommon < $landalowed && $landcommon != -1) {
       $caso=1;
   } elseif ($landreinc < $amt && $landreinc < $landalowed) {
       $caso=2;
   }

   if ($caso==1){
      output("`%La città di Rafflingate non controlla abbastanza terra per completare questa transazione. Puoi acquistare solamente `^".$landcommon." `%acri di terra. E non, come hai chiesto tu, `^".$amt." `%acri.`n");
      addnav("Torna alle Fattorie","slaves.php");
   }elseif ($caso==2){
      output("`%Non ti sei ancora reincarnato abbastanza volte per effettuare questa transazione. Puoi acquistare solamente `^".$landreinc." `%acri di terra. E non, come hai chiesto tu, `^".$amt." `%acri.`n");
      addnav("Torna alle Fattorie","slaves.php");
   }elseif ($landalowed < $amt){
      output("`%Non hai oro a sufficienza per questa transazione. Puoi acquistare solamente `^".$landalowed." `%acri di terra. E non, come hai chiesto tu, `^".$amt." `%acri.`n");
      addnav("Torna alle Fattorie","slaves.php");
   }else{
      $session['user']['gold'] -= $endprice;
      $session['user']['land'] += $amt;
      output("`%Hai acquistato `^".$amt."`% acri di terra ed ora sei il proprietario di `^".$session['user']['land']." `%acri di terra.");
      debuglog("spende ".$endprice." pezzi d'oro per acquistare ".$amt." acri di terra nelle Fattorie.");
      if(moduli('limiteacri')=='on') {
          $landcommon -= $amt;
          savesetting("acri","$landcommon");
      }
      addnav("Torna alle Fattorie","slaves.php");
   }
}else if ($_GET['op']=="buyfarm"){
////////////////buy farm/////////////
   $farmprice = $session['user']['farms']+5;
   $fact1 = $session['user']['land']/100;
   $farmsalowed = $fact1 - $session['user']['farms'];
   $farmsalowed = abs((int)$farmsalowed);
   $fact2 = $session['user']['farms']*100;
   $unusedland = $session['user']['land'] - $fact2;
   $totalprice = $farmsalowed * $farmprice;
   output("`$`c`bArchitetti`c`b`n`n");
   output("`@Ritrovi Mastro Roark e ti metti a discutere con lui al riguardo della costruzione di alcune Fattorie:`n`n`n");
   output("\"`&In qualità di vostro architetto devo informarla che abbisogna di `^100 `&acri di terreno per ogni fattoria.`n");
   output("Al momento lei possiede `^".$unusedland." `&acri di terreno libero ed utilizzabile. Ciò significa che può costruire `^".$farmsalowed." `&Fattorie.`n");
   output("`^1 `&Fattoria le costerà`^ ".$farmprice." `&gemme in questo momento.`n`n");
   output("<form action='slaves.php?op=farm10' method='POST'>",true);
   output("`2Quante Fattorie vuoi costruire: <input name='amount' id='amount' width='5'>`n`n",true);
   output("<input type='submit' class='button' value='Finalizza Contratto'></form>",true);
   addnav("","slaves.php?op=farm10");
   addnav("Torna alle Fattorie","slaves.php");
}else if ($_GET['op']=="farm10"){
   $amt = abs((int)$_POST['amount']);
   $farmprice = $session['user']['farms']+5;
   $fact1 = $session['user']['land']/100;
   $farmsalowed = $fact1 - $session['user']['farms'];
   $farmsalowed = abs((int)$farmsalowed);
   $fact2 = $session['user']['farms']*100;
   $unusedland = $session['user']['land'] - $fact2;
   $totalprice = $amt * $farmprice;
   $case = 0;
   if ($farmsalowed >= $amt){
      $case+=1;
   }else{
      $case+=3;
   }
   if ($totalprice <= $session['user']['gems']){
      $case+=1;
   }else{
      $case+=3;
   }
   if ($case==2){
      $session['user']['gems'] -= $totalprice;
      $session['user']['farms'] += $amt;
      output("`%Hai costruito `^".$amt." `%fattorie e sei ora il proprietario di `^".$session['user']['farms']." `%fattorie in totale.");
      debuglog("spende ".$totalprice." gemme per acquistare ".$amt." Fattorie.");
      addnav("Torna alle Fattorie","slaves.php");
   }else{
      output("`&Mastro Roark è decisamente arrabbiato con te:`n");
      output("`\$Ma che diavolo!!! Non capisci l'Italiano? Hai bigiato le lezioni di matematica o cosa. Ti ho spiegato perfettamente ");
      output(" tutta la storia. Se non hai terreno libero a sufficienza o gemme per acquistare una fattoria, allora non scocciarmi!!!`n`n");
      output("`&Il nano getta il progetto di costruzione per terra, e mentre si allontana come un tornado lo calpesta con rabbia.`n`n");
      addnav("Torna alle Fattorie","slaves.php");
   }
}else if ($_GET['op']=="buyslaves"){
///////////////////////Slaves//////////////////////
   $slaveprice = 1000;
   $fact1 = $session['user']['farms']*100;
   $slavesalowed = $fact1 - $session['user']['slaves'];
   $slavesalowed = abs((int)$slavesalowed);
   output("`\$`c`bMercato degli Schiavi`c`b`n`n");
   output("`@Ti rechi al mercato degli schiavi.");
   output("`@Una grande folla è accalcata attorno al mercato. Gli schiavi vengono venduti a chiunque possa permetterselo.");
   output("`@Apophis, il tuo Manager personale, ti dice che potresti utilizzare `^".$slavesalowed." `@schiavi nelle tue fattorie.`n");
   output("`@Il prezzo attuale per gli schiavi è di `^1000 pezzi d'oro`@.");
   output("<form action='slaves.php?op=slaves10' method='POST'>",true);
   output("`2Quanti schiavi vuoi acquistare: <input name='amount' id='amount' width='5'>`n`n",true);
   output("<input type='submit' class='button' value='Finalizza Contratto'></form>",true);
   addnav("","slaves.php?op=slaves10");
   addnav("Torna alle Fattorie","slaves.php");
}else if ($_GET['op']=="slaves10"){
   $amt = abs((int)$_POST['amount']);
   $slaveprice = 1000;
   $fact1 = $session['user']['farms']*100;
   $slavesalowed = $fact1 - $session['user']['slaves'];
   $slavesalowed = abs((int)$slavesalowed);
   $totalprice = $amt * $slaveprice;
   if ($slavesalowed >= $amt){
      $case+=1;
   }else{
      $case+=3;
   }
   if ($totalprice <= $session['user']['gold']){
      $case+=1;
   }else{
      $case+=3;
   }
   if ($case==2){
      $session['user']['gold'] -= $totalprice;
      $session['user']['slaves'] += $amt;
      output("`%Hai acquistato `^".$amt." `%Schiavi, e sei ora proprietario di `^".$session['user']['slaves']." `%schiavi.`n`n");
      debuglog("spende ".$totalprice." pezzi d'oro per acquistare ".$amt." schiavi nelle Fattorie.");
      addnav("Torna alle Fattorie","slaves.php");
   }else{
      output("`&Apophis è confuso:`n");
      output("`4\"`\$Se non abbiamo l'oro o lo spazio necessario, non dovremmo tentare di acquistare degli schiavi.`n`n");
      addnav("Torna alle Fattorie","slaves.php");
   }
}
}else{
    output("`^Questo mod è riservato ai donatori di OGSI. Aiutaci a sostenere i costi del server, anche 1 solo € è sufficiente.`n");
    output("È semplice diventare donatori, basta cliccare sul link `@PayPal`#OGSI `^che vedi nella pagina, e seguire le istruzioni.`n");
    addnav("Torna alle Tenute Reali","houses.php");
}
page_footer();
?>