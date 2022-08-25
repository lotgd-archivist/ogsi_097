<?php
/*
Il mercante di regalini
By Excalibur (www.ogsi.it)
version 1.0 october 26, 2006
*/
require_once("common.php");
require_once("common2.php");
if (!isset($session)) exit();
$costo = 1; // costo in gemme del regalo
page_header("Il Mercante di preziosi");
output("`n`c`b`^Il Mercante di Preziosi`7`b`c`n",true);
if ($_GET['op']==""){
   output("`7Mentre stai per avviarti verso la foresta in cerca di avventura, alla periferia del villaggio vieni ");
   output("fermat".($session[user][sex]?"a":"o")." da uno strano essere, mezzo elfo, mezzo nano, avvolto in un mantello `2verdognolo`7.`n");
   output("\"`i`#Buona giornata a te, ".$session['user']['name']."`#!! Mi presento sono `@Ophelius`3.`n");
   if ($session['user']['sex']) {
       $regalo = array(
               "`^paio di gemelli d'oro",
               "`8cinturone di cuoio",
               "`#cappello",
               "`(paio di stivali",
               "`&set di pennelli",
               "`^pennino d'oro",
               "`!giavellotto da caccia",
               "`2pugnale intarsiato",
               "`@set di canne da pesca",
       );
   }else {
         $regalo = array(
               "`^paio di orecchini",
               "`%paio di pantofole di raso",
               "`&girocollo di diamanti",
               "`@grazioso braccialetto",
               "`\$rubino incastonato",
               "`3piercing con lapislazzuli",
               "`^monile d'oro",
               "`&solitario incastonato",
               "`(prezioso pendente",
         );
   }
   $session['ciondolo'] = $regalo[e_rand(0, count($regalo)-1)];
   output("`#Può interessarti un ".$session['ciondolo']." `#per qualcuno di speciale?");
   output("È un regalo delizioso, creato dalle abili mani di un esperto artigiano!");
   if ($costo == 1) {
       output("E, per te, solo `&$costo`# gemma!");
   } else  {
       output("E, per te, solo  `&$costo`# gemme!");
   }
   output("`i`7\"`n`n");
   if ($session['user']['sex']==0){
       $session['spos']="`%Violet";
       }else {$session['spos']="`!Seth";}
   if ($session['user']['charisma']==4294967295){
       if ($session['user']['marriedto']==4294967295){
           if ($session['user']['sex']==0){
              $session['spos']="tua moglie `%Violet";
           }else {
              $session['spos']="tuo marito `!Seth";
           }
       }else {
           $result = db_query("SELECT acctid,name FROM accounts WHERE acctid=".$session['user']['marriedto']) or die(db_error(LINK));
           $row = db_fetch_assoc($result) or die(db_error(LINK));
           $session['sposnum'] = $row['acctid'];
           if ($session['user']['sex']==0){
               $session['spos']="tua moglie ".$row['name'];
               $session['spos1']="tuo marito ".$session['user']['name'];
           }else {
               $session['spos']="tuo marito ".$row['name'];
               $session['spos1']="tua moglie ".$session['user']['name'];
           }
       }
   }elseif ($session['user']['charisma']>0){
       if ($session['user']['marriedto']==4294967295){
           if ($session['user']['sex']==0){
               $session['spos']="la tua fidanzata `%Violet";
           }else {$session['spos']="il tuo fidanzato `!Seth";}
       }else {
           $result = db_query("SELECT acctid,name FROM accounts WHERE acctid=".$session['user']['marriedto']) or die(db_error(LINK));
           $row = db_fetch_assoc($result) or die(db_error(LINK));
           $session['sposnum'] = $row['acctid'];
           if ($session['user']['sex']==0){
               $session['spos']="la tua fidanzata ".$row['name'];
               $session['spos1']="il tuo fidanzato ".$session['user']['name'];
           }else {
               $session['spos']="il tuo fidanzato ".$row['name'];
               $session['spos1']="la tua fidanzata ".$session['user']['name'];
           }
       }
   }
   output("`7Osservi attentamente il ".$session['ciondolo']."`7, ne ammiri la fine lavorazione, e cerchi di immaginare ");
   output($session['spos']."`7 mentre ".($session['user']['sex']?"usa":"indossa")." il prezioso dono.");
   $session['user']['specialinc'] = "mercante.php";
   addnav("Acquista il regalo","forest.php?op=comprasi");
   addnav("Non comprare nulla","forest.php?op=comprano");
}elseif($_GET['op']=="comprano"){
   output("`7Dopo aver rimirato attentamente il ".$session['ciondolo']." `7di `@Ophelius`7, decidi di non acquistarlo.`n`n");
   output("`7Sei sicuro che ".$session['spos']."`7 non apprezzerebbe una cosa del genere, non è nel suo stile.`n");
   $session['user']['specialinc'] = "";
}elseif($_GET['op']=="allontanati"){
    addnav("Torna in Foresta","forest.php");
    $session['user']['specialinc'] = "";
    output("`5Non avendo gemme a sufficienza per acquistare il regalo per ".$session['spos']."`5, ti allontani mestamente.`n`n");
}elseif($_GET['op']=="comprasi"){
    if($session['user']['gems']<$costo){
        $session['user']['specialinc'] = "mercante.php";
        if($session['user']['gems']==0){
            output("`7Ophelius osserva la tua mano vuota.`n`n");
        } else {
            if($session['user']['gems']==1){
                output("`7Ophelius osserva l'unica gemma nel palmo della tua mano.`n`n");
            } else {
                output("`7Ophelius osserva le ".$session['user']['gems']." gemme nella tua mano.`n`n");
            }
        }
        output("`7Come pensi di acquistare un regalo a ".$session['spos']." `7senza avere gemme a sufficienza?");
        addnav("Allontanati","forest.php?op=allontanati");
    }else{
        set_pref_pond("mercante",1);
        $session['user']['gems']-=$costo;
        debuglog("spende $costo gemma/e per un regalo al suo amore ".$session['spos']);
        if ($costo == 1) {
           output("`7Decis".($session[user][sex]?"a":"o")." ad acquistare il ".$session['ciondolo']."`7, allunghi la gemma a Ophelius.`n`n");
        } else {
           output("`7Decis".($session[user][sex]?"a":"o")." ad acquistare il ".$session['ciondolo']."`7, Allunghi ad Ophelius $costo gemme.`n`n");
        }
        output("`7Ophelius promette di consegnare il ".$session['ciondolo']."`7 a ".$session['spos']."`7 immediatamente.`n`n");
        output("`7Sei impaziente  di scoprire cosa pensa ".$session['spos']."`7 del regalo, ma dovrai aspettare il prossimo giorno!");
        $likechance=(e_rand(1,100));
        $newval=get_pref_pond("liked",0);
        if ($likechance<=75) {
           $newval++;
        }else {
           $newval--;
        }
        set_pref_pond("liked",$newval);
        addnav("Torna in Foresta","forest.php");
        $session['user']['specialinc'] = "";
        $mailmessage = "`@".$session['spos1']." `@ti ha mandato in regalo un ".$session['ciondolo']."`@!!!";
        systemmail($session['sposnum'],"`2Hai ricevuto un regalo!!",$mailmessage,$session['user']['acctid']);
    }
}elseif($_GET['op'] != "") {
    addnav("Torna in Foresta","forest.php");
    $session['user']['specialinc'] = "";
}


?>