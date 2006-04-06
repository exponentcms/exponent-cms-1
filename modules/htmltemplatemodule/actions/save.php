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

$t = null;
$loc = exponent_core_makeLocation('htmltemplatemodule');
if (isset($_POST['id'])) {
	$t = $db->selectObject('htmltemplate','id='.intval($_POST['id']));
}

if ((!$t && exponent_permissions_check('create',$loc)) ||
	($t  && exponent_permissions_check('edit',$loc))
) {

	$t = htmltemplate::update($_POST,$t);
	if (isset($t->id)) {
		$db->updateObject($t,'htmltemplate');
	} else {
		$db->insertObject($t,'htmltemplate');
	}
	exponent_flow_redirect();
} else {
	echo SITE_403_HTML;
}

?>