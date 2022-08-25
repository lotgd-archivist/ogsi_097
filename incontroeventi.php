<?php
require_once "common.php";

page_header("Editor Frequenza Eventi");
output("`3`c`bEditor Frequenza Eventi`b`c`n`n");
isnewday(4);

if($_GET['op']=="update"){
    if ($_POST[salva]!="") {
        $sqlupdate = "UPDATE peso_eventi SET foresta = '".$_POST['foresta']."', giardino='".$_POST['giardino']."', strega='".$_POST['strega']."' WHERE nomefile='".$_GET['event']."' ";
        db_query($sqlupdate) or die(db_error(LINK));
        output("`nLe probabilità relative all'evento `b".$_GET['event']."`b sono state modificate.`n`n");
    }
    elseif ($_POST[reset]!="") {
        $sqldelete = "DELETE FROM peso_eventi WHERE nomefile='".$_GET['event']."' ";
        db_query($sqldelete) or die(db_error(LINK));
        output("`nLe probabilità relative all'evento `b".$_GET['event']."`b sono state resettate al valore di base (100).`n`n");
    }
}

output("`n`b`#NOTA:`b i valori vanno aggiornati per un singolo file per volta`n`n`0");

//controllo e rimozione di eventuali files cancellati
$sql = "SELECT nomefile FROM peso_eventi";
$result = db_query($sql) or die(sql_error($sql));
$countrow = db_num_rows($result);
for ($i=0; $i<$countrow; $i++){
//for ($i=0;$i<db_num_rows($result);$i++) {
    $row = db_fetch_assoc($result);
    $filename = "special/".$row[nomefile];
    if (!(file_exists($filename))) {
        output("`nAttenzione! File $filename non trovato! Record nella tabella rimosso.`n");
        $sqldelete = "DELETE FROM peso_eventi WHERE nomefile='".$row['nomefile']."' ";
        db_query($sqldelete) or die(db_error(LINK));
    }
}

//calcolo totali nella tabella
$sql = "SELECT COUNT(nomefile) as eventi, SUM(foresta) as foresta, SUM(giardino) AS giardino, SUM(strega) as strega FROM peso_eventi";
$result = db_query($sql) or die(sql_error($sql));
$tot = db_fetch_assoc($result);

if ($handle = opendir("special")){
    $events = array();
    while (false !== ($file = readdir($handle))){
        if (strpos($file,".php")>0){
            array_push($events,$file);
        }
    }
    sort($events);
    if (count($events)==0){
        output("`b`@Non sono presenti eventi speciali in gioco.");
    }else{
        $new = count($events) - $tot[eventi]; //nuovi eventi inseriti
        $tot[foresta] += 100 * $new;
        $tot[giardino] += 100 * $new;
        $tot[strega] += 100 * $new;
        output("<table border=0 cellpadding=2 cellspacing=1 bgcolor='#999999'>",true);
        output("<tr class='trhead'><td>`&<b>Nome del File</b></td><td><b>Chance (`V%`&) Evento in Foresta</b></td><td><b>Chance (`V%`&) Evento nel Giardino Incantato</b></td><td><b>Chance (`V%`&) Evento dalla Strega</b></td><td></td></tr>",true);
        for ($i=0;$i<count($events);$i++) {
            $sql = "SELECT * FROM peso_eventi WHERE nomefile = '".$events[$i]."' ORDER BY nomefile ASC";
            $result = db_query($sql) or die(sql_error($sql));
            if (db_num_rows($result)>0){
// ci basiamo sui dati già noti del'evento
                $row = db_fetch_assoc($result);
                //calcolo percentuali
                $percforesta=round($row[foresta]*100/$tot[foresta],5);
                $percgiardino=round($row[giardino]*100/$tot[giardino],5);
                $percstrega=round($row[strega]*100/$tot[strega],5);
                output("<form action='incontroeventi.php?op=update&event=".$row[nomefile]."' method='POST'>",true);
                addnav("","incontroeventi.php?op=update&event=".$row[nomefile]);
                output("<tr class='".($i%2?"trdark":"trlight")."'><td>",true);
                output("`^$row[nomefile]`0");
                output("</td><td>",true);
                output("<input name='foresta' value=\"".HTMLEntities2($row[foresta])."\" size='5'>`V($percforesta%)`0",true);
                output("</td><td>",true);
                output("<input name='giardino' value=\"".HTMLEntities2($row[giardino])."\" size='5'>`V($percgiardino%)`0",true);
                output("</td><td>",true);
                output("<input name='strega' value=\"".HTMLEntities2($row[strega])."\" size='5'>`V($percstrega%)`0",true);
                output("</td><td>",true);
                output("`n`n<input type='submit' class='button' name='salva' value='Salva'>`n`n <input type='submit' class='button' name='reset' value='Reset'>",true);
                output("</td></tr></form>",true);
            } else {
// trovato un nuovo evento, lo inseriamo nel database
                $sql="INSERT INTO peso_eventi VALUES('".$events[$i]."', '100', '100', '100')";
                db_query($sql) or die(sql_error($sql));
                //calcolo percentuali
                $percforesta=round($row[foresta]*100/$tot[foresta],5);
                $percgiardino=round($row[giardino]*100/$tot[giardino],5);
                $percstrega=round($row[strega]*100/$tot[strega],5);
                output("<form action='incontroeventi.php?op=update&event=".$events[$i]."' method='POST'>",true);
                addnav("","incontroeventi.php?op=update&event=".$events[$i]);
                output("<tr class='".($i%2?"trdark":"trlight")."'><td>",true);
                output("`^$events[$i] `7(inserito) `0");
                output("</td><td>",true);
                output("<input name='foresta' value=\"100\" size='5'>`V($percforesta%)`0",true);
                output("</td><td>",true);
                output("<input name='giardino' value=\"100\" size='5'>`V($percgiardino%)`0",true);
                output("</td><td>",true);
                output("<input name='strega' value=\"100\" size='5'>`V($percstrega%)`0",true);
                output("</td><td>",true);
                output("`n`n<input type='submit' class='button' value='Salva'>`n`n <input type='submit' class='button' name='reset' value='Reset'>",true);
                output("</td></tr></form>",true);
            }
        }
        output("<tr class='".($i%2?"trdark":"trlight")."'><td>",true);
        output("`b`@TOTALE`0`b");
        output("</td><td>",true);
        output("`&$tot[foresta]`0",true);
        output("</td><td>",true);
        output("`&$tot[giardino]`0",true);
        output("</td><td>",true);
        output("`&$tot[strega]`0",true);
        output("</td><td></td></tr></table>",true);
    }
}

addnav("R?Ricarica pagina", "incontroeventi.php");
addnav("G?Torna alla Grotta","superuser.php");
page_footer();
?>