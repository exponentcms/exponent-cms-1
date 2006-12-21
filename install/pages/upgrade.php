<?php

##################################################
#
# Copyright (c) 2004-2006 OIC Group, Inc.
# Copyright (c) 2006 Maxim Mueller
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

$errors = array();

include_once(BASE . 'modules/administrationmodule/actions/installtables.php');

//Run the upgrade scripts
$upgrade_dir = 'upgrades/'.$_POST['from_version'];
if (is_readable($upgrade_dir)) {
	$dh = opendir($upgrade_dir);
        while (($file = readdir($dh)) !== false) {
        	if (is_readable($upgrade_dir.'/'.$file) && ($file != '.' && $file != '..' && $file != '.svn') ) {
                	include_once($upgrade_dir.'/'.$file);
                }
       	}
}
	
$i18n = exponent_lang_loadFile('install/pages/upgrade.php');

if (count($errors)) {
	echo $i18n['errors'].'<br /><br /><br />';
	foreach ($errors as $e) echo $e . '<br />';
} else {
	echo $i18n['success'];;
	echo '<br /><br /><a href="?page=final">'.$i18n['complete'].'</a>.';
}

?>