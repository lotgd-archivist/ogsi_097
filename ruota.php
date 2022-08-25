<?php
/**************************
* Written by Excalibur    *
* excalibur@fastwebnet.it *
* for www.ogsi.it/logd    *
* by an idea of Aris      *
* You can do whatever     *
* you want with this      *
* but leave this note     *
***************************/

require_once "common.php";
if (!isset($session)) exit();
checkday();
page_header("La Giostra del Paese");
$session['user']['locazione'] = 167;
output("`3`c`bLa Giostra del Paese`b`c `n `n");
if ($session['user']['playerfights']==0)
    {
    output("Sei troppo stanco anche al solo pensiero di muovere ancora un semplice passo tra i carrozzoni. Decidi quindi di tornare al ");
    output("villaggio per riposarti e recuperare le forze. Riprova un altro giorno. `n");
    addnav("Torna al Villaggio","village.php");
    }
else {
if ($_GET['op']=="" && $session['user']['level']==1)
    {
    output("`@ Ti aggiri tra i carrozzoni e noti un gitano che sta gridando \"`5Venite madame e messeri, abbiamo ancora un ");
    output("biglietto da vendere, potrebbe essere quello fortunato. Ehi, lei signor".($session['user']['sex']?"a":"e").", ");
    output("potrebbe essere il suo giorno fortunato, perchè non approfittarne ?`@\". `n");
    output("Ti avvicini al carrozzone dell'uomo per chiedere il prezzo del biglietto o preferisci continuare a girovagare ");
    output("tra i carrozzoni per trovare altre occasioni ?");
    addnav("Chiedi il Prezzo","ruota.php?op=prezzo");
    addnav("Prosegui il Giro","ruota.php?op=giro");
    }
else if ($_GET['op']=="" && $session['user']['level']!=1)
    {
    output("`@Ti aggiri tra i carrozzoni e ne noti uno con le serrande abbassate con la scritta `6Ruota della Fortuna`@. `n");
    output("Ti domandi coma mai sia chiuso ... forse il proprietario è andato a pranzo, quando noti un biglietto con la ");
    output("scritta `3\"Chiuso per manutenzione\"`@. Decidi quindi di proseguire nel tuo giro e ripassare un altro giorno.`n");
    addnav("Torna al Campo Zingari","gypsycamp.php");
    }
else if ($_GET['op']=="giro")
    {
    output("`@Decidi che la fortuna non è la tua compagna, e preferisci svagarti girovagando tra i carrozzoni della Giostra del Paese.");
    output("Osservi con attenzione tutte le attrazioni e dopo averne provata qualcuna ti ritrovi con due ");
    output("coccarde di stoffa colorata in più e qualche pezzo d'oro in meno in tasca.");
    if ($session['user']['gold']>0)
        {
        $orospeso=intval($session['user']['gold']/2);
        if ($orospeso > 50) $orospeso=50;
        if ($orospeso<50 and $orospeso<$session['user']['gold']) $orospeso=$session['user']['gold'];
        output("`@Hai speso `6".$orospeso."`@ pezzi d'oro per vincere due stupende `6`b`icoccarde di stoffa colorata`i`b`@.");
        $session['user']['gold']-=$orospeso;
        debuglog("ha speso $orospeso pezzi d'oro in coccarde di stoffa colorata alla Giostra del Paese");
        }
    else
        {
        if ($session['user']['goldinbank']>49){
        $session['user']['goldinbank']-=50;
        output("`@Hai prelevato e speso `650`@ pezzi d'oro per vincere due stupende `6`b`icoccarde di stoffa colorata`i`b`@.");
        debuglog("ha speso 50 pezzi d'oro in coccarde di stoffa colorata alla Giostra del Paese");
        }
        else{
        $orospeso=$session['user']['goldinbank'];
        $session['user']['goldinbank']=0;
        output("`@Hai prelevato e speso ".$orospeso." pezzi d'oro per vincere due stupendi `6`b`icoccarde di stoffa colorata`i`b`@.");
        debuglog("ha speso $orospeso pezzi d'oro in coccarde di stoffa colorata alla Giostra del Paese");
        }
        }
    addnav("Torna al Campo Zingari","gypsycamp.php");
    } //chiusura elseif giro
else if ($_GET['op']=="prezzo")
    {
    page_header("La Ruota della Fortuna");
    output("`3`c`bLa Ruota della Fortuna`b`c `n `n");
    output("`@Decidi di sfidare la Dea Bendata alla `^Ruota della Fortuna`@ e ti avvicini al bancone chiedendo il ");
    output("prezzo del biglietto della lotteria. L'uomo ti rivolge la parola mentre ti mostra il biglietto della ");
    output("lotteria, e ti dice: \"`3Accetto diversi tipi di pagamento, gemme, oro, ma anche punti esperienza vanno bene`@\". ");
    output("E mentre ti parla ti indica un listino appeso alla parete alle sue spalle, dove leggi: `n`n ");
    output("`b`c`7PAGAMENTI ACCETTATI`c`n");
    output("`c`!===================`c`n");
    output("<span style='color:#888888'>",true);
    output("`c1 GEMMA (Premio in Gemme)`c`n");
    output("<span style='color:#AAAA22'>",true);
    output("`c100 Pezzi d'Oro (Premio in Oro)`c`n");
    output("<span style='color:#FF6666'>",true);
    output("`c100 Punti Exp (Premio in Esperienza)`c`n");
    output("`c`@Tassa di partecipazione: 1 Combattimento PvP`c`n");
    output("`c`!===================`c`n`b");
    output("Cosa decidi di giocarti? `n");

    addnav("Giocate Possibili");
    addnav("");
    if ($session['user']['gems']>0) addnav("G?1 Gemma","ruota.php?op=gem");
    if ($session['user']['gold']>99) addnav("O?100 Oro","ruota.php?op=oro");
    if ($session['user']['experience']>99) addnav("E?100 Exp","ruota.php?op=exp");
    addnav("");
    addnav("Z?Campo degli Zingari","gypsycamp.php");
    addnav("V?Torna al Villaggio","village.php");
    }
else if ($_GET['op']=="gem")
    {
    page_header("La Ruota della Fortuna");
    $session['user']['gems']-=1;
    $session['user']['playerfights']-=1;
    $chance=e_rand(1,30);
    $chance1=e_rand(1,10);
    output("`@L'uomo afferra velocemente la tua gemma e ti cede il biglietto della lotteria. `n\"`3Ottima scelta ".($session['user']['sex']?"madamigella":"giovanotto")."`@\" ");
    output("ti dice il gitano, dopodichè si dirige alla ruota e la fa girare .... `nStringi il biglietto nelle tue mani invocando ");
    output("la dea Fortuna, e finalmente la Ruota si ferma sul `4 ".$chance."`@. `n`n");
    switch ($chance)
        {
        case 1: case 2: case 3: case 4: case 5: case 6: case 7: case 8: case 9: case 10:
        $chance+=$chance1;
        output("`@Guardi il tuo biglietto con speranza, ma scopri che il numero su di esso è `6".$chance."`@.`n`4 Hai perso una ");
        output("delle tue sudate gemme!!. `n`@Forse la prossima volta sarai più fortunato. `n");
        addnews("`2".$session['user']['name']." ha perso una gemma alla `6Ruota della Fortuna");
        debuglog(" ha perso 1 gemma alla Ruota della Fortuna");
        break;
        case 11: case 12: case 13: case 14: case 15: case 16: case 17: case 18: case 19: case 20:
        $chance-=$chance1;
        output("`@Guardi il tuo biglietto con speranza, ma scopri che il numero su di esso è `4".$chance."`@. `nNoti però che ");
        output("è dello stesso colore di quello estratto, e ciò ti fa rivincere la gemma che avevi puntato. `nNon hai ne vinto ");
        output("ne perso nulla.");
        $session['user']['gems']+=1;
        addnews("`2".$session['user']['name']." ha giocato alla `6Ruota della Fortuna");
        break;
        case 21: case 22: case 23: case 24: case 25: case 26: case 27: case 28:
        output("`@Guardi il tuo biglietto con speranza, e scopri che il numero su di esso è `6".$chance."`@!! `n`n");
        output("`b`cHai vinto `!2 gemme`@, con un guadagno totale di `!1 gemma`@!!`b`c`n`n");
        output("Incredulo del fatto di aver vinto guardi il gitano senza riuscire a spiccicare parola. `nLui di rimando ");
        output("ti prende il biglietto e confrontandolo con il numero uscito sulla ruota ti dice con fare scocciato:`n");
        output("\"`3Mhhh ... sembra che tu abbia vinto`@\", ma ti paga il premio previsto di `!2 gemme`@!!!`n`n");
        $session['user']['gems']+=2;
        debuglog(" ha vinto 1 gemma alla Ruota della Fortuna");
        addnews("`2".$session['user']['name']." ha guadagnato una gemma alla `6Ruota della Fortuna");
        break;
        case 29: case 30:
        output("`@Guardi il tuo biglietto con speranza, e scopri che il numero su di esso è `4".$chance."`@!! `n");
        output("Inoltre anche il colore è uguale a quello del numero uscito!!`n`n");
        output("`b`cHai vinto `!3 gemme`@, con un guadagno totale di `!2 gemme`@!!`b`c`n`n");
        output("Incredulo del fatto di aver vinto guardi il gitano senza riuscire a spiccicare parola, lui di rimando ");
        output("ti prende il biglietto e confrontandolo con il numero uscito sulla ruota ti dice con fare scocciato:`n");
        output("\"`3Mhhh ... sembra che tu abbia vinto`@\", ma ti paga il premio previsto di `!3 gemme`@!!!`n`n");
        $session['user']['gems']+=3;
        debuglog(" ha vinto 2 gemme alla Ruota della Fortuna");
        addnews("`2".$session['user']['name']." ha guadagnato due gemme alla `6Ruota della Fortuna");
        break;
        } //chiusura switch gem
        addnav("Z?Campo degli Zingari","gypsycamp.php");
        addnav("Torna al Villaggio","village.php");
    } //chiusura elseif gem

else if ($_GET['op']=="oro")
    {
    page_header("La Ruota della Fortuna");
    $session['user']['gold']-=100;
    $session['user']['playerfights']-=1;
    $chance=e_rand(1,30);
    $chance1=e_rand(1,10);
    output("`@L'uomo afferra velocemente i tuoi 100 pezzi d'oro e ti cede il biglietto della lotteria. \"`3Ottima scelta ".($session['user']['sex']?"madamigella":"giovanotto")."`@\" ");
    output("ti dice il gitano, dopodichè si dirige alla ruota e la fa girare .... `nStringi il biglietto nelle tue mani invocando ");
    output("la dea Fortuna, e finalmente la Ruota si ferma sul `4 ".$chance."`@. `n`n");
    switch ($chance)
        {
        case 1: case 2: case 3: case 4: case 5: case 6: case 7: case 8: case 9: case 10:
        $chance+=$chance1;
        output("`@Guardi il tuo biglietto con speranza, ma scopri che il numero su di esso è `6".$chance."`@. `n`4Hai perso 100 ");
        output("dei tuoi sudati pezzi d'oro!! `n`@Forse la prossima volta sarai più fortunato. `n");
        addnews("`2".$session['user']['name']." ha perso 100 pezzi d'oro alla `6Ruota della Fortuna");
        debuglog(" ha perso 100 oro alla Ruota della Fortuna");
        break;
        case 11: case 12: case 13: case 14: case 15: case 16: case 17: case 18: case 19: case 20:
        $chance-=$chance1;
        output("`@Guardi il tuo biglietto con speranza, ma scopri che il numero su di esso è `4".$chance."`@. `nNoti però che ");
        output("è dello stesso colore di quello estratto, e ciò ti fa vincere i 100 pezzi d'oro che avevi puntato. `nNon hai ne vinto ");
        output("ne perso nulla.");
        $session['user']['gold']+=100;
        addnews("`2".$session['user']['name']." ha giocato alla `6Ruota della Fortuna");
        break;
        case 21: case 22: case 23: case 24: case 25: case 26:
        output("`@Guardi il tuo biglietto con speranza, e scopri che il numero su di esso è `6".$chance."`@!! `n`n");
        output("`b`cHai vinto `!200 pezzi d'oro`@, con un guadagno totale di `!100 pezzi d'oro`@!!`b`c`n`n");
        output("Incredulo del fatto di aver vinto guardi il gitano senza riuscire a spiccicare parola. `nLui di rimando ");
        output("ti prende il biglietto e confrontandolo con il numero uscito sulla ruota ti dice con fare scocciato:`n");
        output("\"`3Mhhh ... sembra che tu abbia vinto`@\", ma ti paga il premio previsto di `!200 pezzi d'oro`@!!!`n`n");
        $session['user']['gold']+=200;
        addnews("`2".$session['user']['name']." ha guadagnato 100 pezzi d'oro alla `6Ruota della Fortuna");
        debuglog(" ha vinto 100 oro alla Ruota della Fortuna");
        break;
        case 27: case 28: case 29: case 30:
        output("`@Guardi il tuo biglietto con speranza, e scopri che il numero su di esso è `4".$chance."`@!! `n");
        output("Inoltre anche il colore è uguale a quello del numero uscito!!`n`n");
        output("`b`cHai vinto `!300 pezzi d'oro`@, con un guadagno totale di `!200 pezzi d'oro`@!!`b`c`n`n");
        output("Incredulo del fatto di aver vinto guardi il gitano senza riuscire a spiccicare parola. `nLui di rimando ");
        output("ti prende il biglietto e confrontandolo con il numero uscito sulla ruota ti dice con fare scocciato:`n");
        output("\"`3Mhhh ... sembra che tu abbia vinto`@\" , ma ti paga il premio previsto di `!300 pezzi d'oro`@!!!`n`n");
        $session['user']['gold']+=300;
        addnews("`2".$session['user']['name']." ha guadagnato 200 pezzi d'oro alla `6Ruota della Fortuna");
        debuglog(" ha vinto 200 oro alla Ruota della Fortuna");
        break;
        } //chiusura switch oro
        addnav("Z?Campo degli Zingari","gypsycamp.php");
        addnav("Torna al Villaggio","village.php");
    } //chiusura elseif oro

else if ($_GET['op']=="exp")
    {
    page_header("La Ruota della Fortuna");
    $session['user']['experience']-=100;
    $session['user']['playerfights']-=1;
    $chance=e_rand(1,30);
    $chance1=e_rand(1,10);
    output("`@L'uomo, con dei gesti magici, ti toglie 100 punti esperienza e ti cede il biglietto della lotteria. `n\"`3Ottima scelta ".($session['user']['sex']?"madamigella":"giovanotto")." `@\" ");
    output("ti dice il gitano, dopodichè si dirige alla ruota e la fa girare .... `nStringi il biglietto nelle tue mani invocando ");
    output("la dea Fortuna, e finalmente la Ruota si ferma sul `4 ".$chance."`@. `n`n");
    switch ($chance)
        {
        case 1: case 2: case 3: case 4: case 5: case 6: case 7: case 8: case 9: case 10:
        $chance+=$chance1;
        output("`@Guardi il tuo biglietto con speranza, ma scopri che il numero su di esso è `6".$chance."`@. `n`4Hai perso 100 ");
        output("dei tuoi sudati punti esperienza!! `n`@Forse la prossima volta sarai più fortunato. `n");
        addnews("`2".$session['user']['name']." ha perso 100 punti esperienza alla `6Ruota della Fortuna");
        debuglog(" ha perso 100 exp alla Ruota della Fortuna");
        break;
        case 11: case 12: case 13: case 14: case 15: case 16: case 17: case 18: case 19: case 20:
        $chance-=$chance1;
        output("`@Guardi il tuo biglietto con speranza, ma scopri che il numero su di esso è `4".$chance."`@. `nNoti però che ");
        output("è dello stesso colore di quello estratto. `nIl gitano con un rituale simile a quello con cui te l'ha tolta, ");
        output("ti restituisce i 100 punti esperienza. `nNon hai vinto nulla, ma se non altro non ci hai perso nulla.");
        $session['user']['experience']+=100;
        addnews("`2".$session['user']['name']." ha giocato alla `6Ruota della Fortuna");
        break;
        case 21: case 22: case 23: case 24: case 25: case 26:
        output("`@Guardi il tuo biglietto con speranza, e scopri che il numero su di esso è `6".$chance."`@!! `n`n");
        output("`b`cHai vinto `!!!, guadagni `!200 punti esperienza`@ con un bilancio di `!100 punti esperienza`@!!`b`c`n`n");
        output("Incredulo del fatto di aver vinto guardi il gitano senza riuscire a spiccicare parola, lui di rimando ");
        output("ti prende il biglietto e confrontandolo con il numero uscito sulla ruota ti dice:\"`3Mhhh ... sembra che ");
        output("tu abbia vinto`@\", compie un rituale, e ti paga il premio previsto.`n`n");
        $session['user']['experience']+=200;
        addnews("`2".$session['user']['name']." ha guadagnato 100 punti esperienza alla `6Ruota della Fortuna");
        debuglog(" ha vinto 100 exp alla Ruota della Fortuna");
        break;
        case 27: case 28: case 29: case 30:
        output("`@Guardi il tuo biglietto con speranza, e scopri che il numero su di esso è `4".$chance."`@!! `n");
        output("Inoltre anche il colore è uguale a quello del numero uscito!!`n`n");
        output("`b`cHai vinto `!!!, guadagni `!300 punti esperienza`@ con un bilancio di `!200 punti esperienza`@!!`b`c`n`n");
        output("Incredulo del fatto di aver vinto guardi il gitano senza riuscire a spiccicare parola. `nLui di rimando ");
        output("ti prende il biglietto e confrontandolo con il numero uscito sulla ruota ti dice:`n\"`3Mhhh ... sembra che ");
        output("tu abbia vinto`@\", compie un rituale, e ti paga il premio previsto!!!`n`n");
        $session['user']['experience']+=300;
        addnews("`2".$session['user']['name']." ha guadagnato 200 punti esperienza alla `6Ruota della Fortuna");
        debuglog(" ha vinto 200 exp alla Ruota della Fortuna");
        break;
        } //chiusura switch gem
        addnav("Z?Campo degli Zingari","gypsycamp.php");
        addnav("Torna al Villaggio","village.php");
    } //chiusura elseif gem


} //chiusura del primo else

page_footer();
?>