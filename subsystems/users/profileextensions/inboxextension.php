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

class inboxextension {
	function name() { return "Private Message Center Extension"; }
	function author() { return "James Hunt"; }
	function description() { return "Allow users to configure email forwarding, and view their private messages from their profile."; }

	function modifyForm($form,$u) { // new if !isset($user->id)
	
		pathos_lang_loadDictionary('extras','inboxextension');
	
		if (!isset($u->_inbox_config) || $u->_inbox_config == null) {
			$u->_inbox_config = inboxextension::_blank();
		}
		$form->register(null,'',new htmlcontrol('<hr size="1" /><b>'.TR_X_INBOXEXTENSION_HEADER.'</b>'));
		$form->register("inbox_forward",TR_X_INBOXEXTENSION_FORWARD, new checkboxcontrol($u->_inbox_config->forward,true));
		
		return $form;
	}
	
	function saveProfile($values,$user,$is_new) {
		global $db;
		$db->delete("inbox_userconfig","id=".$user->id);
		
		$inboxcfg = null;
		$inboxcfg->id = $user->id;
		$inboxcfg->forward = (isset($values['inbox_forward']) ? 1 : 0);
		
		$db->insertObject($inboxcfg,"inbox_userconfig");
		return $user;
	}
	
	function getProfile($user) {
		global $db;
		if (!isset($user->id)) {
			$user->_inbox_config = inboxextension::_blank();
		} else {
			$user->_inbox_config = $db->selectObject("inbox_userconfig","id=".$user->id);
		}
		return $user;
	}
	
	function cleanup($user) {
		global $db;
		$db->delete("inbox_userconfig","id=".$user->id);
	}
	
	function clear() {
		global $db;
		$db->delete("inbox_userconfig");
	}
	
	function hasData() {
		global $db;
		return ($db->countObjects("inbox_userconfig") != 0);
	}
	
	function _blank() {
		$cfg = null;
		$cfg->forward = 1;
		return $cfg;
	}
}

?>