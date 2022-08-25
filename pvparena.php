<?php
require_once("common.php");
require_once("common2.php");
page_header("L'Arena!");
$session['user']['locazione'] = 162;

function stats($row){  // Shows player stats
    global $session;
    output("<table align='center' border='0' cellpadding='2' cellspacing='0' class='vitalinfo'><tr><td class='charhead' colspan='4'>`^`bInfo Vitali`b per lo scontro</td><tr><td class='charinfo'>`&Livello: `^`b",true);
    if ($row[bufflist1]) $row[bufflist1]=unserialize($row[bufflist1]);
    if ($row[bufflist2]) $row[bufflist2]=unserialize($row[bufflist2]);
    if ($row[bufflist1]) reset($row[bufflist1]);
    if ($row[bufflist2]) reset($row[bufflist2]);
    if ($row[acctid1]==$session[user][acctid]){
        $atk=$row[att1];
        $def=$row[def1];
        while (list($key,$val)=each($row[bufflist1])){
            //output(" $val[name]/$val[badguyatkmod]/$val[atkmod]/$val[badguydefmod]/$val[defmod]`n");
            $buffs.=appoencode("`#$val[name] `7($val[rounds] round rimanenti)`n",true);
            if (isset($val[atkmod])) $atk *= $val[atkmod];
            if (isset($val[defmod])) $def *= $val[defmod];
        }
        if ($row[bufflist2]){
            while (list($key,$val)=each($row[bufflist2])){
                //output(" $val[name]/$val[badguyatkmod]/$val[atkmod]/$val[badguydefmod]/$val[defmod]`n");
                if (isset($val[badguyatkmod])) $atk *= $val[badguyatkmod];
                if (isset($val[badguydefmod])) $def *= $val[badguydefmod];
            }
        }
        $atk = round($atk, 2);
        $def = round($def, 2);
        $atk = ($atk == $row[att1] ? "`^" : ($atk > $row[att1] ? "`@" : "`$")) . "`b$atk`b`0";
        $def = ($def == $row[def1] ? "`^" : ($def > $row[def1] ? "`@" : "`$")) . "`b$def`b`0";
        if (count($row[bufflist1])==0){
            $buffs.=appoencode("`^nessuno`0",true);
        }
        output("$row[lvl1]`b</td><td class='charinfo'>`&Hitpoints: `^$row[hp1]/`0$row[maxhp1]</td><td class='charinfo'>`&Attacco: `^`b$atk`b</td><td class='charinfo'>`&Difesa: `^`b$def`b</td>",true);
        output("</tr><tr><td class='charinfo' colspan='2'>`&Arma: `^$row[weapon1]</td><td class='charinfo' colspan='2'>`&Armatura: `^$row[armor1]</td>",true);
        output("</tr><tr><td colspan='4' class='charinfo'>`&Buffs:`n$buffs",true);
        output("</td>",true);
    }
    if ($row[acctid2]==$session[user][acctid]){
        $atk=$row[att2];
        $def=$row[def2];
        while (list($key,$val)=each($row[bufflist2])){
            $buffs.=appoencode("`#$val[name] `7($val[rounds] round rimanenti)`n",true);
            if (isset($val[atkmod])) $atk *= $val[atkmod];
            if (isset($val[defmod])) $def *= $val[defmod];
        }
        if ($row[bufflist1]){
            while (list($key,$val)=each($row[bufflist1])){
                if (isset($val[badguyatkmod])) $atk *= $val[badguyatkmod];
                if (isset($val[badguydefmod])) $def *= $val[badguydefmod];
            }
        }
        $atk = round($atk, 2);
        $def = round($def, 2);
        $atk = ($atk == $row[att2] ? "`^" : ($atk > $row[att2] ? "`@" : "`$")) . "`b$atk`b`0";
        $def = ($def == $row[def2] ? "`^" : ($def > $row[def2] ? "`@" : "`$")) . "`b$def`b`0";
        if (count($row[bufflist2])==0){
            $buffs.=appoencode("`^nessuno`0",true);
        }
        output("$row[lvl2]`b</td><td class='charinfo'>`&Hitpoints: `^$row[hp2]/`0$row[maxhp2]</td><td class='charinfo'>`&Attacco: `^`b$atk`b</td><td class='charinfo'>`&Difesa: `^`b$def`b</td>",true);
        output("</tr><tr><td class='charinfo' colspan='2'>`&Arma: `^$row[weapon2]</td><td class='charinfo' colspan='2'>`&Armatura: `^$row[armor2]</td>",true);
        output("</tr><tr><td class='charinfo' colspan='4'>`&Buffs:`n$buffs",true);
        output("</td>",true);
    }
    output("</tr></table>`n",true);
    if ($row[bufflist1]) $row[bufflist1]=serialize($row[bufflist1]);
    if ($row[bufflist2]) $row[bufflist2]=serialize($row[bufflist2]);
}
function arenanav($row){ // Navigation during fight
    if ($row[turn]==1){
        $badguy = array("acctid"=>$row[acctid2],"name"=>$row[name2],"level"=>$row[lvl2],"hitpoints"=>$row[hp2],"attack"=>$row[att2],"defense"=>$row[def2],"weapon"=>$row[weapon2],"armor"=>$row[armor2],"bufflist"=>$row[bufflist2]);
        $goodguy = array("name"=>$row[name1],"level"=>$row[lvl1],"hitpoints"=>$row[hp1],"maxhitpoints"=>$row[maxhp1],"attack"=>$row[att1],"defense"=>$row[def1],"weapon"=>$row[weapon1],"armor"=>$row[armor1],"darkartuses"=>$row[darkartuses1],"magicuses"=>$row[magicuses1],"thieveryuses"=>$row[thieveryuses1],"militareuses"=>$row[militareuses1],"mysticuses"=>$row[mysticuses1],"tacticuses"=>$row[tacticuses1],"rockskinuses"=>$row[rockskinuses1],"rhetoricuses"=>$row[rhetoricuses1],"muscleuses"=>$row[muscleuses1],"natureuses"=>$row[natureuses1],"weatheruses"=>$row[weatheruses1],"elementaleuses"=>$row[elementaleuses1],"barbarouses"=>$row[barbarouses1],"bardouses"=>$row[bardouses1],"bufflist"=>$row[bufflist1]);
    }
    if ($row[turn]==2){
        $badguy = array("acctid"=>$row[acctid1],"name"=>$row[name1],"level"=>$row[lvl1],"hitpoints"=>$row[hp1],"attack"=>$row[att1],"defense"=>$row[def1],"weapon"=>$row[weapon1],"armor"=>$row[armor1],"bufflist"=>$row[bufflist1]);
        $goodguy = array("name"=>$row[name2],"level"=>$row[lvl2],"hitpoints"=>$row[hp2],"maxhitpoints"=>$row[maxhp2],"attack"=>$row[att2],"defense"=>$row[def2],"weapon"=>$row[weapon2],"armor"=>$row[armor2],"darkartuses"=>$row[darkartuses2],"magicuses"=>$row[magicuses2],"thieveryuses"=>$row[thieveryuses2],"militareuses"=>$row[militareuses2],"mysticuses"=>$row[mysticuses2],"tacticuses"=>$row[tacticuses2],"rockskinuses"=>$row[rockskinuses2],"rhetoricuses"=>$row[rhetoricuses2],"muscleuses"=>$row[muscleuses2],"natureuses"=>$row[natureuses2],"weatheruses"=>$row[weatheruses2],"elementaleuses"=>$row[elementaleuses2],"barbarouses"=>$row[barbarouses2],"bardouses"=>$row[bardouses2],"bufflist"=>$row[bufflist2]);
    }
    if ($goodguy[hitpoints]>0 && $badguy[hitpoints]>0) {
        output ("`\$`c`b~ ~ ~ Combattimento ~ ~ ~`b`c`0");
        output("`@Incontri `^$badguy[name]`@ che ti attacca con `%$badguy[weapon]`@");
        // Let's display what buffs the opponent is using - oh yeah
        $buffs="";
        $disp[bufflist]=unserialize($badguy[bufflist]);
        reset($disp[bufflist]);
        while (list($key,$val)=each($disp[bufflist])){
            $buffs.=" `@e `#$val[name] `7($val[rounds] round)";
        }
        if (count($disp[bufflist])==0){
            $buffs.=appoencode("",true);
        }
        output("$buffs");
        output(" `@!`0`n`n");
        output("`2Livello: `6$badguy[level]`0`n");
        output("`2`bRisultato dell'ultimo round:`b`n");
        output("`2HitPoints di `2$badguy[name]: `6$badguy[hitpoints]`0`n");
        output("`2TUOI Hitpoints: `6$goodguy[hitpoints]`0`n");
        output("$row[lastmsg]");
    }
    addnav("Combattimento");
    addnav("Combatti","pvparena.php?op=fight&act=fight");
    addnav("`bAbilità Speciali`b");
    if ($goodguy[darkartuses]>0) {
        addnav("`\$Arti Oscure`0", "");
        addnav("S?`\$&#149; C`\$iurma di Scheletri`7 (1/".$goodguy[darkartuses].")`0","pvparena.php?op=fight&skill=DA&l=1",true);
    }
    if ($goodguy[darkartuses]>1)
        addnav("V?`\$&#149; V`\$oodoo`7 (2/".$goodguy[darkartuses].")`0","pvparena.php?op=fight&skill=DA&l=2",true);
    if ($goodguy[darkartuses]>2)
        addnav("M?`\$&#149; M`\$aledizione`7 (3/".$goodguy[darkartuses].")`0","pvparena.php?op=fight&skill=DA&l=3",true);
    if ($goodguy[darkartuses]>4)
        addnav("A?`\$&#149; A`\$vvizzisci Anima`7 (5/".$goodguy[darkartuses].")`0","pvparena.php?op=fight&skill=DA&l=5",true);
    if ($goodguy[thieveryuses]>0) {
        addnav("`^Furto`0","");
        addnav("I?`^&#149; I`^nsulto`7 (1/".$goodguy[thieveryuses].")`0","pvparena.php?op=fight&skill=TS&l=1",true);
    }
    if ($goodguy[thieveryuses]>1)
        addnav("L?`^&#149; L`^ama Avvelenata`7 (2/".$goodguy[thieveryuses].")`0","pvparena.php?op=fight&skill=TS&l=2",true);
    if ($goodguy[thieveryuses]>2)
        addnav("A?`^&#149; A`^ttacco Nascosto`7 (3/".$goodguy[thieveryuses].")`0","pvparena.php?op=fight&skill=TS&l=3",true);
    if ($goodguy[thieveryuses]>4)
        addnav("P?`^&#149; P`^ugnalata alle Spalle`7 (5/".$goodguy[thieveryuses].")`0","pvparena.php?op=fight&skill=TS&l=5",true);
    if ($goodguy[magicuses]>0) {
        addnav("`%Poteri Mistici`0","");
        //disagree with making this 'n', players shouldn't have their behavior dictated by convenience of god mode, hehe
        addnav("R?`%&#149; R`%igenerazione`7 (1/".$goodguy[magicuses].")`0","pvparena.php?op=fight&skill=MP&l=1",true);
    }
    if ($goodguy[magicuses]>1)
        addnav("P?`%&#149; P`%ugno di Terra`7 (2/".$goodguy[magicuses].")`0","pvparena.php?op=fight&skill=MP&l=2",true);
    if ($goodguy[magicuses]>2)
        addnav("L?`%&#149; D`%rena Vita`7 (3/".$goodguy[magicuses].")`0","pvparena.php?op=fight&skill=MP&l=3",true);
    if ($goodguy[magicuses]>4)
        addnav("A?`%&#149; A`%ura di Fulmini`7 (5/".$goodguy[magicuses].")`0","pvparena.php?op=fight&skill=MP&l=5",true);

    if ($goodguy[militareuses]>0) {
            addnav("`3Militare`0", "");
            addnav("M?`3&#149; C`3olpo Multiplo`7 (1/".$goodguy[militareuses].")`0","pvparena.php?op=fight&skill=CM&l=1",true);
        }
        if ($goodguy[militareuses]>1)
            addnav("o?`3&#149; C`3olpo Mirato`7 (2/".$goodguy[militareuses].")`0","pvparena.php?op=fight&skill=CM&l=2",true);
        if ($goodguy[militareuses]>2)
            addnav("F?`3&#149; P`3arata`7 (3/".$goodguy[militareuses].")`0","pvparena.php?op=fight&skill=CM&l=3",true);
        if ($goodguy[militareuses]>4)
            addnav("B?`3&#149; B`3erserk`7 (5/".$goodguy[militareuses].")`0","pvparena.php?op=fight&skill=CM&l=5",true);

        if ($goodguy[mysticuses]>0) {
            addnav("`\$Seduzione`0", "");
            addnav("`\$&#149; Sirene`7 (1/".$goodguy[mysticuses].")`0","pvparena.php?op=fight&skill=MY&l=1",true);
        }
        if ($goodguy[mysticuses]>1)
            addnav("`\$&#149; Danza`7 (2/".$goodguy[mysticuses].")`0","pvparena.php?op=fight&skill=MY&l=2",true);
        if ($goodguy[mysticuses]>2)
            addnav("`\$&#149; Fascino`7 (3/".$goodguy[mysticuses].")`0","pvparena.php?op=fight&skill=MY&l=3",true);
        if ($goodguy[mysticuses]>4)
            addnav("`\$&#149; Sonno`7 (5/".$goodguy[mysticuses].")`0","pvparena.php?op=fight&skill=MY&l=5",true);

        if ($goodguy[tacticuses]>0) {
            addnav("`^Tattica`0", "");
            addnav("`^&#149; Reclute`7 (1/".$goodguy[tacticuses].")`0","pvparena.php?op=fight&skill=TA&l=1",true);
        }
        if ($goodguy[tacticuses]>1)
            addnav("`^&#149; Sorpresa`7 (2/".$goodguy[tacticuses].")`0","pvparena.php?op=fight&skill=TA&l=2",true);
        if ($goodguy[tacticuses]>2)
            addnav("`^&#149; Attacco Notturno`7 (3/".$goodguy[tacticuses].")`0","pvparena.php?op=fight&skill=TA&l=3",true);
        if ($goodguy[tacticuses]>4)
            addnav("`^&#149; Arti Marziali`7 (5/".$goodguy[tacticuses].")`0","pvparena.php?op=fight&skill=TA&l=5",true);

        if ($goodguy[rockskinuses]>0) {
            addnav("`@Pelle di Roccia`0", "");
            addnav("`@&#149; Rocce Cadenti`7 (1/".$goodguy[rockskinuses].")`0","pvparena.php?op=fight&skill=RS&l=1",true);
        }
        if ($goodguy[rockskinuses]>1)
            addnav("`@&#149; Pelle Dura`7 (2/".$goodguy[rockskinuses].")`0","pvparena.php?op=fight&skill=RS&l=2",true);
        if ($goodguy[rockskinuses]>2)
            addnav("`@&#149; Pugno di Roccia`7 (3/".$goodguy[rockskinuses].")`0","pvparena.php?op=fight&skill=RS&l=3",true);
        if ($goodguy[rockskinuses]>4)
            addnav("`@&#149; Eco Montano`7 (5/".$goodguy[rockskinuses].")`0","pvparena.php?op=fight&skill=RS&l=5",true);

        if ($goodguy[rhetoricuses]>0) {
            addnav("`#Retorica`0", "");
            addnav("`#&#149; Dizionari`7 (1/".$goodguy[rhetoricuses].")`0","pvparena.php?op=fight&skill=RH&l=1",true);
        }
        if ($goodguy[rhetoricuses]>1)
            addnav("`#&#149; Paroloni`7 (2/".$goodguy[rhetoricuses].")`0","pvparena.php?op=fight&skill=RH&l=2",true);
        if ($goodguy[rhetoricuses]>2)
            addnav("`#&#149; Scioglilingua`7 (3/".$goodguy[rhetoricuses].")`0","pvparena.php?op=fight&skill=RH&l=3",true);
        if ($goodguy[rhetoricuses]>4)
            addnav("`#&#149; Discorsi`7 (5/".$goodguy[rhetoricuses].")`0","pvparena.php?op=fight&skill=RH&l=5",true);

        if ($goodguy[muscleuses]>0) {
            addnav("`%Muscoli`0", "");
            addnav("`%&#149; Gragnuola di Pugni`7 (1/".$goodguy[muscleuses].")`0","pvparena.php?op=fight&skill=MS&l=1",true);
        }
        if ($goodguy[muscleuses]>1)
            addnav("`%&#149; Flessibilità`7 (2/".$goodguy[muscleuses].")`0","pvparena.php?op=fight&skill=MS&l=2",true);
        if ($goodguy[muscleuses]>2)
            addnav("`%&#149; Capezzoli infuriati`7 (3/".$goodguy[muscleuses].")`0","pvparena.php?op=fight&skill=MS&l=3",true);
        if ($goodguy[muscleuses]>4)
            addnav("`%&#149; Lozione Abbronzante`7 (5/".$goodguy[muscleuses].")`0","pvparena.php?op=fight&skill=MS&l=5",true);

        if ($goodguy[natureuses]>0) {
            addnav("`3Natura`0", "");
            addnav("`3&#149;Aiuto Animale`7 (1/".$goodguy[natureuses].")`0","pvparena.php?op=fight&skill=NA&l=1",true);
        }
        if ($goodguy[natureuses]>1)
            addnav("`3&#149; Infestazione di Scarafaggi`7 (2/".$goodguy[natureuses].")`0","pvparena.php?op=fight&skill=NA&l=2",true);
        if ($goodguy[natureuses]>2)
            addnav("`3&#149; Artigli delle Aquile`7 (3/".$goodguy[natureuses].")`0","pvparena.php?op=fight&skill=NA&l=3",true);
        if ($goodguy[natureuses]>4)
            addnav("`3&#149; Bigfoot`7 (5/".$goodguy[natureuses].")`0","pvparena.php?op=fight&skill=NA&l=5",true);


        if ($goodguy[weatheruses]>0) {
            addnav("`&Clima`0", "");
            addnav("`&&#149;Folate di Vento`7 (1/".$goodguy[weatheruses].")`0","pvparena.php?op=fight&skill=WE&l=1",true);
        }
        if ($goodguy[weatheruses]>1)
            addnav("`&&#149; Tornado`7 (2/".$goodguy[weatheruses].")`0","pvparena.php?op=fight&skill=WE&l=2",true);
        if ($goodguy[weatheruses]>2)
            addnav("`&&#149; Pioggia`7 (3/".$goodguy[weatheruses].")`0","pvparena.php?op=fight&skill=WE&l=3",true);
        if ($goodguy[weatheruses]>4)
            addnav("`&&#149; Gelo Polare`7 (5/".$goodguy[weatheruses].")`0","pvparena.php?op=fight&skill=WE&l=5",true);
//Excalibur: nuove razze per donatori
        if ($goodguy['elementaleuses']>0) {
            addnav("`^Abilità Elementalista`0", "");
            addnav("`^&#149; Elementale d'Aria`7 (1/".$goodguy['elementaleuses'].")`0","pvparena.php?op=fight&skill=EL&l=1",true);
        }
        if ($goodguy['elementaleuses']>1)
        addnav("`^&#149; Elementale d'Acqua`7 (2/".$goodguy['elementaleuses'].")`0","pvparena.php?op=fight&skill=EL&l=2",true);
        if ($goodguy['elementaleuses']>2)
        addnav("`^&#149; Elementale di Terra`7 (3/".$goodguy['elementaleuses'].")`0","pvparena.php?op=fight&skill=EL&l=3",true);
        if ($goodguy['elementaleuses']>4)
        addnav("`^&#149; Elementale di Fuoco`7 (5/".$goodguy['elementaleuses'].")`0","pvparena.php?op=fight&skill=EL&l=5",true);

        if ($goodguy['barbarouses']>0) {
            addnav("`6Rabbia Barbara`0", "");
            addnav("`6&#149; Schivata Misteriosa`7 (1/".$goodguy['barbarouses'].")`0","pvparena.php?op=fight&skill=BB&l=1",true);
        }
        if ($goodguy['barbarouses']>1)
        addnav("`6&#149; Rabbia`7 (2/".$goodguy['barbarouses'].")`0","pvparena.php?op=fight&skill=BB&l=2",true);
        if ($goodguy['barbarouses']>2)
        addnav("`6&#149; Riduzione Danno`7 (3/".$goodguy['barbarouses'].")`0","pvparena.php?op=fight&skill=BB&l=3",true);
        if ($goodguy['barbarouses']>4)
        addnav("`6&#149; Ira Possente`7 (5/".$goodguy['barbarouses'].")`0","pvparena.php?op=fight&skill=BB&l=5",true);

        if ($goodguy['bardouses']>0) {
            addnav("`5Canzoni del Bardo`0", "");
            addnav("`5&#149; Fascinazione`7 (1/".$goodguy['bardouses'].")`0","pvparena.php?op=fight&skill=BA&l=1",true);
        }
        if ($goodguy['bardouses']>1)
        addnav("`5&#149; Canzone alla Rovescia`7 (2/".$goodguy['bardouses'].")`0","pvparena.php?op=fight&skill=BA&l=2",true);
        if ($goodguy['bardouses']>2)
        addnav("`5&#149; Grandezza Ispirata`7 (3/".$goodguy['bardouses'].")`0","pvparena.php?op=fight&skill=BA&l=3",true);
        if ($goodguy['bardouses']>4)
        addnav("`5&#149; Suggestione di Massa`7 (5/".$goodguy['bardouses'].")`0","pvparena.php?op=fight&skill=BA&l=5",true);

// spells by anpera
    if ($row[turn]==1) $sql="SELECT * FROM items WHERE class='Spell' AND owner=$row[acctid1] AND value1>0 ORDER BY name ASC";
    if ($row[turn]==2) $sql="SELECT * FROM items WHERE class='Spell' AND owner=$row[acctid2] AND value1>0 ORDER BY name ASC";
    $resultz=db_query($sql) or die(db_error(LINK));
    if (db_num_rows($resultz)>0) addnav("Incantesimi & Mosse");
    $countrowz = db_num_rows($resultz);
    for ($i=0; $i<$countrowz; $i++){
    //for ($i=0;$i<db_num_rows($resultz);$i++){
        $rowz = db_fetch_assoc($resultz);
        $spellbuff=unserialize($rowz[buff]);
        addnav("$spellbuff[name] (".$rowz[value1]."x)","pvparena.php?op=fight&skill=zauber&itemid=$rowz[id]");
    }
// end spells

}
function activate_buffs($tag) { // activate buffs (from battle.php with modifications for multiplayer battle)
    global $goodguy,$badguy,$message;
    reset($goodguy['bufflist']);
    reset($badguy['bufflist']);
    $result = array();
    $result['invulnerable'] = 0; // not in use
    $result['dmgmod'] = 1;
    $result['badguydmgmod'] = 1; // not in use
    $result['atkmod'] = 1;
    $result['badguyatkmod'] = 1; // not in use
    $result['defmod'] = 1;
    $result['badguydefmod'] = 1;
    $result['lifetap'] = array();
    $result['dmgshield'] = array();
    while(list($key,$buff) = each($goodguy['bufflist'])) {
        if (isset($buff['startmsg'])) {
            $msg = $buff['startmsg'];
            $msg = str_replace("{badguy}", $badguy[name], $msg);
            output("`%$msg`0");
            $message=$message.$goodguy[name].": \"`i$msg`i\"`n";
            unset($goodguy['bufflist'][$key]['startmsg']);
        }
        $activate = strpos($buff['activate'], $tag);
        if ($activate !== false) $activate = true; // handle strpos == 0;
        // If this should activate now and it hasn't already activated,
        // do the round message and mark it.
        if ($activate && !$buff['used']) {
            // mark it used.
            $goodguy['bufflist'][$key]['used'] = 1;
            // if it has a 'round message', run it.
            if (isset($buff['roundmsg'])) {
                $msg = $buff['roundmsg'];
                $msg = str_replace("{badguy}", $badguy[name], $msg);
                output("`)$msg`0`n");
                $message=$message.$goodguy[name].": \"`i$msg`i\"`n";
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
        if (isset($buff['regen']) && $activate) {
            $hptoregen = (int)$buff['regen'];
            $hpdiff = $goodguy['maxhitpoints'] -
            $goodguy['hitpoints'];
            // Don't regen if we are above max hp
            if ($hpdiff < 0) $hpdiff = 0;
            if ($hpdiff < $hptoregen) $hptoregen = $hpdiff;
            $goodguy['hitpoints'] += $hptoregen;
            // Now, take abs value just incase this was a damaging buff
            $hptoregen = abs($hptoregen);
            if ($hptoregen == 0) $msg = $buff['effectnodmgmsg'];
            else $msg = $buff['effectmsg'];
            $msg = str_replace("{badguy}", $badguy[name], $msg);
            $msg = str_replace("{damage}", $hptoregen, $msg);
            output("`)$msg`0`n");
            $message=$message.$goodguy[name].": \"`i$msg`i\"`n";
        }
        if (isset($buff['minioncount']) && $activate) {
            $who = -1;
            if (isset($buff['maxbadguydamage'])) {
                if (isset($buff['maxbadguydamage'])) {
                    $buff['maxbadguydamage'] = stripslashes($buff['maxbadguydamage']);
                    eval("\$buff['maxbadguydamage'] = $buff[maxbadguydamage];");
                }
                $max = $buff['maxbadguydamage'];

                if (isset($buff['minbadguydamage'])) {
                    $buff['minbadguydamage'] = stripslashes($buff['minbadguydamage']);
                    eval("\$buff['minbadguydamage'] = $buff[minbadguydamage];");
                }
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
                    $badguy[hitpoints] -= $damage;
                } else if ($who == 1) {
                    $goodguy[hitpoints] -= $damage;
                }
                if ($damage < 0) {
                    $msg = $buff['effectfailmsg'];
                } else if ($damage == 0) {
                    $msg = $buff['effectnodmgmsg'];
                } else if ($damage > 0) {
                    $msg = $buff['effectmsg'];
                }
                if ($msg>"") {
                    $msg = str_replace("{badguy}", $badguy['name'], $msg);
                    $msg = str_replace("{goodguy}", $session['user']['name'], $msg);
                    $msg = str_replace("{damage}", $damage, $msg);
                    output("`)$msg`0`n");
                    $message=$message.$goodguy[name].": \"`i$msg`i\"`n";
                }
            }
        }
    }
    while(list($key,$buff) = each($badguy['bufflist'])) { // check badguy buffs
        $activate = strpos($buff['activate'], $tag);
        if ($activate !== false) $activate = true;
        if ($activate && !$buff['used']) {
            $badguy['bufflist'][$key]['used'] = 1;
        }
        if (isset($buff['atkmod'])) {
            $result['badguyatkmod'] *= $buff['atkmod'];
        }
        if (isset($buff['defmod'])) {
            $result['badguydefmod'] *= $buff['defmod'];
        }
        if (isset($buff['badguyatkmod'])) {
            $result['atkmod'] *= $buff['badguyatkmod'];
        }
        if (isset($buff['badguydefmod'])) {
            $result['defmod'] *= $buff['badguydefmod'];
        }
        if (isset($buff['badguydmgmod'])) {
            $result['dmgmod'] *= $buff['badguydmgmod'];
        }
    }
    return $result;
}
function process_lifetaps($ltaps, $damage) {
    global $goodguy,$badguy,$message;
    reset($ltaps);
    while(list($key,$buff) = each($ltaps)) {
        $healhp = $goodguy['maxhitpoints'] -
            $goodguy['hitpoints'];
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
        $goodguy['hitpoints'] += $healhp;
        $msg = str_replace("{badguy}",$badguy['name'], $msg);
        $msg = str_replace("{damage}",$healhp, $msg);
        if ($msg > ""){
            output("`)$msg`n");
            $message=$message.$goodguy[name].": \"`i$msg`i\"`n";
        }
    }
}

function process_dmgshield($dshield, $damage) {
    global $session,$badguy,$message;
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
        $badguy[hitpoints] -= $realdamage;
        $msg = str_replace("{badguy}",$badguy['name'], $msg);
        $msg = str_replace("{damage}",$realdamage, $msg);
        if ($msg > ""){
            output("`)$msg`n");
            $message=$message.$goodguy[name].": \"`i$msg`i\"`n";
        }
    }
}
function expire_buffs() {
    global $goodguy,$badguy;
    reset($goodguy['bufflist']);
    reset($badguy['bufflist']);
    while (list($key, $buff) = each($goodguy['bufflist'])) {
        if ($buff['used']) {
            $goodguy['bufflist'][$key]['used'] = 0;
            $goodguy['bufflist'][$key]['rounds']--;
            if ($goodguy['bufflist'][$key]['rounds'] <= 0) {
                if ($buff['wearoff']) {
                    $msg = $buff['wearoff'];
                    $msg = str_replace("{badguy}", $badguy['name'], $msg);
                    output("`)$msg`n");
                    $message=$message.$goodguy[name].": \"`i$msg`i\"`n";
                }
                unset($goodguy['bufflist'][$key]);
            }
        }
    }
}

