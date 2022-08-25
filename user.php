<?php
require_once("common.php");
require_once("common2.php");
isnewday(3);

if ($_GET[op]=="search"){
    $sql = "SELECT acctid FROM accounts WHERE ";
    $where="
    login LIKE \"%{$_POST['q']}%\" OR
    acctid LIKE \"%{$_POST['q']}%\" OR
    name LIKE \"%{$_POST['q']}%\" OR
    emailaddress LIKE \"%{$_POST['q']}%\" OR
    lastip LIKE \"%{$_POST['q']}%\" OR
    uniqueid LIKE \"%{$_POST['q']}%\" OR
    gentimecount LIKE \"%{$_POST['q']}%\" OR
    level LIKE \"%{$_POST['q']}%\"";
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
        //redirect("user.php?op=edit&userid=$row[acctid]");
        $_GET[op]="";
        $_GET['page']=0;
    }else{
        $_GET[op]="";
        $_GET['page']=0;
    }
}

page_header("Editor Utenti");
    output("<form action='user.php?op=search' method='POST'>Cerca in ogni campo: <input name='q' id='q'><input type='submit' class='button'></form>",true);
    output("<script language='JavaScript'>document.getElementById('q').focus();</script>",true);
    addnav("","user.php?op=search");
addnav("G?Torna alla Grotta","superuser.php");
addnav("M?Torna alla Mondanità","village.php");
addnav("Aggiungi un ban","user.php?op=setupban");
addnav("Elenca/Rimuovi ban","user.php?op=removeban");
addnav("S?Elenca Personaggi Silenziati","user.php?op=silenced");
addnav("R?Ripristina Personaggio","user.php?op=restore");
//addnav("User Editor Home","user.php");
$sql = "SELECT count(acctid) AS count FROM accounts";
$result = db_query($sql);
$row = db_fetch_assoc($result);
$page=0;
while ($row[count]>0){
    $page++;
    addnav("$page Pagina di $page","user.php?page=".($page-1)."&sort=$_GET[sort]");
    $row[count]-=100;
}

$mounts=",0,Nessuno";
$sql = "SELECT mountid,mountname,mountcategory FROM mounts ORDER BY mountcategory";
$result = db_query($sql);
while ($row = db_fetch_assoc($result)){
    $mounts.=",{$row['mountid']},".html_entity_decode(HTMLEntities2(preg_replace("'[`].'","",($row['mountcategory'])))).": ".html_entity_decode(HTMLEntities2(preg_replace("'[`].'","",($row['mountname']))));
}

$fede1="";
$fede2=array_keys($fededio);
$fede3=array_values($fededio);
for($ii=0;$ii<count($fede2);$ii++) {
    $fede1.=",$fede2[$ii],".html_entity_decode(HTMLEntities2(preg_replace("'[`].'","",$fede3[$ii])));
}

$prof1="";
$prof2=array_keys($prof);
$prof3=array_values($prof);
for($ii=0;$ii<count($prof2);$ii++) {
    $prof1.=",$prof2[$ii],".html_entity_decode(HTMLEntities2(preg_replace("'[`].'","",$prof3[$ii])));
}

$races1="";
$races2=array_keys($races);
$races3=array_values($races);
for($ii=0;$ii<count($races2);$ii++) {
    $races1.=",$races2[$ii],".html_entity_decode(HTMLEntities2(preg_replace("'[`].'","",$races3[$ii])));
}


