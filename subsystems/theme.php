<?php

##################################################
#
# Copyright (c) 2004-2005 James Hunt and the OIC Group, Inc.
# All Changes as of 6/1/05 Copyright 2005 James Hunt
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
/* exdoc
 * The definition of this constant lets other parts of the system know 
 * that the subsystem has been included for use.
 * @node Subsystems:Theme
 */
define("SYS_THEME",1);

/* exdoc
 * @state <b>UNDOCUMENTED</b>
 * @node Undocumented
 */
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

/* exdoc
 * Prints the HTML for the Source Selector header table.  This is required
 * of all themes, so that the source selector allows users to browse to Archived
 * content.
 * @node Subsystems:Theme
 */
function pathos_theme_sourceSelectorInfo() {
	if (defined("SOURCE_SELECTOR") || defined("CONTENT_SELECTOR")) {
		$i18n = pathos_lang_loadFile('subsystems/theme.php');
		?>
		<script type="text/javascript">
		window.resizeTo(800,600);
		</script>
		<table cellspacing="0" cellpadding="5" width="100%" border="0">
			<tr>
				<td width="70%">
					<b><?php echo $i18n['selector']; ?></b>
				</td>
				<td width="30%" align="right">
					[ <a class="mngmntlink" href="orphan_source_selector.php"><?php echo $i18n['archived_content']; ?></a> ]
				</td>
			</tr>
		</table>
		<table cellspacing="0" cellpadding="5" width="100%" border="0">
			<tr>
				<td colspan="2" style="background-color: #999; color: #fff; border-bottom: 1px solid #000; padding-bottom: .5em;">
					<i><?php echo $i18n['selector_desc']; ?></i>
				</td>
			</tr>
		</table>
		<?php
	}
}

/* exdoc
 * @state <b>UNDOCUMENTED</b>
 * @node Undocumented
 */
function pathos_theme_headerInfo($section) {
	$langinfo = include(BASE.'subsystems/lang/'.LANG.'.php');
	$str = '<title>'.($section->page_title == "" ? SITE_TITLE : $section->page_title)."</title>\r\n";
	$str .= "\t\t".'<meta http-equiv="Content-Type" content="text/html; charset='.$langinfo['charset'].'" />'."\n";
	$str .= "\t\t".'<meta name="Generator" content="Exponent Content Management System" />' . "\n";
	$str .= "\t\t".'<meta name="Keywords" content="'.($section->keywords == "" ? SITE_KEYWORDS : $section->keywords) . '" />'."\n";
	$str .= "\t\t".'<meta name="Description" content="'.($section->description == "" ? SITE_DESCRIPTION : $section->description) . '" />'."\n";
	$str .= "\t\t".'<style type="text/css"> img { behavior: url(external/png-opacity.htc); } body { behavior: url(external/csshover.htc); }</style>'."\n";
	$str .= "\t\t".'<script type="text/javascript" src="'.PATH_RELATIVE.'pathos.js.php"></script>'."\r\n";
	return $str;
}


function pathos_theme_metaInfo($section) {
	return "<!-- pathos_theme_metaInfo() is DEPRECATED.  Use pathos_theme_headerInfo instead-->\r\n\t\t".pathos_theme_headerInfo($section);
}

/* exdoc
 * Calls the necessary methods to show a specific module, in a section-sensitive way.
 *
 * @param string $module The classname of the module to display
 * @param string $view The name of the view to display the module with
 * @param string $title The title of the module (support is view-dependent)
 * @param string $prefix The prefix of the module's source.  The current section id will be appended to this
 * @param bool $pickable Whether or not the module is pickable in the Source Picer.
 * @node Subsystems:Theme
 */
function pathos_theme_showSectionalModule($module,$view,$title,$prefix = null, $pickable = false) {
	global $db;
	
	if ($prefix == null) $prefix = "@section";
	
	$src = $prefix;
	
	if (pathos_sessions_isset("themeopt_override")) {
		$config = pathos_sessions_get("themeopt_override");
		if (in_array($module,$config['ignore_mods'])) return;
		$src = $config['src_prefix'].$prefix;
		$section = null;
	} else {
		global $section;
		//$last_section = pathos_sessions_get("last_section");
		//$section = $db->selectObject("section","id=".$last_section);
		$src .= $section->id;
	}
	
	
	pathos_theme_showModule($module,$view,$title,$src,$pickable,$section);
}

