<?php

##################################################
#
# Copyright (c) 2006 Maxim Mueller
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

$item = $db->selectObject('file', 'id=' . $_GET['id']);
if ($item) {
	$loc = unserialize($item->location_data);
	$iloc = exponent_core_makeLocation($loc->mod, $loc->src, $item->id);
	
	if (exponent_permissions_check('delete', $loc) ||
		exponent_permissions_check('delete', $iloc)
	) {
		$db->delete('file', 'id=' . $item->id);
		unlink(BASE . "files/" . $item->filename);
		
		exponent_flow_redirect();
	} else {
		echo SITE_403_HTML;
	}
} else {
	echo SITE_404_HTML;
}

?>
