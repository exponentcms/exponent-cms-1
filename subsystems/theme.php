<?php

##################################################
#
# Copyright (c) 2004-2006 OIC Group, Inc.
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

/* exdoc
 * The definition of this constant lets other parts of the system know
 * that the subsystem has been included for use.
 * @node Subsystems:Theme
 */
define("SYS_THEME",1);

$css_files = array();  // This array keeps track of all the css files that need to be included via the minify script

/* exdoc
 * @state <b>UNDOCUMENTED</b>
 * @node Undocumented
 */
function pathos_theme_includeCSS() {
	return exponent_theme_includeCSS();
}
function pathos_theme_sourceSelectorInfo() {
	return exponent_theme_sourceSelectorInfo();
}
function pathos_theme_headerInfo($section) {
	return exponent_theme_headerInfo($section);
}
function pathos_theme_showSectionalModule($module,$view,$title,$prefix = null, $pickable = false) {
	return exponent_theme_showSectionalModule($module,$view,$title,$prefix, $pickable);
}
function pathos_theme_showTopSectionalModule($module,$view,$title,$prefix = null, $pickable = false) {
	return exponent_theme_showTopSectionalModule($module,$view,$title,$prefix, $pickable);
}
function pathos_theme_showModule($module,$view = "Default",$title = "",$source = null,$pickable = false,$section = null) {
	return exponent_theme_showModule($module,$view,$title,$source,$pickable,$section);
}
function pathos_theme_inAction() {
	return exponent_theme_inAction();
}
function pathos_theme_canViewPage() {
	return exponent_theme_canViewPage();
}
function pathos_theme_setFlow() {
	return exponent_theme_setFlow();
}
function pathos_theme_main() {
	return exponent_theme_main();
}
function pathos_theme_runAction() {
	return exponent_theme_runAction();
}
function pathos_theme_goDefaultSection() {
	return exponent_theme_goDefaultSection();
}
function pathos_theme_mainContainer() {
	return exponent_theme_mainContainer();
}
function pathos_theme_getSubthemes($include_default = true,$theme = DISPLAY_THEME) {
	return exponent_theme_getSubthemes($include_default,$theme);
}
//End Pathos Compatibility

function exponent_theme_loadExpDefaults() {
	global $css_files;

	$commondir = 'themes/common/css';

        if (is_dir($commondir) && is_readable($commondir) ) {
        	$dh = opendir($commondir);
                while (($cssfile = readdir($dh)) !== false) {
               		$filename = $commondir.'/'.$cssfile;
                        if ( is_file($filename) && substr($filename,-4,4) == ".css") {
				$css_files[] = URL_FULL.$commondir.'/'.$cssfile;
				if (is_readable('themes/'.DISPLAY_THEME_REAL.'/css/'.$cssfile)) {
			        	$css_files[] = URL_FULL.'themes/'.DISPLAY_THEME_REAL.'/css/'.$cssfile;
			        } elseif (is_readable('themes/'.DISPLAY_THEME_REAL.'/'.$cssfile)) {
			                $css_files[] = URL_FULL.'themes/'.DISPLAY_THEME_REAL.'/'.$cssfile;
				}
                        }
                }
        }
}
function exponent_theme_resetCSS() {
	global $css_files;
	$css_files = array_merge(array(URL_FULL."external/yui/build/reset-fonts-grids/reset-fonts-grids.css"), $css_files);
}

function exponent_theme_loadYUICSS($files=array()) {
	global $css_files;
	foreach ($files as $file) {
		$css_files[] = URL_FULL."external/yui/build/assets/skins/sam/".$file.'.css';
	}
}

function exponent_theme_loadRequiredCSS() {
	global $css_files;

        $requireddir = 'themes/common/css/required/';

	if (is_dir($requireddir) && is_readable($requireddir) ) {
		$dh = opendir($requireddir);
                while (($cssfile = readdir($dh)) !== false) {
			$filename = $requireddir.$cssfile;
			if ( is_file($filename) && substr($filename,-4,4) == ".css") {
				$css_files[] = URL_FULL.$requireddir.$cssfile;
			}
		}
	}
}

function exponent_theme_loadAll() {
	exponent_theme_resetCSS();
        exponent_theme_loadYUICSS(array('menu'));
        exponent_theme_loadExpDefaults();
	exponent_theme_includeCSSFiles();	
}

function exponent_theme_minifyCSS() {
	if (css_file_needs_rebuilt()) {
		global $css_files;

		eDebug($css_files);
		exit();
		// Load the Minify library if needed.                 
		include_once(BASE.'external/minify/minify.php');                 
		// Create new Minify objects.                 
		$minifyCSS = new Minify(Minify::TYPE_CSS);                         
	
		// Specify the files to be minified. Full URLs are allowed as long as they point                 
		// to the same server running Minify. 
        	$minifyCSS->addFile($css_files);
        
		// Establish the file where we will build the compiled CSS file
        	$compiled_file = fopen(BASE.'tmp/css/exp-styles-min.css', 'w');
		//	eDebug($minifyCSS->combine());

		fwrite($compiled_file, $minifyCSS->combine());
		fclose($compiled_file);
	}
}

