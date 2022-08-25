<?php
require_once "common.php";
page_header("Area Test");
addnav("Torna al villaggio","village.php");
addnav("Ricarica Test","test.php");
require_once "common.php";
$commentary = "PROVA`atest di verifica";
$commentary = str_replace("`a","",$commentary);
output ("Prova: commentary vale ".$commentary);
page_footer();
?>