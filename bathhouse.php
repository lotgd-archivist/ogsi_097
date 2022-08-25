<?php
/*
Bathhouse and Odor
with 2 forest specials and graphical stats
Difficulty: Medium
Author Lonny Luberts of http://www.pqcomp.com/logd
Tranlate in italian by Excalibur www.ogsi.it/logd
version 1.0
July 2004


In common.php
before $u['hitpoints']=round($u['hitpoints'],0);
add
//begin odor meter
        $odor="`7- ";
        $len=0;
        $len2=0;
        for ($i=1;$i<21;$i+=1){
            if ($session['user']['clean']>$i){
                $len+=2;
            }else{
                $len2+=2;
            }
        }
            $odor.="<img src=\"./images/ometer.gif\" title=\"\" alt=\"\" style=\"width: $len\px; height: 10px;\">";
            $odor.="<img src=\"./images/hmeterclear.gif\" title=\"\" alt=\"\" style=\"width: $len2\px; height: 10px;\">";
        $odor.="`7 +";
        //end odor meter

and after templatereplace("statrow",array("title"=>"Hitpoints","value"=> (not showing all of it as there are different versions)
.templatereplace("statrow",array("title"=>"Odor","value"=>$odor))

in newday.php after $session['user']['bounties']=0;
add
//begin cleanliness code
    if ($session ['user']['clean'] > 2) $session['user']['charm']-=($session['user']['clean']-2);
    $session['user']['clean']+=1;
    if ($session['user']['clean']>9 and $session['user']['clean']<15) addnews($session['user']['name']."`2 is pretty stinky!");
    if ($session['user']['clean']>14 and $session['user']['clean']<20){
        output("You can hardly stand the smell of yourself!");
        addnews($session['user']['name']."`2 smells really bad!");
    }
    if ($session['user']['clean']>19){
        output("You have earned the title of PigPen for being so dirty!`n");
            $name=$session['user']['name'];
            addnews("$name `7was awarded the title of PigPen for being so dirty!");
            $newtitle="PigPen";
            $n = $session['user']['name'];
            $x = strpos($n,$session['user']['title']);
            if ($x!==false){
                $regname=substr($n,$x+strlen($session['user']['title']));
                $session['user']['name'] = substr($n,0,$x).$newtitle.$regname;
                $session['user']['title'] = $newtitle;
            }else{
                $regname = $session['user']['name'];
                $session['user']['name'] = $newtitle." ".$session['user']['name'];
                $session['user']['title'] = $newtitle;
            }
            //remove unamecolor if you are not using my colored names mod
            unamecolor();
    }
    //end cleanliness code

add to your files where you would like
$session['user']['clean'] += 1; (or whatever value)
when you want to make someone dirty

Mysql inclusions
ALTER TABLE accounts ADD `clean` int(100) NOT NULL default '0'
*/

require_once "common.php";
require_once("common2.php");
checkday();
page_header("Le Docce del Villaggio");
$session['user']['locazione'] = 109;
//checkevent("bathhouse.php");
output("`c`b`&Le Docce del Villaggio`0`b`c`n`n");
if ($_GET['op'] == ""){
output("`2Entrando nella casa che ospita le docce del villaggio noti subito il grande disordine, compreso la vecchia ");
output("che le gestisce.  Ci sono tende attorno a ciascuna doccia per la privacy.  Pensi che una piacevole doccia calda ");
output("ti farebbe sentire meglio.  La vecchia ti guarda curiosa e ti indica un cartello alla parete.  Il cartello dice ");
output("che una doccia ti costerà `65 `2pezzi d'oro.`n");
addnav("`^Fai una Doccia","bathhouse.php?op=bathe");
addnav("`@Torna al Villaggio","village.php");
}
if ($_GET['op'] == "bathe"){
	
	$identarmatura=array();
	$ident_armatura = identifica_armatura();
	$articoloarmatura = $ident_armatura['articolo'];
	
    if ($session['user']['gold']<5){
    output("`2Cerchi nelle tue tasche alla ricerca dei 5 pezzi d'oro, ma non li trovi.  La vecchia si gira e ti indica la porta.`n");
    addnav("`@Torna al Villaggio","village.php");
    }else{
    output("`2Dai alla vecchia i tuoi 5 pezzi d'oro e lei senza dire una parola ti conduce ad una doccia e tira la tenda. `nTi sfili $articoloarmatura `#{$session['user']['armor']}`2 e ti infili sotto l'acqua calda iniziando a togliere lo sporco della foresta e del villaggio dal tuo corpo. `n
    La doccia ti fa sentire divinamente e potresti stare qui all'infinito, ma non appena ti sei abituato all'acqua calda che ti scorre sulla pelle la vecchia riapre indelicatamente la tenda e gesticola per farti uscire. `n 
    Poi richiude la tenda e ti lascia giusto il tempo di asciugarti e rivestirti.  `n`n`@Ti senti molto meglio dopo la doccia ristoratrice!`n");
    $session['user']['clean']=0;
    $session['user']['gold']-=5;
    debuglog("ha speso 5 oro per una doccia");
    addnav("`@Torna al Villaggio","village.php");
    }
}
//I cannot make you keep this line here but would appreciate it left in.
//rawoutput("<div style=\"text-align: left;\"><a href=\"http://www.pqcomp.com\" target=\"_blank\">Bathhouse and Odor by Lonny @ http://www.pqcomp.com</a><br>");
page_footer();
?>