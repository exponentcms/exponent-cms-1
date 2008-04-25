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

class move_viewsc extends upgradescript {
	protected $from_version = '0.96.3';
        protected $to_version = '0.96.5';

	function name() {
		return "Move views_c to tmp";
	}

	function upgrade() {
		if (!file_exists(BASE . "tmp/views_c")) {
			rename(BASE . "views_c", BASE . "tmp/views_c");
		}
		return "Complete";
	}
}

?>
