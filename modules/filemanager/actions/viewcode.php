<?php

##################################################
#
# Copyright (c) 2004-2006 OIC Group, Inc.
# Written and Designed by James Hunt
#
# This file is part of Exponent
#
# Exponent is free software; you can redistribute
# it and/or modify it under the terms of the GNU
# General Public License as published by the Free
# Software Foundation; either version 2 of the
# License, or (at your option) any later version.
#
# GPL: http://www.gnu.org/licenses/gpl.txt
#
##################################################

if (!defined('EXPONENT')) exit('');

$i18n = exponent_lang_loadFile('modules/filemanager/actions/viewcode.php');


$file = url_decode($_GET['file']);
$path = realpath(BASE.$file);
if (strpos($path,BASE) !== 0) {
	echo $i18n['security'];
} else {
	$ext = substr($path,-3,3);
	if ($ext != 'tpl' && $ext != 'php') {
		echo $i18n['bad_type'];
	} else {
		$contents = file_get_contents($path);
		if ($ext == 'php') {
			if (!defined('SYS_INFO')) include_once(BASE.'subsystems/info.php');	
			echo exponent_info_highlightPHP($contents);
		} else {
			echo '<pre>'.$contents.'</pre>';
		}
	}

}

?>
