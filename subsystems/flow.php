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

/* exdoc
 * The definition of this constant lets other parts
 * of the system know that the Flow Subsystem
 * has been included for use.
 * @node Subsystems:Flow
 */
define('SYS_FLOW',1);

/* exdoc
 * Flow Type Specifier : None
 * @node Subsystems:Flow
 */
define('SYS_FLOW_NONE',	 0);

/* exdoc
 * Flow Type Specifier : Public Access
 * @node Subsystems:Flow
 */
define('SYS_FLOW_PUBLIC',	 1);

/* exdoc
 * Flow Type Specifier : Protected Access
 * @node Subsystems:Flow
 */
define('SYS_FLOW_PROTECTED', 2);

/* exdoc
 * Flow Type Specifier : Sectional Page
 * @node Subsystems:Flow
 */
define('SYS_FLOW_SECTIONAL', 1);

/* exdoc
 * Flow Type Specifier : Action Page
 * @node Subsystems:Flow
 */
define('SYS_FLOW_ACTION',	 2);



$SYS_FLOW_REDIRECTIONPATH = 'pathos_default';

/* exdoc
 * Saves the current URL in a persistent session, to be used later.
 *
 * @param integer $access_level The access level of the current page.
 *  Either SYS_FLOW_PUBLIC or SYS_FLOW_PROTECTED
 * @param integer $url_type The type of URSL being set.  Either
 *  SYS_FLOW_SECTIONAL or SYS_FLOW_ACTION
 * @node Subsystems:Flow
 */
function pathos_flow_set($access_level,$url_type) {
	global $SYS_FLOW_REDIRECTIONPATH;
	if ($access_level == SYS_FLOW_PUBLIC) {
		pathos_sessions_set($SYS_FLOW_REDIRECTIONPATH.'_flow_' . SYS_FLOW_PROTECTED . '_' . $url_type,'http://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI']);
		pathos_sessions_set($SYS_FLOW_REDIRECTIONPATH.'_flow_last_' . SYS_FLOW_PROTECTED,'http://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI']);
	}
	pathos_sessions_set($SYS_FLOW_REDIRECTIONPATH.'_flow_' . $access_level . '_' . $url_type,'http://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI']);
	pathos_sessions_set($SYS_FLOW_REDIRECTIONPATH.'_flow_last_' . $access_level,'http://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI']);
}

/* exdoc
 * Looks through persistent session data and returns the last URL set
 * for a specific type.  If the type is set to SYS_FLOW_NONE, then either
 * SYS_FLOW_ACTION or SYS_FLOW_SECTIONAL will be retrieved.
 *
 * @param integer $url_type The type of URL to retrieve, Either
 *   SYS_FLOW_SECTIONAL or SYS_FLOW_ACTION
 * @node Subsystems:Flow
 */
function pathos_flow_get($url_type = SYS_FLOW_NONE) {
	global $SYS_FLOW_REDIRECTIONPATH;
	$access_level = (pathos_sessions_loggedIn() ? SYS_FLOW_PROTECTED : SYS_FLOW_PUBLIC);
	if (!pathos_sessions_isset($SYS_FLOW_REDIRECTIONPATH.'_flow_last_'.$access_level)) return 'http://'.$_SERVER['HTTP_HOST'].PATH_RELATIVE;
	switch ($url_type) {
		case SYS_FLOW_NONE:
			return pathos_sessions_get($SYS_FLOW_REDIRECTIONPATH.'_flow_last_' . $access_level);
		case SYS_FLOW_SECTIONAL:
		case SYS_FLOW_ACTION:
			return pathos_sessions_get($SYS_FLOW_REDIRECTIONPATH.'_flow_' . $access_level . '_' . $url_type);
	}
}

/* exdoc
 * Looks at the persistent session data to figure out what the last 'valid' URL visited
 * was, and then redirects.  If the optional $url_type parameter is specified as anything
 * other than SYS_FLOW_NONE, then only that type of URL will be used for the redirection.
 *
 * @param integer $url_type The type of URL to retrieve, Either
 *   SYS_FLOW_SECTIONAL or SYS_FLOW_ACTION
 * @node Subsystems:Flow
 */
function pathos_flow_redirect($url_type = SYS_FLOW_NONE) {
	global $SYS_FLOW_REDIRECTIONPATH;
	$access_level = (pathos_sessions_loggedIn() ? SYS_FLOW_PROTECTED : SYS_FLOW_PUBLIC);
	// Fallback to the default redirection path in strange edge cases.
	if (!pathos_sessions_isset($SYS_FLOW_REDIRECTIONPATH.'_flow_last_'.$access_level)) $SYS_FLOW_REDIRECTIONPATH='pathos_default';
	$url = '';
	switch ($url_type) {
		case SYS_FLOW_NONE:
			$url = pathos_sessions_get($SYS_FLOW_REDIRECTIONPATH . '_flow_last_' . $access_level);
			break;
		case SYS_FLOW_SECTIONAL:
		case SYS_FLOW_ACTION:
			$url = pathos_sessions_get($SYS_FLOW_REDIRECTIONPATH . '_flow_' . $access_level . '_' . $url_type);
			break;
	}
	if (DEVELOPMENT >= 2) {
		echo '<a href="'.$url.'">'.$url.'</a>';
	} else {
		header("Location: $url");
	}
	exit();
}

?>