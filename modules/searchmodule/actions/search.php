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
	if (!defined("SYS_SEARCH")) include_once(BASE."subsystems/search.php");
	$search_string = trim(strtolower($_POST['search_string']));
	
	if ($search_string == "") {
		echo "You must specify at least one search keyword.";
		return;
	}
	
	$terms = pathos_search_cleanSearchQuery(
		array_map("addslashes",array_map("trim",split(" ",$search_string)))
	);
	
	$results = array();
	
	foreach ($db->selectObjects("search",pathos_search_whereClause(array("title","body"),$terms,SEARCH_TYPE_ANY)) as $r) {
		$result = null;
		
		$rloc = unserialize($r->location_data);
		$sectionref = $db->selectObject("sectionref","module='".$rloc->mod."' AND source='".$rloc->src."'");
		$section = $db->selectObject("section","id=".$sectionref->section);
		//if ($section && ($section->public == 1 || pathos_permissions_check("view",pathos_core_makeLocation("navigationmodule","",$section->id)))) {
			
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
					$result->view_link = $r->view_link;
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
					$lastpos = stripos($r->body,$_POST['search_string'],$lastpos);
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
				$results[] = $result;
			}
/*		} else {
			echo "Permission check failed<br />";
			echo "<xmp>";
			print_r($sectionref);
			print_r($section);
			echo "</xmp>";
		}
*/	}
	
	echo "Your search for '$search_string' returned " . count($results) . " result" . (count($results) == 1 ? "" : "s") . "<br />";
	
	foreach ($results as $r) {
		echo "<hr size='1' />";
		echo "<a href='".$r->view_link."'>".$r->title."</a>";
		echo "<br />".$r->sum."<br />";
	}
// END PERM CHECK

?>