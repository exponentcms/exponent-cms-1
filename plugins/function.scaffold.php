<?php

##################################################
#
# Copyright (c) 2007-2008 OIC Group, Inc.
# Written and Designed by Adam Kessler
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

function smarty_function_scaffold($params,&$smarty) {
	if (isset($params['table']) ) {
		if (!defined('SYS_FORMS')) require_once(BASE.'subsystems/forms.php');
        	exponent_forms_initialize();
		global $db;

		$dir = BASE."datatypes/definitions";
		$file = $params['table'].".php";

		if (is_readable("$dir/$file") && is_file("$dir/$file") && substr($file,-4,4) == ".php" && substr($file,-9,9) != ".info.php") {
			$table = include("$dir/$file");
		} else {
			exit();
		}

		//eDebug($table);
        	foreach ($table as $key=>$col) {
			//Get the default value
			if ( isset($params['item']) ) $default_value = isset($params['item']->$key) ? $params['item']->$key : "";

			//Get the base control
			$control = exponent_forms_guessControlType($col, $default_value);
			
			if (isset($control) && $control != null) {	
				//format the values if needed
			        if (isset($col[FORM_FIELD_FILTER])) {
        	        		switch ($col[FORM_FIELD_FILTER]) {
						case MONEY:
                        			case DECIMAL_MONEY:
                                			$control->default = exponent_core_getCurrencySymbol('USD') . number_format($value,2,'.',',');
                                			$control->filter = 'money';
                                			break;
	                        		default:
        	                        		$control->default = $value;
                			}
        			}

				//Check for scaffolded onclick events
				if (isset($col[FORM_FIELD_ONCLICK])) $control->onclick = $col[FORM_FIELD_ONCLICK];

				//Write out the label for this control
				if (isset($col[FORM_FIELD_LABEL])) {
					echo '<div><label>'.$col[FORM_FIELD_LABEL].'</label>';
				} else {
					echo '<div><label>'.$key.'</label>';
				}

				//write out the control itself...and then we're done. 
				$control_name = isset($col[FORM_FIELD_NAME]) ? $col[FORM_FIELD_NAME] : $key;
				echo $control->controlToHTML($control_name)."</div>";
			}
        	}

		$submit = new buttongroupcontrol('Submit', 'Reset', 'Cancel'); 
		echo $submit->controlToHTML('submit');
		
		//Add the optional params in specified
		/*if (isset($params['value'])) $control->default = $params['value'];
		if (isset($params['size'])) $control->size = $params['size'];
		if (isset($params['disabled']) && $params['disabled'] != false) $control->disabled = true;
		if (isset($params['maxlength'])) $control->maxlength = $params['maxlength'];
		if (isset($params['tabindex'])) $control->tabindex = $params['tabindex'];
		if (isset($params['accesskey'])) $control->accesskey = $params['accesskey'];*/

		
	}
}

?>
