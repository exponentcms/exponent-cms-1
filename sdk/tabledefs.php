<?php

$tables = array();

$dh = opendir('datatypes/definitions');
while (($file = readdir($dh)) !== false) {
	if (substr($file,-4,4) == ".php") {
		$tables[substr($file,0,-4)] = array_keys(include('datatypes/definitions/'.$file));
	}
}

echo serialize($tables);

?>
