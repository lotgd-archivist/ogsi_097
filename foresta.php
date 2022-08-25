<?php
/*
CREATE TABLE foresta (
  id bigint(20) unsigned NOT NULL auto_increment,
  data date NOT NULL default '0000-00-00',
  materiale varchar(10) NOT NULL default '0',
  acctid int(10) unsigned NOT NULL default '0',
  PRIMARY KEY  (id)
) TYPE=MyISAM;
*/
/*
ALTER TABLE `accounts` ADD `boscaiolo` DECIMAL( 3, 2 ) NOT NULL ;
*/

/*
 *
 * Lavora 1
 * Ontano: E’ utilizzato in falegnameria per lavori d’intaglio ed oggetti d’uso quotidiano
 *
 * Lavora 2
 * Betulla: Il legno bianco, elastico e resistente è utilizzato per lavori di falegnameria ed oggetti di uso domestico
 *
 * Lavora 3
 * Faggio: si adopera per fabbricare sedie e mobili
 *
 * Lavora 4
 * Leccio (Quercia): usato per la costruzione di mobili, leve, assi di carri, attrezzi da falegname
 * Rovere (Quercia): botti, tini, mastelli e piccole imbarcazioni, mobili, costruzioni, tutto insomma :-P
 *
 */

require_once("common.php");
require_once("common2.php");
addcommentary();
page_header("Il Bosco Oscuro");
$session['user']['locazione'] = 130;
$turni_mestiere=5;//aggiungere modifica per pass capanno
$stipendio = intval(($session['user']['level']*25)/$turni_mestiere);
$account=$session['user']['acctid'];
//Sook, modifica per usura ascia
function usuraascia() {
    global $session;
    $sqlo = "SELECT usura, usuramax FROM oggetti WHERE id_oggetti = '{$session['user']['oggetto']}'";
    $resulto = db_query($sqlo) or die(db_error(LINK));
    $rowo = db_fetch_assoc($resulto);
    if ($rowo[usuramax]>0) {
        if ($rowo[usura]!= 1) {
            $sqlu = "UPDATE oggetti SET usura = '".($rowo[usura]-1)."' WHERE id_oggetti = '{$session['user']['oggetto']}'";
            db_query($sqlu) or die(db_error($link));
        }else{
            $sqlogg = "DELETE FROM oggetti WHERE id_oggetti = ".$session['user']['oggetto'];
            $result = db_query($sqlogg) or die(db_error(LINK));
            $session['user']['oggetto'] = 0;
            output("La tua ascia, già provata dai colpi precedenti, non ha retto ed a seguito dell'ennesimo colpo alla pianta, si è frantumata.`n");
            output("Senza più un'ascia con cui lavorare, non ti resta che ritornare al villaggio, dove forse potrai procurartene una nuova.`n");
            addnav("Torna all'ingresso", "foresta.php");
        }
    }
}
//fine funzione usura
if ($session['user']['turni_mestiere']<0) $session['user']['turni_mestiere']=0;

// Display turni lavoro
if ($_GET['op']!="fight"
and  $_GET['op']!="run"
and  $_GET['op']!="entra"
and  $_GET['op']!="addturns"
and  $_GET['op']!="addturns1"
and  $_GET['op']!="") {
    $turnilavoro=$session['user']['turni_mestiere'];
    if ($_GET['op']=="lavora1"
    or  $_GET['op']=="lavora2"
    or  $_GET['op']=="lavora3"
    or  $_GET['op']=="lavora4") {
       $turnilavoro--;
    }
    if ($turnilavoro<0) $turnilavoro=0;
    addnav("Turni lavoro: ".$turnilavoro, "");
}
// Inizio
if ($_GET['op']=="") {
    addnav("Luoghi");
    addnav("B?Entra nel Bosco", "foresta.php?op=entra");
    addnav("F?Torna alla Foresta", "forest.php");
    addnav("V?Torna al Villaggio", "village.php");
    output("`3Ti incammini sul sentiero che punta dritto al cuore della Foresta, dopo alcuni minuti giungi in un area rumorosa che sembra un cantiere.`3`n");
    output("`3Un Troll senza un braccio tiene con la mano sana una frusta e ti dice \"`2Ztiamo zerchando brazzia robuzte per lavorhare al thaglio del bozco, la phaga è di $stipendio monete per ogni thurno di lavoro. Però, dhevi procurharti un azzetta ... AMICO ... hahahaha !!!`3\"`n");
}

if ($_GET['op']=="entra") {
    $sqlo = "SELECT * FROM oggetti WHERE id_oggetti = '".$session['user']['oggetto']."'";
    $resultoo = db_query($sqlo) or die(db_error(LINK));
    $rowo = db_fetch_assoc($resultoo);
    addnav("Luoghi");
    if($rowo['nome']=='Ascia da boscaiolo'){
        output("`3Il Troll ti guarda e dice: \"`2Grhan bella azzetta .... AMICO ....., ora vai a lavorhare! Non fhare lo zcanzafatiche altrimenti assaggerhai quezta!`3\" e accarezza la frusta, con fare inquietante.`n");
        addnav("Z?Zona di Taglio", "foresta.php?op=taglio");
    }else{
        output("`3Provi a entrare nel Bosco ma il Troll ti ferma, ti guarda di sbieco e dice: \"`2 ... AMICO ... non vedo la tua azzetta, con coza intendi tirare giù gli alberi, con i Pugni ?! Non fhare il furbo con me comprhati prima un azzetta, e che zia reziztente!!`3\"`n");
    }
    addnav("F?Torna alla Foresta", "forest.php");
    addnav("V?Torna al Villaggio", "village.php");
}
// Inizio Assegnazione Turni Lavoro
if ($_GET['op']=="taglio") {
    output("`3La zona di Taglio.`3`n");
    output("`3Arrivi alla zona di taglio degli alberi, molti abitanti del villaggio si recano al lavoro nei vari sentieri, deve proprio essere un lavoro duro pensi, dall'aspetto delle loro facce!`3`n");
    output("`3Devi decidere quanti turni dedicare al lavoro, al momento ne hai `2".$session['user']['turni_mestiere']." !`3`n");
    output("`3Ogni turno che decidi di impiegare diventano $turni_mestiere turni di lavoro!`3`n");
    output("<form action='foresta.php?op=addturns' method='POST'>",true);
    output("`bTurni che vuoi lavorare: <input name='amt' size='3'> <input type='submit' class='button' value='Lavora'>",true);
    output("</form>",true);

    addnav("Luoghi");
    addnav("","foresta.php?op=addturns");
    addnav("S?Vai ai Sentieri", "foresta.php?op=zona_lavoro");
    addnav("E?Esci dalla Zona", "foresta.php");
}

if ($_GET['op']=="addturns") {
    $_POST['amt'] = floor($_POST['amt']);
    if ($_POST['amt']<0){
        output("`\$Perchhè vuoi prhendermi in ziro? Non puoi lavorhare un numhero di turni `bnegativo`b!!!`n");
        output("Riprova e non zbagliarti questa vholta !!!`n");
        addnav("Luoghi");
        addnav("Zona di scavo","foresta.php?op=taglio");
        addnav("E?Esci dalla Zona", "foresta.php");
    }else if ($_POST['amt']>$session['user']['turns']){
        output("`3Avevi solo ".$session['user']['turns']." turni!`3`n");
        output("Confermi che vuoi lavorare ".$session['user']['turns']." turno/i ?`n`n");
        addnav("Scegli");
        addnav("Si", "foresta.php?op=addturns2&amt=".$session['user']['turns']."");
        addnav("No", "foresta.php?op=taglio");
    }else{
        output("Confermi che vuoi lavorare {$_POST['amt']} turno/i ?`n`n");
        addnav("Si", "foresta.php?op=addturns2&amt=$_POST[amt]");
        addnav("No", "foresta.php?op=taglio");
    }
}

if ($_GET['op']=="addturns2") {
    $session['user']['turni_mestiere']+=($_GET['amt']*$turni_mestiere);
    $session['user']['turns']-=$_GET['amt'];
    output("Hai deciso di lavorare ".$_GET['amt']." turni, hai ottenuto ".($_GET['amt']*$turni_mestiere)." turni di lavoro.`n`n");
    addnav("Luoghi");
    addnav("S?Vai ai Sentieri", "foresta.php?op=zona_lavoro");
    addnav("E?Esci dalla Zona", "foresta.php");
    addnav("F?Torna alla Foresta", "forest.php");
    addnav("V?Torna al Villaggio", "village.php");
}
// Fine Assegnazione Turni Lavoro

