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
 * Text Editor Control
 *
 * @author James Hunt
 * @copyright 2004-2006 OIC Group, Inc.
 * @version 0.95
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
 * Text Editor Control
 *
 * @package Subsystems
 * @subpackage Forms
 */
class yuieditorcontrol extends formcontrol {
	var $cols = 60;
	var $rows = 20;
	
	function name() { return "YUI Editor"; }
	function isSimpleControl() { return false; }
	function getFieldDefinition() {
		return array(
			DB_FIELD_TYPE=>DB_DEF_STRING,
			DB_FIELD_LEN=>10000);
	}
	
	function yuieditorcontrol($default="",$rows = 20,$cols = 60) {
		$this->default = $default;
		$this->rows = $rows;
		$this->cols = $cols;
		$this->required = false;
		$this->maxchars = 0;
	}

	function controlToHTML($name) {
		$html = "<textarea name=\"$name\" id=\"$name\"";
		$html .= " rows=\"" . $this->rows . "\" cols=\"" . $this->cols . "\"";
		if ($this->accesskey != "") $html .= " accesskey=\"" . $this->accesskey . "\"";
		if (!empty($this->class)) $html .= " class=\"" . $this->class . "\"";
		if ($this->tabindex >= 0) $html .= " tabindex=\"" . $this->tabindex . "\"";
		if (@$this->required) {
			$html .= ' required="'.rawurlencode($this->default).'" caption="'.rawurlencode($this->caption).'" ';
		}
		$html .= ">";
		$html .= $this->default;
		$html .= "</textarea>";
		$html .= "
		
		<script>
		var myEditor = new YAHOO.widget.Editor('".$name."', {
		    height: '300px',
		    width: '522px',
		    dompath: true, //Turns on the bar at the bottom
		    animate: true, //Animates the opening, closing and moving of Editor windows
			handleSubmit:true
		});
		//Wait for the editor's toolbar to load
		myEditor.on('toolbarLoaded', function() {
		    //create the new gutter object
		    gutter = new YAHOO.gutter();

		    //The Toolbar buttons config
		    var flickrConfig = {
		            type: 'push',
		            label: 'Insert Flickr Image',
		            value: 'flickr'
		    }

		    //Add the button to the insertitem group
		    myEditor.toolbar.addButtonToGroup(flickrConfig, 'insertitem');

		    //Handle the button's click
		    myEditor.toolbar.on('flickrClick', function() {
		        this._focusWindow();
		        if (ev && ev.img) {
		            //To abide by the Flickr TOS, we need to link back to the image that we just inserted
		            var html = '<a href=\"' + ev.url + '\"><img src=\"' + ev.img + '\" title=\"' + ev.title + '\"></a>';
		            this.execCommand('inserthtml', html);
		        }
		        //Toggle the gutter, so that it opens and closes based on this click.
		        gutter.toggle();
		    });
		    //Create the gutter control
		    gutter.createGutter();
		});
		myEditor.render();
		
		
		
		
		
			
		</script>
		";
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
			$object->rows = 20;
			$object->cols = 60;
			$object->maxchars = 0;
		} 
		
		$i18n = exponent_lang_loadFile('subsystems/forms/controls/texteditorcontrol.php');
		
		$form->register("identifier",$i18n['identifier'],new textcontrol($object->identifier));
		$form->register("caption",$i18n['caption'], new textcontrol($object->caption));
		$form->register("default",$i18n['default'],  new texteditorcontrol($object->default));
		$form->register("rows",$i18n['rows'], new textcontrol($object->rows,4,false,3,"integer"));
		$form->register("cols",$i18n['cols'], new textcontrol($object->cols,4, false,3,"integer"));
		$form->register("submit","",new buttongroupcontrol($i18n['save'],'',$i18n['cancel']));
		return $form;
	}
	
	function update($values, $object) {
		if ($object == null) $object = new texteditorcontrol();
		if ($values['identifier'] == "") {
			$i18n = exponent_lang_loadFile('subsystems/forms/controls/texteditorcontrol.php');
			$post = $_POST;
			$post['_formError'] = $i18n['id_req'];
			exponent_sessions_set("last_POST",$post);
			return null;
		}
		$object->identifier = $values['identifier'];
		$object->caption = $values['caption'];
		$object->default = $values['default'];
		$object->rows = intval($values['rows']);
		$object->cols = intval($values['cols']);
		$object->maxchars = intval($values['maxchars']);
		$object->required = isset($values['required']);
		
		return $object;
	
	}
	
	function parseData($original_name,$formvalues,$for_db = false) {
		return str_replace(array("\r\n","\n","\r"),'<br />', htmlspecialchars($formvalues[$original_name])); 
	}
	
}

?>
