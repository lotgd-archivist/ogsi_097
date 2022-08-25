<?php
require_once "common.php";

page_header("Il Re Goblin!");
if ($_GET['op']==""){
  output("`$ Sei arrivato alla sala del trono! Molti Goblin si zittiscono quando ti vedono.");
    output("La creatura sul trono si alza in piedi e ti mugugna qualche cosa. Prende una mazza enorme più grossa di lui e ti viene incontro. ");
    output("Ora dovrai combattere! Tutti i Goblin esultano per il loro Re");
    $badguy = array("creaturename"=>"`@Re Goblin`0","creaturelevel"=>14,"creatureweapon"=>"Mazza enorme","creatureattack"=>33,"creaturedefense"=>15,"creaturehealth"=>240, "diddamage"=>0);
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
        output("`b`c`&~~ Combattimento Perfetto !! ~~`0`c`b`n`n");
    }


    if ($flawless) {
        output("`nSei stato veramente forte hai dimostrato di essere un ottimo combattente hai trovato altre 5 gemme.");
        debuglog("vince 5 gemme supplementari per combattimento perfetto con Re Goblin");
        $session['user']['gems']+= 5 ;
    }

    addnav("Torna al Villaggio","village.php");
        }

if ($_GET['op']=="run"){
  output("Il Re Goblin ti blocca le vie di fuga!");
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
        addnews("`%".$session['user']['name']."`5 ha ucciso il $badguy[creaturename]`n$taunt");
        debuglog("ha ucciso il Re Goblin e guadagnato 3000 oro e $exp punti esperienza");
        $badguy=array();
        $session['user']['badguy']="";
         $exp=$session['user']['experience']*0.5;
        output("`&Dopo tutte le botte che gli hai dato non si alzerà mai più.`n");
        output("`2Hai vinto!! È stata dura ma sei riuscito a ucciderlo. ");
        output("`n`2Il Re Goblin aveva 3000 pezzi d'oro.  ");
        output("`n`n`2Forse ucciderlo non è stata una buona idea, però hai guadagnato $exp di esperienza.`n  ");
        $session['user']['quest'] += 3;
        $session['user']['gold']+= 3000 ;
        $session['user']['experience']+=$exp;
        addnav("Torna al Villaggio","village.php");
    }else{
        if($defeat){
            addnav("Notizie Giornaliere","news.php");
            $sql = "SELECT taunt FROM taunts ORDER BY rand(".e_rand().") LIMIT 1";
            $result = db_query($sql) or die(db_error(LINK));
            $taunt = db_fetch_assoc($result);
            $taunt = str_replace("%s",($session[user][sex]?"lei":"lui"),$taunt[taunt]);
            $taunt = str_replace("%o",($session[user][sex]?"ella":"egli"),$taunt);
            $taunt = str_replace("%p",($session[user][sex]?"suo":"suo"),$taunt);
            $taunt = str_replace("%x",($session[user][weapon]),$taunt);
            $taunt = str_replace("%X",$badguy[creatureweapon],$taunt);
            $taunt = str_replace("%W",$badguy[creaturename],$taunt);
            $taunt = str_replace("%w",$session[user][name],$taunt);
            addnews("`%".$session['user']['name']."`5 è stato ucciso dal ".$badguy['creaturename']."`n$taunt");
            $session['user']['alive']=false;
            debuglog("ha perso ".$session['user']['gold']." oro quando è stato ucciso dal Re Goblin");
            $session['user']['gold']=0;
            $session['user']['hitpoints']=0;
            $session['user']['badguy']="";
            $session['user']['quest'] += 1;
            output("`b`&Sei stato ucciso dal `%".$badguy['creaturename']."`&!!!`n");
            output("`4Ti ruba tutti i soldi!`n");
            output("Dovrai aspettare un nuovo giorno per affrontare altre avventure.");
            page_footer();
        }else{
          fightnav(true,false);
        }
    }
}
page_footer();
?>