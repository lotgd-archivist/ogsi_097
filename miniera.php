<?php
require_once("common.php");
require_once("common2.php");
addcommentary();
page_header("La Miniera");
$session['user']['locazione'] = 153;
$idplayer = $session['user']['acctid'];
//Sook, modifica per usura piccone
function usurapiccone() {
    global $session;
    $sqlo = "SELECT usura, usuramax FROM oggetti WHERE id_oggetti = '{$session['user']['oggetto']}'";
    $resulto = db_query($sqlo) or die(db_error(LINK));
    $rowo = db_fetch_assoc($resulto);
    if ($rowo[usuramax]>0) {
        if ($rowo[usura]!= 1) {
            $sqlu = "UPDATE oggetti SET usura = '".($rowo[usura]-1)."' WHERE id_oggetti = '{$session['user']['oggetto']}'";
            db_query($sqlu) or die(db_error(LINK));
        }else{
            $sqlogg = "DELETE FROM oggetti WHERE id_oggetti = ".$session['user']['oggetto'];
            $result = db_query($sqlogg) or die(db_error(LINK));
            $session['user']['oggetto'] = 0;
            output("Il tuo piccone, già provato dai colpi precedenti, non ha retto ed a seguito dell'ennesimo colpo alla roccia, si è sgretolato.`n");
            output("Senza più un piccone con cui lavorare, non ti resta che ritornare al villaggio, dove forse potrai procurartene uno nuovo.`n");
            addnav("Torna all'ingresso", "miniera.php");
        }
    }
}
$turni_mestiere=5;//aggiungere modifica per pass capanno
$stipendio = intval(($session['user']['level']*25)/$turni_mestiere);
$account=$session['user']['acctid'];
if ($session['user']['turni_mestiere']<0) $session['user']['turni_mestiere']=0;
if ($_GET['op']=="") {
    addnav("Azioni");
    addnav("Entra nella Miniera", "miniera.php?op=entra");
    addnav("Torna al Villaggio", "village.php");
    output("`3Arrivi alla miniera nella periferia della città.`3`n");
    output("`3Un orco, con una gamba di legno, tiene in mano una frusta e ti dice \"`2Stiamo cercando braccia robuste per lavorare in miniera, la paga è di $stipendio monete per ogni turno di lavoro. Però, devi procurarti un piccone ... AMICO ... hahahaha !!!`3\"`n");
}
if ($_GET['op'] AND $_GET['op']!='scendi'
                AND $_GET['op']!='buio'
                AND $_GET['op']!='luce'
                AND $_GET['op']!='puzz'
                AND $_GET['op']!='zona_lavoro'
                AND $_GET['op']!='entra'
                AND $_GET['op']!='scavo'
                AND $_GET['op']!='addturns2'
                AND $_GET['op']!='addturns'
                AND $_GET['op']!='vecchio'
                AND $_GET['op']!='vecchio2'
                AND $_GET['op']!='vecchio3'
                AND $_GET['op']!='vecchio_bravo'
                AND $_GET['op']!='vecchio_bravo2'
                AND $_GET['op']!='vecchio_bravo3'
                ) {
    $turnilavoro=$session['user']['turni_mestiere']-1;
    if ($turnilavoro<0) $turnilavoro=0;
    addnav("Turni lavoro: ".$turnilavoro, "");
}elseif($_GET['op']=='zona_lavoro' OR  $_GET['op']=='luce' OR $_GET['op']=='buio' OR $_GET['op']=='scendi'){
    addnav("Turni lavoro: ".$session['user']['turni_mestiere'], "");
}
if ($_GET['op']=="entra") {
    $sqlo = "SELECT * FROM oggetti WHERE id_oggetti = '".$session['user']['oggetto']."'";
    $resultoo = db_query($sqlo) or die(db_error(LINK));
    $rowo = db_fetch_assoc($resultoo);
    if($rowo['nome']=='Piccone da minatore'){
        addnav("Esci dalla Miniera", "miniera.php");
        addnav("Zona di scavo", "miniera.php?op=scavo");
        output("`3L'orco ti guarda e dice: \"`2 bel piccone .... AMICO ....., vai a scavare! Non fare lo scansafatiche altrimenti assaggerai questa!`3\" e accarezza la frusta, con fare inquietante.`n");
    }else{
        output("`3Provi a entrare nella miniera.`3`n");
        output("`3L'orco ti guarda di sbieco e dice: \"`2 ... AMICO ... non vedo il tuo piccone, con cosa intendi scavare ?! Non fare il furbo con me comprati prima un piccone!`3\"`n");
        addnav("Torna al Villaggio", "village.php");
    }
}
if ($_GET['op']=="scavo") {
    addnav("Esci dalla Miniera", "miniera.php");
    output("`3La zona di scavo.`3`n");
    output("`3Arrivi alla zona di scavo, molti abitanti del villaggio si recano al lavoro nei vari tunnel, deve proprio essere un lavoro duro pensi, dall'aspetto delle loro facce!`3`n");
    output("`3Devi decidere quanti turni dedicare al lavoro, al momento ne hai `2".$session['user']['turni_mestiere']." !`3`n");
    output("`3Ogni turno che dedichi al lavoro in miniera diventa $turni_mestiere turni in miniera!`3`n");
    addnav("Vai al tunnel", "miniera.php?op=zona_lavoro");
    output("<form action='miniera.php?op=addturns' method='POST'>",true);
    addnav("","miniera.php?op=addturns");
    output("`bTurni che vuoi lavorare: <input name='amt' size='3'> <input type='submit' class='button' value='Lavora'>",true);
    output("</form>",true);

}
if ($_GET['op']=="addturns") {
    $_POST['amt'] = floor($_POST['amt']);
    if ($_POST['amt']<0){
        output("`\$Perchè vuoi prendermi in giro? Non puoi lavorare un numero di turni `bnegativo`b!!!`n");
        output("Riprova e non sbagliarti questa volta !!!`n");
        addnav("Zona di scavo","miniera.php?op=scavo");
    }else if ($_POST['amt']>$session['user']['turns']){
        output("`3Avevi solo ".$session['user']['turns']." turni!`3`n");
        output("Confermi che vuoi lavorare ".$session['user']['turns']." turni ?`n`n");
        addnav("Si", "miniera.php?op=addturns2&amt=".$session['user']['turns']."");
        addnav("No", "miniera.php?op=scavo");
    }else{
        output("Confermi che vuoi lavorare {$_POST['amt']} turni ?`n`n");
        addnav("Si", "miniera.php?op=addturns2&amt=$_POST[amt]");
        addnav("No", "miniera.php?op=scavo");
    }
}
if ($_GET['op']=="addturns2") {
    $session['user']['turni_mestiere']+=($_GET['amt']*$turni_mestiere);
    $session['user']['turns']-=$_GET['amt'];
    addnav("Zona di scavo", "miniera.php?op=zona_lavoro");
    output("Hai deciso di lavorare ".$_GET['amt']." turni, hai ottenuto ".($_GET['amt']*$turni_mestiere)." turni di lavoro.`n`n");
}

if ($_GET['op']=="zona_lavoro") {
    output("`3La biforcazione`3`n");
    output("Arrivi in una zona più larga le persone che si recano al lavoro si dividono, dove vuoi proseguire.`n`n");

    if ($session['user']['turni_mestiere']<=0) {
        output("`@Non hai turni lavoro!`3`n");
    }
    addnav("I?Tunnel Illuminato", "miniera.php?op=luce");
    addnav("B?Tunnel Buio", "miniera.php?op=buio");
    if ($session['user']['turni_mestiere']>0){
       addnav("P?Scendi in Profondità", "miniera.php?op=scendi");
    }
    addnav("Z?Tunnel Puzzolente", "miniera.php?op=puzz");
    addnav("S?Zona di Scavo", "miniera.php?op=scavo");
}
if ($_GET['op']=="luce") {
    output("`3Tunnel illuminato!`3`n");
    output("`3Questo grande tunnel illuminato è il posto migliore per iniziare pensi, un vecchio siede ad una scrivania, e annota alcune cose su un taccuino, alcuni minatori spingono carrelli pieni di carbone !`3`n");
    if ($session['user']['turni_mestiere']>0) {
        addnav("Lavora", "miniera.php?op=lavora1");
    }
    addnav("Parla al vecchio", "miniera.php?op=vecchio");
    addnav("Torna alla zona di scavo", "miniera.php?op=scavo");
}
if ($_GET['op']=="buio") {
    output("`3Tunnel buio!`3`n");
    output("`3Questo tunnel scende in profondità e diventa man mano sempre più buio, forse non è una buona idea scavare qui, ");
    output("ma d'altronde i migliori materiali si trovano in profondità, e quindi dovrai rischiare per ottenere qualcosa.`n");
    output("Anche qui un vecchio che sembra la fotocopia di quello visto nel tunnel illuminato, prende degli appunti su un taccuino, e alcuni minatori spingono carrelli pieni di ferro !`3`n");
    if ($session['user']['turni_mestiere']>0) {
        addnav("Lavora", "miniera.php?op=lavora2");
    }
    addnav("Parla al vecchio", "miniera.php?op=vecchio2");
    addnav("Torna alla zona di scavo", "miniera.php?op=scavo");
}
if ($_GET['op']=="puzz") {
    output("`3Tunnel puzzolente!`3`n");
    output("`3Questo tunnel emana un forte odore di zolfo; l'aria si riesce a malapena a respirare, e non ti stupisce che questa volta non ci sia nessun vecchio ad attenderti!`3`n");
    if ($session['user']['turni_mestiere']>0) {
        addnav("Lavora", "miniera.php?op=lavora5");
    }
    addnav("Torna alla zona di scavo", "miniera.php?op=scavo");
}
if ($_GET['op']=="scendi") {
    output("`3Ascensore per l'Inferno!`3`n");
    output("`3Prendi l'ascensore che riporta la scritta \"`4Lasciate ogni speranza o voi ch'entrate`3\", e appena muovi ");
    output("la leva che lo manovra per azionarlo, inizi a comprendere il vero significato del cartello. La discesa è ");
    output("estremamente lenta ed angosciante, la fioca luce proveniente dalla lampada a soffitto si accende e si spegne ");
    output("continuamente. Violenti strattoni sembrano voler spezzare le funi che sorreggono l'ascensore, e proprio ");
    output("quando sembra che la corsa non abbia fine, con un ultimo violento scossone l'ascensore si arresta.`n`nApri le griglie di ");
    output("protezione ed esci in un tunnel in cui l'unica luce sembra provenire da una debole luminescenza delle pareti stesse. ");
    output("Un piccolo atrio ricavato nella galleria ospita il solito vecchietto (ma sarà sempre lo stesso? ti chiedi), ");
    output("con il solito taccuino su cui prende appunti.`nTi saluta stancamente e ti augura buon lavoro.`n`n");
    if ($session['user']['turni_mestiere']>0) {
        addnav("Lavora", "miniera.php?op=lavora3");
    }
    addnav("Parla al vecchio", "miniera.php?op=vecchio3");
    addnav("Torna alla zona di scavo", "miniera.php?op=scavo");
    //if ($session['user']['superuser']>0)addnav("Scendi nell'Ade", "miniera.php?op=fondo");
}
if ($_GET['op']=="vecchio") {
    page_header("Il vecchio alla scrivania");
    output("`3Il vecchio barbuto ti guarda sospettoso, poi ti dice\"`6mmm .... per 10 pezzi d'oro ti dico quanto sei bravo a fare questo mestiere ?`3\"`n");
    addnav("Paga 10 pezzi d'oro", "miniera.php?op=vecchio_bravo");
    addnav("Torna alla zona di scavo", "miniera.php?op=luce");
}
if ($_GET['op']=="vecchio_bravo") {
    if ($session['user']['gold'] < 10) {
        output("Non hai abbastanza oro.`n");
        addnav("Lascia il vecchio", "miniera.php?op=luce");
    } else {
        //output("`3Il vecchio alla scrivania!`3`n");
        output("`3Il vecchio barbuto dice \"`6I tuoi muscoli mi dicono che la tua abilità è : ".intval($session['user']['minatore']) ." `3\"`n");
        addnav("Lascia il vecchio", "miniera.php?op=luce");
        $session['user']['gold']-=10;
    }
}
if ($_GET['op']=="vecchio2") {
    page_header("Il vecchio gemello!!");
    output("`3Il vecchio barbuto ti guarda sospettoso, poi ti dice\"`6mmm .... per 10 pezzi d'oro ti dico quanto sei bravo a fare questo mestiere ?`3\"`n");
    addnav("Paga 10 pezzi d'oro", "miniera.php?op=vecchio_bravo2");
    addnav("Torna alla zona di scavo", "miniera.php?op=buio");
}
if ($_GET['op']=="vecchio_bravo2") {
    if ($session['user']['gold'] < 10) {
        output("Non hai abbastanza oro.`n");
        addnav("Lascia il vecchio", "miniera.php?op=buio");
    } else {
        //output("`3Il vecchio alla scrivania!`3`n");
        output("`3Il vecchio barbuto dice \"`6I tuoi muscoli mi dicono che la tua abilità è : ".intval($session['user']['minatore']) ." `3\"`n");
        addnav("Lascia il vecchio", "miniera.php?op=buio");
        $session['user']['gold']-=10;
    }
}
if ($_GET['op']=="vecchio3") {
    page_header("Il vecchio gemello?!?!?");
    output("`3Il vecchio barbuto ti guarda con aria stanca, poi ti dice\"`6mmm .... per 10 pezzi d'oro ti dico quanto sei bravo a fare questo mestiere ?`3\"`n");
    output("`3A fianco del vecchio vedi quello che sembra un pozzo abbandonato e chiedi al vecchio informazioni.`n");
    output("Lui ti guarda e poi con voce stanca dice\"`6Ahh, quello`3\" e indica il pozzo con l'indice. \"`6Quello è il famoso pozzo `^Rickenbauer`3\"`n");
    output("Fa una pausa come ricordando questo tizio, chiunque egli fosse, poi riprende \"`6Ha preso il suo nome da quando Rickenbauer non è più riemerso ");
    output("da li sotto. Un masso enorme ha ostruito l'ingresso e nessuno è più stato in grado di spostarlo. È un'impresa che un solo minatore ");
    output("non può affrontare, e ci vuole l'azione combinata di più minatori per riaprire l'accesso alla galleria. Ma se vuoi il mio parere, lascerei stare, ");
    output("girano strane voci su ciò che si cela dietro quella pietra.`3\"`n`n");
    output("Sei indeciso se dar credito alle notizie del vecchio o ritenere tutto un'enorme frottola. Il pozzo è di fronte a te puoi scendere per verificare ");
    output("di persona se quello che racconta il vecchio è verità o no.");
    addnav("Scendi nel Pozzo", "miniera.php?op=fondo");
    addnav("Paga 10 pezzi d'oro", "miniera.php?op=vecchio_bravo3");
    addnav("Torna alla zona di scavo", "miniera.php?op=scendi");
}
if ($_GET['op']=="vecchio_bravo3") {
    if ($session['user']['gold'] < 10) {
        output("Non hai abbastanza oro.`n");
        addnav("Lascia il vecchio", "miniera.php?op=scendi");
    } else {
        //output("`3Il vecchio alla scrivania!`3`n");
        output("`3Il vecchio barbuto dice \"`6I tuoi muscoli mi dicono che la tua abilità è : ".intval($session['user']['minatore']) ." `3\"`n");
        addnav("Lascia il vecchio", "miniera.php?op=scendi");
        $session['user']['gold']-=10;
    }
}
if ($_GET['op']=="fondo") {
    page_header("Il pozzo");
    output("`3Il vecchio barbuto ti guarda e scuote la testa borbottando qualcosa su fantasmi e mostri terribili mentre ti infili nello stretto pozzo.`n");
    output("Utilizzi una scala fatiscente di metallo arrugginito ed inizi la discesa in profondità!`n");
    addnav("Sali", "miniera.php?op=scendi");
    addnav("Scendi", "miniera.php?op=giu");
}

