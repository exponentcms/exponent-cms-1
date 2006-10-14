<?php

##################################################
#
# Copyright (c) 2004-2006 OIC Group, Inc.
# Copyright (c) 2005-2006 Maxim Mueller
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

/*
 * WYSIWYG Editor Control Class
 * Controller Part of the Editor View Item
 *
 * @author Maxim Mueller, based on James Hunt's htmleditorcontrol
 * @copyright 2006 Maxim Mueller
 * @version 0.99
 *
 * @package Subsystems
 * @subpackage Forms
 */
class EditorControl extends formcontrol {
	
	public $datamodel; //every view item commands a datamodel
	public $context; //most likely identical to $loc
	
	//PHP5 constructor
	function __construct($content = "", $context = "") {
		$this->datamodel = new Object();
		
		$this->datamodel->content = htmlentities($content, ENT_COMPAT, LANG_CHARSET); // the content that is to be edited
		//FCKeditor requires special treatment, as always
		//this time it cannot stomack newlines
		if (SITE_WYSIWYG_EDITOR == "FCKeditor") {
			$this->datamodel->content = addslashes(str_replace(array("\n","\r"), "", $this->datamodel->content));
		}
		$this->$context = $context; // For looking up templates.
	}
	
	//DEPRECATED: naming not sufficiently generic
	function controlToHTML($name) {
		global $db;
		// datamodel layer does not exist yet
		$this->datamodel->name = $name;
		$this->datamodel->path_to_editor = PATH_RELATIVE . "external/editors/" . SITE_WYSIWYG_EDITOR . "/";
		$this->datamodel->toolbar = $db->selectObject("toolbar_" . SITE_WYSIWYG_EDITOR, "active=1");
		//use base class inherited method instead
		return $this->show(SITE_WYSIWYG_EDITOR);
	}
	
	function parseData($name, $values, $for_db = false) {
		$html = $values[$name];
		if (trim($html) == "<br />") $html = "";
		return $html;
	}
}

?>
