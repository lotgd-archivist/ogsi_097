<?php
require_once "common.php";
isnewday(3);

page_header("Pagina Acquisti Donatori");
addnav("G?Torna alla Grotta","superuser.php");
addnav("M?Torna alla Mondanità","village.php");
addnav("X?Elimina link mancanti","acquisti.php?op=erase");

if ($_GET['op']==""){
        //gestione pagine, Sook
        $ppp=200; // Linee da mostrare per pagina
        if (!$_GET['limit']){
            $page=0;
        }else{
            $page=(int)$_GET['limit'];
            addnav("Pagina Precedente","acquisti.php?limit=".($page-1)."");
        }
        $limit="".($page*$ppp).",".($ppp+1);
        $sql = "SELECT d.*, a.name,a.donation,a.donationspent
            FROM donazioni d
            LEFT JOIN accounts a ON a.acctid=d.idplayer
            WHERE a.superuser = 0
            ORDER BY d.idplayer DESC LIMIT $limit";
        $result = db_query($sql);
        if (db_num_rows($result)>$ppp) addnav("Pagina Successiva","acquisti.php?limit=".($page+1)."");
        //fine gestione pagine

    output("<table border='0' cellpadding='5' cellspacing='0'>",true);
    output("<tr><td>ID Player</td><td>Nome Player</td><td>Nome</td><td>Usi</td><td>Tipo</td></tr>",true);
    $countrow = db_num_rows($result);
    for ($i=0; $i<$countrow; $i++){
        $row = db_fetch_assoc($result);
        //$sql = "SELECT acctid,name,donation,donationspent,superuser FROM accounts WHERE acctid='".$row['idplayer']."'";
        //$resulta = db_query($sql);
        //$rowa = db_fetch_assoc($resulta);
        //if($rowa['superuser']==0){
        output("<tr class='".($i%2?"trlight":"trdark")."'>",true);
        output("<td>",true);
        output("`V".$row['idplayer']."`0",true);
        output("</td><td>`^".$rowa['name']."`0",true);
        output("</td><td>`@".$row['nome']."`0</td>",true);
        output("</td><td>`@".$row['usi']."`0</td>",true);
        output("<td>`%".$row['tipo']."`0</td>",true);
        output("</tr>",true);
        //}
    }
    output("</table>",true);
}elseif ($_GET['op']=="add1"){
    $search="%";
    for ($i=0;$i<strlen($_POST['name']);$i++){
        $search.=substr($_POST['name'],$i,1)."%";
    }
    $sql = "SELECT name,acctid,donation,donationspent FROM accounts WHERE login LIKE '$search'";
    $result = db_query($sql);
    output("Conferma l'aggiunta di ".$_POST['amt']." punti to:`n`n");
    $countrow = db_num_rows($result);
    for ($i=0; $i<$countrow; $i++){
        $row = db_fetch_assoc($result);
        output("<a href='donators.php?op=add2&id=".$row['acctid']."&amt=".$_POST['amt']."'>",true);
        output($row['name']." (",$row['donation']."/".$row['donationspent'].")");
        output("</a>`n",true);
        addnav("","donators.php?op=add2&id=".$row['acctid']."&amt=".$_POST['amt']);
    }
}elseif ($_GET['op']=="erase"){
    $sql = "DELETE donazioni FROM donazioni
            LEFT JOIN accounts
            ON donazioni.idplayer=accounts.acctid
            WHERE accounts.acctid IS NULL";
    $result = db_query($sql);
    if (db_affected_rows()>0){
        output("`^Righe cancellate: `G".db_affected_rows()."`n");
    }else{
        output("`@Nessuna riga cancellata`n");
    }
}
page_footer();
?>