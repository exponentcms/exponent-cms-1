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

class inboxextension {
	function name() { return "Private Message Center Extension"; }
	function author() { return "James Hunt"; }
	function description() { return "Allow users to configure email forwarding, and view their private messages from their profile."; }

	function modifyForm($form,$user) { // new if !isset($user->id)
	
		pathos_lang_loadDictionary('extras','inboxextension');
	
		if (!isset($user->_inbox_config)) {
			$user->_inbox_config = null;
			$user->_inbox_config->forward = 1;
		}
		$form->register(null,'',new htmlcontrol('<hr size="1" /><b>'.TR_X_INBOXEXTENSION_HEADER.'</b>'));
		$form->register("inbox_forward",TR_X_INBOXEXTENSION_FORWARD, new checkboxcontrol($user->_inbox_config->forward,true));
		
		return $form;
	}
	
	function saveProfile($values,$user,$is_new) {
		global $db;
		$db->delete("inbox_userconfig","id=".$user->id);
		
		$inboxcfg = null;
		$inboxcfg->id = $user->id;
		$inboxcfg->forward = isset($values['inbox_forward']);
		
		$db->insertObject($inboxcfg,"inbox_userconfig");
		return $user;
	}
	
	function getProfile($user) {
		global $db;
		$user->_inbox_config = $db->selectObject("inbox_userconfig","id=".$user->id);
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
}

?>