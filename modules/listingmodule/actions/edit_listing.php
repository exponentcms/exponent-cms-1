<?php

##################################################
#
# Copyright (c) 2004-2005 OIC Group, Inc.
#
# This file is part of Exponent
#
# Exponent is free software; you can redistribute
# it and/or modify it under the terms of the GNU
# General Public License as published by the Free
# Software Foundation; either version 2 of the
# License, or (at your option) any later version.
#
# Exponent is distributed in the hope that it
# will be useful, but WITHOUT ANY WARRANTY;
# without even the implied warranty of
# MERCHANTABILITY or FITNESS FOR A PARTICULAR
# PURPOSE.  See the GNU General Public License
# for more details.
#
# You should have received a copy of the GNU
# General Public License along with Exponent; if
# not, write to:
#
# Free Software Foundation, Inc.,
# 59 Temple Place,
# Suite 330,
# Boston, MA 02111-1307  USA
#
# $Id: edit_listing.php,v 1.2 2005/02/19 16:53:35 filetreefrog Exp $
##################################################

if (!defined("EXPONENT")) exit("");
	$listing = null;
	if (isset($_GET['id'])) {
		$listing = $db->selectObject("listing","id=".$_GET['id']);
		if ($listing != null) {
			$loc = unserialize($listing->location_data);
		} 
	}
	
	if (exponent_permissions_check("manage",$loc)) {
		$i18n = exponent_lang_loadFile('modules/listingmodule/actions/edit_listing.php');
		$config = $db->selectObject('listingmodule_config',"location_data='".serialize($loc)."'");
		if ($config == null) {
			//do nothing here yes.  
		}
		$form = listing::form($listing);
		$form->location($loc);
		$form->meta("action","save_listing");
				
		$template = new template("listingmodule","_form_editlisting",$loc);
		if ($config->enable_categories) {
			$allcats = $db->selectObjects('category', "location_data='".serialize($loc)."'");
			if (!defined('SYS_SORTING')) require_once(BASE.'subsystems/sorting.php');
			usort($allcats, "exponent_sorting_byRankAscending");
			$catarray = array();
			$catarray[0] = $i18n['no_category'];
			foreach ($allcats as $cat) {
				$catarray[$cat->id] = $cat->name;
			}			
			$form->registerBefore('name', 'categories', 'Select Category', new dropdowncontrol($listing->category_id, $catarray));
		}		
		$template->assign("listing",$listing);
		$template->assign("is_edit",(isset($listing->id) ? 1 : 0));
		$template->assign("form_html",$form->toHTML());
		$template->output();
	} else {
		echo SITE_403_HTML;
	}

?>