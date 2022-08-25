<?php
/*******************************************
Cambio carriera by Excalibur per www.ogsi.it
********************************************/
require_once("common.php");
require_once("common2.php");
page_header("Il Chiostro del Monastero");
$data = date("j");
$gemme = 10;
$oro = 10000;
$caca = $session['user']['cambio_carriera'];
$dio = $session['user']['dio'];
$carriera = $session['user']['carriera'];
$gemmeplayer = $session['user']['gems'];
$oroplayer = $session['user']['gold'];
$dio1=array(0=>"`9Agnostico",
            1=>"`6Sgrios",
            2=>"`\$Karnak",
            3=>"`@Drago Verde");

$prof=array(0=>"`5Disoccupato",
            1=>"`3Seguace",
            2=>"`3Accolito",
            3=>"`3Chierico",
            4=>"`3Sacerdote",
            9=>"`#Gran Sacerdote",
            5=>"`2Garzone",
            6=>"`2Apprendista",
            7=>"`2Fabbro",
            8=>"`@Mastro Fabbro",
            10=>"`4Invasato",
            11=>"`4Fanatico",
            12=>"`4Posseduto",
            13=>"`%Maestro delle Tenebre",
            15=>"`\$Falciatore di Anime",
            16=>"`(Portatore di Morte",
            17=>"`(Sommo Chierico",
            41=>"`6Iniziato`0",
            42=>"`6Stregone`0",
            43=>"`^Mago`0",
            44=>"`VArcimago`0",
            50=>"`8Stalliere dei Draghi`0",
            51=>"`8Scudiero dei Draghi`0",
            52=>"`8Cavaliere dei Draghi`0",
            53=>"`(Mastro dei Draghi`0",
            54=>"`(Dominatore di Draghi`0",
            55=>"`^Cancelliere dei Draghi`0",
            255=>"`^`bSupremo`b");

