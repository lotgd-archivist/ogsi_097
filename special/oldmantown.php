<?php
if (!isset($session)) exit();
if ($_GET['op']==""){
    $session['user']['specialinc'] = "oldmantown.php";
    output("`n`3Mentre stai esplorando una radura desolata della foresta alla ricerca di qualche mostruosa creatura ");
    output("da uccidere, intravedi un'ombra che si muove furtivamente tra gli alberi. Ti fermi immobile trattenendo ");
    output("il respiro, pronto a scattare in un attacco fulmineo contro l'essere, prima che questi ti sorprenda ");
    output("attaccandoti per primo. `nMa con tua grande sorpresa, da dietro il tronco di una secolare quercia, ");
    output("spunta solo un vecchietto dall'aria simpatica, che guardandosi intorno spaesato dice: `n`n");
    output("\"`@Mi sono perso, ".($session['user']['sex']?"nobile guerriera":"nobile guerriero").", potrebbe ");
    output("cortesemente accompagnarmi in città?`3\"`n`nTi rendi conto che compiere quel nobile gesto ti farà perdere ");
    output("tempo che potresti invece dedicare a nuove avventure, aiuterai quindi il povero vecchietto ? `n`n");
    addnav("Accompagnalo in città","forest.php?op=1");
    addnav("Lascialo solo nella foresta","forest.php?op=2");
}else{
    switch($_GET['op']) {
       case 1:
            $session['user']['turns']--;
            $caso = e_rand(1,100) ;
            if ($caso < 50 ){
                output("`n`3Hai deciso di aiutare il povero vecchietto, e lo accompagni in un viaggio tranquillo fino ");
                output("sotto le mura del villaggio.`nIn cambio del tempo che hai perso l'anziano personaggio come segno di ");
                output("gratitudine ti regala un'ampolla contenente una pozione invitandoti a berla subito senza paura.`n`n");
                output("`3La bevi e ti senti più attraente, hai infatti guadagnato `%un punto di Fascino !`3");
                $session['user']['charm']++;
                debuglog("guadagna 1 fascino accompagnando il vecchietto al villaggio");
            }elseif ($caso == 50 ){
                $oro_perso = intval(($session['user']['gold']*0.1));
                $session['user']['gold'] -= $oro_perso ;
                output("`n`3Hai deciso di aiutare il povero vecchietto, e ti appresti ad accompagnarlo sotto le mura ");
                output("del villaggio.`n`nAd un tratto, vieni colpito alle spalle, la vista ti si oscura e, precipitando ");
                output("nell'oblio, cadi al suolo tramortito.`nIl simpatico vecchietto si è rivelato un malfattore e ");
                output("approfittando della tua bontà d'animo ha colto l'occasione per derubarti dei tuoi averi.`n");
                output("Ti risvegli dopo pochi minuti e scopri che il ladruncolo rovistando rapidamente nelle tue ");
                output("tasche, è riuscito soltanto a trovare un piccolo portamonete ");
                if ($session['user']['gold'] == 0 OR $oro_perso == 0 ) {
                    output("che per tua fortuna era vuoto.`n`n ");
                    output("`%Non hai perso nulla salvo un turno nella foresta!");
                }else {
                    output("che per tua sfortuna conteneva comunque del danaro.`n`n ");
                    output("`^Hai perso `^$oro_perso monet".(($oro_perso)-1?"e":"a")." d'oro!");
                    debuglog("perde $oro_perso monete accompagnando il vecchietto al villaggio" );
                }
            }elseif ($caso == 51 ){
                $oro_guadagnato = intval (1600-($session['user']['level']*100));
                output("`n`3Hai deciso di aiutare il povero vecchietto, e lo accompagni in un viaggio tranquillo fino ");
                output("sotto le mura del villaggio.`n`nIn cambio del tempo che hai perso l'anziano personaggio come ");
                output("segno di gratitudine ti consegna un portamonete invitandoti ad esaminarne subito il contenuto.");
                output("`nLo apri e scopri che nel suo interno c'è una piccola fortuna, ti ha regalato ");
                output("`^$oro_guadagnato monete d'oro!`n");
                $session['user']['gold'] += $oro_guadagnato;
                debuglog("guadagna $oro_guadagnato oro accompagnando il vecchietto al villaggio" );
            }elseif ($caso > 51 ){
                output("`n`3Hai deciso di aiutare il povero vecchietto, e lo accompagni in un viaggio tranquillo fino ");
                output("sotto le mura della città.`n`n ");
                output("In cambio del tempo che hai perso l'anziano personaggio come segno di gratitudine ti ");
                output("consegna un sacchetto di pelle invitandoti ad esaminarne subito il contenuto. Lo apri e ");
                output("scopri che nel suo interno c'è una piccola sorpresa, ti ha regalato `&UNA GEMMA`3!!`n");
                $session['user']['gems']++;
                debuglog("Guadagna 1 gemma accompagnando il vecchietto al villaggio");
            }
       break;
       case 2:
            output("`n`3Dici al simpatico vecchietto che sei troppo indaffarato e che non hai tempo da perdere per ");
            output("poterlo accompagnare fino in città, tanto dovrebbe essere in grado di arrangiarsi da solo, fino ");
            output("a prova contraria se è riuscito ad arrivare sano e salvo fin nel mezzo della foresta ...`n");
            output("`3Abbandoni quindi l'ometto al suo destino e ti allontani rapidamente. Dopo qualche minuto odi ");
            output("l'ululato di un lupo, ma ormai è troppo tardi per ritornare indietro ... Con una scrollata di ");
            output("spalle fai finta di nulla e prosegui per la tua strada.`nDa lontano ti giunge il suono simile ");
            output("a quello di un gemito soffocato, e ti sembra anche di udire come in un sussurro: ");
            output("`7...... Che tu ..... sia ..... maledett".($session['user']['sex']?"a":"o")." ......`n");
            output("`3Ma sicuramente ti stai confondendo con il rumore del vento tra le fronde degli alberi.`n");
            $session['user']['evil'] += 5;
            debuglog("guadagna 5 punti cattiveria abbandonando al suo destino il vecchietto");
       break;
    }
}
?>