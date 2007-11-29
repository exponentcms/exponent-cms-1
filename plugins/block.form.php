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
		//handle incoming errors
		
		if (exponent_sessions_isset("last_POST")) {
			$formError  = exponent_sessions_get("last_POST");
			$smarty->assign('formError', $formError);
			exponent_sessions_unset("last_POST");
			echo '<ul class="error">';
				foreach($formError['_formError'] as $err) {
					echo '<li>'.$err."</li>";
				}
			echo '</ul>';
		}
		
		
		$name = isset($params['name']) ? $params['name'] : 'form';
		$module = isset($params['module']) ? $params['module'] : $smarty->_tpl_vars['__loc']->mod;

		echo "<!-- Form Object 'form' -->\r\n";
		echo '<script type="text/javascript" src="'.PATH_RELATIVE.'subsystems/forms/js/inputfilters.js.php"></script>'."\r\n";
		echo '<script type="text/javascript" src="'.PATH_RELATIVE.'subsystems/forms/controls/listbuildercontrol.js"></script>'."\r\n";
		echo '<script type="text/javascript" src="'.PATH_RELATIVE.'subsystems/forms/js/required.js"></script>'."\r\n";
		echo '<script type="text/javascript" src="'.PATH_RELATIVE.'external/jscalendar/calendar.js"></script>'."\r\n";
		echo '<script type="text/javascript" src="'.PATH_RELATIVE.'external/jscalendar/lang/calendar-en.js"></script>'."\r\n";
		echo '<script type="text/javascript" src="'.PATH_RELATIVE.'external/jscalendar/calendar-setup.js"></script>'."\r\n";
		echo '<script type="text/javascript" src="'.PATH_RELATIVE.'js/PopupDateTimeControl.js"></script>'."\r\n";
		echo '<form id="'.$name.'" name="'.$name.'" method="post" action="index.php" enctype="'.$params['enctype'].'">'."\r\n";
		echo '<input type="hidden" name="module" id="module" value="'.$module.'" />'."\r\n";
		echo '<input type="hidden" name="src" id="src" value="'.$smarty->_tpl_vars['__loc']->src.'" />'."\r\n";
		echo '<input type="hidden" name="int" id="int" value="'.$smarty->_tpl_vars['__loc']->int.'" />'."\r\n";
		echo isset ($params['ajax']) ? '<input type="hidden" name="ajax_action" value="1" />'."\r\n" : '';
		if (isset($params['action']))  echo '<input type="hidden" name="action" id="action" value="'.$params['action'].'" />'."\r\n";
	
		//echo the innards
	}else{	
		echo $content;	
		echo '</form>';
	}
	
}

?>

