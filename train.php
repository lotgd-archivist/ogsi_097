<?php
require_once("common.php");
require_once("common2.php");
checkday();

/* Hugues Assegna arma
Viene assegnata un'arma al player
*/
function assegnaarma($gold,$weaponname,$weaponarray) {

	global $session ;

	$session['user']['weapon'] = $weaponname;
	$session['user']['attack'] -= $session['user']['weapondmg'];
	$session['user']['weapondmg'] = $session['user']['level']+1;
	$session['user']['attack'] += $session['user']['weapondmg'];
	$session['user']['weaponvalue'] = $gold;
	$usura_max = intval($row['damage'] * max((15 + $session['user']['dragonkills']/2 - 2*$session['user']['reincarna']),10)) + 100;
	$session['user']['usura_arma'] = $usura_max;

} 

page_header("Campo di Allenamento di Bluespring");
$session['user']['locazione'] = 184;
output("`b`cCampo di Allenamento di Bluespring`c`b`n");
$sql = "SELECT * FROM masters WHERE creaturelevel = ".$session['user']['level'];
$result = db_query($sql) or die(sql_error($sql));
if (db_num_rows($result) > 0){
    $master = db_fetch_assoc($result);
    if ($master['creaturename'] == "Gadriel il Ranger Elfico" && $session['user']['race'] == 2) {
        $master['creaturewin'] = "E tu saresti un Elfo?? Forse un Mezzo-Elfo! Torna quando ti sarai allenat".($session[user][sex]?"a":"o")." meglio.";
        $master['creaturelose'] = "Lo sapevo che solo un altro Elfo poteva superarmi. Sei proprio migliorat".($session[user][sex]?"a":"o").".";
    }
    $level = $session['user']['level'];
    //$exprequired=((pow((($level-1)/15),3)*3+1)*100*$level);
    //$exparray=array(1=>100,400,602,1012,1540,2207,3041,4085,5395,7043,9121,11740,15037,19171,24330);
    $exparray=array(1=>100,400,1002,1912,3140,4707,6641,8985,11795,15143,19121,23840,29437,36071,43930);
    $goldarray=array(1=>48,225,585,990,1575,2250,2790,3420,4230,5040,5850,6840,8010,9000,10350);
    $weaponarray=array(1=>"Spada da allenamento liv.1","Spada da allenamento liv.2","Spada da allenamento liv.3","Spada da allenamento liv.4","Spada da allenamento liv.5","Spada da allenamento liv.6","Spada da allenamento liv.7","Spada da allenamento liv.8","Spada da allenamento liv.9","Spada da allenamento liv.10","Spada da allenamento liv.11","Spada da allenamento liv.12","Spada da allenamento liv.13","Spada da allenamento liv.14","Spada da allenamento liv.15");
    
    /*
    while (list($key,$val)=each($exparray)){
        $exparray[$key]= round(
        $val + ($session['user']['dragonkills']/4) * $session['user']['level'] * 100,0);
    }
    $exprequired=$exparray[$session['user']['level']];
    */
    $exprequired = ($exparray[$session['user']['level']]
                    + (25*$session['user']['dragonkills']*$session['user']['level']));
    //output("`\$Exp Required: $exprequired; exp possessed: ".$session['user'][experience]."`0`n");

    if ($_GET['op']==""){
        output("`n`!Il suono della battaglia ti circonda. Il rumore delle armi ispira il tuo cuore di guerrier".($session[user][sex]?"a":"o").". ");
        output("`n`nIl tuo maestro è `^".$master['creaturename']."`0.");
        addnav("Interroga il Maestro","train.php?op=question");
        addnav("Sfida il Maestro","train.php?op=challenge");
        if ($session['user']['superuser'] > 2) {
            addnav("Guadagna Livello (SuperUtente)","train.php?op=challenge&victory=1");
        }
        addnav("Torna al Villaggio","village.php");
    }else if($_GET['op']=="challenge"){
    	$ident_arma=array();
		$ident_arma = identifica_arma();
		$articoloarma = $ident_arma['articolo'];
   	 	$pugni = $ident_arma['pugni'];
   	 	$identarmatura=array();
		$ident_armatura = identifica_armatura();
		$articoloarmatura = $ident_armatura['articolo'];
    	$tshirt = $ident_armatura['tshirt'];
        if ($_GET['victory']) {
            $victory=true;
            $defeat=false;
            if ($session['user']['experience'] < $exprequired)
            $session['user']['experience'] = $exprequired;
            $session['user']['seenmaster'] = 0;
        }
        if ($session['user']['seenmaster']){
            output("`n`!Pensi che, forse, hai visto abbastanza del tuo maestro per oggi. `nLe lezioni che hai appreso ti impediscono di esporti volontariamente ");
            output("ad una simile umiliazione un'altra volta.");
            addnav("Torna al Villaggio","village.php");
        }else{
            if (getsetting("multimaster",1)==0) $session['user']['seenmaster'] = 1;
            
            if ($session['user']['experience']>=$exprequired){
            
				if ($session['user']['level']+1 <= $session['user']['weapondmg']){
				
				}else{
                	output("`n`@`iCome pensi di affrontare un maestro del mio livello senza avere un'arma adeguata ?`i`! `nTi rimprovera `^".$master['creaturename']." ");
                	output("`!mentre tutti gli allievi presenti soffocano delle risatine nei tuoi confronti e ti indicano scuotendo il capo con disapprovazione.`n");
                	$weaponname = $weaponarray[$session['user']['level']+1];
                	$gold = $goldarray[$session['user']['level']+1];
                	$gemme = intval( $gold / 2000 ) ;
                	if ($gemme == 0) $gemme = 1 ;
            		if ($session['user']['gold'] >= $gold){
            			$session['user']['gold'] -= $gold;
            			assegnaarma ($gold,$weaponname,$weaponarray);
            			debuglog("Gli vengono addebitati $gold oro per $weaponname arma");
            			output("Ma nonostante la tua trascuratezza `^".$master['creaturename']."`! ha deciso di offrirti comunque la possibilità di affrontarlo.`n");
            			output("Dall'armeria del campo viene quindi portata una `#".$weaponname."`! che ti viene consegnata da uno scudiero il quale preleva direttamente il costo dell'arma dal tuo portamonete. ");
                	}else{	if ($session['user']['goldinbank'] >= $gold){ 
            					$session['user']['goldinbank'] -= $gold;
            					assegnaarma ($gold,$weaponname,$weaponarray);
            					debuglog("Gli vengono addebitati $gold oro dal conto in banca per $weaponname arma");
            					output("Ma nonostante la tua trascuratezza `^".$master['creaturename']."`! ha deciso di offrirti comunque la possibilità di affrontarlo.`n");
            					output("Dall'armeria del campo viene quindi portata una `#".$weaponname."`! che ti viene consegnata da uno scudiero il quale ti comunica che il costo dell'arma è stato prelevato direttamente dal tuo conto in banca. ");
                			}else{	if ( ($session['user']['gold'] + $session['user']['goldinbank']) >= $gold){ 
                						$restante = $gold - $session['user']['gold'];
                						$session['user']['gold'] = 0 ;
                						$session['user']['goldinbank'] -= $restante;
            							assegnaarma ($gold,$weaponname,$weaponarray);
            							debuglog("Gli vengono addebitati $gold oro dal borsellino e da conto in banca per $weaponname arma");
            							output("Ma nonostante la tua trascuratezza `^".$master['creaturename']."`! ha deciso di offrirti comunque la possibilità di affrontarlo.`n");
            							output("Dall'armeria del campo viene quindi portata una `#".$weaponname."`! che ti viene consegnata da uno scudiero il quale, oltre a prenderti il tuo portamonete, ti comunica che il residuo del costo dell'arma è stato prelevato direttamente dal tuo conto in banca. ");
                					}else{	if ($session['user']['gems'] >= $gemme){
               									$session['user']['gems'] -= $gemme ; 
               									assegnaarma ($gold,$weaponname,$weaponarray);
                								debuglog("Gli vengono addebitate $gemme gemme per $weaponname arma");
                								output("Ma nonostante la tua trascuratezza `^".$master['creaturename']."`! ha deciso di offrirti comunque la possibilità di affrontarlo.`n");
            									output("Dall'armeria del campo viene quindi portata una `#".$weaponname."`! che ti viene consegnata da uno scudiero il quale ti preleva`& $gemme gemme`! come pagamento dell'arma. ");
                							}else{ 	if ($session['user']['level'] == 1){
                										assegnaarma ($gold,$weaponname,$weaponarray);
                										output("Ma nonostante la tua trascuratezza `^".$master['creaturename']."`! ha deciso di offrirti comunque la possibilità di affrontarlo.`n");
                										output("Dall'armeria del campo viene quindi portata una `#".$weaponname."`! che ti viene consegnata da uno scudiero il quale ti comunica che, dato il tuo misero stato, ti viene regalata personalmente dal maestro. ");
                									}else{	if ($session['user']['gems'] == 0) {
                												output("Riprendi umilmente il tuo `#".$session['user']['weapon']."`!, ed esci dal campo di allenamento con la coda tra le gambe seguito dal suono di risate soffocate degli allievi che ridono del tuo stato miserabile.");
                												$session['user']['seenmaster']=1;
                												debuglog("perde con il maestro ".$master[creaturename]." senza affrontarlo perchè non può permettersi una arma adeguata.");
            													$noarma=true;
                											}else{	assegnaarma ($gold,$weaponname,$weaponarray);           						
                													debuglog("Gli vengono tolte ".$session['user']['gems']." gemme restanti per $weaponname arma");
                													output("Ma nonostante la tua trascuratezza `^".$master['creaturename']."`! ha deciso di offrirti comunque la possibilità di affrontarlo.`n");
            														output("Dall'armeria del campo viene quindi portata una `#".$weaponname."`! che ti viene consegnata da uno scudiero il quale ti toglie tutte le tue `&gemme`! come pagamento dell'arma che ti è stata generosamente data. ");
                													$session['user']['gems'] = 0 ;
                											}		
                			 						}
                							}
                					}		
                			}
            		}
            		
            	}
	
				if ($noarma) {
					addnav("Torna al Villaggio.","village.php");
				}else{
                if ($session['user']['reincarna'] > 0 OR ($session['user']['bonusattack']>4 OR $session['user']['bonusdef']>4)){
                    //output("Routine Modificata`n");
                    $reinca = 0;
                    $reincaatt = 0;
                    $reincadef = 0;
                    //if ($session['user']['reincarna']>0) $reinca=round(($session['user']['reincarna']/2)*18);
                    if ($session['user']['reincarna']>0) $reinca=round(($session['user']['reincarna']/2)*27);
                    if ($reinca < 18) $reinca = 18;
                    if ($session['user']['bonusattack'] > 0 AND $session['user']['dragonkills'] > 2){
                        if ($session['user']['bonusattack'] > $reinca) {
                            $reincaatt = $reinca+1;
                        } else $reincaatt = ($session['user']['bonusattack'] / 2) + 1;
                    }
                    if ($session['user']['bonusdefence'] > 0 AND $session['user']['dragonkills'] > 2){
                        if ($session['user']['bonusdefence'] > $reinca) {
                            $reincadef = $reinca+1;
                        } else {
                            $reincadef = ($session['user']['bonusdefence'] / 2) + 1;
                        }
                    }
                    $atkflux = e_rand(0,($session['user']['dragonkills']+$reinca)) + e_rand($reincaatt,($session['user']['bonusattack']-3));
                    $defflux = e_rand(0,($session['user']['dragonkills']-$atkflux + $reinca)) + e_rand($reincadef,($session['user']['bonusdefence']-2));
                    $hpflux = (($session['user']['dragonkills'] - ($atkflux+$defflux)) * 5) + e_rand(0,($reinca*$session['user']['maxhitpoints']/5));
                    if ($session['user']['level']>=12){
                        $atkflux -= 2;
                        $defflux -= 2;
                        //aiuto nel caso si sia stati sconfitti dal maestro le volte precedenti
                        $hpflux = round($hpflux*0.9);
                    }
                    $hpflux -= ($session['user']['helpmaster']*2);
                    $atkflux -= ($session['user']['helpmaster']*3);
                    $defflux -= ($session['user']['helpmaster']*3);
                    /*if ($hpflux > ($session['user']['maxhitpoints'] * 1.4)) {
                    $hpflux = round($session['user']['maxhitpoints'] * 1.4);
                    }
                    if ($session['user']['reincarna'] != 0 AND $hpflux < ($session['user']['maxhitpoints'] / 1.5)) $hpflux = round($session['user']['maxhitpoints'] / 1.5);
                    */
                }else {
                    //output("Routine Normale`n");
                    $atkflux = e_rand(0,$session['user']['dragonkills']);
                    $defflux = e_rand(0,($session['user']['dragonkills']-$atkflux));
                    $hpflux = ($session['user']['dragonkills'] - ($atkflux+$defflux)) * 5;
                }
                $master['creatureattack']+=$atkflux;
                $master['creaturedefense']+=$defflux;
                $master['creaturehealth']+=$hpflux;
                if ($session['user']['superuser'] >=2) {
                    output("`b`#Attacco: ".$master['creatureattack']." Difesa: ".$master['creaturedefense']." HP: ".$master['creaturehealth']."`b`n`n");
                }
                if ($master['creaturehealth'] > ($session['user']['maxhitpoints'] * 1.4)) {
                    $master['creaturehealth'] = round($session['user']['maxhitpoints'] * 1.4);
                }
                if ($master['creaturehealth'] < ($session['user']['maxhitpoints'] / 1.5)) {
                    $master['creaturehealth'] = ($session['user']['maxhitpoints'] / 1.5);
                }
                $master['creaturehealth'] = round ($master['creaturehealth']);
                $session['user']['badguy']=createstring($master);
                $battle=true;
                if ($victory) {
                    $badguy = createarray($session['user']['badguy']);
                    //output("Con un turbinio di colpi abbatti il tuo Maestro.`n");
                }
                }
            }else{
            	if ($pugni AND $tshirt) {
            		output("`n`!A mani nude e indossando solo $articoloarmatura `#".$session['user']['armor']."`! ti avvicini al gran maestro `^".$master['creaturename']."`!.`nUna piccola folla di spettatori ");
	                output("si è raccolta, e noti di sfuggita i sorrisi sulle loro facce, ma ti senti fiducios".($session[user][sex]?"a":"o").". Ti inchini di fronte a `^".$master['creaturename']."`!, ed esegui ");
	                output("un perfetto attacco mulinando vorticosamente $articoloarma `#".$session['user']['weapon']."`!, solo per scoprire che `^".$master['creaturename']."`!, in un lampo ha evitato tutti i tuoi colpi, ti è piombato alle spalle e ti ha dato uno scappellotto sulla nuca.  ");
	                output("Umiliat".($session[user][sex]?"a":"o")." comprendi di non essere ancora pront".($session[user][sex]?"a":"o")." per affrontare il grande maestro ed esci dal campo di allenamento seguito dal suono di risate soffocate.");

            	}else{
            		if ($pugni) {
            			output("`n`!A mani nude e indossando $articoloarmatura `#".$session['user']['armor']."`! ti avvicini al gran maestro `^".$master['creaturename']."`!.`nUna piccola folla di spettatori ");
						output("si è raccolta, e noti di sfuggita i sorrisi sulle loro facce, ma ti senti fiducios".($session[user][sex]?"a":"o").". Ti inchini di fronte a `^".$master['creaturename']."`!, ed esegui ");
	                	output("un perfetto attacco mulinando vorticosamente $articoloarma `#".$session['user']['weapon']."`!, solo per scoprire che `^".$master['creaturename']."`!, in un lampo ha evitato tutti i tuoi colpi, ti è piombato alle spalle e ti ha dato uno scappellotto sulla nuca.  ");
	                	output("Umiliat".($session[user][sex]?"a":"o")." comprendi di non essere ancora pront".($session[user][sex]?"a":"o")." per affrontare il grande maestro ed esci dal campo di allenamento seguito dal suono di risate soffocate.");
                	}else{
                		output("`n`!Con un gesto di rara eleganza sfoderi $articoloarma `#".$session['user']['weapon']." `!e indossando $articoloarmatura `#".$session['user']['armor']."`! ti avvicini al gran maestro `^".$master['creaturename']."`!.`nUna piccola folla di spettatori ");
						output("si è raccolta, e noti di sfuggita i sorrisi sulle loro facce, ma ti senti fiducios".($session[user][sex]?"a":"o").". Ti inchini di fronte a `^".$master['creaturename']."`!, ed esegui ");
	                	output("un perfetto attacco roteante, solo per scoprire che `^".$master['creaturename']."`!, in un lampo ha evitato tutti i tuoi colpi, ti ha disarmato e a braccia conserte ti sta osservando perfettamente calmo e immobile.  ");
	                	output("Umiliat".($session[user][sex]?"a":"o")." riprendi $articoloarma `#".$session['user']['weapon']."`! con la consapevolezza di non essere ancora pront".($session[user][sex]?"a":"o")." per affrontare il grande maestro ed esci mestamente dal campo di allenamento seguito dal suono di risate soffocate.");

                	}
                }
                addnav("Torna al Villaggio","village.php");
                $session['user']['seenmaster']=1;
                debuglog("perde con il maestro ".$master[creaturename]." affrontandolo senza avere abbastanza esperienza");
            }
			
        }
    }else if($_GET['op']=="question"){
        output("`n`!Ti avvicini timidamente a `^".$master['creaturename']."`! e gli domandi come stai andando nel suo corso.");
        if($session['user']['experience']>=$exprequired){
            output("`n`n`^".$master['creaturename']."`3 dice : \"`iMi congratulo con te ".($session[user][sex]?"mia giovane allieva":"mio giovane allievo")." hai completato il tuo addestrament. Ora non ho veramente più nulla da insegnarti.
            	`nSappi però che per passare al livello successivo dovrai sfidarmi e battermi in un leale duello. Sei pront".($session[user][sex]?"a":"o")." a sostenere l'esame finale? `i\"");
            	if ($session['user']['level']+1 <= $session['user']['weapondmg']){
				
				}else{
            		output("`n`i`3Ah, dimenticavo, per poter sostenre un leale combattimento in condizioni di totale parità con me avrai bisogno di un'arma di livello uguale o superiore al mio. `nDal momento che non la vedo, ti consiglio di passare dalla bottega di `!MightyE`3 e di procurartene una!`i");
            	}	 
        }else{
            output("`n`n`^".$master['creaturename']."`! afferma che ti servono altri `%".($exprequired-$session['user']['experience'])."
            `! punti di esperienza prima di essere pront".($session[user][sex]?"a":"o")." a sfidarlo in battaglia.");
        }
        addnav("Interroga il Maestro","train.php?op=question");
        addnav("Sfida il Maestro","train.php?op=challenge");
        if ($session['user']['superuser'] > 2) {
            addnav("Guadagna Livello (SuperUtente)","train.php?op=challenge&victory=1");
        }
        addnav("Torna al Villaggio","village.php");
    }else if($_GET['op']=="autochallenge"){
        addnav("Combatti il Maestro","train.php?op=challenge");
        output("`n`!Alle orecchie di `^".$master['creaturename']."`! sono arrivate tutte le tue prodezze da guerrier".($session[user][sex]?"a":"o").", e addirittura qualcuno gli ha riferito di averti sentito dire che
        pensi di essere tanto più potente di lui e di non avere nemmeno bisogno di sfidarlo in duello e batterlo per mostrare tutta la tua forza.
        Il suo ego è comprensibilmente ferito, e perciò è venuto da te a cercarti. `n `^".$master['creaturename']."`! esige
        una sfida immediata con te, ed il tuo stesso orgoglio ti impedisce di rifiutare la richiesta.");
        if ($session['user']['hitpoints']<$session['user']['maxhitpoints']){
            output("`n`n`!Essendo una persona leale, il tuo maestro ti offre una pozione guaritrice per ripristinare tutte le tue forze prima di iniziare la battaglia.");
            $session['user']['hitpoints']=$session['user']['maxhitpoints'];
        }
        addnews("`3".$session['user']['name']."`3 è stat".($session[user][sex]?"a":"o")." inseguit".($session[user][sex]?"a":"o")." dal suo maestro `^".$master['creaturename']."`3 per
        aver bigiato la scuola.");
        debuglog("è stat".($session[user][sex]?"a":"o")." inseguit".($session[user][sex]?"a":"o")." dal suo maestro ".$master['creaturename']." per aver bigiato la scuola.");
    }
    if ($_GET['op']=="fight"){
        $battle=true;
    }
    if ($_GET['op']=="run"){
        output("`n`&Il tuo codice d'onore ti impedisce di scappare da questo scontro!`n`!");
        $_GET['op']="fight";
        $battle=true;
    }

    if($battle){
        if (count($session['bufflist'])>0 && is_array($session['bufflist']) || $_GET['skill']!=""){
            $_GET['skill']="";
            if ($_GET['skill']=="") $session['user']['buffbackup']=serialize($session['bufflist']);
            $session['bufflist']=array();
            output("`n`&Il tuo codice d'onore ti impedisce di usare qualsiasi abilità speciale in questo scontro!`!");
        }
        if (!$victory) include("battle.php");
        if ($victory){
            //$badguy[creaturegold]=e_rand(0,$badguy[creaturegold]);
            $session['user']['helpmaster'] = 0;
            $search=array(  "%s",
            "%o",
            "%p",
            "%X",
            "%x",
            "%w",
            "%W"
            );
            $replace=array( ($session['user']['sex']?"her":"him"),
            ($session['user']['sex']?"she":"he"),
            ($session['user']['sex']?"her":"his"),
            ($session['user']['weapon']),
            $badguy['creatureweapon'],
            $badguy['creaturename'],
            $session['user']['name']
            );
            $badguy['creaturelose']=str_replace($search,$replace,$badguy['creaturelose']);

            output("`n`b`0".$badguy['creaturelose']."`0`b`n");
            output("`n`b`\$Hai sconfitto `^".$badguy['creaturename']."!`!`b`n");

            $session['user']['level']++;
            $session['user']['maxhitpoints']+=10;
            $session['user']['soulpoints']+=5;
            $session['user']['attack']++;
            $session['user']['defence']++;
            $session['user']['seenmaster']=0;
            output("`#Avanzi al livello `^".$session['user']['level']."`#!`n");
            output("I tuoi massimi HitPoints adesso sono `^".$session['user']['maxhitpoints']."`#!`n");
            output("Guadagni un punto di Attacco!`n");
            output("Guadagni un punto di Difesa!`n");
            $livello = $session['user']['level'];
            if ($session['user']['level']<15){
                output("Ora hai un nuovo maestro.`n");
            }else{
                output("Nessuno nel villaggio è più potente di te!`n");
            }
            if ($session['user']['referer']>0 && $session['user']['level']>=15 && $session['user']['refererawarded']<1){
                $sql="SELECT lastip,uniqueid,emailaddress FROM accounts WHERE acctid={$session['user']['referer']}";
                $result=db_query($sql);
                $ipl = db_fetch_assoc($result);
                if($session['user']['lastip']!=$ipl['lastip']
                AND $session['user']['uniqueid']!=$ipl['uniqueid']
                AND $session['user']['emailaddress']!=$ipl['emailaddress']){
                    $sql = "UPDATE accounts SET donation=donation+10 WHERE acctid={$session['user']['referer']}";
                    db_query($sql);
                    $session['user']['refererawarded']=1;
                    systemmail($session['user']['referer'],"`%Uno dei tuoi riferimenti è avanzato!`0","`%".$session['user']['name']."
                `# è avanzato al livello `^".$session['user']['level']."`#, e quindi hai guadagnato `^10`# punti!");
                }else{
                    systemmail($session['user']['referer'],"`%Uno dei tuoi riferimenti è avanzato!`0","`%".$session['user']['name']."
                `# è avanzato al livello `^".$session['user']['level']."`#, avresti guadagnato `^10`# punti! Ma purtroppo questo personaggio proviene dal tuo stesso indirizzo quindi non viene ritenuto valido!");
                }
            }
            increment_specialty();
            if($session['user']['specialty'] == 13 AND (($livello/2)==intval($livello/2))) {
                $session['user']['maxhitpoints']++;
                output("`n`2Grazie alla tua `6Rabbia Barbara`2, sei molto ardit".($session[user][sex]?"a":"o")." e vieni premiato con un HitPoint extra!`n`n");
            }
            //Modifica PvP Online
            $sql="SELECT acctid2,turn FROM pvp WHERE acctid1=".$session['user']['acctid']." OR acctid2=".$session['user']['acctid']."";
            $result = db_query($sql) or die(db_error(LINK));
            $row = db_fetch_assoc($result);
            if($row['acctid2']==$session['user']['acctid'] && $row['turn']==0){
                output("`n`6`bNon puoi più accettare la sfida nell'arena ora.`b");
                $sql = "DELETE FROM pvp WHERE acctid2=".$session['user']['acctid']." AND turn=0";
                db_query($sql) or die(db_error(LINK));
            }
            //Fine modifica PvP Online
            addnav("Interroga il Maestro","train.php?op=question");
            addnav("Sfida il Maestro","train.php?op=challenge");
            if ($session['user']['superuser'] > 2) {
                addnav("Guadagna Livello (SuperUtente)","train.php?op=challenge&victory=1");
            }
            addnav("Torna al Villaggio","village.php");
            addnews("`%".$session['user']['name']."`3 ha sconfitto il suo maestro, `^".$badguy['creaturename']."`3 per avanzare
            al livello `^".$session['user']['level']."`3 nel suo `^".ordinal($session['user']['age'])."`3 giorno!!");
            debuglog("ha sconfitto il maestro ".$badguy['creaturename']." ed avanza al livello ".$session['user']['level']);
            $badguy=array();
            $session['user']['hitpoints'] = $session['user']['maxhitpoints'];
            //$session['user'][seenmaster]=1;
        }else{
            if($defeat){
                //addnav("Daily news","news.php");
                $session['user']['helpmaster'] += 1;
                
                msgscherno($badguy);

                debuglog("ha sfidato il maestro `^".$badguy['creaturename']." ed ha perso");
                $session['user']['hitpoints']=$session['user']['maxhitpoints'];
                output("`&`bSei stat".($session[user][sex]?"a":"o")." sconfitt".($session[user][sex]?"a":"o")." da `%".$badguy['creaturename']."`&!`b`n");
                output("`%".$badguy['creaturename']."`\$ si ferma un attimo prima di darti il colpo finale, e invece
                allunga una mano per aiutarti ad alzarti, e ti regala una pozione guaritrice.`n");
                $search=array(  "%s",
                "%o",
                "%p",
                "%x",
                "%X",
                "%W",
                "%w"
                );
                $replace=array( ($session['user']['gender']?"him":"her"),
                ($session['user']['gender']?"he":"she"),
                ($session['user']['gender']?"his":"her"),
                ($session['user']['weapon']),
                $badguy['creatureweapon'],
                $badguy['creaturename'],
                $session['user']['name']
                );
                $badguy['creaturewin']=str_replace($search,$replace,$badguy['creaturewin']);
                output("`^`b".$badguy['creaturewin']."`b`0`n");
                addnav("Interroga il Maestro","train.php?op=question");
                addnav("Sfida il Maestro","train.php?op=challenge");
                if ($session['user']['superuser'] > 2) {
                    addnav("Guadagna Livello (SuperUtente)","train.php?op=challenge&victory=1");
                }
                addnav("Torna al Villaggio","village.php");
                $session['user']['seenmaster']=1;
            }else{
                fightnav(false,false);
            }
        }
    }
}else{
    output("`8Vai sul campo di battaglia. I guerrieri più giovani si riuniscono in gruppo e ti indicano mentre passi.  ");
    output("Conosci bene questo posto. Pegasus ti saluta e altrettanto fa MightyE. Non c'è rimasto nulla ");
    output("per te qui a parte i ricordi. Resti ancora un momento, a guardare gli altri guerrieri che si allenano, ");
    output("prima di voltarti per tornare al villaggio.");
    addnav("Torna al Villaggio","village.php");
}
page_footer();
?>