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

if (!defined("PATHOS")) exit("");

if (pathos_permissions_check('manage_site',pathos_core_makeLocation('sharedcoremodule'))) {
	pathos_lang_loadDictionary('modules','sharedcoremodule');

	$site = null;
	if (isset($_POST['id'])) $site = $db->selectObject("sharedcore_site","id=".$_POST['id']);
	$site = sharedcore_site::update($_POST,$site);
	
	if ($site == null) {
		header("Location: " . $_SERVER['HTTP_REFERER']);
		exit('Redirecting');
	}
	
	if (substr($site->path,-1,1) != "/") $site->path .= "/";
	
	if (is_really_writable($site->path)) {
		if (!isset($site->id)) { // New -- link stuff
			if (!file_exists($site->path."pathos_version.php")) {
				if (!defined("SYS_SHAREDCORE")) include_once(BASE."subsystems/sharedcore.php");
				
				$core = $db->selectobject("sharedcore_core","id=".$site->core_id);
				
				$stat = pathos_sharedcore_setup($core,$site); // If we can setup the basic environment
				if ($stat == 0) {
					$site->id = $db->insertObject($site,"sharedcore_site");
					// Update extensions table for this site.
					if (!defined("SYS_INFO")) include_once(BASE."subsystems/info.php");
					$extension = null;
					$extension->site_id = $site->id;
					$extension->locked = 1;
					
					$used = array(
						CORE_EXT_MODULE=>array(),
						CORE_EXT_SUBSYSTEM=>array(),
						CORE_EXT_THEME=>array(),
					);
					
					foreach (pathos_core_resolveDependencies(null,CORE_EXT_SYSTEM,$core->path) as $d) {
						if (!in_array($d['name'],$used[$d['type']])) {
							$extension->name = $d['name'];
							$extension->type = $d['type'];
							$db->insertObject($extension,"sharedcore_extension");
							$used[$d['type']][] = $d['name'];
						}
					}
					
					pathos_sharedcore_link($core,$site,$used);
					
					// Save database config.
					$values = array(
						"c"=>array(
							"db_engine"=>$_POST['db_engine'],
							"db_name"=>$_POST['db_name'],
							"db_user"=>$_POST['db_user'],
							"db_pass"=>$_POST['db_pass'],
							"db_host"=>$_POST['db_host'],
							"db_port"=>$_POST['db_port'],
							"db_table_prefix"=>$_POST['db_table_prefix']
						),
						"opts"=>array(),
						"activate"=>1,
						"configname"=>"Default"
					);
					
					if (!defined("SYS_CONFIG")) include_once(BASE."subsystems/config.php");
					
					pathos_config_saveConfiguration($values,$site->path);
					
					// Install database for base system
					$newdb = pathos_database_connect($_POST['db_user'],$_POST['db_pass'],$_POST['db_host'].':'.$_POST['db_port'],$_POST['db_name'],$_POST['db_engine'],true);
					$newdb->prefix = $_POST['db_table_prefix'] . '_';
					
					// Following code snipped from modules/administrationmodule/actions/installtables.php
					$dir = $site->path."datatypes/definitions";
					if (is_readable($dir)) {
						$tables = array();
						$dh = opendir($dir);
						while (($file = readdir($dh)) !== false) {
							if (is_readable("$dir/$file") && is_file("$dir/$file") && substr($file,-4,4) == ".php" && substr($file,-9,9) != ".info.php") {
								$tablename = substr($file,0,-4);
								$dd = include("$dir/$file");
								$info = null;
								if (is_readable("$dir/$tablename.info.php")) $info = include("$dir/$tablename.info.php");
								if (!$newdb->tableExists($tablename)) {
									//echo "Don't have $tablename<br />";
									$newdb->createTable($tablename,$dd,$info);
									if ($newdb->tableExists($tablename)) {
										$tables[$tablename] = TMP_TABLE_INSTALLED;
									} else {
										$tables[$tablename] = TMP_TABLE_FAILED;
									}
								} else {
									if ($newdb->alterTable($tablename,$dd,$info) == TABLE_ALTER_NOT_NEEDED) {
										$tables[$tablename] = TMP_TABLE_EXISTED;
									} else {
										$tables[$tablename] = TMP_TABLE_ALTERED;
									}
								}
							}
						}
						ksort($tables);
						// End snip
						
						pathos_lang_loadDictionary('standard','dbrecover');
						
						// Following code snipped from db_recover.php
						if ($newdb->tableIsEmpty("user")) {
							echo TR_DBRECOVER_CREATEDEFAULTADMIN.'<br />';
							$u = null;
							$u->username = "admin";
							$u->password = md5("admin");
							$u->is_admin = 1;
							$newdb->insertObject($u,"user");
						}
						
						if ($newdb->tableIsEmpty("modstate")) {
							echo TR_DBRECOVER_ACTIVATEADMINMOD.'<br />';
							$modstate = null;
							$modstate->module = "administrationmodule";
							$modstate->active = 1;
							$newdb->insertObject($modstate,"modstate");
						}
						
						if ($newdb->tableIsEmpty("section")) {
							echo TR_DBRECOVER_CREATEDEFAULTSECTION.'<br />';
							$section = null;
							$section->name = TR_DBRECOVER_DEFAULTSECTION;
							$section->public = 1;
							$section->active = 1;
							$section->rank = 0;
							$section->parent = 0;
							$sid = $newdb->insertObject($section,"section");
						}
						
						$template = new template("administrationmodule","_tableInstallSummary",$loc);
						$template->assign("status",$tables);
						$template->output();
					}
					// End snip
					
					// New site, time to go to the next place, the modules linker
					$url = URL_FULL . "index.php?module=sharedcoremodule&action=edit_site_modules&site_id=".$site->id;
					header("Location: $url");
					exit('Redirecting...');
				} else {
					switch ($stat) {
						case SHAREDCORE_ERR_LINKSRC_NOTREADABLE:
							echo TR_SHAREDCOREMODULE_ERR_NOTREADABLENOW;
							break;
						case SHAREDCORE_ERR_LINKSRC_NOTEXISTS:
							echo TR_SHAREDCOREMODULE_ERR_NOTEXISTSNOW;
							break;
					}
				}
			} else {
				$post = $_POST;
				$v = @include($this->site."pathos_version.php");
				$post['_formError'] = sprintf(TR_SHAREDCOREMODULE_ERR_DESTEXISTS,$v,$site->path);
				pathos_sessions_set("last_POST",$post);
				header("Location: " . $_SERVER['HTTP_REFERER']);
			}
		} else { // Old -- update object
			$db->updateObject($site,"sharedcore_site");
			if ($site->inactive == 0) {
				// Take them to the modules page, because that's probably why they went to edit in the first place.
				$url = URL_FULL . "index.php?module=sharedcoremodule&action=edit_site_modules&site_id=".$site->id;
				header("Location: $url");
				exit('Redirecting');
			} else {
				// For inactive sites, we can't go through the edit modules page, because it would relink and defeat the purpose of the deactivation
				pathos_flow_redirect();
			}
		}
	} else {
		$post = $_POST;
		$post['_formError'] = sprintf(TR_SHAREDCOREMODULE_ERR_DESTNOTWRITABLE,$site->path);
		pathos_sessions_set("last_POST",$post);
		header("Location: " . $_SERVER['HTTP_REFERER']);
	}
} else {
	echo SITE_403_HTML;
}

?>