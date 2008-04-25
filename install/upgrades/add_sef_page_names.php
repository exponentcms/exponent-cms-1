<?php
##################################################
#
# Copyright (c) 2007 OIC Group, Inc.
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

class add_sef_page_names extends upgradescript {
	protected $from_version = '0.96.3';
        protected $to_version = '0.97.0';

	function name() {
		return "Adding SEF Page Names";
	}

	function upgrade() {
		global $db;
		global $router;

		//$sql = "UPDATE ".DB_TABLE_PREFIX."_section set sef_name = REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(LOWER(REPLACE(name, ' - ', '-')), ' ', '-'), '?', ''), '\"', ''), '\'', ''), '(', ''), ')', ''), '&', '-and-'), '/', '-'), 'nbsp;', '') where sef_name = ''";
		//$updateThese = $db->sql($sql);
		$sections = $db->selectObjects('section');
		foreach ($sections as $section) {
			if (empty($section->sef_name)) {
				$sef_name = router::encode($section->name);
				if(section::isDuplicateName($sef_name)) $sef_name .= "-".$section->id;
				$section->sef_name = $sef_name;
				$db->updateObject($section, 'section');
			}
		}
		return "Complete";
	}
}

?>
