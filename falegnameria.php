<?php
/*
ALTER TABLE `accounts` ADD `falegname` DECIMAL( 3, 2 ) NOT NULL ;
*/

require_once("common.php");
require_once("common2.php");

$legni = array(
24=>"Legno di Ontano",
26=>"Legno di Betulla",
27=>"Legno di Faggio",
28=>"Legno di Quercia (Leccio)",
29=>"Legno di Quercia (Rovere)",
);
$setting = array(
24=>"ontano",
26=>"betulla",
27=>"faggio",
28=>"leccio",
29=>"rovere",
);

$idplayer = $session['user']['acctid'];
$idSindaco = getsetting("sindaco",'');

function checkObject($id) {
    $sql = "SELECT * FROM oggetti WHERE id_oggetti = '".$id."'";
    $result = db_query($sql) or die(db_error(LINK));
    $row = db_fetch_assoc($result);
    if ($row['nome']=='Sega da falegname')
       return true;
    else
       return false;
}

function faiPrezzo($id, $valore,$setting) {
   $quantita = getsetting($setting[$id],0);
   $valoreFinale = $valore - (10 * $quantita);
   if ($valoreFinale < 10) $valoreFinale = 10;
   return $valoreFinale;
}

function getRimborso($materiali,$setting) {
   $return = 0;
   foreach ($materiali as $index => $value) {
       $sql = "SELECT id, nome , valoremo FROM materiali WHERE id = '{$value}'";
       $resul = db_query($sql) or die(db_error(LINK));
       $row = db_fetch_assoc($resul);
       $return += faiPrezzo($row[id],$row[valoremo],$setting);
   }
   return $return;
}

//usura
function usura() {
    global $session;
    $sqlo = "SELECT usura, usuramax FROM oggetti WHERE id_oggetti = '{$session['user']['oggetto']}'";
    $resulto = db_query($sqlo) or die(db_error(LINK));
    $rowo = db_fetch_assoc($resulto);
    if ($rowo[usuramax]>0) {
        if ($rowo[usura]!= 1) {
            $sqlu = "UPDATE oggetti SET usura = '".($rowo[usura]-1)."' WHERE id_oggetti = '{$session['user']['oggetto']}'";
            db_query($sqlu) or die(db_error($link));
        }else{
            $sqlogg = "DELETE FROM oggetti WHERE id_oggetti = ".$session['user']['oggetto'];
            $result = db_query($sqlogg) or die(db_error(LINK));
            $session['user']['oggetto'] = 0;
            output("La tua sega, già provata da innumerevoli progetti, non ha retto, ed a seguito dell'ennesimo levoro, si è frantumata.`n");
            output("Senza più una sega con cui lavorare, non ti resta che ritornare al villaggio, dove forse potrai procurartene una nuova.`n");
            debuglog("perde per usura la Sega da falegname");
        }
    }
}
//fine funzione usura

page_header("La Falegnameria");
$session['user']['locazione'] = 125;

// Randomizzazione mercato
$ultimo_mercato = getsetting("ultimo_mercato_fal", 0);
$prossimo_mercato = getsetting("prossimo_mercato_fal", 0);
$data_oggi = time();
if (($data_oggi - $ultimo_mercato) > ($prossimo_mercato)) {
   foreach ($legni as $index => $value) {
      $quantita = getsetting($setting[$index],0);
      $quantita += intval($quantita*e_rand(-10, 10)/100) + e_rand(-5, 5);
      if ($index==28 OR $index==29) {
          $max = 5;
          if ($index = 29) $max = 10;
          if (e_rand(1,$max) == 1) savesetting($setting[$index],$quantita);
      } else {
          savesetting($setting[$index],$quantita);
      }
   }
   addnews("Una carovana di mercanti stranieri è stata vista nei pressi della falegnameria");
   $data_mercato = mt_rand(43200,259200);
   savesetting("prossimo_mercato_fal", $data_mercato);
   savesetting("ultimo_mercato_fal", $data_oggi);
}
// fine randimizzazione mercato

