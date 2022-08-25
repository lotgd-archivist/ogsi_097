<?php
/**
Voodoo Priestess
Originally by lonnyl69
Original URL: http://sourceforge.net/tracker/index.php?func=detail&aid=878436&group_id=76499&atid=547281
Edited by GenmaC

Changes:
% Now uses GET to process spells, curses, shortened by a lot
% Now *requires* the special vars modification by yippy (get it here: http://sourceforge.net/tracker/index.php?func=detail&aid=741505&group_id=76499&atid=547281 )
Sook: Usa una tabella a se stante, "voodoo"
**/

/*TABELLA SQL
CREATE TABLE `voodoo` (

`id` BIGINT( 20 ) NOT NULL AUTO_INCREMENT ,
`caster` INT( 10 ) NOT NULL ,
`target` INT( 10 ) NOT NULL ,
`spell` TEXT NOT NULL ,
PRIMARY KEY ( `id` )
) TYPE = MYISAM
*/

require_once "common.php";
checkday();
//intro
page_header("La Sacerdotessa Voodoo");
$session['user']['locazione'] = 190;
$myname=$session[user][name];
$maxvoodoo=0; //numero massimo di incantesimi lanciabili contemporaneamente, 0 per infiniti

//controllo punti temporanei, se è possibile toglierli con il voodoo, in base al DK (Sook)
function controllopunti($a)
{
    $sqlbers="SELECT dragonkills, attack, defence, bonusattack, bonusdefence, weapondmg, armordef, level FROM accounts WHERE acctid=$a";
    $resultbers = db_query($sqlbers);
    $rowbers = db_fetch_assoc($resultbers);
    $temporanei = $rowbers[attack] + $rowbers[defence] - $rowbers[bonusattack] - $rowbers[weapondmg] - $rowbers[bonusdefence] - $rowbers[armordef] - (2*$rowbers[level]);
    switch ($rowbers[dragonkills]) {
        case 0:
             $soglia=10;
        break;
        case 1:
             $soglia=15;
        break;
        case 2:
             $soglia=20;
        break;
        case 3:
             $soglia=25;
        break;
        default:
             $soglia=30;
        break;
    }
    if ($temporanei > $soglia) {
        return(1);
    } else {
        return(0);
    }
}
//controllo costo del risucchio in base al DK (Sook)
function controllocosto($a)
{
    $sqlbers="SELECT dragonkills, reincarna FROM accounts WHERE acctid=$a";
    $resultbers = db_query($sqlbers);
    $rowbers = db_fetch_assoc($resultbers);
    $costovoodoo = 10 + $rowbers[dragonkills] * 10 + $rowbers[reincarna] * 100;
    if ($costovoodoo > 100) $costovoodoo = 100;
    return $costovoodoo;
}

//conto di incantesimi già lanciati dal player
$sqlm= "SELECT * FROM voodoo WHERE caster = ".$session['user']['acctid'];
$resultm = db_query($sqlm) or die(db_error(LINK));
$rowm = db_fetch_assoc($resultm);
if ($_GET[op]==""){
    if (count($rowm)>$maxvoodoo && $maxvoodoo>0){
            output("`@`c`bLa Sacerdotessa Voodoo`b`c`n");
            output("'3Lei guarda verso di te, e ti dice...`n");
            output("`7Benvenuto $myname.`n`n");
            output("`@Ho già lanciato $maxvoodoo incantesim".($maxvoodoo=1?"o":"i") ." per te oggi, ritorna da me quando avranno avuto effetto.");
            addnav("Ritorna al Villaggio","village.php");
    }
    else{
        output("`@`c`bLa Sacerdotessa Voodoo`b`c`n");
        output("`&`cEntri nella sua capanna fatta con erba, e scorgi una stanca signora anziana che siede al centro di un circolo magico, fatto di frammenti di strane ossa e di rocce.`c`n`n");
        output("`3Lei guarda verso di te, e ti dice:`n");
        output("`3\"`7Benvenuto $myname.");
        output("`7Sono una sacerdotessa Voodoo, e posso lanciare una maledizione o un incantesimo su chiunque tu desideri.`n");
        output("`7Per un adeguato compenso, è ovvio.`3\"`n`n");
//aggiunta cattiveria, Sook (parte 1)
        output("`7Sai che la magia Voodoo è malvagia e vietata dalle leggi dello sceriffo, ma sai anche che è molto potente.");

//build menu

        if ($session[user][gold] > 999){
            addnav("`%Maledizione di Povertà `^(1000 oro)","voodoo.php?op=cast&spell=poverty&cost=1000");
        }
        if ($session[user][gold] > 1999){
            addnav("`%Maledizione di Bruttezza `^(2000 oro)","voodoo.php?op=cast&spell=ugliness&cost=2000");
        }
        if ($session[user][gold] > 2999){
            addnav("`%Maledizione di Lentezza `^(3000 oro)","voodoo.php?op=cast&spell=sleep&cost=3000");
        }
        if ($session[user][gold] > 3999){
            addnav("`%Maledizione di Ebbrezza `^(4000 oro)","voodoo.php?op=cast&spell=drunk&cost=4000");
        }
        if ($session[user][gold] > 4999){
            addnav("`%Maledizione di Debolezza `^(5000 oro)","voodoo.php?op=cast&spell=weak&cost=5000");
        }
//Sook, riduzione dei punti permanenti
        if ($session[user][gems] > 4){
            addnav("`%Riduzione di Attacco `^(5 gemme)","voodoo.php?op=cast&spell=att&cost=5");
        }
        if ($session[user][gems] > 4){
            addnav("`%Riduzione di Difesa `^(5 gemme)","voodoo.php?op=cast&spell=dif&cost=5");
        }
        if ($session[user][gems] > 99){
            addnav("`^Risucchio dell'Attacco `^(max 100 gemme)","voodoo.php?op=cast&spell=attmax&cost=100");
        }
        if ($session[user][gems] > 99){
            addnav("`^Risucchio della Difesa `^(max 100 gemme)","voodoo.php?op=cast&spell=difmax&cost=100");
        }
// uncomment 3 lines below to add curse of death
// if ($session[user][gold] > 19999){
// addnav("`\$Maledizione di Morte `^(20000 oro)","voodoo.php?op=cast&spell=death&cost=20000");
// }
        if ($session[user][gold] > 999){
            addnav("`@Incantesimo di Ricchezza `^(1000 oro)","voodoo.php?op=cast&spell=wealth&cost=1000");
        }
        if ($session[user][gold] > 1999){
            addnav("`@Incantesimo di Bellezza `^(2000 oro)","voodoo.php?op=cast&spell=beauty&cost=2000");
        }
        if ($session[user][gold] > 3999){
            addnav("`@Incantesimo di Resistenza `^(4000 oro)","voodoo.php?op=cast&spell=vitality&cost=4000");
        }
        if ($session[user][gold] > 7999){
            addnav("`@Incantesimo di Vigore `^(8000 oro)","voodoo.php?op=cast&spell=strength&cost=8000");
        }
        addnav("Ritorna al Villaggio","village.php");
    }
}

