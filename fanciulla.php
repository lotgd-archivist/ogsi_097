<?php
/********************************
* La Fanciulla by Excalibur     *
* from an original idea by Aris *
* for OGSI www.ogsi.it          *
* you can do whatever you want  *
*    with this but leave this   *
*        notice unchanged       *
*********************************/
require_once "common.php";
checkday();
if (!isset($session)) exit();
page_header("La Fanciulla");
$session['user']['locazione'] = 126;
if ($session['user']['quest']>3 || $session['user']['turns']<1) {
    page_header("La Fanciulla");
    $_GET['op']="ferma";
    output("`3Sei troppo stanco per affrontare altre avventure oggi. Riprova un altro giorno.");
    addnav("`2Torna al Villaggio","village.php");
}

else if ($_GET['op']==""){
//Setup dei Mastini Rabbiosi
                        $badguy = array("creaturename"=>"`@Due Mastini Rabbiosi`0"
                        ,"creaturelevel"=>12
                        ,"creatureweapon"=>"Fauci Bavose"
                        ,"creatureattack"=>30
                        ,"creaturedefense"=>28
                        ,"creaturehealth"=>280
                        , "diddamage"=>0);
    $points = 0;
    while(list($key,$val)=each($session['user']['dragonpoints'])){
        if ($val=="at" || $val == "de") $points++;
    }
    // Now, add points for hitpoint buffs that have been done by the dragon
    // or by potions!
    $points += (int)(($session['user']['maxhitpoints'] - 150)/5);

    // Okay.. *now* buff the dragon a bit.
    if ($beta)
        $points = round($points*1.5,0);
    else
        $points = round($points*.75,0);

    $atkflux = e_rand(0, $points);
    $defflux = e_rand(0,$points-$atkflux);
    $hpflux = ($points - ($atkflux+$defflux)) * 5;
    $badguy['creatureattack']+=$atkflux;
    $badguy['creaturedefense']+=$defflux;
    $badguy['creaturehealth']+=$hpflux;
    $session['user']['badguy']=createstring($badguy);


page_header("La Fanciulla");
output("`3Oggi hai esaurito i compiti che ti ha assegnato il borgomastro e decidi di farti una passeggiata nel bosco. Mentre `n");
output("`3girovaghi senza meta, cogliendo mirtilli e lamponi odi un grido femminile provenire dalla tua sinistra. Ti volti e `n");
output("`3vedi una graziosa fanciulla con il terrore dipinto sul volto che sembra fuggire da qualcosa o qualcuno. Deve essersi `n");
output("`3accorta della tua presenza, e notando il tuo portamento fiero, si rivolge a te dicendo: \"`^Oh nobile cavaliere la `n");
output("fortuna ha voluto che tu fossi qui. C'è qualcosa che mi sta seguendo, e mi blocca la strada per tornare a Castel Excalibur. ");
output("Saresti così coraggioso da controllare cosa c'è dietro quei cespugli?\"`n");
output("`n`3 Cosa vuoi fare ?");
addnav("`5Che si arrangi","fanciulla.php?op=lascia");
addnav("`3Aiuta la Fanciulla","fanciulla.php?op=aiuta");
}

else if ($_GET['op']=="lascia"){
page_header("La Fuga del Fifone");
output("`3Il coraggio e l'audacia sono caratteristiche che non hanno mai fatto parte del tuo bagaglio di guerriero. La tua `n");
output("sopravvivenza personale è più importante della vita di chiunque altro, fanciulle comprese. Te la lasci alle spalle `n");
output("e mentri ti allontani ti sembra di sentire dei gorgoglii sommessi e qualche grido soffocato.`n");
addnav("`2Torna al Villaggio","village.php");
}