if ($_GET['op']==""){
    output("Nei pressi della foresta c'è una grande costruzione che è stata adibita dagli abitanti del villaggio a falegnameria.`n");
    output("Un grosso Minotauro che porta una curiosa e buffa mascherina davanti alla faccia è intento a sovrintendere il lavoro, a volte rimproverando i poverini che gli capitano sotto mano, credi sia lui il capo mastro.`n");
    output("Dopo un po' che lo osservi si accorge di te e si avvicina con aria minacciosa: \"`&Tu devi essere {$session['user']['login']}`&... Ho sentito parlare da qualche parte di te, ma non ricordo se come protagonista di una barzelletta o di una cosa seria...\"`0.`n");
    output("Si sofferma a pensare e poi ricomincia a parlare: \"`&Bhè, io sono `5Ascharot`&, il responsabile di tutto quello che vedi, quì dentro comando io quindi fila dritto e non farmi perdere tempo!\"`0.`n");
    output("Al momento `5Ascharot`0 ha questi materiali. Ricorda che il valore indicato è quello con cui `5Ascharot`0 vende, compra i materiali a prezzo minore.`n`n`n");

    if ($session['user']['superuser'] > 2) {
        output("<form action='falegnameria.php?op=materiali' method='POST'>",true);
        addnav("","falegnameria.php?op=materiali");
    }
    output("<table cellspacing=0 cellpadding=2 align='center'>", true);
    output("<tr class='trhead' align='center'><td>`bLegno`b</td><td>`bQuantità`b</td><td>`bValore oro`b</td><td>Ops</td>", true);
    if ($session['user']['superuser'] > 2) output("<td>`iModifica quantità`i</td>",true);
    output("</tr>",true);
    $i = 1;
    foreach ($legni as $index => $value) {
       $i++;
       $sqllegno = "SELECT id, nome , valoremo FROM materiali WHERE id = '{$index}'";
       $resullegno = db_query($sqllegno) or die(db_error(LINK));
       $row = db_fetch_assoc($resullegno);
       $valore = faiPrezzo($row[id],$row[valoremo],$setting);
       $quantita = getsetting($setting[$row[id]],0);
       output("<tr class='" . ($i % 2?"trlight":"trdark") . "'><td>$row[nome]</td><td>$quantita</td><td>$valore</td>",true);
       if ($session['user']['level'] < 15) {
           output("<td><A href=falegnameria.php?op=compra&id={$row[id]}&val={$valore}>Compra </a>",true);
           addnav("","falegnameria.php?op=compra&id={$row[id]}&val={$valore}");
       } else {
           output("<td>",true);
       }
       if ($session['user']['level'] > 4) {
          output("-<A href=falegnameria.php?op=vendi&id={$row[id]}&val={$valore}> Vendi</a>",true);
          addnav("","falegnameria.php?op=vendi&id={$row[id]}&val={$valore}");
       }
       if ($session['user']['superuser'] > 2) output("</td><td><input name='".$setting[$row[id]]."' value=\"".HTMLEntities2($quantita)."\" size='5'>",true);
       output("</td></tr>", true);
    }
    output("</table>", true);
    if ($session['user']['superuser'] > 2) output("`n`n<input type='submit' class='button' value='Salva'></form>",true);
    output ("`n`n");
    // Inizio tabella Zaino
    output("<table cellspacing=0 cellpadding=2 align='center'>", true);
    output("<tr class='trhead'><td colspan=2 align='center'>`bIl tuo Zaino`b</td></tr>", true);

    // Selezione Materiali Player
    $sqlmateriali = "SELECT materiali.id AS idmateriali, materiali.nome AS nome, materiali.valoremo AS valoremo, materiali.valorege AS valorege,
                     materiali.descrizione AS descrizione FROM zaino, materiali WHERE zaino.idplayer = $idplayer AND zaino.idoggetto = materiali.id";
    $resultmateriali = db_query($sqlmateriali ) or die(db_error(LINK));

    if (db_num_rows($resultmateriali) == 0) {
        output("<tr class='trhead'><td colspan=2 align='center'>`&Non hai oggetti nello zaino`0</td></tr>", true);
    }
    $countrow = db_num_rows($resultmateriali);
    for ($i=0; $i<$countrow; $i++){
    //for ($i = 0;$i < db_num_rows($resultmateriali);$i++) {
        $row = db_fetch_assoc($resultmateriali);
        output("<tr class='" . ($i % 2?"trlight":"trdark") . "'><td>" . ($i + 1) . ".</td><td>$row[nome]</td></tr>", true);

    }
    output("</table>", true);
    // Fine tabella Zaino

    //Navigazione
    addnav("Laboratorio");
    addnav("L?Laboratorio","falegnameria.php?op=laboratorio");
    // Magazzino (se sei sindaco o superuser)
    if ($idSindaco==$idplayer OR $session['user']['superuser'] > 2) {
       addnav("Magazzino");
       addnav("M?Vai al Magazzino","falegnameria.php?op=magazzino");
    }
    addnav("Exit");
    addnav("F?Torna in Foresta","forest.php");

}
//editor quantità materiali
if ($_GET['op']=="materiali"){
    foreach ($legni as $index => $value) {
        $sqllegno = "SELECT id, nome , valoremo FROM materiali WHERE id = '{$index}'";
        $resullegno = db_query($sqllegno) or die(db_error(LINK));
        $row = db_fetch_assoc($resullegno);
        savesetting($setting[$row[id]],$_POST[$setting[$row[id]]]);
    }
    output("Le quantità dei materiali in vendita nella falegnameria sono state modificate");
    addnav("Exit");
    addnav("F?Torna alla Falegnameria","falegnameria.php");
}
//fine editor quantità materiali
if($_GET['op']=="compra"){
    $valore = $_GET['val'];
    if ($session['user']['gold']>=$valore){
       $id = $_GET['id'];
       $quantita = getsetting($setting[$id],0);
        if ($quantita >= 1) {
            if (zainoPieno ($idplayer)) {
                output("Purtroppo non hai più spazio nello zaino.`n");
            } else {
                output("`5Ascharot`0 afferra i tuoi `^".$valore." pezzi d'oro`0 e ti da un `&{$legni[$id]}`0 in cambio.`n`n");
                $session['user']['gold']-=$valore;
                $sqli="INSERT INTO zaino (idoggetto, idplayer) VALUES ({$id}, '{$idplayer}')";
                db_query($sqli) or die(db_error(LINK));
                debuglog("compra un {$setting[$id]} per {$valore} monete in falegnameria");
                if ($quantita - 1 < 1) {
                    savesetting($setting[$id],"0");
                } else {
                    savesetting($setting[$id],$quantita-1);
                }
            }
        } else {
            output("Purtroppo `5Ascharot`0 ha già venduto tutto il `&{$legni[$id]}`0 che aveva, sei arrivato troppo tardi.`n");
        }
    }else{
        output("`5Ascharot`0 prende la sua fedele `b`&Accetta a Dua Mani`b`0 e comincia ad accarezzarla mentre ti guarda truce, e agitando l'indice sul tuo muso dice :\"`&Non fare il furbo con me !`0\".`n`n");
    }
    addnav("Exit");
    addnav("E?Torna all'Entrata", "falegnameria.php");
}

