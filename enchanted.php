<?php
require_once "common.php";
addcommentary();
checkday();
page_header("Il Giardino Incantato");
$session['user']['locazione'] = 119;
if ($session['user']['dragonkills']>5 || $session['user']['superuser']>1){
   if ($_GET['op']==""){
      output(" `c`@Il Giardino Incantato`c`n`n");
      output(" `@`cBENVENUTO!  `iIl Giardino Incantato`i `2è riservato ai `3Veterani Uccisori del `@Drago`2 `2di OGSI`c`n`n");
      output(" `2Ti trovi all'interno di un giardino circolare circondato da rocce scolpite a forma di drago.`n");
      output(" Passeggi osservando le statue draghesche cercando di capire quali possano essere i misteri racchiusi in questo luogo.`n");
      output(" Una piacevole sensazione di formicolio scorre lungo tutto il corpo.`n");
      output(" Mentre ti avvicini, sfiorando con le dita quelle splendide sculture di Creature Incantate,`n");
      output(" provi ad immaginare chi possa essere stato quell'abile scalpellino in grado di realizzare tali opere d'arte di così grande perfezione.`n");
      output(" Al centro del Giardino c'è una antica meridiana, ti avvicini e noti che ogni punto è inciso e riporta la direzione ed il nome di un vento.`n`n");
      output(" `2In qualità di esperto veterano ". $session['user']['name']." `2hai accesso ad un `3Evento Speciale`2. `n`n");
      output(" Questo ti costerà `&1`2 turno `^PvP`2, se ne hai ancora a disposizione, e l'`3Evento`2 a cui accederai verrà scelto in maniera completamente casuale tra quelli disponibili.`n");
      addnav("`^Torna alla Foresta","forest.php");
      addnav("Accedi all'Evento","enchanted.php?op=evento");
   }elseif ($_GET['op']=="evento" && $session['user']['playerfights']>0) {
      $session['user']['playerfights']-=1;
      $session['user']['restorepage']="enchanted.php";
      if ($handle = opendir("special")){
          $events = array();
          while (false !== ($file = readdir($handle))){
            if (strpos($file,".php")>0){
            // Skip the darkhorse if the horse knows the way
                  if ($session['user']['hashorse'] > 0 && $playermount['tavern'] > 0 && strpos($file, "darkhorse") !== false) continue;
                  // Saltiamo Merk se il personaggio non ha almeno 4 reincarnazioni
                  if ($session['user']['reincarna'] < 4 && strpos($file, "merk") !== false) continue;
                  array_push($events,$file);
            }
          }
          $tot=0; //calcolo somma dei totali degli eventi per poi effettuare il sorteggio
          for($i=0;$i<(count($events));$i++) {
             $sqlch="SELECT giardino FROM peso_eventi WHERE nomefile='".$events[$i]."' ";
             $resultch = db_query($sqlch) or die(db_error(LINK));
             $rowch = db_fetch_assoc($resultch);
             $tot += $rowch['giardino'];
          }
          //srand ((double) microtime() * 10000000);
//              $x = e_rand(0,count($events)-1);
//Sook, diversificazione delle probabilità dei vari eventi
          if (count($events)==0){
            output("`b`@Ahi, il tuo Amministratore ha deciso che non ti è permesso avere eventi speciali. Prenditela con lui, non con me.");
          }else{
            $x=e_rand(1,$tot); //numero estratto per la selezione evento
            $sqlevents="SELECT nomefile, giardino FROM peso_eventi ORDER BY rand()";
            $resultevents = db_query($sqlevents) or die(db_error(LINK));
            $j=0; //indice pesato per la ricerca dell'evento estratto
            while ($j<$x) {
                $rowevents = db_fetch_assoc($resultevents);
                $i = $rowevents['nomefile'];
                $k=0; //controllo che l'evento possa essere scelto
                for ($l=0;$l<(count($events));$l++) {
                    if ($i==$events[$l]) $k=1; //ok, l'evento è stato trovato ed il giocatore può averlo
                }
                if ($k==1) $j += $rowevents['giardino']; //incremento indice peso
            }
            $y = $_GET['op'];
            $_GET['op']="";
            debuglog("ha utilizzato un turno PvP al giardino incantato e ottenuto ".$i);
            //include("special/".$events[$x]);
            //@(include("special/".$events[$x])) or redirect("forest.php");
            @(include("special/".$i)) or redirect("forest.php"); //si usa un altro indice
            $_GET['op']=$y;
          }
      }else{
         output("`c`b`\$ERRORE!!!`b`c`&Non riesco ad aprire gli eventi speciali!  Per favore avverti gli Admin!!`c`b");
      }
      //addnav("`!Torna alla Foresta","forest.php");
   }else {
      output("`c<font size='+1'>`\$Spiacente ma non hai turni PvP residui.</font>`c`n",true);
      //output("`4Spiacente ma non hai turni PvP residui.");
      addnav("`%Torna alla Foresta","forest.php");
   }
   if ($_GET['op']!="evento"){
      output("`n`n`%`@`@I Veterani di OGSI dicono: `n");
      viewcommentary("enchanted","Vantati",20,10);
   }
}else{
    page_header("Il Giardino Incantato");
    output(" `^Ti avvicini al Giardino Incantato. Dopo aver osservato i dintorni per un po',`n");
    output(" continua a sembrarti un mucchio di cespugli e fiori e niente di più. `n");
    output(" E' strano ... ti sembra di sentire dei Guerrieri ma non vedi nessuno.`n");
    output(" Deve essere il vento, non c'è niente di speciale da vedere qui.`n");
    output(" Annoiato, decidi di tornare ad uccidere qualche creatura della foresta.");
    addnav(" Torna alla Foresta","forest.php");
}
page_footer();
?>