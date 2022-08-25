<?php
/*

*/
require_once "common.php";
$titolo = ($session['user']['sex']?"Monsieur":"Madame");
$gems = $session['user']['gems'];
page_header("Il Carrozzone di $titolo Déguise");
output("`%`c`bIl Carrozzone di $titolo Déguise`b`c`n`n");
$session['user']['locazione'] = 116;
if ($_GET['op']=="") {
    output("`%Troppo curios".($session[user][sex]?"a":"o")." di scoprire quali siano le proprietà dei costumi, ti avvii all'interno del carrozzone ".($session[user][sex]?"dell'uomo":"della donna").", continuando la tua perlustrazione. ");
    output("".($session[user][sex]?"Il gitano":"La gitana")." non smette di sorridere mentre ti segue, spiegandoti finalmente l'utilità di quei costumi di cui va tanto fier".($session[user][sex]?"o":"a").". ");
    output("`n`i`0Ho sentito dire che Guido la Guardia diventa ogni giorno più forte, e che a poco serve cercare di distrarlo. Per questo motivo ti dico che i miei costumi sono magici: indossandoli, potrai burlarti di lui facendoti passare per ciò che non sei, ");
    output(" sarà minore il rischio di farti arrestare dallo sceriffo se deciderai di rapinare la banca e Guido inoltre perderà una parte della forza dei suoi possenti muscoli. ");
    output("Certo, non aspettarti miracoli, ma una piccola riduzione sicuramente ci sarà e potrà aiutarti. `n Ovviamente la forza e la durata dell'aiuto che riceverai dipenderanno dalle proprietà magiche del vestito e, naturalmente, dal suo costo. Cosa ne pensi?`i ");
    output("`n`%Piuttosto interessat".($session[user][sex]?"a":"o")." ed indecis".($session[user][sex]?"a":"o")." sul da farsi, lasci che $titolo Déguise ti mostri una parte del suo campionario, tra quelli che ti puoi permettere con le `&gemme`% contenute nel tuo borsellino. ");
       
    addnav("Acquisti");
    if ($session['user']['gems'] < 10){
        addnav("Non hai abbastanza gemme");
    }
    if ($session['user']['gems'] > 9){
        addnav("Costume da Monaco Amanuense (10 gemme)","deguise.php?op=1");
    }
    if ($session['user']['gems'] > 49){
        addnav("".($session[user][sex]?"Costume da giovane Gentiluomo":"Costume da Gentil Donzella")." (50 gemme)","deguise.php?op=2");
    }
    if ($session['user']['gems'] > 99){
        addnav("Costume da Guardia dello Sceriffo (100 gemme)","deguise.php?op=3");
    }
    if ($session['user']['gems'] > 199){
        addnav("Costume da Drago Verde (200 gemme)","deguise.php?op=4");
    }
    addnav("Torna al Campo Zingari","gypsycamp.php");
}elseif ($_GET['op']=="1") {
    output("`&\"`0`iOttima scelta, mi".($session['user']['sex']?"a cara":"o caro")." ".$session['user']['name']."`0, sono certo che Guido avrà qualche problema a riconoscerti alla prossima rapina !`i\"`n`%Detto ciò intasca le `&10 gemme`% che gli porgi e ti consegna il ");
    output("Costume da Monaco Amanuense congedandosi con un deferente inchino.`n`n");
    $session['user']['gems'] -= 10;
    $session['user']['camuffa'] = 1;
    debuglog("spende 10 gemme per un Costume da Monaco Amanuense da $titolo Déguise.");
    addnav("Torna al Campo Zingari","gypsycamp.php");
}elseif ($_GET['op']=="2") {
    output("`&\"`0Ottima scelta, mi".($session['user']['sex']?"a cara":"o caro")." ".$session['user']['name']."`0, sono certo che Guido avrà delle difficoltà a distinguerti alla prossima rapina !`i\"`%`nDetto ciò intasca le `&50 gemme`% che gli porgi e ti consegna il ");
    output("".($session[user][sex]?"Costume da giovane Gentiluomo":"Costume da Gentil Donzella")." congedandosi con un deferente inchino.`n`n");
    $session['user']['gems'] -= 50;
    $session['user']['camuffa'] = 2;
    debuglog("spende 50 gemme per un Costume da Gentil Donzella da $titolo Déguise.");
    addnav("Torna al Campo Zingari","gypsycamp.php");
}elseif ($_GET['op']=="3") {
    output("`&\"`0`iOttima scelta, mi".($session['user']['sex']?"a cara":"o caro")." ".$session['user']['name']."`0, sono certo che Guido avrà grosse difficoltà a distinguerti alla prossima rapina !`i\"`%`nDetto ciò intasca le `&100 gemme`% che gli porgi e ti consegna il ");
    output("Costume da Sceriffo congedandosi con un deferente inchino.`n`n");
    $session['user']['gems'] -= 100;
    $session['user']['camuffa'] = 3;
    debuglog("spende 100 gemme per un Costume da Guardia dello Sceriffo da $titolo Déguise.");
    addnav("Torna al Campo Zingari","gypsycamp.php");
}elseif ($_GET['op']=="4") {
    output("`&\"`0`iOttima scelta, mi".($session['user']['sex']?"a cara":"o caro")." ".$session['user']['name']."`0, sono certo che Guido avrà enormi problemi a riconoscerti alla prossima rapina !`i\"`%`nDetto ciò intasca le `&200 gemme`% che gli porgi e ti consegna il ");
    output("Costume da `@Drago Verde`% congedandosi con un deferente inchino.`n`n");
    $session['user']['gems'] -= 200;
    $session['user']['camuffa'] = 4;
    debuglog("spende 200 gemme per un Costume da Drago Verde da $titolo Déguise.");
    addnav("Torna al Campo Zingari","gypsycamp.php");
}
page_footer();
?>
