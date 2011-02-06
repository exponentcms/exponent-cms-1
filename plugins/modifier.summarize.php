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

function smarty_modifier_summarize($string, $strtype, $type) {
	$sep = ($strtype == "html" ? array("</p>","</div>") : array("\r\n","\n","\r"));
	$origstring = $string;
	switch ($type) {
		case "para":
			foreach ($sep as $s) {
				$para = explode($s,$string);
				$string = $para[0];
			}
			if (strlen($string) < strlen($origstring)-4) {$string .= " ...";}
			return strip_tags($string);
			break;
		case "paralinks":
			foreach ($sep as $s) {
				$para = explode($s,$string);
				$string = $para[0];
			}
			if (strlen($string) < strlen($origstring)-4) {$string .= " ...";}
			return strip_tags($string,'<a>');
			break;			
		default:
			$words = explode(" ",strip_tags($string));
			$string = implode(" ",array_slice($words,0,$type+0));
			if (strlen($string) < strlen($origstring)-4) {$string .= " ...";}
			return $string;
			break;
	}
}

?>
