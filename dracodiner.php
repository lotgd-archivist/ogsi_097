<?php
require_once "common.php";
addcommentary();
checkday();
$op = $_GET['op'];
$mesedioggi = intval(date("m"));
$meselatino = array(1=>'Ianuarius',
                       'Februarius',
                       'Martius',
                       'Aprilis',
                       'Maius',
                       'Iunius',
                       'Iulius',
                       'Augustus',
                       'September',
                       'October',
                       'November',
                       'December');
$datadioggi = (date("Y,")-873)." addì ".date("d ").$meselatino[$mesedioggi] ;
$frase = array(
          1=>"Divori %art% %nome% in pochi secondi come un barbaro ... ma in fondo `bSEI`b un barbaro!",
          2=>"Afferri %art% %nome% con le mani ed inizi ad ingozzarti.`nPer tua fortuna nessun altro qui sembra usare le posate.",
          3=>"Afferri il piatto ed ingurgiti %art% %nome% con avidità.`nNon riesci a capire il motivo per cui si preoccupano di mettere il cucchiaio per te.",
          4=>"Afferri %art% %nome% con le mani ed inizi a mangiare, piuttosto rumorosamente ad essere sinceri.`nTi chiedi cosa siano quei brillanti oggetti metallici che si trovano di fianco al piatto.",
          5=>"Addenti %art% %nome% come un cane rabbioso.  È finito in fretta.`nTi interroghi sulla funzione di quel rettangolo di stoffa a fianco del piatto.",
          6=>"Azzanni %art% %nome% con appetito famelico.`nDopo un potente rutto che fa tremare il locale ti giri verso l'avventore seduto al tavolo a fianco e dici \"Buono eh?\"",
          7=>"Ingurgiti %art% %nome% in un nanosecondo.`nQuando hai finito usi la manica per pulirti la bocca, senza successo comunque."
);
$fraseliquido = array(
          1=>"Sbevazzi %art% %nome% in pochi secondi come un barbaro ... ma in fondo `bSEI`b un barbaro!",
          2=>"Ingolli una tazza di %nome% con grande avidità sbrodolandoti tutto.`nUno spettacolo semplicemente ributtante.",
          3=>"Afferri la ciotola contenente %art% %nome% e bevi tutto in pochi secondi.`nEri proprio assetato!!",
          4=>"Ingurgiti un boccale di %nome% in un istante e concludi la bevuta con poderoso rutto con lascia gli altri clienti del locale a bocca aperta.",
          5=>"A grandi sorsate bevi %art% %nome% come un avido assetato. `nMentre ti pulisci la bocca con il dorso della mano, ti chiedi a cosa possa servire quel rettangolo di stoffa a fianco del piatto.",
          6=>"Ingolli in un fiato %art% %nome% con bramosia.`nDopo un potente rutto che fa tremare il locale ti giri verso l'avventore seduto al tavolo vicino e dici \"Ci voleva proprio!\"",
          7=>"Ingurgiti %art% %nome% in un nanosecondo.`nQuando hai finito usi la manica del tuo abito per pulirti la bocca, senza successo comunque."
);
reset($_POST);
if (is_array($_POST['mount'])) reset($_POST['mount']);
page_header("Taverna del Drago D'Oro");
$session['user']['locazione'] = 117;
if ($session['user']['superuser'] > 2) {
   addnav("Admin");
   addnav("`\$Gestione Cibi","dracodiner.php?op=gestione");
}
$sql="SELECT categoria FROM cibi ORDER BY categoria DESC LIMIT 1";
$result = db_query($sql);
$row = db_fetch_assoc($result);
$maxcat = $row['categoria'];
if (!is_array($session['cibi'])){
   $sql="SELECT categoria FROM cibi ORDER BY categoria DESC LIMIT 1";
   $result = db_query($sql);
   $row = db_fetch_assoc($result);
   $maxcat = $row['categoria'];
    $session['cibi'] = array();
    for ($i = 1; $i <= $maxcat; $i++){
        $sql="SELECT * FROM cibi WHERE categoria = ".$i." ORDER BY RAND() LIMIT 1";
        $result = db_query($sql);
        $session['cibo'][$i] = db_fetch_assoc($result);
    }
}
$color = "!fFRrgXx84(^&)";
output("<font size=+1>`c`b`XTaverna del Drago d'`^Oro`0`b</font>`n`n",true);
output("<img src='./images/insegna.jpg' border='0' align='center'>`c`n",true);

//Festa, Sook, 1° parte (impostazione data)
$festa=0;
if (date("m-d")==getsetting("festa","no") /*OR $data==$datafesta*/) $festa=1;
//if ($data==$datafesta) output("<big>`n`n`c`b`^FESTA DELLA CARNE`b`c`n`n`0</big>",true);
//Fine festa