/* exdoc
 * Calls the necessary methods to show a specific module in such a way that the current
 * section displays the same content as its top-level parent and all of the top-level parent's
 * children, grand-children, grand-grand-children, etc.
 *
 * @param string $module The classname of the module to display
 * @param string $view The name of the view to display the module with
 * @param string $title The title of the module (support is view-dependent)
 * @param string $prefix The prefix of the module's source.  The current section id will be appended to this
 * @param bool $pickable Whether or not the module is pickable in the Source Picer. 
 * @node Subsystems:Theme
 */
function pathos_theme_showTopSectionalModule($module,$view,$title,$prefix = null, $pickable = false) {
	global $db;
	
	if ($prefix == null) $prefix = "@section";
	$last_section = pathos_sessions_get("last_section");
	
	$section = $db->selectObject("section","id=".$last_section);
	// Loop until we find the top level parent.
	while ($section->parent != 0) $section = $db->selectObject("section","id=".$section->parent);
	
	pathos_theme_showModule($module,$view,$title,$prefix.$section->id,$pickable,$section);
}

/* exdoc
 * Calls the necessary methods to show a specific module
 *
 * @param string $module The classname of the module to display
 * @param string $view The name of the view to display the module with
 * @param string $title The title of the module (support is view-dependent)
 * @param string $source The source of the module.
 * @param bool $pickable Whether or not the module is pickable in the Source Picer.
 * @node Subsystems:Theme
 */
function pathos_theme_showModule($module,$view = "Default",$title = "",$source = null,$pickable = false,$section = null) {
	if (!AUTHORIZED_SECTION && $module != 'navigationmodule' && $module != 'loginmodule') {
		return;
	}
	global $db;
	// Ensure that we have a section
	if ($section == null) {
		$section_id = pathos_sessions_get('last_section');
		if ($section_id == null) {
			$section_id = SITE_DEFAULT_SECTION;
		}
		$section = $db->selectObject('section','id='.$section_id);
	}
	if ($module == "loginmodule" && defined("PREVIEW_READONLY") && PREVIEW_READONLY == 1) return;
	
	if (pathos_sessions_isset("themeopt_override")) {
		$config = pathos_sessions_get("themeopt_override");
		if (in_array($module,$config['ignore_mods'])) return;
	}
	$loc = pathos_core_makeLocation($module,$source."");
		
	if ($db->selectObject("locationref","module='$module' AND source='".$loc->src."'") == null) {
		$locref = null;
		$locref->module = $module;
		$locref->source = $loc->src;
		$locref->internal = "";
		$locref->refcount = 1000;
		$db->insertObject($locref,"locationref");
		if ($section != null) {
			$locref->section = $section->id;
			$locref->is_original = 1;
			$db->insertObject($locref,'sectionref');
		}
	}
	if (defined("SELECTOR") && call_user_func(array($module,"hasSources"))) {
		containermodule::wrapOutput($module,$view,$loc,$title);
	} else {
		if (is_callable(array($module,"show"))) {
			call_user_func(array($module,"show"),$view,$loc,$title);
		} else {
			$i18n = pathos_lang_loadFile('subsystems/theme.php');
			echo sprintf($i18n['mod_not_found'],$module);
		}
	}
}

/* exdoc
 * Checks to see if the page is currently in an action.  Useful only if the theme does not use the pathos_theme_main() function
 * Returns whether or not an action should be run.
 * @node Subsystems:Theme
 */
function pathos_theme_inAction() {
	return (isset($_REQUEST['action']) && isset($_REQUEST['module']));
}

/* exdoc
 * Checks to see if the user is authorized to view the current section.
 * Retursn whether or not the user is authorized.
 * @node Subsystems:Theme
 */
function pathos_theme_canViewPage() {
	return AUTHORIZED_SECTION;
	/*
	global $db;
	$last_section = pathos_sessions_get("last_section");
	$section = $db->selectObject("section","id=".$last_section);
	if ($section && navigationModule::canView($section)) {
		$sloc = pathos_core_makeLocation("navigationmodule","",$section->id);
		return pathos_permissions_check("view",$sloc);
	} else return true;
	*/
}

/*  exdoc
 * Looks at the attributes of the current section and properly calls pathos_flow_set
 * @node Subsystems:Theme
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

/* exdoc
 * Takes care of all the specifics of either showing a sectional container or running an action.
 * @node Subsystems:Theme
 */
