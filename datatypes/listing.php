<?php

##################################################
#
# Copyright (c) 2004-2005 OIC Group, Inc.
#
# This file is part of Exponent
#
# Exponent is free software; you can redistribute
# it and/or modify it under the terms of the GNU
# General Public License as published by the Free
# Software Foundation; either version 2 of the
# License, or (at your option) any later version.A
#A
# Exponent is distributed in the hope that it
# will be useful, but WITHOUT ANY WARRANTY;
# without even the implied warranty of
# MERCHANTABILITY or FITNESS FOR A PARTICULAR
# PURPOSE.  See the GNU General Public License
# for more details.
#
# You should have received a copy of the GNU
# General Public License along with Exponent; if
# not, write to:
#
# Free Software Foundation, Inc.,
# 59 Temple Place,
# Suite 330,
# Boston, MA 02111-1307  USA
#
# $Id: listing.php,v 1.4 2005/05/10 18:32:14 filetreefrog Exp $
##################################################

class listing {
	function form($object) {
		if (!defined('SYS_FORMS')) require_once(BASE.'subsystems/forms.php');
		exponent_forms_initialize();

		$form = new form();
		if (!isset($object->id)) {
			$object->name = '';
			$object->url = 'http://';
			$object->opennew = 0;
			$object->summary = '';
			$object->body = '';
		} else {
			$form->meta('id',$object->id);
		}

		$form->register('name','Name',new textcontrol($object->name,50,false,200));
		$form->register('url','URL',new textcontrol($object->url));
		$form->register('opennew','Open in New Window',new checkboxcontrol($object->opennew,false));
		$form->register('summary','Summary',new texteditorcontrol($object->summary));
		$form->register('body','Body',new htmleditorcontrol($object->body));
		$form->register('upload','Upload Picture', new uploadcontrol());
		$form->register('submit','',new buttongroupcontrol('Save','','Cancel'));
		return $form;
	}

	function update($values,$object) {
		$object->name = $values['name'];
		$object->url = htmlentities(stripslashes($values['url']),ENT_QUOTES,LANG_CHARSET);
		//$object->url = $values['url'];
		$object->opennew = (isset($values['opennew']) ? 1 : 0);
		if (!exponent_core_URLisValid($object->url)) {
			$object->url = 'http://'.$object->url;
		}
		// Get rid of default values
		if ( (substr($object->url,0,7) == 'http://') &&
		    (strlen($object->url) == 7) ) {
			$object->url = '';
		}
		$object->summary = $values['summary'];
		$object->body = $values['body'];
		return $object;
	}
}

?>