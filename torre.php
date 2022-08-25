<?php
require_once "common.php";
$cost = $session['user']['level']*20;
$charm = $session['user']['charm']; //luke
$caso = e_rand(0,10);
$fort = e_rand(0,5);

if ($_GET['op']=="entra"){

        page_header("Entrata della Torre");
        $session['user']['locazione'] = 183;

        if ($caso <= 7) {
         output("`5Sei nella stanza a piano terra, la grande stanza circolare è illuminata da globi di vetro
         magicamente luminosi, una massiccia porta di legno da verso l'esterno.");
         output("`5`nMeravigliato da tanto potere ti guardi intorno e noti due scale circolari che corrono lungo
         il perimetro della struttura. Una sale e l'altra scende.");
            addnav("S?Vai Su","torre.php?op=sali");
            addnav("G?Vai Giù","torre.php?op=scendi");
            addnav("F?Fuggi","torre.php?op=scappa");
                }else{
        output("`5Entri nella torre passando per una massiccia porta di legno, la grande stanza circolare è
        illuminata da globi di vetro magicamente luminosi.");
        output("`5`nMeravigliato da tanto potere ti guardi intorno,noti una figura evanescente prendere forma nel
        centro della stanza.");
        if ($fort <= 3) {
        output("`5Minaccioso lo spettro, si avvicina velocemente, cercando di colpirti con i suoi artigli incorporei.
        D'istinto eviti il colpo ma un freddo glaciale ti pervade, non resta altro da fare che combattere");
        addnav("Attacca lo Spettro","spettro.php");
        }else{
        output("`5Minaccioso lo spettro, si avvicina velocemente, cercando di colpirti con i suoi artigli incorporei.
        D'istinto eviti il colpo ma un freddo glaciale di pervade, la paura vince il tuo coraggio e inizi a scappare");
        addnav("Scappa","torre.php?op=scappa");
        }
        }
}elseif ($_GET['op']=="parla"){
        page_header("Il discorso del mago");
             output("`5Arrivi in prossimità del mago e lo saluti. Lui ti guarda intensamente e dice :");
        if ($session['user']['darkarts']>=1) {
         output("`5`nBenvenuto collega, vai ad ampliare le tue conoscenze nella torre.");
        addnav("Entra","torre.php?op=entra");
            }else{
             output("`5`nVattene questo non è un posto per tè.");
             output("`#`nCosa fai.");
            addnav("Attacca il Mago","magonero.php");
            addnav("Scappa","torre.php?op=scappa");
}
}elseif ($_GET['op']=="scendi"){
        if ($fort == 0) {
        page_header("I Sotterranei");
        output("`5 Entri in una stanza buia, non vedi nulla.");
        output("`5`nAccendi una torcia oppure sali al piano di sopra.");
        addnav("Sali","torre.php?op=entra");
        addnav("Accendi la Torcia","torre.php?op=torcia");

        }elseif ($fort == 1){
        page_header("I sotterranei");
        output("`5 Entri in una stanza debolmente illuminata completamente vuota.");
        addnav("Sali","torre.php?op=entra");

        }elseif ($fort == 2){
        page_header("I sotterranei");
        output("`5 Entri in una stanza debolmente illuminata un mago vestito di nero sta leggendo un tomo.
        Appena ti vede ti dice: `#Vieni pure AMICO, ho bisogno di un aiuto con questo incantesimo.");
        output("`5 Il mago inizia a fare dei gesti con le mani e a cantilenare una melodia.");
        output("`#`nCosa fai.");
        addnav("Attacca il Mago","magonero.php");
        addnav("Scappa","torre.php?op=scappa");

        }elseif ($fort == 3){
        page_header("La trappola");
        output("`5Prosegui e ad un certo punto ti sparisce il terreno sotto i piedi ..... CADI.`n");
        output("`5Nella caduta perdi tutti i soldi e tutte le gemme che avevi con te`n`n.");
        output("Sei morto!!!`n");
        output("Hai però imparato qualche cosa, hai guadagnato $exp punti esperienza.`n`n");
        output("`3Potrai continuare a giocare domani`n");
        debuglog("è morto cadendo nei pressi delle Torre Nera e ha perso ".$session['user']['gold']." oro e ".$session['user']['gems']." gemme");
        $session['user']['alive']=false;
        $session['user']['hitpoints']=0;
        $session['user']['gold']=0;
        $session['user']['gems']=0;
        addnav("Terra delle Ombre","shades.php");

        }elseif ($fort == 4){
        page_header("I Sotterranei");
        output(".`nLa stanza è vuota fatta eccezione per un leggio al centro che sostiene un libro. ");
        output("`nIncuriosito inizi a leggere il libro. Dopo molte ore di lettura hai appreso molte cose sulla
        segreta arte della magia, ma hai perso un combattimento nella foresta.");
        $session['user']['turns']-=1;
        $session['user']['darkarts']+=2;
        $session['user']['quest'] += 2;
         output(".`nStanco decidi di tornare in città");
        addnav("Torna al Villaggio","village.php");

        }elseif ($fort == 5){
        page_header("I sotterranei");
         output(".`nUna risata terribile risuona per tutta la torre. Dopo alcuni secondi di silenzio una voce tuona. ");
         output(".`n`\$STO ARRIVANDO NON AVRAI VIA DI SCAMPO. ");
        addnav("Sali","demone.php");
         addnav("Scappa","demone.php");
        addnav("Torna al Villaggio","demone.php");
}
}elseif ($_GET['op']=="torcia"){

        page_header("La Sala del Macellaio");
        output("`5 Appena accendi la torcia, ti appare uno spettacolo raccapricciante, corpi mutilati e sezionati
        sono appoggiati in diversi punti della stanza. ");
        output("`n  Al centro in piedi noti un corpo ricucito che dondola, ti guarda con occhi fiammeggianti, sembra
        molto potente e una forte magia si sprigiona dallo zombie. ");
        output("`5`nCosa fai ?");
        addnav("Attacca lo Zombie","zombie.php");
        addnav("Scappa","torre.php?op=scappa");


}elseif ($_GET['op']=="sali"){
    page_header("L'Esame");
    output("`5Raggiungi una stanza piccola con due porte. Un elfo siede su una poltrona.`n");
    output("`5Sono Pitrick, se sei venuto qui cerchi gloria o ricchezza, se vuoi la gloria entra nella porta rossa,
    se vuoi la ricchezza entra nella porta nera.`n");
    addnav("Gloria","torre.php?op=gloria");
    addnav("Ricchezza","torre.php?op=ricchezza");
    addnav("Scappa","torre.php?op=scappa");

}elseif ($_GET['op']=="gloria"){
        page_header("Gloria");
    $expe=$session['user']['experience']*0.3;
    if ($caso <= 3) {
       output("Un incantesimo ti trasporta attreverso lo spazio e il tempo e ti trovi all'entrata di una grotta.
       La gloria dovrai conquistartela!`n");
       $session['user']['quest'] += 3;
       output("`5`nCosa fai.");
       addnav("Scappa","torre.php?op=scappa");
       addnav("Entra nella grotta","dragon.php");
    }elseif ($caso == 4) {
       output("Hai trovato un <big>`^PEGASO`0</big> che è pronto a seguirti!`nLo vuoi accettare?",true);
       debuglog("con il culo che si ritrova vince un PEGASO alla Torre Nera");
       $session['user']['quest'] += 3;
       addnav("Accetta il Pegaso","torre.php?op=pegaso");
       addnav("Rifiuta. Torna al Villaggio","village.php");
    }else{
       output("Gli dei puniscono gli arroganti, non meriti la gloria.`n");
       $session['user']['experience'] -=$expe;
       output("Hai perso il 30% della tua esperienza: $expe.`n");
       $session['user']['quest'] += 1;
       debuglog("perde il 30% di esperienza alla Torre Nera");
       addnav("Torna al Villaggio","village.php");
    }
}elseif ($_GET['op']=="ricchezza"){
        page_header("Ricchezza");
    $expe=$session['user']['experience']*0.3;
    if ($caso <= 3) {
    output("Trovi un sacco pieno di pezzi d'oro.`n");
    $session['user']['quest'] += 2;
    $session['user']['gold'] += 2000;
    debuglog("trova 2000 oro alla Torre Nera");
    addnav("Torna al Villaggio","village.php");
    }elseif ($caso == 4) {
    output("Trovi un forziere pieno d'oro.`n");
    $session['user']['quest'] += 3;
    $session['user']['gold'] += 20000;
    debuglog("trova 20000 oro alla Torre Nera");
    addnav("Torna al Villaggio","village.php");
    }else{
    output("Gli dei puniscono gli arroganti, non meriti la ricchezza.`n");
    $session['user']['experience'] -=$expe;
    output("Hai perso il 30% della tua esperienza: $expe.`n");
    $session['user']['quest'] += 1;
    debuglog("perde il 30% di esperienza alla Torre Nera");
    addnav("Torna al Villaggio","village.php");
    }
}elseif ($_GET['op']=="scappa"){
        page_header("Scappi come una Bimba Impaurita.");
        output("Ti volti e inizi a correre verso il villaggio.`n");
        output("Hai sprecato del tempo.");
    addnav("Torna al Villaggio","village.php");
    $session['user']['quest'] += 1;
    output("`5`nCosa fai.");
}elseif ($_GET['op']=="pegaso"){
    output("Un <big>`^PEGASO`0</big> da oggi sarà al tuo fianco!!!`n",true);
    debuglog("ha accettato il PEGASO alla Torre Nera");        
    $session['user']['hashorse'] = 13;
    $session['user']['mountname'] = "";
    $sql = "SELECT * FROM mounts WHERE mountid=13";
    $result = db_query($sql) or die(db_error(LINK));
    $mount = db_fetch_assoc($result);
    $session['bufflist']['mount'] = unserialize($mount['mountbuff']);
    addnav("Torna al Villaggio","village.php");
}else{
if ($caso== 1 or $caso==2){
    page_header("Il Mago Nero");
    output("`5Stai attraversando la radura, cammini per quasi 10 minuti finchè in lontananza noti una figura vestita
    di nero che ti stà attendendo,`\$sembrerebbe un mago vestito di nero.");
    output("`5`nCosa fai.");
    addnav("Parla al Mago","torre.php?op=parla");
    addnav("Attacca il Mago","magonero.php");
    addnav("Scappa","torre.php?op=scappa");

}elseif ($caso== 3 or $caso==4){
    page_header("L'incantesimo");
    output("`5Stai attraversando la radura, cammini per quasi 10 minuti finchè in lontananza noti una figura vestita
    di nero che muove le braccia in modo strano e sembra stia parlando.`\$Guardando meglio vedi che è un mago vestito
    di nero.");
    output("`nE ti sta lanciando contro un incantesimo.");
    output("`5`nTi senti debole, le palpebre pesanti si chiudono da sole, `#ti stai addormentando.");
    debuglog("perde ".$session['user']['gold']." oro con il Mago Nero alla Torre Nera.");
    $session['user']['gold']=0;
    $session['user']['quest'] += 1;
        addnav("Torna al Villaggio","village.php");


}elseif ($caso== 5 or $caso==6){
    page_header("La trappola");
    output("`5Prosegui  a un certo punto ti sparisce il terreno sotto i piedi ..... CADI.`n");
    output("`5Nella caduta perdi tutti i soldi e tutte le gemme che avevi con te`n`n.");
    output("`5Sei morto.`n");
    output("Almeno hai imparato qualche cosa riguardo alle grotte guadagnato $exp punti esperienza.`n`n");
    output("`3Puoi continuare a giocare domani`n");
    debuglog("è morto cadendo nei pressi delle Torre Nera e ha perso ".$session['user']['gold']." oro e ".$session['user']['gems']." gemme");
                $session['user']['alive']=false;
                $session['user']['hitpoints']=0;
                $session['user']['gold']=0;
                $session['user']['gems']=0;
    addnav("Terra delle Ombre","shades.php");

}elseif ($caso== 7 or $caso==8){
    page_header("L'entrata");
    output("`5Stai attraversando la radura, cammini per quasi 10 minuti finchè in lontananza vedi,`$ l'entrata della
    torre.");
    output("`5`nLa torre ti mette brividi è veramente orrenda.");
    output("`5`nCosa fai.");
    addnav("Entra","torre.php?op=entra");
    addnav("Scappa","torre.php?op=scappa");


}elseif ($caso== 9 or $caso==10){
    page_header("La magia");
    output("`5Stai attraversando la radura, cammini per quasi 4 ore ,`\$l'entrata della torre sembra non avvicinarsi mai !!");
    output("`5`nLa torre ti mette brividi è veramente orrenda.Il sole sta tramontando e decidi di tornare al villaggio,
    tornerai un altro giorno");
    addnav("Torna al Villaggio","village.php");

}elseif ($caso== 0){
    page_header("Il fulmine");
    output("`5Stai attraversando la radura. Cammini per quasi 30 minuti quando `\$un fulmine cade dal cielo e ti colpisce!!");
    output("`5`nLa torre ti mette i brividi, è veramente orrenda. Mentre muori vedi il cielo sereno e sorridi. ");
    debuglog("muore colpito da un fulmine e perde ".$session['user']['gold']." vicino alla Torre Nera");
    addnav("Terra delle Ombre","shades.php");
    $session['user']['alive']=false;
    $session['user']['hitpoints']=0;
    $session['user']['gold']=0;
}
}
page_footer();
?>