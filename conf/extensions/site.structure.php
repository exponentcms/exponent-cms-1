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

return array(
	"General Site Configuration",
	array(
		"SITE_TITLE"=>array(
			"title"=>"Site Title",
			"description"=>"The title of the website.",
			"control"=>new textcontrol()
		),
		"SITE_ALLOW_REGISTRATION"=>array(
			"title"=>"Allow Registration?",
			"description"=>"Whether or not new users should be allowed to create accounts for themselves.",
			"control"=>new checkboxcontrol()
		),
		"SITE_KEYWORDS"=>array(
			"title"=>"Keywords",
			"description"=>"Search engine keywords for the site.",
			"control"=>new texteditorcontrol("",10,30)
		),
		"SITE_DESCRIPTION"=>array(
			"title"=>"Description",
			"description"=>"A description of what the site is about.",
			"control"=>new texteditorcontrol("",15,50)
		),
		"SITE_404_HTML"=>array(
			"title"=>"'Not Found' Error Text",
			"description"=>"HTML to show to a user when they try to request something that isn't found (like a deleted post, section etc.)",
			"control"=>new texteditorcontrol("",15,50)
		),
		"SITE_403_REAL_HTML"=>array(
			"title"=>"'Access Denied' Error Text",
			"description"=>"HTML to show to a user when they try to perform some action that their user account is not allowed to perform.",
			"control"=>new texteditorcontrol("",15,50)
		),
		"SITE_DEFAULT_SECTION"=>array(
			"title"=>"Default Section",
			"description"=>"The default section.",
			"control"=>new dropdowncontrol("",navigationmodule::levelDropDownControlArray(0))
		),
		"SESSION_TIMEOUT"=>array(
			"title"=>"Session Timeout",
			"description"=>"How long a user can be idle (in seconds) before they are automatically logged out.",
			"control"=>new textcontrol()
		),
		"SESSION_TIMEOUT_HTML"=>array(
			"title"=>"'Session Expired' Error Text",
			"description"=>"HTML to show to a user when their session expires and they are trying to perform some action that requires them to have certain permissions.",
			"control"=>new texteditorcontrol("",15,50)
		),
		"FILE_DEFAULT_MODE_STR"=>array(
			"title"=>"Default File Permissions",
			"description"=>"The readability / writability of uploaded files, for users other than the web server user.",
			"control"=>new dropdowncontrol(null,pathos_config_dropdownData("file_permissions"))
		),
		"DIR_DEFAULT_MODE_STR"=>array(
			"title"=>"Default Directory Permissions",
			"description"=>"The readability / writability of created directories, for users other than the web server user.",
			"control"=>new dropdowncontrol(null,pathos_config_dropdownData("dir_permissions"))
		),
		"ENABLE_SSL"=>array(
			"title"=>"Enable SSL Support",
			"description"=>"Whether or not to turn on Secure Linking through SSL",
			"control"=>new checkboxcontrol()
		),
		"NONSSL_URL"=>array(
			"title"=>"Non-SSL URL Base",
			"description"=>"Full URL of the website without SSL support (usually starting with 'http://')",
			"control"=>new textcontrol()
		),
		"SSL_URL"=>array(
			"title"=>"SSL URL Base",
			"description"=>"Full URL of the website with SSL support (usually starting with 'https://')",
			"control"=>new textcontrol()
		)
	)
);

?>