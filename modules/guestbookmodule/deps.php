<?php
#############################################################
# GUESTBOOKMODULE
#############################################################
# Copyright (c) 2005-2006 Dirk Olten, http://www.extrabyte.de
#
# version 0.5:	Developement-Version
# version 1.0:	1st release for Exponent v0.93.3
# version 1.2:	Captcha added
# version 2.0:	now compatible to Exponent v0.93.5
#
# Exponent is free software; you can redistribute
# it and/or modify it under the terms of the GNU
# General Public License as published by the Free
# Software Foundation; either version 2 of the
# License, or (at your option) any later version.
#
# GPL: http://www.gnu.org/licenses/gpl.txt
#
##############################################################

if (!defined('EXPONENT')) exit('');

return array(
	's_datetime'=>array(
		'name'=>'datetime',
		'type'=>CORE_EXT_SUBSYSTEM,
		'comment'=>''
	),
	's_search'=>array(
		'name'=>'search',
		'type'=>CORE_EXT_SUBSYSTEM,
		'comment'=>''
	)
);

?>