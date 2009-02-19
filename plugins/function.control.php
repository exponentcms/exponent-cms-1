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
	if ( (isset($params['type']) && isset($params['name'])) || $params['type'] == 'buttongroup' || $params['type'] == 'captcha') {
		$i18n = exponent_lang_loadFile('plugins/function_control.php');

		if (!defined('SYS_FORMS')) require_once(BASE.'subsystems/forms.php');
		exponent_forms_initialize();

		// if a label wasn't passed in then we need to set one.
		if (empty($params['label'])) $params['label'] = $params['name'];

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
			$control->multiple = isset($params['multiple']) ? true : false;
			if (isset($params['from']) && isset($params['to'])) {
				for($i=$params['from']; $i <= $params['to']; $i++) {
					$control->items[$i] = isset($params['zeropad']) ? sprintf("%02d",$i) : $i;
				}
			} elseif (isset($params['key']) && isset($params['value'])) {
				foreach($params['items'] as $item) {
					$control->items[$item->$params['key']] = $item->$params['display'];
				}
			} else {
				$control->items = isset($params['items']) ? $params['items'] : array();
			}
		} elseif ($params['type'] == 'textarea') {
			$control = new texteditorcontrol();
			if (isset($params['module'])) $control->module = $params['module'];
			if (isset($params['rows'])) $control->rows = $params['rows'];
			if (isset($params['cols'])) $control->cols = $params['cols'];
			//if (isset($params['toolbar'])) $control->toolbar = $params['toolbar'];
		} elseif ($params['type'] == 'editor') {
			$control = new htmleditorcontrol();
			if (isset($params['module'])) $control->module = $params['module'];
			if (isset($params['rows'])) $control->rows = $params['rows'];
			if (isset($params['cols'])) $control->cols = $params['cols'];
			if (isset($params['toolbar'])) $control->toolbar = $params['toolbar'];
		} elseif ($params['type'] == 'listbuilder') {
                        $default = isset($params['default']) ? $params['default'] : array();
                        $source = isset($params['source']) ? $params['source'] : array();
                        $control = new listbuildercontrol($default, $source);
			echo $control->controlToHTML($params['name']);
                        return;
		} elseif ($params['type'] == 'captcha') {
			if (SITE_USE_CAPTCHA && EXPONENT_HAS_GD) {
				$name = isset($params['name']) ? $params['name'].'Control\"' : "captchaControl\"";
				echo '<div id="'.$name.' class="control"><label><span class="label">'.$params['label'].'</span>';
				echo '<span class="captcha">'.sprintf($i18n['captcha_description'],'<img class="captcha-img" src="'.PATH_RELATIVE.'captcha.php" alt="captcha information" />');

				$infoFile = BASE.'subsystems/lang/'.LANG.'/subsystems/forms/captcha_why.php';
				// If the language specific file is not found use the English
				if (!is_file($infoFile)) {
					$infoFile = URL_FULL.'subsystems/lang/eng_US/subsystems/forms/captcha_why.php';
				} else {
					// rewrite the base to full
					$infoFile = URL_FULL.'subsystems/lang/'.LANG.'/subsystems/forms/captcha_why.php';
				}
				echo '<a href="javascript:void(0)" class="captcha-why" onclick="window.open(\''.$infoFile.'\',\'mywindow\',\'width=450,height=300\')">'.$i18n['why_do_this'].'</a></span>';

				unset($params['label']);
				$params['name'] = 'captcha_string';
				$control = new textcontrol('',6);
				echo '</label></div>';
			} else {
				return false;
			}
		} elseif ($params['type'] == 'user') {
			global $db;
			$control = new dropdowncontrol();
			$control->include_blank = isset($params['includeblank']) ? $params['includeblank'] : false;
			$control->items = $db->selectDropdown('user', 'username');
		} elseif ($params['type'] == 'state') {
                        global $db;
			if ($db->tableExists('geo_region')) {
                        	$country = empty($params['country']) ? 223 : $params['country']; //check for a country,else default to US.
	                        $control = new dropdowncontrol();
        	                $control->include_blank = isset($params['includeblank']) ? $params['includeblank'] : false;
				if (isset($params['multiple'])) {
					$control->multiple = true;
					$control->items[-1] = 'ALL United States';
				}
                	        foreach($db->selectObjects('geo_region', 'country_id=223') as $state) {
					if (isset($params['abbv'])) {
						$control->items[$state->id] = $state->code;
					} else {
                        	        	$control->items[$state->id] = $state->name;
					}
	                        }

        	                // sanitize the default value. can accept as id, code abbrv or full name,
                	        if (!empty($params['value']) && !is_numeric($params['value']) && !is_array($params['value'])) {
                        	        $params['value'] = $db->selectValue('geo_region', 'id', 'name="'.$params['value'].'" OR code="'.$params['value'].'"');
	                        }
			} else {
				echo "NO TABLE"; exit();
			}
		} else {
			$control = new genericcontrol($params['type']);
		}

		//eDebug($smarty->_tpl_vars['formError']);
		//Add the optional params in specified
		if (isset($params['class'])) $control->class = $params['class'];
		if (isset($params['required'])) $control->required = true;
		if (isset($params['checked'])) $control->checked = $params['checked'];
		if (exponent_sessions_isset('last_POST')) {
			$post = exponent_sessions_get('last_POST');
			if ($params['type'] == 'checkbox') {
            	$realname = str_replace('[]', '', $params['name']);
				$control->default = $params['value'];
                if (!empty($post[$realname])) {
					if (is_array($post[$realname])) {
	            	    if (in_array($params['value'], $post[$realname])) $control->checked = true;
					} else {
						$control->checked = true;
					}
                }
			} elseif (isset($params['multiple'])){
				$realname = str_replace('[]', '', $params['name']);
				if (!empty($post[$realname])) $control->default = $post[$realname];
                        } else {
				if (!empty($post[$params['name']])) $control->default = $post[$params['name']];
                        }
		} elseif (isset($params['value'])) {
			$control->default = $params['value'];
		}
		//if (isset($params['value'])) $control->default = $params['value'];
		if (isset($params['size'])) $control->size = $params['size'];
		if (isset($params['flip'])) $control->flip = $params['flip'];
		if (isset($params['disabled']) && $params['disabled'] != false) $control->disabled = true;
		if (isset($params['maxlength'])) $control->maxlength = $params['maxlength'];
		if (isset($params['tabindex'])) $control->tabindex = $params['tabindex'];
		if (isset($params['accesskey'])) $control->accesskey = $params['accesskey'];
		if (isset($params['filter'])) $control->filter = $params['filter'];
		if (isset($params['onclick'])) $control->onclick = $params['onclick'];
		if (isset($params['onchange'])) $control->onchange = $params['onchange'];
		if (isset($params['readonly']) && $params['readonly'] != false) $control->readonly = true;

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
