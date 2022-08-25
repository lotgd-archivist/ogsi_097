<?php
require_once "common.php";
//checkday();
if (!isset($session)) exit();
//Dimensione del labirinto, che è quadrato
$dimensione=5;
//Turni concessi al player, dovrebbero essere circa la metà di $dimensione*$dimensione
$turniplayer=12;
//unset($session['user']['bio']);
//$session['user']['bio']="";
page_header("Il Labirinto di Teseo");
output("`c<font size='+2'>`^Il Labirinto di Teseo</font>`c`n`n",true);
if ($session['user']['turns']<1 and $_GET[op]==""){
output("`5Sei troppo stanco per affrontare il Labirinto oggi. Riprova un altro giorno.`n");
addnav("Torna al Villaggio","village.php");
}
else if ($session['user']['labi']>$turniplayer and $_GET[op]!="") {
    output("`2Purtroppo hai esaurito i turni che avevi per trovare il tesoro. Fino alla prossima uccisione del ");
    output("drago non potrai affrontare il terribile labirinto, che per questa volta ti ha domato. All'improvviso ");
    output("una forza sconosciuta ti trasporta all'ingresso del Labirinto e l'unica possibilità che ti viene concessa ");
    output("è quella di seguire il sentiero che ti conduce al villaggio.`n");
    //unset($session['user']['bio']);
    //$session['user']['bio']="";
    addnav("Torna al Villaggio","village.php");
}
else if ($_GET[op]=="") {
    if ($ori==0 and $vert==0 and $ori1==0 and $vert1==0){
    output("`2Ti ritrovi all'ingresso di quello che sembra essere un labirinto intricato. Le indicazioni che il vecchietto ");
    output("al villaggio ti ha dato sono state precise. Il sentiero che hai percorso nella foresta ti ha portato alla meta. ");
    output("Ora sta a te decidere. Cosa vuoi fare ?`n");
    }
    //global $ori, $vert, $ori1, $vert1, $ori2, $vert2;
    // generiamo il punto in cui si trova il tesoro
    $ori=(e_rand(1,$dimensione));
    $vert=(e_rand(1,$dimensione));
    // generiamo il punto in cui si trova il nostro esploratore
    $ori1=(e_rand(1,$dimensione));
    $vert1=(e_rand(1,$dimensione));
    //generiamo il punto in cui si trova il burrone
    $ori2=(e_rand(1,$dimensione));
    $vert2=(e_rand(1,$dimensione));
    // Controlliamo che il giocatore non si trovi alla locazione del tesoro o del burrone
    if (($ori1==$ori and $vert1==$vert) or ($ori1==$ori2 and $vert1==$vert2)) redirect("labirinto.php");
    // Controlliamo che il tesoro e il burrone non siano nello stesso punto
    if ($ori==$ori2 and $vert==$vert2) redirect("labirinto.php");
    //$session['user']['bio'] = array();
    $session['user']['lotto1']= $ori; //tesoro
    $session['user']['lotto2']= $vert; //tesoro
    $session['user']['lotto3']= $ori1; //player
    $session['user']['lotto4']= $vert1; //player
    $session['user']['win1']= $ori2; //burrone
    $session['user']['win2']= $vert2; //burrone
    //Per usi di debug
    //output("`6Pos.Player {$session['user']['lotto3']} {$session['user']['lotto4']}  `2Pos.Tesoro {$session['user']['lotto1']}
    //{$session['user']['lotto2']}  `%Pos.Trappola {$session['user']['win1']} {$session['user']['win2']}`n");
    addnav("Entra nel Labirinto","labirinto.php?op=labirinto");
    addnav("Torna al Villaggio","village.php");
}
else if ($_GET[op]=="labirinto") {

output("`2Ti inoltri nel labirinto, convinto di riuscire a domarlo. Appena giri il primo angolo però le tue certezze ");
output("iniziano a vacillare ... le pareti sono tutte uguali, e hai già perso il senso d'orientamento. Ma a questo ");
output("punto è troppo tardi per tornare indietro, devi affrontarlo e trovare il tesoro che contiene, o morire nel tentativo. `n");
$session['user']['turns']-=1;
//Per usi di debug
//output("`6Pos.Player {$session['user']['lotto3']} {$session['user']['lotto4']}  `2Pos.Tesoro {$session['user']['lotto1']}
//{$session['user']['lotto2']}  `%Pos.Trappola {$session['user']['win1']} {$session['user']['win2']}`n");
addnav("Cerca il Tesoro","labirinto.php?op=cerca");
}
else if ($_GET[op]=="cerca") {
    if ($session['user']['labi']>$turniplayer) {
        redirect("labirinto.php?op=fineturni");
    }
//Per usi di debug
//output("`6Pos.Player {$session['user']['lotto3']} {$session['user']['lotto4']}  `2Pos.Tesoro {$session['user']['lotto1']}
//{$session['user']['lotto2']}  `%Pos.Trappola {$session['user']['win1']} {$session['user']['win2']}`n");
output("`!`bDove vuoi andare ? Nord, Sud, Est o Ovest ?`b`n ");
addnav("Nord","labirinto.php?op=nord");
addnav("Sud","labirinto.php?op=sud");
addnav("Est","labirinto.php?op=est");
addnav("Ovest","labirinto.php?op=ovest");
}
else if ($_GET[op]=="nord"){
$session['user']['lotto4']+=1;
if ($session['user']['lotto4']>$dimensione) {
    redirect("labirinto.php?op=margine");
}
if ($session['user']['lotto3']==$session['user']['lotto1'] and $session['user']['lotto4']==$session['user']['lotto2']) redirect("labirinto.php?op=tesoro");
if ($session['user']['lotto3']==$session['user']['win1'] and $session['user']['lotto4']==$session['user']['win2']) redirect("labirinto.php?op=burrone");
$session['user']['labi']+=1;
movimento("Nord");
addnav("Continua","labirinto.php?op=cerca");
}
else if ($_GET[op]=="sud"){
$session['user']['lotto4']-=1;
if ($session['user']['lotto4']==0) {
    redirect("labirinto.php?op=margine");
}
if ($session['user']['lotto1']==$session['user']['lotto3'] and $session['user']['lotto2']==$session['user']['lotto4']) redirect("labirinto.php?op=tesoro");
if ($session['user']['lotto3']==$session['user']['win1'] and $session['user']['lotto4']==$session['user']['win2']) redirect("labirinto.php?op=burrone");
$session['user']['labi']+=1;
movimento("Sud");
addnav("Continua","labirinto.php?op=cerca");
}
else if ($_GET[op]=="est"){
$session['user']['lotto3']+=1;
if ($session['user']['lotto3']>$dimensione){
    redirect("labirinto.php?op=margine");
}
if ($session['user']['lotto1']==$session['user']['lotto3'] and $session['user']['lotto2']==$session['user']['lotto4']) redirect("labirinto.php?op=tesoro");
if ($session['user']['lotto3']==$session['user']['win1'] and $session['user']['lotto4']==$session['user']['win2']) redirect("labirinto.php?op=burrone");
$session['user']['labi']+=1;
movimento("Est");
addnav("Continua","labirinto.php?op=cerca");
}
else if ($_GET[op]=="ovest"){
$session['user']['lotto3']-=1;
if ($session['user']['lotto3']==0) {
    redirect("labirinto.php?op=margine");
}
if ($session['user']['lotto1']==$session['user']['lotto3'] and $session['user']['lotto2']==$session['user']['lotto4']) redirect("labirinto.php?op=tesoro");
if ($session['user']['lotto3']==$session['user']['win1'] and $session['user']['lotto4']==$session['user']['win2']) redirect("labirinto.php?op=burrone");
$session['user']['labi']+=1;
movimento("Ovest");
addnav("Continua","labirinto.php?op=cerca");
}
else if ($_GET[op]=="fineturni") {
output("`2Purtroppo hai esaurito i turni che avevi per trovare il tesoro. Fino alla prossima uccisione del ");
output("drago non potrai affrontare il terribile labirinto, che per questa volta ti ha domato. All'improvviso ");
output("una forza sconosciuta ti trasporta all'ingresso del Labirinto e l'unica possibilità che ti viene concessa ");
output("è quella di seguire il sentiero che ti conduce al villaggio.`n");
addnav("Torna al Villaggio","village.php");
}
else if ($_GET[op]=="margine") {
if ($session['user']['lotto3']>$dimensione){
    $z="orientale";
    $session['user']['lotto3']-=1;
}
if ($session['user']['lotto3']==0){
    $z="occidentale";
    $session['user']['lotto3']+=1;
}
if ($session['user']['lotto4']>$dimensione){
    $z="settentrionale";
    $session['user']['lotto4']-=1;
}
if ($session['user']['lotto4']==0){
    $z="meridionale";
    $session['user']['lotto4']+=1;
}
output("`$ Non puoi proseguire in questa direzione, hai raggiunto il margine `3`b".$z."`b`$ del labirinto. `n");
output("`2Prova in un altra direzione. `n");
addnav("Cerca il Tesoro","labirinto.php?op=cerca");
}
else if ($_GET[op]=="tesoro") {
page_header("Il Tesoro");
output("`c<font size='+2'>`\$Il TESORO del Labirinto !!!</font>`c`n`n",true);
output("`%`bNon riesco a crederci!!!`b`n `@Sei riuscito a trovare il Tesoro del Labirinto!!! `n");
output("`!Davanti ai tuoi occhi si presenta un `^`bTesoro Inestimabile`b`!, fatto di oro e gemme!!!`n`n");
$gembonus=e_rand(2,3);
$goldbonus=e_rand(500,2000);
$session['user']['gold']+=$goldbonus;
$session['user']['gems']+=$gembonus;
output("`#Hai trovato `&`b".$gembonus." gemme`b `#e `b`&".$goldbonus." `b`#pezzi d'oro!!!`n");
addnews("`6".$session['user']['name']." `2ha trovato il tesoro del Labirinto!!");
debuglog("ha trovato $gembonus gemme e $goldbonus pezzi d'oro nel Labirinto");
addnav("Torna al Villaggio","village.php");
}
else if ($_GET[op]="burrone"){
page_header("Il Burrone");
output("`c<font size='+2'>`5Il Burrone !!!</font>`c`n`n",true);
output("`2Purtroppo per la foga di trovare il tesoro, non hai notato il crepaccio davanti ai tuoi piedi e ci sei ");
output("caduto dentro. Mentre precipiti nell'abisso che si fa via via più buio ripensi alla tua cupidigia e ti ");
output("riprometti di prestare maggiore attenzione alla prossima esplorazione. `n`n");
output("`\$`bSei MORTO!!!`b`n");
output("`5`bHai perso tutto il tuo oro!!!`b`n");
output("`!`bHai perso il 10% della tua esperienza!!!`b`n");
switch(e_rand(1,8)){
case 1:
if ($session['user']['gems']>0){
output("`5`bPurtroppo nella caduta ti cade una gemma dalla tasca!!!`b`n");
$session['user']['gems']-=1;
}
case 2: case 3: case 4: case 5: case 6: case 7: case 8:

}
$session['user']['alive']=false;
$session['user']['hitpoints']=0;
$goldbonus=$session['user']['gold'];
$session['user']['gold']=0;
$session['user']['experience']*=0.9;
addnews("`2".$session['user']['name']." `4è morto nel tentativo di trovare il tesoro del Labirinto!!");
debuglog("ha perso $goldbonus pezzi d'oro quando è morto nel Labirinto");
addnav("Terra delle Ombre","shades.php");
}
// Per variare un po i messaggi quando il player si sposta
function movimento($type)
  {
   switch(e_rand(1,7)){
   case 1:
   output("`%Cammini in direzione $type fino all'incrocio successivo, ma non trovi nulla. `n`2Continua la tua ricerca.`n");
   break;
   case 2:
   output("`%Fai qualche passo in direzione $type fino alla diramazione successiva, senza risultati. `n`2Continua la tua ricerca.`n");
   break;
   case 3:
   output("`%Ti dirigi verso $type, ma non noti nulla di interessante. `n`2Continua la tua ricerca.`n");
   break;
   case 4:
   output("`%Ti incammini verso $type, con la speranza di imbatterti nel tesoro, ma non hai fortuna. `n`2Continua la tua ricerca.`n");
   break;
   case 5:
   output("`%Vai verso $type, ma ti ritrovi ad un crocevia identico al precedente. `n`2Continua la tua ricerca.`n");
   break;
   case 6:
   output("`%Procedi a $type, nella speranza di trovare il tesoro, ma non hai fortuna. `n`2Continua la tua ricerca.`n");
   break;
   case 7:
   output("`%Ti sposti in direzione $type, e arrivato all'incrocio successivo ti guardi intorno senza speranza. `n`2Continua la tua ricerca.`n");
   }
  }
page_footer();
?>