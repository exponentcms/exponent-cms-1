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
# License, or (at your option) any later version.
#
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
# $Id: slideshowmodule_config.php,v 1.4 2005/04/25 19:02:17 filetreefrog Exp $
##################################################

class slideshowmodule_config {
	function form($object) {
		if (!defined('SYS_FORMS')) require_once(BASE.'subsystems/forms.php');
		exponent_forms_initialize();
		
		$form = new form();
		if (!isset($object->id)) {
			$object->delay = 1000;
			$object->random = 0;
			$object->img_width = 0;
			$object->img_height = 0;
		} else {
			$form->meta('id',$object->id);
		}
		
		$form->register('delay','Slide Delay',new textcontrol(round($object->delay/1000,2)));
		$form->register('random','Show in random order',new checkboxcontrol($object->random,true));
		$form->register('img_width','Image Width', new textcontrol($object->img_width));
		$form->register('img_height','Image Height', new textcontrol($object->img_height));
		$form->register('submit','',new buttongroupcontrol('Save','','Cancel'));
		return $form;
	}
	
	function update($values,$object) {
		$object->delay = $values['delay'] * 1000;
		if ($object->delay < 200) $object->delay = 200;
		$object->random = isset($values['random']);
		$object->img_width = $values['img_width'];
		$object->img_height = $values['img_height'];
		return $object;
	}
}

?>