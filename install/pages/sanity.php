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
include_once("include/sanity.php");

$warnings = array();
$errors = array();
// Run sanity checks

sanity_checkConfigFile();
sanity_checkModules();
sanity_checkThemes();
sanity_checkSite();


?>
<div class="installer_title">
<img src="images/blocks.png" width="32" height="32" />
Sanity Checks
</div>
<br /><br />
Exponent requires that several permissions be set correctly in order to operate.  Sanity checks are being run right now to ensure that the web server directory you wish to install Exponent in is suitable.
<br /><br />
<?php

if (count($errors)) {
	?>
	<b>The following errors were encountered:</b>
	<br />
	<?php
	foreach ($errors as $err) {
		echo "<div class='error'>$err</div>";
	}	
	echo '<br /><br />';
}

if (count($warnings)) {
	?>
	<b>The following warnings were encountered:</b>
	<br />
	<?php
	foreach ($warnings as $err) {
		echo "<div class='warning'>$err</div>";
	}
}

$write_file = 0;

if (count($errors) > 0) {
	// Had errors.  Force halt and fix.
	?>
	<br /><b>Note:</b> For permission errors (files or directories that are not writable / not readable) it is usually best to make sure that the Exponent files were uncompressed with options (-xzvpf) to preserve file permissions.
	<br /><br />
	After you have corrected the above errors, click <a href="?page=sanity">here</a> to run these environment checks again.
	<?php
} else if (count($warnings) == 0) {
	// No errors, and no warnings.  Let them through.
	?>
	The Exponent Install Wizard found no problems with the server environment.
	<br />Please proceed to configure your database by clicking <a href="?page=dbconfig">here</a>.
	<?php
	$write_file = 1;
} else {
	// No errors, but had warnings.  Let them through, but with a warning
	?>
	The Exponent Install Wizard found no minor problems with the server environment, but you can continue.
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