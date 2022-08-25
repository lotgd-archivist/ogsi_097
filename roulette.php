<?php

// ----------------------------------------------------------------------------
// ==
// == roulette.php mod (ver 1.0)
// ==
// == written -entirely- by thegleek (thegleek@thegleek.com)
// == 18 June 2004
// ==
// == if you wish to see this in action, log onto my site and
// == sign up on my logd at http://www.thegleek.com/logd/
// ==
// ==
// == The latest version is always available at: http://dragonprime.cawsquad.net/
// ==
// ----------------------------------------------------------------------------
// DESCRIPTION
//
// - wow! i worked many many hours on this. and it finally works 100% and its
//   even fun! with graphics! whee! i'm quite sure this will be a popular mod!
//
// - i made this mod for players who love to gamble and quite frankly there
//   is some demand for casino games...
//
// ----------------------------------------------------------------------------
// INSTALLATION
//
// - unzip the roulette-pix.zip and make a dir inside your /logd/images/
//   directory called /roulette/ and put all the files in this dir
//
// - put this roulette.php file into your main logd dir and put a addnav
//   link in to your casino, igm, or it can go anywhere! :)
//
// - be aware that you need to change all the:
//
//                      addnav("Return to the Casino","casino.php");
//
//   in this script if you do not want it to go back to the casino.php or if
//   you do NOT have a casino.php
//
//   perhaps i will be nice and upload my casino.php along with this mod :)
//
//   ------------------------------------------------------------------------
//
//   $session[user][casinoturns] turned off by default, if your db has this
//   field in your accounts db, then by all means uncomment it! :)
//
//   to add `casinoturns` into your db, type this in your mysql:
//
//     ALTER TABLE accounts ADD `casinoturns` int(11) NOT NULL default '10';
//
//   and these 2 additional lines to your newday.php:
//
//   find:
//                      $turnsperday=getsetting("turns",10);
//   insert this after:
//                      $casinoturns=getsetting("casinoturns",10);
//
//   find:
//                      $session['user']['turns'] = $turnsperday + $spirits + $dkff;
//   insert this after:
//                      $session['user']['casinoturns'] = $casinoturns;
//
//----------------------------------------------------------------------------

require_once "common.php";
checkday();
page_header("La Roulette");
$session['user']['locazione'] = 166;
output("`c`b`&Roulette`0`b`c`n`n");

if ($_GET['op']=="") {
    rawoutput("<IMG SRC=\"images/roulette/layout-clean.gif\"><br><br>\n");
        output("`@Ti avvicini al tavolo della Roulette .`n`n");
        output("`2Il banchiere ti da un'occhiata di sguincio. Parla a voce così bassa che fai fatica a capire le sue parole.`n`n");
        output("`#\"Il limite massimo di giocata è di 500 pezzi d'oro ...`n`nFai una puntata minima di 100 pezzi d'oro se vuoi giocare, altrimenti ");
        output("allontanati dal mio tavolo vagabond".($session['user']['sex']?"a":"o").".\"`n`n");
        $_GET['op']="placebet";
}

