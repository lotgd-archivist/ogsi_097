<?php
require_once("common.php");
page_header("Direttive delle Sette");
$nomecarriera=array(1=>"msgsgrios","msgkarnak","msgdrago");
$numerocarriera=array(1=>9,15,54);
$dio = $session['user']['dio'];
$carriera = $session['user']['carriera'];
output("`3`c`bLe Direttive della Setta: $fedecasa[$dio]`b`c`n`n");
if ($_GET['op'] == ""){
   if ($dio == 1) {
      addnav("Torna alla Chiesa","chiesa.php");
      if ($carriera == $numerocarriera[$dio] OR $session['user']['superuser'] > 3){
         //output("`aNon è possibile usare codici colori nell'inserimento delle direttive!!!`0`n`n");
         addnav("Aggiungi Indicazione","direttive.php?op=add");
         $flag = true;
      }
   } elseif ($dio == 2) {
      if ($carriera == $numerocarriera[$dio] OR $session['user']['superuser'] > 3){
         //output("`aNon è possibile usare codici colori nell'inserimento delle direttive!!!`0`n`n");
         addnav("Aggiungi Indicazione","direttive.php?op=add");
         $flag = true;
      }
      addnav("Torna alla Grotta","karnak.php");
   } elseif ($dio == 3) {
      addnav("Torna alla Gilda","gildadrago.php");
      if ($carriera == $numerocarriera[$dio] OR $session['user']['superuser'] > 3){
         //output("`aNon è possibile usare codici colori nell'inserimento delle direttive!!!`0`n`n");
         addnav("Aggiungi Indicazione","direttive.php?op=add");
         $flag = true;
      }
   } else {
      addnav("Torna al Villaggio","village.php");
   }
   $sql = "SELECT * FROM custom
           WHERE area1 = '".$nomecarriera[$dio]."'
           ORDER BY amount ASC";
   $result = db_query($sql) or die(db_error(LINK));
   if (db_num_rows($result) == 0){
       output("<big>`c`S`iNessuna Direttiva trovata !!!`i`0`c`n`n</big>",true);
   }else{
       output("<table align='center'><tr><td colspan='4'><big>`SEcco le direttive da seguire per tutti gli appartenenti alla setta:</big></td></tr>",true);
       $countrow = db_num_rows($result);
       for ($i=0; $i<$countrow; $i++){
       //for ($i=0;$i<db_num_rows($result);$i++){
           $row = db_fetch_assoc($result);
           if ($flag){
               $comandi = "<a href='direttive.php?op=mod&id=".$row['id']."'>Edit</a>";
               addnav("","direttive.php?op=mod&id=".$row['id']);
               $comandi2 = "<a href='direttive.php?op=del&id=".$row['id']."'>Delete</a>";
               addnav("","direttive.php?op=del&id=".$row['id']);
           }
           output("<tr><td>`A`b".$comandi."`b</td><td>`G".$row['amount']."</td><td>`G".stripslashes($row['area'])."</td><td>`A`b".$comandi2."`b</td></tr>",true);
       }
       output("</table>",true);
   }
}elseif($_GET['op'] == "add"){
   output("<form action='direttive.php?op=add1' method='POST'>",true);
       output("<label>Ordine:<input name='ordine' size='4' value=''></label>`n
              <label>Regola:<input name='testo' size='80' value=''></label>",true);
   output("<input type='submit' class='button' value='Inserisci'>`n",true);
   addnav("","direttive.php?op=add1");
}elseif($_GET['op'] == "add1"){
   $testo = stripslashes($_POST['testo']);
   $session['ordine'] = preg_replace("'[^0-9]'","",$_POST['ordine']);
   output("`gConfermi il testo scritto?`n`n".$session['ordine'].". ".stripslashes($_POST['testo']));
   $session['testo'] = addslashes($_POST['testo']);
   addnav("Si","direttive.php?op=add2");
   addnav("No","direttive.php?op=add");
}elseif($_GET['op'] == "add2"){
   $sql = "INSERT INTO custom
           VALUES ('','".$session['testo']."','".$session['ordine']."',NOW(),NOW(),'".$nomecarriera[$dio]."')";
   db_query($sql) or die(db_error(LINK));
   $session['ordine'] = "";
   $session['testo'] = "";
   output("`3Testo inserito!`n`n");
   addnav("Torna all'inizio","direttive.php");
   addnav("Altra Indicazione","direttive.php?op=add");
}elseif($_GET['op'] == "mod"){
   $sql = "SELECT amount,area FROM custom
           WHERE id = ".$_GET['id'];
   $result = db_query($sql) or die(db_error(LINK));
   $row = db_fetch_assoc($result);
   rawoutput("(Devi ricopiare il testo nel campo)<br><br>".stripslashes($row['area'])."<br><br>");
   output("<form action='direttive.php?op=mod1&id=".$_GET['id']."' method='POST'>
           <label>Ordine:<input name='ordine' size='4' value='".$row['amount']."'></label>`n
           <label>Regola:<input name='testo' size='50' value=''></label>
           <input type='submit' class='button' value='Inserisci'>`n",true);
   addnav("","direttive.php?op=mod1&id=".$_GET['id']);
}elseif($_GET['op'] == "mod1"){
   output("`gConfermi il testo scritto?`n`n");
   output("`X".preg_replace("'[^0-9]'","",$_POST['ordine']).". ".stripslashes($_POST['testo'])."`n");
   $session['ordine'] = preg_replace("'[^0-9]'","",$_POST['ordine']);
   $session['testo'] = $_POST['testo'];
   addnav("Si","direttive.php?op=mod2&id=".$_GET['id']);
   addnav("No, modificalo","direttive.php?op=mod&id=".$_GET['id']);
}elseif($_GET['op'] == "mod2"){
   $sql = "UPDATE custom
           SET area = '".$session['testo']."',
           amount = ".$session['ordine']."
           WHERE id = ".$_GET['id'];
   db_query($sql) or die(db_error(LINK));
   output("`3Testo modificato!`n`n");
   addnav("Torna all'inizio","direttive.php");
   addnav("Altra Indicazione","direttive.php?op=add");
}elseif($_GET['op'] == "del"){
   $sql = "SELECT amount,area FROM custom
           WHERE id = '".$_GET['id']."'
           ORDER BY amount ASC";
   $result = db_query($sql) or die(db_error(LINK));
   $row = db_fetch_assoc($result);
   output("`3Confermi la cancellazione di questa direttiva?`n`n`X".$row['amount'].". ".stripslashes($row['area'])."`n`n");
   addnav("Si","direttive.php?op=del1&id=".$_GET['id']);
   addnav("No","direttive.php");
}elseif($_GET['op'] == "del1"){
   $sql = "DELETE FROM custom WHERE id = ".$_GET['id'];
   db_query($sql) or die(db_error(LINK));
   output("`3Direttiva cancellata!`n`n");
   addnav("Torna all'inizio","direttive.php");
}
addnav("Ricarica","direttive.php");
//addnav("Villaggio","village.php");
page_footer();
?>