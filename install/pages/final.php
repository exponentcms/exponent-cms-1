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

pathos_sessions_unset("installer_config");

?>
<div class="installer_title">
<img src="images/blocks.png" width="32" height="32" />
Installation Compete - Congratulations!
</div>
<?php

unlink(BASE.'install/not_configured');
if (!file_exists(BASE.'install/configured')) {
	echo '<br /><br />';
	echo 'Unable to create the file install/configured.  You will have to manually create this file.';
}

?>
<br /><br />
Configuration and setup for your new website is complete.
<br /><br />
You can login to your new website using the following administrator account:
<table cellpadding="0" cellspacing="2" border="0">
<tr><td><b>Username:</b></td><td>admin</td></tr>
<tr><td><b>Password:</b></td><td>admin</td></tr>
</table>
<br />
You can visit your new website <a href="http://<?php echo $_SERVER['HTTP_HOST'].PATH_RELATIVE?>index.php" target="_blank">here</a>.
<br /><br />