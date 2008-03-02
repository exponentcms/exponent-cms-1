<?php

##################################################
#
# Copyright (c) 2004-2005 OIC Group, Inc.
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
# $Id: articlemodule_config.php,v 1.3 2005/04/25 19:02:15 filetreefrog Exp $
##################################################

class resourcesmodule_config {
	function form($object) {
		if (!defined('SYS_FORMS')) require_once(BASE.'subsystems/forms.php');
		exponent_forms_initialize();
		
		$form = new form();
		if (!isset($object->id)) {
			$object->allow_anon_downloads = 1;
			$object->enable_podcasting = 0;
			$object->feed_title = "";
			$object->feed_desc = "";
		} else {
			$form->meta('id',$object->id);
		}
		
		$form->register('allow_anon_downloads','Allow anonymous downloads?',new checkboxcontrol($object->allow_anon_downloads,true));
		$form->register(null,'',new htmlcontrol('<br /><div class="moduletitle">Podcasting Configuration</div><hr size="1" />'));
		$form->register('enable_podcasting','Enable Podcasting?', new checkboxcontrol($object->enable_podcasting,true));
		$form->register('feed_title',"Podcast Title",new textcontrol($object->feed_title,35,false,75));
		$form->register('feed_desc',"Podcast Description",new texteditorcontrol($object->feed_desc));
		$form->register('submit','',new buttongroupcontrol('Save','','Cancel'));
		return $form;
	}
	
	function update($values,$object) {
		$object->allow_anon_downloads = isset($values['allow_anon_downloads']);
		$object->enable_podcasting = isset($values['enable_podcasting']);
		$object->feed_title = $values['feed_title'];
		$object->feed_desc = $values['feed_desc'];
		return $object;
	}
}

?>
