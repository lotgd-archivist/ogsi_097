<?php
/* *******************************************************
YAGOR L'AMMAZZAELFI
versione:   0.9

autore:     chumkiu

data:       12/09/2005

note:       Evento per bilanciare lo squilibrio delle razze dovuto in parte a Lonestrider
        e in parte in altri eventi dove elfi più veloci riescono a scappare più facilmente!
        Un pò l'ho fatto anche perché gli elfi mi stanno sui *#*§ :)
        I valori di Yagor vorrei che fossero imprevedibilmente dal difficile all'impossibile!
        Insomma non vorrei che fosse matematico sconfiggerlo per chi ha poteri e valori
        ben sopra la media! Io credo che i valori messi soddisfino tale richiesta,
        ma non ho l'esperienza necessaria per esserne sicuro :)
        Ovviamente la ricompensa per chi riesce a sconfiggerlo deve essere alta,
        per invogliare i giocatori a combatterlo ogni tanto :)

descrizione:    I non-elfi a seconda di ciò che scelgono possono guadagnare una gemma
        o ammazzarlo dando un equo rimborso. Possono anche ritrovarsi di fronte a Lonestrider
        cambiando così l'evento.
        Gli elfi possono attaccarlo oppure parlargli dandogli 3 o 5 gemme. Se gliene danno una
        sola... muoiono. In compenso se sono a secco di gemme si rimediano solo una botta in testa!
        (e sono stato buono :p)
        Se scappano al 90% muoiono
********************************************************* */
page_header("Yagor");
output("`n`n`c`b`#Yagor`3 l'ammazzaElfi`0`b`c`n`n");
//Modifica in caso la razza elfo non sia identificabile così
$isElfo_ES=($session['user']['race']==2)? true : false;
//INIZIO
if ($_GET['op']==""){

output("`3Mentre sei nella foresta in cerca di avventure, un `#nano`3 possente con una grossa ascia da guerra sulle spalle blocca il sentiero che stai percorrendo sbarrandoti la strada!`n");
output("Nonostante la bassa statura la figura è davvero inquietante!`n");
output("Il nano ti scruta attentamente e con astio inizia a parlare:`n`n");
    if($isElfo_ES) {
    //se sei un elfo
    output("\"`!Io sono `#Yagor`! l'ammazzaElfi `b e tu, guardacaso, sei ".($session[user][sex]?"un'`^Elfa":"un `^Elfo")."`!`b!\"");
    output("`n`3Cosa vuoi fare?");
    addnav("Parla a Yagor", "forest.php?op=parla");
    addnav("Attacca Yagor", "forest.php?op=attacca");
    addnav("Scappa!!", "forest.php?op=scappa");
    $session['user']['specialinc']="yagor.php";
    }
    else {
    output("\"`!Io sono `#Yagor`! l'ammazzaElfi e tu... mmmmh no... non sei ".($session[user][sex]?"un'`^Elfa":"un `^Elfo")."`!! Buon per te! Altrimenti ti avrei fatto la pelle\"`n`n");
    output("`3Detto questo scoppia in una fragorosa risata`n`n");
    output("`!\"Sei anche tu un".($session[user][sex]?"a":"o")." di quelli che non sopportano gli `^elfi`!?? Quegli esseri arroganti e presuntuosi??\"`n`n`n");
    output("`3Cosa rispondi?");
    addnav("\"Si li ammazzerei tutti\"", "forest.php?op=odio");
    addnav("\"In fondo sono simpatici\"", "forest.php?op=simpatici");
    addnav("\"Odio di più i `#nani!`!\" ATTACCA", "forest.php?op=ostile");
    $session['user']['specialinc']="yagor.php";
    }
}
else if($_GET['op']=="parla" && $isElfo_ES) {
    if($_GET['num']=="") {
    $session['user']['specialinc']="yagor.php";
    output("`3Cerchi di convincere `#Yagor`3 che in fondo in fondo gli `^elfi`3 sono esseri simpatici... ma con lui questo argomento non attacca nemmeno un pò.`n`n");
    output("Non ti resta che tentare di giocare la carta della corruzione e, dal momento che sai che ai `#nani`3 piacciono le pietre preziose decidi di dare alcune `&gemme`3 al possente `#nano`! : quante ne vuoi offrire?");
    addnav("1 gemma", "forest.php?op=parla&num=1");
    addnav("3 gemme", "forest.php?op=parla&num=3");
    addnav("5 gemme", "forest.php?op=parla&num=5");
    addnav("No ci ho ripensato", "forest.php?op=parla&num=0");
    }
    else {
        // qui l'ho fatto di proposito
        // anche se offre più gemme di quel che ha
        // si comporta allo stesso modo
        if($session['user']['gems']<=2) {
        output("`3Un pò impacciat".($session[user][sex]?"a":"o")." tiri fuori il borsellino con le `&gemme`3 e vedi che " . (($session['user']['gems']==0)? "non ne hai nessuna." : "ne hai davvero poche!") . "`n`nStai per rimettere il borsellino a posto quando `@Yagor`3 scoppia in una fragorosa risata:`n`n");
        output("`!\"ahahaha ".($session[user][sex]?"un'`^Elfa":"un `^Elfo")."`! tirchi".($session[user][sex]?"a":"o").", pover".($session[user][sex]?"a":"o")." e codard".($session[user][sex]?"a":"o")."! Questa è bella!!\" `n`n `3Nonostante la tensione, ridacchi anche tu per sdrammatizzare, ma smetti di ridere`n quando `#Yagor`3 prende la sua ascia e ti dà una bella botta in testa facendoti perdere i sensi.`n`n");
        output("`n`n Quando riacquisti conoscenza ti ritrovi da sol".($session[user][sex]?"a":"o")."! Con grande sollievo scopri che `#Yagor`3 ti ha lasciato viv".($session[user][sex]?"a":"o")."!`n Perdi solo un turno foresta per riacquistare le forze.`n");
        $session['user']['turns']--;
        $session['user']['specialinc']="";
        addnav("`@Continua", "forest.php");
        addnews("".($session[user][sex]?"`3L'`^Elfa":"`3L'`^Elfo")." `3" . $session['user']['name'] . "`3 è riuscit".($session[user][sex]?"a":"o")." a commuovere `#Yagor`3 l'ammazzaElfi`3.");
        }
        else if($_GET['num']<=2 && $session['user']['gems']>=3) {
            if($_GET['num']==0) {
            output("`3Tiri fuori il borsellino e vedi le tue `&" . $session['user']['gems'] . " gemme`3! Ricordi quanto hai faticato per averle e così rimetti il borsellino al suo posto!`nAlzi lo sguardo verso `#Yagor`3 con aria di sfida e... `n");
            $gemlost = intval($session['user']['gems'] * 0.3);
            if ($gemlost > 10) $gemlost=10;
            $session['user']['gems'] -= $gemlost;
            debuglog("muore e perde $gemlost gemme per aver fatto ".($session[user][sex]?"la tirchia":"il tirchio")." con Yagor");
            }
            else {
            output("`3Tenti la carta della corruzione tirando fuori `&una gemma`3 e sperando che questo gesto ti salvi la vita.`n`n");
            debuglog("muore e perde una gemma tentando di corrompere Yagor");
            $session['user']['gems']--;
            }
        output("`3 L'ultima cosa che vedi è l'ascia di `#Yagor`3 che entra nella tuo petto, squartandolo!!`n`n");
        output("`bSEI MORT".($session[user][sex]?"A":"O")."!!`b`n`n`3Dal momento che hai scoperto a tue spese che non è il caso di fare ".($session[user][sex]?"la tirchia":"il tirchio")." con `#Yagor`3 non perdi punti esperienza.`n");
        output("Ovviamente `#Yagor`3 si intasca " . (($_GET['num']==0)? "`b`&$gemlost`b`3 delle tue preziose `&gemme`3" : "la tua `&gemma`3") . "`3 già che c'è.");
        $session['user']['turns']=0;
        $session['user']['alive']=false;
        $session['user']['hitpoints']=0;
        addnav("Notizie Giornaliere", "news.php");
        addnews("`3L'`^elf".($session[user][sex]?"a":"o")."`3 " . $session['user']['name'] . " `3è mort".($session[user][sex]?"a":"o")." per mano di `#Yagor`3 l'ammazzalElfi!");
        $session['user']['specialinc']="";
        }
        else {
        if($session['user']['gems']<$_GET['num']) {
        $session['user']['gems']=0;
            output("`3Vorresti dare `&".$_GET['num']." gemme`3 a `#Yagor`3 ma scopri di averne solo `&" . $session['user']['gems'] . "`3!`n Provi lo stesso a dare al `#nano`3gli tutto quello che hai confidando nella sua clemenza!`n");
        }
        else {
        $session['user']['gems']-=$_GET['num'];
        output("`3Offri `&" . $_GET['num'] . " gemme`3 a `#Yagor`3!`n");
        }
        output("Dopo averci pensato un attimo il `#nano`3 prende le `&gemme`3 dalla tua mano e dice:`n`n");
        output("`!\"mmmmh L'avevo capito subito che il tuo era solo un travestimento.`n");
        output("Non farmi perdere tempo ora... devo andare a caccia di `^elfi`! veri\"`n`n");
        output("`3Detto questo si infila in tasca le `&gemme`3 che gli hai cortesemente offerto e se ne va per la sua strada.");
        $session['user']['specialinc']="";
        // in questo caso se mette più di 3 o 5 è fesso lui :p
         addnav("`@Continua", "forest.php");
        }
    }
}
// fine opzione PARLA

