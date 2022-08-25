<?php
require_once "common.php";
checkday();

page_header("La Spada nella Roccia");
$session['user']['locazione'] = 175;
output("`c`b`&La Spada nella Roccia`0`b`c");
$tradeinvalue = round(($session['user']['weaponvalue']*.75),0);
if ($_GET['op']==""){
    output("`@Molt".($session['user']['sex']?"e donne":"i uomini")." coraggios".($session['user']['sex']?"e":"i")."
     hanno tentato di recuperare questa spada.  Pochi ci sono riusciti finora.`n`n");
    output("`2Riuscirai, `4".($session['user']['name'])." `2 a recuperare la Spada nella Roccia?`n`n");
    $sql="SELECT * FROM custom WHERE area='swordGuy'";
    $result = db_query($sql);
    $dep = db_fetch_assoc($result);
    $userid=$dep['amount'];
    $sql="SELECT title FROM accounts WHERE acctid = '$userid'";
    $result = db_query($sql);
    if (db_num_rows($result)>0) {
        $pippo = db_fetch_assoc($result);
        output("`@Sappi che oggi il prescelto dalla spada possiede il titolo di: `%".$pippo['title']."`n");
    }else{
        output("`#La spada è già stata estratta per oggi ! Riprova domani, magari sarà il tuo giorno fortunato. `n");
    }
    addnav("`#Prova a tirare la Spada","spadaroccia.php?op=pull");
    addnav("`@Torna al Villaggio","village.php");
    if ($session['user']['superuser']>2) {
    addnav("Check Prescelto","spadaroccia.php?op=check");
    addnav("Azzera Prescelto","spadaroccia.php?op=azzera");
    }
}elseif ($_GET['op']=="pull"){
        addnav("`@Torna al Villaggio","village.php");
        if ($session['user']['turns']<=0){output("`\$`bSei troppo stanco per tentare di estrarre la spada. È inutile provarci.`b`0", true);}
             else {
                $session['user']['turns']--;
                $sql="SELECT * FROM custom WHERE area='swordGuy'";
                $result = db_query($sql);
                $dep = db_fetch_assoc($result);
                $userid=$dep['amount'];
                $turns=e_rand(50,100);
                $acctid=$session['user']['acctid'];
                if ($acctid==$userid){
                     addnav("`^Notizie Giornaliere","news.php");
                     output("`c`b<font size='+1'>`!C`@O`#N`5G`3R`^A`4T`1U`2L`2A`4Z`5I`6O`5N`7I`3!</font>`c`b`n`n",true);
                     output("`@Sei riuscito ad estrarre la spada. hai avuto il tuo colpo di fortuna oggi, usala con saggezza!");
                     output("La spada ti aiuterà nelle tue battaglie per `b$turns turni`b, potenziando il tuo attacco.");
                     addnews("`%".$session['user']['name']."`3 ha estratto la Spada dalla Roccia!!");
                     $session['bufflist']['SpadaRoccia'] = array(
                                          "name"=>"`&Spada nella Roccia",
                                          "rounds"=>$turns,
                                          "wearoff"=>"La Spada nella Roccia si polverizza tra le tue mani, dannazione!",
                                          "atkmod"=>2,
                                          "roundmsg"=>"Preparati a morire!!",
                                          "activate"=>"offense"
                                          );
                     $sql="UPDATE custom SET amount = 0, dTime = 0  WHERE area = 'swordGuy'";
                     $result=db_query($sql) or die(db_error(LINK));
                } else {
                    output("`3Piazzi entrambi i piedi sulla roccia, e ti prepari a dare un forte strattone.  Tiri la spada e ...`n",true);
                    output("`^WOWW!!! Voli giù dalla roccia ed atterri sul tuo didietro. Ouch, quello vuol dire `@tirare troppo forte!`n`n", true);
                    output("`3Suppongo tu non sia il prescelto per oggi!",true);
                    output("`2Perdi un turno di combattimento.", true);
                }
         }
}elseif ($_GET['op']=="check") {
    $sql="SELECT * FROM custom WHERE area='swordGuy'";
    $result = db_query($sql) or die(db_error(LINK));
    $dep = db_fetch_assoc($result);
    $userid=$dep['amount'];
    $sql="SELECT name,acctid FROM accounts WHERE acctid = '$userid'";
    $result = db_query($sql) or die(db_error(LINK));
    $pippo = db_fetch_assoc($result);
    output("Possibile utilizzatore della spada: ".$pippo['acctid']." ".$pippo['name']."`n");
    addnav("`@Torna al Villaggio","village.php");
}elseif ($_GET['op']=="azzera") {
    $sql="UPDATE `custom` SET `amount` = 0,dTime = 0 WHERE `area` = 'swordGuy'";
    $result=db_query($sql) or die(db_error(LINK));
    output("Azzerato utilizzatore della spada.`n");
    addnav("`@Torna al Villaggio","village.php");
}

page_footer();
?>