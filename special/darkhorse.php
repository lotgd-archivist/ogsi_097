<?php
if (!isset($session)) exit();
// The addition of the commentary is handled by the forest.php
// addcommentary();
output("`c`b<span style='color: #787878'>Dark Horse Tavern`b`c",true);
$session['user']['specialinc']="darkhorse.php";
switch($_GET['op']){
case "":
    checkday();
    output("Un gruppo di alberi qui vicino ti sembra familiare... Sei sicuro di aver già visto questo posto.  ");
    output("Mentre ti avvicini alla macchia, una strana nebbia ti circonda; la tua mente comincia a ronzare, ");
    output("e non sei più del tutto sicuro di come sei arrivato qui");
    if ($session['user']['hashorse']) output(", ma sembra che il tuo cavallo conoscesse la strada"); // BoarVolk's idea
    output(".`n`nLa nebbia si alza, e vedi davanti a te ");
    output("una costruzione di legno con del fumo che esce dal camino. Un´insegna sopra la porta dice `7\"Taverna del Cavallo Nero.\"`0");
    addnav("`@Entra nella taverna","forest.php?op=tavern");
    addnav("`\$Lascia questo luogo","forest.php?op=leaveleave");
    $session['user']['specialinc']="darkhorse.php";
    break;
case "tavern":
    checkday();
    output("Sei vicino all´entrata della taverna e osservi la scena davanti a te. Laddove molte taverne ");
    output("sono roche e chiassose, questa è tranquilla e quasi vuota. Nell´angolo, un vecchietto gioca con ");
    output("dei dadi. Noti che su tutti i tavoli ci sono incisioni fatte dagli avventurieri che hanno trovato ");
    output("questo posto prima di adesso, e dietro il bancone si aggira un uomo, intento a lucidare i bicchieri, ");
    output("come se ci fosse qualcuno che potesse usarli.");

    if ($session['user']['vecchio'] == 0) addnav("V?`6Parla al Vecchietto","forest.php?op=oldman");
    addnav("B?`6Parla al Barista","forest.php?op=bartender");
    addnav("T?`2Esamina i Tavoli","forest.php?op=tables");
    addnav("E?`\$Esci dalla taverna","forest.php?op=leave");
    $session['user']['specialinc']="darkhorse.php";
    break;
case "tables":
    output("Esamini le incisioni sui tavoli:`n`n");
    viewcommentary("darkhorse","Aggiungi la tua incisione:");
    addnav("`7Torna alla taverna","forest.php?op=tavern");
    break;
case "bartender":
    if ($_GET['what']==""){
        output("L´uomo brizzolato dietro il bancone ti ricorda molto una costoletta di manzo essiccato.`n`n");
        output("\"`7Dimmi, che posssho fare per te ".($session['user']['sex']?"ragassho":"ragassha")."?`0\" domanda lo sdentato ");
        output("barista.  \"`7Quelli come te non sshi vedono sshpesssho da quesshte parti.`0\"");
        addnav("N?`\$Chiedi dei tuoi Nemici","forest.php?op=bartender&what=enemies");
        addnav("C?`&Chiedi `%dei `#colori","forest.php?op=bartender&what=colors");
        //addnav("Compra sciacquabudella","forest.php?op=bartender&what=swill");

        //Modifica Uovo d'Oro
        addnav("U?`^L’Uovo d’Oro","forest.php?op=bartender&what=egg");
    }else if($_GET[what]=="egg"){
          output("\"`7mmmh, allora voreshti sapere qualcosha a prposito dell’uovo d’oro. Ach, è una legenda molto anttica. Sshi dice che chhi possshiede l’uovo d’oro è in grado di shcivare la morte. ");
          output("Inoltre queshto uovo sharebbe la chiavve per raggiunghere una terapeuta di nome Golinda. Bè io non ci credo.");
          if (getsetting("hasegg",0)==0){
             output(" Nessuno ha mai trovato questo uovo.");
          } else {
            $sql = "SELECT acctid,name FROM accounts WHERE acctid = '".getsetting("hasegg",0)."'";
            $result = db_query($sql) or die(db_error(LINK));
            $row = db_fetch_assoc($result);
            $uovo1 = $row['name'];
            $sql = "SELECT acctid,name FROM accounts WHERE acctid = '".getsetting("hasblackegg",0)."'";
            $result = db_query($sql) or die(db_error(LINK));
            $row = db_fetch_assoc($result);
            $uovo2 = $row['name'];
            if(e_rand(0,1)==0){
                $nome=$uovo1;
            }else{
                $nome=$uovo2;
            }
            output("`0\" Inizia a sussurare: \"`7Ma circola la voce,che $nome `7abbia trovato proprio questo uovo. ");
            output("Se vuoi sapere la mia, io $nome `7addirittura lo ucciderei, ");
            output(" pur di scoprire questo, se potessi…`0\"`n`n");
            if (getsetting("hasblackegg",0)!=0){
               output("`0Poi prosegue dicendo: \"`7Mah ti devo avvishare che eshishte anche un altro uovo, un `!Uovo Nero`7 di cui non ");
               output("si conoshcono gli effetti ...`0\"`n`n");
            }
          }
          if ($session['user']['acctid']==getsetting("hasegg",0)){
             output("`n`nAllora ti ritiri evitando di far vedere l'uovo al barista. Ad ");
             output("un tavolo lontano da occhi indiscreti esamini l’uovo...`n`n`n");
             viewcommentary("goldenegg","lascia messaggio:",10,"");
          }
          if ($session['user']['acctid']==getsetting("hasblackegg",0)){
             output("`n`nallora ti ritiri evitando di far vedere l'uovo al barista. Ad ");
             output("un tavolo lontano da occhi indiscreti esamini il malefico uovo...`n`n`n");
             viewcommentary("blackegg","lascia messaggio:",10,"");
          }
//fine modifica Uovo d'Oro

    }else if($_GET['what']=="swill"){

    }else if($_GET['what']=="colors"){
              output("L´uomo si piega in avanti sul bancone.  \"`%Cosshì vuoi sshapere dei colori, sshì?`0\" domanda.");
                output("  Stai per rispondergli quando ti rendi conto che era una domanda retorica.  ");
                output("Lui continua, \"`%Per fare i colori, ecco cossha devi fare.  Prima cossha, ussha un sshegno &#0096; ",true);
                output("(ALT + 096) ssheguito da 1, 2, 3, 4, 5, 6, 7, !, @, #, $, %, ^, &.  Ognuno corrisshponde ad ");
                output("un colore come quesshto: `n`1&#0096;1 `2&#0096;2 `3&#0096;3 `4&#0096;4 `5&#0096;5 `6&#0096;6 `7&#0096;7 ",true);
                output("`n`!&#0096;! `@&#0096;@ `#&#0096;# `\$&#0096;\$ `%&#0096;% `^&#0096;^ `&&#0096;& `n",true);
                output("`% capito?`0\" Puoi fare pratica qui:");
                output("<form action=\"$REQUEST_URI\" method='POST'>",true);
                output("Hai scritto ".str_replace("`","&#0096;",HTMLEntities($_POST[testtext]))."`n",true);
                output("Viene fuori ".$_POST[testtext]." `n");
                output("<input name='testtext'><input type='submit' value='Prova'></form>",true);
                output("`0`n`nQuesshti colori possshono essshere usshati per il tuo nome e per qualunque conversshazione.");
                addnav("",$REQUEST_URI);
    }else if($_GET['what']=="enemies"){
        if ($_GET['who']==""){
            output("\"`7Cosshì, vuoi sshapere qualcossha dei tuoi nemici, sshì? Di chi vuoi sapere qualcossha? Beh? Parla! Ti cosshta solo `^100`7 monete a persshona ad informazione.`0\"");
//SOOK, modifica per evitare i moderatori furbetti
            if ($session['user']['superuser']==2) {
                addnews("`3".$session['user']['name']."`( sta apprendendo notizie sui nemici alla taverna del Cavallo Nero .");
                addnav("`@Torna alla taverna","forest.php?op=bartender&what=enemies");
            }else{
//fine modifica (manca un } più avanti)
            if ($_GET['subop']!="search"){
                output("<form action='forest.php?op=bartender&what=enemies&subop=search' method='POST'><input name='name'><input type='submit' class='button' value='Cerca'></form>",true);
                addnav("","forest.php?op=bartender&what=enemies&subop=search");
            }else{
                addnav("Cerca di nuovo","forest.php?op=bartender&what=enemies");
                $search = "%".$_POST['name']."%";;
                //for ($i=0;$i<strlen($_POST['name']);$i++){
                //    $search.=substr($_POST['name'],$i,1)."%";
                //}
                $sql = "SELECT name,alive,location,sex,level,laston,loggedin,login FROM accounts WHERE (locked=0 AND name LIKE '$search') ORDER BY level DESC";
                //output($sql);
                $result = db_query($sql) or die(db_error(LINK));
                $max = db_num_rows($result);
                if ($max > 100) {
                    output("`n`n\"`7Hey, cosssha pensshi di fare. Ssshono troppi nomi.  Al massshimo posssho parlarti di una parte di quesshti.`0`n");
                    $max = 100;
                }
                output("<table border=0 cellpadding=0><tr><td>Name</td><td>Level</td></tr>",true);
                for ($i=0;$i<$max;$i++){
                    $row = db_fetch_assoc($result);
                    output("<tr><td><a href='forest.php?op=bartender&what=enemies&who=".rawurlencode($row[login])."'>$row[name]</a></td><td>$row[level]</td></tr>",true);
                    addnav("","forest.php?op=bartender&what=enemies&who=".rawurlencode($row[login]));
                }
                output("</table>",true);
            } //(modifica Sook moderatori furbi)
            }
        }else{
            if ($session['user']['gold']>=100){
                $sql = "SELECT name,alive,location,maxhitpoints,gold,sex,level,weapon,armor,attack,defence FROM accounts WHERE login=\"$_GET[who]\"";
                $result = db_query($sql) or die(db_error(LINK));
                if (db_num_rows($result)>0){
                    $row = db_fetch_assoc($result);
                    output("\"`7Bene... vediamo che cossha ssho sshu ".str_replace("s","sh",$row['name'])."`7,`0\" dice...`n`n");
                    output("`4`bNome:`b`6 ".$row['name']."`n");
                    output("`4`bLivello:`b`6 ".$row['level']."`n");
                    output("`4`bPunti ferita:`b`6 ".$row['maxhitpoints']."`n");
                    output("`4`bMonete:`b`6 ".$row['gold']."`n");
                    output("`4`bArma:`b`6 ".$row['weapon']."`n");
                    output("`4`bArmatura:`b`6 ".$row['armor']."`n");
                    output("`4`bAttacco:`b`6 ".$row['attack']."`n");
                    output("`4`bDifesa:`b`6 ".$row['defence']."`n");
                    $session['user'][gold]-=100;
                    debuglog("spende 100 oro per apprendere notizie sui nemici alla taverna");
                }else{
                    output("\"`7Eh..?  Non conosshco nessshuno con quesshto nome.`0\"");
                }
            }else{
                output("\"`7Bene... vediamo che cossha ssho sshui poveracci come te,`0\" dice...`n`n");
                output("`4`bNome:`b`6 Procurati dei ssholdi`n");
                output("`4`bLivello:`b`6 Sshei troppo al verde`n");
                output("`4`bPunti ferita:`b`6 Probabilmente più di te`n");
                output("`4`bMonete:`b`6 Sshicuramente più di te`n");
                output("`4`bArma:`b`6 Qualcossha abbasshtanza buono da sshpaccarti il mussho`n");
                output("`4`bArmatura:`b`6 Probabilmente qualcossha più alla moda della tua`n");
                output("`4`bAttacco:`b`6 Undicimila miliardi`n");
                output("`4`bDifesa:`b`6 Superman`n");           }
        }
    }
    if ($session['user']['superuser']!=2) addnav("`@Torna alla taverna","forest.php?op=tavern");
//if modificato per evitare i moderatori che imbrogliano
    break;
case "oldman":
    addnav("Il Vecchietto");
    switch($_GET['game']){
    case "":
        checkday();
        output("Il vecchio ti guarda, con gli occhi infossati e vuoti. Dai suoi occhi rossi pare che abbia pianto di recente ");
        output("perciò gli chiedi cosa lo preoccupi.  \"`7Aah, ho incontrato un avventuriero nella foresta, ed ho pensato di poter giocare un po´ con");
        output(" ".($session['user']['sex']?"lei":"lui").  ", ma ".($session['user']['sex']?"lei":"lui")." ha vinto, e si è pres".($session['user'][sex]?"a":"o"));
        output("quasi tutti i miei soldi.");
        output("`n`n`0\"`7Dimmi... faresti un favore ad un vecchietto e mi lasceresti tentare di vincere indietro qualcosa da te? So giocare a molti ");
        output("giochi!`0\"");
        $session['user']['specialinc']="darkhorse.php";
        $session['user']['specialmisc']="";
        addnav("D?`%Gioca ai Dadi","forest.php?op=oldman&game=dice");
        addnav("P?`^Gioca alle Pietre","forest.php?op=oldman&game=stones");
        addnav("B?`#Gioca a BlackJack","blackjack.php");
        addnav("`@Torna alla taverna","forest.php?op=tavern");
        break;
    case "stones":
        $stones = unserialize($session['user']['specialmisc']);
        if (!is_array($stones)) {
            $stones = array();
            checkday();
        }
        if ($_GET['side']=="likepair") $stones['side']="likepair";
        if ($_GET['side']=="unlikepair") $stones['side']="unlikepair";
        if (isset($_POST['bet'])) $stones['bet']=min($session['user']['gold'],abs((int)$_POST['bet']));
        if ($stones['side']==""){
            output("`3Il vecchio spiega il gioco, \"`7Ho una borsa con 6 pietre rosse e 10 blu. Puoi scegliere tra 'simili' e 'diverse.'  Io");
            output("estrarrò le pietre due alla volta. Se sono dello stesso colore, vanno a chi ha scelto 'simili,'");
            output("altrimenti vanno a chi ha scelto 'diverse.'  Chi ha più pietre alla fine vince. Se ne abbiamo lo stesso numero ");
            output("è patta, e non vince nessuno.`3\"");
            if ($session['user']['oldmanstones'] > 50) output ("`n`3Il vecchio ha un'espressione in volto poco raccomandabile, ti interroghi per un attimo su quanto sia sicuro fermarsi a giocare con lui.");
            addnav("`\$Lascia perdere","forest.php?op=oldman");
            addnav("`@Simili","forest.php?op=oldman&game=stones&side=likepair");
            addnav("`%Diverse","forest.php?op=oldman&game=stones&side=unlikepair");
            $stones['red']=6;
            $stones['blue']=10;
            $stones['player']=0;
            $stones['oldman']=0;
        }elseif ($stones['bet']==0){
            output("`3\"`7".($stones['side']=="likepair"?"Simili per te, e diverse":"Diverse per te, e simili")." per me allora!");
            output("Quanto scommetti?`3\"");
            output("<form action='forest.php?op=oldman&game=stones' method='POST'><input name='bet' id='bet'><input type='submit' class='button' value='Scommetti'></form>",true);
            output("<script language='JavaScript'>document.getElementById('bet').focus();</script>",true);
            addnav("","forest.php?op=oldman&game=stones");
            addnav("Lascia perdere","forest.php?op=oldman");
        }elseif ($stones['red']+$stones['blue'] > 0 && $stones['oldman']<=8 && $stones['player']<=8){
            $s1=""; $s2="";
            $rstone = "`\$rossa`3";
            $bstone = "`!blu`3";
            while ($s1=="" || $s2==""){
                $s1 = e_rand(1,($stones['red']+$stones['blue']));
                if ($s1<=$stones['red']) {
                    $s1=$rstone;
                    $stones['red']--;
                }else{
                    $s1=$bstone;
                    $stones['blue']--;
                }
                if ($s2=="") {$s2=$s1; $s1="";}
            }
            output("`3Il vecchio pesca nel sacchetto ed estrae due pietre. Sono $s1 e $s2. La tua scommessa è di `^{$stones['bet']}`3 monete.`n`n");
            if ($stones['side']=="likepair"){
                output("Poiché hai scelto simili, ");
                if ($s1==$s2) {
                    output("il vecchio mette le pietre dalla tua parte.");
                    $stones['player']+=2;
                }else{
                    output("il vecchio mette le pietre dalla sua parte.");
                    $stones['oldman']+=2;
                }
            }else{
                output("Poiché hai scelto diverse, ");
                if ($s1==$s2) {
                    output("il vecchio mette le pietre dalla sua parte.");
                    $stones['oldman']+=2;
                }else{
                    output("il vecchio mette le pietre dalla tua parte.");
                    $stones['player']+=2;
                }
            }
            output("`n`nAl momento hai `^".$stones['player']."`3 pietre e il vecchio ha `^".$stones['oldman']."`3 pietre.");
            output("`n`nCi sono ancora ".$stones['red']." pietre $rstone e ".$stones['blue']." pietre $bstone nel sacchetto.");
            addnav("Continua","forest.php?op=oldman&game=stones");
        }else{
            if ($stones['player']>$stones['oldman']){
                $furbo = 50 + ((e_rand(1,100) / $stones['bet']) * 5000);
                //$furbo = 50 + e_rand(1,100) / $stones['bet'] * 5000;
                if ($session['user']['oldmanstones'] > $furbo) {
                      output("`3Avendo battuto il vecchietto al suo gioco, reclami i tuoi `^".$stones['bet']."`3 pezzi d'oro'.`n`n");
                      output("`3Ma il vecchietto, con un ghigno malvagio, estrae una bacchetta grigia, e ti tocca con essa, mentre pronuncia parole arcane a te incomprensibili.`");
                      output("`n`n`\$Sei paralizzato, non riesci più a muoverti! `nMentre stai perdendo i sensi, l'ultima cosa che vedi è il vecchietto che svanisce in una nuvola di fumo con l'oro della tua puntata.");
                      output("`n`n`3Hai perso `^".$session['user']['gold']." pezzi d'oro `3e `%".$session['user']['gems']." gemme");
                      debuglog("Vince al gioco delle pietre, ma il vecchietto non gli da la vincita di ".$stones['bet']." oro e scompare");
                      $session['user']['gold']-=$stones['bet'];
                      $session['user']['vecchio'] = 1;
                }else{
                      output("`3Avendo battuto il vecchietto al suo gioco, reclami i tuoi `^".$stones['bet']."`3 pezzi d'oro'.");
                      $session['user']['gold']+=$stones['bet'];
                      debuglog("vince ".$stones['bet']." oro nel gioco delle pietre alla taverna");
                }
            }elseif ($stones['player']<$stones['oldman']){
                output("`3Avendoti battuto al suo gioco, il vecchio reclama le `^".$stones['bet']."`3 monete.");
                $session['user']['gold']-=$stones['bet'];
                debuglog("perde ".$stones['bet']." oro nel gioco delle pietre alla taverna");
            }else{
                output("`3Avete pareggiato e nessuno vince.");
            }
            $session['user']['oldmanstones'] += $stones['bet'] / 5000;
            $stones=array();
            if ($session['user']['oldmanstones'] > $furbo AND $stones['player']>$stones['oldman']){
                    addnav("`@Riprendi i sensi","forest.php?op=tavern");
            }else{
                    addnav("`%Gioca di nuovo","forest.php?op=oldman&game=stones");
                    addnav("`6Altri giochi","forest.php?op=oldman");
                    addnav("`@Torna alla taverna","forest.php?op=tavern");
            }
        }
        $session['user']['specialmisc']=serialize($stones);
        break;
    case "guess":
        if ($session['user']['gold']>0){
            $bet = abs((int)$_GET['bet'] + (int)$_POST['bet']);
            if ($bet<=0){
                output("`3\"`!Hai 6 possibilità di indovinare il numero a cui sto pensando, da 1 a 100. Ogni volta ti dirò se è più alto o più basso.`3\"`n`n");
                output("`3\"`!Quanto vuoi scommettere ".($session['user']['sex']?"bella ragazza":"giovanotto")."?`3\"");
                output("<form action='forest.php?op=oldman&game=guess' method='POST'><input name='bet' id='bet'><input type='submit' class='button' value='Scommetti'></form>",true);
                output("<script language='JavaScript'>document.getElementById('bet').focus();</script>",true);
                addnav("","forest.php?op=oldman&game=guess");
                $session['user']['specialmisc']=e_rand(1,100);
            }else if($bet>$session['user']['gold']){
                output("`3L'uomo allunga il bastone e tocca il tuo borsellino.  \"`!Non credo che tu abbia `^$bet`! pezzo d'oro!`3\" dichiara.`n`n");
                output("Tentando disperatamente di dimostrargli la tua buona fede, apri il borsellino e lo svuoti: `^".$session['user'][gold]."`3 pezzi d'oro.");
                output("`n`nImbarazzato, pensi di fare ritorno alla taverna.");
                addnav("Torna alla taverna","forest.php?op=tavern");
            }else{
                if ($_POST['guess']!==NULL){
                    $try = (int)$_GET['try'];
                    if ($_POST['guess']==$session['user']['specialmisc']){
                        if ($try == 1) {
                            output("`3\"`!INCREDIBILE!!!!`3\" urla il vecchietto, \"`!Hai indovinato `^al primo tentativo`!! Bene, congratulazioni, sono molto impressionato! È come se mi avessi letto la mente.`3\" Ti guarda sospettoso e pensa di filarsela con la tua vincita, ma si ricorda delle tue apparenti capacità psichiche e ti da i `^$bet`3 pezzi d'oro che ti deve.");
                        } else {
                        output("`3\"`!AAAH!!!!`3\" urla l´uomo, \"`!Hai indovinato il numero con soli $try tentativi! Era `^".$session['user']['specialmisc']."`!!!  Beh, congratulazioni, ");
                        output("penso che me ne andrò adesso... `3\" dice dirigendosi verso la porta. Un rapido colpo del tuo ".$session['user'][weapon]);
                        output(" lo riporta al suo posto. Afferri il suo borsellino, prendendoti i `^$bet`3 pezzi d'oro che ti deve.");
                        }
                        $session['user']['gold']+=$bet;
                        debuglog("vince $bet oro nel gioco indovina il numero alla taverna");
                        $session['user']['specialinc']="darkhorse.php";
                        addnav("Torna alla taverna","forest.php?op=tavern");
                    }else{
                        if ($_GET['try']>=6){
                            output("`3L´uomo ridacchia.  \"`!Il numero era `^".$session['user']['specialmisc']."`!,`3\" dice. Dal cittadino onorevole che sei, ");
                            output("dai all´uomo i `^$bet`3 pezzi d'oro che gli devi, pronto ad andartene.");
                            $session['user']['specialinc']="darkhorse.php";
                            $session['user']['gold']-=$bet;
                            debuglog("perde $bet oro nel gioco indovina il numero alla taverna");
                            addnav("Torna alla taverna","forest.php?op=tavern");
                        }else{
                            if ((int)$_POST['guess']>$session['user']['specialmisc']){
                                output("`3\"`!No, no, no, non è `^".(int)$_POST['guess']."`!, è più basso! Era il tentativo numero `^$try`!.`3\"`n`n");
                            }else{
                                output("`3\"`!No, no, no, non è `^".(int)$_POST['guess']."`!, è più alto! Era il tentativo numero `^$try`!.`3\"`n`n");
                            }
                            output("`3Hai scommesso `^$bet`3 pezzi d'oro. Che numero pensi che sia?");
                            output("<form action='forest.php?op=oldman&game=guess&bet=$bet&try=".(++$try)."' method='POST'><input name='guess'><input type='submit' class='button' value='Indovina'></form>",true);
                            addnav("","forest.php?op=oldman&game=guess&bet=$bet&try=$try");
                        }
                    }
                }else{
                    output("`3Hai scommesso `^$bet`3 pezzi d'oro. Che numero pensi che sia?");
                    output("<form action='forest.php?op=oldman&game=guess&bet=$bet&try=1' method='POST'><input name='guess'><input type='submit' class='button' value='Indovina'></form>",true);
                    addnav("","forest.php?op=oldman&game=guess&bet=$bet&try=1");
                }
            }
        }else{
            output("`3L´uomo allunga il bastone e tocca il tuo borsellino.  \"`!Vuoto?!?!  Come puoi scommettere senza soldi??`3\" urla.");
            output("  Dopodichè ritorna ai suoi dadi, apparentemente la sua collera è già stata dimenticata.");
            addnav("Torna alla taverna","forest.php?op=tavern");
            //$session['user']['specialinc']="darkhorse.php";
        }
        break;
    case "dice":
        if ($session['user']['gold']>0){
            $bet = abs((int)$_GET['bet'] + (int)$_POST['bet']);
            if ($bet<=0){
                output("`3\"`!Puoi lanciare i dadi, e scegliere se tenere o scartare il risultato. Se lo scarti, puoi tirare al massimo altre due volte,");
                output(" per un totale di tre lanci. Una volta che tieni il tuo punteggio (odopo il tuo terzo tiro), io farò la stessa cosa.  ");
                output("Se alla fine il mio punteggio è più alto del tuo, vinco io, se è più alto il tuo, vinci tu, e se sono ugali, ");
                output("non vince nessuno e ci teniamo le nostre puntate.`3\"`n`n");
                output("`3\"`!Quanto vuoi scommettere ".($session['user']['sex']?"bella ragazza":"giovanotto")."?`3\"");
                output("<form action='forest.php?op=oldman&game=dice' method='POST'><input name='bet' id='bet'><input type='submit' class='button' value='Scommetti'></form>",true);
                output("<script language='JavaScript'>document.getElementById('bet').focus();</script>",true);
                addnav("","forest.php?op=oldman&game=dice");
                addnav("Lascia perdere","forest.php?op=oldman");
            }else if($bet>$session['user']['gold']){
                output("`3L'uomo allunga il bastone e tocca il tuo borsellino.  \"`!Non credo che tu abbia `^$bet`! pezzi d'oro!`3\" dichiara.`n`n");
                output("Tentando disperatamente di dimostrargli la tua buona fede, apri il borsellino e lo svuoti: `^".$session['user'][gold]."`3 pezzi d'oro'.");
                output("`n`nImbarazzato, pensi di fare ritorno alla taverna.");
                addnav("Torna alla taverna","forest.php?op=tavern");
            }else{
                if ($_GET['what']!="keep"){
                    $session['user']['specialmisc']=e_rand(1,6);
                    $try=$_GET['try'];
                    $try++;
                    output("Fai il tuo ".($try==1?"primo":($try==2?"secondo":"terzo"))." lancio di dadi, ed ottieni un `b".$session['user']['specialmisc']."`b`n`n");
                    output("`3Hai scommesso `^$bet`3. Che fai?");
                    addnav("Tieni","forest.php?op=oldman&game=dice&what=keep&bet=$bet");
                    if ($try<3) addnav("Scarta","forest.php?op=oldman&game=dice&what=pass&try=$try&bet=$bet");
                }else{
                    output("Il tuo punteggio finale è `b".$session['user']['specialmisc']."`b, ora il vecchietto proverà a batterlo:`n`n");
                    $r = e_rand(1,6);
                    output("Il vecchietto lancia un $r...`n");
                    if ($r>$session['user']['specialmisc'] || $r==6){
                        output("\"`7Penso che me lo terrò!`0\" dice.`n");
                    }else{
                        $r = e_rand(1,6);
                        output("Il vecchietto lancia di nuovo e ottiene un $r...`n");
                        if ($r>=$session['user']['specialmisc']){
                            output("\"`7Penso che me lo terrò!`0\" dice.`n");
                        }else{
                            $r = e_rand(1,6);
                            output("Il vecchietto fa il suo ultimo tiro ed ottiene un $r...`n");
                        }
                    }
                    if ($r>$session['user']['specialmisc']){
                        output("`n\"`7Yahoo, lo sapevo che uno come te non poteva vincere con uno come me!`0\" esclama il vecchietto mentre gli dai i tuoi `^$bet`0 pezzi d'oro.");
                        $session['user']['gold']-=$bet;
                        debuglog("perde $bet pezzi d'oro ai dadi nella taverna");
                    }else{
                        if ($r==$session['user']['specialmisc']){
                            output("`n\"`7Ah!... beh, sembra che abbiamo pareggiato.`0\" dice.");
                        }else{
                            output("`n\"`7Aaarrgh!!! Come ha fatto uno come te a battermi?!?!?`0\" urla il vecchietto mentre ti da i soldi che ti deve.");
                            $session['user']['gold']+=$bet;
                            debuglog("vince $bet pezzi d'oro ai dadi nella taverna");
                        }
                    }
                    addnav("Torna alla taverna","forest.php?op=tavern");
                }
            }
        }else{
            output("`3L´uomo allunga il bastone e tocca il tuo borsellino.  \"`!Vuoto?!?!  Come puoi scommettere senza soldi??`3\" urla.");
            output("  Dopodichè ritorna ai suoi dadi, apparentemente la sua collera è già stata dimenticata.");
            addnav("Torna alla taverna","forest.php?op=tavern");
        }
        break;
    }
    break; //end of old man.
case "leave":
    output("Esci dalla taverna e vagabondi tra le fitte foglie intorno a te. La strana foschia ");
    output("ti visita nuovamente, facendoti ronzare la testa. Poi scompare e ti ritrovi nella foresta ");
    output("che ti è familiare. Come tu sia arrivato alla taverna esattamente non ti è chiaro.");
    $session['user']['specialinc']="";
    break;
case "leaveleave":
    output("Decidi che non vale la pena sprecare il tuo tempo, e ti giri per tornare alla foresta.");
    $session['user']['specialinc']="";
    break;
}
output("</span>",true);
?>
