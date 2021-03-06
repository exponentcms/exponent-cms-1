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

/**
 * Anti-Spam Control
 *
 * @author Ron Miller
 * @copyright 2004-2007 OIC Group, Inc.
 * @version 0.96.6
 *
 * @package Subsystems
 * @subpackage Forms
 */

/**
 * Manually include the class file for formcontrol, for PHP4
 * (This does not adversely affect PHP5)
 */
require_once(BASE."subsystems/forms/controls/formcontrol.php");

/**
 * Text Control
 *
 * @package Subsystems
 * @subpackage Forms
 */
class antispamcontrol extends formcontrol {

	function name() { return "Anti-Spam Control"; }
	function isSimpleControl() { return true; }

	function controlToHTML($name) {
		$i18n = exponent_lang_loadFile('subsystems/forms/controls/antispamcontrol.php');
		$html = $i18n['title'];
		switch ( rand(1,2) ) {
			case 1:
				$html .= $i18n['wrong1'];
			break;
			case 2:
				$html .= $i18n['wrong2'];
				$html .= $i18n['wrong3'];
				$html .= $i18n['correct'];
			break;
		}
		$html .= '</div>';

		return $html;
	}

	function form($object) {
		if (!defined("SYS_FORMS")) require_once(BASE."subsystems/forms.php");
		exponent_forms_initialize();

		$form = new form();
		if (!isset($object->identifier)) {
			$object->identifier = "";
			$object->caption = "";
			$object->default = "";
			$object->size = 0;
			$object->maxlength = 0;
			$object->required = false;
		}
		$i18n = exponent_lang_loadFile('subsystems/forms/controls/textcontrol.php');

		$form->register("identifier",$i18n['identifier'],new textcontrol($object->identifier));
		$form->register("caption",$i18n['caption'], new textcontrol($object->caption));
		$form->register(null, null, new htmlcontrol('<br />'));
		$form->register(null, null, new htmlcontrol('<br />'));
		$form->register("submit","",new buttongroupcontrol($i18n['save'],'',$i18n['cancel']));
		return $form;
	}

	function update($values, $object) {
		if ($object == null) $object = new antispamcontrol();
		if ($values['identifier'] == "") {
			$i18n = exponent_lang_loadFile('subsystems/forms/controls/textcontrol.php');
			$post = $_POST;
			$post['_formError'] = $i18n['id_req'];
			exponent_sessions_set("last_POST",$post);
			return null;
		}
		$object->identifier = $values['identifier'];
		$object->caption = $values['caption'];
		$object->default = $values['default'];
		$object->size = intval($values['size']);
		$object->maxlength = intval($values['maxlength']);
		$object->required = isset($values['required']);
		return $object;
	}

}

?>
