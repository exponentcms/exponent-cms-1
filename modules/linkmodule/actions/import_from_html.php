<?php

#############################################################
# LINK MODULE
#############################################################
# Written by Eric Lestrade 
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
if(exponent_permissions_check('import',$loc)) {
	global $db;
	$mode=$_GET['mode'];
	// There may be more modes in the future :
	//	  - import for a pasted text with links
	//   - import of all external links of a website
	//   - import of all internal links of a website
	$authorized_modes=array('webpage');
	if(in_array($mode,$authorized_modes)) {
		$i18n = exponent_lang_loadFile('modules/linkmodule/actions/import_from_html.php');
		$location = serialize($loc);
		
		if (!defined('SYS_FORMS')) require_once(BASE.'subsystems/forms.php');
		exponent_forms_initialize();
		$form = new form();

		$form->meta('action','import_from_html_preview');
		$form->meta('mode',$mode);
		$form->location($loc);
		
		$template = new template('linkmodule','_form_import_from_html');

	    $allcats = $db->selectObjects('category', "location_data='".serialize($loc)."'");
	    if (!defined('SYS_SORTING')) require_once(BASE.'subsystems/sorting.php');
	    usort($allcats, "exponent_sorting_byRankAscending");
	    $catarray = array();
	    $catarray[0]="&lt;".exponent_lang_loadKey('modules/linkmodule/actions/edit_link.php','top_level')."&gt;";
	    foreach ($allcats as $cat) {
	       $catarray[$cat->id] = $cat->name;
	    }			
	    $form->register('categories', exponent_lang_loadKey('modules/linkmodule/actions/edit_link.php','select_category'), new dropdowncontrol(0, $catarray));
		
		$form->register('url','URL',new textcontrol('http://',80,false,200));
		$form->register('preview','',new buttongroupcontrol($i18n['preview'],'',$i18n['cancel']));
		$template->assign('form_html',$form->toHTML());
		$template->assign('mode',$mode);
		$template->output();
	}
	else {	echo SITE_404_HTML;}
}
else {	echo SITE_403_HTML;}
?>
