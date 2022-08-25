<?php

require_once "common.php";
page_header("Il Maniero Burocratico Maledetto!");
$session['user']['locazione'] = 148;
/*
Il Maniero Burocratico Maledetto
Scritto da Maximus per www.ogsi.it
Basato su una grande idea di Poker (www.ogsi.it)
*/


// Variabili per regolare la grandezza del labirinto :-P
$maxNumeroPiani = 5;
$maxNumeroSotterranei = 3;
$maxNumeroStanzePerPiano = 10;
$maxNumeroStanze = $maxNumeroPiani * $maxNumeroStanzePerPiano ;
$maxNumeroTimbri = 5;

// Funzioni per criptare e decriptare la navigazione
function encrypt($stringa,$silink){
   $criptato=strrev(base64_encode($stringa));
   $rnd=rand(0,9);
   $out="";
   if ($silink) $out= "link=";
   return $out.$criptato.$rnd;
}

function decrypt($stringa){
   $lunghezza=(strlen($stringa)-1);
   $decriptato=base64_decode(strrev(substr($stringa,0,$lunghezza)));
   $url_array = explode("&", $decriptato);
   for($i=0; $i < count($url_array); $i++) {
       $ua=$url_array[$i];
       $url_a = explode("=", $ua);
       $url_valori[$url_a[0]]=$url_a[1];
   }
   return $url_valori;
}
// Funzione modificata per generare il menù di combattimento
function fightnavmod($allowspecial=true, $allowflee=true, $url){
    global $PHP_SELF,$session;
    //$script = str_replace("/","",$PHP_SELF);
    $url = "&".$url;
    $script = substr($PHP_SELF,strrpos($PHP_SELF,"/")+1);
    addnav("Combatti","$script?op=fight$url");
    if ($allowflee) {

        addnav("Fuggi","$script?op=run$url");
    }
    //Modifica AutoFight
    if (getsetting("autofight",0)){
        addnav("AutoFight");
        addnav("5 Round","$script?op=fight&auto=five$url");
        addnav("M?Fino alla Morte","$script?op=fight&auto=full$url");
    }
    //Fine AutoFight
    if ($allowspecial) {
        addnav("Abilità Speciali");
        if ($session['user']['darkartuses']>0) {
            addnav("`\$Arti Oscure`0","");
            addnav("S?`\$&#149; C`\$iurma di Scheletri`7 (1/".$session['user']['darkartuses'].")`0","$script?op=fight&skill=DA&l=1$url",true);
        }
        if ($session['user']['darkartuses']>1)
        addnav("V?`\$&#149; V`\$oodoo`7 (2/".$session['user']['darkartuses'].")`0","$script?op=fight&skill=DA&l=2$url",true);
        if ($session['user']['darkartuses']>2)
        addnav("M?`\$&#149; M`\$aledizione`7 (3/".$session['user']['darkartuses'].")`0","$script?op=fight&skill=DA&l=3$url",true);
        if ($session['user']['darkartuses']>4)
        addnav("A?`\$&#149; A`\$vvizzisci Anima`7 (5/".$session['user']['darkartuses'].")`0","$script?op=fight&skill=DA&l=5$url",true);

        if ($session['user']['thieveryuses']>0) {
            addnav("`^Furto`0","");
            addnav("I?`^&#149; I`^nsulto`7 (1/".$session['user']['thieveryuses'].")`0","$script?op=fight&skill=TS&l=1$url",true);
        }
        if ($session['user']['thieveryuses']>1)
        addnav("L?`^&#149; L`^ama Avvelenata`7 (2/".$session['user']['thieveryuses'].")`0","$script?op=fight&skill=TS&l=2$url",true);
        if ($session['user']['thieveryuses']>2)
        addnav("A?`^&#149; A`^ttacco Nascosto`7 (3/".$session['user']['thieveryuses'].")`0","$script?op=fight&skill=TS&l=3$url",true);
        if ($session['user']['thieveryuses']>4)
        addnav("P?`^&#149; P`^ugnalata alle Spalle`7 (5/".$session['user']['thieveryuses'].")`0","$script?op=fight&skill=TS&l=5$url",true);

        if ($session['user']['magicuses']>0) {
            addnav("`%Poteri Mistici`0","");
            //disagree with making this 'n', players shouldn't have their behavior dictated by convenience of god mode, hehe
            addnav("R?`%&#149; R`%igenerazione`7 (1/".$session['user']['magicuses'].")`0","$script?op=fight&skill=MP&l=1$url",true);
        }
        if ($session['user']['magicuses']>1)
        addnav("P?`%&#149; P`%ugno di Terra`7 (2/".$session['user']['magicuses'].")`0","$script?op=fight&skill=MP&l=2$url",true);
        if ($session['user']['magicuses']>2)
        addnav("D?`%&#149; D`%rena Vita`7 (3/".$session['user']['magicuses'].")`0","$script?op=fight&skill=MP&l=3$url",true);
        if ($session['user']['magicuses']>4)
        addnav("A?`%&#149; A`%ura di Fulmini`7 (5/".$session['user']['magicuses'].")`0","$script?op=fight&skill=MP&l=5$url",true);


        if ($session['user']['militareuses']>0) {
            addnav("`3Militare`0", "");
            addnav("M?`3&#149; C`3olpo Multiplo`7 (1/".$session['user']['militareuses'].")`0","$script?op=fight&skill=CM&l=1$url",true);
        }
        if ($session['user']['militareuses']>1)
        addnav("o?`3&#149; C`3olpo Mirato`7 (2/".$session['user']['militareuses'].")`0","$script?op=fight&skill=CM&l=2$url",true);
        if ($session['user']['militareuses']>2)
        addnav("F?`3&#149; F`3erite Mirate`7 (3/".$session['user']['militareuses'].")`0","$script?op=fight&skill=CM&l=3$url",true);
        if ($session['user']['militareuses']>4)
        addnav("B?`3&#149; B`3erserk`7 (5/".$session['user']['militareuses'].")`0","$script?op=fight&skill=CM&l=5$url",true);

        if ($session['user']['mysticuses']>0) {
            addnav("`\$Seduzione`0", "");
            addnav("`\$&#149; Sirene`7 (1/".$session['user']['mysticuses'].")`0","$script?op=fight&skill=MY&l=1$url",true);
        }
        if ($session['user']['mysticuses']>1)
        addnav("`\$&#149; Danza`7 (2/".$session['user']['mysticuses'].")`0","$script?op=fight&skill=MY&l=2$url",true);
        if ($session['user']['mysticuses']>2)
        addnav("`\$&#149; Fascino`7 (3/".$session['user']['mysticuses'].")`0","$script?op=fight&skill=MY&l=3$url",true);
        if ($session['user']['mysticuses']>4)
        addnav("`\$&#149; Sonno`7 (5/".$session['user']['mysticuses'].")`0","$script?op=fight&skill=MY&l=5$url",true);

        if ($session['user']['tacticuses']>0) {
            addnav("`^Tattica`0", "");
            addnav("`^&#149; Reclute`7 (1/".$session['user']['tacticuses'].")`0","$script?op=fight&skill=TA&l=1$url",true);
        }
        if ($session['user']['tacticuses']>1)
        addnav("`^&#149; Sorpresa`7 (2/".$session['user']['tacticuses'].")`0","$script?op=fight&skill=TA&l=2$url",true);
        if ($session['user']['tacticuses']>2)
        addnav("`^&#149; Attacco Notturno`7 (3/".$session['user']['tacticuses'].")`0","$script?op=fight&skill=TA&l=3$url",true);
        if ($session['user']['tacticuses']>4)
        addnav("`^&#149; Arti Marziali`7 (5/".$session['user']['tacticuses'].")`0","$script?op=fight&skill=TA&l=5$url",true);

        if ($session['user']['rockskinuses']>0) {
            addnav("`@Pelle di Roccia`0", "");
            addnav("`@&#149; Rocce Cadenti`7 (1/".$session['user']['rockskinuses'].")`0","$script?op=fight&skill=RS&l=1$url",true);
        }
        if ($session['user']['rockskinuses']>1)
        addnav("`@&#149; Pelle Dura`7 (2/".$session['user']['rockskinuses'].")`0","$script?op=fight&skill=RS&l=2$url",true);
        if ($session['user']['rockskinuses']>2)
        addnav("`@&#149; Pugno di Roccia`7 (3/".$session['user']['rockskinuses'].")`0","$script?op=fight&skill=RS&l=3$url",true);
        if ($session['user']['rockskinuses']>4)
        addnav("`@&#149; Eco Montano`7 (5/".$session['user']['rockskinuses'].")`0","$script?op=fight&skill=RS&l=5$url",true);

        if ($session['user']['rhetoricuses']>0) {
            addnav("`#Retorica`0", "");
            addnav("`#&#149; Dizionari`7 (1/".$session['user']['rhetoricuses'].")`0","$script?op=fight&skill=RH&l=1$url",true);
        }
        if ($session['user']['rhetoricuses']>1)
        addnav("`#&#149; Paroloni`7 (2/".$session['user']['rhetoricuses'].")`0","$script?op=fight&skill=RH&l=2$url",true);
        if ($session['user']['rhetoricuses']>2)
        addnav("`#&#149; Scioglilingua`7 (3/".$session['user']['rhetoricuses'].")`0","$script?op=fight&skill=RH&l=3$url",true);
        if ($session['user']['rhetoricuses']>4)
        addnav("`#&#149; Discorsi`7 (5/".$session['user']['rhetoricuses'].")`0","$script?op=fight&skill=RH&l=5$url",true);

        if ($session['user']['muscleuses']>0) {
            addnav("`%Muscoli`0", "");
            addnav("`%&#149; Gragnuola di Pugni`7 (1/".$session['user']['muscleuses'].")`0","$script?op=fight&skill=MS&l=1$url",true);
        }
        if ($session['user']['muscleuses']>1)
        addnav("`%&#149; Flessibilità`7 (2/".$session['user']['muscleuses'].")`0","$script?op=fight&skill=MS&l=2$url",true);
        if ($session['user']['muscleuses']>2)
        addnav("`%&#149; Capezzoli infuriati`7 (3/".$session['user']['muscleuses'].")`0","$script?op=fight&skill=MS&l=3$url",true);
        if ($session['user']['muscleuses']>4)
        addnav("`%&#149; Lozione Abbronzante`7 (5/".$session['user']['muscleuses'].")`0","$script?op=fight&skill=MS&l=5$url",true);

        if ($session['user']['natureuses']>0) {
            addnav("`3Natura`0", "");
            addnav("`3&#149; Aiuto Animale`7 (1/".$session['user']['natureuses'].")`0","$script?op=fight&skill=NA&l=1$url",true);
        }
        if ($session['user']['natureuses']>1)
        addnav("`3&#149; Infestazione di Scarafaggi`7 (2/".$session['user']['natureuses'].")`0","$script?op=fight&skill=NA&l=2$url",true);
        if ($session['user']['natureuses']>2)
        addnav("`3&#149; Artigli delle Aquile`7 (3/".$session['user']['natureuses'].")`0","$script?op=fight&skill=NA&l=3$url",true);
        if ($session['user']['natureuses']>4)
        addnav("`3&#149; Bigfoot`7 (5/".$session['user']['natureuses'].")`0","$script?op=fight&skill=NA&l=5$url",true);


        if ($session['user']['weatheruses']>0) {
            addnav("`&Clima`0", "");
            addnav("`&&#149; Folate di Vento`7 (1/".$session['user']['weatheruses'].")`0","$script?op=fight&skill=WE&l=1$url",true);
        }
        if ($session['user']['weatheruses']>1)
        addnav("`&&#149; Tornado`7 (2/".$session['user']['weatheruses'].")`0","$script?op=fight&skill=WE&l=2$url",true);
        if ($session['user']['weatheruses']>2)
        addnav("`&&#149; Pioggia`7 (3/".$session['user']['weatheruses'].")`0","$script?op=fight&skill=WE&l=3$url",true);
        if ($session['user']['weatheruses']>4)
        addnav("`&&#149; Gelo Polare`7 (5/".$session['user']['weatheruses'].")`0","$script?op=fight&skill=WE&l=5$url",true);

        //Excalibur: nuove razze per donatori
        if ($session['user']['elementaleuses']>0) {
            addnav("`^Abilità Elementalista`0", "");
            addnav("`^&#149; Elementale d'Aria`7 (1/".$session['user']['elementaleuses'].")`0","$script?op=fight&skill=EL&l=1$url",true);
        }
        if ($session['user']['elementaleuses']>1)
        addnav("`^&#149; Elementale d'Acqua`7 (2/".$session['user']['elementaleuses'].")`0","$script?op=fight&skill=EL&l=2$url",true);
        if ($session['user']['elementaleuses']>2)
        addnav("`^&#149; Elementale di Terra`7 (3/".$session['user']['elementaleuses'].")`0","$script?op=fight&skill=EL&l=3$url",true);
        if ($session['user']['elementaleuses']>4)
        addnav("`^&#149; Elementale di Fuoco`7 (5/".$session['user']['elementaleuses'].")`0","$script?op=fight&skill=EL&l=5$url",true);

        if ($session['user']['barbarouses']>0) {
            addnav("`6Rabbia Barbara`0", "");
            addnav("`6&#149; Schivata Misteriosa`7 (1/".$session['user']['barbarouses'].")`0","$script?op=fight&skill=BB&l=1$url",true);
        }
        if ($session['user']['barbarouses']>1)
        addnav("`6&#149; Rabbia`7 (2/".$session['user']['barbarouses'].")`0","$script?op=fight&skill=BB&l=2$url",true);
        if ($session['user']['barbarouses']>2)
        addnav("`6&#149; Riduzione Danno`7 (3/".$session['user']['barbarouses'].")`0","$script?op=fight&skill=BB&l=3$url",true);
        if ($session['user']['barbarouses']>4)
        addnav("`6&#149; Ira Possente`7 (5/".$session['user']['barbarouses'].")`0","$script?op=fight&skill=BB&l=5$url",true);

        if ($session['user']['bardouses']>0) {
            addnav("`5Canzoni del Bardo`0", "");
            addnav("`5&#149; Fascinazione`7 (1/".$session['user']['bardouses'].")`0","$script?op=fight&skill=BA&l=1$url",true);
        }
        if ($session['user']['bardouses']>1)
        addnav("`5&#149; Canzone alla Rovescia`7 (2/".$session['user']['bardouses'].")`0","$script?op=fight&skill=BA&l=2$url",true);
        if ($session['user']['bardouses']>2)
        addnav("`5&#149; Grandezza Ispirata`7 (3/".$session['user']['bardouses'].")`0","$script?op=fight&skill=BA&l=3$url",true);
        if ($session['user']['bardouses']>4)
        addnav("`5&#149; Suggestione di Massa`7 (5/".$session['user']['bardouses'].")`0","$script?op=fight&skill=BA&l=5$url",true);



        if ($session[user][superuser]>=3) {
            addnav("`&Super user`0","");
            addnav("!?`&&#149; __M`&ODALITÀ DIVINA","$script?op=fight&skill=godmode$url",true);
        }

        // spells by anpera
        if ($session['user']['reincarna']>1){
            $sql="SELECT * FROM items WHERE (class='Spell') AND owner=".$session['user']['acctid']." AND value1>0 ORDER BY class,name ASC";
            $result=db_query($sql) or die(db_error(LINK));
            if (db_num_rows($result)>0) addnav("Spells & Moves");
            $countrow = db_num_rows($result);
            for ($i=0; $i < $countrow; $i++){
                $row = db_fetch_assoc($result);
                $spellbuff=unserialize($row['buff']);
                addnav("`2".$spellbuff['name']." `0(".$row['value1']."x)","$script?op=fight&skill=zauber&itemid=$row[id]$url");
            }
        }
        // end spells

    }
}

