<?php

##################################################
#
# Copyright (c) 2004-2005 OIC Group, Inc.
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
# $Id: manage_slides.php,v 1.4 2005/02/24 19:53:17 filetreefrog Exp $
##################################################

if (!defined("EXPONENT")) exit("");

if (exponent_permissions_check('administrate',$loc) || exponent_permissions_check('configure',$loc) || exponent_permissions_check('create',$loc) || exponent_permissions_check('edit',$loc) || exponent_permissions_check('delete',$loc)) {
	exponent_flow_set(SYS_FLOW_PROTECTED,SYS_FLOW_ACTION);
	slideshowmodule::show("Manager",$loc,"Manage Slides");
} else {
	echo SITE_403_HTML;
}

?>