<?php
/*-------------------------------------------------------------------*/
/* OGSI Logd Project                                                 */
/* (c) 2004-2006 Gianluca Brozzi                                     */
/* Use of this program is governed by Creative Commons Deeds         */
/* If you did not receive a copy of the license:                     */
/* http://creativecommons.org/licenses/by-nc-sa/2.0/it/              */
/* Original Author : Gianluca Brozzi                                 */
/* Queste righe non possono essere modificate                        */
/*-------------------------------------------------------------------*/
/* Prime Maintainer : Luke and Excalibur                             */
/*-------------------------------------------------------------------*/
/* Modifiche al codice vanno aggiunte quì di seguito                 */
/* con anche una piccola descrizione                                 */
/* Data:       Nome:           Modifica:                             */
/*-------------------------------------------------------------------*/


error_reporting(E_ALL);
ini_set('memory_limit', '30M');
set_time_limit(180); // 3 minutes

if(!empty($_SERVER['SERVER_SOFTWARE'])) {
    echo 'Lo schedulatore può essere richiamato solamente da CLI!';
    exit;
}
require_once "../logd_connect/dbconnect.php";

exec('cd /home/logd_images/'.$DB_NAME.'/; rm *.jpg');


/* Connessione e selezione del database */
$connessione = mysql_connect("$DB_HOST", "$DB_USER", "$DB_PASS")
or die("Connessione non riuscita: " . mysql_error());
//print "Connesso con successo";
mysql_select_db("$DB_NAME") or die("Selezione del database non riuscita");
//Sook, impostazione orario europeo sul db
mysql_query("set time_zone = '+2:00'");
//funziona filtra by Xtramus
function filtra($s) {
    $ris="";
    for($i=0; $i<strlen($s); $i++) {
        $lett= substr($s,$i,1);
        if($lett!="`") {
            $ris.=$lett;
        }else{
            $i++;
        }
    }
    return $ris;
}


//Array carriere
$carriera=array(
0=>"Nessuna",
1=>"Seguace",
2=>"Accolito",
3=>"Chierico",
4=>"Sacerdote",
5=>"Garzone",
6=>"Apprendista",
7=>"Fabbro",
8=>"Mastro Fabbro",
9=>"Gran Sacerdote",
10=>"Invasato",
11=>"Fanatico",
12=>"Posseduto",
13=>"Maestro delle Tenebre",
15=>"Falciatore di Anime",
16=>"Portatore di Morte",
17=>"Sommo Chierico",
41=>"Iniziato",
42=>"Stregone",
43=>"Mago",
44=>"Arcimago",
50=>"Stalliere",
51=>"Scudiero",
52=>"Cavaliere",
53=>"Mastro di Draghi",
54=>"Dominatore di Draghi",
55=>"Cancelliere dei Draghi",
80=>"Moderatore",
255=>"ADMIN"
);


// inizio script
$sql = "SELECT * FROM banner";
$result = mysql_query($sql) or die(db_error(LINK));
while ($linea = mysql_fetch_array($result, MYSQL_ASSOC))
{
    $sql = "SELECT * FROM accounts WHERE acctid='".$linea['id_player']."'";
    $resulta = mysql_query($sql) or die(db_error(LINK));
    $row = mysql_fetch_array($resulta);
    if ($row['acctid']){
        $image = ImageCreateFromJPEG("/home/logd_images/logo_vuoto.jpg");
        $giallo=imagecolorallocate($image,255,255,128);
        $blu=imagecolorallocate($image,102,153,255);
        $bianco= imagecolorallocate($image, 255, 255, 255);
        $nero = imagecolorallocate($image, 0, 0, 0);
        $rosso = imagecolorallocate($image, 255, 159, 159);
        $verde = imagecolorallocate($image, 194, 255, 145);
        $viola = imagecolorallocate($image,226,159,255);
        $azzurro = imagecolorallocate($image, 154, 209, 255);
        $car=$row['carriera'];
        imagestring ($image, 3,2,2,'Nome:', $bianco);
        $pos =strpos($row['title'],"`");
        if($pos===false){
            $title=$row['title'];
            $colore='`6';
        }elseif($pos==0){
            $title=substr($row['title'], 2);
            $colore=substr($row['title'], 0,2);
        }else{
            $title='Problema immagine';
            $colore='`6';
        }
        //echo $colore.' - '.$title.'<br>';  `^C`@I`#A`%O
        if ($colore=='`6' OR $colore=='`^'){
            imagestring ($image, 3,40,2,$title.' '.$row['login'].' '.filtra($row['ctitle']), $giallo);
        }
        if ($colore=='`1' OR $colore=='`!'){
            imagestring ($image, 3,40,2,$title.' '.$row['login'].' '.filtra($row['ctitle']), $blu);
        }
        if ($colore=='`2' OR $colore=='`@'){
            imagestring ($image, 3,40,2,$title.' '.$row['login'].' '.filtra($row['ctitle']), $verde);
        }
        if ($colore=='`3' OR $colore=='`#'){
            imagestring ($image, 3,40,2,$title.' '.$row['login'].' '.filtra($row['ctitle']), $azzurro);
        }
        if ($colore=='`4' OR $colore=='`$'){
            imagestring ($image, 3,40,2,$title.' '.$row['login'].' '.filtra($row['ctitle']), $rosso);
        }
        if ($colore=='`5' OR $colore=='`%'){
            imagestring ($image, 3,40,2,$title.' '.$row['login'].' '.filtra($row['ctitle']), $viola);
        }
        if ($colore=='`7' OR $colore=='`&'){
            imagestring ($image, 3,40,2,$title.' '.$row['login'].' '.filtra($row['ctitle']), $bianco);
        }

        imageline($image,0,17,255,17,$giallo);
        imagestring ($image, 3,2,25,'Reincarnazioni: '.$row['reincarna'], $bianco);
        imagestring ($image, 3,2,35,'DK  : '.$row['dragonkills'], $bianco);
        if($row['dio']==0)imagestring ($image, 3,2,45,'Fede: '.'Ateo', $bianco);
        if($row['dio']==1)imagestring ($image, 3,2,45,'Fede: '.'Sgrios', $giallo);
        if($row['dio']==2)imagestring ($image, 3,2,45,'Fede: '.'Karnak', $rosso);
        if($row['dio']==3)imagestring ($image, 3,2,45,'Fede: '.'Drago', $verde);
        imagestring ($image, 3,120,35,'PVP vinti: '.$row['pvpkills'], $bianco);
        imagestring ($image, 3,120,45,'Rango: '.$carriera[$car].'', $bianco);
        //imagestring ($image, 2,312,46,'www.ogsi.it/logd', $verde);
        imagejpeg($image,'/home/logd_images/'.$DB_NAME.'/'.strtolower($row['login']).'.jpg',40);
    }else{
        //cancellare da tabella banner acctid non esistenti
    }
}
?>