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

class container {
	function form($object,$modules_list = null) {
	}
	
	function update($values,$object,$loc) {
		global $db;
		
		if (!isset($values['id'])) {
			// Only deal with the inc/dec stuff if adding a module.
			$src = "";
			if ($values['i_src'] == "new_source") {
				$src = "@random".uniqid("");
				$object->is_existing = 0;
			} else {
				$src = $values[$values['i_src']];
				$object->is_existing = 1;
			}
			$newInternal = pathos_core_makeLocation($values['i_mod'],$src);
			
			// REFERENCES - Section and Location
			//$sect = $db->selectObject('section','id='.$_POST['current_section']);
			pathos_core_incrementLocationReference($newInternal,$_POST['current_section']);
			
			// Rank is only updateable from the order action
			$object->rank = $values['rank'];
			if (isset($values['rerank'])) $db->increment("container","rank",1,"external='".serialize($loc)."' AND rank >= " . $values['rank']);
			$object->internal = serialize($newInternal);
			$object->external = serialize($loc);
		}
			
		// UPDATE the container
		$object->view = $values['view'];
		$object->title = $values['title'];
		return $object;
	}
	
	function delete($object,$rerank = false) {
		if ($object == null) return false;
		
		$internal = unserialize($object->internal);
		
		global $db;
		$section = pathos_sessions_get("last_section");
		$locref = $db->selectObject("locationref","module='".$internal->mod."' AND source='".$internal->src."' AND internal='".$internal->int."'");
		$secref = $db->selectObject("sectionref", "module='".$internal->mod."' AND source='".$internal->src."' AND internal='".$internal->int."' AND section=$section");
		
		if ($locref) {
			$locref->refcount -= 1;
			$db->updateObject($locref,"locationref","module='".$internal->mod."' AND source='".$internal->src."' AND internal='".$internal->int."'");
		}
		
		if ($secref) {
			$secref->refcount -= 1;
			$db->updateObject($secref,"sectionref", "module='".$internal->mod."' AND source='".$internal->src."' AND internal='".$internal->int."' AND section=$section");
		}
		
		// Fix ranks
		if ($rerank) $db->decrement("container","rank",1,"external='".$object->external."' AND rank > " . $object->rank);
	}
}

?>