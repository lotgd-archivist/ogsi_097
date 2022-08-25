<?php
/* *******************
The Black Egg
A "capture the flag"-like extension for PvP in LoGD
V 1.0 Written by anpera 2003-12-07
Modified
Additional changes in common.php, configuration.php, darkhorse.php, healer.php, newday.php, prefs.php, pvp.php, shades.php required.
See http://www.anpera.net/forum/viewforum.php?f=27 or LotGD project homepage for details.
******************* */

$session['user']['specialinc'] = "goldegg.php";
if ($_GET['op']=="takeit") {
    output("`3Sei certo che questo sia il leggendario `^Uovo d'Oro`3 e ti arrampichi freneticamente sull'albero.`n`n");
    if (e_rand(1,10)==4){
        output("Che Sfortuna! Afferri l'uovo ma in quel momento un grifone ti becca sulla spalla - martoriando le tue carni...");
        $lvl = $session['user']['level'];
        $hurt = e_rand(5*$lvl,10*$lvl);
        $session['user']['hitpoints']-=$hurt;
        output("`n`n`^Perdi $hurt hitpoints!`n");
        if ($session['user']['hitpoints']<=0) {
                output("`4Sei `bMORTO`b!!!`nMantieni tutto il tuo oro e la tua esperienza. Hai imparato la tua lezione per oggi.`n");
                debuglog("muore all'uovo d'oro");
                addnav("Terra delle Ombre","shades.php");
                addnav("Notizie Giornaliere","news.php");
            addnews($session['user']['name']." `0ha cercato di prendere l'`^Uovo d'Oro`0 ed è morto.");
            addnews($session['user']['name']." `0ha cercato di prendere l'`9Uovo Nero`0 ed è morto.");
            }
    } else if (getsetting("hasblackegg",0)!=0){
        output("Che Sfortuna! Raggiungi il nascondiglio per scoprire che l'uovo non c'è più! In basso vedi qualcuno che sta scappando via con il `btuo`b uovo...");
    } else {
        output("`3afferri l'uovo ma ti accorgi con orrore che non `bè`b l'`^Uovo d'Oro`3 bensì l'`!Uovo Nero`3 !!!`n ");
        output(" Quella che sembrava una situazione estremamente fortunata si è rivoltata contro di te !!.");
        //addnews("`^".$session['user']['name']."`^ ha trovato l'Uovo Nero nella foresta!");
        debuglog("ha trovato l'uovo nero in foresta");
        savesetting("hasblackegg",stripslashes($session['user']['acctid']));
    }
    $session['user']['specialinc']="";
} elseif ($_GET['op']=="abhaun") {
    output("`3Perchè rischiare di fronteggiare la madre dell'uovo? Ti sembra più semplice rubarlo ad un altro guerriero. Che siano gli altri a rischiare e prendere l'uovo per te.");
    $session['user']['specialinc']="";
} else {
    if (getsetting("pvp",1)==0) {
        output("`3Sulla biforcazione di un grande albero vedi un enorme nido.`n");
        output("Noti che è vuoto e lo lasci senza prestargli ulteriore attenzione.`n`n ");
        addnav("`@Torna alla Foresta","forest.php");
        $session['user']['specialinc']="";
    } else if (getsetting("hasblackegg",0)==0){
          output("`3Sulla biforcazione di un grande albero vedi un enorme nido. Qualcosa brilla tra i rami. Non è il sole. E' ... qualcosa .... `^d'oro`3!");
          output(" Avevi sentito la favola di un leggendario uovo d'oro alla taverna della foresta e non riesci a credere che possa essere vera.`n");
          output("Quest'uovo potrebbe essere quello della leggenda e ti offrirebbe un mucchio di vantaggi. O forse solo un buon pasto. Ma potrebbe essere solo un 'normale' uovo che riflette i raggi del sole e sua madre non sarebbe di certo contenta ");
          output("se ti trovasse ad arrampicarti sull'albero...");
        addnav("Prendi l'uovo","forest.php?op=takeit");
        addnav("Lascialo dov'è","forest.php?op=abhaun");
        $session['user']['specialinc']="goldegg.php";
    } else {
        $sql = "SELECT acctid,name,sex,level FROM accounts WHERE acctid = '".getsetting("hasblackegg",0)."'";
        $result = db_query($sql) or die(db_error(LINK));
        $row = db_fetch_assoc($result);
        $ownerlvl=$row['level'];
        $owner = $session['user']['acctid'];
        if ($owner == $row['acctid']) {
            if ($session['user']['hitpoints']>0) addnav("`@Torna alla Foresta","forest.php");
            output("`3Ti sembra di riconoscere questa parte della foresta. C'è un enorme nido tra gli alberi sopra di te. E un turbinio nell'aria.");
            output(" E' mamma grifone! Sente l'odore del tuo - suo uovo. Terrorizzato offri l'uovo al grifone ma il grifone è già sopra di te...");
            $lvl = $session['user']['level'];
            $hurt = e_rand(4*$lvl,9*$lvl);
            $session['user']['hitpoints']-=$hurt;
            output("`n`n`^Hai perso l'uovo e $hurt hitpoints! Forse la fortuna si è ricordata di te, finalmente ti sei liberato di quest'uovo !!`n");
            $text ="`^".$session['user']['name']."`^ ha perso l'Uovo Nero nella foresta";
            debuglog("ha perso l'uovo nero in foresta");
            if ($session['user']['hitpoints']<=0) {
                    $session['user']['alive']=false;
                    output("`4Sei `bMORTO`b!!!`nMantieni tutto il tuo oro e la tua esperienza. Hai imparato la tua lezione per oggi.`n");
                    addnav("`%Terra delle Ombre","shades.php");
                    addnav("`^Notizie Giornaliere","news.php");
                    debuglog("è morto quando ha perso l'uovo nero in foresta");
                $text = $text." ed è morto";
                }
            addnews($text.".");
            savesetting("hasblackegg",stripslashes(0));
        } else {
            $session['user']['specialinc']="goldegg.php";
            output("`3Sulla biforcazione di un grande albero vedi un enorme nido. `nInizi a fantasticare sul fatto ");
            output("che possa essere l'alcova del mitico `6`iUovo d'Oro!!!`i`3`n`n");
              output("Decidi di arrampicarti per scoprire che purtroppo è vuoto. ");
            //output("`^ $row[name]`^ ha il `iTUO`i uovo d'oro!`n`3Non vuoi rubarlo a ".($row[sex]?"lei":"lui")."?`n");
            output("`2 $row[name]`3 ha il `iTUO`i `6Uovo d'Oro!`n`3");
            if ($ownerlvl+1<$session['user']['level'] || $ownerlvl-2>$session['user']['level']) {
                if ($ownerlvl<$session['user']['level']){
                    $diff=1;
                    }
                else {
                    $diff=0;
                    }
                output("`2$row[name]`3 è di livello `5`b$row[level]`3`b, mentre tu sei di livello `1`b".$session['user']['level']."`3`b.`n");
                output("Mi spiace ma non puoi combatterlo, il suo livello e' troppo `i`4".($diff?"basso":"alto")."`i`3 rispetto al tuo.`n");
                addnav("`@Torna alla Foresta","forest.php");
                $session['user']['specialinc']="";
            }
            else {
                output("`2$row[name]`3 è di livello `5`b$row[level]`3`b, mentre tu sei di livello `1`b".$session['user']['level']."`3`b.`n `4Cosa vuoi fare ?");
                addnav("b?`4Combattilo","uovapvp.php?op=attacco&id=$row[acctid]");
                addnav("L?`2Mhhhh ... Lascialo stare","uovapvp.php?op=lascia&id=$row[acctid]");
            }
        }
        $session['user']['specialinc']="";

    }
}
?>