<?php
/**ss**********************ss***************
/ LoneStrider's Thief script. .  . LoneStrider's pals strike
/ version 1.55
/ 2.23.04  (7th revision) -scs-
 Additions for 1.55 (strider)
 - Fixed rewards for elves.
 - Adjusted the chances of events
 - Fixed the navigation so it won't confuse players.
 ver 1.5
 -Now adds a line in the news when activated -ss
 -Added some debugging logs
 -Added the Legendgard Race Array (see special note below)
 -Added Race Specific interaction (aka: Lonestrider honors friendly elves.)
/ Version History:
 Ver 1.1 by Strider (of Legendgard)
-Lonestrider does a random backstab even when user kills thieves. -ss
-lose gold as well as gems when you're beaten and a few spelling corrections -ss
 Ver .95 by MightyE (of Central)
 --fighting back, running, distracting and stabbing knives buff -me
 Ver .8 by JT (of Dragoncat)
 - Modified slightly for bug fixes and clarity (and effect) by JT
 - those bruises hurt
**ss**************************ss************/
// -Originally by: Strider
// -Contributors: MightyE, JT
// Feb 2004  - Legendgard Script Release
//if (!isset($session)) exit();
$costwin = (int)($session['user']['gems']*.05);
$costwin++;
$costrun = round($costwin/2,0);
$costlose = $costwin*2;
$costlose = min($session['user']['gems'],$costlose);
if ($session['user']['gems']>0){
    if ($_GET['op']=="" || $_GET['op']=="search"){
    //ss// Lonestrider is the Saint Rogue of Elfin Kin. . .
    if($session['user']['race']==2){
        output("`n`n`3Un vento gelido spira da Ovest. La tua acuta vista `6elfica`3 scruta i dintorni. Un ramoscello schiocca ed improvvisamente,
        molti elfi scuri ti circondano. Un elfo alto, ben vestito e con la pelle chiara, salta giù da un albero circondato da un'aura di nobiltà.
        Ti maledici per non essertene accorto in tempo, essendo costui `\$Lonestrider. `3 e la sua banda di ladroni.
        La tua mente scorre velocemente le opzioni disponibili prima che `\$Lonestrider `3alzi la sua mano in segno di amicizia.");
        output(" `n`n`\$\"Ti porgo i miei saluti mio caro elfo. Giornata stupenda per una passeggiata nella foresta per consolidare le basi della
        nostra fortuna, non è vero?\"`3 `nsenti le risate sguaiate di una dozzina di ladri nascosti, `n`\$\" Non essere così impaurito
        mi".($session['user']['sex']?"a nobile Signora":"o caro Signore").", non ho nessuna intenzione di derubarti. Cerco di proteggere gli `6Elfi`\$, quando posso.
        Beh, ammetto che a volte ci lasciamo trasportare in scorribande dove chiediamo ai malcapitati di contribuire ai nostri bisogni, ma onoro gli elementi
        e il sangue che scorre nelle mie vene. `nHo notato che avevi un'aria sperduta qualche istante fa e ho pensato che saremmo stati in grado di aiutare un compagno elfo.");
        output("`n`n`3Ancora scioccato per la velocità con cui i banditi ti hanno sopraffatto, pensi a cosa rispondere in silenzio.
        Sai che `\$Lonestrider `3vale un bel po' di pezzi d'oro, vivo o morto. . . se potrai catturarlo. Egli sta di fronte a te con i suoi occhi grigio-azzurri e stranamente,
        ti senti a tuo agio, come se tu fossi al sicuro per qualche momento in sua compagnia.
        Alcuni dei suoi ladroni iniziano a farsi impazienti mentre tu consideri le opzioni e decidi che devi dire o fare qualcosa.
        `n`n`7Qual'è la tua risposta?`n");
        $session['user']['specialmisc']="";
        $session['user']['specialinc']="thieves.php";
    //your options////////////////////////////////
        output("<a href=forest.php?op=creatures> \"Sto cercando delle creature.\"</a>`n", true);
        output("<a href=forest.php?op=money> \"Sono preoccupato per l'oro.\"</a>`n", true);
            if($session['user']['specialty']==3){
            output("<a href=forest.php?op=thiefskill> \"Spero di diventare un grande ladro come te un giorno!\"</a>`n", true);}
        output("<a href=forest.php?op=fine> \"Grazie Lonestrider, sto benissimo!\"</a>`n`n", true);
        output("<a href=forest.php?op=stand>\"Sono qui per collezionare la taglia sulla tua TESTA!\"</a>`n", true);
        ///hidden so the html tags will work//
            addnav("","forest.php?op=creatures");
            addnav("","forest.php?op=money");
                if($session['user']['specialty']==3){addnav("","forest.php?op=thiefskill");}
            addnav("","forest.php?op=fine");
            addnav("","forest.php?op=stand");
        /////////////////////////////////////////
        addnav("Rispondi al Ladro?");
        addnav("Creature","forest.php?op=creatures");
        addnav("Oro","forest.php?op=money");
            if($session['user']['specialty']==3){
            addnav("Abilità di Ladro","forest.php?op=thiefskill");}
        addnav("Niente","forest.php?op=fine");
        addnav("Uccidi il Ladro!");
        addnav("ATTACCA!","forest.php?op=stand");
    }else{
        //ss// Begin the script for everyone else, let's rob them if they're not an elf!
        output("`n`n`6Un vento gelido spira da Est. Prima che tu te ne renda conto, i ladroni ti hanno circondato.  Affannato,
        ti maledici per non essertene accorto in tempo.  Ti chiedono `%$costwin`6 gemme minacciandoti. Il loro viscido capo
        `\$Lonestrider `6ti urla sghignazzando che non te ne andrai senza pagare!  Ti rendi conto che ti sovrastano in numero
        e non è certamente un buon giorno per morire.");
        output("`n`n`7Puoi scegliere di soddisfare le loro richieste di `%$costwin`7 gemme, combatterli, usando le tue abilità
        da ".$races[$session['user']['race']]."`7 per tentare di difenderti, o cercare di scomparire nel fogliame che ti circonda
         (gettando `%$costrun`7 ".($costrun>1?"gemme":"gemma")." per distrarli).  Sai che se non gli dai ciò che chiedono e
        fallisci, molto probabilmente il prezzo che pagherai sarà più alto di quello attuale.");
        $session['user']['specialmisc']="";
        $session['user']['specialinc']="thieves.php";
        addnav("Dagli $costwin gemme","forest.php?op=give");
        addnav("Scappa via!","forest.php?op=runawaylikealittlesissybaby");
        addnav("Combattili!","forest.php?op=stand");
        }
    }elseif ($_GET['op']=="thiefskill"){
        if ($session['user']['gems']>0){
        output("`n`n`6Ansiosamente racconti a `\$Lonestrider`6 una storiella delle tue avventure e menzioni il fatto che anche tu sei un elfo ladro,
        imitando le sue gesta. Un sorriso malizioso appare sulle sue labbra ed inizia a ridere. Senti una leggera brezza sul tuo collo e ti giri
        per vedere da cosa è causata. La cosa successiva che apprendi, e che `\$Lonestrider`6 sta ondeggiando il sacchetto delle tue gemme nella sua mano
        con uno sguardo malizioso. Ridi per un attimo, quindi chiedi che ti venga restituito il borsellino. Te lo rida, ma una gemma rimane tra le sue dita.");
        output(" `n`n\"`\$Bene mi".($session['user']['sex']?"a cara Signora":"o nobile Signore").", terrò questa gemma e ti insegnerò un piccolo trucco in cambio.`6\"");
        output("`n`6Tu ed i ladroni ascoltate attentamente mentre racconta una meravigliosa storia di furti nei confronti dei nani e delle loro gemme.
        Quando ha terminato, ti senti considerevolmente più saggio.");
        $session['user']['gems']--;
        increment_specialty();
        debuglog("paga una gemma a Lonestrider per abilità di ladro.");
        }else{
        output("`n`n`6Ansiosamente racconti a `\$Lonestrider`6 una storiella delle tue avventure e menzioni il fatto che anche tu sei un elfo ladro,
        imitando le sue gesta. Un sorriso malizioso appare sulle sue labbra ed inizia a ridere.");
        output(" `n`n\"`\$Bene mi".($session['user']['sex']?"a cara Signora":"o nobile Signore").", continua ad esercitarti e buona fortuna. Un giorno forse potrai
        insegnare a questi mascalzoni un paio di trucchi. Forse ci incontreremo ancora quando sarai più ricco e per allora ti insegnerò qualche trucchetto.`6\"");
        }
        $session['user']['specialinc']="";
    }elseif ($_GET['op']=="money"){
        if ($session['user']['gems']>10){
        output("`n`n`6Depresso, dici a `\$Lonestrider`6 che stai cercando di ottenere più pezzi d'oro. Sembra capire il tuo problema e annuisce con il capo.");
        output(" `n`n`\$\"Bene mi".($session['user']['sex']?"a cara Signora":"o nobile Signore").", forse questo ti potrà aiutare. L'ho, umm. . . trovato nel villaggio.
        Penso che qualcuno non lo volesse più. Ora è tuo.\"");
        $money = e_rand(25, 2000);
        $session['user']['gold']+=$money;
        output("`n`6Ti lancia una piccola borsa di cuoio. Resti piacevolmente sorpreso nel trovarci `%$money pezzi d'oro`6!");
        debuglog("incontra Lonestrider e guadagna $money oro per essere un elfo.");
        }else{
        output("`n`n`6Depresso, dici a `\$Lonestrider`6 che stai cercando di ottenere più pezzi d'oro. Sembra capire il tuo problema e annuisce con il capo.");
        output(" `n`n`\$\"Bene mi".($session['user']['sex']?"a cara Signora":"o nobile Signore").", forse questo ti potrà aiutare. Non preoccuparti, è stata una buona giornata
        nel depredare i nani oggi.\"");
        $gems = e_rand(2, 3);
        $session['user']['gems']+=$gems;
        output("`n`6Ti lancia una piccola borsa di cuoio. Resti piacevolmente sorpreso nel trovarci `%$gems gemme `6!");
        debuglog("incontra Lonestrider e guadagna $gems gemme per essere un elfo.");
        }
        $session['user']['specialinc']="";
    }elseif ($_GET['op']=="creatures"){
        output("`n`n`6Dopo una giornata di combattimenti, dici a `\$Lonestrider`6 che stai cercando altre creature da uccidere. Egli fa spallucce e non parla, ma il sorriso
        beffardo non abbandona il suo fiero viso. Ti offre una fiasca di acqua prima di lasciarti alle tue ricerche.");
        output("`n`n`^Bevi l'acqua e scopri che i tuoi Hit Points sono stati riportati al massimo!`n");
        if ($session['user']['hitpoint'] < $session['user']['maxhitpoints'])
            $session['user']['hitpoints']=$session['user']['maxhitpoints'];
        $session['user']['specialinc']="";
    }elseif ($_GET['op']=="fine"){
        output("`n`n`6Sei un po' più rilassato adesso nei confronti di questo infame ladrone, ma dici a `\$Lonestrider`6 che sei a posto e velocemente ti allontani. Egli non
        dice una parola, ma il suo sorriso sardonico non abbandona mai il suo viso mentre senti una dozzina di elfi nascosti cantare cantare una canzone nella foresta dietro di te.");
        output("`n`n`6Qualcosa in questo incontro ti fa sentire più riposato e a tuo agio nella foresta.`n");
        $session['bufflist'][401] = array("name"=>"`#Canzone della Radura","rounds"=>4,"wearoff"=>"Il Tocco dell'Elfo scompare...","atkmod"=>2,"roundmsg"=>"Un canzone elfica rimane con te e ti da coraggio.","activate"=>"offense");
        $session['user']['specialinc']="";
    }elseif ($_GET['op']=="give"){
        output("`n`n`6Ti rendi conto che le forze di `\$Lonestrider`6 sono ben superiori alle tue, e temendo per la tua vita, decidi di dar loro le `%$costwin`6 gemme che ti hanno chiesto.");
        $session['user']['gems']-=$costwin;
        debuglog("abbandona $costwin gemme per pagare i ladroni di Lonestrider");
        addnews("`3".$session['user']['name']."`6 ha incontrato `4Lonestrider `6e la sua banda di avidi compari! `nÈ stat".($session['user']['sex']?"a":"o")." sentit".($session['user']['sex']?"a":"o")." borbottare qualcosa al riguardo del fatto che ha dovuto pagarli per non si sa che cosa.");
        $session['user']['specialinc']="";
    }elseif ($_GET['op']=="runawaylikealittlesissybaby"){
        output("`n`n`6Ti rendi conto che le forze di `\$Lonestrider`6 sono ben superiori alle tue, e temendo per la tua vita, getti `%$costrun`6 gemme nel tentativo di distrarli.");
        $session['user']['gems']-=$costrun;
        if (e_rand(1,2)==1){
            output("`n`nI ladroni non si sono lasciati distrarre così facilmente, e ti acchiappano, forzandoti allo scontro.");
            output("`n`n`\$Lonestrider`6 si ferma a raccogliere ".($costrun>1?"le gemme":"la gemma")." prima di raggiungere la sua chiassosa banda, pronto a battersi.");
            debuglog("perde $costrun gemme nel tentativo non riuscito di scappare dai ladroni di LoneStrider");
            $session['user']['specialinc']="thieves.php";
            $session['user']['specialmisc']="triedtorun";
            addnav("Combattili!","forest.php?op=stand");
        }else{
            $session['user']['specialinc']="";
            output("`n`nDistrai facilmente i ladroni e riesci a metterti in salvo!");
            debuglog("perde $costrun gemme ma riesce a scampare ai ladroni di LoneStrider");
            addnews("`3".$session['user']['name']."`6 è riuscit".($session['user']['sex']?"a":"o")." a sfuggire a `4Lonestrider `6ed i suoi briganti!");
        }
    }elseif ($_GET['op']=="stand"){
    $session['user']['clean'] += 1;
    $dkb = round($session['user']['dragonkills']*.1);
        $badguy = array(
            "creaturename"=>"`\$I Ladroni di LoneStrider`0",
            "creaturelevel"=>$session['user']['level']+1,
            "creatureweapon"=>"Molti Coltelli",
            "creatureattack"=>$session['user']['attack'],
            "creaturedefense"=>$session['user']['defence'],
            "creaturehealth"=>round($session['user']['maxhitpoints']*0.8,0),
            "diddamage"=>0);
            $session['bufflist']['thieves'] = array(
                "startmsg"=>"`n`^Sei circondato da ladroni armati di coltelli!`n`n",
                "name"=>"`%Pugnali",
                "rounds"=>15,
                "wearoff"=>"I ladroni sono esausti.",
                "minioncount"=>$session['user']['level'],
                "mingoodguydamage"=>0,
                "maxgoodguydamage"=>1+$dkb,
                "effectmsg"=>"Un ladrone ti pugnala per {damage} punti danno.",
                "effectnodmgmsg"=>"Un ladrone cerca di pugnalarti ma ti MANCA.",
                "effectfailmsg"=>"La tua arma stride come se tu non provocassi nessun danno ai tuoi avversari.",
                "activate"=>"roundstart",
                );
        //TODO: Add negative buff hurting the player for many rounds.
        $session['user']['badguy']=createstring($badguy);
        $session['user']['specialinc']="thieves.php";
        $_GET['op']="fight";
    }
    if ($_GET['op']=="run"){
        output("Ci sono troppi ladroni che ti bloccano ora, non hai nessuna possibilità di scappare!");
        $_GET['op']="fight";
    }
    if ($_GET['op']=="fight"){
        $battle=true;
    }
    if ($battle){
      include("battle.php");
        $session['user']['specialinc']="thieves.php";
        if ($victory){
            $badguy=array();
            $session['user']['badguy']="";
            //ss//adding a little more nastiness to this. That way, even the Gods have a 1 in 3 chance to lose no matter what.//s///
            if (e_rand(1,5) == 1) {
            output("`n`6Molti dei ladroni di `\$Lonestrider`6 giacono morti ai tuoi piedi Ma il leader elfico è scomparso. Rabbiosamente scruti l'area, cercando ");
            output("lo stesso `\$Lonestrider`6. La foresta è stranamente in silenzio. Improvvisamente, senti qualcosa di freddo ed affilato sul tuo collo. ");
            output("La velocità degli elfi è spesso sorprendente, anche se il silenzio indicava che fosse oramai lontano.");
            output(" `n`n\"`\$Ben fatto mio caro, ma i nostri affari non si sono ancora conclusi. Spero scuserai il coltello puntato alla gola,");
            output("solo una formalità, veramente. Ora, prima che me ne vada, Prenderò alcune di quelle gemme.`6\"`n`n");
            output("Stai per dire qualcosa di eccezionalmente intelligente quando un colpo sulla nuca ti spedisce nella spirale dell'incoscienza.");
            output("Ti risvegli con un terribile mal di testa e scopri che ");
                $costlose2 = (int)($costlose*1.5);
                if ($costlose2 > $session['user']['gems']) {
                   $costlose2 = $session['user']['gems'];
                }
                $session['user']['gems']-=$costlose2;
                $goldloss = $session['user']['gold'];
                $session['user']['gold'] = 0;
                output("`\$Lonestrider`6 ti ha rubato `%$costlose2 gemme `6e `%$goldloss pezzi d'oro`6.");
                debuglog("perde $costlose2 gemme e tutto l'oro quando Lonestrider ha fatto un attacco speciale.");
                addnews("`%".$session['user']['name']."`2 ha sfidato `4Lonestrider `2e la sua banda di ladroni e si è battut".($session['user']['sex']?"a":"o")." generosamente! Sfortunatamente, `\$Lonestrider `2ha sferrato il colpo finale.`n$taunt");
            }else{
            $bounty = round(getsetting("bountymax", 400) * $session['user']['level'] / 6, 0); //one-sixth of max bounty at this level, roughly 67/level
            output("`n`6Molti dei ladroni di `\$Lonestrider`6 giacono morti ai tuoi piedi. Lo stesso `\$Lonestrider`6 è scomparso ad un certo punto ");
            output("della battaglia, quando le cose si sono messe male per i suoi uomini, e così non avrai la possibilità di riportare la sua testa per ");
            output("la taglia da Dag.  Comunque alcuni dei ladroni a terra hanno taglie sulla loro testa, così sei in grado di ricevere ");
            output("`^$bounty`6 pezzi d'oro per la loro uccisione. Mentre perquisisci le tasche dei cadaveri dei ladroni, scopri `5una pozione ");
            output("rigenerante`6, che trangugi velocemente.");
            addnews("`^".$session['user']['name']."`3 ha sconfitto `4Lonestrider `3e la sua banda di ladroni! Ha ucciso molti dei briganti ma `4Lonestrider `3è riuscito a fuggire.");
            }
            if ($session['user']['specialmisc']=="triedtorun")
                output("`n`nNon trovi nessuna delle gemme usate per distrarre i ladroni sui loro corpi.  `\$Lonestrider`6 deve averle prese quando è fuggito.");
            $session['user']['gold']+=$bounty;
            debuglog("trova $bounty dopo aver ucciso i ladroni di LoneStrider");
            if ($session['user']['hitpoint'] < $session['user']['maxhitpoints'])
                $session['user']['hitpoints']=$session['user']['maxhitpoints'];
            $session['user']['specialinc']="";
            unset($session['bufflist']['thieves']);
        }elseif ($defeat){
            unset($session['bufflist']['thieves']);
            $badguy=array();
            $session['user']['badguy']="";
            // vv This should never evaluate to true, because of the test at line 11.
            if ($costlose > $session['user']['gems'])
                $costlose = $session['user']['gems'];
            $session['user']['gems']-=$costlose;
            $goldloss = $session['user']['gold'];
            $session['user']['gold']-=$goldloss;
            debuglog("perde $costlose gemme e $goldloss oro quando Lonestrider lo hanno reso incoscente.");
            addnews("`%".$session['user']['name']."`6  ha sfidato `4Lonestrider `6e la sua banda di ladroni! Non c'è stata storia ed è stat".($session['user']['sex']?"a":"o")." abbandonat".($session['user']['sex']?"a":"o")." nella foresta seriamente ferit".($session['user']['sex']?"a":"o")."!`n$taunt");
            if ($session['user']['gems'] > 0) {
                output("`n`6La banda di `\$Lonestrider`6 ti ha reso incosciente. Ti sottraggono `%$goldloss `6pezzi d'oro e `%$costlose`6 gemme che trovano");
                output(" in una delle tue tasche.  Fortunatamente per te, non notano l'altra tasca dove hai riposto il resto delle tue gemme.");
            }else{
                output("`n`6La banda di `\$Lonestrider`6 ti ha reso incosciente. Ti sottraggono `%$goldloss `6pezzi d'oro e `%$costlose`6 gemme che trovano nelle tue tasche.");
            }
            $session['user']['turns']--;
            output("`n`nGiaci, gemendo nel fango, aggrappato ad un filo di vita, mentre prelati e politici passano di fianco a te, lasciandoti morire. Ciò fino ");
            output("a che un abitante dell'odiato villaggio di Eythgim ti nota e viene in tuo aiuto.  Avvicina una pozione guaritrice alle tue labbra, e ti porta ");
            output("alla locanda Testa di Cinghiale nel tuo villaggio.  Giunti la, affitta una stanza da Cedrik, e paga per le tue cure, ripartendo prima che");
            output(" tu abbia ripreso competamente conoscienza, non dandoti la possibilità di ringraziarlo.");
            output("`n`n`^Perdi un combattimento mentre sei rimasto incosciente.");
            $session['user']['specialinc']="";
            $session['user']['boughtroomtoday']=1;
            if ($session['user']['hitpoints'] < $session['user']['maxhitpoints'])
                $session['user']['hitpoints']=$session['user']['maxhitpoints'];
            addnav("Svegliati!","inn.php?op=strolldown");
        }else{
          fightnav(true,true);
        }
    }
}else{
    if($session['user']['race']==2){
    output("`n`n`6Un vento gelido spira da Ovest. Prima che tu te ne renda conto, i ladroni ti hanno circondato. Affannato, ti maledici per non essertene accorto in tempo.
    Iniziano a chiederti le gemme minacciandoti.`n`n`^Cerchi di spiegar loro che non ne hai, così ti perquisiscono. Non trovandone,
    ti conducono dal loro leader `\$Lonestrider `6per decidere cosa fare di te. `n`n`\$Lonestrider `6fa spallucce non appena ti portano
    al suo cospetto. Guarda di traverso i briganti e scuote la testa. `n`\$\"Sono dispiaciuto per il trattamento, i miei commilitoni devono averti
    scambiato per qualcun altro. Mi hanno riferito che stai viaggiando senza neanche una gemma. Nessun elfo dovrebbe stare senza gemme.
    Consideralo un omaggio.\"`n`^*Guadagni 1 Gemma*`0");
    addnav("`@Torna alla Foresta","forest.php");
    $session['user']['gems'] ++;
    $session['user']['specialinc']="";
    debuglog("incontra Lonestrider e guadagna una gemma per essere un elfo.");
    }else{
    output("`n`n`6Un vento gelido spira da Ovest. Prima che tu te ne renda conto, i ladroni ti hanno circondato.
    Affannato, ti maledici per non essertene accorto in tempo. Iniziano a chiederti le gemme minacciandoti.`n`n
    `^Cerchi di spiegar loro che non ne hai, così ti perquisiscono. Non trovandone, ti malmenano per un po'
    prima di abbandonarti nella foresta da solo.`0");
    addnav("`@Torna alla Foresta","forest.php");
    $session['user']['charm'] -= 1;
    $session['user']['specialinc']="";
    $session['user']['hitpoints'] -= 5;
    if ($session['user']['hitpoints']<1) $session['user']['hitpoints']=1;
    debuglog("Incontra Lonestrider e viene sbattuto un po'!");
    addnews("`3".$session['user']['name']."`6 ha incontrato `4Lonestrider `6e la sua corte oscura! Sembra un po' scoss".($session['user']['sex']?"a":"o").", ma probabilmente non aveva nulla che volessero.");
 }
}
?>