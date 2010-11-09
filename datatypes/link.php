<?php

class link {
	function form($object) {
   
      $i18n = exponent_lang_loadFile('datatypes/link.php');
   
		if (!defined("SYS_FORMS")) require_once(BASE."subsystems/forms.php");
		exponent_forms_initialize();
		
		$form = new form();
		if (!isset($object->id)) {
			$object->name = "";
			$object->url = "http://";
			$object->description = "";
			$object->opennew = 0;
		} else {
			$form->meta("id",$object->id);
		}
		
		$form->register("name",$i18n['title'],new textcontrol($object->name,80,false,200));
		$form->register("url","URL",new textcontrol($object->url,80,false,200));
		$form->register('opennew',$i18n['opennew'],new checkboxcontrol($object->opennew,true));
		$form->register("description",$i18n['description'],new texteditorcontrol($object->description,5,60));
		$form->register("submit","",new buttongroupcontrol($i18n['save'],'',$i18n['cancel']));
		
		exponent_forms_cleanup();
		return $form;
	}
	
	function update($values,$object) {
		$object->name = $values['name'];
		$object->url = $values['url'];
		$object->opennew = (isset($values['opennew']) ? 1 : 0);
		$object->description = $values['description'];
		
		return $object;
	}
}

?>