if($_GET['op']=="vendi"){
    $id = $_GET['id'];
    $valore = $_GET['val'];

    $sql = "SELECT * FROM zaino WHERE idoggetto = {$id} AND idplayer='{$idplayer}'";
    $result = db_query($sql) or die(db_error(LINK));
    if (db_num_rows($result)<1){
        output("`5Ascharot`0 prende la sua fedele `b`&Accetta a Dua Mani`b`0 e comincia ad accarezzarla mentre ti guarda truce, e agitando l'indice sul tuo muso dice :\"`&Non fare il furbo con me !`0\".`n`n");
    }else{
        $valore = $valore / 2;
        output("`5Ascharot`0 accetta il tuo `&$legni[$id]`0 e ti posa in mano `^".($valore)." pezzi d'oro`0 in cambio.`n`n");
        $session['user']['gold']+=($valore);
        $row = db_fetch_assoc($result);
        $sqld = "DELETE FROM zaino WHERE id = '{$row['id']}'";
        db_query($sqld);
        debuglog("vende un $legni[$id] alla falegameria per {$valore} monete");
        $quantita = getsetting($setting[$id],0);
        savesetting($setting[$id],$quantita+1);
    }
    addnav("Exit");
    addnav("E?Torna all'Entrata", "falegnameria.php");
}

