<?php
/*
Il Lotto di Eric v. 1.0
Based upon the original LORD IGM Seth's Lotto By Joseph Masters
Author: bwatford
Board: http://www.ftpdreams.com/lotgd
Date: 03-2004
Tradotto in italiano da Excalibur
* Drop this in your main folder.
Install the sql tables included with this zip
Find:
        $session['user']['drunkenness']=0;

After That Add:
        $session['user'][lotto]= 0;
        $session['user'][lottochoice]=0;
        $session['user']['matches']=0;

*/

require_once "common.php";
checkday();

if ($_GET['op']==""){
page_header("Il Lotto di Eric");
$session['user']['locazione'] = 121;
if($session['user']['lotto']>0){
addnav("Navigazione");
addnav("Torna al Villaggio","village.php");
output("`b`n`n`c`bIl Lotto di Eric`b`c`n`n");
output("`@`cSembra esserci uno schermo invisibile, che ti impedisce di salire sulla collina! `n`^'Riprova domani!'`^`@`c`n`n");
    }else{
addnav("Vuoi provare?");
addnav("Si","eslotto.php?op=yes");
addnav("No","eslotto.php?op=no");
output("`b`n`n`c`bIl Lotto di Eric`b`c`n`n");
output("`@`cSeguendo istintivamente il sentiero, arrivi ad una larga collina. Sembra insormontabile, ma senti un calore ");
output("pervaderti, ed una forza ti trascina fin sulla cima della collina.`n`n");
output("Sulla cima c'è una strana scatola con dei pulsanti a fianco dei numeri da 0 a 9. C'è un'inscrizione sottostante.`n`n");
output("`^Questa Lotteria è in Onore di Eric Stevens per aver creato questo gioco fantastico.`n`n");
output("Dedicato da Billy Watford, 27-3-2004`^`@`c`n`n");
output("`@`cSembra essere una specie di Macchina della Lotteria...`@`c`n`n");

}
}

if ($_GET['op']=="no"){
page_header("Il Lotto di Eric");
addnav("Navigazione");
addnav("Torna al Villaggio","village.php");
output("`b`n`n`c`bIl Lotto di Eric`b`c`n`n");
output("`@`cTorni verso casa, all'oscuro di ciò che hai perso.`@`c`n`n");

}

if ($_GET['op']=="yes"){
page_header("Il Lotto di Eric");
if($session['user']['gold']<500){
addnav("Navigazione");
addnav("Scordatelo","eslotto.php?op=no");
output("`b`n`n`c`bIl Lotto di Eric`b`c`n`n");
output("`@`cComunque, sembra che tu non abbia i pezzi d'oro necessari per usare la macchina!`@`c`n`n");
output("`c`^`bCosta 500 Pezzi d'Oro usare la Macchina`c`b`^");
    }else{
addnav("Scegli i tuoi Numeri");
addnav("Fammi Scegliere","eslotto.php?op=choose");
output("`b`n`n`c`bIl Lotto di Eric`b`c`n`n");
output("`@`cInserisci i 500 pezzi d'oro nella macchina, e leggi le istruzioni.`n`n");
output("Premi quattro pulsanti per scegliere il tuo codice.  Poi, aspetta cha la macchina faccia il suo lavoro.");
output(" Se indovini uno o più numeri, vincerai e otterrai dei pezzi d'oro in cambio!`@`c");
$session['user']['gold']-=500;

}
}