if($_GET[op]=="cast"){
    if ($_POST['name']==""){
       $spell = $_GET['spell'];
       $cost = $_GET['cost'];
       output("\"`%E su chi vorresti lanciare l'incantesimo ?`0\" chiede la sacerdotessa. \"");
       output("<form action='voodoo.php?op=cast&spell=$spell&cost=$cost' method='POST'><input name='name' value=''><input type='submit' class='button' value='Nome'>`n",true);
       addnav("","voodoo.php?op=cast&spell=$spell&cost=$cost");
       addnav("Annulla questa Maledizione","voodoo.php");
    }else{
        addnav("Annulla questa Maledizione","voodoo.php");
        $string="%";
        for ($x=0;$x<strlen($_POST['name']);$x++){
            $string .= substr($_POST['name'],$x,1)."%";
        }
        $sql = "SELECT acctid,login,name,level FROM accounts
        WHERE name LIKE '".addslashes($string)."'
        AND locked=0
        AND acctid <> '".$session['user']['acctid']."'
        AND superuser=0
        AND lastip <> '".$session['user']['lastip']."'
        ORDER BY level,login";
        $result = db_query($sql);
        output("`\$La Sacerdotessa Voodoo`) ti permette di effettuare un incantesimo sui seguenti guerrieri:`n");
        output("<table cellpadding='3' cellspacing='0' border='0'>",true);
        output("<tr class='trhead'><td>Name</td><td>Level</td></tr>",true);
        $countrow = db_num_rows($result);
        for ($i=0; $i<$countrow; $i++){
        //for ($i=0;$i<db_num_rows($result);$i++) {
                      $row = db_fetch_assoc($result);
//controllo se il bersaglio ha già un incantesimo da subire
                      $sqlt= "SELECT * FROM voodoo WHERE target = ".$row[acctid];
                      $resultt = db_query($sqlt) or die(db_error(LINK));
                      $rowt = db_fetch_assoc($resultt);
                      if (db_num_rows($resultt)==0) {
                              output("<tr class='".($i%2?"trlight":"trdark")."'><td><a href='voodoo.php?op=cast2&spell=$_GET[spell]&cost=$_GET[cost]&name=".HTMLEntities($row['login'])."'>",true);
                              output($row['name']);
                              output("</a></td><td>",true);
                              output($row['level']);
                              output("</td></tr>",true);
                              addnav("","voodoo.php?op=cast2&spell=$_GET[spell]&cost=$_GET[cost]&name=".HTMLEntities($row['login']));
                      }
    }
        output("</table>",true);
    }
}

