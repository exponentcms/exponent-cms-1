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

function smarty_block_permissions($params,$content,&$smarty, &$repeat) {
	if ($content) {
		$uilevel = 99; // MAX
		if (pathos_sessions_isset("uilevel")) $uilevel = pathos_sessions_get("uilevel");
		if (defined("PREVIEW_READONLY")) $uilevel = -1;
		
		$blocklevel = (isset($params['level']) ? $params['level'] : 0);
		
		if ($blocklevel == UILEVEL_PERMISSIONS && substr($smarty->_tpl_vars['__loc']->src,0,5) == "@uid_") return "";
		else if ($blocklevel <= $uilevel) return $content;
		else return "";
	}
}

?>