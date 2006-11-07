 
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
# $Id: rotate_form.php,v 1.2 2005/04/26 03:05:14 filetreefrog Exp $
##################################################

if (!defined('EXPONENT')) exit('');

// PERM CHECK
	$original = $db->selectObject('imageworkshop_image','id='.$_GET['id']);
	if ($original) {
		$working = $db->selectObject('imageworkshop_imagetmp','original_id='.$original->id);
		if ($working) {
			$original->file_id = $working->file_id;
		}
		$file = $db->selectObject('file','id='.$original->file_id);
		
		$template = new template('imageworkshopmodule','_form_rotate');
		$template->assign('original',$original);
		
		if (!defined('SYS_FORMS')) require_once(BASE.'subsystems/forms.php');
		exponent_forms_initialize();
		$form = new form();
		$form->location($loc);
		$form->meta('action','rotate_save');
		$form->meta('id',$original->id);
		$rotate_options = array(
			-90=>'90 degrees Clockwise',
			90=>'90 degrees Counter-clockwise',
			180=>'180 degrees',
		);
		$form->register('degrees','Rotate',new dropdowncontrol(0,$rotate_options));
		$form->register(null,'',new buttongroupcontrol('Save','Reset','Cancel'));
		
		$template->assign('form_html',$form->toHTML());
		
		$template->output();
	} else {
		echo SITE_404_HTML;
	}
// END PERM CHECK

?>