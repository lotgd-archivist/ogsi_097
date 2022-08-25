<?php
/* The House of Sin
** By Phudgee - phudgee@oldschoolpunk.com
** Written Aug. 10, 2004 in about 3 hours.
** Website: http://www.worldwidepunks.com
** Green Dragon Game: http://www.worldwidepunks.com/logd
**
**
** Installation:
** Create a column: bordello => tinyint(4) not null
** In Table: accounts
**
** in newday.php:
**
**  find:  $session['user'][seenmaster]=0;
**
** after, add: $session['user']['bordello']=0;
**
** Modify the prices for the different services below, along with the name of the madam.
** Set your return village by setting the $returnvillage variable on line 29
**
** I pounded this together in about 3 hours. Let me know if there are any bugs!
**
*/

require_once("common.php");
require_once("common2.php");
addcommentary();
checkday();
$madam = "Butterfly";
$returnvillage = "castelexcal.php";
output("`c<font size='+1'>`^CASA DEL PIACERE</font>`c`n`n",true);
//rawoutput("<IMG SRC=\"images/hooker.png\"><br><br>\n");
page_header("Casa del Piacere di Madame ".$madam);
$session['user']['locazione'] = 112;
output("<span style='color: #9900FF'>",true);
output("`c`bLa Casa del Piacere`b`c");
$livello = $session['user']['level'];
$costone = 100 * max($livello, 7);
$costtwo = 130 * max($livello, 6);
$costthree = 170 * max($livello, 5);
$costfour = 200 * max($livello, 5);
$costfive = 250 * max($livello, 5);

