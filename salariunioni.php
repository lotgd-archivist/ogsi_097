<?php
/*
carriera SGRIOS
===============
9=>"Gran Sacerdote",

carriera KARNAK
===============
15=>"Falciatore di Anime",

carriera DRAGO
==============
54=>"Dominatore di Draghi",
*/
require_once("common.php");
page_header("La Sala del Consiglio");
addcommentary();
$dio = $session['user']['dio'];
$carriera = $session['user']['carriera'];
$password = "pwsala".$dio;
$sala = "salariunioni".$dio;
if ($_GET['op']=="") {
   output("`2Benvenuto ".$fededio[$dio]."`2 nella Sala delle Riunioni di ".$fedecasa[$dio]."`2, qui potrai ");
   output("tramare in assoluta sicurezza, infatti l'accesso è riservato solo ai pochi eletti che la più alta carica ");
   output("della tua setta ha deciso di ammettere.`n`n Ora per poter accedere devi inserire la password.");
   output("<form action='salariunioni.php?op=verificapw' method='POST'><input name='pw' value=''><input type='submit' class='button' value='Scrivi Password'>`n",true);
   addnav("","salariunioni.php?op=verificapw");
   if ($dio == 1) {
      $session['user']['locazione'] = 200;
      addnav("Torna alla Chiesa","chiesa.php");
   } elseif ($dio == 2) {
      $session['user']['locazione'] = 201;
      addnav("Torna alla Grotta","karnak.php");
   } elseif ($dio == 3) {
      $session['user']['locazione'] = 202;
      addnav("Torna alla Gilda","gildadrago.php");
   } else {
      addnav("Torna al Villaggio","village.php");
   }
   if ($carriera == 9 OR $carriera == 15 OR $carriera == 54) {
      addnav("Modifica Password","salariunioni.php?op=modificapw");
   }
}elseif ($_GET['op']=="modificapw") {
   output("`@La password attuale è: `b`%".stripslashes(getsetting($password,""))."`b`@, se vuoi cambiarla scrivila nello spazio ");
   output("sottostante, altrimenti puoi tornare alla sala riunioni.`n");
   output("<form action='salariunioni.php?op=modificapw1' method='POST'><input name='newpw' value=''><input type='submit' class='button' value='Scrivi Nuova Password'>`n",true);
   addnav("","salariunioni.php?op=modificapw1");
   addnav("Torna all'ingresso","salariunioni.php");
}elseif ($_GET['op']=="modificapw1") {
   $newpw = addslashes($_POST['newpw']);
   savesetting($password,$newpw);
   output("`@Password salvata!");
   addnav("Torna all'ingresso","salariunioni.php");
}elseif ($_GET['op']=="verificapw") {
   $verifypw = $_POST['pw'];
   if ($verifypw == stripslashes(getsetting($password,"")) OR $session['user']['superuser'] > 3) {
      output("`@Password verificata correttamente, puoi procedere alla Sala Riunioni di ".$fedecasa[$dio]);
      addnav("Procedi","salariunioni.php?op=ok");
   }else{
      output("`%Spiacente, ma la password non è corretta. Potresti averla scritta in modo non corretto, oppure stai ");
      output("cercando di fare il furbo e accedere ad un'area riservata a cui non sei stato invitato.`n");
      addnav("Torna all'ingresso","salariunioni.php");
   }
}elseif ($_GET['op']=="ok") {
   output("`#Dopo aver pronunciato correttamente la parola di accesso, il pesante portone che bloccava l'accesso si apre ");
   output("senza far rumore ed entri in una sala arredata sobriamente: una grande tavola rotonda con molte poltrone ");
   output("attorno ad essa è sovrastata da un grande lampadario dove centinaia di candele illuminano la stanza.`n Ti ");
   output("accomodi ed inizia a discutere di strategie con gli altri eletti che hanno accesso alla sala.`n`n`n");
   if ($dio == 1) {
      addnav("Torna alla Chiesa","chiesa.php");
   } elseif ($dio == 2) {
      addnav("Torna alla Grotta","karnak.php");
   } elseif ($dio == 3) {
      addnav("Torna alla Gilda","gildadrago.php");
   } else {
      addnav("Torna al Villaggio","village.php");
   }
   viewcommentary($sala,"Congiura",25,"","",2);
   if ($carriera == 9 OR $carriera == 15 OR $carriera == 54 OR $session['user']['superuser'] > 2) {
      addnav("Cancella commenti","salariunioni.php?op=delete");
   }
}elseif ($_GET['op']=="delete") {
   output("`\$Sei `b`&SICURO`b`\$ di voler cancellare completamente tutti i commenti?");
   addnav("`@Si","salariunioni.php?op=delete1");
   addnav("`\$No","salariunioni.php?op=ok");
}elseif ($_GET['op']=="delete1") {
    $sql = "SELECT *
              FROM commentary
             WHERE section = '".$sala."'
             ORDER BY commentid ASC";
    $result = db_query($sql) or die(db_error(LINK));
    $countrow = db_num_rows($result);
    for ($i=0; $i<$countrow; $i++){
    //for ($i=0;$i<db_num_rows($result);$i++){
        $row = db_fetch_assoc($result);
        $sql = "INSERT INTO commentdeleted VALUES
                (
                '',
                '".$row['section']."',
                '".addslashes($row['author'])."',
                '".addslashes($row['comment'])."',
                '".$session['user']['acctid']."',
                '".$row['postdate']."'
                )";
        db_query($sql);
   }
   $sql = "DELETE FROM commentary WHERE section = '".$sala."'";
   $result = db_query($sql) or die(db_error(LINK));
   output("`@Commenti cancellati!`n");
   addnav("Torna alla Sala","salariunioni.php?op=ok");
}
page_footer();
?>