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
/* exdoc
 * The definition of this constant lets other parts
 * of the system know that the Configuration Subsystem
 * has been included for use.
 * @node Subsystems:Config
 */
define('SYS_CONFIG',1);

/* exdoc
 * Uses magical regular expressions voodoo to pull the
 * actuall define() calls out of a configuration PHP file,
 * and return them in an associative array, for use in
 * viewing and analyzing configs.  Returns an associative
 * array of constant names => values
 *
 * @param string $configname Configuration Profile name
 * @node Subsystems:Config
 */
function pathos_config_parse($configname,$site_root = null) {
// Last argument added in 0.96, for shared core.  Default it to the old hard-coded value
	if ($site_root == null) $site_root = BASE;
	
	if (!defined('SYS_FORMS')) include_once(BASE.'subsystems/forms.php');
	pathos_forms_initialize();
	// We don't actually use the forms subsystem, but the .structure.php files do.
	
	if ($configname == '') $file = $site_root.'conf/config.php';
	else $file = $site_root."conf/profiles/$configname.php";
	$options = array();
	$valid = array();
	if (is_readable($file)) $options = pathos_config_parseFile($file);
	if (is_readable($site_root.'conf/extensions')) {
		$dh = opendir($site_root.'conf/extensions');
		while (($file = readdir($dh)) !== false) {
			if (substr($file,-13,13) == '.defaults.php') {
				$options = array_merge(pathos_config_parseFile($site_root."conf/extensions/$file"),$options);
			} else if (substr($file,-14,14) == '.structure.php') {
				$tmp = include($site_root."conf/extensions/$file");
				$valid = array_merge($valid,array_keys($tmp[1]));
			}
		}
	}
	
	pathos_forms_cleanup();
	
	$valid = array_flip($valid);
	
	foreach ($options as $key=>$value) {
		if (!isset($valid[$key])) unset($options[$key]);
	}
	
	return $options;
}

/* exdoc
 * Looks through the source of a given configuration PHP file,
 * and pulls out (by mysterious regular expressions) the define()
 * calls and returns those values.  Returns an associative array
 * of constant names => values
 *
 * @param string $file The full path to the file to parse.
 * @node Subsystems:Config
 */
function pathos_config_parseFile($file) {
	$options = array();
	foreach (file($file) as $line) {
		$line = trim(preg_replace(array("/^.*define\([\"']/","/[#].*$/"),"",$line));
		if ($line != "" && substr($line,0,2) != "<?" && substr($line,-2,2) != "?>") {
			$line = str_replace(array("<?php","?>","<?",),"",$line);
			$opts = split("[\"'],",$line);
			if (count($opts) == 2) {
				if (substr($opts[1],0,1) == '"' || substr($opts[1],0,1) == "'") $opts[1] = substr($opts[1],1,-3);
				else $opts[1] = substr($opts[1],0,-2);
				if (substr($opts[0],-5,5) == "_HTML") {
					$opts[1] = eval("return ".$opts[1].";");
				}
				$options[$opts[0]] = str_replace("\\'","'",$opts[1]);
			}
		}
	}
	return $options;
}

/* exdoc
 * This function looks through all of the available configuration
 * extensions, and generates a form object consisting of each
 * extension's form part.  This can then be used to edit the full
 * site configuration.  Returns a form object intended for editting the profile.
 *
 * @param string $configname The name of the configuration profile,
 *    for filling in default values.
 * @node Subsystems:Config
 */
