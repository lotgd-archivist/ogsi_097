<?php
require_once("common.php");
require_once("common2.php");
$pvptime = getsetting("pvptimeout",600);
$pvptimeout = date("Y-m-d H:i:s",strtotime(date("r")."-$pvptime seconds"));
page_header("Combattimento PvP!");
if ($_GET['op']=="attacco"){
    $eggowner=$_GET['id'];
    $sql = "SELECT * FROM accounts WHERE acctid = $eggowner";
    $result = db_query($sql) or die(db_error(LINK));
    $row = db_fetch_assoc($result);
    checkday();
    pvpwarning();
    if ($row['alive']){
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
             jail,
             dragonkills,
             reincarna,
             dio,
             sex
             FROM accounts WHERE acctid = $eggowner";
        $result = db_query($sql) or die(db_error(LINK));
        if (db_num_rows($result)>0){
           $row = db_fetch_assoc($result);
           if (abs($session['user']['level']-$row['creaturelevel'])>2){
              output("`\$Errore:`4 Il livello di questo giocatore è troppo lontano dal tuo!");
           }elseif ($row['pvpflag'] > $pvptimeout){
              output("`\$Oops:`4 Giocatore attaccato di recente, dovrai riprovarci un'altra volta!");
           }else{
              if (strtotime($row['laston']) > strtotime(date("r")."-".getsetting("LOGINTIMEOUT",900)." sec") && $row['loggedin']){
                 output("`\$Errore:`4 Quel giocatore al momento è collegato.");
              }else{
                  if ((int)$row['location']!=0 && 0){
                     output("`\$Errore:`4 Quel giocatore non è in un posto in cui puoi attaccarlo.");
                  }else{
                     if((int)$row['alive']!=1){
                         output("`\$Errore:`4 Quel giocatore è già morto!");
                     }else{
                         if ($session['user']['playerfights']>0){
                            $sql = "UPDATE accounts SET pvpflag=now() WHERE acctid=".$row['acctid'];
                            db_query($sql);
                            $battle=true;
                            $row['pvp']=1;
                            $row['creatureexp'] = round($row['creatureexp'],0);
                            $row['playerstarthp'] = $session['user']['hitpoints'];
                            $session['user']['badguy']=createstring($row);
                            $session['user']['playerfights']--;
                            $session['user']['buffbackup']="";
                            pvpwarning(true);
                        }else{
                          output("`4Visto quanto sei stanco, pensi che sia meglio evitare un'altra battaglia giocatore contro giocatore per oggi.");
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
            addnav("`@Torna al Villaggio","village.php");
        }
    }else{
        output("`4Decidi di affrontare `6".$row['name']."`4 ma purtroppo è già morto !!");
        addnav("`@Torna al Villaggio","village.php");
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
            }elseif ($session['user']['dio'] == 1 AND $badguy['dio'] == 3) {
                $session['user']['history'] = "/me`^ ha sconfitto `2".$badguy['creaturename']."`^, un".($badguy['sex']?"a":"")." debole adorat".($badguy['sex']?"rice":"ore")." del `2Drago Verde`^, che tentava di convincerl".($session['user']['sex']?"a":"o")." della potenza del Drago.";
            }elseif ($session['user']['dio'] == 2 AND $badguy['dio'] == 1) {
                $session['user']['history'] = "/me`\$ ha massacrato `6".$badguy['creaturename']."`\$, un".($badguy['sex']?"a":"")." debole adorat".($badguy['sex']?"rice":"ore")." di `6Sgrios`\$, che tentava di convincerl".($session['user']['sex']?"a":"o")." a redimersi.";
            }elseif ($session['user']['dio'] == 2 AND $badguy['dio'] == 3) {
                $session['user']['history'] = "/me`\$ ha massacrato `2".$badguy['creaturename']."`\$, un".($badguy['sex']?"a":"")." debole adorat".($badguy['sex']?"rice":"ore")." del `2Drago Verde`\$, che tentava di convincerl".($session['user']['sex']?"a":"o")." della potenza del Drago.";
            }elseif ($session['user']['dio'] == 3 AND $badguy['dio'] == 1) {
                $session['user']['history'] = "/me`@ ha reso inerme `6".$badguy['creaturename']."`@, un".($badguy['sex']?"a":"")." debole adorat".($badguy['sex']?"rice":"ore")." di `6Sgrios`@, nel tentativo di convincerl".($session['user']['sex']?"a":"o")." della potenza del Drago.";
            }elseif ($session['user']['dio'] == 3 AND $badguy['dio'] == 2) {
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
        $session['user']['experience']+=($exp+$expbonus);
        output("`b`&".$badguy['creaturelose']."`0`b`n");
        output("`b`\$Hai ucciso ".$badguy['creaturename']."!`0`b`n");
        //output("`#Ricevi `^".$badguy['creaturegold']."`# pezzi d'oro!`n");
        if ($badguy['creaturegold']) output("`#Ricevi `^$oroguadagna`# pezzi d'oro!`n");
        // Bounty Check - Darrell Morrone
        if ($badguy['creaturebounty']>0){
            output("`#Ricevi inoltre per la taglia `^".$badguy['creaturebounty']."`# pezzi d'oro!`n");
            }
        // End Bounty Check - Darrell Morrone
        if ($expbonus>0){
          output("`#***A causa della difficoltà dello scontro, vieni premiato con un bonus di `^$expbonus`# punti esperienza!`n");
        }elseif ($expbonus<0){
          output("`#***Data la facilità dello scontro, vieni penalizzato di `^".abs($expbonus)."`# esperienza!`n");
        }
        output("Ricevi `^".($exp+$expbonus)."`# punti esperienza!`n`0");
        if ($badguy['location']==1){
            addnews("`4".$session['user']['name']."`3 ha sconfitto `4".$badguy['creaturename']."`3 intrufolandosi nella sua stanza alla locanda!");
            $killedin="`6nella Locanda";
            $killedin1="`6nella Locanda";
        }elseif ($badguy['location']==2){
            addnews("`4".$session['user']['name']."`3 ha sconfitto `4".$badguy['creaturename']."`3 intrufolandosi nella sua Tenuta!");
            $killedin="`6nella sua Tenuta";
            $killedin1="`6nella tua Tenuta";
        }elseif ($badguy['location']==3){
            addnews("`4".$session['user']['name']."`3 ha sconfitto `4".$badguy['creaturename']."`3 intrufolandosi nel dormitorio del Municipio!");
            $killedin="`6nel Dormitorio";
            $killedin1="`6nel Dormitorio";
        }elseif ($badguy['jail']==1){
            addnews("`4".$session['user']['name']."`3 ha sconfitto `4".$badguy['creaturename']."`3 intrufolandosi in Prigione!");
            $killedin="`6in Prigione";
            $killedin1="`6in Prigione";
        }else{
            addnews("`4".$session['user']['name']."`3 ha sconfitto `4".$badguy['creaturename']."`3 in un onorevole scontro nei campi.");
            $killedin="`@nei Campi";
            $killedin1="`@nei Campi";
        }

        if ($badguy['creaturegold']) {
            debuglog("`0guadagna ".$oroguadagna." oro per aver ucciso $killedin con PvP Uovo Nero",$badguy['acctid']);
            //$session['user']['gold']+=$badguy['creaturegold'];
            $session['user']['gold']+=$oroguadagna;
        }else {
            debuglog("`0uccide $killedin con PvP Uovo Nero",$badguy['acctid']);
        }
        // Add Bounty Gold - Darrell Morrone
        if ($badguy['creaturebounty']) {
            debuglog("`0guadagna ".$badguy['creaturebounty']." oro per di taglia per aver ucciso $killedin con PvP Uovo Nero",$badguy['acctid']);
            $session['user']['gold']+=$badguy['creaturebounty'];
        }

        // modifica di Excalibur per non aggiungere punti cattiveria se l'ucciso in locanda è ricercato
        if ($badguy['location']>0 && $badguy['evil']>99){
            $session['user']['evil']-=5;
        }
            // fine aggiunta di Excalibur
        // Add Bounty Kill to the News - Darrell Mororne
        if ($badguy['creaturebounty']>0){
            addnews("`4".$session['user']['name']."`3 riceve `4".$badguy['creaturebounty']." pezzi d'oro di taglia per la cattura di `4{$badguy['creaturename']}!");
            }
        // Golden Egg - anpera
      if ($badguy['acctid']==getsetting("hasblackegg",0)){
         savesetting("hasblackegg",stripslashes($session['user']['acctid']));
         output("`n`^NOOOOOOOOOOOO !!!!!! Hai preso a ".$badguy['creaturename']." `!l'Uovo Nero !!`0`n");
         addnews("`^".$session['user']['name']."`^ ha preso a ".$badguy['creaturename']."`^ l'Uovo d'Oro!");
        //Modifica per lotta tra seguaci Sgrios e Karnak
        if ($session['user']['dio']) {
            if ($session['user']['dio'] == 1 AND $badguy['dio'] == 2) {
                $session['user']['history'] = "/me`^ ha sconfitto`4 ".$badguy['creaturename']."`^, un".($badguy['sex']?"a":"")." malvagi".($badguy['sex']?"a":"o")." adorat".($badguy['sex']?"rice":"ore")." di `4Karnak`^, nella vana speranza di redimerl".($badguy['sex']?"a":"o").".";
            }elseif ($session['user']['dio'] == 1 AND $badguy['dio'] == 3) {
                $session['user']['history'] = "/me`^ ha sconfitto `2".$badguy['creaturename']."`^, un".($badguy['sex']?"a":"")." debole adorat".($badguy['sex']?"rice":"ore")." del `2Drago Verde`^, che tentava di convincerl".($session['user']['sex']?"a":"o")." della potenza del Drago.";
            }elseif ($session['user']['dio'] == 2 AND $badguy['dio'] == 1) {
                $session['user']['history'] = "/me`\$ ha massacrato `6".$badguy['creaturename']."`\$, un".($badguy['sex']?"a":"")." debole adorat".($badguy['sex']?"rice":"ore")." di `6Sgrios`\$, che tentava di convincerl".($session['user']['sex']?"a":"o")." a redimersi.";
            }elseif ($session['user']['dio'] == 2 AND $badguy['dio'] == 3) {
                $session['user']['history'] = "/me`\$ ha massacrato `2".$badguy['creaturename']."`\$, un".($badguy['sex']?"a":"")." debole adorat".($badguy['sex']?"rice":"ore")." del `2Drago Verde`\$, che tentava di convincerl".($session['user']['sex']?"a":"o")." della potenza del Drago.";
            }elseif ($session['user']['dio'] == 3 AND $badguy['dio'] == 1) {
                $session['user']['history'] = "/me`@ ha reso inerme `6".$badguy['creaturename']."`@, un".($badguy['sex']?"a":"")." debole adorat".($badguy['sex']?"rice":"ore")." di `6Sgrios`@, nel tentativo di convincerl".($session['user']['sex']?"a":"o")." della potenza del Drago.";
            }elseif ($session['user']['dio'] == 3 AND $badguy['dio'] == 2) {
                $session['user']['history'] = "/me`@ ha reso inerme `4".$badguy['creaturename']."`@, un".($badguy['sex']?"a":"")." malvagio adorat".($badguy['sex']?"rice":"ore")." di `4Karnak`@, nel tentativo di convincerl".($session['user']['sex']?"a":"o")." della potenza del Drago.";
            }
        }
        //Fine modifica lotta tra seguaci
      }
      //aggiunto by luke per prigione
      $sql = "SELECT evil FROM accounts WHERE acctid='".(int)$badguy['acctid']."'";
        $result = db_query($sql);
        $row = db_fetch_assoc($result);
        if ($row['evil'] > 99){
            $sql = "UPDATE accounts SET jail=1 WHERE acctid='".(int)$badguy['acctid']."'";
            db_query($sql) or die(db_error(LINK));
            addnews("`4".$session['user']['name']."`3 ha riportatato `4".$badguy['creaturename']." dallo Sceriffo!");
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
                ." `n`nCome risultato, hai perso il `\$".getsetting("pvpdeflose",5)."%`2 della tua esperienza (approssimativamente $lostexp punti), e `^".$badguy[creaturegold]."`2 pezzi d'oro. Ha anche ricevuto `^".$badguy[creaturebounty]." `2come taglia.";
        if($badguy['jail']!=1){
            $mailmessage.=" `n`nNon pensi sia il momento di vendicarsi?";
        }else{
            $mailmessage.=" `n`nPeccato tu non possa vendicarti al momento, visto che sei in prigione ... crudele il destino dei ricercati!!";
        }
        //$mailmessage = str_replace("%p",($session['user']['sex']?"her":"his"),$mailmessage);
        $mailmessage = str_replace("%o",($session['user']['sex']?"lei":"lui"),$mailmessage);
        systemmail($badguy['acctid'],"`2Sei stato ucciso $killedin1`2",$mailmessage);
        // /\- Gunnar Kreitz

        $sql = "UPDATE accounts SET alive=0, bounty=0, goldinbank=goldinbank-IF(gold<{$badguy['creaturegold']},gold-{$badguy['creaturegold']},0),gold=gold-{$badguy['creaturegold']}, experience=experience-$lostexp WHERE acctid=".(int)$badguy['acctid']."";
        db_query($sql);

        $_GET['op']="";
        if ($badguy['location']){
            addnav("T?`@Torna al Villaggio","village.php");
        } else {
            addnav("T?`@Torna al Villaggio","village.php");
        }
        $badguy=array();
    }else{
        if($defeat){
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
                }elseif ($session['user']['dio'] == 1 AND $badguy['dio'] == 3) {
                    $session['user']['history'] = "/me `6ha attaccato `@".$badguy['creaturename']."`6, seguace del `@Drago Verde`6, perdendo, nel vano tentativo di redimerl".($badguy['sex']?"a":"o").".";
                    $puntipersi = e_rand(1,10);
                    $session['user']['punti_carriera'] -= $puntipersi;
                    $session['user']['punti_generati'] -= $puntipersi;
                    $puntigain = e_rand(1,5);
                    $sqlpu = "UPDATE accounts SET punti_carriera=punti_carriera+$puntigain, punti_generati=punti_generati+$puntigain WHERE acctid = '{$badguy[acctid]}' ";
                    $resultpu = db_query($sqlpu) or die(db_error(LINK));
                    output("`^Purtroppo hai perso `\$$puntipersi `^ Punti Carriera nello scontro !!`n`n");
                }elseif ($session['user']['dio'] == 2 AND $badguy['dio'] == 1) {
                    $session['user']['history'] = "/me `4è stato massacrato da `^".$badguy['creaturename']."`4, un".($badguy['sex']?"a":"")." potente adorat".($badguy['sex']?"rice":"ore")." di `^Sgrios`4, quando l´ha attaccat".($badguy['sex']?"a":"o").".";
                    $puntipersi = e_rand(1,10);
                    $session['user']['punti_carriera'] -= $puntipersi;
                    $session['user']['punti_generati'] -= $puntipersi;
                    $puntigain = e_rand(1,5);
                    $sqlpu = "UPDATE accounts SET punti_carriera=punti_carriera+$puntigain, punti_generati=punti_generati+$puntigain WHERE acctid = '{$badguy[acctid]}' ";
                    $resultpu = db_query($sqlpu) or die(db_error(LINK));
                    output("`^Purtroppo hai perso `\$$puntipersi `^ Punti Carriera nello scontro !!`n`n");
                }elseif ($session['user']['dio'] == 2 AND $badguy['dio'] == 3) {
                    $session['user']['history'] = "/me `4è stato massacrato da `^".$badguy['creaturename']."`4, un".($badguy['sex']?"a":"")." potente seguace del `@Drago Verde`4, quando l´ha attaccat".($badguy['sex']?"a":"o").".";
                    $puntipersi = e_rand(1,10);
                    $session['user']['punti_carriera'] -= $puntipersi;
                    $session['user']['punti_generati'] -= $puntipersi;
                    $puntigain = e_rand(1,5);
                    $sqlpu = "UPDATE accounts SET punti_carriera=punti_carriera+$puntigain, punti_generati=punti_generati+$puntigain WHERE acctid = '{$badguy[acctid]}' ";
                    $resultpu = db_query($sqlpu) or die(db_error(LINK));
                    output("`^Purtroppo hai perso `\$$puntipersi `^ Punti Carriera nello scontro !!`n`n");
                }elseif ($session['user']['dio'] == 3 AND $badguy['dio'] == 1) {
                    $session['user']['history'] = "/me `2è stato reso inerme da `^".$badguy['creaturename']."`2, un".($badguy['sex']?"a":"")." potente adorat".($badguy['sex']?"rice":"ore")." di `^Sgrios`2, quando l´ha attaccat".($badguy['sex']?"a":"o").".";
                    $puntipersi = e_rand(1,10);
                    $session['user']['punti_carriera'] -= $puntipersi;
                    $session['user']['punti_generati'] -= $puntipersi;
                    $puntigain = e_rand(1,5);
                    $sqlpu = "UPDATE accounts SET punti_carriera=punti_carriera+$puntigain, punti_generati=punti_generati+$puntigain WHERE acctid = '{$badguy[acctid]}' ";
                    $resultpu = db_query($sqlpu) or die(db_error(LINK));
                    output("`^Purtroppo hai perso `\$$puntipersi `^ Punti Carriera nello scontro !!`n`n");
                }elseif ($session['user']['dio'] == 3 AND $badguy['dio'] == 2) {
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
            $taunt = str_replace("%s",($session['user']['sex']?"sua":"suo"),$taunt[taunt]);
            $taunt = str_replace("%o",($session['user']['sex']?"lei":"lui"),$taunt);
            $taunt = str_replace("%p",($session['user']['sex']?"her":"his"),$taunt);
            $taunt = str_replace("%x",($session['user']['weapon']),$taunt);
            $taunt = str_replace("%X",$badguy['creatureweapon'],$taunt);
            $taunt = str_replace("%W",$badguy['creaturename'],$taunt);
            $taunt = str_replace("%w",$session['user']['name'],$taunt);
            if ($badguy['location']==1){
                $killedin="`6nella Locanda";
                $killedin1="`6nella Locanda";
            }elseif ($badguy['location']==2){
                $killedin="`6nella sua Tenuta";
                $killedin1="`6nella tua Tenuta";
            }elseif ($badguy['location']==3){
                $killedin="`6nel Dormitorio";
                $killedin1="`6nel Dormitorio";
            }elseif ($badguy['jail']==1){
                $killedin="`6in Prigione";
                $killedin1="`6in Prigione";
            }else{
                $killedin="`@nei Campi";
                $killedin1="`@nei Campi";
            }
            $badguy['acctid']=(int)$badguy['acctid'];
            $badguy['creaturegold']=(int)$badguy['creaturegold'];
            // by Excalibur per evitare trasferimenti di soldi da un PG all'altro
            $goldperdk=2000*($session['user']['dragonkills']+$session['user']['reincarna']*19);
            if ($goldperdk>10000) $goldperdk=10000;
            if ($goldperdk==0) $goldperdk=2000;
            $goldgain=$session['user']['gold'];
            if ($goldgain>$goldperdk) $goldgain=$goldperdk;
            // fine modifica
            systemmail($badguy['acctid'],"`2Sei stato vittorioso $killedin1`2","`^".$session['user']['name']."`2 ti ha attaccato $killedin1`2, ma lo hai battuto!`n`nDi conseguenza, hai ricevuto `^".round($session['user']['experience']*getsetting("pvpdefgain",10)/100,0)."`2 punti esperienza e `^$goldgain`2 pezzi d'oro!");
            addnews("`%".$session['user']['name']."`5 è stato ucciso quando ha attaccato ".$badguy['creaturename']." $killedin`5.`n$taunt");
            $sql = "UPDATE accounts SET gold=gold+$goldgain, experience=experience+".round($session['user']['experience']*getsetting("pvpdefgain",10)/100,0)." WHERE acctid=".(int)$badguy['acctid']."";
            db_query($sql);
            $session['user']['alive']=false;
            debuglog("`0ha attaccato in PvP uovo Nero e ha perso ".$session['user']['gold']." oro, ucciso $killedin da", $badguy['acctid']);
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
if ($_GET['op']=="lascia"){
    $eggowner=$_GET['id'];
    $sql = "SELECT * FROM accounts WHERE acctid = $eggowner";
    $result = db_query($sql) or die(db_error(LINK));
    $row = db_fetch_assoc($result);
    output("`5Osservando i muscoli di `2".$row['name']."`5 vieni assalito dal terrore di morire ed inizi a fuggire tremante ");
    output("di paura. Ti rifugi in una radura del bosco e li passi gran parte della tua giornata.`n");
    $turnlost=e_rand(1,intval($session['user']['turns']/4));
    output("`!`bHai perso `6$turnlost `1combattimenti!!!`b`n");
    $session['user']['turns']-=$turnlost;
    addnews("`6".$session['user']['name']." `!è stato visto tremante di paura in una radura nel bosco");
    addnav("`@Torna al Villaggio","village.php");
    }
page_footer();
?>