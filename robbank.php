<?php
// Intigration is easy.

// Drop this in the games root directory.
//
// Create a column called robbank with a type of int. (you may have to del and re-insert the bounty column for the bounty feature to work.)
//
// Add a database field called robbersclub. with the type of INT and Size of 11 unsigned. (for future use).
//
// Add the following code to bank.php:
// $rob = e_rand(1,10);
// if ($rob > 5 and $session['user']['robbank'] <1)
//      addnav("`4Rob the Bank","robbank.php");
//
// Add the following line to the newday.php file:
// $session['user']['robbank']=0;

require_once "common.php";
checkday();
page_header ("La Vecchia Banca");
$session['user']['locazione'] = 164;
if ($_GET['op']=="") {
        output("`^`c`bLa Tentazione ti sta uccidendo`b`c`6");
        output("`n`nIl banchiere si attarda per un momento davanti a te quindi si dirige nella stanza sul retro.
        Quando si allontana, noti che la cassaforte è aperta. La dentro ricchezze sconosciute gonfiano sacchi
        strapieni.");
        output("`n`nQuesta potrebbe essere un giornata fortunata.");
        addnav("Acchiappa il Malloppo","robbank.php?op=rob");
        addnav("Torna al Villaggio","robbank.php?op=chicken");


//You decide that the best course of action is to leave.
        }else if($_GET['op']=='chicken'){
                output("`^`c`bFifone`b`c`6");
                output("Si, ci sono sempre altre opportunità.");
                addnav("Torna al Villaggio","village.php");

//You let your evil side get to you.
        }else if ($_GET['op']=='rob') {
                output("`^`c`bL'Opportunità Bussa`b`c`6");
                output("`n`nCammini intorno al bancone e afferri tutto l'oro che puoi trasportare.");
                addnav("Torna al Villaggio","robbank.php?op=leave");

//Let em think they are getting away.
        }else if ($_GET['op']=='leave') {
                output("`^`c`bNon così Veloce amico`b`c`6");
                output("`n`nTi precipiti alla porta. Ma mentre stai uscendo, un uomo enorme entra sulla soglia e ti sbatte indietro.");
                output("\"Posso aiutarti a riportare tutto questo denaro in cassaforte?\" chiede Guido.");
                addnav("Combatti","robbank.php?op=fight");
                addnav("Scappa","robbank.php?op=run");

        //Setup Guido.
        $badguy = array("creaturename"=>"`@Guido La Guardia Giurata`0"
                        ,"creaturelevel"=>0
                        ,"creatureweapon"=>"Manganello Mazzuola Ladri"
                        ,"creatureattack"=>1
                        ,"creaturedefense"=>2
                        ,"creaturehealth"=>2
                        ,"diddamage"=>0);

                        //buff Guido up a little
                        $userlevel=$session['user']['level'];
                        $userattack=$session['user']['attack'];
                        $userhealth=$session['user']['maxhitpoints']*3;
                        $userdefense=$session['user']['defense'];
                        $x=e_rand(1,2);
                        $badguy['creaturelevel']+=$userlevel;
                        $badguy['creaturelevel']+=$x;
                        $y=e_rand(4,6);
                        $badguy['creatureattack']+=$userattack;
                        $badguy['creatureattack']+=$y;
                        $z=e_rand(1,($userhealth*.6));
                        $badguy['creaturehealth']=$userhealth + $session['user']['bankrobbed'];
                        $badguy['creaturehealth']+=$z;
                        $t=e_rand(6,8)+max($session['user']['dragonkills'],2);
                        $badguy['creaturedefense']+=$userdefense;
                        $badguy['creaturedefense']+=$t;
                        $badguy['creatureattack']+=$session['user']['bankrobbed'];
                        $badguy['creaturedefense']+=$session['user']['bankrobbed'];
                        $session['user']['badguy']=createstring($badguy);

       }
if ($_GET['op']=='battle' || $_GET['op']=='run') {

//$session['user']['robbank']++;

//how do they say?  You made your bed...
        if ($_GET['op']=='run') {
           output("\"Mi spiace, ma non posso lasciarti andare via.\" dice Guido La Guardia Giurata.");
           $_GET['op']="fight";
        }
}

if ($_GET['op']=='fight') {
   $battle=true;
}

if ($battle) {
        include("battle.php");
        if($victory) {
            //echo "Cattiveria prima: ".$session['user']['bankrobbed']."<br>";
            if ($session['user']['bankrobbed'] > 999){
               $session['user']['bankrobbed'] += intval($session['user']['bankrobbed']*0.15);
            }elseif ($session['user']['bankrobbed'] > 499){
               $session['user']['bankrobbed'] += intval($session['user']['bankrobbed']*0.8);
            }elseif ($session['user']['camuffa']!= 0){
               $session['user']['bankrobbed'] += (10 * $session['user']['camuffa']);
            }else{
               $session['user']['bankrobbed'] += 20;
            }
            //echo "Cattiveria dopo: ".$session['user']['bankrobbed']."<br>";
            $session['user']['robbank']=1;
            //Add a column in your accounts table to take advantage of the Hall of Shame.
            if ($session['user']['camuffa'] != 0) {
                $chance = e_rand(0,6);
                if ($chance < $session['user']['camuffa']) {
                    output("`(Il travestimento ha evitato che Guido ti riconoscesse, ed ha ridotto l'ammontare dei punti ");
                    output("cattiveria che ti sono stati assegnati.`n`n");
                    $session['user']['evil']+=(40-($session['user']['camuffa']*3));
                } else {
                    output("`(Il tuo travestimento non ha ingannato Guido, che ti ha immediatamente riconosciuto !!`n ");
                    output("Il tuo maldestro tentativo non ha evitato che ti venissero assegnati i punti cattiveria che meriti !n`n");
                    $session['user']['evil']+=40;
                    if (e_rand(-35,5) > $session['user']['camuffa']){
                        output("Inoltre durante il combattimento con Guido il travestimento comprato a caro prezzo ");
                        output("si danneggia e diventa inutilizzabile. Dovrai ricomprartelo per beneficiare dei bonus da esso derivanti!!`n`n");
                        $session['user']['camuffa'] = 0;
                    }
                }
            }else {
                if ($session['user']['evil']+30>100 AND $session['user']['evil']<100){
                    $session['user']['evil']+=40;
                }else {
                    $session['user']['evil']+=30;
                }
            }
            if ($session['user']['hitpoints'] < 1) $session['user']['hitpoints']=1;
            output("Hai battuto `^".$badguy['creaturename'].".");
            $badguy=array();
            $session['user']['badguy']="";
            addnav("Torna al Villaggio","village.php");
            $gold=0;
            $totalgold=0;
            $rapine=0;
            $sqlrob = "SELECT * FROM items WHERE class = 'robbank' AND owner='".$session['user']['acctid']."'";
            $resultrob = db_query($sqlrob) or die(db_error(LINK));
            $rapine = db_num_rows($resultrob);
            if ($rapine!=1) {
               //Xtramus. Questa query deve essere la prima!
                $sqlbank="INSERT INTO items (owner,name,class) VALUES('".$session['user']['acctid']."','rapina','robbank')";
                db_query($sqlbank) or die(db_error(LINK));
//              saveuser();
                $limite=e_rand(300,600);
                $sql = "SELECT acctid,goldinbank,bounty,login
                        FROM accounts
                        WHERE superuser=0
                        AND goldinbank>0
                        ORDER BY RAND()
                        LIMIT ".$limite;
                $result = db_query($sql) or die(db_error($sql));

                if (db_num_rows($result)==0){
                    output("`n`n`\$Abbiamo un problema qui. Nessun records trovato.`nAvvisa gli admin del gioco!!`n");
                    //addnav("Torna al Villaggio","village.php");
                }
                $countrow = db_num_rows($result);
                for ($i=0; $i<$countrow; $i++){
                //for ($i=0;$i<db_num_rows($result);$i++){
                    $takengold=0;
                    $row = db_fetch_assoc($result);
                    //You really dont want to Rob yourself. This takes care of that.
                    if ($row['acctid'] != $session['user']['acctid']){
                        //if the victim has 10 gold or less in thier account.  We just take it all. >:)
                        if ($row['goldinbank']<=10)  {
                            $takengold=$row['goldinbank'];
                            $row['goldinbank']=0;
                            $totalgold=$takengold+$totalgold;
                            //Otherwise we take 0.3% of the gold. (this might be a little liberal depending on your game.)
                        } else {
                            $takengold=round($row['goldinbank']*.003);
                            if ($takengold > 1000) $takengold = 1000;
                            $row['goldinbank']=$row['goldinbank']-$takengold;
                            $totalgold=$takengold+$totalgold;
                        }
                        //Send A Email to All victims
                        $sql2 = "UPDATE accounts SET goldinbank=goldinbank-$takengold WHERE login = '{$row['login']}'";
                        db_query($sql2);
                        $mailmessage = "`^".$session['user']['name']." ha derubato la banca e ha preso $takengold pezzi del tuo oro.";
                        if ($takengold!=0) {
                           //systemmail($row['acctid'],"`2(MAIL) Parte del tuo Oro è stato rubato`2",$mailmessage);
                           $sql3 = "INSERT INTO mail (msgfrom,msgto,subject,body,sent)
                                    VALUES ('0','".(int)$row['acctid']."','`2Parte del tuo Oro è stato rubato',
                                    '".addslashes($mailmessage)."',now())";
                           //echo $sql3."<br>";
                           db_query($sql3) or die(db_error(LINK));
                        }
                    }

                }
                $session['user']['gold']+=$totalgold;
                //Set bounty on user.
                $bountyissue=e_rand(100,500);
                $bountyissue=($bountyissue*$session['user']['level']);
                $session['user']['bounty']=$bountyissue+$session['user']['bounty'];
                debuglog("ha derubato la banca di $totalgold oro e aggiunta taglia $bountyissue oro");
                if($session['user']['lupin']['carriera']==2) {
                    debuglog("`2Con la rapina ha passato la prima parte della quest ladri, ora deve riportare il bottino al capo");
                    $session['user']['lupin']['bottino']=$totalgold;
                    $session['user']['lupin']['carriera']=3;
                }
                addnews("`%".$session['user']['name']."`5 ha derubato la Banca!!  Se n'è andat".($session['user']['sex']?"a":"o")." con $totalgold pezzi d'oro e adesso ha una taglia di ".$session['user']['bounty']." sulla sua testa.");
            }
        }else{
                if ($defeat) {
                   //echo "Cattiveria prima: ".$session['user']['bankrobbed']."<br>";
                   if ($session['user']['bankrobbed'] > 999){
                      $session['user']['bankrobbed'] += intval($session['user']['bankrobbed']*0.1);
                   }elseif ($session['user']['bankrobbed'] > 499){
                      $session['user']['bankrobbed'] += intval($session['user']['bankrobbed']*0.05);
                   }elseif ($session['user']['camuffa']!= 0){
                      $session['user']['bankrobbed'] += (8 * $session['user']['camuffa']);
                   }else{
                      $session['user']['bankrobbed'] += 15;
                   }
                   //echo "Cattiveria dopo: ".$session['user']['bankrobbed']."<br>";
                        //Add a column in your accounts table to take advantage of the Hall of Shame.
                        $session['user']['robbank']=1;
                        if ($session['user']['camuffa'] != 0) {
                            $chance = e_rand(0,5);
                            if ($chance < $session['user']['camuffa']) {
                                output("`(`nIl travestimento non ti ha evitato la sconfitta, ma ha ridotto l'ammontare dei punti ");
                                output("cattiveria che ti sono stati assegnati.`n`n");
                                $session['user']['evil']+=(30-($session['user']['camuffa']*3));
                            } else {
                                output("`(`nIl tuo travestimento non ha ingannato Guido, che ti ha immediatamente riconosciuto !!`n ");
                                output("Il tuo maldestro tentativo non ha evitato che ti venissero assegnati i punti cattiveria che meriti !n`n");
                                $session['user']['evil']+=30;
                                if (e_rand(-35,5) > $session['user']['camuffa']){
                                    output("Inoltre Guido ti confisca il travestimento che ti eri comprato a caro prezzo ");
                                    output("e che dovrai ricomprarti per beneficiare dei bonus da esso derivanti!!`n`n");
                                    $session['user']['camuffa'] = 0;
                                }
                            }
                        }else{
                            $session['user']['evil']+=30;
                            output("`(`nPoichè non possiedi nessun travestimento, i punti cattiveria ti vengono assegnati per intero!!`n`n`0");
                        }
                        $chance = e_rand(1,5);
                        if ($session['user']['evil'] > 100 AND $chance == 1){
                           $session['user']['hitpoints'] = 1;
                           $session['user']['alive']=true;
                           output("`3Mentre cadi a terra, perdendo conoscenza, vedi Guido estrarre un paio di manette ed ");
                           output("avvicinarsi a te.`nPoi la vista ti si annebbia e senti in lontananza la risata sardonica di Guido`n");
                           output("Hai perso il 15% della tua esperienza !!!`n");
                           $session['user']['experience'] -= intval($session['user']['experience']*0.15);
                           addnews("`%".$session['user']['name']."`5 è stat".($session['user']['sex']?"a":"o")." catturat".($session['user']['sex']?"a":"o")." e condotto dallo sceriffo mentre tentava di rapinare la banca.");
                           debuglog("perde 15% exp mentre tentava di derubare la banca ed è stato condotto dallo sceriffo");
                           addnav("Continua","constable.php");
                        }else{
                           output("Mentre cadi a terra `^".$badguy['creaturename']. " recupera il bottino.`n");
                           output("Hai perso il 15% della tua esperienza !!!`n");
                           output("Inoltre perdi tutto l'oro che avevi con te!!!`n");
                           output("Questa è la giusta punizione per un gaglioffo della tua risma !!!`n");
                           addnews("`%".$session['user']['name']."`5 è stat".($session['user']['sex']?"a":"o")." uccis".($session['user']['sex']?"a":"o")." mentre tentava di rapinare la banca.");
                           debuglog("perde 15% exp e ".$session['user']['gold']." oro mentre tentava di derubare la banca");
                           $session['user']['experience']*=0.85;
                           $session['user']['alive']=false;
                           $session['user']['hitpoints']=0;
                           $session['user']['gold'] = 0;
                           addnav("Notizie Giornaliere","news.php");
                        }

                }else   {
                        fightnav(true,false);
                }

         }
}
page_footer();
?>