else if ($_GET['op']=="aiuta"){
page_header("L'Audace Guerriero");
switch(e_rand(1,10)){
    case 1:
    output("`3Ti avvicini ai cespugli spada in mano, ed inizi ad esplorare la zona, quando un un vecchio satiro salta fuori `n");
    output("all'improvviso facendoti sussultare per lo spavento. Tu gli punti la spada alla gola e lui inizia ad implorarti `n");
    output("di risparmiarlo, offrendoti del denaro. La tua proverbiale avidità ha la meglio e lo lasci andare, non prima di `n");
    $money=e_rand(100,300)*$session['user']['level'];
    output("aver afferrato il sacchetto contenente `^$money pezzi d'oro `3che il satiro ti ha offerto.`n");
    debuglog("guadagna $money dal satiro con la fanciulla");
    $session[user][gold]+=$money;
    addnav("T?`6Torna dalla Fanciulla","fanciulla.php?op=prosegui");
    break;
    case 2: case 3: case 4: case 5: case 6: case 7: case 8: case 9: case 10:
    output("`3Mentre ti avvicini al gruppo di cespugli con fare cauto, da dietro una roccia spunta un mostruoso cane che `n");
    output("ti sbarra la strada, mentre un secondo cane ti blocca ogni possibile via di fuga posteriormente. Dovrai batterti `n");
    output("contro i due cani, oppure tentare una fuga. `n");
    output("`n`3 Cosa vuoi fare ?");
    addnav("`4Scappa !!!","fanciulla.php?op=run");
    addnav("`3Combatti","fanciulla.php?op=fight");
    }
}