if ($session['user']['superuser'] < 4){
$userinfo = array(
    "Informazioni Account,title",
    "acctid"=>"ID Utente,viewonly",
    "login"=>"Login",
    "newpassword"=>"Nuova Password",
    "emailaddress"=>"Indirizzo Mail",
    "locked"=>"Account Bloccato,bool",
    "consono"=>"Nick Consono,enum,2,Sì,1,No,0,Da Verificare",
    "nocomment"=>"Giorni di Mute,int",
    "banoverride"=>"Supera Ban per questo account,bool",
    "superuser"=>"Utente Super,enum,0,Giorni standard per giorno di calendario,1,Giorni illimitati per giorno di calendario,2,Moderatore (creature e sbeffeggi),3,Admin Totale",
    "loggedin"=>"Connesso,viewonly",
    "badguy"=>"Avversario in combattimento,viewonly",

    "Info Base Utente,title",
    "name"=>"Nome a Video",
    "title"=>"Titolo (devi metterlo anche in Nome a Video)",
    "ctitle"=>"Titolo Personalizzato (devi metterlo anche in Nome a Video)",
    "sex"=>"Sesso,enum,0,Maschio,1,Femmina",
// we can't change this this way or their stats will be wrong.
//  "race"=>"Race,enum,0,Unknown,1,Troll,2,Elf,3,Human,4,Dwarf",
    "age"=>"Giorni dal livello 1,int",
    "dragonkills"=>"Quante volte ha ucciso il drago,int",
    "dragonage"=>"Quanto vecchio l'ultima uccisione drago,int",
    "bestdragonage"=>"Miglior risultato uccisione drago,int",
    "jail"=>"In prigione,enum,0,No,1,Sì (opzioni sceriffo),2,Sì (No opzioni sceriffo)",
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
    "npg"=>"Player Non Giocante (NPG),enum,0,Falso,1,Vero",
    "gdr"=>"Piazza GDR,enum,no,Falso,si,Vero",
    "level"=>"Livello,int",
    "experience"=>"Esperienza,int",
    "hitpoints"=>"Hitpoints Attuali,int",
    "maxhitpoints"=>"Hitpoints Massimi,int",
    "turns"=>"Combattimenti rimanenti,int",
    "turni_mestiere"=>"Turni Mestiere,int",
    "playerfights"=>"Turni PvP rimanenti,int",
    "pk"=>"Immunità PVP (solo nei primi giornì),enum,0,Sì,1,No",
    "attack"=>"Attacco (include danno dell'arma),int",
    "defence"=>"Difesa (include difesa armatura),int",
    "spirits"=>"Spirito (solo display),enum,-2,Molto Basso,-1,Basso,0,Normale,1,Alto,2,Molto Alto",
    "resurrections"=>"Resurrezioni,int",
    "agedrago"=>"Giorni dall'ultimo Drago Verde ucciso, int",
    "quest"=>"Quest,int",
    "questcastle"=>"Quest x Castello Abbandonato,int",
    "labi"=>"Labirinto,int",
    "casa"=>"HouseID,int",
    "house"=>"Proprietario della Tenuta,int",
    "housekey"=>"Chiave di casa (deve essere uguale alla voce Proprietario della Tenuta),int",
    "reincarna"=>"Reincarnazioni,int",
    "bonusattack"=>"Bonus Attacco,int",
    "bonusdefence"=>"Bonus Difesa,int",
    "bonusfight"=>"Bonus Combattimenti,int",
    "oggetto"=>"Oggetto,int",
    "zaino"=>"Zaino,int",
    "messa"=>"Preso Messa,enum,0,No,1,Si",
//    "dio"=>"Dio,enum,0,Agnostico,1,Sgrios,2,Karnak,3,Drago Verde,4,Natura,5,Eretico",
    "dio"=>"Dio,enum$fede1",
    "carriera"=>"Carriera,enum$prof1",
/*    "carriera"=>"Carriera,enum,0,Nessuna,1,
Seguace (Sgrios),2,Accolito (Sgrios),3,Chierico (Sgrios),4,Sacerdote (Sgrios),17,Sommo Chierico (Sgrios),9, Gran Sacerdote (Sgrios),5,
Garzone (Fabbro),6,Apprendista (Fabbro),7,Fabbro,8,Mastro Fabbro,10,
Invasato (Karnak),11,Fanatico (Karnak),12,Posseduto (Karnak),13,Maestro Delle Tenebre (Karnak),16,Portatore Di Morte (Karnak),15,Falciatore Di Anime (Karnak),41,
Iniziato (Mago),42,Stregone (Mago),43,Mago,44,Arcimago,50,
Stalliere Dei Draghi (Drago Verde),51,Scudiero Dei Draghi (Drago Verde),52,Cavaliere Dei Draghi (Drago Verde),53,Mastro Dei Draghi (Drago Verde),55,Cancelliere Dei Draghi (Drago Verde),54,Dominatore Dei Draghi (Drago Verde)",
//    "carriera"=>"Carriera,int",*/
    "punti_carriera"=>"Punti Carriera,int",
    "punti_generati"=>"Punti Generati (dall'ultima messa),int",
    "cambio_carriera"=>"Cambi Carriera,int",
    "suppliche"=>"Supppliche effettuate,int",
    "cittadino"=>"Cittadino,enum,Si,Sì,No,No",
    "fama_mod"=>"Modificatore Fama,viewonly",
    "fama3mesi"=>"Punti Fama Trimestre,viewonly",
    "fama_anno"=>"Punti Fama Annuali,viewonly",
    "minatore"=>"Abilità Minatore,float",
    "boscaiolo"=>"Abilità Boscaiolo,float",
    "falegname"=>"Abilità Falegname,float",
    "assicurazione"=>"Assicurato se muore lavorando,enum,0,No,1,Sì",
    "pvpkills"=>"PVP vinti,int",
    "pvplost"=>"PVP persi,int",
    "pvpflag"=>"Data Ultimo PVP,viewonly",

    "Specialità,title",
    "specialty"=>"Specialità,enum,0,Non Specificata,1,Arti Oscure,2,Poteri Mistici,3,Furto,4,Militare,5,Seduzione,6,
Tattica,7,Pelle di Roccia,8,Retorica,9,Muscoli,10,Natura,11,Clima,12,Elementalista,13,Rabbia Barbara,14,Canzoni del Bardo",
    "darkarts"=>"`4Livello in Arti Oscure`0,int",
    "darkartuses"=>"`4^--Utilizzo odierno`0,int",
    "magic"=>"`%Livello in Poteri Mistici`0,int",
    "magicuses"=>"`%^--Utilizzo odierno`0,int",
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
    "usura_arma"=>"Usura Arma (turni rimanenti),int",
    "armor"=>"Nome Armatura",
    "armordef"=>"Difesa dell'armatura,int",
    "armorvalue"=>"Costo acquisto dell'armatura,int",
    "usura_armatura"=>"Usura Armatura (turni rimanenti),int",

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
    "mountname"=>"Nome Animale",
    "boughtroomtoday"=>"Affittata stanza oggi,bool",
    "drunkenness"=>"Ubriachezza (0-100),int",
    "clean"=>"Odore,int",
    "bladder"=>"Vescica,int",
    "hunger"=>"Fame,int",
    "bounty"=>"Taglia,int",

    "Info Drago,title",
    "cavalcare_drago"=>"Cavalcare Draghi,int",
    "id_drago"=>"ID Drago,int",
    "turni_drago"=>"Turni Drago,int",
    "turni_drago_rimasti"=>"Turni Drago Rimasti,int",

    "Info Varie,title",
    "beta"=>"Desidera Partecipare fase BETA,viewonly",
    "slainby"=>"Ucciso da un Player,viewonly",
    "lastlogin"=>"Ultimo Login,viewonly",
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
    );
} else {
$userinfo = array(
    "Informazioni Account,title",
    "acctid"=>"ID Utente,viewonly",
    "login"=>"Login",
    "newpassword"=>"Nuova Password",
    "emailaddress"=>"Indirizzo Mail",
    "locked"=>"Account Bloccato,bool",
    "consono"=>"Nick Consono,enum,2,Sì,1,No,0,Da Verificare",
    "nocomment"=>"Giorni di Mute,int",
    "banoverride"=>"Supera Ban per questo account,bool",
    "superuser"=>"Utente Super,enum,0,Giorni standard per giorno di calendario,1,Giorni illimitati per giorno di calendario,2,Moderatore (creature e sbeffeggi),3,Admin Totale,4,Super Admin",
    "loggedin"=>"Connesso,enum,0,No,1,Sì",
    "stealth"=>"Modalità Invisibile,enum,0,No,1,Sì",
    "badguy"=>"Avversario in combattimento,viewonly",

    "Info Base Utente,title",
    "name"=>"Nome a Video",
    "title"=>"Titolo (devi metterlo anche in Nome a Video)",
    "ctitle"=>"Titolo Personalizzato (devi metterlo anche in Nome a Video)",
    "sex"=>"Sesso,enum,0,Maschio,1,Femmina",
// we can't change this this way or their stats will be wrong.
//  "race"=>"Race,enum,0,Unknown,1,Troll,2,Elf,3,Human,4,Dwarf",
    "race"=>"Razza (NOTA: non aggiorna le stat in automatico),enum$races1",
     "age"=>"Giorni dal livello 1,int",
    "dragonkills"=>"Quante volte ha ucciso il drago,int",
    "dragonage"=>"Quanto vecchio l'ultima uccisione drago,int",
    "bestdragonage"=>"Miglior risultato uccisione drago,int",
    "jail"=>"In prigione,enum,0,No,1,Sì (opzioni sceriffo),2,Sì (No opzioni sceriffo)",
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
    "npg"=>"Player Non Giocante (NPG),enum,0,Falso,1,Vero",
    "gdr"=>"Piazza GDR,enum,no,Falso,si,Vero",
    "level"=>"Livello,int",
    "experience"=>"Esperienza,int",
    "hitpoints"=>"Hitpoints Attuali,int",
    "maxhitpoints"=>"Hitpoints Massimi,int",
    "turns"=>"Combattimenti rimanenti,int",
    "turni_mestiere"=>"Turni Mestiere,int",
    "playerfights"=>"Turni PvP rimanenti,int",
    "pk"=>"Immunità PVP (solo nei primi giornì),enum,0,Sì,1,No",
    "attack"=>"Attacco (include danno dell'arma),int",
    "defence"=>"Difesa (include difesa armatura),int",
    "spirits"=>"Spirito (solo display),enum,-2,Molto Basso,-1,Basso,0,Normale,1,Alto,2,Molto Alto",
    "resurrections"=>"Resurrezioni,int",
    "agedrago"=>"Giorni dall'ultimo Drago Verde ucciso, int",
    "quest"=>"Quest,int",
    "questcastle"=>"Quest x Castello Abbandonato,int",
    "labi"=>"Labirinto,int",
    "casa"=>"HouseID,int",
    "house"=>"Proprietario della Tenuta,int",
    "housekey"=>"Chiave di casa (deve essere uguale alla voce Proprietario della Tenuta),int",
    "reincarna"=>"Reincarnazioni,int",
    "bonusattack"=>"Bonus Attacco,int",
    "bonusdefence"=>"Bonus Difesa,int",
    "bonusfight"=>"Bonus Combattimenti,int",
    "oggetto"=>"Oggetto,int",
    "zaino"=>"Zaino,int",
    "messa"=>"Preso Messa,enum,0,No,1,Sì",
//    "dio"=>"Dio,enum,0,Agnostico,1,Sgrios,2,Karnak,3,Drago Verde,4,Natura,5,Eretico",
    "dio"=>"Dio,enum$fede1",
    "carriera"=>"Carriera,enum$prof1",
/*    "carriera"=>"Carriera,enum,0,Nessuna,1,
Seguace (Sgrios),2,Accolito (Sgrios),3,Chierico (Sgrios),4,Sacerdote (Sgrios),17,Sommo Chierico (Sgrios),9, Gran Sacerdote (Sgrios),5,
Garzone (Fabbro),6,Apprendista (Fabbro),7,Fabbro,8,Mastro Fabbro,10,
Invasato (Karnak),11,Fanatico (Karnak),12,Posseduto (Karnak),13,Maestro Delle Tenebre (Karnak),16,Portatore Di Morte (Karnak),15,Falciatore Di Anime (Karnak),41,
Iniziato (Mago),42,Stregone (Mago),43,Mago,44,Arcimago,50,
Stalliere Dei Draghi (Drago Verde),51,Scudiero Dei Draghi (Drago Verde),52,Cavaliere Dei Draghi (Drago Verde),53,Mastro Dei Draghi (Drago Verde),55,Cancelliere Dei Draghi (Drago Verde),54,Dominatore Dei Draghi (Drago Verde)",
//    "carriera"=>"Carriera,int",*/
    "punti_carriera"=>"Punti Carriera,int",
    "punti_generati"=>"Punti Generati (dall'ultima messa),int",
    "cambio_carriera"=>"Cambi Carriera,int",
    "suppliche"=>"Supppliche effettuate,int",
    "cittadino"=>"Cittadino,enum,Si,Sì,No,No",
    "fama_mod"=>"Modificatore Fama,viewonly",
    "fama3mesi"=>"Punti Fama Trimestre,viewonly",
    "fama_anno"=>"Punti Fama Annuali,viewonly",
    "minatore"=>"Abilità Minatore,float",
    "boscaiolo"=>"Abilità Boscaiolo,float",
    "falegname"=>"Abilità Falegname,float",
    "assicurazione"=>"Assicurato se muore lavorando,enum,0,No,1,Sì",
    "pvpkills"=>"PVP vinti,int",
    "pvplost"=>"PVP persi,int",
    "pvpflag"=>"Data Ultimo PVP,viewonly",

    "Specialità,title",
    "specialty"=>"Specialità,enum,0,Non Specificata,1,Arti Oscure,2,Poteri Mistici,3,Furto,4,Militare,5,Seduzione,6,
Tattica,7,Pelle di Roccia,8,Retorica,9,Muscoli,10,Natura,11,Clima,12,Elementalista,13,Rabbia Barbara,14,Canzoni del Bardo",
    "darkarts"=>"`4Livello in Arti Oscure`0,int",
    "darkartuses"=>"`4^--Utilizzo odierno`0,int",
    "magic"=>"`%Livello in Poteri Mistici`0,int",
    "magicuses"=>"`%^--Utilizzo odierno`0,int",
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
    "usura_arma"=>"Usura Arma (turni rimanenti),int",
    "armor"=>"Nome Armatura",
    "armordef"=>"Difesa dell'armatura,int",
    "armorvalue"=>"Costo acquisto dell'armatura,int",
    "usura_armatura"=>"Usura Armatura (turni rimanenti),int",

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
    "mountname"=>"Nome Animale",
    "boughtroomtoday"=>"Affittata stanza oggi,bool",
    "drunkenness"=>"Ubriachezza (0-100),int",
    "clean"=>"Odore,int",
    "bladder"=>"Vescica,int",
    "hunger"=>"Fame,int",
    "bounty"=>"Taglia,int",

    "Info Drago,title",
    "cavalcare_drago"=>"Cavalcare Draghi,int",
    "id_drago"=>"ID Drago,int",
    "turni_drago"=>"Turni Drago,int",
    "turni_drago_rimasti"=>"Turni Drago Rimasti,int",

    "Fattorie,title",
    "manager"=>"Direttore Apophis,bool",
    "land"=>"Acri,int",
    "farms"=>"Fattorie,int",
    "slaves"=>"Schiavi,int",

    "Info Varie,title",
    "beta"=>"Desidera Partecipare fase BETA,viewonly",
    "slainby"=>"Ucciso da un Player,viewonly",
    "lastlogin"=>"Ultimo Login,viewonly",
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
    );
}
if ($_GET[op]=="lasthit"){
    $output="";
    $sql = "SELECT output FROM accounts WHERE acctid='{$_GET['userid']}'";
    $result = db_query($sql);
    $row = db_fetch_assoc($result);
    echo str_replace("<iframe src=","<iframe Xsrc=",$row['output']);
    exit();
}elseif ($_GET[op]=="edit"){
    $result = db_query("SELECT * FROM accounts WHERE acctid='$_GET[userid]'") or die(db_error(LINK));
    $row = db_fetch_assoc($result) or die(db_error(LINK));
    output("<form action='user.php?op=special&userid=$_GET[userid]".($_GET['returnpetition']!=""?"&returnpetition={$_GET['returnpetition']}":"")."' method='POST'>",true);
    addnav("","user.php?op=special&userid=$_GET[userid]".($_GET['returnpetition']!=""?"&returnpetition={$_GET['returnpetition']}":"")."");
    output("<input type='submit' class='button' name='kick' value='Disconnetti'>",true);
    output("<input type='submit' class='button' name='newday' value='Assegna Nuovo Giorno'>`n`n",true);
    output("<input type='submit' class='button' name='fixnavs' value='Fissa Link Errato'>",true);
    output("<input type='submit' class='button' name='clearvalidation' value='Segna Email come Valida'>",true);
    output("</form>",true);

    if ($_GET['returnpetition']!=""){
        addnav("Torna alle Petizioni","viewpetition.php?op=view&id={$_GET['returnpetition']}");
        debuglog("`0accede da petizione ".$_GET['returnpetition']);
    }

    addnav("Guarda ultima pagina vista","user.php?op=lasthit&userid={$_GET['userid']}",false,true);
    output("<form action='user.php?op=save&userid=$_GET[userid]".($_GET['returnpetition']!=""?"&returnpetition={$_GET['returnpetition']}":"")."' method='POST'>",true);
    addnav("","user.php?op=save&userid=$_GET[userid]".($_GET['returnpetition']!=""?"&returnpetition={$_GET['returnpetition']}":""));
    addnav("","user.php?op=edit&userid=$_GET[userid]".($_GET['returnpetition']!=""?"&returnpetition={$_GET['returnpetition']}":""));
    addnav("Aggiungi Ban","user.php?op=setupban&userid=$row[acctid]");
    addnav("Mostra Log","user.php?op=debuglog&userid={$_GET['userid']}".($_GET['returnpetition']!=""?"&returnpetition={$_GET['returnpetition']}":"")."");
    output("<input type='submit' class='button' value='Salva'>",true);
    showform($userinfo,$row);
    output("</form>",true);
    output("<iframe src='user.php?op=lasthit&userid={$_GET['userid']}' width='100%' height='400'>Necessiti iframes per vedere l'ultima pagina navigata dal player.  Usa il link nella nav invece.</iframe>",true);
    addnav("","user.php?op=lasthit&userid={$_GET['userid']}");
    debuglog("`0accede al panello dati di ",$_GET['userid']);
    //report salvataggio dati utente
    $sql2 = "SELECT name FROM accounts WHERE acctid=".$_GET['userid'];
    $result2 = db_query($sql2) or die(db_error(LINK));
    $row2 = db_fetch_assoc($result2);
    report(4,"`4Accesso pannello dati utente","`4".$session['user']['name']." `4(acctid: ".$session['user']['acctid'].") è acceduto al pannello dati di ".$row2['name']." `4(acctid: ".$_GET['userid'].")","vista_utente");
}elseif ($_GET[op]=="special"){
    if ($_POST[newday]!=""){
        $sql = "UPDATE accounts SET lasthit='".date("Y-m-d H:i:s",strtotime(date("r")."-".(86500/getsetting("daysperday",4))." secondi"))."' WHERE acctid='$_GET[userid]'";
        debuglog("`0assegna NewDay a ",$_GET['userid']);
    }elseif($_POST[fixnavs]!=""){
        $sql = "UPDATE accounts SET allowednavs='',output=\"\" WHERE acctid='$_GET[userid]'";
        debuglog("`0sblocca la navigazione a ",$_GET['userid']);
    }elseif($_POST[clearvalidation]!=""){
        $sql = "UPDATE accounts SET emailvalidation='' WHERE acctid='$_GET[userid]'";
        debuglog("`0valida l'email di ",$_GET['userid']);
    }elseif($_POST[kick]!=""){
        $sql = "UPDATE accounts SET kicked='1' WHERE acctid='$_GET[userid]'";
        debuglog("`0butta fuori dal gioco ",$_GET['userid']);
    }

    db_query($sql);
    if ($_GET['returnpetition']==""){
        redirect("user.php?".db_affected_rows());
    }else{
        redirect("viewpetition.php?op=view&id={$_GET['returnpetition']}");
    }
}elseif ($_GET[op]=="save"){
    $sql = "UPDATE accounts SET ";
    reset($_POST);
    while (list($key,$val)=each($_POST)){
        if (isset($userinfo[$key])){
            if ($key=="newpassword" ){
                if ($val>"") $sql.="password = \"".md5($val)."\",";
            }else{
                $sql.="$key = \"$val\",";
            }
        }
    }
    $sql=substr($sql,0,strlen($sql)-1);
    $sql.=" WHERE acctid=\"$_GET[userid]\"";
    //output("<pre>$sql</pre>");
    //echo "<pre>$sql</pre>";
    //redirect("user.php");
    //output( db_affected_rows()." rows affected");

    //we must manually redirect so that our changes go in to effect *after* our user save.
    addnav("","viewpetition.php?op=view&id={$_GET['returnpetition']}");
    addnav("","user.php");
    saveuser();
    debuglog("`0modifica i parametri di ",$_GET['userid']);
    db_query($sql) or die(db_error(LINK));

    //report salvataggio dati utente
    $sql2 = "SELECT name FROM accounts WHERE acctid=".$_GET['userid'];
    $result2 = db_query($sql2) or die(db_error(LINK));
    $row2 = db_fetch_assoc($result2);
    report(4,"`\$`bEDIT UTENTE`b","`4".$session['user']['name']." `4(acctid: ".$session['user']['acctid'].") ha salvato i seguenti dati all'utente ".$row2['name']." `4(acctid: ".$_GET['userid']."):`n`n`0".$sql,"edit_utente");

    if ($_GET['returnpetition']!=""){
        header("Location: viewpetition.php?op=view&id={$_GET['returnpetition']}");
    }else{
        header("Location: user.php");
    }

    exit();
}elseif ($_GET[op]=="del"){
    $sql = "SELECT name,emailaddress,login FROM accounts WHERE acctid='$_GET[userid]'";
    $res = db_query($sql);
    $row = db_fetch_assoc($res);
    if ($_GET[conferma]=="bye") {
        //modifica PvP Online
        $sql = "DELETE FROM pvp WHERE acctid2=$_GET[userid] OR acctid1=$_GET[userid]";
        db_query($sql) or die(db_error(LINK));
        //Fine Modifica PvP online
        $sql = "UPDATE items SET owner = 0 WHERE owner=$_GET[userid]";
        db_query($sql);
//        $sql = "UPDATE houses SET owner=0,status=3 WHERE owner=$_GET[userid] AND status=1";
//        db_query($sql);
        $sql = "SELECT * FROM houses WHERE owner=$_GET[userid] AND status=1";
        $resultb = db_query($sql) or die(db_error(LINK));
        $countrow1 = db_num_rows($resultb);
        for ($i=0; $i<$countrow1; $i++){
            $casadel = db_fetch_assoc($resultb);
            $sqlf = "SELECT owner,gems FROM items WHERE value1=".$casadel[houseid]." AND class='key' AND ((owner=$_GET[userid] AND value2=10) OR (owner<>$_GET[userid])) ORDER BY id ASC";
            $resultf = db_query($sqlf) or die(db_error(LINK));
            $office = db_num_rows($resultf);
            $goldgive = round($casadel[gold]/$office);
            for ($k=0; $k<$office; $k++){
                $itemf = db_fetch_assoc($resultf);
                if ($itemf['owner'] != $row[acctid]) {
                    $sqlf2 = "UPDATE accounts SET goldinbank=(goldinbank+$goldgive),gems=(gems+".$itemf['gems'].") WHERE acctid = ".$itemf['owner'];
                    db_query($sqlf2);
                    systemmail($itemf['owner'],"`@Sbattuto Fuori!`0","`&".$row['name']."`2 ha venduto la
                    Tenuta!`nOra `b".$casadel[housename]."`b`2 è in vendita ed abbandonata.`nPoichè hai vissuto come
                    affittuario che ha contribuito al mantenimento della Tenuta, ricevi `^$goldgive`2 pezzi d'oro
                    e le tue `#".$itemf['gems']."`2 gemme dalla cassaforte!");
                    $sqllog = "INSERT INTO debuglog VALUES(0,now(),{$itemf['owner']},0,'".addslashes("viene sbattuto
                    fuori dalla tenuta ".$casadel[housename]." messa in vendita da ".$row['name']." (cancellato) e ottiene $goldgive
                    oro e ".$itemf['gems']." gemme per il disturbo")."')";
                    db_query($sqllog);
                }
            }
            $sqlf3 = "UPDATE items SET owner=0, gems=0, tempo=0 WHERE class='key' AND value1=".$casadel[houseid];
            db_query($sqlf3);
            $sqlf4 = "UPDATE houses SET owner=0, status=3, gold=60000, gems=90, fede=0 WHERE houseid=".$casadel[houseid];
            db_query($sqlf4);
        }
        $sql = "UPDATE houses SET owner=0,status=4 WHERE owner=$_GET[userid] AND status=0";
        db_query($sql);
        $sql = "UPDATE accounts SET charisma=0,marriedto=0 WHERE marriedto=$_GET[userid]";
        db_query($sql);
        $sql = "INSERT INTO accounts_deleted
                SELECT accounts.*
                FROM accounts WHERE accounts.acctid = ".$_GET[userid];
        db_query($sql);
//  Barik: invio mail al player
        $objectmail = $row[login].", sei stato cancellato da ".getsetting("nomedb","logd");
        $banmailbody = $_POST[mailmessage]."\n\nQuesta è una mail di sistema, se vuoi appellarti alla cancellazione del pg usa il link per le petizioni dalla pagina di login.";
        mail($_GET[email],$objectmail,$banmailbody,"From: ".getsetting("gameadminemail","postmaster@localhost.com"));
//  Barik: fine invio mail
        addnews("`#{$row['name']} è stato disfatto dagli dei.");
        debuglog("`0cancella dal gioco il personaggio ".$row['name']);
        report(2,"`&Cancellato personaggio","`\$".$session['user']['name']." ha cancellato il personaggio ".$row['name'],"provvedimenti");
        $sql = "DELETE FROM accounts WHERE acctid='$_GET[userid]'";
        db_query($sql);
        output( db_affected_rows()." utente cancellato.");
    } else {
        output("<big>`^`b`cATTENZIONE!!!!!`c`b`0`n</big>",true);
        output("`n`n`#Stai per cancellare `\$DEFINITIVAMENTE `#il personaggio {$row['name']} `n");
        output("`n`c`^Sei sicuro di voler procedere???`c`n`n`n`0");
//         output("<A href=user.php?op=del&userid=$_GET[userid]&conferma=bye>Cancella </a>`n`n",true);
//         addnav("","user.php?op=del&userid=$_GET[userid]&conferma=bye");
//  Barik: invio mail al player
        output("<form action='user.php?op=del&userid=$_GET[userid]&conferma=bye&email=".$row[emailaddress]."' method='POST'>",true);
        output("`nInserisci il testo della mail di sistema che vuoi inviare a ".$row['name']."`0:`n",true);
        output("<textarea class='input' name='mailmessage' cols='40' rows='10'>".HTMLEntities(stripslashes($_POST['mailmessage']))."</textarea>`n",true);
        output("<input type='submit' class='button' value='Cancella PG' onClick='if (document.getElementById(\"duration\").value==0) {return per confermare(\"Sei sicuro di mettere un ban permanente?\");} else {return true;}'></form>",true);
        addnav("","user.php?op=del&userid=$_GET[userid]&conferma=bye&email=".$row[emailaddress]);
//  Barik: fine invio mail
    }
}elseif($_GET[op]=="setupban"){
    $sql = "SELECT name,lastip,uniqueid,login,emailaddress FROM accounts WHERE acctid=\"$_GET[userid]\"";
    $result = db_query($sql) or die(db_error(LINK));
    $row = db_fetch_assoc($result);
    if ($row[name]!="") output("Setting up ban information based on `\$$row[name]`0");
    output("<form action='user.php?op=saveban&email=".$row['emailaddress']."' method='POST'>",true);
    output("Metti un nuovo ban per Nome, IP o ID (raccommandato IP, sebbene nel caso tu abbia diversi utenti dietro un  NAT, puoi tentare con ID che è facilmente aggirabile)`n");
    output("<input type='checkbox' value='name' name='type1'> Nome: <input name='name' value=\"".HTMLEntities($row[login])."\">`n",true);
    output("<input type='checkbox' value='ip' name='type2' checked> IP: <input name='ip' value=\"".HTMLEntities($row[lastip])."\">`n",true);
    output("<input type='checkbox' value='id' name='type3'> ID: <input name='id' value=\"".HTMLEntities($row[uniqueid])."\">`n",true);
    output("Durata: <input name='duration' id='duration' size='3' value='14'> giorni (0 per permanente)`n",true);
    output("Motivo del ban: <input name='reason' value=\"Non fare il furbo con me.\">`n",true);
    output("`nInserisci il testo della mail di sistema che vuoi inviare a ".$row['name']."`0:`n",true);
    output("<textarea class='input' name='mailmessage' cols='40' rows='10'>".HTMLEntities(stripslashes($_POST['mailmessage']))."</textarea>`n",true);
    output("<input type='submit' class='button' value='Attua Ban' onClick='if (document.getElementById(\"duration\").value==0) {return per confermare(\"Sei sicuro di mettere un ban permanente?\");} else {return true;}'></form>",true);
    output("Per un ban su IP, inserisci la parte iniziale dell'IP che vuoi bannare se vuoi bannare un range di IP, o semplicemente un IP completo per bannare un singolo IP");
    addnav("","user.php?op=saveban&email=".$row['emailaddress']);
}elseif($_GET[op]=="saveban"){
    $sql = "INSERT INTO bans (";
    $sqlbis = "";
    $sqlthird = "";
    $bantype = "BAN ";
    /*if ($_POST[type]=="ip"){
        $sql.="ipfilter";
    }else{
        $sql.="uniqueid";
    }
    $sql.=",banexpire,banreason) VALUES (";
    if ($_POST[type]=="ip"){
        $sql.="\"$_POST[ip]\"";
    }else{
        $sql.="\"$_POST[id]\"";
    }*/
    // Excalibur: modifica per ban char
//     if ($_POST[type]=="ip"){
//         $sql.="ipfilter";
//     }else if ($_POST[type]=="id"){
//         $sql.="uniqueid";
//     }else{
//         $sql.="bannedchar";
//     }
//     $sql.=",banexpire,banreason) VALUES (";
//     if ($_POST[type]=="ip"){
//         $sql.="\"$_POST[ip]\"";
//     }else if ($_POST[type]=="id"){
//         $sql.="\"$_POST[id]\"";
//     }else{
//         $sql.="\"$_POST[name]\"";
//     }
    //Excalibur: fine modifica ban

//  Barik: ban with checkbox instead radio
    if (($_POST[type1]!="")and($_POST[type2]=="")and($_POST[type3]=="")){
        $sql.="bannedchar";
        $bantype.="Name";
    }elseif (($_POST[type1]=="")and($_POST[type2]!="")and($_POST[type3]=="")){
        $sql.="ipfilter";
        $bantype.="IP";
    }elseif (($_POST[type1]=="")and($_POST[type2]=="")and($_POST[type3]!="")){
        $sql.="uniqueid";
        $bantype.="ID";
    }elseif (($_POST[type1]!="")and($_POST[type2]!="")and($_POST[type3]=="")){
        $sqlbis=$sql;
        $sql.="bannedchar";
        $sqlbis.="ipfilter";
        $bantype.="Name/IP";
    }elseif (($_POST[type1]=="")and($_POST[type2]!="")and($_POST[type3]!="")){
        $sqlbis=$sql;
        $sql.="ipfilter";
        $sqlbis.="uniqueid";
        $bantype.="IP/ID";
    }elseif (($_POST[type1]!="")and($_POST[type2]=="")and($_POST[type3]!="")){
        $sqlbis=$sql;
        $sql.="bannedchar";
        $sqlbis.="uniqueid";
        $bantype.="Name/ID";
    }else {
        $sqlbis=$sql;
        $sqlthird=$sql;
        $sql.="bannedchar";
        $sqlbis.="ipfilter";
        $sqlthird.="uniqueid";
        $bantype.="Name/IP/ID";
    }
    $sql.=",banexpire,banreason) VALUES (";
    if ($sqlbis!="") $sqlbis.=",banexpire,banreason) VALUES (";
    if ($sqlthird!="") $sqlthird.=",banexpire,banreason) VALUES (";
    if (($_POST[type1]!="")and($_POST[type2]=="")and($_POST[type3]=="")){
        $sql.="\"$_POST[name]\"";
    }elseif (($_POST[type1]=="")and($_POST[type2]!="")and($_POST[type3]=="")){
        $sql.="\"$_POST[ip]\"";
    }elseif (($_POST[type1]=="")and($_POST[type2]=="")and($_POST[type3]!="")){
        $sql.="\"$_POST[id]\"";
    }elseif (($_POST[type1]!="")and($_POST[type2]!="")and($_POST[type3]=="")){
        $sql.="\"$_POST[name]\"";
        $sqlbis.="\"$_POST[ip]\"";
    }elseif (($_POST[type1]=="")and($_POST[type2]!="")and($_POST[type3]!="")){
        $sql.="\"$_POST[ip]\"";
        $sqlbis.="\"$_POST[id]\"";
    }elseif (($_POST[type1]!="")and($_POST[type2]=="")and($_POST[type3]!="")){
        $sql.="\"$_POST[name]\"";
        $sqlbis.="\"$_POST[id]\"";
    }else{
        $sql.="\"$_POST[name]\"";
        $sqlbis.="\"$_POST[ip]\"";
        $sqlthird.="\"$_POST[id]\"";
    }

    $sql.=",\"".((int)$_POST[duration]==0?"0000-00-00":date("Y-m-d",strtotime(date("r")."+$_POST[duration] days")))."\",";
    $sql.="\"$_POST[reason]\")";
//  Barik: add for second type of ban
    if ($sqlbis!=""){
    $sqlbis.=",\"".((int)$_POST[duration]==0?"0000-00-00":date("Y-m-d",strtotime(date("r")."+$_POST[duration] days")))."\",";
    $sqlbis.="\"$_POST[reason]\")";
    }
//  Barik: add for third type of ban
    if ($sqlthird!=""){
    $sqlthird.=",\"".((int)$_POST[duration]==0?"0000-00-00":date("Y-m-d",strtotime(date("r")."+$_POST[duration] days")))."\",";
    $sqlthird.="\"$_POST[reason]\")";
    }

    if ($_POST[type2]!=""){
        if (substr($_SERVER['REMOTE_ADDR'],0,strlen($_POST[ip])) == $_POST[ip]){
            $sql = "";
            output("Non vuoi veramente bannare te stesso, vero??  Questo è il TUO indirizzo IP!");
        }
    }else{
        if ($_COOKIE[$DB_NAME]==$_POST[id]){
            $sql = "";
            output("Non vuoi veramente bannare te stesso, vero??  Questo è il TUO identificativo ID!");
        }
    }
    if (($sql!="")and($sqlbis=="")and($sqlthird=="")) {
        db_query($sql) or die(db_error(LINK));
        output(db_affected_rows()." ban row inserito.`n`n");
        output(db_error(LINK));
        debuglog("`0assegna un ban");
        if($_POST[type1]!="") report(2,"`&Messo ban","`\$".$session['user']['name']." ha messo un ban di tipo ".$_POST[type1]." su IP: ".$_POST[ip].", ID: ".$_POST[id].", nome: ".$_POST[name],"provvedimenti");
        if($_POST[type2]!="") report(2,"`&Messo ban","`\$".$session['user']['name']." ha messo un ban di tipo ".$_POST[type2]." su IP: ".$_POST[ip].", ID: ".$_POST[id].", nome: ".$_POST[name],"provvedimenti");
        if($_POST[type3]!="") report(2,"`&Messo ban","`\$".$session['user']['name']." ha messo un ban di tipo ".$_POST[type3]." su IP: ".$_POST[ip].", ID: ".$_POST[id].", nome: ".$_POST[name],"provvedimenti");
        // Barik: inserimento punizioni
        $sql_guilty = "SELECT acctid,name FROM accounts WHERE login=\"$_POST[name]\"";
        $result_guilty = db_query($sql_guilty) or die(db_error(LINK));
        $row_guilty = db_fetch_assoc($result_guilty);
        $sql_punitions = "INSERT INTO punitions (acctid_guilty,guilty,whomade,date,cause,type,numday) VALUES (\"".$row_guilty[acctid]."\",\"".$row_guilty[name]."\",\"".$session[user][acctid]."\",now(),\"".$_POST[reason]."\",\"".$bantype."\",\"".$_POST[duration]."\")";
        db_query($sql_punitions) or die(db_error(LINK));
        // Barik: fine punizioni
        $objectmail = $_POST[name].", sei stato bannato da ".getsetting("nomedb","logd");
        $banmailbody = $_POST[mailmessage]."\n\nQuesta è una mail di sistema, se vuoi appellarti alla punizione usa il link per le petizioni dalla pagina di login.";
        mail($_GET[email],$objectmail,$banmailbody,"From: ".getsetting("gameadminemail","postmaster@localhost.com"));
    }elseif (($sql!="")and($sqlbis!="")and($sqlthird=="")) {
        db_query($sql) or die(db_error(LINK));
        output(db_affected_rows()." ban row inserito.`n`n");
        output(db_error(LINK));
        debuglog("`0assegna un ban");
        db_query($sqlbis) or die(db_error(LINK));
        output(db_affected_rows()." ban row inserito.`n`n");
        output(db_error(LINK));
        debuglog("`0assegna un ban");
        if($_POST[type1]!="") report(2,"`&Messo ban","`\$".$session['user']['name']." ha messo un ban di tipo ".$_POST[type1]." su IP: ".$_POST[ip].", ID: ".$_POST[id].", nome: ".$_POST[name],"provvedimenti");
        if($_POST[type2]!="") report(2,"`&Messo ban","`\$".$session['user']['name']." ha messo un ban di tipo ".$_POST[type2]." su IP: ".$_POST[ip].", ID: ".$_POST[id].", nome: ".$_POST[name],"provvedimenti");
        if($_POST[type3]!="") report(2,"`&Messo ban","`\$".$session['user']['name']." ha messo un ban di tipo ".$_POST[type3]." su IP: ".$_POST[ip].", ID: ".$_POST[id].", nome: ".$_POST[name],"provvedimenti");
        // Barik: inserimento punizioni
        $sql_guilty = "SELECT acctid,name FROM accounts WHERE login=\"$_POST[name]\"";
        $result_guilty = db_query($sql_guilty) or die(db_error(LINK));
        $row_guilty = db_fetch_assoc($result_guilty);
        $sql_punitions = "INSERT INTO punitions (acctid_guilty,guilty,whomade,date,cause,type,numday) VALUES (\"".$row_guilty[acctid]."\",\"".$row_guilty[name]."\",\"".$session[user][acctid]."\",now(),\"".$_POST[reason]."\",\"".$bantype."\",\"".$_POST[duration]."\")";
        db_query($sql_punitions) or die(db_error(LINK));
        // Barik: fine punizioni
        $objectmail = $_POST[name].", sei stato bannato da ".getsetting("nomedb","logd");
        $banmailbody = $_POST[mailmessage]."\n\nQuesta è una mail di sistema, se vuoi appellarti alla punizione usa il link per le petizioni dalla pagina di login.";
        mail($_GET[email],$objectmail,$banmailbody,"From: ".getsetting("gameadminemail","postmaster@localhost.com"));
    }elseif (($sql!="")and($sqlbis!="")and($sqlthird!="")) {
        db_query($sql) or die(db_error(LINK));
        output(db_affected_rows()." ban row inserito.`n`n");
        output(db_error(LINK));
        debuglog("`0assegna un ban");
        db_query($sqlbis) or die(db_error(LINK));
        output(db_affected_rows()." ban row inserito.`n`n");
        output(db_error(LINK));
        debuglog("`0assegna un ban");
        db_query($sqlthird) or die(db_error(LINK));
        output(db_affected_rows()." ban row inserito.`n`n");
        output(db_error(LINK));
        debuglog("`0assegna un ban");
        if($_POST[type1]!="") report(2,"`&Messo ban","`\$".$session['user']['name']." ha messo un ban di tipo ".$_POST[type1]." su IP: ".$_POST[ip].", ID: ".$_POST[id].", nome: ".$_POST[name],"provvedimenti");
        if($_POST[type2]!="") report(2,"`&Messo ban","`\$".$session['user']['name']." ha messo un ban di tipo ".$_POST[type2]." su IP: ".$_POST[ip].", ID: ".$_POST[id].", nome: ".$_POST[name],"provvedimenti");
        if($_POST[type3]!="") report(2,"`&Messo ban","`\$".$session['user']['name']." ha messo un ban di tipo ".$_POST[type3]." su IP: ".$_POST[ip].", ID: ".$_POST[id].", nome: ".$_POST[name],"provvedimenti");
        // Barik: inserimento punizioni
        $sql_guilty = "SELECT acctid,name FROM accounts WHERE login=\"$_POST[name]\"";
        $result_guilty = db_query($sql_guilty) or die(db_error(LINK));
        $row_guilty = db_fetch_assoc($result_guilty);
        $sql_punitions = "INSERT INTO punitions (acctid_guilty,guilty,whomade,date,cause,type,numday) VALUES (\"".$row_guilty[acctid]."\",\"".$row_guilty[name]."\",\"".$session[user][acctid]."\",now(),\"".$_POST[reason]."\",\"".$bantype."\",\"".$_POST[duration]."\")";
        db_query($sql_punitions) or die(db_error(LINK));
        // Barik: fine punizioni
        $objectmail = $_POST[name].", sei stato bannato da ".getsetting("nomedb","logd");
        $banmailbody = $_POST[mailmessage]."\n\nQuesta è una mail di sistema, se vuoi appellarti alla punizione usa il link per le petizioni dalla pagina di login.";
        mail($_GET[email],$objectmail,$banmailbody,"From: ".getsetting("gameadminemail","postmaster@localhost.com"));
    }
//  Barik: end ban modify


}elseif($_GET[op]=="delban"){
    $sql = "DELETE FROM bans WHERE ipfilter = \"$_GET[ipfilter]\" AND uniqueid = \"$_GET[uniqueid]\"";
    db_query($sql);
    debuglog("`0rimuove un ban");
    report(2,"`&Tolto ban","`\$".$session['user']['name']." ha tolto il ban su IP:".$_GET[ipfilter]." e ID:".$_GET[uniqueid],"provvedimenti");
    //output($sql);
    redirect("user.php?op=removeban");
}elseif($_GET[op]=="removeban"){
    db_query("DELETE FROM bans WHERE banexpire < \"".date("Y-m-d")."\" AND banexpire>'0000-00-00'");

    $sql = "SELECT * FROM bans ORDER BY banexpire";
    $result = db_query($sql) or die(db_error(LINK));
    output("<table><tr><td>Ops</td><td>IP/ID/Name</td><td>Durata</td><td>Messaggio</td><td>Colpisce:</td></tr>",true);
    $countrow = db_num_rows($result);
    for ($i=0; $i<$countrow; $i++){
    //for ($i=0;$i<db_num_rows($result);$i++){
        $row = db_fetch_assoc($result);
        output("<tr class='".($i%2?"trlight":"trdark")."'><td><a href='user.php?op=delban&ipfilter=".URLEncode($row[ipfilter])."&uniqueid=".URLEncode($row[uniqueid])."'>Lift&nbsp;ban</a>",true);
        addnav("","user.php?op=delban&ipfilter=".URLEncode($row[ipfilter])."&uniqueid=".URLEncode($row[uniqueid]));
        output("</td><td>",true);
        output($row[ipfilter]);
        output($row[uniqueid]);
        output($row[bannedchar]);
        output("</td><td>",true);
        $expire=round((strtotime($row[banexpire])-strtotime(date("r"))) / 86400,0)." giorni";
        if (substr($expire,0,2)=="1 ") $expire="1 giorno";
        if (date("Y-m-d",strtotime($row[banexpire])) == date("Y-m-d")) $expire="Oggi";
        if (date("Y-m-d",strtotime($row[banexpire])) == date("Y-m-d",strtotime("1 day"))) $expire="Domani";
        if ($row[banexpire]=="0000-00-00") $expire="MAI";
        output($expire);
        output("</td><td>",true);
        output($row[banreason]);
        output("</td><td>",true);
        if ($row[bannedchar] == ""){
           $sql = "SELECT DISTINCT accounts.name FROM bans, accounts WHERE (ipfilter='".addslashes($row['ipfilter'])."' AND bans.uniqueid='".addslashes($row['uniqueid'])."') AND ((substring(accounts.lastip,1,length(ipfilter))=ipfilter AND ipfilter<>'') OR (bans.uniqueid=accounts.uniqueid AND bans.uniqueid<>''))";
           $r = db_query($sql);
           $countrowr = db_num_rows($r);
           for ($x=0; $x<$countrowr; $x++){
           //for ($x=0;$x<db_num_rows($r);$x++){
             $ro = db_fetch_assoc($r);
             output("`0{$ro['name']}`n");
           }
        } else {
           $sql = "SELECT name FROM accounts WHERE login LIKE \"%{$row[bannedchar]}%\"";
           $result1 = db_query($sql);
           $row1 = db_fetch_assoc($result1);
           output("`0".$row1[name]."`n");
        }
        output("</td></tr>",true);
    }
    output("</table>",true);
}elseif($_GET[op]=="silenced"){
    $sql = "SELECT acctid, name, nocomment FROM accounts WHERE nocomment>0 ORDER BY nocomment DESC, acctid ASC";
    $result = db_query($sql) or die(db_error(LINK));
    output("<table><tr><td>`cOps`c</td><td>`cAcctid`c</td><td>`cNome`c</td><td>`cDurata`c</td></tr>",true);
    $countrow = db_num_rows($result);
    for ($i=0; $i<$countrow; $i++){
    //for ($i=0;$i<db_num_rows($result);$i++){
        $row = db_fetch_assoc($result);
        output("<tr class='".($i%2?"trlight":"trdark")."'><td><a href='user.php?op=unmute&acctid=".$row[acctid]."'>Unmute</a>",true);
        addnav("","user.php?op=unmute&acctid=".$row[acctid]);
        output("</td><td>",true);
        output($row[acctid]);
        output("</td><td>",true);
        output($row[name]);
        output("</td><td>",true);
        output($row[nocomment]);
        output("</td></tr>",true);
    }
    output("</table>",true);
}elseif($_GET[op]=="unmute"){
    $sql = "UPDATE accounts SET nocomment=0 WHERE acctid=".$_GET['acctid'];
    db_query($sql) or die(db_error(LINK));
    output("`n`nBlocco commenti rimosso.");
    debuglog("`0rimuove un mute a ",$_GET['acctid']);
    report(2,"`&Tolto mute","`\$".$session['user']['name']." ha tolto un mute a ".$_GET['acctid'],"provvedimenti");
}elseif ($_GET[op]=="debuglog"){
    $id = $_GET['userid'];
    addnav("U?Edita info Utente","user.php?op=edit&userid=$id".($_GET['returnpetition']!=""?"&returnpetition={$_GET['returnpetition']}":"")."");
    if ($_GET['returnpetition']!=""){
        addnav("Torna alle Petizioni","viewpetition.php?op=view&id={$_GET['returnpetition']}");
    }
    addnav("-----");
    $id = $_GET['userid'];
    $ppp=500; // Linee di debug da mostrare per pagina
    if (!$_GET[limit]){
        $page=0;
        debuglog("`0accede ai log di ",$_GET['userid']);
        $sql2 = "SELECT name FROM accounts WHERE acctid=$id";
        $result2 = db_query($sql2) or die(db_error(LINK));
        $row2 = db_fetch_assoc($result2);
        report(4,"`2Lettura Log","`4".$session['user']['name']." `4(acctid: ".$session['user']['acctid'].") è acceduto ai log di ".$row2['name']." `4(acctid: $id)","lettura_log");
    }else{
       $page=(int)$_GET[limit];
       addnav("Pagina Precedente","user.php?op=debuglog&userid=".$id."&limit=".($page-1)."");
    }
    $limit="".($page*$ppp).",".($ppp+1);
    $id = $_GET['userid'];
    $sql = "SELECT debuglog.*,a1.name as actorname,a2.name as targetname FROM debuglog LEFT JOIN accounts as a1 ON a1.acctid=debuglog.actor LEFT JOIN accounts as a2 ON a2.acctid=debuglog.target WHERE debuglog.actor=$id OR debuglog.target=$id ORDER by debuglog.date DESC,debuglog.id ASC LIMIT $limit";
    $result = db_query($sql);
    if (db_num_rows($result)>$ppp) addnav("Pagina Successiva","user.php?op=debuglog&userid=".$id."&limit=".($page+1)."");
    $odate = "";

    // Ricerca interna by Xtramus
            output('
          <form onsubmit="return cerca()">
        Evidenzia<input type="text" id="ricerca">
        <input type="button" value="Vai" onClick="cerca()"><br>
        <input type="radio" name="modo" value="evidenziarosso" checked>Evidenzia in rosso
        <input type="radio" name="modo" value="evidenziagiallo">Evidenzia in giallo
        <input type="radio" name="modo" value="eliminaaltri">Elimina altri
        <input type="radio" name="modo" value="elimina selezionati">Elimina selezionati
        </form>
        <br><br>
               ',true);
     // Continua dopo
    $countrow = db_num_rows($result);
    for ($i=0; $i<$countrow; $i++){
    //for ($i=0; $i<db_num_rows($result); $i++) {
        $row = db_fetch_assoc($result);
        $dom = date("D, M d",strtotime($row['date']));
        if ($odate != $dom){
            output("`n`b`@".$dom."`b`n`0");
            $odate = $dom;
        }
        $time = date("H:i:s", strtotime($row['date']));
        output("<div id=\"riga".($i+1)."n\" name=\"riga".($i+1)."n\" style=\"display:inline\">",true);
        output("`@$time`0 - {$row['actorname']} `3 {$row['message']}`0");
        //output("</span>",true);
        if ($row['target']) output(" {$row['targetname']}");
        output("</div>",true);
        output("`n");
    }
    // Ricerca interna
         output('<script type="text/javascript">
        function cerca() {
        parola=document.getElementById("ricerca").value.toLowerCase();
        bagcolor="no";
        modo= document.getElementsByName("modo");
        if(modo[0].checked) {
               bagcolor="#660000";
        } else if(modo[1].checked) {
               bagcolor="#666600";
        } else if(modo[2].checked) {
               eliminare= "altri";
        } else {
               eliminare= "selezionati";
        }
               for(var i=1; i<='.($i).'; i++) {
                    r= "riga"+i+"n";
                    dge= document.getElementById(r);
                    //alert(dge.innerHTML);
                    //break;
                    testo=dge.innerHTML.toLowerCase();
                    if(testo.indexOf(parola)!=-1) {
                         if(bagcolor!="no") {
                              dge.style.backgroundColor=bagcolor;
                              dge.style.display="inline";
                         }
                         else if(eliminare=="altri") {
                              dge.style.backgroundColor="";
                              dge.style.display="inline";
                         }
                         else {
                              dge.style.backgroundColor="";
                              dge.style.display="none";
                         }
                    }
                    else {
                         if(bagcolor!="no") {
                              dge.style.backgroundColor="";
                              dge.style.display="inline";
                         }
                         else if(eliminare=="altri") {
                              dge.style.display="none";
                         }
                         else {
                              dge.style.backgroundColor="";
                              dge.style.display="inline";
                         }
                    }
               }
               return false;
        }
        </script>
        ',true);

        // fine ricerca interna
}elseif ($_GET['op'] == "restore"){
        if ($_GET['userid'] == ""){
           if($_GET['rpage']=="") $_GET['rpage']=0;
           $offset=(int)$_GET['rpage']*100;
           $sql = "SELECT acctid FROM accounts_deleted ORDER BY laston DESC";
           $result = db_query($sql) or die(db_error(LINK));
           $conto = db_num_rows($result);
           $sql = "SELECT acctid,login,name,level,laston,lastip,uniqueid,emailaddress FROM accounts_deleted ORDER BY laston DESC LIMIT $offset,100";
           $result = db_query($sql) or die(db_error(LINK));
           output("<table>",true);
           output("<tr>
           <td>Ops</td>
           <td>Acctid</td>
           <td>Login</td>
           <td>Nome</td>
           <td>Liv</td>
           <td>Laston</td>
           <td>UltimoIP</td>
           <td>UltimoID</td>
           <td>Email</td>
           </tr>",true);
           $countrow = db_num_rows($result);
           for ($i=0; $i<$countrow; $i++){
           //for ($i=0;$i<db_num_rows($result);$i++){
               $row=db_fetch_assoc($result);
               $laston=round((strtotime(date("r"))-strtotime($row[laston])) / 86400,0)." giorni";
               if (substr($laston,0,2)=="1 ") $laston="1 day";
               if (date("Y-m-d",strtotime($row[laston])) == date("Y-m-d")) $laston="Oggi";
               if (date("Y-m-d",strtotime($row[laston])) == date("Y-m-d",strtotime(date("r")."-1 day"))) $laston="Ieri";
               if ($loggedin) $laston="Ora";
               $row[laston]=$laston;
               output("<tr class='".($rn%2?"trlight":"trdark")."'>",true);
               output("<td>",true);
               output("[<a href='user.php?op=restore&userid=$row[acctid]' onClick=\"return conferma('Sei sicuro di voler ripristinare questo utente?');\">Ripristina</a>]",true);
               addnav("","user.php?op=restore&userid=$row[acctid]");
               output("</td><td>",true);
               output($row[acctid]);
               output("</td><td>",true);
               output($row[login]);
               output("</td><td>",true);
               output($row[name]);
               output("</td><td>",true);
               output($row[level]);
               output("</td><td>",true);
               output($row[laston]);
               output("</td><td>",true);
               output($row[lastip]);
               output("</td><td>",true);
               output($row[uniqueid]);
               output("</td><td>",true);
               output($row[emailaddress]);
               output("</td>",true);
               output("</tr>",true);
           }
           output("</table>",true);
           addnav("Personaggi Cancellati");
           if ($_GET['rpage']>0) {
               $pagpre = $_GET['rpage'] - 1;
               addnav("Pagina Precedente","user.php?op=restore&rpage=".$pagpre);
           }
           if($conto > ($offset+100)) {
               $pagsucc = $_GET['rpage'] + 1;
               addnav("Pagina Successiva","user.php?op=restore&rpage=".$pagsucc);
           }

        }else {
                  $sql = "SELECT name FROM accounts_deleted WHERE acctid='$_GET[userid]'";
                  $res = db_query($sql);
                  $row = db_fetch_assoc($res);
                  $sql = "INSERT INTO accounts
                      SELECT accounts_deleted.*
                      FROM accounts_deleted WHERE accounts_deleted.acctid = ".$_GET['userid'];
              db_query($sql);
              $sql = "DELETE FROM accounts_deleted WHERE acctid='$_GET[userid]'";
              db_query($sql);
              output( db_affected_rows()." utente ripristinato.");
              addnews("`#{$row['name']} è stato ricostruito dagli dei.");
              debuglog("`0ripristina il personaggio cancellato ".$row['name']);
              report(2,"`&Ripristinato personaggio","`\$".$session['user']['name']." ha ripristinato il personaggio ".$row['name'],"provvedimenti");

        }
}elseif ($_GET[op]==""){
    if (isset($_GET['guilty'])) {
        $_GET['page']=0;
        $where="acctid=".$_GET[guilty];
    }
    if (isset($_GET['page'])){
        $order = "acctid";
        if ($_GET['sort']!="") $order = $_GET['sort'];
        $offset=(int)$_GET['page']*100;
        $sql = "SELECT acctid,login,name,level,laston,gentimecount,lastip,uniqueid,emailaddress FROM accounts ".($where>""?"WHERE $where ":"")."ORDER BY ".$order." LIMIT $offset,100";
        $result = db_query($sql) or die(db_error(LINK));
        output("<table>",true);
        output("<tr>
        <td>Ops</td>
        <td><a href='user.php?sort=acctid&page=".$_GET['page']."'>Acctid</a></td>
        <td><a href='user.php?sort=login&page=".$_GET['page']."'>Login</a></td>
        <td><a href='user.php?sort=name&page=".$_GET['page']."'>Nome</a></td>
        <td><a href='user.php?sort=level&page=".$_GET['page']."'>Liv</a></td>
        <td><a href='user.php?sort=laston&page=".$_GET['page']."'>Laston</a></td>
        <td><a href='user.php?sort=gentimecount&page=".$_GET['page']."'>Hits</a></td>
        <td><a href='user.php?sort=lastip&page=".$_GET['page']."'>UltimoIP</a></td>
        <td><a href='user.php?sort=uniqueid&page=".$_GET['page']."'>UltimoID</a></td>
        <td><a href='user.php?sort=emailaddress&page=".$_GET['page']."'>Email</a></td>
        </tr>",true);
        addnav("","user.php?sort=acctid&page=".$_GET['page']);
        addnav("","user.php?sort=login&page=".$_GET['page']);
        addnav("","user.php?sort=name&page=".$_GET['page']);
        addnav("","user.php?sort=level&page=".$_GET['page']);
        addnav("","user.php?sort=laston&page=".$_GET['page']);
        addnav("","user.php?sort=gentimecount&page=".$_GET['page']);
        addnav("","user.php?sort=lastip&page=".$_GET['page']);
        addnav("","user.php?sort=uniqueid&page=".$_GET['page']);
        addnav("","user.php?sort=emailaddress&page=".$_GET['page']);
        $rn=0;
        $countrow = db_num_rows($result);
        for ($i=0; $i<$countrow; $i++){
        //for ($i=0;$i<db_num_rows($result);$i++){
            $row=db_fetch_assoc($result);
            $laston=round((strtotime(date("r"))-strtotime($row[laston])) / 86400,0)." giorni";
            if (substr($laston,0,2)=="1 ") $laston="1 day";
            if (date("Y-m-d",strtotime($row[laston])) == date("Y-m-d")) $laston="Oggi";
            if (date("Y-m-d",strtotime($row[laston])) == date("Y-m-d",strtotime(date("r")."-1 day"))) $laston="Ieri";
            if ($loggedin) $laston="Ora";
            $row[laston]=$laston;
            if ($row[$order]!=$oorder) $rn++;
            $oorder = $row[$order];
            output("<tr class='".($rn%2?"trlight":"trdark")."'>",true);

            output("<td>",true);
            output("[<a href='user.php?op=edit&userid=$row[acctid]'>Edita</a>|".
                "<a href='user.php?op=del&userid=$row[acctid]' onClick=\"return conferma('Sei sicuro di voler cancellare questo utente?');\">Del</a>|".
                "<a href='user.php?op=setupban&userid=$row[acctid]'>Ban</a>|".
                "<a href='user.php?op=debuglog&userid=$row[acctid]'>Log</a>|".
                "<a href='nocomment.php?op=mute&userid=$row[acctid]'>Mute</a>|".
                "<a href='nocomment.php?op=pm&userid=".$row['acctid']."'>PM</a>]",true);
            addnav("","user.php?op=edit&userid=$row[acctid]");
            addnav("","user.php?op=del&userid=$row[acctid]");
            addnav("","user.php?op=setupban&userid=$row[acctid]");
            addnav("","user.php?op=debuglog&userid=$row[acctid]");
            addnav("","nocomment.php?op=mute&userid=$row[acctid]");
            addnav("","nocomment.php?op=pm&userid=$row[acctid]");
            output("</td><td>",true);
            output($row[acctid]);
            output("</td><td>",true);
            output($row[login]);
            output("</td><td>",true);
            output($row[name]);
            output("</td><td>",true);
            output($row[level]);
            output("</td><td>",true);
            output($row[laston]);
            output("</td><td>",true);
            output($row[gentimecount]);
            output("</td><td>",true);
            output($row[lastip]);
            output("</td><td>",true);
            output($row[uniqueid]);
            output("</td><td>",true);
            output($row[emailaddress]);
            output("</td>",true);
            $gentimecount+=$row[gentimecount];
            $gentime+=$row[gentime];

            output("</tr>",true);
        }
        output("</table>",true);
        output("Total hits: $gentimecount`n");
        output("Total CPU time: ".round($gentime,3)."s`n");
        output("Il tempo medio di generazione della pagina è ".round($gentime/max($gentimecount,1),4)."s`n");
    }
}
page_footer();
?>