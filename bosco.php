<?php
require_once("common.php");
// This idea is Imusade's from lotgd.net
checkday();
$session['user']['locazione'] = 111;
if ($session['user']['superuser'] > 2){
        output("`n`b`c`2La costruzione`0`c`b");
        output("`n`#Tra le piante in lontananza noti una piccola radura.");
        output("`n Cosa fai torni al villaggio, oppure esplori le costruzioni  ?`n ");
        addnav("Esplora","cimitero.php");
        output("`n`b`c`2La Strana Cosa`0`c`b");
        output("`n`#Tra le piante noti qualche cosa che ti osserva, inseguirla potrebbe essere fonte di guai ma di sicure avventure.");
        output("`n Cosa fai, la insegui oppure scappi al villaggio ?`n ");
        addnav("Insegui la cosa","lacosa.php");
        output("`n`b`c`2Il Test`0`c`b");
        output("`6Mentre girovaghi nella foresta alla ricerca di creature da massacrare per ottenere il tanto agognato ");
        output("oro, noti un riflesso dorato provenire da dietro un gruppo di alberi. `nVuoi avvicinarti per scoprire ");
        output("di che si tratta ?");
        addnav("Avvicinati al bagliore","iltest.php");
        output("`n`b`c`2La selva`0`c`b");
        output("`n`#Il solito sentiero che spesso hai seguito verso sud stavolta vira ad est nei pressi di castel Excalibur.");
        output("`n Prosegui ?`n ");
        addnav("Esplora la zona","selva.php");
        output("`n`b`c`#Il Maniero`0`c`b");
        output("`n`#Ti ritrovi di fronte ad una costruzione fortificata, che sai essere il Municipio di Rafflingate.");
        output("`n Vuoi proseguire ?`n ");
        addnav("Avvicinati","maniero.php");
        output("`n`b`c`2Il sentiero verso sud`0`c`b");
        output("`n`#L'esperienza maturata nei duri scontri ti fa notare un sentiero nascosto che si muove verso sud.");
        output("`n Cosa fai, segui il sentiero ?`n ");
        addnav("Segui il sentiero","sentiero.php");
        output("`n`b`c`2Il vecchietto loquace`0`c`b");
        output("`2Al margine del bosco noti un vecchietto che fuma la pipa appoggiato ad un albero. `nMentre lo guardi ");
        output("egli ti fa cenno di avvicinarti. Giunto vicino a lui, il vecchietto inizia a parlare:`n`n`#\"Ti interessa ");
        output("la mappa per raggiungere il mitico `^`bLabirinto di Teseo`b`# ? Si dice che vi sia celato un tesoro di ");
        output("enorme valore. Io non ho più l'età per avventurarmi nella foresta, ma tu mi sei simpatico e te la regalo\".`n`n");
        output("`2Hai il fegato per seguire le indicazioni della mappa ?`n ");
        addnav("Prendi la Mappa","labirinto.php");
        output("`n`b`c`2La Scorta`0`c`b");
        output("`3Dopo un inseguimento durato settimane lo sceriffo è riuscito a catturare un pericolo individuo`n che ");
        output("si fa chiamare `\$Il Negromante`3. Adesso dovrà scortarlo alla prigione di `&Castel Excalibur`3 perchè`n ");
        output("sia giudicato per i suoi misfatti. Purtroppo la scorta è già impegnata in un'altra contea, per cui `nlo ");
        output("Sceriffo ti chiede se ti senti abbastanza coraggioso per svolgere questo compito per lui. `nLa sua mano ");
        output("si tende verso la tua, con in mano la chiave della cella che rinchiude il pericoloso `\$Negromante`3.`n");
        output("Cosa decidi, accetti il pericoloso incarico ?");
        addnav("Scorta il Negromante","scorta.php");
        include("redeglielfi.php"); 
}
if ($session['user']['dragonkills']>0 AND $session['user']['quest']<3){
    page_header("Il Bosco a Sud");
    if ($session['user']['dragonkills']==1 OR $session['user']['dragonkills']==5 OR $session['user']['dragonkills']==10){
        page_header("Una Strana Costruzione ");
        output("`n`b`c`2La costruzione`0`c`b");
        output("`n`#Tra le piante in lontananza noti una piccola radura.");
        output("`n Cosa fai torni al villaggio, oppure esplori le costruzioni  ?`n ");
        addnav("Esplora","cimitero.php");
    }elseif ($session['user']['dragonkills']==2 OR $session['user']['dragonkills']==6 OR $session['user']['dragonkills']==12 ){
        page_header("Una Strana Cosa");
        output("`n`b`c`2La Strana Cosa`0`c`b");
        output("`n`#Tra le piante noti qualche cosa che ti osserva, inseguirla potrebbe essere fonte di guai ma di sicure avventure.");
        output("`n Cosa fai, la insegui oppure scappi al villaggio ?`n ");
        addnav("Insegui la cosa","lacosa.php");
    }elseif ($session['user']['dragonkills']==4 OR $session['user']['dragonkills']==8 OR $session['user']['dragonkills']==16){
        page_header("Il Test");
        output("`n`b`c`2Il Test`0`c`b");
        output("`6Mentre girovaghi nella foresta alla ricerca di creature da massacrare per ottenere il tanto agognato ");
        output("oro, noti un riflesso dorato provenire da dietro un gruppo di alberi. `nVuoi avvicinarti per scoprire ");
        output("di che si tratta ?");
        addnav("Avvicinati al bagliore","iltest.php");
    }elseif ($session['user']['dragonkills']==9 OR $session['user']['dragonkills']==18) {
	    include("redeglielfi.php");    
    }elseif ($session['user']['dragonkills']==3 OR $session['user']['dragonkills']==7 OR $session['user']['dragonkills']==14){
        page_header("La Selva");
        output("`n`b`c`2La selva`0`c`b");
        output("`n`#Il solito sentiero che spesso hai seguito verso sud stavolta vira ad est nei pressi di castel Excalibur.");
        output("`n Prosegui ?`n ");
        addnav("Esplora la zona","selva.php");
    }elseif ($session['user']['dragonkills']==11 OR $session['user']['dragonkills']==15 OR $session['user']['dragonkills']==17){
        page_header("Il Maniero");
        output("`n`b`c`#Il Maniero`0`c`b");
        output("`n`#Ti ritrovi di fronte ad una costruzione fortificata, che sai essere il Municipio di Rafflingate.");
        output("`n Vuoi proseguire ?`n ");
        addnav("Avvicinati","maniero.php");
    }elseif ($session['user']['level']==12 OR $session['user']['level']==11){
        page_header("Sentiero a Sud");
        output("`n`b`c`2Il sentiero verso sud`0`c`b");
        output("`n`#L'esperienza maturata nei duri scontri ti fa notare un sentiero nascosto che si muove verso sud.");
        output("`n Cosa fai, segui il sentiero ?`n ");
        addnav("Segui il sentiero","sentiero.php");
    }elseif ($session['user']['level']==5){
        page_header("Il Vecchietto Loquace");
        output("`n`b`c`2Il vecchietto loquace`0`c`b");
        output("`2Al margine del bosco noti un vecchietto che fuma la pipa appoggiato ad un albero. `nMentre lo guardi ");
        output("egli ti fa cenno di avvicinarti. Giunto vicino a lui, il vecchietto inizia a parlare:`n`n`#\"Ti interessa ");
        output("la mappa per raggiungere il mitico `^`bLabirinto di Teseo`b`# ? Si dice che vi sia celato un tesoro di ");
        output("enorme valore. Io non ho più l'età per avventurarmi nella foresta, ma tu mi sei simpatico e te la regalo\".`n`n");
        output("`2Hai il fegato per seguire le indicazioni della mappa ?`n ");
        addnav("Prendi la Mappa","labirinto.php");
    }elseif ($session['user']['level']==3){
        page_header("La Scorta");
        output("`n`b`c`2La Scorta`0`c`b");
        output("`3Dopo un inseguimento durato settimane lo sceriffo è riuscito a catturare un pericolo individuo`n che ");
        output("si fa chiamare `\$Il Negromante`3. Adesso dovrà scortarlo alla prigione di `&Castel Excalibur`3 perchè`n ");
        output("sia giudicato per i suoi misfatti. Purtroppo la scorta è già impegnata in un'altra contea, per cui `nlo ");
        output("Sceriffo ti chiede se ti senti abbastanza coraggioso per svolgere questo compito per lui. `nLa sua mano ");
        output("si tende verso la tua, con in mano la chiave della cella che rinchiude il pericoloso `\$Negromante`3.`n");
        output("Cosa decidi, accetti il pericoloso incarico ?");
        addnav("Scorta il Negromante","scorta.php");
    }else{
        page_header("Il Bosco a Sud");
        output("`n`b`c`2Il Bosco a Sud`0`c`b");
        output("`n`n`4Trovi una radura tranquilla ti siedi a riposare.");
        output("`n`n");
    }
} else{
    page_header("Il Bosco a Sud");
    output("`n`b`c`2Il Bosco a Sud`0`c`b");
    output("`n`n`4Trovi una radura tranquilla ti siedi a riposare.");
    output("`n`n");
   }
addnav("Torna al Villaggio","village.php");
page_footer();
?>