if ($_GET['op']=="choose"){
page_header("Il Lotto di Eric");
output("`b`n`n`c`bIl Lotto di Eric`b`c`n`n");
if($session['user']['lottochoice']==0){
addnav("Scegli il tuo Primo Numero");
addnav("0","eslotto.php?op=choose0");
addnav("1","eslotto.php?op=choose1");
addnav("2","eslotto.php?op=choose2");
addnav("3","eslotto.php?op=choose3");
addnav("4","eslotto.php?op=choose4");
addnav("5","eslotto.php?op=choose5");
addnav("6","eslotto.php?op=choose6");
addnav("7","eslotto.php?op=choose7");
addnav("8","eslotto.php?op=choose8");
addnav("9","eslotto.php?op=choose9");
output("`@`cOk È il momento di scegliere il Primo Numero!`@`c`n`n");
output("`^`bI TUOI NUMERI`^`n`n`b");
output("`@Primo Numero: Sconosciuto`n");
output("Secondo Numero: Sconosciuto`n");
output("Terzo Numero: Sconosciuto`n");
output("Quarto Numero: Sconosciuto`@`n`n");
    }else{
if($session['user']['lottochoice']==1){
addnav("Scegli il tuo Secondo Numero");
addnav("0","eslotto.php?op=choose0");
addnav("1","eslotto.php?op=choose1");
addnav("2","eslotto.php?op=choose2");
addnav("3","eslotto.php?op=choose3");
addnav("4","eslotto.php?op=choose4");
addnav("5","eslotto.php?op=choose5");
addnav("6","eslotto.php?op=choose6");
addnav("7","eslotto.php?op=choose7");
addnav("8","eslotto.php?op=choose8");
addnav("9","eslotto.php?op=choose9");
output("`@`cOk ora è il momento di scegliere il tuo Secondo Numero!`@`c`n`n");
output("`^`bI TUOI NUMERI`^`n`n`b");
output("`@Primo Numero: " . ($session['user']['lotto1']) . "`n");
output("Secondo Numero: Sconosciuto`n");
output("Terzo Numero: Sconosciuto`n");
output("Quarto Numero: Sconosciuto`@`n`n");
    }else{
if($session['user']['lottochoice']==2){
addnav("Scegli il tuo Terzo Numero");
addnav("0","eslotto.php?op=choose0");
addnav("1","eslotto.php?op=choose1");
addnav("2","eslotto.php?op=choose2");
addnav("3","eslotto.php?op=choose3");
addnav("4","eslotto.php?op=choose4");
addnav("5","eslotto.php?op=choose5");
addnav("6","eslotto.php?op=choose6");
addnav("7","eslotto.php?op=choose7");
addnav("8","eslotto.php?op=choose8");
addnav("9","eslotto.php?op=choose9");
output("`@`cOk ora è il momento di scegliere il tuo Terzo Numero!`@`c`n`n");
output("`^`bI TUOI NUMERI`^`n`n`b");
output("`@Primo Numero: " . ($session['user']['lotto1']) . "`n");
output("Secondo Numero: " . ($session['user']['lotto2']) . " `n");
output("Terzo Numero: Sconosciuto`n");
output("Quarto Numero: Sconosciuto`@`n`n");
    }else{
if($session['user']['lottochoice']==3){
addnav("Scegli il tuo Ultimo Numero");
addnav("0","eslotto.php?op=choose0");
addnav("1","eslotto.php?op=choose1");
addnav("2","eslotto.php?op=choose2");
addnav("3","eslotto.php?op=choose3");
addnav("4","eslotto.php?op=choose4");
addnav("5","eslotto.php?op=choose5");
addnav("6","eslotto.php?op=choose6");
addnav("7","eslotto.php?op=choose7");
addnav("8","eslotto.php?op=choose8");
addnav("9","eslotto.php?op=choose9");
output("`@`cOk ora è il momento di scegliere il tuo Ultimo Numero!`@`c`n`n");
output("`^`bI TUOI NUMERI`^`n`n`b");
output("`@Primo Numero: " . ($session['user']['lotto1']) . "`n");
output("Secondo Numero: " . ($session['user']['lotto2']) . " `n");
output("Terzo Numero: " . ($session['user']['lotto3']) . " `n");
output("Quarto Numero: Sconosciuto`@`n`n");
    }else{
if($session['user']['lottochoice']==4){
addnav("Navigazione");
addnav("Controlla i miei Numeri","eslotto.php?op=match");
output("`@`cOk ora è il momento di verficare se hai vinto!`@`c`n`n");
output("`^`bI TUOI NUMERI`^`n`n`b");
output("`@Primo Numero: " . ($session['user']['lotto1']) . "`n");
output("Secondo Numero: " . ($session['user']['lotto2']) . " `n");
output("Terzo Numero: " . ($session['user']['lotto3']) . " `n");
output("Quarto Numero: " . ($session['user']['lotto4']) . " `@`n`n");

}
}
}
}
}
}

