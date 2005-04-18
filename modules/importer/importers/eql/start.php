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

if (!defined("PATHOS")) exit("");

if (!defined("SYS_FORMS")) require_once(BASE."subsystems/forms.php");
pathos_forms_initialize();

//Get the I18N constants
pathos_lang_loadDictionary('importers', 'eql');

$form = new form();
$form->meta("module","importer");
$form->meta("action","page");
$form->meta("importer","eql");
$form->meta("page","process");

$form->register("file",TR_IMPORTERMODULE_EQL_EQLFILE,new uploadcontrol());
$form->register("submit","",new buttongroupcontrol(TR_IMPORTERMODULE_EQL_RESTOREBUTTON,"",""));

$template = new template("importer","_eql_restoreForm",$loc);
$template->assign("form_html",$form->toHTML());
$template->output();

?>