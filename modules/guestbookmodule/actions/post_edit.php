<?php
#############################################################
# GUESTBOOKMODULE
#############################################################
# Copyright (c) 2005-2006 Dirk Olten, http://www.extrabyte.de
#
# version 0.5:	Developement-Version
# version 1.0:	1st release for Exponent v0.93.3
# version 1.2:	Captcha added
# version 2.0:	now compatible to Exponent v0.93.5
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

if (!defined('EXPONENT')) exit('');

$post = null;
$iloc = null;
if (isset($_GET['id'])) {
	$post = $db->selectObject('guestbook_post','id='.intval($_GET['id']));
	$loc = unserialize($post->location_data);
	$iloc = exponent_core_makeLocation($loc->mod,$loc->src,$post->id);
}
	$form = guestbook_post::form($post);
	$form->location($loc);
	$form->meta('action','post_save');
	if (SITE_USE_CAPTCHA && EXPONENT_HAS_GD) {
		$i18n = exponent_lang_loadFile('modules/guestbookmodule/views/Default.php');
		$form->registerBefore('submit',null,'',new htmlcontrol(sprintf($i18n['captcha_description'],'<img src="'.PATH_RELATIVE.'captcha.php" />'),false));
		$form->registerBefore('submit','captcha_string','',new textcontrol('',6));
	}
	$template = new template('guestbookmodule','_form_postEdit',$loc);
	$template->assign('form_html',$form->toHTML());
	$template->assign('is_edit', (isset($_GET['id']) ? 1 : 0) );
	$template->output();
?>