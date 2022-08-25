<?php
require_once("common.php");
checkday();
page_header("Pagina per gli script che inseririscono materiali nello zaino del giocatore. ");
addnav("Aggiungi materiale comune", "materiali.php?op=comune");
output ("Per materiale comune si intende qualsiasi materiale che ha solo un costo in monete e non in gemme`n");
addnav("Aggiungi materiale non comune", "materiali.php?op=noncomune");
output ("Per materiale non comune si intende qualsiasi materiale che ha un costo in monete e massimo di 5 gemme`n");
addnav("Aggiungi materiale raro", "materiali.php?op=raro");
output ("Per materiale raro si intende qualsiasi materiale che ha un costo in monete e tra  5-50 gemme`n");
addnav("Aggiungi materiale rarissimo", "materiali.php?op=rarissimo");
output ("Per materiale rarissimo si intende qualsiasi materiale che ha un costo in monete e sopra le 50 gemme`n`n");
output ("Questi script inseriscono in maniera casuale un materiale (non ricette) nello zaino del giocatore, possono essere usati nelle quest o negli eventi speciali.`n");
output ("Il limite di materiali e ricette nello zaino è stato fissato a 20.`n");

output ("Per inserire delle ricette, che vengono susddivise nelle stesse categorie comune, noncomuni, rare e rarissime basta sostituire nella select dei materiali alla voce ( tipo = M )  con ( tipo = R). `n`n");
if (!$_GET[op]) {


        $idplayer = $session[user][acctid];
        $sql = "SELECT materiali.id AS idmateriali, materiali.nome AS nome, materiali.valoremo AS valoremo, materiali.valorege AS valorege,
                 materiali.descrizione AS descrizione FROM zaino, materiali WHERE zaino.idplayer = $idplayer AND zaino.idoggetto = materiali.id";
                output ("Il tuo zaino`n`n");
        output("<table cellspacing=0 cellpadding=2 align='center'><tr><td>&nbsp;</td><td>`bNome`b</td><td>`bValore oro`b</td><td>`bValore gemme`b</td></tr>", true);
        $result = db_query($sql) or die(db_error(LINK));
        if (db_num_rows($result) == 0) {
            output("<tr><td colspan=4 align='center'>`&Non ci sono oggetti disponibili mi spiace`0</td></tr>", true);
        }
        $countrow = db_num_rows($result);
        for ($i=0; $i<$countrow; $i++){
        //for ($i = 0;$i < db_num_rows($result);$i++) {
            $row = db_fetch_assoc($result);
            output("<tr class='" . ($i % 2?"trlight":"trdark") . "'><td>" . ($i + 1) . ".</td><td>$row[nome]</td><td>$row[valoremo]</td><td>$row[valorege]</td></tr>", true);

        }
        output("</table>", true);
}else if ($_GET[op] == comune) {
        //verifica contenuto zaino
        $sqlz = "SELECT * FROM zaino where idplayer = '{$session['user']['acctid']}'";
        $resultz = db_query($sqlz) or die(db_error(LINK));
        if (db_num_rows($resultz) < 20) {
            $sql = "SELECT * FROM  materiali WHERE valorege = 0 AND tipo = 'M'";
            $result = db_query($sql) or die(db_error(LINK));
            $caso = e_rand(1,db_num_rows($result));
            $countrow = db_num_rows($result);
            for ($i=1; $i<$countrow; $i++){
            //for ($i = 1;$i <= db_num_rows($result);$i++) {
                $row = db_fetch_assoc($result);
                if ($i == $caso) {
                  $sql="INSERT INTO zaino (idoggetto, idplayer) VALUES ('{$row['id']}', '{$session['user']['acctid']}')";
                  db_query($sql) or die(db_error(LINK));
                  output ("Aggiunto".$row['id']." `n");
                }else{
                output ("Saltato".$row['id']." `n");
                }
            }
        }else{
            output ("Hai già troppe cose nello zaino`n");
        }
}else if ($_GET[op] == noncomune) {
        $sqlz = "SELECT * FROM zaino where idplayer = '{$session['user']['acctid']}'";
        $resultz = db_query($sqlz) or die(db_error(LINK));
        if (db_num_rows($resultz) < 20) {
            $sql = "SELECT * FROM  materiali WHERE valorege > 0 AND valorege <= 5 AND tipo = 'M'";
            $result = db_query($sql) or die(db_error(LINK));
            $caso = e_rand(1,db_num_rows($result));
            $countrow = db_num_rows($result);
            for ($i=1; $i<$countrow; $i++){
            //for ($i = 1;$i <= db_num_rows($result);$i++) {
               $row = db_fetch_assoc($result);
                if ($i == $caso) {
                   $sql="INSERT INTO zaino (idoggetto, idplayer) VALUES ('{$row['id']}', '{$session['user']['acctid']}')";
                    db_query($sql) or die(db_error(LINK));
                    output ("Aggiunto".$row['id']." `n");
                }else{
                    output ("Saltato".$row['id']." `n");
                }
            }
        }else{
            output ("Hai già troppe cose nello zaino`n");
        }
}else if ($_GET[op] == raro) {
        $sqlz = "SELECT * FROM zaino where idplayer = '{$session['user']['acctid']}'";
        $resultz = db_query($sqlz) or die(db_error(LINK));
        if (db_num_rows($resultz) < 20) {
            $sql = "SELECT * FROM  materiali WHERE valorege > 5 AND valorege <= 50 AND tipo = 'M'";
            $result = db_query($sql) or die(db_error(LINK));
            $caso = e_rand(1,db_num_rows($result));
            $countrow = db_num_rows($result);
            for ($i=1; $i<$countrow; $i++){
            //for ($i = 1;$i <= db_num_rows($result);$i++) {
               $row = db_fetch_assoc($result);
                if ($i == $caso) {
                   $sql="INSERT INTO zaino (idoggetto, idplayer) VALUES ('{$row['id']}', '{$session['user']['acctid']}')";
                    db_query($sql) or die(db_error(LINK));
                    output ("Aggiunto".$row['id']." `n");
                }else{
                    output ("Saltato".$row['id']." `n");
                }
            }
        }else{
            output ("Hai già troppe cose nello zaino`n");
        }
}else if ($_GET[op] == rarissimo) {
        $sqlz = "SELECT * FROM zaino where idplayer = '{$session['user']['acctid']}'";
        $resultz = db_query($sqlz) or die(db_error(LINK));
        if (db_num_rows($resultz) < 20) {
            $sql = "SELECT * FROM  materiali WHERE valorege > 50 AND tipo = 'M'";
            $result = db_query($sql) or die(db_error(LINK));
            $caso = e_rand(1,db_num_rows($result));
            $countrow = db_num_rows($result);
            for ($i=1; $i<$countrow; $i++){
            //for ($i = 1;$i <= db_num_rows($result);$i++) {
               $row = db_fetch_assoc($result);
                if ($i == $caso) {
                   $sql="INSERT INTO zaino (idoggetto, idplayer) VALUES ('{$row['id']}', '{$session['user']['acctid']}')";
                    db_query($sql) or die(db_error(LINK));
                    output ("Aggiunto".$row['id']." `n");
                }else{
                    output ("Saltato".$row['id']." `n");
                }
            }
        }else{
            output ("Hai già troppe cose nello zaino`n");
        }
}
addnav("Torna in paese", "village.php");
page_footer();

?>