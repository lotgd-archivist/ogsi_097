<?php
/********************************
*   Il Villaggio by Excalibur   *
*    for OGSI www.ogsi.it       *
* you can do whatever you want  *
*     with it but leave this    *
*        notice unchanged       *
*********************************/
require_once "common.php";
addcommentary();
checkday();

if ($session['user']['alive']){ }else{
	redirect("shades.php");
}

addnav("Viale dei Giardini");
addnav("B?La Filiale della Banca","banca.php");
addnav("P?I Preziosi di Virna","virna.php");
addnav("S?La Salsamenteria","salsa.php");
//let users try to cheat, we protect against this and will know if they try.
addnav("","superuser.php");
addnav("","user.php");
addnav("","taunt.php");
addnav("","creatures.php");
addnav("","configuration.php");
addnav("","badword.php");
addnav("","armoreditor.php");
addnav("","bios.php");
addnav("","badword.php");
addnav("","donators.php");
addnav("","referers.php");
addnav("","retitle.php");
addnav("","stats.php");
addnav("","viewpetition.php");
addnav("","weaponeditor.php");

if ($session[user][superuser]){
  addnav("Nuovo Giorno","newday.php");
}
addnav("Altro");
addnav("Torna al tuo Villaggio","village.php");
page_header("La Piazza del Borgo");
$session['user']['locazione'] = 188;
output("`@`c`bPiazza del Borgo`b`cIl borgo è relativamente tranquillo. Noti alcuni cittadini che si ");
output(" intrattengono pigramente davanti alle vetrine. Vedi vari negozi ed attività lungo l'unica via del villaggio. ");
output("L'orologio della piazza segna le `^".getgametime()."`@.");
output("`n`n`%`@Alcuni abitanti del borgo spettegolano nelle vicinanze:`n");
viewcommentary("villaggio","Aggiungi",25,10);
page_footer();
?>