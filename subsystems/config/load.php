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

//overides function html entity decode
function exponent_unhtmlentities( $str )
{
	$trans = get_html_translation_table(HTML_ENTITIES);
	$trans['&apos;'] = '\'';
	$trans=array_flip($trans);
	
	$trans['&apos;'] = '\'';
	$trans['&#039;'] = '\'';
	return strtr($str, $trans);
}

@include_once(BASE."conf/config.php");
if (is_readable(BASE."conf/extensions")) {
	$dh = opendir(BASE."conf/extensions");
	while (($file = readdir($dh)) !== false) {
		if (is_readable(BASE."conf/extensions/$file") && substr($file,-13,13) == ".defaults.php") {
			include_once(BASE."conf/extensions/$file");
		}
	}
}

?>