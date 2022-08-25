<?php
/*
Idra della palude di Lerna 
Created by Hugues
Script destinato agli amanti del powerplay 
Il giocatore incontra l'Idra di Lerna le cui nove teste si rigenerano una volta sconfitte in sequenza.
Il giocatore può vincere solo se utilizza la Fiamma della Gloria almeno una volta altrimenti può solo darsi alla fuga
L'utilizzo della Fiamma della Gloria viene controllato a inizio script e memorizzato in tabella sql Idra e alla vittoria sulla nona testa
se il numero di colpi di questo spell è calato random al 60% si decreta la sconfitta del mostro, altrimenti al 40% o se è rimasto uguale 
le teste si rigenerano e si ricomincia il combattimento.
*/
require_once ("common.php");
require_once ("common2.php");

/* Hugues Indebolisci
L'alito venefico dell'Idra di Lerna indebolisce sempre di più il giocatore
*/
function indebolisci() {
    global $session;
    if ($session['user']['level'] > 1) {
    	if ($session['user']['alive']==1){
    		output("`4`bL'alito venefico dell'`8Idra di Lerna`4 ti sta indebolendo!`b`n`0",true);
        	$session['user']['hitpoints'] = round($session['user']['hitpoints']*=.95);
    	}
    }	
}

/* Hugues Fuga
il giocatore tenta la fuga durante il combattimento ma non sempre riesce a fuggire
*/
function fuga() {	
	global $session;
	output("`n`n`GTerrorizzat".($session[user][sex]?"a":"o")." dall'orribile mostro che si erge dalla palude con le sue nove terribili teste tenti la fuga!`n");
    switch(e_rand(1,10)){
            case 1:
    			output("`GPer tua sfortuna L'`8Idra di Lerna`G ti immobilizza con le fauci delle sue terribili teste e sferra un colpo mortale!`n`n");
    			output("`GSei mort".($session[user][sex]?"a":"o")."!!`n");
    			output("Perdi il `^15%`G della tua esperienza e tutto l'oro che avevi con te!");
    			debuglog("perde 15% exp, e ".$session['user']['gold']." oro dall'Idra di Lerna");
    			$session['user']['alive']=false;
    			$session['user']['hitpoints']=0;
    			$session['user']['gold']=0;
    			$session['user']['experience']*=0.85;
    			$session['user']['specialinc']="";
    			addnav("Notizie Giornaliere","news.php");
    			addnews("`G" . $session['user']['name'] . "`G ha incontrato la terribile `8Idra`G di della palude di `8Lerna`G, `nha tentato di sfuggire all'orribile mostro ma non è sopravvissut".($session[user][sex]?"a":"o").".");
    		break;
            case 2:
            case 3:
            case 4:
    			output("`GIncominci a correre a rotta di collo cercando di sfuggire all'orribile mostro che sibilando furiosamente si getta al tuo inseguimento e alla fine...");
    			output("`nSei riuscit".($session[user][sex]?"a":"o")." a fuggire alla spaventosa `8Idra`G di della palude di `8Lerna`G`n");
    			output("`nAnsante per la corsa fatta e tremante di paura per il pericolo appena scampato, ti rifugi in una radura del bosco e vi trascorri gran parte della tua giornata ripensando alla spaventosa creatura appena incontrata.`n");
    			$turnlost=e_rand(1,intval($session['user']['turns']/5));
    			output("`n`G`bHai perso `6$turnlost `Gcombattimenti!!!`b`n");
    			$session['user']['turns']-=$turnlost;
    			addnews("`G".$session['user']['name']."`G dopo aver avuto un incontro ravvicinato con l'`8Idra di Lerna`G è stat".($session[user][sex]?"a":"o")." vist".($session[user][sex]?"a":"o")." tremante di paura in una radura nel bosco");
    			$session['user']['specialinc']="";
    			addnav("Continua", "forest.php");
    		break;
    		case 5:
            case 6:
            case 7:
            case 8:
            case 9:
            case 10:
            	output("`3".$badguy['creaturename']."`GCerchi di fuggire, ma i tuoi stivali sono profondamente affondati nel fango della palude e non riesci a muoverti...");
            	output("`GL'unica tua unica possibilità di sopravvivenza sembra essere quella di continuare a combattere...");
            	$session['user']['specialinc']="idra";
            break;	
    }
}

