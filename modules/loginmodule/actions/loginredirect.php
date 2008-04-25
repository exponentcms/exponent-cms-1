<?php

##################################################
#
# Copyright (c) 2004-2006 OIC Group, Inc.
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

if (!defined('EXPONENT')) exit('');
ob_start();

if (isset($_GET['redirecturl'])) {
	$redirect = urldecode($_GET['redirecturl']);
	if (substr($redirect,0,4) != 'http') {
		$redirect = URL_FULL.$redirect;
	}
	exponent_sessions_set('redirecturl',$redirect);
} else {
	exponent_sessions_set('redirecturl',exponent_flow_get());
}

//$SYS_FLOW_REDIRECTIONPATH = 'loginredirect'; 
//exponent_flow_set(SYS_FLOW_PUBLIC,SYS_FLOW_ACTION);

if (exponent_sessions_loggedIn()) {
	header('Location: ' . exponent_sessions_get('redirecturl'));
	exit('Redirecting...');
} 

$i18n = exponent_lang_loadFile('modules/loginmodule/actions/loginredirect.php');
loginmodule::show('Default',null,$i18n['login']);


$template = new template('loginmodule','_login_redirect');

$template->assign('output',ob_get_contents());
ob_end_clean();
$template->output();

?>