if($_GET[op]=="cast2"){
        output("`)`c`bLa Sacerdotessa Voodoo`b`c");
        $sqlc = "SELECT name,level,acctid FROM accounts WHERE login='".$_GET['name']."'";
        $resultc = db_query($sqlc);
        if (db_num_rows($resultc)>0){
                $rowc = db_fetch_assoc($resultc);
                $sqlt= "SELECT * FROM voodoo WHERE target = ".$rowc[acctid];
                $resultt = db_query($sqlt) or die(db_error(LINK));
                $rowt = db_fetch_assoc($resultt);
                if (db_num_rows($resultt)!=0){
                        output("`4Questa persona ha già subito un incantesimo, non può riceverne un altro di oggi.");
                        addnav("Continua","voodoo.php");
                }
                else{
                    //nome esatto dell'incantesimo, Sook
                    switch ($_GET['spell']) {
                        case "poverty":
                        $spell = "Povertà";
                        $session['user']['voodoouses']+=1;
                        break;
                        case "ugliness":
                        $spell = "Bruttezza";
                        $session['user']['voodoouses']+=1;
                        break;
                        case "sleep":
                        $spell = "Lentezza";
                        $session['user']['voodoouses']+=1;
                        break;
                        case "drunk":
                        $spell = "Ebbrezza";
                        $session['user']['voodoouses']+=1;
                        break;
                        case "weak":
                        $spell = "Debolezza";
                        $session['user']['voodoouses']+=1;
                        break;
                        case "att":
                        $spell = "Riduzione di Attacco";
                        $session['user']['voodoouses']+=1;
                        break;
                        case "dif":
                        $spell = "Riduzione di Difesa";
                        $session['user']['voodoouses']+=1;
                        break;
                        case "attmax":
                        $spell = "Risucchio di Attacco";
                        break;
                        case "difmax":
                        $spell = "Risucchio di Difesa";
                        break;
                        case "death":
                        $spell = "Morte";
                        break;
                        case "wealth":
                        $spell = "Ricchezza";
                        break;
                        case "beauty":
                        $spell = "Bellezza";
                        break;
                        case "vitality":
                        $spell = "Resistenza";
                        break;
                        case "strenght":
                        $spell = "Vigore";
                        break;
                    }
                    $protezione = 1;
                    if ($_GET['spell'] != "att" && $_GET['spell'] != "dif" && $_GET['spell'] != "attmax" && $_GET['spell'] != "difmax") {
                       $session['user']['gold']-=$_GET['cost'];
                    }else{
                       if ($_GET['spell'] == "attmax" OR $_GET['spell'] == "difmax") {
                           $protezione = controllopunti($rowc[acctid]);
                           $_GET['cost'] = controllocosto($rowc[acctid]);
                           output("`n`\$L'incantesimo ti costerà ".$_GET['cost']." gemme`n`n`0");
                       }
                       if ($protezione == 1) $session['user']['gems']-=$_GET['cost'];
                    }
                    if ($protezione == 1) {
                        //aggiunta cattiveria, Sook (parte 2)
                        $session['user']['evil']+=20;
                        if ($_GET['spell'] == "attmax" || $_GET['spell'] == "difmax") {
                            $session['user']['evil']+=30;
                        }
                        if ($_GET['spell'] == "death") {
                            $session['user']['evil']+=50;
                        }
//                        if ($session['user']['voodoouses'] < 5) {
                            $sqlf="INSERT INTO voodoo (caster, target, spell) VALUES ('".$session['user']['acctid']."', '".$rowc['acctid']."', '".$_GET['spell']."')";
                            db_query($sqlf) or die(db_error(LINK));
                            output("`@Hai fatto eseguire un incantesimo voodoo di ".$spell." su `7{$rowc['name']}`)!");
                            output("`n`n`^L'incantesimo sarà completo e mostrerà i suoi effetti al prossimo nuovo giorno per {$rowc['name']}`^.");
                            addnews("`7".$session['user']['name']."`) ha fatto eseguire un potente incantesimo voodoo su `7".$rowc['name']."`)!");
                            debuglog("ha fatto eseguire un incantesimo voodoo su `7".$rowc['name']."`0!",$rowc['acctid']);
                            systemmail($rowc['acctid'],"`)Sei stato incantato!","`)Un incantesimo voodoo di ".$spell." è stato fatto lanciare su di te da ".$session['user']['name']."!");
                            addnav("Ritorna al Villaggio","village.php");
 /*                       } else {
                            output("`^Mentre la sacerdotessa inizia a formulare l'incantesimo, hai l'impressione che la stanza si stia allargando e la sacerdotessa si stia allontanando da te.");
                            output("La sua voce diventa sempre più debole, mentre la stanza in cui ti trovi si perde alla tua vista e diventa sempre più chiara.");
                            output("Ben presto ti ritrovi immerso nel nulla, circondato da luce bianca tutto intorno a te...`n");
                            output("Poi senti una risata malefica, non riesci a capire da dove provenga; infine un'ombra umana compare davanti a te.`n`n");
                            output("`%\"Così sei tu quello che continua a disturbare gli spiriti. Preparati, perchè adesso te la vedrai con me...\"`^. Detto questo, inizia a formulare un incantesimo di attacco contro di te.`n`n");
                            debuglog("vuole eseguire un incantesimo voodoo su `7".$rowc['name']."`0 ma finisce a scontrarsi contro il Barone Samedi!");
                            redirect("samedi.php");
                        }
*/                    } else {
                        output("Questa persona è protetta dagli dei, che impediscono che un incantesimo così devastante possa essere lanciato.`n");
                        addnav("Ritorna al Villaggio","village.php");
                    }
                }
        }
}
page_footer();
?>