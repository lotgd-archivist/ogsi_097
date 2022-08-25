<?php

/*
 * Nome:        ratrace.php
 * Autore:      JLV
 * Versione:    $Revision: 1.1.1.1 $
 * Creato il:   25-June-2006
 * Ottimizzato da Excalibur (eliminata tabella DB)
*/
require_once ("common.php");
page_header('La Corsa dei Ratti');
output("`c`bLa corsa dei Ratti`b`c`n`n");

if (!isset($session)) exit();

if ($_GET['op']=="" ) {
    $session['user']['specialinc']="ratrace.php";

    // se il parametro LOCKED vale 1 vuole dire che qualcuno sta gia' giocando
    // nell' evento speciale. Per gestire la concorrenza, bisogna aspettare che
    // l' evento speciale sia SLOCCATO.

    $locked = get_pref_race("rat_lock",2);
    if ( $locked == 1 ) {
      output ("`2Sei arrivato in una piccola radura dove è in corso una specie di corsa tra ratti. Noti la presenza");
      output ("di un orco e di un altra persona che osserva attentamente lo svolgersi della gara.");
      if ($session['user']['superuser']>=2){
            $session['user']['specialinc']="ratrace.php";
            addnav("T?`bTogli Lock`b","forest.php?op=togli_lock");
      } else{
            $session['user']['specialinc']="";
            addnav("R?Ritorna alla foresta", "forest.php");
      }
    }else{
       output("`2Sei arrivato in una piccola radura ombrosa, e noti al centro di essa un orco che sta russando rumorosamente, al suo fianco serpeggia una striscia ");
       output("`npiu' scura di terra battuta che assomiglia molto a una pista. ");
       output("`nAll'inizio di questo circuito da corsa improvvisato puoi vedere ");
       output("dieci paletti di legno infissi nel terreno a cui dieci ratti sono legati tramite catene arrugginite ");
       output("`nlunghe all'incirca una cinquantina di metri.`n");
       output("`nNoti anche un cartello con qualcosa scritto sopra, ma i caratteri sono molto sbiaditi.");
       addnav("Osserva i ratti", "forest.php?op=osserva_ratti");
       addnav("Leggi il cartello", "forest.php?op=leggi_il_cartello");
       addnav("Lascia la radura", "forest.php?op=lascia");
    }

}elseif ($_GET['op']=="togli_lock") {

    set_pref_race("rat_lock",2);
    output("`2Lock tolto.`n");
    $session['user']['specialinc']="ratrace.php";
    addnav("Continua", "forest.php");

}elseif ($_GET['op']=="osserva_ratti") {
    $session['user']['specialinc']="ratrace.php";
    output("`2I ratti sono molto magri, evidentemente non sono nutriti a sufficienza.`n");
    output("Alcune di queste piccole bestiole ricambiano il tuo sguardo e vedi nei loro occhietti tanta disperazione. ");
    output("`nTremanti e coi baffi tremolanti annusano l'aria, mentre timorosi si guardano intorno, suscitando in te una forte pena nei loro confronti.");
    $session['user']['specialinc']="ratrace.php";
    addnav("Continua", "forest.php");
} elseif ($_GET['op']=="leggi_il_cartello") {
    output("`c`b`2Partecipa alla corsa dei ratti!`b`c`n`n");
    output("`3Punta 100 pezzi d'oro sul tuo campione ed avrai la possibilità di:");
    output("`n`n`#- Vincere `^1000`# pezzi d'oro se il ratto che hai scelto vince.");
    output("`n- Vincere `^1500`# pezzi d'oro se il ratto che hai scelto vince con almeno 5 metri di vantaggio sul secondo.");
    output("`n- Vincere tutto il montepremi se il ratto che hai scelto vince con almeno 10 metri di vantaggio sul secondo.");
    $session['montepremi'] = get_pref_race("rat_montepremi",1500);
    output("`n`nIn questo momento il montepremi ammonta a `^".($session['montepremi'])."`# pezzi d'oro.");
    $session['user']['specialinc']="ratrace.php";
    addnav("Lascia Perdere", "forest.php?op=lascia");
    addnav("Punta", "forest.php?op=punta");

    //$session['user']['specialinc']="ratrace.php";

} elseif ($_GET['op']=="punta") {

     /**************************************************
   /* Il giocatore deve ancora scegliere il ratto
   ***************************************************/

   if ($_GET['subop']!="rattoscelto") {

      $session['user']['specialinc']="ratrace.php";

      if ( $session['user']['gold'] < 100 )
         output("Non hai oro sufficiente per effettuare una scommessa.");
      else
      {
         output("`2Puoi scegliere tra i seguenti campioni:`n`n");
         output("`@(1) Nato Stanco`n
                   (2) Piè Veloce`n
                   (3) Fulmine Svogliato`n
                   (4) Zampa Lenta`n
                   (5) Baffo Affascinante`n
                   (6) Fannullone Vero`n
                   (7) Movimento Lento`n
                   (8) Pantegana Grigia`n
                   (9) Codino Scattante`n
                   (10) Lumacone Nervoso`n`n");
             output("`2Quale scegli [1-10]?`n`n");

         output("<form action='forest.php?op=punta&subop=rattoscelto' method='POST'>
                   <input name='ratto'>
                   <input type='submit' class='button' value='Punta'>
                 </form>",true);

           addnav("","forest.php?op=punta&subop=rattoscelto");
      }

      addnav("Esci dalla radura", "forest.php?op=lascia");

    }

    /**************************************************
    /* Il giocatore ha scelto il ratto
    ***************************************************/

    else {

         $ratto = intval($_POST['ratto']);

         /**************************************************
         /* Check sulla scelta, se è errata -> retry
         ***************************************************/

         if ( $ratto < 1 or $ratto > 10 ) {
             output("Scegli per favore un numero tra 1 e 10.");
             $session['user']['specialinc']="ratrace.php";
             addnav("Punta", "forest.php?op=punta");
         }else{
             /**************************************************
             /* Tutto OK, locchiamo l'evento
             ***************************************************/
             set_pref_race("rat_lock",1);
             debuglog("Ratto scelto dall'utente: ".return_ratname($ratto) );
             output("`2Hai puntato sul ratto: `@".return_ratname($ratto));
             output("`2`n`nSvegli con uno scrollone l'orco e comunichi la tua scelta, consegnandogli i pezzi d'oro. ");
             output("`n L'orco, prendendoli a pedate, costringe i poveri ratti a dirigersi verso la zona di partenza. ");
             output("`nI poveri animali lentamente e a testa bassa si dispongono in fila sulla linea di partenza, pronti a scattare. ");
             output("`nCon un secco colpo di frusta l'orco urla '`@Viaaaaa!!!`2' ed i ratti si mettono in movimento, incominciano a correre... insomma, piu' che altro a barcollare...");

          // Stabiliamo chi è avanti a metà corsa

          $rand_ratto_step_1 = e_rand(1, 10);

           switch(e_rand(1, 10)) {
            case 1: output("`3`n`n...A circa metà della corsa, `!".return_ratname($rand_ratto_step_1)." `3è in testa con una incollatura di vantaggio sul secondo e sul terzo che procedono appaiati.");
            break;
            case 2: output("`3`n`n...A circa metà della corsa, `!".return_ratname($rand_ratto_step_1)." `3è in testa con una lunghezza di vantaggio sul secondo e sul gruppo degli inseguitori.");
            break;
            case 3: output("`3`n`n...A circa metà della corsa, `!".return_ratname($rand_ratto_step_1)." `3è in testa con una incollatura di vantaggio sul secondo e sul terzo che lo pressano da vicino.");
            break;
            case 4: output("`3`n`n...A circa metà della corsa, `!".return_ratname($rand_ratto_step_1)." `3è in testa con due lunghezze di vantaggio sul secondo, segue il resto del gruppo.");
            break;
            case 5: output("`3`n`n...A circa metà della corsa, `!".return_ratname($rand_ratto_step_1)." `3è in testa con una lunghezza di vantaggio sul secondo, seguono il terzo e il quarto mentre il gruppo è lontano.");
            break;
            case 6: output("`3`n`n...A circa metà della corsa, `!".return_ratname($rand_ratto_step_1)." `3è in vantaggio di pochissimo sul secondo e sul terzo che procedono appaiati, via via tutti gli altri.");
            break;
            case 7: output("`3`n`n...A circa metà della corsa, `!".return_ratname($rand_ratto_step_1)." `3è in vantaggio di pochissimo sul gruppo degli inseguitori che lo incalzano tenendogli il fiato sul collo.");
            break;
            case 8: output("`3`n`n...A circa metà della corsa, `!".return_ratname($rand_ratto_step_1)." `3è in testa con grande vantaggio sugli inseguitori che appaiono stanchi e sfiduciati.");
            break;
            case 9: output("`3`n`n...A circa metà della corsa, `!".return_ratname($rand_ratto_step_1)." `3è in testa con due lunghezze di vantaggio sul secondo e sul terzo, dietro di loro il gruppo arranca.");
            break;
            case 10: output("`3`n`n...A circa metà della corsa, `!".return_ratname($rand_ratto_step_1)." `3è in testa con una lunghezza di vantaggio sul secondo, seguono il terzo e il quarto appaiati.");
            break;
            }

          // stabiliamo qua il vantaggio, 10% di probabilità per il vantaggio di 5 metri,
          // 1% vantaggio di 10 metri

          $rand = e_rand(1,100);

          if ( $rand > 2 and $rand < 10 ){
             $rand_vantaggio = 5;
          }elseif ( $rand < 2 ){
             $rand_vantaggio = 10;
          }else{
             $rand_vantaggio = 1;
          }
          $rand_ratto_step_2 = e_rand(1, 10);

          if ( $rand_ratto_step_2 != $rand_ratto_step_1 ){
              switch(e_rand(1, 10)) {
            case 1: output ("`n`n`3...Con un recupero prodigioso, il ratto `#".return_ratname($rand_ratto_step_2)." `3vince, con un vantaggio sul secondo di ");
            break;
            case 2: output ("`n`n`3...Con un finale irresistibile, il ratto `#".return_ratname($rand_ratto_step_2)." `3vince, con un vantaggio sul secondo di ");
            break;
            case 3: output ("`n`n`3...Con uno scatto imperioso, il ratto `#".return_ratname($rand_ratto_step_2)." `3vince, con un vantaggio sul secondo di ");
            break;
            case 4: output ("`n`n`3...Con una progressione inarrestabile, il ratto `#".return_ratname($rand_ratto_step_2)." `3vince, con un vantaggio sul secondo di ");
            break;
            case 5: output ("`n`n`3...Con un attacco nel finale, il ratto `#".return_ratname($rand_ratto_step_2)." `3vince, con un vantaggio sul secondo di ");
            break;
            case 6: output ("`n`n`3...Con una fuga da lontano, il ratto `#".return_ratname($rand_ratto_step_2)." `3vince, con un vantaggio sul secondo di ");
            break;
            case 7: output ("`n`n`3...Con un attacco a sorpresa, il ratto `#".return_ratname($rand_ratto_step_2)." `3vince, con un vantaggio sul secondo di ");
            break;
            case 8: output ("`n`n`3...Con uno scatto finale, il ratto `#".return_ratname($rand_ratto_step_2)." `3vince, con un vantaggio sul secondo di ");
            break;
            case 9: output ("`n`n`3...Con una volata bruciante, il ratto `#".return_ratname($rand_ratto_step_2)." `3vince, con un vantaggio sul secondo di ");
            break;
            case 10: output ("`n`n`3...Con uno sprint eccezionale, il ratto `#".return_ratname($rand_ratto_step_2)." `3vince, con un vantaggio sul secondo di ");
            break;
            }

          }else{
             output ("`n`n`3Proseguendo nella sua corsa, il ratto `#".return_ratname($rand_ratto_step_2)." `3vince, con un vantaggio sul secondo di ");
          }
          if ( $rand_vantaggio == 1 ){
             output("meno di 5 metri." );
             $premio = 1000;
          }elseif ($rand_vantaggio == 5 ){
             output("piu' di 5 metri." );
             $premio = 1500;
          }else{
             output(" piu' di 10 metri!!!" );
             $premio = $session['montepremi'];
          }

          if ( $rand_ratto_step_2 == $ratto ){
             if ( $premio != $session['montepremi']){
                output("`n`n`2Il ratto sul quale hai puntato 100 pezzi d'oro e le tue speranze ha vinto. Il viso dell'orco si intristisce mentre ti consegna i ".$premio." pezzi d'oro.");
                $montepremi = $session['montepremi'] - $premio;
                $session['user']['gold'] += $premio;
             }else{
                output("`n`n`2L'orco si dispera, dato che lo hai mandato in rovina. Riluttante, ti consegna i ".$premio." pezzi d'oro.");
                $session['user']['gold'] += $premio;
             }
             output("`n`n`^Hai vinto ".$premio." pezzi d'oro.");
             debuglog("vince $premio alla corsa dei ratti e il montepremi scende a ".$montepremi);
          }else{
             output("`2`n`nL'orco sorride esponendo i suoi denti marci, e ti dice: ");
             output("`n'`@Hai perso Ahr Ahr, ma non ti preoccupare, i tuoi soldi sono andati ad ");
             output("incrementare il montepremi, puoi sempre riprovarci... Ahr Ahr Ahrr...'");
             output("`2`nL'orco soddisfatto, pone i tuoi pezzi d'oro in un sacchetto e torna a dormire.");
             output("`n`n`^Hai perso 100 pezzi d'oro.");
             $session['user']['gold'] -= 100;
             $montepremi = $session['montepremi'] + 100;
             debuglog("Perde 100 monete d'oro alla corsa dei ratti e il montepremi sale a ".$montepremi );
          }

          // Il minimo del montepremi è 1500

          if ($montepremi < 1500) $montepremi = 1500;
          set_pref_race("rat_montepremi",$montepremi);
          set_pref_race("rat_lock",2);
          $session['user']['specialinc']="ratrace.php";
          addnav("Esci dalla radura","forest.php?op=esci");

       }
    }
} elseif ($_GET['op']=="lascia") {
    output("`2Decidi che le corse di topi non sono interessanti e non fanno al caso tuo, è meglio andare subito nella foresta in cerca di nuove avventure sicuramente più emozionanti!`n");
    //set_pref_race("rat_lock",2);
    $session['user']['specialinc']="";
    addnav("F?Torna alla Foresta","forest.php");
} elseif ($_GET['op']=="esci") {
    output("`2Dopo aver provato l'ebbrezza di aver assistito a una corsa di topi torni alla foresta in cerca di nuove avventure e di creature da massacrare.`n");
    //set_pref_race("rat_lock",2);
    $session['user']['specialinc']="";
    addnav("F?Torna alla Foresta","forest.php");
}
page_footer();

function return_ratname($id=null){
    switch ($id){
        case 1:return "Nato Stanco";
        case 2:return "Piè Veloce";
        case 3:return "Fulmine Svogliato";
        case 4:return "Zampa Lenta";
        case 5:return "Baffo Affascinante";
        case 6:return "Fannullone Vero";
        case 7:return "Movimento Lento";
        case 8:return "Pantegana Grigia";
        case 9:return "Codino Scattante";
        case 10:return "Lumacone Nervoso";
   }
}

function get_pref_race($prefs){
    global $session;
    $sql = "SELECT value1 FROM items WHERE class = 'pond' AND name = '$prefs'";
    $result = db_query($sql) or die(db_error(LINK));
    $row = db_fetch_assoc($result);
    if (db_num_rows($result)==0){
        return 0;
    } else {
        return $row['value1'];
    }
}

function set_pref_race($prefs,$value){
    global $session;
    $sql = "SELECT * FROM items WHERE class = 'pond' AND name='$prefs'";
    $result = db_query($sql) or die(db_error(LINK));
    if (db_affected_rows()==0){
       $sql = "INSERT INTO items (id,name,class,owner,value1,value2,gold,gems,description,hvalue,buff,tempo)
                           VALUES('','$prefs','pond','','$value','','','','','','','')";
       $result = db_query($sql) or die(db_error(LINK));
    }else{
      $sql = "UPDATE items SET value1='$value' WHERE class = 'pond' AND name='$prefs'";
      $result = db_query($sql) or die(db_error(LINK));
    }
}

?>