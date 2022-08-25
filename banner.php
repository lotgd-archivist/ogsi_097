<?php
require_once ('DB.php');
$dsn1 = "mysql://logd97i:logd97i@localhost/logd";
$options1 = array(
    'debug'       => 2,
    'portability' => DB_PORTABILITY_ALL,
);
$db =& DB::connect($dsn1, $options1);
if (PEAR::isError($db)) {
	echo 'Standard Message: ' . $db->getMessage() . "\n";
    exit;
} 
$db->setFetchMode(DB_FETCHMODE_ASSOC);

$login=$_POST['nome'];
$sql = "SELECT * FROM occounts WHERE login='$login'";
$row = $db->getRow("$sql");
if (PEAR::isError($row)) {die($row->getDebugInfo());}


?>