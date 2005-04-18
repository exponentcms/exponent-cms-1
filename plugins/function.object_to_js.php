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

function smarty_function_object_to_js($params,&$smarty) {
	
	if (!defined("SYS_JAVASCRIPT")) require_once(BASE."subsystems/javascript.php");
	
	echo "var ".$params['name']." = new Array();\n";
	if (isset($params['objects']) && count($params['objects']) > 0) {
		
		//Write Out DataClass. This is generated from the data object.
		echo pathos_javascript_class($params['objects'][0],"class_".$params['name']);
		
		//This will load up the data...
		foreach ($params['objects'] as $object) {
			echo $params['name'].".push(".pathos_javascript_object($object,"class_".$params['name']).");\n";
			//Stuff in a unique id for reference.
			echo $params['name']."[".$params['name'].".length-1].__ID = ".$params['name'].".length-1;\n";		
		}
	}
	
	return "";
}

?>