<?php
/*
Robin Hood and his band of merry men forest event
Created by Lonny Luberts
http://www.pqcomp.com/logd e-mail logd@pqcomp.com
place this file in the main (logd) folder
*/
require_once ("common.php");
require_once ("common2.php");
checkday();
page_header("Robin Hood");
output("`c`b`&Qualcosa di Speciale`0`b`c`n`n");

if ($_GET['op'] == ""){
        $totalgold = $session['user']['goldinbank'] + $session['user']['gold'];
        if ($session['user']['gold'] <1) $totalgold = 0;
        output("`7Nel tuo vagabondare per la foresta ti sei imbattuto in Robin Hood e la sua Banda dagli Uomini Allegri.`n");
        if ($totalgold > 500) {
            output("`7Robin Hood ti spiega che si prenderà il tuo oro per darlo ai poveri.`n");
            output("`4Robin Hood quindi ti ordina di consegnargli il tuo oro.`n");
            output("`7Cosa hai intenzione di fare?  Aiutare i poveri con il tuo oro oppure provare a combattere per difendere i tuoi averi?");
            addnav("O?Consegna il tuo Oro","robinhood.php?op=loose&op2=give");
            addnav("Combatti","robinhood.php?op=fight1");
        }else{
            output("`7Ti salutano con le loro mani, prima di continuare per la loro strada.");
            addnav("Continua","forest.php");
        }
}elseif ($_GET['op'] == "loose"){

        if ($session['user']['hitpoints'] < 1) $session['user']['hitpoints'] = 1;
        $loot = $session['user']['gold'];
        $session['user']['gold'] = 0;
        $orobanca = 0;
        $oromano = 0;
        $sql = "SELECT acctid,name,goldinbank,gold,login FROM accounts
                WHERE goldinbank<1 AND gold<1 AND jail=0 AND superuser=0
                AND acctid <> ".$session['user']['acctid'];
        $result = db_query($sql);
        $num=0;
        if (db_num_rows($result) != 0){
           $num=db_num_rows($result);
        }
        if ($num == 0){
           $sql = "SELECT acctid,name,goldinbank,gold,login FROM accounts
                   WHERE goldinbank<1001 AND gold<1001 AND jail=0 AND superuser=0
                   AND acctid <> ".$session['user']['acctid'];
           if (e_rand(0,1)==1) $sql.=" ORDER BY RAND() LIMIT 1";
           $result = db_query($sql);
           if (db_num_rows($result) != 0){
               $num=db_num_rows($result);
               $orobanca = 1000;
               $oromano = 1000;
           }
        }
        if ($num == 0){
           $sql = "SELECT acctid,name,goldinbank,gold,login FROM accounts
           WHERE goldinbank<5001 AND gold<10001 AND jail=0 AND superuser=0
           AND acctid <> ".$session['user']['acctid'];
           if (e_rand(0,3)==3) $sql.=" ORDER BY RAND() LIMIT 1";
           $result = db_query($sql);
           if (db_num_rows($result) != 0){
               $num=db_num_rows($result);
           }
           $orobanca = 5000;
           $oromano = 10000;
        }
        debuglog("ha incontrato Robin Hood che lo ha derubato di $loot monete");
        $dist = 0;
        if ($session['user']['superuser'] > 0) $num = 0;
        if ($num>0) {
           $dist = ceil($loot/$num);
           // if there is no one to give the gold to never worry.... robin hood keeps it. hehe
           //$sql = "SELECT acctid,name,goldinbank,gold,login FROM accounts WHERE superuser=0 AND jail=0";
           //$result = db_query($sql);
           $countrow = db_num_rows($result);
           for ($i=0; $i<$countrow; $i++){
           //for ($i=0;$i<db_num_rows($result);$i++){
               //echo "query SQL: ".$sql;
               $row = db_fetch_assoc($result);
               // Excalibur: inutile, i controlli sono statti effettuati in precedenza
               //if ($row['goldinbank'] <= $orobanca AND $row['gold'] <= $oromano AND $row['acctid'] != $session['user']['acctid']) {
                  //$give = $row['goldinbank'] + $dist;
                  // Excalibur: inutile, i controlli sono statti effettuati in precedenza
                  //if ($row['name'] <> $session['user']['name']){
                     //$sql2 = "UPDATE accounts SET goldinbank = $give WHERE login = '".$row['login']."'";
                     //db_query($sql2);
                     assign_to_pg($row['acctid'],'gold',$dist);
                     $mailmessage = $session['user']['name'];
                     $mailmessage .= " è stato derubato da Robin Hood e la sua Banda degli Uomini Allegri.";
                     $mailmessage .= "Gli hanno preso ".$loot." pezzi d'oro, che hanno consegnato a ";;
                     $mailmessage .= $num." persone.  Ognuno di voi ha ricevuto ";;
                     $mailmessage .= $dist." pezzi d'oro.  Puoi ritirarlo dalla pagina delle preferenze.";;
                     systemmail($row['acctid'],"`2Robin Hood ti ha donato dell'oro!`2",$mailmessage);
                     $message="ha ricevuto $dist oro da Robin Hood, rubati a ".$session['user']['name'];
                     $sql = "INSERT INTO debuglog VALUES(0,now(),".$row['acctid'].",0,'".addslashes($message)."')";
                     db_query($sql);
                  //}
               //}
           }
           if ($num >1) {
               addnews("Robin Hood ha rubato $loot pezzi d'oro a ".$session[user][name]."`7 per darli ai poveri!");
           } else {
               addnews("Robin Hood ha rubato $loot pezzi d'oro a ".$session[user][name]."`7 per darli ad uno dei poveri!");
           }
        }
        if ($num < 1) addnews("Robin Hood ha rubato $loot pezzi d'oro a ".$session['user']['name']."`7 e se li è tenuti!");
        if ($_GET['op2'] <> "give"){
            output("`4Hai perso!`n`7Robin Hood ed i suoi Uomini Allegri non sono poi così cattivi dopotutto... ");
            output("Anche se ti hanno conciato piuttosto male, ti concedono di vivere.`n");
        }
        if ($_GET['op2'] == "give") output("`7Consegni il tuo oro!");
        output("`3Robin Hood prende il tuo oro per distribuirlo ai più poveri del reame.");
        addnav("Continua","forest.php");
        if ($session['user']['hitpoints'] == 1){
                output("Prima di andarsene, Robin Hood si volta indietro e ti lancia una pozione.  La bevi e ");
                switch(e_rand(1,10)){
                        case 1:
                        $session['user']['hitpoints'] = intval($session['user']['maxhitpoints'] * .1);
                        output("recuperi il 10% dei tuoi HitPoints.`n");
                        break;
                        case 2:
                        $session['user']['hitpoints'] = intval($session['user']['maxhitpoints'] * .2);
                        output("recuperi il 20% dei tuoi HitPoints.`n");
                        break;
                        case 3:
                        $session['user']['hitpoints'] = intval($session['user']['maxhitpoints'] * .3);
                        output("recuperi il 30% dei tuoi HitPoints.`n");
                        break;
                        case 4:
                        $session['user']['hitpoints'] = intval($session['user']['maxhitpoints'] * .4);
                        output("recuperi il 40% dei tuoi HitPoints.`n");
                        break;
                        case 5:
                        $session['user']['hitpoints'] = intval($session['user']['maxhitpoints'] * .5);
                        output("recuperi il 50% dei tuoi HitPoints.`n");
                        break;
                        case 6:
                        $session['user']['hitpoints'] = intval($session['user']['maxhitpoints'] * .6);
                        output("recuperi il 60% dei tuoi HitPoints.`n");
                        break;
                        case 7:
                        $session['user']['hitpoints'] = intval($session['user']['maxhitpoints'] * .7);
                        output("recuperi il 70% dei tuoi HitPoints.`n");
                        break;
                        case 8:
                        $session['user']['hitpoints'] = intval($session['user']['maxhitpoints'] * .8);
                        output("recuperi il 80% dei tuoi HitPoints.`n");
                        break;
                        case 9:
                        $session['user']['hitpoints'] = intval($session['user']['maxhitpoints'] * .9);
                        output("recuperi il 90% dei tuoi HitPoints.`n");
                        break;
                        case 10:
                        $session['user']['hitpoints'] = $session['user']['maxhitpoints'];
                        output("recuperi tutti i tuoi HitPoints.`n");
                        break;
                }
        }
}elseif ($_GET['op'] == "win"){
        output("Hai sconfitto Robin Hood e la sua Banda degli Uomini Allegri!");
        addnews("Robin Hood e la sua Banda degli Uomini Allegri sono stati sconfitti nella Foresta da ".$session['user']['name']."`7!");
        output("Pensi che sia meglio andarsene via prima che si riprendano.");
        //addnews
        addnav("Continua","forest.php");
}elseif ($_GET['op'] == "fight1"){
        $badguy = array(        "creaturename"=>"`@Frate Tuck`0"
        ,"creaturelevel"=>0
        ,"creatureweapon"=>"Birra nello Stomaco"
        ,"creatureattack"=>0
        ,"creaturedefense"=>1
        ,"creaturehealth"=>2
        ,"creaturegold"=>0
        ,"diddamage"=>0);

        $userlevel=$session['user']['level'];
        $userattack=e_rand(2,$session['user']['attack'])+2;
        $userhealth=e_rand(30,110)+$session['user']['level'];
        $userdefense=e_rand(2,$session['user']['defence'])+2;
        $badguy['creaturelevel']+=$userlevel;
        $badguy['creatureattack']+=$userattack;
        $badguy['creaturehealth']=$userhealth;
        $badguy['creaturedefense']+=$userdefense;
        $badguy['creaturegold']=0;
        $session['user']['badguy']=createstring($badguy);
        $_GET['op']="fight";
}elseif ($_GET['op'] == "fight2"){
        $badguy = array(        "creaturename"=>"`@Will Scarlet`0"
        ,"creaturelevel"=>0
        ,"creatureweapon"=>"Spada"
        ,"creatureattack"=>1
        ,"creaturedefense"=>2
        ,"creaturehealth"=>2
        ,"creaturegold"=>0
        ,"diddamage"=>0);

        $userlevel=$session['user']['level'];
        $userattack=e_rand(2,$session['user']['attack'])+4;
        $userhealth=e_rand(40,120)+$session['user']['level'];
        $userdefense=e_rand(2,$session['user']['defence'])+4;
        $badguy['creaturelevel']+=$userlevel;
        $badguy['creatureattack']+=$userattack;
        $badguy['creaturehealth']=$userhealth;
        $badguy['creaturedefense']+=$userdefense;
        $badguy['creaturegold']=0;
        $session['user']['badguy']=createstring($badguy);
        $_GET['op']="fight";
}elseif ($_GET['op'] == "fight3"){
        $badguy = array(        "creaturename"=>"`@Little John`0"
        ,"creaturelevel"=>1
        ,"creatureweapon"=>"Bastone"
        ,"creatureattack"=>2
        ,"creaturedefense"=>3
        ,"creaturehealth"=>2
        ,"creaturegold"=>0
        ,"diddamage"=>0);

        $userlevel=$session['user']['level'];
        $userattack=e_rand(2,$session['user']['attack'])+6;
        $userhealth=e_rand(50,130)+$session['user']['level'];
        $userdefense=e_rand(2,$session['user']['defence'])+6;
        $badguy['creaturelevel']+=$userlevel;
        $badguy['creatureattack']+=$userattack;
        $badguy['creaturehealth']=$userhealth;
        $badguy['creaturedefense']+=$userdefense;
        $badguy['creaturegold']=0;
        $session['user']['badguy']=createstring($badguy);
        $_GET['op']="fight";
}elseif ($_GET['op'] == "fight4"){
        $badguy = array(        "creaturename"=>"`@Robin Hood`0"
        ,"creaturelevel"=>2
        ,"creatureweapon"=>"Frecce Volanti"
        ,"creatureattack"=>3
        ,"creaturedefense"=>4
        ,"creaturehealth"=>2
        ,"creaturegold"=>0
        ,"diddamage"=>0);

        $userlevel=$session['user']['level'];
        $$userattack=e_rand(2,$session['user']['attack'])+8;
        $userhealth=e_rand(60,140)+$session['user']['level'];
        $userdefense=e_rand(2,$session['user']['defence'])+8;
        $badguy['creaturelevel']+=$userlevel;
        $badguy['creatureattack']+=$userattack;
        $badguy['creaturehealth']=$userhealth;
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
            output("Hai sconfitto `^".$badguy['creaturename'].".`n");
            if ($badguy['creaturename']=="`@Frate Tuck`0") addnav("Continua","robinhood.php?op=fight2");
            if ($badguy['creaturename']=="`@Will Scarlet`0") addnav("Continua","robinhood.php?op=fight3");
            if ($badguy['creaturename']=="`@Little John`0") addnav("Continua","robinhood.php?op=fight4");
            if ($badguy['creaturename']=="`@Robin Hood`0") addnav("Continua","robinhood.php?op=win");
            $exp= e_rand(($session['user']['level']*8), ($session['user']['level']*15));
            $session['user']['experience'] += $exp;
            debuglog("ha sconfitto ".$badguy['creaturename']." e salva il suo oro");
            output("`@Guadagni `^$exp `@punti esperienza dal combattimento disputato.");
            $badguy=array();
            $session['user']['badguy']="";
        }elseif ($defeat){
            output("Non appena cadi al suolo `^".$badguy['creaturename']." e gli altri Uomini Allegri prendono il tuo oro.");
            output("`n`7`n`7Perdi il `^`b15%`b`7 della tua esperienza. ");
            $session['user']['experience']=round($session['user']['experience']*.85,0);
            addnews("`%".$session['user']['name']."`5 è stat".($session['user']['sex']?"a":"o")." sconfitt".($session['user']['sex']?"a":"o")." quando è stat".($session['user']['sex']?"a":"o")." attaccat".($session['user']['sex']?"a":"o")." da Robin Hood e la sua Banda degli Uomini Allegri.");
            $session['user']['hitpoints']=1;
            addnav("Continua","robinhood.php?op=loose");
        }else{
            fightnav(true,false);
        }
}else{

}
page_footer();
?>