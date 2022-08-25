<?php
require_once "common.php";
isnewday(3);

page_header("Editor Terre dei Draghi");
addnav("G?Torna alla Grotta","superuser.php");
addnav("M?Torna alla Mondanità","village.php");
addnav("Crea un Territorio","terradraghieditor.php?op=add");
addnav("Home Editor","terradraghieditor.php");

if ($_GET['op']==""){
   output("`(Benvenuto nell'editor dei territori dei draghi. Qui puoi vedere i territori dei draghi esistenti nel reame o`n");
   output(" crearne di nuovi.`n");
   output("Fai attenzione prima di effettuare una qualsiasi operazione, potresti avere effetti indesiderati !!`n`n");
   $sql = "SELECT * FROM terre_draghi ORDER BY id";
   output("`c`b`&Territori dei Draghi esistenti nel reame`b`c");
   output("<table cellspacing=0 cellpadding=2 align='center'>",true);
   output("<tr align=center>
               <td>`@Ops</td>
               <td>`b`#ID`b</td>
               <td>`&Nome</td>
               <td>`\$Bonus</td>
               <td>`^Occupato</td>
               <td>`@Stato</td>
               <td>`\$Totale Bonus</td>
            </tr>",true);
   $result = db_query($sql) or die(db_error(LINK));
   if (db_num_rows($result) == 0) {
      output("<tr><td colspan=7 align='center'>`&Non ci sono terre di draghi nel reame`0</td></tr>", true);
   }
   $countrow = db_num_rows($result);
   for ($i=0; $i<$countrow; $i++){
   //for ($i=0;$i<db_num_rows($result);$i++){
       $row = db_fetch_assoc($result);
       addnav("","terradraghieditor.php?op=edit&id=".$row['id']);
       addnav("","terradraghieditor.php?op=del&id=".$row['id']);
       output("<tr align='center' class='" . ($i % 2?"trlight":"trdark") . "'>
                   <td>[<a href='terradraghieditor.php?op=edit&id=".$row['id']."'>Edit</a>]
                       [<a href='terradraghieditor.php?op=del&id=".$row['id']."'>Del</a>]
                   <td>`#".$row['id']."</td>
                   <td>".$row['nome']."</td>
                   <td>`\$".$row['bonus']."</td>"
                  .($row[id_player]==0?"<td>`&NO</td>":"<td>`^SI</td>")."
                  <td>`@".$row['stato']."</td>
                  <td>`\$".$row['totale_bonus']."</td>
               </tr>",true);
   }
   output("</table>",true);

}

if($_GET['op']=="add"){
   output("Editor Terre dei Draghi:`n");
   createrritorio(array());
}

if ($_GET['op']=="edit"){
    $sql = "SELECT * FROM terre_draghi WHERE id='".$_GET['id']."'";
    $result = db_query($sql);
    if (db_num_rows($result)<=0){
        output("`i`b`\$Territorio non trovato.`i`b`0");
    }else{
        output("Editor Terre dei Draghi:`n");
        $row = db_fetch_assoc($result);
        createrritorio($row);
    }
}

if ($_GET['op']=="save"){  //Crea territorio
    $keys='';
    $vals='';
    $sql='';
    $i=0;
    reset($_POST['terra']);
    while (list($key,$val)=each($_POST['terra'])){
        if (is_array($val)) {
	    $val = addslashes(serialize($val));
        } else {
	    $val = addslashes($val);
        }
        if ($_GET['id']>""){
            $sql.="$key='".$val."', ";
        }else{
            $keys.=($i>0?", ":"")."$key";
            $vals.=($i>0?", ":"")."'".$val."'";
        }
        $i++;
    }
    if ($_GET['id']>""){
        $sql = substr($sql,0,strlen($sql)-2);
        $sql = "UPDATE terre_draghi SET ".$sql." WHERE id={$_GET['id']}";
    }else{
        $sql="INSERT INTO terre_draghi (".$keys.") VALUES (".$vals.")";
    }
    db_query($sql);
    if (db_affected_rows()>0){
        output("Terra salvata!");
    }else{
        output("Terra non salvata: $sql");
    }
}

if ($_GET['op']=="del"){  //Cancella territorio, conferma
    output("`@Sei sicuro di voler cancellare il territorio id ".$_GET['id']."?`n");
    addnav("");
    addnav("Si, procedi","terradraghieditor.php?op=del2&id=".$_GET['id']);
    addnav("No, torna al menù","terradraghieditor.php");
}

if ($_GET['op']=="del2"){  //Cancella territorio
    $sql = "DELETE FROM terre_draghi WHERE id='".$_GET['id']."'";
    db_query($sql);
    if (db_affected_rows()>0){
        output("Terra cancellata!");
    }else{
        output("Terra non cancellata: $sql");
    }
}


function createrritorio($terra){
    global $output;
    output("<form action='terradraghieditor.php?op=save&id={$terra['id']}' method='POST'>",true);
    addnav("","terradraghieditor.php?op=save&id={$terra['id']}");
    $output.="<table>";
    $output.="<tr><td>Nome:</td><td><input name='terra[nome]' value=\"".HTMLEntities2($terra['nome'])."\"></td></tr>";
    $output.="<tr><td>Bonus:</td><td><input name='terra[bonus]' value=\"".HTMLEntities2($terra['bonus'])."\"></td></tr>";
    $output.="<tr><td>ID_Player:</td><td><input name='terra[id_player]' value=\"".HTMLEntities2($terra['id_player'])."\"></td></tr>";
    $output.="<tr><td>Descrizione:</td><td><input size=100 name='terra[descrizione]' value=\"".HTMLEntities2($terra['descrizione'])."\"></td></tr>";
    $output.="<tr><td>Totale Bonus:</td><td><input name='terra[totale_bonus]' value=\"".HTMLEntities2($terra['totale_bonus'])."\"></td></tr>";
    $output.="</td></tr>";
    $output.="</table>";
    $output.="<input type='submit' class='button' value='Salva'></form>";
}

page_footer();
?>