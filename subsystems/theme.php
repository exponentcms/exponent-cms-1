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
/**
 * Theme Subsystem
 *
 * Provides an abstraction layer for theme builders, to encapsulate
 * the PHP parts of a theme into well-defined functions.
 *
 * @package		Subsystems
 * @subpackage	Theme
 *
 * @author		James Hunt
 * @copyright		2004 James Hunt and the OIC Group, Inc.
 * @version		0.95
 */

/**
 * SYS flag
 *
 * The definition of this constant lets other parts of the system know 
 * that the subsystem has been included for use.
 */
define("SYS_THEME",1);

function pathos_theme_includeCSS() {
	$dh = opendir(BASE."modules");
	while (($moddir = readdir($dh)) !== false) {
		if (is_dir(BASE."modules/$moddir/css")) {
			$cssdh = opendir(BASE."modules/$moddir/css");
			while (($file = readdir($cssdh)) !== false) {
				if (substr($file,-4,4) == ".css") {
					echo "\t\t<link rel=\"stylesheet\" href=\"".PATH_RELATIVE."modules/$moddir/css/$file\" />\n";
				}
			}
		}
	}
}

/**
 * Output Source Selector HTML
 *
 * Prints the HTML for the Source Selector header table.  This is required
 * of all themes, so that the source selector allows users to browse to Archived
 * content.
 */
function pathos_theme_sourceSelectorInfo() {
	if (defined("SOURCE_SELECTOR") || defined("CONTENT_SELECTOR")) {
		?>
		<script type="text/javascript">
		window.resizeTo(800,600);
		</script>
		<table cellspacing="0" cellpadding="5" width="100%" border="0">
			<tr>
				<td width="70%">
					<b>Site Content Selector</b>
				</td>
				<td width="30%" align="right">
					[ <a class="mngmntlink" href="orphan_source_selector.php">Archived Content</a> ]
				</td>
			</tr>
		</table>
		<table cellspacing="0" cellpadding="5" width="100%" border="0">
			<tr>
				<td colspan="2" style="background-color: #999; color: #fff; border-bottom: 1px solid #000; padding-bottom: .5em;">
					<i>To use existing content, simply browse to the section the content appears in, and select the module.</i>
				</td>
			</tr>
		</table>
		<?php
	}
}

function pathos_theme_metaInfo($section) {
	$str = '<meta name="Generator" content="Exponent Content Management System" />' . "\n";
	$str .= "\t\t".'<meta name="Keywords" content="'.($section->keywords == "" ? SITE_KEYWORDS : $section->keywords) . '" />'."\n";
	$str .= "\t\t".'<meta name="Description" content="'.($section->description == "" ? SITE_DESCRIPTION : $section->description) . '" />'."\n";
	return $str;
}

/**
 * Display a Section-sensitive Module
 *
 * Calls the necessary methods to show a specific module, in a section-sensitive way.
 *
 * @param string $module The classname of the module to display
 * @param string $view The name of the view to display the module with
 * @param string $title The title of the module (support is view-dependent)
 * @param string $prefix The prefix of the module's source.  The current section id will be appended to this
 * @param bool $pickable Whether or not the module is pickable in the Source Picer.
 */

function pathos_theme_showSectionalModule($module,$view,$title,$prefix = null, $pickable = false) {
	global $db;
	
	if ($prefix == null) $prefix = "@section";
	
	$last_section = pathos_sessions_get("last_section");
	$section = $db->selectObject("section","id=".$last_section);
	
	pathos_theme_showModule($module,$view,$title,$prefix.$section->id,$pickable);
}

function pathos_theme_showTopSectionalModule($module,$view,$title,$prefix = null, $pickable = false) {
	global $db;
	
	if ($prefix == null) $prefix = "@section";
	
	$last_section = pathos_sessions_get("last_section");
	if ($prefix == null) $prefix = "@section";
	
	$section = $db->selectObject("section","id=".$last_section);
	while ($section->parent !=0) $section = $db->selectObject("section","id=".$section->parent);
	
	pathos_theme_showModule($module,$view,$title,$prefix.$section->id,$pickable);
}

