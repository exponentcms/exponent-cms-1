<?php

##################################################
#
# Copyright 2004 James Hunt and OIC Group, Inc.
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
# $Id$
##################################################

class newsitem {
	function form($object) {
		global $user;
	
		if (!defined("SYS_FORMS")) include_once(BASE."subsystems/forms.php");
		pathos_forms_initialize();
		
		$form = new form();
		if (!isset($object->id)) {
			$object->title = "";
			$object->body = "";
			$object->publish = null;
			$object->unpublish = null;
		} else {
			$form->meta("id",$object->id);
			if ($object->publish == 0) $object->publish = null;
			if ($object->unpublish == 0) $object->unpublish = null;
		}
		
		$form->register("title","Headline",new textcontrol($object->title));
		$form->register("body","Body",new htmleditorcontrol($object->body));
		if (DISPLAY_CACHE == true) {
			$form->register("cache_warning","",new htmlcontrol("<hr size='1'/><i><b>Note:</b> The administrator has enabled page caching.  If you choose to set an unpublish date for this news item, it may not unpublish at the exact time you specify (but it won't unpublish before).<hr size='1'/>"));
		}
		$form->register("publish","Publish on",new popupdatetimecontrol($object->publish,"Publish immediately"));
		$form->register("unpublish","Unpublish on",new popupdatetimecontrol($object->unpublish,"Don't unpublish"));
		$form->register("submit","",new buttongroupcontrol("Save","","Cancel"));
		
		pathos_forms_cleanup();
		return $form;
	}
	
	function update($values,$object) {
		if (!defined("SYS_FORMS")) include_once(BASE."subsystems/forms.php");
		pathos_forms_initialize();
		
		$object->title = $values['title'];
	//	$object->summary = $values['body']; // Do summary!
		$object->body = $values['body'];
		$object->publish = popupdatetimecontrol::parseData("publish",$values);
		$object->unpublish = popupdatetimecontrol::parseData("unpublish",$values);
		
		pathos_forms_cleanup();
		return $object;
	}
}

?>