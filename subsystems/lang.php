<?php

define('SYS_LANG',1);

function pathos_lang_list() {
	$dir = BASE.'subsystems/lang';
	$langs = array();
	if (is_readable($dir)) {
		$dh = opendir($dir);
		while (($f = readdir($dh)) !== false) {
			if (substr($f,-4,4) == '.php') {
				$info = include($dir.'/'.$f);
				$langs[substr($f,0,-4)] = $info['name'] . ' -- ' . $info['author'];
			}
		}
	}
	return $langs;
}

function pathos_lang_initialize() {
	if (!defined('LANG')) {
		if (is_readable(BASE.'subsystems/lang/'.USE_LANG.'.php')) {
			define('LANG',USE_LANG); // Lang file exists.
		} else {
			define('LANG','en'); // Fallback to 'en' if language file not present.
		}
		$info = include(BASE.'subsystems/lang/'.LANG.'.php');
		setlocale(LC_ALL,$info['locale']);
		// For view resolution
		define('DEFAULT_VIEW',$info['default_view']);
	}
}

function pathos_lang_loadDictionary($type,$dictionary) {
	include_once(BASE.'subsystems/lang/'.LANG.'/'.$type.'/'.$dictionary.'.php'); // Will define constants.
}

?>