<?php

class linkmodule_config {
	function form($object) {
      $i18n = exponent_lang_loadFile('datatypes/linkmodule_config.php');
      
		if (!defined('SYS_FORMS')) require_once(BASE.'subsystems/forms.php');
		exponent_forms_initialize();
		
		$form = new form();
		if (!isset($object->id)) {
			$object->enable_categories = 0;
			$object->open_in_a_new_window = 1;
			$object->enable_rss = false;
			$object->rss_categories = true;
			$object->rss_add_category_name = true;
			$object->enable_rss_categories = false;
			$object->feed_title = "";
			$object->feed_desc = "";
			$object->sort = 'asc_name';
		} else {
			switch ($object->orderhow) {
				case 0: // ascending
					$object->sort = 'asc_'.$object->orderby;
					break;
				case 1: // descending
					$object->sort = 'desc_'.$object->orderby;
					break;
				case 2: // order specified by arrows
					$object->sort = 'rank_';
					break;
				case 3: // random
					$object->sort = 'random_';
					break;
				default:
					$object->sort = 'asc_name';
					break;
			}
			$form->meta('id',$object->id);
		}
		
		$order_options = array(
			'asc_name'=>$i18n['asc_name'],
			'desc_name'=>$i18n['desc_name'],
			'rank_'=>$i18n['rank_'],
			'random_'=>$i18n['random_'],
		);

		$form->register(null,'',new htmlcontrol('<h3>'.$i18n['general_configuration'].'</h3><hr size="1" />'));
		$form->register('enable_categories',$i18n['enable_categories'],new checkboxcontrol($object->enable_categories,true));
		$form->register('orderby','Sorting',new dropdowncontrol($object->sort,$order_options));		
		$form->register('open_in_a_new_window',$i18n['open_in_a_new_window'],new checkboxcontrol($object->open_in_a_new_window));
		$form->register(null,'',new htmlcontrol('<h3>'.$i18n['rss_configuration'].'</h3><hr size="1" />'));
		$form->register('enable_rss',$i18n['enable_rss'], new checkboxcontrol($object->enable_rss));
		$form->register('feed_title',$i18n['feed_title'],new textcontrol($object->feed_title,35,false,75));
		$form->register('feed_desc',$i18n['feed_desc'],new texteditorcontrol($object->feed_desc));
		$form->register('rss_categories',$i18n['rss_categories'], new checkboxcontrol($object->rss_categories));
		$form->register('rss_add_category_name',$i18n['rss_add_category_name'], new checkboxcontrol($object->rss_add_category_name));
		$form->register('enable_rss_categories',$i18n['enable_rss_categories'], new checkboxcontrol($object->enable_rss_categories));

		$form->register('submit','',new buttongroupcontrol($i18n['save'],'',$i18n['cancel']));
		return $form;
	}
	
	function update($values,$object) {
		$object->enable_categories = empty($values['enable_categories']) ? 0 : 1;
		$toks = explode('_',$values['orderby']);
		switch ($toks[0]) {
			case 'asc':
				$object->orderhow = 0;
				break;
			case 'desc':
				$object->orderhow = 1;
				break;
			case 'rank':
				$object->orderhow = 2;
				break;
			case 'random':
				$object->orderhow = 3;
				break;
		}
		$object->orderby = $toks[1];	
		$object->open_in_a_new_window = isset($values['open_in_a_new_window']);
		$object->enable_rss = (isset($values['enable_rss']) ? 1 : 0);
		$object->rss_categories = (isset($values['rss_categories']) ? 1 : 0);
		$object->rss_add_category_name = (isset($values['rss_add_category_name']) ? 1 : 0);
		$object->enable_rss_categories = (isset($values['enable_rss_categories']) ? 1 : 0);
		$object->feed_title = $values['feed_title'];
		$object->feed_desc = $values['feed_desc'];
		return $object;
	}
}

?>