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

?>
<script type="text/javascript">
<!--

var ids = new Array();
var tpls = new Array();
var base_url = document.location.href;

function buildHelp(dbname,username,password,target) {
	for (var i = 0; i < ids.length; i++) {
		var elem = document.getElementById(ids[i]);
		elem.removeChild(elem.firstChild);
		
		str = tpls[i];
		str = str.replace("__DBNAME__",dbname);
		str = str.replace("__USERNAME__",username);
		str = str.replace("__PASSWORD__",password);
		
		elem.appendChild(document.createTextNode(str));
	}
	
	document.location.href = base_url + "#" + target;
}

//-->
</script>
<div class="installer_title">
<img src="images/blocks.png" width="16" height="16" />
Creating a New Database
</div>
<br /><br />
Exponent supports both the MySQL database server and the PostGreSQL database server as backends.
<br /><br />
<div align="center">
|&nbsp;<a href="#mysql">MySQL</a>&nbsp;|&nbsp;<a href="#pgsql">PostGreSQL</a>&nbsp;|
</div>
<br />

<a name="form"></a>
<br />
<div class="important_box">
Fill out the form below and click 'Go' to generate SQL statements for each supported database server.
<br />
<form>
<table>
<tr>
	<td>Database:&nbsp;</td>
	<td><input class="text" type="text" name="dbname" value="<dbname>" /></td>
</tr><tr>
	<td>Username:&nbsp;</td>
	<td><input class="text" type="text" name="username" value="<username>" /></td>
</tr><tr>
	<td>Password:&nbsp;</td>
	<td><input class="text" type="text" name="password" value="<password>" /></td>
</tr><tr>
	<td></td>
	<td><input class="text" type="button" value="For MySQL..." onClick="buildHelp(this.form.dbname.value,this.form.username.value,this.form.password.value,'mysql'); return false" /></td>
</tr>
</form>
</table>
<br />
</div>
<br /><br />
<a name="mysql"></a>
<hr size="1" />
<img src="images/mysql.png" /><br />
<b>MySQL Database Creation</b>
<br /><br />
If you have access to the database server, and have sufficient privileges to create databases, you can use the following SQL statements to setup the database for Exponent.  Note that you will have to fill in the <a href="#form">form</a> above before using these.
<br /><br />
<b>Create the Database</b><br />
<textarea id="mysql_create" rows="1" style="width: 100%">(fill in the above form and click 'go' to generate SQL)</textarea>
<b>Grant Database Rights</b><br />
<textarea id="mysql_perms" rows="3" style="width: 100%">(fill in the above form and click 'go' to generate SQL)</textarea>
<script type="text/javascript">
ids.push("mysql_create");
tpls.push("CREATE DATABASE __DBNAME__;");

ids.push("mysql_perms");
tpls.push("GRANT SELECT, INSERT, UPDATE, DELETE, CREATE, ALTER, INDEX, DROP to __USERNAME__ identified by '__PASSWORD__';");
</script>

<br /><br />
<a name="pgsql"></a>
<hr size="1" />
<img src="images/pgsql.gif" /><br />
<b>PostGreSQL Database Creation</b>
<br /><br />
Because PostGreSQL does not maintain its own set of users like MySQL (and instead relies on system users) you will have to refer to the <a href="http://www.postgresql.org/">online documentation</a> for information on creating new databases and assigning user permissions.
<br /><br />
Note that the Exponent support for PostGreSQL is still <b>experimental</b>.  It is not recommended to use PostGreSQL in a production environment.
<br />