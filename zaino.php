<?php
require_once("common.php");
checkday();
page_header("Lo Zaino");
$session['user']['locazione'] = 192;
if (!$_GET['op']) {
        $idplayer = $session['user']['acctid'];
        $sql = "SELECT materiali.id AS idmateriali, materiali.nome AS nome, materiali.valoremo AS valoremo, materiali.valorege AS valorege,
                 materiali.descrizione AS descrizione FROM zaino, materiali WHERE zaino.idplayer = $idplayer AND zaino.idoggetto = materiali.id";
                output ("Il tuo zaino`n`n");
        output("<table cellspacing=0 cellpadding=2 align='center'><tr><td>&nbsp;</td><td>`bNome`b</td></tr>", true);
        $result = db_query($sql) or die(db_error(LINK));
        if (db_num_rows($result) == 0) {
            output("<tr><td colspan=4 align='center'>`&Non hai oggetti nello zaino`0</td></tr>", true);
        }
        $countrow = db_num_rows($result);
        for ($i=0; $i<$countrow; $i++){
        //for ($i = 0;$i < db_num_rows($result);$i++) {
            $row = db_fetch_assoc($result);
            output("<tr class='" . ($i % 2?"trlight":"trdark") . "'><td>" . ($i + 1) . ".</td><td><A href=zaino.php?op=$row[idmateriali]>$row[nome]</a></td></tr>", true);
            addnav("", "zaino.php?op=$row[idmateriali]");
        }
        output("</table>", true);
}else{
        $idmateriale = $_GET['op'];
        $sql = "SELECT * FROM  materiali WHERE id = $idmateriale";
        $result = db_query($sql) or die(db_error(LINK));
        $row = db_fetch_assoc($result);

        output ("Il tuo zaino`n`n");
        addnav("Guarda lo zaino", "zaino.php");
        output ("Descrizione Oggetto : `n`n`n");
        output ($row['descrizione']."`n`n`n`n");
        if ($row['tipo'] == R) {

            output ("Per realizzare questa ricetta serve :`n`n`n");

            if ($row['ingrediente1']) {
                $sqlm = "SELECT nome FROM  materiali WHERE id='".$row['ingrediente1']."'";
                $resultm = db_query($sqlm) or die(db_error(LINK));
                $rowm = db_fetch_assoc($resultm);
                output ("Ingrediente 1 : ".$rowm['nome']."`n`n");
            }
            if ($row['ingrediente2']) {
                $sqlm = "SELECT nome FROM  materiali WHERE id='".$row['ingrediente2']."'";
                $resultm = db_query($sqlm) or die(db_error(LINK));
                $rowm = db_fetch_assoc($resultm);
                output ("Ingrediente 2 : ".$rowm['nome']."`n`n");
            }
            if ($row['ingrediente3']) {
                $sqlm = "SELECT nome FROM  materiali WHERE id='".$row['ingrediente3']."'";
                $resultm = db_query($sqlm) or die(db_error(LINK));
                $rowm = db_fetch_assoc($resultm);
                output ("Ingrediente 3 : ".$rowm['nome']."`n`n");
            }
            if ($row['ingrediente4']) {
                $sqlm = "SELECT nome FROM  materiali WHERE id='".$row['ingrediente4']."'";
                $resultm = db_query($sqlm) or die(db_error(LINK));
                $rowm = db_fetch_assoc($resultm);
                output ("Ingrediente 4 : ".$rowm['nome']."`n`n");
            }
            if ($row['ingrediente5']) {
                $sqlm = "SELECT nome FROM  materiali WHERE id='".$row['ingrediente5']."'";
                $resultm = db_query($sqlm) or die(db_error(LINK));
                $rowm = db_fetch_assoc($resultm);
                output ("Ingrediente 5 : ".$rowm['nome']."`n`n");
            }
            if ($row['ingrediente6']) {
                $sqlm = "SELECT nome FROM  materiali WHERE id='".$row['ingrediente6']."'";
                $resultm = db_query($sqlm) or die(db_error(LINK));
                $rowm = db_fetch_assoc($resultm);
                output ("Ingrediente 6 : ".$rowm['nome']."`n`n");
            }
            if ($row['ingrediente7']) {
                $sqlm = "SELECT nome FROM  materiali WHERE id='".$row['ingrediente7']."'";
                $resultm = db_query($sqlm) or die(db_error(LINK));
                $rowm = db_fetch_assoc($resultm);
                output ("Ingrediente 7 : ".$rowm['nome']."`n`n");
            }
            if ($row['ingrediente8']) {
                $sqlm = "SELECT nome FROM  materiali WHERE id='".$row['ingrediente8']."'";
                $resultm = db_query($sqlm) or die(db_error(LINK));
                $rowm = db_fetch_assoc($resultm);
                output ("Ingrediente 8 : ".$rowm['nome']."`n`n");
            }
            if ($row['ingrediente9']) {
                $sqlm = "SELECT nome FROM  materiali WHERE id='".$row['ingrediente9']."'";
                $resultm = db_query($sqlm) or die(db_error(LINK));
                $rowm = db_fetch_assoc($resultm);
                output ("Ingrediente 9 : ".$rowm['nome']."`n`n");
            }
            if ($row['ingrediente0']) {
                $sqlm = "SELECT nome FROM  materiali WHERE id='".$row['ingrediente0']."'";
                $resultm = db_query($sqlm) or die(db_error(LINK));
                $rowm = db_fetch_assoc($resultm);
                output ("Ingrediente 10 : ".$rowm['nome']."`n`n");
            }
        }

        if ($row['tipo'] == A OR $row['tipo'] == B OR $row['tipo'] == C OR $row['tipo'] == D OR $row['tipo'] == E OR $row['tipo'] == F) {

            output ("Per preparare questo incantesimo serve :`n`n`n");

            if ($row['ingrediente1']) {
                $sqlm = "SELECT nome FROM  materiali WHERE id='".$row['ingrediente1']."'";
                $resultm = db_query($sqlm) or die(db_error(LINK));
                $rowm = db_fetch_assoc($resultm);
                output ("Ingrediente 1 : ".$rowm['nome']."`n`n");
            }
            if ($row['ingrediente2']) {
                $sqlm = "SELECT nome FROM  materiali WHERE id='".$row['ingrediente2']."'";
                $resultm = db_query($sqlm) or die(db_error(LINK));
                $rowm = db_fetch_assoc($resultm);
                output ("Ingrediente 2 : ".$rowm['nome']."`n`n");
            }
            if ($row['ingrediente3']) {
                $sqlm = "SELECT nome FROM  materiali WHERE id='".$row['ingrediente3']."'";
                $resultm = db_query($sqlm) or die(db_error(LINK));
                $rowm = db_fetch_assoc($resultm);
                output ("Ingrediente 3 : ".$rowm['nome']."`n`n");
            }
            if ($row['ingrediente4']) {
                $sqlm = "SELECT nome FROM  materiali WHERE id='".$row['ingrediente4']."'";
                $resultm = db_query($sqlm) or die(db_error(LINK));
                $rowm = db_fetch_assoc($resultm);
                output ("Ingrediente 4 : ".$rowm['nome']."`n`n");
            }
            if ($row['ingrediente5']) {
                $sqlm = "SELECT nome FROM  materiali WHERE id='".$row['ingrediente5']."'";
                $resultm = db_query($sqlm) or die(db_error(LINK));
                $rowm = db_fetch_assoc($resultm);
                output ("Ingrediente 5 : ".$rowm['nome']."`n`n");
            }
            if ($row['ingrediente6']) {
                $sqlm = "SELECT nome FROM  materiali WHERE id='".$row['ingrediente6']."'";
                $resultm = db_query($sqlm) or die(db_error(LINK));
                $rowm = db_fetch_assoc($resultm);
                output ("Ingrediente 6 : ".$rowm['nome']."`n`n");
            }
            if ($row['ingrediente7']) {
                $sqlm = "SELECT nome FROM  materiali WHERE id='".$row['ingrediente7']."'";
                $resultm = db_query($sqlm) or die(db_error(LINK));
                $rowm = db_fetch_assoc($resultm);
                output ("Ingrediente 7 : ".$rowm['nome']."`n`n");
            }
            if ($row['ingrediente8']) {
                $sqlm = "SELECT nome FROM  materiali WHERE id='".$row['ingrediente8']."'";
                $resultm = db_query($sqlm) or die(db_error(LINK));
                $rowm = db_fetch_assoc($resultm);
                output ("Ingrediente 8 : ".$rowm['nome']."`n`n");
            }
            if ($row['ingrediente9']) {
                $sqlm = "SELECT nome FROM  materiali WHERE id='".$row['ingrediente9']."'";
                $resultm = db_query($sqlm) or die(db_error(LINK));
                $rowm = db_fetch_assoc($resultm);
                output ("Ingrediente 9 : ".$rowm['nome']."`n`n");
            }
            if ($row['ingrediente0']) {
                $sqlm = "SELECT nome FROM  materiali WHERE id='".$row['ingrediente0']."'";
                $resultm = db_query($sqlm) or die(db_error(LINK));
                $rowm = db_fetch_assoc($resultm);
                output ("Ingrediente 10 : ".$rowm['nome']."`n`n");
            }
        }

}
addnav("Metti via lo zaino", "village.php");
page_footer();

?>