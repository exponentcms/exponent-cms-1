<?php

##################################################
#
# Copyright (c) 2004-2005 James Hunt and the OIC Group, Inc.
# All Changes as of 6/1/05 Copyright 2005 James Hunt
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

class weblog_post {
	function form($object) {
		$i18n = pathos_lang_loadFile('datatypes/weblog_post.php');
	
		if (!defined('SYS_FORMS')) require_once(BASE.'subsystems/forms.php');
		pathos_forms_initialize();
		
		$form = new form();
		if (!isset($object->id)) {
			$object->title = '';
			$object->internal_name = '';
			$object->body = '';
			global $user;
			$object->poster = $user->id;
			$object->is_private = 0;
			$object->is_draft = 0;
		} else {
			$form->meta('id',$object->id);
		}
		
		$form->register('title',$i18n['title'], new textcontrol($object->title));
		$form->register('body',$i18n['body'], new htmleditorcontrol($object->body));
		$form->register('is_private',$i18n['is_private'], new checkboxcontrol($object->is_private));
		$form->register('submit','',new buttongroupcontrol($i18n['save'],'',$i18n['cancel']));
		
		return $form;
	}
	
	function update($values,$object) {
		$object->title = $values['title'];
		$object->internal_name = preg_replace('/--+/','-',preg_replace('/[^A-Za-z0-9_]/','-',$values['internal_name']));
		$object->body = $values['body'];
		$object->is_private = (isset($values['is_private']) ? 1 : 0);
		$object->is_draft = (isset($values['is_draft']) ? 1 : 0);
		return $object;
	}
}

?>