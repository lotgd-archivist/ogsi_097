<?php
require_once "common.php";

page_header("Negromante");
if ($_GET[op]==""){
  output("`\$Devi combattere!");
        $badguy = array("creaturename"=>"`@Negromante`0","creaturelevel"=>5,"creatureweapon"=>"Tocco Mortale","creatureattack"=>7,"creaturedefense"=>8,"creaturehealth"=>100, "diddamage"=>0);
        //toughen up each consecutive dragon.
        //      $atkflux = e_rand(0,$session['user']['dragonkills']*2);
        //      $defflux = e_rand(0,($session['user']['dragonkills']*2-$atkflux));
        //      $hpflux = ($session['user']['dragonkills']*2 - ($atkflux+$defflux)) * 5;
        //      $badguy['creatureattack']+=$atkflux;
        //      $badguy['creaturedefense']+=$defflux;
        //      $badguy['creaturehealth']+=$hpflux;

        // First, find out how each dragonpoint has been spent and count those
        // used on attack and defense.
        // Coded by JT, based on collaboration with MightyE
        $points = 0;
        while(list($key,$val)=each($session[user][dragonpoints])){
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
        $session[user][badguy]=createstring($badguy);
        $battle=true;
}else if($_GET[op]=="prologo"){
        output("`@Vittoria!`n`n");
        $flawless = 0;
          if ($_GET['flawless']) {
                $flawless = 1;
                output("`b`c`&~~ Combattimento Perfetto !! ~~`0`c`b`n`n");
        }


        if ($flawless) {
                output("`nSei stato veramente forte. Hai trovato 5 gemme nel cadavere.");
                debuglog("trova 5 gemme per combattimento perfetto con Negromante");
                $session['user']['gems']+= 5 ;
        }

        addnav("Torna al Villaggio","village.php");
                }

if ($_GET[op]=="run"){
  output("Il Negromante ti blocca la fuga!");
        $_GET[op]="fight";
}
if ($_GET[op]=="fight" || $_GET[op]=="run"){
        $battle=true;
}
if ($battle){
  include("battle.php");
        if ($victory){
                $flawless = 0;
                if ($badguy['diddamage'] != 1) $flawless = 1;
                $badguy=array();
                $session[user][badguy]="";
                 $exp=$session[user][experience]*0.3;
                output("`&Hai vinto!! Grazie alle tue capacità sei riuscito a sconfiggerlo.");
                output("`2`nÈ stata dura ma alla fine ne sei uscito vincitore.  ");
                output("`n`n`2Chissà cosa penserà lo sceriffo, ma comunque vada hai guadagnato $exp punti di esperienza.`n  ");
                debuglog("guadagna $exp esperienza con il Negromante");
                $session['user']['quest'] += 2;
                $session[user][experience]+=$exp;
                addnav("Torna al Villaggio","village.php");
        }else{
                if($defeat){
                        addnav("Notizie Giornaliere","news.php");
                        $sql = "SELECT taunt FROM taunts ORDER BY rand(".e_rand().") LIMIT 1";
                        $result = db_query($sql) or die(db_error(LINK));
                        $taunt = db_fetch_assoc($result);
                        $taunt = str_replace("%s",($session[user][sex]?"her":"him"),$taunt[taunt]);
                        $taunt = str_replace("%o",($session[user][sex]?"she":"he"),$taunt);
                        $taunt = str_replace("%p",($session[user][sex]?"her":"his"),$taunt);
                        $taunt = str_replace("%x",($session[user][weapon]),$taunt);
                        $taunt = str_replace("%X",$badguy[creatureweapon],$taunt);
                        $taunt = str_replace("%W",$badguy[creaturename],$taunt);
                        $taunt = str_replace("%w",$session[user][name],$taunt);
                        addnews("`%".$session[user][name]."`5 è stato ucciso nel bosco a sud mentre scortava il $badguy[creaturename]`n");
                        $session[user][alive]=false;
                        debuglog("perde {$session['user']['gold']} oro ucciso dal Negromante");
                        $exp=intval($session[user][experience]*0.1);
						$session[user][gold]=0;
                        $session[user][hitpoints]=0;
                        $session[user][badguy]="";
                        $session[user][experience]-=$exp;
						$session['user']['quest'] += 1;
                        output("`b`&Sei stato ucciso dal `%$badguy[creaturename]`&!!!`n");
                        output("`4Ti deruba tutti i soldi! Per fortuna non ti ha perquisito a fondo e non ti ha sottratto le tue preziose gemme.`n");
                        output("Perdi però il 10% della tua esperienza, cioè $exp punti.");
						output("Dovrai aspettare un nuovo giorno per affrontare altre avventure, o confidare nell'aiuto di `\$Ramius`4.");
                        page_footer();
                }else{
                  fightnav(true,false);
                }
        }
}
page_footer();
?>