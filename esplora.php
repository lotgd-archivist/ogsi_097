<?php
require_once "common.php";
require_once("common2.php");

checkday();

page_header("Le mappe della foresta");

output("`@Osservi con attenzione le mappe che hai acquistato a caro prezzo da `(Merk`@, che indicano l'ubicazione di alcuni luoghi speciali nascosti dalla foresta.`n");
output("L'esplorazione di ognuno di questi luoghi ti costerà `&2`@ turni, e potrebbe essere molto rischiosa, ma portare anche alla scoperta di grosse ricompense...`n`n");
output("Intendi recarti in uno dei luoghi descritti nelle mappe, o preferisci ritornare nella foresta?");
addnav("Torna nella foresta","forest.php");

//determinazione luoghi accessibili dal player
if ($session['user']['turns']>1) {
    addnav("Luoghi speciali");
    $sql = "SELECT * FROM mappe_foresta_player WHERE acctid=".$session['user']['acctid']." AND visitato=0 ORDER BY luogo ASC";
    $result = db_query($sql) or die(db_error(LINK));
    $countrow = db_num_rows($result);
    for ($i=0; $i<$countrow; $i++){
    //for($i=0;$i< db_num_rows($result);$i++){
        $row = db_fetch_assoc($result);
        //determinazione nome del luogo
        $sql2 = "SELECT nome FROM mappe_foresta_luoghi WHERE id=".$row[luogo];
        $result2 = db_query($sql2) or die(db_error(LINK));
        $row2 = db_fetch_assoc($result2);
        addnav($row2[nome],"esplorazione.php?luogo=".$row[luogo]);
    }
}

page_footer();
?>