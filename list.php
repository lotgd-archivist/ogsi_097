<?php
require_once "common.php";
/*
$razzza=array(1=>"`2Troll",
              2=>"`^Elfi",
              3=>"`&Umani",
              4=>"`#Nani",
              5=>"`3Druidi",
              6=>"`@Goblin",
              7=>"`%Orchi",
              8=>"`\$Vampiri",
              9=>"`5Lich",
              10=>"`&Fanciulla delle Nevi",
              11=>"`4Oni",
              12=>"`3Satiro",
              13=>"`#Gigante delle Tempeste",
              14=>"`\$Barbaro",
              15=>"`%Amazzone",
              16=>"`^Titano",
              17=>"`\$Demone",
              18=>"`(Centauro",
              19=>"`8Licantropo",
              20=>"`)Minotauro",
              21=>"`^Cantastorie",
              0=>"Sconosciuto",
              50=>"Pecora Volante",
              60=>"Tester",
              80=>"`%Moderatore",
              100=>"`\$Admin",
              127=>"`SDivinità"
              );
*/
$locazione=array(100=>"Castello Abbandonato",
                 101=>"Accademia",
                 102=>"Farmacia di Adriana",
                 103=>"Agenzia Matrimoniale",
                 104=>"Allenatore di Draghi",
                 105=>"Arena",
                 106=>"Pegasus",
                 107=>"Banca del Borgo",
                 108=>"Banca di Rafflingate",
                 109=>"Docce Pubbliche",
                 110=>"Tavolo del BlackJack",
                 111=>"Bosco a Sud",
                 112=>"Casa del Piacere",
                 113=>"Caserma",
                 114=>"Castel Excalibur",
                 115=>"Dag Durnick",
                 116=>"Madame Déguise",
                 117=>"Tavola Calda del Drago",
                 118=>"Drago Verde",
                 119=>"Giardino Incantato",
                 120=>"Eros Esotico",
                 121=>"Lotto di Eric",
                 122=>"Esplorazione",
                 123=>"Oberon",
                 124=>"Fagioli di Cedrik",
                 125=>"Falegnameria",
                 126=>"Fanciulla",
                 127=>"Caminetto",
                 128=>"Cassandra",
                 129=>"Foresta",
                 130=>"Bosco Oscuro",
                 131=>"Laghetto",
                 132=>"Giardini",
                 133=>"Gilda del Drago",
                 134=>"Gnomo delle Gemme",
                 135=>"Cimitero",
                 136=>"Accampamento Zingari",
                 137=>"Golinda",
                 138=>"Guaritore",
                 139=>"Casa della Strega",
                 140=>"Sala della Gloria",
                 141=>"Ippodromo",
                 142=>"Javella",
                 143=>"Grotta di Karnak",
                 144=>"Biblioteca di Plato",
                 145=>"Capanno di Caccia",
                 146=>"Torre dei Maghi",
                 147=>"Manicomio",
                 148=>"Maniero Burocratico",
                 149=>"Torneo Medaglie",
                 150=>"Mercante Zukron",
                 151=>"Mercante Draghi",
                 152=>"Gilda Mercenari",
                 153=>"Miniera",
                 154=>"Monastero",
                 155=>"Municipio",
                 156=>"Myrrdin",
                 157=>"Oroscopo",
                 158=>"Osservatorio",
                 159=>"Toilette Privata",
                 160=>"Toilette Pubblica",
                 161=>"PvP",
                 162=>"Arena PvP",
                 163=>"sezione HELP",
                 164=>"Rapina Banca",
                 165=>"Gioco RSP",
                 166=>"Roulette",
                 167=>"Luna Park",
                 168=>"Salamenteria di Lulù",
                 169=>"Fuga dalla Prigione",
                 170=>"Scuderie Ippodromo",
                 171=>"Selva Oscura",
                 172=>"Sgarro",
                 173=>"Terra delle Ombre",
                 174=>"Le Fattorie",
                 175=>"Spada nella Roccia",
                 176=>"Hatetepe",
                 177=>"Stalle di Merick",
                 178=>"Gioco delle Pietre",
                 179=>"Casa della Strega",
                 180=>"Palestra di Swarzy",
                 181=>"Terre dei Draghi",
                 182=>"Torneo di LoGD",
                 183=>"Torre Nera",
                 184=>"Campo di Allenamento",
                 185=>"Tunnel degli Inferi",
                 186=>"Negozio di Vessa",
                 187=>"Piazza del Villaggio",
                 188=>"Piazza del Borgo",
                 189=>"Negozio di Virna",
                 190=>"Sacerdotessa Voodoo",
                 191=>"Mighthy",
                 192=>"Gestione Zaino",
                 193=>"Locanda",
                 194=>"Tenuta",
                 195=>"Chiesa di Sgrios",
                 196=>"Piazza GDR del Villaggio",
                 197=>"Dimora degli Dei",
                 198=>"Prigione",
                 199=>"Scuola di Rafflingate",
                 200=>"Chiesa di Sgrios",
                 201=>"Grotta di Karnak",
                 202=>"Drago Verde",
                 203=>"Stanze GDR"
);
if ($session['return']==""){
   $session['return']=$_GET['ret'];
}
if ($session['user']['loggedin']) {
    checkday();
    if ($session['user']['alive']) {
        if ($session['return']==""){
           addnav("T?`@Torna al Villaggio","village.php");
        }else{
            $return = preg_replace("'[&?]c=[[:digit:]-]+'","",$session['return']);
            $return = substr($return,strrpos($return,"/")+1);
            addnav("Torna da dove sei venuto",$return);
        }
    } else {
        addnav("T?`2Torna al Cimitero", "graveyard.php");
    }
    addnav("Collegati al momento","list.php");
    addnav("Elenchi per stat","list.php?op=stat");
    if ($session['user']['superuser']>2) addnav("N?`&Gli Utenti Nascosti","list.php?op=stealth&stat=1");

}else{
    addnav("Pagina Iniziale","index.php");
    addnav("Collegati al momento","list.php");
}
page_header("Elenco Abitanti");
 if($session['user']['euro']<1)output('<center>
 <script type="text/javascript"><!--
google_ad_client = "pub-8533296456863947";
google_ad_width = 468;
google_ad_height = 60;
google_ad_format = "468x60_as";
google_ad_type = "text_image";
//2007-03-22: Logd_468x60_list
google_ad_channel = "7878417336";
google_color_border = "6F3C1B";
google_color_bg = "78B749";
google_color_link = "6F3C1B";
google_color_text = "063E3F";
google_color_url = "000000";
//-->
</script>
<script type="text/javascript"
  src="http://pagead2.googlesyndication.com/pagead/show_ads.js">
</script>
 </center>',true);
$playersperpage=50;

if ($session['user']['superuser']<3) {
    $sql = "SELECT count(acctid) AS c FROM accounts WHERE locked=0 AND stealth=0";
} else {
    $sql = "SELECT count(acctid) AS c FROM accounts WHERE locked=0";
}
$result = db_query($sql);
$row = db_fetch_assoc($result);
$totalplayers = $row['c'];

if ($_GET['op']=="search"){
     if($_POST['name']!="") {
          $search="%".$_POST['name']."%";
         /*Excalibur: modifica per trovare correttamente i nomi dei player
         $search="%";
         for ($x=0;$x<strlen($_POST['name']);$x++){
             $search .= substr($_POST['name'],$x,1)."%";
         } */
          $search=" AND name LIKE '".addslashes($search)."' ";
    }
    else {
          output("`4Non hai inserito un campo di ricerca");
    }
    //addnav("List Warriors","list.php");
}else{
    $pageoffset = (int)$_GET['page'];
    if ($pageoffset>0) $pageoffset--;
    $pageoffset*=$playersperpage;
    $from = $pageoffset+1;
    $to = min($pageoffset+$playersperpage,$totalplayers);

    $limit=" LIMIT $pageoffset,$playersperpage ";
}
addnav("Pagine");
for ($i=0;$i<$totalplayers;$i+=$playersperpage){
    addnav("Pagina ".($i/$playersperpage+1)." (".($i+1)."-".min($i+$playersperpage,$totalplayers).")","list.php?page=".($i/$playersperpage+1));
}

// Order the list by level, dragonkills, name so that the ordering is total!
// Without this, some users would show up on multiple pages and some users
// wouldn't show up
if ($_GET['page']=="" && $_GET['op']=="" && $_GET['stat']==""){
    output("`c`bGuerrieri Collegati al Momento`b`c");
    if ($session['user']['superuser']<3) {
        $sql = "SELECT name,login,alive,location,locazione,sex,level,jail,laston,loggedin,lastip,uniqueid,acctid,reincarna,dragonkills,race,dio,carriera FROM accounts WHERE locked=0 AND loggedin=1 AND stealth=0 AND laston>'".date("Y-m-d H:i:s",strtotime(date("r")."-".getsetting("LOGINTIMEOUT",900)." seconds"))."' ORDER BY level DESC, dragonkills DESC, login ASC";
    } else {
        $sql = "SELECT name,login,alive,location,locazione,sex,level,jail,laston,loggedin,lastip,uniqueid,acctid,reincarna,dragonkills,race,dio,carriera FROM accounts WHERE locked=0 AND loggedin=1 AND laston>'".date("Y-m-d H:i:s",strtotime(date("r")."-".getsetting("LOGINTIMEOUT",900)." seconds"))."' ORDER BY level DESC, dragonkills DESC, login ASC";
    }
}else{
    output("`c`bGuerrieri del reame (Pagina ".($pageoffset/$playersperpage+1).": $from-$to of $totalplayers)`b`c");
    if ($session['user']['superuser']<3) {
        $sql = "SELECT name,login,alive,location,locazione,sex,level,jail,laston,loggedin,lastip,uniqueid,acctid,reincarna,dragonkills,race,dio,carriera FROM accounts WHERE locked=0 AND stealth=0 $search ORDER BY level DESC, dragonkills DESC, login ASC $limit";
    } else {
        $sql = "SELECT name,login,alive,location,locazione,sex,level,jail,laston,loggedin,lastip,uniqueid,acctid,reincarna,dragonkills,race,dio,carriera FROM accounts WHERE locked=0 $search ORDER BY level DESC, dragonkills DESC, login ASC $limit";
    }
}
if ($session['user']['loggedin']){
    output("<form action='list.php?op=search' method='POST'>Cerca per nome: <input name='name'><input type='submit' class='button' value='Cerca'></form>",true);
    addnav("","list.php?op=search");
}

$result = db_query($sql) or die(sql_error($sql));
$max = db_num_rows($result);
if ($max>100) {
    output("`\$Troppi nomi corrispondono a questa ricerca.  Elenco solo i primi 100.`0`n");
}
if ($_GET['stat']=="" && ($_GET['op']!="stat" OR $_GET['op']!="stealth")){
output("<table border=0 cellpadding=2 cellspacing=1 align=center bgcolor='#999999'>",true);
output("<tr class='trhead'><td><b>Vivo</b></td><td><b>Reinc.</b></td><td><b>DK</b></td><td><b>Level</b></td><td><b>Nome</b></td><td><b>Razza</b></td><td><b>Fede</b></td><td><b>Carriera</b></td><td><b>Posizione</b></td><td><b><img src=\"images/female.gif\">/<img src=\"images/male.gif\"></b></td><td><b>Ult.Colleg.</b></tr>",true);
for($i=0;$i<$max;$i++){
    $row = db_fetch_assoc($result);
    output("<tr class='".($i%2?"trdark":"trlight")."'><td>",true);
    output($row['alive']?"`1Si`0":"`4No`0");
    output("</td><td>",true);
    //output($row[reincarna]?"`1Si`0":"`4No`0");
    if (!$row['reincarna']) {
       output("`4No`0");
    }else {
          output("`!{$row[reincarna]}");
    }
    output("</td><td>",true);
    output("`#$row[dragonkills]");
    output("</td><td>",true);
    output("`^$row[level]`0");
    output("</td><td>",true);
    if ($session['user']['loggedin']) output("<a href=\"mail.php?op=write&to=".rawurlencode($row['login'])."\" target=\"_blank\" onClick=\"".popup("mail.php?op=write&to=".rawurlencode($row['login'])."").";return false;\"><img src='images/newscroll.GIF' width='16' height='16' alt='Write Mail' border='0'></a>",true);
    if ($session['user']['loggedin']) output("<a href='bio.php?char=".rawurlencode($row['login'])."'>",true);
    if ($session['user']['loggedin']) addnav("","bio.php?char=".rawurlencode($row['login'])."");
    output("`".($row['acctid']==getsetting("hasegg",0)?"^":"&")."$row[name]`0");
    if ($session['user']['loggedin']) output("</a>",true);
    output("</td><td>",true);
    output($races[$row['race']]);
    output("</td><td>",true);
    output($fededio[$row['dio']]);
    output("</td><td>",true);
    output($prof[$row['carriera']]);
    output("</td><td>",true);
    $loggedin=(date("U") - strtotime($row['laston']) < getsetting("LOGINTIMEOUT",900) && $row['loggedin']);
    $loca = $row['locazione'];
    //luke inizio
    if ($row['jail']>0){
    output("`\$In prigione`0");
    }elseif ($row['location']==11) {
        $sqldraghi="SELECT nome FROM terre_draghi WHERE id_player ='".$row['acctid']."'";
        $resultdraghi = db_query($sqldraghi) or die(sql_error($sqldraghi));
        $rowdraghi = db_fetch_assoc($resultdraghi);
        output ("`\$".$rowdraghi['nome']."`0");
    }elseif ($row['location']==3) {
        output ("`@Nel Dormitorio`0");
    }elseif ($row['location']==2) {
        output ("`6In una Casa`0");
    }elseif ($row['location']==1) {
        output("`%Locanda da Cedrik`0");
    }elseif ($row['locazione']!=0 AND $loggedin==1) {
        output("`#".$locazione[$loca]."`0");
    }elseif ($row['location']==0) {
        output($loggedin?"`#Collegato`0":"`3Nei Campi`0");
    }
    // output($row[location]?"`3Boar's Head Inn`0"  :($loggedin ?"`#Online`0":"`3The Fields`0"));
    output("</td><td>",true);
    /*if($row[sex]==1) {
        output("<span style='color:#FF8888'>",true);
        }
    else {
        output("<span style='color:#5555FF'>",true);
        }
    output($row[sex]?"Femmina`0":"Maschio`0");
    */
    output($row[sex]?"<img src=\"images/female.gif\">":"<img src=\"images/male.gif\">",true);
    output("</td><td>",true);
    $laston=round((strtotime(date("r"))-strtotime($row['laston'])) / 86400,0)." giorni";
    if (substr($laston,0,2)=="1 ") $laston="1 giorno";
    if (date("Y-m-d",strtotime($row['laston'])) == date("Y-m-d")) $laston="Oggi";
    if (date("Y-m-d",strtotime($row['laston'])) == date("Y-m-d",strtotime(date("r")."-1 day"))) $laston="Ieri";
    if ($loggedin) $laston="Adesso";
    output($laston);
    output("</td></tr>",true);
}
output("</table>",true);
}
if ($_GET['op']=="stat" AND $_GET['stat']==""){
        addnav("Liste per Razza");
        addnav("(1)`2Troll","list.php?op=stat&stat=1");
        addnav("(2)`^Elfi","list.php?op=stat&stat=2");
        addnav("(3)`&Umani","list.php?op=stat&stat=3");
        addnav("(4)`#Nani","list.php?op=stat&stat=4");
        addnav("(5)`3Druidi","list.php?op=stat&stat=5");
        addnav("(6)`@Goblin","list.php?op=stat&stat=6");
        addnav("(7)`%Orchi","list.php?op=stat&stat=7");
        addnav("(8)`\$Vampiri","list.php?op=stat&stat=8");
        addnav("(9)`%Lich","list.php?op=stat&stat=9");
        addnav("(10)`&Fanciulle delle Nevi","list.php?op=stat&stat=10");
        addnav("(11)`%Oni","list.php?op=stat&stat=11");
        addnav("(12)`2Satiri","list.php?op=stat&stat=12");
        addnav("A?`!Altro","list.php?op=stat&stat=altro");
        //addnav("T?`@Torna al Villaggio","village.php");
        page_header("Elenco per Razza");
}
if ($_GET['op']=="stat" AND $_GET['stat']!=""){
    page_header("Elenco per Razza");
    if ($_GET['stat']>0 AND $_GET['stat']<13){
        $raza=$_GET['stat'];
        $sql = "SELECT name,login,alive,location,sex,level,jail,laston,loggedin,lastip,uniqueid,acctid,reincarna,dragonkills,race,carriera FROM accounts WHERE locked=0 AND stealth=0 AND race=$raza ORDER BY reincarna DESC, dragonkills DESC, level DESC";
        $result = db_query($sql) or die(sql_error($sql));
        $max = db_num_rows($result);
    }else {
        $sql = "SELECT name,login,alive,location,sex,level,jail,laston,loggedin,lastip,uniqueid,acctid,reincarna,dragonkills,race,carriera FROM accounts WHERE locked=0 AND stealth=0 AND (race>12 OR race<1) ORDER BY reincarna DESC, dragonkills DESC, level DESC";
        $result = db_query($sql) or die(sql_error($sql));
        $max = db_num_rows($result);
        }
    output("<table border=0 cellpadding=2 cellspacing=1 align=center bgcolor='#999999'>",true);
    output("<tr class='trhead'><td><b>Vivo</b></td><td><b>Reinc.</b></td><td><b>DK</b></td><td><b>Level</b></td><td><b>Nome</b></td><td><b>Razza</b></td><td><b>Carriera</b></td><td><b>Posizione</b></td><td><b><img src=\"images/female.gif\">/<img src=\"images/male.gif\"></b></td><td><b>Ult.Colleg.</b></tr>",true);
    if ($max==0) output("<tr class='trdark'><font size='+1'>`4`cNon ci sono {$razzza[$_GET['stat']]} `4in questo villaggio`c</font></tr>",true);
    for($i=0;$i<$max;$i++){
        $row = db_fetch_assoc($result);
        output("<tr class='".($i%2?"trdark":"trlight")."'><td>",true);
        output($row[alive]?"`1Si`0":"`4No`0");
        output("</td><td>",true);
        if (!$row['reincarna']) {
        output("`4No`0");
        }else {
          output("`!".$row['reincarna']."");
        }
        output("</td><td>",true);
        output("`#".$row['dragonkills']);
        output("</td><td>",true);
        output("`^".$row['level']."`0");
        output("</td><td>",true);
        if ($session['user']['loggedin']) output("<a href=\"mail.php?op=write&to=".rawurlencode($row['login'])."\" target=\"_blank\" onClick=\"".popup("mail.php?op=write&to=".rawurlencode($row['login'])."").";return false;\"><img src='images/newscroll.GIF' width='16' height='16' alt='Write Mail' border='0'></a>",true);
        if ($session['user']['loggedin']) output("<a href='bio.php?char=".rawurlencode($row['login'])."'>",true);
        if ($session['user']['loggedin']) addnav("","bio.php?char=".rawurlencode($row['login'])."");
        output("`".($row['acctid']==getsetting("hasegg",0)?"^":"&")."$row[name]`0");
        if ($session['user']['loggedin']) output("</a>",true);
        output("</td><td>",true);
        output("{$races[$row[race]]}");
        output("</td><td>",true);
        output("{$prof[$row[carriera]]}");
        output("</td><td>",true);
        $loggedin=(date("U") - strtotime($row['laston']) < getsetting("LOGINTIMEOUT",900) && $row['loggedin']);
        //luke inizio
        if ($row['jail']==1){
            output("`\$In prigione`0");
        }else if ($row['location']==11) {
            $sqldraghi="SELECT nome FROM terre_draghi WHERE id_player ='".$row['acctid']."'";
            $resultdraghi = db_query($sqldraghi) or die(sql_error($sqldraghi));
            $rowdraghi = db_fetch_assoc($resultdraghi);
            output ("`\$".$rowdraghi['nome']."`0");
        }else if ($row['location']==3) {
            output ("`@Nel Dormitorio`0");
        }else if ($row['location']==2) {
            output ("`6In una Casa`0");
        }else if ($row['location']==1) {
            output("`%Locanda da Cedrik`0");
        } else if ($row['location']==0) {
            output($loggedin?"`#Collegato`0":"`3Nei Campi`0");
        }
        output("</td><td>",true);
        output($row['sex']?"<img src=\"images/female.gif\">":"<img src=\"images/male.gif\">",true);
        output("</td><td>",true);
        $laston=round((strtotime(date("r"))-strtotime($row['laston'])) / 86400,0)." giorni";
        if (substr($laston,0,2)=="1 ") $laston="1 giorno";
        if (date("Y-m-d",strtotime($row['laston'])) == date("Y-m-d")) $laston="Oggi";
        if (date("Y-m-d",strtotime($row['laston'])) == date("Y-m-d",strtotime(date("r")."-1 day"))) $laston="Ieri";
        if ($loggedin) $laston="Adesso";
        output($laston);
        output("</td></tr>",true);
    }
    output("</table>",true);
}
//else if ($_GET['op']=="stat" AND $_GET['stat']=="altro" AND $_GET['stat1']==""){
//  addnav("Ordina per:");
//  addnav
//  }
if ($_GET['op']=="stealth"){
    page_header("Elenco per Razza");
    $sql = "SELECT name,login,alive,sex,level,jail,laston,loggedin,lastip,uniqueid,acctid,reincarna,dragonkills,race,carriera,superuser FROM accounts WHERE locked=0 AND stealth=1 ORDER BY reincarna DESC, dragonkills DESC, level DESC";
    $result = db_query($sql) or die(sql_error($sql));
    $max = db_num_rows($result);
    output("<table border=0 cellpadding=2 cellspacing=1 align=center bgcolor='#999999'>",true);
    output("<tr class='trhead'><td><b>Vivo</b></td><td><b>Reinc.</b></td><td><b>DK</b></td><td><b>Level</b></td><td><b>Nome</b></td><td><b>Livello Superuser</b></td><td><b>Razza</b></td><td><b>Carriera</b></td><td><b><img src=\"images/female.gif\">/<img src=\"images/male.gif\"></b></td><td><b>Ult.Colleg.</b></tr>",true);
    if ($max==0) output("<tr class='trdark'><font size='+1'>`4`cNon ci sono utenti invisibili in questo villaggio`c</font></tr>",true);
    for($i=0;$i<$max;$i++){
        $row = db_fetch_assoc($result);
        output("<tr class='".($i%2?"trdark":"trlight")."'><td>",true);
        output($row['alive']?"`1Si`0":"`4No`0");
        output("</td><td>",true);
        if (!$row['reincarna']) {
        output("`4No`0");
        }else {
          output("`!".$row['reincarna']);
        }
        output("</td><td>",true);
        output("`#".$row['dragonkills']);
        output("</td><td>",true);
        output("`^".$row['level']."`0");
        output("</td><td>",true);
        if ($session['user']['loggedin']) output("<a href=\"mail.php?op=write&to=".rawurlencode($row['login'])."\" target=\"_blank\" onClick=\"".popup("mail.php?op=write&to=".rawurlencode($row['login'])."").";return false;\"><img src='images/newscroll.GIF' width='16' height='16' alt='Write Mail' border='0'></a>",true);
        if ($session['user']['loggedin']) output("<a href='bio.php?char=".rawurlencode($row['login'])."'>",true);
        if ($session['user']['loggedin']) addnav("","bio.php?char=".rawurlencode($row['login'])."");
        output("`".($row['acctid']==getsetting("hasegg",0)?"^":"&").$row['name']."`0");
        if ($session['user']['loggedin']) output("</a>",true);
        output("</td><td align='center'>",true);
        output("`b`(".$row['superuser']."`b`0");
        output("</td><td>",true);
        output("{$races[$row[race]]}");
        output("</td><td>",true);
        output("{$prof[$row[carriera]]}");
        output("</td><td>",true);
        $loggedin=(date("U") - strtotime($row['laston']) < getsetting("LOGINTIMEOUT",900) && $row['loggedin']);
        output($row[sex]?"<img src=\"images/female.gif\">":"<img src=\"images/male.gif\">",true);
        output("</td><td>",true);
        $laston=round((strtotime(date("r"))-strtotime($row['laston'])) / 86400,0)." giorni";
        if (substr($laston,0,2)=="1 ") $laston="1 giorno";
        if (date("Y-m-d",strtotime($row['laston'])) == date("Y-m-d")) $laston="Oggi";
        if (date("Y-m-d",strtotime($row['laston'])) == date("Y-m-d",strtotime(date("r")."-1 day"))) $laston="Ieri";
        if ($loggedin) $laston="Adesso";
        output($laston);
        output("</td></tr>",true);
    }
    output("</table>",true);
}
page_footer();
?>