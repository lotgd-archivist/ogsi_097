<?php
/* mercguild.php
Mercenaries Guild Hall by Talisman (Dragonprime)
version 0.6 May 18, 2004 for LotGD version 0.9.7
Based on Travelling Mercenary forest special by Robert (Maddnet) and Talisman
Fight function thanks to Rogue Warrior (rogue.php) by Robert

Most recent version available at http://dragonprime.cawsquad.net

Installation:

Database:  ALTER TABLE `accounts` ADD `hadmerc` INT( 4 ) DEFAULT '0' NOT NULL ;
village.php:  addnav("Mercenary Guild","mercguild.php");
newday.php:  ADD $session['user']['hadmerc'] = 0;  AFTER  $session['user']['usedouthouse'] = 0;
*/

require_once ("common.php");
require_once ("common2.php");
page_header("La Corporazione dei Mercenari");
$luogo = " castello" ;
if ($session['user']['sex']) {
    $sesso="a";
}else {
    $sesso="o";
}
$session['user']['locazione'] = 152;
if ($_GET['op']=="" and ($session['user']['hadmerc']==0)){

    output("`n`2Entri in quella che assomiglia a una taverna di bassa categoria sulla cui insegna leggi `b`#La Corporazione dei Mercenari`b`2, ai tavoli e impegnati in varie attività ");
    output("`nvedi un eterogenea accozzaglia di guerrieri. In un angolo del locale un nano è impegnato ad affilare con la cote la sua enorme ascia da battaglia, mentre ");
    output("`nnell'angolo opposto un elfo controlla l'impennaggio delle sue frecce mortali. ");
    output("`nMentre il tuo sguardo vaga da un lato all'altro del locale incuriosito dall'abbigliamento e dalle armi che non hai mai visto in vita tua, un barbaro seduto ");
    output("`nin uno dei tavoli centrali in compagnia di un gigante,conclude la sua sfida all'ultima birra ruttando rumorosamente prima di stramazzare al suolo,  ");
    output("`n semiubriaco, suscitando fragorose risate da parte dei mercenari seduti ai tavoli vicini.... ");
    output("`nEstrai le tue armi e assumi una posizione di difesa quando uno dei grossi guerrieri ti si avvicina. ");
    output("`n`n`3Riponi le tue armi, ragazz$sesso, a meno che tu non voglia sfidarci per ottenre gratuitamente i nostri servigi.");
    output("`nAltrimenti, puoi parlare a Louie, il nostro capo, ed affittare uno dei membri della nostra corporazione per la modica cifra di `^una gemma.`n`n");
    output("`@Vuoi andartene, pagare una gemma per assoldare un guerriero, o combattere nella speranza di vincere i servigi di un mercenario?`n`n");
    addnav("`&Parla a Louie","mercguild.php?op=give");
    if ($session['user']['turns']>0) { addnav("`\$Combatti","mercguild.php?op=fightem"); }
    addnav("`@Torna al Castello","castelexcal.php");
}else if ($_GET['op']=="give"){

    if ($session['user']['gems']>0){

        addnav("`@Torna al Castello","castelexcal.php");
        $session['user']['hadmerc']=1;

        output("`n`n`%Una gigantesca creatura guarda fuori dal suo ufficio. ");
        output("`nMentre sta uscendo, ti rendi conto che è alto oltre 2 metri e 20 e che ha una lunga cicatrice che gli attraversa il volto.");
        output("`nEgli zoppica leggermente mentre cammina verso di te ma il suo portamento rivela un'enorme massa di muscoli pronti a scattare in un combattimento mortale.  ");
        output("`n`3Bene, chi è il prossimo ? Dammi una delle tue preziose gemme e non resterai deluso!`n`n ");

        assegna_mercenario($luogo,109,true,$sesso);

    }else{

        output("`n`n Infili la mano nel tuo borsellino per scoprire che purtroppo la gemma che pensavi di avere non c'è. Noti gli occhi avidi di Louie attendere impazienti mentre il cerchio di mercenari si stringe attorno a te.`n`n");
        $freemerc=e_rand(1,1);
        if ($freemerc < 3){
            output("`@Lo sguardo furioso di Louie si ammorbidisce.  `n`3Bene va ragass$sesso, è capitato a tutti di arrivare a toccare il fondo dela fortuna.");
            output("`nIl vecchio Bringham laggiù non esce molto spesso, ma è ancora animato dalla voglia di nuove avventure. Lo mando con te a tenersi in esercisio per un po'.");
            output("`n`n`@Lasci la cooperativa in compagnia di un mercenario, riflettendo su quanto tu sia stato fortunato oggi.");
            addnav("`@Torna al Castello","castelexcal.php");
            $session['bufflist'][109] = array("name"=>"`#Bringham","rounds"=>5,"wearoff"=>"Il Vecchio Bringham si lamenta per l'artrite, e preferisce sedersi per riposare.","defmod"=>1.1,"atkmod"=>1.2,"roundmsg"=>"Bringham affonda un fendente a {badguy}","activate"=>"defense");
            $session['user']['hadmerc']=1;
        }else{
            output("`n`@Mentre gli irosi mercenari ti circondano, ti fai una nota mentale di non fare promesse a vuoto in futuro a loro.`n`n");
            $perdita=round($session['user']['maxhitpoints']*0.15);
            $session['user']['hitpoints']-=$perdita;
            $session['user']['hadmerc']=1;
            if ($session['user']['hitpoints']<=0) {
                $_GET['op']="furbetto";
            }else {
                output("`6Riesci ad trovare l'uscita della cooperativa, non prima di aver subito qualche piccola ferita in seguito ai loro pugni.");
                addnav("`@Torna al Castello","castelexcal.php");

            }
        }
    }
}else if ($_GET['op']=="gain"){

    addnav("`@Torna al Castello","castelexcal.php");
    $session['user']['hadmerc']=1;
    assegna_mercenario($luogo,109,false,$sesso);

}else if ($session['user']['hadmerc']>4){
    addnav("`@Torna al Castello","castelexcal.php");
    output("`n`n`%Le porte degli scantinati sono chiuse e noti un biglietto che dice `^'Lascia Perdere'.");

}else if (($_GET['op']!=="search") and ($session['user']['hadmerc']<5) and ($session['user']['hadmerc']>0)){
    if ($session['user']['hadmerc']<5) addnav("`\$Esplora gli Scantinati","mercguild.php?op=search");
    addnav("`@Torna al Castello","castelexcal.php");
    output("`@`n`n`2Entri in quella che assomiglia a una taverna di bassa categoria sulla cui insegna leggi `b`#La Corporazione dei Mercenari`b`2 e sei sorpreso di trovarla vuota.  `nSe ti senti avventuroso, potresti approfittarne per dare un'occhiata in giro.`n`n`%Non si sa mai cosa potresti trovare!");

}else if ($_GET['op']=="search"){
    $shownavs=1;
    $searchguild=(e_rand(1,45));
    $session['user']['hadmerc']++;
    $guildmsg="`%`n`nNonostante hai scrutato attentamente nella penombra, non trovi niente di interessante";

    if ($searchguild==3){
        $guildmsg="`%`n`n`#Fortunello! `%Hai trovato 100 pezzi d'oro su di uno scaffale";
        $session['user']['gold']+=100;
    }

    if ($searchguild==6){
        $guildmsg="`%`n`n`#Grrrrrrrrr `%Un ringhio feroce ti fà rabbrividire e la paura ti paralizza quando vieni attaccato da un Pitbull Infuriato!`n";
        $critter=e_rand(1,3);
        switch ($critter){
            case 1:
                $guildmsg="$guildmsg `nVeloce più del fulmine ti dai alla fuga, riesci a scappare e per tua fortuna resti illes".($session['user']['sex']?"a":"o")." ";
                break;
                debuglog("Riesce a scappare al Pitbull Infuriato negli scantinati");
            case 2:
                $guildmsg="$guildmsg `nCon grande coraggio ti batti rabbiosamente e alla fine di una cruenta battaglia sei il vincitore, anche se hai subito 10 punti di danno";
                $session['user']['hitpoints']-=10;
                if ($session['user']['hitpoints']<1) $session['user']['hitpoints']=1;
                debuglog("Perde 10 HP negli scantinati contro il Pitbull Infuriato");
                break;
            case 3:
                $guildmsg="$guildmsg `nCon grande coraggio ti batti contro la feroce creatura, ma vieni ferito gravemente e riesci a malapena a metterti in salvo";
                $session['user']['hitpoints']=2;
                debuglog("Viene gravemente ferito negli scantinati dal Pitbull Infuriato");
                break;
        }
    }
    if ($searchguild==7){
        $critter=e_rand(1,4);
        $guildmsg="`%`n`n`#Giornata fortunata! `%Hai trovato un sacchetto contenente $critter gemme !!";
        $session['user']['gems']+=$critter;
        debuglog("Trova $critter gemme negli scantinati");
    }

    if ($searchguild==11){
        $guildmsg="`%`n`n`#Ahhhhhhhhhh `%Precipiti in un trabocchetto che si apre all'improvviso sotto i tuoi piedi! `nSebbene tu riesca a salvarti, perdi molti HP e tutto il tuo oro";
        $session['user']['gold']=0;
        $session['user']['hitpoints']=round($session['user']['hitpoints']*0.3);
        debuglog("perde tutto l'oro e molti HP negli scantinati");
    }

    if ($searchguild==17){
        $guildmsg="`%`n`nSu uno scaffale trovi una bottiglia di vino d'annata, molto invitante. Dopo esserti scolato l'intera bottiglia, ti senti più in salute";
        $session['user']['hitpoints']=($session['user']['hitpoints']*1.1);
        $session['user']['drunkenness'] += 50;
        debuglog("Beve una bottiglia di vino negli scantinati");
    }

    if ($searchguild==18){
        $critter=e_rand(0,5);
        $session['user']['hitpoints']-= $critter;
        if ($session['user']['hitpoints'] < 1) $session['user']['hitpoints']=1;
        $guildmsg="`%`n`nVieni attaccato da un pipistrello vampiro! Riesci a fuggire, ma perdi $critter HP.`n";
        debuglog("Perde $critter HP negli scantinati attaccato da un pipistrello");
    }

    if ($searchguild==24){
        $guildmsg="`%`n`n`#Arghhhhhhhh `%Appoggi il piede sul meccanismo che fa scattare una trappola mortale, una scheggia di vetro che ti trapassa il corpo. Sei Morto!`n";
        $session['user']['alive']=false;
        $shownavs=0;
        $session['user']['hitpoints']=0;
        $debuglog("Muore in una trappola negli scantinati");
        addnav("`\$Piangi da Ramius","news.php");
    }

    if ($searchguild==26){
        $critter = e_rand(1,10);
        $guildmsg="`%`n`nVieni attaccato da $critter pipistrelli!!! Riesci a fronteggiarli e batterli ma anche loro te le hanno suonate ...";
        $session['user']['hitpoints']-=($critter *2);
        debuglog("Viene attaccato da $critter pipistrelli negli scantinati");
        if ($session['user']['hitpoints']<1) $session['user']['hitpoints']=1;
    }

    if ($searchguild==28){
        $guildmsg="`%`n`nTrovi del pane e sentendo i morsi della fame decidi di mangiarlo.  Ti senti più forte.";
        $session['user']['maxhitpoints']+=1;
        debuglog("Guadagna 1 HP trovando del pane negli scantinati");
    }

    if ($searchguild==30){
        $guildmsg="`%`n`nTrovi della carne affumicata e decidi di mangiarla, pressato dai morsi della fame. `nPoco dopo senti lo stomaco in subbuglio, era andata a male !!  Ti senti debole.";
        $session['user']['maxhitpoints']-=1;
        debuglog("Perde 1 HP mangiando carne affumicata negli scantinati");
    }

    if ($searchguild==32){
        $guildmsg="Trovi una fiala contenente un liquido.  Ti sembra sufficientemente sicura e decidi di berla.  I tuoi HP sono riportati al massimo.";
        if ($session['user']['hitpoints']<$session['user']['maxhitpoints']){
            $session['user']['hitpoints']=$session['user']['maxhitpoints'];
        }
        debuglog("Viene guarito completamente bevendo una fiala trovata negli scantinati");
    }

    if ($searchguild==34){
        $guildmsg="`%`n`nSebbene tu non abbia trovato nulla, ti senti stranamente vigoroso. Guadagni un combattimento.";
        $session['user']['turns']++;
        debuglog("Guadagna 1 combattimento negli scantinati");
    }

    if ($searchguild==36){
        $hploss=round($session['user']['hitpoints']*.09);
        $guildmsg="`%`n`nTrovi una piccola fiala contenente una strana pozione.  Ignorando gli avvertimenti di tua madre, la bevi.`nArgh ... c'è del veleno dentro, perdi $hploss HP.";
        $session['user']['hitpoints']-=$hploss;
        if($session['user']['hitpoints'] <1 ) $session['user']['hitpoints']=1;
        debuglog("Perde $hploss bevendo una fiala di veleno negli scantinati");
    }

    if ($searchguild==38){
        $guildmsg="`%`n`nTi ritrovi di fronte a una piccola porticina. Avanzi risoluto e decidi di aprirla. Ti ritrovi sperduto nel mezzo della foresta!";
        $shownavs=0;
        debuglog("Trova una porta negli scantinati e si ritrova in foresta");
        addnav("`@Nella Foresta","forest.php");
    }

    if ($searchguild==42){
        $guildmsg="`%`n`nNoti che un fusto di birra sta perdendo, Nel vedere della buona birra fuoriuscire così per terra e andare sprecata, ne bevi un boccale... due...o tre.`nPerdi un combattimento bighellonando.";
        $session['user']['turns']--;
        debuglog("Perde un combattimento negli scantinati");
    }

    if ($shownavs==1){
        addnav("`#Esplora gli Scantinati","mercguild.php?op=search");
        addnav("`@Torna al Castello","castelexcal.php");
    }
    output("$guildmsg");

}else if($_GET['op']=="fightem"){
    if (!isset($session)) exit();
    output("`n`2Estrai la tua arma e ti prepari a fronteggiare i mercenari. Un grosso guerriero esce dalla folla, e ti interroghi sulla saggezza della tua mossa`b. `n");
    output("`n`2Se vincerai questa battaglia, ti guadagnerai i suoi servigi...ma se perdi, `\$Ramius `2otterrà i tuoi. `n");
    output("`6Combattere ti costerà un turno.`n");
    output("`^Fai la tua scelta, ma falla saggiamente...");
    addnav("Combatti il Mercenario","mercguild.php?op=fight1");
    addnav("Scappa Via","mercguild.php?op=run");

}else if ($_GET['op']=="fight1"){
    $session['user']['turns']--;
    $badguy = array( "creaturename"=>"Mercenario Attaccabrighe"
                    ,"creaturelevel"=>1
                    ,"creatureweapon"=>"Spada degli Antichi"
                    ,"creatureattack"=>2
                    ,"creaturedefense"=>2
                    ,"creaturehealth"=>1
                    ,"diddamage"=>0);

    //buff badguy up a little or tone him down
    $userlevel=$session['user']['level']+2;
    $userattack=$session['user']['attack']+e_rand(9,29);
    $userhealth=$session['user']['hitpoints']+e_rand(25,100);
    $userdefense=$session['user']['defense']+e_rand(9,29);
    $badguy['creaturelevel']+=$userlevel;
    $badguy['creatureattack']+=$userattack;
    $badguy['creaturehealth']=$userhealth;
    $badguy['creaturedefense']+=$userdefense;
    $session['user']['badguy']=createstring($badguy);
    $fight=true;

}else{
    if ($_GET['op'] == "fight") {
        $fight=true;
    } elseif ($_GET['op'] == "run") {
           output("`n`^Scappi da questo combattimento come un cucciolo bastonato!");
           addnav("`%Scappa come una Lepre","village.php");
           debuglog("scappa come una lepre da mercenario attaccabrighe");
           $fight=false;
        }
}
if ($fight){
    include "battle.php";
    if ($victory){
        debuglog("batte il mercenario attaccabrighe e guadagna i servigi di uno di loro");
        output("`n`2Hai battuto il `4 Mercenario attaccabrighe`2!  `n`^I mercenari sono notevolmente impressionati e acconsentono a mandare uno di loro a battersi al tuo fianco! `n");
        addnav("`^Prendi un Mercenario","mercguild.php?op=gain");
    }else if ($defeat){
        $session['user']['specialinc']="";
        $session['user']['gold']= 0;
        $session['user']['experience']*=0.8;
        $session['user']['hadmerc']=1;
        addnews($session['user']['name']." `2è stato ucciso dal `4Mercenario attaccabrighe`2 nella Cooperativa dei Mercenari.");
        debuglog("è stato ucciso dal mercenario attaccabrighe perdendo tutto l'oro che aveva con se.");
        output("`&Sei stato ucciso dal `4Mercenario attaccabrighe`&! `n Egli ti sottrae tutto il tuo `^Oro`& per insegnarti meglio la lezione!");
        addnav("O?`\$Terra delle Ombre","shades.php");
        }else{
        fightnav();
    }
}
if ($_GET['op'] == "furbetto" AND $session['user']['hitpoints'] <= 0){
      $oro = $session['user']['gold'];
      debuglog("ucciso dai mercenari perchè senza gemma. Perde $oro oro e 15% exp.");
      output("`n`n`^Purtroppo i pugni dei mercenari ti hanno ucciso ! `nTi ritrovi a passare dalla vita alla morte e rimuginare sulla tua stupidità.`n");
      output("`\$Perdi tutto l'oro che avevi con te, ed anche il 15% di esperienza !!`n");
      output("`&La prossima volta non tenterai di imbrogliare Louie ed i suoi mercenari !!`n");
      addnav("Terra delle Ombre","shades.php");
      $session['user']['gold'] = 0;
      $session['user']['experience'] *= 0.85;
      $session['user']['experience'] = intval($session['user']['experience']);
      $session['user']['alive']=false;
}
page_footer();
?>