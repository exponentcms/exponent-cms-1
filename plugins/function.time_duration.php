<?php

##################################################
#
# Copyright (c) 2004-2005 James Hunt and the OIC Group, Inc.
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

function smarty_function_time_duration($params,&$smarty) {
	$duration = null;
	if (isset($params['duration'])) {
		$duration = $params['duration'];
	} else {
		$duration = $params['start'] - $params['end'];
	}
	if ($duration < 0) $duration *= -1;
	
	$type = strtolower(isset($params['type']) ? $params['type'] : "hms");
	
	$slots = array();
	if (strstr($type,"d") !== false) {
		$slots["d"] = floor($duration / 86400);
		$duration -= $slots["d"]*86400;
	}
	if (strstr($type,"h") !== false) {
		$slots["h"] = floor($duration / 3600);
		$duration -= $slots["h"] * 3600;
	}
	if (strstr($type,"m") !== false) {
		$slots["m"] = floor($duration / 60);
		$duration -= $slots["m"] * 60;
	}
	if (strstr($type,"s") !== false) $slots["s"] = $duration;
	
	$smarty->assign($params['assign'],$slots);
}

?>