// Superutente? beato te...
if ($session['user']['superuser']>0) $superuser=true;


if ($_GET['op']==""){
   addnav("Azioni");

   output("`#Ti avvicini al Municipio del villaggio, un luogo pericoloso dove anche i piu' scafati degli avventurieri temono avvicinarsi.`n`0");
   output("`#Ti avvicini al portiere che con uno sguardo strano inizia a squadrarti in attesa di sapere che cosa vuoi.`0");

   addnav("E?Voglio Entrare","maniero.php?op=enter");
   addnav("I?Voglio Informazioni","maniero.php?op=info");
   addnav("Uscita");
   addnav("T?Torna al Villaggio","maniero.php?op=esci");
   if ($superuser) {
      $scantinato = e_rand(1,$maxNumeroSotterranei);
      $piano = e_rand(1,$maxNumeroPiani);
      $stanza = e_rand(1,$maxNumeroStanze);
      output("`#`n`nEssendo un Admin il portiere ti dice che il tesoro è allo scantinato - `&{$scantinato}.`0");
      output("`#`nSe invece vuoi farti la trafila dei piani, ti consiglia di dare uno sguardo al '`^Piano `&{$piano} `^Stanza `&{$stanza}`#', buona fortuna :-)`0");
      addnav("Admin Nav");
      $url = encrypt("floor=1&floorf={$scantinato}",true);
      addnav("M?Mandami allo Scantinato","maniero.php?op=scantinato&{$url}");
      $url = encrypt("floor=1&floorf={$piano}&roomf={$stanza}",true);
      addnav("E?Entra e non Farmi Storie","maniero.php?op=explore&{$url}");
   }
}

