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

$banner = null;
if (isset($_GET['id'])) {
	$banner = $db->selectObject('banner_ad','id='.$_GET['id']);
	if ($banner) {
		$loc = unserialize($banner->location_data);
	}
}

if (pathos_permissions_check('manage',$loc)) {
	$form = banner_ad::form($banner);
	$form->location($loc);
	$form->meta('action','ad_save');
	
	pathos_lang_loadDictionary('modules','bannermodule');
	
	if (is_writable(BASE.'files/bannermodule/'.$loc->src)) {
		$form->registerBefore('submit','file',TR_BANNERMODULE_BANNERIMAGE,new uploadcontrol());
	} else {
		$form->controls['submit']->disabled = 1;
		$form->registerBefore('name',null,'',new htmlcontrol('<div class="error">'.TR_BANNERMODULE_NOUPLOAD.'</div>'));
	}
	
	$template = new template('bannermodule','_form_ad_edit');
	$template->assign('form_html',$form->toHTML());
	$template->assign('is_edit',isset($_GET['id']));
	$template->output();
} else {
	echo SITE_403_HTML;
}

?>