checkday();
indebolisci();

page_header("L'Idra di Lerna");
output("`c`b`&Qualcosa di Speciale`0`b`c`n`n");
$rimasti = 0;


if ($_GET['op'] == "run2"){
	fuga();
	if ( $session['user']['alive']=false ) {
	} else { if ( $session['user']['specialinc']=="" ) {
			} else { addnav("Continua","idra.php?op=fight2");
		}
	}		
}
if ($_GET['op'] == "run3"){
	fuga();
    if ( $session['user']['alive']=false ) {
	} else { if ( $session['user']['specialinc']=="" ) {
			} else { addnav("Continua","idra.php?op=fight3");
		}
	}
}
if ($_GET['op'] == "run4"){
	fuga();
    if ( $session['user']['alive']=false ) {
	} else { if ( $session['user']['specialinc']=="" ) {
			} else { addnav("Continua","idra.php?op=fight4");
		}
	}
}
if ($_GET['op'] == "run5"){
	fuga();
    if ( $session['user']['alive']=false ) {
	} else { if ( $session['user']['specialinc']=="" ) {
			} else { addnav("Continua","idra.php?op=fight5");
		}
	}
}
if ($_GET['op'] == "run6"){
	fuga();
    if ( $session['user']['alive']=false ) {
	} else { if ( $session['user']['specialinc']=="" ) {
			} else { addnav("Continua","idra.php?op=fight6");
		}
	}
}
if ($_GET['op'] == "run7"){
	fuga();
    if ( $session['user']['alive']=false ) {
	} else { if ( $session['user']['specialinc']=="" ) {
			} else { addnav("Continua","idra.php?op=fight7");
		}
	}
}
if ($_GET['op'] == "run8"){
	fuga();
    if ( $session['user']['alive']=false ) {
	} else { if ( $session['user']['specialinc']=="" ) {
			} else { addnav("Continua","idra.php?op=fight8");
		}
	}
}
if ($_GET['op'] == "run8"){
	fuga();
    if ( $session['user']['alive']=false ) {
	} else { if ( $session['user']['specialinc']=="" ) {
			} else { addnav("Continua","idra.php?op=fight8");
		}
	}
}
if ($_GET['op'] == "run9"){
	fuga();
    if ( $session['user']['alive']=false ) {
	} else { if ( $session['user']['specialinc']=="" ) {
			} else { addnav("Continua","idra.php?op=fight9");
		}
	}
}
if ($_GET['op'] == ""){
        
        if ($session['user']['level'] > 1) {
        	$totalgold = $session['user']['goldinbank'] + $session['user']['gold'];
        	output("`GLa foresta si apre su un'ampia radura interamente occupata da una maleodorante palude.`n");
        	output("Ovunque, sparsi sul suolo fangoso, corpi di cavalieri e dei loro cavalli dilaniati, fatti a pezzi e contorti in pose grottesche.`n");
        	output("Ai tuoi piedi i resti semischeletrici di un guerriero che, a terra, come uno spaventapasseri straziato, mostra al cielo le insegne del suo casato.`n");
        	output("Gli abiti dai colori vivaci macchiati di sangue rappreso e il mantello a brandelli avvolgono lo scheletro dello sfortunato i cui lineamenti trasudano tutto l'orrore che ha dovuto subire in punto di morte.`n");
        	output("L'odore della morte e della desolazione ti assale e ti prende la gola, i miasmi della putredine e della carne in decomposizione si insinuano ovunque come il terrore che ti paralizza le membra.`n");
        	output("E mentre riconosci il luogo funesto in cui sei capitato, ecco che dall'acqua limacciosa e maleodorante della palude emerge con un possente ruggito il mostro che vi dimora : l'`8Idra di Lerna`G. `n");
        	output("Enorme, con un corpo gigantesco di drago e nove teste di serpente si erge il terribile animale che ti fissa malignamente mentre dalle nove gole nuvole di alito venefico ammorbano l'aria rendendola irrespirabile.`n`n");
        	output("Cosa fare ? Darsi a una poco onorevole fuga o affrontare l'immane pericolo ?`n");         
            addnav("Fuggi","idra.php?op=loose&op2=back");
            addnav("Combatti","idra.php?op=fight1");
        }else{
            output("`GNon vedi assolutamente nulla di diverso da una sterile palude maleodorante.");
            addnav("Continua","forest.php");
        }
}elseif ($_GET['op'] == "loose"){
      
        if ($_GET['op2'] <> "back"){
            output("`GPer tua sfortuna L'`8Idra di Lerna`G riesce ad immobilizzarti con le fauci delle sue terribili teste e ti sferra un colpo mortale!`n");
    		output("`GSei Mort".($session[user][sex]?"a":"o")."! e, non appena cadi al suolo, il terribile mostro fa a pezzi il tuo corpo spargendo le tue membra dilaniate lungo le rive della palude.`n`n");
    		output("Perdi il `^15%`G della tua esperienza e tutto l'oro che avevi con te!");
    		debuglog("perde 15% exp, e ".$session['user']['gold']." oro dall'Idra di Lerna");
    		$session['user']['alive']=false;
    		$session['user']['hitpoints']=0;
    		$session['user']['gold']=0;
    		$session['user']['specialinc']="";
            $session['user']['experience']=round($session['user']['experience']*.85,0);
            addnews("`G".$session['user']['name']."`G è stat".($session['user']['sex']?"a":"o")." fatt".($session['user']['sex']?"a":"o")." a pezzi dall'`8Idra di Lerna`G, `nha combattuto eroicamente, ma non è riuscit".($session['user']['sex']?"a":"o")." a sferrare il colpo finale.");
            addnav("Notizie Giornaliere","news.php");    		
        } else {
        	$session['user']['turns']-=$turnlost;
        	output("`GPer tua fortuna L'`8Idra di Lerna`G è stata distratta da qualcosa e non ti ha inseguit".($session['user']['sex']?"a":"o").", e sollevat".($session['user']['sex']?"a":"o")." dallo scampato pericolo torni suoi passi nei più conosciuti e meno pericolosi sentieri della foresta a te tanto familiari.`n");
    		addnews("`G".$session['user']['name']."`G è giunt".($session[user][sex]?"a":"o")." sino alla palude di `8Lerna`G ma, molto coraggiosamente, `nha preferito tornare sui suoi passi onde evitare spiacevoli incontri.");
    		$session['user']['specialinc']="";
    		addnav("`@Continua", "forest.php");
    	}
        
}elseif ($_GET['op'] == "win"){
			$sql1 = "SELECT * FROM idra WHERE acctid = ".$session['user']['acctid']." " ;
			$result1 = db_query($sql1) or die(db_error(LINK));
			$row1 = db_fetch_assoc($result1);
			$colpi = $row1['colpi'];
			$rimasti = 0;
			$sql2 = "SELECT * FROM items WHERE owner = ".$session['user']['acctid']." AND class='Spell' and name = '`4Fiamma della Gloria' " ;
			$result2 = db_query($sql2) or die(db_error(LINK));
			$countrow = db_num_rows($result2);
			for ($i=0; $i < $countrow; $i++){
				$row2 = db_fetch_assoc($result2);
				$rimasti += $row2['value1'];
			}			
					
		 	if ($colpi > $rimasti ){
		 		switch(e_rand(1,10)){
            		case 1:
            		case 2:
            		case 3:
            		case 4:
            			output("`GHai sconfitto L'`8Idra di Lerna`G!");
        				addnews("`GL'`8Idra`G della palude di `8Lerna`G è stata sconfitta da `G".$session['user']['name']."`G!");
        				output("`GPensi che sia meglio abbandonare rapidamente questo luogo prima che qualche sortilegio faccia sì che l'orribile animale si riprenda.");
        				$goldgained=e_rand(6000,15000);
          				$gemsgained=e_rand(10,25);
          				$session['user']['gems']+=$gemsgained;
               			$session['user']['gold']+=$goldgained;
               			output("`GMa prima di lasciare quella terribile palude scopri che l'`8Idra di Lerna`G custodiva un tesoro che si rivela essere un ottimo bottino : `n`nhai infatti guadagnato `&$gemsgained gemme`G e `^$goldgained pezzi d'oro.`n`n");
        				addnav("Continua","forest.php"); 
        		break;
            		case 5:
            		case 6:	
            		case 7:	 	
		 				output("`GHai sconfitto L'`8Idra di Lerna`G!");
        				addnews("`GL'`8Idra`G della palude di `8Lerna`G è stata sconfitta da `G".$session['user']['name']."`G!");
        				output("`GPensi che sia meglio abbandonare rapidamente questo luogo prima che qualche sortilegio faccia sì che l'orribile animale si riprenda.");
        				$goldgained=e_rand(6000,15000);
          				$gemsgained=e_rand(10,25);
          				$session['user']['gems']+=$gemsgained;
               			$session['user']['gold']+=$goldgained;
               			output("`GMa prima di lasciare quella terribile palude scopri che l'`8Idra di Lerna`G custodiva un tesoro che si rivela essere un ottimo bottino : hai infatti guadagnato `&$gemsgained gemme`G e `^$goldgained pezzi d'oro.`n`n");
               			$identarma=array();
						$ident_arma = identifica_arma();
						$articoloarma = $ident_arma['articolo'];
    					$maledetta = $ident_arma['maledetta'];
    					$pugni = $ident_arma['pugni'];
               			$previously_upgraded   = strpos($session['user']['weapon']," +1")!==false ? true : false;
               			if ($previously_upgraded or $pugni or $maledetta){
               			}else{
           					output("`GRicordi inoltre alcune antiche leggende che narrano di quando Ercole sconfisse lo stesso mostro e di come egli intinse le sue armi nel sangue velenoso `8Idra di Lerna`G potenziandole.");
        					output("`GImiti quindi il grande eroe e meraviglia ! La tua arma è migliorata di ben `#10`G punti in `&attacco`G.");
        					$session['user']['weapon'] = $session['user']['weapon']." +10";
        					$session['user']['weapondmg'] +=10;
            				$session['user']['attack']+=10;
            				$session['user']['weaponvalue']+=10000;
            				$usura_max = intval($session['user']['weapondmg'] * max((15 + $session['user']['dragonkills']/2 - 2*$session['user']['reincarna']),10)) + 100;
            				$session['user']['usura_arma'] = $usura_max;
        				}
        				addnav("Continua","forest.php");	 
        		break;
    				case 8:
    				case 9:
    				case 10:
    					addnav("Continua","idra.php?op=fight1");
            			output("`GCon sgomento assisti ad un prodigio : dalle ferite cha hai provocato con i tuoi colpi le teste dell'`8Idra di Lerna`G si rigenerano e il mostro sempra essere più forte di prima. Con grande orrore ti ritrovi a dover affrontare nuovamente la terribile creatura, eppure non può essere invunerabile, deve esserci un modo per sconfiggerla!");          				
    			break;
    			}
    		} else {
                addnav("Continua","idra.php?op=fight1");
            	output("`GCon sgomento assisti ad un prodigio : dalle ferite cha hai provocato con i tuoi colpi le teste dell'`8Idra di Lerna`G si rigenerano e il mostro sempra essere più forte di prima. Con grande orrore ti ritrovi a dover affrontare nuovamente la terribile creatura, eppure non può essere invunerabile, deve esserci un modo per sconfiggerla!");         
         	}

}elseif ($_GET['op'] == "fight1"){
        $badguy = array(        "creaturename"=>"`@la prima testa dell'Idra di Lerna`0"
        ,"creaturelevel"=>0
        ,"creatureweapon"=>"Fauci velenose"
        ,"creatureattack"=>0
        ,"creaturedefense"=>1
        ,"creaturehealth"=>2
        ,"creaturegold"=>0
        ,"diddamage"=>0);
        $userlevel=$session['user']['level'];
        $userattack=e_rand(2,$session['user']['attack'])+2;
        $userhealth=$session['user']['hitpoints'];
        $userdefense=e_rand(2,$session['user']['defence'])+2;
        $badguy['creaturelevel']+=$userlevel;
        $badguy['creatureattack']+=$userattack;
        $badguy['creaturehealth']=intval($userhealth*0.2);
        $badguy['creaturedefense']+=$userdefense;
        $badguy['creaturegold']=0;
        $session['user']['badguy']=createstring($badguy);
        $_GET['op']="fight";
}elseif ($_GET['op'] == "fight2"){
        $badguy = array(        "creaturename"=>"`@la seconda testa dell'Idra di Lerna`0"
        ,"creaturelevel"=>1
        ,"creatureweapon"=>"Fauci velenose"
        ,"creatureattack"=>1
        ,"creaturedefense"=>2
        ,"creaturehealth"=>2
        ,"creaturegold"=>0
        ,"diddamage"=>0);
        $userlevel=$session['user']['level'];
        $userattack=e_rand(2,$session['user']['attack'])+4;
        $userhealth=$session['user']['hitpoints'];
        $userdefense=e_rand(2,$session['user']['defence'])+4;
        $badguy['creaturelevel']+=$userlevel;
        $badguy['creatureattack']+=$userattack;
        $badguy['creaturehealth']=intval($userhealth*0.4);
        $badguy['creaturedefense']+=$userdefense;
        $badguy['creaturegold']=0;
        $session['user']['badguy']=createstring($badguy);
        $_GET['op']="fight";
}elseif ($_GET['op'] == "fight3"){
        $badguy = array(        "creaturename"=>"`@la terza testa dell'Idra di Lerna`0"
        ,"creaturelevel"=>2
        ,"creatureweapon"=>"Fauci velenose"
        ,"creatureattack"=>2
        ,"creaturedefense"=>3
        ,"creaturehealth"=>2
        ,"creaturegold"=>0
        ,"diddamage"=>0);
        $userlevel=$session['user']['level'];
        $userattack=e_rand(2,$session['user']['attack'])+6;
        $userhealth=$session['user']['hitpoints'];
        $userdefense=e_rand(2,$session['user']['defence'])+6;
        $badguy['creaturelevel']+=$userlevel;
        $badguy['creatureattack']+=$userattack;
        $badguy['creaturehealth']=intval($userhealth*0.6);
        $badguy['creaturedefense']+=$userdefense;
        $badguy['creaturegold']=0;
        $session['user']['badguy']=createstring($badguy);
        $_GET['op']="fight";
}elseif ($_GET['op'] == "fight4"){
        $badguy = array(        "creaturename"=>"`@la quarta testa dell'Idra di Lerna`0"
        ,"creaturelevel"=>3
        ,"creatureweapon"=>"Fauci velenose"
        ,"creatureattack"=>3
        ,"creaturedefense"=>4
        ,"creaturehealth"=>2
        ,"creaturegold"=>0
        ,"diddamage"=>0);
        $userlevel=$session['user']['level'];
        $$userattack=e_rand(2,$session['user']['attack'])+8;
        $userhealth=$session['user']['hitpoints'];
        $userdefense=e_rand(2,$session['user']['defence'])+8;
        $badguy['creaturelevel']+=$userlevel;
        $badguy['creatureattack']+=$userattack;
        $badguy['creaturehealth']=intval($userhealth*0.8);
        $badguy['creaturedefense']+=$userdefense;
        $badguy['creaturegold']=0;
        $session['user']['badguy']=createstring($badguy);
        $_GET['op']="fight";
}elseif ($_GET['op'] == "fight5"){
        $badguy = array(        "creaturename"=>"`@la quinta testa dell'Idra di Lerna`0"
        ,"creaturelevel"=>4
        ,"creatureweapon"=>"Fauci velenose"
        ,"creatureattack"=>4
        ,"creaturedefense"=>5
        ,"creaturehealth"=>3
        ,"creaturegold"=>0
        ,"diddamage"=>0);
        $userlevel=$session['user']['level'];
        $$userattack=e_rand(2,$session['user']['attack'])+10;
        $userhealth=$session['user']['hitpoints'];
        $userdefense=e_rand(2,$session['user']['defence'])+10;
        $badguy['creaturelevel']+=$userlevel;
        $badguy['creatureattack']+=$userattack;
        $badguy['creaturehealth']=intval($userhealth*1.0);
        $badguy['creaturedefense']+=$userdefense;
        $badguy['creaturegold']=0;
        $session['user']['badguy']=createstring($badguy);
        $_GET['op']="fight";
}elseif ($_GET['op'] == "fight6"){
        $badguy = array(        "creaturename"=>"`@la sesta testa dell'Idra di Lerna`0"
        ,"creaturelevel"=>5
        ,"creatureweapon"=>"Fauci velenose"
        ,"creatureattack"=>4
        ,"creaturedefense"=>5
        ,"creaturehealth"=>3
        ,"creaturegold"=>0
        ,"diddamage"=>0);
        $userlevel=$session['user']['level'];
        $$userattack=e_rand(2,$session['user']['attack'])+12;
        $userhealth=$session['user']['hitpoints'];
        $userdefense=e_rand(2,$session['user']['defence'])+12;
        $badguy['creaturelevel']+=$userlevel;
        $badguy['creatureattack']+=$userattack;
        $badguy['creaturehealth']=intval($userhealth*1.2);
        $badguy['creaturedefense']+=$userdefense;
        $badguy['creaturegold']=0;
        $session['user']['badguy']=createstring($badguy);
        $_GET['op']="fight";
}elseif ($_GET['op'] == "fight7"){
        $badguy = array(        "creaturename"=>"`@la settima testa dell'Idra di Lerna`0"
        ,"creaturelevel"=>6
        ,"creatureweapon"=>"Fauci velenose"
        ,"creatureattack"=>4
        ,"creaturedefense"=>5
        ,"creaturehealth"=>3
        ,"creaturegold"=>0
        ,"diddamage"=>0);
        $userlevel=$session['user']['level'];
        $$userattack=e_rand(2,$session['user']['attack'])+14;
        $userhealth=$session['user']['hitpoints'];
        $userdefense=e_rand(2,$session['user']['defence'])+14;
        $badguy['creaturelevel']+=$userlevel;
        $badguy['creatureattack']+=$userattack;
        $badguy['creaturehealth']=intval($userhealth*1.4);
        $badguy['creaturedefense']+=$userdefense;
        $badguy['creaturegold']=0;
        $session['user']['badguy']=createstring($badguy);
        $_GET['op']="fight";
}elseif ($_GET['op'] == "fight8"){
        $badguy = array(        "creaturename"=>"`@l'ottava testa dell'Idra di Lerna`0"
        ,"creaturelevel"=>7
        ,"creatureweapon"=>"Fauci velenose"
        ,"creatureattack"=>4
        ,"creaturedefense"=>5
        ,"creaturehealth"=>3
        ,"creaturegold"=>0
        ,"diddamage"=>0);
        $userlevel=$session['user']['level'];
        $$userattack=e_rand(2,$session['user']['attack'])+16;
        $userhealth=$session['user']['hitpoints'];
        $userdefense=e_rand(2,$session['user']['defence'])+16;
        $badguy['creaturelevel']+=$userlevel;
        $badguy['creatureattack']+=$userattack;
        $badguy['creaturehealth']=intval($userhealth*1.6);
        $badguy['creaturedefense']+=$userdefense;
        $badguy['creaturegold']=0;
        $session['user']['badguy']=createstring($badguy);
        $_GET['op']="fight";
}elseif ($_GET['op'] == "fight9"){
        $badguy = array(        "creaturename"=>"`@l'ultima testa dell'Idra di Lerna`0"
        ,"creaturelevel"=>8
        ,"creatureweapon"=>"Fauci velenose"
        ,"creatureattack"=>4
        ,"creaturedefense"=>5
        ,"creaturehealth"=>3
        ,"creaturegold"=>0
        ,"diddamage"=>0);
        $userlevel=$session['user']['level'];
        $$userattack=e_rand(2,$session['user']['attack'])+18;
        $userhealth=$session['user']['hitpoints'];
        $userdefense=e_rand(2,$session['user']['defence'])+18;
        $badguy['creaturelevel']+=$userlevel;
        $badguy['creatureattack']+=$userattack;
        $badguy['creaturehealth']=intval($userhealth*2.0);
        $badguy['creaturedefense']+=$userdefense;
        $badguy['creaturegold']=0;
        $session['user']['badguy']=createstring($badguy);
        $_GET['op']="fight";
}

