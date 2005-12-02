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
//GREP:HARDCODEDTEXT

if (!defined('PATHOS')) exit('');

if (!defined("SYS_BACKUP")) require_once(BASE."subsystems/backup.php");

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