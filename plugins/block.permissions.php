<?php

##################################################
#
# Copyright (c) 2004-2005 James Hunt and the OIC Group, Inc.
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