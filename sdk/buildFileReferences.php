#!/usr/bin/php
<?php

#############################
#
#  buildFileReferences
#    - A tool for managing file checksums
#
#  Possible actions:
#
#    - recalc : Recalculate all file checksums.
#    - changes : Lists changed files
#    - list : List all files in manifest
#
#    - add : Add a file to the manifest
#    - dir : Add a directory and all its contents
#    - remove : Remove a file from the manifest
#

include_once("../pathos.php");

echo "\n\n";
echo "-------------------------------\n";
echo "Exponent File Reference Builder\n";
echo "    by James Hunt.  Version 0.3\n";
echo "-------------------------------\n\n";

if ($argc == 1) {
	usageOptions();
	exit();
}

$vars = processArguments($argv,"validateArguments");

$manifest = getManifestFile($vars['type'],$vars['name']);
echo $manifest;
if ($manifest == "") exit("Unable to find directory for ". $vars['type']. " " . $vars['name'] . "\n");

echo "action: " . $vars['action'] . "\n";
echo "extension type: ".$vars["type"]."\n";
echo "extension name: ".$vars["name"]."\n";
echo "\n-----------------------------\n\n";

$files = array();
$ofiles = array();
if (is_readable($manifest)) $ofiles = include($manifest);
foreach ($ofiles as $file=>$md5) {
	if (substr($file,0,1) == "/") $file = str_replace(BASE,"",$file);
	$files[$file] = $md5;
}

if ($vars['action'] == "list") {
	echo "Files in manifest\n";
	foreach ($files as $file=>$md5) {
		echo "$file:\n\t$md5\n\n";
	}
	
} else if ($vars['action'] == "changes") {

	echo "Showing changed files\n";
	$changed = 0;
	
	$current = getChecksumsCurrent($files);
	foreach ($current as $file=>$md5) {
		if ($files[$file] != $md5) {
			// change
			$changed++;
			echo "$file\n\tNew: $md5\n\tOld: " . $files[$file] . "\n\n";
		}
	}
	
	if (!$changed) echo "No files have changed.\n";
	else if ($changed == 1) echo "1 file has changed.\n";
	else echo "$changed files have changed.\n";
	
	
} else if ($vars['action'] == "recalc") {
	
	echo "Recalculating file checksums\n";
	
	$current = getChecksumsCurrent($files);
	
	if (is_writable(dirname($manifest))) {
		writeArrayToFile($current,$manifest);
	} else {
		echo "Manifest file is not writable.   Dumping output\n\n";
		print_r($files);
	}
	
} else if ($vars['action'] == "add") {
	
	if (count($vars['files']) == 0) exit("No files given.\n");
	foreach ($vars['files'] as $file) {
		if (is_readable(BASE.$file)) {
			if (isset($files[$file])) echo "Updating file $file\n";
			else echo "Adding file $file\n";
			$files[$file] = md5_file(BASE.$file);
		} else echo "WARNING: $file not readable.\n";
	}
	
	if (is_writable(dirname($manifest))) {
		writeArrayToFile($files,$manifest);
	} else {
		echo "Manifest file is not writable.   Dumping output\n\n";
		print_r($files);
	}

} else if ($vars['action'] == "dir") {

	foreach ($vars['files'] as $dir) {
		if (is_dir(BASE.$dir)) {
			$files = directoryChecksum($dir,$files);
			echo "Adding $dir\n";
		} else echo "WARNING: $dir is not a direcotry\n";
	}
	
	if (is_writable(dirname($manifest))) {
		writeArrayToFile($files,$manifest);
	} else {
		echo "Manifest file is not writable.   Dumping output\n\n";
		print_r($files);
	}

} else if ($vars['action'] == "remove") {
	
	if (count($vars['files']) == 0) exit("No files given.\n");
	foreach ($vars['files'] as $file) {
		echo "Removing file $file\n";
		unset($files[$file]);
	}
	
	if (is_writable(dirname($manifest))) {
		writeArrayToFile($files,$manifest);
	} else {
		echo "Manifest file is not writable.   Dumping output\n\n";
		print_r($files);
	}
	
}







