<?php

if (!isset($session)) exit();
page_header("Il Negromante");
$nome = $session['user']['name'];
$titolo = $session['user']['title'];
if ($_GET['op']==""){
    output("`n`FTi imbatti in uno strano vecchietto. Avvolto in un nero mantello, cammina lentamente appoggiandosi ");
    output("a un bitorzoluto bastone. I suoi occhi sono talmente infossati che il suo viso sembra simile a un ");
    output("teschio con le orbite vuote incorniciate da un sogghigno minaccioso.`n");
    output("Guardandolo hai come la sensazione di percepire una presenza malefica, come se un'aura demoniaca ");
    output("venisse irradiata da lui. ");
    output("Senti la paura attanagliarti lo stomaco e saresti tentat".($session['user']['sex']?"a":"o")." di ");
    output("fuggire a gambe levate ma, nello stesso tempo, sei attratt".($session['user']['sex']?"a":"o")." ");
    output("irresistibilmente da quegli occhi spettrali.`nEgli ti vede e sussurra:`n`n`\$\"Ahh... Vieni quì ");
    output("vicino a me e dammi la mano ragazzin".($session['user']['sex']?"a":"o").". Ci sono dei pericoli ");
    output("tremendi in questa foresta, non lo sai? ");
    output("Se invece ti ritieni forte, coraggios".($session['user']['sex']?"a":"o")." e senza paura, avrei ");
    output("alcuni servizi da offrirti che potresti trovare molto interessanti.\"`n`n");
    output("`FFai quello che ti chiede e ti avvicini? Preferisci usufruire dei servizi che ha da proporti ? O ");
    output("scappi a gambe levate ? `n Sai che ogni scelta che farai potrebbe essere molto pericolosa e ti costerà ");
    output("un turno di combattimento nella foresta. ");
    addnav("`2Avvicinati","forest.php?op=necromancer");
    addnav("`2Usufruisci dei suoi servizi","forest.php?op=servizi");
    addnav("`2Lascialo qui","forest.php?op=leave");
    //addnav("","forest.php?op=necromancer");
    //addnav("","forest.php?op=servizi");
    //addnav("","forest.php?op=leave");
    $session['user']['specialinc'] = "necromancer.php";
}elseif ($_GET['op']=="necromancer"){
    $session['user']['turns']--;
    output("`n`FA dispetto della presenza malefica che avverti in lui, cammini verso il vecchietto. Mentre ti ");
    output("avvicini, il sorriso si trasforma in un ghigno diabolico. ");
    switch(e_rand(1,10)){
      case 1:
        output("`FLo raggiungi e gli dai la mano ma una mano scheletrica ti afferra con forza e ti stringe la gola, ");
        output("il suo sussurro si fà rauco e quasi in un ruggito animalesco dice:`n`n\"`\$\"Tua madre non ti ha mai detto ");
        output("di non dare ascolto sconosciuti?`F\"`n`nDalla bocca del vecchio mago esce una risata satanica ");
        output("mentre la sua stretta si fà sempre più forte diventando una morsa mortale.`nSenti un dolore ");
        output("bruciante quando la tua anima viene strappata dal tuo corpo, poi il buio.`n`n");
        output("`\$`bSei mort".($session['user']['sex']?"a":"o")."!!`b</font>`n`n",true);
        output("`FIl perfido vecchietto perquisisce le tue spoglie e sottrae tutto l'oro che portavi con te.`n");
        output("Perdi inoltre il `\$15%`F della tua esperienza. e potrai tornare a combattere solo domani.`n");
        debuglog("viene ucciso e perde ".$session['user']['gold']." oro e 15% exp dal necromancer");
        $session['user']['alive']=false;
        $session['user']['hitpoints']=0;
        $session['user']['experience']*=0.85;
        $session['user']['gold'] = 0;
        addnav("Notizie Giornaliere","news.php");
        $session['user']['specialinc'] = "";
        addnews("`FIl corpo di ".$session['user']['name']."`F è stato trovato nella foresta, derubato di tutto il suo oro.");
        break;
      case 2:
      case 3:
        output("`FQuando lo raggiungi sussurra sghignazzando:`n`n\"`\$Dai, ragazz".($session['user']['sex']?"a mia":"o mio").",");
        output(" più vicino. Ecco, un altro PASSO!`F\"`n`n");
        output("Mentre urla l'ultima parola, il malvagio demone estrae una bacchetta nera e il suo tocco ti provoca ");
        output("un dolore lancinante in tutto il corpo. E' come se fuoco liquido scorresse nelle tue ossa, dentro le ");
        output("tue vene e sotto la pelle. Mentre la morte sta per afferrarti, il dolore si interrompe.`nTi rialzi, ");
        output("incredulo di essere vivo, ti guardi intorno, ma la malefica creatura è scomparsa.`n");
        if ($session['user']['maxhitpoints']>1){
            $session['user']['maxhitpoints']-=1;
            debuglog("Perde 1 HP permanente dal negromante");
            output("`nL'esperienza è stata talmente traumatica che hai perso `^ 1 `FHitPoint`^ `i`bPermanente`i`b `F!`n");
        }
        output("Ti senti molto debole e scopri che i tuoi HitPoints sono ridotti ad `^Uno!`F`n");
        $session['user']['hitpoints']=1;
        addnews($session['user']['name']."`5 ritorna dalla foresta, meno in forma di quando era partit".($session['user']['sex']?"a":"o"));
        addnav("`@Torna alla Foresta","forest.php");
        $session['user']['specialinc'] = "";
        break;
      case 4:
      case 5:
      case 6:
      case 7:
        output("`FQuando lo raggiungi con aria supplichevole ti chiede:`n`n\"`\$Non avresti una gemma da regalarmi? ");
        output("Una piccola gemmina per me...`F\"`n`n");
        output("Gli dai una gemma?");
        addnav("`2Dagli una gemma","forest.php?op=GiveGem");
        addnav("`2Se lo scorda","forest.php?op=KeepGem");
        //addnav("","forest.php?op=GiveGem");
        //addnav("","forest.php?op=KeepGem");
        $session['user']['specialinc'] = "necromancer.php";
        break;
      case 8:
      case 9:
      case 10:
        output("Sei praticamente di fronte a lui, quando odi delle urla provenire dal folto della foresta alla tua ");
        output("destra. `nTi giri per vedere e scorgi un gruppo di guardie dello sceriffo provenienti dal villaggio ");
        output("avanzare tra gli alberi accompagnate dai cani. Ti giri nuovamente verso il vecchio, ma questi si è ");
        output("volatilizzato, scoprirai in seguito che è una creatura malvagia sospettata di parecchi omicidi ed ");
        output("è il ricercato principale del villaggio.`n");
        output("Peccato però, forse hai scampato un pericolo, ma non saprai mai che cosa volesse da te quel vecchio... ");
        addnav("`@Torna alla Foresta","forest.php");
        $session['user']['specialinc'] = "";
        break;
  }
}elseif ($_GET['op']=="servizi"){
    $session['user']['turns']--;
    if ($session['user']['gold']>=1000){
        if ($session['user']['gold']>10000){
            $oro_da_pagare = intval( $session['user']['gold'] * 0.1 );
        }else{
            $oro_da_pagare = 1000;
        }
        output("`n`F\"`\$Noto con piacere che hai scelto di avvalerti dei miei servizi, brav".($session['user']['sex']?"a":"o")."!!");
        output("  Ottima scelta !!\"`n`n");
        output("`Fpoi prendendoti da parte con un'aria di complicità e sussurrandoti con voce rauca in un orecchio`n`n");
        output("\"`4mhhh .... vediamo .. vediamo un pò ... cosa posso fare per te ....`n");
        output("mhhh... vediamo .. vediamo un pò ... quali servizi potrei offrirti... mhhh.....`n");
        output("..... diciamo che per la misera cifra di `^$oro_da_pagare `4pezzi d'oro potrei .....`n");
        output("mhhh .... ad esempio `Xdirti `4quanti punti cattiveria hai, oppure ....... `n");
        output("mhhh .... `Xvenderti `4alcuni punti cattiveria, se ti interessano ... sì, sì ...`n");
        output("mhhh .... o addirittura `Xtogliertene `4qualcuno. `n`n");
        output("Che ne dici ? Cosa posso fare per te ? ");
        addnav("`2Dimmi i miei punti cattiveria","forest.php?op=Dimmi");
        addnav("`\$Rendimi più cattiv".($session['user']['sex']?"a":"o")."","forest.php?op=Vendimi");
        addnav("`@Fammi più buon".($session['user']['sex']?"a":"o")."","forest.php?op=Toglimi");
        addnav("`^Rinuncio all'affare","forest.php?op=Rinuncia");
        $session['user']['specialinc'] = "necromancer.php";
    }else{
        output("`n`FPurtroppo non hai soldi sufficienti per pagare alcun tipo di servizio offerto dal vecchio e ");
        output("questi irritato sparisce in una nuvola di `^zolfo.`n`n");
        output("`FAttonit".($session['user']['sex']?"a":"o")." dall'incontro appena effettuato e della perentoria ");
        output("sparizione, resti con un palmo di naso ritrovandoti da solo a fantasticare sui meravigliosi guadagni ");
        output("e sui fantastici benefici che avresti potuto ottenere con i servizi offerti dalla creatura demoniaca ");
        output("appena svanita nel nulla.`n`n");
        output("Probabilmente il vecchio era un `\$demone`F inviato da `\$Ramius`F e sicuramente maligno e molto pericoloso, ");
        output("ma che importa ? `n`n\"`\$`bRamius `%io non ti temo, non ho paura del `\$Signore del Male `%e dei suoi ");
        output("immondi servitori!!`b`F\" urli a gran voce .... `n");
        addnav("`@Torna alla Foresta","forest.php");
        $session['user']['specialinc'] = "";
    }
}elseif ($_GET['op']=="leave"){
    $session['user']['turns']--;
    output("`n`FImpaurit".($session['user']['sex']?"a":"o")." dal volto minaccioso e temendo il potere demoniaco ");
    output("emanato dal vecchio, ti giri di scatto e ti butti in una corsa a perdifiato, allontanandoti il più ");
    output("velocemente possibile e fermandoti solo dopo aver frapposto parecchia strada tra te e la malefica ");
    output("creatura del male!`n`n");
    output("Così facendo ti addentri nel folto di un bosco e impieghi un turno per ritrovare il giusto sentiero.`n`n");
    output("Hai perso `@1 `FTurno di Combattimento!!`n");
    addnav("`@Torna alla Foresta","forest.php");
    $session['user']['specialinc']="";
}elseif($_GET['op']=="GiveGem"){
    if ($session['user']['gems']>0){
        output("`n`FIntenerit".($session['user']['sex']?"a":"o")." dal vecchietto, gli doni una delle tue sudate ");
        output("gemme. Lui te la strappa di mano e ");
        switch (e_rand(1,10)){
            case 1: case 2: case 3: case 4: case 5: case 6: case 7: case 8: case 9:
                output("gorgoglia di gioia. Si gira verso di te e dice, \"`n`n`\$Poichè sei stat".($session['user']['sex']?"a":"o"));
                output(" generos".($session['user']['sex']?"a":"o")." con me ");
                output(($session['user']['sex']?"mia cara":"ragazzo").", metterò una buona parola per te con il mio vecchio amico ");
                output("`b`\$Ramius.`b`F\"`n`nIl mago estrae una bacchetta rossa da sotto il mantello, la agita sulla tua testa, ");
                output("e scompare nella foresta in una nuvola di `^zolfo`F.`n`n");
                $favori = e_rand(20,40);
                output("`FHai guadagnato `^$favori`F favori con `\$Ramius, Signore della Morte`F.");
                $session['user']['deathpower'] += $favori;
                $session['user']['gems']--;
                debuglog("Paga 1 gemma per guadagnare $favori favori con Ramius dal negromante");
                addnav("`@Torna alla Foresta","forest.php");
                $session['user']['specialinc'] = "";
               break;
            case 10:
                output("scompare alla tua vista in una nuvola di `^zolfo. `F`n Quell'avaro vecchietto ti ha imbrogliat".($session['user']['sex']?"a":"o"). " rubandoti una gemma!");
                output("Affrant".($session['user']['sex']?"a":"o")." per l'imbroglio subito non ti resta che ritornare nella foresta.");
                $session['user']['gems']--;
                debuglog("Paga 1 gemma ma non guadagna nessun favore con Ramius dal negromante");
                addnav("`@Torna alla Foresta","forest.php");
                $session['user']['specialinc'] = "";
               break;
        }
    }else{
        output("`n`FFrughi nelle tue tasche per scoprire che non hai più gemme. Il vecchio inizialmente attende e ti ");
        output("guarda con aria fiduciosa. Quando si accorge che non hai neanche una gemma e che hai tentato di imbrogliarlo, ");
        output("inizia ad assumere un aspetto sempre più cattivo e minaccioso.");
        output("`nImpaurit".($session['user']['sex']?"a":"o")." da quel volto e temendo il potere demoniaco emanato dal ");
        output("vecchio, ti giri di scatto e ti butti in una corsa a perdifiato, allontanandoti il più velocemente possibile ");
        output("e fermandoti solo dopo aver frapposto parecchia strada tra te e la malefica creatura del male!`n");
        output("Così facendo ti addentri nel folto di un bosco e impieghi un ulteriore turno per ritrovare il giusto sentiero.`n`n");
        $session['user']['turns']--;
        output("Hai perso `@2 `Fturni di combattimento !`n ");
        addnav("`@Torna alla Foresta","forest.php");
        $session['user']['specialinc'] = "";
    }
}elseif ($_GET['op']=="KeepGem"){
    output("`n`FNon volendo separarti da una delle tue preziose gemme e temendo il potere demoniaco emanato dal ");
    output("vecchio, ti giri di scatto e ti butti in una corsa a perdifiato, allontanandoti il più velocemente ");
    output("possibile e fermandoti solo dopo aver frapposto parecchia strada tra te e la malefica creatura del male!`n");
    addnav("`@Torna alla Foresta","forest.php");
    $session['user']['specialinc']="";
}elseif ($_GET['op']=="Dimmi"){
    if ($session['user']['gold']>10000){
        $oro_da_pagare = intval( $session['user']['gold'] * 0.1 );
    }else{
        $oro_da_pagare = 1000;
    }
    $cattiveria = $session['user']['evil'];
    output("`n`FPorgi al vecchio l'oro che ti ha chiesto e questi lo arraffa con una mano scheletrica e lo nasconde ");
    output("sotto il suo mantello. Poi con le labbra tirate in un un ghigno satanico estrae un libriccino rilegato ");
    output("in pelle nera che incomincia a sfogliare.`n`n");
    output("`\$Quindi vuoi sapere quanti punti cattiveria hai. Bene bene, vediamo un pò ..... `n");
    output("Ecco, trovato! ...... `b`G$nome`b `\$hai `^$cattiveria `\$Punti Cattiveria !`n`n");
    $session['user']['gold'] -= $oro_da_pagare ;
    debuglog("Paga $oro_da_pagare per avere informazione su propria cattiveria");
    addnav("`@Torna alla Foresta","forest.php");
    $session['user']['specialinc']="";
}elseif ($_GET['op']=="Vendimi"){
    if ($session['user']['gold']>10000){
       $oro_da_pagare = intval( $session['user']['gold'] * 0.1 );
    }else{
       $oro_da_pagare = 1000;
    }
    $punti_da_aggiungere = e_rand(1,5);
    $session['user']['gold'] -= $oro_da_pagare ;
    $session['user']['evil'] += $punti_da_aggiungere ;
    debuglog("Paga $oro_da_pagare per acquistare $punti_da_aggiungere punti cattiveria");
    output("`n`FPorgi al vecchio l'oro che ti ha chiesto e questi lo arraffa con una mano scheletrica e lo nasconde ");
    output("sotto il suo mantello. Poi con le labbra tirate in un un ghigno satanico estrae una fiala contenente un ");
    output("liquido di colore nero e te la consegna.`n`n");
    output("\"`\$Quindi `b`F$nome`b `\$vuoi diventare ancora più cattiv".($session['user']['sex']?"a":"o")." di quanto ");
    output("lo sei ora. Ottimo, bevi senza timore questa pozione magica .....`F\"`n`n");
    output("Ingurgiti il liquido nerastro tutto in un fiato, il sapore è terribile e crolli al suolo in preda al ");
    output("voltastomaco...`n");
    output("Quando ti riprendi dalla nausea il vecchio è scomparso, faticosamente ti rimetti in piedi e hai come la ");
    output("strana sensazione che la malignità si sia impadronita di te.`n`n");
    output("`n`#Hai acquisito `^$punti_da_aggiungere `#punti cattiveria ! `n");
    addnav("`@Torna alla Foresta","forest.php");
    $session['user']['specialinc']="";
}elseif ($_GET['op']=="Toglimi"){
    if ($session['user']['gold']>10000){
       $oro_da_pagare = intval( $session['user']['gold'] * 0.1 );
    }else{
       $oro_da_pagare = 1000;
    }
    $punti_da_togliere = e_rand(1,2);
    $session['user']['gold'] -= $oro_da_pagare ;
    $session['user']['evil'] -= $punti_da_togliere ;
    debuglog("Paga $oro_da_pagare per farsi togliere $punti_da_togliere punti cattiveria");
    output("`n`FPorgi al vecchio l'oro che ti ha chiesto e questi lo arraffa con una mano scheletrica e lo nasconde ");
    output("sotto il suo mantello.`n");
    output("`FPoi con le labbra tirate in un un ghigno satanico estrae una fiala contenente un liquido di colore ");
    output("`#turchese`F e te la consegna.`n`n");
    output("\"`\$Quindi `b`F$nome`b `\$vuoi diventare meno cattiv".($session['user']['sex']?"a":"o")." di quanto lo ");
    output("sei ora. Peccato... Bevi senza timore questa pozione magica .....`F\"`n`n");
    output("Ingurgiti il liquido azzurrognolo tutto in un fiato, il sapore è terribile e crolli al suolo in preda ");
    output("al voltastomaco...`n");
    output("Quando ti riprendi dalla nausea il vecchio è scomparso, faticosamente ti rimetti in piedi e hai come la ");
    output("strana sensazione di essere diventat".($session['user']['sex']?"a":"o")." più buon".($session['user']['sex']?"a":"o").".`n`n");
    output("`#Hai perso `^`b$punti_da_togliere`b `#Punti Cattiveria!!`n`n");
    addnav("`@Torna alla Foresta","forest.php");
    $session['user']['specialinc']="";
}elseif ($_GET['op']=="Rinuncia"){
    output("`n`FAvendo timore del potere demoniaco emanato dal vecchio non trovi più il coraggio per concludere ");
    output("l'affare, ti giri di scatto e ti butti in una corsa affannosa alla ricerca disperata di una via di fuga.`n");
    output("Il vecchio alza un braccio e un fulmine scaturisce dalla sua mano scheletrica raggiungendoti e colpendoti ");
    output("alla schiena con la forza di un maglio.`n`n");
    output("Il colpo è tremendo e rotoli svenut".($session['user']['sex']?"a":"o")." in un fosso. Quando rinvieni, ti rialzi incredul".($session['user']['sex']?"a":"o")." di essere ancora ");
    output("viv".($session['user']['sex']?"a":"o").". Ti guardi intorno timoros".($session['user']['sex']?"a":"o").", ma la malefica creatura è scomparsa.`n`n");
    output("Ti senti molto debole e scopri che i tuoi hitpoints sono ridotti ad `^Uno!`F e non ti resta altro da ");
    output("fare che riprendere le tue avventure nella foresta.`n");
    $session['user']['hitpoints']=1;
    addnav("`@Torna alla Foresta","forest.php");
    $session['user']['specialinc']="";
}
page_footer();
?>