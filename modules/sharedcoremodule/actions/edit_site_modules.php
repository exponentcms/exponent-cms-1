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

// PERM CHECK
	$site = null;
	if (isset($_GET['site_id'])) $site = $db->selectObject('sharedcore_site','id='.$_GET['site_id']);
	if ($site) {
		/////
		$form = sharedcore_site::linkForm($site);
		$form->meta('module','sharedcoremodule');
		if (isset($site->id)) {
			$form->meta('action','save_extensions'); // Save without db conf if edit
		} else {
			// Need to go through initial db config for new sites.
			$form->meta('action','edit_site_dbconf');
		}
		
		$form->meta('core_id',$_POST['core_id']);
		$form->meta('name',$_POST['name']);
		$form->meta('path',$_POST['path']);
		
		$template = new template('sharedcoremodule','_form_editSite');
		$template->assign('form_html',$form->toHTML());
		$template->output();
	} else echo SITE_404_HTML;
// END PERM CHECK

?>