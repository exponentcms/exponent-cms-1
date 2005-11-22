<?php

include_once("../pathos.php");

$mod_dh =    opendir(BASE."modules");
$subsys_dh = opendir(BASE."subsystems");
$theme_dh =  opendir(BASE."themes");

$all_files = include(BASE."manifest.php");

while (($file = readdir($mod_dh)) !== false) {
	// Process all module files
	if (is_readable(BASE."modules/$file/auto.manifest.php")) {
		$all_files = array_merge($all_files,include(BASE."modules/$file/auto.manifest.php"));
	} else if (is_readable(BASE."modules/$file/manifest.php")) {
		$all_files = array_merge($all_files,include(BASE."modules/$file/manifest.php"));
	}
}

while (($file = readdir($theme_dh)) != false) {
	if (is_readable(BASE."themes/$file/auto.manifest.php")) {
		$all_files = array_merge($all_files,include(BASE."themes/$file/auto.manifest.php"));
	} else if (is_readable(BASE."themes/$file/manifest.php")) {
		$all_files = array_merge($all_files,include(BASE."themes/$file/manifest.php"));
	}
}

while (($file = readdir($subsys_dh)) != false) {
	if (substr($file,-13,13) == ".manifest.php") {
		$all_files = array_merge($all_files,include(BASE."subsystems/$file"));
	}
}

ksort($all_files);

function checkFiles($rel_dir,$all_files,&$unclaimed) {
	$rel_dir = $rel_dir . ($rel_dir != "" ? "/" : "");
	$dh = opendir(BASE.$rel_dir);
	$exclude_dirs = array("files","external","cache","sdk","testing","conf/profiles");
	$exclude_all = array("CVS","views_c");
	while (($file = readdir($dh)) !== false) {
		if (is_dir(BASE.$rel_dir.$file) && substr($file,0,1) != "." && !in_array($rel_dir.$file,$exclude_dirs) && !in_array($file,$exclude_all)) {
			checkFiles("$rel_dir$file",$all_files,$unclaimed);
		} else if (is_file(BASE.$rel_dir.$file) && substr($file,0,1) != ".") {
			if (!isset($all_files["$rel_dir$file"])) {
				$unclaimed[] = "$rel_dir$file";
			}
		}
	}
}

$unclaimed = array();
checkFiles("",$all_files,$unclaimed);

$count = 0;

foreach ($unclaimed as $file) {
	echo "'".$file."'=>";
	if (substr($file,-12,12) == 'manifest.php') {
		echo "1,"."\r\n";
	} else {
		echo "'',"."\r\n";
	}
	$count++;
}

echo "$count file(s) unaccounted for\r\n";

?>