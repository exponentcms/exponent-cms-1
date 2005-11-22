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

define('SCRIPT_EXP_RELATIVE','');
define('SCRIPT_FILENAME','mod_preview.php');

// Initialize the Pathos Framework
include_once('pathos.php');

$i18n = pathos_lang_loadFile('mod_preview.php');

$SYS_FLOW_REDIRECTIONPATH='previewreadonly';

if (is_readable(BASE.'themes/' . DISPLAY_THEME . '/module_preview.php')) {
	// Include the Theme's module_preview.php file if it exists.  Otherwise, we will include the default file later.
	include_once('themes/' . DISPLAY_THEME .'/module_preview.php');
} else if (is_readable(BASE.'module_preview.php')) {
	// Include the default module_preview.php, because we didn't find one in the theme.
	include_once(BASE.'module_preview.php');
} else {
	echo $i18n['no_preview'];
}

?>