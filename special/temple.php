<?php
/* *******************
Temple of the gods
Written by Romulus von Grauhaar
    Visit http://www.scheibenwelt-logd.de.vu

This Special adds an event where players can donate to a temple in the forest.
When the donated gold reachs the limit, a random event happens.
This limit can be set at the beginning of the skript and the names of the gods too.

Before Using this Special-event you have to do the following SQL:

ALTER TABLE `accounts` ADD `tempelgold` INT( 30 ) DEFAULT '0' NOT NULL ;

As an option, you can add to the user.php the following line:
    "tempelgold"=>"Gold donated in the temple,int",
so that superusers can edit the amount donated by the player in the superuser grotto.



******************* */


// The following lines are for configuration od the script. $spendenbetrag ist the
// limit of gold a player must reach to get an event from the gods.
// The other values are the names of the gods.

$spendenbetrag = "5500";
$gott_gem = "Minerva";
$gott_defense = "Ohm";
$gott_hp = "Fato";
$gott_attack = "Mercurio";
$gott_charm = "Afrodante";
$gott_fight = "Nuggan";
$gott_kill = "Bel-Shamharoth";
$gott_hurt = "Astaroth";


$session['user']['specialinc']="temple.php";
if ($_GET['op']==""){
    output("`@Nel tuo girovagare nella foresta ti imbatti in un tempio. Una costruzione imponente ma in rovina. Entri in questo luogo sacro,
    e noti che il tempio deve essere restaurato. L'unica cosa che sembra essere intatta è la cassetta dei poveri dove un cartello recita:
    `n`&\"Amic".($session['user']['sex']?"amica visitatrice":"amico visitatore")." , il nostro tempio è in decadenza, e me ne dispiaccio. Per favore offri qualche pezzo d'oro per la sua ricostruzione.
    Gli dei te ne saranno grati. L'Arciprete.\"`@");
    output("`n`nCosa decidi di fare?");
    addnav("`@Fai un'offerta","forest.php?op=spenden");
    addnav("`\$Abbandona il Tempio","forest.php?op=verlassen");
$session['user']['specialinc']="temple.php";
}
else if ($_GET['op']=="verlassen"){
    output("`@Abbandoni il vecchio Tempio in sfacelo dietro di te, e torni nella foresta in cerca di brividi.");
    $session['user']['specialinc']="";
    addnav("`@Torna alla Foresta","forest.php");
}
else if ($_GET['op']=="spenden"){
$session['user']['specialinc']="temple.php";
addnav("Offerte");
addnav("`6100 Pezzi d'Oro","forest.php?op=spendeneingang&betrag=100");
addnav("`6500 Pezzi d'Oro","forest.php?op=spendeneingang&betrag=500");
addnav("`^1000 Pezzi d'Oro","forest.php?op=spendeneingang&betrag=1000");
addnav("`^5000 Pezzi d'Oro","forest.php?op=spendeneingang&betrag=5000");
addnav("`\$Niente","forest.php?op=verlassen");
output(" ".($session['user']['tempelgold']>0?"Hai già ":"Non hai ancora ")."donato oro alle divinità del tempio.`n Quanto pezzi d'oro vuoi donare per il restauro del tempio?",true);
}
else if ($_GET['op']=="spendeneingang"){
if ($_GET['betrag']>$session['user']['gold'])

    {
        output("`@Oooops. Non hai tutto quell'oro con te. Prega, sperando che gli dei non abbiano notato il tuo tentativo di imbroglio.`n`n");
        output("Abbandoni velocemente il tempio, prima che gli dei notino il tuo errore.");
        $session['user']['specialinc']="";
        addnav("`@Torna alla Foresta","forest.php");
    }
if ($_GET['betrag']<=$session['user']['gold'])
    {
        $betrag=$_GET['betrag'];
        output("`^`bFai un'offerta di `&$betrag`^ pezzi d'oro per il restauro del tempio. ");
        debuglog("dona $betrag oro per il restauro del tempio");
        $session['user']['tempelgold']+=$betrag;
        $session['user']['gold']-=$betrag;
        output("Gli dei del tempio hanno apprezzato la tua offerta.`b`n");
        $session['user']['specialinc']="";
        addnav("`@Abbandona il Tempio","forest.php");
if($session['user']['tempelgold'] >= $spendenbetrag)
    {
    output("`@Dopo che hai gettato l'oro nella cassetta delle offerte, improvvisamente senti il rumore di un tuono in lontananza.
    Sembra che gli dei del tempio abbiano notato il tuo nobile gesto.`n`n");
    switch(e_rand(1,6))
        {
          case 1:
              output("`@La dea del tempio, `^$gott_gem`@, appare di fronte a te. Le tue offerte ti hanno fatto guadagnare `\$10 gemme`@!");
    $session['user']['gems']+=10;
    $session['user']['tempelgold']-=5500;
    debuglog("ottiene 10 gemme nel tempio");
    addnews("`%".$session['user']['name']."`7 ottiene da $gott_gem in un tempio una ricca ricompensa in cambio delle sue offerte.");
                break;
          case 2:
              output("`@Il dio del tempio, `^$gott_defense`@ ,appare di fronte a te.  Grazie al potere degli dei ottieni `\$1 Punto Difesa `bpermanente`b`@ !");
    $session['user']['bonusdefence']+=1;
    $session['user']['defence']+=1;
    $session['user']['tempelgold']-=5500;
    debuglog("ottiene 1 punto difesa permanente al tempio");
    addnews("`7La pelle di `%".$session['user']['name']."`7 è resa più resistente da $gott_defense.");
                break;
          case 3:
              output("`@Il dio del tempio, `^$gott_attack`@, appare di fronte a te.  Grazie al potere degli dei ottieni `\$1 Punto Attacco `bpermanente`b`@ !");
    $session['user']['bonusattack']+=1;
    $session['user']['attack']+=1;
    $session['user']['tempelgold']-=5500;
    debuglog("ottiene 1 punto attacco permanente al tempio");
    addnews("`7I muscoli di `%".$session['user']['name']."`7 sono stati potenziati da $gott_attack.");
                break;
          case 4:
              output("`@Il dio del tempio, `^$gott_hp`@, appare di fronte a te. Il tuo desiderio di longevità è stato esaudito. Ottieni `\$5 HP `bpermanenti`b`@ !");
    $session['user']['hitpoints']+=5;
    $session['user']['maxhitpoints']+=5;
    $session['user']['tempelgold']-=5500;
    debuglog("ottiene 5 HP permanenti al tempio");
    addnews("`%".$session['user']['name']."`7 guadagna della forza vitale supplementare ingraziandosi $gott_hp.");
                break;
          case 5:
              output("`@Il dio del tempio, `^$gott_fight`@, appare di fronte a te. Il potere degli dei ti fa guadagnare in resistenza. Ottieni `\$20`@ combattimenti supplementari !");
    $session['user']['turns']+=20;
    $session['user']['tempelgold']-=5500;
    debuglog("ottiene 20 combattimenti extra al tempio");
    addnews("`%".$session['user']['name']."`7 ottiene molti combattimenti extra grazie a $gott_fight.");
                break;
          case 6:
              output("`@Il dio del tempio, `^$gott_charm`@, appare di fronte a te. Grazie alla benevolenza degli dei, sei decisamente più bell".($session['user']['sex']?"a":"o")." di prima. Guadagni `\$20 punti fascino`@ in cambio delle tue offerte !");
    $session['user']['charm']+=20;
    $session['user']['tempelgold']-=5500;
    debuglog("ottiene 20 punti fascino al tempio");
    addnews("`%".$session['user']['name']."`7 è stat".($session['user']['sex']?"a":"o")." res".($session['user']['sex']?"a":"o")." molto più affascinante da $gott_charm.");
                break;
          case 7:
              output("`@Il dio del tempio, `^$gott_hurt`@, appare di fronte a te. A cosa pensavi per aver evocato un demone famoso per la sua crudeltà ? Dopo un colpo `\$DURO`@ ti risvegli dallo svenimento e scopri di aver perso praticamente quasi tutti i tuoi HP.");
    $session['user']['hitpoints']=1;
    $session['user']['tempelgold']-=5500;
    debuglog("perde tutti gli HP tranne uno al tempio");
    addnews("`%".$session['user']['name']."`7 è stat".($session['user']['sex']?"a":"o")." ferit".($session['user']['sex']?"a":"o")." gravemente in un tempio da $gott_hurt. Non dovrebbe giocare con il potere degli dei.");
                break;
          case 8:
              output("`@Il dio del tempio, `^$gott_kill`@, appare di fronte a te. A cosa pensavi per aver evocato un demone famoso per essere un assassino? $gott_kill ti sfiora e ... sei mort".($session['user']['sex']?"a":"o")." !!!`n");
              output("`b`%Perdi tutto l'oro che avevi con te e il 5% di esperienza !!!`b`n");
    debuglog("perde {$session['user']['gold']}, il 5% di exp e muore in un tempio");
    $session['user']['hitpoints']=0;
    $session['user']['tempelgold']-=5500;
    $session['user']['alive']=false;
    $session['user']['gold']=0;
    $session['user']['experience']*=0.95;
    addnews("`%".$session['user']['name']."`7 è stat".($session['user']['sex']?"a":"o")." uccis".($session['user']['sex']?"a":"o")." in un tempio da $gott_kill. Non dovrebbe giocare con il potere degli dei.");
                break;
        } //switch
    } // benötigten betrag erreicht?
    } //spendeneingang
$session['user'][specialinc]="";
}
        page_footer();
?>