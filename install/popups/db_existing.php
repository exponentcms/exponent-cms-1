<?php

##################################################
#
# Copyright (c) 2004-2005 James Hunt and the OIC Group, Inc.
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
Using an Existing Database
</div>
<br /><br />
A pre-existing database can be used to store the content of your website, however a few issues must be dealt with.
<br /><br />
Exponent needs its own set of tables within a pre-existing database in order to function properly.  This can be accomplished by specifying a new table prefix.
<br /><br />
The table prefix is used to make each table's name in the database unique.  It is prepended to the name of each table.  This means that two Exponent sites can use the database 'db' if one has a table prefix of "exponent_" and the other uses "cms_".
<br /><br />
By convention, a table prefix ends with an underscore.  This improves database readability, and helps with troubleshooting.
<br />