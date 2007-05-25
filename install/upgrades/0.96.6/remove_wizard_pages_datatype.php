<?php
##################################################
#
# Copyright (c) 2007 OIC Group, Inc
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

///////////////////////////////////////////////////////////////////////////////////////
//	if the wizard pages file exists then we need to remove it
//	since it's been renamed to wizard_page.php.  If it doesn't get removed it 
//	cause an error when it tries to redeclare  the wizardpage class.
///////////////////////////////////////////////////////////////////////////////////////
$file = BASE."/datatypes/wizard_pages.php";
if (is_really_writable($file)) {
	unlink ($file);
} 
?>
