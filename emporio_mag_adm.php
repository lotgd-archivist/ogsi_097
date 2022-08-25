<?php
require_once("common.php");
require_once("common2.php");
checkday();
page_header("L'emporio degli oggetti magici per ADMIN (99).");
addnav("Azioni");
addnav("Esamina gli oggetti", "emporio_mag_adm.php?op=esamina");
addnav("Valuta i tuoi oggetti", "emporio_mag_adm.php?az=valuta");
addnav("Scambia oggetti", "emporio_mag_adm.php?az=scambia");
addnav("Exit");
addnav("Torna al Villaggio", "village.php");
if ($_GET[op] == "" and $_GET[az] == "") {
    output("Il vecchio ADMIN,  ha un campionario di oggetti unici, di ottima fattura e spesso con proprietà magiche.`nNaturalmente il prezzo di questi portentosi oggetti è molto alto e non tutti possono sfruttarli.");
} elseif ($_GET[op] == "esamina") {
           $sql = "SELECT * FROM oggetti WHERE dove = 99 ORDER BY livello DESC,nome DESC";
        output("`c`b`&Oggetti al momento disponibili`b`c");
        output("<table cellspacing=0 cellpadding=2 align='center'><tr><td>&nbsp;</td><td>`bNome`b</td><td>`bLivello`b</td><td>`bCosto`b</td></tr>", true);
        $result = db_query($sql) or die(db_error(LINK));
        if (db_num_rows($result) == 0) {
            output("<tr><td colspan=4 align='center'>`&Non ci sono oggetti disponibili mi spiace`0</td></tr>", true);
        }
        $countrow = db_num_rows($result);
        for ($i=0; $i<$countrow; $i++){
        //for ($i = 0;$i < db_num_rows($result);$i++) {
            $row = db_fetch_assoc($result);
            $valore = intval($row['valore'] * (2+(2*$row['usura']/$row['usuramax'])+($row['usuramagica']/$row['usuramagicamax']))/5);
            if ($row['usuramagica']==0) $valore=intval($valore/2);
            output("<tr class='" . ($i % 2?"trlight":"trdark") . "'><td>" . ($i + 1) . ".</td><td><A href=emporio_mag_adm.php?op=$row[id_oggetti]>$row[nome]</a></td><td>$row[livello]</td><td>$valore</td></tr>", true);
            addnav("", "emporio_mag_adm.php?op=$row[id_oggetti]");
        }
        output("</table>", true);

} elseif ($_GET[az] == "") {
    output ("ID Oggetto :" . $_GET[op] . "`n`n");
    $sqlo = "SELECT id_oggetti,nome,livello,descrizione,valore FROM oggetti WHERE id_oggetti = $_GET[op] ORDER BY livello DESC,nome DESC";
    $resulto = db_query($sqlo) or die(db_error(LINK));
    $row = db_fetch_assoc($resulto);
    $valb = $row['valore'];
    if ($row[usuramax] > 0) {
        $valf = intval($row['valore']*$row['usura']/$row['usuramax']);
    } else {
        $valf = $row['valore'];
    }
    if ($row[usuramagicamax] > 0) {
        $valm = intval($row['valore']*$row['usuramagica']/$row['usuramagicamax']);
    } else {
        $valm = $row['valore'];
    }
    $valore = intval((2 * $valb + 2 * $valf + $valm)/5);
    if ($row['usuramagica']==0) $valore=intval($valore/2);
    output ("Oggetto :" . $row[nome] . "`n`n");
    output ("Descrizione:" . $row[descrizione] . "`n`n");
    output ("Livello:" . $row[livello] . "`n`n");
    output ("Valore in gemme:" . $valore . "`n`n");
    output("<form action='emporio_mag_adm.php?az=compra&oggettoid=$_GET[op]' method='POST'>", true);
    addnav("", "emporio_mag_adm.php?az=compra&oggettoid=$_GET[op]");
    // addnav("","user.php?op=edit&userid=$_GET[userid]".($_GET['returnpetition']!=""?"&returnpetition={$_GET['returnpetition']}":"")."");
    // addnav("Set up ban","user.php?op=setupban&userid=$row[acctid]");
    // addnav("Display debug log","user.php?op=debuglog&userid={$_GET['userid']}");
    output("<input type='submit' class='button' value='Compra'>", true);
    // showform($userinfo,$row);
    output("</form>", true);
}
if ($_GET[az] == "compra") {
    $sqlo = "SELECT * FROM oggetti WHERE id_oggetti = $_GET[oggettoid]";
    $resulto = db_query($sqlo) or die(db_error(LINK));
    $row = db_fetch_assoc($resulto);
    $valb = $row['valore'];
    if ($row[usuramax] > 0) {
        $valf = intval($row['valore']*$row['usura']/$row['usuramax']);
    } else {
        $valf = $row['valore'];
    }
    if ($row[usuramagicamax] > 0) {
        $valm = intval($row['valore']*$row['usuramagica']/$row['usuramagicamax']);
    } else {
        $valm = $row['valore'];
    }
    $valore = intval((2 * $valb + 2 * $valf + $valm)/5);
    if ($row['usuramagica']==0) $valore=intval($valore/2);
    if ($valore>$session[user][gems]) {
        output ("Non hai abbastanza gemme!");
    }else{
        $sqlogg = "SELECT * FROM accounts WHERE oggetto = $_GET[oggettoid]";
        $resultogg = db_query($sqlogg) or die(db_error(LINK));
        if ($session[user][dragonkills] < 10) {
            $maxbuylvl = 0 + (3 * $session[user][reincarna]);
        } else if ($session[user][dragonkills] >= 10 AND $session[user][dragonkills] < 19) {
            $maxbuylvl = 1 + (3 * $session[user][reincarna]);
        } else if ($session[user][dragonkills] >= 19) {
            $maxbuylvl = 2 + (3 * $session[user][reincarna]);
        }
        if ($session['user']['superuser'] > 1) {
            $maxbuylvl = 100;
        }
        if ($row[livello] > $maxbuylvl) {
            output ("Questo oggetto è troppo potente per te.`n`n");
        } else {
            if (db_num_rows($resultogg) == 0) {
                $sqlzai = "SELECT * FROM accounts WHERE zaino = $_GET[oggettoid]";
                $resultzai = db_query($sqlzai) or die(db_error(LINK));
                if (db_num_rows($resultzai) == 0) {
                    if ($session['user']['oggetto'] == "0") {
                        output ("Hai comprato questo oggetto :" . $row[nome] . " e lo hai indossato. `n`n");
                        $session['user']['oggetto'] = $row[id_oggetti];
                        $sql = "UPDATE oggetti SET dove=0 WHERE id_oggetti=$_GET[oggettoid]";
                        db_query($sql) or die(db_error(LINK));
                        $session[user][attack] = $session[user][attack] + $row[attack_help];
                        $session[user][defence] = $session[user][defence] + $row[defence_help];
                        $session[user][bonusattack] = $session[user][bonusattack] + $row[attack_help];
                        $session[user][bonusdefence] = $session[user][bonusdefence] + $row[defence_help];
                        if ($row[usuramagica]!=0) $session[user][maxhitpoints] = $session[user][maxhitpoints] + $row[hp_help];
                        if ($row[usuramagica]!=0) $session[user][hitpoints] = $session[user][hitpoints] + $row[hp_help];
                        if ($row[usuramagica]!=0) $session['user']['turns'] = $session['user']['turns'] + $row[turns_help];
                        if ($row[usuramagica]!=0) $session['user']['bonusfight'] = $session['user']['bonusfight'] + $row[turns_help];
                        $session[user][gems] = $session[user][gems] - $valore;
                    } elseif ($session['user']['zaino'] == "0") {
                        output ("Hai comprato questo oggetto :" . $row[nome] . " e lo hai messo nello zaino.`n`n");
                        $session['user']['zaino'] = $row[id_oggetti];
                        $sql = "UPDATE oggetti SET dove=0 WHERE id_oggetti=$_GET[oggettoid]";
                        db_query($sql) or die(db_error(LINK));
                        $session[user][gems] = $session[user][gems] - $valore;
                    } else {
                        output ("Non hai posto ne indosso e neanche nello zaino, quindi non puoi acquistare questo oggetto.`n`n");
                    }
                } else {
                    output ("Qualche altro cliente ha già comprato questo oggetto.`n`n");
                }
            } else {
                output ("Qualche altro cliente ha già comprato questo oggetto.`n`n");
            }
        }
    }
} else if ($_GET[az] == "valuta") {
    $oggetto = $session[user][oggetto];
    $zaino = $session[user][zaino];
    if ($session[user][oggetto] == "0") {
        $ogg = "Nulla";
    } else {
        $sqlo = "SELECT * FROM oggetti WHERE id_oggetti = $oggetto";
        $resultoo = db_query($sqlo) or die(db_error(LINK));
        $rowo = db_fetch_assoc($resultoo);
        $ogg = $rowo[nome];
    }
    if ($session[user][zaino] == "0") {
        $zai = "Nulla";
    } else {
        $sqlo = "SELECT * FROM oggetti WHERE id_oggetti = $zaino";
        $resultoz = db_query($sqlo) or die(db_error(LINK));
        $rowz = db_fetch_assoc($resultoz);
        $zai = $rowz[nome];
    }

    $valvendob = $rowo['valore'];
    if ($rowo[usuramax] > 0) {
        $valvendof = intval($rowo['valore']*$rowo['usura']/$rowo['usuramax']);
    } else {
        $valvendof = $rowo['valore'];
    }
    if ($rowo[usuramagicamax] > 0) {
        $valvendom = intval($rowo['valore']*$rowo['usuramagica']/$rowo['usuramagicamax']);
    } else {
        $valvendom = $rowo['valore'];
    }
    $valvendo = intval((2 * $valvendob + 2 * $valvendof + $valvendom)/10);
    if ($rowo['usuramagica']==0) $valvendo=intval($valvendo/2);

    $valvendzb = $rowz['valore'];
    if ($rowz[usuramax] > 0) {
        $valvendzf = intval($rowz['valore']*$rowz['usura']/$rowz['usuramax']);
    } else {
        $valvendzf = $rowz['valore'];
    }
    if ($rowz[usuramagicamax] > 0) {
        $valvendzm = intval($rowz['valore']*$rowz['usuramagica']/$rowz['usuramagicamax']);
    } else {
        $valvendzm = $rowz['valore'];
    }
    $valvendz = intval((2 * $valvendzb + 2 * $valvendzf + $valvendzm)/10);
    if ($rowz['usuramagica']==0) $valvendz=intval($valvendz/2);

    output ("Possiedi questi oggetti.`n`n");
    addnav("", "emporio_mag_adm.php?az=vendio&oid=$rowo[id_oggetti]");
    addnav("", "emporio_mag_adm.php?az=vendiz&zid=$rowz[id_oggetti]");
    output ("<table>", true);
    output ("<tr><td>Sei equipaggiato con : </td><td>" . $ogg . "</td><td><A href=emporio_mag_adm.php?az=vendio&oid=$rowo[id_oggetti]>Vendi</a> per $valvendo gemme.</td></tr>", true);
    output ("<tr><td>Nello zaino hai : </td><td>" . $zai . "</td><td><A href=emporio_mag_adm.php?az=vendiz&zid=$rowz[id_oggetti]>Vendi</a> per $valvendz gemme.</td></tr>", true);
    output ("</table>", true);
} else if ($_GET[az] == "vendio") {
    if ($session['user']['level'] < 6) {
        output ("Non puoi vendere oggetti magici prima di sconfiggere il quinto maestro.`n`n");
    } else {
        $sqlo = "SELECT * FROM oggetti WHERE id_oggetti = $_GET[oid]";
        $resulto = db_query($sqlo) or die(db_error(LINK));
        $row = db_fetch_assoc($resulto);
        if (!$_GET[oid]) {
            output ("Non hai nulla da vendere.`n`n");
        } else {
            $valb = $row['valore'];
            if ($row[usuramax] > 0) {
                $valf = intval($row['valore']*$row['usura']/$row['usuramax']);
            } else {
                $valf = $row['valore'];
            }
            if ($row[usuramagicamax] > 0) {
                $valm = intval($row['valore']*$row['usuramagica']/$row['usuramagicamax']);
            } else {
                $valm = $row['valore'];
            }
            $valore = intval((2 * $valb + 2 * $valf + $valm)/5);
            if ($row['usuramagica']==0) $valore=intval($valore/2);

            output ("Hai venduto l'oggetto equipaggiato.`n`n");
            $sql = "UPDATE oggetti SET dove=99 WHERE id_oggetti=$_GET[oid]";
            db_query($sql) or die(db_error(LINK));
            $session[user][oggetto] = 0;
            $session[user][attack] = $session[user][attack] - $row[attack_help];
            $session[user][defence] = $session[user][defence] - $row[defence_help];
            $session[user][bonusattack] = $session[user][bonusattack] - $row[attack_help];
            $session[user][bonusdefence] = $session[user][bonusdefence] - $row[defence_help];
            if ($row[usuramagica]!=0) $session[user][maxhitpoints] = $session[user][maxhitpoints] - $row[hp_help];
            if ($row[usuramagica]!=0) $session[user][hitpoints] = $session[user][hitpoints] - $row[hp_help];
            if ($row[usuramagica]!=0) $session['user']['turns'] = $session['user']['turns'] - $row[turns_help];
            if ($row[usuramagica]!=0) $session['user']['bonusfight'] = $session['user']['bonusfight'] - $row[turns_help];
            $session[user][gems] = $session[user][gems] + $valore;
        }
    }
} else if ($_GET[az] == "vendiz") {
        if (!$_GET[zid]) {
            output ("Non hai nulla da vendere.`n`n");
        } else {
                $sqlo = "SELECT * FROM oggetti WHERE id_oggetti = $_GET[zid]";
                $resulto = db_query($sqlo) or die(db_error(LINK));
                $row = db_fetch_assoc($resulto);
                $valb = $row['valore'];
                if ($row[usuramax] > 0) {
                    $valf = intval($row['valore']*$row['usura']/$row['usuramax']);
                } else {
                    $valf = $row['valore'];
                }
                if ($row[usuramagicamax] > 0) {
                    $valm = intval($row['valore']*$row['usuramagica']/$row['usuramagicamax']);
                } else {
                    $valm = $row['valore'];
                }
                $valore = intval((2 * $valb + 2 * $valf + $valm)/5);
                if ($row['usuramagica']==0) $valore=intval($valore/2);

                output ("Hai venduto l'oggetto nello zaino.`n`n");
                $sql = "UPDATE oggetti SET dove=99 WHERE id_oggetti=$_GET[zid]";
                db_query($sql) or die(db_error(LINK));
                $session[user][zaino] = 0;
                $session[user][gems] = $session[user][gems] + $valore;
        }

} else if ($_GET[az] == "scambia") {
    $ogg = $session[user][oggetto];
    $zai = $session[user][zaino];
    $sqlo = "SELECT * FROM oggetti WHERE id_oggetti = $ogg";
    $resulto = db_query($sqlo) or die(db_error(LINK));
    $rowo = db_fetch_assoc($resulto);
    $sqlz = "SELECT * FROM oggetti WHERE id_oggetti = $zai";
    $resultz = db_query($sqlz) or die(db_error(LINK));
    $rowz = db_fetch_assoc($resultz);
    if (!$session[user][oggetto] and !$session[user][zaino]) {
        output ("Non possiedi oggetti da invertire.`n`n");
    } else if (!$session[user][zaino]) {
        output ("Non hai nulla nello zaino.`n`n");
    } else {
        output ("Hai invertito l'oggetto equipaggiato con quello nello zaino.`n`n");
        $session[user][attack] = $session[user][attack] - $rowo[attack_help];
        $session[user][defence] = $session[user][defence] - $rowo[defence_help];
        $session[user][bonusattack] = $session[user][bonusattack] - $rowo[attack_help];
        $session[user][bonusdefence] = $session[user][bonusdefence] - $rowo[defence_help];
        if ($rowo[usuramagica]!=0) $session[user][maxhitpoints] = $session[user][maxhitpoints] - $rowo[hp_help];
        if ($rowo[usuramagica]!=0) $session[user][hitpoints] = $session[user][hitpoints] - $rowo[hp_help];
        if ($rowo[usuramagica]!=0) $session['user']['turns'] = $session['user']['turns'] - $rowo[turns_help];
        if ($rowo[usuramagica]!=0) $session['user']['bonusfight'] = $session['user']['bonusfight'] - $rowo[turns_help];

        $deposito = $session[user][oggetto];
        $session[user][oggetto] = $session[user][zaino];
        $session[user][zaino] = $deposito;

        $session[user][attack] = $session[user][attack] + $rowz[attack_help];
        $session[user][defence] = $session[user][defence] + $rowz[defence_help];
        $session[user][bonusattack] = $session[user][bonusattack] + $rowz[attack_help];
        $session[user][bonusdefence] = $session[user][bonusdefence] + $rowz[defence_help];
        if ($rowz[usuramagica]!=0) $session[user][maxhitpoints] = $session[user][maxhitpoints] + $rowz[hp_help];
        if ($rowz[usuramagica]!=0) $session[user][hitpoints] = $session[user][hitpoints] + $rowz[hp_help];
//      if ($rowz[usuramagica]!=0) $session['user']['turns'] = $session['user']['turns'] + $rowz[turns_help];
        if ($rowz[usuramagica]!=0) $session['user']['bonusfight'] = $session['user']['bonusfight'] + $rowz[turns_help];
    }
}
page_footer();

?>