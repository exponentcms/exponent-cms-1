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

if (!defined('EXPONENT')) exit('');

include_once(BASE."subsystems/forms/form.php");

class fakeform extends form {
	function toHTML($form_id, $module="formbuilder") {
		// Form validation script
		if ($this->validationScript != "") {
			$this->scripts[] = $this->validationScript;
			$this->controls["submit"]->validateJS = "validate(this.form)";
		}

		// Persistent Form Data extension
		$formError = "";
		if (exponent_sessions_isset("last_POST")) {
			// We have cached POST data.  Use it to update defaults.
			$last_POST = exponent_sessions_get("last_POST");

			foreach (array_keys($this->controls) as $name) {
				// may need to look to control a la parseData
				$this->controls[$name]->default = @$last_POST[$name];
			}

			$formError = @$last_POST['_formError'];

			exponent_sessions_unset("last_POST");
		}

		global $router;
		$html = "<!-- Form Object '" . $this->name . "' -->\r\n";
		$html .= "<script type=\"text/javascript\" src=\"" .PATH_RELATIVE."subsystems/forms/js/inputfilters.js.php\"></script>\r\n";
		foreach ($this->scripts as $name=>$script) $html .= "<script type=\"text/javascript\" src=\"$script\"></script>\r\n";
		$html .= $formError;
		$html .= "<form name=\"" . $this->name . "\" method=\"" . $this->method . "\" action=\"" . $this->action . "\" enctype=\"".$this->enctype."\">\r\n";
		foreach ($this->meta as $name=>$value) $html .= "<input type=\"hidden\" name=\"$name\" id=\"$name\" value=\"$value\" />\r\n";
		$html .= "<table cellspacing=\"0\" cellpadding=\"5\" width=\"100%\">\r\n";
		$rank = 0;
		foreach ($this->controlIdx as $name) {
			$html .= "<tr><td valign=\"top\">".$this->controlLbl[$name]."</td><td style='padding-left: 5px;' valign=\"top\">";
			$html .= $this->controls[$name]->controlToHTML($name) . "\r\n";
			$html .= "<td>";
			if ($rank != count($this->controlIdx)-1) {
				//$html .= '<a href="?module='.$module.'&action=order_controls&p='.$form_id.'&a='.$rank.'&b='.($rank+1).'">';
				$html .= '<a href="'.$router->makeLink(array('module'=>$module, 'action'=>'order_controls', 'p'=>$form_id, 'a'=>$rank, 'b'=>($rank+1))).'">';
				$html .= "<img border='0' src='".ICON_RELATIVE."down.png' />";
				$html .= '</a>';
			} else {
				$html .= "<img src='".ICON_RELATIVE."down.disabled.png' />";
			}
			$html .= "&nbsp;";
			if ($rank != 0) {
				//$html .= '<a href="?module='.$module.'&action=order_controls&p='.$form_id.'&a='.$rank.'&b='.($rank-1).'">';
				$html .= '<a href="'.$router->makeLink(array('module'=>$module, 'action'=>'order_controls', 'p'=>$form_id, 'a'=>$rank, 'b'=>($rank-1))).'">';
				$html .= "<img border='0' src='".ICON_RELATIVE."up.png' />";
				$html .= '</a>';
			} else {
				$html .= "<img src='".ICON_RELATIVE."up.disabled.png' />";
			}

			$html .= "&nbsp;&nbsp;";
			if (!$this->controls[$name]->_readonly) {
				//$html .= '<a href="?module='.$module.'&action=edit_control&id='.$this->controls[$name]->_id.'&form_id='.$form_id.'">';
				$html .= '<a href="'.$router->makeLink(array('module'=>$module, 'action'=>'edit_control', 'id'=>$this->controls[$name]->_id, 'form_id'=>$form_id)).'">';
				$html .= '<img style="border:none;" src="'.ICON_RELATIVE.'edit.png" />';
				$html .= '</a>';
			} else {
				$html .= '<img style="border:none;" src="'.ICON_RELATIVE.'edit.disabled.png" />';
			}

			$html .= '&nbsp;';
			if (!$this->controls[$name]->_readonly && $this->controls[$name]->_controltype != 'htmlcontrol' ) {
				//$html .= '<a href="?module='.$module.'&action=delete_control&id='.$this->controls[$name]->_id.'" onclick="return confirm(\'Are you sure you want to delete this control? All data associated with it will be removed from the database!\');">';
				$html .= '<a href="'.$router->makeLink(array('module'=>$module,'action'=>'delete_control','id'=>$this->controls[$name]->_id)).'" onclick="return confirm(\'Are you sure you want to delete this control? All data associated with it will be removed from the database!\');">';
			}
			else {
				$html .= '<a href="'.$router->makeLink(array('module'=>$module,'action'=>'delete_control','id'=>$this->controls[$name]->_id)).'" onclick="return confirm(\'Are you sure you want to delete this?\');">';
			}
			$html .= '<img style="border:none;" src="'.ICON_RELATIVE.'delete.png" />';
			$html .= '</a>';
			$html .= "</td>";
			$html .= "</td></tr>";

			$rank++;
		}
		$html .= "<tr><td width='5%'></td><td wdith='90%'><td></td width='5%'></tr>\r\n";
		$html .= "</table>\r\n";
		$html .= "</form>\r\n";
		return $html;
	}
}

?>
