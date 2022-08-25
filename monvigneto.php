<?php
/***************************************
Vineyard
Written by Robert for Maddnets LoGD
Tradotto da Excalibur per www.ogsi.it
La Vigna è parte del Monastero
i monaci vivono delle offerte fatte loro
****************************************/
require_once "common.php";
if ($session['user']['level']>14) {
    page_header("Il Cortile del Monastero");
    output("`n`n`2Mentre ti stai avvicinando alla `3Vigna del Monastero`2 un Monaco appare dal nulla `n");
    output(" e ti sussurra     `^'Non credi sia tempo di affrontare il Drago?'");
    addnav("Torna alla Foresta","forest.php");}
else{

addnav("Vigna");
addnav("");
addnav("Mangia");
addnav("(U) Ciotola di Uva","monvigneto.php?op=grappolo");
addnav("(P) Cestino di Uva Passa","monvigneto.php?op=uva");
addnav("");
addnav("Bevi");
addnav("(C) Calice di Vin Santo","monvigneto.php?op=vinsanto");
addnav("(F) Fiasca di Vino","monvigneto.php?op=vino");
addnav("Abbandona");
addnav("(M) Monastero","monastero.php");

page_header("Il Vigneto del Monastero");
$prezzovino = 100 + (50 * $session['user']['level']);
$prezzouva = 400 + (50 * $session['user']['level']);
$prezzouvapassa = 700 + (50 * $session['user']['level']);
output(" `5`c`bIl Vigneto del Monastero`b`c `n`n");
output(" `&Nella direzione opposta a quella da cui provieni, nascosto alla vista, scopri `5Il Vigneto del Monastero`&. `n");
output(" `2Vedi numerosi Monaci che lavorano qui. E lavorano duramente, noti con stupore. ");
output(" Un Monaco si avvicina e ti parla: `n`6Per una piccola offerta al Monastero, saremo felici ");
output(" di condividere i frutti del nostro duro lavoro. `n`n");
output(" `&Una donazione di `^".$prezzovino." Pezzi d'Oro `&per una `5fiasca di Vino`&. `n`n");
output(" Una donazione di `^".$prezzouva." Pezzi d'Oro `&per una `5ciotola di Uva`&. `n`n");
output(" Una donazione di `^".$prezzouvapassa." Pezzi d'Oro `&per un `5cestino di Uva Passa`&. `n`n");
output(" Una donazione di `^1 Gemma `&per un `5calice di Vin Santo`&. `n`n");

if ($_GET[op]=="grappolo"){
    if ($session['user']['gold'] >= $prezzouva){
        $temp=intval($session['user']['maxhitpoints']/20);
        if ($temp < 10) {
            $temp = 10;
        }
        if ($temp > 20) {
            $temp = 20;
        }
        if ($session['user']['drunkenness']>99) {
            output("`&Sei già abbastanza ubriaco, non ti darò altri `5Grappoli d'Uva`&.");
        }else{
            $session['user']['hitpoints']+=$temp;
            $session['user']['gold']-=$prezzouva;
            $session['user']['drunkenness']+=20;
            $session['user']['bladder']+=1;
            output(" `&Fai l'offerta di $prezzouva Pezzi d'Oro, il Monaco ti ringrazia e ti porge una grande `5Ciotola di Uva`&. `n");
            output(" Ti siedi ad un tavolo vicino ed inizi ad assaporare l'`5Uva`&.`n");
            output(" Questi sono i più deliziosi grappoli d'uva che tu abbia mai gustato! `n");
            output(" Mentre stai terminando l'uva, ti senti molto più in forma di prima. `n");
            output(" I tuoi HitPoints sono temporaneamente aumentati di `5`b".$temp."`b`&");
            debuglog("compra $temp HP al vigneto");
        }
    }else {
        output(" `4Non ti preoccupare, non puoi far fronte alla donazione questa volta, ecco prendi una Mela.");
    }
}
if ($_GET[op]=="vinsanto"){
    if ($session['user']['gems'] > 0){
        $temp=intval($session['user']['maxhitpoints']/10);
        if ($temp < 30) {
            $temp = 30;
        }
        if ($temp > 40) {
            $temp = 40;
        }
        if ($session['user']['drunkenness']>149) {
        output("`&Sei già abbastanza ubriaco, non ti darò altro `5Vin Santo`&.");
        }else{
            $session['user']['hitpoints']+=$temp;
            $session['user']['gems']-=1;
            $buff = array("name"=>"`\$Forza Divina","rounds"=>25,"wearoff"=>"`!La tua Forza Divina scompare e torni normale", "defmod"=>1.4,"roundmsg"=>"Ti senti in stato di grazia!","activate"=>"defense");
            $session['bufflist']['magicweak'] = $buff;
            $session['user']['drunkenness']+=50;
            $session['user']['bladder']+=6;
            output(" `&Fai l'offerta di 1 Gemma, il Monaco ti ringrazia e ti porge un gigantesco `5Calice di Vin Santo`&. `n");
            output(" `&Ti siedi ad un tavolo vicino ed inizi a bere il `5Nettare Divino`&.`n");
            output(" Questo è il miglior `5Vin Santo`& che tu abbia mai gustato in vita tua! `n");
            output(" Dopo aver bevuto fino all'ultima goccia, senti di essere molto più in forma di prima. `n");
            output(" I tuoi HitPoints sono temporaneamente aumentati di `5`b".$temp."`b`&`n");
            output(" `&Percepisci inoltre una`$ Forza Divina`& che ti aiuterà per qualche combattimento.");
            debuglog("compra $temp HP al vigneto e Forza Divina");
        }
    }else {
        output(" `4Non ti preoccupare, non puoi far fronte alla donazione questa volta, ecco prendi una Pera. ");
    }
}
if ($_GET[op]=="uva"){
    if ($session['user']['gold'] >= $prezzouvapassa){
        $temp=intval($session['user']['maxhitpoints']/15);
        if ($temp < 25) {
            $temp = 25;
        }
        if ($temp > 35) {
            $temp = 35;
        }
        if ($session['user']['drunkenness']>99) {
            output("`&Sei già abbastanza ubriaco, non ti darò altra `5Uva Passa`&.");
        }else{
            $session['user']['drunkenness']+=35;
            $session['user']['bladder']+=2;
            $session['user']['hitpoints']+=25;
            $session['user']['gold']-=$prezzouvapassa;
            output(" `&Fai l'offerta di $prezzouvapassa Pezzi d'Oro, il Monaco ti ringrazia e ti porge un enorme `5Cestino di Uva Passa`&. `n");
            output(" `&Ti siedi ad un tavolo vicino ed inizi ad assaporare l'`5Uva Passa`&.`n");
            output(" Questa è la più deliziosa `5Uvetta`& che tu abbia mai assaporato! `n");
            output(" Dopo aver divorato fino all'ultimo acino, ti accorgi di essere più in forma di prima. `n");
            output(" I tuoi HitPoints sono temporaneamente aumentati di `5`b".$temp."`b`&");
            debuglog("compra $temp HP al vigneto");
        }
    }else {
        output(" `4Non ti preoccupare, non puoi far fronte alla donazione questa volta, ecco prendi una Mela.");
    }
}
if ($_GET[op]=="vino"){
    if ($session['user']['gold'] >= $prezzovino) {
        if ($session['user']['drunkenness']>99) {
            output("`&Sei già abbastanza ubriaco, non ti darò altro `5Vino`&.");
        }else{
            $session['user']['hitpoints']+=5;
            $session['user']['gold']-=$prezzovino;
            $session['user']['drunkenness']+=21;
            $session['user']['bladder']+=4;
            output(" `&Fai l'offerta di $prezzovino Pezzi d'Oro, il Monaco ti ringrazia e ti porge una `5Fiaschetta di Vino`&. `n");
            output(" `&Ti siedi ad un tavolo vicino ed inizi a bere avidamente.`n");
            output(" Il `5Vino`& è delizioso e lo assapori fino all'ultima goccia! `n");
            output(" Dopo averlo bevuto, ti senti più in forma che mai. `n");
            debuglog("compra 5 HP al vigneto");
        }
    }else {
        output(" `4Non ti preoccupare, non puoi far fronte alla donazione questa volta, ecco prendi una Mela. ");
    }
}
}
page_footer();
?>
