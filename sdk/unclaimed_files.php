<?php

include_once("../pathos.php");

$mod_dh =    opendir(BASE."modules");
$subsys_dh = opendir(BASE."subsystems");
$theme_dh =  opendir(BASE."themes");

$all_files = include(BASE."manifest.php");

while (($file = readdir($mod_dh)) !== false) {
	// Process all module files
	if (is_readable(BASE."modules/$file/manifest.php")) {
		#echo $file . "\n";
		$all_files = array_merge($all_files,include(BASE."modules/$file/manifest.php"));
	}
}

while (($file = readdir($theme_dh)) != false) {
	if (is_readable(BASE."themes/$file/manifest.php")) {
		#echo $file . "\n";
		$all_files = array_merge($all_files,include(BASE."themes/$file/manifest.php"));
	}
}

while (($file = readdir($subsys_dh)) != false) {
	if (substr($file,-13,13) == ".manifest.php") {
		#echo $file . "\n";
		$all_files = array_merge($all_files,include(BASE."subsystems/$file"));
	}
}

ksort($all_files);
#print_r($all_files);
//$all_files = array_keys($all_files);

function checkFiles($rel_dir,$all_files,&$unclaimed) {
	$rel_dir = $rel_dir . ($rel_dir != "" ? "/" : "");
	$dh = opendir(BASE.$rel_dir);
	$exclude_dirs = array("files","external","compat","cache","sdk","testing","install","conf/profiles","install_warn");
	$exclude_all = array("CVS","views_c");
	while (($file = readdir($dh)) !== false) {
		if (is_dir(BASE.$rel_dir.$file) && substr($file,0,1) != "." && !in_array($rel_dir.$file,$exclude_dirs) && !in_array($file,$exclude_all)) {
#			echo "Found Dir : $rel_dir$file\n";
			checkFiles("$rel_dir$file",$all_files,$unclaimed);
		} else if (is_file(BASE.$rel_dir.$file) && substr($file,0,1) != "." && substr($file,-12,12) != "manifest.php") {
#			echo "Found file : $rel_dir$file\n";
			if (!isset($all_files["$rel_dir$file"])) {
#				echo "BAD FILE: $rel_dir$file\n";
				$unclaimed[] = "$rel_dir$file";
			} else {
#				echo "good file: $rel_dir$file\n";
			}
		}
	}
}

$unclaimed = array();
checkFiles("",$all_files,$unclaimed);

print_r($unclaimed);

?>