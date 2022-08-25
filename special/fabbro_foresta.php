<?php
// Funzioni per le riparazioni
$manodopera = 0/100;
$riparazione = 20/100;
function getCosto($value_arma, $percentuale_arma, $riparazione, $manodopera) {
    return intval(($value_arma * $percentuale_arma * $riparazione * (1 + $manodopera)) / 100);
}
function getPercentuale($usura_arma, $durata_max_usura_arma) {
    $percentuale = intval(100 - (($usura_arma * 100) / $durata_max_usura_arma));
    if ($percentuale > 100) $percentuale=100;
    return $percentuale;
}

//vettore che identifica le abilità
$abil=array(
1=>"darkarts",
2=>"magic",
3=>"thievery",
4=>"militare",
5=>"mystic",
6=>"tactic",
7=>"rockskin",
8=>"rhetoric",
9=>"muscle",
10=>"nature",
11=>"weather",
12=>"elementale",
13=>"barbaro",
14=>"bardo",
);

$skills = array(1 => "`\$Arti Oscure", "`%Poteri Mistici", "`^Furto", "`3Militare","`\$Seduzione","`^Tattica","`@Pelle di Roccia","`#Retorica","`%Muscoli","`3Natura","`&Clima","`^Elementalista","`6Rabbia Barbara","`5Canzoni del Bardo");

$statowpn = array(
0=>"Perfetta",
1=>"Come Nuova",
2=>"In Buone Condizioni",
3=>"Ammaccata",
4=>"Incrinata",
5=>"`\$Arrugginita",
);
$statoarm = array(
0=>"Perfetta",
1=>"Ben Tenuta",
2=>"Ammaccata",
3=>"Danneggiata",
4=>"Gravemente Danneggiata",
5=>"`\$Quasi a Pezzi",
);
$statoogg = array(
0=>"Perfetto",
1=>"Molto ben tenuto",
2=>"In buono stato",
3=>"Malconcio",
4=>"Danneggiato",
5=>"`\$Semidistrutto",
);
$session['user']['specialinc']="fabbro_foresta.php";


