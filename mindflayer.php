<?php
require_once "common.php";

page_header("Mind Flayer");
if ($_GET[op]==""){
  output("`\$Devi combattere!");
        $badguy = array("creaturename"=>"`@Mind Flayer`0","creaturelevel"=>5,"creatureweapon"=>"Tentacoli Mentali","creatureattack"=>7,"creaturedefense"=>5,"creaturehealth"=>100, "diddamage"=>0);
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
                output("`nSei stato veramente forte. Trovi 5 gemme nel cadavere.");
                debuglog("trova 5 gemme per combattimento perfetto con i Mind Flayer");
                $session['user']['gems']+= 5 ;
        }

        addnav("Continua","iltest.php?op=mindflayerwin");
        }

if ($_GET[op]=="run"){
  output("I Mind Flayer ti bloccano la fuga!");
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
                $exp=intval($session[user][experience]*0.4);
                output("`2Hai vinto !! È stata dura ma sei riuscito ad ucciderli. ");
                output("`n`n`2Hai guadagnato $exp punti di esperienza.`n  ");
                debuglog("guadagna $exp esperienza con i Mind Flayer");
                $session[user][experience]+=$exp;
                addnav("Continua","iltest.php?op=mindflayerwin");
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
                        addnews("`%".$session[user][name]."`5 è stato ucciso nel bosco a sud da dei $badguy[creaturename]`n$taunt");
                        $session[user][alive]=false;
                        debuglog("perde {$session['user']['gold']} oro ucciso dalla Manticora");
                        $session[user][gold]=0;
                        $session[user][hitpoints]=0;
                        $session[user][badguy]="";
//                      $session['user']['quest'] += 1;
                        output("`b`&Sei stato ucciso dai `%$badguy[creaturename]`&!!!`n");
                        output("`4Ti ruba tutti i soldi!`n");
                        output("Dovrai aspettare un nuovo giorno per affronatare altre avventure.");
                        page_footer();
                }else{
                  fightnav(true,false);
                }
        }
}
page_footer();
?>