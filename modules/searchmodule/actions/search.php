<?php

##################################################
#
# Copyright 2004 James Hunt and OIC Group, Inc.
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
# $Id$
##################################################
//GREP:HARDCODEDTEXT
//GREP:VIEWIFY
if (!defined("PATHOS")) exit("");

// PERM CHECK
	// First, check our module config
	$config = $db->selectObject('searchmodule_config',"location_data='".serialize($loc)."'");
	if ($config == null) {
		$config->is_categorized = 0;
	}

	if (!defined("SYS_SEARCH")) include_once(BASE."subsystems/search.php");
	$search_string = trim(strtolower($_REQUEST['search_string']));
	
	if ($search_string == "") {
		pathos_lang_loadDictionary('modules','searchmodule');
		echo TR_SEARCHMODULE_NEEDTERM;
		return;
	}
	
	$term_status = pathos_search_cleanSearchQuery(
		array_map("addslashes",array_map("trim",split(" ",$search_string)))
	);
	
	$terms = $term_status['valid'];
	
	$results = array();
	
	foreach ($db->selectObjects("search",pathos_search_whereClause(array("title","body"),$terms,SEARCH_TYPE_ANY)) as $r) {
		$result = null;
		
		$rloc = unserialize($r->location_data);
		$sectionref = $db->selectObject("sectionref","module='".$rloc->mod."' AND source='".$rloc->src."'");
		$section = $db->selectObject("section","id=".$sectionref->section);
		
		$canview = navigationmodule::canView($section);
		if ($canview && $r->view_perm != '') {
			// No point in checking the perm stuff if they cant even see the section
			$canview = false; // They need to have specific perms on the module.
			foreach (explode(',',$r->view_perm) as $p) {
				if (pathos_permissions_check($p,$rloc)) {
					$canview = true;
					break;
				}
			}
		}
		
		if ($canview) {
			$weight = 0;
			$body_l = strtolower($r->body);
			$title_l = strtolower($r->title);
			foreach ($terms as $term) {
				$weight += preg_match("/(\s+".$term."[\s\.,:;]+)/",$body_l);
				$weight += preg_match("/(\s+".$term."[\s\.,:;]+)/",$title_l);
			}
			
			if ($weight) {
				// find view link
				if ($r->view_link == "") {
					// No viewlink - go to the page
					$result->view_link = pathos_core_makeLink(
						array("section"=>$sectionref->section));
				} else {
					$result->view_link = URL_FULL.$r->view_link;
				}
				
				// find title
				if ($r->title == "") {
					// No title - use site title and section name
					$section = $db->selectObject("section","id=".$sectionref->section);
					$result->title = $section->name . " :: " . SITE_TITLE;
				} else $result->title = $r->title;
				
				$lastpos = 0;
				$first = 0;
				$last = 0;
				$halflen = 50;
				$lastfirst = -2*$halflen-1; // padding to be safe.
				$result->sum = "";
				while (strlen($result->sum) < 200) {
					$lastpos = stripos($r->body,$_REQUEST['search_string'],$lastpos);
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
				
				if ($config->is_categorized) {
					if (!isset($results[$r->category])) {
						$results[$r->category] = array();
					}
					$results[$r->category][] = $result;
				} else {
					$results[] = $result;
				}
			}
		}
	}
	
	$template = new template('searchmodule','_results');
	$template->assign('config',$config);
	$template->assign('good_terms',$terms);
	$template->assign('excluded_terms',$term_status['excluded']);
	$template->assign('have_excluded_terms',count($term_status['excluded']));
	$template->assign('num_results',count($results));
	$template->assign('results',$results);
	$template->output();
// END PERM CHECK

?>