<?php


#############################################################
# LINKMODULE
#############################################################
# Copyright (c) 2006 Eric Lestrade 
#
# Exponent is free software; you can redistribute
# it and/or modify it under the terms of the GNU
# General Public License as published by the Free
# Software Foundation; either version 2 of the
# License, or (at your option) any later version.
#
# GPL: http://www.gnu.org/licenses/gpl.txt
#
##############################################################

if (!defined("EXPONENT"))
	exit ("");
if (exponent_permissions_check('import', $loc) && exponent_permissions_check('edit', $loc)) {
	if(isset($_GET['mode'])) $mode=$_GET['mode'];
	elseif(isset($_POST['mode'])) $mode=$_POST['mode'];
	else $mode='';
	
	switch ($mode) {
		case 'preview' :
			$i18n = exponent_lang_loadFile('modules/linkmodule/actions/delete_all.php');
			if (!defined('SYS_FORMS')) require_once(BASE.'subsystems/forms.php');
			exponent_forms_initialize();
			$form = new form();
			$form->meta('action','delete_all');
			$form->meta('mode','confirmed');
			$form->location($loc);
			$form->register('export','',new buttongroupcontrol($i18n['delete_all'],'',$i18n['cancel']));
			$template = new template("linkmodule","_form_delete_all");
			$template->assign('form_html',$form->toHTML());
			$template->output();
			break;
		case 'confirmed' :
			$db->delete('link', "location_data='" . serialize($loc) . "'");
			exponent_flow_redirect();
			break;
		default :
			echo SITE_404_HTML;
			break;
	}
} else {
	echo SITE_403_HTML;
}
?>