$cost=$session[user][level]*20;

if ($_GET[op]=="challenge"){
    if($_GET[name]=="" && $session[user][playerfights]>0){
        $ppp=25; // Players Per Page to display
        if (!$_GET[limit]){
            $page=0;
        }else{
            $page=(int)$_GET[limit];
            addnav("`\$Pagina Precedente","pvparena.php?op=challenge&limit=".($page-1)."");
        }
        $limit="".($page*$ppp).",".($ppp+1); // love PHP for this ;)
        pvpwarning();
        $days = getsetting("pvpimmunity", 5);
        $exp = getsetting("pvpminexp", 1500);
        output("`6Chi vuoi sfidare? La tassa d'ingresso è `^$cost `6pezzi d'oro. Il tuo avversario si preparerà per lo scontro.`nHai ancora `4".$session[user][playerfights]."`6 sfide rimanenti per oggi.`n`n");
        $sql = "SELECT name,alive,location,sex,level,laston,loggedin,login,pvpflag FROM accounts WHERE
        (locked=0) AND
        (age > $days OR dragonkills > 0 OR pk > 0 OR experience > $exp) AND
        (level >= ".($session[user][level]-1)." AND level <= ".($session[user][level]+2).") AND
        (acctid <> ".$session[user][acctid].")
        ORDER BY level DESC LIMIT $limit";
        $result = db_query($sql) or die(db_error(LINK));

/* from my PvP-Immunity Script -- removed for now
        if ($session['user']['pvpflag']=="2013-10-06 00:42:00"){
            output("`n`&Du hast PvP-Immunität gekauft. Diese verfällt, wenn du jetzt angreifst!`0`n`n");
        }
*/

        if (db_num_rows($result)>$ppp) addnav("`\$Pagina Successiva","pvparena.php?op=challenge&limit=".($page+1)."");
        output("<table border='0' cellpadding='3' cellspacing='0'><tr><td>Nome</td><td>Livello</td><td>Stato</td><td>Ops</td></tr>",true);
        $countrow = db_num_rows($result);
        for ($i=0; $i<$countrow; $i++){
        //for ($i=0;$i<db_num_rows($result);$i++){
            $row = db_fetch_assoc($result);

/* PvP-Immunity by anpera -- removed for now
            if ($row[pvpflag]!="2013-10-06 00:42:00"){
*/

                $biolink="bio.php?char=".rawurlencode($row[login])."&ret=".urlencode($_SERVER['REQUEST_URI']);
                addnav("", $biolink);
                $loggedin=(date("U") - strtotime($row[laston]) < getsetting("LOGINTIMEOUT",900) && $row[loggedin]);
                output("<tr class='".($i%2?"trlight":"trdark")."'><td>$row[name]</td><td>$row[level]</td><td>".($loggedin?"`@Collegato`0":"`3Scollegato`0")."</td><td>[ <a href='$biolink'>Bio</a> | <a href='pvparena.php?op=challenge&name=".rawurlencode($row[login])."'>Sfida</a> ]</td></tr>",true);
                addnav("","pvparena.php?op=challenge&name=".rawurlencode($row[login]));
//          }
        }
        output("</table>",true);
        addnav("Torna all'Arena","pvparena.php");
    }else if ($session[user][playerfights]<=0){
        output("`6Non hai potere a sufficenza per sfidare un altro giocatore.");
        addnav("Torna all'Arena","pvparena.php");
    }else{
        if ($session[user][gold]>=$cost){
            $sql = "SELECT acctid,name,level,sex,hitpoints,maxhitpoints,lastip,emailaddress FROM accounts WHERE login='".$_GET[name]."'";
            $result = db_query($sql) or die(db_error(LINK));
            $row = db_fetch_assoc($result);

/* Anticheat by anpera -- removed for now
            if ($session[user][lastip]==$row[lastip] || $session[user][emailaddress]==$row[emailaddress]){
                output("`n`4Du kannst deine eigenen oder derart verwandte Spieler nicht zu einem Duell herausfordern!`0`n`n");
            }else{
*/

                $sql = "SELECT * FROM pvp WHERE acctid2=".$session[user][acctid]." OR acctid1=$row[acctid] OR acctid2=$row[acctid]";
                $result = db_query($sql) or die(db_error(LINK));
                if (db_num_rows($result)){
                    output("`6Qualcun altro è stato più veloce con questa sfida!");
                }else{
                    $sql = "INSERT INTO pvp (acctid1,acctid2,name1,name2,lvl1,lvl2,hp1,maxhp1,att1,def1,weapon1,armor1,darkartuses1,magicuses1,thieveryuses1,militareuses1,mysticuses1,tacticuses1,rockskinuses1,rhetoricuses1,muscleuses1,natureuses1,weatheruses1,elementaleuses1,barbarouses1,bardouses1,bufflist1,turn)
                    VALUES (".$session[user][acctid].",$row[acctid],'".addslashes($session[user][name])."','".addslashes($row[name])."',".$session[user][level].",$row[level],".$session[user][hitpoints].",".$session[user][maxhitpoints].",".$session[user][attack].",".$session[user][defence].",'".addslashes($session[user][weapon])."','".addslashes($session[user][armor])."',".$session[user][darkartuses].",".$session[user][magicuses].",".$session[user][thieveryuses].",".$session[user][militareuses].",".$session[user][mysticuses].",".$session[user][tacticuses].",".$session[user][rockskinuses].",".$session[user][rhetoricuses].",".$session[user][muscleuses].",".$session[user][natureuses].",".$session[user][weatheruses].",".$session[user][elementaleuses].",".$session[user][barbarouses].",".$session[user][bardouses].",'".addslashes($session[user][bufflist])."',0)";
                    db_query($sql) or die(db_error(LINK));
                    if (db_affected_rows(LINK)<=0) redirect("pvparena.php");
                    output("`6Hai sfidato`4 $row[name] `6per un combattimento \"One on One\" e stai aspettando la sua risposta. `n");// Puoi pagare la tassa d'iscrizione per $row[name]`6 transferendo ");
                    //output("i suoi `^".($row[level]*20)."`6 pezzi d'oro.`n");
                    if ($session[user][dragonkills]<2) output("`n`n`i(Puoi continuare con i tuoi affari ora. Appena $row[name]`6 risponderà, riceverai un messaggio.)`i");
                    systemmail($row[acctid],"`2Sei stato sfidato!","`2".$session[user][name]."`2 (Livello ".$session[user][level].") ti ha sfidato a duello nell'arena. Finchè il tuo livello non cambierà, puoi accettare o declinare la sfida.`nPreparati prima di entrare nell'arena!");
                    $session[user][gold]-=$cost;
                }
//          }
            addnav("Torna all'Arena","pvparena.php");
        }else{
            output("`4Non hai abbastanza oro con te per pagare la tassa. Rosso di vergogna abbandoni l'arena.");
            addnav("Torna all'Arena","pvparena.php");
        }
    }
    addnav("Torna al Villaggio","village.php");
}else if ($_GET[op]=="deny"){
    $sql = "DELETE FROM pvp WHERE acctid2=".$session[user][acctid];
    db_query($sql) or die(db_error(LINK));
    $sql="SELECT acctid,name FROM accounts WHERE acctid=$_GET[id]";
    $result = db_query($sql) or die(db_error(LINK));
    $row = db_fetch_assoc($result);
    output("`6Fronteggi $row[name] `6e la paura prende il sopravvento. Con una scusa ridicola come \"non ho abbastanza oro\" non hai accettato la sfida.");
    systemmail($row[acctid],"`2Sfida rifiutata","`2".$session[user][name]."`2 non ha accettato la sfida. Forse dovresti offrir".($session[user][sex]?"le":"gli")." qualcosa per convincerlo ad accettare la sfida.");
    addnav("Torna alVillaggio","village.php");
}else if ($_GET[op]=="accept"){
    if($session[user][gold]<$cost){
        output("`4Non puoi permetterti di pagare i `^$cost`4 pezzi d'oro per l'ingresso all'arena.");
        addnav("Torna al Villaggio","village.php");
    }else if($session[user][playerfights]<=0){
        output("`4Non puoi fare altri combattimenti per oggi. Dovrai attendere un altro giorno.");
        addnav("Torna al Villaggio","village.php");
    }else{
        $sql = "UPDATE pvp SET name2='".addslashes($session[user][name])."',hp2=".$session[user][hitpoints].",maxhp2=".$session[user][maxhitpoints].",att2=".$session[user][attack].",def2=".$session[user][defence].",weapon2='".addslashes($session[user][weapon])."',armor2='".addslashes($session[user][armor])."',darkartuses2=".$session[user][darkartuses].",magicuses2=".$session[user][magicuses].",thieveryuses2=".$session[user][thieveryuses].",militareuses2=".$session[user][militareuses].",mysticuses2=".$session[user][mysticuses].",tacticuses2=".$session[user][tacticuses].",rockskinuses2=".$session[user][rockskinuses].",rhetoricuses2=".$session[user][rhetoricuses].",muscleuses2=".$session[user][muscleuses].",natureuses2=".$session[user][natureuses].",weatheruses2=".$session[user][weatheruses].",elementaleuses2=".$session[user][elementaleuses].",barbarouses2=".$session[user][barbarouses].",bardouses2=".$session[user][bardouses].",bufflist2='".addslashes($session[user][bufflist])."',turn=2 WHERE acctid2=".$session[user][acctid]."";
        db_query($sql) or die(db_error(LINK));
        if (db_affected_rows(LINK)<=0) redirect("pvparena.php");
        $sql="SELECT * FROM pvp WHERE acctid1=".$session[user][acctid]." OR acctid2=".$session[user][acctid]."";
        $result = db_query($sql) or die(db_error(LINK));
        $row = db_fetch_assoc($result);
        $session[user][gold]-=$cost;
        arenanav($row);
        stats($row);
    }
}else if ($_GET[op]=="back"){
    $sql="SELECT acctid,name FROM accounts WHERE acctid=$_GET[id]";
    $result = db_query($sql) or die(db_error(LINK));
    $row = db_fetch_assoc($result);
    output("`6Non puoi attendere oltre la risposta di $row[name]`6 e ritiri la tua sfida. Ma non puoi convincere la direzione dell'arena a restituirti i soldi.`n");
    $sql = "DELETE FROM pvp WHERE acctid1=".$session[user][acctid];
    db_query($sql) or die(db_error(LINK));
    systemmail($row[acctid],"`2Sfida annullata","`2".$session[user][name]."`2 ha ritirato la sua sfida.");
    addnav("Torna al Villaggio","village.php");
}else if ($_GET[op]=="fight"){
    $sql="SELECT * FROM pvp WHERE acctid1=".$session[user][acctid]." OR acctid2=".$session[user][acctid]."";
    $result = db_query($sql) or die(db_error(LINK));
    $row = db_fetch_assoc($result);
    if ($row[turn]==1){
        $badguy = array("acctid"=>$row[acctid2],"name"=>$row[name2],"level"=>$row[lvl2],"hitpoints"=>$row[hp2],"attack"=>$row[att2],"defense"=>$row[def2],"weapon"=>$row[weapon2],"armor"=>$row[armor2],"bufflist"=>$row[bufflist2]);
        $goodguy = array("name"=>$row[name1],"level"=>$row[lvl1],"hitpoints"=>$row[hp1],"maxhitpoints"=>$row[maxhp1],"attack"=>$row[att1],"defense"=>$row[def1],"weapon"=>$row[weapon1],"armor"=>$row[armor1],"darkartuses"=>$row[darkartuses1],"magicuses"=>$row[magicuses1],"thieveryuses"=>$row[thieveryuses1],"militareuses"=>$row[militareuses1],"mysticuses"=>$row[mysticuses1],"tacticuses"=>$row[tacticuses1],"rockskinuses"=>$row[rockskinuses1],"rhetoricuses"=>$row[rhetoricuses1],"muscleuses"=>$row[muscleuses1],"natureuses"=>$row[natureuses1],"weatheruses"=>$row[weatheruses1],"elementaleuses"=>$row[elementaleuses1],"barbarouses"=>$row[barbarouses1],"bardouses"=>$row[bardouses1],"bufflist"=>$row[bufflist1]);
    }
    if ($row[turn]==2){
        $badguy = array("acctid"=>$row[acctid1],"name"=>$row[name1],"level"=>$row[lvl1],"hitpoints"=>$row[hp1],"attack"=>$row[att1],"defense"=>$row[def1],"weapon"=>$row[weapon1],"armor"=>$row[armor1],"bufflist"=>$row[bufflist1]);
        $goodguy = array("name"=>$row[name2],"level"=>$row[lvl2],"hitpoints"=>$row[hp2],"maxhitpoints"=>$row[maxhp2],"attack"=>$row[att2],"defense"=>$row[def2],"weapon"=>$row[weapon2],"armor"=>$row[armor2],"darkartuses"=>$row[darkartuses2],"magicuses"=>$row[magicuses2],"thieveryuses"=>$row[thieveryuses2],"militareuses"=>$row[militareuses2],"mysticuses"=>$row[mysticuses2],"tacticuses"=>$row[tacticuses2],"rockskinuses"=>$row[rockskinuses2],"rhetoricuses"=>$row[rhetoricuses2],"muscleuses"=>$row[muscleuses2],"natureuses"=>$row[natureuses2],"weatheruses"=>$row[weatheruses2],"elementaleuses"=>$row[elementaleuses2],"barbarouses"=>$row[barbarouses2],"bardouses"=>$row[bardouses2],"bufflist"=>$row[bufflist2]);
    }
    stats($row);
    $adjustment=1;
    $goodguy[bufflist]=unserialize($goodguy[bufflist]);
    $badguy[bufflist]=unserialize($badguy[bufflist]);

// spells by anpera
    if ($_GET[skill]=="zauber"){
        $resultz=db_query("SELECT * FROM items WHERE id=$_GET[itemid]") or die(db_error(LINK));
        $zauber = db_fetch_assoc($resultz);
        $spellbuff=unserialize($zauber[buff]);
        $goodguy[bufflist][$spellbuff[name]]=$spellbuff;
        $zauber[gold]=round($zauber[gold]*($zauber[value1]/($zauber[value2]+1)));
        $zauber[gems]=round($zauber[gems]*($zauber[value1]/($zauber[value2]+1)));
        $zauber[value1]--;
        if ($zauber[value1]<=0 && $zauber[hvalue]<=0){
            db_query("DELETE FROM items WHERE id=$_GET[itemid]");
        }else{
            db_query("UPDATE items SET value1=$zauber[value1], gems=$zauber[gems], gold=$zauber[gold] WHERE id=$_GET[itemid]");
        }
    }
// end spells

    if ($_GET[skill]=="MP"){
        if ($goodguy[magicuses] >= $_GET[l]){
            $creaturedmg = 0;
            switch($_GET[l]){
            case 1:
                $goodguy[bufflist]['mp1'] = array(
                    "startmsg"=>"`^Inizi a rigenerarti!`n`n",
                    "name"=>"`%Rigenerazione",
                    "rounds"=>5,
                    "wearoff"=>"Hai smesso di rigenerarti",
                    "regen"=>$goodguy['level'],
                    "effectmsg"=>"Ti rigeneri per {damage} HP.",
                    "effectnodmgmsg"=>"Non hai ferite da rigenerare.",
                    "activate"=>"roundstart");
                break;
            case 2:
                $goodguy[bufflist]['mp2'] = array(
                    "startmsg"=>"`^{badguy}`% viene afferrato da un pugno di terra e sbattuto al suolo!`n`n",
                    "name"=>"`%Pugno di Terra",
                    "rounds"=>5,
                    "wearoff"=>"Il pugno di terra si sgretola.",
                    "minioncount"=>1,
                    "effectmsg"=>"`) Un grosso pugno di terra colpisce {badguy} causandogli `^{damage}`) punti di danno.",
                    "minbadguydamage"=>1,
                    "maxbadguydamage"=>$goodguy['level']*3,
                    "activate"=>"roundstart"
                    );
                break;
            case 3:
                $goodguy[bufflist]['mp3'] = array(
                    "startmsg"=>"`^La tua arma emette un bagliore ultraterreno.`n`n",
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
                $goodguy[bufflist]['mp5'] = array(
                    "startmsg"=>"`^La tua pelle scintilla e vieni avvolto da un´aura di fulmini`n`n",
                    "name"=>"`%Aura di Fulmini",
                    "rounds"=>5,
                    "wearoff"=>"Crepitando, la tua pelle torna normale.",
                    "damageshield"=>2,
                    "effectmsg"=>"{badguy} si ritrae per gli archi voltaici che emette la tua pelle, che lo colpiscono causando `^{damage}`) di danno.",
                    "effectnodmg"=>"{badguy} è leggermente bruciacchiato dal tuo fulmine, ma niente di più.",
                    "effectfailmsg"=>"{badguy} è leggermente bruciacchiato dal tuo fulmine, ma niente di più..",
                    "activate"=>"offense,defense"
                );
            break;
            }
            $goodguy[magicuses]-=(int)$_GET[l];
        }else{
            $goodguy[bufflist]['mp0'] = array(
                "startmsg"=>"Aggrotti la fronte ed evochi il potere degli elementi. Una fiammella compare.  {badguy} ci si accende una sigaretta, ringraziandoti prima di attaccarti nuovamente.`n`n",
                "rounds"=>1,
                "activate"=>"roundstart"
            );
        }
    }
    if ($_GET[skill]=="DA"){
        if ($goodguy[darkartuses] >= $_GET[l]){
            $creaturedmg = 0;
            switch($_GET[l]){
            case 1:
                $goodguy[bufflist]['da1']=array(
                    "startmsg"=>"`\$Evochi gli spiriti dei morti, e delle mani scheletriche artigliano {badguy} da dentro la tomba.`n`n",
                    "name"=>"`\$Ciurma di Scheletri",
                    "rounds"=>5,
                    "wearoff"=>"I tuoi servi scheletrici si riducono in polvere.",
                    "minioncount"=>round($goodguy[level]/3)+1,
                    "maxbadguydamage"=>round($goodguy[level]/2,0)+1,
                    "effectmsg"=>"`)Un seguace dei non-morti colpisce {badguy} causando `^{damage}`) di danno.",
                    "effectnodmgmsg"=>"`)Un seguace dei non-morti cerca di colpire {badguy} ma `\$LO MANCA`)!",
                    "activate"=>"roundstart"
                    );
                break;
            case 2:
                $goodguy[bufflist]['da2']=array(
                    "startmsg"=>"`\$Estrai una bambolina voodoo che sembra {badguy}`n`n",
                    "effectmsg"=>"Infili uno spillone nella bambola di {badguy} causandogli `^{damage}`) di danno!",
                    "minioncount"=>1,
                    "maxbadguydamage"=>round($goodguy[attack]*3,0),
                    "minbadguydamage"=>round($goodguy[attack]*1.5,0),
                    "activate"=>"roundstart"
                    );
                break;
            case 3:
                $goodguy[bufflist]['da3']=array(
                    "startmsg"=>"`\$Metti una maledizione sugli avi di {badguy}.`n`n",
                    "name"=>"`\$Maledizione",
                    "rounds"=>5,
                    "wearoff"=>"La tua maledizione si esaurisce.",
                    "badguydmgmod"=>0.5,
                    "roundmsg"=>"{badguy} vacilla sotto il peso della tua maledizione e ti causa solo la metà di danno.",
                    "activate"=>"defense"
                    );
                break;
            case 5:
                $goodguy[bufflist]['da5']=array(
                    "startmsg"=>"`\$Punti il dito e le orecchie di {badguy} iniziano a sanguinare.`n`n",
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
            $goodguy[darkartuses]-=(int)$_GET[l];
        }else{
            $goodguy[bufflist]['da0'] = array(
                "startmsg"=>"Esausto, tenti la tua magia più oscura, una pessima battuta.  {badguy} ti guarda pensieroso per un minuto, poi finalmente capisce la battuta. Ridendo, riprende a picchiarti.`n`n",
                "rounds"=>1,
                "activate"=>"roundstart"
                );
        }
    }
    if ($_GET[skill]=="CM"){
        if ($goodguy[militareuses] >= $_GET[l]){
            $creaturedmg = 0;
            switch($_GET[l]){
            case 1:
                $goodguy[bufflist]['cm1']=array(
                    "startmsg"=>"`\$Inizi a menare come un matto il povero {badguy} che non ha speranze di salvarsi.`n`n",
                    "name"=>"`3Colpi Multipli",
                    "rounds"=>5,
                    "wearoff"=>"Affannato torni a colpire normalmente.",
                    "minioncount"=>round($session[user][level]/3)+1,
                    "maxbadguydamage"=>round($session[user][level]/2,0)+1,
                    "effectmsg"=>"`)Ti muovi rapidamente e colpisci di nuovo {badguy} causandogli `^{damage}`) danni.",
                    "effectnodmgmsg"=>"`)Purtroppo il colpo speciale che hai tentato contro {badguy} `\$LO MANCA`)!",
                    "activate"=>"roundstart"
                    );
                break;
            case 2:
                $goodguy[bufflist]['cm2']=array(
                    "startmsg"=>"`\$Miri i tuoi colpi in zone critiche di {badguy}`n`n",
                    "effectmsg"=>"Colpisci in una zona critica {badguy} causandogli `^{damage}`) danni!",
                    "minioncount"=>1,
                    "maxbadguydamage"=>round($session[user][attack]*3,0),
                    "minbadguydamage"=>round($session[user][attack]*1.5,0),
                    "activate"=>"roundstart"
                    );
                break;
            case 3:
                $goodguy[bufflist]['cm3']=array(
                    "startmsg"=>"`\$Adotti una posizione per meglio parare i colpi di {badguy}.`n`n",
                    "name"=>"`3Parata",
                    "rounds"=>5,
                    "wearoff"=>"Hai smesso di parare.",
                    "badguydmgmod"=>0.5,
                    "roundmsg"=>"Stai parando i colpi di {badguy}, dimezzi il danno che ti infligge.",
                    "activate"=>"defense"
                    );
                break;
            case 5:
                    $goodguy[bufflist]['cm5'] = array(
                    "startmsg"=>"`\$La furia si impossessa di te, i tuoi muscoli si gonfiano, `^{badguy}`\$ ti guarda terrorizzato!`n`n",
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
            $goodguy[militareuses]-=$_GET[l];
        }else{
            $session[bufflist]['cm0'] = array(
                "startmsg"=>"Esausto, tenti la tua magia più oscura, una pessima battuta.  {badguy} ti guarda pensieroso per un minuto, poi finalmente capisce la battuta. Ridendo, riprende a picchiarti.`n`n",
                "rounds"=>1,
                "activate"=>"roundstart"
                );
        }
    }
    if ($_GET[skill]=="TS"){
        if ($goodguy[thieveryuses] >= $_GET[l]){
            $creaturedmg = 0;
            switch($_GET[l]){
            case 1:
                $goodguy[bufflist]['ts1']=array(
                    "startmsg"=>"`^Chiami {badguy} con un nomigliolo offensivo, facendolo piangere.`n`n",
                    "name"=>"`^Insulto",
                    "rounds"=>5,
                    "wearoff"=>"La tua vittima smette di piangere e si soffia il naso.",
                    "roundmsg"=>"{badguy} si sente demoralizzato e non ti può attaccare.",
                    "badguyatkmod"=>0.5,
                    "activate"=>"defense"
                    );
                break;
            case 2:
                $goodguy[bufflist]['ts2']=array(
                    "startmsg"=>"`^Metti del veleno sul tuo ".$goodguy[weapon].".`n`n",
                    "name"=>"`^Attacco al Veleno",
                    "rounds"=>5,
                    "wearoff"=>"Il sangue della tua vittima ha lavato via il veleno dall´arma.",
                    "atkmod"=>2,
                    "roundmsg"=>"Il tuo attacco è potenziato!",
                    "activate"=>"offense"
                    );
                break;
            case 3:
                $goodguy[bufflist]['ts3'] = array(
                    "startmsg"=>"`^Con la tua esperienza di ladro, scompari ed attacchi {badguy} da una posizione di vantaggio.`n`n",
                    "name"=>"`^Attacco Nascosto",
                    "rounds"=>5,
                    "wearoff"=>"La tua vittima ti ha localizzato.",
                    "roundmsg"=>"{badguy} non riesce a trovarti.",
                    "badguyatkmod"=>0,
                    "activate"=>"defense"
                    );
                break;
            case 5:
                $goodguy[bufflist]['ts5']=array(
                    "startmsg"=>"`^Usando i tuoi talenti di ladro, scompari dietro {badguy} e infili una lama sottile tra le sue vertebre!`n`n",
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
            $goodguy[thieveryuses]-=(int)$_GET[l];
        }else{
            $goodguy[bufflist]['ts0'] = array(
                "startmsg"=>"Tenti di attaccare {badguy} mettendo in pratica i tuoi migliori talenti di ladro, ma inciampi nel tuo piede.`n`n",
                "rounds"=>1,
                "activate"=>"roundstart"
                );
        }
    }
    //special abilities mod by sixf00t4 start
    if ($_GET[skill]=="MY"){
        if ($goodguy[mysticuses] >= $_GET[l]){
            $creaturedmg = 0;
            switch($_GET[l]){
            case 1:
                $goodguy[bufflist]['my1'] = array(
                    "startmsg"=>"`n`^Le sirene urlano!`n`n",
                    "name"=>"`%Sirene",
                    "rounds"=>3,
                    "wearoff"=>"L'urlo termina",
                    "regen"=>$session['user']['level'],
                    "effectmsg"=>"Rigeneri per {damage} HP.",
                    "effectnodmgmsg"=>"Non hai ferite da rigenerare.",
                    "minbadguydamage"=>1,
                    "maxbadguydamage"=>$session['user']['level']*2,
                    "activate"=>"roundstart");
                break;
            case 2:
                $goodguy[bufflist]['my2'] = array(
                    "startmsg"=>"`n`^{badguy}`% è distratto dal tuo danzargli intorno!`n`n",
                    "name"=>"`%Danza",
                    "rounds"=>5,
                    "wearoff"=>"Termini le energie e la smetti di danzare.",
                    "effectmsg"=>"{badguy} preso dal capogiro inciampa e subisce `^{damage}`) punti di danno.",
                    "minbadguydamage"=>1,
                    "maxbadguydamage"=>$session['user']['level']*3,
                    "activate"=>"roundstart"
                    );
                break;
            case 3:
                $goodguy[bufflist]['my3'] = array(
                    "startmsg"=>"`n`^{badguy} è ammaliato dal tuo fascino`n`n",
                    "name"=>"`%Fascino",
                    "rounds"=>4,
                    "wearoff"=>"{badguy} esce dalla magia del tuo fascino.",
                    "badguyatkmod"=>0,
                    "badguydefmod"=>0.5,
                    "activate"=>"offense,defense");
                break;
            case 5:
                $goodguy[bufflist]['my5'] = array(
                    "startmsg"=>"`n`^Il nemico cade addormentato.`n`n",
                    "name"=>"`%Sonno",
                    "rounds"=>6,
                    "wearoff"=>"si risveglia.",
                    "badguyatkmod"=>0,
                    "activate"=>"roundstart");
                break;
            }
            $goodguy[mysticuses]-=$_GET[l];
        }else{
            $session[bufflist]['my0'] = array(
                "startmsg"=>"`nNon sei più attraente.`n`n",
                "rounds"=>1,
                "activate"=>"roundstart"
                );

        }
    }
    if ($_GET[skill]=="TA"){
        if ($goodguy[tacticuses] >= $_GET[l]){
            $creaturedmg = 0;
            switch($_GET[l]){
            case 1:
                $goodguy[bufflist]['ta1'] = array(
                    "startmsg"=>"`n`^Richiami all'ordine le tue reclute!`n`n",
                    "name"=>"`%Reclute",
                    "rounds"=>5,
                    "wearoff"=>"Le tue reclute ti abbandonano.",
                    "minioncount"=>round($session[user][level]/3)+1,
                    "maxbadguydamage"=>round($session[user][level]/2,0)+1,
                    "effectmsg"=>"`)Una recluta colpisce {badguy} per `^{damage}`) punti danno.",
                    "effectnodmgmsg"=>"`)Una recluta cerca di colpire {badguy} ma  `\$MANCA`)!",
                    "activate"=>"roundstart");
                break;
            case 2:
                $goodguy[bufflist]['ta2'] = array(
                    "startmsg"=>"`n`^Distrai {badguy}con un colpetto sulla spalla e lo attacchi dall'altro lato`n",
                    "name"=>"`%Sorpresa",
                    "rounds"=>8,
                    "wearoff"=>"Sei senza fiato, e non sorprendi più {badguy}.",
                    "minioncount"=>1,
                    "effectmsg"=>"Attacchi a sorpresa!",
                    "minbadguydamage"=>1,
                    "maxbadguydamage"=>$session['user']['level']*3,
                    "activate"=>"roundstart");
                break;
            case 3:
                $goodguy[bufflist]['ta3'] = array(
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
                $goodguy[bufflist]['ta5'] = array(
                    "startmsg"=>"`n`^con le tue ginocchia piegate e la mano estesa, domini i tuoi avversari.`n`n",
                    "name"=>"`%Arti Marziali",
                    "rounds"=>4,
                    "damageshield"=>2,
                    "atkmod"=>3,
                    "effectmsg"=>"{badguy} indietreggia al tuo colpo di karate, cilpito per `^{damage}`) punti danno.",
                    "effectnodmg"=>"{badguy} è moderatamente impressionato dalle tue arti marziali, ma rimane illeso.",
                    "effectfailmsg"=>"{badguy} è moderatamente impressionato dai tuoi movimenti, ma rimane illeso.",
                    "activate"=>"offense,defense");
                break;
            }
            $goodguy[tacticuses]-=$_GET[l];
        }else{
            $goodguy[bufflist]['ta0'] = array(
                "startmsg"=>"`nRimani basito.`n`n",
                "rounds"=>1,
                "activate"=>"roundstart");
        }
    }
    if ($_GET[skill]=="RS"){
        if ($goodguy[rockskinuses] >= $_GET[l]){
            $creaturedmg = 0;
            switch($_GET[l]){
            case 1:
                $goodguy[bufflist]['rs1'] = array(
                    "startmsg"=>"`n`^Le rocce cadono sul tuo nemico!`n`n",
                    "name"=>"`%Rocce Cadenti",
                    "rounds"=>5,
                    "wearoff"=>"le rocce non cadono più.",
                    "minioncount"=>round($session[user][level]/3)+1,
                    "maxbadguydamage"=>round($session[user][level]/2,0)+5,
                    "effectmsg"=>"`)Una roccia colpisce {badguy} per `^{damage}`) punti danno.",
                    "effectnodmgmsg"=>"`)Una roccia sfiora {badguy} ma `\$LO MANCA`)!",
                    "activate"=>"roundstart");
                break;
            case 2:
                $goodguy[bufflist]['rs2'] = array(
                    "startmsg"=>"`n`^Chiudi gli occhi e la tua pelle si indurisce.`n`n",
                    "name"=>"`%Pelle Dura",
                    "rounds"=>5,
                    "defmod"=>3,
                    "roundmsg"=>"{badguy} ti colpisce ma quasi non senti il colpo!",
                    "wearoff"=>"Con un sibilo, la tua pelle torna normale.",
                    "activate"=>"roundstart");
                break;
            case 3:
                $goodguy[bufflist]['rs3'] = array(
                    "startmsg"=>"`n`^Un pugno di roccia esce dalle montagne vicine.`n`n",
                    "name"=>"`%Pugno di Roccia",
                    "rounds"=>5,
                    "wearoff"=>"Il pugno di roccia si frantuma in polvere.",
                    "minioncount"=>1,
                    "effectmsg"=>"Un grosso pugno di roccia colpisce {badguy} per `^{damage}`) punti danno.",
                    "effectnodmgmsg"=>"Un grosso pugno di roccia manca {badguy} che ti schernisce.",
                    "minbadguydamage"=>1,
                    "maxbadguydamage"=>$session['user']['level']*4,
                    "activate"=>"roundstart");
                break;
            case 5:
                $goodguy[bufflist]['rs5'] = array(
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
            $goodguy[rockskinuses]-=$_GET[l];
        }else{
            $goodguy[bufflist]['rs0'] = array(
                "startmsg"=>"`nNon sei vicino a nessuna montagna.`n`n",
                "rounds"=>1,
                "activate"=>"roundstart");
        }
    }
    if ($_GET[skill]=="MS"){
        if ($goodguy[muscleuses] >= $_GET[l]){
            $creaturedmg = 0;
            switch($_GET[l]){
            case 1:
                $goodguy[bufflist]['ms1'] = array(
                    "startmsg"=>"`n`^Le tue mani si ingrossano e colpiscono {badguy}!`n`n",
                    "name"=>"`%Gragnuola di Pugni",
                    "rounds"=>4,
                    "roundmsg"=>"I colpi che sferri a {badguy} lo stanno massacrando.",
                    "wearoff"=>"Le tue mani ritornano alle loro dimensioni normali",
                    "atkmod"=>round($session['user']['level']/10)+1,
                    "activate"=>"offense");
                break;
            case 2:
                $goodguy[bufflist]['ms2'] = array(
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
                $goodguy[bufflist]['ms3'] = array(
                    "startmsg"=>"`n`^I tuoi capezzoli escono per battersi!`n`n",
                    "name"=>"`%Capezzoli infuriati",
                    "minioncount"=>2,
                    "rounds"=>4,
                    "effectmsg"=>"{badguy} è titillato!",
                    "minbadguydamage"=>1,
                    "maxbadguydamage"=>$session['user']['level']*3,
                    "activate"=>"roundstart");
                break;
            case 5:
                $goodguy[bufflist]['ms5'] = array(
                    "startmsg"=>"`n`^La tua pelle scintilla e assumi un'aura bronzea`n`n",
                    "name"=>"`%Lozione Abbronzante",
                    "rounds"=>5,
                    "wearoff"=>"Con un sibilo, la tua pelle torna normale.",
                    "damageshield"=>2,
                    "effectmsg"=>"{badguy} si ritrae a causa dei bagliori della tua pelle nei suoi occhi, lo colpisci per `^{damage}`) punti danno.",
                    "effectnodmg"=>"{badguy} è leggermente impressionato dal tuo colore naturale, ma resta illeso.",
                    "effectfailmsg"=>"{badguy} è illuminato dal tuo colore, ma resta illeso.",
                    "activate"=>"offense,defense");
                break;
            }
            $goodguy[muscleuses]-=$_GET[l];
        }else{
            $goodguy[bufflist]['ms0'] = array(
                "startmsg"=>"`nTi senti debole.`n`n",
                "rounds"=>1,
                "activate"=>"roundstart");
        }
    }
    if ($_GET[skill]=="RH"){
        if ($goodguy[rhetoricuses] >= $_GET[l]){
            $creaturedmg = 0;
            switch($_GET[l]){
            case 1:
                $goodguy[bufflist]['rh1'] = array(
                    "startmsg"=>"`n`^Inizi a lanciare dei dizionari!`n`n",
                    "name"=>"`%Dizionari",
                    "rounds"=>4,
                    "roundmsg"=>"I tuoi dizionari giungo a segno, colpendo {badguy}",
                    "maxbadguydamage"=>round($session['user']['level']/3),
                    "minioncount"=>3,
                    "atkmod"=>1.2,
                    "wearoff"=>"Smetti di lanciare dizionari",
                    "activate"=>"roundstart");
                break;
            case 2:
                $goodguy[bufflist]['rh2'] = array(
                    "startmsg"=>"`n`^{badguy}`% è confuso dai paroloni difficili che usi!`n`n",
                    "name"=>"`%Paroloni",
                    "rounds"=>5,
                    "badguyatkmod"=>0,
                    "roundmsg"=>"{badguy} sta iniziando a non capire più nulla",
                    "wearoff"=>"smette di ascoltarti.",
                    "activate"=>"roundstart"
                    );
                break;
            case 3:
                $goodguy[bufflist]['rh3'] = array(
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
                $goodguy[bufflist]['rh5'] = array(
                    "startmsg"=>"`n`^Inizi un lungo discorso, che ti consente di rigenerare.`n`n",
                    "name"=>"`%Discorsi",
                    "rounds"=>10,
                    "wearoff"=>"Hai smesso di guarirti",
                    "regen"=>$session['user']['level']*2,
                    "effectmsg"=>"Ti curi per {damage} HP.",
                    "effectnodmgmsg"=>"Non hai ferite da curare.",
                    "activate"=>"roundstart"
                    );
                break;
            }
            $goodguy[rhetoricuses]-=$_GET[l];
        }else{
            $goodguy[bufflist]['rh0'] = array(
                "startmsg"=>"`nNon riesci a pensare a nessuna parola.`n`n",
                "rounds"=>1,
                "activate"=>"roundstart"
            );
        }
    }
    if ($_GET[skill]=="NA"){
        if ($goodguy[natureuses] >= $_GET[l]){
            $creaturedmg = 0;
            switch($_GET[l]){
            case 1:
                $goodguy[bufflist]['na1'] = array(
                    "startmsg"=>"`n`^Lanci un grido d'aiuto in direzione della foresta!`n`n",
                    "name"=>"`%Aiuto Animale",
                    "rounds"=>4,
                    "wearoff"=>"Gli animali tornano nella foresta.",
                    "minioncount"=>round($session[user][level]/4)+1,
                    "effectmsg"=>"Gli animali attaccano {badguy} causandogli `^{damage}`) punti danno.",
                    "effectnodmgmdg"=>"Gli animali cercano di azzannare {badguy} ma `\$LO MANCANO!!",
                    "minbadguydamage"=>round($session['user']['level']/3),
                    "maxbadguydamage"=>$session['user']['level'],
                    "activate"=>"roundstart"
                    );
                break;
            case 2:
                $goodguy[bufflist]['na2'] = array(
                    "startmsg"=>"`n`^Colpisci il terreno e gli scarafaggi coprono {badguy}.`n`n",
                    "name"=>"`%Infestazione di Scarafaggi",
                    "effectmsg"=>"Gli scarafaggi scarnificano {badguy} causandogli `^{damage}`) punti danno.",
                    "effectnodmgmdg"=>"Gli scarafaggi non riescono ad attaccare {badguy} !!",
                    "rounds"=>5,
                    "atkmod"=>2,
                    "activate"=>"roundstart"
                    );
                break;
            case 3:
                $goodguy[bufflist]['na3'] = array(
                    "startmsg"=>"`n`^Unisci le mani e invochi le aquile!`n`n",
                    "name"=>"`%Artigli delle Aquile",
                    "effectmsg"=>"Gli artigli strappano brandelli di carne da {badguy} causandogli `^{damage}`) punti danno.",
                    "effectnodmgmdg"=>"L'aquila cerca di artigliare {badguy} ma `\$LO MANCA!!",
                    "rounds"=>5,
                    "wearoff"=>"L'aquila torna al suo nido.",
                    "maxbadguydamage"=>$session['user']['level']*2,
                    "atkmod"=>round($session['user']['level']/5)+1,
                    "activate"=>"offense"
                    );
                break;
            case 5:
                $goodguy[bufflist]['na5'] = array(
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
            $goodguy[natureuses]-=$_GET[l];
        }else{
            $goodguy[bufflist]['na0'] = array(
                "startmsg"=>"`nNon c'è nessun animale nei dintorni'.`n`n",
                "rounds"=>1,
                "activate"=>"roundstart"
            );
        }
    }
    if ($_GET[skill]=="WE"){
        if ($goodguy[weatheruses] >= $_GET[l]){
            $creaturedmg = 0;
            switch($_GET[l]){
            case 1:
                $goodguy[bufflist]['we1'] = array(
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
                $goodguy[bufflist]['we2'] = array(
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
                $goodguy[bufflist]['we3'] = array(
                    "startmsg"=>"`n`^Inizia a piovere a dirotto.`n`n",
                    "name"=>"`%Pioggia",
                    "rounds"=>5,
                    "wearoff"=>"ha smesso di piovere.",
                    "effectmsg"=>"a {badguy} non piace bagnarsi e il suo attacco è ridotto.",
                    "badguydefmod"=>0,
                    "badguyatkmod"=>0.5,
                    "activate"=>"offense,defense"
                    );
                break;
            case 5:
                $goodguy[bufflist]['we5'] = array(
                    "startmsg"=>"`n`^Inizia a fare freddo.`n`n",
                    "name"=>"`%Gelo Polare",
                    "minioncount"=>round($session[user][level]/3)+1,
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
            $goodguy[weatheruses]-=$_GET[l];
        }else{
            $goodguy[bufflist]['we0'] = array(
                "startmsg"=>"`nIl clima è imprevedibile.`n`n",
                "rounds"=>1,
                "activate"=>"roundstart"
            );
        }
    }
    //Sook: nuove Specialità
    if ($_GET['skill']=="EL"){
        if ($goodguy['elementaleuses'] >= $_GET[l]){
            $creaturedmg = 0;
            switch($_GET[l]){
            case 1:
                $goodguy['bufflist']['el1'] = array(
                        "startmsg"=>"`^Evochi un Elementale d'Aria per creare una potente raffica di vento.`n`n",
                        "name"=>"`^Vento",
                        "rounds"=>8,
                        "wearoff"=>"La tua vittima non è più bloccata dal vento.",
                        "roundmsg"=>"{badguy} viene respinto dal vento e non riesce nemmeno a combattere.",
                        "badguyatkmod"=>0.6,
                        "activate"=>"roundstart");
                break;
            case 2:
                $goodguy['bufflist']['el2'] = array(
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
                $goodguy['bufflist']['el3'] = array(
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
                $goodguy['bufflist']['el5'] = array(
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
            $goodguy['elementaleuses']-=$_GET[l];
        }else{
            $goodguy['bufflist']['el0'] = array(
                "startmsg"=>"`nProvi ad evocare un elementale ma riesce a creare solo una pila di fango.  {badguy} si è divertito per lo spettacolino gratuito che gli hai offerto.`n`n",
                "rounds"=>1,
                "activate"=>"roundstart"
            );
        }
    }
    if ($_GET['skill']=="BB"){
        if ($goodguy['barbarouses'] >= $_GET[l]){
            $creaturedmg = 0;
            switch($_GET[l]){
            case 1:
                $goodguy['bufflist']['bb1'] = array(
                        "startmsg"=>"`6La gente non si aspetta che i Barbari riescano a schivare i colpi, ecco perchè ci riescono!`n`n",
                        "name"=>"`^Schivata Misteriosa",
                        "roundmsg"=>"con un balzo rapissimo riesci ad allontanarti da {badguy}, che ti colpisce di striscio",
                        "rounds"=>6,
                        "wearoff"=>"Inizi a sentirti stanco...",
                        "defmod"=>1.4,
                        "activate"=>"roundstart");
                break;
            case 2:
                $goodguy['bufflist']['bb2'] = array(
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
                $goodguy['bufflist']['bb3'] = array(
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
                $goodguy['bufflist']['bb5'] = array(
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
            $goodguy['barbarouses']-=$_GET[l];
        }else{
            $goodguy['bufflist']['bb0'] = array(
                "startmsg"=>"`nTi concentri per raccogliere la tua rabbia, ma non riesci a smettere di ridire pensando ad una delle barzellette di Cedrik.`n`n",
                "rounds"=>1,
                "activate"=>"roundstart"
            );
        }
    }
    if ($_GET['skill']=="BA"){
        if ($goodguy['bardouses'] >= $_GET[l]){
            $creaturedmg = 0;
            switch($_GET[l]){
            case 1:
                $goodguy['bufflist']['ba1'] = array(
                        "startmsg"=>"`5Canti una canzone del tuo impegno di audace avventuriero!  {badguy} non è più motivato!`n`n",
                        "name"=>"`%Fascinazione",
                        "roundmsg"=>"{badguy} è affascinato dalla tua canzone",
                        "rounds"=>8,
                        "wearoff"=>"La tua voce si sta facendo roca...",
                        "badguyatkmod"=>0.5,
                        "activate"=>"defense");
                break;
            case 2:
                $goodguy['bufflist']['ba2'] = array(
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
                $goodguy['bufflist']['ba3'] = array(
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
                $goodguy['bufflist']['ba5'] = array(
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
            $goodguy['bardouses']-=$_GET[l];
        }else{
            $goodguy['bufflist']['ba0'] = array(
                "startmsg"=>"`nCerchi di comporre una canzone, ma ti manca l'ispirazione giusta.  Perciò, nessuno rimane affascinato dalle tue qualità di artista.`n`n",
                "rounds"=>1,
                "activate"=>"roundstart"
            );
        }
    }
    //Sook, fine nuove specialità
    if ($goodguy[hitpoints]>0 && $badguy[hitpoints]>0) {
        output ("`\$`c`b~ ~ ~ Combattimento ~ ~ ~`b`c`0");
        output("`@Incontri `^$badguy[name]`@ che ti attacca con `%$badguy[weapon]`@");
        // Let's display what buffs the opponent is using - oh yeah
        $buffs="";
        $disp[bufflist]=$badguy[bufflist];
        reset($disp[bufflist]);
        while (list($key,$val)=each($disp[bufflist])){
            $buffs.=" `@e `#$val[name] `7($val[rounds] round)";
        }
        if (count($disp[bufflist])==0){
            $buffs.=appoencode("",true);
        }
        output("$buffs");
        output(" `@!`0`n`n");
        output("`2Livello: `6$badguy[level]`0`n");
        output("`2`bInizio del round:`b`n");
        output("`2$badguy[name]`2's Hitpoints: `6$badguy[hitpoints]`0`n");
        output("`2TUOI Hitpoints: `6$goodguy[hitpoints]`0`n");
    }
    reset($goodguy[bufflist]);
    while (list($key,$buff)=each($goodguy['bufflist'])){
        $buff[used]=0;
    }
    if ($badguy[hitpoints]>0 && $goodguy[hitpoints]>0){
        $buffset = activate_buffs("roundstart");
        $creaturedefmod=$buffset['badguydefmod'];
        $creatureatkmod=$buffset['badguyatkmod'];
        $atkmod=$buffset['atkmod'];
        $defmod=$buffset['defmod'];
    }
    if ($badguy[hitpoints]>0 && $goodguy[hitpoints]>0){
        $adjustedcreaturedefense = $badguy[defense];
        $creatureattack = $badguy[attack]*$creatureatkmod;
        $adjustedselfdefense = ($goodguy[defense] * $adjustment * $defmod);
        while($creaturedmg==0 && $selfdmg==0){
            $atk = $goodguy[attack]*$atkmod;
            if (e_rand(1,20)==1) $atk*=3;
            $patkroll = e_rand(0,$atk);
            $catkroll = e_rand(0,$adjustedcreaturedefense);
            $creaturedmg = 0-(int)($catkroll - $patkroll);
            if ($creaturedmg<0) {
                $creaturedmg = (int)($creaturedmg/2);
                $creaturedmg = round($buffset[badguydmgmod]*$creaturedmg,0);
            }
            if ($creaturedmg > 0) {
                $creaturedmg = round($buffset[dmgmod]*$creaturedmg,0);
            }
            $pdefroll = e_rand(0,$adjustedselfdefense);
            $catkroll = e_rand(0,$creatureattack);
            $selfdmg = 0-(int)($pdefroll - $catkroll);
            if ($selfdmg<0) {
                $selfdmg=(int)($selfdmg/2);
                $selfdmg = round($selfdmg*$buffset[dmgmod], 0);
            }
            if ($selfdmg > 0) {
                $selfdmg = round($selfdmg*$buffset[badguydmgmod], 0);
            }
        }
    }
    if ($badguy[hitpoints]>0 && $goodguy[hitpoints]>0){
        $buffset = activate_buffs("offense");
        if ($atk > $goodguy[attack]) {
            if ($atk > $goodguy[attack]*3){
                if ($atk>$goodguy[attack]*4){
                    output("`&`bEsegui una <font size='+1'>MEGA</font> mossa d'attacco!!!`b`n",true);
                }else{
                    output("`&`bEsegui una DOPPIA mossa di potenza!!!`b`n");
                }
            }else{
                if ($atk>$goodguy[attack]*2){
                    output("`&`bEsegui una mossa potente!!!`b`0`n");
                }elseif ($atk>$goodguy['attack']*1.25){
                    output("`7`bEsegui una mossa di potenza aumentata!`b`0`n");
                }
            }
        }
        if ($creaturedmg==0){
            output("`4Cerchi di colpire `^$badguy[name]`4 ma `\$MANCHI!`n");
            $message=$message."`^$goodguy[name]`4 cerca di colpirti ma `\$TI MANCA!`n";
            if ($badguy[hitpoints]>0 && $goodguy[hitpoints]>0) process_dmgshield($buffset[dmgshield], 0);
            if ($badguy[hitpoints]>0 && $goodguy[hitpoints]>0) process_lifetaps($buffset[lifetap], 0);
        }else if ($creaturedmg<0){
            output("`4Cerchi di colpire `^$badguy[name]`4 ma `\$CONTRATTACCA `4e ti causa `\$".(0-$creaturedmg)."`4 punti di danno!`n");
            $message=$message."`^$goodguy[name]`4 cerca di colpirti ma tu `^CONTRATTACCHI`4 e gli causi `^".(0-$creaturedmg)."`4 punti di danno!`n";
            $badguy['diddamage']=1;
            $goodguy[hitpoints]+=$creaturedmg;
            if ($badguy[hitpoints]>0 && $goodguy[hitpoints]>0) process_dmgshield($buffset[dmgshield],-$creaturedmg);
            if ($badguy[hitpoints]>0 && $goodguy[hitpoints]>0) process_lifetaps($buffset[lifetap],$creaturedmg);
        }else{
            output("`4Colpisci `^$badguy[creaturename]`4 causando `^$creaturedmg`4 punti di danno!`n");
            $message=$message."`^$goodguy[name]`4 ti colpisce causandoti `\$$creaturedmg`4 punti di danno!`n";
            $badguy[hitpoints]-=$creaturedmg;
            if ($badguy[hitpoints]>0 && $goodguy[hitpoints]>0) process_dmgshield($buffset[dmgshield],-$creaturedmg);
            if ($badguy[hitpoints]>0 && $goodguy[hitpoints]>0) process_lifetaps($buffset[lifetap],$creaturedmg);
        }

    /* from hardest punch mod by anpera -- removed for now
        if ($creaturedmg>$session[user][punch]){
            $session[user][punch]=$creaturedmg;
            output("`@`b`c--- DAS WAR DEIN BISHER HÄRTESTER SCHLAG! ---`c`b`n");
        }
    */

    }
    if ($goodguy[hitpoints]>0 && $badguy[hitpoints]>0) $buffset = activate_buffs("defense");
    expire_buffs();
    if ($goodguy[hitpoints]>0 && $badguy[hitpoints]>0){
        output("`2`bFine del Round:`b`n");
        output("`2$badguy[name]`2's Hitpoints: `6$badguy[hitpoints]`0`n");
        output("`2TUOI Hitpoints: `6".$goodguy[hitpoints]."`0`n");
    }

    $goodguy[bufflist]=serialize($goodguy[bufflist]);
    $badguy[bufflist]=serialize($badguy[bufflist]);
    if ($row[acctid1]){ // battle still in DB? Result of round:
        if ($badguy[hitpoints]>0 and $goodguy[hitpoints]>0){
            $message=addslashes($message);
            if ($row[turn]==1) $sql = "UPDATE pvp SET hp1=$goodguy[hitpoints],hp2=$badguy[hitpoints],thieveryuses1=$goodguy[thieveryuses],darkartuses1=$goodguy[darkartuses],magicuses1=$goodguy[magicuses],militareuses1=$goodguy[militareuses],mysticuses1=$goodguy[mysticuses],tacticuses1=$goodguy[tacticuses],rockskinuses1=$goodguy[rockskinuses],rhetoricuses1=$goodguy[rhetoricuses],muscleuses1=$goodguy[muscleuses],natureuses1=$goodguy[natureuses],weatheruses1=$goodguy[weatheruses],elementaleuses1=$goodguy[elementaleuses],barbarouses1=$goodguy[barbarouses],bardouses1=$goodguy[bardouses],bufflist1='".addslashes($goodguy[bufflist])."',lastmsg='$message',turn=2 WHERE acctid1=".$session[user][acctid]."";
            if ($row[turn]==2) $sql = "UPDATE pvp SET hp1=$badguy[hitpoints],hp2=$goodguy[hitpoints],thieveryuses2=$goodguy[thieveryuses],darkartuses2=$goodguy[darkartuses],magicuses2=$goodguy[magicuses],militareuses2=$goodguy[militareuses],mysticuses2=$goodguy[mysticuses],tacticuses2=$goodguy[tacticuses],rockskinuses2=$goodguy[rockskinuses],rhetoricuses2=$goodguy[rhetoricuses],muscleuses2=$goodguy[muscleuses],natureuses2=$goodguy[natureuses],weatheruses2=$goodguy[weatheruses],elementaleuses2=$goodguy[elementaleuses],barbarouses2=$goodguy[barbarouses],bardouses2=$goodguy[bardouses],bufflist2='".addslashes($goodguy[bufflist])."',lastmsg='$message',turn=1 WHERE acctid2=".$session[user][acctid]."";
            db_query($sql) or die(db_error(LINK));
            if (db_affected_rows(LINK)<=0) redirect("pvparena.php");
            output("`n`n`2Stai aspettando la mossa del tuo avversario.");
            addnav("Aggiorna","pvparena.php");
        }else if ($badguy[hitpoints]<=0){
            $win=$badguy[level]*20+$goodguy[level]*20;
            $exp=$badguy[level]*10-(abs($goodguy[level]-$badguy[level])*10);
            if ($badguy[level]<=$goodguy[level]){
                $session[user][battlepoints]+=2;
            }else{
                $session[user][battlepoints]+=3*($badguy[level]-$goodguy[level]);
            }
            output("`n`&Immediatamente prima del tuo colpo finale l'arbitro interrompe il combattimento e ti dichiara vincitore!`0`n");
            output("`b`\$hai battuto $badguy[name] `\$!`0`b`n");
            output("`#Vinci il premio di `^$win`# pezzi d'oro e ");
            // $session['user']['donation']+=1; // anpera's donation system removed
            output("`^$exp`# punti esperienza!`n`0");
            $session['user']['gold']+=$win;
            $session['user']['playerfights']--;
            $session['user']['experience']+=$exp;
            $exp = round(getsetting("pvpdeflose",5)*10,0);
            $sql = "UPDATE accounts SET charm=charm-1 WHERE acctid=".$badguy['acctid']." AND charm >0";
            db_query($sql);
            $sql = "UPDATE accounts SET experience=experience-$exp WHERE (acctid =".$badguy['acctid']." AND experience>=$exp)";
            db_query($sql);
            $sql = "UPDATE accounts SET playerfights=playerfights-1 WHERE (acctid =".$badguy['acctid']." AND playerfights>0)";
            db_query($sql);
            //$sql = "UPDATE accounts SET charm=charm-1,experience=experience-$exp,playerfights=playerfights-1 WHERE acctid=$badguy[acctid]";
            $mailmessage = "`^$goodguy[name]`2 ti ha battuto con la sua `^".$goodguy['weapon']."`2 nell'arena!"
                ." `n`n%o aveva `^".$goodguy['hitpoints']."`2 HitPoints al termine del combattimento."
                ." `n`nHai perso `\$$exp`2 punti esperienza.";
            //$mailmessage = str_replace("%p",($session['user']['sex']?"sua":"sua"),$mailmessage);
            $mailmessage = str_replace("%o",($session['user']['sex']?"Lei":"Lui"),$mailmessage);
            systemmail($badguy['acctid'],"`2Sei stato sconfitto nell'Arena",$mailmessage);
            addnews("`\$$goodguy[name]`6 ha battuto `\$$badguy[name]`6 in un  duello nell'`3Arena`6!");
            $sql = "DELETE FROM pvp WHERE acctid1=".$session[user][acctid]." OR acctid2=".$session[user][acctid];
            db_query($sql) or die(db_error(LINK));
        }else if ($goodguy[hitpoints]<=0){
            $exp=$badguy[level]*10-(abs($goodguy[level]-$badguy[level])*10);
            $win=$badguy[level]*20+$goodguy[level]*20;
            if ($badguy[level]>=$goodguy[level]){
                $points=2;
            }else{
                $points=3*($goodguy[level]-$badguy[level]);
            }
            $sql = "SELECT taunt FROM taunts ORDER BY rand(".e_rand().") LIMIT 1";
            $result = db_query($sql) or die(db_error(LINK));
            $taunt = db_fetch_assoc($result);
            $taunt = str_replace("%s",($session[user][sex]?"sua":"suo"),$taunt[taunt]);
            $taunt = str_replace("%o",($session[user][sex]?"lei":"lui"),$taunt);
            $taunt = str_replace("%p",($session[user][sex]?"her":"his"),$taunt);
            $taunt = str_replace("%x",($session[user][weapon]),$taunt);
            $taunt = str_replace("%X",$badguy[weapon],$taunt);
            $taunt = str_replace("%W",$badguy[name],$taunt);
            $taunt = str_replace("%w",$session[user][name],$taunt);
            $badguy[acctid]=(int)$badguy[acctid];
            $badguy[creaturegold]=(int)$badguy[creaturegold];
            systemmail($badguy[acctid],"`2 Sei stato vittorioso nell'arena! ","`^".$session[user][name]."`2 ha perso il combattimento nell'arena!`n`nHai guadagnato `^$exp`2 punti esperienza e hai vinto `^$win`2 pezzi d'oro!");
            $sql = "UPDATE accounts SET gold=gold+$win,experience=experience+$exp,donation=donation+1,playerfights=playerfights-1,battlepoints=battlepoints+$points WHERE acctid=$badguy[acctid]";
            db_query($sql);
            $exp = round(getsetting("pvpdeflose",5)*10,0);
            $session[user][experience]-=$exp;
            $session['user']['playerfights']--;
            output("`n`b`&Sei stato sconfitto da `%$badguy[name]`&!!!`b`n");
            output("$taunt");
            output("`n`4Hai perso `^$exp punti esperienza e un po' del tuo onore!`n");
            if ($session[user][charm]>0) $session[user][charm]--;
            addnews("`\$$badguy[name]`6 ha battuto `\$$goodguy[name]`6 in un duello nell'`3Arena`6!");
            $sql = "DELETE FROM pvp WHERE acctid1=".$session[user][acctid]." OR acctid2=".$session[user][acctid];
            db_query($sql) or die(db_error(LINK));
        }
    }else{
        output("`6Il tuo combattimento è terminato prima ancora di iniziare. La tassa d'iscrizione verrà utilizzata per la manutenzione dell'arena.");
    }
    addnav("Torna al Villaggio","village.php");
}else if ($_GET[op]==""){
    $sql="SELECT * FROM pvp WHERE acctid1=".$session[user][acctid]." OR acctid2=".$session[user][acctid]."";
    $result = db_query($sql) or die(db_error(LINK));
    $row = db_fetch_assoc($result);
    $text=0;
    if($row[acctid1]==$session[user][acctid] && $row[turn]==0){
        $text=1;
        output("`6Ti ricordi di una sfida con `&$row[name2] `6e ti incammini verso l'arena. Ma il tuo avversario sembra non essere nei paraggi.`n");
        addnav("Ritira la Sfida","pvparena.php?op=back&id=$row[acctid2]");
        if (@file_exists("battlearena.php")) addnav("Sfida il Gladiatore","battlearena.php"); // ONE arena for TWO things - if installed ;)
        addnav("Torna al Villaggio","village.php");
        stats($row);
    }else if($row[acctid1]==$session[user][acctid] && $row[turn]==1){
        stats($row);
        arenanav($row);
    }else if($row[acctid1]==$session[user][acctid] && $row[turn]==2){
        stats($row);
        output("`6Il tuo avversario `&$row[name2]`6 non ha ancora compiuto la sua mossa.`n`n");
        $text=1;
        if (@file_exists("battlearena.php")) addnav("Sfida il Gladiatore","battlearena.php");
        addnav("Torna al Villaggio","village.php");
    }else if($row[acctid2]==$session[user][acctid] && $row[turn]==0){
        output("`6Sei stato sfidato da  `&$row[name1] `6. Se accetti la sfida, devi pagare `^$cost`6 pezzi d'oro come tassa d'iscrizione.`n");
        addnav("Sei stato sfidato da $row[name1]");
        addnav("Accetta","pvparena.php?op=accept");
        addnav("Rifiuta","pvparena.php?op=deny&id=$row[acctid1]");
    }else if($row[acctid2]==$session[user][acctid] && $row[turn]==1){
        stats($row);
        output("`6Il tuo avversario `&$row[name1]`6 non ha ancora compiuto la sua mossa.`n`n");
        $text=1;
        if (@file_exists("battlearena.php")) addnav("Sfida il Gladiatore","battlearena.php");
        addnav("Torna al Villaggio","village.php");
    }else if($row[acctid2]==$session[user][acctid] && $row[turn]==2){
        stats($row);
        arenanav($row);
    }else{
        $text=1;
        addnav("Sfida un Giocatore","pvparena.php?op=challenge");
        if (@file_exists("battlearena.php")) addnav("Sfida il Gladiatore","battlearena.php");
        addnav("Torna al Villaggio","village.php");
    }
    if($text==1){
        checkday();
        addcommentary();
        addnav("Aggiorna","pvparena.php");
        output("`6Entri nell'enorme arena di fianco al campo d'allenamento. All'interno alcuni guerrieri affinano le loro abilità speciali in sfide, ");
        output("per determinare chi sia il migliore. Onore e una buona posizione nella classifica è tutto ciò di cui si tratta qui.`n");
        $sql="SELECT * FROM pvp WHERE acctid1 AND acctid2 AND turn>0";
        $result = db_query($sql) or die(db_error(LINK));
        if (db_num_rows($result)){
            output(" Osservi le sfide che si succedono per un po'.`nQuesti guerrieri stanno combattendo proprio ora:`n`n<table border='0' cellpadding='3' cellspacing='0'><tr><td align='center'>`bSfidante`b</td><td align='center'>`bSfidato`b</td><td align='center'>`bRisultato (HP)`b</td></tr>",true);
            $countrow = db_num_rows($result);
            for ($i=0; $i<$countrow; $i++){
            //for ($i=0;$i<db_num_rows($result);$i++){
                $row = db_fetch_assoc($result);
                output("<tr class='".($i%2?"trlight":"trdark")."'><td>$row[name1]</td><td>$row[name2]</td><td>$row[hp1] : $row[hp2]</td></tr>",true);
            }
            output("</table>`n`n",true);
        }else{
            output("`n`n`6Nessun guerriero sta combattendo adesso.`n`n");
        }
        viewcommentary("pvparena","Urla:",20,10,"urla");
    }
}

page_footer();

// this is not the end ;)
?>