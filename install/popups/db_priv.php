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
<div class="installer_title">
<img src="images/blocks.png" width="16" height="16" />
Database User Privileges
</div>
<br /><br />
When Exponent connects to the database, it needs to be able to run the following types of queries:
<br /><br />

<tt>CREATE TABLE</tt><br />
&nbsp;&nbsp;-&nbsp;These queries create new table structures inside the database.  Exponent needs this when you install it for the first time.  CREATE TABLE queries are also run after new modules are uploaded to the site.
<br /><br />

<tt>ALTER TABLE</tt><br />
&nbsp;&nbsp;-&nbsp;If you upgrade any module in Exponent, these queries will be run to change table structures in the database.
<br /><br />

<tt>DROP TABLE</tt><br />
&nbsp;&nbsp;-&nbsp;These queries are executed on the database whenever an administrator trims it to remove tables that are no longer used.
<br /><br />

<tt>SELECT</tt><br />
&nbsp;&nbsp;-&nbsp;Queries of this type are very important to the basic operation of Exponent.  All data stored in the database is read back through the use of SELECT queries.
<br /><br />

<tt>INSERT</tt><br />
&nbsp;&nbsp;-&nbsp;Whenever new content is added to the site, new permissions are assigned, users and/or groups are created and configuration data is saved, Exponent runs INSERT queries.
<br /><br />

<tt>UPDATE</tt><br />
&nbsp;&nbsp;-&nbsp;When content or configurations are updated, Exponent modifies the data in its tables by issuing UPDATE queries.
<br /><br />

<tt>DELETE</tt><br />
&nbsp;&nbsp;-&nbsp;These queries remove content and configuration from the tables in the site database.  They are also executed whenever users and groups are removed, and permissions are revoked.
<br /><br />

<br />