if($_GET['op']=="laboratorio"){
    output("`5Ascharot`0 in fondo è un bravo Minotauro, e mette a disposizione il suo laboratorio a tutti quelli che vogliono apprendere i segreti dell'arte del falegname.`n");
    output("Hai la possibilità di lavorare ai seguenti progetti, `5Ascharot`0 ti ricorda inoltre che devi possedere una `3sega da falegname`0, la `&legna necessaria`0 che varia da progetto a progetto e `^3 turni`0 per poter continuare.`n`n`n");
    output("<table cellspacing=0 cellpadding=2 align='center'>", true);
    output("<tr class='trhead'><td align='center'>`bProgetti`b</td></tr>", true);
    if (checkObject($session['user']['oggetto']) AND $session['user']['turns'] >= 3) {
        $abilita = $session['user']['falegname'];
        $sql = "SELECT * FROM materiali WHERE tipo = 1 AND costo_exp <= $abilita ORDER BY costo_exp";
        $result = db_query($sql) or die(db_error(LINK));
        $countrow = db_num_rows($result);
        for ($i=0; $i<$countrow; $i++){
        //for ($i = 0;$i < db_num_rows($result);$i++) {
             $row = db_fetch_assoc($result);
             $id = $row[id];
             $descrizione = $row[nome];
             output("<tr class='" . ($i % 2?"trlight":"trdark") . "'><td><a href=falegnameria.php?op=project&id={$id}>{$descrizione}</a></td></tr>", true);
             addnav("","falegnameria.php?op=project&id={$id}");
        }
    } else {
        if ($session['user']['turns'] < 3) {
            output("<tr class='trhead'><td colspan=2 align='center'>`c`bNon possiedi abbastanza turni`b`c</td></tr>", true);
        } else {
            output("<tr class='trhead'><td colspan=2 align='center'>`c`bNon possiedi una sega da falegname`b`c</td></tr>", true);
        }

    }
    output("</table>", true);
    addnav("Info");
    addnav("C?Chiedi come stai andando", "falegnameria.php?op=info");
    addnav("Exit");
    addnav("E?Torna all'Entrata", "falegnameria.php");
}

if($_GET['op']=="info"){
    output("Ti avvicini ad `5Ascharot`0 per un parere da esperto, lui ti squadra con attenzione per poi dire: ");
    output("\"`&Bhè, se proprio lo vuoi sapere sei un ");
    $out = "1";
    if ($session['user']['falegname'] > 3) {
       $out = "2";
    }
    if ($session['user']['falegname'] > 6) {
       $out = "3";
    }
    if ($session['user']['falegname'] > 9) {
       $out = "4";
    }
    if ($out == 4) {
        output("Maestro Falegname! Dovrò stare attento altrimenti diventerai più bravo di me...\"`0.`n`n`n");
    }
    if ($out == 3) {
        output("Capo Falegname! Continua su questa strada e forse un giorno dovrò guardarmi le spalle...\"`0.`n`n`n");
    }
    if ($out == 2) {
        output("Lavoratore! Sei sulla buona strada, hai appena cominciato ad apprendere il mestiere...\"`0.`n`n`n");
    }
    if ($out == 1) {
        output("Aiutante! Ti mancano i fondamentali ma forse un giorno chissà...\"`0.`n`n`n");
    }
    if ($session['user']['superuser'] > 0) {
        output("Poi `5Ascharot`0 ti prende in disparte e lontano da occhi indiscreti dice: ");
        output("\"`&Ti ho riconosciuto...sei un superutente! con precisione la tua abilità dell'arte della falegnameria è pari a {$session['user']['falegname']} / 10, vuoi modificarla?");
        output("<form action='falegnameria.php?op=modabilita' method='POST'>",true);
        addnav("","falegnameria.php?op=modabilita");
        output("<table cellspacing=0 cellpadding=2>", true);
        output("<td>Nuovo Valore&nbsp&nbsp<input name='falegname' value=\"".HTMLEntities2($session['user']['falegname'])."\" size='4'>",true);
        output("`n`n<input type='submit' class='button' value='Salva'>",true);
        output("</table></form>", true);
    }
    addnav("Exit");
    addnav("T?Torna al Laboratorio", "falegnameria.php?op=laboratorio");
}