/* exdoc
 * @function include_css()
 * checks for a css document to include on your page.
 * checks first in your theme/css/ folder, then in the 
 * root of your theme, then in themes/common/css/
 * @node Subsystems:Theme
 */

function exponent_theme_includeCSSFiles($files = array()) {
	global $css_files;

	if (empty($files)) {
		global $css_files;

		$cssdirs = array('themes/'.DISPLAY_THEME_REAL.'/css/', 'themes/'.DISPLAY_THEME_REAL.'/');
		
		foreach ($cssdirs as $cssdir) {
		        if (is_dir($cssdir) && is_readable($cssdir) ) {
        		        $dh = opendir($cssdir);
                		while (($cssfile = readdir($dh)) !== false) {
                        		$filename = $cssdir.$cssfile;
	                        	if ( is_file($filename) && substr($filename,-4,4) == ".css") {
        	                        	$css_files[] = URL_FULL.$cssdir.$cssfile;
                	        	}
                		}
        		}
		}
	} else {	
		foreach ($files as $file) {
			if (is_readable('themes/'.DISPLAY_THEME_REAL.'/css/'.$file)) {
				$css_files[] = URL_FULL.'themes/'.DISPLAY_THEME_REAL.'/css/'.$file;
			} elseif (is_readable('themes/'.DISPLAY_THEME_REAL.'/'.$file)) {
				$css_files[] = URL_FULL.'themes/'.DISPLAY_THEME_REAL.'/'.$file;
			}
		}
	}
	exponent_theme_minifyCSS();
}

function css_file_needs_rebuilt() {
	if (DEVELOPMENT > 0 || !is_readable(BASE.'tmp/css/exp-styles-min.css')) {
		return true;
	} else {
		return false;
	}
}
/* exdoc
 * Prints the HTML for the Source Selector header table.  This is required
 * of all themes, so that the source selector allows users to browse to Archived
 * content.
 * @node Subsystems:Theme
 */