if ($_GET['op']=="choose0"){
page_header("Il Lotto di Eric");
addnav("Continua","eslotto.php?op=choose");
output("`b`n`n`c`bIl Lotto di Eric`b`c`n`n");
if($session['user']['lottochoice']==0){
output("`b`c`@HAI SCELTO IL NUMERO `^0`@ COME TUO PRIMO NUMERO!`b`c`@`^");
$session['user']['lottochoice']+=1;
$session['user']['lotto1']=0;
    }else{
if($session['user']['lottochoice']==1){
output("`b`c`@HAI SCELTO IL NUMERO `^0`@ COME TUO SECONDO NUMERO!`b`c`@`^");
$session['user']['lottochoice']+=1;
$session['user']['lotto2']=0;
    }else{
if($session['user']['lottochoice']==2){
output("`b`c`@HAI SCELTO IL NUMERO `^0`@ COME TUO TERZO NUMERO!`b`c`@`^");
$session['user']['lottochoice']+=1;
$session['user']['lotto3']=0;
    }else{
if($session['user']['lottochoice']==3){
output("`b`c`@HAI SCELTO IL NUMERO `^0`@ COME TUO QUARTO NUMERO!`b`c`@`^");
$session['user']['lottochoice']+=1;
$session['user']['lotto4']=0;


}
}
}
}
}

if ($_GET['op']=="choose1"){
page_header("Il Lotto di Eric");
addnav("Continua","eslotto.php?op=choose");
output("`b`n`n`c`bIl Lotto di Eric`b`c`n`n");
if($session['user']['lottochoice']==0){
output("`b`c`@HAI SCELTO IL NUMERO `^1`@ COME TUO PRIMO NUMERO!`b`c`@`^");
$session['user']['lottochoice']+=1;
$session['user']['lotto1']=1;
    }else{
if($session['user']['lottochoice']==1){
output("`b`c`@HAI SCELTO IL NUMERO `^1`@ COME TUO SECONDO NUMERO!`b`c`@`^");
$session['user']['lottochoice']+=1;
$session['user']['lotto2']=1;
    }else{
if($session['user']['lottochoice']==2){
output("`b`c`@HAI SCELTO IL NUMERO `^1`@ COME TUO TERZO NUMERO!`b`c`@`^");
$session['user']['lottochoice']+=1;
$session['user']['lotto3']=1;
    }else{
if($session['user']['lottochoice']==3){
output("`b`c`@HAI SCELTO IL NUMERO `^1`@ COME TUO QUARTO NUMERO!`b`c`@`^");
$session['user']['lottochoice']+=1;
$session['user']['lotto4']=1;

}
}
}
}
}

if ($_GET['op']=="choose2"){
page_header("Il Lotto di Eric");
addnav("Continua","eslotto.php?op=choose");
output("`b`n`n`c`bIl Lotto di Eric`b`c`n`n");
if($session['user']['lottochoice']==0){
output("`b`c`@HAI SCELTO IL NUMERO `^2`@ COME TUO PRIMO NUMERO!`b`c`@`^");
$session['user']['lottochoice']+=1;
$session['user']['lotto1']=2;
    }else{
if($session['user']['lottochoice']==1){
output("`b`c`@HAI SCELTO IL NUMERO `^2`@ COME TUO SECONDO NUMERO!`b`c`@`^");
$session['user']['lottochoice']+=1;
$session['user']['lotto2']=2;
    }else{
if($session['user']['lottochoice']==2){
output("`b`c`@HAI SCELTO IL NUMERO `^2`@ COME TUO TERZO NUMERO!`b`c`@`^");
$session['user']['lottochoice']+=1;
$session['user']['lotto3']=2;
    }else{
if($session['user']['lottochoice']==3){
output("`b`c`@HAI SCELTO IL NUMERO `^2`@ COME TUO QUARTO NUMERO!`b`c`@`^");
$session['user']['lottochoice']+=1;
$session['user']['lotto4']=2;

}
}
}
}
}

if ($_GET['op']=="choose3"){
page_header("Il Lotto di Eric");
addnav("Continua","eslotto.php?op=choose");
output("`b`n`n`c`bIl Lotto di Eric`b`c`n`n");
if($session['user']['lottochoice']==0){
output("`b`c`@HAI SCELTO IL NUMERO `^3`@ COME TUO PRIMO NUMERO!`b`c`@`^");
$session['user']['lottochoice']+=1;
$session['user']['lotto1']=3;
    }else{
if($session['user']['lottochoice']==1){
output("`b`c`@HAI SCELTO IL NUMERO `^3`@ COME TUO SECONDO NUMERO!`b`c`@`^");
$session['user']['lottochoice']+=1;
$session['user']['lotto2']=3;
    }else{
if($session['user']['lottochoice']==2){
output("`b`c`@HAI SCELTO IL NUMERO `^3`@ COME TUO TERZO NUMERO!`b`c`@`^");
$session['user']['lottochoice']+=1;
$session['user']['lotto3']=3;
    }else{
if($session['user']['lottochoice']==3){
output("`b`c`@HAI SCELTO IL NUMERO `^3`@ COME TUO QUARTO NUMERO!`b`c`@`^");
$session['user']['lottochoice']+=1;
$session['user']['lotto4']=3;

}
}
}
}
}

