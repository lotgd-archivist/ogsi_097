<?php
require_once "common.php";   
checkday(); 
page_header("Il Drago Rosso");
if (!isset($session)) exit(); 
if ($_GET[op]=="drago") {
output("`3Cercando di cogliere di sorpresa il `4Drago Rosso`3 estrai la tua ".$session['user']['weapon']." ma il rumore sveglia ");
output("la possente bestia. Hai perso il fattore sorpresa e adesso puoi contare solo sulle tue forze. `n");
$battle=true;

//Setup del Drago Rosso
						$badguy = array("creaturename"=>"`4Il Drago Rosso`0"
						,"creaturelevel"=>14
						,"creatureweapon"=>"Bacchetta dell'Orrore"
						,"creatureattack"=>45
						,"creaturedefense"=>35
						,"creaturehealth"=>300
						, "diddamage"=>0);
	$points = 0;
	while(list($key,$val)=each($session[user][dragonpoints])){
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
	$session[user][badguy]=createstring($badguy);
}
if ($_GET[op]=="drago" || $_GET[op]=="fight"){
$battle=true;
if ($battle) {
//   output("Drago Rosso");
		include_once("battle.php");
		   if($victory) {
                if ($badguy['diddamage'] != 1) $flawless = 1;
				$_GET[op]="vittoria1";
				output("`1Hai sconfitto `^".$badguy['creaturename'].".`n");
        		addnews("`3".$session[user][name]."`5 ha sconfitto `^".$badguy['creaturename']." `%nella Selva Oscura.");
                $expbonus=intval($session['user']['experience']*0.3);
				$gembonus=e_rand(10,20);
				$goldbonus=e_rand(500,1500)*$session['user']['level'];
				$session['user']['quest']+=3;
				debuglog("guadagna $gembonus gemme, $goldbonus oro e 30% exp per aver ucciso Drago Rosso nella selva");
				output("`1Il tesoro che `^".$badguy['creaturename']." `1custodiva è immenso!!`n`n");
				output("`1Di fianco al suo cadavere trovi `6`b$gembonus gemme`b`1 e `6`b$goldbonus pezzi d'oro`b`1!!!`n");
				output("`1Guadagni anche `6`b$expbonus`b`1 punti esperienza!!!`n");
				if ($flawless){
				output("Per aver disputato un combattimento perfetto guadagni altre `610 gemme`1`n");
				debuglog("guadagna altre 10 gemme per combattimento perfetto contro Drago Rosso nella selva");
				$gembonus+=10;
				}
				$session['user']['experience']+=$expbonus;
				$session['user']['gold']+=$goldbonus;
				$session['user']['gems']+=$gembonus;
				$battle=false;
				$badguy=array();
				$session[user][badguy]="";
				}
				else {
					    if ($defeat) {
                        $_GET[op]="";
						output("`nSei stato sconfitto da `^".$badguy['creaturename'].".`n");
						addnews("`3".$session[user][name]."`5 è stat".($session[user][sex]?"a":"o")." uccis".($session[user][sex]?"a":"o")." da `^".$badguy['creaturename']."`5 nella `2Selva Oscura.");
						output("`2Perdi tutto l'oro che avevi con te!!!`n");
						output("Perdi il 20% della tua esperienza!!!`n");
						debuglog("muore e perde 20% exp e {$session['user']['gold']} oro ucciso dal Drago Rosso nella selva");
						$session['user']['quest']+=3;
						$session['user']['gold']=0;
						$session['user']['experience']*=0.8;
						$session['user']['hitpoints']=0;
                        $session['user']['alive']=false;
						$battle=false;
						$badguy=array();
						$session[user][badguy]="";
				addnav("Terra delle Ombre","shades.php");
                        
                }
				else   {
                        fightnav(true,false);
                		}
         		}
} //chiusura del controllo di $battle
} //chiusura del controllo di ==drago o fight2
if ($_GET[op]=="vittoria1" ) {
addnav("Torna al Villaggio","village.php");
switch(e_rand(1,10)){
case 1:
output("`n`3Tra la carcassa del `4Drago Rosso`3 trovi una pozione rigenerante che trangugi velocemente.`n");
output("I tuoi HP sono stati riportati al massimo!");
$session['user']['hitpoints']=$session['user']['maxhitpoints'];
case 2: case 3: case 4: case 5:case 6:case 7: case 8: case 9: case 10:  
}
}
page_footer();


?>