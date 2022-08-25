<?php
require_once "common.php";
$pvptime = getsetting("pvptimeout",600);
$pvptimeout = date("Y-m-d H:i:s",strtotime(date("r")."-$pvptime seconds"));
page_header("Combattimento PvP!");
if ($_GET[op]=="" && $_GET[act]!="attack"){
	//if ($session['user']['age']<=5 && $session['user']['dragonkills']==0){
	//  output("`\$Warning!`^ Players are immune from Player vs Player (PvP) combat for their first 5 days in the game.  If you choose to attack another player, you will lose this immunity!`n`n");
	//}
	checkday();
	pvpwarning();
  output("`4Ti dirigi verso i campi, dove sai che alcuni guerrieri poco saggi stanno dormendo.`n`nHai `^".$session[user][playerfights]."`4 combattimenti PvP rimasti per oggi.");
	addnav("Elenco Guerrieri","pvp.php?op=list");
  addnav("Torna al Villaggio","village.php");
}else if ($_GET[op]=="list"){
	checkday();
	pvpwarning();
	$days = getsetting("pvpimmunity", 5);
	$exp = getsetting("pvpminexp", 1500);
  $sql = "SELECT name,alive,location,sex,level,laston,loggedin,login,pvpflag,jail FROM accounts WHERE 
	(locked=0) AND 
	(age > $days OR dragonkills > 0 OR pk > 0 OR experience > $exp) AND
	(level >= ".($session[user][level]-1)." AND jail=0 AND level <= ".($session[user][level]+2).") AND 
	(alive=1 AND location=0) AND 
	(laston < '".date("Y-m-d H:i:s",strtotime("-".getsetting("LOGINTIMEOUT",900)." sec"))."' OR loggedin=0) AND
	(acctid <> ".$session[user][acctid].")
	ORDER BY level DESC";
	//echo ("<pre>$sql</pre>");
  $result = db_query($sql) or die(db_error(LINK));
	output("<table border='0' cellpadding='3' cellspacing='0'><tr><td>Nome</td><td>Livello</td><td>Ops</td></tr>",true);
	for ($i=0;$i<db_num_rows($result);$i++){
	  $row = db_fetch_assoc($result);
	  $biolink="bio.php?char=".rawurlencode($row[login])."&ret=".urlencode($_SERVER['REQUEST_URI']);
	  addnav("", $biolink);
		if($row[pvpflag]>$pvptimeout){
		  output("<tr class='".($i%2?"trlight":"trdark")."'><td>$row[name]</td><td>$row[level]</td><td>[ <a href='$biolink'>Bio</a> | `i(Attaccato troppo recentemente)`i ]</td></tr>",true);
		}else{
		  output("<tr class='".($i%2?"trlight":"trdark")."'><td>$row[name]</td><td>$row[level]</td><td>[ <a href='$biolink'>Bio</a> | <a href='pvp.php?act=attack&name=".rawurlencode($row[login])."'>Attacca</a> ]</td></tr>",true);
			addnav("","pvp.php?act=attack&name=".rawurlencode($row[login]));
		}
	}
	output("</table>",true);
	addnav("Elenco Guerriero","pvp.php?op=list");
  addnav("Torna al Villaggio","village.php");
if (getsetting("hasegg",0)>0){
   $sql = "SELECT name FROM accounts WHERE acctid = ".getsetting("hasegg",0);
   $result = db_query($sql) or die(db_error(LINK));
   $row = db_fetch_assoc($result);
   output("`n`n$row[name] possiede il mitico `6Uovo d'Oro!");
  } 
} else if ($_GET[act] == "attack") {
 if ($_GET[op5] == "locanda") {
  $session[user][evil]+=5;
  }
  $sql = "SELECT name AS creaturename,
	               level AS creaturelevel,
								 weapon AS creatureweapon,
								 gold AS creaturegold,
								 experience AS creatureexp,
								 maxhitpoints AS creaturehealth,
								 attack AS creatureattack,
								 defence AS creaturedefense,
								 bounty AS creaturebounty,
								 loggedin,
								 location,
								 laston,
								 alive,
								 acctid,
								 pvpflag,
								 evil
					FROM accounts
					WHERE login=\"$_GET[name]\"";
	$result = db_query($sql) or die(db_error(LINK));
	if (db_num_rows($result)>0){
		$row = db_fetch_assoc($result);
		if (abs($session[user][level]-$row[creaturelevel])>2){
		  output("`\$Errore:`4 Il livello di questo giocatore è troppo lontano dal tuo!");
		}elseif ($row[pvpflag] > $pvptimeout){
			output("`\$Oops:`4 Quel giocatore al momento sta combattendo qualcun altro, dovrai aspettare il tuo turno! $row[pvpflag] : $pvptimeout");
		}else{
		  if (strtotime($row[laston]) > strtotime("-".getsetting("LOGINTIMEOUT",900)." sec") && $row[loggedin]){
			  output("`\$Errore:`4 Quel giocatore al momento è collegato.");
			}else{
			  if ((int)$row[location]!=0 && 0){
				  output("`\$Errore:`4 Quel giocatore non è in un posto in cui puoi attaccarlo.");
				}else{
				  if((int)$row[alive]!=1){
					  output("`\$Errore:`4 Quel giocatore è già morto!");
					}else{
					  if ($session[user][playerfights]>0){
							$sql = "UPDATE accounts SET pvpflag=now() WHERE acctid=$row[acctid]";
							db_query($sql);
							$battle=true;
							$row[pvp]=1;
							$row[creatureexp] = round($row[creatureexp],0);
							$row[playerstarthp] = $session[user][hitpoints];
							$session[user][badguy]=createstring($row);
							$session[user][playerfights]--;
							$session['user']['buffbackup']="";
							pvpwarning(true);
						}else{
						  output("`4Visto quanto sei stanco, pensi che sia meglio evitare un'altra battaglia giocatore contro giocatore per oggi.");
						}
					}
				}
			}
		}
	}else{
	  output("`\$Errore:`4 Utente non trovato! E come ci sei arrivato qui, poi?");
	}
  if ($battle){
	  
	}else{
	  addnav("Torna al Villaggio","village.php");
	}
}
if ($_GET[op]=="run"){
  output("Sarebbe disonorevole fuggire");
	$_GET[op]="fight";
}
if ($_GET[skill]!=""){
  output("Sarebbe disonorevole usare un'abilità speciale");
	$_GET[skill]="";
}
if ($_GET[op]=="fight" || $_GET[op]=="run"){
	$battle=true;
}
if ($battle){
  include("battle.php");
	if ($victory){
		//$badguy[creaturegold]=e_rand(0,$badguy[creaturegold]);
		$exp = round(getsetting("pvpattgain",10)*$badguy[creatureexp]/100,0);
		$expbonus = round(($exp * (1+.1*($badguy[creaturelevel]-$session[user][level]))) - $exp,0);
		output("`b`&$badguy[creaturelose]`0`b`n"); 
		output("`b`\$Hai ucciso $badguy[creaturename]!`0`b`n");
		output("`#Ricevi `^$badguy[creaturegold]`# pezzi d'oro!`n");
		//aggiunto by luke per prigione
		//$session[user][evil]+=2;
		//fine prigione by luke 
		// Bounty Check - Darrell Morrone
		if ($badguy[creaturebounty]>0){
		    output("`#Ricevi inoltre per la taglia `^$badguy[creaturebounty]`# pezzi d'oro!`n");
			}
	    // End Bounty Check - Darrell Morrone
		if ($expbonus>0){
		  output("`#***A causa della difficoltà dello scontro, vieni premiato con un bonus di `^$expbonus`# punti esperienza!`n");
		}else if ($expbonus<0){
		  output("`#***Data la facilità dello scontro, vieni penalizzato di `^".abs($expbonus)."`# esperienza!`n");
		}
		output("Ricevi `^".($exp+$expbonus)."`# punti esperienza!`n`0");
		$session['user']['gold']+=$badguy['creaturegold'];
		if ($badguy['creaturegold']) {
			debuglog("guadagna {$badguy['creaturegold']} oro per aver ucciso ", $badguy['acctid']);
		}
		// Add Bounty Gold - Darrell Morrone
		$session['user']['gold']+=$badguy['creaturebounty'];
		if ($badguy['creaturebounty']) {
			debuglog("guadagna {$badguy['creaturebounty']} oro per di taglia per aver ucciso ", $badguy['acctid']);
		}
		$session['user']['experience']+=($exp+$expbonus);
		
		// modifica di Excalibur per non aggiungere punti cattiveria se l'ucciso in locanda è ricercato
		if ($badguy['location']>0 && $badguy['evil']>99){
			$session[user][evil]-=5;
		}
			// fine aggiunta di Excalibur
		
		if ($badguy['location']){
		  addnews("`4".$session['user']['name']."`3 ha sconfitto `4{$badguy['creaturename']}`3 intrufolandosi nella sua stanza alla locanda!");
			$killedin="`6nella Locanda";
		}else{
		  addnews("`4".$session['user']['name']."`3 ha sconfitto `4{$badguy['creaturename']}`3 in un onorevole scontro nei campi.");
			$killedin="`@nei Campi";
		}
		// Add Bounty Kill to the News - Darrell Mororne
		if ($badguy['creaturebounty']>0){
		    addnews("`4".$session['user']['name']."`3 riceve `4{$badguy['creaturebounty']} pezzi d'oro di taglia per la cattura di `4{$badguy['creaturename']}!");
			}
		// Golden Egg - anpera
      if ($badguy['acctid']==getsetting("hasegg",0)){
         savesetting("hasegg",stripslashes($session[user][acctid]));
         output("`n`^Hai preso a $badguy[creaturename] `^l'Uovo d'Oro!`0`n");
         addnews("`^".$session['user']['name']."`^ ha preso a {$badguy['creaturename']}`^ l'Uovo d'Oro!");
      } 
	  //aggiunto by luke per prigione
$sql = "SELECT evil FROM accounts WHERE acctid='".(int)$badguy['acctid']."'"; 
        $result = db_query($sql); 
        $row = db_fetch_assoc($result); 
        if ($row[evil] > 99){ 
            $sql = "UPDATE accounts SET jail=1 WHERE acctid='".(int)$badguy['acctid']."'"; 
            db_query($sql) or die(db_error(LINK));
			addnews("`4".$session['user']['name']."`3 ha riportatato `4{$badguy['creaturename']} dallo Sceriffo!"); 
        } 
        $sql = "SELECT jail FROM accounts WHERE acctid='".(int)$badguy['acctid']."'"; 
        $result = db_query($sql); 
        $row = db_fetch_assoc($result); 
        if ($result==1) addnews("`4{$badguy['creaturename']} deve essere fuggito di prigione."); 
//fine aggiunto by luke per prigione
		$sql = "SELECT gold FROM accounts WHERE acctid='".(int)$badguy['acctid']."'";
		$result = db_query($sql);
		$row = db_fetch_assoc($result);
		$badguy[creaturegold]=((int)$row[gold]>(int)$badguy[creaturegold]?(int)$badguy[creaturegold]:(int)$row[gold]);
		//$sql = "UPDATE accounts SET alive=0, killedin='$killedin', goldinbank=goldinbank-IF(gold<$badguy[creaturegold],gold-$badguy[creaturegold],0),gold=gold-$badguy[creaturegold], experience=experience*.95, slainby=\"".addslashes($session[user][name])."\" WHERE acctid=$badguy[acctid]";
// \/- Gunnar Kreitz
		$lostexp = round($badguy['creatureexp']*getsetting("pvpdeflose",5)/100,0);
 		$mailmessage = "`^".$session['user']['name']."`2 ti ha attaccato $killedin`2 con la sua `^".$session['user']['weapon']."`2, e ti ha sconfitto!"
				." `n`nHai notato che %o aveva un massimo di HitPoints di `^".$badguy['playerstarthp']."`2 e subito prima che tu morissi gliene restavano `^".$session['user']['hitpoints']."`2."
				." `n`nCome risultato, hai perso il `\$".getsetting("pvpdeflose",5)."%`2 della tua esperienza (approssimativamente $lostexp punti), e `^".$badguy[creaturegold]."`2 pezzi d'oro. Ha anche ricevuto `^".$badguy[creaturebounty]." `2come taglia."
				." `n`nNon pensi sia il momento di vendicarsi?";
 		//$mailmessage = str_replace("%p",($session['user']['sex']?"her":"his"),$mailmessage);
 		$mailmessage = str_replace("%o",($session['user']['sex']?"lei":"lui"),$mailmessage);
 		systemmail($badguy['acctid'],"`2Sei stato ucciso $killedin`2",$mailmessage); 
// /\- Gunnar Kreitz

		$sql = "UPDATE accounts SET alive=0, bounty=0, goldinbank=goldinbank-IF(gold<$badguy[creaturegold],gold-$badguy[creaturegold],0),gold=gold-$badguy[creaturegold], experience=experience-$lostexp WHERE acctid=".(int)$badguy[acctid]."";		
		db_query($sql);
		
		$_GET[op]="";
		if ($badguy['location']){
			addnav("Torna al Villaggio","village.php");
		} else {
			addnav("Torna al Villaggio","village.php");
		}
		$badguy=array();
	}else{
		if($defeat){
			addnav("Notizie Giornaliere","news.php");
			$sql = "SELECT taunt FROM taunts ORDER BY rand(".e_rand().") LIMIT 1";
			$result = db_query($sql) or die(db_error(LINK));
			$taunt = db_fetch_assoc($result);
			$taunt = str_replace("%s",($session[user][sex]?"sua":"suo"),$taunt[taunt]);
			$taunt = str_replace("%o",($session[user][sex]?"lei":"lui"),$taunt);
			$taunt = str_replace("%p",($session[user][sex]?"her":"his"),$taunt);
			$taunt = str_replace("%x",($session[user][weapon]),$taunt);
			$taunt = str_replace("%X",$badguy[creatureweapon],$taunt);
			$taunt = str_replace("%W",$badguy[creaturename],$taunt);
			$taunt = str_replace("%w",$session[user][name],$taunt);
			if ($badguy[location]){
				$killedin="`6nella Locanda";
			    }else{
				$killedin="`@nei Campi";
			}
			$badguy[acctid]=(int)$badguy[acctid];
			$badguy[creaturegold]=(int)$badguy[creaturegold];
			systemmail($badguy[acctid],"`2Sei stato vittorioso $killedin`2","`^".$session[user][name]."`2 ti ha attaccato $killedin`2, ma lo hai battuto!`n`nDi conseguenza, hai ricevuto `^".round($session[user][experience]*getsetting("pvpdefgain",10)/100,0)."`2 punti esperienza e `^".$session[user][gold]."`2 pezzi d'oro!"); 
			addnews("`%".$session[user][name]."`5 è stato ucciso quando ha attaccato $badguy[creaturename] $killedin`5.`n$taunt");
			$sql = "UPDATE accounts SET gold=gold+".(int)$session[user][gold].", experience=experience+".round($session[user][experience]*getsetting("pvpdefgain",10)/100,0)." WHERE acctid=".(int)$badguy[acctid]."";
			db_query($sql);
			$session[user][alive]=false;
			debuglog("perso {$session['user']['gold']} oro quando è stato ucciso da ", $badguy['acctid']);
			$session[user][gold]=0;
			$session[user][hitpoints]=0;
			$session[user][experience]=round($session[user][experience]*(100-getsetting("pvpattlose",15))/100,0);
			$session[user][badguy]="";
			//aggiunto by luke per prigione
			//$session[user][evil]+=1;
			//fine aggiunto by luke per prigione
			output("`b`&Sei stato ucciso da `%$badguy[creaturename]`&!!!`n");
			output("`4Hai perso tutto l'oro che avevi con te!`n");
			output("`4".getsetting("pvpattlose",15)."% della tua esperienza è andato perduto!`n");
			output("Potrai ricominciare a combattere domani.");
			
			page_footer();
		}else{
		  fightnav(false,false);
		}
	}
}
page_footer();
?>
