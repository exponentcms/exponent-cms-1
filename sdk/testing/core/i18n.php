<?php

$base = realpath($argv[1]);
$base .= '/subsystems/lang/en';

$otherbase = realpath($argv[1]);
$otherbase .= '/subsystems/lang/test';

`rm -rf $otherbase`;
mkdir($otherbase);

$fh = fopen($otherbase.'.php','w');
fwrite($fh,"<?php\r\n\r\n");
fwrite($fh,"return array(\r\n");
fwrite($fh,"\t'name'=>'Testing Language',\r\n");
fwrite($fh,"\t'charset'=>'', // Reserved for future use\r\n");
fwrite($fh,"\t'author'=>'James Hunt',\r\n");
fwrite($fh,"\t'locale'=>'de_DE', // See RFC 1766 and ISO 639,\r\n");
fwrite($fh,"\t'default_view'=>'Default' // For recursion in view resolution\r\n");
fwrite($fh,");\r\n");
fwrite($fh,"\r\n?>");

fclose($fh);

$constants = array();

$langdh = opendir($base);
while (($dir = readdir($langdh)) !== false) {
	if ($dir{0} != '.' && is_dir($base.'/'.$dir)) {
	
		mkdir($otherbase.'/'.$dir);
		
		$dh = opendir($base.'/'.$dir);
		while (($file = readdir($dh)) !== false) {
			if (is_file($base.'/'.$dir.'/'.$file)) {
				echo '[[i18n]]: Including language dictionary '.$dir.' / ' .substr($file,0,-4)."\r\n";
				include_once($base.'/'.$dir.'/'.$file);
				
				// Open other file
				$fh = fopen($otherbase.'/'.$dir.'/'.$file,'w');
				fwrite($fh,"<?php\r\n");
				foreach (get_defined_constants() as $name=>$value) {
					if (substr($name,0,3) == 'TR_' && !in_array($name,$constants)) {
						fwrite($fh,"define('$name','[-[$value]-]');\r\n");
						$constants[] = $name;
					}
				}
				fwrite($fh,"\r\n?>");
				fclose($fh);
			}
		}
	}
}

?>