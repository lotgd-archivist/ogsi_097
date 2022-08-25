<?php
/**
* Merk l'esploratore
*
* @version 0.1
* @author Sook
*/

page_header("Merk l'esploratore");

if (!isset($session)) exit();

output("`c`b`\$Merk l'esploratore!`b`c",true);

switch($_GET[op]){
    case "":
        $session['user']['specialinc'] = "merk.php";
        output("`n`2Mentre stai girovagando per la foresta, scopri davanti a te un piccolo `3satiro`2, intento a disegnare su di una pergamena.`n");
        output("Ti avvicini incuriosito mentre egli, senza smettere di disegnare, ti rivolge la parola e iniziate così a chiaccherare.`n");
        output("`n\"`(Salute, intrepid".($session[user][sex]?"a":"o")." viandante, Merk è il mio nome, ed esplorare questa foresta è il mio mestiere.`2\" ");
        output("`n`2 \"`6Esploratore? Di questa foresta? Ma non lo sai che è pericoloso? Questa selva oscura pullula di creature pericolose e letali!`2\"");
        output("`n\"`(Non mi fanno paura, io conosco molti modi per proteggermi, se tu mi avessi voluto attaccare non saresti nemmeno riuscit".($session[user][sex]?"a":"o")." a trovarmi... `nE' davvero un luogo affascinante, questa foresta, ed è mio scopo esplorarla tutta!`n");
        output("`(Molti sono ancora i misteri a me celati, eppure io conosco già buona parte dei suoi segreti... `ne se tu sei interessat".($session[user][sex]?"a":"o").", potrei anche rivelartene qualcuno... per il giusto compenso ovviamente, le mie esplorazioni costano!`2\"");
        output("`n`2 \"`6E quali sarebbero questi segreti che saresti disposto a condividere con me ?`2\"");
        output("`n\"`(Uhm ad esempio ci sono dei luoghi leggendari in questa foresta, luoghi che possono nascondere tesori e ricchezze, ma anche creature mostruose, o ancora piacevoli sorprese o grandi avventure... ed io posseggo le mappe per raggiungere questi luoghi favolosi...`2\"");
        output("`n`nNon sai quanto un `3satiro`2 possa essere affidabile, però la proposta potrebbe anche essere interessante. ");
        addnav("Dimmi di più","forest.php?op=mappe");
        addnav("Declina l'Offerta", "forest.php?op=declina");
    break;
    case "declina2":
        output("`n`2Prima o poi riuscirai a trovare anche tu i luoghi che ha scoperto questo `3satiro`2, pensi. O almeno speri...");
    case "declina":
        $session['user']['specialinc']="";
        output("`n`n`2Ne sai troppo poco di questi luoghi, ed il rischio di pagare per informazioni insufficienti o peggio false è alto, quindi non accetti:");
        output("`n`2\"`6Ti ringrazio, Merk l'esploratore, ma per trovare creature da uccidere, non ho bisogno di mappe : la foresta è piena di mostri ed è facile stanarli dai loro nascondigli, le avventure non mi mancano di certo!`2\"");
        output("`n`2Detto questo, ti volti e ti allontani lasciandolo intento al suo lavoro di cartografia.");
        addnav("`@Torna alla Foresta","forest.php");
    break;
    case "mappe":
        $session['user']['specialinc'] = "merk.php";
        //numero di mappe già in possesso al giocatore e calcolo costo nuova mappa (esponenziale)
        $sql = "SELECT * FROM mappe_foresta_player WHERE acctid=".$session['user']['acctid']." ORDER BY luogo";
        $result = db_query($sql) or die(db_error(LINK));
        $mappe_possedute=db_num_rows($result);
        $costo_mappa = 125; //costo acquisto prima mappa
        $j=array(); //vettore che memorizza le mappe già in possesso
        $countrow = db_num_rows($result);
        for ($i=0; $i<$countrow; $i++){
        // for($i=0;$i < db_num_rows($result);$i++){
            $costo_mappa *= 2;
            $row = db_fetch_assoc($result);
            array_push($j,$row[luogo]);
        }
        output("`n`2 \"`6Voglio sapere di più di questi luoghi, puoi davvero prepararmi una mappa che mi consentirà di raggiungerli?`2\"");
        $k=0; //puntatore vettore j
        //determinazione delle mappe in vendita
        $sql = "SELECT * FROM mappe_foresta_luoghi";
        $result = db_query($sql) or die(db_error(LINK));
        if (db_num_rows($result)==0) {
            //nessuna mappa presente nel database
            output("`n`(\"Ahimè! Non ti è consentito l'accesso a nessuno di questi luoghi, appartengono solo agli admin!\"`n`n`2");
        } else {
            output("`n`(\"Ma certamente! `nIo posseggo le mappe di ognuno di questi luoghi leggendari, e in cambio di `&$costo_mappa gemme`( ti consegnerò la copia di una mappa a tua scelta !\"`n`n`2");
            output("<table cellspacing=0 cellpadding=2 align='center'>", true);
            output("<tr class='trhead' align='center'><td></td><td>`bLuogo`b</td><td>Compra</td></tr>",true);

            $countrow = db_num_rows($result);
            for ($i=0; $i<$countrow; $i++){
            //for($i=0;$i < db_num_rows($result);$i++){
                $row = db_fetch_assoc($result);
                if ($row[id]==$j[$k]) {
                    //il giocatore ha già questa mappa
                    output("<tr class='trlight'><td>`2".$row[id]."</td><td>`c`8".$row[nome]."`c</td><td>`7Già in possesso`2</td></tr>",true);
                    $k++;
                } else {
                    if ($session[user][gems]>=$costo_mappa) {
                        //il giocatore non ha la mappa e può comprarla
                        output("<tr class='trdark'><td>`2".$row[id]."</td><td>`c`8".$row[nome]."`c</td><td>`7<A href=forest.php?op=compra&mappa=".$row[id]."&gemme=".$costo_mappa.">Compra </a>`2</td></tr>",true);
                        addnav("","forest.php?op=compra&mappa=".$row[id]."&gemme=".$costo_mappa);
                    } else {
                        output("<tr class='trdark'><td>`2".$row[id]."</td><td>`c`8".$row[nome]."`c</td><td>`7Non hai abbastanza gemme`2</td></tr>",true);
                    }
                }
            } //fine ciclo for
            output("</table>",true);
        }
        addnav("Rinuncia","forest.php?op=declina2");
    break;
    case "compra":
        $sql = "SELECT * FROM mappe_foresta_luoghi WHERE id=".$_GET['mappa'];
        $result = db_query($sql) or die(db_error(LINK));
        $row = db_fetch_assoc($result);
        output("`n`2Decidi di fidarti e porgi a Merk le `&".$_GET['gemme']." gemme `2che il `3satiro`2 ti ha chiesto.`n");
        output("Merk intasca le gemme, prende la mappa che hai scelto, la srotola ed inizia a copiarla su una pergamena.`n");
        output("In pochi minuti la copia della mappa è pronta e te la consegna.`n`n");
        output("Hai guadagnato l'accesso a questo luogo: `V".$row[nome]."`2.`n`n");
        output("Merk riprende il suo lavoro iniziale come se tu non esistessi e quindi, sentendoti fuori luogo, riprendi a girovagare nella foresta.");
        $premi=e_rand(1,3);
        $premio=array(
            1=>"oro",
            2=>"drink",
            3=>"simbolo"
        );
        $sql="INSERT INTO mappe_foresta_player (acctid,luogo,visitato,premio) VALUES ('".$session['user']['acctid']."','".$row[id]."','0','".$premio[$premi]."')";
        db_query($sql) or die(db_error(LINK));
        $session['user']['gems']-=$_GET['gemme'];
        debuglog("compra la mappa ".$_GET['mappa']." da Merk pagandola ".$_GET['gemme']." gemme");
        addnav("`@Torna alla Foresta","forest.php");
    break;
}

page_footer();
?>