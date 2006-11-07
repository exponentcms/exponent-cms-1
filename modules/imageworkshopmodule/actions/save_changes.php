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
# $Id: save_changes.php,v 1.1 2005/04/18 01:27:23 filetreefrog Exp $
##################################################

if (!defined('EXPONENT')) exit('');

// PERM CHECK
	$original = $db->selectObject('imageworkshop_image','id='.$_GET['id']);
	if ($original) {
		$file = $db->selectObject('file','id='.$original->file_id);
		$working = $db->selectObject('imageworkshop_imagetmp','original_id='.$original->id);
		$wfile = $db->selectObject('file','id='.$working->file_id);
		
		rename(BASE.$wfile->directory.'/'.$wfile->filename,BASE.$file->directory.'/'.$file->filename);
		
		$db->delete('file','id='.$wfile->id);
		$db->delete('imageworkshop_imagetmp','id='.$working->id);
		exponent_flow_redirect();
	} else {
		echo SITE_404_HTML;
	}
// END PERM CHECK

?>