if ($_GET['op']=="giu") {
    $session['user']['dove_sei'] = 0;
    page_header("Il pozzo");
    output("`3Scendi oramai da diversi minuti. La luce è sempre più debole e l'aria inizia ad essere calda ed irrespirabile! Non sei più tanto sicuro di voler continuare nell'impresa.`n");
    // exca qui metti un incontro casuale con combattimento di un qualche mostro
    $caso=rand(1,50);
    if ($caso == 10 AND $_GET['op1']!="fatto"){
       output("`4L'ossigeno nell'aria è proprio al limite della sopravvivenza, e la tua mente inizia ad ottenebrarsi... all'improvviso vedi spuntare dalle pareti ");
       output("del pozzo dei `8Vermi Giganti`4, sai che sono allucinazioni provocate dalla mancanza di ossigeno, ma sei obbligato a batterti!!`n`n");
       $dkb = round($session['user']['dragonkills']*.1);
       $badguy = array("creaturename"=>"`(Vermi Giganti`0"
       ,"creaturelevel"=>1
       ,"creatureweapon"=>"`8Bava Corrosiva`0"
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
       $session['bufflist']['miniera'] = array(
       "startmsg"=>"`n`(I Vermi Giganti ti circondano e ti ricoprono con la loro bava corrosiva!`n`n",
       "name"=>"`(Vermi Giganti`0",
       "rounds"=>15,
       "wearoff"=>"I Vermi hanno esaurito la bava",
       "minioncount"=>$session['user']['level'],
       "mingoodguydamage"=>1,
       "maxgoodguydamage"=>2+$dkb,
       "effectmsg"=>"Un verme gigante ti ricopre di bava causandoti {damage} punti danno.",
       "effectnodmgmsg"=>"Un verme gigante ti riversa addosso la sua bava ma tu ti sposti rapidamente e ti MANCA.",
       "activate"=>"roundstart",
       );
       addnav("Combatti","miniera.php?op=fight");
       addnav("Scappa","miniera.php?op=run");
    }else{
       addnav("Risali", "miniera.php?op=scendi");
       addnav("Scendi", "miniera.php?op=fine");
    }
}
if ($_GET['op']=="fine") {
    page_header("Il Masso Enorme");
    output("`3Sei arrivato in fondo al pozzo, 10 centimetri d'acqua coprono il pavimento, ed un masso enorme ostruisce l'unico tunnel che ");
    output("si dirama da questa grotta!`nDovrai metterci tutto il tuo impegno, coordinato con quello di altri minatori per poterlo smuovere ");
    output("e aprire il passaggio per accedere al tunnel.`n`nCosa vuoi fare?");
    addnav("Torna su", "miniera.php?op=giu&op1=fatto");
    addnav("Sposta il Macigno", "miniera.php?op=solleva");
}
if ($_GET['op']=="solleva") {
    $session['user']['dove_sei'] = 100;
    $sqlpic = "SELECT acctid FROM accounts WHERE dove_sei=100";
    $resultpic = db_query($sqlpic) or die(db_error(LINK));
    $player_solleva = db_num_rows($resultpic);
    page_header("L'Enorme Masso!");
    $sqlpic2="SELECT COUNT(*) AS picconatori, a.oggetto, a.zaino, o.id_oggetti, o.nome
              FROM accounts a, oggetti o
              WHERE o.nome = 'Piccone da minatore'
              AND (a.oggetto=o.id_oggetti OR a.zaino=o.id_oggetti)
              AND superuser = 0
              GROUP BY o.nome";
    $resultpic2 = db_query($sqlpic2) or die(db_error(LINK));
    $rowpic2 = db_fetch_assoc($resultpic2);
    $player_picconatori = $rowpic2['picconatori'];
    $player_necessari = round($player_picconatori*.2);
    if($session['user']['superuser']>0){
        output("`#Player impegnati in sollevamento : $player_solleva`n");
        output("`@Player che possiedono piccone    : $player_picconatori`n");
        output("`^Player necessari spostare masso  : $player_necessari`n");
    }
    if($session['user']['superuser']>0)addnav("`^Entra nel Tunnel (Admin)", "miniera.php?op=lavora4");
    if($player_solleva>=$player_necessari){
        output("`3Altri minatori sono arrivati nella zona d'accesso ostruita dall'enorme masso, e cercate di coordinarvi ");
        output("tutti assieme per liberare l'ingresso del tunnel. Spingete come dei forsennati, aiutandovi con i picconi, ");
        output("ed alla fine riuscite a creare un pertugio dove potervi infilare. Senti qualcuno che grida, \"`&SBRIGATEVI AD ENTRAREEEE!!`3\"`n`n`n");
        addnav("Entra nel Tunnel", "miniera.php?op=tunnelpozzo");
        addnav("Torna su", "miniera.php?op=giu");
    }else{
        output("`3Riesci a smuoverlo solo di qualche centimetro, non abbastanza per entrare, e pensi che se qualche altro minatore ti aiutasse forse ....`n`n");
        addnav("Riprova a spostare il Masso", "miniera.php?op=solleva");
        addnav("Torna su", "miniera.php?op=giu");
    }
    viewcommentary("Il tunnel bloccato", "sbuffa",15,5);
}
if ($_GET['op']=="tunnelpozzo") {
   output("`5Subito dopo esserti infilato nel buco, vedi gli altri minatori che si disperdono in tutte le direzioni, e non conoscendo ");
   output("questa zona della miniera, ti infili in un tunnel che ti pare promettente.`n`8L'acqua scorre sul terreno leggermente in ");
   output("pendenza, come ad indicarti la via. Vedi sparsi un po' dappertutto gli strumenti di scavo dei precedenti minatori, e ");
   output("ti chiedi cosa mai li possa aver indotti ad abbandonarli.`n");
   if (rand(1,100)>90 AND $_GET['op1']!="fatto"){
      output("`4Quando poi inizi a vedere gli scheletri sparsi sul pavimento, un leggero brivido scorre lungo la tua spina dorsale ");
      output("ed il terrore pervade la tua mente.`nRicordi le parole del vecchio, al riguardo di un minatore che ha dato il nome ");
      output("a questa sezione della miniera, e quando un piccone sfiora il tuo orecchio e si conficca sul terreno a pochi centimetri ");
      output("dai tuoi piedi, sai di aver commesso la sciocchezza più grossa della tua vita!!`n`^Rickenbauer`\$, o per lo meno quel che ");
      output("resta di lui, si staglia di fronte a te, dall'alto dei suoi 2 metri d'altezza, e ti fronteggia con un ghigno beffardo, ");
      output("reso ancor più orribile dal teschio biancastro che si intravede sotto le carni putrefatte del viso.`n");
      output("\"`7E così vorresti estrarre il mio `&argento`7 eh? Sappi che questa zona della miniera è MIAAA!!`\$\" grida con voce ");
      output("cavernosa, rendendolo ancor più minaccioso. Poi presegue \"`7Nessuno può venire a scavare in questa zona, NESSUNO !!!`\$\"`n");
      output("Dopodichè si prepara al combattimento, e dovrai batterlo per poter proseguire o andrai a far compagnia agli scheletri che ");
      output("hai visto in precedenza nella galleria.`n`n");
      $badguy = array("creaturename"=>"`^Rickenbauer il Minatore`0"
            ,"creaturelevel"=>2
            ,"creatureweapon"=>"`6Piccone da Minatore`0"
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
            addnav("Combatti","miniera.php?op=fight");
            addnav("Scappa","miniera.php?op=run");
   }else{
      output("`3Quando senti uno strano suono provenire da dietro una curva della galleria, stai quasi per decidere di voltarti e tornare da ");
      output("dove sei venuto, ma con l'ultima scintilla di coraggio fai gli ultimi passi e girato l'angolo ti si presenta davanti agli ");
      output("occhi uno spettacolo mozzafiato: un'ampia caverna con `7stalattiti`3 e `7stalagmiti`3 che la decorano, e soprattutto delle ");
      output("splendide venature `&argentee`3 nelle pareti, indice del metallo in esse contenuto.`n Decidi di non perdere tempo e, piccone ");
      output("alla mano, ti avvicini alla parete per iniziare l'estrazione.`n`n");
      addnav("Lavora", "miniera.php?op=lavora4");
   }
}
if ($_GET['op']=="lavora1") {
    $badguy=array();
    $hungerchance=rand(0,4);
    usurapiccone();
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
        if($session['user']['minatore']< 5)$session['user']['minatore']+=0.01;
        if($session['user']['minatore']< 10)$session['user']['minatore']+=0.01;
        output("`3Lavora!`3`n");
        output("`3Impugni il tuo piccone e colpisci la vena di carbone cercando di staccarne la maggior quantità possibile!`3`n");
        $forza=intval(($session['user']['attack']+$session['user']['defence'])/100);
        if($forza==0)$forza=1;
        //result è una percentuale il massimo fissato indica la massima possibilità di riuscita
        $result=$session['user']['minatore']+$forza;
        $cento=rand(1, 100);
        if($result >= $cento){
            output("`#Un buon colpo!`3`n");
            if (e_rand(1,5)==1 AND !zainoPieno($idplayer)) {
               output("`@Riesci anche a mettere parte del carbone estratto nel tuo zaino!`n`n");
               $sqli="INSERT INTO zaino (idoggetto, idplayer) VALUES ('3', '".$session['user']['acctid']."')";
               db_query($sqli) or die(db_error(LINK));
               debuglog("guadagna un pezzo di carbone lavorando in miniera");
            }
            savesetting("carbone",getsetting("carbone",0)+1);
            $sqldr="INSERT INTO miniera (acctid,data,materiale) VALUES ('$account',FROM_UNIXTIME(UNIX_TIMESTAMP()),'Carbone')";
            db_query($sqldr) or die(db_error(LINK));
            $fatica=rand(0,1);
            $session['user']['hitpoints']-=$fatica;
            if ($session['user']['hitpoints'] <=0) $session['user']['hitpoints']=1;
            $session['user']['minatore']+=0.01;
            $caso=rand(1, 100);
            if($caso==100){
                output("`#Dalla parete di roccia che hai colpito, affiorano delle gemme ... le conti e scopri ");
                output("che sono `^`b5`#`b!!!!`nQuesto si che è un colpo di fortuna!!`3`n");
                debuglog("trova 5 gemme nella miniera");
                $session['user']['gems']+=5;
                if ($session['user']['turni_mestiere']>0) {
                    addnav("Lavora", "miniera.php?op=lavora1");
                }
                addnav("Vai al tunnel", "miniera.php?op=zona_lavoro");
            }elseif($caso>=95 AND $caso< 100){
                output("`#Dalla parete di roccia che hai colpito emerge una gemma, che prontamente infili in tasca!`3`n");
                $session['user']['gems']+=1;
                debuglog("trova una gemma nella miniera");
                if ($session['user']['turni_mestiere']>0) {
                    addnav("Lavora", "miniera.php?op=lavora1");
                }
                addnav("Vai al tunnel", "miniera.php?op=zona_lavoro");
            }elseif($caso>=94 AND $caso< 95){
                output("`#Dal colpo che hai dato, si apre una spaccatura nel muro, ed una pergamena viene alla luce.`n");
                output("La esamini e scopri che è una ricetta, felice per il ritrovamento la metti nello zaino!`3`n");
                if (zainoPieno($idplayer)){
                    output ("Non hai posto per questa questa ricetta, il tuo zaino è pieno !");
                }else{
                    $sqldr="INSERT INTO zaino (idoggetto,idplayer) VALUES ('19','$account')";
                    db_query($sqldr) or die(db_error(LINK));
                    debuglog("trova ricetta 19 nella miniera");
                }
                if ($session['user']['turni_mestiere']>0) {
                    addnav("Lavora", "miniera.php?op=lavora1");
                }
                addnav("Vai al tunnel", "miniera.php?op=zona_lavoro");
            }elseif($caso>=92 AND $caso< 94){
                output("`#AAAAARGGHHH!! Hai aperto una tana di un Ragno Gigante!`3`n");
                $badguy = array("creaturename"=>"`8Ragno Gigante`0"
                ,"creaturelevel"=>1
                ,"creatureweapon"=>"`(Morso Paralizzante`0"
                ,"creatureattack"=>2
                ,"creaturedefense"=>3
                ,"creaturehealth"=>3
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
                addnav("Combatti","miniera.php?op=fight");
                addnav("Scappa","miniera.php?op=run");

            }elseif($caso==91){
                output("`4Purtroppo dalla parete fuoriesce del gas, ed il colpo di piccone successivo provoca una ");
                output("scintilla, che innesca il gas con susseguente esplosione!!!`nLa volta della miniera ");
                output("ti crolla addosso e muori solo e tra dolori atroci!!!`n");
                output("`\$Hai perso il 5% della tua esperienza !!!`n");
                addnews("`%".$session['user']['name']."`5 è morto sotto un crollo, mentre lavorava in miniera!!");
                debuglog("è morto sotto un crollo in miniera e ha perso ".$session['user']['gold']." oro");
                $session['user']['experience'] *= 0.95;
                $session['user']['alive'] = false;
                $session['user']['hitpoints'] = 0;
	            if($session['user']['assicurazione']==1) $session['user']['goldinbank']+=$session['user']['gold'];
                $session['user']['gold'] = 0;
                addnav("Notizie Giornaliere","news.php");
            }elseif($caso>=88 AND $caso< 91){
                output("`#AAAAARGGHHH!! Hai aperto una tana di un Scorpione Gigante!`3`n");
                $badguy = array("creaturename"=>"`4Scorpione Gigante`0"
                ,"creaturelevel"=>0
                ,"creatureweapon"=>"`\$Pungiglione Mortale`0"
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
                addnav("Combatti","miniera.php?op=fight");
                addnav("Scappa","miniera.php?op=run");
            }else{
                if ($session['user']['turni_mestiere']>0) {
                    addnav("Lavora", "miniera.php?op=lavora1");
                }
                addnav("Vai al tunnel", "miniera.php?op=zona_lavoro");
            }
        }else{
            output("`vImpegnati di più mettici un po' di energia in quei colpi!`3`n");
            $fatica=rand(0, (intval($session['user']['level']/5)+1));
            $session['user']['hitpoints']-=$fatica;
            if ($session['user']['hitpoints'] <= 0) $session['user']['hitpoints']=1;
            $caso=rand(1, 100);
            if($caso>=86 AND $caso< 89){
                output("`#AAAAARGGHHH!! Hai aperto una tana di un Ratto di Miniera!`3`n");
                $badguy = array("creaturename"=>"`8Ratto di Miniera`0"
                ,"creaturelevel"=>0
                ,"creatureweapon"=>"`(Morso Pestilenziale`0"
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
                addnav("Combatti","miniera.php?op=fight");
                addnav("Scappa","miniera.php?op=run");
            }elseif($caso==85){
                output("La miniera è crollata e sei morto !!!`n");
                output("Hai perso il 5% della tua esperienza !!!`n");
                addnews("`%".$session['user']['name']."`5 è morto sotto un crollo, mentre lavorava in miniera!!");
                $session['user']['experience']*=0.95;
                $session['user']['alive']=false;
                $session['user']['hitpoints']=0;
                debuglog("è morto sotto un crollo mentre lavorava in miniera e ha perso ".$session['user']['gold']." oro");
	            if($session['user']['assicurazione']==1) $session['user']['goldinbank']+=$session['user']['gold'];
                $session['user']['gold']=0;
                addnav("Notizie Giornaliere", "news.php");
            }elseif($caso>=83 AND $caso< 85){
                output("`#AAAAARGGHHH!! Hai aperto una tana di Scarabei Stercorari!`3`n");
                $dkb = round($session['user']['dragonkills']*.1);
                $badguy = array("creaturename"=>"`5Branco di Scarabei Stercorari`0"
                ,"creaturelevel"=>0
                ,"creatureweapon"=>"`%Corna da Combattimento`0"
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
                $session['bufflist']['miniera'] = array(
                "startmsg"=>"`n`%Gli scarabei ti circondano e ti pungono con le loro corna!`n`n",
                "name"=>"`%Corna da combattimento`0",
                "rounds"=>10,
                "wearoff"=>"Gli scarabei sono esausti.",
                "minioncount"=>$session['user']['level'],
                "mingoodguydamage"=>0,
                "maxgoodguydamage"=>1+$dkb,
                "effectmsg"=>"Uno scarabeo ti colpisce per {damage} punti danno.",
                "effectnodmgmsg"=>"Uno scarabeo cerca di colpirti con le sue corna ma ti MANCA.",
                "activate"=>"roundstart",
                );
                addnav("Combatti","miniera.php?op=fight");
                addnav("Scappa","miniera.php?op=run");
            }else{
                if ($session['user']['turni_mestiere']>0) {
                    addnav("Lavora", "miniera.php?op=lavora1");
                }
                addnav("Vai al tunnel", "miniera.php?op=zona_lavoro");
            }
        }
    if($session['user']['superuser']>0)output("`n$caso");
    }
}
if ($_GET['op']=="lavora2") {
    $badguy=array();
    $hungerchance=rand(0,4);
    usurapiccone();
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
        //if($session['user']['minatore']< 5)$session['user']['minatore']+=0.01;
        if($session['user']['minatore']< 10)$session['user']['minatore']+=0.01;
        output("`3Lavora!`3`n");
        output("`3Impugni il tuo piccone e colpisci la vena di ferro cercando di staccarne la maggior quantità possibile!`3`n");
        $forza=intval(($session['user']['attack']+$session['user']['defence'])/100);
        if($forza==0)$forza=1;
        //result è una percentuale il massimo fissato indica la massima possibilità di riuscita
        $result=$session['user']['minatore']+$forza;
        if($result>80)$result=80;
        $cento=rand(1, 100);
        if($result >= $cento){
            if (e_rand(1,5)==1 AND !zainoPieno($idplayer)) {
               output("`@Riesci anche a mettere parte del ferro estratto nel tuo zaino!`n`n");
               $sqli="INSERT INTO zaino (idoggetto, idplayer) VALUES ('1', '".$session['user']['acctid']."')";
               db_query($sqli) or die(db_error(LINK));
               debuglog("guadagna una scaglia di ferro lavorando in miniera");
            }
            savesetting("scagliemetallo",getsetting("scagliemetallo",0)+1);
            $sqldr="INSERT INTO miniera (acctid,data,materiale) VALUES ('$account',FROM_UNIXTIME(UNIX_TIMESTAMP()),'Metallo')";
            db_query($sqldr) or die(db_error(LINK));
            $fatica=rand(0,1);
            $session['user']['hitpoints']-=$fatica;
            if ($session['user']['hitpoints'] <=0) $session['user']['hitpoints']=1;
            output("`#Un buon colpo!`3`n");
            $session['user']['minatore']+=0.01;
            $caso=rand(1, 200);
            if($caso==200){
                output("`#Dalla parete di roccia che hai colpito, affiorano delle gemme ... le conti e scopri ");
                output("che sono `^`b8`#`b!!!!`nQuesto si che è un colpo di fortuna!!`3`n");
                debuglog("trova 8 gemme nella miniera");
                $session['user']['gems']+=8;
                if ($session['user']['turni_mestiere']>0) {
                    addnav("Lavora", "miniera.php?op=lavora2");
                }
                addnav("Vai al tunnel", "miniera.php?op=zona_lavoro");
            }elseif($caso>=195 AND $caso< 200){
                output("`#Dalla parete di roccia che hai colpito emerge una gemma, che prontamente infili in tasca!`3`n");
                $session['user']['gems']+=1;
                debuglog("trova una gemma nella miniera");
                if ($session['user']['turni_mestiere']>0) {
                    addnav("Lavora", "miniera.php?op=lavora2");
                }
                addnav("Vai al tunnel", "miniera.php?op=zona_lavoro");
            }elseif($caso>=190 AND $caso< 195){
                $gold = $session['user']['level'] * 37;
                output("`#Dalla parete di roccia che hai colpito emerge una vena d'oro! Continui a picconare ed al termine del ");
                output("tuo lavoro ti ritrovi con  $gold pezzi d'oro!`3`n");
                $session['user']['gold']+=$gold;
                debuglog("trova $gold pezzi d'oro nella miniera");
                if ($session['user']['turni_mestiere']>0) {
                    addnav("Lavora", "miniera.php?op=lavora2");
                }
                addnav("Vai al tunnel", "miniera.php?op=zona_lavoro");
            }elseif($caso>=188 AND $caso<190){
                output("`#Dal colpo che hai dato, si apre una spaccatura nel muro, ed una pergamena viene alla luce.`n");
                output("La esamini e scopri che è una ricetta, felice per il ritrovamento la metti nello zaino!`3`n");
                if (zainoPieno($idplayer)){
                    output ("Non hai posto per questa questa ricetta, il tuo zaino è pieno !");
                }else{
                    $sqldr="INSERT INTO zaino (idoggetto,idplayer) VALUES ('19','$account')";
                    db_query($sqldr) or die(db_error(LINK));
                    debuglog("trova ricetta 19 nella miniera");
                }
                if ($session['user']['turni_mestiere']>0) {
                    addnav("Lavora", "miniera.php?op=lavora2");
                }
                addnav("Vai al tunnel", "miniera.php?op=zona_lavoro");
            }elseif($caso>=184 AND $caso< 188){
                output("`#AAAAARGGHHH!! Hai aperto la tana di una Tarantola Maculata!`3`n");
                $badguy = array("creaturename"=>"`9Tarantola Maculata`0"
                ,"creaturelevel"=>1
                ,"creatureweapon"=>"`(Morso Atrofizzante`0"
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
                addnav("Combatti","miniera.php?op=fight");
                addnav("Scappa","miniera.php?op=run");

            }elseif($caso>=180 AND $caso< 184){
                output("`4Purtroppo dalla parete fuoriesce del gas, ed il colpo di piccone successivo provoca una ");
                output("scintilla, che innesca il gas con susseguente esplosione!!!`nLa volta della miniera ");
                output("ti crolla addosso e muori solo e tra dolori atroci!!!`n");
                output("`\$Hai perso il 5% della tua esperienza !!!`n");
                addnews("`%".$session['user']['name']."`5 è morto sotto un crollo, mentre lavorava in miniera!!");
                debuglog("è morto sotto un crollo in miniera e ha perso ".$session['user']['gold']." oro");
                $session['user']['experience'] *= 0.95;
                $session['user']['alive'] = false;
                $session['user']['hitpoints'] = 0;
	            if($session['user']['assicurazione']==1) $session['user']['goldinbank']+=$session['user']['gold'];
                $session['user']['gold'] = 0;
                addnav("Notizie Giornaliere","news.php");
            }elseif($caso>=178 AND $caso< 180){
                output("`#AAAAARGGHHH!! Hai aperto una tana di Formiche Rosse!`3`n");
                $dkb = round($session['user']['dragonkills']*.15);
                $badguy = array("creaturename"=>"`\$Formiche Rosse`0"
                ,"creaturelevel"=>0
                ,"creatureweapon"=>"`\$Tenaglie Mortali`0"
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
                $session['bufflist']['miniera'] = array(
                "startmsg"=>"`n`%Le formiche ti circondano e ti mordono con le loro tenaglie!`n`n",
                "name"=>"`%Tenaglie Mortali`0",
                "rounds"=>10,
                "wearoff"=>"le Formiche sono esauste.",
                "minioncount"=>$session['user']['level'],
                "mingoodguydamage"=>0,
                "maxgoodguydamage"=>2+$dkb,
                "effectmsg"=>"Una formica ti morde con le sue tenaglie per {damage} punti danno.",
                "effectnodmgmsg"=>"Una formica cerca di morderti con le sue tenaglie ma ti MANCA.",
                "activate"=>"roundstart",
                );
                addnav("Combatti","miniera.php?op=fight");
                addnav("Scappa","miniera.php?op=run");
            }elseif($caso>=169 AND $caso< 178){
                output("`3Colpendo la volta della galleria, senti uno rombo sordo provenire dalla volta stessa. ");
                output("Comprendi che sta per crollare tutto, ed abbandoni velocemente la zona per evitare di rimanere sepolto sotto ");
                output("la frana.`n Nella fuga precipitosa smarrisci il senso dell'orientamento e perdi 5 turni di scavo.`n`n");
                $session['user']['turni_mestiere'] -= 5;
                if ($session['user']['turni_mestiere'] < 0) $session['user']['turni_mestiere'] = 0;
                if ($session['user']['turni_mestiere']>0) {
                    addnav("Lavora", "miniera.php?op=lavora2");
                }
                addnav("Vai al tunnel", "miniera.php?op=zona_lavoro");
            }elseif($caso>=160 AND $caso<169){
                output("`n`3Noti solo ora nel tunnel una lucina flebile in lontananza e decidi di investigare.`n");
                output("Mentre ti avvicini alla luce vedi una strana costruzione e scopri che si tratta di un bagno ");
                output("pubblico. Chi mai lo avrà costruito? Probabilmente qualche minatore solitario che non voleva ");
                output("`iannaffiare`i la miniera o che non voleva rinunciare alla comodità di un vero bagno.`n");
                output("Chiunque sia stato, decidi di approfittare dell'opportunità e ti liberi di un peso superfluo.`n`n");
                $session['user']['bladder']=0;
                if ($session['user']['turni_mestiere']>0) {
                    addnav("Lavora", "miniera.php?op=lavora2");
                }
                addnav("Vai al tunnel", "miniera.php?op=zona_lavoro");
            }elseif($caso>=159 AND $caso<160){
                output("`#Pensi di aver trovato una buona vena, e dai fondo alle tue energia dando un colpo molto potente ");
                output("con il piccone. Purtroppo il colpo è stato veramente `bTROPPO`b potente!!`n`n");
                output("Il piccone ti si sbriciola tra le mani, e ti ritrovi con qualche scheggia di legno ed un ");
                output("ammasso informe di metallo.`n");
                $sqlogg = "SELECT nome FROM oggetti WHERE id_oggetti = ".$session['user']['oggetto'];
                $resultogg = db_query($sqlogg) or die(db_error(LINK));
                $rowogg = db_fetch_assoc($resultogg);
                if ($rowogg['nome'] == "Piccone da minatore"){
                    $sqlogg = "DELETE FROM oggetti WHERE id_oggetti = ".$session['user']['oggetto'];
                    $result = db_query($sqlogg) or die(db_error(LINK));
                    $session['user']['oggetto'] = 0;
                }else{
                    $sqlogg = "DELETE FROM oggetti WHERE id_oggetti = ".$session['user']['zaino'];
                    $result = db_query($sqlogg) or die(db_error(LINK));
                    $session['user']['zaino'] = 0;
                }
                addnav("Torna all'ingresso", "miniera.php");
            }else{
                if ($session['user']['turni_mestiere']>0) {
                    addnav("Lavora", "miniera.php?op=lavora2");
                }
                addnav("Vai al tunnel", "miniera.php?op=zona_lavoro");
            }

        }else{
            output("`vImpegnati di più mettici un po' di energia in quei colpi!`3`n");
            $fatica=rand(0, (intval($session['user']['level']/5)+1));
            $session['user']['hitpoints']-=$fatica;
            if ($session['user']['hitpoints'] <= 0) $session['user']['hitpoints']=1;
            $caso=rand(0, 100);
            if($caso>=96 AND $caso< 99){
                output("`#AAAAARGGHHH!! Hai aperto una tana di un Pantegana di Miniera!`3`n");
                $badguy = array("creaturename"=>"`(Pantegana di Miniera`0"
                ,"creaturelevel"=>0
                ,"creatureweapon"=>"`8Morso Rabbioso`0"
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
                addnav("Combatti","miniera.php?op=fight");
                addnav("Scappa","miniera.php?op=run");
            }elseif($caso>=93 AND $caso< 96){
                output("La miniera è crollata e sei morto !!!`n");
                output("Hai perso il 5% della tua esperienza !!!`n");
                addnews("`%".$session['user']['name']."`5 è morto sotto un crollo, mentre lavorava in miniera!!");
                $session['user']['experience']*=0.95;
				$session['user']['alive']=false;
				$session['user']['hitpoints']=0;
				debuglog("è morto sotto un crollo mentre lavorava in miniera e ha perso ".$session['user']['gold']." oro");
	            if($session['user']['assicurazione']==1) $session['user']['goldinbank']+=$session['user']['gold'];
                $session['user']['gold']=0;
                addnav("Notizie Giornaliere", "news.php");
            }elseif($caso>=90 AND $caso< 93){
                output("`#AAAAARGGHHH!! Hai aperto una tana di Cavallette Acrididi!`3`n");
                $dkb = round($session['user']['dragonkills']*.1);
                $badguy = array("creaturename"=>"`@Sciame di Cavallette Acrididi`0"
                ,"creaturelevel"=>0
                ,"creatureweapon"=>"`2Mandibole Voraci`0"
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
                $session['bufflist']['miniera'] = array(
                "startmsg"=>"`n`@Le cavallette ti circondano e ti mordono con le loro mandibole possenti!`n`n",
                "name"=>"`2Mandibole Possenti`0",
                "rounds"=>10,
                "wearoff"=>"le Cavallette sono sazie.",
                "minioncount"=>$session['user']['level'],
                "mingoodguydamage"=>0,
                "maxgoodguydamage"=>1+$dkb,
                "effectmsg"=>"Una cavalletta ti morde per {damage} punti danno.",
                "effectnodmgmsg"=>"Una cavalletta cerca di morderti con le sue mandibole ma ti MANCA.",
                "activate"=>"roundstart",
                );
                addnav("Combatti","miniera.php?op=fight");
                addnav("Scappa","miniera.php?op=run");
            }elseif($caso==0){
                output("`3Colpendo la volta della galleria, senti uno rombo sordo provenire dalla volta stessa. ");
                output("Comprendi che sta per crollare tutto, ed abbandoni la zona il più velocemente possibile ");
                output(" per evitare di rimanere sepolto sotto la frana.`n Fuggendo dai un'ultima occhiata fugace ");
                output("alle tue spalle, e vedi un nano che assomiglia in maniera impressionante a Brax che raccoglie ");
                output("il `b`&TUO`b piccone`3, che nella fuga precipitosa ti sei dimenticato nella zona di scavo.`nProprio ");
                output("mentre stai per inseguire Brax o chiunque sia quel nano, la volta della galleria si abbatte tra ");
                output("e il nano, obbligandoti a rinunciare al tuo proposito di inseguimento.`n`n");
                $sqlogg = "SELECT * FROM oggetti WHERE id_oggetti = ".$session['user']['oggetto'];
                $resultogg = db_query($sqlogg) or die(db_error(LINK));
                $rowogg = db_fetch_assoc($resultogg);
                if ($rowogg['nome'] == "Piccone da minatore"){
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
                addnav("Torna all'ingresso", "miniera.php");
            }else{
                if ($session['user']['turni_mestiere']>0) {
                    addnav("Lavora", "miniera.php?op=lavora2");
                }
                addnav("Vai al tunnel", "miniera.php?op=zona_lavoro");
            }
        }
    }
}
if ($_GET['op']=="lavora3") {
    $badguy=array();
    $hungerchance=rand(0,4);
    usurapiccone();
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
        //if($session['user']['minatore']< 5)$session['user']['minatore']+=0.01;
        //if($session['user']['minatore']< 10)$session['user']['minatore']+=0.01;
        output("`3Lavora!`3`n");
        output("`3Impugni il tuo piccone e colpisci la vena di rame cercando di staccarne la maggior quantità possibile!`3`n");
        $forza=intval(($session['user']['attack']+$session['user']['defence'])/100);
        if($forza==0)$forza=1;
        //result è una percentuale il massimo fissato indica la massima possibilità di riuscita
        $result=$session['user']['minatore'];
        if($result>50)$result=50;
        $cento=rand(1, 100);
        if($result >= $cento){
            if (e_rand(1,5)==1 AND !zainoPieno($idplayer)) {
               output("`@Riesci anche a mettere parte del rame estratto nel tuo zaino!`n`n");
               $sqli="INSERT INTO zaino (idoggetto, idplayer) VALUES ('2', '".$session['user']['acctid']."')";
               db_query($sqli) or die(db_error(LINK));
               debuglog("guadagna una scaglia di rame lavorando in miniera");
            }
            savesetting("scaglierame",getsetting("scaglierame",0)+1);
            $sqldr="INSERT INTO miniera (acctid,data,materiale) VALUES ('$account',FROM_UNIXTIME(UNIX_TIMESTAMP()),'Rame')";
            db_query($sqldr) or die(db_error(LINK));
            $fatica=rand(0,1);
            $session['user']['hitpoints']-=$fatica;
            if ($session['user']['hitpoints'] <=0) $session['user']['hitpoints']=1;
            output("`#Un buon colpo!`3`n");
            $session['user']['minatore']+=0.01;
            $caso=rand(1, 200);
            if($caso==200){
                output("`#Dalla parete di roccia che hai colpito, affiorano delle gemme ... le conti e scopri ");
                output("che sono `^`b10`#`b!!!!`nQuesto si che è un colpo di fortuna!!`3`n");
                debuglog("trova 10 gemme nella miniera");
                $session['user']['gems']+=10;
                if ($session['user']['turni_mestiere']>0) {
                    addnav("Lavora", "miniera.php?op=lavora3");
                }
                addnav("Vai al tunnel", "miniera.php?op=zona_lavoro");
            }elseif($caso>=197 AND $caso< 200){
                output("`#Dalla parete di roccia che hai colpito emerge una gemma, che prontamente infili in tasca!`3`n");
                $session['user']['gems']+=1;
                debuglog("trova una gemma nella miniera");
                if ($session['user']['turni_mestiere']>0) {
                    addnav("Lavora", "miniera.php?op=lavora3");
                }
                addnav("Vai al tunnel", "miniera.php?op=zona_lavoro");
            }elseif($caso>=190 AND $caso< 197){
                $gold = $session['user']['level'] * 40;
                output("`#Dalla parete di roccia che hai colpito emerge una vena d'oro! Continui a picconare ed al termine del ");
                output("tuo lavoro ti ritrovi con  $gold pezzi d'oro!`3`n");
                $session['user']['gold']+=$gold;
                debuglog("trova $gold pezzi d'oro nella miniera");
                if ($session['user']['turni_mestiere']>0) {
                    addnav("Lavora", "miniera.php?op=lavora3");
                }
                addnav("Vai al tunnel", "miniera.php?op=zona_lavoro");
            }elseif($caso>=189 AND $caso<190){
                output("`#Dal colpo che hai dato, si apre una spaccatura nel muro, ed una pergamena viene alla luce.`n");
                output("La esamini e scopri che è una ricetta, felice per il ritrovamento la metti nello zaino!`3`n");
                if (zainoPieno($idplayer)){
                    output ("Non hai posto per questa questa ricetta, il tuo zaino è pieno !");
                }else{
                    $sqldr="INSERT INTO zaino (idoggetto,idplayer) VALUES ('19','$account')";
                    db_query($sqldr) or die(db_error(LINK));
                    debuglog("trova ricetta 19 nella miniera");
                }
                if ($session['user']['turni_mestiere']>0) {
                    addnav("Lavora", "miniera.php?op=lavora3");
                }
                addnav("Vai al tunnel", "miniera.php?op=zona_lavoro");
            }elseif($caso>=186 AND $caso< 188){
                output("`#AAAAARGGHHH!! Hai scoperchiato la tomba di un Minatore morto. Il suo fantasma di attacca!`3`n");
                $badguy = array("creaturename"=>"`&Minatore Fantasma`0"
                ,"creaturelevel"=>3
                ,"creatureweapon"=>"`7Ululati dai Brivido`0"
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
                addnav("Combatti","miniera.php?op=fight");
                addnav("Scappa","miniera.php?op=run");

            }elseif($caso>=180 AND $caso< 186){
                output("`4Purtroppo dalla parete fuoriesce del gas, ed il colpo di piccone successivo provoca una ");
                output("scintilla, che innesca il gas con susseguente esplosione!!!`nLa volta della miniera ");
                output("ti crolla addosso e muori solo e tra dolori atroci!!!`n");
                output("`\$Hai perso il 5% della tua esperienza !!!`n");
                addnews("`%".$session['user']['name']."`5 è morto sotto un crollo, mentre lavorava in miniera!!");
                debuglog("è morto sotto un crollo in miniera e ha perso ".$session['user']['gold']." oro");
                $session['user']['experience'] *= 0.95;
                $session['user']['alive'] = false;
                $session['user']['hitpoints'] = 0;
	            if($session['user']['assicurazione']==1) $session['user']['goldinbank']+=$session['user']['gold'];
                $session['user']['gold'] = 0;
                addnav("Notizie Giornaliere","news.php");
            }elseif($caso>=178 AND $caso< 180){
                output("`#AAAAARGGHHH!! Hai riportato alla luce uno Zombie che era stato rinchiuso quaggiù!`3`n");
                $dkb = round($session['user']['dragonkills']*.15);
                $badguy = array("creaturename"=>"`\$Zombie`0"
                ,"creaturelevel"=>2
                ,"creatureweapon"=>"`\$Morso Infettante`0"
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
                $session['bufflist']['miniera'] = array(
                "startmsg"=>"`n`%Il morso dello zombie ti ha infettato!`n`n",
                "name"=>"`%Morso Infettante`0",
                "rounds"=>10,
                "wearoff"=>"L'infezione è stata debellata.",
                "minioncount"=>1,
                "mingoodguydamage"=>0,
                "maxgoodguydamage"=>2+$dkb+$session['user']['level'],
                "effectmsg"=>"L'infezione si propaga per il corpo causando la perdita di {damage} HitPoint.",
                "effectnodmgmsg"=>"Le tue difese immunitarie riescono a contrastare l'infezione.",
                "activate"=>"roundstart",
                );
                addnav("Combatti","miniera.php?op=fight");
                addnav("Scappa","miniera.php?op=run");
            }elseif($caso>=169 AND $caso< 178){
                output("`3Colpendo la volta della galleria, senti uno rombo sordo provenire dalla volta stessa. ");
                output("Comprendi che sta per crollare tutto, ed abbandoni velocemente la zona per evitare di rimanere sepolto sotto ");
                output("la frana.`n Nella fuga precipitosa smarrisci il senso dell'orientamento e perdi 5 turni di scavo.`n`n");
                $session['user']['turni_mestiere'] -= 5;
                if ($session['user']['turni_mestiere'] < 0) $session['user']['turni_mestiere'] = 0;
                if ($session['user']['turni_mestiere']>0) {
                    addnav("Lavora", "miniera.php?op=lavora3");
                }
                addnav("Vai al tunnel", "miniera.php?op=zona_lavoro");
            }elseif($caso>=165 AND $caso<169){
                output("`n`3Noti solo ora nel tunnel una lucina flebile in lontananza e decidi di investigare.`n");
                output("Mentre ti avvicini alla luce vedi una strana costruzione e scopri che si tratta di un bagno ");
                output("pubblico. Chi mai lo avrà costruito? Probabilmente qualche minatore solitario che non voleva ");
                output("`iannaffiare`i la miniera o che non voleva rinunciare alla comodità di un vero bagno.`n");
                output("Chiunque sia stato, decidi di approfittare dell'opportunità e ti liberi di un peso superfluo.`n`n");
                $session['user']['bladder']=0;
                if ($session['user']['turni_mestiere']>0) {
                    addnav("Lavora", "miniera.php?op=lavora3");
                }
                addnav("Vai al tunnel", "miniera.php?op=zona_lavoro");
            }elseif($caso>=159 AND $caso<165){
                output("`#Pensi di aver trovato una buona vena, e dai fondo alle tue energia dando un colpo molto potente ");
                output("con il piccone. Purtroppo il colpo è stato veramente `bTROPPO`b potente!!`n`n");
                output("Il piccone ti si sbriciola tra le mani, e ti ritrovi con qualche scheggia di legno ed un ");
                output("ammasso informe di metallo.`n");
                $sqlogg = "SELECT nome FROM oggetti WHERE id_oggetti = ".$session['user']['oggetto'];
                $resultogg = db_query($sqlogg) or die(db_error(LINK));
                $rowogg = db_fetch_assoc($resultogg);
                if ($rowogg['nome'] == "Piccone da minatore"){
                    $sqlogg = "DELETE FROM oggetti WHERE id_oggetti = ".$session['user']['oggetto'];
                    $result = db_query($sqlogg) or die(db_error(LINK));
                    $session['user']['oggetto'] = 0;
                }else{
                    $sqlogg = "DELETE FROM oggetti WHERE id_oggetti = ".$session['user']['zaino'];
                    $result = db_query($sqlogg) or die(db_error(LINK));
                    $session['user']['zaino'] = 0;
                }
                addnav("Torna all'ingresso", "miniera.php");
            }elseif($caso>=155 AND $caso<159){
                output("`n`3Noti solo ora nel tunnel una lucina flebile in lontananza e decidi di investigare.`n");
                output("Mentre ti avvicini alla luce vedi una strana costruzione e scopri che si tratta di un bagno ");
                output("pubblico. Chi mai lo avrà costruito? Probabilmente qualche minatore solitario che non voleva ");
                output("`iannaffiare`i la miniera o che non voleva rinunciare alla comodità di un vero bagno.`n");
                output("Chiunque sia stato, decidi di approfittare dell'opportunità e ti liberi di un peso superfluo.`n");
                output("Durante la minzione noti qualcosa che brilla sul pavimento. Con estrema cautela ti chini e scopri ");
                output("che è una `&gemma`3, persa da qualche altro minatore!!`n`n");
                $session['user']['gems']++;
                debuglog("trova una gemma al gabinetto della miniera");
                $session['user']['bladder']=0;
                if ($session['user']['turni_mestiere']>0) {
                    addnav("Lavora", "miniera.php?op=lavora3");
                }
                addnav("Vai al tunnel", "miniera.php?op=zona_lavoro");
            }elseif($caso>=151 AND $caso<155){
                output("`n`3Noti solo ora nel tunnel una lucina flebile in lontananza e decidi di investigare.`n");
                output("Mentre ti avvicini alla luce vedi una strana costruzione e scopri che si tratta di un bagno ");
                output("pubblico. Chi mai lo avrà costruito? Probabilmente qualche minatore solitario che non voleva ");
                output("`iannaffiare`i la miniera o che non voleva rinunciare alla comodità di un vero bagno.`n");
                output("Chiunque sia stato, decidi di approfittare dell'opportunità e ti liberi di un peso superfluo.`n");
                output("Durante la minzione purtroppo ti cade una `&gemma`3 dalla tasca, ed il pavimento è troppo sporco per ");
                output("rischiare di riprenderla ... chissà quale terribile malattia contrarresti!!`n`n");
                $session['user']['gems']--;
                debuglog("perde una gemma al gabinetto della miniera");
                $session['user']['bladder']=0;
                if ($session['user']['turni_mestiere']>0) {
                    addnav("Lavora", "miniera.php?op=lavora3");
                }
                addnav("Vai al tunnel", "miniera.php?op=zona_lavoro");
            }elseif($caso>=147 AND $caso<151){
                output("`n`3Noti solo ora nel tunnel una lucina flebile in lontananza e decidi di investigare.`n");
                output("Mentre ti avvicini alla luce vedi una strana costruzione e scopri che si tratta di una doccia ");
                output("pubblica. Chi mai l'avrà costruita? Probabilmente qualche minatore solitario che non voleva ");
                output("rischiare di sporcarsi troppo in miniera e che non voleva tornare al villaggio per togliersi lo sporco di dosso.`n");
                output("Chiunque sia stato, decidi di approfittare dell'opportunità e ti dai una bella lavata.`n");
                output("Al termine ti senti quasi pulito, purtroppo la mancanza di sapone non ti ha permesso di lavarti ");
                output("come avresti voluto ... ma come si dice, a caval donato non si guarda in bocca.`n`n");
                $clean= intval(rand(.3,.7)*$session['user']['clean']);
                $session['user']['clean']-=$clean;
                if ($session['user']['turni_mestiere']>0) {
                    addnav("Lavora", "miniera.php?op=lavora3");
                }
                addnav("Vai al tunnel", "miniera.php?op=zona_lavoro");
            }elseif($caso>=144 AND $caso<147){
                if ($session['user']['potion']<5){
                    output("`2Dopo l'ultimo colpo di piccone dato alla roccia, qualcosa affiora. Pulisci la parete e scorgi ");
                    output("una fiala di colore verde.  `@Hai trovato una Pozione Guaritrice!`2`n`n");
                    $session['user']['potion']+=1;
                }else{
                    output("`2Dopo l'ultimo colpo di piccone dato alla roccia, qualcosa affiora. Pulisci la parete e scorgi ");
                    output("una fiala di colore verde.  `@Hai trovato una Pozione Guaritrice!`2`nPeccato tu non abbia lo spazio ");
                    output("per portarla con te.`nSeppur a malincuore la riponi nella fenditura della parete rocciosa ... forse ");
                    output("qualche altro minatore la troverà e potrà usarla.`n`n");
                }
                if ($session['user']['turni_mestiere']>0) {
                    addnav("Lavora", "miniera.php?op=lavora3");
                }
                addnav("Vai al tunnel", "miniera.php?op=zona_lavoro");
            }elseif($caso>=142 AND $caso<144){
                output("`2All'improvviso senti una flebile melodia provenire dal profondo della galleria. La musica è ");
                output("dolcissima e ne sei attratto come l'ago della bussola è attratto dal polo magnetico della terra. ");
                output("Non riesci a resistere e inizi a percorrere il tunnel, facendoti guidare dalla musica.`n");
                output("Dopo un tempo che ti sembra un'eternità, giungi in una caverna dalla strana conformazione. Una ");
                output("forte corrente d'aria, proveniente chissà da dove, incanalandosi nelle strane concrezioni produce ");
                output("la musica che sentivi!!`nIl tempo che hai dedicato alla ricerca della sorgente musicale ti ha fatto ");
                $turnipersi = intval($session['user']['turni_mestiere']/2);
                $session['user']['turni_mestiere']-=$turnipersi;
                output("perdere `^$turnipersi turni`2 di lavoro!!`nScornato torni sui tuoi passi e riprendi a picconare.`n`n");
                if ($session['user']['turni_mestiere']>0) {
                    addnav("Lavora", "miniera.php?op=lavora3");
                }
                addnav("Vai al tunnel", "miniera.php?op=zona_lavoro");
            }elseif($caso>=140 AND $caso<142){
                output("`2All'improvviso senti una flebile melodia provenire dal profondo della galleria. La musica è ");
                output("dolcissima e ne sei attratto come l'ago della bussola è attratto dal polo magnetico della terra. ");
                output("Non riesci a resistere e inizi a percorrere il tunnel, facendoti guidare dalla musica.`n");
                output("Dopo un tempo che ti sembra un'eternità, giungi in una caverna dalla strana conformazione. Una ");
                output("forte corrente d'aria, proveniente chissà da dove, incanalandosi nelle strane concrezioni produce ");
                output("la musica che sentivi!!`nNella caverna trovi anche un pacchetto confezionato di `(Coscia di maiale ");
                output("arrosto`2, confezionato qualche giorno fa. Deve essere stato dimenticato da qualche altro minatore ");
                output("attirato anche lui dalla musica misteriosa.`nLo apri ed inizi a divorarlo ... eri affamato!!`n");
                output("La mangiata ha quasi saziato la tua fame, e ti ha dato l'energia per altri `^10 turni`2 di lavoro!!`n`n");
                $session['user']['turni_mestiere']+=10;
                $fame= intval(rand(.5,.8)*$session['user']['hunger']);
                $session['user']['hunger']-=$fame;
                if ($session['user']['turni_mestiere']>0) {
                    addnav("Lavora", "miniera.php?op=lavora3");
                }
                addnav("Vai al tunnel", "miniera.php?op=zona_lavoro");
            }else{
                if ($session['user']['turni_mestiere']>0) {
                    addnav("Lavora", "miniera.php?op=lavora3");
                }
                addnav("Vai al tunnel", "miniera.php?op=zona_lavoro");
            }
        }else{
            output("`vImpegnati di più mettici un po' di energia in quei colpi!`3`n");
            $fatica=rand(0, (intval($session['user']['level']/5)+1));
            $session['user']['hitpoints']-=$fatica;
            if ($session['user']['hitpoints'] <= 0) $session['user']['hitpoints']=1;
            $caso=rand(0, 100);
            if($caso>=86 AND $caso< 89){
                output("`#AAAAARGGHHH!! Hai scoperchiato la tomba di uno scheletro!`3`n");
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
                addnav("Combatti","miniera.php?op=fight");
                addnav("Scappa","miniera.php?op=run");
            }elseif($caso>=85 AND $caso< 86){
                output("La miniera è crollata e sei morto !!!`n");
                output("Hai perso il 5% della tua esperienza !!!`n");
                addnews("`%".$session['user']['name']."`5 è morto sotto un crollo, mentre lavorava in miniera!!");
                $session['user']['experience']*=0.95;
                $session['user']['alive']=false;
                $session['user']['hitpoints']=0;
                debuglog("è morto sotto un crollo mentre lavorava in miniera e ha perso ".$session['user']['gold']." oro");
	            if($session['user']['assicurazione']==1) $session['user']['goldinbank']+=$session['user']['gold'];
                $session['user']['gold']=0;
                addnav("Notizie Giornaliere", "news.php");
            }elseif($caso>=83 AND $caso< 85){
                output("`#AAAAARGGHHH!! Una fuoriuscita di gas allucinogeno!`3`n");
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
                $session['bufflist']['miniera'] = array(
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
                addnav("Combatti","miniera.php?op=fight");
                addnav("Scappa","miniera.php?op=run");
            }elseif($caso==0){
                output("`3Colpendo la volta della galleria, senti uno rombo sordo provenire dalla volta stessa. ");
                output("Comprendi che sta per crollare tutto, ed abbandoni la zona il più velocemente possibile ");
                output(" per evitare di rimanere sepolto sotto la frana.`n Fuggendo dai un'ultima occhiata fugace ");
                output("alle tue spalle, e vedi un nano che assomiglia in maniera impressionante a Brax che raccoglie ");
                output("il `b`&TUO`b piccone`3, che nella fuga precipitosa ti sei dimenticato nella zona di scavo.`nProprio ");
                output("mentre stai per inseguire Brax o chiunque sia quel nano, la volta della galleria si abbatte tra ");
                output("e il nano, obbligandoti a rinunciare al tuo proposito di inseguimento.`n`n");
                $sqlogg = "SELECT * FROM oggetti WHERE id_oggetti = ".$session['user']['oggetto'];
                $resultogg = db_query($sqlogg) or die(db_error(LINK));
                $rowogg = db_fetch_assoc($resultogg);
                if ($rowogg['nome'] == "Piccone da minatore"){
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
                addnav("Torna all'ingresso", "miniera.php");
            }else{
                if ($session['user']['turni_mestiere']>0) {
                    addnav("Lavora", "miniera.php?op=lavora3");
                }
                addnav("Vai al tunnel", "miniera.php?op=zona_lavoro");
            }
        }
    }
}
if ($_GET['op']=="lavora4") {
    $session['user']['dove_sei']=0;
    $badguy=array();
    $hungerchance=rand(0,3);
    usurapiccone();
    if ($session['user']['oggetto'] != 0) {
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
        //if($session['user']['minatore']< 5)$session['user']['minatore']+=0.01;
        //if($session['user']['minatore']< 10)$session['user']['minatore']+=0.01;
        output("`3Lavora!`3`n");
        output("`3Impugni il tuo piccone e colpisci la parete della miniera cercando di staccare la maggior quantità possibile di argento!`3`n");
        $forza=intval(($session['user']['attack']+$session['user']['defence'])/100);
        if($forza==0)$forza=1;
        //result è una percentuale il massimo fissato indica la massima possibilità di riuscita
        $result=$session['user']['minatore'];
        if($result>15)$result=15;
        //$result=100;
        $cento=rand(1,100);
        if($result >= $cento){
            //savesetting("carbone",getsetting("carbone",0)+1);
            $sqldr="INSERT INTO miniera (acctid,data,materiale) VALUES ('$account',FROM_UNIXTIME(UNIX_TIMESTAMP()),'Carbone')";
            db_query($sqldr) or die(db_error(LINK));
            $fatica=rand(0,1);
            $session['user']['hitpoints']-=$fatica;
            if ($session['user']['hitpoints'] <=0) $session['user']['hitpoints']=1;
            output("`n`#Un gran colpo!!`n");
            if (rand(1,100) > 95 AND !zainoPieno($idplayer)){
               output("Sei riuscito a staccare una `&Scaglia d'Argento`3 dalla vena!!`nLa riponi nel tuo zaino pensando all'oro che Oberon ti darà in cambio.`n");
               debuglog("trova una scaglia d'argento nella miniera");
                $sqli="INSERT INTO zaino (idoggetto, idplayer) VALUES ('5', '$account')";
               db_query($sqli) or die(db_error(LINK));
            }
            $session['user']['minatore']+=0.01;
            $caso=rand(1, 200);
            //$caso=185;
            if($caso==200){
                output("`#Dalla parete di roccia che hai colpito, affiorano delle gemme ... le conti e scopri ");
                output("che sono `^`b10`#`b!!!!`nQuesto si che è un colpo di fortuna!!`3`n");
                debuglog("trova 10 gemme nella miniera");
                $session['user']['gems']+=10;
                if ($session['user']['turni_mestiere']>0) {
                    addnav("Lavora", "miniera.php?op=lavora3");
                }
                addnav("Vai al tunnel", "miniera.php?op=zona_lavoro");
            }elseif($caso>=197 AND $caso< 200){
                output("`#Dalla parete di roccia che hai colpito emergono `&due gemme`#, che prontamente infili in tasca!`3`n");
                $session['user']['gems']+=2;
                debuglog("trova due gemme nella miniera");
                if ($session['user']['turni_mestiere']>0) {
                    if($session['user']['superuser']>0) addnav("`^Vai al tunnel (Admin)", "miniera.php?op=zona_lavoro");
                    addnav("Lavora", "miniera.php?op=lavora4");
                }else{
                    addnav("Vai al tunnel", "miniera.php?op=zona_lavoro");
                }
            }elseif($caso>=190 AND $caso< 197){
                $gold = $session['user']['level'] * 100;
                output("`#Dalla parete di roccia che hai colpito emerge una vena d'oro! Continui a picconare ed al termine del ");
                output("tuo lavoro ti ritrovi con  $gold pezzi d'oro!`3`n");
                $session['user']['gold']+=$gold;
                debuglog("trova $gold pezzi d'oro nella miniera");
                if ($session['user']['turni_mestiere']>0) {
                    if($session['user']['superuser']>0) addnav("`^Vai al tunnel (Admin)", "miniera.php?op=zona_lavoro");
                    addnav("Lavora", "miniera.php?op=lavora4");
                }else{
                    addnav("Vai al tunnel", "miniera.php?op=zona_lavoro");
                }
            }elseif($caso>=189 AND $caso<190){
                output("`#Dal colpo che hai dato, si apre una spaccatura nel muro, ed una pergamena viene alla luce.`n");
                output("La esamini e scopri che è una ricetta, felice per il ritrovamento la metti nello zaino!`3`n");
                if (zainoPieno($idplayer)){
                    output ("Non hai posto per questa questa ricetta, il tuo zaino è pieno !");
                }else{
                    $sqldr="INSERT INTO zaino (idoggetto,idplayer) VALUES ('19','$account')";
                    db_query($sqldr) or die(db_error(LINK));
                    debuglog("trova ricetta 19 nella miniera");
                }
                if ($session['user']['turni_mestiere']>0) {
                    if($session['user']['superuser']>0) addnav("`^Vai al tunnel (Admin)", "miniera.php?op=zona_lavoro");
                    addnav("Lavora", "miniera.php?op=lavora4");
                }else{
                    addnav("Vai al tunnel", "miniera.php?op=zona_lavoro");
                }
            }elseif($caso>=186 AND $caso< 188){
                output("`#AAAAARGGHHH!! Hai scoperchiato la tomba di un minatore morto. Il suo scheletro si risveglia e ti attacca!`3`n");
                $badguy = array("creaturename"=>"`&Scheletro di Minatore`0"
                ,"creaturelevel"=>3
                ,"creatureweapon"=>"`7Piccone da Minatore`0"
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
                addnav("Combatti","miniera.php?op=fight");
                addnav("Scappa","miniera.php?op=run");
            }elseif($caso>=182 AND $caso< 186){
                output("`4Purtroppo dalla parete fuoriesce del gas, ed il colpo di piccone successivo provoca una ");
                output("scintilla, che innesca il gas con susseguente esplosione!!!`nLa volta della miniera ");
                output("ti crolla addosso e muori solo e tra dolori atroci!!!`n");
                output("`\$Hai perso il 5% della tua esperienza !!!`n");
                addnews("`%".$session['user']['name']."`5 è morto sotto un crollo, mentre lavorava in miniera!!");
                debuglog("è morto sotto un crollo in miniera e ha perso ".$session['user']['gold']." oro");
                $session['user']['experience'] *= 0.95;
                $session['user']['alive'] = false;
                $session['user']['hitpoints'] = 0;
	            if($session['user']['assicurazione']==1) $session['user']['goldinbank']+=$session['user']['gold'];
                $session['user']['gold'] = 0;
                addnav("Notizie Giornaliere","news.php");
            }elseif($caso>=180 AND $caso< 182){
                output("`#AAAAARGGHHH!! Il rumore delle tue picconate ha innervosito i pipistrelli che abitano la caverna!`3`n");
                $dkb = round($session['user']['dragonkills']*.15);
                $badguy = array("creaturename"=>"`(Pipistrelli`0"
                ,"creaturelevel"=>2
                ,"creatureweapon"=>"`\$Morso Succhiasangue`0"
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
                $session['bufflist']['miniera'] = array(
                "startmsg"=>"`n`%Il morso dei pipistrelli ti ha risucchiato un po' di HitPoint!`n`n",
                "name"=>"`%Morso Succhiasangue`0",
                "rounds"=>10,
                "wearoff"=>"I pipistrelli si ritirano nuovamente sulla volta della caverna.",
                "minioncount"=>10,
                "mingoodguydamage"=>1,
                "maxgoodguydamage"=>2+$dkb,
                "effectmsg"=>"Il sangue perso causa la perdita di {damage} HitPoint.",
                "effectnodmgmsg"=>"Fortunatamente il morso non ha preso una vena.",
                "activate"=>"roundstart",
                );
                addnav("Combatti","miniera.php?op=fight");
                addnav("Scappa","miniera.php?op=run");
            }elseif($caso>=169 AND $caso< 180){
                output("`3Colpendo la volta della galleria, senti uno rombo sordo provenire dalla volta stessa. ");
                output("Comprendi che sta per crollare tutto, ed abbandoni velocemente la zona per evitare di rimanere sepolto sotto ");
                output("la frana.`n Nella fuga precipitosa smarrisci il senso dell'orientamento e perdi 5 turni di scavo.`n`n");
                $session['user']['turni_mestiere'] -= 5;
                if ($session['user']['turni_mestiere'] < 0) $session['user']['turni_mestiere'] = 0;
                if ($session['user']['turni_mestiere']>0) {
                    if($session['user']['superuser']>0) addnav("`^Vai al tunnel (Admin)", "miniera.php?op=zona_lavoro");
                    addnav("Lavora", "miniera.php?op=lavora4");
                }else{
                    addnav("Vai al tunnel", "miniera.php?op=zona_lavoro");
                }
            }elseif($caso>=160 AND $caso<169){
                output("`n`3Noti solo ora nel tunnel una lucina flebile in lontananza e decidi di investigare.`n");
                output("Mentre ti avvicini alla luce vedi una strana costruzione e scopri che si tratta di un bagno ");
                output("pubblico. Chi mai lo avrà costruito? Probabilmente qualche minatore solitario che non voleva ");
                output("`iannaffiare`i la miniera o che non voleva rinunciare alla comodità di un vero bagno.`n");
                output("Chiunque sia stato, decidi di approfittare dell'opportunità e ti liberi di un peso superfluo.`n`n");
                $session['user']['bladder']=0;
                if ($session['user']['turni_mestiere']>0) {
                    if($session['user']['superuser']>0) addnav("`^Vai al tunnel (Admin)", "miniera.php?op=zona_lavoro");
                    addnav("Lavora", "miniera.php?op=lavora4");
                }else{
                    addnav("Vai al tunnel", "miniera.php?op=zona_lavoro");
                }
            }elseif($caso>=159 AND $caso<160){
                output("`#Pensi di aver trovato una buona vena, e dai fondo alle tue energia dando un colpo molto potente ");
                output("con il piccone. Purtroppo il colpo è stato veramente `bTROPPO`b potente!!`n`n");
                output("Il piccone ti si sbriciola tra le mani, e ti ritrovi con qualche scheggia di legno ed un ");
                output("ammasso informe di metallo.`n");
                $sqlogg = "SELECT nome FROM oggetti WHERE id_oggetti = ".$session['user']['oggetto'];
                $resultogg = db_query($sqlogg) or die(db_error(LINK));
                $rowogg = db_fetch_assoc($resultogg);
                if ($rowogg['nome'] == "Piccone da minatore"){
                    $sqlogg = "DELETE FROM oggetti WHERE id_oggetti = ".$session['user']['oggetto'];
                    $result = db_query($sqlogg) or die(db_error(LINK));
                    $session['user']['oggetto'] = 0;
                }else{
                    $sqlogg = "DELETE FROM oggetti WHERE id_oggetti = ".$session['user']['zaino'];
                    $result = db_query($sqlogg) or die(db_error(LINK));
                    $session['user']['zaino'] = 0;
                }
                addnav("Torna all'ingresso", "miniera.php");
            }elseif($caso>=155 AND $caso<159){
                output("`n`3Noti solo ora nel tunnel una lucina flebile in lontananza e decidi di investigare.`n");
                output("Mentre ti avvicini alla luce vedi una strana costruzione e scopri che si tratta di un bagno ");
                output("pubblico. Chi mai lo avrà costruito? Probabilmente qualche minatore solitario che non voleva ");
                output("`iannaffiare`i la miniera o che non voleva rinunciare alla comodità di un vero bagno.`n");
                output("Chiunque sia stato, decidi di approfittare dell'opportunità e ti liberi di un peso superfluo.`n");
                output("Durante la minzione noti qualcosa che brilla sul pavimento. Con estrema cautela ti chini e scopri ");
                output("che è una `&gemma`3, persa da qualche altro minatore!!`n`n");
                $session['user']['gems']++;
                debuglog("trova una gemma al gabinetto della miniera");
                $session['user']['bladder']=0;
                if ($session['user']['turni_mestiere']>0) {
                    if($session['user']['superuser']>0) addnav("`^Vai al tunnel (Admin)", "miniera.php?op=zona_lavoro");
                    addnav("Lavora", "miniera.php?op=lavora4");
                }else{
                    addnav("Vai al tunnel", "miniera.php?op=zona_lavoro");
                }
            }elseif($caso>=151 AND $caso<155){
                output("`n`3Noti solo ora nel tunnel una lucina flebile in lontananza e decidi di investigare.`n");
                output("Mentre ti avvicini alla luce vedi una strana costruzione e scopri che si tratta di un bagno ");
                output("pubblico. Chi mai lo avrà costruito? Probabilmente qualche minatore solitario che non voleva ");
                output("`iannaffiare`i la miniera o che non voleva rinunciare alla comodità di un vero bagno.`n");
                output("Chiunque sia stato, decidi di approfittare dell'opportunità e ti liberi di un peso superfluo.`n");
                output("Durante la minzione purtroppo ti cade una `&gemma`3 dalla tasca, ed il pavimento è troppo sporco per ");
                output("rischiare di riprenderla ... chissà quale terribile malattia contrarresti!!`n`n");
                $session['user']['gems']--;
                debuglog("perde una gemma al gabinetto della miniera");
                $session['user']['bladder']=0;
                if ($session['user']['turni_mestiere']>0) {
                    if($session['user']['superuser']>0) addnav("`^Vai al tunnel (Admin)", "miniera.php?op=zona_lavoro");
                    addnav("Lavora", "miniera.php?op=lavora4");
                }else{
                    addnav("Vai al tunnel", "miniera.php?op=zona_lavoro");
                }
            }elseif($caso>=147 AND $caso<151){
                output("`n`3Noti solo ora nel tunnel una lucina flebile in lontananza e decidi di investigare.`n");
                output("Mentre ti avvicini alla luce vedi una strana costruzione e scopri che si tratta di una doccia ");
                output("pubblica. Chi mai l'avrà costruita? Sicuramente questo `^Rickenbauer`3 non voleva ");
                output("rischiare di sporcarsi troppo in miniera  voleva tornare al villaggio lindo.`n");
                output("Decidi di approfittare dell'opportunità e ti dai una bella lavata.`n");
                output("Al termine ti senti pulito, il sapone è stato il tocco di classe che ti ha permesso di lavarti ");
                output("come non avveniva da tempo ... in cuor tuo ringrazi il povero minatore scomparso.`n`n");
                $session['user']['clean']=0;
                if ($session['user']['turni_mestiere']>0) {
                    if($session['user']['superuser']>0) addnav("`^Vai al tunnel (Admin)", "miniera.php?op=zona_lavoro");
                    addnav("Lavora", "miniera.php?op=lavora4");
                }else{
                    addnav("Vai al tunnel", "miniera.php?op=zona_lavoro");
                }
            }elseif($caso>=144 AND $caso<147){
                if ($session['user']['potion']<5){
                    output("`2Dopo l'ultimo colpo di piccone dato alla roccia, qualcosa affiora. Pulisci la parete e scorgi ");
                    output("una fiala di colore verde.  `@Hai trovato una Pozione Guaritrice!`2`n`n");
                    $session['user']['potion']+=1;
                }else{
                    output("`2Dopo l'ultimo colpo di piccone dato alla roccia, qualcosa affiora. Pulisci la parete e scorgi ");
                    output("una fiala di colore verde.  `@Hai trovato una Pozione Guaritrice!`2`nPeccato tu non abbia lo spazio ");
                    output("per portarla con te.`nSeppur a malincuore la riponi nella fenditura della parete rocciosa ... forse ");
                    output("qualche altro minatore la troverà e potrà usarla.`n`n");
                }
                if ($session['user']['turni_mestiere']>0) {
                    if($session['user']['superuser']>0) addnav("`^Vai al tunnel (Admin)", "miniera.php?op=zona_lavoro");
                    addnav("Lavora", "miniera.php?op=lavora4");
                }else{
                    addnav("Vai al tunnel", "miniera.php?op=zona_lavoro");
                }
            }elseif($caso>=142 AND $caso<144){
                output("`2All'improvviso senti una flebile melodia provenire dal profondo della galleria. La musica è ");
                output("dolcissima e ne sei attratto come l'ago della bussola è attratto dal polo magnetico della terra. ");
                output("Non riesci a resistere e inizi a percorrere il tunnel, facendoti guidare dalla musica.`n");
                output("Dopo un tempo che ti sembra un'eternità, giungi in una caverna dalla strana conformazione. Una ");
                output("forte corrente d'aria, proveniente chissà da dove, incanalandosi nelle strane concrezioni produce ");
                output("la musica che sentivi!!`nIl tempo che hai dedicato alla ricerca della sorgente musicale ti ha fatto ");
                $turnipersi = intval(($session['user']['turni_mestiere']/2)+1);
                $session['user']['turni_mestiere']-=$turnipersi;
                output("perdere `^$turnipersi turni`2 di lavoro!!`nScornato torni sui tuoi passi e riprendi a picconare.`n`n");
                if ($session['user']['turni_mestiere']>0) {
                    if($session['user']['superuser']>0) addnav("`^Vai al tunnel (Admin)", "miniera.php?op=zona_lavoro");
                    addnav("Lavora", "miniera.php?op=lavora4");
                }else{
                    addnav("Vai al tunnel", "miniera.php?op=zona_lavoro");
                }
            }elseif($caso>=140 AND $caso<142){
                output("`2All'improvviso senti una flebile melodia provenire dal profondo della galleria. La musica è ");
                output("dolcissima e ne sei attratto come l'ago della bussola è attratto dal polo magnetico della terra. ");
                output("Non riesci a resistere e inizi a percorrere il tunnel, facendoti guidare dalla musica.`n");
                output("Dopo un tempo che ti sembra un'eternità, giungi in una caverna dalla strana conformazione. Una ");
                output("forte corrente d'aria, proveniente chissà da dove, incanalandosi nelle strane concrezioni produce ");
                output("la musica che sentivi!!`nNella caverna trovi anche un pacchetto confezionato di `(Coscia di maiale ");
                output("arrosto`2, confezionato qualche giorno fa. Deve essere stato dimenticato da qualche altro minatore ");
                output("attirato anche lui dalla musica misteriosa.`nLo apri ed inizi a divorarlo ... eri affamato!!`n");
                output("La mangiata ha quasi saziato la tua fame, e ti ha dato l'energia per altri `^10 turni`2 di lavoro!!`n`n");
                $session['user']['turni_mestiere']+=10;
                $fame= intval(rand(.6,.9)*$session['user']['hunger']);
                $session['user']['hunger']-=$fame;
                if ($session['user']['turni_mestiere']>0) {
                    if($session['user']['superuser']>0) addnav("`^Vai al tunnel (Admin)", "miniera.php?op=zona_lavoro");
                    addnav("Lavora", "miniera.php?op=lavora4");
                }else{
                    addnav("Vai al tunnel", "miniera.php?op=zona_lavoro");
                }
            }else{
                if ($session['user']['turni_mestiere']>0) {
                    if($session['user']['superuser']>0) addnav("`^Vai al tunnel (Admin)", "miniera.php?op=zona_lavoro");
                    addnav("Lavora", "miniera.php?op=lavora4");
                }else{
                    addnav("Vai al tunnel", "miniera.php?op=zona_lavoro");
                }
            }
        }else{
            output("`vImpegnati di più mettici un po' di energia in quei colpi!`3`n");
            $fatica=rand(0, (intval($session['user']['level']/5)+1));
            $session['user']['hitpoints']-=$fatica;
            if ($session['user']['hitpoints'] <= 0) $session['user']['hitpoints']=1;
            $caso=rand(0, 100);
            //$caso = 86;
            if($caso>=87 AND $caso< 89){
                output("`#AAAAARGGHHH!! Hai colpito la parete con troppa forza! Un grosso masso ti cade sul piede sinistro ");
                output("ferendoti e facendoti zoppicare. Probabilmente il prossimo combattimento non sarai molto prestante!`3`n");
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
                    if($session['user']['superuser']>0) addnav("`^Vai al tunnel (Admin)", "miniera.php?op=zona_lavoro");
                    addnav("Lavora", "miniera.php?op=lavora4");
                }else{
                    addnav("Vai al tunnel", "miniera.php?op=zona_lavoro");
                }
            }elseif($caso>=85 AND $caso< 87){
                output("La miniera è crollata e sei morto !!!`n");
                output("Hai perso il 5% della tua esperienza !!!`n");
                addnews("`%".$session['user']['name']."`5 è morto sotto un crollo, mentre lavorava in miniera!!");
                $session['user']['experience']*=0.95;
                $session['user']['alive']=false;
                $session['user']['hitpoints']=0;
                debuglog("è morto sotto un crollo mentre lavorava in miniera e ha perso ".$session['user']['gold']." oro");
	            if($session['user']['assicurazione']==1) $session['user']['goldinbank']+=$session['user']['gold'];
                $session['user']['gold']=0;
                addnav("Notizie Giornaliere", "news.php");
            }elseif($caso>=80 AND $caso< 85){
                output("`#AAAAARGGHHH!! Hai colpito la parete con troppa forza! Un enorme masso ti cade sul piede destro ferendoti ");
                output("seriamente e facendoti zoppicare. Probabilmente il prossimo combattimento non sarai molto atletico!`3`n");
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
                    if($session['user']['superuser']>0) addnav("`^Vai al tunnel (Admin)", "miniera.php?op=zona_lavoro");
                    addnav("Lavora", "miniera.php?op=lavora4");
                }else{
                    addnav("Vai al tunnel", "miniera.php?op=zona_lavoro");
                }
            }elseif($caso==0){
                output("`3Colpendo la volta della galleria, senti uno rombo sordo provenire dalla volta stessa. ");
                output("Comprendi che sta per crollare tutto, ed abbandoni la zona il più velocemente possibile ");
                output(" per evitare di rimanere sepolto sotto la frana.`n Fuggendo dai un'ultima occhiata fugace ");
                output("alle tue spalle, e vedi un nano che assomiglia in maniera impressionante a Brax che raccoglie ");
                output("il `b`&TUO`b piccone`3, che nella fuga precipitosa ti sei dimenticato nella zona di scavo.`nProprio ");
                output("mentre stai per inseguire Brax o chiunque sia quel nano, la volta della galleria si abbatte tra ");
                output("e il nano, obbligandoti a rinunciare al tuo proposito di inseguimento.`n`n");
                $sqlogg = "SELECT * FROM oggetti WHERE id_oggetti = ".$session['user']['oggetto'];
                $resultogg = db_query($sqlogg) or die(db_error(LINK));
                $rowogg = db_fetch_assoc($resultogg);
                if ($rowogg['nome'] == "Piccone da minatore"){
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
                addnav("Torna all'ingresso", "miniera.php");
            }else{
                if ($session['user']['turni_mestiere']>0) {
                    if($session['user']['superuser']>0) addnav("`^Vai al tunnel (Admin)", "miniera.php?op=zona_lavoro");
                    addnav("Lavora", "miniera.php?op=lavora4");
                }else{
                    addnav("Vai al tunnel", "miniera.php?op=zona_lavoro");
                }
            }
        }
    }
}
if ($_GET['op']=="lavora5") {
    $badguy=array();
    $hungerchance=rand(0,4);
    usurapiccone();
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
        //if($session['user']['minatore']< 5)$session['user']['minatore']+=0.01;
        if($session['user']['minatore']< 10)$session['user']['minatore']+=0.01;
        output("`3Lavora!`3`n");
        output("`3Impugni il tuo piccone e colpisci la roccia solforosa, cercando di staccarne un bel pezzo!`3`n");
        $forza=intval(($session['user']['attack']+$session['user']['defence'])/100);
        if($forza==0)$forza=1;
        //result è una percentuale il massimo fissato indica la massima possibilità di riuscita
        $result=$session['user']['minatore']+$forza;
        if($result>80)$result=80;
        $cento=rand(1, 100);
        if($result >= $cento){
            if (e_rand(1,5)==1 AND !zainoPieno($idplayer)) {
               output("`@Riesci anche a mettere un po' dello zolfo estratto nel tuo zaino!`n`n");
               $sqli="INSERT INTO zaino (idoggetto, idplayer) VALUES ('21', '".$session['user']['acctid']."')";
               db_query($sqli) or die(db_error(LINK));
               debuglog("guadagna una dose di zolfo lavorando in miniera");
            }
            savesetting("zolfo",getsetting("zolfo",0)+1);
            $sqldr="INSERT INTO miniera (acctid,data,materiale) VALUES ('$account',FROM_UNIXTIME(UNIX_TIMESTAMP()),'Zolfo')";
            db_query($sqldr) or die(db_error(LINK));
            $fatica=rand(0,1);
            $session['user']['hitpoints']-=$fatica;
            if ($session['user']['hitpoints'] <=0) $session['user']['hitpoints']=1;
            output("`#Un buon colpo!`3`n");
            $session['user']['minatore']+=0.01;
            $caso=rand(1, 200);
            if($caso==200){
                output("`#Dalla parete di roccia che hai colpito, affiorano delle gemme ... le conti e scopri ");
                output("che sono `^`b8`#`b!!!!`nQuesto si che è un colpo di fortuna!!`3`n");
                debuglog("trova 8 gemme nella miniera");
                $session['user']['gems']+=8;
                if ($session['user']['turni_mestiere']>0) {
                    addnav("Lavora", "miniera.php?op=lavora5");
                }
                addnav("Vai al tunnel", "miniera.php?op=zona_lavoro");
            }elseif($caso>=195 AND $caso< 200){
                output("`#Dalla parete di roccia che hai colpito emerge una gemma, che prontamente infili in tasca!`3`n");
                $session['user']['gems']+=1;
                debuglog("trova una gemma nella miniera");
                if ($session['user']['turni_mestiere']>0) {
                    addnav("Lavora", "miniera.php?op=lavora5");
                }
                addnav("Vai al tunnel", "miniera.php?op=zona_lavoro");
            }elseif($caso>=190 AND $caso< 195){
                $gold = $session['user']['level'] * 37;
                output("`#Dalla parete di roccia che hai colpito emerge una vena d'oro! Continui a picconare ed al termine del ");
                output("tuo lavoro ti ritrovi con  $gold pezzi d'oro!`3`n");
                $session['user']['gold']+=$gold;
                debuglog("trova $gold pezzi d'oro nella miniera");
                if ($session['user']['turni_mestiere']>0) {
                    addnav("Lavora", "miniera.php?op=lavora5");
                }
                addnav("Vai al tunnel", "miniera.php?op=zona_lavoro");
            }elseif($caso>=188 AND $caso<190){
                output("`#Dal colpo che hai dato, si apre una spaccatura nel muro, ed una pergamena viene alla luce.`n");
                output("La esamini e scopri che contiene un incantesimo, felice per il ritrovamento la metti nello zaino!`3`n");
                if (zainoPieno($idplayer)){
                    output ("Non hai posto per questa questa pergamena, il tuo zaino è pieno !");
                }else{
                    $sqldr="INSERT INTO zaino (idoggetto,idplayer) VALUES ('40','$account')";
                    db_query($sqldr) or die(db_error(LINK));
                    debuglog("trova pergamena 40 nella miniera");
                }
                if ($session['user']['turni_mestiere']>0) {
                    addnav("Lavora", "miniera.php?op=lavora5");
                }
                addnav("Vai al tunnel", "miniera.php?op=zona_lavoro");
            }elseif($caso>=186 AND $caso< 188){
                output("`#AAAAARGGHHH!! Mentre colpisci la roccia, un Esploratore Goblin compare in lontananza! Dovrai combatterlo!`3`n");
                $badguy = array("creaturename"=>"`9Esploratore Goblin`0"
                ,"creaturelevel"=>1
                ,"creatureweapon"=>"`(Spada Goblin`0"
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
                addnav("Combatti","miniera.php?op=fight");
                addnav("Scappa","miniera.php?op=run");

            }elseif($caso>=181 AND $caso< 186){
                output("`4Purtroppo dalla parete fuoriesce del gas, ed il colpo di piccone successivo provoca una ");
                output("scintilla, che innesca il gas con susseguente esplosione!!!`nLa volta della miniera ");
                output("ti crolla addosso e muori solo e tra dolori atroci!!!`n");
                output("`\$Hai perso il 5% della tua esperienza !!!`n");
                addnews("`%".$session['user']['name']."`5 è morto sotto un crollo, mentre lavorava in miniera!!");
                debuglog("è morto sotto un crollo in miniera e ha perso ".$session['user']['gold']." oro");
                $session['user']['experience'] *= 0.95;
                $session['user']['alive'] = false;
                $session['user']['hitpoints'] = 0;
	            if($session['user']['assicurazione']==1) $session['user']['goldinbank']+=$session['user']['gold'];
                $session['user']['gold'] = 0;
                addnav("Notizie Giornaliere","news.php");
            }elseif($caso>=178 AND $caso< 181){
                output("`#AAAAARGGHHH!! Hai trovato un Fungo Assassino!`3`n");
                $dkb = round($session['user']['dragonkills']*.15);
                $badguy = array("creaturename"=>"`\$Fungo Assassino`0"
                ,"creaturelevel"=>0
                ,"creatureweapon"=>"`\$Spore Soffocanti`0"
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
                $session['bufflist']['miniera'] = array(
                "startmsg"=>"`n`%Il Fungo emette una nube di spore velenose!`n`n",
                "name"=>"`%Spore Velenose`0",
                "rounds"=>10,
                "wearoff"=>"La nube di spore si è dispersa.",
                "minioncount"=>$session['user']['level'],
                "mingoodguydamage"=>0,
                "maxgoodguydamage"=>2+$dkb,
                "effectmsg"=>"Le spore del fungo ti causano {damage} punti danno.",
                "effectnodmgmsg"=>"Riesci a non respirare le spore del fungo.",
                "activate"=>"roundstart",
                );
                addnav("Combatti","miniera.php?op=fight");
                addnav("Scappa","miniera.php?op=run");
            }elseif($caso>=169 AND $caso< 178){
                output("`3Colpendo la volta della galleria, senti uno rombo sordo provenire dalla volta stessa. ");
                output("Comprendi che sta per crollare tutto, ed abbandoni velocemente la zona per evitare di rimanere sepolto sotto ");
                output("la frana.`n Nella fuga precipitosa smarrisci il senso dell'orientamento e perdi 5 turni di scavo.`n`n");
                $session['user']['turni_mestiere'] -= 5;
                if ($session['user']['turni_mestiere'] < 0) $session['user']['turni_mestiere'] = 0;
                if ($session['user']['turni_mestiere']>0) {
                    addnav("Lavora", "miniera.php?op=lavora5");
                }
                addnav("Vai al tunnel", "miniera.php?op=zona_lavoro");
            }elseif($caso>=160 AND $caso<169){
                output("`n`3Noti solo ora nel tunnel una lucina flebile in lontananza e decidi di investigare.`n");
                output("Mentre ti avvicini alla luce vedi una strana costruzione e scopri che si tratta di un bagno ");
                output("pubblico. Chi mai lo avrà costruito? Probabilmente qualche minatore solitario che non voleva ");
                output("`iannaffiare`i la miniera o che non voleva rinunciare alla comodità di un vero bagno.`n");
                output("Chiunque sia stato, decidi di approfittare dell'opportunità e ti liberi di un peso superfluo.`n`n");
                $session['user']['bladder']=0;
                if ($session['user']['turni_mestiere']>0) {
                    addnav("Lavora", "miniera.php?op=lavora5");
                }
                addnav("Vai al tunnel", "miniera.php?op=zona_lavoro");
            }elseif($caso>=159 AND $caso<160){
                output("`#Pensi di aver trovato una buona vena, e dai fondo alle tue energia dando un colpo molto potente ");
                output("con il piccone. Purtroppo il colpo è stato veramente `bTROPPO`b potente!!`n`n");
                output("Il piccone ti si sbriciola tra le mani, e ti ritrovi con qualche scheggia di legno ed un ");
                output("ammasso informe di metallo.`n");
                $sqlogg = "SELECT nome FROM oggetti WHERE id_oggetti = ".$session['user']['oggetto'];
                $resultogg = db_query($sqlogg) or die(db_error(LINK));
                $rowogg = db_fetch_assoc($resultogg);
                if ($rowogg['nome'] == "Piccone da minatore"){
                    $sqlogg = "DELETE FROM oggetti WHERE id_oggetti = ".$session['user']['oggetto'];
                    $result = db_query($sqlogg) or die(db_error(LINK));
                    $session['user']['oggetto'] = 0;
                }else{
                    $sqlogg = "DELETE FROM oggetti WHERE id_oggetti = ".$session['user']['zaino'];
                    $result = db_query($sqlogg) or die(db_error(LINK));
                    $session['user']['zaino'] = 0;
                }
                addnav("Torna all'ingresso", "miniera.php");
            }else{
                if ($session['user']['turni_mestiere']>0) {
                    addnav("Lavora", "miniera.php?op=lavora5");
                }
                addnav("Vai al tunnel", "miniera.php?op=zona_lavoro");
            }

        }else{
            output("`vImpegnati di più mettici un po' di energia in quei colpi!`3`n");
            $fatica=rand(0, (intval($session['user']['level']/5)+1));
            $session['user']['hitpoints']-=$fatica;
            if ($session['user']['hitpoints'] <= 0) $session['user']['hitpoints']=1;
            $caso=rand(0, 100);
            if($caso>=87 AND $caso< 89){
                output("`#AAAAARGGHHH!! Hai aperto una tana di un Tasso di Miniera!`3`n");
                $badguy = array("creaturename"=>"`(Tasso di Miniera`0"
                ,"creaturelevel"=>0
                ,"creatureweapon"=>"`8Morso Feroce`0"
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
                addnav("Combatti","miniera.php?op=fight");
                addnav("Scappa","miniera.php?op=run");
            }elseif($caso>=86 AND $caso< 87){
                output("La miniera è crollata e sei morto !!!`n");
                output("Hai perso il 5% della tua esperienza !!!`n");
                addnews("`%".$session['user']['name']."`5 è morto sotto un crollo, mentre lavorava in miniera!!");
                $session['user']['experience']*=0.95;
                $session['user']['alive']=false;
	            $session['user']['hitpoints']=0;
                debuglog("è morto sotto un crollo mentre lavorava in miniera e ha perso ".$session['user']['gold']." oro");
				if($session['user']['assicurazione']==1) $session['user']['goldinbank']+=$session['user']['gold'];
                $session['user']['gold']=0;
                addnav("Notizie Giornaliere", "news.php");
            }elseif($caso>=82 AND $caso< 85){
                output("`#AAAAARGGHHH!! Hai aperto una tana di Lumache Carnivore!`3`n");
                $dkb = round($session['user']['dragonkills']*.1);
                $badguy = array("creaturename"=>"`@Lumache Carnivore`0"
                ,"creaturelevel"=>0
                ,"creatureweapon"=>"`2Bava Urticante`0"
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
                $session['bufflist']['miniera'] = array(
                "startmsg"=>"`n`@Le lumache ti stanno riempiendo di bava!`n`n",
                "name"=>"`2Bava delle Lumache`0",
                "rounds"=>10,
                "wearoff"=>"Le Lumache hanno esaurito la loro bava.",
                "minioncount"=>$session['user']['level'],
                "mingoodguydamage"=>0,
                "maxgoodguydamage"=>1+$dkb,
                "effectmsg"=>"La bava delle lumache ti causa {damage} punti danno.",
                "effectnodmgmsg"=>"Riesci a resistere agli effetti della bava delle lumache.",
                "activate"=>"roundstart",
                );
                addnav("Combatti","miniera.php?op=fight");
                addnav("Scappa","miniera.php?op=run");
            }elseif($caso==0){
                output("`3Colpendo la volta della galleria, senti uno rombo sordo provenire dalla volta stessa. ");
                output("Comprendi che sta per crollare tutto, ed abbandoni la zona il più velocemente possibile ");
                output(" per evitare di rimanere sepolto sotto la frana.`n Fuggendo dai un'ultima occhiata fugace ");
                output("alle tue spalle, e vedi un nano che assomiglia in maniera impressionante a Brax che raccoglie ");
                output("il `b`&TUO`b piccone`3, che nella fuga precipitosa ti sei dimenticato nella zona di scavo.`nProprio ");
                output("mentre stai per inseguire Brax o chiunque sia quel nano, la volta della galleria si abbatte tra ");
                output("e il nano, obbligandoti a rinunciare al tuo proposito di inseguimento.`n`n");
                $sqlogg = "SELECT * FROM oggetti WHERE id_oggetti = ".$session['user']['oggetto'];
                $resultogg = db_query($sqlogg) or die(db_error(LINK));
                $rowogg = db_fetch_assoc($resultogg);
                if ($rowogg['nome'] == "Piccone da minatore"){
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
                addnav("Torna all'ingresso", "miniera.php");
            }else{
                if ($session['user']['turni_mestiere']>0) {
                    addnav("Lavora", "miniera.php?op=lavora5");
                }
                addnav("Vai al tunnel", "miniera.php?op=zona_lavoro");
            }
        }
    }
}
if ($_GET['op']=='battle' || $_GET['op']=='run') {
    if ($_GET['op']=='run') {
        output("Non riesci a fuggire al mostro della miniera!!");
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
        unset($session['bufflist']['miniera']);
        $badguy = createarray($session['user']['badguy']);
        $exp = array(1=>14,26,37,50,61,73,85,98,111,125,140,155,172,189,208,228,250,275,310,348);
        output("Hai battuto `^".$badguy['creaturename'].".`n");
        $guadagno=round($exp[$badguy['creaturelevel']]/2);
        output("Hai guadagnato $guadagno punti esperienza !!!`n");
        addnews("`%".$session['user']['name']."`@ è stato attaccato da ".$badguy['creaturename']. "`@, mentre lavorava in miniera!! E ha vinto!");
        $session['user']['experience']+=$guadagno;
        $session['user']['badguy']="";
        if ($session['user']['turni_mestiere']>0) {
        ////mettere le varie condizioni in base al mostro per tornare alla zona da cui si proveniva
        if($badguy['creaturename']=="`8Ragno Gigante`0" OR
           $badguy['creaturename']=="`4Scorpione Gigante`0" OR
           $badguy['creaturename']=="`8Ratto di Miniera`0" OR
           $badguy['creaturename']=="`5Branco di Scarabei Stercorari`0"){
           if($session['user']['superuser']>0) addnav("`^Vai al tunnel (Admin)", "miniera.php?op=zona_lavoro");
           addnav("Torna al lavoro","miniera.php?op=luce");
        }elseif($badguy['creaturename']=="`9Tarantola Maculata`0" OR
                $badguy['creaturename']=="`\$Formiche Rosse`0" OR
                $badguy['creaturename']=="`(Pantegana di Miniera`0" OR
                $badguy['creaturename']=="`@Sciame di Cavallette Acrididi`0"){
                if($session['user']['superuser']>0) addnav("`^Vai al tunnel (Admin)", "miniera.php?op=zona_lavoro");
                addnav("Torna al lavoro","miniera.php?op=buio");
        }elseif($badguy['creaturename']=="`&Minatore Fantasma`0" OR
                $badguy['creaturename']=="`\$Zombie`0" OR
                $badguy['creaturename']=="`&Scheletro Ossuto`0" OR
                $badguy['creaturename']=="`@Nemici Immaginari`0"){
                if($session['user']['superuser']>0) addnav("`^Vai al tunnel (Admin)", "miniera.php?op=zona_lavoro");
                addnav("Torna al lavoro","miniera.php?op=scendi");
        }elseif($badguy['creaturename']=="`9Esploratore Goblin`0" OR
                $badguy['creaturename']=="`\$Fungo Assassino`0" OR
                $badguy['creaturename']=="`(Tasso di Miniera`0" OR
                $badguy['creaturename']=="`@Lumache Carnivore`0"){
                if($session['user']['superuser']>0) addnav("`^Vai al tunnel (Admin)", "miniera.php?op=zona_lavoro");
                addnav("Torna al lavoro","miniera.php?op=puzz");
        }elseif($badguy['creaturename']=="`(Vermi Giganti`0"){
                if($session['user']['superuser']>0) addnav("`^Vai al tunnel (Admin)", "miniera.php?op=zona_lavoro");
                addnav("Torna al lavoro","miniera.php?op=giu&op1=fatto");
        }elseif($badguy['creaturename']=="`&Scheletro di Minatore`0" OR
                $badguy['creaturename']=="`(Pipistrelli`0"){
                if($session['user']['superuser']>0) addnav("`^Vai al tunnel (Admin)", "miniera.php?op=zona_lavoro");
                addnav("Torna al lavoro","miniera.php?op=lavora4");
        }elseif($badguy['creaturename']=="`^Rickenbauer il Minatore`0"){
                if($session['user']['superuser']>0) addnav("`^Vai al tunnel (Admin)", "miniera.php?op=zona_lavoro");
                addnav("Torna al lavoro","miniera.php?op=tunnelpozzo&op1=fatto");
        }
        } else {
          addnav("Zona di scavo", "miniera.php?op=scavo");
        }

        }else{
            output("`4Sei morto!!`n`n");
            $session['user']['experience']*=0.95;
            $session['user']['badguy']="";
            $session['bufflist']['miniera'] = array();
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
            addnews("`%".$session['user']['name']."`6 è stato ucciso da ".$badguy['creaturename']. "`6, mentre lavorava in miniera!!`n$taunt");
            debuglog("è stato ucciso da un ".$badguy['creaturename']. " mentre lavorava in miniera. Perde 5% exp e ".$session['user']['gold']." oro");
            addnav("Notizie Giornaliere","news.php");
            $session['user']['experience']*=0.95;
            $session['user']['alive']=false;
            $session['user']['hitpoints']=0;
            if($session['user']['assicurazione']==1) $session['user']['goldinbank']+=$session['user']['gold'];
            $session['user']['gold']=0;
            //Sook: Modifica per suicidio con vermi giganti
            if ($badguy == "`(Vermi Giganti`0") {
                    $corrosione = e_rand(1,8);
                    if ($corrosione == 1 || $corrosione == 2 || $corrosione == 5 || $corrosione == 6) {
                            $session['user']['usura_arma'] -= e_rand(5,50);
                            output("`\$La bava corrosiva dei vermi ha danneggiato la tua arma!`n");
                            if ($session['user']['usura_arma'] <= 0) {
                                  output("`7`b".$session['user']['weapon']." è talmente usurato che lo devi buttare via, ");
                                  output("ti ritrovi ancora una volta a usare i Pugni...`n");
                                  debuglog("ha l'arma distrutta dai vermi giganti in miniera");
                                  $session['user']['usura_arma']=999;
                                  $session['user']['weapon']='Pugni';
                                  $session['user']['attack']-=$session['user']['weapondmg'];
                                  $session['user']['weaponvalue']=0;
                                  $session['user']['weapondmg']=0;
                            } else {
                                  debuglog("danneggia l'arma con i vermi giganti in miniera");
                            }
                    }
                    if ($corrosione == 1 || $corrosione == 3 || $corrosione == 5 || $corrosione == 7) {
                            output("`\$La bava corrosiva dei vermi ha danneggiato la tua armatura!`n");
                            $session['user']['usura_armatura'] -= e_rand(5,50);
                            if ($session['user']['usura_armatura'] <= 0) {
                                  output("`7`b".$session['user']['armor']." è talmente usurato che lo devi buttare via, ");
                                  output("ti ritrovi ancora una volta a usare la T-Shirt...");
                                  debuglog("ha l'armatura distrutta dai vermi giganti in miniera");
                                  $session['user']['usura_armatura']=999;
                                  $session['user']['armor']='T-Shirt';
                                  $session['user']['defence']-=$session['user']['armordef'];
                                  $session['user']['armordef'] = 0;
                                  $session['user']['armorvalue'] = 0;
                            } else {
                                  debuglog("danneggia l'armatura con i vermi giganti in miniera");
                            }
                    }
                    if ($corrosione < 5) {
                        $caso=e_rand(1,5);
                        $sqlo = "SELECT usura FROM oggetti WHERE id_oggetti = '{$session['user']['oggetto']}'";
                        $resulto = db_query($sqlo) or die(db_error(LINK));
                        $rowo = db_fetch_assoc($resulto);
                        $rowo['usura'] -= e_rand(5,50);
                        if ($caso !=1 AND $rowo['usura'] > 0) {
                            output("`\$La bava corrosiva dei vermi ha danneggiato il tuo piccone!`n");
                            $sqlo = "UPDATE oggetti SET usura=".$rowo['usura']." WHERE id_oggetti = '{$session['user']['oggetto']}'";
                            $resulto = db_query($sqlo) or die(db_error(LINK));
                            debuglog("danneggia il piccone con i vermi giganti in miniera");
                        } else {
                            output("`\$La bava corrosiva dei vermi ha `bdistrutto`b il tuo piccone!`n `7Ora dovrai procurartene uno nuovo...");
                            $sqlo = "DELETE FROM oggetti WHERE id_oggetti = '{$session['user']['oggetto']}'";
                            $resulto = db_query($sqlo) or die(db_error(LINK));
                            debuglog("distrugge il piccone con i vermi giganti in miniera");
                         }
                    }
            }
            //Sook: fine modifica
            //redirect("shades.php");

        }else{
            fightnav(true,false);
        }

    }
}
page_footer();

?>