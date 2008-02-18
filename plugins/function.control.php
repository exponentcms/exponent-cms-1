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
	if ( (isset($params['type']) && isset($params['name'])) || $params['type'] == 'buttongroup' || $params['type'] == 'capcha') {
		$i18n = exponent_lang_loadFile('plugins/function_control.php');

		if (!defined('SYS_FORMS')) require_once(BASE.'subsystems/forms.php');
			exponent_forms_initialize();

		//Figure out which type of control to use. Also, some controls need some special setup.	 We handle that here.
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
			$control->include_blank = isset($params['includeblank']) ? $params['includeblank'] : false;
			if (isset($params['from']) && isset($params['to'])) {
				for($i=$params['from']; $i <= $params['to']; $i++) {
					$control->items[$i] = $i;
				}
			} else {
				$control->items = isset($params['items']) ? $params['items'] : array();
			}
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
		} elseif ($params['type'] == 'capcha') {
			if (SITE_USE_CAPTCHA && EXPONENT_HAS_GD) {
				echo '<div class="capcha">'.sprintf($i18n['captcha_description'],'<img class="capcha-img" src="'.PATH_RELATIVE.'captcha.php" />');
				echo '<a href="javascript:void(0)" class="capcha-why" onclick="window.open(\''.URL_FULL.'/captcha_why.php\',\'mywindow\',\'width=450,height=300\')">'.$i18n['why_do_this'].'</a></div>';
				$params['name'] = 'captcha_string';
				$control = new textcontrol('',6);
			} else {
				return false;
			}
		} elseif ($params['type'] == 'hidden') {
			$control = new hiddenfieldcontrol();			
	 	}elseif ($params['type'] == 'checkbox') {
			$control = new checkboxcontrol();			
	 	}elseif ($params['type'] == 'radio') {
			$control = new radiocontrol();			
		} else {
			$control = new genericcontrol($params['type']);
		}
	
		//eDebug($smarty->_tpl_vars['formError']);	
		//Add the optional params in specified
		if (isset($params['class'])) $control->class = $params['class'];
		if (isset($params['$required'])) $control->required = $required;;
		if (isset($params['checked'])) $control->checked = $params['checked'];
		if (isset($params['value'])) $control->default = $params['value'];
		if (isset($params['size'])) $control->size = $params['size'];
		if (isset($params['flip'])) $control->flip = $params['flip'];
		echo $control->flip;
		if (isset($params['disabled']) && $params['disabled'] != false) $control->disabled = true;
		if (isset($params['maxlength'])) $control->maxlength = $params['maxlength'];
		if (isset($params['tabindex'])) $control->tabindex = $params['tabindex'];
		if (isset($params['accesskey'])) $control->accesskey = $params['accesskey'];
		if (isset($params['filter'])) $control->filter = $params['filter'];
		if (isset($params['readonly']) && $params['readonly'] != false) $control->readonly = true;

		// check to see if we are returning to the form via errors...if so use the post data instead.
		if (isset($smarty->_tpl_vars['formError'])) {
			if ($params['type'] == 'checkbox') {
				$realname = str_replace('[]', '', $params['name']);
				if (!empty($smarty->_tpl_vars['formError'][$realname])) {
					if (in_array($params['value'], $smarty->_tpl_vars['formError'][$realname])) $control->checked = true;
				}
			} else {
				$control->default = $smarty->_tpl_vars['formError'][$params['name']];
			}
		}

		$control->name = $params['name'];
		$control->id = isset($params['id']) && $params['id'] != "" ? $params['id'] : $params['name'];

		/*$labelclass = isset($params['labelclass']) ? ' '.$params['labelclass'] : '';
		
		//container for the controll set, including labelSpan and input
		if($params['type']!='hidden') echo '<label id="'.$control->id.'Control" class="control">'; 


		//Write out the label for this control if the user specified a label and there is no label position or position is set to left
		if ( (isset($params['label'])) && (!isset($params['labelpos']) || $params['labelpos'] == 'left') ) {
			echo '<span class="label'.$labelclass.'">'.$params['label'].'</span>';
		}
		*/
		//write out the control itself...and then we're done. 
		if (isset($params['model'])) {
			echo $control->toHTML($params['model'].'['.$params['name'].']');
		} else {
			echo $control->toHTML($params['label'],$params['name']);
		}
		/*
		//Write out the label for this control if the user specified a label and position is set to right
		if (isset($params['label']) && $params['labelpos'] == 'right') {
			echo '<span class="label'.$labelclass.'">'.$params['label'].'</span>';
		}
		
		//close the control container div
		if($params['type']!='hidden'){ echo '</label>'; }
		*/
	} else {
		echo "Both the \"type\" and \"name\" parameters are required for the control plugin to function";
	}
}

?>
