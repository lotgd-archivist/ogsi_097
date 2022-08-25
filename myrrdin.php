<?php
/*
Myrrdin the teacher, by Spider

you need to make a small change to common.php for this addition to work properly:

find:
function increment_specialty(){

and replace the entire function with this:

function increment_specialty($force=false){
  global $session;
  if ($force==false){
        if ($session['user']['specialty']>0){
            $skillnames = array(1=>"Dark Arts","Mystical Powers","Thievery");
            $skills = array(1=>"darkarts","magic","thievery");
            $skillpoints = array(1=>"darkartuses","magicuses","thieveryuses");
            $session['user'][$skills[$session['user']['specialty']]]++;
            output("`nYou gain a level in `&".$skillnames[$session['user']['specialty']]."`# to ".$session['user'][$skills[$session['user']['specialty']]].", ");
            $x = ($session['user'][$skills[$session['user']['specialty']]]) % 3;
            if ($x == 0){
                output("you gain an extra use point!`n");
                $session['user'][$skillpoints[$session['user']['specialty']]]++;
            }else{
                output("only ".(3-$x)." more skill levels until you gain an extra use point!`n");
            }
        }else{
            output("`7You have no direction in the world, you should rest and make some important decisions about your life.`n");
        }
    }
    else{
        $skillnames = array(1=>"Dark Arts","Mystical Powers","Thievery");
        $skills = array(1=>"darkarts","magic","thievery");
        $skillpoints = array(1=>"darkartuses","magicuses","thieveryuses");
        $session['user'][$skills[$force]]++;
        output("`nYou gain a level in `&".$skillnames[$force]."`# to ".$session['user'][$skills[$force]].", ");
        $x = ($session['user'][$skills[$force]]) % 3;
        if ($x == 0){
            output("you gain an extra use point!`n");
            $session['user'][$skillpoints[$force]]++;
        }else{
            output("only ".(3-$x)." more skill levels until you gain an extra use point!`n");
        }
    }
}
(nota, funzione modificata per avere altre specialità)
*/

//Parametri setup
$orospec=2000;
$gemmespec=2;
$oroaltro=5000;
$gemmealtro=4;

require_once "common.php";
page_header("L'Ufficio di Myrrdin");
$session['user']['locazione'] = 156;

output("`7`c`bL'Ufficio di Myrrdin`b`c`n");
$skills = array(1 => "`\$Arti Oscure", "`%Poteri Mistici", "`^Furto", "`3Militare","`\$Seduzione","`^Tattica","`@Pelle di Roccia","`#Retorica","`%Muscoli","`3Natura","`&Clima","`^Elementalista","`6Rabbia Barbara","`5Canzoni del Bardo");
switch((int)$session['user']['specialty']){
case 1:
    $c="`$";
    break;
case 2:
    $c="`%";
    break;
case 3:
    $c="`^";
    break;
case 4:
    $c="`3";
    break;
case 5:
    $c="`\$";
    break;
case 6:
    $c="`^";
    break;
case 7:
    $c="`@";
    break;
case 8:
    $c="`#";
    break;
case 9:
    $c="`%";
    break;
case 10:
    $c="`3";
    break;
case 11:
    $c="`&";
    break;
case 12:
    $c="`^";
    break;
case 13:
    $c="`6";
    break;
case 14:
    $c="`5";
    break;
default:
    $c="`0";
    break;
}

if ($_GET['op']==""){
    output("Entri negli uffici di Myrrdin, la stanza principale è enorme ed è ripiena di scaffali con libri, ");
    output("scrivanie e strani attrezzi di cui non ti è chiara l'utilità.");
    output("Diverse persone sono intente a studiare dei libri, e puoi vedere Myrrdin seduto alla sua enorme ");
    output("scrivania di pregiato legno di quercia, intento ad esaminare un bizzarro aggegio metallico. ");
    output("Non sapendo di cosa si tratta, non ti avvicini più di tanto.`n");
    output("Con un colpo di tosse cerchi di attirare l'attenzione di Myrrdin su di te, e ci riesci.`n`n");
    if ($session['user']['specialty']>0 && $session['user']['specialty']<4){
        output("`3\"Oh, salve, ti chiedo scusa ma ero distratto.  Vediamo un po'... Sì, tu devi essere specializzato ");
        output("in ".$skills[$session['user']['specialty']]."`3. Io posso aiutarti ad impararne di più, o anche insegnarti ");
        output("qualcos'altro se preferisci.");
        output("Ovviamente apprendere qualcosa in cui non hai familiarità non sarà altrettanto semplice, e ti costerà di più.\"`n`n");
    }
    else{
        output("`3\"Oh, salve, ti chiedo scusa ma ero distratto.  Vediamo un po'... Tu non mi sembri specializzato ");
        output("in nulla. Posso insegnarti qualcosa, ma non sarà semplice per te da apprendere non avendone ");
        output("familiarità, e ti costerà parecchio.\"`n`n");
    }
    for ($i=1;$i<=count($skills);$i++) {
        output("`3\"Posso insegnarti qualcosa di ".$c.$skills[$i]."`3 al prezzo di");
        if ($session['user']['specialty']==$i) {
            output("`^$orospec pezzi d'oro`3, `&$gemmespec gemme`3 e 1 turno\"`n");
        } else {
            if ($i==12) {
                $oroaltro*=2;
                $gemmealtro*=2;
            }
            output("`^$oroaltro pezzi d'oro`3, `&$gemmealtro gemme`3 e 2 turni\"`n");
        }
        addnav("Lezione di $c $skills[$i]`0","myrrdin.php?op=learn&type=".$i);
    }
}

