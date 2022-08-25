<?php
/**ss**********************ss***************
/ Housing Script (houses.php)
LEGENDGARD FORK - ver 0.95 - brought to you by Anpera and Lonestrider

additional credits:
/ Translation support and first English Translation of this script by LonnyL
/ Further support and suggestions by Robert (Madnet) and Talisman (DragonPrime)

/ version 0.95  (LEGENDGARD FORK from Anpera's 0.93) by Strider
/ 4.27.04  (1st legendgard Edition) -scs-

 Additions for 0.95 (strider)
 - Completely new storyline for the houses. Changed it to "Estates" instead of houses in certain parts.
 - Adjusted the install instructions and polished off the translations
 - Fixed the navigation so it won't confuse players.
 - Gave Thieves an advantages in robbing the house.
 - Fixed a bug where it'll give generic names to estates
 - Created Architectural Studio
 - Added Superuser Editor for houses
 - Added patch to the game so now the houses have no post limit, but your game continues to have one.

______________________________________________________________________________________
/ About: Make Estates for storing gold and gems and for a safe place to sleep (logout)
   Features: Build house, sell house, buy house, share house with others, private chat-area with no limits, PvP

----install-instructions--------------------------------------------------
/ -SS-SCALE: ****** :expert level - not for the squimish: -
 Version 0.95 is built for LOTGD 0.9.7

 This is an involved script with many steps to install it. If you're not comfortable editing
 LotGD, you should be careful. Refer to the install instructions to walk you though.

 You should find the most current version of this script with install instructions and forums to
 help you at DRAGONPRIME - http://dragonprime.cawsquad.net
______________________________________________________________________________________

/ Version History:

* Version: 0.93 (anpera) & (LonnyL)
* Data: March 2004
* Author: Anpera / Created Houses / First English Translation by LonnyL
* Enamel: logd@anpera.de

**ss**************************ss************/
// -Originally by: Anpera   [created in german]
// -English translation from original German Lonny Luberts and Strider
// -Liberties against the translation taken by a quill from Strider's Desk

// -Contributors: Anpera, Lonnyl, Robert, Strider and Talisman
// April 2004  - Legendgard Script Release
/* ******* ottobre 2006 **********
Xtramus: aggiunto sistema di controllo antimultiaccount e punti cattiveria in caso di attacchi continui. (non mi va di scrivere in inglese! :p
**************************** */

require_once("common.php");
require_once("common2.php");
$session['user']['locazione'] = 194;
manutenzione(getsetting("manutenzione",3));
function getStatusFromId($id) {
     $query="select attacco from houses where houseid=$id";
     $res= db_query($query) or die(db_error(LINK));
     $r= db_fetch_assoc($res);
     return getStatus($r['attacco']);
}
function getStatus($tempo) {
     $stati= Array(
          0=> Array(
                    stato=>"`@Solida e robusta`0",
                    descr=>"`@Era da un pezzo che questa casa non veniva attaccata! Nessuno ti dirà nulla!`0",
                    cattiveria=>0,
                    minsec=>0
                    ),
          1=> Array(
                    stato=>"`^Ammaccata`0",
                    descr=>"`^Sapevi bene che sfruttare le debolezze della casa avrebbe fruttato! Sei proprio furbo!`0",
                    cattiveria=>5,
                    minsec=>3600*36
                    ),
          2=> Array(
                    stato=>"`%In riparazione`0",
                    descr=>"`%Ma sarà stato giusto attaccare una tenuta già in difficoltà?`0",
                    cattiveria=>10,
                    minsec=>3600*18
                    ),
          3=> Array(
                    stato=>"`4Completamente distrutta`0",
                    descr=>"`4Poverini... è stato proprio da farabutti approfittare di una situazione del genere!`0",
                    cattiveria=>20,
                    minsec=>3600*6
                    ),
          4=> Array(
                    stato=>"`\$Sorvegliata dallo sceriffo`0",
                    descr=>"Noti lo sceriffo che sorveglia la porta!! Ma fortunatamente riesci a sfuggirgli!!",
                    cattiveria=>1000,
                    minsec=>3600*3
                    )
          );
     //$query="select attacco from houses where houseid=$id";
     //$res= db_query($query) or die(db_error(LINK));
     //$tempo= db_fetch_assoc($res);
     //tempo dall'utlimo attacco
     $sectrasc= time()-$tempo;
     for($i=count($stati)-1; $i>=0; $i--) {
          if($sectrasc<$stati[$i]['minsec'] && $tempo!=0) {
               return $stati[$i];
          }
     }
     return $stati[0];

}
function getUserKey($user, $house) {
    $chiaveSql = "SELECT * FROM items WHERE owner=$user AND class='Key' AND value1=$house ORDER BY value2 DESC";
    $chiaveResult = db_query($chiaveSql) or die(db_error(LINK));
    $chiave = db_fetch_assoc($chiaveResult);
    return $chiave;
}

function getGemsInHouse($house) {
    $gemmeSql = "SELECT SUM(gems) AS gemmetotali FROM items WHERE class='Key' AND value1=$house GROUP BY value1";
    $gemmeResult =  db_query($gemmeSql) or die(db_error(LINK));
    $gemme = db_fetch_assoc($gemmeResult);
    return $gemme[gemmetotali];
}

addcommentary();
checkday();

// base values for pricing and chest size:
$goldcost=60000;
$gemcost=90;
// all other values are controlled by banksettings

