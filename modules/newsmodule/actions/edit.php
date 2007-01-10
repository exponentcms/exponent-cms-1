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

if (!defined("EXPONENT")) exit("");

$i18n = exponent_lang_loadFile('modules/newsmodule/actions/edit.php');

$news = null;
$iloc = null;
if (isset($_GET['id'])) {
	$news = $db->selectObject("newsitem","id=" . intval($_GET['id']));
}

if ($news != null) {
	$loc = unserialize($news->location_data);
	$iloc = $loc;
	$iloc->int = $news->id;
}

if (($news != null && exponent_permissions_check("edit_item",$loc)) || 
	($news == null && exponent_permissions_check("add_item",$loc)) ||
	($iloc != null   && exponent_permissions_check("edit_item",$iloc)) 
) {
	$form = newsitem::form($news);
	$form->location($loc);
	$form->meta("action","save");

	//Get the tags collections assigned to this module and then get all tags from those collections
	//to populate the tag listbuilder control.
	$newsmodule_config = $db->selectObject('newsmodule_config', "location_data='".serialize($loc)."'");
	//$newsmodule_config = $db->selectObject('newsmodule_config', "location_data='".$news->location_data."'");
	if (isset($newsmodule_config->enable_tags)) {
		$cols = array();
		$tags = array();
		$cols = unserialize($newsmodule_config->collections);
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
			if (isset($news->id)) {
				$tag_ids = unserialize($news->tags);
				if (is_array($tag_ids)) {  //If it's not an array, we don't have any tags.
					$selected_tags = $db->selectObjectsInArray('tags', $tag_ids, 'name');
					foreach ($selected_tags as $selected_tag) {
						$used_tags[$selected_tag->id] = $selected_tag->name;
					}
				}
			}

			if (count($tag_list) > 0) {
				$form->registerAfter('tag_header','tags',$i18n['tags'],new listbuildercontrol($used_tags,$tag_list));
			} else {
				$form->registerAfter('tag_header','tags', '',new htmlcontrol('<br /><div>There are no tags assigned to the collection(s) available to this module.</div>'));
			}
		} else {
			$form->registerAfter('tag_header','tags', '',new htmlcontrol('<br /><div>No tag collection have been assigned to this module</div>'));
		}

	}	
	$template = new template("newsmodule","_form_edit",$loc);
	$template->assign("form_html",$form->toHTML());
	$template->assign("is_edit", (isset($_GET['id']) ? 1 : 0) );
	$template->output();
} else {
	echo SITE_403_HTML;
}

?>
