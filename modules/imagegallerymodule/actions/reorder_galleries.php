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
if (exponent_permissions_check('edit',$loc)) {
	if ( isset ($_GET['a']) && isset ($_GET['b']) ) {
		$db->switchValues('imagegallery_gallery','galleryorder',intval($_GET['a']),intval($_GET['b']),"location_data='" . serialize($loc)."'");
	}
	$template = new template('imagegallerymodule','_reorder_galleries', $loc);
	$template->assign('galleries',$db->selectObjects('imagegallery_gallery',"location_data='".serialize($loc)."'", 'galleryorder ASC'));
	$template->output();
	
} else {
	echo SITE_403_HTML;
}

?>
