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

class database_importer {
	function form() {
		
		pathos_lang_loadDictionary('modules','database');
	
		if (!defined('SYS_FORMS')) include_once(BASE.'subsystems/forms.php');
		pathos_forms_initialize();

		$form = new form();
		//Form is created to collect information from the user
		//Values set previously (defaults or user-entered) are displayed
		$form->register('dbengine',TR_DATABASE_DBTYPE,new dropdowncontrol('',pathos_database_backends()));
		$form->register('host',TR_DATABASE_HOST,new textcontrol(DB_HOST));
		$form->register('port',TR_DATABASE_PORT,new textcontrol(DB_PORT));
		$form->register('dbname',TR_DATABASE_DBNAME,new textcontrol(''));
		$form->register('username',TR_DATABASE_USER,new textcontrol(DB_USER));
		$form->register('pwd',TR_DATABASE_PWD,new passwordcontrol(''));
		
		pathos_forms_cleanup();
		return $form;
	}
}
?>
