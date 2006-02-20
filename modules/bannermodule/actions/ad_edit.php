<?php

##################################################
#
# Copyright (c) 2004-2005 OIC Group, Inc.
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

if (!defined('PATHOS')) exit('');

$banner = null;
if (isset($_GET['id'])) {
	$banner = $db->selectObject('banner_ad','id='.intval($_GET['id']));
	if ($banner) {
		$loc = unserialize($banner->location_data);
	}
}

if (pathos_permissions_check('manage',$loc)) {
	$i18n = pathos_lang_loadFile('modules/bannermodule/actions/ad_edit.php');

	$form = banner_ad::form($banner);
	$form->location($loc);
	$form->meta('action','ad_save');
	
	if (is_really_writable(BASE.'files/bannermodule/'.$loc->src)) {
		$form->registerBefore('submit','file',$i18n['file'],new uploadcontrol());
	} else {
		$form->controls['submit']->disabled = 1;
		$form->registerBefore('name',null,'',new htmlcontrol('<div class="error">'.$i18n['no_upload'].'</div>'));
	}
	
	$template = new template('bannermodule','_form_ad_edit');
	$template->assign('form_html',$form->toHTML());
	$template->assign('is_edit',(isset($_GET['id']) ? 1 : 0));
	$template->output();
} else {
	echo SITE_403_HTML;
}

?>
