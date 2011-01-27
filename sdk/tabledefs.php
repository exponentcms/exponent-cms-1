<?php

include_once('../exponent.php');

$tables = array();

$dh = opendir(BASE.'datatypes/definitions');
while (($file = readdir($dh)) !== false) {
	if (substr($file,-4,4) == ".php") {
		$tables[substr($file,0,-4)] = array_keys(include(BASE.'datatypes/definitions/'.$file));
	}
}

echo serialize($tables);

?>