if($_GET['op']=="modabilita"){
    $falegname = $_POST[falegname];
    if ($falegname > 10) $falegname = 10;
    if ($falegname < 0) $falegname = 0;
    $session['user']['falegname'] = $falegname;
    output("`5Ascharot`0 pronuncia una serie di strane parole, ora hai $falegname punti falegname!");
    addnav("Exit");
    addnav("T?Torna al Laboratorio", "falegnameria.php?op=laboratorio");
}

if($_GET['op']=="project"){

    $id = $_GET['id'];
    $sql = "SELECT * FROM materiali WHERE id = $id";
    $result = db_query($sql) or die(db_error(LINK));
    $row = db_fetch_assoc($result);

    $numero_ingredienti = 10;
    $materiali = array();
    $abilita = $row[costo_exp];
    $num_ing = 0;
    $progetto = $row[nome];

    for ($i = 1;$i < $numero_ingredienti ;$i++) {
         $ingrediente = "ingrediente".$i;
         if ($row[$ingrediente] != 0) {
            $materiali[$i] = $row[$ingrediente];
            $num_ing++;
         }
    }

    $i=1;
    $tutto_ok = true;
    output ("`b`cPer realizzare `&'$progetto'`0 ti serve :`c`b`n`n");
    output("<table cellspacing=0 cellpadding=2 align='center'>", true);
    foreach ($materiali as $index => $value) {
        // Controllo se Disponibile
        $sql = "SELECT id FROM  zaino WHERE idplayer='{$idplayer}' AND idoggetto = '{$value}' {$ingr[($i-1)]}";
        $result = db_query($sql) or die(db_error(LINK));
        $row = db_fetch_assoc($result);
        if (db_num_rows($result) > 0) {
            $stato[$i] = "`6in tuo Possesso";
            $ingr[$i] = $ingr[($i-1)]." AND id != '{$row['id']}'";
            $ingr_delete[$i] = $ingr_delete[($i-1)]." OR id ='{$row['id']}'";
        }else{
            $tutto_ok = false;
            $stato[$i] = "`4NON `6in tuo Possesso";
            $ingr[$i] = $ingr[($i-1)];
            $ingr_delete[$i] = $ingr_delete[($i-1)];
        }
        output("<tr class='" . ($i % 2?"trlight":"trdark") . "'><td>`0Ingrediente $i</td><td>`3{$legni[$value]}</td><td>$stato[$i]`0</td></tr>", true);
        $i++;
    }
    output("</table>`n`n", true);

    if ($tutto_ok) {
        output ("Metti insieme tutto il necessario e cominci a lavorare. Dopo molte ore riesci a finire il progetto.`n`n");
        if($session['user']['falegname']< 5)$session['user']['falegname']+=0.01;
        if($session['user']['falegname']< 10)$session['user']['falegname']+=0.01;
        $session['user']['turns'] = $session['user']['turns'] - 3;
        $premio = getRimborso($materiali,$setting);
        $session['user']['gold'] += $premio;
        $query = $ingr_delete[$num_ing];
        $query = substr($query,4);
        $sqle = "DELETE FROM zaino WHERE idplayer='{$idplayer}' AND ($query)";
        db_query($sqle);
        output ("`5Ascharot`0 si avvicina compiaciuto e ti fa i complimenti, poi senza dire nulla ti requisisce l'oggetto dandoti in cambio un borsello di cuoio, ");
        output ("aprendolo scopri che ci sono `^{$premio} Monete d'Oro`0.`n`n");
        debuglog("viene rimborsato di {$premio} monete per aver creato {$progetto} in falegnameria");
        $progettosetting = str_replace(" ","_",substr($progetto,0,20));
        savesetting($progettosetting,getsetting($progettosetting,0) + 1);
    } else {
        output ("Purtroppo non hai tutto il materiale necessario per procedere alla lavorazione, prova a chiedere ad `5Ascharot`0 se ha quello che ti serve.`n`n");
    }

    addnav("Exit");
    addnav("T?Torna al Laboratorio", "falegnameria.php?op=laboratorio");
}