if ($_GET['op']==""){
    output("`c`b`%Madame ".$madam." ti accoglie alla porta.`0`b`c");
    if (get_pref_pond("bordello",0) == 1)    {
        output("`n`n`^La casa è chiusa per il resto della giornata!!`n");
        output("`^Torna a farci visita domani!!`n`n");
        addnav("Torna al Castello",$returnvillage);
        page_footer ();
    }
    if ($session['user']['gold'] < $costone)    {
        output("`n`n`^'Ma cosa credevi!?!?' Esclama Madame ".$madam."!`n");
        output("`^'".($session['user']['sex']?"`5I miei ragazzi`^ ":"`5Le mie ragazze`^ ")." non sono ne a buon ");
        output("mercato, ne gratis! Vattene!!!'`n`n");
    } else {
    output("`n`nMadame ".$madam." ti mostra ".($session['user']['sex']?"`^alcuni dei suoi ragazzi`0 ":"`5alcune delle sue ragazze`0 "));
    output("per il tuo intrattenimento:`n`n");
    }
    if ($session['user']['gold'] >= $costone)    {
        addnav(($session['user']['sex']?"`^Tor`0 ":"`5Tiri`0 ")." (".$costone." oro)","casachiusa.php?op=one");
    }
    if ($session['user']['gold'] >= $costtwo)    {
    addnav(($session['user']['sex']?"`^Sandar`0 ":"`5Sephya`0 ")." (".$costtwo." oro)","casachiusa.php?op=two");
    }
    if ($session['user']['gold'] >= $costthree)    {
    addnav(($session['user']['sex']?"`^Derris`0 ":"`5Glynna`0 ")." (".$costthree." oro)","casachiusa.php?op=three");
    }
    if ($session['user']['gold'] >= $costfour)    {
    addnav(($session['user']['sex']?"`^Kierst`0 ":"`5Kyale`0 ")." (".$costfour." oro)","casachiusa.php?op=four");
    }
    if ($session['user']['gold'] >= $costfive)    {
    addnav(($session['user']['sex']?"`^Travys`0 ":"`5Mora`0 ")." (".$costfive." oro)","casachiusa.php?op=five");
    }
}else if ($_GET['op']=="one"){
    $session['user']['gold']-=$costone;
    set_pref_pond("bordello",1);
    output("`n`%Allunghi a Madame ".$madam." i tuoi `6".$costone."`0 pezzi d'oro, e ti avvii al piano superiore con ");
    output(($session['user']['sex']?"`^Tor`0 ":"`5Tiri`0 ").".`n`n");
    if (e_rand(0,100)<50){
        output("`n`%È evidente che ".($session['user']['sex']?"`^egli`0 ":"`5ella`0 ")." ha avuto una brutta ");
        output("giornata, e non ha nessuna intenzione di intrattenerti e darti piacere.`n");
        output(($session['user']['sex']?"`^È frustrato`0 ":"`5È frustrata`0 ")." e ti dice di andartene.`n`n");
        output("Torni al piano inferiore e chiedi indietro il tuo oro a Madame ".$madam."!`n");
        output("Lei ti risponde che è sul tavolo alle tue spalle... ma mentre ti giri per prenderlo,");
        output("ti sferra un colpo sulla testa con una pala.`n`n");
        output("Ti risvegli più tardi nel vicolo con un bel bernoccolo sulla nuca.`n`n");
        switch (e_rand(1,3)){
            case 1:
                output("`n`b`^(Sei lo scemo del villaggio! Perdi due (2) punti fascino)`n");
                //addnews("`%".$session['user']['name']."`@ è stato colpito e abbandonato in un vicolo da `%Madame ".$madam."`@");
                $session['user']['charm']-=2;
                break;
            case 2:
                output("`n`b`^(Hai perso molti dei tuoi HitPoints)`n");
                //addnews("`%".$session['user']['name']."`@ è stato colpito e abbandonato in un vicolo da `%Madame ".$madam."`@");
                $session['user']['hitpoints']-=round($session['user']['hitpoints']*.5);
                break;
            case 3:
                output("`n`b`^(Ti rendi conto di aver dormito per un bel po' nel vicolo! perdi 2 turni!)`n");
                //addnews("`%".$session['user']['name']."`@ è stato colpito e abbandonato in un vicolo da `%Madame ".$madam."`@");
                $session['user']['turns']-=2;
        }
    } else {
        output("`n`%Questo è il miglior denaro che tu abbia mai speso!!`n");
        output(($session['user']['sex']?"`^Tor`0 ":"`5Tiri`0 ")." conosce alla perfezione cosa ti fa impazzire.`n`n");
        output(($session['user']['sex']?"`^Lui`0 ":"`5Lei`0 ")." fa cosa che non avevi mai neanche immaginato!!`n");
        output("Dopo alcuni minuti di estasi, abbandoni l'edificio con il più grande sorriso che tu ");
        output("abbia mai avuto negli ultimi anni!!`n`n");
        switch (e_rand(1,3)){
            case 1:
                output("`n`b`^(hai un aspetto decisamente più \"Luminoso\"! Guadagni un (1) punto fascino)`n");
                //addnews("`%".$session['user']['name']."`@ abbandona la Casa del Piacere con il sorriso sulle labbra!!`@");
                $session['user']['charm']+=1;
                break;
            case 2:
                output("`n`b`^(Ti senti come se fossi in grado di conquistare il mondo! Guadagni due (2) turni)`n");
                //addnews("`%".$session['user']['name']."`@ abbandona la Casa del Piacere con il sorriso sulle labbra!!`@");
                $session['user']['turns']+=2;
                break;
            case 3:
                output("`n`b`^(Ti senti completamente ristorato! Sei stato completamente guarito!)`n");
                //addnews("`%".$session['user']['name']."`@ abbandona la Casa del Piacere con il sorriso sulle labbra!!`@");
                $session['user']['hitpoints']=$session['user']['maxhitpoints'];
        }
    }
}else if ($_GET['op']=="two"){
    set_pref_pond("bordello",1);
    $session['user']['gold']-=$costtwo;
    output("`n`%Allunghi a Madame ".$madam." i tuoi `6".$costtwo."`0 pezzi d'oro, e ti avvii al piano superiore con ");
    output(($session['user']['sex']?"`^Sandar`0 ":"`5Sephya`0 ").".`n`n");
    if (e_rand(0,100)<45){
        output("`n`%".($session['user']['sex']?"`^Lui`0 ":"`5Lei`0 ")." sa perfettamente come dare piacere ad ".($session['user']['sex']?"`^una donna`0 ":"`5un uomo`0 ")." ");
        output("e fa cose meravigliose che fanno impazzire per un buon quarto d'ora.....`n`n");
        output("Completamente soddisfatto, te ne vai con un sorriso!`n");
        output("Tre giorni più tardi, non sorridi più così tanto....`n`n");
        switch (e_rand(1,3)){
            case 1:
                output("`n`b`^(Hai contratto una malattia venerea!!)`n");
                $buff = array("name"=>"`4Malattia Venerea`0",
                      "rounds"=>60,
                      "wearoff"=>"`5`bLa tua malattia è guarita!`b`0",
                      "atkmod"=>.95,
                      "roundmsg"=>"La malattia riduce le tue capacità di combattimento!",
                      "activate"=>"offense"
                );
                $session['bufflist']['bordello']=$buff;
                //addnews("`%".$session['user']['name']."`@ ha contratto una malattia venerea con "
                //.($session['user']['sex']?"`^un gigolò`0 ":"`5una prostituta`0 ")."!`@");
                break;
            case 2:
                output("`n`b`^(Hai contratto una malattia venerea!!)`n");
                $buff = array("name"=>"`4Malattia Venerea`0",
                      "rounds"=>60,
                      "wearoff"=>"`5`bLa tua malattia è guarita!`b`0",
                      "defmod"=>.95,
                      "roundmsg"=>"La malattia riduce le tue capacità di combattimento!",
                      "activate"=>"defense"
                );
                $session['bufflist']['bordello']=$buff;
                //addnews("`%".$session['user']['name']."`@ ha contratto una malattia venerea con "
                //.($session['user']['sex']?"`^un gigolò`0 ":"`5una prostituta`0 ")."!`@");
                break;
            case 3:
                output("`n`b`^(Hai contratto una malattia venerea!!)`n");
                $buff = array("name"=>"`4Malattia Venerea`0",
                      "rounds"=>60,
                      "wearoff"=>"`5`bLa tua malattia è guarita!`b`0",
                      "atkmod"=>.95,
                      "defmod"=>.95,
                      "roundmsg"=>"La malattia riduce le tue capacità di combattimento!",
                      "activate"=>"offense"
                );
                $session['bufflist']['bordello']=$buff;
                //addnews("`%".$session['user']['name']."`@ ha contratto una malattia venerea con "
                //.($session['user']['sex']?"`^un gigolò`0 ":"`5una prostituta`0 ")."!`@");
        }
    } else {
        output("`n`%Questo è il miglior denaro che tu abbia mai speso!!`n");
        output(($session['user']['sex']?"`^Sandar`0 ":"`5Sephya`0 ")." conosce alla perfezione come darti piacere.`n`n");
        output(($session['user']['sex']?"`^Lui`0 ":"`5Lei`0 ")." fa cose che non avevi mai immaginato!!`n");
        output("Dopo momenti d'estasi, abbandoni l'edificio con rinnovato vigore!!`n`n");
        switch (e_rand(1,3)){
            case 1:
                output("`n`b`^(Il rinnovato vigore aggiunge un bonus d'attacco e 10 HitPoints!)`n");
                //addnews("`%".$session['user']['name']."`@ lascia la Casa di Piacere con una freccia in più nel suo arco!!`@");
                $buff = array("name"=>"`!Appagamento`0",
                      "rounds"=>60,
                      "wearoff"=>"`5`bNon sei più appagato!`b`0",
                      "atkmod"=>1.05,
                      "roundmsg"=>"I tuoi recenti piaceri aumentano le tue capacità di combattimento!",
                      "activate"=>"offense"
                );
                $session['bufflist']['bordello']=$buff;
                $session['user']['hitpoints'] += 10;
                break;
            case 2:
                output("`n`b`^(Il rinnovato vigore aggiunge un bonus di difesa e 2 turni!)`n");
                //addnews("`%".$session['user']['name']."`@ lascia la Casa di Piacere con una freccia in più nel suo arco!!`@");
                $buff = array("name"=>"`!Appagamento`0",
                      "rounds"=>60,
                      "wearoff"=>"`5`bNon sei più appagato!`b`0",
                      "defmod"=>1.05,
                      "roundmsg"=>"I tuoi recenti piaceri aumentano le tue capacità di combattimento!",
                      "activate"=>"defense"
                );
                $session['bufflist']['bordello']=$buff;
                $session['user']['turns'] += 2;
                break;
            case 3:
                output("`n`b`^(Il rinnovato vigore aggiunge un bonus di attacco e difesa, 10 HitPoints, e un turno!)`n");
                //addnews("`%".$session['user']['name']."`@ lascia la Casa di Piacere con una freccia in più nel suo arco!!`@");
                $buff = array("name"=>"`!Appagamento`0",
                      "rounds"=>60,
                      "wearoff"=>"`5`bNon sei più appagato!`b`0",
                      "atkmod"=>1.05,
                      "defmod"=>1.05,
                      "roundmsg"=>"I tuoi recenti piaceri aumentano le tue capacità di combattimento!",
                      "activate"=>"offense"
                );
                $session['bufflist']['bordello']=$buff;
                $session['user']['hitpoints'] += 10;
                $session['user']['turns'] += 1;
        }
    }
} else if ($_GET['op']=="three"){
    set_pref_pond("bordello",1);
    $session['user']['gold']-=$costthree;
    output("`n`%Allunghi a Madame ".$madam." i tuoi `6".$costthree."`0 pezzi d'oro, e ti avvii al piano superiore con ");
    output(($session['user']['sex']?"`^Derris`0 ":"`5Glynna`0 ").".`n`n");
    if (e_rand(0,100)<40){
        output("`n`%".($session['user']['sex']?"`^Lui`0 ":"`5Lei`0 ")." non ha evidentemente nessun indizio di come ");
        output("procurare piacere ad ".($session['user']['sex']?"`^una donna`0 ":"`5un uomo`0 ")." e tenta cose assurde!!`n`n");
        output("Ti allontani contrariato per lamentarti con Madam ".$madam."!`nMa ");
        output(($session['user']['sex']?"`^Derris`0 ":"`5Glynna`0 ")." ti colpisce sulla testa...`n`n");
        output("Ti risvegli in un vicolo, con il borsellino più leggero...`n");
        switch (e_rand(1,3)){
            case 1:
                output("`n`b`^(Sei stato derubato!! La notizia del tuo imbarazzo si diffonde rapidamente. Non sei più in grado di difenderti molto bene!)`n");
                $buff = array("name"=>"`4Umiliazione`0",
                      "rounds"=>60,
                      "wearoff"=>"`5`bRiconquisti il tuo orgoglio!`b`0",
                      "defmod"=>.95,
                      "roundmsg"=>"Sei troppo umiliato per difenderti al meglio!",
                      "activate"=>"defense"
                );
                $session['bufflist']['bordello']=$buff;
                $session['user']['gold'] = round($session['user']['gold'] * .5);
                //addnews("`%".$session['user']['name']."`@ è stato derubato da "
                //.($session['user']['sex']?"`^un gigolò`0 ":"`5una prostituta`0 ")."!`@");
                break;
            case 2:
                output("`n`b`^(Sei stato derubato!! La notizia del tuo imbarazzo si diffonde rapidamente. Non sei più in grado di difenderti molto bene!)`n");
                $buff = array("name"=>"`4Umiliazione`0",
                      "rounds"=>60,
                      "wearoff"=>"`5`bRiconquisti il tuo orgoglio!`b`0",
                      "atkmod"=>.95,
                      "roundmsg"=>"Sei troppo umiliato per attaccare al meglio!",
                      "activate"=>"offense"
                );
                $session['bufflist']['bordello']=$buff;
                $session['user']['gold'] = round($session['user']['gold'] * .5);
                //addnews("`%".$session['user']['name']."`@ è stato derubato da "
                //.($session['user']['sex']?"`^un gigolò`0 ":"`5una prostituta`0 ")."!`@");
                break;
            case 3:
                output("`n`b`^(Sei stato derubato!! La notizia del tuo imbarazzo si diffonde rapidamente. Non sei più in grado di difenderti molto bene!)`n");
                $buff = array("name"=>"`4Umiliazione`0",
                      "rounds"=>60,
                      "wearoff"=>"`5`bRiconquisti il tuo orgoglio!`b`0",
                      "defmod"=>.95,
                      "atkmod"=>.95,
                      "roundmsg"=>"Sei troppo umiliato per combattere al meglio!",
                      "activate"=>"offense"
                );
                $session['bufflist']['bordello']=$buff;
                $session['user']['gold'] = round($session['user']['gold'] * .5);
                $session['user'][gems] = round($session['user'][gems] * .5);
                //addnews("`%".$session['user']['name']."`@ è stato derubato da "
                //.($session['user']['sex']?"`^un gigolò`0 ":"`5una prostituta`0 ")."!`@");
        }
    } else {
        output("`n`%Questo è il miglior denaro che tu abbia mai speso!!`n");
        output(($session['user']['sex']?"`^Sandar`0 ":"`5Sephya`0 ")." conosce alla perfezione come darti piacere.`n`n");
        output(($session['user']['sex']?"`^Lui`0 ":"`5Lei`0 ")." fa cose che non avevi mai immaginato!!`n");
        output("Dopo momenti di pura estasi, abbandoni l'edificio sentendoti come mai prima d'ora!!`n`n");
        switch (e_rand(1,3)){
            case 1:
                //addnews("`%".$session['user']['name']."`@ ha ottenuto grande soddisfazione alla Casa del Piacere!!`@");
                output("`n`b`^(Questa esperienza ti ha rinfrancato completamente! I tuoi sensi sono al massimo. La tua difesa è incrementata, e guadagni un turno!)`n");
                $buff = array("name"=>"`4Rinfrancato`0",
                      "rounds"=>60,
                      "wearoff"=>"`5`bNon ti senti più rinfrancato!`b`0",
                      "defmod"=>1.5,
                      "roundmsg"=>"I tuoi sensi sono acuiti!!",
                      "activate"=>"defense"
                );
                $session['bufflist']['bordello']=$buff;
                $session['user']['turns'] += 1;
                break;
            case 2:
                //addnews("`%".$session['user']['name']."`@ ha ottenuto grande soddisfazione alla Casa del Piacere!!`@");
                output("`n`b`^(Questa esperienza ti ha rinfrancato completamente! I tuoi sensi sono al massimo. Il tuo attacco è incrementato, e guadagni un turno!)`n");
                $buff = array("name"=>"`4Rinfrancato`0",
                      "rounds"=>60,
                      "wearoff"=>"`5`bNon ti senti più rinfrancato!`b`0",
                      "atkmod"=>1.5,
                      "roundmsg"=>"I tuoi sensi sono acuiti!!",
                      "activate"=>"offense"
                );
                $session['bufflist']['bordello']=$buff;
                $session['user']['turns'] += 1;
                break;
            case 3:
                //addnews("`%".$session['user']['name']."`@ ha ottenuto grande soddisfazione alla Casa del Piacere!!`@");
                output("`n`b`^(Questa esperienza ti ha rinfrancato completamente! I tuoi sensi sono al massimo. Atacco e difesa sono incrementati!)`n");
                $buff = array("name"=>"`4Rinfrancato`0",
                      "rounds"=>60,
                      "wearoff"=>"`5`bNon ti senti più rinfrancato!`b`0",
                      "defmod"=>1.5,
                      "atkmod"=>1.5,
                      "roundmsg"=>"I tuoi sensi sono acuiti!!",
                      "activate"=>"offense"
                );
                $session['bufflist']['bordello']=$buff;
        }
    }
} else if ($_GET['op']=="four"){
    set_pref_pond("bordello",1);
    $session['user']['gold']-=$costfour;
    output("`n`%Allunghi a Madame ".$madam." i tuoi `6".$costfour."`0 pezzi d'oro, e ti avvii al piano superiore con ");
    output(($session['user']['sex']?"`^Kierst`0 ":"`5Kyale`0 ").".`n`n");
    if (e_rand(0,100)<35){
        output("`n`%".($session['user']['sex']?"`^Lui`0 ":"`5Lei`0 ")." evidentemente non è in gran forma ultimamente.`n");
        output("Si siede in un angolo della stanza e si rifiuta di essere toccat".($session['user']['sex']?"o":"a")."!!`n`n");
        output("Dice di provare ribrezzo nei tuoi confronti, e fuge dalla stanza...`n");
        output("Scornato, bevi il tuo drink, raccogli le tue cose e lasci la casa....`n");
        output("Una folla di persone si raggruppa all'esterno e ti indica ridendo....`n");
        output("Non sei sicuro del perchè stiano ridendo, ma ".($session['user']['sex']?"`^Kierst`0 ":"`5Kyale`0 ")." capeggia la folla.`n");
        switch (e_rand(1,3)){
            case 1:
                output("`n`b`^(Sei stato drogato nella casa. Il tuo attacco e la tua difesa sono ridotti.)`n");
                $buff = array("name"=>"`4Drogato`0",
                      "rounds"=>60,
                      "wearoff"=>"`5`bL'effetto della droga svanisce!`b`0",
                      "defmod"=>.95,
                      "atkmod"=>.95,
                      "roundmsg"=>"Sei drogato. È difficile combattere!",
                      "activate"=>"offense"
                );
                $session['bufflist']['bordello']=$buff;
                //addnews("`%".$session['user']['name']."`@ è stato drogato da "
                //.($session['user']['sex']?"`^un gigolò`0 ":"`5una prostituta`0 ")."!`@");
                break;
            case 2:
                output("`n`b`^(Sei stato drogato nella casa. Il tuo attacco e la tua difesa sono decisamente ridotti.)`n");
                $buff = array("name"=>"`4Drogato`0",
                      "rounds"=>60,
                      "wearoff"=>"`5`bL'effetto della droga svanisce!`b`0"
                      ,"defmod"=>.85,
                      "atkmod"=>.85,
                      "roundmsg"=>"Sei drogato. È difficile combattere!",
                      "activate"=>"offense"
                );
                $session['bufflist']['bordello']=$buff;
                //addnews("`%".$session['user']['name']."`@ è stato drogato da "
                //.($session['user']['sex']?"`^un gigolò`0 ":"`5una prostituta`0 ")."!`@");
                break;
            case 3:
                output("`n`b`^(Sei stato drogato nella casa. Il tuo attacco e la tua difesa sono molto ridotti!)`n");
                $buff = array("name"=>"`4Drogato`0",
                      "rounds"=>60,
                      "wearoff"=>"`5`bL'effetto della droga svanisce!`b`0",
                      "defmod"=>.75,
                      "atkmod"=>.75,
                      "roundmsg"=>"Sei drogato. È difficile combattere!",
                      "activate"=>"offense"
                );
                $session['bufflist']['bordello']=$buff;
                //addnews("`%".$session['user']['name']."`@ è stato drogato da "
                //.($session['user']['sex']?"`^un gigolò`0 ":"`5una prostituta`0 ")."!`@");
        }
    } else {
        output("`n`%Questo è il miglior denaro che tu abbia mai speso!!`n");
        output(($session['user']['sex']?"`^Kierst`0 ":"`5Kyale`0 ")." conosce alla perfezione come darti piacere.`n`n");
        output(($session['user']['sex']?"`^He`0 ":"`5She`0 ")." ti tratta come ".($session['user']['sex']?"`5una Regina`0 ":"`^un Re`0 ")."!!!`n");
        output("Dopo momenti di pura estasi, abbandoni l'edificio sentendoti come se potessi toccare il cielo con un dito!!`n`n");
        switch (e_rand(1,3)){
            case 1:
                output("`n`b`^(Sei in estasi. Il tuo attacco e la tua difesa sono leggermente potenziati.)`n");
                $buff = array("name"=>"`4Estasi`0",
                      "rounds"=>60,
                      "wearoff"=>"`5`bLa tua euforia scompare!`b`0",
                      "defmod"=>1.05,
                      "atkmod"=>1.05,
                      "roundmsg"=>"Tutto il tuo corpo è in estasi!",
                      "activate"=>"offense"
                );
                $session['bufflist']['bordello']=$buff;
                //addnews("`%".$session['user']['name']."`@ ha raggiunto l'estasi alla Casa del Piacere!!`@");
                break;
            case 2:
                output("`n`b`^(Sei in estasi. Il tuo attacco e la tua difesa sono potenziati.)`n");
                $buff = array("name"=>"`4Estasi`0",
                      "rounds"=>60,
                      "wearoff"=>"`5`bLa tua euforia scompare!`b`0",
                      "defmod"=>1.15,
                      "atkmod"=>1.15,
                      "roundmsg"=>"Tutto il tuo corpo è in estasi!",
                      "activate"=>"offense"
                );
                $session['bufflist']['bordello']=$buff;
                //addnews("`%".$session['user']['name']."`@ ha raggiunto l'estasi alla Casa del Piacere!!`@");
                break;
            case 3:
                output("`n`b`^(Sei in estasi. Il tuo attacco e la tua difesa sono potenziati e guadagni un turno.)`n");
                $buff = array("name"=>"`4Estasi`0",
                      "rounds"=>60,
                      "wearoff"=>"`5`bLa tua euforia scompare!`b`0",
                      "defmod"=>1.15,
                      "atkmod"=>1.15,
                      "roundmsg"=>"Tutto il tuo corpo è in estasi!",
                      "activate"=>"offense"
                );
                $session['bufflist']['bordello']=$buff;
                $session['user']['turns'] += 1;
                //addnews("`%".$session['user']['name']."`@ ha raggiunto l'estasi alla Casa del Piacere!!`@");
        }
    }
} else if ($_GET['op']=="five"){
    set_pref_pond("bordello",1);
    $session['user']['gold']-=$costfive;
    output("`n`%Allunghi a Madame ".$madam." i tuoi `6".$costfive."`0 pezzi d'oro, e ti avvii al piano superiore con ");
    output(($session['user']['sex']?"`^Travys`0 ":"`5Mora`0 ").".`n`n");
    if (e_rand(0,100)<30){
        output("`n`%".($session['user']['sex']?"`^Lui`0 ":"`5Lei`0 ")." inizia a procurarti piacere....`n");
        output(($session['user']['sex']?"`^Lui`0 ":"`5Lei`0 ")." ti ha fatto sdraiare con gli occhi chiusi...`n`n");
        output("Non hai mai provato un piacere maggiore in vita tua!!!!`n");
        output("Inizi a fonderti con ".($session['user']['sex']?"`^Travys`0 ":"`5Mora`0 ")." quando noti qualcosa di strano.....`n");
        output(($session['user']['sex']?"`^Travys`0 ":"`5Mora`0 ")." in realtà è ".($session['user']['sex']?"`^UNA DONNA`0 ":"`5UN UOMO`0 ")."!!!`n");
        switch (e_rand(1,3)){
            case 1:
                output("`n`b`^(Sei troppo imbarazzato per mostrarti in giro in città. Perdi alcuni turni!!)`n");
                $buff = array("name"=>"`4Imbarazzo`0",
                      "rounds"=>60,
                      "wearoff"=>"`5`bRiesci a superare il tuo imbarazzo!`b`0",
                      "defmod"=>.65,
                      "atkmod"=>.65,
                      "roundmsg"=>"Sei troppo imbarazzato per combattere appieno!",
                      "activate"=>"offense"
            );
                $session['bufflist']['bordello']=$buff;
                $session['user']['turns'] -= round($session['user']['turns']*.5);
                //addnews("`%".$session['user']['name']."`@ ha provato ad andare con  ".($session['user']['sex']?"`^una prostituta`0 ":"`5un gigolo`0 ")."!!`@");
                break;
            case 2:
                output("`n`b`^(Sei troppo imbarazzato per mostrarti in giro in città. Perdi alcuni turni!!)`n");
                $buff = array("name"=>"`4Imbarazzo`0",
                      "rounds"=>60,
                      "wearoff"=>"`5`bRiesci a superare il tuo imbarazzo!`b`0",
                      "defmod"=>.55,
                      "atkmod"=>.55,
                      "roundmsg"=>"Sei troppo imbarazzato per combattere appieno!",
                      "activate"=>"offense"
                );
                $session['bufflist']['bordello']=$buff;
                $session['user']['turns'] -= round($session['user']['turns']*.5);
                //addnews("`%".$session['user']['name']."`@ ha provato ad andare con  ".($session['user']['sex']?"`^una prostituta`0 ":"`5un gigolo`0 ")."!!`@");
                break;
            case 3:
                output("`n`b`^(Sei troppo imbarazzato per mostrarti in giro in città. Perdi alcuni turni!!)`n");
                $buff = array("name"=>"`4Imbarazzo`0",
                      "rounds"=>60,
                      "wearoff"=>"`5`bRiesci a superare il tuo imbarazzo!`b`0",
                      "defmod"=>.45,
                      "atkmod"=>.45,
                      "roundmsg"=>"Sei troppo imbarazzato per combattere appieno!",
                      "activate"=>"offense"
                );
                $session['bufflist']['bordello']=$buff;
                $session['user']['turns'] -= round($session['user']['turns']*.5);
                //addnews("`%".$session['user']['name']."`@ ha provato ad andare con  ".($session['user']['sex']?"`^una prostituta`0 ":"`5un gigolo`0 ")."!!`@");
        }
    } else {
        output("`n`%Tu sei ".($session['user']['sex']?"`^LA DONNA`0 ":"`5L'UOMO`0 ")."!!`n");
        output(($session['user']['sex']?"`^Travys`0 ":"`5Mora`0 ")." non ha mai provato nulla di simile a ciò che ha provato ");
        output("con te in tutta la sua vita!!!`n`n");
        output("Racconterà all'intero villaggio le tue gesta di amante fantastico!!!`n");
        output("Lasci la casa a testa alta e fiero della tua prestazione!!!`n`n");
        switch (e_rand(1,3)){
            case 1:
                output("`n`b`^(Sei la persona di più riverita nel villaggio!! Guadagni dei turni supplementari!!)`n");
                $buff = array("name"=>"`4Il Miglior Amante`0",
                      "rounds"=>60,
                      "wearoff"=>"`5`bNon ti senti più il migliore!`b`0",
                      "defmod"=>1.35,
                      "atkmod"=>1.35,
                      "roundmsg"=>"L'orgoglio scorre nelle tue vene!!",
                      "activate"=>"offense"
                );
                $session['bufflist']['bordello']=$buff;
                $session['user']['turns'] += 5;
                //addnews("`%".$session['user']['name']."`@ è il più `#Grande Amante del Mondo!!!!`@");
                break;
            case 2:
                output("`n`b`^(Sei la persona di più riverita nel villaggio!! Guadagni dei turni supplementari!!)`n");
                $buff = array("name"=>"`4Il Miglior Amante`0",
                      "rounds"=>60,
                      "wearoff"=>"`5`bNon ti senti più il migliore!`b`0",
                      "defmod"=>1.45,
                      "atkmod"=>1.45,
                      "roundmsg"=>"L'orgoglio scorre nelle tue vene!!",
                      "activate"=>"offense"
                );
                $session['bufflist']['bordello']=$buff;
                $session['user']['turns'] += 5;
                //addnews("`%".$session['user']['name']."`@ è il più `#Grande Amante del Mondo!!!!`@");
                break;
            case 3:
                output("`n`b`^(Sei la persona di più riverita nel villaggio!! Guadagni dei turni supplementari!!)`n");
                $buff = array("name"=>"`4Il Miglior Amante`0",
                      "rounds"=>60,
                      "wearoff"=>"`5`bNon ti senti più il migliore!`b`0",
                      "defmod"=>1.55,
                      "atkmod"=>1.55,
                      "roundmsg"=>"L'orgoglio scorre nelle tue vene!!",
                      "activate"=>"offense"
                );
                $session['bufflist']['bordello']=$buff;
                $session['user']['turns'] += 5;
                //addnews("`%".$session['user']['name']."`@ è il più `#Grande Amante del Mondo!!!!`@");
        }
    }
}
addnav("Torna al Castello",$returnvillage);
page_footer ();
?>