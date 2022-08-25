<?php
require_once "common.php";

if ($_GET[op]==""){

    $sql = "SELECT lastupdate,serverid FROM logdnet WHERE address='$_GET[addy]'";
    $result = db_query($sql);
    $row = db_fetch_assoc($result);

    if (db_num_rows($result)>0){
        if (strtotime($row[lastupdate])<strtotime(date("r")."-1 minutes")){
            //echo strtotime($row[lastupdate])."<br>".strtotime("-5 minutes");
            $sql = "UPDATE logdnet SET priority=priority*0.99";
            db_query($sql);
            //use PHP server time for lastupdate in case mysql server and PHP server have different times.
            $sql = "UPDATE logdnet SET priority=priority+1,description='".soap($_GET[desc])."',lastupdate='".date("Y-m-d H:i:s")."' WHERE serverid=$row[serverid]";
            //echo $sql;
            db_query($sql);
            echo "Ok - updated";
        }else{
            echo "Ok - too soon to update";
        }
    }else{
        $sql = "INSERT INTO logdnet (address,description,lastupdate) VALUES ('$_GET[addy]','".soap($_GET[desc])."',now())";
        $result = db_query($sql);
        echo "Ok - added";
    }
}elseif ($_GET[op]=="net"){
    $sql = "SELECT address,description FROM logdnet WHERE lastupdate > '".date("Y-m-d H:i:s",strtotime(date("r")."-7 days"))."' ORDER BY priority DESC";
    $result=db_query($sql);
    $countrow = db_num_rows($result);
    for ($i=0; $i<$countrow; $i++){
    //for ($i=0;$i<db_num_rows($result);$i++){
        $row = db_fetch_assoc($result);
        $row = serialize($row);
        echo $row."\n";
    }
}else{
    page_header("LoGD Net");
    //$sql = "SELECT * FROM logdnet ORDER BY priority DESC";
    //$result=db_query($sql);
    addnav("Torna alla pagina di login","index.php");

    output("`@Questo � un elenco di altri server LoGD registrati con LoGD Net`n`n");
    output("<table>",true);
    output("<tr bgcolor='#FF0000'><td>`&`bNome Server e Link`b`0</td><td width='115'>`&`bVersione`b`0</td></tr>",true);
    $servers=file(getsetting("logdnetserver","http://lotgd.net/")."logdnet.php?op=net");
    while (list($key,$val)=each($servers)){
        $row=unserialize($val);
        if (trim($row[description])=="") $row[description]="Another LoGD Server";
        if (substr($row[address],0,7)!="http://"){

        }else{
            $row['description'] = soap(HTMLEntities2($row['description']));
            $row['description'] =  str_replace("`&amp;", "`&", $row['description']);
            output("<tr class='" . ($key % 2?"trlight":"trdark") . "'>", true);
            output("<td valign='top'><a href='".HTMLEntities($row[address])."' target='_blank'>".$row[description]."`0</a></td><td valign='top' width='115'>".HTMLEntities($row[version])."</td></tr>",true);
        }
    }
    output("</table>",true);





/*  output("`@Questo � un elenco di altri server LoGD registrati con LoGD Net.");
    output("<table>",true);
    $servers=file(getsetting("logdnetserver","http://lotgd.net/")."logdnet.php?op=net");
    while (list($key,$val)=each($servers)){
        $row=unserialize($val);
        if (trim($row[description])=="") $row[description]="Un altro server LoGD";
        if (substr($row[address],0,7)!="http://"){

        }else{
            //output("<tr><td><a href='".HTMLEntities($row[address])."' target='_blank'>".soap(HTMLEntities($row[description]))."`0</a></td></tr>",true);
            $row['description'] = soap(HTMLEntities2($row['description']));
            $row['description'] =  str_replace("`&amp;", "`&", $row['description']);
            output("<tr><td><a href='".HTMLEntities($row[address])."' target='_blank'>".$row['description']."`0</a></td></tr>",true);

        }
    }
    output("</table>",true);*/
    page_footer();
}
?>