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

class inboxmodule {
	function name() { return "Private Message Center"; }
	function description() { return "Allows user to send and receive private messages, to and from other users on the site."; }
	function author() { return "James Hunt"; }
	
	function hasSources() { return false; }
	function hasContent() { return false; }
	function hasViews() { return true; }
	
	function supportsWorkflow() { return false; }
	
	function permissions($internal = "") {
		if ($internal == "") {
			return array(
				"administrate"=>"Administrate",
				"configure"=>"Configure",
			);
		} else {
			return array(
				"administrate"=>"Administrate",
				"configure"=>"Configure",
			);
		}
	}
	
	function show($view,$loc,$title) {
		global $db, $user;
		if ($user) {
			$template = new template("inboxmodule",$view,$loc);
			
			$read = $db->countObjects("privatemessage","recipient=".$user->id." AND unread=0");
			$unread = $db->countObjects("privatemessage","recipient=".$user->id." AND unread=1");
			
			$template->assign("readMessages",$read);
			$template->assign("unreadMessages",$unread);
			$template->assign("totalMessages",$unread+$read);
			$template->assign("user",$user);
			$template->assign("moduletitle",$title);
			
			$template->output();
		}
	}
}

?>