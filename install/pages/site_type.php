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

if (!defined('PATHOS')) exit('');

//GREP:HARDCODEDTEXT
//GREP:PAGENOTUSED
?>
<div class="installer_title">
<img src="images/blocks.png" width="32" height="32" />
Select a Website Type
</div>
<br /><br />
Select a type of website.  Each site type (will eventually) come with a different default database, reflecting the content strucutre and layout for that type of site.
<br /><br />
<form method="post" action="">
<input type="hidden" name="page" value="create_site" />
<select name="site_type">
<?php

$dh = opendir("sitetypes");
$types = array();
while (($file = readdir($dh)) !== false) {
	if (is_file("sitetypes/$file") && substr($file,-4,4) == ".php") {
		$types[] = substr($file,0,-4);
	}
}
usort($types,"strnatcmp");

foreach ($types as $type) {
	echo '<option value="'.$type.'">'.$type.'</option>';
}

?>
</select><input type="submit" value="Create Site" />
</form>