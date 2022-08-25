<?php
// upgraded to 1.1 .. randomised event outcomes and added a few extra's
// upgraded to 1.2 .. added Dragon King
// upgraded to 1.3 .. added graphics
//o.9.7 Conversion by Excalibur (www.ogsi.it/logd)
require_once("common.php");
//settings
$maxhp=e_rand(2,5);  //max HP gained
$favor=50;  //% of favors lost
$session['user']['specialinc'] = "dragonqueen.php";
page_header("Dragon Queen");
$op = $_GET['op'];
//if ($op != "attack" AND $op != "fight") $op = "choice";
    if ($op=="" || $op=="search"){
        output("`3Ti imbatti in un cunicolo ricoperto d'edera che pare essere l'ingresso di una caverna.");
        output("Cosa vuoi fare?");
        addnav("La Caverna");
        addnav("Sposta l'edera ed entra", "forest.php?op=enter");
        addnav("Gira i tacchi e fuggi", "forest.php?op=run");
        addnav("Allontanati lentamente", "forest.php?op=back");
    }elseif ($op=="enter") {
        output("`6Sposti alcuni filari di edera ed entrando ti ritrovi in una piccola e scura caverna ");
        output("sul cui suolo giacciono le ossa di avventurieri sfortunati e varie creature della foresta.");
        output("`n`n");
        output("`^Rabbrividendo alla vista, decidi di tornare alla foresta.");
        addnav("Torna in Foresta", "forest.php?");
        $session['user']['specialinc'] = "";
    }elseif ($op=="run"){
        output("`2Temendo per la tua stessa vita inverti la direzione per zone meno pericolose della foresta.");
        addnav("Torna alla Foresta", "forest.php?");
        $session['user']['specialinc'] = "";
    }elseif ($op=="back") {
        output("`3Indietreggi lentamente dall'ingresso della caverna, quando improvvisamente il terreno si apre ");
        output("sotto i tuoi piedi, e barcollando sul bordo della buca, perdi l'equilibrio e precipiti.");
        output("`n`n");
        output("Colpisci il suolo con un sordo rimbombo, ti rialzi in piedi, controlli di non essere ferito, ");
        output("e constatando di essere stato fortunato, dai un'occhiata tutt'intorno.");
        addnav("La Caverna");
        addnav("Continua", "forest.php?op=continue");
        addnav("Chiudi gli occhi e incrocia le dita", "forest.php?op=hope");
    }elseif ($op=="hope"){
        output("`6Chiudi i tuoi occhi ermeticamente, cona la speranza che qualcuno o qualcosa intervenga ");
        output("per salvarti l'osso del collo.");
        output("`n`n");
        output("`7Improvvisamente un vento impetuoso percuote la tua armatura, e sembra penetrare in ogni più ");
        output("piccolo pertugio gelando le ossa del tuo debole corpo. Vieni sollevato in piedi, sbattuto contro ");
        output("le pareti e la volta della caverna, e quando improvvisamente il vento scompare, riapri gli occhi.");
        output("`n`n");
        output("Ti ritrovi nuovamente nella foresta, alquanto malridotto e lacerato dall'esperienza.");
        output("`n`n");
        $prev = $session['user']['turns'];
        if ($prev >= 4) $session['user']['turns']-=4;
        else if ($prev < 4) $session['user']['turns']=0;
        $current = $session['user']['turns'];
        $lost = $prev - $current;
        output("`^Perdi $lost turni combattimento.");
        debuglog("perde $lost turni combattimento (DragonQueen)");
        addnav("Torna alla Foresta", "forest.php?");
        $session['user']['specialinc'] = "";
    }elseif ($op=="continue") {
        output("`7Guardandoti attorno con attenzione vedi numerosi tunnel che si diramano dalla caverna principale, ");
        output("ed in un angolo i resti di un guerriero, che stringe nella mano scheletrica un messaggio.");
        output("`n`n");
        output("Afferri il foglio dalla sua presa e lo leggi.");
        output("`n`n");
        output("`@Sia di monito per tutti coloro che entrano qui.  Questa caverna è il regno della `^DragonQueen.  ");
        output("`@Ci sono molte trappole e trabocchetti, ma la ricompensa sarà grande.");
        output("`n`n");
        output("`7La scrittura malferma sembra far sospettare che lo scrittore stesse per continuare, ma che ");
        output("qualcosa l'abbia fermato ... quello e le gocce di sangue intrise nella pergamena.");
        output("`n`n");
        output("Quale tunnel decidi di seguire?");
        addnav("The Cavern");
        addnav ("Tunnel Uno", "forest.php?op=tunnel&op1=1");
        addnav ("Tunnel Due", "forest.php?op=tunnel&op1=2");
        addnav ("Tunnel Tre", "forest.php?op=tunnel&op1=3");
        addnav ("Tunnel Quattro", "forest.php?op=tunnel&op1=4");
        addnav ("Tunnel Cinque", "forest.php?op=tunnel&op1=5");
    }elseif ($op=="tunnel") {
        switch (e_rand (1, 5)){
               case 1:
               output("`7A metà strada giù nel tunnel, ti imbatti in una frana.");
               output("`n`n");
               output("`3Vedendola ti volti e ti dirigi nuovamente alla caverna.");
               output("`n`n");
               output("`7Indietro nella Caverna. Tenti di decidere quale tunnel scegliere questa volta.");
               addnav("La Caverna");
               addnav("Continua", "forest.php?op=continue");
               break;
               case 2:
               output("`3Scendendo nel tunnel intravedi in distanza una luce fioca. Decidi di seguirla e ti ritrovi ");
               output("nuovamente nella foresta, affaticato per la lunga camminata.");
               output("`n`n");
               output("`3Noti qualcosa di lucente alla tua sinistra, e guardando meglio trovi `b`&2 gemme`b`3!!");
               $session['user']['gems']+=2;
               debuglog("trova 2 gemme nella caverna di DragonQueen");
               addnav("Torna alla Foresta", "forest.php?");
               $session['user']['specialinc'] = "";
               break;
               case 3:
               output("`7Scendendo nel tunnel ti storti la caviglia, e zoppicando in direzione della caverna, ");
               output("ti rendi conto che non riuscirai ad uscire, e morirai qui ...");
               output("`n`n");
               output("Il tuo corpo giace ora in una caverna.");
               output("`n`n");
               output("Perdi il 10% della tua esperienza. Potrai continuare a giocare domani.");
               $session['user']['alive']=false;
               $session['user']['hitpoints']=0;
               $session['user']['experience']*=0.9;
               $gold = $session['user']['gold'];
               $session['user']['gold'] = 0;
               debuglog("muore e perde 10% exp, $gold oro nella caverna DragonQueen");
               addnav("`^Notizie Giornaliere","news.php");
               addnews("`3Il corpo di ".$session['user']['name']." `3è stato rinvenuto in una profonda caverna.");
               $session['user']['specialinc'] = "";
               break;
               case 4:
               output("`7Percorri una svolta nel tunnel per giungere ad un fiume sotterraneo, e ti ritrovi su uno ");
               output("spuntone di roccia con una cascata che scende sopra di te.");
               output("`n`n");
               output("Mentre rimani li a godere del ristoro dato dall'acqua, percepisci aumentare la tua resistenza!");
               output("`n`n");
               $reward = e_rand(1,$maxhp);
               output("I tuoi HitPoints Massimi sono stati incrementati `bpermanentemente`b di ".$reward."!");
               output("`n`n");
               $session['user']['maxhitpoints'] += $reward;
               $session['user']['hitpoints'] += $reward;
               output("Ripercorrendo la strada al contrario ti ritrovi nuovamente all'imbocco della caverna.");
               debuglog("guadagna $reward HP permanenti nella caverna DragonQueen");
               addnav("Torna alla Foresta", "forest.php?");
               $session['user']['specialinc'] = "";
               break;
               case 5:
               output("`7Inizi a scendere nel tunnel, e sentendo un suono come di fruscio vedi una `^fatina`7 ");
               output("intrappolata in una gabbietta per uccelli in una rientranza alla tua destra.");
               output("`n`n");
               output("La fata si rivolge a te: \"`^Aiutami, ti prego. Un Troll Maligno mi ha imprigionata!! ");
               output("Liberami e saprò ricompensarti.`7\"");
               output("`n`n");
               output("La aiuterai?");
               addnav("La Fatina");
               addnav("Liberala", "forest.php?op=free");
               addnav("Continua per la tua strada", "forest.php?op=onway");
               break;
        }
    }elseif ($op=="free") {
        switch (e_rand (1, 3)){
            case 1:
            output("`^Raggiungi la rientranza per liberare la Fatina dalla gabbia, e appena apri lo sportello ");
            output("la piccola creature vola via.");
            output("`n`n");
            output("Una nebbia lucente appare dal nulla e ti avvolge facendoti perdere conoscienza. Quando ti ");
            output("risvegli sei molto più in profondità nella caverna, la fatina ti ha aiutato nella tua ricerca.");
            output("`n`n");
            output("C'è una svolta nel tunnel proprio di fronte a te.");
            addnav("Fata Liberata");
            addnav("Scendi nel tunnel","forest.php?op=turn");
            break;
            case 2:
            output("`^Raggiungi la rientranza per liberare la Fatina dalla gabbia,e appena apri lo sportello ");
            output("la piccola creature vola via.");
            output("`n`n");
            output("L'oscurità ti avvolge, e quando infine ti risvegli sei nuovamente nella caverna principale, ");
            output("con un combattimento foresta in meno.");
            $session['user']['turns']-=1;
            debuglog("perde un combattimento nella caverna DragonQueen");
            addnav("Continua", "forest.php?op=continue");
            break;
            case 3:
            output("`^Raggiungi la rientranza per liberare la Fatina dalla gabbia,e appena apri lo sportello ");
            output("la piccola creature vola via.");
            output("`n`n");
            output("Una luce dorata ti avvolge, e quando scompare ti rendi conto di aver guadagnato 2 combattimenti ");
            output("foresta e continui sulla tua via.");
            $session['user']['turns']+=2;
            debuglog("guadagna 2 combattimenti nella caverna DragonQueen");
            addnav("Continua sulla tua via", "forest.php?op=onway");
            break;
        }
    }elseif ($op=="turn") {
        output("`7Scendendo nel tunnel arrivi ad un bivio.");
        output("Quale scegli?");
        addnav("Prendi la Sinistra","forest.php?op=choice&op1=1");
        addnav("Vai Diritto","forest.php?op=choice&op1=2");
        addnav("Prendi la Destra","forest.php?op=choice&op1=3");
    }elseif ($op=="onway") {
        output("`7Proseguendo nel tunnel ti imbatti in un borsellino per `\$g`5e`2m`6m`7e, che afferri rapidamente.");
        $session ['user']['gems']+=1;
        output("`n`n");
        output("`#Aprendolo scopri che contiene una gemma !!");
        debuglog("trova 1 gemma nella caverna DragonQueen");
        output("`n`n");
        output("`7Continuando ti ritrovi nei pressi di un torrente sotterraneo. Alla tua sinistra c'è una piccola ");
        output("barca, mentre alla tua destra c'è un sentiero che conduce ad un altro tunnel.");
        output("`n`n");
        output("Cosa vuoi fare?");
        addnav("Il Ruscello");
        addnav ("Prendi il Sentiero", "forest.php?op=path");
        addnav ("Prendi la Barca", "forest.php?op=boat");
        addnav ("Attraverso a Nuoto", "forest.php?op=swim");
    }elseif ($op=="swim"){
        output("`7Tuffandoti inizia a nuotare nel torrente. Alla tua sinistra noti un flusso di bolle, e colto ");
        output("dal panico inizi a nuotare più velocemente, tenendo d'occhio le bolle.");
        output("`n`n");
        output("Le bolle continuano ad avvicinarsi, e pensi di non farcela a raggiungere la riva.");
        output("`n`n");
        output("All'improvviso un tentacolo gigante sale dalle profondità e ti trascina giù con se. ");
        output("Senza fiato, ti ritrovi ad osservare l'occhio di una piovra.");
        output("`n`n");
        output("Mentre rilasci l'aria residua dai tuoi polmoni e ti rassegni all'idea della morte, ti ritrovi ");
        output("libero come per miracolo, e senza farti pregare riconquisti la superficie per inspirare una ");
        output("agognata boccata d'aria e ti trascini fino alla riva.");
        output("`n`n");
        output("Tremando e rabbrividendo per il freddo ripensi alla scelta di nuotare!");
        addnav("The Stream");
        addnav ("Prendi il Sentiero", "forest.php?op=path");
        addnav ("Prendi la Barca", "forest.php?op=boat");
    }elseif ($op=="path"){
        output("`(Giri a destra e imbocchi il sentiero che alla fine conduce ad un piccolo capanno, situato ");
        output("a lato di uno dei tanti tunnel. Bussi alla porta, ed un enorme (e orrendo) Troll apre l'uscio e ");
        output("ti fissa con attenzione.");
        output("`n`n");
        output("Grugnendo dice: \"`^Cosa vuoi?`(\"");
        output("`n`n");
        output("Pensandoci sopra rispondi: \"`^Voglio solo andarmene in fretta da qui!`(\"");
        output("`n`n");
        output("Il Troll si offre come guida per portarti in superficie alla modica cifra di 1 gemma, ");
        output("accetti la sua offerta?");
        addnav("Il Troll");
        if ($session['user']['gems'] > 0) addnav ("Pagalo", "forest.php?op=pay");
        addnav ("No, non pago", "forest.php?op=don");
    }elseif ($op=="don"){
        output("`2\"`^Non se ne parla, non ti pagherò 1 gemma, troverò l'uscita da solo!`2\"");
        output("`n`n");
        output("`2Girando i tacchi inizi a vagare senza orientamento nel vasto ed intricato sistema di caverne ");
        output("per raggiungere finalmente l'uscita, ma al costo di 3 combattimenti foresta.");
        $session ['user']['turns']-=3;
        debuglog("perde 3 combattimenti nella caverna DragonQueen");
        addnav("Torna alla Foresta", "forest.php?");
        $session['user']['specialinc'] = "";
    }elseif ($op=="pay") {
        output("`(Annuendo con la testa, estrai velocemente la gemma dal tuo borsellino e la getti al Troll, ");
        output("che la ripone altrettanto velocemente, e si volta per condurti alla superficie.");
        $session ['user']['gems']-=1;
        debuglog("paga 1 gemma al Troll nella caverna DragonQueen per uscire");
        addnav("Torna in foresta", "forest.php?");
        $session['user']['specialinc'] = "";
    }elseif ($op=="boat"){
        output("`3Sleghi la piccola imbarcazione e salti a bordo, remando con vigore per attraversare il torrente. ");
        output("Con la coda dell'occhio noti un flusso di bolle dirigersi diretto verso di te.");
        output("`n`n");
        output("`3Raggiunta la riva, salti velocemente fuori dalla barca, e ti giri giusto in tempo per vedere un ");
        output("enorme tentacolo distruggere l'imbarcazione, i cui resti seguono lentamente la corrente. ");
        output("Asciugandoti il sudore dalla fronte dopo averla scampata per un pelo, devi decidere quale strada seguire.");
        output("Quale seguire? Il tunnel a sinistra? Quello a destra?");
        addnav("La Biforcazione");
        addnav ("Vai a Sinistra", "forest.php?op=choice&op1=1");
        addnav ("Vai a Destra", "forest.php?op=choice&op1=2");
        addnav("Prosegui Diritto", "forest.php?op=choice&op1=3");
    }elseif ($op=="choice") {
        switch (e_rand (1, 3)){
            case 1:
            output ("`3La `^Dragon Queen `3ti fa cenno di avvicinarti, e non percependo nessun pericolo ti avvicini ");
            output("a lei. Lei ti sorride amichevolmente.");
            output("`n`n");
            output("`6Ti porgo la mia gratitudine, hai lasciato il mio nido indisturbato e con esso le mie uova, ");
            output("ti sei guadagnato i miei favori, ecco il mio regalo.");
            output("`n`n");
            output ("`3Spingendoti delicatamente, ti invita ad andare nel retro della caverna, una grande tenda ");
            output("ricamata con filigrana d'`7argento `3copre un passaggio a volta dietro alla caverna, e mentre ");
            output("lo sposti di lato vieni accecato da una luce improvvisa....");
            output("`n`n");
            output ("`#Guardando con gli occhi socchiusi alla luminosità improvvisa comprendi che la DragonQueen ");
            output("ti ha dato in regalo un NewDay!!");
            output("`n`n");
            //rawoutput("<IMG SRC=\"images/dq_gold.gif\"><BR>\n");
            //rawoutput("<div style=\"text-align: left;\"><a href=\"http://www.arcanetinmen.dk\" target=\"_blank\">copyright and used with permission of Arcane Tinmen ApS 1999 - 2003, http://www.arcanetinmen.dk</a><br>");
            $session['user']['lasthit']="0000-00-00 01:00";
            $session['user']['restorepage']="forest.php";
            debuglog("guadagna 1 NewDay nella caverna DragonQueen");
            addnav("Torna alla Foresta","forest.php");
            $session['user']['specialinc'] = "";
            break;
            case 2:
            $a = intval($session['user']['deathpower']*($favor/100));
            output("`(ti ritrovi in una caverna pieno di uova di dragone, molte delle quali schiuse ... ");
            output("all'improvviso un tremendo ruggito scuote le pareti, e corri fuori dalla caverna il più ");
            output("velocemente possibile ... una mano fredda ti agguanta proprio mentre salti fuori dalla ");
            output("caverna. Perdi $a favori con Ramius!!");
            $session['user']['deathpower']-=$a;
            $session['user']['specialinc'] = "";
            debuglog("perde $a favori da Ramius nella caverna DragonQueen");
            addnav("Torna alla Foresta","forest.php");
            $session['user']['specialinc'] = "";
            break;
            case 3:
            $id=$session['user']['acctid'];
            $sql = "SELECT acctid,name FROM accounts WHERE alive=1 and acctid<>'$id' ORDER BY rand(".e_rand().") LIMIT 1";
            $res = db_query($sql);
            $row = db_fetch_assoc($res);
            $random = $row['name'];
            output("`7Inciampi e cadi, e percepisci un fiato caldo sulla tua schiena e qualcosa che si rompe sotto ");
            output("le tue mani. Sollevando un po' la testa, ti ritrovi ad osservare le zanne di un enorme ");
            output(" `)Drago d'Ossidiana.  `7Sembra che abbia appena terminato di pasteggiare con un guerriero i cui ");
            output(" resti assomigliano vagamente a ".$random."`7. Guardando verso il basso ti rendi conto che hai ");
            output("appena schiacciato un uovo di drago.");
            output("`n`n");
            output("Con un improvviso urlo rabbioso, il Re Dragone attacca.");
            output("`n`n");
            //rawoutput("<IMG SRC=\"images/dq_black.gif\"><BR>\n");
            //rawoutput("<div style=\"text-align: left;\"><a href=\"http://www.arcanetinmen.dk\" target=\"_blank\">copyright and used with permission of Arcane Tinmen ApS 1999 - 2003, http://www.arcanetinmen.dk</a><br>");
            addnav("Il Re Dragone","forest.php?op=attack");
            break;
        }
    }elseif ($op=="attack"){
        $id=$session['user']['acctid'];
        $op=$_GET['op'];
        page_header("Il Re Dragone");
        $level = $session['user']['level']+2;
        $dk = round($session['user']['dragonkills']*.5);
        $badguy = array(
            "creaturename"=>"`7Re Dragone",
            "creaturelevel"=>$level,
            "creatureweapon"=>"`7Zanne d'Ossidiana `5e `#Respiro Ghiacciato",
            "creatureattack"=>round($session['user']['attack'])+1,
            "creaturedefense"=>round($session['user']['defense'])+1,
            "creaturehealth"=>round($session['user']['maxhitpoints']*1.76),
            "diddamage"=>0);
        $session['user']['badguy']=createstring($badguy);
        $op="fight";
        $_GET['op']= "fight";
    }
    if ($op=="fight"){ $battle=true; }
    if ($battle){
        include("battle.php");
        if ($victory){
            output("`n`^Il Re Dragone giace ai tuoi piedi.");
            $expbonus=$session['user']['dragonkills']*4;
            $expgain =($session['user']['level']*e_rand(18,26)+$expbonus);
            $session['user']['experience']+=$expgain;
            output("`@Guadagni `#".$expgain." punti esperienza`@.`n`n");
            addnews($session['user']['name']." `^ha sconfitto `)il Re Dragone`^.");
            output("`^Sentendosi piuttosto stanco ma eccitato dall'esperienza, ritorni alla foresta.");
            debuglog("Uccide il Re Dragone e guadagna $expgain exp nella caverna DragonQueen");
            addnav("Torna alla Foresta", "forest.php?");
            $session['user']['specialinc'] = "";
        }elseif($defeat){
            debuglog("perde $exploss exp, ".$session['user']['gold']." oro e muore nella caverna DragonQueen");
            $session['user']['specialinc'] = "";
            $session['user']['alive'] = false;
            $session['user']['hitpoints'] = 0;
            $session['user']['gold'] = 0;
            output("`n`8Puoi sentire il ruggito trionfante e forse il rumore di frattura dell'ultimo osso intero ");
            output("del tuo corpo maciullato mentre la vita ti abbandona.`n`bSEI MORTO!!`n`n");
            $exploss = round($session['user']['experience']*.1);
            $session['user']['experience']-=$exploss;
            output("`\$Hai perso `&$exploss `\$punti esperienza!`n");
            output("Hai perso tutto il tuo oro!`n");
            output("Potrai continuare a giocare domani.");
            addnav("`^Notizie Giornaliere","news.php");
            addnews($session['user']['name']."`2 è stato visto avventurarsi in una caverna, da cui non è più uscito.");
        }else{
            fightnav(true,false);
        }
    }
    page_footer();
?>