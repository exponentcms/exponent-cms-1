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

class sharedcore_site {
	function form($object) {
		if (!defined("SYS_FORMS")) include_once(BASE."subsystems/forms.php");
		pathos_forms_initialize();
		
		$form = new form();
		if (!isset($object->id)) {
			$object->core_id = 0;
			$object->name = "";
			$object->path = "";
		} else {
			$form->meta("id",$object->id);
		}
		
		$codebases = array();
		global $db;
		foreach ($db->selectObjects("sharedcore_core") as $c) {
			$codebases[$c->id] = $c->name;
		}
		uasort($codebases,"strnatcmp");
		
		
		$form->register("core_id","Codebase", new dropdowncontrol($object->core_id,$codebases));
		$form->register("name","Site Name", new textcontrol($object->name));
		$t = new textcontrol($object->path);
		if (isset($object->id)) $t->disabled = true;
		$form->register("path","Path", $t);
		
		if (!isset($object->id)) {
			// Setup initial database config
			$form->register(null,"",new htmlcontrol("<hr size='1' /><b>Database Configuration</b>"));
			$form->register("db_engine","Database Backend",new dropdowncontrol(DB_ENGINE,pathos_database_backends()));
			$form->register("db_host","Server Address",new textcontrol(DB_HOST));
			$form->register("db_port","Server Port",new textcontrol(DB_PORT));
			$form->register("db_name","Database Name",new textcontrol(DB_NAME));
			$form->register("db_user","Username",new textcontrol(DB_USER));
			$form->register("db_pass","Password",new textcontrol());
			$form->register("db_table_prefix","Table Prefix",new textcontrol(DB_TABLE_PREFIX));
			$form->meta("_db_config",1);
		}
		
		$form->register("submit","",new buttongroupcontrol("Save","","Cancel"));
		
		pathos_forms_cleanup();
		return $form;
	}
	
	function linkForm($object) {
		if (!defined("SYS_FORMS")) include_once(BASE."subsystems/forms.php");
		pathos_forms_initialize();
		
		$form = new form();
		
		global $db;
		$linked_mods = array();
		$linked_mods_lock = array();
		$linked_themes = array();
		$linked_themes_lock = array();
		if (isset($object->id)) {
			foreach ($db->selectObjects("sharedcore_extension","site_id=".$object->id) as $ext) {
				if ($ext->type == CORE_EXT_MODULE) {
					if ($ext->locked == 1) $linked_mods_lock[$ext->name] = $ext->name;
					$linked_mods[$ext->name] = $ext->name;
				} else {
					if ($ext->locked == 1) $linked_themes_lock[$ext->name] = $ext->name;
					$linked_themes[$ext->name] = $ext->name;
				}
			}
			$form->meta("site_id",$object->id);
		}
		
		$form->register(uniqid(""),"",new htmlcontrol("<b>Modules</b>"));
		// Get a list of modules from the chosen core.
		$core = $db->selectObject("sharedcore_core","id=".$object->core_id);
		$dh = opendir($core->path."modules");
		while (($file = readdir($dh)) !== false) {
			if (substr($file,0,1) != "." && $file != "CVS") {
				if (!class_exists($file) && is_readable($core->path."modules/$file/class.php")) include_once($core->path."modules/$file/class.php");
				if (class_exists($file)) {
					$name = (class_exists($file) ? call_user_func(array($file,"name")) : $file);
					$cb = new checkboxcontrol(isset($linked_mods[$file]) ? 1 : 0);
					if (isset($linked_mods_lock[$file])) $cb->disabled = true;
					$form->register("mods[$file]",$name,$cb);
				} else {
					if (isset($linked_mods[$file])) $form->meta("mods[$file]",1);
				}
			}
		}
		
		$form->register(uniqid(""),"",new htmlcontrol("<b>Themes</b>"));
		$dh = opendir($core->path."themes");
		while (($file = readdir($dh)) !== false) {
			if (substr($file,0,1) != "." && $file != "CVS") {
				if (!class_exists($file) && is_readable($core->path."themes/$file/class.php")) include_once($core->path."themes/$file/class.php");
				$name = (class_exists($file) ? call_user_func(array($file,"name")) : $file);
				
				$cb = new checkboxcontrol(isset($linked_themes[$file]) ? 1 : 0);
				if (isset($linked_themes_lock[$file])) $cb->disabled = true;
				$form->register("themes[$file]",$name,$cb);
			}
		}
		$form->register("submit","",new buttongroupcontrol("Next"));
		return $form;
	}
	
	function update($values,$object) {
		if (isset($values['_db_config'])) {
			// Test configuration, and return NULL if it doesn't work.
			
			if (preg_match("/[^A-Za-z0-9]/",$values['db_table_prefix'])) {
				$post = $values;
				$post['_formError'] = "Invalid table prefix.  The table prefix can only contain alphanumeric characters and underscores ('_').<br />";
				pathos_sessions_set("last_POST",$post);
				return null;
			}
			
			$linkdb = pathos_database_connect($values['db_user'],$values['db_pass'],$values['db_host'].":".$values['db_port'],$values['db_name'],$values['db_engine'],true);
			$linkdb->prefix = $values['db_table_prefix']."_";
			
			if (!$linkdb->isValid()) {
				$post = $values;
				$post['_formError'] = "Unable to connect to database server.  Make sure that the database specified exists, and the user account specified has access to the server.<br />";
				pathos_sessions_set("last_POST",$post);
				return null;
			}
			
			$status = $linkdb->testPrivileges();
			$failed = false;
			$errors = "";
			foreach ($status as $type=>$flag) {
				if (!$flag) {
					$failed = true;
					$errors .= "Unable to run $type commands<br />";
				}
			}
			if ($failed) {
				$post = $values;
				$post['_formError'] = $errors;
				pathos_sessions_set("last_POST",$post);
				return null;
			}
		}
	
		$object->name = $values['name'];
		$object->path = $values['path'];
		$object->core_id = $values['core_id'];
		return $object;
	}
}

?>