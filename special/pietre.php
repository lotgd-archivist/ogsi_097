<?php
/*
/ pietre.php - Magic Stones V0.3.0
/ Originally by Excalibur (www.ogsi.it)
/ English cleanup by Talisman (dragonprime.cawsquad.net)
/ Contribution LonnyL (www.pqcomp.com)
/ Original concept from Aris (www.ogsi.it)
/ July 2004

----install-instructions--------------------------------------------------
DIFFICULTY SCALE: easy

Forest Event for LotGD 0.9.7
Drop into your "Specials" folder
--------------------------------------------------------------------------
SQL Modification
#
# Table structure `pietre`
#

CREATE TABLE `pietre` (
  `pietra` int(4) unsigned NOT NULL default '0',
  `owner` int(4) unsigned NOT NULL default '0'
) TYPE=MyISAM;

--------------------------------------------------------------------------
----- In File:
newday.php

----- Find:
    $session['user']['bounties'] = 0;
}

----- Add after:
//Modification for pietre.php
$owner1=$session['user']['acctid'];
$sql="SELECT * FROM pietre WHERE owner=$owner1";
$result = db_query($sql);
$pot = db_fetch_assoc($result);
if (count($result) != 0) {
    $flagstone=$pot['pietra'];
    switch ($flagstone) {
    case 1:
         output("`n`n`%Poichè possiedi la {$pietre[$flagstone]}`% della `&Fonte di Aris`% perdi un combattimento supplementare !`n");
         $session['user']['turns']-=1;
    break;

    case 2:
         output("`n`n`%Poichè possiedi la {$pietre[$flagstone]}`% della `&Fonte di Aris`% guadagni un punto di fascino !`n");
         $session['user']['charm']+=1;
    break;

    case 3:
         output("`n`n`%Poichè possiedi la {$pietre[$flagstone]}`% della `&Fonte di Aris`% guadagni un combattimento supplementare !`n");
         $session['user']['turns']+=1;
    break;

    case 4:
         output("`n`n`%Poichè possiedi la {$pietre[$flagstone]}`% della `&Fonte di Aris`% guadagni 300 pezzi d'oro !`n");
         $session['user']['gold']+=300;
    break;

    case 5:
         output("`n`n`%Poichè possiedi la {$pietre[$flagstone]}`% della `&Fonte di Aris`% guadagni potenza in attacco !`n");
         $session[bufflist][120] = array("name"=>"{$pietre[$flagstone]}","rounds"=>200,"wearoff"=>"`4La luminescenza scompare dalla tua {$pietre[$flagstone]}.","atkmod"=>1.3,"roundmsg"=>"`4La {$pietre[$flagstone]} potenzia il tuo attacco!.","activate"=>"offense");
    break;

    case 6:
         output("`n`n`%Poichè possiedi la {$pietre[$flagstone]}`% della `&Fonte di Aris`% la tua difesa viene potenziata !`n");
         $session[bufflist][120] = array("name"=>"{$pietre[$flagstone]}","rounds"=>200,"wearoff"=>"`4La luminescenza scompare dalla tua {$pietre[$flagstone]}.","defmod"=>1.3,"roundmsg"=>"`4La {$pietre[$flagstone]} potenzia la tua difesa!.","activate"=>"offense");
    break;

    case 7:
         output("`n`n`%Poichè possiedi la {$pietre[$flagstone]}`% della `&Fonte di Aris`% attacchi e difendi meglio !`n");
         $session[bufflist][120] = array("name"=>"{$pietre[$flagstone]}","rounds"=>200,"wearoff"=>"`4La luminescenza scompare dalla tua {$pietre[$flagstone]}.","atkmod"=>1.3,"defmod"=>1.3,"roundmsg"=>"`4La {$pietre[$flagstone]} acuisce le tue capacità!.","activate"=>"offense");
    break;

    case 8:
         output("`n`n`%Poichè possiedi la {$pietre[$flagstone]}`% della `&Fonte di Aris`% acquisisci capacità supplementari in alcune arti !`n");
         $session['user']['darkartuses']+=6;
         $session['user']['magicuses']+=6;
         $session['user']['thieveryuses']+=6;
         $session['user']['militareuses']+=6;
    break;

    case 9:
         output("`n`n`%Poichè possiedi la {$pietre[$flagstone]}`% della `&Fonte di Aris`% guadagni un combattimento supplementare !`n");
         $session['user']['turns']+=1;
    break;

    case 10:
         output("`n`n`%Poichè possiedi la {$pietre[$flagstone]}`% della `&Fonte di Aris`% ti senti meno colpevole (perdi alcuni punti cattiveria) !`n");
         $session['user']['evil']-=5;
    break;

    case 11:
         output("`n`n`%Poichè possiedi la {$pietre[$flagstone]}`% della `&Fonte di Aris`% guadagni 200 pezzi d'oro !`n");
         $session['user']['gold']+=200;
    break;

    case 12:
         output("`n`n`%Poichè possiedi la {$pietre[$flagstone]}`% della `&Fonte di Aris`% guadagni 500 pezzi d'oro !`n");
         $session['user']['gold']+=500;
    break;

    case 13:
         output("`n`n`%Poichè possiedi la {$pietre[$flagstone]}`% della `&Fonte di Aris`% guadagni 800 pezzi d'oro !`n");
         $session['user']['gold']+=800;
    break;

    case 14:
         output("`n`n`%Poichè possiedi la {$pietre[$flagstone]}`% della `&Fonte di Aris`% attacchi e difendi meglio !`n");
         $session[bufflist][120] = array("name"=>"{$pietre[$flagstone]}","rounds"=>500,"wearoff"=>"`4La luminescenza scompare dalla tua {$pietre[$flagstone]}.","atkmod"=>1.5,"defmod"=>1.5,"roundmsg"=>"`4La {$pietre[$flagstone]} acuisce le tue capacità!.","activate"=>"offense");
    break;

    case 15:
         output("`n`n`%Poichè possiedi la {$pietre[$flagstone]}`% della `&Fonte di Aris`% guadagni favori con Ramius !`n");
         $session['user']['deathpower']=200;
    break;

    case 16:
         output("`n`n`%Poichè possiedi la {$pietre[$flagstone]}`% della `&Fonte di Aris`% sei ubriaco !`n");
         $session['user']['drunkenness']=66;
    break;

    case 17:
         output("`n`n`%Poichè possiedi la {$pietre[$flagstone]}`% della `&Fonte di Aris`% guadagni due combattimenti supplementari !`n");
         $session['user']['turns']+=2;
    break;

    case 18:
         output("`n`n`%Poichè possiedi la {$pietre[$flagstone]}`% della `&Fonte di Aris`% ti senti più puro (perdi alcuni punti cattiveria) !`n");
         $session['user']['evil']-=3;
    break;

    case 19:
         output("`n`n`%Poichè possiedi la {$pietre[$flagstone]}`% della `&Fonte di Aris`% guadagni un combattimento supplementare !`n");
         $session['user']['turns']+=1;
    break;

    case 20:
         output("`n`n`%Poichè possiedi la {$pietre[$flagstone]}`% della `&Fonte di Aris`% guadagni una gemma !`n");
         $session['user']['gems']+=1;
    break;
    }
}
//end pietre.php modification


----- In File:
common.php

----- Find:
$races=array(1=>"Troll",2=>"Elf",3=>"Human",4=>"Dwarf");

----- Add before:

$pietre=array(1=>"`\$Poker's Stone",2=>"`^Love's Stone",3=>"`^Friendship's Stone",4=>"`#King's Stone",5=>"`#Mighthy's Stone",6=>"`#Pegasus' Stone",7=>"`@Aris' Stone",8=>"`@Excalibur's Stone",9=>"`@Luke's Stone",10=>"`&Innocence's Stone",11=>"`#Queen's Stone",12=>"`#Imperator's Stone",13=>"`!Gold's Stone",14=>"`%Power's Stone",15=>"`\$Ramius' Stone",16=>"`#Cedrik's Stone",17=>"`%Honour's Stone",18=>"`&Purity's Stone",19=>"`&Light's Stone",20=>"`&Diamond's Stone");

--------------------------------------------------------------------------

Drop the code of monpietre.php where you like ... You can put in in hof.php (the new version from 0.9.8)
or you can use it "as is" giving a link from village.

Version History:
Ver. 1.0 Created by Excalibur (www.ogsi.it)
Original Version posted to DragonPrime

// -Originally by: Excalibur
// -Contributors: Excalibur, Talisman, LonnyL
// July 2004
// Last revision: November 2007
*/

