<?php

class category {
	function form($object) {
		if (!defined("SYS_FORMS")) include_once(BASE."subsystems/forms.php");
		pathos_forms_initialize();
		
		$form = new form();
		if (!isset($object->id)) {
			$object->name = "";
			$object->color = "#000000";
		} else {
			$form->meta("id",$object->id);
		}
		
		$form->register("name","Name",new textcontrol($object->name));
		$form->register("color","Color",new textcontrol($object->color));
		$form->register("submit","",new buttongroupcontrol("Save","","Cancel"));
		
		pathos_forms_cleanup();
		return $form;
	}
	
	function update($values,$object) {
		$object->name = $values['name'];
		$object->color = $values['color'];
		return $object;
	}
}

?>