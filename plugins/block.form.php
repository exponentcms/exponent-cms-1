<?php

##################################################
#
# Copyright (c) 2007-2008 OIC Group, Inc.
# Written and Designed by Adam Kessler, Phillip Ball, Ron Miller
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

function smarty_block_form($params,$content,&$smarty, &$repeat) {
	if(empty($content)){
		$name = isset($params['name']) ? 'id="'.$params['name'].'"' : '';
		$module = isset($params['module']) ? $params['module'] : $smarty->_tpl_vars['__loc']->mod;
		$method = isset($params['method']) ? $params['method'] : "post";
		$class = isset($params['class']) ? 'class="'.$params['class'].'"' : '';
		$enctype = isset($params['enctype']) ? $params['enctype'] : 'multipart/form-data';

		echo "<!-- Form Object 'form' -->\r\n";
		echo '<script type="text/javascript" src="'.PATH_RELATIVE.'subsystems/forms/js/inputfilters.js.php"></script>'."\r\n";
		echo '<script type="text/javascript" src="'.PATH_RELATIVE.'subsystems/forms/controls/listbuildercontrol.js"></script>'."\r\n";
		echo '<script type="text/javascript" src="'.PATH_RELATIVE.'subsystems/forms/js/required.js"></script>'."\r\n";
		echo '<script type="text/javascript" src="'.PATH_RELATIVE.'external/jscalendar/calendar.js"></script>'."\r\n";
		echo '<script type="text/javascript" src="'.PATH_RELATIVE.'external/jscalendar/lang/calendar-en.js"></script>'."\r\n";
		echo '<script type="text/javascript" src="'.PATH_RELATIVE.'external/jscalendar/calendar-setup.js"></script>'."\r\n";
		echo '<script type="text/javascript" src="'.PATH_RELATIVE.'js/PopupDateTimeControl.js"></script>'."\r\n";
		echo '<form '.$name.$class.' method="'.$method.'" action="'.URL_FULL.'index.php" enctype="'.$enctype.'">'."\r\n";
		echo '<input type="hidden" name="module" value="'.$module.'" />'."\r\n";
		echo '<input type="hidden" name="src" value="'.$smarty->_tpl_vars['__loc']->src.'" />'."\r\n";
		echo '<input type="hidden" name="int" value="'.$smarty->_tpl_vars['__loc']->int.'" />'."\r\n";
		echo isset ($params['ajax']) ? '<input type="hidden" name="ajax_action" value="1" />'."\r\n" : '';
		if (isset($params['action']))  echo '<input type="hidden" name="action" value="'.$params['action'].'" />'."\r\n";

		//echo the innards
	}else{
		echo $content;
		echo '</form>';
	}

}

?>

