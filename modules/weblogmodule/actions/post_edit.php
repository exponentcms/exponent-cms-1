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

if (!defined('EXPONENT')) exit('');

$post = null;
$iloc = null;

$i18n = exponent_lang_loadFile('modules/weblogmodule/actions/post_edit.php');

if (isset($_GET['id'])) {
	$post = $db->selectObject('weblog_post','id='.intval($_GET['id']));
	$loc = unserialize($post->location_data);
	$iloc = exponent_core_makeLocation($loc->mod,$loc->src,$post->id);
}

if (($post == null && exponent_permissions_check('post',$loc)) ||
	($post != null && exponent_permissions_check('edit',$loc)) ||
	($post != null && exponent_permissions_check('edit',$iloc))
) {
	$form = weblog_post::form($post);
	$form->location($loc);
	$form->meta('action','post_save');
	
$weblogmodule_config = $db->selectObject('weblogmodule_config', "location_data='".serialize($loc)."'");
	//$weblogmodule_config = $db->selectObject('weblogmodule_config', "location_data='".$post->location_data."'");
	if (isset($weblogmodule_config->enable_tags) && $weblogmodule_config->enable_tags = true) {
		$cols = array();
		$tags = array();
		$cols = unserialize($weblogmodule_config->collections);
		if (count($cols) > 0) {
			foreach ($cols as $col) {
				$available_tags = array();
				$available_tags = $db->selectObjects('tags', 'collection_id='.$col);
				$tags = array_merge($tags, $available_tags);
			}
				
			if (!defined('SYS_SORTING')) include_once(BASE.'subsystems/sorting.php');
			usort($tags, "exponent_sorting_byNameAscending");

			$tag_list = array();
			foreach ($tags as $tag) {
				$tag_list[$tag->id] = $tag->name;
			}

			$selected_tags = array();
			$used_tags = array();
			if (isset($post->id)) {
				$tag_ids = unserialize($post->tags);
				if (is_array($tag_ids) && count($tag_ids)>0) {  //If it's not an array, we don't have any tags.
					$selected_tags = $db->selectObjectsInArray('tags', $tag_ids, 'name');
					foreach ($selected_tags as $selected_tag) {
						$used_tags[$selected_tag->id] = $selected_tag->name;
					}
				}
			}
			if (count($tag_list) > 0) {
				$form->registerAfter('tag_header','tags',$i18n['tags'],new listbuildercontrol($used_tags, $tag_list));
			} else {
				$form->registerAfter('tag_header','tags', '',new htmlcontrol('<br /><div>There are no tags assigned to the collection(s) available to this module.</div>'));
			}
		} else {
			$form->registerAfter('tag_header','tags', '',new htmlcontrol('<br /><div>No tag collection have been assigned to this module</div>'));
		}

	}

	$template = new template('weblogmodule','_form_postEdit',$loc);
	$template->assign('form_html',$form->toHTML());
	$template->assign('is_edit', (isset($_GET['id']) ? 1 : 0) );
	$template->output();
} else {
	echo SITE_403_HTML;
}

?>