for ($j=0;$j<6;$j+=1){
$chow[$j]=substr(strval($session['user']['chow']),$j,1);
if ($chow[$j] > 0) $userchow++;
}
$pricebase=($session['user']['dragonkills'] * 2) + ($session['user']['reincarna'] * 7) + ($session['user']['level'] * 2);
if ($op != "cameriere" AND $op != "gestione") {
    if ($op == ""){
        output("`2L'insegna invitante ti riporta alla mente una leggenda locale che racconta di come la Taverna ");
        output("ha cambiato nome a causa di un pericoloso `@Drago `2avvistato nei paraggi. \"`#Chissà se è vero ");
        output("o solo una storia raccontata da qualche cliente ubriaco? `2\" ti domandi. ");
        output("`nEntri comunque nel locale con la speranza di aver trovato finalmente il luogo giusto per calmare ");
        output("i morsi della fame che senti nello stomaco, `nil profumino che senti è invitante e ti fa venire subito ");
        output("l'acquolina in bocca.`nDopo esserti accomodat".($session[user][sex]?"a":"o")." ad un tavolo libero e abbastanza pulito, ");
        output("afferri una pergamena che riporta il menù del giorno e inizi a scorrere la lista delle portate proposte valutando attentamente cosa mangiare in base alla tua fame e, ");
        output("soprattutto, alle monete d'oro che contiene il tuo borsellino.`n");
        //output("`2Questo è il posto giusto per placare i morsi della fame che senti nello stomaco. `n");
        //output("Il locale ha mutuato il nome dal pericoloso drago avvistato da queste parti. `n");
        if ($festa==1) {
           output("`n`c`S`bNoti su una parete del locale un manifesto in cui è scritto che,`nessendo un ");
           output("giorno di festa, oggi è possibile mangiare un pasto completo alla `XTaverna del Drago d'`^Oro`2 gratuitamente.`b`c`2`n");
        }
        //output("Dopo esserti accomodato ad un tavolo afferri un menù e inizi a scorrerlo.`n`n");
        output("`n<big>`b`c`^=-=-=-=LISTA DELLE VIVANDE=-=-=-=`b</big>`n",true);
        output("`(Anno Domini $datadioggi`c");
        output("<table border='0' cellpadding='1' align='center'",true);
        for ($i = 1; $i <= count($session['cibo']); $i++){
            $cibo = $session['cibo'][$i]['nome'];
            output("<tr><td><a href=\"dracodiner.php?op=".$i."\">`".substr("$color", ($i-1), 1).$cibo."</a></td>&nbsp;&nbsp;&nbsp;
                    <td align='right'>`".substr("$color", ($i-1), 1).($festa?0:($pricebase+$session['cibo'][$i]['costo']))."</td>
                    <td>`".substr("$color", ($i-1), 1)."Pezzi d'Oro</td></tr>",true);
            addnav("","dracodiner.php?op=".$i);
        }
        output("</table>",true);
        //Modifica lavoro da cameriere, Sook (prima parte)
        if ($session['user']['dragonkills'] < 6 AND $session['user']['turns'] > 0) {
            output("`n`n`2Noti anche un cartello, vicino alla porta d'entrata, su cui è scritto:`%\"Cercasi urgentemente camerier".($session[user][sex]?"a":"e")."\"`2.");
            output("`nQuesta potrebbe essere una possibilità per fare soldi senza alcun rischio...");
            addnav("Lavoro");
            addnav("Lavora come camerier".($session[user][sex]?"a":"e")."","dracodiner.php?op=cameriere");
        }
        output("`n`n`2Ai tavoli vicini al tuo senti altri guerrieri affamati conversare tra loro.");
        viewcommentary("tavernadrago","parla con gli altri",20,10);
    }elseif (is_numeric($op)){
        /*
        echo "Gold: ".$session['user']['gold'].
             "<br>Costo: ".($pricebase+$session['cibo'][$op]['costo']).
             "<br>Indice: ".$op."<br>";
        */
        if ($session['user']['gold'] >= ($pricebase+$session['cibo'][$op]['costo']) OR $festa == 1){
            if ($session['user']['hunger']>-10){
	            if ($session['cibo'][$op]['liquido']) {
		            $old = array("%art%", "%nome%");
                	$new = array($session['cibo'][$op]['articolo'], $session['cibo'][$op]['nome']);
                	$nuovafrase = str_replace($old, $new, $fraseliquido[e_rand(1,count($fraseliquido))]);
		        }else{   
		            $old = array("%art%", "%nome%");
                	$new = array($session['cibo'][$op]['articolo'], $session['cibo'][$op]['nome']);
                	$nuovafrase = str_replace($old, $new, $frase[e_rand(1,count($frase))]);
                }	
                //$frase1 = $frase[e_rand(1,count($frase))];
                //$old = array("%art%", "%nome%");
                //$new = array($session['cibo'][$op]['articolo'], $session['cibo'][$op]['nome']);
                //$nuovafrase = str_replace($old, $new, $frase[e_rand(1,count($frase))]);
                output("`@".$nuovafrase."`n");
                $session['user']['hunger']-=($session['cibo'][$op]['fame']*1.4);
                $session['user']['bladder']+=$session['cibo'][$op]['sete'];
                if ($festa == 1){
                    output("`iIl fatto di non averlo pagato lo rende ancora più appetitoso!`i`n");
                }else{
                    $session['user']['gold']-=($pricebase+$session['cibo'][$op]['costo']);
                }
                addnav("`^Ancora!","dracodiner.php");
            }else{
                output("`%Sei troppo pien".($session[user][sex]?"a":"o")." per poter ingollare anche una sola porzione!");
            }
        }else{
            output("`\$`bNon te lo puoi permettere!");
            addnav("Torna alla Taverna","dracodiner.php");
        }
    }
}elseif ($op == "cameriere") {
    output("`2Accetti il lavoro, ed entri in cucina a proporti come camerier".($session[user][sex]?"a":"e").".`n");
    output("Ti viene consegnato un grembiule, che indossi prontamente, e passi almeno 2 ore a servire i clienti della taverna.`n`n");
    output("Infine la taverna chiude e, dopo aver sparecchiato i tavoli riordinato i piatti e le stoviglie e scopato il pavimento, ti viene consegnata la paga che hai faticosamente guadagnato.`n`n");
    $paga= 20 + 10 * ($session['user']['level'] - 1);
    output("Hai guadagnato `^".$paga." pezzi d'oro`2.`n`n");
    if (e_rand(1,10) == 1) {
            $mancia=e_rand(1, ($session['user']['level'] * 10));
            $paga += $mancia;
            output("Mentre lavoravi hai ricevuto una lauta mancia da un ricco cittadino.`");
            output("Hai guadagnato anche altri `^".$mancia." pezzi d'oro`2.`n`n");
    }
    $session['user']['gold'] += $paga;
    debuglog("guadagna $paga oro lavorando come cameriere alla taverna.");
    $session['user']['turns'] -= 1;
    output("Il tempo speso a lavorare ti costa `\$1 combattimento `2nella foresta");
    addnav("Torna alla taverna","dracodiner.php");
    if ($session['user']['turns'] > 0){
       addnav("Lavora ancora","dracodiner.php?op=cameriere");
    }
}elseif ($op == "gestione") {
    addnav("Aggiungi cibo","dracodiner.php?op=gestione&az=add");
    if ($_GET['az'] == ""){
        $sql = "SELECT * FROM cibi ORDER BY categoria ASC, id ASC";
        $result = db_query($sql);
        output("<table cellspacing=0 cellpadding=2 align='center' border='1'>
        <tr class='trhead'><td></td><td>Categoria</td><td>Articolo</td><td>Nome</td><td>Costo</td>
        <td>Liv.Min.</td><td>Liv.Max</td><td>Fame</td><td>Sete</td><td>Liquido</td><td></td></tr>",true);
        $countrow = db_num_rows($result);
        for ($i=0; $i<$countrow; $i++){
        //for ($i = 0; $i<db_num_rows($result); $i++){
            $row = db_fetch_assoc($result);
            output("<tr class='".($i%2?"trlight":"trdark")."'>",true);
            output("<td>[<a href=\"dracodiner.php?op=gestione&az=modifica&id=".$row['id']."\">Edit</a>]</td>",true);
            output("<td>".$row['categoria']."</td>",true);
            output("<td>".$row['articolo']."</td>",true);
            output("<td>".$row['nome']."</td>",true);
            output("<td>".$row['costo']."</td>",true);
            output("<td>".$row['liv_min']."</td>",true);
            output("<td>".$row['liv_max']."</td>",true);
            output("<td>".$row['fame']."</td>",true);
            output("<td>".$row['sete']."</td>",true);
            output("<td>".$row['liquido']."</td>",true);
            output("<td>[<a href=\"dracodiner.php?op=gestione&az=delete&id=".$row['id']."\">Del</a>]</td>",true);
            output("</tr>",true);
            addnav("","dracodiner.php?op=gestione&az=modifica&id=".$row['id']);
            addnav("","dracodiner.php?op=gestione&az=delete&id=".$row['id']);
        }
        output("</table>",true);
    }elseif ($_GET['az'] == "modifica"){
        $sql = "SELECT * FROM cibi WHERE id = ".$_GET['id'];
        $result = db_query($sql);
        output("Editor cibi:`n");
        $row = mysql_fetch_assoc($result);
        creaentry($row);
    }elseif ($_GET['az'] == "delete"){
        output("Sicuro di voler cancellare questo record?`n");
        $sql = "SELECT * FROM cibi WHERE id='".$_GET['id']."'";
        $result = mysql_query($sql);
        $row = db_fetch_assoc($result);
        output("<table cellspacing=0 cellpadding=2 align='center' border='1'>
        <tr class='trhead'><td>Categoria</td><td>Articolo</td><td>Nome</td><td>Costo</td>
        <td>Liv.Min.</td><td>Liv.Max</td><td>Fame</td><td>Sete</td><td>Liquido</td></tr>",true);
        output("<tr class='".($i%2?"trlight":"trdark")."'>",true);
        output("<td>".$row['categoria']."</td>",true);
        output("<td>".$row['articolo']."</td>",true);
        output("<td>".$row['nome']."</td>",true);
        output("<td>".$row['costo']."</td>",true);
        output("<td>".$row['liv_min']."</td>",true);
        output("<td>".$row['liv_max']."</td>",true);
        output("<td>".$row['fame']."</td>",true);
        output("<td>".$row['sete']."</td>",true);
        output("<td>".$row['liquido']."</td>",true);
        output("</tr></table>",true);
        addnav("`\$Si","dracodiner.php?op=gestione&az=deletesi&id=".$_GET['id']);
        addnav("`@No, ci ho ripensato","dracodiner.php?op=gestione");
    }elseif ($_GET['az'] == "deletesi"){
        $sql = "DELETE FROM cibi WHERE id='".$_GET['id']."'";
        $result = mysql_query($sql);
        output("Cibo cancellato");
    }elseif ($_GET['az'] == "add"){
        $row['id'] = null;
        creaentry($row);
    }elseif ($_GET['az']=="save"){
        $keys='';
        $vals='';
        $sql='';
        $i=0;
        while (list($key,$val)=each($_POST['mount'])){
           if (is_array($val)) $val = addslashes(serialize($val));
              if ($_GET['id']>""){
                 $sql.=($i>0?",":"")."$key='$val'";
              }else{
                 $keys.=($i>0?",":"")."$key";
                 $vals.=($i>0?",":"")."'$val'";
              }
              $i++;
        }
        if ($_GET['id']>""){
           $sql="UPDATE cibi SET $sql WHERE id=".$_GET['id'];
        }else{
           $sql="INSERT INTO cibi ($keys) VALUES ($vals)";
        }
        db_query($sql);
        if (db_affected_rows()>0){
           output("`SCibo salvato!");
        }else{
           output("`ACibo non salvato: $sql");
        }
    }
}
addnav("Esci");
addnav("`@Torna al Villaggio","village.php");
function creaentry($mount){
    global $output;
    output("<form action=\"dracodiner.php?op=gestione&az=save&id=".$mount['id']."\" method=\"POST\">",true);
    addnav("","dracodiner.php?op=gestione&az=save&id=".$mount['id']);
    output("<table>",true);
    output("<tr><td>Categoria:</td><td><input name='mount[categoria]' value=\"".HTMLEntities2($mount['categoria'])."\"></td></tr>",true);
    output("<tr><td>Articolo:</td><td><input name='mount[articolo]' value=\"".HTMLEntities2($mount['articolo'])."\"></td></tr>",true);
    output("<tr><td>Nome:</td><td><input name='mount[nome]' value=\"".HTMLEntities2($mount['nome'])."\"></td></tr>",true);
    output("<tr><td>Costo:</td><td><input name='mount[costo]' value=\"".HTMLEntities2($mount['costo'])."\"></td></tr>",true);
    output("<tr><td>Livello Min:</td><td><input name='mount[liv_min]' value=\"".HTMLEntities2($mount['liv_min'])."\"></td></tr>",true);
    output("<tr><td>Livello Max:</td><td><input name='mount[liv_max]' value=\"".HTMLEntities2($mount['liv_max'])."\"></td></tr>",true);
    output("<tr><td>Fame:</td><td><input name='mount[fame]' value=\"".HTMLEntities2($mount['fame'])."\"></td></tr>",true);
    output("<tr><td>Sete:</td><td><input name='mount[sete]' value=\"".HTMLEntities2($mount['sete'])."\"></td></tr>",true);
    output("<tr><td>Liquido:</td><td><input name='mount[liquido]' value=\"".HTMLEntities2($mount['liquido'])."\"></td></tr>",true);
    //output("</td></tr>",true);
    output("</table>",true);
    output("<input type='submit' class='button' value='Salva'></form>",true);
    addnav("","dracodiner.php?op=gestione&az=save&id=".$mount['id']);
}
page_footer();
?>