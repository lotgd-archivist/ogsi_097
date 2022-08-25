<?php
require_once "common.php";
isnewday(2);
if ($_GET['op']=="search"){
    $sql = "SELECT guilty FROM punitions WHERE ";
    $where="guilty LIKE '%{$_POST['q']}%'";
    $result = db_query($sql.$where);
    if (db_num_rows($result)<=0){
        output("`\$Nessun player trovato`0");
        $_GET['op']="";
        $where="";
    }elseif (db_num_rows($result)>100){
        output("`\$Troppi risultati, restringi il campo di ricerca per favore.`0");
        $_GET['op']="";
        $where="";
    }elseif (db_num_rows($result)==1){
        $_GET['op']="";
        $_GET['page']=0;
    }else{
        $_GET['op']="";
        $_GET['page']=0;
    }
}

page_header("Punizioni");
output("<form action='punitions.php?op=search' method='POST'>Cerca un player: <input name='q' id='q'><input type='submit' class='button'></form>",true);
output("<script language='JavaScript'>document.getElementById('q').focus();</script>",true);
addnav("","punitions.php?op=search");
addnav("G?Torna alla Grotta","superuser.php");
addnav("M?Torna alla Mondanità","village.php");
if ($session['user']['superuser']>2) addnav("U?Editor Utenti","user.php");

$sql = "SELECT COUNT(id_punitions) AS count FROM punitions";
$result = db_query($sql);
$row = db_fetch_assoc($result);
$page=0;
while ($row['count']>0){
    $page++;
    addnav("$page Pagina di $page","punitions.php?page=".($page-1)."&sort=".$_GET['sort']);
    $row['count']-=100;
}

if ($_GET['op']==""){
    if (isset($_GET['page'])){
        $offset=(int)$_GET['page']*100;
        $order = "date DESC";
        if ($_GET['sort']!="") $order = $_GET['sort'];
        $sql = "SELECT * FROM punitions LEFT JOIN accounts ON (punitions.whomade=accounts.acctid) ".($where>""?"WHERE $where ":"")."ORDER BY $order LIMIT $offset,100";
        //echo $sql;
        $result = db_query($sql) or die(db_error(LINK));
        output("<table>",true);
        output("<tr>
        <td><a href='punitions.php?sort=acctid_guilty&page=".$_GET['page']."'>Name</a></td>
        <td><a href='punitions.php?sort=type&page=".$_GET['page']."'>Type</a></td>
        <td><a href='punitions.php?sort=numday&page=".$_GET['page']."'>Days</a></td>
        <td><a href='punitions.php?sort=date&page=".$_GET['page']."'>Date</a></td>
        <td><a href='punitions.php?sort=cause&page=".$_GET['page']."'>Cause</a></td>
        <td><a href='punitions.php?sort=whomade&page=".$_GET['page']."'>SuperUser</a></td>
        </tr>",true);
        addnav("","punitions.php?sort=acctid_guilty&page=".$_GET['page']);
        addnav("","punitions.php?sort=type&page=".$_GET['page']);
        addnav("","punitions.php?sort=numday&page=".$_GET['page']);
        addnav("","punitions.php?sort=date&page=".$_GET['page']);
        addnav("","punitions.php?sort=cause&page=".$_GET['page']);
        addnav("","punitions.php?sort=whomade&page=".$_GET['page']);
        $rn=0;
        $countrow = db_num_rows($result);
        for ($i=0; $i<$countrow; $i++){
        //for ($i=0;$i<db_num_rows($result);$i++){
          $row=db_fetch_assoc($result);
          output("<tr class='".($i%2?"trlight":"trdark")."'>",true);
          output("<td>",true);
          if ($session['user']['superuser']>2) {
            output("<a href='user.php?guilty=".rawurlencode($row['acctid_guilty'])."'>",true);
            addnav("","user.php?guilty=".rawurlencode($row['acctid_guilty'])."");
            output("`&".$row['guilty']);
            output("</a>",true);
          } else  output($row['guilty'],true);
          output("</td><td>",true);
          output($row['type']);
          output("</td><td>",true);
          output($row['numday']);
          output("</td><td>",true);
          output($row['date']);
          output("</td><td>",true);
          output($row['cause']);
          output("</td><td>",true);
          output($row['name']);
          output("</td>",true);
          output("</tr>",true);
        }
        output("</table>",true);
    }
}
page_footer();
?>