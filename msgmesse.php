<?php
require_once("common.php");
page_header("Messaggio Messa");
$flag = false;
$diff = 3600; //Differenza di tempo in secondi tra le messe (3600 sec = 1 ora)
switch ($session['user']['dio']) {
   case 1:
       $sala="`6Sala del `^Gran Sacerdote";
       $messa="messasgrios";
       $return="chiesa.php";
       $dio="`^Sgrios`\$";
       break;
   case 2:
       $sala="`4Grotta del `\$Falciatore di Anime";
       $messa="messakarnak";
       $return="karnak.php";
       $dio="`bKarnak`b";
       break;
   case 3:
       $sala="`2Sala del `@Dominatore di Draghi";
       $messa="messadrago";
       $return="gildadrago.php";
       $dio="il `@Drago Verde`\$";
       break;
}

$month = array(
1=>"Gennaio",
2=>"Febbraio",
3=>"Marzo",
4=>"Aprile",
5=>"Maggio",
6=>"Giugno",
7=>"Luglio",
8=>"Agosto",
9=>"Settembre",
10=>"Ottobre",
11=>"Novembre",
12=>"Dicembre");
if ($_GET['op'] == ""){
     $anno=date("Y");
     $mese=date("m");
     $giorno=date("d");
     addnav("`%Scelta data Messa","");
     addnav("Lascia Perdere",$return);
     output("<FORM name='DataMessa' action='msgmesse.php?op=end' method='POST'>",true);
     output("<table border=0 cellspacing=5 cellpadding=5>",true);
     output("<tr>",true);
     //Inserimento Anno
     output("<td>`2Seleziona l'anno</td>",true);
     output("<td><input type='radio' name='year' value='".$anno."'> `@".$anno."`n",true);
     if ($mese=12 AND $giorno>26){
         output("<input type='radio' name='year' value='".($anno+1)."'> ".($anno+1)."`n",true);
     }
     output("</td></tr>",true);
     //Inserimento Mese
     output("<tr><td>`2Seleziona il mese</td>",true);
     output("<td><input type='radio' name='month' value='1'> `@Gennaio&nbsp&nbsp&nbsp",true);
     output("<input type='radio' name='month' value='2'> Febbraio`n",true);
     output("<input type='radio' name='month' value='3'> Marzo&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp",true);
     output("<input type='radio' name='month' value='4'> Aprile`n",true);
     output("<input type='radio' name='month' value='5'> Maggio&nbsp&nbsp&nbsp&nbsp",true);
     output("<input type='radio' name='month' value='6'> Giugno`n",true);
     output("<input type='radio' name='month' value='7'> Luglio&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp",true);
     output("<input type='radio' name='month' value='8'> Agosto`n",true);
     output("<input type='radio' name='month' value='9'> Settembre",true);
     output("<input type='radio' name='month' value='10'> Ottobre`n",true);
     output("<input type='radio' name='month' value='11'> Novembre",true);
     output("<input type='radio' name='month' value='12'> Dicembre`n</td></tr>",true);
     //Inserimento Giorno
     output("<tr><td>`2Seleziona il Giorno</td>",true);
     output("<td><input type='radio' name='day' value='1'> `@1&nbsp&nbsp",true);
     output("<input type='radio' name='day' value='2'> 2&nbsp&nbsp",true);
     output("<input type='radio' name='day' value='3'> 3&nbsp&nbsp",true);
     output("<input type='radio' name='day' value='4'> 4`n",true);
     output("<input type='radio' name='day' value='5'> 5&nbsp&nbsp",true);
     output("<input type='radio' name='day' value='6'> 6&nbsp&nbsp",true);
     output("<input type='radio' name='day' value='7'> 7&nbsp&nbsp",true);
     output("<input type='radio' name='day' value='8'> 8`n",true);
     output("<input type='radio' name='day' value='9'> 9&nbsp&nbsp",true);
     output("<input type='radio' name='day' value='10'> 10",true);
     output("<input type='radio' name='day' value='11'> 11",true);
     output("<input type='radio' name='day' value='12'> 12`n",true);
     output("<input type='radio' name='day' value='13'> 13",true);
     output("<input type='radio' name='day' value='14'> 14",true);
     output("<input type='radio' name='day' value='15'> 15",true);
     output("<input type='radio' name='day' value='16'> 16`n",true);
     output("<input type='radio' name='day' value='17'> 17",true);
     output("<input type='radio' name='day' value='18'> 18",true);
     output("<input type='radio' name='day' value='19'> 19",true);
     output("<input type='radio' name='day' value='20'> 20`n",true);
     output("<input type='radio' name='day' value='21'> 21",true);
     output("<input type='radio' name='day' value='22'> 22",true);
     output("<input type='radio' name='day' value='23'> 23",true);
     output("<input type='radio' name='day' value='24'> 24`n",true);
     output("<input type='radio' name='day' value='25'> 25",true);
     output("<input type='radio' name='day' value='26'> 26",true);
     output("<input type='radio' name='day' value='27'> 27",true);
     output("<input type='radio' name='day' value='28'> 28`n",true);
     output("<input type='radio' name='day' value='29'> 29",true);
     output("<input type='radio' name='day' value='30'> 30",true);
     output("<input type='radio' name='day' value='31'> 31`n</td></tr>",true);
     //Inserimento Ora
     output("<tr><td>`2Seleziona l'Ora</td>",true);
     output("<td><select name='hour' class='input'>",true);
     output("<option value='' selected>Scegli Ora</option>`n",true);
     for ($i=0;$i<24;$i++){
         output(" <option value='".$i."'>".$i."</option>",true);
     }
     output("/td></tr>",true);
     //Inserimento Minuto
     output("<tr><td>`2Seleziona il Minuto</td>",true);
     output("<td><select name='minute' class='input'>",true);
     output("<option value='' selected>Scegli Minuto</option>`n",true);
     for ($i=0;$i<56;$i+=5){
         output(" <option value='".$i."'>".$i."</option>",true);
     }
     output("/td></tr>",true);
     output("<tr><input type='submit' class='button' value='Invia Dati'></tr></table>",true);
     output("</form>",true);
     addnav("","msgmesse.php?op=end");
}elseif ($_GET['op']=="end") {
     $data = $_POST['year']."-".$_POST['month']."-".$_POST['day'];
     if ($_POST['minute']== "0") $_POST['minute']="00";
     if ($_POST['minute']== "5") $_POST['minute']="05";
     $data .= " ".$_POST['hour'].":".$_POST['minute'].":00";
     if ($_POST['year']=="" OR $_POST['month']=="" OR $_POST['day']=="" OR $_POST['hour']=="" OR $_POST['minute']==""){
        output("`\$Ti sei dimenticato di inserire qualche dato:`n");
        output("Anno: ".$_POST['year']."`n");
        output("Mese: ".$_POST['month']."`n");
        output("Giorno: ".$_POST['day']."`n");
        output("Ora: ".$_POST['hour']."`n");
        output("Minuti: ".$_POST['minute']."`n`n");
        addnav("Inserisci Data Messa","msgmesse.php");
     }elseif(strtotime($data) < strtotime("now")){
         output("`\$I dati inserito si riferiscono ad un istante già trascorso, e ".$dio." non vuole assistere a paradossi temporali dentro Rafflingate!!`n");
         output("Anno: ".$_POST['year']."`n");
         output("Mese: ".$_POST['month']."`n");
         output("Giorno: ".$_POST['day']."`n");
         output("Ora: ".$_POST['hour']."`n");
         output("Minuti: ".$_POST['minute']."`n`n");
         addnav("Inserisci Data Messa","msgmesse.php");
     }elseif(
         ($_POST['month']==2 AND $_POST['day']>28 AND ($_POST['year']%4)!=0)
         OR
         ($_POST['month']==2 AND $_POST['day']>29 AND ($_POST['year']%4)==0)){
         output("`\$Il mese di `&Febbraio`\$ dell'anno ".$_POST['year']." ha solo ".(($_POST['year']%4)?"28":"29")." giorni!!");
         addnav("Inserisci Data Messa","msgmesse.php");
     }elseif( ($_POST['month']==11 OR $_POST['month']==4 OR $_POST['month']==6 OR $_POST['month']==9) AND $_POST['day']>30){
         output("`\$Il mese di `&".$month[$_POST['month']]."`\$ ha solo 30 giorni!!");
         addnav("Inserisci Data Messa","msgmesse.php");
     }else{
         output("`#Hai scelto di inserire il messaggio relativo alla prossima messa che celebrerai:`n");
         output("`3Anno: `#".$_POST['year']."`n");
         output("`3Mese: `#".$month[$_POST['month']]."`n");
         output("`3Giorno: `#".$_POST['day']."`n");
         output("`3Ora: `#".$_POST['hour']."`n");
         output("`3Minuti: `#".$_POST['minute']."`n`n");

/* Sook, disabilitato, con pochi giocatori non si hanno problemi di sovraccarico del server e i giocatori hanno più libertà
         // Excalibur: controllo coincidenza messa con altre sette
         $sql="SELECT * FROM custom WHERE area LIKE '`(La prossima messa verrà%' ORDER BY dDate ASC, dTime ASC";
         db_query($sql) or die(db_error(LINK));
         $result = db_query($sql);
         $countrow = db_num_rows($result);
         for ($i=0; $i<$countrow; $i++){
         //for ($i=0; $i<db_num_rows($result); $i++){
             $row = db_fetch_assoc($result);
             if ($messa != $row['area1']){
                $tempo[$i] = $row['dDate']." ".$row['dTime'];
                $setta[$i] = $row['area1'];
                if (abs(strtotime($data)-strtotime($row['dDate']." ".$row['dTime'])) < $diff) $flag=true;
             }
         }
*/
         if ($flag == false){
             output("`#Scegliendo `i`b`%PROSEGUI`#`i`b confermi questi dati ed il messaggio apparirà nella ".$sala."`#.`n");
             output("`#Se invece qualche dato non è corretto, reinseriscili.`n`n");
             addnav("Prosegui","msgmesse.php?op=reg");
         }else{
             output("<big>`\$La data/ora che hai scelto per la messa è troppo ravvicinata a quella di un'altra setta.</big>`n",true);
             output("<big>Devi modificarla e lasciare almeno un'ora di differenza tra quelle qui sotto indicate.</big>`n`n",true);
             for ($i=1; $i<4; $i++){
                 if($setta[$i]!="") output("`2".$setta[$i]." `@".$tempo[$i]."`n");
             }
         }
         //Excalibur: termine controllo coincidenza messe
         addnav("Correggi","msgmesse.php");
         addnav("Lascia Perdere",$return);
         $session['year']=$_POST['year'];
         $session['month']=$_POST['month'];
         $session['day']=$_POST['day'];
         $session['hour']=$_POST['hour'];
         $session['minute']=$_POST['minute'];
     }
}elseif($_GET['op']=="reg") {
     $giorno = $session['year']."-".$session['month']."-".$session['day'];
     if ($session['minute']== "0") $session['minute']="00";
     $ora = $session['hour'].":".$session['minute'].":00";
     $msg = "`(La prossima messa verrà celebrata da ".$session['user']['name']." `(il giorno ";
     $msg .=date("d/m/Y",strtotime($giorno))." alle ore ".$ora;
     $msg=addslashes($msg);
     $sql="SELECT * FROM custom WHERE area1='".$messa."'";
     $result=db_query($sql);
     $dep = db_fetch_assoc($result);
     if (db_num_rows($result) == 0) {
         $sql = "INSERT INTO custom (area,amount,dTime,dDate,area1)
         VALUES ('".$msg."','".$session['user']['acctid']."','".$ora."','".$giorno."','".$messa."')";
         $result=db_query($sql);
     }else{
         $sql = "UPDATE custom SET area='".$msg."',amount='".$session['user']['acctid']."',
         dTime='".$ora."',dDate='".$giorno."'
         WHERE area1='".$messa."'";
         $result=db_query($sql);
     }
     $sqlmail = "SELECT acctid FROM accounts WHERE dio=".$session['user']['dio']." AND superuser=0 AND acctid<>".$session['user']['acctid'];
     $resultmail = db_query($sqlmail);
     //output("Query SQL: ".$sqlmail."`nNumero righe: ".db_num_rows($resultmail));
     $countrow = db_num_rows($resultmail);
     for ($imail=0; $imail<$countrow; $imail++){
     //for ($imail=0;$imail<db_num_rows($resultmail);$imail++){
         $rowmail = db_fetch_assoc($resultmail);
         systemmail($rowmail['acctid'],"`^Annuncio messa!`0",$msg,$session['user']['acctid']);
         //output("Account ID N°".($imail+1).": ".$rowmail['acctid']."`n");
     }
    debuglog("Ha fissato la messa: ".$msg);
    addnav("Torna alla Setta",$return);
}
page_footer();
?>