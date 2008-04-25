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

class rename_monthly_view extends upgradescript {
	protected $from_version = '0.96.3';
        protected $to_version = '0.96.5';

	function name() {
		return "Moving Calendar Monthly View to Default";
	}

	function upgrade() {
		global $db;

		$updateThese = $db->selectObjects('container', "internal LIKE '%calendarmodule%' AND view='Monthly'");

		foreach ($updateThese as $module) {
			$module->view = "Default";
			$db->updateObject($module, 'container');
		}
		return "Complete";
	}
}

?>
