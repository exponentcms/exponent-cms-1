<?php

##################################################
#
# Copyright 2004 James Hunt and OIC Group, Inc.
#
# This file is part of Exponent
#
# Exponent is free software; you can redistribute
# it and/or modify it under the terms of the GNU
# General Public License as published by the Free
# Software Foundation; either version 2 of the
# License, or (at your option) any later version.
#
# Exponent is distributed in the hope that it
# will be useful, but WITHOUT ANY WARRANTY;
# without even the implied warranty of
# MERCHANTABILITY or FITNESS FOR A PARTICULAR
# PURPOSE.  See the GNU General Public License
# for more details.
#
# You should have received a copy of the GNU
# General Public License along with Exponent; if
# not, write to:
#
# Free Software Foundation, Inc.,
# 59 Temple Place,
# Suite 330,
# Boston, MA 02111-1307  USA
#
# $Id$
##################################################

function smarty_modifier_format_date($timestamp,$format) {
	// Do some sort of mangling of the format for windows.
	// reference the PHP_OS constant to figure that one out.
	if (strtolower(substr(PHP_OS,0,3)) == 'win') {
		// We are running on a windows platform.  Run the replacements
		
		// Preserve the '%%'
		$toks = explode('%%',$format);
		for ($i = 0; $i < count($toks); $i++) {
			$toks[$i] = str_replace(
				array('%D','%e','%g','%G','%h','%r','%R','%T','%l'),
				array('%m/%d/%y','%#d','%y','%Y','%b','%b','%I:%M:%S %p','%H:%M','%H:%M:%S','%#H'),
				$toks[$i]);
		}
		$format = join('%%',$toks);
	}
	return strftime($format,$timestamp);
}

?>