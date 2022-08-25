<?php
require_once "common.php";

page_header("Lo spirito guardiano!");
if ($_GET['op']==""){
    output("`n`3Non c'è modo di evitare questo combattimento, è una questione di `2Vita`3 o di `4Morte`3!!!`n");
    $badguy = array("creaturename"=>"`@Spirito Guardiano`0","creaturelevel"=>6,"creatureweapon"=>"Tocco Gelido","creatureattack"=>13,"creaturedefense"=>10,"creaturehealth"=>60, "diddamage"=>0);
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
        output("`nSei stato veramente forte. Hai dimostrato di essere un ottimo combattente.`n");
    }

    addnav("Torna alla Foresta","village.php");
        }

if ($_GET['op']=="run"){
  output("Il Guardiano non ti permette di scappare!");
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
        output("`&Sei riuscito a sconfiggere lo spirito.`n");
        output("`2Puoi adesso impossessarti di quello che c'era nella tomba.");
        output("`n`n`2Trovi 1000 pezzi d'oro.");
        debuglog("trova 1000 oro uccidendo lo spirito guardiano");
        $session['user']['gold']+= 1000 ;
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
            addnews("`%".$session['user']['name']."`5 è stat".($session['user']['sex']?"a":"o")." uccis".($session['user']['sex']?"a":"o")." nel cimitero dallo $badguy[creaturename]`n$taunt");
            $session['user']['alive']=false;
            debuglog("perde {$session['user']['gold']} oro e 10% exp ucciso dallo spirito guardiano");
            $session['user']['gold']=0;
            $session['user']['hitpoints']=0;
            $session['user']['badguy']="";
            $session['user']['experience'] = intval(0.9 * $session['user']['experience']);
            output("`b`&Sei stato ucciso dallo `%{$badguy['creaturename']}`&!!!`n");
            output("`4Ti priva dei tuoi averi!`n Perdi anche il 10% della tua esperienza!`n");
            output("`\$Dovrai aspettare un nuovo giorno per affrontare altre avventure.");
            page_footer();
        }else{
          fightnav(true,false);
        }
    }
}
page_footer();
?>