if ($_GET['op']=="esci"){
   addnav("Azioni");
   output("`#Esci e te ne vai con le pive nel sacco non hai avuto abbastanza coraggio`0");
   output("`# per intraprendere questa dura missione!!`n`n`0");
   output("`#Poker e Maximus da distante ti indicano e ridono a crepapelle...ti hanno visto, ma che sfortuna! `&Hai perso un pò di fama`#!`n");
   if ($session['user']['fama3mesi']<=500){
      $session['user']['fama3mesi']=0;
      $messaggio = "tutti i punti fama";
   } else {
      $session['user']['fama3mesi']-=500;
      $messaggio = "500 punti fama";
   }
   $session['user']['quest']++;
   debuglog("Perde {$messaggio} per aver abbandonato il maniero");
   addnav("Uscita");
   addnav("T?Torna al Villaggio","village.php");
}

if ($_GET['op']=="scappa"){
   addnav("Azioni");

   output("`#Scappi da Poker e risali i sotterranei correndo il più veloce possibile, ");
   output("`#Maximus da distante ti indica e ride a crepapelle...ma che sfortuna ti ha visto! `&Hai perso un pò di fama`#!`n");
   if ($session['user']['fama3mesi']<=500){
      $session['user']['fama3mesi']=0;
      $messaggio = "tutti i punti fama";
   } else {
      $session['user']['fama3mesi']-=500;
      $messaggio = "500 punti fama";
   }
   $session['user']['quest']++;
   debuglog("Perde {$messaggio} per sfuggire a Poker nel maniero");
   addnav("Uscita");
   addnav("T?Torna al Villaggio","village.php");
}

if ($_GET['op']=="info"){
   addnav("Azioni");

   $session['user']['quest']++;
   output("`#Chiedi informazioni al portiere e dopo averti osservato per bene ti risponde che ");
   switch (e_rand(1,10)) {
      case 1:
      case 2:
           $scantinato = e_rand(1,$maxNumeroSotterranei);
           output("`# quello che cerchi si trova nello scantinato, piano - `&{$scantinato}`#. Ti consegna anche un foglio con sopra 5 timbri.");
           $url = encrypt("floor=1&floorf={$scantinato}",true);
           addnav("S?Scendi nello Scantinato","maniero.php?op=scantinato&{$url}");
           break;
      case 3:
      case 4:
      case 5:
           $stanza = e_rand(1,$maxNumeroStanze);
           output("`# l'ufficio informazioni è al '`^Piano `&{$maxNumeroPiani} `^Stanza `&{$stanza}`#'");
           $url = encrypt("floor=1&floorf={$maxNumeroPiani}&roomf={$stanza}",true);
           addnav("E?Entra ed Esplora","maniero.php?op=explore&{$url}");
           break;
      case 6:
      case 7:
      case 8:
      case 9:
      case 10:
           output("`# non ne sa assolutamente nulla.");
           addnav("E?Entra","maniero.php?op=enter");
           break;
   }
   addnav("Uscita");
   addnav("T?Torna al Villaggio","maniero.php?op=esci");

}

if ($_GET['op']=="enter"){
   addnav("Azioni");

   $session['user']['quest']++;
   output("`#Entri nel Municipio del villaggio e ti avvicini alla reception dove c'è una donna goblin con gli occhiali");
   output("`# che senza alzare lo sguardo da `(RafflinGate 3000`#, intenta a leggere un articolo riguardante ");
   switch (e_rand(1,10)) {
      case 1:
           output(" `(un presunto flirt tra `%Seth`( e `5Violet`#,  ");
           break;
      case 2:
           output(" `(le `@Terra dei Draghi`#,  ");
           break;
      case 3:
           output(" `(un presunto flirt tra `!MightyE`( e `#Pegasus`#,  ");
           break;
      case 4:
           output(" `(la birra di `7Cedrik`#,  ");
           break;
      case 5:
           output(" `(le presunte tangenti allo sceriffo`#,  ");
           break;
      case 6:
           output(" `(l'asino della zingara`#,  ");
           break;
      case 7:
           output(" `(una ragazza che si perde sempre in foresta`#,  ");
           break;
      case 8:
           output(" `(L'Uovo `6D'Oro`#,  ");
           break;
      case 9:
           output(" `(L'Uovo `wNero`#,  ");
           break;
      case 10:
           output(" `(il nuovo record del lancio del `#nano`#,  ");
           break;
   }

   switch (e_rand(1,2)) {
      case 1:
           $piano = e_rand(1,$maxNumeroPiani);
           $stanza = e_rand(1,$maxNumeroStanze);
           output(" `#ti porge un fogliettino con scritto  ");
           output("`# '`^Piano `&{$piano} `^Stanza `&{$stanza} `#timbri `\${$maxNumeroTimbri}`#'");
           $url = encrypt("floor=1&floorf={$piano}&roomf={$stanza}",true);
           addnav("V?Vai ai Piani","maniero.php?op=explore&{$url}");
           break;
      case 2:
           output("`#ti risponde in un dialetto strano e l'unica cosa che capisci è `&\"Che stai a di??\"");
           addnav("P?Prova di Nuovo","maniero.php?op=enter");
           break;
   }
   addnav("Uscita");
   addnav("T?Torna al Villaggio","maniero.php?op=esci");

}

if ($_GET['op']=="scantinato"){
   addnav("Azioni");

   $urlValori = decrypt($_GET['link']);

   $azione = $urlValori[az];
   $pianoFinale = $urlValori['floorf'];
   $pianoAttuale = $urlValori['floor'];
   $navigazione=true;

   output("`#Una targa dorata ti ricorda che sei al `^Sotterraneo  -{$pianoAttuale}`n`n");
   if ($azione!="watch") {
      output("`#E' molto buio e la tua vista non riesce a vedere molto bene...");
      output("`#`n`nCosa Fai?");

      if ($superuser) {
         if ($pianoAttuale==$pianoFinale) {
            output("`#`n`nEssendo un Admin, con la tua vista a raggi XYZ, riesci a vedere che questo è lo scantinato esatto`#.");
         } else {
            output("`#`n`nI tuoi superpoteri di Admin ti dicono che sei al piano sbagliato, devi raggiungere lo scantinato - `&{$pianoFinale}`#.");
         }
         output("`#`nIl solito fortunato!! :-D.");
      }

   } else {
      output("`#Cominci ad esplorare lo scantinato ");
      if ($pianoAttuale==$pianoFinale) {
         switch (e_rand(1,4)) {
            case 1:
                 output("`# e in un angolino buoi trovi uno scrigno! Forse la tua ricerca è finalmente terminata!");
                 addnav("A?Apri lo Scrigno!","maniero.php?op=win");
                 $navigazione=false;
                 break;
            case 2:
                 $scantinato = $pianoFinale;
                 while ($scantinato == $pianoFinale) {
                    $scantinato = e_rand(1,$maxNumeroSotterranei);
                 }
                 $pianoFinale = $scantinato;
                 output("`# ma è completamente allagato, trovi solo un cartello che ti rimanda ad un altro scantinato, il - `&{$pianoFinale}`#...");
                 break;
            case 3:
                 $scantinato = $pianoFinale;
                 while ($scantinato == $pianoFinale) {
                    $scantinato = e_rand(1,$maxNumeroSotterranei);
                 }
                 $pianoFinale = $scantinato;
                 output("`# e trovi un branco di topi che stanno seguendo un corso su come salvarsi dai gatti, uno di questi si gira e in squittese ti dice che quello che cerchi è allo scantinato - `&{$pianoFinale}`#...");
                 break;
            case 4:
                 $scantinato = $pianoFinale;
                 while ($scantinato == $pianoFinale) {
                    $scantinato = e_rand(1,$maxNumeroSotterranei);
                 }
                 $pianoFinale = $scantinato;
                 output("`# e dopo alcune ore di ricerca scopri che qualche simpaticone ha cambiato i cartelli mentre esploravi e che il piano giusto e' un altro, il - `&{$pianoFinale}`#, indicato da un cartello appena dietro alla scala che hai usato per arrivare allo scantinato.. averlo saputo prima.. grrr!!!");
                 break;
         }
      } else {
         output("`# ma non trovi nulla che ti interessi...");
      }
   }

   if ($navigazione) {
      if ($pianoAttuale>1 && $pianoAttuale<$maxNumeroSotterranei+1) {
         $stanza = $pianoAttuale - 1;
         $url = encrypt("floor={$stanza}&floorf={$pianoFinale}",true);
         addnav("S?Sali","maniero.php?op=scantinato&{$url}");
      }
      if ($pianoAttuale>0 && $pianoAttuale<$maxNumeroSotterranei) {
         $stanza = $pianoAttuale + 1;
         $url = encrypt("floor={$stanza}&floorf={$pianoFinale}",true);
         addnav("S?Scendi","maniero.php?op=scantinato&{$url}");
      }
      if ($azione!="watch") {
         $url = encrypt("az=watch&floor={$pianoAttuale}&floorf={$pianoFinale}",true);
         addnav("E?Esplora la Penombra","maniero.php?op=scantinato&{$url}");
      }

   }

   addnav("Uscita");
   addnav("T?Torna al Villaggio","maniero.php?op=esci");
}

