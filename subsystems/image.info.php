<?php

##################################################
#
# Copyright (c) 2004-2005 OIC Group, Inc.
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

if (!defined('PATHOS')) exit('');

$i18n = pathos_lang_loadFile('subsystems/image.info.php');

return array(
	'name'=>$i18n['subsystem_name'],
	'author'=>'James Hunt',
	'description'=>$i18n['subsystem_description'],
	'version'=>pathos_core_version(true)
);

?>