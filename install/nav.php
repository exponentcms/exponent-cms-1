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

$pages = array(
	'welcome'=>'Welcome',
	'sanity'=>'Sanity Checks',
	'dbconfig'=>'Database Settings',
	'dbcheck'=>'Database Verification',
	'tmp_create_site'=>'Default Content',
	'final'=>'Finished'
);

echo '<table cellspacing="0" cellpadding="0" width="200">';
$done = 1;
foreach ($pages as $p=>$name) {
		if ($p == $_REQUEST['page']) $done = 0;
		echo '<tr>';
		echo '<td class="step'.($done ? ' completed':'').'">';
		echo (!$done ? $name : '<a href="?page='.$p.'" style="color: inherit; font: inherit; text-decoration: none; border-width: 0px;">'.$name.'</a>');
		echo '</td>';
		echo '</tr>';
}
echo '</table>';

?>