if ($_GET['op']=="explore"){
   addnav("Azioni");

   $urlValori = decrypt($_GET['link']);

   $hunger = $urlValori['hg'];
   if ($hunger==null)  $hunger=0;

   $hp = $urlValori['hp'];
   if ($hp==null)  $hp=0;

   $timbri = $urlValori['timbri'];
   if ($timbri==null)  $timbri=0;

   $return= $urlValori['ret'];
   if ($return==null)  $return=0;

   $pianoAttuale = $urlValori['floor'];
   $pianoFinale = $urlValori['floorf'];
   $stanzaFinale = $urlValori['roomf'];
   $stanzaEsatta = e_rand(1,$maxNumeroStanzePerPiano);

   if ($return==2) {
      output("`#Torni sano e salvo nel corridoio, forse è meglio stare alla larga da quel piano!`n`n");
   }

   output("`#Una targa dorata ti ricorda che sei al `^Piano {$pianoAttuale}`#, per il momento hai accumulato `\${$timbri} `#timbri.`n`n");
   output("`#Alla tua vista si presenta un lunghissimo corridoio, riesci a contare {$maxNumeroStanzePerPiano} stanze.`n`n");

   if ($return==1) {
      output("`#Torni nel corridoio e sembra che la disposizione delle stanze sia cambiata...una goccia di sudore freddo ti riga il volto, hai paura che non sia così semplice trovare la stanza esatta...`n`n");
   }

   for ($i=0;$i<$maxNumeroStanzePerPiano;$i++){
        $stanza = $i + 1;
        $url = encrypt("room={$stanza}&floor={$pianoAttuale}&floorf={$pianoFinale}&roomf={$stanzaFinale}&roome={$stanzaEsatta}&timbri={$timbri}&hg={$hunger}&hp={$hp}",true);
        if ($stanza<10) {
           addnav("{$stanza}?Stanza N {$stanza}","maniero.php?op=watch&{$url}");
        } else {
           addnav("Stanza N {$stanza}","maniero.php?op=watch&{$url}");
        }
   }

   if ($pianoAttuale==$maxNumeroPiani) {
       output("`#Sei all'ultimo piano ma noti che le scale salgono ancora, le uniche lettere che si leggono su un cartello consumato sono `(\"`bPIANO F`ba.`bNTA`b.`bS`b.`bM`b.`bA`b.`bGO`b.`bRIC`b.`bO`b.\"`#...`n`n");
       $url = encrypt("floor={$pianoAttuale}&floorf={$pianoFinale}&roomf={$stanzaFinale}&roome={$stanzaEsatta}&timbri={$timbri}&hg={$hunger}&hp={$hp}",true);
       addnav("S?Sali Ancora","maniero.php?op=fantasmagorico&{$url}");
   }

   if ($superuser) {
      if ($pianoAttuale==$pianoFinale) {
         output("`#Essendo un Admin, con la tua vista a raggi XYZ, riesci a vedere che la stanza esatta è la Numero `&{$stanzaEsatta}`#.`n");
      } else {
         output("`#I tuoi superpoteri di Admin ti dicono che sei al piano sbagliato, devi raggiungere il `^Piano `&{$pianoFinale}`#.`n");
      }
      output("`#Il solito fortunato!! :-D.`n`n");
   }

   if ($pianoAttuale>0 && $pianoAttuale<$maxNumeroPiani) {
      $piano = $pianoAttuale + 1;
      $url = encrypt("floor={$piano}&floorf={$pianoFinale}&roomf={$stanzaFinale}&roome={$stanzaEsatta}&timbri={$timbri}&hg={$hunger}&hp={$hp}",true);
      addnav("S?Sali","maniero.php?op=explore&{$url}");
   }
   if ($pianoAttuale>1 && $pianoAttuale<$maxNumeroPiani+1) {
      $piano = $pianoAttuale - 1;
      $url = encrypt("floor={$piano}&floorf={$pianoFinale}&roomf={$stanzaFinale}&roome={$stanzaEsatta}&timbri={$timbri}&hg={$hunger}&hp={$hp}",true);
      addnav("S?Scendi","maniero.php?op=explore&{$url}");
   }

   addnav("Uscita");
   addnav("T?Torna al Villaggio","maniero.php?op=esci");

   output("`#Cosa fai?");

}

if ($_GET['op']=="fantasmagorico"){
   addnav("Azioni");
   $urlValori = decrypt($_GET['link']);

   $hunger = $urlValori['hg'];
   if ($hunger==null)  $hunger=0;

   $hp = $urlValori['hp'];
   if ($hp==null)  $hp=0;

   $timbri = $urlValori['timbri'];
   if ($timbri==null)  $timbri=0;

   $pianoAttuale = $urlValori['floor'];
   $pianoFinale = $urlValori['floorf'];
   $stanzaFinale = $urlValori['roomf'];
   $stanzaAttuale = $urlValori['room'];
   $stanzaEsatta = $urlValori['roome'];

   output("`#Incuriosito sali ancora le scale, la vista è spettacolare, sembra che questo piano non esista nella realtà, tutto intorno a te sembra etereo.`n`n");
   output("`#Improvvisamente l'aria intorno a te comincia a riscaldarsi, ti giri di scatto e vedi svolazzare a mezz'aria un `b`!Drago Blu`b`#");

   if (e_rand(1,10)==1) {
       output("`# che ti si para davanti...Forse non è stata una buona idea quella di esplorare questo piano, sei costrett".($session[user][sex]?"a":"o")." ad affrontare una creatura molto pericolosa e con poche possibilità di vittoria.`n`n");
       $name = "`!Drago Blu`0";
       $arma = "`^Fiammata Ustionante`0";
       $message = "`@{goodguy} `3ha incontrato il {badguy}`3 e ne è uscit".($session[user][sex]?"a":"o")." cott".($session[user][sex]?"a":"o")." con la salsa barbecue !!!";

       $vita = round($session['user']['maxhitpoints']*1.5,0);
       $attacco = $session['user']['attack']+5;
       $difesa = $session['user']['defence']+5;

       $exp_gain=$session['user']['level']*150;
       $gem_gain = rand(1,4);
       $gold_gain = rand(300,400)*$session['user']['level'];

       $badguy = array(
               "creaturename"=>$name,
               "creaturelevel"=>$session['user']['level']+2,
               "creatureweapon"=>$arma,
               "creatureattack"=>$attacco,
               "creaturedefense"=>$difesa,
               "creaturehealth"=>$vita,
               "diddamage"=>0,
               "message"=>$message,
               "expgain"=>$exp_gain,
               "goldgain"=>$gold_gain,
               "gemgain"=>$gem_gain,
               "isdrago"=>true
       );

       $session['user']['badguy']=createstring($badguy);
       $url = encrypt("operazione=explore&floor={$pianoAttuale}&floorf={$pianoFinale}&roomf={$stanzaFinale}&timbri={$timbri}&hg={$hunger}&hp={$hp}&ret=2",false);
       $_GET['link'] = $url;
       $_GET['op']="fight";
   } else {
       output("`#, spaventat".($session[user][sex]?"a":"o")." cominci a correre e a correre, ma il piano non esiste e di colpo inizi a precipitare e l'ultimo tuo ricordo e' di una botte di pece nella quale ");
       output("`#ti infili in tuffo acrobatico e migliaia di piume che ti coprono dalla testa ai piedi.`n`n");
       output("`#Non sei riuscit".($session[user][sex]?"a":"o")." a vincere il palazzo maledetto e sei anche diventat".($session[user][sex]?"a":"o")." lo zimbello di turno, `&hai perso un bel pò di fama`#!`n");
       output("`#`\$Perdi qualche punto ferita nella caduta`#.`n");
       output("`#`5A causa del tuo aspetto perdi alcuni punti fascino`#.`n");
       output("`#`^Inoltre noti che mentre eri svenut".($session[user][sex]?"a":"o")." qualcuno ha pensato di alleggerirti dei tuoi averi`#.`n`n");
       output("`#Poker e Maximus da distante ti indicano e ridono a crepapelle...ma che sfortuna!`n");
       output("`#Ti alzi, raccogli un pezzo di carta da terra, e scopri il cartello del piano voleva dire \"`bPIANO FA`bi, i`bN TA`bnti `bS`bono stati `bM`bangi`bA`bti dal dra`bGO`b pe`bRIC`bolos`bO`b\"!`n");
       addnav("Uscita");
       addnav("T?Torna al Villaggio","village.php");
       $messaggio = "";
       if ($session['user']['hitpoints']<=50){
          $session['user']['hitpoints']=1;
       } else {
          $session['user']['hitpoints']-=50;
       }
       if ($session['user']['charm']<=20){
          $session['user']['charm']=0;
          $messaggio = "tutti (".$session['user']['charm'].") i punti fascino ";
       } else {
          $session['user']['charm']-=20;
          $messaggio = "20 punti fascino ";
       }
       $session['user']['gold']=0;
       if ($session['user']['fama3mesi']<=5000){
          if ($messaggio != ""){
              $messaggio .= "e ".$session['user']['fama3mesi']." punti fama ";
          }else{
              $messaggio = $session['user']['fama3mesi']." punti fama ";
          }
          $session['user']['fama3mesi']=0;
       } else {
          if ($messaggio != ""){
              $messaggio .= "e 5000 punti fama ";
          }else{
              $messaggio = "5000 punti fama ";
          }
          $session['user']['fama3mesi']-=5000;
       }
       if ($messaggio != "") debuglog("Perde {$messaggio} per aver esplorato l'ultimo piano del maniero");
   }
}

