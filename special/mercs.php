<?php
/*
Travelling Mercenaries - Forest Special
By Robert (Maddnet) and Talisman (DragonPrime)
version 0.5 May 15, 2004
Most recent copy available at http://dragonprime.cawsquad.net
Translated in italian by Excalibur www.ogsi.it/logd
*/
require_once ("common2.php");
page_header("La Compagnia dei Mercenari");
$luogo = "la foresta";
if ($session['user']['sex']) {
    $sesso="a";
}else {
    $sesso="o";
}
if (!isset($session)) exit();
if ($_GET['op']==""){

    output("`n`n`7Assort$sesso nei tuoi pensieri mentre girovaghi nella foresta, ti ritrovi circondat$sesso una moltitudine di guerrieri delle razze più svariate e da te mai viste sinora. `n");
    output("Quella che inizialmente si presenta a te come un'accozzaglia multicolore di guerrieri, bardati con variopinte corazze, dotati di multiformi scudi e armati `n") ;
    output("delle armi più strane e disparate, si rivela poi essere una efficente compagnia di mercenari, ottimi ed esperti combattenti disposti ad offrire i loro servizi `n");
    output("in cambio di qualche gemma o di piccole somme di denaro. `n");
    output("Il loro capo `b`2Louie `b`7ti spiega che la tua fama di guerrier$sesso giunta fino a loro e che avrebbero piacere di unirsi a te per affiancarti in qualche battaglia.  `n ");
    output("Certo, essendo dei mercenari qualcosina in cambio occorrerà pagare, ma dopo qualche minuto di intensa trattativa riuscite a trovare un accordo: `n ");
    output("per il misero prezzo di `&1 gemma`7, uno di questi valenti combattenti si unirà a te accompagnandoti nelle tue pericolose avventure. `n `n");
    output("Che cosa decidi di fare ?");
    addnav("Getta una Gemma","forest.php?op=give");
    addnav("Non se ne parla","forest.php?op=dont");
    $session['user']['specialinc']="mercs.php";
}elseif     ($_GET['op']=="give"){
            if ($session['user']['gems']>0){

                assegna_mercenario($luogo,107,true,$sesso);

            }else{
                $hploss=round($session['user']['hitpoints']*.09);
                $session['user']['hitpoints']-=$hploss;
                output("`n`n`2Prometti di dare una gemma ai mercenari, ma quando apri il tuo borsellino, scopri di non averne ");
                output("neanche una.  I mercenari estraggono le loro armi e ti attaccano. `n`^Perdi $hploss HP prima di metterti in salvo.");
            }
            $session['user']['specialinc']="";
      }else {



         switch (e_rand(1, 10)) {

            case 1:
            case 2:
            case 3:
            case 4:
            case 5:
            case 6:
                output("`n`n`2Non ritieni i loro servizi degni del pagamento di una delle tue preziose gemme, quindi rifiuti l'offerta e auguri ai mercenari una buona giornata continuando per la tua strada. ");
            break;

            case 7:
            case 8:
                output("`n`n`2Non ritieni i loro servizi degni del pagamento di una delle tue preziose gemme, quindi rifiuti l'offerta e auguri ai mercenari una buona giornata. ");
                output("`nGli avidi avventurieri però notano il tuo borsellino rigonfio appeso alla cintura e sogghignando ti dicono che ti lasceranno proseguire per la tua strada ");
                output("`nsolo dopo aver pagato una piccola tassa di passaggio. Non potendoli affrontare in uno scontro vista la tua palese condizione di inferiorità numerica ") ;
                // se non ha i soldi per pagare se ne può andare gratis tra l'ilarità generale perdendo un punto di fascino per la figuraccia fatta
                if ( $session['user']['gold'] <= 50 ) {

                    output("`ne non avendo sufficente denaro per pagare la gabella che ti è stata imposta, mostri ai mercenari il contenuto del tuo portamonete suscitando l'ilarità ");
                    output("`ngenerale quando si scopre che contiene solo una manciata di fagioli secchi. ");
                    output("`n`nInseguit$sesso da un coro di urla e dagli schiamazzi di quei rozzi guerrieri che sghignazzando sulle tue misere condizioni economiche ti deridono, ");
                    output("`nprosegui scornat$sesso e pien$sesso di vergogna il tuo cammino nella foresta.");
                    output("`n`# Chissà se la notizia di questa tua figuraccia giungerà fino in paese ? ");
                    addnews("`%".$session['user']['name']."`3 ha tentato di pagare i mercenari del$luogo con i fagioli della minestra.");
                    if ($session['user']['charm'] > 0) {
                        $session['user']['charm']--;
                        debuglog("Perde 1 punto fascino coi mercenari del$luogo ");
                    }
                }

                else {
                    $session['user']['gold'] -= 50;
                    debuglog("Paga 50 monete d'oro ai mercenari del$luogo ");
                    output("`ndecidi di sottostare alla gabella che ti è stata imposta, assicurandoti così un ritorno alla foresta tranquillo. ");
                    output("`n`nPaghi quindi `^50 monete d'oro `2e auguri agli improvvisati esattori buona giornata, sperando di non reincontrarli ancora. ");
                }
            break;

            case 9:
                output("`n`n`2Non ritieni i loro servizi degni del pagamento di una delle tue preziose gemme, quindi rifiuti l'offerta e auguri ai mercenari una buona giornata continuando per la tua strada. ");
                output("`nCosì facendo però urti la suscettibilità di quegli audaci guerrieri i quali ritenendosi offesi decidono di impartirti una bella lezione a suon di randellate. ");
                output("`nTi ritrovi così un pò più pest$sesso e dolorante. `# Mai fare arrabbiare un mercenario ! ");
                if ($session['user']['hitpoints']>($session['user']['maxhitpoints']*.1)) {
                    $session['user']['hitpoints']-=round($session['user']['maxhitpoints']*.1,0);
                    output("`n`%Perdi un pò di punti ferita.`n");
                }
            break;

            case 10:
                output("`n`n`2Non ritieni i loro servizi degni del pagamento di una delle tue preziose gemme, quindi rifiuti l'offerta e auguri ai mercenari una buona giornata continuando per la tua strada. ");
                output("`nCosì facendo però urti la suscettibilità di quegli audaci guerrieri i quali ritenendosi offesi decidono di impartirti una bella lezione a suon di randellate. ");
                output("`nRiesci però a scappare fuggendo come una lepre e lasciando i mercenari con un palmo di naso.");
            break;
        }
        $session['user']['specialinc']="";
}


?>