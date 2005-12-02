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

if (pathos_permissions_check('manage_channel',$loc)) {

	pathos_flow_set(SYS_FLOW_PROTECTED,SYS_FLOW_ACTION);

	if (!defined('SYS_CHANNELS')) require_once(BASE.'subsystems/channels.php');
	$channel = pathos_channels_getChannel($loc);
	if ($channel) {
		$items = pathos_channels_getItems($channel);
		
		$template = new template('newsmodule','_channelManager',$loc);
		$template->assign('channel',$channel);
		$template->assign('items',$items);
		$template->output();
	} else {
		echo SITE_404_HTML;
	}
} else {
	echo SITE_403_HTML;
}

?>