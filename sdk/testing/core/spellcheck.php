<?php

$base = realpath($argv[1]);
$base .= '/subsystems/lang/en';

$langdh = opendir($base);
while (($dir = readdir($langdh)) !== false) {
	if ($dir{0} != '.' && is_dir($base.'/'.$dir)) {
		$dh = opendir($base.'/'.$dir);
		while (($file = readdir($dh)) !== false) {
			if (is_file($base.'/'.$dir.'/'.$file)) {
				echo '[[spellcheck]]: Including language dictionary '.$dir.' / ' .substr($file,0,-4)."\r\n";
				include_once($base.'/'.$dir.'/'.$file);
			}
		}
	}
}

foreach (get_defined_constants() as $name=>$value) {
	if (substr($name,0,3) == 'TR_') {
		echo '[[spellcheck]]: '.$name."\r\n";
		echo $value."\r\n";
	}
}

?>