// Inizio Zone di Lavoro
if ($_GET['op']=="zona_lavoro") {
    output("`3La Biforcazione`3`n");
    output("Arrivi nello slargo dove gli altri boscaioli che si recano al lavoro si dividono, che sentiero vuoi intraprendere ?`n`n");
    addnav("Luoghi");
    if ($session['user']['turni_mestiere']<=0) {
        output("`@Purtroppo hai finito i tuoi turni lavoro, devi tornare alla Zona di Taglio!`3`n");
        addnav("Z?Zona di Taglio", "foresta.php?op=taglio");
    } else {
        addnav("B?Sentiero Battuto", "foresta.php?op=battuto");
        addnav("n?Sentiero non Battuto", "foresta.php?op=nonbattuto");
        addnav("O?Selva Oscura", "foresta.php?op=selva");
    }
    addnav("E?Esci dalla Zona", "foresta.php");
    addnav("F?Torna alla Foresta", "forest.php");
    addnav("V?Torna al Villaggio", "village.php");
}
// Sentiero Battuto
if ($_GET['op']=="battuto") {
    output("`3Sentiero Battuto!`3`n");
    output("`3Questo grande sentiero battuto è il posto migliore per iniziare pensi, un vecchio boscaiolo sovraintende il lavoro dei tuoi compagni di lavoro e annota alcune cose su un taccuino, alcuni boscaioli portano in spalla dei tronchi di ontano.`3`n");
    addnav("Azioni");
    if ($session['user']['turni_mestiere']>0) {
        addnav("L?Lavora", "foresta.php?op=lavora1");
    }
    addnav("P?Parla al vecchio", "foresta.php?op=vecchio");
    addnav("Luoghi");
    addnav("S?Torna ai Sentieri", "foresta.php?op=zona_lavoro");
    addnav("Z?Zona di Taglio", "foresta.php?op=taglio");
}
// Sentiero non Battuto
if ($_GET['op']=="nonbattuto") {
    output("`3Sentiero Non Battuto!`3`n");
    output("`3Questo sentiero si addentra nelle in profondità e diventa man mano sempre più buio, forse non è una buona idea tagliar legna qui, ");
    output("ma d'altronde il rischio vale la candela, potresti trovare alberi migliori da abbattere, e quindi dovrai rischiare per ottenere qualcosa.`n");
    output("Anche qui un vecchio boscaiolo che sembra la fotocopia di quello visto nel sentiero battuto, prende degli appunti su un taccuino, alcuni boscaioli portano in spalla dei tronchi di faggio.`3`n");
    addnav("Azioni");
    if ($session['user']['turni_mestiere']>0) {
        addnav("L?Lavora", "foresta.php?op=lavora2");
    }
    addnav("P?Parla al vecchio", "foresta.php?op=vecchio2");
    addnav("Luoghi");
    addnav("S?Torna ai Sentieri", "foresta.php?op=zona_lavoro");
    addnav("Z?Zona di Taglio", "foresta.php?op=taglio");
}
// Selva Oscura
if ($_GET['op']=="selva") {
    output("`3La Selva Oscura!`3`n");
    output("`3Ti addentri in un Sentiero che riporta la scritta \"`4Nel mezzo del cammin di nostra vita mi ritrovai per una selva oscura ché la diritta via era smarrita...`3\", ");
    output("e appena cominci a muovere i primi passi senti sempre più vivo un profondo senso di inquietudine.");
    output("La camminata è estremamente lenta ed angosciante, il vento che scivola tra le fronde emette degli ululati animaleschi che ti fanno stringere ");
    output("con sempre più forza la tua Ascia da boscaiolo.");
    output("Un piccolo spiazzo ospita il solito vecchietto (ma sarà sempre lo stesso? ti chiedi), ");
    output("con il solito taccuino su cui prende appunti...`nTi saluta stancamente e ti augura buon lavoro.`n`n");
    addnav("Azioni");
    if ($session['user']['turni_mestiere']>0) {
        addnav("L?Lavora", "foresta.php?op=lavora3");
    }
    addnav("P?Parla al vecchio", "foresta.php?op=vecchio3");
    addnav("Luoghi");
    addnav("S?Torna ai Sentieri", "foresta.php?op=zona_lavoro");
    addnav("Z?Zona di Taglio", "foresta.php?op=taglio");
}
// I Tre Vecchietti
// Uno
if ($_GET['op']=="vecchio") {
    page_header("Il vecchio");
    output("`3Il vecchio ti guarda sospettoso, poi ti dice\"`6mmm .... per 10 pezzi d'oro ti dico quanto sei bravo a fare questo mestiere`3\"`n");
    addnav("Azioni");
    addnav("P?Paga 10 pezzi d'oro", "foresta.php?op=vecchio_bravo");
    addnav("Luoghi");
    addnav("B?Sentiero Battuto", "foresta.php?op=battuto");
    addnav("S?Torna ai Sentieri", "foresta.php?op=zona_lavoro");
}
if ($_GET['op']=="vecchio_bravo") {
    if ($session['user']['gold'] < 10) {
        output("Non hai abbastanza oro per pagarlo.`n");
    } else {
        output("`3Il vecchio dice \"`6I tuoi muscoli mi dicono che la tua abilità è : ".intval($session['user']['boscaiolo']) ." `3\"`n");
        $session['user']['gold']-=10;
    }
    addnav("Luoghi");
    addnav("B?Sentiero Battuto", "foresta.php?op=battuto");
    addnav("S?Torna ai Sentieri", "foresta.php?op=zona_lavoro");
}
// Due
if ($_GET['op']=="vecchio2") {
    page_header("Il vecchio gemello!!");
    output("`3Il vecchio ti guarda sospettoso, poi ti dice\"`6mmm .... per 10 pezzi d'oro ti dico quanto sei bravo a fare questo mestiere`3\"`n");
    addnav("Azioni");
    addnav("P?Paga 10 pezzi d'oro", "foresta.php?op=vecchio_bravo2");
    addnav("Luoghi");
    addnav("n?Sentiero non Battuto", "foresta.php?op=nonbattuto");
    addnav("S?Torna ai Sentieri", "foresta.php?op=zona_lavoro");
}
if ($_GET['op']=="vecchio_bravo2") {
    if ($session['user']['gold'] < 10) {
        output("Non hai abbastanza oro per pagarlo.`n");
    } else {
        output("`3Il vecchio dice \"`6I tuoi muscoli mi dicono che la tua abilità è : ".intval($session['user']['boscaiolo']) ." `3\"`n");
        $session['user']['gold']-=10;
    }
    addnav("Luoghi");
    addnav("n?Sentiero non Battuto", "foresta.php?op=nonbattuto");
    addnav("S?Torna ai Sentieri", "foresta.php?op=zona_lavoro");
}
// e tre, Virgilio :-D
if ($_GET['op']=="vecchio3") {
    page_header("Il vecchio gemello?!?!?");
    output("`3Il vecchio ti guarda con aria stanca e ti dice\"`6Non omo, omo già fui, e li parenti miei furon...`3\" poi si ferma a riflettere un secondo, scuote la testa e ricomincia a parlare ");
    output(" \"`6mmm stavo aspettando un altra persona.... ma per 10 pezzi d'oro ti dico quanto sei bravo a fare questo mestiere `3\"`n");

    output("`3A fianco del vecchio riesci a scorgere in lontananza quello che sembra un ENORME albero millenario e chiedi al vecchio informazioni.`n");
    output("Lui ti guarda e poi con voce stanca dice\"`6Ahh, quello`3\" e indica l'albero con l'indice. \"`6Quello è il famoso albero `^Thonetauer`3, anche detta Quercia `^Thonetauer`3\"`n");
    output("Fa una pausa come ricordando questo tizio, chiunque egli fosse, poi riprende \"`6Ha preso il suo nome da quando Thonetauer ha cercato di abbatterlo ");
    output("ma è stato risucchiato dalle sue radici e non ha fatto più ritorno. Buttarlo a terra è un'impresa che un solo boscaiolo ");
    output("non può affrontare, e ci vuole l'azione combinata di più taglialegna per esaminare quello che è sepolto lì sotto. Ma se vuoi il mio parere, lascerei stare, ");
    output("girano strane voci su quella quercia.`3\"`n`n");
    output("Sei indeciso se dar credito alle notizie del vecchio o ritenere tutto un'enorme frottola. La quercia è di fronte a te puoi verificare ");
    output("di persona se quello che racconta il vecchio è verità o no.");

    addnav("Azioni");
    addnav("P?Paga 10 pezzi d'oro", "foresta.php?op=vecchio_bravo3");
    addnav("Luoghi");
    addnav("Avvicinati alla Quercia", "foresta.php?op=quercia");
    addnav("O?Selva Oscura", "foresta.php?op=selva");
    addnav("S?Torna ai Sentieri", "foresta.php?op=zona_lavoro");


}
if ($_GET['op']=="vecchio_bravo3") {
    if ($session['user']['gold'] < 10) {
        output("Non hai abbastanza oro per pagarlo.`n");
    } else {
        output("`3Il vecchio dice \"`6I tuoi muscoli mi dicono che la tua abilità è : ".intval($session['user']['boscaiolo']) ." `3\"`n");
        $session['user']['gold']-=10;
    }
    addnav("Luoghi");
    addnav("O?Selva Oscura", "foresta.php?op=selva");
    addnav("S?Torna ai Sentieri", "foresta.php?op=zona_lavoro");
}
// La Quercia
if ($_GET['op']=="quercia") {
    page_header("La quercia millenaria");
    output("`3Il vecchio ti guarda e scuote la testa borbottando qualcosa su fantasmi e mostri terribili mentre ti avvicini alla quercia.`n");
    addnav("Luoghi");
    addnav("Avvicinati", "foresta.php?op=queravv");
    addnav("O?Selva Oscura", "foresta.php?op=selva");
}
if ($_GET['op']=="queravv") {
   page_header("La quercia millenaria");
   output("`3Ti avvicini alla quercia da {$_GET['op']}, l'aria inizia ad essere calda e una nebbiolina inquietante comincia a salire, non sei più tanto sicuro di voler continuare nell'impresa.`n");
   $caso=e_rand(1,10);
   if ($caso == 10 AND $_GET['op1']!="fatto"){
       output("`4L'ossigeno nell'aria è proprio al limite della sopravvivenza, e la tua mente inizia ad ottenebrarsi... all'improvviso vedi spuntare dagli alberi ");
       output("un `8Fantasma`4, sai che sono allucinazioni provocate dalla mancanza di ossigeno, ma sei obbligato a batterti!!`n`n");
       $dkb = round($session['user']['dragonkills']*.1);
       $badguy = array("creaturename"=>"`(Fantasma`0"
       ,"creaturelevel"=>1
       ,"creatureweapon"=>"`8Apparizione Orrenda`0"
       ,"creatureattack"=>6
       ,"creaturedefense"=>6
       ,"creaturehealth"=>2
       ,"diddamage"=>0);
       $userlevel=$session['user']['level'];
       $userattack=$session['user']['attack'];
       $userhealth=$session['user']['maxhitpoints'];
       $userdefense=$session['user']['defense'];
       $badguy['creaturelevel']+=$userlevel;
       $badguy['creatureattack']+=$userattack;
       $badguy['creaturehealth']=intval($userhealth*1.5);
       $badguy['creaturedefense']+=$userdefense;
       $session['user']['badguy']=createstring($badguy);
       addnav("Azioni");
       addnav("Combatti","foresta.php?op=fight");
       addnav("Scappa","foresta.php?op=run");
   }else{
       addnav("Luoghi");
       addnav("Torna dal Vecchio", "foresta.php?op=quercia");
       addnav("Avvicinati ancora", "foresta.php?op=fine");
   }
}
if ($_GET['op']=="fine") {
    page_header("La quercia millenaria");
    output("`3Sei arrivato finalmente alla Quercia, i suoi grandi rami fanno filtrare ben pochi raggi di sole, è ancora più grande di quanto ti aspettassi!");
    output("`nDovrai metterci tutto il tuo impegno, coordinato con quello di altri boscaioli per poterlo abbattere.");
    output("`n`nCosa vuoi fare?");
    addnav("Azioni");
    addnav("Abbatti la Quercia", "foresta.php?op=abbatti");
    addnav("Torna dal Vecchio", "foresta.php?op=quercia");
}
if ($_GET['op']=="abbatti") {
    page_header("La quercia millenaria");
    $dove_sei = 150;
    $session['user']['dove_sei'] = $dove_sei;
    $sqlpic = "SELECT acctid FROM accounts WHERE dove_sei={$dove_sei}";
    $resultpic = db_query($sqlpic) or die(db_error(LINK));
    $player_solleva = db_num_rows($resultpic);
    $sqlpic2="SELECT COUNT(*) AS boscaioli, a.oggetto, a.zaino, o.id_oggetti, o.nome
              FROM accounts a, oggetti o
              WHERE o.nome = 'Ascia da boscaiolo'
              AND (a.oggetto=o.id_oggetti OR a.zaino=o.id_oggetti)
              AND superuser = 0
              GROUP BY o.nome";
    $resultpic2 = db_query($sqlpic2) or die(db_error(LINK));
    $rowpic2 = db_fetch_assoc($resultpic2);
    $player_picconatori = $rowpic2['boscaioli'];
    $player_necessari = round($player_picconatori*.2);
    if($session['user']['superuser']>0){
        output("`#Player impegnati............................: $player_solleva`n");
        output("`@Player che possiedono un'accetta............: $player_picconatori`n");
        output("`^Player necessari per abbattere la quercia...: $player_necessari`n");
    }
    addnav("Azioni");
    if($session['user']['superuser']>0)addnav("`^Comincia a Lavorare (Admin)", "foresta.php?op=lavora4");
    if($player_solleva>=$player_necessari){
        output("`3Altri boscaioli sono arrivati alla grande quercia e cercate di coordinarvi ");
        output("tutti assieme per riuscire ad abbatterla. Lavorate come dei forsennati con le vostre accette ");
        output("ed alla fine riuscite a tirare giù la grande quercia millenaria. Senti qualcuno che grida, \"`&SPOSTATEVI DA LIIII!!!!!`3\"`n`n`n");
        addnav("Spostati!", "foresta.php?op=spostati");
        addnav("Torna dal Vecchio", "foresta.php?op=quercia");
    }else{
        output("`3Non riesci nemmeno a scalfire la superficie di questa magnifica pianta, pensi che se qualche altro boscaiolo ti aiutasse forse ....`n`n");
        addnav("Abbatti la Quercia", "foresta.php?op=abbatti");
        addnav("Torna dal Vecchio", "foresta.php?op=quercia");
    }
    viewcommentary("La Grande Quercia", "sbuffa",20,5);
}
if ($_GET['op']=="spostati") {
   output("`5Subito dopo aver sradicato la quercia vedi gli altri boscaioli che si disperdono in tutte le direzioni per paura che l'enorma albero ");
   output("possa travolgerli nel suo ultimo disperato tentativo di salvarsi. Ti butti velocemente dietro una siepe e vedi sparsi un po' dappertutto ");
   output("degli strumenti che tu conosci benissimo, forse erano dei precedenti boscaioli, e ti chiedi cosa mai li possa aver indotti ad abbandonarli.`n");
   if (rand(1,100)>80 AND $_GET['op1']!="fatto"){
      output("`4Quando poi inizi a vedere gli scheletri sparsi sul prato, un leggero brivido scorre lungo la tua spina dorsale ");
      output("ed il terrore pervade la tua mente.`nRicordi le parole del vecchio, al riguardo di un boscaiolo che ha dato il nome ");
      output("a questa sezione della foresta, e quando un accetta sfiora il tuo orecchio e si conficca sul terreno a pochi centimetri ");
      output("dai tuoi piedi, sai di aver commesso la sciocchezza più grossa della tua vita!!`n`^Thonetauer`\$, o per lo meno quel ");
      output("resta di lui, si staglia di fronte a te, dall'alto dei suoi 2 metri d'altezza, e ti fronteggia con un ghigno beffardo, ");
      output("reso ancor più orribile dal teschio biancastro che si intravede sotto le carni putrefatte del viso.`n");
      output("\"`7E così vorresti impossessarti del mio `)legno`7 eh? Sappi che questa zona della foresta è MIAAA!! SOLO MIAAA!!!`\$\" grida con voce ");
      output("cavernosa, rendendolo ancor più minaccioso. Poi presegue \"`7Nessuno può venire a ficcare il naso in questa zona, NESSUNO !!!`\$\"`n");
      output("Dopodichè si prepara al combattimento, e dovrai batterlo per poter continuare a vivere o andrai a far compagnia agli scheletri che ");
      output("giacciono ai tuoi piedi.`n`n");
      $badguy = array("creaturename"=>"`^Thonetauer il Boscaiolo`0"
            ,"creaturelevel"=>2
            ,"creatureweapon"=>"`6Ascia da boscaiolo`0"
            ,"creatureattack"=>7
            ,"creaturedefense"=>7
            ,"creaturehealth"=>3
            ,"diddamage"=>0);
            $userlevel=$session['user']['level'];
            $userattack=$session['user']['attack'];
            $userhealth=$session['user']['maxhitpoints'];
            $userdefense=$session['user']['defense'];
            $badguy['creaturelevel']+=$userlevel;
            $badguy['creatureattack']+=$userattack;
            $badguy['creaturehealth']=intval($userhealth*.8);
            $badguy['creaturedefense']+=$userdefense;
            $session['user']['badguy']=createstring($badguy);
            addnav("Azioni");
            addnav("Combatti","foresta.php?op=fight");
            addnav("Scappa","foresta.php?op=run");
   }else{
      output("`3Quando senti uno strano suono provenire da dietro un cespuglio, stai quasi per decidere di voltarti e tornare da ");
      output("dove sei venuto, ma con l'ultima scintilla di coraggio fai gli ultimi passi verso la grande quercia.`n Decidi di non perdere tempo e, accetta ");
      output("alla mano, ti avvicini ad essa per iniziare la potatura.`n`n");
      addnav("Azioni");
      addnav("Lavora", "foresta.php?op=lavora4");
   }
}
// Lavora (era ora...)
if ($_GET['op']=="lavora1") {
    $badguy=array();
    $hungerchance=rand(0,4);
    usuraascia();
    if ($session['user']['oggetto'] != 0) {
        if ($session['user']['superuser'] >= 3) output("Debug: Chance di avere fame $hungerchance`n");
        if ($hungerchance == 4) $session['user']['hunger'] += 1;
        $bladchance=rand(0,5);
        if ($session['user']['superuser'] >= 3) output("Debug: Chance di andare in bagno $bladchance`n");
        if ($bladchance == 5) $session['user']['bladder'] += 1;
        $cleanchance=rand(0,2);
        if ($session['user']['superuser'] >= 3) output("Debug: Chance di sporcarsi $cleanchance`n");
        if ($cleanchance == 2) $session['user']['clean'] += 2;
        $session['user']['gold']+=$stipendio;
        $session['user']['turni_mestiere']-=1;
        if($session['user']['boscaiolo']< 5)$session['user']['boscaiolo']+=0.01;
        if($session['user']['boscaiolo']< 10)$session['user']['boscaiolo']+=0.01;
        output("`3Lavora!`3`n");
        output("`3Impugni la tua accetta e colpisci un albero cercando di abbatterlo!`3`n");
        $forza=intval(($session['user']['attack']+$session['user']['defence'])/100);
        if($forza==0)$forza=1;
        //result è una percentuale il massimo fissato indica la massima possibilità di riuscita
        $result=$session['user']['boscaiolo']+$forza;
        $cento=rand(1, 100);
        addnav("Azioni");
        $cento=1;
        if($session['user']['superuser']>0)output("`@[DEBUG] result --> {$result}`n");
        if($session['user']['superuser']>0)output("`@[DEBUG] cento --> {$cento}`n");
        if($result >= $cento){
            savesetting("ontano",getsetting("ontano",0)+1);
            $sqldr="INSERT INTO foresta (acctid,data,materiale) VALUES ('$account',FROM_UNIXTIME(UNIX_TIMESTAMP()),'Ontano')";
            db_query($sqldr) or die(db_error(LINK));
            $fatica=rand(0,1);
            $session['user']['hitpoints']-=$fatica;
            if ($session['user']['hitpoints'] <=0) $session['user']['hitpoints']=1;
            output("`#Un buon colpo!`3`n");
            $session['user']['boscaiolo']+=0.01;
            $caso=rand(1, 100);
            if($session['user']['superuser']>0)output("`@[DEBUG] Caso --> {$caso}`n");
            if($caso==100){
                output("`#Dalla corteccia dell'albero si apre una fessura da dove affiorano delle gemme ... le conti e scopri ");
                output("che sono `^`b5`#`b!!!!`nQuesto si che è un colpo di fortuna!! Un po' meno per chi le aveva nascoste lì ma ormai sono tue!`3`n");
                debuglog("trova 5 gemme nella foresta dei boscaioli");
                $session['user']['gems']+=5;
                if ($session['user']['turni_mestiere']>0) {
                    addnav("Lavora", "foresta.php?op=lavora1");
                }
                addnav("S?Vai ai Sentieri", "foresta.php?op=zona_lavoro");
            }elseif($caso>=95 AND $caso< 100){
                output("`#Dalla corteccia dell'albero si apre una fessura da dove affiora una gemma, che prontamente infili in tasca!`3`n");
                $session['user']['gems']+=1;
                debuglog("trova una gemma nella foresta dei boscaioli");
                if ($session['user']['turni_mestiere']>0) {
                    addnav("Lavora", "foresta.php?op=lavora1");
                }
                addnav("S?Vai ai Sentieri", "foresta.php?op=zona_lavoro");
            }elseif($caso>=94 AND $caso< 95){
                output("`#Dal colpo che hai dato, si apre una spaccatura nella corteccia, ed una pergamena viene alla luce.`n");
                output("La esamini e scopri che è una ricetta, felice per il ritrovamento la metti nello zaino!`3`n");
                if (zainoPieno($session['user']['acctid'])){
                    output ("Purtroppo ti accorgi che lo zaino è pieno e a malincuore getti la getti via, un vero peccato...");
                }else{
                    $sqldr="INSERT INTO zaino (idoggetto,idplayer) VALUES ('25','$account')";
                    db_query($sqldr) or die(db_error(LINK));
                    debuglog("trova ricetta 25 nella foresta dei boscaioli");
                }
                if ($session['user']['turni_mestiere']>0) {
                    addnav("Lavora", "foresta.php?op=lavora1");
                }
                addnav("S?Vai ai Sentieri", "foresta.php?op=zona_lavoro");
            }elseif($caso>=89 AND $caso< 94){
                output("`#AAAAARGGHHH!! Quell'albero era la tana di un Ragno Velenoso!`3`n");
                $badguy = array("creaturename"=>"`8Ragno Velenoso`0"
                ,"creaturelevel"=>1
                ,"creatureweapon"=>"`(Morso Velenoso`0"
                ,"creatureattack"=>2
                ,"creaturedefense"=>3
                ,"creaturehealth"=>3
                ,"diddamage"=>0
                );
                $userlevel=$session['user']['level'];
                $userattack=$session['user']['attack'];
                $userhealth=$session['user']['maxhitpoints'];
                $userdefense=$session['user']['defense'];
                $badguy['creaturelevel']+=$userlevel;
                $badguy['creatureattack']+=$userattack;
                $badguy['creaturehealth']=intval($userhealth*1.5);
                $badguy['creaturedefense']+=$userdefense;
                $session['user']['badguy']=createstring($badguy);
                $session['bufflist']['foresta'] = array(
                "startmsg"=>"`n`(Il veleno circola nelle tue vene!`n`n",
                "name"=>"`(Morso Velenoso`0",
                "rounds"=>15,
                "wearoff"=>"Il veleno ha esaurito il suo effetto",
                "minioncount"=>$session['user']['level'],
                "mingoodguydamage"=>1,
                "maxgoodguydamage"=>2+$dkb,
                "effectmsg"=>"Il veleno ti causa {damage} punti danno.",
                "effectnodmgmsg"=>"Il veleno non ha avuto nessun effetto.",
                "activate"=>"roundstart",
                );
                addnav("Combatti","foresta.php?op=fight");
                addnav("Scappa","foresta.php?op=run");
            }elseif($caso==88){
                output("`4Purtroppo non hai sentito le grida di avvertimento di un altro boscaiolo.");
                output("`nUn albero ti crolla addosso e muori solo e tra dolori atroci!!!`n");
                output("`\$Hai perso il 5% della tua esperienza !!!`n");
                addnews("`%".$session['user']['name']."`5 è morto schiacciato da un albero, mentre lavorava nella foresta!!");
                debuglog("è morto schiacciato da un albero in foresta e ha perso ".$session['user']['gold']." oro");
                $session['user']['experience'] *= 0.95;
                $session['user']['alive'] = false;
                $session['user']['hitpoints'] = 0;
                if($session['user']['assicurazione']==1) $session['user']['goldinbank']+=$session['user']['gold'];
                $session['user']['gold'] = 0;
                addnav("Notizie Giornaliere","news.php");
            }elseif($caso>=83 AND $caso< 88){
                output("`#AAAAARGGHHH!! Quell'albero era la tana di alcune Tarme Carnivore!`3`n");
                $badguy = array("creaturename"=>"`4Tarme Carnivore`0"
             ,"creaturelevel"=>0
                ,"creatureweapon"=>"`\$Infestazione`0"
                ,"creatureattack"=>1
                ,"creaturedefense"=>3
                ,"creaturehealth"=>2
                ,"diddamage"=>0);
                $userlevel=$session['user']['level'];
                $userattack=$session['user']['attack'];
                $userhealth=$session['user']['maxhitpoints'];
                $userdefense=$session['user']['defense'];
                $badguy['creaturelevel']+=$userlevel;
                $badguy['creatureattack']+=$userattack;
                $badguy['creaturehealth']=intval($userhealth*1.3);
                $badguy['creaturedefense']+=$userdefense;
                $session['user']['badguy']=createstring($badguy);
                addnav("Combatti","foresta.php?op=fight");
                addnav("Scappa","foresta.php?op=run");
            }else{
                if ($session['user']['turni_mestiere']>0) {
                    addnav("Lavora", "foresta.php?op=lavora1");
                }
                addnav("S?Vai ai Sentieri", "foresta.php?op=zona_lavoro");
            }
        }else{
            output("`vImpegnati di più, mettici un po' di energia in quei colpi!`3`n");
            $fatica=rand(0, (intval($session['user']['level']/5)+1));
            $session['user']['hitpoints']-=$fatica;
            if ($session['user']['hitpoints'] <= 0) $session['user']['hitpoints']=1;
            $caso=rand(1, 100);
            if($caso>=86 AND $caso< 89){
                output("`#AAAAARGGHHH!! Su quell'albero c'era un nido di Vespe!`3`n");
                $badguy = array("creaturename"=>"`8Vespe Arrabbiate`0"
                ,"creaturelevel"=>0
                ,"creatureweapon"=>"`(Pungiglione Appuntito`0"
                ,"creatureattack"=>1
                ,"creaturedefense"=>2
                ,"creaturehealth"=>2
                ,"diddamage"=>0);
                $userlevel=$session['user']['level'];
                $userattack=$session['user']['attack'];
                $userhealth=$session['user']['maxhitpoints'];
                $userdefense=$session['user']['defense'];
                $badguy['creaturelevel']+=$userlevel;
                $badguy['creatureattack']+=$userattack;
                $badguy['creaturehealth']=intval($userhealth*1.2);
                $badguy['creaturedefense']+=$userdefense;
                $session['user']['badguy']=createstring($badguy);
                $session['bufflist']['foresta'] = array(
                "startmsg"=>"`n`(Le vespe ti pizzicano!`n`n",
                "name"=>"`(Pungiglioni`0",
                "rounds"=>15,
                "wearoff"=>"Le vespe non hanno più i loro pungiglioni",
                "minioncount"=>$session['user']['level'],
                "mingoodguydamage"=>1,
                "maxgoodguydamage"=>2+$dkb,
                "effectmsg"=>"Le vespe ti pizzicano causandoti {damage} punti danno.",
                "effectnodmgmsg"=>"Le vespe non riescono a pizzicarti.",
                "activate"=>"roundstart",
                );
                addnav("Combatti","foresta.php?op=fight");
                addnav("Scappa","foresta.php?op=run");
            }elseif($caso==85){
                output("`4Purtroppo non hai sentito le grida di avvertimento di un altro boscaiolo.");
                output("`nUn albero ti crolla addosso e muori solo e tra dolori atroci!!!`n");
                output("`\$Hai perso il 5% della tua esperienza !!!`n");
                addnews("`%".$session['user']['name']."`5 è morto schiacciato da un albero, mentre lavorava nella foresta!!");
                debuglog("è morto schiacciato da un albero in foresta e ha perso ".$session['user']['gold']." oro");
                $session['user']['experience'] *= 0.95;
                $session['user']['alive'] = false;
                $session['user']['hitpoints'] = 0;
                if($session['user']['assicurazione']==1) $session['user']['goldinbank']+=$session['user']['gold'];
                $session['user']['gold'] = 0;
                addnav("Notizie Giornaliere","news.php");
            }elseif($caso>=80 AND $caso< 85){
                output("`#AAAAARGGHHH!! Hai disturbato il sonno di un Cinghiale!`3`n");
                $dkb = round($session['user']['dragonkills']*.1);
                $badguy = array("creaturename"=>"`5Cinghiale`0"
                ,"creaturelevel"=>0
                ,"creatureweapon"=>"`%Carica Pericolosa`0"
                ,"creatureattack"=>1
                ,"creaturedefense"=>2
                ,"creaturehealth"=>2
                ,"diddamage"=>0);
                $userlevel=$session['user']['level'];
                $userattack=$session['user']['attack'];
                $userhealth=$session['user']['maxhitpoints'];
                $userdefense=$session['user']['defense'];
                $badguy['creaturelevel']+=$userlevel;
                $badguy['creatureattack']+=$userattack;
                $badguy['creaturehealth']=$userhealth;
                $badguy['creaturedefense']+=$userdefense;
                $session['user']['badguy']=createstring($badguy);
                addnav("Combatti","foresta.php?op=fight");
                addnav("Scappa","foresta.php?op=run");
            }else{
                if ($session['user']['turni_mestiere']>0) {
                    addnav("Lavora", "foresta.php?op=lavora1");
                }
                addnav("S?Vai ai Sentieri", "foresta.php?op=zona_lavoro");
            }
        }
    }
}
if ($_GET['op']=="lavora2") {
    $badguy=array();
    $hungerchance=rand(0,4);
    usuraascia();
    if ($session['user']['oggetto'] != 0) {
        if ($session['user']['superuser'] >= 3) output("Debug: Chance di avere fame $hungerchance`n");
        if ($hungerchance == 0) $session['user']['hunger'] += 1;
        $bladchance=rand(0,5);
        if ($session['user']['superuser'] >= 3) output("Debug: Chance di andare in bagno $bladchance`n");
        if ($bladchance == 0) $session['user']['bladder'] += 1;
        $cleanchance=rand(0,2);
        if ($session['user']['superuser'] >= 3) output("Debug: Chance di sporcarsi $cleanchance`n");
        if ($cleanchance == 0) $session['user']['clean'] += 2;
        $session['user']['gold']+=intval($stipendio*1.05);
        $session['user']['turni_mestiere']-=1;
        //if($session['user']['boscaiolo']< 5)$session['user']['boscaiolo']+=0.01;
        if($session['user']['boscaiolo']< 10)$session['user']['boscaiolo']+=0.01;
        output("`3Lavora!`3`n");
        output("`3Impugni la tua accetta e colpisci un albero cercando di abbatterlo!`3`n");
        $forza=intval(($session['user']['attack']+$session['user']['defence'])/100);
        if($forza==0)$forza=1;
        //result è una percentuale il massimo fissato indica la massima possibilità di riuscita
        $result=$session['user']['boscaiolo']+$forza;
        if($result>80)$result=80;
        $cento=rand(1, 100);
        addnav("Azioni");
        if($result >= $cento){
            savesetting("betulla",getsetting("betulla",0)+1);
            $sqldr="INSERT INTO foresta (acctid,data,materiale) VALUES ('$account',FROM_UNIXTIME(UNIX_TIMESTAMP()),'Betulla')";
            db_query($sqldr) or die(db_error(LINK));
            $fatica=rand(0,1);
            $session['user']['hitpoints']-=$fatica;
            if ($session['user']['hitpoints'] <=0) $session['user']['hitpoints']=1;
            output("`#Un buon colpo!`3`n");
            $session['user']['boscaiolo']+=0.01;
            $caso=rand(1, 200);
            if($caso==200){
                output("`#Dalla corteccia dell'albero si apre una fessura da dove affiorano delle gemme ... le conti e scopri ");
                output("che sono `^`b8`#`b!!!!`nQuesto si che è un colpo di fortuna!! Un po' meno per chi le aveva nascoste lì ma ormai sono tue!`3`n");
                debuglog("trova 8 gemme nella foresta dei boscaioli");
                $session['user']['gems']+=8;
                if ($session['user']['turni_mestiere']>0) {
                    addnav("Lavora", "foresta.php?op=lavora2");
                }
                addnav("S?Vai ai Sentieri", "foresta.php?op=zona_lavoro");
            }elseif($caso>=195 AND $caso< 200){
                output("`#Dalla corteccia dell'albero si apre una fessura da dove affiora una gemma, che prontamente infili in tasca!`3`n");
                $session['user']['gems']+=1;
                debuglog("trova una gemma nella foresta dei boscaioli");
                if ($session['user']['turni_mestiere']>0) {
                    addnav("Lavora", "foresta.php?op=lavora2");
                }
                addnav("S?Vai ai Sentieri", "foresta.php?op=zona_lavoro");
            }elseif($caso>=190 AND $caso< 195){
                $gold = $session['user']['level'] * 37;
                output("`#Dalla corteccia dell'albero si apre una fessura da dove affiora un borsellino contenente {$gold} pezzi d'oro!`3`n");
                $session['user']['gold']+=$gold;
                debuglog("trova $gold pezzi d'oro nella foresta dei boscaioli");
                if ($session['user']['turni_mestiere']>0) {
                    addnav("Lavora", "foresta.php?op=lavora2");
                }
                addnav("S?Vai ai Sentieri", "foresta.php?op=zona_lavoro");
            }elseif($caso>=188 AND $caso<190){
                output("`#Dal colpo che hai dato, si apre una spaccatura nella corteccia, ed una pergamena viene alla luce.`n");
                output("La esamini e scopri che è una ricetta, felice per il ritrovamento la metti nello zaino!`3`n");
                if (zainoPieno($session['user']['acctid'])){
                    output ("Purtroppo ti accorgi che lo zaino è pieno e a malincuore getti la getti via, un vero peccato...");
                }else{
                    $sqldr="INSERT INTO zaino (idoggetto,idplayer) VALUES ('25','$account')";
                    db_query($sqldr) or die(db_error(LINK));
                    debuglog("trova ricetta 25 nella foresta dei boscaioli");
                }
                if ($session['user']['turni_mestiere']>0) {
                    addnav("Lavora", "foresta.php?op=lavora2");
                }
                addnav("S?Vai ai Sentieri", "foresta.php?op=zona_lavoro");
            }elseif($caso>=184 AND $caso< 188){
                output("`#AAAAARGGHHH!! Quell'albero era la tana di alcuni Toporagni Voraci!`3`n");
                $badguy = array("creaturename"=>"`9Toporagni Voraci`0"
                ,"creaturelevel"=>1
                ,"creatureweapon"=>"`(Denti Rosso-Bruno`0"
                ,"creatureattack"=>4
                ,"creaturedefense"=>4
                ,"diddamage"=>0);
                $userlevel=$session['user']['level'];
                $userattack=$session['user']['attack'];
                $userhealth=$session['user']['maxhitpoints'];
                $userdefense=$session['user']['defense'];
                $badguy['creaturelevel']+=$userlevel;
                $badguy['creatureattack']+=$userattack;
                $badguy['creaturehealth']=intval($userhealth*1.7);
                $badguy['creaturedefense']+=$userdefense;
                $session['user']['badguy']=createstring($badguy);
                addnav("Combatti","foresta.php?op=fight");
                addnav("Scappa","foresta.php?op=run");
            }elseif($caso>=180 AND $caso< 184){
                output("`4Purtroppo non hai sentito le grida di avvertimento di un altro boscaiolo.");
                output("`nUn albero ti crolla addosso e muori solo e tra dolori atroci!!!`n");
                output("`\$Hai perso il 5% della tua esperienza !!!`n");
                addnews("`%".$session['user']['name']."`5 è morto schiacciato da un albero, mentre lavorava nella foresta!!");
                debuglog("è morto schiacciato da un albero in foresta e ha perso ".$session['user']['gold']." oro");
                $session['user']['experience'] *= 0.95;
                $session['user']['alive'] = false;
                $session['user']['hitpoints'] = 0;
                if($session['user']['assicurazione']==1) $session['user']['goldinbank']+=$session['user']['gold'];
                $session['user']['gold'] = 0;
                addnav("Notizie Giornaliere","news.php");
            }elseif($caso>=175 AND $caso< 180){
                output("`#AAAAARGGHHH!! Quell'albero era la tana di una Faina!`3`n");
                $dkb = round($session['user']['dragonkills']*.15);
                $badguy = array("creaturename"=>"`\$Faina`0"
                ,"creaturelevel"=>0
                ,"creatureweapon"=>"`\$Morso Letale`0"
                ,"creatureattack"=>2
                ,"creaturedefense"=>4
                ,"diddamage"=>0);
                $userlevel=$session['user']['level'];
                $userattack=$session['user']['attack'];
                $userhealth=$session['user']['maxhitpoints'];
                $userdefense=$session['user']['defense'];
                $badguy['creaturelevel']+=$userlevel;
                $badguy['creatureattack']+=$userattack;
                $badguy['creaturehealth']=intval($userhealth*1.5);
                $badguy['creaturedefense']+=$userdefense;
                $session['user']['badguy']=createstring($badguy);
                addnav("Combatti","foresta.php?op=fight");
                addnav("Scappa","foresta.php?op=run");
            }elseif($caso>=169 AND $caso< 175){
                output("`3Mentre stai lavorando senti un grido di avvertimento di un altro boscaiolo, un albero ti stà cadendo addosso!");
                output("`nFai appena in tempo a gettarti da una parte, la paura ti paralizza e purtroppo perdi 5 turni ti lavoro ma almeno sei salvo.");
                $session['user']['turni_mestiere'] -= 5;
                if ($session['user']['turni_mestiere'] < 0) $session['user']['turni_mestiere'] = 0;
                if ($session['user']['turni_mestiere']>0) {
                    addnav("Lavora", "foresta.php?op=lavora2");
                }
                addnav("S?Vai ai Sentieri", "foresta.php?op=zona_lavoro");
            }elseif($caso>=160 AND $caso<169){
                output("`n`3Noti solo ora nel sentiero una lucina flebile in lontananza e decidi di investigare.`n");
                output("Mentre ti avvicini alla luce vedi una strana costruzione e scopri che si tratta di un bagno ");
                output("pubblico. Chi mai lo avrà costruito? Probabilmente qualche boscaiolo solitario che non voleva ");
                output("`innaffiare`i il bosco o che non voleva rinunciare alla comodità di un vero bagno.`n");
                output("Chiunque sia stato, decidi di approfittare dell'opportunità e ti liberi di un peso superfluo.`n`n");
                $session['user']['bladder']=0;
                if ($session['user']['turni_mestiere']>0) {
                    addnav("Lavora", "foresta.php?op=lavora2");
                }
                addnav("S?Vai ai Sentieri", "foresta.php?op=zona_lavoro");
            }elseif($caso>=159 AND $caso<160){
                output("`#Pensi di aver trovato un buon albero, e dai fondo alle tue energia dando un colpo molto potente ");
                output("con l'accetta. Purtroppo il colpo è stato veramente `bTROPPO`b potente!!`n`n");
                output("L'accetta si spezza e ti ritrovi con qualche scheggia di legno ed un ");
                output("ammasso informe di metallo.`n");
                $sqlogg = "SELECT nome FROM oggetti WHERE id_oggetti = ".$session['user']['oggetto'];
                $resultogg = db_query($sqlogg) or die(db_error(LINK));
                $rowogg = db_fetch_assoc($resultogg);
                if ($rowogg['nome'] == "Ascia da boscaiolo"){
                    $sqlogg = "DELETE FROM oggetti WHERE id_oggetti = ".$session['user']['oggetto'];
                    $result = db_query($sqlogg) or die(db_error(LINK));
                    $session['user']['oggetto'] = 0;
                }else{
                    $sqlogg = "DELETE FROM oggetti WHERE id_oggetti = ".$session['user']['zaino'];
                    $result = db_query($sqlogg) or die(db_error(LINK));
                    $session['user']['zaino'] = 0;
                }
                addnav("Torna all'Inizio", "foresta.php");
            }else{
                if ($session['user']['turni_mestiere']>0) {
                    addnav("Lavora", "foresta.php?op=lavora2");
                }
                addnav("S?Vai ai Sentieri", "foresta.php?op=zona_lavoro");
            }
        }else{
            output("`vImpegnati di più mettici un po' di energia in quei colpi!`3`n");
            $fatica=rand(0, (intval($session['user']['level']/5)+1));
            $session['user']['hitpoints']-=$fatica;
            if ($session['user']['hitpoints'] <= 0) $session['user']['hitpoints']=1;
            $caso=rand(0, 100);
            if($caso>=86 AND $caso< 89){
                output("`#AAAAARGGHHH!! Hai disturbato uno Sparviero Gigante!`3`n");
                $badguy = array("creaturename"=>"`(Sparviero Gigante`0"
                ,"creaturelevel"=>0
                ,"creatureweapon"=>"`8Grida acute e ripetute`0"
                ,"creatureattack"=>1
                ,"creaturedefense"=>2
                ,"creaturehealth"=>2
                ,"diddamage"=>0);
                $userlevel=$session['user']['level'];
                $userattack=$session['user']['attack'];
                $userhealth=$session['user']['maxhitpoints'];
                $userdefense=$session['user']['defense'];
                $badguy['creaturelevel']+=$userlevel;
                $badguy['creatureattack']+=$userattack;
                $badguy['creaturehealth']=intval($userhealth*1.2);
                $badguy['creaturedefense']+=$userdefense;
                $session['user']['badguy']=createstring($badguy);
                $session['bufflist']['foresta'] = array(
                "startmsg"=>"`n`@Le grida disturbano ti fanno sanguinare le orecchie!`n`n",
                "name"=>"`2Grida Acute`0",
                "rounds"=>10,
                "wearoff"=>"le grida sono finite.",
                "minioncount"=>$session['user']['level'],
                "mingoodguydamage"=>0,
                "maxgoodguydamage"=>1+$dkb,
                "effectmsg"=>"Le grida ti causano {damage} punti danno.",
                "effectnodmgmsg"=>"Riesci a tapparti le orecchie.",
                "activate"=>"roundstart",
                );
                addnav("Combatti","foresta.php?op=fight");
                addnav("Scappa","foresta.php?op=run");
            }elseif($caso>=85 AND $caso< 86){
                output("`4Purtroppo non hai sentito le grida di avvertimento di un altro boscaiolo.");
                output("`nUn albero ti crolla addosso e muori solo e tra dolori atroci!!!`n");
                output("`\$Hai perso il 5% della tua esperienza !!!`n");
                addnews("`%".$session['user']['name']."`5 è morto schiacciato da un albero, mentre lavorava nella foresta!!");
                debuglog("è morto schiacciato da un albero in foresta e ha perso ".$session['user']['gold']." oro");
                $session['user']['experience'] *= 0.95;
                $session['user']['alive'] = false;
                $session['user']['hitpoints'] = 0;
                if($session['user']['assicurazione']==1) $session['user']['goldinbank']+=$session['user']['gold'];
                $session['user']['gold'] = 0;
                addnav("Notizie Giornaliere","news.php");
            }elseif($caso>=80 AND $caso< 85){
                output("`#AAAAARGGHHH!! Hai disturbato un Biacco Aggressivo!`3`n");
                $dkb = round($session['user']['dragonkills']*.1);
                $badguy = array("creaturename"=>"`@Biacco Aggressivo`0"
                ,"creaturelevel"=>0
                ,"creatureweapon"=>"`2Morso poco Velenoso`0"
                ,"creatureattack"=>2
                ,"creaturedefense"=>3
                ,"creaturehealth"=>2
                ,"diddamage"=>0);
                $userlevel=$session['user']['level'];
                $userattack=$session['user']['attack'];
                $userhealth=$session['user']['maxhitpoints'];
                $userdefense=$session['user']['defense'];
                $badguy['creaturelevel']+=$userlevel;
                $badguy['creatureattack']+=$userattack;
                $badguy['creaturehealth']=$userhealth;
                $badguy['creaturedefense']+=$userdefense;
                $session['user']['badguy']=createstring($badguy);
                $session['bufflist']['foresta'] = array(
                "startmsg"=>"`n`@Il Biacco ti frusta con la sua coda!`n`n",
                "name"=>"`2Frustate con la Coda`0",
                "rounds"=>10,
                "wearoff"=>"Il Biacco si stanca.",
                "minioncount"=>$session['user']['level'],
                "mingoodguydamage"=>0,
                "maxgoodguydamage"=>1+$dkb,
                "effectmsg"=>"La coda ti colpisce causandoti {damage} punti danno.",
                "effectnodmgmsg"=>"La coda ti MANCA.",
                "activate"=>"roundstart",
                );
                addnav("Combatti","foresta.php?op=fight");
                addnav("Scappa","foresta.php?op=run");
            }elseif($caso==0){
                output("`3Mentre stai lavorando senti un grido di avvertimento di un altro boscaiolo, un albero ti stà cadendo addosso!");
                output("`nFai appena in tempo a gettarti da una parte, purtroppo la tua accetta è stata schiacciata ed ora è inutilizzabile...");
                $sqlogg = "SELECT * FROM oggetti WHERE id_oggetti = ".$session['user']['oggetto'];
                $resultogg = db_query($sqlogg) or die(db_error(LINK));
                $rowogg = db_fetch_assoc($resultogg);
                if ($rowogg['nome'] == "Ascia da boscaiolo"){
                    //$oldloc = $rowogg['dove_origine'];
                    $oid = $rowogg['id_oggetti'];
                    $sql = "UPDATE oggetti SET dove=1 WHERE id_oggetti=$oid";
                    db_query($sql) or die(db_error(LINK));
                    $session['user']['oggetto'] = 0;
                }else{
                    $sqlogg = "SELECT * FROM oggetti WHERE id_oggetti = ".$session['user']['zaino'];
                    $resultogg = db_query($sqlogg) or die(db_error(LINK));
                    $rowogg = db_fetch_assoc($resultogg);
                    //$oldloc = $rowogg['dove_origine'];
                    $oid = $rowo['id_oggetti'];
                    $sql = "UPDATE oggetti SET dove=1 WHERE id_oggetti=$oid";
                    db_query($sql) or die(db_error(LINK));
                    $session['user']['zaino'] = 0;
                }
                addnav("Torna all'Inizio", "foresta.php");
            }else{
                if ($session['user']['turni_mestiere']>0) {
                    addnav("Lavora", "foresta.php?op=lavora2");
                }
                addnav("S?Vai ai Sentieri", "foresta.php?op=zona_lavoro");
            }
        }
    }
}
if ($_GET['op']=="lavora3") {
    $badguy=array();
    $hungerchance=rand(0,4);
    usuraascia();
    if ($session['user']['oggetto'] != 0) {
        if ($session['user']['superuser'] >= 3) output("Debug: Chance di avere fame $hungerchance`n");
        if ($hungerchance == 0) $session['user']['hunger'] += 1;
        $bladchance=rand(0,5);
        if ($session['user']['superuser'] >= 3) output("Debug: Chance di andare in bagno $bladchance`n");
        if ($bladchance == 0) $session['user']['bladder'] += 1;
        $cleanchance=rand(0,2);
        if ($session['user']['superuser'] >= 3) output("Debug: Chance di sporcarsi $cleanchance`n");
        if ($cleanchance == 0) $session['user']['clean'] += 2;
        $session['user']['gold']+=intval($stipendio*1.05);
        $session['user']['turni_mestiere']-=1;
        //if($session['user']['boscaiolo']< 5)$session['user']['boscaiolo']+=0.01;
        //if($session['user']['boscaiolo']< 10)$session['user']['boscaiolo']+=0.01;
        output("`3Lavora!`3`n");
        output("`3Impugni la tua accetta e colpisci un albero cercando di abbatterlo!`3`n");
        $forza=intval(($session['user']['attack']+$session['user']['defence'])/100);
        if($forza==0)$forza=1;
        //result è una percentuale il massimo fissato indica la massima possibilità di riuscita
        $result=$session['user']['boscaiolo'];
        if($result>50)$result=50;
        $cento=rand(1, 100);
        addnav("Azioni");
        if($result >= $cento){
            savesetting("faggio",getsetting("faggio",0)+1);
            $sqldr="INSERT INTO foresta (acctid,data,materiale) VALUES ('$account',FROM_UNIXTIME(UNIX_TIMESTAMP()),'Faggio')";
            db_query($sqldr) or die(db_error(LINK));
            $fatica=rand(0,1);
            $session['user']['hitpoints']-=$fatica;
            if ($session['user']['hitpoints'] <=0) $session['user']['hitpoints']=1;
            output("`#Un buon colpo!`3`n");
            $session['user']['boscaiolo']+=0.01;
            $caso=rand(1, 200);
            if($caso==200){
                output("`#Dalla corteccia dell'albero si apre una fessura da dove affiorano delle gemme ... le conti e scopri ");
                output("che sono `^`b10`#`b!!!!`nQuesto si che è un colpo di fortuna!! Un po' meno per chi le aveva nascoste lì ma ormai sono tue!`3`n");
                debuglog("trova 10 gemme nella foresta dei boscaioli");
                $session['user']['gems']+=10;
                if ($session['user']['turni_mestiere']>0) {
                    addnav("Lavora", "foresta.php?op=lavora3");
                }
                addnav("S?Vai ai Sentieri", "foresta.php?op=zona_lavoro");
            }elseif($caso>=197 AND $caso< 200){
                output("`#Dalla corteccia dell'albero si apre una fessura da dove affiora una gemma, che prontamente infili in tasca!`3`n");
                debuglog("trova una gemma nella foresta dei boscaioli");
                $session['user']['gems']+=1;
                if ($session['user']['turni_mestiere']>0) {
                    addnav("Lavora", "foresta.php?op=lavora3");
                }
                addnav("S?Vai ai Sentieri", "foresta.php?op=zona_lavoro");
            }elseif($caso>=190 AND $caso< 197){
                $gold = $session['user']['level'] * 40;
                output("`#Dalla corteccia dell'albero si apre una fessura da dove affiora un borsellino contenente {$gold} pezzi d'oro!`3`n");
                debuglog("trova $gold pezzi d'oro nella foresta dei boscaioli");
                $session['user']['gold']+=$gold;
                if ($session['user']['turni_mestiere']>0) {
                    addnav("Lavora", "foresta.php?op=lavora3");
                }
                addnav("S?Vai ai Sentieri", "foresta.php?op=zona_lavoro");
            }elseif($caso>=189 AND $caso<190){
                output("`#Dal colpo che hai dato, si apre una spaccatura nella corteccia, ed una pergamena viene alla luce.`n");
                output("La esamini e scopri che è una ricetta, felice per il ritrovamento la metti nello zaino!`3`n");
                if (zainoPieno($session['user']['acctid'])){
                    output ("Purtroppo ti accorgi che lo zaino è pieno e a malincuore getti la getti via, un vero peccato...");
                }else{
                    $sqldr="INSERT INTO zaino (idoggetto,idplayer) VALUES ('25','$account')";
                    db_query($sqldr) or die(db_error(LINK));
                    debuglog("trova ricetta 25 nella foresta dei boscaioli");
                }
                if ($session['user']['turni_mestiere']>0) {
                    addnav("Lavora", "foresta.php?op=lavora3");
                }
                addnav("S?Vai ai Sentieri", "foresta.php?op=zona_lavoro");
            }elseif($caso>=184 AND $caso< 188){
                output("`#AAAAARGGHHH!! Hai disturbato lo spirito di un Boscaiolo morto. Il suo fantasma di attacca!`3`n");
                $badguy = array("creaturename"=>"`&Boscaiolo Fantasma`0"
                ,"creaturelevel"=>3
                ,"creatureweapon"=>"`7Ululati da Brivido`0"
                ,"creatureattack"=>5
                ,"creaturedefense"=>5
                ,"diddamage"=>0);
                $userlevel=$session['user']['level'];
                $userattack=$session['user']['attack'];
                $userhealth=$session['user']['maxhitpoints'];
                $userdefense=$session['user']['defense'];
                $badguy['creaturelevel']+=$userlevel;
                $badguy['creatureattack']+=$userattack;
                $badguy['creaturehealth']=intval($userhealth*1.8);
                $badguy['creaturedefense']+=$userdefense;
                $session['user']['badguy']=createstring($badguy);
                addnav("Combatti","foresta.php?op=fight");
                addnav("Scappa","foresta.php?op=run");
            }elseif($caso>=180 AND $caso< 184){
                output("`4Purtroppo non hai sentito le grida di avvertimento di un altro boscaiolo.");
                output("`nUn albero ti crolla addosso e muori solo e tra dolori atroci!!!`n");
                output("`\$Hai perso il 5% della tua esperienza !!!`n");
                addnews("`%".$session['user']['name']."`5 è morto schiacciato da un albero, mentre lavorava nella foresta!!");
                debuglog("è morto schiacciato da un albero in foresta e ha perso ".$session['user']['gold']." oro");
                $session['user']['experience'] *= 0.95;
                $session['user']['alive'] = false;
                $session['user']['hitpoints'] = 0;
                if($session['user']['assicurazione']==1) $session['user']['goldinbank']+=$session['user']['gold'];
                $session['user']['gold'] = 0;
                addnav("Notizie Giornaliere","news.php");
            }elseif($caso>=175 AND $caso< 180){
                output("`#AAAAARGGHHH!! Hai incontrato un Troll del Boschi a caccia!`3`n");
                $dkb = round($session['user']['dragonkills']*.15);
                $badguy = array("creaturename"=>"`\$Troll del Boschi`0"
                ,"creaturelevel"=>2
                ,"creatureweapon"=>"`\$Pesante Clava`0"
                ,"creatureattack"=>3
                ,"creaturedefense"=>5
                ,"diddamage"=>0);
                $userlevel=$session['user']['level'];
                $userattack=$session['user']['attack'];
                $userhealth=$session['user']['maxhitpoints'];
                $userdefense=$session['user']['defense'];
                $badguy['creaturelevel']+=$userlevel;
                $badguy['creatureattack']+=$userattack;
                $badguy['creaturehealth']=intval($userhealth*1.6);
                $badguy['creaturedefense']+=$userdefense;
                $session['user']['badguy']=createstring($badguy);
                addnav("Combatti","foresta.php?op=fight");
                addnav("Scappa","foresta.php?op=run");
            }elseif($caso>=169 AND $caso< 175){
                output("`3Mentre stai lavorando senti un grido di avvertimento di un altro boscaiolo, un albero ti stà cadendo addosso!");
                output("`nFai appena in tempo a gettarti da una parte, la paura ti paralizza e purtroppo perdi 5 turni ti lavoro ma almeno sei salvo.");
                $session['user']['turni_mestiere'] -= 5;
                if ($session['user']['turni_mestiere'] < 0) $session['user']['turni_mestiere'] = 0;
                if ($session['user']['turni_mestiere']>0) {
                    addnav("Lavora", "foresta.php?op=lavora3");
                }
                addnav("S?Vai ai Sentieri", "foresta.php?op=zona_lavoro");
            }elseif($caso>=160 AND $caso<169){
                output("`n`3Noti solo ora nel sentiero una lucina flebile in lontananza e decidi di investigare.`n");
                output("Mentre ti avvicini alla luce vedi una strana costruzione e scopri che si tratta di un bagno ");
                output("pubblico. Chi mai lo avrà costruito? Probabilmente qualche boscaiolo solitario che non voleva ");
                output("`innaffiare`i il bosco o che non voleva rinunciare alla comodità di un vero bagno.`n");
                output("Chiunque sia stato, decidi di approfittare dell'opportunità e ti liberi di un peso superfluo.`n`n");
                $session['user']['bladder']=0;
                if ($session['user']['turni_mestiere']>0) {
                    addnav("Lavora", "foresta.php?op=lavora3");
                }
                addnav("S?Vai ai Sentieri", "foresta.php?op=zona_lavoro");
            }elseif($caso>=159 AND $caso<160){
                output("`#Pensi di aver trovato un buon albero, e dai fondo alle tue energia dando un colpo molto potente ");
                output("con l'accetta. Purtroppo il colpo è stato veramente `bTROPPO`b potente!!`n`n");
                output("L'accetta si spezza e ti ritrovi con qualche scheggia di legno ed un ");
                output("ammasso informe di metallo.`n");
                $sqlogg = "SELECT nome FROM oggetti WHERE id_oggetti = ".$session['user']['oggetto'];
                $resultogg = db_query($sqlogg) or die(db_error(LINK));
                $rowogg = db_fetch_assoc($resultogg);
                if ($rowogg['nome'] == "Ascia da boscaiolo"){
                    $sqlogg = "DELETE FROM oggetti WHERE id_oggetti = ".$session['user']['oggetto'];
                    $result = db_query($sqlogg) or die(db_error(LINK));
                    $session['user']['oggetto'] = 0;
                }else{
                    $sqlogg = "DELETE FROM oggetti WHERE id_oggetti = ".$session['user']['zaino'];
                    $result = db_query($sqlogg) or die(db_error(LINK));
                    $session['user']['zaino'] = 0;
                }
                addnav("Torna all'Inizio", "foresta.php");
            }elseif($caso>=155 AND $caso<159){
                output("`n`3Noti solo ora nel sentiero una lucina flebile in lontananza e decidi di investigare.`n");
                output("Mentre ti avvicini alla luce vedi una strana costruzione e scopri che si tratta di un bagno ");
                output("pubblico. Chi mai lo avrà costruito? Probabilmente qualche boscaiolo solitario che non voleva ");
                output("`innaffiare`i il bosco o che non voleva rinunciare alla comodità di un vero bagno.`n");
                output("Chiunque sia stato, decidi di approfittare dell'opportunità e ti liberi di un peso superfluo.`n`n");
                output("Durante la minzione noti qualcosa che brilla sul pavimento. Con estrema cautela ti chini e scopri ");
                output("che è una `&gemma`3, persa da qualche altro boscaiolo!!`n`n");
                $session['user']['gems']++;
                debuglog("trova una gemma al gabinetto della foresta dei boscaioli");
                $session['user']['bladder']=0;
                if ($session['user']['turni_mestiere']>0) {
                    addnav("Lavora", "foresta.php?op=lavora3");
                }
                addnav("S?Vai ai Sentieri", "foresta.php?op=zona_lavoro");
            }elseif($caso>=151 AND $caso<155){
                output("`n`3Noti solo ora nel sentiero una lucina flebile in lontananza e decidi di investigare.`n");
                output("Mentre ti avvicini alla luce vedi una strana costruzione e scopri che si tratta di un bagno ");
                output("pubblico. Chi mai lo avrà costruito? Probabilmente qualche boscaiolo solitario che non voleva ");
                output("`innaffiare`i il bosco o che non voleva rinunciare alla comodità di un vero bagno.`n");
                output("Chiunque sia stato, decidi di approfittare dell'opportunità e ti liberi di un peso superfluo.`n`n");
                output("Durante la minzione purtroppo ti cade una `&gemma`3 dalla tasca, ed il pavimento è troppo sporco per ");
                output("rischiare di riprenderla ... chissà quale terribile malattia contrarresti!!`n`n");
                $session['user']['gems']--;
                debuglog("perde una gemma al gabinetto della foresta dei boscaioli");
                $session['user']['bladder']=0;
                if ($session['user']['turni_mestiere']>0) {
                    addnav("Lavora", "foresta.php?op=lavora3");
                }
                addnav("S?Vai ai Sentieri", "foresta.php?op=zona_lavoro");
            }elseif($caso>=147 AND $caso<151){
                output("`n`3Noti solo ora nel sentiero una lucina flebile in lontananza e decidi di investigare.`n");
                output("Mentre ti avvicini alla luce vedi una strana costruzione e scopri che si tratta di una doccia ");
                output("pubblica. Chi mai l'avrà costruita? Probabilmente qualche boscaiolo solitario che non voleva ");
                output("rischiare di sporcarsi troppo e che non voleva tornare al villaggio per togliersi lo sporco di dosso.`n");
                output("Chiunque sia stato, decidi di approfittare dell'opportunità e ti dai una bella lavata.`n");
                output("Al termine ti senti quasi pulito, purtroppo la mancanza di sapone non ti ha permesso di lavarti ");
                output("come avresti voluto ... ma come si dice, a caval donato non si guarda in bocca.`n`n");
                $clean= intval(rand(.3,.7)*$session['user']['clean']);
                $session['user']['clean']-=$clean;
                if ($session['user']['turni_mestiere']>0) {
                    addnav("Lavora", "foresta.php?op=lavora3");
                }
                addnav("S?Vai ai Sentieri", "foresta.php?op=zona_lavoro");
            }elseif($caso>=144 AND $caso<147){
                if ($session['user']['potion']<5){
                    output("`2Dalla corteccia dell'albero si apre una fessura, qualcosa che assomiglia ad una fiala di colore verde affiora. ");
                    output("`@Hai trovato una Pozione Guaritrice!`2`n`n");
                    $session['user']['potion']+=1;
                }else{
                    output("`2Dalla corteccia dell'albero si apre una fessura, qualcosa che assomiglia ad una fiala di colore verde affiora. ");
                    output("`@Hai trovato una Pozione Guaritrice!`2`n`n");
                    output("Peccato tu non abbia lo spazio per portarla con te.`n");
                    output("Seppur a malincuore la riponi nella fenditura... ");
                    output("forse qualche altro boscaiolo la troverà e potrà usarla.`n`n");
                }
                if ($session['user']['turni_mestiere']>0) {
                    addnav("Lavora", "foresta.php?op=lavora3");
                }
                addnav("S?Vai ai Sentieri", "foresta.php?op=zona_lavoro");
            }elseif($caso>=142 AND $caso<144){
                output("`2All'improvviso senti una flebile melodia provenire dal profondo del bosco. La musica è ");
                output("dolcissima e ne sei attratto come l'ago della bussola è attratto dal polo magnetico della terra. ");
                output("Non riesci a resistere e inizi a camminare facendoti guidare dalla musica.`n");
                output("Dopo un tempo che ti sembra un'eternità, giungi in una caverna dalla strana conformazione. Una ");
                output("forte corrente d'aria, proveniente chissà da dove, incanalandosi nelle strane concrezioni produce ");
                output("la musica che sentivi!!`nIl tempo che hai dedicato alla ricerca della sorgente musicale ti ha fatto ");
                $turnipersi = intval($session['user']['turni_mestiere']/2);
                $session['user']['turni_mestiere']-=$turnipersi;
                output("perdere `^$turnipersi turni`2 di lavoro!!`nScornato torni sui tuoi passi...`n`n");
                if ($session['user']['turni_mestiere']>0) {
                    addnav("Lavora", "foresta.php?op=lavora3");
                }
                addnav("S?Vai ai Sentieri", "foresta.php?op=zona_lavoro");
            }elseif($caso>=140 AND $caso<142){
                output("`2All'improvviso senti una flebile melodia provenire dal profondo del bosco. La musica è ");
                output("dolcissima e ne sei attratto come l'ago della bussola è attratto dal polo magnetico della terra. ");
                output("Non riesci a resistere e inizi a camminare facendoti guidare dalla musica.`n");
                output("Dopo un tempo che ti sembra un'eternità, giungi in una caverna dalla strana conformazione. Una ");
                output("forte corrente d'aria, proveniente chissà da dove, incanalandosi nelle strane concrezioni produce ");
                output("la musica che sentivi!!`nNella caverna trovi anche un pacchetto confezionato di `(Coscia di maiale ");
                output("arrosto`2, confezionato qualche giorno fa. Deve essere stato dimenticato da qualche altro boscaiolo ");
                output("attirato anche lui dalla musica misteriosa.`nLo apri ed inizi a divorarlo ... eri affamato!!`n");
                output("La mangiata ha quasi saziato la tua fame, e ti ha dato l'energia per altri `^10 turni`2 di lavoro!!`n`n");
                $session['user']['turni_mestiere']+=10;
                $fame= intval(rand(.5,.8)*$session['user']['hunger']);
                $session['user']['hunger']-=$fame;
                if ($session['user']['turni_mestiere']>0) {
                    addnav("Lavora", "foresta.php?op=lavora3");
                }
                addnav("S?Vai ai Sentieri", "foresta.php?op=zona_lavoro");
            }else{
                if ($session['user']['turni_mestiere']>0) {
                    addnav("Lavora", "foresta.php?op=lavora3");
                }
                addnav("S?Vai ai Sentieri", "foresta.php?op=zona_lavoro");
            }
        }else{
            output("`vImpegnati di più mettici un po' di energia in quei colpi!`3`n");
            $fatica=rand(0, (intval($session['user']['level']/5)+1));
            $session['user']['hitpoints']-=$fatica;
            if ($session['user']['hitpoints'] <= 0) $session['user']['hitpoints']=1;
            $caso=rand(0, 100);
            if($caso>=86 AND $caso< 89){
                output("`#AAAAARGGHHH!! Hai disturbato il sonno di uno scheletro!`3`n");
                $badguy = array("creaturename"=>"`&Scheletro Ossuto`0"
                ,"creaturelevel"=>1
                ,"creatureweapon"=>"`&Colpo d'Osso`0"
                ,"creatureattack"=>2
                ,"creaturedefense"=>4
                ,"creaturehealth"=>2
                ,"diddamage"=>0);
                $userlevel=$session['user']['level'];
                $userattack=$session['user']['attack'];
                $userhealth=$session['user']['maxhitpoints'];
                $userdefense=$session['user']['defense'];
                $badguy['creaturelevel']+=$userlevel;
                $badguy['creatureattack']+=$userattack;
                $badguy['creaturehealth']=intval($userhealth*1.4);
                $badguy['creaturedefense']+=$userdefense;
                $session['user']['badguy']=createstring($badguy);
                addnav("Combatti","foresta.php?op=fight");
                addnav("Scappa","foresta.php?op=run");
            }elseif($caso>=85 AND $caso< 86){
                output("`4Purtroppo non hai sentito le grida di avvertimento di un altro boscaiolo.");
                output("`nUn albero ti crolla addosso e muori solo e tra dolori atroci!!!`n");
                output("`\$Hai perso il 5% della tua esperienza !!!`n");
                addnews("`%".$session['user']['name']."`5 è morto schiacciato da un albero, mentre lavorava nella foresta!!");
                debuglog("è morto schiacciato da un albero in foresta e ha perso ".$session['user']['gold']." oro");
                $session['user']['experience'] *= 0.95;
                $session['user']['alive'] = false;
                $session['user']['hitpoints'] = 0;
                if($session['user']['assicurazione']==1) $session['user']['goldinbank']+=$session['user']['gold'];
                $session['user']['gold'] = 0;
                addnav("Notizie Giornaliere","news.php");
            }elseif($caso>=80 AND $caso< 85){
                output("`#AAAAARGGHHH!! La zona è infettata da funghi allucinogeni!`3`n");
                $dkb = round($session['user']['dragonkills']*.1);
                $badguy = array("creaturename"=>"`@Nemici Immaginari`0"
                ,"creaturelevel"=>1
                ,"creatureweapon"=>"`2Colpi Fantasma`0"
                ,"creatureattack"=>3
                ,"creaturedefense"=>4
                ,"creaturehealth"=>2
                ,"diddamage"=>0);
                $userlevel=$session['user']['level'];
                $userattack=$session['user']['attack'];
                $userhealth=$session['user']['maxhitpoints'];
                $userdefense=$session['user']['defense'];
                $badguy['creaturelevel']+=$userlevel;
                $badguy['creatureattack']+=$userattack;
                $badguy['creaturehealth']=intval($userhealth*1.2);
                $badguy['creaturedefense']+=$userdefense;
                $session['user']['badguy']=createstring($badguy);
                $session['bufflist']['foresta'] = array(
                "startmsg"=>"`n`@I nemici immaginari ti circondano e ti colpiscono col le loro armi immaginarie!`n`n",
                "name"=>"`\$Nemici Immaginari`0",
                "rounds"=>15,
                "wearoff"=>"le Cavallette sono sazie.",
                "minioncount"=>$session['user']['level'],
                "mingoodguydamage"=>0,
                "maxgoodguydamage"=>3+$dkb,
                "effectmsg"=>"Un nemico immaginario ti colpisce per {damage} punti danno.",
                "effectnodmgmsg"=>"Un nemico immaginario cerca di colpirti con la sua arma immaginaria ma ti MANCA.",
                "activate"=>"roundstart",
                );
                addnav("Combatti","foresta.php?op=fight");
                addnav("Scappa","foresta.php?op=run");
            }elseif($caso==0){
                output("`3Mentre stai lavorando senti un grido di avvertimento di un altro boscaiolo, un albero ti stà cadendo addosso!");
                output("`nFai appena in tempo a gettarti da una parte, purtroppo la tua accetta è stata schiacciata ed ora è inutilizzabile...");
                $sqlogg = "SELECT * FROM oggetti WHERE id_oggetti = ".$session['user']['oggetto'];
                $resultogg = db_query($sqlogg) or die(db_error(LINK));
                $rowogg = db_fetch_assoc($resultogg);
                if ($rowogg['nome'] == "Ascia da boscaiolo"){
                    //$oldloc = $rowogg['dove_origine'];
                    $oid = $rowogg['id_oggetti'];
                    $sql = "UPDATE oggetti SET dove=1 WHERE id_oggetti=$oid";
                    db_query($sql) or die(db_error(LINK));
                    $session['user']['oggetto'] = 0;
                }else{
                    $sqlogg = "SELECT * FROM oggetti WHERE id_oggetti = ".$session['user']['zaino'];
                    $resultogg = db_query($sqlogg) or die(db_error(LINK));
                    $rowogg = db_fetch_assoc($resultogg);
                    //$oldloc = $rowogg['dove_origine'];
                    $oid = $rowo['id_oggetti'];
                    $sql = "UPDATE oggetti SET dove=1 WHERE id_oggetti=$oid";
                    db_query($sql) or die(db_error(LINK));
                    $session['user']['zaino'] = 0;
                }
                addnav("Torna all'Inizio", "foresta.php");
            }else{
                if ($session['user']['turni_mestiere']>0) {
                    addnav("Lavora", "foresta.php?op=lavora3");
                }
                addnav("S?Vai ai Sentieri", "foresta.php?op=zona_lavoro");
            }
        }
    }
}
if ($_GET['op']=="lavora4") {
    $session['user']['dove_sei']=0;
    $badguy=array();
    usuraascia();
    if ($session['user']['oggetto'] != 0) {
        $hungerchance=rand(0,3);
        if ($session['user']['superuser'] >= 3) output("Debug: Chance di avere fame $hungerchance`n");
        if ($hungerchance == 3) $session['user']['hunger'] += 1;
        $bladchance=rand(0,4);
        if ($session['user']['superuser'] >= 3) output("Debug: Chance di andare in bagno $bladchance`n");
        if ($bladchance == 4) $session['user']['bladder'] += 1;
        $cleanchance=rand(0,3);
        if ($session['user']['superuser'] >= 3) output("Debug: Chance di sporcarsi $cleanchance`n");
        if ($cleanchance == 3) $session['user']['clean'] += 2;
        $session['user']['gold']+=intval($stipendio*1.08);
        $session['user']['turni_mestiere']-=1;
        //if($session['user']['boscaiolo']< 5)$session['user']['boscaiolo']+=0.01;
        //if($session['user']['boscaiolo']< 10)$session['user']['boscaiolo']+=0.01;
        output("`3Lavora!`3`n");
        output("`3Impugni la tua accetta e colpisci la grande quercia!`3`n");
        $forza=intval(($session['user']['attack']+$session['user']['defence'])/100);
        if($forza==0)$forza=1;
        //result è una percentuale il massimo fissato indica la massima possibilità di riuscita
        $result=$session['user']['boscaiolo'];
        if($result>15)$result=15;
        //$result=100;
        $cento=rand(1,100);
        addnav("Azioni");
        if($result >= $cento){
            //savesetting("carbone",getsetting("carbone",0)+1);
            savesetting("leccio",getsetting("leccio",0)+1);
            $sqldr="INSERT INTO foresta (acctid,data,materiale) VALUES ('$account',FROM_UNIXTIME(UNIX_TIMESTAMP()),'Leccio')";
            db_query($sqldr) or die(db_error(LINK));
            $fatica=rand(0,1);
            $session['user']['hitpoints']-=$fatica;
            if ($session['user']['hitpoints'] <=0) $session['user']['hitpoints']=1;
            output("`n`#Un gran colpo!!`n");
            if (rand(1,100) > 95){
                output("Sei riuscito a ricavare del `&Legno di Rovere`3 dalla corteccia!!");
                output("`nLa riponi nel tuo zaino pensando all'oro che potrai ricavarci.`n");
                if (zainoPieno($session['user']['acctid'])){
                    output ("Purtroppo ti accorgi che lo zaino è pieno e a malincuore getti la getti via, un vero peccato...");
                }else{
                    debuglog("trova un Legno di Rovere nella foresta dei boscaioli");
                    $sqli="INSERT INTO zaino (idoggetto, idplayer) VALUES ('29', '$account')";
                    db_query($sqli) or die(db_error(LINK));
                }
            }
            $session['user']['boscaiolo']+=0.01;
            $caso=rand(1, 200);
            //$caso=185;
            if($caso==200){
                output("`#Dalla corteccia dell'albero si apre una fessura da dove affiorano delle gemme ... le conti e scopri ");
                output("che sono `^`b10`#`b!!!!`nQuesto si che è un colpo di fortuna!! Un po' meno per chi le aveva nascoste lì ma ormai sono tue!`3`n");
                debuglog("trova 10 gemme nella foresta dei boscaioli");
                $session['user']['gems']+=10;
                if ($session['user']['turni_mestiere']>0) {
                    addnav("Lavora", "foresta.php?op=lavora3");
                }
                addnav("S?Vai ai Sentieri", "foresta.php?op=zona_lavoro");
            }elseif($caso>=197 AND $caso< 200){
                output("`#Dalla corteccia dell'albero si apre una fessura da dove affiorano `&due gemme`#, che prontamente infili in tasca!`3`n");
                $session['user']['gems']+=2;
                debuglog("trova due gemme nella foresta dei boscaioli");
                if ($session['user']['turni_mestiere']>0) {
                    if($session['user']['superuser']>0) addnav("S?Vai ai Sentieri (Admin)", "foresta.php?op=zona_lavoro");
                    addnav("Lavora", "foresta.php?op=lavora4");
                }else{
                    addnav("S?Vai ai Sentieri", "foresta.php?op=zona_lavoro");
                }
            }elseif($caso>=190 AND $caso< 197){
                $gold = $session['user']['level'] * 100;
                output("`#Dalla corteccia dell'albero si apre una fessura da dove affiora un borsellino contenente {$gold} pezzi d'oro!`3`n");
                debuglog("trova $gold pezzi d'oro nella foresta dei boscaioli");
                $session['user']['gold']+=$gold;
                if ($session['user']['turni_mestiere']>0) {
                    if($session['user']['superuser']>0) addnav("S?Vai ai Sentieri (Admin)", "foresta.php?op=zona_lavoro");
                    addnav("Lavora", "foresta.php?op=lavora4");
                }else{
                    addnav("S?Vai ai Sentieri", "foresta.php?op=zona_lavoro");
                }
            }elseif($caso>=189 AND $caso<190){
                output("`#Dal colpo che hai dato, si apre una spaccatura nella corteccia, ed una pergamena viene alla luce.`n");
                output("La esamini e scopri che è una ricetta, felice per il ritrovamento la metti nello zaino!`3`n");
                if (zainoPieno($session['user']['acctid'])){
                    output ("Purtroppo ti accorgi che lo zaino è pieno e a malincuore getti la getti via, un vero peccato...");
                }else{
                    $sqldr="INSERT INTO zaino (idoggetto,idplayer) VALUES ('25','$account')";
                    db_query($sqldr) or die(db_error(LINK));
                    debuglog("trova ricetta 25 nella foresta dei boscaioli");
                }
                if ($session['user']['turni_mestiere']>0) {
                    if($session['user']['superuser']>0) addnav("S?Vai ai Sentieri (Admin)", "foresta.php?op=zona_lavoro");
                    addnav("Lavora", "foresta.php?op=lavora4");
                }else{
                    addnav("S?Vai ai Sentieri", "foresta.php?op=zona_lavoro");
                }
            }elseif($caso>=184 AND $caso< 188){
                output("`#AAAAARGGHHH!! Hai scoperto i resti di un Boscaiolo morto. Il suo scheletro si risveglia e ti attacca!`3`n");
                $badguy = array("creaturename"=>"`&Scheletro di Boscaiolo`0"
                ,"creaturelevel"=>3
                ,"creatureweapon"=>"`7Ascia da boscaiolo`0"
                ,"creatureattack"=>5
                ,"creaturedefense"=>5
                ,"diddamage"=>0);
                $userlevel=$session['user']['level'];
                $userattack=$session['user']['attack'];
                $userhealth=$session['user']['maxhitpoints'];
                $userdefense=$session['user']['defense'];
                $badguy['creaturelevel']+=$userlevel;
                $badguy['creatureattack']+=$userattack;
                $badguy['creaturehealth']=intval($userhealth*1.8);
                $badguy['creaturedefense']+=$userdefense;
                $session['user']['badguy']=createstring($badguy);
                addnav("Combatti","foresta.php?op=fight");
                addnav("Scappa","foresta.php?op=run");
            }elseif($caso>=180 AND $caso< 184){
                output("`4Purtroppo non hai sentito le grida di avvertimento di un altro boscaiolo.");
                output("`nUn albero ti crolla addosso e muori solo e tra dolori atroci!!!`n");
                output("`\$Hai perso il 5% della tua esperienza !!!`n");
                addnews("`%".$session['user']['name']."`5 è morto schiacciato da un albero, mentre lavorava nella foresta!!");
                debuglog("è morto schiacciato da un albero in foresta e ha perso ".$session['user']['gold']." oro");
                $session['user']['experience'] *= 0.95;
                $session['user']['alive'] = false;
                $session['user']['hitpoints'] = 0;
                if($session['user']['assicurazione']==1) $session['user']['goldinbank']+=$session['user']['gold'];
                $session['user']['gold'] = 0;
                addnav("Notizie Giornaliere","news.php");
            }elseif($caso>=175 AND $caso< 180){
                output("`#AAAAARGGHHH!! Hai incontrato dei Demoni dei Boschi in cerca di preda!`3`n");
                $dkb = round($session['user']['dragonkills']*.15);
                $badguy = array("creaturename"=>"`(Demoni dei Boschi`0"
                ,"creaturelevel"=>2
                ,"creatureweapon"=>"`\$Artigliate Maledette`0"
                ,"creatureattack"=>5
                ,"creaturedefense"=>5
                ,"diddamage"=>0);
                $userlevel=$session['user']['level'];
                $userattack=$session['user']['attack'];
                $userhealth=$session['user']['maxhitpoints'];
                $userdefense=$session['user']['defense'];
                $badguy['creaturelevel']+=$userlevel;
                $badguy['creatureattack']+=$userattack;
                $badguy['creaturehealth']=intval($userhealth*1.6);
                $badguy['creaturedefense']+=$userdefense;
                $session['user']['badguy']=createstring($badguy);
                $session['bufflist']['foresta'] = array(
                "startmsg"=>"`n`%l'artiglio è infetto e produce emorragia!`n`n",
                "name"=>"`%Artigliate Maledette`0",
                "rounds"=>10,
                "wearoff"=>"La ferita si richiude.",
                "minioncount"=>10,
                "mingoodguydamage"=>1,
                "maxgoodguydamage"=>2+$dkb,
                "effectmsg"=>"Il sangue perso causa la perdita di {damage} HitPoint.",
                    "effectnodmgmsg"=>"Fortunatamente dalla ferita non scorre troppo sangue.",
                "activate"=>"roundstart",
                );
                addnav("Combatti","foresta.php?op=fight");
                addnav("Scappa","foresta.php?op=run");
            }elseif($caso>=169 AND $caso< 175){
                output("`3Mentre stai lavorando senti un grido di avvertimento di un altro boscaiolo, un albero ti stà cadendo addosso!");
                output("`nFai appena in tempo a gettarti da una parte, la paura ti paralizza e purtroppo perdi 5 turni ti lavoro ma almeno sei salvo.");
                $session['user']['turni_mestiere'] -= 5;
                if ($session['user']['turni_mestiere'] < 0) $session['user']['turni_mestiere'] = 0;
                if ($session['user']['turni_mestiere']>0) {
                    if($session['user']['superuser']>0) addnav("S?Vai ai Sentieri (Admin)", "foresta.php?op=zona_lavoro");
                    addnav("Lavora", "foresta.php?op=lavora4");
                }else{
                    addnav("S?Vai ai Sentieri", "foresta.php?op=zona_lavoro");
                }
            }elseif($caso>=160 AND $caso<169){
                output("`n`3Noti solo ora nel sentiero una lucina flebile in lontananza e decidi di investigare.`n");
                output("Mentre ti avvicini alla luce vedi una strana costruzione e scopri che si tratta di un bagno ");
                output("pubblico. Chi mai lo avrà costruito? Probabilmente qualche boscaiolo solitario che non voleva ");
                output("`innaffiare`i il bosco o che non voleva rinunciare alla comodità di un vero bagno.`n");
                output("Chiunque sia stato, decidi di approfittare dell'opportunità e ti liberi di un peso superfluo.`n`n");
                $session['user']['bladder']=0;
                if ($session['user']['turni_mestiere']>0) {
                    if($session['user']['superuser']>0) addnav("S?Vai ai Sentieri (Admin)", "foresta.php?op=zona_lavoro");
                    addnav("Lavora", "foresta.php?op=lavora4");
                }else{
                    addnav("S?Vai ai Sentieri", "foresta.php?op=zona_lavoro");
                }
            }elseif($caso>=159 AND $caso<160){
                output("`#Pensi di aver trovato un buon albero, e dai fondo alle tue energia dando un colpo molto potente ");
                output("con l'accetta. Purtroppo il colpo è stato veramente `bTROPPO`b potente!!`n`n");
                output("L'accetta si spezza e ti ritrovi con qualche scheggia di legno ed un ");
                output("ammasso informe di metallo.`n");
                $sqlogg = "SELECT nome FROM oggetti WHERE id_oggetti = ".$session['user']['oggetto'];
                $resultogg = db_query($sqlogg) or die(db_error(LINK));
                $rowogg = db_fetch_assoc($resultogg);
                if ($rowogg['nome'] == "Ascia da boscaiolo"){
                    $sqlogg = "DELETE FROM oggetti WHERE id_oggetti = ".$session['user']['oggetto'];
                    $result = db_query($sqlogg) or die(db_error(LINK));
                    $session['user']['oggetto'] = 0;
                }else{
                    $sqlogg = "DELETE FROM oggetti WHERE id_oggetti = ".$session['user']['zaino'];
                    $result = db_query($sqlogg) or die(db_error(LINK));
                    $session['user']['zaino'] = 0;
                }
                addnav("Torna all'Inizio", "foresta.php");
            }elseif($caso>=155 AND $caso<159){
                output("`n`3Noti solo ora nel sentiero una lucina flebile in lontananza e decidi di investigare.`n");
                output("Mentre ti avvicini alla luce vedi una strana costruzione e scopri che si tratta di un bagno ");
                output("pubblico. Chi mai lo avrà costruito? Probabilmente qualche boscaiolo solitario che non voleva ");
                output("`innaffiare`i il bosco o che non voleva rinunciare alla comodità di un vero bagno.`n");
                output("Chiunque sia stato, decidi di approfittare dell'opportunità e ti liberi di un peso superfluo.`n`n");
                output("Durante la minzione noti qualcosa che brilla sul pavimento. Con estrema cautela ti chini e scopri ");
                output("che è una `&gemma`3, persa da qualche altro boscaiolo!!`n`n");
                $session['user']['gems']++;
                debuglog("trova una gemma al gabinetto della foresta dei boscaioli");
                $session['user']['bladder']=0;
                if ($session['user']['turni_mestiere']>0) {
                    if($session['user']['superuser']>0) addnav("S?Vai ai Sentieri (Admin)", "foresta.php?op=zona_lavoro");
                    addnav("Lavora", "foresta.php?op=lavora4");
                }else{
                    addnav("S?Vai ai Sentieri", "foresta.php?op=zona_lavoro");
                }
            }elseif($caso>=151 AND $caso<155){
                output("`n`3Noti solo ora nel sentiero una lucina flebile in lontananza e decidi di investigare.`n");
                output("Mentre ti avvicini alla luce vedi una strana costruzione e scopri che si tratta di un bagno ");
                output("pubblico. Chi mai lo avrà costruito? Probabilmente qualche boscaiolo solitario che non voleva ");
                output("`innaffiare`i il bosco o che non voleva rinunciare alla comodità di un vero bagno.`n");
                output("Chiunque sia stato, decidi di approfittare dell'opportunità e ti liberi di un peso superfluo.`n`n");
                output("Durante la minzione purtroppo ti cade una `&gemma`3 dalla tasca, ed il pavimento è troppo sporco per ");
                output("rischiare di riprenderla ... chissà quale terribile malattia contrarresti!!`n`n");
                $session['user']['gems']--;
                debuglog("perde una gemma al gabinetto della foresta dei boscaioli");
                $session['user']['bladder']=0;
                if ($session['user']['turni_mestiere']>0) {
                    if($session['user']['superuser']>0) addnav("S?Vai ai Sentieri (Admin)", "foresta.php?op=zona_lavoro");
                    addnav("Lavora", "foresta.php?op=lavora4");
                }else{
                    addnav("S?Vai ai Sentieri", "foresta.php?op=zona_lavoro");
                }
            }elseif($caso>=147 AND $caso<151){
                output("`n`3Noti solo ora nel sentiero una lucina flebile in lontananza e decidi di investigare.`n");
                output("Mentre ti avvicini alla luce vedi una strana costruzione e scopri che si tratta di una doccia ");
                output("pubblica. Chi mai l'avrà costruita? Sicuramente questo `^Thonetauer`3 non voleva ");
                output("rischiare di sporcarsi troppo, voleva tornare al villaggio lindo.`n");
                output("Decidi di approfittare dell'opportunità e ti dai una bella lavata.`n");
                output("Al termine ti senti pulito, il sapone è stato il tocco di classe che ti ha permesso di lavarti ");
                output("come non avveniva da tempo ... in cuor tuo ringrazi il povero boscaiolo scomparso.`n`n");
                $session['user']['clean']=0;
                if ($session['user']['turni_mestiere']>0) {
                    if($session['user']['superuser']>0) addnav("S?Vai ai Sentieri (Admin)", "foresta.php?op=zona_lavoro");
                    addnav("Lavora", "foresta.php?op=lavora4");
                }else{
                    addnav("S?Vai ai Sentieri", "foresta.php?op=zona_lavoro");
                }
            }elseif($caso>=144 AND $caso<147){
                if ($session['user']['potion']<5){
                    output("`2Dalla corteccia dell'albero si apre una fessura, qualcosa che assomiglia ad una fiala di colore verde affiora. ");
                    output("`@Hai trovato una Pozione Guaritrice!`2`n`n");
                    $session['user']['potion']+=1;
                }else{
                    output("`2Dalla corteccia dell'albero si apre una fessura, qualcosa che assomiglia ad una fiala di colore verde affiora. ");
                    output("`@Hai trovato una Pozione Guaritrice!`2`n`n");
                    output("Peccato tu non abbia lo spazio per portarla con te.`n");
                    output("Seppur a malincuore la riponi nella fenditura... ");
                    output("forse qualche altro boscaiolo la troverà e potrà usarla.`n`n");
                }
                if ($session['user']['turni_mestiere']>0) {
                    if($session['user']['superuser']>0) addnav("S?Vai ai Sentieri (Admin)", "foresta.php?op=zona_lavoro");
                    addnav("Lavora", "foresta.php?op=lavora4");
                }else{
                    addnav("S?Vai ai Sentieri", "foresta.php?op=zona_lavoro");
                }
            }elseif($caso>=142 AND $caso<144){
                output("`2All'improvviso senti una flebile melodia provenire dal profondo del bosco. La musica è ");
                output("dolcissima e ne sei attratto come l'ago della bussola è attratto dal polo magnetico della terra. ");
                output("Non riesci a resistere e inizi a camminare facendoti guidare dalla musica.`n");
                output("Dopo un tempo che ti sembra un'eternità, giungi in una caverna dalla strana conformazione. Una ");
                output("forte corrente d'aria, proveniente chissà da dove, incanalandosi nelle strane concrezioni produce ");
                output("la musica che sentivi!!`nIl tempo che hai dedicato alla ricerca della sorgente musicale ti ha fatto ");
                $turnipersi = intval(($session['user']['turni_mestiere']/2)+1);
                $session['user']['turni_mestiere']-=$turnipersi;
                output("perdere `^$turnipersi turni`2 di lavoro!!`nScornato torni sui tuoi passi...`n`n");
                if ($session['user']['turni_mestiere']>0) {
                    if($session['user']['superuser']>0) addnav("S?Vai ai Sentieri (Admin)", "foresta.php?op=zona_lavoro");
                    addnav("Lavora", "foresta.php?op=lavora4");
                }else{
                    addnav("S?Vai ai Sentieri", "foresta.php?op=zona_lavoro");
                }
            }elseif($caso>=140 AND $caso<142){
                output("`2All'improvviso senti una flebile melodia provenire dal profondo del bosco. La musica è ");
                output("dolcissima e ne sei attratto come l'ago della bussola è attratto dal polo magnetico della terra. ");
                output("Non riesci a resistere e inizi a camminare facendoti guidare dalla musica.`n");
                output("Dopo un tempo che ti sembra un'eternità, giungi in una caverna dalla strana conformazione. Una ");
                output("forte corrente d'aria, proveniente chissà da dove, incanalandosi nelle strane concrezioni produce ");
                output("la musica che sentivi!!`nNella caverna trovi anche un pacchetto confezionato di `(Coscia di maiale ");
                output("arrosto`2, confezionato qualche giorno fa. Deve essere stato dimenticato da qualche altro boscaiolo ");
                output("attirato anche lui dalla musica misteriosa.`nLo apri ed inizi a divorarlo ... eri affamato!!`n");
                output("La mangiata ha quasi saziato la tua fame, e ti ha dato l'energia per altri `^10 turni`2 di lavoro!!`n`n");
                $session['user']['turni_mestiere']+=10;
                $fame= intval(rand(.6,.9)*$session['user']['hunger']);
                $session['user']['hunger']-=$fame;
                if ($session['user']['turni_mestiere']>0) {
                    if($session['user']['superuser']>0) addnav("S?Vai ai Sentieri (Admin)", "foresta.php?op=zona_lavoro");
                    addnav("Lavora", "foresta.php?op=lavora4");
                }else{
                    addnav("S?Vai ai Sentieri", "foresta.php?op=zona_lavoro");
                }
            }else{
                if ($session['user']['turni_mestiere']>0) {
                    if($session['user']['superuser']>0) addnav("S?Vai ai Sentieri (Admin)", "foresta.php?op=zona_lavoro");
                    addnav("Lavora", "foresta.php?op=lavora4");
                }else{
                    addnav("S?Vai ai Sentieri", "foresta.php?op=zona_lavoro");
                }
            }
        }else{
            output("`vImpegnati di più mettici un po' di energia in quei colpi!`3`n");
            $fatica=rand(0, (intval($session['user']['level']/5)+1));
            $session['user']['hitpoints']-=$fatica;
            if ($session['user']['hitpoints'] <= 0) $session['user']['hitpoints']=1;
            $caso=rand(0, 100);
            //$caso = 86;
            if($caso>=86 AND $caso< 89){
                output("`#AAAAARGGHHH!! Mentre stai lavorando senti un grido di avvertimento di un altro boscaiolo, un albero ti stà cadendo addosso! ");
                output("Ti scansi ma l'albero cade sul tuo piede sinistro ferendoti e facendoti zoppicare.`n");
                output("Probabilmente il prossimo combattimento non sarai molto prestante!`3`n");
                $session['bufflist']['zoppichi'] = array(
                "name"=>"`4Piede Ferito",
                "rounds"=>10,
                "wearoff"=>"Il dolore inizia lentamente a passare.",
                "atkmod"=>.85,
                "defmod"=>.85,
                "roundmsg"=>"Zoppichi con il piede sinistro dolorante!",
                "activate"=>"roundstart"
                );
                if ($session['user']['turni_mestiere']>0) {
                    if($session['user']['superuser']>0) addnav("S?Vai ai Sentieri (Admin)", "foresta.php?op=zona_lavoro");
                    addnav("Lavora", "foresta.php?op=lavora4");
                }else{
                    addnav("S?Vai ai Sentieri", "foresta.php?op=zona_lavoro");
                }
            }elseif($caso>=85 AND $caso< 86){
                output("`4Purtroppo non hai sentito le grida di avvertimento di un altro boscaiolo.");
                output("`nUn albero ti crolla addosso e muori solo e tra dolori atroci!!!`n");
                output("`\$Hai perso il 5% della tua esperienza !!!`n");
                addnews("`%".$session['user']['name']."`5 è morto schiacciato da un albero, mentre lavorava nella foresta!!");
                debuglog("è morto schiacciato da un albero in foresta e ha perso ".$session['user']['gold']." oro");
                $session['user']['experience'] *= 0.95;
                $session['user']['alive'] = false;
                $session['user']['hitpoints'] = 0;
                if($session['user']['assicurazione']==1) $session['user']['goldinbank']+=$session['user']['gold'];
                $session['user']['gold'] = 0;
                addnav("Notizie Giornaliere","news.php");
            }elseif($caso>=80 AND $caso< 85){
                output("`#AAAAARGGHHH!! Mentre stai lavorando senti un grido di avvertimento di un altro boscaiolo, un albero ti stà cadendo addosso! ");
                output("Ti scansi ma l'albero cade sul tuo piede destro ferendoti seriamente e facendoti zoppicare. `n");
                output("Probabilmente il prossimo combattimento non sarai molto atletico!`3`n");
                $session['bufflist']['zoppichino'] = array(
                "name"=>"`4Piede Ferito",
                "rounds"=>5,
                "wearoff"=>"Il dolore inizia lentamente a passare.",
                "atkmod"=>.8,
                "defmod"=>.8,
                "roundmsg"=>"Zoppichi con il piede destro dolorante!",
                "activate"=>"roundstart"
                );
                if ($session['user']['turni_mestiere']>0) {
                    if($session['user']['superuser']>0) addnav("S?Vai ai Sentieri (Admin)", "foresta.php?op=zona_lavoro");
                    addnav("Lavora", "foresta.php?op=lavora4");
                }else{
                    addnav("S?Vai ai Sentieri", "foresta.php?op=zona_lavoro");
                }
            }elseif($caso==0){
                output("`3Mentre stai lavorando senti un grido di avvertimento di un altro boscaiolo, un albero ti stà cadendo addosso!");
                output("`nFai appena in tempo a gettarti da una parte, purtroppo la tua accetta è stata schiacciata ed ora è inutilizzabile...");
                $sqlogg = "SELECT * FROM oggetti WHERE id_oggetti = ".$session['user']['oggetto'];
                $resultogg = db_query($sqlogg) or die(db_error(LINK));
                $rowogg = db_fetch_assoc($resultogg);
                if ($rowogg['nome'] == "Ascia da boscaiolo"){
                    //$oldloc = $rowogg['dove_origine'];
                    $oid = $rowogg['id_oggetti'];
                    $sql = "UPDATE oggetti SET dove=1 WHERE id_oggetti=$oid";
                    db_query($sql) or die(db_error(LINK));
                    $session['user']['oggetto'] = 0;
                }else{
                    $sqlogg = "SELECT * FROM oggetti WHERE id_oggetti = ".$session['user']['zaino'];
                    $resultogg = db_query($sqlogg) or die(db_error(LINK));
                    $rowogg = db_fetch_assoc($resultogg);
                    //$oldloc = $rowogg['dove_origine'];
                    $oid = $rowo['id_oggetti'];
                    $sql = "UPDATE oggetti SET dove=1 WHERE id_oggetti=$oid";
                    db_query($sql) or die(db_error(LINK));
                    $session['user']['zaino'] = 0;
                }
                addnav("Torna all'Inizio", "foresta.php");
            }else{
                if ($session['user']['turni_mestiere']>0) {
                    if($session['user']['superuser']>0) addnav("S?Vai ai Sentieri (Admin)", "foresta.php?op=zona_lavoro");
                    addnav("Lavora", "foresta.php?op=lavora4");
                }else{
                    addnav("S?Vai ai Sentieri", "foresta.php?op=zona_lavoro");
                }
            }
        }
    }
}
if ($_GET['op']=='battle' || $_GET['op']=='run') {
    if ($_GET['op']=='run') {
        output("Non riesci a fuggire al mostro della foresta!!");
        $_GET['op']="fight";
    }
}
if ($_GET['op']=='fight') {
    $battle=true;
}

