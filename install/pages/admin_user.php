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

if (!defined('PATHOS')) exit('');

$i18n = pathos_lang_loadFile('install/pages/admin_user.php');

?>
<h2 id="subtitle"><?php echo $i18n['subtitle']; ?></h2>
<form method="post">
<input type="hidden" name="page" value="save_admin" />
<div class="form_section">
	<div class="control">
		&#0149; <span class="control_caption"><?php echo $i18n['username']; ?>: </span>
		<input class="text" type="text" name="username" value="<?php echo $i18n['username_default']; ?>" />
		<div class="control_help">
			<?php echo $i18n['username_desc']; ?>
		</div>
	</div>
	<div class="control">
		&#0149; <span class="control_caption"><?php echo $i18n['password']; ?>: </span>
		<input class="text" type="text" name="password" value="<?php echo $i18n['password_default']; ?>" />
		<div class="control_help">
			<?php echo $i18n['password_desc']; ?>
		</div>
	</div>
	<div class="control">
		&#0149; <span class="control_caption"><?php echo $i18n['firstname']; ?>: </span>
		<input class="text" type="text" name="firstname" value="<?php echo $i18n['firstname_default']; ?>" />
	</div>
	<div class="control">
		&#0149; <span class="control_caption"><?php echo $i18n['lastname']; ?>: </span>
		<input class="text" type="text" name="lastname" value="<?php echo $i18n['lastname_default']; ?>" />
	</div>
	<div class="control">
		&#0149; <span class="control_caption"><?php echo $i18n['email']; ?>: </span>
		<input class="text" type="text" name="email" value="" />
	</div>
</div>
<input type="submit" value="<?php echo $i18n['continue']; ?>" class="text" />
</form>