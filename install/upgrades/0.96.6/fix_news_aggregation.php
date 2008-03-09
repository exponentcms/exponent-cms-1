<?php
##################################################
#
# Copyright (c) 2007 OIC Group, Inc
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

if (!defined('SYS_MODULES')) include_once(BASE.'subsystems/modules.php');

$configs = $db->selectObjects('newsmodule_config');

$sql = 'ALTER TABLE '.DB_TABLE_PREFIX.'_newsmodule_config MODIFY aggregate VARCHAR(200) NOT NULL';
$updateThese = $db->sql($sql);

$news = exponent_modules_getModuleInstancesByType('newsmodule');
foreach ($configs as $config) {
	$all_news_modules = array();
	if ($config->aggregate == 1) {
		$loc = unserialize($config->location_data);
		foreach ($news as $src=>$mod) {
			if ($src != $loc->src) $all_news_modules[] = $src;
		}
	}

	$config->aggregate = serialize($all_news_modules);
	$db->updateObject($config, 'newsmodule_config');
}

?>
