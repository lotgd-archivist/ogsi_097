<?php
/*
 * Major MAJOR revamps by JT from logd.dragoncat.net  Frankly I threw out my code and used his.
 *
 */
require_once("common.php");

function activate_buffs($tag) {
    global $session, $badguy;
    reset($session['bufflist']);
    $result = array();
    $result['invulnerable'] = 0;
    $result['dmgmod'] = 1;
    $result['badguydmgmod'] = 1;
    $result['atkmod'] = 1;
    $result['badguyatkmod'] = 1;
    $result['defmod'] = 1;
    $result['badguydefmod'] = 1;
    $result['lifetap'] = array();
    $result['dmgshield'] = array();
    $result['protectiveshield'] = array();

    while(list($key,$buff) = each($session['bufflist'])) {
        if (isset($buff['startmsg'])) {
            $msg = $buff['startmsg'];
            $msg = str_replace("{badguy}", $badguy['creaturename'], $msg);
            output("`%$msg`0");
            unset($session['bufflist'][$key]['startmsg']);
        }
        $activate = strpos($buff['activate'], $tag);
        if ($activate !== false) $activate = true; // handle strpos == 0;

        // If this should activate now and it hasn't already activated,
        // do the round message and mark it.
        if ($activate && !$buff['used']) {
            // mark it used.
            $session['bufflist'][$key]['used'] = 1;
            // if it has a 'round message', run it.
            if (isset($buff['roundmsg'])) {
                $msg = $buff['roundmsg'];
                $msg = str_replace("{badguy}", $badguy['creaturename'], $msg);
                output("`)$msg`0`n");
            }
        }

        // Now, calculate any effects and run them if needed.
        if (isset($buff['invulnerable'])) {
            $result['invulnerable'] = 1;
        }
        if (isset($buff['atkmod'])) {
            $result['atkmod'] *= $buff['atkmod'];
        }
        if (isset($buff['badguyatkmod'])) {
            $result['badguyatkmod'] *= $buff['badguyatkmod'];
        }
        if (isset($buff['defmod'])) {
            $result['defmod'] *= $buff['defmod'];
        }
        if (isset($buff['badguydefmod'])) {
            $result['badguydefmod'] *= $buff['badguydefmod'];
        }
        if (isset($buff['dmgmod'])) {
            $result['dmgmod'] *= $buff['dmgmod'];
        }
        if (isset($buff['badguydmgmod'])) {
            $result['badguydmgmod'] *= $buff['badguydmgmod'];
        }
        if (isset($buff['lifetap'])) {
            array_push($result['lifetap'], $buff);
        }
        if (isset($buff['damageshield'])) {
            array_push($result['dmgshield'], $buff);
        }
        if (isset($buff['protectiveshield'])) {
            array_push($result['protectiveshield'], $buff);
        }
        if (isset($buff['regen']) && $activate) {
            $hptoregen = (int)$buff['regen'];
            $hpdiff = $session['user']['maxhitpoints'] -
            $session['user']['hitpoints'];
            // Don't regen if we are above max hp
            if ($hpdiff < 0) $hpdiff = 0;
            if ($hpdiff < $hptoregen) $hptoregen = $hpdiff;
            $session['user']['hitpoints'] += $hptoregen;
            // Now, take abs value just incase this was a damaging buff
            $hptoregen = abs($hptoregen);
            if ($hptoregen == 0) $msg = $buff['effectnodmgmsg'];
            else $msg = $buff['effectmsg'];
            $msg = str_replace("{badguy}", $badguy['creaturename'], $msg);
            $msg = str_replace("{damage}", $hptoregen, $msg);
            output("`)$msg`0`n");
        }
        if (isset($buff['minioncount']) && $activate) {
            $who = -1;
            if ($buff['maxbadguydamage']  <> 0) {
                $max = $buff['maxbadguydamage'];
                $min = $buff['minbadguydamage'];
                $who = 0;
            } else {
                $max = $buff['maxgoodguydamage'];
                $min = $buff['mingoodguydamage'];
                $who = 1;
            }
            for ($i = 0; $who >= 0 && $i < $buff['minioncount']; $i++) {
                $damage = e_rand($min, $max);
                if ($who == 0) {
                    $badguy['creaturehealth'] -= $damage;
                } else if ($who == 1) {
                    $session['user']['hitpoints'] -= $damage;
                }
                if ($damage < 0) {
                    $msg = $buff['effectfailmsg'];
                } else if ($damage == 0) {
                    $msg = $buff['effectnodmgmsg'];
                } else if ($damage > 0) {
                    $msg = $buff['effectmsg'];
                }
                if ($msg>"") {
                    $msg = str_replace("{badguy}", $badguy['creaturename'], $msg);
                    $msg = str_replace("{goodguy}", $session['user']['name'], $msg);
                    $msg = str_replace("{damage}", $damage, $msg);
                    output("`)$msg`0`n");
                }
            }
        }
    }
    return $result;
}

function process_lifetaps($ltaps, $damage) {
    global $session, $badguy;
    reset($ltaps);
    while(list($key,$buff) = each($ltaps)) {
        $healhp = $session['user']['maxhitpoints'] -
            $session['user']['hitpoints'];
        if ($healhp < 0) $healhp = 0;
        if ($healhp == 0) {
            $msg = $buff['effectnodmgmsg'];
        } else {
            if ($healhp > $damage * $buff['lifetap'])
                $healhp = $damage * $buff['lifetap'];
            if ($healhp < 0) $healhp = 0;
            if ($damage > 0) {
                $msg = $buff['effectmsg'];
            } else if ($damage == 0) {
                $msg = $buff['effectfailmsg'];
            } else if ($damage < 0) {
                $msg = $buff['effectfailmsg'];
            }
        }
        //Excalibur: modifica per evitare numeri decimali
        $healhp = round($healhp);
        //Excalibur: fine modifica
        $session['user']['hitpoints'] += $healhp;
        $msg = str_replace("{badguy}",$badguy['creaturename'], $msg);
        $msg = str_replace("{damage}",$healhp, $msg);
        if ($msg > "") output("`)$msg`n");
    }
}

function process_dmgshield($dshield, $damage) {
    global $session, $badguy;
    reset($dshield);
    while(list($key,$buff) = each($dshield)) {
        $realdamage = $damage * $buff['damageshield'];
        if ($realdamage < 0) $realdamage = 0;
        if ($realdamage > 0) {
            $msg = $buff['effectmsg'];
        } else if ($realdamage == 0) {
            $msg = $buff['effectnodmgmsg'];
        } else if ($realdamage < 0) {
            $msg = $buff['effectfailmsg'];
        }
        $realdamage = (int)$realdamage;
        $badguy['creaturehealth'] -= $realdamage;
        $msg = str_replace("{badguy}",$badguy['creaturename'], $msg);
        $msg = str_replace("{damage}",$realdamage, $msg);
        if ($msg > "") output("`)$msg`n");
    }
}

//Sook, buff scudo (assorbe parte dei danni)
function process_protshield($pshield, $damage) {
    global $session, $badguy;
    reset($pshield);
    while(list($key,$buff) = each($pshield)) {
        //Sook, modifica per accettare {damage}
        if (strstr($buff['protectiveshield'],"{damage}") != ""){
             $oper="";
             $num=str_replace("{damage}","",$buff['protectiveshield']);
             if (strstr($buff['protectiveshield'],"*") != ""){
                 $num=str_replace("*","",$num);
                 $oper="*";
             }
             if (strstr($buff['protectiveshield'],"/") != ""){
                 $num=str_replace("/","",$num);
                 $oper="/";
             }
             if (strstr($buff['protectiveshield'],"+") != ""){
                 $num=str_replace("+","",$num);
                 $oper="+";
             }
             if (strstr($buff['protectiveshield'],"-") != ""){
                 $num=str_replace("-","",$num);
                 $oper="-";
             }
             $num=trim($num);
             switch ($oper){
                 case "*":
                     $buff['protectiveshield']=$damage*$num;
                 break;
                 case "/":
                     $buff['protectiveshield']=$damage/$num;
                 break;
                 case "+":
                     $buff['protectiveshield']=$damage+$num;
                 break;
                 case "-":
                     $buff['protectiveshield']=$damage-$num;
                 break;
                 case "":
                     $buff['protectiveshield']=$damage;
                 break;
             }
             $buff['protectiveshield']=intval($buff['protectiveshield']);
        }
        //Fine modifica
        if ($damage > 0) {
            $dmgabs = min($buff['protectiveshield'], $damage);
            $damage -= $dmgabs;
            $msg = $buff['effectmsg'];
        } else if ($damage == 0) {
            $msg = $buff['effectnodmgmsg'];
        } else if ($damage < 0) {
            $msg = $buff['effectfailmsg'];
        }
//Per visualizzare nel messaggio quanto danno è stato assorbito, usare {shield}
        $msg = str_replace("{badguy}",$badguy['creaturename'], $msg);
        $msg = str_replace("{shield}",$dmgabs, $msg);
        $msg = str_replace("{damage}",$realdamage, $msg);
        if ($msg > "") output("`)$msg`n");
    }
    return ($damage);
}
//fine buff scudo

function expire_buffs() {
    global $session, $badguy;
    reset($session['bufflist']);
    while (list($key, $buff) = each($session['bufflist'])) {
        if ($buff['used']) {
            $session['bufflist'][$key]['used'] = 0;
            if ($session['bufflist'][$key]['rounds']>0) //Aggiunta di Excalibur
            $session['bufflist'][$key]['rounds']--;
            if ($session['bufflist'][$key]['rounds'] == 0) { //Modifica di Excalibur x round infiniti con -1
            //if ($session['bufflist'][$key]['rounds'] <= 0) {
                if ($buff['wearoff']) {
                    $msg = $buff['wearoff'];
                    $msg = str_replace("{badguy}", $badguy['creaturename'], $msg);
                    output("`)$msg`n");
                }
                unset($session['bufflist'][$key]);
            }
        }
    }
}
//Modifica AutoFight
if ($_GET[auto]=="full"){
  $count=100;
}else if ($_GET['auto']=="five"){
  $count=5;
}else{
  $count=1;
}
//Fine AutoFight
$badguy = createarray($session['user']['badguy']);

/*if (date("m-d")=="04-01"){
    if (!strpos($badguy['creaturename'],"bork bork")){
        $badguy['creaturename']=$badguy['creaturename']." bork bork";
    }
}*/

$adjustment = ($session['user']['level']/$badguy['creaturelevel']);
if ($badguy['pvp']) $adjustment=1;

