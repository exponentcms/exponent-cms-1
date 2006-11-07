<?php

##################################################
#
# Copyright (c) 2004-2005 OIC Group, Inc.
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
# $Id: delete_slide.php,v 1.4 2005/02/23 23:30:27 filetreefrog Exp $
##################################################

if (!defined('EXPONENT')) exit('');

$slide = null;
$file = null;
if (isset($_GET['id'])) {
	$slide = $db->selectObject('slideshow_slide','id='.$_GET['id']);
	if ($slide) {
		$file = $db->selectObject('file','id='.$slide->file_id);
	}
}

if ($slide) {
	$loc = unserialize($slide->location_data);
	
	if (exponent_permissions_check('delete_slide',$loc)) {
		$db->delete('slideshow_slide','id='.$slide->id);
		if ($file) {
			file::delete($file);
			$db->delete("file","id=".$file->id);
		}
		exponent_flow_redirect();
	} else {
		echo SITE_403_HTML;
	}
} else {
	echo SITE_404_HTML;
}

?>