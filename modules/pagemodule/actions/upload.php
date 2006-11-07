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
# $Id: upload.php,v 1.4 2005/04/26 02:59:42 filetreefrog Exp $
##################################################

if (!defined("EXPONENT")) exit("");

if (exponent_permissions_check("configure",$loc)) {
	if (!defined("SYS_FORMS")) require_once(BASE."subsystems/forms.php");
	exponent_forms_initialize();
	
	$form = new form();
	$form->location($loc);
	$form->meta("action","save_upload");
	$form->register("file","File",new uploadcontrol());
	$form->register("submit","",new buttongroupcontrol("Upload","","Cancel"));
	
	echo $form->toHTML();
}

?>