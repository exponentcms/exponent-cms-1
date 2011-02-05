<?php

##################################################
#
# Copyright (c) 2004-2011 OIC Group, Inc.
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
##################################################

if (!defined('EXPONENT')) exit('');

$qna = null;
if (isset($_GET['id'])) {
	$qna = $db->selectObject('faq','id='.$_GET['id']);
	if ($qna != null) {
		$loc = unserialize($qna->location_data);
	} 
}

if (exponent_permissions_check('manage',$loc)) {
	$config = $db->selectObject('faqmodule_config',"location_data='".serialize($loc)."'");
	if ($config == null) {
		$config->enable_categories = 0;
	}

	$i18n = exponent_lang_loadFile('modules/faqmodule/actions/edit_faq.php');

	$form = faq::form($qna);
	$form->location($loc);
	$form->meta('action','save_faq');
			
	$template = new template('faqmodule','_form_editfaq',$loc);
	if ($config->enable_categories) {
		$ddopts = array();
		$ddopts[0] = '<i>'.$i18n['no_category'].'</i>';
		foreach ($db->selectObjects('category',"location_data='".serialize($loc)."' ORDER BY rank ASC") as $opt) {
			$ddopts[$opt->id] = $opt->name;
		}
		if (!isset($item->category_id)) $item->category_id = null;
		$form->registerBefore('question','categories',$i18n['categories'],new dropdowncontrol($qna->category_id,$ddopts));
	}
	$template->assign('is_edit',(isset($qna->id) ? 1 : 0));
	$template->assign('form_html',$form->toHTML());
	$template->output();
} else {
	echo SITE_403_HTML;
}

?>