//need needed v
if ($session[user][slainby] !=""){
    page_header("Sei stato ucciso!");
    output("`\$Sei stato ucciso ".$session[user][killedin]."`$ da `%".$session[user][slainby]."`$ e derubato di tutto l'oro che avevi con te.
            Hai perso anche il 5% di esperienza. Pensi sia il caso di prendersi una rivincita?");
    addnav("Altro",$REQUEST_URI);
    $session[user][slainby] ="";
    page_footer();
}
// ^
page_header("Le Proprietà Reali");
// $victory=0;

if ($_GET['op'] =="newday"){
    $ident_arma=array();
	$ident_arma = identifica_arma();
	$articoloarma = $ident_arma['articolo'];
    $pugni = $ident_arma['pugni'];
    $ident_armatura=array();
	$ident_armatura = identifica_armatura();
	$articoloarmatura = $ident_armatura['articolo'];
    $tshirt = $ident_armatura['tshirt'];
    output("`n`2Ti risvegli nella tua camera da letto, ben riposat".($session[user][sex]?"a":"o")." e pront".($session[user][sex]?"a":"o")." per affrontare una nuova giornata.");
    if (($tshirt) and ($pugni)) {
    	output("`nQuindi ti alzi dal tuo giaciglio indossando $articoloarmatura `#" . $session['user']['armor'] . "`2  e armat".($session[user][sex]?"a":"o")." solamente de$articoloarma `#" . $session['user']['weapon'] . "`2 vai in cerca di nuove avventure.`0");
	}else{
		if ($tshirt) {
			output("`nQuindi ti alzi dal tuo giaciglio e indossando solamente $articoloarmatura `#" . $session['user']['armor'] . "`2 e armat".($session[user][sex]?"a":"o")." con $articoloarma `#" . $session['user']['weapon'] . "`2, ti avvii alla ricerca di nuove avventure.`0");
		}else{
			if ($pugni) {
				output("`nQuindi ti alzi dal tuo giaciglio, fischiettando indossi $articoloarmatura `#" . $session['user']['armor'] . "`2 e armat".($session[user][sex]?"a":"o")." solamente de$articoloarma `#" . $session['user']['weapon'] . "`2 vai in cerca di nuove avventure.`0");
			}else{
    			output("`nQuindi ti alzi dal tuo giaciglio, fischiettando indossi $articoloarmatura `#" . $session['user']['armor'] . "`2, ti armi con $articoloarma `#" . $session['user']['weapon'] . "`2 e ti avvii in perfetto assetto da guerra alla ricerca di nuove avventure.`0");
    		}
    	}
    }
    $session[user][location] =0;
    $session['user']['specialinc'] = "";
    $sql = "UPDATE items SET hvalue = 0 WHERE hvalue>0 AND owner =".$session[user][acctid]." AND class='Key'";
    db_query($sql) or die(sql_error($sql));
    addnav("Notizie Giornaliere","news.php");
    addnav("Le Proprietà Reali","houses.php?op=enter");
    addnav("Torna al Villaggio","village.php");
}elseif ($_GET['op'] =="build"){
    if ($_GET[act] =="start"){
        $sql = "INSERT INTO houses (owner,status,gold,gems,housename) VALUES (".$session[user][acctid].",0,0,0,'Feudo ".$session[user][login]."')";
        db_query($sql) or die(db_error(LINK));
        if (db_affected_rows(LINK)<=0) redirect("houses.php");
        $sql = "SELECT * FROM houses WHERE status = 0 AND owner =".$session[user][acctid]." ORDER BY houseid DESC";
        $result = db_query($sql) or die(db_error(LINK));
        $row = db_fetch_assoc($result);
        $session[user][house] =$row[houseid];
        output("`@Entri nella ditta di costruzioni e ristrutturazioni diRoark&StoneFist. Mastro Roark è un massiccio nano con una autoritaria presenza.
                Le sue mani massicce frugano tra decine di progetti e non puoi fare a meno di pensare che prode guerriero sarebbe se non fosse un artista.
                Alza per un attimo lo sguardo su di te, mentre la sua mente pensa ai dettagli dei progetti. Ti presenti velocemente e gli dici del tuo desiderio
                di costruire una tua proprietà privata. Casualmente, fruga su una scrivania vicina ed estrae una mappa ed alcuni moduli da compilare.`n`n");
        output("`6Mastro Roark`5 ti dice `#\"`3 Sembra che costruirai sul lotto `^$row[houseid]`3. Proprio un bell'appezzamento di terreno hai scelto.
                Solida roccia per costruire solide fondamenta. Inizieremo i lavori dopo che ci darai l'ordine, ma dovrai versare almeno un anticipo sulla proprietà
                prima che inizino i lavori.`#\"`5`n" );
        output("`0<form action=\"houses.php?op=build&act=build2\" method ='POST'>",true);
        output("`nQuale sarà il nome della tua proprietà  <input name = 'housename' maxlength='25'>`n",true);
        output("`nQuanti pezzi d'oro desideri investire nella tua proprietà <input type = 'gold' name = 'gold'>`n",true);
        output("`nQuante gemme desideri investire nella tua proprietà <input type = 'gems' name = 'gems'>`n",true);
        output("<input type = 'submit' class = 'button' value ='Costruisci la Proprietà'>",true);
        addnav("","houses.php?op=build&act=build2");
    }elseif ($_GET[act] =="build2"){
        $sql = "SELECT * FROM houses WHERE status = 0 AND owner =".$session[user][acctid]." ORDER BY houseid DESC";
        $result = db_query($sql) or die(db_error(LINK));
        $row = db_fetch_assoc($result);
        $paidgold=(int)$_POST['gold'];
        if ($_POST['housename']>""){
            $housename=stripslashes($_POST['housename']);
        }else{
            $housename=stripslashes($row[housename]);
        }
        $paidgems=(int)$_POST['gems'];
        if ($session[user][gold]<$paidgold || $session[user][gems]<$paidgems){
            output("`@Comunichi a Mastro Architetto Roark che desideri investire `^ $paidgold `@ oro e `^ $paidgems `@gemme sulla tua abitazione per il momento. Con uno
                    schiocco della lingua e un tocco di sdegno, fa schioccare le dita e gli operai iniziano ad abbandonare il lotto che hai scelto.`n`n");
            output("`6Roark`5 dice `#\"`3 Sappiamo che non ti puoi permettere quelle cifre. Non pensare che lavoreremo a gratis! Ci devi pagare per i
                    nostri servigi.`#\"`5`n" );
            addnav("Fai un'altra offerta","houses.php?op=build");
            addnav("Ritorna alla Proprietà","houses.php");
        }elseif ($session[user][turns]<1){
            output("`@Ti avvicini a Mastro Architetto Roark con tutta una serie di idee, ma prima che tu possa iniziare, non riesci a soffocare
                    uno sbadiglio. Mastro Roark arrotola i suoi progetti e scuote la testa. Semplicemente non hai le forze per lavorare alla tua Proprietà oggi.");
        }elseif ($paidgold<0 || $paidgems<0){
            $goldloss = $session['user']['gold']*.1;
            $lifeloss = $session['user']['hitpoints']*.5;
            output("`@Ti accordi con gli operai per trovarvi all'alba per lavorare sulla tua proprietà. Mentre tutti attendono
                    che tu versi l'anticipo a Mastro Architetto Roark, tu ridi e gli offri un bel `3NIENTE `@per il suo talento. Mentre stai li in piedi ridendo
                    come un idiota, Roark ti colpisce nello stomaco, quindi ti spinge in una fossa. I suoi operai si avvicinano, dandoti un calcio per avergli fatto
                    perdere il loro tempo. `n`nMentre sei li sdraiato a ricevere i calci, perdi `^$lifeloss HP `@e `^$goldloss `@pezzi d'oro cadono dalle tue tasche.
                    Gli operai li raccolgono velocemente.");
            $session['user']['hitpoints'] =-$lifeloss;
            $session['user']['gold'] =-$goldloss;
            $session['user']['charm'] == 1;
            debuglog("perde $goldloss pezzi d'oro, un sacco di punti fascino e $lifeloss HP per aver tentato di imbrogliare l'Architetto.");
        }else{
            output("`@`@Ti accordi con gli operai per trovarvi all'alba per lavorare sulla tua proprietà. Tutti attendono che tu versi
                    l'anticipo a Mastro Architetto Roark, prima di inziare a lavorare. Velocemente dai al Mastro Nano `^$paidgold pezzi d'oro `@e `^$paidgems gemme `@per il lavoro. Il robusto nano conta l'oro e
                    annuisce con il capo. I corni squillano attraverso il campo e gli operai si riuniscono come un esercito. I lavori hanno inizio con un ritmo frenetico mentre i nani cesellano la pietra ed i
                    maghi sussurrano incantesimi per modellare le strutture come da te richiesto. Durante il giorno, osservi ammirato Mastro Roark mentre dirige la costruzione della tua Proprietà.`n`n");
            $row[gold] +=$paidgold;
            $session[user][gold] -=$paidgold;
            output("`nPerdi un turno per supervisionare la costruzione della tua Proprietà.`n`n");
            $session[user][turns]--;
            if ($row[gold]>$goldcost){
                output("`nHai pagato l'intera cifra d'oro richiesta. Non è necessario pagare ulteriori pezzi d'oro.");
                $session[user][gold] +=$row[gold]-$goldcost;
                $row[gold] =$goldcost;
            }
            $row[gems] +=$paidgems;
            $session[user][gems]-=$paidgems;
            if ($row[gems]>$gemcost){
                output("`nHai pagato l'intera quantità di gemme richiesta. Nessun'altra gemma è dovuta.");
                $session[user][gems]+=$row[gems]-$gemcost;
                $row[gems]=$gemcost;
            }
            $goldtopay=$goldcost-$row[gold];
            $gemstopay=$gemcost-$row[gems];
            $done=round(100-((100*$goldtopay/$goldcost)+(100*$gemstopay/$gemcost))/2);

            if ($row[gems]<=$gemcost || $row[gold]<=$goldcost){
                output("`nLa tua casa è pronta al`\$ $done %`@. Devi ancora pagare `^$goldtopay`@ Pezzi D'oro e `#$gemstopay `@Gemme prima di poter procedere.");
                //it makes no sense to show this line if the work is completed. I added an IF < cost --strider
            }
            if ($row[gems]>=$gemcost && $row[gold]>=$goldcost){
                output("`n`n`nGli operai continuano a lavorare aggiungendo gli ultimi ritocchi fino al tramonto. Finalmente, Mastro Roark ti consegna un set di chiavi
                        della tua nuova proprietà . . .  \"`&$housename`@\".`n Hai `b10`b chiavi, 9 delle quali puoi dare ad altri giocatori.");
                $row[gems] =0;
                $row[gold] =0;
                $session[user][housekey] =$row[houseid];
                $row[status] =1;
                addnews("`2".$session[user][name]."`3 ha completato la costruzione di `2$row[housename]`3 tra le Tenute Reali.");
                debuglog("ha costruito una nuova tenuta (".$row['housename'].", N°".$row['houseid'].")");
                for ($i=1;$i<=10;$i++){
                    $sql = "INSERT INTO items (name,owner,class,value1,value2,gold,gems,description) VALUES ('House key',".$session[user][acctid].",'Key',$row[houseid],$i,0,0,'Key for house number $row[houseid]')";
                    db_query($sql);
                    if (db_affected_rows(LINK)<=0) output("`\$Errore`^: il tuo inventario non può essere aggiornato! Avvisa Admin Luke per favore. ");
                }
            }
            $sql = "UPDATE houses SET gold = $row[gold],gems=$row[gems],housename='".addslashes($housename)."',status =".(int)$row[status]." WHERE houseid = $row[houseid]";
            db_query($sql);
        }
    }else{
        if ($session[user][housekey]>0){
            output("`@Possiedi già una Tenuta. Se desideri costruire una nuova Proprietà devi prima vendere la tua Proprietà attuale.");
            ////change this line if you wish to change the "Player Requirements" of house ownership/////
        }elseif ($session[user][reincarna]<1){
            ////ss/////////////////////////////////////////////////////////////////////////////////////
            output("`@Solamente coloro che si sono reincarnati possono costruire. Devi guadagnarti il diritto di entrare nelle Tenute Reali. Forse puoi
                    mendicare un posto sul divano di qualcun altro mentre sali di livello.");
        }elseif ($session[user][turns]<1){
            output("`@Sei troppo esaust".($session[user][sex]?"a":"o")." per commissionare la costruzione di una casa. Dovrai attendere fino a domani.");
        }elseif ($session[user][house]>0){
            $sql = "SELECT * FROM houses WHERE status = 0 AND owner =".$session[user][acctid]." ORDER BY houseid DESC";
            $result = db_query($sql) or die(db_error(LINK));
            $row = db_fetch_assoc($result);
            output("`@Ti avvicini al sito di costruzione della tua nuova tenuta sul lotto numero `3$row[houseid]`@.`n`n");
            $goldtopay=$goldcost-$row[gold];
            $gemstopay=$gemcost-$row[gems];
            $done=round(100- ( (100*$goldtopay/$goldcost) + (100*$gemstopay/$gemcost))/2);
            output("La tua Tenuta è completata al `\$$done%`@. Devi ancora pagare `^$goldtopay`@ pezzi d'oro e `#$gemstopay `@gemme per completarla.
                    `nDesideri investire ancora qualcosa nella tua Tenuta? `n`n");
            output("`0<form action=\"houses.php?op=build&act=build2\" method ='POST'>",true);
            output("Investi Risorse: `nQuanto oro? <input type = 'gold' name = 'gold'>`n",true);
            output("`nQuante gemme? <input type = 'gems' name = 'gems'>`n",true);
            output("<input type = 'submit' class = 'button' value = 'Costruisci'>",true);
            addnav("","houses.php?op=build&act=build2");
        }else{
            output("`@In qualità di eroe del villaggio, hai guadagnato il diritto di sviluppare la tua proprietà tra le Tenute Reali.");
            output("Per costruire la tua Proprietà avrai bisogno di `^$goldcost Pezzi d'Oro`@ e `#$gemcost Gemme`@. Non devi pagare in un'unica soluzione, ma puoi dilazionare i pagamenti. ");
            output("La velocità con cui finirai la casa, dipende dalla frequenza e dalla consistenza dei pagamenti. `n");
            output("Puoi viverci da solo o condividerla con altri guerrieri. La tenuta ti darà un luogo più sicuro dove riposare ed una cassaforte dove riporre le tue ricchezze.");
            output("Una volta iniziata la costruzione, deve essere completata. `n`nDesideri iniziare la costruzione di una casa ?");
            addnav("Crea una Tenuta","houses.php?op=build&act=start");
        }
    }
    addnav("Torna alle Tenute Reali","houses.php");
    addnav("Torna al Villaggio","village.php");
}elseif ($_GET['op'] =="breakin"){
    If(!$_GET[id]){
        //$sql = "SELECT * FROM houses WHERE status = 1 AND owner<>".$session[user][acctid]." ORDER BY houseid ASC";
        $sql = "SELECT h.*,a.dio FROM houses h LEFT JOIN accounts a ON a.acctid=h.owner
                WHERE h.status = 1 AND h.owner<>".$session['user']['acctid']." ORDER BY h.houseid ASC";
        output("`c`b`^Irruzione`b`c`0`n");
        output("`@Ti guardi in giro e scegli una casa abitata per un'irruzione. ");
        output("Sfortunatamente non puoi sapere quanti e quanto forti sono gli abitanti della casa. Questo tipo di irruzione può essere molto rischiosa. `nQuale hai scelto ? `n`n");
        if ($session['user']['pvpflag'] =="2013-10-06 00:42:00") output("`n`&(Hai comprato l'immunità PvP. Sarà annullata se continui!)`0`n`n");
        output("<table cellspacing = 0 cellpadding = 2 align = 'center'><tr><td>`bCasa#. `b</td><td>`bNome`b</td><td>`bProprietario`b</td><td>Stato della porta</td><td>Fede Proprietario</td></tr>",true);
        $result = db_query($sql) or die(db_error(LINK));
        if (db_num_rows($result) ==0){
            output("<tr><td colspan = 4 align ='center'>`&`iAttualmente non ci sono case abitate`i`0</td></tr>",true);
        }else{
            $countrow = db_num_rows($result);
            for ($i=0; $i<$countrow; $i++){
            //for ($i=0;$i<db_num_rows($result);$i++){
                $row = db_fetch_assoc($result);
                $bgcolor= ($i%2==1?"trlight":"trdark");
                $statoporta= getStatus($row['attacco']);
                $link= ($statoporta['cattiveria']==1000)? $row['housename'] : "<a href='houses.php?op=breakin&id=$row[houseid]'>$row[housename]</a>";
                output("<tr class='$bgcolor'><td align = 'right'>$row[houseid]</td><td>$link</td><td>",true);
                $sql= "SELECT name FROM accounts WHERE acctid = $row[owner] ORDER BY acctid DESC";
                $result2 = db_query($sql) or die(db_error(LINK));
                $row2 = db_fetch_assoc($result2);
                output("$row2[name]</td><td>".$statoporta['stato']. "</td><td align='right'>".$fededio[$row['dio']]."</td></tr>",true);
                addnav("","houses.php?op=breakin&id=$row[houseid]");
            }
        }
        output("</table>",true);
        addnav("Torna alle Tenute","houses.php");
    }else{
        if ($session[user][turns]<1 || $session[user][playerfights]<=0){
            output("`nSei troppo stanco.");
            addnav("Torna alle Tenute","houses.php");
        }
        elseif(ismultiaction("tenute",$_GET['id'])) {
          output("`nHai già attaccato recentemente questa tenuta con uno dei tuoi pg");
          addnav("Torna alle Tenute","houses.php");
        }
        else{
            saveaction("tenute",$_GET['id'],3600);
            output("`2Ti avvicini con cautela alla casa numero $_GET[id].");
            $session[housekey] =$_GET[id];
            // Would query, whether key available!
            $sql = "SELECT id FROM items WHERE class='key' AND owner =".$session[user][acctid]." AND value1=".(int)$_GET[id]." ORDER BY id DESC";
            $result2 = db_query($sql) or die(db_error(LINK));
            $row2 = db_fetch_assoc($result2);
            if (db_num_rows($result2)>0){
                output("Silenziosamente, ti avvicini alla porta d'ingresso della residenza che hai deciso di derubare. Getti uno sguardo a sinistra, per accertarti che non ci sia nessuna presenza ostile
                        che ti impedisca l'ingresso. Quindi, cerchi qualcosa per forzare la porta. Hmmm... forse potresti AVERE LA CHIAVE. ");
                output("`nNon è una vera e propria effrazione se hai la chiave della porta, giusto?");
                addnav("Entra nella Casa","houses.php?op=inside&id=$_GET[id]");
                addnav("Torna al Villaggio","village.php");
            }else{
                //ss// watch Overcome
                output("Gli arnesi da scasso che trasporti attirano l'attenzione di un guardiano che si trova nelle vicinanze ...`n");
                $pvptime = getsetting("pvptimeout",600);
                $pvptimeout = date("Y-m-d H: i: s",strtotime(date("r")."-$pvptime seconds"));
                $days = getsetting("pvpimmunity", 5);
                $exp = getsetting("pvpminexp", 1500);
                $sql = "SELECT acctid,level,hitpoints,login,housekey FROM accounts WHERE
                        (locked=0) AND
                        (alive=1 AND location=2) AND
                        (laston < '".date("Y-m-d H:i:s",strtotime(date("r")."-".getsetting("LOGINTIMEOUT",900)." sec"))."' OR loggedin=0) AND
                        (age > $days OR dragonkills > 0 OR pk > 0 OR experience > $exp) AND
                        (acctid <> ".$session[user][acctid].") AND
                        (pvpflag <> '2013-10-06 00:42:00') AND
                        (pvpflag < '$pvptimeout') ORDER BY hitpoints DESC";
                $result = db_query($sql) or die(db_error(LINK));
                $hp=0;
                $countrow = db_num_rows($result);
                // count chars at home and strongest find
                if($countrow){
                    for ($i=0; $i<$countrow; $i++){
                        $row = db_fetch_assoc($result);
                        $sql = "SELECT value1 FROM items WHERE value1=".(int)$session[housekey]." AND owner=$row[acctid] AND class='key' AND hvalue=".(int)$session[housekey]." ORDER BY id";
                        $result2 = db_query($sql) or die(db_error(LINK));
                        if (db_num_rows($result2)>0){
                            if ($row[hitpoints]>$hp){
                                $hp=(int)$row[hitpoints];
                            }
                        }
                        db_free_result($result2);
                    }
                    //ss// Sorry guys, I want people to be able to rob these places once in a while. I'm going to weaken the guards a little -strider.
                    //Later we can sell them extra protection for their houses.
                    $badguy = array("creaturename"=>"Soldato di Vigilanza Del Quartiere",
                            "creaturelevel"=>$session['user']['level'],
                            "creatureweapon"=>"Spada Corta",
                            "creatureattack"=>$session['user']['attack']*.8,
                            "creaturedefense"=>$session['user']['defence']*.8,
                            "creaturehealth"=>abs($session['user']['maxhitpoints']-$hp) +1,
                            "diddamage"=>0);
                }else{
                    $badguy = array("creaturename"=>"Troll di Vigilanza Del Quartiere",
                            "creaturelevel"=>$session['user']['level'],
                            "creatureweapon"=>"Bastone Chiodato",
                            "creatureattack"=>$session['user']['attack']*.9,
                            "creaturedefense"=>$session['user']['defence']*.9,
                            "creaturehealth"=>abs($session['user']['maxhitpoints']),
                            "diddamage"=>0);
                }
                debuglog("prima di affrontare il guardiano ha ".$session['user']['playerfights']." PvP");
                $session['user']['playerfights'] --;
                $session['user']['badguy'] =createstring($badguy);
                $fight=true;
            }
        }
    }
}elseif($_GET['op'] == "fight"){
    $fight=true;
}elseif($_GET['op'] == "run"){
    output("`% La vigilanza del quartiere non ti lascia scappare! `n");
    $fight=true;
}elseif ($_GET['op'] =="breakin2"){
    // Player overcome
    debuglog("ha superato il vigilante $name ed entra in casa e ha ".$session['user']['playerfights']." PvP");
    //$pvptime = getsetting("pvptimeout",300);
    $pvptime = 1;
    $pvptimeout = date("Y-m-d H:i:s",strtotime(date("r")."-$pvptime seconds"));
    $days = getsetting("pvpimmunity", 5);
    $exp = getsetting("pvpminexp", 1500);
    $sql = "SELECT acctid,name,hitpoints,defence,attack,level,laston,loggedin,login,housekey FROM accounts WHERE
            (locked=0) AND
            (alive=1 AND location=2) AND
            (laston < '".date("Y-m-d H:i:s",strtotime(date("r")."-".getsetting("LOGINTIMEOUT",900)." sec"))."' OR loggedin=0) AND
            (age > $days OR dragonkills > 0 OR pk > 0 OR experience > $exp) AND
            (acctid <> ".$session[user][acctid].") AND
            (pvpflag <> '2013-10-06 00:42:00') AND
            (pvpflag < '$pvptimeout') ORDER BY maxhitpoints DESC";
    $result = db_query($sql) or die(db_error(LINK));
    $athome=0;
    $name="";
    $hp=0;
    // count chars at home and strongest find
    $countrow = db_num_rows($result);
    for ($i=0; $i<$countrow; $i++){
    //for ($i=0;$i<db_num_rows($result);$i++){
        $row = db_fetch_assoc($result);
        $sql = "SELECT value1 FROM items WHERE value1=".(int)$session[housekey]." AND owner=$row[acctid] AND class='key' AND hvalue=".(int)$session[housekey]." ORDER BY id";
        $result2 = db_query($sql) or die(db_error(LINK));
        if (db_num_rows($result2)>0){
            $athome++;
            if ($row[hitpoints]>$hp){
                $hp=$row[hitpoints];
                $name=$row[login];
            }
        }
        db_free_result($result2);
    }
    //Hugues per far passare la voglia ai soliti buontemponi di sfondare le tenute dello staff
    $sqlh = "SELECT superuser from accounts a,houses b WHERE houseid=".(int)$session[housekey]." AND owner=acctid ";
    $resulth = db_query($sqlh) or die(db_error(LINK));
    $rowh = db_fetch_assoc($resulth);
    if ($rowh['superuser'] > 0 ) {
	    page_header("La Collera degli Dei.");
        output("`5Hai osato entrare nella casa di una divinità. `nGli Dei ti puniscono per tanta audacia... vieni incenerito all'istante da un fulmine.`n");
        output("`5Sei morto !!! Potrai continuare a giocare domani`n");
        if ( $session['user']['gems'] > 2 ) {
	    	$session['user']['gems']= $session['user']['gems'] - 2 ;
		        output("`3A parziale rimborso dei danni che hai provocato ti vengono detratte `&2`3 gemme.`n");
		        debuglog("è morto incenerito mentre tentava di entrare nella casa di una divinità e perde 2 gemme. ");
	    }else{        
	        if ( $session['user']['goldinbank'] > 5000 ) {
		        $session['user']['goldinbank']= $session['user']['goldinbank'] - 5000 ;
		        output("`3A parziale rimborso dei danni che hai provocato ti vengono detratti `^5000`3 pezzi d'oro dal tuo conto in banca.`n");
		        debuglog("è morto incenerito mentre tentava di entrare nella casa di una divinità e perde 5000 monete dal conto in banca. "); 
		    }else{
			    if ( $session['user']['cittadino']=="Si" ){ 
				    output("`3A parziale rimborso dei danni che hai provocato ti vengono aumentate le tasse di `^5000`3 pezzi d'oro.`n");
		        	debuglog("è morto incenerito mentre tentava di entrare nella casa di una divinità e perde 5000 monete in tasse . ");
				    $sqlupdate = "UPDATE tasse SET oro = oro + 5000 WHERE acctid=".$session['user']['acctid'];
	                db_query($sqlupdate) or die(db_error(LINK));
	                savesetting("oro_tasse",(5000+getsetting("oro_tasse",'0')));
				}else{
					 debuglog("è morto incenerito mentre tentava di entrare nella casa di una divinità ma è un poveraccio. ");     
	        	}
	        }
        }
        addnews("`%".$session[user][name]."`3 è stat".($session[user][sex]?"a":"o")." incenerit".($session[user][sex]?"a":"o")." dagli Dei per aver disturbato il loro sonno.");
        $session['user']['alive']=false;
        $session['user']['hitpoints']=0;
        addnav("Notizie Giornaliere","news.php");
	}else{   
		addnav("Scappa Via","village.php");
        if ($athome>0){
	        if ($session['user']['playerfights']>0){
	            output("`nAll'interno trovi $athome inquilini della proprietà pesantemente armati e molto protettivi.
	                   Il più forte di loro si preparara rapidamente estraendo le armi per difendere la sua residenza.");
	            addnav("Battiti con il Campione","pvp.php?op6=houses&act=attack&bg=2&name=$name");
	        }else{
	            output("`n`6Sei troppo stanco per affrontare altri combattimenti ... puoi solo ritirarti con la coda tra le gambe.`n");
	        }
	    }else{
	        output("Fortunello, sembra non esserci nessuno in casa. Derubare questo posto dovrebbe essere facile come rubare le caramelle ai bambini.");
	        addnav("Inizia a Perquisire","houses.php?op=swipe&id=$session[housekey]");
	    }
    }
}elseif ($_GET['op'] =="swipe"){
    If(!$_GET[id]){
        output("<big>`c`SEd ora? Avvisa gli Admin. ERRORE di SISTEMA!!!`c`0</big>",true);
        addnav("Torna al Villaggio","village.php");
    }else{
        addnav("Torna al Villaggio","village.php");
        $sql = "SELECT h.*,a.name FROM houses h
                LEFT JOIN accounts a ON (h.owner = a.acctid)
                WHERE houseid =".$session['housekey']." ORDER BY houseid ASC";
        $result = db_query($sql) or die(db_error(LINK));
        $row = db_fetch_assoc($result);
        $gemme = getGemsInHouse($session[housekey]);
        $wasnu=e_rand(1,3);
        switch ($wasnu){
            case 1:
                $getgems=0;
                $getgold=e_rand(0,round($row[gold]/4));
                $sql = "UPDATE houses SET gold = gold-$getgold WHERE houseid = $row[houseid]";
                break;
            case 2:
                $getgems=e_rand(0,round($gemme/8));
                $getgold=e_rand(0,round($row[gold]/8));
                $sql = "UPDATE houses SET gold=gold-$getgold WHERE houseid = $row[houseid]";
                for ($i=$getgems;$i>0;$i--){
                    $sqlruba = "SELECT id, owner, gems FROM items WHERE gems > 0 AND value1 = ".$session[housekey]." AND class = 'Key' ORDER BY RAND() LIMIT 1";
                    $resultruba = db_query($sqlruba) or die(db_error(LINK));
                    $rowruba = db_fetch_assoc($resultruba);
                    $furtato = $rowruba[owner];
                    $sqltogli = "UPDATE items SET gems = gems-1 WHERE id = ".$rowruba['id'];
                    $resulttogli = db_query($sqltogli) or die(db_error(LINK));
                    $tolte[$furtato] ++;
                }
                $sqltrova = "SELECT owner FROM items WHERE value1 =".$row[houseid]." AND class = 'Key'";
                $resulttrova = db_query($sqltrova) or die(db_error(LINK));
                $countrow1 = db_num_rows($resulttrova);
                for ($i=0; $i<$countrow1; $i++){
                    $rowtrova = db_fetch_assoc($resulttrova);
                    $owner = $rowtrova['owner'];
                    if ($tolte[$owner] > 0) {
                        systemmail($owner,"`SIRRUZIONE!!`0",
                                   "`\$".$session['user'] ['name']."`\$ è penetrato nella tenuta
                                   `R".$row['housename']." `\$di `r".$row['name']."
                                    `\$e ha rubato `&".$tolte[$owner]." `\$delle tue `&Gemme!");
                        $tolte[$owner] = 0;
                    }
                }
                break;
            case 3:
                $getgems=e_rand(0,round($gemme/8));
                $getgold=0;
                //$sql = "UPDATE houses SET gems = gems-$getgems WHERE houseid = $row[houseid]";
                $sql = "UPDATE houses SET gems = gems WHERE houseid = $row[houseid]";
                for ($i=$getgems;$i>0;$i--){
                    $sqlruba = "SELECT id, owner, gems FROM items WHERE gems > 0 AND value1 = ".$session[housekey]." AND class = 'Key' ORDER BY RAND() LIMIT 1";
                    $resultruba = db_query($sqlruba) or die(db_error(LINK));
                    $rowruba = db_fetch_assoc($resultruba);
                    $furtato = $rowruba[owner];
                    $sqltogli = "UPDATE items SET gems = gems-1 WHERE id = ".$rowruba['id'];
                    $resulttogli = db_query($sqltogli) or die(db_error(LINK));
                    $tolte[$furtato] ++;
                }
                $sqltrova = "SELECT owner FROM items WHERE value1 =".$row[houseid]." AND class = 'Key'";
                $resulttrova = db_query($sqltrova) or die(db_error(LINK));
                $countrow3 = db_num_rows($resulttrova);
                for ($i=0; $i<$countrow3; $i++){
                    $rowtrova = db_fetch_assoc($resulttrova);
                    $owner = $rowtrova['owner'];
                    if ($tolte[$owner] > 0) {
                        systemmail($owner,"`aIRRUZIONE!!`0",
                                   "`\$".$session['user'] ['name']."`\$ è penetrato nella tenuta
                                   `R".$row['housename']." `\$di `r".$row['name']."
                                    `\$e ha rubato `&".$tolte[$owner]." `\$delle tue `&Gemme!");
                        $tolte[$owner] = 0;
                    }
                }
                break;
        }
        db_query($sql) or die(db_error(LINK));

        //Excalibur: Possibilità di inserire testo nei commenti della tenuta per prendere in giro gli occupanti
        addnav("Sbeffeggia Occupanti","houses.php?op=sbeff&id=".$_GET['id']);
        //Excalibur: fine

        $statoporta= getStatusFromId($_GET['id']);
        $session[user][gold] +=$getgold;
        $session[user][gems] +=$getgems;
        $session['user']['evil'] += 10;
        //perquisizione contemporanea
        if($statoporta['cattiveria']==1000) {
          if($getgold>0 || $getgems>0) {
               output("`n`n`^Gran Colpo!!! Tu e i tuoi complici state svuotando per bene la cassaforte! Chissà che sorpresa al suo ritorno!`0");
          } else {
               output("`n`n`1Non hai rubato nulla... speriamo che ai tuoi complici sia andata meglio!");
          }
          $session['user']['evil'] +=10;
          debuglog("Ha derubato con i complici in tenuta e ha preso 10 punti cattiveria aggiuntivi");
        /*
          $session['user']['evil'] += 100;
          $session['user']['jail']=1;
          output("`n`n`\$Qualcuno ha derubato un attimo prima di te!");
          output("giusto il tempo di attirare l'attenzione dello sceriffo che ti coglie con le mani nel sacco!!!`0");
          debuglog("E' stato arrestato dallo sceriffo colto con le mani nel sacco e prende 100 punti cattiveria");
          addnews("`1$" .$session['user']['name']. " `1è stato catturato dallo Sceriffo in una tenuta e sbattuto in Prigione!");
          */
        }else {
          debuglog("Ha preso " . $statoporta['cattiveria'] . " punti cattiveria aggiuntivi per aver derubato una tenuta con lo stato " . $statoporta['stato']);
          $session['user']['evil'] += $statoporta['cattiveria'];
        }
        output("`@Sei riuscito a razziare `^$getgold `@Pezzi d'Oro e `#$getgems `@Gemme dalla cassaforte della Tenuta!");
        output("`n`n" . $statoporta['descr']);
        $query= "update houses set attacco=" . time() . " where houseid=$_GET[id]";
        db_query($query) or die(db_error(LINK));
        addnews("`6".$session[user][name]."`6 ha rubato `#$getgems`6 gemme e `^$getgold`6 pezzi d'oro penetrando nella tenuta `@$row[housename]`6!");
        systemmail($row[owner],"`\$IRRUZIONE!`0","`\$".$session['user'] ['name']."`\$ è penetrato in casa tua e ha rubato in totale
        `^$getgold Pezzi d'Oro `\$e `&$getgems Gemme`\$!!
        `G(`iRiceverai un PM per le gemme rubate a te`i)");
        debuglog("`7ha fatto irruzione nella casa di ".$row['name']."`& (acctid: ".$row['owner'].") `7rubando
                  `^$getgold oro `7e `&$getgems gemme`0");
    }

//Excalibur: routine per inserire sbeffeggio
}elseif ($_GET['op'] == "sbeff"){
    if($_GET['act']==""){
        output("`n`n`@Scrivi il commento che preferisci per sbeffeggiare gli occupanti della casa.`n`n");
        output("<form action='houses.php?op=sbeff&id=".$_GET['id']."&act=ok' method='POST'><u>Commento</u>: <input name='comm' accesskey='o'>`n",true);
        output("<input type='submit' class='button' value='Inserisci Commento'></form>",true);
        output("<script language='javascript'>document.getElementById('to').focus();</script>",true);
        addnav("","houses.php?op=sbeff&id=".$_GET['id']."&act=ok");
    }elseif ($_GET['act']=="ok"){
        $session['string'] = $_POST['comm'];
        output("`@Ecco il commento che verrà inserito:`n".$session['string']);
        output("`n`n`@Confermi o vuoi modificarlo?`n");
        addnav("`@Confermo","houses.php?op=sbeff&id=".$_GET['id']."&act=okok");
        addnav("`\$Modifica","houses.php?op=sbeff&id=".$_GET['id']);
    }elseif ($_GET['act']=="okok"){
        output("`@Sbeffeggio inserito !!!`n");
        $string = addslashes("::vi sbeffeggia dicendo:`3\"`#".$session['string']."`3\"");
        $sqlsbeff = "INSERT INTO commentary (postdate,section,author,comment) VALUES (now(),'house-".$_GET['id']."','".$session['user']['acctid']."',\"$string\")";
        db_query($sqlsbeff) or die(db_error(LINK));
        $session['string'] = "";
        addnav("Torna al Villaggio","village.php");
    }
//Excalibur: fine routine sbeffeggio

}elseif ($_GET['op'] =="fight"){
    $battle=true;
}elseif ($_GET['op'] =="run"){
    output("`\$Il tuo onore ti impedisce di fuggire!`0");
    $_GET['op'] ="fight";
    $battle=true;
}elseif ($_GET['op'] =="buy"){
/*
if ($session[user][house]>0){
Output("`@You have already developed the plot of land you were granted. If you want to have another house, you must first move out of your present estate.");
else }
*/
    If(!$_GET[id]){
        $sql = "SELECT * FROM houses WHERE status = 2 OR status = 3 OR status = 4 ORDER BY houseid ASC";
        output("`c`b`^Case Disabitate`b`c`0`n");
        output("`@Ti avvii verso lo studio di Architettura sul Viale Reale. Un Agente di Vendita sta valutando con avidità
                le sue attuali offerte, con altre note scarabocchiate inviate dai proprietari privati e da personaggi nell'ombra che
                hanno ottenuto in qualche modo un Leasing Reale. Osservi le varie offerte per quale proprietà sia disponibile.");

        output("<table cellspacing = 0 cellpadding = 2 align = 'center'><tr><td>`bCasa#. `b</td><td>`bNome Tenuta`b</td><td>`bOro`b</td><td>`bGemme`b</td><td>`bVenditore`b</td></tr>",true);
        $result = db_query($sql) or die(db_error(LINK));
        if (db_num_rows($result) ==0){
            output("<tr><td colspan = 4 align = 'center'>`&`iAttualmente non ci sono case in vendita.`i`0</td></tr>",true);
        }else{
            $countrow = db_num_rows($result);
            for ($i=0; $i<$countrow; $i++){
            //for ($i=0;$i<db_num_rows($result);$i++){
                $row = db_fetch_assoc($result);
                $bgcolor= ($i%2==1?"trlight":"trdark");
                output("<tr class='$bgcolor'><td align = 'right'>$row[houseid]</td><td><a href='houses.php?op=buy&id=$row[houseid]'>$row[housename]</a></td><td align = 'right'>$row[gold]</td><td align = 'right'>$row[gems]</td><td>",true);
                if ($row[status] ==3){
                    output("`4Libera`0");
                }elseif ($row[status] ==4){
                    output("`\$Casa in Rovina`0");
                }elseif ($row[owner] ==0){
                    output("`^Vendita Agenzia`0");
                }else{
                    output("`6Vendita Privata`0");
                }
                output("</td></tr>",true);
                addnav("","houses.php?op=buy&id=$row[houseid]");
            }
        }
        output("</table>",true);
    }else{
        $sql = "SELECT * FROM houses WHERE houseid =".(int)$_GET[id]." ORDER BY houseid DESC";
        $result = db_query($sql) or die(db_error(LINK));
        $row = db_fetch_assoc($result);
        if ($session[user][acctid] ==$row[owner]){
            output("`@Decidi che non è ancora il momento di vendere la tua casa.");
            $session[user][housekey] =$row[houseid];
            $sql = "UPDATE houses SET gold=0,gems=0,status=1 WHERE houseid = $row[houseid]";
            db_query($sql);
            /// 0.95C edition : only those with DKs can purchase a house: (strider)
        }elseif (($session['user']['dragonkills'] < 19 OR
                 ($session['user']['dragonkills'] == 19 AND $session['user']['level']<=5)) AND
                  $session['user']['reincarna'] < 1){
            ////ss/////////////////////////////////////////////////////////////////////////////////////
            output("`@Solo i guerrieri che hanno ucciso il Drago `^19 volte `@e siano almeno di `^Livello 6`@ o si siano ");
            output("reincarnati possono acquistare una Tenuta. Devi guadagnarti il permesso di ingresso nelle Tenute Reali. ");
            output("Magari puoi chiedere a qualcuno di ospitarti nella sua casa mentre guadagni qualche livello?");
        }elseif ($session['user']['gold']<$row['gold'] OR $session['user']['gems']<$row['gems']) {
            output("`@Una casa di questo genere probabilmente eccede le tue finanze.");
        }else{
            output("`@Benvenuto nella tua nuova casa! `n`n");
            $session[user][gold]-=$row[gold];
            $session[user][gems]-=$row[gems];
            $session[user][house]=$row[houseid];
            output("Dai `^$row[gold]`@ Pezzi d'Oro e `#$row[gems]`@ Gemme al venditore, che ti da la chiave e l'atto di vendita per la casa `b$row[houseid]`b.");
            if ($row[owner]>0){
                $sql = "UPDATE accounts SET goldinbank = goldinbank + $row[gold],gems=gems+$row[gems],house=0,housekey=0 WHERE acctid = $row[owner]";
                db_query($sql);
                systemmail($row[owner],"`@Casa Venduta!`0","`&{$session['user']['name']}`2 ha acquistato la tua casa. Ottieni `^$row[gold]`2 Oro in banca e `#$row[gems] `2Gemme!");
                debuglog("acquista per ".$row['gold']." oro e ".$row['gems']." gemme una nuova casa (N° ".$row['houseid'].") da ",$row['owner']);
                $session[user][housekey] =$row[houseid];
            }
            if ($row['status'] == 3){
                //$sql = "UPDATE houses SET status=1,owner =".$session['user']['acctid'].",fede=0 WHERE houseid = $row[houseid]";
                $sql = "UPDATE houses SET status=1,gold=0,gems=0,owner =".$session['user']['acctid'].",fede=0 WHERE houseid = $row[houseid]";
                db_query($sql);
                $sql = "UPDATE items SET owner =".$session['user']['acctid']." WHERE owner = 0 AND class='key' AND value1=$row[houseid]";
                db_query($sql);
                output("Forse dovresti considerare che hai comprato una casa usata. Forse, qualcun altro ha la chiave!");
                $session['user']['housekey'] =$row['houseid'];
                debuglog("acquista per ".$row['gold']." oro e ".$row['gems']." gemme una casa abbandonata (N° ".$row['houseid'].")");
            }elseif ($row['status'] == 4){
                $sql = "UPDATE houses SET status=0,owner =".$session['user']['acctid'].",fede=0 WHERE houseid = $row[houseid]";
                db_query($sql);
                output("Hai acquistato una costruzione in rovina. Dovrai terminare la costruzione prima di poter entrare!");
                debuglog("acquista per ".$row['gold']." oro e ".$row['gems']." gemme una casa in rovina (N° ".$row['houseid'].")");
            }else{
                $sql = "UPDATE houses SET gold=0,gems=0,status=1,owner =".$session['user']['acctid'].",fede=0 WHERE houseid = $row[houseid]";
                db_query($sql);
                $sql = "UPDATE items SET owner =".$session['user']['acctid']." WHERE class='key' AND value1=$row[houseid]";
                $session['user']['housekey'] =$row['houseid'];
                debuglog("acquista per ".$row['gold']." oro e ".$row['gems']." gemme una casa in vendita all'agenzia (N° ".$row['houseid'].")");
            }
        }
    }
    addnav("Torna alle Tenute Reali","houses.php");
    addnav("Torna al Villaggio","village.php");
}elseif ($_GET['op'] =="sell"){
    $sql = "SELECT * FROM houses WHERE houseid =".$session['user']['housekey']." ORDER BY houseid DESC";
    $result = db_query($sql) or die(db_error(LINK));
    $row= db_fetch_assoc($result);
    $halfgold=round($goldcost/3);
    $halfgems=round($gemcost/3);
    if ($_GET[act] =="wages"){
        if (!$_POST[gold] &&!$_POST[gems]){
            output("`@Pensi sinceramente di vendere la tua casa. Se determini un prezzo, considera che dovrà essere pagato tutto insieme ");
            output(" e non accettare anticipi. Inoltre non potrai costruire una nuova casa, ma potrai vivere in questa sino alla vendita.");
            output(" Otterrai i soldi immediatamente se la casa viene venduta. La vendita può essere interrotta, solo da te.");
            output("`nQuando vuoi i soldi immediatamente, devi vendere la tua casa per `^$halfgold`@ pezzi d'oro e `#$halfgems`@ gemme ad un'agenzia.");
            output("`0<form action=\"houses.php?op=sell&act=sold\" method ='POST'>",true);
            output("`nQuanto Oro chiedi per la casa? <input type = 'gold' name = 'gold'>`n",true);
            output("`nE quante Gemme dovrebbe costare? <input type = 'gems' name = 'gems'>`n",true);
            output("<input type = 'submit' class = 'button' value = 'Metti in Vendita'>",true);
            addnav("","houses.php?op=sell&act=sell");

            addnav("Vendi ad un'Agenzia","houses.php?op=sell&act=broker");
        }else{
            $halfgold=(int)$_POST[gold];
            $halfgems=(int)$_POST[gems];
            if (($halfgold<$goldcost/40 && $halfgems<$gemcost/10) || ($halfgold==0 && $halfgems<$gemcost/2) || ($halfgold<$goldcost/20 && $halfgems==0)){
                output("`@Nessuno crede che tu voglia vendere una Tenuta Reale per quel prezzo. Appena hai inviato il prezzo incredibilmente basso all'agenzia,
                        i goblins cominciano a gridare ed urlare in scherno.  Forse dovresti chiedere più soldi, o vendere la casa all'agenzia ora.");
                addnav("Nuovo Prezzo","houses.php?op=sell&act=sell");
            }elseif ($halfgold>$goldcost*3 || $halfgems>$gemcost*5){
                output("`@Studi la possibilità di vendere la tua proprietà per quell'importo, ma capisci che non la venderai MAI per quella cifra. Dovresti ripensarci.");
                addnav("Nuovo Prezzo","houses.php?op=sell&act=sell");
            }else{
                output("`@La casa è in vendita per `^$halfgold`@ pezzi d'oro e `#$halfgems`@ gemme. Tu e i tuoi compagni di stanza dividete in parti uguali l'oro
                        della casa prima che riconsegnino le loro chiavi.");
                        // Distribute gold and gems at inhabitant and take in key
                $sql = "SELECT owner,gems FROM items WHERE value1=$row[houseid]
                        AND class='key'
                        AND ( (owner=$row[owner] AND value2=10) OR (owner<>$row[owner]) )
                        ORDER BY id ASC";
                $result = db_query($sql) or die(db_error(LINK));
                $office = db_num_rows($result);
                $goldgive = round($row[gold] / $office);
                $countrow1 = db_num_rows($result);
                for ($i=0; $i<$countrow1; $i++){
                //for ($i=0; $i<db_num_rows($result); $i++){
                    $item = db_fetch_assoc($result);
                    if ($session['user']['acctid'] != $item['owner']) {
                        $sql = "UPDATE accounts SET goldinbank=goldinbank+$goldgive,gems=gems+'{$item['gems']}' WHERE acctid = $item[owner]";
                        db_query($sql);
                        systemmail($item[owner],"`@Sbattuto Fuori!`0","`&{$session['user']['name']}`2 ha venduto la
                        Tenuta!`nOra `b$row[housename]`b`2 è in vendita e abbandonata.`nPoichè hai vissuto come
                        affittuario che ha contribuito al mantenimento della Tenuta, ricevi `^$goldgive`2 pezzi d'oro
                        e le tue `#".$item['gems']."`2 gemme dalla cassaforte!");
                        $sqllog = "INSERT INTO debuglog VALUES(0,now(),{$item['owner']},0,'".addslashes("viene sbattuto
                        fuori dalla tenuta ".$row['houseid']." messa in vendita da ".$session['user']['name']." e ottiene $goldgive
                        oro e ".$item['gems']." gemme per il disturbo")."')";
                        db_query($sqllog);
                   } else {
                        output("`n`@Ottieni `^$goldgive pezzi d'oro `@e le tue `&".$item['gems']." gemme`@ che erano
                        depositate nella cassaforte.`n");
                        $session['user']['goldinbank'] += $goldgive;
                        $session['user']['gems'] += $item['gems'];
                        debuglog("mette in vendita la tenuta N°".$session['user']['house']." e ottiene $goldgive dalla cassaforte e le sue ".$item['gems']."
                        gemme che erano in cassaforte");
                   }
                }
                $sql = "UPDATE items SET owner = $row[owner], gems = 0, tempo = 0 WHERE class='key' AND value1=$row[houseid]";
                db_query($sql);
                debuglog("mette in vendita la tenuta ".$row['housename']." per ".$halfgold." oro e ".$halfgems." gemme.");
                $row[gold] =$halfgold;
                $row[gems] =$halfgems;
                $session['user']['house'] =0;
                $session['user']['housekey'] =0;
                $sql = "UPDATE houses SET gold = $row[gold],gems=$row[gems],status=2 WHERE houseid = $row[houseid]";
                db_query($sql);
            }
        }
    }elseif ($_GET[act] =="broker"){
        output("`@Non puoi aspettare di vendere la tua casa sul mercato, così ti avvicini all'agenzia di intermediazione.
                Una dozzina di goblin esamina la tua proprietà in modo caotico e collettivamente ridono scioccamente, quindi acconsentono
                a pagarti `^ $halfgold `@ pezzi d'oro e `^ $halfgems `@ gemme. `n`n");
        output("La tua casa va immediatamente in vendita e puoi costruirne una nuova o acquistare un'altra casa.");
        // Distribute gold and gems at inhabitant and take in key
        $sql = "SELECT owner,gems FROM items WHERE value1=$row[houseid]
                AND class='key'
                AND ( (owner=$row[owner] AND value2=10) OR (owner<>$row[owner]) )
                ORDER BY id ASC";
        $result = db_query($sql) or die(db_error(LINK));
        $office = db_num_rows($result);
        $goldgive = round($row[gold] / $office);
        $countrow = db_num_rows($result);
        for ($i=0; $i<$countrow; $i++){
        //for ($i=0; $i<db_num_rows($result); $i++){
            $item = db_fetch_assoc($result);
            if ($session['user']['acctid'] != $item['owner']) {
                $sql = "UPDATE accounts SET goldinbank=goldinbank+$goldgive,gems=gems+'{$item['gems']}' WHERE acctid = $item[owner]";
                db_query($sql);
                systemmail($item[owner],"`@Sbattuto Fuori!`0","`&{$session['user'] ['name']}`2 ha venduto la Tenuta! `b$row[housename]`b`2 è in vendita e abbandonata.
                    Poichè hai vissuto come affittuario che ha contribuito al mantenimento della Tenuta, ricevi `^$goldgive`2 pezzi d'oro e le tue `#".$item['gems']."`2 gemme dalla cassaforte!");
                $sqllog = "INSERT INTO debuglog VALUES(0,now(),{$item['owner']},0,'".addslashes("viene sbattuto fuori
                dalla tenuta ".$row['houseid']." venduta all'agenzia da ".$session['user']['name']." e ottiene $goldgive oro e
                ".$item['gems']." gemme per il disturbo")."')";
                db_query($sqllog);
           } else {
                output("`n`@Ottieni `^$goldgive pezzi d'oro `@e le tue `&".$item['gems']." gemme`@ che erano
                depositate nella cassaforte.`n");
                $session['user']['goldinbank'] += $goldgive;
                $session['user']['gems'] += $item['gems'];
                debuglog("vende all'agenzia la tenuta e ottiene $goldgive dalla cassaforte e le sue ".$item['gems']."
                gemme che erano in cassaforte");
           }
        }
        $sql = "UPDATE items SET owner = $row[owner], gems = 0, tempo = 0 WHERE class='key' AND value1=$row[houseid]";
        db_query($sql);
    // Set variable and database updaten
        $row['gold'] =$goldcost-$halfgold;
        $row['gems'] =$gemcost;
        $session['user']['goldinbank'] +=$halfgold;
        $session['user']['gems'] +=$halfgems;
        debuglog("vende la tenuta al broker per $halfgold pezzi d'oro e $halfgems gemme");
        $session['user']['house'] =0;
        $session['user']['housekey'] =0;
        //$session['user']['donation'] +=1;
        $sql = "UPDATE houses SET owner=0,gold=$row[gold],gems=$row[gems],status=2 WHERE houseid = $row[houseid]";
        db_query($sql);
    }else{
        output("`@Desideri fissare un prezzo per la tua proprietà o lasci al mediatore ricevere la vendita ? I grassi
                goblin mediatori ti darebbero immediatamente `^$halfgold`@ pezzi d'oro e `#$halfgems`@ gemme. ");
        output("Se la vendi privatamente, puoi ottenere un prezzo più alto, ma dovrai aspettare per il pagamento,
                fino a che qualcuno decide di acquistarla. `nTutte l'oro nella cassaforte della casa verrà diviso
                in maniera eguale fra tutti gli abitanti.`n`n");
        output("`0<form action=\"houses.php?op=sell&act=wages\" method ='POST'>",true);
        output("Prezzo `nQuanto Oro? <input type = 'gold' name = 'gold'>`n",true);
        output("`nQuante Gemme? <input type = 'gems' name = 'gems'>`n`n",true);
        output("<input type = 'submit' class = 'button' value = 'Vendi Tenuta'>",true);
        addnav("","houses.php?op=sell&act=wages");

        addnav("Opzioni di Vendita");
        addnav("Vendi all'Agenzia","houses.php?op=sell&act=broker");
    }
    addnav("Altro");
    addnav("Torna alle Tenute Reali","houses.php");
    addnav("Torna al Villaggio","village.php");
}elseif ($_GET['op'] =="inside"){
    if ($_GET['id']) {
        $session['housekey']=(int)$_GET['id'];
        $session['user']['casa'] = (int)$_GET['id'];
    }
    if(!$session['housekey']) redirect("houses.php");
    $sql = "SELECT * FROM houses WHERE houseid =".$session['housekey']." ORDER BY houseid DESC";
    $result = db_query($sql) or die(db_error(LINK));
    $row = db_fetch_assoc($result);
    $chiave = getUserKey($session['user']['acctid'], $row['houseid']);
    if ($_GET['act'] =="takekey"){
        if (!$_POST['goal']){
            $sql = "SELECT owner FROM items
                    WHERE value1 = ".$row['houseid']."
                    AND class='key'
                    AND owner <> ".$session['user']['acctid']."
                    ORDER BY VALUE2 ASC";
            $result = db_query($sql) or die(db_error(LINK));
            output("<form action='houses.php?op=inside&act=takekey' method ='POST'>",true);
            output("`2Desiderate togliere la chiave ? <select name = 'goal'>",true);
            $countrow = db_num_rows($result);
            for ($i=0; $i<$countrow; $i++){
            //for ($i=0;$i<db_num_rows($result);$i++){
                $item = db_fetch_assoc($result);
                $sql = "SELECT acctid, name, login FROM accounts
                        WHERE acctid = ".$item['owner']." AND
                        ( jail = 0 OR (jail <> 0 AND laston < '".date("Y-m-d H:i:s",strtotime(date("r")."-20 days"))."') )
                        ORDER BY login DESC";
                $result2 = db_query($sql) or die(db_error(LINK));
                $row2 = db_fetch_assoc($result2);
                if ($office !=$row2[acctid] && $row2[acctid] !=$row[owner]) output("<option value=\"".rawurlencode($row2['name'])."\">".preg_replace("'[`].'","",$row2['name'])."</option>",true);
                $office=$row2[acctid];
            }
            output("</select>`n`n",true);
            output("<input type = 'submit' class = 'button' value = 'Togli Chiave'></form>",true);
            addnav("","houses.php?op=inside&act=takekey");
        }else{
            $sql = "SELECT acctid,name,login,gold,gems FROM accounts WHERE name ='". addslashes(rawurldecode(stripslashes($_POST['goal'])))."' AND locked = 0";
            $result2 = db_query($sql);
            $row2 = db_fetch_assoc($result2);
            $chiaveDaTogliere = getUserKey($row2[acctid], $row[houseid]);
            if ($row2[acctid] !=$row[owner]){
                output("`2Chiedi indietro la chiave a `&$row2[name]`2. `n");
                $sql = "SELECT owner FROM items WHERE value1=$row[houseid] AND class='key' AND owner<>$row[owner] ORDER BY id ASC";
                $result = db_query($sql) or die(db_error(LINK));
                $goldgive=round($row[gold] / (db_num_rows($result) +1));
                $gemsgive = $chiaveDaTogliere['gems'];
                systemmail($row2[acctid],"`@Chiave Presa Indietro!`0","`&".$session['user'] ['name']."`2 ha voluto indietro la chiave della casa numero `b".$row['houseid']."`b (".$row['housename']."`2). Vieni risarcito con `^$goldgive`2 pezzi d'oro in banca e `#$gemsgive`2 gemme che avevi depositato!");
                output("$row2[name]`2 ottiene `^$goldgive`2 pezzi d'oro dall'oro collettivo e le sue `#$gemsgive`2 gemme che aveva depositato in cassaforte.");
                debuglog("toglie la chiave a {$row2[name]} dandogli $goldgive oro e $gemsgive gemme");
                $sqllog = "INSERT INTO debuglog VALUES(0,now(),{$row2['acctid']},0,'".addslashes("perde la chiave della tenuta ".$row['houseid']." ma ottiene $goldgive oro e $gemsgive gemme")."')";
                db_query($sqllog);
                $datarestituzione = strtotime(date("r")."+48 hours");
                $sql = "UPDATE items SET owner = $row[owner], gems = 0, buff = $row2[acctid], tempo = $datarestituzione WHERE id = ".$chiaveDaTogliere['id'];
                db_query($sql);
                $sql = "UPDATE accounts SET goldinbank=goldinbank+$goldgive,gems=gems+$gemsgive WHERE acctid=$row2[acctid]";
                db_query($sql) or die(db_error(LINK));
                $sql = "UPDATE houses SET gold=gold-$goldgive WHERE houseid = $row[houseid]";
                db_query($sql) or die(db_error(LINK));
            } else {
                output("`2Non puoi chiedere indietro la chiave al proprietario!`2. `n");
            }
        }
        addnav("Torna alla Tenuta","houses.php?op=inside");
    }elseif($_GET['act'] =="awaykey"){
        //$sql = "SELECT acctid,name,login,gold,gems FROM accounts WHERE name ='". addslashes(rawurldecode(stripslashes($_POST['goal'])))."' AND locked = 0";
        //$result2 = db_query($sql);
        //$row2 = db_fetch_assoc($result2);
        $chiaveDaTogliere = getUserKey($session['user']['acctid'], $row[houseid]);
        $sqlprop = "SELECT owner FROM items WHERE value1=$row[houseid] AND class='key' AND owner=$row[owner] ORDER BY id ASC LIMIT 1";
        $resultprop = db_query($sql) or die(db_error(LINK));
        $rowprop = db_fetch_assoc($resultprop);
        $gemsgive = $chiaveDaTogliere['gems'];
        output("`2Restituisci la chiave al proprietario della tenuta, riprendendoti le tue `&$gemsgive gemme `2.");
        $session['user']['gems'] += $gemsgive;
        systemmail($rowprop['owner'],"`@Chiave Restituita!`0","`&".$session['user'] ['name']."`2 ti ha restituito la chiave della casa numero `b".$row['houseid']."`b (".$row['housename']."`2).`n
        Si è ripreso `#$gemsgive`2 gemme che aveva depositato!");
        debuglog("si riprende $gemsgive gemme restituendo la chiave della tenuta a ",$rowprop['owner']);
        $datarestituzione = strtotime(date("r")."+48 hours");
        $sql = "UPDATE items SET owner = $row[owner], gems = 0, buff = ".$session['user']['acctid'].", tempo = $datarestituzione WHERE id = ".$chiaveDaTogliere['id'];
        db_query($sql);
        addnav("Torna al Villaggio","village.php");
    }elseif ($_GET['act'] =="givekey"){
        If(!$_POST['goal']){
            output("`c`b`2Proprietari di chiavi di `3$row[housename]`2:`b`c`n");
            $sql = "SELECT * FROM items WHERE value1=$row[houseid] AND class='key' ORDER BY VALUE2 ASC";
            $result = db_query($sql) or die(db_error(LINK));
            $countrow = db_num_rows($result);
            for ($i=0; $i<$countrow; $i++){
            //for ($i=0;$i<db_num_rows($result);$i++){
                $item = db_fetch_assoc($result);
                $sql = "SELECT acctid, name, login FROM accounts WHERE acctid = $item[owner] ORDER BY login DESC";
                $result2 = db_query($sql) or die(db_error(LINK));
                $row2 = db_fetch_assoc($result2);
                if ($office !=$row2[acctid]){
                    output("`c`& $row2[name]`0",true);
                    if ($row2[acctid] ==$row[owner]) output(" (Proprietario) `n");
                    output("`c");
                }
                $office=$row2[acctid];
            }
            $sql = "SELECT VALUE2 FROM items WHERE value1=$row[houseid] AND class='key' AND owner = $row[owner] ORDER BY id ASC";
            $result = db_query($sql) or die(db_error(LINK));
            if (db_num_rows($result)>1){
                output("`n`2Hai `b".(db_num_rows($result)-1)."`b chiavi da distribuire.");
                output("<form action='houses.php?op=inside&act=givekey' method ='POST'>",true);
                output("A chi vuoi darle ? <input name = 'goal'>`n", true);
                output("<input type = 'submit' class = 'button' value = 'Invia Chiave'></form>",true);
                output("`n`6Ricorda: `2Quando consegni una chiave, Il tesoro della casa diventa di proprietà comune. Puoi ritirare le chiavi in ogni momento, comunque, ");
                output("quel giocatore riceverà una parte uguale del Tesoro della Tenuta quando gli toglierai la chiave.");
                addnav("","houses.php?op=inside&act=givekey");
            }else{
                output("`n`2Spiacente, non hai altre chiavi da distribuire.");
            }
        }else{
            if ($_GET['subfinal'] ==1){
                $sql = "SELECT acctid,name,login,lastip,emailaddress,dio FROM accounts WHERE name ='".addslashes(rawurldecode(stripslashes($_POST['goal'])))."' AND locked = 0";
            }else{
                $goal = stripslashes(rawurldecode($_POST['goal']));
                $name="%".$goal."%";
                /*Excalibur: modifica per trovare correttamente i nomi dei player
                $name="%";
                for ($x=0;$x<strlen($goal);$x++){
                    $name.=substr($goal,$x,1)."%";
                } */
                $sql = "SELECT acctid,name,login,lastip,emailaddress,dio FROM accounts WHERE name LIKE '".addslashes($name)."' AND locked = 0";
            }
            $result2 = db_query($sql);
            if (db_num_rows($result2) == 0){
                output("`2Sembra non esserci nessun con quel nome. Per favore riprova.");
            }elseif(db_num_rows($result2) > 100){
                output("`2Ci sono più di 100 guerrieri con un nome simile. Forse dovresti restringere la ricerca.");
            }elseif(db_num_rows($result2) > 1){
                output("`2C'è qualche guerriero che corrisponde alla descrizione. A chi vuoi dare la chiave? `n");
                output("<form action='houses.php?op=inside&act=givekey&subfinal=1' method ='POST'>",true);
                output("`2Chi intendi esattamente? <select name = 'goal'>",true);
                $countrow2 = db_num_rows($result2);
                for ($i=0; $i<$countrow2; $i++){
                //for ($i=0;$i<db_num_rows($result2);$i++){
                    $row2 = db_fetch_assoc($result2);
                    output("<option value=\"".rawurlencode($row2['name'])."\">".preg_replace("'[`].'","",$row2['name'])."</option>",true);
                }
                output("</select>`n`n",true);
                output("<input type = 'submit' class = 'button' value = 'Invia Chiave'></form>",true);
                addnav("","houses.php?op=inside&act=givekey&subfinal=1");
                //Addnav("","houses.php?op=inside&act=givekey"); // why the brightly what this in there?
            }else{
                $row2 = db_fetch_assoc($result2);
                $sql = "SELECT owner FROM items WHERE owner=$row2[acctid] AND value1=$row[houseid] AND class='key' ORDER BY id ASC";
                $result = db_query($sql) or die(db_error(LINK));
                $item = db_fetch_assoc($result);
                if ($row2[login] == $session[user][login]){
                    output("`2Che senso ha dare una chiave a te stesso? Sei impazzito forse ?");
                }elseif($item[owner] ==$row2[acctid]){
                    output("`2$row2[name]`2 ha già la chiave della tua tenuta!");
                }/*elseif($session['user']['superuser']<3 && $session['user']['lastip'] == $row2['lastip'] || ($session['user'] ['emailaddress'] == $row2['emailaddress'] && $row2[emailaddress])){
                    output("`2Spiacente, i vostri personaggi non possono interagire.`n`n`n`n`n`n");
                }*/else{
                    $sqlcasa = "SELECT fede FROM houses WHERE owner = ".$session['user']['acctid'];
                    $resultcasa = db_query($sqlcasa) or die(db_error(LINK));
                    $rowcasa = db_fetch_assoc($resultcasa);
                    if ($rowcasa['fede']==0 OR $rowcasa['fede']==$row2['dio']){
                       $sql = "SELECT value2,buff,tempo FROM items WHERE value1=$row[houseid] AND class='key' AND owner = $row[owner] ORDER BY value2 ASC LIMIT 1";
                       $result = db_query($sql) or die(db_error(LINK));
                       $knrt = db_fetch_assoc($result);
                       $knr=$knrt[value2];
                       //print($row2['acctid']."  ".$knrt['buff']);
                       if ($knrt['buff'] != $row2[acctid] OR ($knrt['tempo'] < strtotime(date("r")) AND $knrt['tempo']!=0)) {
                          output(" `c`b`@Consegna Chiavi`b`c`n`n ");
                          output("`2Ti metti in contatto con i folletti postini che consegnano una chiave a `&$row2[name]`2. `nRicorda, puoi togliergli la chiave della casa in qualsiasi momento, ma $row2[name]`2 ");
                          output("otterrà una parte equa del Tesoro della Tenuta. `n");
                          debuglog("consegna una chiave della sua tenuta a {$row2[name]} ");
                          systemmail($row2[acctid],"`@Consegna Chiave!`0","`&{$session['user']['name']}`2 ti ha fornito
                          una chiave della sua proprietà personale. Puoi trovare ($row[housename]`2) al lotto numero
                          `b$row[houseid]`b nelle Tenute Reali.");
                          $sql = "UPDATE items SET owner=$row2[acctid], gold=0, gems=0, tempo=0, buff='' WHERE owner = $row[owner] AND class='key' AND value1=$row[houseid] AND value2=$knr";
                          db_query($sql);
                       }else{
                          $tempo = round(($knrt['tempo']-strtotime(date("r")))/3600, 2);
                          output("`b`\$Non fare il furbo, non puoi ancora ridare la chiave a quel player !!!`n");
                          output("L'hai tolta (o l'ospite l'ha restituita) da troppo poco tempo !!!`n");
                          output("Dovrai aspettare `^$tempo ore`\$ per potergliela ridare !!`n");
                          report(3,"`@Furbata Chiave!`0","`&{$session['user']['name']}`2 ha voluto fare il furbo togliendo (o ricevendo) e ridando subito una chiave della tenuta a $row2[name]","furbata_chiave");
                       }
                    }else{
                       output("`%Non puoi consegnare la chiave ad un player di fede diversa da quella della tenuta!!!");
                    }
                }
            }
        }
        addnav("Torna alle Tenuta","houses.php?op=inside");
    }elseif ($_GET['act'] =="takegold"){
        $maxtfer = $session[user][level]*getsetting("transferperlevel",25);
        if (!$_POST[gold]){
            $transleft = getsetting("transferreceive",3) - $session[user][transferredtoday];
            output("`2Ci sono `^$row[gold]`2 Pezzi d'Oro nella cassaforte della tua tenuta. `nPrelievo quotidiano restante: `^$transleft `2 `nPrelievo massimo di `^$maxtfer`2 pezzi d'oro. `n");
            output("`2<form action=\"houses.php?op=inside&act=takegold\" method ='POST'>",true);
            output("`nRitira quanto Oro? <input type = 'gold' name = 'gold'>`n`n",true);
            output("<input type = 'submit' class = 'button' value = 'Ritira'>",true);
            addnav("","houses.php?op=inside&act=takegold");
        }else{
            $office=abs((int)$_POST[gold]);
            if ($office>$row[gold]){
                output("`2Non c'è tutto quell'oro nella tua cassaforte.");
            }elseif ($maxtfer<$office){
                output("`2Al massimo puoi prelevare `^$maxtfer`2 pezzi d'oro per volta.");
            }elseif ($office<0){
                output("`2Getti un po' di polvere sul tuo tesoro. Bel lavoro . . .(stupido).");
            }elseif ($session[user][transferredtoday]>=getsetting("transferreceive",3)){
                output("`2Hai già ritirato la massima quantità d'oro possibile per oggi. Dovrai attendere fino a domani.");
            }else{
                $row[gold]-=$office;
                $session[user][gold]+=$office;
                $session[user][transferredtoday]+=1;
                $sql = "UPDATE houses SET gold = $row[gold] WHERE houseid = $row[houseid]";
                db_query($sql) or die(db_error(LINK));
                output("`2Prelevi `^$office`2 pezzi d'oro. Ci sono `^$row[gold]`2 Pezzi d'oro nella casa.");
                $sql = "INSERT INTO commentary (postdate,section,author,comment) VALUES (now(),'house-".$row[houseid]."',".$session[user][acctid].",'/me `\$ preleva`^ $office`\$ Oro.')";
                db_query($sql) or die(db_error(LINK));
                debuglog("preleva $office pezzi d'oro dalla tenuta ".$row['houseid']);
            }
        }
        addnav("Torna alla Tenuta","houses.php?op=inside");
    }elseif ($_GET['act'] == "givegold"){
        $maxout = $session[user][level]*getsetting("maxtransferout",25);
        if (!$_POST[gold]){
            $transleft = $maxout - $session[user][amountouttoday];
            output("`2Puoi depositare fino a `^$transleft`2 pezzi d'oro oggi. `n");
            output("`2<form action=\"houses.php?op=inside&act=givegold\" method ='POST'>",true);
            output("Deposita `nQuanto oro? <input type = 'gold' name = 'gold'>`n`n",true);
            output("<input type = 'submit' class = 'button' value = 'Deposita'>",true);
            addnav("","houses.php?op=inside&act=givegold");
        }else{
            $office=abs((int)$_POST[gold]);
            if ($office>$session[user][gold]){
                output("`2Non hai tutto quell'oro da depositare.");
            }elseif ($row[gold]>round($goldcost/3*4)){
                output("`2La cassaforte è piena.");
            }elseif ($office>(round($goldcost/3*4)-$row[gold])){
                output("`2La tua cassaforte non può contenere tanto.");
            }elseif ($office<0){
                output("`2Prova ad inserire un importo reale la prossima volta.");
            }elseif ($session[user][amountouttoday] +$office > $maxout){
                output("`2Puoi depositare solamente `^$maxout`2 pezzi d'oro al giorno nelle Tenute Reali.");
            }else{
                $row[gold]+=$office;
                $session[user][gold]-=$office;
                $session[user][amountouttoday]+=$office;
                output("`2Hai depositato `^$office`2 pezzi d'oro. C'è un totale di  `^$row[gold]`2 Pezzi d'Oro nella casa.");
                $sql = "UPDATE houses SET gold = $row[gold] WHERE houseid = $row[houseid]";
                db_query($sql) or die(db_error(LINK));
                $sql="INSERT INTO commentary (postdate,section,author,comment) VALUES (now(),'house-".$row[houseid]."',".$session[user][acctid].",'/me `@deposita `^$office`@ Oro.')";
                db_query($sql) or die(db_error(LINK));
                debuglog("deposita $office pezzi d'oro nella tenuta ".$row['houseid']);
            }
        }
        addnav("Torna alla Tenuta","houses.php?op=inside");
    }elseif ($_GET['act'] =="takegems"){
        if (!$_POST[gems]){
            output("`2Hai `#$chiave[gems]`2 Gemme nella Cassaforte della Tenuta. `n`n");
            output("`2<form action=\"houses.php?op=inside&act=takegems\" method ='POST'>",true);
            output("`nPreleva quante Gemme? <input type = 'gems' name = 'gems'>`n`n",true);
            output("<input type = 'submit' class = 'button' value = 'Ritira'>",true);
            addnav("","houses.php?op=inside&act=takegems");
        }else{
            $office=abs((int)$_POST[gems]);
            if ($office>$chiave[gems]){
                output("`2Non ci sono così tante gemme nella tua cassaforte.");
            }elseif ($office<0){
                output("`2Non puoi ritirare così tante gemme.");
            }else{
                $chiave[gems]-=$office;
                $session[user][gems] +=$office;
                if ($session[user][house] == $chiave[value1]){
                   $aggiornaGemmeSql = "UPDATE items SET gems = $chiave[gems] WHERE owner=".$session['user']['acctid']." AND class='Key' AND value1=".$row['houseid']." AND value2=10";
                }else{
                   $aggiornaGemmeSql = "UPDATE items SET gems = $chiave[gems] WHERE owner=".$session['user']['acctid']." AND class='Key' AND value1=".$row['houseid'];
                }
                db_query($aggiornaGemmeSql);
                output("`2Prelevi `#$office`2 gemme. Hai ancora `#$chiave[gems]`2 Gemme nella cassaforte.");
                $sql ="INSERT INTO commentary (postdate,section,author,comment) VALUES (now(),'house-".$row[houseid]."',".$session[user][acctid].",'/me `\$` preleva $office`\$ Gemme.')";
                db_query($sql) or die(db_error(LINK));
                debuglog("preleva $office gemme dalla tenuta ".$row['houseid']);
            }
        }
        addnav("Torna alla Tenuta","houses.php?op=inside");
    }elseif ($_GET['act'] =="givegems"){
        if (!$_POST[gems]){
            output("`2<form action=\"houses.php?op=inside&act=givegems\" method ='POST'>",true);
            output("`nQuante gemme vuoi depositare? <input type = 'gems' name = 'gems'>`n`n",true);
            output("<input type = 'submit' class = 'button' value = 'Deposita'>",true);
            addnav("","houses.php?op=inside&act=givegems");
            //    }elseif ($row[gems]>(2*$gemcost)){
            //        output("`2La cassaforte è piena.");
        }else{
            $office=abs((int)$_POST[gems]);
            if ($office>$session[user][gems]){
                output("`2Non hai tutte quelle gemme!");
            }elseif ($office>(100-$chiave[gems])){
                output("`2Provi a depositare una tonnellata di gemme nella cassaforte, ma si rovesciano tutto intorno. `nPuoi depositare al massimo `^100 `2gemme.");
            }elseif ($office<0){
                output("`2Non ne hai abbastanza da ritirare.");
            }else{
                $chiave[gems] +=$office;
                $session[user][gems] -=$office;
                if ($session[user][house] == $chiave[value1]){
                   $aggiornaGemmeSql = "UPDATE items SET gems = $chiave[gems] WHERE owner=".$session[user][acctid]." AND class='Key' AND value1=".$row['houseid']." AND value2=10";
                }else{
                   $aggiornaGemmeSql = "UPDATE items SET gems = $chiave[gems] WHERE owner=".$session[user][acctid]." AND class='Key' AND value1=".$row['houseid'];
                }
                db_query($aggiornaGemmeSql);
                output("`2Depositi `#$office`2 gemme. Hai un totale di `#$chiave[gems]`2 Gemme nella cassaforte.");
                $sql = "INSERT INTO commentary (postdate,section,author,comment) VALUES (now(),'house-". $row[houseid]."',".$session[user][acctid].",'/me `@deposita `#$office`@ Gemme.')";
                db_query($sql) or die(db_error(LINK));
                debuglog("deposita $office gemme nella tenuta ".$row['houseid']);
            }
        }
        addnav("Torna alla Tenuta","houses.php?op=inside");
    }elseif ($_GET['act'] =="rename"){
        if (!$_POST['housename']){
            output("`2Rinominare la casa costa `^1000`2 pezzi d'oro e `#1`2 gemma.`n");
            output("`@(Hai a disposizione 30 caratteri compresi quelli per il cambio colore)`n`n");
            output("`0<form action=\"houses.php?op=inside&act=rename\" method ='POST'>",true);
            output("`nScegli un nuovo nome per la tua Tenuta: <input name = 'housename' maxlength='30' size='35'>`n",true);
            output("<input type = 'submit' class = 'button' value = 'Rinomina'>",true);
            addnav("","houses.php?op=inside&act=rename");
        }elseif ($_POST['housename'] AND $_GET['act5']!="si"){
            output("`2Ecco come apparirà il nome della tua tenuta: `@".stripslashes($_POST['housename']));
            output("`n`n`2Se ti ritieni soddisfatt".($session['user']['sex']?"a":"o")." copia il codice qui ");
            output("sotto nel riquadro e clicca su prosegui.`n`n`&");
            rawoutput(stripslashes($_POST['housename']));
            addnav("`\$No","houses.php?op=inside&act=rename");
            output("`n`n<form action=\"houses.php?op=inside&act=rename&act5=si\" method ='POST'>",true);
            output("<input name = 'housename' maxlength='30' size='35'>`n",true);
            output("<input type = 'submit' class = 'button' value = 'Procedi'>",true);
            addnav("","houses.php?op=inside&act=rename&act5=si");
        }elseif ($_POST['housename'] AND $_GET['act5']=="si"){
            if ($session['user']['gold']<1000 || $session['user']['gems']<1){
                output("`2Non puoi permettertelo.");
            }else{
                //$_POST['housename'] = $_GET['act5'];
                output("`2La tua casa `@".$row['housename']."`2 ora si chiama `@".stripslashes($_POST['housename'])."`2.");
                $sql = "UPDATE houses SET housename ='".$_POST['housename']."' WHERE houseid = $row[houseid]";
                db_query($sql);
                $session['user']['gold'] -=1000;
                $session['user']['gems'] -=1;
            }
        }
        addnav("Torna alla Tenuta","houses.php?op=inside");
    }elseif ($_GET['act'] =="desc"){
        if (!$_POST[desc]){
            output("`2Puoi modificare la descrizione della tua Tenuta. `n`nL'attuale descrizione recita:`0$row[description]`0`n");
            output("`0<form action=\"houses.php?op=inside&act=desc\" method ='POST'>",true);
            output("`n`2Inserisci la nuova Descrizione: `n<input name = 'desc' maxlength='350' size='50'>`n",true);
            output("<input type = 'submit' class = 'button' value = 'Sottoponi'>",true);
            addnav("","houses.php?op=inside&act=desc");
        }else{
            output("`2La descrizione è stata modificata.`n`0".stripslashes($_POST[desc])."`2.");
            $sql = "UPDATE houses SET description ='".$_POST[desc]."' WHERE houseid = $row[houseid]";
            db_query($sql);
        }
        addnav("Torna alla Tenuta","houses.php?op=inside");

    }elseif ($_GET['act'] =="nanna"){ // Serve per capire chi tra i possessori di chiave stia dormendo in casa e chi no

        output("`2Fai il giro delle stanze della tua tenuta per verificare che tutto sia in ordine.`0`n`n");
        output("`c`b`2Inquilini della tenuta \"`0$row[housename]`2\"`b`c");

        $inquiliniSql = "SELECT u.name, u.login, u.alive, u.location, u.sex, u.level, u.jail, u.laston, u.loggedin, u.lastip, u.uniqueid, u.acctid, u.reincarna, u.dragonkills, u.race, u.carriera, i.hvalue FROM accounts u, items i WHERE u.acctid = i.owner AND i.value1=".(int)$session[housekey]." AND u.house <> $row[houseid] AND i.class='Key' ORDER BY i.value2";

        $inquilini = db_query($inquiliniSql) or die(sql_error($inquiliniSql));
        $numeroInquilini = db_num_rows($inquilini);
        output("<table border=0 cellpadding=2 cellspacing=1 align=center bgcolor='#999999'>",true);
        output("<tr class='trhead'><td><b>Vivo</b></td><td><b>Reinc.</b></td><td><b>DK</b></td><td><b>Level</b></td><td><b>Nome</b></td><td><b>Razza</b></td><td><b>Carriera</b></td><td><b>Posizione</b></td><td><b><img src=\"images/female.gif\">/<img src=\"images/male.gif\"></b></td><td><b>Ult.Colleg.</b></tr>",true);
        for($i=0;$i<$numeroInquilini;$i++){
            $inquilino = db_fetch_assoc($inquilini);
            output("<tr class='".($i%2?"trdark":"trlight")."'><td>",true);
            output($inquilino[alive]?"`1Si`0":"`4No`0");
            output("</td><td>",true);
            if (!$inquilino['reincarna']) {
                output("`4No`0");
            }else {
                output("`!{$inquilino[reincarna]}");
            }
            output("</td><td>",true);
            output("`#$inquilino[dragonkills]");
            output("</td><td>",true);
            output("`^$inquilino[level]`0");
            output("</td><td>",true);
            output("<a href=\"mail.php?op=write&to=".rawurlencode($inquilino['login'])."\" target=\"_blank\" onClick=\"".popup("mail.php?op=write&to=".rawurlencode($inquilino['login'])."").";return false;\"><img src='images/newscroll.GIF' width='16' height='16' alt='Write Mail' border='0'></a>",true);
            if ($session[user][loggedin]) output("<a href='bio.php?char=".rawurlencode($inquilino['login'])."&ret=".urlencode($_SERVER['REQUEST_URI'])."'>",true);
            if ($session[user][loggedin]) addnav("","bio.php?char=".rawurlencode($inquilino['login'])."&ret=".urlencode($_SERVER['REQUEST_URI']));
            output("`".($inquilino[acctid]==getsetting("hasegg",0)?"^":"&")."$inquilino[name]`0");
            if ($session[user][loggedin]) output("</a>",true);
            output("</td><td>",true);
            output("{$races[$inquilino[race]]}");
            output("</td><td>",true);
            output("{$prof[$inquilino[carriera]]}");
            output("</td><td>",true);
            $loggedin=(date("U") - strtotime($inquilino[laston]) < getsetting("LOGINTIMEOUT",900) && $inquilino[loggedin]);

            if ($inquilino[jail]==1){
                output("`\$In prigione`0");
            } elseif ($inquilino[location]==2) {
                if($inquilino[hvalue] == $row[houseid]){
                    output("`6In Casa`0");
                } else {
                    output("`\$In un'altra Casa`0");
                }
            } elseif ($inquilino[location]==1) {
                output("`%Locanda da Cedrik`0");
            } elseif ($inquilino[location]==0) {
                output($loggedin?"`#Collegato`0":"`3Nei Campi`0");
            }
            output("</td><td>",true);
            output($inquilino[sex]?"<img src=\"images/female.gif\">":"<img src=\"images/male.gif\">",true);
            output("</td><td>",true);
            $laston=round((strtotime(date("r"))-strtotime($inquilino[laston])) / 86400,0)." giorni";
            if (substr($laston,0,2)=="1 ") $laston="1 giorno";
            if (date("Y-m-d",strtotime($inquilino[laston])) == date("Y-m-d")) $laston="Oggi";
            if (date("Y-m-d",strtotime($inquilino[laston])) == date("Y-m-d",strtotime(date("r")."-1 day"))) $laston="Ieri";
            if ($loggedin) $laston="Adesso";
            output($laston);
            output("</td></tr>",true);
        }
        output("</table>",true);

        addnav("Torna alla Tenuta","houses.php?op=inside");

    }elseif ($_GET['act'] == "dichiara"){
        if ($_GET['fede']==""){
           $inquiliniSql = "SELECT u.name, u.dio, i.hvalue FROM accounts u, items i WHERE u.acctid = i.owner AND i.value1=".(int)$session[housekey]." AND u.house <> $row[houseid] AND i.class='Key' ORDER BY i.value2";
           $inquilini = db_query($inquiliniSql) or die(sql_error($inquiliniSql));
           $numeroInquilini = db_num_rows($inquilini);
           output("<table border=0 cellpadding=2 cellspacing=1 align=center bgcolor='#999999'>",true);
           output("<tr class='trhead'><td><b>Nome Inquilino</b></td><td><b>Fede</b></td></tr>",true);
           for($i=0;$i<$numeroInquilini;$i++){
               $inquilino = db_fetch_assoc($inquilini);
               output("<tr class='".($i%2?"trdark":"trlight")."'><td>",true);
               output("`^".$inquilino['name']."`0");
               output("</td><td>",true);
               output("`^".$fededio[$inquilino['dio']]."`0");
               output("</td></tr>",true);
               if ($inquilino['dio'] != $session['user']['dio']) $contofede++;
           }
           output("</table>",true);
           if ($contofede != 0){
              output("`n`3Purtroppo nella tua casa ci sono ospiti non appartenenti alla tua stessa fede, non puoi quindi ");
              output("dichiarare la fede per la tua tenuta. Dovrai prima togliere le chiavi a questi inquilini, ed in seguito ");
              output("potrai fare la tua dichiarazione di appartenenza.`n`n");
              addnav("Torna alla Tenuta","houses.php?op=inside");
           } else {
              output("`n`3Dichiarare la propria fede per la tenuta è un atto impegnativo e vincolante. Non potrai tornare ");
              output("sui tuoi passi, quindi pensaci bene prima di prendere una decisione avventata.`nSei sicuro di ");
              output("voler dichiarare la tua fede e far diventare la tua tenuta la dimora del tuo dio?`n");
              addnav("`@SI!!","houses.php?op=inside&act=dichiara&fede=si");
              addnav("`\$NO!!","houses.php?op=inside");
           }
        }else{
           output("`n`#Hai dichiarato di voler associare la tua fede alla tua tenuta. Da ora in avanti potranno entrarci solo ");
           output("coloro i quali apparterranno alla tua stessa setta, e se decideranno di cambiare fede non potranno più ");
           output("entrare nella tua tenuta. Allo stesso modo non potrai dare le chiavi della tua proprietà a giocatori di ");
           output("fede diversa o agnostici.`n");
           $sqlfede = "UPDATE houses SET fede = ".$session['user']['dio']." WHERE owner = ".$session['user']['acctid'];
           db_query($sqlfede) or die(sql_error($sqlfede));
           addnews($session['user']['name']." `2ha dichiarato la sua fede di ".$fededio[$session['user']['dio']]." `2trasformando
           la sua casa in un tempio dedicato alla sua divinità.");
           addnav("Torna alla Tenuta","houses.php?op=inside");

        }
    }elseif ($_GET['act'] == "rimuovi"){
        if ($session['user']['gems'] > 99){
           output("`#Sei sicuro di voler rimuovere la dichiarazione di fede dalla tua tenuta?`n");
              addnav("`@SI!!","houses.php?op=inside&act=rimuovi2");
              addnav("`\$NO!!","houses.php?op=inside");
        } else {
           output("`%Non hai le gemme richieste quale obolo per la rimozione della dichiarazione di fede. Procuratele prima di tornare.`n");
           addnav("Torna alla Tenuta","houses.php?op=inside");
        }
    }elseif ($_GET['act'] == "rimuovi2"){
           output("`3Hai deciso di rimuovere la dichiarazione di fede della tua tenuta. Ora potrai ospitare nuovamente ");
           output("qualunque player, indipendentemente dalla sua fede. Sii saggio nelle tue scelte, potresti pentirtene ");
           output("in futuro ...`n");
           addnav("Torna alla Tenuta","houses.php?op=inside");
           $sqlfede = "UPDATE houses SET fede = 0 WHERE owner = ".$session['user']['acctid'];
           $session['user']['gems'] -= 100;
           db_query($sqlfede) or die(sql_error($sqlfede));
           addnews($session['user']['name']." `2ha rimosso l'appartenenza della sua tenuta alla fede di ".$fededio[$session['user']['dio']]."`2. Ora
           potrà ospitare nuovamente qualsivoglia viandante, indipendentemente dalla sua fede.");

    }elseif ($_GET['act'] =="logout"){
        /*
        //Excalibur: registrazione dei tempi di connessione
        // per chi è rimasto connesso per più di X ore (X = 2)
        $check = getsetting("onlinetime",7200); //secondi oltre i quali viene registrato il tempo di connessione  7200 = 2 ore
        if ( (strtotime($session['user']['laston']) - strtotime($session['user']['lastlogin']) ) > $check) {
            $sql1 = "INSERT INTO furbetti
                     (type,acctid,logintime,logouttime)
                     VALUES ('time','".$session['user']['acctid']."','".$session['user']['lastlogin']."','".$session['user']['laston']."')";
            $result1 = db_query($sql1) or die(db_error(LINK));
        }
        //Excalibur: fine
        */
        $sql = "UPDATE items SET hvalue =".$session['housekey']." WHERE value1=".(int)$session['housekey']." AND owner =".$session[user][acctid]." AND class='Key'";
        db_query($sql) or die(sql_error($sql));
        $session['user']['loggedin'] = 0;
        $session['user']['sconnesso'] = 0;
        $session['user']['location'] = 2;
        $session['user']['locazione'] = 0;
        // hitpointspvp verra' usato per segnare gli hp tolti quando il personaggio viene attaccato nel sonno
        debuglog("va a dormire nella tenuta ".(int)$session['housekey']." ");
        $session['user']['hitpointspvp'] = $session['user']['maxhitpoints'];
        $sql = "UPDATE accounts SET loggedin=0,sconnesso=0,location=2,locazione=0,hitpointspvp=maxhitpoints WHERE acctid = ".$session['user']['acctid'];
        db_query($sql) or die(sql_error($sql));
        saveuser();
        $session=array();
        redirect("index.php");
    }else{
        if ($row['fede'] == 0 OR $row['fede'] == $session['user']['dio']){
        output("`2`b`c".$row['housename']."`7 in onore di ".$fedecasa[$row['fede']]."`c`b`n");
        if ($row['description']) output("`0`c".$row['description']."`c`n");
        $casanum = $row['houseid'];
        $gemmeincasa = getGemsInHouse($casanum);
        output("`2La cassaforte della tua Tenuta contiene `^$row[gold]`2 Pezzi d'Oro e `#$gemmeincasa`2 Gemme, di cui `#".$chiave['gems']."`2 sono tue. `nL'orologio segna le `^".getgametime()."`2. `n`n");
        viewcommentary("house-".$row['houseid'],"Chiacchere all'interno della casa:",30,"","dice","2");
        output("`n`n`n`2`bPossessori di Chiave: `b `0");
        $sql = "SELECT * FROM items WHERE value1=$row[houseid] AND class='key' ORDER BY id ASC";
        $result = db_query($sql) or die(db_error(LINK));
        $countrow = db_num_rows($result);
        for ($i=1; $i<=$countrow; $i++){
        //for ($i=1;$i<=db_num_rows($result);$i++){
            $item = db_fetch_assoc($result);
            $sql = "SELECT acctid, name, dio FROM accounts WHERE acctid = $item[owner] ORDER BY login DESC";
            $result2 = db_query($sql) or die(db_error(LINK));
            $row2 = db_fetch_assoc($result2);
            if ($row2[name] ==""){
                output("`n`2$i: `4`iPerso`i`0");
            }else{
                output("`n`2$i: `&".$row2['name']." `&(".$fededio[$row2['dio']]."`&)");
            }
            if ($row2['acctid'] == $row['owner']) output(" `&(`7proprietario`&)`0 ");
        }
        addnav("Oro");
        addnav("Deposita","houses.php?op=inside&act=givegold");
        addnav("Preleva","houses.php?op=inside&act=takegold");
        addnav("Gemme");
        addnav("Deposita","houses.php?op=inside&act=givegems");
        addnav("Preleva","houses.php?op=inside&act=takegems");
        addnav("Intrattenimenti");
        addnav("Roulette","roulette.php");
        addnav("Gioco delle Pietre","stonesgame.php");
        if ($session['user']['house'] == $session['housekey']){
            addnav("Amministrazione Chiavi");
            addnav("Dai Chiave","houses.php?op=inside&act=givekey");
            addnav("Ritira Chiave","houses.php?op=inside&act=takekey");
            addnav("Amministrazione Tenuta");
            addnav("Rinomina Tenuta","houses.php?op=inside&act=rename");
            addnav("Cambia Descrizione","houses.php?op=inside&act=desc");
            addnav("Rimbocca le Coperte","houses.php?op=inside&act=nanna");
            if ($row['fede'] == 0 AND $session['user']['dio'] != 0){
               addnav("Dichiara fede della Tenuta","houses.php?op=inside&act=dichiara");
            }
            if ($row['fede'] != 0){
               addnav("Rimuovi fede (100 gemme)","houses.php?op=inside&act=rimuovi");
            }
        }else{
            addnav("Amministrazione Chiavi");
            addnav("W?Restituisci Chiave","houses.php?op=inside&act=awaykey",false,false,true);
        }
        addnav("Altro");
        addnav("Torna alle Tenute Reali","houses.php");
        addnav("Torna al Villaggio","village.php");
        addnav("Q?Dormi (Logout)","houses.php?op=inside&act=logout");
        } else {
        output("`b`(Non hai più accesso a questa casa.`nProbabilmente hai cambiato fede, e l'accesso a questa casa ");
        output("è riservato solamente a chi è ".$fededio[$row['fede']].".`n`(Il proprietario è stato avvisato e presto ");
        output("ti sarà tolta la chiave.`b`n");
        $sql = "SELECT owner FROM houses WHERE houseid = ".$session['housekey'];
        $resultcf = db_query($sql) or die(db_error(LINK));
        $rowcf = db_fetch_assoc($resultcf);
        $mailmessage=$session['user']['name']." `#ha cambiato fede e non può più accedere alla tua casa. Ti consiglio di ";
        $mailmessage.="togliergli la chiave e di darla ad un altro player della tua stessa fede.";
        systemmail($rowcf['owner'],"`\$ATTENTIONE, cambio fede inquilino!!",$mailmessage);
        addnav("Torna alle Tenute Reali","houses.php");
        }
    }
}elseif ($_GET['op'] =="enter"){
    output("`@Hai accesso alle seguenti tenute: `n`n");
    $sql = "UPDATE items SET hvalue = 0 WHERE hvalue>0 AND owner =".$session['user']['acctid']." AND class='Key'";
    db_query($sql) or die(sql_error($sql));
    $sql = "SELECT * FROM items WHERE owner =".$session['user']['acctid']." AND class='key' ORDER BY id ASC";
    $result = db_query($sql) or die(db_error(LINK));
    output("<table cellpadding = 2 align = 'center'><tr><td>`bCasa#. `b</td><td>`bNome`b</td></tr>",true);
    if ($session['user']['house']>0 && $session['user']['housekey']>0){
        $sql = "SELECT houseid,housename FROM houses WHERE houseid=".$session['user']['house']." ORDER BY houseid DESC";
        $result2 = db_query($sql) or die(db_error(LINK));
        $row2 = db_fetch_assoc($result2);
        output("<tr><td align = 'center'>$row2[houseid]</td><td><a href='houses.php?op=inside&id=$row2[houseid]'>$row2[housename]</a> (tua proprietà)</td></tr>",true);
        addnav("","houses.php?op=inside&id=$row2[houseid]");
    }elseif ($session['user']['house']>0 && $session['user']['housekey'] ==0){
        output("<tr><td colspan = 2 align = 'center'>`&`iLa tua casa è attualmente in costruzione o in vendita.`i`0</td></tr>",true);
    }
    if (db_num_rows($result) ==0){
        output("<tr><td colspan = 4 align = 'center'>`&`iNon hai nessuna Tenuta`i`0</td></tr>",true);
    }else{
        $rebuy=0;
        $countrow = db_num_rows($result);
        for ($i=0; $i<$countrow; $i++){
        //for ($i=0;$i< db_num_rows($result);$i++){
            $item = db_fetch_assoc($result);
            if ($item[value1] ==$session['user']['house'] && $session['user']['housekey'] ==0) $rebuy=1;
            $bgcolor= ($i%2==1?"trlight":"trdark");
            //$sql = "SELECT houseid,housename FROM houses WHERE houseid = $item[value1] and status != 3 ORDER BY houseid DESC";
            $sql = "SELECT houseid,housename FROM houses WHERE houseid = $item[value1] and status = 1 ORDER BY houseid DESC";
            $result2 = db_query($sql) or die(db_error(LINK));
            $row2 = db_fetch_assoc($result2);
            if ($office !=$item[value1] && $item[value1] !=$session[user][house]){
                output("<tr class='$bgcolor'><td align = 'center'>$row2[houseid]</td><td><a href='houses.php?op=inside&id=$row2[houseid]'>$row2[housename]</a></td></tr>",true);
                addnav("","houses.php?op=inside&id=$row2[houseid]");
            }
            $office=$item[value1];
        }
    }
    output("</table>",true);
    if ($rebuy==1) addnav("Annulla la Vendita","houses.php?op=buy&id =".$session[user][house]."");
    addnav("Torna alle Tenute Reali","houses.php");
    addnav("Torna al Villaggio","village.php");
}else{
    output("`@`b`cLe Tenute Reali`c`b`n`n");
    $session[housekey] =0;
    $sql = "SELECT * FROM items WHERE owner =".$session[user][acctid]." AND class='key' ORDER BY id ASC";
    $result2 = db_query($sql) or die(db_error(LINK));
    if (db_num_rows($result2)>0 || $session[user][housekey]>0){
        addnav("Tenuta Reale");
        addnav("Entra nella Tenuta","houses.php?op=enter");
    }
    //first page, needs some serious description. . .
    output("`@Ti allontani dal villaggio in direzione dei Feudi Reali.  Qui i ricchi eroi e le gilde del regno stanno costruendo le loro costose abitazioni e i loro castelli.
            Le grandi tenute la fanno da padrone sui bellissimi giardini e le torri arazzate si profilano in lontananza sopra i lussureggianti appezzamenti delle tenute. Lungo
            le strade ventose che si allungano fra ogni lotto, vedi diverse incroci di stili e dinamiche create dagli Architetti Reali, Mastro Roark e Mastro Stonefist. `n`n
            Ti trovi sulla passeggiata vicino allo `3Studio di Architettura`@. Parecchi negozietti occupano la via di acciottolato, ciascuno specializzato in rari manufatti per
            la decorazione delle residenze. Sai che agli uccisori di draghi di livello elevato possono essere assegnati dei feudi tramite gli Architetti Reali, se dispongono dei
            mezzi per realizzare delle belle riproduzioni delle Tenute Reali. ");
    output("`n`n Tenute Reali:");
    $sql = "SELECT * FROM houses WHERE status<100 ORDER BY houseid ASC";
    output("<table cellpadding = 2 cellspacing = 1 bgcolor='#999999' align = 'center'><tr class = 'trhead'><td>`bCasa#. `b</td><td>`bNome`b</td><td>`bProprietario`b</td><td>`bStato`b</td><td>`bFede`b</td></tr>",true);
    $result = db_query($sql) or die(db_error(LINK));
    if (db_num_rows($result) ==0){
        output("<tr><td colspan = 4 align = 'center'>`&`iAncora nessuna Casa`i`0</td></tr>",true);
    }else{
        $countrow = db_num_rows($result);
        for ($i=0; $i<$countrow; $i++){
        //for ($i=0;$i<db_num_rows($result);$i++){
            $row = db_fetch_assoc($result);
            $bgcolor= ($i%2==1?"trlight":"trdark");
            output("<tr class='$bgcolor'><td align = 'right'>$row[houseid]</td><td>$row[housename]</td><td>",true);
            $sql = "SELECT name FROM accounts WHERE acctid = $row[owner] ORDER BY acctid DESC";
            $result2 = db_query($sql) or die(db_error(LINK));
            $row2 = db_fetch_assoc($result2);
            output("$row2[name]</td><td>",true);
            if ($row['status'] ==0) output("`6In Costruzione`0");
            elseif ($row['status'] ==1) output("`!Abitata`0");
            elseif ($row['status'] ==2) output("`^In Vendita`0");
            elseif ($row['status'] ==3) output("`4Abbandonata`0");
            elseif ($row['status'] ==4) output("`\$In Rovina`0");
            output("</td><td>",true);
            output($fedecasa[$row['fede']]);
            output("</td></tr>",true);
        }
    }
    output("</table>",true);
    if ($session['user']['housekey']){
        output("`nTieni alta la testa e cammini a grandi passi con sicurezza mentre girovaghi per le Tenute Reali.
                Trattieni a stento un sorriso di compiacimento allungando la mano nella tasca e toccando la tua chiave con
                una punta di orgoglio.");
    }
    addnav("Passeggiata Reale");
    if ($session['user']['house'] && $session['user']['housekey']){
        addnav("Agenzia di Mediazione delle Tenute","houses.php?op=sell");
    }else{
        if (!$session['user']['house']) addnav("Acquista una Casa","houses.php?op=buy");
        addnav("Studio d'Architettura","houses.php?op=build");
    }
    //////////////////////ss//////////////////////////////////////////////////
    /////////////////////////specialty specific stuff here (rob, curse, bless)
    //Modifica by Excalibur per dar la possibilità a tutti di penetrare in una casa
    //Hugues modifica per abilitare/disabilitare possibilità di derubare una tenuta pur mantenendo attivi i pvp
	if (getsetting("housesfree",1) == 1) {
    	if (getsetting("pvp",1) == 1) {
	    	addnav("Deruba una Tenuta","houses.php?op=breakin");
		}
	}
    //  if (getsetting("pvp",1) ==1 && $session[user][specialty]==3) addnav("Deruba una Tenuta","houses.php?op=breakin");
    //  if (getsetting("pvp",1) ==1 && $session[user][specialty]==4) addnav("Deruba una Tenuta","houses.php?op=breakin");
    //  if (getsetting("pvp",1) ==1 && $session[user][specialty]==1) addnav("Curse an Estate","houses.php?op=darkcurse");

    // Here's where I'll add other shops and features to the main promenade.
    /////////////////////////////////////////////////////////////////////////
    addnav("Altro");
    addnav("F?Le Fattorie","slaves.php");
    addnav("Torna al Villaggio","village.php");
}
if ($fight){
    //ss///////////////////////////////////////////
    //we'll randomize your ability to keep bonuses. . . give the thieves a chance to get into an Estate if they have a bonus.
    if (count($session[bufflist])>0 && is_array($session[bufflist]) || $_GET[skill] !=""){
        $skillchance = e_rand(0,3);
        if ($skillchance==1){
            output("`&La fortuna sembra favorirti mentre affrontate le guardie. I tuoi bonus rimangono!`0");
        }
        if ($skillchance==2){
            output("`&Senti improvvisamente il sangue scorrere nelle vene mentre ti getti nello scontro!`0");
            $session[bufflist][600] = array("name"=>"`%Brama di Sangue",
                    "rounds"=>1,
                    "wearoff"=>"la guardia non ti teme più",
                    "atkmod"=>1.4,
                    "roundmsg"=>"Sei venuto a spargere terrore su questa tenuta. Il tuono ruggisce ad ogni tuo attacco e le guardie tremano!",
                    "activate"=>"offense");
        }
        if ($skillchance==3){
            $_GET[skill] ="";
            if ($_GET['skill'] =="") $session['user'] ['buffbackup'] =serialize($session['bufflist']);
            $session[bufflist]=array();
            output("`&I tuoi bonus svaniscono mentre combatti in terra nemica!`0");
        }
    }
    include "battle.php";
    if ($victory){
        output("`n`#Hai battuto il Guardiano di Quartiere e ora puoi saccheggiare la tenuta! `nGuadagni un po' di punti di esperienza per aver sistemato il guardiano.");
        addnav("Entra nella Casa","houses.php?op=breakin2&id=$session[housekey]");
        addnav("Torna al Villaggio","village.php");
        $session[user][experience] +=$session[user][level]*10;
        $session[user][turns] --;
        $badguy=array();
        debuglog("ha battuto il guardiano della casa e guadagna il 10% di exp");
    }elseif($defeat){
        output("`n`\$Il Guardiano di Quartiere continua a malmenarti. Finalmente, crolli sotto i colpi martellanti. Sei morto! `nPerdi il 10% della tua esperienza, ma non l'oro. `nPotrai combattere nuovamente domani.");
        $session[user][hitpoints] =0;
        $session[user][alive] =false;
        $session[user][experience] =round($session[user][experience]*0.9);
        $session[user][badguy] ="";
        addnews("`%".$session[user][name]."`3 è stat".($session[user][sex]?"a":"o")." battut".($session[user][sex]?"a":"o")." dai Guardiani di Quartiere mentre tentava di derubare una tenuta.");
        addnav("Notizie Giornaliere","news.php");
    }else{
        fightnav(false,true);
    }
}
page_footer();
?>