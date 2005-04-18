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

if (pathos_permissions_check('configure',$loc)) {
	if (pathos_template_getModuleViewFile($loc->mod,'_configure',false) == TEMPLATE_FALLBACK_VIEW) {
		$template = new template('common','_configure',$loc);
	} else {
		$template = new template($loc->mod,'_configure',$loc);
	}
	
	$hasConfig = 0;
	
	$submit = null;
	$form = null;
	
	if ($db->tableExists($_GET['module'].'_config') && class_exists($_GET['module'].'_config')) {
		$config = $db->selectObject($_GET['module'].'_config',"location_data='".serialize($loc)."'");
	
		$form = call_user_func(array($_GET['module'].'_config','form'),$config);
		
		if (isset($form->controls['submit'])) {
			$submit = $form->controls['submit'];
			$form->unregister('submit');
		}
		$hasConfig = 1; //We have some form of configuration
	}

	// Check for channels stuff.
	if (is_callable(array($loc->mod,'channelType'))) {
		$form->meta('supports_channels',1);
		// Maybe display a message explaining what the channels stuff does?
		$form->register(null,'',new htmlcontrol('<hr size="1" />Shared Content Settings'));
		
		if (!defined('SYS_CHANNELS')) include_once(BASE.'subsystems/channels.php');
		$channel = pathos_channels_getChannel($loc);
		
		$form->register('open_channel','Allow others to use content from here.',new checkboxcontrol($channel->is_open,true));
		
		$form->register('public_channel','Allow others to submit Shared Content for review',new checkboxcontrol($channel->name != '',true));
		$form->register('channel_name','Publishing Name',new textcontrol($channel->name));
	}

	$container = $db->selectObject('container',"internal='".serialize($loc)."'");
	if ($container) {
		$values = ($container->view_data != '' ? unserialize($container->view_data) : array());
		$form = pathos_template_getViewConfigForm($loc->mod,$container->view,$form,$values);
		
		if (isset($form->controls['submit'])) { // Still have a submit button.
			$submit = $form->controls['submit'];
			$form->unregister('submit');
			$hasConfig = 1;
		}
	}
	
	if ($submit !== null) {
		$form->register('submit','',$submit);
	}
	
	if ($hasConfig) {	
		// Place these last, so they ALWAYS run
		$form->location($loc);
		$form->meta('action','saveconfig');
		$form->meta('_common','1');
		
		$template->assign('form_html',$form->toHTML());
	}
	
	$template->assign('hasConfig',$hasConfig);
	
	$template->output();
} else {
	echo SITE_403_HTML;
}

?>