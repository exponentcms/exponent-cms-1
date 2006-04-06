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

return array(
	'Site Maintenance Settings',
	array(
		'MAINTENANCE_MODE'=>array(
			'title'=>'Maintenance Mode?',
			'description'=>'Whether or not the site is in maintenance mode.  While in maintenance mode, only administrators and acting administrators will be allowed to login.',
			'control'=>new checkboxcontrol(false,true)
		),
		'MAINTENANCE_MSG_HTML'=>array(
			'title'=>'Maintenance Mode Message',
			'description'=>'A message to display to all non-administrators visiting the site.',
			'control'=>new texteditorcontrol()
		)
	)
);

?>