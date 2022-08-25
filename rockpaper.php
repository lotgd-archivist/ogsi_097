<?php
/* Rock, Paper, Scissors
An Add on for inn.php LoGD version 097
Written by Robert of Maddnet
version 1.3 Sep2004
Latest version is available at Dragon Prime
http://dragonprime.net

Simple little game to entertain players
INSTALL INSTRUCTIONS:
    open inn.php
    find: addnav("Ask Seth to entertain","inn.php?op=seth&subop=hear");
    add under:  addnav("Play Seth a game","rockpaper.php");

Game default settings can be changed - see below
Feel free to alter to suit but please keep this entire comment tag intact
*/
//opzioni blocco gioco - sceriffo
$bloccoon=1; //(1 il blocco è abilitato, 0 il bloco è disabilitato)
$livellosn=0; //(1: turni per newday per livello; 0: turni totali per newday)
$turni=150; //(numero di turni per cui parte il controllo dello sceriffo, sia per livello sia in totale, vedi sopra)
$giocate=230; //(numero minimo di giocate consentite, per livello o in totale, vedi sopra. E' un bonus sul controllo dello sceriffo)
//fine opzioni blocco gioco - sceriffo

require_once "common.php";
// To make a free game (no wagering) change the next line from 1 to 0
$money = 1;
// You can change the cost to whatever you like in the next line (default is 2 gold)
// IF you make a FREE game, no wager (see lines 16-17 above) change to 0
$cost = 100;
// Do not change the rest unless you know what your doing!!
$who="Seth";
$a="`8Pietra";
$b="`&Carta";
$c="`2Forbici";
$d="Scegli";
$e="ha scelto";
// Free game lose message
$lmsg="Buona fortuna per la prossima volta";
// Free game win message
$wmsg="Non è stupendo?";
$sceriffo=0; //Modifica di Sook (vedere sotto)
page_header("Pietra, Carta, Forbici");
$session['user']['locazione'] = 165;
if ((($livellosn == 1) && ($session['user']['rockpaper'] >= ($session['user']['level']*$turni)) || ($session['user']['rockpaper'] >= $turni)) AND ($money==1)) {
     output ("Diverse persone si sono avvicinate al tavolo a vedervi giocare, ti interroghi se non sia troppo pericoloso continuare");
     output ("con tutta questa attenzione puntata sul tavolo; è risaputo che lo sceriffo passa di frequente da Cedrik per una birra.");
}
     output("`c<font size='+1'>`3Tu e Seth giocate</font>`c`n",true);
// While I can't force you to keep the next line - It would be appreciated
if ($_GET[op]==""){
    output("`n`n Noti che $who si sta aggirando per la locanda con un'espressione estremamente annoiata,");
    output("`n se tu volessi confortarlo, potresti chiedergli di fare qualche partita a $a`3, $b`3, $c`3.");
if ($money == 1){
    output("`n`n Ogni giocata ti costerà $cost pezzi d'oro");
    if ($session['user']['gold']< $cost) output("`n`n `& Che peccato, non ne hai abbastanza per giocare.");
    }else{
    output("Sai, $who è `isempre`i disponibile a fare qualche partita in amicizia con te.");
    }
}else if ($_GET[op]=="1"){
    $session['user']['rockpaper'] ++; //Sook, numero di giocate
    switch(e_rand(1,3)){
        case 1: output("`n`n`3 $d $a`3 - $who $e $a`3 - parità!");break;
        case 2: output("`n`n`3 $d $a`3 - $who $e $b`n $b `3copre $a`3, `\$ Hai Perso`3! ");
        if ($money == 1){
            output("`n Dai $cost pezzi d'oro a $who");
            $session['user']['gold']-=$cost;
        }else{ output("`n $lmsg ");}
        break;
        case 3: output("`n`n`3 $d $a`3 - $who $e $c`n $a `3spunta le $c`3, `^ hai Vinto`3! ");
        if ($money == 1){
            output("`n $who ti da $cost pezzi d'oro "); $session['user']['gold']+=$cost;
        }else{ output("`n $wmsg "); }break;
    }
}
else if ($_GET[op]=="2"){
    $session['user']['rockpaper'] ++; //Sook, numero di giocate
    switch(e_rand(1,3)){
        case 1: output("`n`n`3 $d $b`3 - $who $e $a `n $b `3copre $a`3, `^ Hai Vinto`3! ");
        if ($money == 1){
            output("`n $who ti da $cost pezzi d'oro "); $session['user']['gold']+=$cost;
        }else{ output("`n $wmsg "); }
        break;
        case 2: output("`n`n`3 $d $b`3 - $who $e $b `3 - parità! ");break;
        case 3: output("`n`n`3 $d $b`3 - $who $e $c `n $c `3taglia $b, `\$ Hai Perso`3 ");
        if ($money == 1){
            output("`n Dai $cost pezzi d'oro a $who");
            $session['user']['gold']-=$cost;
        }else{ output("`n $lmsg ");}
        break;
    }
}else if ($_GET[op]=="3"){
    $session['user']['rockpaper'] ++; //Sook, numero di giocate
    switch(e_rand(1,3)){
        case 2: output("`n`n`3 $d $c`3 - $who $e $a`n $a `3spunta $c`3, `\$ Hai Perso`3! ");
        if ($money == 1){
            output("`n Dai $cost pezzi d'oro a $who");
            $session['user']['gold']-=$cost;
        }else{ output("`n $lmsg ");}
        break;
        case 1: output("`n`n`3 $d $c`3 - $who $e $b`n $c `3taglia $b`3, `^ Hai Vinto`3! ");
        if ($money == 1){
            output("`n $who ti da $cost pezzi d'oro ");
            $session['user']['gold']+=$cost;
        }else{ output("`n $wmsg "); }
        break;
        case 3: output("`n`n`3 $d $c`3 - $who $e $c`3 - parità! ");break;
    }
}else if ($_GET[op]=="rule"){
    output("`n`n$a`3, $b`3, $c `3è un gioco molto semplice e facile da giocare.`n`n");
    output("Scegli uno dei tre oggetti: $a`3, $b `3o $c`3.`n");
    output("Il tuo avversario farà altrettanto: $a`3, $b `3o $c`3.`n`n");
    output("`^Chi è il vincitore?`n");
    output("`3Se scegliete entrambi lo stesso oggetto, sarà un pareggio, nessuno vince.`n");
    output("$a `3vince su $c `3perchè $a `3spunta $c`n");
    output("$b `3vince su $a `3perchè $b `3copre $a`n");
    output("$c `3vince su $b `3perchè $c `3taglia $b`n");
}

