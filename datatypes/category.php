<?php

class category {
	function form($object) {
		pathos_lang_loadDictionary('modules','category');
		pathos_lang_loadDictionary('standard','core');
		
		if (!defined("SYS_FORMS")) include_once(BASE."subsystems/forms.php");
		pathos_forms_initialize();
		
		$form = new form();
		if (!isset($object->id)) {
			$object->name = "";
			$object->color = "#000000";
		} else {
			$form->meta("id",$object->id);
		}
		
		$form->register("name",TR_CATEGORY_NAME,new textcontrol($object->name));
		$form->register("color",TR_CATEGORY_COLOR,new textcontrol($object->color));
		$form->register("submit","",new buttongroupcontrol(TR_CORE_SAVE,"",TR_CORE_CANCEL));
		
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