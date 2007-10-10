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
	if(!$content){
	//handle incoming errors
	
	if (exponent_sessions_isset("last_POST")) {
		$formError  = exponent_sessions_get("last_POST");
		$smarty->assign($params['last_POST'],$formError);
		exponent_sessions_unset("last_POST");
	}
		
		
	$name = isset($params['name']) ? $params['name'] : 'form';
	echo "<!-- Form Object 'form' -->\r\n";
	echo '<script type="text/javascript" src="/subsystems/forms/js/inputfilters.js.php"></script>'."\r\n";
	echo '<script type="text/javascript" src="/subsystems/forms/controls/listbuildercontrol.js"></script>'."\r\n";
	echo '<script type="text/javascript" src="/subsystems/forms/js/required.js"></script>'."\r\n";
	echo '<form id="'.$name.'" name="'.$name.'" method="post" action="index.php" enctype="'.$params['enctype'].'">'."\r\n";
	echo '<input type="hidden" name="module" id="module" value="'.$smarty->_tpl_vars['__loc']->mod.'" />'."\r\n";
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