function exponent_theme_sourceSelectorInfo() {
	if (defined("SOURCE_SELECTOR") || defined("CONTENT_SELECTOR")) {
		$i18n = exponent_lang_loadFile('subsystems/theme.php');
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
function exponent_theme_headerInfo($section /*this variable is now deprecated*/) {
	global $sectionObj; //global section object created from exponent_core_initializeNavigation() function
	$langinfo = include(BASE.'subsystems/lang/'.LANG.'.php');
	$str = '';

	// load all the required CSS files for the user.
	exponent_theme_loadRequiredCSS();

	if ($sectionObj != null) {
		$str = '<title>'.($sectionObj->page_title == "" ? SITE_TITLE : $sectionObj->page_title)."</title>\r\n";
		$str .= "\t".'<meta http-equiv="Content-Type" content="text/html; charset='.$langinfo['charset'].'" />'."\n";
		$str .= "\t".'<meta name="Generator" content="Exponent Content Management System - '.EXPONENT_VERSION_MAJOR.'.'.EXPONENT_VERSION_MINOR.'.'.EXPONENT_VERSION_REVISION.'.'.EXPONENT_VERSION_TYPE.'" />' . "\n";
		$str .= "\t".'<meta name="Keywords" content="'.($sectionObj->keywords == "" ? SITE_KEYWORDS : $sectionObj->keywords) . '" />'."\n";
		$str .= "\t".'<meta name="Description" content="'.($sectionObj->description == "" ? SITE_DESCRIPTION : $sectionObj->description) . '" />'."\n";
		$str .= "\t".'<!--[if IE 6]><style type="text/css"> img { behavior: url(external/png-opacity.htc); } body { behavior: url(external/csshover.htc); }</style><![endif]-->'."\n";
		$str .= "\t".'<script type="text/javascript" src="'.PATH_RELATIVE.'exponent.js.php"></script>'."\r\n";
		$str .= "\t".'<link rel="stylesheet" type="text/css" href="'.URL_FULL.'tmp/css/exp-styles-min.css">'."\r\n";	
	}
	return $str;
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
function exponent_theme_showSectionalModule($module,$view,$title,$prefix = null, $pickable = false) {
	global $db;

	if ($prefix == null) $prefix = "@section";

	$src = $prefix;

	if (exponent_sessions_isset("themeopt_override")) {
		$config = exponent_sessions_get("themeopt_override");
		if (in_array($module,$config['ignore_mods'])) return;
		$src = $config['src_prefix'].$prefix;
		$section = null;
	} else {
		global $sectionObj;
		//$last_section = exponent_sessions_get("last_section");
		//$section = $db->selectObject("section","id=".$last_section);
		$src .= $sectionObj->id;
	}


	exponent_theme_showModule($module,$view,$title,$src,$pickable,$sectionObj->id);
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
function exponent_theme_showTopSectionalModule($module,$view,$title,$prefix = null, $pickable = false) {
	global $db;

	if ($prefix == null) $prefix = "@section";
	$last_section = exponent_sessions_get("last_section");

	$section = $db->selectObject("section","id=".$last_section);
	// Loop until we find the top level parent.
	while ($section->parent != 0) $section = $db->selectObject("section","id=".$section->parent);

	exponent_theme_showModule($module,$view,$title,$prefix.$section->id,$pickable,$section);
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
function exponent_theme_showModule($module,$view = "Default",$title = "",$source = null,$pickable = false,$section = null) {
	if (!AUTHORIZED_SECTION && $module != 'navigationmodule' && $module != 'loginmodule') {
		return;
	}
	global $db, $sectionObj;
	// Ensure that we have a section
	//FJD - changed to $sectionObj
	if ($sectionObj == null) {
		$section_id = exponent_sessions_get('last_section');
		if ($section_id == null) {
			$section_id = SITE_DEFAULT_SECTION;
		}
		$sectionObj = $db->selectObject('section','id='.$section_id);
		//$section->id = $section_id;
	}
	if ($module == "loginmodule" && defined("PREVIEW_READONLY") && PREVIEW_READONLY == 1) return;

	if (exponent_sessions_isset("themeopt_override")) {
		$config = exponent_sessions_get("themeopt_override");
		if (in_array($module,$config['ignore_mods'])) return;
	}
	$loc = exponent_core_makeLocation($module,$source."");

	if ($db->selectObject("locationref","module='$module' AND source='".$loc->src."'") == null) {
		$locref = null;
		$locref->module = $module;
		$locref->source = $loc->src;
		$locref->internal = "";
		$locref->refcount = 1000;
		$db->insertObject($locref,"locationref");
		if ($sectionObj != null) {
			$locref->section = $sectionObj->id;
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
			$i18n = exponent_lang_loadFile('subsystems/theme.php');
			echo sprintf($i18n['mod_not_found'],$module);
		}
	}
}

/* exdoc
 * Checks to see if the page is currently in an action.  Useful only if the theme does not use the exponent_theme_main() function
 * Returns whether or not an action should be run.
 * @node Subsystems:Theme
 */
function exponent_theme_inAction() {
	return (isset($_REQUEST['action']) && isset($_REQUEST['module']));
}

/* exdoc
 * Checks to see if the user is authorized to view the current section.
 * Retursn whether or not the user is authorized.
 * @node Subsystems:Theme
 */
function exponent_theme_canViewPage() {
	return AUTHORIZED_SECTION;
	/*
	global $db;
	$last_section = exponent_sessions_get("last_section");
	$section = $db->selectObject("section","id=".$last_section);
	if ($section && navigationModule::canView($section)) {
		$sloc = exponent_core_makeLocation("navigationmodule","",$section->id);
		return exponent_permissions_check("view",$sloc);
	} else return true;
	*/
}

/*  exdoc
 * Looks at the attributes of the current section and properly calls exponent_flow_set
 * @node Subsystems:Theme
 */
function exponent_theme_setFlow() {
	if ((!defined("SOURCE_SELECTOR") || SOURCE_SELECTOR == 1) && (!defined("CONTENT_SELECTOR") || CONTENT_SELECTOR == 1)) {
		global $db;
		$last_section = exponent_sessions_get("last_section");
		$section = $db->selectObject("section","id=".$last_section);

		if ($section && $section->public == 0) {
			exponent_flow_set(SYS_FLOW_PROTECTED,SYS_FLOW_SECTIONAL);
		} else if ($section && $section->public == 1) {
			exponent_flow_set(SYS_FLOW_PUBLIC,SYS_FLOW_SECTIONAL);
		}
	}
}

/* exdoc
 * Takes care of all the specifics of either showing a sectional container or running an action.
 * @node Subsystems:Theme
 */
function exponent_theme_main() {
	global $db, $user;

	if ((!defined("SOURCE_SELECTOR") || SOURCE_SELECTOR == 1) && (!defined("CONTENT_SELECTOR") || CONTENT_SELECTOR == 1)) {
		$last_section = exponent_sessions_get("last_section");
		$section = $db->selectObject("section","id=".$last_section);
		// View authorization will be taken care of by the runAction and mainContainer functions
		if (exponent_theme_inAction()) {
			exponent_theme_runAction();
		} else if ($section == null) {
			exponent_theme_goDefaultSection();
		} else {
			exponent_theme_mainContainer();
		}
	} else {
		if (isset($_REQUEST['module'])) {
			include_once(BASE."modules/containermodule/actions/orphans_content.php");
		} else {
			$i18n = exponent_lang_loadFile('subsystems/theme.php');
			echo $i18n['select_module'];
		}
	}
}

/* exdoc
 * Runs the approriate action, by looking at the $_REQUEST variable.
 * @node Subsystems:Theme
 */
function exponent_theme_runAction() {

	if (exponent_theme_inAction()) {
		if (!AUTHORIZED_SECTION) {
			echo SITE_403_HTML;
		//	return;
		}
		if (exponent_sessions_isset("themeopt_override")) {
			$config = exponent_sessions_get("themeopt_override");
			echo "<a class='mngmntlink sitetemplate_mngmntlink' href='".$config['mainpage']."'>".$config['backlinktext']."</a><br /><br />";
		}

		global $db, $user;

		$loc = null;
		$loc->mod = $_REQUEST['module'];
		$loc->src = (isset($_REQUEST['src']) ? $_REQUEST['src'] : "");
		$loc->int = (isset($_REQUEST['int']) ? $_REQUEST['int'] : "");

		$actfile = "/" . $_REQUEST['module'] . "/actions/" . $_REQUEST['action'] . ".php";
		if (isset($_REQUEST['_common'])) $actfile = "/common/actions/" . $_REQUEST['action'] . ".php";

		if (is_readable(BASE."themes/".DISPLAY_THEME_REAL."/modules".$actfile)) {
			include_once(BASE."themes/".DISPLAY_THEME_REAL."/modules".$actfile);
		} elseif (is_readable(BASE.'modules/'.$actfile)) {
			include_once(BASE.'modules/'.$actfile);
		} else {
			$i18n = exponent_lang_loadFile('subsystems/theme.php');
			echo SITE_404_HTML . '<br /><br /><hr size="1" />';
			echo sprintf($i18n['no_action'],strip_tags($_REQUEST['module']),strip_tags($_REQUEST['action']));
			echo '<br />';
		}
	}
}

function exponent_theme_showAction($module, $action, $src, $params="") {
	
	global $db, $user;

        $loc = null;
        $loc->mod = $module;
        $loc->src = (isset($src) ? $src : "");
        $loc->int = (isset($int) ? $int : "");

        $actfile = "/" . $module . "/actions/" . $action . ".php";
        //if (isset($['_common'])) $actfile = "/common/actions/" . $_REQUEST['action'] . ".php";

        if (is_readable(BASE."themes/".DISPLAY_THEME_REAL."/modules".$actfile)) {
	        include_once(BASE."themes/".DISPLAY_THEME_REAL."/modules".$actfile);
        } elseif (is_readable(BASE.'modules/'.$actfile)) {
                include_once(BASE.'modules/'.$actfile);
        } else {
                $i18n = exponent_lang_loadFile('subsystems/theme.php');
                echo SITE_404_HTML . '<br /><br /><hr size="1" />';
                echo sprintf($i18n['no_action'],strip_tags($_REQUEST['module']),strip_tags($_REQUEST['action']));
                echo '<br />';
        }
}

/* exdoc
 * Redirect User to Default Section
 * @node Subsystems:Theme
 */
function exponent_theme_goDefaultSection() {
	$last_section = exponent_sessions_get("last_section");
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
 * Useful only if theme does not use exponent_theme_main
 *
 * @param bool $public Whether or not the page is public.
 * @node Subsystems:Theme
 */
function exponent_theme_mainContainer() {
	if (!AUTHORIZED_SECTION) {
		// Set this so that a login on an Auth Denied page takes them back to the previously Auth-Denied page
		exponent_flow_set(SYS_FLOW_PROTECTED,SYS_FLOW_SECTIONAL);
		echo SITE_403_HTML;
		return;
	}

	if (PUBLIC_SECTION) exponent_flow_set(SYS_FLOW_PUBLIC,SYS_FLOW_SECTIONAL);
	else exponent_flow_set(SYS_FLOW_PROTECTED,SYS_FLOW_SECTIONAL);

#	if (exponent_sessions_isset("themeopt_override")) {
#		$config = exponent_sessions_get("themeopt_override");
		exponent_theme_showSectionalModule("containermodule","Default","","@section");
#	} else {
#		exponent_theme_showSectionalModule("containermodule","Default","","@section");
#	}
}

/* exdoc
 * @state <b>UNDOCUMENTED</b>
 * @node Undocumented
 */
function exponent_theme_getSubthemes($include_default = true,$theme = DISPLAY_THEME) {
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
