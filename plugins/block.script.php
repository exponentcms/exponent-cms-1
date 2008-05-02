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

function smarty_block_script($params,$content,&$smarty, &$repeat) {
	if ($content) {
		global $userjsfiles;
		
		if (empty($params['unique'])) die("<strong style='color:red'>The 'unique' parameter is required for the {script} pluggin.</strong>"); 
		
		
		if (!empty($params['yuimodules'])) {
			
			$userjsfiles['yuimodules'][$params['unique']] = $params['yuimodules'];
			$userjsfiles['yuiloader'][$params['unique']] = $content;
			
		} else {
			$userjsfiles[$smarty->_tpl_vars[__name]][$params['unique']] = $content;
		}
	}
	
}


?>