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
# $Id: edit_slide.php,v 1.4 2005/02/23 23:30:27 filetreefrog Exp $
##################################################

if (!defined('EXPONENT')) exit('');

$slide = null;
if (isset($_GET['id'])) {
	$slide = $db->selectObject('slideshow_slide','id='.$_GET['id']);
	if ($slide) {
		$loc = unserialize($slide->location_data);
	}
}

if (	($slide == null && exponent_permissions_check('create_slide',$loc)) ||
	($slide != null && exponent_permissions_check('edit_slide',$loc))
) {
	$form = slideshow_slide::form($slide);
	$form->location($loc);
	$form->meta('action','save_slide');
	
	$template = new template('slideshowmodule','_form_editSlide',$loc);
	$template->assign('is_edit',($slide != null ? 1 : 0));
	$template->assign('form_html',$form->toHTML());
	$template->output();
}

?>