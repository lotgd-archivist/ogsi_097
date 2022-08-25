<?php
require_once "common.php";
page_header("MD5 PASSWORD");
if ($_GET['op']==""){
    output("`c`b`&Codifica MD5 Password Utenti`b`c`n");
    output("`^Questo script serve a codificare le password di tutti gli utenti con l'algoritmo MD5.`n");
    output("Ora leggerai la tua password, se quello che leggerai è la tua PW in chiaro, significa che l'algoritmo ");
    output("non è stato ancora applicato e puoi quindi procedere con la codifica. Se al contrario quella che leggerai ");
    output("sarà una sequenza di caratteri senza senso, vuol dire che la codifica è già stata applicata alle password ");
    output(" e quindi `\$`bNON DEVI`b`^ applicare l'algoritmo, pena l'IMPOSSIBILITÁ per tutti gli utenti di fare ");
    output("correttamente il login.`n`n");
    output("`b`c<font size='+1'>`%La tua attuale password è: `^".$session['user']['password']."`b</font>`c`n`n",true);
    output("`\$`n`n`c<font size='+1'>ATTENZIONE !!! Stai per codificare le PW in MD5.`n",true);
    output("Sei sicuro al 100% di volerlo fare ?</font>`c",true);
    addnav("G?`#Torna alla Grotta","superuser.php");
    addnav("M?`@Torna alla Mondanità","village.php");
    addnav("C?`^Codifica MD5","md5.php?op=verifica");
}elseif ($_GET['op']=="verifica"){
    output("`@<font size='+1'>`c`n`nQuesta è l'ultima possibilità che hai. `nOltre questo punto non potrai tornare indietro.`n",true);
    output("`\$Vuoi VERAMANTE codificare le password utenti con l'algoritmo MD5 ?`c</font>`n`n",true);
    output("`b`c<font size='+1'>`%La tua attuale password è: `^".$session['user']['password']."`b</font>`c`n`n",true);
    addnav("G?`#Torna alla Grotta","superuser.php");
    addnav("M?`@Torna alla Mondanità","village.php");
    addnav("P?`\$Codifica MD5","md5.php?op=sicuro");
}elseif ($_GET['op']=="sicuro"){
   addnav("G?`#Torna alla Grotta","superuser.php");
   addnav("M?`@Torna alla Mondanità","village.php");
   $sql="SELECT acctid,login,password FROM accounts WHERE acctid <> ".$session['user']['acctid'];
   $result = db_query($sql);
   $countrow = db_num_rows($result);
   for ($i=0; $i<$countrow; $i++){
   //for ($i=0;$i<db_num_rows($result);$i++){
     $row = db_fetch_assoc($result);
     $newpass = md5($row['password']);
     $sqlu = "UPDATE accounts SET password = '".$newpass."' WHERE acctid = ".$row['acctid'];
     $resultu = db_query($sqlu);
     output("`3Aggiornata PW di ".$row['login'].": `#Old PW: ".$row['password']." `%New PW: $newpass`n");
   }
   $session['user']['password'] = md5($session['user']['password']);
}
page_footer();
?>