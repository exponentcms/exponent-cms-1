<?php

##################################################
#
# Copyright (c) 2004-2005 James Hunt and the OIC Group, Inc.
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
		pathos_lang_loadDictionary('standard','core');
		pathos_lang_loadDictionary('modules','sharedcoremodule');
	
		if (!defined('SYS_FORMS')) include_once(BASE.'subsystems/forms.php');
		pathos_forms_initialize();
		
		$form = new form();
		if (!isset($object->id)) {
			$object->core_id = 0;
			$object->name = '';
			$object->path = '';
			$object->relpath = '';
		} else {
			$form->meta('id',$object->id);
		}
		
		$codebases = array();
		global $db;
		foreach ($db->selectObjects('sharedcore_core') as $c) {
			$codebases[$c->id] = $c->name;
		}
		uasort($codebases,'strnatcmp');
		
		
		$form->register('core_id',TR_SHAREDCOREMODULE_SITECORE, new dropdowncontrol($object->core_id,$codebases));
		$form->register('name',TR_SHAREDCOREMODULE_SITENAME, new textcontrol($object->name));
		$t = new textcontrol($object->path);
		if (isset($object->id)) $t->disabled = true;
		$form->register('path',TR_SHAREDCOREMODULE_SITEPATH, $t);
		$t->default = $object->relpath;
		$form->register('relpath',TR_SHAREDCOREMODULE_SITEPATH, $t);
		
		if (!isset($object->id)) {
			pathos_lang_loadDictionary('config','database');
			// Setup initial database config
			$form->register(null,'',new htmlcontrol('<hr size="1" /><b>'.TR_CONFIG_DATABASE_TITLE.'</b>'));
			$form->register('db_engine',TR_CONFIG_DATABASE_DB_ENGINE,new dropdowncontrol(DB_ENGINE,pathos_database_backends()));
			$form->register('db_host',TR_CONFIG_DATABASE_DB_HOST,new textcontrol(DB_HOST));
			$form->register('db_port',TR_CONFIG_DATABASE_DB_PORT,new textcontrol(DB_PORT));
			$form->register('db_name',TR_CONFIG_DATABASE_DB_NAME,new textcontrol(DB_NAME));
			$form->register('db_user',TR_CONFIG_DATABASE_DB_USER,new textcontrol(DB_USER));
			$form->register('db_pass',TR_CONFIG_DATABASE_DB_PASS,new textcontrol());
			$form->register('db_table_prefix',TR_CONFIG_DATABASE_DB_TABLE_PREFIX,new textcontrol(DB_TABLE_PREFIX));
			$form->meta('_db_config',1);
		}
		
		$form->register('submit','',new buttongroupcontrol(TR_CORE_SAVE,'',TR_CORE_CANCEL));
		
		pathos_forms_cleanup();
		return $form;
	}
	
	function linkForm($object) {
		pathos_lang_loadDictionary('standard','core');
		pathos_lang_loadDictionary('modules','sharedcoremodule');
	
		if (!defined('SYS_FORMS')) include_once(BASE.'subsystems/forms.php');
		pathos_forms_initialize();
		
		$form = new form();
		
		global $db;
		$linked_mods = array();
		$linked_mods_lock = array();
		$linked_themes = array();
		$linked_themes_lock = array();
		if (isset($object->id)) {
			foreach ($db->selectObjects('sharedcore_extension','site_id='.$object->id) as $ext) {
				if ($ext->type == CORE_EXT_MODULE) {
					if ($ext->locked == 1) $linked_mods_lock[$ext->name] = $ext->name;
					$linked_mods[$ext->name] = $ext->name;
				} else {
					if ($ext->locked == 1) $linked_themes_lock[$ext->name] = $ext->name;
					$linked_themes[$ext->name] = $ext->name;
				}
			}
			$form->meta('site_id',$object->id);
		}
		
		$form->register(uniqid(''),'',new htmlcontrol('<b>'.TR_SHAREDCOREMODULE_MODULES.'</b>'));
		// Get a list of modules from the chosen core.
		$core = $db->selectObject('sharedcore_core','id='.$object->core_id);
		$dh = opendir($core->path.'modules');
		while (($file = readdir($dh)) !== false) {
			if (substr($file,0,1) != '.' && $file != 'CVS') {
				if (!class_exists($file) && is_readable($core->path."modules/$file/class.php")) include_once($core->path.'modules/$file/class.php');
				if (class_exists($file)) {
					$name = (class_exists($file) ? call_user_func(array($file,'name')) : $file);
					$cb = new checkboxcontrol(isset($linked_mods[$file]) ? 1 : 0);
					if (isset($linked_mods_lock[$file])) $cb->disabled = true;
					$form->register("mods[$file]",$name,$cb);
				} else {
					if (isset($linked_mods[$file]) && !isset($linked_mods_loc[$file])) $form->meta("mods[$file]",1);
				}
			}
		}
		
		$form->register(uniqid(''),'',new htmlcontrol('<b>'.TR_SHAREDCOREMODULE_THEMES.'</b>'));
		$dh = opendir($core->path.'themes');
		while (($file = readdir($dh)) !== false) {
			if (substr($file,0,1) != '.' && $file != 'CVS') {
				if (!class_exists($file) && is_readable($core->path.'themes/'.$file.'/class.php')) include_once($core->path.'themes/'.$file.'/class.php');
				$name = (class_exists($file) ? call_user_func(array($file,'name')) : $file);
				
				$cb = new checkboxcontrol(isset($linked_themes[$file]) ? 1 : 0);
				if (isset($linked_themes_lock[$file])) $cb->disabled = true;
				$form->register("themes[$file]",$name,$cb);
			}
		}
		$form->register('submit','',new buttongroupcontrol(TR_CORE_NEXT));
		return $form;
	}
	
	function update($values,$object) {
		if (isset($values['_db_config'])) {
			pathos_lang_loadDictionary('config','database');
		
			// Test configuration, and return NULL if it doesn't work.
			
			if (preg_match('/[^A-Za-z0-9]/',$values['db_table_prefix'])) {
				$post = $values;
				$post['_formError'] = TR_CONFIG_DATABASE_ERROR_BADPREFIX.'<br />';
				pathos_sessions_set('last_POST',$post);
				return null;
			}
			
			$linkdb = pathos_database_connect($values['db_user'],$values['db_pass'],$values['db_host'].':'.$values['db_port'],$values['db_name'],$values['db_engine'],true);
			$linkdb->prefix = $values['db_table_prefix'].'_';
			
			if (!$linkdb->isValid()) {
				$post = $values;
				$post['_formError'] = TR_CONFIG_DATABASE_ERROR_CANTCONNECT.'<br />';
				pathos_sessions_set('last_POST',$post);
				return null;
			}
			
			$status = $linkdb->testPrivileges();
			$failed = false;
			$errors = '';
			foreach ($status as $type=>$flag) {
				if (!$flag) {
					$failed = true;
					$errors .= sprintf(TR_CONFIG_DATABASE_ERROR_PERMDENIED,$type).'<br />';
				}
			}
			if ($failed) {
				$post = $values;
				$post['_formError'] = $errors;
				pathos_sessions_set('last_POST',$post);
				return null;
			}
		}
	
		$object->name = $values['name'];
		$object->core_id = $values['core_id'];
		
		if (!isset($object->id)) {
			$object->path = $values['path'];
			if ($object->path{0} != '/') {
				$object->path = '/'.$object->path;
			}
			if (substr($object->path,-1,1) != '/') {
				$object->path = $object->path.'/';
			}
			
			$object->relpath = $values['relpath'];
			if ($object->relpath{0} != '/') {
				$object->relpath = '/'.$object->relpath;
			}
			if (substr($object->relpath,-1,1) != '/') {
				$object->relpath = $object->relpath.'/';
			}
			
		}
		return $object;
	}
}

?>