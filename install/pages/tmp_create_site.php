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
//GREP:HARDCODEDTEXT

if (!defined('PATHOS')) exit('');

if (!defined("SYS_BACKUP")) include_once(BASE."subsystems/backup.php");

$eql = BASE."install/sitetypes/db/_default.eql";
$errors = array();
pathos_backup_restoreDatabase($db,$eql,$errors,0);

?>
<div class="installer_title">
<img src="images/blocks.png" width="32" height="32" />
Populating Database (Default Content)
</div>
<br /><br />
<?php

if (count($errors)) {
	echo "Errors were encountered populating the site database.<br /><br />";
	foreach ($errors as $e) echo $e . "<br />";
} else {
	echo "Default content has been inserted into your database.  This content structure should help you to learn how Exponent works, and how to use it for your website.";
	echo "<br /><br />Click <a href='?page=final'>here</a> to complete the installation.";
}

?>