if ($_GET['op']=="choose4"){
page_header("Il Lotto di Eric");
addnav("Continua","eslotto.php?op=choose");
output("`b`n`n`c`bIl Lotto di Eric`b`c`n`n");
if($session['user']['lottochoice']==0){
output("`b`c`@HAI SCELTO IL NUMERO `^4`@ COME TUO PRIMO NUMERO!`b`c`@`^");
$session['user']['lottochoice']+=1;
$session['user']['lotto1']=4;
    }else{
if($session['user']['lottochoice']==1){
output("`b`c`@HAI SCELTO IL NUMERO `^4`@ COME TUO SECONDO NUMERO!`b`c`@`^");
$session['user']['lottochoice']+=1;
$session['user']['lotto2']=4;
    }else{
if($session['user']['lottochoice']==2){
output("`b`c`@HAI SCELTO IL NUMERO `^4`@ COME TUO TERZO NUMERO!`b`c`@`^");
$session['user']['lottochoice']+=1;
$session['user']['lotto3']=4;
    }else{
if($session['user']['lottochoice']==3){
output("`b`c`@HAI SCELTO IL NUMERO `^4`@ COME TUO QUARTO NUMERO!`b`c`@`^");
$session['user']['lottochoice']+=1;
$session['user']['lotto4']=4;

}
}
}
}
}

if ($_GET['op']=="choose5"){
page_header("Il Lotto di Eric");
addnav("Continua","eslotto.php?op=choose");
output("`b`n`n`c`bIl Lotto di Eric`b`c`n`n");
if($session['user']['lottochoice']==0){
output("`b`c`@HAI SCELTO IL NUMERO `^5`@ COME TUO PRIMO NUMERO!`b`c`@`^");
$session['user']['lottochoice']+=1;
$session['user']['lotto1']=5;
    }else{
if($session['user']['lottochoice']==1){
output("`b`c`@HAI SCELTO IL NUMERO `^5`@ COME TUO SECONDO NUMERO!`b`c`@`^");
$session['user']['lottochoice']+=1;
$session['user']['lotto2']=5;
    }else{
if($session['user']['lottochoice']==2){
output("`b`c`@HAI SCELTO IL NUMERO `^5`@ COME TUO TERZO NUMERO!`b`c`@`^");
$session['user']['lottochoice']+=1;
$session['user']['lotto3']=5;
    }else{
if($session['user']['lottochoice']==3){
output("`b`c`@HAI SCELTO IL NUMERO `^5`@ COME TUO QUARTO NUMERO!`b`c`@`^");
$session['user']['lottochoice']+=1;
$session['user']['lotto4']=5;

}
}
}
}
}

if ($_GET['op']=="choose6"){
page_header("Il Lotto di Eric");
addnav("Continua","eslotto.php?op=choose");
output("`b`n`n`c`bIl Lotto di Eric`b`c`n`n");
if($session['user']['lottochoice']==0){
output("`b`c`@HAI SCELTO IL NUMERO `^6`@ COME TUO PRIMO NUMERO!`b`c`@`^");
$session['user']['lottochoice']+=1;
$session['user']['lotto1']=6;
    }else{
if($session['user']['lottochoice']==1){
output("`b`c`@HAI SCELTO IL NUMERO `^6`@ COME TUO SECONDO NUMERO!`b`c`@`^");
$session['user']['lottochoice']+=1;
$session['user']['lotto2']=6;
    }else{
if($session['user']['lottochoice']==2){
output("`b`c`@HAI SCELTO IL NUMERO `^6`@ COME TUO TERZO NUMERO!`b`c`@`^");
$session['user']['lottochoice']+=1;
$session['user']['lotto3']=6;
    }else{
if($session['user']['lottochoice']==3){
output("`b`c`@HAI SCELTO IL NUMERO `^6`@ COME TUO QUARTO NUMERO!`b`c`@`^");
$session['user']['lottochoice']+=1;
$session['user']['lotto4']=6;

}
}
}
}
}