function pathos_config_configurationForm($configname,$database=false) {
	// $configname = "" for active config
	if (is_readable(BASE."conf/extensions")) {
		global $user;
		$options = pathos_config_parse($configname);
		
		if (!defined("SYS_FORMS")) include_once(BASE."subsystems/forms.php");
		pathos_forms_initialize();
		
		pathos_lang_loadDictionary('subsystems','config');
		pathos_lang_loadDictionary('standard','core');
		
		$form = new form();
	
		$form->register(null,'',new htmlcontrol('<a name="config_top"></a><h3>'.TR_CONFIGSUBSYSTEM_FORMTITLE.'</h3>'));
		$form->register('configname',TR_CONFIGSUBSYSTEM_PROFILE,new textcontrol($configname));
		$form->register('activate',TR_CONFIGSUBSYSTEM_ACTIVATE,new checkboxcontrol((!defined('CURRENTCONFIGNAME') || CURRENTCONFIGNAME==$configname)));
	
		$sections = array();
	
		$dh = opendir(BASE.'conf/extensions');
		while (($file = readdir($dh)) !== false) {
			if (is_readable(BASE."conf/extensions/$file") && substr($file,-14,14) == ".structure.php") {
				$arr = include(BASE."conf/extensions/$file");
				// Check to see if the current user is a super admin, and only include database if so
				if (substr($file,0,-14) != 'database' || $user->is_admin == 1) {
					$form->register(null,'',new htmlcontrol("<a name='config_".count($sections)."'></a><div style='font-weight: bold; margin-top: 1.5em; border-top: 1px solid black; border-bottom: 1px solid black; background-color: #ccc; font-size: 12pt;'>" . $arr[0] . "</div><a href='#config_top'>Top</a>"));
					$sections[] = '<a href="#config_'.count($sections).'">'.$arr[0].'</a>';
					foreach ($arr[1] as $directive=>$info) {
						
						if ($info['description'] != '') {
							$form->register(null,'',new htmlcontrol('<br /><br />'.$info['description'],false));
						}
						if (is_a($info['control'],"checkboxcontrol")) {
							$form->meta("opts[$directive]",1);
							$info['control']->default = $options[$directive];
							$info['control']->flip = true;
							$form->register("o[$directive]",'<b>'.$info['title'].'</b>',$info['control']);
						} else {
							if (isset($options[$directive])) $info["control"]->default = $options[$directive];
							$form->register("c[$directive]",'<b>'.$info['title'].'</b>',$info['control'],$info['description']);
						}
					}
				}
			}
		}
		$form->registerAfter('activate',null,'',new htmlcontrol('<hr size="1" />'.implode('&nbsp;&nbsp;|&nbsp;&nbsp;',$sections)));
		$form->register('submit','',new buttongroupcontrol(TR_CORE_SAVE,'',TR_CORE_CANCEL));
		
		pathos_forms_cleanup();
		
		return $form;
	}
}

/* exdoc
 * Processes the POSTed data from the configuration form
 * object generated by pathos_config_configurationForm, and writes
 * a bunch of define() statements to the profiles config file.
 *
 * @param array $values The _POST array to pull configuration data from.
 * @node Subsystems:Config
 */
function pathos_config_saveConfiguration($values,$site_root=null) {
// Last argument added in 0.96, for shared core.  Default it to the old hard-coded value
	if ($site_root == null) $site_root = BASE;

	$configname = str_replace(" ","_",$values['configname']);

	$original_config = pathos_config_parse($configname,$site_root);

	$str = "<?php\n\n";
	foreach ($values['c'] as $directive=>$value) {
		$directive = strtoupper($directive);
		
		// Because we may not have all config options in the POST,
		// we need to unset the ones we do have from the original config.
		unset($original_config[$directive]);
		
		$str .= "define(\"$directive\",";
		if (substr($directive,-5,5) == "_HTML") {
			$value = htmlentities(stripslashes($value)); // slashes added by POST
			$str .= "html_entity_decode('$value')";
		}
		else if (is_int($value)) $str .= $value;
		else $str .= "'".str_replace("'","\'",$value)."'";
		$str .= ");\n";
	}
	foreach ($values['opts'] as $directive=>$val) {
		$directive = strtoupper($directive);
		
		// Because we may not have all config options in the POST,
		// we need to unset the ones we do have from the original config.
		unset($original_config[$directive]);
		
		$str .= "define(\"$directive\"," . (isset($values['o'][$directive]) ? 1 : 0) . ");\n";
	}
	// Now pick up all of the unspecified values
	// THIS MAY SCREW UP on checkboxes.
	foreach ($original_config as $directive=>$value) {
		$str .= "define(\"$directive\",";
		if (substr($directive,-5,5) == "_HTML") {
			$value = htmlentities(stripslashes($value)); // slashes added by POST
			$str .= "html_entity_decode('$value')";
		}
		else if (is_int($value)) $str .= $value;
		else $str .= "'".str_replace("'","\'",$value)."'";
		$str .= ");\n";
	}
	$str .= "\n?>";
	
	if ($configname != "") {
		// Wishing to save
		if (	(file_exists($site_root."conf/profiles/$configname.php") && is_writable($site_root."conf/profiles/$configname.php")) ||
			is_writable($site_root."conf/profiles")) {
			
			$fh = fopen($site_root."conf/profiles/$configname.php","w");
			fwrite($fh,$str);
			fclose($fh);
		} else echo "Unable to write profile configuration.<br />";
	}
	
	if (isset($values['activate']) || $configname == "") {
		if (
			(file_exists($site_root."conf/config.php") && is_writable($site_root."conf/config.php")) ||
			is_writable($site_root."conf")) {
			
			$fh = fopen($site_root."conf/config.php","w");
			fwrite($fh,$str);
			fwrite($fh,"\n<?php\ndefine(\"CURRENTCONFIGNAME\",\"$configname\");\n?>\n");
			fclose($fh);
		} else echo TR_CONFIGSUBSYSTEM_CONFNOTWRITABLE.'<br />';
	}
}

