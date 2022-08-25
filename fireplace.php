<?php
/*
"name"=>     the Fireplace,
"version"=>  1.0 THIS ONE IS FOR LOGD v097 - check which version of LoGD you run!!,
"author"=>   Robert of Maddnet LoGD,
"category"=> Inn ADD-ON - Holiday mod,
"install"=>  Very easy
"download"=> Available at Dragon Prime - http://dragonprime.net/

This mod offers each player 1 gift on Christmas Day and Valentines Day
-- Just think of how many other occassions or holidays you can add to it!!

Install instructions:
install a field in your db:
ALTER TABLE `accounts` ADD `gift` TINYINT( 1 ) DEFAULT '0' NOT NULL ;

1). Look through the file to see if you need to edit anything
2). open inn.php
    look for:  addnav("Cedrik the Barkeep","inn.php?op=bartender");
    ADD this UNDER it:  if (@file_exists("fireplace.php"))  addnav("the fireplace","fireplace.php");
3). Open user.php
    find:  "seenbard"=>"Heard bard,bool",
    UNDER it add:  "gift"=>"Got free Inn gift?,bool",
3). SAVE and upload inn.php, user.php and fireplace.php into your logd folder

Feel free to edit as you need to but please leave this entire comment tag unedited and in place
*/
require_once "common.php";
$day = 25;
$month = 12;
$year = 2004;
page_header("Locanda della Coda Infuocata");
$session['user']['locazione'] = 127;
$name=$session['user']['name'];
output("`c`b<font size='+1'>`\$Il Caminetto`b`c`n</font>",true);
//rawoutput("<IMG SRC=\"images/fireplace.jpg\"><BR><BR><BR>\n");
output(" Ti avvicini al largo camino che troneggia nella sala - a fianco del quale ci sono molti ceppi pronti per essere arsi ");
output("sul fuoco che scoppietta e lancia piccole scintille infuocate. ");
$days = (int)((mktime (0,0,0,$month,$day,$year) - time(void))/86400);
//output("`nThere are $days days until $day/$month/$year Christmas");
if ($days == 0) {
   output("`n`n`b`2È NATALE !!!! Tantissimi Auguri da parte dello staff di `@Legend of the Green Dragon !!!`b`n`n`0");
}elseif ($days > 0){
   output("`n`n`b`2Mancano ancora $days giorni a Natale !`b`n`n`0");
}
if (date("m-d")=="12-03"){
    output("Oggi è il 3 Dec - 22 giorni prima di Natale");
    addnav("Abbandona il Camino");
    addnav("Torna alla Locanda","inn.php");
}
elseif (date("m-d")=="12-05"){
    output(" Alcuni cittadini si sono radunati attorno al camino e stanno discutendo delle `2Feste `\$Natalizie`&.");
    addnav("Abbandona il Camino");
    addnav("Torna alla Locanda","inn.php");
}
elseif (date("m-d")=="12-08"){
    output(" Alcuni cittadini si sono radunati attorno al camino e stanno discutendo delle `2Feste `\$Natalizie`&.");
    addnav("Abbandona il Camino");
    addnav("Torna alla Locanda","inn.php");
}
elseif (date("m-d")=="12-11"){
    output(" `&Vedi `6Poker e `%Yuril `& che stanno addobbando un `2Albero di Natale `&vicino al camino. ");
    output("Li osservi mentre decorano l'albero con dei deliziosi ornamenti in tema natalizio.");
    addnav("Abbandona il Camino");
    addnav("Torna alla Locanda","inn.php");
}
elseif (date("m-d")=="12-13"){
    output(" `&Alcuni cittadini si sono radunati attorno al camino e stanno cantando dei `2Canti Natalizi`&.");
    addnav("Abbandona il Camino");
    addnav("Torna alla Locanda","inn.php");
}
elseif (date("m-d")=="12-17"){
    output(" Alcuni cittadini si sono radunati attorno al camino e stanno discutendo delle `2Feste `\$Natalizie`&.");
    addnav("Abbandona il Camino");
    addnav("Torna alla Locanda","inn.php");
}
elseif (date("m-d")=="12-20"){
    output("`&Noti `%Yuril `&occupata ad appendere delle calze sulla parete sopra il camino. Mentre ti avvicini per guardare meglio, ");
    output(" puoi vedere che ogni calza ha un biglietto con un nome sopra di essa. HEY! ne sta appendendo una proprio adesso con il TUO nome! ");
    addnav("Abbandona il Camino");
    addnav("Torna alla Locanda","inn.php");
}
elseif (date("m-d")=="12-21"){
    output("`& Osservi tutti i `2c`\$a`2l`\$z`2e`\$t`2t`\$o`2n`\$i `&appesi alla parete sopra il camino. Ti avvicini per ");
    output(" trovare quello con il tuo nome scritto sopra. Ah! Eccolo lì! `n`n");
    addnav("Abbandona il Camino");
    addnav("Torna alla Locanda","inn.php");
}
elseif (date("m-d")=="12-22"){
    output("`& Osservi tutti i `2c`\$a`2l`\$z`2e`\$t`2t`\$o`2n`\$i `&appesi alla parete sopra il camino. Ti avvicini per ");
    output(" trovare quello con il tuo nome scritto sopra. Ah! Eccolo lì! `n`n");
    addnav("Abbandona il Camino");
    addnav("Torna alla Locanda","inn.php");
}
elseif (date("m-d")=="12-23"){
    output("`& Vedi tutti i `2c`\$a`2l`\$z`2e`\$t`2t`\$o`2n`\$i `&sulla parete sopra il caminetto. Guardando più da vicino, ");
    output(" cerchi quello con riportato sopra il tuo nome. Ah! Eccolo lì! `n`n");
    addnav("Abbandona il Camino");
    addnav("Torna alla Locanda","inn.php");
}
elseif (date("m-d")=="12-24"){
    output("`& Osservi tutti i `2c`\$a`2l`\$z`2e`\$t`2t`\$o`2n`\$i `&appesi alla parete sopra il camino. Ti avvicini per ");
    output(" trovare quello con il tuo nome scritto sopra. Ah! Eccolo lì! `n`n");
    output(" `&Alcuni cittadini si sono radunati attorno al camino e stanno cantando dei `2Canti Natalizi`&.");
    addnav("Abbandona il Camino");
    addnav("Torna alla Locanda","inn.php");
}
elseif (date("m-d")=="12-25"){
    if ($session['user']['gift']==0){
    output("`& Osservi tutti i `2c`\$a`2l`\$z`2e`\$t`2t`\$o`2n`\$i `&appesi alla parete sopra il camino. Ti avvicini per ");
    output(" trovare quello con il tuo nome scritto sopra. Ah! Eccolo lì!! `n`n`&Hey! Sembra che la tua calza sia piena!! ");
    output(" `n`nPrendi il calzettone il cui biglietto riporta la scritta `@$name `&sopra di esso e lo stacchi dalla parete. ");
    output("Guardando all'interno trovi ");
    addnav("Abbandona il Camino");
    addnav("Torna alla Locanda","inn.php");
    $session['user']['gift']=1;
    switch (e_rand(1,6)) {
        case 1:
        output(" che qualcuno ti ha mandato 5 Gemme! `nApri il biglietto e scopri che te le manda `\$Admin Excalibur`&. ");
        $session['user']['gems']+=5;
        debuglog(" riceve `^5 gemme `0in regalo al Caminetto della Locanda");
        break;
        case 2:
        output(" che qualcuno ti ha mandato 2 Gemme! `nApri il biglietto e scopri che te le manda `\$Admin Excalibur`&. ");
        $session['user']['gems']+=2;
        debuglog(" riceve `^2 gemme `0in regalo al Caminetto della Locanda");
        break;
        case 3:
        output(" che qualcuno ti ha mandato 5.000 pezzi d'oro! `nApri il biglietto e scopri che te le manda `\$Admin Excalibur`&.  ");
        $session['user']['gold']+=5000;
        debuglog(" riceve `^5000 oro `0in regalo al Caminetto della Locanda");
        break;
        case 4:
        output(" che qualcuno ti ha mandato 3 Gemme! `nApri il biglietto e scopri che te le manda `\$Admin Excalibur`&. ");
        $session['user']['gems']+=3;
        debuglog(" riceve `^3 gemme `0in regalo al Caminetto della Locanda");
        break;
        case 5:
        output(" che qualcuno ti ha mandato 8.000 pezzi d'oro! `nApri il biglietto e scopri che te le manda `\$Admin Excalibur`&. ");
        $session['user']['gold']+=8000;
        debuglog(" riceve `^8000 oro `0in regalo al Caminetto della Locanda");
        break;
        case 6:
        output(" che qualcuno ti ha inviato un cesto pieno di auguri! `nApri il biglietto e scopri che te le manda `\$Admin Excalibur`&. ");
        $buff = array("name"=>"`2Auguri Natalizi`0","rounds"=>100,"wearoff"=>"`4`bLa tua allegria scema!.`b`0","atkmod"=>1.2,"defmod"=>1.2,"roundmsg"=>"`2la tua gioia incrementa le tue abilità di combattimento!`0","activate"=>"offense");
        $session['bufflist']['magicweak']=$buff;
        debuglog(" riceve buff in attacco al Caminetto della Locanda");
        break;
        }
    }else{
    output(" Ti avvicini nuovamente al Caminetto in questo giorno di festa - DOH! hai già svuotato il tuo calzettone!!");
    addnav("Abbandona il Camino");
    addnav("Torna alla Locanda","inn.php");
    }
}
elseif (date("m-d")=="02-13"){
    output("`&Noti che  `%Yuril `&è occupata ad attaccare biglietti di `i`^San Valentino`i `\$rossi a forma di cuore `&sulla parete sopra il Caminetto. Dando un'occhiata da più vicino, ");
    output(" vedi che ogni cuore ha un nome scritto sopra di esso. HEY! ne sta appendendo uno proprio ora con il TUO nome! ");
    addnav("Abbandona il Camino");
    addnav("Torna alla Locanda","inn.php");
}
elseif (date("m-d")=="02-14"){
    if ($session['user']['gift']==0){
    output("Ti avvicini al Caminetto e cerchi il biglietto sui cui è scritto `@$name`& sopra di esso. Ahh! Eccolo lì! ");
    output(" Apri il biglietto e scopri che è il tuo grande amore a mandartelo! Inizi a leggerlo e il biglietto recita,`n`n");
    output(" `\$Le Rose sono Rosse, `%le Viole sono `!blu,`n");
    output(" `^accetta questo dono, che il mio Amore non tramonti più,`&`n");
    addnav("Abbandona il Camino");
    addnav("Torna alla Locanda","inn.php");
    $session['user']['gift']=1;
    switch (e_rand(1,6)) {
        case 1:
        output(" la forza per altri 5 combattimenti!  ");
        $session['user']['turns']+=5;
        debuglog(" riceve 5 turni al Caminetto della Locanda");
        break;
        case 2:
        output(" una deliziosa gemma!  ");
        $session['user']['gems']+=1;
        debuglog(" riceve `^1 gemma `0al Caminetto della Locanda");
        break;
        case 3:
        output(" un bacio appassionato che ti fa guadagnare 10 punti fascino!  ");
        $session['user']['charm']+=10;
        debuglog(" riceve `^10 fascino`0 al Caminetto della Locanda");
        break;
        case 4:
        output(" due gemme bellissime!  ");
        $session['user']['gems']+=2;
        debuglog(" riceve `^2 gemme `0al Caminetto della Locanda");
        break;
        case 5:
        output(" un borsellino con 1.000 pezzi d'oro!  ");
        $session['user']['gold']+=1000;
        debuglog(" riceve `^1000 oro`0 al Caminetto della Locanda");
        break;
        case 6:
        output(" un cesto pieno di allegria!  ");
        $buff = array("name"=>"`2Auguri di S.Valentino`0","rounds"=>75,"wearoff"=>"`4`bLa tua allegria svanisce!.`b`0","atkmod"=>1.2,"defmod"=>1.2,"roundmsg"=>"`2La tua gioia incrementa le tue abilità di combattimento!`0","activate"=>"offense");
        $session['bufflist']['magicweak']=$buff;
        debuglog(" riceve buff in attacco al Caminetto della Locanda");
        break;
        }
    }else{
    output(" Ti avvicini nuovamente al Caminetto in questo giorno di festa - DOH! hai già prelevato il tuo biglietto di S.Valentino!");
    addnav("Abbandona il Camino");
    addnav("Torna alla Locanda","inn.php");
    }
}else{
    output("`n`& Noti quanto `\$caldo `&faccia stare vicino al Caminetto. ");
    addnav("Abbandona il Camino");
    addnav("Torna alla Locanda","inn.php");
}
page_footer();
?>