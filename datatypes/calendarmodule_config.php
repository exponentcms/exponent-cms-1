<?php

##################################################
# __XYZZY_BOILER
# $Id$
##################################################

class calendarmodule_config {
	function form($object) {
		if (!defined("SYS_FORMS")) include_once(BASE."subsystems/forms.php");
		pathos_forms_initialize();
		
		$form = new form();
		if (!isset($object->id)) {
			$object->enable_categories = 0;
			$object->enable_feedback = 0;
		} else {
			$form->meta("id",$object->id);
		}
		
		$form->register("enable_categories","Enable Categories?",new checkboxcontrol($object->enable_categories,true));
		$form->register("enable_feedback","Enable Feedback?",new checkboxcontrol($object->enable_feedback,true));				
		$form->register("submit","",new buttongroupcontrol("Save","","Cancel"));
		
		pathos_forms_cleanup();
		return $form;
	}
	
	function update($values,$object) {
		$object->enable_categories = isset($values['enable_categories']);
		$object->enable_feedback = isset($values['enable_feedback']);
		return $object;
	}
}

?>