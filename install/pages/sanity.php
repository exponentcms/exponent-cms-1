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

include_once("include/sanity.php");

$status = sanity_checkFiles();
// Run sanity checks
$errcount = count($status);
$warncount = 0; // No warnings with permissions
?>
<div class="installer_title">
<img src="images/blocks.png" width="32" height="32" />
Sanity Checks
</div>
<br /><br />
Exponent requires that several permissions be set correctly in order to operate.  Sanity checks are being run right now to ensure that the web server directory you wish to install Exponent in is suitable.
<br /><br />
<table cellspacing="0" cellpadding="3" rules="all" border="0" style="border:1px solid grey;" width="100%">
<tr><td colspan="2" style="background-color: lightgrey;"><b>File and Directory Permission Tests</b></td></tr>
<?php
foreach ($status as $file=>$stat) {
	echo '<tr><td width="55%"><span style="color: #AAA;">'.BASE.'</span>'.$file.'</td><td align="center" width="45%"';
	if ($stat != SANITY_FINE) echo ' style="color: #C00; font-weight: bold; background-color: #999;">';
	else echo ' style="color: green; font-weight: bold;">';
	switch ($stat) {
		case SANITY_NOT_E:
			echo 'File Not Found';
			break;
		case SANITY_NOT_R:
			echo 'Not Readable';
			break;
		case SANITY_NOT_RW:
			echo 'Not Readable / Writable';
			break;
		case SANITY_FINE:
			$errcount--;
			echo 'Okay';
			break;
		default:
			echo '????';
			break;
	}
	echo '</td></tr>';
}
?>
<tr><td colspan="2" style="background-color: lightgrey;"><b>Other Tests</b></td></tr>
<?php

$status = sanity_checkServer();
$errcount += count($status);
$warncount += count($status);
foreach ($status as $test=>$stat) {
	echo '<tr><td width="55%">'.$test.'</td>';
	echo '<td align="center" width="45%" ';
	if ($stat[0] == SANITY_FINE) {
		$warncount--;
		$errcount--;
		echo 'style="color: green; font-weight: bold;">';
	} else if ($stat[0] == SANITY_ERROR) {
		$warncount--;
		echo 'style="color: red; font-weight: bold; background-color: #999;">';
	} else {
		$errcount--;
		echo 'style="color: yellow; font-weight: bold; background-color: #999;">';
	}
	echo $stat[1].'</td></tr>';
}

$status = sanity_checkModules();
if (count($status)) {
	?>
	<tr><td colspan="2" style="background-color: lightgrey;"><b>Module Tests</b></td></tr>
	<?php
	$errcount += count($status);
	foreach ($status as $mod=>$stat) {
		echo '<tr><td width="55%">'.$mod.'</td>';
		echo '<td align="center" width="45%" ';
		if ($stat[0] == SANITY_FINE) {
			$errcount--;
			echo 'style="color: green; font-weight: bold;">';
		} else {
			echo 'style="color: red; font-weight: bold; background-color: #999;">';
		}
		echo $stat[1].'</td></tr>';
	}
}

?>
</table>
<br />
<?php

$write_file = 0;

if ($errcount > 0) {
	// Had errors.  Force halt and fix.
	?>
	The Exponent Install Wizard found some major problems with the server environment, which you must fix before you can continue.
	<?php
	if (ini_get('safe_mode') == true) {
		echo '<br /><br /><div style="font-weight: bold; color: red;">SAFE MODE IS ENABLED.  You may encounter many strange errors unless you give the web server user ownership of ALL Exponent files.  On UNIX, this can be done with a "chown -R" command</div>';
	}
	?>
	<br /><b>Note:</b> For permission errors (files or directories that are not writable / not readable) it is usually best to make sure that the Exponent files were uncompressed with options (-xzvpf) to preserve file permissions.
	<br /><br />
	After you have corrected the above errors, click <a href="?page=sanity">here</a> to run these environment checks again.
	<?php
} else if ($warncount > 0) {
	?>
	The Exponent Install Wizard found some minor problems with the server environment, but you should be able to continue.
	<?php
	if (ini_get('safe_mode') == true) {
		echo '<br /><br /><div style="font-weight: bold; color: red;">SAFE MODE IS ENABLED.  You may encounter many strange errors unless you give the web server user ownership of ALL Exponent files.  On UNIX, this can be done with a "chown -R" command</div>';
	}
	?>
	<br />Please proceed to configure your database by clicking <a href="?page=dbconfig">here</a>.
	<?php
} else {
	// No errors, and no warnings.  Let them through.
	?>
	The Exponent Install Wizard found no problems with the server environment.
	<br />Please proceed to configure your database by clicking <a href="?page=dbconfig">here</a>.
	<?php
	$write_file = 1;
}

if ($write_file) {
	$components = join('/',array_splice(split('/',$_SERVER['SCRIPT_NAME']),0,-2)).'/';
	$path_relative = PATH_RELATIVE;
	
	if ($components != $path_relative) {
		$path_relative = $components;
		$fh = fopen(BASE.'overrides.php','w');
		fwrite($fh,"<?php\r\n\r\ndefine('PATH_RELATIVE','$path_relative');\r\n\r\n?>\r\n");
		fclose($fh);
	}
}

?>