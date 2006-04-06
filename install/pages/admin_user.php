<?php

##################################################
#
# Copyright (c) 2004-2006 OIC Group, Inc.
# Written and Designed by James Hunt
#
# This file is part of Exponent
#
# Exponent is free software; you can redistribute
# it and/or modify it under the terms of the GNU
# General Public License as published by the Free
# Software Foundation; either version 2 of the
# License, or (at your option) any later version.
#
# GPL: http://www.gnu.org/licenses/gpl.txt
#
##################################################

if (!defined('EXPONENT')) exit('');

$i18n = exponent_lang_loadFile('install/pages/admin_user.php');

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