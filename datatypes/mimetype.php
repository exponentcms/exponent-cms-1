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

//TODO: bring back icon selector, this time based on the filebrowser engine
class mimetype {
	function form($object) {
		$i18n = exponent_lang_loadFile('datatypes/mimetype.php');
		
		$form = new form();
		if (!isset($object->mimetype)) {
			$object->mimetype = '';
			$object->name = '';
			$object->icon = 'mime_empty.png';
		} else {
			$form->meta('oldmime',$object->id);
		}
		
		$form->register('mimetype',$i18n['mimetype'], new textcontrol($object->mimetype));
		$form->register('name',$i18n['name'],new textcontrol($object->name));
		
		// Replace this with something a little better.
//		$icodir = MIMEICON_RELATIVE;
		$icontype = pathinfo($object->icon);
		$htmlimg = ($object->icon == '' ? '' : '<img src="'.MIMEICON_RELATIVE.$object->icon.'"/>');
		$form->register('currenticon',$i18n['currenticon'],new customcontrol($htmlimg));	

		$mimeabs = __realpath($_SERVER['DOCUMENT_ROOT']).MIMEICON_RELATIVE;
		$iconlist = array();
		$selected = null;
		if ($handle = opendir($mimeabs)) {
			/* This is the correct way to loop over the directory. */
			while (false !== ($file = readdir($handle))) {
				$name = pathinfo($file);
				if (($name['filename'] != '.') && (! empty($name['filename']))) {
					$iconlist[] = $name['filename'];
				}
			}
			closedir($handle);
		}
		sort($iconlist);
		if (! empty($icontype['filename'])) {
			$selected = array_search($icontype['filename'],$iconlist);
		} else {
			$selected = array_search('mime_empty',$iconlist);
		}
		$form->register('icon',$i18n['icon'],new dropdowncontrol($selected,$iconlist));
			
		$form->register('submit','',new buttongroupcontrol($i18n['save'],'',$i18n['cancel']));
		return $form;
	}
	
	function update($values,$object) {
		$object->mimetype = $values['mimetype'];
		$object->name = $values['name'];
		$mimeabs = __realpath($_SERVER['DOCUMENT_ROOT']).MIMEICON_RELATIVE;
		$iconlist = array();
		$selected = null;
		if ($handle = opendir($mimeabs)) {
			/* This is the correct way to loop over the directory. */
			while (false !== ($file = readdir($handle))) {
				$name = pathinfo($file);
				if (($name['filename'] != '.') && (! empty($name['filename']))) {
					$iconlist[] = $name['filename'];
				}
			}
			closedir($handle);
		}
		sort($iconlist);
		$icon = $iconlist[$values['icon']];
		if (empty($values['icon'])) {
			$object->icon = "mime_empty.png";
//			$object->icon = "";
		} else {
			$object->icon = $icon.".png";
		}		
		return $object;
	}
}
?>