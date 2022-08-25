<?php
/* Original script by LonnyL
Modified by Excalibur for www.ogsi.it
*/
output("`2Noti un movimento dietro un cespuglio e scatti prontamente con un balzo!  Ohhhh!`n
Inciampi e cadi in pieno in una pozza di fango!  Sei coperto da capo a piedi di palta!`n`n");
$dirty = e_rand(1,5);
$session['user']['clean'] += $dirty;
output("Mentre ti rialzi, aiutandoti con le mani, nel fango senti qualcosa sotto il palmo della mano:`n");
switch (e_rand(0,5)) {
    case 0:
        output("`@estraendola dalla fanghiglia ti ritrovi a rimirare una stupenda `&gemma`@! `n");
        output("Non è stata una giornata totalmente sfortunata!`n`n");
        $session['user']['gems']++;
    break;
    case 1:
        output("sollevandola dal fango ti accorgi di essere ferito.`n Il `\$sangue`2 sgorga copioso dal taglio ");
        output("che ti sei procurato. `4La vista ti si annebbia e perdi conoscenza .... `2Quando ti risvegli ti senti ");
        output("debole e scopri di aver perso molti dei tuoi punti ferita!`n`n");
        $session['user']['hitpoints'] -= intval($session['user']['hitpoints']/3);
        if ($session['user']['hitpoints'] <= 0) $session['user']['hitpoints'] = 0;
    break;
    case 2:
        $gold = e_rand(100,200)*$session['user']['level'];
        output("`#sembra un borsellino di cuoio, ed aprendolo vi trovi all'interno `^$gold `#pezzi d'oro!`n");
        output("Non è stata una giornata totalmente sfortunata!`n`n");
        $session['user']['gold'] += $gold;
    break;
    case 3:
        output("`7ha tutta l'aria di una vecchia pergamena. Incuriosito la srotoli con cura, ed inizi a leggerla. `n");
        output("`2Terminata la lettura senti come un brivido percorrere la tua schiena, e ti senti più `@vigoroso!`n");
        output("`@La `7Dea della Fanghiglia`@ ti ha infuso un maggior vigore per i tuoi combattimenti!`n`n");
        $turni = e_rand(3,5);
        $session['bufflist']['deadef'] = array(
             "name"=>"`#Dea del Fango",
             "rounds"=>$turni,
             "startmsg"=>"`n`^Lanci schizzi di palta contro il tuo nemico!`n",
             "minioncount"=>round($session['user']['level']/3)+1,
             "wearoff"=>"`&Hai esaurito la scorta di fango",
             "maxbadguydamage"=>round($session['user']['level']/2,0)+1,
             "effectmsg"=>"`&Gli schizzi di fango colpiscono {badguy} e gli procurano {damage} punti danno.",
             "activate"=>"roundstart"
             );
    break;
    case 4:
        output("`7ha tutta l'aria di una vecchia pergamena. Incuriosito la srotoli con cura, ed inizi a leggerla. `n");
        output("`2Terminata la lettura senti come un brivido percorrere la tua schiena, e ti senti più `@prestante!`n");
        output("`@La `7Dea della Fanghiglia`@ ti ha infuso un maggior vigore per i tuoi combattimenti!`n`n");
        $turni = e_rand(5,15);
        $session['bufflist']['deadef'] = array(
             "name"=>"`#Dea del Fango",
             "rounds"=>$turni,
             "wearoff"=>"`&Hai esaurito la scorta di fango",
             "defmod"=>1.3,
             "roundmsg"=>"`&Lanci schizzi di fango contro {badguy} accecandolo. Il suo colpo giunge a bersaglio ma con potenza ridotta.",
             "activate"=>"defense"
             );
    break;
    case 5:
        output("`5sembra un borsellino di cuoio, il `b`%TUO`5`b borsellino! `n`2Lo apri e scopri con orrore che tutti ");
        output("i pezzi d'oro che vi erano contenuti sono andati persi nel fango!!`n");
        switch (e_rand(1,2)) {
            case 1:
                $recupero = intval(($session['user']['gold'] * e_rand(1,10))/100);
                output("`3Frughi nel fango nella speranza di ritrovarne qualcuno, e alla fine riesci a ritrovarne `#$recupero`n`n");
                $session['user']['gold'] = $recupero;
            break;
            case 2:
                output("Frughi nel fango nella speranza di ritrovarne qualcuno, ma purtroppo non riesci a recuperare ");
                output("neanche un singolo pezzo d'oro! Sembrano essere stati inghiottiti dalla melma!`n`n");
                $session['user']['gold'] = 0;
            break;
        }
    break;
}
output("`n`n`6Inoltre perdi un turno per ripulirti dal fango che ti ricopre interamente!`n`n");
$session['user']['turns'] -= 1;
addnav("`@Torna alla Foresta","forest.php");
?>