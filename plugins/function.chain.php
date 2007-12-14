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

function smarty_function_chain($params,&$smarty) {
	if (empty($params['module']))  return false;

	$src = isset($params['src']) ? $params['src'] : $smarty->_tpl_vars['__loc']->src;
	$title = isset($params['title']) ? $params['title'] : '';
	$view = isset($params['view']) ? $params['view'] : 'Default';
	$action = isset($params['action']) ? $params['action'] : null;

	if (empty($action)) {
		echo exponent_theme_showModule($params['module'], $view, $title, $src);
	} else {
		echo exponent_theme_showAction($params['module'], $action, $src);
	}
}

?>

