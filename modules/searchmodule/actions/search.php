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

//GREP:VIEWIFY
if (!defined("EXPONENT")) exit("");
if (!defined("SYS_SEARCH")) include_once(BASE."subsystems/search.php");

// First, check our module config
$config = $db->selectObject('searchmodule_config',"location_data='".serialize($loc)."'");
if ($config == null) {
	$config->is_categorized = 0;
}

//Double check which modules we are supposed to search
if (isset($_REQUEST['modules'])) {
	$modules = $_REQUEST['modules'];
} elseif (!isset($config->modules) && !isset($_REQUEST['modules'])) {
	//$modules = array();
	$modules=array_keys(getModuleNames(null));
} else {
	$modules = unserialize($config->modules);
}

$search_string = trim(strtolower(strip_tags($_GET['search_string'])));

if ($search_string == "") {
	echo exponent_lang_loadKey('modules/searchmodule/actions/search.php','need_term');
	return;
}

$term_status = exponent_search_cleanSearchQuery(
	array_map("addslashes",array_map("trim",split(" ",$search_string)))
);

$terms = $term_status['valid'];

$results = array();

//foreach ($db->selectObjects("search",exponent_search_whereClause(array("title","body"),$terms,$modules, SEARCH_TYPE_ANY)) as $r) {
foreach ($db->selectSearch($search_string) as $r) {
	$result = null;

	if ($r->ref_type != 'section') {	
		$rloc = unserialize($r->location_data);
		$sectionref = $db->selectObject("sectionref","module='".$rloc->mod."' AND source='".$rloc->src."'");
		$section = $db->selectObject("section","id=".$sectionref->section);
	} else {
		$section = $db->selectObject('section', 'id='.$r->original_id);
	}
	
	$canview = navigationmodule::canView($section);
	if ($canview && $r->view_perm != '') {
		// No point in checking the perm stuff if they cant even see the section
		$canview = false; // They need to have specific perms on the module.
		foreach (explode(',',$r->view_perm) as $p) {
			if (exponent_permissions_check($p,$rloc)) {
				$canview = true;
				break;
			}
		}
	}
	
	if ($canview) {
		//$weight = 0;
		$body_l = strtolower($r->body);
		$title_l = strtolower($r->title);
		//foreach ($terms as $term) {
		//	$weight += preg_match("/(\s+".$term."[\s\.,:;]+)/",$body_l);
		//	$weight += preg_match("/(\s+".$term."[\s\.,:;]+)/",$title_l);
		//}
		
		//if ($weight) {
			// find view link
		if ($r->category != 'Webpages') {
            		if ($r->view_link == "") {
				// No viewlink - go to the page
				$result->view_link = exponent_core_makeLink(array("section"=>$sectionref->section));
			} else {
				$result->view_link = URL_FULL.$r->view_link;
			}
			
			// find title
			if ($r->title == "") {
				// No title - use site title and section name
				$section = $db->selectObject("section","id=".$sectionref->section);
				$result->title = $section->name . " :: " . SITE_TITLE;
			} else $result->title = $r->title;
		} else {
			global $router;
			$result->view_link = $router->makeLink(array('section'=>$r->original_id));
			$result->title = $r->title;
		}
			
			$lastpos = 0;
			$first = 0;
			$last = 0;
			$halflen = 50;
			$lastfirst = -2*$halflen-1; // padding to be safe.
			$result->sum = "";
			while (strlen($result->sum) < 200) {
				$lastpos = stripos($r->body,$_GET['search_string'],$lastpos);
				if ($lastpos === false) break;
				$first = $lastpos - $halflen;
				if ($first < 0) $first = 0;
				
				if ($first < $lastfirst + ($halflen*2)) { // inside the bounds of last chunk
					$first = $lastfirst + ($halflen*2);
				} else if ($first != 0) $result->sum .= " ... ";
				
				while ($first > 0 && substr($r->body,$first,1) != " ") $first--;
				$last = $first + (2*$halflen);
				if ($last > strlen($r->body)) $last = strlen($r->body);
				else while ($last > $first && substr($r->body,$last,1) != " ") $last--;
			
				$result->sum .= substr($r->body,$first,$last - $first);
				
				$lastpos++;
				$lastfirst = $first;
			}
			
			if ($last == $first && $first == 0) {
				$last = 300;
				if ($last > strlen($r->body)) $last = strlen($r->body);
				else while ($last > $first && substr($r->body,$last,1) != " ") $last--;
				
				$result->sum = substr($r->body,$first,$last);
			}
			if ($last < strlen($r->body)) $result->sum .= " ...";
			
			$p_term_search  = array_map(create_function('$a','return "/([\s.,:;]{1}".$a."[\s.,:;$]{1})/i";'),$terms);
			$result->sum = preg_replace($p_term_search,"<b>\$1</b> ",$result->sum);
		
			$result->sum = str_replace("\n","<br />",$result->sum);
			
			if ($config->is_categorized == 1) {
				if (!isset($results[$r->category])) {
					$results[$r->category] = array();
				}
				$results[$r->category][] = $result;
			} else {
				$results[] = $result;
			}
		//}
	}
}

$template = new template('searchmodule','_results');
$template->assign('config',$config);
$template->assign('query',join(' ',$terms));
$template->assign('good_terms',$terms);
$template->assign('excluded_terms',$term_status['excluded']);
$template->assign('have_excluded_terms',count($term_status['excluded']));
$template->assign('num_results',count($results, COUNT_RECURSIVE));
$template->assign('results',$results);
$template->output();

?>
