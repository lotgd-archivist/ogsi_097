<?php
require_once "common.php";
isnewday(2);
addcommentary();

$statuses=array(0=>"`b`\$Non Letta`b","`@Letta`0","`#Chiusa`0","`%Storica`0");

array_push($petizioni, "SPAM");


if ($_GET['op']=="delsi"){
    $sql = "SELECT accounts.login, petitions.body
           FROM petitions
           LEFT JOIN accounts ON accounts.acctid=petitions.author
           WHERE petitionid=".$_GET['id'];
    $result = db_query($sql);
    $row = db_fetch_assoc($result);
    $start = strpos($row['body'],"n] ");
    $b = substr($row['body'],($start+5));
    $start = strpos($b,"[contaparole]");
    $length = strlen($b);
    $c = substr($b,(-$length),$start);
    debuglog("`%cancella la pet.N° `&".$_GET['id']."`% di `%".$row['login'].": `(".$c);
    $sql = "DELETE FROM petitions WHERE petitionid=".$_GET['id'];
    db_query($sql);
    $sql = "DELETE FROM commentary WHERE section='pet-".$_GET['id']."'";
    db_query($sql);
    $_GET['op']="";
}
if ($_GET['op']=="del"){
    output("`\$`bSei sicuro di voler cancellare questa petizione ???`b");
    addnav("`\$Si","viewpetition.php?op=delsi&id=".$_GET['id']);
    addnav("`%No","viewpetition.php");
}
page_header("Visore Petizioni");
addnav("G?Torna alla Grotta","superuser.php");
addnav("M?Torna alla Mondanità","village.php");
addnav("A?Aggiungi FAQ","aggiungifaq.php");
if ($_GET['op']==""){
    if ($_GET['setstat']!=""){
        $sql = "UPDATE petitions SET status=\"".$_GET['setstat']."\"";
        if ($_GET['setstat']=="2") $sql .= ", datedel=\"".date("Y-m-d H:i:s",strtotime(date("r")."+7 days"))."\", closedby=\"".$session['user']['name']."\"";
        $sql .= " WHERE petitionid=\"".$_GET['id']."\"";
        db_query($sql);
        //output($sql);
    }
//    $sql = "DELETE FROM petitions WHERE status=2 AND date<'".date("Y-m-d H:i:s",strtotime(date("r")."-7 days"))."'";
    $sql = "DELETE FROM petitions WHERE status=2 AND datedel<'".date("Y-m-d H:i:s",strtotime(date("r")))."'";
    db_query($sql);
    for ($j = 1; $j <= count($petizioni); $j++){
        $sql = "SELECT petitionid,accounts.name,petitions.date,petitions.datedel,petitions.categoria,petitions.closedby,petitions.status,petitions.body
                FROM petitions
                LEFT JOIN accounts ON accounts.acctid=petitions.author
                WHERE categoria = ".$j."
                ORDER BY status ASC, date ASC";
        $result = db_query($sql);
        output("<table border='0'>",true);
        if (db_num_rows($result)!=0){
           output("<br><tr bgcolor='#BBBB33'><td colspan='8' align='center'>`v`bCategoria: ".$petizioni[$j]."`b</td></tr>",true);
           output("<tr class='trhead'><td>Num</td><td>Operazione</td><td>Da</td><td>Spedita il</td>
           <td>Stato</td><td>Commenti</td><td>Data Autocancellazione</td><td>Chiusa da</td></tr>",true);
           $countrow = db_num_rows($result);
           for ($i=0; $i<$countrow; $i++){
           //for ($i=0;$i<db_num_rows($result);$i++){
               $row = db_fetch_assoc($result);
               $sql = "SELECT count(commentid) AS c FROM commentary WHERE section='pet-".$row['petitionid']."'";
               $res = db_query($sql);
               $counter = db_fetch_assoc($res);
               output("<tr class='".($i%2?"trlight":"trdark")."'><td>".$row['petitionid']."</td>
                       <td>[<a href='viewpetition.php?op=view&id=".$row['petitionid']."'>Leggi</a>|
                       <a href='viewpetition.php?op=del&id=".$row['petitionid']."' onClick='return conferma(\"Sei sicuro di voler cancellare questa petizione?\");'>Del</a>|
                       <a href='viewpetition.php?setstat=0&id=".$row['petitionid']."'>Non Letta</a>|
                       <a href='viewpetition.php?setstat=1&id=".$row['petitionid']."'>Letta</a>|
                       <a href='viewpetition.php?setstat=2&id=".$row['petitionid']."'>Chiusa</a>|
                       <a href='viewpetition.php?setstat=3&id=".$row['petitionid']."'>Storica</a>]</td>",true);
               output("<td>",true);
               if ($row['name']==""){
                   output(preg_replace("'[^a-zA-Z0-91234567890\\[\\]= @.!,?-]'","",substr($row['body'],0,strpos($row['body'],"[email"))));
               }else{
                   output($row['name']);
               }
               if ( date("Y-m-d") == substr($row['date'],0,10) ) {
                   output("</td><td>`\$".$row['date']."</td>",true);
               }else{
                   output("</td><td>".$row['date']."</td>",true);
               }
               output("<td>".$statuses[$row['status']]."</td>
                       <td>".$counter['c']."</td>",true);
               if (($row['status'] == 2) AND (strtotime($row['datedel']) > strtotime(date("r")))){
                   output("<td>`\$".$row['datedel']."</td>",true);
               }else{
                   output("<td>Non si autocancella</td>",true);
               }
               if (($row['status'] == 2) AND ($row['closedby'] != "")){
                   output("<td>`\$".$row['closedby']."</td></tr>",true);
               }else{
                   output("<td>-</td></tr>",true);
               }

               addnav("","viewpetition.php?op=view&id=".$row['petitionid']);
               addnav("","viewpetition.php?op=del&id=".$row['petitionid']);
               addnav("","viewpetition.php?setstat=0&id=".$row['petitionid']);
               addnav("","viewpetition.php?setstat=1&id=".$row['petitionid']);
               addnav("","viewpetition.php?setstat=2&id=".$row['petitionid']);
               addnav("","viewpetition.php?setstat=3&id=".$row['petitionid']);
           }
        }
    }
    addnav("Ricarica Pagina","viewpetition.php");
    output("</table><br>",true);
    output("`i(N.B. In `\$ rosso `0 sono evidenziate le petizioni del giorno. Le petizioni chiuse si cancelleranno automaticamente dopo `\$7 giorni`0)`i");
    output("`n`n`b`&Stato`0`b`nNon Letta: Nessuno sta lavorando al problema, e non è stato quindi ancora risolto.
    `nLetta: Qualcuno sta probabilmente lavorando al problema.
    `nChiusa: Questo problema è stato risolto, non è richiesto nessun lavoro aggiuntivo.`n`n
    Quando leggi una petizione, viene marcata come \"Letta\" a meno che venga marcata come \"Chiusa\".`n
    Se non riesci a risolvere subito il problema, per favore segnala come \"Non Letta\" così che qualcun altro
    possa aiutare il giocatore.`n
    Le petizione segnate \"Letta\" sono probabilmente sotto investigazione di qualcuno, perciò lasciala stare
    a meno che siano in quello stato già da un po' (forse qualcuno si è dimenticato di segnarla \"Non Letta\"
    dopo avergli dato un'occhiata).`n
    Se hai risolto il problema, marcala \"Chiuso\", e si autocancellerà dopo 7 giorni.`n
    Oppure marcala \"Storica\" per conservarla senza incombere nell'autocancellazione 
    (le petizioni storiche si intendono anche chiuse)");
}elseif($_GET['op']=="view"){
    //print "Categoria: ".$_POST['categoria']."<br>";
    if ($_GET['subop'] == "newcat" and $_POST['categoria']!=""){
        $sql1 = "UPDATE petitions SET categoria=".$_POST['categoria']." WHERE petitionid='".$_GET['id']."'";
        db_query($sql1);
        //print $sql1;
        $_GET['subop'] = "";
    }
    if ($session['user']['superuser'] > 2){
        if ($_GET['viewpageinfo']==1){
            addnav("Nascondi Dettagli","viewpetition.php?op=view&id=".$_GET['id']);
        }else{
            addnav("Mostra Dettagli","viewpetition.php?op=view&id=".$_GET['id']."&viewpageinfo=1");
        }
    }
    addnav("Visore Petizioni","viewpetition.php");

    addnav("Operazioni Petizioni");
    addnav("Chiudi Petizione","viewpetition.php?setstat=2&id=".$_GET['id']);
    addnav("Marca Storica","viewpetition.php?setstat=3&id=".$_GET['id']);
    addnav("Marca Non Vista","viewpetition.php?setstat=0&id=".$_GET['id']);
    addnav("Marca Vista","viewpetition.php?setstat=1&id=".$_GET['id']);

    $sql = "SELECT accounts.name,accounts.login,accounts.acctid,petitions.date,petitions.status,categoria,petitionid,body,pageinfo
            FROM petitions
            LEFT JOIN accounts ON accounts.acctid=petitions.author
            WHERE petitionid='".$_GET['id']."'
            ORDER BY date ASC";
    $result = db_query($sql);
    $row = db_fetch_assoc($result);
    if ($row['acctid']>0){
        addnav("Edita Dati Utente","user.php?op=edit&userid=".$row['acctid']."&returnpetition=".$_GET['id']);
    }
    output("`@Da: ");
    $row['body']=stripslashes($row['body']);
    if ($row['login']>""){
       output("<a href=\"mail.php?op=write&to=".rawurlencode($row['login']).
       "&body=".URLEncode("\n\n----- Tua Petizione -----\n".$row['body']).
       "&subject=RE:+Petition\" target=\"_blank\" onClick=\"".
       popup("mail.php?op=write&to=".rawurlencode($row['login'])."&body=".
       URLEncode("\n\n----- Tua Petizione -----\n".$row['body']).
       "&subject=RE:+Petition").";return false;\">
       <img src='images/newscroll.GIF' width='16' height='16' alt='Write Mail' border='0'></a>",true);
    }
    output("`^`b".$row['name']."`b`n");
    output("`@Data: `^`b".$row['date']."`b`n");
    output("`@Corpo:`^`n");
    //$body = HTMLEntities($row['body']);
    $body = HTMLEntities2($row['body']);
    $body = preg_replace("'([[:alnum:]_.-]+[@][[:alnum:]_.-]{2,}([.][[:alnum:]_.-]{2,})+)'i","
            <a href='mailto:\\1?subject=RE: Petition&body=\"".str_replace("+"," ",
            URLEncode(HTMLEntities2("\n\n----- Tua Petizione -----\n".$row['body'])))."\"'>\\1</a>",$body);
    $body = preg_replace("'([\\[][[:alnum:]_.-]+[\\]])'i","<span class='colLtRed'>\\1</span>",$body);
    $output.="<span style='font-family: fixed-width'>".nl2br($body)."</span>";

    output("<br><form action='viewpetition.php?op=view&subop=newcat&id=".$_GET['id']."' method='POST'>
    <select name=\"categoria\">",true);
        for ($i = 1; $i <= count($petizioni); $i++){
            if ($row['categoria'] == $i){
                output("<option value=\"".$i."\" selected>".$i." ".$petizioni[$i]."</option>",true);
            }else{
                output("<option value=\"".$i."\">".$i." ".$petizioni[$i]."</option>",true);
            }
        }
        output("</select>`n<input type='submit' class='button' value='Cambia Categoria'></form>",true);
    addnav("","viewpetition.php?op=view&subop=newcat&id=".$_GET['id']);

    output("`n`@Commento:`n");
    viewcommentary("pet-".$_GET['id']."","Add",200);
    if ($_GET['viewpageinfo']){
        output("`n`n`@Page Info:`&`n");
        $row[pageinfo]=stripslashes($row['pageinfo']);
        $body = HTMLEntities2($row['pageinfo']);
        $body = preg_replace("'([[:alnum:]_.-]+[@][[:alnum:]_.-]{2,}([.][[:alnum:]_.-]{2,})+)'i","
                <a href='mailto:\\1?subject=RE: Petition&body=\"".str_replace("+"," ",
                URLEncode(HTMLEntities2("\n\n----- Tua Petizione -----\n".$row['body'])))."\"'>\\1</a>",$body);
        $body = preg_replace("'([\\[][[:alnum:]_.-]+[\\]])'i","<span class='colLtRed'>\\1</span>",$body);
        $output.="<span style='font-family: fixed-width'>".nl2br($body)."</span>";
    }
    if ($row['status']==0) {
        $sql = "UPDATE petitions SET status=1 WHERE petitionid='".$_GET[id]."'";
        $result = db_query($sql);
    }
}page_footer();
?>