//modifica sceriffo by Sook
if ($bloccoon == 1) {
    if ($livellosn=1) {
        if ((($session['user']['rockpaper']) > ($session['user']['level']*$turni)) AND ($money==1) AND (($_GET[op]=="1") OR ($_GET[op]=="2") OR ($_GET[op]=="3"))) {
              $sceriffo = e_rand (1,60) + $session['user']['rockpaper'] - $session['user']['level']*$giocate;
              if ($sceriffo > 50) {
                  output("`n`n`n`&Senti un colpo di tosse, lo sceriffo era alle tue spalle e ti ha visto giocare!`n`n");
                  output("Ti afferra un braccio e te lo stringe dietro la schiena, facendoti quasi urlare dal dolore. `#\"Sarebbe così gentile da seguirmi in caserma senza fare storie?\"`&, dice sarcasticamente.");
                  output("`nNella locanda cala il silenzio, Seth è come sparito nel nulla e tanti occhi sono puntati su di te.");
                  output("`n`nNon hai possibilità di scappare, lo sceriffo non ti ha ancora liberato il braccio, e non credi proprio che lo farà nella locanda.");
                  output("Tuo malgrado, sei costretto a fare quello che ti ha detto; ti alzi dal tavolo e, `i\"accompagnato\"`i dallo sceriffo, ti dirigi alla porta d'uscita, per andare in caserma.");
                  $session['user']['evil'] += 10;
                  $name=$session['user']['name'];
                  addnews("`1$name `1è stato colto dallo sceriffo mentre giocava a soldi con Seth!");
                  addnav("C?Continua","constable.php?op=rockpaper");
              }
        }
        if ($sceriffo < 51) {
            if ($session['user']['gold']>= $cost) {
                addnav("Scegli");
                addnav("P?`8Pietra","rockpaper.php?op=1");
                addnav("C?`&Carta","rockpaper.php?op=2");
                addnav("F?`2Forbici","rockpaper.php?op=3");
                addnav("altro");
            }
            addnav("R?Regole","rockpaper.php?op=rule");
            addnav("uscita dal gioco");
            addnav("T?Torna alla Locanda","inn.php");
        }
    } else {
        if ((($session['user']['rockpaper']) > ($turni)) AND ($money==1) AND (($_GET[op]=="1") OR ($_GET[op]=="2") OR ($_GET[op]=="3"))) {
              $sceriffo = e_rand (1,60) + $session['user']['rockpaper'] - $giocate;
              if ($sceriffo > 50) {
                  output("`n`n`n`&Senti un colpo di tosse, lo sceriffo era alle tue spalle e ti ha visto giocare!`n`n");
                  output("Ti afferra un braccio e te lo stringe dietro la schiena, facendoti quasi urlare dal dolore. `#\"Sarebbe così gentile da seguirmi in caserma senza fare storie?\"`&, dice sarcasticamente.");
                  output("`nNella locanda cala il silenzio, Seth è come sparito nel nulla e tanti occhi sono puntati su di te.");
                  output("`n`nNon hai possibilità di scappare, lo sceriffo non ti ha ancora liberato il braccio, e non credi proprio che lo farà nella locanda.");
                  output("Tuo malgrado, sei costretto a fare quello che ti ha detto; ti alzi dal tavolo e, `i\"accompagnato\"`i dallo sceriffo, ti dirigi alla porta d'uscita, per andare in caserma.");
                  $session['user']['evil'] += 10;
                  $name=$session['user']['name'];
                  addnews("`1$name `1è stato colto dallo sceriffo mentre giocava a soldi con Seth!");
                  addnav("C?Continua","constable.php?op=rockpaper");
              }
        }
        if ($sceriffo < 51) {
            if ($session['user']['gold']>= $cost) {
                addnav("Scegli");
                addnav("P?`8Pietra","rockpaper.php?op=1");
                addnav("C?`&Carta","rockpaper.php?op=2");
                addnav("F?`2Forbici","rockpaper.php?op=3");
                addnav("altro");
            }
            addnav("R?Regole","rockpaper.php?op=rule");
            addnav("uscita dal gioco");
            addnav("T?Torna alla Locanda","inn.php");
        }
    }
} else {
        addnav("R?Regole","rockpaper.php?op=rule");
        addnav("uscita dal gioco");
        addnav("T?Torna alla Locanda","inn.php");
}
//fine modifica sceriffo
// While I can't force you to keep the next line - It would be appreciated
output("`)");
rawoutput("<br><br><div style=\"text-align: right;\">Script by Robert of Maddnet LoGD<br>");
page_footer();
?>