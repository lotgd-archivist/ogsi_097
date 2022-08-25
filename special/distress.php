<?php
/* *******************
The damsel in distress was written by Joe Naylor
Much of the event text was written by Matt Clift, I understand it is
heavily inspired by an event in Legend of the Red Dragon.
Feel free to use this any way you want to, but please give credit where due.
Version 1.1
******************* */

if (!isset($session)) exit();

if ($_GET['op']==""){
    $session['user']['specialinc']="distress.php";
    output("`n`3Aggirandoti nella foresta di Tarencia in cerca di avventure, vedi una sagoma umana riversa sul terreno. ");
    output("Una freccia nera piantata nella schiena, una pozza di sangue e il suo abbigliamento ti fanno intuire immediatamente ");
    output("di aver incrociato lungo il tuo cammino il cadavere di un guerriero.`n`n");

    output("`3Esamini il corpo, frugando dappertutto alla ricerca di oggetti di valore, ma inutilmente, nulla di interessante. ");
    output("A un certo punto, però noti che il guerriero stringe nel pugno un pezzetto di carta, lo liberi con cautela e ");
    output("vedi che è un biglietto scritto frettolosamente. Lo leggi attentamente cercando di interpretare la calligrafia. Dice:`n`n");

    output("`3\"`GAiuto! Sono stat".($session['user']['sex']?"o":"a")." imprigionat".($session['user']['sex']?"o":"a")." da un mostro che vuole costringermi al suo volere.  ");
    output("Per favore aiutatemi! Sono prigionier".($session['user']['sex']?"o":"a")." nel ...`3\"`n`n");

    output("`3Il resto del biglietto è troppo sporco di sangue e troppo malridotto per poter essere decifrato.`n`n");

    output("`3Infuriat".($session['user']['sex']?"a":"o")." urli \"`rDEVO SALVARL".($session['user']['sex']?"O":"A")."!!`3\" Ma dove andare ?`n`n");

    output("<a href=forest.php?op=1>Vai al Mastio di Viverna</a>`n", true);
    output("<a href=forest.php?op=2>Vai al Bastione Hastur</a>`n", true);
    output("<a href=forest.php?op=3>Vai al Maniero di Ganjipor</a>`n", true);
    output("<a href=forest.php?op=4>Vai al Torrione di Slaag</a>`n", true);
    output("<a href=forest.php?op=5>Vai al Castello di Draco</a>`n", true);
    output("`n<a href=forest.php?op=no>Inutile perdere tempo</a>", true);
    addnav("Vai al");
    addnav("V?Mastio di Viverna","forest.php?op=1");
    addnav("H?Bastione Hastur","forest.php?op=2");
    addnav("G?Maniero di Ganjipor","forest.php?op=3");
    addnav("T?Torrione di Slaag","forest.php?op=4");
    addnav("C?Castello di Draco","forest.php?op=5");
    addnav("L?Lascia perdere","forest.php?op=no");
    addnav("","forest.php?op=1");
    addnav("","forest.php?op=2");
    addnav("","forest.php?op=3");
    addnav("","forest.php?op=4");
    addnav("","forest.php?op=5");
    addnav("","forest.php?op=no");

}else if ($_GET['op']=="no"){
    output("`3Accartocci il biglietto e lo getti tra gli alberi. Non hai paura, solo non vale la pena perdere tempo. ");
    output("No, non hai paura, nient´affatto. Volti le spalle alla richiesta d´aiuto de".($session['user']['sex']?"l giovane":"lla damigella")." ");
    output("e ti dirigi tra gli alberi nel bel mezzo della foresta in cerca di qualcosa di meno pericol... ehm, più stimolante.");
    addnav("Torna alla foresta","forest.php");
    $session['user']['specialinc']="";
}else{
    switch($_GET['op']) {
        case 1: $loc = "`GMastio di Viverna";
            break;
        case 2: $loc = "`gBastione Hastur";
            break;
        case 3: $loc = "`RManiero di Manjipor";
            break;
        case 4: $loc = "`rTorrione di Slaag";
            break;
        case 5: $loc = "`FCastello di Draco";
            break;
    }

    output("`n`3Avvolto in un silenzio irreale ti avvicini a un edificio lugubre e semidiroccato: il `&$loc.");
    output(" `3Con grande coraggio irrompi attraverso il portone principale e ti ritrovi ad affrontare un esercito ");
    output("di creature del male che ringhiando e ululando, ti sbarrano la strada. `nIncominci il combattimento ");
    output("seminando cadaveri al tuo passaggio, le cotte di maglia dei nemici che stridono sotto la tua lama e ");
    output("spruzzi di sangue ad ogni tuo fendente che giunge a segno. Le immonde creature pian piano ");
    output("indietreggiano incalzate dai tuoi vigorosi assalti...  `n");

    switch (e_rand(1, 10)) {
        case 1:
        case 2:
        case 3:
        case 4:
            output("`3Giungi infine a quella che deve essere la stanza della prigionia e apri la porta di una camera ben arredata. `n`n");
            output("Al suo interno incroci lo sguardo impaurito di ".($session['user']['sex']?"un giovane, bello":"una giovane, bella")." e riconoscente occupante.`n`n");
            output("\"`#Oh, il mio messaggio allora non è rimasto inascoltato! `3\" ".($session['user']['sex']?"dice lui. \"`#Mia Salvatrice,":"esclama lei. \"`#Mio Eroe,")." come posso ringraziarti?`3\"`n`n");
            output("Presi da irrefrenabile passione trascorrete alcune ore l'uno nelle braccia dell'altra, `n");
            output("ma il tuo spirito di avventurier".($session['user']['sex']?"a":"o")." prende il sopravvento, ");
            output("decidi di lasciare ".($session['user']['sex']?"il principe":"la principessa")." e di andare per la tua strada. `n");
            output("Non senza aver prima ricevuto un segno tangibile del suo apprezzamento e della sua gratitudine per aver".($session['user']['sex']?"lo liberato":"la liberata")." dalla prigionia. `n`n");
            switch (e_rand(1, 5)) {
                case 1:
                    output("".($session['user']['sex']?"Lui":"Lei")." ti consegna infatti una piccola borsa di cuoio.`n`n");
                    $reward = e_rand(1, 2);
                    output("`^Guadagni $reward gemm".(($reward-1)?"e":"a")."!");
                    $session['user']['gems'] += $reward;
                    break;
                case 2:
                    output("".($session['user']['sex']?"Lui":"Lei")." ti consegna infatti una piccola borsa di cuoio.`n`n");
                    $reward = e_rand(1, $session['user']['level']*30);
                    output("`^Guadagni $reward monete!");
                    $session['user']['gold'] += $reward;
                    break;
                case 3:
                    output("".($session['user']['sex']?"Lui":"Lei")." ti ha mostrato infatti cose che non avevi mai neanche sognato.`n`n");
                    output("`^Hai acquisito esperienza!");
                    $session['user']['experience'] *= 1.1;
                    break;
                case 4:
                    output("".($session['user']['sex']?"Lui":"Lei")." ti ha insegnato come essere ".($session['user']['sex']?"una vera donna":"un vero uomo").".`n`n");
                    output("`^Guadagni due punti di fascino!");
                    $session['user']['charm'] += 2;
                    break;
                case 5:
                    output("".($session['user']['sex']?"Lui":"Lei")." ti mostra un sentiero segreto attraverso il bosco che ti permette di guadagnare tempo sulla via del ritorno.`n`n");
                    output("`^Guadagni un combattimento nella foresta e vieni totalmente guarito!");
                    $session['user']['turns'] ++;
                    if ($session['user']['hitpoints']<$session['user']['maxhitpoints']) $session['user']['hitpoints'] = $session['user']['maxhitpoints'];
                    break;
                }
            break;
        case 5:
            output("`3Giungi infine a quella che deve essere la stanza della prigionia e apri la porta di una camera ben arredata. `n`n");
            output("Noti immediatamente che nella camera c´è un grosso scrigno, dal cui interno provengono grida smorzate. ");
            output("Con un colpo di spada tranci il lucchetto che lo chiude e lo spalanchi di colpo, assumendo una posa eroica tronfio del tuo successo. `n");
            output("Con tuo grande sgomento dall'enorme baule esce ".($session['user']['sex']?"un mostruoso e ributtante":"una mostruosa e ributtante")." troll!! `n ");
            output("Con un balzo felino ti salta addosso e incomincia a sedurti.`nDopo alcune ore di... eccitazione,");
            output(" ".($session['user']['sex']?"egli":"ella")." decide di lasciarti andare per la tua strada. `n");
            output("Dire che ti senti sporc".($session['user']['sex']?"a":"o")." sarebbe un eufemismo.`n`n");

            output("`%Perdi un combattimento nella foresta!`n");
            output("`%Perdi un punto di fascino!`n");
            if ($session['user']['turns'] > 0) $session['user']['turns']--;
            if ($session['user']['charm'] > 0) $session['user']['charm']--;
            break;
        case 6:
            output("`3Giungi infine a quella che deve essere la stanza della prigionia e apri la porta di una camera ben arredata. `n`n");
            output("Entri nella stanza pregustando il tuo successo ma .... `5Orrore !! `3`nTi ritrovi di fronte a `i`X".($session['user']['sex']?"un vecchio raggrinzito":"una vecchia megera raggrinzita")."`i`3. ");
            output("Sgomento dinnanzi alla cosa mostruosa che hai davanti fuggi urlando dalla stanza. Ti sembra che in qualche modo una maledizione ti sia piovuta addosso. `n`n");
            output("`%Perdi un punto di fascino!`n");
            if ($session['user']['charm'] > 0) $session['user']['charm']--;
            break;
        case 7:
            output("`3Giungi infine a quella che deve essere la stanza della prigionia e apri la porta di una camera ben arredata. `n`n");
            output("Entri nella stanza pregustando il tuo successo ma, seduto alla finestra, vedi un damerino effemminato dall´aspetto ridicolo ");
            output("\"`5Oh, sei qui!`3\" strilla, saltando in piedi. Mentre viene verso di te, inciampa nella sua ");
            output("coperta e si ritrova intrecciato tra le lenzuola. Approfitti di questa opportunità per svignartela il più rapidamente ");
            output("e silenziosamente possibile. Fortunatamente nessuno è stato ferito tranne il tuo orgoglio.`n`n");
            break;
        case 8:
            output("`3Ne segue uno scontro immane, in cui ti impegni eroicamente! Ma quando ti sembra di riuscire ad avere la meglio sui tuoi nemici, ");
            output("ecco che giungono i rinforzi: un serrato cuneo di nere creature munite di piccoli scudi tondi penetra con la forza fra la massa dei compagni in rotta. ");
            output("Ti scagli contro i nuovi avversari, la tua lama colpisce uno strato di cuoio e legno, ma le creature ti circondano, sollevano gli scudi in maniera  ");
            output("da isolare la tua spada, un'asta di lancia si insinua tra i tuoi piedi facendoti inciampare mentre qualcosa ti colpisce alla nuca facendoti precipitare ");
            output("in un buio mondo di sofferenza. E nello stesso istante in cui le tue ginocchia cedono e si appoggiano al terreno, cadi sotto le lame dei tuoi nemici. `n`n");
            output("`%Sei morto!`n`n");
            output("`3La lezione di vita ricevuta compensa tutta l´esperienza che avresti dovuto perdere.`n");
            output("Potrai continuare a giocare domani.");
            $session['user']['alive']=false;
            $session['user']['hitpoints']=0;
            addnav("Notizie quotidiane","news.php");
            addnews("`%".$session['user']['name']."`3 è stato uccis".($session['user']['sex']?"a":"o")." mentre tentava di salvare un".($session['user']['sex']?" principe":"a principessa")." nel $loc`0.");
            break;
        case 9:
            output("`3Ne segue uno scontro immane, in cui ti impegni eroicamente! Ma quando ti sembra di riuscire ad avere la meglio sui tuoi nemici, ");
            output("ecco che giungono i rinforzi: un serrato cuneo di nere creature munite di piccoli scudi tondi penetra con la forza fra la massa dei compagni in rotta. ");
            output("Ti rendi conto di essere stanchissim".($session['user']['sex']?"a":"o")." oltre che in inferiorità numerica, ma riesci a trovare un'opportunità per liberarti dall'accerchiamento dei tuoi nemici.`n");
            output("L'ultima cosa che gli abitanti del $loc `3 vedono è la tua schiena, mentre fuggi a rotta di collo nascondendoti nel folto degli alberi.`n`n");
            output("`%Perdi un combattimento nella foresta!`n");
            output("`%Perdi molti dei tuoi punti ferita!`n");
            if ($session['user']['turns']>0) $session['user']['turns']--;
            if ($session['user']['hitpoints']>($session['user']['hitpoints']*.1)) $session['user']['hitpoints']=round($session['user']['hitpoints']*.1,0);
            break;
        case 10:
            output("`3Giungi infine a quella che deve essere la stanza della prigionia e apri la porta di una camera ben arredata. `n`n");
            output("Ti precipiti al suo interno e trovi un sorpreso nobiluomo e sua moglie che si stanno accingendo a cenare.`n`n");
            output("\"`^Che significa tutto questo?`3\" domanda. Balbettando per lo stupore, tenti di spiegare che sei finit".($session['user']['sex']?"a":"o")." nel posto ");
            output("sbagliato, ma non sembra che la tua scusa venga creduta. Vengono chiamate le autorità, lo sceriffo ti fà una bella paternale e poi ");
            output("decide di lasciarti andare per la tua strada a patto che tu rimborsi completamente i danni che hai provocato.`n`n");
            output("`%Perdi tutte le monete che avevi con te!`n");
            $session['user']['gold']=0;
            break;
    }

    $session['user']['specialinc']="";
}

?>