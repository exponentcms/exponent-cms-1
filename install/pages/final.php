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

exponent_sessions_unset('installer_config');
$i18n = exponent_lang_loadFile('install/pages/final.php');

?>
<h2 id="subtitle"><?php echo $i18n['subtitle']; ?></h2>
<?php

unlink(BASE.'install/not_configured');

//old files merged into coretasks.php
if (file_exists(BASE.'modules/administrationmodule/tasks/files_tasks.php')){
	$ret = unlink(BASE.'modules/administrationmodule/tasks/files_tasks.php');
	if ($ret == false){
		echo '<br />';
        	echo '<span style="color: red">'.$i18n['no_remove_filetask'].'</span>';
	}
}

if (file_exists(BASE.'modules/administrationmodule/tasks/workflow_tasks.php')){
	$ret = unlink(BASE.'modules/administrationmodule/tasks/workflow_tasks.php');
	if ($ret == false){
		echo '<br /><br />';
        	echo '<span style="color: red">'.$i18n['no_remove_workflow'].'</span>';
	}
}

if (file_exists(BASE.'install/not_configured')) {
	echo '<br /><br />';
	echo '<span style="color: red">'.$i18n['no_remove'].'</span>';
}

?>
<br /><br />
<?php echo $i18n['success']; ?>
<?php exponent_sessions_clearAllSessionData();?>
<br /><br />
<span style="font-weight:bold;color:red;">
	<u>Important Notice:</u></span>
<span style="color:red;font-size:12px;"><br />
<ul>
<li><b>Important: **You should now remove or rename the 'install' directory.**</b><br/></li>
<li>The PATHOS function, constant, and file prefixes are deprecated in favor of the EXPONENT prefix. The limited compatibility layer currently in place will be removed on our next release.</li>
</ul>
<br />
<br />
<a href="<?php echo URL_FULL; ?>index.php"><?php echo $i18n['visit']; ?></a>.
<br /><br />
