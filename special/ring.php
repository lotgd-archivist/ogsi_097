<?php
/*
L'Anello Incantato - by CMT
Ultima revisione 17/07/06
Qualche piccolo ritocco Excalibur 13/6/07

Il player vede qualcosa che luccica tra i rovi e può tentare di prenderlo.
In alcuni casi trova qualcosa (un pezzo d'oro, una gemma, una scaglia d'argento) o niente del tutto e perde 1 HP nel caso più sfortunato, parecchi HP e fascino).
Nella maggioranza dei casi trova un anello che può decidere di indossare o abbandonare (perdendo un turno nel secondo caso).
Indossare l'anello può portare grandi ricompense (fascino, oro e gemme, punti abilità) o gravi tragedie (morte, perdita di molti HP, perdità di punti abilità o fascino, divorzio!) e perfino un cambio casuale di abilità.
In alcuni casi i personaggi con un po' di fascino possono essere salvati da una fatina dai risultati peggiori.
*/
if (!isset($session)) exit();
if ($_GET['op']==""){
  page_header("Un luccichio tra i rovi");
  output("`%Mentre ti aggiri per la foresta in cerca di mostri da impagliare per farne un bel trofeo, vedi qualcosa che luccica tra i rovi. Potrebbe essere una gemma, o un gioiello, o un artefatto dagli immensi poteri magici... o magari un semplice pezzo di vetro... Cosa fai?");
  addnav("Controlli meglio","forest.php?op=check");
  addnav("Te ne vai","forest.php?op=leave");
  $session['user']['specialinc']="ring.php";
}else{
  switch ($_GET['op']){
    case "leave":
      output("`2Non hai tempo da perdere a frugare tra i rovi solo per un luccichio, che lo faccia qualcun altro meno impegnato!");
      addnav("Torna alla foresta","forest.php");
    break;

    case "leavering":
      page_header("L'Anello Incantato");
      output("`3Ma per favore, gli anelli sono roba da signorine snob, non da guerrieri! Getti via il cerchietto metallico e te ne vai. Anelli! Pfui! E che nessuno osi pensare che hai avuto paura di indossarlo!!!`n");
      output("Comunque sia, questa storia ti ha fatto perdere il tempo per un combattimento in foresta.");
      $session['user']['turns']--;
      addnav("Torna alla foresta","forest.php");
    break;

    case "check":
      output("`2Ti inginocchi vicino al cespuglio ed allunghi le mani per afferrare l'oggetto luccicante. ");
      $chance = e_rand(0,100);
      if ($chance == 0){
        output("`2Prima di riuscire anche solo a sfiorarlo hai le mani piene di graffi, ma alla fine riesci a toccare qualcosa, ");
        output("qualcosa di caldo... e morbido... e `\$AHIA!`2 Qualcosa che morde!`n");
        output("Ritiri le mani di scatto, o almeno ci provi, ma sei impigliato nei rovi e non fai che peggiorare la situazione!!!`n`n");
        output("Alla fine, qualunque cosa ti abbia morso ti lascia andare, e con calma e pazienza riesci a estrarre le mani sanguinanti dal cespuglio.`n");
        output("Per caso, nel farlo afferri anche l'oggetto che eri venuto a cercare e guarda... dopotutto era davvero un pezzo di vetro, anche se luccica davvero tanto...`n");
        output("... così tanto, in effetti, da attrarre l'attenzione di una gazza, che ti si getta addosso in picchiata e ti dà una tremenda beccata per poi volarsene via col vetraccio!!!`n`n");
        output("Ma tu guarda che razza di giornata! Gli dei devono avercela con te oggi!!!`n");
        output("In tutta questa storia hai perso un combattimento in foresta e parecchi hitpoint.`n");
        $session['user']['turns']--;
        $session['user']['hitpoints'] = round($session['user']['hitpoints']*0.33);
        if ($session['user']['charm']>0){
          output("Inoltre, visto come sei ridotto, perdi un punto di fascino.");
          $session['user']['charm']--;
        }
        $session['bufflist']['hurtands'] = array(
        "name"=>"`^Mani graffiate",
        "rounds"=>5,
        "wearoff"=>"Le tue mani stanno migliorando",
        "atkmod"=>.7,
        "roundmsg"=>"Le mani ti fanno male e non colpisci troppo bene il nemico",
        "activate"=>"roundstart");
        addnews("`3L".($session['user']['sex']?"a sventurata":"o sventurato")." `^".$session['user']['name']." `3si
        è fatt".($session['user']['sex']?"a":"o")." graffiare, mordere e beccare per recuperare un inutile
        pezzo di vetro...!!!");
        addnav("Torna alla foresta","forest.php");
      }elseif ($chance <= 5){
        output("`2Riesci a prenderlo e scopri che è veramente un inutile pezzo di vetro. Ti sei graffiato tutte le mani per niente!!!");
        $session['user']['hitpoints']--;
        addnav("Torna alla foresta","forest.php");
      }elseif ($chance <= 15){
        output("`2Riesci a prenderlo e scopri che è un pezzo d'oro. Ti sei graffiato tutte le mani per così poco!!!");
        $session['user']['hitpoints']--;
        $session['user']['gold']++;
        debuglog("trova un pezzo d'oro tra i cespugli");
        addnav("Torna alla foresta","forest.php");
      }elseif ($chance <= 20){
        output("`2Riesci a prenderlo e scopri che è una `&gemma!!`2 Ti sei graffiato tutte le mani ma forse ne è valsa
        la pena!!!");
        $session['user']['hitpoints']--;
        $session['user']['gems']++;
        debuglog("trova una gemma tra i cespugli");
        addnav("Torna alla foresta","forest.php");
      }elseif ($chance <= 25){
        output("`2Riesci a prenderlo e scopri che è una `7scaglia d'Argento!!`2 Ti sei graffiato tutte le mani ma
        forse ne è valsa la pena!!!");
        $session['user']['hitpoints']--;
        if (!zainoPieno($session['user']['acctid'])){
          $sqli="INSERT INTO zaino (idoggetto, idplayer) VALUES ('5', '".$session['user']['acctid']."')";
          db_query($sqli) or die(db_error(LINK));
          debuglog("trova una scaglia d'argento tra i cespugli");
        }else{
          output("`%È un vero peccato che tu abbia lo zaino pieno e non possa raccoglierla!!!`n");
          debuglog("trova una scaglia d'argento tra i cespugli ma ha lo zaino pieno");
        }
        addnav("Torna alla foresta","forest.php");
      }else{
        page_header("L'Anello Incantato");
        output("`2Dopo aver frugato un po', senti sotto le dita qualcosa di liscio e rotondo. Lo estrai dai rovi e vedi
        che si tratta di un anello lucido e brillante. Sembra quasi brillare di luce propria! Potrebbe essere un
        anello incantato! Cosa fai?");
        addnav("Indossi l'anello","forest.php?op=wear");
        addnav("Lo lasci e te ne vai","forest.php?op=leavering");
        $session['user']['specialinc']="ring.php";
      }
    break;
    case "wear":
      page_header("L'Anello Incantato");
      output("`2Decidi di indossare l'anello e scopri ");
      $chance = e_rand(0,100);
      if ($chance == 0){
        if ($session['user']['charisma']==4294967295){
          output("`2con orrore che si tratta di una fede nuziale, e quel che è peggio non viene più via in nessun modo!!!`n`n");
          $sql = "SELECT acctid,name FROM accounts WHERE locked=0 AND acctid=".$session['user']['marriedto']."";
          $result = db_query($sql) or die(db_error(LINK));
          $row = db_fetch_assoc($result);
          $partner=$row['name'];
          output("`2Tornato al villaggio, cerchi inutilmente di spiegare a $partner `2che non ti sei
          sposat".($session['user']['sex']?"a":"o")." con un".($session['user']['sex']?" altro uomo":"'altra donna").",
          ma ".($session['user']['sex']?"lui":"lei")." non ti crede e ti lascia (ogni scusa è buona per portarsi via
          metà delle proprietà del coniuge...)");
          addnews("`\$$partner `\$ha lasciato ".$session['user']['name']."`\$ dopo aver scoperto che era
          bigam".($session['user']['sex']?"a":"o")."!");
          $session['user']['charisma']=0;
          $session['user']['marriedto']=0;
          if ($session['user']['goldinbank']>0){
            $getgold=round($session['user']['goldinbank']/2);
          }
          $session['user']['goldinbank']-=$getgold;
          $sql = "UPDATE accounts SET charisma=0,marriedto=0 WHERE acctid=".$row['acctid'];
          db_query($sql) or die(db_error(LINK));
          $sql = "DELETE FROM matrimoni
                  WHERE acctid1 = ".$session['user']['acctid']."
                  OR acctid2 = ".$session['user']['acctid'];
          db_query($sql) or die(db_error(LINK));
          $text="`3Ricevi $getgold per il tradimento subito`0";
          assign_to_pg($row['acctid'],'gold',$getgold,$text);
          debuglog("trova una fede nuziale che lo fa divorziare da $partner");
          systemmail($row['acctid'],"`\$Tradimento!!!`0","`&".$session['user']['name']."`6 è
          tornat".($session['user']['sex']?"a":"o")." da chissà dove con un'altra fede al dito!!!`n
          Non ti sei fatt".($session['user']['sex']?"o":"a")." incantare dalle sue patetiche scuse ed hai
          immediatamente chiesto il divorzio.`nRiceverai `^$getgold`6 del suo patrimonio, per ritirarlo vai alla
          pagina delle preferenze.");
        }else{
          output("`2con orrore che l'anello doveva essere maledetto e ti ha trasformato in un orribile mostro!
          Sei talmente orrendo che solo a vedere la tua immagine riflessa quasi ti viene un accidente!`n`n");
          output("`\$Hai `&COMPLETAMENTE `\$perso il tuo fascino!");
          $session['user']['charm'] = 0;
          debuglog("viene trasformato in un mostro da un anello maledetto");
        }
      }elseif ($chance <= 5){
        output("`2che si tratta del favoloso anello dei tre desideri. Cerchi subito di esprimerne uno, ma dall'anello
        giunge una vocina metallica...`n");
        output("\"`#Siamo spiacenti di informarla che i desideri sono esauriti, speriamo di averla ancora tra i
        nostri clienti quando avremo rinnovato le scorte\".`n");
        output("`2Dopodiché l'anello scompare nel nulla...!`n`n");
        output("In tutta questa storia, hai solo perso il tempo di un combattimento in foresta, che nervi!");
        $session['user']['turns']--;
      }elseif ($chance <= 15){
        output("`2con tua immensa sorpresa di essere diventato invisibile!");
        $session['bufflist']['invring'] = array(
        "name"=>"`^Anello dell'Invisibilità",
        "rounds"=>30,
        "wearoff"=>"Combattendo hai perso l'anello!",
        "atkmod"=>1.2,"roundmsg"=>"Sei invisibile! {badguy} non sa dove colpire!",
        "badguyatkmod"=>0.1,
        "activate"=>"defense");
      }elseif ($chance <= 30){
        output("`2che forse l'anello non sarà magico ma di sicuro ti sta davvero bene al dito! Ti dà quel certo
        non so che...`n`n");
        $addcharm=e_rand(2,5);
        output("`^Guadagni $addcharm punti fascino!!!");
        $session['user']['charm']+= $addcharm;
        debuglog("guadagna $addcharm punti fascino indossando un anello");
      }elseif ($chance <= 45){
        output("`2che qualcosa è cambiato profondamente in te, anche se al momento non saresti in grado di dire cosa.
        Forse un giorno lo capirai...");
        debuglog("cambia specialità indossando un anello");
        $newspec = e_rand(0,14);
        if ($newspec == $session['user']['specialty']){
          $newspec = 0;
        }
        $session['user']['specialty'] = $newspec;
      }elseif ($chance <= 60){
        output("`2che... cosa hai scoperto..? E poi perché ti aspettavi di scoprire qualcosa...?");
        output("E chi sei, esattamente, già che ci siamo...? Cosa fai qui...? Dove vai...? Da dove vieni...?`n");
        if (e_rand(1,50) <= $session['user']['charm']){
          output("`n`2Fortunatamente, impietosita dalla tua situazione, una fatina scende su di te e ti cosparge
          la mano con la sua polvere di fata.`n");
          output("`7Il malefico anello scompare, e la tua mente torna lucida`n");
          output("`2Da questa storia hai imparato una lezione (mai indossare un anello senza sapere cosa sia!!!)
          ed acquisito un po' di esperienza.");
          debuglog("guadagna ".round($session['user']['experience']*0.01)." liberandosi di un anello maledetto");
          $session['user']['experience'] += round($session['user']['experience']*0.01);
        }else{
          output("`2Forse l'anello che hai al dito potrebbe aiutarti a cap... un momento... l'anello... quell'anello...???`n`n");
          output("Ti togli il malefico monile dal dito e la mente ti si schiarisce subito.");
          if (e_rand(1,100) <= 40 and $session['user']['specialty']>0){
            output(".. ma senti che una parte della tua memoria è andata perduta per sempre.");
            $skillnames = array(1 => "`\$Arti Oscure", "`%Poteri Mistici", "`^Furto", "`3Militare","`\$Seduzione","`^Tattica","`@Pelle di Roccia","`#Retorica","`%Muscoli","`3Natura","`&Clima","`^Elementalista","`6Rabbia Barbara","`5Canzoni del Bardo");
            $skills = array(1=>"darkarts","magic","thievery","militare","mystic","tactic","rockskin","rhetoric","muscle","nature","weather","elementale","barbaro","bardo");
            $skillpoints = array(1=>"darkartuses","magicuses","thieveryuses","militareuses","mysticuses","tacticuses","rockskinuses","rhetoricuses","muscleuses","natureuses","weatheruses","elementaleuses","barbarouses","bardouses");
            output("Hai `\$PERSO `2un punto di utilizzo in ".$skillnames[$session['user']['specialty']]."!`n");
            $session['user'][$skillpoints[$session['user']['specialty']]]--;
            debuglog("perde un punto utilizzo in ".$skillnames[$session['user']['specialty']]." indossando un anello");
          }else{
            output("`n`5Purtroppo, nel frattempo hai perso il tempo di un combattimento in foresta.");
            $session['user']['turns']--;
          }
        }
      }elseif ($chance <= 70){
        output("`2che la tua mente si apre a conoscenze che fino adesso non sapevi di avere!!!`n");
        increment_specialty();
      }elseif ($chance <= 80){
        output("`2che l'anello ti sta succhiando via la vita!!! Ti senti sempre più debole e non riesci a sfilarti
        quel maledetto gioiello dal dito!!!`n");
        if (e_rand(1,50) <= $session['user']['charm']){
          output("`n`2Fortunatamente, impietosita dalla tua situazione, una fatina scende su di te e ti cosparge
          la mano con la sua polvere di fata.`n");
          output("`2Il malefico anello scompare, e il tuo corpo torna integro`n");
          output("`2Da questa storia hai imparato una lezione (`7mai indossare un anello senza sapere cosa sia!!!`2)
          ed acquisito un po' di esperienza.");
          debuglog("guadagna ".round($session['user']['experience']*0.01)." liberandosi di un anello maledetto");
          $session['user']['experience'] += round($session['user']['experience']*0.01);
        }else{
          output("`2Alla fine riesci a liberarti del malefico anello, ma nel frattempo ti ha terribilmente
          indebolito.`n");
          debuglog("Perde moltissimi HP indossando un anello maledetto");
          if (e_rand(1,100) <= 30){
            output("`@Ti rimane `\$UN SOLO `@hitpoint!!!");
            $session['user']['hitpoints'] = 1;
          }else{
            output("`^Hai perso gran parte dei tuoi hitpoints!!!");
            $session['user']['hitpoints'] = round($session['user']['hitpoints']/3);
          }
        }
      }elseif ($chance <= 90){
        output("`2che sull'anello sono incise rune magiche appartenenti ad un antico stregone.`n`n");
        output("`8Antico e ... `4Potente`4.`n`n");
        if (e_rand <= 50){
          output("`8Antico, `4Potente e... `\$MALVAGIO!!!`n");
          output("`(L'anello era una trappola mortale, creato dallo stregone in fin di vita per potersi reincarnare
          nel corpo del primo stolto che lo avesse indossato (in questo caso: TU!!!)`n");
          output("`8Senti la tua anima abbandonare il tuo corpo");
          if (e_rand(1,50) <= $session['user']['charm']){
            output(", ma un attimo prima che tu raggiunga l'aldilà, una fatina impietosita dalla tua situazione
            scende su di te e ti cosparge la mano con la sua polvere di fata.`n");
            output("`6Il malefico anello scompare, e sei di nuovo padrone di te stesso.`n");
            output("Da questa storia hai imparato una lezione (`(mai indossare un anello senza sapere cosa sia!!!`6)
            ed acquisito un po' di esperienza.");
            debuglog("guadagna ".round($session['user']['experience']*0.01)." liberandosi di un anello maledetto");
            $session['user']['experience'] += round($session['user']['experience']*0.01);
          }elseif (e_rand(150,500) <= $session['user']["evil"]){
            output(".`nMa che diamine, TU sei `^MOLTO `(più `\$MALVAGIO `(di lui, e lo rispedisci a calci
            (be', si fa per dire) da dove è venuto!`n");
            output("`6Peccato solo che in questa faccenda tu abbia sprecato il tempo per un turno in foresta.");
            $session['user']['turns']--;
          }else{
            output("`( e in un attimo ti ritrovi nell'aldilà, mentre lo stregone se ne va beatamente a spasso con
            il TUO corpo!`n`n`\$Sei MORTO!!!`n`(Lo stregone si è preso il tuo corpo e tutto il tuo oro.
            Fortunatamente non sembrava interessato alle gemme, e pensi che potrai recuperarle quando tornerai
            in vita (vale a dire, presumibilmente, domani)");
            $session['user']['hitpoints'] = 0;
            $session['user']['gold'] = 0;
            $session['user']["alive"] = false;
            debuglog("viene posseduto da uno stregone indossando un anello maledetto");
            addnews("`^".$session['user']['name']." `3 si comporta in maniera molto strana, ultimamente. Sembra quasi un'altra persona.");
          }
        }else{
          output("`(L'anello sembra essere infuso del suo potere, e così anche tu ora che l'hai indossato.`n
          Ti rende stranamente potente,`n");
          $skillnames = array(1 => "`\$Arti Oscure", "`%Poteri Mistici", "`^Furto", "`3Militare","`\$Seduzione","`^Tattica","`@Pelle di Roccia","`#Retorica","`%Muscoli","`3Natura","`&Clima","`^Elementalista","`6Rabbia Barbara","`5Canzoni del Bardo");
            $skills = array(1=>"darkarts","magic","thievery","militare","mystic","tactic","rockskin","rhetoric","muscle","nature","weather","elementale","barbaro","bardo");
            $skillpoints = array(1=>"darkartuses","magicuses","thieveryuses","militareuses","mysticuses","tacticuses","rockskinuses","rhetoricuses","muscleuses","natureuses","weatheruses","elementaleuses","barbarouses","bardouses");
            $newspec = e_rand (1,14);
            $session[user][$skillpoints[$newspec]] += 2;
            output("`^Guadagni DUE punti di utilizzo in ".$skillnames[$newspec]."!`n");
            debuglog("guadagna due punti utilizzo in ".$skillnames[$newspec]." indossando un anello incantato");
        }
      }elseif ($chance <= 95){
        output("`(che ti senti terribilmente pesante, è come se quell'anello pesasse una tonnellata! E non viene via!!!");
        $session['bufflist']['heavyring'] = array(
        "name"=>"`^Anello maledetto",
        "rounds"=>30,
        "wearoff"=>"`&Finalmente l'anello ti si sfila dal dito!",
        "atkmod"=>0.3,
        "roundmsg"=>"`6Sei troppo appesantito per combattere!",
        "badguyatkmod"=>1.6,
        "activate"=>"defense");
      }elseif ($chance <= 99){
        output("`(che l'anello stona terribilmente con il tuo look! Ti dà un'aria da deficiente...!`n`n");
        $addcharm=e_rand(1,3);
        output("Perdi `^$addcharm `(punti fascino!!!");
        $session['user']['charm']-= $addcharm;
        debuglog("perde $addcharm punti fascino indossando un anello");
      }else{
        output("`(che l'anello parla!`n");
        output("Con vocina metallica ti dice \"`^Lei ha esattamente tre secondi per esprimere il suo desiderio...
        due... uno...\"`(`n");
        $gems = e_rand(2,10);
        $gold = e_rand(1,15)*1000;
        output("Per la fretta non riesci a pensare a niente di più elaborato che oro e gemme. L'anello scompare,
        ma davanti a te vedi un mucchio di `&`b$gems `b`(gemme e `b`^$gold `b`(pezzi d'oro!!!`n");
        output("`@Oggi gli dei ti sorridono!!!");
        debuglog("guadagna $gold oro e $gem gemme con l'anello dei desideri");
        $session['user']['gold'] += $gold;
        $session['user']['gems'] += $gems;
        addnews("`3".($session['user']['sex']?"La":"Il")." fortunatissim".($session['user']['sex']?"a":"o")." `^".$session['user']['name']." `3 ha trovato un anello dei desideri e si è arricchit".($session['user']['sex']?"a":"o")."!!!");
      }
      if ($session['user']['alive']==true){
         addnav("Torna alla Foresta","forest.php");
      }else{
         addnav("Notizie Giornaliere","news.php");
      }
      $session['user']['specialinc']="";
    break;
  }
}

?>