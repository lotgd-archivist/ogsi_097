<?php
if (!isset($session)) exit();
if ($_GET['op']==""){
output("`@Ti imbatti in una radura. Noti un altare con 10 nicchie rivolti verso di te. Ogni nicchia contiene un oggetto differente.`n`n");
output("`@Vedi `# Un Pugnale, `$ Un Teschio,`% Una Bacchetta Ingioiellata, `^ Un Abaco, `7 Un Libro, `3Un Anello,
`2Una Frusta, `4Una Roccia, `5Un Dizionario, `6Un Peso, `7Un Fiore, `1Una Statuetta di Ghiaccio. `n`n");
output("`@In alto al centro dell'altare c'è una`& Sfera di Cristallo Fiammeggiante.`n`n");
output(" `@Sai che per prendere uno di questi oggetti perderai un turno foresta.`n`n`n");
    addnav("`#Prendi il Pugnale","forest.php?op=dagger");
    addnav("`\$Prendi il Teschio","forest.php?op=skull");
    addnav("`%Prendi la Bacchetta","forest.php?op=wand");
    addnav("`^Prendi l'Abaco","forest.php?op=abacus");
    addnav("`7Prendi il Libro","forest.php?op=book");
    addnav("`3Prendi l'Anello","forest.php?op=anello");
    addnav("`2Prendi la Frusta","forest.php?op=frusta");
    addnav("`4Prendi la Roccia","forest.php?op=roccia");
    addnav("`5Prendi il Dizionario","forest.php?op=dizionario");
    addnav("`6Prendi il Peso","forest.php?op=peso");
    addnav("`7Prendi il Fiore","forest.php?op=fiore");
    addnav("`1Prendi la Statuetta","forest.php?op=statua");
    addnav("`&Prendi la Sfera di Cristallo","forest.php?op=bolt");
addnav("`!Abbandona l'Altare","forest.php?op=forgetit");
$session['user']['specialinc'] = "altar.php";

}else if ($_GET['op']=="dagger"){
$session['user']['turns']--;
if (e_rand(0,1)==0){
output("`#Afferri il Pugnale dal suo piedistallo. Il pugnale svanisce, e tu senti una sorgente di potere che pervade
il tuo corpo!`n`n `&Guadagni 5 turni di Abilità Furto`n`n`#Ma sei triste sapendo che il potere svanirà entro domani.");
debuglog("guadagna 5 punti utilizzo abilità Furto");
$session['user']['thieveryuses']+=5;
}else{
output("`@`#Afferri il Pugnale dal suo piedistallo. Il pugnale svanisce, e tu senti una sorgente di potere che
pervade il tuo corpo!`n`n `&Guadagni 2 livelli di Abilità Furto!");
debuglog("guadagna 2 abilità Furto");
$session['user']['thievery']+=6;
$session['user']['thieveryuses']+=2;
}
addnav("Torna alla Foresta","forest.php");
$session['user']['specialinc']="";

}else if ($_GET['op']=="skull"){
$session['user']['turns']--;
if (e_rand(0,1)==0){
output("`#Afferri il Teschio dal suo piedistallo. Il Teschio svanisce, e tu senti una sorgente di potere che pervade
il tuo corpo!`n`n `&Guadagni 5 turni di Abilità Arti Oscure`n`n`#Ma sei triste sapendo che il potere svanirà entro domani.");
debuglog("guadagna 5 punti utilizzo abilità Arti Oscure");
$session['user']['darkartuses']+=5;
}else{
output("`@`#Afferri il Teschio dal suo piedistallo. Il Teschio svanisce, e tu senti una sorgente di potere che
pervade il tuo corpo!`n`n `&Guadagni 2 livelli di Abilità Arti Oscure!");
debuglog("guadagna 2 abilità Arti Oscure");
$session['user']['darkarts']+=6;
$session['user']['darkartuses']+=2;
}
addnav("Torna alla Foresta","forest.php");
$session['user']['specialinc']="";

}else if ($_GET['op']=="wand"){
$session['user']['turns']--;
if (e_rand(0,1)==0){
output("`#Afferri la Bacchetta dal suo piedistallo. La bacchetta svanisce, e tu senti una sorgente di potere che
pervade il tuo corpo!`n`n `&Guadagni 5 turni di Abilità Poteri Mistici`n`n`#Ma sei triste sapendo che il potere
svanirà entro domani.");
debuglog("guadagna 5 punti utilizzo abilità Poteri Mistici");
$session['user']['magicuses']+=5;
}else{
output("`@`#Afferri la Bacchetta dal suo piedistallo. La bacchetta svanisce, e tu senti una sorgente di potere che
pervade il tuo corpo!`n`n `&Guadagni 2 livelli di Abilità Poteri Mistici!");
debuglog("guadagna 2 abilità Poteri Mistici");
$session['user']['magic']+=6;
$session['user']['magicuses']+=2;
}
addnav("Torna alla Foresta","forest.php");
$session['user']['specialinc']="";

}else if ($_GET['op']=="abacus"){
$session['user']['turns']--;
if (e_rand(0,1)==0){
$gold = e_rand($session['user']['level']*30,$session['user']['level']*90);
$gems = e_rand(1,4);
output("`#Afferri l'Abaco dal suo piedistallo. L'Abaco si trasforma in un sacchetto pieno di oro e gemme!`n`n
Guadagni $gold pezzi d'oro e $gems gemme!");
debuglog("guadagna $gold oro e $gems gemme all'Altare");
$session['user']['gold']+=$gold;
$session['user']['gems']+=$gems;
}else{
$gold = $session['user']['gold']+($session['user']['level']*20);
output("`@`#Afferri l'Abaco dal suo piedistallo. L'Abaco si trasforma in un sacchetto pieno di oro!`n`n
Guadagni $gold pezzi d'oro!");
debuglog("guadagna $gold oro all'Altare");
$session['user']['gold']+=$gold;
}
addnav("Torna alla Foresta","forest.php");
$session['user']['specialinc']="";

}else if ($_GET['op']=="book"){
$session['user']['turns']--;
if (e_rand(0,1)==0){
$exp=$session['user']['experience']*0.2;
output("`#Afferri il Libro dal suo piedistallo ed inizi a leggerlo. Le conoscenze contenute nel libro ti vengono
trasmesse. Rimetti il libro sull'altare, sperando che qualche altro esploratore lo trovi e faccia uso delle
conoscenze in esso contenute.`n`nGuadagni $exp punti di esperienza!");
debuglog("guadagna $exp esperienza all'Altare");
$session['user']['experience']+=$exp;
}else{
$ffights = e_rand(1,5);
output("`@`#Afferri il Libro dal suo piedistallo ed inizi a leggerlo. Il Libro contiene segreti che ti consentono
di mettere a profitto i tuoi viaggi nella foresta. Rimetti il libro sull'altare, sperando che qualche altro
esploratore lo trovi e faccia uso dei segreti in esso contenuti.`n`nGuadagni $ffights turni foresta!");
debuglog("guadagna $ffights combattimenti all'Altare");
$session['user']['turns']+=$ffights;
}
addnav("Torna alla Foresta","forest.php");
$session['user']['specialinc']="";

}else if ($_GET['op']=="anello"){
$session['user']['turns']--;
if (e_rand(0,1)==0){
output("`3Afferri l'Anello dal suo piedistallo e lo infili. L'Anello svanisce, e tu senti una sorgente di potere che
pervade il tuo corpo!`n`n `&Guadagni 5 turni di Abilità Seduzione`n`n`#Ma sei triste sapendo che il potere
svanirà entro domani.");
debuglog("guadagna 5 punti utilizzo abilità Seduzione");
$session['user']['mysticuses']+=5;
}else{
output("`3Afferri l'Anello dal suo piedistallo e lo infili. L'Anello svanisce, e tu senti una sorgente di potere che
pervade il tuo corpo!`n`n `&Guadagni 2 livelli di Abilità Seduzione!");
debuglog("guadagna 2 abilità Seduzione");
$session['user']['mystic']+=6;
$session['user']['mysticuses']+=2;
}
addnav("Torna alla Foresta","forest.php");
$session['user']['specialinc']="";

}else if ($_GET['op']=="frusta"){
$session['user']['turns']--;
if (e_rand(0,1)==0){
output("`2Afferri la Frusta dal suo piedistallo e la fai schioccare. Subito scompare, e tu senti una sorgente di
potere che pervade il tuo corpo!`n`n `&Guadagni 5 turni di Abilità Tattica`n`n`#Ma sei triste sapendo che il potere
svanirà entro domani.");
debuglog("guadagna 5 punti utilizzo abilità Tattica");
$session['user']['tacticuses']+=5;
}else{
output("`2Afferri la Frusta dal suo piedistallo e la fai schioccare. Subito scompare, e tu senti una sorgente di potere che
pervade il tuo corpo!`n`n `&Guadagni 2 livelli di Abilità Tattica!");
debuglog("guadagna 2 abilità Tattica");
$session['user']['tactic']+=6;
$session['user']['tacticuses']+=2;
}
addnav("Torna alla Foresta","forest.php");
$session['user']['specialinc']="";

}else if ($_GET['op']=="roccia"){
$session['user']['turns']--;
if (e_rand(0,1)==0){
output("`4Afferri la Roccia dal suo piedistallo e la osservi. Si sbriciola tra le tue mani, e tu senti una sorgente di
potere che pervade il tuo corpo!`n`n `&Guadagni 5 turni di Abilità Pelle di Roccia`n`n`#Ma sei triste sapendo che il potere
svanirà entro domani.");
debuglog("guadagna 5 punti utilizzo abilità Pelle di Roccia");
$session['user']['rockskinuses']+=5;
}else{
output("`4Afferri la Roccia dal suo piedistallo e la osservi. Si sbriciola tra le tue mani, e tu senti una sorgente di
potere che pervade il tuo corpo!`n`n `&Guadagni 2 livelli di Abilità Pelle di Roccia!");
debuglog("guadagna 2 abilità Pelle di Roccia");
$session['user']['rockskin']+=6;
$session['user']['rockskinuses']+=2;
}
addnav("Torna alla Foresta","forest.php");
$session['user']['specialinc']="";

}else if ($_GET['op']=="dizionario"){
$session['user']['turns']--;
if (e_rand(0,1)==0){
output("`5Afferri il Dizionario dal suo piedistallo ed inizi a leggerlo. Mentre lo leggi si sbriciola tra le tue mani,
e tu senti una sorgente di potere che pervade il tuo corpo!`n`n `&Guadagni 5 turni di Abilità Retorica`n`n
`#Ma sei triste sapendo che il potere svanirà entro domani.");
debuglog("guadagna 5 punti utilizzo abilità Retorica");
$session['user']['rhetoricuses']+=5;
}else{
output("`5Afferri il Dizionario dal suo piedistallo ed inizi a leggerlo. Mentre lo leggi si sbriciola tra le tue mani,
e tu senti una sorgente di potere che pervade il tuo corpo!`n`n `&Guadagni 2 livelli di Abilità Retorica!");
debuglog("guadagna 2 abilità Retorica");
$session['user']['rhetoric']+=6;
$session['user']['rhetoricuses']+=2;
}
addnav("Torna alla Foresta","forest.php");
$session['user']['specialinc']="";

}else if ($_GET['op']=="peso"){
$session['user']['turns']--;
if (e_rand(0,1)==0){
output("`6Afferri il Bilanciere dal suo piedistallo e lo soppesi. Mentre fai degli esercizi scompare tra le tue mani,
e tu senti una sorgente di potere che pervade il tuo corpo!`n`n `&Guadagni 5 turni di Abilità Muscoli`n`n
`#Ma sei triste sapendo che il potere svanirà entro domani.");
debuglog("guadagna 5 punti utilizzo abilità Muscoli");
$session['user']['muscleuses']+=5;
}else{
output("`6Afferri il Bilanciere dal suo piedistallo e lo soppesi. Mentre fai degli esercizi scompare tra le tue mani,
e tu senti una sorgente di potere che pervade il tuo corpo!`n`n `&Guadagni 2 livelli di Abilità Muscoli!");
debuglog("guadagna 2 abilità Muscoli");
$session['user']['muscle']+=6;
$session['user']['muscleuses']+=2;
}
addnav("Torna alla Foresta","forest.php");
$session['user']['specialinc']="";

}else if ($_GET['op']=="fiore"){
$session['user']['turns']--;
if (e_rand(0,1)==0){
output("`7Afferri il Fiore dal suo piedistallo e ne inali il profumo. Subito scompare tra le tue mani,
e tu senti una sorgente di potere che pervade il tuo corpo!`n`n `&Guadagni 5 turni di Abilità Natura`n`n
`#Ma sei triste sapendo che il potere svanirà entro domani.");
debuglog("guadagna 5 punti utilizzo abilità Natura");
$session['user']['natureuses']+=5;
}else{
output("`7Afferri il Fiore dal suo piedistallo e ne inali il profumo. Subito scompare tra le tue mani,
e tu senti una sorgente di potere che pervade il tuo corpo!`n`n `&Guadagni 2 livelli di Abilità Natura!");
debuglog("guadagna 2 abilità Natura");
$session['user']['nature']+=6;
$session['user']['natureuses']+=2;
}
addnav("Torna alla Foresta","forest.php");
$session['user']['specialinc']="";

}else if ($_GET['op']=="statua"){
$session['user']['turns']--;
if (e_rand(0,1)==0){
output("`1Afferri la Statua dal suo piedistallo e la stringi tra le mani. Si scioglie in pochi secondi,
e tu senti una sorgente di potere che pervade il tuo corpo!`n`n `&Guadagni 5 turni di Abilità Clima`n`n
`#Ma sei triste sapendo che il potere svanirà entro domani.");
debuglog("guadagna 5 punti utilizzo abilità Clima");
$session['user']['weatheruses']+=5;
}else{
output("`1Afferri la Statua dal suo piedistallo e la stringi tra le mani. Si sciogli in pochi secondi,
e tu senti una sorgente di potere che pervade il tuo corpo!`n`n `&Guadagni 2 livelli di Abilità Clima!");
debuglog("guadagna 2 abilità Clima");
$session['user']['weather']+=6;
$session['user']['weatheruses']+=2;
}
addnav("Torna alla Foresta","forest.php");
$session['user']['specialinc']="";

}else if ($_GET['op']=="bolt"){
    $session['user']['turns']--;
    $bchance=e_rand(0,7);
    if ($bchance==0){
        output("`#Afferri la Sfera di Cristallo Fiammeggiante dal suo piedistallo. La Sfera svanisce tra le tue mani e
        riappare sull'alto dell'altare. Dopo svariati tentativi di afferrare la Sfera, decidi di non perdere altro tempo,
        con la paura di innervosire gli dei");
        addnav("Torna alla Foresta","forest.php");
    }elseif ($bchance==1){
        output("`#Afferri la Sfera di Cristallo Fiammeggiante dal suo piedistallo. Appena tocchi la Sfera vieni sbattuto
        indietro al suolo. Ti rialzi, pervaso da un senso di potenza!`n`nGuadagni 2 livelli in ognuna delle tue Abilità!
        Ma sei triste sapendo che il potere svanirà entro domani.");
        debuglog("guadagna 2 abilità in ogni specialità all'Altare");
        $session['user']['thieveryuses']+=2;
        $session['user']['darkartuses']+=2;
        $session['user']['magicuses']+=2;
        $session['user']['militareuses']+=2;
        $session['user']['mysticuses'] +=2;
        $session['user']['tacticuses']+=2;
        $session['user']['rockskinuses']+=2;
        $session['user']['rhetoricuses']+=2;
        $session['user']['muscleuses']+=2;
        $session['user']['natureuses']+=2;
        $session['user']['weatheruses']+=2;
        addnav("Torna alla Foresta","forest.php");
    }elseif($bchance==2){
        output("`#Afferri la Sfera di Cristallo Fiammeggiante dal suo piedistallo. Appena tocchi la Sfera vieni sbattuto
        indietro al suolo. Ti rialzi, pervaso da un senso di potenza!`n`nGuadagni 1 livello in ogni Abilità!");
        debuglog("guadagna 1 livello in ogni abilità all'Altare");
        $session['user']['thievery']+=1;
        $session['user']['darkarts']+=1;
        $session['user']['magic']+=1;
        $session['user']['militare']+=1;
        $session['user']['mystic']+=1;
        $session['user']['tactic']+=1;
        $session['user']['rockskin']+=1;
        $session['user']['rhetoric']+=1;
        $session['user']['muscle']+=1;
        $session['user']['nature']+=1;
        $session['user']['weather']+=1;
        $session['user']['thieveryuses']++;
        $session['user']['darkartuses']++;
        $session['user']['magicuses']++;
        $session['user']['militareuses']++;
        $session['user']['mysticuses']++;
        $session['user']['tacticuses']++;
        $session['user']['rockskinuses']++;
        $session['user']['rhetoricuses']++;
        $session['user']['muscleuses']++;
        $session['user']['natureuses']++;
        $session['user']['weatheruses']++;
        addnav("Torna alla Foresta","forest.php");
    }elseif($bchance==3){
        output("`#Afferri la Sfera di Cristallo Fiammeggiante dal suo piedistallo. Appena tocchi la Sfera vieni sbattuto
        indietro al suolo. Ti rialzi, pervaso da un senso di potenza!`n`nGuadagni 3 Max HP!");
        $session['user']['maxhitpoints']+=3;
        $session['user']['hitpoints']+=3;
        debuglog("guadagna 3 HP all'Altare");
        addnav("Torna alla Foresta","forest.php");
    }elseif($bchance==4){
        output("`#Afferri la Sfera di Cristallo Fiammeggiante dal suo piedistallo. Appena tocchi la Sfera vieni sbattuto
        indietro al suolo. Ti rialzi, pervaso da un senso di potenza!`n`nGuadagni 1 Punto Attacco e 1 Punto Difesa `iPERMANENTI`i!!!");
        $session['user']['attack']+=1;
        $session['user']['defence']+=1;
        $session['user']['bonusattack']+=1;
        $session['user']['bonusdefence']+=1;
        debuglog("guadagna 1 punto Attacco e 1 punto Difesa PERMANENTI all'Altare");
        addnav("Torna alla Foresta","forest.php");
    }elseif($bchance==5){
        $exp=$session['user']['experience']*0.15;
        output("`#Afferri la Sfera di Cristallo Fiammeggiante dal suo piedistallo. Appena tocchi la Sfera vieni sbattuto
        indietro al suolo. Ti rialzi, pervaso da un senso di potenza!`n`nGuadagni $exp punti esperienza!");
        $session['user']['experience']+=$exp;
        debuglog("guadagna $exp esperienza all'Altare");
        addnav("Torna alla Foresta","forest.php");
    }elseif($bchance==6){
        $exp=$session['user']['experience']*.15;
        output("`#Tenti di afferrare Sfera di Cristallo Fiammeggiante quando il cielo si riempie improvvisamente di nubi.
        Pensi di aver contrariato gli dei, e inizi a scappare, ma prima di uscire dalla radura un fulmine dal cielo ti
        colpisce.`n`nRimani Basito! Perdi $exp punti esperienza!");
        $session['user']['experience']-=$exp;
        debuglog("perde $exp esperienza all'Altare");
        addnav("Torna alla Foresta","forest.php");
    }else{
        output("`#Tenti di afferrare Sfera di Cristallo Fiammeggiante quando il cielo si riempie improvvisamente di nubi.
        Pensi di aver contrariato gli dei, e inizi a scappare, ma prima di uscire dalla radura un fulmine dal cielo ti
        colpisce.`n`nSei Morto!");
        output("Perdi il 5% della tua Esperienza e tutto l'oro che avevi in mano!`n`n");
        output("Potrai continuare a giocare domani.");
        $session['user']['clean'] += 1;
        $session['user']['alive']=false;
        $session['user']['hitpoints']=0;
        $gold=$session['user']['gold'];
        $session['user']['gold']=0;
        $session['user']['experience']=$session['user']['experience']*0.95;
        debuglog("perde $gold oro e il 5% esperienza all'Altare");
        addnav("Notizie Giornaliere","news.php");
        addnews($session['user']['name']." è stat".($session['user']['sex']?"a":"o")."  fulminat".($session['user']['sex']?"a":"o")."
        dagli dei perchè è stat".($session['user']['sex']?"a":"o")." troppo ingord".($session['user']['sex']?"a":"o")."!");
    }
$session['user']['specialinc']="";

}else if ($_GET['op']=="forgetit"){
    output("`@Decidi di non sfidare la sorte e la rabbia degli dei. Lasci la radura con l'altare.");
    output("Mentre te ne stai andando trovi un sacchetto che contiene 2 gemme! Devi proprio essere benvoluto dagli dei!");
    $session['user']['gems']+=2;
    debuglog("trova 2 gemme all'Altare");
    addnav("Torna alla Foresta","forest.php");
    $session['user']['specialinc']="";
}
?>

