<?php
/*$url=$_GET['url'];
$dir = str_replace("\\","/",dirname($url)."/");
$subdir = str_replace("\\","/",dirname($_SERVER['SCRIPT_NAME'])."/");
//echo "<pre>$subdir</pre>";
$legal_dirs = array(
    $subdir."" => 1,
  $subdir."special/"  => 1
);
$illegal_files = array(
    ($subdir=="//"?"/":$subdir)."dbconnect.php"=>"contiene informazioni sensibili specifiche per quest'installazione.",
    ($subdir=="//"?"/":$subdir)."dragon.php"=>"Se vuoi leggere lo script del Drago Verde, ti consiglio di farlo sconfiggendolo!",
    ($subdir=="//"?"/":$subdir)."topwebvote.php"=>"X", // hide completely
    ($subdir=="//"?"/":$subdir)."translator_it.php"=>"Non rilasciato almeno per il momento.",
    ($subdir=="//"?"/":$subdir)."lodge.php"=>"Non rilasciato almeno per il momento.",
    ($subdir=="//"?"/":$subdir)."labirinto.php"=>"Protetto.",
    ($subdir=="//"?"/":$subdir)."ruota.php"=>"Protetto.",
    ($subdir=="//"?"/":$subdir)."perdita.php"=>"Protetto.",
    ($subdir=="//"?"/":$subdir)."source.php"=>"Protetto.",
    ($subdir=="//"?"/":$subdir)."trumptower.php"=>"X", // hide completely
    ($subdir=="//"?"/":$subdir)."special/pietre.php"=>"Protetto.",
    ($subdir=="//"?"/":$subdir)."robbank.php"=>"Protetto.",
    ($subdir=="//"?"/":$subdir)."remotebackup.php"=>"X" // hide completely

);
$legal_files=array();

echo "<h1>View Source: ", HTMLEntities2($url), "</h1>";
echo "<a href='#source'>Clicca qui per il sorgente,</a> oppure<br>";
echo "<b>Altri file di cui potresti voler vedere il sorgente:</b><ul>";
while (list($key,$val)=each($legal_dirs)){
    //echo "<pre>$key</pre>";
    $skey = substr($key,strlen($subdir));
    //echo $skey." ".$key;
    if ($key==dirname($_SERVER[SCRIPT_NAME])) $skey="";
    $d = dir("./$skey");
    if (substr($key,0,2)=="//") $key = substr($key,1);
    if ($key=="//") $key="/";
    while (false !== ($entry = $d->read())) {
            if (substr($entry,strrpos($entry,"."))==".php"){
                if ($illegal_files["$key$entry"]!=""){
                    if ($illegal_files["$key$entry"]=="X"){
                        //we're hiding the file completely.
                    }else{
                        echo "<li>$skey$entry &#151; questo file non può essere visto: ".$illegal_files["$key$entry"]."</li>\n";
                    }
                }else{
                    echo "<li><a href='source.php?url=$key$entry'>$skey$entry</a></li>\n";
                    $legal_files["$key$entry"]=true;
                }
            }
    }
    $d->close();
}
echo "</ul>";

echo "<h1><a name='source'>Sorgente di: ", HTMLEntities2($url), "</a></h1>";

$page_name = substr($url,strlen($subdir)-1);
if (substr($page_name,0,1)=="/") $page_name=substr($page_name,1);
if ($legal_files[$url]){
    show_source($page_name);
}else if ($illegal_files[$url]!="" && $illegal_files[$url]!="X"){
    echo "<p>Non puoi vedere questo file: $illegal_files[$url]</p>";
}else {
    echo "<p>Non puoi vedere questo file.</p>";
}
*/
print("Qui dovrebbero esserci i sorgenti, ma come vedi non ci sono.<br>");
print("Vai a <a href='http://sourceforge.net/projects/lotgd' target='_blank'>SourceForge</a> per scaricare l'ultima versione del gioco");
?>