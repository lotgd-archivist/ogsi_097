<?php
require_once "common.php";

page_header("Demone");
if ($_GET['op']==""){
  output("`$Devi combattere!");
    $badguy = array("creaturename"=>"`@Demone`0","creaturelevel"=>12,"creatureweapon"=>"Dannazione","creatureattack"=>15,"creaturedefense"=>9,"creaturehealth"=>155, "diddamage"=>0);
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
    while(list($key,$val)=each($session['user']['dragonpoints'])){
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
    $session['user']['badguy']=createstring($badguy);
    $battle=true;
}else if($_GET['op']=="prologo"){
    output("`@Vittoria!`n`n");
    $flawless = 0;
    if ($_GET['flawless']) {
        $flawless = 1;
        output("`b`c`&~~ Combattimento perfetto !! ~~`0`c`b`n`n");
    }


    if ($flawless) {
        output("`nSei stato veramente forte hai dimostrato di essere un ottimo combattente hai trovato altre 5 gemme.");
        $session['user']['gems']+= 5 ;
    }

    addnav("Torna in città","village.php");
        }

if ($_GET['op']=="run"){
  output("Il demone ti blocca ogni possibile via di fuga!");
    $_GET['op']="fight";
}
if ($_GET['op']=="fight" || $_GET['op']=="run"){
    $battle=true;
}
if ($battle){
  include("battle.php");
    if ($victory){
        $flawless = 0;
        if ($badguy['diddamage'] != 1) $flawless = 1;
        $badguy=array();
        $session['user']['badguy']="";
         $exp=$session['user']['experience']*0.4;
        output("`&Dopo tutte le botte che gli hai dato non si alzerà mai più.");
        output("`2Hai vinto, è stata dura ma alla fine sei riuscito a ucciderlo.  ");
        output("`n`2Il demone aveva 3000 monete con se.  ");
        output("`n`n`2Ma forse ucciderlo non è stata una buona idea però hai guadagnato un po di esperienza $exp.  ");
        $session['user']['quest'] += 2;
        $session['user']['gold']+= 3000 ;
        $session['user']['experience']+=$exp;
        addnav("Torna al villaggio","village.php");
    }else{
        if($defeat){
            addnav("News","news.php");
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
            addnews("`%".$session['user']['name']."`5 è stato ucciso nella foresta a sud mentre inseguiva il $badguy[creaturename]`n$taunt");
            $session[user][alive]=false;
            debuglog("lost ".$session['user']['gold']." gold when they were slain");
            $session['user']['gold']=0;
            $session['user']['hitpoints']=0;
            $session['user']['badguy']="";
            $session['user']['quest'] += 1;
            output("`b`&Sei stato ucciso dal `%".$badguy['creaturename']."`&!!!`n");
            output("`4Ti ruba tutti i soldi!`n");
            output("Dovrai aspettare un giorno per affrontare nuove avventure.");
            page_footer();
        }else{
          fightnav(true,false);
        }
    }
}
page_footer();
?>