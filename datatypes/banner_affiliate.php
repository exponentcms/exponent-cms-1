<?php

##################################################
# __XYZZY_BOILER
# $Id$
##################################################

class banner_affiliate {
	function form($object) {
		if (!defined('SYS_FORMS')) include_once(BASE.'subsystems/forms.php');
		pathos_forms_initialize();
		
		$form = new form();
		if (!isset($object->id)) {
			$object->name = '';
			$object->contact_info = '';
		} else {
			$form->meta('id',$object->id);
		}
		
		pathos_lang_loadDictionary('standard','core');
		pathos_lang_loadDictionary('modules','bannermodule');
		
		$form->register('name',TR_BANNERMODULE_AFFILIATENAME, new textcontrol($object->name));
		$form->register('contact_info',TR_BANNERMODULE_AFFILIATE, new texteditorcontrol($object->contact_info,12,50));
		$form->register('submit','', new buttongroupcontrol(TR_CORE_SAVE,'',TR_CORE_CANCEL));
		
		pathos_forms_cleanup();
		return $form;
	}
	
	function update($values,$object) {
		$object->name = $values['name'];
		$object->contact_info = $values['contact_info'];
		return $object;
	}
}

?>