<?php
require_once "common.php";
isnewday(3);
addnav("G?Torna alla Grotta","superuser.php");
page_header("Controllo Heimdall");
$sql = "SELECT COUNT( z.idoggetto ) AS quantita, z.idoggetto AS numero_ricetta, m.nome
        FROM zaino z
        INNER JOIN materiali m ON m.id = z.idoggetto
        WHERE idplayer = 3
        GROUP BY idoggetto";
    $result = db_query($sql);
    $countrow = db_num_rows($result);
    for ($i=0; $i<$countrow; $i++){
    //for ($i=0;$i<db_num_rows($result);$i++){
        $row = db_fetch_assoc($result);
        if ($row['quantita'] > 50){
           $sql1 = "DELETE FROM zaino
                 WHERE idplayer = 3 AND idoggetto = ".$row['numero_ricetta']."
                 LIMIT ".($row['quantita'] - 50);
           $result1 = db_query($sql1);
           output("<big>`\$`bCancellate N° ".($row['quantita'] - 50)." ".$row['nome']." dallo zaino di Heimdall`b</big>`n`n",true);
        }else{
           output("`@`bNessuna ".$row['nome']." cancellata dallo zaino di Heimdall (".$row['quantita']." trovate)`b`n`n");
        }
    }
page_footer();
?>