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

if (!defined("PATHOS")) exit("");

// PERM CHECK

	if (!defined("SYS_FORMS")) include_once(BASE."subsystems/forms.php");
	pathos_forms_initialize();
	
	$form = new form();
	$form->meta("module","sharedcoremodule");
	$form->meta("action","deactivate_site");
	
	$dh = opendir(BASE."modules/sharedcoremodule/views");
	$tpls = array();
	while (($file = readdir($dh)) !== false) {
		if (substr($file,0,8) == "_reason_") $tpls[substr($file,0,-4)] = substr($file,8,-4);
	}
	uksort($tpls,"strnatcmp");
	
	$form->meta("site_id",$_GET['id']);
	$form->register("tpl","Template",new dropdowncontrol("",$tpls));
	$form->register("reason","Reason",new htmleditorcontrol());
	$form->register("submit","",new buttongroupcontrol("Deactivate","","Cancel"));
	
	echo $form->toHTML();

// END PERM CHECK

?>