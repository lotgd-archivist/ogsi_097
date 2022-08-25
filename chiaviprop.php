<?php
require_once("common.php");
page_header("Distribuzione Chiavi");
output("<big><big><big>`c`bDistribuzione Chiavi a proprietari`b`c`n`n</big></big></big>",true);
$sqlCase = "SELECT owner, houseid FROM houses";
$case = db_query($sqlCase) or die(db_error(LINK));

for ($i = 0; $i < db_num_rows($case); $i++) {
    $casa = db_fetch_assoc($case);
    $sqlChiave = "INSERT INTO items (name,owner,class,value1,value2,gold,gems,description)
    VALUES ('House key',".$casa['owner'].",'Key',".$casa['houseid'].",10,0,0,'Key for house number ".$casa['houseid']."')";
    output($sqlChiave);
    db_query($sqlChiave) or die(db_error(LINK));
}
addnav("Torna alla Grotta","grotto.php");
page_footer();
?>