if($_GET['op']=="magazzino"){
    output("`5Ascharot`0 ti porta nel magazzino per farti vedere che cosa ha a disposizione, ti porge una pergamena dove è ");
    output("riportato un inventario:`n`n");

    if ($session['user']['superuser'] > 2) {
        output("<form action='falegnameria.php?op=modmagazzino' method='POST'>",true);
        addnav("","falegnameria.php?op=modmagazzino");
    }

    output("<table cellspacing=0 cellpadding=2 align='center'>", true);
    output("<tr class='trhead'><td align='center'>`bOggetti`b</td><td align='center'>`bQuantità`b</td>", true);
    if ($session['user']['superuser'] > 2) {
        output("<td align='center'>`iModifica quantità`i</td>",true);
    }

    output("</tr>", true);
    $sql = "SELECT * FROM materiali WHERE tipo = 1 ORDER BY costo_exp";
    $result = db_query($sql) or die(db_error(LINK));
    $countrow = db_num_rows($result);
    for ($i=0; $i<$countrow; $i++){
    //for ($i = 0;$i < db_num_rows($result);$i++) {
        $row = db_fetch_assoc($result);
        $descrizione = $row[nome];
        $progettosetting = str_replace(" ","_",substr($descrizione,0,20));
        $magazzino = getsetting($progettosetting,0);
        output("<tr class='" . ($i % 2?"trlight":"trdark") . "'><td>$descrizione</td><td align='center'>$magazzino</td>", true);

        if ($session['user']['superuser'] > 2) output("<td align='center'><input name='".$progettosetting."' value=\"".HTMLEntities2($magazzino)."\" size='5'>",true);

        output("</tr>", true);

    }
    output("</table>", true);
    if ($session['user']['superuser'] > 2) output("`n`n<input type='submit' class='button' value='Salva'></form>",true);
    addnav("Exit");
    addnav("E?Torna all'Entrata", "falegnameria.php");
}
//editor quantità materiali in magazzino
if ($_GET['op']=="modmagazzino"){
    $sql = "SELECT * FROM materiali WHERE tipo = 1 ORDER BY costo_exp";
    $result = db_query($sql) or die(db_error(LINK));
    $countrow = db_num_rows($result);
    for ($i=0; $i<$countrow; $i++){
    //for ($i = 0;$i < db_num_rows($result);$i++) {
        $row = db_fetch_assoc($result);
        $descrizione = $row[nome];
        $progettosetting = str_replace(" ","_",substr($descrizione,0,20));
        savesetting($progettosetting,$_POST[$progettosetting]);
    }
    output("Le quantità dei materiali in magazzino sono state modificate");
    addnav("Exit");
    addnav("M?Torna al Magazzino","falegnameria.php?op=magazzino");
}
//fine editor quantità materiali in magazzino
page_footer();
?>