else if ($_GET['op']=="learn"){
    if ($_GET['type']==$session['user']['specialty']) $cheap=true;
    if ($cheap==true){
        if ($session['user']['gold']>=$orospec && $session['user']['gems']>=$gemmespec && $session['user']['turns']>0){
            output("`3\"Vedo che vuoi imparare di più riguardo alla tua specialità. Bene, mi piacciono le persone ");
            output("che studiano ciò che più interessa loro.");
            output(" Sediamoci a quel tavolo e ti insegnerò qualcosa di nuovo riguardo a ".$c.$skills[$_GET['type']]."`3.\"`0`n`n");
            output("Ti siedi ed ascolti i saggi insegnamenti di Myrrdin.");
            increment_specialty();
            $session['user']['gold']-=$orospec;
            $session['user']['gems']-=$gemmespec;
            $session['user']['turns']--;
            addnav("Altra Lezione","myrrdin.php");
        }elseif ($session['user']['gold']<$orospec){
            output("`3\"Non hai abbastanza pezzi d'oro per pagare una mia lezione, non ti insegnerò nulla se non sei ");
            output("in grado di pagarmi! Ricorda, i benefici della conoscenza valgono molto più del suo prezzo.\"");
        }elseif ($session['user']['gems']<$gemmespec){
            output("`3\"Non hai abbastanza gemme per pagare una mia lezione, non ti insegnerò nulla se non sei ");
            output("in grado di pagarmi! Ricorda, i benefici della conoscenza valgono molto più del suo prezzo.\"");
        }else{
            output("`3\"Non hai abbastanza turni! Sei troppo stanco per poter imparare qualcosa, non sprecherò ");
            output("il mio tempo con te e non ti farò sprecare i tuoi averi.\"");
        }
    }
    else{
        if ($_GET['type']>11) {
                $oroaltro*=2;
                $gemmealtro*=2;
            }
        if ($session['user']['gold']>=$oroaltro && $session['user']['gems']>=$gemmealtro && $session['user']['turns']>1){
            output("`3\"Meraviglioso! E così vuoi ampliare le tue conoscenze in un secondo campo! Più cose si ");
            output("conoscono, più si viene ricompensati dalla vita\"`n");
            output("`3\"Sediamoci a quel tavolo e ti insegnerò qualcosa di nuovo riguardo a ".$c.$skills[$_GET['type']]."`3.\"`0`n`n");
            output("Ti siedi ed ascolti i saggi insegnamenti di Myrrdin.");
            increment_specialty($_GET['type']);
            $session['user']['gold']-=$oroaltro;
            $session['user']['gems']-=$gemmealtro;
            $session['user']['turns']-=2;
            addnav("Altra Lezione","myrrdin.php");
        }elseif ($session['user']['gold']<$oroaltro){
            output("`3\"Non hai abbastanza pezzi d'oro per pagare una mia lezione, non ti insegnerò nulla se non sei ");
            output("in grado di pagarmi! Ricorda, i benefici della conoscenza valgono molto più del suo prezzo.\"");
        }elseif ($session['user']['gems']<$gemmealtro){
            output("`3\"Non hai abbastanza gemme per pagare una mia lezione, non ti insegnerò nulla se non sei in ");
            output("grado di pagarmi! Ricorda, i benefici della conoscenza valgono molto più del suo prezzo.\"");
        }else{
            output("`3\"Non hai abbastanza turni! Sei troppo stanco per poter imparare qualcosa, non sprecherò il ");
            output("mio tempo con te e non ti farò sprecare i tuoi averi.\"");
        }
    }
}

addnav("Uscita");
addnav("Torna all'Accademia","accademia.php");
addnav("V?Torna al Villaggio","village.php");

page_footer();
?>