else if ($_GET['op']=="run" || $_GET['op']=="fight"){
page_header("I due Mastini Rabbiosi");

if ($_GET['op']=="run"){
output("`2Tenti un'inutile fuga, ma le due bestie non ti lasciano nessuna possibilità di fuga. Dovrai combatterle !! ");
}
$battle=true;
if ($battle) {
   include_once("battle.php");
   $_GET['op']="fight";
        if($victory) {
                if ($badguy['diddamage'] != 1) $flawless = 1;
                $session['user']['quest']+=2;
                $_GET['op']="vittoria";
                output("`!Hai sconfitto `^".$badguy['creaturename'].".`n");
                addnews("`%".$session['user']['name']."`5 ha sconfitto `^".$badguy['creaturename']." `%aiutando la Fanciulla.");
                $expbonus=intval($session['user']['experience']*0.2);
                $gembonus=e_rand(3,8);
                $goldbonus=e_rand(200,500)*$session['user']['level'];
                output("`!Il tesoro che i `^".$badguy['creaturename']." `!custodivano è incredibile!!`n`n");
                output("`!Di fianco al loro cadavere trovi `6`b$gembonus gemme`b`! e `6`b$goldbonus pezzi d'oro`b`!!!!`n");
                output("`!Guadagni anche `6`b$expbonus`b`! punti esperienza!!!`n");
                if ($flawless==1){
                    output("Per aver disputato un combattimento perfetto guadagni altre `65 gemme`!");
                    $gembonus+=5;
                }
                debuglog("guadagna $gembonus gemme, $goldbonus oro e $expbonus exp uccidendo {$badguy[creaturename]} aiutando la Fanciulla");
                $session['user']['experience']+=$expbonus;
                $session['user']['gold']+=$goldbonus;
                $session['user']['gems']+=$gembonus;
                $battle=false;
                $badguy=array();
                $session['user']['badguy']="";
                }
                else{
                if ($defeat) {
                        $_GET['op']="";
                        $session['user']['quest']+=1;
                        output("`nSei stat".($session['user']['sex']?"a":"o")." sconfitt".($session['user']['sex']?"a":"o")." da `^".$badguy['creaturename'].".`n");
                        addnews("`%".$session['user']['name']."`5 è stat".($session['user']['sex']?"a":"o")." uccis".($session['user']['sex']?"a":"o")." dai `^".$badguy['creaturename']."`5 nel tentativo di aiutare `6La Fanciulla`0.");
                        debuglog("perde ".$session['user']['gold']." oro e 15% exp ucciso dai Due Mastini Rabbiosi nel tentativo di aiutare la fanciulla in difficoltà");
                        output("`6`bPerdi tutto l'oro che avevi con te!!!`n");
                        output("`4Perdi il 15% della tua esperienza!!!`b`n");
                        $session['user']['gold']=0;
                        $session['user']['experience']*=0.85;
                        $session['user']['hitpoints']=0;
                        $battle=false;
                        $badguy=array();
                        $session['user']['badguy']="";
                        addnav("Terra delle Ombre","shades.php");

                }
                else   {
                        fightnav(true,false);
                        }
                }
}
if ($_GET['op']=="vittoria") addnav("`6Torna dalla Fanciulla","fanciulla.php?op=prosegui");
}
else if ($_GET['op']=="prosegui") {
page_header("La Ricompensa");
output("`3Esausto per la battaglia sostenuta torni dalla fanciulla che ti accoglie con un caldo sorriso e dice: ");
output("\"`6Grazie nobile cavaliere, mi hai salvato la vita e ti sarò eternamente grata. ");
    switch (e_rand(1,2)){
    case 1:
    output("Sono certa che le nostre strade si incroceranno nuovamente.\" `3`n ");
    output("`nMentre si avvia tu la guardi incantato da tanta grazia, ma fatti pochi passi si volta, ti rivolge ");
    output("un ultimo fugace sguardo e con una voce carica di tristezza dice: \"`6");
        switch (e_rand(1,6)){
        case 1:
        output("So che nulla potrà ripagare il gesto che hai compiuto, ma prendi questa gemma, che possa servirti ");
        output("a conservare il mio ricordo.\"`3 `nStringi la gemma nella mano e inebriato dal profumo che essa emana ");
        output("ti incammini in direzione del villaggio.`n");
        debuglog("guadagna 1 gemma dalla fanciulla");
        $session['user']['gems']+=1;
        $session['user']['quest']+=1;
        addnav("T?`2Torna al Villaggio","village.php");
        break;
        case 2:
        output("So che nulla potrà ripagare il gesto che hai compiuto, ma prendi queste 2 gemme, che possano servirti ");
        output("a conservare il mio ricordo.\"`3 `nStringi le gemme nella mano e inebriato dal profumo che emanano ");
        output("ti incammini in direzione del villaggio.`n");
        debuglog("guadagna 2 gemme dalla fanciulla");
        $session['user']['gems']+=2;
        $session['user']['quest']+=1;
        addnav("T?`2Torna al Villaggio","village.php");
        break;
        case 3:
        output("So che nulla potrà ripagare il gesto che hai compiuto, ma prendi questi 1000 pezzi d'oro, che possano servirti ");
        output("a conservare il mio ricordo.\"`3 `nStringi le monete nella mano e inebriato dal profumo che emanano ");
        output("ti incammini in direzione del villaggio.`n");
        debuglog("guadagna 1000 oro dalla fanciulla");
        $session['user']['gold']+=1000;
        $session['user']['quest']+=1;
        addnav("T?`2Torna al Villaggio","village.php");
        break;
        case 4:
        output("So che nulla potrà ripagare il gesto che hai compiuto, ma prendi questi 2000 pezzi d'oro, che possano servirti ");
        output("a conservare il mio ricordo.\"`3 `nStringi le monete nella mano e inebriato dal profumo che emanano ");
        output("ti incammini in direzione del villaggio.`n");
        debuglog("guadagna 2000 oro dalla fanciulla");
        $session['user']['gold']+=2000;
        $session['user']['quest']+=1;
        addnav("T?`2Torna al Villaggio","village.php");
        break;
        case 5:
        output("Sei il guerriero più gentile che abbia mai conosciuto.\" `3E dette queste dolci parole ti da una fugace ");
        output("carezza sulla guancia. `n`b`2Ti senti più affascinante !!!`3`b`n");
        $session['user']['charm']+=3;
        debuglog("guadagna 3 fascino dalla fanciulla");
        $session['user']['quest']+=1;
        addnav("T?`2Torna al Villaggio","village.php");
        break;
        case 6:
        if ($session['user']['sex']==0){
            $spos="Violet";
            }else {$spos="Seth";}
        if ($session['user']['charisma']==4294967295){
            if ($session['user']['marriedto']==4294967295){
                if ($session['user']['sex']==0){
                $spos="tua moglie Violet";
                }else {$spos="tuo marito Seth";}
            }else {
            $result = db_query("SELECT name FROM accounts WHERE acctid='{$session[user][marriedto]}'") or die(db_error(LINK));
            $row = db_fetch_assoc($result) or die(db_error(LINK));
                if ($session['user']['sex']==0){
                $spos="tua moglie ".$row['name'];
                }else {$spos="tuo marito ".$row['name'];}
            }
            }else if ($session['user']['charisma']>0){
            if ($session['user']['marriedto']==4294967295){
                if ($session['user']['sex']==0){
                $spos="la tua fidanzata Violet";
                }else {$spos="il tuo fidanzato Seth";}
            }else {
            $result = db_query("SELECT name FROM accounts WHERE acctid='{$session[user][marriedto]}'") or die(db_error(LINK));
            $row = db_fetch_assoc($result) or die(db_error(LINK));
                if ($session['user']['sex']==0){
                    $spos="la tua fidanzata ".$row['name'];
                }else {
                    $spos="il tuo fidanzato ".$row['name'];
                }
            }
            }
        $perdfasc=e_rand(4,8);
        output("Sei il guerriero più gentile che abbia mai conosciuto.\" `3E dette queste dolci parole ti da un fugace ");
        output("bacio sulla guancia. Torni al villaggio con il cuore pieno di gioia ma, appena arrivi nella piazza del ");
        output("villaggio, trovi `2`b$spos`b`3 che ti attende, e che dopo aver notato le labbra stampate sulla tua guancia ti ");
        output("molla un ceffone che difficilmente scorderai !!! `n`4`bPerdi $perdfasc punti fascino !!!`b`n");
        $session['user']['charm']-=$perdfasc;
        debuglog("perde $perdfasc punti fascino dalla fanciulla");
        $session['user']['quest']+=1;
        addnav("T?`2Torna al Villaggio","village.php");
        break;
        }
    break;
    case 2:
    output("La strada che devo percorrere è ancora lunga ed impervia, ed un nobile guerriero come te al mio fianco ");
    output("mi sarebbe estremamente utile. Inoltre prima di arrivare al mio borgo c'è un fabbro che forgia oggetti magici come quelli ");
    output("del vostro mercante Brax. Potrei intercedere per te, e fartene regalare uno. Vuoi accompagnarmi sino alla mia dimora ?\" `3`n`n");
    output("La scorterai fino al suo villaggio ?");
    addnav("S?`#Si, la seguirei ovunque","fanciulla.php?op=scorta");
    addnav("N?`\$No, sono impegnato","fanciulla.php?op=abbandona");
    }
}
else if ($_GET['op']=="abbandona"){
page_header("L'abbandono");
output("`n`3 Seppur con la tristezza in fondo al tuo cuore sei costretto a declinare l'invito. Sai che la dolce fanciulla ");
output("saprà cavarsela da qui in avanti, l'ultima creatura malefica di questa zona di foresta è stata domata, e non");
output("incontrerà altri pericoli. Ti congedi con un ultimo rispettoso inchino e ti incammini verso il tuo villaggio.`n");
$session['user']['quest']+=1;
addnav("`@Torna al Villaggio","village.php");
}
else if ($_GET['op']=="scorta"){
page_header("La Scorta Armata");
output("`3Ti incammini al fianco della fanciulla. Il sentiero che lei sembra conoscere perfettamente, si snoda in un'incantevole ");
output("vallata. Ma ben presto la strada devia in direzione di un'impevia salita. Il peso dell'armatura si fa sentire e tu ");
output("arranchi con fatica, mentre la graziosa ragazza sembra non risentire della fatica.`n ");
$caso=e_rand(1,20);
    if ($caso == 20){
    output("La vista offuscata dallo sforzo non ti fa notare un sasso sporgente, inciampi e cadi rovinosamente  a terra ferendoti.`n");
    $perdhp=$session['user']['maxhitpoints']*(e_rand(5,10)/100);
    $session['user']['hitpoints']-=$perdhp;
    output("`4Perdi $perdhp HP !!`n");
    debuglog("perde $perdhp HP nel crepaccio con la fanciulla");
        if ($session['user']['hitpoints']<0){
        output("`5`bSEI MORTO !!!`b`n");
        debuglog("e muore cadendo nel crepaccio");
        $session['user']['quest']+=1;
        $session['user']['alive']=false;
        redirect("shade.php");
        }
    }
    output("`3`nAll'improvviso davanti a te si apre un profondi crepaccio nella montagna ed un ponte di corde che lo ");
    output("lo attraversa. L'idea di attraversarlo ti fa tremare, ma ancora di più la vista di un `5Nero Cavaliere`3 ");
    output("dall'altra parte del crepaccio. Cerchi di scrutare nei suoi occhi, ma con terrore ti accorgi che sotto ");
    output("l'elmo non riesci a scorgere un viso ma solo una nera nebbia ... Cosa vuoi fare ?`n");
    addnav("S?`#Scappa terrorizzato","fanciulla.php?op=terrore");
    addnav("A?`\$Affrontalo ... ","fanciulla.php?op=nerocavaliere");
}
else if($_GET['op']=="terrore"){
page_header("Il Dilemma");
output("`3Decidi che ne hai avuto abbastanza di avventure per oggi, che si arrangi la fanciulla. Non sei la sua guardia ");
output("del corpo, e in fin dei conti cosa si aspettava da te ? L'hai già salvata una volta, che se la sbrighi da sola ");
output("questa volta.`nInizi a fuggire precipitosamente verso il villaggio e nella fuga ti scivola di tasca");
    if ($session['user']['gems']>0){
    debuglog("perde 1 gemma scappando dal Nero Cavaliere");
    output(" `61 gemma `3!!!`n");
    }else {
    $oroperso=intval($session['user']['gold']/2);
    $session['user']['gold']-=$oroperso;
    debuglog("perde $oroperso scappando dal Nero Cavaliere");
    output("`6 $oroperso pezzi d'oro`3 !!!`n");
    }
$session['user']['quest']+=1;
addnav("T?`3Torna al Villaggio","village.php");
}
else if ($_GET['op']=="nerocavaliere"){
page_header("Il Nero Cavaliere");
output("`3Decidi di compiere un ultimo sforzo per aiutare la ragazza a raggiungere il suo villaggio. Attraversi il ponte ");
output("sospeso sul crepaccio e quando giungi dall'altra parte scopri la vera natura del `5Nero Cavaliere`3.`n`n ");
output("Altro non è che un fantoccio piazzato a guardia del ponte per spaventare i guerrieri meno coraggiosi. `nTi giri per scoprire che ");
output("la leggiadra fanciulla è già al tuo fianco, e guardandola con fare interrogativo lei ti dice: `n\"`6Questa è ");
output("stata solo l'ultima prova per provare il tuo coraggio. Sei degno di entrare nel nostro villaggio e poter ");
output("sfruttare le opportunità che i vari negozi che vi troverai ti offrono. Sappi sfruttare l'occasione che ti è stata ");
output("concessa, non avrai un'altra chance fino alla prossima uccisione del `2`bDrago Verde`b`6.`n");
$casoogg=e_rand(1,3);
$casoogg1=e_rand(1,4);
if ($casoogg>2){
    output("`n`3Mentre percorrete le poche miglia che ti separano dal borgo natale della fanciulla, vi imbattete ");
    output("nella forgia del fabbro. Egli ti accoglie calorosamente, come se fosse un tuo vecchio compagno d'armi, ");
    output("e dopo aver confabulato con la gentil donzella, dice: \"`5Lucrezia mi ha raccontato le tue gesta, il ");
    output("minimo che possa fare è ricompensarti con un oggetto uscito dalla mia fucina`3\" e scompare nel retro.`n");
    $dove=101;
    if ($casoogg1>3){$dove+=1;}
    $sql = "SELECT * FROM oggetti WHERE dove = '$dove' ORDER BY livello DESC,nome DESC";
    $result = db_query($sql) or die(db_error(LINK));
    if (db_num_rows($result) == 0) {
        //Caso in cui non ci sono oggetti nel contenitore richiesto
        output("`3Il fabbro ha fatto i conti senza l'oste, purtroppo non ha più neanche un oggetto.`n");
        output("Sei stato estremamente sfortunato `2".$session['user']['name']."`3, sarà per la prossima volta.");
    }else{
        $caso_ogg = e_rand(0,(db_num_rows($result)-1));
        $countrow = db_num_rows($result);
        for ($i=0; $i<$countrow; $i++){
        //for ($i = 0;$i < db_num_rows($result);$i++) {
        $row = db_fetch_assoc($result);
            if ($i == $caso_ogg) {
                output ("`3Poco dopo ricompare con in mano `6" . $row['nome'] . "`3 e te lo porge.`n");
                output ("\"`4Che magnifico oggetto!\"`3, pensi mentre il fabbro ti dice che ha questi bonus:`5 " . $row[descrizione] . ".`3`n`n");
                if ($session['user']['zaino'] == "0") {
                output ("Raccogli l'oggetto e lo metti nello zaino.`n`n");
                debuglog("riceve {$row[nome]} dal fabbro del borgo");
                $session['user']['zaino'] = $row['id_oggetti'];
                $oggetto_id = $row['id_oggetti'];
                $sqlu = "UPDATE oggetti SET dove=0 WHERE id_oggetti='$oggetto_id'";
                db_query($sqlu) or die(db_error(LINK));
            }else{
                debuglog("avrebbe potuto ricevere {$row[nome]} ma non aveva spazio nello zaino");
                output ("Sfortunatamente non hai posto nello zaino e lo devi lasciare quì.`n`n");
            }
            }
        }
    }
}else{
    output("`n`3Percorrete le poche miglia che ti separano dal borgo natale della fanciulla, e quando vi giungete ");
    output("la fanciulla ti mostra i pochi negozi che si sono lunga la via maestra, dandoti una pozione per alleviare ");
    output("le tue fatiche. La bevi e ");
    $caso=e_rand(1,10);
    if ($caso == 1){
        $perdhp=intval($session['user']['maxhitpoints']*(e_rand(30,50)/100));
        if ($perdhp>=$session['user']['hitpoints']) {$perdhp=$session['user']['hitpoints']-1;}
        $session['user']['hitpoints']-=$perdhp;
        output("ti accorgi che ha il gusto della liquirizia .... `#liquirizia ?? `bLIQUIRIZIA`b ???`n");
        output("`4Ma tu sei allergico alla `$`bLIQUIRIZIA`b !!!!`n`n");
        output("`3Senti le tue forze venire meno e quando finalmente la nebbia scompare dalla tua vista ti rendi conto ");
        output("che hai perso `6$perdhp HP`3 e ti ritrovi con soli `^{$session[user][hitpoints]} HP`3 !!!`n");
    }else{
        output("il piacevole gusto di liquirizia rinfranca il tuo corpo. `#I tuoi HP sono stati riportati al massimo !!!`n");
        if ($session['user']['hitpoints'] < $session['user']['maxhitpoints']) {
           $session['user']['hitpoints']=$session['user']['maxhitpoints'];
        }
    }
output("`n`3Vedi la fanciulla che si dirige verso la sua abitazione, e decidi di dedicare la tua attenzione ai pochi ");
output("negozi che vedi. Chissà se sarà vero che i prezzi sono così conveniente rispetto a quelli dei tuo villaggio ?`n");
}

$session['user']['quest']+=1;
addnav("B?`6Borgo della Fanciulla","villaggio.php");
}
page_footer();
?>