if ($_GET['op']=="choose7"){
page_header("Il Lotto di Eric");
addnav("Continua","eslotto.php?op=choose");
output("`b`n`n`c`bIl Lotto di Eric`b`c`n`n");
if($session['user']['lottochoice']==0){
output("`b`c`@HAI SCELTO IL NUMERO `^7`@ COME TUO PRIMO NUMERO!`b`c`@`^");
$session['user']['lottochoice']+=1;
$session['user']['lotto1']=7;
    }else{
if($session['user']['lottochoice']==1){
output("`b`c`@HAI SCELTO IL NUMERO `^7`@ COME TUO SECONDO NUMERO!`b`c`@`^");
$session['user']['lottochoice']+=1;
$session['user']['lotto2']=7;
    }else{
if($session['user']['lottochoice']==2){
output("`b`c`@HAI SCELTO IL NUMERO `^7`@ COME TUO TERZO NUMERO!`b`c`@`^");
$session['user']['lottochoice']+=1;
$session['user']['lotto3']=7;
    }else{
if($session['user']['lottochoice']==3){
output("`b`c`@HAI SCELTO IL NUMERO `^7`@ COME TUO QUARTO NUMERO!`b`c`@`^");
$session['user']['lottochoice']+=1;
$session['user']['lotto4']=7;

}
}
}
}
}

if ($_GET['op']=="choose8"){
page_header("Il Lotto di Eric");
addnav("Continua","eslotto.php?op=choose");
output("`b`n`n`c`bIl Lotto di Eric`b`c`n`n");
if($session['user']['lottochoice']==0){
output("`b`c`@HAI SCELTO IL NUMERO `^8`@ COME TUO PRIMO NUMERO!`b`c`@`^");
$session['user']['lottochoice']+=1;
$session['user']['lotto1']=8;
    }else{
if($session['user']['lottochoice']==1){
output("`b`c`@HAI SCELTO IL NUMERO `^8`@ COME TUO SECONDO NUMERO!`b`c`@`^");
$session['user']['lottochoice']+=1;
$session['user']['lotto2']=8;
    }else{
if($session['user']['lottochoice']==2){
output("`b`c`@HAI SCELTO IL NUMERO `^8`@ COME TUO TERZO NUMERO!`b`c`@`^");
$session['user']['lottochoice']+=1;
$session['user']['lotto3']=8;
    }else{
if($session['user']['lottochoice']==3){
output("`b`c`@HAI SCELTO IL NUMERO `^8`@ COME TUO QUARTO NUMERO!`b`c`@`^");
$session['user']['lottochoice']+=1;
$session['user']['lotto4']=8;

}
}
}
}
}

if ($_GET['op']=="choose9"){
page_header("Il Lotto di Eric");
addnav("Continua","eslotto.php?op=choose");
output("`b`n`n`c`bIl Lotto di Eric`b`c`n`n");
if($session['user']['lottochoice']==0){
output("`b`c`@HAI SCELTO IL NUMERO `^9`@ COME TUO PRIMO NUMERO!`b`c`@`^");
$session['user']['lottochoice']+=1;
$session['user']['lotto1']=9;
    }else{
if($session['user']['lottochoice']==1){
output("`b`c`@HAI SCELTO IL NUMERO `^9`@ COME TUO SECONDO NUMERO!`b`c`@`^");
$session['user']['lottochoice']+=1;
$session['user']['lotto2']=9;
    }else{
if($session['user']['lottochoice']==2){
output("`b`c`@HAI SCELTO IL NUMERO `^9`@ COME TUO TERZO NUMERO!`b`c`@`^");
$session['user']['lottochoice']+=1;
$session['user']['lotto3']=9;
    }else{
if($session['user']['lottochoice']==3){
output("`b`c`@HAI SCELTO IL NUMERO `^9`@ COME TUO QUARTO NUMERO!`b`c`@`^");
$session['user']['lottochoice']+=1;
$session['user']['lotto4']=9;

}
}
}
}
}