if ($_GET['op']=="fight"){

// Modifica CMT per generare buff dinamici
/*
foreach ($spellbuff as $property => $value){
    //calculate dynamic buff fields
    $origstring = $value;
    //simple <variable> replacements for $session['user']['variable']
    $value = preg_replace("/{([A-Za-z0-9]+)}/","\$session['user']['\\1']",$value);
    if ($value != $origstring) {
        $val = eval("return $value;");
    } else {
        $val = $value;
    }
    //Avoiding PHP bug 27646
    // (http://bugs.php.net/bug.php?id=27646&edit=2) -
    // Unserialize doesn't recognize NAN, -INF and INF
    if (function_exists('is_nan')) {
        if (is_numeric($val) && (is_nan($val) || is_infinite($val))) {
            $val=$value;
        }
    } else {
        // We have an older version of PHP, so, let's try something else.
        $l = strtolower("$val");
        if ((substr($l, 3) == "nan") || (substr($l, -3) == "inf")) {
            $val = $value;
        }
    }
    if (!isset($output)) {
        $output = "";
    }
    if ($output == "" && (string)$val != (string)$origstring) {
        $spellbuff[$property] = $val;
    }
    unset($val);
}*/
//Fine modifica CMT

// spells by anpera
    if ($_GET['skill']=="zauber"){
        $resultz=db_query("SELECT * FROM items WHERE id=".$_GET['itemid']) or die(db_error(LINK));
        $zauber = db_fetch_assoc($resultz);
        $spellbuff=unserialize($zauber['buff']);

      //Excalibur: modifica per poter usare {level} come operatore negli incantesimi al posto di $session['user']['level']
      if (strstr($spellbuff['maxbadguydamage'],"{level}") != ""){
         $oper="";
         $num=str_replace("{level}","",$spellbuff['maxbadguydamage']);
         if (strstr($spellbuff['maxbadguydamage'],"*") != ""){
            $num=str_replace("*","",$num);
            $oper="*";
         }
         if (strstr($spellbuff['maxbadguydamage'],"/") != ""){
            $num=str_replace("/","",$num);
            $oper="/";
         }
         if (strstr($spellbuff['maxbadguydamage'],"+") != ""){
            $num=str_replace("+","",$num);
            $oper="+";
         }
         if (strstr($spellbuff['maxbadguydamage'],"-") != ""){
            $num=str_replace("-","",$num);
            $oper="-";
         }
         $num=trim($num);
         $num=intval($num);
      switch ($oper){
         case "*":
            $spellbuff['maxbadguydamage']=$session['user']['level']*$num;
         break;
         case "/":
            $spellbuff['maxbadguydamage']=$session['user']['level']/$num;
         break;
         case "+":
            $spellbuff['maxbadguydamage']=$session['user']['level']+$num;
         break;
         case "-":
            $spellbuff['maxbadguydamage']=$session['user']['level']-$num;
         break;
         case "":
            $spellbuff['minbadguydamage']=$session['user']['level'];
         break;
      }
      }
      if (strstr($spellbuff['minbadguydamage'],"{level}") != ""){
         $oper="";
         $num=str_replace("{level}","",$spellbuff['minbadguydamage']);
         if (strstr($spellbuff['minbadguydamage'],"*") != ""){
            $num=str_replace("*","",$num);
            $oper="*";
         }
         if (strstr($spellbuff['minbadguydamage'],"/") != ""){
            $num=str_replace("/","",$num);
            $oper="/";
         }
         if (strstr($spellbuff['minbadguydamage'],"+") != ""){
            $num=str_replace("+","",$num);
            $oper="+";
         }
         if (strstr($spellbuff['minbadguydamage'],"-") != ""){
            $num=str_replace("-","",$num);
            $oper="-";
         }
         $num=trim($num);
         $num=intval($num);
      switch ($oper){
         case "*":
            $spellbuff['minbadguydamage']=$session['user']['level']*$num;
         break;
         case "/":
            $spellbuff['minbadguydamage']=$session['user']['level']/$num;
         break;
         case "+":
            $spellbuff['minbadguydamage']=$session['user']['level']+$num;
         break;
         case "-":
            $spellbuff['minbadguydamage']=$session['user']['level']-$num;
         break;
         case "":
            $spellbuff['minbadguydamage']=$session['user']['level'];
         break;
      }
      }
      if (strstr($spellbuff['minioncount'],"{level}") != ""){
         $oper="";
         $num=str_replace("{level}","",$spellbuff['minioncount']);
         if (strstr($spellbuff['minioncount'],"*") != ""){
            $num=str_replace("*","",$num);
            $oper="*";
         }
         if (strstr($spellbuff['minioncount'],"/") != ""){
            $num=str_replace("/","",$num);
            $oper="/";
         }
         if (strstr($spellbuff['minioncount'],"+") != ""){
            $num=str_replace("+","",$num);
            $oper="+";
         }
         if (strstr($spellbuff['minioncount'],"-") != ""){
            $num=str_replace("-","",$num);
            $oper="-";
         }
         $num=trim($num);
         $num=intval($num);
      switch ($oper){
         case "*":
            $spellbuff['minioncount']=$session['user']['level']*$num;
         break;
         case "/":
            $spellbuff['minioncount']=$session['user']['level']/$num;
         break;
         case "+":
            $spellbuff['minioncount']=$session['user']['level']+$num;
         break;
         case "-":
            $spellbuff['minioncount']=$session['user']['level']-$num;
         break;
         case "":
            $spellbuff['minioncount']=$session['user']['level'];
         break;
      }
      }
      //Excalibur: fine modifica

        $session['bufflist'][$spellbuff['name']]=$spellbuff;
        $zauber['gold']=round($zauber['gold']*($zauber['value1']/($zauber['value2']+1)));
        $zauber['gems']=round($zauber['gems']*($zauber['value1']/($zauber['value2']+1)));
        $zauber['value1']--;
        if ($zauber['value1']<=0 && $zauber['hvalue']<=0){
            db_query("DELETE FROM items WHERE id=".$_GET['itemid']);
        }else{
            db_query("UPDATE items SET value1=".$zauber['value1'].",
                      gems=".$zauber['gems'].", gold=".$zauber['gold']." WHERE id=".$_GET['itemid']);
        }
    }
// end spells
// incantesimi dei maghi
    if ($_GET['skill']=="incantesimo"){
        $resultz=db_query("SELECT * FROM incantesimi WHERE id=".$_GET['incid']) or die(db_error(LINK));
        $incantesimo = db_fetch_assoc($resultz);
        $spellbuff=unserialize($incantesimo['buff']);

      //Excalibur: modifica per poter usare {level} come operatore negli incantesimi al posto di $session['user']['level']
      if (strstr($spellbuff['maxbadguydamage'],"{level}") != ""){
         $oper="";
         $num=str_replace("{level}","",$spellbuff['maxbadguydamage']);
         if (strstr($spellbuff['maxbadguydamage'],"*") != ""){
            $num=str_replace("*","",$num);
            $oper="*";
         }
         if (strstr($spellbuff['maxbadguydamage'],"/") != ""){
            $num=str_replace("/","",$num);
            $oper="/";
         }
         if (strstr($spellbuff['maxbadguydamage'],"+") != ""){
            $num=str_replace("+","",$num);
            $oper="+";
         }
         if (strstr($spellbuff['maxbadguydamage'],"-") != ""){
            $num=str_replace("-","",$num);
            $oper="-";
         }
         $num=trim($num);
         $num=intval($num);
      switch ($oper){
         case "*":
            $spellbuff['maxbadguydamage']=$session['user']['level']*$num;
         break;
         case "/":
            $spellbuff['maxbadguydamage']=$session['user']['level']/$num;
         break;
         case "+":
            $spellbuff['maxbadguydamage']=$session['user']['level']+$num;
         break;
         case "-":
            $spellbuff['maxbadguydamage']=$session['user']['level']-$num;
         break;
         case "":
            $spellbuff['minbadguydamage']=$session['user']['level'];
         break;
      }
      }
      if (strstr($spellbuff['minbadguydamage'],"{level}") != ""){
         $oper="";
         $num=str_replace("{level}","",$spellbuff['minbadguydamage']);
         if (strstr($spellbuff['minbadguydamage'],"*") != ""){
            $num=str_replace("*","",$num);
            $oper="*";
         }
         if (strstr($spellbuff['minbadguydamage'],"/") != ""){
            $num=str_replace("/","",$num);
            $oper="/";
         }
         if (strstr($spellbuff['minbadguydamage'],"+") != ""){
            $num=str_replace("+","",$num);
            $oper="+";
         }
         if (strstr($spellbuff['minbadguydamage'],"-") != ""){
            $num=str_replace("-","",$num);
            $oper="-";
         }
         $num=trim($num);
         $num=intval($num);
      switch ($oper){
         case "*":
            $spellbuff['minbadguydamage']=$session['user']['level']*$num;
         break;
         case "/":
            $spellbuff['minbadguydamage']=$session['user']['level']/$num;
         break;
         case "+":
            $spellbuff['minbadguydamage']=$session['user']['level']+$num;
         break;
         case "-":
            $spellbuff['minbadguydamage']=$session['user']['level']-$num;
         break;
         case "":
            $spellbuff['minbadguydamage']=$session['user']['level'];
         break;
      }
      }
      if (strstr($spellbuff['minioncount'],"{level}") != ""){
         $oper="";
         $num=str_replace("{level}","",$spellbuff['minioncount']);
         if (strstr($spellbuff['minioncount'],"*") != ""){
            $num=str_replace("*","",$num);
            $oper="*";
         }
         if (strstr($spellbuff['minioncount'],"/") != ""){
            $num=str_replace("/","",$num);
            $oper="/";
         }
         if (strstr($spellbuff['minioncount'],"+") != ""){
            $num=str_replace("+","",$num);
            $oper="+";
         }
         if (strstr($spellbuff['minioncount'],"-") != ""){
            $num=str_replace("-","",$num);
            $oper="-";
         }
         $num=trim($num);
         $num=intval($num);
      switch ($oper){
         case "*":
            $spellbuff['minioncount']=$session['user']['level']*$num;
         break;
         case "/":
            $spellbuff['minioncount']=$session['user']['level']/$num;
         break;
         case "+":
            $spellbuff['minioncount']=$session['user']['level']+$num;
         break;
         case "-":
            $spellbuff['minioncount']=$session['user']['level']-$num;
         break;
         case "":
            $spellbuff['minioncount']=$session['user']['level'];
         break;
      }
      }
      //Excalibur: fine modifica

        $session['bufflist'][$spellbuff['name']]=$spellbuff;
        $incantesimo['quanti']--;
        if ($incantesimo['quanti']<=0){
            db_query("DELETE FROM incantesimi WHERE id=".$_GET['incid']);
        }else{
            db_query("UPDATE incantesimi SET quanti=".$incantesimo['quanti']." WHERE id='".$_GET['incid']."'");
        }
    }
// fine incantesimi

    if ($_GET['skill']=="godmode"){
        $session['bufflist']['godmode']=array(
            "name"=>"`&MODALITÀ DIVINA",
            "rounds"=>1,
            "wearoff"=>"Ti senti di nuovo mortale.",
            "atkmod"=>25,
            "defmod"=>25,
            "invulnerable"=>1,
            "startmsg"=>"`n`&Ti senti un dio`n`n",
            "activate"=>"roundstart"
        );
    }
    if ($_GET['skill']=="MP"){
        if ($session['user']['magicuses'] >= $_GET['l']){
            $creaturedmg = 0;
            switch($_GET['l']){
            case 1:
                $session['bufflist']['mp1'] = array(
                    "startmsg"=>"`n`^Inizi a rigenerarti!`n`n",
                    "name"=>"`%Rigenerazione",
                    "rounds"=>5,
                    "wearoff"=>"Hai smesso di rigenerarti",
                    "regen"=>$session['user']['level'],
                    "effectmsg"=>"Ti rigeneri per {damage} HP.",
                    "effectnodmgmsg"=>"Non hai ferite da rigenerare.",
                    "activate"=>"roundstart");
                break;
            case 2:
                $session['bufflist']['mp2'] = array(
                    "startmsg"=>"`n`^{badguy}`% viene afferrato da un pugno di terra e sbattuto al suolo!`n`n",
                    "name"=>"`%Pugno di Terra",
                    "rounds"=>5,
                    "wearoff"=>"Il pugno di terra si sgretola.",
                    "minioncount"=>1,
                    "effectmsg"=>"Un grosso pugno di terra colpisce {badguy} causandogli `^{damage}`) punti di danno.",
                    "minbadguydamage"=>1,
                    "maxbadguydamage"=>$session['user']['level']*3,
                    "activate"=>"roundstart"
                    );
                break;
            case 3:
                $session['bufflist']['mp3'] = array(
                    "startmsg"=>"`n`^La tua arma emette un bagliore ultraterreno.`n`n",
                    "name"=>"`%Drena Vita",
                    "rounds"=>5,
                    "wearoff"=>"L´aura della tua arma svanisce.",
                    "lifetap"=>1, //ratio of damage healed to damage dealt
                    "effectmsg"=>"Vieni guarito di {damage} punti HP.",
                    "effectnodmgmsg"=>"Senti un formicolio mentre la tua arma ripristina la salute del tuo corpo.",
                    "effectfailmsg"=>"La tua arma si lamenta perchè non fai danni al tuo avversario.",
                    "activate"=>"offense,defense",
                    );
                break;
            case 5:
                $session['bufflist']['mp5'] = array(
                    "startmsg"=>"`n`^La tua pelle scintilla e vieni avvolto da un´aura di fulmini`n`n",
                    "name"=>"`%Aura di Fulmini",
                    "rounds"=>5,
                    "wearoff"=>"Crepitando, la tua pelle torna normale.",
                    "damageshield"=>2,
                    "effectmsg"=>"{badguy} si ritrae per gli archi voltaici che emette la tua pelle, che lo colpiscono causando `^{damage}`) di danno.",
                    "effectnodmg"=>"{badguy} è leggermente bruciacchiato dal tuo fulmine, ma niente di più.",
                    "effectfailmsg"=>"{badguy} è leggermente bruciacchiato dal tuo fulmine, ma niente di più.",
                    "activate"=>"offense,defense"
                );
                break;
            }
            $session['user']['magicuses']-=$_GET['l'];
        }else{
            $session['bufflist']['mp0'] = array(
                "startmsg"=>"`nAggrotti la fronte ed evochi il potere degli elementi. Una fiammella compare.  {badguy} ci si accende una sigaretta, ringraziandoti prima di attaccarti nuovamente.`n`n",
                "rounds"=>1,
                "activate"=>"roundstart"
            );
        }
    }
    if ($_GET['skill']=="DA"){
        if ($session['user']['darkartuses'] >= $_GET['l']){
            $creaturedmg = 0;
            switch($_GET['l']){
            case 1:
                $session['bufflist']['da1']=array(
                    "startmsg"=>"`n`\$Evochi gli spiriti dei morti, e delle mani scheletriche artigliano {badguy} da dentro la tomba.`n`n",
                    "name"=>"`\$Ciurma di Scheletri",
                    "rounds"=>5,
                    "wearoff"=>"I tuoi servi scheletrici si riducono in polvere.",
                    "minioncount"=>round($session['user']['level']/3)+1,
                    "maxbadguydamage"=>round($session['user']['level']/2,0)+1,
                    "effectmsg"=>"`)Un seguace non-morto colpisce {badguy} causando `^{damage}`) punti danno.",
                    "effectnodmgmsg"=>"`)Un seguace non-morto cerca di colpire {badguy} ma `\$LO MANCA`)!",
                    "activate"=>"roundstart"
                    );
                break;
            case 2:
                $session['bufflist']['da2']=array(
                    "startmsg"=>"`n`\$Estrai una bambolina voodoo che sembra {badguy}`n`n",
                    "effectmsg"=>"Infili uno spillone nella bambola di {badguy} causandogli `^{damage}`) punti danno!",
                    "minioncount"=>1,
                    "maxbadguydamage"=>round($session['user']['attack']*3,0),
                    "minbadguydamage"=>round($session['user']['attack']*1.5,0),
                    "activate"=>"roundstart"
                    );
                break;
            case 3:
                $session['bufflist']['da3']=array(
                    "startmsg"=>"`n`\$Piazzi una maledizione sugli avi di {badguy}.`n`n",
                    "name"=>"`\$Maledizione",
                    "rounds"=>5,
                    "wearoff"=>"La tua maledizione si esaurisce.",
                    "badguydmgmod"=>0.5,
                    "roundmsg"=>"{badguy} vacilla sotto il peso della tua maledizione e ti causa solo la metà di danno.",
                    "activate"=>"defense"
                    );
                break;
            case 5:
                $session['bufflist']['da5']=array(
                    "startmsg"=>"`n`\$Punti il dito e le orecchie di {badguy} iniziano a sanguinare.`n`n",
                    "name"=>"`\$Avvizzisci Anima",
                    "rounds"=>5,
                    "wearoff"=>"L´anima della tua vittima è stata curata.",
                    "badguyatkmod"=>0,
                    "badguydefmod"=>0,
                    "roundmsg"=>"{badguy} si artiglia gli occhi, tentando di liberare la sua anima, e non può attaccare né difendersi.",
                    "activate"=>"offense,defense"
                    );
                break;
            }
            $session['user']['darkartuses']-=$_GET['l'];
        }else{
            $session['bufflist']['da0'] = array(
                "startmsg"=>"`nEsausto, tenti la tua magia più oscura, una pessima battuta.  {badguy} ti guarda pensieroso per un minuto, poi finalmente capisce la battuta. Ridendo, riprende a picchiarti.`n`n",
                "rounds"=>1,
                "activate"=>"roundstart"
                );
        }
    }
    if ($_GET['skill']=="CM"){
        if ($session['user']['militareuses'] >= $_GET['l']){
            $creaturedmg = 0;
            switch($_GET['l']){
            case 1:
                $session['bufflist']['cm1']=array(
                    "startmsg"=>"`n`\$Inizi a menare come un matto il povero {badguy} che non ha speranze di salvarsi.`n`n",
                    "name"=>"`3Colpi Multipli",
                    "rounds"=>5,
                    "wearoff"=>"Affannato torni a colpire normalmente.",
                    "minioncount"=>round($session['user']['level']/3)+1,
                    "maxbadguydamage"=>round($session['user']['level']/2,0)+1,
                    "effectmsg"=>"`)Ti muovi rapidamente e colpisci di nuovo {badguy} causandogli `^{damage}`) danni.",
                    "effectnodmgmsg"=>"`)Purtroppo il colpo speciale che hai tentato contro {badguy} `\$LO MANCA`)!",
                    "activate"=>"roundstart"
                    );
                break;
            case 2:
                $session['bufflist']['cm2']=array(
                    "startmsg"=>"`n`\$Miri i tuoi colpi in zone critiche di {badguy}`n`n",
                    "effectmsg"=>"Colpisci in una zona critica {badguy} causandogli `^{damage}`) danni!",
                    "minioncount"=>1,
                    "maxbadguydamage"=>round($session['user']['attack']*3,0),
                    "minbadguydamage"=>round($session['user']['attack']*1.5,0),
                    "activate"=>"roundstart"
                    );
                break;
            case 3:
                $session['bufflist']['cm3']=array(
                    "startmsg"=>"`n`\$Adotti una posizione per meglio parare i colpi di {badguy}.`n`n",
                    "name"=>"`3Parata",
                    "rounds"=>5,
                    "wearoff"=>"Hai smesso di parare.",
                    "badguydmgmod"=>0.5,
                    "roundmsg"=>"Stai parando i colpi di {badguy}, dimezzi il danno che ti infligge.",
                    "activate"=>"defense"
                    );
                break;
            case 5:
                    $session['bufflist']['cm5'] = array(
                    "startmsg"=>"`n`\$La furia si impossessa di te, i tuoi muscoli si gonfiano, `^{badguy}`\$ ti guarda terrorizzato!`n`n",
                    "name"=>"`3Berserk",
                    "rounds"=>7,
                    "wearoff"=>"Gli occhi ti si iniettano di sangue, inizi ad ansimare e la forza ti abbandona.",
                    "minioncount"=>1,
                    "effectmsg"=>"Massacri {badguy} e gli infliggi `^{damage}`) danni.",
                    "minbadguydamage"=>10,
                    "maxbadguydamage"=>$session['user']['level']*3,
                    "activate"=>"roundstart"
                    );
                break;
            }
            $session['user']['militareuses']-=$_GET['l'];
        }else{
            $session['bufflist']['da0'] = array(
                "startmsg"=>"`nEsausto, tenti la tua magia più oscura, una pessima battuta.  {badguy} ti guarda pensieroso per un minuto, poi finalmente capisce la battuta. Ridendo, riprende a picchiarti.`n`n",
                "rounds"=>1,
                "activate"=>"roundstart"
                );
        }
    }
    if ($_GET['skill']=="TS"){
        if ($session['user']['thieveryuses'] >= $_GET['l']){
            $creaturedmg = 0;
            switch($_GET['l']){
            case 1:
                $session['bufflist']['ts1']=array(
                    "startmsg"=>"`n`^Chiami {badguy} con un nomigliolo offensivo, facendolo piangere.`n`n",
                    "name"=>"`^Insulto",
                    "rounds"=>5,
                    "wearoff"=>"La tua vittima smette di piangere e si soffia il naso.",
                    "roundmsg"=>"{badguy} si sente demoralizzato e non ti può attaccare.",
                    "badguyatkmod"=>0.5,
                    "activate"=>"defense"
                    );
                break;
            case 2:
                $session['bufflist']['ts2']=array(
                    "startmsg"=>"`n`^Metti del veleno sul tuo ".$session['user']['weapon'].".`n`n",
                    "name"=>"`^Attacco al Veleno",
                    "rounds"=>5,
                    "wearoff"=>"Il sangue della tua vittima ha lavato via il veleno dall´arma.",
                    "atkmod"=>2,
                    "roundmsg"=>"Il tuo attacco è potenziato!",
                    "activate"=>"offense"
                    );
                break;
            case 3:
                $session['bufflist']['ts3'] = array(
                    "startmsg"=>"`n`^Con la tua esperienza di ladro, scompari ed attacchi {badguy} da una posizione di vantaggio.`n`n",
                    "name"=>"`^Attacco Nascosto",
                    "rounds"=>5,
                    "wearoff"=>"La tua vittima ti ha localizzato.",
                    "roundmsg"=>"{badguy} non riesce a trovarti.",
                    "badguyatkmod"=>0,
                    "activate"=>"defense"
                    );
                break;
            case 5:
                $session['bufflist']['ts5']=array(
                    "startmsg"=>"`n`^Usando i tuoi talenti di ladro, scompari dietro {badguy} e infili una lama sottile tra le sue vertebre!`n`n",
                    "name"=>"`^Pugnalata alle Spalle",
                    "rounds"=>5,
                    "wearoff"=>"È improbabile che la tua vittima ti faccia passare di nuovo dietro di lei!",
                    "atkmod"=>3,
                    "defmod"=>3,
                    "roundmsg"=>"Il tuo attacco è potenziato, come anche la tua difesa!",
                    "activate"=>"offense,defense"
                    );
                break;
            }
            $session['user']['thieveryuses']-=$_GET['l'];
        }else{
            $session['bufflist']['ts0'] = array(
                "startmsg"=>"`nTenti di attaccare {badguy} mettendo in pratica i tuoi migliori talenti di ladro, ma inciampi nel tuo piede.`n`n",
                "rounds"=>1,
                "activate"=>"roundstart"
                );
        }
    }
    //special abilities mod by sixf00t4 start
    if ($_GET['skill']=="MY"){
        if ($session['user']['mysticuses'] >= $_GET['l']){
            $creaturedmg = 0;
            switch($_GET['l']){
            case 1:
                $session['bufflist']['my1'] = array(
                    "startmsg"=>"`n`^Le sirene urlano!`n`n",
                    "name"=>"`%Sirene",
                    "rounds"=>4,
                    "wearoff"=>"Le sirene si rituffano in mare.",
                    "minioncount"=>round($session['user']['level']/3)+1,
                    "maxbadguydamage"=>round($session['user']['level']/2,0)+1,
                    "effectmsg"=>"`)L'urlo di una sirena stordisce {badguy} causandogli `^{damage}`) punti danno.",
                    "effectnodmgmsg"=>"`)L'urlo di una sirena non riesce a stordire {badguy}!",
                    "activate"=>"roundstart");
                break;
            case 2:
                $session['bufflist']['my2'] = array(
                    "startmsg"=>"`n`^{badguy}`% è distratto dal tuo danzargli intorno!`n`n",
                    "name"=>"`%Danza",
                    "rounds"=>5,
                    "wearoff"=>"Termini le energie e la smetti di danzare.",
                    "roundmsg"=>"`^{badguy} `)è preso dal capogiro e i tuoi attacchi sono potenziati !",
                    "minbadguydamage"=>1,
                    "maxbadguydamage"=>$session['user']['level']*3,
                    "activate"=>"roundstart"
                    );
                break;
            case 3:
                $session['bufflist']['my3'] = array(
                    "startmsg"=>"`n`^{badguy} è ammaliato dal tuo fascino`n`n",
                    "name"=>"`%Fascino",
                    "roundmsg"=>"`^{badguy} non riesce a distogliere lo sguardo e non colpisce.",
                    "rounds"=>4,
                    "wearoff"=>"{badguy} esce dalla magia del tuo fascino.",
                    "badguyatkmod"=>0,
                    "badguydefmod"=>0.5,
                    "activate"=>"offense,defense");
                break;
            case 5:
                $session['bufflist']['my5'] = array(
                    "startmsg"=>"`n`^Il nemico cade addormentato.`n`n",
                    "roundmsg"=>"{badguy} dorme come un neonato, e tu ne approfitti con un colpo potenziato",
                    "name"=>"`%Sonno",
                    "rounds"=>5,
                    "wearoff"=>"si risveglia.",
                    "badguyatkmod"=>0,
                    "badguydefmod"=>0,
                    "minbadguydamage"=>1,
                    "maxbadguydamage"=>$session['user']['level']*3,
                    "activate"=>"roundstart");
                break;
            }
            $session['user']['mysticuses']-=$_GET['l'];
        }else{
            $session['bufflist']['my0'] = array(
                "startmsg"=>"`nNon sei più attraente.`n`n",
                "rounds"=>1,
                "activate"=>"roundstart"
                );

        }
    }
    if ($_GET['skill']=="TA"){
        if ($session['user']['tacticuses'] >= $_GET['l']){
            $creaturedmg = 0;
            switch($_GET['l']){
            case 1:
                $session['bufflist']['ta1'] = array(
                    "startmsg"=>"`n`^Richiami all'ordine le tue reclute!`n`n",
                    "name"=>"`%Reclute",
                    "rounds"=>5,
                    "wearoff"=>"Le tue reclute ti abbandonano.",
                    "minioncount"=>round($session['user']['level']/3)+1,
                    "maxbadguydamage"=>round($session['user']['level']/2,0)+1,
                    "effectmsg"=>"`)Una recluta colpisce {badguy} per `^{damage}`) punti danno.",
                    "effectnodmgmsg"=>"`)Una recluta cerca di colpire {badguy} ma  `\$LO MANCA`)!",
                    "activate"=>"roundstart");
                break;
            case 2:
                $session['bufflist']['ta2'] = array(
                    "startmsg"=>"`n`^Distrai {badguy} con un colpetto sulla spalla e lo attacchi dall'altro lato`n",
                    "name"=>"`%Sorpresa",
                    "rounds"=>8,
                    "wearoff"=>"Sei senza fiato, e non sorprendi più {badguy}.",
                    "minioncount"=>1,
                    "roundmsg"=>"Attacchi a sorpresa!",
                    "minbadguydamage"=>1,
                    "maxbadguydamage"=>$session['user']['level']*2,
                    "activate"=>"roundstart");
                break;
            case 3:
                $session['bufflist']['ta3'] = array(
                    "startmsg"=>"`n`^la notte cala sulla foresta.`n`n",
                    "name"=>"`%Attacco Notturno",
                    "roundmsg"=>"`#{badguy} incapace di vedere non riesce a colpirti.",
                    "rounds"=>5,
                    "atkmod"=>2,
                    "badguydefmod"=>0,
                    "wearoff"=>"La foresta si illumina nuovamente.",
                    "activate"=>"offense,defense");
                break;
            case 5:
                $session['bufflist']['ta5'] = array(
                    "startmsg"=>"`n`^con le tue ginocchia piegate e la mano estesa, domini i tuoi avversari.`n`n",
                    "name"=>"`%Arti Marziali",
                    "rounds"=>4,
                    "damageshield"=>2,
                    "atkmod"=>3,
                    "effectmsg"=>"{badguy} indietreggia al tuo colpo di karate, colpito per `^{damage}`) punti danno.",
                    "effectnodmg"=>"{badguy} è moderatamente impressionato dalle tue arti marziali, ma rimane illeso.",
                    "effectfailmsg"=>"{badguy} è moderatamente impressionato dai tuoi movimenti, ma rimane illeso.",
                    "activate"=>"offense,defence");
                break;
            }
            $session['user']['tacticuses']-=$_GET['l'];
        }else{
            $session['bufflist']['ta0'] = array(
                "startmsg"=>"`nRimani basito.`n`n",
                "rounds"=>1,
                "activate"=>"roundstart");
        }
    }
    if ($_GET['skill']=="RS"){
        if ($session['user']['rockskinuses'] >= $_GET['l']){
            $creaturedmg = 0;
            switch($_GET['l']){
            case 1:
                $session['bufflist']['rs1'] = array(
                    "startmsg"=>"`n`^Le rocce cadono sul tuo nemico!`n`n",
                    "name"=>"`%Rocce Cadenti",
                    "rounds"=>5,
                    "wearoff"=>"le rocce non cadono più.",
                    "minioncount"=>round($session['user']['level']/3)+1,
                    "maxbadguydamage"=>round($session['user']['level']/2,0)+5,
                    "effectmsg"=>"`)Una roccia colpisce {badguy} per `^{damage}`) punti danno.",
                    "effectnodmgmsg"=>"`)Una roccia sfiora {badguy} ma `\$LO MANCA`)!",
                    "activate"=>"roundstart");
                break;
            case 2:
                $session['bufflist']['rs2'] = array(
                    "startmsg"=>"`n`^Chiudi gli occhi e la tua pelle si indurisce.`n`n",
                    "name"=>"`%Pelle Dura",
                    "rounds"=>5,
                    "defmod"=>3,
                    "roundmsg"=>"{badguy} ti colpisce ma quasi non senti il colpo!",
                    "wearoff"=>"Con un sibilo, la tua pelle torna normale.",
                    "activate"=>"roundstart");
                break;
            case 3:
                $session['bufflist']['rs3'] = array(
                    "startmsg"=>"`n`^Un pugno di roccia esce dalle montagne vicine.`n`n",
                    "name"=>"`%Pugno di Roccia",
                    "rounds"=>4,
                    "wearoff"=>"Il pugno di roccia si frantuma in polvere.",
                    "minioncount"=>1,
                    "effectmsg"=>"Un grosso pugno di roccia colpisce {badguy} per `^{damage}`) punti danno.",
                    "effectnodmgmsg"=>"Un grosso pugno di roccia manca {badguy} che ti schernisce.",
                    "minbadguydamage"=>1,
                    "maxbadguydamage"=>$session['user']['level']*4,
                    "activate"=>"roundstart");
                break;
            case 5:
                $session['bufflist']['rs5'] = array(
                    "startmsg"=>"`n`^Urli e l'eco rimbalza tra le montagne.  {badguy} si copre le orecchie impaurito.`n`n",
                    "name"=>"`%Eco Montano",
                    "roundmsg"=>"L'eco delle tue minacce rende {badguy} inerme.",
                    "rounds"=>6,
                    "wearoff"=>"l'eco si esaurisce.",
                    "badguyatkmod"=>0,
                    "badguydefmod"=>0,
                    "activate"=>"offense,defense");
                break;
            }
            $session['user']['rockskinuses']-=$_GET['l'];
        }else{
            $session['bufflist']['rs0'] = array(
                "startmsg"=>"`nNon sei vicino a nessuna montagna.`n`n",
                "rounds"=>1,
                "activate"=>"roundstart");
        }
    }
    if ($_GET['skill']=="MS"){
        if ($session['user']['muscleuses'] >= $_GET['l']){
            $creaturedmg = 0;
            switch($_GET['l']){
            case 1:
                $session['bufflist']['ms1'] = array(
                    "startmsg"=>"`n`^Le tue mani si ingrossano e colpiscono {badguy}!`n`n",
                    "name"=>"`%Gragnuola di Pugni",
                    "rounds"=>4,
                    "roundmsg"=>"I colpi che sferri a {badguy} lo stanno massacrando.",
                    "wearoff"=>"Le tue mani ritornano alle loro dimensioni normali",
                    "atkmod"=>round($session['user']['level']/10)+1,
                    "activate"=>"offense");
                break;
            case 2:
                $session['bufflist']['ms2'] = array(
                    "startmsg"=>"`n`^Inizi a rigenerare!`n`n",
                    "name"=>"`%Flessibilità",
                    "rounds"=>6,
                    "wearoff"=>"Hai smesso di rigenerarti",
                    "regen"=>$session['user']['level'],
                    "effectmsg"=>"Rigeneri per {damage} HP.",
                    "effectnodmgmsg"=>"Non hai ferite da rigenerare.",
                    "activate"=>"roundstart");
                break;
            case 3:
                $session['bufflist']['ms3'] = array(
                    "startmsg"=>"`n`^I tuoi capezzoli escono per battersi!`n`n",
                    "name"=>"`%Capezzoli infuriati",
                    "minioncount"=>2,
                    "rounds"=>5,
                    "effectmsg"=>"`^{badguy} `)è titillato per`\$ {damage} `)punti danno!",
                    "effectnodmgmsg"=>"`^{badguy} `)non si lascia eccitare dai tuoi capezzoli",
                    "minbadguydamage"=>1,
                    "maxbadguydamage"=>$session['user']['level']*3,
                    "activate"=>"offense");
                break;
            case 5:
                $session['bufflist']['ms5'] = array(
                    "startmsg"=>"`n`^La tua pelle scintilla e assumi un'aura bronzea`n`n",
                    "name"=>"`%Lozione Abbronzante",
                    "rounds"=>6,
                    "wearoff"=>"Con un sibilo, la tua pelle torna normale.",
                    "damageshield"=>1.5,
                    "effectmsg"=>"{badguy} si ritrae a causa dei bagliori della tua pelle nei suoi occhi, lo colpisci per `^{damage}`) punti danno.",
                    "effectnodmg"=>"{badguy} è leggermente impressionato dal tuo colore naturale, ma resta illeso.",
                    "effectfailmsg"=>"{badguy} è illuminato dal tuo colore, ma resta illeso.",
                    "maxbadguydamage"=>$session['user']['level']*1.5,
                    "activate"=>"offense,defense");
                break;
            }
            $session['user']['muscleuses']-=$_GET['l'];
        }else{
            $session['bufflist']['ms0'] = array(
                "startmsg"=>"`nTi senti debole.`n`n",
                "rounds"=>1,
                "activate"=>"roundstart");
        }
    }
    if ($_GET['skill']=="RH"){
        if ($session['user']['rhetoricuses'] >= $_GET['l']){
            $creaturedmg = 0;
            switch($_GET['l']){
            case 1:
                $session['bufflist']['rh1'] = array(
                    "startmsg"=>"`n`^Inizi a lanciare dei dizionari!`n`n",
                    "name"=>"`%Dizionari",
                    "rounds"=>4,
                    "roundmsg"=>"I tuoi dizionari giungono a segno, colpendo {badguy}",
                    "maxbadguydamage"=>round($session['user']['level']/3),
                    "minioncount"=>3,
                    "atkmod"=>1.2,
                    "wearoff"=>"Smetti di lanciare dizionari",
                    "activate"=>"roundstart");
                break;
            case 2:
                $session['bufflist']['rh2'] = array(
                    "startmsg"=>"`n`^{badguy}`% è confuso dai paroloni difficili che usi!`n`n",
                    "name"=>"`%Paroloni",
                    "rounds"=>5,
                    "badguyatkmod"=>0,
                    "roundmsg"=>"{badguy} sta iniziando a non capire più nulla",
                    "wearoff"=>"si infila dei tappi nelle orecchie e smette di ascoltarti.",
                    "activate"=>"defense"
                    );
                break;
            case 3:
                $session['bufflist']['rh3'] = array(
                    "startmsg"=>"`n`^Inizi a dire degli scioglilingua.`n`n",
                    "name"=>"`%Scioglilingua",
                    "rounds"=>4,
                    "roundmsg"=>"Supercalifragilistichespiralidoso ...",
                    "atkmod"=>round($session['user']['level']/4)+1,
                    "wearoff"=>"La tua lingua si è annodata.",
                    "activate"=>"offense,defense"
                    );
                break;
            case 5:
                $session['bufflist']['rh5'] = array(
                    "startmsg"=>"`n`^Inizi un lungo discorso, che ti consente di rigenerare.`n`n",
                    "name"=>"`%Discorsi",
                    "rounds"=>10,
                    "wearoff"=>"Hai smesso di guarirti",
                    "regen"=>$session['user']['level']*2,
                    "effectmsg"=>"Ti curi per {damage} HP.",
                    "effectnodmgmsg"=>"Non hai ferite da curare.",
                    "activate"=>"roundstart");
                    break;
            }
            $session['user']['rhetoricuses']-=$_GET['l'];
        }else{
            $session['bufflist']['rh0'] = array(
                "startmsg"=>"`nNon riesci a pensare a nessuna parola.`n`n",
                "rounds"=>1,
                "activate"=>"roundstart"
            );
        }
    }
    if ($_GET['skill']=="NA"){
        if ($session['user']['natureuses'] >= $_GET['l']){
            $creaturedmg = 0;
            switch($_GET['l']){
            case 1:
                $session['bufflist']['na1'] = array(
                    "startmsg"=>"`n`^Lanci un grido d'aiuto in direzione della foresta!`n`n",
                    "name"=>"`%Aiuto Animale",
                    "rounds"=>4,
                    "wearoff"=>"Gli animali tornano nella foresta.",
                    "minioncount"=>round($session['user']['level']/4)+1,
                    "effectmsg"=>"Gli animali attaccano {badguy} causandogli `^{damage}`) punti danno.",
                    "effectnodmgmsg"=>"Gli animali cercano di azzannare {badguy} ma `\$LO MANCANO!!",
                    "minbadguydamage"=>round($session['user']['level']/3),
                    "maxbadguydamage"=>$session['user']['level'],
                    "activate"=>"roundstart"
                    );
                break;
            case 2:
                $session['bufflist']['na2'] = array(
                    "startmsg"=>"`n`^Colpisci il terreno e gli scarafaggi coprono {badguy} aumentando la potenza dei tuoi attacchi.`n`n",
                    "name"=>"`%Infestazione di Scarafaggi",
                    "rounds"=>5,
                    "wearoff"=>"`8Gli scarafaggi tornano nelle viscere della terra",
                    "atkmod"=>2,
                    "activate"=>"roundstart"
                    );
                break;
            case 3:
                $session['bufflist']['na3'] = array(
                    "startmsg"=>"`n`^Unisci le mani e invochi le aquile!`n`n",
                    "name"=>"`%Artigli delle Aquile",
                    "effectmsg"=>"Gli artigli strappano brandelli di carne da {badguy} causandogli `^{damage}`) punti danno.",
                    "effectnodmgmsg"=>"Le aquile cercano di artigliare {badguy} ma `\$LO MANCANO!!",
                    "rounds"=>5,
                    "minioncount"=>1,
                    "wearoff"=>"`8Le aquile tornano al loro nido.",
                    "maxbadguydamage"=>$session['user']['level']*2,
                    "atkmod"=>round($session['user']['level']/10)+1,
                    "activate"=>"offense"
                    );
                break;
            case 5:
                $session['bufflist']['na5'] = array(
                    "startmsg"=>"`n`^emetti il richiamo per Bigfoot`n`n",
                    "name"=>"`%Bigfoot",
                    "rounds"=>6,
                    "effectmsg"=>"Bigfoot combatte con te e colpisce {badguy} causandogli `^{damage}`) punti danno.",
                    "effectnodmgmsg"=>"Bigfoot tenta di colpire {badguy} ma `\$LO MANCA!!",
                    "minioncount"=>1,
                    "wearoff"=>"Bigfoot torna a nascondersi.",
                    "minbadguydamage"=>$session['user']['level']*2,
                    "maxbadguydamage"=>$session['user']['level']*5,
                    "activate"=>"offense,defense"
                );
                break;
            }
            $session['user']['natureuses']-=$_GET['l'];
        }else{
            $session['bufflist']['na0'] = array(
                "startmsg"=>"`nNon c'è nessun animale nei dintorni'.`n`n",
                "rounds"=>1,
                "activate"=>"roundstart"
            );
        }
    }
    if ($_GET['skill']=="WE"){
        if ($session['user']['weatheruses'] >= $_GET['l']){
            $creaturedmg = 0;
            switch($_GET['l']){
            case 1:
                $session['bufflist']['we1'] = array(
                    "name"=>"`%Folate di Vento",
                    "startmsg"=>"`n`^Il vento amico ti aiuta a rigenerare le tue ferite!`n`n",
                    "rounds"=>10,
                    "wearoff"=>"il vento si calma.",
                    "regen"=>round($session['user']['level']/2),
                    "effectmsg"=>"Ti rigeneri per {damage} HP.",
                    "effectnodmgmsg"=>"Non hai ferite da rigenerare.",
                    "activate"=>"roundstart");
                break;
            case 2:
                $session['bufflist']['we2'] = array(
                    "startmsg"=>"`n`^{badguy}`% viene sollevato da un tornado!`n`n",
                    "name"=>"`%Tornado",
                    "rounds"=>5,
                    "maxbadguydamage"=>$session['user']['level']*1.1,
                    "atkmod"=>round($session['user']['level']/7.5),
                    "wearoff"=>"{badguy} ripiomba al suolo.",
                    "minioncount"=>1,
                    "effectmsg"=>"I detriti sollevati dal tornado colpiscono {badguy} per `^{damage}`) punti danno.",
                    "effectnodmgmsg"=>"I detriti sollevati dal tornado sfiorano {badguy} senza colpirlo.",
                    "activate"=>"roundstart"
                    );
                break;
            case 3:
                $session['bufflist']['we3'] = array(
                    "startmsg"=>"`n`^Inizia a piovere a dirotto.`n`n",
                    "name"=>"`%Pioggia",
                    "rounds"=>6,
                    "wearoff"=>"ha smesso di piovere.",
                    "roundmsg"=>"a {badguy} non piace bagnarsi e il suo attacco è ridotto.",
                    "badguydefmod"=>0,
                    "badguyatkmod"=>0.5,
                    "activate"=>"defense"
                    );
                break;
            case 5:
                $session['bufflist']['we5'] = array(
                    "startmsg"=>"`n`^Inizia a fare freddo.`n`n",
                    "name"=>"`%Gelo Polare",
                    "minioncount"=>round($session['user']['level']/3)+1,
                    "rounds"=>6,
                    "effectmsg"=>"Cristalli di ghiaccio colpiscono {badguy}, incapace di muoversi, per `^{damage}`) punti danno.",
                    "effectnomdgmsg"=>"Cristalli di ghiaccio passano vicini a {badguy}, ma non lo colpiscono.",
                    "wearoff"=>"{badguy} si sta scongelando.",
                    "badguydefmod"=>0,
                    "maxbadguydamage"=>$session['user']['level']*2,
                    "activate"=>"offense,defense"
                );
                break;
            }
            $session['user']['weatheruses']-=$_GET['l'];
        }else{
            $session['bufflist']['we0'] = array(
                "startmsg"=>"`nIl clima è imprevedibile.`n`n",
                "rounds"=>1,
                "activate"=>"roundstart"
            );
        }
    }
    //special abilities mod by sixf00t4 end
    //Excalibur: nuove Specialità
    if ($_GET['skill']=="EL"){
        if ($session['user']['elementaleuses'] >= $_GET['l']){
            $creaturedmg = 0;
            switch($_GET['l']){
            case 1:
                $session['bufflist']['el1'] = array(
                        "startmsg"=>"`^Evochi un Elementale d'Aria per creare una potente raffica di vento.`n`n",
                        "name"=>"`^Vento",
                        "rounds"=>8,
                        "wearoff"=>"La tua vittima non è più bloccata dal vento.",
                        "roundmsg"=>"{badguy} viene respinto dal vento e non riesce nemmeno a combattere.",
                        "badguyatkmod"=>0.6,
                        "activate"=>"roundstart");
                break;
            case 2:
                $session['bufflist']['el2'] = array(
                        "startmsg"=>"`^Evochi un Elementale d'Acqua per creare una profonda pozza d'acqua sotto i piedi di {badguy}.`n`n",
                        "name"=>"`^Pozza d'Acqua",
                        "rounds"=>8,
                        "wearoff"=>"La pozza d'acqua si prosciuga.",
                        "atkmod"=>2,
                        "roundmsg"=>"{badguy} non riesce ad uscire dalla pozza d'acqua, consentendoti un attacco migliore!",
                     "activate"=>"roundstart"
                    );
                break;
            case 3:
                $session['bufflist']['el3'] = array(
                        "startmsg"=>"`^Alcuni piccoli golem di pietra emergono dal terreno ed iniziano a lanciare ciottoli contro {badguy}.`n`n",
                        "name"=>"`^Golem di Pietra",
                        "rounds"=>8,
                        "wearoff"=>"I golem si sgretolano e tornano ad essere polvere.",
                        "minioncount"=>round($session['user']['level']/2.5)+1,
                        "maxbadguydamage"=>round($session['user']['level']/1.8,0)+1,
                        "effectmsg"=>"`)Un golem lancia una pietra e colpisce {badguy}`) causandogli `^{damage}`) punti di danno.",
                        "effectnodmgmsg"=>"`)Un golem lancia una pietra e e cerca di colpire {badguy}`) ma `\$LO MANCA`)!",
                    "activate"=>"offense,defense",
                    );
                break;
            case 5:
                $session['bufflist']['el5'] = array(
                        "startmsg"=>"`^Concentrandoti profondamente, evochi una piccola fiammella sul palmo della tua mano.`nSalta giù dalla tua mano e si ingrossa velocemente.`nLa fiamma balza addosso a {badguy}`^ e lo avviluppa!`n`n",
                        "name"=>"`^Elementale di Fuoco",
                        "rounds"=>8,
                        "wearoff"=>"Del fumo si leva dalla tua vittima mentre il fuoco si estingue!",
                        "minioncount"=>1,
                        "maxbadguydamage"=>round($session['user']['attack']*3.5,0),
                        "minbadguydamage"=>round($session['user']['attack']*1.75,0),
                        "roundmsg"=>"{badguy} è avviluppato nelle fiamme mentre l'elementale di fuoco lo circonda!",
                    "activate"=>"offense,defense"
                );
                break;
            }
            $session['user']['elementaleuses']-=$_GET['l'];
        }else{
            $session['bufflist']['el0'] = array(
                "startmsg"=>"`nProvi ad evocare un elementale ma riesce a creare solo una pila di fango.  {badguy} si è divertito per lo spettacolino gratuito che gli hai offerto.`n`n",
                "rounds"=>1,
                "activate"=>"roundstart"
            );
        }
    }

    if ($_GET['skill']=="BB"){
        if ($session['user']['barbarouses'] >= $_GET['l']){
            $creaturedmg = 0;
            switch($_GET['l']){
            case 1:
                $session['bufflist']['bb1'] = array(
                        "startmsg"=>"`6La gente non si aspetta che i Barbari riescano a schivare i colpi, ecco perchè ci riescono!`n`n",
                        "name"=>"`^Schivata Misteriosa",
                        "roundmsg"=>"con un balzo rapissimo riesci ad allontanarti da {badguy}, che ti colpisce di striscio",
                        "rounds"=>6,
                        "wearoff"=>"Inizi a sentirti stanco...",
                        "defmod"=>1.4,
                        "activate"=>"roundstart");
                break;
            case 2:
                $session['bufflist']['bb2'] = array(
                        "startmsg"=>"`6Entri in comunione con il tuo subconscio e tiri fuori la rabbia interiore!`n`n",
                        "name"=>"`^Rabbia",
                        "maxbadguydamage"=>$session['user']['level']*1.1,
                        "minioncount"=>1,
                        "rounds"=>6,
                        "wearoff"=>"La stanchezza inizia a farsi sentire.",
                        "effectmsg"=>"`^Dai a {badguy} un sonoro schiaffone, provocandogli {damage} punti di danno.",
                        "effectnodmgmsg"=>"`^Forse incanalare la rabbia non sta funzionando, perché manchi {badguy}!",
                        "atkmod"=>1.5,
                        "activate"=>"roundstart"
                    );
                break;
            case 3:
                $session['bufflist']['bb3'] = array(
                        "startmsg"=>"`6Il dolore alimenta maggiormente la tua rabbia...`n`n",
                        "name"=>"`^Riduzione Danno",
                        "roundmsg"=>"La rabbia ti fa rispondere con più veemenza ai colpi subiti",
                        "rounds"=>6,
                        "wearoff"=>"L'adrenalina viene riassorbita, ed il dolore della battaglia torna a farsi sentire....",
                        "badguyatkmod"=>0.9,
                        "damageshield"=>1.4,
                        "activate"=>"offense,defense",
                    );
                break;
            case 5:
                $session['bufflist']['bb5'] = array(
                        "startmsg"=>"`6Essere un Barbaro non è difficile, e non c'è nessun segreto.`nTi infuri per un nonnulla e ti lamenti di qualsiasi cosa.`n`n",
                        "name"=>"`^Ira Possente",
                        "rounds"=>7,
                        "wearoff"=>"La fatica prende il sopravvento sull'ira.",
                        "effectmsg"=>"`^Colpisci {badguy} con un colpo diretto, causandogli {damage} punti di danno.",
                        "effectnodmgmsg"=>"`^Forse dovresti guidare meglio la tua ira, perché manchi clamorosamente {badguy}!",
                        "atkmod"=>1.5,
                        "damageshield"=>1.4,
                        "activate"=>"offense,defense"
                );
                break;
            }
            $session['user']['barbarouses']-=$_GET['l'];
        }else{
            $session['bufflist']['bb0'] = array(
                "startmsg"=>"`nTi concentri per raccogliere la tua rabbia, ma non riesci a smettere di ridire pensando ad una delle barzellette di Cedrik.`n`n",
                "rounds"=>1,
                "activate"=>"roundstart"
            );
        }
    }

    if ($_GET['skill']=="BA"){
        if ($session['user']['bardouses'] >= $_GET['l']){
            $creaturedmg = 0;
            switch($_GET['l']){
            case 1:
                $session['bufflist']['ba1'] = array(
                        "startmsg"=>"`5Canti una canzone del tuo impegno di audace avventuriero!  {badguy} non è più motivato!`n`n",
                        "name"=>"`%Fascinazione",
                        "roundmsg"=>"{badguy} è affascinato dalla tua canzone",
                        "rounds"=>8,
                        "wearoff"=>"La tua voce si sta facendo roca...",
                        "badguyatkmod"=>0.5,
                        "activate"=>"defense");
                break;
            case 2:
                $session['bufflist']['ba2'] = array(
                        "startmsg"=>"`%{badguy} `5non vuole far del male a `^".$session['user']['name'].".`n`%{badguy} `5vuole far male a `%{badguy}!`n`n",
                        "name"=>"`%Canzone alla Rovescia",
                        "roundmsg"=>"{badguy} non riesce a mettere tutta la potenza nei suoi colpi, ed anzi subisce le tue risposte.",
                        "rounds"=>8,
                        "damageshield"=>0.5,
                        "wearoff"=>"La tua voce si sta indebolendo...",
                        "activate"=>"roundstart"
                );
                break;
            case 3:
                $session['bufflist']['ba3'] = array(
                        "startmsg"=>"`5Vieni avvolto dall'aura della tua propria grandezza ascoltando la tua canzone.`n`n",
                        "name"=>"`%Grandezza Ispirata",
                        "rounds"=>8,
                        "effectmsg"=>"`%Bastoni {badguy} con la tua chitarra, provocandogli {damage} punti danno.",
                        "effectnodmgmsg"=>"`%Nonostante la tua immensa grandezza, di cui parla la tua canzone, `\$LO MANCHI`%!",
                        "atkmod"=>1.5,
                        "defmod"=>1.5,
                        "wearoff"=>"La tua voce è ridotta ad un flebile sospiro...",
                        "activate"=>"offense,defense",
                );
                break;
            case 5:
                $session['bufflist']['ba5'] = array(
                        "startmsg"=>"`5Il tuo fascino richiama in tuo aiuto molte creature nelle vicinanze!`n`n",
                        "name"=>"`%Suggestione di Massa",
                        "rounds"=>8,
                        "minioncount"=>round($session['user']['level']/3)+1,
                        "minbadguydamage"=>1,
                        "maxbadguydamage"=>$session['user']['level']*2,
                        "effectmsg"=>"`%Una creatura affascinata dalla tua voce colpisce {badguy} causandogli {damage} punti danno!",
                        "effectnodmgmsg"=>"`%Una delle creature richiamate dal tuo fascino manca completamente {badguy}!  È difficile trovare dei buoni aiutanti al giorno d'oggi.",
                        "wearoff"=>"La tua voce perde il fascino che la contraddistingue...",
                        "activate"=>"roundstart"
                );
                break;
            }
            $session['user']['bardouses']-=$_GET['l'];
        }else{
            $session['bufflist']['ba0'] = array(
                "startmsg"=>"`nCerchi di comporre una canzone, ma ti manca l'ispirazione giusta.  Perciò, nessuno rimane affascinato dalle tue qualità di artista.`n`n",
                "rounds"=>1,
                "activate"=>"roundstart"
            );
        }
    }
}

if ($badguy['creaturehealth']>0 && $session['user']['hitpoints']>0) {
    output ("`\$`c`b~ ~ ~ Combattimento ~ ~ ~`b`c`0`n");

    output("`@Incontri `^".$badguy['creaturename']."`@ che ti attacca con `%".$badguy['creatureweapon']."`@!`0`n`n");
    if ($session['user']['alive']){
        output("`2Livello: `6".$badguy['creaturelevel']."`0`n");
    }else{
        output("`2Livello: `6Non-Morto`0`n");
    }

    output("`2`bInizio del round:`b`n");
    output(($session['user']['alive']?"Gli Hitpoints":"I Soulpoints")."`2 di ".$badguy['creaturename']."`2: `6".$badguy['creaturehealth']."`0`n");
    output("`2I tuoi ".($session['user']['alive']?"Hitpoints":"Soulpoints").": `6".$session['user']['hitpoints']."`0`n");
}

reset($session['bufflist']);
while (list($key,$buff)=each($session['bufflist'])){
    // reset the 'used this round state'
    $buff['used']=0;
}

if ($badguy['pvp'] && $_GET['bg']==2){
    debuglog("attacca ".$badguy['creaturename']." in casa");
}
if ($badguy['pvp'] &&
    count($session['bufflist'])>0 &&
    is_array($session['bufflist'])) {
    if ($session['user']['buffbackup']>""){

    }else{
        output("`&Gli dei hanno sospeso ogni effetto speciale!`n");
        $session['user']['buffbackup']=serialize($session['bufflist']);
        $session['bufflist']=array();
        if ($_GET['bg']==2){
            $session['bufflist']['homeadvantage'] = array(
                "startmsg" =>"`n`\$".$badguy['creaturename']." `\$ha il vantaggio dato dal combattimento casalingo! `n`n",
                "name" =>"`\$Svantaggio",
                "rounds" =>20,
                "wearoff" =>"Il Vantaggio Casalingo del tuo avversario si è esaurito.",
                "minioncount" =>1,
                "maxgoodguydamage" => round($session['user']['level']+5),
                "effectmsg" =>"`7Combatti contro il Vantaggio Casalingo di {badguy}`7, che ti causa `\${damage}`7 punti di danno.",
                "effectnodmgmsg" =>"",
                "activate" =>"roundstart",
                );
        }
        if ($_GET['bg']==1){
            $session['bufflist']['bodyguard'] = array(
                "startmsg"=>"`n`\$La Guardia del Corpo di ".$badguy['creaturename']." lo protegge!`n`n",
                "name"=>"`&Guardia del Corpo",
                "rounds"=>5,
                "wearoff"=>"La Guardia del Corpo sembra essersi addormentata.",
                "minioncount"=>1,
                "maxgoodguydamage"=> round($session['user']['level']/2,0) +1,
                "effectmsg"=>"`7La Guardia del Corpo di {badguy} ti colpisce e ti fa `\${damage}`7 di danno.",
                "effectnodmgmsg"=>"`7La Guardia del Corpo di {badguy} cerca di colpirti ma  `\$TI MANCA`7!",
                "activate"=>"roundstart"
                );
        }
    }
}
// Run the beginning of round buffs (this also calculates all modifiers)

//Modifica AutoFight
for ($count=$count;$count>0;$count--){
    // Modifica per facilitare la vita in foresta ai contadini fino al livello 5, ma non contro i maestri
    if (!$badguy['pvp'] AND $session['user']['reincarna'] == 0 AND $session['user']['dragonkills'] == 0 AND $session['user']['pk'] == 0 AND $session['user']['level'] < 6 AND !isset($master['creatureattack']) AND $session['user']['alive'] != 0){
        $session['bufflist']['farmboy']=array(
            "name"=>"`&L'aiuto degli Dei",
            "rounds"=>1,
            "wearoff"=>"Forse gli dei ti aiuteranno nuovamente",
            "atkmod"=>1.3,
            "defmod"=>1.3,
            "regen"=>$session['user']['level'],
            "badguydmgmod"=>0.9,
            "startmsg"=>"`%Senti l'aiuto degli dei dentro di te`n",
            "activate"=>"roundstart"
        );
    }
    // Fine modifica by Excalibur

if ($badguy['creaturehealth']>0 && $session['user']['hitpoints']>0){
//Fine AutoFight
//INIZIO DEL ROUND
$buffset = activate_buffs("roundstart");
$creaturedefmod=$buffset['badguydefmod'];
$creatureatkmod=$buffset['badguyatkmod'];
$atkmod=$buffset['atkmod'];
$defmod=$buffset['defmod'];
} //Modifica AutoFight
if ($badguy['creaturehealth']>0 && $session['user']['hitpoints']>0){
    if ($badguy['pvp']) {
        $adjustedcreaturedefense = $badguy['creaturedefense'];
    } else {
        $adjustedcreaturedefense =
             ($creaturedefmod*$badguy['creaturedefense'] /
             ($adjustment*$adjustment));
    }
    $creatureattack = $badguy['creatureattack']*$creatureatkmod;
    $adjustedselfdefense = ($session['user']['defence'] * $adjustment * $defmod);

    while($creaturedmg==0 && $selfdmg==0){//---------------------------------
        $atk = $session['user']['attack']*$atkmod;
        if (e_rand(1,20)==1) $atk*=3;
        $patkroll = e_rand(0,$atk);
        $catkroll = e_rand(0,$adjustedcreaturedefense);
        $creaturedmg = 0-(int)($catkroll - $patkroll);
        if ($creaturedmg<0) {
            //output("`#DEBUG: Initial (<0) creature damage $creaturedmg`n");
            $creaturedmg = (int)($creaturedmg/2);
            //output("`#DEBUG: Modified (<0) creature damage $creaturedmg`n");
            $creaturedmg = round($buffset['badguydmgmod']*$creaturedmg,0);
            //output("`#DEBUG: Modified (<0) creature damage $creaturedmg`n");
        }
        if ($creaturedmg > 0) {
            //output("`#DEBUG: Initial (>0) creature damage $creaturedmg`n");
            $creaturedmg = round($buffset['dmgmod']*$creaturedmg,0);
            //output("`#DEBUG: Modified (>0) creature damage $creaturedmg`n");
        }
        //output("`#DEBUG: Attack score: $atk`n");
        //output("`#DEBUG: Creature Defense Score: $adjustedcreaturedefense`n");
        //output("`#DEBUG: Player Attack roll: $patkroll`n");
        //output("`#DEBUG: Creature Defense roll: $catkroll`n");
        //output("`#DEBUG: Final Creature Damage: $creaturedmg`n");
        $pdefroll = e_rand(0,$adjustedselfdefense);
        $catkroll = e_rand(0,$creatureattack);
        $selfdmg = 0-(int)($pdefroll - $catkroll);
        if ($selfdmg<0) {
            //output("`#DEBUG: Initial (<0) self damage $selfdmg`n");
            $selfdmg=(int)($selfdmg/2);
            //output("`#DEBUG: Modified (<0) self damage $selfdmg`n");
            $selfdmg = round($selfdmg*$buffset['dmgmod'], 0);
            //output("`#DEBUG: Modified (<0) self damage $selfdmg`n");
        }
        if ($selfdmg > 0) {
            //output("`#DEBUG: Initial (>0) self damage $selfdmg`n");
            $selfdmg = round($selfdmg*$buffset['badguydmgmod'], 0);
            //output("`#DEBUG: Modiied (>0) self damage $selfdmg`n");
        }
        //output("`#DEBUG: Defense score: $adjustedselfdefense`n");
        //output("`#DEBUG: Creature Attack score: $creatureattack`n");
        //output("`#DEBUG: Player Defense roll: $pdefroll`n");
        //output("`#DEBUG: Creature Attack roll: $catkroll`n");
        //output("`#DEBUG: Final Player damage: $selfdmg`n");
    }
}else{
    $creaturedmg=0;
    $selfdmg=0;
    $count=0;
}
// Handle god mode's invulnerability
if ($buffset['invulnerable']) {
    $creaturedmg = abs($creaturedmg);
    $selfdmg = -abs($selfdmg);
}

if (e_rand(1,3)==1 &&
    ($_GET['op']=="search" ||
     ($badguy['pvp'] && $_GET['act']=="attack"))) {
    if ($badguy['pvp']){
        output("`b`^L'abilità di ".$badguy['creaturename']."`\$ gli permette di effettuare il primo round di attacco!`0`b`n`n");
    }else{
        output("`b`^".$badguy['creaturename']."`\$ ti sorprende e fa la prima mossa d'attacco!`0`b`n`n");
    }
    $_GET['op']="run";
    $surprised=true;
}else{
    if ($_GET['op']=="search")
        output("`b`\$La tua abilità ti consente di attaccare per primo!`0`b`n`n");
    $surprised=false;
}

if ($_GET['op']=="fight" || $_GET['op']=="run"){
    if ($_GET['op']=="fight"){   //FASE DI ATTACCO
        if ($badguy['creaturehealth']>0 && $session['user']['hitpoints']>0){

            //Modifica Fame
            if ($session['user']['alive']==1 AND $session['user']['superuser']==0) $session['user']['hunger']+=1;
            //fine modifica fame

            $buffset = activate_buffs("offense");
            if ($atk > $session['user']['attack']) {
                if ($atk > $session['user']['attack']*3){
                    if ($atk>$session['user']['attack']*4){
                        output("`&`bEsegui una <font size='+1'>MEGA</font> mossa d'attacco!!!`b`n",true);
                    }else{
                        output("`&`bEsegui una DOPPIA mossa di potenza!!!`b`n");
                    }
                }else{
                    if ($atk>$session['user']['attack']*2){
                        output("`&`bEsegui una mossa potente!!!`b`0`n");
                    }elseif ($atk>$session['user']['attack']*1.25){
                        output("`7`bEsegui una mossa di potenza aumentata!`b`0`n");
                    }
                }
            }
            if ($creaturedmg==0){
                output("`4Cerchi di colpire `^".$badguy['creaturename']."`4 ma `\$MANCHI!`n");
                $creaturedmg = -(process_protshield($buffset['protectiveshield'], -$creaturedmg));
                process_dmgshield($buffset['dmgshield'], 0);
                process_lifetaps($buffset['lifetap'], 0);
            }else if ($creaturedmg<0){
                output("`4Cerchi di colpire `^".$badguy['creaturename']."`4 ma `\$CONTRATTACCA `4e ti causa `\$".(0-$creaturedmg)."`4 punti di danno!`n");
                $creaturedmg = -(process_protshield($buffset['protectiveshield'], -$creaturedmg));
                if ($creaturedmg<0) $badguy['diddamage']=1;
                $session['user']['hitpoints']+=$creaturedmg;
                process_dmgshield($buffset['dmgshield'],-$creaturedmg);
                process_lifetaps($buffset['lifetap'],$creaturedmg);
                //Maximus Inizio: Usura Arma e Armatura
                /*
                if ($session['user']['usura_armatura']>0){
                    $session['user']['usura_armatura']--;
                }
                */
                //Maximus Fine: Usura Arma e Armatura
            }else{
                output("`4Colpisci `^".$badguy['creaturename']."`4 causando `^$creaturedmg`4 punti di danno!`n");
                $creaturedmg = -(process_protshield($buffset['protectiveshield'], -$creaturedmg));
                $badguy['creaturehealth']-=$creaturedmg;
                process_dmgshield($buffset['dmgshield'],-$creaturedmg);
                process_lifetaps($buffset['lifetap'],$creaturedmg);
                //Maximus Inizio: Usura Arma e Armatura
                /*
                if ($session['user']['usura_arma']>0){
                    $session['user']['usura_arma']--;
                }
                */
                //Maximus Fine: Usura Arma e Armatura
            }
            if ($creaturedmg>$session['user']['punch']){
               $session['user']['punch']=$creaturedmg;
               output("`@`b`cQUESTO È STATO IL TUO COLPO PIÙ POTENTE DI SEMPRE!`b`c");
            }
        }
    }else if($_GET['op']=="run" && !$surprised){
        output("`4Sei troppo impegnato a tentare di scappare come un coniglio per combattere `^".$badguy['creaturename']."`4.`n");
    }
    // We need to check both user health and creature health. Otherwise the user
     // can win a battle by a RIPOSTE after he has gone <= 0 HP.
    //-- Gunnar Kreitz
    if ($badguy['creaturehealth']>0 && $session['user']['hitpoints']>0){ //FASE DI DIFESA
        $buffset = activate_buffs("defense");
        //Excalibur: Modifica per limitare turni perfetti
        if (!$badguy['pvp']) {
        if ($session['user']['perfect'] > 10 AND $badguy['diddamage'] == 0) {
            $caso = e_rand(1,100);
            if ($caso > 90) $selfdmg = intval($session['user']['hitpoints']*(1-$caso/100));
        }
        if ($session['user']['perfect'] > 15 AND $badguy['diddamage'] == 0) {
            $caso = e_rand(1,100);
            if ($caso > 70) $selfdmg = intval($session['user']['hitpoints']*(1-$caso/100));
        }
        if ($session['user']['perfect'] > 20 AND $badguy['diddamage'] == 0) {
            $caso = e_rand(1,100);
            if ($caso > 50) $selfdmg = intval($session['user']['hitpoints']*(1-$caso/100));
        }
        if ($session['user']['perfect'] > 25 AND $badguy['diddamage'] == 0) {
            $caso = e_rand(1,100);
            if ($caso > 30) $selfdmg = intval($session['user']['hitpoints']*(1-$caso/100));
        }
        if ($session['user']['perfect'] > 30 AND $badguy['diddamage'] == 0) {
            $caso = e_rand(1,100);
            if ($caso > 10) $selfdmg = intval($session['user']['hitpoints']*(1-$caso/100));
        }
        if ($session['user']['perfect'] > 35 AND $badguy['diddamage'] == 0) {
            $caso = e_rand(1,100);
            if ($caso > 1) $selfdmg = intval($session['user']['hitpoints']*(1-$caso/100));
        }
        }//Excalibur: fine modifica limitazione perfect
        if ($selfdmg==0){
            output("`^".$badguy['creaturename']."`4 cerca di colpirti ma `\$MANCA!`n");
            $selfdmg = process_protshield($buffset['protectiveshield'], $selfdmg);
            process_dmgshield($buffset['dmgshield'], 0);
            process_lifetaps($buffset['lifetap'], 0);
        }else if ($selfdmg<0){
            output("`^".$badguy['creaturename']."`4 cerca di colpirti ma `^CONTRATTACCHI`4 causando `^".(0-$selfdmg)."`4 punti di danno!`n");
            $selfdmg = process_protshield($buffset['protectiveshield'], $selfdmg);
            $badguy[creaturehealth]+=$selfdmg;
            process_lifetaps($buffset['lifetap'], -$selfdmg);
            process_dmgshield($buffset['dmgshield'], $selfdmg);
            //Maximus Inizio: Usura Arma e Armatura
            /*
            if ($session['user']['usura_arma']>0){
                $session['user']['usura_arma']--;
            }
            */
            //Maximus Fine: Usura Arma e Armatura
        }else{
            output("`^".$badguy['creaturename']."`4 ti colpisce causando `\$$selfdmg`4 punti di danno!`n");
            $selfdmg = process_protshield($buffset['protectiveshield'], $selfdmg);
            $session['user']['hitpoints']-=$selfdmg;
            process_dmgshield($buffset['dmgshield'], $selfdmg);
            process_lifetaps($buffset['lifetap'], -$selfdmg);
            if ($selfdmg>0) $badguy['diddamage']=1;
            //Maximus Inizio: Usura Arma e Armatura
            /*
            if ($session['user']['usura_armatura']>0){
                $session['user']['usura_armatura']--;
            }
            */
            //Maximus Fine: Usura Arma e Armatura
        }
    } //FINE FASE DIFESA
    //Sook, modifica buff che agiscono a fine round
    if ($badguy['creaturehealth']>0 && $session['user']['hitpoints']>0){
        $buffset = activate_buffs("endround");
    }
}
expire_buffs();

//Modifica AutoFight
$creaturedmg=0;
$selfdmg=0;
if ($count>1 && $session['user']['hitpoints']>0 && $badguy['creaturehealth']>0) output("`2`bRound successivo:`b`n");
if ($badguy['creaturehealth']<=0 AND $session['user']['hitpoints']>0){
  $victory=true;
  $defeat=false;
  $count=0;
}else{
  if ($session['user']['hitpoints']<=0){
      $defeat=true;
      $victory=false;
      $count=0;
  }else{
      $defeat=false;
      $victory=false;
  }
}
}
//Fine AutoFight

if ($session['user']['hitpoints']>0 &&
    $badguy['creaturehealth']>0 &&
    ($_GET['op']=="fight" || $_GET['op']=="run")){
    output("`2`bFine del Round:`b`n");
    output("`2".$badguy['creaturename']."`2 ha ".($session['user']['alive']?"Hitpoints":"Soulpoints").": `6".$badguy['creaturehealth']."`0`n");
    output("`2TU HAI ".($session['user']['alive']?"Hitpoints":"Soulpoints").": `6".$session['user']['hitpoints']."`0`n");
}
// Modifica AutoFight
/*
if ($badguy[creaturehealth]<=0){
    $victory=true;
    $defeat=false;
}else{
    if ($session[user][hitpoints]<=0){
        $defeat=true;
        $victory=false;
    }else{
        $defeat=false;
        $victory=false;
    }
}
*/
//Fine AutoFight
if ($victory || $defeat){
    // Unset the bodyguard buff at the end of the fight.
    // Without this, the bodyguard persists *and* the older buffs are held
    // off for a while! :/
    if (isset($session['bufflist']['bodyguard'])) unset($session['bufflist']['bodyguard']);
    if (isset($session['bufflist']['homeadvantage'])) unset ($session['bufflist']['homeadvantage']);

    //Sook, cancellazione dei buff che durano un solo combattimento e che sono ancora attivi
    reset($session['bufflist']);
    while (list($key, $buff) = each($session['bufflist'])) {
        $deactivate = strpos($buff['deactivate'], "battleend");
        if ($deactivate !== false) $deactivate = true; // handle strpos == 0;
        if ($deactivate) {
            if ($buff['wearoff']) {
                $msg = $buff['wearoff'];
                $msg = str_replace("{badguy}", $badguy['creaturename'], $msg);
                output("`)$msg`n");
            }
            unset($session['bufflist'][$key]);
        }
    }

    if (!is_array($session['bufflist']) || count($session['bufflist']) <= 0) {
        $session['bufflist'] = unserialize($session['user']['buffbackup']);
        if (is_array($session['bufflist'])) {
            if (count($session['bufflist'])>0 && $badguy['pvp'])
                output("`&Gli dei hanno ripristinato i tuoi effetti speciali.`n`n");
        } else {
            $session['bufflist'] = array();
        }
    }
    $session['user']['buffbackup'] = "";
}
$session['user']['badguy']=createstring($badguy);
?>