/**
 * Display a Module
 *
 * Calls the necessary methods to show a specific module
 *
 * @param string $module The classname of the module to display
 * @param string $view The name of the view to display the module with
 * @param string $title The title of the module (support is view-dependent)
 * @param string $source The source of the module.
 * @param bool $pickable Whether or not the module is pickable in the Source Picer.
 */
function pathos_theme_showModule($module,$view = "Default",$title = "",$source = null,$pickable = false) {
	if ($module == "loginmodule" && defined("PREVIEW_READONLY") && PREVIEW_READONLY == 1) return;
	
	if (pathos_sessions_isset("themeopt_override")) {
		$config = pathos_sessions_get("themeopt_override");
		if (in_array($module,$config['ignore_mods'])) return;
		if (substr($source,0,8) == "@section") $source = "@section";
		$loc = pathos_core_makeLocation($module,$config['src_prefix'].$source."");
	} else {
		$loc = pathos_core_makeLocation($module,$source."");
	}
	global $db;
	if ($db->selectObject("locationref","module='$module' AND source='".$loc->src."'") == null) {
		$locref = null;
		$locref->module = $module;
		$locref->source = $loc->src;
		$locref->internal = "";
		$locref->refcount = 1000;
		$db->insertObject($locref,"locationref");
	}
	if (defined("SELECTOR") && call_user_func(array($module,"hasSources"))) {
		containermodule::wrapOutput($module,$view,$loc,$title);
	} else {
		call_user_func(array($module,"show"),$view,$loc,$title);
	}
}

/**
 * Check Page State
 *
 * Checks to see if the page is currently in an action.  Useful only if the theme does not use the pathos_theme_main() function
 *
 * @return bool Whether or not an action should be run.
 */
function pathos_theme_inAction() {
	return (isset($_REQUEST['action']) && isset($_REQUEST['module']));
}

/**
 * Check Use View Authorization
 *
 * Checks to see if the user is authorized to view the current section.
 *
 * @return bool Whether or not the user is authorized.
 */
function pathos_theme_canViewPage() {
	global $db;
	$last_section = pathos_sessions_get("last_section");
	$section = $db->selectObject("section","id=".$last_section);
	if ($section && $section->public == 0) {
		$sloc = pathos_core_makeLocation("navigationmodule","",$section->id);
		return pathos_permissions_check("view",$sloc);
	} else return true;
}

/**
 * Intelligently call pathos_flow_set
 *
 * Looks at the attributes of the current section and properly calls pathos_flow_set
 */
function pathos_theme_setFlow() {
	if ((!defined("SOURCE_SELECTOR") || SOURCE_SELECTOR == 1) && (!defined("CONTENT_SELECTOR") || CONTENT_SELECTOR == 1)) {
		global $db;
		$last_section = pathos_sessions_get("last_section");
		$section = $db->selectObject("section","id=".$last_section);
		
		if ($section && $section->public == 0) {
			pathos_flow_set(SYS_FLOW_PROTECTED,SYS_FLOW_SECTIONAL);
		} else if ($section && $section->public == 1) {
			pathos_flow_set(SYS_FLOW_PUBLIC,SYS_FLOW_SECTIONAL);
		}
	}
}

/**
 * Main Theme Execution
 *
 * Takes care of all the specifics of either showing a sectional container or running an action.
 */
