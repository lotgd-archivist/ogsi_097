<?php
require_once "common.php";

page_header("Il piccolo Goblin!");
if ($_GET['op']==""){
  output("`$ Il Goblin si avvicina e ti scuote con un un piede, tu continui a far finta di essere morto.
  Mentre lui infila le sue luride mani nella tua borsa lo agguanti per il collo! ");
    output("La creatura si dimena, si agita e ti assesta alcuni calci. Tu ti alzi stringento il fragile collo tra
    le tue mani, ma ti accorgi che sta per prendere un pugnale che porta legato alla cintola. ");
    output("Prima di essere trafitto scaraventi il Goblin a terra e prendi la tua arma. Ora dovrai combattere!");
    $badguy = array("creaturename"=>"`@Il piccolo Goblin`0","creaturelevel"=>8,"creatureweapon"=>"Pugnale","creatureattack"=>13,"creaturedefense"=>5,"creaturehealth"=>40, "diddamage"=>0);
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
        debuglog("guadagna 5 gemme per combattimento perfetto con piccolo goblin");
        $session['user']['gems']+= 5 ;
    }

    addnav("Torna in citt?","village.php");
        }

if ($_GET['op']=="run"){
  output("Il goblin ti blocca le vie di fuga!");
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
        output("`&Dopo tutte le botte che gli hai dato non si alzer? mai pi?.`n");
        output("`2Hai vinto. ? stata dura ma alla fine sei riuscito a ucciderlo.`n");
        output("`n`2Il piccolo Goblin aveva 1000 pezzi d'oro.`n");
        output("`n`n`2Ma forse ucciderlo non ? stata una buona idea per? hai guadagnato un po di esperienza $exp.  ");
        $session['user']['quest'] += 2;
        $session['user']['gold']+= 1000 ;
        $session['user']['experience']+=$exp;
        debuglog("guadagna 1000 oro e $exp esperienza uccidendo il piccolo goblin");
        addnav("Torna al villaggio","village.php");
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
            addnews("`%".$session['user']['name']."`5 ? stato ucciso nella foresta a sud mentre inseguiva il $badguy[creaturename]`n$taunt");
            $session['user']['alive']=false;
            debuglog("perde ".$session['user']['gold']." oro ucciso da piccolo goblin");
            $session['user']['gold']=0;
            $session['user']['hitpoints']=0;
            $session['user']['badguy']="";
            $session['user']['quest'] += 1;
            output("`b`&Sei stato ucciso dal `%".$badguy['creaturename']."`&!!!`n");
            output("`4Ti ruba tutti i soldi!`n");
            output("Dovrai attendere fino a domani per affrontare nuove avventure.");
            page_footer();
        }else{
          fightnav(true,false);
        }
    }
}
page_footer();
?>