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

if (!defined('PATHOS')) exit('');

$collection = null;
if (isset($_GET['id'])) {
	$collection = $db->selectObject('file_collection','id='.$_GET['id']);
}
$loc = pathos_core_makeLocation('filemanagermodule');

if ($collection) {
	// PERM CHECK
		if (!defined('SYS_FORMS')) require_once(BASE.'subsystems/forms.php');
		pathos_forms_initialize();
		
		$form = new form();
		$form->meta('module','filemanagermodule');
		$form->meta('action','save_upload');
		$form->meta('collection_id',$collection->id);
		
		$form->register('name','Name',new textcontrol());
		$form->register('file','File',new uploadcontrol());
		$form->register('submit','',new buttongroupcontrol('Save','','Cancel'));
		
		echo $form->toHTML();
	// END PERM CHECK
} else {
	echo SITE_404_HTML;
}

?>