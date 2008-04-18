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

class move_iconset extends upgradescript {
	protected $from_version = '0.96.3';
        protected $to_version = '0.96.5';

	function name() {
		return "Move Iconset to Common Theme";
	}

	function upgrade() {
		if (!file_exists(BASE . "themes/common/images/icons")) {
			rename(BASE . "iconset", BASE . "themes/common/images/icons");
		}
		return "Complete";
	}
}

?>
