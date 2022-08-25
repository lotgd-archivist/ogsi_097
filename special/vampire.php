<?php
/*************************
Vampire's Lair
Special Event/Add-on
for LoGD
by Mike Counts (genmac)
- Dec. 2003

Install:

-Special event: copy vampire.php into /special directory.

Add-on: copy vampire.php into main LoGD directory, add
link from village.php or wherever you wish.
*************************/

require_once "common.php";
if (!isset($session)) exit();

$vampstrlink="vampire.php?op=str";
$vampdeflink="vampire.php?op=def";
$vampwealthlink="vampire.php?op=wealth";
$vampconlink="vampire.php?op=continue";
$vampleavelink="village.php";
if ($session['user']['race'] == 8) {
   $multiplier = 1;
}else{
    $multiplier = 1.2;
}
if(strstr($_SERVER['REQUEST_URI'],"forest")!=FALSE){
    $userinforest = TRUE;
    $session['user']['specialinc']="vampire.php";
    $vampstrlink="forest.php?op=str";
    $vampdeflink = "forest.php?op=def";
    $vampwealthlink = "forest.php?op=wealth";
    $vampconlink = "forest.php?op=continue";
    $vampleavelink="forest.php?op=leave";
}
$lifecost = round($session['user']['level'] * $multiplier);
if ($session['user']['reincarna']>0) {
    $lifecost*=$session['user']['reincarna'];
    if ($session['user']['reincarna'] == 1) $lifecost=round($lifecost*=1.5);
}
$gemgain = $session['user']['level'];
$goldgain = $session['user']['level']*1000;
if($_GET['op']=="" || $_GET['op']=="search"){
    if($inforest) $session['user']['specialinc']="vampire.php";
    page_header("Un Sentiero buio");
    output("`^`c`bUn Sentiero Buio`b`c");
    output("`n`n`n`xMentre vaghi nella foresta alla continua ricerca di nuove avventure, percorri un sentiero ");
    output("contorto e tenebroso. Ad un tratto ti ritrovi avvolto in una foschia oscura mentre un senso di terrore ");
    output("si impossessa di te e un freddo brivido penetra nelle tue ossa. Dita simili ad artigli, dotate di una ");
    output("forza sovraumana, si serrano intorno al tuo braccio, poi uno strattone improvviso che ti devasta la ");
    output("giuntura della spalla, ti solleva da terra e ti proietta al suolo come una bambola di stracci.`n");
    output("Mentre atterri pesantemente a terra restando senza aria nei polmoni, una figura ossuta ti si ");
    output("materializza di fronte, una bocca si spalanca snudando zanne d'avorio puntate alla tua gola e un ");
    output("nauseabondo odore di sangue e putrescenza ti prende la gola facendoti quasi soffocare.`n`n");
    output("Oserai affrontare l'immonda creatura che ti trovi davanti?");
    addnav("`@Avanzi Coraggiosamente","forest.php?op=continue");
    addnav("`\$Scappi Terrorizzato","forest.php?op=leave");
    $session['user']['specialinc']="vampire.php";
}else if($_GET['op']=="continue"){
    page_header("Il Regno del Vampiro");
    output("`^`c`bIl Regno del Vampiro`b`c");
    output("`n`n`xUna presenza demoniaca si manifesta. Sei tremante di paura davanti a questo antico ");
    output("potere, ed Egli ti parla:`n`n\"`X Mortale, percepisco del potenziale in te. Invecchiando, la mia sete ");
    output("di caccia svanisce. In cambio di una parte della tua forza vitale, ti garantisco poteri al di la ");
    output("di ogni tua fantasia.`x\"`n`nCapisci di trovarti davanti ad un `\$Vampiro`x, indeciso su cosa fare.`n`n");
    $lifecost1 = $lifecost;
    if ($session['user']['oggetto'] != 0){
        $ogg = $session['user']['oggetto'];
        $sql = "SELECT * FROM oggetti WHERE id_oggetti = $ogg";
        $result = db_query($sql) or die(db_error(LINK));
        $row = db_fetch_assoc($result);
        if ($row['hp_help'] != 0){
            $lifecost1 = $lifecost+$row['hp_help'];
        }
    }
    if ($session['user']['superuser'] > 2){
        print("Lifecost : ".$lifecost."  Lifecost1 : ".$lifecost1." helpHP : ".$row['hp_help']);
    }
    if(($session['user']['maxhitpoints']-$lifecost1)>=($session['user']['level']*10)) {
        addnav("F?`\$Scambia ".$lifecost." punti vita per Forza",$vampstrlink);
        addnav("D?`@Scambia ".$lifecost." punti vita per Difesa",$vampdeflink);
        addnav("R?`^Scambia ".$lifecost." punti vita per Ricchezza",$vampwealthlink);
    } else{
        output("`\$Purtroppo non hai HP a sufficienza da barattare con il vampiro! Scenderesti sotto il minimo ");
        output("consentito di 10 HP per livello.`nL'unica possibilità è fuggire ...`n");
    }
    addnav("S?`#Scappa terrorizzato",$vampleavelink);
}else if($_GET['op']=="leave"){
    $session['user']['specialinc']="";
    output("`n`n`n`xAbbandoni questo luogo maledetto il più velocemente possibile.");
    if($userinforest){ addnav("`%Ritorna alla Foresta","forest.php"); }
    addnav("`@Torna al Villaggio","village.php");
}else if($_GET['op']=="str" || $_GET['op']=="def" || $_GET['op']=="wealth"){
    $session['user']['maxhitpoints'] -= $lifecost;
    if($session['user']['hitpoints']>$session['user']['maxhitpoints']){
        $session['user']['hitpoints']=$session['user']['maxhitpoints'];
    }
    page_header("Il Regno del Vampiro");
    output("`^`c`bIl Regno del Vampiro`b`c");
    output("`n`n`n`xInizi a tremare quando il vampiro affonda i canini nel tuo collo. Senti la tua vita ");
    output("fluire dalla ferita. In cambio, il vampiro lancia un incantesimo sulla tua tremante figura.`n`n`n`@");
    if($_GET['op']=="str"){
        $session['user']['attack']++;
        $session['user']['bonusattack']++;
        debuglog("paga $lifecost HP permanenti per 1 punto attacco permanente dal Vampiro");
        output("Il tuo attacco è aumentato `6PERMANENTEMENTE`@ di `^1`@, ma al costo di`$ ".$lifecost." `@punti vita.");
    }else if($_GET['op']=="def"){
        $session['user']['defence']++;
        $session['user']['bonusdefence']++;
        debuglog("paga $lifecost HP permanenti per 1 punto difesa permanente dal Vampiro");
        output("La tua difesa è aumentata `6PERMANENTEMENTE`@ di `^1`@, ma al costo di`$ ".$lifecost." `@punti vita.");
    }else if($_GET['op']=="wealth"){
        $session['user']['gold'] += $goldgain;
        $session['user']['gems'] += $gemgain;
        debuglog("paga $lifecost HP permanenti per $goldgain oro e $gemgain gemme dal Vampiro");
        output("Guadagni `^".$goldgain." `@pezzi d'oro e `%".$gemgain." `@gemme, ma al costo di`$ ".$lifecost." `@punti vita.");
    }
    addnav("`@Abbandona questo Luogo",$vampleavelink);
} else {
    output("`\$Dannata strega ti ha portato ai margini del paese .... Che sfortuna che hai!");
    addnav("`@Torna al Villaggio","village.php");
}
page_footer();