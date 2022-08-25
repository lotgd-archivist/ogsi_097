<?php
require_once("common.php");
require_once("common2.php");
isnewday(3);

if ($_GET[op]=="save"){
    if ($_POST[blockdupeemail]==1) $_POST[requirevalidemail]=1;
    if ($_POST[requirevalidemail]==1) $_POST[requireemail]=1;
    reset($_POST);
    while (list($key,$val)=each($_POST)){
        savesetting($key,stripslashes($val));
        output("Setting $key to ".stripslashes($val)."`n");
    }
}

page_header("Settaggi del Gioco");
addnav("G?Torna alla Grotta","superuser.php");
addnav("M?Torna alla Mondanità","village.php");
addnav("O?Aggiusta Offset Nuovo Giorno","oraset.php");
addnav("",$REQUEST_URI);
//$nextnewday = ((gametime()%86400))/4 ; //abs(((86400- gametime())/getsetting("daysperday",4))%86400 );
//echo date("h:i:s a",strtotime("-$nextnewday seconds"))." (".($nextnewday/60)." minutes) ".date("h:i:s a",gametime()).gametime();
$time = (strtotime(date("1981-m-d H:i:s",strtotime(date("r")."-".getsetting("gameoffsetseconds",0)." seconds"))))*getsetting("daysperday",4) % strtotime("1981-01-01 00:00:00");
$time = gametime();
/*
$tomorrow = strtotime(date("Y-m-d H:i:s",$time)." + 1 day");
$tomorrow = strtotime(date("Y-m-d 00:00:00",$tomorrow));
$today = strtotime(date("Y-m-d 00:00:00",$time));
$dayduration = ($tomorrow-$today) / getsetting("daysperday",4);
$secstotomorrow = $tomorrow-$time;
$secssofartoday = $time - $today;
$realsecstotomorrow = $secstotomorrow / getsetting("daysperday",4);
$realsecssofartoday = $secssofartoday / getsetting("daysperday",4);
*/
$tomorrow = mktime(0,0,0,date('m',$time),date('d',$time)+1,date('Y',$time));
$today = mktime(0,0,0,date('m',$time),date('d',$time),date('Y',$time));
$dayduration = ($tomorrow-$today) / getsetting("daysperday",4);
$secstotomorrow = $tomorrow-$time;
$secssofartoday = $time - $today;
$realsecstotomorrow = round($secstotomorrow / getsetting("daysperday",4),0);
$realsecssofartoday = round($secssofartoday / getsetting("daysperday",4),0);
if ($session['user']['superuser']>3){
   output("`n`GCurrent server time: ".date("Y-m-d H:i:s")."`n`gCurrent game time: ".date("Y-m-d H:i:s",$time));
   output("`n`RTomorrow is ".date("Y-m-d H:i:s",$tomorrow).", $secstotomorrow secs to tomorrow which is $realsecstotomorrow real secs.`n");
   output("`rCurrent server time: ".date("h:i:s a")."`n`XCurrent game time: ".date("h:i:s a",$time)."`n");
   output("`fNext NewDay at ".date("h:i:s a",strtotime(date("r")."+$realsecstotomorrow seconds")).".`0`n`n");
}
$enum="enum";
for ($i=0;$i<=86400;$i+=900){
    $enum.=",$i,".((int)($i/60/60)).":".($i/60 %60)."";
}
$setup = array(
    "Game Setup,title",
    "loginbanner"=>"Login Banner (under login prompt: 255 chars)",
    "soap"=>"Pulisci post utenti (filtra le parolacce e divide le parole oltre i 45 caratteri),bool",
    "maxcolors"=>"Max # di cambio colori usabili in un commento,int",
    "gameadminemail"=>"Email Admin",
    "autofight"=>"Abilita AutoFight,bool",
    "paypalemail"=>"Indirizzo Email dell'account PayPal dell'Admin",
    "defaultlanguage"=>"Linguaggio di Default,enum,en,Inglese,dk,Danese,de,Tedesco,es,Spagnolo,fr,Francese,it,Italiano",
    "automaster"=>"Maestri cercano gli allievi che bigiano,bool",
    "multimaster"=>"Il Maestro puo essere affrontato più volte in un giorno?,bool",
    "topwebid"=>"ID for Top Web Games (se sei registrato),int",
    "beta"=>"Abilita caratteristica beta per tutti i players?,bool",
    "yomtoemail"=>"Consenti ai giocatori di ricevere su email copia dei pm ricevuti?,bool",

    "Creazione Account,title",
    "superuser"=>"Livello default per SuperUtenti,enum,0,Giorni standard per giorno di calendario,1,Giorni illimitati per giorno di calendario,2,Admin (creature e sbeffeggi),3,Admin Totale",
    "newplayerstartgold"=>"Quantità d'oro con cui parte un nuovo giocatore,int",
    "requireemail"=>"Richiedi agli utenti di inserire indirizzo mail,bool",
    "requirevalidemail"=>"Richiedi agli utenti di validare il loro indirizzo mail,bool",
    "blockdupeemail"=>"Un account per ogni indirizzo mail,bool",
    "spaceinname"=>"Consenti spazi nel nome utente,bool",
    "selfdelete"=>"Consenti ai giocatori di cancellare i loro personaggi,bool",

    "Nuovo Giorno,title",
    "fightsforinterest"=>"Max numero di combattimenti rimanenti per guadagnare gli interessi?,int",
    "maxinterest"=>"Max Tasso di Interesse (%),int",
    "mininterest"=>"Min Tasso di Interesse (%),int",
    "daysperday"=>"Giorni nel gioco per ogni giorno reale,int",
    "specialtybonus"=>"Usi giornalieri extra in aree speciali,int",

    "Foresta,title",
    "turns"=>"Combattimenti Foresta al giorno,int",
    "dropmingold"=>"Creature della Foresta droppano almeno 1/4 dell'oro max,bool",
    "lowslumlevel"=>"Livello minimo per cui combattimento perfetto da un turno extra,int",
    //Maximus - Inizio
    "limite_zaino"=>"Limite Materiali e Ricette nello Zaino,int",
    //Maximus - Fine

    "Ricercati (Taglie),title",
    "bountymin"=>"Quantità minima per livello dell'obbiettivo per la taglia,int",
    "bountymax"=>"Quantità massima per livello dell'obbiettivo per la taglia,int",
    "bountylevel"=>"Livello minimo giocatore per diventare un obbiettivo di taglia,int",
    "bountyfee"=>"Percentuale di taglia trattenuta da Dag Durnick,int",
    "maxbounties"=>"Quante taglie può mettere un player al giorno,int",

    "Settaggi Banca,title",
    "borrowperlevel"=>"Max oro Player può prendere in prestito per livello (val * livello per max),int",
    "allowgoldtransfer"=>"Consenti il trasferimento d'oro tra player,bool",
    "transferperlevel"=>"Max oro un player può ricevere da un trasferimento (val * level),int",
    "mintransferlev"=>"Livello minimo un player (0 DK) deve avere per trasferire oro,int",
    "transferreceive"=>"Quantità totale un player può ricevere in un giorno,int",
    "maxtransferout"=>"Quantità un player può trasferire agli altri (val * livello),int",
    "innfee"=>"Tassa per pagamento veloce stanza in locanda (x or x%),int",

    "Settaggi Mail,title",
    "mailsizelimit"=>"Dimensione limite per messaggio,int",
    "inboxlimit"=>"Limite # di messaggi nella inbox,int",
    "oldmail"=>"Cancella automaticamente i messaggi vecchi di (giorni),int",

    "PvP,title",
    "hasegg"=>"L'attuale proprietario dell'uovo d'Oro è (Account-ID - 0=Nessuno),int",
    "hasblackegg"=>"L'attuale proprietario dell'uovo Nero è (Account-ID - 0=Nessuno),int",
    "pvp"=>"Abilita PvP,bool",
    "pvpday"=>"Combattimenti PvP al giorno,int",
    "pvpimmunity"=>"Giorni in cui i nuovi giocatori sono al sicuro dal PvP,int",
    "pvpminexp"=>"Esperienza sotto la quale i giocatori sono salvi dal PvP,int",
    "pvpattgain"=>"% di esperienza della vittima che l'attaccante guadagna vincendo,int",
    "pvpattlose"=>"%di esperienza l'attaccante perde se sconfitto,int",
    "pvpdefgain"=>"% dell'esperienza dell'attaccante che vince chi difende vincendo,int",
    "pvpdeflose"=>"%di esperienza chi difende perde se sconfitto,int",
 
    "Tenute,title",
	"housesfree"=>"Abilita attacchi alle Tenute,bool",

    "Reincarnazioni,title",
    "puntipermbase"=>"Punti attacco e difesa permanenti massimi che è possibile avere per i non reincarnati (-1 per illimitati),int",
    "puntipermreinc"=>"Punti attacco e difesa permanenti massimi che si guadagnano per ogni reincarnazione (se settato limite),int",

    "Carriere,title",
    "mastini"=>"Incantesimi attivi contro i mastini,bool",
    "maghi_incanta_ogg"=>"I maghi possono incantare oggetti,bool",
    "blocco_valore"=>"Un solo incantamento di tipo valore è consentito,bool",
    "dk_sette"=>"DK necessari per essere conteggiati nello scontro delle sette,int",

    "Scadenza Contenuti,title",
    "expirecontent"=>"Giorni da mantenere commenti e notizie?  (0 = infinito),int",
    "expirecontentgdr"=>"Giorni da mantenere commenti stanze GDR?  (0 = infinito),int",
    "expiretrashacct"=>"Giorni da mantenere gli account mai utilizzati? (0 = infinite),int",
    "expirenewacct"=>"Giorni da mantenere gli account di livello 1 (0 DK)? (0 =infinite),int",
    "expireoldacct"=>"Giorni da mantenere tutti gli altri account? (0 = infinite),int",
    "LOGINTIMEOUT"=>"Secondi di inattività prima di scollegare un player,int",

    "Informazioni Utili,title",
    "Ultimo Nuovo Giorno: ".date("h:i:s a",strtotime(date("r")."-$realsecssofartoday seconds")).",viewonly",
    "Prossimo Nuovo Giorno: ".date("h:i:s a",strtotime(date("r")."+$realsecstotomorrow seconds")).",viewonly",
    "Ora attuale di gioco: ".getgametime().",viewonly",
    "Durata Giorno: ".($dayduration/60/60)." hours,viewonly",
    "Ora Attuale del Server: ".date("Y-m-d h:i:s a").",viewonly",
    "gameoffsetseconds"=>"Tempo reale per il prossimo Nuovo Giorno,$enum",

    "LoGDnet Setup (LoGDnet does require PHP to have file wrappers enabled!!),title",
    "logdnet"=>"Register with LoGDnet?,bool",
    "serverurl"=>"Server URL",
    "serverdesc"=>"Descrizione Server (255 chars)",
    "logdnetserver"=>"Master LoGDnet Server (default http://lotgd.net/)",

    "End Game Setup,title"
    );

if ($_GET[op]==""){
    loadsettings();
    output("<form action='configuration.php?op=save' method='POST'>",true);
    addnav("","configuration.php?op=save");
    showform($setup,$settings);
    output("</form>",true);
}
page_footer();
?>