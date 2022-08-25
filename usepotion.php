<?php
/*
Portable Potions with clickable icons
Author: Lonnyl of http://www.pqcomp.com/logd
Dificulty: Medium
E-Mail: logd@pqcomp.com
version 1.0
June 2004
Hope I got it all in here! 8)
This puts icons in the stats for potions... to use it click on the potion icon!
Adds potion use to every page without much coding!
Will not allow potion use during battle or from newday (newday creates infinate loop)
Also for safety sake if it is loaded from badnav or corrupts navs.. it will return the
player to the village.

Potions are more expensive that healing... but then that is the price of convenience
They also can use a potion if they do not need it.... Hey, they should not have clicked there.

in common.php before $u['hitpoints']=round($u['hitpoints'],0);
add
$currentpage=$_SERVER['REQUEST_URI'];
                if (strstr($currentpage, "?op") !=""){
                    $position=strrpos($currentpage,"?op");
                    $currentpage=substr($currentpage,0,$position);
                }
                $currentpage=str_replace("/logd/","",$currentpage);
//begin potion meter
        global $badguy;
        for ($i=0;$i<6;$i+=1){
            if ($session['user']['potion']>$i){
                if ($badguy['creaturename']<>"" or $session['user']['alive']==0 or strstr($currentpage, "usepotion") !="" or strstr($currentpage, "usechow") !="" or strstr($currentpage, "newday") !=""){
                $potion.="<img src=\"./images/potion.gif\" title=\"\" alt=\"\" style=\"width: 14px; height: 20px;\">";
                }else{
                $potion.="<a href=\"usepotion.php\"><img src=\"./images/potion.gif\" title=\"\" alt=\"\" style=\"border: 0px solid ; width: 14px; height: 20px;\"></a>";
                addnav("","usepotion.php");
                }
            }else{
                $potion.="<img src=\"./images/potionclear.gif\" title=\"\" alt=\"\" style=\"width: 14px; height: 20px;\">";
            }
        }
        //end potion meter

and after .templatereplace("statrow",array("title"=>"Gems","value"=>$u['gems']))
add
.templatereplace("statrow",array("title"=>"Potions","value"=>$potion))

and after $session[output]=$output;
add
$currentpage=$_SERVER['REQUEST_URI'];
                if (strstr($currentpage, "?op") !=""){
                    $position=strrpos($currentpage,"?op");
                    $currentpage=substr($currentpage,0,$position);
                }
                $currentpage=str_replace("/logd/","",$currentpage);
    if ($currentpage != "usepotion.php" or $currentpage != "usechow.php"){
        $session['user']['pqrestorepage']=$currentpage;
    }


in the healer.php and/or adapt for your potion shop
after $cost = ($loglev * ($session[user][maxhitpoints]-$session[user][hitpoints])) + ($loglev*10);
add
$potioncost = ($loglev * ($session[user][maxhitpoints])) + ($loglev*10);
if ($potioncost == 0) $potioncost=$session['user']['dragonkills']*5;

and before
if ($_GET[op]==""){
    checkday();
add
if ($_GET[op] == "heal"){
    output("Doc Quinn eagerly snatches your gold and tosses you a vile with a strange liquid in it.`n");
    output("You carefully place it in your pack.");
    $session['user']['gold']-=round(100*$potioncost/100,0);
    $session['user']['potion']+=1;
    addnav("Continue","healer.php");
}else{

and after
if ($_GET[op]==""){
    checkday();

add
if ($session[user][gold] >= round(100*$potioncost/100,0) and $session['user']['potion']<5){
    output("`2Special Potion!");
    output("`3Healing Potion`7 costs `6".round(100*$potioncost/100,0)." gold`7. You can carry up to 5 with you to heal yourself.`n`n");
    addnav("Specials");
    addnav("Healing Potion","healer.php?op=heal");
    }

and before page_footer();
add
}

MYSQL Addition
ALTER TABLE accounts ADD `potion` int(11) NOT NULL default '0'
ALTER TABLE accounts ADD `pqrestorepage` varchar(128) default ''
*/
require_once "common.php";
checkday();
page_header("Pozioni");
output("`c`b`&<big><big>Pozione Guaritrice</big></big>`0`b`c`n`n",true);
if ($session['user']['hitpoints'] > 0){}else{
    redirect("shades.php");
}
$session['return']=$_GET['ret'];
checkday();
if ($session['user']['alive']) {
   if ($session['return']!=""){
      $return = preg_replace("'[&?]c=[[:digit:]-]+'","",$session['return']);
      $return = substr($return,strrpos($return,"/")+1);
      $sql = "INSERT INTO pozioni (acctid,tipo,url) VALUES ('".$session['user']['acctid']."','pozione','".$return."')";
      db_query($sql) or die(db_error(LINK));
      addnav("Continua",$return);
   }else{
      addnav("Navigazione Corrotta","village.php");
   }
}
$session['return']="";
/*
$rp = $session['user']['pqrestorepage'];
        $x = max(strrpos("&",$rp),strrpos("?",$rp));
        if ($x>0) $rp = substr($rp,0,$x);
        if (substr($rp,0,10)=="badnav.php" or substr($rp,0,10)=="newday.php") {
           addnav("Continue","village.php");
        } else {
          if ($session[user][superuser]>=2){
             output("`n`bDEBUG: rp = $rp`b`n`n");
          }
          if (!$rp) {
             addnav("Continua","village.php");
          } else {
            addnav("Continua",preg_replace("'[?&][c][=].+'","",$rp));
          }
        }
*/
output("`@Trangugi la pozione, e percepisci un immediato senso di guarigione pervadere il tuo corpo!`n");
$session['user']['potion']-=1;
$session['user']['hitpoints']=$session['user']['maxhitpoints'];
debuglog("usa una pozione guaritrice");
page_footer();
?>