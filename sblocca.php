<?php
require_once("common.php");
require_once("common2.php");
isnewday(2);

if ($_GET[op]=="search"){
    $sql = "SELECT acctid FROM accounts WHERE ";
    $where="
    login LIKE '%{$_POST['q']}%' OR
    acctid LIKE '%{$_POST['q']}%' OR
    name LIKE '%{$_POST['q']}%' OR
    emailaddress LIKE '%{$_POST['q']}%' OR
    lastip LIKE '%{$_POST['q']}%' OR
    uniqueid LIKE '%{$_POST['q']}%' OR
    gentimecount LIKE '%{$_POST['q']}%' OR
    level LIKE '%{$_POST['q']}%'";
    $result = db_query($sql.$where);
    if (db_num_rows($result)<=0){
        output("`\$No results found`0");
        $_GET[op]="";
        $where="";
    }elseif (db_num_rows($result)>100){
        output("`\$Troppi risultati, restringi il campo di ricerca per favore.`0");
        $_GET[op]="";
        $where="";
    }elseif (db_num_rows($result)==1){
        //$row = db_fetch_assoc($result);
        //redirect("sblocca.php?op=edit&userid=$row[acctid]");
        $_GET[op]="";
        $_GET['page']=0;
    }else{
        $_GET[op]="";
        $_GET['page']=0;
    }
}

page_header("Fissa Link Errato");
    output("<form action='sblocca.php?op=search' method='POST'>Cerca in ogni campo: <input name='q' id='q'><input type='submit' class='button'></form>",true);
    output("<script language='JavaScript'>document.getElementById('q').focus();</script>",true);
    addnav("","sblocca.php?op=search");
addnav("V?Torna al Villaggio","village.php");
$sql = "SELECT count(acctid) AS count FROM accounts";
$result = db_query($sql);
$row = db_fetch_assoc($result);
$page=0;
/*while ($row[count]>0){
    $page++;
    addnav("$page Pagina di $page","sblocca.php?page=".($page-1)."&sort=$_GET[sort]");
    $row[count]-=100;
}*/

