<?php
page_header("Vice Sceriffo");
output("`c`b`&Il Vice Sceriffo`0`b`c`n`n");
if ($_GET['op']==""){
        $badguy = array(        "creaturename"=>"`@Vice Sceriffo `0"
                                ,"creaturelevel"=>0
                                ,"creatureweapon"=>"Manganello mazzuola criminali"
                                ,"creatureattack"=>1
                                ,"creaturedefense"=>2
                                ,"creaturehealth"=>2
                                ,"diddamage"=>0);
                                //buff Vice Sceriffo up a little
                                $userlevel=$session['user']['level'];
                                $userattack=$session['user']['attack'];
                                $userhealth=$session['user']['maxhitpoints']*3;
                                $userdefense=$session['user']['defense'];
                                $x=e_rand(2,4);
                                $badguy[creaturelevel]+=$userlevel;
                                $badguy[creaturelevel]+=$x;
                                $y=e_rand(6,15);
                                $badguy[creatureattack]+=$userattack;
                                $badguy[creatureattack]+=$y;
                                $z=e_rand(1,($userhealth*.6));
                                $badguy[creaturehealth]=$userhealth;
                                $badguy[creaturehealth]+=$z;
                                $t=e_rand(6,15);
                                $badguy[creaturedefense]+=$userdefense;
                                $badguy[creaturedefense]+=$t;
                                $session[user][badguy]=createstring($badguy);
     if ($session['user']['evil'] < 100){
        $premio = 500 - (5 * $session['user']['evil']);
        output("`2Nel tuo girovagare per la foresta in cerca di creature da massacrare incontri il Vice-Sceriffo `n");
        output("in perlustrazione. Sta cercando tipi con una reputazione sicuramente meno candida della tua. `n");
        output("Dopo averti squadrat".($session[user][sex]?"a":"o")." per bene estrae un quadernetto e ti dice:`n`n\"`#Molto bene `@".$session['user']['name']);
        output(" `#sono felice di comunicarti che hai ".$session['user']['evil']." punti cattiveria, e che gli onesti cittadini ");
        output("di `^Rafflingate`# hanno deciso di promuovere una campagna contro il crimine, elargendo un premio a tutti ");
        output("i guerrieri che non hanno superato la soglia dei 100 punti cattiveria con un premio in oro. Grazie a loro ");
        output("guadagni $premio pezzi d'oro!!`2\"`n`n Detto ciò ti consegna un borsellino contenente il tuo premio e si avvia ");
        output("nuovamente nella foresta.`n");
        $session['user']['gold'] += $premio;
        debuglog("incontra il Vice Sceriffo che gli consegna $premio oro. EVIL=".$session['user']['evil']);
        addnav("Continua","forest.php");
     }else{
         output("`4Mentre ti stai facendo gli affari tuoi nella foresta, senti un rumore e quando ti giri per vedere `n");
         output("chi l'abbia prodotto ti trovi faccia a faccia con il Vice Sceriffo. Non hai molte chance di fuggire ma potresti`n");
         output("sempre tentare di scappare oppure .... Cosa fai ?");
         addnav("Combatti","forest.php?op=fight");
         addnav("Scappa","forest.php?op=run");
         $session['user']['specialinc']="vicescerif.php";
     }
}
if ($_GET['op']=='run') {
         output("`#\"`%Ah ah ah, non hai nessuna via di fuga, criminale!`#\"`n dice il Vice-Sceriffo.");
         addnav("Combatti","forest.php?op=fight");
}
if ($_GET['op']=='fight') {
        $battle=true;
}
if ($battle) {
    include("battle.php");
    $session['user']['specialinc']="vicescerif.php";
        if ($victory){
            $badguy=array();
            $session['user']['badguy']="";
            output("`n`3Dopo un duro scontro, sei riuscito a battere il `@Vice Sceriffo`3. `nLo abbandoni sul terreno ");
            output("privo di sensi e un pò ammaccato te ne torni, liber".($session[user][sex]?"a":"o").", sui tuoi passi.`n");
            output("`#O almeno fino al prossimo incontro con lui o con lo Sceriffo.`n");
            debuglog("ha sconfitto il Vice Sceriffo ed è riuscito a sfuggirgli. EVIL=".$session['user']['evil']);
            addnav("`@Torna alla Foresta","forest.php");
            $session['user']['specialinc']="";
        } elseif ($defeat){
            $badguy=array();
            $session[user][badguy]="";
            debuglog("è stato sconfitto dal Vice Sceriffo e portato in prigione. EVIL=".$session['user']['evil']);
            output("`n`7Il `@Vice Sceriffo`7 ti ha sconfitt".($session[user][sex]?"a":"o")." !! Pien".($session[user][sex]?"a":"o")." di lividi e dolorante ti scorta fino alla ");
            output("prigione, dove sconterai la tua pena.");
            $session['user']['hitpoints']=$session['user']['maxhitpoints'];
            $session[user][jail]=1;
            $name=$session[user][name];
            $session['user']['specialinc']="";
            addnews("`1$name `^è stat".($session[user][sex]?"a":"o")." catturat".($session[user][sex]?"a":"o")." nella foresta e sbattut".($session[user][sex]?"a":"o")." in prigione dal Vice-Sceriffo!");
            addnav("Continua","constable.php?op=twiddle");
        } else {
            fightnav(true,true);
        }
}
page_footer();
?>