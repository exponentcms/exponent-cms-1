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

echo "Installing a " . $_POST['site_type'] ."<br/><br />";

if (!defined("SYS_BACKUP")) include_once(BASE."subsystems/backup.php");

$eql = BASE."install/sitetypes/db/".$_POST['site_type'].".eql";
$errors = array();
pathos_backup_restoreDatabase($db,$eql,$errors,0);

if (count($errors)) {
	echo "Errors were encountered.<br /><br />";
	foreach ($errors as $e) echo $e . "<br />";
} else {
	echo "Site database built successfully!";
	echo "<br />Click <a href='?page=final'>here</a> to complete the installation.";
}
//GREP:HARDCODEDTEXT
?>