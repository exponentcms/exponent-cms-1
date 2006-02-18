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
	$db->delete("htmltemplateassociation","template_id=".intval($_GET['tid'])." AND global = 0 AND module='".$_GET['mod']."'");
	pathos_flow_redirect();
// END PERM CHECK

?>