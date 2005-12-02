<?php

##################################################
#
# Copyright (c) 2004-2005 OIC Group, Inc.
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

class sharedcore_site {
	function form($object) {
		$i18n = pathos_lang_loadFile('datatypes/sharedcore_site.php');
	
		if (!defined('SYS_FORMS')) require_once(BASE.'subsystems/forms.php');
		pathos_forms_initialize();
		
		$form = new form();
		if (!isset($object->id)) {
			$object->core_id = 0;
			$object->name = '';
			$object->path = '';
			$object->relpath = '';
			$object->host = HOSTNAME;
		} else {
			$form->meta('id',$object->id);
		}
		
		$codebases = array();
		global $db;
		foreach ($db->selectObjects('sharedcore_core') as $c) {
			$codebases[$c->id] = $c->name;
		}
		uasort($codebases,'strnatcmp');
		
		
		$form->register('core_id',$i18n['core'], new dropdowncontrol($object->core_id,$codebases));
		$form->register('name',$i18n['name'], new textcontrol($object->name));
		
		$path_ctl = new textcontrol($object->path);
		if (isset($object->id)) $path_ctl->disabled = true;
		$form->register('path',$i18n['path'], $path_ctl);
		
		$host_ctl = new textcontrol($object->host);
		if (isset($object->id)) $host_ctl->disabled = true;
		$form->register('host',$i18n['host'], $host_ctl);
		
		$relpath_ctl = new textcontrol($object->relpath);
		if (isset($object->id)) $relpath_ctl->disabled = true;
		$form->register('relpath',$i18n['relpath'], $relpath_ctl);
		
		
		if (!isset($object->id)) {
			$local_i18n = pathos_lang_loadFile('conf/extensions/database.structure.php');
			// Setup initial database config
			$form->register(null,'',new htmlcontrol('<hr size="1" /><b>'.$local_i18n['title'].'</b>'));
			$form->register('db_engine',$local_i18n['db_engine'],new dropdowncontrol(DB_ENGINE,pathos_database_backends()));
			$form->register('db_host',$local_i18n['db_host'],new textcontrol(DB_HOST));
			$form->register('db_port',$local_i18n['db_port'],new textcontrol(DB_PORT));
			$form->register('db_name',$local_i18n['db_name'],new textcontrol(DB_NAME));
			$form->register('db_user',$local_i18n['db_user'],new textcontrol(DB_USER));
			$form->register('db_pass',$local_i18n['db_pass'],new textcontrol());
			$form->register('db_table_prefix',$local_i18n['db_table_prefix'],new textcontrol(DB_TABLE_PREFIX));
			$form->meta('_db_config',1);
		}
		
		$form->register('submit','',new buttongroupcontrol($i18n['save'],'',$i18n['cancel']));
		
		return $form;
	}
	
	function linkForm($object) {
		$i18n = pathos_lang_loadFile('datatypes/sharedcore_site.php');
	
		if (!defined('SYS_FORMS')) require_once(BASE.'subsystems/forms.php');
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
		
		$form->register(uniqid(''),'',new htmlcontrol('<b>'.$i18n['modules'].'</b>'));
		// Get a list of modules from the chosen core.
		$core = $db->selectObject('sharedcore_core','id='.$object->core_id);
		$dh = opendir($core->path.'modules');
		while (($file = readdir($dh)) !== false) {
			if (substr($file,0,1) != '.' && $file != 'CVS') {
				if (!class_exists($file) && is_readable($core->path."modules/$file/class.php")) include_once($core->path."modules/$file/class.php");
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
		
		$form->register(uniqid(''),'',new htmlcontrol('<b>'.$i18n['themes'].'</b>'));
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
		$form->register('submit','',new buttongroupcontrol($i18n['next']));
		return $form;
	}
	
	function update($values,$object) {
		if (isset($values['_db_config'])) {
			$i18n = pathos_lang_loadFile('datatypes/sharedcore_site.php');
		
			// Test configuration, and return NULL if it doesn't work.
			
			if (preg_match('/[^A-Za-z0-9]/',$values['db_table_prefix'])) {
				$post = $values;
				$post['_formError'] = $i18n['bad_prefix'].'<br />';
				pathos_sessions_set('last_POST',$post);
				return null;
			}
			
			$linkdb = pathos_database_connect($values['db_user'],$values['db_pass'],$values['db_host'].':'.$values['db_port'],$values['db_name'],$values['db_engine'],true);
			$linkdb->prefix = $values['db_table_prefix'].'_';
			
			if (!$linkdb->isValid()) {
				$post = $values;
				$post['_formError'] = $i18n['cant_connect'].'<br />';
				pathos_sessions_set('last_POST',$post);
				return null;
			}
			
			$status = $linkdb->testPrivileges();
			$failed = false;
			$errors = '';
			foreach ($status as $type=>$flag) {
				if (!$flag) {
					$failed = true;
					$errors .= sprintf($i18n['perm_denied'],$type).'<br />';
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
			
			$object->host = $values['host'];
			if (substr($object->host,0,7) != 'http://' && substr($object->host,0,8) != 'https://') {
				$object->host = 'http://'.$object->host;
			}
			if (substr($object->host,-1,1) == '/') {
				$object->host = substr($object->host,0,-1);
			}
		}
		return $object;
	}
}

?>