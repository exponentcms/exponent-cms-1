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

function smarty_function_icon($params,&$smarty) {
	$loc = $smarty->_tpl_vars['__loc'];

	// setup the link params
	if (!isset($params['module'])) $params['module'] = $loc->mod;
	if (!isset($params['src']) && @call_user_func(array($loc->mod,'hasSources'))) $params['src'] = $loc->src;
	if (!isset($params['int'])) $params['int'] = $loc->int;

	// figure out whether to use the edit icon or text, alt tags, etc.
	$title 	= (empty($params['title'])) ? '' : $params['title'];
	$alt 	= (empty($params['alt'])) ? '' : $params['alt'];
	$text 	= (empty($params['text'])) ? '' : $params['text'];
	$img 	= (empty($params['img'])) ? '' : '<img src="'.ICON_RELATIVE.$params['img'].'" title="'.$title.'" alt="'.$alt.'" '.XHTML_CLOSING.'>';
	$linktext = $img.$text;

	// we need to unset these vars before we pass the params array off to makeLink
	unset($params['alt']);
	unset($params['title']);
	unset($params['text']);
	unset($params['img']);

	if (!empty($params['action'])) {
		echo '<a href="'.exponent_core_makeLink($params).'" title="'.$title.'"';
		if (isset($params['onclick'])) echo ' onclick="'.$params['onclick'].'"';
		echo '>'.$linktext.'</a>';
	} else {
		echo $linktext;
	}
}

?>
