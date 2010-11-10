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

class newsitem {
	function form($object) {
		$i18n = exponent_lang_loadFile('datatypes/newsitem.php');

		global $user;

		if (!defined('SYS_FORMS')) require_once(BASE.'subsystems/forms.php');
		exponent_forms_initialize();

		$form = new form();
		if (!isset($object->id)) {
			$object->title = '';
			$object->internal_name = '';
			$object->body = '';
			$object->publish = null;
			$object->unpublish = null;
			$object->is_featured = 0;
		} else {
			$form->meta('id',$object->id);
			if ($object->publish == 0) $object->publish = null;
			if ($object->unpublish == 0) $object->unpublish = null;
		}

		//$form->register(null,'',new htmlcontrol('<br /><div class="moduletitle">'.$i18n['news_content'].'</div><hr size="1" />'));
		$form->register('title',$i18n['title'],new textcontrol($object->title));
		$form->register('body',$i18n['body'],new htmleditorcontrol($object->body));

		$form->register(null,'',new htmlcontrol('<h3>'.$i18n['publish_information'].'</h3><hr size="1" />'));
		//$form->register('publish',$i18n['publish'],new popupdatetimecontrol($object->publish,$i18n['nopublish']));
		$checked = empty($object->publish) ? true : false;
		$form->register('publish',$i18n['publish'],new yuidatetimecontrol($object->publish,$i18n['nopublish'], true, true, false, $checked));
		//$form->register('unpublish',$i18n['unpublish'],new popupdatetimecontrol($object->unpublish,$i18n['nounpublish']));
		$checked = empty($object->unpublish) ? true : false;
		$form->register('unpublish',$i18n['unpublish'],new yuidatetimecontrol($object->unpublish,$i18n['nounpublish'], true, true, false, $checked));

		$form->register('featured_header','',new htmlcontrol('<h3>'.$i18n['featured_info'].'</h3><hr size="1" />'));
		$form->register('is_featured',$i18n['feature'],new checkboxcontrol($object->is_featured,true));

		$form->register('image_header','',new htmlcontrol('<h3>'.$i18n['upload_file'].'</h3><hr size="1" />'));
		$form->register('file',$i18n['upload_image'],new uploadcontrol());

		$form->register('tag_header','',new htmlcontrol('<h3>'.$i18n['tags'].'</h3><hr size="1" />'));

		$form->register('submit','',new buttongroupcontrol($i18n['save'],'',$i18n['cancel']));

		return $form;
	}

	function update($values,$object) {
		if (!defined('SYS_FORMS')) require_once(BASE.'subsystems/forms.php');
		exponent_forms_initialize();

		$object->title = $values['title'];
		// Comment out this next one - there's no place in the form to fill it in.  No idea what the intention is.  Maia - 2/5/09
		//$object->internal_name = preg_replace('/--+/','-',preg_replace('/[^A-Za-z0-9_]/','-',$values['int']));
		$object->body = $values['body'];
		//$object->publish = popupdatetimecontrol::parseData('publish',$values);
		$object->publish = yuidatetimecontrol::parseData('publish',$values);
		//$object->unpublish = popupdatetimecontrol::parseData('unpublish',$values);
		$object->unpublish = yuidatetimecontrol::parseData('unpublish',$values);
		$object->is_featured = (isset($values['is_featured']) ? 1 : 0);

		return $object;
	}
}

?>
