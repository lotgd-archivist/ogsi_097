<?php
if ($session['user']['potion']<5 and $session['user']['dragonkills']<10){
    output("`@Mentre ti inoltri nella foresta intravedi davanti a te un carro coperto del tipo comunemente usato ");
    output("dai mercanti girovaghi. Incuriosito vai a vedere più da vicino e vieni interpellato dal conduttore del ");
    output("carro, un uomo anziano vestito con un lungo mantello e un cappello a cono, che ti spiega che si stava ");
    output("recando a Rafflingate quando una ruota è rimasta accidentalmente bloccata in una buca e i due muli ai ");
    output("quali il carro è aggiogato non sembrano essere abbastanza forti per uscire dall'impaccio. Dato che ");
    output("l'uomo ti ha ispirato simpatia fin dal primo istante decidi di aiutarlo ... `n`nIl compito si rivela ");
    output("particolarmente duro dato che, anche se tu da parte tua spingi come un dannato i due animali non ");
    output("sembrano molto disposti a collaborare ma alla fine riesci a liberare la ruota.`n`nAl termine del lavoro ");
    output("vieni ringraziato calorosamente dell'uomo che ti prega ti attendere ancora un minuto prima di scomparire ");
    output("all'interno del carro, riemergendone qualche istante dopo con in mano una `%Pozione Guaritrice `@che ti ");
    output("dona in cambio del tuo prezioso aiuto.`n`nMentre il conduttore del carro riprende il suo viaggio ti ");
    output("saluta con un cenno della mano che tu ricambi mentre a tua volta riprendi il tuo girovagare in foresta.`n");
    $session['user']['potion']+=1;
    addnav("`@Torna alla Foresta","forest.php");
    $session['user']['specialinc']="";
}else{
    output("`XMentre ti inoltri nella foresta intravedi davanti a te un carro coperto del tipo comunemente usato ");
    output("dai mercanti girovaghi. Incuriosito vai a vedere più da vicino per scoprire soltanto che il carro giace ");
    output("abbandonato in quel luogo da lungo tempo e, a parte alcune cianfrusaglie, non continene nulla di ");
    output("valore.`n`nIrritato da questa constatazione decidi di tornare in foresta senza perdere altro tempo e ");
    output("di sfogare la tua rabbia sul primo mostro tanto stupido da capitare sulla tua strada.`n");
    addnav("Trova qualcosa da uccidere","forest.php?op=search");
    $session['user']['specialinc']="";
}
?>