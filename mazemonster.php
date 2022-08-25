<?php
/*
mazemonster.php part of the Abandonded Castle Mod By Lonnyl @ http://www.pqcomp.com/logd
Author Lonnyl
version 1.01
June 2004
*/
require_once "common.php";
checkday();
$sex = $session['user']['sex'];
page_header("Mostro del Labirinto");
if ($_GET[op]=="ghost1"){
    $badguy = array(        "creaturename"=>"`@Spettro Senza Corpo`0"
                                ,"creaturelevel"=>0
                                ,"creatureweapon"=>"Poteri Spettrali"
                                ,"creatureattack"=>1
                                ,"creaturedefense"=>2
                                ,"creaturehealth"=>1000
                                ,"diddamage"=>0);


                                $userattack=$session['user']['attack']+e_rand(1,3);
                                $userhealth=round($session['user']['hitpoints']/2);
                                $userdefense=$session['user']['defense']+e_rand(1,3);
                                $badguy['creaturelevel']=$session['user']['level'];
                                $badguy['creatureattack']+=($userattack*.5);
                                $badguy['creaturehealth']+=$userhealth;
                                $badguy['creaturedefense']+=($userdefense*2);
                                $session['user']['badguy']=createstring($badguy);
    redirect("mazemonster.php?op=fight");
}
if ($_GET[op]=="ghost2"){
    $badguy = array(        "creaturename"=>"`@Spettro Arrabbiato`0"
                                ,"creaturelevel"=>0
                                ,"creatureweapon"=>"Poteri Spettrali"
                                ,"creatureattack"=>1
                                ,"creaturedefense"=>2
                                ,"creaturehealth"=>400
                                ,"diddamage"=>0);


                                $userattack=$session['user']['attack']+e_rand(1,3);
                                $userhealth=round($session['user']['hitpoints']/2);
                                $userdefense=$session['user']['defense']+e_rand(1,3);
                                $badguy['creaturelevel']=$session['user']['level'];
                                $badguy['creatureattack']+=($userattack*.5);
                                $badguy['creaturehealth']+=$userhealth;
                                $badguy['creaturedefense']+=($userdefense*1.5);
                                $session['user']['badguy']=createstring($badguy);
    redirect("mazemonster.php?op=fight");
}
if ($_GET[op]=="bat"){
    $badguy = array(        "creaturename"=>"`@Pipistrello`0"
                                ,"creaturelevel"=>0
                                ,"creatureweapon"=>"Zanne Taglienti"
                                ,"creatureattack"=>1
                                ,"creaturedefense"=>2
                                ,"creaturehealth"=>1
                                ,"diddamage"=>0);


                                $userattack=$session['user']['attack']+e_rand(1,3);
                                $userhealth=round($session['user']['hitpoints']/2);
                                $userdefense=$session['user']['defense']+e_rand(1,3);
                                $badguy['creaturelevel']=$session['user']['level'];
                                $badguy['creatureattack']+=($userattack*.5);
                                $badguy['creaturehealth']+=($userhealth*.5);
                                $badguy['creaturedefense']+=($userdefense*.5);
                                $session['user']['badguy']=createstring($badguy);
    redirect("mazemonster.php?op=fight");
}
if ($_GET[op]=="rat"){
    $badguy = array(        "creaturename"=>"`@Ratto Enorme`0"
                                ,"creaturelevel"=>0
                                ,"creatureweapon"=>"Zanne Affilate"
                                ,"creatureattack"=>1
                                ,"creaturedefense"=>2
                                ,"creaturehealth"=>1
                                ,"diddamage"=>0);


                                $userattack=$session['user']['attack']+e_rand(1,3);
                                $userhealth=round($session['user']['hitpoints']/2);
                                $userdefense=$session['user']['defense']+e_rand(1,3);
                                $badguy['creaturelevel']=$session['user']['level'];
                                $badguy['creatureattack']+=($userattack*.75);
                                $badguy['creaturehealth']+=($userhealth*.75);
                                $badguy['creaturedefense']+=($userdefense*.75);
                                $session['user']['badguy']=createstring($badguy);
    redirect("mazemonster.php?op=fight");
}
if ($_GET[op]=="minotaur"){
    $badguy = array(        "creaturename"=>"`@Minotauro`0"
                                ,"creaturelevel"=>0
                                ,"creatureweapon"=>"Zanne Mortali"
                                ,"creatureattack"=>1
                                ,"creaturedefense"=>40
                                ,"creaturehealth"=>1000
                                ,"diddamage"=>0);


                                $userattack=$session['user']['attack']+e_rand(1,3);
                                $userhealth=round($session['user']['hitpoints']/2);
                                $userdefense=$session['user']['defense']+e_rand(1,3);
                                $badguy['creaturelevel']=$session['user']['level'];
                                $badguy['creatureattack']+=($userattack-4);
                                $badguy['creaturehealth']+=$userhealth;
                                $badguy['creaturedefense']+=$userdefense;
                                $session['user']['badguy']=createstring($badguy);
    redirect("mazemonster.php?op=fight");
}

if ($_GET[op]=="fight" or $_GET[op]=="run"){
$battle=true;
$fight=true;
if ($battle){
    include_once ("battle.php");

    if ($victory){
                output("`b`4Hai ucciso `^".$badguy['creaturename']."`4.`b`n");
                $badguy=array();
                $session['user']['badguy']="";
                $session['user']['specialinc']="";
                $gold=e_rand(100,500);
                $experience=$session['user']['level']*e_rand(37,99);
                output("`#Ricevi `6$gold `#pezzi d'oro!`n");
                $session['user']['gold']+=$gold;
                output("`#Guadagni `6$experience `#punti esperienza!`n");
                $session['user']['experience']+=$experience;
                addnav("`@Continua","abandoncastle.php?loc=".$session['user']['pqtemp']);
    }elseif ($defeat){
                output("Mentre cadi al suolo `^".$badguy['creaturename']." scappa via.");
                addnews("`%".$session['user']['name']."`5 è stat".($sex?"a":"o")." uccis".($sex?"a":"o")." quando ha incontrato un ".$badguy['creaturename']." nel Castello Abbandonato.");
                $experience=intval($session['user']['experience']*0.15);
                output("`#Perdi `6$experience `#Punti Esperienza!`n");
                $session['user']['experience']-=$experience;
                $badguy=array();
                $session['user']['badguy']="";
                $session['user']['hitpoints']=0;
                $session['user']['alive']=0;
                $session['user']['specialinc']="";
                addnav("`\$Continua","shades.php");
    }else{
            if ($fight){
            fightnav(true,false);
            if ($badguy['creaturehealth'] > 0){
            $hp=$badguy['creaturehealth'];
        }
        }
    }
    }else{
        redirect("abandoncastle.php?loc=".$session['user']['pqtemp']);
    }
}
//I cannot make you keep this line here but would appreciate it left in.
rawoutput("<div style=\"text-align: right;\">Abandonded Castle by Lonny<br>");
page_footer();
?>