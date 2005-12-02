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

if (!defined('PATHOS')) exit('');

echo "Installing a " . $_POST['site_type'] ."<br/><br />";

if (!defined("SYS_BACKUP")) require_once(BASE."subsystems/backup.php");

$eql = BASE."install/sitetypes/db/".$_POST['site_type'].".eql";
$errors = array();
pathos_backup_restoreDatabase($db,$eql,$errors,0);

if (count($errors)) {
	echo "Errors were encountered.<br /><br />";
	foreach ($errors as $e) echo $e . "<br />";
} else {
	echo "Site database built successfully!";
	echo "<br />Click <a href='index.php?page=final'>here</a> to complete the installation.";
}
//GREP:HARDCODEDTEXT
?>