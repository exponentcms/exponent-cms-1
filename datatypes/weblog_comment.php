<?php

##################################################
#
# Copyright 2004 James Hunt and OIC Group, Inc.
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

class weblog_comment {
	function form($object) {
		pathos_lang_loadDictionary('standard','core');
		pathos_lang_loadDictionary('modules','weblogmodule');
		
		if (!defined('SYS_FORMS')) include_once(BASE.'subsystems/forms.php');
		pathos_forms_initialize();
		
		$form = new form();
		if (!isset($object->id)) {
			global $db;
			$post = $db->selectObject('weblog_post','id='.$_GET['parent_id']);
			
			$object->title = 'Re: ' . $post->title;
			#$object->body = '<br /><br />-' . chunk_split($post->body,60,'<br />-');
			$object->body = '';
			$form->meta('parent_id',$_GET['parent_id']);
		} else {
			$form->meta('id',$object->id);
		}
		
		$form->register('title',TR_WEBLOGMODULE_COMMENTTITLE,new textcontrol($object->title));
		$form->register('body',TR_WEBLOGMODULE_COMMENTBODY, new htmleditorcontrol($object->body));
		$form->register('submit','',new buttongroupcontrol(TR_CORE_SAVE,'',TR_CORE_CANCEL));
		
		pathos_forms_cleanup();
		return $form;
	}
	
	function update($values,$object) {
		$object->title = $values['title'];
		$object->body = $values['body'];
		return $object;
	}
}

?>