function pathos_theme_main() {
	global $db, $user;
	
	if ((!defined("SOURCE_SELECTOR") || SOURCE_SELECTOR == 1) && (!defined("CONTENT_SELECTOR") || CONTENT_SELECTOR == 1)) {
		$last_section = pathos_sessions_get("last_section");
		$section = $db->selectObject("section","id=".$last_section);
		// View authorization will be taken care of by the runAction and mainContainer functions
		if (pathos_theme_inAction()) {
			pathos_theme_runAction();
		} else if ($section == null) {
			pathos_theme_goDefaultSection();
		} else {
			pathos_theme_mainContainer();
		}
	} else {
		if (isset($_REQUEST['module'])) {
			include_once(BASE."modules/containermodule/actions/orphans_content.php");
		} else {
			$i18n = pathos_lang_loadFile('subsystems/theme.php');
			echo $i18n['select_module'];
		}
	}
}

/* exdoc
 * Runs the approriate action, by looking at the $_REQUEST variable.
 * @node Subsystems:Theme
 */
function pathos_theme_runAction() {
	
	if (pathos_theme_inAction()) {
		if (!AUTHORIZED_SECTION) {
			echo SITE_403_HTML;
		//	return;
		}
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
		
		if (is_readable(BASE.'modules/'.$actfile)) {
			include_once(BASE.'modules/'.$actfile);
		} else {
			$i18n = pathos_lang_loadFile('subsystems/theme.php');
			echo SITE_404_HTML . '<br /><br /><hr size="1" />';
			echo sprintf($i18n['no_action'],strip_tags($_REQUEST['module']),strip_tags($_REQUEST['action']));
			echo '<br />';
		}
	}
}

/* exdoc
 * Redirect User to Default Section
 * @node Subsystems:Theme
 */
function pathos_theme_goDefaultSection() {
	$last_section = pathos_sessions_get("last_section");
	if (defined("SITE_DEFAULT_SECTION") && SITE_DEFAULT_SECTION != $last_section) {
		header("Location: ".URL_FULL."index.php?section=".SITE_DEFAULT_SECTION);
		exit();
	} else {
		global $db;
		$section = $db->selectObject("section","public = 1 AND active = 1"); // grab first section, go there
		if ($section) {
			header("Location: ".URL_FULL."index.php?section=".$section->id);
			exit();
		} else {
			echo SITE_404_HTML;
		}
	}
}

/* exdoc
 * Useful only if theme does not use pathos_theme_main
 *
 * @param bool $public Whether or not the page is public.
 * @node Subsystems:Theme
 */
function pathos_theme_mainContainer() {
	if (!AUTHORIZED_SECTION) {
		// Set this so that a login on an Auth Denied page takes them back to the previously Auth-Denied page
		pathos_flow_set(SYS_FLOW_PROTECTED,SYS_FLOW_SECTIONAL);
		echo SITE_403_HTML;
		return;
	}
	
	if (PUBLIC_SECTION) pathos_flow_set(SYS_FLOW_PUBLIC,SYS_FLOW_SECTIONAL);
	else pathos_flow_set(SYS_FLOW_PROTECTED,SYS_FLOW_SECTIONAL);
		
#	if (pathos_sessions_isset("themeopt_override")) {
#		$config = pathos_sessions_get("themeopt_override");
		pathos_theme_showSectionalModule("containermodule","Default","","@section");
#	} else {
#		pathos_theme_showSectionalModule("containermodule","Default","","@section");
#	}
}

/* exdoc
 * @state <b>UNDOCUMENTED</b>
 * @node Undocumented
 */
function pathos_theme_getSubthemes($include_default = true,$theme = DISPLAY_THEME) {
	$base = BASE."themes/$theme/subthemes";
	// The array of subthemes.  If the theme has no subthemes directory,
	// or the directory is not readable by the web server, this empty array
	// will be returned (Unless the caller wanted us to include the default layout)
	$subs = array();
	if ($include_default == true) {
		// Caller wants us to include the default layout.
		$subs[''] = DEFAULT_VIEW; // Not really its intended use, but it works.
	}
	
	if (is_readable($base)) {
		// subthemes directory exists and is readable by the web server.  Continue on.
		$dh = opendir($base);
		// Read out all entries in the THEMEDIR/subthemes directory
		while (($s = readdir($dh)) !== false) {
			if (substr($s,-4,4) == '.php' && is_file($base."/$s") && is_readable($base."/$s")) {
				// Only readable .php files are allowed to be subtheme files.
				$subs[substr($s,0,-4)] = substr($s,0,-4);
			}
		}
		// Sort the subthemes by their keys (which are the same as the values)
		// using a natural string comparison funciton (PHP built-in)
		uksort($subs,'strnatcmp');
	}
	return $subs;
}

?>