function processArguments($args,$validator_callback = "") {
	$vars = array(
		"action"=>"",
		"type"=>"module",
		"name"=>"",
		"files"=>array()
	);
	
	for ($i = 1; $i < count($args); $i++) {
		switch ($args[$i]) {
			case "-a":
			case "--action":
				$vars["action"] = strtolower($args[++$i]);
				break;
			case "-t":
			case "--type":
				$vars["type"] = strtolower($args[++$i]);
				break;
			case "-n":
			case "--name":
				$vars["name"] = strtolower($args[++$i]);
				break;
			case "-f":
			case "--file":
				for ($i = $i + 1; $i < count($args); $i++) {
					$vars["files"][] = str_replace(BASE,"",realpath($args[$i]));
				}
				break;
		}
	}
	if (function_exists($validator_callback)) return $validator_callback($vars);
	else return $vars;
}

function usageOptions() {
	echo "Options:\n";
	echo "-a <action>\n";
	echo "--action <action>\n";
	echo "\tAction to perform.  Valid values are : add remove change recalc list dir\n";
	echo "-t <type>\n";
	echo "--type <type>\n";
	echo "\tType of extension.  Valid values are : system module theme subsystem\n";
	echo "-n <name>\n";
	echo "--name <name>\n";
	echo "\tName of the extension to work with\n";
	echo "-f <filename>\n";
	echo "--file <filename>\n";
	echo "\tName of file to add / remove from manifest.\n";
	echo "\n\n\n";
}

function validateArguments($vars) {
	switch ($vars["action"]) {
		case "add":
		case "remove":
		case "changes":
		case "recalc":
		case "list":
		case "dir":
			break;
		default:
			exit("Invalid action ('".$vars['action']."') specified.\n");
	}
	if ($vars['type'] == "") exit("You must specify an extension type.\n");
	if ($vars['name'] == "" && $vars['type'] != "system") exit("You must specify an extension name.\n");
	switch ($vars['type']) {
		case "module":
		case "theme":
		case "subsystem":
		case "system":
			break;
		default:
			exit("Invalid type ('".$vars['type']."') specified.\nValid types are: module theme subsystem\n");
	}
	
	return $vars;
}


function getManifestFile($type,$name) {
	if ($type == "system") return BASE."manifest.php";
	$dir = BASE.$type."s/$name/";
	if ($type == "subsystem") {
		$dir = BASE.$type."s/$name.";
	} else if (!is_readable($dir)) return "";
	return $dir."manifest.php";
}

function getChecksumsCurrent($files) {
	$newfiles = array();
	foreach (array_keys($files) as $file) {
		echo "Generating new checksum for $file\n";
		if (file_exists(BASE.$file)) $newfiles[$file] = md5_file(BASE.$file);
		else echo "WARNING: $file no longer exists.\n";
		if (file_exists(BASE.$file)) $newfiles[$file] = md5_file(BASE.$file);
		else echo "WARNING: $file no longer exists.\n";
	}
	return $newfiles;
}

function writeArrayToFile($arr,$file) {
	$fh = fopen($file,"w");
	fwrite($fh,"<?php\n\n");	
	fwrite($fh,"# Manifest file\n\n");
	fwrite($fh,"return array(\n");
	$total = count($arr);
	$i = 0;
	foreach ($arr as $key=>$value) {
		$i++;
		if (strpos($key,BASE) == 0) $key = str_replace(BASE,"",$key);
		fwrite($fh,"\t\"$key\"=>\"$value\"");
		if ($i < $total) {
			fwrite($fh,",\n");
		} else {
			fwrite($fh,"\n");
		}
	}
	fwrite($fh,");\n\n?>");
}


function directoryChecksum($dir,$arr) {
	if (is_readable(BASE.$dir)) {
		$dh = opendir(BASE.$dir);
		while (($file = readdir($dh)) !== false) {
			if (is_readable(BASE."$dir/$file") && $file != "." && $file != ".." && $file != "CVS") {
				if (is_file(BASE."$dir/$file")) $arr["$dir/$file"] = md5_file(BASE."$dir/$file");
				else $arr = directoryChecksum("$dir/$file",$arr);
			}
		}
	}
	return $arr;
}


?>
