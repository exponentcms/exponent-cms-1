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

class inboxextension {
	function name() { return pathos_lang_loadKey('subsystems/users/profileextensions/inboxextension.php','extension_name'); }
	function author() { return 'James Hunt'; }
	function description() { return pathos_lang_loadKey('subsystems/users/profileextensions/inboxextension.php','extension_description'); }

	function modifyForm($form,$u) { // new if !isset($user->id)
		$i18n = pathos_lang_loadFile('subsystems/users/profileextensions/inboxextension.php');
		
		if (!isset($u->_inbox_config) || $u->_inbox_config == null) {
			$u->_inbox_config = inboxextension::_blank();
		}
		$form->register(null,'',new htmlcontrol('<hr size="1" /><b>'.$i18n['header'].'</b>'));
		$form->register('inbox_forward',$i18n['forward'], new checkboxcontrol($u->_inbox_config->forward,true));
		
		return $form;
	}
	
	function saveProfile($values,$user,$is_new) {
		global $db;
		$db->delete('inbox_userconfig','id='.$user->id);
		
		$inboxcfg = null;
		$inboxcfg->id = $user->id;
		$inboxcfg->forward = (isset($values['inbox_forward']) ? 1 : 0);
		
		$db->insertObject($inboxcfg,'inbox_userconfig');
		return $user;
	}
	
	function getProfile($user) {
		global $db;
		if (!isset($user->id)) {
			$user->_inbox_config = inboxextension::_blank();
		} else {
			$user->_inbox_config = $db->selectObject('inbox_userconfig','id='.$user->id);
		}
		return $user;
	}
	
	function cleanup($user) {
		global $db;
		$db->delete('inbox_userconfig','id='.$user->id);
	}
	
	function clear() {
		global $db;
		$db->delete('inbox_userconfig');
	}
	
	function hasData() {
		global $db;
		return ($db->countObjects('inbox_userconfig') != 0);
	}
	
	function _blank() {
		$cfg = null;
		$cfg->forward = 1;
		return $cfg;
	}
}

?>