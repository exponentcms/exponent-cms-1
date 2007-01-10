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
# $Id: bbmodule_config.php,v 1.6 2005/04/26 03:07:22 filetreefrog Exp $
##################################################

class bbmodule_config {
	function form($object) {
		if (!defined('SYS_FORMS')) require_once(BASE.'subsystems/forms.php');
		exponent_forms_initialize();
		
		$form = new form();
		if (!isset($object->id)) {
			$object->email_title_thread = 'Exponent Forum : New Thread Posted';
			$object->email_title_reply =  'Exponent Forum : New Reply Posted';
			
			$object->email_from_thread = 'Forum Manager <forum@'.HOSTNAME.'>';
			$object->email_from_reply  = 'Forum Manager <forum@'.HOSTNAME.'>';
			
			$object->email_address_thread = 'forum@'.HOSTNAME;
			$object->email_address_reply  = 'forum@'.HOSTNAME;
			
			$object->email_reply_thread = 'forum@'.HOSTNAME;
			$object->email_reply_reply  = 'forum@'.HOSTNAME;
			
			$object->email_showpost_thread = 0;
			$object->email_showpost_reply  = 0;
			
			$object->email_signature = "--\nThanks, Webmaster";
			$object->items_perpage = 20;
		} else {
			$form->meta('id',$object->id);
		}
		
		$form->register(null,'',new htmlcontrol('<b>"New Thread" Email</b><hr size="1" />'));
		$form->register('email_title_thread','Message Title',new textcontrol($object->email_title_thread,45));
		$form->register('email_from_thread','From (Display)',new textcontrol($object->email_from_thread,45));
		$form->register('email_address_thread','From (Email)',new textcontrol($object->email_address_thread,45));
		$form->register('email_reply_thread','Reply-to',new textcontrol($object->email_reply_thread,45));
		$form->register('email_showpost_thread','Show post in message?',new checkboxcontrol($object->email_showpost_thread,true));
		
		$form->register(null,'',new htmlcontrol('<br /><b>"New Reply" Email</b><hr size="1" />'));
		$form->register('email_title_reply','Message Title',new textcontrol($object->email_title_reply,45));
		$form->register('email_from_reply','From (Display)',new textcontrol($object->email_from_reply,45));
		$form->register('email_address_reply','From (Email)',new textcontrol($object->email_address_reply,45));
		$form->register('email_reply_reply','Reply-to',new textcontrol($object->email_reply_reply,45));
		$form->register('email_showpost_reply','Show post in message?',new checkboxcontrol($object->email_showpost_reply,true));
		
		$form->register(null,'',new htmlcontrol('<br /><b>General Settings (applies to both email message types)</b><hr size="1" />'));
		$form->register('email_signature','Email Signature',new texteditorcontrol($object->email_signature,5,30));
		$form->register('whos_online','Display Who\'s Online?',new checkboxcontrol($object->whos_online));
		
		$form->register('items_perpage','Items per page: ',new textcontrol($object->items_perpage,5));
		
		$form->register('submit','',new buttongroupcontrol('Save','','Cancel'));
		return $form;
	}
	
	function update($values,$object) {
		$object->email_title_thread = $values['email_title_thread'];
		$object->email_from_thread = $values['email_from_thread'];
		$object->email_address_thread = $values['email_address_thread'];
		$object->email_reply_thread = $values['email_reply_thread'];
		$object->email_showpost_thread = (isset($values['email_showpost_thread']) ? 1 : 0);
		
		$object->email_title_reply = $values['email_title_reply'];
		$object->email_from_reply = $values['email_from_reply'];
		$object->email_address_reply = $values['email_address_reply'];
		$object->email_reply_reply = $values['email_reply_reply'];
		$object->email_showpost_reply = (isset($values['email_showpost_reply']) ? 1 : 0);
		
		$object->email_signature = $values['email_signature'];
		$object->whos_online = $values['whos_online'];
		$object->items_perpage = $values['items_perpage'];
		return $object;
	}
}

?>