if ($_GET['op']=="win"){
    addnav("Azioni");
    output("`#Sollevi da terra lo scrigno con le lacrime agli occhi, sei stremat".($session[user][sex]?"a":"o")." dalla fatica fatta in questo maledetto posto e vuoi ");
    output("`#assaporare fino all'ultimo questo magico momento.`n");
    switch (e_rand(1,4)) {
           case 1: case 2: case 3:
                $session['user']['quest'] = 3;
                output("`#Apri lo scrigno e finalmente i tuoi sforzi sono stati premiati!`n`n");
                switch (e_rand(1,3)) {
                       case 1:
                            $oro = e_rand(500,1500)*$session['user']['level'];
                            $gemme = e_rand(10,20);
                            output("`#Al suo interno trovi `^$oro Monete D'Oro `#e `&$gemme Gemme`#!!`n`n");
                            $session['user']['gold'] += $oro;
                            $session['user']['gems'] += $gemme;
                            debuglog("trova $oro oro e $gemme gemme finendo il maniero.");
                            break;
                       case 2:
                            $reward = e_rand(intval($session['user']['maxhitpoints'] * 0.1),intval($session['user']['maxhitpoints'] * 0.3));
                            if ($reward > 60) {
                               $reward=60;
                            }
                            if ($reward < 20) {
                               $reward=20;
                            }
                            output("`#Al suo interno trovi una `&Pozione della Vita Extra`# che ti fa guadagnare $reward HP Permanenti!!`n`n");
                            $session['user']['maxhitpoints']=$session['user']['maxhitpoints']+$reward;
                            debuglog("guadagna $reward HP Permanenti finendo il maniero");
                            break;
                       case 3:
                            $reward = e_rand(2,4);
                            if (e_rand(1,2)==1) {
                               output("`#Al suo interno trovi una `&Pozione della Potenza Extra`# ti fa guadagnare $reward Punti di Attacco Permanenti!!`n`n");
                               debuglog("guadagna $reward attacco permanente per aver finito il maniero");
                               $session['user']['attack']+=$reward;
                               $session['user']['bonusattack']+=$reward;
                            }  else {
                               output("`#Al suo interno trovi una `&Pozione della Pellaccia Extra`# che ti fa guadagnare $reward Punti Difesa Permanenti!!`n`n");
                               debuglog("guadagna $reward difesa permanente per aver finito il maniero");
                               $session['user']['bonusdefence']+=$reward;
                               $session['user']['defence']+=$reward;
                            }
                            break;
                }
                addnav("Uscita");
                addnav("Torna al Villaggio","village.php");
                addnews("{$session['user']['name']} `@è uscit".($session[user][sex]?"a":"o")." indenne dal `^Maniero Burocratico Maledetto `@!!!");
                break;
           case 4:
                $session['user']['quest']++;
                output("`#Stai per metterti ad esultare quando qualcuno ti tocca sulla spalla, ti giri e quasi svieni quando riconosci l'innominabile ");
                output("`#portatore di Malasorte che sogghignando ti dice: ");
                output("`&\"`iOh ma chi si vede, anche tu qui? Ma che brav".($session[user][sex]?"a":"o")." tutti e 5 i timbri, ma allora sei fortunat".($session[user][sex]?"a":"o")." e questo non va bene! Che ne dici di verificare quanto sei veramente fortunat".($session[user][sex]?"a":"o")."?`i\"`n");
                output("`#Detto questo tira fuori i suoi famosi `\$'Dadi della Malasorte'`#... Scoraggiat".($session[user][sex]?"a":"o")." cominci a piangere...");
                addnav("Accetta di Giocare","maniero.php?op=gioca");
                addnav("Rifiuta di Giocare","maniero.php?op=nongioca");
                addnav("Scappa!","maniero.php?op=scappa");
                break;
    }
}

