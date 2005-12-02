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

if (!defined("PATHOS")) exit("");

// PERM CHECK
	$assoc = null;
	$assoc->template_id = $_POST['template_id'];
	if (isset($_POST['mod'])) $assoc->module = $_POST['mod'];
	else $assoc->is_global = 1;
	
	$db->insertObject($assoc,"htmltemplateassociation");
	pathos_flow_redirect();
// END PERM CHECK

?>