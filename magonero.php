<?php
require_once "common.php";

page_header("Mago nero");
if ($_GET['op']==""){
  output("`$Devi combattere!");
    $badguy = array(
                  "creaturename"=>"`@Mago Nero`0",
                  "creaturelevel"=>5,
                  "creatureweapon"=>"Magia",
                  "creatureattack"=>10,
                  "creaturedefense"=>5,
                  "creaturehealth"=>100,
                  "diddamage"=>0
                  );
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
        output("`nSei stato veramente forte. Hai dimostrato di essere un ottimo combattente. Hai trovato altre 5 gemme.");
        debuglog("trova 5 gemme per combattimento perfetto con Mago Nero");
        $session['user']['gems']+= 5 ;
    }

    addnav("Torna al Villaggio","village.php");
        }

if ($_GET['op']=="run"){
  output("Il Mago Nero ti blocca la fuga!");
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
        output("`&Dopo tutte le botte che gli hai dato non si alzer? mai pi?.");
        output("`2Hai vinto!! ? stata dura ma sei riuscito a ucciderlo.  ");
        output("`n`2Il Mago Nero aveva 1000 monete con se.");
        output("`n`n`2Forse ucciderlo non ? stata una buona idea, ma hai guadagnato $exp punti di esperienza.`n  ");
        debuglog("guadagna 1000 oro e $exp esperienza con il Mago Nero");
        $session['user']['quest'] += 2;
        $session['user']['gold']+= 1000 ;
        $session['user']['experience']+=$exp;
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
            addnews("`%".$session['user']['name']."`5 ? stato ucciso nel bosco a sud mentre inseguiva il ".$badguy['creaturename']."`n$taunt");
            $session['user']['alive']=false;
            debuglog("perde ".$session['user']['gold']." oro ucciso dal Mago Nero");
            $session['user']['gold']=0;
            $session['user']['hitpoints']=0;
            $session['user']['badguy']="";
            $session['user']['quest'] += 1;
            output("`b`&Sei stato ucciso dal `%$badguy[creaturename]`&!!!`n");
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