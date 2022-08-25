<?php
require_once "common.php";

page_header("I tre Golem!");
if ($_GET['op']==""){
    output("`n`3Non c'è modo di evitare questo combattimento, è una questione di `2`bvita`3`b o di `4`bmorte`3`b!");
    $badguy = array(
                    "creaturename"=>"`@3 Golem di Pietra`0",
                    "creaturelevel"=>17,
                    "creatureweapon"=>"Pugno di Pietra",
                    "creatureattack"=>20,
                    "creaturedefense"=>40,
                    "creaturehealth"=>400,
                    "diddamage"=>0
                    );
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
        output("`nSei stato veramente forte. Hai dimostrato di essere un ottimo combattente..");
    }

    addnav("Torna alla Foresta","village.php");
        }

if ($_GET['op']=="run"){
  output("I Golem di Pietra non ti lasciano vie di fuga!");
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
        $soldi = e_rand($session['user']['level']*100,$session['user']['level']*1500);
        $gemme = e_rand($session['user']['level']*1,$session['user']['level']*2);
$session['user']['gold'] += $soldi;
$session['user']['gems'] += $gemme;
output ("`n`nNon riesci a credere ai tuoi occhi. Il tesoro davanti a te è <b style='font-size:18px;color:#FF0000;'>immenso</b>`n`n", true);
output ("`6Trovi $soldi monete d'oro e $gemme gemme");
debuglog("trova $soldi oro e $gemme gemme dopo aver ucciso i golem di pietra");
addnav("Torna alla Foresta","forest.php");
    }else{
        if($defeat){
            addnav("Notizie Giornaliere","news.php");
            $sql = "SELECT taunt FROM taunts ORDER BY rand(".e_rand().") LIMIT 1";
            $result = db_query($sql) or die(db_error(LINK));
            $taunt = db_fetch_assoc($result);
            $taunt = str_replace("%s",($session['user']['sex']?"her":"him"),$taunt['taunt']);
            $taunt = str_replace("%o",($session['user']['sex']?"she":"he"),$taunt);
            $taunt = str_replace("%p",($session['user']['sex']?"her":"his"),$taunt);
            $taunt = str_replace("%x",($session['user']['weapon']),$taunt);
            $taunt = str_replace("%X",$badguy['creatureweapon'],$taunt);
            $taunt = str_replace("%W",$badguy['creaturename'],$taunt);
            $taunt = str_replace("%w",$session['user']['name'],$taunt);
            addnews("`%".$session['user']['name']."`5 è stato/a ucciso/a nel cimitero dallo ".$badguy['creaturename']."`n$taunt");
            $session['user']['alive']=false;
            debuglog("perde ".$session['user']['gold']." oro ucciso dai 3 Golem di Pietra");
            $session['user']['gold']=0;
            $session['user']['hitpoints']=0;
            $session['user']['badguy']="";
            output("`b`&Sei stato ucciso dai `%".$badguy['creaturename']."`&!!!`n");
            output("`4Ti privano dei tuoi averi!`n");
            output("`0Dovrai aspettare un nuovo giorno per affrontare altre avventure.");
            page_footer();
        }else{
          fightnav(true,false);
        }
    }
}
page_footer();
?>