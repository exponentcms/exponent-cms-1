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

function smarty_function_img($params,&$smarty) {
	$alt = isset($params['alt']) ? $params['alt'] : 'Image';
	$closing = (XHTML==1) ? ' />' : '>';

	if (isset($params['width']) || isset($params['height']) || isset($params['square'])) {
		$src = URL_FULL.'thumb.php';

		// figure out which file we're showing
		if (isset($params['file'])) $src .= '?file='.$params['file'];
		if (isset($params['file_id'])) $src .= '?id='.$params['file_id'];

		// get the image dimensions
		if (isset($params['constraint'])) $src .= '&amp;constraint=1';
		if (isset($params['square'])) $src .= '&amp;square='.$params['square'];
		if (isset($params['width'])) $src .= '&amp;width='.$params['width'];
		if (isset($params['height'])) $src .= '&amp;height='.$params['height'];
	} else {
		$src = $params['src'];
	}

	echo '<img src="'.$src.'" alt="'.$alt.'"'.$closing;
}

?>

