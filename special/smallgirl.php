<?php
/*
   Small Girl
   Author - Robert of Maddnet
   Italian translation by Excalibur for OGSI
   Forest Event for LotGD 097
   Install - Just drop into your specials folder
*/
if (!isset($session)) exit();
if ($_GET['op']==""){

    $session['user']['specialinc'] = "smallgirl.php";
    switch($session['user']['sex']) {
        case 0:
             output("`n`3Stai camminando lungo un sentiero ben tracciato quando ti imbatti in una giovane fanciulla nella foresta.`n ");
             output("La ragazza è ben vestita, molto carina e ti osserva con aria civettuola in un rapido sbatter di ciglia.  `n");
             output("`n\"`#Mi sono persa, nobile cavaliere, potrebbe cortesemente accompagnarmi in città?`3\"`n`n");
             output("La giovane è molto bella e potrebbe essere una dolce compagnia, ma accompagnarla fino al villaggio ti farà perdere tempo. Cosa decidi di fare ? `n`n");
             addnav("Accompagnala in città","forest.php?op=1");
             addnav("Lasciala sola nella foresta","forest.php?op=2");
        break;
        case 1:
             output("`n`3Stai camminando lungo un sentiero ben tracciato quando ti imbatti in un giovane nella foresta.`n ");
             output("Il ragazzo è ben vestito, molto carino e ti osserva con uno sguardo molto affascinante.  `n");
             output("`n\"`#Mi sono perso, nobile guerriera, potrebbe cortesemente accompagnarmi in città?`3\"`n`n");
             output("Il giovane è molto bello e potrebbe essere una dolce compagnia, ma accompagnarlo fino al villaggio ti farà perdere tempo. Cosa decidi di fare ? `n`n");
             addnav("Accompagnalo in città","forest.php?op=1");
             addnav("Lascialo solo nella foresta","forest.php?op=2");
        break;
    }
}else{
    switch($_GET['op']) {
        case 1:
             $session['user']['turns']--;
             output("`n`3Hai deciso di aiutare ".($session['user']['sex']?"il giovane ":"la giovane fanciulla ")." e insieme arrivate fin sotto le mura del villaggio. `n ");
             output("".($session['user']['sex']?"il giovane":"la giovane")." ti è riconoscente per per dimostrare la sua gratitudine ti porge una pietra luccicante che dice di aver trovato nella foresta. `n`n ");
             output("Osservando attentamente la pietra scopri che ...`n`^");
             $caso = e_rand(1,100) ;
             // Si forza variabile $caso a 1 per premiare giocatori con meno di 4 gemme in tasca
             if ($session['user']['gems'] <=3){
                $caso = 1;
             }
             //$caso=94;
             if ($caso <= 10 ){
                 output("la pietra rilucente è una gemma! `n`n`2Ringrazi per il prezioso dono ricevuto e torni nella foresta felice di aver compiuto un gesto nobile. ");
                 $session['user']['gems']+=1;
                 debuglog("Riceve 1 gemma accompagnando il/la giovane al villaggio");
             }elseif ($caso >= 11 AND $caso <= 90 ){
                 output("la pietra è sicuramente rilucente, ma a parte ciò non c'è nulla di speciale in essa. `n`n`2Ringrazi comunque per il dono ricevuto e torni nella foresta felice di aver compiuto un gesto nobile. ");
             }elseif ($caso >= 91 AND $caso <= 95 ){
                 output("la pietra è sicuramente rilucente ma a parte ciò non c'è nulla di speciale in essa. `n`n");
                 output("`3All'ingresso del villaggio incontrate ".($session['user']['sex']?"le sorelle del giovane ":"i fratelli della giovane")." che ti guardano con aria minacciosa. `n ");
                 output("A torto sono convint".($session['user']['sex']?"e":"i")." che tu abbia approfittato dell'occasione per fare ".($session['user']['sex']?"la gatta morta":"il cascamorto")." e ti assalgono per darti una sonora lezione. `n`n ");
                 $hitpoints_persi = intval(e_rand(0,($session['user']['hitpoints']/2)));
                 $session['user']['hitpoints'] -= $hitpoints_persi;
                 if ($hitpoints_persi == 0 ) {
                     output("`^Per tua fortuna intuisci le loro cattive intenzioni e riesci a fuggire senza danni nascondendoti velocemente nel folto della foresta!");
                 }else {
                     output("`^Hai subito ferite per `%$hitpoints_persi hitpoints!");
                 }
             }elseif ($caso >= 96 ){
                 output("la pietra risplende di una pallida luce e mentre la stringi tra le mani, un caldo tepore ");
                 output("riscalda il tuo corpo. `n`n`2Ringrazi per il prezioso dono ricevuto e torni nella foresta ");
                 output("felice di aver compiuto un gesto nobile.`n");
                 $session['user']['charm']++;
                 $session['user']['hitpoints']++;
                 debuglog("Guadagna 1 fascino e 1 HP accompagnando il/la giovane al villaggio");
             }
        break;
        case 2:
             output("`n`n`2Non volendo perdere del tempo per accompagnare ".($session['user']['sex']?"il giovane, lo ":"la giovane fanciulla, la ")." abbandoni e continui per la tua strada. ");
             output("`n`nMentre ti allontani vieni colpito dietro la testa da una piccola pietra. ".($session['user']['sex']?"Il giovane, stizzito ":"La giovane fanciulla, stizzita ")."per la mancanza di cortesia che hai dimostrato, ");
             output("`nprima di scomparire dalla tua vista nascondendosi nel folto degli alberi ha deciso di vendicarsi colpendoti a tradimento. ");
             $session['user']['charm']--;
             debuglog("Perde 1 fascino lasciando il/la giovane nella foresta");
             if ($session['user']['hitpoints']>=6){
                 output("`n`nTi accorgi inoltre che la pietra ti ha ferito più seriamente di quanto pensavi! ");
                 $hitpoints_persi = intval($session['user']['hitpoints']/2);
                 $session['user']['hitpoints'] -= $hitpoints_persi;
                 if ($hitpoints_persi != 0 ){
                     output("`n`n`^Hai subito ferite per `%$hitpoints_persi HitPoints!");
                 }
             }
        break;
    }
}
?>