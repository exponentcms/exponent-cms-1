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

?>
<div class="installer_title">
<img src="images/blocks.png" width="32" height="32" />
Database Configuration
</div>
<br /><br />
In order to best manage your content, Exponent stores it in a relational database.  Configuring your database connection properly is the most important step in setting up your new website.
<br /><br />
Due to security reasons, you or your web server administrator will have to create an empty database.  For information on how to create the database, click <a href="" onClick="pop('db_create');">here</a>.  Optionally, you may choose to use a pre-existing database.  For information on using an existing database and what it entails, click <a href="" onClick="return pop('db_existing');">here</a>.
<br /><br />

<form method="post" action="">
<input type="hidden" name="page" value="dbcheck" />

<div class="form_section_header">Server Information</div>
<div class="form_section">
	<div class="control">
		&#0149; <span class="control_caption">Backend: </span>
		<select name="c[db_engine]" value="mysql">
		<?php
		$dh = opendir(BASE."subsystems/database");
		while (($file = readdir($dh)) !== false) {
			if (is_file(BASE."subsystems/database/$file") && is_readable(BASE."subsystems/database/$file") && substr($file,-9,9) == ".info.php") {
				$info = include(BASE."subsystems/database/$file");
				echo '<option value="'.substr($file,0,-9).'">'.$info['name'].'</option>';
			}
		}
		?>
		</select>
		<div class="control_help">
			Select which database server software package your web server is running.  If the software is not listed, it is not supported by Exponent.
			<br /><br />
			If in doubt, contact your system administrator or hosting provider.
		</div>
	</div>
	
	<div class="control">
		&#0149; <span class="control_caption">Address: </span>
		<input class="text" type="text" name="c[db_host]" value="localhost" />
		<div class="control_help">
			If your database server software runs on a different physical machine than the web server, enter the address of the database server machine.  Either an IP address (like 1.2.3.4) or an internet domain name (such as example.com) will work.
			<br /><br />
			If your database server software runs on the same machine as the web server, use the default setting, 'localhost'.
			<br /><br />
			If in doubt, contact your system administrator or hosting provider.
		</div>
	</div>
	
	<div class="control">
		&#0149; <span class="control_caption">Port: </span>
		<input class="text" type="text" name="c[db_port]" value="3306" size="5" />
		<div class="control_help">
			If you are using a database server that supports TCP or other network connection protocols, and that database software runs on a different physical machine than the web server, enter the connection port.
			<br /><br />
			If you entered 'localhost' in the Address field, you should leave this as the default setting.
			<br /><br />
			If in doubt, contact your system administrator or hosting provider.
		</div>
	</div>
</div>

<div class="form_section_header">Database Information</div>
<div class="form_section">
	<div class="control">
		&#0149; <span class="control_caption">Database Name: </span>
		<input class="text" type="text" name="c[db_name]" value="" />
		<div class="control_help">
			This is the real name of the database, according to the database server.  Consult your system administrator or hosting provider if you are unsure and did not set the database up yourself.
		</div>
	</div>
	
	<div class="control">
		&#0149; <span class="control_caption">Username: </span>
		<input class="text" type="text" name="c[db_user]" value="" />
		<div class="control_help">
			All database server software supported by Exponent require some sort of authentication.  Enter the name of the user account to use for logging into the database server.
			<br /><br />
			For information on what database user privileges are required, click <a href="" onClick="return pop('db_priv');">here</a>.
		</div>
	</div>
	<div class="control">
		&#0149; <span class="control_caption">Password: </span>
		<input class="text" type="text" name="c[db_pass]" value="" />
		<div class="control_help">
			Enter the password for the username you entered above.  The password will <b>not</b> be obscured, because it cannot be obscured in the configuration file.  The Exponent developers urge you to use a completely new password, unlike any of your others, for security reasons.
		</div>
	</div>
	
	<div class="control">
		&#0149; <span class="control_caption">Table Prefix: </span>
		<input class="text" type="text" name="c[db_table_prefix]" value="exponent" />
		<div class="control_help">
			A table prefix helps Exponent differentiate tables for this site from other tables that may already exist (or eventually be created by other scripts).  If you are using an existing database, you may want to change this. 
			<br /><br />
			<b>Note:</b> A table prefix can only contains numbers and letters.  Spaces and symbols (including '_') are not allowed.  An underscore will be added for you, by Exponent.
		</div>
	</div>
</div>

<div class="form_section_header">Verify Configuration</div>
<div class="form_section">
	<div class="control">
		<div class="control_help">
		After you are satisfied that the information you have entered is correct, click the 'Submit' button, below.  The Exponent Install Wizard will then perform some preliminary tests to ensure that the configuration is valid.
		<br /><br />
		</div>
		<input type="submit" value="Submit" class="text" />
	</div>
</div>