/* exdoc
 * Takes care of setting the appropriate template variables
 * to be used when viewing a profile or set of profiles.
 * Returns the initializes template.
 *
 * @param template $template The template object to assign to.
 * @param string $configname The name of the configuration profile.
 * @node Subsystems:Config
 */
function pathos_config_outputConfigurationTemplate($template,$configname) {
	if (is_readable(BASE."conf/extensions")) {
		pathos_lang_loadDictionary('subsystems','config');
	
		$categorized = array();
		$options = pathos_config_parse($configname);
		
		$dh = opendir(BASE."conf/extensions");
		while (($file = readdir($dh)) !== false) {
			if (is_readable(BASE."conf/extensions/$file") && substr($file,-14,14) == ".structure.php") {
				$arr = include(BASE."conf/extensions/$file");
				$categorized[$arr[0]] = array();
				foreach ($arr[1] as $directive=>$info) {
					if (is_a($info["control"],"passwordcontrol")) {
						$info["value"] = TR_CONFIGSUBSYSTEM_HIDDEN;
					} else if (is_a($info["control"],"checkboxcontrol")) {
						$info["value"] = (isset($options[$directive]) ? ($options[$directive]?"yes":"no") : "no");
					} else if (is_a($info["control"],"dropdowncontrol") && isset($options[$directive])) {
						$info["value"] = @$info["control"]->items[$options[$directive]];
					} else $info["value"] = (isset($options[$directive]) ? $options[$directive] : "");
					unset($info["control"]);
					
					$categorized[$arr[0]][$directive] = $info;
				}
			}
		}
		$template->assign("configuration",$categorized);
	}
	return $template;
}

/* exdoc
 * Looks through the conf/profiles directory, and finds all of
 * the configuration profiles in existence.  This function also
 * performs some minor name-mangling, to make the Profile Names
 * more user friendly. Returns an array of Profile names.
 *
 * @node Subsystems:Config
 */
function pathos_config_profiles() {
	$profiles = array();
	if (is_readable(BASE."conf/profiles")) {
		$dh = opendir(BASE."conf/profiles");
		while (($file = readdir($dh)) !== false) {
			if (is_readable(BASE."conf/profiles/$file") && substr($file,-4,4) == ".php") {
				$name = substr($file,0,-4);
				$profiles[$name] = str_replace("_"," ",$name);
			}
		}
	}
	return $profiles;
}

/* exdoc
 * Deletes a configuration profile from the conf/profiles
 * directory.
 *
 * @param string $profile The name of the Profile to remove.
 * @node Subsystems:Config
 */
function pathos_config_deleteProfile($profile) {
	if (file_exists(BASE."conf/profiles/$profile.php")) {
		// do checking with realpath
		unlink(BASE."conf/profiles/$profile.php");
	}
}

/* exdoc
 * Activates a Configuration Profile.
 *
 * @param string $profile The name of the Profile to activate.
 * @node Subsystems:Config
 */
function pathos_config_activateProfile($profile) {
	if (is_readable(BASE."conf/profiles/$profile.php") && is_writable(BASE."conf/config.php")) {
		copy(BASE."conf/profiles/$profile.php",BASE."conf/config.php");
		$fh = fopen(BASE."conf/config.php","a");
		fwrite($fh,"\n<?php\ndefine(\"CURRENTCONFIGNAME\",\"$profile\");\n?>");
		fclose($fh);
	}
}

/* exdoc
 * Parse Drop Down options from a file.
 * @param string $dropdown_name The name of the dropdown type.  The name of the
 *   file will be retrieved by adding .dropdown as a suffix, and searching the conf/data directory.
 * @node Subsystems:Config
 */
function pathos_config_dropdownData($dropdown_name) {
	$array = array();
	if (is_readable(BASE."conf/data/$dropdown_name.dropdown")) {
		$t = array();
		foreach (file(BASE."conf/data/$dropdown_name.dropdown") as $l) {
			$l = trim($l);
			if ($l != "" && substr($l,0,1) != "#") {
				$go = count($t);
				
				$t[] = trim($l);
				
				if ($go) {
					$array[$t[0]] = $t[1];
					$t = array();
				}
			}
		}
	}
	return $array;
}

?>