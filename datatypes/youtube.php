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

class youtube {
	function form($youtube = null) {
		return null;
	}
	
	function update($values,$object = null) {
		$object->height = $values['height'];
		$object->width = $values['width'];
		$object->url = $values['url'];
		$object->description = $values['description'];
		$object->name = $values['name'];
		return $object;
	}
}

?>
