<?php
require_once "common.php";

page_header("La tua dimora");
output("`b`c`2La tua dimora`0`c`b");
if ($HTTP_GET_VARS[op]=="strolldown"){
output("`\$Ti risvegli nel tuo comodo letto, con le coperte che ti donano un caldo tepore, e ti concedi un ultimo 
minuto prima di alzarti e affrontare nuovamente le creature della foresta! ");
checkday();
}else{
output("`n`n Bentornato a casa. Le quattro mura ti donano una certa sicurezza, sai che i malintenzionati non 
avranno vita facile, anche se hai visto dei brutti ceffi aggirarsi da queste parti.`n Ora il tuo comodo letto 
ti aspetta con il suo caldo tepore. Puoi finalmente riposarti dalle fatiche delle battaglie.");
}
addnav("Torna al Villaggio","village.php");
addnav("Dormi ","dimora.php?op=esci");
if ($HTTP_GET_VARS[op]=="esci"){
					$session['user']['location']=2;
					$session['user']['loggedin']=0;
					saveuser();
					$session=array();
					redirect("index.php");
}
page_footer();
?>
