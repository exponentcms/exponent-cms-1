<?php

$def_a = unserialize(file_get_contents($argv[1]));
$def_b = unserialize(file_get_contents($argv[2]));

$ver_a = basename($argv[1]);
$ver_b = basename($argv[2]);

$fname = "pathos_backup_" . join('',explode('.',$ver_a));

echo "<?php\r\n// Converting EQL version $ver_a to $ver_b\r\n\r\n";

echo "if (!defined('PATHOS')) exit('');\r\n\r\n";


foreach ($def_a as $table=>$fields) {
	if (!isset($def_b[$table])) {
		// Missing table in newer version
		echo "// Table '$table' was removed in $ver_b\r\n";
		echo 'function '.$fname.'_'.$table.'($db,$object) {'."\r\n";
		echo "\t// do nothing\r\n}\r\n\r\n";
	} else {
		$fields_changed = false;
		foreach ($fields as $field) {
			if (!in_array($field,$def_b[$table])) {
				// Field removed in newer version
				echo "// Field '$field' was removed from '$table' in $ver_b\r\n";
				$fields_changed = true;
			}
		}
		foreach ($def_b[$table] as $field) {
			if (!in_array($field,$fields)) {
				// Field aded in newer version
				echo "// Field '$field' was added to '$table' in $ver_b\r\n";
				$fields_changed = true;
			}
		}
		if ($fields_changed) {
			echo 'function '.$fname.'_'.$table.'($db,$object) {'."\r\n";
			echo "\t// IMPLEMENTME\r\n";
			echo "\r\n\t\$db->insertObject(\$object,'$table');\r\n";
			echo "}\r\n\r\n";
		}
	}
}

foreach ($def_b as $table=>$fields) {
	if (!isset($def_a[$table])) {
		// Missing table in older version
		echo "// Table '$table' was added in $ver_b\r\n";
	}
}

echo "\r\n?>\r\n";

?>