if ($_GET['op'] == "fight"){
        $battle=true;
}

if ($battle){
        include_once("battle.php");
        if ($victory){
        	switch(e_rand(1,10)){
            case 1:
    			output("`n`GCon un perfetto fendente riesci a staccare la testa dell'Idra che cade al suolo`n`n");
    		break;
            case 2:
            	output("`n`GLa tua serie precisa di colpi stacca la testa dell'Idra che cade al suolo`n`n");
            break;
            case 3:
            	output("`n`GCon un perfetto fendente decapiti la testa dell'Idra che rotola al suolo`n`n");
            break;
            case 4:
    			output("`n`GIl tuo ultimo colpo decapita la testa dell'Idra che cade nel fango della palude`n`n");
    		break;
    		case 5:
    			output("`n`GIl tuo colpo micidiale ha staccato la testa dell'Idra che cade al suolo`n`n");
    		break;
            case 6:
            	output("`n`GIl tuo perfetto colpo decapita la testa dell'Idra che cade al suolo`n`n");
            break;
            case 7:
            	output("`n`GCon un ottimo colpo stacchi la testa dell'Idra che cade al suolo`n`n");
            break;
            case 8:
            	output("`n`GIl tuo ultimo colpo recide la testa dell'Idra che cade nella palude`n`n");
            break;
            case 9:	
            	output("`n`GIl tuo ultimo magistrale colpo decapita la testa dell'Idra`n`n");
            break;
            case 10:
            	output("`n`GIl tuo ultimo colpo da vero maestro decapita la testa dell'Idra che cade al suolo`n`n");
            break;	
    }
            output("`GHai sconfitto ".$badguy['creaturename'].".`n");
            if ($badguy['creaturename']=="`@la prima testa dell'Idra di Lerna`0") {
            		addnav("Continua","idra.php?op=fight2");
            		addnav("Scappa","idra.php?op=run2");
            	}	
            	if ($badguy['creaturename']=="`@la seconda testa dell'Idra di Lerna`0") {
            		addnav("Continua","idra.php?op=fight3");
            		addnav("Scappa","idra.php?op=run3");
            	}
            	if ($badguy['creaturename']=="`@la terza testa dell'Idra di Lerna`0") {
            		addnav("Continua","idra.php?op=fight4");
            		addnav("Scappa","idra.php?op=run4");
            	}
           		if ($badguy['creaturename']=="`@la quarta testa dell'Idra di Lerna`0") {
           			addnav("Continua","idra.php?op=fight5");
            		addnav("Scappa","idra.php?op=run5");
            	}
            	if ($badguy['creaturename']=="`@la quinta testa dell'Idra di Lerna`0") {
            		addnav("Continua","idra.php?op=fight6");
            		addnav("Scappa","idra.php?op=run6");
            	}
            	if ($badguy['creaturename']=="`@la sesta testa dell'Idra di Lerna`0") {
            		addnav("Continua","idra.php?op=fight7");
            		addnav("Scappa","idra.php?op=run7");
            	}
            	if ($badguy['creaturename']=="`@la settima testa dell'Idra di Lerna`0") {
            		addnav("Continua","idra.php?op=fight8");
            		addnav("Scappa","idra.php?op=run8");
            	}
            	if ($badguy['creaturename']=="`@l'ottava testa dell'Idra di Lerna`0") {
            		addnav("Continua","idra.php?op=fight9");
            		addnav("Scappa","idra.php?op=run9");
            	}
            if ($badguy['creaturename']=="`@l'ultima testa dell'Idra di Lerna`0") addnav("Continua","idra.php?op=win");
            
            $exp= e_rand(($session['user']['level']*8), ($session['user']['level']*15));
            $session['user']['experience'] += $exp;
            debuglog("ha sconfitto ".$badguy['creaturename']." e salva il suo oro");
            output("`GGuadagni `^$exp `Gpunti esperienza dal combattimento disputato.");
            $badguy=array();
            $session['user']['badguy']="";
        }elseif ($defeat){
            $session['user']['alive']=0;
            addnav("Continua","idra.php?op=loose");
        }else{
            fightnav(true,false);
        }
}else{

}
page_footer();
?>