$mounts=",0,Nessuno";
$sql = "SELECT mountid,mountname,mountcategory FROM mounts ORDER BY mountcategory";
$result = db_query($sql);
while ($row = db_fetch_assoc($result)){
    $mounts.=",{$row['mountid']},{$row['mountcategory']}: {$row['mountname']}";
}
$userinfo = array(
    "Informazioni Account,title",
    "acctid"=>"ID Utente,viewonly",
    "login"=>"Login,viewonly",
    //"newpassword"=>"Nuova Password",
    //"emailaddress"=>"Indirizzo Mail",
    //"locked"=>"Account Bloccato,bool",
    //"banoverride"=>"Supera Ban per questo account,bool",
    //"superuser"=>"Utente Super,enum,0,Giorni standard per giorno di calendario,1,Giorni illimitati per giorno di calendario,2,Admin (creature e sbeffeggi),3,Admin Totale",

    "Info Base Utente,title",
    "name"=>"Nome a Video,viewonly"
/*    "title"=>"Titolo (devi metterlo anche in Nome a Video)",
    "ctitle"=>"Titolo Personalizzato (devi metterlo anche in Nome a Video)",
    "sex"=>"Sesso,enum,0,Maschio,1,Femmina",
// we can't change this this way or their stats will be wrong.
//  "race"=>"Race,enum,0,Unknown,1,Troll,2,Elf,3,Human,4,Dwarf",
    "age"=>"Giorni dal livello 1,int",
    "dragonkills"=>"Quante volte ha ucciso il drago,int",
    "dragonage"=>"Quanto vecchio l'ultima uccisione drago,int",
    "bestdragonage"=>"Miglior risultato uccisione drago,int",
    "bio"=>"Biografia",
    "jail"=>"In prigione (0 = No - 1 = Si - 2 = Si (No sceriffo),int",
    "evil"=>"Cattiveria,int",
    "bankrobbed"=>"Numero Rapine,int",
    "robbank"=>"Banca derubata,int",
    "torneo"=>"Iscritto al Torneo,int",
    "torneopoints"=>"Livelli Torneo già fatti,viewonly",
    "medhunt"=>"Iscritto al Torneo delle Medaglie,int",
    "medpoints"=>"Medaglie Trovate,int",
    "medfind"=>"Medaglie per il giorno,int",
    "compleanno"=>"Data Compleanno (scrivi 2050-00-00 per bloccare),date",

    "Statistiche,title",
    "level"=>"Livello,int",
    "experience"=>"Esperienza,int",
    "hitpoints"=>"Hitpoints Attuali,int",
    "maxhitpoints"=>"Hitpoints Massimi,int",
    "turns"=>"Combattimenti rimanenti,int",
    "playerfights"=>"Turni PvP rimanenti,int",
    "attack"=>"Attacco (include danno dell'arma),int",
    "defence"=>"Difesa (include difesa armatura),int",
    "spirits"=>"Spirito (solo display),enum,-2,Molto Basso,-1,Basso,0,Normale,1,Alto,2,Molto Alto",
    "resurrections"=>"Resurrezioni,int",
    "quest"=>"Quest,int",
    "questcastle"=>"Quest x Castello Abbandonato,int",
    "labi"=>"Labirinto,int",
    "casa"=>"HouseID,int",
    "chiavi di casa"=>"Housekey,int",
    "reincarna"=>"Reincarnazioni,int",
    "bonusattack"=>"Bonus Attacco,int",
    "bonusdefence"=>"Bonus Difesa,int",
    "bonusfight"=>"Bonus Combattimenti,int",
    "oggetto"=>"Oggetto,int",
    "zaino"=>"Zaino,int",
    "dio"=>"Dio,int",
    "carriera"=>"Carriera,int",
    "punti_carriera"=>"Punti Carriera,int",
    "fama_mod"=>"Modificatore Fama,viewonly",
    "fama3mesi"=>"Punti Fama Trimestre,viewonly",
    "fama_anno"=>"Punti Fama Annuali,viewonly",

    "Specialità,title",
    "specialty"=>"Specialità,enum,0,Non Specificata,1,Arti Oscure,2,Poteri Mistici,3,Furto,4,Militare,5,Seduzione,
    6,Tattica,7,Pelle di Roccia,8,Retorica,9,Muscoli,10,Natura,11,Clima,12,Elementalista,13,Rabbia Barbara,14,Canzoni del Bardo",
    "darkarts"=>"`4Livello in Arti Oscure`0,int",
    "darkartuses"=>"`4^--Utilizzo odierno`0,int",
    "magic"=>"`%Livello in Poteri Mistici`0,int",
    "magicuses"=>"`%^--UUtilizzo odierno`0,int",
    "thievery"=>"`^Livello in Furto`0,int",
    "thieveryuses"=>"`^^--Utilizzo odierno`0,int",
    "militare"=>"`#Livello in Militare`0,int",
    "militareuses"=>"`#^--Utilizzo odierno`0,int",
    "mystic"=>"`\$Livello in Seduzione`0,int",
    "mysticuses"=>"`\$^--Utilizzo odierno`0,int",
    "tactic"=>"`^Livello in Tattica`0,int",
    "tacticuses"=>"`^^--Utilizzo odierno`0,int",
    "rockskin"=>"`@Livello in Pelle di Roccia`0,int",
    "rockskinuses"=>"`@^--Utilizzo odierno`0,int",
    "rhetoric"=>"`#Livello in Retorica`0,int",
    "rhetoricuses"=>"`#^--Utilizzo odierno`0,int",
    "muscle"=>"`%Livello in Muscoli`0,int",
    "muscleuses"=>"`%^--Utilizzo odierno`0,int",
    "nature"=>"`!Livello in Natura`0,int",
    "natureuses"=>"`!^--Utilizzo odierno`0,int",
    "weather"=>"`&Livello in Clima`0,int",
    "weatheruses"=>"`&^--Utilizzo odierno`0,int",
    //Excalibur: nuove specialità per donatori
    "elementale"=>"`^Livello in Elementale`0,int",
    "elementaleuses"=>"`^^--Utilizzo odierno`0,int",
    "barbaro"=>"`6Livello in Barbaro`0,int",
    "barbarouses"=>"`6^--Utilizzo odierno`0,int",
    "bardo"=>"`5Livello in Bardo`0,int",
    "bardouses"=>"`5^--Utilizzo odierno`0,int",
     //Excalibur: fine nuove specialità
    "Statistiche Cimitero,title",
    "deathpower"=>"Favori con Ramius,int",
    "gravefights"=>"Combattimenti Cimitero restanti,int",
    "soulpoints"=>"Punti Anima (Soulpoints),int",

    "Abbigliamento,title",
    "gems"=>"Gemme,int",
    "gold"=>"Oro in mano,int",
    "goldinbank"=>"Oro in banca,int",
    "transferredtoday"=>"Numero di trasferimenti odierni,int",
    "amountouttoday"=>"Valore totale trasferimenti dal player oggi,int",
    "weapon"=>"Nome Arma",
    "weapondmg"=>"Danno dell'arma,int",
    "weaponvalue"=>"Costo acquisto dell'arma,int",
    "armor"=>"Nome Armatura",
    "armordef"=>"Difesa dell'armatura,int",
    "armorvalue"=>"Costo acquisto dell'armatura,int",

    "Speciali,title",
    "marriedto"=>"Partner-ID (4294967295 = Violet/Seth),int",
    "charisma"=>"Flirts (4294967295 = sposato con il partner),int",
    "seenlover"=>"Visto amante,bool",
    "seenbard"=>"Ascoltato il bardo,bool",
    "gift"=>"Ricevuto Regalo alla Locanda?,bool",
    "charm"=>"Punti Fascino,int",
    "seendragon"=>"Visto il Drago oggi,bool",
    "seenmaster"=>"Visto il maestro oggi,bool",
    "usedouthouse"=>"Usato il cesso oggi,bool",
    "hashorse"=>"Animale,enum$mounts",
    "boughtroomtoday"=>"Affittata stanza oggi,bool",
    "drunkenness"=>"Ubriachezza (0-100),int",
    "bounty"=>"Taglia,int",
    "cavalcare_drago"=>"Cavalcare Draghi,int",
    "id_drago"=>"ID Drago,int",
    "turni_drago"=>"Turni Drago,int",
    "turni_drago_rimasti"=>"Turni Drago Rimasti,int",

    "Info Varie,title",
    "beta"=>"Desidera Partecipare fase BETA,viewonly",
    "slainby"=>"Ucciso da un Player,viewonly",
    "laston"=>"Ultimo Collegamento,viewonly",
    "lasthit"=>"Ultimo Nuovo Giorno,viewonly",
    "lastmotd"=>"Ultimo giorno MOTD,viewonly",
    "lastip"=>"Ultimo IP,viewonly",
    "uniqueid"=>"Unico ID,viewonly",
    "gentime"=>"Somma dei tempi di generazione pagine,viewonly",
    "gentimecount"=>"Page hits,viewonly",
    "allowednavs"=>"Navigazioni Consentite,viewonly",
    "dragonpoints"=>"Punti Drago spesi,viewonly",
    "bufflist"=>"Lista Abilità Speciali,viewonly",
    "prefs"=>"Preferenze,viewonly",
    //"lastwebvote"=>"Last time voted at Top Web Games,viewonly",
    //"donationconfig"=>"Donation buys,viewonly"
*/
    );

