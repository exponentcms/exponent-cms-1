<?php

##################################################
#
# Copyright (c) 2004-2005 James Hunt and the OIC Group, Inc.
# All Changes as of 6/1/05 Copyright 2005 James Hunt
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

$i18n = pathos_lang_loadFile('install/popups/db_priv.php');

?>
<b><?php echo $i18n['title']; ?></b>
<div class="bodytext">
<?php echo $i18n['header']; ?>
<br /><br />

<tt><?php echo $i18n['create']; ?></tt><br />
&nbsp;&nbsp;-&nbsp;<?php echo $i18n['create_desc']; ?>
<br /><br />

<tt><?php echo $i18n['alter']; ?></tt><br />
&nbsp;&nbsp;-&nbsp;<?php echo $i18n['alter_desc']; ?>
<br /><br />

<tt><?php echo $i18n['drop']; ?></tt><br />
&nbsp;&nbsp;-&nbsp;<?php echo $i18n['drop_desc']; ?>
<br /><br />

<tt><?php echo $i18n['select']; ?></tt><br />
&nbsp;&nbsp;-&nbsp;<?php echo $i18n['select_desc']; ?>
<br /><br />

<tt><?php echo $i18n['insert']; ?></tt><br />
&nbsp;&nbsp;-&nbsp;<?php echo $i18n['insert_desc']; ?>
<br /><br />

<tt><?php echo $i18n['update']; ?></tt><br />
&nbsp;&nbsp;-&nbsp;<?php echo $i18n['update_desc']; ?>
<br /><br />

<tt><?php echo $i18n['delete']; ?></tt><br />
&nbsp;&nbsp;-&nbsp;<?php echo $i18n['delete_desc']; ?>
</div>