if ($_GET['op']=="match"){
page_header("Il Lotto di Eric");
addnav("Navigazione");
addnav("Reclama la tua Vincita","eslotto.php?op=winnings");
output("`b`n`n`c`bIl Lotto di Eric`b`c`n`n");
output("`@`cLa macchina ronza e fischia! Mostra magicamente i tuoi numeri sulla lavagna di fronte.`@`c`n`n");
output("`^`bI TUOI NUMERI`^`n`n`b");
output("`@Primo Numero: " . ($session['user']['lotto1']) . "`n");
output("Secondo Numero: " . ($session['user']['lotto2']) . " `n");
output("Terzo Numero: " . ($session['user']['lotto3']) . " `n");
output("Quarto Numero: " . ($session['user']['lotto4']) . " `@`n`n");
output("`@`cLa macchina ronza e fischia un'altra volta! E mostra magicamente i numeri vincenti sulla lavagna proprio sotto i tuoi numeri!`@`c`n`n");
$win1 = e_rand(0,9);
$win2 = e_rand(0,9);
$win3 = e_rand(0,9);
$win4 = e_rand(0,9);
$session['user']['win1']= $win1;
$session['user']['win2']= $win2;
$session['user']['win3']= $win3;
$session['user']['win4']= $win4;
output("`^`bNUMERI VINCENTI`^`n`n`b");
output("`@Primo Numero: " . ($session['user']['win1']) . "`n");
output("Secondo Numero: " . ($session['user']['win2']) . " `n");
output("Terzo Numero: " . ($session['user']['win3']) . " `n");
output("Quarto Numero: " . ($session['user']['win4']) . " `@`n`n");
if($session['user']['lotto1']!=$session['user']['win1'] AND $session['user']['lotto2']!=$session['user']['win2'] AND
$session['user']['lotto3']!=$session['user']['win3'] AND $session['user']['lotto4']!=$session['user']['win4']){
output("`\$`bSpiacente, ma nessuno dei tuoi numeri corrisponde oggi, riprova domani`^`b`n`n");
}
if($session['user']['lotto1']==$session['user']['win1']){
output("`#`bIl tuo Primo Numero corrisponde!`^`b`n`n");
$session['user']['matches']+=1;
}
if($session['user']['lotto2']==$session['user']['win2']){
output("`%`bIl tuo Secondo Numero corrisponde!`^`b`n`n");
$session['user']['matches']+=1;
}
if($session['user']['lotto3']==$session['user']['win3']){
output("`^`bIl tuo Terzo Numero corrisponde!`^`b`n`n");
$session['user']['matches']+=1;
}
if($session['user']['lotto4']==$session['user']['win4']){
output("`\$`bIl tuo Quarto Numero corrisponde!`^`b`n`n");
$session['user']['matches']+=1;
}
}

if ($_GET['op']=="winnings"){
page_header("Il Lotto di Eric");
addnav("Navigazione");
addnav("Torna al Villaggio","village.php");
output("`b`n`n`c`bIl Controllo Vincite del Lotto di Eric`b`c`n`n");
if($session['user']['matches']==0){
output("`@`cla macchina scampanella e fischia fino a fermarsi completamente e spegnersi.`@`c`n`n");
output("`^`cSpiacente non hai vinto oggi! Riprova domani!`^`c");
$session['user']['lotto']=1;
    }else{
if($session['user']['matches']==1){
output("`@`cLa macchina fischia e stride freneticamente mentre 500 Pezzi d'Oro escono dal lato inferiore!`@`c`n`n");
output("`#`cHai indovinato 1 numero! Hai vinto 500 Pezzi d'Oro`^`c");
debuglog("vince 500 oro alla slot machine");
$session['user']['lotto']=1;
$session['user']['gold']+=1000;
    }else{
if($session['user']['matches']==2){
output("`@`cLa macchina fischia e stride freneticamente mentre 3.000 Pezzi d'Oro escono dal lato inferiore!`@`c`n`n");
output("`^`cHai indovinato 2 numeri! Hai vinto 3.000 Pezzi d'Oro`^`c");
debuglog("vince 3.000 oro alla slot machine");
$session['user']['lotto']=1;
$session['user']['gold']+=3500;
    }else{
if($session['user']['matches']==3){
output("`@`cLa macchina fischia e stride freneticamente mentre 7.500 Pezzi d'Oro escono dal lato inferiore!`@`c`n`n");
output("`^`cHai indovinato 3 numeri! Hai vinto 7.500 Pezzi d'Oro`^`c");
debuglog("vince 7.500 oro alla slot machine");
$session['user']['lotto']=1;
$session['user']['gold']+=8000;
    }else{
if($session['user']['matches']==4){
output("`@`cLa macchina fischia e stride freneticamente mentre 100.000 Pezzi d'Oro escono dal lato inferiore!`@`c`n`n");
output("`^`cHai VINTO IL JACKPOT !! Vinci 100.000 Pezzi d'Oro`^`c");
$session['user']['lotto']=1;
$session['user']['gold']+=100500;
debuglog("vince 100.000 oro alla slot machine");

}
}
}
}
}
}

page_footer();
?>