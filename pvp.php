<?php
require_once("common.php");
require_once("common2.php");
$pvptime = getsetting("pvptimeout",600);
if ($_GET['op6']=="houses"){
    $pvptime=1;
    debuglog("ha ".$session['user']['playerfights']." PvP e attacca in una tenuta ".$_GET['name']);
}
$pvptimeout = date("Y-m-d H:i:s",strtotime(date("r")."-$pvptime seconds"));
page_header("Combattimento PvP!");
$session['user']['locazione'] = 161;
if ($_GET['op']=="" && $_GET['act']!="attack"){
    //if ($session['user']['age']<=5 && $session['user']['dragonkills']==0){
    //  output("`\$Warning!`^ Players are immune from Player vs Player (PvP) combat for their first 5 days in the game.  If you choose to attack another player, you will lose this immunity!`n`n");
    //}
    checkday();
    pvpwarning();
    output("`4Ti dirigi verso i campi, dove sai che alcuni guerrieri poco saggi stanno dormendo.`n`nHai `^".$session['user']['playerfights']."`4 combattimenti PvP rimasti per oggi.");
    addnav("E?`4Elenco Guerrieri","pvp.php?op=list");
    addnav("T?`3Torna al Villaggio","village.php");
}else if ($_GET['op']=="list"){
    checkday();
    pvpwarning();
    $days = getsetting("pvpimmunity", 5);
    $exp = getsetting("pvpminexp", 1500);
    $sql = "SELECT name,alive,location,sex,level,laston,loggedin,login,pvpflag,jail
          FROM accounts WHERE
          (locked=0 AND npg=0) AND
          (age > $days OR dragonkills > 0 OR pk > 0 OR reincarna > 0 OR experience > $exp) AND
          (level >= ".($session['user']['level']-1)." AND jail=0 AND level <= ".($session['user']['level']+2).") AND
          (alive=1 AND location=0) AND  superuser < 1 AND
          (lastip <> '".$session['user']['lastip']."') AND (emailaddress <> '".$session['user']['emailaddress']."') AND
          (laston < '".date("Y-m-d H:i:s",strtotime(date("r")."-".getsetting("LOGINTIMEOUT",900)." sec"))."' OR loggedin=0) AND
          (acctid <> ".$session['user']['acctid'].")
          ORDER BY level DESC";
    //echo ("<pre>$sql</pre>");
    $result = db_query($sql) or die(db_error(LINK));
    output("<table border='0' cellpadding='3' cellspacing='0'><tr><td>Nome</td><td>Livello</td><td>Ops</td></tr>",true);
    $countrow = db_num_rows($result);
    for ($i=0; $i<$countrow; $i++){
    //for ($i=0;$i<db_num_rows($result);$i++){
        $row = db_fetch_assoc($result);
        $biolink="bio.php?char=".rawurlencode($row['login'])."&ret=".urlencode($_SERVER['REQUEST_URI']);
        addnav("", $biolink);
        if($row['pvpflag']>$pvptimeout){
            output("<tr class='".($i%2?"trlight":"trdark")."'><td>".$row['name']."</td><td>".$row['level']."</td><td>[ <a href='$biolink'>Bio</a> | `i(Attaccato troppo recentemente)`i ]</td></tr>",true);
        }else{
            output("<tr class='".($i%2?"trlight":"trdark")."'><td>".$row['name']."</td><td>".$row['level']."</td><td>[ <a href='$biolink'>Bio</a> | <a href='pvp.php?act=attack&name=".rawurlencode($row[login])."'>Attacca</a> ]</td></tr>",true);
            addnav("","pvp.php?act=attack&name=".rawurlencode($row['login']));
        }
    }
    output("</table>",true);
    addnav("E?`4Elenco Guerrieri","pvp.php?op=list");
    addnav("`3Torna al Villaggio","village.php");
    if (getsetting("hasegg",0)>0 AND getsetting("hasblackegg",0)>0){
        if (e_rand(0,1) == 0){
            $uovo = "hasegg";
        }else{
            $uovo = "hasblackegg";
        }
        $sql = "SELECT name FROM accounts WHERE acctid = ".getsetting($uovo,0);
        $result = db_query($sql) or die(db_error(LINK));
        $row = db_fetch_assoc($result);
        output("`n`n".$row['name']." possiede il mitico `^Uovo d'Oro!");
    }elseif (getsetting("hasegg",0)>0){
        $sql = "SELECT name FROM accounts WHERE acctid = ".getsetting("hasegg",0);
        $result = db_query($sql) or die(db_error(LINK));
        $row = db_fetch_assoc($result);
        output("`n`n".$row['name']." possiede il mitico `^Uovo d'Oro!");
    }elseif (getsetting("hasblackegg",0)>0){
        $sql = "SELECT name FROM accounts WHERE acctid = ".getsetting("hasblackegg",0);
        $result = db_query($sql) or die(db_error(LINK));
        $row = db_fetch_assoc($result);
        output("`n`n".$row['name']." possiede il mitico `^Uovo d'Oro!");
    }
} else if ($_GET['act'] == "attack") {
    if ($_GET['op5'] == "locanda") {
        $session['user']['evil']+=5;
    }
    $sql = "SELECT name AS creaturename,
                   level AS creaturelevel,
                                 weapon AS creatureweapon,
                                 gold AS creaturegold,
                                 experience AS creatureexp,
                                 maxhitpoints AS creaturehealth,
                                 attack AS creatureattack,
                                 defence AS creaturedefense,
                                 bounty AS creaturebounty,
                                 loggedin,
                                 location,
                                 laston,
                                 alive,
                                 acctid,
                                 pvpflag,
                                 evil,
                                 dragonkills,
                                 reincarna,
                                 dio,
                                 sex
                    FROM accounts
                    WHERE login=\"".$_GET['name']."\"";
    if ($_GET['op6'] == "houses") {
        $session['user']['evil']+=3;
        $sql = "SELECT name AS creaturename,
          level AS creaturelevel,
          weapon AS creatureweapon,
          gold AS creaturegold,
          experience AS creatureexp,
          hitpointspvp AS creaturehealth,
          attack AS creatureattack,
          defence AS creaturedefense,
          bounty AS creaturebounty,
          loggedin,
          location,
          laston,
          alive,
          acctid,
          house,
          pvpflag,
          evil,
          dragonkills,
          reincarna,
          dio,
          sex
          FROM accounts
          WHERE login=\"".$_GET['name']."\"";
    }
    $result = db_query($sql) or die(db_error(LINK));
    if (db_num_rows($result)>0){
        $row = db_fetch_assoc($result);
        if (abs($session['user']['level']-$row['creaturelevel'])>2 && $row['location']!=2){
            output("`\$Errore:`4 Il livello di questo giocatore è troppo lontano dal tuo!");
        }elseif ($row['pvpflag'] > $pvptimeout){
            output("`\$Oops:`4 Quel giocatore al momento sta combattendo qualcun altro, dovrai aspettare il tuo turno! `n`\$".$row['pvpflag']." : $pvptimeout");
        }else{
            if (strtotime($row['laston']) > strtotime(date("r")."-".getsetting("LOGINTIMEOUT",900)." sec") && $row['loggedin']){
                output("`\$Errore:`4 Quel giocatore al momento è collegato.");
            }else{
                if ((int)$row['location']!=0 && 0 && $row['location']!=2){
                    output("`\$Errore:`4 Quel giocatore non è in un posto in cui puoi attaccarlo.");
                }else{
                    if((int)$row['alive']!=1){
                        output("`\$Errore:`4 Quel giocatore è già morto!");
                    }else{
                        if ($session['user']['playerfights']>0 OR $_GET['op6']=="houses"){
                            $sql = "UPDATE accounts SET pvpflag=now() WHERE acctid=".$row['acctid'];
                            db_query($sql);
                            $battle=true;
                            $row['pvp']=1;
                            $row['creatureexp'] = round($row['creatureexp'],0);
                            $row['playerstarthp'] = $session['user']['hitpoints'];
                            $session['user']['badguy']=createstring($row);
                            if ($_GET['op6']!="houses") $session['user']['playerfights']--;
                            $session['user']['buffbackup']="";
                            pvpwarning(true);
                        }else{
                            output("`4Visto quanto sei stanco, pensi che sia meglio evitare un altro PvP per oggi.");
                        }
                    }
                }
            }
        }
    }else{
        output("`\$Errore:`4 Utente non trovato! E come ci sei arrivato qui, poi?");
    }
    if ($battle){

    }else{
        addnav("T?`3Torna al Villaggio","village.php");
    }
}
if ($_GET['op']=="run"){
    output("Sarebbe disonorevole fuggire");
    $_GET['op']="fight";
}
if ($_GET['skill']!=""){
    output("Sarebbe disonorevole usare un'abilità speciale");
    $_GET['skill']="";
}
if ($_GET['op']=="fight" || $_GET['op']=="run"){
    $battle=true;
}
if ($battle){
    include("battle.php");
    if ($victory){
        //Excalibur: gemma per aver ucciso player con ban dalle chiese
        $sqlpun = "SELECT * FROM punizioni_chiese WHERE acctid = ".$badguy['acctid']." AND fede = ".$badguy['dio'];
        $resultpun = db_query($sqlpun) or die(db_error(LINK));
        $refpun = db_fetch_assoc($resultpun);
        if(db_num_rows($resultpun)>0) {
            output("`^Per aver ucciso un seguace ostracizzato dai suoi stessi simili, guadagni una gemma!!`n");
            $session['user']['gems']++;
        }
        //Modifica per lotta tra seguaci Sgrios e Karnak
        if ($session['user']['dio']) {
            if ($session['user']['dio'] == 1 AND $badguy['dio'] == 2) {
                $session['user']['history'] = "/me`^ ha sconfitto`4 ".$badguy['creaturename']."`^, un".($badguy['sex']?"a":"")." malvagi".($badguy['sex']?"a":"o")." adorat".($badguy['sex']?"rice":"ore")." di `4Karnak`^, nella vana speranza di redimerl".($badguy['sex']?"a":"o").".";
            }else if ($session['user']['dio'] == 1 AND $badguy['dio'] == 3) {
                $session['user']['history'] = "/me`^ ha sconfitto `2".$badguy['creaturename']."`^, un".($badguy['sex']?"a":"")." debole adorat".($badguy['sex']?"rice":"ore")." del `2Drago Verde`^, che tentava di convincerl".($session['user']['sex']?"a":"o")." della potenza del Drago.";
            }else if ($session['user']['dio'] == 2 AND $badguy['dio'] == 1) {
                $session['user']['history'] = "/me`\$ ha massacrato `6".$badguy['creaturename']."`\$, un".($badguy['sex']?"a":"")." debole adorat".($badguy['sex']?"rice":"ore")." di `6Sgrios`\$, che tentava di convincerl".($session['user']['sex']?"a":"o")." a redimersi.";
            }else if ($session['user']['dio'] == 2 AND $badguy['dio'] == 3) {
                $session['user']['history'] = "/me`\$ ha massacrato `2".$badguy['creaturename']."`\$, un".($badguy['sex']?"a":"")." debole adorat".($badguy['sex']?"rice":"ore")." del `2Drago Verde`\$, che tentava di convincerl".($session['user']['sex']?"a":"o")." della potenza del Drago.";
            }else if ($session['user']['dio'] == 3 AND $badguy['dio'] == 1) {
                $session['user']['history'] = "/me`@ ha reso inerme `6".$badguy['creaturename']."`@, un".($badguy['sex']?"a":"")." debole adorat".($badguy['sex']?"rice":"ore")." di `6Sgrios`@, nel tentativo di convincerl".($session['user']['sex']?"a":"o")." della potenza del Drago.";
            }else if ($session['user']['dio'] == 3 AND $badguy['dio'] == 2) {
                $session['user']['history'] = "/me`@ ha reso inerme `4".$badguy['creaturename']."`@, un".($badguy['sex']?"a":"")." malvagio adorat".($badguy['sex']?"rice":"ore")." di `4Karnak`@, nel tentativo di convincerl".($session['user']['sex']?"a":"o")." della potenza del Drago.";
            }
        }
        //Fine modifica lotta tra seguaci
        //$badguy[creaturegold]=e_rand(0,$badguy[creaturegold]);
        $exp = round(getsetting("pvpattgain",10)*$badguy['creatureexp']/100,0);
        $expbonus = round(($exp * (1+.1*($badguy['creaturelevel']-$session['user']['level']))) - $exp,0);
        //by Excalibur per limitare l'oro che puo' essere rubato ad un PG
        $goldperdk=2000*($session['user']['dragonkills']+$session['user']['reincarna']*19);
        if ($goldperdk>10000) $goldperdk=10000;
        if ($goldperdk==0) $goldperdk=2000;
        if ($badguy['creaturegold']>$goldperdk){
            $oroguadagna=$goldperdk;
        }else $oroguadagna=$badguy['creaturegold'];
        // fine modifica by Excalibur
        output("`b`&".$badguy['creaturelose']."`0`b`n");
        output("`b`\$Hai ucciso ".$badguy['creaturename']."!`0`b`n");
        if ($badguy['creaturegold']) output("`#Ricevi `^$oroguadagna`# pezzi d'oro!`n");
        // Bounty Check - Darrell Morrone
        if ($badguy['creaturebounty']>0){
            output("`#Ricevi inoltre per la taglia `^".$badguy['creaturebounty']."`# pezzi d'oro!`n");
        }
        // End Bounty Check - Darrell Morrone

        //Modifica per exp in base ai DK by Excalibur
        $dkills1=($session['user']['reincarna']*19)+$session['user']['dragonkills'];
        $dkills2=($badguy['reincarna']*19)+$badguy['dragonkills'];
        $dkdiff=$dkills1-$dkills2;
        //output("DK player: $dkills1 `nDK opponente: $dkills2`n");
        if ($dkdiff>20){
            output("`n`4Ma non ti vergogni ad attaccare un guerriero di livello così basso ?? Hai paura ad affrontare i tuoi pari ??`n");
            output("C'è una differenza di`6`b $dkdiff `b`2Dragon Kill `4 tra te ed il tuo avversario. Per il tuo gesto vile vieni penalizzato di `6`b$exp `b`4punti esperienza.`n");
            $session['user']['experience']-=$exp;
            if ($expbonus>0){
                output("`2A parziale scusante del tuo gesto, il tuo avversario era di livello superiore e quindi guadagni comunque ");
                output("`6$expbonus `2punti esperienza.");
            }
            else if ($expbonus<0){
                output("`4Inoltre era di livello inferiore al tuo, e pertanto vieni penalizzato di altri `6`b".abs($expbonus)." `b`4punti esperienza!`n");
            }
            $session['user']['experience']+=$expbonus;
        }else if ($dkdiff>15 && $dkdiff<21){
            output("`n`5A causa della differenza di titolo tra te ed il tuo avversario, lo scontro era già deciso in partenza. `n");
            output("Pertanto non hai guadagnato nessun punto esperienza. La prossima volta affronta guerrieri del tuo livello, `n");
            output("e lascia in pace i pivelli. `n");
            $exp=0;
            if ($expbonus>0){
                output("`2A parziale scusante del tuo gesto, il tuo avversario era di livello superiore e quindi guadagni comunque ");
                output("`6$expbonus `2punti esperienza.");
            }
            else if ($expbonus<0){
                output("`4Inoltre era di livello inferiore al tuo, e pertanto vieni penalizzato `6`b".abs($expbonus)." `b`4punti esperienza!");
            }
            $session['user']['experience']+=$expbonus;
        }else if ($dkdiff>10 && $dkdiff<16){
            if ($dkdiff==11) $perc=0.85;
            if ($dkdiff==12) $perc=0.70;
            if ($dkdiff==13) $perc=0.55;
            if ($dkdiff==14) $perc=0.40;
            if ($dkdiff==15) $perc=0.20;
            $expnew=intval($perc*$exp);
            output("`n`3A causa della differenza di Dragon Kill tra te ed il tuo avversario l'esperienza che hai guadagnato si `n");
            output("riduce da `&`b$exp `b`3a `6`b$expnew `b`3punti esperienza.`n");
            if ($expbonus>0){
                output("`#***A causa della difficoltà dello scontro, vieni premiato con un bonus di `^$expbonus`# punti esperienza!`n");
            }else if ($expbonus<0){
                output("`#***Data la facilità dello scontro, vieni penalizzato di `^".abs($expbonus)."`# esperienza!`n");
            }
            output("Ricevi `^".($expnew+$expbonus)."`# punti esperienza!`n`0");
            $session['user']['experience']+=($expnew+$expbonus);
        }else if ($dkdiff<=10){
            if ($expbonus>0){
                output("`#***A causa della difficoltà dello scontro, vieni premiato con un bonus di `^$expbonus`# punti esperienza!`n");
            }else if ($expbonus<0){
                output("`n`#***Data la facilità dello scontro, vieni penalizzato di `^".abs($expbonus)."`# esperienza!`n");
            }
            output("Ricevi `^".($exp+$expbonus)."`# punti esperienza!`n`0");
            $session['user']['experience']+=($exp+$expbonus);
        }

        // modifica di Excalibur per non aggiungere punti cattiveria se l'ucciso in locanda è ricercato
        if ($badguy['location']>0 && $badguy['evil']>99){
            $session['user']['evil']-=5;
        }
        // fine aggiunta di Excalibur

        if ($badguy['location']==1){
            addnews("`4".$session['user']['name']."`3 ha sconfitto `4".$badguy['creaturename']."`3 intrufolandosi nella sua stanza alla locanda!");
            $killedin="`6nella Locanda";
            $killedin1="`6nella Locanda";
        }else if ($badguy['location']==2){
            $sqlhouse = "SELECT a.login, h.housename
                         FROM houses h
                         LEFT JOIN accounts a ON (a.acctid = h.owner)
                         WHERE ".$session['housekey']." = h.houseid";
            //echo "Query casa: ".$sqlhouse;
            $resulthouse = db_query($sqlhouse);
            $rowhouse = db_fetch_assoc($resulthouse);
            if ($badguy['house'] == $session['housekey']){
               $killedin="`6nella sua Tenuta `G".$rowhouse['housename'];
               $killedin1="`6nella tua Tenuta `G".$rowhouse['housename'];
            }else{
               $killedin="`6nella Tenuta `G".$rowhouse['housename']." `6di `@".$rowhouse['login'];
               $killedin1="`6nella Tenuta `G".$rowhouse['housename']." `6di `@".$rowhouse['login'];
            }
            addnews("`g".$session['user']['name']."`g ha sconfitto `X".$badguy['creaturename']."`g intrufolandosi ".$killedin."!!");
        }else if ($badguy['location']==3){
            addnews("`4".$session['user']['name']."`3 ha sconfitto `4".$badguy['creaturename']."`3 intrufolandosi nel dormitorio del Municipio!");
            $killedin="`6nel Dormitorio";
            $killedin1="`6nel Dormitorio";
        }else{
            addnews("`4".$session['user']['name']."`3 ha sconfitto `4".$badguy['creaturename']."`3 in un onorevole scontro nei campi.");
            $killedin="`@nei Campi";
            $killedin1="`@nei Campi";
        }

        if ($badguy['creaturegold']) {
            debuglog("`0guadagna $oroguadagna oro per aver ucciso $killedin con PvP Classico",$badguy['acctid']);
            $session['user']['gold']+=$oroguadagna;
        }else {
            debuglog("`0uccide $killedin con PvP Classico",$badguy['acctid']);
        }
        // Add Bounty Gold - Darrell Morrone
        if ($badguy['creaturebounty']) {
            debuglog("`0guadagna ".$badguy['creaturebounty']." oro di taglia per aver ucciso $killedin con PvP Classico",$badguy['acctid']);
            $session['user']['gold']+=$badguy['creaturebounty'];
        }

        // Add Bounty Kill to the News - Darrell Mororne
        if ($badguy['creaturebounty']>0){
            addnews("`4".$session['user']['name']."`3 riceve `4".$badguy['creaturebounty']." pezzi d'oro di taglia per la cattura di `4{$badguy['creaturename']}!");
        }
        // Golden Egg - anpera
        if ($badguy['acctid']==getsetting("hasegg",0)){
            savesetting("hasegg",stripslashes($session['user']['acctid']));
            output("`n`^Hai preso a ".$badguy['creaturename']." `^l'Uovo d'Oro!`0`n");
            addnews("`^".$session['user']['name']."`^ ha preso a ".$badguy['creaturename']."`^ l'Uovo d'Oro!");
            debuglog("ha preso a ".$badguy['creaturename']." l'Uovo d'Oro con PvP Classico");
        }
        // Uovo Nero - Excalibur
        if ($badguy['acctid']==getsetting("hasblackegg",0)){
            savesetting("hasblackegg",stripslashes($session['user']['acctid']));
            output("`n`^Hai preso a ".$badguy['creaturename']." `!l'Uovo Nero!`0`n");
            output("Nessun altro, oltre a te saprà che sei in possesso di questo malefico uovo. `nAugurati di perderlo quanto prima !!!");
            addnews("`^".$session['user']['name']."`^ ha preso a ".$badguy['creaturename']."`^ l'Uovo d'Oro!");
            debuglog("ha preso a ".$badguy['creaturename']." l'Uovo Nero con PvP Classico");
        }
        //aggiunto by luke per prigione
        $sql = "SELECT evil FROM accounts WHERE acctid='".(int)$badguy['acctid']."'";
        $result = db_query($sql);
        $row = db_fetch_assoc($result);
        if ($row[evil] > 99){
            $sql = "UPDATE accounts SET jail=1 WHERE acctid='".(int)$badguy['acctid']."'";
            db_query($sql) or die(db_error(LINK));
            addnews("`4".$session['user']['name']."`3 ha riportato `4".$badguy['creaturename']." dallo Sceriffo!");
        }
        $sql = "SELECT jail FROM accounts WHERE acctid='".(int)$badguy['acctid']."'";
        $result = db_query($sql);
        $row = db_fetch_assoc($result);
        if ($result==1) addnews("`4".$badguy['creaturename']." deve essere fuggito di prigione.");
        //fine aggiunto by luke per prigione
        $sql = "SELECT gold FROM accounts WHERE acctid='".(int)$badguy['acctid']."'";
        $result = db_query($sql);
        $row = db_fetch_assoc($result);
        $badguy['creaturegold']=((int)$row['gold']>(int)$badguy['creaturegold']?(int)$badguy['creaturegold']:(int)$row['gold']);
        //$sql = "UPDATE accounts SET alive=0, killedin='$killedin', goldinbank=goldinbank-IF(gold<$badguy[creaturegold],gold-$badguy[creaturegold],0),gold=gold-$badguy[creaturegold], experience=experience*.95, slainby=\"".addslashes($session[user][name])."\" WHERE acctid=$badguy[acctid]";
        // \/- Gunnar Kreitz
        $lostexp = round($badguy['creatureexp']*getsetting("pvpdeflose",5)/100,0);
        $mailmessage = "`^".$session['user']['name']."`2 ti ha attaccato $killedin1`2 con la sua `^".$session['user']['weapon']."`2, e ti ha sconfitto!"
        ." `n`nHai notato che %o aveva un massimo di HitPoints di `^".$badguy['playerstarthp']."`2 e subito prima che tu morissi gliene restavano `^".$session['user']['hitpoints']."`2."
        ." `n`nCome risultato, hai perso il `\$".getsetting("pvpdeflose",5)."%`2 della tua esperienza (approssimativamente $lostexp punti), e `^".$badguy[creaturegold]."`2 pezzi d'oro. Ha anche ricevuto `^".$badguy[creaturebounty]." `2come taglia."
        ." `n`nNon pensi sia il momento di vendicarsi?";
        //$mailmessage = str_replace("%p",($session['user']['sex']?"her":"his"),$mailmessage);
        $mailmessage = str_replace("%o",($session['user']['sex']?"lei":"lui"),$mailmessage);
        systemmail($badguy['acctid'],"`2Ucciso $killedin1`2",$mailmessage);
        // /\- Gunnar Kreitz

        //$sql = "UPDATE accounts SET alive=0, bounty=0, goldinbank=goldinbank-IF(gold<$badguy[creaturegold],gold-$badguy[creaturegold],0),gold=gold-$badguy[creaturegold], experience=experience-$lostexp WHERE acctid=".(int)$badguy[acctid]."";
        // Modifica per case
        $sql = "UPDATE accounts SET hitpointspvp =".$badguy['creaturehealth'].", alive=0, bounty=0, goldinbank=goldinbank-IF(gold<$badguy[creaturegold],gold-$badguy[creaturegold],0),gold=gold-$badguy[creaturegold], experience=experience-$lostexp WHERE acctid=".(int)$badguy[acctid]."";
        db_query($sql);
        //contatore PVP vinti & persi
        $session['user']['pvpkills']++;
        $sql = "UPDATE accounts SET pvplost=pvplost+1 WHERE acctid=".(int)$badguy['acctid']."";
        db_query($sql);
        //fine contatore
        $_GET['op']="";
        if ($badguy['location']==1){
            addnav("T?`3Torna alla Locanda","inn.php");
        }else if ($badguy['location']==2){
            $fight=false;
            addnav("T?`3Torna alla Casa","houses.php?op=breakin2&id=".$session['housekey']);
        }else {
            addnav("T?`3Torna al Villaggio","village.php");
        }
        $badguy=array();
    }else{
        if($defeat){
            //Excalibur: Modifica per PvP Houses
            $sqlh = "UPDATE accounts SET hitpointspvp = '".$badguy['creaturehealth']."' WHERE acctid = '".$badguy['acctid']."'";
            $resulth = db_query($sqlh) or die(db_error(LINK));
            //Fine Modifica

            //Excalibur: Modifica per lotta tra seguaci Sgrios, Karnak  e Drago Verde
            if ($session['user']['dio']){
                if ($session['user']['dio'] == 1 AND $badguy['dio'] == 2) {
                    $session['user']['history'] = "/me `6ha attaccato `\$".$badguy['creaturename']."`6, adorat".($badguy['sex']?"rice":"ore")." di `\$Karnak`6, perdendo, nel vano tentativo di redimerl".($badguy['sex']?"a":"o").".";
                    $puntipersi = e_rand(1,10);
                    $session['user']['punti_carriera'] -= $puntipersi;
                    $session['user']['punti_generati'] -= $puntipersi;
                    $puntigain = e_rand(1,5);
                    $sqlpu = "UPDATE accounts SET punti_carriera=punti_carriera+$puntigain, punti_generati=punti_generati+$puntigain WHERE acctid = '{$badguy[acctid]}' ";
                    $resultpu = db_query($sqlpu) or die(db_error(LINK));
                    output("`^Purtroppo hai perso `\$$puntipersi `^ Punti Carriera nello scontro !!`n`n");
                }else if ($session['user']['dio'] == 1 AND $badguy['dio'] == 3) {
                    $session['user']['history'] = "/me `6ha attaccato `@".$badguy['creaturename']."`6, seguace del `@Drago Verde`6, perdendo, nel vano tentativo di redimerl".($badguy['sex']?"a":"o").".";
                    $puntipersi = e_rand(1,10);
                    $session['user']['punti_carriera'] -= $puntipersi;
                    $session['user']['punti_generati'] -= $puntipersi;
                    $puntigain = e_rand(1,5);
                    $sqlpu = "UPDATE accounts SET punti_carriera=punti_carriera+$puntigain, punti_generati=punti_generati+$puntigain WHERE acctid = '{$badguy[acctid]}' ";
                    $resultpu = db_query($sqlpu) or die(db_error(LINK));
                    output("`^Purtroppo hai perso `\$$puntipersi `^ Punti Carriera nello scontro !!`n`n");
                }else if ($session['user']['dio'] == 2 AND $badguy['dio'] == 1) {
                    $session['user']['history'] = "/me `4è stato massacrato da `^".$badguy['creaturename']."`4, un".($badguy['sex']?"a":"")." potente adorat".($badguy['sex']?"rice":"ore")." di `^Sgrios`4, quando l´ha attaccat".($badguy['sex']?"a":"o").".";
                    $puntipersi = e_rand(1,10);
                    $session['user']['punti_carriera'] -= $puntipersi;
                    $session['user']['punti_generati'] -= $puntipersi;
                    $puntigain = e_rand(1,5);
                    $sqlpu = "UPDATE accounts SET punti_carriera=punti_carriera+$puntigain, punti_generati=punti_generati+$puntigain WHERE acctid = '{$badguy[acctid]}' ";
                    $resultpu = db_query($sqlpu) or die(db_error(LINK));
                    output("`^Purtroppo hai perso `\$$puntipersi `^ Punti Carriera nello scontro !!`n`n");
                }else if ($session['user']['dio'] == 2 AND $badguy['dio'] == 3) {
                    $session['user']['history'] = "/me `4è stato massacrato da `^".$badguy['creaturename']."`4, un".($badguy['sex']?"a":"")." potente seguace del `@Drago Verde`4, quando l´ha attaccat".($badguy['sex']?"a":"o").".";
                    $puntipersi = e_rand(1,10);
                    $session['user']['punti_carriera'] -= $puntipersi;
                    $session['user']['punti_generati'] -= $puntipersi;
                    $puntigain = e_rand(1,5);
                    $sqlpu = "UPDATE accounts SET punti_carriera=punti_carriera+$puntigain, punti_generati=punti_generati+$puntigain WHERE acctid = '{$badguy[acctid]}' ";
                    $resultpu = db_query($sqlpu) or die(db_error(LINK));
                    output("`^Purtroppo hai perso `\$$puntipersi `^ Punti Carriera nello scontro !!`n`n");
                }else if ($session['user']['dio'] == 3 AND $badguy['dio'] == 1) {
                    $session['user']['history'] = "/me `2è stato reso inerme da `^".$badguy['creaturename']."`2, un".($badguy['sex']?"a":"")." potente adorat".($badguy['sex']?"rice":"ore")." di `^Sgrios`2, quando l´ha attaccat".($badguy['sex']?"a":"o").".";
                    $puntipersi = e_rand(1,10);
                    $session['user']['punti_carriera'] -= $puntipersi;
                    $session['user']['punti_generati'] -= $puntipersi;
                    $puntigain = e_rand(1,5);
                    $sqlpu = "UPDATE accounts SET punti_carriera=punti_carriera+$puntigain, punti_generati=punti_generati+$puntigain WHERE acctid = '{$badguy[acctid]}' ";
                    $resultpu = db_query($sqlpu) or die(db_error(LINK));
                    output("`^Purtroppo hai perso `\$$puntipersi `^ Punti Carriera nello scontro !!`n`n");
                }else if ($session['user']['dio'] == 3 AND $badguy['dio'] == 2) {
                    $session['user']['history'] = "/me `2è stato reso inerme da `\$".$badguy['creaturename']."`2, un".($badguy['sex']?"a":"")." potente seguace di `\$Karnak`2, quando l´ha attaccat".($badguy['sex']?"a":"o").".";
                    $puntipersi = e_rand(1,10);
                    $session['user']['punti_carriera'] -= $puntipersi;
                    $session['user']['punti_generati'] -= $puntipersi;
                    $puntigain = e_rand(1,5);
                    $sqlpu = "UPDATE accounts SET punti_carriera=punti_carriera+$puntigain, punti_generati=punti_generati+$puntigain WHERE acctid = '{$badguy[acctid]}' ";
                    $resultpu = db_query($sqlpu) or die(db_error(LINK));
                    output("`^Purtroppo hai perso `\$$puntipersi `^ Punti Carriera nello scontro !!`n`n");
                }
                if ($session['user']['punti_carriera'] < 0) $session['user']['punti_carriera'] = 0;
            }
            //Fine modifica lotta tra seguaci

            addnav("Notizie Giornaliere","news.php");
            $sql = "SELECT taunt FROM taunts ORDER BY rand(".e_rand().") LIMIT 1";
            $result = db_query($sql) or die(db_error(LINK));
            $taunt = db_fetch_assoc($result);
            $taunt = str_replace("%s",($session['user']['sex']?"sua":"suo"),$taunt['taunt']);
            $taunt = str_replace("%o",($session['user']['sex']?"lei":"lui"),$taunt);
            $taunt = str_replace("%p",($session['user']['sex']?"her":"his"),$taunt);
            $taunt = str_replace("%x",($session['user']['weapon']),$taunt);
            $taunt = str_replace("%X",$badguy['creatureweapon'],$taunt);
            $taunt = str_replace("%W",$badguy['creaturename'],$taunt);
            $taunt = str_replace("%w",$session['user']['name'],$taunt);
            if ($badguy['location']==1){
                $killedin="`6nella Locanda";
                $killedin1="`6nella Locanda";
            }else if ($badguy['location']==2){
                  $sqlhouse = "SELECT a.login, h.housename
                               FROM houses h
                               LEFT JOIN accounts a ON (a.acctid = h.owner)
                               WHERE ".$session['housekey']." = h.houseid";
                  //echo "Query casa: ".$sqlhouse;
                  $resulthouse = db_query($sqlhouse);
                  $rowhouse = db_fetch_assoc($resulthouse);
                  if ($badguy['house'] == $session['housekey']){
                     $killedin="`6nella sua Tenuta `G".$rowhouse['housename'];
                     $killedin1="`6nella tua Tenuta `G".$rowhouse['housename'];
                  }else{
                     $killedin="`6nella Tenuta `G".$rowhouse['housename']." `6di `@".$rowhouse['login'];
                     $killedin1="`6nella Tenuta `G".$rowhouse['housename']." `6di `@".$rowhouse['login'];
                  }
                //$killedin="`6nella sua Tenuta";
                //$killedin1="`6nella tua Tenuta";
            }else if ($badguy['location']==3){
                $killedin="`6nel Dormitorio";
                $killedin1="`6nel Dormitorio";
            }else{
                $killedin="`@nei Campi";
                $killedin1="`@nei Campi";
            }
            $badguy['acctid']=(int)$badguy['acctid'];
            $badguy['creaturegold']=(int)$badguy['creaturegold'];
            // by Excalibur per evitare trasferimenti di soldi da un PG all'altro
            $goldperdk=1000*($session['user']['dragonkills']+$session['user']['reincarna']*19);
            if ($goldperdk>5000) $goldperdk=5000;
            if ($goldperdk==0) $goldperdk=1000;
            $goldgain=$session['user']['gold'];
            if ($goldgain>$goldperdk) $goldgain=$goldperdk;
            // fine modifica
            systemmail($badguy['acctid'],"`2Vittoria $killedin1`2","`^".$session['user']['name']."`2 ti ha attaccato $killedin1`2, ma lo hai battuto!`n`nDi conseguenza, hai ricevuto `^".round($session['user']['experience']*getsetting("pvpdefgain",10)/100,0)."`2 punti esperienza e `^$goldgain`2 pezzi d'oro!");
            addnews("`%".$session['user']['name']."`5 è stato ucciso quando ha attaccato ".$badguy['creaturename']." $killedin`5.`n$taunt");
            $sql = "UPDATE accounts SET gold=gold+$goldgain, pvpkills=pvpkills+1, experience=experience+".round($session['user']['experience']*getsetting("pvpdefgain",10)/100,0)." WHERE acctid=".(int)$badguy['acctid']."";
            db_query($sql);
            $session['user']['pvplost'] += 1;
            $session[user][alive]=false;
            debuglog("`0ha attaccato in PvP classico e ha perso ".$session['user']['gold']." oro, ucciso $killedin da", $badguy['acctid']);
            $session['user']['gold']=0;
            $session['user']['hitpoints']=0;
            $session['user']['experience']=round($session['user']['experience']*(100-getsetting("pvpattlose",15))/100,0);
            $session['user']['badguy']="";
            output("`b`&Sei stato ucciso da `%".$badguy['creaturename']."`&!!!`n");
            output("`4Hai perso tutto l'oro che avevi con te!`n");
            output("`4".getsetting("pvpattlose",15)."% della tua esperienza è andato perduto!`n");
            output("Potrai ricominciare a combattere domani.");

            page_footer();
        }else{
            fightnav(false,false);
        }
    }
}
page_footer();
?>