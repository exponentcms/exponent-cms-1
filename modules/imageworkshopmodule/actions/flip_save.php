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
# $Id: flip_save.php,v 1.2 2005/04/26 03:05:14 filetreefrog Exp $
##################################################

if (!defined('EXPONENT')) exit('');

// PERM CHECK
	$original = $db->selectObject('imageworkshop_image','id='.$_POST['id']);
	if ($original) {
		$working = $db->selectObject('imageworkshop_imagetmp','original_id='.$original->id);
		if ($working) {
			$original->file_id = $working->file_id;
		}
		
		$file = $db->selectObject('file','id='.$original->file_id);
		$file->_path = BASE.$file->directory.'/'.$file->filename;
		$wfile = null;
		if ($working == null) {
			$working = imageworkshopmodule::createWorkingCopy($original,$file);
			$wfile = $working->_file;
			unset($working->_file);
		} else {
			$wfile = $db->selectObject('file','id='.$working->file_id);
		}
		$wfile->_path = BASE.$wfile->directory.'/'.$wfile->filename;
		
		
		if (!defined('SYS_IMAGE')) require_once(BASE.'subsystems/image.php');
		$newimg = exponent_image_flip($file->_path,($_POST['flip'] == 'h'));
		if (is_resource($newimg)) {
			exponent_image_output($newimg,exponent_image_sizeinfo($file->_path),$wfile->_path);
			exponent_flow_redirect();
		} else {
			echo $newimg;
		}
	} else {
		echo SITE_404_HTML;
	}
// END PERM CHECK

?>