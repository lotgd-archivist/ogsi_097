<?php
require_once "common.php";
isnewday(3);
page_header("Permessi multiaccounts");
if($_GET['op']=="") {
     if ($session['user']['superuser']>=3) addnav("Aggiungi un permesso","multiaccounts.php?op=add");
     addnav("Torna alla grotta","superuser.php");
    $sql="SELECT id,acctid1,acctid2 FROM allowmulti,accounts where acctid1=acctid ORDER BY login ASC";
     $dom= db_query($sql);
     $i=0;
     output("`@Tabella degli accounts con multilogin permesso:");
     output("<table cellpadding=10>",true);
     while($row= db_fetch_assoc($dom)) {
          $trclass= ($i%2)? "trdark" : "trlight";
          $sql= "SELECT login,acctid FROM accounts WHERE acctid=".$row['acctid1']." || acctid=".$row['acctid2'];
          $ric= db_query($sql);
          output("<tr class=\"$trclass\">",true);
          while($nome= db_fetch_assoc($ric)) {
               output("<td>".$nome['login']."</td>",true);
          }
          output("<td><a href=\"multiaccounts.php?op=cancella&row=".$row['id']."\">Cancella</td></tr>",true);
          if ($session['user']['superuser']>=3) addnav("","multiaccounts.php?op=cancella&row=".$row['id']);
          $i++;
     }
     if(!$i) {
          output("<tr class=\"trlight\"><td>Nessun multiaccounts permesso</td></tr>",true);
     }
     output("</table>",true);
}else if($_GET['op']=="add") {
     addnav("Torna alla tabella","multiaccounts.php");
     if($_GET['passo']==2) {
          //$id1= ($_POST['p1']>$_POST['p2'])? $_POST['p2'] : $_POST['p1'];
          //$id2= ($_POST['p1']>$_POST['p2'])? $_POST['p1'] : $_POST['p2'];
          $id1 = $_POST['p1'];
          $id2 = $_POST['p2'];
          $query="insert into allowmulti (acctid1,acctid2) VALUES($id1,$id2)";
          db_query($query) or die(sql_error($query));
          output("Personaggi inseriti");
          //addnav("Torna alla tabella","multiaccounts.php");
     }
     if($_GET['passo']==1) {
          $p1= accsearch($_POST['pg1']);
          $p2= accsearch($_POST['pg2']);
          if($p1===FALSE) {
               output("`\$Non esiste nessun ".$_POST['pg1']."`n");
               $retry=true;
          }else {
               if($p2===FALSE) {
                    output("`\$Non esiste nessun ".$_POST['pg2']."`n");
                    $retry=true;
               }else {
                    output("`@Confermi il permesso a `&".$p1['login']." `@e `&".$p2['login']." `@di connettersi con lo stesso ip/id?");
                    output("
                    <form method=\"post\" action=\"multiaccounts.php?op=add&passo=2\">
                    <input type=\"hidden\" value=\"".$p1['acctid']."\" name=\"p1\">
                    <input type=\"hidden\" value=\"".$p2['acctid']."\" name=\"p2\">
                    <input type=\"submit\" class=\"button\" value=\"conferma\">
                    <a href=\"multiaccounts.php?op=add\" class=\"button\">Annulla</a>
                    </form>",true);
                    addnav("","multiaccounts.php?op=add");
                    addnav("","multiaccounts.php?op=add&passo=2");
               }
          }
     }
     if($_GET['passo']=="" || $retry) {
          output("`@Questa opzione registra in una tabella due accounts che sono stati segnalati come familiari! Evitando così l'avviso di multiaccount ad ogni login");
          output("`n`n`^Inserisci il nome del login dei due personaggi:");
          output("
          <form method=\"post\" action=\"multiaccounts.php?op=add&passo=1\">
               personaggio1 <input type=\"text\" value=\"".$_POST['pg1']."\" name=\"pg1\"><br>
               personaggio2 <input type=\"text\" value=\"".$_POST['pg2']."\" name=\"pg2\"><br>
               <input type=\"submit\" value=\"inserisci\">
          </form>",true);
          addnav("","multiaccounts.php?op=add&passo=1");
     }

}
elseif($_GET['op']=="cancella") {
     $sql="delete from allowmulti where id=".$_GET['row'];
     db_query($sql) or die(sql_error($sql));
     output("riga cancellata");
     addnav("Torna alla tabella","multiaccounts.php");
}

function accsearch($login) {
     $ritorno= Array();
     $query="select login,acctid from accounts where login=\"$login\"";
     $dom= db_query($query);
     $trovati= db_num_rows($dom);
     if($trovati==0) {
          return false;
     }else {
          $row= db_fetch_assoc($dom);
          return $row;
     }
}
page_footer();
?>