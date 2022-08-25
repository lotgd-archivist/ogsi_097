<?php
require_once "common.php";

page_header("4 Goblin!");
if ($_GET['op']==""){
  output("`\$Carichi i 4 Goblin che con le loro lance in mano si preparano allo scontro!");
    $badguy = array("creaturename"=>"`@4 Goblin`0","creaturelevel"=>10,"creatureweapon"=>"Lance","creatureattack"=>18,"creaturedefense"=>6,"creaturehealth"=>120, "diddamage"=>0);
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
        output("`nSei stato veramente forte. Hai dimostrato di essere un ottimo combattente. Hai trovato altre 5 gemme.");
        debuglog("vince 5 gemme per l'uccisione dei 4 goblin");
        $session['user']['gems']+= 5 ;
    }

    addnav("Torna al Villaggio","village.php");
        }

if ($_GET['op']=="run"){
  output("I Goblin ti bloccano le vie di fuga!");
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
        $exp=$session['user']['experience']*0.3;
          $session['user']['experience']+=$exp;
        output("`&Dopo tutte le botte che gli hai dato non si alzeranno mai più.`n");
        output("`2Hai vinto!! È stata dura ma sei riuscito a ucciderli.`n  ");
        output("`2I 4 Goblin avevano 2500 pezzi d'oro.  ");
        output("Hai guadagnato $exp punti esperienza.`n`n");
        output("Sei molto stanco e decidi quindi di tornare a casa.`n`n");
        debuglog("vince 2500 oro e $exp esperienza uccidendo i 4 goblin");
        $session['user']['quest'] += 2;
        $session['user']['gold']+= 2500 ;
        addnav("Torna al Villaggio","village.php");
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
            addnews("`%".$session['user']['name']."`5 è stato ucciso nel bosco a sud mentre combatteva con $badguy[creaturename]`n$taunt");
            $session['user']['alive']=false;
            debuglog("perde ".$session['user']['gold']." oro ucciso dai 4 goblin");
            $session['user']['gold']=0;
            $session['user']['hitpoints']=0;
            $session['user']['badguy']="";
            $session['user']['quest'] += 1;
            output("`b`&Sei stato ucciso da `%".$badguy['creaturename']."`&!!!`n");
            output("`4Ti rubano tutti i soldi!`n");
            output("Dovrai aspettare fino a domani per affronatare nuove avventure.");

            page_footer();
        }else{
          fightnav(true,false);
        }
    }
}
page_footer();
?>