if ($battle) {
    include_once("battle.php");
    if($victory) {
    if ($session['user']['hitpoints'] > 0){
        unset($session['bufflist']['foresta']);
        $badguy = createarray($session['user']['badguy']);
        $exp = array(1=>14,26,37,50,61,73,85,98,111,125,140,155,172,189,208,228,250,275,310,348);
        output("Hai battuto `^".$badguy['creaturename'].".`n");
        $guadagno=round($exp[$badguy['creaturelevel']]/2);
        output("Hai guadagnato $guadagno punti esperienza !!!`n");
        addnews("`%".$session['user']['name']."`@ è stato attaccato da ".$badguy['creaturename']. "`@, mentre lavorava nella foresta!! E ha vinto!");
        $session['user']['experience']+=$guadagno;
        $session['user']['badguy']="";
        if ($session['user']['turni_mestiere']>0) {
        ////mettere le varie condizioni in base al mostro per tornare alla zona da cui si proveniva
        // lavora1 - fatto
        if($badguy['creaturename']=="`8Ragno Velenoso`0" OR
           $badguy['creaturename']=="`4Tarme Carnivore`0" OR
           $badguy['creaturename']=="`8Vespe Arrabbiate`0" OR
           $badguy['creaturename']=="`5Cinghiale`0"){
           if($session['user']['superuser']>0) addnav("S?Vai ai Sentieri (Admin)", "foresta.php?op=zona_lavoro");
           addnav("Torna al lavoro","foresta.php?op=lavora1");
        // lavora2 - fatto
        }elseif($badguy['creaturename']=="`9Toporagni Voraci`0" OR
                $badguy['creaturename']=="`\$Faina`0" OR
                $badguy['creaturename']=="`(Sparviero Gigante`0" OR
                $badguy['creaturename']=="`@Biacco Aggressivo`0"){
                if($session['user']['superuser']>0) addnav("S?Vai ai Sentieri (Admin)", "foresta.php?op=zona_lavoro");
                addnav("Torna al lavoro","foresta.php?op=lavora2");
        // lavora3 - fatto
        }elseif($badguy['creaturename']=="`&Boscaiolo Fantasma`0" OR
                $badguy['creaturename']=="`\$Troll del Boschi`0" OR
                $badguy['creaturename']=="`&Scheletro Ossuto`0" OR
                $badguy['creaturename']=="`@Nemici Immaginari`0"){
                if($session['user']['superuser']>0) addnav("S?Vai ai Sentieri (Admin)", "foresta.php?op=zona_lavoro");
                addnav("Torna al lavoro","foresta.php?op=lavora3");
        // queravv - fatto
        }elseif($badguy['creaturename']=="`(Fantasma`0"){
                if($session['user']['superuser']>0) addnav("S?Vai ai Sentieri (Admin)", "foresta.php?op=zona_lavoro");
                addnav("Torna al lavoro","foresta.php?op=queravv&op1=fatto");
        // lavora4 - fatto
        }elseif($badguy['creaturename']=="`&Scheletro di Boscaiolo`0" OR
                $badguy['creaturename']=="`(Demoni dei Boschi`0"){
                if($session['user']['superuser']>0) addnav("S?Vai ai Sentieri (Admin)", "foresta.php?op=zona_lavoro");
                addnav("Torna al lavoro","foresta.php?op=lavora4");
        // spostati - fatto
        }elseif($badguy['creaturename']=="`^Thonetauer il Boscaiolo`0"){
                if($session['user']['superuser']>0) addnav("S?Vai ai Sentieri (Admin)", "foresta.php?op=zona_lavoro");
                addnav("Torna al lavoro","foresta.php?op=spostati&op1=fatto");
        }
        } else {
          addnav("Z?Zona di Taglio", "foresta.php?op=taglio");
        }

        }else{
            output("`4Sei morto!!`n`n");
            $session['user']['experience']*=0.95;
            $session['user']['badguy']="";
            $session['bufflist']['foresta'] = array();
            $session['user']['alive']=false;
            addnav("Notizie Giornaliere","news.php");
        }
    }else   {
        if ($defeat) {
            $badguy = createarray($session['user']['badguy']);
            output("`4Mentre cadi a terra `^".$badguy['creaturename']. "`4 ride, per quanto `^".$badguy['creaturename']. "`4 possa ridere!`n");
            output("`\$Hai perso il `b`^5%`b`\$ della tua esperienza !!!`n");
            output("Inoltre perdi tutto l'oro che avevi con te !!!`n`n");
            $sql = "SELECT taunt FROM taunts ORDER BY rand(".rand().") LIMIT 1";
            $result = db_query($sql) or die(db_error(LINK));
            $taunt = db_fetch_assoc($result);
            $taunt = str_replace("%s",($session['user']['sex']?"sua":"suo"),$taunt['taunt']);
            $taunt = str_replace("%o",($session['user']['sex']?"lei":"lui"),$taunt);
            $taunt = str_replace("%p",($session['user']['sex']?"her":"his"),$taunt);
            $taunt = str_replace("%x",($session['user']['weapon']),$taunt);
            $taunt = str_replace("%X",$badguy['creatureweapon'],$taunt);
            $taunt = str_replace("%W",$badguy['creaturename'],$taunt);
            $taunt = str_replace("%w",$session['user']['name'],$taunt);
            addnews("`%".$session['user']['name']."`6 è stato ucciso da ".$badguy['creaturename']. "`6, mentre lavorava in foresta!!`n$taunt");
            debuglog("è stato ucciso da un ".$badguy['creaturename']. " mentre lavorava in foresta. Perde 5% exp e ".$session['user']['gold']." oro");
            addnav("Notizie Giornaliere","news.php");
            $session['user']['experience']*=0.95;
            $session['user']['alive']=false;
            $session['user']['hitpoints']=0;
            if($session['user']['assicurazione']==1) $session['user']['goldinbank']+=$session['user']['gold'];
            $session['user']['gold']=0;
            //Sook: Modifica per suicidio con fantasma
            if ($badguy == "`(Fantasma`0") {
                    $corrosione = e_rand(1,8);
                    if ($corrosione == 1 || $corrosione == 2 || $corrosione == 5 || $corrosione == 6) {
                            $session['user']['usura_arma'] -= e_rand(5,50);
                            output("`\$Il tocco del fantasma ha danneggiato la tua arma!`n");
                            if ($session['user']['usura_arma'] <= 0) {
                                  output("`7`b".$session['user']['weapon']." è talmente usurato che lo devi buttare via, ");
                                  output("ti ritrovi ancora una volta a usare i Pugni...`n");
                                  debuglog("ha l'arma distrutta dal fantasma del bosco oscuro");
                                  $session['user']['usura_arma']=999;
                                  $session['user']['weapon']='Pugni';
                                  $session['user']['attack']-=$session['user']['weapondmg'];
                                  $session['user']['weaponvalue']=0;
                                  $session['user']['weapondmg']=0;
                            } else {
                                  debuglog("danneggia l'arma col fantasma del bosco oscuro");
                            }
                    }
                    if ($corrosione == 1 || $corrosione == 3 || $corrosione == 5 || $corrosione == 7) {
                            output("`\$Il tocco del fantasma ha danneggiato la tua armatura!`n");
                            $session['user']['usura_armatura'] -= e_rand(5,50);
                            if ($session['user']['usura_armatura'] <= 0) {
                                  output("`7`b".$session['user']['armor']." è talmente usurato che lo devi buttare via, ");
                                  output("ti ritrovi ancora una volta a usare la T-Shirt...");
                                  debuglog("ha l'armatura distrutta dal fantasma del bosco oscuro");
                                  $session['user']['usura_armatura']=999;
                                  $session['user']['armor']='T-Shirt';
                                  $session['user']['defence']-=$session['user']['armordef'];
                                  $session['user']['armordef'] = 0;
                                  $session['user']['armorvalue'] = 0;
                            } else {
                                  debuglog("danneggia l'armatura col fantasma del bosco oscuro");
                            }
                    }
                    if ($corrosione < 5) {
                        $caso=e_rand(1,5);
                        $sqlo = "SELECT usura FROM oggetti WHERE id_oggetti = '{$session['user']['oggetto']}'";
                        $resulto = db_query($sqlo) or die(db_error(LINK));
                        $rowo = db_fetch_assoc($resulto);
                        $rowo['usura'] -= e_rand(5,50);
                        if ($caso !=1 AND $rowo['usura'] > 0) {
                            output("`\$Il tocco del fantasma ha danneggiato la tua accetta!`n");
                            $sqlo = "UPDATE oggetti SET usura=".$rowo['usura']." WHERE id_oggetti = '{$session['user']['oggetto']}'";
                            $resulto = db_query($sqlo) or die(db_error(LINK));
                            debuglog("danneggia l'accetta col fantasma del bosco oscuro");
                        } else {
                            output("`\$Il tocco del fantasma ha `bdistrutto`b la tua accetta!`n `7Ora dovrai procurartene una nuova...");
                            $sqlo = "DELETE FROM oggetti WHERE id_oggetti = '{$session['user']['oggetto']}'";
                            $resulto = db_query($sqlo) or die(db_error(LINK));
                            debuglog("distrugge l'accetta col fantasma del bosco oscuro");
                         }
                    }
            }
            //Sook: fine modifica
        }else{
            fightnav(true,false);
        }

    }
}
page_footer();

?>