page_header("La bottega di Thor.");
if ($_GET['op']==""){
        output("`n`n`6Mentre vaghi per la foresta, ti ritrovi daventi ad un'enorme quercia... `n");
        output("Ti fermi ad ammirarla, e scopri una piccola porta mimetizzata tra le sue radici nel terreno.`n");
        output("Apri la porta e scopri uno stretto passaggio che scende nel sottosuolo.`n`n");
        output("`3Ti addentri nel passaggio, scendi le scale e percorri un lungo cunicolo. Dopo circa un centinaio di metri, vedi una");
        output("tenue luce davanti a te. Avvicinandoti, ti ritrovi dinanzi ad una stanza, di cui non riesci a vedere l'interno a causa di");
        output("una tendina messa all'ingresso.`n`nResti indeciso se entrare o tornare indietro, quando senti una voce provenire dall'interno della stanza:");
        output("\"`@Prego, vieni dentro, non essere timoroso`3\".`n`nA questo punto entri, e ti ritrovi davanti ad un uomo apparentemente giovane, ma con i capelli bianchi.`n`n");
        output("\"`@Benvenuto, il mio nome è Thor, e questa è la mia bottega. Mi occupo di costruire, incantare e riparare oggetti. Per un equo prezzo, posso riparare");
        output("o perfino migliorare un tuo oggetto, ma in questo momento ho tempo per una sola operazione. Ora, cosa posso fare per te?`3\"`n`n");
        addnav("`&Riparazione","forest.php?op=ripara");
        if ($session['user']['reincarna'] > 0 AND ($session['user']['oggetto']!=0 OR $session['user']['zaino']!=0)) addnav("O?`&Migliora Oggetto Magico","forest.php?op=oggetti");
        if ($session['user']['reincarna'] > 2) addnav("A?`&Artefatti","forest.php?op=artefatti");
        debuglog("incontra Thor nella sua bottega.");
        addnav("Esci");
        addnav("Torna nella Foresta","forest.php?op=foresta");

} elseif ($_GET['op'] == "ripara") {
        if ($_GET['og'] == "") {
            output("`3\"`@E cosa vorresti riparare?`3\"`n`n");
            // calcolo usura dell'arma
            $livello_arma = $session['user']['weapondmg'];
            $value_arma = $session['user']['weaponvalue'];
            $durata_max_usura_arma = intval($livello_arma * max((15 + $session['user']['dragonkills']/2 - 2*$session['user']['reincarna']),10)) + 100;
            $usura_arma = $session['user']['usura_arma'];
            $percentuale_arma = getPercentuale($usura_arma, $durata_max_usura_arma);
            $costo_arma = getCosto($value_arma, $percentuale_arma, $riparazione, $manodopera);
            if (donazioni('tessera_riparazioni')==true) {
                $costo_arma = round($costo_arma/2, 0);
                output("`@Mostri a Thor la tua tessera riparazioni, che ti dà diritto al 50% di sconto!`n`n");
            }
            if ($livello_arma == 0 || $percentuale_arma <= 0) {$percentuale_arma = 0; $costo_arma = 0;}
            $stwpn = round($percentuale_arma/20);
            $stato_arma = $statowpn[$stwpn];
            // calcolo usura dell'armatura
            $value_armatura = $session['user']['armorvalue'];
            $livello_armatura = $session['user']['armordef'];
            $durata_max_usura_armatura = intval($livello_armatura * max((15 + $session['user']['dragonkills']/2 - 2*$session['user']['reincarna']),10)) + 100;
            $usura_armatura = $session['user']['usura_armatura'];
            $percentuale_armatura = getPercentuale($usura_armatura, $durata_max_usura_armatura);
            $costo_armatura = getCosto($value_armatura, $percentuale_armatura, $riparazione, $manodopera);
            if (donazioni('tessera_riparazioni')==true) {
                $costo_armatura = round($costo_armatura/2, 0);
            }
            if ($livello_armatura == 0 || $percentuale_armatura <= 0) {$percentuale_armatura = 0; $costo_armatura = 0;}
            $starm = round($percentuale_armatura/20);
            $stato_armatura = $statoarm[$starm];
            output("<table border=0 cellpadding=2 cellspacing=1 align=center>",true);
            output("<tr class='trhead'><td>`bOggetto`b</td><td>`bStato`b</td><td>`bCosto Riparazione`b</td><td>`bOps`b</td>",true);
            if ($session['user']['superuser']>0) {
                output("<td>`bPercentuale`b</td>",true);
            }
            output("</tr>",true);
            output("<tr class='trlight'><td>`&".$session['user']['weapon']."</td><td>$stato_arma</td><td>`^$costo_arma Oro</td><td><A href=forest.php?op=ripara&og=arma&costo=".$costo_arma.">Ripara </a></td>",true);
            if ($session['user']['superuser']>0) {
                output("<td>`b".$percentuale_arma."`b</td>",true);
            }
            output("</tr>",true);
            output("<tr class='trdark'><td>`&".$session['user']['armor']."</td><td>$stato_armatura</td><td>`^$costo_armatura Oro</td><td><A href=forest.php?op=ripara&og=armatura&costo=".$costo_armatura.">Ripara </a></td>",true);
            if ($session['user']['superuser']>0) {
                output("<td>`b".$percentuale_armatura."`b</td>",true);
            }
            output("</tr>",true);
            addnav("","forest.php?op=ripara&og=arma&costo=$costo_arma");
            addnav("","forest.php?op=ripara&og=armatura&costo=$costo_armatura");
            if ($session['user']['oggetto'] != 0) {
                $sqlo = "SELECT * FROM oggetti WHERE id_oggetti = '{$session['user']['oggetto']}'";
                $resulto = db_query($sqlo) or die(db_error(LINK));
                $rowo = db_fetch_assoc($resulto);
                $ogg = $rowo['nome'];
                if ($rowo[usuramax]==-1 OR $rowo[usuramax]==0) {
                    $percentuale_oggetto = 0;
                } else {
                    $percentuale_oggetto = getPercentuale($rowo[usura], $rowo[usuramax]);
                }
                $value_oggetto = (100 + (10 * $rowo[livello]) * $rowo[valore]) ;
                $costo = getCosto($value_oggetto, $percentuale_oggetto, $riparazione, $manodopera);
                if (donazioni('tessera_riparazioni')==true) {
                    $costo = round($costo/2, 0);
                }
                $stogg = round($percentuale_oggetto/20);
                $stato_oggetto = $statoogg[$stogg];
                output("<tr class='trlight'><td>`&".$ogg."</td><td>$stato_oggetto</td><td>`^$costo Oro</td><td><A href=forest.php?op=ripara&og=oggetto&costo=".$costo.">Ripara </a></td>",true);
                if ($session['user']['superuser']>0) {
                    output("<td>`b".$percentuale_oggetto."`b</td>",true);
                }
                output("</tr>",true);
                addnav("","forest.php?op=ripara&og=oggetto&costo=$costo");
            }
            if ($session['user']['zaino'] != 0) {
                $sqlz = "SELECT * FROM oggetti WHERE id_oggetti = '{$session['user']['zaino']}'";
                $resultz = db_query($sqlz) or die(db_error(LINK));
                $rowz = db_fetch_assoc($resultz);
                $ogg = $rowz['nome'];
                if ($rowz[usuramax]==-1 OR $rowz[usuramax]==0) {
                    $percentuale_oggetto = 0;
                } else {
                    $percentuale_oggetto = getPercentuale($rowz[usura], $rowz[usuramax]);
                }
                $value_oggetto = (100 + (10 * $rowz[livello]) * $rowz[valore]) ;
                $costo = getCosto($value_oggetto, $percentuale_oggetto, $riparazione, $manodopera);
                if (donazioni('tessera_riparazioni')==true) {
                    $costo = round($costo/2, 0);
                }
                $stogg = round($percentuale_oggetto/20);
                $stato_oggetto = $statoogg[$stogg];
                output("<tr class='trdark'><td>`&".$ogg."</td><td>$stato_oggetto</td><td>`^$costo Oro</td><td><A href=forest.php?op=ripara&og=oggettozaino&costo=".$costo.">Ripara </a></td>",true);
                if ($session['user']['superuser']>0) {
                    output("<td>`b".$percentuale_oggetto."`b</td>",true);
                }
                output("</tr>",true);
                addnav("","forest.php?op=ripara&og=oggettozaino&costo=$costo");
            }
            output("</table>",true);
        } else {
            if ($session['user']['gold'] >= $_GET['costo']) {
                if ($_GET['og'] == "arma") {
                        $session['user']['usura_arma'] = (intval($session['user']['weapondmg'] * max((15 + $session['user']['dragonkills']/2 - 2*$session['user']['reincarna']),10)) + 100);
                        output("`3Thor prende il tuo ".$session['user']['weapon']." e inizia a lavorarci sopra.`n`n");
                        output("Dopo alcuni minuti te lo restituisce: \"`@Ecco a te, adesso è come nuovo!`3\"`n");
                        debuglog("si fa riparare l'arma da Thor spendendo ".$_GET['costo'].".");
                } elseif ($_GET['og'] == "armatura") {
                        $session['user']['usura_armatura'] = (intval($session['user']['armordef'] * max((15 + $session['user']['dragonkills']/2 - 2*$session['user']['reincarna']),10)) + 100);
                        output("`3Thor prende la tua ".$session['user']['armor']." e inizia a lavorarci sopra.`n`n");
                        output("Dopo alcuni minuti te la restituisce: \"`@Ecco a te, adesso è come nuovo!`3\"`n");
                        debuglog("si fa riparare l'armatura da Thor spendendo ".$_GET['costo'].".");
                } elseif ($_GET['og'] == "oggetto") {
                        $sqlo = "SELECT * FROM oggetti WHERE id_oggetti = '".$session['user']['oggetto']."'";
                        $resulto = db_query($sqlo) or die(db_error(LINK));
                        $rowo = db_fetch_assoc($resulto);
                        $sql="UPDATE oggetti SET usura='".$rowo[usuramax]."' WHERE id_oggetti='".$rowo[id_oggetti]."'";
                        db_query($sql) or die(db_error(LINK));
                        output("`3Thor prende il tuo ".$rowo['nome']." e inizia a lavorarci sopra.`n`n");
                        output("Dopo alcuni minuti te la restituisce: \"`@Ecco a te, adesso è come nuovo!`3\"`n");
                        debuglog("si fa riparare ".$rowo[nome]." da Thor spendendo ".$_GET['costo'].".");
                } elseif ($_GET['og'] == "oggettozaino") {
                        $sqlo = "SELECT * FROM oggetti WHERE id_oggetti = '".$session['user']['zaino']."'";
                        $resulto = db_query($sqlo) or die(db_error(LINK));
                        $rowo = db_fetch_assoc($resulto);
                        $sql="UPDATE oggetti SET usura='".$rowo[usuramax]."' WHERE id_oggetti='".$rowo[id_oggetti]."'";
                        db_query($sql) or die(db_error(LINK));
                        output("`3Thor prende il tuo ".$rowo['nome']." e inizia a lavorarci sopra.`n`n");
                        output("Dopo alcuni minuti te la restituisce: \"`@Ecco a te, adesso è come nuovo!`3\"`n");
                        debuglog("si fa riparare ".$rowo[nome]." da Thor spendendo ".$_GET['costo'].".");
                }
                $session['user']['gold'] -= $_GET['costo'];
            } else {
                output("`3\"`@E' un vero peccato che tu non possa pagarmi, ovviamente non riparerò il tuo oggetto`3\" ti dice Thor seccato");
            }
        }
        addnav("Esci");
        addnav("Torna nella Foresta","forest.php?op=foresta");
} elseif ($_GET['op'] == "oggetti") {
        if ($_GET['og'] == "") {
            output("`3\"`@Vediamo un po'... Qual'è l'oggetto in questione?`3\"`n`n");
            if ($session['user']['oggetto'] != 0) {
                $sqlo = "SELECT * FROM oggetti WHERE id_oggetti = '{$session['user']['oggetto']}'";
                $resulto = db_query($sqlo) or die(db_error(LINK));
                $rowo = db_fetch_assoc($resulto);
                output ("<table>", true);
                output ("<tr><td>`2Sei equipaggiato con : </td><td>" . $rowo['nome'] . "</td></tr>", true);
                if ($rowo['pregiato']==true) output ("<tr><td>`^`bL'oggetto è pregiato`b </td><td></td></tr>", true);
                output ("<tr><td>`7Aiuto HP: </td><td>`&" . $rowo['hp_help'] . "</td></tr>", true);
                output ("<tr><td>`4Aiuto Attacco: </td><td>`\$" . $rowo['attack_help'] . "</td></tr>", true);
                output ("<tr><td>`6Aiuto Difesa: </td><td>`^" . $rowo['defence_help'] . "</td></tr>", true);
                output ("<tr><td>`6Aiuto Turni: </td><td>`^" . $rowo['turns_help'] . "</td></tr>", true);
                if ($rowo['pvp_help']!=0) output ("<tr><td>`vAiuto PVP: </td><td>`^" . $rowo['pvp_help'] . "</td></tr>", true);
                if ($rowo['special_help']!=0) output ("<tr><td>`5Abilità: </td><td>`@" . $skills[$rowo['special_help']] . "(`^".$rowo['special_use_help']."`@)</td></tr>", true);
                if ($rowo['heal_help']!=0) output ("<tr><td>`5Cura: </td><td>`@" . $rowo['heal_help'] . " hp</td></tr>", true);
                if ($rowo['quest_help']!=0) output ("<tr><td>`5Quest: </td><td>`@" . $rowo['quest_help'] . "punti quest</td></tr>", true);
                if ($rowo['exp_help']!=0) output ("<tr><td>`5Esperienza: </td><td>`@+" . $rowo['exp_help'] . "%</td></tr>", true);
                output ("<tr><td>`2Cariche totali: </td><td>`@" . $rowo['potere'] . "</td></tr>", true);
                output ("<tr><td>`2Cariche residue:</td><td>`@" . $rowo['potere_uso'] . "</td></tr>", true);
                output ("<tr><td>`2Potenziamenti residui:</td><td>`@" . $rowo['potenziamenti'] . "</td></tr>", true);
                if ($rowo['usuramax']>0) output ("<tr><td>`)Usura fisica:</td><td>`(".intval(100-(100*$rowo['usura']/$rowo['usuramax']))."</td></tr>", true);
                else output ("<tr><td>`)Usura fisica:</td><td>`(Non Soggetto</td></tr>", true);
                if ($rowo['usuramagicamax']>0) output ("<tr><td>`)Usura magica:</td><td>`8".intval(100-(100*$rowo['usuramagica']/$rowo['usuramagicamax']))."</td></tr>", true);
                else output ("<tr><td>`)Usura magica:</td><td>`8Non Soggetto</td></tr>", true);
                output ("<tr><td>  </td><td>  </td></tr>", true);
                output ("<tr><td>`2Valore di Base: </td><td>`@" . $rowo['valore'] . "</td></tr>", true);
                output ("<tr><td>  </td><td>  </td></tr>", true);
                output ("<tr><td><A href=forest.php?op=oggetti&og=mano> Migliora </A></td></tr>", true);
                output ("</table>", true);
                output("`n`n`n");
                addnav("", "forest.php?op=oggetti&og=mano");
            }
            if ($session['user']['zaino'] != 0) {
                $sqlo = "SELECT * FROM oggetti WHERE id_oggetti = '{$session['user']['zaino']}'";
                $resulto = db_query($sqlo) or die(db_error(LINK));
                $rowo = db_fetch_assoc($resulto);
                output ("<table>", true);
                output ("<tr><td>`2Nello zaino hai : </td><td>" . $rowo['nome'] . "</td></tr>", true);
                if ($rowo['pregiato']==true) output ("<tr><td>`^`bL'oggetto è pregiato`b </td><td></td></tr>", true);
                output ("<tr><td>`7Aiuto HP: </td><td>`&" . $rowo['hp_help'] . "</td></tr>", true);
                output ("<tr><td>`4Aiuto Attacco: </td><td>`\$" . $rowo['attack_help'] . "</td></tr>", true);
                output ("<tr><td>`6Aiuto Difesa: </td><td>`^" . $rowo['defence_help'] . "</td></tr>", true);
                output ("<tr><td>`6Aiuto Turni: </td><td>`^" . $rowo['turns_help'] . "</td></tr>", true);
                if ($rowo['pvp_help']!=0) output ("<tr><td>`vAiuto PVP: </td><td>`^" . $rowo['pvp_help'] . "</td></tr>", true);
                if ($rowo['special_help']!=0) output ("<tr><td>`5Abilità: </td><td>`@" . $skills[$rowo['special_help']] . "(`^".$rowo['special_use_help']."`@)</td></tr>", true);
                if ($rowo['heal_help']!=0) output ("<tr><td>`5Cura: </td><td>`@" . $rowo['heal_help'] . " hp</td></tr>", true);
                if ($rowo['quest_help']!=0) output ("<tr><td>`5Quest: </td><td>`@" . $rowo['quest_help'] . "punti quest</td></tr>", true);
                if ($rowo['exp_help']!=0) output ("<tr><td>`5Esperienza: </td><td>`@+" . $rowo['exp_help'] . "%</td></tr>", true);
                output ("<tr><td>`2Cariche totali: </td><td>`@" . $rowo['potere'] . "</td></tr>", true);
                output ("<tr><td>`2Cariche residue:</td><td>`@" . $rowo['potere_uso'] . "</td></tr>", true);
                output ("<tr><td>`2Potenziamenti residui:</td><td>`@" . $rowo['potenziamenti'] . "</td></tr>", true);
                if ($rowo['usuramax']>0) output ("<tr><td>`)Usura fisica:</td><td>`(".intval(100-(100*$rowo['usura']/$rowo['usuramax']))."</td></tr>", true);
                else output ("<tr><td>`)Usura fisica:</td><td>`(Non Soggetto</td></tr>", true);
                if ($rowo['usuramagicamax']>0) output ("<tr><td>`)Usura magica:</td><td>`8".intval(100-(100*$rowo['usuramagica']/$rowo['usuramagicamax']))."</td></tr>", true);
                else output ("<tr><td>`)Usura magica:</td><td>`8Non Soggetto</td></tr>", true);
                output ("<tr><td>  </td><td>  </td></tr>", true);
                output ("<tr><td>`2Valore di Base: </td><td>`@" . $rowo['valore'] . "</td></tr>", true);
                output ("<tr><td>  </td><td>  </td></tr>", true);
                output ("<tr><td><A href=forest.php?op=oggetti&og=zaino> Migliora </A></td></tr>", true);
                output ("</table>", true);
                output("`n`n`n");
                addnav("", "forest.php?op=oggetti&og=zaino");
            }
        } else {
            if ($_GET['bonus'] == "") {
                output("`3\"`@Molto bene... In che modo devo migliorarlo?`3\"`n`n`n");
                if ($_GET['og'] == "mano") {
                    $sqlo = "SELECT * FROM oggetti WHERE id_oggetti = '{$session['user']['oggetto']}'";
                    $resulto = db_query($sqlo) or die(db_error(LINK));
                    $rowo = db_fetch_assoc($resulto);
                    output("<big>`c$rowo[nome]`c</big>`n`n`n",true);
                    output("<table cellspacing=1 cellpadding=2 align='center'><tr class='trdark'>
                             <td>`b`@Bonus`b</td>
                             <td>`b`#Costo`b</td>
                             <td>`b`^Potenziamenti necessari`b</td>
                             <td>`b`^Effetto`b</td>
                             <td>`b`&Note`b</td></tr>",true);
                    output("<tr class='trlight'><td>`b`@Attacco`b</td><td>`#10 gemme</td><td>`^1 potenziamento</td><td>`^+1 attacco </td><td></td></tr>",true);
                    output("<tr class='trlight'><td>`b`@Vita`b</td><td>`#10 gemme</td><td>`^1 potenziamento</td><td>`^+10 vita </td><td></td></tr>",true);
                    output("<tr class='trlight'><td>`b`@Difesa`b</td><td>`#10 gemme</td><td>`^1 potenziamento</td><td>`^+1 difesa </td><td></td></tr>",true);
                    output("<tr class='trlight'><td>`b`@Turni`b</td><td>`#20 gemme</td><td>`^1 potenziamento</td><td>`^+1 combattimenti foresta </td><td></td></tr>",true);
                    output("<tr class='trlight'><td>`b`@Resistenza Fisica`b</td><td>`#3 gemme</td><td>`^Nessun potenziamento</td><td>`^Minore usura </td><td></td></tr>",true);
                    if ($session['user']['reincarna'] > 2) output("<tr class='trlight'><td>`b`@Resistenza Fisica Completa`b</td><td>`#20 gemme</td><td>`^1 potenziamento</td><td>`^Immunità all'usura fisica </td>
                             <td>`&Richiede Usura Fisica < 10%</td></tr>",true);
                    output("<tr class='trlight'><td>`b`@Resistenza Magica`b</td><td>`#3 gemme</td><td>`^Nessun potenziamento</td><td>`^Minore usura magica</td><td></td></tr>",true);
                    if ($session['user']['reincarna'] > 2) output("<tr class='trlight'><td>`b`@Resistenza Magica Completa`b</td><td>`#20 gemme</td><td>`^1 potenziamento</td><td>`^Immunità all'usura magica </td>
                             <td>`&Richiede Usura Magica < 10%</td></tr>",true);
                    if ($session['user']['reincarna'] > 2) output("<tr class='trlight'><td>`b`@Abilità speciale`b</td><td>`#5 gemme</td><td>`^1 potenziamento</td><td>`^Conferisce un'abilità speciale al proprietario </td>
                             <td>`&L'oggetto può migliorare una sola abilità e non si può cambiare abilità per l'oggetto</td></tr>",true);
                    if ($session['user']['reincarna'] > 2) output("<tr class='trlight'><td>`b`@Guarigione`b</td><td>`#5 gemme</td><td>`^1 potenziamento</td><td>`^Conferisce la capacità di curare il proprietario </td>
                             <td>`&Conta come un'abilità speciale per l'oggetto</td></tr>",true);
                    if ($session['user']['reincarna'] > 4) output("<tr class='trlight'><td>`b`@Esperienza`b</td><td>`#10 gemme</td><td>`^1 potenziamento</td><td>`^Conferisce un bonus sull'esperienza di cui si è in possesso </td>
                             <td>`&Conta come un'abilità speciale per l'oggetto</td></tr>",true);
                    if ($session['user']['reincarna'] > 2) output("<tr class='trlight'><td>`b`@Utilizzi`b</td><td>`#5 gemme</td><td>`^1 potenziamento</td><td>`^Incrementa le cariche giornaliere a disposizione dell'oggetto </td>
                             <td>`&Solo la guarigione permette di avere più di una carica giornaliera</td></tr>",true);
                    output("</table>",true);

                    if ($session['user']['gems']>9) addnav("`\$Attacco", "forest.php?op=oggetti&og=mano&bonus=attacco");
                    if ($session['user']['gems']>9) addnav("`@Vita", "forest.php?op=oggetti&og=mano&bonus=vita");
                    if ($session['user']['gems']>9) addnav("`&Difesa", "forest.php?op=oggetti&og=mano&bonus=difesa");
                    if ($session['user']['gems']>19 AND $rowo[turns_help] < 10) addnav("`4Turni", "forest.php?op=oggetti&og=mano&bonus=turni");
                    if ($session['user']['gems']>2 AND $rowo[usuramax] > 0) addnav("`(Resistenza Fisica", "forest.php?op=oggetti&og=mano&bonus=resistenza");
                    if ($session['user']['gems']>19 AND $session['user']['reincarna'] > 2 AND (($rowo[usura]/$rowo[usuramax])>0.9) AND $rowo[usuramax] > 0) addnav("`8Resistenza Fisica (immune)", "forest.php?op=oggetti&og=mano&bonus=resistenzaimmune");
                    if ($session['user']['gems']>2 AND $rowo[usuramagicamax] > 0) addnav("`VResistenza Magica", "forest.php?op=oggetti&og=mano&bonus=magica");
                    if ($session['user']['gems']>19 AND $session['user']['reincarna'] > 2 AND (($rowo[usuramagica]/$rowo[usuramagicamax])>0.9) AND $rowo[usuramagicamax] > 0) addnav("`vResistenza Magica (immune)", "forest.php?op=oggetti&og=mano&bonus=magicaimmune");
                    if ($session['user']['gems']>4 AND $session['user']['reincarna'] > 2 AND ($rowo[heal_help]==0 AND $rowo[quest_help]==0 AND $rowo[exp_help]==0)) addnav("`!Abilità", "forest.php?op=oggetti&og=mano&bonus=abilita");
                    if ($session['user']['gems']>4 AND $session['user']['reincarna'] > 2 AND ($rowo[special_help]==0 AND $rowo[quest_help]==0 AND $rowo[exp_help]==0)) addnav("`#Guarigione", "forest.php?op=oggetti&og=mano&bonus=guarigione");
                    if ($session['user']['gems']>9 AND $session['user']['reincarna'] > 4 AND ($rowo[heal_help]==0 AND $rowo[quest_help]==0 AND $rowo[special_help]==0) AND $rowo[exp_help]<5) addnav("`3Esperienza", "forest.php?op=oggetti&og=mano&bonus=esperienza");
                    //if ($session['user']['gems']>4 AND $session['user']['reincarna'] > 2 AND ($rowo[special_help]==0 AND $rowo[quest_help]==0 AND $rowo[exp_help]==0 AND $rowo[potere]>0)) addnav("`4Utilizzi", "forest.php?op=oggetti&og=mano&bonus=utilizzi");
                    if ($session['user']['gems']>4 AND $session['user']['reincarna'] > 2 AND $rowo[heal_help]>0 AND $rowo[potere]>0) addnav("`4Utilizzi", "forest.php?op=oggetti&og=mano&bonus=utilizzi");
                } else {
                    $sqlo = "SELECT * FROM oggetti WHERE id_oggetti = '{$session['user']['zaino']}'";
                    $resulto = db_query($sqlo) or die(db_error(LINK));
                    $rowo = db_fetch_assoc($resulto);
                    output("<big>`c$rowo[nome]`c</big>`n`n`n",true);
                    output("<table cellspacing=1 cellpadding=2 align='center'><tr class='trdark'>
                             <td>`b`@Bonus`b</td>
                             <td>`b`#Costo`b</td>
                             <td>`b`^Potenziamenti necessari`b</td>
                             <td>`b`^Effetto`b</td>
                             <td>`b`&Note`b</td></tr>",true);
                    output("<tr class='trlight'><td>`b`@Attacco`b</td><td>`#10 gemme</td><td>`^1 potenziamento</td><td>`^+1 attacco </td><td></td></tr>",true);
                    output("<tr class='trlight'><td>`b`@Vita`b</td><td>`#10 gemme</td><td>`^1 potenziamento</td><td>`^+10 vita </td><td></td></tr>",true);
                    output("<tr class='trlight'><td>`b`@Difesa`b</td><td>`#10 gemme</td><td>`^1 potenziamento</td><td>`^+1 difesa </td><td></td></tr>",true);
                    output("<tr class='trlight'><td>`b`@Turni`b</td><td>`#20 gemme</td><td>`^1 potenziamento</td><td>`^+1 combattimenti foresta </td><td></td></tr>",true);
                    output("<tr class='trlight'><td>`b`@Resistenza Fisica`b</td><td>`#3 gemme</td><td>`^Nessun potenziamento</td><td>`^Minore usura </td><td></td></tr>",true);
                    if ($session['user']['reincarna'] > 2) output("<tr class='trlight'><td>`b`@Resistenza Fisica Completa`b</td><td>`#20 gemme</td><td>`^1 potenziamento</td><td>`^Immunità all'usura fisica </td>
                             <td>`&Richiede Usura Fisica < 10%</td></tr>",true);
                    output("<tr class='trlight'><td>`b`@Resistenza Magica`b</td><td>`#3 gemme</td><td>`^Nessun potenziamento</td><td>`^Minore usura magica</td><td></td></tr>",true);
                    if ($session['user']['reincarna'] > 2) output("<tr class='trlight'><td>`b`@Resistenza Magica Completa`b</td><td>`#20 gemme</td><td>`^1 potenziamento</td><td>`^Immunità all'usura magica </td>
                             <td>`&Richiede Usura Magica < 10%</td></tr>",true);
                    if ($session['user']['reincarna'] > 2) output("<tr class='trlight'><td>`b`@Abilità`b</td><td>`#5 gemme</td><td>`^1 potenziamento</td><td>`^Conferisce un'abilità speciale al proprietario </td>
                             <td>`&L'oggetto può migliorare una sola abilità e non si può cambiare abilità per l'oggetto</td></tr>",true);
                    if ($session['user']['reincarna'] > 2) output("<tr class='trlight'><td>`b`@Guarigione`b</td><td>`#5 gemme</td><td>`^1 potenziamento</td><td>`^Conferisce la capacità di curare il proprietario </td>
                             <td>`&Conta come un'abilità speciale per l'oggetto</td></tr>",true);
                    if ($session['user']['reincarna'] > 4) output("<tr class='trlight'><td>`b`@Esperienza`b</td><td>`#15 gemme</td><td>`^1 potenziamento</td><td>`^Conferisce un bonus sull'esperienza di cui si è in possesso </td>
                             <td>`&Conta come un'abilità speciale per l'oggetto</td></tr>",true);
                    if ($session['user']['reincarna'] > 2) output("<tr class='trlight'><td>`b`@Utilizzi`b</td><td>`#5 gemme</td><td>`^1 potenziamento</td><td>`^Incrementa le cariche giornaliere a disposizione dell'oggetto </td>
                             <td>`&Solo la guarigione permette di avere più di una carica giornaliera</td></tr>",true);
                    output("</table>",true);

                    if ($session['user']['gems']>9) addnav("`\$Attacco", "forest.php?op=oggetti&og=zaino&bonus=attacco");
                    if ($session['user']['gems']>9) addnav("`@Vita", "forest.php?op=oggetti&og=zaino&bonus=vita");
                    if ($session['user']['gems']>9) addnav("`&Difesa", "forest.php?op=oggetti&og=zaino&bonus=difesa");
                    if ($session['user']['gems']>19 AND $rowo[turns_help] < 10) addnav("`4Turni", "forest.php?op=oggetti&og=zaino&bonus=turni");
                    if ($session['user']['gems']>2 AND $rowo[usuramax] > 0) addnav("`(Resistenza Fisica", "forest.php?op=oggetti&og=zaino&bonus=resistenza");
                    if ($session['user']['gems']>19 AND $session['user']['reincarna'] > 2 AND (($rowo[usura]/$rowo[usuramax])>0.9) AND $rowo[usuramax] != -1) addnav("`8Resistenza Fisica (immune)", "forest.php?op=oggetti&og=zaino&bonus=resistenzaimmune");
                    if ($session['user']['gems']>2 AND $rowo[usuramagicamax] > 0) addnav("`VResistenza Magica", "forest.php?op=oggetti&og=zaino&bonus=magica");
                    if ($session['user']['gems']>19 AND $session['user']['reincarna'] > 2 AND (($rowo[usuramagica]/$rowo[usuramagicamax])>0.9) AND $rowo[usuramagicamax] != -1) addnav("`vResistenza Magica (immune)", "forest.php?op=oggetti&og=zaino&bonus=magicaimmune");
                    if ($session['user']['gems']>4 AND $session['user']['reincarna'] > 2 AND ($rowo[heal_help]==0 AND $rowo[quest_help]==0 AND $rowo[exp_help]==0)) addnav("`!Abilità", "forest.php?op=oggetti&og=zaino&bonus=abilita");
                    if ($session['user']['gems']>4 AND $session['user']['reincarna'] > 2 AND ($rowo[special_help]==0 AND $rowo[quest_help]==0 AND $rowo[exp_help]==0)) addnav("`#Guarigione", "forest.php?op=oggetti&og=zaino&bonus=guarigione");
                    if ($session['user']['gems']>9 AND $session['user']['reincarna'] > 4 AND ($rowo[heal_help]==0 AND $rowo[quest_help]==0 AND $rowo[special_help]==0) AND $rowo[exp_help]<5) addnav("`3Esperienza", "forest.php?op=oggetti&og=zaino&bonus=esperienza");
                    //if ($session['user']['gems']>4 AND $session['user']['reincarna'] > 2 AND ($rowo[special_help]==0 AND $rowo[quest_help]==0 AND $rowo[exp_help]==0 AND $rowo[potere]>0)) addnav("`4Utilizzi", "forest.php?op=oggetti&og=zaino&bonus=utilizzi");
                    if ($session['user']['gems']>4 AND $session['user']['reincarna'] > 2 AND $rowo[heal_help]>0 AND $rowo[potere]>0) addnav("`4Utilizzi", "forest.php?op=oggetti&og=zaino&bonus=utilizzi");
                }
            } else {
                if ($_GET['og'] == "mano") {
                    $sqlo = "SELECT * FROM oggetti WHERE id_oggetti = '{$session['user']['oggetto']}'";
                    $resulto = db_query($sqlo) or die(db_error(LINK));
                    $rowo = db_fetch_assoc($resulto);
                } else {
                    $sqlo = "SELECT * FROM oggetti WHERE id_oggetti = '{$session['user']['zaino']}'";
                    $resulto = db_query($sqlo) or die(db_error(LINK));
                    $rowo = db_fetch_assoc($resulto);
                }
                if ($rowo[potenziamenti] == 0 AND $_GET['bonus'] != "resistenza" AND $_GET['bonus'] != "magica") {
                    output("`3\"`@Mi spiace, ma non esiste alcun modo di potenziare ulteriormente il tuo oggetto`3\" ti dice Thor.");
                } else {
                    switch($_GET['bonus']) {
                        case "attacco":
                            $sql="UPDATE oggetti SET attack_help=attack_help+1, potenziamenti=potenziamenti-1 WHERE id_oggetti='".$rowo[id_oggetti]."'";
                            db_query($sql) or die(db_error(LINK));
                            output("`3L'attacco del tuo ".$rowo[nome]."`3 è stato migliorato di 1 punto.");
                            if ($_GET['og'] == "mano"){
                                $session['user']['attack']++;
                                $session['user']['bonusattack']++;
                            }
                            $session['user']['gems']-=10;
                            debuglog("potenzia l'attacco di ".$rowo[nome]." da Thor spendendo 10 gemme.");
                        break;
                        case "vita":
                            $sql="UPDATE oggetti SET hp_help=hp_help+10, potenziamenti=potenziamenti-1 WHERE id_oggetti='".$rowo[id_oggetti]."'";
                            db_query($sql) or die(db_error(LINK));
                            output("`3I punti vita del tuo ".$rowo[nome]."`3 sono stati migliorati di 10 punti.");
                            if ($_GET['og'] == "mano") $session['user']['maxhitpoints']+=10;
                            $session['user']['gems']-=10;
                            debuglog("potenzia i punti vita di ".$rowo[nome]." da Thor spendendo 10 gemme.");
                        break;
                        case "difesa":
                            $sql="UPDATE oggetti SET defence_help=defence_help+1, potenziamenti=potenziamenti-1 WHERE id_oggetti='".$rowo[id_oggetti]."'";
                            db_query($sql) or die(db_error(LINK));
                            output("`3La difesa del tuo ".$rowo[nome]."`3 è stata migliorata di 1 punto.");
                            if ($_GET['og'] == "mano"){
                                $session['user']['defence']++;
                                $session['user']['bonusdefence']++;
                            }
                            $session['user']['gems']-=10;
                            debuglog("potenzia la difesa di ".$rowo[nome]." da Thor spendendo 10 gemme.");
                        break;
                        case "turni":
                            $sql="UPDATE oggetti SET turns_help=turns_help+1, potenziamenti=potenziamenti-1 WHERE id_oggetti='".$rowo[id_oggetti]."'";
                            db_query($sql) or die(db_error(LINK));
                            output("`3I turni extra del tuo ".$rowo[nome]."`3 sono stati incrementati di 1.");
                            $session['user']['gems']-=20;
                            if ($_GET['og'] == "mano") {
                                $session['user']['turns'] ++;
                                $session['user']['bonusfight'] ++;
                            }
                            debuglog("potenzia i turni extra di ".$rowo[nome]." da Thor spendendo 20 gemme.");
                        break;
                        case "resistenza":
                            $extra = max( intval($rowo[usuramax]/10),50);
                            $sql="UPDATE oggetti SET usuramax=usuramax+$extra, usuraextra=usuraextra+$extra, usura=usura+$extra WHERE id_oggetti='".$rowo[id_oggetti]."'";
                            db_query($sql) or die(db_error(LINK));
                            output("`3La resistenza del tuo ".$rowo[nome]."`3 è stato migliorata.");
                            $session['user']['gems']-=3;
                            debuglog("potenzia la resistenza all'usura di ".$rowo[nome]." da Thor spendendo 3 gemme.");
                        break;
                        case "resistenzaimmune":
                            $sql="UPDATE oggetti SET usuramax=-1, usura=-1 WHERE id_oggetti='".$rowo[id_oggetti]."'";
                            db_query($sql) or die(db_error(LINK));
                            output("`3Il tuo ".$rowo[nome]."`3 ora non è più soggetto all'usura fisica.");
                            $session['user']['gems']-=20;
                            debuglog("rende immune all'usura fisica il suo ".$rowo[nome]." da Thor spendendo 20 gemme.");
                        break;
                        case "magica":
                            $extra = max( intval($rowo[usuramagicaamax]/10),50);
                            $sql="UPDATE oggetti SET usuramagicamax=usuramagicamax+$extra, usuramagicaextra=usuramagicaextra+$extra, usuramagica=usuramagica+$extra WHERE id_oggetti='".$rowo[id_oggetti]."'";
                            db_query($sql) or die(db_error(LINK));
                            output("`3La resistenza magica del tuo ".$rowo[nome]."`3 è stato migliorata.");
                            $session['user']['gems']-=3;
                            debuglog("potenzia la resistenza all'usura magica di ".$rowo[nome]." da Thor spendendo 3 gemme.");
                        break;
                        case "magicaimmune":
                            $sql="UPDATE oggetti SET usuramagicamax=-1, usuramagica=-1 WHERE id_oggetti='".$rowo[id_oggetti]."'";
                            db_query($sql) or die(db_error(LINK));
                            output("`3Il tuo ".$rowo[nome]."`3 ora non è più soggetto all'usura magica.");
                            $session['user']['gems']-=20;
                            debuglog("rende immune all'usura magica il suo ".$rowo[nome]." da Thor spendendo 20 gemme.");
                        break;
                        case "abilita":
                            if ($rowo[special_help]==0 AND !$_GET['spec']) {
                                output("A questo oggetto non è ancora stata assegnata una specialità. Quale secgli?`n`n");
                                for($i=1;$i<=count($skills);$i++) {
                                    output($skills[$i]."`n");
                                    addnav("`!Abilità ".$skills[$i], "forest.php?op=oggetti&og=".$_GET['og']."&bonus=abilita&spec=".$i);
                                }
                            } elseif ($rowo[special_help]==0 AND $_GET['spec']) {
                                $rowo[special_help]=$_GET['spec'];
                                debuglog("fissa l'abilità di ".$rowo[nome]." da Thor. Abilità scelta: ".$skills[$_GET['spec']]);
                            }
                            if ($rowo[special_help]!=0) {
                                $sql = "UPDATE oggetti SET potenziamenti=potenziamenti-1, special_use_help=special_use_help+1, special_help=$rowo[special_help] WHERE id_oggetti='".$rowo[id_oggetti]."'";
                                db_query($sql) or die(db_error(LINK));
                                if ($rowo[potere]==0) {
                                    $sql = "UPDATE oggetti SET potere=1 WHERE id_oggetti='".$rowo[id_oggetti]."'";
                                    db_query($sql) or die(db_error(LINK));
                                }
                                output("`3Il tuo ".$rowo[nome]." ora conferisce ".($rowo[special_use_help]+1)."`3 utilizzi dell'abilità ".$skills[$rowo[special_help]]."`3.");
                                $session['user']['gems']-=5;
                                debuglog("potenzia i livelli di specialità di ".$rowo[nome]." da Thor spendendo 5 gemme.");
                            }
                        break;
                        case "guarigione":
                            $sql = "UPDATE oggetti SET potenziamenti=potenziamenti-1, heal_help=heal_help+20 WHERE id_oggetti='".$rowo[id_oggetti]."'";
                            db_query($sql) or die(db_error(LINK));
                            if ($rowo[potere]==0) {
                                $sql = "UPDATE oggetti SET potere=1 WHERE id_oggetti='".$rowo[id_oggetti]."'";
                                db_query($sql) or die(db_error(LINK));
                            }
                            output("`3Il tuo ".$rowo[nome]."`3 ora può curare ".($rowo[heal_help]+10)." HP.");
                            $session['user']['gems']-=5;
                            debuglog("potenzia la capacità curativa di ".$rowo[nome]." da Thor spendendo 5 gemme.");
                        break;
                        case "quest":
                            $sql = "UPDATE oggetti SET potenziamenti=potenziamenti-1, quest_help=quest_help+1 WHERE id_oggetti='".$rowo[id_oggetti]."'";
                            db_query($sql) or die(db_error(LINK));
                            if ($rowo[potere]==0) {
                                $sql = "UPDATE oggetti SET potere=1 WHERE id_oggetti='".$rowo[id_oggetti]."'";
                                db_query($sql) or die(db_error(LINK));
                            }
                            output("`3Il tuo ".$rowo[nome]."`3 ora può rigenerare ".($rowo[quest_help]+1)." Punti Quest ogni newday.");
                            $session['user']['gems']-=100;
                            debuglog("potenzia la rigenerazione di punti quest di ".$rowo[nome]." da Thor spendendo 100 gemme.");
                        break;
                        case "esperienza":
                            $sql = "UPDATE oggetti SET potenziamenti=potenziamenti-1, exp_help=exp_help+2 WHERE id_oggetti='".$rowo[id_oggetti]."'";
                            db_query($sql) or die(db_error(LINK));
                            if ($rowo[potere]==0) {
                                $sql = "UPDATE oggetti SET potere=1 WHERE id_oggetti='".$rowo[id_oggetti]."'";
                                db_query($sql) or die(db_error(LINK));
                            }
                            output("`3Il tuo ".$rowo[nome]."`3 ora può incrementare la tua esperienza del ".($rowo[exp_help]+2)."%.");
                            $session['user']['gems']-=15;
                            debuglog("potenzia la capacità di aumentare l'esperienza di ".$rowo[nome]." da Thor spendendo 15 gemme.");
                        break;
                        case "utilizzi":
                            $sql = "UPDATE oggetti SET potenziamenti=potenziamenti-1, potere=potere+1 WHERE id_oggetti='".$rowo[id_oggetti]."'";
                            db_query($sql) or die(db_error(LINK));
                            output("`3Il tuo ".$rowo[nome]."`3 ora può essere utilizzato ".($rowo[potere]+1)." volte ogni newday.");
                            $session['user']['gems']-=5;
                            debuglog("aumenta gli utilizzi giornalieri di ".$rowo[nome]." da Thor spendendo 5 gemme.");
                        break;
                    }
                    //fine switch
                }
            }
        }
        addnav("Esci");
        addnav("Torna nella Foresta","forest.php?op=foresta");
} elseif ($_GET['op'] == "artefatti") {
        if ($_GET['og'] == "") {
            output("`3Senti Thor che tra sè e sè dice qualcosa:\"`@Artefatti... Gli oggetti più potenti mai creati, la frontiera tra i mortali e le divinità...`3\"`n`n");
            $in_possesso=0;
            if ($session['user']['oggetto'] != 0) {
                $sqlo = "SELECT dove FROM oggetti WHERE id_oggetti = '{$session['user']['oggetto']}'";
                $resulto = db_query($sqlo) or die(db_error(LINK));
                $rowo = db_fetch_assoc($resulto);
                if ($rowo['dove']==30) $in_possesso=1;
            }
            if ($session['user']['zaino'] != 0) {
                $sqlo = "SELECT dove FROM oggetti WHERE id_oggetti = '{$session['user']['zaino']}'";
                $resulto = db_query($sqlo) or die(db_error(LINK));
                $rowo = db_fetch_assoc($resulto);
                if ($rowo['dove']==30) $in_possesso=1;
            }
            if ($in_possesso==0) {
                output("`3Poi si rivolge a te mentre la sua voce si alza:\"`@Bene, quindi tu vorresti entrare in possesso di un artefatto?");
                output("Io te ne posso forgiare uno, e sono l'unico in grado di farlo, ma sappi che il procedimento ha il suo costo, parliamo di `^20000 pezzi d'oro `@e `&10 gemme`@.");
                output("Inoltre devi sapere che un artefatto inizialmente non possiede nessuna proprietà magica; la sua unica caratteristica è che virtualmente un artefatto");
                output("si può potenziare un numero infinito di volte, anche se aumentarne i potenziamenti disponibili è un lavoro complesso quasi come costruire un nuovo artefatto.`n");
                output("Devi inoltre sapere che un artefatto non è un comune oggetto, e non potrai disfartene semplicemente vendendolo... Difatti, l'artefatto sarà legato per sempre a te, non potrai perderlo e nessuno potrà comprarlo.`n");
                output("Inoltre, e questo è tutto, puoi possedere un unico artefatto; esso impedirà che tu possa entrare in possesso di un secondo.`n`n");
                output("Allora, vuoi che ti prepari un nuovo artefatto?`3\"`n`n");
                if ($session['user']['oggetto']!=0 AND $session['user']['zaino']!=0) output("`^Ma tu hai già un oggetto in mano ed uno nello zaino, e non potendo portare con te un terzo oggetto devi rifiutare...");
                if ($session['user']['gold']>19999 AND $session['user']['gems']>9 AND($session['user']['oggetto']==0 OR $session['user']['zaino']==0)) addnav("Costruisci nuovo artefatto","forest.php?op=crea_artefatto");
                addnav("No, grazie","forest.php?op=foresta");
            } else {
                if ($session['user']['oggetto'] != 0) {
                    $sqlo = "SELECT * FROM oggetti WHERE id_oggetti = '{$session['user']['oggetto']}'";
                    $resulto = db_query($sqlo) or die(db_error(LINK));
                    $rowo = db_fetch_assoc($resulto);
                    if ($rowo['dove']==30) {
                        $nomeartefatto=$rowo['nome'];
                        output ("<table>", true);
                        output ("<tr><td>`2Sei equipaggiato con : </td><td>" . $rowo['nome'] . "</td></tr>", true);
                        if ($rowo['pregiato']==true) output ("<tr><td>`^`bL'oggetto è pregiato`b </td><td></td></tr>", true);
                        output ("<tr><td>`7Aiuto HP: </td><td>`&" . $rowo['hp_help'] . "</td></tr>", true);
                        output ("<tr><td>`4Aiuto Attacco: </td><td>`\$" . $rowo['attack_help'] . "</td></tr>", true);
                        output ("<tr><td>`6Aiuto Difesa: </td><td>`^" . $rowo['defence_help'] . "</td></tr>", true);
                        output ("<tr><td>`6Aiuto Turni: </td><td>`^" . $rowo['turns_help'] . "</td></tr>", true);
                        if ($rowo['pvp_help']!=0) output ("<tr><td>`vAiuto PVP: </td><td>`^" . $rowo['pvp_help'] . "</td></tr>", true);
                        if ($rowo['gold_help']!=0) output ("<tr><td>`^Oro creato: </td><td>`@" . $rowo['gold_help'] . "/ newday</td></tr>", true);
                        if ($rowo['gems_help']!=0) output ("<tr><td>`&Gemme create: </td><td>`@" . $rowo['gems_help'] . "/ newday</td></tr>", true);
                        if ($rowo['special_help']!=0) output ("<tr><td>`5Abilità: </td><td>`@" . $skills[$rowo['special_help']] . "(`^".$rowo['special_use_help']."`@)</td></tr>", true);
                        if ($rowo['heal_help']!=0) output ("<tr><td>`5Cura: </td><td>`@" . $rowo['heal_help'] . " hp</td></tr>", true);
                        if ($rowo['quest_help']!=0) output ("<tr><td>`5Quest: </td><td>`@" . $rowo['quest_help'] . "punti quest</td></tr>", true);
                        if ($rowo['exp_help']!=0) output ("<tr><td>`5Esperienza: </td><td>`@+" . $rowo['exp_help'] . "%</td></tr>", true);
                        output ("<tr><td>`2Cariche totali: </td><td>`@" . $rowo['potere'] . "</td></tr>", true);
                        output ("<tr><td>`2Cariche residue:</td><td>`@" . $rowo['potere_uso'] . "</td></tr>", true);
                        output ("<tr><td>`2Potenziamenti residui:</td><td>`@" . $rowo['potenziamenti'] . "</td></tr>", true);
                        if ($rowo['usuramax']>0) output ("<tr><td>`)Usura fisica:</td><td>`(".intval(100-(100*$rowo['usura']/$rowo['usuramax']))."</td></tr>", true);
                        else output ("<tr><td>`)Usura fisica:</td><td>`(Non Soggetto</td></tr>", true);
                        if ($rowo['usuramagicamax']>0) output ("<tr><td>`)Usura magica:</td><td>`8".intval(100-(100*$rowo['usuramagica']/$rowo['usuramagicamax']))."</td></tr>", true);
                        else output ("<tr><td>`)Usura magica:</td><td>`8Non Soggetto</td></tr>", true);
                        output ("<tr><td>  </td><td>  </td></tr>", true);
                        output ("<tr><td>`2Valore di Base: </td><td>`@" . $rowo['valore'] . "</td></tr>", true);
                        output ("<tr><td>  </td><td>  </td></tr>", true);
                        output ("<tr><td><A href=forest.php?op=artefatti&og=mano> Migliora </A></td></tr>", true);
                        output ("</table>", true);
                        output("`n`n`n");
                        addnav("", "forest.php?op=artefatti&og=mano");
                    }
                }
                if ($session['user']['zaino'] != 0) {
                    $sqlo = "SELECT * FROM oggetti WHERE id_oggetti = '{$session['user']['zaino']}'";
                    $resulto = db_query($sqlo) or die(db_error(LINK));
                    $rowo = db_fetch_assoc($resulto);
                    if ($rowo['dove']==30) {
                        $nomeartefatto=$rowo['nome'];
                        output ("<table>", true);
                        output ("<tr><td>`2Nello zaino hai : </td><td>" . $rowo['nome'] . "</td></tr>", true);
                        if ($rowo['pregiato']==true) output ("<tr><td>`^`bL'oggetto è pregiato`b </td><td></td></tr>", true);
                        output ("<tr><td>`7Aiuto HP: </td><td>`&" . $rowo['hp_help'] . "</td></tr>", true);
                        output ("<tr><td>`4Aiuto Attacco: </td><td>`\$" . $rowo['attack_help'] . "</td></tr>", true);
                        output ("<tr><td>`6Aiuto Difesa: </td><td>`^" . $rowo['defence_help'] . "</td></tr>", true);
                        output ("<tr><td>`6Aiuto Turni: </td><td>`^" . $rowo['turns_help'] . "</td></tr>", true);
                        if ($rowo['pvp_help']!=0) output ("<tr><td>`vAiuto PVP: </td><td>`^" . $rowo['pvp_help'] . "</td></tr>", true);
                        if ($rowo['gold_help']!=0) output ("<tr><td>`^Oro creato: </td><td>`@" . $rowo['gold_help'] . "/ newday</td></tr>", true);
                        if ($rowo['gems_help']!=0) output ("<tr><td>`&Gemme create: </td><td>`@" . $rowo['gems_help'] . "/ newday</td></tr>", true);
                        if ($rowo['special_help']!=0) output ("<tr><td>`5Abilità: </td><td>`@" . $skills[$rowo['special_help']] . "(`^".$rowo['special_use_help']."`@)</td></tr>", true);
                        if ($rowo['heal_help']!=0) output ("<tr><td>`5Cura: </td><td>`@" . $rowo['heal_help'] . " hp</td></tr>", true);
                        if ($rowo['quest_help']!=0) output ("<tr><td>`5Quest: </td><td>`@" . $rowo['quest_help'] . "punti quest</td></tr>", true);
                        if ($rowo['exp_help']!=0) output ("<tr><td>`5Esperienza: </td><td>`@+" . $rowo['exp_help'] . "%</td></tr>", true);
                        output ("<tr><td>`2Cariche totali: </td><td>`@" . $rowo['potere'] . "</td></tr>", true);
                        output ("<tr><td>`2Cariche residue:</td><td>`@" . $rowo['potere_uso'] . "</td></tr>", true);
                        output ("<tr><td>`2Potenziamenti residui:</td><td>`@" . $rowo['potenziamenti'] . "</td></tr>", true);
                        if ($rowo['usuramax']>0) output ("<tr><td>`)Usura fisica:</td><td>`(".intval(100-(100*$rowo['usura']/$rowo['usuramax']))."</td></tr>", true);
                        else output ("<tr><td>`)Usura fisica:</td><td>`(Non Soggetto</td></tr>", true);
                        if ($rowo['usuramagicamax']>0) output ("<tr><td>`)Usura magica:</td><td>`8".intval(100-(100*$rowo['usuramagica']/$rowo['usuramagicamax']))."</td></tr>", true);
                        else output ("<tr><td>`)Usura magica:</td><td>`8Non Soggetto</td></tr>", true);
                        output ("<tr><td>  </td><td>  </td></tr>", true);
                        output ("<tr><td>`2Valore di Base: </td><td>`@" . $rowo['valore'] . "</td></tr>", true);
                        output ("<tr><td>  </td><td>  </td></tr>", true);
                        output ("<tr><td><A href=forest.php?op=artefatti&og=zaino> Migliora </A></td></tr>", true);
                        output ("</table>", true);
                        output("`n`n`n");
                        addnav("", "forest.php?op=artefatti&og=zaino");
                    }
                }
                output("`3Thor aggiunge:\"`@Forse avrai già notato che l'artefatto è legato al tuo destino e non puoi disfartene con le tue sole forze.");
                output("Io posso aiutarti in questa operazione, se lo desideri, ma nel farlo perderai ".$nomeartefatto." `@per sempre e non potrai mai più riottenerlo in nessun modo... a te la scelta.`3\"`n`n`n");
                addnav("Disfati di ".$nomeartefatto, "forest.php?op=rinuncia_artefatto");
            }
        } else {
            if ($_GET['bonus'] == "") {
                output("`3\"`@Così vuoi potenziare il tuo artefatto... In cosa devo migliorarlo?`3\"`n`n`n");
                if ($_GET['og'] == "mano") {
                    $sqlo = "SELECT * FROM oggetti WHERE id_oggetti = '{$session['user']['oggetto']}'";
                    $resulto = db_query($sqlo) or die(db_error(LINK));
                    $rowo = db_fetch_assoc($resulto);
                    output("<big>`c$rowo[nome]`c</big>`n`n`n",true);
                    output("<table cellspacing=1 cellpadding=2 align='center'><tr class='trdark'>
                             <td>`b`@Bonus`b</td>
                             <td>`b`#Costo`b</td>
                             <td>`b`^Potenziamenti necessari`b</td>
                             <td>`b`^Effetto`b</td>
                             <td>`b`&Note`b</td></tr>",true);
                    output("<tr class='trlight'><td>`b`@Attacco`b</td><td>`#10 gemme</td><td>`^1 potenziamento</td><td>`^+2 attacco </td><td></td></tr>",true);
                    output("<tr class='trlight'><td>`b`@Vita`b</td><td>`#10 gemme</td><td>`^1 potenziamento</td><td>`^+20 vita </td><td></td></tr>",true);
                    output("<tr class='trlight'><td>`b`@Difesa`b</td><td>`#10 gemme</td><td>`^1 potenziamento</td><td>`^+2 difesa </td><td></td></tr>",true);
                    output("<tr class='trlight'><td>`b`@Turni`b</td><td>`#20 gemme</td><td>`^1 potenziamento</td><td>`^+1 combattimenti foresta </td>
                             <td>`&Non si può superare il limite di 10 turni extra</td></tr>",true);
                    output("<tr class='trlight'><td>`b`@PVP`b</td><td>`#100 gemme</td><td>`^4 potenziamenti</td><td>`^+1 combattimenti PVP </td>
                             <td>`&Non si può superare il limite di 1 PVP extra</td></tr>",true);
                    output("<tr class='trlight'><td>`b`@Oro`b</td><td>`#30 gemme</td><td>`^2 potenziamenti</td><td>`^+200 pezzi d'oro/giorno </td>
                             <td>`&Non si può superare il limite di 1000 pezzi d'oro/giorno</td></tr>",true);
                    if ($session['user']['reincarna'] > 3) output("<tr class='trlight'><td>`b`@Gemme`b</td><td>`#50 gemme</td><td>`^5 potenziamenti</td><td>`^+1 gemma/giorno </td>
                             <td>`&Non si può superare il limite di 2 gemme/giorno</td></tr>",true);
                    output("<tr class='trlight'><td>`b`@Abilità`b</td><td>`#5 gemme</td><td>`^1 potenziamento</td><td>`^Conferisce un'abilità speciale al proprietario </td>
                             <td>`&L'artefatto può migliorare una sola abilità e non si può cambiare abilità per l'artefatto</td></tr>",true);
                    output("<tr class='trlight'><td>`b`@Guarigione`b</td><td>`#5 gemme</td><td>`^1 potenziamento</td><td>`^Conferisce la capacità di curare il proprietario </td>
                             <td>`&Conta come un'abilità speciale per l'artefatto</td></tr>",true);
                    if ($session['user']['reincarna'] > 4) output("<tr class='trlight'><td>`b`@Esperienza`b</td><td>`#15 gemme</td><td>`^1 potenziamento</td><td>`^Conferisce un bonus sull'esperienza di cui si è in possesso </td>
                             <td>`&Conta come un'abilità speciale per l'artefatto. Limite massimo 5%</td></tr>",true);
//                    if ($session['user']['reincarna'] > 4) output("<tr class='trlight'><td>`b`@Quest`b</td><td>`#100 gemme</td><td>`^3 potenziamenti</td><td>`^Rigenera 1 punto quest a newday </td>
//                             <td>`&Conta come un'abilità speciale per l'artefatto, non si può superare il limite di 1 punto quest/giorno</td></tr>",true);
                    output("<tr class='trlight'><td>`b`@Utilizzi`b</td><td>`#5 gemme</td><td>`^1 potenziamento</td><td>`^Incrementa le cariche giornaliere a disposizione dell'oggetto </td>
                             <td>`&Solo la guarigione permette di avere più di una carica giornaliera</td></tr>",true);
                    output("<tr class='trlight'><td>`b`&Potenziamenti`b</td><td>`#".(($rowo[livello]+1)*10)." gemme</td><td>`^Nessun potenziamento</td><td>`^Aumenta di 2 i potenziamenti a disposizione dell'artefatto </td>
                             <td>`&Aumenta di 1 il livello dell'artefatto</td></tr>",true);
                    output("</table>",true);

                    if ($session['user']['gems']>9) addnav("`\$Attacco", "forest.php?op=artefatti&og=mano&bonus=attacco&pot=1");
                    if ($session['user']['gems']>9) addnav("`@Vita", "forest.php?op=artefatti&og=mano&bonus=vita&pot=1");
                    if ($session['user']['gems']>9) addnav("`&Difesa`0", "forest.php?op=artefatti&og=mano&bonus=difesa&pot=1");
                    if ($session['user']['gems']>19 AND $rowo[turns_help] < 10) addnav("`4Turni", "forest.php?op=artefatti&og=mano&bonus=turni&pot=1");
                    if ($session['user']['gems']>99 AND $rowo[pvp_help] < 1) addnav("`vPVP", "forest.php?op=artefatti&og=mano&bonus=pvp&pot=4");
                    if ($session['user']['gems']>29 AND $rowo[gold_help] < 1000) addnav("`^Oro", "forest.php?op=artefatti&og=mano&bonus=oro&pot=2");
                    if ($session['user']['gems']>49 AND $session['user']['reincarna'] > 3 AND $rowo[gems_help] < 2) addnav("`&Gemme", "forest.php?op=artefatti&og=mano&bonus=gemme&pot=5");
                    if ($session['user']['gems']>4 AND $rowo[heal_help]==0 AND $rowo[quest_help]==0 AND $rowo[exp_help]==0) addnav("`!Abilità", "forest.php?op=artefatti&og=mano&bonus=abilita&pot=1");
                    if ($session['user']['gems']>4 AND $rowo[special_help]==0 AND $rowo[quest_help]==0 AND $rowo[exp_help]==0) addnav("`#Guarigione", "forest.php?op=artefatti&og=mano&bonus=guarigione&pot=1");
                    if ($session['user']['gems']>14 AND $session['user']['reincarna'] > 4 AND ($rowo[heal_help]==0 AND $rowo[quest_help]==0 AND $rowo[special_help]==0) AND $rowo[exp_help]<5) addnav("`3Esperienza", "forest.php?op=artefatti&og=mano&bonus=esperienza&pot=1");
//                    if ($session['user']['gems']>99 AND $session['user']['reincarna'] > 4 AND ($rowo[heal_help]==0 AND $rowo[quest_help]==0 AND $rowo[special_help]==0 AND $rowo[exp_help]==0)) addnav("`7Punti Quest", "forest.php?op=artefatti&og=mano&bonus=quest&pot=3");
                    //if ($session['user']['gems']>4 AND $rowo[special_help]==0 AND $rowo[quest_help]==0 AND $rowo[exp_help]==0 AND $rowo[potere]>0) addnav("`4Utilizzi", "forest.php?op=artefatti&og=mano&bonus=utilizzi&pot=1");
                    if ($session['user']['gems']>4 AND $rowo[heal_help]>0 AND $rowo[potere]>0) addnav("`4Utilizzi", "forest.php?op=artefatti&og=mano&bonus=utilizzi&pot=1");
                    if ($session['user']['gems']>(($rowo[livello]+1)*10) AND $rowo[livello]<=($session['user']['reincarna']*3+min(intval($session['user']['dragonkills']/10),2))) addnav("`b&Potenziamenti`b", "forest.php?op=artefatti&og=mano&bonus=potenziamenti&pot=0");
                } else {
                    $sqlo = "SELECT * FROM oggetti WHERE id_oggetti = '{$session['user']['zaino']}'";
                    $resulto = db_query($sqlo) or die(db_error(LINK));
                    $rowo = db_fetch_assoc($resulto);
                    output("<big>`c$rowo[nome]`c</big>`n`n`n",true);
                    output("<table cellspacing=1 cellpadding=2 align='center'><tr class='trdark'>
                             <td>`b`@Bonus`b</td>
                             <td>`b`#Costo`b</td>
                             <td>`b`^Potenziamenti necessari`b</td>
                             <td>`b`^Effetto`b</td>
                             <td>`b`&Note`b</td></tr>",true);
                    output("<tr class='trlight'><td>`b`@Attacco`b</td><td>`#10 gemme</td><td>`^1 potenziamento</td><td>`^+2 attacco </td><td></td></tr>",true);
                    output("<tr class='trlight'><td>`b`@Vita`b</td><td>`#10 gemme</td><td>`^1 potenziamento</td><td>`^+20 vita </td><td></td></tr>",true);
                    output("<tr class='trlight'><td>`b`@Difesa`b</td><td>`#10 gemme</td><td>`^1 potenziamento</td><td>`^+2 difesa </td><td></td></tr>",true);
                    output("<tr class='trlight'><td>`b`@Turni`b</td><td>`#20 gemme</td><td>`^1 potenziamento</td><td>`^+1 combattimenti foresta </td>
                             <td>`&Non si può superare il limite di 10 turni extra</td></tr>",true);
                    output("<tr class='trlight'><td>`b`@PVP`b</td><td>`#100 gemme</td><td>`^4 potenziamenti</td><td>`^+1 combattimenti PVP </td>
                             <td>`&Non si può superare il limite di 1 PVP extra</td></tr>",true);
                    output("<tr class='trlight'><td>`b`@Oro`b</td><td>`#30 gemme</td><td>`^2 potenziamenti</td><td>`^+200 pezzi d'oro/giorno </td>
                             <td>`&Non si può superare il limite di 1000 pezzi d'oro/giorno</td></tr>",true);
                    if ($session['user']['reincarna'] > 3) output("<tr class='trlight'><td>`b`@Gemme`b</td><td>`#50 gemme</td><td>`^5 potenziamenti</td><td>`^+1 gemma/giorno </td>
                             <td>`&Non si può superare il limite di 2 gemme/giorno</td></tr>",true);
                    output("<tr class='trlight'><td>`b`@Abilità`b</td><td>`#5 gemme</td><td>`^1 potenziamento</td><td>`^Conferisce un'abilità speciale al proprietario </td>
                             <td>`&L'artefatto può migliorare una sola abilità e non si può cambiare abilità per l'artefatto</td></tr>",true);
                    output("<tr class='trlight'><td>`b`@Guarigione`b</td><td>`#5 gemme</td><td>`^1 potenziamento</td><td>`^Conferisce la capacità di curare il proprietario </td>
                             <td>`&Conta come un'abilità speciale per l'artefatto</td></tr>",true);
                    if ($session['user']['reincarna'] > 4) output("<tr class='trlight'><td>`b`@Esperienza`b</td><td>`#15 gemme</td><td>`^1 potenziamento</td><td>`^Conferisce un bonus sull'esperienza di cui si è in possesso </td>
                             <td>`&Conta come un'abilità speciale per l'artefatto. Limite massimo 5%</td></tr>",true);
//                    if ($session['user']['reincarna'] > 4) output("<tr class='trlight'><td>`b`@Quest`b</td><td>`#100 gemme</td><td>`^3 potenziamenti</td><td>`^Rigenera 1 punto quest a newday </td>
//                             <td>`&Conta come un'abilità speciale per l'artefatto, non si può superare il limite di 1 punto quest/giorno</td></tr>",true);
                    output("<tr class='trlight'><td>`b`@Utilizzi`b</td><td>`#5 gemme</td><td>`^1 potenziamento</td><td>`^Incrementa le cariche giornaliere a disposizione dell'oggetto </td>
                             <td>`&Solo la guarigione permette di avere più di una carica giornaliera</td></tr>",true);
                    output("<tr class='trlight'><td>`b`&Potenziamenti`b</td><td>`#".(($rowo[livello]+1)*10)." gemme</td><td>`^Nessun potenziamento</td><td>`^Aumenta di 2 i potenziamenti a disposizione dell'artefatto </td>
                             <td>`&Aumenta di 1 il livello dell'artefatto</td></tr>",true);
                    output("</table>",true);

                    if ($session['user']['gems']>9) addnav("`\$Attacco", "forest.php?op=artefatti&og=zaino&bonus=attacco&pot=1");
                    if ($session['user']['gems']>9) addnav("`@Vita", "forest.php?op=artefatti&og=zaino&bonus=vita&pot=1");
                    if ($session['user']['gems']>9) addnav("`&Difesa`0", "forest.php?op=artefatti&og=zaino&bonus=difesa&pot=1");
                    if ($session['user']['gems']>19 AND $rowo[turns_help] < 10) addnav("`4Turni", "forest.php?op=artefatti&og=zaino&bonus=turni&pot=1");
                    if ($session['user']['gems']>99 AND $rowo[pvp_help] < 1) addnav("`vPVP", "forest.php?op=artefatti&og=zaino&bonus=pvp&pot=4");
                    if ($session['user']['gems']>29 AND $rowo[gold_help] < 1000) addnav("`^Oro", "forest.php?op=artefatti&og=zaino&bonus=oro&pot=2");
                    if ($session['user']['gems']>49 AND $session['user']['reincarna'] > 3 AND $rowo[gems_help] < 2) addnav("`&Gemme", "forest.php?op=artefatti&og=zaino&bonus=gemme&pot=5");
                    if ($session['user']['gems']>4 AND $rowo[heal_help]==0 AND $rowo[quest_help]==0 AND $rowo[exp_help]==0) addnav("`!Abilità", "forest.php?op=artefatti&og=zaino&bonus=abilita&pot=1");
                    if ($session['user']['gems']>4 AND $rowo[special_help]==0 AND $rowo[quest_help]==0 AND $rowo[exp_help]==0) addnav("`#Guarigione", "forest.php?op=artefatti&og=zaino&bonus=guarigione&pot=1");
                    if ($session['user']['gems']>14 AND $session['user']['reincarna'] > 4 AND ($rowo[heal_help]==0 AND $rowo[quest_help]==0 AND $rowo[special_help]==0) AND $rowo[exp_help]<5) addnav("`3Esperienza", "forest.php?op=artefatti&og=zaino&bonus=esperienza&pot=1");
//                    if ($session['user']['gems']>99 AND $session['user']['reincarna'] > 4 AND ($rowo[heal_help]==0 AND $rowo[quest_help]==0 AND $rowo[special_help]==0 AND $rowo[exp_help]==0)) addnav("`7Punti Quest", "forest.php?op=artefatti&og=zaino&bonus=quest&pot=3");
                    //if ($session['user']['gems']>4 AND $rowo[special_help]==0 AND $rowo[quest_help]==0 AND $rowo[exp_help]==0 AND $rowo[potere]>0) addnav("`4Utilizzi", "forest.php?op=artefatti&og=zaino&bonus=utilizzi&pot=1");
                    if ($session['user']['gems']>4 AND $rowo[heal_help]>0 AND $rowo[potere]>0) addnav("`4Utilizzi", "forest.php?op=artefatti&og=zaino&bonus=utilizzi&pot=1");
                    if ($session['user']['gems']>(($rowo[livello]+1)*10) AND $rowo[livello]<=($session['user']['reincarna']*3+min(intval($session['user']['dragonkills']/10),2))) addnav("`b`&Potenziamenti`b", "forest.php?op=artefatti&og=zaino&bonus=potenziamenti&pot=0");
                }
            } else {
                if ($_GET['og'] == "mano") {
                    $sqlo = "SELECT * FROM oggetti WHERE id_oggetti = '{$session['user']['oggetto']}'";
                    $resulto = db_query($sqlo) or die(db_error(LINK));
                    $rowo = db_fetch_assoc($resulto);
                } else {
                    $sqlo = "SELECT * FROM oggetti WHERE id_oggetti = '{$session['user']['zaino']}'";
                    $resulto = db_query($sqlo) or die(db_error(LINK));
                    $rowo = db_fetch_assoc($resulto);
                }
                if ($rowo[potenziamenti] < $_GET['pot']) {
                    output("`3\"`@Mi spiace, ma il tuo artefatto non dispone di un numero sufficiente di potenziamenti, prima di tutto dovrai incrementare quelli.`3\" ti dice Thor.");
                } else {
                    switch($_GET['bonus']) {
                        case "attacco":
                            $sql="UPDATE oggetti SET attack_help=attack_help+2, potenziamenti=potenziamenti-1 WHERE id_oggetti='".$rowo[id_oggetti]."'";
                            db_query($sql) or die(db_error(LINK));
                            output("`3L'attacco del tuo ".$rowo[nome]."`3 è stato migliorato di 2 punti.");
                            if ($_GET['og'] == "mano"){
                                $session['user']['attack']+=2;
                                $session['user']['bonusattack']+=2;
                            }
                            $session['user']['gems']-=10;
                            debuglog("potenzia l'attacco di ".$rowo[nome]." da Thor `&(artefatto)`0 spendendo 10 gemme.");
                        break;
                        case "vita":
                            $sql="UPDATE oggetti SET hp_help=hp_help+20, potenziamenti=potenziamenti-1 WHERE id_oggetti='".$rowo[id_oggetti]."'";
                            db_query($sql) or die(db_error(LINK));
                            output("`3I punti vita del tuo ".$rowo[nome]."`3 sono stati migliorati di 20 punti.");
                            if ($_GET['og'] == "mano") $session['user']['maxhitpoints']+=20;
                            $session['user']['gems']-=10;
                            debuglog("potenzia i punti vita di ".$rowo[nome]." da Thor `&(artefatto)`0 spendendo 10 gemme.");
                        break;
                        case "difesa":
                            $sql="UPDATE oggetti SET defence_help=defence_help+2, potenziamenti=potenziamenti-1 WHERE id_oggetti='".$rowo[id_oggetti]."'";
                            db_query($sql) or die(db_error(LINK));
                            output("`3La difesa del tuo ".$rowo[nome]."`3 è stata migliorata di 2 punti.");
                            if ($_GET['og'] == "mano"){
                                $session['user']['defence']+=2;
                                $session['user']['bonusdefence']+=2;
                            }
                            $session['user']['gems']-=10;
                            debuglog("potenzia la difesa di ".$rowo[nome]." da Thor `&(artefatto)`0 spendendo 10 gemme.");
                        break;
                        case "turni":
                            $sql="UPDATE oggetti SET turns_help=turns_help+1, potenziamenti=potenziamenti-1 WHERE id_oggetti='".$rowo[id_oggetti]."'";
                            db_query($sql) or die(db_error(LINK));
                            output("`3I turni extra del tuo ".$rowo[nome]."`3 sono stati incrementati di 1.");
                            if ($_GET['og'] == "mano") {
                                $session['user']['turns'] ++;
                                $session['user']['bonusfight'] ++;
                            }
                            $session['user']['gems']-=20;
                            debuglog("potenzia i turni extra di ".$rowo[nome]." da Thor `&(artefatto)`0 spendendo 20 gemme.");
                        break;
                        case "pvp":
                            $sql = "UPDATE oggetti SET potenziamenti=potenziamenti-4, pvp_help=pvp_help+1 WHERE id_oggetti='".$rowo[id_oggetti]."'";
                            db_query($sql) or die(db_error(LINK));
                            output("`3Il tuo ".$rowo[nome]."`3 ora ti concede ".($rowo[pvp_help]+1)." PVP extra ogni newday.");
                            $session['user']['gems']-=100;
                            debuglog("potenzia i PVP extra di ".$rowo[nome]." da Thor `&(artefatto)`0 spendendo 100 gemme.");
                        break;
                        case "oro":
                            if ($rowo['gold_help']<800) {
                                $sql="UPDATE oggetti SET gold_help=gold_help+200, potenziamenti=potenziamenti-1 WHERE id_oggetti='".$rowo[id_oggetti]."'";
                                output("`3Il tuo ".$rowo[nome]."`3 ora può generare ".($rowo[gold_help]+200)." pezzi d'oro ogni newday.");
                                debuglog("potenzia l'oro generato per newday di ".$rowo[nome]." portandolo a ".($rowo[gold_help]+200)." da Thor spendendo 30 gemme.");
                            } else {
                                $sql="UPDATE oggetti SET gold_help=1000, potenziamenti=potenziamenti-2 WHERE id_oggetti='".$rowo[id_oggetti]."'";
                                output("`3Il tuo ".$rowo[nome]."`3 ora può generare 1000 pezzi d'oro ogni newday.");
                                debuglog("potenzia l'oro generato per newday di ".$rowo[nome]." portandolo a 1000 da Thor `&(artefatto)`0 spendendo 30 gemme.");
                        }
                            db_query($sql) or die(db_error(LINK));
                            $session['user']['gems']-=30;
                        break;
                        case "gemme":
                            $sql="UPDATE oggetti SET gems_help=gems_help+1, potenziamenti=potenziamenti-5 WHERE id_oggetti='".$rowo[id_oggetti]."'";
                            db_query($sql) or die(db_error(LINK));
                            output("`3Il tuo ".$rowo[nome]."`3 ora può generare ".($rowo[gems_help]+1)." gemme ogni newday.");
                            $session['user']['gems']-=50;
                            debuglog("potenzia le gemme generate per newday di ".$rowo[nome]." portandole a ".($rowo[gems_help]+1)." da Thor `&(artefatto)`0 spendendo 50 gemme.");
                        break;
                        case "abilita":
                            if ($rowo[special_help]==0 AND !$_GET['spec']) {
                                output("A questo oggetto non è ancora stata assegnata una specialità. Quale secgli?`n`n");
                                for($i=1;$i<=count($skills);$i++) {
                                    output($skills[$i]."`n");
                                    addnav("`!Abilità ".$skills[$i], "forest.php?op=oggetti&og=".$_GET['og']."&bonus=abilita&spec=".$i);
                                }
                            } elseif ($rowo[special_help]==0 AND $_GET['spec']) {
                                $rowo[special_help]=$_GET['spec'];
                                debuglog("fissa l'abilità di ".$rowo[nome]." da Thor. Abilità scelta: ".$skills[$_GET['spec']]." `&(artefatto)`0");
                            }
                            if ($rowo[special_help]!=0) {
                                $sql = "UPDATE oggetti SET potenziamenti=potenziamenti-1, special_use_help=special_use_help+1, special_help=$rowo[special_help] WHERE id_oggetti='".$rowo[id_oggetti]."'";
                                db_query($sql) or die(db_error(LINK));
                                if ($rowo[potere]==0) {
                                    $sql = "UPDATE oggetti SET potere=1 WHERE id_oggetti='".$rowo[id_oggetti]."'";
                                    db_query($sql) or die(db_error(LINK));
                                }
                                output("`3Il tuo ".$rowo[nome]."`3 ora conferisce ".($rowo[special_use_help]+1)." utilizzi dell'abilità ".$skills[$rowo[special_help]]."`3.");
                                $session['user']['gems']-=5;
                                debuglog("potenzia i livelli di specialità di ".$rowo[nome]." da Thor `&(artefatto)`0 spendendo 5 gemme.");
                            }
                        break;
                        case "guarigione":
                            $sql = "UPDATE oggetti SET potenziamenti=potenziamenti-1, heal_help=heal_help+20 WHERE id_oggetti='".$rowo[id_oggetti]."'";
                            db_query($sql) or die(db_error(LINK));
                            if ($rowo[potere]==0) {
                                $sql = "UPDATE oggetti SET potere=1 WHERE id_oggetti='".$rowo[id_oggetti]."'";
                                db_query($sql) or die(db_error(LINK));
                            }
                            output("`3Il tuo ".$rowo[nome]."`3 ora può curare ".($rowo[heal_help]+20)." HP.");
                            $session['user']['gems']-=5;
                            debuglog("potenzia la capacità curativa di ".$rowo[nome]." da Thor `&(artefatto)`0 spendendo 5 gemme.");
                        break;
                        case "quest":
                            $sql = "UPDATE oggetti SET potenziamenti=potenziamenti-3, quest_help=quest_help+1 WHERE id_oggetti='".$rowo[id_oggetti]."'";
                            db_query($sql) or die(db_error(LINK));
                            if ($rowo[potere]==0) {
                                $sql = "UPDATE oggetti SET potere=1 WHERE id_oggetti='".$rowo[id_oggetti]."'";
                                db_query($sql) or die(db_error(LINK));
                            }
                            output("`3Il tuo ".$rowo[nome]."`3 ora può rigenerare ".($rowo[quest_help]+1)." Punti Quest ogni newday.");
                            $session['user']['gems']-=100;
                            debuglog("potenzia la rigenerazione di punti quest di ".$rowo[nome]." da Thor `&(artefatto)`0 spendendo 100 gemme.");
                        break;
                        case "esperienza":
                            $sql = "UPDATE oggetti SET potenziamenti=potenziamenti-1, exp_help=exp_help+5 WHERE id_oggetti='".$rowo[id_oggetti]."'";
                            db_query($sql) or die(db_error(LINK));
                            if ($rowo[potere]==0) {
                                $sql = "UPDATE oggetti SET potere=1 WHERE id_oggetti='".$rowo[id_oggetti]."'";
                                db_query($sql) or die(db_error(LINK));
                            }
                            output("`3Il tuo ".$rowo[nome]."`3 ora può incrementare la tua esperienza del ".($rowo[exp_help]+5)."%.");
                            $session['user']['gems']-=15;
                            debuglog("potenzia la capacità di aumentare l'esperienza di ".$rowo[nome]." da Thor `&(artefatto)`0 spendendo 15 gemme.");
                        break;
                        case "utilizzi":
                            $sql = "UPDATE oggetti SET potenziamenti=potenziamenti-1, potere=potere+1 WHERE id_oggetti='".$rowo[id_oggetti]."'";
                            db_query($sql) or die(db_error(LINK));
                            output("`3Il tuo ".$rowo[nome]."`3 ora può essere utilizzato ".($rowo[potere]+1)." volte ogni newday.");
                            $session['user']['gems']-=5;
                            debuglog("aumenta gli utilizzi giornalieri di ".$rowo[nome]." da Thor `&(artefatto)`0 spendendo 5 gemme.");
                        break;
                        case "potenziamenti":
                            $sql = "UPDATE oggetti SET potenziamenti=potenziamenti+2, livello=livello+1 WHERE id_oggetti='".$rowo[id_oggetti]."'";
                            db_query($sql) or die(db_error(LINK));
                            output("`3Il tuo ".$rowo[nome]."`3 ora ha ".($rowo[potenziamenti]+2)." potenziamenti disponibili, ma è salito di un livello.");
                            $session['user']['gems']-=(($rowo[livello]+1)*10);
                            debuglog("incrementa i potenzianziamenti a disposizione di ".$rowo[nome]." da Thor `&(artefatto)`0 spendendo ".(($rowo[livello]+1)*10)." gemme.");
                        break;
                    }
                    //fine switch
                }
            }
        }
        addnav("Esci");
        addnav("Torna nella Foresta","forest.php?op=foresta");
} elseif ($_GET['op'] == "crea_artefatto") {
    if (!$_GET['nome']) {
        output("`3Comunichi a Thor la tua decisione di volere a tutti i costi un artefatto, e iniziate a discutere sulla forma di questo tuo nuovo e potente oggetto.`n");
        output("A proposito, dovrai anche decidere il nome da dare a questo artefatto, come lo vuoi chiamare?`n`n");
        output("<form action='forest.php?op=crea_artefatto&nome=1' method='POST'>",true);
        output("<input id='input' name='nome' maxlength=40 width=10>&nbsp;<input type='submit' class='button' value='Battezza!'>`n`)(Max 40 caratteri)`0`n</form>",true);
        output("<script language='javascript'>document.getElementById('input').focus();</script>",true);
        addnav("","forest.php?op=crea_artefatto&nome=1");
        addnav("Esci");
        addnav("Torna nella Foresta","forest.php?op=foresta");
    } else {
        $nome = $_POST[nome]." `9(Artefatto)";
        $nome = soap($nome);
        output("`3Thor si mette a lavorare, ti accorgi subito della complessità del lavoro e ben presto non riesci più a star dietro ai vari passaggi che compie");
        if ($session['user']['carriera']>4 AND $session['user']['carriera']<9) output("(pur avendo appreso da Oberon molti segreti sull'arte del fabbro)");
        output("; infine ti distrai completamente ed inizi a fantasticare su quanto potente possa davvero essere questo \"artefatto\".`n`n");
        output("Passa molto tempo, che ti costa `\$2 Combattimenti nella Foresta`3, infine Thor termina la forgiatura e, mentre procede a temprare il tuo `b$nome`b appena creato,");
        output("ti avverte che il suo lavoro è terminato e che il tuo artefatto è pronto.`n");
        output("Ora non ti resta che pagare, e correre a mostrarlo agli altri avventurieri!");
        $desc="Un artefatto creato da ".$session['user']['login'];
        $sql="INSERT INTO oggetti (nome, pregiato, descrizione, dove, dove_origine, livello, valore, potenziamenti,attack_help,defence_help,turns_help,
            usura, usuramax, usuraextra, usuramagica, usuramagicamax, usuramagicaextra)
            VALUES ('{$nome}','1','{$desc}','30','31','10','30','10','0','0','0',
            '-1', '-1', '0', '-1', '-1', '0')";
        db_query($sql);
        $sql= "SELECT id_oggetti FROM oggetti WHERE dove=30 ORDER BY id_oggetti DESC LIMIT 1";
        $result = db_query($sql) or die(db_error(LINK));
        $row = db_fetch_assoc($result);
        if ($session['user']['oggetto']==0) {
            $session['user']['oggetto']=$row[id_oggetti];
        } else {
            $session['user']['zaino']=$row[id_oggetti];
        }
        $session['user']['gold'] -= 20000;
        $session['user']['gems'] -= 10;
        $session['user']['turns'] -= 2;
        debuglog("ha creato l'artefatto $nome da Thor pagando 20000 monete d'oro, 10 gemme e 2 turni");
        addnav("Esci");
        addnav("Torna nella Foresta","forest.php?op=foresta");
    }
} elseif ($_GET['op'] == "rinuncia_artefatto") {
    output("`c`\$`bATTENZIONE!!!`c`n`n`cVuoi veramente disfarti del tuo artefatto?`c`b");
    addnav("Disfati dell'artefatto", "forest.php?op=rinuncia_conferma");
    addnav("Esci");
    addnav("Torna nella Foresta","forest.php?op=foresta");
} elseif ($_GET['op'] == "rinuncia_conferma") {
    if ($session['user']['oggetto'] != 0) {
        $sqlo = "SELECT * FROM oggetti WHERE id_oggetti = '{$session['user']['oggetto']}'";
        $resulto = db_query($sqlo) or die(db_error(LINK));
        $rowo = db_fetch_assoc($resulto);
        if ($rowo['dove']==30) {
            $session['user']['attack'] -= $rowo['attack_help'];
            $session['user']['defence'] -= $rowo['defence_help'];
            $session['user']['bonusattack'] -= $rowo['attack_help'];
            $session['user']['bonusdefence'] -= $rowo['defence_help'];
            $session['user']['maxhitpoints'] -= $rowo['hp_help'];
            $session['user']['hitpoints'] -= $rowo['hp_help'];
            $session['user']['playerfights'] -= $rowo['pvp_help'];
            $session['user']['turns'] -= $rowo['turns_help'];
            $session['user']['bonusfight'] -= $rowo['turns_help'];
            $session['user']['oggetto'] = 0;
            $sql = "UPDATE oggetti SET dove=31 WHERE id_oggetti='".$rowo[id_oggetti]."'";
            db_query($sql) or die(db_error(LINK));
            debuglog("si è disfatto dell'artefatto ".$rowo[nome]." da Thor.");
        }
    }
    if ($session['user']['zaino'] != 0) {
        $sqlo = "SELECT * FROM oggetti WHERE id_oggetti = '{$session['user']['zaino']}'";
        $resulto = db_query($sqlo) or die(db_error(LINK));
        $rowo = db_fetch_assoc($resulto);
        if ($rowo['dove']==30) {
            $session['user']['zaino'] = 0;
            $sql = "UPDATE oggetti SET dove=31 WHERE id_oggetti='".$rowo[id_oggetti]."'";
            db_query($sql) or die(db_error(LINK));
            debuglog("si è disfatto dell'artefatto ".$rowo[nome]." da Thor.");
        }
    }
    output("`&Ti sei disfatto per sempre del tuo artefatto ".$rowo['nome']);
    addnav("Esci");
    addnav("Torna nella Foresta","forest.php?op=foresta");
} elseif ($_GET['op'] == "foresta") {
    output("`3Ti rendi conto che Thor non è l'unico ad avere da fare, le creature della foresta stanno aspettando");
    output("di essere uccise, e tu non vuoi certo fare in modo che la loro vita sia più lunga di quello che il destino ha decretato.`n`n");
    output("Ripercorri il corridoio all'incontrario, fino a ritornare in superficie accanto alla quercia da cui sei entrato.");
    $session['user']['specialinc']="";
    addnav("La Foresta","forest.php");
}

page_footer();

?>