if ($_GET['op']=="placebet") {

    // *** uncomment the line below if you are using the `casinoturn` field in your accounts db ***
        if ($session['user']['casinoturns']>0) {

                output("`@Quanto vuoi puntare?`n");
                output("<form action='roulette.php?op=mybet' method='POST'>
                          <input name='mybet' id='mybet'>
                          <input type='submit' class='button' value='fai la tua puntata'>
                        </form>",true);
                output("<script language='JavaScript'>
                          document.getElementById('mybet').focus();
                        </script>",true);
                addnav("","roulette.php?op=mybet");

    // *** uncomment the line below if you are using the `casinoturn` field in your accounts db ***
     } else { output("Hai esaurito i turni alla Roulette! Dovrai aspettare fino a domani per giocare nuovamente!"); }
}

if ($_GET['op']=="mybet") {
        $mybet=$_POST['mybet'];
        if ($mybet>$session['user']['gold']) {
                output("`\$Non hai $mybet pezzi d'oro.`n"); addnav("Continua","roulette.php?op=placebet");
        } elseif ($mybet<100) {
                output("`\$Devi fare una puntata minima di 100 pezzi d'oro per giocare!`n"); addnav("Continua","roulette.php?op=placebet");
        } elseif ($mybet>500) {
                output("`\$Il limite massimo di puntata è di 500 pezzi d'oro, la tua puntata è accettata come 500 pezzi d'oro.`n`n");
                $_POST['mybet']=500;
        $_GET['op']="picknums";

        // *** uncomment the line below if you are using the `casinoturn` field in your accounts db ***
        $session['user']['casinoturns']-=1;

        } else {
        $_GET['op']="picknums";

        // *** uncomment the line below if you are using the `casinoturn` field in your accounts db ***
        $session['user']['casinoturns']-=1;
    }
}

if ($_GET['op']=="picknums") {
    $bet=abs((int)$_GET['mybet']+(int)$_POST['mybet']);
    if ($_GET['try']=="") {
        rawoutput("<IMG SRC=\"images/roulette/layout-2.gif\"><br><br>\n");
        output("`3Hai puntato `^$bet`3 pezzi d'oro. Scegli il -tipo- di puntata ora ...:`n`n");
        output("`bPuntate Interne:`b (Inside Bet)`n`n
            A - 1 numero, Numero Pieno 38:1`n
            B - 2 numeri, Coppia di Numeri 17:1`n
            C - 3 numeri, Terzina 11:1`n
            D - 4 numeri, Quartina 8:1`n
            E - 5 numeri, Cinquina (sia 0 che 00 e i primi tre) 6:1`n
            F - 6 numeri, Sestina 5:1`n`n
            `bOutside bets:`b`n`n
            G - 12 numeri, Colonna 2:1`n
            H - 12 numeri, Dozzina 2:1`n
            J - 24 numeri, Coppia di Colonne 1:2`n
            K - 24 numeri, Coppia di Dozzine 1:2`n
            X - Pari/Dispari - Paga 1:1`n
            Y - Rosso/Nero - Paga 1:1`n
            Z - 1-18/19-36 (chiamato Alto/Basso) - Paga 1:1`n`n");
        addnav("A - 1 numero","roulette.php?op=picknums&mybet=$bet&try=A");
        addnav("B - 2 numeri","roulette.php?op=picknums&mybet=$bet&try=B");
        addnav("C - 3 numeri","roulette.php?op=picknums&mybet=$bet&try=C");
        addnav("D - 4 numeri","roulette.php?op=picknums&mybet=$bet&try=D");
        addnav("E - 5 numeri","roulette.php?op=picknums&mybet=$bet&try=E");
        addnav("F - 6 numeri","roulette.php?op=picknums&mybet=$bet&try=F");
        addnav("G - 12 numeri","roulette.php?op=picknums&mybet=$bet&try=G");
        addnav("H - 12 numeri","roulette.php?op=picknums&mybet=$bet&try=H");
        addnav("J - 24 numeri","roulette.php?op=picknums&mybet=$bet&try=J");
        addnav("K - 24 numeri","roulette.php?op=picknums&mybet=$bet&try=K");
        addnav("X - pari/dispari","roulette.php?op=picknums&mybet=$bet&try=X");
        addnav("Y - rosso/nero","roulette.php?op=picknums&mybet=$bet&try=Y");
        addnav("Z - 1 a 18/19 a 36","roulette.php?op=picknums&mybet=$bet&try=Z");
    } else {
        $try=$_GET['try']; $tbet=strtolower($try); $layoutpic="layout-";
                switch($tbet) {
                        case "b": case "c": case "d":
            case "e": case "f": case "g":
            case "h": case "j": case "k":
                            $layoutpic .= $tbet;
                        break;
                        default:
                $layoutpic .= "clean";
                break;
        }
        $layoutpic .= ".gif";
        if ($_POST['typea']=="B") { $layoutpic="layout-clean.gif"; }
        rawoutput("<IMG SRC=\"images/roulette/$layoutpic\"><br><br>\n");
        $drunkenness=array(-1=>"terribilmente sobrio",
                    0=>"piuttosto sobrio",
                    1=>"leggermente brillo",
                    2=>"alticcio",
                    3=>"quasi ubriaco",
                    4=>"leggermente ubriaco",
                    5=>"ubriaco",
                    6=>"molto ubriaco",
                    7=>"sbronzo",
                    8=>"completamente sbronzo",
                    9=>"quasi privo di sensi"
        );
        $drunk=round($session['user']['drunkenness']/10-.5,0);
        if ($_GET['illegalbet']=="Y") {
            if ($drunk>4) { output("`b`7HEY! LEI È ".strtoupper($drunkenness[$drunk])."!`b`n`n"); }
            output("`bFACCIA ATTENZIONE E SCELGA UN NUMERO CHE SIA SUL TAVOLO SIGNOR".($session['user']['sex']?"A":"E")."!`b`n`n");
            $HTTP_GET_VAR['illegalbet']="";
        }
        output("Hai deciso di piazzare una puntata di tipo-$try.`n`n");
        switch($try) {
            case "A":
                // A - 1 number, Straight up 38:1
                output("Su che numero desideri puntare l'intera somma di $bet della tua puntata? `b(Validi: 1-36, 37=0, 38=00)`b`n");
                output("<form action='roulette.php?op=spin' method='POST'>
                      <input name='mynum' id='mynum'>
                      <input type='hidden' name='typea' value='A'>
                      <input type='hidden' name='mybet' value=$bet>
                      <input type='submit' class='button' value='gira la ruota'>
                    </form>",true);
                output("<script language='JavaScript'>
                        document.getElementById('mynum').focus();
                    </script>",true);
                addnav("","roulette.php?op=spin");
                break;
            case "B":
                // B - 2 numbers, Split 17:1     *** 57 choices to pick from ***
                if ($_POST['typea']!="B") {
                    output("Scegli il tuo -PRIMO- numero, in seguito sceglierai il secondo. `b(Validi: 1-36)`b`n");
                    output("<form action='roulette.php?op=picknums&try=B' method='POST'>
                        <input name='mynum' id='mynum'>
                        <input type='hidden' name='typea' value='B'>
                        <input type='hidden' name='mybet' value=$bet>
                        <input type='submit' class='button' value='continua'>
                        </form>",true);
                    output("<script language='JavaScript'>
                            document.getElementById('mynum').focus();
                        </script>",true);
                    addnav("","roulette.php?op=picknums&try=B");
                } else {
                    $mynum=$_POST['mynum'];
                    if ($mynum<1 || $mynum>36) { redirect("roulette.php?op=picknums&mybet=$bet&try=B&illegalbet=Y"); }
                    $valid=array(    "1"=>"2,4",         "2"=>"1,3,5",       "3"=>"2,6",         "4"=>"1,5,7",
                             "5"=>"2,4,6,8",     "6"=>"3,5,9",       "7"=>"4,8,10",      "8"=>"5,7,9,11",
                             "9"=>"6,8,12",     "10"=>"7,11,13",    "11"=>"8,10,12,14", "12"=>"9,11,15",
                            "13"=>"10,14,16",   "14"=>"11,13,15,17","15"=>"12,14,18",   "16"=>"13,17,19",
                            "17"=>"14,16,18,20","18"=>"15,17,21",   "19"=>"16,20,22",   "20"=>"17,19,21,23",
                            "21"=>"18,20,24",   "22"=>"19,23,25",   "23"=>"20,22,24,26","24"=>"21,23,27",
                            "25"=>"22,26,28",   "26"=>"23,25,27,29","27"=>"24,26,30",   "28"=>"25,29,31",
                            "29"=>"26,28,30,32","30"=>"27,29,33",   "31"=>"28,32,34",   "32"=>"29,31,33,35",
                            "33"=>"30,32,36",   "34"=>"31,35",      "35"=>"32,34,36",   "36"=>"33,35"
                    );
                    output("Hai scelto `b". $mynum. "`b come -PRIMO- numero, ora ...`n`n");
                    output("Seleziona il tuo -SECONDO- numero, a quel punto sarai pronto a girare la ruota! ");
                    output("`b(Scelta di secondi numeri validi: ". $valid[$mynum].")`b`n");
                    $secnum=split("\,",$valid[$mynum]);
                    for ($x=1;$x<count($secnum)+1;$x++) {
                      $choice=chr(64+$x);
                      addnav($choice." = ".$secnum[$x-1],"roulette.php?op=spin&mybet=$bet&mynum=B".$mynum."-".$secnum[$x-1]);
                    }
                }
                break;
            case "C":
                // C - 3 numbers, 3 Line 11:1
                output("Scegli il punto su cui desideri piazzare la tua puntata ...`n");
                addnav("A = 1,2,3","roulette.php?op=spin&mybet=$bet&mynum=C1");
                addnav("B = 4,5,6","roulette.php?op=spin&mybet=$bet&mynum=C2");
                addnav("C = 7,8,9","roulette.php?op=spin&mybet=$bet&mynum=C3");
                addnav("D = 10,11,12","roulette.php?op=spin&mybet=$bet&mynum=C4");
                addnav("E = 13,14,15","roulette.php?op=spin&mybet=$bet&mynum=C5");
                addnav("F = 16,17,18","roulette.php?op=spin&mybet=$bet&mynum=C6");
                addnav("G = 19,20,21","roulette.php?op=spin&mybet=$bet&mynum=C7");
                addnav("H = 22,23,24","roulette.php?op=spin&mybet=$bet&mynum=C8");
                addnav("I = 25,26,27","roulette.php?op=spin&mybet=$bet&mynum=C9");
                addnav("J = 28,29,30","roulette.php?op=spin&mybet=$bet&mynum=C10");
                addnav("K = 31,32,33","roulette.php?op=spin&mybet=$bet&mynum=C11");
                addnav("L = 34,35,36","roulette.php?op=spin&mybet=$bet&mynum=C12");
                break;
            case "D":
                // D - 4 numbers, Corner 8:1
                output("Scegli il punto su cui desideri piazzare la tua puntata ...`n");
                addnav("A = 1,2,4,5","roulette.php?op=spin&mybet=$bet&mynum=D1");
                addnav("B = 2,3,5,6","roulette.php?op=spin&mybet=$bet&mynum=D2");
                addnav("C = 4,5,7,8","roulette.php?op=spin&mybet=$bet&mynum=D3");
                addnav("D = 5,6,8,9","roulette.php?op=spin&mybet=$bet&mynum=D4");
                addnav("E = 7,8,10,11","roulette.php?op=spin&mybet=$bet&mynum=D5");
                addnav("F = 8,9,11,12","roulette.php?op=spin&mybet=$bet&mynum=D6");
                addnav("G = 10,11,13,14","roulette.php?op=spin&mybet=$bet&mynum=D7");
                addnav("H = 11,12,14,15","roulette.php?op=spin&mybet=$bet&mynum=D8");
                addnav("I = 13,14,16,17","roulette.php?op=spin&mybet=$bet&mynum=D9");
                addnav("J = 14,15,17,18","roulette.php?op=spin&mybet=$bet&mynum=D10");
                addnav("K = 16,17,19,20","roulette.php?op=spin&mybet=$bet&mynum=D11");
                addnav("L = 17,18,20,21","roulette.php?op=spin&mybet=$bet&mynum=D12");
                addnav("M = 19,20,22,23","roulette.php?op=spin&mybet=$bet&mynum=D13");
                addnav("N = 20,21,23,24","roulette.php?op=spin&mybet=$bet&mynum=D14");
                addnav("O = 22,23,25,26","roulette.php?op=spin&mybet=$bet&mynum=D15");
                addnav("P = 23,24,26,27","roulette.php?op=spin&mybet=$bet&mynum=D16");
                addnav("Q = 25,26,28,29","roulette.php?op=spin&mybet=$bet&mynum=D17");
                addnav("R = 26,27,29,30","roulette.php?op=spin&mybet=$bet&mynum=D18");
                addnav("S = 28,29,31,32","roulette.php?op=spin&mybet=$bet&mynum=D19");
                addnav("T = 29,30,32,33","roulette.php?op=spin&mybet=$bet&mynum=D20");
                addnav("U = 31,32,34,35","roulette.php?op=spin&mybet=$bet&mynum=D21");
                addnav("V = 32,33,35,36","roulette.php?op=spin&mybet=$bet&mynum=D22");
                break;
            case "E":
                // E - 5 numbers, 1st Five 6:1
                output("-- solo una scelta è disponibile con questa puntata --`n`n");
                addnav("Clicca qui per girare la ruota","roulette.php?op=spin&mybet=$bet&mynum=E1");
                break;
            case "F":
                // F - 6 numbers, 6 Line 5:1
                output("Scegli il punto su cui desideri piazzare la tua puntata ...`n");
                output("-- hai 11 possibilità di scelta --`n`n");
                addnav("A = 1,2,3,4,5,6","roulette.php?op=spin&mybet=$bet&mynum=F1");
                addnav("B = 4,5,6,7,8,9","roulette.php?op=spin&mybet=$bet&mynum=F2");
                addnav("C = 7,8,9,10,11,12","roulette.php?op=spin&mybet=$bet&mynum=F3");
                addnav("D = 10,11,12,13,14,15","roulette.php?op=spin&mybet=$bet&mynum=F4");
                addnav("E = 13,14,15,16,17,18","roulette.php?op=spin&mybet=$bet&mynum=F5");
                addnav("F = 16,17,18,19,20,21","roulette.php?op=spin&mybet=$bet&mynum=F6");
                addnav("G = 19,20,21,22,23,24","roulette.php?op=spin&mybet=$bet&mynum=F7");
                addnav("H = 22,23,24,25,26,27","roulette.php?op=spin&mybet=$bet&mynum=F8");
                addnav("I = 25,26,27,28,29,30","roulette.php?op=spin&mybet=$bet&mynum=F9");
                addnav("J = 28,29,30,31,32,33","roulette.php?op=spin&mybet=$bet&mynum=F10");
                addnav("K = 31,32,33,34,35,36","roulette.php?op=spin&mybet=$bet&mynum=F11");
                break;
            case "G":
                // G - 12 numbers, Column 2:1
                output("Scegli il punto su cui desideri piazzare la tua puntata ...`n");
                addnav("A = RIGA IN ALTO","roulette.php?op=spin&mybet=$bet&mynum=G1");
                addnav("B = RIGA CENTRALE","roulette.php?op=spin&mybet=$bet&mynum=G2");
                addnav("C = RIGA IN BASSO","roulette.php?op=spin&mybet=$bet&mynum=G3");
                break;
            case "H":
                // H - 12 numbers, Dozen 2:1
                output("Scegli il punto su cui desideri piazzare la tua puntata ...`n");
                addnav("A = PRIMI 12","roulette.php?op=spin&mybet=$bet&mynum=H1");
                addnav("B = SECONDI 12","roulette.php?op=spin&mybet=$bet&mynum=H2");
                addnav("C = TERZI 12","roulette.php?op=spin&mybet=$bet&mynum=H3");
                break;
            case "J":
                // J - 24 numbers, Split Columns 1:2
                output("Scegli il punto su cui desideri piazzare la tua puntata...`n");
                addnav("A = 2 RIGHE ALTE","roulette.php?op=spin&mybet=$bet&mynum=J1");
                addnav("B = 2 RIGHE BASSE","roulette.php?op=spin&mybet=$bet&mynum=J2");
                break;
            case "K":
                // K - 24 numbers, Split Dozens 1:2
                output("Scegli il punto su cui desideri piazzare la tua puntata ...`n");
                addnav("A = 1a E 2a DOZZINA","roulette.php?op=spin&mybet=$bet&mynum=K1");
                addnav("B = 2a e 3a DOZZINA","roulette.php?op=spin&mybet=$bet&mynum=K2");
                break;
            case "X":
                // X - Even/Odd - Pays even money 1:1
                output("Scegli il punto su cui desideri piazzare la tua puntata ...`n");
                addnav("A = PARI","roulette.php?op=spin&mybet=$bet&mynum=X1");
                addnav("B = DISPARI","roulette.php?op=spin&mybet=$bet&mynum=X2");
                break;
            case "Y":
                // Y - Red/Black - Pays even money 1:1
                output("Scegli il punto su cui desideri piazzare la tua puntata ...`n");
                addnav("A = ROSSO","roulette.php?op=spin&mybet=$bet&mynum=Y1");
                addnav("B = NERO","roulette.php?op=spin&mybet=$bet&mynum=Y2");
                break;
            case "Z":
                // Z - 1-18/19-36 (also called Low/High) - Pays even money 1:1
                output("Scegli il punto su cui desideri piazzare la tua puntata ...`n");
                addnav("A = 1 TO 18","roulette.php?op=spin&mybet=$bet&mynum=Z1");
                addnav("B = 19 TO 36","roulette.php?op=spin&mybet=$bet&mynum=Z2");
                break;
            default:
                break;
        }
    }
}

if ($_GET['op']=="spin") {
    $mybet=abs((int)$_GET['mybet']+(int)$_POST['mybet']);
        $mynum=$_POST['mynum']; if (!$mynum) { $mynum=$_GET['mynum']; }
    $mlen=strlen($mynum);
    if ($_POST['typea']!="A") {
        $bet=substr($mynum,0,1); $type=substr($mynum,1,$mlen-1);
        switch($bet) {
            case "B":
                // B - 2 numbers, Split 17:1
                $twonums=split("-",$type);
                $betarray=array(1=>$twonums[0],$twonums[1]);
                $payout=17;
                break;
            case "C":
                // C - 3 numbers, 3 Line 11:1
                switch($type) {
                    case "1":
                        $betarray=array(1=>1,2,3);
                        break;
                    case "2":
                        $betarray=array(1=>4,5,6);
                        break;
                    case "3":
                        $betarray=array(1=>7,8,9);
                        break;
                    case "4":
                        $betarray=array(1=>10,11,12);
                        break;
                    case "5":
                        $betarray=array(1=>13,14,15);
                        break;
                    case "6":
                        $betarray=array(1=>16,17,18);
                        break;
                    case "7":
                        $betarray=array(1=>19,20,21);
                        break;
                    case "8":
                        $betarray=array(1=>22,23,24);
                        break;
                    case "9":
                        $betarray=array(1=>25,26,27);
                        break;
                    case "10":
                        $betarray=array(1=>28,29,30);
                        break;
                    case "11":
                        $betarray=array(1=>31,32,33);
                        break;
                    case "12":
                        $betarray=array(1=>34,35,36);
                        break;
                }
                $payout=11;
                break;
            case "D":
                // D - 4 numbers, Corner 8:1
                switch($type) {
                    case "1":
                        $betarray=array(1=>1,2,4,5);
                        break;
                    case "2":
                        $betarray=array(1=>2,3,5,6);
                        break;
                    case "3":
                        $betarray=array(1=>4,5,7,8);
                        break;
                    case "4":
                        $betarray=array(1=>5,6,8,9);
                        break;
                    case "5":
                        $betarray=array(1=>7,8,10,11);
                        break;
                    case "6":
                        $betarray=array(1=>8,9,11,12);
                        break;
                    case "7":
                        $betarray=array(1=>10,11,13,14);
                        break;
                    case "8":
                        $betarray=array(1=>11,12,14,15);
                        break;
                    case "9":
                        $betarray=array(1=>13,14,16,17);
                        break;
                    case "10":
                        $betarray=array(1=>14,15,17,18);
                        break;
                    case "11":
                        $betarray=array(1=>16,17,19,20);
                        break;
                    case "12":
                        $betarray=array(1=>17,18,20,21);
                        break;
                    case "13":
                        $betarray=array(1=>19,20,22,23);
                        break;
                    case "14":
                        $betarray=array(1=>20,21,23,24);
                        break;
                    case "15":
                        $betarray=array(1=>22,23,25,26);
                        break;
                    case "16":
                        $betarray=array(1=>23,24,26,27);
                        break;
                    case "17":
                        $betarray=array(1=>25,26,28,29);
                        break;
                    case "18":
                        $betarray=array(1=>26,27,29,30);
                        break;
                    case "19":
                        $betarray=array(1=>28,29,31,32);
                        break;
                    case "20":
                        $betarray=array(1=>29,30,32,33);
                        break;
                    case "21":
                        $betarray=array(1=>31,32,34,35);
                        break;
                    case "22":
                        $betarray=array(1=>32,33,35,36);
                        break;
                }
                $payout=8;
                break;
            case "E":
                // E - 5 numbers, 1st Five 6:1
                $betarray=array(1=>1,2,3,37,38);
                $payout=6;
                break;
            case "F":
                // F - 6 numbers, 6 Line 5:1
                switch($type) {
                    case "1":
                        $betarray=array(1=>1,2,3,4,5,6);
                        break;
                    case "2":
                        $betarray=array(1=>4,5,6,7,8,9);
                        break;
                    case "3":
                        $betarray=array(1=>7,8,9,10,11,12);
                        break;
                    case "4":
                        $betarray=array(1=>10,11,12,13,14,15);
                        break;
                    case "5":
                        $betarray=array(1=>13,14,15,16,17,18);
                        break;
                    case "6":
                        $betarray=array(1=>16,17,18,19,20,21);
                        break;
                    case "7":
                        $betarray=array(1=>19,20,21,22,23,24);
                        break;
                    case "8":
                        $betarray=array(1=>22,23,24,25,26,27);
                        break;
                    case "9":
                        $betarray=array(1=>25,26,27,28,29,30);
                        break;
                    case "10":
                        $betarray=array(1=>28,29,30,31,32,33);
                        break;
                    case "11":
                        $betarray=array(1=>31,32,33,34,35,36);
                        break;
                }
                $payout=5;
                break;
            case "G":
                // G - 12 numbers, Column 2:1
                switch($type) {
                    case "1":
                        $betarray=array(1=>3,6,9,12,15,18,21,24,27,30,33,36);
                        break;
                    case "2":
                        $betarray=array(1=>2,5,8,11,14,17,20,23,26,29,32,35);
                        break;
                    case "3":
                        $betarray=array(1=>1,4,7,10,13,16,19,22,25,28,31,34);
                        break;
                }
                $payout=2;
                break;
            case "H":
                // H - 12 numbers, Dozen 2:1
                switch($type) {
                    case "1":
                        $betarray=array(1=>1,2,3,4,5,6,7,8,9,10,11,12);
                        break;
                    case "2":
                        $betarray=array(1=>13,14,15,16,17,18,19,20,21,22,23,24);
                        break;
                    case "3":
                        $betarray=array(1=>25,26,27,28,29,30,31,32,33,34,35,36);
                        break;
                }
                $payout=2;
                break;
            case "J":
                // J - 24 numbers, Split Columns 1:2
                switch($type) {
                    case "1":
                        $betarray=array(1=>2,3,5,6,8,9,11,12,14,15,17,18,20,21,23,24,26,27,29,30,32,33,35,36);
                        break;
                    case "2":
                        $betarray=array(1=>1,2,4,5,7,8,10,11,13,14,16,17,19,20,22,23,25,26,28,29,31,32,34,35);
                        break;
                }
                $payout=0.5;
                break;
            case "K":
                // K - 24 numbers, Split Dozens 1:2
                switch($type) {
                    case "1":
                        $betarray=array(1=>1,2,3,4,5,6,7,8,9,10,11,12,13,14,15,16,17,18,19,20,21,22,23,24);
                        break;
                    case "2":
                        $betarray=array(1=>13,14,15,16,17,18,19,20,21,22,23,24,25,26,27,28,29,30,31,32,33,34,35,36);
                        break;
                }
                $payout=0.5;
                break;
            case "X":
                // X - Even/Odd - Pays even money 1:1
                switch($type) {
                    case "1":
                        $betarray=array(1=>2,4,6,8,10,12,14,16,18,20,22,24,26,28,30,32,34,36);
                        break;
                    case "2":
                        $betarray=array(1=>1,3,5,7,9,11,13,15,17,19,21,23,25,27,29,31,33,35);
                        break;
                }
                $payout=1;
                break;
            case "Y":
                // Y - Red/Black - Pays even money 1:1
                switch($type) {
                    case "1":
                        $betarray=array(1=>1,3,5,7,9,12,14,16,18,19,21,23,25,27,30,32,34,36);
                        break;
                    case "2":
                        $betarray=array(1=>2,4,6,8,10,11,13,15,17,20,22,24,26,28,29,31,33,35);
                        break;
                }
                $payout=1;
                break;
            case "Z":
                // Z - 1-18/19-36 (also called Low/High) - Pays even money 1:1
                switch($type) {
                    case "1":
                        $betarray=array(1=>1,2,3,4,5,6,7,8,9,10,11,12,13,14,15,16,17,18);
                        break;
                    case "2":
                        $betarray=array(1=>19,20,21,22,23,24,25,26,27,28,29,30,31,32,33,34,35,36);
                        break;
                }
                $payout=1;
                break;
            default:
                break;
        }
    } else {
        if ($mynum<1 || $mynum>38) { redirect("roulette.php?op=picknums&mybet=$mybet&try=A&illegalbet=Y"); }
        $payout="38";
    }

    // *** debug'n output - not needed for gameplay ***

    // output("`n`bDEBUG: op = ".$_GET['op']."`b`n");
    // output("`bDEBUG: mynum = ".$mynum."`b`n");
    // output("`bDEBUG: mybet = ".$mybet."`b`n");
    // output("`bDEBUG: typea = ".$_POST['typea']."`b`n");
    // output("`bDEBUG: drop = ".$_POST['drop']."`b`n");
    // output("`bDEBUG: bet = ".$bet."`b`n");
    // output("`bDEBUG: type = ".$type."`b`n");
    // output("`bDEBUG: payout = ".$payout."`b`n`n");

    if ($_POST['drop']!="yes") {
        output("MADAME E MONSIEUR, LE JEUX SONT FAIT ! RIEN NE VAS PLUS !`n`n");
        rawoutput("<IMG SRC=\"images/roulette/wheel.gif\"><br><br>\n");
        output("<form action='roulette.php?op=spin' method='POST'>
            <input type='hidden' name='mynum' value=$mynum>
            <input type='hidden' name='mybet' value=$mybet>
            <input type='hidden' name='typea' value=".$_POST['typea'].">
            <input type='hidden' name='drop' value='yes'>
            <input type='submit' class='button' value='FERMA LA RUOTA!'>
            </form>",true);
        addnav("","roulette.php?op=spin");
    } else if ($_POST['drop']=="yes") {
        $youwon=0; $session['user']['specialmisc']=e_rand(1,38);
        $win=$session['user']['specialmisc']; $winpic=$win;
        if ($win==37) { $winpic="0"; $color="Verde"; }
        if ($win==38) { $winpic="00"; $color="Verde"; }
        $showwin = $winpic;
        $winpic .= ".gif";
        $red=array(1=>1,3,5,7,9,12,14,16,18,19,21,23,25,27,30,32,34,36);
        while (list($key,$val)=each($red)){
            if ($win==$val) { $color="Rosso"; }
        }
        if ($color!="Verde" && $color!="Rosso") { $color="Nero"; }
        rawoutput("<IMG SRC=\"images/roulette/layout-clean.gif\"><br><br>\n");
        output("La pallina cade infine nel buco mentre la ruota lentamente si ferma e il numero corrispondente è ...`n`n");
        rawoutput("<IMG SRC=\"images/roulette/$winpic\" align=center>\n");
        output(" [ ".$color." ".$showwin." ]`n`n");
        if ($payout!=38) {
            while (list($key,$val)=each($betarray)){
                if ($win==$val) { $youwon=1; }
            }
        }
        if ($mynum==$win || $youwon) {
            $winnings=round($mybet*$payout,0);
            $session['user']['gold']+=$winnings;
            output("Hai VINTO $winnings pezzi d'oro al tavolo della Roulette!!!`n`n");
            if ($payout==0.5) {
                output("In base alla tua puntata di $mybet pezzi d'oro con le chance di 1:2`n`n");
            } else { output("In base alla tua puntata di $mybet pezzi d'oro con le chance di $payout:1`n`n"); }
                //$session[user][casinoturns]+=1;
            debuglog("vince $winnings oro alla Roulette");
        } else {
            $session['user']['gold']-=$mybet;
            output("Hai perso $mybet pezzi d'oro al tavolo della Roulette.`n`n");
            //$session[user][casinoturns]-=1;
            debuglog("perde $mybet oro alla Roulette");
        }
        $_GET['op']="";
    }
}

// I cannot make you keep this line here but would appreciate it left in.
//rawoutput("<br><br><hr><div style=\"text-align: left;\"><a href=\"http://www.thegleek.com/\" target=\"_blank\">
//       Roulette by thegleek @ http://www.thegleek.com/</a><br>");

if ($_GET['op']!="spin") {
    if ($_GET['op']!="picknums") {
       addnav("Torna alla Tenuta","houses.php?op=enter");
       addnav("Gioca ancora","roulette.php");
    }
}
page_footer();

?>