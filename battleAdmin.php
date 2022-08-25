<?php
require_once "common.php";
page_header("Admin Infuriati!!!");
if ($_GET['op']==""){
    $badguy = array("creaturename"=>"`\$Admin Infuriati`0",
                    "creaturelevel"=>18,
                    "creatureweapon"=>"Ban, Mute e depotenziamenti",
                    "creatureattack"=>10000,
                    "creaturedefense"=>10000,
                    "creaturehealth"=>500000,
                    "diddamage"=>0);
     $session['user']['buffbackup']=serialize($session['bufflist']);
     $session[bufflist]=array();
     $session['user']['badguy']=createstring($badguy);
    $battle=true;
    output("`^Sei stato colto con le mani nel sacco! `nSook, Excalibur, chumkiu, Essizard, Barik e il temibile Luke ti guardano infuriati!");
    output("`nNon ti resta che combattere!");
}
if ($_GET['op']=="run"){
  output("chumkiu ti blocca le vie di fuga!");
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
         $exp=$session['user']['experience']*0.45;
        output("`&Bravo congratulazioni! Ma questo non ti eviterà il ban.`n");
        addnews("E' riuscito a battere gli Admin Infuriati! Ma il ban lo prenderà lo stesso");
        addnav("Torna al Villaggio","village.php");
    }else{
        if($defeat){
            addnav("Notizie Giornaliere","news.php");
            addnews("`%".$session[user][name]."`5 è stato ucciso dagli `\$Admin Infuriati!");
            debuglog("`\$E' stato ucciso dagli admin infuriati");
            $session['user']['alive']=false;
            $session['user']['gold']=0;
            $session['user']['hitpoints']=0;
            $session['user']['badguy']="";
            $session['user']['quest'] += 1;
            output("`nDovrai aspettare che l'inferno ghiacci prima di poter giocare qui!");
            page_footer();
        }else{
          fightnav(false,false);
        }
    }
}
page_footer();
?>