if ($_GET['op']=="lasthit"){
    $output="";
    $sql = "SELECT output FROM accounts WHERE acctid='{$_GET['userid']}'";
    $result = db_query($sql);
    $row = db_fetch_assoc($result);
    echo str_replace("<iframe src=","<iframe Xsrc=",$row['output']);
    exit();
}elseif ($_GET['op']=="edit"){
    $result = db_query("SELECT * FROM accounts WHERE acctid='$_GET[userid]'") or die(db_error(LINK));
    $row = db_fetch_assoc($result) or die(db_error(LINK));
    output("<form action='sblocca.php?op=special&userid=$_GET[userid]".($_GET['returnpetition']!=""?"&returnpetition={$_GET['returnpetition']}":"")."' method='POST'>",true);
    addnav("","sblocca.php?op=special&userid=$_GET[userid]".($_GET['returnpetition']!=""?"&returnpetition={$_GET['returnpetition']}":"")."");
    output("<input type='submit' class='button' name='fixnavs' value='Fissa Link Errato'>",true);
    output("</form>",true);

    if ($_GET['returnpetition']!=""){
        addnav("Torna alle Petizioni","viewpetition.php?op=view&id={$_GET['returnpetition']}");
    }

    addnav("Guarda ultima pagina vista","sblocca.php?op=lasthit&userid={$_GET['userid']}",false,true);
    output("<form action='sblocca.php?op=save&userid=$_GET[userid]".($_GET['returnpetition']!=""?"&returnpetition={$_GET['returnpetition']}":"")."' method='POST'>",true);
    addnav("","sblocca.php?op=save&userid=$_GET[userid]".($_GET['returnpetition']!=""?"&returnpetition={$_GET['returnpetition']}":"")."");
    addnav("","sblocca.php?op=edit&userid=$_GET[userid]".($_GET['returnpetition']!=""?"&returnpetition={$_GET['returnpetition']}":"")."");
    //addnav("Aggiungi Ban","sblocca.php?op=setupban&userid=$row[acctid]");
    //addnav("Mostra Log","sblocca.php?op=debuglog&userid={$_GET['userid']}");
    //output("<input type='submit' class='button' value='Salva'>",true);
    showform($userinfo,$row,true);
    output("</form>",true);
    output("<iframe src='sblocca.php?op=lasthit&userid={$_GET['userid']}' width='100%' height='400'>Necessiti iframes per vedere l'ultima pagina navigata dal player.  Usa il link nella nav invece.</iframe>",true);
    addnav("","sblocca.php?op=lasthit&userid={$_GET['userid']}");
}elseif ($_GET[op]=="special"){
    if($_POST[fixnavs]!=""){
        $sql = "UPDATE accounts SET allowednavs='',output=\"\" WHERE acctid='$_GET[userid]'";
        debuglog("`0sblocca la navigazione a ",$_GET['userid']);
    }
    db_query($sql);
    if ($_GET['returnpetition']==""){
        redirect("sblocca.php?".db_affected_rows());
    }else{
        redirect("viewpetition.php?op=view&id={$_GET['returnpetition']}");
    }
}elseif ($_GET[op]==""){
    if (isset($_GET['page'])){
        $order = "acctid";
        if ($_GET[sort]!="") $order = "$_GET[sort]";
        $offset=(int)$_GET['page']*100;
        $sql = "SELECT acctid,login,name FROM accounts ".($where>""?"WHERE $where ":"")."ORDER BY \"$order\" LIMIT $offset,100";
        $result = db_query($sql) or die(db_error(LINK));
        output("<table>",true);
        output("<tr>
        <td>Ops</td>
        <td>Login</td>
        <td>Nome</td>
        </tr>",true);
        $rn=0;
        $countrow = db_num_rows($result);
        for ($i=0; $i<$countrow; $i++){
        //for ($i=0;$i<db_num_rows($result);$i++){
            $row=db_fetch_assoc($result);
            if ($row[$order]!=$oorder) $rn++;
            $oorder = $row[$order];
            output("<tr class='".($rn%2?"trlight":"trdark")."'>",true);

            output("<td>",true);
            output("[<a href='sblocca.php?op=edit&userid=$row[acctid]'>Edita</a>|",true);
            addnav("","sblocca.php?op=edit&userid=$row[acctid]");
            output("</td><td>",true);
            output($row[login]);
            output("</td><td>",true);
            output($row[name]."</td></tr>",true);
        }
        output("</table>",true);
    }
}
page_footer();
?>