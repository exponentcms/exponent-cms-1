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

pathos_sessions_unset('installer_config');
$i18n = pathos_lang_loadFile('install/pages/final.php');

?>
<h2 id="subtitle"><?php echo $i18n['subtitle']; ?></h2>
<?php

unlink(BASE.'install/not_configured');
if (file_exists(BASE.'install/not_configured')) {
	echo '<br /><br />';
	echo '<span style="color: red">'.$i18n['no_remove'].'</span>';
}

?>
<br /><br />
<?php echo $i18n['success']; ?>
<br /><br />
<a href="<?php echo URL_FULL; ?>index.php"><?php echo $i18n['visit']; ?></a>.
<br /><br />