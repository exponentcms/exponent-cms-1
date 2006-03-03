<?php

##################################################
#
# Copyright (c) 2004-2005 OIC Group, Inc.
# Written and Designed by James Hunt
#
# This file is part of Exponent
#
# Exponent is free software; you can redistribute
# it and/or modify it under the terms of the GNU
# General Public License as published by the Free
# Software Foundation; either version 2 of the
# License, or (at your option) any later version.
#
# GPL: http://www.gnu.org/licenses/gpl.txt
#
##################################################

class inboxmodule {
	function name() { return exponent_lang_loadKey('modules/inboxmodule/class.php','module_name'); }
	function description() { return exponent_lang_loadKey('modules/inboxmodule/class.php','module_description'); }
	function author() { return 'James Hunt'; }
	
	function hasSources() { return false; }
	function hasContent() { return false; }
	function hasViews() { return true; }
	
	function supportsWorkflow() { return false; }
	
	function permissions($internal = '') {
		$i18n = exponent_lang_loadFile('modules/inboxmodule/class.php');
		return array(
			'administrate'=>$i18n['perm_administrate'],
			'contact_all'=>$i18n['perm_contact_all']
		);
	}
	
	function deleteIn($loc) {
		// Nothing to delete, since there are no sources, and doing
		// so would be quite dangerous
	}
	
	function copyContent($from_loc,$to_loc) {
		// No content to copy, since there are not sources
	}
	
	function show($view,$loc,$title) {
		global $db, $user;
		if ($user) {
			$template = new template('inboxmodule',$view,$loc);
			
			$read = $db->countObjects('privatemessage','recipient='.$user->id.' AND unread=0');
			$unread = $db->countObjects('privatemessage','recipient='.$user->id.' AND unread=1');
			
			$template->assign('readMessages',$read);
			$template->assign('unreadMessages',$unread);
			$template->assign('totalMessages',$unread+$read);
			$template->assign('user',$user);
			$template->assign('moduletitle',$title);
			$template->register_permissions('administrate',$loc);
			$template->output();
		}
	}
	
	function spiderContent($item = null) {
		// Do nothing, no content
		return false;
	}
	
}

?>