if ($data<"21" AND getsetting("puntisgriosfinemese",0)==0 AND getsetting("puntikarnakfinemese",0)==0 AND getsetting("puntidragofinemese",0)==0) {
    if ($_GET['op']=="") {
        output("`3`c`bAltare dei Sacrifici`b`c`n`n ");
        output("`3Mentre ti aggiri per il `2Monastero`3, ti imbatti in un altare di pietra, scavato in un ");
        output("blocco di roccia basaltica nei pressi di un grande albero della sorba. Ti avvicini, e noti ");
        output("un pertugio nella parte superiore. Questo è sicuramente un luogo speciale, e con un piccolo ");
        output("obolo hai la possibilità di cambiare la tua professione. `n`n");
        $sqlcasa = "SELECT fede FROM houses WHERE owner = ".$session['user']['acctid'];
        $resultcasa = db_query($sqlcasa) or die(db_error(LINK));
        $righe = db_num_rows($resultcasa);
        $rowcasa = db_fetch_assoc($resultcasa);
        //print("Righe lette nel DB: $righe  Fede casa:".$rowcasa['fede']);
        if ($righe == 0 OR $rowcasa['fede'] == 0){
            if ($dio == 0 AND $carriera == 0) {
                output("`2Un monaco si avvicina e ti dice \"`#Cosa ci fai qui? Non hai ancora scelto una professione ");
                output("e non hai necessità dei nostri servigi. Puoi tornare quando, dopo aver scelto una carriera, ed ");
                output("essendoti reso conto di aver sbagliato, vorrai cambiare indirizzo.`2\"`n`n");
                addnav("Abbandona");
                addnav("Torna al Monastero","monastero.php");
            }else if ($carriera >=5 AND $carriera <= 8 AND $dio == 0) {
                if ($session['user']['cambio_carriera']){
                    output("`2Un monaco si avvicina e ti dice con fare comprensivo \"`#Caro fratello fabbro, comprendo che la ");
                    output("professione che hai scelto sia faticosa, ma per cambiarla dovrai versare `&".$gemme*($caca + 1)." gemme`2 e `^".$oro*($caca + 1)." pezzi d'oro`2.`n");
                    output("Sappi inoltre che perderai tutti i punti carriera che hai guadagnato con la tua attuale professione.`2\"`n");
                    output("A questo punto l'affabile monaco ti guarda con fare interrogativo attendendo una tua decisione.`n");
                    addnav("`@Si voglio cambiare","moncambiocarriera.php?op=cambiopaga");
                    addnav("`\$No ci voglio pensare","monastero.php");
                }else{
                    output("`2Un monaco si avvicina e ti dice con fare comprensivo \"`#Caro fratello fabbro, comprendo che la ");
                    output("professione che hai scelto sia faticosa, e poichè è la prima volta che ti rivolgi a noi per cambiare ");
                    output("carriera per questa volta sarà gratuita.`n");
                    output("Sappi comunque che perderai tutti i punti carriera che hai guadagnato con la tua attuale professione.`2\"`n");
                    output("A questo punto l'affabile monaco ti guarda con fare interrogativo attendendo una tua decisione.`n");
                    addnav("`@Si voglio cambiare","moncambiocarriera.php?op=cambiogratis");
                    addnav("`\$No ci voglio pensare","monastero.php");
                }
            }else if ($carriera >=5 AND $carriera <= 8 AND $dio != 0) {
                if ($session['user']['cambio_carriera']){
                    output("`2Un monaco si avvicina e ti dice con fare comprensivo \"`#Caro fratello fabbro, vedo che professi ");
                    output("anche una fede. Vuoi cambiare solo la tua fede, cambiare carriera o entrambe le cose ?`n");
                    output("Cambiare ti costerà `&".$gemme*($caca + 1)." gemme`2 e `^".$oro*($caca + 1)." pezzi d'oro`2.");
                    addnav("`@Solo la fede","moncambiocarriera.php?op=solofedepaga");
                    addnav("`@Solo la carriera","moncambiocarriera.php?op=solocarrpaga");
                    addnav("`#Azzera tutto","moncambiocarriera.php?op=cambiopaga");
                    addnav("`\$No ci voglio pensare","monastero.php");
                }else{
                    output("`2Un monaco si avvicina e ti dice con fare comprensivo \"`#Caro fratello fabbro, vedo che professi ");
                    output("anche una fede. Vuoi cambiare solo la tua fede, cambiare carriera o entrambe le cose ?`n");
                    output("Essendo la prima volta che ti rivolgi a noi il cambio sarà gratuito.`n");
                    addnav("`@Solo la fede","moncambiocarriera.php?op=solofedegratis");
                    addnav("`@Solo la carriera","moncambiocarriera.php?op=solocarrgratis");
                    addnav("`#Azzera tutto","moncambiocarriera.php?op=cambiogratis");
                    addnav("`\$No ci voglio pensare","monastero.php");
                }
            }else if ($carriera >=41 AND $carriera <= 44 AND $dio == 0) {
                if ($session['user']['cambio_carriera']){
                    output("`2Un monaco si avvicina e ti dice con fare comprensivo \"`#Caro fratello mago, comprendo che la ");
                    output("professione che hai scelto sia irta di sacrifici, ma per cambiarla dovrai versare `&".$gemme*($caca + 1)." gemme`2 e `^".$oro*($caca + 1)." pezzi d'oro`2.`n");
                    output("Sappi inoltre che perderai tutti i punti carriera che hai guadagnato con la tua attuale professione.`2\"`n");
                    output("A questo punto l'affabile monaco ti guarda con fare interrogativo attendendo una tua decisione.`n");
                    addnav("`@Si voglio cambiare","moncambiocarriera.php?op=cambiopaga");
                    addnav("`\$No ci voglio pensare","monastero.php");
                }else{
                    output("`2Un monaco si avvicina e ti dice con fare comprensivo \"`#Caro fratello mago, comprendo che la ");
                    output("professione che hai scelto sia irta di sacrifici, e poichè è la prima volta che ti rivolgi a noi per cambiare ");
                    output("carriera per questa volta sarà gratuita.`n");
                    output("Sappi comunque che perderai tutti i punti carriera che hai guadagnato con la tua attuale professione.`2\"`n");
                    output("A questo punto l'affabile monaco ti guarda con fare interrogativo attendendo una tua decisione.`n");
                    addnav("`@Si voglio cambiare","moncambiocarriera.php?op=cambiogratis");
                    addnav("`\$No ci voglio pensare","monastero.php");
                }
            }else if ($carriera >=41 AND $carriera <= 44 AND $dio != 0) {
                if ($session['user']['cambio_carriera']){
                    output("`2Un monaco si avvicina e ti dice con fare comprensivo \"`#Caro fratello mago, vedo che professi ");
                    output("anche una fede. Vuoi cambiare solo la tua fede, cambiare carriera o entrambe le cose ?`n");
                    output("Cambiare ti costerà `&".$gemme*($caca + 1)." gemme`2 e `^".$oro*($caca + 1)." pezzi d'oro`2.");
                    addnav("`@Solo la fede","moncambiocarriera.php?op=solofedepaga");
                    addnav("`@Solo la carriera","moncambiocarriera.php?op=solocarrpaga");
                    addnav("`#Azzera tutto","moncambiocarriera.php?op=cambiopaga");
                    addnav("`\$No ci voglio pensare","monastero.php");
                }else{
                    output("`2Un monaco si avvicina e ti dice con fare comprensivo \"`#Caro fratello mago, vedo che professi ");
                    output("anche una fede. Vuoi cambiare solo la tua fede, cambiare carriera o entrambe le cose ?`n");
                    output("Essendo la prima volta che ti rivolgi a noi il cambio sarà gratuito.`n");
                    addnav("`@Solo la fede","moncambiocarriera.php?op=solofedegratis");
                    addnav("`@Solo la carriera","moncambiocarriera.php?op=solocarrgratis");
                    addnav("`#Azzera tutto","moncambiocarriera.php?op=cambiogratis");
                    addnav("`\$No ci voglio pensare","monastero.php");
                }
            }else if ($dio == 1 AND (($carriera >= -1 AND $carriera < 5) OR $carriera == 9 OR $carriera = 17)) {
                if ($session['user']['cambio_carriera']){
                    output("`2Un monaco si avvicina e ti dice con fare comprensivo \"`#Caro figlio di `6Sgrios`2, comprendo che la ");
                    output("professione che hai scelto sia impegnativa, ma per cambiarla dovrai versare `&".$gemme*($caca + 1)." gemme `2e `^".$oro*($caca + 1)." pezzi d'oro`2.`n");
                    output("Sappi inoltre che perderai tutti i punti carriera che hai guadagnato con la tua attuale professione.`2\"`n");
                    output("A questo punto l'affabile monaco ti guarda con fare interrogativo attendendo una tua decisione.`n");
                    addnav("`@Si voglio cambiare","moncambiocarriera.php?op=cambiopaga");
                    addnav("`^Voglio mantenere la fede","moncambiocarriera.php?op=solocarrpaga");
                    addnav("`\$No ci voglio pensare","monastero.php");
                }else{
                    output("`2Un monaco si avvicina e ti dice con fare comprensivo \"`#Caro figlio di `6Sgrios`#, comprendo che la ");
                    output("professione che hai scelto sia impegnativa, e poichè è la prima volta che ti rivolgi a noi per cambiare ");
                    output("carriera per questa volta sarà gratuita.`n");
                    output("Sappi comunque che perderai tutti i punti carriera che hai guadagnato con la tua attuale professione.`2\"`n");
                    output("A questo punto l'affabile monaco ti guarda con fare interrogativo attendendo una tua decisione.`n");
                    addnav("`@Si voglio cambiare","moncambiocarriera.php?op=cambiogratis");
                    addnav("`^Voglio mantenere la fede","moncambiocarriera.php?op=solocarrgratis");
                    addnav("`\$No ci voglio pensare","monastero.php");
                }
            }else if (($dio == 3 AND $carriera >= 50 AND $carriera < 56) OR ($dio == 3 AND $carriera==0)) {
                if ($session['user']['cambio_carriera']){
                    output("`2Un monaco si avvicina e ti dice con fare comprensivo:`n`n\"`#Caro seguace del `@Drago Verde`#, comprendo che la ");
                    output("professione che hai scelto sia impegnativa, ma per cambiarla dovrai versare `&".$gemme*($caca + 1)." gemme `#e `^".$oro*($caca + 1)." pezzi d'oro`#.`n");
                    output("Sappi inoltre che perderai tutti i punti carriera che hai guadagnato con la tua attuale professione.`2\"`n`n");
                    output("A questo punto l'affabile monaco ti guarda con fare interrogativo attendendo una tua decisione.`n");
                    addnav("`@Si voglio cambiare","moncambiocarriera.php?op=cambiopaga");
                    addnav("`^Voglio mantenere la fede","moncambiocarriera.php?op=solocarrpaga");
                    addnav("`\$No ci voglio pensare","monastero.php");
                }else{
                    output("`2Un monaco si avvicina e ti dice con fare comprensivo \"`#Caro seguace del `@Drago Verde`#, comprendo che la ");
                    output("professione che hai scelto sia impegnativa, e poichè è la prima volta che ti rivolgi a noi per cambiare ");
                    output("carriera per questa volta sarà gratuita.`n");
                    output("Sappi comunque che perderai tutti i punti carriera che hai guadagnato con la tua attuale professione.`2\"`n");
                    output("A questo punto l'affabile monaco ti guarda con fare interrogativo attendendo una tua decisione.`n");
                    addnav("`@Si voglio cambiare","moncambiocarriera.php?op=cambiogratis");
                    addnav("`^Voglio mantenere la fede","moncambiocarriera.php?op=solocarrgratis");
                    addnav("`\$No ci voglio pensare","monastero.php");
                }
            }else if (($dio == 2 AND $carriera >= 10 AND $carriera < 17) OR ($dio == 2 AND $carriera==0)) {
                if ($session['user']['cambio_carriera']){
                    output("`2Un monaco si avvicina e ti dice con fare comprensivo:`n`n\"`#Malefico seguace di `\$Karnak`#, ti sei finalmente ");
                    output("ravveduto e hai deciso di cambiare radicalmente la tua vita. Ma per farlo dovrai versare `&");
                    output($gemme*($caca + 1)." gemme `#e `^".$oro*($caca + 1)." pezzi d'oro`#.`n");
                    output("Sappi inoltre che perderai tutti i punti carriera che hai guadagnato con la tua attuale professione.`2\"`n");
                    output("A questo punto l'affabile monaco ti guarda con fare interrogativo attendendo una tua decisione.`n");
                    addnav("`@Si voglio cambiare","moncambiocarriera.php?op=cambiopaga");
                    addnav("`^Voglio mantenere la fede","moncambiocarriera.php?op=solocarrpaga");
                    addnav("`\$No ci voglio pensare","monastero.php");
                }else{
                    output("`2Un monaco si avvicina e ti dice con fare comprensivo:`n`n\"`#Malefico seguace di `\$Karnak`#, ti sei finalmente ");
                    output("ravveduto e hai deciso di cambiare radicalmente la tua vita, e poichè è la prima volta che ti rivolgi a noi per cambiare ");
                    output("carriera per questa volta sarà gratuita.`n");
                    output("Sappi comunque che perderai tutti i punti carriera che hai guadagnato con la tua attuale professione.`2\"`n");
                    output("A questo punto l'affabile monaco ti guarda con fare interrogativo attendendo una tua decisione.`n");
                    addnav("`@Si voglio cambiare","moncambiocarriera.php?op=cambiogratis");
                    addnav("`^Voglio mantenere la fede","moncambiocarriera.php?op=solocarrgratis");
                    addnav("`\$No ci voglio pensare","monastero.php");
                }
            }else if ($dio == 1 AND $carriera = 0) {
                if ($session['user']['cambio_carriera']){
                    output("`2Un monaco si avvicina e ti dice con fare comprensivo \"`#Caro figlio di `6Sgrios`2, comprendo che la ");
                    output("fede che hai scelto sia impegnativa, ma per cambiarla dovrai versare `&".$gemme*($caca + 1)." gemme `2e `^".$oro*($caca + 1)." pezzi d'oro.`2\"`n");
                    output("A questo punto l'affabile monaco ti guarda con fare interrogativo attendendo una tua decisione.`n");
                    addnav("`@Si voglio cambiare","moncambiocarriera.php?op=cambiopaga");
                    addnav("`\$No ci voglio pensare","monastero.php");
                }else{
                    output("`2Un monaco si avvicina e ti dice con fare comprensivo \"`#Caro figlio di `6Sgrios`#, comprendo che la ");
                    output("fede che hai scelto sia impegnativa, e poichè è la prima volta che ti rivolgi a noi per cambiare ");
                    output("carriera per questa volta sarà gratuita.`2\"n");
                    output("A questo punto l'affabile monaco ti guarda con fare interrogativo attendendo una tua decisione.`n");
                    addnav("`@Si voglio cambiare","moncambiocarriera.php?op=cambiogratis");
                    addnav("`\$No ci voglio pensare","monastero.php");
                }
            }else if ($dio == 3 AND $carriera==0) {
                if ($session['user']['cambio_carriera']){
                    output("`2Un monaco si avvicina e ti dice con fare comprensivo:`n`n\"`#Caro seguace del `@Drago Verde`#, comprendo che la ");
                    output("fede che hai scelto sia impegnativa, ma per cambiarla dovrai versare `&".$gemme*($caca + 1)." gemme `#e `^".$oro*($caca + 1)." pezzi d'oro`#.`2\"n");
                    output("A questo punto l'affabile monaco ti guarda con fare interrogativo attendendo una tua decisione.`n");
                    addnav("`@Si voglio cambiare","moncambiocarriera.php?op=cambiopaga");
                    addnav("`\$No ci voglio pensare","monastero.php");
                }else{
                    output("`2Un monaco si avvicina e ti dice con fare comprensivo \"`#Caro seguace del `@Drago Verde`#, comprendo che la ");
                    output("fede che hai scelto sia impegnativa, e poichè è la prima volta che ti rivolgi a noi per cambiare ");
                    output("carriera per questa volta sarà gratuita.`2\"n");
                    output("A questo punto l'affabile monaco ti guarda con fare interrogativo attendendo una tua decisione.`n");
                    addnav("`@Si voglio cambiare","moncambiocarriera.php?op=cambiogratis");
                    addnav("`\$No ci voglio pensare","monastero.php");
                }
            }else if ($dio == 2 AND $carriera==0) {
                if ($session['user']['cambio_carriera']){
                    output("`2Un monaco si avvicina e ti dice con fare comprensivo:`n`n\"`#Malefico seguace di `\$Karnak`#, ti sei finalmente ");
                    output("ravveduto e hai deciso di cambiare radicalmente la tua vita. Ma per farlo dovrai versare `&");
                    output($gemme*($caca + 1)." gemme `#e `^".$oro*($caca + 1)." pezzi d'oro`#.`2\"n");
                    output("A questo punto l'affabile monaco ti guarda con fare interrogativo attendendo una tua decisione.`n");
                    addnav("`@Si voglio cambiare","moncambiocarriera.php?op=cambiopaga");
                    addnav("`\$No ci voglio pensare","monastero.php");
                }else{
                    output("`2Un monaco si avvicina e ti dice con fare comprensivo:`n`n\"`#Malefico seguace di `\$Karnak`#, ti sei finalmente ");
                    output("ravveduto e hai deciso di cambiare radicalmente la tua vita, e poichè è la prima volta che ti rivolgi a noi per cambiare ");
                    output("carriera per questa volta sarà gratuita.`2\"n");
                    output("A questo punto l'affabile monaco ti guarda con fare interrogativo attendendo una tua decisione.`n");
                    addnav("`@Si voglio cambiare","moncambiocarriera.php?op=cambiogratis");
                    addnav("`\$No ci voglio pensare","monastero.php");
                }
            }else {
                output("`\$ERRORE! Segnala la cosa agli admin");
                addnav("Torna al monastero","monastero.php");
            }
        }else {
            output("`\$Sei proprietario di una tenuta schierata in favore di ".$dio1[$rowcasa['fede']]."`\$, per cui ");
            output("non ti è permesso di cambiare fede.`n`3Puoi solo cambiare carriera, con la relativa perdita di `bTUTTI`b ");
            output("i tuoi punti carriera. `nVuoi proseguire ?`n");
            addnav("Si, Azzera Carriera","moncambiocarriera.php?op=cambcarr");
            addnav("No, Torna al Monastero","monastero.php");
        }
    }else if ($_GET['op']=="cambcarr") {
        if ($session['user']['cambio_carriera']){
            output("`2Un monaco si avvicina e ti dice con fare comprensivo:`n`n\"`#Hai deciso di cambiare carriera e ti ");
            output("sei rivolto alla persona giusta. Ma per farlo dovrai versare `&");
            output($gemme*($caca + 1)." gemme `#e `^".$oro*($caca + 1)." pezzi d'oro`#.`n");
            output("La tua fede rimarrà inalterata ma i tuoi punti carriera saranno azzerrati.`2\"`n");
            output("A questo punto l'affabile monaco ti guarda con fare interrogativo attendendo una tua decisione.`n");
            addnav("`@Si voglio cambiare","moncambiocarriera.php?op=cambcarrpaga");
            addnav("`\$No ci voglio pensare","monastero.php");
        }else{
            output("`2Un monaco si avvicina e ti dice con fare comprensivo:`n`n\"`#Hai deciso di cambiare carriera e ti ");
            output("sei rivolto alla persona giusta, e poichè è la prima volta che vuoi cambiare ");
            output("carriera per questa volta sarà gratuita.`n");
            output("Sappi comunque che perderai tutti i punti carriera che hai guadagnato con la tua attuale professione.`2\"`n");
            output("A questo punto l'affabile monaco ti guarda con fare interrogativo attendendo una tua decisione.`n");
            addnav("`@Si voglio cambiare","moncambiocarriera.php?op=cambcarrgratis");
            addnav("`\$No ci voglio pensare","monastero.php");
        }
    }else if ($_GET['op']=="cambcarrgratis") {
        output("`2A seguito della tua conferma il monaco estrae un pergamena dal foro presente sulla parte superiore ");
        output("dell'altare ed inizia a leggerla con voce ferma in quella che è una lingua a te sconosciuta:`n");
        output("\"`^Teân Soget ñaõ trôû neân ñoàng nghóa vôùi dòch thuaät ñaït trình ñoä xuaát saéc nhaát`2\"`n");
        output("Terminata la melodiosa cantilena ti guarda e vedendo la tua espressione interrogativa dice`n");
        output("\"`#Puoi andare, la tua carriera è stata azzerata, puoi iniziare una nuova vita adesso`2\" `ne ti ");
        output("accompagna all'ingresso del monastero.`n");
        debuglog("cambia solo carriera (era ".$session['user']['carriera'].") gratuitamente");
        //rimozione del record punti riparazione, Sook
        if ($session['user']['carriera'] > 4 AND $session['user']['carriera'] < 9) {
            $sqldel = "DELETE FROM riparazioni WHERE acctid = '".$session['user']['acctid']."'";
            db_query($sqldel);
        }
        //rimozione del record quote dei maghi, Sook
        if ($session['user']['carriera'] > 41 AND $session['user']['carriera'] < 45) {
            $sqldel = "DELETE FROM rigenerazioni WHERE acctid = '".$session['user']['acctid']."'";
            db_query($sqldel);
        }
        report(3,"`%Cambio Carriera!`0","`\$".$session['user']['name']." ha cambiato carriera gratis per la ".($session['user']['cambio_carriera']+1)."° volta. Carriera precedente: ".$prof[$session['user']['carriera']],"cambio_fede_carriera");
        $session['user']['carriera'] = 0;
        $session['user']['punti_carriera'] = 0;
        //$session['user']['punti_generati'] = -5000;
        $session['user']['cambio_carriera'] += 1;
        addnav("Monastero","monastero.php");
    }else if ($_GET['op']=="cambcarrpaga") {
        if ($oroplayer >= ($oro*($caca + 1)) AND $gemmeplayer >= ($gemme*($caca + 1)) ) {
            output("`2A seguito della tua conferma il monaco ti chiede di versare nel foro dell'altare quanto ");
            output("richiesto, e quindi estrae un pergamena da una tasca interna del saio che indossa ");
            output("ed inizia a leggerla con voce ferma in quella che è una lingua a te sconosciuta:`n");
            output("\"`^Teân Soget ñaõ trôû neân ñoàng nghóa vôùi dòch thuaät ñaït trình ñoä xuaát saéc nhaát`2\"`n");
            output("Terminata la melodiosa cantilena ti guarda e vedendo la tua espressione interrogativa dice:`n");
            output("\"`#Puoi andare, la tua carriera è stata azzerata, puoi iniziare una nuova vita adesso`2\"`ne ti ");
            output("accompagna all'ingresso del monastero.`n");
            debuglog("cambia solo carriera (era ".$session['user']['dio'].") pagando.");
            //rimozione del record punti riparazione, Sook
            if ($session['user']['carriera'] > 4 AND $session['user']['carriera'] < 9) {
                $sqldel = "DELETE FROM riparazioni WHERE acctid = '".$session['user']['acctid']."'";
                db_query($sqldel);
            }
              //rimozione del record quote dei maghi, Sook
              if ($session['user']['carriera'] > 41 AND $session['user']['carriera'] < 45) {
                 $sqldel = "DELETE FROM rigenerazioni WHERE acctid = '".$session['user']['acctid']."'";
                 db_query($sqldel);
            }
            $session['user']['gold'] -= $oro*($caca + 1);
            $session['user']['gems'] -= $gemme*($caca + 1);
            report(3,"`%Cambio Carriera!`0","`\$".$session['user']['name']." ha cambiato carriera pagando per la ".($session['user']['cambio_carriera']+1)."° volta. Carriera precedente: ".$prof[$session['user']['carriera']],"cambio_fede_carriera");
            $session['user']['carriera'] = 0;
            $session['user']['punti_carriera'] = 0;
            //$session['user']['punti_generati'] = -5000;
            $session['user']['cambio_carriera'] += 1;
            addnav("Monastero","monastero.php");
        }else {
            output("`2\"`#Non hai `&gemme`2 o `^oro`2 a sufficienza per pagare il processo che ti permette di azzerare la carriera ");
            output("che avevi scelto precedentemente. Procurati `&".$gemme*($caca + 1)." gemme `2e `^".$oro*($caca + 1)." pezzi d'oro`2 ");
            output("prima di tornare qui al monastero.`2\"`n Detto ciò il monaco si congeda e si avvia verso il chiostro.`n");
            addnav("Monastero","monastero.php");
        }
    }else if ($_GET['op']=="solofedegratis") {
        output("`2Il paziente monaco ha ascoltato attentamente la tua decisione, ma prima di procedere con l'azzeramento ");
        output("della tua carriera ti chiede \"`#Il procedimento dell'azzeramento non è reversibile, ed è mio dovere informarti ");
        output("che una volta effettuato non sarà possibile ripristinare la situazione precedente`2\". Detto questo rimane ");
        output("in attesa di una conferma definitiva prima di procedere, ricordandoti che attualmente sei un {$prof[$session['user']['carriera']]} `2e hai `^".$session['user']['punti_carriera']." `2punti carriera, ");
        output("e che sei simpatizzante di ".$dio1[$dio].".`n");
        addnav("`@Si, sono convinto","moncambiocarriera.php?op=solofedegratissicuro");
        addnav("`\$No, non sono sicuro al 100%","monastero.php");
    }else if ($_GET['op']=="solocarrgratis") {
        output("`2Il paziente monaco ha ascoltato attentamente la tua decisione, ma prima di procedere con l'azzeramento ");
        output("della tua carriera ti chiede \"`#Il procedimento dell'azzeramento non è reversibile, ed è mio dovere informarti ");
        output("che una volta effettuato non sarà possibile ripristinare la situazione precedente`2\". Detto questo rimane ");
        output("in attesa di una conferma definitiva prima di procedere, ricordandoti che attualmente sei un {$prof[$session['user']['carriera']]} `2e hai `^".$session['user']['punti_carriera']." `2punti carriera, ");
        output("e che sei simpatizzante di ".$dio1[$dio].".`n");
        addnav("`@Si, sono convinto","moncambiocarriera.php?op=cambcarrgratis");
        addnav("`\$No, non sono sicuro al 100%","monastero.php");
    }else if ($_GET['op']=="solocarrpaga") {
        output("`2Il paziente monaco ha ascoltato attentamente la tua decisione, ma prima di procedere con l'azzeramento ");
        output("della tua carriera ti chiede \"`#Il procedimento dell'azzeramento non è reversibile, ed è mio dovere informarti ");
        output("che una volta effettuato non sarà possibile ripristinare la situazione precedente`2\". Detto questo rimane ");
        output("in attesa di una conferma definitiva prima di procedere, ricordandoti che attualmente sei un {$prof[$session['user']['carriera']]} `2e hai `^".$session['user']['punti_carriera']." `2punti carriera, ");
        output("e che sei simpatizzante di ".$dio1[$dio].".`n");
        addnav("`@Si, sono convinto","moncambiocarriera.php?op=cambcarrpaga");
        addnav("`\$No, non sono sicuro al 100%","monastero.php");
    }else if ($_GET['op']=="solofedegratissicuro") {
        output("`2A seguito della tua seconda conferma il monaco estrae un pergamena dal foro presente sulla parte superiore ");
        output("dell'altare ed inizia a leggerla con voce ferma in quella che è una lingua a te sconosciuta:`n");
        output("\"`^Teân Soget ñaõ trôû neân ñoàng nghóa vôùi dòch thuaät ñaït trình ñoä xuaát saéc nhaát`2\"`n");
        output("Terminata la melodiosa cantilena ti guarda e vedendo la tua espressione interrogativa dice`n");
        output("\"`#Puoi andare, la tua fede è stata azzerata, puoi iniziare una nuova vita adesso`2\" `ne ti ");
        output("accompagna all'ingresso del monastero.`n");
        debuglog("cambia solo fede (era ".$session['user']['dio'].") gratuitamente");
        report(3,"`%Cambio Fede!`0","`\$".$session['user']['name']." ha cambiato fede gratis per la ".($session['user']['cambio_carriera']+1)."° volta. Fede precedente: ".$dio1[$session['user']['dio']],"cambio_fede_carriera");
        $session['user']['dio'] = 0;
        //$session['user']['carriera'] = 0;
        //$session['user']['punti_carriera'] = 0;
        $session['user']['punti_generati'] = -5000;
        $session['user']['cambio_carriera'] += 1;
        addnav("Monastero","monastero.php");
    }else if ($_GET['op']=="solofedepaga") {
        //$dio1 = $dio - 1;
        output("`2Il paziente monaco ha ascoltato attentamente la tua decisione, ma prima di procedere con l'azzeramento ");
        output("della tua carriera ti chiede \"`#Il procedimento dell'azzeramento non è reversibile, ed è mio dovere informarti ");
        output("che una volta effettuato non sarà possibile ripristinare la situazione precedente`2\". Detto questo rimane ");
        output("in attesa di una conferma definitiva prima di procedere, ricordandoti che attualmente sei un {$prof[$session['user']['carriera']]} `2e hai `^".$session['user']['punti_carriera']." `2punti carriera, ");
        output("e che sei simpatizzante di ".$dio1[$dio]."`2.`n");
        addnav("`@Si, sono convinto","moncambiocarriera.php?op=solofedepagasicuro");
        addnav("`\$No, non sono sicuro al 100%","monastero.php");
    }else if ($_GET['op']=="solofedepagasicuro") {
        if ($oroplayer >= ($oro*($caca + 1)) AND $gemmeplayer >= ($gemme*($caca + 1)) ) {
            output("`2A seguito della tua seconda conferma il monaco ti chiede di versare nel foro dell'altare quanto ");
            output("richiesto, e quindi estrae un pergamena da una tasca interna del saio che indossa ");
            output("ed inizia a leggerla con voce ferma in quella che è una lingua a te sconosciuta:`n");
            output("\"`^Teân Soget ñaõ trôû neân ñoàng nghóa vôùi dòch thuaät ñaït trình ñoä xuaát saéc nhaát`2\"`n");
            output("Terminata la melodiosa cantilena ti guarda e vedendo la tua espressione interrogativa dice:`n");
            output("\"`#Puoi andare, la tua fede è stata azzerata, puoi iniziare una nuova vita adesso`2\"`ne ti ");
            output("accompagna all'ingresso del monastero.`n");
            debuglog("cambia solo fede (era ".$session['user']['dio'].") pagando.");
            $session['user']['gold'] -= $oro*($caca + 1);
            $session['user']['gems'] -= $gemme*($caca + 1);
            report(3,"`%Cambio Fede!`0","`\$".$session['user']['name']." ha cambiato fede pagando per la ".($session['user']['cambio_carriera']+1)."° volta. Fede precedente: ".$dio1[$session['user']['dio']],"cambio_fede_carriera");
            $session['user']['dio'] = 0;
            //$session['user']['carriera'] = 0;
            //$session['user']['punti_carriera'] = 0;
            $session['user']['punti_generati'] = -5000;
            $session['user']['cambio_carriera'] += 1;
            addnav("Monastero","monastero.php");
            }else{
                output("`2\"`#Non hai `&gemme`2 o `^oro`2 a sufficienza per pagare il processo che ti permette di azzerare la carriera ");
                output("che avevi scelto precedentemente. Procurati `&".$gemme*($caca + 1)." gemme `2e `^".$oro*($caca + 1)." pezzi d'oro`2 ");
                output("prima di tornare qui al monastero.`2\"`n Detto ciò il monaco si congeda e si avvia verso il chiostro.`n");
                addnav("Monastero","monastero.php");
            }
        }else if ($_GET['op']=="cambiogratis") {
            output("`2Il paziente monaco ha ascoltato attentamente la tua decisione, ma prima di procedere con l'azzeramento ");
            output("della tua carriera ti chiede \"`#Il procedimento dell'azzeramento non è reversibile, ed è mio dovere informarti ");
            output("che una volta effettuato non sarà possibile ripristinare la situazione precedente`2\". Detto questo rimane ");
            output("in attesa di una conferma definitiva prima di procedere.`n");
            addnav("`%Sbrigati !!!","moncambiocarriera.php?op=prepotente");
            addnav("`@Si, sono convinto","moncambiocarriera.php?op=gratissicuro");
            addnav("`\$No, non sono sicuro al 100%","monastero.php");
        }else if ($_GET['op']=="cambiopaga") {
            output("`2Il paziente monaco ha ascoltato attentamente la tua decisione, ma prima di procedere con l'azzeramento ");
            output("della tua carriera ti chiede \"`#Il procedimento dell'azzeramento non è reversibile, ed è mio dovere informarti ");
            output("che una volta effettuato non sarà possibile ripristinare la situazione precedente`2\". Detto questo rimane ");
            output("in attesa di una conferma definitiva prima di procedere, ricordandoti che attualmente sei un ".$prof[$session['user']['carriera']]." `2e hai `^".$session['user']['punti_carriera']." `2punti carriera.`n");
            addnav("`@Si, sono convinto","moncambiocarriera.php?op=pagasicuro");
            addnav("`\$No, non sono sicuro al 100%","monastero.php");
        }else if ($_GET['op']=="gratissicuro") {
            output("`2A seguito della tua seconda conferma il monaco estrae un pergamena dal foro presente sulla parte superiore ");
            output("dell'altare ed inizia a leggerla con voce ferma in quella che è una lingua a te sconosciuta:`n");
            output("\"`^Teân Soget ñaõ trôû neân ñoàng nghóa vôùi dòch thuaät ñaït trình ñoä xuaát saéc nhaát`2\"`n");
            output("Terminata la melodiosa cantilena ti guarda e vedendo la tua espressione interrogativa dice`n");
            output("\"`#Puoi andare, la tua carriera è stata azzerata, puoi iniziare una nuova vita adesso`2\" `ne ti ");
            output("accompagna all'ingresso del monastero.`n");
            debuglog("cambia carriera/fede gratuitamente");
            //rimozione del record punti riparazione, Sook
            if ($session['user']['carriera'] > 4 AND $session['user']['carriera'] < 9) {
            $sqldel = "DELETE FROM riparazioni WHERE acctid = '".$session['user']['acctid']."'";
            db_query($sqldel);
        }
        //rimozione del record quote dei maghi, Sook
        if ($session['user']['carriera'] > 41 AND $session['user']['carriera'] < 45) {
            $sqldel = "DELETE FROM rigenerazioni WHERE acctid = '".$session['user']['acctid']."'";
            db_query($sqldel);
        }
        report(3,"`%Cambio fede e carriera","`\$".$session['user']['name']." ha cambiato fede e carriera gratis per la ".($session['user']['cambio_carriera']+1)."° volta. Fede precedente: ".$dio1[$session['user']['dio']]." Carriera precedente: ".$prof[$session['user']['carriera']],"cambio_fede_carriera");
        $session['user']['dio'] = 0;
        $session['user']['carriera'] = 0;
        $session['user']['punti_carriera'] = 0;
        $session['user']['punti_generati'] = -5000;
        $session['user']['cambio_carriera'] += 1;
        addnav("Monastero","monastero.php");
    }else if ($_GET['op']=="pagasicuro") {
        if ($oroplayer >= ($oro*($caca + 1)) AND $gemmeplayer >= ($gemme*($caca + 1)) ) {
            output("`2A seguito della tua seconda conferma il monaco ti chiede di versare nel foro dell'altare quanto ");
            output("richiesto, e quindi estrae un pergamena da una tasca interna del saio che indossa ");
            output("ed inizia a leggerla con voce ferma in quella che è una lingua a te sconosciuta:`n");
            output("\"`^Teân Soget ñaõ trôû neân ñoàng nghóa vôùi dòch thuaät ñaït trình ñoä xuaát saéc nhaát`2\"`n");
            output("Terminata la melodiosa cantilena ti guarda e vedendo la tua espressione interrogativa dice`n");
            output("\"`#Puoi andare, la tua carriera è stata azzerata, puoi iniziare una nuova vita adesso`2\"`ne ti ");
            output("accompagna all'ingresso del monastero.`n");
            debuglog("cambia carriera/fede pagando");
        //rimozione del record punti riparazione, Sook
        if ($session['user']['carriera'] > 4 AND $session['user']['carriera'] < 9) {
            $sqldel = "DELETE FROM riparazioni WHERE acctid = '".$session['user']['acctid']."'";
            db_query($sqldel);
        }
        //rimozione del record quote dei maghi, Sook
        if ($session['user']['carriera'] > 41 AND $session['user']['carriera'] < 45) {
            $sqldel = "DELETE FROM rigenerazioni WHERE acctid = '".$session['user']['acctid']."'";
            db_query($sqldel);
        }
            $session['user']['gold'] -= $oro*($caca + 1);
            $session['user']['gems'] -= $gemme*($caca + 1);
            report(3,"`%Cambio fede e carriera","`\$".$session['user']['name']." ha cambiato fede e carriera pagando per la ".($session['user']['cambio_carriera']+1)."° volta. Fede precedente: ".$dio1[$session['user']['dio']]." Carriera precedente: ".$prof[$session['user']['carriera']],"cambio_fede_carriera");
            $session['user']['dio'] = 0;
            $session['user']['carriera'] = 0;
            $session['user']['punti_carriera'] = 0;
            $session['user']['punti_generati'] = -5000;
            $session['user']['cambio_carriera'] += 1;
            addnav("Monastero","monastero.php");
        }else {
            output("`2\"`#Non hai `&gemme`2 o `^oro`2 a sufficienza per pagare il processo che ti permette di azzerare la carriera ");
            output("che avevi scelto precedentemente. Procurati `&".$gemme*($caca + 1)." gemme `2e `^".$oro*($caca + 1)." pezzi d'oro`2 ");
            output("prima di tornare qui al monastero.`2\"`n Detto ciò il monaco si congeda e si avvia verso il chiostro.`n");
            addnav("Monastero","monastero.php");
        }
    }else if ($_GET['op']=="prepotente") {
        if ($oroplayer >= $oro) {
            $oroperso = $oro;
        } else {
            $oroperso = $oroplayer;
        }
        if ($gemmeplayer >= $gemme) {
            $gemmeperso = $gemme;
        } else {
            $gemmeperso = $gemmeplayer;
        }
        output("`2\"`#La tua arroganza non deve restare impunita. Se ti fossi comportato civilmente avresti ottenuto ");
        output("l'azzeramento della tua precedente carriera gratuitamente, ma sei stato prepotente e quindi pagherai ");
        output("il fio della tua tracotanza !!`2\"`nDetto ciò il monaco, prima che tu riesca ad impedirglielo, ti ");
        output("sottrae `^$oroperso pezzi d'oro `2e `&$gemmeperso gemme`2!!!`n Sei stato comunque fortunato perchè ha ");
        output("comunque azzerato la tua precedente carriera.`n");
        debuglog("cambia carriera/fede arrogantemente e viene penalizzato");
        addnav("Monastero","monastero.php");
        //rimozione del record punti riparazione, Sook
        if ($session['user']['carriera'] > 4 AND $session['user']['carriera'] < 9) {
            $sqldel = "DELETE FROM riparazioni WHERE acctid = '".$session['user']['acctid']."'";
            db_query($sqldel);
        }
        //rimozione del record quote dei maghi, Sook
        if ($session['user']['carriera'] > 41 AND $session['user']['carriera'] < 45) {
            $sqldel = "DELETE FROM rigenerazioni WHERE acctid = '".$session['user']['acctid']."'";
            db_query($sqldel);
        }
        $session['user']['gold'] -= $oroperso;
        $session['user']['gems'] -= $gemmeperso;
        report(3,"`%Cambio fede e carriera","`\$".$session['user']['name']." ha cambiato fede e carriera da prepotente per la ".($session['user']['cambio_carriera']+1)."° volta. Fede precedente: ".$dio1[$session['user']['dio']]." Carriera precedente: ".$prof[$session['user']['carriera']],"cambio_fede_carriera");
        $session['user']['dio'] = 0;
        $session['user']['carriera'] = 0;
        $session['user']['punti_carriera'] = 0;
        $session['user']['punti_generati'] = -5000;
        $session['user']['cambio_carriera'] += 1;
    }
} elseif (getsetting("puntisgriosfinemese",0)!=0 OR getsetting("puntikarnakfinemese",0)!=0 OR getsetting("puntidragofinemese",0)!=0) {
    output("`3`c`bAltare dei Sacrifici`b`c`n`n ");
    output("`3Mentre ti aggiri per il `2Monastero`3, ti imbatti in un altare di pietra, scavato in un ");
    output("blocco di roccia basaltica nei pressi di un grande albero della sorba. Ti avvicini, e noti ");
    output("un pertugio nella parte superiore. Questo è sicuramente un luogo speciale, e con un piccolo ");
    output("obolo hai la possibilità di cambiare la tua professione. `n`n");
    output("Sull'altare è stata lasciata una pergamena aperta, avvicinandoti leggi le seguenti parole:`n`n");
    output("`^\"Le premiazioni per la sfida delle sette non sono ancora state effettuate! Riprova tra qualche giorno o contatta gli admin se la situazione persiste\"`3.`n`n");
    addnav("Torna al monastero","monastero.php");
} else {
    output("`3`c`bAltare dei Sacrifici`b`c`n`n ");
    output("`3Mentre ti aggiri per il `2Monastero`3, ti imbatti in un altare di pietra, scavato in un ");
    output("blocco di roccia basaltica nei pressi di un grande albero della sorba. Ti avvicini, e noti ");
    output("un pertugio nella parte superiore. Questo è sicuramente un luogo speciale, e con un piccolo ");
    output("obolo hai la possibilità di cambiare la tua professione. `n`n");
    output("Sull'altare è stata lasciata una pergamena aperta, avvicinandoti leggi le seguenti parole:`n`n");
    output("`^\"Sono spiacente, forestiero, ma ho iniziato un lungo ritiro di preghiera nella mia cella e non posso aiutarti. Torna il prossimo mese\"`3.`n`n");
    addnav("Torna al monastero","monastero.php");
}
page_footer();
?>