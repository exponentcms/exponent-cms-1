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

if (pathos_permissions_check('workflow',pathos_core_makeLocation('administrationmodule'))) {
	$policy = null;
	if (isset($_GET['id'])) $policy = $db->selectObject('approvalpolicy','id='.$_GET['id']);
	
	$form = approvalpolicy::form($policy);
	$form->meta('module','workflow');
	$form->meta('action','admin_savepolicy');
	
	$template = new template('workflow','_form_editpolicy',$loc);
	$template->assign('is_edit',(isset($policy->id) ? 1 : 0));
	$template->assign('form_html',$form->toHTML());
	$template->output();
}

?>