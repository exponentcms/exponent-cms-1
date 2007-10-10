<?php

##################################################
#
# Copyright (c) 2007-2008 OIC Group, Inc.
# Written and Designed by Adam Kessler, Phillip Ball, and Ron Miller
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

function smarty_function_control($params,&$smarty) { 
	if ( (isset($params['type']) && isset($params['name'])) || $params['type'] == 'buttongroup') {
		if (!defined('SYS_FORMS')) require_once(BASE.'subsystems/forms.php');
        	exponent_forms_initialize();

		//Figure out which type of control to use. Also, some controls need some special setup.  We handle that here.
		if ($params['type'] == 'popupdatetimecontrol') {
			$control = new popupdatetimecontrol(null, "",false);
		} elseif ($params['type'] == 'buttongroup') {
			$submit = isset($params['submit']) ? $params['submit'] : null;
			$reset = isset($params['reset']) ? $params['reset'] : null;
			$cancel = isset($params['cancel']) ? $params['cancel'] : null;
			$control = new buttongroupcontrol($submit, $reset, $cancel);
		} elseif ($params['type'] == 'file') {
			$control = new uploadcontrol();
		} elseif ($params['type'] == 'dropdown') {
			$control = new dropdowncontrol();
			if (isset($params['items'])) $control->items = $params['items'];
		} elseif ($params['type'] == 'textarea') {
			$control = new texteditorcontrol();
			if (isset($params['module'])) $control->module = $params['module'];
			if (isset($params['rows'])) $control->rows = $params['rows'];
			if (isset($params['cols'])) $control->cols = $params['cols'];
			if (isset($params['toolbar'])) $control->toolbar = $params['toolbar'];
		} elseif ($params['type'] == 'editor') {
			$control = new htmleditorcontrol();
			if (isset($params['module'])) $control->module = $params['module'];
			if (isset($params['rows'])) $control->rows = $params['rows'];
			if (isset($params['cols'])) $control->cols = $params['cols'];
			if (isset($params['toolbar'])) $control->toolbar = $params['toolbar'];
		} else {
			$control = new genericcontrol($params['type']);
		}
		
		//Add the optional params in specified
		if (isset($params['class'])) $control->class = $params['class'];
		if (isset($params['$required'])) $control->required = $required;;
		if (isset($params['checked'])) $control->checked = $params['checked'];
		if (isset($params['value'])) $control->default = $params['value'];
		if (isset($params['size'])) $control->size = $params['size'];
		if (isset($params['disabled']) && $params['disabled'] != false) $control->disabled = true;
		if (isset($params['maxlength'])) $control->maxlength = $params['maxlength'];
		if (isset($params['tabindex'])) $control->tabindex = $params['tabindex'];
		if (isset($params['accesskey'])) $control->accesskey = $params['accesskey'];
		if (isset($params['filter'])) $control->filter = $params['filter'];
		if (isset($params['readonly']) && $params['readonly'] != false) $control->readonly = true;
		$control->id = isset($params['id']) && $params['id'] != "" ? $params['id'] : $params['name'];
		
		$labelclass = isset($params['labelclass']) ? ' '.$params['labelclass'] : '';
		
		//container for the controll set, including labelSpan and input
		if($params['type']!='hidden') echo '<label id="'.$control->id.'Control" class="control">'; 


		//Write out the label for this control if the user specified a label and there is no label position or position is set to left
		if ( (isset($params['label'])) && (!isset($params['labelpos']) || $params['labelpos'] == 'left') ) {
			echo '<span class="label'.$labelclass.'">'.$params['label'].'</span>';
		}
		
		//write out the control itself...and then we're done. 
		echo $control->controlToHTML($params['name']);
		
		//Write out the label for this control if the user specified a label and position is set to right
		if (isset($params['label']) && $params['labelpos'] == 'right') {
			echo '<span class="label'.$labelclass.'">'.$params['label'].'</span>';
		}
		
		//close the control container div
		if($params['type']!='hidden'){ echo '</label>'; }
	}
}

?>