page_header("La Fonte di Aris");
output("<font size='+1'>`c`b`!La Fonte di Aris`b`c`n`n</font>",true);
$session['user']['specialinc']="pietre.php";
$numpietre="20";
$owner1=$session['user']['acctid'];
$sql="SELECT * FROM pietre WHERE owner=$owner1";
$result = db_query($sql);
$pot = db_fetch_assoc($result);
$flagstone=$pot['pietra'];
if (db_num_rows($result) == 0) {
    switch($_GET['op']){
        case "":
            page_header("La Fonte di Aris");
            output("`@Nel tuo girovagare per la foresta in cerca di avventura, ti imbatti in una fonte la cui acqua ");
            output("evanescente emana una luce misteriosa. Ti sei imbattut".($session['user']['sex']?"a":"o")." nella mitica `&Fonte di Aris`@, fonte che ");
            output("prendendo il nome dal primo esploratore che la scoprì e ne descrisse l'esistenza al villaggio, ");
            output("sembra essere dotata di poteri fantastici.`nMolti hanno fantasticato e scritto leggende su questa ");
            output("fonte, ma il saggio Aris nelle sue memorie, affermò che le pietre immerse nell'acqua miracolosa ");
            output("hanno la capacità di emanare forze misteriose in grado di dotare chi le possiede di poteri ");
            output("sovrannaturali.`nNei suoi scritti disse anche che queste pietre sono numerate e che soltanto una ");
            output("può essere posseduta dall'audace guerriero che ha la fortuna di imbattersi nella fonte, usufruendo ");
            output("così degli speciali poteri ad essa associati.`n`n");
            output("Anche tu adesso potrai sfruttare questa opportunità e avere una di queste magiche pietre, ma ");
            output("dovrai effettuare la tua scelta alla cieca, senza sapere che poteri possiede e se è già nelle mani ");
            output("di qualche altro guerriero.`n`n");
            output("Fin dal tuo arrivo in questo luogo hai notato una serie di grezzi pulsanti incisi nella roccia a ");
            output("fianco della sorgente e, avvicinandoti, noti che riportano dei numeri da 1 a $numpietre.`nDovrai ");
            output("quindi premere uno di questi pulsanti e sperare che la pietra corrispondente non sia già nelle mani ");
            output("di qualche altro guerriero, anche se esiste la pur remota possibilità di impossessartene ugualmente ");
            output("nonostante la pietra sia già in mano ad un tuo pari.`nDecidi di affidare la tua sorte al fato e ");
            output("chiudendo gli occhi avvicini la tua mano alla roccia ...");
            addnav("Abbandona questo luogo","forest.php?op=lascia");
            addnav("Scegli un pulsante","forest.php?op=premi");
        break;
        case "premi";
            output("`@La mano sfiora la roccia, e tramite essa percepisci l'immenso potere della sorgente e delle ");
            output("pietre immerse nell'acqua miracolosa. Indecis".($session['user']['sex']?"a":"o")." la fai scorrere sulla dura pietra, non sapendo ");
            output("quale pulsante premere.`nAlla fine ti sembra di sentire un più intenso flusso di energia provenire ");
            output("dalla roccia e premi quindi decis".($session['user']['sex']?"a":"o")." il pulsante sotto il palmo della tua mano.`nUn rumore di ");
            output("meccanismi in movimento proviene dall'interno e aprendo gli occhi, che hai tenuto serrati fino a ");
            output("questo momento, osservi incuriosit".($session['user']['sex']?"a":"o")." la polla d'acqua.`nLa sorgente sta ribollendo e scorgi dei ");
            output("riflessi dorati nell'acqua. `nProprio mentre ti stai chiedendo se la dea bendata ti sarà favorevole ");
            output("ecco che ....`n`n");
            $session['user']['specialinc']="";
            addnav("`@Torna al Villaggio","village.php");
            addnav("`\$Torna alla Foresta","forest.php");
            $pietra=e_rand(1,20);
            $sql="SELECT pietra,owner FROM pietre WHERE pietra = $pietra";
            $result = db_query($sql) or die(db_error(LINK));
            if (db_num_rows($result) == 0) {
                //la pietra è disponibile
                output("`#... senti rotolare qualcosa all'interno della roccia, e come per magia appare una`n
                stupenda pietra all'interno della sorgente !! Ha incise delle rune sopra di essa, e ...`n");
                if ($pietra==1){
                    output("`5scopri con orrore che è la ".$pietre[$pietra]." !!!`5`n
                    Purtroppo il possesso di questa pietra ti farà perdere 1 turno di combattimento ogni giorno,`n
                    e l'unico modo per disfarsene è sperare che un altro guerriero altrettanto sfortunato incappi`n
                    nella `&Fonte di Aris`5 e prenda a sua volta la pietra. ");
                    $session['user']['turns']-=1;
                    $id=$session['user']['acctid'];
                    $sql="INSERT INTO pietre (pietra,owner) VALUES ('$pietra','$id')";
                    db_query($sql);
                }else{
                    output("... scopri con immensa gioia che è la ".$pietre[$pietra]."!! `#Possederla ti da un potere
                    particolare che scoprirai al nuovo giorno. Oggi è stato proprio il tuo giorno fortunato ");
                    output($session['user']['name']." !!!`n");
                    $id=$session['user']['acctid'];
                    $sql="INSERT INTO pietre (pietra,owner) VALUES ('$pietra','$id')";
                    db_query($sql);
                }
            }else{
                $row = db_fetch_assoc($result);
                output("`#... odi un sibilo che lentamente cresce d'intensità fino a trasformarsi in un lamento. Una ");
                output("voce profonda e pacata ti dice:`n`n\"");
    
                $caso=e_rand(0,4);
                $account=$row['owner'];
                $sqlz="SELECT name, sex FROM accounts WHERE acctid = $account";
                $resultz = db_query($sqlz) or die(db_error(LINK));
                $rowz = db_fetch_assoc($resultz);
                if ($pietra==1) $switch=1;
                if ($caso==2){
                    output("`%".($switch?"Per tua fortuna":"Purtroppo")." mi".($session['user']['sex']?"a":"o")." car".($session['user']['sex']?"a":"o")." ".$prof[$session['user']['carriera']]);
                    output("`% ".$session['user']['name']."`% la ".$pietre[$pietra]."`% è già in possesso di `@");
                    output($rowz['name']."`%, e non è in mio potere levarla a ".($rowz['sex']?"lei":"lui")." per darla a te.`nSpero ti consolerai ");
                    output("con i `^`b5`b`% turni di combattimento supplementari che ti concedo per aver fatto visita ");
                    output("a questa fonte magica.`#\".`n`nSenti un flusso di energia percorrere il tuo corpo e scopri ");
                    output("che la promessa fatta dalla voce si è avverata !!! `n");
                    $session['user']['turns']+=5;
                    }else{
                        output("`^La pietra che hai scelto è già in possesso di `@".$rowz['name']."`^, ma devo dirti che non mi è mai stat".($rowz['sex']?"a":"o")."
                        particolarmente ".($switch?($rowz['sex']?"antipatica":"antipatico"):($rowz['sex']?"simpatica":"simpatico")).".`n Ho deciso di toglierla a ".($rowz['sex']?"lei":"lui")." per darla a te,
                        sono sicuro che saprai utilizzarla al meglio ".($switch?" ah ah ah":"")."\"`#.`n`n
                        Dopo qualche istante vedi materializzarsi nella polla d'acqua una magnifica pietra finemente lavorata,
                        e chinandoti la raccogli.`n`n");
                        if ($pietra != 1){
                            output("Ammiri la {$pietre[$pietra]} `#sapendo che avrai un potere supplementare ogni Nuovo Giorno. `n");
                        }else{
                            output("`5Scopri con orrore che è la ".$pietre[$pietra]." !!!`5`nPurtroppo il possesso di ");
                            output("questa pietra ti farà perdere 1 turno di combattimento ogni giorno, e l'unico modo ");
                            output("per disfarsene è sperare che un altro guerriero altrettanto sfortunato incappi");
                            output(" nella `&Fonte di Aris`5 e prenda a sua volta la pietra.`n`n");
                            $session['user']['turns']-=1;
                        }
                        $account1=$session['user']['acctid'];
                        $sqlr="UPDATE pietre SET owner = $account1 WHERE pietra = $pietra";
                        db_query($sqlr);
                        $mailmessage = "`@".$session['user']['name']." `@ha trovato la `&Fonte di Aris`@ e le divinità della terra hanno deciso di dare a ".($session['user']['sex']?"lei":"lui")." la tua  ".$pietre[$pietra]." `@!! È il tuo giorno ".($switch?"":"s")."fortunato.";
                        systemmail($account,"`2La tua pietra è ora nella mani di ".$session['user']['name']." `2",$mailmessage);
                    }
                }
        break;
        case "lascia";
            $session['user']['specialinc']="";
            $perdita=intval($session['user']['maxhitpoints']*0.3);
            $session['user']['hitpoints']-=$perdita;
            if ($session['user']['hitpoints'] < 1) {
                $perdita += $session['user']['hitpoints'];
                $session['user']['hitpoints'] = 1;
            }
            output("`4Spaventat".($session['user']['sex']?"a":"o")." dal potere della sorgente, decidi di non sfidare la sorte. `nTi giri in direzione della
            foresta per tornare alla tua abituale attività `nquando senti un gorgoglio provenire dalla polla d'acqua. `n
            Non fai in tempo a girarti che un getto d'acqua ti colpisce alle spalle, e ti getta a terra ferendoti.`n
            `\$`bPerdi $perdita HP !!!!`b");
            addnav("`\$Torna alla Foresta","forest.php");
        break;
    }//chiusura switch
}else{ //chiusura if iniziale
    switch($_GET['op']){
        case"":
            output("`@Nel tuo girovagare per la foresta in cerca di avventura, ti imbatti in una fonte che emana una luce
                misteriosa. Ti sei imbattut".($session['user']['sex']?"a":"o")." nella famosa `&Fonte di Aris`@. Ma tu già conosci il potere di questa sorgente,
                sei già in possesso di una delle sue magiche pietre, la {$pietre[$flagstone]}.");
            addnav("Abbandona questo luogo","forest.php?op=lascia");
            addnav("Rinuncia alla tua pietra","forest.php?op=rinuncia");
        break;
        case "lascia";
            $session['user']['specialinc']="";
            output("`n`nNon essere ingord".($session['user']['sex']?"a":"o")." e lascia che altri guerrieri possano godere delle proprietà taumaturgiche 
            della fonte magica. `nConsolati pensando che ogni Nuovo Giorno usufruirai di un potere speciale supplementare.`n`n `%Perdi un turno per il tempo 
            che hai speso alla fonte, ma bevendo l'acqua della fonte vieni completamente ristorat".($session['user']['sex']?"a":"o").".");
            if ($session['user']['hitpoints']<$session['user']['maxhitpoints']) $session['user']['hitpoints']=$session['user']['maxhitpoints'];
            $session['user']['turns']-=1;
            addnav("`\$Torna alla Foresta","forest.php");
        break;
        case "rinuncia";
            $session['user']['specialinc']="";
            $sqlr="DELETE FROM pietre WHERE pietra = $flagstone";
            db_query($sqlr);
            output("`nLanci la tua pietra nell'acqua della polla, restituendola alla fonte. Ti volti quindi in direzione della foresta, 
                per tornare alla tua abituale attività; il tempo che hai trascorso alla fonte ti fa perdere un turno.");
            $session['user']['turns']-=1;
            if (e_rand(1,2)==1) {
                $perdita=intval($session['user']['maxhitpoints']*0.3);
                $session['user']['hitpoints']-=$perdita;
                if ($session['user']['hitpoints'] < 1) {
                    $perdita += $session['user']['hitpoints'];
                    $session['user']['hitpoints'] = 1;
                }
                output("`n`nImprovvisamente senti un gorgoglio provenire dalla polla d'acqua. `nNon fai in tempo a girarti che un getto d'acqua ti colpisce 
                alle spalle, e ti getta a terra ferendoti.`n`\$`bPerdi $perdita HP !!!!`b");
            }
            addnav("`\$Torna alla Foresta","forest.php");
        break;
        }//chiusura switch
    }
page_footer();
?>