// se qualcuno lo attacca
else if($_GET['op']=="attacca" || $_GET['op']=="ostile") {
$battle=true;
fightnav(true,false);
$session['user']['specialinc']="yagor.php";
output("`3Attacchi `#Yagor`3!!");
$badguy = array(
        "creaturename"=>"`#Yagor`@ L'ammazzaElfi",
        "creaturelevel"=>$session['user']['level']+e_rand(1,3),
        "creatureweapon"=>"Ascia da Guerra",
        "creatureattack"=>$session['user']['attack']+ e_rand(1,(($session['user']['attack']/100)*$session['user']['level'])),
        "creaturedefense"=>$session['user']['defence']+ e_rand(1,(($session['user']['attack']/100)*$session['user']['level'])),
        "creaturehealth"=>round(($session['user']['maxhitpoints']*1.5)+(($session['user']['maxhitpoints']/100)*$session['user']['level'])),
        "diddamage"=>0
        );
    $points = 0;
    while(list($key,$val)=each($session['user']['dragonpoints'])){
        if ($val=="at" || $val == "de") $points++;
    }
    $points += (int)(($session['user']['maxhitpoints'] - 150)/10);
    //Modifica di Excalibur per tener conto dei PA e PD PERMANENTI
    $bnsatk=(int)(($session['user']['bonusattack']/1.5)+0.99);
    $bnsdef=(int)(($session['user']['bonusdefence']/1.5)+0.99);
    // Fine modifica ... vedi anche sotto
    //luke per potenziare un po il drago verde per i reincarnati
    $bnsatk+=(int)(($session['user']['reincarna']* e_rand(($bnsatk/2), $bnsatk))/4);
    $bnsdef+=(int)(($session['user']['reincarna']* e_rand(($bnsdef/2), $bnsdef))/4);
    //fine modifica luke
    $atkflux = e_rand(0, ($bnsatk+$points));
    $defflux = e_rand(0,($points-$atkflux+$bnsdef));
    //$hpflux = ($points + $bnsatk + $bnsdef - ($atkflux+$defflux)) * 5;

    $badguy['creatureattack']+=$atkflux;
    $badguy['creaturedefense']+=$defflux;
    $badguy['creaturehealth']+=$hpflux;
    $badguy['creaturegold']=e_rand(100,300)*$session['user']['level'];
    $badguy['creaturegems']=e_rand(1,5);
    $badguy['creatureexp']= e_rand(100,150)*$session['user']['level'];
/* **************---- VALORI DI YAGOR---- ************************
    $badguy['creatureexp']= e_rand(100,150)*$session['user']['level'];
// la vita dipende anche dall'attacco... altrimenti con un voodoo lo fa fuori
// se è
    $badguy['playerstarthp']=$session['user']['maxhitpoints']+round(($session['user']['attack']/100)*$session['user']['maxhitpoints']);
    $badguy['creaturename']="`#Yagor`@ L'ammazzaElfi";
    $badguy['creatureweapon']="Ascia da guerra";
    $badguy['creaturegold']=e_rand(3000,10000);
    $badguy['creaturegems']=e_rand(3,13);
    $badguy['creaturelevel']=$session['user']['level']+e_rand(2,5);
    $badguy['creatureattack']=$session['user']['attack']+ e_rand(1,$session['user']['attack']);
    $badguy['creaturedefense']=$session['user']['defence']+ e_rand(1,$session['user']['defence']);
    $badguy['creaturehealth']=$badguy['playerstarthp'];
    $badguy['diddamage']=200;
/* **************---- /VALORI DI YAGOR---- ************************ */

   $session['user']['badguy']=createstring($badguy);
    require("battle.php");
//    $session['user']['badguy']=createstring($badguy);
}
else if($_GET['op']=="fight") {
$session['user']['specialinc']="yagor.php";
$badguy= createarray($session['user']['badguy']);
$battle=true;
require("battle.php");
    if($victory) {
    output("`n`3Stai per sferrare il colpo finale su `#Yagor`3 agonizzante, quando lui ti implora di fermarti!`n`n");
    output("Promette di darti, se lo lasci vivere, una parte dei suoi averi che ha nascosto sotto un albero vicino!`n");
    output("Accetti il compromesso, dopotutto esplori la foresta alla continua ricerca di tesori e ricchezze!`n`n`n");
    output("`#Yagor`3 ti mostra il suo bottino che ammonta a `^". $badguy['creaturegold']. " `3pezzi d'`^oro`3 e `&" . $badguy['creaturegems'] . " gemme`3!!!`n");
    output("Guadagni inoltre `^" . $badguy['creatureexp'] . "`3 punti esperienza");
        if($isElfo_ES) {
        addnews("`3L'`^elf".($session[user][sex]?"a":"o")." " . $session['user']['name'] . " `3ha sconfitto `#Yagor`3 l'ammazzaElfi!!");
        }
        else {
        addnews(" ".$session['user']['name']." `3ha attaccato `#Yagor`3 l'ammazzaElfi senza ragione alcuna e ha vinto!");
        }
    $session['user']['specialinc']="";
    $session['user']['badguy']="";
    $session['user']['experience']+=$badguy['creatureexp'];
    $session['user']['gold']+=$badguy['creaturegold'];
    $session['user']['gems']+=$badguy['creaturegems'];
    $session['user']['turns']--;
    $session['user']['specialinc']="";
    addnav("Torna nella foresta","forest.php");
    }else if($defeat) {
    output("`n`n`b`3 Sei stat".($session[user][sex]?"a":"o")." uccis".($session[user][sex]?"a":"o")." da `#Yagor`3 l'ammazzaElfi!!!`n");
    output("Perdi tutto il tuo `^oro`3 e il `^15%`3 di esperienza`b");
    addnews("`3Pover".($session[user][sex]?"a":"o")." " . $session['user']['name'] . "`3! E' stat".($session[user][sex]?"a":"o")." uccis".($session[user][sex]?"a":"o")." da `#Yagor`3 l'Ammazzaelfi!");
    debuglog("perde il 15% exp e ".$session['user']['gold']." oro con Yagor");
    $session['user']['specialinc']="";
    $session['user']['gold']=0;
    $session['user']['hitpoints']=0;
    $session['user']['experience']=round($session['user']['experience']*.85,0);
    $session['user']['badguy']="";
    $session['user']['alive']=false;
    $session['user']['turns']=0;
    addnav("Notizie Giornaliere","news.php");
    }else {
    fightnav(true,false);
    }
}
// fine combattimento
else if($_GET['op']=="scappa" && $isElfo_ES) {
$case_ES=e_rand(1,10);
output("`n`n`3Terrorizzat".($session[user][sex]?"a":"o")." dalla possente muscolatura e dall'atteggiamento ostile del `#nano`3 provi a svignartela!`n");
    if($case_ES > 1) {
    output("Per tua sfortuna `#Yagor`3 è abituato a fronteggiare abilità elfiche e, mentre tu ti dai alla fuga, scaglia la sua ascia che ti colpisce in pieno nella schiena,");
    output("penetrando tra le scapole e raggiungendo il tuo cuore!!`n`n`n");
    output("`3SEI MORT".($session[user][sex]?"A":"O")."!!`n");
    output("Perdi il `^15%`3 della tua esperienza.`n");
    $gemlost = intval($session['user']['gems'] * 0.2);
    if ($gemlost > 10) $gemlost = 10;
    output("Perdi inoltre tutto l'`^oro`3 e `b`&$gemlost gemme`b`3 che avevi con te!!");
    debuglog("perde 15% exp, $gemlost gemme e ".$session['user']['gold']." oro da Yagor");
    $session['user']['alive']=false;
    $session['user']['hitpoints']=0;
    $session['user']['gold']=0;
    $session['user']['gems']-=$gemlost;
    $session['user']['experience']*=0.85;
    $session['user']['specialinc']="";
    addnav("Notizie Giornaliere","news.php");
    addnews("`3L'`^elf".($session[user][sex]?"a":"o")." `^" . $session['user']['name'] . "`3 ha incontrato `#Yagor`3 l'ammazzaElfi e non è sopravvissut".($session[user][sex]?"a":"o").".");
    }
    else {
    // una possibilità su 10 che riesci a fuggire
    output("`3La tua celebre velocità elfica ti permette di scomparire dalla sua vista prima che egli possa tentare di scagliarti addosso la sua enorme ascia!");
    output("`n`3SEI RIUSCIT".($session[user][sex]?"A":"O")." A FUGGIRE`n`n");
    output("L'hai fatta in barba al cacciatore di `^elfi`3! Guadagni il `^10%`3 di esperienza in più");
    $session['user']['experience']*=1.1;
    $session['user']['specialinc']="";
    addnews("`3L'`^elf".($session[user][sex]?"a":"o")." `3" . $session['user']['name'] . "`3 è riuscit".($session[user][sex]?"a":"o")." a sfuggire a `#Yagor`3 l'ammazzaElfi.");
    addnav("`@Continua", "forest.php");
    }
}
else if($_GET['op']=="simpatici" && !$isElfo) {
    output("`^\"Ma noooo! In fondo sono simpatici con le loro orecchie a punta\"`n`n");
    output("`#Yagor`3 sbarra gli occhi e poi scoppia a ridere:`n`n");
    output("`!\"AHAHAHAH per un attimo avevo creduto che parlassi sul serio! Mi sei proprio simpatic".($session[user][sex]?"a":"o")."!\"`n`n");
    output("`3Detto questo tira fuori `&una gemma`3 e te la lancia`n`n");
    output("`!\"Tieni! Questa era del tuo ultimo amichetto con le orecchie a punta! AHAHAHA\"`n`n`n`n");
    output("`3Beh in tutta questa vicenda almeno qualcosa ci hai guadagnato!");
    $session['user']['gems']++;
    $session['user']['specialinc']="";
    addnav("`@Continua", "forest.php");
}
else if($_GET['op']=="odio" && !$isElfo) {
    output("`2\"Li ammazzerei tutti quegli `^elfi`2 odiosi!!\"`n");
    output("`3Pronunci una serie di offese irripetibili nei confronti degli `^lfi`3, quando ad un tratto...`n");
    output("`n`c`b`6...un vento gelido spira da ovest...`b`c`n`n");
    output("`#Yagor`3 sbarra gli occhi e scappa via!`n`n");
    output("Avendo un brutto presentimento, ti giri e trovi `b`\$Lonestrider`b`3 e i suoi ladroni che sghignazzano:`n`n");
    output("`n`^\"E così ci ammazzeresti tutti quanti??\"`n`n`3 ti chiede divertito `b`\$Lonestrider`3`b accompagnato dalle risate dei suoi amici!`n`n`n");
        if($session['user']['gems']==0)  {
            output("`3Prima che tu te ne renda conto, i ladroni ti hanno circondato.
    Affannat".($session[user][sex]?"a":"o").", ti maledici per non essertene accorto in tempo. Iniziano col pretendere parte delle le tue preziose `&gemme`3 minacciandoti.`n`n
    `3Cerchi di spiegar loro che non ne hai, così ti perquisiscono. E, non trovandone, ti malmenano per un po' prima di abbandonarti nella foresta da sol".($session[user][sex]?"a":"o").".`0");
            addnav("`@Torna alla Foresta","forest.php");
              $session['user']['charm'] -= 1;
              $session['user']['specialinc']="";
              $session['user']['hitpoints'] -= 5;
        }
        else {
            output("`n`n`3Cosa fai??");
        // parte del codice di lonestrider!
        $costwin = (int)($session['user']['gems']*.05);
        $costwin++;
        $costrun = round($costwin/2,0);
        $costlose = $costwin*2;
        $costlose = min($session['user']['gems'],$costlose);
        $session['user']['specialinc']="";
        addnav("Dagli $costwin gemm" . (($costwin>1)? "e" : "a") ." (conosci l'antifona)","forest.php?op=give");
            addnav("Scappa via!","forest.php?op=runawaylikealittlesissybaby");
            addnav("Combattili!","forest.php?op=stand");
    //  addnav("`@Continua", "forest.php");
        $session['user']['specialinc']="thieves.php";
        }

}
else if($_GET['op']=="annulla") {
$session['user']['specialinc']="";
addnav("Torna alla Foresta", "forest.php");
}
?>