<?php
/*
Chow with clickable icons
Author: Lonnyl of http://www.pqcomp.com/logd
Dificulty: Medium/Difficult
E-Mail: logd@pqcomp.com
version 1.0
July 2004

Note some of these inclusions are in the portable potions mod!
Note this has not been tested or run with any other hunger mod!
Note eating food at draco's diner is more filling that what you take with.
Note food gets more expensive with dragon kills.
Note chow is serialized... each digit in the number represents a inventory item

Hope I got it all in here! 8)
This puts icons in the stats for chow... to use it click on the chow icon!
Adds chow use to every page without much coding!
Will not allow chow use during battle or from newday (newday creates infinate loop)
Also for safety sake if it is loaded from badnav or corrupts navs.. it will return the
player to the village.

Draco's Diner is for purchasing chow and is set to return to the village.... adjust for your realm

OK the hard part of this mod is to adjust a persons hunger level.....
Any food in any other mod I added lines to adjust hunger
$session['user']['hunger']-=5;

in newday.php I up the hunger level each new day
$session['user']['hunger']+=10;

adjust hunger anywhere else you feel the need.

OPTIONAL!
I give new players a little bit of chow and a potion in newday.php
after
$session['user']['specialty']=$_GET['setspecialty'];
    }
add
if ($session['user']['level']==1 and $session['user']['dragonkills']==0){
        $session['user']['chow']=12367;
        $session['user']['potion']=1;
    }
end OPTIONAL

and battling creatures and others should make you hungry as you expend energy
in battle.php after
if ($session[user][hitpoints]>0 && $badguy[creaturehealth]>0){
add
if ($session['user']['alive']==1) $session['user']['hunger']+=1;



in common.php
in the checkday function
after function checkday($CheckIfDead=FALSE) {
add
//check if they are starving
    if ($session[user][alive]==1){
    if ($session['user']['hunger']>160) output("`4`c`bYou are very Hungry!`b`c'");
    if ($session['user']['hunger']>200){
        output("`4`c`bYou are starving to death!`b`c'");
        $session['user']['hitpoints']*=.90;
    }
    }

before $u['hitpoints']=round($u['hitpoints'],0);
add
$currentpage=$_SERVER['REQUEST_URI'];
                if (strstr($currentpage, "?op") !=""){
                    $position=strrpos($currentpage,"?op");
                    $currentpage=substr($currentpage,0,$position);
                }
                //logd may need to be adjusted to fit your game location
                $currentpage=str_replace("/logd/","",$currentpage);
//begin chow meter
        global $badguy;

        for ($i=0;$i<6;$i+=1){
            if ($session['user']['chow']>$i){
                $mychow[$i]=substr(strval($session['user']['chow']),$i,1);
                if ($badguy['creaturename']<>"" or $session['user']['alive']==0 or strstr($currentpage, "usechow") !="" or strstr($currentpage, "usepotion") !="" or strstr($currentpage, "newday") !=""){
                if ($mychow[$i]=="1") $chow.="<img src=\"./images/bread.gif\" title=\"\" alt=\"\" style=\"width: 14px; height: 16px;\">";
                if ($mychow[$i]=="2") $chow.="<img src=\"./images/pork.gif\" title=\"\" alt=\"\" style=\"width: 14px; height: 16px;\">";
                if ($mychow[$i]=="3") $chow.="<img src=\"./images/ham.gif\" title=\"\" alt=\"\" style=\"width: 14px; height: 16px;\">";
                if ($mychow[$i]=="4") $chow.="<img src=\"./images/steak.gif\" title=\"\" alt=\"\" style=\"width: 14px; height: 16px;\">";
                if ($mychow[$i]=="5") $chow.="<img src=\"./images/chicken.gif\" title=\"\" alt=\"\" style=\"width: 14px; height: 16px;\">";
                if ($mychow[$i]=="6") $chow.="<img src=\"./images/milk.gif\" title=\"\" alt=\"\" style=\"width: 14px; height: 16px;\">";
                if ($mychow[$i]=="7") $chow.="<img src=\"./images/water.gif\" title=\"\" alt=\"\" style=\"width: 14px; height: 16px;\">";
                }else{
                if ($mychow[$i]=="1"){
                    $chow.="<a href=\"usechow.php?op=bread\"><img src=\"./images/bread.gif\" title=\"\" alt=\"\" style=\"border: 0px solid ; width: 14px; height: 16px;\"></a>";
                    addnav("","usechow.php?op=bread");
                }
                if ($mychow[$i]=="2"){
                    $chow.="<a href=\"usechow.php?op=pork\"><img src=\"./images/pork.gif\" title=\"\" alt=\"\" style=\"border: 0px solid ; width: 14px; height: 16px;\"></a>";
                    addnav("","usechow.php?op=pork");
                }
                if ($mychow[$i]=="3"){
                    $chow.="<a href=\"usechow.php?op=ham\"><img src=\"./images/ham.gif\" title=\"\" alt=\"\" style=\"border: 0px solid ; width: 14px; height: 16px;\"></a>";
                    addnav("","usechow.php?op=ham");
                }
                if ($mychow[$i]=="4"){
                    $chow.="<a href=\"usechow.php?op=steak\"><img src=\"./images/steak.gif\" title=\"\" alt=\"\" style=\"border: 0px solid ; width: 14px; height: 16px;\"></a>";
                    addnav("","usechow.php?op=steak");
                }
                if ($mychow[$i]=="5"){
                    $chow.="<a href=\"usechow.php?op=chicken\"><img src=\"./images/chicken.gif\" title=\"\" alt=\"\" style=\"border: 0px solid ; width: 14px; height: 16px;\"></a>";
                    addnav("","usechow.php?op=chicken");
                }
                if ($mychow[$i]=="6"){
                    $chow.="<a href=\"usechow.php?op=milk\"><img src=\"./images/milk.gif\" title=\"\" alt=\"\" style=\"border: 0px solid ; width: 14px; height: 16px;\"></a>";
                    addnav("","usechow.php?op=milk");
                }
                if ($mychow[$i]=="7"){
                    $chow.="<a href=\"usechow.php?op=water\"><img src=\"./images/water.gif\" title=\"\" alt=\"\" style=\"border: 0px solid ; width: 14px; height: 16px;\"></a>";
                    addnav("","usechow.php?op=water");
                }
            }
            }else{
                $chow.="<img src=\"./images/breadclear.gif\" title=\"\" alt=\"\" style=\"width: 14px; height: 16px;\">";
            }
        }
        //end chow meter
        //hunger meter
        $hunger="`7- ";
        $len=0;
        $len2=0;
        for ($i=0;$i<200;$i+=10){
            if ($session['user']['hunger']>$i){
                $len+=2;
            }else{
                $len2+=2;
            }
        }
        $hunger.="<img src=\"./images/hmeter.gif\" title=\"\" alt=\"\" style=\"width: $len\px; height: 10px;\">";
        $hunger.="<img src=\"./images/hmeterclear.gif\" title=\"\" alt=\"\" style=\"width: $len2\px; height: 10px;\">";
        $hunger.="`7 +";
        //end hunger meter

and after .templatereplace("statrow",array("title"=>"Gems","value"=>$u['gems']))
add
.templatereplace("statrow",array("title"=>"Chow","value"=>$chow))

and after templatereplace("statrow",array("title"=>"Hitpoints","value"=> (not showing all of it as there are different versions)
.templatereplace("statrow",array("title"=>"Hunger","value"=>$hunger))

and after $session[output]=$output;
add
$currentpage=$_SERVER['REQUEST_URI'];
                if (strstr($currentpage, "?op") !=""){
                    $position=strrpos($currentpage,"?op");
                    $currentpage=substr($currentpage,0,$position);
                }
                //logd may need to be adjusted to fit your game location
                $currentpage=str_replace("/logd/","",$currentpage);
    if ($currentpage != "usepotion.php" or $currentpage != "usechow.php"){
        $session['user']['pqrestorepage']=$currentpage;
    }


MYSQL Addition
ALTER TABLE accounts ADD `hunger` int(11) NOT NULL default '0'
ALTER TABLE accounts ADD `chow` int(11) NOT NULL default '0'
ALTER TABLE accounts ADD `pqrestorepage` varchar(128) default ''
*/
require_once "common.php";
checkday();
page_header("Chow");
//read op and use the appropriate chow...
for ($i=0;$i<6;$i+=1){
$chow[$i]=substr(strval($session['user']['chow']),$i,1);
}
if ($_GET[op] == "bread"){
    output("`c`b`&Mangi un pezzo di pane`0`b`c`n`n");
    $usedchow=1;
    output("Divori il panino non lasciando cadere neanche una briciola!`n");
    $session['user']['hunger']-=15;
}
if ($_GET[op] == "pork"){
    output("`c`b`&Mangi una braciola di maiale`0`b`c`n`n");
    $usedchow=2;
    output("Divori la braciola di maiale!`n");
    $session['user']['hunger']-=22;
}
if ($_GET[op] == "ham"){
    output("`c`b`&Mangi una bistecca di cavallo`0`b`c`n`n");
    $usedchow=3;
    output("Divori la bistecca di cavallo!`n");
    $session['user']['hunger']-=30;
}
if ($_GET[op] == "steak"){
    output("`c`b`&Mangi un filetto`0`b`c`n`n");
    $usedchow=4;
    output("Divori il filetto!`n");
    $session['user']['hunger']-=43;
}
if ($_GET[op] == "chicken"){
    output("`c`b`&Mangi un pollo`0`b`c`n`n");
    $usedchow=5;
    output("Divori l'intero pollo!`n");
    $session['user']['hunger']-=50;
}
if ($_GET[op] == "milk"){
    output("`c`b`&Bevi del latte`0`b`c`n`n");
    $usedchow=6;
    output("Tracanni l'intera bottiglia di latte!`n");
    $session['user']['hunger']-=12;
    $session['user']['bladder']+=1;
}
if ($_GET[op] == "water"){
    output("`c`b`&Bevi dell'acqua`0`b`c`n`n");
    $usedchow=7;
    output("Tracanni l'intera fiasca d'acqua!`n");
    $session['user']['hunger']-=3;
    $session['user']['bladder']+=1;
}
if ($session['return']==""){
   $session['return']=$_GET['ret'];
}
checkday();
if ($session['user']['alive']) {
   if ($session['return']!=""){
      $return = preg_replace("'[&?]c=[[:digit:]-]+'","",$session['return']);
      $return = substr($return,strrpos($return,"/")+1);
      addnav("Continua",$return);
      $sql = "INSERT INTO pozioni (acctid,tipo,url) VALUES ('".$session['user']['acctid']."','cibo','$return')";
      db_query($sql) or die(db_error(LINK));
   }else{
      addnav("Navigazione Corrotta","village.php");
   }
}
$session['return']="";
/*
$rp = $session['user']['pqrestorepage'];
        $x = max(strrpos("&",$rp),strrpos("?",$rp));
        if ($x>0) $rp = substr($rp,0,$x);
        if (substr($rp,0,10)=="badnav.php" or substr($rp,0,10)=="newday.php"){
            addnav("`@Continua","village.php");
        }else{
            addnav("`@Continua",preg_replace("'[?&][c][=].+'","",$rp));
        }
*/
for ($i=0;$i<6;$i+=1){
    if ($usedchow<>""){
        if ($usedchow==$chow[$i]){
            $usedchow="";
            $chow[$i]="";
        }
    }
    $newchow.=$chow[$i];
}
$newchow.="0";
$session['user']['chow']=$newchow;
page_footer();
?>