function pathos_theme_main() {
	global $db, $user;
	
	if ((!defined("SOURCE_SELECTOR") || SOURCE_SELECTOR == 1) && (!defined("CONTENT_SELECTOR") || CONTENT_SELECTOR == 1)) {
		$last_section = pathos_sessions_get("last_section");
		$section = $db->selectObject("section","id=".$last_section);
		
		if (pathos_theme_inAction()) {
			pathos_theme_runAction();
		} else if ($section == null) {
			pathos_theme_goDefaultSection();
		} else if ($section->public == 0) {
			$sloc = pathos_core_makeLocation("navigationmodule","",$section->id);
			if (!pathos_permissions_check("view",$sloc)) {
				echo SITE_403_HTML;
				// Set as protected, so that a successfull login will bring us back here.
				pathos_flow_set(SYS_FLOW_PROTECTED,SYS_FLOW_SECTIONAL);
			} else {
				// Authorized.
				pathos_theme_mainContainer(false);
			}
		} else {
			pathos_theme_mainContainer(true);
		}
	} else {
		if (isset($_REQUEST['module'])) {
			include_once(BASE."modules/containermodule/actions/orphans_content.php");
		} else echo "Select a module";
	}
}

/**
 * Run an Action
 *
 * Runs the approriate action, by looking at the $_REQUEST variable.
 */
function pathos_theme_runAction() {
	if (pathos_theme_inAction()) {
		if (pathos_sessions_isset("themeopt_override")) {
			$config = pathos_sessions_get("themeopt_override");
			echo "<a class='mngmntlink sitetemplate_mngmntlink' href='".$config['mainpage']."'>".$config['backlinktext']."</a><br /><br />";
		}
	
		global $db, $user;
		
		$loc = null;
		$loc->mod = $_REQUEST['module'];
		$loc->src = (isset($_REQUEST['src']) ? $_REQUEST['src'] : "");
		$loc->int = (isset($_REQUEST['int']) ? $_REQUEST['int'] : "");
		
		$actfile = "/" . $_REQUEST['module'] . "/actions/" . $_REQUEST['action'] . ".php";
		if (isset($_REQUEST['_common'])) $actfile = "/common/actions/" . $_REQUEST['action'] . ".php";
		
		if (is_readable(BASE."modules/".$actfile)) include_once(BASE."modules/".$actfile);
		else echo SITE_404_HTML . "<br /><br /><Hr size='1' />No such module action : " . $_REQUEST['module'] . ":" . $_REQUEST['action'] . "<br />";
	}
}

/**
 * Redirect User to Default Section
 */
function pathos_theme_goDefaultSection() {
	$last_section = pathos_sessions_get("last_section");
	if (defined("SITE_DEFAULT_SECTION") && SITE_DEFAULT_SECTION != $last_section) {
		header("Location: http://".$_SERVER['HTTP_HOST'] . PATH_RELATIVE."index.php?section=".SITE_DEFAULT_SECTION);
		exit();
	} else {
		global $db;
		$section = $db->selectObject("section","public = 1 AND active = 1"); // grab first section, go there
		if ($section) {
			header("Location: http://".$_SERVER['HTTP_HOST'] . PATH_RELATIVE."index.php?section=".$section->id);
			exit();
		}
		else echo SITE_404_HTML;
	}
}

/**
 * Shows the Main Container
 *
 * Useful only if theme does not use pathos_theme_main
 *
 * @param bool $public Whether or not the page is public.
 */
function pathos_theme_mainContainer($public = true) {
	if ($public) pathos_flow_set(SYS_FLOW_PUBLIC,SYS_FLOW_SECTIONAL);
	else pathos_flow_set(SYS_FLOW_PROTECTED,SYS_FLOW_SECTIONAL);
		
	if (pathos_sessions_isset("themeopt_override")) {
		$config = pathos_sessions_get("themeopt_override");
		pathos_theme_showSectionalModule("containermodule","Default","","@section");
	} else {
		pathos_theme_showSectionalModule("containermodule","Default","","@section");
	}
}




function pathos_theme_getSubthemes($theme = DISPLAY_THEME) {
	$base = BASE."themes/$theme/subthemes";
	$dh = opendir($base);
	while (($s = readdir($dh)) !== false) {
		if (substr($s,-4,4) == ".php" && is_file($base."/$s") && is_readable($base."/$s")) {
			$subs[substr($s,0,-4)] = substr($s,0,-4);
		}
	}
	$subs[""] = "[[ None ]]";
	uksort($subs,"strnatcmp");
	return $subs;
}

?>