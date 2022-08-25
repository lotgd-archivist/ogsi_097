<?php
require_once("common.php");
require_once("common2.php");
checkday();
page_header("La Palestra di Swarzy");

output("`b`c`@La Palestra di Swarzy`c`b");
$session['user']['locazione'] = 180;
$sql = "SELECT * FROM masters WHERE creaturelevel = ".$session['user']['level'];
$result = db_query($sql) or die(sql_error($sql));
if (db_num_rows($result) > 0){
    $master = db_fetch_assoc($result);
    $level = $session['user']['level'];
    $exparray=array(1=>100,400,1002,1912,3140,4707,6641,8985,11795,15143,19121,23840,29437,36071,43930);
    while (list($key,$val)=each($exparray)){
        $exparray[$key]= round(
            $val + ($session['user']['dragonkills']/4) * $session['user']['level'] * 100
        ,0);
    }
    $exprequired=$exparray[$session['user']['level']];
    //output("`\$Exp Required: $exprequired; exp possessed: ".$session[user][experience]."`0`n");

    if ($_GET['op']==""){
        output("Un cartello sulla porta della palestra dice che la palestra è chiusa, prova a tornare domani. ");
        //output("Il suono degli scontri ti circonda. Il rumore metallico delle armi ispira il tuo cuore di guerriero. ");
        //output("`n`nIl tuo maestro è `^$master[creaturename]`0.");
        //if ($session['user']['dragonkills']<5)
        //        addnav("Allenati per un po'","swarzy1.php");
        //addnav("Interroga il Maestro","swarzy.php?op=question");
        //addnav("Sfida il Maestro","swarzy.php?op=challenge");
        //if ($session['user']['superuser'] > 2) {
        //    addnav("Superuser Gain level","swarzy.php?op=challenge&victory=1");
        //}
        addnav("Torna al Castello","castelexcal.php");
        addnav("Torna al Villaggio","village.php");
    }else if($_GET['op']=="challenge"){
        if ($_GET['victory']) {
            $victory=true;
            $defeat=false;
            if ($session['user']['experience'] < $exprequired)
                $session['user']['experience'] = $exprequired;
            $session['user']['seenmaster'] = 0;
        }
        if ($session['user']['seenmaster']){
            output("Pensi che, forse, ne hai avute abbastanza dal tuo maestro per oggi, la lezione appresa in precedenza ti impedisce di sottoporti ");
            output("nuovamente ad un'ulteriore umiliazione.");
            addnav("Torna al Castello","castelexcal.php");
            addnav("Torna al Villaggio","village.php");
        }else{
            if (getsetting("multimaster",1)==0) $session['user']['seenmaster'] = 1;
            if ($session['user']['experience']>=$exprequired){
                if ($session['user']['reincarna'] > 0 OR ($session['user']['bonusattack']>4 OR $session['user']['bonusdef']>4)){
                    //output("Routine Modificata`n");
                    $reinca = 0;
                    $reincaatt = 0;
                    $reincadef = 0;
                    //if ($session['user']['reincarna']>0) $reinca=round(($session['user']['reincarna']/2)*18);
                    if ($session['user']['reincarna']>0) $reinca=round(($session['user']['reincarna']/2)*27);
                    if ($reinca < 18) $reinca = 18;
                    if ($session['user']['bonusattack'] > 0 AND $session['user']['dragonkills'] > 2){
                        if ($session['user']['bonusattack'] > $reinca) {
                            $reincaatt = $reinca+1;
                        } else $reincaatt = ($session['user']['bonusattack'] / 2) + 1;
                    }
                    if ($session['user']['bonusdefence'] > 0 AND $session['user']['dragonkills'] > 2){
                        if ($session['user']['bonusdefence'] > $reinca) {
                            $reincadef = $reinca+1;
                        } else {
                          $reincadef = ($session['user']['bonusdefence'] / 2) + 1;
                        }
                    }
                    $atkflux = e_rand(0,($session['user']['dragonkills']+$reinca)) + e_rand($reincaatt,($session['user']['bonusattack']-1));
                    $defflux = e_rand(0,($session['user']['dragonkills']-$atkflux + $reinca)) + e_rand($reincadef,($session['user']['bonusdefence']-1));
                    $hpflux = (($session['user']['dragonkills'] - ($atkflux+$defflux)) * 5) + e_rand(0,($reinca*$session['user']['maxhitpoints']/5));
                    if ($session['user']['level']>=12){
                        $atkflux -= 1;
                        $defflux -= 1;
                        $hpflux = round($hpflux*0.9);
                    }
                    /*if ($hpflux > ($session['user']['maxhitpoints'] * 1.4)) {
                       $hpflux = round($session['user']['maxhitpoints'] * 1.4);
                    }
                    if ($session['user']['reincarna'] != 0 AND $hpflux < ($session['user']['maxhitpoints'] / 1.5)) $hpflux = round($session['user']['maxhitpoints'] / 1.5);
                    */
                }else {
                    //output("Routine Normale`n");
                    $atkflux = e_rand(0,$session['user']['dragonkills']);
                    $defflux = e_rand(0,($session['user']['dragonkills']-$atkflux));
                    $hpflux = ($session['user']['dragonkills'] - ($atkflux+$defflux)) * 5;
               }
                $master['creatureattack']+=$atkflux;
                $master['creaturedefense']+=$defflux;
                $master['creaturehealth']+=$hpflux;
                if ($session['user']['superuser'] >=2) {
                   output("`b`#Attacco: ".$master['creatureattack']." Difesa: ".$master['creaturedefense']." HP: ".$master['creaturehealth']."`b`n`n");
                }
                if ($master['creaturehealth'] > ($session['user']['maxhitpoints'] * 1.4)) {
                   $master['creaturehealth'] = round($session['user']['maxhitpoints'] * 1.4);
                }
                if ($master['creaturehealth'] < ($session['user']['maxhitpoints'] / 1.5)) {
                   $master['creaturehealth'] = ($session['user']['maxhitpoints'] / 1.5);
                }
                $master['creaturehealth'] = round ($master['creaturehealth']);
                $session['user']['badguy']=createstring($master);
                $battle=true;
                if ($victory) {
                    $badguy = createarray($session['user']['badguy']);
                    output("Con un turbinio di colpi sistemi il tuo Maestro.`n");
                }
            }else{
                output("Impugni la tua ".$session['user']['weapon']." e ".$session['user']['armor']." e ti avvicini a `^".$master['creaturename']."`0.`n`nUn piccolo capannello di avventori e curiosi ");
                output("si è raccolto, e noti velocemente i sorrisi sui loro volti, ma ti senti fiducioso.  Avanzi verso `^".$master['creaturename']."`0, ed esegui ");
                output("una perfetta mossa d'attacco, per scoprire che non hai in mano NIENTE!  `^".$master['creaturename']."`0 è in piedi di fianco a te con in mano la tua arma. ");
                output("Mogio recuperi la tua ".$session['user']['weapon'].", e ti defili dal campo d'allenamento con le grida di derisione alla tue spalle.");
                addnav("Torna al Castello","castelexcal.php");
                addnav("Torna al Villaggio.","village.php");
                $session['user']['seenmaster']=1;
            }
        }
    }else if($_GET['op']=="question"){
        output("Ti avvicini timidamente a `^".$master['creaturename']."`0 e gli chiedi come sta andando il tuo allenamento.");
        if($session['user']['experience']>=$exprequired){
            output("`n`n`^".$master['creaturename']."`0 dice, \"Wow, i tuoi muscoli stanno diventando più grossi dei miei...\"");
        }else{
            output("`n`n`^".$master['creaturename']."`0 ti dice che hai bisogno di altri `%".($exprequired-$session['user']['experience'])."`0 punti esperienza prima di poterlo sfidare in battaglia.");
        }
        addnav("Interroga il Maestro","swarzy.php?op=question");
        addnav("Sfida il Maestro","swarzy.php?op=challenge");
        if ($session['user']['superuser'] > 2) {
            addnav("Superuser Gain level","swarzy.php?op=challenge&victory=1");
        }
        addnav("Torna al Castello","castelexcal.php");
        addnav("Torna al Villaggio","village.php");
    }else if($_GET['op']=="autochallenge"){
        addnav("Combatti il Maestro","swarzy.php?op=challenge");
        output("`0A `^".$master['creaturename']."`0 sono giunte voci delle tue gesta come guerriero, ed anche che tu pensi di
        essere più forte di lui e che non hai bisogno di batterti per provarlo. Il suo ego è comprensibilmente offeso, e
                quindi è venuto a cercarti. `^".$master['creaturename']."`0 ti chiede un incontro Immediato,
        ed il tuo orgoglio non ti consente di rifiutare la sua richiesta.");
        if ($session['user']['hitpoints']<$session['user']['maxhitpoints']){
            output("`n`nEssendo una persona onesta, il tuo maestro ti da una pozione di guarigione completa prima dell'inizio dello scontro.");
            $session['user']['hitpoints']=$session['user']['maxhitpoints'];
        }
        addnews("`3".$session['user']['name']."`3 è stato rincorso dal suo maestro `^".$master['creaturename']."`3 per aver bigiato le lezioni.");
    }
    if ($_GET['op']=="fight"){
        $battle=true;
    }
    if ($_GET['op']=="run"){
        output("`\$Il tuo orgoglio ti impedisce di fuggire da questo scontro!`0");
        $_GET['op']="fight";
        $battle=true;
    }

    if($battle){
        if (count($session['bufflist'])>0 && is_array($session['bufflist']) || $_GET['skill']!=""){
            $_GET['skill']="";
            if ($_GET['skill']=="") $session['user']['buffbackup']=serialize($session['bufflist']);
            $session['bufflist']=array();
            output("`&Il tuo orgoglio ti inpedisce di usare qualunque abilità speciale durante l'incontro!`0");
        }
        if (!$victory) include("battle.php");
        if ($victory){
            //$badguy[creaturegold]=e_rand(0,$badguy[creaturegold]);
            $search=array(    "%s",
                                            "%o",
                                            "%p",
                                            "%X",
                                            "%x",
                                            "%w",
                                            "%W"
                                        );
            $replace=array(    ($session['user']['sex']?"lei":"lui"),
                                            ($session['user']['sex']?"lei":"lui"),
                                            ($session['user']['sex']?"sua":"suo"),
                                            ($session['user']['weapon']),
                                            $badguy['creatureweapon'],
                                            $badguy['creaturename'],
                                            $session['user']['name']
                                        );
            $badguy[creaturelose]=str_replace($search,$replace,$badguy['creaturelose']);

            output("`b`&".$badguy['creaturelose']."`0`b`n");
            output("`b`\$Hai battuto ".$badguy['creaturename']."!`0`b`n");

            $session['user']['level']++;
            $session['user']['maxhitpoints']+=10;
            $session['user']['soulpoints']+=5;
            $session['user']['attack']++;
            $session['user']['defence']++;
            $session['user']['seenmaster']=0;
            output("`#Avanzi al livello `^".$session['user']['level']."`#!`n");
            output("I tuoi hitpoints massimi adesso sono `^".$session['user']['maxhitpoints']."`#!`n");
            output("Guadagni un punto attacco!`n");
            output("Guadagni un punto difesa!`n");
            if ($session['user']['level']<15){
                output("Hai un nuovo maestro.`n");
            }else{
                output("Nessuno nel villaggio è più abile di te!`n");
            }
            if ($session['user']['referer']>0 && $session['user']['level']>=15 && $session['user']['refererawarded']<1){
                $sql = "UPDATE accounts SET donation=donation+10 WHERE acctid={$session['user']['referer']}";
                db_query($sql);
                $session['user']['refererawarded']=1;
                systemmail($session['user']['referer'],"`%Uno dei tuoi \"referrals\" è avanzato!`0","`%".$session['user']['name']."`# è avanzato al livello `^{$session['user']['level']}`#, e così hai guadagnato `^25`# punti!");
            }
            increment_specialty();
            addnav("Interroga il Maestro","swarzy.php?op=question");
            addnav("Sfida il Maestro","swarzy.php?op=challenge");
            if ($session['user']['superuser'] > 2) {
                addnav("Superuser Gain level","swarzy.php?op=challenge&victory=1");
            }
            addnav("Torna al Castello","castelexcal.php");
            addnav("Torna al Villaggio","village.php");
            addnews("`%".$session['user']['name']."`3 ha sconfitto il suo maestro, `%".$badguy['creaturename']."`3 per avanzare al livello `^".$session[user][level]."`3 nel suo `^".ordinal($session[user][age])."`3 giorno!!");
            $badguy=array();
            $session['user']['hitpoints'] = $session['user']['maxhitpoints'];
            //$session[user][seenmaster]=1;
        }else{
            if($defeat){
                //addnav("Daily news","news.php");
                $sql = "SELECT taunt FROM taunts ORDER BY rand(".e_rand().") LIMIT 1";
                $result = db_query($sql) or die(db_error(LINK));
                $taunt = db_fetch_assoc($result);
                $taunt = str_replace("%s",($session['user']['gender']?"lui":"lei"),$taunt['taunt']);
                $taunt = str_replace("%o",($session['user']['gender']?"egli":"ella"),$taunt);
                $taunt = str_replace("%p",($session['user']['gender']?"suo":"sua"),$taunt);
                $taunt = str_replace("%x",($session['user']['weapon']),$taunt);
                $taunt = str_replace("%X",$badguy['creatureweapon'],$taunt);
                $taunt = str_replace("%W",$badguy['creaturename'],$taunt);
                $taunt = str_replace("%w",$session['user']['name'],$taunt);

                addnews("`%".$session['user']['name']."`5 ha sfidato il suo maestro, ".$badguy['creaturename']." ed ha perso!`n$taunt");
                //$session[user][alive]=false;
                //$session[user][gold]=0;
                $session['user']['hitpoints']=$session['user']['maxhitpoints'];
                output("`&`bSei stato battuto da `%".$badguy['creaturename']."`&!`b`n");
                output("`%".$badguy['creaturename']."`\$ si ferma proprio prima di sferrare il colpo finale, e invece allunga una mano per aiutarti a rialzarti, e ti da una pozione di guarigione completa.`n");
                $search=array(    "%s",
                                                "%o",
                                                "%p",
                                                "%x",
                                                "%X",
                                                "%W",
                                                "%w"
                                            );
                $replace=array(    ($session['user']['gender']?"lui":"lei"),
                                                ($session['user']['gender']?"egli":"ella"),
                                                ($session['user']['gender']?"lui":"lei"),
                                                ($session['user']['weapon']),
                                                $badguy['creatureweapon'],
                                                $badguy['creaturename'],
                                                $session['user']['name']
                                            );
                $badguy[creaturewin]=str_replace($search,$replace,$badguy['creaturewin']);
                output("`^`b".$badguy['creaturewin']."`b`0`n");
                addnav("Interroga il Maestro","swarzy.php?op=question");
                addnav("Sfida il Maestro","swarzy.php?op=challenge");
                if ($session['user']['superuser'] > 2) {
                    addnav("Superuser Gain level","swarzy.php?op=challenge&victory=1");
                }
                addnav("Torna al Castello","castelexcal.php");
                addnav("Torna al Villaggio","village.php");
                $session['user']['seenmaster']=1;
            }else{
              fightnav(false,false);
            }
        }
    }
}else{
  output("Vai sul campo di battaglia. I guerrieri più giovani si riuniscono in gruppo e ti indicano mentre passi. Conosci bene questo posto. Non ");
    output("c'è rimasto nulla per te qui a parte i ricordi. Resti ancora un momento, a guardare gli altri guerrieri che si allenano, prima di voltarti ");
    output("per tornare al villaggio. ");
    addnav("Torna al Castello","castelexcal.php");
    addnav("Torna al Villaggio","village.php");
}
page_footer();
?>
