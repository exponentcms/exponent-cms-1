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
 
if (!defined('PATHOS')) exit('');
ob_start();

if (isset($_GET['redirecturl'])) {
	$redirect = urldecode($_GET['redirecturl']);
	if (substr($redirect,0,4) != 'http') {
		$redirect = URL_FULL.$redirect;
	}
	pathos_sessions_set('redirecturl',$redirect);
}

//$SYS_FLOW_REDIRECTIONPATH = 'loginredirect'; 
pathos_flow_set(SYS_FLOW_PUBLIC,SYS_FLOW_ACTION);

if (pathos_sessions_loggedIn()) {
	header('Location: ' . pathos_sessions_get('redirecturl'));
	exit;
} 

loginmodule::show('Default',null,'Log In');


$template = new template('loginmodule','_login_redirect');

$template->assign('output',ob_get_contents());
ob_end_clean();
$template->output();

?>