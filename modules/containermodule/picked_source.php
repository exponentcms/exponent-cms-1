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

include_once("../../pathos.php");
define("SCRIPT_RELATIVE",PATH_RELATIVE."modules/containermodule/");
define("SCRIPT_ABSOLUTE",BASE."modules/containermodule/");
define("SCRIPT_FILENAME","picked_source.php");

$src = $_GET['ss'];
$mod = $_GET['sm'];

$locref = $db->selectObject("locationref","module='".$mod."' AND source='".$src."'");
if (!isset($locref->description)) $locref->description = "... no description ...";

?>
<html>
<head>
<script type="text/javascript">
function saveSource() {
	window.opener.sourcePicked("<?php echo $_GET['ss']; ?>","<?php echo str_replace(array("\"","\r\n"),array("\\\"","\\r\\n"),$locref->description); ?>");
	window.close();
	
}
</script>
</head>
<body onload="saveSource()">
</body>
</html>