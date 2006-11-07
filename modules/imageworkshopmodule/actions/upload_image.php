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
# $Id: upload_image.php,v 1.1 2005/04/18 01:27:23 filetreefrog Exp $
##################################################

if (!defined('EXPONENT')) exit('');

// PERM CHECK
	$image = imageworkshop_image::update($_POST,null);
	$file = file::update('file','files/imageworkshopmodule/'.$loc->src,null,uniqid('').'_'.$_FILES['file']['name']);
	$image->file_id = $db->insertObject($file,'file');
	$image->is_upload = 1;
	$image->location_data = serialize($loc);
	$image->rank = $db->max('imageworkshop_image','rank','location_data',"location_data='".$image->location_data."'");
	if ($image->rank == null) {
		$image->rank = 0;
	} else {
		$image->rank++;
	}
	$db->insertObject($image,'imageworkshop_image');
	exponent_flow_redirect();
// END PERM CHECK

?>