if ($_GET['op']=="gioca"){
    output("`%`\$Poker`% ti da in mano un dado e tu, ancora intimorit".($session[user][sex]?"a":"o")." dalla sua fama e non riuscendo a biascicare una sola parola,
    ti ritrovi costrett".($session[user][sex]?"a":"o")." a tirare il dado !!`n Dopo aver rotolato per terra il dado si ferma e ... ");
    switch (e_rand(0,6)){
        case 0:
        $flagstone = 0;
        $id=$session['user']['acctid'];
        $sqlzk="SELECT * FROM pietre WHERE owner=$id";
        $resultzk = db_query($sqlzk) or die(db_error(LINK));
        if (db_num_rows($resultzk) != 0){
           $pot = db_fetch_assoc($resultzk);
           $flagstone=$pot['pietra'];
        }
        if ($flagstone > 0){
           $sqlk = "DELETE FROM pietre WHERE pietra = '$flagstone' AND owner = '$id'";
           $resultk = db_query($sqlk) or die(db_error(LINK));
        }
        $sqlr="SELECT pietra,owner FROM pietre WHERE pietra = '1'";
        $resultr = db_query($sqlr) or die(db_error(LINK));
        $rowr = db_fetch_assoc($resultr);
        if (db_num_rows($resultr) == 0) {
            $sqlp="INSERT INTO pietre (pietra,owner) VALUES ('1','$id')";
            db_query($sqlp);
        }else{
            $account=$rowr['owner'];
            $sqlpr="UPDATE pietre SET owner = '$id' WHERE pietra = '1'";
            db_query($sqlpr);
            $mailmessage = "`^".$session['user']['name']." `@ha incontrato `&Poker`@ che ha pensato bene di dare a lui la tua  {$pietre[1]} `@!! È il tuo giorno fortunato.";
            systemmail($account,"`2La tua pietra è ora nella mani di `@".$session['user']['name']." `2",$mailmessage);
        }
        $session['user']['turns']-=1;
        output("`b`#INCREDIBILE !!!`b `%`n`nIl dado si è infilato nella tana di un topolino!!!
        È un segno del destino, e `\$Poker`%, il cui secondo nome è `^\"L'interprete dei segni del Destino\"`% sa perfettamente cosa
        deve fare in una situazione del genere. Compie un gesto scaramantico e nelle sue mani magicamente compare
        una `\$Pietra`% ... la `bSUA`b pietra, e prima che tu possa dire o fare qualcosa te la infila nella tasca.`n
        Sei diventato l".($session[user][sex]?"a":"o")." sfortunat".($session[user][sex]?"a":"o")." proprietari".($session[user][sex]?"a":"o")." della $pietre[1] !!!!");
        debuglog("riceve la Pietra di Poker direttamente dal suo creatore (nel maniero)");
        addnav("Uscita");
        addnav("T?Torna al Villaggio","village.php");
        break;
        case 1:
        output("`% mostra sulla faccia superiore il numero `^1`%. Non è un buon segno. Poker osserva
        la cifra sul dado e dice \"`@`iSono spiacente per te car" . ($session['user']['sex']?"a":"o") . "
        ".$session['user']['name'].", ma con questo risultato perdi 2 combattimenti`i`nScornat".($session[user][sex]?"a":"o")." e con le pive nel sacco
        ti allontani da questo posto infernale.");
        $session['user']['turns']-=2;
        if ($session['user']['turns'] < 0) $session['user']['turns']=0;
        debuglog("perde 2 turni foresta con Poker (nel maniero)");
        addnav("Uscita");
        addnav("T?Torna al Villaggio","village.php");
        break;
        case 2:
        output("`% mostra sulla faccia superiore il numero `^2`%. Non è un buon segno. `\$Poker`% osserva
        la cifra sul dado e dice \"`@`iSono spiacente per te car".($session['user']['sex']?"a":"o")." ".$session['user']['name'].", ma
        con questo risultato perdi 1 HP permanente !!`i\"`%. Detto ciò ti congeda e ti invita a riprendere la tua
        strada. `nScornat".($session[user][sex]?"a":"o")." e con le pive nel sacco ti allontani da questo posto maledetto.");
        $session['user']['maxhitpoints']-=1;
        $session['user']['hitpoints']-=1;
        debuglog("perde 1 HP permanente da Poker (nel maniero)");
        addnav("Uscita");
        addnav("T?Torna al Villaggio","village.php");
        break;
        case 3:
        output("`% mostra sulla faccia superiore il numero `^3`%. Chissà se è un bene. `\$Poker`% osserva
        la cifra sul dado e dice \"`@`iMhhhhhh ... buon per te car".($session['user']['sex']?"a":"o")." ".$session['user']['name'].",
        con questo risultato guadagni 1 HP permanente !!`i\"`%. Detto ciò ti congeda e ti invita a riprendere la tua
        strada. `nFelice per il premio ricevuto intoni canti di gioia e ti allontani da questo posto.");
        $session['user']['maxhitpoints']+=1;
        $session['user']['hitpoints']+=1;
        debuglog("guadagna 1 HP permanente da Poker (nel maniero)");
        addnav("Uscita");
        addnav("T?Torna al Villaggio","village.php");
        break;
        case 4:
        output("`% mostra sulla faccia superiore il numero `^4`%. Chissà se è un bene. `\$Poker`% osserva
        la cifra sul dado e dice \"`@`iMhhhhhh ... buon per te car".($session['user']['sex']?"a":"o")." ".$session['user']['name'].",
        con questo risultato guadagni 3 combattimenti in foresta !!`i\"`%. Detto ciò ti congeda e ti invita a riprendere la tua
        strada. `nFelice per il premio ricevuto intoni canti di gioia e ti allontani da questo posto.");
        $session['user']['turns']+=3;
        debuglog("guadagna 3 turni foresta da Poker (nel maniero)");
        addnav("Uscita");
        addnav("T?Torna al Villaggio","village.php");
        break;
        case 5:
        $oro=round(e_rand(100,200)*$session['user']['level']);
        output("`% mostra sulla faccia superiore il numero `^5`%. Chissà se è un bene. `\$Poker`% osserva
        la cifra sul dado e dice \"`@`iMhhhhhh ... buon per te car".($session['user']['sex']?"a":"o")." ".$session['user']['name'].",
        con questo risultato guadagni $oro pezzi d'oro !!`i\"`%. Detto ciò ti congeda e ti invita a riprendere la tua
        strada. `nFelice per il premio ricevuto intoni canti di gioia e ti allontani da questo posto.");
        $session['user']['gold']+=$oro;
        debuglog("guadagna $oro oro da Poker (nel maniero)");
        addnav("Uscita");
        addnav("T?Torna al Villaggio","village.php");
        break;
        case 6:
        $gemme=e_rand(1,3);
        output("`% mostra sulla faccia superiore il numero `^6`%. Chissà se è un bene. `\$Poker`% osserva
        la cifra sul dado e dice \"`@`iMhhhhhh ... buon per te car".($session['user']['sex']?"a":"o")." ".$session['user']['name'].",
        con questo risultato guadagni $gemme gemme !!`i\"`%. Detto ciò ti congeda e ti invita a riprendere la tua
        strada. `nFelice per il premio ricevuto intoni canti di gioia e ti allontani da questo posto.");
        debuglog("guadagna $gemme gemme da Poker (nel maniero)");
        addnav("Uscita");
        addnav("T?Torna al Villaggio","village.php");
        $session['user']['gems']+=$gemme;
        break;
        }
}

if ($_GET['op']=="nongioca"){
   output("`&\"`iAhi ahi ahi ".$session['user']['name']." `#... E io che volevo essere gentile con te! E allora tieni questo potente amuleto in memoria
             di me Bhuahuahuah!!!`i\"`n`#Detto questo compie un gesto scaramantico e nelle sue mani magicamente compare una `\$Pietra`# ...
             la `bSUA`b pietra, e prima che tu possa dire o fare qualcosa te la infila nella tasca.`n
             Sei diventat".($session[user][sex]?"a":"o")." l".($session[user][sex]?"a":"o")." sfortunat".($session[user][sex]?"a":"o")." proprietari".($session[user][sex]?"a":"o")." della $pietre[1] !!!!");
   $flagstone = 0;
   $id=$session['user']['acctid'];
   $sqlzk="SELECT * FROM pietre WHERE owner=$id";
   $resultzk = db_query($sqlzk) or die(db_error(LINK));
   if (db_num_rows($resultzk) != 0){
      $pot = db_fetch_assoc($resultzk);
      $flagstone=$pot['pietra'];
   }
   if ($flagstone > 0){
      $sqlk = "DELETE FROM pietre WHERE pietra = '$flagstone' AND owner = '$id'";
      $resultk = db_query($sqlk) or die(db_error(LINK));
   }
   $sqlr="SELECT pietra,owner FROM pietre WHERE pietra = '1'";
   $resultr = db_query($sqlr) or die(db_error(LINK));
   $rowr = db_fetch_assoc($resultr);
   if (db_num_rows($resultr) == 0) {
       $sqlp="INSERT INTO pietre (pietra,owner) VALUES ('1','$id')";
       db_query($sqlp);
   }else{
       $account=$rowr['owner'];
       $sqlpr="UPDATE pietre SET owner = '$id' WHERE pietra = '1'";
       db_query($sqlpr);
       $mailmessage = "`^".$session['user']['name']." `@ha incontrato `&Poker`@ che ha pensato bene di dare a lui la tua  {$pietre[1]} `@!! È il tuo giorno fortunato.";
       systemmail($account,"`2La tua pietra è ora nella mani di `@".$session['user']['name']." `2",$mailmessage);
   }
   debuglog("riceve la Pietra di Poker direttamente dal suo creatore (nel maniero)");
   addnav("Uscita");
   addnav("T?Torna al Villaggio","village.php");
}

if ($_GET['op']=="watch"){
   addnav("Azioni");

   $urlValori = decrypt($_GET['link']);

   $hunger = $urlValori['hg'];
   if ($hunger==null)  $hunger=0;

   $hp = $urlValori['hp'];
   if ($hp==null)  $hp=0;

   $timbri = $urlValori['timbri'];
   if ($timbri==null)  $timbri=0;

   $pianoAttuale = $urlValori['floor'];
   $pianoFinale = $urlValori['floorf'];
   $stanzaFinale = $urlValori['roomf'];
   $stanzaAttuale = $urlValori['room'];
   $stanzaEsatta = $urlValori['roome'];

   if ($pianoAttuale==$pianoFinale) {
      $numero = $stanzaFinale;
      if ($stanzaAttuale!=$stanzaEsatta){
          while ($numero == $stanzaFinale) {
             $numero = e_rand(1,$maxNumeroStanze);
          }
      }
   } else {
      $numero = e_rand(1,$maxNumeroStanze);
   }

   output("`#Bussi ed entri nella `&{$stanzaAttuale}`#a Stanza che riporta il numero `&{$numero}`#.");

   if ($pianoAttuale==$pianoFinale && $stanzaAttuale==$stanzaEsatta){
         output("`#`nFinalmente è la stanza esatta!");
         switch (e_rand(1,2)) {
            case 1:
                 output("`#`nUna simpatica statua in gesso ti si para davanti e una scritta sotto recita:");
                 output("`&`n\"Battere forte il foglio timbri sul timbro inserito nell' apposito pertugio\"");
                 output("`#`nTi ci vuole un ora ma dopo aver trovato e mangiato formaggi e salumi, fatto il pieno di medicine e aver ammirato lo show di un simpatico topolino cantante capisci quale è il timbro giusto e ottieni l'agognata timbratura!");
                 $timbri++;

                 if ($hunger>1) {
                    $hunger = intval((floor($hunger / 2)));
                    $session['user']['hunger'] = $session['user']['hunger'] - $hunger;
                 }

                 if ($hp>1) {
                    $hp = e_rand(1,intval(floor($hp/2)));
                    $session['user']['hitpoints'] = $session['user']['hitpoints'] + $hp;
                 }

                 if ($timbri<$maxNumeroTimbri) {
                    $piano = e_rand(1,$maxNumeroPiani);
                    $stanza = e_rand(1,$maxNumeroStanze);
                    output("`#`nVicino vedi un nuovo foglio con un altro numero di stanza e di piano... ");
                    output("`#'`^Piano `&{$piano} `^Stanza `&{$stanza}`#'");
                    $url = encrypt("floor={$pianoAttuale}&floorf={$piano}&roomf={$stanza}&timbri={$timbri}&hg={$hunger}&hp={$hp}",true);
                    addnav("C?Torna al Corridoio","maniero.php?op=explore&{$url}");
                 } else {
                    output("`&`n\"Complimenti! Hai raccolto tutti i timbri!\"");
                    $scantinato = e_rand(1,$maxNumeroSotterranei);
                    output("`#`nQuello che cerchi si trova nello scantinato, piano - `&{$scantinato}`#.");
                    $url = encrypt("floor=1&floorf={$scantinato}",true);
                    addnav("S?Scendi nello Scantinato","maniero.php?op=scantinato&{$url}");
                 }
                 break;
            case 2:
                 $piano = e_rand(1,$maxNumeroPiani);
                 $stanza = $stanzaEsatta;
                 while ($stanza == $stanzaEsatta) {
                       $stanza = e_rand(1,$maxNumeroStanze);
                 }
                 output("`#`nTi avvicini e porgi il foglio all'impiegato di turno:");
                 output("`&`n\"Ma chi le ha detto questo...nooo era ieri che si andava li...e solo quando siamo pari...ma oggi ci sono solo io e altri ma non dispari...ma neanche pari...\"");
                 output("`#`nDopo 6 ore di discorsi inutili ti molla un foglio in mano con scritto '`^Piano `&{$piano} `^Stanza `&{$stanza}`#'...incominci ad avere paura...");
                 $url = encrypt("floor={$pianoAttuale}&floorf={$piano}&roomf={$stanza}&timbri={$timbri}&hg={$hunger}&hp={$hp}&ret=1",true);
                 addnav("C?Torna al Corridoio","maniero.php?op=explore&{$url}");
                 break;
         }
   } else {
         switch (e_rand(1,5)) {
            case 1:
                 output("`#`nTrovi alcuni impiegati che sono in pausa pranzo, intenti a mangiarsi prelibatezze....");
                 output("`#`nsigh il profumino che arriva ti fa venire l'acquilina in bocca… con la testa bassa continui per la tua strada...");
                 $session['user']['hunger']++;
                 $hunger++;
                 break;
            case 2:
                 output("`#`nTrovi alcune impiegate che parlano dei piu' incredibili pettegolezzi del villaggio.");
                 output("`#`nCerchi piu' volte di attirare l'attenzione ottenendo solo una timbrata su un dito durante un discorso piu' acceso di altri.");
                 $hplose = e_rand(1,3);
                 $hp = $hp + $hplose;
                 $session['user']['hitpoints'] = $session['user']['hitpoints'] - $hplose;
                 if ($session['user']['hitpoints']>0) {
                     output("`#`nPeccato sia il timbro sbagliato, con le pive nel sacco continui la tua ricerca...");
                 } else {
                     output("`#`nPeccato che il timbro ti abbia dato il colpo di grazia! Non è stata una morte molto onorevole la tua...");
                     debuglog("Muore nel maniero timbrato da un impiegata");
                     addnews($session['user']['name']." `#è stat".($session[user][sex]?"a":"o")." trovat".($session[user][sex]?"a":"o")." mort".($session[user][sex]?"a":"o")." con un timbro in fronte!");
                 }
                 break;
            case 3:
                 output("`#`nAppena ti avvicini un tipo dietro la porta ti blocca.");
                 output("`&`n\"Cosa vuole Chi la manda vuole un timbro i timbri si pagano.. che crede che vuole ecco dia dia.. non era questo il timbro? Doveva spiegarsi meglio mi spiace arrivederci\"");
                 output("`#`nSbam...non sai se e' la tua testa contro il muro o il rumore della porta quando la chiude di scatto...uhmm forse sei stato raggirato...");
                 if ($session['user']['gold']>0) {
                    $session['user']['gold']--;
                    debuglog("perde una moneta d'oro nel maniero");
                 }
                 break;
            case 4:
                 $name = "";
                 $arma = "";
                 switch (e_rand(1,11)) {
                     case 1:
                          $name = "`2Impiegato Impazzito";
                          $arma = "`2Spillatrice Rotta";
                          $message = "`3{badguy} `3ha spillato su `@{goodguy}`3 il suo certificato di morte !!!";
                          break;
                     case 2:
                          $name = "`2Dirigente Annoiato";
                          $arma = "`2Contratto da Firmare";
                          $message = "`3{badguy} `3ha firmato un contratto con Ramius sull'anima di `@{goodguy}`3 !!!";
                          break;
                     case 3:
                          $name = "`2Funzionario Irritato";
                          $arma = "`2Pratica da Sbrigare";
                          $message = "`3{badguy} `3ha annullato l'assicurazione sulla vita a `@{goodguy}`3 !!!";
                          break;
                     case 4:
                          $name = "`2Donna delle Pulizie";
                          $arma = "`2Scopettone Sporco";
                          $message = "`@{goodguy} `3è stat".($session[user][sex]?"a":"o")." punit".($session[user][sex]?"a":"o")." da {badguy} `3per aver sporcato il bagno !!!";
                          break;
                     case 5:
                          $name = "`2Impiegata Stakanovista";
                          $arma = "`2Foglio Presenze";
                          $message = "`@{goodguy} `3si e' vist".($session[user][sex]?"a":"o")." dimezzare le ferie da {badguy}`3 !!!";
                          break;
                     case 6:
                          $name = "`2Notaio Severo";
                          $arma = "`2Parcella Esosa";
                          $message = "`3{badguy} `3ha timbrato tutte le dita a `@{goodguy}`3 !!!";
                          break;
                     case 7:
                          $name = "`2Avvocato del Diavolo";
                          $arma = "`2Pentola senza Coperchio";
                          $message = "`3{badguy} `3ha rinviato a giudizio `@{goodguy}`3 per guida di cavallo ubriaco !!!";
                          break;
                     case 8:
                          $name = "`2Fiscalista";
                          $arma = "`2Note Fiscali";
                          $message = "`3{badguy} `3ha fiscalizzato `@{goodguy}`3 !!!";
                          break;
                     case 9:
                          $name = "`2Burocrate";
                          $arma = "`2Cavilli legali";
                          $message = "`3{badguy} `3ha eletto `@{goodguy}`3 \"Mort".($session[user][sex]?"a":"o")." dell'anno\" !!!";
                          break;
                     case 10:
                          $name = "`2Portinaia Chiacchierona";
                          $arma = "`2Pettegolezzi";
                          $message = "`@{goodguy} `3e' finit".($session[user][sex]?"a":"o")." su Rafflingate 3000 Grazie alla `3{badguy}`3 !!!";
                          break;
                     case 11:
                          $name = "`2Macchinetta del Caffe";
                          $arma = "`2Resto non Disponibile";
                          $message = "`@{goodguy} `3e' stat".($session[user][sex]?"a":"o")." mess".($session[user][sex]?"a":"o")." fuori servizio da una `3{badguy}`3 !!!";
                          break;
                 }

                 $reinc = $session['user']['reincarna'];
                 $livello = $session['user']['level'];

                 $vita = (10 * $livello) + (10 * $reinc);
                 $attacco = (1 * $livello) + (1 * $reinc);
                 $difesa = (1 * $livello) + (1 * $reinc);

                 $exp_gain = e_rand(1,5);
                 $gold_gain = e_rand(1,5);

                 $badguy = array(
                         "creaturename"=>$name,
                         "creaturelevel"=>1,
                         "creatureweapon"=>$arma,
                         "creatureattack"=>$attacco,
                         "creaturedefense"=>$difesa,
                         "creaturehealth"=>$vita,
                         "diddamage"=>0,
                         "message"=>$message,
                         "expgain"=>$exp_gain,
                         "goldgain"=>$gold_gain,
                         "isdrago"=>false
                 );

                 $session['user']['badguy']=createstring($badguy);

                 $url = encrypt("operazione=explore&floor={$pianoAttuale}&floorf={$pianoFinale}&roomf={$stanzaFinale}&timbri={$timbri}&hg={$hunger}&hp={$hp}&ret=1",false);

                 $_GET['link'] = $url;
                 $_GET['op']="fight";

                 output("`#`nAccidenti hai disturbato {$name}`#!! Agita in aria {$arma}`# e sei costrett".($session[user][sex]?"a":"o")." ad affrontarlo!!`n`n");
                 break;
            case 5:
                 output("`#`nTrovi Sook sbragato su una sedia con i piedi sulla scrivania che sorseggia beatamente un barile di birra.");
                 output("`#`nQuando vede aprire la porta lancia verso di essa ");

                 switch (e_rand(1,2)) {
                     case 1:
                          output("`#una bottiglia vuota che ti prende in piena fronte...");
                          $hplose = e_rand(1,3);
                          if ($session['user']['hitpoints']>$hplose) {
                             $hp = $hp + $hplose;
                             $session['user']['hitpoints'] = $session['user']['hitpoints'] - $hplose;
                          } else {
                             $session['user']['charm']-=1;
                          }
                          break;
                     case 2:
                          output("`#un borsellino di cuoio che prontamente riesci ad afferrare, ");
                          output("`#Sook ti dice di andargli a comperare qualche altra birra...");
                          output("`#`nIl borsello contiene `^".$session['user']['level']." Monete D'oro`#.");
                          $session['user']['gold']+=$session['user']['level'];
                          debuglog("guadagna ".$session['user']['level']." oro nel maniero");
                          break;
                 }
                 break;
         }
         if ($session['user']['hitpoints']>0 && $_GET['op']!="fight") {
            $url = encrypt("floor={$pianoAttuale}&floorf={$pianoFinale}&roomf={$stanzaFinale}&timbri={$timbri}&hg={$hunger}&hp={$hp}&ret=1",true);
            addnav("C?Torna al Corridoio","maniero.php?op=explore&{$url}");
         }
   }
   if ($_GET['op']!="fight") {
      if ($session['user']['hitpoints']>0) {
         addnav("Uscita");
         addnav("T?Torna al Villaggio","maniero.php?op=esci");
      } else {
         addnav("T?Terra delle Ombre","shades.php");
      }
   }

}

if ($_GET['op']=="run"){
   $badguy = createarray($session['user']['badguy']);
   $nome = $badguy['creaturename'];
   output("`n`n`c`b`\$Non sei riuscit".($session[user][sex]?"a":"o")." a sfuggire a {$nome}!`0`b`c");
   $_GET['op']="fight";
}
if ($_GET['op']=="fight"){
    $battle=true;
}
if ($battle) {
    $link = $_GET['link'];

    include("battle.php");
    if ($victory){
            $badguy = createarray($session['user']['badguy']);
            $nome = $badguy['creaturename'];
            $exp_gain = $badguy['expgain'];
            $gold_gain = $badguy['goldgain'];
            $isdrago = $badguy['isdrago'];
            output("`n`3Dopo un duro scontro, sei riuscito ad avere la meglio su {$nome}`3.`n");
            output("`^Guadagni $exp_gain punti esperienza per la battaglia disputata.`n");
            if ($isdrago){
                $gem_gain = $badguy['gemgain'];
                output("All'interno del ventre del drago trovi anche $gem_gain gemme e $gold_gain pezzi d'oro !!!");
            }else{
                $gem_gain=0;
                output("Sbirci nelle sue tasche e trovi anche $gold_gain pezzi d'oro !!!");
            }

            debuglog("guadagna $exp_gain exp, $gem_gain gemme e $gold_gain oro nel maniero per un combattimento");
            $session['user']['gems']+=$gem_gain;
            $session['user']['gold']+=$gold_gain;
            $session['user']['experience']+=$exp_gain;

            $urlValori = decrypt($link);
            $chiavi = array_keys($urlValori);
            $url = "";
            $op = "op=";
            for ($i=0; $i < count($chiavi); $i++) {
                if ($chiavi[$i]!="operazione") {
                   $url = $url.$chiavi[$i]."=";
                   $valore = $urlValori[$chiavi[$i]];
                   $url = $url.$valore."&";
                } else {
                   $op = $op.$urlValori[$chiavi[$i]];
                }
            }
            $lunghezza=(strlen($url)-1);
            $url = substr($url,0,$lunghezza);
            $url=encrypt($url,true);
            $session['user']['badguy']="";
            $badguy=array();
            addnav("C?Torna al Corridoio","maniero.php?{$op}&{$url}");
        } elseif ($defeat){
            $badguy = createarray($session['user']['badguy']);
            $nome = $badguy['creaturename'];
            $isdrago = $badguy['isdrago'];
            $testo="è stat".($session[user][sex]?"a":"o")." uccis".($session[user][sex]?"a":"o")." da {$nome}";
            if ($isdrago){
                output("`n`7Che peccato!! Sei stat".($session[user][sex]?"a":"o")." battut".($session[user][sex]?"a":"o")." dal {$nome}`7 !!`n`n`7Perdi il `^`b10%`b`7 della tua esperienza.`n");
            }else{
                output("`n`7Che vergogna!! Sei stat".($session[user][sex]?"a":"o")." battut".($session[user][sex]?"a":"o")." da {$nome}`7 !!`n`n`7Perdi il `^`b10%`b`7 della tua esperienza.`n");
            }

            output("`&Inoltre, ");
            $gem_loss = rand(1,4);

            if ($gem_loss >= $session['user']['gems']) {
                output("`&hai perso `bTUTTE`b le tue gemme nella battaglia, assieme a tutto il tuo oro!`n`n");
                $testo=$testo.", perde ".$session['user']['gems']." gemme";
                $session['user']['gems']=0;
            } else {
                output("hai perso `b$gem_loss`b delle tue gemme nello scontro, assieme a tutto il tuo oro!`n`n");
                $testo=$testo.", perde $gem_loss gemme";
                $session['user']['gems']-=$gem_loss;
            }
            addnav("`^Notizie Giornaliere","news.php");
            $gold_loss = $session['user']['gold'];
            debuglog("$testo, $gold_loss oro e il 10% di exp nel maniero");
            $session['user']['alive']=false;
            $session['user']['gold']=0;
            $session['user']['hitpoints']=0;
            $session['user']['experience']=round($session['user']['experience']*.9,0);
            $session['user']['specialinc']="";
            $message = $badguy['message'];
            $message = str_replace("{badguy}", $badguy['creaturename'], $message);
            $message = str_replace("{goodguy}", $session['user']['name'], $message);
            addnews($message);
            $badguy=array();
            $session['user']['badguy']="";
        } else {
            fightnavmod(true,false,"link=".$link);
        }
}

output("`n`n`n");
rawoutput("<br><div style=\"text-align: right ;\"><a href=\"http://www.ogsi.it\" target=\"_blank\"><font color=\"#33FF33\">Il Maniero Burocratico Maledetto by Maximus & Poker @ http://www.ogsi.it</font></a><br>");
page_footer();
?>