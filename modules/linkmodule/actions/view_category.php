<?php

#############################################################
# LINKMODULE
#############################################################
# Copyright (c) 2006 Eric Lestrade 
#
# Exponent is free software; you can redistribute
# it and/or modify it under the terms of the GNU
# General Public License as published by the Free
# Software Foundation; either version 2 of the
# License, or (at your option) any later version.
#
# GPL: http://www.gnu.org/licenses/gpl.txt
#
##############################################################

if (!defined("EXPONENT")) exit("");
$category_id=intval($_GET['id']);
$category = $db->selectObject("category","id=" . $category_id);

if ($category != null) {

   $view = (isset($_GET['view']) ? $_GET['view'] : "_view_category");
   $template = new template("linkmodule",$view,$loc);

   global $db;
   $config = $db->selectObject('linkmodule_config',"location_data='".$category->location_data."'");
   if(!$config) {
		$config->enable_categories = 0;
		$config->orderby = 'name';
		$config->orderhow = 0; // Ascending
		$config->open_in_a_new_window=1;
		$config->enable_rss = false;
		$config->enable_rss_categories = false;
   }
   if($config->open_in_a_new_window==1)
      $template->assign("target","_blank");
   else
      $template->assign("target","_self");

	switch ($config->orderhow) {
	// Four options, ascending, descending, by user selected rank, and random
	case 0:
		//usort($listings,'exponent_sorting_byNameAscending');
		$sortFunc = 'exponent_sorting_byNameAscending';
		break;
	case 1:
		//usort($listings,'exponent_sorting_byNameDescending');
		$sortFunc = 'exponent_sorting_byNameDescending';
		break;
	case 2:
		//sort the listings by their rank
		//usort($listings, 'exponent_sorting_byRankAscending');
		$sortFunc = 'exponent_sorting_byRankAscending';
		break;
	case 3:
		//shuffle($listings);
		$sortFunc = '';
		break;
	} 

   if (!defined("SYS_SORTING")) require_once(BASE."subsystems/sorting.php");

   $template->assign('category', $category);
   
   $links = $db->selectObjects("link","location_data='".$category->location_data."' AND category_id=".$category_id);

//   usort($links, "exponent_sorting_byNameAscending");
   usort($links, $sortFunc);

	$template->assign('orderhow', $config->orderhow);
	$template->assign('enable_categories',$config->enable_categories);
   $template->assign('links',$links);
	$template->register_permissions(
		array('administrate','configure','add','edit','delete','import','manage_categories'),
		$loc);
	$title = $db->selectValue('container', 'title', "internal='".serialize($loc)."'");	
	$template->assign('moduletitle', $title);

	$template->assign('enable_rss', $config->enable_rss);
   $template->assign('enable_rss_categories', $config->enable_rss_categories AND $config->enable_rss);
      
   $template->output();

} else {
	echo SITE_404_HTML;
}

?>